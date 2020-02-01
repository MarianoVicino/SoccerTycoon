<?php
if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response']))
{
    /*$captcha = $_POST['g-recaptcha-response'];
    $privatekey = "6Lc82yAUAAAAAEUxEDPYnM3rbxOmbwYHinpZxuhp";
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $privatekey,
        'response' => $captcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $curlConfig = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data
    );

    $ch = curl_init();
    curl_setopt_array($ch, $curlConfig);
    $response = curl_exec($ch);
    curl_close($ch);
    $jsonResponse = json_decode($response);
    if($jsonResponse->success)
    {   */
        require_once("../../models/class.Fn.php");
        $f=new Fn();
        if($f->Validar_Long($_POST['user'], 4, 12)==1 && $f->Validar_Long($_POST['password'], 4, 12)==1)
        {
            require_once("../../models/class.User.php");
            $user=new User();
            $user->UserLogin($_POST['user'], $_POST['password']);
        }        
        else
        {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Invalid data, accepted length (4-12).
                              </div>';
        }
    /*}
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Please, complete the captcha to login.
                              </div>';
    }   */ 
}    
?>

