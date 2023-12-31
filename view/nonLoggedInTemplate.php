<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="./public/css/main.css" rel="stylesheet" />
    <link href="./public/css/projectCard.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./public/css/logo2.png">
    <script defer src="https://kit.fontawesome.com/808c973e0c.js" crossorigin="anonymous"></script>
    <script defer src="./public/js/popUp.js"></script>
    <script defer src="./public/js/search.js"></script>



</head>

<body>
    <!-- CAN VIEW :::
  MAIN PAGE // OPENING PAGE // OVERALL CARD PAGES (SAME THING THAT LOGGED IN USERS CAN SEE)
  LOGIN IN PAGE
  SIGN UP PAGE
  INDIVIDUAL CARDS
  PROFILES
    -->

    <!-- TODO: INCLUDE HEADER FOR LOGGED IN USERS -->
    <?php
    include "./view/component/loggedOutHeader.php";
    if (isset($_GET['error'])) {
        include "./view/component/statusPopUp.php";
    }
    ?>
    <?= $content; ?>
</body>

</html>