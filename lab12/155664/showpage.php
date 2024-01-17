<?php
function PokazPodstrone($id)
{
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $db = 'moja_strona';
    $id_clear = htmlspecialchars($id);
    $link = new mysqli($dbhost,$dbuser,$dbpass,$db);

    $query="SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if(empty($row['id']))
    {
        $web = '[nie_znaleziono_strony]';
    }
    else
    {
        $web = $row['page_content'];
    }
    return $web;
}
?>