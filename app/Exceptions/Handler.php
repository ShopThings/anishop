<?php

namespace App\Exceptions;

use App\Enums\Responses\ResponseTypesEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException as SymphonyFileNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function invalidJson($request, ValidationException $exception)
    {
        $messages = $exception->validator->errors()->all();
        if (!count($messages) || !is_string($messages[0])) {
            return $exception->validator->getTranslator()->get('داده‌های ارسال شده نامعتبر می‌باشند.');
        }
        $message = array_shift($messages);
        if ($count = count($messages)) {
            $message .= ' (' . 'و' . " $count " . 'خطای دیگر' . ')';
        }

        return response()->json([
            'message' => $message,
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
//        $this->reportable(function (Throwable $e) {
//            //
//        });

        // custom not found message for APIs
        $this->renderable(function (FileNotFoundException|SymphonyFileNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => 'فایل/پوشه مورد نظر وجود ندارد!',
                ], ResponseCodes::HTTP_NOT_FOUND);
            }
        });

        // custom not found message for APIs
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => 'API درخواست شده پیدا نشد!',
                ], ResponseCodes::HTTP_NOT_FOUND);
            }
        });

        // custom un-authentication message for APIs
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => 'ابتدا عملیات ورود را انجام داده و سپس درخواست را مجددا ارسال نمایید.',
                ], ResponseCodes::HTTP_FORBIDDEN);
            }
        });

        // custom unauthorized message for APIs
        $this->renderable(function (AuthorizationException|UnauthorizedException|AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => 'شما اجازه انجام این عملیات را ندارید.',
                ], ResponseCodes::HTTP_UNAUTHORIZED);
            }
        });

        // handle lazy loading violation of models
        Model::handleLazyLoadingViolationUsing(function (Model $model, string $relation) {
            $class = get_class($model);
            info("Attempted to lazy load [" . $relation . "] on model [" . $class . "].");
        });
    }
}
