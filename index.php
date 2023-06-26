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
            $gif = $_POST['gif'] ?? "";
            $description = $_POST['description'] ?? "";
            $title = $_POST['title'] ?? "";
            $tags = $_POST['tags'] ?? "";
            $languages = $_POST['languages'] ?? "";
            $project_id = $_GET['project_id'] ?? "";

            if ($gif and $description and $title and $tags and $languages and $project_id) {
                updateProject($gif, $description, $title, $tags, $languages, $_GET['project_id']);
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
        case "createUser":
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
                // TODO: NEEDS TO GO BACK TO SIGN UP PAGE WITH ERROR MESSAGE
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
            echo "<pre>";
            print_r($_POST);
            $gif = $_POST['gif'] ?? "";
            $title = $_POST['title'] ?? "";
            $description = $_POST['description'] ?? "";
            $tags = $_POST['tags'] ?? "";
            $languages = $_POST['languages'] ?? "";
            $user_id = $_SESSION['id'] ?? "";

            if ($user_id and $gif and $title and $description and $tags and $languages) {
                insertNewProject($user_id, $gif, $title, $description, $tags, $languages);
            } else {
                throw new Exception("Missing required information.");
            }
            break;

        case "logOut":
            logOut();
            break;

        case "logIn":
            $username = $_POST['username'] ?? "";
            $password = $_POST['password'] ?? "";
            if ($username and $password) {
                logIn($username, $password);
            }
            break;

            // FOR LOGGED IN USERS -- so that it doesn't take them to new page
        case "showUserPage":
            displayCards();
            // showUserPage();
            break;

            // FOR EDITING A USER
        case "editUser":
            $id = $_SESSION['id'];
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];
            if ($id and $username and $email) {
                editUser($id, $username, $email);
            } else {
                throw new Exception("The data is missing");
            }
            break;

            // EDITING THE USER

        case "submitEditedUser":
            $id = $_POST['id'] ?? "";
            $first_name = $_POST['firstName'] ?? "";
            $last_name = $_POST['lastName'] ?? "";
            $username = $_POST['username'] ?? "";
            $email = $_POST['email'] ?? "";
            $password = $_POST['password'] ?? "";
            $profile_image = $_POST['profileImage'] ?? "";
            $bio = $_POST['bio'] ?? "";
            $linked_in = $_POST['linkedIn'] ?? "";
            $git_hub = $_POST['gitHub'] ?? "";
            if (
                // 'OR' IS USED SO THAT A USER CAN EDIT ANY PIECE OF 
                // INFORMATION THEY WANT
                $id or
                $first_name or
                $last_name or
                $username or
                $email or
                $password or
                $profile_image or
                $bio or
                $linked_in or
                $git_hub
            ) {
                submitEditedUser(
                    $id,
                    $first_name,
                    $last_name,
                    $username,
                    $email,
                    $password,
                    $profile_image,
                    $bio,
                    $linked_in,
                    $git_hub
                );
            }
            break;
        case "getProjectVotes":
            // grab the status, project_id, and user_id from the GET parameters
            if (
                isset($_GET['user_id']) and
                isset($_GET['project_id']) and
                isset($_GET['stat'])

            ) {
                echo "After";

                getProjectVotes($_GET['user_id'], ($_GET['project_id']), ($_GET['stat']));
            } else {
                echo "Bad";
            }
            break;




        default:
            displayCards();
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
