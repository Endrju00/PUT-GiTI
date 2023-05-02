const Todo = ({ text, todo, todos, setTodos }) => {
    const deleteHandler = () => {
        setTodos(todos.filter((el) => el._id !== todo._id));
        deleteTodo();
    };

    const competeHandler = () => {
        setTodos(todos.map((item) => {
            if (item._id === todo._id) {
                return {
                    ...item,
                    done: !item.done
                };
            }
            return item;
        }))
        updateTodo();
    };

    function updateTodo() {
        console.log(todo._id);
        fetch(`http://localhost:4000/api/todos/`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _id: todo._id, text: todo.text, done: !todo.done }),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    function deleteTodo() {
        if (todo._id === undefined) {
            return;
        }
        fetch(`http://localhost:4000/api/todos/${todo._id}`, {
            method: 'DELETE',
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    return (
        <div className="todo">
            <li className={`todo-item ${todo.done ? "done" : ""}`}>{text}</li>
            <button onClick={competeHandler} className="complete-btn"><i className="fas fa-check"></i></button>
            <button onClick={deleteHandler} className="trash-btn"><i className="fas fa-trash"></i></button>
        </div>
    );
};

export default Todo;