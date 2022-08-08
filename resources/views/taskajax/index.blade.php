@extends('layouts.app')
@section('content')
    <a href="javascript:void(0)" id="new-task" class="btn btn-primary">Add Task</a>

    <div class="container">
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
    </div>


    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Users</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div id="task_paginate"></div>

    <!-- Modal -->
    <div class="modal fade" id="ajax-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="taskShowModal"></h4>
                </div>

                <form class="cmxform" id="form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">name</label>
                            <input type="text" name="name" class="form-control" id="inputName">
                            <span id="name_err_msg" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="inputDesc" class="form-label">description</label>
                            <textarea class="form-control" id="inputDesc" rows="3" name="description"></textarea>
                            <span id="description_err_msg" class="text-danger"></span>
                        </div>

                        <div class="mb-3 optionUsers">
                            <span id="user_id_err_msg" class="text-danger"></span>
                            <select name="user_id" class="form-control select"></select>
                        </div>

                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn_close">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_save">Save</button>
                    </div>

                </form>


            </div>
        </div>
    </div>
@endsection



@section('script')
    <script>
        //   http://127.0.0.1:8000/tasks/get/tasks?page=3&keyword=mohammed
        function search(keyword = null) {
            var page = 1;
            // console.log(keyword);
            // var keyword = (keyword !== '') ? '' : keyword;
            // console.log(keyword);

            $.get(`tasks/get/tasks?page=${page}&keyword=${keyword}`, function(response) {
                $('table tbody').html('')
                appendRows(response.data);
                //change link Paginate
                appendPaginate(response.links, response.current_page, keyword);
            });

        }

        //serch by name
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            // console.log(value);
            search(value.trim());
        });

        function getUsers() {
            $.get('tasks/get/users', function(data) {
                appendUsers(data);
            });
        }
        getUsers();

        function appendUsers(data) {
            data.forEach((user) => {
                $('.optionUsers select').append(`<option value="${user.id}">${user.name}</option>`)
            })
        }


        function getTasks(page = 1, keyword = null) {
            var keyword = (keyword !== '') ? '' : keyword;
            $.get(`tasks/get/tasks?page=${page}&keyword=${keyword}`, function(response) {
                $('table tbody').html('');
                appendRows(response.data);
                appendPaginate(response.links, response.current_page, keyword);
            });

        }
        getTasks();

        function appendRows(data) {
            data.forEach((task) => {
                $('table tbody').append(`<tr>
                                            <td>${task.id}</td>
                                            <td>${task.name}</td>
                                            <td>${task.description}</td>
                                            <td>${task.user_id}</td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-task" data-id="${task.id}" title="Delete todo">Delete</a>
                                                <a href="javascript:void(0)" class="btn btn-success btn-sm edit-task" data-id="${task.id}" title="Edit todo">Edit</a>
                                            </td>
                                        </tr>`);
            });


            /* When click delete task */
            deleteFn();

            /* When click edit task */
            editFn();

        }


        function appendPaginate(links, current, keyword = null) {
            let allLinks = ``;
            links.forEach((link) => {
                var url = link.url + `&keyword=${keyword}`;

                allLinks += `<li class="page-item${link.active ? ' active' : ''}">
                                <a class="page-link" ${link.url == null || link.label == current ? 'disabled' : url} href="${link.url == null || link.label == current ? '#' : url}">${link.label}</a>
                            </li>`
            });

            $('#task_paginate').html(`<nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">${allLinks}</ul>
                                    </nav>`);

            $('#task_paginate .page-link:not([disabled])').click(function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                var data = url.split('?')[1].split('&');
                getTasks(data[0].split('=')[1], data[1].split('=')[1]);
            })

        }
    </script>







    <script>
        // /* When click btn close */
        $('.btn_close').click(function() {
            $('#form')[0].reset();
            $('#ajax-modal').modal('hide');
            $('#name_err_msg,#description_err_msg,#user_id_err_msg').text('');
            $("#inputId").remove();
        });
    </script>


    <script>
        // /* When click new task */
        $('#new-task').click(function() {
            $('#taskShowModal').html("Create Task");
            $('#ajax-modal').modal('show');
        });

        //store route
        let store_route = () => {
            $('#name_err_msg,#description_err_msg,#user_id_err_msg').text('');
            let formData = new FormData($('#form')[0]);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: "{{ route('tasks.store') }}",
                data: formData,
                type: 'post',
                processData: false,
                contentType: false,
                cache: false,

                success: function(response) {
                    console.log('Task Send AJax ');
                    $('#form')[0].reset();
                    $('#ajax-modal').modal('hide');
                    getTasks();
                    // toastr.success(response.message);
                },
                error: function(reject) {

                    let res = $.parseJSON(reject.responseText);
                    $.each(res.errors, function(key, value) {
                        $("#" + key + "_err_msg").text(value[0]);
                    })
                }
            });





        };

        //update route
        let update_route = () => {
            console.log("update_route");

            let formData = new FormData($('#form')[0]);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');


            $.ajax({
                type: "post",
                url: "/tasks/" + $('#inputId').val(),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,

                success: function(response) {
                    console.log('Task Send AJax updat ');
                    $('#form')[0].reset();
                    $('#ajax-modal').modal('hide');
                    // toastr.success(response.message + id);
                    getTasks();
                },
                error: function(reject) {

                    let res = $.parseJSON(reject.responseText);
                    $.each(res.errors, function(key, value) {
                        $("#" + key + "_err_msg").text(value[0]);
                    })
                }
            });


        };


        $('#btn_save').click(function() {
            var inputId = $('#inputId').val();

            if (typeof inputId !== 'undefined') {
                //update_route
                update_route();
            } else {
                //store_route
                store_route();
            }
        });
    </script>



    <script>
        let deleteFn = () => {
            /* When click delete task */
            $('.delete-task').click(function() {

                var id = $(this).data('id');
                console.log(id);

                $.ajax({
                    url: "tasks/" + id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // toastr.success(response.message);

                        console.log('delete-task');
                        getTasks();

                    }
                });

            })
        }
        deleteFn();
    </script>

    <script>
        let editFn = () => {
            $('.edit-task').click(function() {
                console.log('edit-task');
                $('.modal-body').append(`<input type="hidden" id="inputId">`);
                $('#taskShowModal').html("Edit Task");
                $('#ajax-modal').modal('show');

                let id = $(this).attr("data-id")

                $.get('tasks/' + id + '/edit', function(data) {
                    $('#taskShowModal').html("Todo Edit");
                    $("#btn_save").attr('id', 'btn_edit');

                    $('#inputId').val(data.id);
                    $('#inputName').val(data.name);
                    $('#inputDesc').val(data.description);
                    $("div.optionStatus select").val(data.user_id);
                });

            });
        };
        editFn();
    </script>
@endsection
