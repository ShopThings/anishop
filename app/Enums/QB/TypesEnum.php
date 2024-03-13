<?php

namespace App\Enums\QB;

enum TypesEnum: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case DATE_OR_TIME_OR_BOTH = 'datetime';
    case BOOLEAN = 'boolean';
}
