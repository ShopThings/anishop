<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ControllerPaginateTrait
{
    /**
     * @param Request $request
     * @return array
     */
    protected function getPaginateParameters(Request $request): array
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $page = floor($offset / $limit) + 1;
        $order = [$request->input('column', 'id') => $request->input('sort', 'desc')];
        $text = $request->input('text', null);

        return compact('limit', 'page', 'order', 'text');
    }
}
