<?php

namespace App\Exceptions;

use App\Enums\Responses\ResponseTypesEnum;
use Illuminate\Http\Request;

trait ExceptionTrait
{
    /**
     * @param Request $request
     * @param $msg
     * @param $status
     * @param array|null $extra
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function sendResponse(Request $request, $msg, $status, ?array $extra = null)
    {
        $resArray = [
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => $msg,
        ];

        if (!is_null($extra)) {
            foreach ($extra as $key => $value) {
                $resArray[$key] = $value;
            }
        }

        if ($request->expectsJson()) {
            return response()->json($resArray, $status);
        }

        return response($resArray, $status);
    }
}
