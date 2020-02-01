<?php
if(isset($_POST['user']) && isset($_POST['coins']))
{
    $coins=intval($_POST['coins']);
    if($coins>0 && $coins<=99999999)
    {
        require_once("../../models/class.Miscellaneous.php");
        $miscellaneous=new Miscellaneous();
        $miscellaneous->AddCoins($_POST['user'], $coins);
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Colocar una cantidad válida (1-99999999).
                          </div>';
    }    
}    
?>

