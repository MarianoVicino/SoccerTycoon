<div class="container-fluid nav-bg">
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid well">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed btn-lg" id="show-hide" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <a href="index.php">
                        <img src="images/panel_logo.png" class="logo_panel">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-globe"></span> Regiones <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?module=add_region"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Región</a></li>
                                <li><a href="index.php?module=edit_region"><span class="glyphicon glyphicon-chevron-right"></span> Editar Región</a></li>
                                <li><a href="index.php?module=show_regions"><span class="glyphicon glyphicon-chevron-right"></span> Ver Regiones</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-star"></span> Divisiones <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?module=add_division"><span class="glyphicon glyphicon-chevron-right"></span> Agregar División</a></li>
                                <li><a href="index.php?module=edit_division"><span class="glyphicon glyphicon-chevron-right"></span> Editar División</a></li>
                                <li><a href="index.php?module=show_divisions"><span class="glyphicon glyphicon-chevron-right"></span> Ver Divisiones</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="index.php?module=nuevo"><span class="glyphicon glyphicon-star"></span> Nuevo ususario</a>
                            <!--<ul class="dropdown-menu">
                                <li><a href="index.php?module=add_division"><span class="glyphicon glyphicon-chevron-right"></span> Agregar División</a></li>
                                <li><a href="index.php?module=edit_division"><span class="glyphicon glyphicon-chevron-right"></span> Editar División</a></li>
                                <li><a href="index.php?module=show_divisions"><span class="glyphicon glyphicon-chevron-right"></span> Ver Divisiones</a></li>
                            </ul>-->
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-fire"></span> Premium <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">JUGADORES</li>
                                <li><a href="index.php?module=add_premium_player"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Jugador Pago</a></li>
                                <li><a href="index.php?module=add_player_to_user"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Jugador a Usuario</a></li>
                                <li><a href="index.php?module=edit_premium_player"><span class="glyphicon glyphicon-chevron-right"></span> Editar Jugador Pago</a></li>
                                <li><a href="index.php?module=show_premium_players"><span class="glyphicon glyphicon-chevron-right"></span> Ver Jugadores Pagos</a></li>                        
                                <li class="dropdown-header">PACKS ORO</li>
                                <li><a href="index.php?module=add_gold_pack"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Pack de Oro</a></li>
                                <li><a href="index.php?module=edit_gold_pack"><span class="glyphicon glyphicon-chevron-right"></span> Editar Pack de Oro</a></li>
                                <li><a href="index.php?module=show_gold_packs"><span class="glyphicon glyphicon-chevron-right"></span> Ver Packs de Oro</a></li>
                                <li class="dropdown-header">PACKS JUGADORES</li>
                                <li><a href="index.php?module=add_player_pack"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Pack de Jugador</a></li>
                                <li><a href="index.php?module=edit_player_pack"><span class="glyphicon glyphicon-chevron-right"></span> Editar Pack de Jugador</a></li>
                                <li class="dropdown-header">MEJORAS JUGADOR</li>
                                <li><a href="index.php?module=add_player_improvement"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Mejora de Jugador</a></li>
                                <li><a href="index.php?module=edit_player_improvement"><span class="glyphicon glyphicon-chevron-right"></span> Editar Mejora de Jugador</a></li>
                                <li><a href="index.php?module=show_player_improvements"><span class="glyphicon glyphicon-chevron-right"></span> Ver Mejoras de Jugador</a></li>
                                <li class="dropdown-header">MEJORAS ESTADIO</li>
                                <li><a href="index.php?module=add_stadium_improvement"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Mejora de Estadio</a></li>
                                <li><a href="index.php?module=edit_stadium_improvement"><span class="glyphicon glyphicon-chevron-right"></span> Editar Mejora de Estadio</a></li>
                                <li><a href="index.php?module=show_stadium_improvements"><span class="glyphicon glyphicon-chevron-right"></span> Ver Mejoras de Estadio</a></li>
                             </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus"></span> Adicional <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">ESCUDOS</li>
                                <li><a href="index.php?module=add_shield"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Escudo</a></li>
                                <li><a href="index.php?module=show_shields"><span class="glyphicon glyphicon-chevron-right"></span> Ver Escudos</a></li>
                                <li class="dropdown-header">CAMISETAS</li>
                                <li><a href="index.php?module=add_shirt"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Camiseta</a></li>
                                <li><a href="index.php?module=show_shirts"><span class="glyphicon glyphicon-chevron-right"></span> Ver Camisetas</a></li>
                                <li class="dropdown-header">JUGADORES</li>
                                <li><a href="index.php?module=add_free_player"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Jugadores Gratis</a></li>
                                <li><a href="index.php?module=show_free_players"><span class="glyphicon glyphicon-chevron-right"></span> Ver Jugadores Gratis</a></li>
                                <li class="dropdown-header">NOTICIAS</li>
                                <li><a href="index.php?module=add_news"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Noticia</a></li>
                                <li class="dropdown-header">COINS</li>
                                <li><a href="index.php?module=add_coins"><span class="glyphicon glyphicon-chevron-right"></span> Agregar Coins</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-usd"></span> Ventas y Retiros <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?module=show_bought_packs"><span class="glyphicon glyphicon-chevron-right"></span> Ver packs comprados</a></li>
                                <li><a href="index.php?module=show_requests"><span class="glyphicon glyphicon-chevron-right"></span> Ver retiros pendientes</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> Cuenta <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?module=change_password"><span class="glyphicon glyphicon-chevron-right"></span> Cambiar contraseña</a></li>
                                <li><a href="index.php?module=search_multiple"><span class="glyphicon glyphicon-chevron-right"></span> Borrar usuarios fantasmas</a></li>
                            </ul>
                        </li>
                        <li><a href="index.php?module=logout"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Salir</a></li> 
                    </ul>
                </div>
            </div>
        </nav>             
    </header>
</div>



