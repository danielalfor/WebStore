<?php
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';

// echo "<h1>Lets process credentials</h1>";

if (isset($_POST['username'])){
    //echo $_POST['username'];
    $username = $_POST['username'];
    ChromePhp::log("Username is " . $username);
}
if (isset($_POST['password'])){
    //echo $_POST['password'];
    $password = $_POST['password'];
    ChromePhp::log("Password is " . $password);
}

// Hash the password using SHA256 algorithm
$hashedPassword = hash('sha256', $password);

// Alternatively, you can use password_hash() function which includes salt and is more secure
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password
// echo "Username is: " . $username;
// echo "Password is: " . $password;
// echo "Hashed Password: " . $hashedPassword;

//Database validation
try{
    ChromePhp::log("Validating password...");
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, CONSTANTS::$MYSQL_PASSWORD);

    $success = validateCredentials($username, $hashedPassword);
    
    if ($success) {
        header('Location: ../acadia.php');
        exit();
        //sendResponse(200, "Validation Successful.", null);
    } else {
        sendResponse(500, null, "Failed to validate credentials.");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)){
        $cm->closeConnection();
    }
}

function validateCredentials($username, $inPassword){
    global $cm; // Access the ConnectionManager object
    $sql = "select password FROM `employee` WHERE username = :username";
    $stmt = $cm->getConnection()->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    ChromePhp::log("Fetching results...");
    // Check if a row was returned
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $hashedPasswordFromDB = $result['password'];
        
        ChromePhp::log("user pass is: " . $hashedPasswordFromDB);
        ChromePhp::log("pass from db: " . $hashedPasswordFromDB);

        // Use password_verify() to compare hashed password with input password
        if (trim($inPassword) === trim($hashedPasswordFromDB)) {
            // Passwords match, validation successful
            ChromePhp::log("Validation successful");
            return true;
        } else {
            // Passwords don't match
            ChromePhp::log("failed");
            return false;
        }
    } else {
        // No user found with the given username
        return false;
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