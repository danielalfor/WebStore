<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

// ***** Provide info for delivery (orders to pick up) ******
$method = $_SERVER['REQUEST_METHOD'];

try {
    ChromePhp::log("deliveryByDate Service executing");

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
    global $cm;
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

function getTxnsByDate($startDate, $endDate)
{
    global $cm;
    $sql = "SELECT * FROM `txn` WHERE `shipDate` BETWEEN :startDate AND :endDate";
    $stmt = $cm->getConnection()->prepare($sql);
    $stmt->bindValue(':startDate', $startDate);
    $stmt->bindValue(':endDate', $endDate);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// ***** using field $notes, get the txnID to call insertOrderItems *****
function getAllItems($startDate, $endDate){
    global $cm; // Access the ConnectionManager object
    //$sql = "select * from `txn`";
    $sql = "select txn.txnID as OrderID, (select site.name from site where siteID = siteIDFrom) as 'SiteFrom', 
    (select site.name from site where siteID = siteIDTo) as 'SiteTo', `status`, sum(quantity) as Items, 
    SUM(quantity * item.weight) AS Weight_kg, 
    shipDate as DeliveryDate, (SELECT vehicleType FROM vehicle 
    WHERE maxWeight > SUM(weight) * 300 
    order by maxweight LIMIT 1) AS Vehicle_Size 
    FROM txn 
    INNER JOIN `site` on txn.siteIDTo = site.siteID 
    INNER JOIN `txnitems` on txn.txnID = txnitems.txnID 
    INNER JOIN `item` ON txnitems.itemID = item.itemID 
    WHERE status in ('processing', 'assembled', 'assembling', 'in transit') 
    and `shipDate` BETWEEN :startDate AND :endDate 
    GROUP BY txn.txnID, site.name, `status`, shipDate 
    ORDER BY `status`";

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
