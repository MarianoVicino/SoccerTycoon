<?php
    require_once("core/models/class.Builder.php");
    $builder=new Builder();
    require_once("core/modules/modals.php");
    include("core/modules/menu_register.php");
	global $HOME;
?>
<script>
$(document).ready(function(){
	
	GetRankingIndex(); 
       $('#login_form').submit(function(e){
        e.preventDefault();
        var info = new FormData($(this)[0]);
        $('#button_login').attr('disabled','disabled');
        $.ajax({
            beforeSend: function()
            {
                $('#msj_login').html('<p class="alert alert-info">Validating your credentials ...</p>');
            },
            url: '<?= $HOME; ?>core/modules/ajax/loginAjax.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp)
            {
                $('#msj_login').html(resp);
                $('#button_login').removeAttr('disabled');
                grecaptcha.reset(captcha2);
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj_login').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $('#recover_form').submit(function(e){
        e.preventDefault();
        var info = new FormData($(this)[0]);
        $('#button_recover').attr('disabled','disabled');
        $.ajax({
            beforeSend: function()
            {
                $('#msj_recover').html('<p class="alert alert-info">Validating your e-mail ...</p>');
            },
            url: '<?= $HOME; ?>core/modules/ajax/recoverAjax.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp)
            {
                $('#msj_recover').html(resp);
                $('#button_recover').removeAttr('disabled');
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj_recover').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $('#register_form').submit(function(e){ 
        e.preventDefault();
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Loading ...</p>');
            },
            url: '<?= $HOME; ?>core/modules/ajax/registerAjax.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp)
            {
                $('#msj').html(resp);
                $('#button_submit').removeAttr('disabled');
                grecaptcha.reset(captcha1);
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});
</script>  
<div class="container">
    <h1 class="text-center slogan" ><img src="<?= $HOME; ?>libs/images/LOGO1.png" alt="goalmanageronline"></h1>

    <h3 class="text-center" style="font-family: oblique bold,Verdana"><span class="count" style="color:orange"><?php $builder->CountUsers(); ?></span> Active Users.</h3>
    
    
    <div class="register-box col-md-12">
    <div class="center-block col-md-6">
        <h3 class="module-title text-center" style="padding-top:0px; margin-top:0px; font-family: oblique bold,Verdana">REGISTER NOW!</h3>
        <div id="msj"></div>        
        <form method="POST" action="#" id="register_form">
			<input type="hidden" name="referral" value="<?php echo (isset($_GET['referral']) ? $_GET['referral'] : ''); ?>">
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
            <div class="form-group">
                <a href="<?php //echo $google_client->createAuthUrl(); ?>">Google</a>
            </div>
            <div id="captcha1"></div>
            <button type="submit" class="btn btn-primary center-block button-register" id="button_submit">REGISTER</button>
        </form>
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
<a href="mailto:info@goalmanageronline.com" data-toggle="tooltip" data-container="body" data-placement="top" title="" data-original-title="info@goalmanageronline.com"><img src="<?= $HOME; ?>libs/images/email_icon.png" alt=""></a>            <a href="http://www.forum.goalmanageronline.com/index.php" target="_blank"><img src="<?= $HOME; ?>libs/images/forum_icon.png" alt=""></a>
        </div>
        <p class="text-center"><a href="#" data-toggle="modal" data-target="#modalTycEn">Terms & Conditions</a> - <a href="#" data-toggle="modal" data-target="#modalTycEs">Términos y Condiciones</a></p>
    </div>
 <p class="text-center">A free soccer manager game online in where you can change your virtual money that you win into REAL MONEY</p>
</footer>
