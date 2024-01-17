<?php
// Dane do połączenia z bazą danych
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
$login = 'admin@gmail.com';
$pass = 'admin';

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect($dbhost, $dbuser, $dbpass);

// Sprawdzenie czy połączenie zostało pomyślnie ustanowione
if (!$link) {
    die('Przerwane połączenie: ' . mysqli_error($link));
}

// Wybór bazy danych
if (!mysqli_select_db($link, $baza)) {
    die('Nie wybrano bazy: ' . mysqli_error($link));
}
?>
