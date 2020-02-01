<?php
session_start();
if(isset($_POST['n']) && isset($_POST['player']))
{
	$n=intval($_POST['n']);
    $player=intval($_POST['player']);
    if($n>0 && $player>0)
    {
        require_once("../../models/class.Team.php");
        $team=new Team();
        $team->BuyPlayerUpgrade($n, $player, $_SESSION['user_fmo']);
    }else if($_POST['n'] == 'stamina' && $player>0){
		require_once("../../models/class.Team.php");
        $team=new Team();
        $team->BuyPlayerStamina($player, $_SESSION['user_fmo']);
	}
}
?>