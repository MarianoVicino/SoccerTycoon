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

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>GoalManager - online football manager- fútbol manager online</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="SHORTCUT ICON" href="<?= $HOME; ?>libs/images/icon.ico"/>
        <link rel="stylesheet" href="<?= $HOME; ?>libs/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $HOME; ?>libs/styles.css?nocache=">      
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <meta name="google-signin-client_id" content="103341539377-e9ekc976l0ossu4o5mtvrekcj8s5456r.apps.googleusercontent.com">
<meta name="description" content="GoalManager is a strategy soccer game, in which you have the posibility to convert your virtual currency into real money."/> 
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