<?php
class Team
{
    protected $formations;
    public function __construct()
    {
        $this->formations=array(
            1=> "4-4-2",
            2=> "4-3-2-1",
            3=> "4-1-2-1-2",
            4=> "4-3-3",
            5=> "4-2-3-1",
            6=> "4-2-4",
            7=> "5-4-1",
            8=> "3-4-3",
            9=> "5-3-2",
            10=> "2-3-5",
        );
    }
    public function ChangeTeamName($user,$name)
    {
        $name=ucwords(mb_strtolower($name,'UTF-8'));
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE nombre=?;");
        $stmt->bind_param("s",$name);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            $stadium_name=$name." Stadium";
            $stmt=$db->prepare("UPDATE Equipos INNER JOIN Estadios ON Equipos.idEquipos=Estadios.Equipos_idEquipos SET Equipos.nombre=?,Estadios.nombre_es=? WHERE Equipos.usuario=?;");
            $stmt->bind_param("sss", $name,$stadium_name,$user);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                The team name has been edited, refreshing...
                          </div><script>$(location).attr("href", "team_name");</script>';           
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                That team name already exist or is your current team name, please try with another one.
                          </div>';
        }    
    }
    public function ChangeTeamShield($user,$shield,$type)
    {
        $shield=file_get_contents($shield['tmp_name']);
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("UPDATE Equipos SET logo_eq=?,tipo_logo_eq=? WHERE usuario=?;");
        $stmt->bind_param("sss", $shield,$type,$user);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    The shield has been updated.
             </div><script>$(location).attr("href", "team_name");</script>';
    }
    public function ChangeTeamShirt($user,$id)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT camiseta,tipo_ca FROM Camisetas WHERE idCamisetas=? LIMIT 1;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($shirt,$type);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt=$db->prepare("UPDATE Equipos SET camiseta_eq=?,tipo_camiseta_eq=? WHERE usuario=?;");
            $stmt->bind_param("sss", $shirt,$type,$user);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Your team shirt has been updated.
             </div>';
        }   
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        There was a problem, please try again or contact to the support.
             </div>';
        }    
    }    
    public function DrawField($user)
    {
        require_once("../../models/class.Connection.php");
        require_once("../../models/class.Fn.php");
        $f=new Fn();
        $db = new Connection();
        $stmt=$db->prepare("SELECT formacion,idEquipos,camiseta_eq,tipo_camiseta_eq FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_formation,$id_team,$shirt,$type);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->prepare("SELECT idJugadores,posicion_ju,puntos_ju,orden,numero_ju,nombre_ju FROM Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE Equipos_idEquipos=? ORDER BY orden ASC;");
        $stmt->bind_param("i", $id_team);
        $stmt->bind_result($idJugador,$player_position,$player_score,$order,$player_number,$player_name);
        $stmt->execute();
        if(empty($type))
        {
            $shirt='<img src="libs/images/black_shirt.png" class="shirt_image">';
        }
        else
        {
            $shirt='<img src="data:image/'.$type.';base64,'.base64_encode($shirt).'" class="shirt_image">';
        }    
        $team=array();
        while($stmt->fetch())
        {
            $team[$order]=array(
				"id" => $idJugador,
                "pos"=>$player_position,
                "score"=>$player_score,
                "number"=>$player_number,
                "name"=>$player_name			
            );
        } 
        $stmt->close();
        $db->close();
        
        switch ($id_formation) 
        {
            case 1:
                // 4-4-2
                echo '<div class="formation_442">
                        <div class="row section1">
                            <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                     ',$f->GetPlayerLoses("CF", $team[10]["pos"]),'
						         <div class="number" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
								 ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[10]["name"],'</p>
                           <p class="player_position nom-nop text-center">CF',$f->GetPlayerScoreOnField("CF", $team[10]["pos"], $team[10]["score"]),$f->GetPlayerLevelFormated($team[10]["score"]),'</p>
											</div>
                                    </div>
                                    <div class="col-xs-6 slot">
                               <div class="player player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">
                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'
											</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[11]["name"],'</p>
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
										</div>
                                    </div>
								</div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                                     <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">
                                            ',$f->GetPlayerLoses("SLM", $team[8]["pos"]),'
                                            <div class="number" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[8]["name"],'</p>
                                            <p class="player_position text-center">SLM',$f->GetPlayerScoreOnField("SLM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                              <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("SRM", $team[9]["pos"]),'
                                            <div class="number" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[9]["name"],'</p>
                                            <p class="player_position text-center">SRM',$f->GetPlayerScoreOnField("SRM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[6]["name"],'</p>
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[7]["name"],'</p>
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[4]["name"],'</p>
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                  <div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">
                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[5]["name"],'</p>
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[2]["name"],'</p>
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[3]["name"],'</p>
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                <div class="col-xs-12 slot">
		 	           <div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">

                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
												<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">',$team[1]["name"],'</p>
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 2:
                // 4-3-2-1
                echo '<div class="formation_4321">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
                        <div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            
                                        ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>
											
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                                     <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("OM", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>											
                                            <p class="player_position text-center">OM',$f->GetPlayerScoreOnField("OM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("OM", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>											
                                            <p class="player_position nom-nop text-center">OM',$f->GetPlayerScoreOnField("OM", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-4 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                             <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>											
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>											
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>											
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 3:
                // 4-1-2-1-2
                echo '<div class="formation_41212">
                                <div class="row section1">
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("CF", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>											
                                            <p class="player_position nom-nop text-center">CF',$f->GetPlayerScoreOnField("CF", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>											
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-12 slot">
                                     <div class="player center-block player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("OM", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>											
                                            <p class="player_position text-center">OM',$f->GetPlayerScoreOnField("OM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-4 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("SLM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>											
                                            <p class="player_position text-center">SLM',$f->GetPlayerScoreOnField("SLM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("DM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>											
                                            <p class="player_position text-center">DM',$f->GetPlayerScoreOnField("DM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("SRM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>											
                                            <p class="player_position text-center">SRM',$f->GetPlayerScoreOnField("SRM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>											
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>											
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>											
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   '; 
            break;
            case 4:
                // 4-3-3
                echo '<div class="formation_433">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>											
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                                 <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("LW", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>											
                                            <p class="player_position text-center">LW',$f->GetPlayerScoreOnField("LW", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("RW", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>											
                                            <p class="player_position nom-nop text-center">RW',$f->GetPlayerScoreOnField("RW", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-4 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("CM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>											
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>											
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>											
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>											
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>											
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   '; 
            break;
            case 5:
                // 4-2-3-1
                echo '<div class="formation_4231">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>																
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-4 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("SLM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>																
                                            <p class="player_position text-center">SLM',$f->GetPlayerScoreOnField("SLM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                       <div class="player center-block player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("OM", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>																
                                            <p class="player_position text-center">OM',$f->GetPlayerScoreOnField("OM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("SRM", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>																
                                            <p class="player_position nom-nop text-center">SRM',$f->GetPlayerScoreOnField("SRM", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("DM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>																
                                            <p class="player_position text-center">DM',$f->GetPlayerScoreOnField("DM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("DM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>																
                                            <p class="player_position text-center">DM',$f->GetPlayerScoreOnField("DM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>																
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>																
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>																
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>																
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>																
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 6:
                // 4-2-4
                echo '<div class="formation_424">
                                <div class="row section1">
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("CF", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>																
                                            <p class="player_position nom-nop text-center">CF',$f->GetPlayerScoreOnField("CF", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>																
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("LW", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>																
                                            <p class="player_position text-center">LW',$f->GetPlayerScoreOnField("LW", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                               <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("RW", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>																
                                            <p class="player_position text-center">RW',$f->GetPlayerScoreOnField("RW", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>																
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>																
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("FLB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>																
                                            <p class="player_position text-center">FLB',$f->GetPlayerScoreOnField("FLB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("FRB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>																
                                            <p class="player_position text-center">FRB',$f->GetPlayerScoreOnField("FRB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>																
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>																
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>																
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 7:
                // 5-4-1
                echo '<div class="formation_541">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>																
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                                     <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("SLM", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>																
                                            <p class="player_position text-center">SLM',$f->GetPlayerScoreOnField("SLM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("SRM", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>																
                                            <p class="player_position nom-nop text-center">SRM',$f->GetPlayerScoreOnField("SRM", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("CM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("WLB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>													
                                            <p class="player_position text-center">WLB',$f->GetPlayerScoreOnField("WLB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("WRB", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>													
                                            <p class="player_position text-center">WRB',$f->GetPlayerScoreOnField("WRB", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-4 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>													
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 8:
                // 3-4-3
                echo '<div class="formation_343">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>													
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                             <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                          ',$f->GetPlayerLoses("LW", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>													
                                            <p class="player_position text-center">LW',$f->GetPlayerScoreOnField("LW", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("RW", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>													
                                            <p class="player_position nom-nop text-center">RW',$f->GetPlayerScoreOnField("RW", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("SLM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>													
                                            <p class="player_position text-center">SLM',$f->GetPlayerScoreOnField("SLM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("SRM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>													
                                            <p class="player_position text-center">SRM',$f->GetPlayerScoreOnField("SRM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("CM", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-4 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>													
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   '; 
            break;
            case 9:
                // 5-3-2
                echo '<div class="formation_532">
                                <div class="row section1">
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("CF", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>													
                                            <p class="player_position nom-nop text-center">CF',$f->GetPlayerScoreOnField("CF", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>													
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("CM", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                              <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-6 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("WLB", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>													
                                            <p class="player_position text-center">WLB',$f->GetPlayerScoreOnField("WLB", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("WRB", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>													
                                            <p class="player_position text-center">WRB',$f->GetPlayerScoreOnField("WRB", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-4 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player center-block player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("SW", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>													
                                            <p class="player_position text-center">SW',$f->GetPlayerScoreOnField("SW", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>													
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            case 10:
                // 2-3-5
                echo '<div class="formation_235">
                                <div class="row section1">
                                    <div class="col-xs-12 slot">
<div class="player center-block player11 dragAndDrop" situation="titular" id="'.$team[11]["id"].'">                                            ',$f->GetPlayerLoses("CF", $team[11]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[11]["name"],' [',$team[11]["pos"],']','">',$team[11]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[11]["name"],'
								</p>													
                                            <p class="player_position text-center">CF',$f->GetPlayerScoreOnField("CF", $team[11]["pos"], $team[11]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section2">
                                    <div class="col-xs-6 slot">
                                <div class="player player9 dragAndDrop" situation="titular" id="'.$team[9]["id"].'">
                                            ',$f->GetPlayerLoses("LW", $team[9]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[9]["name"],' [',$team[9]["pos"],']','">',$team[9]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[9]["name"],'
								</p>													
                                            <p class="player_position text-center">LW',$f->GetPlayerScoreOnField("LW", $team[9]["pos"], $team[9]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                             <div class="player player10 dragAndDrop" situation="titular" id="'.$team[10]["id"].'">
                                            ',$f->GetPlayerLoses("RW", $team[10]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[10]["name"],' [',$team[10]["pos"],']','">',$team[10]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[10]["name"],'
								</p>													
                                            <p class="player_position nom-nop text-center">RW',$f->GetPlayerScoreOnField("RW", $team[10]["pos"], $team[10]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section3">
                                    <div class="col-xs-6 slot">
                                     <div class="player center-block player7 dragAndDrop" situation="titular" id="'.$team[7]["id"].'">
                                            ',$f->GetPlayerLoses("IF", $team[7]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[7]["name"],' [',$team[7]["pos"],']','">',$team[7]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[7]["name"],'
								</p>													
                                            <p class="player_position text-center">IF',$f->GetPlayerScoreOnField("IF", $team[7]["pos"], $team[7]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
 <div class="player player8 dragAndDrop" situation="titular" id="'.$team[8]["id"].'">                                            ',$f->GetPlayerLoses("IF", $team[8]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[8]["name"],' [',$team[8]["pos"],']','">',$team[8]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[8]["name"],'
								</p>													
                                            <p class="player_position text-center">IF',$f->GetPlayerScoreOnField("IF", $team[8]["pos"], $team[8]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section4">
                                    <div class="col-xs-4 slot">
                                      <div class="player player4 dragAndDrop" situation="titular" id="'.$team[4]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[4]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[4]["name"],' [',$team[4]["pos"],']','">',$team[4]["number"],'</div> 
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[4]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[4]["pos"], $team[4]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
<div class="player player5 dragAndDrop" situation="titular" id="'.$team[5]["id"].'">                                            ',$f->GetPlayerLoses("CM", $team[5]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[5]["name"],' [',$team[5]["pos"],']','">',$team[5]["number"],'</div>    
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[5]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[5]["pos"], $team[5]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 slot">
                                     <div class="player player6 dragAndDrop" situation="titular" id="'.$team[6]["id"].'">
                                            ',$f->GetPlayerLoses("CM", $team[6]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[6]["name"],' [',$team[6]["pos"],']','">',$team[6]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[6]["name"],'
								</p>													
                                            <p class="player_position text-center">CM',$f->GetPlayerScoreOnField("CM", $team[6]["pos"], $team[6]["score"]),'</p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                                <div class="row section5">
                                    <div class="col-xs-6 slot">
                                     <div class="player player2 dragAndDrop" situation="titular" id="'.$team[2]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[2]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[2]["name"],' [',$team[2]["pos"],']','">',$team[2]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[2]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[2]["pos"], $team[2]["score"]),'</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 slot">
                                     <div class="player player3 dragAndDrop" situation="titular" id="'.$team[3]["id"].'">
                                            ',$f->GetPlayerLoses("CB", $team[3]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[3]["name"],' [',$team[3]["pos"],']','" >',$team[3]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[3]["name"],'
								</p>													
                                            <p class="player_position text-center">CB',$f->GetPlayerScoreOnField("CB", $team[3]["pos"], $team[3]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row section6">
                                    <div class="col-xs-12 slot">
<div class="player center-block player1 dragAndDrop" situation="titular" id="'.$team[1]["id"].'">                                            ',$f->GetPlayerLoses("GK", $team[1]["pos"]),'
                                            <div class="number" data-toggle="tooltip" data-placement="top" title="',$team[1]["name"],' [',$team[1]["pos"],']','">',$team[1]["number"],'</div>     
                                            ',$shirt,'
								<p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
								',$team[1]["name"],'
								</p>													
                                            <p class="player_position text-center">GK',$f->GetPlayerScoreOnField("GK", $team[1]["pos"], $team[1]["score"]),'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   ';
            break;
            default:
                break;
        }
    }   
    public function UpdateTitularsSelect($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,puntos_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE usuario=? ORDER BY orden DESC;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id,$name,$position,$score,$number);
        $stmt->execute();
        $stmt->store_result();
        echo '<option value="0" hidden>Select the player you would like to change...</option>';
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">',$number,' ',$name,' [',$position,'] [',$score,']</option>';
        }
        $stmt->close();
        $db->close();
    } 
    public function UpdateOtherPlayersSelect($user,$id_titular)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND idJugadores=?;");
        $stmt->bind_param("si", $user,$id_titular);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,puntos_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE usuario=? AND idJugadores!=?;");
            $stmt->bind_param("si", $user,$id_titular);
            $stmt->bind_result($id,$name,$position,$score,$number);
            $stmt->execute();
            $stmt->store_result();
            echo '<option value="0" hidden>Change that player for...</option>';
            echo '<optgroup label="Change for titular">';
            while($stmt->fetch())
            {
                echo '<option value="',$id,'">',$number,' ',$name,' [',$position,'] [',$score,']</option>';
            }
            echo '</optgroup>';
            echo '<optgroup label="Change for substitute">';
            $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,puntos_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND idJugadores!=? AND subasta=0;");
            $stmt->bind_param("si", $user,$id_titular);
            $stmt->bind_result($id,$name,$position,$score,$number);
            $stmt->execute();
            $stmt->store_result();
            while($stmt->fetch())
            {
                echo '<option value="',$id,'">',$number,' ',$name,' [',$position,'] [',$score,']</option>';
            }
            echo '</optgroup>';
            $stmt->close();
            $db->close();
        }
        else
        {
            $stmt->close();
            $db->close();
        }    
    }
	
	public function PlayerSellValue($v){
		return $v*7;
	}
	
	public function AutomaticSellPlayer($user, $id_ts){
		require_once("../../models/class.Connection.php");
		$db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos,oro,idJugadores,puntos_ju,nombre_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND idJugadores=?;");
		$stmt->bind_param("si",$user,$id_ts);
        $stmt->bind_result($team,$gold,$id,$valor,$name);
        $stmt->execute();
        $stmt->store_result();
		if($stmt->num_rows>0){
			$stmt->fetch();
			$price = $this->PlayerSellValue($valor);
			
			$gold = $gold+$price;
			$stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
			$stmt->bind_param("ii", $gold, $team);
			$stmt->execute();
			$stmt2=$db->prepare("DELETE FROM Suplentes WHERE Jugadores_idJugadores=?;");
			$stmt2->bind_param("i", $id);
			$stmt2->execute();
			$stmt2=$db->prepare("DELETE FROM Jugadores WHERE idJugadores=?;");
			$stmt2->bind_param("i", $id);
			$stmt2->execute();
			
			require_once("../../models/class.Fn.php");
			$f=new Fn();
			$meta = array($price . ' Coins', $name);
			$f->InsertHistoric($team, 3, $meta);
			
			 echo '<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						The player have been sold.
				  </div>';
		}else{
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Could not sell this player. Try again.
			  </div>';
		}
	}
	
	public function ConfirmSellPlayer($user,$id_ts){
		require_once("../../models/class.Connection.php");
		$db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos,formacion,nombre_ju,puntos_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND idJugadores=?;");
		$stmt->bind_param("si", $user,$id_ts);
        $stmt->bind_result($id_team,$formation,$nombre,$valor);
        $stmt->execute();
        $stmt->store_result();
		if($stmt->num_rows>0){
			$stmt->fetch();
			$price = $this->PlayerSellValue($valor);
			echo "¿Seguro que quieres vender a $nombre por $price?";
		}else{
			die('0');
		}
	}
	
    public function ChangePlayer($user,$id_tit,$id_new_tit)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos,formacion,orden FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE usuario=? AND idJugadores=?;");
        $stmt->bind_param("si", $user,$id_tit);
        $stmt->bind_result($id_team,$formation,$order_tit);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt->bind_param("si", $user,$id_new_tit);
            $stmt->bind_result($id_team,$formation,$order_new_tit); //obtengo id de equipo y formacion
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows>0)
            {
                $stmt->fetch();
                //ENTONCES LOS DOS SON TITULARES
                // $order_tit es el orden del viejo y $id_tit su id
                // $order_new_tit es el orden del viejo y $id_new_tit su id
                
                //INTERCAMBIO
                $stmt=$db->prepare("UPDATE Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores SET Jugadores_idJugadores=? WHERE Jugadores_idJugadores=? AND Equipos_idEquipos=? AND orden=?;");
                $stmt->bind_param("iiii", $id_new_tit,$id_tit,$id_team,$order_tit);
                $stmt->execute();
                $stmt=$db->prepare("UPDATE Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores SET Jugadores_idJugadores=? WHERE Jugadores_idJugadores=? AND Equipos_idEquipos=? AND orden=?;");
                $stmt->bind_param("iiii", $id_tit,$id_new_tit,$id_team,$order_new_tit);
                $stmt->execute();
                require_once('../../models/class.Fn.php');
                $f=new Fn();
                $stmt=$db->prepare("UPDATE Equipos SET score=? WHERE usuario=?");
                $stmt->bind_param("is", $f->GetTeamScore($id_team, $formation),$user);
                $stmt->execute();
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The players have been changed.
                      </div>';
            }    
            else
            {
                // tengo que ver si el jugador existe en el banco
                $stmt=$db->prepare("SELECT idJugadores FROM Jugadores INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE Equipos_idEquipos=? AND Jugadores_idJugadores=? AND subasta=0;");
                $stmt->bind_param("ii", $id_team,$id_new_tit);
                $stmt->bind_result($id_substitute);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows>0)
                {
                    $stmt->fetch();
                    //existe $id_substitute es el id del jugador
                    //pongo en el banco al jugador que se reemplazo
                    $stmt=$db->prepare("UPDATE Jugadores INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores SET Jugadores_idJugadores=? WHERE Jugadores_idJugadores=? AND Equipos_idEquipos=?;");
                    $stmt->bind_param("iii", $id_tit,$id_substitute,$id_team);
                    $stmt->execute();
                    $stmt=$db->prepare("UPDATE Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores SET Jugadores_idJugadores=? WHERE Jugadores_idJugadores=? AND Equipos_idEquipos=? AND orden=?;");
                    $stmt->bind_param("iiii", $id_substitute,$id_tit,$id_team,$order_tit);
                    $stmt->execute();
                    require_once('../../models/class.Fn.php');
                    $f=new Fn();
                    $stmt=$db->prepare("UPDATE Equipos SET score=? WHERE usuario=?");
                    $stmt->bind_param("is", $f->GetTeamScore($id_team, $formation),$user);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The players have been changed
                        </div>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, select 2 players to make a change.
                      </div>';
                }
            } 
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, select 2 players to make a change.
                      </div>';
            $stmt->close();
            $db->close();
        }    
    } 
    public function UpdateScore($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT score FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($score);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo number_format($score, 0, ",", ".");
    } 
    public function UpdateFormation($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT formacion,nombre_formacion FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_afor,$name_afor);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo '<option value="',$id_afor,'">',$name_afor,'</option>';
        foreach ($this->formations as $id => $name) 
        {
            if($id!=$id_afor)
            {
                echo '<option value="',$id,'">',$name,'</option>';
            }    
        }
    } 
    public function ChangeFormation($id_formation,$user)
    {
        require_once("../../models/class.Connection.php");
        require_once("../../models/class.Fn.php");
        $db=new Connection();
        $f=new Fn();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_team);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("UPDATE Equipos SET formacion=?,nombre_formacion=?,score=? WHERE idEquipos=?;");
        $stmt->bind_param("isii", $id_formation, $this->formations[$id_formation],$f->GetTeamScore($id_team, $id_formation),$id_team);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }        
    public function GetTeamSubstitutes($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,puntos_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND subasta=0;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id,$name,$position,$score,$number);
        $stmt->execute();
        $stmt->store_result();
        $shirt='<img src="libs/images/black_shirt.png" class="shirt_image">';
		echo '<div class="row">';
        while($stmt->fetch())
        {

 			echo '<div class="col-md-3 col-sm-3 col-xs-3" style="margin:0px 0px 100px 0px">
					  <div class="player dragAndDrop" situation="substitute" id="'.$id.'">
						  <div class="number" title="">',$number,'</div>    
						  ',$shirt,'
						  <p style="margin-top:-5px; font-size:12px" class="nom-nop text-center">
						  ',$name,'
						  </p>
						  <p class="player_position nom-nop text-center">
						  '.$position.'
						  <span style="color: green">['.$score.']</span>
						  </p>
					  </div>
                  </div>';
        }
		echo '</div>';
        $stmt->close();
        $db->close();
    } 
    public function GetTeamTitulars($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,puntos_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE usuario=? ORDER BY orden DESC;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id,$name,$position,$score,$number);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<li><span>',$number,'</span> ',$name,' <span>',$position,'</span> <span>',$score,'</span></li>';
        }
        $stmt->close();
        $db->close();
    } 
    public function BuyPremiumPlayer($id,$shirt,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadoresPagos,nombre_jp,posicion_jp,puntos_jp,precio_jp,grupo_jp,stock_jp FROM JugadoresPagos WHERE stock_jp>0 AND idJugadoresPagos=? LIMIT 1;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($id_player,$name,$position,$score,$price,$group,$stock);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt->prepare("SELECT idEquipos,oro FROM Equipos WHERE usuario=? LIMIT 1;");
            $stmt->bind_param("s", $user);
            $stmt->bind_result($id_team,$balance);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if($balance>=$price)
            {
                $stmt=$db->prepare("SELECT * FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND numero_ju=?;");
                $stmt->bind_param("si", $user,$shirt);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows==0)
                {
                    $stmt=$db->prepare("SELECT idEquipos FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE nombre_ju=? AND idEquipos=?;");
                    $stmt->bind_param("si", $name,$id_team);
                    $stmt->execute();
                    $stmt->store_result();
                    if($stmt->num_rows==0)
                    {
                        $balance=$balance-$price;
                        $stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                        $stmt->bind_param("ii", $balance,$id_team);
                        $stmt->execute();

                        $stmt=$db->prepare("UPDATE JugadoresPagos SET stock_jp=? WHERE idJugadoresPagos=?;");
                        $stock--;
                        $stmt->bind_param("ii", $stock,$id_player);
                        $stmt->execute();

                        $stmt=$db->prepare("INSERT INTO Jugadores (nombre_ju,numero_ju,posicion_ju,puntos_ju,precio_ju,grupo_ju,Equipos_idEquipos) VALUES (?,?,?,?,?,?,?);");
                        $stmt->bind_param("sisiiii", $name,$shirt,$position,$score,$price,$group,$id_team);
                        $stmt->execute();
                        $stmt->store_result();
                        $new_id=$stmt->insert_id;

                        $stmt=$db->prepare("INSERT INTO Suplentes (Jugadores_idJugadores) VALUES (?);");
                        $stmt->bind_param("i", $new_id);
                        $stmt->execute();
                        $stmt->close();
                        $db->close();
						
						require_once("../../models/class.Fn.php");
						$f=new Fn();
						$meta = array($price . ' Coins', $name);
						$f->InsertHistoric($id_team, 2, $meta);
						
                        echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Congratulations, you have bought ',$name,', he is with your substitutes.
                            </div>';
                    }
                    else
                    {
                        $stmt->close();
                        $db->close();
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Sorry, have already bought this player.
                            </div>';
                    }    
                } 
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The number you choosed already exist on your team, please try with another one or change the number of the player who has it.
                        </div>';
                }    
            }    
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Sorry, you do not have enough coins to buy this player.
                    </div>';
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
    public function BuyStadiumUpgrade($id,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT capacidad_me,precio_me,idMejorasEstadio FROM MejorasEstadio WHERE idMejorasEstadio=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($capacity_upgrade,$price,$id_upgrade);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt->prepare("SELECT capacidad_es,ingresos_es,idEstadios,oro,idEquipos FROM Equipos INNER JOIN Estadios ON Equipos.idEquipos=Estadios.Equipos_idEquipos WHERE usuario=?;");
            $stmt->bind_param("s", $user);
            $stmt->bind_result($capacity,$revenue,$id_stadium,$balance,$id_team);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if($price<=$balance)
            {
                $new_capacity=$capacity+$capacity_upgrade;
                if($new_capacity<=100000)
                {
                    $balance=$balance-$price;
                    $stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt->bind_param("ii", $balance,$id_team);
                    $stmt->execute();
                    $capacity=$capacity+$capacity_upgrade;
                    $revenue=$revenue+intval($capacity_upgrade*0.01);
                    $stmt=$db->prepare("UPDATE Estadios SET capacidad_es=?,ingresos_es=? WHERE idEstadios=?;");
                    $stmt->bind_param("iii", $capacity,$revenue,$id_stadium);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
					
					require_once("../../models/class.Fn.php");
					$f=new Fn();
					$meta = array($price . ' Coins', $capacity_upgrade . ' More seats in Stadium');
					$f->InsertHistoric($id_team, 4, $meta);
					
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Congratulations, now your stadium capacity is ',$capacity,'.
                     </div>';
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            The max capacity for a stadium is 100.000.
                     </div>';
                }
            }
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Sorry, you do not have enough coins to buy this upgrade.
                 </div>';
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

    public function MessageUpgrade($score)
    {
        require_once("../../models/class.Fn.php");
        $f=new Fn();
        $option = $f->GetPlayerLevel($score);
      switch($option)
      {
          case 'LEVEL 1':
              return "This player is Level 1 and cannot be trained more than 5000 sc";
              break;
          case 'LEVEL 2':
              return "This player is Level 2 and cannot be trained more than 15000 sc";
              break;
          case 'LEVEL 3':
              return "This player is Level 3 and cannot be trained more than 25000 sc";
              break;
      }
    }
	
	public function CanUpgrade($score,$upgrade)
	{
		$can = false;
        require_once("../../models/class.Fn.php");
        $f=new Fn();
		$playerLevel = $f->GetPlayerLevel($score);
        $sumTotal = $score+$upgrade;
        ///FOR LEVEL 1
		if(($playerLevel=='LEVEL 1') && $sumTotal<=5000)
		{
			$can=true;
		}
        ///FOR LEVEL 2
        if(($playerLevel=='LEVEL 2') && $sumTotal<=15000)
		{
			$can=true;
		}
        ///FOR LEVEL 3
		if(($playerLevel=='LEVEL 3') && $sumTotal<=25000)
		{
			$can=true;
		}
        ///FOR LEVEL 4
        if($playerLevel=='LEVEL 4')
		{
			$can=true;
		}
		return $can;
		
	}
	
	public function GetSalary($user){
		require_once("../../models/class.Connection.php");
        $db=new Connection();
		$stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_teampl);
        $stmt->execute();
		$stmt->fetch();
		
		$dbpl=new Connection();
        // CUENTO CUANTOS TIENEN SCORE <= 500
		$stmtpl=$dbpl->prepare("SELECT COUNT(*) FROM Jugadores WHERE Equipos_idEquipos=? AND puntos_ju<=500;");
		$stmtpl->bind_param("i", $id_teampl);
		$stmtpl->bind_result($total_a);
		$stmtpl->execute();
		$stmtpl->store_result();
		$stmtpl->fetch();
		// CUENTO CUANTOS TIENEN SCORE >= 501 Y <=2500
		$stmtpl=$dbpl->prepare("SELECT COUNT(*) FROM Jugadores WHERE Equipos_idEquipos=? AND puntos_ju>=501 AND puntos_ju<=2500;");
		$stmtpl->bind_param("i", $id_teampl);
		$stmtpl->bind_result($total_b);
		$stmtpl->execute();
		$stmtpl->store_result();
		$stmtpl->fetch();
		// CUENTO CUANTOS TIENEN SCORE >=2501 Y <=4000
		$stmtpl=$dbpl->prepare("SELECT COUNT(*) FROM Jugadores WHERE Equipos_idEquipos=? AND puntos_ju>=2501 AND puntos_ju<=4000;");
		$stmtpl->bind_param("i", $id_teampl);
		$stmtpl->bind_result($total_c);
		$stmtpl->execute();
		$stmtpl->store_result();
		$stmtpl->fetch();
		// CUENTO CUANTOS TIENEN SCORE >=4001 Y <=9000
		$stmtpl=$dbpl->prepare("SELECT COUNT(*) FROM Jugadores WHERE Equipos_idEquipos=? AND puntos_ju>=4001 AND puntos_ju<=9000;");
		$stmtpl->bind_param("i", $id_teampl);
		$stmtpl->bind_result($total_d);
		$stmtpl->execute();
		$stmtpl->store_result();
		$stmtpl->fetch();
		// CUENTO CUANTOS TIENEN SCORE >=9001
		$stmtpl=$dbpl->prepare("SELECT COUNT(*) FROM Jugadores WHERE Equipos_idEquipos=? AND puntos_ju>=9001;");
		$stmtpl->bind_param("i", $id_teampl);
		$stmtpl->bind_result($total_e);
		$stmtpl->execute();
		$stmtpl->store_result();
		$stmtpl->fetch();
		// CALCULO EL TOTAL DE LOS SALARIOS
		$charge=(29*$total_a*8)+(29*$total_b*10)+(29*$total_c*40)+(29*$total_d*70)+(29*$total_e*110);
		$stmtpl->close();
		
		echo number_format($charge, 0, ",", ".");
	}
	
	public function BuyFullStamina($user){
		$price = 25000;
		$nstamina = 100;
		require_once("../../models/class.Connection.php");
        $db=new Connection();
		$stmt=$db->prepare("SELECT idEquipos, oro, formacion FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($team, $balance, $formation);
        $stmt->execute();
		$stmt->fetch();
		
		$stmt->prepare("SELECT idJugadores FROM Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE Equipos_idEquipos=? ORDER BY orden ASC;");
        $stmt->bind_param("i", $team);
        $stmt->bind_result($idJugador);
        $stmt->execute();
        $players=array();
        while($stmt->fetch())
        {
            $players[]= $idJugador;
        }
				
		if($price<=$balance)
		{
			foreach($players as $player){
				$db2=new Connection();
				$stmt2=$db2->prepare("UPDATE Jugadores SET cansancio_ju=? WHERE Equipos_idEquipos=? AND idJugadores=?;");
				$stmt2->bind_param("iii", $nstamina,$team,$player);
				$stmt2->execute();
			}
			$balance=$balance-$price;
			require_once("../../models/class.Fn.php");
			$f=new Fn();
			$score = $f->GetTeamScore($team, $formation);
			$db3=new Connection();
			$stmt3=$db3->prepare("UPDATE Equipos SET oro=?,score=? WHERE idEquipos=?;");
			$stmt3->bind_param("iii", $balance, $score, $team);
			$stmt3->execute();
			$stmt3->close();
			$db3->close();
			
			
			$meta = array($price . ' Coins', 'Regenerate stamina');
			$f->InsertHistoric($team, 4, $meta);
			
			echo '<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Congratulations, you refilled your players stamina.
				   </div>';
		}else{
			$stmt->close();
			$db->close();
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 You do not have enough coins to refill stamina.
			 </div>';
		}
	}
	
	public function BuyPlayerStamina($player,$user)
    {
		$price = 3000;
		$nstamina = 100;
		require_once("../../models/class.Connection.php");
        $db=new Connection();
		$stmt=$db->prepare("SELECT idEquipos, oro, formacion FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($team, $balance, $formation);
        $stmt->execute();
		$stmt->fetch();
		
		if($price<=$balance)
		{
			$db2=new Connection();
			$stmt2=$db2->prepare("UPDATE Jugadores SET cansancio_ju=? WHERE Equipos_idEquipos=? AND idJugadores=?;");
			$stmt2->bind_param("iii", $nstamina,$team,$player);
			$stmt2->execute();
			
			$balance=$balance-$price;
			require_once("../../models/class.Fn.php");
			$f=new Fn();
			$score = $f->GetTeamScore($team, $formation);
			$db3=new Connection();
			$stmt3=$db3->prepare("UPDATE Equipos SET oro=?,score=? WHERE idEquipos=?;");
			$stmt3->bind_param("iii", $balance, $score, $team);
			$stmt3->execute();
			$stmt3->close();
			$db3->close();
			
			$meta = array($price . ' Coins', 'Regenerate stamina');
			$f->InsertHistoric($team, 4, $meta);
			
			echo '<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Congratulations, you refilled your player stamina.
				   </div>';
		}else{
			$stmt->close();
			$db->close();
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 You do not have enough coins to refill stamina.
			 </div>';
		}
	}
	
	public function BuyPlayerUpgrade($id,$player,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT puntos_mju,precio_mju FROM MejorasJugador WHERE afecta_todos=0 AND idMejorasJugador=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($score,$price);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt=$db->prepare("SELECT idEquipos,idJugadores,oro,puntos_ju,nombre_ju,formacion FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND idJugadores=?;");
            $stmt->bind_param("si", $user,$player);
            $stmt->bind_result($id_team,$id_player,$balance,$player_score,$name,$formation);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows>0)
            {
                $stmt->fetch();
                //$canUpgrade = ($this->CanUpgrade($player_score,$score));
				if($this->CanUpgrade($player_score,$score))
				{
					if($price<=$balance)
					{
						$player_score=$player_score+$score;
						$stmt=$db->prepare("UPDATE Jugadores SET puntos_ju=? WHERE idJugadores=?;");
						$stmt->bind_param("ii", $player_score,$id_player);
						$stmt->execute();
						$balance=$balance-$price;
						require_once("../../models/class.Fn.php");
						$f=new Fn();
						$stmt=$db->prepare("UPDATE Equipos SET oro=?,score=? WHERE idEquipos=?;");
						$stmt->bind_param("iii", $balance,$f->GetTeamScore($id_team, $formation),$id_team);
						$stmt->execute();
						$stmt->close();
						$db->close();
						
						$meta = array($price . ' Coins', ' Plus ' . $score . ' to player: ' . $name);
						$f->InsertHistoric($id_team, 4, $meta);
						
						echo '<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									Congratulations, ',$name,' has increased his score to ',$player_score,'.
							   </div>';
					}    
					else
					{
						$stmt->close();
						$db->close();
						echo '<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								 You do not have enough coins to buy this improvement.
						 </div>';
					}
				}
				else
				{
                    $stmt->close();
                    $db->close();
						echo '<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								 '.$this->MessageUpgrade($player_score).'
						 </div>';					
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
	
    public function BuyTeamUpgrade($id,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT puntos_mju,precio_mju FROM MejorasJugador WHERE afecta_todos=1 AND idMejorasJugador=?;");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($score,$price);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt=$db->prepare("SELECT idEquipos,oro,formacion FROM Equipos WHERE usuario=?;");
            $stmt->bind_param("s", $user);
            $stmt->bind_result($id_team,$balance,$formation);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if($price<=$balance)
            {
                $stmt=$db->prepare("INSERT INTO Equipos_has_MejorasJugador (Equipos_idEquipos,MejorasJugador_idMejorasJugador) VALUES (?,?);");
                $stmt->bind_param("ii", $id_team,$id);
                $stmt->execute();
                $balance=$balance-$price;
                require_once("../../models/class.Fn.php");
                $f=new Fn();
                $new_score=$f->GetTeamScore($id_team, $formation);
                $stmt=$db->prepare("UPDATE Equipos SET oro=?,score=? WHERE idEquipos=?;");
                $stmt->bind_param("iii", $balance,$new_score,$id_team);
                $stmt->execute();
                $stmt->close();
                $db->close();

				require_once("../../models/class.Fn.php");
				echo '1';
				$f=new Fn();
				$meta = array($price . ' Coins', '20% more scoring');
				$f->InsertHistoric($id_team, 4, $meta);

                echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Congratulations, your team score has increased to ',$new_score,'.
                        </div>';
            }
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            You do not have enough coins to buy this upgrade.
                     </div>';
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
    public function ChangePlayerNumber($id,$number,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND idJugadores=?;");
        $stmt->bind_param("ii", $user,$id);
        $stmt->bind_result($id_team);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt=$db->prepare("SELECT idJugadores FROM Jugadores WHERE Equipos_idEquipos=? AND numero_ju=?;");
            $stmt->bind_param("ii", $id_team,$number);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows==0)
            {
                $stmt=$db->prepare("UPDATE Jugadores SET numero_ju=? WHERE Equipos_idEquipos=? AND idJugadores=?;");
                $stmt->bind_param("iii", $number,$id_team,$id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Congratulations, you have changed his shirt number successfully.
                     </div><script>$(location).attr("href", "team_name");</script>';
            }
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            You have already have a player using this shirt number, please try with another one or change his number first.
                     </div>';
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
    public function SellPlayer($id_player,$price,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND subasta=0 AND idJugadores=?;");
        $stmt->bind_param("si", $user,$id_player);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("UPDATE Suplentes SET subasta=1 WHERE Jugadores_idJugadores=?;");
            $stmt->bind_param("i", $id_player);
            $stmt->execute();
            $stmt=$db->prepare("INSERT INTO Subasta (precio_su,fecha,Jugadores_idJugadores) VALUES (?,now(),?);");
            $stmt->bind_param("ii", $price,$id_player);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Your player is now on the trading list to be sold, refreshing..
                 </div><script>$(location).attr("href", "sell_player");</script>';
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
    public function BuyTradePlayer($id_player,$shirt,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre_ju,Equipos_idEquipos,precio_su FROM Jugadores INNER JOIN Subasta ON Jugadores.idJugadores=Subasta.Jugadores_idJugadores WHERE idJugadores=?;");
        $stmt->bind_param("i", $id_player);
        $stmt->bind_result($name,$team_seller,$price);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt=$db->prepare("SELECT idEquipos FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=? AND nombre_ju=?;");
            $stmt->bind_param("ss", $user,$name);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows==0)
            {
                $stmt=$db->prepare("SELECT oro,idEquipos FROM Equipos WHERE usuario=?;");
                $stmt->bind_param("s", $user);
                $stmt->bind_result($balance,$team_buyer);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if($price<=$balance)
                {
                    $stmt=$db->prepare("SELECT * FROM Jugadores WHERE Equipos_idEquipos=? AND numero_ju=?;");
                    $stmt->bind_param("ii", $team_buyer,$shirt);
                    $stmt->execute();
                    $stmt->store_result();
                    if($stmt->num_rows==0)
                    {
                        //lo saco de subasta
                        $stmt=$db->prepare("DELETE FROM Subasta WHERE Jugadores_idJugadores=?;");
                        $stmt->bind_param("i", $id_player);
                        $stmt->execute();
                        //lo cambio de equipo
                        $stmt=$db->prepare("UPDATE Jugadores INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores SET subasta=0,Equipos_idEquipos=?,numero_ju=? WHERE idJugadores=?;");
                        $stmt->bind_param("iii", $team_buyer,$shirt,$id_player);
                        $stmt->execute();
                        //retiro dinero al comprador
                        $balance=$balance-$price;
                        $stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                        $stmt->bind_param("ii", $balance,$team_buyer);
                        $stmt->execute();
                        //consigo el balance del vendedor
                        $stmt=$db->prepare("SELECT oro FROM Equipos WHERE idEquipos=?;");
                        $stmt->bind_param("i", $team_seller);
                        $stmt->bind_result($balance_seller);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->fetch();
                        //le pago al vendedor
                        $balance_seller=$balance_seller+$price;
                        $stmt=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                        $stmt->bind_param("ii", $balance_seller,$team_seller);
                        $stmt->execute();
                        $stmt->close();
                        $db->close();
						//Agrego la data al historico del comprador
						require_once("../../models/class.Fn.php");
						$f=new Fn();
						$meta = array($price . ' Coins', $name);
						$f->InsertHistoric($team_buyer, 1, $meta);
						//Agrego la data al historico del vendedor
						$f->InsertHistoric($team_seller, 3, $meta);
						
                        echo '<div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Congratulations, you have bought ',$name,' he is now with your substitutes, refreshing...
                         </div><script>setTimeout(function(){$(location).attr("href", "trade_list")},1000);</script>';
                    }
                    else
                    {
                        $stmt->close();
                        $db->close();
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    You already have a player with this shirt number, please choose another number or change the number of your player.
                         </div>';
                    }    
                }
                else
                {
                    $stmt->close();
                    $db->close();
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                You do not have enough coins to buy ',$name,'.
                     </div>';
                } 
            }    
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            You already have ',$name,' on your team, he could not be bought twice.
                 </div>';
            }    
           
        }
        else 
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    We could not verify your data, please try again or reload this page.
             </div>';
        }
    } 
    public function RemovePlayer($id_player,$user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND subasta=1 AND idJugadores=?;");
        $stmt->bind_param("si", $user,$id_player);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("DELETE FROM Subasta WHERE Jugadores_idJugadores=?;");
            $stmt->bind_param("i", $id_player);
            $stmt->execute();
            $stmt->store_result();
            $stmt=$db->prepare("UPDATE Suplentes SET subasta=0 WHERE Jugadores_idJugadores=?;");
            $stmt->bind_param("i", $id_player);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        You have removed the player from the trade list, refreshing...
             </div><script>setTimeout(function(){$(location).attr("href", "remove_player");},500);</script>';
        } 
        else 
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    We could not verify your data, please try again or reload this page.
             </div>';
        }
    }        
}    
?>
