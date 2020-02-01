<?php
if(isset($_POST['n']))
{
    $n=intval($_POST['n']);
    if($n>=0)
    {
		session_start();
		require_once("../../models/class.Builder.php");
        $builder=new Builder();
        $builder->GetHistoric($_SESSION['user_fmo'], $n);
    }    
}    
?>
