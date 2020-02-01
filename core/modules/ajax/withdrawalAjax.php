<?php
session_start();
if(isset($_POST['email']) && isset($_POST['coins']))
{
    $coins=intval($_POST['coins']);
    if($coins>0)
    {
        if($coins>=889000)
        {
            $email=intval($_POST['email']);
            if($email==1 || $email==2 || $email==3)
            {
                require_once("../../models/class.User.php");
                $user = new User();
                $user->WithdrawalMoney($email, $coins, $_SESSION['user_fmo']);
            }
            else
            {
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                The e-mail is invalid, please try again or contact support.
                          </div>';
            }    
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                You have to have at least 889000 (=u$s20) to withdrawal your founds.
                          </div>';
        }
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Please, insert a valid amount of coins to exchange.
                          </div>';
    }    
}    
?>

