<?php
class Admin
{
    protected $usuario;
    protected $pass;
    protected $oldpass;
    public function Login($usr,$pass)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->usuario=strtolower($usr);
        $this->pass=hash('sha512',strtolower($pass));
        $stmt=$db->prepare("SELECT usuario_ad FROM Admin WHERE usuario_ad=? AND clave_ad=?;");
        $stmt->bind_param("ss", $this->usuario, $this->pass);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($usuario);
        if($stmt->num_rows>0)
        {
            $stmt->fetch();
            $stmt->close();
            $db->close();
            session_start();
            $_SESSION['admin_fmo']=$usuario;
            echo '<div class="alert alert-success">Bienvenido ',$usuario,'!!</div><script>$(location).attr("href","index.php");</script>';
        }        
        else
        {
            $stmt->free_result();
            $stmt->close();
            $db->close();
            echo '<p class="alert alert-danger">Usuario y/o contraseña incorrecta.</p>';
        }    
    }   
    public function ChangePassword($user,$oldpw, $newpw)
    {
        require_once("../../../core/models/class.Connection.php");
        $db=new Connection();
        $this->usuario=$user;
        $this->oldpass=hash('sha512', strtolower($oldpw));
        $this->pass=hash('sha512', strtolower($newpw));
        
        $stmt=$db->prepare("SELECT * FROM Admin WHERE clave_ad=?;");
        $stmt->bind_param("s", $this->oldpass);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0)
        {
            $stmt=$db->prepare("UPDATE Admin SET clave_ad=? WHERE usuario_ad=?;");
            $stmt->bind_param("ss", $this->pass, $this->usuario);
            $stmt->execute();
            $stmt->close();
            $db->close();
            echo '<p class="alert alert-success">La contraseña ha sido cambiada con éxito.</p>';
        } 
        else
        {
            $stmt->close();
            $db->close();
            echo '<p class="alert alert-danger">La contraseña actual es incorrecta, intente nuevamente.</p>';
        }    
    }        
}
?>
