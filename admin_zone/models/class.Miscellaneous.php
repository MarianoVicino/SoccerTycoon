<?php
class Miscellaneous
{
    protected $escudo,$camiseta,$tipo;
    protected $name,$position,$group,$number,$score;
    public function AddShield($escudo,$tipo)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->escudo=file_get_contents($escudo['tmp_name']);
        $this->tipo=$tipo;
        $stmt=$db->prepare("INSERT INTO Escudos (escudo,tipo_es) VALUES (?,?);");
        $stmt->bind_param("ss",$this->escudo,$this->tipo);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           El escudo ha sido agregado satisfactoriamente.
                      </div>';
    } 
    public function ShowShields()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT escudo,tipo_es FROM Escudos;");
        $stmt->bind_result($this->escudo, $this->tipo);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<div class="col-xs-4 col-sm-2">
                    <div class="well">
                    <img class="img-responsive center-block shield" src="data:image/',$this->tipo,';base64,'.base64_encode($this->escudo).'"/>
                    </div>
                  </div>';
        } 
        $stmt->close();
        $db->close();
    }  
    public function AddShirt($camiseta,$tipo)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->camiseta=file_get_contents($camiseta['tmp_name']);
        $this->tipo=$tipo;
        $stmt=$db->prepare("INSERT INTO Camisetas (camiseta,tipo_ca) VALUES (?,?);");
        $stmt->bind_param("ss",$this->camiseta,$this->tipo);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           La camiseta ha sido agregado satisfactoriamente.
                      </div>';
    } 
    public function ShowShirts()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT camiseta,tipo_ca FROM Camisetas;");
        $stmt->bind_result($this->camiseta, $this->tipo);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<div class="col-xs-4 col-sm-2">
                    <div class="well">
                    <img class="img-responsive center-block shield" src="data:image/',$this->tipo,';base64,'.base64_encode($this->camiseta).'"/>
                    </div>
                  </div>';
        } 
        $stmt->close();
        $db->close();
    } 
    public function AddFreePlayer($name,$position,$group,$number,$score)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->number=$number;
        $stmt=$db->prepare("SELECT idJugadoresGratis FROM JugadoresGratis WHERE numero_jg=?;");
        $stmt->bind_param("i", $this->number);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {    
            $this->score=$score;
            $this->name=$name;
            $this->position=$position;
            $this->group=$group;
            $stmt=$db->prepare("INSERT INTO JugadoresGratis (nombre_jg,numero_jg,posicion_jg,puntos_jg,grupo_jg) VALUES (?,?,?,?,?);");
            $stmt->bind_param("sisii", $this->name,$this->number,$this->position,$this->score,$this->group);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        El jugador ha sido agregado satisfactoriamente, ya puede ser elegido.
                   </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Ese numero de camiseta ya existe, por favor, elija otro.
                   </div>';
        }    
    } 
    public function ShowFreePlayers($group)
    {
        require_once("../../../core/models/class.Connection.php");
        $this->group=$group;
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre_jg,numero_jg,posicion_jg,puntos_jg FROM JugadoresGratis WHERE grupo_jg=?;");
        $stmt->bind_param("i", $this->group);
        $stmt->bind_result($this->name, $this->number, $this->position, $this->score);
        $stmt->execute();
        $count=1;
        echo '<table class="table table-striped table-responsive text-center">
            <tr>
                <td>#</td>
                <td>Nombre</td>
                <td>Número</td>
                <td>Posición</td>
                <td>Scoring</td>
            </tr>';
        while($stmt->fetch())
        {
            echo '<tr>
                    <td>',$count,'</td>
                    <td>',$this->name,'</td>
                    <td>',$this->number,'</td>
                    <td>',$this->position,'</td>
                    <td>',$this->score,'</td>
                  </tr>';
            $count++;
        }
        echo '</table>';
        $stmt->close();
        $db->close();
    }  
    public function AddNew($title,$text,$date)
    {
       require_once("../../../core/models/class.Connection.php");
       $db=new Connection(); 
       $stmt=$db->prepare("INSERT INTO Noticias (titulo,texto,fecha) VALUES (?,?,?);");
       $stmt->bind_param("sss", $title,$text,$date);
       $stmt->execute();
       $stmt->close();
       $db->close();
       echo '<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        La noticia ha sido agregada con éxito.
                   </div>';
    }  
    public function SearchUser($data)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $data="%{$data}%";
        $stmt=$db->prepare("SELECT usuario FROM Equipos WHERE usuario LIKE ?;");
        $stmt->bind_param("s", $data);
        $stmt->bind_result($user);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $array[]=$user;
        }
        $stmt->close();
        $db->close();
        return $array;
    }
	public function AddPlayerToUser($user, $number, $name, $position, $group, $scoring){
		require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $user=mb_strtolower($user,'UTF-8');
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_team);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
			$stmt->fetch();
			$stmt=$db->prepare("SELECT * FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND numero_ju=?;");
			$stmt->bind_param("si", $user,$number);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows==0)
			{
				$stmt=$db->prepare("INSERT INTO Jugadores (nombre_ju,numero_ju,posicion_ju,puntos_ju,grupo_ju,Equipos_idEquipos) VALUES (?,?,?,?,?,?);");
				$stmt->bind_param("sisiii", $name,$number,$position,$scoring,$group,$id_team);
				$stmt->execute();
				$stmt->store_result();
				$new_id=$stmt->insert_id;

				$stmt=$db->prepare("INSERT INTO Suplentes (Jugadores_idJugadores) VALUES (?);");
				$stmt->bind_param("i", $new_id);
				$stmt->execute();
				$stmt->close();
				$db->close();
				
				echo '<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Se inserto correctamente el jugador ',$name,', en el equipo de ',$user,'.
					</div>';
			}else{
				$stmt->close();
				$db->close();
				echo '<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Este numero ya esta usando en este equipo.
					</div>';
			}
		}else{
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                El usuario ingresado no existe, por favor intente nuevamente.
                          </div>';
        }  
	}
	
    public function AddCoins($user,$coins)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $user=mb_strtolower($user,'UTF-8');
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("UPDATE Equipos SET oro=oro+? WHERE usuario=?;");
            $stmt->bind_param("is", $coins,$user);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                El balance de St ',$user,' se ha incrementado.
                          </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                El usuario ingresado no existe, por favor intente nuevamente.
                          </div>';
        }    
    } 
    public function DeleteUser($user)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos,rango_div,idLigas,full FROM Divisiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones INNER JOIN Equipos ON Ligas.idLigas=Equipos.Ligas_idLigas WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_team,$rangue,$id_league,$full);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $nombre="Team ".($id_team-1);
            $stmt=$db->prepare("UPDATE Equipos SET nombre=?,usuario=NULL,clave=NULL,email=NULL,email_paypal=NULL,email_mercadopago=NULL,email_neteller=NULL,fantasma=1,asignado=1,oro=1000 WHERE usuario=?;");
            $stmt->bind_param("ss", $nombre,$user);
            $stmt->execute();
            /*if($rangue==4)
            {*/
                if($full==1)
                {
                    $full=0;
                }
                $buscar = mysqli_query($db, "SELECT equipos_reales FROM `Ligas` WHERE idLigas='".$id_league."'");
                if($buscar->num_rows >0){
                    $stmt=$db->prepare("UPDATE Ligas SET equipos_reales=equipos_reales-1,full=? WHERE idLigas=?;");
                    $stmt->bind_param("ii", $full,$id_league);
                    $stmt->execute();
                }
                
            //}    
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        El usuario ha sido borrado con éxito.
                   </div>';
        }
        else
        {
            
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        El usuario no existe, vuelva a intentarlo..
                   </div>';
        }    
    }        
}
?>