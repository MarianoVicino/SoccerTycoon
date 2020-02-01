<?php
if(isset($_POST['player']) && isset($_POST['score']) && isset($_POST['price']) && isset($_POST['stock']) && isset($_FILES['photo']))
{
    $id_player=intval($_POST['player']);
    $score=intval($_POST['score']);
    $price=intval($_POST['price']);
    $stock=intval($_POST['stock']);
    if($id_player>0 && $score>0 && $price>0 && $stock>=0)
    {
        $type=strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if($_FILES['photo']['size']==0 || ($_FILES['photo']['size']>0 && ($type==='jpg' || $type==='png')))
        {
            require_once("../../models/class.Premium.php");
            $premium=new Premium();
            $premium->EditPremiumPlayer($id_player, $score, $price, $stock,$_FILES['photo'],$type);
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

