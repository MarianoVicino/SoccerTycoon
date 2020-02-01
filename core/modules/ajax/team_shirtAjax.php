<?php
    session_start();
    if(isset($_POST['shirt']))
    {
        $id=intval($_POST['shirt']);
        if($id>0)
        {
            require_once("../../models/class.Team.php");
            $team=new Team();
            $team->ChangeTeamShirt($_SESSION['user_fmo'], $id);
        }    
    }    
?>

