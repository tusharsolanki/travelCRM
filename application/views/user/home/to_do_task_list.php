<?php 
/*
    @Description: user Dashborad task list
    @Author     : Sanjay Chabhadiya
    @Date       : 19-01-2015
*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
close_popup();
</script>
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
$path_per_1 = 'contacts/insert_conversations';
$path_per_tou = 'contacts/insert_personal_touches';
$path_per_notes = 'contacts/insert_contact_notes';
if(!isset($tabid))
	$tabid = 1;
?>

<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content mian_box">
      <div class="modal-header">
        <button type="button" class="close close_call_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Phone Call<span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body pading_zero">
			<iframe src="" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_contact_popup">
          
		  <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		  </div>
		  
		  <?php /*?><?php $this->load->view('user/interaction_plans/view_contact_popup');?><?php */?>
		  
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" style="display: none;" id="template_details" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title"><span class="template_data"></span></h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup text-center">
		 <div id="row_data">
         </div>
		 <!--<input type="submit" class="btn btn-secondary" value="Print" onClick="Popup()" name="print" />-->
		<div id="previewformdata">
		</div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('task_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>To-Do Tasks</h3>
       <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
				<?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
    	 
         </div>
         <div class="portlet-content">
            <ul class="nav nav-tabs" id="myTab1">
             <? if(in_array('communications',$this->modules_unique_name)){ }else{$tabid = 4;}?>
             <? if(in_array('communications',$this->modules_unique_name)){ ?>
              <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?> > <a title="Mailings" data-toggle="tab" href="#Mailings" onclick ="contact_search('changesearch','1');">Mailings</a> </li>      
               <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Calls" data-id="calls" data-toggle="tab" href="#Calls" onclick ="contact_search('changesearch','2');">Calls</a> </li>
               <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Text" data-toggle="tab" href="#Text" onclick ="contact_search('changesearch','3');">Text</a> </li>
                 <? } ?>
               <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="Tasks" data-toggle="tab" href="#Tasks" onclick ="contact_search('changesearch','4');">Tasks</a> </li>
                <? if(in_array('communications',$this->modules_unique_name)){ ?>
               <li <?php if($tabid == 5){?> class="active" <?php } ?>> <a title="Emails" data-toggle="tab" href="#Emails" onclick ="contact_search('changesearch','5');">Emails</a> </li>
                <? } ?>
               <li <?php if($tabid == 6){?> class="active" <?php } ?>> <a title="All Actions" data-toggle="tab" href="#All_Actions" onclick ="contact_search('changesearch','6');">All Actions</a> </li>
           </ul>
         <div class="tab-content" id="myTab1Content">
          <? if(in_array('communications',$this->modules_unique_name)){ ?>
         	<div class="<?php if($tabid == '' || $tabid == 1){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="Mailings" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                         <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input class="searchtext" type="text" name="searchtext_1" id="searchtext_1" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','1');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                   <button class="btn btn-secondary howler" data-type="danger" title="Complete" onclick="finaldata('0')" target="_blank">Complete</button>
                  </div>
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_1">
                 	<?=$this->load->view('user/home/letter_label_envelope_ajax_list')?>
                 </div>
            </div>
            <div class="<?php if($tabid == 2){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="Calls" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                         <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input class="searchtext" type="text" name="searchtext_2" id="searchtext_2" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','2');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                  </div>
                  <div class="col-sm-6">
                  	<a href="#basicModal1" class="text_size btn pull-right btn-success howler start_call" id="basicModal" data-toggle="modal" onclick="phone_call(0)" >Start Call</a>
                  </div>
                 </div>
                 <div id="common_div_2">
                 <?=$this->load->view('user/home/telephone_task_ajax_list')?>
                 </div>
            </div>
            <div class="<?php if($tabid == 3){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="Text" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_3" id="searchtext_3" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','3');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                  </div>
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_3">
                 <?=$this->load->view('user/home/sms_ajax_list',!empty($sms_data)?$sms_data:'');?>
                 </div>
            </div>
            <? } ?>
            <div class="<?php if($tabid == 4){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="Tasks" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                         <input type="hidden" name="uri_segment_4" id="uri_segment_4" value="0">
                        <input class="searchtext" type="text" name="searchtext_4" id="searchtext_4" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','4');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                  </div>
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_4">
                 <?php if($tabid == 4){ 
                 	echo $this->load->view('user/home/task_ajax_list');
                  } ?>
                 </div>
            </div>
             <? if(in_array('communications',$this->modules_unique_name)){ ?>
            <div class="<?php if($tabid == 5){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="Emails" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_5" id="searchtext_5" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','2');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                  </div>
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_5">
                 <?=$this->load->view('user/home/email_ajax_list',!empty($emails_data)?$emails_data:'');?>
                 </div>
            </div>
             <? } ?>
            <div class="<?php if($tabid == 6){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="All_Actions" >
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                         <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input class="searchtext" type="text" name="searchtext_6" id="searchtext_6" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','6');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button>
                  </div>
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_6">
                 <?=$this->load->view('user/home/to_do_task_ajax_list')?>
                 </div>
            </div>
         </div>
         <input type="hidden" id="tab_id" name="tab_id" value="<?=!empty($tabid)?$tabid:'1'?>" />
         <input type="hidden" id="url" name="url" value="<?php echo base_url();?>user/dashboard/to_do_task" />
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
  <!-- .-header --> 
  
  <!-- /#content-container --> 
  
 </div>


<div aria-hidden="true" style="display: none;" id="basicModal_email_popup1" class="modal fade email_sms_send_popup1">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
		<input type="hidden" name="from_joomla_view" value="3" />
        <h3 class="modal-title">Send <span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body holds-the-iframe">
        <iframe src="" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<div aria-hidden="true" style="display: none;" id="basicModal_conversation" class="new_call_log_popup modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 class="modal-title">Add Call Log</h3>
      </div>
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>_call_log" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:
			  </td>
              <td>
               <select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php if(!empty($interaction_type) && count($interaction_type) > 0){
						foreach($interaction_type as $row)
				{?>
                 <option <?php if($row['id'] == 4) echo 'selected="selected"'; ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php } } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>
                <textarea class="form-control" name="description" id="description"></textarea>
              </td>
            </tr>
            <tr>
              <td>Disposition:</td>
              <td>
                 <select id="disposition_type" name="disposition_type" class="form-control parsley-validated" data-required="true">
				<?php if(!empty($disposition_type) && count($disposition_type) > 0){
						foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php } } ?>
                </select>
				 <input id="contact_id1" name="contact_id" type="hidden" value="">
				 <input type="hidden" name="from_joomla_view" value="3" />
              </td>
            </tr>
          </table>
		  
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="submit" id="activitylog" class="btn btn-secondary" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">Add Call Log</button>
        <!--<button type="button" class="btn btn-primary">Cancel</button>-->
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
 <div aria-hidden="true" style="display: none;" id="basicModal_2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 class="modal-title">Add To Do Task</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_tou;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:</td>
              <td>
                <select id="interaction_type" name="interaction_type" class="form-control parsley-validated" data-required="true">
				<?php if(!empty($interaction_type) && count($interaction_type) > 0){ 
						foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php } } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Task:</td>
              <td>
                <textarea class="form-control" name="task" id="task"></textarea>
              </td>
            </tr>
            <tr>
              <td>Follow-up Date:</td>
              <td>
			  	 <input id="contact_id2" name="contact_id" type="hidden" value="">
                 <input id="followup_date" name="followup_date" class="form-control parsley-validated" type="text" readonly>
              </td>
            </tr>
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <input type="submit" value="Add To Do Task" class="btn btn-secondary" title="Add To Do Task" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
		<input type="hidden" name="from_joomla_view" value="3" />
        
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
 <div aria-hidden="true" style="display: none;" id="basicModal_notes" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 class="modal-title">Add Note</h3>
      </div>
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path_per_notes;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Notes:</td>
              <td>
                <textarea class="form-control" name="notes_detail" id="notes_detail"></textarea>
              </td>
            </tr>
          </table>
		 
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <input id="contact_id3" name="contact_id" type="hidden" value="">
        <input type="submit" value="Add Note" class="btn btn-secondary" title="Add Note" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
		<input type="hidden" name="from_joomla_view" value="3" />
        
      </div>
	  </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>		

