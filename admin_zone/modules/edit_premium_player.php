<script>
$(document).ready(function(){
    $('#player').change(function(e){
        e.preventDefault();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/get_premium_player_infoAjax.php',
            type: 'POST',
            data: {id:$('#player option:selected').val()},
            async: false,
            success: function(resp)
            {
                if(resp.length>10)
                {
                    $('#rest-form').css({'display':'block'});
                    $('#msj').html(resp);
                }
                else
                {
                    $('#msj').html("");
                    $('#rest-form').css({'display':'none'});
                }    
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
        });
    });
    $('#edit_player_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_premium_playerAjax.php',
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
        <h4 class="text-center">EDITAR JUGADOR PAGO</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="edit_player_form" enctype="multipart/form-data">
            <div class="form-group">
                <select class="form-control" name="player" id="player">
                    <option hidden>Seleccionar jugador... </option>
                    <?php
                        require_once("models/class.Premium.php");
                        $premium=new Premium();
                        $premium->SelectPremiumPlayers();
                    ?>
                </select>
            </div>
            <div id="rest-form">
                <div class="form-group input-group">
                    <span class="input-group-addon">SCORING</span>
                    <input type="number" name="score" class="form-control" min="1" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">PRECIO</span>
                    <input type="number" name="price" class="form-control" min="1" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">STOCK</span>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png">
                </div>
                <button type="submit" class="btn btn-default center-block" id="button_submit">EDITAR</button>
            </div>
        </form>
    </div>
</div>

