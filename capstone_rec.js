var ajaxURL = "webservice.php";
var ingredients;


$(document).ready(function(){
    ingredients = GetAllIngredients();
    GetAllRecipes();
    $("#submit").click(function(){ AddRecipe()})
    
})

function AddRecipe(){
    let sendData = {};
    sendData['action'] = "addRec";
    sendData['name'] = $("#name").val();
    let ingArray = [];
    for(let i = 1; i <= 4; ++i){
        console.log($("#i"+i).val());
        if($("#i"+i).val() != 0){
            ingArray.push({"id" : $("#i"+i).val(),"quantity" : $("#q"+i).val()});
        }
    }
    console.log(ingArray);
    sendData['data'] = ingArray;
    
    AjaxRequest('POST', ajaxURL, sendData, 'json', GetAllRecipes, ErrorHandler);
 }
function GetAllRecipes(){
    let sendData = {};
    sendData['action'] = "getRecipes";
    AjaxRequest('GET', ajaxURL, sendData, 'json', ShowRecipes, ErrorHandler);
}

function GetAllIngredients(){
    let sendData = {};
    sendData['action'] = "getAllIng"
    AjaxRequest('GET', ajaxURL, sendData, 'json', FillDropDowns, ErrorHandler);
}
function ShowRecipes(data, AjaxStatus){
    //console.log("HERE!");
    console.log(data['jsonData']);
    console.log(data['jsonData'][0].Ing[0].Name);
    var tableBody = $('#table-body');
    tableBody.empty();
    for(let i = 0; i < data['jsonData'].length; ++i){

        //Create the delete button and assign the Ingredient ID to a attribute on the button
        var deleteBtn = document.createElement("BUTTON");
        deleteBtn.innerHTML = "Delete";
        deleteBtn.classList.add("deleteRec");
        deleteBtn.dataset.id = `${data['jsonData'][i].ID_PK}`;
        var oP = document.createElement('td');
        oP.appendChild(deleteBtn);

        //Ingredients Cell
        var ingDiv = document.createElement("div");
        ingDiv.style.display = "inline-block";
        ingDiv.style.verticalAlign = "top";
        ingDiv.style.overflow = "hidden";
        ingDiv.style.border = "solid grey 1px";
        var ingDD = document.createElement("select");
        ingDD.size = data['jsonData'][i].Ing.length;
        ingDD.style.padding = "10px";
        ingDD.style.margin = "-5px -20px -5px -5px";
        for(let j = 0; j < data['jsonData'][i].Ing.length; ++j){
            var option = document.createElement("option");
            option.value = data['jsonData'][i].Ing[j].Name;
            option.text = data['jsonData'][i].Ing[j].Name;
            ingDD.appendChild(option);
        }
        ingDD.dataset.id = `${data['jsonData'][i].ID_PK}`
        ingDiv.appendChild(ingDD);
        var ing = document.createElement("td");
        ing.appendChild(ingDiv);
        

        //Create Table Row
        var tableRow = document.createElement('tr');
        //Create Name Cell
        var name = document.createElement('td');
        name.innerHTML = data['jsonData'][i].Name;
        //Append all cells to table row
        tableRow.appendChild(name);
        tableRow.appendChild(ing);
        tableRow.appendChild(oP);
        
        //Append table row to body
        tableBody.append(tableRow);
    }
    //Bind event handler to the created buttons
    $('.deleteRec').click(function(){ DeleteRec(this)});
    $('.IngDD').each(function() { FillIng(this)});
}

function DeleteRec(obj){
    let sendData = {};
    sendData["action"] = "deleteRec";
    console.log(obj.getAttribute("data-id"));
    sendData["ID"] = obj.getAttribute("data-id");
    AjaxRequest('POST', ajaxURL, sendData, 'json', GetAllRecipes, ErrorHandler);
}


function FillDropDowns(data, AjaxStatus)
{
    console.log(data['status']);
    ingredients = data['jsonData'];
    for(let i = 0; i < ingredients.length; ++i){
        $("#i1").append($('<option></option>').val(ingredients[i].ID_PK).html(ingredients[i].NAME));
        $("#i2").append($('<option></option>').val(ingredients[i].ID_PK).html(ingredients[i].NAME));
        $("#i3").append($('<option></option>').val(ingredients[i].ID_PK).html(ingredients[i].NAME));
        $("#i4").append($('<option></option>').val(ingredients[i].ID_PK).html(ingredients[i].NAME));
    }
    
}

function ErrorHandler(){
    
}


