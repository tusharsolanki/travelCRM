<?php 
    /*
        @Description: Admin social media post list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<style type="text/css">
input[type="text"].form-control { float:left; width:68%; }
</style>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
$start_date123 = date('Y-m-d');

?>

<div id="content">
  <div id="content-header">
    <h1> Analytics </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i> Analytics </h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content"> 
            
            <!-- /.table-responsive -->
            <div class="row">
              <div class="col-md-12"> <a class="btn btn-success howler" href="#basicModal_for" data-toggle="modal" title="Export report" > <i class="fa fa-sign-out"></i> Export Report to PDF </a> </div>
              <div class="col-md-12 new_bg_block">
                <div class="col-lg-6 col-sm-12 new_bg_white">
                  <p><a href="javascript:void(0)" onclick="contact_search()">Number of Leads : <strong class="font">
                    <?=!empty($total_lead_count)?$total_lead_count:'0'?>
                    </a></strong></p>
                     <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
                  <p><a href="javascript:void(0)" onclick="contact_search('assign_contact_data')">Leads assigned to campaigns : <strong class="font">
                    <?=!empty($assigned_lead_count)?$assigned_lead_count:'0'?>
                    </strong></a></p>
                  <p><a href="javascript:void(0)" onclick="contact_search('not_assign_contact_data')">Leads not assigned to campaigns : <strong class="font">
                    <?=!empty($not_assigned_lead_count)?$not_assigned_lead_count:'0'?>
                    </strong></a></p>
                  <p><a href="javascript:void(0)" onclick="contact_search('no_of_sent_email')">No. of Emails Sent against <br />
                    Communications : <strong class="font" id="sent_email">
                    <?=!empty($email_sent_against_interaction_plan_count)?$email_sent_against_interaction_plan_count:'0'?>
                    </strong></p></a>
                    <? } ?>
                    <div>
                
                <div class="main_box">
                  <div class="box-btn-left">Select Date-range</div>
                  <div class="box-btn-left">
                    <input type="text" class="form-control parsley-validated" id="sent_start_date" name="sent_start_date"  value="<?=date('Y-m-d', strtotime('-30 days'))?>" readonly="readonly">
                  </div>
                  <div class="box-btn-left">
                    <input type="text" class="form-control parsley-validated" id="sent_end_date" name="sent_end_date" value="<?=date('Y-m-d')?>" readonly="readonly">
                  </div>
                  <div class="box-btn-left">
                    <button class="btn btn-danger howler" onclick="contact_search('no_of_sent_email','');" title="Show Analytics"  data-type="danger">Show Analytics</button>
                    <input type="hidden" id="assign_not_contact_data" name="assign_not_contact_data" value="" />
                    <input type="hidden" id="joomla_domain_contact_data" name="joomla_domain_contact_data" value="" />
                    <input type="hidden" id="is_completed" name="is_completed" value="" />
                    <input type="hidden" id="current_url" name="current_url" value="" />
                  </div>
                </div>
                 <? if(!empty($joomla_domain)){ ?>
                <div class="main_box">
                   <p><strong>Joomla Leads :</strong></p>
                   </div>
                  <?
				   foreach($joomla_domain as $row)
				   {
				   ?>
                <p><a href="javascript:void(0)" onclick="contact_search('joomla_contact_data','','<?=!empty($row['domain'])?$row['domain']:''?>')"><?=!empty($row['domain'])?$row['domain']:''?> : <strong class="font">
                   <?=!empty($row['total_joomla_contact'])?$row['total_joomla_contact']:'0'?>
                    </strong></a></p>
                    <? }} ?>
                 
              </div>
                </div>
                <div class="col-sm-12 col-lg-6 new_bg_white">
                  <div class="">
                    <p><a href="javascript:void(0)" onclick="contact_search('task','pending');">Open Task List : <strong class="font">
                      <?=!empty($open_task_list)?$open_task_list:'0'?>
                      </strong></a></p>
                    <p><a href="javascript:void(0)" onclick="contact_search('task','complete');">Completed Task List : <strong class="font">
                      <?=!empty($completed_task)?$completed_task:'0'?>
                      </strong></a></p>
                    <p><a href="javascript:void(0)" onclick="contact_search('client_contact_lead');">Lead Conversion List : <strong class="font">
                      <?=!empty($contact_lead)?$contact_lead:'0'?>
                      </strong></a></p>
                    <p><a href="javascript:void(0)" onclick="contact_search('new_contact');">New Contacts (Leads) : <strong class="font" id="new_contacts">
                      <?=!empty($new_contacts)?$new_contacts:'0'?>
                      </strong></a></p>
                  </div>
                  <br clear="all">
                  <div class="">
                    <div class="box-btn-left">Select Date-range</div>
                    <div class="box-btn-left">
                      <input type="text" class="form-control parsley-validated" id="start_date" name="start_date" value="<?=date('Y-m-d', strtotime('-30 days'))?>" readonly="readonly">
                    </div>
                    <div class="box-btn-left">
                      <input type="text" class="form-control parsley-validated" id="end_date" name="end_date" value="<?=date('Y-m-d')?>" readonly="readonly">
                    </div>
                    <div class="box-btn-left">
                      <button class="btn btn-danger howler" onclick="contact_search('new_contact');" title="Show Analytics"  data-type="danger">Show Analytics</button>
                    </div>
                  </div>
                </div>
              </div>
              <br clear="all">
              
            </div>
            
            <!--<div class="col-md-12">
              <div class="col-sm-12">
              <div class="table_large-responsive">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Phone No.</th>
                        <th>Email</th>
                        <th>Contact Status</th>
                        <th>Address</th>
                        <th>Contact Type</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>shape</td>
                        <td>Verve Systems</td>
                        <td>2065951522</td>
                        <td>Steven@smail.com</td>
                        <td>New Lead</td>
                        <td>1164 21st Ave SW Burien, WA</td>
                        <td>Buyer</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>shape</td>
                        <td></td>
                        <td>2065952678</td>
                        <td>nancy@mail.com</td>
                        <td>Prospects</td>
                        <td>11039 35th Ave SW, Seattle WA98146</td>
                        <td>Seller</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Jennifer</td>
                        <td>Kaizen Developers </td>
                        <td>2065831521</td>
                        <td>jen@kz.com</td>
                        <td>Future Prospects</td>
                        <td>6707 41st Ave SW, Seattle, WA 98136</td>
                        <td>Friend</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>
              </div>
            </div>-->
            <div class="show_graph"> </div>
            
            <div id="common_div">
              <?=$this->load->view('admin/'.$viewname.'/contact_ajax_list')?>
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
<!-- #content -->

<div aria-hidden="true" style="display: none;" id="basicModal_for" class="modal fade ">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_plan_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Export Report</h3>
      </div>
      <div class="modal-body popup_loading">
        <form method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').$viewname.'/generate_pdf'?>"  data-validate="parsley" novalidate>
          <div class="col-sm-12">
          <div class="col-sm-6">
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="1" checked="checked" >
              </div>
              Number of Leads
              </label>
            </div>
            <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){ $cheked='checked="checked"';}else{$cheked='';}?>
             <?php if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){?>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="2" <?=$cheked?>/ >
              </div>
              Leads assigned to campaigns
              </label>
            </div>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="3" <?=$cheked?>>
              </div>
              Leads not assigned to campaigns
              </label>
            </div>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="4" <?=$cheked?>>
              </div>
              No. of Emails Sent against Communications
              </label>
            </div>
            <? } ?>
            <div class="col-sm-12 clear">
              <div class="col-sm-12 col-lg-6">
                <input type="text" class="form-control parsley-validated" id="email_cp_start_date" name="email_cp_start_date" placeholder="Start Date" value="<?=date('Y-m-d', strtotime('-30 days'))?>">
              </div>
              <div class="col-sm-12 col-lg-6">
                <input type="text" class="form-control parsley-validated" id="email_cp_end_date" name="email_cp_end_date" placeholder="End Date" value="<?=date('Y-m-d')?>">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <p>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="5" checked="checked" >
              </div>
              Open Task List
              </label>
            </div>
            </p>
            <p>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="6" checked="checked" >
              </div>
              Completed Task List
              </label>
            </div>
            </p>
            <p>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="8" checked="checked" >
              </div>
              Lead Conversion List
              </label>
            </div>
            </p>
            <p>
            <div class="checkbox nopadding margin-left-20 clear">
              <label class="">
              <div class="">
                <input type="checkbox" name="chk_contact_type_id[]" value="7" checked="checked" >
              </div>
              New Contacts (Leads)
              </label>
            </div>
            </p>
            <div class="col-sm-12 clear">
              <div class="col-sm-12 col-lg-6">
                <input type="text" class="form-control parsley-validated" id="newcontacts_cp_start_date" name="newcontacts_cp_start_date" placeholder="Start Date" value="<?=date('Y-m-d', strtotime('-30 days'))?>">
              </div>
              <div class="col-sm-12 col-lg-6">
                <input type="text" class="form-control parsley-validated" id="newcontacts_cp_end_date" name="newcontacts_cp_end_date" placeholder="End Date" value="<?=date('Y-m-d')?>">
              </div>
            </div>
          </div>
          <div class="col-sm-12 text-center mrgb4 clear">
            <input type="submit" value="Export" class="btn btn-secondary export_analytic_pdf">
          </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> --> 
<script>
$(document).ready(function(){
	$("#div_msg").fadeOut(4000); 
	$(function() {
		$("#start_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false,
			onClose: function (date) {
				var selectedate = date.split('-');
				var currentdate = selectedate[2];
				var currentmonth = selectedate[1]-1;
				var currentyear = selectedate[0];
				d = new Date();
				d.setFullYear(currentyear,currentmonth,currentdate);
				d = new Date(d.getTime() + 24 * 60 * 60 * 1000 * 30);
				$('#end_date').datepicker('setDate',d);
				$('#end_date').datepicker('option', 'minDate', date);
				$('#end_date').focus();
			}
		});
		$("#end_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			minDate: '<?=date('Y-m-d', strtotime('-30 days'))?>',
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
		
		$("#email_cp_start_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false,
			onClose: function (date) {
				var selectedate = date.split('-');
				var currentdate = selectedate[2];
				var currentmonth = selectedate[1]-1;
				var currentyear = selectedate[0];
				d = new Date();
				d.setFullYear(currentyear,currentmonth,currentdate);
				d = new Date(d.getTime() + 24 * 60 * 60 * 1000 * 30);
				$('#email_cp_end_date').datepicker('setDate', d);
				$('#email_cp_end_date').datepicker('option', 'minDate', date);
				$('#email_cp_end_date').focus();
			}
		});
		$("#email_cp_end_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			minDate: '<?=date('Y-m-d', strtotime('-30 days'))?>',
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
		
		$("#newcontacts_cp_start_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false,
			onClose: function (date) {
				var selectedate = date.split('-');
				var currentdate = selectedate[2];
				var currentmonth = selectedate[1]-1;
				var currentyear = selectedate[0];
				d = new Date();
				d.setFullYear(currentyear,currentmonth,currentdate);
				d = new Date(d.getTime() + 24 * 60 * 60 * 1000 * 30);
				$('#newcontacts_cp_end_date').datepicker('setDate', d);
				$('#newcontacts_cp_end_date').datepicker('option', 'minDate', date);
				$('#newcontacts_cp_end_date').focus();
			}
		});
		$("#newcontacts_cp_end_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			minDate: '<?=date('Y-m-d', strtotime('-30 days'))?>',
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
		
		$("#sent_start_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			//minDate: "",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false,
			onClose: function (date) {
				var selectedate = date.split('-');
				var currentdate = selectedate[2];
				var currentmonth = selectedate[1]-1;
				var currentyear = selectedate[0];
				d = new Date();
				d.setFullYear(currentyear,currentmonth,currentdate);
				d = new Date(d.getTime() + 24 * 60 * 60 * 1000 * 30);
				$('#sent_end_date').datepicker('setDate', d);
				$('#sent_end_date').datepicker('option', 'minDate', date);
				$('#sent_end_date').focus();
			}
		});
		$("#sent_end_date").datepicker({
			showOn: "both",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+1",
			minDate: '<?=date('Y-m-d', strtotime('-30 days'))?>',
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
	});		
});

	function contact_search(txt,is_completed,joomla_domain)
	{
		$("#sortfield").val('');
		$("#sortby").val('');
		//$("#sent_start_date").val('');
		//$("#sent_end_date").val('');
		$('#assign_not_contact_data').val('');
		$('#assign_not_contact_data').val(txt);
		if(txt !='joomla_contact_data')
		{$('#joomla_domain_contact_data').val('');}
		if(joomla_domain !='')
		{
			$('#joomla_domain_contact_data').val(joomla_domain);
		}
		if(txt == 'task')
		{
			if(is_completed.trim() != '')
				$("#is_completed").val(is_completed);
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>/open_completed_task');
		}
		else if(txt == 'last_month_contact')
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>/last_month_contact');
		else if(txt == 'no_of_sent_email')
		{
			$('#assign_not_contact_data').val(txt);
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>/sent_email');
		}
		else if(txt == 'client_contact_lead')
		{
			$('#assign_not_contact_data').val(txt);
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>');
		}
		else
		{
			if(txt == 'new_contact')
			{
				//$(".show_graph").show();
				if($("#start_date").val().trim() == '' && $("#end_date").val().trim() == '')
				{
					var today= new Date;
					var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!
					var m = today.getMonth();
					var yyyy = today.getFullYear();
					
					todaydate = yyyy+'-'+mm+'-'+dd;
					//var m = mm.toString().length;
					if(dd.toString().length == 1)
						dd = "0"+dd;
					if(mm.toString().length == 1)
						mm = "0"+mm;
					if(m == 0)
						$("#start_date").val((yyyy-1)+'-12-'+dd);
					else if(m.toString().length == 1)
						$("#start_date").val(yyyy+'-0'+m+'-'+dd);
					else
						$("#start_date").val(yyyy+'-'+m+'-'+dd);
					$("#end_date").val(yyyy+'-'+mm+'-'+dd);
				}
			}
			/*else
			{
				$("#start_date").val('');
				$("#end_date").val('');
			}*/
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>');
		}
	}

	/*function sent_mail_search()
	{
		if($("#sent_start_date").val().trim() != '' && $("#sent_end_date").val().trim() != '')
			sanjaytest('<?php echo base_url();?>admin/<?=$viewname?>/sent_email');
		return false;
		
	}*/
	
	function changepages()
	{
		contact_search($('#assign_not_contact_data').val(),'');
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search($('#assign_not_contact_data').val());
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		   sanjaytest($(this).attr('href'));
		   return false;
    });
		
	function sanjaytest(url)
	{
		$("#current_url").val(url);
		 $.ajax({
			type: "POST",
			url: url,
			data: {
			result_type:'ajax',perpage:$("#perpage").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),assign_contact_data:$('#assign_not_contact_data').val(),joomla_domain_contact_data:$('#joomla_domain_contact_data').val(),is_completed:$("#is_completed").val(),date1:$("#start_date").val(),date2:$("#end_date").val(),date3:$("#sent_start_date").val(),date4:$("#sent_end_date").val()
		},
		beforeSend: function() {
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				  },
			success: function(html){
				$("#common_div").html(html);
				$.unblockUI();
				if($('#assign_not_contact_data').val() == 'no_of_sent_email')
					$("#sent_email").html($("#sent_email_count").val());
			   	if($('#assign_not_contact_data').val() == 'new_contact')
				{
					$("#new_contacts").html($("#new_contact_count").val());
					
					$.ajax({
					type: "POST",
					url: '<?php echo base_url();?>admin/<?=$viewname?>/graph',
					data: {
					result_type:'ajax',date1:$("#start_date").val(),date2:$("#end_date").val()
				  },
					success: function(html){
						$(".show_graph").html(html);
					}
					});
				}
				else
					$(".show_graph").html('');
			}
		});
		return false;
	}
/*$(".export_analytic_pdf").click(function(){
	$('.popup_loading').block({ message: 'Loading...' });
	//$('.popup_loading').unblock(); 
});*/
</script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>exporting.js"></script> 
<script type="text/javascript" src="<?=$this->config->item('js_path')?>highcharts.js"></script>