<header class="logged-in">
    <!-- TODO:place logo here. -->
    <div class="left-side">
        <!-- TODO: add logo  -->
        <img class="company-logo" src="" alt="">
        <!-- TODO:href take us back to index -->
        <a href="index.php">DevShop</a>
        <!-- homebutton -->
    </div>


    <div class="right-side">
        <div class="search-bar-div">
            <input class="search-bar" type="search" name="" id="" placeholder="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <img class="user-profile-pic" src="<?= $_SESSION['profile_img'] ?>">
        <!-- TODO:users can click on profile pic to take them to their profile -->
        <div>



            <!-- TODO:  add the index.php?action=""id="php user id here" -->

            <!-- MAKING CONNECTION TO MY PROFILE  -->
            <?php if (isset($_SESSION['id']) and isset($_GET['id']) and $_SESSION['id'] == $_GET['id']) { ?>
                <a href="index.php?action=editUser&id=<?= $_SESSION['id'] ?>">Edit Profile</a>
            <?php } else { ?>
                <a href="index.php?action=userProfileView&id=<?= $_SESSION['id'] ?>">My profile</a>
            <?php } ?>
            <a class="log-out-btn" href="index.php?action=logOut">Log Out</a>
        </div>
        <!-- <i class="fa-solid fa-user"></i> -->
    </div>
    <!-- nav bar? -->
</header>