<?php $viewname = $this->router->uri->segments[2];?>
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content mian_box">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Profile Picture<span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body pading_zero">
			<img class="img-responsive profile_padding" src="<?=$this->config->item('user_upload_img_big')?>/<?=(!empty($prifile_pic[0]['contact_pic'])?$prifile_pic[0]['contact_pic']:'');?>"/>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_error_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Error List</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_error_popup">
          
      <div class="text-center">
        <img src="<?=base_url()?>images/ajaxloader.gif" />
      </div>
      
      <?php /*?><?php $this->load->view('user/interaction_plans/view_error_popup');?><?php */?>
      
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="overlay">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Dashboard Notification</h4>
							  </div>
							  <div class="modal-body">
							  
							<table width="100%" class="table1 table-striped1 table-striped2 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
							<thead>
							  <tr role="row">
								<th width="7%" aria-label="CSS grade" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Task Notification</th>
							  </tr>
							</thead>
							<tr class="load_conversations">
							  <td width="50%">
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    
								<?php 
								if(!empty($task_name_popup) || !empty($user_name_own))
								{
									
									for($j=0;$j < count($task_name_popup);$j++)
									{
										if($is_close[$j] == '0' || $close_popup[$j]=='0')
										{
										?>
										<tr class="load_conversations">
											  <td width="50%">
												<table width="100%"  border="0" cellspacing="0" cellpadding="0">	
													<tr>
														<td width="30%" colspan="3"><b>User :  </b><?php if(!empty($user_name_popup[$j])){echo ucwords($user_name_popup[$j]);}else{{echo ucwords($user_name_own[$j]);}} ?></td>
													</tr>
													<tr>
														<td colspan="2"><b>Task Date : </b>
														<?php echo date($this->config->item('common_date_format'),strtotime($task_data[$j]));?></td>
													</tr>
													<tr>
																<td colspan="2"><?php echo $task_name_popup[$j]; ?></td>
													</tr>
												</table>
											</td>
										</tr>
										<input type="hidden" id="task_id" name="task_id[]" value="<?php echo $created_by[$j]; ?>" />
						<input type="hidden" id="popup_id" name="popup_id" value="<?php echo $is_close[$j]; ?>" />
						<input type="hidden" id="popup_id1" name="popup_id1" value="<?php echo $close_popup[$j]; ?>" />
						<input type="hidden" id="trans_id" name="trans_id[]" value="<?php echo $trans_id[$j]; ?>" />
						
												
										<?php 
								}}
							} ?>	
							
								
							<?php 
							
							if(!empty($calendar_name_popup))
							{
								for($j=0;$j < count($calendar_name_popup);$j++)
								{
									if($calendar_is_close[$j] == '0')
									{
									?>
									<tr class="load_conversations">
										  <td width="50%">
											<table width="100%"  border="0" cellspacing="0" cellpadding="0">	
												<tr>
													<td width="30%" colspan="3"><b>User :  </b>
													
													<?php $calendar_user_name = $calendar_user_name_popup[$j].", ".$calendar_admin_name_popup[$j];
												
														//$address=str_replace(', ',', ',$user_name);
														$letters = array(', , , , ,',', , , ,',', , , ',', , ');
														$fruit   = array(',',',',',',',');
														//$text    = $address;
														$output  = str_replace($letters, $fruit, $calendar_user_name);
														$output = ltrim($output,",");
														$output = rtrim($output,",");
														echo ucwords($output);
												?>
												</td>
												</tr>
												<tr>
													<td width="30%"><b>Calendar Event Date</b></td>
													
													<td width="70%"><?php echo date($this->config->item('common_date_format'),strtotime($calendar_data[$j])); ?></td>
												</tr>
												<tr>
															<td colspan="2"><?php echo $calendar_name_popup[$j]; ?></td>
												</tr>
												
											</table>
										</td>
									</tr>
			
									<input type="hidden" id="calendar_id[]" name="calendar_id[]" value="<?php echo $calendar_id[$j]; ?>" />
										
									<input type="hidden" id="calendar_popup_id" name="calendar_popup_id" value="<?php echo $calendar_is_close[$j]; ?>" />
											
									<?php 
							}}
						} ?>			
								 </table>
                  			</td>
                		</tr>
							
              	</table>
				<div class="col-sm-12 text-center mrgb4">
				
				
        <input type="submit" value="Close Task Notification" class="btn btn-secondary" title="Close Task Notification" onclick="close_popup(1);">
		<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Snooze</button>
        
      </div>
		 </div>
	</div>
  </div>
