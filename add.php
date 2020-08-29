<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek add';
    header('Location: index.php');
    return;
}

require_once "util.php";

if (isset($_POST['save']) && $_POST['save'] === "Save") {
    $upload_dir = 'images/' . $_SESSION['user_id'];
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $upload_dir .= '/post';
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $new_src = $upload_dir . '/' . date('HisdmY') . '_' .$_SESSION['user_id'] . '.png';
    file_put_contents($new_src, file_get_contents($_POST['src']));
    $stmt = $pdo->prepare("INSERT INTO Photo (user_id, path) VALUES (:uid, :src)");
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':src' => $new_src
    ));
    header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
}

require_once "components/header.php";
require_once "components/add-view.php";
require_once "components/footer.php";
flashMessages();
