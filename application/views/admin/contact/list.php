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
   <h1>Contact</h1>
  </div>
  <div id="content-container">
   <div class="row">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i></h3>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
          <div class="col-sm-12">
           <ul class="contact_add">
            
            <li><a class="btn btn-xs btn-secondary pull-right" href="javascript:void(0)"><i class="fa fa-level-down"></i> &nbsp;Import Contact</a></li>
           
            <li><a class="btn btn-xs btn-secondary pull-right" href="javascript:void(0)"><i class="fa fa-sign-out"></i> &nbsp;Export Contacts</a></li>
            
            <li><a class="btn btn-xs btn-secondary pull-right" href="javascript:void(0)"><i class="fa fa-exchange"></i> &nbsp;Merge Duplicate Contacts</a></li>
           </ul>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
           <div id="DataTables_Table_0_length" class="dataTables_length">
            <label>
             <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
             <option value="">Select</option>
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
             </select>
            </label>
           </div>
          </div>
          <div class="col-sm-6">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search...">
            </label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
           <button class="btn btn-danger howler" data-type="danger">Delete Contact</button>
          </div>
          <div class="col-sm-6">
          <a class="btn  pull-right btn-success howler" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add new Contact</a>
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
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
