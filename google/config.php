<?php
require_once 'autoload.php';
$google_client = new Google_Client();
$google_client->setClientId('103341539377-e9ekc976l0ossu4o5mtvrekcj8s5456r.apps.googleusercontent.com');
$google_client->setClientSecret('u_CZ_y581j1mcXXCba-eeqV4');
$google_client->setRedirectUri('https://soccertycoon.com/index.php');
$google_client->addScope('email');
$google_client->addScope('profile');

function logingoogle($email,$referral,$full_name,$id){
	// Motrar todos los errores de PHP
	error_reporting(-1);
	 
	// No mostrar los errores de PHP
	error_reporting(0);
	 
	// Motrar todos los errores de PHP
	error_reporting(E_ALL);
	 
	// Motrar todos los errores de PHP
	ini_set('error_reporting', E_ALL);
	require_once("core/models/class.User.php");
	require_once("core/models/class.Connection.php");
	$db2=new Connection();

	//Vemos si el usuario ya esta logeado
	$log = mysqli_query($db2, "SELECT * FROM `Equipos` WHERE email='".$email."'");
	if($log->num_rows == 0){
		$ref = 0;
		$user = new User();
		$ssq = mysqli_query($db2, "SELECT MIN(idRegiones) min, MAX(idRegiones) max FROM `Regiones`");
		$rree = mysqli_fetch_array($ssq);
		$id_region = rand($rree['min'], $rree['max']);
		$ref = 0;//GetUserIdByLogin($referral);
		$user->AddUser($id_region, $full_name, $id, $email, $ref,false);
	}
	//return $referral;
	return mb_strtolower($full_name,'UTF-8');
}

?>