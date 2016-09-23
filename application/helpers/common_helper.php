<?php

/*
    @Description: Function for send email
    @Author: Jayesh Rojasara
    @Input: - 
    @Output: - send email
    @Date: 06-05-14
    */
    function send_email($name='',$email_temp_title,$from,$to,$subject,$date='',$confirm_link='',$total='',$link='',$password='',$exdate='',$membername='',$img='',$username='',$code='')
    {   
        $CI = get_instance();
        $CI->load->model('emailtemplates_model');
        $fields = array('id','name','content');
        $arr = array('name'=>$email_temp_title);
        
        $email_templates = $CI->emailtemplates_model->getemailtemplates($fields,$arr,'','=','','','');
        
        if($email_templates)    
        {
         $content = $email_templates[0]['content'];
         $temp_name = $email_templates[0]['name'];

            switch($temp_name)
            {
              case 'registration_link':
                        $get_name = array ('/{username}/', '/{link}/','/{adminname}/'); 
                        $set_name = array ($name, $link, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
            case 'registrationby_admin_link':
                $get_name = array ('/{username}/','/{userid}/','/{password}/', '/{link}/','/{adminname}/'); 
                $set_name = array ($name,$to,$password, $link, 'Tops Tech'); 
                $msg = preg_replace ($get_name, $set_name, $content);
                break;
               case 'appointment_email':
                       $d= explode(" ",$date);
                         $get_name = array ('/{username}/', '/{date}/','/{time}/','/{adminname}/','{link}'); 
                        $set_name = array ($name, date('d-m-Y',strtotime($d[0])), date("H:i a",strtotime($d[1])),'Tops Tech',$confirm_link); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                        break;
               case 'payment_reminder':
                        $get_name = array ('/{username}/', '/{total}/','/{adminname}/'); 
                        $set_name = array ($name, $total, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                        break;
              case 'payment_confirmation':
                        $get_name = array ('/{username}/', '/{total}/','/code/','/{adminname}/'); 
                        $set_name = array ($name, $total,$code, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                        break;
              case 'forgot_password':
                        
                        $get_name = array ('/{username}/', '/{password}/','/{adminname}/'); 
                        $set_name = array ($name, $password, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                        break; 
              case 'appointment_confirm':
                         $d= explode(" ",$date);
                        $get_name = array ('/{username}/', '/{date}/','/{time}/','/{link}/','/{adminname}/'); 
                        $set_name = array ($name,date('d-m-Y',strtotime($d[0])), date("H:i a",strtotime($d[1])), $link, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
             case 'create_membership':
                        $get_name = array ('/{username}/', '/{date}/','/{adminname}/'); 
                        $set_name = array ($name,$date, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
             case 'membership_payment':
                        $get_name = array ('/{username}/', '/{date}/','/{date1}/','/{adminname}/'); 
                        $set_name = array ($name,$date,$exdate,'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
             case 'membership_payment':
                        $get_name = array ('/{username}/','/{membershipname}/', '/{date}/','/{date1}/','/{adminname}/'); 
                        $set_name = array ($name,$membername,$date,$exdate,'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
             case 'app_confirm':
                         $d= explode(" ",$date);
                        $get_name = array ('/{username}/', '/{date}/','/{time}/','/{adminname}/'); 
                        $set_name = array ($name,date('d-m-Y',strtotime($d[0])), date("H:i a",strtotime($d[1])), 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
             case 'app_cancel':
                         $d= explode(" ",$date);
                        $get_name = array ('/{username}/', '/{date}/','/{time}/','/{adminname}/'); 
                        $set_name = array ($name,date('d-m-Y',strtotime($d[0])), date("H:i a",strtotime($d[1])), 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
                 case 'birthday_wish':
                         $get_name = array ('/{username}/','/{src}/','/{adminname}/'); 
                        $set_name = array ($name,$img, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
                  case 'birthday_wish1':
                         $get_name = array ('/{manager}/','/{username}/','/{adminname}/'); 
                        $set_name = array ($name,$username, 'Tops Tech'); 
                        $msg = preg_replace ($get_name, $set_name, $content);
                    break;
               default :
                   break;
            }           
            $config['protocol'] = 'sendemail';
            $config['mailpath'] = '/var/spool/mqueue';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = FALSE;
            $config['protocol'] = 'sendemail';
            $config['smtp_port'] = '10000';
            $config['smtp_host'] = 'webmin@stratus.agentleadspro.com';
            $config['smtp_user'] = 'topsint';  
            $config['smtp_pass'] = 'aditya';  
            $config['mailtype']='html';
            $config['newline']="\r\n";
            $CI->load->library('email', $config);
            
            $CI->email->initialize($config);
            $CI->email->from($from,'Admin');
            $CI->email->to($to);
            $CI->email->subject($subject);
            $CI->email->message($msg);	
            if($CI->email->send())
            {   
                
                return $CI->lang->line('general_email_successsending');
            }
            else
                return $CI->lang->line('general_email_errorsending');
        }
        else
            return $CI->lang->line('general_email_errorsending');
    }
	
	
	function check_admin_login(){
		$CI = & get_instance();  //get instance, access the CI superobject
  		$adminLogin = $CI->session->userdata($CI->lang->line('common_admin_session_label'));
        (!empty($adminLogin['id']))?'':redirect('login');  	
	}
	function check_user_login(){
		$CI = & get_instance();  //get instance, access the CI superobject
  		$userLogin = $CI->session->userdata($CI->lang->line('common_user_session_label'));
        (!empty($userLogin['id']))?'':redirect('login');  	
	}

	function check_superadmin_login(){
		$CI = & get_instance();  //get instance, access the CI superobject
  		$superadminLogin = $CI->session->userdata($CI->lang->line('common_superadmin_session_label'));
        (!empty($superadminLogin['id']))?'':redirect('login');  	
	}
	/*
    @Description: Function for check right
    @Author: Niral Patel
    @Input: - 
    @Output: - date
    @Date: 18-7-2014
    */
	function check_rights($module)
	{
		$CI = get_instance();
		$modules_lists = $CI->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(in_array($module,$modules_lists))
			{}
			else
			{show_404();}	
		}
	}
	/*
    @Description: Function for print array
    @Author: Niral Patel
    @Input: - 
    @Output: - sort
    @Date: 2-2-2014
    */
	function pr($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
	/*
    @Description: Function for date format
    @Author: Niral Patel
    @Input: - 
    @Output: - date
    @Date: 2-2-2014
    */
	function dateformat($date)
    {
        //echo date("m/d/Y", strtotime($date));
		return date("m/d/Y", strtotime($date));
    }
	function databasedateformat($date)
    {
        return date("Y-m-d", strtotime($date));
    }
     //calendar time function
	 function js2PhpTime($jsdate)
	 {
	  if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches)==1){
		$ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
		//echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
	  }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
		$ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
		//echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
	  }
	  return $ret;
	}
	
	function php2JsTime($phpDate){
		//return "/Date(" . $phpDate*1000 . ")/";
		return date("m/d/Y H:i", $phpDate);
	}
	
	function php2MySqlTime($phpDate){
		return date("Y-m-d H:i:s", $phpDate);
	}
	
	function mySql2PhpTime($sqlDate){
		$arr = date_parse($sqlDate);
		return mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);
	}
	
	function common_rescheduled_task($communication_trans_id='')
	{
		$CI = & get_instance();
		$CI->load->model('interaction_model'); 
		$CI->load->model('interaction_plans_model');
		$CI->load->model('work_time_config_master_model');		
		$CI->load->model('email_campaign_master_model');
		$CI->load->model('sms_campaign_recepient_trans_model');
		
		if(!empty($communication_trans_id))
			$get_completed_data = $CI->interaction_model->fetch_contact_communication_record($communication_trans_id);
		//pr($get_completed_data);exit;
		if(!empty($get_completed_data[0]['interaction_plan_id']))
		{		
			$plan_id = $get_completed_data[0]['interaction_plan_id'];
			
			//////////////////// Update task date of related interactions //////////////////////////
			
			$table = "interaction_plan_interaction_master as ipim";
			$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
			$join_tables = array(
								'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
								'admin_users as au' => 'au.id = ipim.created_by',
								'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
								);
			
			$group_by='ipim.id';
			
			$where1 = array('ipim.interaction_plan_id'=>$plan_id);
			
			$interaction_list =$CI->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
			
			//	echo "here";exit;
			
			//////////////// Update Contact Interaction Plan-Interaction Transaction /////////////////////
			
			if(count($interaction_list) > 0)
			{
				foreach($interaction_list as $row1)
				{
				
					if($get_completed_data[0]['interaction_plan_interaction_id'] != $row1['id'])
					{
				
						$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
					
						//////////////////// Integrate User Work time config ///////////////////
						
						if(!empty($assigned_user_id))
						{
						
							//echo $assigned_user_id;
							
							//Get Working Days
							
							//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
							$new_user_id = $assigned_user_id;
							
							//echo $new_user_id;
							
							$match = array("user_id"=>$new_user_id);
							$worktimedata = $CI->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
							
							//pr($worktimedata);exit;
							
							$match = array("user_id"=>$new_user_id,"rule_type"=>1);
							$worktimespecialdata = $CI->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
							
							//pr($worktimespecialdata);exit;
							
							$match = array("user_id"=>$new_user_id);
							$worktimeleavedata = $CI->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
							
							//pr($worktimeleavedata);exit;
							
							$user_work_off_days1 = array();
							
							if(!empty($worktimedata[0]['id']))
							{
								if(empty($worktimedata[0]['if_mon']))
									$user_work_off_days1[] = 'Mon';
								if(empty($worktimedata[0]['if_tue']))
									$user_work_off_days1[] = 'Tue';
								if(empty($worktimedata[0]['if_wed']))
									$user_work_off_days1[] = 'Wed';
								if(empty($worktimedata[0]['if_thu']))
									$user_work_off_days1[] = 'Thu';
								if(empty($worktimedata[0]['if_fri']))
									$user_work_off_days1[] = 'Fri';
								if(empty($worktimedata[0]['if_sat']))
									$user_work_off_days1[] = 'Sat';
								if(empty($worktimedata[0]['if_sun']))
									$user_work_off_days1[] = 'Sun';
							}
							
							$special_days1 = array();
							
							if(!empty($worktimespecialdata))
							{
								foreach($worktimespecialdata as $row)
								{
									$day_string = '';
									if(!empty($row['nth_day']) && !empty($row['nth_date']))
									{
										switch($row['nth_day'])
										{
											case 1:
											$day_string .= 'First ';
											break;
											case 2:
											$day_string .= 'Second ';
											break;
											case 3:
											$day_string .= 'Third ';
											break;
											case 4:
											$day_string .= 'Fourth ';
											break;
											case 5:
											$day_string .= 'Last ';
											break;
										}
										switch($row['nth_date'])
										{
											case 1:
											$day_string .= 'Day';
											break;
											case 2:
											$day_string .= 'Weekday';
											break;
											case 3:
											$day_string .= 'Weekend';
											break;
											case 4:
											$day_string .= 'Monday';
											break;
											case 5:
											$day_string .= 'Tuesday';
											break;
											case 6:
											$day_string .= 'Wednesday';
											break;
											case 7:
											$day_string .= 'Thursday';
											break;
											case 8:
											$day_string .= 'Friday';
											break;
											case 9:
											$day_string .= 'Saturday';
											break;
											case 10:
											$day_string .= 'Sunday';
											break;
											default:
											break;
										}
										
										$special_days1[] = $day_string;
										
									}
								}
							}
							
							$leave_days1 = array();
							
							foreach($worktimeleavedata as $row)
							{
								if(!empty($row['from_date']))
								{
									$leave_days1[] = $row['from_date'];
									if(!empty($row['to_date']))
									{
										
										//$from_date = date('Y-m-d',strtotime($row['from_date']));
										
										$from_date = date('Y-m-d', strtotime($row['from_date'] . ' + 1 day'));
										
										$to_date = date('Y-m-d',strtotime($row['to_date']));
										
										//echo $from_date."-".$to_date;
										
										while($from_date <= $to_date)
										{
											$leave_days1[] = $from_date;
											$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
										}
									}
								}
							}
							
							//pr($user_work_off_days1);
							
							//pr($special_days1);
							
							//pr($leave_days1);
							
						}
								
						////////////////////////////////////////////////////////////////////////
					
						if(!empty($get_completed_data[0]['contact_id']))
						{
							$row = array('id'=>$get_completed_data[0]['contact_id']);
							//foreach($addcontactdata as $row)
							//{
								//$iccdata['interaction_plan_id'] = $plan_id;
								//$iccdata['contact_id'] = $row['id'];
								//$iccdata['interaction_plan_interaction_id'] = $row1['id'];
								//$iccdata['interaction_type'] = $row1['interaction_type'];
								//pr($row1);
								//exit;
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $CI->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
								
								
									if($row1['start_type'] == '1')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										
										///////////////////////////////////////////////////////////////
										
										$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
										$plan_contact_data = $CI->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
										
										//pr($plan_contact_data);exit;
										
										///////////////////////////////////////////////////////////////
										
										if(!empty($plan_contact_data[0]['start_date']))
										{
											$newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
											
											$newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
									
											////////////////////////////////////////////////////////
											
											$repeatoff = 1;
											
											while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
											{
												// Check for Work off days
												// echo $newtaskdate;
												$day_of_date = date('D', strtotime($newtaskdate1));
												$new_special_days = array();
												
												if(!empty($special_days1))
												{
													foreach($special_days1 as $mydays)
													{
														if (strpos($mydays,'Weekday') !== false) {
															$nthday = explode(" ",$mydays);
															if(!empty($nthday[0]))
															{
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
															}
														}
														elseif (strpos($mydays,'Weekend') !== false) {
															$nthday = explode(" ",$mydays);
															if(!empty($nthday[0]))
															{
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
															}
														}
														else
															$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
													}
												}
												
												//pr($new_special_days);
												
												if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
												{
													//echo 'work off'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
												{
													//echo 'special days'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
												{
													//echo 'leave'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												else
												{
													//echo 'else';
													$repeatoff = 0;
													$newtaskdate = $newtaskdate1;
												}
												
												//echo $repeatoff;
												
											}
											
											//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
											
											///////////////////////////////////////////////////////
											
											$iccdata1['task_date'] = $newtaskdate;
										}
									}
									elseif($row1['start_type'] == '2')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										
										$interaction_id = $row1['interaction_id'];
										
										$interaction_res = $CI->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
										
										//pr($interaction_res);
										
										//echo $interaction_res->task_date;
										
										if(!empty($interaction_res->task_date))
										{
											$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
											
											$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
										
											////////////////////////////////////////////////////////
											
											$repeatoff = 1;
											
											while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
											{
												// Check for Work off days
												// echo $newtaskdate;
												$day_of_date = date('D', strtotime($newtaskdate1));
												$new_special_days = array();
												
												if(!empty($special_days1))
												{
													foreach($special_days1 as $mydays)
													{
														if (strpos($mydays,'Weekday') !== false) {
															$nthday = explode(" ",$mydays);
															if(!empty($nthday[0]))
															{
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
															}
														}
														elseif (strpos($mydays,'Weekend') !== false) {
															$nthday = explode(" ",$mydays);
															if(!empty($nthday[0]))
															{
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
																$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
															}
														}
														else
															$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
													}
												}
												
												//pr($new_special_days);
												
												if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
												{
													//echo 'work off'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
												{
													//echo 'special days'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
												{
													//echo 'leave'."<br>";
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												else
												{
													//echo 'else';
													$repeatoff = 0;
													$newtaskdate = $newtaskdate1;
												}
												
												//echo $repeatoff;
												
											}
											
											//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
											
											///////////////////////////////////////////////////////
											
											//echo $newtaskdate;
											
											//$icdata['task_date'] = $newtaskdate;
										
											$iccdata1['task_date'] = $newtaskdate;
										}
										
									}
									else
									{
										$iccdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
									}
									
									//$iccdata['created_date'] = date('Y-m-d H:i:s');
									//$iccdata['created_by'] = $this->admin_session['id'];
									
									//pr($iccdata1);
									
									$sendemaildate = $iccdata1['task_date'];
									
									if(!empty($contact_interaction_plan_interaction_id->id))
									{
										$CI->interaction_model->update_contact_communication_record($iccdata1);
									}
									//exit;
									unset($iccdata1);
									
									// Update old Email or SMS //
						
									if($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8)
									{
										//echo $row1['id']." ".$row['id']."<br>";;
										$table = "email_campaign_master as ecm";
										$fields = array('ecr.*');
										$join_tables = array(
											'email_campaign_recepient_trans as ecr' => 'ecr.email_campaign_id = ecm.id'
											);
					
										//$group_by='ipim.id';
					
										$where1 = array('ecm.interaction_id'=>$row1['id'],'ecr.contact_id'=>$row['id']);
					
										$email_campaign_data = $CI->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
										if(count($email_campaign_data) > 0)
										{
											if(!empty($email_campaign_data[0]['id']))
											{
												$idata['id'] = $email_campaign_data[0]['id'];
												$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
												$CI->email_campaign_master_model->update_email_campaign_trans($idata);
												//echo $this->db->last_query()."<br>";
												unset($idata);
											}
										}
									}
									elseif($row1['interaction_type'] == 3)
									{
										$table = "sms_campaign_master as scm";
										$fields = array('scr.*');
										$join_tables = array(
											'sms_campaign_recepient_trans as scr' => 'scr.sms_campaign_id = scm.id'
											);
					
										//$group_by='ipim.id';
					
										$where1 = array('scm.interaction_id'=>$row1['id'],'scr.contact_id'=>$row['id']);
					
										$sms_campaign_data = $CI->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
										if(count($sms_campaign_data) > 0)
										{
											if(!empty($sms_campaign_data[0]['id']))
											{
												$idata['id'] = $sms_campaign_data[0]['id'];
												$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
												$CI->sms_campaign_recepient_trans_model->update_record($idata);
												unset($idata);
											}
										}
									}
						
								///////////////////////////////
								
								}
				
							//}
						
						}
						
						unset($user_work_off_days1);
						unset($special_days1);
						unset($leave_days1);
					}
				}
			}
			////////////////////////////////////////////////////////////////////////////////////////
		}
	}
        
        /*
            @Description: Function for check joomla tab setting for user.
            @Author     : Sanjay Moghariya
            @Input      : login id
            @Output     : tab config value
            @Date       : 06-01-2015
        */
    function check_joomla_tab_setting($id)
	{
            $CI = & get_instance();
            $CI->load->model('contact_masters_model'); 
            //$adminLogin = $CI->session->userdata($CI->lang->line('common_admin_session_label'));
            //$match1 = array("id"=>$adminLogin['id']);
            $fields = array('is_buyer_tab,lead_dashboard_tab,market_watch_tab,contact_form_tab');
            $match1 = array("id"=>$id);
            $result = $CI->contact_masters_model->select_records1($fields,$match1,'','=','','','','id','desc','login_master');
            return $result;
	}
        
        function addhttp($url) {
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }
            return $url;
        }
        function image_url($image)
        {
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_HEADER, true); 
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
            curl_setopt($ch, CURLOPT_URL, $image); //specify the url
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            $head = curl_exec($ch);
            if(!stristr($head,'HTTP/1.1 404 Not Found'))
                return $image;
            else
                return "";
            //pr($head);
            //return $image;
            //ini_set('display_errors',1);
            //ini_set('display_startup_errors',1);
            //error_reporting(-1);

            //$img = get_headers($image, 1);
            //$img = getimagesize($image);
            /*if(!empty($head))
                return $image;
            else
                return '';*/

            /*if(substr($img['Content-Type'], 0,5) == 'image')
            {
                    return $image;exit;
            }
            else
                    return '';*/
        }
?>
