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
    <h1>Communications</h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>Communications</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                   <?php if(!empty($this->modules_unique_name) && in_array('communications_add',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/interaction_plans/add_record');?>"><img title="Add Communication Plan" alt="Add Communication Plan" src="<?=base_url()?>images/add-communication-plan-icon.jpg" class="img-responsive"><span>Add Communication Plan</span></a></div>
                  </div>
                  <? } ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/interaction_plans');?>"><img title="View Existing Plans" alt="View Existing Plans" src="<?=base_url()?>images/view-existing-plans.jpg" class="img-responsive"><span>View Existing Plans</span></a></div>
                  </div>
                  <?php if(!empty($this->modules_unique_name) && in_array('communications_delete',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/interaction_plans/view_archive');?>"><img title="View Archived Plans" alt="View Archived Plans" src="<?=base_url()?>images/view-archived-plans.jpg" class="img-responsive"><span>View Archived Plans</span></a></div>
                  </div>
                  <? } ?>
                </div>
                <form class="form parsley-form" enctype="multipart/form-data" name="pause_play_stop" id="pause_play_stop" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?>interaction_plans/all_pause_play_stop">
                <div class="row">
                <?php if(!empty($this->modules_unique_name) && in_array('play_push_stop',$this->modules_unique_name)){?>
					<div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><button class="btn btn-secondary padding_left_right" title="Play All" name="plan_play" id="plan_play" onclick="return plan_pause_play_stop('Play','1');"><img title="Play All" alt="Play All" src="<?=base_url()?>images/play-all-icon.jpg" class="img-responsive"></button><span>Play All</span></a></div>
                  </div>
                  	<div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><button class="btn btn-warning padding_left_right" title="Pause All" name="plan_pause" id="plan_pause" onclick="return plan_pause_play_stop('Pause','2');"><img title="Pause All" alt="Pause All" src="<?=base_url()?>images/pause-all-icon.jpg" class="img-responsive"></button><span>Pause All</span></a></div>
                  </div>
                  	<div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><button class="btn btn-primary padding_left_right" title="Reset All" name="plan_stop" id="plan_stop" onclick="return plan_pause_play_stop('Stop','3');"><img title="Stop All" alt="Stop All" src="<?=base_url()?>images/stop-all-icon.JPG" class="img-responsive"></button><span>Reset All</span></a></div>
                    </div>
                   <? } ?>
                  </div>
                  <input type="hidden" name="plan_status" id="plan_status" value="" />
                 </form>
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
	if(plan_status == 3)
		var message = '<strong>Warning!</strong> Are you sure you want to reset all of your communications?<br>This function will stop all communication plans, and when restarted, all plans will start from the beginning!';
  else if(plan_status == 2)
    var message = '<strong>Warning!</strong> Are you sure you want to pause all of your communications?<br> This function will pause all communication plans. Schedule will be adjusted by number of paused days!';
	else
		var message = 'Are you sure want to '+str.toLowerCase()+' all communication?';

	$.confirm({
			'title': 'Confirm Message','message': message,'buttons': {'Yes': {'class': '',
			'action': function(){
				$('#plan_status').val(plan_status);
				$('#pause_play_stop').submit();
				return true;
				}},'No'	: {'class'	: 'special'}}});
	return false;
}
</script>