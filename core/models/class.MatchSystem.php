<?php
class MatchSystem
{
    public function DeleteMatchsAndRounds($id_league)
    {
        $db_clean=new Connection();
        $stmt_clean=$db_clean->prepare("SELECT idFechas FROM Fechas WHERE Ligas_idLigas=?;");
        $stmt_clean->bind_param("i", $id_league);
        $stmt_clean->bind_result($id_round);
        $stmt_clean->execute();
        $stmt_clean->store_result();
        while($stmt_clean->fetch())
        {
            //BORRO TODOS LOS PARTIDOS DE CADA FECHA
            $stmt_clean2=$db_clean->prepare("DELETE FROM Partidos WHERE Fechas_idFechas=?;");
            $stmt_clean2->bind_param("i", $id_round);
            $stmt_clean2->execute();
            $stmt_clean2->close();
        } 
        //ELIMINO TODAS LAS FECHAS
        $stmt_clean=$db_clean->prepare("DELETE FROM Fechas WHERE Ligas_idLigas=?;");
        $stmt_clean->bind_param("i", $id_league);
        $stmt_clean->execute();
        $stmt_clean->close();
        $db_clean->close();
    } 
    public function GetFourWinners($id_league)
    {
        $db_winners=new Connection();
        $winners=array();
        $stmt_winners=$db_winners->prepare("SELECT Equipos_idEquipos FROM Posiciones WHERE Ligas_idLigas=? ORDER BY puntos DESC,(goles_favor-goles_contra) DESC LIMIT 4;");
        $stmt_winners->bind_param("i", $id_league);
        $stmt_winners->bind_result($team);
        $stmt_winners->execute();
        $stmt_winners->store_result();
        while($stmt_winners->fetch())
        {    
            $winners[]=$team;
        }
        $stmt_winners->close();
        $db_winners->close();
        return $winners;
    }
    public function GetFourLosers($id_league)
    {
        $db_lo=new Connection();
        $losers=array();
        $stmt_lo=$db_lo->prepare("SELECT Equipos_idEquipos FROM Posiciones WHERE Ligas_idLigas=? ORDER BY puntos ASC,(goles_favor-goles_contra) ASC LIMIT 4;");
        $stmt_lo->bind_param("i", $id_league);
        $stmt_lo->bind_result($team);
        $stmt_lo->execute();
        $stmt_lo->store_result();
        while($stmt_lo->fetch())
        {
            $losers[]=$team;
        }
        $stmt_lo->close();
        $db_lo->close();
        return $losers;
    }
    public function AddGhostToLeague($id_league,$n_teams)
    {
        $f=new Fn();
        $dbg=new Connection();
        $stmt0=$dbg->prepare("SELECT COUNT(*) FROM Equipos;");
        $stmt0->bind_result($sufijo_fantasma);
        $stmt0->execute();
        $stmt0->store_result();
        $stmt0->fetch();
        $stmt0->close();
        for($i=1;$i<=$n_teams;$i++)
        {
            $nombre="Team ".$sufijo_fantasma;
            $stmt=$dbg->prepare("INSERT INTO Equipos (nombre,score,oro,fantasma,formacion,nombre_formacion,Ligas_idLigas) VALUES (?,0,1000,1,1,'4-4-2',?);");
            $stmt->bind_param("si", $nombre,$id_league);
            $stmt->execute();
            $sufijo_fantasma=$stmt->insert_id;  
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
                $stmt2=$dbg->prepare("SELECT nombre_jg,numero_jg,posicion_jg,puntos_jg FROM JugadoresGratis WHERE grupo_jg=? ORDER BY RAND() LIMIT ?;");
                $stmt2->bind_param("ii", $j,$limit);
                $stmt2->bind_result($name_jg,$number_jg,$position_jg,$score_jg);
                $stmt2->execute();
                $stmt2->store_result();
                while($stmt2->fetch())
                {
                    $stmt3=$dbg->prepare("INSERT INTO Jugadores (nombre_ju,numero_ju,posicion_ju,puntos_ju,grupo_ju,Equipos_idEquipos) VALUES (?,?,?,?,?,?);");
                    $stmt3->bind_param("sisiii", $name_jg,$number_jg,$position_jg,$score_jg,$j,$sufijo_fantasma);
                    $stmt3->execute();
                }
            }
            //CREO ESTADIO DE CADA EQUIPO
            $name_stadium=$nombre." Stadium";
            $stmt4=$dbg->prepare("INSERT INTO Estadios (nombre_es,capacidad_es,ingresos_es,Equipos_idEquipos) VALUES (?,5000,10,?);");
            $stmt4->bind_param("si",$name_stadium,$sufijo_fantasma);
            $stmt4->execute();
            // CARGAR TITULARES Y SUPLENTES
            $stmt4=$dbg->prepare("SELECT idJugadores FROM Jugadores WHERE Equipos_idEquipos=? ORDER BY RAND();");
            $stmt4->bind_param("i", $sufijo_fantasma);
            $stmt4->bind_result($id_player);
            $stmt4->execute();
            $stmt4->store_result();
            $flag=1;
            while($stmt4->fetch())
            {
                if($flag<=11)
                {
                    $stmt5=$dbg->prepare("INSERT INTO Titulares (orden,Jugadores_idJugadores) VALUES (?,?);");
                    $stmt5->bind_param("ii",$flag,$id_player);
                    $stmt5->execute();
                }
                else
                {
                    $stmt5=$dbg->prepare("INSERT INTO Suplentes (Jugadores_idJugadores) VALUES (?);");
                    $stmt5->bind_param("i",$id_player);
                    $stmt5->execute();
                } 
                $flag++;
            }
            // CALCULAR SCORING DEL EQUIPO
            $stmt5=$dbg->prepare("UPDATE Equipos SET score=? WHERE idEquipos=?;");
            $fix11=$f->GetTeamScore($sufijo_fantasma,1);
            $stmt5->bind_param("ii", $fix11,$sufijo_fantasma);
            $stmt5->execute();
        }
        $stmt->close();
        $stmt2->close();
        $stmt3->close();
        $stmt4->close();
        $stmt5->close();
        $dbg->close();
    }  
    public function PayAwards($id_league)
    {
        $dbp=new Connection();
        //OBTENGO EL RANGO Y EL ORO
        $stmtp=$dbp->prepare("SELECT rango_div,precio_partido FROM Divisiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones WHERE idLigas=?;");
        $stmtp->bind_param("i", $id_league);
        $stmtp->bind_result($range_div_award,$gold_award);
        $stmtp->execute();
        $stmtp->store_result();
        $stmtp->fetch();
        //OBTENGO Y CALCULO LOS PREMIOS
        $fp=new Fn();
        $awards=$fp->GetDivisionAwards($range_div_award, $gold_award);
        // ELIJO LOS EQUIPOS QUE TIENEN PREMIO POR COBRAR
        $stmtp=$dbp->prepare("SELECT Equipos_idEquipos FROM Posiciones WHERE Ligas_idLigas=? ORDER BY puntos DESC,(goles_favor-goles_contra) DESC LIMIT 26;");
        $stmtp->bind_param("i", $id_league);
        $stmtp->bind_result($team_topay);
        $stmtp->execute();
        $stmtp->store_result();
        $k=1;
        while($stmtp->fetch())
        {
            if($k==1)
            {
                $award=$awards["champion_award"];
            }    
            else if($k==2)
            {
                $award=$awards["second_award"];
            }
            else
            {
                $award=$awards["rest_award"];
            }    
            $stmtp2=$dbp->prepare("UPDATE Equipos SET oro=oro+? WHERE idEquipos=?;");
            $stmtp2->bind_param("ii", $award,$team_topay);
            $stmtp2->execute();
            $stmtp2->close();
            $k++;
			
			$f = new Fn();
			$meta = array($award . ' Coins');
			$f->InsertHistoric($team_topay, 8, $meta);
        }
        $stmtp->close();
        $dbp->close();
    }
    public function PayToPlayers($id_league)
    {
        $dbpl=new Connection();
        $stmtm=$dbpl->prepare("SELECT idEquipos FROM Equipos WHERE fantasma=0 AND Ligas_idLigas=?;");
        $stmtm->bind_param("i", $id_league);
        $stmtm->bind_result($id_teampl);
        $stmtm->execute();
        $stmtm->store_result();
        while($stmtm->fetch())
        {
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
            // CALCULO EL TOTAL DE LOS SALARIOS Y LO DEBITO
            $charge=(29*$total_a*11)+(29*$total_b*13)+(29*$total_c*46)+(29*$total_d*77)+(29*$total_e*150);
            $stmtpl=$dbpl->prepare("UPDATE Equipos SET oro=oro-? WHERE idEquipos=?;");
            $stmtpl->bind_param("ii", $charge,$id_teampl);
            $stmtpl->execute();
            $stmtpl->close();
			
			$f = new Fn();
			$meta = array($charge . ' Coins');
			$f->InsertHistoric($charge, 9, $meta);
        }
        $stmtm->close();
        $dbpl->close();
    }
    public function DeleteTable($id_league)
    {
        $dbdt=new Connection();
        $stmtdt=$dbdt->prepare("DELETE FROM Posiciones WHERE Ligas_idLigas=?;");
        $stmtdt->bind_param("i", $id_league);
        $stmtdt->execute();
        $stmtdt->close();
        $dbdt->close();
    } 
    public function CreateTablesAndMatches($id_league)
    {
        $dbc=new Connection();
        $fc=new Fn();
        $teamsc=array();
        //BUSCO LOS EQUIPOS QUE HAY EN LA LIGA
        $stmtc=$dbc->prepare("SELECT idEquipos FROM Equipos WHERE Ligas_idLigas=?;");
        $stmtc->bind_param("i", $id_league);
        $stmtc->bind_result($idteamc);
        $stmtc->execute();
        $stmtc->store_result();
        while($stmtc->fetch())
        {
            $teamsc[]=$idteamc;
        }
        $stmtc->close();
        //ARMO FIXTURE
        $rounds=$fc->BuildFixture($teamsc);
        for($i=0;$i<count($rounds);$i++)
        {
            $round_name="Round ".($i+1);
            $stmt5=$dbc->prepare("INSERT INTO Fechas (nombre_fecha,terminada,Ligas_idLigas) VALUES (?,0,?);");
            $stmt5->bind_param("si", $round_name,$id_league);
            $stmt5->execute();
            $stmt5->store_result();
            $id_round=$stmt5->insert_id;
            // CARGAR PARTIDOS
            for($j=0;$j<count($rounds[$i]);$j++)
            {
                $stmt6=$dbc->prepare("INSERT INTO Partidos (local,visitante,Fechas_idFechas) VALUES (?,?,?);");
                $stmt6->bind_param("iii", $rounds[$i][$j][0],$rounds[$i][$j][1],$id_round);
                $stmt6->execute();
            }
        }
        // CARGAR POSICIONES
        for($i=0;$i<count($teamsc);$i++)
        {
            $stmt6=$dbc->prepare("INSERT INTO Posiciones (puntos,partidos_jugados,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra,Ligas_idLigas,Equipos_idEquipos) VALUES (0,0,0,0,0,0,0,?,?);");
            $stmt6->bind_param("ii", $id_league,$teamsc[$i]);
            $stmt6->execute();
        }
        $stmt5->close();
        $stmt6->close();
    }        
}
?>