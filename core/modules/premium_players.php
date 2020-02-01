<script>
var posSelected = '';

function selectPos(pos)
{
	$('.market_player_box').html('<div id="msj"></div>');
	posSelected = pos;
	GetPremiumPlayers(0,posSelected);
}


$(document).ready(function(){
   GetPremiumPlayers(0,posSelected); 
   $('.market_player_box').on('click','.show_more',function(e){
       e.preventDefault();
       $(this).hide();
       GetPremiumPlayers($(this).val(),posSelected);
   });
   $('.market_player_box').on('click','.buy',function(e){
       e.preventDefault();
       var n=$(this).val();
       var shirt=$('.shirt'+n).val();
       $('html, body').animate({scrollTop: $(".dashboard-item-box").offset().top}, 500);
       $.ajax({
            beforeSend: function()
            {
               $('#msj').html('<p class="alert alert-info">Validating...</p>');
            },
            url: 'core/modules/ajax/buy_premiumplayerAjax.php',
            type: 'POST',
            data: {n:n,shirt:shirt},
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
            <div class="col-md-10">
            <h3 class="nom-nop"><span class="market_title">Buy Premium Players</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="In this section you can buy real players, these will count depending on the quality, much or little score for your team, level 4 players are top perfomance, and level 1 players are low performers, once the player has bought, This will appear in the substitutes.
Remember that players have a stock, for example Messi has a stock of 3, if other users buy before you, it will stop appearing in the market until a further notice."><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </div>
            <div class="col-md-2 right text-right">
            <div class="dropdown" style="text-align:right">
              <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Filter position
              <span class="caret"></span></button>
              <ul class="dropdown-menu" style="cursor:pointer">
                <li><a onClick="selectPos('');">ALL</a></li>
                <li><a onClick="selectPos('GK');">GK</a></li>
                <li><a onClick="selectPos('SW');">SW</a></li>
                <li><a onClick="selectPos('CB');">CB</a></li>
                <li><a onClick="selectPos('WLB');">WLB</a></li>
                <li><a onClick="selectPos('WRB');">WRB</a></li>
                <li><a onClick="selectPos('FLB');">FLB</a></li>
                <li><a onClick="selectPos('FRB');">FRB</a></li>
                <li><a onClick="selectPos('CM');">CM</a></li>
                <li><a onClick="selectPos('DM');">DM</a></li>
                <li><a onClick="selectPos('OM');">OM</a></li>
                <li><a onClick="selectPos('SLM');">SLM</a></li>
                <li><a onClick="selectPos('SRM');">SRM</a></li>
                <li><a onClick="selectPos('RW');">RW</a></li>
                <li><a onClick="selectPos('LW');">LW</a></li>
                <li><a onClick="selectPos('IF');">IF</a></li>
                <li><a onClick="selectPos('CF');">CF</a></li>                
			 </ul>
            </div>            
			</div>
            </hgroup>
            <div class="col-xs-12 market_player_box">
                <div id="msj"></div>
            </div>
        </div>
    </div>
</div>   

