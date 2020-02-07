<?php
require_once("../../../core/models/class.Connection.php");
$db 		= new Connection();
$player = $_POST['player'];
$liga_nueva = $_POST['ligas'];
$liga_vieja = $_POST['ligasvieja'];

$busco = mysqli_query($db, "SELECT * FROM `equipos` WHERE idEquipos='".$player."'");
$re_busco = mysqli_fetch_array($busco);

$nombre 	= $re_busco['nombre'];
$usuario 	= $re_busco['usuario'];
$clave 		= $re_busco['clave'];
$email 		= $re_busco['email'];
$fantasma 	= $re_busco['fantasma'];
$referral 	= $re_busco['referral'];
$idEquipos 	= $re_busco['idEquipos'];
$nn 		= $idEquipos-1;
$nnombre 	= "Team ".$nn;

$liga = mysqli_query($db, "SELECT * FROM `equipos` WHERE Ligas_idLigas='".$liga_nueva."' AND fantasma=1 LIMIT 1");
$re_liga = mysqli_fetch_array($liga);

$idEquipos2 = $re_liga['idEquipos'];

$estadio = mysqli_query($db, "SELECT * FROM `estadios` WHERE idEstadios='".$idEquipos."'");
$re_estadio = mysqli_fetch_array($estadio);

$nombre_estadio = $re_estadio['nombre_es'];
$nnombreest 	= $nnombre." Stadium";

$editamos = mysqli_query($db, "UPDATE `equipos` SET `nombre`='".$nombre."',`usuario`='".$usuario."',`clave`='".$clave."',`email`='".$email."',`fantasma`='".$fantasma."',`referral`='".$referral."' WHERE `idEquipos`='".$idEquipos2."'");

$editamos_estadio = mysqli_query($db, "UPDATE `estadios` SET `nombre_es`='".$nombre_estadio."' WHERE `idEstadios`='".$idEquipos2."'");

$eliminamos = mysqli_query($db, "UPDATE `equipos` SET `nombre`='".$nnombre."',`usuario`='NULL',`clave`='NULL',`email`='NULL',`fantasma`='1',`referral`='0',`asignado`='1' WHERE `idEquipos`='".$idEquipos."'");

$eliminamos_estadio = mysqli_query($db, "UPDATE `estadios` SET `nombre_es`='".$nnombreest."' WHERE `idEstadios`='".$idEquipos."'");

$restar_liga = mysqli_query($db, "UPDATE Ligas SET equipos_reales=equipos_reales-1 WHERE idLigas='".$liga_vieja."'");
$sumar_liga = mysqli_query($db, "UPDATE Ligas SET equipos_reales=equipos_reales+1 WHERE idLigas='".$liga_nueva."'");

mysqli_close($db);

?>