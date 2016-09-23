<?php
	/*
        @Description: user lead list
        @Author: Mohit Trivedi
        @Date: 18-09-14
    */
?>	
<?php
$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
$form_id=$this->router->uri->segments[3];
?>
<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
		$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
	$(document).ready(function(){
		$.unblockUI();
	});
</script>

<style>
	.ui-multiselect{width:100% !important;}
	input:focus{cursor:auto;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<div id="content">
  <div id="content-header">
    <h1>
      <?= ucwords($datalist[0]['form_title'])?>:Leads
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
              <?= ucwords($datalist[0]['form_title'])?>:Leads
            </h3>
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
          <div id="temp_dev" class="col-sm-12 text-center error"></div>
         </div>

         <div class="row dt-rt">
          <div class="col-sm-1">
           
          </div>
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search...">
			 <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
			 <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
            </label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
         <div class="col-sm-10">
		  	<div class="row">
		  	 		<div class="col-sm-4 col-md-3">
           			<button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Lead">Delete Lead</button>				</div>
					<div class="col-sm-8">
				<label class="margin-top-5px col-sm-7 col-md-5 col-xs-12"><?=$this->lang->line('user_assign_msg');?></label>
                 <div class="col-sm-5 row">
				<select class="form-control col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per" name="slt_user_type[]" id="slt_user_type">
				   	
				   	<?php if(!empty($userlist)){
						
							foreach($userlist as $row){
								$email = !empty($row['email_id'])?' ('.$row['email_id'].')':''?>
								<option value="<?=$row['id']?>"><?=ucfirst(strtolower($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email));?></option>
							<?php } ?>
				   <?php } ?>
				  </select>
                   </div>
			<button class="btn btn-success col-sm-2 col-lg-2 col-md-4 col-xs-4 howler margin_left_10px" data-type="danger" title="Assign Contacts" onclick="check_assign_contact();">Assign</button>		
				</div>
				</div>
			</div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/ajax_list')?>
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
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
</div>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/lead_capturing_view/<?php echo $this->router->uri->segments[3];?>",
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
				
				if (event.keyCode == 13) {
						contact_search();
				}
		  });
	});
	
	function clearfilter_contact()
	{
		$("#searchtext").val("");
		contact_search();
	}
	
	function changepages()
	{
		contact_search();	
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search();
	}
	
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
//delete all lead 

	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
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
					var msg = 'Are you sure want to delete  "'+name+'"';
				 }
				else
				{
					var msg = 'Are you sure want to delete  "'+unescape(name)+'"';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+""+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 


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
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/lead_capturing_view/<?php echo $this->router->uri->segments[3];?>",
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
	function check_assign_contact()
	{      
			
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type').val();
			if(boxes.length == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select lead "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;
			}
			else if(user_id == '')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#slt_user_type').focus();
				return false;
			}
			else
			{
				assign_contact();
			}
						
	} 

	function assign_contact()
	{      
		var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
		var user_id = $('#slt_user_type').val();
			
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/assign_lead';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'user_id':user_id,'form_id':<?=$form_id?>},
			success: function(data_temp){
				//alert(data_temp);
				if(data_temp.msg == '1')
				{
				$("#temp_dev").css('display','block');
					$("#temp_dev").html('Lead(s) assigned successfully.');
					$("#temp_dev").fadeOut(4000);
				}
				else
				{
					$("#temp_dev").css('display','block');
					$("#temp_dev").html('Lead(s) are already assigned to another user.');
					$("#temp_dev").fadeOut(4000);
				}
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/lead_capturing_view/<?php echo $this->router->uri->segments[3];?>/"+data_temp.page,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
</script>
<div aria-hidden="true" style="display: none;" id="basicModal2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Assign Leads</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_embedform_popup1 text-center">
        <form class="form parsley-form1" enctype="multipart/form-data" name="assigncontact" id="assigncontact" method="post" data-validate="parsley" accept-charset="utf-8" action="<?= $this->config->item('user_base_url')?><?php echo $viewname.'/assign_contact/'.$form_id;?>" novalidate>
		<div id="row_data">
        <div class="col-sm-12">
        	<div class="col-sm-4">
    	        <label for="text-input">Assign Lead To:</label>
            </div>
            <div class="col-sm-8">
	            <select name="user" id="user" class="selectBox"><option value='-1'>User</option></select>
            </div>
         </div><br /><br />
         </div>
         <input type="hidden" name="formid"  value="<?=$form_id?>" /> 
         <input type="hidden" name="leadid"  id='id' value="" />
      <input type="submit" class="btn btn-secondary" title="Assign" value="Assign" onclick="return select_box();" name="submitbtn1" />
		</form>
        <div id="previewformdata">
        
		</div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
 <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade merge_popup_main_div">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Lead Details</h3>
      </div>
      <div class="modal-body lead_details">
          <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		  </div>
		  
		  <?php /*?><?php $this->load->view('admin/interaction_plans/view_contact_popup');?><?php */?>
		  
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	$("select#user").multiselect({
		 multiple: false,
		 header: "Select User",
		 noneSelectedText: "Select-User",
		 selectedList: 1
	}).multiselectfilter();	
	
	$('body').on('click','.view_form_btn',function(e){
		
			formid = $(this).attr('data-id');
			$("#id").val(formid);
					$.ajax({
			dataType: "json",
			url: "<?php echo $this->config->item('user_base_url').$viewname.'/user_list';?>",
			data: {'id':formid},
			success: function(result){
					 $("#user").html("");
  				$.each(result,function(i,item){ 
							var option = $('<option />');
							if(item.email_id == null)
							{
								var email_id = '';
							}
							else
							{
							var email_id = '('+item.email_id+')'
							}
							option.attr('value', item.id).text(item.first_name+' '+item.middle_name+' '+item.last_name+' '+email_id);
							$('#user').append(option);
					});
			$("select#user").multiselect('refresh').multiselectfilter();
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	$(".view_embedform_popup1").html('Something went wrong.');
			}
			});
	});
	

</script>
<script type="text/javascript">
function contact_details(id)
{
	$.ajax({
		type: "POST",
		url:"<?php echo $this->config->item('user_base_url').$viewname.'/contact_details';?>",
		data:{lead_id:id},
		beforeSend: function() {
						$(".merge_popup_main_div .lead_details").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
					  },
		success: function(html){
			$(".lead_details").html(html);
		}
	});
}

function select_box()
{
	var abc = $("#user").val();
	if(abc > 0)
	{
		$('.parsley-form1').submit();
	}
	else
	{
		$.confirm({'title': 'Alert','message': "<strong> Please select atleast one user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;

	}
}
</script>
