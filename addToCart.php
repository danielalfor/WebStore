<?php
require_once __DIR__ . '/utils/ChromePhp.php';
session_start();

// Dump the session variables
//var_dump($_SESSION);

ChromePhp::log("addToCart Executing: ");

//Retrieve itemIDs from user selection
// if (isset($_POST['itemID'])){
//     $itemID = $_POST['itemID'];

//     $_SESSION['cart'][] = $itemID; //Add item to cart array

//     ChromePhp::log("Item received: ");
//     ChromePhp::log($itemID);
// }
// else
// {
//     ChromePhp::log("NO Item received: ");
// }

// ***** Store object on array CartObjects
if (isset($_POST['row'])){
    //retrieve JSON string
    $rowJSON = $_POST['row'];
    // Parse the JSON string back to an object
    $row = json_decode($rowJSON);
    $_SESSION['cartObjects'][] = $row;

    ChromePhp::log("Row received: ");
    ChromePhp::log($row);
}

//Redirect to previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>