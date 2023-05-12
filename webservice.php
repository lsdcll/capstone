<?php
// do requires
// ?? function/session stuff
//require_once "db.php";
require_once "db.php";

//seintel check
 /* if (!isset($_SESSION['user'])){
    //redirect not needed here in this case. 
    die();
 }
 */
// if we make it here.. connection was good

// Set to always get fresh page processing, no caches supplied
header("Cache-Control: no-cache, must re-validate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Define global response object
// Response Keys : jsonData - hold response to the request
//                 status - string status for success/fail
$ajaxData = array(); 
$ajaxData['status'] = "No query was executed!";
$ajaxData['jsonData'] = [];

function Done(){
    global $ajaxData;
    $responseArray = array();
    $responseArray['jsonData'] = $ajaxData['jsonData'];
    $responseArray['status'] = $ajaxData['status'];
    echo(json_encode($responseArray));
    die();
}

function PourDrink($recID){
    error_log("Pouring Drink...");

    global $mysqli, $mysqli_response, $mysqli_status; // register the globals here for use.. odd but it works that way    
    global $ajaxData;

    // local output variables
    $outArray = array(); // array to hold ingredients returned from query
    $outArray2 = array(); //array to hold pinOuts from query
    $status = "";

   

    $query = "select IngredientID, Quantity from RecipeIngredients WHERE RecipeID = '{$recID}'";
    error_log($query);
    
    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){
            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in
        }
        $status = "Query successful {$NumRows} rows returned";
    }
    else{
        $status = "Query {$query} failed";
    }

    foreach($outArray as $row){
        $query = "select PinOut from Tank WHERE Ingredient_ID = '{$row["IngredientID"]}'";
        error_log($query);
        if($result = mysqliQuery($query)){
            $row2 = $result->fetch_assoc();
            
            system(" gpio mode {$row2["PinOut"]} out ");
            system(" gpio write {$row2["PinOut"]} 1");
            sleep(5);
            system(" gpio write {$row2["PinOut"]} 0");
        }
        else{
            $status = "Query {$query} failed";
            error_log($status);
        }
    }  
}


function QueryGetAllAvailableRecipes()
{
    error_log("Query Available Recipes");
    $ajaxData["status"] = "Here!";

	global $mysqli, $mysqli_response, $mysqli_status; // register the globals here for use.. odd but it works that way    
    global $ajaxData;

    // local output variables
    $outArray = array(); // return dictionary
    $status = "";

   

    $query = "select *";
    $query.= "from Recipe ";
    

    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){
            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in
        }
        $status = "Query successful {$NumRows} rows returned";
    }
    else{
        $status = "Query {$query} failed";
    }
    error_log($outArray);
    $ajaxData['jsonData'] = $outArray;
    $ajaxData['status'] = $status;
    error_log($status);
    
}

function QueryGetAllRecipes()
{
    error_log("Query Recipes");
    $ajaxData["status"] = "Here!";

	global $mysqli, $mysqli_response, $mysqli_status; // register the globals here for use.. odd but it works that way    
    global $ajaxData;

    // local output variables
    $outArray = array(); // return dictionary
    $status = "";

   

    $query = "select *";
    $query.= "from Recipe ";
    

    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){

            $row["Ing"] = array();
            $queryIng = "select Name from Ingredients INNER JOIN RecipeIngredients ON RecipeIngredients.RecipeID = {$row['ID_PK']} Where RecipeIngredients.IngredientID = Ingredients.ID_PK";
            error_log($queryIng);
            if($result2 = mysqliQuery($queryIng)){
                while($row2 = $result2->fetch_assoc()){
                    $row["Ing"][] = $row2;
                }
            }

            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in
        }
        $status = "Query successful {$NumRows} rows returned";
    }
    else{
        $status = "Query {$query} failed";
    }
    $ajaxData['jsonData'] = $outArray;
    $ajaxData['status'] = $status;
    error_log($status);
    
}
function QueryAddRec($name, $data)
{

    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    
    //sanitize input
    $name = $mysqli->real_escape_string(strip_tags($name));
    
    $query = "INSERT INTO Recipe (Name, ID_PK) VALUES ('{$name}', NULL)";

    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "FAILED:Query Error: " . $query;

    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    error_log("Insert Recipe: " . $status);

    $query = "SELECT * from Recipe Where Name = '{$name}'";
    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){
            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in
        }
    }
   
    foreach($data as $row){
        $query = "INSERT INTO RecipeIngredients (RecipeID, IngredientID, Quantity) VALUES ('{$outArray[0]['ID_PK']}', '{$row['id']}', '{$row['quantity']}')";
        mysqliNonQuery($query);
    }

    $ajaxData['status'] = $status;
}
function QueryAddIng($name, $quantity)
{

    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    error_log("TO ADD TO DB: " . $name . " : " . $quantity );
    //sanitize input
    $name = $mysqli->real_escape_string(strip_tags($name));
    $quantity = $mysqli->real_escape_string(strip_tags($quantity));
    
    //build query
    if($quantity != 'NULL'){
        $query = "INSERT INTO Ingredients (NAME, ID_PK, QUANTITY) VALUES('{$name}', NULL, '{$quantity}')";
    }
    else{
        $query = "INSERT INTO Ingredients (NAME, ID_PK, QUANTITY) VALUES('{$name}', NULL, NULL)";
    }
    
    error_log($query);

    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "FAILED:Query Error: " . $query;

    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}

