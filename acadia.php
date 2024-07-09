<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadia portal</title>

    <!-- <script type="module" src="main.js"></script> -->
    <link rel="stylesheet" href="styleSheet.css">
    <script src="https://kit.fontawesome.com/ab0ce74acc.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JavaScript (Optional, for certain components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #tableOutput{
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
    </style>
</head>
<body>
    <header id="header">
        <a href="#"><img src="images/BullseyeLogo.png" class="logo" alt="bullseyelogo"></a>

        <div>
            <ul id="navbar">
                <li><a class="active" href="acadia.php">Home</a></li>
                <li>
                    <select id="selectCity" name="city" class="form-select" aria-label="Default select example" >
                        <!-- To be filled by JS -->
                    </select>
                </li>
                <li><a href="about.php">About</a></li>
                <li><a href="vieworders.php">Orders</a></li>
                <!-- <li><a href="cart.php">Cart</a></li> -->
                <!-- <li><a href="shop.php"><i class="fa-solid fa-cart-shopping"></i></a></li> -->
            </ul>
        </div>
    </header>

    <content>
    <div>
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate">
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate">
        <button id="searchByDate">Search</button>
    </div>
    <div id="tableOutput">
        <!-- Items list will be here -->
    </div>
    </content>

    <script>
        window.onload = function(){
            getAllOrders();
            //modify to get txntype dropdown
            //initStoresDropdown();
            document.getElementById("searchByDate").addEventListener("click", searchByDate);
        };

        // ***** Populates items for city dropdown *****
        function initStoresDropdown()
        {
            console.log("initStoresDropdown executing on js ");
            let url = "bullseye/sites";
            let method = "GET";
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    let resp = JSON.parse(xhr.responseText);
                    //console.log("Received data is" + resp);
                    if (resp.data !== null) {
                        buildComboBox(resp.data);
                    } else {
                        alert(resp.error + " status code: " + xhr.status);
                    }
                }
            };
            xhr.open(method, url, true);
            xhr.send();
        }

        // ***** Gets all items to build table *****
        function getAllOrders() {
            //URL = "bullseye/items"
            let url = "bullseye/txns";
            let method = "GET";
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                console.log(xhr.responseText);
                let resp = JSON.parse(xhr.responseText);
                if (resp.data) {
                    console.log("items retrieved successfully");
                buildTable(resp.data);
                //buildCards(resp.data);
                //setDeleteUpdateButtonState(false);
                } else {
                alert(resp.error + "; status code: " + xhr.status);
                }
            }
            };
            xhr.open(method, url, true);
            xhr.send();
        }
        
        // ***** Build items table into itemsOutput div with weight and items
        function buildTable(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Build table running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            let html = "<table class='table table-striped'><thead class='thead-dark'>";
            html += "<tr><th scope='col'>Order ID</th><th scope='col'>Location</th><th scope='col'>Status</th>";            
            html += "<th scope='col'>Items</th><th scope='col'>Weight in kg</th><th scope='col'>Delivery Date</th>";
            html += "<th scope='col'>Vehicle Size</th>";
            // html += "<th scope='col'>Emergency Delivery</th><th scope='col'>Notes</th>";
            html += "</tr></thead>";
            html += "<tbody>";

            for (let i = 0; i < arr.length; i++) {
            let row = arr[i];
            html += "<tr>";
            html += "<td>" + row.OrderID + "</td>";
            html += "<td>" + row.Location + "</td>";
            html += "<td>" + row.status + "</td>";
            html += "<td>" + row.Items + "</td>";
            html += "<td>" + row.Weight_kg + "</td>";
            html += "<td>" + row.DeliveryDate + "</td>";
            html += "<td>" + row.Vehicle_Size + "</td>";

            // html += "<td>" + row.barCode + "</td>";
            // html += "<td>" + row.createdDate + "</td>";
            // html += "<td>" + row.deliveryID + "</td>";
            // html += "<td>" + row.emergencyDelivery + "</td>";
            // html += "<td>" + row.notes + "</td>";
            ////html += "<td>" + (row.vegetarian === 1 ? "Yes" : "No") + "</td>";
            ////Add button to buy
            // html += "<td><form action='addtoCart.php' method='post'>";
            // html += "<input type='hidden' name='itemID' value='" + row.itemID + "'>";
            // html += "<button type='submit' name='addItem'>";
            //html += "<i class='fa-solid fa-bag-shopping' style='color: #74C0FC;'></i></button></form></td>"
            html += "</tr>";
            }
            html += "</tbody></table>";
            let theTable = document.querySelector("#tableOutput");
            theTable.innerHTML = html;
        }

        // ***** Build items table into itemsOutput div select * from txn
        function buildTable0(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Build table running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            let html = "<table class='table table-striped'><thead class='thead-dark'>";
            html += "<tr><th scope='col'>Txn ID</th><th scope='col'>To Site</th><th scope='col'>From Site</th>";            
            html += "<th scope='col'>Status</th><th scope='col'>ShipDate</th><th scope='col'>Txn Type</th>";
            html += "<th scope='col'>Barcode</th><th scope='col'>Created Date</th><th scope='col'>DeliveryID</th>";
            html += "<th scope='col'>Emergency Delivery</th><th scope='col'>Notes</th></tr></thead>";
            html += "<tbody>";

            for (let i = 0; i < arr.length; i++) {
            let row = arr[i];
            html += "<tr>";
            html += "<td>" + row.txnID + "</td>";
            html += "<td>" + row.siteIDTo + "</td>";
            html += "<td>" + row.siteIDFrom + "</td>";
            html += "<td>" + row.status + "</td>";
            html += "<td>" + row.shipDate + "</td>";
            html += "<td>" + row.txnType + "</td>";
            html += "<td>" + row.barCode + "</td>";
            html += "<td>" + row.createdDate + "</td>";
            html += "<td>" + row.deliveryID + "</td>";
            html += "<td>" + row.emergencyDelivery + "</td>";
            html += "<td>" + row.notes + "</td>";
            ////html += "<td>" + (row.vegetarian === 1 ? "Yes" : "No") + "</td>";
            ////Add button to buy
            // html += "<td><form action='addtoCart.php' method='post'>";
            // html += "<input type='hidden' name='itemID' value='" + row.itemID + "'>";
            // html += "<button type='submit' name='addItem'>";
            //html += "<i class='fa-solid fa-bag-shopping' style='color: #74C0FC;'></i></button></form></td>"
            html += "</tr>";
            }
            html += "</tbody></table>";
            let theTable = document.querySelector("#tableOutput");
            theTable.innerHTML = html;
        }

        function searchByDate() {
        let startDate = document.getElementById("startDate").value;
        let endDate = document.getElementById("endDate").value;
        // Call a service to retrieve data based on the selected date range
        let url = `api/txnsByDate.php?startDate=${startDate}&endDate=${endDate}`;
        let method = "GET";
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                let resp = JSON.parse(xhr.responseText);
                if (resp.data) {
                    console.log("Data retrieved successfully");
                    buildTable0(resp.data);
                } else {
                    alert(resp.error + "; status code: " + xhr.status);
                }
            }
        };
        xhr.open(method, url, true);
        xhr.send();
        }


    </script>
</body>
</html>