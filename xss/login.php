<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row["password"])) {
            // Set a custom session ID based on the username
            $custom_session_id = hash('sha256', "user_" . $row['id']);
            session_write_close(); // Close any existing session
            session_id($custom_session_id); // Assign new session ID
            session_start();

            $_SESSION["username"] = $user;
            $_SESSION["session_id"] = $custom_session_id;

            // Store session ID in database
            $update_sql = "UPDATE users SET session_id='$custom_session_id' WHERE username='$user'";
            $conn->query($update_sql);

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
        }
        .left-container {
            width: 70%;
            overflow: hidden;
        }
        .left-container img{
            width: 100%;
            object-fit: contain;
            height: 100vh;
            transform: scale(1.3);
        }
        .right-container {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
        }
        .form-container {
            width: 80%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
            border-radius: 8px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #333;
        }
        h5{
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="left-container">
    <img src="photo.png" alt="none">
    </div>
    
    <div class="right-container">
        <div class="form-container">
            <h2>Log-In</h2>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log In</button>
                <h5>Didn't have an account? <a href="index.html">SignIn</a></h5>
            </form>
        </div>
    </div>
</body>
</html>
