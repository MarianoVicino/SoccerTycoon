<script>
$(document).ready(function(){
    $('#select').change(function(e){
        e.preventDefault();
        var text=$('#select option:selected').text();
        $('#region').val(text);
        $('#rest-form').css({'display':'block'});
    });
    $('#edit_region_form').submit(function(e){
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/edit_regionAjax.php',
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
        <h4 class="text-center">EDITAR REGIÓN</h4>
        <div id="msj"></div>
        <form method="POST" action="#" enctype="multipart/form-data" id="edit_region_form">
            <div class="form-group">
                <select class="form-control" name="id_region" id="select">
                    <option hidden>Seleccionar una región...</option>
                    <?php
                        require_once("models/class.Region.php");
                        $region=new Region();
                        $region->SelectRegions();
                    ?>
                </select>
            </div> 
            <div id="rest-form">
                <div class="form-group">
                    <input type="text" name="region" id="region" class="form-control" placeholder="NOMBRE DE REGIÓN" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-picture"></span></span>
                    <input type="file" name="logo" class="form-control" >
                </div>    
                <button type="submit" class="btn btn-default center-block" id="button_submit">EDITAR</button>
            </div>
        </form>
    </div>
</div>

