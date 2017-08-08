<?php

/**
 * Wykonuje połączenie z bazą danych.
 * 
 * Uwaga! Nie zapomnij wywołać metody close() zamykającej połączenie.
 *
 * @author Bartłomiej Kulesa
 */
class PolaczenieDB {

    private $host;
    private $dbUser;
    private $dbPassword;
    private $dbName;
    private $connection;

    /**
     * 
     * @param string $host - nazwa hosta.
     * @param string $dbUser - nazwa użytkownika bazy danych.
     * @param string $dbPassword - hasło do bazy danych.
     * @param string $dbName - nazwa bazy danych.
     */
    public function __construct($host, $dbUser, $dbPassword, $dbName) {
        $this->host = $host;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }
    
    /**
     * Metoda zwraca wartość pola $connection, jest to połączenie z bazą.
     * @return  zwraca pole connection (połączenie z bazą).
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Połączenie z serwerem mysql.
     */
    public function connect() {
        
        try {
            $this->connection = @new mysqli($this->host, $this->dbUser, $this->dbPassword, $this->dbName);
            
            if ($this->connection->connect_errno != 0) {
                throw new Exception('Nie można połączyć się z bazą!');
            }

            // Ustawienie kodowania.
            if (!$this->connection->set_charset("utf8")) {
                throw new Exception('Nie można ustawić kodowania.');
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }

    }
    
    /**
     * Zamknięcie połączenia z bazą. NIE ZAPOMNIJ :)
     */
    public function close() {
        $this->connection->close();
    }
}

?>