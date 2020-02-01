<?php
if(isset($_POST['g-recaptcha-response']))
{
    $captcha = $_POST['g-recaptcha-response'];
    $privatekey = "6Lc4ByEUAAAAADv9eKSmf7BiMvmNvQSgk08X94e8";
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
    // if($jsonResponse->success)
    if(true)
    { 
        if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['repassword']) && isset($_POST['email']) && isset($_POST['region']))
        {
			require_once("../../models/class.Fn.php");
            $f=new Fn();
            if($f->Validar_Long($_POST['user'], 4, 12)==1)
            {
                $id_region=intval($_POST['region']);
                if($id_region>0)
                {
                    if($f->Validar_Long($_POST['password'], 4, 12)==1 && $f->Validar_Long($_POST['repassword'], 4, 12)==1)
                    {
                        if($_POST['password']===$_POST['repassword'])
                        {
                            if($f->Validar_Long($_POST['email'], 6, 100)==1 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                            {
                                require_once("../../models/class.User.php");
								$ref = 0;
                                $user=new User();
								if(isset($_POST['referral']) && $f->Validar_Long($_POST['user'], 4, 12)==1)
									$ref = $user->GetUserIdByLogin($_POST['referral']);
								$user->AddUser($id_region, $_POST['user'], $_POST['password'], $_POST['email'], $ref);
                            }
                            else
                            {
                                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        The e-mail is invalid, please try again.
                                  </div>';
                            }    
                        }
                        else
                        {
                            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    The passswords are different, please try again.
                              </div>';
                        }    
                    }
                    else
                    {
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              Please, insert a valid password or re-password (6-12).
                        </div>';
                    }    
                }
                else
                {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, select a region.
                       </div>';
                }    
            }
            else
            {
                echo '<div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please, insert a valid user name (6-12).
                       </div>';    
            }
        }  
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Please, complete the captcha to register.
                              </div>';
    }    
}    
?>
