<div class="well">
    <div class="len50">
        <h4 class="text-center">LISTA DE REGIONES</h4>
        <table class="table table-striped table-responsive text-center">
            <tr>
                <td>#</td>
                <td>Logo</td>
                <td>Nombre</td>
                <td>N&deg; Divisiones</td>
            </tr>
            <?php
                require_once("models/class.Region.php");
                $region=new Region();
                $region->ShowRegions();
            ?>
        </table>
    </div>
</div>

