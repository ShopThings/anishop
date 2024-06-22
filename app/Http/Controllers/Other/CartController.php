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
     * @return User
     * @throws LoginNeededException
     */
    protected function getCurrentUserWithAuthentication(): User
    {
        $user = request()->user();

        if (is_null($user)) {
            throw new LoginNeededException('ابتدا به سایت وارد شوید سپس دوباره تلاش نمایید.');
        }

        return $user;
    }

    /**
     * @param string $cart
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function show(string $cart): JsonResponse
    {
        $user = $this->getCurrentUserWithAuthentication();

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => CartResource::collection(Cart::instance($cart)->ownedBy($user)->restore()),
        ], ResponseCodes::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse|bool
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function destroy(Request $request): JsonResponse|bool
    {
        $user = $this->getCurrentUserWithAuthentication();

        $res = Cart::instance($request->string('cart_name'))->ownedBy($user)->destroy();

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'سبد خرید شما حذف شد.',
            ], ResponseCodes::HTTP_OK);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در حذف سبد خرید!',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return JsonResponse|bool
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function store(Request $request): JsonResponse|bool
    {
        $user = $this->getCurrentUserWithAuthentication();

        $items = $request->input('items');
        $res = Cart::instance($request->string('cart_name'))->ownedBy($user)
            ->validate($items, true)
            ->store();

        if ($res) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'سبد خرید شما ذخیره شد.',
            ], ResponseCodes::HTTP_OK);
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
     */
    public function sessionCarts(Request $request): JsonResponse
    {
        $carts = $request->input('carts');

        if (empty($carts)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => [],
            ]);
        }

        $cartNameDefault = config('market.cart_name.default');
        $cartNameWishlist = config('market.cart_name.wishlist');

        if (is_null($cartNameDefault) || is_null($cartNameWishlist)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در سرور: عدم دسترسی به سبد‌های خرید',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        $cartDefault = Cart::instance($cartNameDefault)->validate($carts[$cartNameDefault] ?? []);
        $cartWishlist = Cart::instance($cartNameWishlist)->validate($carts[$cartNameWishlist] ?? []);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                $cartNameDefault => CartResource::collection($cartDefault),
                $cartNameWishlist => CartResource::collection($cartWishlist),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param $code
     * @return JsonResponse|bool
     * @throws BindingResolutionException
     */
    public function addToCart(Request $request, $code): JsonResponse|bool
    {
        $cartName = $request->string('cart_name', '');
        $items = $request->input('items');

        $cart = Cart::instance($cartName);

        $res = $cart
            ->validate($items, true)
            ->add($code, $request->integer('quantity', 1));

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
     * @return JsonResponse|bool
     * @throws BindingResolutionException
     */
    public function addAllToCart(Request $request): JsonResponse|bool
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

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'message' => 'سبد خرید بروزرسانی شد.',
            'data' => CartResource::collection($cart->getContent()),
        ]);
    }
}
