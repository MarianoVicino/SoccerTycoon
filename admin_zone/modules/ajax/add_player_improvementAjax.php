<?php
if(isset($_POST['name']) && isset($_POST['scope']) && isset($_POST['score']) && isset($_POST['price']) && isset($_FILES['logo']))
{
    $scope=intval($_POST['scope']);
    $score=intval($_POST['score']);
    $price=intval($_POST['price']);
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    if(($scope==1 || $scope==0) && $score>0 && $price>0 && $f->Validar_Long($_POST['name'], 3, 45)==1)
    {
        $type=strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if($_FILES['logo']['size']>0 && ($type==='jpg' || $type==='png'))
        {
            require_once("../../models/class.Premium.php");
            $premium=new Premium();
            $premium->AddPlayerImprovement($_POST['name'], $scope, $score, $price, $_FILES['logo'], $type);
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
