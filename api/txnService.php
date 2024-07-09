<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try{
    ChromePhp::log("txn Service executing");

    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);

    if ($method === "GET"){
        //ChromePhp::log("Lets do a Get");
        doGet();
    }
    else
    {
        sendResponse(405, null, "Method not allowed.");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)){
        $cm->closeConnection();
    }
}

function doGet()
{
    global $cm;
    //ChromePhp::log("Do a Get executing");
    if (isset($_GET['txnid'])) {
        ChromePhp::log("Do Get For One Item :(");
        sendResponse(405, null, "individual GETs not allowed");
    }
    else
    {
        //ChromePhp::log("Do Get For all items");
        try
        {
            $results = getAllItems();
            if (count($results) > 0){
                $results = json_encode($results, JSON_NUMERIC_CHECK);
                ChromePhp::log($results);
                sendResponse(200, $results, null);
            } else {
                ChromePhp::log("Sending a null 400");
                sendResponse(400, null, "Could not retrieve items");
            }
        } catch (Exception $e) {
            sendResponse(500, null, "ERROR " . $e->getMessage());
        }
    }
}

// ***** using field $notes, get the txnID to call insertOrderItems *****
function getAllItems(){
    global $cm; // Access the ConnectionManager object
    //$sql = "select * from `txn`";
    $sql = "select txn.txnID as OrderID, site.name as Location, `status`, sum(quantity) as Items, 
    SUM(quantity * item.weight) AS Weight_kg, 
    shipDate as DeliveryDate, (SELECT vehicleType FROM vehicle 
    WHERE maxWeight > SUM(weight) * 300 
    order by maxweight LIMIT 1) AS Vehicle_Size 
    FROM txn 
    INNER JOIN `site` on txn.siteIDTo = site.siteID 
    INNER JOIN `txnitems` on txn.txnID = txnitems.txnID 
    INNER JOIN `item` ON txnitems.itemID = item.itemID 
    WHERE status in ('processing', 'new', 'complete', 'assembled', 'assembling', 'in transit') 
    GROUP BY txn.txnID, site.name, `status`, shipDate 
    ORDER BY `status`";

    //and shipDate BETWEEN '2018-01-19' AND '2024-01-19' 

    $stmt = $cm->getConnection()->prepare($sql);
    //$stmt->bindValue(':notes',$inNotes);
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
