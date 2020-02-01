<?php
session_start();
if(isset($_POST['titular']))
{
    $id_titular=intval($_POST['titular']);
    if($id_titular>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->UpdateOtherPlayersSelect($_SESSION['user_fmo'], $id_titular);
    }    
}    
?>

