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
            <h3> <i class="fa fa-table"></i>Mail Blast</h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
                       <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                <div class="row dt-rt">
                   <? if(in_array('letter_add',$this->modules_unique_name) || in_array('envelope_add',$this->modules_unique_name) || in_array('label_add',$this->modules_unique_name)){ ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/mail_out/add_record');?>"><img title="Email_Library" alt="Email_Library" src="<?=base_url()?>images/new-mail-blast.jpg" class="img-responsive"><span>New Mail Blast</span></a></div>
                  </div>
                   <? } ?>
                  <div class="col-lg-3 col-md-3 text-center">
                    <div class="iconbox"><a id="configuration_view" href="<?=base_url('user/mail_out');?>"><img title="Auto Responder" alt="Auto Responder" src="<?=base_url()?>images/view-past-mail-blast.jpg" class="img-responsive"><span>View Previous Blasts</span></a></div>
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
