<?php
 if(isset($_GET['term']))
    {
        require_once("../../models/class.Miscellaneous.php");
        $miscellaneous=new Miscellaneous();
        echo json_encode($miscellaneous->SearchUser($_GET['term']));
    } 
?>    

