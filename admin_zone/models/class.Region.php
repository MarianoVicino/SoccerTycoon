<?php
class Region
{
    protected $nombre,$id,$logo,$tipo;
    public function AddRegion($nombre,$logo,$tipo)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->nombre=$nombre;
        $stmt=$db->prepare("SELECT * FROM Regiones WHERE nombre_reg=?;");
        $stmt->bind_param("s", $this->nombre);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==0)
        {
            $this->logo=file_get_contents($logo['tmp_name']);
            $this->tipo=$tipo;
            $stmt=$db->prepare("INSERT INTO Regiones (nombre_reg,logo_reg,tipo_logo_reg) VALUES (?,?,?);");
            $stmt->bind_param("sss", $this->nombre, $this->logo, $this->tipo);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            La región ha sido agregada satisfactoriamente.
                      </div>';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Esa región ya existe, por favor, intente con otro nombre.
                      </div>';
        }
    } 
    public function SelectRegions()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $stmt=$db->prepare("SELECT idRegiones,nombre_reg FROM Regiones;");
        $stmt->execute();
        $stmt->bind_result($this->id, $this->nombre);
        while($stmt->fetch())
        {
            echo '<option value="',$this->id,'">',$this->nombre,'</option>';
        }
        $stmt->close();
        $db->close();
    }  
    public function EditRegion($id,$nombre,$logo,$tipo)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->id=$id;
        $stmt=$db->prepare("SELECT idRegiones FROM Regiones WHERE idRegiones=?;");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $this->nombre=$nombre;
            if($logo['size']==0)
            {
                $stmt->prepare("UPDATE Regiones SET nombre_reg=? WHERE idRegiones=?;");
                $stmt->bind_param("si", $this->nombre, $this->id);
                $stmt->execute();
            }
            else
            {
                $this->tipo=$tipo;
                $this->logo=file_get_contents($logo['tmp_name']);
                $stmt->prepare("UPDATE Regiones SET nombre_reg=?,logo_reg=?,tipo_logo_reg=? WHERE idRegiones=?;");
                $stmt->bind_param("sssi", $this->nombre,$this->logo,$this->tipo,$this->id);
                $stmt->execute();
            }  
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                La región ha sido editada satisfactoriamente, actualizando...
                          </div>
                          <meta http-equiv="refresh" content="1;URL="index.php?module=edit_region" />';
        }
        else
        {
            $stmt->close();
            $db->close();
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Fatal error</strong>, la región es inválida.
                          </div>';
        }    
    } 
    public function ShowRegions()
    {
        require_once("../core/models/class.Connection.php");
        $db=new Connection();
        $db2=new Connection();
        $stmt=$db->prepare("SELECT * FROM Regiones;");
        $stmt->execute();
        $stmt->bind_result($this->id, $this->nombre, $this->logo, $this->tipo);
        $count=1;
        while($stmt->fetch())
        {
            $stmt2=$db2->prepare("SELECT COUNT(*) FROM Regiones INNER JOIN Divisiones ON Regiones.idRegiones=Divisiones.Regiones_idRegiones WHERE idRegiones=?;");
            $stmt2->bind_param("i", $this->id);
            $stmt2->execute();
            $stmt2->bind_result($n);
            $stmt2->fetch();
            echo '<tr>
                      <td>',$count,'</td>
                      <td><img class="img-responsive center-block region_logo" src="data:image/',$this->tipo,';base64,'.base64_encode($this->logo).'"/></td>
                      <td>',$this->nombre,'</td>
                      <td>',$n,'</td>
                  </tr>';
            $stmt2->close();
            $count++;
        }
        $stmt->close();
        $db2->close();
        $db->close();
    }        
}
?>
