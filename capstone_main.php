<?php
    require_once "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="capstone_main.js"></script>
    <script src="ajax.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="table-wrapper">
    <table class="fl-table">
    <thead>
    <tr>
        <th>
        <button style="width:200px;height:50px;">
        <a href="capstone_ing.php">Ingredient Manager</a>
        </button>
        </th>
        <th>
        <button style="width:200px;height:50px;">
        <a href="capstone_rec.php">Recipe Manager</a>
        </button>
        </th>
        <th>
        <button style="width:200px;height:50px;">
        <a href="capstone_tank.php">Tank Manager</a> 
        </button>
        </th>
        </tr>
    </thead>
    </table>
    </div>
    
        
        <div style="margin:auto;">
            <select style="display:block;width:25%; font-size:larger;margin:auto; text-align:center;" id="lbRecipes" size="10">
            </select>
        </div>
        
        
        <button style="display:block; margin:auto; width:25%; font-size:larger;"id="btnGo"> Pour me a Drink ! </button>
    
    

</body>

</html>