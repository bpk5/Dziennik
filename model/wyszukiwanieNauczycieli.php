<?php

/**
 * Wyszukuje nauczycieli w bazie i wstawia do tabelli $daneBaza.
 * Po wyszukaniu na koniec zostaje ustawiony komunikat['flaga'] na '1'.
 * 
 * @author Bartłomiej Kulesa
 */
class WyszukiwanieNauczycieli {

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
     * $daneBaza[][0] - idnauczyciela
     * $daneBaza[][1] - imie,
     * $daneBaza[][2] - nazwisko,
     * $daneBaza[][3] - pesel,
     * $daneBaza[][4] - klasa,
     */
    public function wyszukajNauczycieli() {

        try {
            $resultQuery = $this->connection->query("SELECT n.idnauczyciela, n.imie, n.nazwisko, n.aktywny, n.pesel, k.nazwa FROM nauczyciele n, klasy k WHERE n.idklasy=k.idklasy AND n.aktywny=1 ORDER BY n.nazwisko, n.imie");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania. (wyszukaj nauczycieli)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $resultQuery->num_rows;

            if ($ile > 0) {

                $i = 0;
                while ($daneOsoby = $resultQuery->fetch_array()) {
                    $this->daneBaza[$i][0] = $daneOsoby['idnauczyciela'];
                    $this->daneBaza[$i][1] = $daneOsoby['imie'];
                    $this->daneBaza[$i][2] = $daneOsoby['nazwisko'];
                    $this->daneBaza[$i][3] = $daneOsoby['pesel'];
                    $this->daneBaza[$i][4] = $daneOsoby['nazwa'];
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
