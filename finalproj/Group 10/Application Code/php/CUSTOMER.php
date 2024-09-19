<?php
// Include the configuration file
require_once('inc/config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize customer data from the form
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $service = mysqli_real_escape_string($db, $_POST['service']);

    // Insert data into the Person table
    $personQuery = "INSERT INTO Person (Name, Phone, Address) VALUES ('$name', '$phone', '$address')";
    $personResult = mysqli_query($db, $personQuery);

    // Check if the Person data was inserted successfully
    if ($personResult) {
        // Retrieve the generated PId for the newly inserted person
        $pid = mysqli_insert_id($db);

        // Insert data into the Customer table
        $customerQuery = "INSERT INTO Customer (PId, Service) VALUES ('$pid', '$service')";
        $customerResult = mysqli_query($db, $customerQuery);

        // Check if the Customer data was inserted successfully
        if ($customerResult) {
            echo "Customer added successfully.";
        } else {
            echo "Error adding customer: " . mysqli_error($db);
        }
    } else {
        echo "Error adding person: " . mysqli_error($db);
    }
}

// Handle customer deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $customerIdToDelete = $_GET['delete'];

    // Perform the deletion (consider adding additional checks before executing the DELETE query)
    $deleteCustomerQuery = "DELETE FROM Customer WHERE PId = $customerIdToDelete";
    $deleteCustomerResult = mysqli_query($db, $deleteCustomerQuery);

    // Check if the deletion was successful
    if ($deleteCustomerResult) {
        echo "Customer deleted successfully.";
    } else {
        echo "Error deleting customer: " . mysqli_error($db);
    }
}

// Fetch all customers from the database
$fetchCustomersQuery = "SELECT * FROM Customer
                        INNER JOIN Person ON Customer.PId = Person.PId";
$customersResult = mysqli_query($db, $fetchCustomersQuery);

// Check if the query was successful
if ($customersResult) {
    // Fetch the data as an associative array
    $customersData = mysqli_fetch_all($customersResult, MYSQLI_ASSOC);
} else {
    // Handle the error if the query fails
    echo "Error fetching customers: " . mysqli_error($db);
}

// Close the database connection
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            var loadingIndicator = document.getElementById('loading-indicator');

            form.addEventListener('submit', function() {
                // Show the loading indicator when the form is submitted
                loadingIndicator.style.display = 'block';
            });
        });
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            width: 15%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
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

        a {
            text-decoration: none;
            color: #3498db;
        }

        a:hover {
            text-decoration: underline;
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

        #loading-indicator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #loading-indicator img {
            width: 50px;
            /* Adjust the size of the loading spinner */
            height: 50px;
        }

        #loading-indicator p {
            margin-top: 10px;
            font-weight: bold;
        }
        .delete-button {
    background-color: #e74c3c;
    color: #fff;
    padding: 4px 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s;
}

.delete-button:hover {
    background-color: #c0392b;
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
    <form method="post" action="customer.php">
        <!-- Include your form fields for customer information (name, phone, address, service) -->
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone">

        <label for="address">Address:</label>
        <input type="text" name="address">

        <label for="service">Service:</label>
        <input type="text" name="service" required>

        <input type="submit" value="Add Customer">
    </form>
    <!-- loading indicator -->
    <div id="loading-indicator" style="display: none;">
        <img src="load.gif" alt="Loading..." />
    </div>
    <!-- Display the table of all customers -->
    <h2>All Customers</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Service</th>
        <th>Action</th>
    </tr>
    <?php
    // Check if there are any customers to display
    if (!empty($customersData)) {
        // Loop through the data and display each customer in a table row
        foreach ($customersData as $customer) {
            echo "<tr>";
            echo "<td>{$customer['PId']}</td>";
            echo "<td>{$customer['Name']}</td>";
            echo "<td>{$customer['Phone']}</td>";
            echo "<td>{$customer['Address']}</td>";
            echo "<td>{$customer['Service']}</td>";
            echo "<td><a href='customer.php?delete={$customer['PId']}' onclick='return confirm(\"Are you sure?\")' class='delete-button'>Delete</a></td>";
            echo "</tr>";
        }
    } else {
        // Display a message if there are no customers
        echo "<tr><td colspan='6'>No customers found.</td></tr>";
    }
    ?>
</table>

</body>

</html>