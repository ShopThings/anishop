<?php

namespace App\Support\Model;

use App\Contracts\BuildExpressionInterface;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class CaseWhen implements BuildExpressionInterface
{
    use AliasTrait;

    /**
     * @var Builder
     */
    private Builder $query;

    /**
     * @var array
     */
    private array $builder = [];

    /**
     * @var string
     */
    private string $statement = '';

    /**
     * @var bool
     */
    private bool $isComplete = false;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;

        $this->builder['when'] = [];
        $this->builder['else'] = '';

        $this->statement .= 'CASE ';
    }

    /**
     * @param callable|string $condition
     * @param callable|string $do
     * @return static
     */
    public function when(callable|string $condition, callable|string $do): static
    {
        if ($this->isComplete) return $this;

        $this->isCallableStringOrString($condition, function ($result) use ($do) {
            $this->isCallableStringOrString($do, function ($result2) use ($result) {
                $this->builder['when'][] = "WHEN $result THEN $result2";
            });
        });

        return $this;
    }

    /**
     * @param callable|string $do
     * @return static
     */
    public function else(callable|string $do): static
    {
        $this->isComplete = true;

        $this->isCallableStringOrString($do, function ($result) {
            $this->builder['else'] = "ELSE $result";
        });

        return $this;
    }

    /**
     * @return CaseWhen
     */
    public function elseCase(): static
    {
        $this->isComplete = true;
        return new static($this->query);
    }

    /**
     * It must be called to build your case thing
     *
     * @return Builder
     */
    public function build(): Builder
    {
        foreach ($this->builder['when'] as $when)
            $this->statement .= $when . "\r\n";

        if ('' != $this->builder['else'])
            $this->statement .= $this->builder['else'] . "\r\n";

        $this->statement .= $this->buildAlias($this->statement);

        $this->query->raw($this->statement);

        return $this->query;
    }

    /**
     * @param $callable
     * @param Closure $do
     */
    private function isCallableStringOrString($callable, Closure $do)
    {
        if (is_callable($callable)) {
            $result = call_user_func_array($callable, [$this->query]);
            if (is_string($result))
                $do($result);

        } elseif (is_string($callable)) {
            $do($callable);
        }
    }
}
