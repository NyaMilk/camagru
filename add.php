<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek add';
    header('Location: index.php');
    return;
}

require_once "util.php";

if (isset($_POST['close']) && $_POST['close']) {
    header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
    // return;
}

if (isset($_POST['save']) && $_POST['save']) {
    $upload_dir = 'images/' . $_SESSION['user_id'];
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $upload_dir .= '/post';
    if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);
    $new_src = $upload_dir . '/' . date('HisdmY') . '_' . $_SESSION['user_id'] . '.png';
    file_put_contents($new_src, file_get_contents($_POST['src']));

    $stmt = $pdo->prepare("INSERT INTO Photo (user_id, path, description_photo) VALUES (:uid, :src, :dp)");
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':src' => $new_src,
        ':dp' => nl2br(trim(mb_substr(htmlentities($_POST['text_photo']), 0, 80)))
    ));
    header('Location: me.php?user=' . $_SESSION['name'] . '&page=1&posts');
}

$stmt_filters = $pdo->query("SELECT path FROM Filters");
$stmt_stickers = $pdo->query("SELECT path FROM Stickers");

require_once "components/header.php";
require_once "components/add-view.php";
require_once "components/footer.php";
flashMessages();
