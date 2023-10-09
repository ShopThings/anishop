<?php

namespace App\Traits;

trait ControllerPaginateTrait
{
    /**
     * @param $request
     * @return array
     */
    protected function getPaginateParameters($request): array
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $page = floor($offset / $limit) + 1;
        $order = [$request->input('column', 'id') => $request->input('sort', 'desc')];
        $text = $request->input('text', '');

        return compact('limit', 'page', 'order', 'text');
    }
}
