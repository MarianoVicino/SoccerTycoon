<?php
if(isset($_POST['name']) && isset($_POST['position']) && isset($_POST['gold']) && isset($_POST['stock']) && isset($_POST['scoring']) && isset($_FILES['photo']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    $price=intval($_POST['gold']);
    $scoring=intval($_POST['scoring']);
    $stock=intval($_POST['stock']);
    if($f->Validar_Long($_POST['name'], 3, 45)==1 && $price>0 && $scoring>0 && $stock>0)
    {
        $group=$f->DeterminatePlayerGroup($_POST['position']);
        if($group!=-1)
        {
            $type=strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if($_FILES['photo']['size']==0 || ($_FILES['photo']['size']>0 && ($type==='jpg' || $type==='png')))
            {
                require_once("../../models/class.Premium.php");
                $premium=new Premium();
                $premium->AddPremiumPlayer($_POST['name'], $_POST['position'], $group, $price, $stock, $scoring,$_FILES['photo'],$type);
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