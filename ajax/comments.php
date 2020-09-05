<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once '../util.php';

$stmt = $pdo->prepare('SELECT u.user_id, u.email, u.notification FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
$stmt->execute(array(':iid' => $_SESSION['img']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['comment'])) /* valid */ {
    $stmt = $pdo->prepare('INSERT INTO Comment (user_id, img_id, comment) VALUES (:uid, :iid, :cm)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':iid' => $_SESSION['img'],
        ':cm' => nl2br(mb_substr(htmlentities($_POST['comment']), 0, 80))
        // ':cm' => nl2br(substr(htmlentities($_POST['comment']), 0, 80))
    ));
    /* mail  надо проверить*/
    if ($row['notification'] == 'yes') {
        $email = $row['email'];
        $subject = 'New comment';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: amilyukovadev@gmail.com\r\n";
        $message = '<p>You have new comment on <a href="http://localhost:8080/photo.php?img=' . $_GET['img'] . '">photo</a></p>
        <blockquote>
        <p>' . nl2br(htmlentities($_POST['comment'])) .'</p>
        <cite>avtor: ' . $_SESSION['name'] .'</cite>
        </blockquote>';
        mail($email, $subject, $message, $headers);
    }
    return;
}

if (isset($_POST['com_id'])) {
    if (isset($_POST['com_id']) && $_POST['com_id'] && $_SESSION['user_id']) {
        $stmt = $pdo->prepare('DELETE FROM Comment WHERE comment_id = :cid');
        $stmt->execute(array(':cid' => $_POST['com_id'])); /* проверить */
    }
    return;
}

$add_comm = $pdo->prepare('SELECT * FROM Comment JOIN Users ON Comment.user_id = Users.user_id WHERE img_id = :iid ORDER BY comment_id');
$add_comm->execute(array(':iid' => $_SESSION['img']));
$comments = $add_comm->rowCount();
if ($comments > 0) {
    for ($i = 1; $i <= $comments; $i++) {
        $comment = $add_comm->fetch(PDO::FETCH_ASSOC);
        if ($comment !== false) {
            echo '<article>';
            echo '<div class="photo-user__block">';
            echo '<a href="me.php?user=' . htmlentities($comment['name']) . '&page=1&posts">';
            echo '<img class="photo-user__block-img" src="' . htmlentities($comment['avatar']) . '">';
            echo '</a></div>';
            echo '<div class="page_info_user"><span>' . htmlentities($comment['name']) . '</span> '; /* проверить */
            echo '<time>' . date("d M Y G:i", strtotime($comment['created_at_comment'])) . '</time>';
            if ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['user_id'] == $comment['user_id']) {
                echo '<a class="modal-link" href="#openModal' . $i . '">';
                echo '<img class="page-img_delete" src="img/icon/cancel.svg">';
                echo '</a>';
                echo '<div id="openModal' . $i . '" class="modal">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<p class="modal-title">Delete comment?</p>';
                echo '<p>This can’t be undone and it will be removed from your profile.</p>';
                echo '<div>';
                echo '<input id="' . htmlentities($comment['comment_id']) . '" type="submit" name="delete" class="btn-blue btn-confirm-del" value="Delete">';
                echo '<input type="submit" name="close" class="btn-gray btn-close" value="Close">';
                echo '</div></div></div></div>';
            }
            echo '<p>' . $comment['comment'] . '</p>';
            echo '</div></article>';
        }
    }
} else
    echo '<p class="count-message">There is no comment yet</p>';
