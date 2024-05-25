<?php

namespace App\Repositories;

use App\Enums\Times\TimeFormatsEnum;
use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Http\Requests\Filters\FileListFilter;
use App\Models\FileManager;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\FileTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use function pathinfo;

class FileRepository extends Repository implements FileRepositoryInterface
{
    use FileTrait;

    protected bool $useSoftDeletes = false;

    public function __construct(FileManager $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     * @throws InvalidFileException
     */
    public function savePath(string $fullPath, array $attributes = []): ?Model
    {
        $fullPath = $this->getNormalizedPath($fullPath);

        $info = pathinfo($fullPath);
        $filename = $info['filename'];
        $extension = $info['extension'] ?? null;

        if (empty($extension)) {
            throw new InvalidFileException('تنها فایل‌ها قابلیت ذخیره شدن در پایگاه داده را دارا می‌باشند.');
        }

        $attributes = $attributes + [
                'name' => $filename,
                'extension' => $extension,
                'path' => $fullPath,
            ];

        return $this->create($attributes);
    }

    /**
     * @inheritDoc
     * @throws FileDuplicationException
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function upload(string $path, UploadedFile $file, string $disk, bool $overwrite = false): bool
    {
        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $name = $file->getClientOriginalName() ?: $file->hashName();
        $name = pathinfo($name, PATHINFO_FILENAME);
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();

        $origName = $this->getUploadingFileName($name);
        $uploadFilename = $origName . '.' . $extension;

        if (!$overwrite && $this->fileExists($uploadFilename, $disk))
            throw new FileDuplicationException();

        // it is a cleanup step if there is any file in storage but not in DB
        // to prevent showing multiple same name files in UI
        $filePath = trim($path . '/' . $this->getNormalizedPath($name . '.' . $extension), '\\/');
        $cleanupFiles = $this->fileExists(
            filePath: $filePath,
            disk: $disk,
            getFiles: true,
            getAllVariants: true,
            justGetFiles: true,
        );

        if (count($cleanupFiles)) {
            $diskStorage = Storage::disk($disk);
            foreach ($cleanupFiles as $f) {
                $diskStorage->delete($f);
            }
        }

        //

        DB::beginTransaction();

        $model = $this->updateOrCreate([
            'name' => $name,
            'extension' => $extension,
            'path' => $path,
        ]);

        $file->storeAs($path, $uploadFilename, $disk);
        $res = $this->uploadThumbnailsIfSupported($file, $path, $disk, $origName);

        if (!$res || !$model instanceof Model) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @inheritDoc
     * @throws InvalidPathException
     * @throws InvalidDiskException
     */
    public function list(FileListFilter $filter): array
    {
        $path = $filter->getPath();
        $disk = $filter->getDisk();
        $fileSize = $filter->getSize();
        $search = $filter->getSearchText();
        $extensions = $filter->getExtensions();
        $order = $filter->getOrder();

        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $where = new WhereBuilder('file_manager');

        $hasSearch = !is_null($search) && trim($search) != '';

        if ($hasSearch) {
            $search = $this->getNormalizedPath($search);

            $where->group(function (WhereBuilderInterface $where) use ($search, $path) {
                $where->orWhereLike('name', $search)
                    ->orWhereRegexp('path', '^' . $path);

                if (mb_strpos('.', $search) !== false) {
                    $where->orWhereLike('extension', $search);
                }
            });
        } else {
            $where->whereEqual('path', $path);
        }

        if (count($extensions)) {
            $where->whereIn('extension', $extensions);
        }

        $listFiles = [];
        $this->chunk(function (
            Collection $files
        ) use (&$dbFiles, &$listFiles, $hasSearch, $fileSize, $disk) {
            $files->groupBy('path')->each(function (
                Collection $group,
                           $path
            ) use ($disk, $hasSearch, &$listFiles, $fileSize) {
                $storageFiles = Storage::disk($disk)->files($path);

                $tmpFiles = $this->getSimilarFiles(
                    $storageFiles,
                    '(' . implode('|', array_map(function ($value) {
                        return preg_quote($value);
                    }, Arr::pluck($group, 'name'))) . ')',
                    $hasSearch ? ['i'] : []
                );

                $tmpFiles = $this->getSpecificThumbnail($tmpFiles, $fileSize);

                // get file info of fetched files
                foreach ($tmpFiles as $file) {
                    $fileInfo = $this->getFileInfo($file, $disk);
                    if (!empty($fileInfo)) {
                        $tmpName = explode('-', $file)[0];
                        $dbFile = $group->filter(function ($i) use ($tmpName) {
                            return trim($i->path . '/' . $i->name, '\\/') === $tmpName;
                        })->first();

                        if (!empty($dbFile)) {
                            $fileInfo['id'] = $dbFile->id;
                            $fileInfo['path'] = $dbFile->path;
                            $fileInfo['full_path'] = $dbFile->full_path;
                            $listFiles[] = $fileInfo;
                        }
                    }
                }
            });
        }, $where->build());

        $treeList = $this->treeList($path, $disk);

        $orderCol = array_keys($order)[0] ?? 'name';
        $sort = array_values($order)[0] ?? 'asc';

        // sort tree list
        if ($sort == 'desc') {
            Arr::sortDesc($treeList, function ($value) use ($orderCol) {
                return $value[$orderCol] ?? 'name';
            });
        } else {
            Arr::sort($treeList, function ($value) use ($orderCol) {
                return $value[$orderCol] ?? 'name';
            });
        }

        // sort file list
        if ($sort == 'desc') {
            Arr::sortDesc($listFiles, function ($value) use ($orderCol) {
                return $value[$orderCol] ?? 'name';
            });
        } else {
            Arr::sort($listFiles, function ($value) use ($orderCol) {
                return $value[$orderCol] ?? 'name';
            });
        }

        return array_merge($treeList, $listFiles);
    }

    /**
     * @inheritDoc
     * @throws InvalidPathException
     * @throws InvalidDiskException
     */
    public function treeList(string $path, string $disk, ?string $search = null): array
    {
        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $diskStorage = Storage::disk($disk);

        $hasSearch = !is_null($search) && trim($search) != '';

        if ($hasSearch) {
            $dirs = $diskStorage->allDirectories($path);
            $pattern = '[^\/]*' . preg_quote($search) . '[^\/]*$';
            $dirs = $this->getSimilarFiles($dirs, $pattern, ['i']);
        } else {
            $dirs = $diskStorage->directories($path);
        }

        $dirsList = [];
        foreach ($dirs as $dir) {
            if (!empty($fileInfo = $this->getFileInfo($dir, $disk))) $dirsList[] = $fileInfo;
        }

        return $dirsList;
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function createDirectory(string $name, string $path, string $disk): bool
    {
        $path = $this->getNormalizedPath($path);
        $name = $this->getNormalizedPath($name);

        if (trim($name) == '')
            throw new InvalidArgumentException('نام پوشه انتخاب شده نامعتبر می‌باشد.');

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);
        $this->ensurePathExists($path, $disk);

        return Storage::disk($disk)
            ->makeDirectory($path . '/' . ltrim($name . '/\\'));
    }

    /**
     * @inheritDoc
     * @throws FileDuplicationException
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function rename(string $path, string $oldName, string $newName, string $disk): bool
    {
        $path = $this->getNormalizedPath($path);
        $oldName = $this->getNormalizedPath($oldName);
        $newName = $this->getNormalizedPath($newName);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $diskStorage = Storage::disk($disk);

        $newPath = $path . '/' . ltrim($newName, '\\/');

        $tmpOldExtension = pathinfo($oldName, PATHINFO_EXTENSION) ?? null;
        $tmpNewExtension = pathinfo($newName, PATHINFO_EXTENSION) ?? null;

        if (
            (is_null($tmpOldExtension) && !is_null($tmpNewExtension)) ||
            (!is_null($tmpOldExtension) && is_null($tmpNewExtension))
        ) {
            throw new InvalidArgumentException('نوع فایل/پوشه در تغییر نام، عوض شده و نامعتبر می‌باشد!');
        }

        if (
            (!is_null($tmpNewExtension) && $this->fileExists($newPath, $disk)) ||
            (is_null($tmpNewExtension) && $diskStorage->exists($newPath))
        )
            throw new FileDuplicationException('فایل/پوشه مورد نظر در محل ذخیره‌سازی وجود دارد.');

        return $this->moveOrCopy($path, $oldName, $newPath, $disk, self::OPERATION_MOVE);
    }

    /**
     * @inheritDoc
     * @throws InvalidPathException
     */
    public function move(array $paths, string $destination, string $disk): bool
    {
        $this->checkDiskValidation($disk);

        $res = true;
        foreach ($paths as $path) {
            $res = $res && $this->moveOrCopyOne($path, $destination, $disk, self::OPERATION_MOVE);
            if (!$res) break;
        }

        return $res;
    }

    /**
     * @inheritDoc
     * @throws InvalidPathException
     */
    public function copy(array $paths, string $destination, string $disk): bool
    {
        $this->checkDiskValidation($disk);

        $res = true;
        foreach ($paths as $path) {
            $res = $res && $this->moveOrCopyOne($path, $destination, $disk, self::OPERATION_COPY);
            if (!$res) break;
        }

        return $res;
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function remove(array|string $files, string $path, string $disk): bool
    {
        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $success = true;

        $files = !is_array($files) ? [$files] : $files;

        foreach ($files as $file) {
            $success = $success && $this->removeFile($file, $path, $disk);
        }

        return $success;
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidFileException
     */
    public function download(string $path, string $disk): mixed
    {
        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);

        $files = $this->fileExists($path, $disk, true);

        if (!count($files))
            throw new InvalidFileException();

        $downloadFile = null;
        $filename = null;
        foreach ($files as $file) {
            if (count($arr = explode('-', $file)) == 2) {
                $filename = $arr[0];
                $downloadFile = $file;
                break;
            }
        }

        if (is_null($downloadFile) || is_null($filename))
            throw new InvalidFileException('فایل انتخاب شده برای دانلود نامعتبر می‌باشد.');

        return Storage::disk($disk)->download($downloadFile, $filename);
    }

    /**
     * @inheritDoc
     */
    public function fileExists(
        string  $filePath,
        string  $disk,
        bool    $getFiles = false,
        ?string $fileSize = null,
        bool    $getAllVariants = false,
        bool    $justGetFiles = false
    ): bool|array
    {
        $filePath = $this->getNormalizedPath($filePath);
        if (empty(trim($filePath))) return $getFiles ? [] : false;

        $info = pathinfo($filePath);
        $filename = $info['filename'];
        $filePath = $this->getNormalizedPath($info['dirname']);
        $filePath = $this->normalizeDirname($filePath);
        $extension = $info['extension'] ?? null;

        if (!Storage::disk($disk)->exists($filePath)) return $getFiles ? [] : false;

        if (!$justGetFiles) {
            $where = new WhereBuilder('file_manager');
            $where
                ->whereEqual('path', $filePath)
                ->whereEqual('name', $filename);
            if (!empty($extension)) $where->whereEqual('extension', $extension);

            if (!$this->exists($where->build())) return $getFiles ? [] : false;
        }

        if ($getAllVariants) $variantPattern = '(?:\-[a-zA-Z0-9]+)?';

        if (is_null($fileSize) || trim($fileSize) === '' || $fileSize == self::ORIGINAL) {
            $pattern = '^(' . $filePath . '\/)?' . preg_quote($filename) . '\-[0-9]+' . ($variantPattern ?? '');
        } else {
            $pattern = '^(' . $filePath . '\/)?' . preg_quote($filename) . '.*\-' . preg_quote($fileSize);
        }

        if (!is_null($extension)) {
            $pattern .= '\.' . preg_quote($extension) . '$';
        } else {
            $pattern .= '\.[a-z]+$';
        }

        $files = $this->getSimilarFiles(Storage::disk($disk)->files($filePath), $pattern);

        return $getFiles ? $files : (bool)count($files);
    }

    /**
     * NOTE:
     *   -It may return empty array in case of invalid file
     *
     * @inheritDoc
     */
    public function getFileInfo(string $file, string $disk): array
    {
        $file = $this->getNormalizedPath($file, true);
        $diskStorage = Storage::disk($disk);

        if (!$diskStorage->exists($file)) return [];

        $path = $diskStorage->path($file);
        $info = pathinfo($file);
        $info['dirname'] = $this->normalizeDirname($info['dirname']);

        $ext = $info['extension'] ?? null;
        $filename = $info['filename'];
        $isDir = is_dir($path);

        $createdAt = null;
        $modifiedAt = $diskStorage->lastModified($file);

        $filenameParts = explode('-', $filename);
        if (count($filenameParts) == 2) {
            $filename = $filenameParts[0];
            $createdAt = $filenameParts[1];
        } elseif (count($filenameParts) > 2) {
            $sliceCount = -1;
            $lastPart = $filenameParts[count($filenameParts) - 1];
            if (!is_numeric($lastPart)) {
                $sliceCount = -2;

                $beforeLastPart = $filenameParts[count($filenameParts) - 2] ?? null;
                if (is_numeric($beforeLastPart)) {
                    $createdAt = $beforeLastPart;
                }
            } else {
                $createdAt = $lastPart;
            }

            $filename = implode('-', array_slice($filenameParts, 0, $sliceCount));
        } else {
            $filename = $filenameParts[0];
        }

        $fileInfo = [
            'name' => $filename,
            'extension' => $ext,
            'full_name' => $filename . (
                !is_null($ext) ? '.' . $ext : ''
                ),
            'path' => $info['dirname'],
            'full_path' => $info['dirname'] . ('/' . $filename . (!is_null($ext) ? '.' . $ext : '')),
            'is_dir' => $isDir,
            'size' => $this->formatBytes($this->getSizeRecursive($diskStorage, $file)),
            'mime_type' => !$isDir ? $diskStorage->mimeType($file) : null,
            'created_at' => isset($createdAt)
                ? vertaTz(Carbon::createFromTimestamp(intval($createdAt)))
                    ->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'last_modified' => isset($modifiedAt)
                ? vertaTz(Carbon::createFromTimestamp(intval($modifiedAt)))
                    ->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];

        if (!$isDir) {
            $fileInfo['visibility'] = $diskStorage->getVisibility($file);
        }

        return $fileInfo;
    }

    /**
     * Because of same code for move and copy,
     * we have one method to do just one move/copy for simplifying purposes
     *
     * @param string $path
     * @param string $destination
     * @param string $disk
     * @param string $operation
     * @return bool
     * @throws InvalidPathException
     */
    protected function moveOrCopyOne(
        string $path,
        string $destination,
        string $disk,
        string $operation
    ): bool
    {
        $p = $this->getNormalizedPath($path);
        $destination = $this->getNormalizedPath($destination);

        $pInfo = pathinfo($p);
        $pInfo['dirname'] = $this->getNormalizedPath($this->normalizeDirname($pInfo['dirname']));

        return $this->moveOrCopy($pInfo['dirname'], $pInfo['basename'], $destination, $disk, $operation);
    }
}
