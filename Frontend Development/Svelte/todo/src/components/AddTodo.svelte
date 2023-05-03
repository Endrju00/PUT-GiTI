<script lang="ts">
    type AddTodoType = (todo: string) => void
    type TodosAmountType = number

    export let createTodo: AddTodoType

    let todo: string = ''

    function handleSubmit() {
        createTodo(todo)
        todo = ''
    }
</script>

<form on:submit|preventDefault={handleSubmit}>
    <!-- svelte-ignore a11y-autofocus -->
    <input
      bind:value={todo}
      id="new-todo"
      class="new-todo"
      placeholder="What needs to be done?"
      type="text"
      autofocus
    />
</form>

<style>
    .new-todo {
        width: 100%;
        padding: var(--spacing-16);
        padding-left: 60px;
        font-size: var(--font-24);
        border: none;
        border-bottom: 1px solid var(--shadow-1);
    }
</style>
