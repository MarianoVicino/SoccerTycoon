<?php

require_once("../../../core/models/class.Connection.php");
$db 		= new Connection();
$sql 		= mysqli_query($db, "SELECT Ligas_idLigas FROM `equipos` WHERE idEquipos='".$_POST['id']."'");
$re 		= mysqli_fetch_array($sql);

$liga 		= mysqli_query($db, "SELECT Divisiones_idDivisiones FROM `ligas` WHERE idLigas='".$re['Ligas_idLigas']."'");
$re_liga 	= mysqli_fetch_array($liga);

$division 	= mysqli_query($db, "SELECT Regiones_idRegiones FROM `divisiones` WHERE idDivisiones='".$re_liga['Divisiones_idDivisiones']."'");
$re_div 	= mysqli_fetch_array($division);

$regiones 	= mysqli_query($db, "SELECT nombre_reg FROM `regiones` WHERE idRegiones='".$re_div['Regiones_idRegiones']."'");
$re_reg 	= mysqli_fetch_array($regiones);

$array = array(
'ligas' => $re['Ligas_idLigas'],
'divisiones' => $re_liga['Divisiones_idDivisiones'], 
'regiones' => $re_div['Regiones_idRegiones'], 
'nombre' => $re_reg['nombre_reg']
);

echo json_encode($array, JSON_FORCE_OBJECT);
mysqli_close($db);
?>