<?php
session_start();
if(isset($_POST['formation']))
{
    $id_formation=intval($_POST['formation']);
    if($id_formation>=1 && $id_formation<=10)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->ChangeFormation($id_formation, $_SESSION['user_fmo']);
    }    
}    
?>

