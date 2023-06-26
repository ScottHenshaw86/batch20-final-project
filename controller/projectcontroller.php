<?php
require_once "./model/ProjectManager.php";

function displayCards()
{
    $projectManager = new ProjectManager();
    $projects = $projectManager->getCards();
    require './view/indexView.php';
}

function getProjectVotes($user_id, $project_id, $stat)
{

    $projectManager = new ProjectManager();
    $status = $projectManager->projectVotes($user_id, $project_id, $stat);
    echo $status;
    // header('./view/userProfileView.php');
    // header("Location: index.php");
}
