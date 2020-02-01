<script>
$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validating ...</p>');
            },
            url: 'core/modules/ajax/passwordAjax.php',
            type: 'POST',
            data: info,
            async: true,
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
        <div class="col-md-9 col-sm-7 push-sm col-xs-12">
            <div class="dashboard-item-box">
                <h3 class="module-title text-center">CHANGE PASSWORD</h3>
                <div class="len50 center-block">
                <div id="msj"></div>
                <form method="POST" action="#">
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="old_password" minlength="4" maxlength="12" class="form-control" placeholder="OLD PASSWORD" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="new_password" minlength="4" maxlength="12" class="form-control" placeholder="NEW PASSWORD" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="new_password2" minlength="4" maxlength="12" class="form-control" placeholder="REPEAT PASSWORD" required>
                    </div>
                    <button type="submit" class="btn btn-default center-block" id="button_submit">CHANGE</button>
                </form>
                </div>    
            </div>
        </div>