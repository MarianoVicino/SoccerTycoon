<?php
    if(isset($_FILES['shield']))
    {
        $type=strtolower(pathinfo($_FILES['shield']['name'], PATHINFO_EXTENSION));
        if($_FILES['shield']['size']>0 && $type==='png')
        {
            require_once("../../models/class.Miscellaneous.php");
            $miscellaneous=new Miscellaneous();
            $miscellaneous->AddShield($_FILES['shield'], $type);
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
