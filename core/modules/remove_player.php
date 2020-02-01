<script>
$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validating ...</p>');
            },
            url: 'core/modules/ajax/remove_playerAjax.php',
            method: 'POST',
            data: info,
            async: true,
            cache: false,
            contentType: false,
            processData: false
            }).done(function(resp) {
                $('#msj').html(resp);
        });
    });
});    
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">REMOVE PLAYER FROM TRADE LIST</h3>
        <div class="container-fluid market_master">
            <div class="len50 center-block">
                <div id="msj"></div>
                <?php
                    $builder->GetPlayersOnSubast($_SESSION['user_fmo']);
                ?>         
            </div>    
        </div>
    </div>
</div>   
