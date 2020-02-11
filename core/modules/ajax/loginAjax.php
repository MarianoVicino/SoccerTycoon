<?php
if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response']))
{
    $Retorno=getCaptcha($_POST['g-recaptcha-response']);
    if($Retorno->success)
    {   
        require_once("../../models/class.Fn.php");
        $f=new Fn();
        if($f->Validar_Long($_POST['user'], 4, 12)==1 && $f->Validar_Long($_POST['password'], 4, 12)==1)
        {
            require_once("../../models/class.User.php");
            $user=new User();
            $user->UserLogin($_POST['user'], $_POST['password']);
        }        
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Invalid data, accepted length (4-12).
                              </div>';
        }
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Please, complete the captcha to login.
                              </div>';
    }    
}   
function getCaptcha($SecretKey)
{
    $Respuesta=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeFodcUAAAAADlPtNbnus2fafGRw6_o8vrRcyM0&response={$SecretKey}");
    $Retorno=json_decode($Respuesta);
    return $Retorno;
    
 }  
?>

