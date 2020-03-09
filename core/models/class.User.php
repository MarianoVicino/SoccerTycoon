<?php
class User
{
    public function UserLogin($user,$password)
    {
        session_start();
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $user=mb_strtolower($user,'UTF-8');
        $password=hash('sha512', mb_strtolower($password,'UTF-8'));
        $stmt=$db->prepare("SELECT usuario FROM Equipos WHERE usuario=? AND clave=?;");
        $stmt->bind_param("ss", $user,$password);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->close();
            $db->close();
            $_SESSION['user_fmo']=$user;
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Welcome to Goal Manager Online, ',$user,'.
                          </div><script>$(location).attr("href", "index.php");</script>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Wrong user or password, please try again.
                          </div>';
        }
    }
    public function AddUser($region,$user,$password,$email,$ref,$fr)
    {
        $user=mb_strtolower($user,'UTF-8');
        $email=mb_strtolower($email,'UTF-8');
        if($fr){
            require_once("../../models/class.Connection.php");
        }elseif($fr == 3){
            require_once("../models/class.Connection.php");
        }else{
            require_once("core/models/class.Connection.php");
        }
        
        $db=new Connection();
        //reviso que no exista un usuario con ese id ni mail
        $stmt=$db->prepare("SELECT * FROM Equipos WHERE usuario=? OR email=?;");
        $stmt->bind_param("ss", $user,$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            // reviso si hay lugar o debo crear una liga
            $noEncontroLugar = true;
            $noFinalizoLaBusqueda = true;
            $divActual = 4;
            // Veo si hay lugar en 4ta, 3ra, 2da o 1ra antes de crear una nueva liga
            while ($noEncontroLugar && $noFinalizoLaBusqueda)
            {
              $stmt=$db->prepare("SELECT idEquipos,idLigas,equipos_reales FROM Divisiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones INNER JOIN Equipos ON Ligas.idLigas=Equipos.Ligas_idLigas WHERE Regiones_idRegiones=? AND rango_div=? AND full=0 AND fantasma=1 AND terminada=0 LIMIT 1;");
              $stmt->bind_param("ii", $region,$divActual);
              $stmt->bind_result($id_e_team,$id_e_league,$n_real_teams);
              $stmt->execute();
              $stmt->store_result();

              if($stmt->num_rows>0)
              {
                $noEncontroLugar=false;
              }
              $divActual--;
              if($divActual==0)
              {
                // No encontró lugar en ninguna división
                $noFinalizoLaBusqueda=false;
              }
            }

            if($stmt->num_rows>0)
            {
                //tomo un equipo fantasma
                $stmt->fetch();
                $n_real_teams++;
                if($n_real_teams==16)
                {
                    $full=1;
                }
                else
                {
                    $full=0;
                }
                $stmt=$db->prepare("UPDATE Ligas SET full=?,equipos_reales=? WHERE idLigas=?");
                $stmt->bind_param("iii", $full,$n_real_teams,$id_e_league);
                $stmt->execute();
                $nombre=$user." team";
                $password=hash('sha512', mb_strtolower($password,'UTF-8'));
                $email=mb_strtolower($email,'UTF-8');
                $stmt=$db->prepare("UPDATE Equipos SET nombre=?,usuario=?,clave=?,email=?,oro=10,fantasma=0,referral=?,asignado=0 WHERE idEquipos=?;");
                $stmt->bind_param("ssssii", $nombre,$user,$password,$email,$ref,$id_e_team);
                $stmt->execute();
                $name_stadium=$user." Stadium";
                $stmt=$db->prepare("UPDATE Estadios SET nombre_es=? WHERE Equipos_idEquipos=?;");
                $stmt->bind_param("si", $name_stadium,$id_e_team);
                $stmt->execute();
                $stmt->close();
                $db->close();
                session_start();
                $_SESSION['user_fmo']=$user;
                echo '<div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Welcome to Goal Manager Online, ',$user,', you will be redirected to your team right now!
                              </div><script>setTimeout(function(){$(location).attr("href", "index.php");},1200);</script>';
            }
            else
            {
                //consigo el id de la division mas baja de la region
                $stmt=$db->prepare("SELECT idDivisiones FROM Divisiones WHERE Regiones_idRegiones=? AND rango_div=1;");
                $stmt->bind_param("i", $region);
                $stmt->bind_result($id_division);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows>0)
                {
                    //creo liga
                    $stmt->fetch();
                    $stmt=$db->prepare("SELECT COUNT(*) FROM Ligas WHERE Divisiones_idDivisiones=?;");
                    $stmt->bind_param("i", $id_division);
                    $stmt->bind_result($n_leagues);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->fetch();
                    $n_leagues++;
                    $name_league="League ".$n_leagues;
                    $stmt=$db->prepare("INSERT INTO Ligas (nombre_li,equipos_reales,full,terminada,Divisiones_idDivisiones) VALUES (?,1,0,0,?);");
                    $stmt->bind_param("si", $name_league,$id_division);
                    $stmt->execute();
                    $stmt->store_result();
                    $id_league=$stmt->insert_id;
                    //le coloco la bandera correspondiente a la liga
                    $stmtx=$db->prepare("UPDATE Ligas SET id_ultima=? WHERE idLigas=?;");
                    $stmtx->bind_param("ii", $id_league,$id_league);
                    $stmtx->execute();
                    $stmtx->close();
                    //cargar usuario nuevo y crear equipos fantasmas
                    require_once("../../models/class.Fn.php");
                    $f=new Fn();
                    $teams=array();
                    for($i=1;$i<=16;$i++)
                    {
                        // CREO EQUIPO QUE USARA EL JUGADOR
                        if($i==1)
                        {
                            $nombre=$user." team";
                            $password=hash('sha512', mb_strtolower($password,'UTF-8'));
                            $email=mb_strtolower($email,'UTF-8');
                            $stmt=$db->prepare("INSERT INTO Equipos (nombre,score,usuario,clave,email,oro,fantasma,formacion,nombre_formacion,Ligas_idLigas,referral,asignado) VALUES (?,0,?,?,?,0,0,1,'4-4-2',?,?,0);");
                            $stmt->bind_param("ssssii", $nombre,$user,$password,$email,$id_league,$ref);
                            $stmt->execute();
                            $teams[]=$stmt->insert_id;
                            $sufijo_fantasma=$stmt->insert_id;
                        }
                        else
                        {
                            $nombre="Team ".$sufijo_fantasma;
                            $stmt=$db->prepare("INSERT INTO Equipos (nombre,score,oro,fantasma,formacion,nombre_formacion,Ligas_idLigas,referral) VALUES (?,0,1000,1,1,'4-4-2',?,?);");
                            $stmt->bind_param("sii", $nombre,$id_league,$ref);
                            $stmt->execute();
                            $teams[]=$stmt->insert_id;
                            $sufijo_fantasma=$stmt->insert_id;
                        }
                        //CARGO JUGADORES DEL EQUIPO CORRESPONDIENTE
                        for($j=0;$j<=3;$j++)
                        {
                            if($j==0)
                            {
                                $limit=2;
                            }
                            else
                            {
                                $limit=5;
                            }
                            $stmt2=$db->prepare("SELECT nombre_jg,numero_jg,posicion_jg,puntos_jg FROM JugadoresGratis WHERE grupo_jg=? ORDER BY RAND() LIMIT ?;");
                            $stmt2->bind_param("ii", $j,$limit);
                            $stmt2->bind_result($name_jg,$number_jg,$position_jg,$score_jg);
                            $stmt2->execute();
                            $stmt2->store_result();
                            while($stmt2->fetch())
                            {
                                $stmt3=$db->prepare("INSERT INTO Jugadores (nombre_ju,numero_ju,posicion_ju,puntos_ju,grupo_ju,Equipos_idEquipos) VALUES (?,?,?,?,?,?);");
                                $stmt3->bind_param("sisiii", $name_jg,$number_jg,$position_jg,$score_jg,$j,$sufijo_fantasma);
                                $stmt3->execute();
                            }
                        }
                        //CREO ESTADIO DE CADA EQUIPO
                        $name_stadium=$nombre." Stadium";
                        $stmt4=$db->prepare("INSERT INTO Estadios (nombre_es,capacidad_es,ingresos_es,Equipos_idEquipos) VALUES (?,5000,10,?);");
                        $stmt4->bind_param("si",$name_stadium,$sufijo_fantasma);
                        $stmt4->execute();
                        // CARGAR TITULARES Y SUPLENTES
                        $stmt4=$db->prepare("SELECT idJugadores FROM Jugadores WHERE Equipos_idEquipos=? ORDER BY RAND();");
                        $stmt4->bind_param("i", $sufijo_fantasma);
                        $stmt4->bind_result($id_player);
                        $stmt4->execute();
                        $stmt4->store_result();
                        $flag=1;
                        while($stmt4->fetch())
                        {
                            if($flag<=11)
                            {
                                $stmt5=$db->prepare("INSERT INTO Titulares (orden,Jugadores_idJugadores) VALUES (?,?);");
                                $stmt5->bind_param("ii",$flag,$id_player);
                                $stmt5->execute();
                            }
                            else
                            {
                                $stmt5=$db->prepare("INSERT INTO Suplentes (Jugadores_idJugadores) VALUES (?);");
                                $stmt5->bind_param("i",$id_player);
                                $stmt5->execute();
                            }
                            $flag++;
                        }
                        // CALCULAR SCORING DEL EQUIPO
                        $stmt5=$db->prepare("UPDATE Equipos SET score=? WHERE idEquipos=?;");
                        $stmt5->bind_param("ii", $f->GetTeamScore($sufijo_fantasma,1),$sufijo_fantasma);
                        $stmt5->execute();
                    }
                    // ARMAR FECHAS Y PARTIDOS
                    $rounds=$f->BuildFixture($teams);
                    for($i=0;$i<count($rounds);$i++)
                    {
                        $round_name="Round ".($i+1);
                        $stmt5=$db->prepare("INSERT INTO Fechas (nombre_fecha,terminada,Ligas_idLigas) VALUES (?,0,?);");
                        $stmt5->bind_param("si", $round_name,$id_league);
                        $stmt5->execute();
                        $stmt5->store_result();
                        $id_round=$stmt5->insert_id;
                        // CARGAR PARTIDOS
                        for($j=0;$j<count($rounds[$i]);$j++)
                        {
                            $stmt6=$db->prepare("INSERT INTO Partidos (local,visitante,Fechas_idFechas) VALUES (?,?,?);");
                            $stmt6->bind_param("iii", $rounds[$i][$j][0],$rounds[$i][$j][1],$id_round);
                            $stmt6->execute();
                        }
                    }
                    // CARGAR POSICIONES
                    for($i=0;$i<count($teams);$i++)
                    {
                        $stmt6=$db->prepare("INSERT INTO Posiciones (puntos,partidos_jugados,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra,Ligas_idLigas,Equipos_idEquipos) VALUES (0,0,0,0,0,0,0,?,?);");
                        $stmt6->bind_param("ii", $id_league,$teams[$i]);
                        $stmt6->execute();
                    }
                    $stmt6->close();
                    $stmt5->close();
                    $stmt4->close();
                    $stmt3->close();
                    $stmt2->close();
                    $stmt->close();
                    $db->close();
                    session_start();
                    $_SESSION['user_fmo']=$user;
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Welcome to Goal Manager Online, ',$user,' you will be redirected to your team right now!
                              </div><script>setTimeout(function(){$(location).attr("href", "index.php");},1200);</script>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    This region is not avaliable, try with another one.
                              </div>';
                }
            }
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        The user or email already exist, try with another one.
                  </div>';
        }
    }
    public function ChangePassword($user,$old_pass,$new_pass)
    {
        $old_pass=hash('sha512',mb_strtolower($old_pass,'UTF-8'));
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? AND clave=?;");
        $stmt->bind_param("ss", $user,$old_pass);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $new_pass=hash('sha512',mb_strtolower($new_pass,'UTF-8'));
            $stmt=$db->prepare("UPDATE Equipos SET clave=? WHERE usuario=? AND clave=?;");
            $stmt->bind_param("sss", $new_pass,$user,$old_pass);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The password has been changed.
                      </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The current password is wrong, please try again.
                      </div>';
        }
    }
    public function ShowWithdrawalMethods($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT email_paypal,email_neteller,email_mercadopago FROM Equipos WHERE usuario=? LIMIT 1");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($email_paypal,$email_neteller,$email_mercadopago);
        $stmt->execute();
        $stmt->fetch();
        if(empty($email_paypal)){ $email_paypal="-"; }
        if(empty($email_neteller)){ $email_neteller="-"; }
        if(empty($email_mercadopago)){ $email_mercadopago="-";}
        echo '<tr>
                   <td class="email1">',$email_paypal,'</td>
                   <td><img src="libs/images/paypal.png" class="img-responsive center-block"></td>
                   <td><button class="btn btn-default btn-xs edit" value="1"><span class="glyphicon glyphicon-pencil"></span></button></td>
             </tr>
             <tr>
                   <td class="email3">',$email_mercadopago,'</td>
                   <td><img src="libs/images/mercadopago.png" class="img-responsive center-block"></td>
                   <td><button class="btn btn-default btn-xs edit" value="3"><span class="glyphicon glyphicon-pencil"></span></button></td>
             </tr>
             <tr>
             <td class="email2">',$email_neteller,'</td>
                   <td><img src="libs/images/neteller.png" class="img-responsive center-block"></td>
                   <td><button class="btn btn-default btn-xs edit" value="2"><span class="glyphicon glyphicon-pencil"></span></button></td>
             </tr>';
        $stmt->close();
        $db->close();
    }
    public function EditWithdrawalMethod($user,$id,$email)
    {
        $email=mb_strtolower($email,'UTF-8');
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        switch($id)
        {
            case 1:
                $stmt=$db->prepare("UPDATE Equipos SET email_paypal=? WHERE usuario=?");
                $stmt->bind_param("ss", $email,$user);
                $stmt->execute();
                break;
            case 2:
                $stmt=$db->prepare("UPDATE Equipos SET email_neteller=? WHERE usuario=?");
                $stmt->bind_param("ss", $email,$user);
                $stmt->execute();
                break;
            case 3:
                $stmt=$db->prepare("UPDATE Equipos SET email_mercadopago=? WHERE usuario=?");
                $stmt->bind_param("ss", $email,$user);
                $stmt->execute();
                break;
            default:
                break;
        }
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                The withdrawal method has been updated, refreshing...
                          </div><script>$(location).attr("href", "withdrawal_methods");</script>';
    }
	
	public function BuyPlayers($id_pack,$method,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT * FROM PacksJugador WHERE idPacksJugador=?;");
        $stmt->bind_param("i", $id_pack);
        $stmt->bind_result($id_pack,$players,$gold,$price,$imagen);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $title="Pack ".$players." players";
            $stmt=$db->prepare("SELECT idEquipos,oro FROM Equipos WHERE usuario=? LIMIT 1;");
            $stmt->bind_param("s", $user);
            $stmt->bind_result($id_team,$oro);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
			if($method == 3){
				$oro = $oro - $gold;
				if($oro < 0){
					echo '<div class="modal fade" id="announce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel">',$title,'</h4>
										</div>
										<div class="modal-body">
											You dont have enough gold for this purchase.
										</div>
									</div>
								</div>
							  </div><script>$("#announce").modal("show");</script>';
					exit;
				}else{
					$stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt->bind_param("is", $oro,$id_team);
                    $stmt->execute();
					$date = date("d/m/Y");
					$type = 'st';
					$stmt=$db->prepare("INSERT INTO Compras (monto,fecha,pagado,Equipos_idEquipos,PacksJugador_idPacksJugador,Pago) VALUES (?,?,1,?,?,?);");
					$stmt->bind_param("isiis", $price,$date,$id_team,$id_pack,$type);
					$stmt->execute();
					$stmt->store_result();
					$id_order=$stmt->insert_id;
					$stmt->close();
					$db->close();
					echo '<div class="modal fade" id="announce" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel">',$title,'</h4>
										</div>
										<div class="modal-body">
											You successfully bought the pack.
										</div>
									</div>
								</div>
							  </div><script>$("#announce").modal("show");</script>';
					exit;
				}
			}
			$date = date("d/m/Y");
            $stmt=$db->prepare("INSERT INTO Compras (monto,fecha,pagado,Equipos_idEquipos,PacksJugador_idPacksJugador) VALUES (?,?,0,?,?);");
            $stmt->bind_param("isii", $price,$date,$id_team,$id_pack);
			$stmt->execute();
            $stmt->store_result();
            $id_order=$stmt->insert_id;
            $stmt->close();
            $db->close();
            switch($method)
            {
                case 1:
                    //paypal
                    echo '<div class="modal fade" id="paypal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">',$title,'</h4>
                                    </div>
                                    <div class="modal-body">
                                        Paying by  paypal, the accreditation of St is immediate.
                                    </div>
                                    <div class="modal-footer">
                                        <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                            <input type="hidden" name="cmd" value="_xclick">
                                            <input type="hidden" name="business" value="mvicino@ar.ibm.com">
                                            <input type="hidden" name="custom" value="',$id_order,'">
                                            <input type="hidden" name="currency_code" value="USD">
                                            <input type="hidden" name="item_name" value="',$title,'">
                                            <input type="hidden" name="amount" value="',$price,'">
                                            <button type="submit" class="btn btn-primary">Pay with PayPal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                          </div><script>$("#paypal").modal("show");</script>';
                break;
                case 2:
                    //mercadopago
                    require_once("../../mercadopago/mercadopago.php");
                    $mp = new MP('383015242804717', 'yn6kpl34FN5tsai2JDdNCW892KGDBpxG');
                    $preference_data = array(
                        "items" => array(
                            array(
                                "id" => $id_pack,
                                "title" => $title,
                                "currency_id" => "USD",
                                "category_id" => "Pack St",
                                "quantity" => 1,
                                "unit_price" => $price
                            )
                        ),
                        "back_urls" => array(
                            "success" => "https://goalmanageronline.com/asd/",
                            "failure" => "https://goalmanageronline.com/asd/",
                            "pending" => "https://goalmanageronline.com/asd/"
                        ),
                        "notification_url" => "https://goalmanageronline.com/gmo_ipn/",
                        "external_reference" => $id_order,
                        "expires" => false
                    );
                    $preference = $mp->create_preference($preference_data);
                    echo '<div class="modal fade" id="mercadopago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">',$title,'</h4>
                                    </div>
                                    <div class="modal-body">
                                        Pagando por mercado pago, dependiendo la opcion que elija, los St seran acreditados apenas el pago sea aceptado.
                                    </div>
                                    <div class="modal-footer">
                                        <a href="',$preference["response"]["init_point"],'" class="btn btn-primary">Pagar</a>
                                    </div>
                                </div>
                            </div>
                          </div><script>$("#mercadopago").modal("show");</script>';
                break;
				case 3:
				
				break;
			}
			
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            We could not verify your data, please try again.
                   </div>';
        }
    }
	
    public function BuyCoins($id_pack,$method,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT * FROM PacksOro WHERE idPacksOro=?;");
        $stmt->bind_param("i", $id_pack);
        $stmt->bind_result($id_pack,$gold,$price);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $title="Pack ".$gold." St";
            $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
            $stmt->bind_param("s", $user);
            $stmt->bind_result($id_team);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            $stmt=$db->prepare("INSERT INTO Compras (monto,fecha,pagado,Equipos_idEquipos,PacksOro_idPacksOro) VALUES (?,?,0,?,?);");
            $date = date("d/m/Y");
			$stmt->bind_param("dsii", $price,$date,$id_team,$id_pack);
            $stmt->execute();
            $stmt->store_result();
            $id_order=$stmt->insert_id;
            $stmt->close();
            $db->close();
            switch($method)
            {
                case 1:
                    //paypal
                    echo '<div class="modal fade" id="paypal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">',$title,'</h4>
                                    </div>
                                    <div class="modal-body">
                                        Paying by  paypal, the accreditation of St is immediate.
                                    </div>
                                    <div class="modal-footer">
                                        <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                            <input type="hidden" name="cmd" value="_xclick">
                                            <input type="hidden" name="business" value="mvicino@ar.ibm.com">
                                            <input type="hidden" name="custom" value="',$id_order,'">
                                            <input type="hidden" name="currency_code" value="USD">
                                            <input type="hidden" name="item_name" value="',$title,'">
                                            <input type="hidden" name="amount" value="',$price,'">
                                            <button type="submit" class="btn btn-primary">Pay with PayPal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                          </div><script>$("#paypal").modal("show");</script>';
                break;
                case 2:
                    //mercadopago
                    require_once("../../mercadopago/mercadopago.php");
                    $mp = new MP('383015242804717', 'yn6kpl34FN5tsai2JDdNCW892KGDBpxG');
                    $preference_data = array(
                        "items" => array(
                            array(
                                "id" => $id_pack,
                                "title" => $title,
                                "currency_id" => "USD",
                                "category_id" => "Pack St",
                                "quantity" => 1,
                                "unit_price" => $price
                            )
                        ),
                        "back_urls" => array(
                            "success" => "https://goalmanageronline.com/asd/",
                            "failure" => "https://goalmanageronline.com/asd/",
                            "pending" => "https://goalmanageronline.com/asd/"
                        ),
                        "notification_url" => "https://goalmanageronline.com/gmo_ipn/",
                        "external_reference" => $id_order,
                        "expires" => false
                    );
                    $preference = $mp->create_preference($preference_data);
                    echo '<div class="modal fade" id="mercadopago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">',$title,'</h4>
                                    </div>
                                    <div class="modal-body">
                                        Pagando por mercado pago, dependiendo la opcion que elija, los St seran acreditados apenas el pago sea aceptado.
                                    </div>
                                    <div class="modal-footer">
                                        <a href="',$preference["response"]["init_point"],'" class="btn btn-primary">Pagar</a>
                                    </div>
                                </div>
                            </div>
                          </div><script>$("#mercadopago").modal("show");</script>';
                break;
            }
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            We could not verify your data, please try again.
                   </div>';
        }
    }
    public function WithdrawalMoney($email,$coins,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT email_mercadopago,email_paypal,email_neteller,oro FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($emailm,$emailp,$emailn,$balance);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if($balance>=$coins)
        {
            if($email==1)
            {
                if(!empty($emailp))
                {
                    // retiro con paypal
                    //debito coins
                    $stmt=$db->prepare("UPDATE Equipos SET oro=oro-? WHERE usuario=?;");
                    $stmt->bind_param("is", $coins,$user);
                    $stmt->execute();
                    //calculo precio
                    $equals=($coins/7000);
                    $total=round($equals-($equals*0.10),2);
                    //cargo a la lista
                    $stmt=$db->prepare("INSERT INTO Retiros (usuario,monto,metodo,email,fecha) VALUES (?,?,'Paypal',?,now());");
                    $stmt->bind_param("sds", $user,$total,$emailp);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Congratulations, your request is going to be checked as soon as possible and processed.
                          </div>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                We could not verify your data, please try again.
                          </div>';
                }
            }
            if($email==2)
            {
                if(!empty($emailm))
                {
                    // retiro con mercadopago
                    //debito coins
                    $stmt=$db->prepare("UPDATE Equipos SET oro=oro-? WHERE usuario=?;");
                    $stmt->bind_param("is", $coins,$user);
                    $stmt->execute();
                    //calculo precio
                    $equals=($coins/7000);
                    $total=round($equals-($equals*0.10),2);
                    //cargo a la lista
                    $stmt=$db->prepare("INSERT INTO Retiros (usuario,monto,metodo,email,fecha) VALUES (?,?,'Mercadoapago',?,now());");
                    $stmt->bind_param("sds", $user,$total,$emailm);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Congratulations, your request is going to be checked as soon as possible and processed.
                          </div>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                We could not verify your data, please try again.
                          </div>';
                }
            }
            if($email==3)
            {
                if(!empty($emailn))
                {
                    // retiro con mercadopago
                    //debito coins
                    $stmt=$db->prepare("UPDATE Equipos SET oro=oro-? WHERE usuario=?;");
                    $stmt->bind_param("is", $coins,$user);
                    $stmt->execute();
                    //calculo precio
                    $equals=($coins/7000);
                    $total=round($equals-($equals*0.10),2);
                    //cargo a la lista
                    $stmt=$db->prepare("INSERT INTO Retiros (usuario,monto,metodo,email,fecha) VALUES (?,?,'Neteller',?,now());");
                    $stmt->bind_param("sds", $user,$total,$emailn);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Congratulations, your request is going to be checked as soon as possible and processed.
                          </div>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                We could not verify your data, please try again.
                          </div>';
                }
            }
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                You do not have that amount of St to withdrawal, please try again.
                          </div>';
        }
    }
	public function GetUserIdByLogin($login){
		require_once("../../models/class.Connection.php");
		$db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $login);
        $stmt->bind_result($id_team);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
		if($stmt->num_rows>0)
        {
			return $id_team;
		}
		return 0;
	}
	
    public function ResetPassword($email)
    {
        $email=mb_strtolower($email,'UTF-8');
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos,usuario FROM Equipos WHERE email=? LIMIT 1;");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($id_team,$user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if($stmt->num_rows>0)
        {
            $new_pass = rand(10000,99999);
            $stmt=$db->prepare("UPDATE Equipos SET clave=? WHERE idEquipos=?;");
            $stmt->bind_param("si", hash('sha512',$new_pass),$id_team);
            $stmt->execute();
            $stmt->close();
            $db->close();
            require_once("../../mailer/PHPMailerAutoload.php");
            $mail=new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'mail.goalmanageronline.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@goalmanageronline.com';
            $mail->Password = 'Songokuh14';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('no-reply@goalmanageronline.com', 'Goal Manager');
            $mail->addAddress($email, $user);
            $mail->isHTML(true);
            $mail->Subject = 'Goal Manager Online New Password ';
            $mail->Body = '<body style="background-color: #185d48;color:#fff;padding:2%;"><img src="https://goalmanageronline.com/libs/images/logo.png"><br><br><div style="padding:3% 2%;">Hello '.ucfirst($user).',<br>Your new password is '.$new_pass.'<br><br>Regards,<br>Goal Manager Online Team.</div></body>';
            if(!$mail->send())
            {
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            There has been an error, please contact info@goalmanageronline.com
                    </div>';
            }
            else
            {
                echo '<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Your password has been reseted, please check in your e-mail account, remind that this e-mail could be in spam folder.
              </div>';
            }
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    The e-mail you have entered does not exist, please try again.
              </div>';
        }
    }
}
