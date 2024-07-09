<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

// ***** Provide info for delivery (orders to pick up) ******
$method = $_SERVER['REQUEST_METHOD'];

try {
    ChromePhp::log("employee Roles Service executing");

    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);

    if ($method === "GET") {
        doGet();
    } else {
        sendResponse(405, null, "Method not allowed.");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function doGet()
{
    //global $cm;
            
        try {
            //$results = getTxnsByDate($startDate, $endDate);
            $results = getAllItems();
            if (count($results) > 0) {
                $results = json_encode($results, JSON_NUMERIC_CHECK);
                sendResponse(200, $results, null);
            } else {
                sendResponse(404, null, "No roles found");
            }
        } catch (Exception $e) {
            sendResponse(500, null, "ERROR " . $e->getMessage());
        }
    
}

// ***** get items based on query *****
function getAllItems(){
    global $cm; // Access the ConnectionManager object
    
    $sql = "select * from posn";

    $stmt = $cm->getConnection()->prepare($sql);
    
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); //get results as an associative array
    if($result) {
        return $result; //return txnID
    } else {
        return null;
    }
}

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
?>
