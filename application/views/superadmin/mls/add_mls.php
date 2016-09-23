<?php 
    /*
        @Description: superadmin contact list
        @Author: Niral Patel
        @Date: 12-03-2015
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
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
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
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('property_import_head');?></h3>
	   <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>       

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
					<div class="col-sm-4 form-group"><h5>&nbsp;</h5></div>
					
        		</div>
        </div>
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url').$viewname.'/insert_mls';?>" data-validate="parsley" >
			<div id="data_field">
			<?php
			//pr($mls_fields);exit;
         	if(!empty($mls_fields))
         	{
         		foreach($mls_fields as $fileds)
         		{
         			?>
         				<div class="col-sm-12">
		            	<div class="row">
							
							
							<div class="col-sm-4 form-group"><b><?=$fileds['Field']?></b> <?=!empty($fileds['Comment'])?'('.$fileds['Comment'].')':''?></div>
							<div class="col-sm-4 form-group">
								<select class="form-control parsley-validated" name="slt_<?=$fileds['Field']?>" id="slt_<?=$fileds['Field']?>">
								   <option value="">Please Select Field</option>
								   	<?php foreach($dropdown_data as $row){
								    ?>
								   <option value="<?php echo $row['id'];?>"><?php echo $row['field'];?></option>
								   <?php } ?>
									
						  		</select>
							</div>
							<div class="col-sm-4 form-group"></div>
		        		</div>
		         	</div>
         			<?
         			$last_data=$fileds['Field'];
         		}

         	}
         	?>

			</div>
			<div class="col-sm-12">
            	<div class="row">
					
					
					
					<div class="col-sm-4 form-group">
					<input type="hidden" class="form-control parsley-validated" value="<?=$last_data?>"  id="last_field" name="submitbtn1" />
					<input type="button" class="btn btn-secondary-green" value="Add New Field"  title="Add New Field" id="add_new" name="submitbtn" />
					</div>
					<div class="col-sm-4 form-group">&nbsp;</div>
					<div class="col-sm-4 form-group"></div>
        		</div>
        		<div class="row" id="add_new_data">
					
					
					
					<div class="col-sm-3 form-group">
					<input placeholder="Field Name" type="text" name="field_name" class="form-control parsley-validated" data-required="true" data-type="alphanum" id="field_name" />
					</div>
					<div class="col-sm-3 form-group">
					<select class="form-control parsley-validated" data-required="true" name="field_type" id="field_type">
				   		<option value="">Select Data Type</option>
				   		<option value="int">Int</option>
				   		<option value="varchar">Varchar</option>
				   		<option value="float">Float</option>
				   		<option value="datetime">Datetime</option>
				   		<option value="text">Text</option>
				   		<option value="char">Char</option>
				   	</select>
				   	</div>
				   	<div class="col-sm-3 form-group">
		  			<input placeholder="Field Size" maxlength="4" type="text" name="field_size" class="form-control parsley-validated" data-required="true" onkeypress="return isNumberKey(event)" value="" id="field_size" />
					</div>
					<div class="col-sm-2 form-group">
						<input type="submit" class="btn btn-secondary-green" value="Save"  title="Save" id="savefield" name="submitbtn" />
						<input type="button" class="btn btn-secondary-green" value="Cancel"  title="Cancel" id="cancelfield" name="submitbtn" />
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
					<input type="submit" class="btn btn-secondary-green" value="Import"  title="Import Contacts" id="save" name="submitbtn" />
				<a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel">Cancel</a>
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
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/get_filed_list';?>",
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
							$("#"+value['mls_master_field']+" option:contains(" + value['csv_field'] + ")").prop('selected', 'selected'); 
							//console.log("#"+value['mls_master_field']+" : "+value['csv_field']);
						}
						catch(e){}
					});
					
					return false;
				}
			}
		});
	 
	   /*shareproj = $.ajax({
						url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/get_filed_list';?>",
						type: "POST",
						data: {'mapping_id':mapping_id},
						datatype:"json"
						
					});
					shareproj.done(function( msg ) {
					
						alert(msg.field_list);
					
						$.each( msg.field_list, function( key, value ) {
							alert( key + ": " + value );
						});
					
							//alert(msg[0].mls_master_field);
					});*/
					
    }
