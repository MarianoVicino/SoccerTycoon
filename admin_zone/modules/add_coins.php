<script>
$(document).ready(function(){
    $('#search').autocomplete({
        source: 'modules/ajax/search.php'
    });
    $('form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_coinsAjax.php',
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
<div class="well">
    <div class="len50">
        <h4 class="text-center">AGREGAR ST</h4>
        <div id="msj"></div>
        <form method="POST" action="#">
            <div class="form-group">
                <input type="text" name="user" id="search" class="form-control" placeholder="BUSQUE UN USUARIO...">
            </div>
            <div class="form-group">
                <input type="number" name="coins" min="1" max="99999999" class="form-control" placeholder="CANTIDAD DE ST A AGREGAR" required>
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>

