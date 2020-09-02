<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once "util.php";

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    $stmt = $pdo->prepare('SELECT confirm FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_SESSION['name']));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        if ($row['confirm'] == 'yes')
            $_SESSION['confirm'] = $row['confirm']; /* без обновления? */
    $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 1 DAY)');
    // $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 10 SECOND)');
    if ($stmt->rowCount()) {
        $_SESSION['error'] = "TimeOut"; /* ошибку описать */
        unset($_SESSION['name']);
        unset($_SESSION['user_id']);
        header('Location: index.php');
        return;
    }
}

$stmt = $pdo->query('SELECT * FROM Photo');
$offset = 9;
$pages = ceil($stmt->rowCount() / $offset);
if ($stmt->rowCount() != 0) {
    if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages) {
        $limit = $offset * $_GET['page'];
        $sql = 'SELECT * FROM Photo';
        if ($_GET['sort'] === 'popular')
            $sql = $sql . ' ORDER BY likes DESC';
        elseif ($_GET['sort'] === 'new')
            $sql = $sql . ' ORDER BY created_at_photo DESC';
        $stmt = $pdo->query($sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
    } else
        header('Location: gallery.php?sort=all&page=1');
}

require_once "components/header.php";
require_once "components/gallery-view.php";

$pageName = 'gallery';
paginationList($pageName, $pages);

require_once "components/footer.php";

flashMessages();
