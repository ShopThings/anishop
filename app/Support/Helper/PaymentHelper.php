<?php

namespace App\Support\Helper;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Events\OrderPaidEvent;
use App\Events\PaymentUnverifiedEvent;
use App\Events\PaymentVerifiedEvent;
use App\Models\GatewayPayment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\Contracts\OrderServiceInterface;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Payment\Facade\Payment;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class PaymentHelper
{
    /**
     * It'll return json encoded required redirect parameters
     *
     * @param string $orderId
     * @param int $amount
     * @param PaymentMethod $method
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function pay(string $orderId, int $amount, PaymentMethod $method): string
    {
        /**
         * @var OrderServiceInterface $orderService
         */
        $orderService = app()->get(OrderServiceInterface::class);

        $payment = $orderService->createGatewayPayment([
            'order_id' => $orderId,
            'gateway_type' => $method->bank_gatewat_type ?? null,
        ]);

        // Payment should just work on production(not reasonable on development and testing phase)
        if (!app()->isProduction()) {
            // Make gateway payment to success payment
            $payment->setTransaction('987654321');
            $payment->setReceipt('123456789');
            $payment->setStatus(true);
            $payment->setAsPaid();

            $payment->save();
            $payment->refresh();

            // Also make order chunk of this payment to successful status
            $order = $payment->order;
            $order->payment_status = PaymentStatusesEnum::SUCCESS->value;
            $order->payment_status_changed_at = now();
            $order->paid_at = now();
            $order->save();

            $frontendUrl = config(
                'market.order.gateway_proxy_callback_url',
                config('market.frontend_url', 'http://localhost')
            );

            return json_encode([
                'action' => $frontendUrl,
                'inputs' => [
                    'g' => $payment->id,
                ],
                'method' => 'get',
            ]);
        }

        return Payment::via($method->bank_gateway_type)
            ->config(self::getPaymentDriverConfig($method))
            ->callbackUrl(route('payment.verify', [$payment]))
            ->amount($amount)
            ->purchase(null, function ($driver, $transactionId) use ($payment) {
                $payment->setTransaction($transactionId);
            })
            ->pay()
            ->toJson();
    }

    /**
     * @param PaymentMethod $method
     * @return array|null[]
     */
    private static function getPaymentDriverConfig(PaymentMethod $method): array
    {
        $options = $method->options;

        switch ($method->bank_gatway_type) {
            case GatewaysEnum::BEHPARDAKHT:
                return [
                    'terminalId' => $options['terminal_id'] ?? null,
                    'username' => $options['username'] ?? null,
                    'password' => $options['password'] ?? null,
                ];
            case GatewaysEnum::IDPAY:
                return [
                    'merchantId' => $options['api_key'] ?? null,
                ];
            case GatewaysEnum::IRANKISH:
                return [
                    'terminalId' => $options['terminal_id'] ?? null,
                    'password' => $options['password'] ?? null,
                    'acceptorId' => $options['acceptor_id'] ?? null,
                    'pubKey' => $options['public_key'] ?? null,
                ];
            case GatewaysEnum::PARSIAN:
                return [
                    'merchantId' => $options['password'] ?? null,
                ];
            case GatewaysEnum::SADAD:
                return [
                    'key' => $options['password'] ?? null,
                    'merchantId' => $options['merchant_id'] ?? null,
                    'terminalId' => $options['terminal_id'] ?? null,
                ];
            case GatewaysEnum::SEPEHR:
                return [
                    'terminalId' => $options['terminal_id'] ?? null,
                ];
            case GatewaysEnum::ZARINPAL:
                return [
                    'merchantId' => $options['merchant_id'] ?? null,
                ];
        }

        return [];
    }

    /**
     * @param GatewayPayment $payment
     * @return array
     * @throws Exception
     */
    public static function verify(GatewayPayment $payment): array
    {
        $type = ResponseTypesEnum::SUCCESS->value;
        $message = null;
        $data = null;
        $status = ResponseCodes::HTTP_OK;

        $method = $payment->order->paymentMethod;

        if (is_null($method)) {
            $type = ResponseTypesEnum::ERROR->value;
            $message = 'تراکنش نامعتبر می‌باشد.';
            $status = ResponseCodes::HTTP_NOT_ACCEPTABLE;

            return compact('type', 'message', 'data', 'status');
        }

        /**
         * @var Order $order
         */
        $order = $payment->order;
        $orderDetail = $order->detail;

        $user = self::getLoggedInUser();

        // Payment should just work on production(not reasonable on development)
        if (!app()->isProduction()) {
            if (!is_null($user)) {
                PaymentVerifiedEvent::dispatch($user, $orderDetail);
            }

            return compact('type', 'message', 'data', 'status');
        }

        try {

            $amount = $order->must_pay_price;
            $transactionId = $payment->transaction;

            $receipt = Payment::via($payment->gateway_type)
                ->config(self::getPaymentDriverConfig($method))
                ->amount($amount)
                ->transactionId($transactionId)
                ->verify();

            $payment->setReceipt($receipt->getReferenceId());
            $payment->setStatus(true);
            $payment->setAsPaid();

            // show payment referenceId to the user.
            $data = $receipt->getReferenceId();

            $order->payment_status = PaymentStatusesEnum::SUCCESS->value;
            $order->payment_status_changed_at = now();
            $order->paid_at = now();
            $order->save();

            if ($orderDetail->hasCompletePaid()) {
                OrderPaidEvent::dispatch($orderDetail);
            }

            if (!is_null($user)) {
                PaymentVerifiedEvent::dispatch($user, $orderDetail);
            }
        } catch (InvalidPaymentException|InvoiceNotFoundException $e) {
            /**
             * when payment is not verified, it will throw an exception.
             * We can catch the exception to handle invalid payments.
             * getMessage method, returns a suitable message that can be used in user interface.
             **/
            $message = $e->getMessage();
            $type = ResponseTypesEnum::ERROR->value;
            $status = ResponseCodes::HTTP_UNPROCESSABLE_ENTITY;

            // update result message of payment
            $payment->message = $message;
            $payment->save();

            if (!is_null($user)) {
                PaymentUnverifiedEvent::dispatch($user, $orderDetail, $message);
            }
        }

        return compact('type', 'message', 'data', 'status');
    }

    /**
     * @return Authenticatable|null
     */
    private static function getLoggedInUser(): ?Authenticatable
    {
        return Auth::user();
    }
}
