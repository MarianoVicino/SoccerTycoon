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

if($_POST['accion'] == "sumar"){
	$regla = ($precio*100/$inicial)/100;
	$aumento = 0.002*$precio;
	$final = $inicial+$aumento;
}else{
	$regla = $precio*$inicial;
	$aumento = 0.001*$inicial;
	$final = $inicial-$aumento;
}

mysqli_query($db, "UPDATE `Gold` SET `precio`='".number_format($final, 3)."'");
$uuser = mysqli_query($db, "SELECT gold,oro FROM `Equipos` WHERE usuario='".$_POST['usuario']."'");
$re_uuser = mysqli_fetch_array($uuser);

$gold = $re_uuser['gold'];
$oro = $re_uuser['oro'];

if($_POST['accion'] == "sumar"){
	$restar = $oro - $precio;
	$sumar = $gold + $regla;
	mysqli_query($db, "UPDATE `Equipos` SET `oro`='$restar',`gold`='".number_format($sumar, 3)."' WHERE usuario='".$_POST['usuario']."'");
	$texto = "<b>".$_POST['usuario']."</b> bought ".number_format($regla, 3)." gold and the rate increase to ".number_format($final, 3);
}else{
	$restar = $gold - $precio;
	$sumar = $oro + $regla;
	mysqli_query($db, "UPDATE `Equipos` SET `oro`='$sumar',`gold`='".number_format($restar, 3)."' WHERE usuario='".$_POST['usuario']."'");
	$texto = "<b>".$_POST['usuario']."</b> sold ".$precio." gold and the rate decreased to ".number_format($final, 3);
}


$hora = date("Y-m-d H:i:s");
mysqli_query($db, "INSERT INTO `Gold_acciones`(`texto`, `fecha`) VALUES ('$texto','$hora')");
?>
