<div class="well">
    <div class="len50">
        <h4 class="text-center">LISTA DE MEJORAS DE JUGADOR</h4>
        <table class="table table-responsive table-striped text-center">
            <tr>
                <td>Nombre</td>
                <td>Pts.Aporta</td>
                <td>Oro</td>
                <td>Alcance</td>
            </tr>
            <?php
                require_once("models/class.Premium.php");
                $premium=new Premium();
                $premium->ShowPlayerImprovements();
            ?>
        </table>
    </div>
</div>

