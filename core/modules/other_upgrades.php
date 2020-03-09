<script>
    $(document).ready(function(e){
        $(function () 
        {
            $('[data-toggle="popover"]').popover();
        });
        $('.buy').click(function(e){
           e.preventDefault();
           $.ajax({
                beforeSend: function()
                {
                   $('#msj_stadium').html('<p class="alert alert-info">Validating...</p>');
                },
                url: 'core/modules/ajax/buy_stadiumupgradeAjax.php',
                type: 'POST',
                data: {n:$(this).val()},
                async: true,
                success: function(resp)
                {
                    $('#msj_stadium').html(resp);
                }
            });
       });
        $('.buy_pl_upgrade').click(function(e){
           e.preventDefault();
           var n = $(this).val();
           var player=$('.select'+n).val();
           $.ajax({
                beforeSend: function()
                {
                   $('#msj_player').html('<p class="alert alert-info">Validating...</p>');
                },
                url: 'core/modules/ajax/buy_playerupgradeAjax.php',
                type: 'POST',
                data: {n:n,player:player},
                async: true,
                success: function(resp)
                {
                    $('#msj_player').html(resp);
                }
            });
       });
        $('.buy_tm_upgrade').click(function(e){
           e.preventDefault();
           var n = $(this).val();
           $.ajax({
                beforeSend: function()
                {
                   $('#msj_team').html('<p class="alert alert-info">Validating...</p>');
                },
                url: 'core/modules/ajax/buy_teamupgradeAjax.php',
                type: 'POST',
                data: {n:n},
                async: true,
                success: function(resp)
                {
                    $('#msj_team').html(resp);
                }
            });
       });
});   
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <div class="row container-fluid ranking_content market_master">
            <hgroup>
                <h3 class="nom-nop"><span class="market_title">Buy Team Improvements</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="The team improvement, will give a plus of 20% to the overall scoring total of your team, this improvement is only valid for 1 encounter and then the scoring will return to normal, for example, if your team has 1000 scoring and you applies the improvement of 20%, the team will have 1200 scoring, only for 1 match. If you play strategically, this will help you win the game."><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </hgroup>
            <div class="col-xs-12 market_upgrade_box">
                <div id="msj_team"></div>
                <?php
                    $builder->GetTeamImprovements();
					$builder->GetTeamFullStamina();
                ?>
            </div>
        </div>
        <div class="row container-fluid ranking_content market_master">
            <hgroup>
                <h3 class=""><span class="market_title">Buy Player Improvements</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="In this section you can increase the scoring forever of the player who chooses, that will help you increase your overall scoring of the team. For example, if you have a player with 140 scoring and you buy a 50 scoring upgrade, that player will have 190 scoring. Also here you can buy the Energy of your player, in the section Stamina, you will recover all the energy of the player"><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </hgroup>
            <div class="col-xs-12 market_upgrade_box">
                <div id="msj_player"></div>
                <?php
                    $builder->GetPlayerImprovements($_SESSION['user_fmo']);
                ?>
            </div>
        </div>
        <div class="row container-fluid ranking_content market_master">
            <hgroup>
                <h3 class=""><span class="market_title">Buy Stadium Seats</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="The stadium improvements (seats) determines the capacity of tickets that the team will sell, having more seats, more people will attend the meeting and you will earn more St, every 100 seats you will earn 1 extra St per local match."><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </hgroup>
            <div class="col-xs-12 market_upgrade_box">
                <div id="msj_stadium"></div>
                <?php
                    $builder->GetStadiumImprovements();
                ?>
            </div>
        </div>
    </div>
</div>   


