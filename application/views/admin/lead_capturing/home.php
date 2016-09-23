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
    <h1><?=$this->lang->line('sms_campaign_blast');?></h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>Form Builder</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                   <?php if(!empty($this->modules_unique_name) && in_array('form_builder_add',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/lead_capturing/add_record');?>"><img title="Build a New Form" alt="Build a New Form" src="<?=base_url()?>images/build-new-form-icon.jpg" class="img-responsive"><span>Build a New Form</span></a></div>
                  </div>
                  <? } ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/lead_capturing');?>"><img title="View Existing Forms" alt="View Existing Forms" src="<?=base_url()?>images/view-existing-forms-icon.jpg" class="img-responsive"><span>View Existing Forms</span></a></div>
                  </div>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/lead_capturing/form_lead_list');?>"><img title="View Submitted Forms" alt="View Submitted Forms" src="<?=base_url()?>images/view-existing-forms-icon.jpg" class="img-responsive"><span>View Submitted Forms</span></a></div>
                  </div>
                </div>
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
