<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Enums\Results\CouponResultEnum;
use App\Exceptions\InvalidCartNameException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\CouponResource as HomeCouponResource;
use App\Services\Contracts\CouponServiceInterface;
use App\Support\Cart\Cart;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeCouponController extends Controller
{
    /**
     * @param CouponServiceInterface $service
     */
    public function __construct(
        protected CouponServiceInterface $service
    )
    {
    }

    /**
     * @param Request $request
     * @param string $code
     * @return HomeCouponResource|JsonResponse
     * @throws InvalidCartNameException
     * @throws BindingResolutionException
     */
    public function check(Request $request, string $code): HomeCouponResource|JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            throw new UnauthorizedException('ابتدا به سایت وارد شوید سپس دوباره تلاش نمایید.');
        }

        $cartName = $request->string('cart_name', '');
        $items = $request->string('items', '');

        if (!is_array($items)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'محصولات انتخاب شده قابل شناسایی نیستند!',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        } elseif (!count($items)) {
            return response()->json([
                'type' => ResponseTypesEnum::INFO->value,
                'message' => 'ابتدا محصولات خود را انتخاب کنید سپس برای محاسبه هزینه ارسال اقدام نمایید.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cart = Cart::instance($cartName)->ownedBy($user)->validate($items, true);

        if (!$cart->calculations()->count()) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'هیچ محصولی برای بررسی و اعمال کد تخفیف وجود ندارد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupon = $this->service->checkCoupon($code, $user);

        if ($coupon instanceof Model) {
            if ($coupon->canApplyOn($cart->totalDiscountedPrice())) {
                return new HomeCouponResource($coupon);
            } else {
                $msg = 'کد تخفیف وارد شده قابل اعمال بر روی محدوده قیمت ';
                if ($coupon->apply_min_price) {
                    $msg .= 'شروع از ' . number_format($coupon->apply_min_price) . ' تومان ';
                }
                if ($coupon->apply_max_price) {
                    $msg .= 'تا سقف ' . number_format($coupon->apply_max_price) . ' تومان ';
                }
                $msg .= ' می‌باشد.';

                return response()->json([
                    'type' => ResponseTypesEnum::WARNING->value,
                    'message' => $msg,
                ]);
            }
        }

        if ($coupon === CouponResultEnum::LIMITED) {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'در حال حاضر امکان استفاده این کد تخفیف را ندارید.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'کد تخفیف وارد شده نامعتبر می‌باشد.',
            ]);
        }
    }
}
