<?php
$no_access = true;
require_once "lib/autoload.php";

BasicHead();
HomePage();
print LoadTemplate("no_access");
BasicFooter();
        ?>
