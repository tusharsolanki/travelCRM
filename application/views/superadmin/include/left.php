<?
$superadmin = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
if($this->uri->segment(2) != 'interaction_plans')
	{$this->session->unset_userdata('iplans_sortsearchpage_data');}
else if($this->uri->segment(2) == 'interaction_plans' && $this->uri->segment(3) == 'view_archive')
	{$this->session->unset_userdata('iplans_sortsearchpage_data');}
else if($this->uri->segment(2) == 'interaction_plans' && $this->uri->segment(3) == '')
	{$this->session->unset_userdata('iplan_view_archive_sortsearchpage_data');}
	
if($this->uri->segment(2) != 'default_interaction_plans')
	{$this->session->unset_userdata('default_iplans_sortsearchpage_data');}
else if($this->uri->segment(2) == 'default_interaction_plans' && $this->uri->segment(3) == 'view_archive')
	{$this->session->unset_userdata('iplans_sortsearchpage_data');}
else if($this->uri->segment(2) == 'default_interaction_plans' && $this->uri->segment(3) == '')
	{$this->session->unset_userdata('iplan_view_archive_sortsearchpage_data');}	
	
if($this->uri->segment(2) != 'interaction')
	{$this->session->unset_userdata('interaction_sortsearchpage_data');}
else if($this->uri->segment(2) == 'interaction' && $this->uri->segment(3) == 'view_archive')
	{$this->session->unset_userdata('interaction_sortsearchpage_data');}
else if($this->uri->segment(2) == 'interaction' && $this->uri->segment(4) == '')
	{$this->session->unset_userdata('iview_archive_sortsearchpage_data');}
if($this->uri->segment(2) != 'user_rr_weightage')
    {$this->session->unset_userdata('agent_rr_weightage_sortsearchpage_data');}
if($this->uri->segment(2) != 'joomla_tab_configuration')
    {$this->session->unset_userdata('joomla_tab_config_sortsearchpage_data');}

if($this->uri->segment(2) != 'default_interaction')
	{$this->session->unset_userdata('interaction_sortsearchpage_data');}
else if($this->uri->segment(2) == 'default_interaction' && $this->uri->segment(3) == 'view_archive')
	{$this->session->unset_userdata('interaction_sortsearchpage_data');}
else if($this->uri->segment(2) == 'default_interaction' && $this->uri->segment(4) == '')
	{$this->session->unset_userdata('iview_archive_sortsearchpage_data');}
    
if($this->uri->segment(2) != 'country')
    {$this->session->unset_userdata('country_sortsearchpage_data');}
if($this->uri->segment(2) != 'state')
    {$this->session->unset_userdata('state_sortsearchpage_data');}

if($this->uri->segment(2) != 'mls_map')
    {$this->session->unset_userdata('mls_connect_sortsearchpage_data');}

if($this->uri->segment(2) != 'superadmin_management')
    {$this->session->unset_userdata('superadmin_management_sortsearchpage_data');}
if($this->uri->segment(2) != 'admin_management')
    {$this->session->unset_userdata('admin_management_sortsearchpage_data');}

if($this->uri->segment(2) != 'email_library')
	{$this->session->unset_userdata('email_library_sortsearchpage_data');}
if($this->uri->segment(2) != 'auto_responder')
	{$this->session->unset_userdata('auto_responder_sortsearchpage_data');}
if($this->uri->segment(2) != 'envelope_library')
	{$this->session->unset_userdata('envelope_library_sortsearchpage_data');}
if($this->uri->segment(2) != 'phonecall_script')
	{$this->session->unset_userdata('phonecall_script_sortsearchpage_data');}
if($this->uri->segment(2) != 'socialmedia_post')
    {$this->session->unset_userdata('socialmedia_post_sortsearchpage_data');}
if($this->uri->segment(2) != 'sms_texts')
    {$this->session->unset_userdata('sms_texts_sortsearchpage_data');}
if($this->uri->segment(2) != 'label_library')
    {$this->session->unset_userdata('label_library_sortsearchpage_data');}
if($this->uri->segment(2) != 'letter_library')
    {$this->session->unset_userdata('letter_library_sortsearchpage_data');}    
if($this->uri->segment(2) != 'sms_texts_response')
    {$this->session->unset_userdata('sms_texts_response_sortsearchpage_data');}    
if($this->uri->segment(2) != 'assign_package')
    {$this->session->unset_userdata('assign_package_sortsearchpage_data');}    
if($this->uri->segment(2) != 'map_joomla')
    {$this->session->unset_userdata('map_joomla_sortsearchpage_data');}
if($this->uri->segment(2) != 'mls')
	$this->session->unset_userdata('mls_amenity_sortsearchpage_data');
if($this->uri->segment(2) != 'child_admin')
    $this->session->unset_userdata('child_admin_sortsearchpage_data');
