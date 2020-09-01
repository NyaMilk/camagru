<style>
    .notif {
        display: inline-block;
        position: relative;
        cursor: pointer;
        /* -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        tap-highlight-color: transparent; */
        margin-bottom: 50px;
    }

    .notif input {
        opacity: 0;
        position: absolute;
    }

    .notif input+span {
        display: inline-block;
        position: relative;
        /* width: 80px;
        height: 42px; */
        width: 100px;
        height: 52px;
        background: #e6e7ed;
        border-radius: 25px;
        transition: 0.3s;
    }

    .notif input+span:after {
        content: "";
        display: block;
        position: absolute;
        /* width: 40px;
        height: 40px; */
        width: 50px;
        height: 50px;
        border: 1px solid #e6e7ed;
        border-radius: 50%;
        background: #fff;
    }

    .notif input:checked+span {
        background: #49d1ca;
    }

    .notif input:checked+span:after {
        left: 50px;
    }
</style>
<div class="edit-page align_footer">
    <div class="container">
        <form class="edit-page__form" enctype="multipart/form-data" method="post">
            <div class="edit-page__form-img">
                <img id="current-avatar" src="<?= htmlentities($row['avatar']) ?>">
                <label class="btn-blue">
                    <input id="new-avatar" name="ava" type="file">
                    Change avatar
                </label>
            </div>
            <div class="edit-page__form-text">
                <h2>Notification</h2>
                <label class="notif">
                    <input type="checkbox" name="notific" value="yes" <?= $checked ?>>
                    <span></span>
                </label>

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

<script src="js/edit.js"></script>