<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'moja_strona';
$link = new mysqli($dbhost, $dbuser, $dbpass, $db);

function DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
    global $link;

    $query = "INSERT INTO Produkty (tytul, opis, cena_netto, vat, ilosc, status, kategoria, gabaryt, zdjecie) 
              VALUES ('$tytul', '$opis', '$cena_netto', '$vat', '$ilosc', '$status', '$kategoria', '$gabaryt', '$zdjecie')";

    if ($link->query($query) === TRUE) {
        echo "Dodano nowy produkt.";
    } else {
        echo "Błąd zapytania SQL: " . $link->error;
    }
}

function UsunProdukt($produkt_id) {
    global $link;

    $query = "DELETE FROM Produkty WHERE id = '$produkt_id'";

    if ($link->query($query) === TRUE) {
        echo "Usunięto produkt.";
    } else {
        echo "Błąd zapytania SQL: " . $link->error;
    }
}

function EdytujProdukt($produkt_id, $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie) {
    global $link;

    $query = "UPDATE Produkty SET 
              tytul='$tytul', opis='$opis', cena_netto='$cena_netto', vat='$vat', ilosc='$ilosc', status='$status', 
              kategoria='$kategoria', gabaryt='$gabaryt', zdjecie='$zdjecie' WHERE id='$produkt_id'";

    if ($link->query($query) === TRUE) {
        echo "Zaktualizowano produkt.";
    } else {
        echo "Błąd zapytania SQL: " . $link->error;
    }
}

function PokazProdukty() {
    global $link;

    $query = "SELECT * FROM Produkty";
    $result = $link->query($query);

    if ($result) {
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>Tytuł</th><th>Opis</th><th>Cena Netto</th><th>VAT</th><th>Ilość</th><th>Status</th><th>Kategoria</th><th>Gabaryt</th><th>Zdjęcie</th><th>Akcje</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['tytul'].'</td>';
            echo '<td>'.$row['opis'].'</td>';
            echo '<td>'.$row['cena_netto'].'</td>';
            echo '<td>'.$row['vat'].'</td>';
            echo '<td>'.$row['ilosc'].'</td>';
            echo '<td>'.$row['status'].'</td>';
            echo '<td>'.$row['kategoria'].'</td>';
            echo '<td>'.$row['gabaryt'].'</td>';
            echo '<td>';
            echo '<img src="zdjecia/' . $row['zdjecie'] . '" alt="Zdjęcie produktu">';
            echo '</td>';
            echo '<td>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="id" value="'.$row['id'].'">';
            echo '<input type="submit" name="delete" value="Usuń">';
            echo '</form>';

            echo '<form method="post" action="">';
            echo '<input type="hidden" name="id" value="'.$row['id'].'">';
            echo '<input type="hidden" name="tytul" value="'.$row['tytul'].'">';
            echo '<input type="hidden" name="opis" value="'.$row['opis'].'">';
            echo '<input type="hidden" name="cena_netto" value="'.$row['cena_netto'].'">';
            echo '<input type="hidden" name="vat" value="'.$row['vat'].'">';
            echo '<input type="hidden" name="ilosc" value="'.$row['ilosc'].'">';
            echo '<input type="hidden" name="status" value="'.$row['status'].'">';
            echo '<input type="hidden" name="kategoria" value="'.$row['kategoria'].'">';
            echo '<input type="hidden" name="gabaryt" value="'.$row['gabaryt'].'">';
            echo '<input type="hidden" name="zdjecie" value="'.$row['zdjecie'].'">';
            echo '<input type="submit" name="edit" value="Edytuj">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '<tr>';
        echo '<td>';
        // Formularz dodawania nowego produktu
        echo '<form method="post" action="">';
        echo '<input type="submit" name="submit_add" value="Dodaj Produkt">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
    } else {
        echo 'Brak produktów.';
    }
}

// Obsługa formularza usuwania
if (isset($_POST['delete'])) {
    // Formularz usuwania
    echo '<h2>Usuń Produkt:</h2>';
    echo '
    <form method="post" action="">
        <input type="hidden" name="id" value="'.$_POST['id'].'">
        Czy na pewno chcesz usunąć ten produkt?<br>
        <input type="submit" name="submit_delete" value="Tak">
        <input type="submit" name="cancel" value="Anuluj">
    </form>
    ';
}

