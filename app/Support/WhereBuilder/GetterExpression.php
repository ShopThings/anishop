<?php

namespace App\Support\WhereBuilder;

class GetterExpression implements GetterExpressionInterface
{
    public function __construct(protected string $statement, protected array $bindings)
    {
    }

    /**
     * @inheritDoc
     */
    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * @inheritDoc
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }
}
