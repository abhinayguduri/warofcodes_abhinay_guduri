<?php
include "core/init.php";
if ($getFromU->loggedIn()==true){
	header("Location:".BASE_URL."home.php");
}
if(isset($_POST['login']) && !empty($_POST['login'])){
    $email = $getFromU->checkInput($_POST['email']);
    $email = strtolower($email);
    $password = $_POST['password'];
    if(!empty($email) && !empty($password)){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if($getFromU->login($email,md5($password))===false){
                $_SESSION['error'] = "Invalid Credentials";
                $_SESSION['time']=time();
                header("Refresh: 0");
            }else{
                header("Location: ".BASE_URL."home.php");
                header("Refresh: 0");
            }
        }else{
            $_SESSION['error'] = "Invalid Email";
            $_SESSION['time']=time();
            header("Refresh: 0");
        }
    }else{
        $_SESSION['error'] = "All Feilds are Required";
        $_SESSION['time']=time();
        header("Refresh: 0");
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <title>War of Codes</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="POST" class="login-form">
        <?php
            $currentTime = time();
                if(isset($_SESSION['error']) && !empty($_SESSION['error']) && ($currentTime - $_SESSION['time'] < 4) ){
                    echo '<div class="err_msg">
                    <p>'.$_SESSION['error'].'</p>
                    </div>';
                    
                }

            ?>
            <h1 id="title">War of Codes</h1>
            <label for="email">Email</label><br>
            <input type="text" name="email" id="input" placeholder="johndoe@gmail.com"><br>
            <label for="username">Password</label><br>
            <input type="password" name="password" id="input" placeholder="It's a secret"><br>
            <input type="submit" name="login" id="g_button" value="Login"><br>
            <a href="register.php">Didn't have an account ? Register</a>
        </form>
    </div>
</body>
</html>