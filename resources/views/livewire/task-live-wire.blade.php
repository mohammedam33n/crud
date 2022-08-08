<div>
<table class="table">
    <button wire:click="create()"  class="btn btn-primary"> create </button>
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
                    <a wire:click="edit('{{ $task->id }}')" href="#" class="btn btn-info">edit</a>
                    <button wire:click="destroy('{{ $task->id }}')" class="btn btn-danger">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>

    <div class="modal" @if($modelShow)style="display: block;background: #00000036;" @endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                    <button type="button" wire:click="close" class="btn-close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" wire:model='task.name'>
                            @error('name')
                            <div class="text-danger">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" wire:model='task.description'>
                            @error('description')
                            <div class="text-danger">
                                <strong>{{ $errors->first('description') }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Users</label>
                            <select class="form-control" wire:model='task.user_id'>
                                    <option  selected> Choose user </option>
                                @foreach ($users as $userKey => $user)
                                    <option value="{{ $userKey }}" {{ $userKey == $task->user_id ? 'selected' : ''}} > {{ $user }}</option>
                                @endforeach
                            </select>
                            {{-- <p>@json($task)</p> --}}
                            @error('user_id')
                            <div class="text-danger">
                                <strong>{{ $errors->first('user_id') }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="close" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button  class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</table>
</div>

