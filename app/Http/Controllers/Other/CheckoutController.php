<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\LoginNeededException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\Home\OrderDetailResource as HomeOrderDetailResource;
use App\Models\OrderDetail;
use App\Models\User;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Cart\Cart;
use App\Support\Helper\OrderHelper;
use App\Support\Helper\PaymentHelper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderServiceInterface $service
    )
    {
    }

    /**
     * @param StoreOrderRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws LoginNeededException
     */
    public function placeOrder(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // authentication check is in request class
        $user = $this->getLoggedInUser($request);
        $cart = Cart::instance($validated['cart_name'])->ownedBy($user)
            ->validate($validated['items'] ?? [], true);

        // we don't need cart_name in validated array
        unset($validated['cart_name']);

        // check if any validated items is in cart
        if (!$cart->calculations()->count()) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'سبد خرید انتخاب شده خالی می‌باشد. لطفا پیش از ثبت سفارش، محصولات خود را انتخاب نمایید.',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        $orderDetail = $this->service->placeOrder($user, $validated, $cart);

        if (!is_null($orderDetail)) {
            // make current cart empty in both local and database(after order placed)
            $cart->removeAll()->destroy();

            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'سفارش شما با موفقیت ثبت شد. نسبت به پرداخت آن تا زمان مشخص شده اقدام نمایید😉',
                'data' => new HomeOrderDetailResource($orderDetail),
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد سفارش، لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws LoginNeededException
     * @throws NotFoundExceptionInterface
     */
    public function pay(Request $request, $id): JsonResponse
    {
        $user = $this->getLoggedInUser($request);

        // This is not necessary but make order validation more reliable
        $orderCode = $request->input('order_code', '');

        // check order code
        /**
         * @var OrderDetail $detail
         */
        $detail = $this->service->getUserOrderByCode($user->id, $orderCode);

        if (is_null($detail)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'سفارش انتخاب شده نامعتبر می‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check if id is existing
        $orderPayment = $detail->orders()->findOrFail($id);

        // check if it's already paid
        if ($orderPayment->hasPaid()) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'پرداخت این سفارش انجام شده است.',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        if (OrderHelper::calculateRemainedPayTime($detail) <= 0) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'زمان پرداخت سفارش به پایان رسیده است. در صورتی که پرداخت موفقی انجام داده‌اید، برای بازگشت وجه با پشتیبانی تماس حاصل نمایید.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check payment method
        $paymentMethod = $orderPayment->paymentMethod;

        if (is_null($paymentMethod)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'روش پرداخت برای این سفارش وجود ندارد!',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        // connect to gateway and get transaction id
        $result = PaymentHelper::pay($orderPayment->id, $orderPayment->must_pay_price, $paymentMethod);

        // return needed gateway connection fields to user
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => json_decode($result, true),
        ]);
    }

    /**
     * @param Request $request
     * @return User
     * @throws LoginNeededException
     */
    private function getLoggedInUser(Request $request): User
    {
        $user = $request->user();
        if (is_null($user)) {
            throw new LoginNeededException('ابتدا به سایت وارد شوید سپس دوباره تلاش نمایید.');
        }

        return $user;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws LoginNeededException
     * @throws NotFoundExceptionInterface
     */
    public function calculateSendPrice(Request $request): JsonResponse
    {
        $user = $this->getLoggedInUser($request);

        $province = $request->integer('province');
        $city = $request->integer('city');
        $cartName = $request->string('cart_name', '');
        $items = $request->input('items', []);
        $sendMethodId = $request->integer('send_method');

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

        $res = OrderHelper::shippingPriceCalculation($province, $city, $cart, $sendMethodId);

        $status = $res['status'];
        unset($res['status']);

        if (is_null($res['message'])) {
            unset($res['message']);
        }

        return response()->json($res, $status);
    }
}
