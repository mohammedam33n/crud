@extends('layouts.app')

@section('content')
<table class="table">
    <a href="{{ route('tasks.create') }}" class="btn btn-primary"> create</a>
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Users</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <td>{{ $task->name }}</td>
            <td> {{ $task->description }}</td>
            <td> {{ $task->user->name }}</td>
            <td>
                <a href="{{ route('tasks.edit',$task->id) }}" class="btn btn-info">edit</a>
                <button class="btn btn-danger">Delete</button>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection
