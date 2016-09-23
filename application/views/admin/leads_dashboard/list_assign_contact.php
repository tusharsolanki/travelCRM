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
		<div id="temp_dev1" class="col-sm-12 text-center error"></div>
        <div id="common_div" class="dataTables_wrapper">
			<?php $this->load->view('admin/'.$viewname.'/ajax_list_valuation_searched')?>
		</div>
		<div id="temp_dev" class="col-sm-12 text-center error"></div>
		 <div id="common_div1" class="dataTables_wrapper">
		 	
			<?php $this->load->view('admin/'.$viewname.'/ajax_list_select_contact')?>
		</div>
		
         <?php /*?><?php $this->load->view('admin/'.$viewname.'/ajax_list_assign_contact')?>
		 <?php $this->load->view('admin/'.$viewname.'/ajax_list_select_contact')?><?php */?>
		
			
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  

 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
