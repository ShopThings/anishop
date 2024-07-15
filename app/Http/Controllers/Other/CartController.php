<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\LoginNeededException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\CartResource;
use App\Models\User;
use App\Support\Cart\Cart;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CartController extends Controller
{
    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function index(): JsonResponse
    {
        $user = $this->getCurrentUserWithAuthentication();

        $carts = Cart::instance()->ownedBy($user)->restoreAll();
        $carts->map(function ($item) {
            return new CartResource($item);
        });

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $carts,
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @param bool $throwException
     * @return User|null
     * @throws LoginNeededException
     */
    protected function getCurrentUserWithAuthentication(bool $throwException = true): ?User
    {
        $user = request()->user();

        if (is_null($user)) {
            if ($throwException) {
                throw new LoginNeededException('ابتدا به سایت وارد شوید سپس دوباره تلاش نمایید.');
            }
            return null;
        }

        return $user;
    }

    /**
     * @param Request $request
     * @param string $cart
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function show(Request $request, string $cart): JsonResponse
    {
        $user = $this->getCurrentUserWithAuthentication();

        $items = $request->input('items', []);

        if (!is_array($items)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => [],
            ], ResponseCodes::HTTP_OK);
        }

        $cart = Cart::instance($cart)->ownedBy($user);
        $cart->restore(true)->mergeWith($items)->storeOrDestroy();

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => CartResource::collection($cart->getContent()),
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->getCurrentUserWithAuthentication();

        $items = $request->input('items', []);

        if (is_array($items)) {
            $cart = Cart::instance($request->string('cart_name'))->ownedBy($user);
            $tmpItems = $cart->validate($items);
            $res = $cart->mergeWith($tmpItems)->storeOrDestroy();

            if ($res) {
                return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
            }
        }

        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ذخیره سبد خرید!',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $this->getCurrentUserWithAuthentication();

        $res = Cart::instance($request->string('cart_name'))->ownedBy($user)->destroy();

        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در خالی نمودن سبد خرید!',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function sessionCarts(Request $request): JsonResponse
    {
        $carts = $request->input('carts');

        $cartNameDefault = config('market.cart_name.default');
        $cartNameWishlist = config('market.cart_name.wishlist');

        if (is_null($cartNameDefault) || is_null($cartNameWishlist)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در سرور: عدم دسترسی به سبد‌های خرید',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        $cartDefault = Cart::instance($cartNameDefault);
        $cartWishlist = Cart::instance($cartNameWishlist);

        $cartDefaultItems = $cartDefault->validate($carts[$cartNameDefault] ?? []);
        $cartWishlistItems = $cartWishlist->validate($carts[$cartNameWishlist] ?? []);

        $user = $this->getCurrentUserWithAuthentication(false);
        if (!is_null($user)) {
            $cartDefaultItems = $cartDefault->ownedBy($user)
                ->restore(true)
                ->mergeWith($cartDefaultItems)
                ->getContent();
            $cartWishlistItems = $cartWishlist->ownedBy($user)
                ->restore(true)
                ->mergeWith($cartWishlistItems)
                ->getContent();
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                $cartNameDefault => CartResource::collection($cartDefaultItems),
                $cartNameWishlist => CartResource::collection($cartWishlistItems),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param $code
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function addToCart(Request $request, $code): JsonResponse
    {
        $cartName = $request->string('cart_name', '');
        $items = $request->input('items');

        $cart = Cart::instance($cartName);

        $res = $cart
            ->validate($items, true)
            ->add($code, $request->integer('quantity', 1));

        $user = $this->getCurrentUserWithAuthentication(false);
        if (!is_null($user)) {
            $items = $cart->getContent();
            $cart->ownedBy($user)->restore(true)->mergeWith($items)->storeOrDestroy();
        }

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصول به سبد خرید اضافه شد.',
                'data' => CartResource::collection($cart->getContent()),
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'امکان اضافه کردن این محصول به سبد خرید وجود ندارد.',
        ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function addAllToCart(Request $request): JsonResponse
    {
        $codesQuantities = $request->input('codes_quantities', []);

        if (!is_array($codesQuantities)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'آیتم‌های ارسال شده برای بروزرسانی، نامعتبر می‌باشند.',
            ]);
        }

        $items = $request->input('items');
        $cartName = $request->string('cart_name', '');
        $cart = Cart::instance($cartName)
            ->validate($items, true)
            ->addAll($codesQuantities);

        $user = $this->getCurrentUserWithAuthentication(false);
        if (!is_null($user)) {
            $items = $cart->getContent();
            $cart->ownedBy($user)->restore(true)->mergeWith($items)->storeOrDestroy();
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'سبد خرید بروزرسانی شد.',
            'data' => CartResource::collection($cart->getContent()),
        ]);
    }
}
