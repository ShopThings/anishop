<?php

namespace App\Support\Traits;

trait FilenameTrait
{
    /**
     * @param string $filename
     * @param bool $detectExtension
     * @return string
     */
    public function getSaveFilename(string $filename, bool $detectExtension = true): string
    {
        if ($detectExtension) {
            $fileInfo = pathinfo($filename);
            $extension = $fileInfo['extension'];

            $filename = rtrim($this->getEscapedFilename($fileInfo['dirname']), '/') .
                '/' .
                ltrim($this->getEscapedFilename($fileInfo['filename']), '/');

            return $filename . '-' . time() . '.' . $extension;
        }

        return $filename . '-' . time();
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getEscapedFilename(string $filename): string
    {
        return $this->convertBackslashToSlash($this->convertInvalidPathCharacters($filename, false, ['\\', '/']));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function convertBackslashToSlash(string $path): string
    {
        return str_replace('\\', '/', $path);
    }

    /**
     * @param string $path
     * @param bool $isLocal
     * @param array $excludes
     * @return string
     */
    protected function convertInvalidPathCharacters(string $path, bool $isLocal = false, array $excludes = []): string
    {
        $escapes = ['?', '$', '<', '>', '&', '{', '}', '*', '\\', '/', ':', ';', ',', "'", '"'];

        if (!$isLocal) $escapes[] = '-';

        $escapes = array_diff($escapes, $excludes);

        return mb_strtolower(
            str_replace(
                $escapes,
                '_', trim($path)
            )
        );
    }
}
