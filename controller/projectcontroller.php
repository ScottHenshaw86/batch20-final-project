<?php
require_once "./model/ProjectManager.php";

function displayCards($offset = 0)
{
    
    $projectManager = new ProjectManager();
    $carousels = $projectManager->getCarousels();
    $projects = $projectManager->getCards($offset);
    $votes = $projectManager->getUserVotes();
    $projectCount = $projectManager->getCount();
    
    if ($offset > 0) {
        foreach($projects as $project) {
            require "./view/component/projectCard.php";
        }
    } else {
        require './view/indexView.php';
    }
    // echo "<pre>";
    // print_r($votes);
    // echo "</pre>";
    // TODO: uncomment this
}

function getSearchInfo($query)
{
    $projectManager = new ProjectManager();
    $projects = $projectManager->getProjectSearch($query);
    foreach ($projects as $project) {
        require "./view/component/projectCard.php";
    }
}

function displayFullProject($project_id)
{
    $projectManager = new ProjectManager();
    $userManager = new UserManager();
    $userInfo = $userManager->getUserInfo($project_id);
    $fullProject = $projectManager->displayFullProject($project_id);
    $tags = $userManager->getProjectTags($project_id);
    // Use the project id (from the router)
    // to call a model function that will get all of the data for that specific project
    require "./view/projectPage.php";
}

function getProjectVotes($user_id, $project_id, $stat)
{
    $projectManager = new ProjectManager();
    $status = $projectManager->projectVotes($user_id, $project_id, $stat);

    echo $status;
}
function insertNewProject($user_id, $video_source, $title, $description, $tags, $languages)
{
    $projectManager = new ProjectManager();

    //UPLOADING VIDEO
    // UPLOAD TO: $target_dir . $hashed_filename . $extension
    // WHEN INSERTING INTO DB: $hashed_filename . $extension
    $maxsize = 5242880; // max size is 30s video: 5MB in bytes
    if (
        isset($_FILES['video']['name']) and ($_FILES['video']['name'] != "")
    ) {

        $name = $_FILES['video']['name'];
        //saves unique name for each uploaded file
        $hashed_filename = hash_file('md5', $_FILES['video']['tmp_name']);

        //$target_dir specifies the directory
        $target_dir = "./public/uploaded_videos/";
        $allowedExtensions = array("mp4"); //shows the kinds of files allowed in the array
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION)); //extracts the file extension from the file name
        // in_array("mp4", $extensions)
        if (in_array($extension, $allowedExtensions)) {
            //$target_file specifies the path of the file to be uploaded
            $target_file = $target_dir . $hashed_filename . "." . $extension; //hash the file name here

            //saving the info to store in DB
            $video_source = $hashed_filename . "." . $extension;

            //check the uploaded file
            if (($_FILES['video']['size'] > $maxsize) or ($_FILES['video']['size'] == 0)) {
                $message = urlencode("Your file is too big. Please upload a file smaller than 5 MB.");
                header("Location: index.php?action=insertNewProject&error=true&message=$message");
                //TODO: uncomment
            } else if
            //move_uploaded_file paramaters are the file name of the uploaded file to
            //the destination it needs to be moved
            (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
                $projectManager->insertNewProject($user_id, $video_source, $title, $description, $tags, $languages);
            } else {
                $message = urlencode("Failed to upload video file.");
                header("Location: index.php?action=insertNewProject&error=true&message=$message");
                //TODO: uncomment
            }
        }
    }

    header("Location: index.php");
}

function getFilteredProjects($filter, $limit)
{
    $projectManager = new ProjectManager();
    if ($filter == 'mostRecent') {
        $projects = $projectManager->getMostRecentProjects($limit);
    } else if ($filter == 'mostLikes') {
        $projects = $projectManager->getMostLikedProjects($limit);
    }
    
    foreach ($projects as $project) {
        require "./view/component/projectCard.php";
    }
}
