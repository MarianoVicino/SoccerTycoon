<?php
class Fn
{
    public function Validar_Long($cadena,$min,$max)
    {
        if(strlen($cadena)>=$min && strlen($cadena)<=$max)
        {
            return 1;
        }
        else
        {
            return 0;
        }    
    }
    public function Validate_Price($number)
    {
        if(floatval($number)>0)
        {
            for($i=0;$i<strlen($number);$i++)
            {
                if($number[$i]===',')
                {
                    $number[$i]='.';
                    break;
                }   
            }
            if(is_numeric($number))
            {
                return floatval($number);
            }    
            else
            {
                return -1;
            } 
        }
        else
        {
            return -1;
        }    
    }
    public function Validate_Img($imagenes)
    {
        $arr=[];
        if($imagenes['image1']['size']>0)
        {    
            for($i=1;$i<=sizeof($imagenes);$i++)
            {
                $type=pathinfo($imagenes['image'.$i]['name'], PATHINFO_EXTENSION);
                if($imagenes['image'.$i]['size']>0 && ($type==='jpg' || $type==='png'))
                {
                    array_push($arr, $imagenes['image'.$i]);
                }        
            }
        }
        return $arr;   
    }
    public function DeterminatePlayerGroup($position)
    {
        $defensive=array("SW","CB","WLB","WRB","FLB","FRB");
        $midfield=array("CM","MRB","MLB","DM","OM","SLM","SRM");
        $attack=array("RW","LW","IF","CF","HO");
        if($position==="GK")
        {
            return 0;
        }
        else if(in_array($position, $defensive))
        {
            return 1;
        }
        else if(in_array($position, $midfield))
        {
            return 2;
        }
        else if(in_array($position, $attack))
        {
            return 3;
        }
        else
        {
            return -1;
        }    
    }
	
	public function InsertHistoric($team, $type, $meta){
		$meta = implode(',', $meta);
		$db = new Connection();
		$stmt=$db->prepare("INSERT INTO historico_data (Equipos_idEquipos, Historico_Textos_id, metadata, date) VALUES (?,?,?,?);");
        $date = time();
		$stmt->bind_param("iisi", $team, $type, $meta, $date);
        $stmt->execute();
	}
	
