<script>
$(document).ready(function(){
    $('#add_region_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_regionAjax.php',
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
        <h4 class="text-center">AGREGAR REGIÓN</h4>
        <div id="msj"></div>
        <form method="POST" action="#" enctype="multipart/form-data" id="add_region_form">
            <div class="form-group">
                <input type="text" name="region" class="form-control" placeholder="NOMBRE DE REGIÓN" required>
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-picture"></span></span>
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png" required>
            </div>    
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>

