<?php
if(isset($_POST['improvement']) && isset($_POST['capacity']) && isset($_POST['price']))
{
    $capacity=intval($_POST['capacity']);
    $price=intval($_POST['price']);
    $id_imp=intval($_POST['improvement']);
    if($id_imp>0 && $capacity>0 && $price>0)
    {
        require_once("../../models/class.Premium.php");
        $premium=new Premium();
        $premium->EditStadiumImprovement($id_imp, $capacity, $price);
    }    
}    
?>