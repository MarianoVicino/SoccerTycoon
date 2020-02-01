<script>
$(document).ready(function(){
    $('#add_gold_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_player_packAjax.php',
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
        <h4  class="text-center">AGREGAR PACK DE JUGADOR</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="add_gold_form">
            <div class="form-group">
                <input type="text" name="players" class="form-control" placeholder="CANTIDAD DE JUGADORES" required>
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">$</span>
                <input type="text" name="price" class="form-control" placeholder="PRECIO USD" required>
            </div>
            <div class="form-group">
				<input type="number" name="gold" class="form-control" placeholder="PRECIO COINS" required>    	
			</div>
			<div class="form-group">
                <select class="form-control" name="image" id="player">
                    <option hidden value="0">Seleccionar jugador... </option>
                    <option value="bronce_pack.png">Bronce</option>
                    <option value="Plata_pack.png">Plata</option>
                    <option value="Gold_pack.png">Oro</option>                    
                </select>
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>