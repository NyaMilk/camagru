<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek edit';
    header('Location: index.php');
    return;
}

require_once 'util.php';

flashMessages(); /* куда можно перенести? */

if (isset($_SESSION['name']) && isset($_GET['user']) && $_SESSION['name'] == $_GET['user']) {
    $salt = 'XyZzy12*_';

    $stmt = $pdo->prepare('SELECT user_id, name, email, password, avatar, description_user, notification FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_GET['user'])); /* из сессии мб? */
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        if ($row['notification'] == 'yes')
            $checked = 'checked';

        if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
            $page = 'edit.php?user=' . $row['name'];

            if (isset($_POST['notific']) && $_POST['notific'] == 'yes')
                $notific = 'yes';
            else
                $notific = 'no';
            $stmt = $pdo->prepare('UPDATE Users SET notification = :nf WHERE user_id = :uid');
            $stmt->execute(array(
                ':nf' => $notific,
                ':uid' => $_SESSION['user_id']
            ));

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
            checkLenInput('description', $page, 'Description');
            if (strlen($_POST['pass_up']) > 0 || strlen($_POST['repass_up']) > 0) {
                checkPassword($pdo, $page);
                if (!isset($_SESSION['error'])) {
                    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE user_id = :uid');
                    $stmt->execute(array(
                        ':ps' => hash('sha512', $salt . $_POST['repass_up']),
                        ':uid' => $_SESSION['user_id']
                    ));
                }
            }
            if (!isset($_SESSION['error'])) {
                $stmt = $pdo->prepare('UPDATE Users SET name = :nm, email = :em, description_user = :du WHERE user_id = :uid');
                $stmt->execute(array(
                    ':nm' => $_POST['username_up'],
                    ':em' => $_POST['email_up'],
                    ':du' => $_POST['description'],
                    ':uid' => $_SESSION['user_id']
                ));
                $_SESSION['name'] = $_POST['username_up'];

                $upload_dir = 'images/' . $row['user_id'];
                if (!file_exists($upload_dir))
                    mkdir($upload_dir, 0777, true);
                $upload_dir .= '/avatar';
                if (!file_exists($upload_dir))
                    mkdir($upload_dir, 0777, true);

                $tmp_name = $_FILES['ava']['tmp_name'];
                $name = $upload_dir . '/' . date('HisdmY') . '_' . $row['user_id'] . '.png';
                // basename() может предотвратить атаку на файловую систему;
                // может быть целесообразным дополнительно проверить имя файла
                $move = move_uploaded_file($tmp_name, $name);
                if ($move) {
                    $stmt = $pdo->prepare('UPDATE Users SET avatar = :av WHERE user_id = :uid');
                    $stmt->execute(array(
                        ':av' => $name,
                        ':uid' => $_SESSION['user_id']
                    ));
                    if (isset($row['avatar']) && $row['avatar'] && $row['avatar'] != 'img/icon/user.svg')
                        unlink($row['avatar']);
                }
                header('Location: me.php?user=' . htmlentities($_SESSION['name']) . '&page=1&posts');
            }
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Cancel')
            header('Location: me.php?user=' . $row['name'] . '&page=1&posts');
    }
} else
    header('Location: index.php');

    
require_once 'components/header.php';
require_once 'components/edit-view.php';
require_once 'components/footer.php';




