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

$stmt_likes = $pdo->prepare('SELECT p.likes FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
$stmt_likes->execute(array(':iid' => $_SESSION['img']));
$ans = $stmt_likes->fetch(PDO::FETCH_ASSOC);
$response = new \stdClass();
$response->likes = $ans['likes'];

if ($row == false)
    $response->isLiked = false;
else
    $response->isLiked = true;

$json = json_encode($response);
echo $json;
