<script>
$(document).ready(function(){
    $('#region').change(function(e){
        $('#rest-form-ad').css({'display':'none'});
        $('#rest-form').css({'display':'none'});
        e.preventDefault();
        var id=$('#region option:selected').val();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_division_auxAjax.php',
            type: 'POST',
            data: {region:id},
            async: false,
            success: function(resp)
            {
                $('#msj').html("");
                if(resp.length>4)
                {
                    $('#division').html(resp);
                    $('#rest-form').css({'display':'block'});
                }
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
        });
    });
    $('#division').change(function(e){
        e.preventDefault();
        $('#rest-form-ad').css({'display':'none'});
        var id=$('#division option:selected').val();
        var text=$('#division option:selected').text();
        if(id>0)
        {
            $('#name_division').val(text);
            $('#rest-form-ad').css({'display':'block'});
            $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_division_getpriceAjax.php',
            type: 'POST',
            data: {id:id},
            async: false,
            success: function(resp)
            {
                $('#msj').html("");
                $('#precio').val(resp);
                $('#name_division').val(text);
                $('#rest-form-ad').css({'display':'block'});
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
            });
        }
    });
    $('#edit_division_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_divisionAjax.php',
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
        <h4 class="text-center">EDITAR DIVISIÓN</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="edit_division_form" enctype="multipart/form-data">
            <div class="form-group">
                <select id="region" name="region" class="form-control">
                    <option hidden>Seleccionar una región...</option>
                    <?php 
                        require_once("models/class.Region.php");
                        $region = new Region();
                        $region->SelectRegions();
                    ?>
                </select>
            </div>
            <div id="rest-form">
                <div class="form-group">
                    <select name="division" id="division" class="form-control"></select>
                </div>
                <div id="rest-form-ad">
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-star"></span></span>
                        <input type="text" class="form-control" id="name_division" name="name_division" placeholder="NOMBRE DE DIVISION">
                    </div>    
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank"></span></span>
                        <input type="text" class="form-control" id="precio" name="precio" placeholder="POZO DE ORO POR DIVISION">
                    </div>    
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                        <input type="file" class="form-control" name="logo" accept="image/jpeg,image/png">
                    </div> 
                    <button type="submit" class="btn btn-default center-block" id="button_submit">EDITAR</button>
                </div>
            </div>
        </form>
    </div>
</div>
