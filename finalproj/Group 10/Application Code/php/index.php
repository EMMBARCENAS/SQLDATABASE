<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap">
    <title>Inventory Managed By Yourself</title>
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

        p {
            text-align: center;
            color: #555;
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

        a {
            display: block;
            text-align: center;
            color: #838582;
            text-decoration: none;
            margin-top: 15px;
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
        <h1>IMBY</h1>
        <p>Who we are: Inventory Tracking website suited for your Personal and Business Needs</p>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <a href="register.php">Don't have an account? Register Here</a>
    </div>
</body>

</html>