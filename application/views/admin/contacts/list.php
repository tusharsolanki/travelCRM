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

<style>
.height_fix{ height:350px;}
.ui-multiselect{width:96% !important; }
.change-width{
  width: 50% !important;
  float:left;
}
.ui-multiselect-menu{width:22% !important; }
.smart-drip-plan-con-box{  height: 275px !important;}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
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
<div id="content">
  <div id="content-header">
    <h1>
      <?=$this->lang->line('contact_header');?>
    </h1>
  </div>
  <div id="content-container">
    <div class="">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-table"></i>
              <?=$this->lang->line('Contact_list_head');?>
            </h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
              <div class="row dt-rt">
                <?php if(!empty($msg)){?>
                <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div>
                <?php } ?>
                <div id="temp_dev" class="col-sm-12 text-center error"></div>
                <div class="col-sm-12 col-lg-7 col-md-12">
                  <ul class="contact_add">
                  <?php if(!empty($this->modules_unique_name) && in_array('import_contacts',$this->modules_unique_name)){?>
                    <li><a class="btn btn-xs" title="Import Contacts" href="<?=base_url('admin/'.$viewname.'/import');?>"><i class="fa fa-level-down"></i> &nbsp;Import Contacts</a></li>
                    <?php } ?>
                    <li><a class="btn btn-xs" title="Export Contacts" onclick="return export_confirm();"><i class="fa fa-sign-out"></i> &nbsp;Export Contacts</a></li>
                    <li><a class="btn btn-xs" title="Merge Duplicate Contacts" href="<?=base_url('admin/'.$viewname.'/merge_duplicate_contacts');?>"><i class="fa fa-exchange"></i> &nbsp;Merge Duplicate Contacts</a></li>
                  </ul>
                </div>
                <div class="col-lg-5 col-sm-12 col-xs-12">
                  <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                      <div class="change-width">
                      <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                      <select class="form-control parsley-validated" name="slt_cont_status[]" id="slt_cont_status" multiple="multiple">
                           <!--<option value="">Please Select</option>-->
                           <?php if(!empty($status_type)){
                           
                                    foreach($status_type as $row){
                    $name = !empty($row['name'])?"(".$row['name'].")":'' ?>
                                        <option value="<?=$row['id']?>">
                                        <?=ucwords($row['name'].$name);?><?=!empty($row['name'])?'('.$row['name'].')':''?>
                                        </option>
                                    <?php } ?>
                           <?php } ?>
                        </select>
                      </div>
                      <input class="" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?htmlentities($searchtext):''?>" />
                      <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search Contacts">Search</button>
                      <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All Contacts">View All</button>
                    </label>
                  </div>
                </div>
              </div>
              <!--<div class="row dt-rt">
          
          <div class="col-sm-11">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
             <input class="col-xs-7" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search...">
			 <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();">Search</button>
            </label>
           </div>
          </div>
         </div>-->
              <div class="row dt-rt">
                <div class="col-sm-12 col-lg-12">
                  <div class="row"> 
                    <!--<div class="col-sm-4 col-md-4">
           				<button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Contacts">Delete Contacts</button>				
                        <button class="btn btn-danger howler" data-type="danger" onclick="archivecontactdata('0');" title="Archive Contacts">Add to Archive</button>				
                    </div>-->
                    
                    <div class="col-lg-5 col-md-5 col-sm-5 col-sm-12">
                     <?php if(!empty($this->modules_unique_name) && in_array('contact_delete',$this->modules_unique_name)){?>
                      <button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');" title="Delete Contacts">Delete Contacts</button>
                      <button class="btn btn-danger howler" data-type="danger" onclick="archivecontactdata('0');" title="Archive Contacts">Add to Archive</button>
                       <? } ?>
                      <!--<br />
                    <label id="cnt_selected">0 Record Selected</label> | 
                    <a class="text_color_red text_size add_email_address" onclick="remove_selection();" title="Remove Selected" href="javascript:void(0);">Remove Selection</a>--> 
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-sm-12 pull-right"> 
                    <?php if(!empty($this->modules_unique_name) && in_array('contact_delete',$this->modules_unique_name)){?>
                    	<a class="btn  pull-right btn-success howler margin-left-5px" href="<?=base_url('admin/'.$viewname.'/view_archive');?>" title="Add Contact">Archived Contacts</a> 
                        <? } ?>
                        <?php if(!empty($this->modules_unique_name) && in_array('contact_add',$this->modules_unique_name)){?>
                        <a class="btn  pull-right btn-secondary-green howler" href="<?=base_url('admin/'.$viewname.'/add_record');?>" title="Add Contact">Add Contact</a>
                        <? } ?>
                        </div>
                    
                  </div>
                </div>
                
                <div class="col-sm-12 clear">
                  <div class="row">
                    <div class="col-sm-7 col-md-5 col-lg-3 mar-10">
                      <label id="cnt_selected">0 Record Selected</label>
                      | 
                      <!--<input type="button" class="btn  pull-right btn-success howler" onclick="remove_selection();" value="Remove Selected" />--> 
                      <a class="text_color_red text_size add_email_address" onclick="remove_selection();" title="Remove Selected" href="javascript:void(0);">Remove Selection</a> </div>
                      <div class="col-sm-11 col-lg-8 col-md-7 margin-top-5px">
                      <label class="margin-top-5px col-sm-5 col-md-6 col-lg-4 col-xs-12 text-aline">
                        <?=$this->lang->line('user_assign_msg_agent');?>
                      </label>
                      <div class="col-sm-5 col-md-3 col-lg-4 row">
                        <!--<select class="form-control col-sm-5 col-md-5 col-lg-3 col-xs-7 parsley-validated margin-left-5px width20-per" name="slt_user_type[]" id="slt_user_type">
                          <option value="">Users</option>
                          <?php if(!empty($user_list)){
						
							foreach($user_list as $row){
								$email = !empty($row['email_id'])?"(".$row['email_id'].")":'' ?>
                          <option value="<?=$row['id']?>">
                          <?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email);?>
                          </option>
                          <?php } ?>
                          <?php } ?>
                        </select>-->
                        
                        <select class="form-control parsley-validated" name="slt_user_type[]" id="slt_user_type" multiple="multiple">
                           <!--<option value="">Please Select</option>-->
                           <?php if(!empty($user_list)){
                                    foreach($user_list as $row){
										$email = !empty($row['email_id'])?"(".$row['email_id'].")":'' ?>
                                        <option value="<?=$row['id']?>">
                                        <?=ucwords($row['first_name']." ".$row['middle_name']." ".$row['last_name'].$email);?><?=!empty($row['agent_type'])?'('.$row['agent_type'].')':''?>
                                        </option>
                                    <?php } ?>
                           <?php } ?>
                      	</select>
                      </div>
                      <button class="btn btn-success col-sm-2 col-lg-2 col-md-3 col-xs-4 howler margin_left_10px" data-type="danger" title="Assign Contacts" onclick="check_assign_contact();">Assign</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="table_large-responsive">
                <div id="common_div">
                  <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
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
<!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> --> 
<script>
$("select#slt_user_type").multiselect({
		header: "User",
		noneSelectedText: "Users",
		selectedList: 0
}).multiselectfilter();	

