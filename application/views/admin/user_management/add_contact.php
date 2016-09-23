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
?>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('contact_header');?></h1>
  </div>
  <div id="content-container">
   <div class="row">
    <div class="col-md-12">
	<div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('contact_import_head');?></h3>
	   <span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div id="common_div">
			
			
			<?php if(!empty($msg)){?>
			<div class="col-sm-12 text-center" id="div_msg">
			<?php echo '<label class="error">'.urldecode ($msg).'</label>';
				$newdata = array('msg'  => '');
				$this->session->set_userdata('message_session', $newdata);?> 
			</div>
			<?php } ?>
         	
			<div class="col-sm-12">
            	<div class="row">
					<div class="col-sm-4 form-group"></div>
					<div class="col-sm-4 form-group"></div>
					<div class="col-sm-4 form-group"> <select class="form-control parsley-validated" name="mapping_id" id="mapping_id" onchange="get_map_field(this.value)">
				   <option value="">Select Saved Mapping</option>
				   <?php foreach($contact_mapping_list as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
				   <?php }?>
						
			  </select></div>
        		</div>
			<div class="col-sm-12">
            	<div class="row">
					<div class="col-sm-4 form-group"><h5><b>CRM Field</b></h5></div>
					<div class="col-sm-4 form-group"><h5><b>CSV Import Field</b></h5></div>
					<div class="col-sm-4 form-group"><h5><b>Extra Field Type</b></h5></div>
        		</div>
        </div>
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').$viewname.'/insert_contact';?>" >
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('contact_add_prefix');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_prefix" id="slt_prefix">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('contact_add_fname');?><span class="redcolor">*</span></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_fname" id="slt_fname"  data-required="true">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('contact_add_mname');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_mname" id="slt_mname">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('contact_add_lname');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_lname" id="slt_lname">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('contact_add_company');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_company" id="slt_company">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_address1');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_address1" id="slt_address1">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_address_type" id="slt_address_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_address2');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_address2" id="slt_address2">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_city');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_city" id="slt_city">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_state');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_state" id="slt_state">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_contact_source');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_contact_source" id="slt_contact_source">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_contact_type');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_contact_type" id="slt_contact_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_contact_lead');?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_contact_lead" id="slt_contact_lead">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_contact_default_email');?><span class="redcolor">*</span></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_default_email" id="slt_default_email"  data-required="true">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email_type" id="slt_email_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_email').'2';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email2" id="slt_email2">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email_type_2" id="slt_email_type_2">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_email').'3';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email3" id="slt_email3">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email_type_3" id="slt_email_type_3">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_email').'4';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email4" id="slt_email4">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email_type_4" id="slt_email_type_4">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_email').'5';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email5" id="slt_email5">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_email_type_5" id="slt_email_type_5">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
			  	   </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_contact_default_phone');?><span class="redcolor">*</span></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_default_phone" id="slt_default_phone"  data-required="true">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_phone_type" id="slt_phone_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_phone').'2';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_phone2" id="slt_phone2">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_phone2_type" id="slt_phone2_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group"><?=$this->lang->line('common_label_phone').'3';?></div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_phone3" id="slt_phone3">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					</div>
					<div class="col-sm-4 form-group">
					<select class="form-control parsley-validated" name="slt_phone3_type" id="slt_phone3_type">
				   <option value="">Please Select Field</option>
				   <?php foreach($dropdown_data as $row){ ?>
				   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
				   <?php }?>
						
			  </select>
					
					</div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					<div class="col-sm-4 form-group">Save this Mapping as</div>
					<div class="col-sm-4 form-group">
					<input type="text" name="save_mapping" class="form-control parsley-validated" id="save_mapping" />
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			<div class="col-sm-12">
            	<div class="row">
					

					<div class="col-sm-4 form-group"></div>
					<div class="col-sm-4 form-group">
					<input type="hidden" name="csv_id" id="csv_id" value="<?php echo $csv_id;?>" />
					<input type="submit" class="btn btn-secondary" value="Import Contacts"  id="save" name="submitbtn" />
				<a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
					</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
         	</div>
			</form>
			
       </div>
       <!-- /.table-responsive --> 
      </div>
      <!-- /.portlet-content --> 
      
     </div>
    </div>
   </div>
  </div>
   </div>
  </div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
 </div>
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
  function get_map_field(data)
    {
	 var mapping_id = $("#mapping_id").val();
	 
	 $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/get_filed_list';?>",
			dataType: 'json',
			async: false,
			data: {'mapping_id':mapping_id},
			success: function(data){
				
				if(data == '')
				{
					$('#contacts select option').removeAttr("selected"); 
					//$("select :selected").prop('selected', false);
					//$("select").val($("select option:first").val());
					//$("select option:first").attr('selected','selected');
					//$("select option:first-child").attr('selected','selected');
					//$("select option:contains('Please Select Field')").attr('selected','selected');
					//$('.parsley-form select').val('');
					//$('.parsley-form select').find('option:first-child').attr('selected', 'selected');
				}
				else
				{
					//$('#<?php // echo $viewname;?> select option').removeAttr("selected");
					
					$('#contacts select option').removeAttr("selected"); 
					
					$.each( data, function( key, value ) {
						try{
							$("#"+value['contact_master_field']+" option:contains(" + value['csv_field'] + ")").prop('selected', 'selected'); 
							//console.log("#"+value['contact_master_field']+" : "+value['csv_field']);
						}
						catch(e){}
					});
					
					return false;
				}
			}
		});
	 
	   /*shareproj = $.ajax({
						url: "<?php echo $this->config->item('admin_base_url').$viewname.'/get_filed_list';?>",
						type: "POST",
						data: {'mapping_id':mapping_id},
						datatype:"json"
						
					});
					shareproj.done(function( msg ) {
					
						alert(msg.field_list);
					
						$.each( msg.field_list, function( key, value ) {
							alert( key + ": " + value );
						});
					
							//alert(msg[0].contact_master_field);
					});*/
					
    }
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
</script>