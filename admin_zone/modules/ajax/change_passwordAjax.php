<?php
if(isset($_POST['old_pw']) && isset($_POST['new_pw']) && isset($_POST['new_pw2']))
{
    require("../../models/class.Fn.php");
    $f=new Fn();
    if($f->Validar_Long($_POST['old_pw'], 5, 12) && $f->Validar_Long($_POST['new_pw'], 5, 12) && $f->Validar_Long($_POST['new_pw2'], 5, 12))
    {
        if(strcmp($_POST['new_pw'], $_POST['new_pw2'])==0)
        {
            require("../../models/class.Admin.php");
            session_start();
            $admin= new Admin();
            $admin->ChangePassword($_SESSION['admin_fmo'], $_POST['old_pw'], $_POST['new_pw']);
        }        
        else
        {
            echo '<p class="alert alert-danger">Las contraseñas no coinciden, intente nuevamente.</p>';
        }    
    }
    else
    {
        echo '<p class="alert alert-danger">Por favor, envie datos válidos (5-12 caracteres).</p>';
    }    
}
?>            