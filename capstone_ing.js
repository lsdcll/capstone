ajaxURL = "webservice.php";

$(document).ready(function(){
    GetIng();
    $('#isLoaded').click(function(){ ToggleControls()});
    $("#submit").click(function() { AddIngredient()});
    //$('#addUser').click(function(){  AddUser() });
})

function ToggleControls(){


    if($("#isLoaded").prop("checked") == true){
        $("#quantity").prop("disabled", false);
        $("#tankID").prop("disabled", false);
    }
    else{
        $("#quantity").prop("disabled", true);
        $("#tankID").prop("disabled", true);
    }

}   
function AddIngredient(){
    let sendData = {};
    sendData['action'] = 'addIng';
    sendData['Name'] = $("#name").val();
    if($("#isLoaded").prop("checked") == true){
        sendData['isLoaded'] = true;
        sendData['quantity'] = $("#quantity").val();
    }
    else{
        sendData['isLoaded'] = false;
    }
    console.log(sendData['isLoaded']);
    AjaxRequest('POST', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);
}

function GetIng(){
    let sendData = {};
    sendData["action"] = "getAllIng";
    AjaxRequest('GET', ajaxURL, sendData, 'json', ShowIng, ErrorHandler);
}

function ShowIng(data, AjaxStatus){

    var tableBody = $('#table-body');
    tableBody.empty();
    for(let i = 0; i < data['jsonData'].length; ++i){

        //Create the delete button and assign the Ingredient ID to a attribute on the button
        var deleteBtn = document.createElement("BUTTON");
        deleteBtn.innerHTML = "Delete";
        deleteBtn.classList.add("deleteIng");
        deleteBtn.dataset.id = `${data['jsonData'][i].ID_PK}`;
        var oP = document.createElement('td');
        oP.appendChild(deleteBtn);

        //Quantity Cell
        var quantity = document.createElement('td');
        var tbQ = document.createElement("input");
        if(data['jsonData'][i].QUANTITY == null){
            
            tbQ.type = "text";
            tbQ.id = "q"+ i;
            quantity.appendChild(tbQ);
        }
        else{
            quantity.innerHTML = data['jsonData'][i].QUANTITY;
        }

        //Create Load / Unload Button
        if(data['jsonData'][i].QUANTITY != null){
            var unloadBtn = document.createElement("Button");
            unloadBtn.innerHTML = "Unload";
            unloadBtn.classList.add("unloadIng");
            unloadBtn.dataset.id = `${data['jsonData'][i].ID_PK}`;
            unloadBtn.dataset.name = `${data['jsonData'][i].NAME}`;
            unloadBtn.dataset.quantity = `${data['jsonData'][i].QUANTITY}`;
            oP.appendChild(unloadBtn);
        }
        else{
            var loadBtn = document.createElement("Button");
            loadBtn.innerHTML = "Load";
            loadBtn.classList.add("loadIng");
            loadBtn.dataset.id = `${data['jsonData'][i].ID_PK}`;
            loadBtn.dataset.row = i;
            var oP1 = document.createElement('td');
            oP.appendChild(loadBtn);
        }

        //Create Table Row
        var tableRow = document.createElement('tr');
        //Create Name Cell
        var name = document.createElement('td');
        name.innerHTML = data['jsonData'][i].NAME;
        //Append all cells to table row
        tableRow.appendChild(name);
        tableRow.appendChild(quantity);
        tableRow.appendChild(oP);
        //Append table row to body
        tableBody.append(tableRow);
    }
    //Bind event handler to the created buttons
    $('.deleteIng').click(function(){ DeleteIng(this)});
    $('.unloadIng').click(function(){ UnloadIng(this)});
    $('.loadIng').click(function(){ LoadIng(this)});
}

function HandleStatus(data, AjaxStatus)
{
    $("#pageStatus").text(data['status']);
    console.log(data['status']);
    GetIng();
}

function ErrorHandler(){
    
}

function DeleteIng( obj )
{
    let sendData = {};
    sendData["action"] = "deleteIng";
    console.log(obj.getAttribute("data-id"));
    sendData["ID"] = obj.getAttribute("data-id");
    AjaxRequest('POST', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);
}
function UnloadIng( obj )
{
    let sendData = {};
    sendData["action"] = "unloadIng";
    console.log(obj.getAttribute("data-id"));
    sendData["ID"] = obj.getAttribute("data-id");
    sendData["NAME"] = obj.getAttribute("data-name");
    sendData["QUANTITY"] = obj.getAttribute("data-quantity");
    AjaxRequest('POST', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);
}
function LoadIng( obj )
{
    let sendData = {};
    sendData["action"] = "loadIng";
    console.log(obj.getAttribute("data-id"));
    sendData["ID"] = obj.getAttribute("data-id");
    var tempq = "#q" + obj.getAttribute("data-row");``
    sendData["QUANTITY"] = $(tempq).val();
    AjaxRequest('POST', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);
}