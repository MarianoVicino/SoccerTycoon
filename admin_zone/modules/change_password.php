<script>
$(document).ready(function(){
    $('#form_change_pass').submit(function(e){
        e.preventDefault();
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/change_passwordAjax.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html(resp);
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
    <h4 class="text-center">CAMBIAR CONTRASEÑA</h4>
    <div class="len50">
        <div id="msj"></div>
        <form method="POST" action="#" id="form_change_pass">
            <div class="form-group">
                <input type="password" class="form-control" name="old_pw" maxlength="12" placeholder="CONTRASEÑA ACTUAL" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="new_pw" maxlength="12" placeholder="NUEVA CONTRASEÑA" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="new_pw2" maxlength="12" placeholder="REPETIR CONTRASEÑA" required>
            </div>
            <button class="btn btn-default center-block" type="submit" name="submit">CAMBIAR</button>
        </form>
    </div>
</div>
