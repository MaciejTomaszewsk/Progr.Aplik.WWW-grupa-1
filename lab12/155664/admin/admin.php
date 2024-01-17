<?php
// Rozpoczęcie sesji
session_start();

// Sprawdzenie czy formularz logowania został przesłany
if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    // Pobranie danych z formularza
    $enteredLogin = $_POST['login_email'];
    $enteredPass = $_POST['login_pass'];

    // Wczytanie danych konfiguracyjnych
    require_once 'cfg.php';

    // Sprawdzenie poprawności danych logowania
    if ($enteredLogin === $login && $enteredPass === $pass) {
        $_SESSION['logged_in'] = true;
    } else {
        $error_message = "Błąd logowania. Spróbuj ponownie.";
    }
}

// Obsługa wylogowania
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

// Sprawdzenie czy użytkownik jest zalogowany
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    // Wyświetlenie powitania i formularza wylogowania
    echo "Witaj, administrator! Opcje administracyjne:";
    echo '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <input type="submit" name="logout" value="Wyloguj">
          </form>';

    // Wywołanie funkcji obsługujących podstrony
    ListaPodstron1();
    EdytujPodstrone();
    DodajNowaPodstrone();
    UsunPodstrone();
} else {
    // Wyświetlenie komunikatu błędu logowania lub formularza logowania
    echo isset($error_message) ? "<p style='color: red;'>$error_message</p>" : "";
    echo FormularzLogowania();
}

// Funkcja generująca formularz logowania
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Formularz Logowania:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="logowanie">
                    <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';
    return $wynik;
}

// Funkcja wyświetlająca listę podstron
function ListaPodstron1()
{
    // Konfiguracja bazy danych
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $db = 'moja_strona';

    // Połączenie z bazą danych
    $link = new mysqli($dbhost, $dbuser, $dbpass, $db);

    // Zapytanie SQL
    $query = "SELECT * FROM page_list LIMIT 100";
    $result = mysqli_query($link, $query);

    // Wyświetlenie listy podstron w tabeli
    if ($result) {
        echo '<h2>Lista Podstron:</h2>';
        echo '<table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tytuł Podstrony</th>
                    <th>Akcje</th>
                </tr>';

        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td>' . $row['page_title'] . '</td>
                    <td>
                        <button type="button" onclick="edytujPodstrone(' . $row['id'] . ')">Edytuj</button>
                        <button type="button" onclick="usunPodstrone(' . $row['id'] . ')">Usuń</button>
                    </td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo "Błąd zapytania SQL: " . mysqli_error($link);
    }

    // Zamknięcie połączenia z bazą danych
    mysqli_close($link);
}

// Funkcja obsługująca edycję podstrony
function EdytujPodstrone()
{
    // Wyświetlenie formularza edycji podstrony
    echo '
    <h2>Edycja Podstrony:</h2>
    <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
        <label for="id_podstrony">ID Podstrony:</label>
        <input type="text" id="id_podstrony" name="id_podstrony" required><br>

        <label for="tytul">Tytuł:</label>
        <input type="text" id="tytul" name="tytul" required><br>

        <label for="tresc">Treść:</label>
        <textarea id="tresc" name="tresc" required></textarea><br>

        <input type="submit" name="edytuj_podstrone" value="Edytuj Podstronę">
    </form>
    ';

    // Obsługa przesłanego formularza edycji podstrony
    if (isset($_POST['edytuj_podstrone'])) {
        if (isset($_POST['tytul'], $_POST['tresc'], $_POST['id_podstrony'])) {
            // Pobranie danych z formularza
            $tytul = $_POST['tytul'];
            $tresc = $_POST['tresc'];
            $id_podstrony = $_POST['id_podstrony'];

            // Konfiguracja bazy danych
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            $db = 'moja_strona';

            // Połączenie z bazą danych
            $link = new mysqli($dbhost, $dbuser, $dbpass, $db);

            // Sprawdzenie połączenia z bazą danych
            if ($link->connect_error) {
                die("Błąd połączenia z bazą danych: " . $link->connect_error);
            }

            // Zapytanie SQL do aktualizacji danych podstrony
            $query = "UPDATE page_list SET page_title='$tytul', page_content='$tresc' WHERE id=$id_podstrony";

            // Wykonanie zapytania SQL
            if ($link->query($query) === TRUE) {
                echo "Edycja podstrony zakończona sukcesem!";
            } else {
                echo "Błąd zapytania SQL: " . $link->error;
            }

            // Zamknięcie połączenia z bazą danych
            $link->close();
        } else {
            echo "Błąd: Wymagane pola tytuł, treść lub ID podstrony są puste.";
        }
    }
}

