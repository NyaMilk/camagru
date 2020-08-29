<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700&display=swap" rel="stylesheet"> 
    <title>CamaGuru</title>
</head>

<body>

<header class="site-header">
    <div class="container">
        <div>
            <a class="site-logo" href="index.php">
                <!-- <img src="img/logo.png" alt="CamaGuru"> -->
                <h1>CamaGuru</h1>
            </a>
        </div>
        <nav class="site-navigation">
            <ul class="site-navigation-list">
                <?php
                if (!isset($_SESSION['name'])) {
                    if ($_SERVER['PHP_SELF'] == '/gallery.php') {
                        echo '<li><a href="index.php">Sign In</a></li>';
                    } else {
                        echo '<li><a href="gallery.php?sort=all&page=1">Gallery</a></li>';
                    }
                }
                if (isset($_SESSION['name'])) {
                    echo '<li><a href="me.php?user=' . $_SESSION['name'] . '&page=1&posts">Profile</a></li>';
                    echo '<li><a href="logout.php">Sign out</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>