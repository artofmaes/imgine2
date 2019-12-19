<?php
require_once "lib/autoload.php";

//een array voor mogelijke errors
$errors = array('bestand_afb'=>'', 'afb_naam'=>'', 'afb_omschr'=>'', 'afb_main_cat'=>'');

//steek de data in variabelen om ze makkelijker te kunnen oproepen
$user_id = $_SESSION['user']['user_id'];
$user_voornaam = $_SESSION['user']['user_voornaam'];
$user_naam = $_SESSION['user']['user_naam'];
$user_email = $_SESSION['user']['user_email'];
$user_username =$_SESSION['user']['user_username'];
$user_date = $_SESSION['user']['user_date'];
$user_join_date = $_SESSION['user']['user_join_date'];

if (isset($_POST['changebutton'])) {      // POST save check
    $user_username = $_POST['user_username'];
    $user_email = $_POST['user_email'];


    // usernaam nakijken
    if (empty($_POST['user_username'])) {
        $errors['user_username'] =  '<p>A username is required </p>';
    }
    else {
        $user_username = $_POST['user_username'];
        // nakijken of er alleen maar  letters, cijfers , spaties en koppeltekens en underscores zijn gebruikt
        if ((preg_match('/^([\w\s\-_])*+$/', $user_username)) == false) {
            $errors['user_username'] =  '<p>Username can only contain letters, numbers, spaces , - or _</p>';
        }
        //controle of gebruiker al bestaat
        $sql = "SELECT * FROM user WHERE user_username='" . $user_username . "' ";
        $data = GetData($sql);
        if ( count($data) > 0 ){
            $errors['user_username'] =  '<p>Username already exists, please chose another one.</p>';
        }
    } //einde usernaam nakijken

    // e-mail nakijken
    if (empty($_POST['user_email'])) {
        $errors['user_email'] =  '<p>An e-mail address is required </p>';
    }
    else {
        $user_email = $_POST['user_email'];
        // nakijken of het een valid email adres is
        if ((filter_var($user_email, FILTER_VALIDATE_EMAIL)) ==false) {
            $errors['user_email'] =  '<p>E-mail must be a valid e-mail address</p>';
        }
    } //einde e-mail nakijken

    if(!array_filter($errors)) {
        if (ExecuteSQL("update user 
                                         set user_username = '$user_username',
                                               user_email = '$user_email'
                                          where user_id = '$user_id';")) {
            header("Location: user-page.php?id=$user_id");
        }
    }
}   // einde POST save check

if (isset($_POST['delete_profile'])) {
    header("Location: delete-user.php?id=$user_id");
}

BasicHead();
HomePage2();

?>
<div class="uploadform">
    <form id="settings" method="post" action="user-settings.php?id=<?php echo $user_id ?>">

        <fieldset>
            <legend>Settings</legend>
            <ul>
                <li><label for="user_username">Change your username:</label>
                    <input type="text" id="user_username" name="user_username" placeholder="Enter a unique username here" tabindex="1" value="<?php echo htmlspecialchars($user_username) ?>">
                    <p class="errors"> <?php echo $errors['user_username']; ?></p></li>

                <li><label for="user_email">Change your e-mail address:</label>
                    <input type="text" id="user_email" name="user_email" placeholder="Enter your e-mail address here" tabindex="2" value="<?php echo htmlspecialchars($user_email) ?>">
                    <p class="errors"> <?php echo $errors['user_email']; ?></p></li>
            </ul>
            <p>
                <input name="changebutton" type="submit" tabindex="3" value="Save changes">
            </p>
        </fieldset>

        <fieldset>
            <legend>Do you wanna leave us?</legend>
            <p>We hate to see you go! We hope you never lose your passion for photography!</p>
            <input type="submit" name="delete_profile" value="Delete your profile">
            <p>Attention!! Once you delete your account, all your pictures will be deleted, too. <br>There is no way to undo this!!</p>
        </fieldset>
    </form>
</div>
<?php
BasicFooter();