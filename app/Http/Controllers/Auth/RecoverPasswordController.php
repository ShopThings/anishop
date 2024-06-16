<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\AlreadyLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckUserRequest;
use App\Http\Requests\Auth\RecoverNewPasswordRequest;
use App\Http\Requests\Auth\RecoverSendCodeRequest;
use App\Http\Requests\Auth\RecoverVerifyCodeRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class RecoverPasswordController extends Controller
{
    /**
     * @param AuthServiceInterface $service
     */
    public function __construct(
        protected readonly AuthServiceInterface $service
    )
    {
    }

    /**
     * @param CheckUserRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function checkMobile(CheckUserRequest $request): JsonResponse
    {
        $this->checkLogin();

        $username = $request->validated('username');
        return $this->sendVerificationCode($username);
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
     * @param string $username
     * @return JsonResponse
     */
    protected function sendVerificationCode(string $username): JsonResponse
    {
        $status = $this->service->sendForgetPasswordVerificationCode($username);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کد تایید برای شما ارسال شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ارسال کد تایید، لطفا دوباره تلاش نمایید.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param RecoverSendCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function resendCode(RecoverSendCodeRequest $request): JsonResponse
    {
        $this->checkLogin();

        $username = $request->validated('username');
        return $this->sendVerificationCode($username);
    }

    /**
     * @param RecoverVerifyCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function verifyCode(RecoverVerifyCodeRequest $request): JsonResponse
    {
        $this->checkLogin();

        $code = $request->validated('code');
        $username = $request->validated('username');
        $status = $this->service->verifyForgetPasswordCode($username, $code);

        if ($status) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'کد وارد شده نادرست است.',
        ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param RecoverNewPasswordRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function assignNewPassword(RecoverNewPasswordRequest $request): JsonResponse
    {
        $this->checkLogin();

        $password = $request->validated('password');
        $username = $request->validated('username');
        $user = $this->service->getUserByUsername($username);

        if (!$user instanceof User) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'شماره موبایل شما نامعتبر می‌باشد!',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        $status = $this->service->resetPassword($user, $password);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کلمه عبور شما با موفقیت بازنشانی شد.',
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در بازنشانی کلمه عبور',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
