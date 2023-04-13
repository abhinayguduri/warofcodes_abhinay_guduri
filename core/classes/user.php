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

  //user data 

         public function userData($user_id){
      $stmt= $this->pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
      $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_OBJ);
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




  
  
  #-----------------------------------------------------------------------------
  
  







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

 public function deleteUser($id){
  $stmt=$this->pdo->prepare("DELETE FROM `users` WHERE `id`=:id");
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  $stmt->execute();
    }  
}
?>



