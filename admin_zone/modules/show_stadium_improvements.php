<div class="well">
    <div class="len50">
        <h4 class="text-center">VER MEJORAS DE CAPACIDAD DE ESTADIO</h4>
        <table class="table table-responsive table-striped text-center">
            <tr>
                <td>#</td>
                <td>Capacidad</td>
                <td>Oro</td>
            </tr>
            <?php
                require_once("models/class.Premium.php");
                $premium=new Premium();
                $premium->ShowStadiumImprovements();
            ?>
        </table>
    </div>
</div>

