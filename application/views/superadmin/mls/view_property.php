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
$mls_id=$this->uri->segment(4);
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
       <h3> <i class="fa fa-table"></i>Property Details</h3>
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
			
			<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url').$viewname.'/insert_mls';?>" data-validate="parsley" >
				<!-- Property Master -->
				<div id="property_data_field">
					<input type="hidden" name="id" value="<?=!empty($mls_id)?$mls_id:''?>">
					<div class="row">
							<div class="col-sm-8 form-group">
								<label><h2>Property Details</h2></label>
							</div>
							<div class="col-sm-4 form-group">&nbsp;</div>
							<div class="col-sm-4 form-group"></div>
			        	</div>
					<?php
					//pr($mls_property_fields);exit;
		         	if(!empty($mls_property_fields))
		         	{
		         		//$k=0;

		         		foreach($mls_property_fields as $fileds)
		         		{
		         			//pr($fileds);
		         			?>
		         				<div class="col-sm-6">
					            	<div class="row">
										<div class="col-sm-6 form-group"><b><?=$fileds['master_field_name']?></b> <?php if(!empty($fileds['field_comment'])){ echo "(".$fileds['field_comment'].")";}?> </div>
										<div class="col-sm-3 form-group">
											<?=!empty($datalist[0][$fileds['master_field_name']])?$datalist[0][$fileds['master_field_name']]:'-';?>
										</div>
																										
					        		</div>
				         		</div>
		         			<?
		         			//$last_data=$fileds['master_field_name'];
		         			//$k++;
		         		}

		         	}
		         	?>
		         	<div class="col-sm-12">
		            	<div class="row">
							<div class="col-sm-3 form-group"><b>Display Price</b> </div>
							<div class="col-sm-3 form-group">
								<?=!empty($datalist[0]['display_price'])?$datalist[0]['display_price']:'-';?>
							</div>
																							
		        		</div>
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
				}
				else
				{
					$('#contacts select option').removeAttr("selected"); 
					
					$.each( data, function( key, value ) {
						alert(value['csv_field']);
						try{
							$("#"+value['mls_master_field']+" optgroup option:contains(" + value['csv_field'] + ")").prop('selected', 'selected'); 
							//console.log("#"+value['mls_master_field']+" : "+value['csv_field']);
						}
						catch(e){}
					});
					
					return false;
				}
			}
		});
	 }
