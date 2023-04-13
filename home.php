<?php

include "core/init.php";
if ($getFromU->loggedIn()==false){
	header("Location:".BASE_URL."index.php");
}
$user_id = $_SESSION['id'];
$user = $getFromU->userData($user_id);
if(isset($_POST['update']) && !empty($_POST['update'])){
    $firstname = $getFromU->checkInput($_POST['firstname']);
    $firstname = strtoupper($firstname);
    $lastname = $getFromU->checkInput($_POST['lastname']);
    $lastname = strtoupper($lastname);
    $phone = $getFromU->checkInput($_POST['phone']);
    if(!empty($firstname) && !empty($lastname)  && !empty($phone)){
        if (preg_match("/^[0-9]{10}$/", $phone)) {
            $getFromU->update('users',$user_id,array('firstname'=>$firstname,'lastname'=>$lastname,'phone'=>$phone));
            $_SESSION['error'] = "Updated Successfully";
            $_SESSION['time']=time();
                    header("Refresh: 0");
        }else{
            $_SESSION['error'] = "Invalid Phone";
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
    <script>
        function redirect(){
            alert("hi");
            window.location.href = 'https://www.example.com';
        }
    </script>
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
            
            <label for="firstname">Firstname</label><br>
            <input type="text" name="firstname" id="input" placeholder="John" value="<?php echo $user->firstname;?>"><br>
            <label for="lastname">Lastname</label><br>
            <input type="text" name="lastname" id="input" placeholder="Doe" value="<?php echo $user->lastname;?>"><br>
            <label for="email">Email</label><br>
            <input type="text" name="email" id="input" placeholder="johndoe@gmail.com" value="<?php echo $user->email;?>" readonly><br>
            <label for="phone">Phone</label><br>
            <input type="text" name="phone" id="input" placeholder="6471257558" value="<?php echo $user->phone;?>" ><br>
            <input type="submit" id="g_button" value="Update" name="update"><br>
            <a href="changepassword.php"> Change Password</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="logout.php">Logout</a>
        </form><br>
        
        
    </div>
</body>
</html>