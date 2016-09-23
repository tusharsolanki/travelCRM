<!DOCTYPE HTML>
<!--[if lt IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie9"> <![endif]-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="keyword"/>
<meta name="Description" content="discription"/>
<title>Tops Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery-1.9.1.js"></script>
<script src="<?=$this->config->item('js_path')?>jquery.blockUI.js" type="text/javascript"></script>
<!--confirm box css-->
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.confirm.js"></script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>common.js"></script>
<script src="<?=$this->config->item('js_path')?>bootstrap.js"></script> 
<script src="<?=$this->config->item('js_path')?>App.js"></script> 
<script src="<?=$this->config->item('js_path')?>parsley.js"></script> 
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>fontcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>runtime.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>font-awesome.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>bootstrapcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>crm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>btncrm.css" type="text/css">

<?php

if(isset($_COOKIE['adminsiteAuth']))
{
$StringArray = explode( '&', $_COOKIE['adminsiteAuth'] );

$usernamestring = $StringArray[0];
$passwordstring = $StringArray[1];

$usernamefinal = explode( '=', $usernamestring );
$username = $usernamefinal[1];
 
$passwordfinal = explode( '=', $passwordstring );
$password = $passwordfinal[1];

}
else{$username='';$password='';}
?>
</head>

<body class="login">


<div id="wrapper">
 <div id="login-container">
  <div id="login">
   <div id="logo"> <a href="./login.html"> <img src="<?php echo base_url('images/logo.png')?>" > </a> </div>
   <h3>Welcome to LiveWire CRM Admin.</h3>
   <h5>Please sign in to get access.</h5>
   <br>
   <div class="row">
    <div class="col-sm-6"> <a class="btn btn-twitter btn-block" href="./index.html"> <i class="fa fa-twitter"></i> &nbsp;&nbsp;Login with Twitter </a> </div>
    <!-- /.col -->
    
    <div class="col-sm-6"> <a class="btn btn-facebook btn-block" href="./index.html"> <i class="fa fa-facebook"></i> &nbsp;&nbsp;Login with Facebook </a> </div>
    <!-- /.col --> 
    
   </div>
   <!-- /.row --> 
   
   <!--<span class="text-muted">OR, SIGN IN BELOW</span>-->
   
   <form class="form parsley-form" id="login-form" id="login-form" method="post" action="" novalidate>
    <div class="form-group">
     <label for="login-username">Username</label>
     <!--<input type="text" placeholder="Username" id="login-username" class="form-control">-->
	 <input id="email" value="<?php if(isset($username)){  echo $username; }?>"  placeholder="Username" autofocus type="email" name="email" class="form-control parsley-validated" data-required="true">
    </div>
    <div class="form-group">
     <label for="login-password">Password</label>
     <!--<input type="password" placeholder="Password" id="login-password" class="form-control">-->
	 <input id="password" value="<?php if(isset($password)){  echo $password; }?>"  placeholder="Password" data-bvalidator="required"  type="password" name="password" class="form-control" data-required="true">
    </div>
    <div class="form-group">
     <button class="btn btn-primary btn-block" id="login-btn" type="submit">Signin &nbsp; <i class="fa fa-play-circle"></i></button>
    </div>
   </form>
   
   <a class="btn btn-default" href="javascript:;">Forgot Password?</a> </div>
  <!-- /#login-form --> 
  
  <a class="btn btn-lg btn-block" id="signup-btn" href="javascript:;"> Create an Account </a> </div>
</div>

</body>
</html>