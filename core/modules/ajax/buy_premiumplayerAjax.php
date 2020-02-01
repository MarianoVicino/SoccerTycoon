<?php
session_start();
if(isset($_POST['n']) && isset($_POST['shirt']))
{
    $n=intval($_POST['n']);
    if($n>0)
    {
        $shirt=intval($_POST['shirt']);
        if($shirt>0 && $shirt<=99)
        {
            require_once("../../models/class.Team.php");
            $team=new Team();
            $team->BuyPremiumPlayer($n,$shirt,$_SESSION['user_fmo']);
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Please choose a valid number for your player (1-99).
                    </div>';
        }         
    }    
}    
?>

