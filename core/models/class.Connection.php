<?php
class Connection extends mysqli
{
    public function __construct()
    {
        //parent::mysqli('localhost', 'goalma5_lucho', 'lucho1995','goalma5_gmo');
        parent::mysqli('localhost', 'root', '','soccertycoon');
        $this->set_charset('utf8');
        $this->connect_errno ? die('There are problems with the connection.') : $x = 'Connected';
        unset($x);
    }  
    public function Recorrer($x)
    {
        return mysqli_fetch_array($x);
    }    
    public function NumRows($x)
    {
        return mysqli_num_rows($x);
    }        
}
?>

