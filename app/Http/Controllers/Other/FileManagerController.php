<?php

namespace App\Http\Controllers\Other;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
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
use App\Services\FileService;
use App\Support\Gate\PermissionHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
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
            $request->input('path', ''),
            $request->input('disk', ''),
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
            $request->input('disk', ''),
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
     * @param $file
     * @param $size
     * @param FileRequest $request
     * @return JsonResponse|BinaryFileResponse
     */
    public function show($file, $size, FileRequest $request): BinaryFileResponse|JsonResponse
    {
        $user = $request->user();
        $isAuthenticated = !!$user?->can(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FILE_MANAGER
        ));

        $theFile = $this->service->findFile($file, $request->input('disk'), $size, $isAuthenticated);
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
            $request->input('disk', '')
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
            $request->input('disk', '')
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
            $request->input('disk', '')
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
            $request->input('disk', '')
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
     * @param $file
     * @param FileRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidDiskException
     * @throws InvalidFileException
     * @throws InvalidPathException
     */
    public function destroy($file, FileRequest $request): JsonResponse
    {
        $path = $request->input('path', '');
        /**
         * @var FileManager $dbFile
         */
        $dbFile = $this->service->find($path . '/' . rtrim($file));

        if (!$dbFile) throw new InvalidFileException();

        $this->authorize('delete', $dbFile);

        $res = $this->service->delete($dbFile, null, $request->input('disk', ''));

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
            $request->input('files', ''),
            $request->input('path', ''),
            $request->input('disk', '')
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
        $disk = $request->input('disk', '');

        return $this->service->download($file, $path, $disk);
    }
}
