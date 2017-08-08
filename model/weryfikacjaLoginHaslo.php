<?php

/**
 * Klasa sprawdza czy w bazie występuje podany login i hasło.
 * Jeżeli znajdzie ustawia zmienne sesyjne profil i iduzytkownika.
 * Do wykonania zapytania potrzebne jest połączenie z bazą danych.
 * 
 * @author Bartłomiej Kulesa
 */
class WeryfikacjaLoginHaslo {

    private $connection;
    private $login;
    private $password;
    private $idAndProfile;  // iduzytkownika i profil z bazy(ale tylko jeżeli logowanie się powiedzie)

    /**
     * @param object $connection - połączenie z bazą (wykonuje klasa PolaczenieDB)
     * @param string $login
     * @param string $password
     */

    public function __construct($connection, $login, $password) {
        $this->connection = $connection;

        $this->login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $this->password = htmlentities($password, ENT_QUOTES, "UTF-8");

        $this->login = mysqli_real_escape_string($this->connection, $this->login);
        $this->password = mysqli_real_escape_string($this->connection, $this->password);

        $this->idAndProfile['iduzytkownika'] = "";
        $this->idAndProfile['profil'] = "L";
    }

    /**
     * Metoda sprawdza czy wprowadzony login i hasło są w bazie danych.
     * Jeżeli zostały znalezione pobiera cały wiersz z bazy
     * i zapisuje go do pola $line[] (tablicy asocjacyjnej).
     * Następnie ustawia zmienne sesyjne profil i iduzytkownika.
     * 
     * @return string komunikat o błędzie
     * 
     * @throws Exception: Jeżeli wystąpił błąd zapytania.
     */
    public function zaloguj() {

        $komunikat = "";
        // Zapytanie do bazy
        try {
            $result = $this->connection->query(sprintf("SELECT * FROM uzytkownicy WHERE login='%s' AND aktywny = 1", $this->login));

            if (!$result) {
                throw new Exception('Błąd zapytania przy sprawdzaniu loginu i hasła.');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $howUsers = $result->num_rows;

            // Sprawdzenie czy znaleziono jakiegoś uzytkownika.
            if ($howUsers > 0) {
                $line = $result->fetch_assoc();
                // Czyszczenie rezultatu zapytania!!!
                $result->free_result();

                // Sprawdzenie czy hasło jest zgodne.
                if (password_verify($this->password, $line['haslo'])) {
                    // Przypisanie wartości tablicy danymi z bazy danych.
                    $this->idAndProfile['iduzytkownika'] = $line['iduzytkownika'];
                    $this->idAndProfile['profil'] = $line['profil'];

                    if (($this->idAndProfile['profil'] == 'U') or ( $this->idAndProfile['profil'] == 'N')) {
                        $this->pobierzID();
                    }
                }
                else {
                    $komunikat['ogolny'] = 'Nieprawidłowy login lub hasło.';
                }
            }
            else {
                $komunikat['ogolny'] = 'Nieprawidłowy login lub hasło.';
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }

        // Profil można zmienić tylko kiedy jest L, czyli nikt nie jest zalogowany.
        if ($_SESSION['profil'] == 'L') {
            $_SESSION['profil'] = $this->idAndProfile['profil'];
            $_SESSION['iduzytkownika'] = $this->idAndProfile['iduzytkownika'];
        }

        return $komunikat;
    }

    protected function pobierzID() {
        $iduzytkownika = $this->idAndProfile['iduzytkownika'];
        try {
            if ($this->idAndProfile['profil'] == 'N') {
                $result = $this->connection->query("SELECT idnauczyciela id, imie, nazwisko FROM nauczyciele WHERE iduzytkownika='$iduzytkownika' AND aktywny = 1");
            }
            else {
                $result = $this->connection->query("SELECT iducznia id, imie, nazwisko FROM uczniowie WHERE iduzytkownika=$iduzytkownika AND aktywny = 1");
            }

            if (!$result) {
                throw new Exception('Błąd zapytania (pobieranie informacji o id nauczyciela lub ucznia).');
            }

            $line = $result->fetch_assoc();
            $_SESSION['id'] = $line['id'];
            $_SESSION['imie'] = $line['imie'];
            $_SESSION['nazwisko'] = $line['nazwisko'];

            $result->free_result();
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

}
