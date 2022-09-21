
const buttonAddTheme = document.getElementById('btn-add-theme');


buttonAddTheme.addEventListener('click', function(e){
    const newTheme = document.getElementById('add-theme').value;
    if(newTheme != "") {
       waitingForResponse(newTheme);
    } 
})

async function waitingForResponse(string) {
    try {
        const response = await fetch("action.php?action=add_theme&name_theme="+string);
        const todoList = await response.json();
        console.table(todoList);
        createTheme(string, todoList['id-theme']);
    }
    catch(error) {
    console.error("Unable to load todolist datas from the server : " + error);
    } 
}

function createTheme(s1, s2) {
    let newNode = document.createElement("label")
    newNode.innerHTML = "<input type='checkbox' checked name='theme[]' value='"+s2+"'>"+s1+"</input><br>";
    document.getElementById('fieldset').insertBefore(newNode, document.getElementById('add-theme'));
    document.getElementById('add-theme').value = "";
}