<?php
require_once 'components/header.php';
require_once 'util.php';
require_once 'class/user.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    require_once 'components/login.php';
} else {
    Header('Location: gallery.php');
}

if (isset($_POST['submit'])) {
    $user = new User();
    $salt = 'XyZzy12*_';
    if ($_POST["submit"] === 'Sign In') {
        if (strlen($_POST['username']) == 0 || strlen($_POST['pass']) == 0) {
            $_SESSION['error'] = 'Username and password are required';
            header('Location: index.php');
            return;
        }

        $check = hash('sha512', $salt . $_POST['pass']);
        $row = $user->ft_check_nm_pw(array(':nm' => $_POST['username'], ':pw' => $check));
        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            header('Location: gallery.php');
            return;
        } else {
            $_SESSION['error'] = 'Incorrect password';
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
        if (strlen($_POST['pass_up']) > 0 && strlen($_POST['pass_up']) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters long';
            header('Location: index.php');
            return;
        }

        $row = $user->ft_check_nm(array(':nm' => $_POST['username_up']));
        if ($row !== false) {
            $_SESSION['error'] = 'That username is already taken';
            header('Location: index.php');
            return;
        }
        $row = $user->ft_check_em(array(':em' => $_POST['email_up']));
        if ($row !== false) {
            $_SESSION['error'] = 'That email is already taken';
            header('Location: index.php');
            return;
        }
        if ($_POST['pass_up'] != $_POST['repass_up']) {
            $_SESSION['error'] = 'Password do not match';
            header('Location: index.php');
            return;
        }
        $row = $user->ft_add(array(
            ':nm' => $_POST['username_up'],
            ':em' => $_POST['email_up'],
            ':ps' => hash('sha512', $salt . $_POST['pass_up']),
            ':cf' => 'no'
        ));
        $_SESSION['success'] = 'Profile added';
        header('Location: index.php');
        return;
    }
}
require_once 'components/footer.php';
