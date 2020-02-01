<?php
    if(isset($_POST['region']))
    {
        $id=intval($_POST['region']);
        if($id>0)
        {
            require_once("../../models/class.Division.php");
            $division=new Division();
            $division->SelectDivision($id);
        }    
    }    
?>

