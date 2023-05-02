import React, { useEffect, useState, useContext } from "react";
import { v4 as uuidv4 } from 'uuid';
import MyContext from "./MyContext";
import "./TodoList.css";

const TodoList = props => {
  const [loading, setLoading] = useState(false);
  const [activity, setActivity] = useState("");
  const [data, setData] = useState([]);
  const [error, setError] = useState("");

  const myContext = useContext(MyContext);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);

      try {
        const response = await fetch(
          "https://jsonplaceholder.typicode.com/todos/"
        );
        const items = await response.json();
        setData(items);
      } catch (error) {
        setError(error);
      }
      setLoading(false);
    };

    fetchData();

    return () => {
      console.log("zmiana nazwy");
    };
  }, []);

  const lis = data.map((d, i) => <li key={d.id}>{d.title}</li>);

  const handleSubmit = e => {
    e.preventDefault();
    setData([...data, { id: uuidv4(), title: activity }]);
    setActivity("");
  };

  return (
    <div>
      <h2>ToDos {myContext}</h2>
      <small>{loading ? "loading…" : ""}</small>
      {error}
      <hr />
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          value={activity}
          onChange={e => setActivity(e.target.value)}
        ></input>

        <button>Save</button>
      </form>
      <ul>{lis}</ul>
    </div>
  );
};

export default TodoList;