$("select#slt_cont_status").multiselect({
    header: "status",
    noneSelectedText: "status",
    selectedList: 0
}).multiselectfilter(); 


	function export_confirm()
		{      
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export contact(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('admin/'.$viewname.'/export_contact');?>';
			$.ajax({
				type: "POST",
				url: url,
				data: {
				myarray:popupcontactlist,searchtext:$("#searchtext").val()
			},
				success: function(html){
					url = '<?=base_url('admin/'.$viewname.'/export');?>';
					window.location = url;
					//$("#common_div").html(html);
				}
			});
			return false;
			//var url='<?=base_url('admin/'.$viewname.'/export');?>';
			//window.location = url;
			}},'No'	: {'class'	: 'special'}}});
} 
</script> 
<script>
var arraydatacount = 0;
var popupcontactlist = Array();

function remove_selection()
{
	var cnt = popupcontactlist.length;
	for(i=0;i<popupcontactlist.length;i++)
	{
		$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',false);
	}
	$('#selecctall').attr('checked',false);
	popupcontactlist = Array();
	$("#cnt_selected").text("0 Record Selected");
	arraydatacount = 0;
	
}

    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
	 $("#temp_dev").css('display','none'); 
	 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
           /* var myarray = new Array;
            var i=0;
            var boxes = $('input[name="check[]"]:checked');
            $(boxes).each(function(){
               myarray[i]=this.value;
               i++;
           });*/
      var status_id = $('#slt_cont_status').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/contacts/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,status_id:status_id
		},
		beforeSend: function() {
					$('#common_div').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				$("#common_div").html(html);
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
					}
				}
				catch(e){}
				$("#cnt_selected").text(popupcontactlist.length + " Record Selected");
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
		$("#searchtext").val("");
     $("select#slt_cont_status").multiselect("uncheckAll");
      $("select#slt_cont_status").multiselect('refresh').multiselectfilter();
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
					//alert(JSON.stringify(popupcontactlist));
					try
					{
						for(i=0;i<popupcontactlist.length;i++)
						{
							$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
						}
					}
					catch(e){}
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
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = parseInt(this.value);
				}             
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex >= 0)
				{
					popupcontactlist.splice( arrayindex, 1 );
					arraydatacount--;
				}
				
            });        
        }
		$("#cnt_selected").text(popupcontactlist.length + " Record Selected");
   		/*if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }*/
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
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			data: {'myarray':popupcontactlist,'single_remove_id':id},
			success: function(data){
			popupcontactlist = Array();
			$("#cnt_selected").text(popupcontactlist.length + " Record Selected");
			$.unblockUI();
			
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/contacts/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#cnt_selected").text("0 Record Selected");
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
	function check_assign_contact()
	{      
			var boxes = $('input[name="check[]"]:checked');
			var user_id = $('#slt_user_type').val();
			
			if(popupcontactlist.length == '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to assign. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
				$('#selecctall').focus();
				return false;
			}
			else if(user_id == '' || user_id == null)
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select User')
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
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/assign_contact';?>",
			//dataType: 'json',
			//async: false,
			data: {'myarray':popupcontactlist,'user_id':user_id},
			beforeSend: function() {
				$('#common_div').block({ message: 'Loading...' }); 
			},
			success: function(data_temp){
				$("#cnt_selected").text("0 Record Selected");
				arraydatacount = 0;
				popupcontactlist = Array();
				$("#slt_user_type").multiselect("uncheckAll");
				if(data_temp.msg == '1')
				{
				$("#temp_dev").css('display','block');
					$("#temp_dev").html('Contact(s) assigned successfully.');
					$("#temp_dev").fadeOut(4000);

				}
				/*else
				{
					$("#temp_dev").css('display','block');
					$("#temp_dev").html('Contact(s) are already assigned to another user.');
					$("#temp_dev").fadeOut(4000);
				}*/
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/contacts/",
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
	
	function deletepopup1(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(popupcontactlist.length == '0' && id== '0')
			{
				
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
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
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name +'"';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+unescape(msg)+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
	   						
							delete_all(id);
							
							$("#cnt_selected").text("0 Record Selected");
							arraydatacount = 0;
							popupcontactlist = Array();
						}},'No'	: {'class'	: 'special'}}});
	}
	
	function archivecontactdata(id,name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(popupcontactlist.length == '0' && id== '0')
			{
				
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to archive. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				//alert('Please Select Contacts')
				$('#selecctall').focus();
				return false;
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to archive record(s)';
			}
			else
			{
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to archive "'+unescape(name)+'"';
				//var msg = 'Are you sure want to archive '+name;
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							archive_all(id);
							$("#cnt_selected").text("0 Record Selected");
							arraydatacount = 0;
							popupcontactlist = Array();

						}},'No'	: {'class'	: 'special'}}});
	} 
	
	function archive_all(id)
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
                        var uri_segment = $("#uri_segment").val();
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_archive_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':popupcontactlist,'single_remove_id':id},
			beforeSend: function() {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		  },
			success: function(data){
				popupcontactlist = Array();
				$("#cnt_selected").text(popupcontactlist.length + " Record Selected");
				$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/contacts/"+data,
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
	
	$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$("#cnt_selected").text(popupcontactlist.length + " Record Selected");
		
	});

</script> 
