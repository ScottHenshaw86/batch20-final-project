<?php

$title = "Edit a user";
ob_start();
?>
<link href="./public/css/formInput.css" rel="stylesheet" />

<div id="edit-user-form">
    <form action="index.php?action=submitEditedProfile" method="POST" id="editForm">
        <p id="edit-user-title">Edit Profile</p>
        <input type="hidden" name="id" value="<?= $userinfo->id ?>">


        <p>
            <label for="bio">Bio: </label>
            <input type="text" name="bio" id="bio" value="<?= $userinfo->bio ?? "" ?> ">
        </p>
        <p>
            <label for="linkedIn">LinkedIn: </label>
            <input type="text" name="linkedIn" id="linkedIn" value="<?= $userinfo->linkedIn ?? "" ?>">
        </p>
        <p>
            <label for="gitHub">GitHub: </label>
            <input type="text" name="gitHub" id="gitHub" value="<?= $userinfo->gitHub ?? "" ?>">
        </p>

        <p>
            <input type="submit" value="submit changes" id="editSubmit">
        </p>
    </form>

</div>
<?php
$content = ob_get_clean();
require "template.php";
?>