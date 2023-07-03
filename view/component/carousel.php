<div class="slide" <?php if ($i == 0) echo "data-active" ?>>
    <video autoplay loop muted src="./public/uploaded_videos/<?= htmlspecialchars($carousels[$i]->video_src) ?>"></video>
    <div class="flex">
        <img class="carousel-profile-img" src="<?= htmlspecialchars($carousels[$i]->profile_img) ?>" alt="">
        <p class="carousel-username"><?= htmlspecialchars($carousels[$i]->username) ?></p>
    </div>
    <h1><?= htmlspecialchars($carousels[$i]->title) ?></h1>
    <p class="carousel-description"><?= htmlspecialchars($carousels[$i]->description) ?></p>
    <button class="viewMore" data-projectid=<?= htmlspecialchars($carousels[$i]->project_id) ?>>More Details</button>
</div>