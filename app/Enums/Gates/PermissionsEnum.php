<?php

namespace App\Enums\Gates;

enum PermissionsEnum: string
{
    case READ = 'read';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case PERMANENT_DELETE = 'permanent_delete';
    case PRINT = 'print';
    case EXPORT = 'export';
    case IMPORT = 'import';
    case WATCH = 'watch'; // watch means to follow any interactions happen;
    case PUBLISH = 'publish';
    case VERIFY = 'verify';
    case BAN = 'ban';
    case DOWNLOAD = 'download';
    case UPLOAD = 'upload';
}
