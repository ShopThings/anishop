<?php

namespace App\Exceptions;

use App\Enums\Responses\ResponseTypesEnum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class FileDuplicationException extends Exception
{
    use ExceptionTrait;

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response|JsonResponse
    {
        $msg = $this->getMessage() ?: 'فایل مورد نظر در محل ذخیره‌سازی وجود دارد.';
        $status = ResponseCodes::HTTP_UNPROCESSABLE_ENTITY;
        return $this->sendResponse($request, $msg, $status);
    }
}
