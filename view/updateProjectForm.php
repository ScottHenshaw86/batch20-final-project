<?php
$title = "Update Project";
ob_start();
?>
<link rel="stylesheet" href="./public/css/addProjectForm.css">
<script defer src="https://kit.fontawesome.com/033b80222d.js" crossorigin="anonymous"></script>
<script defer src="./public/js/validateAddProjectForm.js"></script>

<h1>Update Project</h1>

<form action="index.php?action=updateProject&project_id=<?= $project->id ?>" method="POST">
    <p>
        <label for="gif">Gif: </label>
        <input type="text" name="gif" id="gif" value="<?= htmlspecialchars($project->gif)  ?>">
    </p>

    <p>
        <label for="title">Title: </label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($project->title) ?>">
    </p>

    <p>
        <span>Tags: </span>
    <p class="tag-container">
        <input type="text" name="tags" id="tags" value="<?= htmlspecialchars($tagsInputVal) ?>">
    </p>
    </p>

    <p>
        <label for="description">Description: </label>
        <input type="text" name="description" id="description" value="<?= htmlspecialchars($project->description) ?>">
    </p>

    <div>
        <span>Languages: </span>
        <div class="languages-container">
            <input type="text" name="languages" id="languagesInput" value="<?= htmlspecialchars($languageInputVal) ?>">
            <p id="languageResults"></p>
        </div>
    </div>

    <input type="submit" value="Update Project" id="submit">
</form>


<?php
$content = ob_get_clean();
require "formTemplate.php";
?>