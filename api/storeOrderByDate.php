<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

// ***** Provide info for delivery (orders to pick up) ******
$method = $_SERVER['REQUEST_METHOD'];

try {
    ChromePhp::log("storeOrderByDate Service executing");

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
    if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        try {
            //$results = getTxnsByDate($startDate, $endDate);
            $results = getAllItems($startDate, $endDate);
            if (count($results) > 0) {
                $results = json_encode($results, JSON_NUMERIC_CHECK);
                sendResponse(200, $results, null);
            } else {
                sendResponse(404, null, "No transactions found for the specified date range");
            }
        } catch (Exception $e) {
            sendResponse(500, null, "ERROR " . $e->getMessage());
        }
    } else {
        sendResponse(400, null, "Missing startDate or endDate parameters");
    }
}

// ***** get items based on query *****
function getAllItems($startDate, $endDate){
    global $cm; // Access the ConnectionManager object
    //$sql = "select * from `txn`";
    $sql = "select txn.txnID AS OrderID, date(txn.createdDate) AS Date, txn.status AS Status, CEILING(SUM(weight) / 20) AS Pallets, 
    SUM(weight) AS Weight, 
    (SELECT vehicleType FROM vehicle 
    WHERE maxWeight > SUM(weight) * 300 
    order by maxweight LIMIT 1) AS Vehicle_Size 
    FROM txn 
    INNER JOIN txnitems ON txn.txnID = txnitems.txnID 
    INNER JOIN item ON txnitems.itemID = item.itemID 
    WHERE txn.txntype = 'Store Order' and txn.siteIDTo = 5 
    GROUP BY txn.txnID, txn.status
    limit 1";

    //and shipDate BETWEEN '2018-01-19' AND '2024-01-19' 

    $stmt = $cm->getConnection()->prepare($sql);
    //$stmt->bindValue(':notes',$inNotes);
    $stmt->bindValue(':startDate', $startDate);
    $stmt->bindValue(':endDate', $endDate);
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
