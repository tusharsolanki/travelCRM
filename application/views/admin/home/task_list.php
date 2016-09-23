<?php 
/*
    @Description: Admin Dashborad task list
    @Author     : Sanjay Moghariya
    @Date       : 22-10-14
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

<div aria-hidden="true" style="display: none;" id="template_details" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Task View</h3>
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
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('dashboard_task_list_head');?></h3>
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
                <input type="text" name="searchtext" id="searchtext" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
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
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('admin/home/task_ajax_list')?>
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
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export contact(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('admin/'.$viewname.'/export');?>';
			 window.location= url;
			}},'No'	: {'class'	: 'special'}}});
} 
</script>
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/dashboard/daily_task/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_4").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_4").val(),sortby:$("#sortby_4").val(),allflag:allflag
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
		$("#sortfield_4").val(sortfilter);
		$("#sortby_4").val(sorttype);
		contact_search('changesorting');
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_4").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_4").val(),sortby:$("#sortby_4").val()
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
         $('.mycheckbox_4').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox_4').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check_4[]"]:checked');
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
			url: "<?php echo $this->config->item('admin_base_url').'task/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/dashboard/daily_task/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_4").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_4").val(),sortby:$("#sortby_4").val(),allflag:''
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
			var boxes = $('input[name="check_4[]"]:checked');
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
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+unescape(name)+'"';
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
		$('#row_data').html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif"></div>');
		var id = $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "<?=$this->config->item('admin_base_url')?>dashboard/view_records",
			data: {id:id},
			success: function(html){
				$("#row_data").html(html);
			}
		});
	
	});

function iscompleted(value)
{
	$.confirm({'title': 'CONFIRM','message': " <strong>Are you sure that the task is completed"+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
	$.ajax({
			type: "POST",
			url: '<?php echo base_url("admin/dashboard/iscompleted");?>',
			data: {
			selectedvalue:value
		},
		beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },

			success: function(html){
				
			$.ajax({
                type: "POST",
                url: '<?=base_url()?>admin/dashboard/daily_task/'+html,
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_4").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_4").val(),sortby:$("#sortby_4").val()
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
		$('.complted_task_checkbox:checkbox[value='+parseInt(value)+']').attr('checked',false);
	}
	}}});
		return false;
	
}

</script>