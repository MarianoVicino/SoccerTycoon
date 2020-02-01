<script>
$(document).ready(function(){
    $('#improvement').change(function(e){
        e.preventDefault();
        $('#rest-form').css({'display':'none'});
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_player_improvement_infoAjax.php',
            type: 'POST',
            data: {id:$('#improvement option:selected').val()},
            async: false,
            success: function(resp)
            {
                if(resp.length>10)
                {
                    $('#msj').html(resp);
                    $('input[name=name]').val($('#improvement option:selected').text());
                    $('#rest-form').css({'display':'block'});
                }
                else
                {
                    $('#msj').html("");
                    $('input[name=name]').val("");
                    $('input[name=score]').val("");
                    $('input[name=price]').val("");
                }    
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
        });
    });
    $('#edit_player_improvement_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_player_improvementAjax.php',
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
        <h4 class="text-center">EDITAR MEJORA DE JUGADOR/ES</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="edit_player_improvement_form" enctype="multipart/form-data">
            <div class="form-group">
                <select name="improvement" id="improvement" class="form-control">
                    <option hidden>Seleccionar una mejora...</option>
                    <?php
                        require_once("models/class.Premium.php");
                        $premium=new Premium();
                        $premium->SelectPlayerImprovements();
                    ?>
                </select>
            </div>
            <div id="rest-form">
                <div class="form-group input-group">
                    <span class="input-group-addon">NOMBRE</span>
                    <input type="text" name="name" class="form-control"  placeholder="NOMBRE DE LA MEJORA" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">PUNTOS</span>
                    <input type="number" name="score" min="1" class="form-control" placeholder="PUNTOS QUE APORTA" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">ORO</span>
                    <input type="number" name="price" min="1" class="form-control" placeholder="PRECIO EN ORO" required>
                </div>
                <div class="form-group">
                    <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png">
                </div>
                <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
            </div>
        </form>
    </div>
</div>