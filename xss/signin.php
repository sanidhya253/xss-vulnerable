<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $hobby = $_POST["hobby"];


    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $age = (int) $conn->real_escape_string($age);  // Convert age to integer
    $gender = $conn->real_escape_string($gender);
    // $hobby = $conn->real_escape_string($hobby);
    $hobby = $conn->real_escape_string(htmlspecialchars($hobby, ENT_QUOTES, 'UTF-8'));


    $sql = "INSERT INTO users (username, password, age, gender, hobby) VALUES ('$username', '$password', '$age', '$gender', '$hobby')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

