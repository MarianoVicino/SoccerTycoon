<?php 
    session_start();
    ob_start();
	global $HOME;
    if($_SERVER["HTTP_HOST"] == "localhost"){
        $HOME = 'http://localhost/SoccerTycoon/';
    }else{
        $HOME = 'https://'.$_SERVER["HTTP_HOST"].'/';
    }
    include("core/models/class.Connection.php");
    $db=new Connection();
    include('google/config.php');
    if(!isset($_SESSION['user_fmo'])){
        //Create a URL to obtain user authorization  
        $login_button = '<a href="'.$google_client->createAuthUrl().'" class="btn btn-g"><i class="fa fa-GOOGLE"></i> GOOGLE</a>';
    }
    if(isset($_GET["code"])){
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
        if(!isset($token['error'])){
            $google_client->setAccessToken($token['access_token']);
            $google_service = new Google_Service_Oauth2($google_client);
            $data = $google_service->userinfo->get();
            $referral = "<script>document.getElementById('referral').value;</script>";
            $ver = logingoogle($data['email'],$referral,$data['name'],$data['id']);
            $_SESSION['user_fmo'] = $ver;
            header("Location: index.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SoccerTycoon - online football manager- fútbol manager online</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="SHORTCUT ICON" href="<?= $HOME; ?>libs/images/icon.ico"/>
        <link rel="stylesheet" href="<?= $HOME; ?>libs/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $HOME; ?>libs/styles.css?nocache=">   
        <script src="https://kit.fontawesome.com/926dc0e14d.js" crossorigin="anonymous"></script>   
        <meta name="description" content="SoccerTycoon is a strategy soccer game, in which you have the posibility to convert your virtual currency into real money."/> 
        <?php 
            if(isset($_GET['module']))
            {
                if($_GET['module']==="formation")
                {
                    echo '<link rel="stylesheet" href="'.$HOME.'libs/field.css">';
                }    
            }    
        ?>
		<script>
			var HOME = "<?= $HOME; ?>";
		</script>
        <script src="<?= $HOME; ?>libs/jquery-3.2.0.min.js"></script>
        <script src="<?= $HOME; ?>libs/bootstrap.min.js"></script>
        <script src="<?= $HOME; ?>libs/jquery.countdown.js"></script>
        <script src="<?= $HOME; ?>libs/moment.min.js"></script>
        <script src="<?= $HOME; ?>libs/moment-timezone-with-data.js"></script>
        <script src="<?= $HOME; ?>libs/js/functions.js"></script> 
        
        
        <script> 
            var captcha1;
            var captcha2;
            var CaptchaCallback = function() {
                captcha1=grecaptcha.render(document.getElementById('captcha1'), {'sitekey' : '6LeFodcUAAAAAJnyVVYK5c2tIMGuBPaGguSylVVJ'});
                captcha2=grecaptcha.render(document.getElementById('captcha2'), {'sitekey' : '6LeFodcUAAAAAJnyVVYK5c2tIMGuBPaGguSylVVJ'});
            };
        </script> 
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102117334-1', 'auto'); 
  ga('send', 'pageview'); 

</script>


        <script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
        
    </head>
    <body>
        <?php
            if(isset($_SESSION['user_fmo']))
            {
				if(isset($_GET['referral'])){
					header('location: index.php');
				}
                include("core/modules/user_menu.php");
                echo '<div class="container dashboard-content">
                        <div class="row">';
                if(isset($_GET['module']))
                {
                    $module_name=strtolower($_GET['module']);
                    if(!($module_name==="formation"))
                    {
                        include("core/modules/side_userbar.php");
                    }    
                    if($module_name==="side_userbar" || $module_name==="user_menu" || $module_name==="register" || $module_name==="modals" || $module_name==="menu_register" || $module_name==="404")
                    {
                        header('location: index.php');
                    }    
                    $path="core/modules/".$module_name.".php";
                    if(file_exists($path))
                    {
                        include($path);
                        unset($path);
                    }
                    else
                    {
                        unset($path);
                        include("core/modules/error.php");
                    }    
                }
                else
                {
                    include("core/modules/side_userbar.php");
                    include("core/modules/dashboard.php");
                }  
                echo  '</div>
                </div>';
            }
            else
            {
				if(isset($_GET['module']))
                {
					header('location:index.php');
                }
				include("core/modules/register.php");
            }    
        ?>     
    </body>
</html>
<?php
    ob_end_flush();
?>
<script>

$(function() {
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
});
function fbLogout() {
    FB.logout(function() {
        console.log("se");
    });
}

    var udateTime = function() {
    let currentDate = new Date(),
        hours = currentDate.getHours(),
        minutes = currentDate.getMinutes(), 
        seconds = currentDate.getSeconds();
 
    document.getElementById('hours').textContent = hours;
 
    if (minutes < 10) {
        minutes = "0" + minutes
    }
 
    if (seconds < 10) {
        seconds = "0" + seconds
    }
 
    document.getElementById('minutes').textContent = minutes;
    document.getElementById('seconds').textContent = seconds;
};
 
udateTime();
 
setInterval(udateTime, 1000);
</script>