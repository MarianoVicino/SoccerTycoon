<?php
session_start();
if(isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['new_password2']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    if($f->Validar_Long($_POST['old_password'], 4, 12)==1 && $f->Validar_Long($_POST['new_password'], 4, 12)==1 && $f->Validar_Long($_POST['new_password2'], 4, 12)==1)
    {
        if($_POST['new_password']===$_POST['new_password2'])
        {
            require_once("../../models/class.User.php");
            $user=new User();
            $user->ChangePassword($_SESSION['user_fmo'], $_POST['old_password'], $_POST['new_password']);
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The passswords are different, please try again.
                      </div>';
        }    
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Invalid data, accepted length (4-12).
                          </div>';
    }    
}    
?>