</div>
<div id="content">
  <div id="content-header">
   <h1>Dashboard</h1>
  </div>
  <!-- #content-header --> 
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('common_label_dashboard');?></h3>
	   <span class="pull-right">
	  <?php /* <a title="Previous" href="#" onclick="next_and_pre(-1);"><i class="fa fa-backward btn btn-prev-next-avail arrow"></i></a>
	   <label id="now_date"><?php if(!empty($now_date)){ echo date($this->config->item('common_date_format'),strtotime($now_date));} ?> </label>
		<a title="Next" href="#" onclick="next_and_pre(1);"><i class="fa fa-forward btn btn-prev-next-avail arrow"></i></a>
	*/	?>
		 </span> 
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
			<div class="table-responsive">
            	<div class="table-in-responsive">
          
          <div id="common_div">
		
         <?=$this->load->view('user/home/ajax_list')?>
         </div>
		 
      								 
        </div>
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
  <!-- /#content-container --> 
  
 </div>
 									
 <!-- #content --> 
 
 <script language="javascript">
 $( document ).ready(function(){
$("#div_msg").fadeOut(4000);
var popup_id = $('#popup_id').val();
var popup_id1 = $('#popup_id1').val();
var popup_id2 = $('#calendar_popup_id').val();

if(popup_id == '0' || popup_id1== '0' || popup_id2 == '0')
{
 $('#overlay').modal('show');
}

setTimeout(function() {
    $('#overlay').modal('hide');
}, 30000);
 });
 function next_and_pre(date_val)
 {
 	var current = $('#hidden_date').val();
	//alert(current);
	if(date_val == '1')
	{
		var date = new Date(current);
		date.setDate(date.getDate() + 1);
		date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
	}
	else
	{
		var date = new Date(current);
		date.setDate(date.getDate() - 1);
		date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
	}
	//alert(date);
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('base_url').'user/dashboard/';?>",
			data: {result_type:'ajax','date':date},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					},
			success: function(data){
				
				$("#common_div").html(data);
				$('#common_div').unblock(); 
				var next = $('#hidden_date').val();
				//$('#now_date').text(next);

		return false;
			}
		});
 
 }
 function close_popup(id)
 {
 	
	
	var myarray = new Array;
			var i=0;
			var boxes = $('input[name="task_id[]"]');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
					i++;
			});
			
			
	var myarray1 = new Array;
			var j=0;
			var boxes1 = $('input[name="trans_id[]"]');
			$(boxes1).each(function(){
  				  myarray1[j]=this.value;
					j++;
			});
	var k=0;		
	var myarray_cal = new Array;		
	var boxes_cal = $('input[name="calendar_id[]"]');
			$(boxes_cal).each(function(){
  				  myarray_cal[k]=this.value;
					k++;
			});
			
			
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('base_url').'user/dashboard/popup_changes';?>",
			data: {result_type:'ajax','myarray':myarray,'myarray1':myarray1,'id':id,'myarray_cal':myarray_cal},
			success: function(data){
				
				$('.modal-header .close').trigger('click');

		return false;
			}
		});
 }
 
 </script>
 <script>
 function contact_search(allflag)
  {
            var uri_segment = $("#uri_segment").val();
    $.ajax({
      type: "POST",
      url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_error_data/';?>"+uri_segment,
      
      data: {
      result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
    },
    beforeSend: function() {
          $('#common_div').block({ message: 'Loading...' }); 
          },
      success: function(html){
        $("#error_div").html(html);
        $('#error_div').unblock(); 
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
  $('body').on('click','.view_error_btn',function(e){
      $(".view_error_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
  
      planid = $(this).attr('data-id');
      
      $.ajax({
      type: "POST",
      url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_error_data';?>",
      data: {'interaction_plan':planid},
      success: function(html){
        $(".view_error_popup").html(html);  
      },
      error: function(jqXHR, textStatus, errorThrown) {
          //console.log(textStatus, errorThrown);
          $(".view_error_popup").html('Something went wrong.');
      }
      });
  });
</script>
<script>
function deletepopup1(id,name)
  {      
      
    if(name.length > 50)
    {
      name = unescape(name).substr(0, 50)+'...';
      var msg = 'Are you sure want to delete '+name;
    }
    else
    {
      var msg = 'Are you sure want to delete '+unescape(name);
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
'action': function(){
    delete_all(id);
        }},'No' : {'class'  : 'special'}}});
  }
  function delete_all(id)
  {
    $.ajax({
    type: "POST",
    url: "<?php echo $this->config->item('user_base_url').$viewname.'/update_error';?>",
    dataType: 'json',
    async: false,
    beforeSend: function() {
            $('#error_div').block({ message: 'Loading...' }); 
            },
    data: {'remove_id':id},
    success: function(data){
      
      $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('user_base_url').$viewname.'/view_error_data/';?>"+data,
        data: {
        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:'',selected_view:$('#selected_view').val()
      },
      beforeSend: function() {
            $('#error_div').block({ message: 'Loading...' }); 
            },
        success: function(html){
          //alert(html);
          $("#error_div").html(html);
          $('#error_div').unblock(); 
        }
      });
      return false;
    }
  });
} 
$('body').on('click','#common_tb1 a.paginclass_A',function(e){
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
                   
                    $("#error_div").html(html);
          $.unblockUI();
                }
            });
            return false;
        });

$('#basicModal1').bind('hidden.bs.modal', function () {
  $.ajax({
  type: "POST",
                url: "<?php echo $this->config->item('user_base_url').$viewname.'/update_error_data';?>",
        data: {},
      /*beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            },*/
                success: function(er){
                   
                    $(".view_error_btn").text(er);
                    //$.unblockUI();
                }
            });
 });
</script>
