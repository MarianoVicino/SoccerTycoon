<?php
    require_once("../../models/class.Fn.php");
    $f=new Fn();
	$name = $_POST['name'];
	$position = $_POST['position'];
    $scoring=intval($_POST['scoring']);
	$user = $_POST['user'];
	$number = intval($_POST['number']);
    if($f->Validar_Long($_POST['name'], 3, 45)==1 && $scoring>0)
    {
        $group=$f->DeterminatePlayerGroup($position);
        if($group!=-1)
        {
			require_once("../../models/class.Miscellaneous.php");
			$miscellaneous=new Miscellaneous();
			$miscellaneous->AddPlayerToUser($user, $number, $name, $position, $group, $scoring);
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
?>