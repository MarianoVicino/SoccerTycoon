<?php
include("../core/models/class.Connection.php");
$db22=new Connection();

$sql = mysqli_query($db22, "SELECT precio FROM `Gold`");
$re = mysqli_fetch_array($sql);
mysqli_query($db22, "INSERT INTO `Gold_Historico`(`fecha`, `precio`) VALUES ('".date("Y-m-d")."','".$re["precio"]."')");

?>