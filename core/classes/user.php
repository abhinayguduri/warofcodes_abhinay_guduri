<?php

class User 
{
	
		protected $pdo;

	function __construct($pdo){
		$this->pdo = $pdo;
	}

	//check input 

		public function checkInput($var){
		$var = htmlspecialchars($var);
		$var = trim($var);
		$var = stripcslashes($var);
		return $var;
	}

//Login   

      public function login($email,$password){
       $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `email`= :email AND `password` = :password");
       $stmt->execute(array('email' =>$email ,'password'=>$password));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            $_SESSION['id']=$user->id;
            $_SESSION['key']= md5(md5(md5($user->email)));
            return true;
            
       }else{
         return false;
       }

   }

         public function userDataP($email){
       $stmt = $this->pdo->prepare("SELECT `user_id` FROM `users` WHERE `email`= :username");
       $stmt->execute(array('username' =>$email));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            
            return $user;
            
       }else{
         return false;
       }

   }

   //verify

         public function verify($mail,$code,$id){
       $stmt = $this->pdo->prepare("SELECT * FROM `tmp` WHERE tmp_id = :id AND hmail= :mail AND code = :code");
       $stmt->execute(array('id' =>$id ,'code'=>$code,'mail'=>$mail));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return $user;
            
       }else{
         return false;
       }



   }


            public function verifyp($hmail,$code,$id){
       $stmt = $this->pdo->prepare("SELECT * FROM `p_tmp` WHERE tmp_id = :id AND code = :code AND hmail = :hmail ");
       $stmt->execute(array('id' =>$id ,'code'=>$code, 'hmail'=>$hmail));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return $user;
            
       }else{
         return false;
       }

      

   }

   //check Mail

         public function checkEmail($email){
       $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  email= :email");
       $stmt->execute(array('email' =>$email));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return true;
            
       }else{
         return false;
       }

   }
   //check username


         public function checkUsername($username){
       $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  username= :username");
       $stmt->execute(array('username' =>$username));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return true;
            
       }else{
         return false;
       }

   }



 //Logout

      public function logout(){
      $_SESSION =  array();
      session_destroy();
      header("Location:".BASE_URL."index.php");
   }

//checks user logged in or not
     public function loggedIn(){
      if(isset($_SESSION['id']) && isset($_SESSION['key'])){
        $user =$this->UserData($_SESSION['id']);
        if(md5(md5(md5($user->email)))===$_SESSION['key']){
          return true;
        }else{
          unset($_SESSION['id']);
          unset($_SESSION['key']);
          return false;
        }
      }
   }

  //user data 

         public function userData($user_id){
      $stmt= $this->pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
      $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_OBJ);
   }

   //Product DATA.....

            public function productData($id){
      $stmt= $this->pdo->prepare("SELECT * FROM `products` WHERE `product_id` = :id");
      $stmt->bindParam(":id",$id,PDO::PARAM_INT);
      $stmt->execute();
     $count=$stmt->rowCount();
      if ($count>0) {
      	return $stmt->fetch(PDO::FETCH_OBJ);
      }else{
      	return false;
      }
   }
   //user data By name

          public function userDataByName($username){
      $stmt= $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = :user_id");
      $stmt->bindParam(":user_id",$username,PDO::PARAM_STR);
      $stmt->execute();
      $count=$stmt->rowCount();
      if ($count>0) {
      	return $stmt->fetch(PDO::FETCH_OBJ);
      }else{
      	return false;
      }
      
   }

   //Product Data

             public function getproductData($pid){
      $stmt= $this->pdo->prepare("SELECT * FROM `products` WHERE `product_id` = :pid");
      $stmt->bindParam(":pid",$pid,PDO::PARAM_INT);
      $stmt->execute();
      $count=$stmt->rowCount();
      if ($count>0) {
      	return $stmt->fetch(PDO::FETCH_OBJ);
      }else{
      	return false;
      }
      
   }
   //code

   public function code(){
   	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
   	return substr(str_shuffle($str_result),  
                       0, 10);
   }

   ///Update.............

      public function update($table , $user_id, $fields = array()){
      $columns = '';
      $i = 1;
      foreach($fields as $name => $value){
      $columns .= "`{$name}` = :{$name}";
      if ($i<count($fields)) {
           $columns .= ', ';
      }
      $i++; 
      }
      $sql = "UPDATE {$table} SET {$columns} WHERE `id` = {$user_id}";
      if ($stmt = $this->pdo->prepare($sql)) {
         foreach($fields as $key=> $value){
            $stmt->bindValue(':'.$key, $value);
         }
         $stmt->execute();
      }

   }

   //Update Product

      public function updateP($table , $user_id, $fields = array()){
      $columns = '';
      $i = 1;
      foreach($fields as $name => $value){
      $columns .= "`{$name}` = :{$name}";
      if ($i<count($fields)) {
           $columns .= ', ';
      }
      $i++; 
      }
      $sql = "UPDATE {$table} SET {$columns} WHERE `product_id` = {$user_id}";
      if ($stmt = $this->pdo->prepare($sql)) {
         foreach($fields as $key=> $value){
            $stmt->bindValue(':'.$key, $value);
         }
         $stmt->execute();
      }

   }

