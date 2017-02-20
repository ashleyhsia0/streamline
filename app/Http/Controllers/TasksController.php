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
        // TODO: Put the validator into a helper function, since used in @update

        $input_array = ['title' => $request->input('title')];

        // Check if parent task ID is null or not
        // If null, let database take care of it (defaults to 0)
        if ($request->input('parentId') !== null) {
            $input_array['parent_id'] = $request->input('parentId');
        }

        $task = Task::create($input_array);

        // Re-query to obtain record that has all attributes
        return Task::find($task->id);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!isset($task)) {
            return response()->json(['message' => 'Task ID does not exist'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'parentId' => 'nullable|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input. Please try again.'], 400);
        }
        // TODO: Put the validator into a helper function, since used in @store

        $task->title = $request->input('title');

        if ($request->input('parentId') !== null) {
            $newParent = Task::find($request->input('parentId'));

            if ($newParent->id === $task->id) {
                return response()->json(['message' => 'The parent task cannot be itself. Please try again.'], 400);
            }

            if ($newParent->isDescendantOf($task->id)) {
                return response()->json(['message' => 'The parent task cannot be one of its dependencies. Please try again.'], 400);
            }

            $task->parent_id = $request->input('parentId');
            $task->save();

            if (!$newParent->isComplete()) {
                $newParent->status = 1;
                $newParent->save();
            }
        } else {
            $task->parent_id = (int)0;
            $task->save();
        }

        return $task;
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

        if ($status === 0) {
            $task->mark();
        } else {
            $task->unmark();
            // TODO: Return a response to user that says status of parent task cannot be modified.
        }

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
