<?php
require_once("../core/models/class.Connection.php");
require_once("../core/models/class.Fn.php");
require_once("../core/models/class.MatchSystem.php");
$db=new Connection();
$match_sys=new MatchSystem();

//AGARRO TODAS LAS LIGAS
$stmt_season=$db->prepare("SELECT idLigas,terminada,rango_div,precio_partido,id_ultima,id_tercera,id_segunda,id_primera,idRegiones,equipos_reales FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones INNER JOIN Ligas ON Divisiones.idDivisiones=Ligas.Divisiones_idDivisiones;");
$stmt_season->bind_result($id_league,$terminada,$range_div,$gold,$id_ultima,$id_tercera,$id_segunda,$id_primera,$id_region,$real_teams);
$stmt_season->execute();
$stmt_season->store_result();
while($stmt_season->fetch())
{    
    // SI LA LIGA NO ESTA TERMINADA, HAY QUE JUGAR PARTIDOS
    if($terminada==0)
    {
        // BUSCO LAS FECHAS QUE NO SE JUGARON Y SELECCIONO LA PRIMERA
        $stmt_round=$db->prepare("SELECT idFechas,nombre_fecha FROM Fechas WHERE terminada=0 AND Ligas_idLigas=? LIMIT 1;");
        $stmt_round->bind_param("i", $id_league);
        $stmt_round->bind_result($id_round,$name_round);
        $stmt_round->execute();
        $stmt_round->store_result();
        if($stmt_round->num_rows>0)
        {
            $stmt_round->fetch();
            //LLAMO A LA CLASE FN
            require_once("../core/models/class.Fn.php");
            $f=new Fn();
            // BUSCO LOS PREMIOS DE LA DIVISION
            $award=$f->GetDivisionAwards($range_div, $gold);
            // BUSCO LOS PARTIDOS DE LA FECHA
            $stmt_match=$db->prepare("SELECT local,visitante,idPartidos FROM Partidos WHERE Fechas_idFechas=?;");
            $stmt_match->bind_param("i", $id_round);
            $stmt_match->bind_result($id_home,$id_guest,$id_match);
            $stmt_match->execute();
            $stmt_match->store_result();
            while($stmt_match->fetch())
            {
                //TRAIGO LA INFORMACION DE LOS EQUIPOS
                //INFORMACION DEL LOCAL
                $stmt_team=$db->prepare("SELECT nombre,oro,score,ingresos_es,formacion,fantasma FROM Equipos INNER JOIN Estadios ON Equipos.idEquipos=Estadios.Equipos_idEquipos WHERE idEquipos=?;");
                $stmt_team->bind_param("i", $id_home);
                $stmt_team->bind_result($name_local,$balance_local,$score_local,$stadium_revenue,$formation_local,$ghost_local);
                $stmt_team->execute();
                $stmt_team->store_result();
                $stmt_team->fetch();
                //INFORMACION DEL VISITANTE
                $stmt_team=$db->prepare("SELECT nombre,oro,score,formacion,fantasma FROM Equipos WHERE idEquipos=?;");
                $stmt_team->bind_param("i", $id_guest);
                $stmt_team->bind_result($name_guest,$balance_guest,$score_guest,$formation_guest,$ghost_guest);
                $stmt_team->execute();
                $stmt_team->store_result();
                $stmt_team->fetch();
                $stmt_team->close();
                // OBTENGO GANADOR DE PARTIDO Y GOLES EN BASE A LOS SCORES
                $match=$f->GoalFunction($score_local, $score_guest);
				$f->StaminaFunction($id_home, $ghost_local);
				$f->StaminaFunction($id_guest, $ghost_guest);
				//GANO EL LOCAL
                if($match["home_winner"]==1)
                {
                    //CARGO EL NUEVO BALANCE DEL EQUIPO LOCAL
                    $balance_local=$balance_local+$award["match_winner_award"]+$stadium_revenue;
                    $stmt_balance=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt_balance->bind_param("ii", $balance_local,$id_home);
                    $stmt_balance->execute();
                    $stmt_balance->close();
					if($ghost_local == 0){
						$meta = array($award["match_winner_award"] . ' St', $name_guest);
						$f->InsertHistoric($id_home, 6, $meta);
					}
                    //AL VISITANTE NO SE LE DA NADA YA QUE PERDIO Y NO ES LOCAL
                    //CARGO A LA TABLA LA INFO DEL LOCAL QUE GANO
                    $stmt_table=$db->prepare("UPDATE Posiciones SET puntos=puntos+3, partidos_jugados=partidos_jugados+1,partidos_ganados=partidos_ganados+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_winner"],$match["goals_loser"],$id_league,$id_home);
                    $stmt_table->execute();
                    //CARGO A LA TABLA LA INFO DEL VISITANTE QUE PERDIO
                    $stmt_table=$db->prepare("UPDATE Posiciones SET partidos_jugados=partidos_jugados+1,partidos_perdidos=partidos_perdidos+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_loser"],$match["goals_winner"],$id_league,$id_guest);
                    $stmt_table->execute();
                    $stmt_table->close();
                    //CARGO LOS RESULTADOS
                    $stmt_match2=$db->prepare("UPDATE Partidos SET goles_local=?,goles_visitante=? WHERE idPartidos=?;");
                    $stmt_match2->bind_param("iii",$match["goals_winner"],$match["goals_loser"],$id_match);
                    $stmt_match2->execute();
                    $stmt_match2->close();
                }
                //GANO EL VISITANTE
                else if($match["guest_winner"]==1)
                {
                    //CARGO EL MONTO QUE SE GANO POR ENTRADAS A ESTADIO DEL LOCAL
                    $balance_local=$balance_local+$stadium_revenue;
                    $stmt_balance=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt_balance->bind_param("ii", $balance_local,$id_home);
                    $stmt_balance->execute();
                    //LE PAGO EL PARTIDO AL VISITANTE YA QUE GANO
                    $balance_guest=$balance_guest+$award["match_winner_award"];
                    $stmt_balance=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt_balance->bind_param("ii", $balance_guest,$id_guest);
                    $stmt_balance->execute();
                    $stmt_balance->close();
					if($ghost_guest == 0){
						$meta = array($award["match_winner_award"] . ' St', $name_local);
						$f->InsertHistoric($id_guest, 6, $meta);
					}
					
                    //CARGO A LA TABLA LA INFO DEL LOCAL QUE PERDIO
                    $stmt_table=$db->prepare("UPDATE Posiciones SET partidos_jugados=partidos_jugados+1,partidos_perdidos=partidos_perdidos+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_loser"],$match["goals_winner"],$id_league,$id_home);
                    $stmt_table->execute();
                    //CARGO A LA TABLA LA INFO DEL VISITANTE QUE GANO
                    $stmt_table=$db->prepare("UPDATE Posiciones SET puntos=puntos+3, partidos_jugados=partidos_jugados+1,partidos_ganados=partidos_ganados+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_winner"],$match["goals_loser"],$id_league,$id_guest);
                    $stmt_table->execute();
                    $stmt_table->close();
                    //CARGO LOS RESULTADOS
                    $stmt_match2=$db->prepare("UPDATE Partidos SET goles_local=?,goles_visitante=? WHERE idPartidos=?;");
                    $stmt_match2->bind_param("iii",$match["goals_loser"],$match["goals_winner"],$id_match);
                    $stmt_match2->execute();
                    $stmt_match2->close();
                }
                //HUBO EMPATE
                else
                {
                    //CALCULO EL PRECIO DE UN EMPATE
                    $award_tie=intval($award["match_winner_award"]/2);
                    //CARGO EL MONTO QUE SE GANO POR ENTRADAS A ESTADIO DEL LOCAL Y LE PAGO LA MITAD DEL PREMIO POR PARTIDO
                    //$balance_local=$balance_local+$award_tie+$stadium_revenue;
                    $balance_local=$balance_local;
                    $stmt_balance=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt_balance->bind_param("ii", $balance_local,$id_home);
                    $stmt_balance->execute();
                    //LE PAGO LA MITAD DEL PREMIO AL VISITANTE YA QUE EMPATO
                    //$balance_guest=$balance_guest+$award_tie;
                    $balance_guest=$balance_guest;
                    $stmt_balance=$db->prepare("UPDATE Equipos SET oro=? WHERE idEquipos=?;");
                    $stmt_balance->bind_param("ii", $balance_guest,$id_guest);
                    $stmt_balance->execute();
                    $stmt_balance->close();
					if($ghost_local == 0){
						$meta = array($award_tie . ' St', $name_guest);
						$f->InsertHistoric($id_home, 7, $meta);
					}
					if($ghost_guest == 0){
						$meta = array($award_tie . ' St', $name_local);
						$f->InsertHistoric($id_guest, 7, $meta);
					}
                    //CARGO A LA TABLA EL PUNTO QUE SACO EL LOCAL (EMPATE)
                    $stmt_table=$db->prepare("UPDATE Posiciones SET puntos=puntos+1, partidos_jugados=partidos_jugados+1,partidos_empatados=partidos_empatados+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_winner"],$match["goals_winner"],$id_league,$id_home);
                    $stmt_table->execute();
                    //CARGO A LA TABLA EL PUNTO QUE SACO EL VISITANTE (EMPATE)
                    $stmt_table=$db->prepare("UPDATE Posiciones SET puntos=puntos+1, partidos_jugados=partidos_jugados+1,partidos_empatados=partidos_empatados+1,goles_favor=goles_favor+?,goles_contra=goles_contra+? WHERE Ligas_idLigas=? AND Equipos_idEquipos=?;");
                    $stmt_table->bind_param("iiii",$match["goals_winner"],$match["goals_winner"],$id_league,$id_guest);
                    $stmt_table->execute();
                    $stmt_table->close();
                    //CARGO LOS RESULTADOS
                    $stmt_match2=$db->prepare("UPDATE Partidos SET goles_local=?,goles_visitante=? WHERE idPartidos=?;");
                    $stmt_match2->bind_param("iii",$match["goals_winner"],$match["goals_winner"],$id_match);
                    $stmt_match2->execute();
                    $stmt_match2->close();
                }
				if($ghost_local == 0){
					$meta = array($stadium_revenue . ' St', $name_guest);
					$f->InsertHistoric($id_home, 5, $meta);
				}
                if($ghost_local==0)
                {
                    $stmt_upgrade=$db->prepare("DELETE FROM Equipos_has_MejorasJugador WHERE Equipos_idEquipos=?;");
                    $stmt_upgrade->bind_param("i", $id_home);
                    $stmt_upgrade->execute();
                    $stmt_upgrade=$db->prepare("UPDATE Equipos SET score=? WHERE idEquipos=?;");
                    $outscore=$f->GetTeamScore($id_home, $formation_local);
                    $stmt_upgrade->bind_param("ii",$outscore,$id_home);
                    $stmt_upgrade->execute();
                    $stmt_upgrade->close();
                } 
                if($ghost_guest==0)
                {
                    $stmt_upgrade=$db->prepare("DELETE FROM Equipos_has_MejorasJugador WHERE Equipos_idEquipos=?;");
                    $stmt_upgrade->bind_param("i", $id_guest);
                    $stmt_upgrade->execute();
                    $stmt_upgrade=$db->prepare("UPDATE Equipos SET score=? WHERE idEquipos=?;");
                    $outscore=$f->GetTeamScore($id_guest, $formation_guest);
                    $stmt_upgrade->bind_param("ii",$outscore,$id_guest);
                    $stmt_upgrade->execute();
                    $stmt_upgrade->close();
                }  
            }
            $stmt_match->close();
            //DOY POR TERMINADA LA FECHA.
            $stmt_round=$db->prepare("UPDATE Fechas SET terminada=1 WHERE idFechas=?;");
            $stmt_round->bind_param("i", $id_round);
            $stmt_round->execute();
            //ME FIJO SI ESTOY EN LA ULTIMA FECHA
            $stmt_last=$db->prepare("SELECT idFechas,nombre_fecha FROM Fechas WHERE terminada=0 AND Ligas_idLigas=? LIMIT 1;");
            $stmt_last->bind_param("i", $id_league);
            $stmt_last->execute();
            $stmt_last->store_result();
            if($stmt_last->num_rows==0)
            {
                $stmt_season_aux=$db->prepare("UPDATE Ligas SET terminada=1 WHERE idLigas=?;");
                $stmt_season_aux->bind_param("i", $id_league);
                $stmt_season_aux->execute();
                $stmt_season_aux->close();
            }
            $stmt_last->close();
        }
        $stmt_round->close();      
    }
    // SI LA LIGA ESTA TERMINADA, HAY QUE PAGAR PREMIOS, ASCENDER Y DESCENDER, ETC
    if($terminada==1)
    {   
        if($range_div==4)
        {
            if($id_tercera==-1)
            {
                //NO EXISTE TERCER LIGA DE RANGO 3
                // CONSIGO EL ID DE LA DIVISION DE RANGO 3
                $stmtl=$db->prepare("SELECT idDivisiones FROM Divisiones WHERE rango_div=3 AND Regiones_idRegiones=?;");
                $stmtl->bind_param("i", $id_region);
                $stmtl->bind_result($id_divrg3);
                $stmtl->execute();
                $stmtl->store_result();
                $stmtl->fetch();
                // CREO LA LIGA DE RANGO 3 y LA PONGO EN TERMINADA=2
                $name_new_league="League ".$id_league." - 3";
                $stmtl=$db->prepare("INSERT INTO Ligas (nombre_li,equipos_reales,full,id_ultima,terminada,Divisiones_idDivisiones) VALUES (?,0,0,?,2,?);");
                $stmtl->bind_param("sii", $name_new_league,$id_league,$id_divrg3);
                $stmtl->execute();
                $stmtl->store_result();
                $id_newtercera=$stmtl->insert_id;
                // CREO LA REFERENCIA 
                $stmtl=$db->prepare("UPDATE Ligas SET id_tercera=? WHERE idLigas=? OR idLigas=?;");
                $stmtl->bind_param("iii", $id_newtercera,$id_league,$id_newtercera);
                $stmtl->execute();
                // BORRO TODOS LOS PARTIDOS Y FECHAS DE LA LIGA DE RANGO 4
                $match_sys->DeleteMatchsAndRounds($id_league);
                // PAGO LOS PREMIOS
                $match_sys->PayAwards($id_league);
                // COBRAR SALARIOS
                $match_sys->PayToPlayers($id_league);
                // SACAR LOS 4 QUE ASCIENDEN
                $winners=$match_sys->GetFourWinners($id_league);
                // ASCIENDO A LOS 4 PUNTEROS A LA LIGA 3
                $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                $stmtl->bind_param("iiiii", $id_newtercera,$winners[0],$winners[1],$winners[2],$winners[3]);
                $stmtl->execute();
                // METO 26 EQUIPOS FANTASMAS A LA LIGA 3
                $match_sys->AddGhostToLeague($id_newtercera,26);
                // METO 4 FANTASMAS A LA LIGA 4
                $match_sys->AddGhostToLeague($id_league, 4);
                // BORRO LA TABLA DE LA LIGA 4
                $match_sys->DeleteTable($id_league);
                // CARGO FECHAS Y PARTIDOS EN LA LIGA 4
                $match_sys->CreateTablesAndMatches($id_league);
                // CARGO FECHAS Y PARTIDOS DE LA LIGA 3
                $match_sys->CreateTablesAndMatches($id_newtercera);
                // PONGO A LA LIGA 4 EN ESTADO TERMINADA=2 Y ABRO LUGAR PARA LOS NUEVOS EQUIPOS
                $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                $stmtl->bind_param("iiii", $winners[0],$winners[1],$winners[2],$winners[3]);
                $stmtl->bind_result($n_real);
                $stmtl->execute();
                $stmtl->store_result();
                $stmtl->fetch();
                if($n_real>0)
                {
                    //EN LOS GANADORES HAY JUGADORES REALES
                    $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=equipos_reales-?,full=0,terminada=2 WHERE idLigas=?;");
                    $stmtl->bind_param("ii",$n_real,$id_league);
                    $stmtl->execute();
                }
                else
                {    
                    // NO HAY JUGADORES REALES EN LOS GANADORES
                    $stmtl=$db->prepare("UPDATE Ligas SET terminada=2 WHERE idLigas=?;");
                    $stmtl->bind_param("i", $id_league);
                    $stmtl->execute(); 
                }
                $stmtl->close();
            }
            else
            {
                //EXISTE UNA TERCERA DIVISION
                if($id_segunda==-1)
                {
                    //NO EXISTE SEGUNDA LIGA DE RANGO 2
                    // CONSIGO EL ID DE LA DIVISION DE RANGO 2
                    $stmtl=$db->prepare("SELECT idDivisiones FROM Divisiones WHERE rango_div=2 AND Regiones_idRegiones=?;");
                    $stmtl->bind_param("i", $id_region);
                    $stmtl->bind_result($id_divrg2);
                    $stmtl->execute();
                    $stmtl->store_result();
                    $stmtl->fetch();
                    // CREO LA LIGA DE RANGO 2 y LA PONGO EN TERMINADA=2
                    $name_new_league="League ".$id_league." - 2";
                    $stmtl=$db->prepare("INSERT INTO Ligas (nombre_li,equipos_reales,full,id_ultima,id_tercera,terminada,Divisiones_idDivisiones) VALUES (?,0,0,?,?,2,?);");
                    $stmtl->bind_param("siii", $name_new_league,$id_league,$id_tercera,$id_divrg2);
                    $stmtl->execute();
                    $stmtl->store_result();
                    $id_newsegunda=$stmtl->insert_id;
                    // CREO LA REFERENCIA 
                    $stmtl=$db->prepare("UPDATE Ligas SET id_segunda=?,terminada=2 WHERE idLigas=? OR idLigas=? OR idLigas=?;");
                    $stmtl->bind_param("iiii",$id_newsegunda,$id_league,$id_tercera,$id_newsegunda);
                    $stmtl->execute();
                    // BORRO TODOS LOS PARTIDOS Y FECHAS DE LA LIGA DE RANGO 4 y 3
                    $match_sys->DeleteMatchsAndRounds($id_league);
                    $match_sys->DeleteMatchsAndRounds($id_tercera);
                    // PAGO LOS PREMIOS DE LIGA 4 Y 3
                    $match_sys->PayAwards($id_league);
                    $match_sys->PayAwards($id_tercera);
                    // COBRAR SALARIOS DE LIGA 4 Y 3
                    $match_sys->PayToPlayers($id_league);
                    $match_sys->PayToPlayers($id_tercera);
                    //ASCENSOS Y DESCENSOS//
                    // SACAR LOS 4 DE LA LIGA 3 QUE ASCIENDEN A LA LIGA 2
                    $winners_div3=$match_sys->GetFourWinners($id_tercera);
                    // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 3 A LA LIGA 2
                    $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                    $stmtl->bind_param("iiiii", $id_newsegunda,$winners_div3[0],$winners_div3[1],$winners_div3[2],$winners_div3[3]);
                    $stmtl->execute();
                    // METO 26 EQUIPOS FANTASMAS A LA LIGA 2
                    $match_sys->AddGhostToLeague($id_newsegunda,26);
                    // SACAR LOS 4 DE LA LIGA 4 QUE ASCIENDEN A LA LIGA 3
                    $winners_div4=$match_sys->GetFourWinners($id_league);
                    // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 4 A LA LIGA 3
                    $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                    $stmtl->bind_param("iiiii", $id_tercera,$winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                    $stmtl->execute();
                    // SACO A LOS 4 ULTIMOS DE LA LIGA 3 QUE DESCIENDEN A LA LIGA 4
                    $losers_div3=$match_sys->GetFourLosers($id_tercera);
                    // DESCIENDO A LOS 4 ULTIMOS DE LA LIGA 3 A LA LIGA 4
                    $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                    $stmtl->bind_param("iiiii", $id_league,$losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                    $stmtl->execute();
                    // METO 4 FANTASMAS A LA LIGA 3 PARA COMPLETAR
                    $match_sys->AddGhostToLeague($id_tercera,4);
                    // BORRO LA TABLA DE LA LIGA 4 Y 3
                    $match_sys->DeleteTable($id_league);
                    $match_sys->DeleteTable($id_tercera);
                    // CARGO FECHAS Y PARTIDOS EN LA LIGA 4,3 y 2
                    $match_sys->CreateTablesAndMatches($id_league);
                    $match_sys->CreateTablesAndMatches($id_tercera);
                    $match_sys->CreateTablesAndMatches($id_newsegunda);
                    //REVISO COMO QUEDARON LOS LUGARES EN LA LIGA 4
                    $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                    $stmtl->bind_param("iiii", $winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                    $stmtl->bind_result($teams_out4);
                    $stmtl->execute();
                    $stmtl->store_result();
                    $stmtl->fetch();
                    $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                    $stmtl->bind_param("iiii", $losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                    $stmtl->bind_result($teams_in4);
                    $stmtl->execute();
                    $stmtl->store_result();
                    $stmtl->fetch();
                    $real_teams=$real_teams-$teams_out4+$teams_in4;
                    if($real_teams==30)
                    {
                        $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=1,terminada=2 WHERE idLigas=?;");
                        $stmtl->bind_param("ii",$real_teams,$id_league);
                        $stmtl->execute();
                    }
                    else
                    {
                        $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=0,terminada=2 WHERE idLigas=?;");
                        $stmtl->bind_param("ii",$real_teams,$id_league);
                        $stmtl->execute();
                    }    
                    $stmtl->close();
                }
                else
                {
                    //EXISTE UNA SEGUNDA DIVISION
                    if($id_primera==-1)
                    {
                        //NO EXISTE SEGUNDA LIGA DE RANGO 1
                        // CONSIGO EL ID DE LA DIVISION DE RANGO 1
                        $stmtl=$db->prepare("SELECT idDivisiones FROM Divisiones WHERE rango_div=1 AND Regiones_idRegiones=?;");
                        $stmtl->bind_param("i", $id_region);
                        $stmtl->bind_result($id_divrg1);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $stmtl->fetch();
                        // CREO LA LIGA DE RANGO 1 y LA PONGO EN TERMINADA=2
                        $name_new_league="League ".$id_league." - 1";
                        $stmtl=$db->prepare("INSERT INTO Ligas (nombre_li,equipos_reales,full,id_ultima,id_tercera,id_segunda,terminada,Divisiones_idDivisiones) VALUES (?,0,0,?,?,?,2,?);");
                        $stmtl->bind_param("siiii", $name_new_league,$id_league,$id_tercera,$id_segunda,$id_divrg1);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $id_newprimera=$stmtl->insert_id;
                        // CREO LA REFERENCIA 
                        $stmtl=$db->prepare("UPDATE Ligas SET id_primera=?,terminada=2 WHERE idLigas=? OR idLigas=? OR idLigas=? OR idLigas=?;");
                        $stmtl->bind_param("iiiii",$id_newprimera,$id_league,$id_tercera,$id_segunda,$id_newprimera);
                        $stmtl->execute();
                        $stmtl->close();
                        // BORRO TODOS LOS PARTIDOS Y FECHAS DE LA LIGA DE RANGO 4, 3 y 2
                        $match_sys->DeleteMatchsAndRounds($id_league);
                        $match_sys->DeleteMatchsAndRounds($id_tercera);
                        $match_sys->DeleteMatchsAndRounds($id_segunda);
                        // PAGO LOS PREMIOS DE LIGA 4, 3 y 2
                        $match_sys->PayAwards($id_league);
                        $match_sys->PayAwards($id_tercera);
                        $match_sys->PayAwards($id_segunda);
                        // COBRAR SALARIOS DE LIGA 4, 3 y 2
                        $match_sys->PayToPlayers($id_league);
                        $match_sys->PayToPlayers($id_tercera);
                        $match_sys->PayToPlayers($id_segunda);
                        
                        //ASCENSOS Y DESCENSOS
                        $winners_div2=$match_sys->GetFourWinners($id_segunda);
                        // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 2 A LA LIGA 1
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_newprimera,$winners_div2[0],$winners_div2[1],$winners_div2[2],$winners_div2[3]);
                        $stmtl->execute();
                        // METO 26 EQUIPOS FANTASMAS A LA LIGA 1
                        $match_sys->AddGhostToLeague($id_newprimera,26);
                        
                        // SACO LOS PRIMEROS 4 DE LA LIGA 3 QUE ASCIENDEN A LA 2
                        $winners_div3=$match_sys->GetFourWinners($id_tercera);
                        // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 3 A LA LIGA 2
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_segunda,$winners_div3[0],$winners_div3[1],$winners_div3[2],$winners_div3[3]);
                        $stmtl->execute();
                        
                        // SACO LOS ULTIMOS 4 DE LA LIGA 2 Y LOS DESCIENDO A LA LIGA 3
                        $losers_div2=$match_sys->GetFourLosers($id_segunda);
                        // DESCIENDO A LOS 4 ULTIMOS DE LA LIGA 2 A LA LIGA 3
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_tercera,$losers_div2[0],$losers_div2[1],$losers_div2[2],$losers_div2[3]);
                        $stmtl->execute();
                        // AGREGO 4 FANTASMAS A LA LIGA 2
                        $match_sys->AddGhostToLeague($id_segunda,4);
                        
                        //SACO LOS PRIMEROS 4 DE LA LIGA 4 Y LOS ASCIENDO A LA LIGA 3
                        $winners_div4=$match_sys->GetFourWinners($id_league);
                        // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 4 A LA LIGA 3
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_tercera,$winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                        $stmtl->execute();
                        //SACO LOS ULTIMOS 4 DE LA LIGA 3 Y LOS DESCIENDO A LA LIGA 4
                        $losers_div3=$match_sys->GetFourLosers($id_tercera);
                        // DESCIENDO LOS ULTIMOS 4 DE LA LIGA 3 A LA LIGA 4
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_league,$losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                        $stmtl->execute();
                        
                        // BORRO LAS TABLAS DE LA LIGA 4, 3 y 2
                        $match_sys->DeleteTable($id_league);
                        $match_sys->DeleteTable($id_tercera);
                        $match_sys->DeleteTable($id_segunda);
                        // CARGO FECHAS Y PARTIDOS EN LA LIGA 4, 3, 2 y 1
                        $match_sys->CreateTablesAndMatches($id_league);
                        $match_sys->CreateTablesAndMatches($id_tercera);
                        $match_sys->CreateTablesAndMatches($id_segunda);
                        $match_sys->CreateTablesAndMatches($id_newprimera);
                        
                        //REVISO COMO QUEDARON LOS LUGARES EN LA LIGA 4
                        $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                        $stmtl->bind_param("iiii", $winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                        $stmtl->bind_result($teams_out4);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $stmtl->fetch();
                        $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                        $stmtl->bind_param("iiii", $losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                        $stmtl->bind_result($teams_in4);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $stmtl->fetch();
                        $real_teams=$real_teams-$teams_out4+$teams_in4;
                        if($real_teams==30)
                        {
                            $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=1,terminada=2 WHERE idLigas=?;");
                            $stmtl->bind_param("ii",$real_teams,$id_league);
                            $stmtl->execute();
                        }
                        else
                        {
                            $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=0,terminada=2 WHERE idLigas=?;");
                            $stmtl->bind_param("ii",$real_teams,$id_league);
                            $stmtl->execute();
                        }    
                        $stmtl->close();
                    }
                    else 
                    {
                        //EXISTEN TODAS LAS LIGAS, NO TENGO QUE CREAR NADA.
                        // LES COLOCO BANDERA PARA NO EJECUTARLAS 
                        $stmtl=$db->prepare("UPDATE Ligas SET terminada=2 WHERE idLigas=? OR idLigas=? OR idLigas=? OR idLigas=?;");
                        $stmtl->bind_param("iiii",$id_league,$id_tercera,$id_segunda,$id_primera);
                        $stmtl->execute();
                        // BORRO TODOS LOS PARTIDOS Y FECHAS DE LA LIGA DE RANGO 4, 3, 2 y 1
                        $match_sys->DeleteMatchsAndRounds($id_league);
                        $match_sys->DeleteMatchsAndRounds($id_tercera);
                        $match_sys->DeleteMatchsAndRounds($id_segunda);
                        $match_sys->DeleteMatchsAndRounds($id_primera);
                        // PAGO LOS PREMIOS DE LIGA 4, 3, 2 y 1
                        $match_sys->PayAwards($id_league);
                        $match_sys->PayAwards($id_tercera);
                        $match_sys->PayAwards($id_segunda);
                        $match_sys->PayAwards($id_primera);
                        // COBRAR SALARIOS DE LIGA 4, 3, 2 y 1
                        $match_sys->PayToPlayers($id_league);
                        $match_sys->PayToPlayers($id_tercera);
                        $match_sys->PayToPlayers($id_segunda);
                        $match_sys->PayToPlayers($id_primera);
                        
                        //LE ENTREGO EL TROFEO AL PRIMERO
                        $winners_div1=$match_sys->GetFourWinners($id_primera);
                        $stmt_trophy_aux=$db->prepare("SELECT fantasma FROM Equipos WHERE fantasma=0 AND idEquipos=?;");
                        $stmt_trophy_aux->bind_param("i", $winners_div1[0]);
                        $stmt_trophy_aux->execute();
                        $stmt_trophy_aux->store_result();
                        if($stmt_trophy_aux->num_rows>0)
                        {
                            $stmt_trophy=$db->prepare("INSERT INTO Trofeos (nombre,cantidad,fecha,Equipos_idEquipos) VALUES ('Trofeo',1,?,?);");
                            $stmt_trophy->bind_param("si", date("m/d/Y"),$winners_div1[0]);
                            $stmt_trophy->execute();
                            $stmt_trophy->close();
                        }
                        $stmt_trophy_aux->close();
                        
                        //ASCENSOS Y DESCENSOS
                        // SACO LOS PRIMEROS 4 DE LA LIGA 2 Y LOS ASCIENDO A LA LIGA 1
                        $winners_div2=$match_sys->GetFourWinners($id_segunda);
                        //SACO LOS ULTIMOS 4 DE LA LIGA 1 Y LOS DESCIENDO A LA LIGA 2
                        $losers_div1=$match_sys->GetFourLosers($id_primera);
                        //SACO LOS PRIMEROS 4 DE LA LIGA 3 Y LOS ASCIENDO A LA LIGA 2
                        $winners_div3=$match_sys->GetFourWinners($id_tercera);
                         //SACO LOS ULTIMOS 4 DE LA LIGA 2 Y LOS DESCIENDO A LA LIGA 3
                        $losers_div2=$match_sys->GetFourLosers($id_segunda);
                        //SACO LOS PRIMEROS 4 DE LA LIGA 4 Y LOS ASCIENDO A LA LIGA 3
                        $winners_div4=$match_sys->GetFourWinners($id_league);
                        //SACO LOS ULTIMOS 4 DE LA LIGA 3 Y LOS DESCIENDO A LA LIGA 4
                        $losers_div3=$match_sys->GetFourLosers($id_tercera);
                        
                        // ASCIENDO A LOS 4 PUNTEROS DE LA LIGA 2 A LA LIGA 1
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_primera,$winners_div2[0],$winners_div2[1],$winners_div2[2],$winners_div2[3]);
                        $stmtl->execute();
                        // DESCIENDO LOS 4 ULTIMOS DE LA LIGA 1 A LA LIGA 2
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_segunda,$losers_div1[0],$losers_div1[1],$losers_div1[2],$losers_div1[3]);
                        $stmtl->execute();
                        // ASCIENDO A LOS 4 PRIMEROS DE LA LIGA 3 A LA LIGA 2
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_segunda,$winners_div3[0],$winners_div3[1],$winners_div3[2],$winners_div3[3]);
                        $stmtl->execute();
                        // DESCIENDO LOS ULTIMOS 4 DE LA LIGA 2 A LA LIGA 3
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_tercera,$losers_div2[0],$losers_div2[1],$losers_div2[2],$losers_div2[3]);
                        $stmtl->execute();
                        // DESCIENDO LOS 4 ULTIMOS DE LA LIGA 3 A LA LIGA 4
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_league,$losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                        $stmtl->execute();
                        //ASCIENDO A LOS PRIMEROS 4 DE LA LIGA 4 A LA LIGA 3
                        $stmtl=$db->prepare("UPDATE Equipos SET Ligas_idLigas=? WHERE idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?;");
                        $stmtl->bind_param("iiiii", $id_tercera,$winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                        $stmtl->execute();
                        
                        // BORRO LAS TABLAS DE LA LIGA 4, 3, 2 y 1
                        $match_sys->DeleteTable($id_league);
                        $match_sys->DeleteTable($id_tercera);
                        $match_sys->DeleteTable($id_segunda);
                        $match_sys->DeleteTable($id_primera);
                        // CARGO FECHAS Y PARTIDOS EN LA LIGA 4, 3, 2 y 1
                        $match_sys->CreateTablesAndMatches($id_league);
                        $match_sys->CreateTablesAndMatches($id_tercera);
                        $match_sys->CreateTablesAndMatches($id_segunda);
                        $match_sys->CreateTablesAndMatches($id_primera);
                        
                        //REVISO COMO QUEDARON LOS LUGARES EN LA LIGA 4
                        $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                        $stmtl->bind_param("iiii", $winners_div4[0],$winners_div4[1],$winners_div4[2],$winners_div4[3]);
                        $stmtl->bind_result($teams_out4);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $stmtl->fetch();
                        $stmtl=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0 AND (idEquipos=? OR idEquipos=? OR idEquipos=? OR idEquipos=?);");
                        $stmtl->bind_param("iiii", $losers_div3[0],$losers_div3[1],$losers_div3[2],$losers_div3[3]);
                        $stmtl->bind_result($teams_in4);
                        $stmtl->execute();
                        $stmtl->store_result();
                        $stmtl->fetch();
                        $real_teams=$real_teams-$teams_out4+$teams_in4;
                        if($real_teams==30)
                        {
                            $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=1,terminada=2 WHERE idLigas=?;");
                            $stmtl->bind_param("ii",$real_teams,$id_league);
                            $stmtl->execute();
                        }
                        else
                        {
                            $stmtl=$db->prepare("UPDATE Ligas SET equipos_reales=?,full=0,terminada=2 WHERE idLigas=?;");
                            $stmtl->bind_param("ii",$real_teams,$id_league);
                            $stmtl->execute();
                        }    
                        $stmtl->close();
                    }
                }    
            }        
        }            
    }    
}
//EVITO EL BUG Y TODAS LAS TERMINADAS = 2 PASAN A 0 PARA DAR INICIO EN LA PROXIMA FECHA
$stmt_season=$db->prepare("UPDATE Ligas SET terminada=0 WHERE terminada=2;");
$stmt_season->execute();
$stmt_season->close();
$db->close();
?>