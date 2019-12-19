<?php
session_start();
session_destroy();
unset($_SESSION);

session_start();
session_regenerate_id();
$_SESSION["msg"][] = "You are now logged out! Until next time!";
header("Location: ../login.php");

?>