<?php
if(isset($_POST['name']) && isset($_POST['position']) && isset($_POST['number']) && isset($_POST['scoring']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    $number=intval($_POST['number']);
    $scoring=intval($_POST['scoring']);
    if($f->Validar_Long($_POST['name'], 3, 45)==1 && $number>0 && $scoring>0)
    {
        $group=$f->DeterminatePlayerGroup($_POST['position']);
        if($group!=-1)
        {
            require_once("../../models/class.Miscellaneous.php");
            $miscellaneous=new Miscellaneous();
            $miscellaneous->AddFreePlayer($_POST['name'], $_POST['position'],$group, $number, $scoring);
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Por favor, seleccionar una posición.
               </div>';
        }    
    }
    else 
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     Colocar datos válidos.
               </div>';
    }
}    
?>
