#!/usr/bin/php
<?php
include_once 'pdo.php';

// exec('sudo mysql < config/add_user.sql');
try {
    if ($sql = file_get_contents('config/dump.sql')) {
        $pdo->exec($sql);
        echo "Database created successfully\n";
    }
} catch (PDOException $e) {
    die('Error creating database: ' . $e->getMessage() . "\n");
}

// PDO::setAttribute — Установка атрибута объекту PDO
// PDO::ATTR_ERRMODE: Режим сообщений об ошибках.
// PDO::ERRMODE_EXCEPTION: Выбрасывать исключения.