//mail sending
public function mail($email,$code,$tmp_id){
echo '<iframe style="display:none;" src="https://ramsee.000webhostapp.com/mail.php?email='.$email.'&data=http://rentnals.in/verify/'.md5($email).'/'.$code.'/'.$tmp_id.'"></iframe>';

}

            public function checkPhone($phone){
       $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE  phone= :phone");
       $stmt->execute(array('phone' =>$phone));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return true;
            
       }else{
         return false;
       }

   }
public function pmail($email,$code,$tmp_id){
echo '<iframe style="display:none;" src="https://ramsee.000webhostapp.com/pmail.php?email='.$email.'&data=http://rentnals.in/resetpassword/'.md5($email).'/'.$code.'/'.$tmp_id.'"></iframe>';

   }




   ///Image Upload

      public function uploadImage($file,$a){
      $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
      $filename = basename($file['name']);
      $fileTmp = $file['tmp_name'];
      $fileSize = $file['size'];
      $error = $file['error'];

      $ext = explode('.', $filename);
      $ext = strtolower(end($ext));
      $allowed_ext = array('jpg', 'png', 'jpeg','gif');

      if (in_array($ext, $allowed_ext)===true) {
         if ($error===0) {
            if ($fileSize <= 709272152 ) {
               $fileRoot = $a.'/'.$s.'.'.$ext;
               move_uploaded_file($fileTmp, $fileRoot);
               return $fileRoot;

            }else{
               echo '<script>alert("Image size must be 0-5 MB");</script>';
            }
         }
      }else{
         echo '<script>alert("That type of file is not allowed");</script>';
      }
   }

   //Check Password

            public function checkPassword($user_id,$cpwd){
       $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE user_id = :user_id AND password = :password");
       $stmt->execute(array('user_id' =>$user_id,'password'=>$cpwd));
       $user=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            return true;
            
       }else{
         return false;
       }

   }

   ///Displaying All Cats
      public function getcats(){
      $stmt = $this->pdo->prepare("SELECT * FROM `cats`");
      $stmt->execute();
      $cats = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($cats as $cat){
         echo '<a href="'.BASE_URL.'category/'.$cat->cat_name.'">
	<div class="full_container">
	<div class="container">
		<div id="category"><img src="'.BASE_URL.$cat->img.'"></div>
		<div id="cat_details">
			<h1>'.$cat->cat_name.'</h1>
		</div>
	</div>	
</div></a>
         ';

      }
   }


//Getting Products.....

         public function getproducts($cat){
      $stmt = $this->pdo->prepare("SELECT * FROM `products` WHERE `cat` = :category");
      $stmt->execute(array(':category'=> $cat));
      $products = $stmt->fetchAll(PDO::FETCH_OBJ);
      $count=$stmt->rowCount();
      if ($count>0) {
      foreach ($products as $product){
      	$auser = $this->userData($product->add_by);
         echo '<a href="'.BASE_URL.'product/'.$product->product_id.'"><div  class="pcont container">
		<div id="category"><img src="'.BASE_URL.$product->img1.'"></div>
		<div id="cat_details">
			<h3>'.$product->name.'</h3>
		</div>
		<div id="p_details">
			<img src="'.BASE_URL.$auser->profileImage.'">
			<h3>By: '.$auser->screenName.'</h3>
		</div>
		<div id="for_badge">
			<img src="'.BASE_URL.'assets/images/'.$product->p_for.'.png" height="50" width="50">
		</div>
	</div></a>';

      }
      }else{
      	echo '<img style="position:relative; top:100px; width: 40%; " src="'.BASE_URL.'assets/images/nops.png">';
      }

   }

   //getting Options
         public function getopt(){
      $stmt = $this->pdo->prepare("SELECT * FROM `cats`");
      $stmt->execute();
      $cats = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($cats as $cat){
         echo '<option value="'.$cat->cat_name.'">'.$cat->cat_name.'</option>';

      }
   }

   //create a new record.....

      public function create($table, $fields = array()){
         $columns = implode(',', array_keys($fields));
         $values = ':'.implode(', :', array_keys($fields));
         $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
         if ($stmt = $stmt = $this->pdo->prepare($sql)) {
         foreach ($fields as $key => $data){
            $stmt->bindValue(':'.$key, $data);
         }
         $stmt->execute();
         return $this->pdo->lastInsertId();
                  }
   }

   //get selected cats..............

         public function getselproducts($user_id){
      $stmt = $this->pdo->prepare("SELECT * FROM `products` WHERE `add_by` = :user_id");
      $stmt->execute(array(':user_id'=>$user_id));
      $products = $stmt->fetchAll(PDO::FETCH_OBJ);
      $count=$stmt->rowCount();
      if ($count>0) {
      foreach ($products as $product){
         echo '<a href="'.BASE_URL.'product/'.$product->product_id.'"><div class="pcont container">
		<div id="category"><img src="'.BASE_URL.$product->img1.'"></div>
		<div id="cat_details">
			<h3>'.$product->name.'</h3>
		</div>
		<div id="e_d">
		  <a href="'.BASE_URL.'editproduct/'.$product->product_id.'"><button  id="edit">Edit <i class="fa fa-pencil"></i></button></a>
		  <form method="post" action="">
		  <input type="hidden" name="pid" value="'.$product->product_id.'">
		  <button onclick="this.form.submit();" name="delp" id="del">Delete <i class="fa fa-trash"></i></button>
		  </form>
		</div>
	</div></a>';

      }
      }else{
      	echo '<img style="position:relative; top:100px; width: 40%; " src="'.BASE_URL.'assets/images/nops.png">';
      }
     }


  //options 2...
      public function getopt2($cat_name){
      $stmt = $this->pdo->prepare("SELECT * FROM `cats`");
      $stmt->execute();
      $cats = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($cats as $cat){
         echo '<option value="'.$cat->cat_name.'"';
         ?>
         <?php
         if ($cat_name==$cat->cat_name) {
            echo 'selected';
         }
          echo '>'.$cat->cat_name.'</option>';

      }
   }

