@extends('layouts.base')

@section('content')
  <h1>Tasks</h1>
  
  <table class="table">
    <thead class="thead-inverse">
      <tr>
        <th>Task ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Parent Task</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      @foreach ($tasks as $task)
        <tr>
          <td>{{ $task->id }}</td>
          <td>{{ $task->title }}</td>
          <td>{{ $task->status }}</td>
          <td>{{ $task->parent_id }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
