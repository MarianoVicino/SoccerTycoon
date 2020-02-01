$(document).ready(function(){
    $('.count').each(function(){
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
            },{
            duration: 1000,
            easing: 'swing',
            step: function (now){
            $(this).text(Math.ceil(now));
            }
        });
    });
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
});

function GetTradePlayers(n,pos)
{
    $.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_tradeplayersAjax.php',
        type: 'POST',
        data: {n:n,pos:pos},
        async: false,
        success: function(resp)
        {
            $('.market_player_box').append(resp);
        }
    });
}

function GetPremiumPlayers(n,pos)
{
    $.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_premiumplayersAjax.php',
        type: 'POST',
        data: {n:n,pos:pos},
        async: false,
        success: function(resp)
        {
            $('.market_player_box').append(resp);
        }
    });
}

function GetRanking(n)
{
    $.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_rankingAjax.php',
        type: 'POST',
        data: {n:n},
        async: false,
        success: function(resp)
        {
            $('.ranking_content').append(resp);
        }
    });
}

function GetReferrals(){
	$.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_referralsAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.referrals_content').html(resp);
        }
    });
}

function GetHistoric(n)
{
    $.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_historicAjax.php',
        type: 'POST',
        data: {n:n},
        async: false,
        success: function(resp)
        {
            $('.historic_content').append(resp);
        }
    });
}

function GetRankingIndex()
{
    $.ajax({
        beforeSend: function()
        {
           // $('#msj').html('<p class="alert alert-info">Validating...</p>');
        },
        url: HOME+'core/modules/ajax/get_rankingAjaxIndex.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.ranking_content').append(resp);
        }
    });
}

