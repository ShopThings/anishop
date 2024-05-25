<?php

namespace App\Support\QB;

use BadMethodCallException;
use Illuminate\Support\Str;

/**
 * @method betweenAction(array $queryItem, string $columnName, $firstValue, $secondValue, string $condition, bool $reverseOperation);
 * @method comparisonAction(array $queryItem, string $columnName, string $operationStatement, $value, string $condition);
 * @method hasReplacementAction(array $queryItem, string $columnName, $value, string $replacement, string $condition, bool $reverseOperation);
 * @method isMultipleAction(array $queryItem, string $columnName, array $values, string $condition, bool $reverseOperation);
 * @method nullableAction(array $queryItem, string $columnName, string $condition, bool $reverseOperation);
 */
class QueryItemActions
{
    /**
     * @var array
     */
    private array $actions = [];

    public function __construct(array $actions)
    {
        foreach ($actions as $action) {
            if ($action instanceof QueryItemActionInterface) {
                $this->actions[$action->name] = $action;
            }
        }
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        /*
        |
        | These are supported names:
        | ['between', 'comparison', 'has_replacement', 'is_multiple', 'nullable']
        |
        | And passed $name variable should contain one of them plus 'Action'
        | like: 'betweenAction'
        |
        */
        if (Str::contains('Action', $method)) {

            $method = Str::snake($method);

            if (isset($this->actions[$method])) {
                $this->actions[$method]->action(...$arguments);
            }

            return;

        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }
}
