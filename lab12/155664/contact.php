<?php
// Definicja klasy Kontakt
class Kontakt
{
    // Metoda wyświetlająca formularz kontaktowy
    public function PokazKontakt()
    {
        echo '
        <h2>Formularz Kontaktowy:</h2>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="wiadomosc">Wiadomość:</label>
            <textarea id="wiadomosc" name="wiadomosc" required></textarea><br>

            <input type="submit" name="wyslij_mail" value="Wyślij">
        </form>
        ';
    }

    // Metoda wysyłająca maila na podstawie formularza kontaktowego
    public function WyslijMailaKontakt($odbiorca = 'domyślny_adres_email')
    {
        if (isset($_POST['wyslij_mail'])) {
            if (empty($_POST['wiadomosc']) || empty($_POST['email'])) {
                echo '[nie_wypełniłeś_pola]';
                $this->PokazKontakt();
            } else {
                // Utworzenie tablicy z danymi maila
                $mail['subject']   = 'Temat wiadomości';
                $mail['body']      = $_POST['wiadomosc'];
                $mail['sender']    = $_POST['email'];
                $mail['recipient'] = $odbiorca;

                // Ustawienie nagłówków maila
                $header  = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
                $header .= "Content-Type: text/plain; charset=utf-8\n";
                $header .= "Content-Transfer-Encoding: quoted-printable\n";
                $header .= "X-Sender: <" . $mail['sender'] . ">\n";
                $header .= "X-Mailer: PRapWW mail 1.2\n";
                $header .= "X-Priority: 3\n";
                $header .= "Return-Path: <" . $mail['sender'] . ">\n";

                // Wysłanie maila
                mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

                echo '[wiadomosc_wyslana]';
            }
        }
    }

    // Metoda wyświetlająca formularz przypomnienia hasła
    public function PrzypomnijHaslo()
    {
        echo '
        <h2>Przypomnij Hasło:</h2>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <label for="email_przypomnienie">Email:</label>
            <input type="email" id="email_przypomnienie" name="email_przypomnienie" required><br>

            <input type="submit" name="remind_password" value="Przypomnij Hasło">
        </form>
        ';

        if (isset($_POST['remind_password'])) {
            // Wywołanie metody wysyłającej maila kontaktowego
            $this->WyslijMailaKontakt();
        }
    }
}

// Utworzenie obiektu klasy Kontakt
$kontakt = new Kontakt();

// Sprawdzenie, czy formularz został przesłany
if (isset($_POST['wyslij_mail'])) {
    // Wywołanie metody wysyłającej maila kontaktowego
    $kontakt->WyslijMailaKontakt();
} elseif (isset($_POST['remind_password'])) {
    // Wywołanie metody przypominającej hasło
    $kontakt->WyslijMailaKontakt(); // Możesz tutaj ustawić inny adres e-mail, jeśli potrzebujesz
} else {
    // Wywołanie metody wyświetlającej formularz kontaktowy
    $kontakt->PokazKontakt();
    
    // Wywołanie metody wyświetlającej formularz przypomnienia hasła
    $kontakt->PrzypomnijHaslo();
}
?>
