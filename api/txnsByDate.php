<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    ChromePhp::log("txnsByDate Service executing");

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
            $results = getTxnsByDate($startDate, $endDate);
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

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
?>
