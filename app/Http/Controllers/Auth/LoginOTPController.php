<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\AlreadyLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginOTPRequest;
use App\Http\Requests\Auth\LoginOTPSendCodeRequest;
use App\Http\Requests\Auth\LoginOTPVerifyCodeRequest;
use App\Http\Resources\Showing\UserAuthShowResource;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class LoginOTPController extends Controller
{
    /**
     * @param AuthServiceInterface $service
     * @param UserServiceInterface $userService
     */
    public function __construct(
        protected readonly AuthServiceInterface $service,
        protected readonly UserServiceInterface $userService
    )
    {
    }

    /**
     * @param LoginOTPRequest $request
     * @return JsonResponse
     */
    public function checkMobile(LoginOTPRequest $request): JsonResponse
    {
        $username = $request->validated('username');

        $user = $this->service->getUserByUsername($username);
        if (!is_null($user)) {
            return $this->sendOTP($username);
        }

        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'شماره موبایل وارد شده نامعتبر می‌باشد.',
        ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param string $username
     * @return JsonResponse
     */
    protected function sendOTP(string $username): JsonResponse
    {
        $status = $this->service->sendOTP($username);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'رمز یکبار مصرف برای شما ارسال شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ارسال رمز یکبار مصرف، لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param LoginOTPSendCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function resendCode(LoginOTPSendCodeRequest $request): JsonResponse
    {
        $this->checkLogin();

        $username = $request->validated('username');
        return $this->sendOTP($username);
    }

    /**
     * @return void
     * @throws AlreadyLoggedInException
     */
    protected function checkLogin(): void
    {
        if (Auth::check()) throw new AlreadyLoggedInException();
    }

    /**
     * @param LoginOTPVerifyCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     * @throws ValidationException
     */
    public function verifyCode(LoginOTPVerifyCodeRequest $request): JsonResponse
    {
        $this->checkLogin();

        $code = $request->validated('code');
        $username = $request->validated('username');
        $token = $this->service->verifyOTP($request, $username, $code);

        if (is_null($token)) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                'user' => new UserAuthShowResource($user),
                'token' => $token,
            ],
        ]);
    }
}
