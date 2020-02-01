<script>
$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validating...</p>');
            },
            url: 'core/modules/ajax/team_shieldAjax.php',
            type: 'POST',
            data: info,
            async: true,
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
        <div class="col-md-9 col-sm-7 push-sm col-xs-12">
            <div class="dashboard-item-box">
                <h3 class="module-title text-center">CHANGE TEAM SHIELD</h3>
                <div class="len50 center-block">
                    <div id="msj"></div>
                    <form method="POST" action="#" enctype="multipart/form-data">
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span class="glyphicon glyphicon-info-sign"></span> The shield must be a .png and with a size of 200x200px for optimal visualization and no more bigger than 200kb.
                          </div>
                        <div class="form-group">
                            <input type="file" name="shield" class="form-control" accept="image/png" required>
                        </div>
                        <button type="submit" class="btn btn-default center-block" id="button_submit">CHANGE</button>
                    </form>
                </div>    
            </div>
        </div>

