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
   <div class="">
    <div class="col-md-12">
		
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('contact_import_head');?></h3>
	    <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" ><?php echo $this->lang->line('common_back_title')?></a> </span>      
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div id="common_div">
         	<div class="col-sm-12 pull-left text-center">
            	<div class="">
					
					<div class="col-sm-12 form-group"><?php echo $count_no_contact."  Contact imported successfully."; ?><?=!empty($msg)?' '.$msg:'';?></div>
					
        		</div>
         	</div>
			<div class="col-sm-12 pull-left text-center">
            	<div class="row">
					
					<a title="Undo Last Import" class="btn btn-secondary" href="<?=base_url('admin/'.$viewname.'/delete_last_import')."/".$csv_id;?>">Undo Last Import</a>
					<a title="Import More Contacts" class="btn btn-secondary" href="<?=base_url('admin/'.$viewname.'/import');?>">Import More Contacts</a> 
					<a title="Finish" class="btn btn-secondary" href="<?=base_url('admin/'.$viewname);?>">Finish</a>
        		</div>
         	</div>
			
			
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
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->