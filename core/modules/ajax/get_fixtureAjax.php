<?php
    session_start();
    require_once("../../models/class.Builder.php");
    $builder=new Builder();
    $builder->GetFixture($_SESSION['user_fmo']);
?>

