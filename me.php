<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek me';
    header('Location: index.php');
    return;
}

require_once "util.php";
require_once "model/me-model.php";

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    $_SESSION['error'] = 'Confirm your email address.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

if (isset($_POST['delete'])) {
    if (isset($_POST['img_id']) && $_POST['img_id'] && $_SESSION['user_id']) {
        delPhoto($pdo, $_POST['img_id']);
        header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
    }
}

$row = getUserId($pdo, $_GET['user']);
if ($row !== false) {
    $likes = getSumLikes($pdo, $row['user_id']);
    if (!$likes['likes'])
        $likes['likes'] = 0;

    $posts = getPosts($pdo, $row['user_id']);
    $offset = 6;
    $pages = ceil(($posts + 1) / $offset);

    if ($posts) {
        if ($_GET['page'] > 0 && $_GET['page'] <= $pages) {
            $limit = $offset * $_GET['page'];
            if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                $limit--;
                if ($_GET['page'] == 1) {
                    $photos = getPhotos($pdo, $limit);
                    $flag = 1;
                } else
                    $photos = getPhotos($pdo, $limit, $offset);
            } else
            $photos = getPhotos($pdo, $limit, $offset);
            $photos->execute(array(':uid' => $row['user_id']));
        }
        // else
        // header('Location: me.php?user=' . $_GET['user'] . '&page=1&posts');
    }

    $favorites = getFavorites($pdo, $row['user_id']);

    $pages_likes = ceil($favorites / $offset);
    if ($favorites) {
        if ($_GET['page'] > 0 && $_GET['page'] <= $pages_likes) {
            $limit = $offset * $_GET['page'];
            if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                if ($_GET['page'] == 1) {
                    $photo_likes = getLikes($pdo, $limit);
                } else
                    $photo_likes = getLikes($pdo, $limit, $offset);
            } else
                $photo_likes = getLikes($pdo, $limit, $offset);
            $photo_likes->execute(array(':uid' => $row['user_id']));
        }
        // else
        // header('Location: me.php?user=' . $_GET['user'] . '&page=1&favorites'); /* - - - */
    }
} else
{
    $_SESSION['error'] = 'Error profile. Please contact the site administrator.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

require_once "components/header.php";
require_once "components/me-view.php";
require_once "components/footer.php";
