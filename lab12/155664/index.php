<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-Language" content="pl" />
    <meta name="Author" content="Maciej Tomaszewski" />
    <title>Gry komputerowe w pigułce</title>
	<link rel="stylesheet" href="style/style.css">
	<script type="text/javascript" src="java/kolorujtlo.js"></script>
	<script src="../java/timedate.js" type="text/javascript"></script>
    
</head>

<body>
<?php
include('cfg.php');
include('showpage.php');
include('admin/admin.php');
include('contact.php');
include('Kategorie.php');
include('produkty.php');
include('koszyk.php');
?>
	
<?php
// Informacje o autorze i grupie
$nr_indeksu = '155664';
$nrGrupy = '1';
echo 'Autor: Maciej Tomaszewski '. $nr_indeksu.' grupa: '. $nrGrupy.'<br/><br/>';
?>
<?php
$dbFail = False;
if (isset($_GET['id'])) {
    // Obsługa różnych przypadków ID
    if ($_GET['id'] == '1') {
        echo PokazPodstrone(1);
    } elseif ($_GET['id'] == '2') {
        echo PokazPodstrone(2);
    } elseif ($_GET['id'] == '3') {
        echo PokazPodstrone(3);
    } elseif ($_GET['id'] == '4') {
        echo PokazPodstrone(4);
    } elseif ($_GET['id'] == '5') {
        echo PokazPodstrone(5);
    } elseif ($_GET['id'] == '6') {
        echo PokazPodstrone(6);
    } elseif ($_GET['id'] == '7') {
        echo PokazPodstrone(7);
    } elseif ($_GET['id'] == '8') {
        echo PokazPodstrone(8);
    } else {
        $dbFail = True;
    }
} else {
    $dbFail = True;
}

// Obsługa błędu bazy danych
if ($dbFail == True) {
    if ($_GET['idp'] == '0') {
        include("Podstrony/main.html");
    } elseif ($_GET['idp'] == '1') {
        include("Podstrony/menu.html");
    } elseif ($_GET['idp'] == '2') {
        include("Podstrony/kontakt.html");
    } elseif ($_GET['idp'] == '3') {
        include("Podstrony/gry singleplayer.html");
    } elseif ($_GET['idp'] == '4') {
        include("Podstrony/gry multiplayer.html");
    } elseif ($_GET['idp'] == '5') {
        include("Podstrony/gry przeglądarkowe.html");
    } elseif ($_GET['idp'] == '6') {
        include("Podstrony/filmy.html");
    } else {
        include("Podstrony/main.html");
    }
}
?>


</body>

</html>
