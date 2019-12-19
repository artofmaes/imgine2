<?php
/* Deze functie laadt de <head> sectie */
function BasicHead()
{
    print LoadTemplate("basic_head");

    $_SESSION["head_printed"] = true;
}


function HomePage(){
    print LoadTemplate("homepage");
}

function HomePage2()
{
    $user_id = $_SESSION['user']['user_id'];
    $username = GetData("select user_username from user where user_id = '$user_id';");
    $user_username = $username[0]['user_username'];
    $data = array( "user_id" => $user_id, "user_username" => $user_username ) ;
    $template = LoadTemplate("homepage2");
    print ReplaceContentOneRow($data, $template);

}

function HomePage3()
{
    print LoadTemplate("homepage3");
}

function NavBar(){
    print LoadTemplate("nav");
}
function BasicFooter()
{
    print LoadTemplate("footer");
}

function PrintNavBar()
{
    //navbar items ophalen
    $data = GetData("select * from menu order by men_order");

    $laatstedeelurl = basename($_SERVER['REQUEST_URI']);
    //aan de juiste datarij, 'home', de sleutels 'active' en 'sr-only' toevoegen
    foreach( $data as $r => $row )
    {
        //if ( $r == 0 )
        if($laatstedeelurl == $data[$r]['men_destination'])
        {
            $data[$r]['active'] = 'active';
        }
        else
        {
            $data[$r]['active'] = '';
        }
    }

    //template voor 1 item samenvoegen met data voor items
    $template_navbar_item = LoadTemplate("nav_item");
    $navbar_items = ReplaceContent($data, $template_navbar_item);

    //navbar template samenvoegen met resultaat ($navbar_items)
    $data = array( "navbar_items" => $navbar_items ) ;
    $template_navbar = LoadTemplate("nav");
    print ReplaceContentOneRow($data, $template_navbar);
}

/* Deze functie laadt de opgegeven template */
function LoadTemplate( $name )
{
    if ( file_exists("$name.html") ) return file_get_contents("$name.html");
    if ( file_exists("template/$name.html") ) return file_get_contents("template/$name.html");
    if ( file_exists("../template/$name.html") ) return file_get_contents("../template/$name.html");
}

/* Deze functie voegt data en template samen en print het resultaat */
function ReplaceContent( $data, $template_html )
{
    $returnval = "";

    foreach ( $data as $row )
    {
        //replace fields with values in template
        $content = $template_html;
        foreach($row as $field => $value)
        {
            $content = str_replace("@@$field@@", $value, $content);
        }

        $returnval .= $content;
    }

    return $returnval;
}

/* Deze functie voegt data en template samen en print het resultaat */
function ReplaceContentOneRow( $row, $template_html )
{
        //replace fields with values in template
        $content = $template_html;
        foreach($row as $field => $value)
        {
            $content = str_replace("@@$field@@", $value, $content);
        }

    return $content;
}



// deze functie encrypt de email adressen
function encodeEmail($e) {
    for ($i = 0; $i < strlen($e); $i++) { $output .= '&#'.ord($e[$i]).';'; }
    return $output;
}


// functie voor categories de juiste id nummer van de database te geven
function Categories($cat) {
    if ($cat == 'catnat') {
        return 1;
    } elseif ($cat == 'cathum') {
        return 2;
    } elseif ($cat == 'catobj') {
        return 3;
    } elseif ($cat == 'cattech') {
        return 4;
    } else {
        return 'none';
    }
}


function afbOmschrExplode($afb_omschr) {
    //omschr in een array zetten met gebruik van end of line
    $afb_omschr_array = explode(PHP_EOL, $afb_omschr);
    //hoeveel paragrafen zijn er?
    $afb_omsch_para = count($afb_omschr_array);
    $counter = 0;
    while ($counter < $afb_omsch_para) {
        echo '<p class="omschr">' . $afb_omschr_array[$counter] . '</p>';
        $counter ++;
    }
} //einde function afbOmschrExplode($afb_omschr)