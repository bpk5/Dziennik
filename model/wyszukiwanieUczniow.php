<?php

/**
 * Wyszukuje uczniów w bazie i wstawia do tabelli $daneBaza.
 * Po wyszukaniu uczniów na koniec zostaje ustawiony komunikat['flaga'] na '1'.
 */
class WyszukiwanieUczniow {

    private $daneBaza;
    private $komunikat;

    /**
     * 
     * @param string $klasa nazwa klasy/
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        //$this->klasa = $klasa;

        $this->komunikat['ogolny'] = '';
        $this->komunikat['flaga'] = '0';
    }

    /**
     * Wyszukuje uczniów na podstawie klasy (szkolnej).
     * Dane zostają wstawione do tabeli daneBaza[][].
     * $daneBaza[][0] - imie,
     * $daneBaza[][1] - nazwisko,
     * $daneBaza[][2] - pesel,
     * $daneBaza[][3] - idklasy,
     */
    public function wyszukajUczniow($klasa) {

        try {
            $resultQuery = $this->connection->query("SELECT * FROM uczniowie WHERE idklasy=(SELECT idklasy FROM  klasy WHERE nazwa = '$klasa') AND aktywny=1 ORDER BY nazwisko, imie");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania. (wyszukaj uczniów)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $resultQuery->num_rows;

            if ($ile > 0) {

                $i = 0;
                while ($daneOsoby = $resultQuery->fetch_array()) {
                    $this->daneBaza[$i][0] = $daneOsoby['imie'];
                    $this->daneBaza[$i][1] = $daneOsoby['nazwisko'];
                    $this->daneBaza[$i][2] = $daneOsoby['pesel'];
                    $this->daneBaza[$i][3] = $daneOsoby['idklasy'];
                    $i++;
                }

                $resultQuery->free_result();
                $this->komunikat['flaga'] = '1';
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
    }

    /**
     * @return array[]
     */
    public function getKomunikat() {
        return $this->komunikat;
    }

    /**
     * @return array[][]
     */
    public function getDaneBaza() {
        return $this->daneBaza;
    }

}
