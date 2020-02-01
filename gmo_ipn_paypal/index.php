<?php

// STEP 1: Read POST data

// reading posted data from directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead. 
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
     $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}


// STEP 2: Post IPN data back to paypal to validate

$ch = curl_init('https://www.paypal.com/cgi-bin/webscr'); // change to [...]sandbox.paypal[...] when using sandbox to test
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($ch);
    exit;
}
curl_close($ch);


// STEP 3: Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
    // check whether the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment

    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    if ($_POST['mc_gross'] != NULL)
    {    
    	$payment_amount = $_POST['mc_gross'];
    }    
    else
    {    
        $payment_amount = $_POST['mc_gross1'];
    }    
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $custom = $_POST['custom'];
    if($payment_status==="Completed")
    {
        if($receiver_email==="mvicino@ar.ibm.com")
        {
            $id_order=intval($custom);
            require_once("../core/models/class.Connection.php");
            $db=new Connection();
            $stmt=$db->prepare("SELECT Equipos_idEquipos,oro,monto FROM PacksOro INNER JOIN Compras ON PacksOro.idPacksOro=Compras.PacksOro_idPacksOro WHERE idCompras=? AND pagado=0 LIMIT 1;");
            $stmt->bind_param("i", $id_order);
            $stmt->bind_result($id_team,$gold,$amount);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows>0)
            {
                $stmt->fetch();
                if($amount<=floatval($payment_amount))
                {
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
            }
            $stmt->close();
            $db->close();
        }    
    }    

} else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
}
?>