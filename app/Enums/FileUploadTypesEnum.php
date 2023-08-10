<?php

namespace App\Enums;

enum FileUploadTypesEnum
{
    case IMAGE;

    case FONT;

    case AUDIO;

    case VIDEO;

    case DOCUMENT;

    case ARCHIVE;

    /**
     * @param string $type
     * @return bool
     */
    public static function isImage(string $type): bool
    {
        return in_array($type, ['jpg', 'pjpg', 'jpe', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'svgz', 'tiff', 'tif', 'webp', 'ico', 'avif']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isFont(string $type): bool
    {
        return in_array($type, ['ttc', 'otf', 'ttf', 'woff', 'woff2']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isAudio(string $type): bool
    {
        return in_array($type, ['mp3', 'm4a', 'ogg', 'mpga', 'wav']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isVideo(string $type): bool
    {
        return in_array($type, ['smv', 'movie', 'mov', 'wvx', 'wmx', 'wm', 'mp4', 'mp4', 'mp4v', 'mpg4', 'mpeg', 'mpg', 'mpe', 'wmv', 'avi', 'ogv', '3gp', '3g2']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isDocument(string $type): bool
    {
        return in_array($type, ['css', 'csv', 'html', 'htm', 'conf', 'log', 'txt', 'text', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isArchive(string $type): bool
    {
        return in_array($type, ['gzip', 'rar', 'tar', 'zip', '7z']);
    }
}
