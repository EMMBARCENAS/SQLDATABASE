<?php
// Include the configuration file
require_once('inc/config.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start the session
session_start();

// Retrieve the user ID dynamically
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inventory data from the form
    $invName = mysqli_real_escape_string($db, $_POST['invName']);
    $invQuantity = mysqli_real_escape_string($db, $_POST['invQuantity']);

    // Insert data into the Inventory table without specifying UserId
    $inventoryQuery = "INSERT INTO Inventory (InvName, InvQuantity, UserId) VALUES ('$invName', '$invQuantity', '$userId')";
    $inventoryResult = mysqli_query($db, $inventoryQuery);

    // Check if the Inventory data was inserted successfully
    if ($inventoryResult) {
        // Retrieve the generated InvId for the newly inserted inventory
        $invId = mysqli_insert_id($db);

        // Retrieve data from the form
        $sName = mysqli_real_escape_string($db, $_POST['sName']);
        $sPhone = mysqli_real_escape_string($db, $_POST['sPhone']);
        $sAddress = mysqli_real_escape_string($db, $_POST['sAddress']);

        // Insert data into the Supplier table using the retrieved InvId
        $supplierQuery = "INSERT INTO Supplier (SName, SPhone, SAddress, InvId) VALUES ('$sName', '$sPhone', '$sAddress', '$invId')";
        $supplierResult = mysqli_query($db, $supplierQuery);

        // Check if the Supplier data was inserted successfully
        if ($supplierResult) {
            // Retrieve and sanitize delivery data from the form
            $deliveryQuantity = mysqli_real_escape_string($db, $_POST['deliveryQuantity']);
            $deliveryPrice = mysqli_real_escape_string($db, $_POST['deliveryPrice']);

            // Insert data into the Delivery table using the retrieved InvId and SupId
            $deliveryQuery = "INSERT INTO Delivery (InvId, SupId, SQuantity, SPrice) VALUES ('$invId', LAST_INSERT_ID(), '$deliveryQuantity', '$deliveryPrice')";
            $deliveryResult = mysqli_query($db, $deliveryQuery);

            // Check if the Delivery data was inserted successfully
            if ($deliveryResult) {
                echo "Supplier and Delivery information added successfully.";
            } else {
                echo "Error adding delivery information: " . mysqli_error($db);
            }
        } else {
            echo "Error adding supplier: " . mysqli_error($db);
        }
    } else {
        echo "Error adding inventory: " . mysqli_error($db);
    }
}

// Fetch all suppliers and delivery information for the logged-in user from the database
$fetchDataQuery = "SELECT 
                        Supplier.SupId, Supplier.SName, Supplier.SPhone, Supplier.SAddress, 
                        Supplier.InvId, Delivery.SupId AS DeliveryId, Delivery.SQuantity, Delivery.SPrice
                   FROM Supplier
                   LEFT JOIN Delivery ON Supplier.SupId = Delivery.SupId
                   WHERE Supplier.InvId IN (SELECT InvId FROM Inventory WHERE UserId = '$userId')";


$dataResult = mysqli_query($db, $fetchDataQuery);

// Check if the query was successful
if ($dataResult) {
    // Fetch the data as an associative array
    $data = mysqli_fetch_all($dataResult, MYSQLI_ASSOC);
} else {
    // Handle the error if the query fails
    echo "Error fetching data: " . mysqli_error($db);
}

// Close the database connection
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier, Inventory, and Delivery Management</title>
    <script>
        function confirmInsertion() {
            // Display a confirmation dialog
            var confirmation = confirm("Do you want to add this inventory, supplier, and delivery information?");

            // If the user clicks "OK" in the confirmation dialog, submit the form
            if (confirmation) {
                document.getElementById("insertForm").submit();
            } else {
                // If the user clicks "Cancel"
            }
        }
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            width: 60%;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        label {
            display: block;
            margin-bottom: 5px;
            width: 45%;
        }

        input {
            width: 45%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            margin-top: 2.5px;
            width: 100%;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        nav {
            background-color: #333;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        li {
            display: inline;
            margin-right: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        a:hover {
            text-decoration: underline;
        }

        body {
            margin-top: 60px;
        }

        @media screen and (max-width: 600px) {
            .main {
                margin: 20px;
            }
        }
        /* Style for the delete button */
.delete-button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.delete-button:hover {
    background-color: darkred;
}

    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a href="main.php">Main Page</a></li>
            <li><a href="person.php">Person Entity</a></li>
            <li><a href="inventory.php">Inventory</a></li>
        </ul>
    </nav>

    <h1>Supplier, Inventory, and Delivery Management</h1>

    <form method="post" action="inventory.php" id="insertForm">
        <!-- Include your form fields for inventory information-->
        <label for="invName">Inventory Name:</label>
        <input type="text" name="invName" required>

        <label for="invQuantity">Inventory Quantity:</label>
        <input type="text" name="invQuantity" required>

        <!-- Include your form fields for supplier information  -->
        <label for="sName">Supplier Name:</label>
        <input type="text" name="sName" required>

        <label for="sPhone">Supplier Phone:</label>
        <input type="text" name="sPhone">

        <label for="sAddress">Supplier Address:</label>
        <input type="text" name="sAddress">

        <!-- Include your form fields for delivery information -->
        <label for="deliveryQuantity">Delivery Quantity:</label>
        <input type="text" name="deliveryQuantity" required>

        <label for="deliveryPrice">Delivery Price:</label>
        <input type="text" name="deliveryPrice" required>

        <input type="submit" value="Add Inventory, Supplier, and Delivery" onclick="confirmInsertion()">
    </form>

<h2>All Suppliers and Delivery Information</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Inventory ID</th>
        <th>Delivery ID</th>
        <th>Delivery Quantity</th>
        <th>Delivery Price</th>
        <th>Action</th>
    </tr>
    <?php
    // Check if there is any data to display
    if (!empty($data)) {
        // Loop through the data and display each row in a table row
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>{$row['SupId']}</td>";
            echo "<td>{$row['SName']}</td>";
            echo "<td>{$row['SPhone']}</td>";
            echo "<td>{$row['SAddress']}</td>";
            echo "<td>{$row['InvId']}</td>";
            echo "<td>{$row['DeliveryId']}</td>";
            echo "<td>{$row['SQuantity']}</td>";
            echo "<td>{$row['SPrice']}</td>";
            // Add a styled delete button with a form for each row
            echo "<td>";
            echo "<form method='post' action='delete.php'>";
            echo "<input type='hidden' name='deleteId' value='{$row['SupId']}'>";
            echo "<input type='submit' value='Delete' class='delete-button'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        // Display a message if there is no data
        echo "<tr><td colspan='9'>No data found.</td></tr>";
    }
    ?>
</table>


</body>

</html>