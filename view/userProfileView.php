<?php

$title = "Batch 20 Final Project";
ob_start();
?>
<!-- TODO: do we want to include this header? -->
<?php include "./view/component/loggedInHeader.php" ?>
<main>
    <!-- <section class="user-profile-view-info"> -->
    <!-- <?php if (isset($_SESSION['id'])) { ?>
            <a href="index.php?action=editUser">Edit Profile</a>
        <?php
            } ?> -->

    <!-- </section> -->
    <section class="user-profile-view">
        <aside class="user-profile-info">


            <div id='profile-img' class=<?= $user_color ?>>

                <img id=<?= $user_color ?> src="<?= $profiles->profile_img ?>" alt="the photo of <?= $profiles->username; ?>">

            </div>
            <div>
                <h1 id='profile-name'><?= "$profiles->first_name $profiles->last_name"; ?></h1>
                <h1 id='profile-username'><?= $profiles->username; ?></h1>
            </div>


            <div class="user-profile-bio">
                <p><?= $profiles->bio ?></p>
                <span class="user-language-tag">
                    <?php
                    if (count($projects)) {
                        echo "<span id='languages'>Languages: </span>";
                        foreach ($profiles->languages as $language) {
                            echo "$language ";
                        }
                    }
                    ?>
                </span>
            </div>
            <hr>

            <!-- <div class="user-profile-languages"> -->
            <!-- feel free to change the span to something else to make it easier
            to style :) -->
            <!-- <span class="user-language-tag"> <?php
                                                    if (count($projects))
                                                        foreach ($profiles->languages as $language) {
                                                            echo "$language ";
                                                        } ?></span> -->
            <!-- </div> -->
            <div class="user-profile-socials">
                <h1 id='links-title'>Social Links: </h1>
                <div class="links">
                    <p class="social-link">
                        <a href="<?= $profiles->gitHub; ?>"><i class="fa-brands fa-2xl fa-github" style="color: #d2c3ee;"></i></a>
                    </p>
                    <p class="social-link">
                        <a href="<?= $profiles->linkedIn; ?>"><i class="fa-brands fa-2xl fa-linkedin" style="color: #d2c3ee;"></i></a>
                    </p>
                </div>

            </div>

            <?php if (isset($_SESSION) and $_SESSION['username'] === $profiles->username) { ?>
                <button><a href="index.php?action=add_project">Add a Project</a></button>
            <?php } ?>

        </aside>
        <div class="user-profile-projects">
            <h1>Projects: </h1>
            <div class="projects">
                <?php
                foreach ($projects as $project) {
                    include "./view/component/projectCard.php";
                }
                ?>
            </div>
        </div>


    </section>
</main>
<?php
$content = ob_get_clean();
require "template.php";
// if (isset($_SESSION['user_id'])) {
//     require "loggedInTemplate.php";
// } else {
//     require "nonLoggedInTemplate.php";
// }
?>

<script defer src="./public/js/projectVotes.js"></script>