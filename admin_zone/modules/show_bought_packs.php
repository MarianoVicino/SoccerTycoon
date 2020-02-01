<div class="well">
    <div class="len50">
        <h4 class="text-center">VER PACKS COMPRADOS</h4>
        <div id="msj"></div>
        <table class="table table-striped table-responsive text-center">
            <tr>
                <td>Pack</td>
                <td>Fecha</td>
                <td>Monto</td>
                <td>Usuario</td>
            </tr>
            <?php 
                require_once("models/class.Orders.php");
                $order=new Orders();
                $order->GetBoughtPacks();
            ?>
        </table>
    </div>
</div>    
