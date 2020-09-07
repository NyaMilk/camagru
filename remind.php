<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'util.php';

if (isset($_POST['submit']) && $_POST['submit'] === 'Send') {
    $stmt = $pdo->prepare('SELECT name, email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $_POST['remind-email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        $_SESSION['error'] = 'Email address not found';
        header('Location: remind.php');
        return;
    }
    $page = 'remind.php';
    sendNotification($row['email'], $row['name'], $page);
    $_SESSION['success'] = 'Send'; /* написать нормально */
    header('Location: index.php');
    return;
}

if (isset($_POST['submit']) && $_POST['submit'] === 'Set new password') {
    /* прописать проверки как в index / проверки вынести в util */
    $salt = 'XyZzy12*_';
    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE name = :nm');
    $stmt->execute(array(':ps' => hash('sha512', $salt . $_POST['reset-pass']), ':nm' => $_GET['name']));
    $_SESSION['success'] = 'Password reset'; /* update / подумать бы над сообщениями */
    header('Location: index.php');
    return;
}

require_once 'components/header.php';
if (isset($_GET['name']))
    require_once "components/reset-pass.php";
else
    require_once "components/remind-page.php";
require_once "components/footer.php";

flashMessages();
