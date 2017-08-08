<?php

/**
 * Klasa odpowiada za dodanie osoby do bazy.
 * Weryfikuje pola formularza, dodaje dane do bazy.
 * Uwaga! klasa potrzebuje połączenia z bazą.
 * Dane dodawane są do dwóch tabel: uczniowie i uzytkownicy lub nauczyciele i uzytkownicy.
 *
 * @author Bartłomiej Kulesa
 */
class DodawanieOsoby {

    private $connection;
    private $imie;
    private $nazwisko;
    private $pesel;
    private $klasa;
    private $login;
    private $haslo1;
    private $haslo2;
    private $hasloHash;
    private $komunikat;
    private $tabela;

    /**
     * 
     * @param object $connection połączenie z bazą
     * @param string $imie
     * @param string $nazwisko
     * @param string $pesel
     * @param string $klasa
     * @param string $login
     * @param string $haslo1
     * @param string $haslo2 
     */
    public function __construct($connection, $imie, $nazwisko, $pesel, $klasa, $login, $haslo1, $haslo2) {
        $this->connection = $connection;
        $this->imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
        $this->nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
        $this->pesel = htmlentities($pesel, ENT_QUOTES, "UTF-8");
        $this->klasa = $klasa;
        $this->login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $this->haslo1 = htmlentities($haslo1, ENT_QUOTES, "UTF-8");
        $this->haslo2 = htmlentities($haslo2, ENT_QUOTES, "UTF-8");

        $this->imie = mysqli_real_escape_string($this->connection, $this->imie);
        $this->nazwisko = mysqli_real_escape_string($this->connection, $this->nazwisko);
        $this->pesel = mysqli_real_escape_string($this->connection, $this->pesel);
        $this->login = mysqli_real_escape_string($this->connection, $this->login);
        $this->haslo1 = mysqli_real_escape_string($this->connection, $this->haslo1);
        $this->haslo2 = mysqli_real_escape_string($this->connection, $this->haslo2);

        $this->komunikat['imie'] = '';
        $this->komunikat['nazwisko'] = '';
        $this->komunikat['pesel'] = '';
        $this->komunikat['login'] = '';
        $this->komunikat['haslo'] = '';
        $this->komunikat['ogolny'] = '';
        $this->komunikat['wychowawca'] = '';

        $this->tabela['U'] = 'uczniowie';
        $this->tabela['N'] = 'nauczyciele';
    }

    /**
     * Sprawdzanie ile znaków ma imię. Nie może być dłuższe niż 25.
     * 
     * @return boolean TRUE ok, FALSE 2-25 znaków
     */
    public function weryfikacjaImie() {
        if ((strlen($this->imie) < 2) || (strlen($this->imie) > 25)) {
            $this->komunikat['imie'] = 'Błędnie wprowadzone imię.';
            return FALSE;  // Imie ma za mało lud za dużo znaków.
        }

        return TRUE; // Ok
    }

    /**
     * Sprawdzanie ile znaków ma nazwisko. Nie może być dłuższe niż 25.
     * 
     * @return boolean TRUE ok, FALSE 2-25 znaków
     */
    public function weryfikacjaNazwisko() {
        if ((strlen($this->nazwisko) < 2) || (strlen($this->nazwisko) > 25)) {
            $this->komunikat['nazwisko'] = 'Błędnie wprowadzone nazwisko.';
            return FALSE;  // Imie ma za mało lud za dużo znaków.
        }

        return TRUE; // Ok
    }

    /**
     * Sprawdzenie czy pesel ma 11 znaków, czy już istnieje w bazie.
     * 
     * @param char
     * 
     * @return boolean TRUE można dodać osobę o tym peselu.
     * @throws Exception
     */
    public function weryfikacjaPESEL($profilUN) {
        $result = TRUE;

        $tabela = $this->tabela[$profilUN];

        if (strlen($this->pesel) != 11) {
            $this->komunikat['pesel'] = 'Pesel powinien posiadać 11 znaków.';
            $result = FALSE;   // Pesel nie ma 11 znaków.
        }
        else {
            try {
                $resultQuery = $this->connection->query("SELECT * FROM $tabela WHERE pesel = '$this->pesel' AND aktywny = 1");

                if (!$resultQuery) {
                    throw new Exception('Nie można wykonać zapytania do bazy. (Weryfikacja PESEL)');
                }

                // Sprawdzenie ile jest rezultatów zapytania.
                $howUsers = $resultQuery->num_rows;

                // Sprawdzenie czy coś znaleziono
                if ($howUsers > 0) {
                    $this->komunikat['pesel'] = 'Osoba o podanym peselu jest już w bazie.';
                    $result = FALSE;
                    // Czyszczenie rezultatu zapytania!!!
                    $resultQuery->free_result();
                }
            }
            catch (Exception $ex) {
                echo $ex->getMessage();
                exit();
            }
        }

        return $result;
    }

