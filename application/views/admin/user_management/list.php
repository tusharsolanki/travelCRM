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
   <h1><?=$this->lang->line('user_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('user_list_head');?></h3>
       		 <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>  
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
                <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
            </label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
          <div class="col-sm-6">
          <?php if(!empty($this->modules_unique_name) && in_array('user_management_delete',$this->modules_unique_name)){?>
		  	<button class="btn btn-danger howler" data-type="danger" onclick="active_plan('1');" title="Add to Archive List">Add to Archive List</button>
            <? } ?>
           <!--<button class="btn btn-danger howler" data-type="danger" onclick="deletepopup1('0');">Delete User</button>-->
          </div>
          <div class="col-sm-6">
		<?php /*?>  <?php if(isset($remain_user[0]['number_of_users_allowed']) && isset($count_user[0]['total_count']) && $remain_user[0]['number_of_users_allowed'] > $count_user[0]['total_count']) { ?><?php */?>
         <?php if(!empty($this->modules_unique_name) && in_array('user_management_add',$this->modules_unique_name)){?>
          <? if(!empty($admin_list)) {?>
          <? /* if(!empty($assignright_superadmin)) {?>
         <a title="Add Admin Rights" class="btn pull-right btn-secondary-green howler margin-left-5px" href="<?=base_url('admin/'.$viewname.'/admin_rights');?>">Add  Rights</a>
         <? } */ } ?>
          <a class="btn  pull-right btn-secondary-green howler margin-left-5px" title="Add User" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add User</a>
          <? } ?>
		<?php /*?>  <?php } ?><?php */?>
         <?php if(!empty($this->modules_unique_name) && in_array('user_management_delete',$this->modules_unique_name)){?>
          <a class="btn  pull-right btn-success howler" title="View Archive" href="<?=base_url('admin/'.$viewname.'/2');?>">View Archive</a>
          <? } ?>
		  </div>
         </div>
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
	function export_confirm()
		{      
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export user(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('admin/'.$viewname.'/export');?>';
			 window.location= url;
			}},'No'	: {'class'	: 'special'}}});
} 
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(8000); 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/user_management/1/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
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
					url: "<?php echo base_url();?>admin/user_management/"+data,
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

function pubunpub_data(count1,id)
{
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_record/"+id;
		html = '<a onclick="pubunpub_data(0,'+id+');" href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-check-circle"></i></a> &nbsp;';
		html1 = 'Active';
	}
	else
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_record/"+id;
		html = '<a onclick="pubunpub_data(1,'+id+');" href="javascript:void(0);" class="btn btn-xs btn-primary"><i class="fa fa-times-circle"></i></a> &nbsp;';
		html1 = 'Inactive';
	}
	$.ajax({
			type: "POST",
			url :url,
			async: false,
			success: function(data){
			
				$(".pubunpub_span_"+id).html(html);
				$(".status_span_"+id).html(html1);
			
			}
	});
}
function active_plan(name)
	{      
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0')
			{
			$.confirm({'title': 'Alert','message': " <strong> Please select users "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
			//alert('Select Contacts');
			return false;}
			else
			{
				$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							active_all_plans();
						}},'No'	: {'class'	: 'special'}}});
				
			}
	} 
	function single_active_plans(name,id)
	{
		if(id.length > 50)
			{
				var msg = unescape(id).substr(0, 50)+'...';
			}
			else
			{
				var msg = unescape(id);
			}
				
		$.confirm({'title': 'CONFIRM','message': ' <strong> Are you sure want to archive "'+msg+'"'+'<strong>?</strong>','buttons': {'Yes': {'class': '',
	   'action': function(){
							active_all_plans(name);
						}},'No'	: {'class'	: 'special'}}});
	}
	
	function active_all_plans(name)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
			
			if(name != '0')
			{
				var single_active_id = name;
			}
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_Inactive_all';?>",
			dataType: 'json',
			//async: false,
			data: {'myarray':myarray,'single_active_id':name},
				beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
			success: function(data){
					$.unblockUI();
				$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('admin_base_url').$viewname.'/1/';?>"+data,
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

</script>