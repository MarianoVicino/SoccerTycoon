<?php
session_start();
if(isset($_POST['id']))
{
    $id_to_sell=intval($_POST['id']);
	
    if($id_to_sell>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->ConfirmSellPlayer($_SESSION['user_fmo'], $id_to_sell);
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Could not sell this player. Try again.
                      </div>';
    }
}
?>

