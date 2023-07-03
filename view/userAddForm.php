<?php
$title = "SIGN UP";
ob_start();

if (isset($_GET['error'])) {
    include './view/component/statusPopUp.php';
};

?>
<link rel="stylesheet" href="./public/css/formInput.css">

<!-- FOR SIGN IN WITH GOOGLE -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<!-- ======================== -->

<div class="signup-form">

    <form action="index.php?action=createUser" method="POST" id="signUpForm">
        <h1>SIGN UP</h1>
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Username..." id="username">
        </p>
        <p>
            <span id="usernameNotValid">Not valid Username</span>
            <span id="usernameMissing">Username is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="email">E-mail:</label>
            <input type="email" name="email" placeholder="E-mail..." id="email">
        </p>
        <p>
            <span id="emailNotValid">Not valid E-mail</span>
            <span id="emailMissing">E-mail is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="text" name="password" placeholder="Password..." id="password">
        </p>
        <p>
            <span id="passwordNotValid">Not valid Password</span>
            <span id="passwordMissing">Password is missing</span>
            <span>&#8203;</span>
        </p>
        <p>
            <label for="password_confirm">Confirm password:</label>
            <input type="text" name="password_confirm" placeholder="Confirm password..." id="passwordConfirm">
        </p>
        <p>
            <span id="passwordConfirmError">Password does not match</span>
            <span>&#8203;</span>
        </p>

        <p>
            <input type="submit" value="Submit form" id="signupSubmit">
        </p>

        <!-- GOOGLE BUTTON -->
        <div class="google-btn">
            <div id="g_id_onload" data-client_id="<?= getenv("GOOGLE_CLIENT_ID") ?>" data-context="signin" data-ux_mode="popup" data-login_uri="http://localhost/sites/batch20-final-project/index.php?action=googleLogIn&signUp=true" data-auto_prompt="false">
            </div>

            <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left">
            </div>
        </div>



        <span class="line"></span>

        <button class="signup-btn">
            <a href="index.php?action=signInForm">Have an account? Sign in</a>
        </button>


    </form>
</div>

<?php
$content = ob_get_clean();
require "template.php";
?>