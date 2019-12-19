<?php
$register_form = true;
require_once "lib/autoload.php";

// een array voor mogelijke errors
$errors = array('user_voornaam'=>'', 'user_naam'=>'', 'user_email'=>'', 'user_username'=>'', 'user_date'=>'', 'user_password'=>'');

if (isset($_POST['registerbutton'])) {      // POST check

    // voornaam nakijken
    if (empty($_POST['user_voornaam'])) {
        $errors['user_voornaam'] =  '<p>First name is required </p>';
    }
    else {
        $user_voornaam = $_POST['user_voornaam'];
        // nakijken of er alleen maar lowercase letters, uppercase letters, spaties en koppeltekens zijn gebruikt
        if ((preg_match('/^([a-zA-Z\s\-])*+$/', $user_voornaam)) == false) {
            $errors['user_voornaam'] =  '<p>First name can only contain letters, spaces  and -</p>';
        }
    } //einde voornaam nakijken

    // achternaam nakijken
    if (empty($_POST['user_naam'])) {
        $errors['user_naam'] =  '<p>Last name is required </p>';
    }
    else {
        $user_naam = $_POST['user_naam'];
        // nakijken of er alleen maar lowercase letters, uppercase letters, spaties en koppeltekens zijn gebruikt
        if ((preg_match('/^([a-zA-Z\s\-])*+$/', $user_naam)) == false) {
            $errors['user_naam'] =  '<p>Last name can only contain letters, spaces  and -</p>';
        }
    } //einde achternaam nakijken

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

    // geboortedatum nakijken
    if (empty($_POST['user_date'])) {
        $errors['user_date'] =  '<p>Your birth date is required </p>';
    }
    else {
        $user_date = $_POST['user_date'];
        // timestamp van nu
        $now = time();
        // steek de datum die als yyyy-mm-dd geformuleerd staat in een indexed array die de string verdeeldt aan de -
        $user_date_array = explode("-", $user_date);
        // steek elk onderdeel van de datum in een apparte variabelen
        $user_date_year = $user_date_array[0];
        $user_date_month = $user_date_array[1];
        $user_date_day = $user_date_array[2];
        // maak een timestamp van de info in de variabelen
        $user_date_timestamp = mktime(0,0,0, $user_date_month, $user_date_day, $user_date_year);
        // 13 jaar in seconden
        $sec13year = 410240038;
        // als het aantal seconden van nu - aantal seconden op de geboortedag kleiner is dan 13 jaar in seconden, dan is de persoon niet oud genoeg. ps: voor 1970 word de - een +  (omdat de timestamp van de verjaardag dan - is dus -- => + waardoor het altijd groter is dan 13 jaar in seconden maar dit maakt niet uit want iedereen van voor 1970 is automatisch ouder dan 13 jaar
        if (($now - $user_date_timestamp) < $sec13year) {
            $errors['user_date'] =  '<p>You are not old enough to enter the website</p>';
        }
    } //einde geboortedatum nakijken

    // passwoord nakijken
    if (empty($_POST['user_password'])) {
        $errors['user_password'] =   '<p>A password is required </p>';
    }
    //controle wachtwoord minimaal 8 tekens
    elseif ( strlen($_POST["user_password"]) < 8 ){
        $errors['user_password'] =   '<p>The password needs to be at least 8 characters long</p>';
    }
    else {
        //wachtwoord coderen
        $password_encrypted = password_hash ( $_POST["user_password"] , PASSWORD_DEFAULT );
        $user_password = $password_encrypted;
    } //einde passwoord nakijken

    //kijken of er errors gebeurt zijn. als er geen errors in $error staan is de statement false
    if(!array_filter($errors)) {
        $user_voornaam = ucwords($user_voornaam);
        $user_naam = ucwords($user_naam);
        $sql = "insert into user(user_voornaam, user_naam, user_email, user_username, user_date, user_password, user_join_date)
                    values('$user_voornaam', '$user_naam', '$user_email', '$user_username', '$user_date_timestamp', '$user_password', '$now')";

        if ( ExecuteSQL($sql) ) {
            $_SESSION["msg"][] = "Thank you for signing up!" ;

            if ( ControleLoginWachtwoord( $_POST["user_username"] , $_POST["user_password"]) ) {
                header('Location: index.php');
            }
        }
        else {
            $_SESSION["msg"][] = "Sorry, something went wrong. Your data was not successfully saved." ;
        }
    } // einde submit to database
}   // einde POST check

BasicHead();
HomePage3();
?>
    <section class="register">
    <form id="registration_form" method="post" action="register.php">

        <fieldset>
            <legend>Welcome new user!</legend>
            <ul>
                <li><label for="user_voornaam">First name:</label>
                    <input type="text" id="user_voornaam" name="user_voornaam" placeholder="Enter your first name here" tabindex="1" value="<?php echo htmlspecialchars($user_voornaam) ?>"> <!-- htmlspecialchars dat speciale tekens omgezet worden in escape enteties zodat eventuele code niet gerund kan worden -->
                    <p class="errors"> <?php echo $errors['user_voornaam']; ?></p></li>

                <li><label for="user_naam">Last name:</label>
                    <input type="text" id="user_naam" name="user_naam" placeholder="Enter your last name here" tabindex="2" value="<?php echo htmlspecialchars($user_naam) ?>">
                    <p class="errors"> <?php echo $errors['user_naam']; ?></p></li>

                <li><label for="user_email">E-mail:</label>
                    <input type="text" id="user_email" name="user_email" placeholder="Enter your e-mail address here" tabindex="3" value="<?php echo htmlspecialchars($user_email) ?>">
                    <p class="errors"> <?php echo $errors['user_email']; ?></p></li>

                <li><label for="user_username">Username:</label>
                    <input type="text" id="user_username" name="user_username" placeholder="Enter a unique username here" tabindex="4" value="<?php echo htmlspecialchars($user_username) ?>">
                    <p class="errors"> <?php echo $errors['user_username']; ?></p></li>

                <li><label for="user_date">Birthday:</label>
                    <input type="date" id="user_date" name="user_date" placeholder="Enter your birthday here" tabindex="5" value="<?php echo htmlspecialchars($user_date) ?>">
                    <p class="errors"> <?php echo $errors['user_date']; ?></p></li>

                <li><label for="user_password">Password:</label>
                    <input type="password" id="user_password" name="user_password" placeholder="Enter your password here" tabindex="6">
                    <p class="errors"> <?php echo $errors['user_password']; ?></p></li>
            </ul>
        </fieldset>
        <p>
            <input name="registerbutton" type="submit" tabindex="7" value="Register">
            <a href="" title="Link naar de login pagina" tabindex="8">Already a member?</a>
        </p>
    </form>
<?php BasicFooter();