<?php

namespace App\Support\Model;

use Illuminate\Support\Facades\DB;

trait AliasTrait
{
    /**
     * @var string
     */
    protected string $alias = '';

    /**
     * @param string $alias
     * @return static
     */
    public function alias(string $alias): static
    {
        $this->alias = trim($alias);
        return $this;
    }

    /**
     * @param $statement
     * @return string
     */
    protected function buildAlias($statement): string
    {
        if ('' !== trim($this->alias)) {
            $this->escapeAlias();
        }
        return '' !== trim($this->alias) ? '(' . $statement . ')' . ' AS ' . $this->alias : $statement;
    }

    /**
     * @return void
     */
    protected function escapeAlias(): void
    {
        $this->alias = DB::getQueryGrammar()->wrap($this->alias);
    }
}
