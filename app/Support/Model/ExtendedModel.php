<?php

namespace App\Support\Model;

use App\Traits\FilterableByDatesTrait;
use App\Traits\ModelScopeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Builder whereLike(string|array $columns, string $search, $replacement = '%{value}%', string $operator = 'and')
 * @method Builder orWhereLike(string|array $columns, string $search, $replacement = '%{value}%')
 * @method Builder whereNotLike(string|array $columns, string $search, $replacement = '%{value}%', string $operator = 'and')
 * @method Builder orWhereNotLike(string|array $columns, string $search, $replacement = '%{value}%')
 * @method Builder whereRegex(string|array $columns, string $search, string $operator = 'and')
 * @method Builder orWhereRegex(string|array $columns, string $search)
 */
abstract class ExtendedModel extends Model
{
    use ModelScopeTrait,
        ModifierBootTrait,
        FilterableByDatesTrait;
}
