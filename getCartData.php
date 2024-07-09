<?php
require_once __DIR__ . '/utils/ChromePhp.php';
session_start();
ChromePhp::log("getCartData Executing: ");

$response = array(); // Initialize response array

if (isset($_SESSION['cartObjects'])) {
    $cartObjects = $_SESSION['cartObjects'];
    $JSON_array = array(); // Initialize array to store cart data in JSON format

    foreach ($cartObjects as $row) {
        // Log cart item details
        ChromePhp::log("Item ID: " . $row->itemID);
        ChromePhp::log("Name: " . $row->name);
        // Log other properties as needed

        // Push item details to JSON array
        $JSON_array[] = array(
            'itemID' => $row->itemID,
            'name' => $row->name,
            'quantity' => $row->quantity,
            'retailPrice' => $row->retailPrice
            // Include other properties as needed
        );
    }

    // Set success message in response
    $response['success'] = true;
    $response['data'] = $JSON_array;
} else {
    // Set error message in response if cart is empty
    $response['success'] = false;
    $response['message'] = "Cart is empty.";
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
