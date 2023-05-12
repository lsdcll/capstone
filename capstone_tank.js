var ajaxURL = "webservice.php";

$(document).ready(function(){
    GetTanks();
})

function GetTanks(){
    let sendData = {};
    sendData['action'] = 'getTanks';
    AjaxRequest('GET', ajaxURL, sendData, 'json', ShowTanks, ErrorHandler);
}

function ShowTanks(data, AjaxRequest){
    var tableBody = $('#table-body');
    tableBody.empty();
    for(let i = 0; i < data['jsonData'].length; ++i){

        //Ingredients Cell
        var ingDD = document.createElement("select");
        ingDD.id = "I" + i;
        for(let j = 0; j < data['jsonData'][i].Ing.length; ++j){
            var option = document.createElement("option");
            option.value = data['jsonData'][i].Ing[j].ID_PK;
            option.text = data['jsonData'][i].Ing[j].Name;
            if(data['jsonData'][i].Ing[j].ID_PK == data['jsonData'][i].Ingredient_ID){
                option.selected = true;
            } 
            ingDD.appendChild(option);
        }
        ingDD.dataset.id = `${data['jsonData'][i].ID_PK}`
        var ing = document.createElement("td");
        ing.appendChild(ingDD);
        
        //Create Update Button
        var updateBtn = document.createElement("Button");
        updateBtn.innerHTML = "Update";
        updateBtn.classList.add("updateIng");
        updateBtn.dataset.id = `${data['jsonData'][i].ID_PK}`;
        updateBtn.dataset.row = i;
        var oP = document.createElement('td');
        oP.appendChild(updateBtn);

        //Create Table Row
        var tableRow = document.createElement('tr');
        //Create Name Cell
        var TankNum = document.createElement('td');
        TankNum.innerHTML = data['jsonData'][i].Tank_Number;
        //Append all cells to table row
        tableRow.appendChild(TankNum);
        tableRow.appendChild(ing);
        tableRow.appendChild(oP);
        
        //Append table row to body
        tableBody.append(tableRow);
    }
    $(".updateIng").click(function(){ UpdateTankIng(this)});
}
function UpdateTankIng(obj){
    let sendData = {};
    sendData['action'] = 'updateTank';
    sendData['TANKID'] = obj.getAttribute("data-id");
    sendData['IngID'] = $("#I" + obj.getAttribute("data-row")).val();
    AjaxRequest('POST', ajaxURL, sendData, 'json', HandleStatus, ErrorHandler);

}

function HandleStatus(){

}
function ErrorHandler(){

}