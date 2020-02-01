<?php
session_start();
if(isset($_FILES['shield']))
{
    if($_FILES['shield']['size']<=200000)
    {
       $type=strtolower(pathinfo($_FILES['shield']['name'], PATHINFO_EXTENSION));
       if($type==="png")
       {
           require_once("../../models/class.Team.php");
           $team=new Team();
           $team->ChangeTeamShield($_SESSION['user_fmo'], $_FILES['shield'], $type);
       }
       else
       {
           echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    The image is not a png, please try with another one.
             </div>';
       }    
    } 
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    The image is bigger than 200kb, please try to reduce its size.
             </div>';
    }    
}    
?>