</script>
<script>
    $(document).ready(function(){
    	$('#field_name').val('').attr('disabled','disabled');
	 	$('#field_type').val('').attr('disabled','disabled');
	 	$('#field_size').val('').attr('disabled','disabled');
	 $("#div_msg").fadeOut(4000); 
	 $('#add_new_data').hide();
	 $('#add_new').click(function(){
	 	$('#field_name').val('').removeAttr('disabled');
	 	$('#field_type').val('').removeAttr('disabled');
	 	$('#field_size').val('').removeAttr('disabled');
	 	$('#add_new_data').show();	
	 	$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
	 	
	 });
	 $('#cancelfield').click(function(){
	 	$('#field_name').val('').attr('disabled','disabled');
	 	$('#field_type').val('').attr('disabled','disabled');
	 	$('#field_size').val('').attr('disabled','disabled');
	 	$('#add_new_data').hide();	
	 	$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();

	 });
	 $('#save').click(function(){
	 	$('#field_name').val('').attr('disabled','disabled');
	 	$('#field_type').val('').attr('disabled','disabled');
	 	$('#field_size').val('').attr('disabled','disabled');
	 	$("#<?php echo $viewname;?>").parsley().destroy();
		$("#<?php echo $viewname;?>").parsley();
	 	//$('#add_new_data').hide();	

	 });
	 
	 $('#savefield').click(function(){
	 	if ($("#<?php echo $viewname;?>").parsley().isValid()) {
	 	$('#common_div').block({ message: 'Loading...' }); 
	 	var field_name=$('#field_name').val();
	 	var field_type=$('#field_type').val();
	 	var field_size=$('#field_size').val();
	 	var last_field=$('#last_field').val();	



	 	$.ajax({
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/add_new_field';?>",
			type: "POST",
			data: {'field_name':field_name,'field_type':field_type,'field_size':field_size,'last_field':last_field},
			datatype:"json",
			/*beforeSend: function() {
				$('#common_div').block({ message: 'Loading...' }); 
			},*/
			success: function(data){
				//alert(data);
				if(data == 'error')
				{
					$.confirm({'title': 'Alert','message': " <strong> Field already exist."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#field_name').val('');
		 	        $('#field_type').val('');
		 	        $('#field_size').val('');
					$('#common_div').unblock();
					return false;
				}
				else
				{
				 	var inlinehtml='';
				 	<?php /*html='<div class="col-sm-12"><div class="row"><div class="col-sm-4 form-group"><?=$fileds['Field']?></div><div class="col-sm-4 form-group"><select class="form-control parsley-validated" name="slt_<?=$fileds['Field']?>" id="slt_<?=$fileds['Field']?>"><option value="">Please Select Field</option>'<?php foreach($dropdown_data as $row){ ?>
				 		'<option value="<?php echo $row['id'];?>">'<?php echo $row['field'];?>'</option>'<?php }?>'</select></div><div class="col-sm-4 form-group"></div></div></div>';
			         	alert(html); */ ?>
			        inlinehtml += '<div class="col-sm-12">';
					inlinehtml += '<div class="row">';
					inlinehtml += '<div class="col-sm-4 form-group"><b>'+field_name+'</b></div>';
					inlinehtml += '<div class="col-sm-4 form-group">';
		            inlinehtml += '<select class="form-control parsley-validated" name="slt_'+field_name+'" id="slt_'+field_name+'">';
		            inlinehtml += ' <option value="">Please Select Field</option>';
		            <?php foreach($dropdown_data as $row){ ?>
					// inlinehtml += '<?php foreach($dropdown_data as $row){ ?>';
					inlinehtml += '  <option value="<?php echo $row["id"];?>"><?php echo $row["field"];?></option>';
		            //inlinehtml += ' <?php }?>';
		            <?php } ?>
					inlinehtml += '</select>';
					inlinehtml += '</div>';
					inlinehtml += '<div class="col-sm-4 form-group"></div>';
					inlinehtml += '</div>';
					inlinehtml += '</div>'; 	

					$('#data_field').append(inlinehtml);
					$('#common_div').unblock();
					$('#last_field').val(field_name);
					$('#field_name').val('');
		 	        $('#field_type').val('');
		 	        $('#field_size').val('');
		 	        return false;
		 	    }
			}
			
		});
		
		return false;
		}
	 });
		return false;
    });
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;

		return true;
	}

</script>
