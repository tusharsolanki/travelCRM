<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade email_sms_send_popup">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content mian_box">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Profile Picture<span class="popup_heading_h3"></span></h3>
      </div>
      <div class="modal-body pading_zero">
      <?php  if(!empty($prifile_pic[0]['admin_pic']) && file_exists($this->config->item('admin_big_img_path').$prifile_pic[0]['admin_pic'])){
	  ?>
			<img class="img-responsive profile_padding" src="<?=$this->config->item('admin_upload_img_big')?>/<?=(!empty($prifile_pic[0]['admin_pic'])?$prifile_pic[0]['admin_pic']:'');?>"/>
      <?php } ?>
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
      
      <?php /*?><?php $this->load->view('admin/interaction_plans/view_error_popup');?><?php */?>
      
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php $viewname = $this->router->uri->segments[2];?>
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
								<th width="7%" aria-label="CSS grade" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Show Notification</th>
							  </tr>
							</thead>
						
				<?php 
				if(!empty($task_name_popup))
				{
					for($j=0;$j < count($task_name_popup);$j++)
					{
						if($is_close[$j] == '0')
						{
						?>
						<tr class="load_conversations">
							  <td width="50%">
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">	
									<tr>
										<td width="30%" colspan="3"><b>User :  </b>
										
										<?php $user_name = $user_name_popup[$j].", ".$admin_name_popup[$j];
									
											//$address=str_replace(', ',', ',$user_name);
											$letters = array(', , , , ,',', , , ,',', , , ',', , ');
											$fruit   = array(',',',',',',',');
											//$text    = $address;
											$output  = str_replace($letters, $fruit, $user_name);
											$output = ltrim($output,",");
											$output = rtrim($output,",");
											echo ucwords($output);
									?>
									</td>
									</tr>
                                    <tr>
										<td colspan="2"><b>Task : </b><?php echo $task_name_popup[$j]; ?></td>
									</tr>
									<tr>
                                    	<td colspan="2"><b>Task Date : </b><?php echo date($this->config->item('common_date_format'),strtotime($task_data[$j])); ?></td>
									</tr>
									
								</table>
							</td>
						</tr>
						<input type="hidden" id="task_id" name="task_id[]" value="<?php echo $created_by[$j]; ?>" />
						<input type="hidden" id="popup_id" name="popup_id" value="<?php echo $is_close[$j]; ?>" />
								
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
										<td colspan="2"><b>Event : </b><?php echo $calendar_name_popup[$j]; ?></td>
									</tr>
									<tr>
                                    	<td colspan="2"><b>Calendar Event Date : </b><?php echo date($this->config->item('common_date_format'),strtotime($calendar_data[$j])); ?></td>
										<!--<td width="35%"><b>Calendar Event Date : </b></td>
										
										<td width="55%"><?php echo date($this->config->item('common_date_format'),strtotime($calendar_data[$j])); ?></td>-->
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
				<div class="col-sm-12 text-center mrgb4">
				
			
        <input type="submit" value="Close Notification" class="btn btn-secondary" title="Close Notification" onclick="close_popup(1);">
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
  <div id="content-container" class="nishit">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('common_label_dashboard');?></h3>
	   <span class="pull-right">
               <?php /*
               
               echo !empty($time_message)?$time_message.' '.$this->admin_session['name'].'!':'';
               //$this->config->item('common_date_format');
               ?>
	   <a title="Previous" href="#" onclick="next_and_pre(-1);"><i class="fa fa-backward btn btn-prev-next-avail arrow"></i></a>
	   <label id="now_date"><?php if(!empty($now_date)){ echo date('l F d, Y',strtotime($now_date));} ?> </label>
		<a title="Next" href="#" onclick="next_and_pre(1);"><i class="fa fa-forward btn btn-prev-next-avail arrow"></i></a> 
           <?php
            if(!empty($now_date1)){ echo date('h:i A',strtotime($now_date1));} else { date('h:i A'); }
           */ ?>
           </span> 
                
            
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">

<!--<div>
<form class="form parsley-form" enctype="multipart/form-data" name="pause_play_stop" id="pause_play_stop" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?>interaction_plans/all_pause_play_stop">
	<input type="hidden" name="plan_status" id="plan_status" value="" />
	<button class="btn btn-secondary" title="Pause" name="plan_pause" id="plan_pause" onclick="return plan_pause_play_stop('Pause','2');"><i class="fa fa-pause"></i></button>
    <button class="btn btn-secondary" title="Play" name="plan_play" id="plan_play" onclick="return plan_pause_play_stop('Play','1');"><i class="fa fa-play"></i></button>
    <button class="btn btn-secondary" title="Stop" name="plan_stop" id="plan_stop" onclick="return plan_pause_play_stop('Stop','3');"><i class="fa fa-stop"></i></button>
</form>
</div>-->
          <div id="common_div">  
         <?=$this->load->view('admin/home/ajax_list')?>
         </div>
		 
        </div>
       </div>
       <!-- /.table-responsive --> 
       
       <!--  
       /*
       
       <div class="row">
    <div class="col-md-12">
    <div class="row">
    
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="deshboard_main"><h3>Good Morning Isaac!</h3></div>
    </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
     <div class="deshboard_Wednesday">
     <div class="Wednesday_main">
     <p><span class="arrow_left"><a href="#" class="arrow_left"></a></span> Wednesday September 24, 2014<span><a href="#" class="arrow_rigth"></a>
     </span></p>
     
     </div>
     	<div class="Wednesday_right">4:45 PM</div>
    
     </div>
    </div>
    </div>
    
     <div class="row">
     <div id="content-container_margin_bottom">
     <div class="col-md-12">
     
     <div class="row">
      <div class="col-sm-4">
      <div class="new_listings"><p>New Listings</p>
      <h1>3</h1></div>
      
      </div>
     
       <div class="col-sm-4 mein_box_center">
      <div class="new_listings"><p>New Leads</p>
      <h1>9</h1></div>
       </div>
        <div class="col-sm-4">
       <div class="new_listings"><p>New Expired / Cancelled</p>
      <h1>27</h1></div>
        </div>
     
     </div>
     </div>
     </div>
    <div class="col-sm-12">
    <div class="row">
    <div id="content-container_margin_bottom">
    
    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
       <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="Daily_box_mian">
        <div class="Daily_box_mian_img"></div>
        <div class="Daily_box_mian_te">
           <p>   Mailings </p>
            <h2>6 / <span>1</span></h2>
      </div>
      </div>
       
       
       </div>
        <div id="content-container_margin_bottom">
         <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
         <div class="Daily_box_mian phone">
        <div class="Daily_box_phone_img"></div>
        <div class="Daily_box_mian_te">
           <p>  Colls </p>
            <h2>9 <span></span></h2>
      </div>
      </div>
         
         </div>
         </div>
         </div>
         <div class="row">
          <div class="content-container_margin_bottom">
         <div class="col-md-12">
         <div class="row">
        
          <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
          <div class="Daily_box_mian_bottom">
          <div class="Daily_box_mian_bottom_te">
           <p>  SMS </p>
            <h2>17 </h2>
      </div>
        <div class="Daily_box_mian_bottom_img"></div>
        
      </div></div>
           <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
           <div class="Daily_box_mian Social">
           <div class="Daily_box_mian_bottom_te">
           <p>  Social Media </p>
            <h2>32</h2>
      </div>
           
        <div class="Daily_box_Social_img"></div>
        
      </div>
           </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="Daily_box_mian Schedule">
           <div class="Daily_box_mian_bottom_te">
           <p>  Tasks </p>
            <h2>32</h2>
      </div>
           
        <div class="Daily_box_mian_Schedule_img"></div>
        
      </div>
            </div>
             <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 mein_box_center1">
             <div class="Daily_box_mian email">
           <div class="Daily_box_mian_bottom_te">
           <p>  Emails </p>
            <h2>18 / <span>2</span></h2>
      </div>
           
        <div class="Daily_box_mian_email_img"></div>
        
      </div>
             </div>
             </div>
             </div>
         </div>
         </div>
    </div>
    </div>
    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <div class="Daily_box">
      <div class="Daily_box_img"></div>
      <div class="daily_task_count">
      <p> All Tasks </p>
      <h2>87 / <span>6</span></h2>
      </div>
      
      </div>
    </div>
    </div>
    
    
    </div>
     
     
    
    
    </div>
       </div>
      </div>-->
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
var popup_id = $('#calendar_popup_id').val();
var popup_id1 = $('#popup_id').val();

if(popup_id == '0' || popup_id1 == '0')
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
			url: "<?php echo $this->config->item('admin_base_url').'dashboard';?>",
			data: {result_type:'ajax','date':date},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					},
			success: function(data){
				
				$("#common_div").html(data);
				$('#common_div').unblock(); 
				var next = $('#hidden_date').val();
				//$('#now_date').text(next);
				//$.unblockUI();

		return false;
			}
		});
 
 }
 function close_popup(id)
 {
 	var myarray = new Array;
	var myarray_cal = new Array;
			var i=0;
			var j=0;
			var boxes = $('input[name="task_id[]"]');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
					i++;
			});
			
			var boxes_cal = $('input[name="calendar_id[]"]');
			$(boxes_cal).each(function(){
  				  myarray_cal[j]=this.value;
					j++;
			});
			
	$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').'dashboard/popup_changes';?>",
			data: {result_type:'ajax','myarray':myarray,'id':id,'myarray_cal':myarray_cal},
			success: function(data){
				
				$('.modal-header .close').trigger('click');

		return false;
			}
		});
 }
function plan_pause_play_stop(str,plan_status)
{
	$.confirm({
			'title': 'Confirm Message','message': " <strong> Are you sure want to "+str+" communication?",'buttons': {'Yes': {'class': '',
			'action': function(){
				$('#plan_status').val(plan_status);
				$('#pause_play_stop').submit();
				return true;
				}},'No'	: {'class'	: 'special'}}});
	return false;
}
 </script>
 <script>
 function contact_search(allflag)
  {
            var uri_segment = $("#uri_segment").val();
    $.ajax({
      type: "POST",
      url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_error_data/';?>"+uri_segment,
      
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
      url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_error_data';?>",
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
    url: "<?php echo $this->config->item('admin_base_url').$viewname.'/update_error';?>",
    dataType: 'json',
    async: false,
    beforeSend: function() {
            $('#error_div').block({ message: 'Loading...' }); 
            },
    data: {'remove_id':id},
    success: function(data){
      
      $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_error_data/';?>"+data,
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
/*$('.close_error_select_popup').live('click',function(){
  //alert('hi');

            
});*/
$('#basicModal1').bind('hidden.bs.modal', function () {
  $.ajax({
  type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/update_error_data';?>",
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