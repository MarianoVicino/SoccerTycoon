<?php
if(isset($_POST['email']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    if($f->Validar_Long($_POST['email'], 6, 100)==1 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        require_once("../../models/class.User.php");
        $user=new User();
        $user->ResetPassword($_POST['email']);
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

