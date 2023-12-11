<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\AlreadyLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class SignupController extends Controller
{
    protected string $sessionActivation = 'activation_session';

    /**
     * @param AuthServiceInterface $service
     */
    public function __construct(
        protected readonly AuthServiceInterface $service
    )
    {
    }

    /**
     * @param SignupRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function store(SignupRequest $request): JsonResponse
    {
        $this->checkLoginNSession(false);
        Session::forget($this->sessionActivation);

        $username = $request->validated(['username']);
        Session::put($this->sessionActivation, $username);

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
        $status = $this->service->verifyActivationCode(Session::get($this->sessionActivation), $code);

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
    public function assignPassword(NewPasswordRequest $request): JsonResponse
    {
        $this->checkLoginNSession();

        $user = $this->service->getUserByUsername(Session::pull($this->sessionActivation));
        $password = $request->validated(['password']);

        if (!$user instanceof User) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'شماره موبایل شما نامعتبر می‌باشد!',
            ]);
        }

        $status = $this->service->assignPassword($user, $password);

        if ($status) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'کلمه عبور شما ثبت شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت کلمه عبور',
            ]);
        }
    }

    /**
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function resendCode(): JsonResponse
    {
        $this->checkLoginNSession(false);

        $status = $this->service->sendActivationVerificationCode(Session::get($this->sessionActivation));

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

        if ($checkFurther && !Session::has($this->sessionActivation))
            throw new InvalidArgumentException('لطفا ابتدا شماره موبایل خود را وارد نمایید.');
    }
}
