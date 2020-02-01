<?php
session_start();
if(isset($_POST['id']) && isset($_POST['email']))
{
    $id=intval($_POST['id']);
    if(($id==1 || $id==2 || $id==3) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        require_once("../../models/class.User.php");
        $user=new User();
        $email=htmlspecialchars($_POST['email'],ENT_QUOTES);
        $user->EditWithdrawalMethod($_SESSION['user_fmo'], $id, $email);
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                The e-mail is invalid, please try again.
                          </div>';
    }    
}    
?>

