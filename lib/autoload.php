<?php
session_start();
$_SESSION["head_printed"] = false;

require_once "pdo.php";                         //database functies
require_once "view_functions.php";      //basic_head, load_template, replacecontent...
require_once "authorisation.php";      //controle login e.d.
require_once "show_messages.php";


