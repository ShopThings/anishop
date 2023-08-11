<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $path
     * @param UploadedFile $file
     * @param string $disk
     * @param bool $overwrite
     * @return bool
     */
    public function upload(string $path, UploadedFile $file, string $disk, bool $overwrite = false): bool;

    /**
     * @param string $path
     * @param string $disk
     * @param string|null $search
     * @param string $fileSize
     * @param array $order
     * @return array
     */
    public function list(
        string  $path,
        string  $disk,
        ?string $search = null,
        string  $fileSize = 'original',
        array   $order = []
    ): array;

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
     * @return bool
     */
    public function remove(array|string $files, string $path, string $disk): bool;

    /**
     * @param string $path
     * @param string $disk
     * @return mixed
     */
    public function download(string $path, string $disk);

    /**
     * If $getFiles is true, founded files will return,
     * otherwise return a boolean to show file exists
     *
     * @param string $filePath
     * @param string $disk
     * @param bool $getFiles
     * @param string|null $fileSize
     * @return bool|array
     */
    public function fileExists(
        string  $filePath,
        string  $disk,
        bool    $getFiles = false,
        ?string $fileSize = null
    ): bool|array;

    /**
     * @param string $file
     * @param string $disk
     * @return array
     */
    public function getFileInfo(string $file, string $disk): array;
}
