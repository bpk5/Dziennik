<?php

/**
 * Klasa do 'usuwania' osoby z bazy. Uwaga osoba nie jest usuwana tylko
 * zmieniane jest pole 'aktywny' z 1 na 0.
 * Wszystkie rekordy z 'aktywny' = 0 traktujemy jako usunięte.
 * Zmiany dokonujemy na dwóch tabelach: uczniowie i uzytkownicy lub nauczyciele i uzytkownicy.
 *
 * @author Bartłomiej Kulesa
 */
class UsuwanieOsoby {

    private $connection;
    private $komunikat;

    /**
     * 
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        
        $this->komunikat['ogolny'] = '';
    }

    /**
     * Metoda ustawia flagę kolumny 'aktywny' na 0.
     * Rekordy nie są kasowane tylko zmieniam flagę.
     * 
     * @param int $iduzytkownika        id użytkownika
     * @param string $tabela            podajemy nazwę tabeli w jakiej robimy update, Uwaga tylko tabela:
     *                                      - uczniowie,
     *                                      - nauczyciele,
     * @throws Exception
     */
    public function usunOsobe($iduzytkownika, $tabela) {

        try {
            $this->connection->begin_transaction();
            $q1 = $this->connection->query("UPDATE $tabela SET aktywny=0 WHERE iduzytkownika='$iduzytkownika'");
            $q2 = $this->connection->query("UPDATE uzytkownicy SET aktywny=0 WHERE iduzytkownika='$iduzytkownika'");

            // Zmiana tylko gdy usuwanie dotyczy nauczyciela.
            $q3 = TRUE;
            // Wykonuje tylko dla nauczycieli
            if ($tabela == 'nauczyciele') {
                $q3 = $this->connection->query("UPDATE naucz_przedm SET aktywny=0 WHERE idnauczyciela=(SELECT idnauczyciela FROM nauczyciele WHERE iduzytkownika=$iduzytkownika)");
            }

            if (!$q1 or !$q2 or !$q3) {
                throw new Exception('Nie można wykonać zapytania. (usun osobę)');
            }
            else {
                $this->connection->commit();
            }
        }
        catch (Exception $ex) {
            // Wycofanie zapytań, jest jakiś błąd.
            $this->connection->rollback();
            echo $ex->getMessage();
            exit();
        }

        $this->komunikat['ogolny'] = 'Dane o osobie zostały usunięte.';
        $this->komunikat['flaga'] = '0';
    }

    /**
     * 
     * @return array
     */
    public function getKomunikat() {
        return $this->komunikat;
    }
}

?>
