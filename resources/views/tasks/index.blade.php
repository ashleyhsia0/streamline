@extends('layouts.base')

@section('stylesheets')
@endsection

@section('content')
    <example></example>

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
                <td>
                    @if ($task->status == 0)
                        IN PROGRESS
                    @elseif ($task->status == 1)
                        DONE
                    @else
                        COMPLETED
                    @endif
                </td>
                <td>{{ $task->parent_id }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
@endsection
