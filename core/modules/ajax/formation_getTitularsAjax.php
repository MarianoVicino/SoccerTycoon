<?php
session_start();
require_once("../../models/class.Team.php");
$team=new Team();
$team->UpdateTitularsSelect($_SESSION['user_fmo']);
?>

