<?php
session_start();

if(isset($_POST['siteID'])) {
    $_SESSION['siteID'] = $_POST['siteID'];
    echo "Site ID stored in session: ";
    echo $_SESSION['siteID'];
} else {
    echo "Site ID not provided";
}
?>