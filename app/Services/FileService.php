<?php

namespace App\Services;

use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Http\Requests\Filters\FileListFilter;
use App\Models\FileManager;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\FileRepository;
use App\Services\Contracts\FileServiceInterface;
use App\Support\WhereBuilder\WhereBuilder;
use App\Traits\VersionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService implements FileServiceInterface
{
    use VersionTrait;

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
    public function list(FileListFilter $filter): array
    {
        $fileSize = $filter->getSize();
        if (!$this->isValidThumbSize($fileSize))
            $fileSize = $this->repository::ORIGINAL;

        return $this->repository->list($filter);
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
    public function delete(FileManager|array $files, ?string $path, string $disk): bool
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
    public function find($file, bool $isAuthenticated = false): Model|null
    {
        $where = new WhereBuilder('file_manager');
        $where->orWhereEqual('full_path', $file);

        if ($isAuthenticated) {
            $where->orWhereEqual('id', $file);
        }

        return $this->repository->findWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function findFile(
        $file,
        ?string $disk = null,
        ?string $size = null,
        bool $isAuthenticated = false
    ): ?string
    {
        $dbFile = $this->find($file, $isAuthenticated);

        if (!$dbFile) return null;

        $size = $this->isValidThumbSize($size) ? $size : $this->repository::ORIGINAL;

        if (
            is_null($disk) ||
            !in_array($disk, $this->repository::STORAGE_DISKS)
        ) {
            $files = $this->repository->fileExists($dbFile->path, $disk, true);
        } else {
            $disk = $this->repository::STORAGE_DISK_PUBLIC;
            $files = $this->repository->fileExists($dbFile->path, $disk, true, $size);
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
            FileRepositoryInterface::ORIGINAL,
            FileRepositoryInterface::SMALL,
            FileRepositoryInterface::MEDIUM,
            FileRepositoryInterface::LARGE,
        ]);
    }
}
