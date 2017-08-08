<?php

/**
 * Dodaje i usuwa oceny z bazy.
 * 
 * @author Bartłomiej Kulesa
 */
class dodawanieUsuwanieOceny {

    private $komunikat;

    /**
     * @param string $klasa nazwa klasy/
     * @param object $connection uchwyt do bazy.
     */
    public function __construct($connection) {
        $this->connection = $connection;
        $this->komunikat['ogolny'] = '';
    }

    /**
     * Dodaje ocenę do bazy danych.
     * 
     * @param int $iducznia
     * @param int $idnauczyciela
     * @param int $idprzedmiotu
     * @param string $ocena
     * @throws Exception
     */
    public function dodajOcene($iducznia, $idnauczyciela, $idprzedmiotu, $ocena) {
        
        try {
            $resultQuery = $this->connection->query("INSERT INTO oceny
                (iducznia, idnauczyciela, idprzedmiotu, ocena) 
                VALUE   ($iducznia, $idnauczyciela, $idprzedmiotu, '$ocena')");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania (dodaj ocenę).');
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }

        $this->komunikat['ogolny'] = 'Dodano ocenę.';
    }
    
    /**
     * Ustawia w bazie aktywny na 0.
     * Wszystkie z aktywny 0 traktujemy jako usunięte.
     * 
     * @param int $idoceny
     * @throws Exception
     */
    public function usunOcene($idoceny) {
        try {
            $resultQuery = $this->connection->query("UPDATE oceny SET aktywny=0 WHERE idoceny='$idoceny'");

            if (!$resultQuery) {
                throw new Exception('Bład zapytania (usuń ocenę).');
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage(); // Błąd zapytania.
            exit();
        }

        $this->komunikat['ogolny'] = 'Usunięto ocenę.';
    }

    /**
     * Zwraca tablicę asocjacyjną z komunikatami.
     * @return array[]
     */
    public function getKomunikat() {
        return $this->komunikat;
    }

}
