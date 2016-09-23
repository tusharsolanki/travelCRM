<?php 
    /*
        @Description: superadmin Import Add
        @Author: Niral patel
        @Date: 12-03-15
    */

?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
		 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('superadmin_base_url').$viewname.'/insert_mls_csv';?>" data-validate="parsley" >
		 <div class="col-sm-12">
            <div class="row">
			<!-- <div class="col-sm-6 form-group clear">
				<label for="text-input"><a href="<?=$this->config->item('download_csv_path')?>" class="textdecoration import_only">Click here</a> to Download Sample CSV File for Contacts. </label>
			</div> -->
         	<div class="col-sm-6 form-group clear">
			  <label for="text-input"><?=$this->lang->line('contact_select_file');?></label>
               <div class="browse"> <span class="text"> </span>
						  <div class="browse_btn">
							<div class="file_input_div">
			   				<input type="button" value="Attach" class="file_input_button" />
								   
									<input type="file" alt="1" name="doc_file" id="doc_file" class="file_input_hidden" />
									<input type="hidden" name="hiddenFiledoc" id="hiddenFiledoc" value="" />
							 </div>
							 <span id="priview_doc"></span>
						  </div>
								
				</div>
            </div>
			<div class="col-sm-6 form-group clear">
				<label for="text-input"><?=$this->lang->line('contact_select_file_msg');?></label>
			</div>
			<div class="col-sm-6 form-group clear">
				<input type="submit" class="btn btn-secondary-green" value="Upload"  id="save" name="submitbtn" onclick="return checkfile();" />
				<a class="btn btn-primary" href="javascript:history.go(-1);">Cancel</a>
			</div>
			</div>
		 </div> 
		 </form>
         </div>
        </div>
       </div>
       <!-- /.table-responsive --> 
       
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
	$(function(){
		var btnUpload1=$('#doc_file');
		new AjaxUpload(btnUpload1, {
			type: 'post',
			data:{},
			action: '<?=$this->config->item('superadmin_base_url').$viewname."/upload_csv";?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(csv)$/.test(ext))){ 
				 	$.confirm({'title': 'Alert','message': " <strong> You can upload only CSV. File "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        			//alert('You can upload only csv. File');
					return false;
				}
				$('#priview_doc').html('<img src="<?=$this->config->item('image_path').'ajax-loader.gif'?>" />');
			},
			onComplete: function(file, response){
				//alert(response);return false;
			var data = jQuery.parseJSON(response);
				
				var result=data.document_name.split('-');
				if(result.length > 1)
				{
					var arrayindex = jQuery.inArray( result[0] , result );
					
					if(arrayindex >= 0)
					{
						result.splice( arrayindex, 1 );
					}
				}
				$('#priview_doc').text(result.join('-'));	
				$('#hiddenFiledoc').val(data.document_name);
			}
		});
	});
	function checkfile()
	{	
		var btnUpload1=$('#hiddenFiledoc').val();
		if(btnUpload1 == '')
		{
			$.confirm({'title': 'Alert','message': " <strong> Please select file "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			//alert('Please Select File');
			return false;
		}
	}
</script>