function QueryGetAllIng()
{
    error_log("Query Ing");

	global $mysqli, $mysqli_response, $mysqli_status; // register the globals here for use.. odd but it works that way    
    global $ajaxData;

    // local output variables
    $outArray = array(); // return dictionary
    $status = "";

   

    $query = "select ID_PK, NAME, QUANTITY ";
    $query.= "from Ingredients ";
    

    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){
            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in
        }
        $status = "Query successful {$NumRows} rows returned";
    }
    else{
        $status = "Query {$query} failed";
    }

    $ajaxData['jsonData'] = $outArray;
    $ajaxData['status'] = $status;
    error_log($status);
}

function QueryDeleteIng( $ID )
{
    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    $ID = $mysqli->real_escape_string(strip_tags($ID));

    $query = "DELETE FROM Ingredients WHERE ID_PK = {$ID}";

    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "FAILED:Query Error: " . $query;

    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}
function QueryDeleteRec( $ID )
{
    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    $ID = $mysqli->real_escape_string(strip_tags($ID));

    $query = "DELETE FROM Recipe WHERE ID_PK = {$ID}";

    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "FAILED:Query Error: " . $query;

    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}

function QueryUnloadIng( $ID , $name, $quantity, $tank)
{
    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    $ID = $mysqli->real_escape_string(strip_tags($ID));


    $query = "UPDATE Ingredients SET QUANTITY = NULL WHERE Ingredients.ID_PK = {$ID}";
    error_log($query);
    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "FAILED:Query Error: " . $query;
    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}
function QueryLoadIng( $ID , $quantity, $tank)
{
    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    $ID = $mysqli->real_escape_string(strip_tags($ID));


    $query = "UPDATE Ingredients SET QUANTITY ={$quantity} WHERE Ingredients.ID_PK = {$ID}";
    error_log($query);
    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "Error: " . $query;
    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}
function QueryGetTanks()
{
    error_log("Query Tanks");

	global $mysqli, $mysqli_response, $mysqli_status; // register the globals here for use.. odd but it works that way    
    global $ajaxData;

    // local output variables
    $outArray = array(); // return dictionary
    $status = "";

   

    $query = "select *";
    $query.= "from Tank ";
    

    if( $result = mysqliQuery($query))
    {
        $NumRows = $result->num_rows;
        while( $row = $result->fetch_assoc()){

            $row["Ing"] = array();
            $queryIng = "select Name, ID_PK from Ingredients";
            if($result2 = mysqliQuery($queryIng)){
                while($row2 = $result2->fetch_assoc()){
                    $row["Ing"][] = $row2;
                }
            }

            $outArray[] = $row; // row will be dictionary of col_name : value, keep adding them in


        }
        $status = "Query successful {$NumRows} rows returned";
    }
    else{
        $status = "Query {$query} failed";
    }
    $ajaxData['jsonData'] = $outArray;
    $ajaxData['status'] = $status;
    error_log($status);
}
function QueryUpdateTank( $tank ,$ing)
{
    global $mysqli, $mysqli_response, $mysqli_status;
    global $ajaxData;

    $query = "UPDATE Tank SET Ingredient_ID = {$ing} WHERE Tank.ID_PK = {$tank}";
    error_log($query);
    if(($result = mysqliNonQuery($query)) < 0)
    {
        $status = "Error: " . $query;
    }
    else{
        $status = "SUCCESS: {$result} rows affected";
    }
    $ajaxData['status'] = $status;
}
// Direct function calls

if( isset( $_GET['action']) && $_GET['action'] == 'getTanks')
{
    QueryGetTanks();
    Done();
}
if( isset( $_GET['action']) && $_GET['action'] == 'getAvailableRecipes')
{
    QueryGetAvailableRecipes();
    Done();
}
if( isset( $_GET['action']) && $_GET['action'] == 'getRecipes')
{
    QueryGetAllRecipes();
    Done();
}
if( isset( $_GET['action']) && $_GET['action'] == 'getAllIng')
{
    QueryGetAllIng();
    Done();
}
if( isset($_POST['action']) && $_POST['action'] == 'addRec'
    && isset($_POST['name']) && strlen($_POST['name']) > 0){
        QueryAddRec($_POST['name'], $_POST['data']);
        Done();
    }
if( isset($_POST['action']) && $_POST['action'] == 'addIng'
    && isset($_POST['Name']) && strlen($_POST['Name']) > 0)
{
    if(isset($_POST["isLoaded"]) && $_POST["isLoaded"] == "true")
    {
        QueryAddIng($_POST['Name'], $_POST['quantity']);
        Done();
    }
    else{
        error_log("not loaded.....");
        QueryAddIng($_POST['Name'], 'NULL');
        Done();
    }
    
}
if(isset($_POST['action']) && $_POST['action'] == "deleteIng"){
    QueryDeleteIng($_POST['ID']);
    Done();
}
if(isset($_POST['action']) && $_POST['action'] == "deleteRec"){
    QueryDeleteRec($_POST['ID']);
    Done();
}
if(isset($_POST['action']) && $_POST['action'] == "unloadIng"){
    QueryUnloadIng($_POST['ID'],$_POST['NAME'],$_POST['QUANTITY'],$_POST['TANK']);
    Done();   
}
if(isset($_POST['action']) && $_POST['action'] == "loadIng"){
    QueryLoadIng($_POST['ID'],$_POST['QUANTITY'],$_POST['TANK']);
    Done();
}
if( isset( $_POST['action']) && $_POST['action'] == 'updateTank')
{
    QueryUpdateTank($_POST['TANKID'], $_POST['IngID']);
    Done();
}
if( isset( $_GET['action']) && $_GET['action'] == 'pourDrink')
{
    PourDrink($_GET['recipe']);
    Done();
}
// repeat for all functions defined above
error_log("Made it !!!");
echo json_encode( $ajaxData );
die();