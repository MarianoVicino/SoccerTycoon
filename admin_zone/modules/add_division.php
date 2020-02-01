<script>
$(document).ready(function(){
    $('#add_division_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_divisionAjax.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html(resp);
                $('#button_submit').removeAttr('disabled');
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});
</script>
<div class="well">
    <div class="len50">
        <h4 class="text-center">AGREGAR DIVISIÓN</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="add_division_form" enctype="multipart/form-data">
            <div class="form-group">
                <select name="region" id="select" class="form-control">
                    <option hidden>Seleccionar una región...</option>
                    <?php
                        require_once("models/class.Region.php");
                        $region=new Region();
                        $region->SelectRegions();
                    ?>
                </select>
            </div>    
            <div id="rest-formm">
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-star"></span></span>
                    <input type="text" name="division" id="basic-addon1" class="form-control" minlength="2" maxlength="45" placeholder="NOMBRE DE LA DIVISIÓN" required>
                </div>
                <div class="form-group">
                    <input type="number" name="rango" class="form-control" min="1" max="4" placeholder="RANGO DIVISION (1-4)" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank"></span></span>
                    <input type="number" name="precio" id="basic-addon1" min="0" class="form-control" placeholder="POZO DE ORO POR DIVISION" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                    <input type="file" name="logo" id="basic-addon1" class="form-control" accept="image/jpeg,image/png" required>
                </div>
                <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
            </div>
        </form>
    </div>
</div>

