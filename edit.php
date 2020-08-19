<?php
require_once 'components/header.php';
require_once 'util.php';

flashMessages();

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek edit';
    header('Location: index.php');
    return;
}

if (isset($_SESSION['name']) && isset($_GET['user']) && $_SESSION['name'] == $_GET['user']) {
    $salt = 'XyZzy12*_';

    $stmt = $pdo->prepare('SELECT user_id, name, email, password, avatar, description_user FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_GET['user'])); /* из сессии мб? */
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
            $page = 'edit.php?user=' . $row['name'];

            // if (empty($_POST['username_up']) || empty($_POST['email'])) {
            if (strlen($_POST['username_up']) == 0 || strlen($_POST['email_up']) == 0) {
                $_SESSION['error'] = 'Username and email are required';
                header('Location: edit.php?user=' . $row['name']);
                return;
            }

            if ($_POST['username_up'] != $row['name']) {
                checkUserName($pdo, $page);
                $row['name'] = $_POST['username_up'];
            }
            if ($_POST['email_up'] != $row['email'])
                checkEmail($pdo, $page);
            if (strlen($_POST['pass_up']) > 0 || strlen($_POST['repass_up']) > 0) {
                checkPassword($pdo, $page);
                if (!isset($_SESSION['error'])) {
                    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE user_id = :uid');
                    $stmt->execute(array(
                        ':ps' => hash('sha512', $salt . $_POST['repass_up']),
                        // ':uid' => $_POST['profile_id']
                        ':uid' => $_SESSION['user_id']
                    ));
                }
            }
            if (!isset($_SESSION['error'])) {
                $_SESSION['name'] = $row['name'];
                $stmt = $pdo->prepare('UPDATE Users SET name = :nm, email = :em, description_user = :du WHERE user_id = :uid');
                $stmt->execute(array(
                    ':nm' => $_POST['username_up'],
                    ':em' => $_POST['email_up'],
                    ':du' => $_POST['description'],
                    // ':uid' => $_POST['profile_id']
                    ':uid' => $_SESSION['user_id']
                ));

                $upload_dir = 'images/' . $row['name'];
                if (!file_exists($upload_dir))
                    mkdir($upload_dir);

                $format = array('image/jpeg', 'image/gif', 'image/png', 'image/svg+xml');
                if (!in_array($_FILES['ava']['type'], $format)) {
                    $_SESSION['error'] = 'Wrong type';
                    header('Location: edit.php?user=' . $_SESSION['name']);
                    return;
                } else {
                    $tmp_name = $_FILES['ava']['tmp_name'];
                    $name = $upload_dir . '/' . date('HisdmY') . basename($_FILES['ava']['name']);
                    // basename() может предотвратить атаку на файловую систему;
                    // может быть целесообразным дополнительно проверить имя файла
                    // echo $name;
                    $move = move_uploaded_file($tmp_name, $name);
                    if ($move) {
                        $stmt = $pdo->prepare('UPDATE Users SET avatar = :av WHERE user_id = :uid');
                        $stmt->execute(array(
                            ':av' => $name,
                            ':uid' => $_SESSION['user_id']
                        ));
                        unlink($row['avatar']);
                        // header('Location: edit.php?user=' . $row['name']);
                        // header('Location: me.php?user=' . $row['name'] . '&page=1&posts');
                    }
                }
                header('Location: me.php?user=' . $row['name'] . '&page=1&posts');
            }
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Cancel')
            header('Location: me.php?user=' . $row['name'] . '&page=1&posts');
    }
    require_once 'components/edit-view.php';
} else
    header('Location: index.php');


require_once 'components/footer.php';
