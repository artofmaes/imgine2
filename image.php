<?php
require_once "lib/autoload.php";
if ( ! isset($_SESSION['user']) AND ! $login_form AND ! $register_form AND ! $no_access)
{ header("Location: no_access.php");}
BasicHead();
HomePage2();

$afb_id = $_GET['id'];

//haal de nodige data van de database
$sql = "select afb_naam, afb_user_id, afb_date, afb_omschr, afb_bestand, user_username, tag_omschr, afb_tag_tag_id
from afbeelding
inner join user on afbeelding.afb_user_id = user.user_id
inner join afb_tag on afbeelding.afb_id = afb_tag.afb_tag_afb_id
inner join tag on afb_tag.afb_tag_tag_id = tag.tag_id
where afb_id = '$afb_id'";

$data = GetData($sql);

//steek de data in variabelen om ze makkelijker te kunnen oproepen
$afb_naam = $data[0]['afb_naam'];
$afb_user_id = $data[0]['afb_user_id'];
$afb_date = $data[0]['afb_date'];
$afb_omschr = $data[0]['afb_omschr'];
$afb_bestand = $data[0]['afb_bestand'];
$user_username =  $data[0]['user_username'];
$afb_tag_tag_id =  $data[0]['afb_tag_tag_id'];
$tag_omschr =  $data[0]['tag_omschr'];
$current_user = $_SESSION['user']['user_id'];

//cats
$cat = '<p><a href="category.php?id=' . $afb_tag_tag_id . '" title="category is ' . $tag_omschr . '">#' . $tag_omschr . '</a></p>';
if (count($data) > 1) {
    $afb_tag_tag_id2 =  $data[1]['afb_tag_tag_id'];
    $tag_omschr2 = $data[1]['tag_omschr'];
    $cat = '<p><a href="category.php?id=' . $afb_tag_tag_id . '" title="category is ' . $tag_omschr . '">#' . $tag_omschr . '</a>
                        <a href="category.php?id=' . $afb_tag_tag_id2 . '" title="category is ' . $tag_omschr2 . '">#' . $tag_omschr2 . '</a></p>';
}

//is de afbeelding geliked?
$likes = GetData("select * from `like`
                                 where like_afb_id = '$afb_id' and like_user_id = '$current_user';");
if (count($likes) > 0) { $like_status = "img_liked"; }
else { $like_status = "img_unliked"; }
?>

    <section class="image">
        <div class="left">
            <input type="button" value="&#xf060 Back" onClick="javascript:history.back(-1);" class="fas fa-arrow-left">
            <a href="images/<?php echo $afb_bestand ?>" title="full screen view of the image" target="_blank"><img src="images/<?php echo $afb_bestand ?>" alt="image with caption: <?php echo $afb_naam ?>"></a>
        </div><!-- left-->
        <div class="right">
            <h1><?php echo $afb_naam ?></h1>
            <p class="info">Uploaded by: <a href="user-page.php?id=<?php echo $afb_user_id ?>" title="link to userpage of <?php echo $user_username ?>"><?php echo $user_username ?></a></p>
            <p class="info">Upload date: <?php echo date("d/m/Y", $afb_date) ?></p>
            <?php echo afbOmschrExplode($afb_omschr) ?>
            <?php echo $cat ?>

            <div class="buttons">
                <p><a href="like.php?id=<?php echo $afb_id ?>" title="like this image!" class="<?php echo $like_status ?>"><span class="fas fa-heart"></span> Like</a></p>
                <p><a href="images/<?php echo $afb_bestand ?>" title="you can dowload the image here!" download class="download"><span class="fas fa-download"></span> Download</a></p>
            </div>
        </div><!-- right -->

<?php BasicFooter();