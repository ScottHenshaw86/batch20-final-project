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
            <div class="user-info">
                <div class="flex">
                    <div id='profile-img' data-tooltip="Change the profile image">
                        <a href="index.php?action=editUserPicture&id=<?= $_GET['id'] ?>"><img src="<?= htmlspecialchars($userInfo->profile_img) ?>" alt="profile image"></a>
                    </div>
                    <div id="profile">
                        <h1 id='profile-name'><?= htmlspecialchars($userInfo->first_name) . " " . htmlspecialchars($userInfo->last_name); ?></h1>
                        <h1 id='profile-username'><?= "@" . htmlspecialchars($userInfo->username) ?></h1>
                    </div>
                </div>

                <div class="user-stats">
                    <h1><?= count($projects) . " projects" ?></h1>
                    <h1><?= count($userLanguages) . " languages" ?></h1>
                    <h1><?= $userLikes . " likes" ?></h1>

                </div>

                <div class="user-profile-bio">
                    <p><?= htmlspecialchars($userInfo->bio) ?? "" ?></p>
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

                <div class="social-links">
                    <?php if ($userInfo->gitHub) {
                        echo "<a href='<?=" .  htmlspecialchars($userInfo->gitHub) . "; ?>'><i class='fa-brands fa-2xl fa-github'></i></a>";
                    }
                    ?>

                    <?php if ($userInfo->linkedIn) {
                        echo "<a href='<?=" . $userInfo->linkedIn . "; ?>''><i class='fa-brands fa-2xl fa-linkedin'></i></a>";
                    }
                    ?>
                </div>

            </div>
            </div>






            <!-- FOR DISPLAYING THE 'ADD PROJECT' ONLY WHEN 'MY PROJECT' IS CLICKED -->
            <?php if (isset($_SESSION['id']) and isset($_GET['id']) and $_SESSION['id'] == $_GET['id']) { ?>
                <p><button id="add-project-btn"><a href="index.php?action=add_project">Add a Project</a></button></p>

            <?php } ?>


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
<script defer src="./public/js/projectVotes.js"></script>
<?php
$content = ob_get_clean();
require "template.php";
?>