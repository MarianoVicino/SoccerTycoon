<div class="well">
    <div class="len50">
        <h4 class="text-center">LISTA DE DIVISIONES</h4>
        <table class="table table-striped table-responsive text-center">
            <tr>
                <td>Región</td>
                <td>División</td>
                <td>Logo</td>
                <td>Oro Ganador</td>
                <td>Rg</td>
                <td>N&deg; Ligas</td>
            </tr>
            <?php 
                require_once("models/class.Division.php");
                $division=new Division();
                $division->ShowDivisions();
            ?>
        </table>
    </div>
</div>

