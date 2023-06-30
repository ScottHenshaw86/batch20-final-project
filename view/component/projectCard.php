<div class="card">
    <a href="index.php?action=fullProjectPage&project_id=<?= $project->id ?>">
        <!-- muted attribute enables video to play when hovered. otherwise user would have to click anywhere on the page -->
        <video muted src="./public/uploaded_videos/<?= htmlspecialchars($project->video_src) ?>" class="project-mp4" onmouseover="this.play();" onmouseout="this.load();" onended="this.load()">
        </video></a>

    <!-- ***** Bottom Half of Project Card ***** -->
    <div class='outer-container'>
        <div class="bottom-card-container">
            <!-- this has same class as the header -->
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
        <div class="projectVotesContainer">
            <?php
            include "projectVotes.php";
            ?>
        </div>
    </div>

</div>