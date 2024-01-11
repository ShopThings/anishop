<?php

namespace App\Http\Requests\Filters;

use App\Enums\Gates\RolesEnum;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Support\Traits\OrderingTrait;
use Illuminate\Http\Request;

class FileListFilter
{
    use OrderingTrait;

    /**
     * @var string
     */
    protected string $path = '/';

    /**
     * @var string
     */
    protected string $disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC;

    /**
     * @var string
     */
    protected $size = FileRepositoryInterface::ORIGINAL;

    /**
     * @var array|null
     */
    protected ?array $extensions = null;

    /**
     * @var array|string[]
     */
    protected array $order = ['name' => 'asc'];

    /**
     * @var string|null
     */
    protected ?string $searchText = null;

    public function __construct(protected Request $request)
    {
        $this->setPath($request->string('path', ''));
        $this->setDisk($request->string('disk', FileRepositoryInterface::STORAGE_DISK_PUBLIC));
        $this->setSize($request->string('size', FileRepositoryInterface::ORIGINAL));

        $extensions = $request->input('extensions', []);
        if (is_array($extensions)) $this->setExtensions($extensions);

        $orderColumn = $request->string('column', 'name')->toString();
        $orderSort = $request->string('sort', 'asc')->toString();

        if (!empty(trim($orderColumn)) && !empty(trim($orderSort))) {
            $this->setOrder([$orderColumn => $orderSort]);
        }

        $this->setSearchText($request->string('search')->toString());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return static
     */
    public function setPath(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @param string $disk
     * @return static
     */
    public function setDisk(string $disk): static
    {
        $user = $this->request->user();

        if (
            !$user ||
            // only below roles can access other storages than "public"
            !$user->hasAnyRole(
                [
                    RolesEnum::DEVELOPER->value,
                    RolesEnum::SUPER_ADMIN->value,
                    RolesEnum::ADMIN->value
                ]
            ) ||
            !in_array(
                $disk,
                FileRepositoryInterface::STORAGE_DISKS
            )
        ) {
            $this->disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC;
        } else {
            $this->disk = $disk;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return static
     */
    public function setSize(string $size): static
    {
        if (in_array($size, array_keys(FileRepositoryInterface::VALID_SIZES))) {
            $this->size = $size;
        } else {
            $this->size = FileRepositoryInterface::ORIGINAL;
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }

    /**
     * @param array|null $extensions
     * @return static
     */
    public function setExtensions(?array $extensions): static
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrder(): array
    {
        return $this->convertOrdersColumnToArray($this->order);
    }

    /**
     * It should be like:
     *  [
     *    column => sort,
     *    ...
     *  ]
     *
     * @param array $order
     * @return static
     *
     * @example As an example:
     *  [
     *    'id' => 'desc',
     *    'name' => 'asc',
     *    ...
     *  ]
     */
    public function setOrder(array $order): static
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @param string|null $searchText
     * @return static
     */
    public function setSearchText(?string $searchText): static
    {
        if (null !== $searchText) $this->searchText = trim($searchText);
        else $this->searchText = null;
        return $this;
    }

    /**
     * @return static
     */
    public function reset(): static
    {
        $this->path = '/';
        $this->disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC;
        $this->size = FileRepositoryInterface::ORIGINAL;
        $this->extensions = null;
        $this->order = ['name' => 'asc'];
        $this->searchText = null;

        return $this;
    }
}
