/* things to do: consol.log */
// console.log("hello world")

/* things to do: put IDs */

/* now, things to do: locate the selectors */

const toDoButton = document.getElementById("to-do-button")
const toDoInput = document.getElementById("to-do-input")
const toDoList = document.getElementById("to-do-list")

// console.log(todoInput, todoButton, todoList)

/* now, add event listener */

toDoButton.addEventListener("click", addThingsToDo)

/* now, create a function without panic */

function addThingsToDo(event) {
    const taskContent = document.createElement("div");
    taskContent.classList.add("to-do");

    const newTask = document.createElement("li");
    newTask.innerText = "hello world!";
    newTask.classList.add("to-do-item")
}