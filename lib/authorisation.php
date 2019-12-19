<?php
function ControleLoginWachtwoord( $login, $paswd )
{
    //gebruiker opzoeken ahv zijn login (e-mail)
    $sql = "SELECT * FROM user WHERE user_username='" . $login . "' ";
    $data = GetData($sql);
    if ( count($data) == 1 )
    {
        $row = $data[0];
        //password controleren
        if ( password_verify( $paswd, $row['user_password'] ) ) $login_ok = true;
    }

    if ( $login_ok )
    {
        session_start();
        $_SESSION['user'] = $row;
        return true;
    }

    return false;
}