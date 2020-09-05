<?php
session_start();
require "Database.php";

 function AuthURl()
{
    $clientID="bcd61da02191b2846b66";
    $redirectURL="http://localhost/task5/CallBack.php";
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        $url='https://github.com/login/oauth/authorize?client_id='.$clientID."&redirect_url=".$redirectURL."&scope=user";
        header("location:$url");
    }
    
}

function GetUserData()
{
    $clientID="bcd61da02191b2846b66";
    $redirectURL="http://localhost/task5/CallBack.php";
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET['code']))
        {
            $tempCode=$_GET['code'];
            $post=http_build_query(array(
                'client_id'=>$clientID,
                'redirect_url'=>$redirectURL,
                'client_secret'=>'7a809e2e163ea932e310a05ab7fdf987f9559a8e',
                'code'=>$tempCode,
            ));

        }

        $data =file_get_contents("https://github.com/login/oauth/access_token?".$post);
        $exploded1 = explode('access_token=', $data);
        $exploded2 = explode('&scope=user', $exploded1[1]);
        $access_token = $exploded2[0];
        $opts = [ 'http' => [
                        'method' => 'GET',
                        'header' => [ 'User-Agent: PHP']
                    ]
        ];

        $url = "https://api.github.com/user?access_token=$access_token";
        $context = stream_context_create($opts);
        $data2 = file_get_contents($url, false, $context);
        $user_data = json_decode($data2, true);
        $username = $user_data['login'];


        $url1 = "https://api.github.com/user/emails?access_token=$access_token";
        $emails = file_get_contents($url1, false, $context);
        $emails = json_decode($emails, true);

        $email = $emails[0];
            
        
        $UserProfile = [
            'username' => $username,
            'email' => $email,
            'from' => "github"
        ];
        $_SESSION['payload'] = $UserProfile;
        $_SESSION['user'] = $username;
        $_SESSION['Email'] = $UserProfile['email']['email'];
        $UserEmail=$UserProfile['email']['email'];
        
        CheckIfLinked($username);

        return $UserProfile;

    }
}

    function InsertUser($username,$password,$email)
    {   

        $Dbobj=Database::getInstance();
        $fields=array("username","password","email");
        $values=array($username,sha1($password),$email);
        $AlreadySigned=$Dbobj->selectWhere("users","username = '".$username."'");
        $Dbobj->insert('users',$fields,$values);
        
    }

    function CheckIfLinked($username)
    {   

        $Dbobj=Database::getInstance();
        $AlreadySigned=$Dbobj->selectWhere("users","username = '".$username."'");
        $_SESSION['AlreadyLinked']=$AlreadySigned;
        if($AlreadySigned)
        {
            echo "<script>alert('your account is already linked with us please login');</script>";
            echo "<script>setTimeout(\"location.href = 'HomePage.php';\",10);</script>";
        }

    }

    function Login($username,$password)
    {
        $DbObj=Database::getinstance();
        $result = $DbObj->CheckLogin("users", "username = '".$username."' and password = '".$password."'");

        if($result != NULL)
        {
            header("Location:HomePage2.php");
        }

    }
?>