    /**
     * Sprawdza czy wybrana klasa istnieje już w bazie.
     * Jeżeli istnieje nie można jej przypisać kolejny raz.
     * Jedna klasa nie może posiadać kilku wychowawców.
     * @return boolean TRUE - można dodać do bazy
     * @throws Exception
     */
    public function weryfikacjaWychowawcy() {
        $result = TRUE;
        try {
            $resultQuery = $this->connection->query("SELECT * FROM nauczyciele WHERE idklasy=(SELECT idklasy FROM klasy WHERE nazwa='$this->klasa') AND aktywny=1");

            if (!$resultQuery) {
                throw new Exception('Nie można wykonać zapytania do bazy. (Weryfikacja wychowawcy)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $howUsers = $resultQuery->num_rows;

            // Sprawdzenie czy coś znaleziono
            if ($howUsers > 0) {
                $this->komunikat['wychowawca'] = 'Podana klasa ma już wychowawcę.';
                $result = FALSE;
                // Czyszczenie rezultatu zapytania!!!
                $resultQuery->free_result();
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }

        return $result;
    }

    /**
     * Sprawdza czy login ma od 3 do 20 znaków,
     * czy są to znaki dozwolone,
     * czy login jest już w bazie.
     * 
     * @return boolean TRUE - można dodać do bazy.
     * @throws Exception
     */
    public function weryfikacjaLoginu() {
        $result = TRUE;

        if ((strlen($this->login) < 3) || (strlen($this->login) > 20)) {
            $this->komunikat['login'] = 'Login musi posiadać od 3 do 20 znaków.';
            $result = FALSE;    // Login musi posiadać od 3 do 20 znaków.
        }
        else if (ctype_alnum($this->login) == FALSE) {
            $this->komunikat['login'] = 'Login może skłądać się tylko z liter i cyfr (bez polskich znaków)';
            $result = FALSE; // Login może składać się tylko z liter i cyfr(bez polskich znaków).
        }
        else {
            try {
                $resultQuery = $this->connection->query("SELECT * FROM uzytkownicy WHERE login='$this->login' AND aktywny = 1");

                if (!$resultQuery) {
                    throw new Exception('Nie można wykonać zapytania do bazy. (Weryfikacja loginu)');
                }

                // Sprawdzenie ile jest rezultatów zapytania.
                $howUsers = $resultQuery->num_rows;

                // Sprawdzenie czy coś znaleziono
                if ($howUsers > 0) {
                    $this->komunikat['login'] = 'Osoba o podanym loginie jest już w bazie.';
                    $result = FALSE;
                    // Czyszczenie rezultatu zapytania!!!
                    $resultQuery->free_result();
                }
            }
            catch (Exception $ex) {
                echo $ex->getMessage();
                exit();
            }
        }
        return $result;
    }

    /**
     * Sprawdzanie czy hasło ma odpowiednią długość 8 - 20 znaków.
     * Porównuje dwa hasła czy są tej samej długości.
     * Hashuje hasło i zapisuje w polu $hasloHash.
     * 
     * @return boolean TRUE - można dodać do bazy.
     */
    public function weryfikacjaHasla() {
        $result = TRUE;

// Sprawdzenie ilości znaków.
        if ((strlen($this->haslo1) < 8) || (strlen($this->haslo2) > 20)) {
            $this->komunikat['haslo'] = 'Hasło musi posiadać od 8 do 20 znaków.';
            $result = FALSE; // Hasło musi posiadać od 8 do 20 znaków!
        }

// Porównanie haseł.
        if ($this->haslo1 != $this->haslo2) {
            $this->komunikat['haslo'] = 'Podane hasła nie są identyczne.';
            $result = FALSE; // Podane hasła nie są identyczne.
        }

// Hashowanie haseł
        $this->hasloHash = password_hash($this->haslo1, PASSWORD_DEFAULT);

        return $result;
    }

    /**
     * 
     * @param char $profil  profil osoby dodanej do bazy (U, N)
     * @param string $tabela 
     * @throws Exception
     */
    public function dodajOsobe($profilUN) {
        $tabela = $this->tabela[$profilUN];
        try {

            $this->connection->begin_transaction();

            $q1 = $this->connection->query("INSERT INTO uzytkownicy (login, haslo, profil) VALUES ('$this->login', '$this->hasloHash', '$profilUN')");
            $q2 = $this->connection->query("INSERT INTO $tabela (imie, nazwisko, pesel, idklasy, iduzytkownika) VALUES ('$this->imie', '$this->nazwisko', '$this->pesel', (SELECT idklasy FROM klasy WHERE nazwa = '$this->klasa'), (SELECT iduzytkownika FROM uzytkownicy WHERE login = '$this->login' AND aktywny = 1))");

            if (!$q1 or ! $q2) {
                throw new Exception('Błąd zapytania do bazy (dodawanie ucznia).');
            }
            else {
                $this->connection->commit();
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
// Wycofanie zapytań, jest jakiś błąd.
            $this->connection->rollback();
            exit();
        }

        $this->komunikat['ogolny'] = 'Dodano osobę ' . $this->imie . ' ' . $this->nazwisko . ' do bazy danych.';
    }

    /**
     * @return array tablica z komunikatami.
     */
    public function getKomunikat() {
        return $this->komunikat;
    }

}
