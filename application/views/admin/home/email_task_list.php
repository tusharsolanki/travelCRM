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
$viewname = $this->router->uri->segments[3];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
<div aria-hidden="true" style="display: none;" id="template_details" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h3 class="modal-title">Email Message</h3>
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
       <h3> <i class="fa fa-table"></i>Scheduled Email Tasks</h3>
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
         <?=$this->load->view('admin/home/email_ajax_list')?>
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
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search(allflag)
	{
            var uri_segment = $("#uri_segment").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/dashboard/<?=$viewname?>/"+uri_segment,
			data: {
			result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_5").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_5").val(),sortby:$("#sortby_5").val(),allflag:allflag
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
		$("#sortfield_5").val(sortfilter);
		$("#sortby_5").val(sorttype);
		contact_search('changesorting');
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_5").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_5").val(),sortby:$("#sortby_5").val()
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
         $('.mycheckbox_5').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox_5').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check_5[]"]:checked');
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
			url: "<?php echo $this->config->item('admin_base_url').'dashboard/ajax_delete_task';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id,interaction_type:'email'},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/dashboard/<?=$viewname?>/"+data,
					data: {
					result_type:'ajax',perpage:$("#perpage_5").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_5").val(),sortby:$("#sortby_5").val(),allflag:''
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
			var boxes = $('input[name="check_5[]"]:checked');
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
				var msg = 'Are you sure want to delete "'+unescape(name) +'"';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
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

function resend_mail(id,contact_id)
{
	var uri_segment = '<?=$this->uri->segment(4)?>';
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('admin_base_url').'dashboard/send_mail/';?>",
		//async: false,
		data: {'single_id':id,contact_id:contact_id,uri_segment:uri_segment},
		beforeSend: function() {
			$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
		success: function(result){
			$.unblockUI();
		//alert(result);
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').'dashboard/'.$viewname;?>/"+result,
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage_5").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_5").val(),sortby:$("#sortby_5").val()
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