<?php
if(isset($_POST['pack']) && isset($_POST['gold']) && isset($_POST['price']))
{
    $gold=intval($_POST['gold']);
    $id_pack=intval($_POST['pack']);
    require_once("../../models/class.Fn.php");
    $f=new Fn();
    $price=$f->Validate_Price($_POST['price']);
    if($id_pack>0 && $gold>0 && $price!=-1)
    {
        require_once("../../models/class.Premium.php");
        $premium=new Premium();
        $premium->EditGoldPack($id_pack, $gold, $price);
    } 
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Por favor, colocar datos válidos.
                        </div>';
    }    
}    
?>
