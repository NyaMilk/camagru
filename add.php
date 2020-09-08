<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek add';
    header('Location: index.php');
    return;
}

require_once "util.php";
require_once "model/add-model.php";
flashMessages();

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    $_SESSION['error'] = 'Confirm your email address.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

if (isset($_POST['close']) && $_POST['close']) {
    header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
    return;
}

if (isset($_POST['save']) && $_POST['save']) {
    $upload_dir = 'images/' . $_SESSION['user_id'];
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $upload_dir .= '/post';
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $newSrc = $upload_dir . '/' . date('HisdmY') . '_' . $_SESSION['user_id'] . '.png';
    file_put_contents($newSrc, file_get_contents($_POST['src']));

    addPhoto($pdo, $_SESSION['user_id'], $newSrc, htmlentities($_POST['text_photo']));
    header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
}

$stmt_filters = getTools($pdo, "Filters");
$stmt_stickers = getTools($pdo, "Stickers");

require_once "components/header.php";
require_once "components/add-view.php";
require_once "components/footer.php";