//DELETE....

public function delete($pid){
$stmt=$this->pdo->prepare("DELETE FROM `products` WHERE `product_id`=:pid");
$stmt->bindParam(":pid",$pid,PDO::PARAM_INT);
$stmt->execute();
  }   

  public function deletetmp($id,$table){
$stmt=$this->pdo->prepare("DELETE FROM {$table} WHERE `tmp_id`=:id");
$stmt->bindParam(":id",$id,PDO::PARAM_INT);
$stmt->execute();
  } 

    public function deletetmpt(){
    	
$stmt=$this->pdo->prepare("DELETE FROM tmp WHERE addOn < NOW() - INTERVAL 15 MINUTE");
$stmt->execute();
  } 

      public function deletetmpp(){
    	
$stmt=$this->pdo->prepare("DELETE FROM p_tmp WHERE addOn < NOW() - INTERVAL 15 MINUTE");
$stmt->execute();
  } 
  
  
  #-----------------------------------------------------------------------------
  
  
           public function updateC($table , $user_id, $fields = array()){
      $columns = '';
      $i = 1;
      foreach($fields as $name => $value){
      $columns .= "`{$name}` = :{$name}";
      if ($i<count($fields)) {
           $columns .= ', ';
      }
      $i++; 
      }
      $sql = "UPDATE {$table} SET {$columns} WHERE `cat_id` = {$user_id}";
      if ($stmt = $this->pdo->prepare($sql)) {
         foreach($fields as $key=> $value){
            $stmt->bindValue(':'.$key, $value);
         }
         $stmt->execute();
      }

   }

         public function count_t($table){
       $stmt = $this->pdo->prepare("SELECT * FROM {$table}");
       $stmt->execute();
       $count=$stmt->rowCount();
       return $count;

   }


         public function cdel($table,$col,$id){
      
$stmt=$this->pdo->prepare("DELETE FROM {$table} WHERE {$col} = :id");
$stmt->execute(array('id'=>$id));
  } 




        public function getUsers(){

                $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `role` =0");
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($users as $u){
         echo '<tr>
         <td>'.$u->firstname.'</td>
         <td>'.$u->lastname.'</td>
         <td>'.$u->email.'</td>
         <td>'.$u->phone.'</td>
         <td>
           <button type="button" class="btn btn-primary">Edit</button>
           <form method="POST" action="">
      <input type="hidden" name="id" value="'.$u->id.'">
      <button type="button" onclick="this.form.submit()" name="delete" class="btn btn-danger">Delete</button>
      </form>
         </td>
       </tr>';

      }
 }


        public function getps(){
                $stmt = $this->pdo->prepare("SELECT * FROM `products` order by `name` ASC");
      $stmt->execute();
      $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($products as $p){
            $pu = $this->UserData($p->add_by);
         echo ' <div class="block">
    <div id="u_img">
      <img src="'.BASE_URL.$p->img1.'" height="60" width="60">
    </div>
    <div id="u_d">
      <h1>'.$p->name.'</h1>
      <p>Cat: '.$p->cat.'</p>
      <a href="'.BASE_URL.'profile/'.$pu->username.'"><p>By: @'.$pu->username.'</p></a>
    </div>
    <div id="u_btns">
      <a href="'.BASE_URL.'product/'.$p->product_id.'"><button id="v_btn"><i class="fa fa-eye"></i> View</button></a>
      <form method="POST" action="">
      <input type="hidden" name="id" value="'.$p->product_id.'">
      <button onclick="this.form.submit()" name="pdel" id="d_btn"><i class="fa fa-trash"></i> Delete</button>
      </form>
    </div>
  </div>
';

      }
  }

       public function getcs(){
                $stmt = $this->pdo->prepare("SELECT * FROM `cats` order by `cat_name` ASC");
      $stmt->execute();
      $cats = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($cats as $c){
         echo ' <div class="block">
    <div id="u_img">
      <img src="'.BASE_URL.$c->img.'" height="60" width="60">
    </div>
    <div id="u_d">
      <h1>'.$c->cat_name.'</h1>
      <p>&nbsp; </p>
    </div>
    <div id="u_btns">
      <a href="'.BASE_URL.'editcat/'.$c->cat_id.'"><button id="v_btn"><i class="fa fa-edit"></i> Edit</button></a>
      <form method="POST" action="">
      <input type="hidden" name="id" value="'.$c->cat_id.'">
      <button onclick="this.form.submit()" name="cdel" id="d_btn"><i class="fa fa-trash"></i> Delete</button>
      </form>
    </div>
  </div>
';

      }
  }


     public function getnUsers($sp){
            if ($sp=="all"){
                $stmt = $this->pdo->prepare("SELECT * FROM `tmp` order by `screenName` ASC");
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($users as $u){
         echo ' <div class="block">
    <div id="u_img">
      <img src="'.BASE_URL.'assets/images/def.jpg" height="60" width="60">
    </div>
    <div id="u_d">
      <h1>'.$u->screenName.'</h1>
      <p>@'.$u->username.'</p>
    </div>
    <div id="u_btns">
    <form method="POST" action="">
      <input type="hidden" name="id" value="'.$u->tmp_id.'">
      <button onclick="this.form.submit()" name="vali" id="v_btn"><i class="fa fa-check"></i> Validate</button>
      </form>
      <form method="POST" action="">
      <input type="hidden" name="id" value="'.$u->tmp_id.'">
      <button onclick="this.form.submit()" name="udel" id="d_btn"><i class="fa fa-trash"></i> Delete</button>
      </form>
    </div>
  </div>
';

      }
   }else{
$stmt = $this->pdo->prepare("SELECT * FROM `tmp` WHERE `username` LIKE :sp OR `screenName` LIKE :sp OR `email` LIKE :sp");
      $stmt->bindValue('sp', $sp.'%');
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($users as $u){
         echo ' <div class="block">
    <div id="u_img">
      <img src="'.BASE_URL.'assets/images/def.jpg" height="60" width="60">
    </div>
    <div id="u_d">
      <h1>'.$u->screenName.'</h1>
      <p>@'.$u->username.'</p>
    </div>
    <div id="u_btns">
      <form method="POST" action="">
      <input type="hidden" name="id" value="'.$u->tmp_id.'">
      <button onclick="this.form.submit()" name="vali" id="v_btn"><i class="fa fa-check"></i> Validate</button>
      </form>
      <form method="POST" action="">
      <input type="hidden" name="id" value="'.$u->tmp_id.'">
      <button onclick="this.form.submit()" name="udel" id="d_btn"><i class="fa fa-trash"></i> Delete</button>
      </form>
    </div>
  </div>
';
        }



   }
 }
          public function validate($id){
       $stmt = $this->pdo->prepare("SELECT * FROM `tmp` WHERE tmp_id = :id");
       $stmt->execute(array('id' =>$id));
       $tuser=$stmt->fetch(PDO::FETCH_OBJ);
       $count=$stmt->rowCount();
       if ($count>0) {
            $this->create('users', array('email'=> $tuser->email,'screenName'=>$tuser->screenName,'password'=>$tuser->password,'username'=>$tuser->username,'phone'=>$tuser->phone));
            $this->deletetmp($id,'tmp');
            return true;
            
       }else{
         return false;
       }



   }


             public function getcd($pid){
      $stmt= $this->pdo->prepare("SELECT * FROM `cats` WHERE `cat_id` = :pid");
      $stmt->bindParam(":pid",$pid,PDO::PARAM_INT);
      $stmt->execute();
      $count=$stmt->rowCount();
      if ($count>0) {
        return $stmt->fetch(PDO::FETCH_OBJ);
      }else{
        return false;
      }
      
   }





}
?>



