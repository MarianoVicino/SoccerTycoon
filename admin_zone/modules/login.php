<script>
$(document).ready(function(){
    $('#login_form').submit(function(e){
        e.preventDefault();
        $('#button_login').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Iniciando sesión...</p>');
            },
            url: 'modules/ajax/loginAjax.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html(resp);
                $('#button_login').removeAttr('disabled');
                grecaptcha.reset();
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
<div class="logo">
    <a href="index.php"><img src="images/panel_logo.png" class="img-responsive center-block"></a>
</div>
<div class="len50 login">
    <div id="msj"></div>
    <form method="POST" action="#" id="login_form">
        <div class="input-group form-group">
            <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" class="form-control" placeholder="USUARIO" name="user" minlength="4" maxlength="12" required="">
        </div>
        <div class="input-group form-group">
            <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock"></span></span>
            <input type="password" class="form-control" placeholder="CONTRASEÑA" name="pass" minlength="4" maxlength="12" required="">
        </div>
        <div class="g-recaptcha" data-sitekey="6LeFodcUAAAAAJnyVVYK5c2tIMGuBPaGguSylVVJ"></div>
        <button type="submit" class="btn btn-default center-block" name="submit" id="button_login">ENTRAR</button>    
    </form>
</div>   

