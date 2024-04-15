<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\GatewayPaymentResource;
use App\Models\GatewayPayment;
use App\Models\User;
use App\Support\Helper\PaymentHelper;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{
    /**
     * @param GatewayPayment $payment
     * @return RedirectResponse
     * @throws Exception
     */
    public function verify(GatewayPayment $payment): RedirectResponse
    {
        PaymentHelper::verify($payment);

        $frontendUrl = config(
            'market.order.gateway_proxy_callback_url',
            config('market.frontend_url', 'http://localhost')
        );
        return redirect()->to($frontendUrl . '?g=' . $payment->id);
    }

    /**
     * @param Request $request
     * @param GatewayPayment $id
     * @return GatewayPaymentResource
     */
    public function verificationResult(Request $request, GatewayPayment $id): GatewayPaymentResource
    {
        $user = $this->getLoggedInUser($request);
        $orderDetail = $id?->order?->detail;

        if (is_null($orderDetail) || $orderDetail->user_id !== $user->id) {
            throw new NotFoundHttpException();
        }

        return new GatewayPaymentResource($id);
    }

    /**
     * @param Request $request
     * @return User
     */
    private function getLoggedInUser(Request $request): User
    {
        $user = $request->user();
        if (is_null($user)) {
            throw new UnauthorizedException('ابتدا به سایت وارد شوید سپس دوباره تلاش نمایید.');
        }

        return $user;
    }
}
