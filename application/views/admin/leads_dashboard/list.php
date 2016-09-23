<?php 
 /*
    @Description: Joomla Dashboard
    @Author     : Sanjay Moghariya
    @Date       : 14-11-2014

*/
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){

	//var temp = $('#joomla_contact_category').css('color','#e3b500');
	//alert($('#joomla_contact_category option[selected=selected]').length);
	try{
		var id = parent.$("#slt_interaction_type").val();
		//parent.selecttemplate(id,'selected')	
		parent.selectcategory(id,'selected');
	 }
	 catch(err) {
        // Handle error(s) here
    }
	parent.parent.$('.close_contact_select_popup').trigger('click');
	$.unblockUI();
});
</script>
<?php

$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
$tab_setting = check_joomla_tab_setting($admin_session['id']);
$path_per_1 = 'contacts/insert_conversations';
$path_per_tou = 'contacts/insert_personal_touches';
$path_per_notes = 'contacts/insert_contact_notes';
?>

 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('leads_dashboard_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('leads_dashboard_header');?></h3>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="">
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
                <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." title="Search Text" value="<?=!empty($searchtext)?$searchtext:''?>">
                <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
            </label>
           </div>
          </div>
         </div>
            
         <div class="row dt-rt">
          <div class="col-sm-4">
           <?php if(!empty($this->modules_unique_name) && in_array('lead_dashboard_delete',$this->modules_unique_name)){?>
           <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Contact">Delete Lead</button>
           <? } ?>
          </div>
          
        <div class="col-sm-8">
            <?php if(!empty($tab_setting) && $tab_setting[0]['contact_form_tab'] == '1'){?>
             <a class="btn  pull-right btn-success howler margin-left-5px kmrg" title="<?=$this->lang->line('joomla_contact_form');?>" href="<?=base_url('admin/joomla_contact_form');?>"><?=$this->lang->line('joomla_contact_form');?></a>
             <? } ?>
        <?php if(!empty($this->modules_unique_name) && (in_array('lead_distribution_agent',$this->modules_unique_name) || in_array('lead_distribution_lender',$this->modules_unique_name))){?>
             <a class="btn  pull-right btn-success howler margin-left-5px kmrg" title="Lead Distribution" href="<?=base_url('admin/user_rr_weightage');?>">Lead Distribution</a>
             <? } ?>
              <?php if(!empty($this->modules_unique_name) && in_array('auto_communication',$this->modules_unique_name)){?>
             <a class="btn  pull-right btn-secondary-green howler" title="<?php echo $this->lang->line('leads_dashboard_assigncomm_plan');?>" href="<?=base_url('admin/joomla_assign/joomla_assign_home');?>"><?php echo $this->lang->line('leads_dashboard_assigncomm_plan');?></a>
             <?php } ?>
        </div>
          
         </div>
            <!-- Sanjay Moghariya 10-07-2015 -->
            <div class="row dt-rt">
                <div class="col-sm-4">
                    <select class="form-control parsley-validated" name="sel_reg_source" id="sel_reg_source" onchange="contact_search('changescriteria');">
                        <option value="">Select Registration Source</option>
                        <?php if(!empty($sel_assigned_domain))
                        {
                            foreach($sel_assigned_domain as $sel_assigned_row)
                            { ?>
                                <option value="<?=$sel_assigned_row['id']?>" <?php if(!empty($search_reg_source) && $search_reg_source == $sel_assigned_row['id']) { echo 'selected="selected"'; } ?>><?=$sel_assigned_row['domain_name']?></option>;
                            <?php }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select class="form-control parsley-validated" name="sel_lead_type" id="sel_lead_type" onchange="contact_search('changescriteria');">
                        <option value="">Select Lead Type</option>
                            <option value="Buyer" <?php if(!empty($search_lead_type) && $search_lead_type == 'Buyer') { echo 'selected="selected"'; } ?>>Buyer</option>
                            <option value="Seller" <?php if(!empty($search_lead_type) && $search_lead_type == 'Seller') { echo 'selected="selected"'; } ?>>Seller</option>
                            <option value="Buyer/Seller" <?php if(!empty($search_lead_type) && $search_lead_type == 'Buyer/Seller') { echo 'selected="selected"'; } ?>>Buyer/Seller</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select class="form-control parsley-validated" name="sel_agent" id="sel_agent" onchange="contact_search('changescriteria');">
                        <option value="">Select Agent</option>
                        <?php if(!empty($sel_agent_list))
                        {
                            foreach($sel_agent_list as $agent_row)
                            { ?>
                                <option value="<?=$agent_row['id']?>" <?php if(!empty($search_agent) && $search_agent == $agent_row['id']) { echo 'selected="selected"'; } ?>><?=$agent_row['agent_name'].' ('.$agent_row['email_id'].')'?></option>;
                            <?php }
                        }
                        ?>
                    </select>
                </div>
            </div> 
            <!-- End Sanjay Moghariya 10-07-2015 -->
             
		 <div class="row dt-rt">
          <div class="col-sm-7">

		    <a class="btn new-color howler margin-left-5px" title="New" href="#" onclick="contact_category('changesearch','New')">NEW</a>
			<a class="btn quality-color howler margin-left-5px" title="Qualify" href="#" onclick="contact_category('changesearch','Qualify')">QUALIFY</a>
			<a class="btn hot-color howler margin-left-5px" title="Hot" href="#" onclick="contact_category('changesearch','Hot')">HOT</a>
			<a class="btn watch-color howler margin-left-5px" title="Watch" href="#" onclick="contact_category('changesearch','Watch')">WATCH</a>
			<a class="btn narture-color howler margin-left-5px" title="Nurture" href="#" onclick="contact_category('changesearch','Nurture')">NURTURE</a>
			
			<a class="btn active_all-color howler margin-left-5px" title="All Active" href="#" onclick="contact_category('changesearch','Inactive')">All ACTIVE</a>
			  
          </div>
          
        <div class="col-sm-5">
           

			<a class="btn  pull-right trash-color howler margin-left-5px" title="Trash" href="#" onclick="contact_category('changesearch','Bogus')">BOGUS</a>
						 <a class="btn  pull-right archive-color howler margin-left-5px" title="Inactive Prospect" href="#" onclick="contact_category('changesearch','Inactive Prospect')">ARCHIVE</a>
			<a class="btn  pull-right closed-color howler margin-left-5px" title="Closed Transaction" href="#" onclick="contact_category('changesearch','Closed Transaction')">CLOSED</a>

			 <a class="btn  pull-right pending-color howler margin-left-5px" title="Pending Transaction" href="#" onclick="contact_category('changesearch','Pending Transaction')">PENDING</a>
        </div>
          
         </div>
            
         <div id="common_div" class="table_large-responsive table-large-scroll">
         <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
         </div>
        </div>
       </div>
       <!-- /.table-responsive --> 
       
 <div class="row">      
<div class="col-sm-12 col-md-12 col-lg-6">
<div class="active-categories-box">
<h4>Active Categories</h4>
<div class="color-box"><div class="new-color">NEW</div><div class="color-info">= New Lead - <strong>Call & email ASAP!</strong></div></div>
<div class="color-box"><div class="quality-color">QUALIFY</div><div class="color-info">= Not Contacted - <strong>Need to Qualify</strong></div></div>
<div class="color-box"><div class="narture-color">NURTURE</div><div class="color-info">= Contacted -<strong> Ready in 3 to 6 mos.</strong></div></div>
<div class="color-box"><div class="watch-color">WATCH</div><div class="color-info">= Contacted -<strong> Long-term Prospect</strong></div></div>
<div class="color-box"><div class="hot-color">HOT</div><div class="color-info">= <strong>Ready &amp; Committed Prospect</strong></div></div>
</div>
</div>

<div class="col-sm-12 col-md-12 col-lg-6">
<div class="active-categories-box">
<h4>Other Categories</h4>
<div class="color-box"><div class="pending-color">PENDING</div><div class="color-info">= <strong>Pending Transaction</strong></div></div>
<div class="color-box"><div class="closed-color">CLOSED</div><div class="color-info">= <strong>Closed Transaction</strong></div></div>
<div class="color-box"><div class="archive-color">ARCHIVE</div><div class="color-info">= <strong>Inactive Prospect</strong></div></div>
<div class="color-box"><div class="trash-color">TRASH</div><div class="color-info">= <strong>Bogus info / No Potential Value</strong></div></div>
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
 <div aria-hidden="true" style="display: none;" id="basicModal_email_popup1" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
		<input type="hidden" name="from_joomla_view" value="2" />
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
	   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>_call_log" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_1;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		 
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:
			  </td>
              <td>
               <select id="sl_interaction_type" name="sl_interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option <?php if($row['id'] == 4) echo 'selected="selected"'; ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				 <?php }?>
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
				<?php foreach($disposition_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
                </select>
                 <input type="hidden" name="from_joomla_view" value="1" />
				 <input id="contact_id1" name="contact_id" type="hidden" value="">
				 <input type="hidden" name="from_joomla_view" value="2" />
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
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_tou;?>" novalidate >
      <div class="modal-body">
        <div class="col-sm-12">
		
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr style="display:none;">
              <td>Action Type:</td>
              <td>
                <select id="interaction_type" name="interaction_type" class="form-control parsley-validated" data-required="true">
				<?php foreach($interaction_type as $row)
				{?>
                 <option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
				 <?php }?>
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
      	<input type="hidden" name="from_joomla_view" value="1" />
        <input type="submit" value="Add To Do Task" class="btn btn-secondary" title="Add To Do Task" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
		<input type="hidden" name="from_joomla_view" value="2" />
        
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
	  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_per_notes;?>" novalidate >
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
      	<input type="hidden" name="from_joomla_view" value="1" />
        <input id="contact_id3" name="contact_id" type="hidden" value="">
        <input type="submit" value="Add Note" class="btn btn-secondary" title="Add Note" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();">
		<input type="hidden" name="from_joomla_view" value="2" />
        
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
 
 
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	 
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title"><?=$this->lang->line('label_assigned_iplan');?></h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
       
		<div class="col-sm-12 view_contact_popup height_fix">
		
          
		  <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		  </div>
		 
		  
		  <?php /*?><?php $this->load->view('admin/interaction_plans/view_contact_popup');?><?php */?>
		  
       
      </div>
    </div>
	</div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
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
			url: "<?php echo base_url();?>admin/leads_dashboard/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
                                ,registration_source:$("#sel_reg_source").val(),lead_type:$("#sel_lead_type").val(),sel_agent:$("#sel_agent").val()
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
	
	function contact_category(allflag,category)
	{
            var uri_segment = $("#uri_segment").val();
			var flag_category = 1;//alert(category);
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/leads_dashboard/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:category,sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,flag_category:flag_category
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
	
	function clearfilter_contact()
	{
		$("#sel_reg_source").val("");
                $("#sel_lead_type").val("");
                $("#sel_agent").val("");
                $("#searchtext").val("");
		contact_search('all');
	}
	
	function changepages()
	{
		contact_search('');	
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search('changesorting');
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
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
            return false;
        });
		
	//$('#selecctall').click(function(event) {  //on click
	$('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
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
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			success: function(data){
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/leads_dashboard/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
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
			},error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				//alert('Please Select Record(s) To Delete.')
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to delete Record(s)';
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
        function change_contact_type(contact_type,contact_id)
        {
            $('#common_div').block({ message: 'Loading...' });
            $.ajax({
                type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/change_contact_type';?>",
                dataType: 'json',
                async: false,
                data: {'contact_id':contact_id,'contact_type':contact_type},
                success: function(data){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>admin/leads_dashboard/"+data,
                        data: {
                            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
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
		 function change_contact_category(contact_category,contact_id)
        {
            $('#common_div').block({ message: 'Loading...' });
            $.ajax({
                type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/change_contact_category';?>",
                dataType: 'json',
                async: false,
                data: {'contact_id':contact_id,'contact_category':contact_category},
                success: function(data){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>admin/leads_dashboard/"+data,
                        data: {
                            result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
                            ,registration_source:$("#sel_reg_source").val(),lead_type:$("#sel_lead_type").val(),sel_agent:$("#sel_agent").val()
                        },
                        beforeSend: function() {
                                $('#common_div').block({ message: 'Loading...' }); 
                          },
                        success: function(html){
                                $("#common_div").html(html);
                                /*var cls = $('#joomla_contact_category_'+contact_id).find('option:selected').attr('class');
                                $('#joomla_contact_category_'+contact_id).attr('class',cls);*/
                                $('#common_div').unblock(); 
                        }
                    });
                    return false;
                }
            });
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

        var frameSrc = '<?= $this->config->item('admin_base_url'); ?>emails/add_record/'+id+'/'+email_trans_id;
	$('.popup_heading_h3').html('Email');
	$(".email_sms_send_popup1 .modal-body").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	//$('iframe').attr("src",frameSrc);
	$(".email_sms_send_popup .modal-body").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="505" width="99.6%"></iframe>');
}
$('body').on('click','.view_contacts_btn',function(e){
	
			$(".view_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	
			id = $(this).attr('data-id');
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_contact_interaction_plan_list';?>",
			data: {'contact_id':id},
			success: function(html){
				
				$(".view_contact_popup").html(html);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	//console.log(textStatus, errorThrown);
			  	$(".view_contact_popup").html('Something went wrong.');
			}
			});
	});
        
        function setcontact_session(contact_id,view_id)
        {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/selectedview_session';?>",
                data: {selected_view:view_id,page_name:'dboard'},
                success: function(html){
                    //window.location.href = "<?=base_url()?>admin/contacts/view_record/"+contact_id;
                    window.location.href = "<?=base_url()?>admin/leads_dashboard/view_record/"+contact_id;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        //$(".view_contact_popup").html('Something went wrong.');
                }
            });

        }
        function form_submit3(viewname)
        {
          $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
          $("#"+viewname).submit();
        }
</script>