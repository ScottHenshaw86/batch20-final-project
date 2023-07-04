<?php
session_start();

require "./controller/controller.php";
require "./controller/projectcontroller.php";

try {
    $action = $_GET['action'] ?? "";
    switch ($action) {
            // TODO: link add project btn to "index.php?action=add_project"
        case "add_project":
            addProject();
            break;

            // TODO: link delete project btn to "index.php?action=delete_project&project_id='projectid'"
        case "delete_project":
            $project_id = $_GET['project_id'] ?? "";

            if ($project_id) {
                deleteProject($project_id);
            } else {
                throw new Exception("Missing project id in GET parameter");
            }

            break;

            // TODO: link update project btn to "index.php?action=updateProjectForm&project_id=*"
        case "updateProjectForm":
            $project_id = $_GET['project_id'] ?? "";
            if ($project_id) {
                updateProjectForm($project_id);
            } else {
                throw new Exception("Missing project id");
            }

            break;

        case "updateProject":
            //$hidden_video used for submitting update form without a video
            $hidden_video = $_POST['hiddenVideo'] ?? "";
            $video_src = $_FILES['video'] ?? "";
            $description = $_POST['description'] ?? "";
            $title = $_POST['title'] ?? "";
            $tags = $_POST['tags'] ?? "";
            $languages = $_POST['languages'] ?? "";
            $project_id = $_GET['project_id'] ?? "";

            if ($video_src and $description and $title and $tags and $languages and $project_id and $hidden_video) {
                updateProject($video_src, $description, $title, $tags, $languages, $_GET['project_id'], $hidden_video);
            } else {
                throw new Exception("Error, missing project info");
            }


            break;

        case "add_user":
            addUser();
            break;

            //CREATING USER PROFILE VIEW PAGE
        case "userProfileView":
            if (isset($_GET['id'])) {
                showUserProfile($_GET['id']);
            } else {
                throw new Exception("error");
            }
            break;

            // CREATING A NEW USER 
        case "createUser": // add new user to database
            $username = $_POST['username'] ?? "";
            $email = $_POST['email'] ?? "";
            $password = $_POST['password'] ?? "";
            $password_confirm = $_POST['password_confirm'] ?? "";
            // TODO: Check password confirm
            // 

            if ($username and $email and $password and $password_confirm and $password === $password_confirm) {
                createUser($username, $email, $password, $password_confirm);
            } else {
                // throw new Exception("Couldn't create your account, missing required information.");
                // TODO: NEEDS TO GO BACK TO SIGN UP PAGE WITH ERROR MESSAGE (maybe set action=add_user?)
                $message = urlencode("Sign up failed");
                header("Location: index.php?action=signInForm&error=true&message=$message");
            }
            break;

        case "signInForm":
            showSignInForm();
            break;

        case "insertNewProject":
            if (!$_SESSION['id']) {
                throw new Exception("Missing user id");
            }

            // echo "<pre>";
            // print_r($_FILES['video']);
            // echo "</pre>";
            $video_src = $_FILES['video'] ?? "";
            $title = $_POST['title'] ?? "";
            $description = $_POST['description'] ?? "";
            $tags = $_POST['tags'] ?? "";
            $languages = $_POST['languages'] ?? "";
            $user_id = $_SESSION['id'] ?? "";

            if ($user_id and $video_src and $title and $description and $tags and $languages) {
                insertNewProject($user_id, $video_src, $title, $description, $tags, $languages);
            } else {
                throw new Exception("Missing required information.");
            }
            break;

        case "logOut":
            logOut();
            break;

        case "logIn":
            $username = $_POST['username'];
            $password = $_POST['password'];
            if ($username and $password) {
                logIn($username, $password);
            } else {
                // do front-end validation + back-end validation
            }
            break;


            // FOR LOGING IN WITH GOOGLE BUTTON
        case "googleLogIn":
            if (isset($_GET['signUp'])) {
                $signUp = $_GET['signUp'];
            }

            $token = $_REQUEST['credential'];
            $jwt = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

            $currentdate = time();

            if (
                $jwt->aud === getenv("GOOGLE_CLIENT_ID") &&
                ($jwt->iss === "accounts.google.com" || $jwt->iss === "https://accounts.google.com") &&
                $currentdate < $jwt->exp
            ) {
                // FOR SPLITTING THE EMAIL ON @ SIGN, AND MAKING A USERNAME
                $username = substr($jwt->email, 0, strpos($jwt->email, "@"));
                logInGoogle($username, $jwt->given_name, $jwt->family_name, $jwt->email, $jwt->picture, $signUp);
            }
            break;

            // FOR LOGGED IN USERS -- so that it doesn't take them to new page
        case "showUserPage":
            displayCards(); // TODO: change to show all projects
            // showUserPage();
            break;
            // FOR EDITING A USER
        case "editUser":
            if (isset($_GET['id'])) {
                editUser($_GET['id']);
            } else {
                throw new Exception("The data is missing");
            }
            break;

        case "personal_info":
            if (isset($_GET['id'])) {
                personalInfo($_GET['id']);
            } else {
                throw new Exception("The data is missing");
            }
            break;

            // EDITING THE USER

        case "editUserPicture":
            if (isset($_GET['id'])) {
                editUserPicture($_GET['id']);
            } else {
                throw new Exception("The data is missing");
            }
            break;

        case "submitEditedProfilePicture":
            $id = $_POST['id'] ?? "";
            $profile_image = $_FILES['profileImage'];
            // $hidden_image = $_POST['hiddenImage'];

            if (
                $id and $profile_image
            ) {
                uploadProfilePicture($profile_image, $id);
            }
            break;

        case "submitEditedProfile":


            $id = $_POST['id'] ?? "";
            $bio = $_POST['bio'] ?? "";
            $linked_in = $_POST['linkedIn'] ?? "";
            $git_hub = $_POST['gitHub'] ?? "";

            if (
                // 'OR' IS USED SO THAT A USER CAN EDIT ANY PIECE OF 
                // INFORMATION THEY WANT

                $id and
                $bio or
                $linked_in or
                $git_hub
            ) {
                submitEditedProfile(
                    $id,
                    $bio,
                    $linked_in,
                    $git_hub
                );
            }

            break;

        case "submitPersonalInfo":
            $id = $_POST['id'] ?? "";
            $first_name = $_POST['firstName'] ?? "";
            $last_name = $_POST['lastName'] ?? "";
            $username = $_POST['username'] ?? "";
            $email = $_POST['email'] ?? "";
            if (
                // 'OR' IS USED SO THAT A USER CAN EDIT ANY PIECE OF 
                // INFORMATION THEY WANT
                $id and
                $first_name or
                $last_name or
                $username or
                $email
            ) {
                submitPersonalInfo(
                    $id,
                    $first_name,
                    $last_name,
                    $username,
                    $email
                );
            }
            break;

        case "change_password":
            if ($_GET['id']) {
                changePassword($_GET['id']);
            }
            break;

        case "submitChangePassword":
            if ($_POST['password'] and $_POST['id']) {
                submitChangePassword($_POST['id'], $_POST['password']);
            }
            break;

        case "getProjectVotes":
            // grab the status, project_id, and user_id from the GET parameters
            if (
                isset($_GET['user_id']) and
                isset($_GET['project_id']) and
                isset($_GET['stat'])
            ) {
                getProjectVotes($_GET['user_id'], ($_GET['project_id']), ($_GET['stat']));
            } else {
                throw new Exception("The data is missing");
            }
            break;

        case "fullProjectPage":
            // when you click on a project, it should bring you here
            // with a GET parameter with the project id
            $project_id = $_GET['project_id'] ?? "";
            // $profile_image = $_POST['profileImage'] ?? "";
            if ($project_id) {
                displayFullProject($project_id);  //$profile_image//
            };
            break;

        case "filter":
            $limit = $_GET['limit'] ?? 4;
            if (isset($_GET['filterOn'])) {
                getFilteredProjects($_GET['filterOn'], $limit);
            } else {
                throw new Exception("Missing filter value");
            }
            break;

        case "search_data":
            if (isset($_GET['query'])) {
                getSearchInfo($_GET['query']);
            }
            break;

        default:
            $offset = $_GET['offset'] ?? 0;
            displayCards($offset);
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