<a id="a_conversation_id" style="display:none;" title="Add Conversations Log" href="#basicModal_conversation"  data-toggle="modal" >Click</a>
<a title="Add To Do"  style="display:none;" id="set_to_do" href="#basicModal_2"  data-toggle="modal"><button title="Set To Do" data-type="danger" class="btn btn-secondary howler smaller_btn"><i class="fa fa-file-text"></i>Set To Do</button></a>

<a title="Add Notes" href="#basicModal_notes" style="display:none;"  data-toggle="modal" id='Add_Note'>Add Note</a>

<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search(allflag,tab)
	{
		//alert(tab);
		//var tab_id = 1;
		if(tab == 1)
			url = "<?php echo base_url();?>user/dashboard/letter_label_envelope_task";
		else if(tab == 2)
			url = "<?php echo base_url();?>user/dashboard/telephone_task";
		else if(tab == 3)
			url = "<?php echo base_url();?>user/dashboard/sms_task"
		else if(tab == 4)
			url = "<?php echo base_url();?>user/dashboard/daily_task";
		else if(tab == 5)
			url = "<?php echo base_url();?>user/dashboard/email_task";
		else if(tab == 6)
			url = "<?php echo base_url();?>user/dashboard/to_do_task";
		$("#tab_id").val(tab);
		$("#url").val(url);
        //var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: url,
			data: {
			result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val(),allflag:allflag
		},
		beforeSend: function() {
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				  },
			success: function(html){
				$("#common_div_"+tab).html(html);
				$.unblockUI();
			}
		});
		return false;
	}
	/*$('body').on('click','#myTab1 li a',function(e){
		contact_search('changesearch',$(this).attr('data-id'));
	});*/
	
	
	$('body').on('click','.common_tb a.paginclass_A',function(e){
		//alert($(this).attr('href'));
	 	//alert($("#uri_segment_5").val());
	   	sanjaytest($(this).attr('href'));
	   	return false;
    });
	
	
	function sanjaytest(url)
	{
		var tab = $("#tab_id").val();
		 $.ajax({
			type: "POST",
			url: url,
			data: {
			result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val()
		},
		beforeSend: function() {
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				  },
			success: function(html){
				$("#common_div_"+tab).html(html);
				$.unblockUI();
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('.searchtext').keyup(function(event) 
		  {	
				if (event.keyCode == 13) {
						contact_search('changesearch',$("#tab_id").val());
				}
		  });
	});
	$(".close_call_popup").click(function (){
		var tab = $("#tab_id").val();
		contact_search('all',tab)
	});
	
	function clearfilter_contact()
	{
		var tab = $("#tab_id").val();
		$("#searchtext_"+tab).val("");
		contact_search('all',tab);
	}
	
	function changepages()
	{
		contact_search('',$("#tab_id").val());
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		var tab = $("#tab_id").val();
		$("#sortfield_"+tab).val(sortfilter);
		$("#sortby_"+tab).val(sorttype);
		contact_search('changesorting',tab);
	}
	
	$('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox_'+$("#tab_id").val()).each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox_'+$("#tab_id").val()).each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
	{
		var tab = $("#tab_id").val();
		if(tab == 4)
			var url = "<?php echo $this->config->item('user_base_url').'dashboard/ajax_delete_all';?>"
		else
			var url = "<?php echo $this->config->item('user_base_url').'dashboard/ajax_delete_task';?>"
		var myarray = new Array;
		var i=0;
		var boxes = $('input[name="check_'+$("#tab_id").val()+'[]"]:checked');
		$(boxes).each(function(){
			  myarray[i]=this.value;
			  i++;
		});
		if(id != '0')
		{
			var single_remove_id = id;
		}
		$.ajax({
		type: "POST",
		url: url,
		dataType: 'json',
		async: false,
		data: {'myarray':myarray,'single_remove_id':id,'interaction_type':tab},
		success: function(data){
			$.ajax({
				type: "POST",
				url: $("#url").val()+'/'+data,
				data: {
				result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val(),allflag:'',interaction_type:'to-do'
			},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
				success: function(html){
					$("#common_div_"+tab).html(html);
					$.unblockUI();
				}
			});
			return false;
		}
		});
	}
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check_'+$("#tab_id").val()+'[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;
				
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				
				if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name+'"';
				}
				else
				{
					var msg = 'Are you sure want to delete "'+unescape(name)+'"';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	}
	
function iscompleted(id,value)
{
	var tab = $("#tab_id").val();
	if(tab == 4)
	{
		var url = "<?php echo base_url("user/dashboard/iscompleted");?>";
		var msg = "Are you sure that the task is completed";
	}
	else
	{
		if(value == '3')
			var msg = "Are you sure want to reschedule task";
		else
			var msg = "Are you sure that the task is completed";
		var url = "<?php echo base_url("user/dashboard/is_completed");?>";
	}
	
	$.confirm({'title': 'CONFIRM','message': " <strong>"+msg+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: url,
			data: {
			selectedvalue:id,disposition:value,interaction_type:tab
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },

			success: function(html){
				$.ajax({
					type: "POST",
					url: $("#url").val()+'/'+html,
					data: {
					result_type:'ajax',searchreport:$("#searchreport_"+tab).val(),perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val()
				},
				beforeSend: function() {
							$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
						  },
					success: function(html){
						$("#common_div_"+tab).html(html);
						$.unblockUI();
					}
				});
			}
		});
	}},'No'	: {'class'	: 'special',
	'action': function(){
		$('.disposition:radio[value='+parseInt(value)+']').attr('checked',false);
	}
	}}});
		return false;
	
}
	