if($this->uri->segment(2) == 'child_admin' && $this->uri->segment(3) != 'carousels')
    $this->session->unset_userdata('child_carousels_sortsearchpage_data');
    
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
    <li <?php if($this->uri->segment(2)=='dashboard'){?> class="active" <?php } ?>> <a href="<?=base_url('superadmin')."/dashboard";?>"> <i class="fa fa-dashboard"></i>Dashboard </a> </li>
	<li <?php if($this->uri->segment(2)=='superadmin_management'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/superadmin_management');?>"> <i class="fa fa-user"></i>Super admin Management</a> </li>
	<li <?php if($this->uri->segment(2)=='admin_management'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/admin_management');?>"> <i class="fa fa-group"></i>Admin Management</a> </li>
	<?php /* ?>
	<li <?php if(($this->uri->segment(2)=='package_management') || ($this->uri->segment(2)=='assign_package') ){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/package_management');?>"> <i class="fa fa-bookmark"></i>Package Management</a> </li>
	<li <?php if(($this->uri->segment(2)=='interaction_plans') || ($this->uri->segment(2)=='interaction') ){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/interaction_plans');?>"> <i class="fa fa-sort-amount-asc"></i>Premium Communications</a> </li>
        <?php */ ?>
        <li class="<?php if(($this->uri->segment(2)=='contact_masters') || ($this->uri->segment(2)=='marketing_library_masters') || ($this->uri->segment(2)=='marketing_library_home') || ($this->uri->segment(2)=='email_library') || ($this->uri->segment(2)=='auto_responder') || ($this->uri->segment(2)=='envelope_library') || ($this->uri->segment(2)=='socialmedia_post')|| ($this->uri->segment(2)=='phonecall_script') || ($this->uri->segment(2)=='sms_texts') || ($this->uri->segment(2)=='label_library') || ($this->uri->segment(2)=='letter_library')  || ($this->uri->segment(2)=='sms_texts_response') || ($this->uri->segment(2)=='property_list_masters') || ($this->uri->segment(2)=='default_interaction_plans') || ($this->uri->segment(2)=='mls_master')){?> active <?php } ?>dropdown"> <a href="javascript:;"> <i class="fa fa-cog"></i>Defaults In Configuration<span class="caret"></span> </a>
     <ul class="sub-nav">
     
      <li <?php if($this->uri->segment(2)=='contact_masters'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/contact_masters');?>"> <i class="fa fa-file-text"></i>Contacts</a> </li>
      <?php /* ?>
      <li <?php if($this->uri->segment(2)=='mls_master'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/mls_master');?>"> <i class="fa fa-file-text"></i>MLS Master</a> </li>
      <li <?php if($this->uri->segment(2)=='marketing_library_masters'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/marketing_library_masters');?>"> <i class="fa fa-file-text"></i>Master Library Configuration</a> </li>
  <li class="<?php if(($this->uri->segment(2)=='marketing_library_home') || ($this->uri->segment(2)=='email_library') || ($this->uri->segment(2)=='auto_responder') || ($this->uri->segment(2)=='envelope_library') || ($this->uri->segment(2)=='socialmedia_post')|| ($this->uri->segment(2)=='phonecall_script') || ($this->uri->segment(2)=='sms_texts') || ($this->uri->segment(2)=='label_library') || ($this->uri->segment(2)=='letter_library') || ($this->uri->segment(2)=='sms_texts_response')){?> active <?php } ?>"> 
     <a href="<?=base_url('superadmin/marketing_library_home');?>"> <i class="fa fa-file-text"></i><?=$this->lang->line('marketing_title');?><!--<span class="caret"></span>--> </a>
    </li>
     <li <?php if($this->uri->segment(2)=='default_interaction_plans'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/default_interaction_plans');?>"> <i class="fa fa-file-text"></i>Communications</a> </li>
     
     <li <?php if($this->uri->segment(2)=='property_list_masters'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/property_list_masters');?>"> <i class="fa fa-file-text"></i>Property List</a> </li>
     <?php */ ?>
 </ul>
    </li>

    <?php /* ?>
    <li <?php if($this->uri->segment(2)=='map_joomla'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/map_joomla');?>"> <i class="fa fa-exchange"></i>Map With joomla</a> </li>
    <li <?php if($this->uri->segment(2)=='child_admin'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/child_admin');?>"> <i class="fa fa-desktop"></i>Manage Website</a> </li>
    <li <?php if($this->uri->segment(2)=='joomla_tab_configuration'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/joomla_tab_configuration');?>"> <i class="fa fa-cog"></i><?=$this->lang->line('superadmin_joomla_tab_config')?></a> </li>
<li <?php if($this->uri->segment(2)=='mls_map'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/mls_map');?>"> <i class="fa fa-exchange"></i>MLS Mapping</a> </li>
<?php /* <li <?php if($this->uri->segment(2)=='mls'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/mls');?>"> <i class="fa fa-exchange"></i>MLS</a> </li> 
    <?php /*<li <?php if($this->uri->segment(2)=='user_rr_weightage'){?> class="active" <?php } ?>><a href="<?=base_url('superadmin/user_rr_weightage');?>"> <i class="fa fa-user"></i>Lead Distribution</a> </li> */?>
   
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
		<? if(!empty($superadmin['id'])){ ?>
				window.location="<?= base_url('superadmin/logout') ?>";
				<? }?>
				
		}},'No'	: {'class'	: 'special'}}});
	}
 </script>