<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\FavoriteProductResource;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserController extends Controller
{
    use ControllerPaginateTrait;

    /**
     * @param UserServiceInterface $service
     */
    public function __construct(
        protected readonly UserServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return UserResource::collection($this->service->getUsers(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد کاربر با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد کاربر',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();
        unset($validated['username']);
        $model = $this->service->updateById($user->id, $validated);

        if (!is_null($model)) {
            return new UserResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش اطلاعات کاربر',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        $permanent = $request->user()->id === $user->id;
        $res = $this->service->deleteById($user->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchDestroy(Request $request)
    {
        $this->authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $ids = Arr::flatten($ids);
        Arr::where($ids, function ($value) use ($request) {
            return $value != $request->user()?->id;
        });

        $res = $this->service->batchDeleteByIds($ids);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function addresses(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $params = $this->getPaginateParameters($request);

        return AddressResource::collection($this->service->getUserAddresses(
            user: $user, searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function favoriteProducts(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $params = $this->getPaginateParameters($request);

        return FavoriteProductResource::collection($this->service->getUserFavoriteProduct(
            user: $user, searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function purchases(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $params = $this->getPaginateParameters($request);

        return PurchaseResource::collection($this->service->getUserPurchases(
            user: $user, searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function carts(User $user)
    {
        $this->authorize('view', $user);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getUserCarts($user),
        ]);
    }
}
