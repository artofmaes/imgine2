<?php
$login_form = true;
require_once "lib/autoload.php";



BasicHead();
ShowMessages();
HomePage3();
print LoadTemplate("login");
BasicFooter();
        ?>
