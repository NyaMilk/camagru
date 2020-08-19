<?php
require_once "components/header.php";
require_once "util.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
    $stmt = $pdo->prepare('SELECT confirm FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $_SESSION['name']));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        if ($row['confirm'] == 'yes')
            $_SESSION['confirm'] = $row['confirm']; /* без обновления? */
    $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 1 DAY)');
    // $stmt = $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 10 SECOND)');
    if ($stmt->rowCount()) {
        $_SESSION['error'] = "TimeOut"; /* ошибку описать */
        unset($_SESSION['name']);
        unset($_SESSION['user_id']);
        header('Location: index.php');
        return;
    }
}
?>

<section class="gallery align_footer">
    <div class="container">
        <form method="post" name="gallery_sort">
            <label>All<input type="radio" name="sort" value="all"></label>
            <label>Popular<input type="radio" name="sort" value="popular"></label>
            <label>Newest<input type="radio" name="sort" value="new"></label>
        </form>

        <div class="gallery_list">
            <?php
            $stmt = $pdo->query('SELECT * FROM Photo');
            $offset = 9;
            $pages = ceil($stmt->rowCount() / $offset);

            if ($stmt->rowCount() == 0)
                echo 'No photos t.t' . "\n";
            else {
                if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages) {
                    $limit = $offset * $_GET['page'];
                    $sql = 'SELECT * FROM Photo';
                    if ($_GET['sort'] === 'popular')
                        $sql = $sql . ' ORDER BY likes DESC';
                    elseif ($_GET['sort'] === 'new')
                        $sql = $sql . ' ORDER BY created_at_photo DESC';
                    $stmt = $pdo->query($sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit);
                    for ($i = 1; $i <= $offset; $i++) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row !== false)
                            echo '<a class="photo__link" href="photo.php?img=' . htmlentities($row['img_id']) . '">' . '<img class="gallery_item" src="' . htmlentities($row['path']) . '" ></a>' . "\n";
                    }
                } else
                    header('Location: gallery.php?sort=all&page=1');
            }
            ?>
        </div>
        <?php
        $pageName = 'gallery';
        paginationList($pageName, $pages);
        ?>
    </div>
</section>

<script>
    let item = document.forms['gallery_sort'].elements['sort'];
    for (let i = 0; i < item.length; i++) {
        item[i].onclick = function() {
            window.location.href = "gallery.php?sort=" + this.value + "&page=1";
        };

    }
</script>

<?php
require_once "components/footer.php";
?>