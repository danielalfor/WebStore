<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bullseye Cart</title>

    <script type="module" src="main.js"></script>
    <link rel="stylesheet" href="styleSheet.css">
    <script src="https://kit.fontawesome.com/ab0ce74acc.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JavaScript (Optional, for certain components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<?php
require_once __DIR__ . '/utils/ChromePhp.php';
//echo "\n Shopping cart \n";
session_start();

// save cartObject to array $cartObjects
if (isset($_SESSION['cartObjects']))
{
    $cartObjects = $_SESSION['cartObjects'];
    // foreach ($cartObjects as $row){
    //     echo "Item ID: " . $row->itemID . "<br>";
    //     echo "Name: " . $row->name . "<br>";
    //     // Echo other properties as needed
    //     echo "<br>";    
    // }
}
else echo "Your cart is empty.";
?>

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
        <div id="cartOutput">
            <h3>Items on Cart</h3>
            <!-- Items list will be here -->               
        </div>

        <!-- Bootstrap Cart Summary -->
        <div class="card" style="width: 45rem;">
    <img src="..." class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">Items on Cart</h5>
        <p class="card-text">Here are the items on your cart:</p>
        <table>
            <thead>
                <th>Item</th>
                <th>Description</th>
                <th>Qty Ordered</th>
                <th>Qty Available</th>
                <th>Price</th>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                if(count($cartObjects) > 0): 
                    foreach ($cartObjects as $row): 
                        $subtotal += $row->retailPrice;
                ?>
                    <tr>
                        <td><?php echo $row->itemID; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td>1</td>
                        <td><?php echo $row->quantity; ?></td>
                        <td><?php echo $row->retailPrice; ?></td>
                        <td><button type="button" class="btn btn-info add-item" data-itemid="<?php echo $row->itemID; ?>">Add another</button></td>
                        <td><button type="button" class="btn btn-secondary delete-item" data-itemid="<?php echo $row->itemID; ?>">Delete Item</button></td>
                    <tr>
                <?php endforeach; ?>
                <tr><td></td><td>Subtotal:</td><td></td><td></td><td><?php echo $subtotal; ?></td></tr>
                <tr><td></td><td>Total:</td><td></td><td></td><td><?php echo $subtotal * 1.15; ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- Bootstrap Payment Options -->
<div class="card" style="width: 45rem;">
    <img src="..." class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">Payment Info</h5>
        <p class="card-text">Customer Information:</p>
                
        <!-- Customer Information Form -->
        <form id="customerInfoForm" method="post" action="api/createOrder.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary" id="payAndOrder">Pay and Order</button>
        </form>
    </div>
</div>
    </content>

    <!-- JavaScript code -->

<script>
    // Add event listeners to buttons
    document.querySelectorAll('.add-item').forEach(button => {
        button.addEventListener('click', function() {
            // Get item ID from data attribute
            const itemId = this.dataset.itemid;
            // Call a function to add item to cart based on itemId
            addItemToCart(itemId);
        });
    });

    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            // Get item ID from data attribute
            const itemId = this.dataset.itemid;
            // Call a function to delete item from cart based on itemId
            deleteItemFromCart(itemId);
        });
    });

    // Event listener for submit
    //document.querySelector("#payAndOrder").addEventListener('click',createOrder);

    function createOrder()
    {
    console.log("lets create a new Order");

    // Construct order data
    const orderData = {
        // Include order details here
    };

    // Make HTTP request to create order
    fetch('api/createOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to create order');
        }
        // Order created successfully
        return response.json();
    })
    .then(data => {
        // Handle successful response
        console.log('Order created successfully:', data);
        // Add items to order or perform other actions
    })
    .catch(error => {
        // Handle errors
        console.error('Error creating order:', error);
        // Display error message to user
    });
        //alert("Order was created!");
    }
    // Function to add item to cart
    function addItemToCart(itemId) {
        // Implement logic to add item to cart
        console.log("Lets add item: " + itemId);
        console.log("Not implemented yet: ");
    }

    // Function to delete item from cart
function deleteItemFromCart(itemId) {
    // Implement logic to delete item from cart
    console.log("Lets delete item: " + itemId);

    // Updating session using fetch API
    fetch('deleteItemFromCart.php', {
        method: 'POST',
        body: JSON.stringify({itemId: itemId}),
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Update cart table after successful deletion
        console.log("time to call updateCartTable");
        //header('Location: ' . $_SERVER['REQUEST_URI']); //reload or redirect to same page
        //header("Location: cart.php");
        //exit();
        updateCartTable();
    })
    .catch(error => {
        console.error('Error deleting item:', error);
    });
}

// Function to update the cart table
function updateCartTable() {
    console.error('Update Cart table executing... ');
    // Fetch updated cart data
    fetch('getCartData.php') // You need to create a PHP file to fetch updated cart data
    .then(response => response.json())
    .then(cartData => {
        // Clear existing table rows
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        location.reload(); //reload page because below code didnot work

        // Add new rows based on updated cart data
        
        // cartData.forEach(row => {
        //     const tr = document.createElement('tr');
        //     tr.innerHTML = `
        //         <td>${row.itemID}</td>
        //         <td>${row.name}</td>
        //         <td>1</td>
        //         <td>${row.quantity}</td>
        //         <td>${row.retailPrice}</td>
        //         <td><button type="button" class="btn btn-info add-item" data-itemid="${row.itemID}">Add another</button></td>
        //         <td><button type="button" class="btn btn-secondary delete-item" data-itemid="${row.itemID}">Delete Item</button></td>
        //     `;
        //     tbody.appendChild(tr);
        // });

        // Update subtotal and total if needed
        // ...

    })
    .catch(error => {
        console.error('Error fetching cart data:', error);
    });
}


</script>
</body>
</html>