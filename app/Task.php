<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * Enables/disables created_at and updated_at columns to exist on the table.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Scopes a query to only include tasks that are in progress.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scopes a query to only include tasks that are done.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDone($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scopes a query to only include tasks that are complete.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplete($query)
    {
        return $query->where('status', 2);
    }
}
