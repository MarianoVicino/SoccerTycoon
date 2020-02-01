<?php
session_start();
require_once("../../models/class.Team2.php");
$team=new Team();
$team->GetTeamSubstitutes($_SESSION['user_fmo']);
?>

