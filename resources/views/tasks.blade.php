@extends('layouts.app')

@section('content')
    <style>
        li {
            list-style-type: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div>

        @if (count($errors) > 0)
            <div>
                <strong>Упс! Что-то пошло не так!</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <label for="task">Задача</label>
        <input type="text" name="task" id="task-name">
        <button onclick="addTask()">Добавить задачу</button>
    </div>

    <ul id="listTask">
        @if (count($tasks) > 0)
            @foreach ($tasks as $task)
                <li id="task{{ $task->id }}">
                    <div>
                        <input onclick="updateTask({{ $task->id }})" type="checkbox" {{ ($task->status) ? 'checked' : '' }}>
                        <span class="text-todo" id="taskSpan{{ $task->id }}" style="{{ ($task->status) ? 'text-decoration: line-through;' : '' }}">{{ $task->task }}</span>
                        <button onclick="destroyTask({{ $task->id }})">Удалить задачу</button>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
    <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            editableSpan();
        });
        function editableSpan() {
            $(".text-todo").dblclick(function (e) {
                const OriginalContent = $(this).text();
                const id = e.target.getAttribute('id').replace('taskSpan', '');
                $(this).html("<input type='text' value='" + OriginalContent + "' />");
                $(this).children().first().focus();
                $(this).children().first().keypress(function (e) {
                    if (e.keyCode == 13) {
                        const newContent = $(this).val();
                        $(this).parent().text(newContent);
                        textUpdateTask(id, newContent);
                    }
                });
                $(this).children().first().blur(function () {
                    $(this).parent().text(OriginalContent);
                });
            });

        }
        function addTask(){
            $.ajax({
                type: "POST",
                url: "/task",
                data: {
                    task: $('#task-name').val(),
                    _method: 'POST'
                }
            }).done(function(data) {
                const task = JSON.parse(data);
                $('#task-name').val('');
                $('#listTask').append('<li id="task' + task.id  + '"><div><input onclick="updateTask(' + task.id  + ')" type="checkbox"' + ((task.status) ? 'checked' : '') + '><span class="text-todo" id="taskSpan' + task.id  + '" style="' + ((task.status) ? 'text-decoration: line-through;' : '' ) + '">' + task.task + '</span><button onclick="destroyTask(' + task.id  + ')">Удалить задачу</button></div></li>');
                editableSpan();
            });
        }
        function updateTask(id){
            $.ajax({
                type: "POST",
                url: "/task-update/" + id,
                data: {
                    id: id,
                    _method: 'PATCH'
                }
            }).done(function(data) {
                const task = JSON.parse(data);
                $('#taskSpan' + id).attr('style', (task.status) ? 'text-decoration: line-through;' : '');
            });
        }
        function textUpdateTask(id, text){
            $.ajax({
                type: "POST",
                url: "/text-task-update/" + id,
                data: {
                    id: id,
                    task: text,
                    _method: 'PATCH'
                }
            }).done(function(data) {
                //console.log(JSON.parse(data));
            });
        }
        function destroyTask(id){
            $.ajax({
                type: "POST",
                url: "/task-destroy/" + id,
                data: {
                    id: id,
                    _method: 'DELETE'
                }
            }).done(function(data) {
                $('#task' + id).remove();
            });
        }
    </script>
@endsection