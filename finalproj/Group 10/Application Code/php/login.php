<?php
// Include the configuration file to connect to the database
include("inc/config.php");

// Function to redirect to another page
function redirect($url)
{
    header("Location: $url");
    exit; // No code is executed after the redirection
}

// Retrieve user input
$username = $_POST['username'];
$password = $_POST['password'];

// Sanitize input to prevent SQL injection
$username = mysqli_real_escape_string($db, $username);

// Query the database to get the hashed password
$query = "SELECT Password FROM User WHERE Username = '$username'";
$result = mysqli_query($db, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $hashedPassword = password_hash('1234', PASSWORD_DEFAULT);
        // Store $hashedPassword in the database


        // Verify the entered password against the hashed password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            echo "Login successful! Welcome, $username.";
            redirect("main.php");
        } else {
            // Failed login
            echo "Login failed. Incorrect password.";
        }
    } else {
        // Username not found
        echo "Login failed. Username not found.";
    }
} else {
    // Database query error
    echo "Database query error: " . mysqli_error($db);
}

// Close the database connection
mysqli_close($db);
