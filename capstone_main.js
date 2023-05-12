ajaxURL = "webservice.php";

$(document).ready(function(){

    console.log("READY!");
    $('#btnGo').click(function(){PourDrink()});
    GetRecipes();
    
})

function GetRecipes(){
    console.log("Getting Recipes...")
    let sendData = {};
    sendData['action'] = 'getRecipes';
    AjaxRequest('GET', ajaxURL, sendData, 'json', ShowRecipes, ErrorHandler);
}

function ShowRecipes(data, AjaxStatus){

    console.log("ShowRecipes...");
    var listbox = $("#lbRecipes");
    listbox.empty();

    for(let i = 0; i < data["jsonData"].length; i++){
        console.log(data["jsonData"][i]);
        var option = document.createElement("option");
        option.text = data["jsonData"][i].Name;
        option.value = data["jsonData"][i].ID_PK;
        $('#lbRecipes').append(option);
    }
   
   
}

function PourDrink(){
    let sendData = {};
    sendData['action'] = "pourDrink";
    sendData['recipe'] = $("#lbRecipes").val();
    AjaxRequest('GET', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);
}
function HandleStatus(data, AjaxStatus){


}

function ErrorHandler(data, AjaxStatus) {
    console.log(AjaxStatus);
    console.log(data.responseText);
}