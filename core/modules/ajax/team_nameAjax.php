<?php
session_start();
if(isset($_POST['name']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    if($f->Validar_Long($_POST['name'], 4, 45)==1)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $name=htmlspecialchars($_POST['name'],ENT_QUOTES);
        $team->ChangeTeamName($_SESSION['user_fmo'], $name);
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Please, insert a valid team name (4-45).
               </div>';  
    }    
}    
?>

