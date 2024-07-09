<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';


// ***** When body has been stringified, use this
$json = file_get_contents('php://input');
$data = json_decode($json);

if(isset($data->email)) {
    $email = $data->email;
    // Now you can use the $email variable as needed
    ChromePhp::log("User email is" . $email);
    //echo "user email is " . $email;

} else {
    ChromePhp::log("Type your email on the box");
    //echo "No user email found ";
}

// if(isset($_POST['email'])) {
//     $email = $_POST['email'];    
// } 

//search for order status
// Database operations
try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);

    $orderInfo = getOrderInfo($email);

    if ($orderInfo) {
        sendResponse(200, $orderInfo, null);
    } else {
        sendResponse(404, null, "Order not found for email: $email");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function getOrderInfo($email)
{
    global $cm;
    $sql = "SELECT * FROM txn WHERE notes LIKE ?";
    $emailWildcard = '%' . $email . '%';
    $stmt = $cm->getConnection()->prepare($sql);
    $stmt->execute([$emailWildcard]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results ? $results : null;
}

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}


?>