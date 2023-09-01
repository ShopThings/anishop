<?php

namespace App\Services;

use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Models\FileManager;
use App\Repositories\FileRepository;
use App\Services\Contracts\FileServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService extends Service implements FileServiceInterface
{
    public function __construct(protected FileRepository $repository)
    {
    }

    /**
     * @inheritDoc
     * @throws FileDuplicationException
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function upload(string $path, $file, string $disk, bool $overwrite = false): bool
    {
        if (!($file instanceof UploadedFile)) return false;
        return $this->repository->upload($path, $file, $disk, $overwrite);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function list(
        string  $path,
        string  $disk,
        ?string $search = null,
        ?string $fileSize = null,
        array   $extensions = [],
        array   $order = ['name' => 'asc']
    ): array
    {
        if (is_null($fileSize) || !$this->isValidThumbSize($fileSize))
            $fileSize = $this->repository::ORIGINAL;

        return $this->repository->list(
            $path,
            $disk,
            $search,
            $fileSize,
            $extensions,
            $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function treeList(string $path, string $disk, ?string $search = null): array
    {
        return $this->repository->treeList($path, $disk, $search);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function createDirectory(string $name, string $path, string $disk): bool
    {
        return $this->repository->createDirectory($name, $path, $disk);
    }

    /**
     * @inheritDoc
     * @throws FileDuplicationException
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function rename(string $path, string $oldName, string $newName, string $disk): bool
    {
        if (trim($oldName) == '' || trim($newName) == '') return false;
        return $this->repository->rename($path, $oldName, $newName, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function move(array $paths, string $destination, string $disk): bool
    {
        return $this->repository->move($paths, $destination, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function copy(array $paths, string $destination, string $disk): bool
    {
        return $this->repository->copy($paths, $destination, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function delete(array|FileManager $files, ?string $path, string $disk): bool
    {
        if ($files instanceof FileManager) {
            $path = $files->path;
            $files = $files->name . '.' . $files->extension;
        }

        return $this->repository->remove($files, $path, $disk);
    }

    /**
     * @inheritDoc
     * @return mixed|StreamedResponse
     * @throws InvalidDiskException
     * @throws InvalidFileException
     */
    public function download($file, string $path, string $disk): mixed
    {
        $file = rtrim($path, '\\/') . '/' . ltrim($file, '/');
        return $this->repository->download($file, $disk);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path, string $disk): bool
    {
        $hasDisk = $this->repository->checkDiskValidation($disk, false);

        if (!$hasDisk) return false;

        return $this->repository->checkPathExists($path, false);
    }

    /**
     * @inheritDoc
     */
    public function find($file): Model|null
    {
        $where = new WhereBuilder();
        $where->orWhereEqual('id', $file)
            ->orWhereEqual('CONCAT(file_manager.path, \'/\', file_manager.name)', $file);
        return $this->repository->findWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function findFile($file, ?string $disk = null, ?string $size = null): ?string
    {
        $dbFile = $this->find($file);

        if (!$dbFile) return null;

        $size = $this->isValidThumbSize($size) ? $size : $this->repository::ORIGINAL;

        if (
            is_null($disk) ||
            !in_array($disk, $this->repository::$storageDisks)
        ) {
            $files = $this->repository->fileExists($dbFile->path, $disk, true);
        } else {
            $disk = $this->repository::STORAGE_DISK_LOCAL;
            $files = $this->repository->fileExists($dbFile->path, $this->repository::STORAGE_DISK_PUBLIC, true, $size);
            if (!count($files)) {
                $disk = $this->repository::STORAGE_DISK_LOCAL;
                $files = $this->repository->fileExists($dbFile->path, $disk, true, $size);
            }
        }

        return Storage::disk($disk)->path($files[0]);
    }

    /**
     * @param string $size
     * @return bool
     */
    public function isValidThumbSize(string $size): bool
    {
        return in_array($size, [
            $this->repository::ORIGINAL,
            $this->repository::SMALL,
            $this->repository::MEDIUM,
            $this->repository::LARGE,
        ]);
    }
}
