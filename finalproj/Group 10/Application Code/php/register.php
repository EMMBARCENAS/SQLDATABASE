<?php
require_once('inc/config.php');

// Function to redirect to another page
function redirect($url)
{
    header("Location: $url");
    exit; // No code is executed after the redirection
}

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert the user data into the database
    $userQuery = "INSERT INTO User (Username, Password) VALUES (?, ?)";
    $userStmt = mysqli_prepare($db, $userQuery);

    if ($userStmt) {
        mysqli_stmt_bind_param($userStmt, "ss", $username, $password);

        if (mysqli_stmt_execute($userStmt)) {
            // Retrieve the user ID after successful registration
            $userId = mysqli_insert_id($db);

            // Insert the person data into the database
            $personQuery = "INSERT INTO Person (Name, Phone, Address, PId) VALUES (?, ?, ?, ?)";
            $personStmt = mysqli_prepare($db, $personQuery);

            if ($personStmt) {
                mysqli_stmt_bind_param($personStmt, "sssi", $_POST['name'], $_POST['phone'], $_POST['address'], $userId);

                if (mysqli_stmt_execute($personStmt)) {
                    // Store the user ID in the session
                    $_SESSION['user_id'] = $userId;

                    echo "User registration successful!";
                    redirect("main.php");
                } else {
                    echo "Error: " . mysqli_error($db);
                }

                mysqli_stmt_close($personStmt);
            } else {
                echo "Error: " . mysqli_error($db);
            }
        } else {
            echo "Error: " . mysqli_error($db);
        }

        mysqli_stmt_close($userStmt);
    } else {
        echo "Error: " . mysqli_error($db);
    }

    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D2B48C;
        }

        .main {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #838582;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            transition: 0.5s;
            background-color: #000;
        }

        @media screen and (max-width: 600px) {
            .main {
                margin: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="main">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <!-- User Information -->
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <!-- Person Information -->
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone">

            <label for="address">Address:</label>
            <input type="text" name="address">

            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>