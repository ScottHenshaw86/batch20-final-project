<?php

$title = "Edit a user";
ob_start();

if (isset($_GET['error'])) {
    include './view/component/statusPopUp.php';
};

?>
<link href="./public/css/formInput.css" rel="stylesheet" />
<div id="edit-user-picture-form">

    <form action="index.php?action=submitEditedProfilePicture" enctype="multipart/form-data" method="POST" id="editForm">
        <p id="edit-user-picture">Edit Picture</p>
        <input type="hidden" accept="image/.png, image/jpeg, image/jpg" name="id" value="<?= $userinfo->id ?>">

        <p>
            <label for="profileImage">Profile Image: </label>
            <input type="file" name="profileImage" id="profileImage" value="<?= $userinfo->profile_img ?? "" ?>" accept="image/*">
        </p>
        <p>
            <input type="submit" value="submit changes" id="editSubmit">
        </p>
    </form>

    <?php
    $content = ob_get_clean();
    require "template.php";
    ?>