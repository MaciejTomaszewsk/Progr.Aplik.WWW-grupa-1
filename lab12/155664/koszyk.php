<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'moja_strona';
$link = new mysqli($dbhost, $dbuser, $dbpass, $db);

// Funkcja łącząca się z bazą danych
function polaczZBaza() {
    global $dbhost, $dbuser, $dbpass, $db;
    $polaczenie = new mysqli($dbhost, $dbuser, $dbpass, $db);

    if ($polaczenie->connect_error) {
        die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
    }

    return $polaczenie;
}

// Funkcja dodająca produkt do koszyka
function dodajProduktDoKoszyka($idProduktu, $ilosc) {
    // Sprawdzenie, czy sesja jest rozpoczęta
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    // Pobranie informacji o produkcie z bazy danych
    $polaczenie = polaczZBaza();
    $rezultat = $polaczenie->query("SELECT * FROM Produkty WHERE id = '$idProduktu'");

    if ($rezultat->num_rows > 0) {
        $produkt = $rezultat->fetch_assoc();

        // Utworzenie unikalnego identyfikatora dla produktu w koszyku
        $identyfikatorProduktu = md5($idProduktu . $produkt['tytul']);

        // Sprawdzenie, czy produkt już istnieje w koszyku
        if (isset($_SESSION['koszyk'][$identyfikatorProduktu])) {
            // Aktualizacja ilości produktu w koszyku
            $_SESSION['koszyk'][$identyfikatorProduktu]['ilosc'] += $ilosc;
        } else {
            // Dodanie nowego produktu do koszyka
            $_SESSION['koszyk'][$identyfikatorProduktu] = array(
                'id' => $idProduktu,
                'ilosc' => $ilosc,
                'cenaNetto' => $produkt['cena_netto'],
                'vat' => $produkt['vat'],
            );
        }
    }

    $rezultat->free_result();
    $polaczenie->close();
}


// Funkcja usuwająca produkt z koszyka
function usunProduktZKoszyka($identyfikatorProduktu) {
    // Sprawdzenie, czy sesja jest rozpoczęta
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    // Sprawdzenie, czy produkt istnieje w koszyku
    if (isset($_SESSION['koszyk'][$identyfikatorProduktu])) {
        // Usunięcie produktu z koszyka
        unset($_SESSION['koszyk'][$identyfikatorProduktu]);
    }
}

// Funkcja aktualizująca ilość produktu w koszyku
function aktualizujIloscWKoszyku($identyfikatorProduktu, $nowaIlosc) {
    // Sprawdzenie, czy sesja jest rozpoczęta
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    // Sprawdzenie, czy produkt istnieje w koszyku
    if (isset($_SESSION['koszyk'][$identyfikatorProduktu])) {
        // Aktualizacja ilości produktu w koszyku
        $_SESSION['koszyk'][$identyfikatorProduktu]['ilosc'] = $nowaIlosc;
    }
}

// Wczytanie danych o produktach z bazy do koszyka (do wykorzystania przy starcie sesji, na przykład w pliku startowym)
function wczytajProduktyZBazyDoKoszyka() {
    // Sprawdzenie, czy sesja jest rozpoczęta
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    // Pobranie danych o produktach z bazy
    $polaczenie = polaczZBaza();
    $rezultat = $polaczenie->query("SELECT * FROM Produkty");

    if ($rezultat->num_rows > 0) {
        while ($produkt = $rezultat->fetch_assoc()) {
            // Zadeklarowanie zmiennej $idProduktu
            $idProduktu = $produkt['id'];

            $identyfikatorProduktu = md5($idProduktu . $produkt['tytul']);
            
            // Sprawdzenie, czy produkt już istnieje w koszyku
            if (!isset($_SESSION['koszyk'][$identyfikatorProduktu])) {
                $_SESSION['koszyk'][$identyfikatorProduktu] = array(
                    'id' => $idProduktu,
                    'ilosc' => 0, // Domyślnie ilość ustawiamy na 0, aby później zaktualizować
                    'cenaNetto' => $produkt['cena_netto'],
                    'vat' => $produkt['vat'],
                );
            }
        }
    }

    $rezultat->free_result();
    $polaczenie->close();
}


// Funkcja obliczająca łączną wartość koszyka
function obliczSumarycznaWartoscKoszyka() {
    $suma = 0;

    foreach ($_SESSION['koszyk'] as $produkt) {
        $cenaNetto = $produkt['cenaNetto'];
        $vat = $produkt['vat'];
        $ilosc = $produkt['ilosc'];

        $cenaBrutto = $cenaNetto + ($cenaNetto * $vat);
        $suma += $cenaBrutto * $ilosc;
    }

    return $suma;
}

// Przykładowe użycie
wczytajProduktyZBazyDoKoszyka();

// Obsługa formularza dodawania do koszyka
if (isset($_POST['submit_add_to_cart'])) {
    $idProduktu = $_POST['id'];
    $ilosc = $_POST['ilosc'];
    dodajProduktDoKoszyka($idProduktu, $ilosc);
}

// Obsługa formularza usuwania z koszyka
if (isset($_POST['submit_remove_from_cart'])) {
    $identyfikatorProduktu = $_POST['identyfikatorProduktu'];
    usunProduktZKoszyka($identyfikatorProduktu);
}

// Obsługa formularza aktualizacji ilości w koszyku
if (isset($_POST['submit_update_quantity'])) {
    $identyfikatorProduktu = $_POST['identyfikatorProduktu'];
    $nowaIlosc = $_POST['nowaIlosc'];
    aktualizujIloscWKoszyku($identyfikatorProduktu, $nowaIlosc);
}

// Wyświetlanie koszyka
echo '<h2>Koszyk:</h2>';
if (!empty($_SESSION['koszyk'])) {
    echo '<table border="1">';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Cena Netto</th><th>VAT</th><th>Ilość</th><th>Wartość</th><th>Akcje</th></tr>';

    foreach ($_SESSION['koszyk'] as $identyfikatorProduktu => $produkt) {
        echo '<tr>';
        echo '<td>' . $produkt['id'] . '</td>';
        echo '<td>' . $produkt['id'] . '</td>';
        echo '<td>' . $produkt['cenaNetto'] . '</td>';
        echo '<td>' . $produkt['vat'] . '</td>';
        echo '<td>';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="identyfikatorProduktu" value="' . $identyfikatorProduktu . '">';
        echo '<input type="number" name="nowaIlosc" value="' . $produkt['ilosc'] . '" min="1">';
        echo '<input type="submit" name="submit_update_quantity" value="Aktualizuj">';
        echo '</form>';
        echo '</td>';
        echo '<td>' . ($produkt['cenaNetto'] + ($produkt['cenaNetto'] * $produkt['vat'])) * $produkt['ilosc'] . '</td>';
        echo '<td>';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="identyfikatorProduktu" value="' . $identyfikatorProduktu . '">';
        echo '<input type="submit" name="submit_remove_from_cart" value="Usuń">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '<tr>';
    echo '<td colspan="5" align="right">Suma:</td>';
    echo '<td>' . obliczSumarycznaWartoscKoszyka() . '</td>';
    echo '<td>';
    echo '<form method="post" action="">';
    echo '<input type="submit" name="submit_clear_cart" value="Wyczyść koszyk">';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
} else {
    echo 'Koszyk jest pusty.';
}

// Obsługa formularza wyczyszczenia koszyka
if (isset($_POST['submit_clear_cart'])) {
    $_SESSION['koszyk'] = array();
}

// Zamknięcie połączenia z bazą danych
$link->close();
?>
