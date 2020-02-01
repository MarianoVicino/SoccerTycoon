<?php
session_start();
if(isset($_POST['player']) && isset($_POST['price']))
{
    $id_player=intval($_POST['player']);
    $price=intval($_POST['price']);
    if($id_player>0)
    {
        if($price>0 && $price<=1000000)
        {
            require_once("../../models/class.Team.php");
            $team=new Team();
            $team->SellPlayer($id_player, $price, $_SESSION['user_fmo']);
        } 
        else
        {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, choose a valid price (1-1.000.000).
                        </div>';
        }    
    }    
}    
?>

