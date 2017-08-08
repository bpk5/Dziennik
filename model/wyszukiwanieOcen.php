<?php

/**
 * Wyszukuje oceny w bazie i wstawia do tabelli $daneBaza.
 * 
 * @author Bartłomiej Kulesa
 */
class WyszukiwanieOcen {

    private $daneBaza;

    /**
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        // Utworzenie tablicy.
        for ($i = 0; $i < 6; $i++) {
            $this->daneBaza[$i][0] = '';
        }
    }

    /**
     * Pobiera z bazy oceny.
     * Ocenay wstawiane są do tablicy[][] ([przedmiot][oceny])
     * @param int $iducznia
     * @throws Exception
     */
    public function wyszukajOceny($iducznia) {

        try {

            for ($i = 0; $i < 6; $i++) {
                $oceny[$i] = $this->connection->query("SELECT * FROM oceny WHERE iducznia=$iducznia AND idprzedmiotu=($i + 1) AND aktywny=1");
            }

            if (!$oceny[0] or ! $oceny[1] or ! $oceny[2] or ! $oceny[3] or ! $oceny[4] or ! $oceny[5]) {
                throw new Exception('Bład zapytania. (wyszukaj oceny)');
            }

            $this->tworzTablice($oceny);
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
    }

    private function tworzTablice($oceny) {
        for ($i = 0; $i < 6; $i++) {
            $j = 0;
            while ($ocena = $oceny[$i]->fetch_array()) {
                $this->daneBaza[$i][$j] = $ocena['ocena'];
                $j++;
            }

            $oceny[$i]->free_result();
        }
    }

    /**
     * @return array[][]
     */
    public function getDaneBaza() {
        return $this->daneBaza;
    }

}
