<?php
class Database
{
    private const DSN = 'mysql:host=localhost;dbname=camagru';
    private const USER = 'super';
    private const PASSWORD = '1234';
    public $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(Database::DSN, Database::USER, Database::PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error connecting database: ' . $e->getMessage() . "\n");
            
        }
        return $this->conn;
    }
}
