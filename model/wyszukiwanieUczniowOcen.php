<?php

/**
 * Wyszukuje uczniów + oceny.
 * @author Bartłomiej Kulesa
 */
class WyszukiwanieUczniowOcen {

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
     * $daneBaza[][3] - idlasy,
     * $daneBaza[][4] - iducznia,
     * $daneBaza[][5] - string z ocenami
     * 
     * @param string $klasa
     * @param int $idprzedmiotu 
     */
    public function wyszukajOcenyUczniow($klasa, $idprzedmiotu) {

        try {
            $uczniowie = $this->connection->query("SELECT * FROM uczniowie WHERE idklasy=(SELECT idklasy FROM  klasy WHERE nazwa = '$klasa') AND aktywny=1 ORDER BY nazwisko, imie");

            if (!$uczniowie) {
                throw new Exception('Bład zapytania. (wyszukaj uczniów)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $uczniowie->num_rows;

            if ($ile > 0) {

                $i = 0;
                while ($daneOsoby = $uczniowie->fetch_array()) {
                    $this->daneBaza[$i][0] = $daneOsoby['imie'];
                    $this->daneBaza[$i][1] = $daneOsoby['nazwisko'];
                    $this->daneBaza[$i][2] = $daneOsoby['pesel'];
                    $this->daneBaza[$i][3] = $daneOsoby['idklasy'];
                    $this->daneBaza[$i][4] = $daneOsoby['iducznia'];
                    $this->daneBaza[$i][5] = '';                        // oceny

                    $this->pobierzOceny($daneOsoby['iducznia'], $idprzedmiotu, $i);
                    $i++;
                }

                $uczniowie->free_result();
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
    }

    /**
     * Pobiera oceny z bazy i jako string wstawia do tablicy.
     * Między ocenami wstawiany jest fragment formularza (radio).
     * 
     * @param int $iducznia
     * @param int $idprzedmiotu
     * @param int $ii liczba mówiąca o tym do jakiej komórki przypisywać oceny.
     * @throws Exception
     */
    private function pobierzOceny($iducznia, $idprzedmiotu, $ii) {

        try {
            $oceny = $this->connection->query("SELECT ocena, idoceny FROM oceny WHERE iducznia=$iducznia AND idprzedmiotu=$idprzedmiotu AND aktywny=1");

            if (!$oceny) {
                throw new Exception('Bład zapytania. (wyszukiwanieUczniowOcen pobierzOceny)');
            }

            // Sprawdzenie ile jest rezultatów zapytania.
            $ile = $oceny->num_rows;

            if ($ile > 0) {

                $i = 0;
                $oc = '';
                while ($daneOsoby = $oceny->fetch_array()) {
                    $idoceny = $daneOsoby['idoceny'];
                    $oc .= $daneOsoby['ocena'];
                    $oc .= ' <input type="radio" name="usunOcene" value="' . $idoceny . '">';
                    $i++;
                }
                $this->daneBaza[$ii][5] = $oc;
                $oceny->free_result();
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
