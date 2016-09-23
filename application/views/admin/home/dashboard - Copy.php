<?php $viewname = $this->router->uri->segments[2];?>
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
               <?php
               
               echo !empty($time_message)?$time_message.' '.$this->admin_session['name'].'!':'';
               //$this->config->item('common_date_format');
               ?>
	   <a title="Previous" href="#" onclick="next_and_pre(-1);"><i class="fa fa-backward btn btn-prev-next-avail arrow"></i></a>
	   <label id="now_date"><?php if(!empty($now_date)){ echo date('l F d, Y',strtotime($now_date));} ?> </label>
		<a title="Next" href="#" onclick="next_and_pre(1);"><i class="fa fa-forward btn btn-prev-next-avail arrow"></i></a> 
           <?php
            if(!empty($now_date1)){ echo date('h:i A',strtotime($now_date1));} else { date('h:i A'); }
            ?>
           </span> 
                
            
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">

         
       
          <div id="common_div">
		  
         <?=$this->load->view('admin/home/ajax_list')?>
         </div>
		 
        </div>
       </div>
       <!-- /.table-responsive --> 
       
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
      </div>
      <!-- /.portlet-content --> 
      
     </div>
    </div>
   </div>
  </div>
  <!-- /#content-container --> 
 
             
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
			success: function(data){
				
				$("#common_div").html(data);
				$('#common_div').unblock(); 
				var next = $('#hidden_date').val();
				$('#now_date').text(next);

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
 </script>