<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Validator;

class TasksController extends Controller
{
    /**
     * Return a JSON of all tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        $tasksWithDecendants = [];

        foreach ($tasks as $task) {
            $task['descendants'] = $task->descendants()->get();
            array_push($tasksWithDecendants, $task);
        }

        return $tasksWithDecendants;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a new task in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'parentId' => 'nullable|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input. Please try again.'], 400);
        }

        $input_array = ['title' => $request->input('title')];

        // Check if parent task ID is null or not
        // If null, let database take care of it (defaults to 0)
        if ($request->input('parentId') !== null) {
            $input_array['parent_id'] = $request->input('parentId');
        }

        $task = Task::create($input_array);

        // Obtain record to get all attributes
        $task = Task::find($task->id);

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Update the status of a task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id)
    {
        $task = Task::find($id);

        if (!isset($task)) {
            return response()->json(['message' => 'Task ID does not exist'], 404);
        }

        $status = $task->status;
        $dependencies = $task->children()->get();

        if ($status === 0) {
            $task->status = 1;
            $task->mark();

        // Task that has COMPLETE status can be IN PROGRESS if it has no dependencies (it is not a parent task)
        // A parent task must not revert to IN PROGRESS from DONE or COMPLETE.
        } else {
            if ($dependencies->isEmpty()) {
                $task->status = 0;

                $parent = $task->parent()->first();
                if ($parent) {
                    $parent->status = 1;
                    $parent->save();
                }
            }

            // TODO: Return a response to user that says status of parent task cannot be modified.
        }

        $task->save();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
