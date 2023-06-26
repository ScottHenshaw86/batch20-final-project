<?php
$title = "Full Project Page HTML";
ob_start();
?>

<!-- <div>Header</div> PLACEHOLDER
TODO: add appropriate header template to the top of page-->
<a href="http://localhost/sites/batch20-final-project/index.php?action=showUserPage"><button>Back</button></a>
<a href="http://localhost/sites/batch20-final-project/index.php"><button>Home</button></a>
<div class="project-container">

  <div class="top-container"> <!--block elements -->
    <div class="titleTag"> <!--inline elements -->
      <div class="title"><?= $fullProject->title ?>
      </div>

      <div class="animation-container">
        <p>
          <img class="project-gif" src="<?= $fullProject->gif ?>" alt="user project gif">
        </p>
      </div>

      <div class="userLang"> <!--inline elements -->
        <div class="username"><?= $fullProject->username ?></div>
        <div class="languages">
          <?php
          for ($i = 0; $i < count($fullProject->languages); $i++) {

            echo $fullProject->languages[$i] . " ";
          }; ?>
        </div>
      </div>
    </div>
    <div>
      <!-- <?php
            echo "<pre>";
            print_r($fullProject);
            echo "</pre>";
            ?> -->
    </div>

    <div class="bottom-container"> <!--inline elements -->
      <div class="tags"><?php foreach ($tags as $tag) {
                          echo "$tag " . " ";
                        } ?></div>
    </div>

    <div class="description"><?= $fullProject->description ?></div>

    <div class="modify"></div>
    <a href="">Edit</a> <!--edit and delete, block elements -->
    <a href="">Delete</a>
  </div>

</div>

<?php
$content = ob_get_clean();
require "projectTemplate.php";
// require "template.php";
?>