@extends('layouts.app')


@section('content')

@if (session()->has('success'))
<div class="alert alert-success">
    <p>
        {{ session('success') }}
    </p>
</div>
@endif

<form action="{{ $action }}" method="post">
    @csrf
    @if($method == 'PUT')
        @method('PUT')
    @endif
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $task->name }}">
        @error('name')
        <div class="text-danger">
            <strong>{{ $errors->first('name') }}</strong>
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" class="form-control" name="description" value="{{ $task->description }}">
        @error('description')
        <div class="text-danger">
            <strong>{{ $errors->first('description') }}</strong>
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Users</label>
        <select class="form-control" name="user_id">
            <option disabled selected> Choose user </option>
            @foreach ($users as $userKey => $user)
                <option value="{{ $userKey }}" {{ $userKey == $task->user_id ? 'selected' : '' ;}}> {{ $user }}</option>
            @endforeach
        </select>
        @error('user_id')
        <div class="text-danger">
            <strong>{{ $errors->first('user_id') }}</strong>
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

@endsection
