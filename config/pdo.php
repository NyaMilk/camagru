<?php
$DB_DSN = 'mysql:host=127.0.0.1;dbname=camagru';
$DB_USER = 'super';
$DB_PASSWORD = '1234';

try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error connecting database: ' . $e->getMessage() . "\n");
}
