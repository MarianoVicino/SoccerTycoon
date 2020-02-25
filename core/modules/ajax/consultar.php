<?php
include("../../models/class.Connection.php");
$db=new Connection();

$sql = mysqli_query($db, "SELECT precio FROM `Gold`");
$re = mysqli_fetch_array($sql);
//Invertir en GOLD
$importe = $_POST['valor'];

//Valor inicial del SL
$inicial = $re['precio'];
$final = $inicial;

for ($i=0; $i <$importe ; $i++) { 
	if($_POST['accion'] == "sumar"){
		$final = $final + 0.002;
	}else{
		$final = $final - 0.002;
	}
}

/*if($_POST['accion'] == "sumar"){
	$resul = $inicial * $importe;
	$alta = $resul * 0.01;
	$final = $resul + $alta;
	
}else{
	$resul = $inicial / $importe;
	$alta = $resul * 0.01;
	$final = $resul - $alta;
}

if($final < 0){
	$final = 2;
}*/

echo number_format($final, 3, ',', '.');
?>