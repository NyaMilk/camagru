<?php
require 'db.php';

class User {
    public $conn;

    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->connect();
    }

    private function query($sql, $arr) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($arr);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function ft_check_nm_pw($arr) {
        $sql = 'SELECT * FROM Users WHERE name = :nm AND password = :pw';
        $row = $this->query($sql, $arr);

        return $row;
    }

    public function ft_check_nm($arr) {
        $sql = 'SELECT name FROM Users WHERE name = :nm';
        $row = $this->query($sql, $arr);

        return $row;
    }

    public function ft_check_em($arr) {
        $sql = 'SELECT email FROM Users WHERE email = :em';
        $row = $this->query($sql, $arr);

        return $row;
    }

    public function ft_add($arr) {
        $sql = 'INSERT INTO Users (name, email, password, confirm) VALUES (:nm, :em, :ps, :cf)';
        $row = $this->query($sql, $arr);

        return $row;
    }
}

