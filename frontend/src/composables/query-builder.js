import {isRef} from "vue";
import isArray from "lodash.isarray";

export const TYPES = {
    STRING: 'string',
    NUMBER: 'number',
    DATE: 'datetime',
    TIME: 'datetime',
    DATETIME: 'datetime',
    BOOLEAN: 'boolean',
}

export const INPUTS = {
    TEXT: 'text',
    NUMBER: 'number',
    TEXTAREA: 'textarea',
    SWITCH: 'switch',
    SELECT: 'select',
    MULTISELECT: 'multiselect',
    DATETIME: 'datetime',
    DATE: 'date',
    TIME: 'time',
    COLOR: 'color',
    RANGE: 'range',
}

export const operatorsMap = {
    equal: {
        statement: '=',
        multiple: false,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME, TYPES.BOOLEAN],
    },
    notEqual: {
        statement: '<>',
        multiple: false,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME, TYPES.BOOLEAN],
    },
    in: {
        statement: 'IN',
        multiple: true,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME],
    },
    notIn: {
        statement: 'NOT IN',
        multiple: true,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME],
    },
    less: {
        statement: '<',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    lessOrEqual: {
        statement: '<=',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    greater: {
        statement: '>',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    greaterOrEqual: {
        statement: '>=',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    between: {
        statement: 'BETWEEN',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    notBetween: {
        statement: 'NOT BETWEEN',
        multiple: false,
        applyTo: [TYPES.NUMBER, TYPES.DATETIME],
    },
    beginsWith: {
        statement: '=',
        replacement: '%{value}',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    notBeginsWith: {
        statement: '<>',
        replacement: '%{value}',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    contains: {
        statement: '=',
        replacement: '%{value}%',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    notContains: {
        statement: '<>',
        replacement: '%{value}%',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    endsWith: {
        statement: '=',
        replacement: '{value}%',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    notEndsWith: {
        statement: '<>',
        replacement: '{value}%',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    isEmpty: {
        statement: '=',
        replacement: '{value}',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    isNotEmpty: {
        statement: '<>',
        replacement: '{value}',
        multiple: false,
        applyTo: [TYPES.STRING],
    },
    isNull: {
        statement: 'IS NULL',
        multiple: false,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME, TYPES.BOOLEAN],
    },
    isNotNull: {
        statement: 'NOT IS NULL',
        multiple: false,
        applyTo: [TYPES.STRING, TYPES.NUMBER, TYPES.DATETIME, TYPES.BOOLEAN],
    }
}

export const hasTwoValues = [
    'between',
    'notBetween',
]

export const hasMultipleValues = [
    'in',
    'notIn',
]

export const noNeededInputOperators = [
    'isEmpty',
    'isNotEmpty',
    'isNull',
    'isNotNull',
]

const hasReplacements = [
    'beginsWith',
    'notBeginsWith',
    'contains',
    'notContains',
    'endsWith',
    'notEndsWith',
    'isEmpty',
    'isNotEmpty',
]

const emptyReplacements = [
    'isEmpty',
    'isNotEmpty',
]

export function removeEmptyRules(query) {
    let q = isRef(query) ? query.value : query
    let newQuery = []
    let tmpQ

    if (!isArray(q)) return query

    for (let i = 0; i < q.length; i++) {
        if (
            q[i].rule &&
            q[i].rule.column &&
            q[i].rule.column.value &&
            q[i].rule.column.input &&
            q[i].rule.column.input.type &&
            q[i].rule.operator &&
            (
                q[i].rule.value !== null &&
                typeof q[i].rule.value !== 'undefined'
            ) &&
            (
                hasTwoValues.indexOf(q[i].rule.operator.value) === -1 ||
                (
                    hasTwoValues.indexOf(q[i].rule.operator.value) !== -1 &&
                    q[i].rule.value2 !== null &&
                    typeof q[i].rule.value2 !== 'undefined'
                )
            )
        ) {
            newQuery.push(q[i])
        } else if (
            (
                q[i].children &&
                q[i].children.length
            )
        ) {
            tmpQ = removeEmptyRules(q[i].children)

            if (tmpQ && tmpQ.length)
                newQuery.push({
                    condition: q[i].condition,
                    children: tmpQ,
                })
        }
    }

    return newQuery
}

export function toSql(query, useNamedParameter, level) {
    const useQuestionMark = !(!!useNamedParameter)
    let sql = ''
    let params
    let tmpSql, paramStr = 'param_'
    let q = isRef(query) ? query.value : query

    level = isNaN(parseInt(level, 10)) ? 1 : parseInt(level, 10)
    q = removeEmptyRules(q)
    params = useQuestionMark ? [] : {}

    if (!isArray(q)) return {sql, params}

    for (let i = 0; i < q.length; i++) {
        if (q[i].children) {
            tmpSql = toSql(q[i].children, useNamedParameter, level + 1)
            if (tmpSql && tmpSql.sql) {
                sql += ' (' + tmpSql.sql + ')'
                if (useQuestionMark)
                    params = [...params, ...tmpSql.params]
                else
                    params = {...params, ...tmpSql.params}

                if (i < q.length - 1) {
                    sql += ' ' + q[i].condition + ' '
                }
            }
        } else {
            let paramName = paramStr + level + '_' + i
            let paramName2 = paramName + '_2'

            sql += q[i].rule.column.value + ' '
            sql += q[i].rule.operator.statement + ' '
            sql += useQuestionMark ? '?' : ':' + paramName

            if (hasTwoValues.indexOf(q[i].rule.operator.value) !== -1) {
                sql += ' AND ' + useQuestionMark ? '?' : ':' + paramName2

                if (useQuestionMark) {
                    params.push(q[i].rule.value)
                    params.push(q[i].rule.value2)
                } else {
                    params[paramName] = q[i].rule.value
                    params[paramName2] = q[i].rule.value2
                }
            } else {
                if (
                    hasReplacements.indexOf(q[i].rule.operator.value) !== -1 &&
                    q[i].rule.operator.replacement
                ) {
                    if (useQuestionMark)
                        params.push(q[i].rule.operator.replacement.replace('{value}', q[i].rule.value))
                    else
                        params[paramName] = q[i].rule.operator.replacement.replace('{value}', q[i].rule.value)
                } else if (emptyReplacements.indexOf(q[i].rule.operator.value) !== -1) {
                    if (useQuestionMark)
                        params.push('')
                    else
                        params[paramName] = ''
                } else {
                    if (useQuestionMark)
                        params.push(q[i].rule.value)
                    else
                        params[paramName] = q[i].rule.value
                }
            }

            if (i < q.length - 1) {
                sql += ' ' + q[i].rule.condition + ' '
            }
        }
    }

    return {
        sql: sql.trim(),
        params,
    }
}
