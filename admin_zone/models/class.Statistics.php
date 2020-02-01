<?php
require_once("../core/models/class.Connection.php");
class Statistics
{
    public function GetRegions()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Regiones;");
        $stmt->execute();
        $stmt->bind_result($number);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $number;
    }        
    public function GetDivisions()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Divisiones;");
        $stmt->execute();
        $stmt->bind_result($number);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $number;
    }        
    public function GetLeagues()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Ligas;");
        $stmt->execute();
        $stmt->bind_result($number);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $number;
    }        
    public function GetUsers()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Equipos WHERE fantasma=0;");
        $stmt->execute();
        $stmt->bind_result($number);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $number;
    }  
    public function GetFounds()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT SUM(monto) FROM Compras WHERE pagado=1;");
        $stmt->bind_result($balance);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo number_format($balance, 2,",",".");
    }    
    public function GetRequests()
    {
        $db=new Connection();
        $stmt=$db->prepare("SELECT COUNT(*) FROM Retiros;");
        $stmt->bind_result($withdrawals);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $withdrawals;
    }        
}
?>
