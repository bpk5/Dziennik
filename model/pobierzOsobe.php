<?php

/**
 * Pobiera dane o osobie, umieszcza je w tablicy asocjacyjnej $daneBaza.
 * 
 * @author Bartłomiej Kulesa
 */
class PobierzOsobe {
    
    private $connection;
    private $daneBaza;
    private $komunikat;

    /**
     * 
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        
        $this->komunikat['ogolny'] = '';

        $this->daneBaza['imie'] = '';
        $this->daneBaza['nazwisko'] = '';
        $this->daneBaza['pesel'] = '';
        $this->daneBaza['iduzytkownika'] = '';
        $this->komunikat['flaga'] = '0';
    }
    
    /**
     * Metoda wyszukuje rekord o danym peselu i tylko z polem aktywny o wartości 1.
     * Uwaga musimy podać z jakiej tabeli mają być wyszukane informacje.
     * Uwaga wyszukujemy tylko w tabeli uczniowie lub nauczyciele.
     * Wyszukana dane ustawiane są w tabeli asocjacyjnej.
     * Jeżeli wyszukano osobę falga w tablicy zostaje ustawiona na 1.
     * 
     * @param string $tabela nazwa tabeli z jakiej ma wyszukiwać
     * @param string $pesel pesel osoby której dane wyszukujemy
     * 
     * @return bool $result TRUE - wyszukano, FALSE - brak rekordów
     */
    public function wyszukajOsobe($tabela, $pesel) {
        $pesel = htmlentities($pesel, ENT_QUOTES, "UTF-8");
        $pesel = mysqli_real_escape_string($this->connection, $pesel);
        
        $result = TRUE;
        
        try {
            $resultQuery = $this->connection->query("SELECT * FROM $tabela WHERE pesel = '$pesel' AND aktywny = 1");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania. (wyszukaj osobę)');
            }
            else if ($resultQuery->num_rows == 0) {
                $this->komunikat['ogolny'] = 'W bazie nie ma osoby o podanym peselu.';
                $result = FALSE;
            }
            else {
                $dane = $resultQuery->fetch_assoc();
                $this->daneBaza['imie'] = $dane['imie'];
                $this->daneBaza['nazwisko'] = $dane['nazwisko'];
                $this->daneBaza['pesel'] = $dane['pesel'];
                $this->daneBaza['iduzytkownika'] = $dane['iduzytkownika'];
                $this->komunikat['flaga'] = '1';
                $resultQuery->free_result();
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }
        
        return $result;
    }
    
    public function getKomunikat() {
        return $this->komunikat;
    }
    
    public function getDaneBaza() {
        return$this->daneBaza;
    }
}
