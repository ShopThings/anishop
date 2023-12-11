<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class PleaseWaitException extends Exception
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
        $msg = $this->getMessage() ?: 'لصفا کمی صبر کنید و سپس تلاش نمایید.';
        $status = ResponseCodes::HTTP_TOO_MANY_REQUESTS;
        return $this->sendResponse($request, $msg, $status);
    }
}
