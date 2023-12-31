<?php
require_once "./model/UserManager.php";

function showIndex()
{
    require "./view/indexView.php";
}

function addProject()
{
    require "./view/addProjectForm.php";
}

//TODO: CHECK PASSING PARAMATER??
function showUserProfile($user_id)
{
    if (isset($_SESSION['id'])) {
        // print_r($_SESSION['id']);
        $userManager = new UserManager();
        $projectManager = new ProjectManager();

        $projects = $projectManager->getUserProjects($user_id);
        $userInfo = $userManager->getUserInfo($user_id);
        $userLikes = $userManager->getUserLikes($user_id);
        $userLanguages = $userManager->getUserLanguages($user_id);

        // echo($userLikes);

        require "./view/userProfileView.php";
    } else {
        require "./view/signInForm.php";
    }
}

// CREATING A NEW USER
function createUser($username, $email, $password)
{
    $userManager = new UserManager();
    // select count of username + password
    //    -> if both counts == 0 -> we can add user
    //    -> else there is duplicate, show pop up error

    $userCount = $userManager->userExists($username)->count;

    $emailCount = $userManager->emailExists($email)->count;

    if ($userCount == 0 and $emailCount == 0) { // Both unique
        // echo "unique";
        $userManager->addUser($username, $email, $password);
        $message = urlencode("User created successfully. Please log in.");
        header("Location: index.php?action=signInForm&error=false&message=$message");
    } else { // already in db
        // echo "not unique";
        $message = urlencode("Username or email already exists");
        header("Location: index.php?action=add_user&error=true&message=$message");
    }
}

function addUser()
{
    require "./view/userAddForm.php";
}


function logInGoogle($username, $given_name, $family_name, $email, $picture, $signUp)
{
    $userManager = new UserManager();

    $userCount = $userManager->userExists($username)->count;

    $emailCount = $userManager->emailExists($email)->count;

    // IF WE ARE CREATING A NEW USER
    if ($userCount == 0 and $emailCount == 0 and $signUp === "false") {

        $message = urlencode("User does not exist. Please, sign up first");

        header("Location: index.php?action=add_user&error=false&message=$message");
    } else if ($userCount == 0 and $emailCount == 0) { // Both unique
        $user_id = $userManager->addUserGoogle($username, $given_name, $family_name, $email, $picture);

        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        $userInfo = $userManager->getUserInfo($user_id);
        $_SESSION['profile_img'] = $userInfo->profile_img;
        $_SESSION['id'] = $user_id;

        $message = urlencode("User created successfully.");

        header("Location: index.php?action=showUserPage&error=false&message=$message");
    } else if ($signUp === "true") {

        $message = urlencode("User already exists.");

        header("Location: index.php?action=signInForm&error=false&message=$message");
    } else { // already in db

        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        $userInfo = $userManager->getUserInfoGoogle($username);
        $_SESSION['profile_img'] = $userInfo->profile_img;
        $_SESSION['id'] = $userInfo->id;

        $message = urlencode("Welcome back!");

        header("Location: index.php?action=showUserPage&error=true&message=$message");
    }
}


function showSignInForm()
{
    require "./view/signInForm.php";
}

function logOut()
{
    session_destroy();
    // require "./view/signInForm.php";
    header("Location: index.php");
}

function logIn($username, $password)
{
    $userManager = new UserManager();
    $result = $userManager->logIn($username);



    if ($result->username === $username and password_verify($password, $result->password)) {

        $_SESSION['id'] = $result->id;
        $_SESSION['username'] = $result->username;
        $_SESSION['email'] = $result->email;
        $_SESSION['profile_img'] = $result->profile_img;
        $message = urlencode("You have succesfully logged in!");
        header("Location: index.php?action=showUserPage&error=false&message=$message");
        if ($password) {
            $_SESSION['password_exist'] = "password_exist";
        }
        $message = urlencode("You have succesfully logged in!");
        header("Location: index.php?action=showUserPage&error=false&message=$message");        // require "./view/indexView.php";
    } else {
        $message = urlencode("You have failed to login. Please try again");
        header("Location: index.php?action=signInForm&error=true&message=$message");
    }
}


function editUser($id)
{
    $userManager = new UserManager();
    $userinfo = $userManager->getUserInfo($id);
    require "./view/editUserForm.php";
}
function editUserPicture($id)
{
    $userManager = new UserManager();
    $userinfo = $userManager->getUserInfo($id);
    require "./view/editUserPictureForm.php";
}

function personalInfo($id)
{
    $userManager = new UserManager();
    $userinfo = $userManager->getUserInfo($id);
    require "./view/settings.php";
}

// EDITING A USER INFO
function submitEditedProfile(
    $id,
    $bio,
    $linked_in,
    $git_hub
) {
    $userManager = new UserManager();
    $userManager->submitEditedProfile(
        $id,
        $bio,
        $linked_in,
        $git_hub
    );

    header("Location: index.php?action=userProfileView&id=$id");
}

