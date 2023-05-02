const Form = ({ setInputText, todos, setTodos, inputText, setStatus }) => {
    const inputTextHandler = (event) => {
        setInputText(event.target.value);
    };

    const submitTodoHandler = (event) => {
        event.preventDefault();
        if (inputText !== "") {
            createTodo();
        }
        document.getElementById("taskInput").focus()
    }

    const statusHandler = (event) => {
        setStatus(event.target.value);
    }

    function createTodo() {
        fetch('http://localhost:4000/api/todos/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ text: inputText, done: false }),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                setTodos([
                    ...todos,
                    { text: inputText, done: false, _id: data._id }
                ]);
                setInputText("");
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    return (
        <form>
            <input id="taskInput" autoFocus value={inputText} onChange={inputTextHandler} type="text" className="todo-input" />
            <button onClick={submitTodoHandler} className="todo-button" type="submit">
                <i className="fas fa-plus-square"></i>
            </button>
            <div className="select">
                <select onChange={statusHandler} name="todos" className="filter-todo">
                    <option value="all">All</option>
                    <option value="done">Completed</option>
                    <option value="uncompleted">Uncompleted</option>
                </select>
            </div>
        </form>
    );
};

export default Form;