<div class="edit-page align_footer">
    <div class="container">
        <form class="edit-page__form" enctype="multipart/form-data" method="post">
            <div class="edit-page__form-img">
                <img id="current-avatar" src="<?= htmlentities($row['avatar']) ?>">
                <label class="btn-blue">
                    <input id="new-avater" name="ava" type="file" onchange="document.getElementById('current-avatar').src = window.URL.createObjectURL(this.files[0])">
                    Change avatar
                </label>
            </div>
            <div class="edit-page__form-text">
                <h2>Personal data</h2>
                <label>
                    Username
                    <input type="text" name="username_up" class="input-gray" value="<?= htmlentities($row['name']) ?>">
                </label>

                <label>
                    Email
                    <input type="email" name="email_up" class="input-gray" value="<?= htmlentities($row['email']) ?>">
                </label>
                <label>
                    Description
                    <textarea name="description" class="input-gray" rows="5"><?= htmlentities($row['description_user']) ?></textarea>
                </label>

                <h2>Change password</h2>
                <label>
                    Current password
                    <input type="password" name="pass_up" class="input-gray">
                </label>
                <label>
                    New password
                    <input type="password" name="repass_up" class="input-gray">
                </label>

                <input type="hidden" name="profile_id" value="<?= htmlentities($row['user_id']) ?>">
                <input type="submit" name="submit" class="edit-page__btn btn-blue" value="Save">
                <input type="submit" name="submit" class="edit-page__btn btn-gray" value="Cancel">
            </div>
        </form>
    </div>
</div>

<script>
    // document.getElementById("new-avater").addEventListener("change", function() {
    //     var reader = new FileReader();
    //     reader.onload = function(e) {
    //         document.getElementById("image").setAttribute('src', e.target.result);
    //     }
    // });
</script>