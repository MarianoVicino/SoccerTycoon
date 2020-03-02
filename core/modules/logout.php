<?php
include('../../google/config.php');
session_start();
$google_client->revokeToken();
session_destroy();
header("location:  $HOME");
?>

