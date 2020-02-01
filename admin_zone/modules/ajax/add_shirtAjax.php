<?php
    if(isset($_FILES['shirt']))
    {
        $type=strtolower(pathinfo($_FILES['shirt']['name'], PATHINFO_EXTENSION));
        if($_FILES['shirt']['size']>0 && $type==='png')
        {
            require_once("../../models/class.Miscellaneous.php");
            $miscellaneous=new Miscellaneous();
            $miscellaneous->AddShirt($_FILES['shirt'], $type);
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Colocar una imagen válida (solo .png).
                  </div>';
        }        
    } 
?>
