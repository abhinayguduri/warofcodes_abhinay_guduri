<?php

include "core/init.php";
if ($getFromU->loggedIn()==true){
	header("Location:".BASE_URL."home.php");
}

if(isset($_POST['register']) && !empty($_POST['register'])){
    $firstname = $getFromU->checkInput($_POST['firstname']);
    $firstname = strtoupper($firstname);
    $lastname = $getFromU->checkInput($_POST['lastname']);
    $lastname = strtoupper($lastname);
    $email = $getFromU->checkInput($_POST['email']);
    $email = strtolower($email);
    $phone = $getFromU->checkInput($_POST['phone']);
    $password = $_POST['password'];
    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($phone) && !empty($password)){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (preg_match("/^[0-9]{10}$/", $phone)) {
                if (preg_match("/^(?=.*[A-Z])(?=.*[^\w\d\s:])(?!.*\s).{8,}$/", $password)) {
                    if($getFromU->checkEmail($email)){
                        $_SESSION['error']="Account Exists with Email";
                        $_SESSION['time']=time();
                        header("Refresh: 0");
                    }else{
                        $tmp_id=$getFromU->create('users', array('email'=> $email,'firstname'=>$firstname,'lastname'=>$lastname,'password'=>md5($password),'phone'=>$phone));
                        $_SESSION['error'] = "Account Created Successfully ";
                        $_SESSION['time']=time();
                        header("Refresh: 0");
                    }
                  } else {
                    $_SESSION['error'] = "Password must contain Uppercase and a symbol";
                    $_SESSION['time']=time();
                    header("Refresh: 0");
                  }
              } else {
                $_SESSION['error'] = "Invalid Phone Number";
                $_SESSION['time']=time();
                header("Refresh: 0");
              }
              
          } else {
            $_SESSION['error'] = "Invalid Email";
            $_SESSION['time']=time();
            header("Refresh: 0");
          }
    }else{
        $_SESSION['error'] = "All feilds are required";
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
            <label for="firstname">Firstname</label><br>
            <input type="text" name="firstname" id="input" placeholder="John"><br>
            <label for="lastname">Lastname</label><br>
            <input type="text" name="lastname" id="input" placeholder="Doe"><br>
            <label for="email">Email</label><br>
            <input type="text" name="email" id="input" placeholder="johndoe@gmail.com"><br>
            <label for="phone">Phone</label><br>
            <input type="text" name="phone" id="input" placeholder="6471257558"><br>
            <label for="username">Password</label><br>
            <input type="password" name="password" id="input" placeholder="It's a secret"><br>
            <input type="submit" id="g_button" value="Register" name="register"><br>
            <a href="index.php">Already have an account ? Login</a>
        </form>
    </div>
</body>
</html>