// Obsługa formularza edycji
if (isset($_POST['edit'])) {
    // Formularz edycji
    echo '<h2>Edytuj Produkt:</h2>';
    echo '
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="'.$_POST['id'].'">

        <label for="tytul">Tytuł:</label>
        <input type="text" id="tytul" name="tytul" value="'.$_POST['tytul'].'" required><br>

        <label for="opis">Opis:</label>
        <textarea id="opis" name="opis" required>'.$_POST['opis'].'</textarea><br>

        <label for="cena_netto">Cena Netto:</label>
        <input type="text" id="cena_netto" name="cena_netto" value="'.$_POST['cena_netto'].'" required><br>

        <label for="vat">VAT:</label>
        <input type="text" id="vat" name="vat" value="'.$_POST['vat'].'" required><br>

        <label for="ilosc">Ilość:</label>
        <input type="text" id="ilosc" name="ilosc" value="'.$_POST['ilosc'].'" required><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="'.$_POST['status'].'" required><br>

        <label for="kategoria">Kategoria:</label>
        <input type="text" id="kategoria" name="kategoria" value="'.$_POST['kategoria'].'" required><br>

        <label for="gabaryt">Gabaryt:</label>
        <input type="text" id="gabaryt" name="gabaryt" value="'.$_POST['gabaryt'].'" required><br>

        <label for="zdjecie">Zdjęcie:</label>
        <input type="file" id="zdjecie" name="zdjecie" accept="image/*"><br>

        <input type="submit" name="submit_edit" value="Zapisz Edycję">
    </form>
    ';
}

// Obsługa formularza dodawania
if (isset($_POST['submit_add'])) {
    // Formularz dodawania
    echo '<h2>Dodaj Produkt:</h2>';
    echo '
    <form method="post" action="" enctype="multipart/form-data">
        <label for="tytul">Tytuł:</label>
        <input type="text" id="tytul" name="tytul" required><br>

        <label for="opis">Opis:</label>
        <textarea id="opis" name="opis" required></textarea><br>

        <label for="cena_netto">Cena Netto:</label>
        <input type="text" id="cena_netto" name="cena_netto" required><br>

        <label for="vat">VAT:</label>
        <input type="text" id="vat" name="vat" required><br>

        <label for="ilosc">Ilość:</label>
        <input type="text" id="ilosc" name="ilosc" required><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br>

        <label for="kategoria">Kategoria:</label>
        <input type="text" id="kategoria" name="kategoria" required><br>

        <label for="gabaryt">Gabaryt:</label>
        <input type="text" id="gabaryt" name="gabaryt" required><br>

        <label for="zdjecie">Zdjęcie:</label>
        <input type="file" id="zdjecie" name="zdjecie" accept="image/*"><br>

        <input type="submit" name="submit_add" value="Dodaj Produkt">
    </form>
    ';
}

// Obsługa formularza usuwania
if (isset($_POST['submit_delete'])) {
    $produkt_id = $_POST['id'];
    UsunProdukt($produkt_id);
}

// Obsługa formularza edycji
if (isset($_POST['submit_edit'])) {
    $produkt_id = $_POST['id'];
    $tytul = $_POST['tytul'];
    $opis = $_POST['opis'];
    $cena_netto = $_POST['cena_netto'];
    $vat = $_POST['vat'];
    $ilosc = $_POST['ilosc'];
    $status = $_POST['status'];
    $kategoria = $_POST['kategoria'];
    $gabaryt = $_POST['gabaryt'];
    $zdjecie = $_FILES['zdjecie']['name'];

    // Zapisz zdjęcie w odpowiednim folderze (musisz utworzyć folder o nazwie "zdjecia" w miejscu, gdzie jest kod)
	if (isset($_FILES['zdjecie']) && $_FILES['zdjecie']['error'] === UPLOAD_ERR_OK) {
		$zdjecie = $_FILES['zdjecie']['name'];

		// Zapisz zdjęcie w odpowiednim folderze (musisz utworzyć folder o nazwie "zdjecia" w miejscu, gdzie jest kod)
		$target_dir = "zdjecia/";
		$target_file = $target_dir . basename($_FILES["zdjecie"]["name"]);
		move_uploaded_file($_FILES["zdjecie"]["tmp_name"], $target_file);
	} else {
		// Jeżeli nie przesłano zdjęcia, możesz ustawić zmienną $zdjecie na pusty ciąg znaków lub null, w zależności od preferencji.
		$zdjecie = ""; // lub $zdjecie = null;
	}

    EdytujProdukt($produkt_id, $tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
}

// Obsługa formularza dodawania
if (isset($_POST['submit_add'])) {
    $tytul = $_POST['tytul'];
    $opis = $_POST['opis'];
    $cena_netto = $_POST['cena_netto'];
    $vat = $_POST['vat'];
    $ilosc = $_POST['ilosc'];
    $status = $_POST['status'];
    $kategoria = $_POST['kategoria'];
    $gabaryt = $_POST['gabaryt'];
    $zdjecie = $_FILES['zdjecie']['name'];

    // Zapisz zdjęcie w odpowiednim folderze (musisz utworzyć folder o nazwie "zdjecia" w miejscu, gdzie jest kod)
    $target_dir = "zdjecia/";
    $target_file = $target_dir . basename($_FILES["zdjecie"]["name"]);
    move_uploaded_file($_FILES["zdjecie"]["tmp_name"], $target_file);

    DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
} 

// Wyświetlanie produktów
PokazProdukty();

// Zamknięcie połączenia z bazą danych
$link->close();
?>
