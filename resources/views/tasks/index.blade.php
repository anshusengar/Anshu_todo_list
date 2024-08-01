<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>To-Do List</h1>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <input type="text" id="taskInput" placeholder="New task">
    <button id="addTask">Add Task</button>
    <button id="showAll">Show All Tasks</button>
    <ul id="taskList"></ul>

    <script>

$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function fetchTasks(showAll = false) {
            $.get('/tasks', function(tasks) {
                $('#taskList').empty();
                tasks = showAll ? tasks : tasks.filter(task => !task.completed);
                tasks.forEach(task => {
                    $('#taskList').append(`
                        <li>
                            <input type="checkbox" ${task.completed ? 'checked' : ''} data-id="${task.id}" class="toggle-complete">
                            ${task.task}
                            <button data-id="${task.id}" class="delete-task">Delete</button>
                        </li>
                    `);
                });
            });
        }

        $(document).ready(function() {
            fetchTasks();

            $('#addTask').click(function() {
                const task = $('#taskInput').val();
                $.post('/tasks', { task }, function(response) {
                    $('#taskInput').val('');
                    fetchTasks();
                }).fail(function(response) {
                    alert(response.responseJSON.message);
                });
            });

            $('#taskList').on('click', '.toggle-complete', function() {
                const id = $(this).data('id');
                const completed = $(this).is(':checked');
                $.ajax({
                    url: `/tasks/${id}`,
                    method: 'PUT',
                    data: { completed },
                    success: function() {
                        fetchTasks();
                    }
                });
            });

            $('#taskList').on('click', '.delete-task', function() {
                if (!confirm('Are you sure to delete this task?')) return;

                const id = $(this).data('id');
                $.ajax({
                    url: `/tasks/${id}`,
                    method: 'DELETE',
                    success: function() {
                        fetchTasks();
                    }
                });
            });

            $('#showAll').click(function() {
                fetchTasks(true);
            });
        });
    </script>
</body>
</html>