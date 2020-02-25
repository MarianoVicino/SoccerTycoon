<?php
include("../../models/class.Connection.php");
$db=new Connection();

$accion = $_POST['accion'];
$precio = $_POST['precio'];

//Buscamos el precio de cambio
$sql = mysqli_query($db, "SELECT precio FROM `Gold`");
$re = mysqli_fetch_array($sql);

//Valor inicial del SL
$inicial = $re['precio'];
$final = $inicial;

for ($i=0; $i <$precio ; $i++) { 
	if($_POST['accion'] == "sumar"){
		$final = $final + 0.002;
	}else{
		$final = $final - 0.002;
	}
}

mysqli_query($db, "UPDATE `Gold` SET `precio`='$final'");

?>
