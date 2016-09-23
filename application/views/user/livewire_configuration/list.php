<?php 
    /*
        @Description: Admin contact list
        @Author: Niral Patel
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));

?>
 <div id="content">
  <div id="content-header">
   <h1>Configuration</h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Configuration Settings</h3>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
  
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
		 	<div class="col-lg-3 col-md-3 text-center">
             <div class="iconbox">
				<a href="<?=base_url('user/user_profile')?>" id="configuration_view"><img src="<?=base_url('images/My Profile.png') ?>" alt="MyProfile" title="MyProfile"><span>MyProfile</span></a>
         	</div>
            </div>
            <?php /* if(!empty($this->modules_unique_name) && in_array('email_signature',$this->modules_unique_name)){?>
			<div class="col-lg-3 col-md-3 text-center">
               <div class="iconbox">
				<a href="<?=base_url('user/email_signature');?>" id="configuration_view"><img src="<?=base_url('images/Email Signature.png') ?>" alt="Email Signature" title="Email Signature"><span>Email Signature</span></a>
         	</div>
            </div>
            <? } ?>
            <?php if(!empty($this->modules_unique_name) && in_array('social_account',$this->modules_unique_name)){?>
			<div class="col-lg-3 col-md-3 text-center">
             <div class="iconbox">
				<a href="<?=base_url('user/social_account_master/');?>" id="configuration_view"><img src="<?=base_url('images/Social Account.png') ?>" alt="Social Account Master" title="Social Account Master"><span>Social Account</span></a>
         	</div>
            </div>
             <? } ?>
            <?php if(!empty($this->modules_unique_name) && in_array('work_time_configuration',$this->modules_unique_name)){?>
			<div class="col-lg-3 col-md-3 text-center">
            <div class="iconbox">
				<a href="<?=base_url('user/work_time_config_master/');?>" id="configuration_view"><img src="<?=base_url('images/Work Time Configuration.png') ?>" alt="Work Time Configuration" title="Work Time Configuration"><span>Work Time Configuration</span></a>
         	</div>
            	</div>
            <? } */ ?>
        </div>
       </div>
            

      <!-- /.portlet-content --> 
      
     </div>
    </div>
   </div>
  </div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  </div>
 </div>
 