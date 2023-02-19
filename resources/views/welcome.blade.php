<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>

    </style>
</head>

<body class="antialiased">
    <button type="button" class="btn btn-primary" id="add_todo">Add Todo </button>

    <table class="table table-bordered">
        <thead>
            <th>Sr.no</th>
            <th>Name</th>
            <th>Action</th>
        </thead>

        <tbody id="list_todo">
            @foreach ($todos as $todo)
                <tr id="row_todo_{{ $todo->id }}">
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->name }}</td>
                    <td>
                        <button type="button" id="edit_todo"
                            data-id="{{ $todo->id }}"class="btn btn-sm btn-info ml-1">Edit</button>
                        <button type="button" id="delete_todo"
                            data-id="{{ $todo->id }}"class="btn btn-sm btn-danger ml-1">Delete</button>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- The Modal -->
    <div class="modal" id="modal_todo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-todo">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="name" id="name_todo" class="form-control"
                            placeholder="Enter todo ...">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                }
            })
        });


        $("#add_todo").on('click', function() {
            $("#form_todo").trigger('reset');
            $("#modal_title").html('Add todo');
            $("#modal_todo").modal('show');
        });

        $("body").on('click', '#edit_todo', function() {
            var id = $(this).data('id');
            $.get('todos/' + id + '/edit', function(res) {
                $("#modal_title").html('Edit Todo');
                $("#id").val(res.id);
                $("#name_todo").val(res.name);
                $("#modal_todo").modal('show');
            });
        });


        //Delete Todo

        $("body").on('click', '#delete_todo', function() {
            var id = $(this).data('id');
            confirm('Are you sure want to delete')

            $.ajax({
                type: 'DELETE',
                url: "todo/destroy/" + id
            }).done(function(res) {
                $("#row_todo_" + id).remove();
            });
        });


        //save data
        $("form").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "todos/store",
                data: $("#form_todo").serialize(),
                type: 'POST'
            }).done(function(res) {
                var row = '<tr id="row_todo_' + res.id + '">';
                row += '<td>' + res.id + '</td>';
                row += '<td>' + res.name + '</td>';
                row += '<td>' + '<button type="button" id="edit_todo" data-id="' + res.id +
                    '" class="btn btn-info btn-sm mr-1">Edit</button>' +
                    '<button type="button" id="delete_todo" data-id="' + res.id +
                    '" class="btn btn-danger btn-sm">Delete</button>' +
                    '</td>';

                if ($("#id").val()) {
                    $("#row_todo" + res.id).replaceWith(row);
                } else {
                    $("#list_todo").prepend(row);
                }

                $("#form_todo").trigger('reset');
                $("#model_todo").model('hide');

            });
        });
    </script>
</body>

</html>
