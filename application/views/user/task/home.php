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
    <h1>Task</h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>Task</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                 <?php if(!empty($this->modules_unique_name) && in_array('tasks_add',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/task/add_record');?>"><img title="New Task" alt="New Task" src="<?=base_url()?>images/new-task-icon.jpg" class="img-responsive"><span>New Task</span></a></div>
                  </div>
                   <? } ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/task');?>"><img title="View Pending Tasks" alt="View Pending Tasks" src="<?=base_url()?>images/pending-task-icon.jpg" class="img-responsive"><span>View Pending Tasks</span></a></div>
                  </div>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/task/completed_task');?>"><img title="View Completed Tasks" alt="View Completed Tasks" src="<?=base_url()?>images/view-completed-tasks-icon.jpg" class="img-responsive"><span>View Completed Tasks</span></a></div>
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
<script>
function plan_pause_play_stop(str,plan_status)
{
	$.confirm({
			'title': 'Confirm Message','message': " <strong> Are you sure want to "+str+" all communication?",'buttons': {'Yes': {'class': '',
			'action': function(){
				$('#plan_status').val(plan_status);
				$('#pause_play_stop').submit();
				return true;
				}},'No'	: {'class'	: 'special'}}});
	return false;
}
</script>