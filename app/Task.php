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

}
