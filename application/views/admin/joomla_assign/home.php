<?php 
/*
    @Description: Home page for assign communication
    @Author     : Sanjay Moghariya
    @Date       : 23-12-2014
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
        <h1><?=$this->lang->line('leads_dashboard_assigncomm_plan');?></h1>
    </div>
    <div id="content-container">
        <div class="">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-table"></i><?=$this->lang->line('leads_dashboard_assigncomm_plan');?></h3>
                        <span class="pull-right"><a title="Back" class="btn btn-secondary" href="<?php echo $this->config->item('admin_base_url')?>leads_dashboard"><?php echo $this->lang->line('common_back_title')?></a> </span>
                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                            <div class="row dt-rt">
                            <?php if(!empty($this->modules_unique_name) && in_array('auto_communication_add',$this->modules_unique_name)){?>
                                <div class="col-lg-3 col-md-3 text-center">
                                    <div class="iconbox"><a id="" href="<?=base_url('admin/joomla_assign/add_record');?>"><img title="Add New Plan" alt="Add New Plan" src="<?=base_url()?>images/add-communication-plan-icon.jpg" class="img-responsive"><span>Add New Plan</span></a></div>
                                </div>
                                 <?php } ?>
                                <?php if(!empty($this->modules_unique_name) && in_array('auto_communication',$this->modules_unique_name)){?>
                                <div class="col-lg-3 col-md-3 text-center">
                                    <div class="iconbox"><a id="" href="<?=base_url('admin/joomla_assign');?>"><img title="View Existing Plan" alt="View Existing Plan" src="<?=base_url()?>images/view-existing-plans.jpg" class="img-responsive"><span>View Existing Plan</span></a></div>
                                </div>
                                 <?php } ?>
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