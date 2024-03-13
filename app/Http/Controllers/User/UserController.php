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
use App\Http\Resources\UserSingleResource;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserController extends Controller
{
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
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection($this->service->getUsers($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreUserRequest $request): JsonResponse
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
     * @return UserSingleResource
     * @throws AuthorizationException
     */
    public function show(User $user): UserSingleResource
    {
        $this->authorize('view', $user);
        return new UserSingleResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserSingleResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): UserSingleResource|JsonResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validated();
        unset($validated['username']);
        $model = $this->service->updateById($user->id, $validated);

        if (!is_null($model)) {
            return new UserSingleResource($model);
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
    public function destroy(Request $request, User $user): JsonResponse
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
    public function batchDestroy(Request $request): JsonResponse
    {
        $this->authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $ids = Arr::flatten($ids);
        Arr::where($ids, function ($value) use ($request) {
            return $value != $request->user()?->id;
        });

        $res = $this->service->batchDeleteByIds($ids, considerDeletable: true);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function addresses(Filter $filter, User $user): AnonymousResourceCollection
    {
        $this->authorize('view', $user);
        return AddressResource::collection($this->service->getUserAddresses(user: $user, filter: $filter));
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function favoriteProducts(Filter $filter, User $user): AnonymousResourceCollection
    {
        $this->authorize('view', $user);
        return FavoriteProductResource::collection($this->service->getUserFavoriteProduct(
            user: $user, filter: $filter
        ));
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function purchases(Filter $filter, User $user): AnonymousResourceCollection
    {
        $this->authorize('view', $user);
        return PurchaseResource::collection($this->service->getUserPurchases(user: $user, filter: $filter));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function carts(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getUserCarts($user),
        ]);
    }
}
