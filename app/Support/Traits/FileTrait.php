<?php

namespace App\Support\Traits;

use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidPathException;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait FileTrait
{
    use FilenameTrait {
        convertInvalidPathCharacters as private;
    }

    /**
     * @param array $files
     * @param string $search
     * @param array $flags
     * @return array
     */
    protected function getSimilarFiles(array $files, string $search, array $flags = []): array
    {
        $matchingFiles = preg_grep('#' . $search . '#' . implode('', $flags), $files);
        return $matchingFiles === false ? [] : array_values($matchingFiles);
    }

    /**
     * @param array $files
     * @param string $fileSize
     * @return array
     */
    protected function getSpecificThumbnail(array $files, string $fileSize): array
    {
        $searchFiles = collect();

        foreach ($files as $file) {
            if (preg_match('#[^\-\s]*-[0-9]+(?:-' . preg_quote($fileSize) . ')?\.[a-z]+$#i', $file)) {
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $parts = explode('-', $filename);

                // it means it doesn't upload from uploader plugin
                // NOTE:
                //   -it's not a correct check to know it uploaded from uploader plugin or not,
                //    we just assume if there is no part-1, it is not from uploader
                if (!isset($parts[1])) continue;

                // create a default size
                $size = $parts[2] ?? self::ORIGINAL;

                $searchFiles->add([
                    'file' => $file,
                    'name' => $parts[0] . '-' . $parts[1],
                    'size' => $size,
                ]);
            }
        }

        return $searchFiles->groupBy('name')->map(function ($items) use ($fileSize) {
            $filtered = $items->filter(function ($item) use ($fileSize) {
                return $item['size'] === $fileSize;
            });

            if ($filtered->count()) return $filtered->first();
            return $items->first();
        })->pluck('file')->toArray();
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
                $imagePath = Storage::disk($disk)->path($storedPath);
                $size = self::VALID_SIZES[$size] ?? self::VALID_SIZES[self::MEDIUM];
                $img = Image::make($imagePath)->resize($size[0], $size[1], function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($imagePath);
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
        return $this->getNormalizedPath($name, true) . '-' . time();
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
     * Parameters to send, has the following description:
     *   if you have "a/b/c/d.jpg" or "a/b/c" and want to move/copy to "e/f/g/h.jpg" or "e/f/g" then
     *   (accordingly below variables expected):
     *     <code>
     *       "$path" ->  "a/b/c" or "a/b"
     *       "$source" -> "d.jpg" or "c"
     *       "$destination" -> "e/f/g/h.jpg" or "e/f/g" // destination must be another specific independent path
     *     </code>
     *
     * @param string $path
     * @param string $source
     * @param string $destination
     * @param string $disk
     * @param string $operation - accepted "move" or "copy" string literals
     * @return bool
     * @throws InvalidPathException
     */
    protected function moveOrCopy(
        string $path,
        string $source,
        string $destination,
        string $disk,
        string $operation
    ): bool
    {
        $diskStorage = Storage::disk($disk);
        $sourcePath = trim($path . '/' . trim($source, '\\/'), '\\/');
        $destinationPath = trim($destination, '\\/');

        $sourcePathInfo = pathinfo($sourcePath);
        $destinationPathInfo = pathinfo($destinationPath);

        if (empty($sourcePathInfo['dirname'])) {
            $sourcePathInfo['dirname'] = '';
        } else {
            $sourcePathInfo['dirname'] = $this->normalizeDirname($sourcePathInfo['dirname']);
            $sourcePathInfo['dirname'] = $this->getNormalizedPath($sourcePathInfo['dirname']);
        }

        if (empty($destinationPathInfo['dirname'])) {
            $destinationPathInfo['dirname'] = '';
        } else {
            $destinationPathInfo['dirname'] = $this->normalizeDirname($destinationPathInfo['dirname']);
            $destinationPathInfo['dirname'] = $this->getNormalizedPath($destinationPathInfo['dirname']);
        }

        if (empty($sourcePathInfo['extension']) && !empty($destinationPathInfo['extension'])) {
            throw new InvalidPathException();
        }

        DB::beginTransaction();

        $res = true;
        $where = new WhereBuilder('file_manager');

        // files MUST be got before any database operation,
        // because after change in database, there is no file to get with "fileExists" method
        // ("fileExists" check database internally apparently)
        $files = $this->fileExists(filePath: $sourcePath, disk: $disk, getFiles: true, getAllVariants: true);

        if (!empty($sourcePathInfo['extension'])) { // if it is a file

            $attributes = [
                'path' => !empty($destinationPathInfo['extension'])
                    ? $destinationPathInfo['dirname']
                    : $destinationPath,
            ];

            if ($operation === FileRepositoryInterface::OPERATION_MOVE) {
                if (!empty($destinationPathInfo['extension'])) {
                    $attributes['name'] = $destinationPathInfo['filename'];
                    $attributes['extension'] = $destinationPathInfo['extension'];
                }

                $where
                    ->reset()
                    ->whereEqual('disk', $disk)
                    ->whereEqual('name', $sourcePathInfo['filename'])
                    ->whereEqual('extension', $sourcePathInfo['extension'])
                    ->whereEqual('path', $sourcePathInfo['dirname']);

                if ($this->exists($where->build())) {
                    $res = $res && $this->updateWhere($attributes, $where->build());
                }
            } elseif ($operation === FileRepositoryInterface::OPERATION_COPY) {
                $attributes['name'] = $sourcePathInfo['filename'];
                $attributes['extension'] = $sourcePathInfo['extension'];

                $res = $this->create($attributes) instanceof Model;
            }

            if (!$res) {
                DB::rollBack();
                return false;
            }

            foreach ($files as $file) {
                $fileInfo = pathinfo($file);
                $fileParts = explode('-', $fileInfo['basename']);
                if (!empty($destinationPathInfo['extension'])) $fileParts[0] = $destinationPathInfo['filename'];
                $newFile = implode('-', $fileParts);

                $fileExtension = pathinfo($newFile, PATHINFO_EXTENSION);
                $extPos = mb_strrpos($newFile, $fileExtension ?: '');
                if ($extPos !== false && !empty($destinationPathInfo['extension'])) {
                    $newFile = mb_substr($newFile, 0, $extPos) . $destinationPathInfo['extension'];
                }

                $from = $sourcePathInfo['dirname'] . '/' . $fileInfo['basename'];
                $to = $destinationPathInfo['dirname'] . '/' . $newFile;
                if (empty($sourcePathInfo['extension']))
                    $from = $sourcePath . '/' . $fileInfo['basename'];
                if (empty($destinationPathInfo['extension']))
                    $to = $destinationPath . '/' . $newFile;

                if ($operation === FileRepositoryInterface::OPERATION_MOVE) {
                    $res = $res && $diskStorage->move($from, $to);
                } elseif ($operation === FileRepositoryInterface::OPERATION_COPY) {
                    $res = $res && $diskStorage->copy($from, $to);
                }

                if (!$res) break;
            }

        } else { // if it is a directory

            // it is illegal to move/copy a parent directory into child directory
            if (mb_strpos($destinationPath, $sourcePath) !== false)
                throw new InvalidPathException('پوشه والد نمی‌تواند به پوشه فرزند انتقال پیدا کند.');
            // it is impossible to move a directory to a file!
            if (!empty($destinationPathInfo['extension']))
                throw new InvalidPathException('انتقال پوشه به فایل غیر ممکن است!');

            $where
                ->reset()
                ->whereLike('path', $sourcePath, '{value}%');

            $attributes = [
                'path' => $destinationPath,
            ];

            if ($this->exists($where->build())) {
                $res = $res && $this->updateWhere($attributes, $where->build());
            }

            if (!$res) {
                DB::rollBack();
                return false;
            }

            if ($operation === FileRepositoryInterface::OPERATION_MOVE) {
                $res = $res && $diskStorage->move($sourcePath, $destinationPath);
            } elseif ($operation === FileRepositoryInterface::OPERATION_COPY) {
                $res = $res && $diskStorage->copy($sourcePath, $destinationPath);
            }

        }

        if (!$res) {

            // rollback all move/copy files
            $files = $this->fileExists(filePath: $destinationPath, disk: $disk, getFiles: true, getAllVariants: true);
            if ($operation === FileRepositoryInterface::OPERATION_MOVE) {
                foreach ($files as $file) {
                    $fileInfo = pathinfo($file);
                    $fileParts = explode('-', $fileInfo['basename']);
                    if (!empty($sourcePathInfo['extension'])) $fileParts[0] = $sourcePathInfo['filename'];
                    $oldFile = implode('-', $fileParts);

                    $fileExtension = pathinfo($oldFile, PATHINFO_EXTENSION);
                    $extPos = mb_strrpos($oldFile, $fileExtension ?: '');
                    if ($extPos !== false && !empty($sourcePathInfo['extension'])) {
                        $oldFile = mb_substr($oldFile, 0, $extPos) . $sourcePathInfo['extension'];
                    }

                    $from = $destinationPathInfo['dirname'] . '/' . $fileInfo['basename'];
                    $to = $sourcePathInfo['dirname'] . '/' . $oldFile;
                    if (empty($destinationPathInfo['extension']))
                        $from = $destinationPath . '/' . $fileInfo['basename'];
                    if (empty($sourcePathInfo['extension']))
                        $to = $sourcePath . '/' . $oldFile;

                    $diskStorage->move($from, $to);

                    if (!$res) break;
                }
            } elseif ($operation === FileRepositoryInterface::OPERATION_COPY) {
                if (!empty($destinationPathInfo['extension'])) {
                    $diskStorage->delete(
                        array_map(
                            fn($item) => !empty($destinationPathInfo['extension'])
                                ? $destinationPathInfo['dirname'] . '/' . $item
                                : $destinationPath . '/' . $item,
                            $files
                        )
                    );
                } else {
                    $diskStorage->deleteDirectory(
                        !empty($destinationPathInfo['extension'])
                            ? $destinationPathInfo['dirname']
                            : $destinationPath
                    );
                }
            }

            // rollback MUST be done after file rollback
            DB::rollBack();
            return false;

        }

        DB::commit();
        return true;
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

        $path = trim($path, '\\/');
        $file = trim($this->getNormalizedPath($file), '\\/');
        $fileExt = pathinfo($file, PATHINFO_EXTENSION) ?? null;

        // remove from database
        $fullPath = $path . '/' . $file;
        $where = new WhereBuilder('file_manager');
        $where->whereEqual('disk', $disk);

        // to prevent left/right extra slash in case of directory path
        // to match database records
        if (empty($fileExt)) $fullPath = trim($fullPath, '/');

        if (!empty($fileExt)) {
            $removeFiles = $this->fileExists(
                filePath: $path . '/' . $file,
                disk: $disk,
                getFiles: true,
                getAllVariants: true
            );
            $where->whereEqual('full_path', $fullPath);
        } else {
            $where->whereLike('path', $fullPath, '{value}%');
        }

        DB::beginTransaction();

        $success = $this->deleteWhere($where->build());

        if (!empty($fileExt))
            $success = $success && $diskStorage->delete($removeFiles);
        else
            $success = $diskStorage->deleteDirectory($fullPath);

        if ($success) {
            DB::commit();
            return true;
        }

        DB::rollBack();
        return false;
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
        $path = $this->getNormalizedPath($path);
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
        if (!in_array(strtolower($disk), self::STORAGE_DISKS)) {
            if ($throw) {
                throw new InvalidDiskException();
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Filesystem $storage
     * @param $path
     * @return int
     */
    protected function getSizeRecursive(Filesystem $storage, $path): int
    {
        $totalSize = 0;

        if (is_dir($storage->path($path))) {
            $files = $storage->allFiles($path);
            foreach ($files as $file) {
                $totalSize += $storage->size($file);
            }
        } else {
            $totalSize = $storage->size($path);
        }

        return $totalSize;
    }

    /**
     * "$isLocal" variable is for "$path" that is an actual file and has
     * xxx-timestamp[-size].ext format and don't want to convert '-' character with '_'
     * to prevent internal error(the error is not showing files because it can't find them!)
     *
     * @param string $path
     * @param bool $isLocal
     * @return string
     */
    protected function getNormalizedPath(string $path, bool $isLocal = false): string
    {
        $path = $this->normalizeDirname($path);
        $info = pathinfo($path);
        $filename = $info['filename'];
        $dirname = $this->normalizeDirname($info['dirname'] ?? '');
        $extension = $info['extension'] ?? null;

        $parts = array_filter(explode('/', !empty($extension) ? $dirname : $path), 'strlen');
        $absolutes = [];

        foreach ($parts as $part) {
            if ('.' === $part) continue;
            if ('..' === $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $this->convertInvalidPathCharacters(trim($part));
            }
        }

        if (!empty($extension)) {
            $filename = $this->convertInvalidPathCharacters($filename, $isLocal);
            $extension = $this->convertInvalidPathCharacters($extension);
            $absolutes[] = $filename . '.' . $extension;
        }

        return implode('/', $absolutes);
    }

    /**
     * @param string $extension
     * @return bool
     */
    protected function isSupportedImage(string $extension): bool
    {
        $arr = ['jpeg', 'png', 'jpg'];
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

    /**
     * @param string|null $dirname
     * @return string
     */
    protected function normalizeDirname(?string $dirname): string
    {
        return trim(str_replace('\\', '/', $dirname ?? ''));
    }
}
