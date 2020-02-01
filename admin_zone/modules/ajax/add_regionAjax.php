<?php
    if(isset($_POST['region']) && isset($_FILES['logo']))
    {
        require_once("../../models/class.Fn.php");
        $f=new Fn();
        if($f->Validar_Long($_POST['region'], 2, 45)==1)
        {
            $type=strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if($_FILES['logo']['size']>0 && ($type==='jpg' || $type==='png'))
            {
                require_once("../../models/class.Region.php");
                $region=new Region();
                $region->AddRegion($_POST['region'], $_FILES['logo'],$type);
            }
            else
            {
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Colocar una imagen válida.
                      </div>';
            }            
        }        
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Colocar un nombre válido (2-45).
                      </div>';
        }    
    }    
?>

