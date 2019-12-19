<?php
require_once "lib/autoload.php";

//een array voor mogelijke errors
$errors = array('bestand_afb'=>'', 'afb_naam'=>'', 'afb_omschr'=>'', 'afb_main_cat'=>'');

$afb_id = $_GET['id'];
$data = GetData("select * from afbeelding where afb_id = '$afb_id';");

$afb_naam = $data[0]['afb_naam'];
$afb_omschr = $data[0]['afb_omschr'];

if (isset($_POST['update'])) {
    $afb_naam = $_POST['afb_naam'];
    $afb_omschr = $_POST['afb_omschr'];
    // caption nakijken
    if (empty($afb_naam)) {
        $errors['afb_naam'] =  "<p>This field can't be emtpy. Please write a short but sweet title to your image. </p>";
    }
    else {
        // nakijken of er alleen maar lowercase letters, uppercase letters, spaties en koppeltekens zijn gebruikt
        if ((preg_match('/^([[:alnum:]\s-.,?!"\'])*+$/', $afb_naam)) == false) {
            $errors['afb_naam'] =  '<p>Your caption can only contain the following:<br> letters &emsp; numbers &emsp; spaces <br>, &emsp; . &emsp; ! &emsp; ? &emsp; -</p>';
        }
    } //einde caption nakijken

    // description nakijken
    if (empty($afb_omschr)) {
        $errors['afb_omschr'] =  "<p>This field can't be emtpy. Please tell us a little more about your image. </p>";
    } //einde description nakijken
    //------------EINDE INGEGEVEN WAARDEN NAAKIJKEN ----------------

    if(!array_filter($errors)) {
        if (ExecuteSQL("update afbeelding 
                                         set afb_naam = '$afb_naam',
                                               afb_omschr = '$afb_omschr'
                                          where afb_id = '$afb_id';")) {
            header("Location: image.php?id=$afb_id");
        }
    }
}  // einde POST check

BasicHead();
HomePage2();
?>

    <div class="uploadform">
        <form id="updateform" name="updateform" method="post" action="update.php?id=<?php echo $afb_id ?>">
            <fieldset>
                <legend>You're in edit mode!!</legend>
                <ul>
                    <li>
                        <label for="afb_naam">Caption:</label>
                        <input type="text" id="afb_naam" name="afb_naam" placeholder="Choose a catching caption!" tabindex="1" value="<?php echo htmlspecialchars($afb_naam) ?>">
                        <p class="errors"> <?php echo $errors['afb_naam']; ?></p>
                    </li>

                    <li>
                        <label for="afb_omschr">Description:</label>
                        <textarea id="afb_omschr" name="afb_omschr" placeholder="Tell a bit more about your captivating capture!" tabindex="2" value="<?php echo htmlspecialchars($afb_omschr) ?>" rows="10"><?php echo htmlspecialchars($afb_omschr) ?></textarea>
                        <p class="errors"> <?php echo $errors['afb_omschr']; ?></p>
                    </li>

                </ul>
            </fieldset>

            <p>
                <input name="update" type="submit" value="Update" tabindex="3">
            </p>

        </form>
    </div>
<?php
BasicFooter();