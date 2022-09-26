
const buttonAddTheme = document.getElementById('btn-add-theme');

buttonAddTheme.addEventListener('click', function(e){
    const newTheme = document.getElementById('add-theme').value;
    if(newTheme != "") {
        waitingForResponse(newTheme);
    } 
})

async function waitingForResponse(string) {
    try {
        // console.log(string);
        const response = await fetch("api.php?action=add_theme&name_theme="+string);
        const todoList = await response.json();
        console.table(string,todoList);
        if(todoList['is-good']) createTheme(string, todoList['id-theme']);
        // else "error";
    }
    catch(error) {
        console.error("Unable to load todolist datas from the server : " + error);
    } 
}

function createTheme(s1, s2) {
    // console.log('youpi yopu', "hdshfdhjwd");
    let newNode = document.createElement("label")
    newNode.innerHTML = "<input type='checkbox' checked name='theme[]' value='"+s2+"'>"+s1+"</input><br>";
    document.getElementById('fieldset').insertBefore(newNode, document.getElementById('add-theme'));
    document.getElementById('add-theme').value = "";
}

