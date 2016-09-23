<?php
$viewname = $this->router->uri->segments[2];
    session_start();

    $config['base_url']             =   base_url();
    $config['callback_url']         =   base_url().'user/social_account_master/linkedin_insert_data';
    $config['linkedin_access']      =   $this->config->item('linkedin_api_key_user');
    $config['linkedin_secret']      =   $this->config->item('linkedin_secret_key_user');

    include_once "linkedin.php";

    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);
  
    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    //echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
    header("Location: " . $linkedin->generateAuthorizeUrl());
?>
