<?php
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';

session_start();

// ***** Save cartObject to array $cartObjects *****
if (isset($_SESSION['cartObjects']))
{
    $cartObjects = $_SESSION['cartObjects'];
    //// ***** test if cart objects are ready ****
    //foreach ($cartObjects as $row){
        //echo "Item ID: " . $row->itemID . "<br>";
        //echo "Name: " . $row->name . "<br>";
        // Echo other properties as needed
        //echo "<br>";    
    //}
}
else echo "Your cart is empty.";

// ***** Check if required POST variables are set ****
if (isset($_POST['name'], $_POST['email'], $_POST['phone'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
} else {
    sendResponse(400, null, "Missing required parameters.");
    exit;
}

// Check if siteID is set in session
if (isset($_SESSION['siteID'])) {
    $siteID = $_SESSION['siteID'];
} else {
    sendResponse(400, null, "siteID not set in session.");
    exit;
}

// Database operations
try{
    //ChromePhp::log("Item Service executing...");
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);

    $success = createOnlineOrder($name, $email, $phone, $siteID);
    
    if ($success) {
        sendResponse(200, "Order created successfully.", null);
    } else {
        sendResponse(500, null, "Failed to create order.");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)){
        $cm->closeConnection();
    }
}

function createOnlineOrder($name, $email, $phone, $siteID)
{
    global $cm; // Access the ConnectionManager object

    $inNotes = $name . "," . $phone . "," . $email;
    $insertStatement = "insert INTO `txn` VALUES (null, :siteIDTo, 1, 'New', sysdate(), 'Online Order', '100011100011', sysdate(), null, null, :notes)";

    $stmt = $cm->getConnection()->prepare($insertStatement);
    $stmt->bindValue(':siteIDTo', $siteID);
    $stmt->bindValue(':notes', $inNotes);
    $success = $stmt->execute();

    if ($success) {
        // Retrieve the ID of the last inserted row (this block didnt work)
        //$lastInsertedID = $cm->getConnection()->lastInsertId();
        // Fetch the inserted row if needed
        // $insertedRow = fetchInsertedRow($lastInsertedID);

        $txnID = getTxnID($inNotes); //get the txnID from created order
        ChromePhp::log("Online order created, Number" . $txnID);
        ////test if online order number was generated correctly
        //echo "ONline order number is ";
        //echo $txnID;
        insertOrderItems($txnID); //insert items associated to order
        //clear cart
        $_SESSION['cartObjects'] = null;
        //forward control to orderCreated.php
        header('Location: ../orderCreated.php');
        exit();
        return $success;
    } else {
        return false;
    }
}

// ***** using field $notes, get the txnID to call insertOrderItems *****
function getTxnID($inNotes){
    global $cm; // Access the ConnectionManager object
    $sql = "select txnID from `txn` where notes = :notes order by abs(timestampdiff(second, createdDate, now())) limit 1";
    $stmt = $cm->getConnection()->prepare($sql);
    $stmt->bindValue(':notes',$inNotes);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); //get results as an associative array
    if($result) {
        return $result['txnID']; //return txnID
    } else {
        return null;
    }
}

// ***** insert order items from $cartObjects to txnitems *****
function insertOrderItems($txnID){
    
    if (isset($_SESSION['cartObjects']))
    {
    $cartObjects = $_SESSION['cartObjects'];
    
    foreach ($cartObjects as $row){
        //echo "Item ID: " . $row->itemID . "<br>";
        //echo "Name: " . $row->name . "<br>";
        // Echo other properties as needed
        //echo "<br>";    
        global $cm;
        $sql = "insert INTO `txnitems` VALUES (:txnID, :itemID, 1, null)";
        $stmt = $cm->getConnection()->prepare($sql);
        $stmt->bindValue(':txnID',$txnID);
        $stmt->bindValue(':itemID',$row->itemID);

        $stmt->execute();

    }
}

}
function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    //echo json_encode($resp, JSON_NUMERIC_CHECK);
}
?>