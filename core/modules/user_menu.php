<?php 
    require_once("core/models/class.Builder.php");
    $builder=new Builder();
?>
<style type="text/css">
    body
    {
        background: url("libs/images/background_dashboard.jpg") no-repeat top center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
	ul.two-col{
		columns: 2 !important;
		margin-top: 24% !important;
	}
	ul.one-col{
		columns: 1 !important;
		text-align: center !important;
	}	
	
	span.htext{
		font-weight:900;
		font-size:18px;
	}
</style>
<div class="menu-box">
    <div class="container-fluid nav-bg-2 barra">
        <header>
            <nav class="navbar navbar-default navbar-user">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed btn-lg" id="show-hide" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="index.php">
                            <img src="libs/images/logo.png" class="logo_panel logo_panel_user">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="border:0px transparent;">
                        <ul class="nav navbar-nav navbar-nav-user navbar-right">
                            <li class="dashboard_link"><a href="http://www.forum.goalmanageronline.com/index.php" target="_blank"><span class="glyphicon glyphicon-comment"></span>Forum</a></li>
                            <li class="dashboard_link"><a href="?module=news"><span class="glyphicon glyphicon-bullhorn"></span>News</a></li>
                            <li class="dashboard_link"><a href="?module=rules"><span class="glyphicon glyphicon-file"></span>Rules/ Guide</a></li>
                            <li class="dropdown dashboard_link">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-retweet"></span>Trade</a>
                                <ul class="dropdown-menu">
                                    <li><a href="?module=trade_list"><span class="glyphicon glyphicon-chevron-right"></span> Show Trade List</a></li>
                                    <li><a href="?module=sell_player"><span class="glyphicon glyphicon-chevron-right"></span> Sell Player in Trade</a></li>
                                    <li><a href="?module=remove_player"><span class="glyphicon glyphicon-chevron-right"></span> Remove Player From Trade</a></li>
                                </ul>
                            </li>
                            <li class="dropdown dashboard_link">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="libs/images/market.png" class="icon-menu2 center-block">Market</a>
                                <ul class="dropdown-menu">
                                    <li><a href="?module=premium_players"><span class="glyphicon glyphicon-chevron-right"></span> Buy Premium Players</a></li>
                                    <li><a href="?module=other_upgrades"><span class="glyphicon glyphicon-chevron-right"></span> Stadium or Team Upgrades</a></li>
                                </ul>
                            </li>
                            <li class="dashboard_link"><a href="?module=division_awards"><img src="libs/images/league.png" class="icon-menu2 center-block">Awards</a></li>
                            <li class="dashboard_link"><a href="?module=ranking"><img src="libs/images/ranking.png" class="icon-menu2 center-block">Ranking</a></li>
                            <li class="dashboard_link"><a href="?module=results"><span class="glyphicon glyphicon-eye-open"></span> Results</a></li>
                            <li class="dropdown dashboard_link">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="libs/images/shirt.png" class="icon-menu2 center-block">My Team</a>
                                <ul class="dropdown-menu">
                                    <li><a href="?module=formation"><span class="glyphicon glyphicon-chevron-right"></span> My Formation</a></li>
                                    <li><a href="?module=team_name"><span class="glyphicon glyphicon-chevron-right"></span> Change Team Name/Shield/Number</a></li>
                                    <li><a href="?module=team_shirt"><span class="glyphicon glyphicon-chevron-right"></span> Change Team T-Shirt</a></li>
                                    <li><a href="?module=fixture"><span class="glyphicon glyphicon-chevron-right"></span> My Fixture</a></li>
                                </ul>
                            </li>
                            <li class="dashboard_link"><a href="?module=buy_coins"><span class="glyphicon glyphicon-plus-sign"></span>Buy Coins</a></li>
                            <li class="dashboard_link"><a href="?module=referrals"><span class="glyphicon glyphicon-user"></span>Referrals</a></li>
                            <li class="dropdown dashboard_link">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span>Account</a>
                                <ul class="dropdown-menu">
                                    <li><a href="?module=withdrawal_methods"><span class="glyphicon glyphicon-chevron-right"></span> Withdrawls Founds</a></li>
                                    <li><a href="?module=transactions"><span class="glyphicon glyphicon-chevron-right"></span> My Transactions</a></li>
                                    <li><a href="?module=password"><span class="glyphicon glyphicon-chevron-right"></span> Change Password</a></li>
                                    <li><a href="?module=logout"><span class="glyphicon glyphicon-chevron-right"></span> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
           </nav>    
	<script>
		jQuery(document).ready(function(){
			var optDdlPosition = '';
		var cardPosition = '';
		jQuery('#ddlPosition li').on('click', function () {
		  optDdlPosition = jQuery(this).text();
		  
		  showPositions();
		  
		});
		function showPositions() {
		  jQuery('div.col-xs-12.market_player_box div.col-lg-2.col-sm-6.col-xs-12.player_box').each(function () {
			cardPosition = jQuery(this).find('div.thumbnail div.caption h5.text-center.nom-nop.player_info span:first').text();
			if (cardPosition != optDdlPosition) {
			  jQuery(this).closest('div.col-xs-12.market_player_box div.col-lg-2.col-sm-6.col-xs-12.player_box').hide();
			} 
			else {
			  jQuery(this).closest('div.col-xs-12.market_player_box div.col-lg-2.col-sm-6.col-xs-12.player_box').show();
			}
		  });
		}
		});
		</script>		   
        </header>
    </div>
</div> 

