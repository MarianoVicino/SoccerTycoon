<?php
if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['g-recaptcha-response']))
{
   $Retorno=getCaptcha($_POST['g-recaptcha-response']);
    if($Retorno->success) 
    { 
        if(!empty($_POST['user']) && !empty($_POST['pass']))
        {
            require_once("../../models/class.Fn.php");
            $f=new Fn();
            if($f->Validar_Long($_POST['user'], 4, 12)==1 && $f->Validar_Long($_POST['pass'], 4, 12)==1)
            {
                require_once("../../models/class.Admin.php");
                $admin=new Admin();
                $admin->Login($_POST['user'], $_POST['pass']);
            }
            else
            {
                echo '<p class="alert alert-danger">Por favor, enviar longitud válida (6-12).</p>';
            }    
        }
        else
        {
            echo '<p class="alert alert-danger">Por favor, no enviar datos vacíos.</p>';
        }
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Por favor, verificar captcha.
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
