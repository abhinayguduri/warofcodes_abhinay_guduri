<?php
include "core/init.php";
if ($getFromU->loggedIn()==false){
	header("Location:".BASE_URL."index.php");
}
$user_id = $_SESSION['id'];
$user = $getFromU->userData($user_id);
if(isset($_POST['update']) && !empty($_POST['update'])){
$cur_password = $_POST['currentpassword'];
$new_password = $_POST['newpassword'];
$re_password = $_POST['repassword'];
if($user->password === md5($cur_password)){
    if (preg_match("/^(?=.*[A-Z])(?=.*[^\w\d\s:])(?!.*\s).{8,}$/", $new_password)) {
        if($new_password===$re_password){
            $getFromU->update('users',$user_id,array('password'=>md5($new_password)));
            $_SESSION['error'] = "Password Updated";
            $_SESSION['time']=time();
        header("Refresh: 0");
        }else{
            $_SESSION['error'] = "Passwords Dont match";
            $_SESSION['time']=time();
        header("Refresh: 0");
        }
    }else{
        $_SESSION['error'] = "Password must contain Uppercase and a symbol and 8 Chars";
        $_SESSION['time']=time();
        header("Refresh: 0");
    }
}else{
    $_SESSION['error'] = "Incorrect Password";
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
            <h1 id="title">Hello! <?php echo $user->firstname.' '.$user->lastname?></h1>
            <label for="currentpassword">Current Password</label><br>
            <input type="password" name="currentpassword" id="input" ><br>
            <label for="newpassword">New Password</label><br>
            <input type="password" name="newpassword" id="input"><br>
            <label for="repassword">Re Password</label><br>
            <input type="password" name="repassword" id="input"><br>
            <input type="submit" name="update" id="g_button" value="Update"><br>
            <a href="home.php">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="logout.php">Logout</a>
        </form>
    </div>
</body>
</html>