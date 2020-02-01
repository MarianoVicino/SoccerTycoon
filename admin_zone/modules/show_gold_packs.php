<div class="well">
    <div class="len50">
        <h4 class="text-center">LISTA DE PACKS</h4>
        <table class="table table-responsive table-striped text-center">
            <tr>
                <td>#</td>
                <td>Oro</td>
                <td>Precio</td>
            </tr>
            <?php 
                require_once("models/class.Premium.php");
                $premium=new Premium();
                $premium->ShowGoldPacks();
            ?>
        </table>
    </div>
</div>

