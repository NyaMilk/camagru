<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['name']))
    header('Location: gallery.php?sort=all&page=1');

require_once 'util.php';

if (isset($_POST['submit'])) {
    $salt = 'XyZzy12*_';

    if ($_POST['submit'] === 'Sign In') {
        if (strlen($_POST['username']) == 0 || strlen($_POST['pass']) == 0) {
            $_SESSION['error'] = 'Username and password are required';
            header('Location: index.php');
            return;
        }
        $check = hash('sha512', $salt . $_POST['pass']);
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE name = :nm AND password = :pw');
        $stmt->execute(array(':nm' => $_POST['username'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['confirm'] = $row['confirm'];
            if ($row['confirm'] == 'no') {
                $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 1 DAY)');
                // $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 10 SECOND)');
                if ($stmt->rowCount()) {
                    $_SESSION['error'] = "TimeOut"; /* ошибку описать */
                    unset($_SESSION['name']);
                    unset($_SESSION['user_id']);
                    header('Location: index.php');
                    return;
                }
            }
            header('Location: gallery.php?sort=all&page=1');
            return;
        } else {
            $_SESSION['error'] = 'Incorrect username or password';
            header('Location: index.php');
            return;
        }
    }

    if ($_POST['submit'] === 'Sign Up') {
        if (strlen($_POST['username_up']) == 0 || strlen($_POST['email_up']) == 0 || strlen($_POST['pass_up']) == 0 || strlen($_POST['repass_up']) == 0) {
            $_SESSION['error'] = 'All values are required';
            header('Location: index.php');
            return;
        }

        $page = 'index.php';
        checkUserName($pdo, $page);

        $email = $_POST['email_up'];
        $subject = 'Confirm email address';
        $hash = md5($_POST['username_up'] . time());
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        // $headers .= "To: <$email>\r\n";
        // $headers .= "From: no-reply@example.com\r\n";
        // $headers .= "From: nyamilk@yandex.ru\r\n";
        $headers .= "From: amilyukovadev@gmail.com\r\n";
        $message = '<p>To complete the sign-up process please follow the <a href="http://localhost:8080/components/confirm.php?hash=' . $hash . '">link</a></p>';

        $stmt = $pdo->prepare('INSERT INTO Users (name, email, password, hash) VALUES (:nm, :em, :ps, :hs)');
        $stmt->execute(array(
            ':nm' => $_POST['username_up'],
            ':em' => $_POST['email_up'],
            ':ps' => hash('sha512', $salt . $_POST['pass_up']),
            ':hs' => $hash
        ));
        $_SESSION['success'] = 'Profile added. You need to confirm the email address.';
        
        mail($email, $subject, $message, $headers); /* проверка на ошибку? */

        header('Location: index.php');
        return;
    }
}

require_once 'components/header.php';
require_once 'components/login.php';
require_once 'components/footer.php';

flashMessages();
