<script>
$(document).ready(function(){
    function LoadRequests()
    {
        $.ajax({
            beforeSend: function()
            {
                
            },
            url: 'modules/ajax/get_requestsAjax.php',
            async: true,
            success: function(resp)
            {
                $('#requests').html(resp);
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            }
        });
    }
    LoadRequests();
    $('#requests').on('click','.delete_request',function(e){
        e.preventDefault();
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Borrando retiro...</p>');
            },
            url: 'modules/ajax/delete_requestsAjax.php',
            async: true,
            type: 'POST',
            data:{id:$(this).val()},
            success: function(resp)
            {
                $('#msj').html(resp);
                LoadRequests();
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
        <h4 class="text-center">RETIROS PENDIENTES</h4>
        <div id="msj"></div>
        <div id="requests">
            
        </div>
    </div>
</div>