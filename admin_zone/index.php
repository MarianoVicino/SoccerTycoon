<?php
    ob_start();
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="SHORTCUT ICON" href="images/icon.ico"/>
        <link rel="stylesheet" href="../libs/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
        <script src="../libs/jquery-3.2.0.min.js"></script>
        <script src="../libs/jquery-ui.min.js"></script>
        <script src="../libs/bootstrap.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <div class="container holder">
            <?php
                if(isset($_SESSION['admin_fmo']))
                {
                    include("modules/panel.php");
                    if(isset($_GET['module']))
                    {
                        $path="modules/".strtolower($_GET['module']).".php";
                        if(file_exists($path))
                        {
                            include($path);
                            unset($path);
                        }
                        else
                        {
                            unset($path);
                            include("modules/404.php");
                        }    
                    }
                    else
                    {
                        include("modules/dashboard.php");
                    }    
                }
                else
                {
                    include("modules/login.php");
                }    
            ?>
        </div>
        
    </body>
</html>
<?php
    ob_end_flush();
?>