<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order Status</title>

    <script type="module" src="main.js"></script>
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
                <li><a href="about.php">About</a></li>
                <li><a href="vieworders.php">Orders</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="shop.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
            </ul>
        </div>
    </header>

    <content>
    <div class="card" style="width: 45rem;">
        <div class="card-body">
            <h5 class="card-title">Search Your Order</h5>
            <h6 class="card-subtitle mb-2 text-muted">Type your email and press the search button to find your order.</h6>
            <div class="input-group mb-3">
                <form id="searchOrderForm">
                    <input type="email" id="email" name="email" required class="form-control" placeholder="Enter your email">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div id="orderResult"></div>
        </div>
    </div>
    </content>

    <script>
    document.getElementById("searchOrderForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission
    let email = document.getElementById("email").value;
    console.log("email is: " + email);

    fetch('api/searchOrder.php', {
        method: 'POST',
        body: JSON.stringify({email: email}),
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Bienvenidos a la fiesta de la alegria");
        console.log(data); // Log the response from the server
        // Display the response in your output div id="orderResult"
        displayInfo(data);
    })
    .catch(error => {
        console.log("Oh no, pilas brother");
        console.error('Error:', error);
    });
});

function displayInfo(data) {
    const orderResultDiv = document.getElementById("orderResult");
    orderResultDiv.innerHTML = ""; // Clear any existing content

    // Parse the JSON data
    const jsonData = JSON.parse(data);
    //const jsonData = data;

    // Create a table element
    const table = document.createElement("table");
    table.classList.add("table");

    // Create table header
    const headerRow = document.createElement("tr");
    const headers = ["Txn ID", "Status", "Ship Date"];
    headers.forEach(headerText => {
        const header = document.createElement("th");
        header.textContent = headerText;
        headerRow.appendChild(header);
    });
    table.appendChild(headerRow);

    // Create table body
    const tbody = document.createElement("tbody");
    jsonData.data.forEach(order => {
        const row = document.createElement("tr");
        const txnIDCell = document.createElement("td");
        txnIDCell.textContent = order.txnID;
        row.appendChild(txnIDCell);

        const statusCell = document.createElement("td");
        statusCell.textContent = order.status;
        row.appendChild(statusCell);

        const shipDateCell = document.createElement("td");
        shipDateCell.textContent = order.shipDate;
        row.appendChild(shipDateCell);

        tbody.appendChild(row);
    });
    table.appendChild(tbody);

    // Append the table to the orderResultDiv
    orderResultDiv.appendChild(table);
}

    </script>
</body>
</html>