<?php

namespace App\Exceptions;

use App\Enums\Responses\ResponseTypesEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\Permission\Exceptions\UnauthorizedException as SpatieUnauthorizedException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException as SymphonyFileNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            $msg = 'فایل/پوشه مورد نظر وجود ندارد!';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => $msg,
                ], ResponseCodes::HTTP_NOT_FOUND);
            }

            return response($msg);
        });

        // custom not found message for APIs
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $msg = 'آیتم/API درخواست شده پیدا نشد!';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => $msg,
                ], ResponseCodes::HTTP_NOT_FOUND);
            }

            return response($msg);
        });

        // custom un-authentication message for APIs
        $this->renderable(function (AuthenticationException $e, $request) {
            $msg = 'ابتدا عملیات ورود را انجام داده و سپس درخواست را مجددا ارسال نمایید.';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => $msg,
                ], ResponseCodes::HTTP_FORBIDDEN);
            }

            return response($msg);
        });

        // custom unauthorized message for APIs
        $this->renderable(function (
            AuthorizationException|SpatieUnauthorizedException|AccessDeniedHttpException $e,
                                                                                         $request
        ) {
            $msg = 'شما اجازه انجام این عملیات را ندارید.';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => $msg,
                ], ResponseCodes::HTTP_UNAUTHORIZED);
            }

            return response($msg);
        });

        // custom invalid argument message for APIs
        $this->renderable(function (InvalidArgumentException $e, $request) {
            $msg = $e->getMessage() ?: 'پارامترهای ارسال شده نامعتبر می‌باشد.';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'type' => ResponseTypesEnum::ERROR->value,
                    'message' => $msg,
                ], ResponseCodes::HTTP_UNAUTHORIZED);
            }

            return response($msg);
        });

        // handle lazy loading violation of models
        Model::handleLazyLoadingViolationUsing(function (Model $model, string $relation) {
            $class = get_class($model);
            info("Attempted to lazy load [" . $relation . "] on model [" . $class . "].");
        });
    }
}
