<?php
session_start();
if(isset($_POST['player']) && isset($_POST['number']))
{
    $id=intval($_POST['player']);
    $number=intval($_POST['number']);
    if($id>0 && $number>=1 && $number<=99)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->ChangePlayerNumber($id, $number, $_SESSION['user_fmo']);
    }  
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Please, choose a shirt number between 1 and 99.
                 </div>';
    }    
}    
?>

