<?php
if(isset($_POST['user']))
{
    require_once("../../models/class.Miscellaneous.php");
    $misc=new Miscellaneous();
    $misc->DeleteUser($_POST['user']);
}    
?>

