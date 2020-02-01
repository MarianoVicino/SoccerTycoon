<?php
if(isset($_POST['capacity']) && isset($_POST['price']))
{
    $capacity=intval($_POST['capacity']);
    $price=intval($_POST['price']);
    if($capacity>0 && $price>0)
    {
        require_once("../../models/class.Premium.php");
        $premium=new Premium();
        $premium->AddStadiumImprovement($capacity, $price);
    }    
}    
?>