function uploadProfilePicture($profile_img, $id)
{
    $userManager = new UserManager();
    if ($profile_img["name"]) {
        //setting directory for where the profile image will be stored
        $target_dir = "./public/profile_images/";
        // set the path to the target directory
        $hashName = hash_file('md5', $profile_img["tmp_name"]);
        // get file extension name in lower case
        $imageFileType = strtolower(pathinfo($profile_img["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $hashName . "." . $imageFileType;
        $uploadErrors = [];
        $size = $profile_img['size'];
        // echo $size;
        if ($size > 250000) {
            $uploadErrors[] = "Your file is too large";
        }

        if (!empty($uploadErrors)) {
            $message = urlencode(implode(".", $uploadErrors));
            echo $message;
            header("Location: index.php?action=editUserPicture&error=true&id=$id&error=true&message=$message");
            // if everything is ok, try to upload file
        } else {
            move_uploaded_file($profile_img["tmp_name"], $target_file);

            // echo "The file " . htmlspecialchars(basename($profile_img["name"])) . " has been uploaded.";
            $userManager->uploadProfilePicture($id, $target_file);
            $message = urlencode("You uploaded your picture!");
            // echo $id;
            header("Location: index.php?action=userProfileView&id=$id");
        }
    } else {
        header("Location: index.php?action=editUserPicture&id=$id");
        $uploadErrors[] = "Please upload a photo.";
        $message = urlencode(implode(".", $uploadErrors));
        echo $message;
        header("Location: index.php?action=editUserPicture&error=true&id=$id&error=true&message=$message");
    }
}

function submitPersonalInfo(
    $id,
    $first_name,
    $last_name,
    $username,
    $email

) {
    $userManager = new UserManager();
    $userManager->submitPersonalInfo(
        $id,
        $first_name,
        $last_name,
        $username,
        $email
    );

    header("Location: index.php?action=userProfileView&id=$id");
}

function changePassword($id)
{
    require "./view/changePassword.php";
}

function submitChangePassword($id, $password)
{

    $userManager = new UserManager();
    $userManager->submitChangePassword($id, $password);

    header("Location: index.php?action=userProfileView&id=$id");
}

function  deleteProject($project_id)
{
    $userManager = new UserManager();
    $userManager->deleteProject($project_id);
    header("Location: index.php?action=userProfileView&id={$_SESSION['id']}");
}

// user goes to update FORM and updates project with 'project_id'
function updateProjectForm($project_id)
{
    $userManager = new UserManager();

    $project = $userManager->getProject($project_id);
    $languages = $userManager->getProjectLanguages($project_id);
    $tags = $userManager->getProjectTags($project_id);

    $languageInputVal = join(",", $languages);

    $tagsInputVal = join(",", $tags);

    require "./view/updateProjectForm.php";
}

// insert project updates into database
// function updateProject($video_source, $description, $title, $tags, $languages, $project_id)
function updateProject($video_source, $description, $title, $tags, $languages, $project_id, $hidden_video)
{
    $userManager = new UserManager();
    $maxsize = 31457280; // max size is 30MB in bytes
    $should_upload_video = isset($_FILES['video']) and ($_FILES['video']['size'] > 0);


    if (
        $should_upload_video and !empty($_FILES['video']['tmp_name'])
    ) {
        $name = $_FILES['video']['name'];
        $hashed_filename = hash_file('md5', $_FILES['video']['tmp_name']);
        $target_dir = "./public/uploaded_videos/";
        $allowedExtensions = array("mp4");
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (in_array($extension, $allowedExtensions)) {
            //$target_file specifies the path of the file to be uploaded
            $target_file = $target_dir . $hashed_filename . "." . $extension; //hash the file name here
            //saving the info to store in DB
            $video_source = $hashed_filename . "." . $extension;

            if (($_FILES['video']['size'] > $maxsize) or ($_FILES['video']['size'] == 0)) {
                $message = urlencode("Video file exceeds 30 MB. Failed to upload.");
                header("Location: index.php?action=updateProjectForm&project_id=$project_id&error=true&message=$message");
                return;
            } else if (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
                $userManager->updateProjectMain($video_source, $description, $title, $project_id);
                $userManager->updateProjectTags($tags, $project_id);
                $userManager->updateProjectLanguages($languages, $project_id);
            } else {
                //TODO: if regex works, use this
                // $message = urlencode("Video file exceeds 30 MB. Failed to upload.");
                // header("Location: index.php?action=updateProjectForm&project_id=$project_id&error=true&message=$message");

                header("Location: index.php?action=fullProjectPage&project_id=$project_id");
            }
        }
    } else {
        $userManager->updateProjectMain($hidden_video, $description, $title, $project_id);
        $userManager->updateProjectTags($tags, $project_id);
        $userManager->updateProjectLanguages($languages, $project_id);
    }
    //if submit button with filling out nothing, bring back to project page
    header("Location: index.php?action=fullProjectPage&project_id=$project_id");
    //TODO: if regex works, use this
    // $message = urlencode("Video file exceeds 30 MB. Failed to upload.");
    // header("Location: index.php?action=updateProjectForm&project_id=$project_id&error=true&message=$message");

}

function showUserPage()
{
    require "./view/indexView.php";
}
