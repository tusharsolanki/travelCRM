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
$formAction = 'merge_search_contacts'; 
$path = $viewname.'/'.$formAction;
?>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('contact_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
   	<div class="col-md-12">
    	
	 <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('contact_merge_list_head');?></h3>
	     <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>  
	   
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
	  <div class="row">
       <div class="col-sm-12">
	   		
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate >
			   <div class="col-sm-12">
				<div class="row">
				 <div class="col-sm-12 col-lg-4 form-group appendfielddata">
				  <label for="text-input"><?=$this->lang->line('contact_merge_list_title');?></label>
				  <div class="row">
					  <div class="col-sm-10 form-group">
						  <select class="form-control parsley-validated" name="slt_fields[]" id="slt_prefix" data-required="true">
						   <option value="">Please Select</option>
						   <option value="first_name"><?=$this->lang->line('contact_add_fname');?></option>
						   <option value="middle_name"><?=$this->lang->line('contact_add_mname');?></option>
						   <option value="last_name"><?=$this->lang->line('contact_add_lname');?></option>
						   <option value="email_address"><?=$this->lang->line('contact_add_email_address');?></option>
						   <option value="phone_no"><?=$this->lang->line('contact_add_phone_no');?></option>
						  </select>
					  </div>
					  <div class="col-sm-2 form-group padding-top-5">
						<a title="Add More" href="javascript:void(0);" class="btn btn-xs btn-success addnewfielddropdown"><i class="fa fa-plus"></i></a>
					  </div>
				  </div>
				 </div>
				</div>
			   </div>
			   
			   <div class="col-sm-12 pull-left text-center">
				  <input type="submit" class="btn btn-secondary-green" title="Find Duplicates" value="Find Duplicates" />
				  <a title="Cancel" class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
				</div>
			   
			</form>
	   </div>
	  </div>
       <!-- /.table-responsive --> 
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
 <!-- #content --> 
<script type="text/javascript">

	var counter = 1;
	$('body').on('click','.addnewfielddropdown',function(e){
		
		var inlinehtml = '';
		
		inlinehtml += '<div class="remove_field_div clear">';
			inlinehtml += '<div class="row">';
					  inlinehtml += '<div class="col-sm-10 form-group">';
						  inlinehtml += '<select class="form-control parsley-validated" name="slt_fields[]" id="slt_prefix'+counter+'" data-required="true">';
						  inlinehtml += '</select>';
					  inlinehtml += '</div>';
					  inlinehtml += '<div class="col-sm-2 form-group padding-top-5">';
						inlinehtml += '<a href="javascript:void(0);" class="btn btn-xs btn-primary removenewfielddropdown"><i class="fa fa-times"></i></a>';
					  inlinehtml += '</div>';
				  inlinehtml += '</div>';
		inlinehtml += '</div>';
		
		$('.appendfielddata').append(inlinehtml);
		
		$('#slt_prefix option').clone().appendTo('#slt_prefix'+counter);
		
		counter++;
		
	});
	
	$('body').on('click','.removenewfielddropdown',function(e){
		
		var removediv = $(this).closest('.remove_field_div').remove();
		
		return false;
		
	});
	
</script>