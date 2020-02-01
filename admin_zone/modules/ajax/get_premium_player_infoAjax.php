<?php
    if(isset($_POST['id']))
    {
        $id=intval($_POST['id']);
        if($id>0)
        {
            require_once("../../models/class.Premium.php");
            $premium=new Premium();
            $premium->GetPremiumPlayerInfo($id);
        }    
    }    
?>

