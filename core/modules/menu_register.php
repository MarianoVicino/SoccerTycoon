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
                            <img svg version="1.1" id="Capa_1" class="logo_panel" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 560 288" style="enable-background:new 0 0 560 288;" xml:space="preserve">
                            <style type="text/css">
                                .st0 {
                                    font-family: 'BookAntiqua-BoldItalic';
                                }

                                .st1 {
                                    font-size: 30px;
                                    color: #FFF;
                                }
                            </style>
                            <text transform="matrix(1 0 0 1 125.9769 140.1446)" class="st0 st1">SOCCER TYCOON</text>

                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="border:0px transparent;">
                        <ul class="nav navbar-nav navbar-right">
                            <div class="form-group input-group">
                                <span id="open_login" class="input-group-addon login"  data-target="#modalLogin"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" name="user" class="form-control" maxlength="12" minlength="4" placeholder="USER" required="">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon login" data-toggle="modal" ><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password"  name="password" maxlength="12" minlength="4" class="form-control" placeholder="PASSWORD" required="">
                            </div>
                            <button type="submit" class="btn btn-primary center-block button-register" id="button_submit">LOGIN</button>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</div>