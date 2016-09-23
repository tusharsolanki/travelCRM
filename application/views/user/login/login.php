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
<title>LiveWire CRM</title>
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

if(isset($_COOKIE['usersiteAuth']))
{
$StringArray = explode( '&', $_COOKIE['usersiteAuth'] );

$usernamestring = $StringArray[0];
$passwordstring = $StringArray[1];

$usernamefinal = explode( '=', $usernamestring );
$username = $usernamefinal[1];
 
$passwordfinal = explode( '=', $passwordstring );
$password = $passwordfinal[1];

}
else{$username='';$password='';}

$multiple_users_session = $this->session->userdata('all_admin_account_session');
$multiple_passusers_session = $this->session->userdata('all_passadmin_account_session');

$this->load->view('google_analytics'); ?>
</head>

<body class="login">


<div id="wrapper">
 <div id="login-container">
  <div id="login">
   <div id="logo"> <a href="<?=base_url()?>"> <img src="<?php echo base_url('images/logo.png')?>" > </a> </div>
   <h3>Welcome to LiveWire CRM.</h3>
   <h5>Please sign in to get access.</h5>
  
   <?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
   
   <!--<div class="row">
    <div class="col-sm-6"> <a class="btn btn-twitter btn-block" href="./index.html"> <i class="fa fa-twitter"></i> &nbsp;&nbsp;Login with Twitter </a> </div>
    
    <div class="col-sm-6"> <a class="btn btn-facebook btn-block" href="./index.html"> <i class="fa fa-facebook"></i> &nbsp;&nbsp;Login with Facebook </a> </div>
    
   </div>-->
   <!-- /.row --> 
   
   <!--<span class="text-muted">OR, SIGN IN BELOW</span>-->
   
   <form class="form parsley-form" id="login-form" data-validate="parsley" method="post" action="" novalidate>
    <div class="form-group">
     <label for="login-username">Username</label>
     <!--<input type="text" placeholder="Username" id="login-username" class="form-control">-->
	 <input id="email" value="<?php if($this->session->userdata('temp_user_name') != ''){ echo $this->session->userdata('temp_user_name'); }elseif(isset($username)){  echo $username; }?>"  placeholder="Username" autofocus type="email" name="email" class="form-control parsley-validated" data-required="true">
    </div>
    <div class="form-group">
     <label for="login-password">Password</label>
     <!--<input type="password" placeholder="Password" id="login-password" class="form-control">-->
	 <input id="password" value="<?php if($this->session->userdata('temp_user_pswd') != ''){ echo $this->session->userdata('temp_user_pswd'); }elseif(isset($password)){  echo $password; }?>"  placeholder="Password" data-bvalidator="required"  type="password" name="password" class="form-control" data-required="true">
    </div>
    
    <?php if(count($multiple_users_session)>1){?>
    <div class="form-group">
    	<label for="login-username">Select Admin</label>
        <select class="form-control parsley-validated" name="slt_user_session" id="slt_user_session">
        	<option value="">Select Admin</option>
			<?php foreach($multiple_users_session as $row){ ?>
            	<option value="<?=$row['id']?>"><?=$row['admin_name']?></option>
            <?php } ?>
        </select>
    </div>
    <?php } ?>
    
    <div class="form-group">
     <button class="btn btn-primary btn-block" id="login-btn" type="submit">Sign In &nbsp; <i class="fa fa-play-circle"></i></button>
    </div>
   </form>
   
   
   
   <a class="btn btn-default" href="javascript:;" onClick="hide_show();">Forgot Password?</a> </div>
  <!-- /#login-form --> 
  
  </div>
  
  <div id="login-container" class="forgot" style="display:none;">
  <div id="login">
   <div id="logo"> <a href="<?=base_url();?>"> <img src="<?php echo base_url('images/logo.png')?>" > </a> </div>
   <h3>Forgot Password?</h3>
   <?php if(!empty($msg)){?>
        <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
        $newdata = array('msg'  => '');
        $this->session->set_userdata('message_session', $newdata);?> </div>
    <?php } ?>
   
  <form class="form parsley-form" id="login-form12" method="post" action="" novalidate >
    <div class="form-group text-left">
    <input id="email" value="<?php if($this->session->userdata('temp_passuser_name') != ''){ echo $this->session->userdata('temp_passuser_name'); }?>"  placeholder="Email" autofocus type="email" name="forgot_email" class="form-control parsley-validated" data-required="true">
    </div>
    <?php if(count($multiple_passusers_session)>1){?>
        <div class="form-group">
            <label for="login-username">Select Admin</label>
            <select class="form-control parsley-validated" name="slt_passuser_session" id="slt_passuser_session">
                    <option value="">Select Admin</option>
                            <?php foreach($multiple_passusers_session as $row){ ?>
                    <option value="<?=$row['id']?>"><?=$row['admin_name']?></option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>
    <div class="form-group">
     <button class="btn btn-primary btn-block" id="login-btn" type="submit">Submit &nbsp; <i class="fa fa-play-circle"></i></button>
	 
    </div>
	
   </form>
   <a class="btn btn-default" href="javascript:;" onClick="show();">Back To Log In</a> </div>
  
</div>

</body>
</html>
<script>
	function hide_show()
    {
		$('#login-container').hide(); 
        $('.forgot').show();        
    }
	function show()
    {
		$('#login-container').show();  
        $('.forgot').hide();       
    }
	
    $(document).ready(function(){
        <?php if(!empty($multiple_passusers_session)) { ?>
                hide_show();
        <?php } ?>
        $("#div_msg1").fadeOut(15000); 
        $("#div_msg").fadeOut(15000); 
		/*	$('[placeholder]').focus(function() {
			  var input = $(this);
			  if (input.val() == input.attr('placeholder')) 
			  {
				input.val('');
				input.removeClass('placeholder');
			  }
			}).blur(function() {
			  var input = $(this);
			  if (input.val() == '' || input.val() == input.attr('placeholder')) {
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			  }
			}).blur().parents('form').submit(function() {
			  $(this).find('[placeholder]').each(function() {
				var input = $(this);
				if (input.val() == input.attr('placeholder')) {
				  input.val('');
				}
			  })
			}); */

	});
   

</script>