</script>
<script>
$(document).ready(function(){
	$('.field_name').val('').attr('disabled','disabled');
 	$('.field_type').val('').attr('disabled','disabled');
 	$('.field_size').val('').attr('disabled','disabled');
 	$('.field_comment').val('').attr('disabled','disabled');
 $("#div_msg").fadeOut(4000); 
 $('.add_new_data').hide();


	//return false;
});
 function add_fields(field_type)
 {
 	$('#'+field_type+'_field_name').val('').removeAttr('disabled');
 	$('#'+field_type+'_field_type').val('').removeAttr('disabled');
 	$('#'+field_type+'_field_size').val('').removeAttr('disabled');
 	$('#'+field_type+'_field_comment').val('').removeAttr('disabled');

 	$('#'+field_type+'_add_new_data').show();	
 	$("#<?php echo $viewname;?>").parsley().destroy();
	$("#<?php echo $viewname;?>").parsley();
 }
 function cancle_fields(field_type)
 {
 	$('#'+field_type+'_field_name').val('').attr('disabled','disabled');
 	$('#'+field_type+'_field_type').val('').attr('disabled','disabled');
 	$('#'+field_type+'_field_size').val('').attr('disabled','disabled');
 	$('#'+field_type+'_field_comment').val('').attr('disabled','disabled');
 	$('#'+field_type+'_add_new_data').hide();	
 	$("#<?php echo $viewname;?>").parsley().destroy();
	$("#<?php echo $viewname;?>").parsley();
 }
 function save_fields(field_type)
 {
 	$('.field_name').attr('disabled','disabled');
 	$('.field_type').attr('disabled','disabled');
 	$('.field_size').attr('disabled','disabled');
 	$('.field_comment').attr('disabled','disabled');
 	$('.add_new_data').hide();		
 	$('#'+field_type+'_field_name').removeAttr('disabled');
 	$('#'+field_type+'_field_type').removeAttr('disabled');
 	$('#'+field_type+'_field_size').removeAttr('disabled');
 	$('#'+field_type+'_field_comment').removeAttr('disabled');
 	$('#'+field_type+'_add_new_data').show();
 	$("#<?php echo $viewname;?>").parsley().destroy();
	$("#<?php echo $viewname;?>").parsley();
 	//$('#'+field_type+'_field_name').focus();
	if ($("#<?php echo $viewname;?>").parsley().isValid()) {
		//$('#common_div').block({ message: 'Loading...' }); 
	 	var field_name=$('#'+field_type+'_field_name').val();
	 	var field_type1=$('#'+field_type+'_field_type').val();
	 	var field_size=$('#'+field_type+'_field_size').val();
	 	var field_comment=$('#'+field_type+'_field_comment').val();
	 	//alert(field_comment);
	 	//return false;
	 	var last_field=$('#'+field_type+'_last_field').val();	



	 	$.ajax({
			url: "<?php echo $this->config->item('superadmin_base_url').$viewname.'/add_new_field';?>",
			type: "POST",
			data: {'tblname':field_type,'field_name':field_name,'field_type':field_type1,'field_size':field_size,'last_field':last_field,'field_comment':field_comment},
			datatype:"json",
			/*beforeSend: function() {
				$('#common_div').block({ message: 'Loading...' }); 
			},*/
			success: function(data){
				//alert(data);
				if(data == 'error')
				{
					$.confirm({'title': 'Alert','message': " <strong> Field already exist."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					$('#'+field_type+'_field_name').val('');
		 	        $('#'+field_type+'_field_type').val('');
		 	        $('#'+field_type+'_field_size').val('');
		 	        $('#'+field_type+'_field_comment').val('');
					$('#common_div').unblock();
					return false;
				}
				else
				{
				 	var inlinehtml='';
				 	<?php /*html='<div class="col-sm-12"><div class="row"><div class="col-sm-4 form-group"><?=$fileds['Field']?></div><div class="col-sm-4 form-group"><select class="form-control parsley-validated" name="slt_<?=$fileds['Field']?>" id="slt_<?=$fileds['Field']?>"><option value="">Please Select Field</option>'<?php foreach($dropdown_data as $row){ ?>
				 		'<option value="<?php echo $row['id'];?>">'<?php echo $row['field'];?>'</option>'<?php }?>'</select></div><div class="col-sm-4 form-group"></div></div></div>';
			         	alert(html); */ ?>
		         	fieldcomment='';
		         	if(field_comment != '')
		         	{var fieldcomment='('+field_comment+')';}	
			        	
			        inlinehtml += '<div class="col-sm-12">';
					inlinehtml += '<div class="row">';
					inlinehtml += '<div class="col-sm-4 form-group"><b>'+field_name+'</b> '+fieldcomment+'</div>';
					inlinehtml += '<div class="col-sm-4 form-group">';
		            inlinehtml += '<select class="form-control parsley-validated" name="slt_'+field_name+'" id="slt_'+field_name+'">';
		            inlinehtml += ' <option value="">Please Select Field</option>';
		            <?php foreach($field_data as $tbl=>$value){
				    ?>
				    inlinehtml += '<optgroup label="<?=$tbl?>">';
				    	
				   	<?php for($i=0;$i<count($field_data[$tbl][0]);$i++){ ?>
					   inlinehtml += '<option value="<?=$tbl?>.<?php echo $field_data[$tbl][0][$i];?>"><?php echo $field_data[$tbl][0][$i];?></option>'
					 <?php } ?>
					 inlinehtml += '</optgroup>';
					 <?php } ?>			
					inlinehtml += '</select>';
					inlinehtml += '</div>';
					
					inlinehtml += '<div class="col-sm-4 form-group">';
		            inlinehtml += '<select class="form-control parsley-validated" name="tbl_'+field_name+'" id="tbl_'+field_name+'">';
		            inlinehtml += ' <option value="">Please Select Field</option>';
		            <?php 
		            if(!empty($load_tables))
		            {
		            foreach($load_tables as $row){
				    ?>
				       //inlinehtml += '<option value="<?=$tbl?>.<?php echo $row["Tables_in_asheville"];?>"><?php echo $field_data[$tbl][0][$i];?></option>'
				       inlinehtml +='<option value="<?=!empty($row["Tables_in_asheville"])?$row["Tables_in_asheville"]:''?>"><?=!empty($row["Tables_in_asheville"])?$row["Tables_in_asheville"]:''?></option>';
					<?php } } ?>			
					inlinehtml += '</select>';
					inlinehtml += '</div>';

					inlinehtml += '</div>';
					inlinehtml += '</div>'; 	

					$('#'+field_type+'_data_field').append(inlinehtml);
					$('#common_div').unblock();
					$('#'+field_type+'_last_field').val(field_name);
					$('#'+field_type+'_field_name').val('');
		 	        $('#'+field_type+'_field_type').val('');
		 	        $('#'+field_type+'_field_size').val('');
		 	        $('#'+field_type+'_field_comment').val('');
		 	        return false;
		 	    }
			}
			
		});
		
		return false;
	}
 }
 
function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}
/*
Get transection table fields
*/
function get_transection_fields(table,field_id)
{
	if(table != '')
	{
		$('#fld'+field_id).html('');
		var data1=$('select #'+table).html();
		$('#fld_'+field_id).html(data1);
		$('#fld_'+field_id).append("<option value=''>Please Select Table Fields</option>");
	}	
	else
	{
		$('#fld_'+field_id).html('');
		$('#fld_'+field_id).append("<option value=''>Please Select Table Fields</option>");
	}
}
function setdefaultdata()
{
	 if ($('#<?php echo $viewname?>').parsley().isValid()) {
    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
    
}
}
/*$("select.form-control").multiselect({
		 multiple: false,
		 selectedList: 1
	}).multiselectfilter();*/
</script>
