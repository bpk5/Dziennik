<?php

/**
 * Wyszukuje nauczycieli w bazie i wstawia do tabelli $daneBaza.
 * Po wyszukaniu uczniów na koniec zostaje ustawiony komunikat['flaga'] na '1'.
 * 
 * @author Bartłomiej Kulesa
 */
class WyszukiwanieNauczSzczegolowe {

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
     * $this->daneBaza[][0] = imie,
     * $this->daneBaza[][1] = nazwisko,
     * $this->daneBaza[][2] = pesel,
     * $this->daneBaza[][3] = wychowawca,
     * $this->daneBaza[][4] = klasa,
     * $this->daneBaza[][5] = przedmiot,
     * $this->daneBaza[][6] = idnauczprzedm,
     * $this->daneBaza[][7] = idnauczyciela,
     * $this->daneBaza[][8] = idprzedmiotu,
     * $this->daneBaza[][9] = idklasy
     */
    public function wyszukajNauczyciela($idnauczyciela) {

        try {
            $resultQuery = $this->connection->query("SELECT n.imie, n.nazwisko, n.pesel, k.nazwa wychowawca, n.idnauczyciela FROM nauczyciele n, klasy k WHERE n.idklasy=k.idklasy AND n.idnauczyciela=$idnauczyciela AND n.aktywny=1");
            
            $resultQuery2 = $this->connection->query("SELECT p.nazwa przedmiot, k.nazwa klasa, np.idnauczprzedm, p.idprzedmiotu, k.idklasy
                    FROM naucz_przedm np, klasy k, przedmioty p
                    WHERE np.idprzedmiotu=p.idprzedmiotu
                    AND np.idklasy=k.idklasy AND np.idnauczyciela=$idnauczyciela
                    AND np.aktywny=1
                    ORDER BY k.nazwa");

            if (!$resultQuery or !$resultQuery2) {
                throw new Exception('Bład zapytania. (wyszukaj szczegolowe info o nauczycielu)');
            }
            
            $daneOsoby = $resultQuery->fetch_array();
            $danePrzedmioty = $resultQuery2->fetch_array();
            
            $this->daneBaza[0][0] = $daneOsoby['imie'];
            $this->daneBaza[0][1] = $daneOsoby['nazwisko'];
            $this->daneBaza[0][2] = $daneOsoby['pesel'];
            $this->daneBaza[0][3] = $daneOsoby['wychowawca'];
            $this->daneBaza[0][4] = $danePrzedmioty['klasa'];
            $this->daneBaza[0][5] = $danePrzedmioty['przedmiot'];
            $this->daneBaza[0][6] = $danePrzedmioty['idnauczprzedm'];
            $this->daneBaza[0][7] = $daneOsoby['idnauczyciela'];
            $this->daneBaza[0][8] = $danePrzedmioty['idprzedmiotu'];
            $this->daneBaza[0][9] = $danePrzedmioty['idklasy'];
            
            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $resultQuery2->num_rows;

            if ($ile > 0) {

                $i = 1;
                while ($danePrzedmioty = $resultQuery2->fetch_array()) {
                    $this->daneBaza[$i][0] = '';
                    $this->daneBaza[$i][1] = '';
                    $this->daneBaza[$i][2] = '';
                    $this->daneBaza[$i][3] = '';
                    $this->daneBaza[$i][4] = $danePrzedmioty['klasa'];
                    $this->daneBaza[$i][5] = $danePrzedmioty['przedmiot'];
                    $this->daneBaza[$i][6] = $danePrzedmioty['idnauczprzedm'];
                    $this->daneBaza[$i][7] = $daneOsoby['idnauczyciela'];
                    $this->daneBaza[$i][8] = $danePrzedmioty['idprzedmiotu'];
                    $this->daneBaza[$i][9] = $danePrzedmioty['idklasy'];
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
