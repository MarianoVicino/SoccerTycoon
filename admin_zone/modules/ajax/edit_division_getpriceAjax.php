<?php
    if(isset($_POST['id']))
    {
        $id=intval($_POST['id']);
        if($id>0)
        {
            require_once("../../models/class.Division.php");
            $division=new Division();
            $division->GetPrice($id);
        }    
    }    
?>
