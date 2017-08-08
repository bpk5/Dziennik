<?php

/**
 * Odpina przedmiot od nauczyciela.
 * Odpięcie polega na zmianie w bazie aktywny na 0.
 * Wszystkie wiersze gdzie aktywny jest ustawiony na 0 traktujemy jako usunięte.
 */
class OdpinaniePrzedmiotu {
    
    private $connection;
    private $komunikat;

    /**
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        
        $this->komunikat['ogolny'] = '';
    }
    
    /**
     * Ustawia w wierszu aktywny na 0,
     * 
     * @param int $idnauczprzedm
     * @throws Exception
     */
    public function odepnijPrzedmiot($idnauczprzedm) {

        try {
            if ($idnauczprzedm == NULL) {
                $this->komunikat['ogolny'] = 'Brak rekordów do usunięcia.';
                $q1 = TRUE;
            }
            else {
                $q1 = $this->connection->query("UPDATE naucz_przedm SET aktywny=0 WHERE idnauczprzedm=$idnauczprzedm");
                $this->komunikat['ogolny'] = 'Odpięto przedmiot i klasę od nauczyciela.';
            }
            
            if (!$q1) {
                throw new Exception('Nie można wykonać zapytania. (odepnij przedmiot)');
            }
            
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }

    }

    /**
     * 
     * @return array
     */
    public function getKomunikat() {
        return $this->komunikat;
    }
}
