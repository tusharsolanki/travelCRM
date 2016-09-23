<?php
$user = $this->session->userdata($this->lang->line('common_user_session_label'));
$tab_setting = check_joomla_tab_setting($user['id']);
?>
<div id="sidebar-wrapper" class="collapse sidebar-collapse">
  <div id="search">
   <!--<form>
    <input class="form-control input-sm" name="search" placeholder="Search..." type="text">
    <button type="submit" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
   </form>-->
  </div>
  <!-- #search -->
  <?php
        if($this->uri->segment(2) != 'contacts' && array_key_exists('contacts_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('contacts_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'contacts' && $this->uri->segment(3) == 'view_archive' && array_key_exists('contacts_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('contacts_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'contacts' && $this->uri->segment(3) != 'view_archive' && array_key_exists('contact_view_archive_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('contact_view_archive_sortsearchpage_data');}
		if($this->uri->segment(2) != 'interaction_plans' && array_key_exists('iplans_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('iplans_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'interaction_plans' && $this->uri->segment(3) == 'view_archive' && array_key_exists('iplans_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('iplans_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'interaction_plans' && $this->uri->segment(3) == '' && array_key_exists('iplan_view_archive_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('iplan_view_archive_sortsearchpage_data');}
		if($this->uri->segment(2) != 'interaction' && array_key_exists('interaction_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('interaction_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'interaction' && $this->uri->segment(3) == 'view_archive' && array_key_exists('interaction_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('interaction_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'interaction' && $this->uri->segment(4) == '' && array_key_exists('iview_archive_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('iview_archive_sortsearchpage_data');}
			
        if($this->uri->segment(2) != 'socialmedia_post' && (array_key_exists('socialmedia_post_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_socialmedia_post_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('socialmedia_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('socialmedia_post_sortsearchpage_data');
			$this->session->unset_userdata('def_socialmedia_post_sortsearchpage_data');
			$this->session->unset_userdata('socialmedia_selected_view_session');}
		if($this->uri->segment(2) != 'mail_out' && array_key_exists('mailout_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('mailout_sortsearchpage_data');}
			
		if($this->uri->segment(2) != 'task' && array_key_exists('task_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('task_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'task' && $this->uri->segment(3) == 'completed_task' && array_key_exists('task_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('task_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'task' && $this->uri->segment(3) == '' && array_key_exists('completed_task_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('completed_task_sortsearchpage_data');}
		 else if($this->uri->segment(2) == 'task' && $this->uri->segment(3) == 'my_task' && array_key_exists('my_task_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('my_task_sortsearchpage_data');}
		if($this->uri->segment(2) != 'emails' && array_key_exists('emails_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('emails_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'emails' && $this->uri->segment(3) == 'all_sent_mail' && array_key_exists('emails_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('emails_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'emails' && $this->uri->segment(3) == 'sent_email' && array_key_exists('emails_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('emails_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'emails' && $this->uri->segment(3) == '' && (array_key_exists('all_sent_maillist_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('sent_email_sortsearchpage_data',$this->session->all_userdata()))) {
            $this->session->unset_userdata('all_sent_maillist_sortsearchpage_data');
            $this->session->unset_userdata('sent_email_sortsearchpage_data');
        }
    if(($this->uri->segment(3) != 'view_error_data' || ($this->uri->segment(2) == 'sms' && ($this->uri->segment(3) == 'all_sent_sms' || $this->uri->segment(3) == 'sent_sms'))) && array_key_exists('error_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('error_sortsearchpage_data');}
		if(($this->uri->segment(2) != 'bomb_emails' || ($this->uri->segment(2) == '' && ($this->uri->segment(3) == 'bomb_all_sent_mail'))) && array_key_exists('bomb_sortsearchpage_data',$this->session->all_userdata()))
        {$this->session->unset_userdata('bomb_sortsearchpage_data');}
		else if($this->uri->segment(2) == 'bomb_emails' && $this->uri->segment(3) == '' && (array_key_exists('all_bombsent_maillist_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('sent_bomb_sortsearchpage_data',$this->session->all_userdata()))) {
            $this->session->unset_userdata('all_bombsent_maillist_sortsearchpage_data');
            $this->session->unset_userdata('sent_bomb_sortsearchpage_data');
        }
		if($this->uri->segment(2) != 'sms' && array_key_exists('sms_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('sms_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'sms' && $this->uri->segment(3) == 'all_sent_sms' && array_key_exists('sms_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('sms_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'sms' && $this->uri->segment(3) == 'sent_sms' && array_key_exists('sms_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('sms_sortsearchpage_data');}
        else if($this->uri->segment(2) == 'sms' && $this->uri->segment(3) == '' && (array_key_exists('all_sent_sms_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('sent_sms_sortsearchpage_data',$this->session->all_userdata()))) {
            $this->session->unset_userdata('all_sent_sms_sortsearchpage_data');
            $this->session->unset_userdata('sent_sms_sortsearchpage_data');
        }
        if($this->uri->segment(2) != 'email_library' && (array_key_exists('email_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_email_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('email_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('email_library_sortsearchpage_data');
			$this->session->unset_userdata('def_email_library_sortsearchpage_data');
			$this->session->unset_userdata('email_selected_view_session');}
			
		 if($this->uri->segment(2) != 'bomb_library' && (array_key_exists('bomb_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_bomb_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('bomb_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('bomb_library_sortsearchpage_data');
			$this->session->unset_userdata('def_bomb_library_sortsearchpage_data');
			$this->session->unset_userdata('bomb_selected_view_session');}
        if($this->uri->segment(2) != 'auto_responder' && (array_key_exists('auto_responder_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_auto_responder_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('auto_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('auto_responder_sortsearchpage_data');
			$this->session->unset_userdata('def_auto_responder_sortsearchpage_data');
			$this->session->unset_userdata('auto_selected_view_session');}
        if($this->uri->segment(2) != 'envelope_library' && (array_key_exists('envelope_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_envelope_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('envelop_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('envelope_library_sortsearchpage_data');
			$this->session->unset_userdata('def_envelope_library_sortsearchpage_data');
			$this->session->unset_userdata('envelop_selected_view_session');}
        if($this->uri->segment(2) != 'phonecall_script' && (array_key_exists('phonecall_script_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_phonecall_script_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('phone_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('phonecall_script_sortsearchpage_data');
			$this->session->unset_userdata('def_phonecall_script_sortsearchpage_data');
			$this->session->unset_userdata('phone_selected_view_session');}
        if($this->uri->segment(2) != 'sms_texts' && (array_key_exists('sms_texts_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_sms_texts_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('smstext_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('sms_texts_sortsearchpage_data');
			$this->session->unset_userdata('def_sms_texts_sortsearchpage_data');
			$this->session->unset_userdata('smstext_selected_view_session');}
        if($this->uri->segment(2) != 'label_library' && (array_key_exists('label_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_label_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('label_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('label_library_sortsearchpage_data');
			$this->session->unset_userdata('def_label_library_sortsearchpage_data');
			$this->session->unset_userdata('label_selected_view_session');}
        if($this->uri->segment(2) != 'letter_library' && (array_key_exists('letter_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('def_letter_library_sortsearchpage_data',$this->session->all_userdata()) || array_key_exists('phone1_selected_view_session',$this->session->all_userdata())))
            {$this->session->unset_userdata('letter_library_sortsearchpage_data');
			$this->session->unset_userdata('def_letter_library_sortsearchpage_data');
			$this->session->unset_userdata('phone1_selected_view_session');}
	
		if($this->uri->segment(3) != 'daily_task' && array_key_exists('dashboard_task_sortsearchpage_data1',$this->session->all_userdata()))
            $this->session->unset_userdata('dashboard_task_sortsearchpage_data1');
		if($this->uri->segment(3) != 'telephone_task' && array_key_exists('dashboard_task_sortsearchpage_data2',$this->session->all_userdata()))
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data2');
		if($this->uri->segment(3) != 'email_task' && array_key_exists('dashboard_task_sortsearchpage_data3',$this->session->all_userdata()))
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data3');
		if($this->uri->segment(3) != 'sms_task' && array_key_exists('dashboard_task_sortsearchpage_data4',$this->session->all_userdata()))	
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data4');
		if($this->uri->segment(3) != 'dashboard_task_sortsearchpage_data5' && array_key_exists('dashboard_task_sortsearchpage_data5',$this->session->all_userdata()))
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data5');
		
		if($this->uri->segment(3) != 'interaction_plan_queued_list' && $this->uri->segment(3) != 'view_interaction_data' && (array_key_exists('emails_interaction_plan_queued_list_data',$this->session->all_userdata()) || array_key_exists('sms_interaction_plan_queued_list_data',$this->session->all_userdata())))
		{
			$this->session->unset_userdata('emails_interaction_plan_queued_list_data');
			$this->session->unset_userdata('sms_interaction_plan_queued_list_data');
		}
		if($this->uri->segment(3) != 'to_do_task' && array_key_exists('dashboard_task_sortsearchpage_data6',$this->session->all_userdata()))
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data6');
		
		if($this->uri->segment(2) != 'listing_manager' && array_key_exists('property_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('property_sortsearchpage_data');}
            
            if($this->uri->segment(2) == 'leads_dashboard' && $this->uri->segment(3) != 'view_record' && array_key_exists('joomla_dbord_selected_view_session',$this->session->all_userdata()))
            {$this->session->unset_userdata('joomla_dbord_selected_view_session');}
            if($this->uri->segment(2) != 'contacts' && array_key_exists('joomla_selected_view_session',$this->session->all_userdata()))
            {$this->session->unset_userdata('joomla_selected_view_session');}
            
            if($this->uri->segment(2) == 'lead_capturing' && $this->uri->segment(3) != 'form_lead_list' && array_key_exists('submitted_formlead_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('submitted_formlead_sortsearchpage_data');}

            if($this->uri->segment(2) == 'dashboard' && $this->uri->segment(3) != 'form_lead_list' && array_key_exists('dashboard_formlead_sortsearchpage_data',$this->session->all_userdata()))
            {$this->session->unset_userdata('dashboard_formlead_sortsearchpage_data');}
			

   ?>
   
  <nav id="sidebar">
   <ul id="main-nav" class="open-active">
    <li <?php if($this->uri->segment(2)=='dashboard'){?> class="active" <?php } ?>> <a href="<?=base_url('user/dashboard');?>"> <i class="fa fa-dashboard"></i> Dashboard </a> </li>
     <?php /* if(!empty($this->modules_unique_name) && in_array('lead_dashboard',$this->modules_unique_name)){?>
    <?php if(!empty($tab_setting) && $tab_setting[0]['lead_dashboard_tab'] == '1') { ?>
    <li <?php if($this->uri->segment(2)=='leads_dashboard' || $this->uri->segment(2)=='joomla_assign'){?> class="active" <?php } ?>><a href="<?=base_url('user/leads_dashboard');?>" style="color:#eb2027 !important"> <i class="fa fa-plus-square"></i>Leads Dashboard</a> </li>
    <?php } ?>
    <?php }*/ ?>
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
    <?php if(!empty($this->modules_unique_name) && in_array('contact',$this->modules_unique_name)){?>
    <li <?php if($this->uri->segment(2)=='contacts'){?> class="active" <?php } ?>><a href="<?=base_url('user/contacts');?>"> <i class="fa fa-phone-square"></i>Contacts</a> </li>
     <?php } ?>
     <?php /*if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
	 <li <?php if(($this->uri->segment(2)=='interaction_plans') || ($this->uri->segment(2)=='interaction')){?> class="active" <?php } ?>><a href="<?=base_url('user/interaction_plans/interaction_plans_home');?>"> <i class="fa fa-sort-amount-asc"></i>Communications</a> </li>
      <?php } ?>
       <?php if(!empty($this->modules_unique_name) && in_array('social',$this->modules_unique_name)){?>
	 <li <?php if($this->uri->segment(2)=='social'){?> class="active" <?php } ?>><a href="<?=base_url('user/social/social_home');?>"> <i class="fa fa-thumbs-o-up"></i> Social</a> </li>
      <?php } ?>
      <?php if(!empty($this->modules_unique_name) && in_array('tasks',$this->modules_unique_name)){?>
    <li <?php if($this->uri->segment(2)=='task'){?> class="active" <?php } ?>><a href="<?=base_url('user/task/task_home');?>"> <i class="fa fa-tasks"></i>Tasks</a> </li>
     <?php } ?>
     

     <?php if(!empty($this->modules_unique_name) && (in_array('email_blast',$this->modules_unique_name) || in_array('bomb_bomb_email_blast',$this->modules_unique_name))){?>
     <li <?php if($this->uri->segment(2)=='emails' || $this->uri->segment(2)=='bomb_emails'){?> class="active" <?php } ?>><a href="<?=base_url('user/emails/emails_home');?>"> <i class="fa fa-envelope"></i><?=$this->lang->line('emails_left_menu');?></a> </li>
     <?php } ?>
     <?php if(!empty($this->modules_unique_name) && in_array('text_blast',$this->modules_unique_name)){?>
	 <li <?php if($this->uri->segment(2)=='sms'){?> class="active" <?php } ?>><a href="<?=base_url('user/sms/sms_home');?>"> <i class="fa fa-envelope-o"></i><?=$this->lang->line('sms_left_menu');?></a> </li>
      <?php } ?>
       <?php if(!empty($this->modules_unique_name) && in_array('mail_blast',$this->modules_unique_name)){?>
	 <li <?php if($this->uri->segment(2)=='mail_out'){?> class="active" <?php } ?>><a href="<?=base_url('user/mail_out/mail_out_home');?>"> <i class="fa fa-mail-forward"></i><?=$this->lang->line('mail_out_header');?></a> </li>
     <?php }*/ ?>
    
      <?php if(!empty($this->modules_unique_name) && in_array('form_builder',$this->modules_unique_name)){?>
     <li <?php if(($this->uri->segment(2)=='lead_capturing') || ($this->uri->segment(2)=='lead_capturing_view')){?> class="active" <?php } ?>><a href="<?=base_url('user/lead_capturing/lead_capturing_home');?>"> <i class="fa fa-file"></i><?=$this->lang->line('lead_capturing_header');?></a> </li>
      <?php } ?>
    <?php /*if(!empty($this->modules_unique_name) && in_array('template_library',$this->modules_unique_name)){?>
    <li class="<?php if(($this->uri->segment(2)=='marketing_library_home') || ($this->uri->segment(2)=='email_library') || ($this->uri->segment(2)=='auto_responder') || ($this->uri->segment(2)=='envelope_library') || ($this->uri->segment(2)=='socialmedia_post')|| ($this->uri->segment(2)=='phonecall_script') || ($this->uri->segment(2)=='sms_texts') || ($this->uri->segment(2)=='sms_texts_response') || ($this->uri->segment(2)=='label_library') || ($this->uri->segment(2)=='letter_library') || ($this->uri->segment(2)=='bomb_library')){?> active <?php } ?>"> 
     <a href="<?=base_url('user/marketing_library_home');?>"> <i class="fa fa-file-text"></i><?=$this->lang->line('marketing_title');?><!--<span class="caret"></span>--> </a>
    </li>
     <?php } ?>
      <?php if(!empty($this->modules_unique_name) && in_array('listing_manager',$this->modules_unique_name)){?>
    <li <?php if(($this->uri->segment(2)=='listing_manager')){?> class="active" <?php } ?>><a href="<?=base_url('user/listing_manager');?>"> <i class="fa fa-list-alt"></i>Listing Manager</a> </li>
     <?php } ?>
      <?php if(!empty($this->modules_unique_name) && in_array('calendar',$this->modules_unique_name)){?>
	<li <?php if($this->uri->segment(2)=='calendar'){?> class="active" <?php } ?>><a href="<?=base_url('user/calendar');?>"> <i class="fa fa-calendar"></i>Calendar</a> </li>
     <?php } ?>
	 <!--<li <?php if($this->uri->segment(2)=='mail_out'){?> class="active" <?php } ?>><a href="<?=base_url('user/mail_out');?>"> <i class="fa fa-mail-forward"></i>Perform Mail Out</a> </li>-->
     <li <?php if($this->uri->segment(2)=='note'){?> class="active" <?php } ?>><a href="<?=base_url('user/note/');?>"><i class="fa fa-clipboard"></i>
Notes</a> </li>
     */ ?>
    <li class="dropdown"><a href="javascript:void(0);" onclick="logout();"><i class="fa fa-power-off"></i><span>Log out</span></a>
   </ul>
  </nav>
  <!-- #sidebar --> 
  
 </div>
 <!-- /#sidebar-wrapper -->
 <script>
 function logout()
	{
		$.confirm({
		'title': 'Log out','message': " <strong> Are you sure want to log out?",'buttons': {'Yes': {'class': 'logout_class',
		'action': function(){
		<? if(!empty($user['id'])){ ?>
				window.location="<?= base_url('user/logout') ?>";
				<? }?>
				
		}},'No'	: {'class'	: 'special'}}});
	}
 </script>
