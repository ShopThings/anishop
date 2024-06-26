<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Exceptions\AlreadyLoggedInException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Http\Resources\Showing\UserAuthShowResource;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class SignupController extends Controller
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
     * @param SignupRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function store(SignupRequest $request): JsonResponse
    {
        $username = $request->validated('username');

        // add user to database
        // username is either exists and not verified or not exists at all
        $user = $this->service->getUserByUsername($username);
        if (is_null($user)) {
            $this->userService->createTemporaryUser($username);
        } elseif ($user->isVerified()) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'کاربر با شماره موبایل وارد شده قبلا ثبت نام شده است.',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        return $this->sendVerificationCode($username);
    }

    /**
     * @param string $username
     * @return JsonResponse
     */
    protected function sendVerificationCode(string $username): JsonResponse
    {
        $status = $this->service->sendActivationVerificationCode($username);

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
     * @param SendCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function resendCode(SendCodeRequest $request): JsonResponse
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
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $this->checkLogin();

        $code = $request->validated('code');
        $username = $request->validated('username');
        $status = $this->service->verifyActivationCode($username, $code);

        if ($status) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'کد وارد شده نادرست است.',
        ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param NewPasswordRequest $request
     * @return JsonResponse
     * @throws AlreadyLoggedInException
     */
    public function assignPassword(NewPasswordRequest $request): JsonResponse
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

        $status = $this->service->assignPassword($user, $password);

        if ($status) {
            $loggedIn = Auth::loginUsingId($user->id);
            if ($loggedIn) {
                $tokenName = config('market.token_name.main');
                $expireAt = Carbon::now()->addDays(30);
                $token = $user->createToken(name: $tokenName, expiresAt: $expireAt)->plainTextToken;
                return response()->json([
                    'type' => ResponseTypesEnum::SUCCESS->value,
                    'data' => [
                        'user' => new UserAuthShowResource($user),
                        'token' => $token,
                    ],
                ]);
            }

            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => null,
            ]);
        }

        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت کلمه عبور',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
