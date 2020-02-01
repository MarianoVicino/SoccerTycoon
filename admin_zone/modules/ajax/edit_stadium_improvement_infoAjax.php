<?php
if(isset($_POST['id']))
{
    $id_imp=intval($_POST['id']);
    if($id_imp>0)
    {
        require_once("../../models/class.Premium.php");
        $premium=new Premium();
        $premium->GetStadiumImprovementsInfo($id_imp);
    }    
}    
?>

