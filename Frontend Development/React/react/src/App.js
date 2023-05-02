import React from "react";
import "./App.css";
import ClassComponent from "./ClassComponent";
import Dictaphone from "./Dictaphone";
import { FunctionalComponent } from "./FunctionalComponent";
import logo from "./logo.svg";
import MyContext from "./MyContext";
import TodoList from "./TodoList";

function App() {
  const cos = "Ala ma psa";

  return (
    <MyContext.Provider value={cos}>
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <h1>React: Context API & Hooks</h1>
        </header>
      </div>

      <div className="App-content">
        
    <Dictaphone />

      </div>
    </MyContext.Provider>
  );
}

export default App;
