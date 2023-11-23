<?php

namespace App\Repositories;

use App\Enums\Times\TimeFormatsEnum;
use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Models\FileManager;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\FileTrait;
use App\Support\WhereBuilder\WhereBuilder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use function pathinfo;

class FileRepository extends Repository implements FileRepositoryInterface
{
    use FileTrait;

    /**
     * Storage disks constants
     */
    const STORAGE_DISK_PUBLIC = 'public';

    const STORAGE_DISK_LOCAL = 'local';

    /**
     * File sizes for images
     */
    const ORIGINAL = 'original';

    const SMALL = 'small';

    const MEDIUM = 'medium';

    const LARGE = 'large';

    /**
     * @var string[]
     */
    public static array $storageDisks = [
        self::STORAGE_DISK_LOCAL,
        self::STORAGE_DISK_PUBLIC
    ];

    /**
     * @var array|int[]
     */
    public static array $validSizes = [
        self::SMALL => [400, 300],
        self::MEDIUM => [800, 600],
        self::LARGE => [1000, 800],
    ];

    protected bool $useSoftDeletes = false;

    public function __construct(FileManager $model)
    {
        parent::__construct($model);
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

        $name = $this->getNormalizedPath($file->getClientOriginalName() ?: $file->hashName());
        $name = pathinfo($name, PATHINFO_FILENAME);
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();

        $origName = $this->getUploadingFileName($name);
        $uploadFilename = $origName . '.' . $extension;

        if (!$overwrite && $this->fileExists($uploadFilename, $disk))
            throw new FileDuplicationException();

        $file->storeAs($path, $uploadFilename, $disk);
        $res = $this->uploadThumbnailsIfSupported($file, $path, $disk, $origName);

        $model = $this->create([
            'name' => $name,
            'extension' => $extension,
            'path' => $path,
        ]);

        return $res && $model instanceof Model;
    }

