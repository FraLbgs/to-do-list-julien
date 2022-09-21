const message = document.getElementById("confirm-msg");
// message.innerText = "gzrgrhyqry";
// const finishList = document.querySelectorAll(".links a:last-child");
// console.log(finishList);
// finish.addEventListener("click", displayMessage);

// for(const finish of finishList){
//     finish.addEventListener("click", displayMessage);
// }


// function displayMessage(){
//     message.innerText = "gregergez";
//     setTimeout(() => {
//         message.innerText = "";
//       }, "3000");
// }

if(message.innerText !== ""){
  setTimeout(() => {
    message.innerText = "";
    history.replaceState(null, document.title, 'index.php');
    }, "3000");
}
