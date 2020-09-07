<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once '../util.php';

header("Content-Type: application/json; charset=UTF-8");
$stmt = $pdo->prepare('SELECT * FROM Likes WHERE user_id = :uid AND img_id = :iid');
$stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':iid' => $_SESSION['img']
));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$response = new stdClass();
$response->isLiked = false;

if ($row === false) {
    $stmt = $pdo->prepare('INSERT INTO Likes (user_id, img_id) VALUES (:uid, :iid)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':iid' => $_SESSION['img']
    ));

    $response->isLiked = true;
} else {
    $stmt = $pdo->prepare('DELETE FROM Likes WHERE user_id = :uid AND img_id = :iid');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':iid' => $_SESSION['img']
    ));
    $response->isLiked = false;
}

$stmt = $pdo->prepare('SELECT * FROM Likes WHERE img_id = :iid');
$stmt->execute(array(
    ':iid' => $_SESSION['img']
));

$count = $stmt->rowCount();
$response->count = $count;

$stmt = $pdo->prepare('UPDATE Photo SET likes = :c WHERE img_id = :iid');
$stmt->execute(array(
    ':c' => $count,
    ':iid' => $_SESSION['img']
));

$response->likes = $count;

$json = json_encode($response);
echo $json;
