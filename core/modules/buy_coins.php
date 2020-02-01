<script>
$(document).ready(function(){
    $('.es').hide();
        $('.show_en').click(function(){
            $('.es').hide();
            $('.en').show(500);
        });
        $('.show_es').click(function(){
            $('.en').hide();
            $('.es').show(500);
        });
	$('.buy_coins').click(function(e){
		e.preventDefault();
		var n=$(this).val();
		var method=$('.select'+n).val();
		$('html, body').animate({scrollTop: $(".dashboard-item-box").offset().top}, 500);
		$.ajax({
			beforeSend: function(e)
			{
			   $('#msj').html("<p class='alert alert-info'>Verifying...</p>");
			},
			url: 'core/modules/ajax/buy_packcoinsAjax.php',
			type: 'POST',
			data: {n:n,method:method},
			async: true,
			success: function(resp)
			{
			  $('#msj').html(resp);
			}
		});
	});
	$('.buy_players').click(function(e){
		e.preventDefault();
		var n=$(this).val();
		var method=$('.select'+n).val();
		$('html, body').animate({scrollTop: $(".dashboard-item-box").offset().top}, 500);
		$.ajax({
			beforeSend: function(e)
			{
			   $('#msj').html("<p class='alert alert-info'>Verifying...</p>");
			},
			url: 'core/modules/ajax/buy_packplayersAjax.php',
			type: 'POST',
			data: {n:n,method:method},
			async: true,
			success: function(resp)
			{
			  $('#msj').html(resp);
			}
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
        <div class="row container-fluid ranking_content market_master">
            <hgroup>
                <h3 class="nom-nop"><span class="market_title">Coin Packs</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="In this section you can buy coins for use in the game."><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </hgroup>
            <div class="col-xs-12 market_player_box">
                <div id="msj"></div>
            <div class="lang2">
                <div class="col-xs-6">
                    <img class="img-responsive show_en" src="libs/images/en.png" style="float:right;">
                </div>
                <div class="col-xs-6">
                    <img class="img-responsive show_es" src="libs/images/es.png">
                </div>
                <div class="clearfix"></div>
            </div>    
                <p class="alert alert-info en">
               <span class="glyphicon glyphicon-chevron-right"></span> MercadoPago is only for Argentina Residents.
                              <br/>  <span class="glyphicon glyphicon-chevron-right"></span> If you have a Neteller account you can send us a email and we can help you to pay with that platform.
                              <br/>  <span class="glyphicon glyphicon-chevron-right"></span> Pack Bronze = 3 Players Level 1 / Pack Silver = 3 Player level 3 / Pack Gold = 3 Player level 4, The players are chosen by the system and appear in the list of substitutes within 24 hours.

             </p>
            <p class="alert alert-info es">
                                        <span class="glyphicon glyphicon-chevron-right"></span> MercadoPago es solo para Residentes de Argentina.
                              <br/>  <span class="glyphicon glyphicon-chevron-right"></span> Si usted tiene una cuenta Neteller, usted puede enviarnos un mail y nosotros le tomaremos el pago y le acreditaremos las monedas en el instante.
  <br/>  <span class="glyphicon glyphicon-chevron-right"></span> Pack Bronze = 3 Players Level 1 / Pack Silver = 3 Player level 3 / Pack Gold = 3 Player level 4. Los jugadores son elegidos por el sistema y aparecerán en su lista de suplentes dentro de las 24hs.
            </p>
                <div class="coin_content">
                    <?php 
                        $builder->GetPacks();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>   

