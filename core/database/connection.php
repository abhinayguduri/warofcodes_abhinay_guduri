<?php
  $host="localhost";
  $dbname = "warofcodes";
  $user = "root";
  $pass = "";

  try{
  	$pdo = new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);
  	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
  }catch(PDOException $e){
  	echo 'connection Failed! '.$e->getMessage();
  }
?>
