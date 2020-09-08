<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['error'] = 'Kek photo';
    header('Location: index.php');
    return;
}

require_once 'util.php';
require_once 'model/photo-model.php';
flashMessages();

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    $_SESSION['error'] = 'Confirm your email address.';
    header('Location: gallery.php?sort=all&page=1');
    return;
}

$_SESSION['img'] = $_GET['img'];

$view = getView($pdo, $_GET['img']);
if (empty($view))
    setView($pdo, $_GET['img']);
else
    updateView($pdo, $view['img_id']);

$view = getSumViews($pdo, $_GET['img']);
$row = getPhotoInfo($pdo, $_GET['img']);

require_once "components/header.php";
require_once "components/photo-view.php";
require_once "components/footer.php";
