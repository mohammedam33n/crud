@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    <script>
        $('.edit-task').click(function() {
            console.log('edit-task');


        });




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
                            <a href="javascript:void(0)" class="btn btn-success btn-sm edit-task" id="edit-task" data-id="${task.id}" title="Edit todo">Edit</a>
                            </td>
                        </tr>`)
            })
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

        function getTasks(page = 1, keyword = null) {
            var keyword = (keyword !== '') ? '' : keyword;
            $.get(`tasks/get/tasks?page=${page}&keyword=${keyword}`, function(response) {
                $('table tbody').html('')
            });
            appendRows(response.data);
                appendPaginate(response.links, response.current_page);
        }
        getTasks();

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




        /*
        function getUsers() {
            $.get(`tasks/get/users`, function(response) {
                console.log(response);
                // $('#users').html('')
                // appendRows(response.data);
                // appendPaginate(response.links, response.current_page);
                let users = ``;

                response.forEach((link) => {
                    users += ``
                });

                $('#users').html(`<p></p>`);

            });
        }
        getUsers();
        */
    </script>

    {{-- <script>
            /*
                        function markup(taskValues) {
                            return `<tr id="tr-${taskValues.id}">
                    <th scope="row">1</th>
                    <td>${taskValues.title}</td>
                    <td>${taskValues.desc}</td>
                    <td>${taskValues.status}</td>
                    <td>
                        <a href="javascript:void(0)" class="edit-task" data-id="${taskValues.id}" title="Edit todo"><i class="fas fa-pencil-alt me-3"></i></a>
                        <a href="javascript:void(0)" class="text-danger delete-task" data-id="${taskValues.id}" title="Delete todo"><i class="fas fa-trash-alt me-3"></i></a>
                    </td>
                </tr>`;
                        }
                    */


            function markup(taskValues) {
                return `<tr id="row-${taskValues.id}">
                    <td>{{ $task->name }}</td>
                    <td> {{ $task->description }}</td>
                    <td> {{ $task->user->name }}</td>
                    <td>
                        <a href="javascript:void(0)" class="btn btn-info edit-task" data-id="${taskValues.id}" title="Edit task">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger delete-task" data-id="${taskValues.id}" title="Delete task">Delete</a>
                    </td>
                </tr>`;
            }
    </script> --}}

    <script>
        // /* When click btn close */
        $('.btn_close').click(function() {
            $('#form')[0].reset();
            $('#ajax-modal').modal('hide');
            $('#name_err_msg,#description_err_msg,#user_id_err_msg').text('');
            $("#inputId").remove();
            console.log('btn_close');
            $("#btn_edit").attr('id', 'btn_save');



        });


        // /* When click new task */
        $('#new-task').click(function() {
            console.log('new-task');
            $('#taskShowModal').html("Create Task");
            $('#ajax-modal').modal('show');
        });


        /*
        $('#btn_save').click(function() {
            console.log('btn_save');
            $('#name_err_msg,#description_err_msg,#user_id_err_msg').text('');


            // let formData = new FormData($('#form')[0]);
            // formData.append('_token', '{{ csrf_token() }}');
            // $.ajax({
            //     url: "{{ route('tasks.store') }}",
            //     data: formData,
            //     type: 'post',
            //     processData: false,
            //     contentType: false,
            //     cache: false,

            //     success: function(response) {
            //         console.log('Task Send AJax ');
            //         $('#form')[0].reset();
            //         $('#ajax-modal').modal('hide');
            //         getTasks();


            //         // toastr.success(response.message);
            //         // $("table tbody").append(markup(e));

            //     },
            //     error: function(reject) {

            //         let res = $.parseJSON(reject.responseText);
            //         // console.log(res.errors);
            //         $.each(res.errors, function(key, value) {
            //             $("#" + key + "_err_msg").text(value[0]);
            //         })
            //     }

            // });


        });
        */
    </script>




    <script>
        /* When click delete task */
        $('.delete-task').click(function() {

            var id = $(this).data('id');
            console.log('delete-task');
            // console.log(id);

            /*
            $.ajax({
                url: "tasks/" + id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    console.log(response);
                    // getTasks();


                    // toastr.success(response.message);

                }
            });
            */

        })
    </script>

    <script>
        // $('#edit-task').click(function() {
        //     console.log('-- edit-task --');
        // });



        /*
        $('.edit-task').click(function() {
            console.log('edit-task');
            $("#btn_save").attr('id', 'btn_edit');

            $('#taskShowModal').html("Edit Task");
            $('#ajax-modal').modal('show');

            let id = $(this).attr("data-id")
            $('.modal-body').append(`<input type="hidden" value="${id}" name="id" id="inputId">`);




            $.get('tasks/' + id + '/edit', function(data) {
                $('#taskShowModal').html("Todo Edit");
                $('#ajax-modal').modal('show');
                $('#inputName').val(data.name);
                $('#inputDesc').val(data.description);
                $(".select").val(data.user_id).change();
            });

        });
        */


        // $('#btn_edit').click(function() {
        //     console.log('btn_edit');
        //     let id = $('#inputId').val();
        //     console.log(id);

        //     // let formData = new FormData($('#form')[0]);
        //     // formData.append('_token', '{{ csrf_token() }}');
        //     // formData.append('_method', 'PUT');

        //     // $.ajax({
        //     //     type: "post",
        //     //     url: "/tasks/" + id,
        //     //     data: formData,
        //     //     processData: false,
        //     //     contentType: false,
        //     //     cache: false,

        //     //     success: function(response) {
        //     //         console.log('Task Send AJax updat ');
        //     //         $('#form')[0].reset();
        //     //         $('#ajax-modal').modal('hide');
        //     //         // toastr.success(response.message + id);
        //     //         getTasks();
        //     //     },
        //     //     error: function(reject) {

        //     //         let res = $.parseJSON(reject.responseText);
        //     //         $.each(res.errors, function(key, value) {
        //     //             $("#" + key + "_err_msg").text(value[0]);
        //     //         })
        //     //     }




        //     // })



        // });
    </script>
@endsection
