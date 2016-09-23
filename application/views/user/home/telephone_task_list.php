<?php 
/*
    @Description: User Dashborad task list
    @Author     : Sanjay Chabhadiya
	@Date       : 11-11-14
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
$viewname = $this->router->uri->segments[3];
$path_per_1 = 'contacts/insert_conversations';
$path_per_tou = 'contacts/insert_personal_touches';
$path_per_notes = 'contacts/insert_contact_notes';
?>
<div aria-hidden="true" style="display: none;" id="template_details" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Script</h3>
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

 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('task_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Scheduled Phone Tasks</h3>
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
         <div class="row dt-rt">
          <div class="col-sm-1">
           
          </div>
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
                 <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                <input type="text" name="searchtext" id="searchtext" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>">
                    <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch');">Search</button>
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
          <a href="#basicModal1" class="text_size btn pull-right btn-success howler start_call" id="basicModal1" data-toggle="modal" onclick="phone_call(0)" >Start Call</a>
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/home/telephone_task_ajax_list')?>
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
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
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
				 <?php } }?>
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

$(function() {
	$( "#followup_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/dashboard/<?=$viewname?>/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_2").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_2").val(),sortby:$("#sortby_2").val(),allflag:allflag
		},
		beforeSend: function() {
					$('#common_div').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div").html(html);
				$('#common_div').unblock(); 
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('#searchtext').keyup(function(event) 
		  {
			  /*if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}*/
				
				if (event.keyCode == 13) {
						contact_search('changesearch');
				}
			//return false;
		  });
	});
	$(".close_call_popup").click(function (){
		contact_search();
	});
	
	function clearfilter_contact()
	{
		$("#searchtext").val("");
		contact_search('all');
	}
	
	function changepages()
	{
		contact_search('');	
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield_2").val(sortfilter);
		$("#sortby_2").val(sorttype);
		contact_search('changesorting');
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_2").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_2").val(),sortby:$("#sortby_2").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
            return false;
        });
		
	//$('#selecctall').click(function(event) {  //on click
	$('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox_2').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox_2').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check_2[]"]:checked');
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
			url: "<?php echo $this->config->item('user_base_url').'dashboard/ajax_delete_task';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id,interaction_type:'call'},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/dashboard/<?=$viewname?>/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_2").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_2").val(),sortby:$("#sortby_2").val(),allflag:''
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
					}
				});
				return false;
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check_2[]"]:checked');
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
				var msg = 'Are you sure want to delete '+name;
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

function phone_call(uri_segment){
	var frameSrc = '<?= $this->config->item('user_base_url'); ?>dashboard/phone_call_popup/'+$("#sortfield_2").val()+'/'+$("#sortby_2").val()+'/'+uri_segment;
	
	$(".email_sms_send_popup .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="525" width="99.6%"></iframe>');
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
$('body').on('click','.view_form_btn',function(e){	
	var id = $(this).attr('data-id');
	//var temp_name = $('#temp_name_'+id).val();
	//var temp_category = $('#temp_category_'+id).html();
	//var temp_subject = $('#temp_subject_'+id).val();
	var temp_desc = $('#temp_desc_'+id).html();
	var form_data = '<table><tr><td colspan="3" align="left">'+temp_desc+'</td></tr></table>';
	$("#row_data").html(form_data);
});

function iscompleted(id,value)
{
	if(value == '3')
		var msg = "Are you sure want to reschedule task";
	else
		var msg = "Are you sure that the task is completed";
	$.confirm({'title': 'CONFIRM','message': " <strong>"+msg+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("user/dashboard/is_completed");?>',
			data: {
			selectedvalue:id,disposition:value,interaction_type:'call',selecteddate:$("#task_date").val()
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
			success: function(html){
			$.ajax({
                type: "POST",
                url: '<?=base_url()?>user/dashboard/<?=$viewname?>/'+html,
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
			
				$.unblockUI();
			}
		});
	}},'No'	: {'class'	: 'special',
	'action': function(){
		$('.disposition:radio[value='+parseInt(value)+']').attr('checked',false);
	}
	}}});
		return false;
	
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
	//$('iframe').attr("src",frameSrc);
	
	$(".email_sms_send_popup1 .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}

</script>