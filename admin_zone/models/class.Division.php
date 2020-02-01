<?php
class Division
{
    protected $idregion,$id,$nombre_reg,$nombre,$oro,$logo,$tipo,$rango;
    public function AddDivision($idregion,$nombre,$oro,$logo,$tipo,$range)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->idregion=$idregion;
        $stmt=$db->prepare("SELECT * FROM Regiones WHERE idRegiones=?;");
        $stmt->bind_param("i", $this->idregion);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $this->rango=$range;
            $stmt=$db->prepare("SELECT * FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones WHERE idRegiones=? AND rango_div=?;");
            $stmt->bind_param("ii", $this->idregion, $this->rango);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows==0)
            {
                $this->oro=$oro;
                $this->nombre=$nombre;
                $this->tipo=$tipo;
                $this->logo= file_get_contents($logo['tmp_name']);
                $stmt=$db->prepare("INSERT INTO Divisiones (nombre_div,precio_partido,Regiones_idRegiones,logo_div,tipo_logo_div,rango_div) VALUES (?,?,?,?,?,?);");
                $stmt->bind_param("siissi", $this->nombre, $this->oro, $this->idregion, $this->logo, $this->tipo, $this->rango);
                $stmt->execute();
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                La división ha sido agregada satisfactoriamente.
                          </div>';
            }
            else
            {
                $stmt->close();
                $db->close();
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Ya existe una division en dicha region con ese rango.
                          </div>';
            }
        }
        else
        {
            $stmt->close();
            $db->close();
        }    
    } 
    public function ShowDivisions()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $db2=new Connection();
        $stmt=$db->prepare("SELECT idDivisiones,nombre_reg,nombre_div,logo_div,tipo_logo_div,precio_partido,rango_div FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones;");
        $stmt->bind_result($this->id, $this->nombre_reg, $this->nombre, $this->logo, $this->tipo, $this->oro, $this->rango);
        $stmt->execute();
        while($stmt->fetch())
        {
            $stmt2=$db2->prepare("SELECT COUNT(*) FROM Ligas WHERE Divisiones_idDivisiones=?;");
            $stmt2->bind_param("i", $this->id);
            $stmt2->bind_result($n);
            $stmt2->execute();
            $stmt2->fetch();
            echo '<tr>
                      <td>',$this->nombre_reg,'</td>
                      <td>',$this->nombre,'</td>
                      <td><img class="img-responsive center-block region_logo" src="data:image/',$this->tipo,';base64,'.base64_encode($this->logo).'"/></td>
                      <td>',$this->oro,'</td>
                      <td>',$this->rango,'</td>
                      <td>',$n,'</td>
                  </tr>';
            $stmt2->close();
        } 
        $db2->close();
        $stmt->close();
        $db->close();
    } 
    public function SelectDivision($idregion)
    {
        $this->idregion=$idregion;
        require_once("../../../core/models/class.Connection.php");
        $db = new Connection();
        $stmt=$db->prepare("SELECT idDivisiones,nombre_div FROM Divisiones WHERE Regiones_idRegiones=?;");
        $stmt->bind_param("i", $this->idregion);
        $stmt->bind_result($this->id, $this->nombre);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            echo '<option hidden="hidden">Seleccionar una division ..</option>';
            while($stmt->fetch())
            {
                echo '<option value="', $this->id,'">',$this->nombre,'</option>';
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
    public function GetPrice($id)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->id=$id;
        $stmt=$db->prepare("SELECT precio_partido FROM Divisiones WHERE idDivisiones=?;");
        $stmt->bind_param("i", $this->id);
        $stmt->bind_result($n);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        echo $n;
    }        
    public function EditDivision($idregion,$id,$nombre,$oro,$logo,$tipo)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->idregion=$idregion;
        $this->id=$id;
        $stmt=$db->prepare("SELECT * FROM Divisiones WHERE idDivisiones=? AND Regiones_idRegiones=?;");
        $stmt->bind_param("ii", $this->id, $this->idregion);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $this->nombre=$nombre;
            $this->oro=$oro;
            if($logo['size']==0)
            {
                $stmt=$db->prepare("UPDATE Divisiones SET nombre_div=?,precio_partido=? WHERE idDivisiones=?;");
                $stmt->bind_param("sii", $this->nombre, $this->oro, $this->id);
                $stmt->execute();
            }
            else
            {
                $this->logo=file_get_contents($logo['tmp_name']);
                $this->tipo=$tipo;
                $stmt=$db->prepare("UPDATE Divisiones SET nombre_div=?,precio_partido=?,logo_div=?,tipo_logo_div=? WHERE idDivisiones=?;");
                $stmt->bind_param("sissi", $this->nombre, $this->oro, $this->logo, $this->tipo, $this->id);
                $stmt->execute();
            }    
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La división ha sido editada satisfactoriamente, actualizando..
                      </div><meta http-equiv="refresh" content="1;URL="index.php?module=edit_division"/>';
        }
        else
        {
            $stmt->close();
            $db->close();
        }    
    }
}
?>
