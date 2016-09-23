<?php 
    /*
        @Description: Admin Import Add
        @Author: Kaushik Valiya
        @Date: 11-07-14
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
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
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
       <h3> <i class="fa fa-table"></i>Import Property</h3>
	   <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>       

      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div id="common_div">
		 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').$viewname.'/insert_contact_csv';?>" data-validate="parsley" >
		 <div class="col-sm-12">
            <div class="row">
			<div class="col-sm-6 form-group clear">
				<label for="text-input"><a href="<?=$this->config->item('download_property_csv_sample')?>" class="textdecoration import_only">Click here</a> to Download Sample CSV File for Property. </label>
			</div>
         	<div class="col-sm-6 form-group clear">
			  <label for="text-input"><?=$this->lang->line('contact_select_file');?></label>
               <div class="browse"> <span class="text"> </span>
						  <div class="browse_btn">
							<div class="file_input_div">
			   				<input type="button" value="Attach" class="file_input_button" />
								   
                            <input type="file" alt="1" name="csvfile" id="csvfile" class="file_input_hidden" />
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