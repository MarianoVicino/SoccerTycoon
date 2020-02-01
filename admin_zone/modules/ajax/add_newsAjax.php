<?php
if(isset($_POST['title']) && isset($_POST['text']))
{
    if(!empty($_POST['title']) && !empty($_POST['text']))
    {
        require_once("../../models/class.Miscellaneous.php");
        $miscellaneous=new Miscellaneous();
        $miscellaneous->AddNew($_POST['title'], $_POST['text'], date("m/d/Y"));
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Por favor, colocar titulo y texto.
               </div>';
    }    
}    
?>

