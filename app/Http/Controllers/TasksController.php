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
            'parent_id' => 'nullable|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input. Please try again.'], 400);
        }

        $input_array = ['title' => $request->input('title')];

        // Check if parent task ID is null or not
        // If null, let database take care of it (defaults to 0)
        if ($request->input('parent_id') !== null) {
            $input_array['parent_id'] = $request->input('parent_id');
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

        if(!isset($task)) {
            return response()->json(['message' => 'Task ID does not exist'], 404);
        }

        $status = $task->status;

        if ($status === 0) {
            $task->status = 1;

            // Task is considered complete if it has no dependencies
            // or if all of its dependencies have a COMPLETED status
            $isTaskComplete = false;
            $dependencies = $task->children()->get();

            if ($dependencies->isEmpty()) {
                $isTaskComplete = true;
            }

            foreach ($dependencies as $dependency) {
                $isTaskComplete = ($dependency->status === 2) ? true : false;
            }

            if ($isTaskComplete) {
                $task->status = 2;
            }

        } elseif ($status === 1) {
            $task->status = 0;
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
