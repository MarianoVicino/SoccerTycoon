<script>

$(document).ready(function(){
    $('#player').change(function(e){
        e.preventDefault();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/get_nuevo_usuario.php',
            type: 'POST',
            data: {id:$('#player option:selected').val()},
            dataType: 'json',
            success: function(resp)
            {
                $('#rest-form').css({'display':'block'}); 
                buascar("divisio",resp.regiones,resp.divisiones,resp.ligas,false);
                document.getElementById("regiones").value = resp.nombre;
                document.getElementById("ligasvieja").value = resp.ligas;
                    //$("#regiones").val('30');
                    //$('#msj').html(resp);
   
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
            url: 'modules/ajax/edit_nuevo_usuario.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html('<p class="alert alert-info">Validaciòn correcta.</p>');
                $('#rest-form').css({'display':'none'}); 
                $('#button_submit').removeAttr('disabled');
                document.getElementById("edit_player_form").reset();
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
function buascar(donde,vvalor,otrva,lliga,acci) {
    if(donde == "ligas"){
        vvalor = document.getElementById("divisiones").value;
    }
    $.ajax({
        data: { "donde" : donde, "vvalor" : vvalor },
        url:   'modules/ajax/select.php',
        type:  'POST',
        dataType: 'json',
        success:  function (r) {
            if(donde == 'divisio'){
                $.each(r, function(id,value){
                    $("#divisiones").append('<option value="'+value.idDivisiones+'">'+value.nombre_div+'</option>');
                });
               $("#divisiones").val(""+otrva+"");
               buascar("ligas",0,0,lliga,true);
            }else{
                $('#ligas').html("");
                $.each(r, function(id,value){
                    if(value.equipos_reales == 30){
                        $("#ligas").append('<option value="'+value.idLigas+'" disabled >'+value.nombre_li+' - ('+value.equipos_reales+')</option>');
                    }else{
                        $("#ligas").append('<option value="'+value.idLigas+'">'+value.nombre_li+' - ('+value.equipos_reales+')</option>');
                    }
                    
                });
                if(acci){
                    $("#ligas").val(""+lliga+"");
                }
               
            }
            
        },
        error: function(){
            alert('Ocurrio un error en el servidor ..');
            alumnos.prop('disabled', false);
        }
    });
}

</script>
<div class="well">
    <div class="len50">
        <h4 class="text-center">EDITAR JUGADOR NUEVO</h4>
        <div id="msj"></div>
        <form method="POST" action="#" id="edit_player_form" enctype="multipart/form-data">
            <div class="form-group">
                <select class="form-control" name="player" id="player">
                    <option hidden>Seleccionar jugador... </option>
                    <?php
                        require_once("../core/models/class.Connection.php");
                        $db=new Connection();
                        $stmt=$db->prepare("SELECT idEquipos,nombre FROM `Equipos` WHERE asignado=0;");
                        $stmt->bind_result($idEquipos,$nombre);
                        $stmt->execute();
                        while($stmt->fetch())
                        {
                            echo '<option value="',$idEquipos,'">',$nombre,'</option>';
                        }  
                        $stmt->close();
                        
                    ?>
                </select>
            </div>
            <div id="rest-form">
                <div class="form-group input-group">
                    <span class="input-group-addon">REGIONES</span>
                    <input type="text" name="regiones" id="regiones" class="form-control" min="1" readonly>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">DIVISIONES</span>
                    <select class="form-control" name="divisiones" id="divisiones" onchange="buascar('ligas',0,0,0,false);">
                    </select>
                    <!--<input type="number" name="price" class="form-control" min="1" required>-->
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">LIGAS</span>
                    <select class="form-control" name="ligas" id="ligas" >
                    </select>
                    <!--<input type="number" name="ligas" id="ligas" class="form-control" required>-->
                </div>
                <input type="hidden" name="ligasvieja" id="ligasvieja" class="form-control">
                <button type="submit" class="btn btn-default center-block" id="button_submit">EDITAR</button>
            </div>
            <?php
            $db->close();
            ?>
        </form>
    </div>
</div>

