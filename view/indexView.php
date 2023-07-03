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

  <div class="about-container">
    <!-- <marquee>ABOUT US!!!!!!!ABOUT US!!!!!!!ABOUT US!!!!!!!ABOUT US!!!!!!!ABOUT US!!!!!!!ABOUT US!!!!!!!</marquee> -->
    <p>DevShop is a platform that brings together developers of all skill levels to upload, share, and explore a wide range of projects. This makes it easier for others to discover like-minded developers and engage in meaningful collaborations.With DevShop, you can embark on a journey of exploration, inspiration, and growth. Unleash your creativity, share your expertise, and collaborate with fellow developers as you shape the future of software development.</p>
  </div>



  <!-- FILTER PROJECTS -->
  <select name="filter" id="filter" onchange="filterProjects(this.value)">
    <option value="" selected disabled hidden>Filter</option>
    <option value="mostRecent">Most Recent</option>
    <option value="mostLikes">Most Likes</option>
  </select>
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
  </div>
  <!-- SHOW MORE FOR LIMITS -->
  <button class="more"><a href="index.php?action=showUserPage&limit=<?= $limit ?>" onclick="scrollDown()">show more</a></button>


  <!-- </div> -->
  <script defer src="./public/js/carousel.js"></script>
  <script defer src="./public/js/projectVotes.js"></script>
  <script defer src="./public/js/filterProjects.js"></script>
  <script defer src="./public/js/scrollDown.js"></script>


  <!-- <script defer src="popUp.js"></script> -->
  <?php
  $content = ob_get_clean();


  if (isset($_SESSION['id'])) {
    require "loggedInTemplate.php";
  } else {
    require "nonLoggedInTemplate.php";
  }

  if (isset($_GET["limit"])) {

  ?>
    <script>
      window.scrollTo(0, document.body.scrollHeight);
    </script>
  <?php

  }


  ?>
  <?php
  include "./view/footer.php";
  ?>