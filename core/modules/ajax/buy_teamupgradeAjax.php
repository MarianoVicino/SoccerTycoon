<?php
session_start();
if(isset($_POST['n']))
{
	if($_POST['n'] == 'fullstamina'){
		require_once("../../models/class.Team.php");
        $team=new Team();
        $team->BuyFullStamina($_SESSION['user_fmo']);
	}else{
		$n=intval($_POST['n']);
		if($n>0)
		{
			require_once("../../models/class.Team.php");
			$team=new Team();
			$team->BuyTeamUpgrade($n, $_SESSION['user_fmo']);
		}
	}
}    
?>
