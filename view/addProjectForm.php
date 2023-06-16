<?php
$title = "Add Project";
ob_start();
?>

<h1>Add Project</h1>

<form action="index.php?action=add_project" method="POST">
    <p>
        <label for="gif">Gif: </label>
        <input type="text" name="gif" id="gif">
    </p>

    <p>
        <label for="title">Title: </label>
        <input type="text" name="title" id="title">
    </p>

    <p>
        <label for="tags">Tags: </label>
        <input type="text" name="tags" id="tags">
    </p>

    <p>
        <label for="description">Description: </label>
        <input type="text" name="description" id="description">
    </p>

    <p>
        <label for="Languages">Languages: </label>
        <input type="text" name="languages" id="languages">
    </p>

    <input type="submit" value="Add Project">
</form>

<?php
$content = ob_get_clean();
require "template.php";
?>