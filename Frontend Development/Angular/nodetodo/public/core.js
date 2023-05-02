var nodeTodo = angular.module("nodeTodo", []);

function mainController($scope, $http) {
  $scope.formData = {};
  $scope.todos = [];
  $scope.cos = "Andrzej";

  // when landing on the page, get all todos and show them
  $http
    .get("/api/todos")
    .success(function(data) {
      $scope.todos = data;
    })
    .error(function(data) {
      console.log("Error: " + data);
    });

  // when submitting the add form, send the text to the node API
  $scope.createTodo = function() {
    $http
      .post("/api/todos", $scope.formData)
      .success(function(data) {
        document.getElementById("newTodo").value = "";
        $scope.todos.push(data);
      })
      .error(function(data) {
        console.log("Error: " + data);
      });
  };

  // update a todo after checking it
  $scope.updateTodo = function(id) {
    let todo = $scope.todos.find((el) => el._id === id);
    $http
      .put("/api/todos", {_id: todo._id, text: todo.text, done: todo.done})
      .success(function(data) {
        document.getElementById("newTodo").value = "";
        todo = data;
      })
      .error(function(data) {
        console.log("Error: " + data);
      });
  };

  // delete a todo after checking it
  $scope.deleteTodo = function(id) {
    $http
      .delete("/api/todos/" + id)
      .success(function(data) {
        $scope.todos = data;
      })
      .error(function(data) {
        console.log("Error: " + data);
      });
  };
}
