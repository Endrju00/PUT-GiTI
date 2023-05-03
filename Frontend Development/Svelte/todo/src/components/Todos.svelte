<script lang="ts">
    import type { ITodo, FiltersType } from '$root/types/todo'
    import { onMount } from "svelte";

    import AddTodo from './AddTodo.svelte';
    import Todo from './Todo.svelte';
    import TodosLeft from './TodosLeft.svelte';
    import FilterTodos from './FilterTodos.svelte';

    let todos: ITodo[] = [];
    onMount(async function () {
        const response = await fetch('http://localhost:4000/api/todos/');
        todos = await response.json();;
    });

    let selectedFilter: FiltersType = 'all'
    
    $: todosAmount = todos.length;
    $: todosLeft = todos.filter(todo => !todo.done).length;
    $: filteredTodos = filterTodos(todos, selectedFilter);

    async function createTodo(todo: string): Promise<void> {
      const response = await fetch('http://localhost:4000/api/todos/', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ text: todo, done: false })
      });

      const newTodo: ITodo = await response.json();
      todos = [...todos, newTodo];
    }

    async function updateTodo(id: string) {
      todos = todos.map(todo => {
        if (todo._id === id) {
          todo.done = !todo.done;
        }
        return todo;
      })

      let todo = todos.find(todo => todo._id === id);
      await fetch('http://localhost:4000/api/todos', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
          body: JSON.stringify({ _id: todo._id, done: todo.done, text: todo.text })
      });
    }

    async function removeTodo(id: string) {
      await fetch(`http://localhost:4000/api/todos/${id}/`, {
        method: 'DELETE'
      });
      todos = todos.filter(todo => todo._id !== id);
    }

    function setFilter(newFilter: FiltersType) {
      selectedFilter = newFilter;
    }

    function filterTodos(todos: ITodo[], filter: FiltersType): ITodo[] {
      switch (filter) {
        case 'active':
          return todos.filter(todo => !todo.done);
        case 'completed':
          return todos.filter(todo => todo.done);
        default:
          return todos;
      }
    }
</script>

<main>
    <h1 class="title">todos</h1>
  
    <section class="todos">
      <AddTodo {createTodo} />
      {#if todosAmount}
        <ul class="todo-list">
          {#each filteredTodos as todo (todo._id)}
            <Todo {todo} {updateTodo} {removeTodo}/>
          {/each}
        </ul>
    
        <div class="actions">
          <TodosLeft {todosLeft} />
          <FilterTodos {selectedFilter} {setFilter} />
        </div>
      {/if}
    </section>
</main>

<style>
    .title {
      font-size: var(--font-80);
      font-weight: inherit;
      text-align: center;
      color: var(--color-title);
    }
  
    .todos {
      --width: 500px;
      --todos-bg: hsl(0 0% 98%);
      --todos-text: hsl(220 20% 14%);
  
      width: var(--width);
      color: var(--todos-text);
      background-color: var(--todos-bg);
      border-radius: var(--radius-base);
      border: 1px solid var(--color-gray-90);
      box-shadow: 0 0 4px var(--shadow-1);
    }
  
    .todo-list {
      list-style: none;
    }
  
    .actions {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: var(--spacing-8) var(--spacing-16);
      font-size: 0.9rem;
      border-top: 1px solid var(--color-gray-90);
    }
  
    .actions:before {
      content: '';
      height: 40px;
      position: absolute;
      right: 0;
      bottom: 0;
      left: 0;
      box-shadow: 0 1px 1px hsla(0, 0%, 0%, 0.2), 0 8px 0 -3px hsl(0, 0%, 96%),
        0 9px 1px -3px hsla(0, 0%, 0%, 0.2), 0 16px 0 -6px hsl(0, 0%, 96%),
        0 17px 2px -6px hsla(0, 0%, 0%, 0.2);
      z-index: -1;
    }
</style>
  