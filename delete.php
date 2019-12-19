<?php
require_once "lib/autoload.php";

$afb_id = $_GET['id'];

$sql = "delete from afbeelding
           where afb_id = '$afb_id';";
if (ExecuteSQL($sql)) {
    header("Location: index.php");
} else { echo "Something went wrong";}