<script>
$(document).ready(function(){
    $('#add_stadium_improvement_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_stadium_improvementAjax.php',
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
        <h4 class="text-center">AGREGAR MEJORA DE CAPACIDAD DE ESTADIO</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="add_stadium_improvement_form">
            <div class="form-group">
                <input type="text" class="form-control" name="capacity" min="1" placeholder="CAPACIDAD" required>
            </div>
            <div class="form-group">
                <input type="text" name="price" min="0" class="form-control" placeholder="PRECIO DE LA MEJORA EN ORO" required>
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>
