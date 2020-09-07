<?php
require_once '../config/pdo.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_GET['hash']) && $_GET['hash']) {
    $stmt = $pdo->prepare('SELECT user_id, confirm FROM Users WHERE hash = :hs'); /* имя еще проверять? через хеш? */
    $stmt->execute(array(':hs' => $_GET['hash']));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['confirm'] == 'no') {
            $stmt = $pdo->prepare('UPDATE Users SET confirm = :cf WHERE user_id = :uid');
            $stmt->execute(array(
                ':cf' => 'yes',
                ':uid' => $row['user_id']
            ));
            $_SESSION['success'] = "Email address confirm.";
        } elseif ($row['confirm'] == 'yes')
            $_SESSION['success'] = "Your email address already confirmed.";
    } else
        $_SESSION['error'] = "Email address confirm error.";
    header('Location: ../index.php');
    return;
}
