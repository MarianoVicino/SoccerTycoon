<?php global $HOME; ?>

        <div class="menu-box">
            <div class="container-fluid nav-bg">
                <header>
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed btn-lg" id="show-hide" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                </button>
                                <a href="<?= $HOME; ?>index.php">
                                    <img src="<?= $HOME; ?>libs/images/logo.png" class="logo_panel" alt="logo">
                                </a>
                            </div>
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="border:0px transparent;">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="btn btn-warning" data-toggle="modal" data-target="#modalTheGame">THE GAME</a></li>
                                    <li>&nbsp;&nbsp;</li>
                                    <li><a class="btn btn-danger" id="open_login" data-toggle="modal" data-target="#modalLogin"><span class="glyphicon glyphicon-user"></span> LOGIN</a></li> 
                                </ul>
                            </div>
                        </div>
                    </nav>             
                </header>
            </div>
        </div> 
