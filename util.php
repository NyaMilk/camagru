<?php
require_once 'config/pdo.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

function flashMessages()
{
    if (isset($_SESSION['error'])) {
        // echo '<p class="message_error">' . htmlentities($_SESSION['error']) . "</p>\n";
        echo '<script>alert("' . htmlentities($_SESSION['error']) . '");</script>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        // echo '<p class="message_success">' . htmlentities($_SESSION['success']) . "</p>\n";
        echo '<script>alert("' . htmlentities($_SESSION['success']) . '");</script>';
        unset($_SESSION['success']);
    }
}

function checkUserName($pdo, $page)
{
    $stmt = $pdo->prepare('SELECT name FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_POST['username_up']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['error'] = 'That username is already taken';
        header('Location: ' . $page);
        return;
    }
    if ($page == 'index.php')
        checkEmail($pdo, $page);
}

function checkEmail($pdo, $page)
{
    $stmt = $pdo->prepare('SELECT email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $_POST['email_up']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['error'] = 'That email address is already taken';
        header('Location: ' . $page);
        return;
    }
    if ($page == 'index.php')
        checkPassword($pdo, $page);
}

function checkPassword($pdo, $page)
{
    if ($page == 'index.php') {
        if ($_POST['pass_up'] != $_POST['repass_up']) {
            $_SESSION['error'] = 'Password do not match';
            header('Location: ' . $page);
            return;
        }
    } else {
        $salt = 'XyZzy12*_';
        $stmt = $pdo->prepare('SELECT name FROM Users WHERE password = :ps');
        $stmt->execute(array(':ps' => hash('sha512', $salt . $_POST['pass_up'])));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $_SESSION['error'] = 'Wrong password';
            header('Location: ' . $page);
            return;
        }
    }
    // if ((strlen($_POST['pass_up']) > 0 && strlen($_POST['pass_up'])) < 6
    // || (strlen($_POST['repass_up']) > 0 && strlen($_POST['repass_up']) < 6)) {
    //     $_SESSION['error'] = 'Password must be at least 6 characters long';
    //     header('Location: ' . $page);
    //     return;
    // }
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

function countLikes($pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM Likes WHERE img_id = :iid');
    $stmt->execute(array(
        ':iid' => $_GET['img']
    ));

    $count = $stmt->rowCount();

    $stmt = $pdo->prepare('UPDATE Photo SET likes = :c WHERE img_id = :iid');
    $stmt->execute(array(
        ':c' => $count,
        ':iid' => $_GET['img']
    ));
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
