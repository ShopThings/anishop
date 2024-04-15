<?php

namespace App\Support\Helper;

use App\Enums\QB\TypesEnum;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QBHelper
{
    /**
     * @var array|array[]
     */
    public static array $operators = [
        'equal' => [
            'statement' => '=',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value,
                TypesEnum::BOOLEAN->value
            ],
        ],
        'notEqual' => [
            'statement' => '<>',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value,
                TypesEnum::BOOLEAN->value
            ],
        ],
        'in' => [
            'statement' => 'IN',
            'multiple' => true,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'notIn' => [
            'statement' => 'NOT IN',
            'multiple' => true,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'less' => [
            'statement' => '<',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'lessOrEqual' => [
            'statement' => '<=',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'greater' => [
            'statement' => '>',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'greaterOrEqual' => [
            'statement' => '>=',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'between' => [
            'statement' => 'BETWEEN',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'notBetween' => [
            'statement' => 'NOT BETWEEN',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value
            ],
        ],
        'beginsWith' => [
            'statement' => '=',
            'replacement' => '{value}%',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'notBeginsWith' => [
            'statement' => '<>',
            'replacement' => '{value}%',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'contains' => [
            'statement' => '=',
            'replacement' => '%{value}%',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'notContains' => [
            'statement' => '<>',
            'replacement' => '%{value}%',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'endsWith' => [
            'statement' => '=',
            'replacement' => '%{value}',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'notEndsWith' => [
            'statement' => '<>',
            'replacement' => '%{value}',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'isEmpty' => [
            'statement' => '=',
            'replacement' => '{value}',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'isNotEmpty' => [
            'statement' => '<>',
            'replacement' => '{value}',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value
            ],
        ],
        'isNull' => [
            'statement' => 'IS NULL',
            'multiple' => false,
            'opposite' => false,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value,
                TypesEnum::BOOLEAN->value
            ],
        ],
        'isNotNull' => [
            'statement' => 'NOT IS NULL',
            'multiple' => false,
            'opposite' => true,
            'applyTo' => [
                TypesEnum::STRING->value,
                TypesEnum::NUMBER->value,
                TypesEnum::DATE_OR_TIME_OR_BOTH->value,
                TypesEnum::BOOLEAN->value
            ],
        ],
    ];

    /**
     * @var array|string[]
     */
    public static array $hasTwoValues = [
        'between',
        'notBetween',
    ];

    /**
     * @var array|string[]
     */
    public static array $hasReplacements = [
        'beginsWith',
        'notBeginsWith',
        'contains',
        'notContains',
        'endsWith',
        'notEndsWith',
        'isEmpty',
        'isNotEmpty',
    ];

    /**
     * @var array|string[]
     */
    public static array $emptyReplacements = [
        'isEmpty',
        'isNotEmpty',
    ];

    /**
     * Refine provided query to the following structure:
     * <code>
     *     [
     *       // if it is a single rule
     *       [
     *         'column' => string,
     *         'type' => string, // TypesEnum
     *         'operator' => array,
     *         'condition' => string, // 'and' or 'or'
     *         'value' => mixed, // [string, int, array]
     *         'value2' => mixed, // [mostly int] (optional)
     *       ],
     *       OR if it is a nested rule
     *       [
     *         'condition' => string, // 'and' or 'or'
     *         'children' => array, // array of single rule or nested rule
     *       ],
     *       ...,
     *     ]
     * </code>
     *
     * More detailed structure about given data to this method is:
     * <code>
     *     [
     *       // if it is a single rule
     *       'rule': [
     *         'column': [
     *           'value': string,
     *           'name': string,
     *           'type': string,
     *           'input': [
     *             'text': string,
     *             'placeholder': string, // optional
     *             'type': string
     *           ]
     *         ],
     *         'operator': [
     *           'value': string,
     *           'name': string,
     *           'multiple': boolean,
     *           'statement': string,
     *           'replacement': string,
     *         ],
     *         'condition': string, // 'and' or 'ir'
     *         'value': mixed, // [string, int, array]
     *         'value2' => mixed, // [mostly int] (optional)
     *       ],
     *       OR if it is a nested rule
     *       [
     *         'condition' => string, // 'and' or 'or'
     *         'children' => array, // array of single rule or nested rule
     *       ],
     *       ...,
     *     ]
     * </code>
     *
     * @param array $query
     * @param array $allowedColumns
     * @return array
     */
    public static function refineQuery(array $query, array $allowedColumns): array
    {
        $refinedQuery = [];
        $filledQuery = self::removeEmptyRules($query);

        if (!count($filledQuery)) return [];

        foreach ($filledQuery as $item) {
            if (isset($item['children'])) {
                $refinedQuery[] = [
                    'condition' => $item['condition'],
                    'children' => self::refineQuery($item['children'], $allowedColumns),
                ];
            } elseif (
                in_array($item['rule']['column']['value'], $allowedColumns) &&
                isset(self::$operators[$item['rule']['operator']['value']])
            ) {
                $operator = self::$operators[$item['rule']['operator']['value']];
                $operator['value'] = $item['rule']['operator']['value'];

                $refinedQuery[] = [
                    'column' => $item['rule']['column']['value'],
                    'type' => $item['rule']['column']['type'],
                    'operator' => $operator,
                    'condition' => $item['rule']['condition'],
                    'value' => $item['rule']['value'],
                    'value2' => $item['rule']['value2'] ?? null,
                ];
            }
        }

        return $refinedQuery;
    }

    /**
     * @param array $query
     * @return array
     */
    private static function removeEmptyRules(array $query): array
    {
        $refinedQuery = [];

        if (!is_array($query)) return $query;

        foreach ($query as &$item) {
            if (
                isset(
                    $item['rule'],
                    $item['rule']['column'],
                    $item['rule']['column']['value'],
                    $item['rule']['column']['input'],
                    $item['rule']['column']['input']['type'],
                    $item['rule']['operator']
                ) &&
                isset($item['rule']['value']) &&
                (
                    !in_array($item['rule']['operator']['value'], self::$hasTwoValues) ||
                    (
                        in_array($item['rule']['operator']['value'], self::$hasTwoValues) &&
                        isset($item['rule']['value2'])
                    )
                )
            ) {
                if (!in_array(mb_strtolower($item['rule']['condition']), ['and', 'or'])) {
                    $item['rule']['condition'] = 'and';
                }

                $refinedQuery[] = $item;
            } elseif (
                isset($item['children']) &&
                count($item['children'])
            ) {
                $tmpRefined = self::removeEmptyRules($item['children']);
                if (!empty($tmpRefined)) {
                    $refinedQuery[] = [
                        'condition' => $item['condition'],
                        'children' => $tmpRefined,
                    ];
                }
            }
        }

        return $refinedQuery;
    }

    /**
     * "$item" should have the following structure:
     * <code>
     *     [
     *      column => string,
     *      type => string,
     *      operator => array
     *                  [
     *                    value => string,
     *                    statement => string,
     *                    replacement => string, // {value}
     *                    multiple => boolean,
     *                    applyTo => array, // array of QB types
     *                  ],
     *      condition => string,
     *      value => mixed, (optional in some cases)
     *      value2 => mixed, // (optional)
     *    ],
     *    ...,
     * </code>
     *
     * @param WhereBuilderInterface $where
     * @param array $item
     * @return WhereBuilderInterface
     */
    public static function addToWhereClause(WhereBuilderInterface $where, array $item): WhereBuilderInterface
    {
        // check if item's type is acceptable to apply operator
        if (!isset($item['type']) || !in_array($item['type'], $item['operator']['applyTo'])) {
            return $where;
        }

        // try parse all item value according to its type
        if (isset($item['value'])) {
            $item['value'] = self::tryParseQueryValue($item['value'], $item['type']);
        }
        if (isset($item['value2'])) {
            $item['value2'] = self::tryParseQueryValue($item['value2'], $item['type']);
        }

        // multiple means 'in' or 'or not' statement
        if ($item['operator']['multiple']) {
            if (is_array($item['value']) || is_string($item['value'])) {
                $values = Arr::wrap(Arr::flatten($item['value']));

                $where->whereIn(
                    $item['column'],
                    $values,
                    $item['condition'],
                    $item['operator']['opposite']
                );
            }
        } // has replacements will replace value to specified operator pattern
        elseif (in_array($item['operator']['value'], QBHelper::$hasReplacements)) {
            $v = in_array($item['operator']['value'], QBHelper::$emptyReplacements)
                ? ''
                : $item['value'];

            $where->whereLike(
                $item['column'],
                $v,
                $item['operator']['replacement'],
                $item['condition'],
                $item['operator']['opposite']
            );
        } // otherwise we'll check other operator values
        else {
            switch ($item['operator']['value']) {
                case 'equal':
                case 'notEqual':
                case 'less':
                case 'lessOrEqual':
                case 'greater':
                case 'greaterOrEqual':
                    if (is_scalar($item['value'])) {
                        $where->where(
                            $item['column'],
                            $item['operator']['statement'],
                            $item['value'],
                            $item['condition']
                        );
                    }
                    break;

                case 'between':
                case 'notBetween':
                    if (
                        isset($item['value'], $item['value2']) &&
                        is_scalar($item['value']) &&
                        is_scalar($item['value2'])
                    ) {
                        $where->whereBetween(
                            $item['column'],
                            $item['value'],
                            $item['value2'],
                            $item['condition'],
                            $item['operator']['opposite']
                        );
                    }
                    break;

                case 'isNull':
                case 'isNotNull':
                    $where->whereNull(
                        $item['column'],
                        $item['condition'],
                        $item['operator']['opposite']
                    );
                    break;
            }
        }

        return $where;
    }

    /**
     * @param $value
     * @param string $type
     * @return mixed
     */
    private static function tryParseQueryValue($value, string $type): mixed
    {
        if (is_null($value)) return $value;

        switch ($type) {
            case TypesEnum::NUMBER->value:
                return intval($value);
            case TypesEnum::STRING->value:
                return Str::of($value)->toString();
            case TypesEnum::BOOLEAN->value:
                return to_boolean($value);
            case TypesEnum::DATE_OR_TIME_OR_BOTH->value:
                try {
                    return Carbon::parse($value);
                } catch (InvalidFormatException) {
                    return null;
                }
        }

        return $value;
    }
}
