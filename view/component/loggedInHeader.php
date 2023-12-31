<header class="logged-in">
    <!-- TODO:place logo here. -->
    <div class="left-side">
        <a href="index.php"><img class="company-logo" src="./public/css/logo.png" alt="Company's logo"></a>
        <!-- TODO:href take us back to index -->
        <a href="index.php">DevShop</a>
        <!-- homebutton -->
    </div>


    <!-- <div class="right-side-wrap"> -->

    <div class="right-side">

        <div class="search-bar-div">
            <input class="search-bar" type="search" name="input" id="input" placeholder="Search">

            <!-- <i class="fa-solid fa-magnifying-glass"></i> -->
        </div>


        <div class="click-user-wrap">

            <input type="checkbox" name="checkbox" id="checkbox">
            <label for="checkbox" id="blur-overlay"></label>

            <label for="checkbox" id="checkbox-label">
                <?php if (!(isset($_SESSION['id']) and isset($_GET['id']) and $_SESSION['id'] == $_GET['id'])) { ?>
                    <a href="index.php?action=userProfileView&id=<?= $_SESSION['id'] ?>">
                        <div class="click-user">
                            <img class="user-profile-pic" src="<?= htmlspecialchars($_SESSION['profile_img'] ?? "") ?>">
                            <p><?= htmlspecialchars($_SESSION['username'] ?? "") ?></p>
                        </div>
                    </a>
                <?php } else { ?>
                    <div class="click-user-page">
                        <img class="user-profile-pic" src="<?= htmlspecialchars($_SESSION['profile_img'] ?? "") ?>">
                        <!-- <p><?= htmlspecialchars($_SESSION['username'] ?? "") ?></p> -->
                        <p><?= htmlspecialchars($userInfo->username ?? "") ?></p>
                    </div>
                <?php } ?>
            </label>




            <?php if (isset($_SESSION['id']) and isset($_GET['id']) and $_SESSION['id'] == $_GET['id']) { ?>
                <div class="popup-container">
                    <p><button><a href="index.php?action=editUser&id=<?= $_SESSION['id'] ?>">Edit Profile</a></button></p>
                    <p><button><a href="index.php?action=personal_info&id=<?= $_GET['id'] ?>">Edit Personal Information</a></button></p>
                    <?php if (isset($_SESSION['password_exist'])) { ?>
                        <p><button><a href="index.php?action=change_password&id=<?= $_SESSION['id'] ?>">Change Password</a></button></p>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>

        <input type="checkbox" name="checkbox" id="checkbox">
        <label for="checkbox" id="blur-overlay"></label>



        <!-- TODO:users can click on profile pic to take them to their profile -->
        <div>
            <!-- TODO:  add the index.php?action=""id="php user id here" -->

            <!-- MAKING CONNECTION TO MY PROFILE  -->
            <!-- <?php if (!(isset($_SESSION['id']) and isset($_GET['id']) and $_SESSION['id'] == $_GET['id'])) { ?>
                    <a class="my-profile" href="index.php?action=userProfileView&id=<?= $_SESSION['id'] ?>">My profile</a>


                <?php } ?> -->


            <!-- <a href="index.php?action=editUser&id=<?= $_SESSION['id'] ?>">Edit Profile</a> -->
            <a class="log-out-btn" href="index.php?action=logOut">Log Out</a>
        </div>
        <!-- <i class="fa-solid fa-user"></i> -->
    </div>


    <input type="checkbox" name="toggle" id="toggle">
    <label for="toggle" id="toggle">&#8942;</label>
    <nav class="dropdown-responsive">
        <ul class="drop-items">
            <a href="index.php?action=logOut">
                <li>Log Out</li>
            </a>

        </ul>
    </nav>
</header>