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
    <h1>Email Blast</h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>Email Blast</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                   <?php if(!empty($this->modules_unique_name) && in_array('email_blast_add',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/emails/add_record');?>"><img title="New Email Blast" alt="New Email Blast" src="<?=base_url()?>images/email-sent-form.jpg" class="img-responsive"><span>New Email Blast</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('email_blast',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/emails');?>"><img title="View Sent Blasts" alt="View Sent Blasts" src="<?=base_url()?>images/email-sent-list.jpg" class="img-responsive"><span>View Sent Blasts</span></a></div>
                  </div>
                  <? } 
				    if(!empty($connection[0]['bombbomb_username']) && !empty($connection[0]['bombbomb_password']))
					{
				  ?>
                   <?php if(!empty($this->modules_unique_name) && in_array('bomb_bomb_email_blast_add',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/bomb_emails/add_record');?>"><img title="New Bomb Bomb Email Blast" alt="New Bomb Bomb Email Blast" src="<?=base_url()?>images/new-bomb-bomb-email-blast.jpg" class="img-responsive"><span>New Bomb Bomb Email Blast</span></a></div>
                  </div>
                  <? } ?>
                  <?php if(!empty($this->modules_unique_name) && in_array('bomb_bomb_email_blast',$this->modules_unique_name)){?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('admin/bomb_emails');?>"><img title="View Sent Bomb Bomb Blasts" alt="View Sent Bomb Bomb Blasts" src="<?=base_url()?>images/view-sent-bomb-bomb-blasts.jpg" class="img-responsive"><span>View Sent Bomb Bomb Blasts</span></a></div>
                  </div>
                  <? } 
					} ?>
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
