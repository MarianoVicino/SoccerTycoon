<?php
session_start();
if(isset($_POST['n']))
{
    $n=intval($_POST['n']);
    if($n>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->BuyStadiumUpgrade($n, $_SESSION['user_fmo']);
    }
}    
?>
