<?php
if(isset($_POST['improvement']) && isset($_POST['name']) && isset($_POST['score']) && isset($_POST['price']) && isset($_FILES['logo']))
{
    $id_imp=intval($_POST['improvement']);
    $score=intval($_POST['score']);
    $price=intval($_POST['price']);
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    if($id_imp>0 && $score>0 && $price>0 && $f->Validar_Long($_POST['name'], 3, 45)==1)
    {
        $type=strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if($_FILES['logo']['size']==0 || ($_FILES['logo']['size']>0 && ($type==='jpg' || $type==='png')))
        {
            require_once("../../models/class.Premium.php");
            $premium=new Premium();
            $premium->EditPlayerImprovement($id_imp, $_POST['name'], $score, $price, $_FILES['logo'], $type);
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
                            Colocar datos válidos.
                      </div>';
    }    
}    
?>

