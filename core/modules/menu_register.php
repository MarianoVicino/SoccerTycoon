<?php global $HOME; ?>
<style>
    ul {
        list-style: none;
    }
</style>
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

                                .modal-footer{
                                    padding: 4px;
                                }
                                .fb {
                                    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAA7EAAAOxAGVKw4bAAADFUlEQVRIia2WXWgcVRSAvzM/+6v5NcSuIYF2Y+lGDNUYEQ3aGEipkBo0tJBSEJqi6Euf8tCHgkKgkD6LIBZfCkF9UooxkjRGEAJFrLVSQu3WTaSY0jabmmR2Z+b6sDubnc0kEdwLd+Zwzz33nPvdc+6MOI6jiUgvcAjQqE5zgVnge3Fdtw+YFBFNKYWIoJSqlpN+jR0iF5H/I2vAIc1b3Iu6PPoqyJpR6TmoKcfByaRxFv9E2TZ6UzPGvnYkEt3ZTikMT/DY+85AKaypy6xduoizlCmNiwjyeA2RI28SG34HicZK4+VzgIKDQESWRfb8OawfpkGg+AABhUKtrrA28Tn2779SO/4xVCSHJwciUkqxOv4RubmZkk5LtBA6+AKYJvbCTewb15CaWuLvnUG04OzeFlH+l2msK1PFiDXiIx8QHTwGRmnD5K/OI7V1mO37fVgqEWnl3ry35owRG1gGE2InTxEdGvYtDmA+342RfHrXjNqCSOXvQ3aecIeFsUfHfOO4T3/lhs0/1tZCFIHeDoOIWbZWECI2Mih3A5Sg721DonGf/rPZHEsPFIUAvfGCg9RTOm1PeA63QVR2RMW+/fZBytCC7f4XRJEWRMKgclxf+Zt2e524GSvp3+o2WV0vGDsKJn7KY9kKTYS6mPjXUmrrNSFGA9R08WU+xfv39nPp5jc+/cBzBideCTH8sskzLTqWDUoJiXqh8bHCjsp3uzWLgOuJMS5kk1gufPrbF0wsXMZ2HV9k6QdZzn+9UbLrf9afZTsWWueelzjc1sO3d+ZwUVz4+SJf3fqOF5s7Cesh/shmmL97jVhNL/raEIm6EINdZmDBBhYawNmud3mUW+PHv64CkM4ukV5ZQrE5NxedJJFc5sPXRomY/sh3LDSAsB5ivGeU0a4Rnow3FfFtYgzrIQ639vDJkdO0Nmo+210ReU1HeDvZz9G9r7PwMM3tlUVyrk1TtJ5UQ5KGSG2g3a6IKu8WQ3RSDUkO1O/zjW83vxKRG4SoSrKrUfj6uyKFIqlWLwY+I67rakAf8CrV/W2ZAab/BRxB0SmWbWr9AAAAAElFTkSuQmCC") no-repeat left center transparent;
                                    background-size: 100% 100%;
                                }

                                .btn-fb {
                                    box-shadow: inset 0px 1px 0px 0px #9fb4f2;
                                    background: linear-gradient(to bottom, #7892c2 5%, #476e9e 100%);
                                    background-color: #7892c2;
                                    border-radius: 3px;
                                    border: 1px solid #4e6096 !important;
                                    display: inline-block;
                                    cursor: pointer;
                                    color: #ffffff !important;
                                    font-family: Arial;
                                    font-size: 13px;
                                    padding: 6px 24px;
                                    text-decoration: none;
                                    text-shadow: 0px 1px 0px #283966 !important;
                                }

                                .btn-fb:hover {
                                    background: linear-gradient(to bottom, #476e9e 5%, #7892c2 100%);
                                    background-color: #476e9e;
                                }
                                .login-group{
                                    margin-top: 0.1%;
                                    margin-bottom:0.1% !important; 
                                }
                                .btn-fb:active {
                                    position: relative;
                                    top: 1px;
                                }
                                .nav-menu li{
                                    float: left;
                                    position: relative;
                                    
                                 
                                }
                                .nav-menu li a{
                                    color: #ffffff;
                                    text-decoration: none;
                                    align-items: center;
                                    margin-top: 1%;
                                }
                                .nav-menu li #open_login{
                                    margin-top: 15%
                                }
                                .nav-menu li ul li {
                                    display: none;
                                    margin:0;
                                    position: absolute;
                                    
                                    background: rgba(0, 0, 0, .5);
                                }

                                .nav li:hover > ul li {
                                    display: block;
                                }
                                h3{
                                    font-size: auto;
                                    margin:0;
                                }
                                p{
                                    margin:0;
                                }
                                .modal-body{
                                    padding:0;
                                }
                                .row{
                                    margin-left:auto;
                                    margin-right: auto;
                                }
                                .abcRioButtonLightBlue{
                                    margin-left: auto;
                                    margin-right: auto;
                                }
                            </style>
                            <text transform="matrix(1 0 0 1 125.9769 140.1446)" class="st0 st1">SOCCER TYCOON</text>

                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="border:0px transparent;">
                        <ul class="nav navbar-nav navbar-right">
                            <header class="head-form">
                                <ul class="nav-menu">
                                    <li><a href="#" id="open_login" class="glyphicon glyphicon-user"></span> LOGIN</a>
                                        <ul class="desplegable">
                                            <li>
                                                <h3>LOG IN</h3>
                                                <p>WITH</p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="social-buttons" method="POST" action="#" id="login_form">
                                                            <a href="#" class="btn btn-fb"><i class="fa fa-facebook"></i> FACEBOOK</a>
                                                            <div class="g-signin2 center" data-onsuccess="onSignIn"></div> 
                                                            <p>OR</p>
                                                        </div>
                                            

                                    
                                    
                                        <form method="POST" action="#" id="login_form">
                                            <div class="modal-body row">
                                                <div id="msj_login"></div>
                                                <div class="form-group input-group login-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                                    <input type="text" name="user" class="form-control" minlength="4" maxlength="12" placeholder="USERNAME" required="">
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                                    <input type="password" name="password" class="form-control" minlength="4" maxlength="12" placeholder="PASSWORD" required="">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" id="button_login">LOGIN</button>
                                                <button class="btn btn-default" data-toggle="modal" data-target="#modalLostPassword" data-dismiss="modal">LOST PASSWORD</button>

                                            </div>
                                        </form>
                                    </li>
                            </li>
                                </ul>
                            </header>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</div>