    /**
     * @inheritDoc
     * @throws InvalidPathException
     * @throws InvalidDiskException
     */
    public function list(
        string  $path,
        string  $disk,
        ?string $search = null,
        string  $fileSize = self::ORIGINAL,
        array   $extensions = [],
        array   $order = []
    ): array
    {
        $path = $this->getNormalizedPath($path);

        $this->checkDiskValidation($disk);
        $this->checkPathExists($path, $disk);

        $where = new WhereBuilder('file_manager');

        $hasSearch = !is_null($search) && trim($search) != '';

        if ($hasSearch) {
            $search = $this->getNormalizedPath($search);

            $where->whereLike('name', $search)
                ->whereRegexp('path', '^' . $path);

            if (mb_strpos('.', $search) !== false) {
                $where->whereLike('extension', $search);
            }
        } else {
            $where->whereEqual('path', $path);
        }

        if (count($extensions)) {
            $where->whereIn('extension', $extensions);
        }

        $listFiles = [];
        $dbFiles = [];
        $this->chunk(function (Collection $files) use (&$dbFiles, &$listFiles, $hasSearch, $fileSize, $disk) {
            $files->each(function (FileManager $file) use (&$dbFiles) {
                $dbFiles[] = [
                    'name' => $file->name,
                    'extension' => $file->extension,
                    'path' => $file->path,
                ];
            });

            $tmpFiles = $this->getSimilarFiles(
                $files->toArray(),
                '(' . implode('|', array_map(function ($value) {
                    return preg_quote($value);
                }, Arr::pluck($dbFiles, 'name'))) . ')',
                $hasSearch ? ['i'] : []
            );
            foreach ($tmpFiles as $tmpFile) {
                if (!in_array($tmpFile, $listFiles)) {
                    $listFiles[] = $tmpFile;
                }
            }

            $tmpFiles = $this->getSpecificThumbnail($listFiles, $fileSize);

            // get file info of fetched files
            foreach ($tmpFiles as $file) {
                $listFiles[] = $this->getFileInfo($file, $disk);
            }

            $dbFiles = [];
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

        return $treeList + $listFiles;
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
            $dirs = $this->getSimilarFiles($dirs, preg_quote($search), ['i']);
        } else {
            $dirs = $diskStorage->directories($path);
        }

        $dirsList = [];
        foreach ($dirs as $dir) {
            $dirsList[] = $this->getFileInfo($dir, $disk);
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

        $oldPath = $path . '/' . ltrim($oldName, '\\/');
        $newPath = $path . '/' . ltrim($newName, '\\/');

        $tmpOldExtension = pathinfo($oldName, PATHINFO_EXTENSION) ?? null;
        $tmpNewExtension = pathinfo($newName, PATHINFO_EXTENSION) ?? null;
        if (
            (is_null($tmpOldExtension) && !is_null($tmpNewExtension)) ||
            (!is_null($tmpOldExtension) && is_null($tmpNewExtension))
        )
            throw new InvalidArgumentException('نوع فایل/پوشه در تغییر نام عوض شده و نامعتبر می‌باشد!');

        if (
            (!is_null($tmpNewExtension) && $this->fileExists($newPath, $disk)) ||
            (is_null($tmpNewExtension) && $diskStorage->exists($newPath))
        )
            throw new FileDuplicationException('فایل/پوشه مورد نظر در محل ذخیره‌سازی وجود دارد.');

        if (!is_null($tmpNewExtension)) {
            $files = $this->fileExists($oldPath, $disk, true);
            foreach ($files as $file) {
                $diskStorage->move($file, $newPath);

                // It needs to update in database too
                $info = pathinfo($newPath);
                $attrs = [
                    'name' => $info['filename'],
                    'path' => $info['dirname'],
                    'extension' => $info['extension'],
                ];

                $where = new WhereBuilder('file_manager');
                $where->whereEqual('name', $oldName)
                    ->whereEqual('path', $oldPath);
                $this->updateWhere($attrs, $where->build());
            }
        } else {
            $diskStorage->move($oldPath, $newPath);
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function move(array $paths, string $destination, string $disk): bool
    {
        $this->moveOrCopy($paths, $destination, $disk);
        return true;
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function copy(array $paths, string $destination, string $disk): bool
    {
        $this->moveOrCopy($paths, $destination, $disk, true);
        return true;
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
    public function download(string $path, string $disk)
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
        ?string $fileSize = null
    ): bool|array
    {
        $info = pathinfo($filePath);
        $filename = $info['filename'];
        $filePath = $info['dirname'];
        $extension = $info['extension'] ?? null;

        $where = new WhereBuilder('file_manager');
        $where->whereEqual('name', $filename)
            ->whereEqual('path', $filePath);
        if (!$this->exists($where->build())) return $getFiles ? [] : false;

        if (is_null($fileSize) && trim($fileSize) != '')
            if ($fileSize == self::ORIGINAL) {
                if (!is_null($extension))
                    $pattern = '^' . preg_quote($filename) . '\-[0-9]\.' . preg_quote($extension) . '$';
                else
                    $pattern = '^' . preg_quote($filename) . '\-[0-9]\.[a-z]+$';
            } else {
                if (!is_null($extension))
                    $pattern = '^' . preg_quote($filename) . '.*\-' . preg_quote($fileSize) . '\.' . preg_quote($extension) . '$';
                else
                    $pattern = '^' . preg_quote($filename) . '.*\-' . preg_quote($fileSize) . '\.[a-z]+$';
            }
        else {
            if (!is_null($extension))
                $pattern = '^' . preg_quote($filename) . '.*\.' . preg_quote($extension) . '$';
            else
                $pattern = '^' . preg_quote($filename) . '.*\.[a-z]+$';
        }

        $files = $this->getSimilarFiles(Storage::disk($disk)->files($filePath), $pattern);

        return $getFiles ? $files : (bool)count($files);
    }

    /**
     * @inheritDoc
     */
    public function getFileInfo(string $file, string $disk): array
    {
        $diskStorage = Storage::disk($disk);
        $path = $diskStorage->path($file);
        $info = pathinfo($path);
        $createdAt = explode('-', $info['filename'])[1] ?? null;

        return [
            'name' => $info['filename'],
            'extension' => $info['extension'] ?? null,
            'full_name' => $info['filename'] . (
                $info['extension']
                    ? '.' . $info['extension']
                    : ''
                ),
            'path' => $path,
            'full_path' => rtrim($path, '/') . '/'
                . $info['filename'] . (
                $info['extension']
                    ? '.' . $info['extension']
                    : ''
                ),
            'is_dir' => is_dir($path),
            'size' => $this->formatBytes($diskStorage->size($file)),
            'url' => $diskStorage->url($path),
            'mime_type' => is_file($path) ? $diskStorage->mimeType($file) : null,
            'visibility' => $diskStorage->getVisibility($path),
            'created_at' => $createdAt
                ? verta(Carbon::createFromTimestamp($createdAt))
                    ->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'last_modified' => $diskStorage->lastModified('file.jpg'),
        ];
    }
}
