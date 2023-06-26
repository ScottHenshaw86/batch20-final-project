<div class="card">

    <p>
        <a href="index.php?action=fullProjectPage&project_id=<?= $project->id ?>"> <img class="project-gif" src="<?= $project->gif ?>" alt="user project gif"></a>
    </p>

    <!-- ***** Bottom Half of Project Card ***** -->
    <div class='outer-container'>
        <div class="bottom-card-container">
            <!-- this has same class as the header -->
            <!-- TODO: when you click on the profile pic, it will take you to user profile view -->

            <a href="index.php?action=userProfileView&id=<?= $project->user_id ?>"><img class="user-profile-pic" src="<?= htmlspecialchars($project->profile_img) ?>" alt="user profile pic">
            </a>
            <div class="project-description">

                <span class="project-title">
                    <?= htmlspecialchars($project->title) ?>
                </span>

                <span class="language-tag"> <?php
                                            echo join(", ", $project->languages) ?></span>
            </div>

        </div>
        <!-- Upvote + Downvote Buttons -->
        <div class="project-votes-container">
            <?php
            include "projectVotes.php";
            ?>
        </div>
    </div>

</div>