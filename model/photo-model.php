<?php

function getView($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT img_id FROM Views WHERE img_id = :iid AND date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function setView($pdo, $imgId)
{
    $stmt = $pdo->prepare('INSERT INTO Views SET counter = 1, img_id = :iid, date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
}

function updateView($pdo, $imgId)
{
    $stmt = $pdo->prepare('UPDATE Views SET counter = counter + 1 WHERE img_id = :iid AND date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
}

function getSumViews($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT SUM(counter) views FROM Views WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPhotoInfo($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT u.user_id, u.name, u.avatar, u.email, u.notification, p.likes, p.path, p.description_photo, p.created_at_photo
    FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