function resend_mail(id,contact_id)
{
	var tab = $("#tab_id").val();
	//alert($("#uri_segment_"+tab).val());
	if(tab == 3)
		url = "<?php echo $this->config->item('user_base_url').'dashboard/send_sms/';?>";
	else
		url = "<?php echo $this->config->item('user_base_url').'dashboard/send_mail/';?>";
	$.ajax({
		type: "POST",
		url: url,
		async: false,
		data: {'single_id':id,contact_id:contact_id,uri_segment:$("#uri_segment_"+tab).val()},
		success: function(result){
		//alert(result);
			$.ajax({
				type: "POST",
				url: $("#url").val()+'/'+result,
				data: {
				result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val()
			},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
				success: function(html){
					$("#common_div_"+tab).html(html);
					$.unblockUI();
				}
			});
			return false;
		}
	});
}

	
	$('body').on('click','.div_dash_more_data_a',function(e){
		//alert($(this).closest('div.div_dash_less_data').next('div.div_dash_more_data').html());
		$(this).closest('div.div_dash_less_data').toggle();
		$(this).closest('div.div_dash_less_data').next('div.div_dash_more_data').toggle();
	});
	
	$('body').on('click','.div_dash_less_data_a',function(e){
		//alert($(this).closest('div.div_dash_less_data').next('div.div_dash_more_data').html());
		$(this).closest('div.div_dash_more_data').toggle();
		$(this).closest('div.div_dash_more_data').prev('div.div_dash_less_data').toggle();
	});
	
