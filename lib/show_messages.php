<?php
function ShowMessages()
{

    if ( !$_SESSION["head_printed"] ) BasicHead();

    //weergeven messages
    if (isset($_SESSION["msg"]) )
    {

        foreach( $_SESSION["msg"] as $message )
        {
            $row = array( "message" => $message );
            $data = array( $row ) ;
            $templ = LoadTemplate("messages");
            print ReplaceContent( $data, $templ );
        }

        unset($_SESSION['msg']);
    }
}