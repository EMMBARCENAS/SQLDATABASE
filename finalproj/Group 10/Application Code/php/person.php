<?php
require_once('inc/config.php');
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>Person Entity</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background-color: #D2B48C;
            margin: 0;
            padding: 0;
        }

        .welcome-container {
            margin-top: 10px;
            padding: 5px;
            background-color: #fff;
            width: 50%;
            margin-left: 385px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .image-container {
            display: flex;
            justify-content: space-around;
            margin: 35px;
        }

        .image-container img {
            width: 600px;
            height: 600px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .image-container img:hover {
            transform: scale(1.02);
        }

        .logo img {
            padding-left: 77px;
            width: 100px;
            height: 100px;
            margin-top: 10px;
            margin-left: 10px;
            float: left;
        }

        .logout-container {
            margin-top: 10px;
            padding: 5px;
            background-color: #fff;
            width: 5%;
            margin-left: 0;
            margin-right: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            float: right;

        }

        .logout-container a {
            color: #333;
            text-decoration: none;
        }

        .logout-container a:hover {
            color: #D2B48C;
        }

        @media screen and (max-width: 600px) {
            .main {
                margin: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <a href="main.php?logout=true">Logout</a>
    </div>
    <div class="logo">
        <a href="main.php">
            <img src="logo.png" alt="Logo">
        </a>
    </div>
    <div class="welcome-container">
        <h1>Welcome to the Person Entity!</h1>
        <p>Explore our features and manage your inventory effortlessly.</p>
    </div>

    <div class="image-container">
        <a href="employee.php">
            <img src="EMPLOYEE.jpg" alt="Employee Image">
        </a>

        <a href="customer.php">
            <img src="customer.jpg" alt="Customer Image">
        </a>
    </div>
</body>

</html>