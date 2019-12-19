<?php
require_once "lib/autoload.php";

$afb_id = $_GET['id'];
$user_id = $_SESSION['user']['user_id'];

//nakijken of er al geliked is
$sql = "select * from `like`
            where like_afb_id = '$afb_id'
            and like_user_id = '$user_id';";

//als er nog geen data is, data toevoegen
if (GetData($sql) == null) {
    $sql = "insert into `like`
                set like_afb_id = '$afb_id',
                       like_user_id = '$user_id';";
    ExecuteSQL($sql);
} else {
    $sql = "delete from `like`
               where like_afb_id = '$afb_id'
               and like_user_id = '$user_id';";
    ExecuteSQL($sql);
}

//header("location: javascript://history.go(-1)");
$url = $_SERVER['HTTP_REFERER'];
header("location: $url");