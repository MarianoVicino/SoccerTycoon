<script>
$(document).ready(function(){
    $('#search').autocomplete({
        source: 'modules/ajax/search.php'
    });
    $('#add_player_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_player_to_userAjax.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html(resp);
                $('input').val("");
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
        <h4 class="text-center">AGREGAR JUGADOR PREMIUM A USUARIO</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="add_player_form" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="name" class="form-control" minlength="3" maxlength="45" placeholder="NOMBRE DEL JUGADOR" required>
            </div>
            <div class="form-group">
                <select name="position" class="form-control">
                    <option hidden>Seleccionar una posición...</option>
                    <optgroup label="Defensive Skills">
                        <option value="GK">GK - Goal Keeper</option>
                        <option value="SW">SW - Sweeper</option>
                        <option value="CB">CB - Center Back</option>
                        <option value="WLB">WLB - Wing Left Back</option>
                        <option value="WRB">WRB - Wing Right Back</option>
                        <option value="FLB">FLB - Full Left Back</option>
                        <option value="FRB">FRB - Full Right Back</option>
                    </optgroup>
                    <optgroup label="Midfield Skills">
                        <option value="CM">CM - Center Midfielder</option>
                        <option value="MRB">MRB - Midfield Right Back</option>
                        <option value="MLB">MLB - Midfield Left Back</option>
                        <option value="DM">DM - Defensive Midfielder</option>
                        <option value="OM">OM - Offensive Midfielder</option>
                        <option value="SLM">SLM - Side Left Midfielder</option>
                        <option value="SRM">SRM - Side Right Midfielder</option>
                    </optgroup>
                    <optgroup label="Attack Skills">
                        <option value="RW">RW - Right Winger</option>
                        <option value="LW">LW - Left Winger</option>
                        <option value="IF">IF - Inside Forward</option>
                        <option value="CF">CF - Center Forward</option>
                        <option value="HO">HO - Hole</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <input type="number" name="scoring" min="1" class="form-control" placeholder="SCORING JUGADOR" required>
            </div>
			<div class="form-group">
                <input type="number" name="number" min="1" class="form-control" placeholder="NUMERO JUGADOR" required>
            </div>
            <div class="form-group">
                <input type="text" name="user" id="search" class="form-control" placeholder="BUSQUE UN USUARIO...">
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>