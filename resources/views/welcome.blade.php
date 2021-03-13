<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<h1 class="text-center mt-5">Todo List</h1>
<div class="container mt-5">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="todo-list-name" placeholder="新增任務">
        <button class="btn btn-outline-secondary btn-primary text-white" type="button" id="add-todo-list">Add</button>
    </div>
    <ul class="list-group todo-list">
    </ul>
</div>
</body>
<script>
    const APP_URL = 'http://localhost:8000/api/';
    const todoListElement = document.querySelector('.todo-list');

    document.querySelector('#add-todo-list').addEventListener('click', addTodoList);

    function addAllEvent() {
        const checkboxElement = document.querySelectorAll('.todo-checkbox')
        for (let i = 0; i < checkboxElement.length; i++) {
            checkboxElement[i].addEventListener("click", updateTodoList);
        }

        const deleteBtn = document.querySelectorAll('.delete-btn')
        for (let i = 0; i < deleteBtn.length; i++) {
            deleteBtn[i].addEventListener("click", deleteTodoList);
        }
    }
    function appendTodoList(id, name, completed) {
        const html =
        `<li class="list-group-item">
            <input class="form-check-input todo-checkbox" type="checkbox" id="flexCheckDefault"
                list-id="${id}" ${ completed ? 'checked' : '' }>
            <label class="form-check-label" for="flexCheckDefault">
                ${name}
            </label>
            <a class="delete-btn" href="##" list-id="${id}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                </svg>
            </a>
        </li>`

        todoListElement.innerHTML += html;
    }

    async function getTodoList() {
        const response = await fetch(APP_URL + 'items');
        const todo_list = await response.json();
        todo_list.forEach(todo => {
            appendTodoList(todo.id, todo.name, todo.completed);
        })

        addAllEvent();
    }

    async function addTodoList() {
        const inputElement = document.querySelector('#todo-list-name')
        const name = inputElement.value;
        const response = await fetch(APP_URL + 'items', {
            method: 'POST',
            body: JSON.stringify({name: name}),
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
        });
        const todo = await response.json();

        appendTodoList(todo.id, todo.name, false);
        inputElement.value = '';
    }

    async function updateTodoList() {
        const id = this.getAttribute('list-id')
        const value = this.checked;

        await fetch(APP_URL + 'items/' + id, {
            method: 'PUT',
            body: JSON.stringify({completed: value}),
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
        });
    }

    async function deleteTodoList() {
        const liElement = this.parentElement;
        liElement.remove();

        const id = this.getAttribute('list-id')
        await fetch(APP_URL + 'items/' + id, {
            method: 'DELETE',
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
        });
    }

    getTodoList()
</script>
</html>
