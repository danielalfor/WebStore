<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Order</title>

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
                <li><a class="active" href="reports.php">Reports</a></li>
                <!-- <li> -->
                    <!-- <select id="selectCity" name="city" class="form-select" aria-label="Default select example" > -->
                        <!-- To be filled by JS -->
                    <!-- </select> -->
                <!-- </li> -->
                                
            </ul>
        </div>
    </header>

    <content>
    <div class="bg-info">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate">
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate">
        <button id="searchByDate">Search</button>
    </div>
    
    <div class="header text-center py-4 bg-info">
        <h1 class="text-white">Bullseye Emergency Order</h1>
    </div>
    <div id="tableOutput">
        <!-- Items list will be here -->
    </div>
    </content>

    <script>
        window.onload = function(){
            //getAllOrders();            
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

        // ***** Search the latest Store Order ******
        function searchByDate() {
            let startDate = document.getElementById("startDate").value;
            let endDate = document.getElementById("endDate").value;
            // Call a service to retrieve data based on the selected date range
            let url = `api/emergencyOrderByDate.php?startDate=${startDate}&endDate=${endDate}`;
            let method = "GET";
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    let resp = JSON.parse(xhr.responseText);
                    if (resp.data) {
                        console.log("Data retrieved successfully");
                        //buildTable0(resp.data);
                        buildStoreOrderCard(resp.data);

                        let arr = JSON.parse(resp.data);
                        txnID = arr[0].OrderID;
                        console.log("TXN id is: " + txnID);
                        populateItems(txnID);
                    } else {
                        alert(resp.error + "; status code: " + xhr.status);
                    }
                }
            };
            xhr.open(method, url, true);
            xhr.send();
        }

        // ***** Build items table into itemsOutput div with weight and items
        function buildStoreOrderCard1(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Building StoreOrderCard running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            let html = "<table class='table table-striped'><thead class='thead-dark'>";
            html += "<tr><th scope='col'>Order ID</th><th scope='col'>Date</th><th scope='col'>Status</th>";            
            html += "<th scope='col'>Pallets</th><th scope='col'>Weight</th><th scope='col'>Vehicle Size</th>";
            
            html += "</tr></thead>";
            html += "<tbody>";

            for (let i = 0; i < arr.length; i++) {
            let row = arr[i];
            html += "<tr>";
            html += "<td>" + row.OrderID + "</td>";
            html += "<td>" + row.Date + "</td>";
            html += "<td>" + row.Status + "</td>";
            html += "<td>" + row.Pallets + "</td>";
            html += "<td>" + row.Weight + "</td>";
            html += "<td>" + row.Vehicle_Size + "</td>";            
            html += "</tr>";
            }
            html += "</tbody></table>";
            let theTable = document.querySelector("#tableOutput");
            theTable.innerHTML = html;
        }

        function buildStoreOrderCard(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Building StoreOrderCard running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            
            let itemsOutput = document.getElementById("tableOutput");
            itemsOutput.innerHTML = ""; // Clear previous content
            
            for (let i = 0; i < arr.length; i++) {
                let row = arr[i];
                let orderCard = document.createElement("div");
                orderCard.classList.add("card", "ml-1", "mr-1");
                orderCard.style.width = "calc(100% - 2rem)"; // Adjust the width as needed
                
                let cardBody = document.createElement("div");
                cardBody.classList.add("card-body");
                
                let orderID = document.createElement("h5");
                orderID.classList.add("card-title");
                orderID.textContent = "Order ID: " + row.OrderID;
                
                let orderDetails = document.createElement("p");
                orderDetails.classList.add("card-text");
                orderDetails.textContent = "Date: " + row.Date + ", Status: " + row.Status + ", Pallets: " + row.Pallets + ", Weight: " + row.Weight + ", Vehicle Size: " + row.Vehicle_Size;
                
                cardBody.appendChild(orderID);
                cardBody.appendChild(orderDetails);
                orderCard.appendChild(cardBody);
                itemsOutput.appendChild(orderCard);
            }
        }

        // ***** Populate txnItems for the requested Order ******
        // Calls buildItemsTable after receiving data
        function populateItems(orderID) {        
            console.log("Sending orderID = " + orderID);            
            // Call a service to retrieve data based on the selected date range
            let url = `api/populateOrderItems.php?orderID=${orderID}`;
            let method = "GET";
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    let resp = JSON.parse(xhr.responseText);
                    if (resp.data) {
                        console.log("Data retrieved successfully");
                        //buildTable0(resp.data);
                        buildItemsTable(resp.data);
                    } else {
                        alert(resp.error + "; status code: " + xhr.status);
                    }
                }
            };
            xhr.open(method, url, true);
            xhr.send();
        }
        // ***** Builds a table with txnItems 
        function buildItemsTable(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Build table running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            let html = "<table class='table table-striped'><thead class='thead-dark'>";
            html += "<tr><th scope='col'>Txn ID</th><th scope='col'>ItemID</th><th scope='col'>Quantity</th>";            
            html += "<th scope='col'>Notes</th>";
            // html += "<th scope='col'>Emergency Delivery</th><th scope='col'>Notes</th>";
            html += "</tr></thead>";
            html += "<tbody>";

            for (let i = 0; i < arr.length; i++) {
            let row = arr[i];
            html += "<tr>";
            html += "<td>" + row.txnID + "</td>";
            html += "<td>" + row.ItemID + "</td>";
            html += "<td>" + row.quantity + "</td>";
            html += "<td>" + row.notes + "</td>";
            html += "</tr>";
            }
            html += "</tbody></table>";
            let theTable = document.querySelector("#tableOutput");
            theTable.innerHTML += html;

            let htmlFooter = "<div class='row border-top mt-3 pt-3'>";

            // Add a div for notes
            htmlFooter += "<div class='col-12 border-bottom pb-5'><div class='notes'>Notes</div></div>";

            // Add two divs for Received Signature and date
            htmlFooter += "<div class='col-6 border pb-5'><div class='received-signature'>Received Signature:</div></div>";
            htmlFooter += "<div class='col-6 border pb-5'><div class='received-date'>Date:</div></div>";

            htmlFooter += "</div>"; // Closing the row div
            theTable.innerHTML += htmlFooter;
        }

    </script>
</body>
</html>