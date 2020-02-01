<div class="well">
    <div class="len50">
        <h4 class="text-center">LISTA DE JUGADORES PREMIUM</h4>
        <table class="table table-responsive table-striped text-center">
            <tr>
                <td>Nombre</td>
                <td>Posición</td>
                <td>Score</td>
                <td>Oro</td>
                <td>Stock</td>
            </tr>    
            <?php
                require_once("models/class.Premium.php");
                $premium=new Premium();
                $premium->ShowPremiumPlayers();
                ?>
        </table>
    </div>
</div>

