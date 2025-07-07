<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["user"])) {
    $selected_user = $_GET["user"];

    // Fetch hobby (NO sanitization - XSS vulnerable)
    $stmt = $conn->prepare("SELECT hobby FROM users WHERE username = ?");
    $stmt->bind_param("s", $selected_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // No escaping - XSS possible
    $hobby = $row ? $row["hobby"] : "No hobby found.";
    $stmt->close();
} else {
    $hobby = "No user selected.";
}

// If the form is submitted, update the hobby (NO sanitization - XSS vulnerable)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_hobby"])) {
    $new_hobby = $_POST["new_hobby"]; // No sanitization - XSS stays in DB
    //$new_hobby = htmlspecialchars($_POST["new_hobby"], ENT_QUOTES, 'UTF-8');
    $stmt = $conn->prepare("UPDATE users SET hobby = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_hobby, $_SESSION["username"]);
    if ($stmt->execute()) {
        $hobby = $new_hobby;
        echo "<p style='color: green;'>Hobby updated successfully!</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Hobby</title>
</head>
<body>
    <h2>Hobby of <?php echo htmlspecialchars($selected_user, ENT_QUOTES, 'UTF-8'); ?></h2>
    
    <!-- XSS Vulnerable Hobby Display (No escaping) -->
    <p><?php echo $hobby; ?></p>
     

    <?php if ($selected_user === $_SESSION["username"]) { ?>
        <!-- XSS Vulnerable Input Field -->
        <form method="POST">
            <input type="text" name="new_hobby" placeholder="Update your hobby" required>
            <button type="submit">Update Hobby</button>
        </form>
    <?php } ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
