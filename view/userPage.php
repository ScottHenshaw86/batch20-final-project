<?php
$title = "User Page";
ob_start();
?>
<!-- TODO: this page was created for testing purposes
Please, fix it later -->

<h1>Hello new user</h1>
<p>Hello <?= $_SESSION['username'] ?></p>
<a href="index.php?action=editUser&id=<?= $_SESSION['id'] ?>">Edit the user info</a>
<?php


$content = ob_get_clean();

require "template.php";
?>