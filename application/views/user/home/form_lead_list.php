<?php 
/*
    @Description: user Dashborad task list
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
$viewname = $this->router->uri->segments[3];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>
 <div id="content">
  <div id="content-header">
   <h1>Form Lead List</h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Form Lead List</h3>
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
           <!-- <button class="btn btn-danger howler" data-type="danger" title="Delete Task" onclick="deletepopup1('0');">Delete Task</button> -->
          </div>
          <div class="col-sm-6">
          </div>
         </div>
         <div id="common_div">
         <?=$this->load->view('user/home/form_lead_ajax_list')?>
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
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
	function export_confirm()
		{      
			$.confirm({'title': 'CONFIRM','message': " <strong> Would you like to export contact(s) to CSV "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
			var url='<?=base_url('user/'.$viewname.'/export');?>';
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
			url: "<?php echo base_url();?>user/dashboard/<?=$viewname?>/"+uri_segment,
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
			url: "<?php echo $this->config->item('user_base_url').'task/ajax_delete_all1';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>user/dashboard/daily_task/"+data,
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
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete '+unescape(name)+'?';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong></strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 

function pubunpub_data(count1,id)
{
	
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('user_base_url').$viewname; ?>/publish_record/"+id;
		html = '<a onclick="pubunpub_data(0,'+id+');" href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-check-circle"></i></a> &nbsp;';
		html1 = 'Active';
	}
	else
	{
		url = "<?php echo  $this->config->item('user_base_url').$viewname; ?>/unpublish_record/"+id;
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
function contact_details(id)
{
	$.ajax({
		type: "POST",
		url:"<?php echo $this->config->item('user_base_url').'lead_capturing_view/contact_details';?>",
		data:{lead_id:id},
		beforeSend: function() {
						$(".merge_popup_main_div .lead_details").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
					  },
		success: function(html){
			$(".lead_details").html(html);
		}
	});
}
</script>