<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\FileManager;
use Illuminate\Database\Eloquent\Model;

interface FileServiceInterface extends ServiceInterface
{
    /**
     * @param string $path
     * @param $file
     * @param string $disk
     * @param bool $overwrite
     * @return bool
     */
    public function upload(string $path, $file, string $disk, bool $overwrite = false): bool;

    /**
     * @param string $path
     * @param string $disk
     * @param string|null $search
     * @param string|null $fileSize
     * @param array $extensions
     * @param array $order
     * @return array
     */
    public function list(
        string  $path,
        string  $disk,
        ?string $search = null,
        ?string $fileSize = null,
        array   $extensions = [],
        array   $order = ['name' => 'acs']
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
     * @param array|FileManager $files
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public function delete(array|FileManager $files, ?string $path, string $disk): bool;

    /**
     * @param $file
     * @param string $path
     * @param string $disk
     * @return mixed
     */
    public function download($file, string $path, string $disk): mixed;

    /**
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function exists(string $path, string $disk): bool;

    /**
     * @param $file
     * @return Model|null
     */
    public function find($file): Model|null;

    /**
     * @param $file
     * @param string|null $disk
     * @param string|null $size
     * @return string|null
     */
    public function findFile($file, ?string $disk = null, ?string $size = null): ?string;

    /**
     * @param string $size
     * @return bool
     */
    public function isValidThumbSize(string $size): bool;
}
