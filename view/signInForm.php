<?php
$title = "SIGN IN";
ob_start();

?>
<link rel="stylesheet" href="./public/css/formInput.css">
<script defer src="./public/js/validateSignIn.js"></script>

<!-- FOR SIGN IN WITH GOOGLE -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<!-- ======================== -->

<div class="login-form">
  <form action="index.php?action=logIn" method="POST" id="login">

    <div class="login-title">
      <p>Welcome back!</p>
      <p>Sign into your account</p>
    </div>


    <p>
      <label for="loginUsername">Username: </label>
      <input type="text" name="username" id="loginUsername">
    </p>
    <p>
      <label for="loginPassword">Password: </label>
      <input type="text" name="password" id="loginPassword">
    </p>

    <a class="sign-assist" href="index.php?action=">Need help signing in?</a>


    <p>
      <input type="submit" value="submit" id="loginSubmit" disabled>
    </p>

    <!-- GOOGLE BUTTON -->
    <div class="google-btn">
      <div id="g_id_onload" data-client_id="<?= getenv("GOOGLE_CLIENT_ID") ?>" data-context="signin" data-ux_mode="popup" data-login_uri="http://localhost/sites/batch20-final-project/index.php?action=googleLogIn&signUp=false" data-auto_prompt="false">
      </div>

      <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left">
      </div>
    </div>

    <span class="line"></span>

    <button class="sign-btn">
      <a href="index.php?action=add_user">Don't have an account? Sign up</a>
    </button>


  </form>
</div>


<?php

$content = ob_get_clean();
require "template.php";
?>

<!-- 

  Disabled if no username or password

  if both are not empty, enable
 -->