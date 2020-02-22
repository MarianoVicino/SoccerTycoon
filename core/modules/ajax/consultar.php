<?php
include("../../models/class.Connection.php");
$db=new Connection();

$sql = mysqli_query($db, "SELECT precio FROM `Gold`");
$re = mysqli_fetch_array($sql);
//Invertir en GOLD
$importe = $_POST['valor'];

//Valor inicial del SL
$inicial = $re['precio'];

$resultado = 0;
 
$resul = ($importe / $inicial);
$por 	= $resul * 0.01;
$resultado = $resul + $por;
if($resultado < 0){
	$inicial = $inicial;
}else{
	if($_POST['accion'] == "sumar"){
		$inicial = $inicial + $resultado;
	}else{
		$inicial = $inicial - $resultado;
	}
}
if($inicial < 0){
	$inicial = 1; 
}
	

echo number_format($inicial, 2, ',', '.');
?>