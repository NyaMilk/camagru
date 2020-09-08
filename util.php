<?php
require_once 'config/pdo.php';

$salt = 'XyZzy12*_';

function flashMessages()
{
    if (isset($_SESSION['error'])) {
        echo '<script>alert("' . htmlentities($_SESSION['error']) . '");</script>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<script>alert("' . htmlentities($_SESSION['success']) . '");</script>';
        unset($_SESSION['success']);
    }
}

function checkLenInput($value, $msg)
{
    if (isset($_POST[$value]) && strlen($_POST[$value]) > 80) {
        $_SESSION['error'] = $msg . ' must be no more than 80 characters';
        return false;
    }
    return true;
}

function checkUserName($pdo, $page)
{
    if (checkLenInput('username_up', 'Username') == false) {
        header('Location: ' . $page);
        return false;
    }

    $stmt = $pdo->prepare('SELECT name FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_POST['username_up']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['error'] = 'That username is already taken';
        header('Location: ' . $page);
        return false;
    }
    if ($page == 'index.php')
        checkEmail($pdo, $page);
    return true;
}

function checkEmail($pdo, $page)
{
    if (!checkLenInput('email_up', 'Email')) {
        header('Location: ' . $page);
        return false;
    }

    $stmt = $pdo->prepare('SELECT email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $_POST['email_up']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['error'] = 'That email address is already taken';
        header('Location: ' . $page);
        return false;
    }
    if ($page == 'index.php')
        checkPassword($pdo, $page);
    return true;
}

function checkPassword($pdo, $page)
{
    if (!checkLenInput('repass_up', 'Password')) {
        header('Location: ' . $page);
        return false;
    }

    // if ((strlen($_POST['pass_up']) > 0 && strlen($_POST['pass_up'])) < 6
    // || (strlen($_POST['repass_up']) > 0 && strlen($_POST['repass_up']) < 6)) {
    //     $_SESSION['error'] = 'Password must be at least 6 characters long';
    //     header('Location: ' . $page);
    //     return;
    // }

    if ($page == 'index.php') {
        if ($_POST['pass_up'] != $_POST['repass_up']) {
            $_SESSION['error'] = 'Password do not match';
            header('Location: ' . $page);
            return false;
        }
    } else {
        $salt = 'XyZzy12*_';
        $stmt = $pdo->prepare('SELECT name FROM Users WHERE password = :ps');
        $stmt->execute(array(':ps' => hash('sha512', $salt . $_POST['pass_up'])));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $_SESSION['error'] = 'Wrong password';
            header('Location: ' . $page);
            return false;
        }
    }
    return true;
}

function deleteNotConfirmUser($pdo)
{
    $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 1 DAY)');
    // $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 10 SECOND)');
    if ($stmt->rowCount()) {
        $_SESSION['error'] = "TimeOut"; /* ошибку описать */
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['confirm']);
        return true;
    }
    return false;
}

function checkConfirmUser($pdo)
{
    $stmt = $pdo->prepare('SELECT confirm FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_SESSION['name']));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['confirm'] == 'yes') {
            $_SESSION['confirm'] = $row['confirm'];
            return true;
        }
        return false;
    }
}

function paginationList($pageName, $pages, $text = null)
{
    if (isset($_GET['user']))
        $getter = 'user=' . $_GET['user'];
    if (isset($_GET['sort']))
        $getter = 'sort=' . $_GET['sort'];

    echo '<div class="pagination">';
    if ($pages > 1 && isset($_GET['page'])) {
        if (!is_numeric($_GET['page'])) /* повторение, подумать - ворнинг */
            $_GET['page'] = 1;
        if ($_GET['page'] == 1) {
            $count = $_GET['page'] + 2;
            $i = $_GET['page'];
        } else {
            $count = $_GET['page'] + 1;
            $i = $_GET['page'] - 1;
        }
        if ($_GET['page'] == $pages)
            $i = $_GET['page'] - 2;
        if ($_GET['page'] > 1) {
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=1' . $text . '">&#171;</a>';
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . ($_GET['page'] - 1) . $text . '">&#8249;</a>';
        } else {
            echo '<p>&#171;</p>';
            echo '<p>&#8249;</p>';
        }
        for ($i; $i <= $count; $i++) {
            if ($i < 1)
                continue;
            if ($i <= $pages) {
                if ($i == $_GET['page'])
                    echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $i . $text . '" class="active">' . $i . '</a>';
                else
                    echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $i . $text . '">' . $i . '</a>';
            }
        }
        if ($_GET['page'] + 1 <= $pages) {
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . ($_GET['page'] + 1) . $text . '">&#8250;</a>';
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $pages . $text . '">&#187;</a>';
        } else {
            echo '<p>&#8250;</p>';
            echo '<p>&#187;</p>';
        }
    }
    echo '</div>';
}

function sendNotification($value, $elem, $page)
{
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: amilyukovadev@gmail.com\r\n";

    if ($page == 'index.php') {
        $email = $_POST[$value];
        $subject = 'Confirm email address';
        $message = '<p>To complete the sign-up process, please follow the <a href="http://localhost:8080/components/confirm.php?hash=' . $elem . '">link</a></p>.';
    } elseif ($page == 'remind.php') {
        $email = $value;
        $subject = 'Remind username and password';
        $message = '<p>Your username: ' . htmlentities($elem) . '</p>';
        $message .= '<p>To reset your password please follow the <a href="http://localhost:8080/remind.php?name=' . htmlentities($elem) . '">link</a></p>';
    } elseif ($page == 'comments.php')
    {
        $email = $value;
        $subject = 'New comment';
        $message = '<p>You have new comment on <a href="http://localhost:8080/photo.php?img=' . $_GET['img'] . '">photo</a></p>';
        $message .= '<blockquote><p>' . htmlentities($elem) .'</p>';
        $message .= '<cite>avtor: ' . $_SESSION['name'] .'</cite></blockquote>';
    }
    mail($email, $subject, $message, $headers);
}

function changeNumber($nb)
{
    if ($nb >= 1000000) {
        return floor($nb / 1000000) . 'kk';
    } elseif ($nb >= 1000) {
        return floor($nb / 1000) . 'k';
    } else {
        return $nb;
    }
}
