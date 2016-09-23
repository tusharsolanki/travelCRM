<?php 
    /*
        @Description: Admin DashBoard Notification
        @Author: kaushik Valiya
        @Date: 30-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$user = $this->session->userdata($this->lang->line('common_user_session_label'));
?>
<table width="100%" class="table1 table-striped1 table-striped2 table-bordered1 table-hover1 table-highlight table table-striped table-bordered  " id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
		 		<thead>
                  <tr role="row">
                    <th width="7%" aria-label="CSS grade" colspan="2" rowspan="1" role="columnheader" data-filterable="true" class="hidden-xs hidden-sm sorting_disabled">Task Notification</th>
                  </tr>
                </thead>
            	<tr class="load_conversations">
                  <td width="50%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <input type="hidden" id="hidden_date" name="hidden_date"  value="<?php if(!empty($now_date)){ echo date($this->config->item('common_date_format'),strtotime($now_date));} ?>" />
						<?php 
					  	if(!empty($task_notification))
						{
							//pr($task_notification);
							for($j=0;$j < count($task_notification);$j++)
							{
								if($task_notification[$j]['is_email'] != '' && $task_notification[$j]['is_popup'] != '')
								{
									
									$task_date=date_create(date($this->config->item('common_date_format'),strtotime($task_notification[$j]['task_date'])));
									$now_date1=date_create(date($this->config->item('common_date_format'),strtotime($task_notification[$j]['created_date'])));
									//$now_date1=date_create(date($this->config->item('common_date_format')));
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
									//pr($newtaskdate);
									
								$now_datetime=date($this->config->item('log_date_format'),strtotime($task_notification[$j]['created_date']));
								//pr($now_datetime);
								if(strtotime($task_notification[$j]['task_date']) >= $now_datetime  && strtotime($newtaskdate) <= $now_datetime)								
										{
										?>
											
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
										$now_datetime=date($this->config->item('common_date_format'),strtotime($task_notification[$j]['created_date']));
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
											//pr($task_notification);
									if(!empty($task_notification[$j]['popup_time_before']))
									{
										if(!empty($task_notification[$j]['popup_time_type']) && $task_notification[$j]['popup_time_type']=='1')
										{
											$counttype='Hours';
										$newtaskdate = date($this->config->item('log_date_format'),strtotime($task_notification[$j]['task_date']."- ".$task_notification[$j]['popup_time_before']." ".$counttype));
									//	pr($newtaskdate);
								$now_datetime=date($this->config->item('log_date_format'),strtotime($task_notification[$j]['created_date']));
								if(strtotime($task_notification[$j]['task_date']) >= $now_datetime  && $newtaskdate <= $now_datetime)	
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
										$now_datetime=date($this->config->item('common_date_format'),strtotime($task_notification[$j]['created_date']));
										if($newtaskdate1 == $now_datetime)								
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
								<td width="30%"><b><?php echo $task_notification[$j]['task_name'] ?></b></td>
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