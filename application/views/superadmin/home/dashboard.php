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
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">

         <table width="100%" class="table1 table-striped1 table-striped2 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
		 		<thead>
                  <tr role="row">
                    <th width="7%" aria-label="CSS grade" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Task Notification</th>
                  </tr>
                </thead>
            	<tr>
                  <td width="50%">
                    <table width="100%" class="personaltouches" border="0" cellspacing="0" cellpadding="0">
                     <?php 
					  	if(!empty($personale_touches))
						{
							//pr($personale_touches);
							for($i=0;$i < count($personale_touches);$i++)
							{
								
								$follow_date=date_create(date($this->config->item('common_date_format'),strtotime($personale_touches[$i]['followup_date'])));
								$now_date=date_create(date($this->config->item('common_date_format')));
								$diff=date_diff($follow_date,$now_date);
								$result=$diff->format("%a");
							if(empty($result))
							{
							?>
							  <tr>
								<td width="30%" colspan="3"><b>Contact :  </b><?php echo $personale_touches[$i]['contact_name'] ?></td>
								
							  </tr>
							   <tr>
								<td width="30%"><b><?php echo $personale_touches[$i]['name'] ?></b></td>
								<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($personale_touches[$i]['followup_date'])); ?></td>
								<td width="20%"><a class="btn pull-right btn-success howler" href="<?php echo $this->config->item('admin_base_url')."contacts/view_record/".$personale_touches[$i]['contact_id']."/5#myTab2";?>">Review</a></td>
							  </tr>
							
							  <tr>
								<td colspan="2"><?php echo $personale_touches[$i]['task'] ?></td>
							  </tr>
							  <?php } }} ?>
						<?php 
					  	if(!empty($task_notification))
						{
							//pr($task_notification);
							for($j=0;$j < count($task_notification);$j++)
							{
								if($task_notification[$j]['is_email'] == '' && $task_notification[$j]['is_popup'] == '')
								{
									
								}
								else
								{
									
									$task_date=date_create(date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])));
									$now_date1=date_create(date($this->config->item('common_date_format')));
									$diff1=date_diff($task_date,$now_date1);
									$result1=$diff1->format("%a");
								}
								if(!empty($task_notification[$j]['is_email']))
								{
									if(!empty($task_notification[$j]['email_time_before']))
									{
										if(!empty($task_notification[$j]['email_time_type']) && $task_notification[$j]['email_time_type']=='1')
										{
											$counttype='Hours';
										$newtaskdate = date($this->config->item('log_date_format'),strtotime($task_notification[$j]['task_date']."- ".$task_notification[$j]['email_time_before']." ".$counttype));
								$now_datetime=strtotime(date($this->config->item('log_date_format')));
								if(strtotime($task_notification[$j]['task_date']) >= $now_datetime  && strtotime($newtaskdate) <= $now_datetime)								
										{?>
											<tr>
												<td width="30%" colspan="3"><b>User :  </b><?php echo $task_notification[$j]['user_name'] ?></td>
											</tr>
											<tr>
												<td width="30%"><b><?php echo $task_notification[$j]['task_name'] ?></b></td>
												<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])); ?></td>
												<td width="20%"></td>
											</tr>
											<tr>
														<td colspan="2"><?php echo $task_notification[$j]['task_name'] ?></td>
											</tr>
											
										<?php }
										}
										if(!empty($task_notification[$j]['email_time_type'])&& $task_notification[$j]['email_time_type']=='2')
										{
										$counttype='Days';
										$newtaskdate1 = date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date']."- ".$task_notification[$j]['email_time_before']." ".$counttype));
										//echo $newtaskdate1;
										$now_datetime=strtotime(date($this->config->item('common_date_format')));
										if(strtotime($newtaskdate1) == $now_datetime)								
												{?>
													 <tr>
														<td width="30%" colspan="3"><b>User :  </b><?php echo $task_notification[$j]['user_name'] ?></td>
													</tr>
													 <tr>
														<td width="30%"><b><?php echo $task_notification[$j]['task_name'] ?></b></td>
														<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])); ?></td>	
														<td width="20%"></td>
													 </tr>
													 <tr>
														<td colspan="2"><?php echo $task_notification[$j]['task_name'] ?></td>
													</tr>
													
												<?php }
												}
											}
										}
										
								if(!empty($task_notification[$j]['is_popup']))
								{
											
									if(!empty($task_notification[$j]['popup_time_before']))
									{
										if(!empty($task_notification[$j]['popup_time_type']) && $task_notification[$j]['popup_time_type']=='1')
										{
											$counttype='Hours';
										$newtaskdate = date($this->config->item('log_date_format'),strtotime($task_notification[$j]['task_date']."- ".$task_notification[$j]['popup_time_before']." ".$counttype));
									//	pr($newtaskdate);
								$now_datetime=strtotime(date($this->config->item('log_date_format')));
								if(strtotime($task_notification[$j]['task_date']) >= $now_datetime  && strtotime($newtaskdate) <= $now_datetime)								
										{?>
											
											<tr>
												<td width="30%" colspan="3"><b>User :  </b><?php echo $task_notification[$j]['user_name'] ?></td>
											</tr>
											<tr>
												<td width="30%"><b><?php echo $task_notification[$j]['task_name'] ?></b></td>
												<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])); ?></td>
												<td width="20%"></td>
											</tr>
											<tr>
														<td colspan="2"><?php echo $task_notification[$j]['task_name'] ?></td>
											</tr>
										
										<?php }
										}
										if(!empty($task_notification[$j]['popup_time_type'])&& $task_notification[$j]['popup_time_type']=='2')
										{
										$counttype='Days';
										$newtaskdate1 = date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date']."- ".$task_notification[$j]['popup_time_before']." ".$counttype));
										//echo $newtaskdate1;
										$now_datetime=strtotime(date($this->config->item('common_date_format')));
										if(strtotime($newtaskdate1) == $now_datetime)								
												{?>
													 <tr>
														<td width="30%" colspan="3"><b>User :  </b><?php echo $task_notification[$j]['user_name'] ?></td>
													</tr>
													 <tr>
														<td width="30%"><b><?php echo $task_notification[$j]['task_name'] ?></b></td>
														<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])); ?></td>	
														<td width="20%"></td>
													 </tr>
													<tr>
														<td colspan="2"><?php echo $task_notification[$j]['task_name'] ?></td>
													</tr>
												<?php }
												}
											}
										}
								
							if(empty($result1))
							{
							?>
							  <tr>
								<td width="30%" colspan="3"><b>User :  </b><?php echo $task_notification[$j]['user_name'] ?></td>
								
							  </tr>
							   <tr>
								<td width="30%"><b><?php echo $task_notification[$j]['name'] ?></b></td>
								<td width="50%"><?php echo date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])); ?></td>
								<td width="20%"></td>
							  </tr>
							
							  <tr>
								<td colspan="2"><?php echo $task_notification[$j]['task_name'] ?></td>
							  </tr>
							  <?php } }} ?>
							 	  
							 
                    </table>
                  </td>
                  
                </tr>
              </table>
       
         
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