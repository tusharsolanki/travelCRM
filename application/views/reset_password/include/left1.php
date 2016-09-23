<?
$admin = $this->session->userdata($this->lang->line('common_admin_session_label')); 
?>
<div id="sidebar-wrapper" class="collapse sidebar-collapse">
  <div id="search">
   <!--<form>
    <input class="form-control input-sm" name="search" placeholder="Search..." type="text">
    <button type="submit" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
   </form>-->
  </div>
  <!-- #search -->
  
  <nav id="sidebar">
   <ul id="main-nav" class="open-active">
    <li> <a href="<?=base_url('admin');?>"> <i class="fa fa-dashboard"></i>Dashboard </a> </li>
    <!--<li class="dropdown"> <a href="javascript:;"> <i class="fa fa-file-text"></i>Communications<span class="caret"></span> </a>
     <ul class="sub-nav">
      <li> <a href="#"> <i class="fa fa-user"></i> Profile </a> </li>
      <li> <a href="#"> <i class="fa fa-money"></i> Invoice </a> </li>
      <li> <a href="#"> <i class="fa fa-dollar"></i> Pricing Plans </a> </li>
     </ul>
    </li>
    <li class="dropdown"> <a href=""> <i class="fa fa-tasks"></i>Email<span class="caret"></span> </a>
     <ul class="sub-nav">
      <li> <a href="#"> <i class="fa fa-location-arrow"></i> Regular Elements </a> </li>
      <li> <a href="#"> <i class="fa fa-magic"></i> Extended Elements </a> </li>
      <li> <a href="#"> <i class="fa fa-check"></i> Validation </a> </li>
     </ul>
    </li>-->
    <li <?php if($this->uri->segment(2)=='contacts'){?> class="active" <?php } ?>><a href="<?=base_url('admin/contacts');?>"> <i class="fa fa-phone-square"></i>Contacts</a> </li>
    <!--<li <?php if($this->uri->segment(2)=='contact_masters'){?> class="active" <?php } ?>><a href="<?=base_url('admin/contact_masters/add_record');?>"> <i class="fa fa-phone-square"></i>Contact Masters</a> </li>-->
  <!--  <li <?php if($this->uri->segment(2)=='interaction_plan_masters'){?> class="active" <?php } ?>><a href="<?=base_url('admin/interaction_plan_masters/add_record');?>"> <i class="fa fa-phone-square"></i>Communication Masters</a> </li>-->
    
    <li <?php if($this->uri->segment(2)=='interaction_plans'){?> class="active" <?php } ?>><a href="<?=base_url('admin/interaction_plans');?>"> <i class="fa fa-sort-amount-asc"></i>Communications</a> </li>
    
    <li <?php if($this->uri->segment(2)=='socialmedia_post'){?> class="active" <?php } ?>><a href="<?=base_url('admin/socialmedia_post');?>"> <i class="fa fa-user"></i>Social</a> </li>
	
	<li <?php if($this->uri->segment(2)=='task'){?> class="active" <?php } ?>><a href="<?=base_url('admin/task');?>"> <i class="fa fa-tasks"></i>Tasks</a> </li>
	 <li <?php if($this->uri->segment(2)=='emails'){?> class="active" <?php } ?>><a href="<?=base_url('admin/emails');?>"> <i class="fa fa-envelope"></i>Emails</a> </li>
	 <li <?php if($this->uri->segment(2)=='sms'){?> class="active" <?php } ?>><a href="<?=base_url('admin/sms');?>"> <i class="fa fa-envelope-o"></i>SMS</a> </li>
	 
     <li class="<?php if(($this->uri->segment(2)=='email_library') || ($this->uri->segment(2)=='envelope_library') || ($this->uri->segment(2)=='socialmedia_post')|| ($this->uri->segment(2)=='phonecall_script') || ($this->uri->segment(2)=='sms_texts') || ($this->uri->segment(2)=='label_library') || ($this->uri->segment(2)=='letter_library') || ($this->uri->segment(2)=='mail_out')){?> active <?php } ?>dropdown"> <a href="javascript:;"> <i class="fa fa-file-text"></i>Marketing Library<span class="caret"></span> </a>
     <ul class="sub-nav">
     
      <li <?php if($this->uri->segment(2)=='email_library'){?> class="active" <?php } ?>><a href="<?=base_url('admin/email_library');?>"> <i class="fa fa-user"></i> Email Library </a> </li>
      <li <?php if($this->uri->segment(2)=='envelope_library'){?> class="active" <?php } ?>><a href="<?=base_url('admin/envelope_library');?>"> <i class="fa fa-user"></i> Envelope Library </a> </li>
      <li <?php if($this->uri->segment(2)=='socialmedia_post'){?> class="active" <?php } ?>><a href="<?=base_url('admin/socialmedia_post');?>"> <i class="fa fa-user"></i> Social Media Posts</a> </li>
      <li <?php if($this->uri->segment(2)=='phonecall_script'){?> class="active" <?php } ?>><a href="<?=base_url('admin/phonecall_script');?>"> <i class="fa fa-user"></i> Phone Call Scripts</a> </li>
      <li <?php if($this->uri->segment(2)=='sms_texts'){?> class="active" <?php } ?>><a href="<?=base_url('admin/sms_texts');?>"> <i class="fa fa-user"></i> SMS Texts</a> </li>
      <li <?php if($this->uri->segment(2)=='label_library'){?> class="active" <?php } ?>><a href="<?=base_url('admin/label_library');?>"> <i class="fa fa-user"></i> Label Library </a> </li>
       <li <?php if($this->uri->segment(2)=='letter_library'){?> class="active" <?php } ?>><a href="<?=base_url('admin/letter_library');?>"> <i class="fa fa-user"></i> Letter Library </a> </li>
      <?php /*?><li <?php if($this->uri->segment(2)=='phonecall_script'){?> class="active" <?php } ?>><a href="<?=base_url('admin/email_campaign');?>"> <i class="fa fa-user"></i> Email Campaign </a> </li><?php */?>
	   <li <?php if($this->uri->segment(2)=='mail_out'){?> class="active" <?php } ?>><a href="<?=base_url('admin/mail_out');?>"> <i class="fa fa-user"></i>Perform Mail Out</a> </li>
     </ul>
    </li>
	
	<!--<li <?php if($this->uri->segment(2)=='user_management'){?> class="active" <?php } ?>><a href="<?=base_url('admin/user_management');?>"> <i class="fa fa-group"></i>User Management</a> </li>-->
	
	<li <?php if($this->uri->segment(2)=='calendar'){?> class="active" <?php } ?>><a href="<?=base_url('admin/calendar');?>"> <i class="fa fa-calendar"></i>Calendar</a> </li>
	
	<!--<li <?php if($this->uri->segment(2)=='email_signature'){?> class="active" <?php } ?>><a href="<?=base_url('admin/email_signature');?>"> <i class="fa fa-phone-square"></i>Email Signature</a> </li>-->
	
	 <!-- <li <?php if($this->uri->segment(2)=='marketing_library_masters'){?> class="active" <?php } ?>><a href="<?=base_url('admin/marketing_library_masters');?>"> <i class="fa fa-phone-square"></i>Marketing Library Masters</a> </li>-->
    
    <!--<li class="dropdown"><a href="dummy.html"> <i class="fa fa-table"></i>Calender </a> </li>
    <li> <a href="#"><i class="fa fa-list-alt"></i> Lead Capturing </a> </li>
    <li class="dropdown"><a href="#"> <i class="fa fa-file-text-o"></i>Task</a> </li>
    <li class="dropdown"><a href="#"> <i class="fa fa-file-text-o"></i>Transaction</a> </li>
    <li class="dropdown"><a href="#"> <i class="fa fa-file-text-o"></i>Marketing Master Library</a> </li>
    <li class="dropdown"><a href="#"> <i class="fa fa-file-text-o"></i>Social Media</a> </li>-->
    <li class="dropdown"><a href="javascript:void(0);" onclick="logout();"><i class="fa fa-power-off"></i><span>Logout</span></a>
   </ul>
  </nav>
  <!-- #sidebar --> 
  
 </div>
 <!-- /#sidebar-wrapper -->
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
 </script>