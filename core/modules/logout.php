<?php
//session_start();
require_once('google/config.php');
$google_client->revokeToken();
session_destroy();
header("location:  $HOME");
?>

