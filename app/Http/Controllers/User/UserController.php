<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\FavoriteProductResource;
use App\Http\Resources\Home\CartResource;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSingleResource;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Cart\Cart;
use App\Support\Filter;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
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
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);
        return UserResource::collection($this->service->getUsers($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد کاربر با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد کاربر',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserSingleResource
     */
    public function show(User $user): UserSingleResource
    {
        Gate::authorize('view', $user);
        return new UserSingleResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserSingleResource|JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): UserSingleResource|JsonResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();
        unset($validated['username']);

        // check permissions
        if (Gate::denies('canMakeDeletable', User::class)) {
            unset($validated['is_deletable']);
        }
        if (Gate::denies('canBan', $user)) {
            unset($validated['is_banned']);
            unset($validated['ban_desc']);
        }

        $model = $this->service->updateById($user->id, $validated);

        if (!is_null($model)) {
            return new UserSingleResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش اطلاعات کاربر',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        Gate::authorize('delete', $user);

        $permanent = $request->user()->id === $user->id;
        $res = $this->service->deleteById($user->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchDestroy(Request $request): JsonResponse
    {
        Gate::authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $ids = Arr::flatten($ids);
        Arr::where($ids, function ($value) use ($request) {
            return $value != $request->user()?->id;
        });

        $res = $this->service->batchDeleteByIds($ids, considerDeletable: true);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function addresses(Filter $filter, User $user): AnonymousResourceCollection
    {
        Gate::authorize('view', $user);
        return AddressResource::collection($this->service->getUserAddresses(user: $user, filter: $filter));
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function favoriteProducts(Filter $filter, User $user): AnonymousResourceCollection
    {
        Gate::authorize('view', $user);
        return FavoriteProductResource::collection($this->service->getUserFavoriteProduct(
            user: $user, filter: $filter
        ));
    }

    /**
     * @param Filter $filter
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function purchases(Filter $filter, User $user): AnonymousResourceCollection
    {
        Gate::authorize('view', $user);
        return PurchaseResource::collection($this->service->getUserPurchases(user: $user, filter: $filter));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function carts(User $user): JsonResponse
    {
        Gate::authorize('view', $user);

        $cartNames = config('market.cart_name');

        if (!isset($cartNames['default']) || !isset($cartNames['wishlist'])) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در سرور: عدم دسترسی به سبد‌های خرید',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        $carts = Cart::instance()->ownedBy($user)->restoreAll();

        // add name to carts
        $carts[$cartNames['default']] = [
            'name' => 'سبد خرید پیش فرض',
            'items' => CartResource::collection($carts[$cartNames['default']]),
        ];
        $carts[$cartNames['wishlist']] = [
            'name' => 'سبد خرید پشتیبان',
            'items' => CartResource::collection($carts[$cartNames['wishlist']]),
        ];

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $carts,
        ]);
    }
}
