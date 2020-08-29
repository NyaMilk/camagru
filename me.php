<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek me';
    header('Location: index.php');
    return;
}

require_once "util.php";

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    /* подумать куда выводить ошибку */
    $_SESSION['error'] = 'Confirm your email address.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

if (isset($_POST['delete'])) {
    if (isset($_POST['img_id']) && $_POST['img_id'] && $_SESSION['user_id']) {
        $stmt = $pdo->prepare('DELETE FROM Photo WHERE img_id = :iid');
        $stmt->execute(array(':iid' => $_POST['img_id'])); /* проверить */
        header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
    }
}

// $stmt = $pdo->prepare('SELECT u.name, u.avatar, p.img_id, p.path, u.user_id, p.user_id FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE u.name = :nm');
$stmt = $pdo->prepare('SELECT * FROM Users WHERE name = :nm');
$stmt->execute(array(':nm' => $_GET['user']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row !== false) {
    $stmt = $pdo->prepare('SELECT SUM(likes) likes FROM Photo WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $row['user_id']));
    $likes = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($likes['likes'] === NULL)
    if (!$likes['likes'])
        $likes['likes'] = 0;

    $sql = 'SELECT * FROM Photo WHERE user_id = :uid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':uid' => $row['user_id']));
    $posts = $stmt->rowCount();

    $offset = 6;
    $pages = ceil(($posts + 1) / $offset);

    if ($posts) {
        if ($_GET['page'] > 0 && $_GET['page'] <= $pages) {
            $limit = $offset * $_GET['page'];
            if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                $limit--;
                if ($_GET['page'] == 1) {
                    $photos = $pdo->prepare($sql . ' LIMIT ' . '0, ' . $limit); /* order by cr_at */
                    $flag = 1;
                } else
                    $photos = $pdo->prepare($sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
            } else
                $photos = $pdo->prepare($sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
            $photos->execute(array(':uid' => $row['user_id']));
        }
        // else
        // header('Location: me.php?user=' . $_GET['user'] . '&page=1&posts');
    }


    $sql_like = 'SELECT l.img_id, p.path FROM Likes l JOIN Photo p WHERE l.user_id = :uid AND l.img_id = p.img_id';
    // SELECT l.img_id, l.user_id, p.path, p.likes FROM Likes l JOIN Photo p WHERE l.user_id = 2 AND l.img_id = p.img_id;         
    $stmt = $pdo->prepare($sql_like);
    $stmt->execute(array(':uid' => $row['user_id']));
    $favorites = $stmt->rowCount();

    $pages_likes = ceil($favorites / $offset);
    if ($favorites) {
        if ($_GET['page'] > 0 && $_GET['page'] <= $pages_likes) {
            $limit = $offset * $_GET['page'];
            if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                if ($_GET['page'] == 1) {
                    $photo_likes = $pdo->prepare($sql_like . ' LIMIT ' . '0, ' . $limit); /* order by cr_at */
                } else
                    $photo_likes = $pdo->prepare($sql_like . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
            } else
                $photo_likes = $pdo->prepare($sql_like . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
            $photo_likes->execute(array(':uid' => $row['user_id']));
        }
        // else
        // header('Location: me.php?user=' . $_GET['user'] . '&page=1&favorites'); /* - - - */
    }
} else
    echo 'Error profile';

require_once "components/header.php";
require_once "components/me-view.php";
require_once "components/footer.php";
