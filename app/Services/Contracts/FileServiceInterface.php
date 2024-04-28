<?php

namespace App\Services\Contracts;

use App\Http\Requests\Filters\FileListFilter;
use Illuminate\Database\Eloquent\Model;

interface FileServiceInterface
{
    /**
     * 📍[Use with caution]
     *  -Only use this if you are absolutely sure the file exists but not stored in database.
     *
     * @param array|string $data Data is an array of database fields,
     *                           or it is a typical full path of a file that will parse to database fields.
     * @param array $extraAttributes This parameter is for using with string $data parameter
     *                               to add extra parameter to add in database.
     * @return Model|null
     */
    public function saveToDb(array|string $data, array $extraAttributes = []): ?Model;

    /**
     * @param string $path
     * @param $file
     * @param string $disk
     * @param bool $overwrite
     * @return bool
     */
    public function upload(string $path, $file, string $disk, bool $overwrite = false): bool;

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
     * @param string|array $files
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public function delete(string|array $files, ?string $path, string $disk): bool;

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
     * @param bool $isAuthenticated
     * @return Model|null
     */
    public function find($file, bool $isAuthenticated = false): Model|null;

    /**
     * @param $file
     * @param string|null $disk
     * @param string|null $size
     * @param bool $isAuthenticated
     * @return string|null
     */
    public function findFile(
        $file,
        ?string $disk = null,
        ?string $size = null,
        bool $isAuthenticated = false
    ): ?string;

    /**
     * @param string $size
     * @return bool
     */
    public function isValidThumbSize(string $size): bool;
}
