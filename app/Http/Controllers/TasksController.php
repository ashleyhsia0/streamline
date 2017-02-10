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
        return Task::all();
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
            return response()->json(['message' => 'Parent Task ID does not exist'], 400);
        }

        $input_array = ['title' => $request->input('title')];

        // Check if parent task ID is null or not
        // If null, let database take care of it (defaults to 0)
        if ($request->input('parent_id') !== null) {
            $input_array['parent_id'] = $request->input('parent_id');
        }

        $task = Task::create($input_array);

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

        if ($status == 0) {
            $task->status = 1;
        } elseif ($status == 1) {
            $task->status = 0;
        } else {
            // TODO: Check dependencies to see if status can be updated to COMPLETED
            // For now it just marks/unmarks as DONE
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
