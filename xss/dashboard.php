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

// $user = $_SESSION["username"];


// $users_sql = "SELECT username FROM users";
// $users_result = $conn->query($users_sql);
// $users = [];
// while ($user_row = $users_result->fetch_assoc()) {
//     $users[] = $user_row;
// }


// $selected_user = isset($_GET['user']) ? $_GET['user'] : $users[0]['username'];

// $user_info_sql = "SELECT username, age, gender FROM users WHERE username = ?";
// $stmt = $conn->prepare($user_info_sql);
// $stmt->bind_param("s", $selected_user);
// $stmt->execute();
// $result = $stmt->get_result();
// $user_info = $result->fetch_assoc();
// $stmt->close();
$logged_in_user = $_SESSION["username"];

// Fetch all users
$users_sql = "SELECT username FROM users";
$users_result = $conn->query($users_sql);
$users = [];
while ($user_row = $users_result->fetch_assoc()) {
    $users[] = $user_row;
}

// Determine which user info to display
$selected_user = isset($_GET['user']) ? $_GET['user'] : $logged_in_user;

$user_info_sql = "SELECT username, age, gender FROM users WHERE username = ?";
$stmt = $conn->prepare($user_info_sql);
$stmt->bind_param("s", $selected_user);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bungee+Tint&family=Exo:ital,wght@0,100..900;1,100..900&display=swap');
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Exo", sans-serif;
        }

        .main {
            width: 100%;
            background-color: #d6f3db;
            height: 100vh;
            padding-top: 20px;
        }

        .header {
            background: #f2f2f2;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: darkred;
            width: 80%;
            margin: auto;
            border-radius: 20px 20px 0 0;
        }

        .sub-header {
            display: flex;
            justify-content: space-between;
            background: #e6e6e6;
            padding: 10px 20px;
            font-size: 18px;
            width: 80%;
            margin: auto;
            border-radius: 0 0 20px 20px;
        }

        .logout-btn {
            background: red;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .container {
            display: flex;
            margin: 20px;
            width: 80%;
            margin: auto;
            margin-top: 20px;
            justify-content: space-between;
        }

        .users-panel {
            width: 25%;
            background: white;
            padding: 20px;
            border-radius: 30px;

        }

        .users-panel h3 {
            text-align: center;
        }

        .user-list {
            list-style: none;
            padding: 0;
        }

        .user-list li {
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 18px;
            border-bottom: 1px dotted black;
        }

        .user-list li img {
            width: 40px;
            
        }

        .user-info-panel {
            width: 70%;
            background: white;
            margin-left: 20px;
            padding: 40px;
            border-radius: 30px;
            display: flex;
        }

        .user-info-panel div img {
            width: 170px;
        }

        .user-info-panel .info-img {
            width: 25%;
        }

        .user-info-panel .info-detail {
            width: 35%;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            
        }

        .info-detail p span{
            font-size: 25px;
            font-weight: 600;
        }
        .info-hobby p span{
            font-size: 25px;
            font-weight: 600;
        }
        .user-info-panel .info-hobby {
            width: 40%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: 50px;
        }


        .view-hobby-btn {
            background: #d4851f;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="header">HOBBY NEST</div>

        <div class="sub-header">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
            <button class="logout-btn" onclick="location.href='logout.php'">Log Out</button>
        </div>

        <div class="container">
            <!-- Left Panel: All Users -->
            <div class="users-panel">
                <h3>All users</h3>
                <ul class="user-list">
                    <?php foreach ($users as $u) { ?>
                        <li onclick="location.href='dashboard.php?user=<?php echo urlencode($u['username']); ?>'">
                            <img src="user.png" alt="User">
                            <?php echo htmlspecialchars($u['username']); ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Right Panel: Selected User Info -->
            <div class="user-info-panel">
                <div class="info-img">
                    <img src="user.png" alt="user">
                </div>
                <div class="info-detail">
                    <p><span>Name: </span> <br> <?php echo htmlspecialchars($user_info['username']); ?></p>
                    <p><span>Age:</span> <br> <?php echo htmlspecialchars($user_info['age']); ?></p>
                    <p><span>Gender:</span> <br> <?php echo htmlspecialchars($user_info['gender']); ?></p>

                </div>
                <div class="info-hobby">
                    <div>

                    <p><span>Message:</span></p>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ducimus cumque iusto minima ipsum impedit quia?</p>
                    </div>
                    <button class="view-hobby-btn" onclick="location.href='view_hobby.php?user=<?php echo urlencode($user_info['username']); ?>'">View Hobby</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>