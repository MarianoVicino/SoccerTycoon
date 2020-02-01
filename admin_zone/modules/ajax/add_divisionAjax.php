<?php
    if(isset($_POST['rango']) && isset($_POST['region']) && isset($_POST['division']) && isset($_POST['precio']) && isset($_FILES['logo']))
    {
        $id_region=intval($_POST['region']);
        if($id_region>0)
        {
            require_once("../../models/class.Fn.php");
            $f=new Fn();
            $oro=intval($_POST['precio']);
            $range=intval($_POST['rango']);
            if($f->Validar_Long($_POST['division'], 2, 45)==1 && $oro%2==0 && $oro>0 && $range>=1 && $range<=4)
            {
                $type=strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                if($_FILES['logo']['size']>0 && ($type==='jpg' || $type==='png'))
                {
                    require_once("../../models/class.Division.php");
                    $division=new Division();
                    $division->AddDivision($id_region, $_POST['division'], $oro, $_FILES['logo'], $type,$range);
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
                                Colocar un nombre válido (2-45), un rango y un premio que sea par y positivo.
                          </div>';
            } 
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Por favor, seleccionar una región.
                          </div>';
        }    
   
    }    
?>
