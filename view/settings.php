<?php

$title = "Edit a user";
ob_start();
?>
<link href="./public/css/formInput.css" rel="stylesheet" />

<div id="settings-form">
    <form action="index.php?action=submitPersonalInfo" method="POST" id="settingsForm">
        <p id="edit-user-title">Edit Personal Information</p>
        <input type="hidden" name="id" value="<?= $userinfo->id ?>">
        <p>
            <label for="firstName">First Name: </label>
            <input type="text" name="firstName" id="firstName" value="<?= $userinfo->first_name ?? "" ?>">
        </p>
        <p>
            <span id="firstNameNotValid">Not valid First Name</span>
            <span id="firstNameMissing">First Name is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="lastName">Last Name: </label>
            <input type="text" name="lastName" id="lastName" value="<?= $userinfo->last_name ?? "" ?>">
        </p>
        <p>
            <span id="lastNameNotValid">Not valid Last Name</span>
            <span id="lastNameMissing">Last Name is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="username">Username: </label>
            <input type="text" name="username" id="usernameEdit" value="<?= $userinfo->username ?>">
        </p>
        <p>
            <span id="usernameNotValid">Not valid Username</span>
            <span id="usernameMissing">Username is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="email">E-mail: </label>
            <input type="text" name="email" id="emailEdit" value="<?= $userinfo->email ?>">
        </p>
        <p>
            <span id="emailNotValid">Not valid E-mail</span>
            <span id="emailMissing">E-mail is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <input type="submit" value="Submit Changes" id="editSubmit">
        </p>
    </form>
</div>
<?php
$content = ob_get_clean();
require "template.php";
?>