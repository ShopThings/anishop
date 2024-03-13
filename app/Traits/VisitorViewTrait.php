<?php

namespace App\Traits;

trait VisitorViewTrait
{
    /**
     * @return int
     */
    public function uniqueIPViews(): int
    {
        return $this->visitLogs()->distinct('ip')->count('ip');
    }

    /**
     * @return int
     */
    public function uniqueUserViews(): int
    {
        return $this->visitLogs()->visitor()->count();
    }
}
