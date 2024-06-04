<?php

namespace App\Services;

use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Http\Requests\Filters\FileListFilter;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\FileRepository;
use App\Services\Contracts\FileServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Traits\FilenameTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Traits\VersionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService implements FileServiceInterface
{
    use VersionTrait, FilenameTrait;

    public function __construct(protected FileRepository $repository)
    {
    }

    /**
     * @inheritDoc
     * @throws InvalidFileException
     * @throws InvalidDiskException
     */
    public function saveToDb(
        array|string $data,
        array        $extraAttributes = [],
        string       $disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC
    ): ?Model
    {
        if (!in_array(strtolower($disk), FileRepositoryInterface::STORAGE_DISKS)) {
            throw new InvalidDiskException();
        }

        if (is_string($data)) {
            return $this->repository->savePath($data, $extraAttributes, $disk);
        }

        $attrs = [
            'name' => $this->getEscapedFilename($data['name']),
            'extension' => $data['extension'],
            'path' => $this->getEscapedFilename($data['path']),
            'disk' => $disk,
        ];

        if (isset($data['created_by'])) {
            $attrs['created_by'] = $data['created_by'];
        }
        if (isset($data['updated_by'])) {
            $attrs['updated_by'] = $data['updated_by'];
        }

        return $this->repository->create($attrs);
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
        $path = NumberConverter::toEnglish($path);
        return $this->repository->upload($path, $file, $disk, $overwrite);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function list(FileListFilter $filter): array
    {
        return $this->repository->list($filter);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function treeList(string $path, string $disk, ?string $search = null): array
    {
        $path = NumberConverter::toEnglish($path);
        return $this->repository->treeList($path, $disk, $search);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function createDirectory(string $name, string $path, string $disk): bool
    {
        $name = NumberConverter::toEnglish($name);
        $path = NumberConverter::toEnglish($path);
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

        $path = NumberConverter::toEnglish($path);
        $oldName = NumberConverter::toEnglish($oldName);
        $newName = NumberConverter::toEnglish($newName);
        return $this->repository->rename($path, $oldName, $newName, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function move(array $paths, string $destination, string $disk): bool
    {
        $paths = NumberConverter::toEnglish($paths);
        $destination = NumberConverter::toEnglish($destination);
        return $this->repository->move($paths, $destination, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function copy(array $paths, string $destination, string $disk): bool
    {
        $paths = NumberConverter::toEnglish($paths);
        $destination = NumberConverter::toEnglish($destination);
        return $this->repository->copy($paths, $destination, $disk);
    }

    /**
     * @inheritDoc
     * @throws InvalidDiskException
     * @throws InvalidPathException
     */
    public function delete(string|array $files, ?string $path, string $disk): bool
    {
        $files = NumberConverter::toEnglish($files);
        $path = NumberConverter::toEnglish($path);
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
        $file = NumberConverter::toEnglish($file);
        $path = NumberConverter::toEnglish($path);

        $file = rtrim($path, '\\/') . '/' . ltrim($file, '\\/');
        return $this->repository->download($file, $disk);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path, string $disk): bool
    {
        $hasDisk = $this->repository->checkDiskValidation($disk, false);

        if (!$hasDisk) return false;

        $path = NumberConverter::toEnglish($path);
        $pathInfo = pathinfo($path);

        return !empty($pathInfo['extension'])
            ? $this->repository->fileExists($path, $disk)
            : $this->repository->checkPathExists($path, $disk);
    }

    /**
     * @inheritDoc
     */
    public function find($file, bool $isAuthenticated = false): Model|null
    {
        $file = NumberConverter::toEnglish($file);

        // basic normalization
        $file = trim($file, '.');
        $file = str_replace('\\', '/', $file);

        if (explode('/', $file) > 2) {
            $file = ltrim($file, '/');
        }
        //

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
        $file = NumberConverter::toEnglish($file);
        $size = $this->isValidThumbSize($size ?: '') ? $size : $this->repository::ORIGINAL;

        if (
            is_null($disk) ||
            !in_array($disk, $this->repository::STORAGE_DISKS)
        ) {
            $files = $this->repository->fileExists($file, $disk, true, $size);
        } else {
            $disk = $this->repository::STORAGE_DISK_PUBLIC;
            $files = $this->repository->fileExists($file, $disk, true, $size);
            if (!count($files) && $isAuthenticated) {
                $disk = $this->repository::STORAGE_DISK_LOCAL;
                $files = $this->repository->fileExists($file, $disk, true, $size);
            }
        }

        if (!count($files)) return null;

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