// Funkcja obsługująca dodawanie nowej podstrony
function DodajNowaPodstrone()
{
    // Wyświetlenie formularza dodawania nowej podstrony
    echo '
    <h2>Dodaj Nową Podstronę:</h2>
    <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
        <label for="tytul">Tytuł:</label>
        <input type="text" id="tytul" name="tytul" required><br>

        <label for="tresc">Treść:</label>
        <textarea id="tresc" name="tresc" required></textarea><br>

        <input type="submit" name="dodaj_nowa_podstrone" value="Dodaj Nową Podstronę">
    </form>
    ';

    // Obsługa przesłanego formularza dodawania nowej podstrony
    if (isset($_POST['dodaj_nowa_podstrone'])) {
        if (isset($_POST['tytul'], $_POST['tresc'])) {
            // Pobranie danych z formularza
            $tytul = $_POST['tytul'];
            $tresc = $_POST['tresc'];

            // Konfiguracja bazy danych
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            $db = 'moja_strona';

            // Połączenie z bazą danych
            $link = new mysqli($dbhost, $dbuser, $dbpass, $db);

            // Sprawdzenie połączenia z bazą danych
            if ($link->connect_error) {
                die("Błąd połączenia z bazą danych: " . $link->connect_error);
            }

            // Zapytanie SQL do dodania nowej podstrony
            $query = "INSERT INTO page_list (page_title, page_content) VALUES ('$tytul', '$tresc')";

            // Wykonanie zapytania SQL
            if ($link->query($query) === TRUE) {
                echo "Dodano nową podstronę pomyślnie.";
            } else {
                echo "Błąd zapytania SQL: " . $link->error;
            }

            // Zamknięcie połączenia z bazą danych
            $link->close();
        } else {
            echo "Błąd: Wymagane pola tytuł i treść są puste.";
        }
    }
}

// Funkcja obsługująca usuwanie podstrony
function UsunPodstrone()
{
    // Wyświetlenie formularza usuwania podstrony
    echo '
    <h2>Usuń Podstronę:</h2>
    <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
        <label for="id_podstrony_usun">ID Podstrony:</label>
        <input type="text" id="id_podstrony_usun" name="id_podstrony_usun" required><br>

        <input type="submit" name="usun_podstrone" value="Usuń Podstronę">
    </form>
    ';

    // Obsługa przesłanego formularza usuwania podstrony
    if (isset($_POST['usun_podstrone'])) {
        if (isset($_POST['id_podstrony_usun'])) {
            // Pobranie danych z formularza
            $id_podstrony_usun = $_POST['id_podstrony_usun'];

            // Konfiguracja bazy danych
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            $db = 'moja_strona';

            // Połączenie z bazą danych
            $link = new mysqli($dbhost, $dbuser, $dbpass, $db);

            // Sprawdzenie połączenia z bazą danych
            if ($link->connect_error) {
                die("Błąd połączenia z bazą danych: " . $link->connect_error);
            }

            // Zapytanie SQL do usunięcia podstrony
            $query = "DELETE FROM page_list WHERE id=$id_podstrony_usun LIMIT 1";

            // Wykonanie zapytania SQL
            if ($link->query($query) === TRUE) {
                echo "Podstrona została pomyślnie usunięta!";
            } else {
                echo "Błąd zapytania SQL: " . $link->error;
            }

            // Zamknięcie połączenia z bazą danych
            $link->close();
        } else {
            echo "Błąd: Pole ID podstrony do usunięcia jest puste.";
        }
    }
}
?>
