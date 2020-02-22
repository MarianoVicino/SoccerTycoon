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

$resultado = 0;

$resul = ($precio / $inicial);
$por 	= $resul * 0.01;
$resultado = $resul + $por;
if($resultado < 0){
	$inicial = $inicial;
}else{
	if($accion == "sumar"){
		$inicial = $inicial + $resultado;
	}else{
		$inicial = $inicial - $resultado;
	}	
}

mysqli_query($db, "UPDATE `Gold` SET `precio`='$inicial'");

?>
