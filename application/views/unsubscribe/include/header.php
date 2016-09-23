<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class=""><!--<![endif]-->
<?php
$data=$this->session->userdata($this->lang->line('common_admin_session_label'));
$match_values = array("id"=>$data['id']);
$getfields=array("admin_name");
$ci = &get_instance();
$ci->load->model('Admin_model');
$admin_name = $ci->Admin_model->get_user($getfields,$match_values,'='); 
//pr($admin_name);
?> 
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title><?="LiveWire-CRM Admin";?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>fontcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>runtime.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>font-awesome.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>bootstrapcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>jquery-ui-1.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>checkboxcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>dropdowncrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>calendarcrm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>crm.css" type="text/css">
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>btncrm.css" type="text/css">
<!-- Colorbox css -->
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('js_path') ?>colorbox/colorbox.css"/>
<!-- Logout confirm -->
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_path')?>jquery.confirm.css"/>

<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery-1.9.1.js"></script>
<script src="<?=$this->config->item('js_path')?>jquery-1.7.2.min.js" type="text/javascript" ></script>
<script src="<?=$this->config->item('js_path')?>jquery.blockUI.js" type="text/javascript"></script>
<!--confirm box css-->
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.confirm.js"></script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>common.js"></script>
<script type="text/javascript" src="<?=$this->config->item('ck_editor_path')?>ckeditor.js"></script>
<!-- Colorbox js -->
<script type="text/javascript" src="<?=$this->config->item('js_path') ?>colorbox/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>colorbox/jquery.colorbox-min.js"></script>
<!--<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_path')?>datepicker_css/jquery-ui-timepicker-addon.css" />
<script type="text/javascript" src="<?=$this->config->item('js_path')?>datepicker_js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>datepicker_js/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>datepicker_js/jquery-ui-timepicker-addon.js"></script>-->

</head>

<body>
<div id="wrapper">
 <h1 id="site-logo"><a href="<?=base_url('admin');?>"><img src="<?php echo $this->config->item('image_path');?>logo.png" alt="Site Logo"></a></h1>
 <header id="header"> <a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle" class="navbar-toggle collapsed"> <i class="fa fa-cog"></i> </a> <a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle" class="navbar-toggle collapsed"> <i class="fa fa-reorder"></i> </a> </header>
 <!-- header -->
 
 <nav id="top-bar" class="collapse top-bar-collapse">
  <ul class="nav navbar-nav pull-left">
   <li class=""> <!--<a href="#"> <i class="fa fa-home"></i> Home </a> --></li>
   <!--<li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;"> Dropdown <span class="caret"></span> </a>
    <ul class="dropdown-menu" role="menu">
     <li><a href="javascript:;"><i class="fa fa-user"></i>&nbsp;&nbsp;Example #1</a></li>
     <li><a href="javascript:;"><i class="fa fa-calendar"></i>&nbsp;&nbsp;Example #2</a></li>
     <li class="divider"></li>
     <li><a href="javascript:;"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Example #3</a></li>
    </ul>
   </li>-->
  </ul>
  
 </nav>
 <!-- /#top-bar -->
<script>
 function logout()
	{
		$.confirm({
		'title': 'Logout','message': " <strong> Are you sure want to logout?",'buttons': {'Yes': {'class': '',
		'action': function(){
		<? if(!empty($admin['id'])){ ?>
				window.location="<?= base_url('admin/logout') ?>";
				<? }?>
				
		}},'No'	: {'class'	: 'special'}}});
	}
$(document).ready(function(){
	$('[placeholder]').focus(function() {
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
	}); 

});
 </script>
