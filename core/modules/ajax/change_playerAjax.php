<?php
session_start();
if(isset($_POST['titular']) && isset($_POST['changefor']))
{
    $id_titular=intval($_POST['titular']);
    $id_change=intval($_POST['changefor']);
	
    if($id_titular>0 && $id_change>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->ChangePlayer($_SESSION['user_fmo'], $id_titular, $id_change);
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, select 2 players to make a change.
                      </div>';
    }
}    
?>

