<script>
$(document).ready(function(){
    $('#add_player_improvement_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_player_improvementAjax.php',
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
        <h4 class="text-center">AGREGAR MEJORA DE JUGADOR/ES</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="add_player_improvement_form" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" class="form-control" name="name" minlength="3" maxlength="45" placeholder="NOMBRE DE LA MEJORA" required>
            </div>
            <div class="form-group">
                <select class="form-control" name="scope">
                    <option value="0">Un único Jugador</option>
                    <option value="1">Todo el equipo</option>
                </select>
            </div> 
            <div class="form-group">
                <input type="number" name="score" min="1" class="form-control" placeholder="PUNTOS QUE APORTA" required>
            </div>
            <div class="form-group">
                <input type="number" name="price" min="1" class="form-control" placeholder="PRECIO EN ORO" required>
            </div>
            <div class="form-group">
                <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png" required>
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>