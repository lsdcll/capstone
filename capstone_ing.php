<?php
require_once "functions.php";
?>
<!DOCTYPE html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="capstone_ing.js"></script>
    <script src="ajax.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="table-wrapper">
        <table class="fl-table">
        <thead>
        <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="table-body">
            

        </tbody>
        </table>
    </div>
    <div class="table-wrapper">
    
        <table class="fl-table">
            <thead>
            <th>
            Create New Ingredient!
            </th>
            </thead>
            <tbody>
            <tr>
            <td>
            <input id="name" type="text" name="name" placeholder="Ingredient Name">
            </td>
            </tr>
            <tr>
            <td>
            <label>Is the Ingredient Loaded? : </label> 
            <input type="checkbox" name="isLoaded" id="isLoaded">
            </td>
            </tr>
            <tr>
            <td>
            <input id="quantity" type="text" name="quantity" placeholder="Quantity in ml" disabled>
            </td>
            
            </tr>
            <tr>
            <td>    
            <input id="submit" type="submit" name="submit" value="Add Ingredient!">
            </td>
            
            </tr>
            </tbody>
        </table>
    </div>
    
    <div>
    <label id="pageStatus"></label>
    </div>

</body>