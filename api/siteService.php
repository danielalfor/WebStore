<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/SiteAccessor.php'; //complete
require_once dirname(__DIR__, 1) . '/entity/Site.php'; //complete
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try{
    ChromePhp::log("Site Service executing");

    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);
    $ita = new SiteAccessor($cm->getConnection());

    if ($method === "GET"){
        //ChromePhp::log("Lets do a Get");
        doGet($ita);
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

function doGet($ita)
{
    //ChromePhp::log("Do a Get executing");
    if (isset($_GET['itemid'])) {
        ChromePhp::log("Do Get For One Item :(");
        sendResponse(405, null, "individual GETs not allowed");
    }
    else
    {
        //ChromePhp::log("Do Get For all items");
        try
        {
            $results = $ita->getAllItems();
            if (count($results) > 0){
                $results = json_encode($results, JSON_NUMERIC_CHECK);
                //ChromePhp::log($results);
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

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
