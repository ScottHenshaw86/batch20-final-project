<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?= $title; ?></title>
    <link href="./public/css/main.css" rel="stylesheet" />


</head>

<body>
    <!-- LOGGED IN CAN VIEW :::
  MAIN PAGE // OPENING PAGE // OVERALL CARD PAGES (SAME THING THAT LOGGED IN USERS CAN SEE)
  LOGIN IN PAGE
  SIGN UP PAGE
  LOGOUT PAGE

  CANNOT VIEW:::
  INDIVIDUAL CARD PAGES
  LOG OUT PAGE
    -->

    <!-- TODO: INCLUDE HEADER FOR NON LOGGED IN USERS -->
    <?php

    if (isset($_GET['error'])) {
        include "./view/component/statusPopUp.php";
    }
    ?>
    <?= $content; ?>

    <footer>
        <!-- TODO: INCLUDE FOOTER FOR NON LOGGED IN USERS -->
    </footer>

</body>

</html>