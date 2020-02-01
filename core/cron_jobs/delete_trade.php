<?php
    require_once("../models/class.Connection.php");
    $db=new Connection();
    $stmt=$db->prepare("SELECT idSubasta,Jugadores_idJugadores,datediff(fecha,now()) AS diferencia FROM Subasta;");
    $stmt->bind_result($id_trade,$player,$difference);
    $stmt->execute();
    $stmt->store_result();
    while($stmt->fetch())
    {
        $difference=-$difference;
        if($difference>=4)
        {
            $stmt2=$db->prepare("DELETE FROM Subasta WHERE idSubasta=?;");
            $stmt2->bind_param("i", $id_trade);
            $stmt2->execute();
            $stmt2=$db->prepare("UPDATE Jugadores INNER JOIN Suplentes ON Jugadores.idJugadores=Suplentes.Jugadores_idJugadores SET subasta=0 WHERE idJugadores=?;");
            $stmt2->bind_param("i", $player);
            $stmt2->execute();
            $stmt2->close();
        } 
    } 
    $stmt->close();
    $db->close();
?>