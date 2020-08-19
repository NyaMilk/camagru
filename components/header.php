<?php
require_once 'config/pdo.php';
require_once 'components/head.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();
?>

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