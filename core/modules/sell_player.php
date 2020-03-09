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
            url: 'core/modules/ajax/sell_playerAjax.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp)
            {
                $('#msj').html(resp);
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
    $(function () 
    {
        $('[data-toggle="popover"]').popover();
    });
});    
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">SELL PLAYER IN TRADE <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="Here you can put  your player in the trade list, remember that only you can sell the players that appear in your subtitutes list, once it is for sale you will not be able to use it. If you want to use the player again, you have to remove the player from the trade list."><span class="glyphicon glyphicon-question-sign"></span></button></h3>
        <div class="container-fluid market_master">
            <div class="len50 center-block">
                <div id="msj"></div>
                <form method="POST" action="#">
                    <div class="form-group">
                        <select name="player" class="form-control">
                            <option value="0" hidden>Select the player you'd like to sell</option>
                            <?php
                                $builder->GetSubastablePlayers($_SESSION['user_fmo']);
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" name="price" class="form-control" placeholder="PRICE IN St" required>
                    </div>
                    <button type="submit" class="btn btn-default center-block">ADD TO TRADE LIST</button>
                </form>
            </div>    
        </div>
    </div>
</div>   
