<script>
$(document).ready(function(){
    $('#pack').change(function(e){
        e.preventDefault();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/get_gold_pack_infoAjax.php',
            type: 'POST',
            data: {id:$('#pack option:selected').val()},
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
    $('#edit_gold_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_gold_packAjax.php',
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
        <h4 class="text-center">EDITAR PACKS DE ORO</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="edit_gold_form">
            <div class="form-group">
                <select name="pack" id="pack" class="form-control">
                    <option hidden>Seleccionar pack ...</option>
                    <?php
                        require_once("models/class.Premium.php");
                        $premium=new Premium();
                        $premium->SelectGoldPacks();
                    ?>
                </select>
            </div>
            <div id="rest-form">
                <div class="form-group">
                    <input type="number" name="gold" min="1" class="form-control" placeholder="CANTIDAD DE ORO" required>
                </div>    
                <div class="form-group input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" name="price" class="form-control" placeholder="PRECIO" required>
                </div>  
                <button type="submit" class="btn btn-default center-block" id="button_submit">EDITAR</button>
            </div>    
        </form>
    </div>
</div>