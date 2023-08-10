<?php

namespace App\Support\Model;

trait AliasTrait
{
    /**
     * @var string
     */
    protected string $alias = '';

    /**
     * @param string $alias
     */
    public function alias(string $alias): void
    {
        $this->alias = trim($alias);
    }

    /**
     * @param $statement
     * @return string
     */
    protected function buildAlias($statement): string
    {
        return '' != $this->alias ? '(' . $statement . ')' . 'AS ' . $this->alias : $statement;
    }
}
