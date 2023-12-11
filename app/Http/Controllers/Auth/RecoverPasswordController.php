<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\AlreadyLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\CheckUserRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class RecoverPasswordController extends Controller
{
    protected string $sessionRecoverPass = 'recover_password_session';

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
        $this->checkLoginNSession(false);
        Session::forget($this->sessionRecoverPass);

        $username = $request->validated(['username']);
        Session::put($this->sessionRecoverPass, $username);

        return $this->resendCode();
    }

    /**
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $this->checkLoginNSession();

        $code = $request->validated(['code']);
        $status = $this->service->verifyForgetPasswordCode(Session::get($this->sessionRecoverPass), $code);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'کد وارد شده نادرست است.',
            ]);
        }
    }

    /**
     * @param NewPasswordRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function assignNewPassword(NewPasswordRequest $request): JsonResponse
    {
        $this->checkLoginNSession();

        $user = $this->service->getUserByUsername(Session::pull($this->sessionRecoverPass));
        $password = $request->validated(['password']);

        if (!$user instanceof User) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'شماره موبایل شما نامعتبر می‌باشد!',
            ]);
        }

        $status = $this->service->resetPassword($user, $password);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کلمه عبور با موفقیت بازنشانی شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در بازنشانی کلمه عبور',
            ]);
        }
    }

    /**
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function resendCode(): JsonResponse
    {
        $this->checkLoginNSession();

        $status = $this->service->sendForgetPasswordVerificationCode(Session::get($this->sessionRecoverPass));

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کد تایید برای شما ارسال شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ارسال کد تایید، لطفا دوباره تلاش نمایید.',
            ]);
        }
    }

    /**
     * @param bool $checkFurther
     * @return void
     * @throws AlreadyLoggedInException
     */
    protected function checkLoginNSession(bool $checkFurther = true): void
    {
        if (Auth::check()) throw new AlreadyLoggedInException();

        if ($checkFurther && !Session::has($this->sessionRecoverPass))
            throw new InvalidArgumentException('لطفا ابتدا شماره موبایل خود را وارد نمایید.');
    }
}
