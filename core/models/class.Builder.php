<?php

class Builder 
{
    public function CountUsers()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idEquipos FROM Equipos WHERE fantasma=0;");
        if($stmt->num_rows > 0){
            $stmt->execute();
            $stmt->store_result();
            $number=$stmt->num_rows;
            $stmt->close();
            $db->close();
            echo $number+350;
        }else{
            echo 0;
        }
        
    }   
    public function GetRegions()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idRegiones,nombre_reg FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones WHERE rango_div=1;");
        $stmt->bind_result($id,$name);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">',$name,'</option>';
        }
        $stmt->close();
        $db->close();
    }
    public function GetGold($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT oro FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($gold);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo '<h4><img src="libs/images/gold.png"> ',number_format($gold,3, ',', '.'),'</h4>';
    } 
    public function GetGold2($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT gold FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($gold);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo '<h4><img src="libs/images/gold.png"> ',number_format($gold,3, ',', '.'),'</h4>';
    }     
    public function GetTeamInfo($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT score,nombre,logo_eq,tipo_logo_eq,logo_div,tipo_logo_div,idEquipos FROM Divisiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones INNER JOIN Equipos ON Ligas.idLigas=Equipos.Ligas_idLigas WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($score,$nombre,$shield,$type,$logo_div,$type_div,$id_team);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Trofeos WHERE Equipos_idEquipos=?;");
        $stmt->bind_param("i", $id_team);
        $stmt->bind_result($trophies);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if(empty($type))
        {
            echo '<img class="img-responsive team-logo center-block" src="libs/images/shield_default.png">';
        }
        else
        {
            echo '<img class="img-responsive team-logo center-block" src="data:image/',$type,';base64,'.base64_encode($shield).'">';
        }
        echo '<hgroup>
                 <h2>',$nombre,'</h2>
                 <h2><img class="division-logo" src="data:image/',$type_div,';base64,'.base64_encode($logo_div).'"><img class="division-logo trophies" src="libs/images/trophy.png" data-toggle="tooltip" data-container="body" data-placement="right" title="The trophies are only available for first division champions."> ',$trophies,'</h2>
                 <h4>SCORE : ',$score,'</h4>   
             </hgroup>';
        $stmt->close();
        $db->close();  
    } 
    public function GetLeagueTable($user)
    {
		global $HOME;
		
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT Ligas_idLigas FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_league);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("SELECT puntos,partidos_jugados,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra,nombre,logo_eq,tipo_logo_eq FROM Posiciones INNER JOIN Equipos ON Posiciones.Equipos_idEquipos=Equipos.idEquipos WHERE Posiciones.Ligas_idLigas=? ORDER BY puntos DESC,(goles_favor-goles_contra) DESC;");
        $stmt->bind_param("i", $id_league);
        $stmt->bind_result($points,$n_played,$n_won,$n_lost,$n_tied,$gf,$gc,$team,$shield,$type_shield);
        $stmt->execute();
        $stmt->store_result();
        $pos=1;
        while($stmt->fetch())
        {
            if($pos<=4)
            {
                $class="first_table";
            }
            if($pos>4 && $pos<27)
            {
                $class="";
            }
            else if($pos>=27 && $pos<=30)
            {
                $class="last_table";
            }    
            echo '<tr class="',$class,'">
                        <td>',$pos,'</td>';
            if(empty($type_shield))
            {
                echo  '<td class="text-left"><img class="mini-team-logo" src="'.$HOME.'libs/images/shield_default.png"> ',$team,'</td>';
            }
            else
            {
                echo  '<td class="text-left"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($shield).'"> ',$team,'</td>';
            }  
            echo            '<td>',$n_played,'</td>
                        <td class="hidden-sm hidden-xs">',$n_won,'</td>
                        <td class="hidden-sm hidden-xs">',$n_tied,'</td>
                        <td class="hidden-sm hidden-xs">',$n_lost,'</td>
                        <td class="hidden-sm hidden-xs">',$gf,'</td>
                        <td class="hidden-sm hidden-xs">',$gc,'</td>
                        <td class="hidden-sm hidden-xs">',($gf-$gc),'</td>
                        <td>',$points,'</td>
                  </tr>';
            $pos++;
        }    
        $stmt->close();
        $db->close();
    }  
    public function GetTeamStadium($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre_es,capacidad_es,ingresos_es FROM Equipos INNER JOIN Estadios ON Equipos.idEquipos=Estadios.Equipos_idEquipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($name_stadium,$capacity,$revenue);
        $stmt->execute();
        $stmt->fetch();
        echo '<hgroup>
                    <h3 class="nom-nop">',$name_stadium,'</h3>
                </hgroup>
              <img class="img-responsive team-logo center-block" src="libs/images/stadium.png">
              <hgroup>
                    <h4>CAPACITY: ', number_format($capacity, 0, ',', '.'),'</h4>
                    <h4>PROFIT/MATCH:  <img src="libs/images/gold.png">', number_format($revenue, 0, ',', '.'),'</h4>
              </hgroup>';
        $stmt->close();
        $db->close();
    } 
    public function GetTeamName($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $name;
    }
	
	public function GetPrevMatch($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT Ligas_idLigas,idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_league,$id_team);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("SELECT local,visitante,nombre_fecha,goles_local,goles_visitante FROM Fechas INNER JOIN Partidos ON Fechas.idFechas=Partidos.Fechas_idFechas WHERE Fechas.Ligas_idLigas=? AND Fechas.terminada=1 AND (Partidos.local=? OR Partidos.visitante=?)  ORDER BY idFechas DESC LIMIT 1;");
        $stmt->bind_param("iii", $id_league,$id_team,$id_team);
        $stmt->bind_result($home,$guest,$name_round,$goal_local,$goal_visit);
        $stmt->execute();
        $stmt->store_result();	
        if($stmt->num_rows>0)
        {    
            $stmt->fetch();
            echo '<h3 class="nom-nop">Previous Match <small>[',$name_round,']</small></h3><br>';
            if($home==$id_team)
            {
                // SOY LOCAL
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_team);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <span class="goals_box">',$goal_local,'</span></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <span class="goals_box">',$goal_local,'</span></h4>';
                }    
                echo '<h4 class="text-center">VS</h4>';
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $guest);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <span class="goals_box">',$goal_visit,'</span></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <span class="goals_box">',$goal_visit,'</span></h4>';
                }   
            }
            else
            {
                // SOY VISITANTE
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $home);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <span class="goals_box">',$goal_local,'</span></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <span class="goals_box">',$goal_local,'</span></h4>';
                }    
                echo '<h4 class="text-center">VS</h4>';
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_team);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <span class="goals_box">',$goal_visit,'</span></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <span class="goals_box">',$goal_visit,'</span></h4>';
                } 
            }
        }
        else
        {
            echo '<h3 class="nom-nop">Next Match</h3>
                  <h4><small>THE TOURNAMENT HAS FINISHED</small></h4>
                  <h5><small>IN THE NEXT 12 HOURS YOU WILL SEE YOUR AWARD CREDITED IF YOU ARE INTO THE FIRST 26 TEAMS AND A NEW TOURNAMENT WILL START</small></h5>';
        }    
        $stmt->close();
        $db->close();
    }
	
    public function GetNextMatch($user)
    {
		
		$time1 = $this->getNextRunTime(['i' => 0, 'H' => 0, 'd' => 0, 'm' => 0]);
		$time2 = $this->getNextRunTime(['i' => 0, 'H' => 24, 'd' => 01, 'm' => 01]);
		$time = min($time1, $time2);
		
		?>
		<span id="nextMatch" class="clock_box"></span>
		<script>
		var nextGame = moment.tz("<?= date('Y-m-d H:i:s', $time); ?>", "UTC");
		$('#nextMatch').countdown(nextGame.toDate(), function(event) {
		  $(this).html(event.strftime('%H:%M:%S'));
		});
		</script>
		<?php
		
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT Ligas_idLigas,idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_league,$id_team);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("SELECT local,visitante,nombre_fecha FROM Fechas INNER JOIN Partidos ON Fechas.idFechas=Partidos.Fechas_idFechas WHERE Fechas.Ligas_idLigas=? AND Fechas.terminada=0 AND (Partidos.local=? OR Partidos.visitante=?) LIMIT 1;");
        $stmt->bind_param("iii", $id_league,$id_team,$id_team);
        $stmt->bind_result($home,$guest,$name_round);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {    
            $stmt->fetch();
            echo '<h3 class="nom-nop">Next Match <small>[',$name_round,']</small></h3><br>';
            if($home==$id_team)
            {
                // SOY LOCAL
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_team);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <small>',$score,'</small></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <small>',$score,'</small></h4>';
                }    
                echo '<h4 class="text-center">VS</h4>';
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $guest);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <small>',$score,'</small></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <small>',$score,'</small></h4>';
                }   
            }
            else
            {
                // SOY VISITANTE
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $home);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <small>',$score,'</small></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <small>',$score,'</small></h4>';
                }    
                echo '<h4 class="text-center">VS</h4>';
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_team);
                $stmt->bind_result($team_name,$team_shield,$type_shield,$score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($type_shield))
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="libs/images/shield_default.png"> ',$team_name,' <small>',$score,'</small></h4>';
                }
                else
                {
                    echo '<h4 class="nom-nop"><img class="mini-team-logo" src="data:image/',$type_shield,';base64,'.base64_encode($team_shield).'"> ',$team_name,' <small>',$score,'</small></h4>';
                } 
            }
            echo '<h4 class="text-right show_fixture"><small><a href="fixture">SHOW FULL FIXTURE</a></small></h4>';
        }
        else
        {
            echo '<h3 class="nom-nop">Next Match</h3>
                  <h4><small>THE TOURNAMENT HAS FINISHED</small></h4>
                  <h5><small>IN THE NEXT 12 HOURS YOU WILL SEE YOUR AWARD CREDITED IF YOU ARE INTO THE FIRST 26 TEAMS AND A NEW TOURNAMENT WILL START</small></h5>';
        }    
        $stmt->close();
        $db->close();
    }
    public function GetResults($user, $round = 0)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT Ligas_idLigas FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_league);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
		if($round == 0){
			$stmt=$db->prepare("SELECT idFechas,nombre_fecha FROM Fechas WHERE Ligas_idLigas=? AND terminada=1 ORDER BY idFechas DESC LIMIT 1;");
			$stmt->bind_param("i", $id_league);
        }else{
			$stmt=$db->prepare("SELECT idFechas,nombre_fecha FROM Fechas WHERE Ligas_idLigas=? AND nombre_fecha=? ORDER BY idFechas DESC LIMIT 1;");
			$name_r = "Round ".$round;
			$stmt->bind_param("is", $id_league, $name_r); 
		}
		$stmt->bind_result($id_round,$name_round);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
			preg_match_all('/\d+/', $name_round, $matches);
			$num_round = (empty($matches[0])) ? 0 : $matches[0][0];
			$prev_round = $num_round-1;
			$next_round = $num_round+1;
			if($num_round > 1)
				echo '<form method="post">
					  <button type="submit" name="round" value="'.$prev_round.'" class="btn btn-link center-block show_more btn-prev pull-left">
						<span class="glyphicon glyphicon-chevron-left"></span>
					  </button>
					</form>';
			if($num_round < 58)
				echo '<form method="post">
					  <button type="submit" name="round" value="'.$next_round.'" class="btn btn-link center-block show_more btn-next pull-right">
						<span class="glyphicon glyphicon-chevron-right"></span>
					  </button>
					</form>';
			
            echo '<h3 class="module-title text-center">', strtoupper($name_round),' RESULTS</h3>';
            $stmt=$db->prepare("SELECT local,visitante,goles_local,goles_visitante FROM Partidos WHERE Fechas_idFechas=?;");
            $stmt->bind_param("i", $id_round);
            $stmt->bind_result($id_home,$id_guest,$goals_home,$goals_guest);
            $stmt->execute();
            $stmt->store_result();
            while($stmt->fetch())
            {
                $stmt_aux=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt_aux->bind_param("i", $id_home);
                $stmt_aux->bind_result($name_home,$home_shield,$type_home_shield);
                $stmt_aux->execute();
                $stmt_aux->store_result();
                $stmt_aux->fetch();
                if(empty($type_home_shield))
                {
                    $home_shield="<img class='mini-team-logo' src='libs/images/shield_default.png'>";
                }
                else
                {
                    $home_shield="<img class='mini-team-logo' src='data:image/".$type_home_shield.";base64,".base64_encode($home_shield)."'>";
                }    
                $stmt_aux->bind_param("i", $id_guest);
                $stmt_aux->bind_result($name_guest,$guest_shield,$type_guest_shield);
                $stmt_aux->execute();
                $stmt_aux->store_result();
                $stmt_aux->fetch();
                $stmt_aux->close();
                if(empty($type_guest_shield))
                {
                    $guest_shield="<img class='mini-team-logo' src='libs/images/shield_default.png'>";
                }
                else
                {
                    $guest_shield="<img class='mini-team-logo' src='data:image/".$type_guest_shield.";base64,".base64_encode($guest_shield)."'>";
                } 
                echo '<div class="ranking_box">
                            <h4 class="nom-nop text-center">',$home_shield,' ',$name_home,' <span class="goals_box">',$goals_home,'</span> -  <span class="goals_box">',$goals_guest,'</span> ',$guest_shield,' ',$name_guest,'</h4>
                      </div>';
            }   
        }
        else
        {
            echo '<p class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> There is nothing to show at the moment, come back after first round.</p>';
        }
        $stmt->close();
        $db->close();
        
    }
    public function GetFixture($user)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt2=$db->prepare("SELECT Ligas_idLigas,idEquipos,nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt2->bind_param("s", $user);
        $stmt2->bind_result($id_league,$id_team,$home_name,$home_shield,$home_type_shield,$home_score);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->fetch();
        if(empty($home_type_shield))
        {
            $src_home='libs/images/shield_default.png';
        }
        else
        {
            $src_home=$src='data:image/'.$home_type_shield.';base64,'.base64_encode($home_shield).'';
        }
        $stmt2=$db->prepare("SELECT nombre_fecha,terminada,local,visitante,goles_local,goles_visitante FROM Fechas INNER JOIN Partidos ON Fechas.idFechas=Partidos.Fechas_idFechas WHERE Ligas_idLigas=? AND (local=? OR visitante=?);");
        $stmt2->bind_param("iii", $id_league,$id_team,$id_team);
        $stmt2->bind_result($name_round,$finished,$id_home,$id_guest,$goals_home,$goals_guest);
        $stmt2->execute();
        $stmt2->store_result();
        while($stmt2->fetch())
        {
            // SOY LOCAL
            if($id_home==$id_team)
            {
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_guest);
                $stmt->bind_result($guest_name,$guest_shield,$guest_type_shield,$guest_score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($guest_type_shield))
                {
                    $src_guest='libs/images/shield_default.png';
                }
                else
                {
                    $src_guest=$src='data:image/'.$guest_type_shield.';base64,'.base64_encode($guest_shield).'';
                }
                if($finished==1)
                {    
                    echo '<div class="ranking_box">
                                <h3 class="text-center round_name">',$name_round,'</h3>
                                <h4 class="nom-nop text-center"><img class="mini-team-logo" src="',$src_home,'"> ',$home_name,'<small> [',$home_score,']</small> <span class="goals_box">',$goals_home,'</span>  -  <span class="goals_box">',$goals_guest,'</span> <img class="mini-team-logo" src="',$src_guest,'"> ',$guest_name,'<small> [',$guest_score,']</small></h4>
                          </div>';
                }
                else
                {
                    echo '<div class="ranking_box">
                                <h3 class="text-center round_name">',$name_round,'</h3>
                                <h4 class="nom-nop text-center"><img class="mini-team-logo" src="',$src_home,'"> ',$home_name,'<small> [',$home_score,']</small>  vs  <img class="mini-team-logo" src="',$src_guest,'"> ',$guest_name,'<small> [',$guest_score,']</small></h4>
                          </div>';
                }    
            }    
            else
            {
                $stmt=$db->prepare("SELECT nombre,logo_eq,tipo_logo_eq,score FROM Equipos WHERE idEquipos=? LIMIT 1;");
                $stmt->bind_param("i", $id_home);
                $stmt->bind_result($guest_name,$guest_shield,$guest_type_shield,$guest_score);
                $stmt->execute();
                $stmt->store_result();
                $stmt->fetch();
                if(empty($guest_type_shield))
                {
                    $src_guest='libs/images/shield_default.png';
                }
                else
                {
                    $src_guest=$src='data:image/'.$guest_type_shield.';base64,'.base64_encode($guest_shield).'';
                }
                if($finished==1)
                {    
                    echo '<div class="ranking_box">
                                <h3 class="text-center round_name">',$name_round,'</h3>
                                <h4 class="nom-nop text-center"><img class="mini-team-logo" src="',$src_guest,'"> ',$guest_name,'<small> [',$guest_score,']</small> <span class="goals_box">',$goals_home,'</span> - <span class="goals_box">',$goals_guest,'</span> <img class="mini-team-logo" src="',$src_home,'"> ',$home_name,'<small> [',$home_score,']</small></h4>
                          </div>';
                }
                else
                {
                    echo '<div class="ranking_box">
                                <h3 class="text-center round_name">',$name_round,'</h3>
                                <h4 class="nom-nop text-center"><img class="mini-team-logo" src="',$src_guest,'"> ',$guest_name,'<small> [',$guest_score,']</small>  vs <img class="mini-team-logo" src="',$src_home,'"> ',$home_name,'<small> [',$home_score,']</small></h4>
                          </div>';
                }    
            }
             $stmt->close();
        }
       
        $stmt2->close();
        $db->close();
    }        
    public function GetShirts()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idCamisetas,camiseta,tipo_ca FROM Camisetas;");
        $stmt->bind_result($id,$shirt,$type);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-sm-2 col-xs-6 shirt text-center">
                       <img src="data:image/',$type,';base64,'.base64_encode($shirt).'" class="shirt_image">
                       <div class="center-block">
                            <input type="radio" name="shirt" value="',$id,'" class="center">
                        </div>
                   </div>';
        }
        $stmt->close();
        $db->close();
    }
	public function GetReferrals($user){

		require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt2=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt2->bind_param("s", $user);
        $stmt2->bind_result($id_team);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->fetch();
		
		
		$stmt2=$db->prepare("SELECT idEquipos,nombre,usuario FROM Equipos WHERE referral=?;");
		$stmt2->bind_param("i", $id_team);
		$stmt2->bind_result($id_ref,$name_ref,$user_ref);
        $stmt2->execute();
        $stmt2->store_result();
		$referred = [];
		
		while($stmt2->fetch()){
			$referred[$id_ref] = array('name' => $name_ref, 'user' => $user_ref);
		}
		if(count($referred) == 0){
			echo '<div class="col-xs-12 ranking_box">You have no referrels yet.</div>';
			exit;
		}

		foreach($referred as $key=>$ref){
			$stmt=$db->prepare("SELECT monto FROM Compras WHERE Equipos_idEquipos=? AND Pago ='Usd' AND pagado = 1;");
			$stmt->bind_param("i", $key);
			$stmt->bind_result($usd);
			$stmt->execute();
			$stmt->store_result();
			$income = 0;
			while($stmt->fetch())
			{
				$income+= $usd;
			}
			$referred[$key]['income'] = $income;
		}
		
		echo '<div class="col-xs-12 referral_box" style="background: rgba(0,0,0,0.8);">';
			echo '<span class="referral_name">Name</span> <span class="referral_user">Team</span> <span class="referral_income">Income</span>';
		echo '</div>';

		foreach($referred as $r){
			$price = 'U$S '. number_format($r['income'],2,",",".");
			echo '<div class="col-xs-12 referral_box">';
				echo '<span class="referral_name">'.$r['name'].'</span> <span class="referral_user">'.$r['user'].'</span> <span class="referral_income">'.$price.'</span>';
			echo '</div>';
		}

	}
	
	public function GetHistoric($user, $range)
    {
		require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt2=$db->prepare("SELECT idEquipos FROM Equipos WHERE usuario=? LIMIT 1;");
        $stmt2->bind_param("s", $user);
        $stmt2->bind_result($id_team);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->fetch();
		
		$stmt2=$db->prepare("SELECT id,texto FROM historico_textos;");
        $stmt2->bind_result($historic_id, $historic_text);
        $stmt2->execute();
        $stmt2->store_result();
		$historic_texts = [];
		while($stmt2->fetch()){
			$historic_texts[$historic_id] = $historic_text;
		}
		$param=$range+1;
        $range=$range*20;
        $stmt=$db->prepare("SELECT historico_textos_id,metadata,date FROM historico_data WHERE Equipos_idEquipos=? ORDER BY id DESC LIMIT ?,20;");
        $stmt->bind_param("ii", $id_team, $range);
        $stmt->bind_result($texto_id, $metadata, $date);
        $stmt->execute();
        $stmt->store_result();
		while($stmt->fetch())
        {
			$timesince = $this->time_elapsed_string('@'.$date);
			 echo '<div class="col-xs-12 historic_box">
                    <hgroup>';
				echo '<h4 class="nom-nop"><span class="historic_date">',$timesince,'</span> <span class="">',vsprintf($historic_texts[$texto_id], explode(',',$metadata)),'</span></h4>';
            echo '  </hgroup>
                  </div>';
		}
		$stmt->prepare("SELECT COUNT(*) FROM historico_data WHERE Equipos_idEquipos=?;");
		$stmt->bind_param("i", $id_team);
        $stmt->bind_result($total);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		if(($param*20)<$total)
        {
            echo '<button class="btn btn-link center-block show_more" value="',$param,'"><span class="glyphicon glyphicon-chevron-down"></span></button>';
        }    
		
	}
	
	function getNextRunTime($config) {
		$minute = $config['i'];
		$hour   = $config['H'];
		$day    = $config['d'];
		$month  = $config['m'];

		// Get minute
		switch($minute) {
			case 90 :
				$nextMinute = date('i', strtotime('now + 1 minute'));
				break;
			default :
				$nextMinute = $minute;
		}

		// Get hour
		switch($hour) {
			case 90 :
				if($minute == 90 || $nextMinute > date('i')) {
					$nextHour = date('H');
				} else {
					$nextHour = date('H', strtotime('now + 1 hour'));
				}
				break;
			default :
				$nextHour = $hour;
		}

		// Get day
		switch($day) {
			case 90 :
				if($hour == 90 && $nextHour > date('H')) {
					$nextDay = date('d');
				} elseif($hour <> 90 && $nextHour <= date('H')) {
					$nextDay = date('d', strtotime('now + 1 day'));
				} else {
					if($nextHour > date('H')) {
						$nextDay = date('d');
					} else {
						if ($nextMinute > date('i')) {
							$nextDay = date('d');
						} else {
							$nextDay = date('d', strtotime('now + 1 day'));
						}
					}
				}
				break;
			case 91 :
				if(date('t') == date('d')) {
					if($nextHour > date('H')) {
						$nextDay = date('d');
					} elseif($nextHour == date('H') && $nextMinute > date('i')) {
						$nextDay = date('d');
					} else {
						$nextDay = date('t', strtotime('now + 1 month'));
					}
				} else {
					$nextDay = date('t');
				}
				break;
			default :
				$nextDay = $day;
		}

		// Get month
		switch($month) {
			case 90 :
				if($day == 90 || $nextDay > date('d')) {
					$nextMonth = date('m');
				} elseif($nextDay == date('d')) {
					if($hour == 90 || $nextHour > date('H')) {
						$nextMonth = date('m');
					} elseif($nextHour == date('H')) {
						if($minute == 90 || $nextMinute > date('i')) {
							$nextMonth = date('m');
						} else {
							$nextMonth = date('m', strtotime('now + 1 month'));
						}
					} else {
						$nextMonth = date('m', strtotime('now + 1 month'));
					}
				} else {
					$nextMonth = date('m', strtotime('now + 1 month'));
				}
				break;
			default :
				$nextMonth = $month;
		}

		// Get year
		if($month == 90 || $nextMonth > date('m')) {
			$nextYear = date('Y');
		} elseif($nextMonth == date('m')) {
			if($day == 90 || $nextDay > date('d')) {
				$nextYear = date('Y');
			} elseif($nextDay == date('m')) {
				if($hour == 90 || $nextHour > date('H')) {
					$nextYear = date('Y');
				} elseif($nextHour == date('H')) {
					if($minute == 90 || $nextMinute > date('i')) {
						$nextYear = date('Y');
					} else {
						$nextYear = date('Y') + 1;
					}
				} else {
					$nextYear = date('Y') + 1;
				}
			} else {
				$nextYear = date('Y') + 1;
			}
		} else {
			$nextYear = date('Y') + 1;
		}

		// Create the timestamp for the 'Next Run Time'
		$nextRunTime = mktime($nextHour, $nextMinute, 0, $nextMonth, $nextDay, $nextYear);

		// Check if the job has to run every minute, maybe a reset to d-m-Y h:00 is possible
		if($nextRunTime > time() && $minute == 90) {
			$tempNextRunTime = mktime($nextHour, 0, 0, $nextMonth, $nextDay, $nextYear);

			if($tempNextRunTime > time()) {
				$nextMinute  = 0;
				$nextRunTime = $tempNextRunTime;
			}
		}

		// Check if the job has to run every hour, maybe a reset to d-m-Y 00:i is possible
		if($nextRunTime > time() && $hour == 90) {
			$tempNextRunTime = mktime(0, $nextMinute, 0, $nextMonth, $nextDay, $nextYear);

			if($tempNextRunTime > time()) {
				$nextHour    = 0;
				$nextRunTime = $tempNextRunTime;
			}
		}

		// Check if the job has to run every day, maybe a reset to 1-m-Y H:i is possible
		if($nextRunTime > time() && $day == 90) {
			$tempNextRunTime = mktime($nextHour, $nextMinute, 0, $nextMonth, 1, $nextYear);

			if($tempNextRunTime > time()) {
				$nextRunTime = $tempNextRunTime;
			}
		}

		// Return the Next Run Time timestamp
		return $nextRunTime;
	}
	
	public function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
    public function GetRanking($range)
    {
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $param=$range+1;
        $range=$range*20;
        $pos=$range+1;
        $stmt=$db->prepare("SELECT nombre,score,usuario,logo_eq,tipo_logo_eq,idEquipos FROM Equipos WHERE fantasma=0 ORDER BY score DESC LIMIT ?,20;");
        $stmt->bind_param("i", $range);
        $stmt->bind_result($name,$score,$user,$shield,$type,$id_team);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $stmt_aux=$db->prepare("SELECT COUNT(*) FROM Trofeos WHERE Equipos_idEquipos=?;");
            $stmt_aux->bind_param("i", $id_team);
            $stmt_aux->bind_result($trophies);
            $stmt_aux->execute();
            $stmt_aux->store_result();
            $stmt_aux->fetch();
            $stmt_aux->close();
            echo '<div class="col-xs-12 ranking_box">
                    <hgroup>';
            if(empty($type))
            {
                echo '<h4 class="nom-nop"><span class="ranking_number">',$pos,'</span> <img class="mini-team-logo" src="libs/images/shield_default.png"> ',$name,' <small class="ranking_score">[',$score,']</small> <img class="mini-team-trophy" src="libs/images/trophy.png"><small class="ranking_score">[',$trophies,']</small><small> [',strtoupper($user),']</small></h4>';
            }
            else
            {
                echo '<h4 class="nom-nop"><span class="ranking_number">',$pos,'</span> <img class="mini-team-logo" src="data:image/',$type,';base64,'.base64_encode($shield).'"> ',$name,' <small class="ranking_score">[',$score,']</small> <img class="mini-team-trophy" src="libs/images/trophy.png"><small class="ranking_score">[',$trophies,']</small> <small> [',strtoupper($user),']</small></h4>';
            }    
            echo '  </hgroup>
                  </div>';
            $pos++;
        }
        $stmt->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0;");
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if(($param*20)<$total)
        {
            echo '<button class="btn btn-link center-block show_more" value="',$param,'"><span class="glyphicon glyphicon-chevron-down"></span></button>';
        }    
        $stmt->close();
        $db->close();
    } 
	
	
	public function GetRankingIndex()
    {
		global $HOME;
        if($_SERVER["HTTP_HOST"] == "localhost"){
        $HOME = 'http://localhost/SoccerTycoon/';
        }else{
            $HOME = 'https://'.$_SERVER["HTTP_HOST"].'/';
        }
		
        require_once("../../models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT nombre,score,usuario,logo_eq,tipo_logo_eq,idEquipos FROM Equipos WHERE fantasma=0 ORDER BY score DESC LIMIT 10;");
        //$stmt->bind_param("i", $range);
        $stmt->bind_result($name,$score,$user,$shield,$type,$id_team);
        $stmt->execute();
        $stmt->store_result();
		$pos=1;
        while($stmt->fetch())
        {
            $stmt_aux=$db->prepare("SELECT COUNT(*) FROM Trofeos WHERE Equipos_idEquipos=?;");
            $stmt_aux->bind_param("i", $id_team);
            $stmt_aux->bind_result($trophies);
            $stmt_aux->execute();
            $stmt_aux->store_result();
            $stmt_aux->fetch();
            $stmt_aux->close();
            echo '<div class="col-xs-12 ranking_box">
                    <hgroup>';
            if(empty($type))
            {
                echo '<h4 class="nom-nop"><span class="ranking_number">',$pos,'</span> <img class="mini-team-logo" src="'.$HOME.'libs/images/shield_default.png"> ',$name,' <small class="ranking_score">[',$score,']</small> <img class="mini-team-trophy" src="'.$HOME.'libs/images/trophy.png"><small class="ranking_score">[',$trophies,']</small><small> [',strtoupper($user),']</small></h4>';
            }
            else
            {
                echo '<h4 class="nom-nop"><span class="ranking_number">',$pos,'</span> <img class="mini-team-logo" src="data:image/',$type,';base64,'.base64_encode($shield).'"> ',$name,' <small class="ranking_score">[',$score,']</small> <img class="mini-team-trophy" src="'.$HOME.'libs/images/trophy.png"><small class="ranking_score">[',$trophies,']</small> <small> [',strtoupper($user),']</small></h4>';
            }    
            echo '  </hgroup>
                  </div>';
            $pos++;
        }
        $stmt->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0;");
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();   
        $stmt->close();
        $db->close();
    } 
	
	
	
    public function GetLeagueInfo($user)
    {
        require_once("core/models/class.Connection.php");
        require_once("core/models/class.Fn.php");
        $db = new Connection();
        $f = new Fn();
        $stmt=$db->prepare("SELECT Regiones_idRegiones FROM Divisiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones INNER JOIN Equipos ON Ligas.idLigas=Equipos.Ligas_idLigas WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_region);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt=$db->prepare("SELECT precio_partido,logo_div,tipo_logo_div,rango_div FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones WHERE idRegiones=? ORDER BY rango_div ASC;");
        $stmt->bind_param("i", $id_region);
        $stmt->bind_result($gold,$logo,$type,$range_div);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {    
            $awards=$f->GetDivisionAwards($range_div, $gold);
            echo '<div class="container-fluid ranking_box"><div class="col-md-4 col-sm-12 col-xs-12">
                            <img class="img-responsive center-block" src="data:image/',$type,';base64,'.base64_encode($logo).'">
                      </div>
                      <div class="col-md-4 col-sm-6 col-xs-12">
                            <hgroup>
                                <h4>N&deg; Teams: 30</h4>
                                <h4>N&deg; Rounds: 58</h4>
                                <h4>Match Winner: <img src="libs/images/gold.png"> ', number_format($awards["match_winner_award"], 0, "", "."),'</h4>
                                <h4>Match Tie: <img src="libs/images/gold.png"> ', number_format(intval($awards["match_winner_award"]/2), 0, "", "."),'</h4>
                            </hgroup>
                      </div>
                      <div class="col-md-4 col-sm-6 col-xs-12">
                            <hgroup>
                                <h4>Division Awards</h4>
                                <h4>1&deg;: &nbsp;<img src="libs/images/gold.png"> ',number_format($awards["champion_award"], 0, "", "."),'</h4>
                                <h4>2&deg;: &nbsp;<img src="libs/images/gold.png"> ',number_format($awards["second_award"], 0, "", "."),'</h4>
                                <h4>3&deg; - 25&deg;: &nbsp;<img src="libs/images/gold.png"> ',number_format($awards["rest_award"], 0, "", "."),'</h4>
                            </hgroup>
                      </div></div>';
        }
        $stmt->close();
        $db->close();
    }    
    public function GetPremiumPlayers($range,$pos)
    {
        require_once("../../models/class.Connection.php");
        require_once("../../models/class.Fn.php");
        $db=new Connection();
        $f=new Fn();
        $param=$range+1;
        $range=$range*12;
		$stringSearchByPos='';
		if($pos!=null)
		{
			$stringSearchByPos = "AND posicion_jp='".$pos."'";
		}
        $stmt=$db->prepare("SELECT idJugadoresPagos,nombre_jp,posicion_jp,puntos_jp,precio_jp,foto_jp,tipo_foto_jp FROM JugadoresPagos WHERE stock_jp>0 ".$stringSearchByPos." ORDER BY precio_jp ASC LIMIT ?,12;");
        $stmt->bind_param("i", $range);
        $stmt->bind_result($id,$name,$position,$score,$price,$photo,$type);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            if(!empty($type))
            {
                $src='data:image/'.$type.';base64,'.base64_encode($photo).'';
            }
            else
            {
                $src='libs/images/player_avatar.png';
            }
            echo '<div class="col-lg-2 col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="player_avatar" src="',$src,'" alt="">
                        <div class="caption">
                            <h4 class="text-center nom-nop player_name">',ucfirst($name),'</h4>
                            <h5 class="text-center nom-nop player_info"><span>',$position,'</span> <span>',$score,'</span></h5>
                            <h5 class="text-center player_info"><span>',$f->GetPlayerLevel($score),'</span></h5>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ',number_format($price,0,"","."),'</h5>
                            <div class="form-group">
                                <input type="number" name="shirt" class="form-control shirt',$id,'" placeholder="SHIRT N&deg;">
                            </div>
                            <button class="btn btn-primary center-block buy" value="',$id,'">BUY</button>       
                        </div>
                    </div>
                </div>';
        }
        $stmt->prepare("SELECT COUNT(*) FROM JugadoresPagos WHERE stock_jp>0;");
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if(($param*12)<$total)
        {
            echo '<div class="col-xs-12"><button class="btn btn-link center-block show_more" value="',$param,'"><span class="glyphicon glyphicon-chevron-down"></span></button></div>';
        }    
        $stmt->close();
        $db->close();
        
    } 
    public function GetStadiumImprovements()
    {
        require_once("core/models/class.Connection.php");
        $db = new Connection();
        $stmt=$db->prepare("SELECT idMejorasEstadio,capacidad_me,precio_me FROM MejorasEstadio;");
        $stmt->bind_result($id,$capacity,$price);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<div class="col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="player_avatar" src="libs/images/seats.png">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">Add ',$capacity,' seats</h4>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ', number_format($price,0,"","."),'</h5>
                            <h4 class="text-center"><small>THIS EXPANDS YOUR STADIUM CAPACITY</small></h4>
                        </div>
                        <button class="btn btn-primary center-block buy" value="',$id,'">BUY</button> 
                    </div>
                </div>';
        }
        $stmt->close();
        $db->close();
    }   
    public function GetPlayerImprovements($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idMejorasJugador,nombre_mju,puntos_mju,precio_mju,logo_mju,tipo_logo_mju FROM MejorasJugador WHERE afecta_todos=0;");
        $stmt->bind_result($id,$name,$score,$price,$logo,$type);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="" src="data:image/',$type,';base64,'.base64_encode($logo).'">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">',$name,'</h4>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ', number_format($price,0,"","."),'</h5>
                            <h4 class="text-center"><small>THIS WILL ADD ',$score,' OF SCORE TO YOUR PLAYER</small></h4>
                               
                        </div>
                        <div class="form-group">
                            <select class="form-control select',$id,'">';
                            $stmt2=$db->prepare("SELECT idJugadores,nombre_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=?;");
                            $stmt2->bind_param("s", $user);
                            $stmt2->bind_result($id_player,$player_name);
                            $stmt2->execute();
                            $stmt2->store_result();
                            while($stmt2->fetch())
                            {
                                echo '<option value="',$id_player,'">',$player_name,'</option>';
                            }    
            echo            '</select>
                        </div> 
                        <button class="btn btn-primary center-block buy_pl_upgrade" value="',$id,'">BUY</button> 
                    </div>
                </div>';
			$tt = $type;
			$ll = $logo;
        }
		
		$price = 3000;
		
		echo '<div class="col-sm-6 col-xs-12 player_box">
				<div class="thumbnail">
					<img class="" src="data:image/',$tt,';base64,'.base64_encode($ll).'">
					<div class="caption">
						<h4 class="text-center upgrade_title">REGENERATE STAMINA</h4>
						<h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ', number_format($price,0,"","."),'</h5>
						<h4 class="text-center"><small>THIS WILL REGENERATE THE STAMINA OF YOUR PLAYER</small></h4>
						   
					</div>
					<div class="form-group">
						<select class="form-control selectstamina">';
						$stmt3=$db->prepare("SELECT idJugadores,nombre_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=?;");
						$stmt3->bind_param("s", $user);
						$stmt3->bind_result($id_player,$player_name);
						$stmt3->execute();
						$stmt3->store_result();
						while($stmt3->fetch())
						{
							echo '<option value="',$id_player,'">',$player_name,'</option>';
						}    
		echo            '</select>
					</div> 
					<button class="btn btn-primary center-block buy_pl_upgrade" value="stamina">BUY</button> 
				</div>
			</div>';
        $stmt2->close();
        $stmt->close();
        $db->close();
  }
    public function GetTeamImprovements()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idMejorasJugador,nombre_mju,puntos_mju,precio_mju,logo_mju,tipo_logo_mju FROM MejorasJugador WHERE afecta_todos=1;");
        $stmt->bind_result($id,$name,$score,$price,$logo,$type);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="" src="data:image/',$type,';base64,'.base64_encode($logo).'">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">',$name,'</h4>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ', number_format($price,0,"","."),'</h5>
                            <h4 class="text-center"><small>THIS WILL INCREASE YOUR TEAM SCORE IN A ',$score,'% FOR 1 ROUND</small></h4>                             
                        </div>
                        <button class="btn btn-primary center-block buy_tm_upgrade" value="',$id,'">BUY</button> 
                    </div>
                </div>';
        }
        $stmt->close();
        $db->close();
    }
	
	public function GetTeamFullStamina()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idMejorasJugador,nombre_mju,puntos_mju,precio_mju,logo_mju,tipo_logo_mju FROM MejorasJugador WHERE afecta_todos=1;");
        $stmt->bind_result($id,$name,$score,$price,$logo,$type);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
			$price = 30000;
			$name = "100% STAMINA FOR YOUR PLAYERS";
            echo '<div class="col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="" src="data:image/',$type,';base64,'.base64_encode($logo).'">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">',$name,'</h4>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ', number_format($price,0,"","."),'</h5>
                            <h4 class="text-center"><small>THIS WILL RESTART ALL YOUR TITULAR PLAYERS STAMINA</small></h4>                             
                        </div>
                        <button class="btn btn-primary center-block buy_tm_upgrade" value="fullstamina">BUY</button> 
                    </div>
                </div>';
        }
        $stmt->close();
        $db->close();
    }
	
	
    public function GetPlayers($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id,$name,$number);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<option value="',$id,'">',$number,' - ',$name,'</option>';
        }  
        $stmt->close();
        $db->close();
    } 
    public function GetNews()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT titulo,texto,fecha FROM Noticias ORDER BY idNoticias DESC LIMIT 5;");
        $stmt->bind_result($title,$text,$date);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<div class="col-xs-12 news_box">
                    <hgroup>
                        <h3 class="nom-nop">',$title,' <small>',$date,'</small></h3>
                    </hgroup>
                    <div class="text">',$text,'</div>
                </div>';
        } 
        $stmt->close();
        $db->close();
    }      
    public function GetSubastablePlayers($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,numero_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND subasta=0;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_player,$name,$number);
        $stmt->execute();
        while($stmt->fetch())
        {
            echo '<option value="',$id_player,'">',$name,' (',$number,')</option>';
        }    
        $stmt->close();
        $db->close();
    }    
    public function GetTradePlayers($range,$pos)
    {
        require_once("../../models/class.Connection.php");
        require_once("../../models/class.Fn.php");
        $db=new Connection();
        $f=new Fn();
        $param=$range+1;
        $range=$range*12;
		$stringSearchByPos='';
		if($pos!=null)
		{
			$stringSearchByPos = "WHERE Jugadores.posicion_ju='".$pos."'";
		}
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju,posicion_ju,precio_su,puntos_ju FROM Jugadores INNER JOIN Subasta ON Jugadores.idJugadores=Subasta.Jugadores_idJugadores ".$stringSearchByPos."  ORDER BY precio_su ASC LIMIT ?,12;");
        $stmt->bind_param("i", $range);
        $stmt->bind_result($id,$name,$position,$price,$score);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-lg-2 col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="player_avatar" src="libs/images/player_avatar.png" alt="">
                        <div class="caption">
                            <h4 class="text-center nom-nop player_name">',ucfirst($name),'</h4>
                            <h5 class="text-center nom-nop player_info"><span>',$position,'</span> <span>',$score,'</span></h5>
                                <h5 class="text-center player_info"><span>',$f->GetPlayerLevel($score),'</span></h5>
                            <h5 class="text-center nom-nop player_price"><img src="libs/images/gold.png"> ',number_format($price,0,"","."),'</h5>
                            <div class="form-group">
                                <input type="number" name="shirt" class="form-control shirt',$id,'" placeholder="SHIRT N&deg;">
                            </div>
                            <button class="btn btn-primary center-block buy_trade" value="',$id,'">BUY</button>       
                        </div>
                    </div>
                </div>';
        }
        $stmt->prepare("SELECT COUNT(*) FROM Subasta;");
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if(($param*12)<$total)
        {
            echo '<div class="col-xs-12"><button class="btn btn-link center-block show_more" value="',$param,'"><span class="glyphicon glyphicon-chevron-down"></span></button></div>';
        }    
        $stmt->close();
        $db->close();
    }
	
	
	
	
    public function GetPlayersOnSubast($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idJugadores,nombre_ju FROM Equipos INNER JOIN Jugadores ON Equipos.idEquipos=Jugadores.Equipos_idEquipos INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE usuario=? AND subasta=1;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id_player,$name);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            
            echo '<form method="POST" action="#">
                    <div class="form-group">
                        <select name="player" class="form-control">';
            while($stmt->fetch())
            {
                echo '<option value="',$id_player,'">',$name,'</option>';
            }    
            echo '</select>
                    </div>
                    <button type="submit" class="btn btn-default center-block">REMOVE</button>
                </form>';
            $stmt->close();
            $db->close();
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<p class="alert alert-warning">You do not have players on the trade list.</p>';
        }    
    }        
    public function GetPacks()
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
		$stmt=$db->prepare("SELECT * FROM PacksJugador ORDER BY precio ASC;");
        $stmt->bind_result($id_pack,$jugadores,$coins,$price,$imagen);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-md-4 col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="img-responsive" src="libs/images/'.$imagen.'">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">Pack ',$jugadores,' Players</h4>
                            <h5 class="text-center nom-nop player_price">U$S ', number_format($price, 2,",","."),'</h5>
                            <h5 class="text-center nom-nop player_price">Coins ', number_format($coins, 0,",","."),'</h5>
                               
                        </div>
                        <div class="form-group">
                            <select class="form-control select',$id_pack,'">
                                <option value="0" hidden>Select a payment method ...</option>
                                <option value="1">Paypal</option>
                                <option value="2">Mercadopago</option>
                                <option value="3">Coins</option>
                            </select>
                        </div> 
                        <button class="btn btn-primary center-block buy_players" value="',$id_pack,'">BUY</button> 
                    </div>
                </div>';
        }
		
        $stmt=$db->prepare("SELECT * FROM PacksOro ORDER BY precio ASC;");
        $stmt->bind_result($id_pack,$coins,$price);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            echo '<div class="col-md-4 col-sm-6 col-xs-12 player_box">
                    <div class="thumbnail">
                        <img class="img-responsive" src="libs/images/coins_pack.png">
                        <div class="caption">
                            <h4 class="text-center upgrade_title">Pack ',$coins,' Coins</h4>
                            <h5 class="text-center nom-nop player_price">U$S ', number_format($price, 2,",","."),'</h5>
                               
                        </div>
                        <div class="form-group">
                            <select class="form-control select',$id_pack,'">
                                <option value="0" hidden>Select a payment method ...</option>
                                <option value="1">Paypal</option>
                                <option value="2">Mercadopago</option>
                            </select>
                        </div> 
                        <button class="btn btn-primary center-block buy_coins" value="',$id_pack,'">BUY</button> 
                    </div>
                </div>';
        }
        $stmt->close();
        $db->close();
    }   
    public function GetWithdrawalAccounts($user)
    {
        require_once("core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT email_paypal,email_mercadopago,email_neteller,oro FROM Equipos WHERE usuario=?;");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($emailp,$emailm,$emailn,$balance);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if(!empty($emailp) || !empty($emailm) || !empty($emailn))
        {
            echo '<form method="POST" action="#"><div class="form-group">
                    <select name="email" class="form-control">';
            if(!empty($emailp))
            {
                echo '<option value="1">Paypal - ',$emailp,'</option>';
            }    
            if(!empty($emailm))
            {
                echo '<option value="2">Mercadopago - ',$emailm,'</option>';
            }
            if(!empty($emailn))
            {
                echo '<option value="3">Neteller - ',$emailn,'</option>';
            }
            echo ' </select>
                </div>
                <div class="form-group">
                    <input type="number" min="140000" max="',$balance,'" class="form-control" name="coins" placeholder="AMOUNT OF COINS YOU WOULD LIKE TO EXCHANGE" required>
                </div>
                <div class="withdrawal_values">
                    <hgroup>
                        <h5>EQUALS: $<span class="equals">0.00</span></h5>
                        <h5>TAX: $<span class="tax">0.00</span></h5>
                        <h5>TOTAL: $<span class="total">0.00</span></h5>
                    </hgroup>
                </div>
                <button type="submit" class="btn btn-default center-block" id="button_submit">WITHDRAWAL</button></form>';
        }
        else
        {
            echo '<p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> You have to add a withdrawal account first to withdrawal your founds, you can do it <u><a href="withdrawal_methods">here</a></u>.</p>';
        }    
        
    }        
	
	
}

