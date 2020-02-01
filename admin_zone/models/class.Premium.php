<?php
class Premium
{
    public function AddPremiumPlayer($name,$position,$group,$price,$stock,$score,$photo,$type)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        if(empty($type))
        {
            $stmt=$db->prepare("INSERT INTO JugadoresPagos (nombre_jp,stock_jp,posicion_jp,puntos_jp,precio_jp,grupo_jp) VALUES (?,?,?,?,?,?);");
            $stmt->bind_param("sisiii",$name,$stock,$position,$score,$price,$group);
        }
        else
        {
            $photo=file_get_contents($photo['tmp_name']);
            $stmt=$db->prepare("INSERT INTO JugadoresPagos (nombre_jp,stock_jp,posicion_jp,puntos_jp,precio_jp,grupo_jp,foto_jp,tipo_foto_jp) VALUES (?,?,?,?,?,?,?,?);");
            $stmt->bind_param("sisiiiss",$name,$stock,$position,$score,$price,$group,$photo,$type);
        }
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     El jugador ha sido agregado con éxito.
               </div>';
    }
    public function ShowPremiumPlayers()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre_jp,posicion_jp,puntos_jp,stock_jp,precio_jp FROM JugadoresPagos;");
        $stmt->bind_result($name,$position,$score,$stock,$precio);
        $stmt->execute();
        $count=1;
        while($stmt->fetch())
        {
            echo '<tr>
                    <td>',$name,'</td>
                    <td>',$position,'</td>
                    <td>',$score,'</td>
                    <td>', number_format($precio,0,',', '.'),'</td>
                    <td>',$stock,'</td>
                 </tr>';
        } 
        $stmt->close();
        $db->close();
    }  
    public function SelectPremiumPlayers()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadoresPagos,nombre_jp,stock_jp FROM JugadoresPagos ORDER BY stock_jp ASC;");
        $stmt->bind_result($id,$name,$stock);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">',$name,' - (',$stock,')</option>';
        }  
        $stmt->close();
        $db->close();
    } 
    public function GetPremiumPlayerInfo($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT puntos_jp,precio_jp,stock_jp FROM JugadoresPagos WHERE idJugadoresPagos=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($score,$price,$stock);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            while($stmt->fetch())
            {
                echo '<script>
                            $("input[name=score]").val(',$score,');
                            $("input[name=price]").val(',$price,');
                            $("input[name=stock]").val(',$stock,');
                      </script>';
            } 
            $stmt->close();
            $db->close();
        }
        else
        {
            $stmt->close();
            $db->close();
        }    
    }  
    public function EditPremiumPlayer($id,$score,$price,$stock,$photo,$type)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        if(empty($type))
        {
            $stmt=$db->prepare("UPDATE JugadoresPagos SET stock_jp=?,puntos_jp=?,precio_jp=? WHERE idJugadoresPagos=?;");
            $stmt->bind_param("iiii", $stock,$score,$price,$id);
        }
        else
        {
            $photo=file_get_contents($photo['tmp_name']);
            $stmt=$db->prepare("UPDATE JugadoresPagos SET stock_jp=?,puntos_jp=?,precio_jp=?,foto_jp=?,tipo_foto_jp=? WHERE idJugadoresPagos=?;");
            $stmt->bind_param("iiissi", $stock,$score,$price,$photo,$type,$id);
        }    
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            El jugador ha sido editado satisfactoriamente, actualizando...
                        </div><script>$(location).attr("href","index.php?module=edit_premium_player");</script>';
    }  
	
	public function AddPlayerPack($players, $gold,$price,$imagen)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idPacksJugador FROM PacksJugador WHERE oro=? AND jugadores = ?;");
        $stmt->bind_param("ii", $gold, $players);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            $stmt=$db->prepare("INSERT INTO PacksJugador (jugadores,oro,precio,imagen) VALUES (?,?,?,?);");
            $stmt->bind_param("siis", $players,$gold,$price,$imagen);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            El pack ha sido agregado con éxito.
                        </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Ese pack ya existe, puedes <a href="index.php?module=edit_player_pack">editarlo</a> o intenta con otra cantidad de jugadores...
                        </div>';
        }    
    }
	
    public function AddGoldPack($gold,$price)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT oro FROM PacksOro WHERE oro=?;");
        $stmt->bind_param("i", $gold);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            $stmt=$db->prepare("INSERT INTO PacksOro (oro,precio) VALUES (?,?);");
            $stmt->bind_param("id", $gold,$price);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            El pack ha sido agregado con éxito.
                        </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Ese pack ya existe, puedes <a href="index.php?module=edit_gold_pack">editarlo</a> o intenta con otra cantidad de oro...
                        </div>';
        }    
    }    
    public function ShowGoldPacks()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT oro,precio FROM PacksOro ORDER BY precio ASC;");
        $stmt->bind_result($gold,$price);
        $stmt->execute();
        $count=1;
        while($stmt->fetch())
        {
            echo '<tr>
                      <td>',$count,'</td>
                      <td>',$gold,'</td>
                      <td>$',number_format($price, 2, ",", "."),'</td>
                  </tr>';
            $count++;
        } 
        $stmt->close();
        $db->close();
    }
	public function SelectPlayerPacks()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idPacksJugador,jugadores FROM PacksJugador ORDER BY precio ASC;");
        $stmt->bind_result($id,$players);
        $stmt->execute();
		while($stmt->fetch())
        {
            echo '<option value="',$id,'">Pack de ',$players,' jugadores</option>';
        }
        $stmt->close();
        $db->close();
    }
    public function SelectGoldPacks()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idPacksOro,oro FROM PacksOro ORDER BY precio ASC;");
        $stmt->bind_result($id,$gold);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">Pack ',$gold,' de Oro</option>';
        }
        $stmt->close();
        $db->close();
    }
	
	public function GetPlayerPackInfo($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT jugadores,oro,precio,imagen FROM PacksJugador WHERE idPacksJugador=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($players,$gold,$price,$imagen);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            while($stmt->fetch())
            {
                echo '<script>
                            $("input[name=players]").val(',$players,');
                            $("input[name=gold]").val(',$gold,');
                            $("input[name=price]").val(',$price,');
							$("option[value=\'',$imagen,'\']").prop("selected", true);
                      </script>';
            } 
            $stmt->close();
            $db->close();
        }
        else
        {
            $stmt->close();
            $db->close();
        }          
    } 
	
    public function GetPackInfo($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT oro,precio FROM PacksOro WHERE idPacksOro=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($gold,$price);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            while($stmt->fetch())
            {
                echo '<script>
                            $("input[name=gold]").val(',$gold,');
                            $("input[name=price]").val(',$price,');
                      </script>';
            } 
            $stmt->close();
            $db->close();
        }
        else
        {
            $stmt->close();
            $db->close();
        }          
    }
	
	public function EditPlayerPack($id,$player,$gold,$price,$image)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idPacksJugador FROM PacksJugador WHERE idPacksJugador=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("UPDATE PacksJugador SET jugadores=?,oro=?,precio=?,imagen=? WHERE idPacksJugador=?;");
            $stmt->bind_param("siisi", $player,$gold,$price,$image,$id);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            El pack ha sido editado satisfactoriamente, actualizando...
                        </div><meta http-equiv="refresh" content="1;URL="index.php?module=edit_gold_pack"/>';
        }else{
            $stmt->close();
            $db->close();
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Este pack ya no existe.
					</div>';
        }    
    }
	
    public function EditGoldPack($id,$gold,$price)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idPacksOro FROM PacksOro WHERE idPacksOro=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("UPDATE PacksOro SET oro=?,precio=? WHERE idPacksOro=?;");
            $stmt->bind_param("idi", $gold,$price,$id);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            El pack ha sido editado satisfactoriamente, actualizando...
                        </div><meta http-equiv="refresh" content="1;URL="index.php?module=edit_gold_pack"/>';
        }
        else
        {
            $stmt->close();
            $db->close();
        }    
    } 
    public function AddPlayerImprovement($name,$scope,$score,$price,$logo,$type)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $logo=file_get_contents($logo['tmp_name']);
        $stmt=$db->prepare("INSERT INTO MejorasJugador (nombre_mju,puntos_mju,precio_mju,logo_mju,tipo_logo_mju,afecta_todos) VALUES (?,?,?,?,?,?);");
        $stmt->bind_param("siissi", $name,$score,$price,$logo,$type,$scope);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La mejora ha sido agregada satisfactoriamente.
                        </div>';
    } 
    public function ShowPlayerImprovements()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre_mju,puntos_mju,precio_mju,afecta_todos FROM MejorasJugador ORDER BY afecta_todos ASC;");
        $stmt->bind_result($name,$score,$gold,$scope);
        $stmt->execute();
        while($stmt->fetch())
        {
            if($scope==1)
            {
                $scope="Equipo";
            }
            else
            {
                $scope="Jugador";
            }    
            echo '<tr>
                      <td>',$name,'</td>
                      <td>',$score,'</td>
                      <td>',$gold,'</td>
                      <td>',$scope,'</td>
                  </tr>';
        } 
        $stmt->close();
        $db->close();
    }    
    public function SelectPlayerImprovements()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idMejorasJugador,nombre_mju FROM MejorasJugador;");
        $stmt->bind_result($id,$name);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">',$name,'</option>';
        }   
        $stmt->close();
        $db->close();
    }   
    public function GetPlayerImprovementsInfo($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT puntos_mju,precio_mju FROM MejorasJugador WHERE idMejorasJugador=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($score,$price);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<script>
                        $("input[name=score]").val(',$score,');
                        $("input[name=price]").val(',$price,');
                 </script>';
        }
        $stmt->close();
        $db->close();
    }  
    public function EditPlayerImprovement($id,$name,$score,$price,$logo,$type)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        if($logo['size']==0)
        {
            $stmt=$db->prepare("UPDATE MejorasJugador SET nombre_mju=?,puntos_mju=?,precio_mju=? WHERE idMejorasJugador=?;");
            $stmt->bind_param("siii", $name,$score,$price,$id);
            $stmt->execute();
        } 
        else
        {
            $logo=file_get_contents($logo['tmp_name']);
            $stmt=$db->prepare("UPDATE MejorasJugador SET nombre_mju=?,puntos_mju=?,precio_mju=?,logo_mju=?,tipo_logo_mju=? WHERE idMejorasJugador=?;");
            $stmt->bind_param("siissi", $name,$score,$price,$logo,$type,$id);
            $stmt->execute();
        }
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La mejora ha sido editada satisfactoriamente, actualizando...
                        </div><meta http-equiv="refresh" content="1;URL="index.php?module=edit_player_improvement"/>';
    }  
    public function AddStadiumImprovement($capacity,$price)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT * FROM MejorasEstadio WHERE capacidad_me=?;");
        $stmt->bind_param("i", $capacity);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            $stmt=$db->prepare("INSERT INTO MejorasEstadio (capacidad_me,precio_me) VALUES (?,?);");
            $stmt->bind_param("ii", $capacity,$price);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La mejora de estadio ha sido agregada con éxito.
                        </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Esa mejora ya existe, puedes editarla <a href="index.php?module=edit_stadium_improvement">aqui</a>.
                        </div>';
        }    
    } 
    public function ShowStadiumImprovements()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT capacidad_me,precio_me FROM MejorasEstadio ORDER BY capacidad_me ASC;");
        $stmt->bind_result($capacity,$gold);
        $stmt->execute();
        $count=1;
        while($stmt->fetch())
        {
            echo '<tr>
                      <td>',$count,'</td>
                      <td>',$capacity,' Butacas</td>
                      <td>',$gold,'</td>
                  </tr>';
            $count++;
        } 
        $stmt->close();
        $db->close();
    } 
    public function SelectStadiumImprovements()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idMejorasEstadio,capacidad_me FROM MejorasEstadio;");
        $stmt->bind_result($id,$capacity);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">Mejora de ',$capacity,' Butacas</option>';
        } 
        $stmt->close();
        $db->close();
    }   
    public function GetStadiumImprovementsInfo($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT capacidad_me,precio_me FROM MejorasEstadio WHERE idMejorasEstadio=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($capacity,$price);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<script>
                        $("input[name=capacity]").val(',$capacity,');
                        $("input[name=price]").val(',$price,');
                 </script>';
        }
        $stmt->close();
        $db->close();        
    }  
    public function EditStadiumImprovement($id,$capacity,$price)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("UPDATE MejorasEstadio SET capacidad_me=?,precio_me=? WHERE idMejorasEstadio=?;");
        $stmt->bind_param("iii", $capacity,$price,$id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La mejora ha sido editada satisfactoriamente, actualizando...
                        </div><meta http-equiv="refresh" content="1;URL="index.php?module=edit_stadium_improvement"/>';
    }        
}