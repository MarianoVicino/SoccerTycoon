<?php
class Orders
{
    public function GetBoughtPacks()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idCompras,oro,precio,fecha,Equipos_idEquipos,Pago FROM PacksOro INNER JOIN Compras ON PacksOro.idPacksOro=Compras.PacksOro_idPacksOro WHERE pagado=1 ORDER BY idCompras DESC;");
        $stmt->bind_result($id,$pack,$price,$date,$id_team,$pago);
        $stmt->execute();
        $stmt->store_result();
		$packs = [];
        while($stmt->fetch())
        {
            $stmt2=$db->prepare("SELECT usuario FROM Equipos WHERE idEquipos=? LIMIT 1;");
            $stmt2->bind_param("i", $id_team);
            $stmt2->bind_result($user);
            $stmt2->execute();
            $stmt2->store_result();
            $stmt2->fetch();
            $stmt2->close();
			$packs[$id] = array('type' => '1', 'pack' => $pack, 'date' => $date, 'price' => $price, 'user' => $user, 'pago' => $pago);
        }
		
		$stmt=$db->prepare("SELECT idCompras,jugadores,oro,precio,fecha,Equipos_idEquipos,Pago FROM PacksJugador INNER JOIN Compras ON PacksJugador.idPacksJugador=Compras.PacksJugador_idPacksJugador WHERE pagado=1 ORDER BY idCompras DESC;");
        $stmt->bind_result($id,$pack,$gold,$price,$date,$id_team,$pago);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $stmt2=$db->prepare("SELECT usuario FROM Equipos WHERE idEquipos=? LIMIT 1;");
            $stmt2->bind_param("i", $id_team);
            $stmt2->bind_result($user);
            $stmt2->execute();
            $stmt2->store_result();
            $stmt2->fetch();
            $stmt2->close();
			if($pago == 'coin')
				$price = $gold;
			$packs[$id] = array('type' => '2', 'pack' => $pack, 'date' => $date, 'price' => $price, 'user' => $user, 'pago' => $pago);
        }

		krsort($packs);
		
		foreach($packs as $p){
			if($p['type'] == '1'){
				$title = $p['pack'] . ' Coins';
			}else{
				$title = $p['pack'] . ' Players';
			}
			
			if($p['pago'] == 'coin'){
				$price = 'Coins '. number_format($p['price'],0,",",".");
			}else{
				$price = 'U$S '. number_format($p['price'],2,",",".");
			}
			
			echo '<tr>
                    <td>',$title,'</td>
                    <td>',$p['date'],'</td>    
                    <td>', $price,'</td>
                    <td>',$p['user'],'</td>    
                  </tr>';
			
		}
		
        $stmt->close();
        $db->close();
    } 
    public function GetRequests()
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idRetiros,usuario,monto,metodo,email FROM Retiros;");
        $stmt->bind_result($id,$user,$amount,$method,$email);
        $stmt->execute();
        $stmt->store_result();
        echo '<table class="table table-responsive table-striped text-center request_table">
                   <tr>
                        <td>Usuario</td>
                        <td>Monto</td>
                        <td>Metodo</td>
                        <td>E-Mail</td>
                        <td>Borrar</td>
                   </tr>';
        while($stmt->fetch())
        {
            echo '<tr>
                       <td>',$user,'</td>
                       <td>U$S ',number_format($amount,2,',','.'),'</td>
                       <td>',$method,'</td>
                       <td>',$email,'</td>
                       <td><button class="btn btn-default btn-xs delete_request" value="',$id,'"><span class="glyphicon glyphicon-remove"></span></button></td>
                   </tr>';
        }
        echo '</table>';
        $stmt->close();
        $db->close();
    } 
    public function DeleteRequest($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("DELETE FROM Retiros WHERE idRetiros=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Retiro borrado satisfactoriamente.
                          </div>';
    }        
}

