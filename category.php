<?php
require_once "lib/autoload.php";
//Geef de header met de log in/register knop
BasicHead();
if ( ! isset($_SESSION['user']) AND ! $login_form AND ! $register_form AND ! $no_access)
{
    HomePage();
}else{
    HomePage2();
    ShowMessages();
}

PrintNavBar();

$afb_tag_tag_id = $_GET['id'];


$data = GetData("select * from afbeelding
                                      inner join afb_tag on afbeelding.afb_id = afb_tag.afb_tag_afb_id
                                      where afb_tag_tag_id = '$afb_tag_tag_id'
                                      order by afb_date desc;");
$template = LoadTemplate("user-page-uploads");
print ReplaceContent( $data, $template);

BasicFooter();
?>