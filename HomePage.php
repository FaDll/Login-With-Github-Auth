<?php
    require "GitHubAuth.php";
?>

<form action="" method="POST">
   <input type="text" name="username">
   <input type="password" name="password"><br><br>
   <input type="submit" value="Login" name="Login">

</form>

<?php

if(isset($_REQUEST['Login']))
{
    Login($_POST['username'],sha1($_POST['password']));
    $_SESSION['user']=$_POST['username'];
}
?>

<a href="Login.php">Sign in with GitHub</a>