<?php

namespace App\Traits;

trait ExcelColumnsGeneratorTrait
{
    /**
     * @param int $length
     * @return array
     */
    protected function generateSemiExcelColumns(int $length): array
    {
        $columns = [];
        foreach (range(1, $length) as $index) {
            $columns[] = $this->getExcelColumnName($index);
        }
        return $columns;
    }

    /**
     * @param int $columnNumber
     * @return string
     */
    private function getExcelColumnName(int $columnNumber): string
    {
        $columnName = '';
        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;
            $columnName = chr(65 + $remainder) . $columnName;
            $columnNumber = ($columnNumber - $remainder - 1) / 26;
        }
        return $columnName;
    }
}
