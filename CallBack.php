<?php

require "GitHubAuth.php";
$data=GetUserData();


if (!isset($_SESSION['user'])) {
    //header("location: HomePage.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signed In</title>
</head>
<body style="margin-top: 200px; text-align: center;">

 <div>
        <?php 
    
        if(isset($_REQUEST['submit_btn']))
        {
            InsertUser($_SESSION['user'],$_POST['pwd'],$_SESSION['Email']);
            header("location:HomePage.php");
        }   
    
        ?>
<?php
if($_SESSION['AlreadyLinked']==false)
{
    echo "Welcome, ".$_SESSION['user'];
    echo '<form action="" method="POST">
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" minlength="8"><br><br>
        <input type="submit" value="Submit" name="submit_btn">
    </form>';
}
?>
 </div>

</body>
</html>