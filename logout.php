<?php
include 'core/init.php';
$getFromU->logout();
if ($getFromU->loggedIn()===false) {
	header("location: ".BASE_URL."index.php");
}

?>