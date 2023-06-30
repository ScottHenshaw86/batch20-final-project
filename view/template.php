<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?= $title; ?></title>
    <link href="./public/css/main.css" rel="stylesheet" />
    <link href="./public/css/projectCard.css" rel="stylesheet">
    <script defer src="./public/js/validateSignUp.js"></script>
    <script src="https://kit.fontawesome.com/74c73035c9.js" crossorigin="anonymous"></script>
    <script defer src="./public/js/validateEditUser.js"></script>
    <script defer src="./public/js/search.js"></script>

    <!-- FOR SIGN IN WITH GOOGLE -->
    <meta name="google-signin-client_id" content="<?= getenv("GOOGLE_CLIENT_ID") ?>">
    <!-- ======================== -->

</head>

<body>
    <?php
    if (isset($_GET['error'])) {
        include "./view/component/statusPopUp.php";
    }
    ?>
    <?= $content; ?>
</body>

</html>