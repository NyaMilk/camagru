<section class="page-img align_footer">
    <div class="container">
        <div class="page-img__photo">
            <div class="page-img__photo-block">
                <img src="<?= htmlentities($row['path']) ?>">
                <form class="page-img__photo-likes" method="post">
                    <button type="submit" name="likes">
                        <img class="photo-like" src="<?= $src ?>" alt="like">
                    </button>
                </form>
            </div>

            <div class="page-img__photo-info">
                <p><span><?= htmlentities(changeNumber($view['views'])) ?></span> Views</p>
                <p><span><?= htmlentities(changeNumber($row['likes'])) ?></span> Likes</p>

                <div>
                    <p>Share:</p>
                    <!-- esc_url( sprintf( 'http://www.twitter.com?status=%s', urlencode( $message ) ) ); -->
                    <a class="btn btn-default btn-lg" target="_blank" title="facebook" href="http://www.facebook.com/sharer.php?u=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/facebook.svg" alt="fb">
                    </a>
                    <a class="btn btn-default btn-lg" target="_blank" title="twitter" href="http://twitter.com/share?url=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/twitter.svg" alt="tw">
                    </a>
                    <a class="btn btn-default btn-lg" target="_blank" title="vk" href="http://vk.com/share.php?url=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/vk.svg" alt="vk">
                    </a>
                </div>

            </div>

            <p class="page-img__photo-description"><?= htmlentities($row['description_photo']) ?></p>
            <time><?= date("d M Y G:i", strtotime($row['created_at_photo'])) ?></time>

            <a class="page-img__photo-user" href="me.php?user=<?= htmlentities($row['name']) ?>&page=1&posts">
                <div class="photo-user__block">
                    <img class="photo-user__block-img" src="<?= htmlentities($row['avatar']) ?>">
                </div>
                <p><?= htmlentities($row['name']) ?></p>
            </a>
        </div>

        <div class="page-img__comments">
            <div class="page-img__comments-set">
                <h2>Comments</h2>
                <form class="page-img__comments-set__form" method="post">
                    <span class="span_comment">No more than 80 characters</span>
                    <textarea id="text" name="text_comment" rows="1" placeholder="Leave a comment"></textarea>
                    <button class="btn-blue" type="submit">Send</button>
                </form>
            </div>

            <div class="page-img__comments-list">
                <?php
                $comments = $test->rowCount();
                if ($comments > 0) {
                    for ($i = 1; $i <= $comments; $i++) {
                        $comment = $test->fetch(PDO::FETCH_ASSOC);
                        if ($comment !== false) {
                            echo '<article>';
                            echo '<div class="photo-user__block">';
                            echo '<a href="me.php?user=' . htmlentities($comment['name']) . '&page=1&posts">';
                            echo '<img class="photo-user__block-img" src="' . htmlentities($comment['avatar']) . '">';
                            echo '</a></div>';
                            echo '<div class="page_info_user"><span>' . htmlentities($comment['name']) . '</span> '; /* проверить */
                            echo '<time>' . date("d M Y G:i", strtotime($comment['created_at_comment'])) . '</time>';
                            if ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['user_id'] == $comment['user_id']) {

                                echo '<a href="#openModal' . $i . '">';
                                echo '<img class="page-img_delete" src="img/icon/cancel.svg">';
                                echo '</a>';

                ?>
                                <div id="openModal<?= $i ?>" class="modal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <p class="modal-title">Delete comment?</p>
                                            <p>This can’t be undone and it will be removed from your profile.</p>
                                            <form method="post" action="photo.php?img=<?= $_GET['img'] ?>">
                                                <input type="hidden" name="comment_id" value="<?= htmlentities($comment['comment_id']) ?>">
                                                <input type="submit" name="delete" class="btn-blue" value="Delete">
                                                <input type="submit" name="close" class="btn-gray" value="Close"> <!-- проверить -->
                                                <!-- <a href="" class="close" title="Close">Close</a> -->
                                            </form>
                                        </div>
                                    </div>
                                </div>
                <?php

                            }
                            echo '<p>' . $comment['comment'] . '</p>';
                            echo '</div></article>';
                        }
                    }
                } else
                    echo '<p class="count-message">There is no comment yet</p>';
                ?>
            </div>
        </div>
    </div>
</section>

<script>
    let photo = document.getElementById('text');
    photo
    
</script>