<?php

namespace App\Enums\QB;

enum InputTypesEnum: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case TEXTAREA = 'textarea';
    case SWITCH = 'switch';
    case SELECT = 'select';
    case MULTISELECT = 'multiselect';
    case DATETIME = 'datetime';
    case DATE = 'date';
    case TIME = 'time';
    case COLOR = 'color';
    case RANGE = 'range';
}
