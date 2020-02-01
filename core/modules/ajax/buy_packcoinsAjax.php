<?php
session_start();
if(isset($_POST['n']) && isset($_POST['method']))
{
    $id_pack=intval($_POST['n']);
    if($id_pack>0)
    {
        $method=intval($_POST['method']);
        if($method==1 || $method==2)
        {
            require_once("../../models/class.User.php");
            $user=new User();
            $user->BuyCoins($id_pack, $method, $_SESSION['user_fmo']);
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           Please, select a payment method.
                   </div>';
        }    
    }    
}    
?>
