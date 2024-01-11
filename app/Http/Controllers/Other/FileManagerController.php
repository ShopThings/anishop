<?php

namespace App\Http\Controllers\Other;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\FileDuplicationException;
use App\Exceptions\InvalidDiskException;
use App\Exceptions\InvalidFileException;
use App\Exceptions\InvalidPathException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\Filters\FileListFilter;
use App\Http\Requests\StoreFileRequest;
use App\Models\FileManager;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Services\FileService;
use App\Support\Gate\PermissionHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class FileManagerController extends Controller
{
    public function __construct(protected FileService $service)
    {
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function index(FileRequest $request): JsonResponse
    {
        $this->authorize('viewAny', FileManager::class);

        $filter = new FileListFilter($request);

        $list = $this->service->list($filter);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $list,
        ]);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function treeList(FileRequest $request): JsonResponse
    {
        $this->authorize('viewAny', FileManager::class);

        $list = $this->service->treeList(
            $request->input('path') ?? '',
            $this->_getCorrectStorage($request, $request->string('disk', '')),
            $request->input('search')
        );

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $list,
        ]);
    }

    /**
     * @param StoreFileRequest $request
     * @return JsonResponse
     * @throws FileDuplicationException
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function store(StoreFileRequest $request): JsonResponse
    {
        $this->authorize('upload', FileManager::class);

        $res = $this->service->upload(
            $request->input('path', ''),
            $request->file('file'),
            $this->_getCorrectStorage($request, $request->string('disk', '')),
            (bool)$request->input('overwrite', false)
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در بارگذاری فایل',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'بارگذاری فایل با موفقیت انجام شد.',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     */
    public function show(Request $request): BinaryFileResponse|JsonResponse
    {
        $user = $request->user();
        $isAuthenticated = !!$user?->can(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FILE_MANAGER
        ));

        $file = $request->input('file');
        $size = $request->input('size');

        if (empty($file)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'فایل وارد شده برای نمایش نامعتبر می‌باشد.',
            ], ResponseCodes::HTTP_BAD_REQUEST);
        }

        $theFile = $this->service->findFile(
            $file,
            $this->_getCorrectStorage($request, $request->string('disk', '')),
            $size,
            $isAuthenticated
        );

        if (empty($theFile)) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }

        return response()->file($theFile);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function createDirectory(FileRequest $request): JsonResponse
    {
        $this->authorize('create', FileManager::class);

        $res = $this->service->createDirectory(
            $request->input('name', ''),
            $request->input('path', ''),
            $this->_getCorrectStorage($request, $request->string('disk', ''))
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد پوشه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'پوشه ایجاد شد.',
        ]);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws FileDuplicationException
     * @throws AuthorizationException
     */
    public function rename(FileRequest $request): JsonResponse
    {
        $this->authorize('update', FileManager::class);

        $res = $this->service->rename(
            $request->input('path', ''),
            $request->input('old_name', ''),
            $request->input('new_name', ''),
            $this->_getCorrectStorage($request, $request->string('disk', '')),
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در تغییر نام',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'تغییر نام با موفقیت انجام شد.',
        ]);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function move(FileRequest $request): JsonResponse
    {
        $this->authorize('update', FileManager::class);

        $res = $this->service->move(
            $request->input('files', []),
            $request->input('destination', ''),
            $this->_getCorrectStorage($request, $request->string('disk', ''))
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در جابجایی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'جابجایی با موفقیت انجام شد.',
        ]);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function copy(FileRequest $request): JsonResponse
    {
        $this->authorize('update', FileManager::class);

        $res = $this->service->copy(
            $request->input('files', []),
            $request->input('destination', ''),
            $this->_getCorrectStorage($request, $request->string('disk', ''))
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در جابجایی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'جابجایی با موفقیت انجام شد.',
        ]);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidDiskException
     * @throws InvalidFileException
     * @throws InvalidPathException
     */
    public function destroy(FileRequest $request): JsonResponse
    {
        $user = $request->user();
        $isAuthenticated = !!$user?->can(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FILE_MANAGER
        ));

        $path = $request->input('path', '');
        $pathInfo = pathinfo($path);
        $file = $pathInfo['filename'];

        if (!empty($pathInfo['extension'])) {
            /**
             * @var FileManager $dbFile
             */
            $dbFile = $this->service->find($path, $isAuthenticated);

            if (!$dbFile) throw new InvalidFileException();

            $this->authorize('delete', $dbFile);

            $file = $pathInfo['basename'];
        }

        $res = $this->service->delete(
            $file,
            $pathInfo['dirname'] ?: '',
            $this->_getCorrectStorage($request, $request->string('disk', ''))
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در حذف',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'حذف با موفقیت انجام شد.',
        ], ResponseCodes::HTTP_NO_CONTENT);
    }

    /**
     * @param FileRequest $request
     * @return JsonResponse
     * @throws InvalidDiskException
     * @throws InvalidPathException
     * @throws AuthorizationException
     */
    public function batchDestroy(FileRequest $request): JsonResponse
    {
        $this->authorize('batchDelete', FileManager::class);

        $res = $this->service->delete(
            $request->input('files', []),
            $request->input('path', ''),
            $this->_getCorrectStorage($request, $request->string('disk', ''))
        );

        if (!$res) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در حذف',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'حذف با موفقیت انجام شد.',
        ], ResponseCodes::HTTP_NO_CONTENT);
    }

    /**
     * @param $file
     * @param FileRequest $request
     * @return mixed
     * @throws InvalidDiskException
     * @throws InvalidFileException
     * @throws AuthorizationException
     */
    public function download($file, FileRequest $request): mixed
    {
        $this->authorize('download', FileManager::class);

        $path = $request->input('path', '');
        $disk = $this->_getCorrectStorage($request, $request->string('disk', ''));

        return $this->service->download($file, $path, $disk);
    }

    /**
     * @param Request $request
     * @param string $disk
     * @return string
     */
    public function _getCorrectStorage(Request $request, string $disk): string
    {
        $user = $request->user();

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
            $disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC;
        }

        return $disk;
    }
}
