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

$current_user = $_SESSION['user']['user_id'];

if ($_GET['id'] == null) {
    $data = GetData("select * from afbeelding order by afb_date desc");
    foreach ($data as $row) {
        $afb_id = $row['afb_id'];
        $likes = GetData("select * from `like`
                                                 where like_afb_id = '$afb_id' and like_user_id = '$current_user';");
        if (count($likes) > 0) { //kijken of de huidige gebruiker de afbeelding al geliked heeft
            $row['like_status'] = "img_liked";

        } else {
            $row['like_status'] = "img_unliked";
        }
        $template = LoadTemplate("user-page-uploads");
        echo ReplaceContentOneRow ($row, $template);
    }
}
elseif ($_GET['id'] != null and $_GET['id'] < 5) { //als de id naar een van de vier categoriën verwijst
    $afb_tag_tag_id = $_GET['id'];
    $data = GetData("select * from afbeelding
                                              inner join afb_tag on afbeelding.afb_id = afb_tag.afb_tag_afb_id
                                              where afb_tag_tag_id = '$afb_tag_tag_id'
                                              order by afb_date desc;"); //alle afbeeldingen in de database die die tag van de categorie dragen
    foreach ($data as $row) {
        $afb_id = $row['afb_id'];
        $likes = GetData("select * from `like`
                                                 where like_afb_id = '$afb_id' and like_user_id = '$current_user';");
        if (count($likes) > 0) { //kijken of de huidige gebruiker de afbeelding al geliked heeft
            $row['like_status'] = "img_liked";

        } else {
            $row['like_status'] = "img_unliked";
        }
        $template = LoadTemplate("user-page-uploads");
        echo ReplaceContentOneRow ($row, $template);
    }
}
else {
    $afb_tag_tag_id = $_GET['id'];
$user_id = $_SESSION['user']['user_id'];
    $data = GetData("select * from afbeelding
                                              inner join `like` on afbeelding.afb_id = `like`.like_afb_id
                                              where like_user_id = '$user_id'
                                              order by afb_date desc;");
    foreach ($data as $row) {
        $afb_id = $row['afb_id'];
        $likes = GetData("select * from `like`
                                                 where like_afb_id = '$afb_id' and like_user_id = '$current_user';");
        if (count($likes) > 0) { //kijken of de huidige gebruiker de afbeelding al geliked heeft
            $row['like_status'] = "img_liked";

        } else {
            $row['like_status'] = "img_unliked";
        }
        $template = LoadTemplate("user-page-uploads");
        echo ReplaceContentOneRow ($row, $template);
    }
}

BasicFooter();
?>