<?php

require "./controller/controller.php";

try {
    $action = $_GET['action'] ?? "";
    switch ($action) {
        case "Scott":
            // do something;
            break;
        default:
            showIndex();
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
