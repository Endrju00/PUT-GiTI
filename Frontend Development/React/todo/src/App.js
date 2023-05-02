import { useState, useEffect } from "react"
import './App.css';
import Form from "./components/Form"
import TodoList from './components/TodoList';

function App() {
  const [inputText, setInputText] = useState("");
  const [todos, setTodos] = useState([]);
  const [status, setStatus] = useState("all");
  const [filteredTodos, setFilteredTodos] = useState([]);

  // Get data from server
  async function getTodos() {
    const response = await fetch('http://localhost:4000/api/todos/');
    const data = await response.json();
    setTodos(data);
  }

  useEffect(() => {
    getTodos();
  }, []);

  // Filter on change todos and status and save data
  useEffect(() => {
    const filterHandler = () => {
      switch (status) {
        case "done":
          setFilteredTodos(todos.filter(todo => todo.done === true));
          break;
        case "uncompleted":
          setFilteredTodos(todos.filter(todo => todo.done === false));
          break
        default:
          setFilteredTodos(todos);
          break;
      }
    };

    filterHandler();
  }, [todos, status]);

  return (
    <div className="App">
      <header>
        <h1>Todo List</h1>
      </header>
      <Form
        inputText={inputText}
        todos={todos}
        setTodos={setTodos}
        setInputText={setInputText}
        setStatus={setStatus}
      />
      <TodoList
        setTodos={setTodos}
        todos={todos}
        filteredTodos={filteredTodos}
      />
    </div>
  );
}

export default App;
