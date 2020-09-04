<section class="photo-edit align_footer">
    <div class="container">
        <div class="photo-upload">
            <label class="custom-file-upload">
                <img src="img/icon/transfer.svg" alt="from computer">
                <p>Upload</p>
                <input id='file-upload' type="file">
            </label>

            <label class="custom-file-upload">
                <img src="img/icon/photo-camera.svg" alt="from camera">
                <p>Camera</p>
                <input id="startbutton" type="button">
            </label>
        </div>

        <div class="photo-edit__canvas">
            <img id="preview" src="img/preview.png" alt="preview">

            <img id="origin" src="img/preview.png">
            <video id="video"></video>
            <canvas id="canvas"></canvas>
            <form><input type='button' id='snapshot' value="snapshot"></form>
        </div>

        <div class="photo-upload">
            
            <label class="custom-file-upload">
                <img src="img/icon/shoot.svg" alt="from computer">
                <p>Shoot</p>
                <input id='shoot' type="button">
            </label>
            <label class="custom-file-upload">
                <img src="img/icon/save.svg" alt="from computer">
                <p>Save</p>
                <form method="post" enctype="multipart/form-data">
                    <input id="save" name="src" type="hidden" value="img/preview.png">
                    <input id="save_btn" name="save" type="submit" value="Save" disabled>
                </form>
            </label>
            <label id="discard" class="custom-file-upload">
                <img src="img/icon/filter.svg" alt="from camera">
                <p>Clear</p>
            </label>
        </div>

        <div class="photo-filters">
            <div class="photo-carousel">
                <?php
                $i = 0;
                while ($row = $stmt_filters->fetch(PDO::FETCH_ASSOC))
                    echo '<div id="' . $i++ . '" class="filter"><img class="carousel-item" src="' . $row['path'] . '"></div>';
                ?>
            </div>
        </div>

        <div class="photo-stickers">
            <div id="stick" class="photo-carousel">
                <?php
                $i = 0;
                while ($row = $stmt_stickers->fetch(PDO::FETCH_ASSOC))
                    echo '<div id="' . $i++ . '" class="sticker"><img class="carousel-item" src="' . $row['path'] . '"></div>';
                ?>
            </div>
        </div>
    </div>
</section>

<script src="js/add.js"></script>