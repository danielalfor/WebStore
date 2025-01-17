<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>

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
                <li>
                    <select id="selectCity" name="city" class="form-select" aria-label="Default select example" >
                        <!-- To be filled by JS -->
                    </select>
                </li>                
            </ul>
        </div>
    </header>

    <content>
        <div class="w-100 p-3 text-center py-4" style="background-color: #ced;">
        <h3>
        Bullseye Report
        <small class="text-muted">Store Inventory</small>
        </h3>
        </div>
        
        <div id="tableOutput">
            <!-- Items list will be here -->
        </div>
        <div id="cardsContainer"></div>
    </content>

    <script>
        window.onload = function (){
            document.querySelector("#selectCity").addEventListener("change", cityChanged);
            
            initStoresDropdown(); //calls function to load select values
        };

        // ***** Populates items for city dropdown *****
        function initStoresDropdown(){
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

        // ***** Build html with info from db *****
        function buildComboBox(text) {
            let arr = JSON.parse(text);
            let html = "";
            for (let i = 0; i < arr.length; i++) {
                let row = arr[i];
                html +=
                    "<option value='" +
                    row.siteID +
                    "'>" +
                    row.name +
                    "</option>";
            }
            let selectElement = document.querySelector("select#selectCity");
            selectElement.innerHTML = html;
        }

        // ***** Updates products view based on city dropdown *****
        function cityChanged(){
            console.log("Selected city changed!");
            let siteID = document.querySelector("#selectCity").value;
            // console.log("siteID is: " + siteID);

            let url = `api/inventoryByStore.php?siteID=${siteID}`;
            let method = "GET";
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                //console.log(xhr.responseText);
                let resp = JSON.parse(xhr.responseText);
                if (resp.data) {
                console.log(resp.data);
                buildTable(resp.data);
                //storeCurrentCity(); //call to add city to session!
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
        function storeCurrentCity() {
            console.log("Selected city changed!");
            let siteID = document.querySelector("#selectCity").value;
            // console.log("siteID is: " + siteID);

            let method = "POST";
            let data = new FormData();
            data.append('siteID', siteID);

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log(xhr.responseText);
                    // You can handle the response here if needed
                }
            };
            xhr.open(method, 'storeSiteID.php', true);
            xhr.send(data);
        }
        // ***** Build a items table into itemsOutput div
        // ***** Build items table into itemsOutput div with weight and items
        function buildTable(text) {
            //hideAllOutput();
            //showPlayersTable();
            console.log("Build table running, data is: ");
            let arr = JSON.parse(text); // get JS Objects
            console.log(arr);
            let html = "<table class='table table-striped'><thead class='thead-dark'>";
            html += "<tr><th scope='col'>ID</th><th scope='col'>Description</th><th scope='col'>SKU</th>";            
            html += "<th scope='col'>Site</th><th scope='col'>Quantity</th>";            
            // html += "<th scope='col'>Emergency Delivery</th><th scope='col'>Notes</th>";
            html += "</tr></thead>";
            html += "<tbody>";

            for (let i = 0; i < arr.length; i++) {
            let row = arr[i];
            html += "<tr>";
            html += "<td>" + row.ID + "</td>";
            html += "<td>" + row.Description + "</td>";
            html += "<td>" + row.SKU + "</td>";
            html += "<td>" + row.Site + "</td>";
            html += "<td>" + row.quantity + "</td>";
            
            html += "</tr>";
            }
            html += "</tbody></table>";
            let theTable = document.querySelector("#tableOutput");
            theTable.innerHTML = html;
        }

    </script>
</body>
</html>