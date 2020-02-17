<?php
include('../../google/config.php');
session_start();

//Reset OAuth access token
$google_client->revokeToken();
session_destroy();
header("location:  $HOME");

?>

