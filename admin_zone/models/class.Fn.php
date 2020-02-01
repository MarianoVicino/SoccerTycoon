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
}
?>
