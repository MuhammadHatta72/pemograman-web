<?php
session_start();
require 'functions.php';

//cek cookie
if(isset($_COOKIE['id'])&& isset($_COOKIE['usename']))
{
    $id=$_COOKIE['id'];
    $key=$_COOKIE['key'];

    // ambil username berdasarkan id
    $result=mysqli_query($conn,"SELECT username FROM user WHERE id=$id");
    $row=mysqli_fetch_assoc($result);

    //cek cookie dan username
    if($key === hash('sha256',$row['username']))
    {
        $_SESSION['login']=true;
    }    
}

if(isset($_SESSION["login"]))
{
    header("location:index.php"); 
    exit;
}


if(isset($_POST["login"]))
{
        $username=$_POST["username"];
        $password=$_POST["password"];
    
    
    $result=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($result)===1)
    {
        $row=mysqli_fetch_assoc($result); 
        
        if(password_verify($password,$row["password"]))
        {
            //set session
            $_SESSION["login"]=true;

            //cek remember me
            if(isset($_POST['remember']))
            {
                //enkripsi cookie menggunakan hash tipe sha256
                setcookie('id',$row['id'],time()+60);
                setcookie('key',hash(sha256,$row['username']),time()+60);
            }

            //redirect ke halaman index.php 
            header("Location:index.php");
            exit;
        } 
    }
    $error=true;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Login</title>
</head>
<body>
    <h1> Halaman Login</h1>
   
    <?php if(isset($error)):?>
        <p style="color:red;font-style=bold">
        Username dan password salah</p>
        
    <?php endif?>

    <form action="" method="post">
    <ul>
        <li>
            <label for="username">username</label>
            <input type="text" name="username" id="username">
        </li>
        <li>
             <label for="password">password</label>
             <input type="password" name="password" id="password">
        </li>
        <li>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
        </li>
        <li>
            <button type="submit" name="login">Login</button>
        </li>
    </ul>
    </form>
</body>
</html>