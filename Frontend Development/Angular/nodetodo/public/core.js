var nodeTodo = angular.module("nodeTodo", []);

function mainController($scope, $http) {
  $scope.formData = {};
  $scope.todos = [];
  $scope.deleted = [];
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
        $scope.todos = data;
      })
      .error(function(data) {
        console.log("Error: " + data);
      });
  };

  // update a todo after checking it
  $scope.updateTodo = function(id) {
    let d = $scope.deleted.find((todo) => todo._id === id);
    let index = $scope.deleted.indexOf(d);
    if (index > -1) {
      $scope.deleted.splice(index, 1);
    }
    $scope.todos.push(d);
    $http
      .post("/api/todos", {text: d.text})
      .success(function(data) {
        document.getElementById("newTodo").value = "";
        $scope.todos = data;
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
        let d = $scope.todos.find((todo) => todo._id === id);
        $scope.deleted.push(d);
        $scope.todos = data;
      })
      .error(function(data) {
        console.log("Error: " + data);
      });
  };
}
