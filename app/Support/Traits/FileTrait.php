<?php

namespace App\Support\Traits;

use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidPathException;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileTrait
{
    /**
     * @param array $files
     * @param string $search
     * @param array $flags
     * @return array
     */
    protected function getSimilarFiles(array $files, string $search, array $flags = []): array
    {
        $matchingFiles = preg_grep('/' . $search . '/' . implode('', $flags), $files);
        return $matchingFiles === false ? [] : $matchingFiles;
    }

    /**
     * @param array $files
     * @param string $fileSize
     * @return array
     */
    protected function getSpecificThumbnail(array $files, string $fileSize): array
    {
        $searchFiles = [];
        foreach ($files as $file) {
            if (
                preg_match('/[^\-]*\-[0-9](\-' . preg_quote($fileSize) . ')?\.[a-z]+$/i', $file) ||
                preg_match('/[^\-]*\-[0-9]\.[a-z]+$/i', $file)
            ) {
                $parts = explode('-', $file);

                // if file is not added by uploader,
                // it doesn't have correct generated file name
                if (!isset($parts[0]) && !isset($parts[1])) continue;

                foreach ($searchFiles as $key => $searchFile) {
                    if ($searchFile == $file) continue;

                    $searchParts = explode('-', $searchFile);

                    // if search file is not added by uploader,
                    // it doesn't have correct generated file name
                    if (!isset($searchParts[0]) && !isset($searchParts[1])) {
                        unset($searchFiles[$key]);
                        continue;
                    }

                    // 1. if count of parts and search parts are equals,
                    //    then it means they are same or different files
                    //    and not a thumbnail for scanned file
                    // 2. if first part of parts and search parts are not equals,
                    //    it means they are not same file with different thumbnail
                    if (
                        count($parts) == count($searchParts) ||
                        $parts[0] != $searchParts[0]
                    ) continue;

                    // if $parts[0] == $searchParts[0]:
                    //    [
                    //      equal first part means they are same file
                    //      and must check further for other parts
                    //    ]
                    if (isset($parts[2])) {
                        unset($searchFiles[$key]);
                        $searchFiles[] = $file;
                    }
                    if (isset($searchParts[2])) continue;
                }
            }
        }

        return $searchFiles;
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @param string $originalFileName
     * @return bool
     */
    protected function uploadThumbnailsIfSupported(
        UploadedFile $file,
        string       $path,
        string       $disk,
        string       $originalFileName
    ): bool
    {
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();
        $thumbs = [
            self::SMALL => $originalFileName . '-' . self::SMALL,
            self::MEDIUM => $originalFileName . '-' . self::MEDIUM,
            self::LARGE => $originalFileName . '-' . self::LARGE,
        ];

        if ($this->isSupportedImage($extension)) {
            foreach ($thumbs as $size => $thumb) {
                $storedPath = $file->storeAs($path, $thumb . '.' . $extension, $disk);
                $size = self::$validSizes[$size] ?? self::$validSizes[self::MEDIUM];
                $img = Image::make($storedPath)->resize($size[0], $size[1], function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($storedPath);
            }
        }

        return true;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getUploadingFileName(string $name): string
    {
        return $this->getNormalizedPath($name) . '-' . time();
    }

    /**
     * @param string $path
     * @param string $disk
     * @return void
     * @throws InvalidPathException
     */
    protected function ensurePathExists(string $path, string $disk): void
    {
        $diskStorage = Storage::disk($disk);
        if (!$diskStorage->exists($path) || !$diskStorage->makeDirectory($path))
            throw new InvalidPathException();
    }

    /**
     * @param array $paths
     * @param string $destination
     * @param string $disk
     * @param bool $duplicate
     * @return void
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    protected function moveOrCopy(array $paths, string $destination, string $disk, bool $duplicate = false): void
    {
        $destination = $this->getNormalizedPath($destination);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($destination, $disk);

        $diskStorage = Storage::disk($disk);

        foreach ($paths as $path) {
            $normalizedPath = $this->getNormalizedPath($path);

            $isDir = false;

            if ($diskStorage->exists($normalizedPath)) {
                $disPath = $diskStorage->path($normalizedPath);
                if (is_dir($disPath)) $isDir = true;
            }

            if (!$isDir) {
                $files = $this->fileExists($normalizedPath, $disk, true);
                foreach ($files as $file) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);

                    // Do not move files that are exists in destination
                    if (!$this->fileExists($destination . '/' . $filename, $disk)) {
                        if ($duplicate)
                            $diskStorage->copy($file, $destination);
                        else
                            $diskStorage->move($file, $destination);
                    }
                }

                $info = pathinfo($normalizedPath);

                // It needs to update in database too
                if ($duplicate) {
                    $this->create([
                        'name' => $info['filename'],
                        'extension' => $info['extension'],
                        'path' => $destination,
                    ]);
                } else {
                    $where = new WhereBuilder('file_manager');
                    $where->whereEqual('name', $info['filename'])
                        ->whereEqual('path', $info['dirname']);
                    $this->updateWhere([
                        'path' => $destination,
                    ], $where->build());
                }
            } else {
                if ($duplicate)
                    $diskStorage->copy($normalizedPath, $destination);
                else
                    $diskStorage->move($normalizedPath, $destination);
            }
        }
    }

    /**
     * @param $file
     * @param $path
     * @param $disk
     * @return bool
     */
    protected function removeFile($file, $path, $disk): bool
    {
        $diskStorage = Storage::disk($disk);

        $file = $this->getNormalizedPath($file);
        $removeFiles = $this->fileExists($path . '/' . rtrim($file, '\\/'), $disk, true);
        $success = $diskStorage->delete($removeFiles);

        // Remove from database too
        $where = new WhereBuilder('file_manager');
        $where->whereEqual('name', $file)
            ->whereEqual('path', $path);

        return $success && $this->deleteWhere($where->build());
    }

    /**
     * @param string $path
     * @param string $disk
     * @param bool $throw
     * @return bool
     * @throws InvalidPathException
     */
    public function checkPathExists(string $path, string $disk, bool $throw = true): bool
    {
        $diskStorage = Storage::disk($disk);
        if (!$diskStorage->exists($path) || !is_dir($diskStorage->path($path))) {
            if ($throw)
                throw new InvalidPathException('مسیر پوشه انتخاب شده نامعتبر می‌باشد.');
            else
                return false;
        }
        return true;
    }

    /**
     * @param string $disk
     * @param bool $throw
     * @return bool
     * @throws InvalidDiskException
     */
    public function checkDiskValidation(string $disk, bool $throw = true): bool
    {
        if (!in_array(strtolower($disk), self::$storageDisks)) {
            if ($throw)
                throw new InvalidDiskException();
            else
                return false;
        }
        return true;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getNormalizedPath(string $path): string
    {
        $arr = explode('/', $path);
        $newName = '';
        foreach ($arr as $item) {
            $tmp = strtolower(str_replace(['?', ' ', '-', '$', '<', '>', '&', '{', '}', '*', '\\', '/', ':', ';', ',', "'", '"'], '_', trim($item)));
            $newName .= '/' . Str::slug($tmp);
            $newName = rtrim($newName, '/');
        }
        return trim($newName, '/');
    }

    /**
     * @param string $extension
     * @return bool
     */
    protected function isSupportedImage(string $extension): bool
    {
        $arr = ['jpeg', 'png', 'jpg', 'gif'];
        return in_array($extension, $arr);
    }

    /**
     * helper to format bytes to other units
     *
     * @param int $size
     * @param int $precision
     * @return string
     */
    protected function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * converts KB,MB,GB,TB,PB,EB,ZB,YB to bytes
     *
     * @param string $from
     * @return float|int|string
     *
     * @example 1KB => 1000 (bytes)
     */
    protected function convertToBytes(string $from): float|int|string
    {
        $number = (int)substr($from, 0, -2);

        return match (strtoupper(substr($from, -2))) {
            'KB' => $number * 1024,
            'MB' => $number * pow(1024, 2),
            'GB' => $number * pow(1024, 3),
            'TB' => $number * pow(1024, 4),
            'PB' => $number * pow(1024, 5),
            'EB' => $number * pow(1024, 6),
            'ZB' => $number * pow(1024, 7),
            'YB' => $number * pow(1024, 8),
            default => $from,
        };
    }
}
