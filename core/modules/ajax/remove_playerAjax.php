<?php
session_start();
if(isset($_POST['player']))
{
    $id_player=intval($_POST['player']);
    if($id_player>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->RemovePlayer($id_player, $_SESSION['user_fmo']);
    }    
}    
?>
