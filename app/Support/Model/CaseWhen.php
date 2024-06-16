<?php

namespace App\Support\Model;

use App\Contracts\BuildExpressionInterface;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class CaseWhen implements BuildExpressionInterface
{
    use AliasTrait;

    /**
     * @var array
     */
    private array $builder = [];

    /**
     * @var bool
     */
    private bool $isComplete = false;

    /**
     * @param Builder|null $query
     */
    public function __construct(private readonly ?Builder $query = null)
    {
        $this->builder['when'] = [];
        $this->builder['else'] = '';
    }

    /**
     * @param callable|string $condition
     * @param callable|string $do
     * @param array $bindings
     * @param string|null $type
     * @return CaseWhen|Builder|array
     */
    public function when(
        callable|string $condition,
        callable|string $do,
        array           $bindings = [],
        ?string         $type = ''
    ): CaseWhen|Builder|array
    {
        if ($this->isComplete) return $this;

        $this->isCallableStringOrString($condition, function ($result) use ($do, $bindings) {
            $this->isCallableStringOrString($do, function ($result2) use ($result, $bindings) {
                $this->builder['when'][] = [
                    'statement' => "WHEN $result THEN $result2",
                    'bindings' => $bindings,
                ];
            });
        });

        return !is_null($type) && trim($type) === '' ? $this : $this->build($type);
    }

    /**
     * @param callable|string $do
     * @param array $bindings
     * @param string|null $type
     * @return CaseWhen|Builder|array
     */
    public function else(
        callable|string $do,
        array           $bindings = [],
        ?string         $type = 'select'
    ): CaseWhen|Builder|array
    {
        if ($this->isComplete || !count($this->builder['when'])) return $this;

        $this->isComplete = true;

        $this->isCallableStringOrString($do, function ($result) use ($bindings) {
            $this->builder['else'] = [
                'statement' => "ELSE $result",
                'bindings' => $bindings,
            ];
        });

        return $this->build($type);
    }

    /**
     * Use this to add nested cases
     *
     * @return CaseWhen
     */
    public function elseCase(): CaseWhen
    {
        if ($this->isComplete) return $this;

        $this->isComplete = true;

        $new = new static($this->query);
        $this->builder['when'][] = $new;

        return $new;
    }

    /**
     * @return array
     */
    public function getStatementAndBindings(): array
    {
        $statement = "CASE \n";
        $bindings = [];

        foreach ($this->builder['when'] as $when) {
            // check nested $when clause
            if ($when instanceof CaseWhen) {
                $statementNBindings = $when->getStatementAndBindings();

                $statement .= '(' . $statementNBindings['statement'] . ") \r\n";
                $bindings[] = $statementNBindings['bindings'];
            } else {
                $statement .= $when['statement'] . " \r\n";
                $bindings[] = $when['bindings'];
            }
        }

        if ('' != $this->builder['else']) {
            $statement .= $this->builder['else']['statement'] . " \r\n";
            $bindings[] = $this->builder['else']['bindings'];
        }

        $statement .= 'END';

        // add alias to statement
        $statement = $this->buildAlias($statement);

        return [
            'statement' => $statement,
            'bindings' => Arr::flatten($bindings),
        ];
    }

    /**
     * Build the final CASE WHEN expression and apply it to the query.
     *
     * NOTE:
     *   It MUST be called to apply it to query
     *
     * @param string|null $type
     * @return Builder|array - In case of returning array, it'll be ['statement' => <string>, 'bindings' => <array>] keys
     */
    public function build(?string $type = 'select'): Builder|array
    {
        $statementNBindings = $this->getStatementAndBindings();

        if (is_null($this->query) || !in_array($type, ['select', 'where'], true)) {
            return $statementNBindings;
        } else {
            if ($type === 'select') {
                $this->query->selectRaw($statementNBindings['statement'], $statementNBindings['bindings']);
            } else {
                $this->query->whereRaw($statementNBindings['statement'], $statementNBindings['bindings']);
            }
        }

        return $this->query;
    }

    /**
     * Call $do parameter based on type of $callable and pass result of $callable to $do
     *
     * @param $callable
     * @param Closure $do
     */
    private function isCallableStringOrString($callable, Closure $do)
    {
        if (is_callable($callable)) {
            $result = call_user_func_array($callable, [$this->query]);
            if (is_string($result)) $do($result);

        } elseif (is_string($callable)) {
            $do($callable);
        }
    }
}
