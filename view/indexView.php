<?php

$title = "Batch 20 Final Project";
ob_start();
?>

<?php
if (isset($_SESSION['id'])) {
  include "./view/component/loggedInHeader.php";
}

?>

<!-- OVERALL CONTAINER -->
<div class="index-container">
  <!-- carousel container -->
  <div class="carousel-container" data-carousel>
    <button class="carousel-button prev" data-carousel-button='prev'>&#x2039</button>
    <button class="carousel-button next" data-carousel-button='next'>&#x203A</button>
    <div data-slides>
      <?php
      for ($i = 0; $i < count($carousels); $i++) {
        include "./view/component/carousel.php";
      }
      ?>
    </div>
    <!-- <div class="carousel-img"></div>
    <p class="cusername">username</p>
    <p class="ctitle">title of project</p>
    <p class="cdescription">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum dignissimos officiis nam id. Modi id amet ullam rem labore quam perspiciatis nulla explicabo aut pariatur fugiat eum, tenetur illum quis!</p>
    <button class="carousel-more">view more</button> -->
  </div>

  <!-- <h1>Landing Page</h1> -->
  <div class="project-container">
    <?php

    // echo "<h1>Landing Page</h1>";
    foreach ($projects as $project) {
      if ($project->is_active) {
        include "./view/component/projectCard.php";
      }
    }
    ?>

    <!-- SHOW MORE FOR LIMITS -->
    <a href="index.php?limit=<?= $limit ?>">show more</a>
  </div>




  <!-- </div> -->
  <script defer src="./public/js/carousel.js"></script>
  <script defer src="./public/js/projectVotes.js"></script>
  <!-- <script defer src="popUp.js"></script> -->
  <?php
  $content = ob_get_clean();


  if (isset($_SESSION['id'])) {
    require "loggedInTemplate.php";
  } else {
    require "nonLoggedInTemplate.php";
  }

  ?>
