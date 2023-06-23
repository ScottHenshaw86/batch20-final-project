<?php

$title = "Batch 20 Final Project";
ob_start();
?>
<!-- TODO: do we want to include this header? -->
<?php include "./view/component/loggedInHeader.php" ?>
<main>
     

    <!-- </section> -->
    <section class="user-profile-view">
        <aside class="user-profile-info">
            <div id='profile-img'>
                <img src="<?= $userInfo->profile_img ?>" alt="the photo of <?= $userInfo->username; ?>">
            </div>
            <div>
                <h1 id='profile-name'><?= "$userInfo->first_name $userInfo->last_name"; ?></h1>
                <h1 id='profile-username'><?= $userInfo->username; ?></h1>
            </div>


            <div class="user-profile-bio">
                <p><?= $userInfo->bio ?></p>
                <span class="user-language-tag">
                    <?php
                    echo "<span id='languages'>Languages: </span>";
                    if (count($projects)) {
                        $languages = join(", ", $userLanguages);
                        echo $languages;
                    } else {
                        echo "<i class='default'>none</i>";
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
                                                        foreach ($userInfo->languages as $language) {
                                                            echo "$language ";
                                                        } ?></span> -->
            <!-- </div> -->
            <div class="user-profile-socials">
                <h1 id='links-title'>Social Links: </h1>
                <div class="links">
                    <p class="social-link">
                        <a href="<?= $userInfo->gitHub; ?>"><i class="fa-brands fa-2xl fa-github" style="color: #d2c3ee;"></i></a>
                    </p>
                    <p class="social-link">
                        <a href="<?= $userInfo->linkedIn; ?>"><i class="fa-brands fa-2xl fa-linkedin" style="color: #d2c3ee;"></i></a>
                    </p>
                </div>

            </div>

            
            <button><a href="index.php?action=add_project">Add a Project</a></button>
     

        </aside>
        <div class="user-profile-projects">
            <h1>Projects: </h1>
            <div class="projects">
                <?php
                if (count($projects)) {
                    foreach ($projects as $project) {
                        include "./view/component/projectCard.php";
                    }
                } else {
                    echo "<i class='default'>No Projects</i>";
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