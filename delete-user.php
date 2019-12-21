<?php
require_once "lib/autoload.php";

$user_id = $_GET['id'];

$sql = "delete from user
           where user_id = '$user_id';";
if (ExecuteSQL($sql)) {
    session_destroy();
    unset($_SESSION);
    header("Location: register.php");
} else { echo "Something went wrong";}