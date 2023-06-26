<?php

$title = "Change Password";
ob_start();
?>
<link href="./public/css/formInput.css" rel="stylesheet" />

<div id="edit-user-form">

    <form action="index.php?action=submitChangePassword" method="POST" id="changePassword">
        <p id="edit-user-title">Change Password</p>
        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
        <p>
            <label for="password">New Password: </label>
            <input type="text" name="password" id="passwordEdit" value="">
        </p>
        <p>
            <span id="passwordNotValid">Not valid Password</span>
            <span id="passwordMissing">Password is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <input type="submit" value="Submit New Password" id="editSubmit">
        </p>
    </form>
</div>
<?php
$content = ob_get_clean();
require "template.php";
?>