<?php

/**
 * Przypisuje przedmiot i klasę do nauczyciela.
 *
 * @author bpk5
 */
class PrzypisywaniePrzedmiotu {

    private $connection;
    private $komunikat;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->komunikat['ogolny'] = '';
        $this->komunikat['flaga'] = '0';
    }

    /**
     * Sprawdza czy można przypisać przedmiot i klasę do nauczyciela.
     * Sprawdzenie polega na sprawdzeniu czy istnieje już w bazie przypisana
     * klasa i przedmiot do jakiegoś  nauczyciela. Dodatkowo w kolumnie
     * aktywny musi być 1.
     * 
     * @param string $klasa
     * @param string $przedmiot
     * @throws Exception
     */
    public function sprawdzMozliwoscPrzypisania($klasa, $przedmiot) {
        $zapytanie = "SELECT * FROM naucz_przedm
                    WHERE
                    idprzedmiotu=(SELECT idprzedmiotu FROM przedmioty WHERE nazwa='$przedmiot')
                    AND idklasy=(SELECT idklasy FROM klasy WHERE nazwa='$klasa')
                    AND aktywny=1";

        try {
            $resultQuery = $this->connection->query($zapytanie);

            if (!$resultQuery) {
                throw new Exception('Bład zapytania. (wyszukaj uczniów)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $resultQuery->num_rows;

            if ($ile > 0) {
                $this->komunikat['ogolny'] = 'Nie można przypisać. Przedmiot i klasa są już przypisane!';
                $resultQuery->free_result();
            }
            else {
                $this->komunikat['flaga'] = '1';
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
    }

    /**
     * Metoda przypisuje nauczycielowi klasę i przedmiot.
     * 
     * @param string $pesel
     * @param string $klasa
     * @param string $przedmiot
     * @throws Exception
     */
    public function dopiszPrzedmiot($pesel, $klasa, $przedmiot) {
                
        try {
            $resultQuery = $this->connection->query("INSERT INTO naucz_przedm
                (idnauczyciela, idprzedmiotu, idklasy) 
                VALUE   (
                (SELECT idnauczyciela FROM nauczyciele WHERE pesel='$pesel' AND aktywny=1),
                (SELECT idprzedmiotu FROM przedmioty WHERE nazwa='$przedmiot'),
                (SELECT idklasy FROM klasy WHERE nazwa='$klasa'))");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania (dopisz przedmiot).');
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
        
        $this->komunikat['ogolny'] = 'Przypisano nauczycielowi przedmiot i klasę.';
    }

    /**
     * @return array[] komunikat
     */
    public function getKomunikat() {
        return $this->komunikat;
    }

}
