<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AlreadyLoggedInException extends Exception
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
        $msg = $this->getMessage() ?: 'شما در حال حاضر به پنل خود دسترسی دارید!';
        $status = ResponseCodes::HTTP_NOT_ACCEPTABLE;
        return $this->sendResponse($request, $msg, $status);
    }
}
