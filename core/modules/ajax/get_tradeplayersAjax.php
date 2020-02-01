<?php
if(isset($_POST['n']))
{
    $n=intval($_POST['n']);
    if($n>=0)
    {
        require_once("../../models/class.Builder.php");
        $builder=new Builder();
        $builder->GetTradePlayers($n,$_POST['pos']);
    }    
}    
?>
