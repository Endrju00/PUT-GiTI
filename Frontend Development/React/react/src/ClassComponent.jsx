import React, { Component } from "react";
import MyContext from "./MyContext";

export default class ClassComponent extends Component {
  static contextType = MyContext;

  constructor(props) {
    super(props);

    this.state = {
      ala: "cos",
      count: 0
    };
  }

  handleChange = e => {
    console.log("click");
    this.setState({ ...this.state, count: this.state.count + 1 });
  };

  render() {
    return (
      <div>
        {this.props.name} ma {this.props.age} lat
        <br />
        <button onClick={this.handleChange}>{this.state.count}</button>

        <div>{this.context}</div>
      </div>
    );
  }
}
