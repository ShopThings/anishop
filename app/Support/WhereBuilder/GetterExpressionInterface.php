<?php

namespace App\Support\WhereBuilder;

interface GetterExpressionInterface
{
    /**
     * @return string
     */
    public function getStatement(): string;

    /**
     * @return array
     */
    public function getBindings(): array;
}
