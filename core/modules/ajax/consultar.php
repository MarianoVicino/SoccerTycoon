<?php
include("../../models/class.Connection.php");
$db=new Connection();

$sql = mysqli_query($db, "SELECT precio FROM `Gold`");
$re = mysqli_fetch_array($sql);

//Valor inicial del SL
$inicial = $re['precio']; 

//Invertir en GOLD
$importe = $_POST['valor'];

if($_POST['accion'] == "sumar"){
	$regla = ($importe*100/$inicial)/100;
}else{
	$regla = $importe*$inicial;
}

echo number_format($regla, 3, ',', '.');
?>