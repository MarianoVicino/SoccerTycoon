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
            url: 'core/modules/ajax/team_shirtAjax.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp)
            {
                $('.info').hide();
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
                <h3 class="module-title text-center">CHANGE TEAM SHIRT</h3>
                <div class="len50 center-block">
                    <div id="msj"></div>
                    <form method="POST" action="#" enctype="multipart/form-data">
                        <div class="alert alert-info alert-dismissible info" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span class="glyphicon glyphicon-info-sign"></span> Please select the shirt you'd like to have on your team.
                        </div>
                        <div class="row shirts_box">
                            <?php
                                $builder->GetShirts();
                            ?>
                        </div>    
                        <button type="submit" class="btn btn-default center-block" id="button_submit">CHANGE</button>
                    </form>
                </div>    
            </div>
        </div>
   

