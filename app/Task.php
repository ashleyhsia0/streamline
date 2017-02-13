<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = ['title', 'parent_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * Enables/disables created_at and updated_at columns to exist on the table.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the children for the task.
     */
    public function children()
    {
        return $this->hasMany('App\Task', 'parent_id');
    }

    /**
     * Get all descendants for the task.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the parent for the task.
     */
    public function parent()
    {
        return $this->belongsTo('App\Task', 'parent_id');
    }

    /**
     * Get all ascendants for the task.
     */
    public function ascendants()
    {
        return $this->parent()->with('ascendants');
    }

    /**
     * Check if a task is considered COMPLETE.
     *
     * A task is considered COMPLETE if it has no dependencies or if all of its
     * dependencies have a COMPLETED status.
     */
    public function isComplete()
    {
        $dependencies = $this->children()->get();

        foreach ($dependencies as $dependency) {
            if ($dependency->status !== 2) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mark the status as COMPLETE and traverse upwards, propagating the status change.
     */
    public function mark()
    {
        // Base Case #1: If the task is incomplete after checking its dependencies
        if (!$this->isComplete()) {
            return;
        }

        $this->status = 2;
        $this->save();

        // Base Case #2: If we have reached the root parent (no more to check)
        $parent = $this->parent()->first();
        if (!$parent) {
            return;
        }

        $parent->mark();
    }
}
