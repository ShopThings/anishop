<?php

namespace App\Support\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeletesTrait
{
    use SoftDeletes;

    protected $useDeletedBy = true;

    /**
     * @see https://github.com/laravel/framework/issues/4990
     * @see https://gist.github.com/casperwilkes/10a2bee6b9e94dd4a663d7cb0e4ae3ff
     * @inheritDoc
     */
    protected function runSoftDelete()
    {
        if (!$this->useDeletedBy) {
            parent::runSoftDelete();
        } else {
            $query = $this->setKeysForSaveQuery($this->newModelQuery());

            $time = $this->freshTimestamp();

            $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];

            $this->{$this->getDeletedAtColumn()} = $time;

            if ($this->usesTimestamps() && !is_null($this->getUpdatedAtColumn())) {
                $this->{$this->getUpdatedAtColumn()} = $time;

                $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
            }

            $columns = array_merge($query->getModel()->getDirty(), $columns);

            // here we add deleted by column's value
            $columns['deleted_by'] = Auth::user()?->id;

            $query->update($columns);

            $this->syncOriginalAttributes(array_keys($columns));

            $this->fireModelEvent('trashed', false);
        }
    }
}
