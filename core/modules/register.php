<?php
require_once("core/models/class.Builder.php");
$builder = new Builder();
require_once("core/modules/modals.php");
include("core/modules/menu_register.php");
global $HOME;
?>
<script>
    $(document).ready(function() {

        GetRankingIndex();
        $('#login_form').submit(function(e) {
            e.preventDefault();
            var info = new FormData($(this)[0]);
            $('#button_login').attr('disabled', 'disabled');
            $.ajax({
                beforeSend: function() {
                    $('#msj_login').html('<p class="alert alert-info">Validating your credentials ...</p>');
                },
                url: '<?= $HOME; ?>core/modules/ajax/loginAjax.php',
                type: 'POST',
                data: info,
                async: true,
                success: function(resp) {
                    $('#msj_login').html(resp);
                    $('#button_login').removeAttr('disabled');
                    grecaptcha.reset(captcha2);
                },
                error: function(jqXRH, estado, error) {
                    $('#msj_login').html(error);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        $('#recover_form').submit(function(e) {
            e.preventDefault();
            var info = new FormData($(this)[0]);
            $('#button_recover').attr('disabled', 'disabled');
            $.ajax({
                beforeSend: function() {
                    $('#msj_recover').html('<p class="alert alert-info">Validating your e-mail ...</p>');
                },
                url: '<?= $HOME; ?>core/modules/ajax/recoverAjax.php',
                type: 'POST',
                data: info,
                async: true,
                success: function(resp) {
                    $('#msj_recover').html(resp);
                    $('#button_recover').removeAttr('disabled');
                },
                error: function(jqXRH, estado, error) {
                    $('#msj_recover').html(error);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        
    });
    function register() {
            var info2 =  document.forms['formregister'];
            //e.preventDefault();
            $('#button_submit').attr('disabled', 'disabled');
            var info =  new FormData(info2);
            $.ajax({
                beforeSend: function() {
                    $('#msj').html('<p class="alert alert-info">Loading ...</p>');
                },
                url: '<?= $HOME; ?>core/modules/ajax/registerAjax.php',
                type: 'POST',
                data: info,
                async: true,
                success: function(resp) {
                    $('#msj').html(resp);
                    $('#button_submit').removeAttr('disabled');
                    grecaptcha.reset(captcha1);
                },
                error: function(jqXRH, estado, error) {
                    $('#msj').html(error);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        };
</script>
<style>
    .btn-g {
        box-shadow: inset 0px 1px 0px 0px #cf866c;
        background: linear-gradient(to bottom, #d0451b 5%, #bc3315 100%);
        background-color: #d0451b;
        border-radius: 3px;
        border: 1px solid #942911;
        display: inline-block;
        cursor: pointer;
        color: #ffffff;
        font-family: Arial;
        font-size: 13px;
        padding: 6px 24px;
        text-decoration: none;
        text-shadow: 0px 1px 0px #854629;

    }

    .btn-g:hover {
        background: linear-gradient(to bottom, #bc3315 5%, #d0451b 100%);
        background-color: #bc3315;
    }

    .btn-g:active {
        position: relative;
        top: 1px;
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

    .btn-fb:active {
        position: relative;
        top: 1px;
    }
</style>
<div class="container">
    <h1 class="text-center slogan"><img src="<?= $HOME; ?>libs/images/LOGO1.png" alt="goalmanageronline"></h1>

    <h3 class="text-center" style="font-family: oblique bold,Verdana"><span class="count" style="color:orange"><?php $builder->CountUsers(); ?></span> Active Users.</h3>


    <div class="register-box col-md-12">
        <div class="center-block col-md-6">
            <h3 class="module-title text-center" style="padding-top:0px; margin-top:0px; font-family: oblique bold,Verdana">REGISTER WITH</h3>
            <div class="social-buttons" method="POST" action="#" id="register_form" class="text-center" style="padding-top:0px; margin-top:0%;text-align:center;">
            <div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>
            <a href="https://www.facebook.com/login.php?skip_api_login=1&api_key=125231578816554&kid_directed_site=0&app_id=125231578816554&signed_next=1&next=https%3A%2F%2Fwww.facebook.com%2Fdialog%2Foauth%3Fapp_id%3D125231578816554%26auth_type%26cbt%3D1582922362893%26channel_url%3Dhttps%253A%252F%252Fstaticxx.facebook.com%252Fconnect%252Fxd_arbiter.php%253Fversion%253D45%2523cb%253Df1515f16071120c%2526domain%253Dsoccertycoon.com%2526origin%253Dhttps%25253A%25252F%25252Fsoccertycoon.com%25252Ff2cffdb816ca506%2526relation%253Dopener%26client_id%3D125231578816554%26display%3Dpopup%26domain%3Dsoccertycoon.com%26e2e%3D%257B%257D%26fallback_redirect_uri%3Dhttps%253A%252F%252Fsoccertycoon.com%252F%26id%3Df2a62acf81a27de%26locale%3Des_ES%26logger_id%3Da9c08049-475b-454a-965b-275451e6af3a%26origin%3D1%26plugin_prepare%3Dtrue%26redirect_uri%3Dhttps%253A%252F%252Fstaticxx.facebook.com%252Fconnect%252Fxd_arbiter.php%253Fversion%253D45%2523cb%253Df22f785f88d0b6%2526domain%253Dsoccertycoon.com%2526origin%253Dhttps%25253A%25252F%25252Fsoccertycoon.com%25252Ff2cffdb816ca506%2526relation%253Dopener.parent%2526frame%253Df2a62acf81a27de%26ref%3DLoginButton%26response_type%3Dsigned_request%252Ctoken%252Cgraph_domain%26scope%26sdk%3Djoey%26size%3D%257B%2522width%2522%253A600%252C%2522height%2522%253A679%257D%26url%3Ddialog%252Foauth%26ret%3Dlogin%26fbapp_pres%3D0&cancel_url=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D45%23cb%3Df22f785f88d0b6%26domain%3Dsoccertycoon.com%26origin%3Dhttps%253A%252F%252Fsoccertycoon.com%252Ff2cffdb816ca506%26relation%3Dopener.parent%26frame%3Df2a62acf81a27de%26error%3Daccess_denied%26error_code%3D200%26error_description%3DPermissions%2Berror%26error_reason%3Duser_denied&display=popup&locale=es_ES&pl_dbl=0" class="btn btn-fb" style="width:47%"><i class="fa fa-facebook"></i> Facebook</a>
             <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&amp;access_type=online&amp;client_id=103341539377-e9ekc976l0ossu4o5mtvrekcj8s5456r.apps.googleusercontent.com&amp;redirect_uri=https%3A%2F%2Fsoccertycoon.com%2Findex.php&amp;state&amp;scope=email%20profile&amp;approval_prompt=auto" class="btn btn-g" style="width:47%"><i class="fa fa-GOOGLE"></i> GOOGLE</a>     <br>
             <fb:login-button perms="email,user_birthday"></fb:login-button>
            <?php echo $login_button; ?>                               
             </div>

            </BR>
            <h3 class="module-title text-center" style="padding-top:0px; margin-top:0px; font-family: oblique bold,Verdana">OR REGISTER WITH</h3>
            <div id="msj"></div>
            <form id="formregister">
                <input type="hidden" name="referral" id="referral" value="<?php echo (isset($_GET['referral']) ? $_GET['referral'] : ''); ?>">
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" name="user" class="form-control" maxlength="12" minlength="4" placeholder="USER" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" name="password" maxlength="12" minlength="4" class="form-control" placeholder="PASSWORD" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" name="repassword" maxlength="12" minlength="4" class="form-control" placeholder="REPEAT PASSWORD" required>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="email" name="email" maxlength="100" minlength="6" class="form-control" placeholder="E-MAIL" required>
                </div>
                <div class="form-group">
                    <select name="region" class="form-control">
                        <option hidden>Choose a region ...</option>
                        <?php
                        $builder->GetRegions();
                        ?>
                    </select>
                </div>
                <div id="captcha1"></div>
            </form>
            <button class="btn btn-primary center-block button-register" id="button_submit" onclick="register();">REGISTER</button>
        </div>
        <div class="col-md-6">
            <h3 class="module-title text-center" style="padding-top:0px; margin-top:0px; font-family: oblique bold,Verdana">TOP GLOBAL USERS</h3>
            <div class="center-block  ranking_content">
            </div>
        </div>
    </div>
</div>

<footer>
    <div id="footer">
        <div class="social_media text-center">
            <a href="https://www.instagram.com/goalmanageronline/" target="_blank"><img src="<?= $HOME; ?>libs/images/iconistagram_icon.png" alt=""></a>
            <a href="https://www.youtube.com/channel/UCZrbxBYbo8gqyxH19bYx62w" target="_blank"><img src="<?= $HOME; ?>libs/images/youtube_icon.png" alt=""></a>
            <a href="https://www.facebook.com/Goalmanageronline-830927277065560/" target="_blank"><img src="<?= $HOME; ?>libs/images/fac_icon.png" alt=""></a>
            <a href="mailto:info@goalmanageronline.com" data-toggle="tooltip" data-container="body" data-placement="top" title="" data-original-title="info@goalmanageronline.com"><img src="<?= $HOME; ?>libs/images/email_icon.png" alt=""></a> <a href="http://www.forum.goalmanageronline.com/index.php" target="_blank"><img src="<?= $HOME; ?>libs/images/forum_icon.png" alt=""></a>
        </div>
        <p class="text-center"><a href="#" data-toggle="modal" data-target="#modalTycEn">Terms & Conditions</a> - <a href="#" data-toggle="modal" data-target="#modalTycEs">Términos y Condiciones</a></p>
    </div>
    <p class="text-center">A free soccer manager game online in where you can change your virtual money that you win into REAL MONEY</p>
</footer>
<script>
function onSignIn(googleUser) {
        var referral = document.getElementById('referral').value;
      var profile = googleUser.getBasicProfile();
      var info2 = new FormData();
      info2.append('ID',profile.getId());
      info2.append('Full Name',profile.getName());
      info2.append('Email',profile.getEmail());
      info2.append('referral',referral);
      $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Loading ...</p>');
            },
            url: '<?= $HOME; ?>core/modules/registergoogle.php',
            type: 'POST',
            data: info2,
            async: true,
            success: function(resp)
            {
                location.reload();
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        }); 
    }

/*$(function() {
  $.ajax({
    url: '//connect.facebook.net/es_ES/all.js',
    dataType: 'script',
    cache: true,
    success: function() {
      FB.init({
        appId: '125231578816554',
        xfbml: true
      });
      FB.Event.subscribe('auth.authResponseChange', function(response) {
        if (response && response.status == 'connected') {
          FB.api('/me?fields=name,first_name,last_name,email,link,gender,picture', function(response2) {

            var referral = document.getElementById('referral').value;
              var info3 = new FormData();
              info3.append('ID',response2.id);
              info3.append('Full_Name',response2.name);
              info3.append('Email',response2.email);
              info3.append('referral',referral);
              $.ajax({
                    beforeSend: function()
                    {
                        $('#msj').html('<p class="alert alert-info">Loading ...</p>');
                    },
                    url: '<?= $HOME; ?>core/modules/registerfacebook.php',
                    type: 'POST',
                    data: info3,
                    async: true,
                    success: function(resp)
                    {
                        location.reload();
                    },
                    error: function(jqXRH,estado,error)
                    {
                        $('#msj').html(error);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                }); 
            //alert('Nombre: ' + response2.name);
          });
        }
      });
    }
  });
});*/
</script>

  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
  <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v6.0"></script>