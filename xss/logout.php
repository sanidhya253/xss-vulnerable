<?php
session_start();
// Unset only username but keep session ID
unset($_SESSION["username"]);
header("Location: index.html");
exit();
?>