$('body').on('click','.view_contacts_btn',function(e){
	
	$(".view_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');

	planid = $(this).attr('data-id');
	
	$.ajax({
	type: "POST",
	url: "<?php echo $this->config->item('user_base_url').'dashboard/view_contacts_of_interaction_plans';?>",
	data: {'interaction_plan':planid},
	success: function(html){
		$(".view_contact_popup").html(html);	
	},
	error: function(jqXHR, textStatus, errorThrown) {
		//console.log(textStatus, errorThrown);
		$(".view_contact_popup").html('Something went wrong.');
	}
	});
});

$('body').on('click','.view_form_btn',function(e){
	
			var id = $(this).attr('data-id');
			var temp_name = $('#temp_name_'+id).text();
			var temp_category = $('#temp_category_'+id).html();
			//var temp_subject = $('#temp_subject_'+id).text();
			var temp_desc = $('#temp_desc_'+id).html();
			$('#row_data').html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif"></div>');
			var tab = $("#tab_id").val();
			if(tab == 1)
			{
				var title = 'Template Details';
				var form_data = '<table><tr><td align="left"><label align="left" for="text-input">Template Name  </label></td><td> : </td><td align="left">'+temp_name+'</td></tr><tr><td align="left"><label align="left" for="text-input">Category  </label></td><td> : </td><td align="left">'+temp_category+'</td></tr><tr><td valign="top" align="left" width="25%"><label align="left" for="text-input">Template Message</label></td><td> : </td><td>&nbsp;</td></tr><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';
			
			}
			else if(tab == 2)
			{
				var title = 'Script';
				var form_data = '<table><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';	
			}
			else if(tab == 3)
			{
				var title = 'Text Message';
				var form_data = '<table><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';	
			}
			else if(tab == 4)
			{
				var title = 'Task View';
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('user_base_url')?>dashboard/view_records",
					data: {id:id},
					success: function(html){
						
						$("#row_data").html(html);
						//alert(html);
						//var from_data = html;
					}
				});
			}
			else if(tab == 5)
			{
				var title = 'Email Message';
				var form_data = '<table><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';	
			}
			
			$(".template_data").html(title);
			$("#row_data").html(form_data);
	});
function phone_call(uri_segment){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>dashboard/phone_call_popup/'+$("#sortfield_2").val()+'/'+$("#sortby_2").val()+'/'+uri_segment;
	
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="525" width="99.6%"></iframe>');
}

function show_action(id,contact_id)
{
		if(id != '')
		{
			$("#contact_id1").val(contact_id);
			$("#contact_id2").val(contact_id);
			$("#contact_id3").val(contact_id);
			$("#"+id).trigger('click');
		}	
	
}
function add_email_campaign(id,email_trans_id){
    var frameSrc = '<?= $this->config->item('user_base_url'); ?>emails/add_record/'+id+'/'+email_trans_id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup1 .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	
	$(".email_sms_send_popup1 .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
</script>