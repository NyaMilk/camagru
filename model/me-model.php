<?php

function delPhoto($pdo, $imageId)
{
    $stmt = $pdo->prepare('DELETE FROM Photo WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imageId));
}

function getUserId($pdo, $login)
{
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $login));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSumLikes($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT SUM(likes) likes FROM Photo WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $userId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPosts($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT * FROM Photo WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $userId));
    return $stmt->rowCount();
}

function getPhotos($pdo, $limit, $offset = null)
{
    $sql = 'SELECT * FROM Photo WHERE user_id = :uid ORDER BY created_at_photo DESC ';
    if ($offset)
        $sql = $sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit;
    else
        $sql = $sql . ' LIMIT ' . '0, ' . $limit;
    return $pdo->prepare($sql);
}

function getFavorites($pdo, $userId) {
    $sql_like = 'SELECT l.img_id, p.path FROM Likes l JOIN Photo p WHERE l.user_id = :uid AND l.img_id = p.img_id';
    $stmt = $pdo->prepare($sql_like);
    $stmt->execute(array(':uid' => $userId));
    return $stmt->rowCount();
}

function getLikes($pdo, $limit, $offset = null) {
    $sql = 'SELECT l.img_id, p.path FROM Likes l JOIN Photo p WHERE l.user_id = :uid AND l.img_id = p.img_id';
    if ($offset)
        $sql = $sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit;
    else
        $sql = $sql . ' LIMIT ' . '0, ' . $limit;
    return $pdo->prepare($sql);
}
