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
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));

$tab_result = check_joomla_tab_setting($admin_session['id']);
$path_reset = $viewname.'/change_password';
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
            <?php if(!empty($this->modules_unique_name) && in_array('configuration_contact',$this->modules_unique_name)){?>
          <div class="col-lg-3 col-md-3 text-center">
           	<div class="iconbox"><a href="<?=base_url('admin/contact_masters/');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Contacts.png') ?>" alt="Contact Management" title="Contacts Management"><span>Contacts</span></a></div>
		  	 </div>
            <? } ?>
            <?php /* if(!empty($this->modules_unique_name) && in_array('configuration_template_library',$this->modules_unique_name)){?>
            <div class="col-lg-3 col-md-3 text-center">
				<div class="iconbox"><a href="<?=base_url('admin/marketing_library_masters');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Marketing Master Library.png') ?>" alt="Master Template Library Configuration" title="Master Template Library Configuration"><span>Master Template Library</span></a></div>
         	</div>
            <? } ?>
            <?php if(!empty($this->modules_unique_name) && in_array('work_time_configuration',$this->modules_unique_name)){?>
            <div class="col-lg-3 col-md-3 text-center">
				<div class="iconbox"><a href="<?=base_url('admin/work_time_config_master/');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Work Time Configuration.png') ?>" alt="Work Time Configuration" title="Work Time Configuration"><span>Work Time Configuration</span></a></div>
         	</div>
             <? } ?>
			<?php if(!empty($this->modules_unique_name) && in_array('social_account',$this->modules_unique_name)){?>
            <div class="col-lg-3 col-md-3 text-center">
				<div class="iconbox"><a href="<?=base_url('admin/social_account_master/');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Social Account.png') ?>" alt="Social Account Master" title="Social Account Master"><span>Social Account</span></a></div>
         	</div>
            <? } ?>
            <?php if(!empty($this->modules_unique_name) && in_array('email_signature',$this->modules_unique_name)){?>
			<div class="col-lg-3 col-md-3 text-center clear">
				<div class="iconbox"><a href="<?=base_url('admin/email_signature');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Email Signature.png') ?>" alt="Email Signature" title="Email Signature"><span>Email Signature</span></a>
</div>         	</div>
			<? } */ ?>
			<?php if(!empty($this->modules_unique_name) && in_array('user_management',$this->modules_unique_name)){?>
			<div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="<?=base_url('admin/user_management');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/USEICON.png') ?>" alt="User Management" title="User Management"><span>User Management</span></a></div>
         	</div>
            <? } ?>
             <?php /* if(!empty($this->modules_unique_name) && (in_array('lead_distribution_agent',$this->modules_unique_name) || in_array('lead_distribution_lender',$this->modules_unique_name))){?>
                        <?php if(!empty($tab_result) && $tab_result[0]['lead_dashboard_tab'] == '1') { ?>
            <div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="<?=base_url('admin/user_rr_weightage');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/user-rr-assignment.jpg') ?>" alt="User RR Assignment" title="User RR Assignment"><span>User RR Assignment</span></a></div>
         	</div>
                        <?php } ?>
                        <?php } */ ?>
            <div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="<?=base_url('admin/livewire_configuration/edit_profile');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/My Profile.png') ?>" alt="Profile" title="Profile"><span>Profile</span></a></div>
         	</div>
            <?php /* if(!empty($this->modules_unique_name) && in_array('configuration_listing_manager',$this->modules_unique_name)){?>
            <div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="<?=base_url('admin/property_list_masters');?>" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/listing-masters.jpg') ?>" alt="Listing Manager" title="Listing Manager"><span>Listing Manager</span></a></div>
         	</div>
            <? }*/ ?>
			<div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="#basicModal" data-toggle="modal" id="configuration_view"><img class="img-responsive" src="<?=base_url('images/Change Password.png') ?>" alt="Change Password" title="Change Password"><span>Change Password</span></a></div>
         	</div>
            <?php /* if(!empty($admin_session['user_type']) && $admin_session['user_type'] == '2') { ?>
            <div class="col-lg-3 col-md-3 text-center ">
				<div class="iconbox"><a href="<?=base_url('admin/assistant_management');?>"><img class="img-responsive" src="<?=base_url('images/assistant-management.jpg') ?>" alt="Assistant Management" title="Assistant Management"><span>Assistant Management</span></a></div>
         	</div>
            <?php }*/ ?>
           
          
          <?php if(!empty($this->modules_unique_name) && in_array('configuration_contact',$this->modules_unique_name)){?>
          <div class="col-lg-3 col-md-3 text-center">
            <div class="iconbox"><a href="<?=base_url('admin/department_masters/');?>" id="configuration_view"><img class="img-responsive" src="" alt="Department Management" title="Department Management"><span>Department</span></a></div>
         </div>
            <? } ?>

            

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
 <div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  		<div class="modal-dialog">
    	<div class="modal-content">
      	<div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h4 class="modal-title">Change Password</h4>
      </div>
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php //echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_reset;?>" novalidate >

      <div class="modal-body">
	 
	 	<div class="col-xs-12 mrgb2 form-group">
	  	<label for="validateSelect">Old Password <span class="mandatory_field margin-left-5px">*</span></label>
	  	<input id="oldpassword" name="oldpassword" class="form-control parsley-validated" type="password" data-required="true" onchange="check_old_password(this.value);"/>
	  </div>
	 	<div class="col-xs-12 mrgb2 form-group">
	  	<label for="validateSelect">New Password <span class="mandatory_field margin-left-5px">*</span></label>
	  	<input id="npassword" name="npassword" class="form-control parsley-validated" type="password" data-required="true"/>
	  
		</div>
		<div class="col-xs-12 mrgb2 form-group">
		  <label for="validateSelect">Confirm Password <span class="mandatory_field margin-left-5px">*</span></label>
		  <input id="cpassword" name="cpassword" class="form-control parsley-validated" type="password" data-equalto="#npassword" data-required="true">
		</div>
		
	  </div>
	    
      <div class="col-sm-12 text-center mrgb4">
       
		
		<input type="submit" id="submit" class="btn btn-success" id="reset_pass" title="Change Password" value="Change Password">
		
      </div>
	</form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
 <script>
 function check_old_password(pass)
 {
 	
	$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/check_password';?>",
				dataType: 'json',
				async: false,
				data: {'pass':pass},
				success: function(data){
		
				if(data == '0')
				{
					$('#oldpassword').val('');
					$('#oldpassword').focus();	
					//alert('This Email Already Existing ! Please Select other Email');
					$.confirm({'title': 'Alert','message': " <strong> Old password doesn't match! Please enter correct password "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok','action': function(){
										$('#oldpassword').val('');
										$('#oldpassword').focus();		
									}}}});
				}
				}
			});
			return false;
					

 	
 }
 </script>