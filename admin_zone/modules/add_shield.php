<script>
$(document).ready(function(){
    $('#add_shield_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_shieldAjax.php',
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
        <h4 class="text-center">AGREGAR ESCUDO</h4>
        <div id="msj">
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="glyphicon glyphicon-info-sign"></span> El tamaño debe ser de 65x65px para un mejor rendimiento.
            </div>
        </div>
        <form method="POST" action="#" id="add_shield_form" enctype="multipart/form-data">
            <div class="form-group input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
                <input type="file" name="shield" class="form-control" accept="image/png" required>
            </div>    
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>

