<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek photo';
    header('Location: index.php');
    return;
}

require_once 'util.php';

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    /* куда вывести ошибку? */
    $_SESSION['error'] = 'Confirm your email address.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

if (isset($_POST['likes'])) {
    $stmt = $pdo->prepare('SELECT * FROM Likes WHERE user_id = :uid AND img_id = :iid');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':iid' => $_GET['img']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // $src = '../img/valentines-heart.svg';
    if ($row === false) {
        $stmt = $pdo->prepare('INSERT INTO Likes (user_id, img_id) VALUES (:uid, :iid)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':iid' => $_GET['img']
        ));

        countLikes($pdo);
    } else {
        $stmt = $pdo->prepare('DELETE FROM Likes WHERE user_id = :uid AND img_id = :iid');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':iid' => $_GET['img']
        ));

        countLikes($pdo);
    }
    header('Location: photo.php?img=' . $_GET['img']);
    return;
}

/* можно сделать еще проверку на пользователя чтоб без накрутки */
$stmt = $pdo->prepare('SELECT * FROM Views WHERE img_id = :iid AND date_views = CURDATE()');
$stmt->execute(array(':iid' => $_GET['img']));
$view = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($view)) {
    $stmt = $pdo->prepare('INSERT INTO Views SET counter = 1, img_id = :iid, date_views = CURDATE()');
    $stmt->execute(array(':iid' => $_GET['img']));
} else {
    $stmt = $pdo->prepare('UPDATE Views SET counter = counter + 1 WHERE img_id = :iid AND date_views = CURDATE()');
    $stmt->execute(array(':iid' => $view['img_id']));
}
$stmt = $pdo->prepare('SELECT SUM(counter) views FROM Views WHERE img_id = :iid');
$stmt->execute(array(':iid' => $_GET['img']));
$view = $stmt->fetch(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare('SELECT u.user_id, u.name, u.avatar, u.email, u.notification, p.likes, p.path, p.description_photo, p.created_at_photo
FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
$stmt->execute(array(':iid' => $_GET['img']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

/* повторение запроса */
$stmt = $pdo->prepare('SELECT * FROM Likes WHERE user_id = :uid AND img_id = :iid');
$stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':iid' => $_GET['img']
));
if ($likes = $stmt->fetch(PDO::FETCH_ASSOC))
    $src = '../img/icon/valentines-heart1.svg';
else
    $src = '../img/icon/valentines-heart.svg'; /* - - - */

if ($row !== false) {
    if (isset($_POST['text_comment']) && $_POST['text_comment']) /* valid */ {
        $stmt = $pdo->prepare('INSERT INTO Comment (user_id, img_id, comment) VALUES (:uid, :iid, :cm)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':iid' => $_GET['img'],
            ':cm' => nl2br(mb_substr(htmlentities($_POST['text_comment']), 0, 80))
        ));
        /* mail */
        if ($row['notification'] == 'yes') {
            $email = $row['email'];
            $subject = 'New comment';
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: amilyukovadev@gmail.com\r\n";
            $message = '<p>You have new comment on <a href="http://localhost:8080/photo.php?img=' . $_GET['img'] . '">photo</a></p>
            <p>To unsubscribe from this thread, please <a href="">click here</a></p>';
            mail($email, $subject, $message, $headers);
        }
        header('Location: photo.php?img=' . $_GET['img']);
    }
    $test = $pdo->prepare('SELECT * FROM Comment JOIN Users ON Comment.user_id = Users.user_id WHERE img_id = :iid ORDER BY comment_id');
    $test->execute(array(':iid' => $_GET['img']));

    if (isset($_POST['delete'])) {
        if (isset($_POST['comment_id']) && $_POST['comment_id'] && $_SESSION['user_id']) {
            $stmt = $pdo->prepare('DELETE FROM Comment WHERE comment_id = :cid');
            $stmt->execute(array(':cid' => $_POST['comment_id'])); /* проверить */
            header('Location: photo.php?img=' . $_GET['img']);
        }
    }
} else
    echo 'Error photo';

require_once "components/header.php";
require_once "components/photo-view.php";
require_once "components/footer.php";

flashMessages();
