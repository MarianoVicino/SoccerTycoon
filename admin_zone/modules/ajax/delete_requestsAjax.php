<?php
if(isset($_POST['id']))
{
    $id=intval($_POST['id']);
    require_once("../../models/class.Orders.php");
    $order=new Orders();
    $order->DeleteRequest($id);
}    

?>

