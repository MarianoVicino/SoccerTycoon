<?php
if(isset($_POST['gold']) && isset($_POST['price']))
{
    require_once("../../models/class.Fn.php");
    $f=new Fn();
	$players = $_POST['players'];
    $price=$f->Validate_Price($_POST['price']);
    $gold=intval($_POST['gold']);
	$image = $_POST['image'];
	if($image == '0'){
		echo '<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				Por favor, seleccione una imagen.
			</div>';
		exit;
	}
	if($gold>0 && $price!=-1)
    {
        require_once("../../models/class.Premium.php");
        $premium=new Premium();
        $premium->AddPlayerPack($players, $gold, $price, $image);
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

