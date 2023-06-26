<?php
require_once "./model/ProjectManager.php";

function displayCards()
{
    $projectManager = new ProjectManager();
    $projects = $projectManager->getCards();
    require './view/indexView.php';
}

function displayFullProject($project_id)
{
    $projectManager = new ProjectManager();
    $userManager = new UserManager();
    $fullProject = $projectManager->displayFullProject($project_id);
    $tags = $userManager->getProjectTags($project_id);
    // Use the project id (from the router)
    // to call a model function that will get all of the data for that specific project
    require "./view/projectPage.php"; // TODO: uncomment this!
}
function getProjectVotes($user_id, $project_id, $stat)
{

    $projectManager = new ProjectManager();
    $status = $projectManager->projectVotes($user_id, $project_id, $stat);
    echo $status;
    // header('./view/userProfileView.php');
    // header("Location: index.php");
}
