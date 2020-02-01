<?php
if(isset($_POST['group']))
{
    $group=intval($_POST['group']);
    if($group>=0 && $group<=3)
    {
        require_once("../../models/class.Miscellaneous.php");
        $miscellaneous=new Miscellaneous();
        $miscellaneous->ShowFreePlayers($group);
    }    
}    

