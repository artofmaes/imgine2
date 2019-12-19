<?php
//------------ VARIABELEN BENOEMEN --------------
//session user benoemen
$afb_user_id = $_SESSION['user']['user_id'];
$afb_usernaam = $_SESSION['user']['user_username'];

//een array voor mogelijke errors
$errors = array('bestand_afb'=>'', 'afb_naam'=>'', 'afb_omschr'=>'', 'afb_main_cat'=>'');

$toegelaten_bestands_extensies = ['jpeg','jpg','png'];  //een array van toegelaten extensies

$bestands_naam = $_FILES['bestand']['name'];
$bestands_type = $_FILES['bestand']['type'];
$bestands_temp_naam = $_FILES['bestand']['tmp_name'];
$bestands_grootte = $_FILES['bestand']['size'];

//mappen definiëren
$huidige_map = getcwd();  //geeft de huidige map
$upload_map = "/images/";

//de waarde in bestandsnaam exploden naar wat er voor en achter het punts staat -> het laatste ellement in de array nemen ->  alle karakters veranderen in lowercase
$bestands_extensie = strtolower(end(explode('.',$bestands_naam)));

//$upload_pad = $huidige_map . $upload_map . basename($bestands_naam);
$afb_naam = $_POST['afb_naam'];
$afb_omschr = $_POST['afb_omschr'];
$afb_main_cat = $_POST['afb_main_cat'];
$afb_sec_cat = $_POST['afb_sec_cat'];
$afb_date = time();
//------------ EINDE VARIABELEN BENOEMEN --------------

if (isset($_POST['upload'])) {      // POST check
//------------INGEGEVEN WAARDEN NAAKIJKEN ----------------
    // bestand nakijken
    if (empty($bestands_naam)) {
        $errors['bestand_afb'] =  'Woops! You forgot to pick a file!';
    }
    else {
        $bestands_naam = uniqid('imgine-'. $afb_user_id .'-', true) . "." . $bestands_extensie;
        $upload_pad = $huidige_map . $upload_map . basename($bestands_naam);


        // nakijken of de extensie van het upgeloadde bestand bij de toegelaten extensies behoort
        if (! in_array($bestands_extensie,$toegelaten_bestands_extensies)) {
            $errors['bestand_afb'] = "Woops! You uploaded a '" .   strtoupper($bestands_extensie) . "' file.<br>Please upload a JPEG, JPG or PNG file";
        }
        // nakijken of de opgegeven file extensie bij de array van toegelate extensies zit
        $mb = substr("$bestands_grootte",-9,-6);
        $gb = substr("$bestands_grootte",-11,-9);
        if ($bestands_grootte > 1000000000) {
            $errors['bestand_afb'] = "Woops! Your image size is " . $gb . "GB.<br>Sorry, it has to be less than or equal to 20MB";
        } elseif ($bestands_grootte > 20000000) {
            $errors['bestand_afb'] = "Woops! Your image size is " . $mb . "MB.<br>Sorry, it has to be less than or equal to 20MB";
        }
    } //einde bestand nakijken

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

    // main category nakijken
    if (empty($afb_main_cat)) {
        $errors['afb_main_cat'] =  "<p>This field can't be emtpy. Please select at least 1 category. </p>";
    }    //einde main category nakijken

    //categories de nodige id geven
    $afb_main_cat = Categories($afb_main_cat);
    if ($afb_sec_cat  !=  null or $afb_sec_cat != 'catnone') {
        $afb_sec_cat = Categories($afb_sec_cat);
    }
// -------------------------------------------------------------------------------JAVASCRIPT MOET GEFIXED WORDEN----------------------------------------------------------------------------------------------------
//------------EINDE INGEGEVEN WAARDEN NAAKIJKEN ----------------

    //kijken of er errors gebeurt zijn. als er geen errors in $error staan is de statement false
    if(!array_filter($errors)) {
        // het upgeloade bestand in de juiste map steken
        $is_upgeload = move_uploaded_file($bestands_temp_naam, $upload_pad);

        //de data overbrengen naar tabel afbeelding in de database
        $sql = "insert into  afbeelding
                    set   afb_naam = '$afb_naam',
                            afb_user_id = '$afb_user_id',
                            afb_date = '$afb_date',
                            afb_omschr = '$afb_omschr',
                            afb_bestand = '$bestands_naam';";
        ExecuteSQL($sql);

        //
        $sql = "select afb_id from afbeelding
                    where afb_bestand = '$bestands_naam'";
        $data = GetData($sql);
        $afb_tag_afb_id = $data[0]['afb_id'];

        //
        if ($afb_sec_cat  ==  null or $afb_sec_cat == 'catnone') {
            $sql = "insert into afb_tag
                         set afb_tag_afb_id = '$afb_tag_afb_id',
                                afb_tag_tag_id = '$afb_main_cat';'";
            ExecuteSQL($sql);
        } else {
            $sql = "insert into afb_tag
                         set afb_tag_afb_id = '$afb_tag_afb_id',
                                afb_tag_tag_id = '$afb_main_cat';
                        insert into afb_tag
                        set afb_tag_afb_id = '$afb_tag_afb_id',
                                afb_tag_tag_id = '$afb_sec_cat';";
            if (ExecuteSQL($sql)) {
                header("Location: image.php?id=$afb_tag_afb_id");
            }
        }
    }
}  // einde POST check
?>