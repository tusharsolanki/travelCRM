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
<title>Quality Survey</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="<?php echo $this->config->item('js_path')?>jquery-1.9.1.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('js_path')?>jquery.bvalidator.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_path')?>bvalidator.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/admin/admin.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/admin/responsive.css')?>">

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
<section id="wrapper" style="display:inline-block;">
  <div class="header_top">
    <div id="login_logo"><a href="#"><img src="<?php echo base_url('images/admin2/logo.png')?>" title="logo" alt="logo" ></a></div>
  </div>
  <div class="login_form_main">
    <form id="login" method="post" action="">
      <div class="left_side">
        <h1>Login</h1>
        <i><img src="<?php echo base_url('images/admin2/login_lock_icon.jpg')?>" alt="Logo" title="Logo"></i> </div>
      <div class="right_side">
        <p>
          <input id="email" class="textbox" value="<?php if(isset($username)){  echo $username; }?>" placeholder="Username" autofocus="" data-bvalidator="required,email"  type="text" name="email">
          <img src="<?php echo base_url('images/admin2/user_img.png')?>" alt="user"> </p>
        <p>
          <input id="password" class="textbox" value="<?php if(isset($username)){  echo $password; }?>"  placeholder="******" data-bvalidator="required"  type="password" name="password">
          <img src="<?php echo base_url('images/admin2/pass_icon.png')?>" alt="password"> </p>
        <b> <a id="div_forgotpwdlink" href="javascript:void(0);" onClick="hide_show('div_forgotpwd','div_login',1000);">Forgot Password</a></b><br>
        <div class="remember">
          <input type="checkbox" id="rb-check" value="on"  name="rememberme" />  <span>Remember me</span>
          <input id="submit" class="login_btn" value="Login" type="submit">
        </div>
      </div>
    </form>
    <form id="forgotpwd" method="post" action="" style="display:none;">
      <h1>Forgot Password?</h1>
      <?php if(isset($forgotpwd_msg)) echo '<fieldset id="error_fieldset"><label class="error">'.$forgotpwd_msg.'</label></fieldset>'; ?>
      <fieldset id="inputs_email">
        <input id="email_forgot" placeholder="Email" autofocus="" data-bvalidator="required,email" type="text" name="email">
        <input id="forgotpwd" value="1" name="forgotpwd" type="hidden"/>
      </fieldset>
      <fieldset id="actions">
        <input id="submit_new" value="Submit" type="submit">
        <a id="div_loginlink" href="javascript:void(0);" onClick="hide_show('div_login','div_forgotpwd',1000);">Login</a>
      </fieldset>
    </form>
  </div>
</section>
<div class="cf"></div>
<div class="login_footer">© 2014 Customer Thermometer LLP - v1.8.4 - Terms of use | <a href="#">Privacy Policy</a> | <a href="#">Contact</a></div>
</body>
</html>
<script>
    $(document).ready(function()
    {      
	  $('#login').bValidator();
	  $('#forgotpwd').bValidator();
	  
        var forgotpwd_msg = '<?php echo (isset($forgotpwd_msg)) ? $forgotpwd_msg : '';?>';
        
        if(forgotpwd_msg)
        {
            $('#forgotpwd').css('display','inline');
            $('#login').css('display','none');
        }
        else
        {
            $('#forgotpwd').css('display','none');
            $('#login').css('display','inline');
        }
    });
    function hide_show(showdiv,hidediv,timer)
    {
        var show = showdiv.split('div_');
        var hide = hidediv.split('div_');
        hide[1] ? $('#'+hide[1]).hide(timer) : '';
        show[1] ? $('#'+show[1]).show(timer) : '';
        $('#'+showdiv).show(timer); 
        $('#'+hidediv).hide(timer);        
        $('#'+showdiv+'link').hide();
        $('#'+hidediv+'link').show();        
    }
</script>
<style type="text/css">
.custom-checkbox {
	width: 14px;
	height: 14px;
	display: inline-block;
	position: relative;
	z-index: 1;
	top: 3px;
	background: url("../images/admin2/disselect.png") no-repeat;
}
.custom-checkbox:hover {
	background: url("../images/admin2/selected.png") no-repeat;
}
.custom-checkbox.selected {
	background: url("../images/admin2/selected.png") no-repeat;
}
.custom-checkbox input[type="checkbox"] {
	margin: 0;
	z-index: 2;
	cursor: pointer;
	outline: none;
	opacity: 0;
		/* CSS hacks for older browsers */
		_noFocusLine: expression(this.hideFocus=true);
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	-khtml-opacity: 0;
	-moz-opacity: 0;
}
/* Let's Beautify Our Form */
    form {
	margin: 0px;
}
label {
	display: block;
	padding: 2px 0;
}
 
</style>
<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script type="text/javascript">
    function customCheckbox(checkboxName){
        var checkBox = $('input[name="'+ checkboxName +'"]');
        $(checkBox).each(function(){
		$(this).wrap( "<span class='custom-checkbox'></span>" );
		 
            if($(this).is(':checked')){
                $(this).parent().addClass("selected");
            }
        });
        $(checkBox).click(function(){
            $(this).parent().toggleClass("selected");
        });
    }
    $(document).ready(function (){
        customCheckbox("rememberme");
      <?php	if($username != '' && $password != '')	{ ?>
		 	$('.remember .custom-checkbox').addClass("selected");
			$('.remember .custom-checkbox #rb-check').attr("checked","checked");
			<? }?>
    })
</script>