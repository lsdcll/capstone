<?php
require_once "functions.php";
?>
<!DOCTYPE html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="capstone_rec.js"></script>
    <script src="ajax.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                <th>Name</th>
                <th>Ingredients</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
    <div class="table-wrapper">
    
        <table class="fl-table">
        <thead>
        <th>Create A New Recipe!</th>
        </thead>
        <tbody>
        <tr>
        <td>
        <input id="name" type="text" name="name" placeholder="Recipe Name">
        </td>
        </tr>
        <tr>
        <td>
        <select id="i1">
            <option value="0">None</option>
        </select>
        <input id="q1" type="text" placeholder="quantity in ml">
        </td>
        </tr>
        <tr>
        <td>
        <select id="i2">
        <option value="0" selected="true">None</option></select>
        <input id="q2" type="text" placeholder="quantity in ml">
        </td>
        </tr>
        <tr>
        <td>
        <select id="i3">
        <option value="0" selected="true">None</option></select>
        <input id="q3" type="text" placeholder="quantity in ml">
        </td>
        </tr>
        <tr>
        <td>
        <select id="i4">
        <option value="0" selected="true">None</option></select>
        <input id="q4" type="text" placeholder="quantity in ml">
        </td>
        </tr>
        <tr>
        <td>
        <input id="submit" type="submit" name="submit" value="Create Recipe!">
        </td>
        </tr>

        </tbody>
        </table>
    </div>



</body>