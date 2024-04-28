<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Http\Requests\Filters\FileListFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface extends RepositoryInterface
{
    /**
     * Storage disks constants
     */
    public const STORAGE_DISK_PUBLIC = 'public';

    public const STORAGE_DISK_LOCAL = 'private';

    /**
     * File sizes for images
     */
    public const ORIGINAL = 'original';

    public const SMALL = 'small';

    public const MEDIUM = 'medium';

    public const LARGE = 'large';

    /**
     * @var string[]
     */
    public const STORAGE_DISKS = [
        self::STORAGE_DISK_LOCAL,
        self::STORAGE_DISK_PUBLIC
    ];

    /**
     * @var array|int[]
     */
    public const VALID_SIZES = [
        self::SMALL => [400, 300],
        self::MEDIUM => [800, 600],
        self::LARGE => [1000, 800],
    ];

    /**
     * File operations
     */
    public const OPERATION_MOVE = 'move';
    public const OPERATION_COPY = 'copy';

    /**
     * @param string $fullPath
     * @param array $attributes
     * @return Model|null
     */
    public function savePath(string $fullPath, array $attributes = []): ?Model;

    /**
     * @param string $path
     * @param UploadedFile $file
     * @param string $disk
     * @param bool $overwrite
     * @return bool
     */
    public function upload(string $path, UploadedFile $file, string $disk, bool $overwrite = false): bool;

    /**
     * @param FileListFilter $filter
     * @return array
     */
    public function list(FileListFilter $filter): array;

    /**
     * @param string $path
     * @param string $disk
     * @param string|null $search
     * @return array
     */
    public function treeList(string $path, string $disk, ?string $search = null): array;

    /**
     * @param string $name
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function createDirectory(string $name, string $path, string $disk): bool;

    /**
     * @param string $path
     * @param string $oldName
     * @param string $newName
     * @param string $disk
     * @return bool
     */
    public function rename(string $path, string $oldName, string $newName, string $disk): bool;

    /**
     * Note: It doesn't move and overwrite file/directory to existing one in destination
     *
     * @param array $paths
     * @param string $destination
     * @param string $disk
     * @return bool
     */
    public function move(array $paths, string $destination, string $disk): bool;

    /**
     * @param array $paths
     * @param string $destination
     * @param string $disk
     * @return bool
     */
    public function copy(array $paths, string $destination, string $disk): bool;

    /**
     * @param array|string $files
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function remove(array|string $files, string $path, string $disk): bool;

    /**
     * @param string $path
     * @param string $disk
     * @return mixed
     */
    public function download(string $path, string $disk): mixed;

    /**
     * If $getFiles is true, founded files will return,
     * otherwise return a boolean to show file exists
     *
     * @param string $filePath
     * @param string $disk
     * @param bool $getFiles
     * @param string|null $fileSize
     * @param bool $getAllVariants
     * @param bool $justGetFiles
     * @return bool|array
     */
    public function fileExists(
        string  $filePath,
        string  $disk,
        bool    $getFiles = false,
        ?string $fileSize = null,
        bool    $getAllVariants = false,
        bool    $justGetFiles = false
    ): bool|array;

    /**
     * @param string $file
     * @param string $disk
     * @return array
     */
    public function getFileInfo(string $file, string $disk): array;
}
