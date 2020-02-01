<?php
require_once "../core/mercadopago/mercadopago.php";
$mp = new MP('383015242804717', 'yn6kpl34FN5tsai2JDdNCW892KGDBpxG');
if(!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"]))
{
    http_response_code(404);
    return;
}

// Get the payment and the corresponding merchant_order reported by the IPN.
if($_GET["topic"] == 'payment')
{
    $payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
    $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
// Get the merchant_order reported by the IPN.
} 
else if($_GET["topic"] == 'merchant_order')
{
	$merchant_order_info = $mp->get("/merchant_orders/" . $_GET["id"]);
}
if($merchant_order_info["status"] == 200)
{
    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items 
    $paid_amount = 0;
    foreach ($merchant_order_info["response"]["payments"] as  $payment)
    {
	if ($payment['status'] == 'approved')
        {
            $paid_amount += $payment['transaction_amount'];
	}	
    }
    if($paid_amount >= $merchant_order_info["response"]["total_amount"])
    {
	if(count($merchant_order_info["response"]["shipments"]) > 0) 
        { 
            // The merchant_order has shipments
            if($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship")
            {
		//print_r("Totally paid. Print the label and release your item.");
            }
        }
        else
        { 
            $id_order=intval($merchant_order_info['response']['external_reference']);
            require_once("../core/models/class.Connection.php");
            $db=new Connection();
            $stmt=$db->prepare("SELECT Equipos_idEquipos,oro FROM PacksOro INNER JOIN Compras ON PacksOro.idPacksOro=Compras.PacksOro_idPacksOro WHERE idCompras=? AND pagado=0 LIMIT 1;");
            $stmt->bind_param("i", $id_order);
            $stmt->bind_result($id_team,$gold);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows>0)
            {
                $stmt->fetch();
                //LO COLOCO COMO PAGADO
                $stmt=$db->prepare("UPDATE Compras SET pagado=1 WHERE idCompras=?");
                $stmt->bind_param("i", $id_order);
                $stmt->execute();
                //CONSIGO EL BALANCE DEL EQUIPO
                $stmt=$db->prepare("SELECT oro FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_team);
                $stmt->bind_result($balance);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                // LE AGREGO EL ORO QUE COMPRO
                $balance=$balance+$gold;
                $stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                $stmt->bind_param("ii", $balance,$id_team);
                $stmt->execute();
            }
            $stmt->close();
            $db->close();
        }
    }
    else
    {
	//print_r("Not paid yet. Do not release your item.");
    }
}

?>