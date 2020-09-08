<?php

function getEmail($pdo, $email)
{
    $stmt = $pdo->prepare('SELECT name, email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $email));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function changePass($pdo, $pass, $login)
{
    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE name = :nm');
    $stmt->execute(array(
        ':ps' => $pass,
        ':nm' => $login
    ));
}
