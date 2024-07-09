<?php
require_once __DIR__ . '/utils/ChromePhp.php';
session_start();

// Initialize response array
$response = array();

ChromePhp::log("deleteItemFromCart Executing: ");

// Retrieve itemID to delete
$itemID = null;
$json = file_get_contents('php://input');
$data = json_decode($json);

// Check if data is received
if ($data) {
    $itemID = $data->itemId;
    ChromePhp::log("Item received: ");
    ChromePhp::log($itemID);

    // Retrieve cartObjects array from session
    if (isset($_SESSION['cartObjects'])) {
        $cartObjects = $_SESSION['cartObjects'];

        // Iterate over the cartObjects array to find the row with matching itemID
        foreach ($cartObjects as $key => $row) {
            if ($row->itemID == $itemID) {
                // Remove the row from the cartObjects array
                unset($cartObjects[$key]);
                // Update the session variable with the modified cartObjects array
                $_SESSION['cartObjects'] = $cartObjects;
                // Log the updated cartObjects array
                ChromePhp::log("Updated cartObjects array:");
                ChromePhp::log($cartObjects);
                // Set success message in response
                $response['success'] = true;
                $response['message'] = "Item deleted successfully.";
                break; // Exit the loop after removing the row
            }
        }
    } else {
        // Set error message in response
        $response['success'] = false;
        $response['message'] = "No items found in the cart.";
    }
} else {
    // Set error message in response
    $response['success'] = false;
    $response['message'] = "No item received.";
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>