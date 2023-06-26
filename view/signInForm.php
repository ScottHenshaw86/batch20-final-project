<?php
$title = "SIGN IN";
ob_start();

?>
<link rel="stylesheet" href="./public/css/formInput.css">
<script defer src="./public/js/validateSignIn.js"></script>

<div class="login-form">
  <form action="index.php?action=logIn" method="POST" id="login">

    <p class="login-title"> Log in to DevShop</p>

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