    public function GetTeamScore($id_team,$formation)
    {
        $db2=new Connection();
        switch($formation)
        {
            // 4-4-2
            case 1:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"CM",
                    7=>"CM",
                    8=>"SLM",
                    9=>"SRM",
                    10=>"CF",
                    11=>"CF");
            break;
             // 4-3-2-1
            case 2:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"CM",
                    7=>"CM",
                    8=>"CM",
                    9=>"OM",
                    10=>"OM",
                    11=>"CF");
            break;    
             // 4-1-2-1-2
            case 3:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"SLM",
                    7=>"DM",
                    8=>"SRM",
                    9=>"OM",
                    10=>"CF",
                    11=>"CF");
            break;    
             // 4-3-3
            case 4:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"CM",
                    7=>"CM",
                    8=>"CM",
                    9=>"LW",
                    10=>"RW",
                    11=>"CF");
            break;    
             // 4-2-3-1
            case 5:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"DM",
                    7=>"DM",
                    8=>"SLM",
                    9=>"OM",
                    10=>"SRM",
                    11=>"CF");
            break; 
            // 4-2-4
            case 6:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"FLB",
                    5=>"FRB",
                    6=>"CM",
                    7=>"CM",
                    8=>"LW",
                    9=>"RW",
                    10=>"CF",
                    11=>"CF");
            break;
            // 5-4-1
            case 7:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"CB",
                    5=>"WLB",
                    6=>"WRB",
                    7=>"CM",
                    8=>"CM",
                    9=>"SLM",
                    10=>"SRM",
                    11=>"CF");
            break;
            // 3-4-3
            case 8:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"CB",
                    5=>"CM",
                    6=>"CM",
                    7=>"SLM",
                    8=>"SRM",
                    9=>"LW",
                    10=>"RW",
                    11=>"CF");
            break;
            // 5-3-2
            case 9:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"SW",
                    4=>"CB",
                    5=>"WLB",
                    6=>"WRB",
                    7=>"CM",
                    8=>"CM",
                    9=>"CM",
                    10=>"CF",
                    11=>"CF");
            break;
            // 2-3-5
            case 10:
                $ideal=array(
                    1=>"GK",
                    2=>"CB",
                    3=>"CB",
                    4=>"CM",
                    5=>"CM",
                    6=>"CM",
                    7=>"IF",
                    8=>"IF",
                    9=>"LW",
                    10=>"RW",
                    11=>"CF");
            break;
        }
        $stmt_ti=$db2->prepare("SELECT posicion_ju,puntos_ju,cansancio_ju FROM Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE Equipos_idEquipos=? ORDER BY orden ASC;");
        $stmt_ti->bind_param("i", $id_team);
        $stmt_ti->bind_result($position_ju,$score_ju,$cansancio_ju);
        $stmt_ti->execute();
        $stmt_ti->store_result();
        $i=1;
        $team_score=0;
        while($stmt_ti->fetch())
        {
			$score_ju =floor($score_ju*$cansancio_ju/100);
            if($position_ju===$ideal[$i])
            {
                $team_score+=$score_ju;
            } 
            else
            {
                $score_ju=$score_ju*0.5;
                $team_score+=$score_ju;
            }
            $i++;
        }    
        $stmt_ti=$db2->prepare("SELECT puntos_mju FROM Equipos_has_MejorasJugador INNER JOIN MejorasJugador ON MejorasJugador_idMejorasJugador=idMejorasJugador WHERE Equipos_idEquipos=?;");
        $stmt_ti->bind_param("i", $id_team);
        $stmt_ti->bind_result($percent);
        $stmt_ti->execute();
        $stmt_ti->store_result();
        $total=0;
        while($stmt_ti->fetch())
        {
            $total=$total+$percent;
        }    
        $stmt_ti->close();
        $db2->close();
        return intval($team_score+($team_score*($total/100)));
    }
	
	public function StaminaFunction($team, $ghost = 0){
        $db = new Connection();
        $db2 = new Connection();
        $stmt=$db->prepare("SELECT idJugadores,cansancio_ju FROM Jugadores INNER JOIN Titulares ON Jugadores.idJugadores=Titulares.Jugadores_idJugadores WHERE Equipos_idEquipos=? ORDER BY orden ASC;");
        $stmt->bind_param("i", $team);
        $stmt->bind_result($idJugador,$player_stamina);
        $stmt->execute();
		while($stmt->fetch())
        {
			if($ghost){
				$new_stamina = 100;
			}else{
				$new_stamina = max(0, $player_stamina-5);
			}
            $tstmt=$db2->prepare("UPDATE Jugadores SET cansancio_ju=? WHERE Equipos_idEquipos=? AND idJugadores=?;");
			$tstmt->bind_param("iii", $new_stamina,$team,$idJugador);
			$tstmt->execute();
        }
		
		$stmt->prepare("SELECT idJugadores,cansancio_ju FROM Jugadores INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores WHERE Equipos_idEquipos=?;");
        $stmt->bind_param("i", $team);
        $stmt->bind_result($idJugador,$player_stamina);
        $stmt->execute();
		while($stmt->fetch())
        {
			$new_stamina = min(100, $player_stamina+10);
            $tstmt=$db2->prepare("UPDATE Jugadores SET cansancio_ju=? WHERE Equipos_idEquipos=? AND idJugadores=?;");
			$tstmt->bind_param("iii", $new_stamina,$team,$idJugador);
			$tstmt->execute();
        }
	}
	
    public function BuildFixture($names) 
    { 
        $teams = sizeof($names);
        $totalRounds = $teams - 1;
        $matchesPerRound = $teams / 2;
        $rounds = array();
        for ($i = 0; $i < $totalRounds; $i++) {
            $rounds[$i] = array();
        }
        for ($round = 0; $round < $totalRounds; $round++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $home = ($round + $match) % ($teams - 1);
                $away = ($teams - 1 - $match + $round) % ($teams - 1);
                if ($match == 0) {
                    $away = $teams - 1;
                }
                $rounds[$round][$match] = array($names[$home],$names[$away]);
            }
        }
        $interleaved = array();
        for ($i = 0; $i < $totalRounds; $i++) {
            $interleaved[$i] = array();
        }
        $evn = 0;
        $odd = ($teams / 2);
        for ($i = 0; $i < sizeof($rounds); $i++) {
            if ($i % 2 == 0) {
                $interleaved[$i] = $rounds[$evn++];
            } else {
                $interleaved[$i] = $rounds[$odd++];
            }
        }
        $rounds = $interleaved;
         for ($round = 0; $round < sizeof($rounds); $round++) {
            if ($round % 2 == 1) {
                $temp=$rounds[$round][0][0];
                $rounds[$round][0][0]=$rounds[$round][0][1];
                $rounds[$round][0][1]=$temp;
            }
        }
        for($i = $totalRounds; $i < $totalRounds*2; $i++)
        {
            $rounds[$i] = array();
        }
        $j=0;
        for ($round = $totalRounds; $round < $totalRounds*2; $round++) 
        {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $rounds[$round][$match][0] = $rounds[$j][$match][1];
                $rounds[$round][$match][1] = $rounds[$j][$match][0];
            }
            $j++;
        }
        return $rounds;
    }
    public function GetPlayerScoreOnField($pos_field,$pos_player,$score,$stamina=100)
    {
		$score = ceil($score*$stamina/100);
        if($pos_field===$pos_player)
        {
            echo '<span class="score">[',$score,']</span>';
        }
        else
        {
            echo '<span class="score">[',intval($score/2),']</span>';
        }    
    }
	

    public function GetPlayerLoses($pos_field,$pos_player)
    {
		echo '<div class="originalpos">'.$pos_player.'</div>';
        if($pos_field==$pos_player)
        {
            echo '<div class="lose hab100">100</div>';
        }
        else
        {
            echo '<div class="lose hab50">50</div>';
        }    
    }
	public function GetPlayerTired($qty){
		if($qty == 100)
			return;
		
		echo '<div class="tired">'.$qty.'%</div>';
	}
    public function GetDivisionAwards($range_div,$gold)
    {
        switch ($range_div) 
        {
            case 1:
                $awards=array(
                    "match_winner_award" => intval($gold*0.005),
                    "champion_award" => $gold,
                    "second_award" => intval($gold*0.27),
                    "rest_award" => intval($gold*0.035),
                );
            break;
            case 2:
                $awards=array(
                    "match_winner_award" => intval($gold*0.01),
                    "champion_award" => $gold,
                    "second_award" => intval($gold*0.2),
                    "rest_award" => intval($gold*0.05),
                );
            break;
            case 3:
                $awards=array(
                    "match_winner_award" => intval($gold*0.015),
                    "champion_award" => $gold,
                    "second_award" => intval($gold*0.3),
                    "rest_award" => intval($gold*0.1),
                );
            break;
            case 4:
                $awards=array(
                    "match_winner_award" => intval($gold*0.01),
                    "champion_award" => $gold,
                    "second_award" => intval($gold*0.3),
                    "rest_award" => intval($gold*0.1),
                );
            break;

            default:
                $awards=array();
            break;
        }
        return $awards;
    }     
    public function GetPlayerLevel($score)
    {
        if($score<=5000)
        {
            return "LEVEL 1";
        }
        else if($score>=5001 && $score<=15000)
        {
            return "LEVEL 2";
        }    
        else if($score>=15001 && $score<=25000)
        {
            return "LEVEL 3";
        }    
        else
        {
            return "LEVEL 4";
        }    
    }    
	
		public function GetPlayerLevelFormated($score)
		{
			  $option = $this->GetPlayerLevel($score);
			  switch($option)
			  {
				  case 'LEVEL 1':
					  return '<span class="score">[L1]</span>';
					  break;
				  case 'LEVEL 2':
					  return '<span class="score">[L2]</span>';
					  break;
				  case 'LEVEL 3':
					  return '<span class="score">[L3]</span>';
					  break;
			  }
		}  
	
	
	
    public function GoalFunction($score1,$score2)
    {
        if($score1>$score2)
        {
            //gana local
            if(($score1-$score2)>5000)
            {
                $max=7;
            }
            else
            {
                $max=4;
            }
            $goles_ganador=rand(1,$max);
            $goles_perdedor=rand(0,$goles_ganador-1);
            $match=array(
                    "home_winner" => 1, 
                    "guest_winner" => 0,
                    "tie" => 0,
                    "goals_winner" => $goles_ganador,
                    "goals_loser" => $goles_perdedor
                );
            return $match;
        }
        else if($score1<$score2)
        {
            //gana visitante
            if(($score2-$score1)>5000)
            {
                $max=7;
            }
            else
            {
                $max=4;
            }
            $goles_ganador=rand(1,$max);
            $goles_perdedor=rand(0,$goles_ganador-1);
            $match=array(
                    "home_winner" => 0, 
                    "guest_winner" => 1,
                    "tie" => 0,
                    "goals_winner" => $goles_ganador,
                    "goals_loser" => $goles_perdedor
                );
            return $match;
        }
        else 
        {
            //empate
            $goles=rand(0,4);
            $match=array(
                    "home_winner" => 0, 
                    "guest_winner" => 0,
                    "tie" => 1,
                    "goals_winner" => $goles,
                    "goals_loser" => $goles
                );
            return $match;
        }
    }        
}
?>
