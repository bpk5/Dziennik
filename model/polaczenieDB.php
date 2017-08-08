<?php

/**
 * Description of ZalogujPHPClass.
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
     * @param type $host - nazwa hosta.
     * @param type $dbUser - nazwa użytkownika bazy danych.
     * @param type $dbPassword - hasło do bazy danych.
     * @param type $dbName - nazwa bazy danych.
     */
    public function __construct($host, $dbUser, $dbPassword, $dbName) {
        $this->host = $host;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }
    
    /**
     * Metoda zwraca wartość pola $connection, jest to połączenie z bazą.
     * @return type - zwraca pole connection (połączenie z bazą).
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
                //throw new Exception(mysqli_connect_errno());
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
