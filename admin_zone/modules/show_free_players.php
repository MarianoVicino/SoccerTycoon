<script>
$(document).ready(function(){
    $('#group').change(function(e){
        e.preventDefault();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Cargando...</p>');
            },
            url: 'modules/ajax/show_free_playersAjax.php',
            type: 'POST',
            data: {group:$('#group option:selected').val()},
            async: false,
            success: function(resp)
            {
                $('#msj').html("");
                $('#content').html(resp);
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
        });
    });
});
</script>
<div class="well">
    <div class="len50">
        <h4 class="text-center">VER LISTA DE JUGADORES GRATIS</h4>
        <div id="msj"></div>
        <div class="form-group">
            <select class="form-control" id="group">
                <option hidden>Seleccionar un grupo de jugadores ...</option>
                <option value="0">Arqueros</option>
                <option value="1">Defensores</option>
                <option value="2">Mediocampistas</option>
                <option value="3">Delanteros</option>
            </select>
        </div>
        <div id="content"></div>
    </div>
</div>