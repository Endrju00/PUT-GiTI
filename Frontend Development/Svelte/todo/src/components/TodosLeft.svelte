<script lang="ts">
    export let todosLeft: number;
</script>

<span class="todo-count">
    {todosLeft}
    {todosLeft === 1 ? "item" : "items"} left
</span>