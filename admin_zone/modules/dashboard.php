<?php
    require_once("models/class.Statistics.php");
    $statistics=new Statistics();
?>
<div class="row dashboard center-block">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>REGIONES</h4>
            <p><span class="glyphicon glyphicon-globe"></span> <?php $statistics->GetRegions(); ?></p>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>DIVISIONES</h4>
            <p><span class="glyphicon glyphicon-star"></span> <?php $statistics->GetDivisions(); ?></p>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>LIGAS</h4>
            <p><span class="glyphicon"><img src="images/trophy.png"></span> <?php $statistics->GetLeagues(); ?></p>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>USUARIOS</h4>
            <p><span class="glyphicon glyphicon-user"></span> <?php $statistics->GetUsers(); ?></p>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>RETIROS</h4>
            <p><span class="glyphicon glyphicon-share-alt"></span> <?php $statistics->GetRequests(); ?></p>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <div class="well">
            <h4>VENTAS</h4>
            <p>U$S <?php $statistics->GetFounds(); ?></p>
        </div>
    </div>
</div>

