<?php
require_once "lib/autoload.php";

//redirect naar NO ACCESS pagina als de gebruiker niet ingelogd is en niet naar
//de loginpagina gaat
if ( ! isset($_SESSION['user']) AND ! $login_form AND ! $register_form AND ! $no_access)
{
    header("Location: no_access.php");
}
require_once "lib/upload.php";
BasicHead();
HomePage2();
?>

    <div class="uploadform">
        <form id="uploadform" name="uploadform" method="post" action="upload.php" enctype="multipart/form-data">    <!-- action nakijken ---------------------------------->
            <fieldset>
                <legend>Upload a new image!</legend>
                <ul>
                    <li>
                        <input type="file" id="bestand" name="bestand" tabindex="1" class="inputfile inputfile-4" >
                        <label for="bestand"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose a file&hellip;</span></label>
                        <p class="errors"> <?php echo $errors['bestand_afb']; ?></p></li>
                    </li>

                    <li>
                        <label for="afb_naam">Caption:</label>
                        <input type="text" id="afb_naam" name="afb_naam" placeholder="Choose a catching caption!" tabindex="2" value="<?php echo htmlspecialchars($afb_naam) ?>">
                        <p class="errors"> <?php echo $errors['afb_naam']; ?></p>
                    </li>

                    <li>
                        <label for="afb_omschr">Description:</label>
                        <textarea id="afb_omschr" name="afb_omschr" placeholder="Tell a bit more about your captivating capture!" tabindex="3" value="<?php echo htmlspecialchars($afb_omschr) ?>"><?php echo htmlspecialchars($afb_omschr) ?></textarea>
                        <p class="errors"> <?php echo $errors['afb_omschr']; ?></p>
                    </li>

                    <li>
                        <label for="afb_main_cat">Main Category:</label>
                        <select id="afb_main_cat" name="afb_main_cat"  tabindex="4" onchange="dropdownlist(this.options[this.selectedIndex].value);">
                            <option value="selectcat" disabled selected>Select Main Category</option>
                            <option value="catnat">Nature</option>
                            <option value="cathum">Human</option>
                            <option value="catobj">Objects</option>
                            <option value="cattech">Techniques</option>
                        </select>
                        <p class="errors"> <?php echo $errors['afb_main_cat']; ?></p>
                        <button name="second_cat" type="button" tabindex="5" onclick="showSecCat()">Add/remove a secondary category?</button>
                    </li>

                    <li id="addseccat">
                        <label for="afb_sec_cat">Secondary Category:</label>
                        <script type="text/javascript" language="JavaScript">
                            document.write('<select name="afb_sec_cat"><option value="" disabled selected>Select Secondary Category</option></select>')
                        </script>
                        <noscript>
                            <select name="afb_sec_cat" id="afb_sec_cat" >
                                <option value="" disabled selected>Select Secondary Category</option>
                            </select>
                        </noscript>
                    </li>
                </ul>
            </fieldset>

            <p>
                <input name="upload" type="submit" value="Upload" tabindex="7">
                <input type="reset" value="Start over?" tabindex="8">
            </p>

        </form>
    </div>
    <?php
BasicFooter();