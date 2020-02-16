<?php
require_once("../../../core/models/class.Connection.php");
$db 		= new Connection();
$arre = array();
if($_POST['donde'] == "divisio"){
	$sql = mysqli_query($db, "SELECT idDivisiones,nombre_div FROM `Divisiones` WHERE Regiones_idRegiones='".$_POST['vvalor']."' ORDER BY nombre_div ASC");
}else if($_POST['donde'] == "ligas"){
	$sql = mysqli_query($db, "SELECT idLigas,nombre_li,equipos_reales FROM `Ligas` WHERE Divisiones_idDivisiones='".$_POST['vvalor']."' ORDER BY nombre_li ASC");
}
while ($re = mysqli_fetch_array($sql)) {
	$arre[] = $re;
}

echo json_encode($arre, JSON_FORCE_OBJECT);
mysqli_close($db);
?>