<?php

namespace App\Enums\Responses;

enum ResponseTypesEnum: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
}
