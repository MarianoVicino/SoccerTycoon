<?php

include("../models/class.Connection.php");
include("../models/class.User.php");
$db2=new Connection();

//Vemos si el usuario ya esta logeado
$log = mysqli_query($db2, "SELECT * FROM `Equipos` WHERE email='".$_POST['Email']."'");
if($log->num_rows == 0){
	$ref = 0;
	$user=new User();
	$ssq = mysqli_query($db2, "SELECT MIN(idRegiones) min, MAX(idRegiones) max FROM `Regiones`");
	$rree = mysqli_fetch_array($ssq);
	$id_region = rand($rree['min'], $rree['max']);
	if($_POST['referral'] == ""){
		$ref = 0;
	}else{
		$ref = GetUserIdByLogin($_POST['referral']);
	}
	$user->AddUser($id_region, $_POST['Full_Name'], $_POST['ID'], $_POST['Email'], $ref,3);
}else{
	session_start();
}
$_SESSION['user_fmo'] = mb_strtolower($_POST['Full_Name'],'UTF-8');
?>