<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>

    <script type="module" src="reports.js"></script>
    <link rel="stylesheet" href="styleSheet.css">
    <script src="https://kit.fontawesome.com/ab0ce74acc.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JavaScript (Optional, for certain components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header id="header">
        <a href="#"><img src="images/BullseyeLogo.png" class="logo" alt="bullseyelogo"></a>

        <div>
            <ul id="navbar">
                <li><a class="active" href="dashboard.php">Home</a></li>
                <li>
                    <select id="selectCity" name="city" class="form-select" aria-label="Default select example" >
                        <!-- To be filled by JS -->
                    </select>
                </li>
                <li><a href="delivery.php">Delivery Report</a></li>
                <li><a href="storeOrder.php">Store Orders</a></li>
                <!-- <li><a href="shipping.php">Shipping Receipt</a></li> -->
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="emergencyOrder.php">Emergency Orders</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="backorders.php">Back Orders</a></li>
                <li><a href="supplierOrders.php">Supplier Orders</a></li>
                <!-- <li><a href="shop.php"><i class="fa-solid fa-cart-shopping"></i></a></li> -->
            </ul>
        </div>
    </header>

    <content>
        <div id="itemsOutput">
            <!-- Items list will be here -->
        </div>
        <div id="cardsContainer"></div>
    </content>
</body>
</html>