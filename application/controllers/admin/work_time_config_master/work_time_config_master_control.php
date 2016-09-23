<?php
    /*
        @Description: Controller to Get All Work Time Configuration Data
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : all Work Time Configuration list
        @Date       : 19-08-2014
    */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class work_time_config_master_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
		//check user right
		check_rights('work_time_configuration');	
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('work_time_config_master_model');
		$this->load->model('interaction_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_recepient_trans_model');

        $this->obj = $this->work_time_config_master_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All Work Time Configuration List
        @Author     : Mohit Trivedi
        @Input      : Search value or null
        @Output     : all Work Time Configuration list
        @Date       : 14-08-2014
    */
	
    public function index()
    {	
		redirect('admin/'.$this->viewName."/add_record");
    }


    /*
        @Description: Function Add New Work Time Configuration details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add Work Time Configuration plan details
        @Date       : 14-08-2014
    */
	
    public function add_record()
    {
        $match = array("user_id"=>$this->admin_session['id']);
        $data['work_time'] = $this->obj->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
		$data['user_leave'] = $this->obj->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
		$data['special_rules'] = $this->obj->select_records1('',$match,'','=','','','','id','asc','work_time_special_rules');
	    $data['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$data);
    }
	
    /*
        @Description: Function for Insert New Work Time Configuration type data
        @Author     : Mohit Trivedi
        @Input      : Details of new Work Time Configuration plan type
        @Output     : List of Work Time Configuration type
        @Date       : 09-08-2014
    */
	
    public function insert_data()
    {

		// Data for work_time_config_master
		$cdata['user_id']=$this->admin_session['id'];
		$cdata['if_mon'] = $this->input->post('if_mon');
		if($cdata['if_mon']=='1')
		{
			if($this->input->post('mon_start_time'))
			{
				$change['mon_start_time'] = $this->input->post('mon_start_time');
				$cdata['mon_start_time'] = date("H:i",strtotime($change['mon_start_time']));
			}
			else
				$cdata['mon_start_time'];
			
			if($this->input->post('mon_end_time'))
			{
				$change['mon_end_time'] = $this->input->post('mon_end_time');
				$cdata['mon_end_time'] = date("H:i",strtotime($change['mon_end_time']));
			}
			else
				$cdata['mon_end_time'];
			
		}
		$cdata['if_tue'] = $this->input->post('if_tue');
		if($cdata['if_tue']=='1')
		{
			if($this->input->post('tue_start_time'))
			{
				$change['tue_start_time'] = $this->input->post('tue_start_time');
				$cdata['tue_start_time'] = date("H:i",strtotime($change['tue_start_time']));
			}
			else
				$cdata['tue_start_time'];
				
			if($this->input->post('tue_end_time'))
			{
				$change['tue_end_time'] = $this->input->post('tue_end_time');
				$cdata['tue_end_time'] = date("H:i",strtotime($change['tue_end_time']));
			}
			else
				$cdata['tue_end_time'];
		}
		$cdata['if_wed'] = $this->input->post('if_wed');
		if($cdata['if_wed']=='1')
		{
			if($this->input->post('wed_start_time'))
			{
				$change['wed_start_time'] = $this->input->post('wed_start_time');
				$cdata['wed_start_time'] = date("H:i",strtotime($change['wed_start_time']));
			}
			else
				$cdata['wed_start_time'];
			
			if($this->input->post('wed_end_time'))
			{
				$change['wed_end_time'] = $this->input->post('wed_end_time');
				$cdata['wed_end_time'] = date("H:i",strtotime($change['wed_end_time']));
			}
			else
				$cdata['wed_end_time'];
		}
		$cdata['if_thu'] = $this->input->post('if_thu');
		if($cdata['if_thu']=='1')
		{
			if($this->input->post('thu_start_time'))
			{
				$change['thu_start_time'] = $this->input->post('thu_start_time');
				$cdata['thu_start_time'] = date("H:i",strtotime($change['thu_start_time']));
			}
			else
				$cdata['thu_start_time'];
			
			if($this->input->post('thu_end_time'))
			{	
				$change['thu_end_time'] = $this->input->post('thu_end_time');
				$cdata['thu_end_time'] = date("H:i",strtotime($change['thu_end_time']));
			}
			else
				$cdata['thu_end_time'];
		}
		$cdata['if_fri'] = $this->input->post('if_fri');
		if($cdata['if_fri']=='1')
		{
			if($this->input->post('fri_start_time'))
			{
				$change['fri_start_time'] = $this->input->post('fri_start_time');
				$cdata['fri_start_time'] = date("H:i",strtotime($change['fri_start_time']));
			}
			else
				$cdata['fri_start_time'];
			
			if($this->input->post('fri_end_time'))
			{
				$change['fri_end_time'] = $this->input->post('fri_end_time');
				$cdata['fri_end_time']=date("H:i",strtotime($change['fri_end_time']));
			}
			else
				$cdata['fri_end_time'];
			
		}
		$cdata['if_sat'] = $this->input->post('if_sat');
		if($cdata['if_sat']=='1')
		{
			if($this->input->post('sat_start_time'))
			{
				$change['sat_start_time'] = $this->input->post('sat_start_time');
				$cdata['sat_start_time'] = date("H:i",strtotime($change['sat_start_time']));
			}
			else
				$cdata['sat_start_time'];
			
			if($this->input->post('sat_end_time'))
			{
				$change['sat_end_time'] = $this->input->post('sat_end_time');
				$cdata['sat_end_time'] = date("H:i",strtotime($change['sat_end_time']));
			}
			else
				$cdata['sat_end_time'];
				
		}
		$cdata['if_sun'] = $this->input->post('if_sun');
		if($cdata['if_sun']=='1')
		{
			if($this->input->post('sun_start_time'))
			{
				$change['sun_start_time'] = $this->input->post('sun_start_time');
				$cdata['sun_start_time'] = date("H:i",strtotime($change['sun_start_time']));
			}
			else
				$cdata['sun_start_time'];
			
			if($this->input->post('sun_end_time'))
			{
				$change['sun_end_time'] = $this->input->post('sun_end_time');
				$cdata['sun_end_time'] = date("H:i",strtotime($change['sun_end_time']));
			}
			else
				$cdata['sun_end_time'];
		}
		$cdata['created_by'] = $this->admin_session['id'];
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
		$match=array("user_id"=>$this->admin_session['id']);
		$data['work_time'] = $this->obj->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
		//pr($data['work_time']);exit;
		if(!empty($data['work_time']))
		{	
			$this->obj->update_data($cdata);
			unset($cdata);
		}
		else
		{
			$this->obj->insert_data($cdata);
			unset($cdata);		
		}
		//user_leave_data table

		$from_date = $this->input->post('from_date');
		
		$to_date = $this->input->post('to_date');
		if($from_date!='')
		{
			for($i=0;$i<count($from_date);$i++)
			{
				if(!empty($from_date[$i]))
				{
					$cdata1['user_id']=$this->admin_session['id'];
					$cdata1['from_date'] = date('Y-m-d',strtotime($from_date[$i]));
					$cdata1['to_date'] = date('Y-m-d',strtotime($to_date[$i]));
					$cdata1['created_by'] = $this->admin_session['id'];
					$cdata1['created_date'] = date('Y-m-d H:i:s');		
					$cdata1['status'] = '1';
					$this->obj->insert_data1($cdata1);
				}
			}
		}
		//work_time_special_rules table
		
		$cdata4['nth_day'] = $this->input->post('nth_day');
		$cdata4['nth_date'] = $this->input->post('nth_date');
		$cdata4['start_time'] = $this->input->post('start_time');
        $cdata4['end_time'] = $this->input->post('end_time');
		for($i=0;$i<count($cdata4['nth_day']);$i++)
		{
			if(!empty($cdata4['nth_day'][$i]) && !empty($cdata4['nth_date'][$i]))
			{
				$cdata2['rule_type']= $this->input->post('rule_type'.$i);
				if($cdata2['rule_type'] == 2)
				{
					$cdata2['start_time'] = date("H:i",strtotime($cdata4['start_time'][$i]));
					$cdata2['end_time'] = date("H:i",strtotime($cdata4['end_time'][$i]));
				}
				$cdata2['user_id']=$this->admin_session['id'];
				$cdata2['nth_day'] = $cdata4['nth_day'][$i];
				$cdata2['nth_date'] = $cdata4['nth_date'][$i];
				$cdata2['created_by'] = $this->admin_session['id'];
				$cdata2['created_date'] = date('Y-m-d H:i:s');		
				$cdata2['status'] = '1';
				$this->obj->insert_data2($cdata2);
				unset($cdata2);
			}
		}
		
		///////////////////////////////////////////////////////////////////////////////
		
		$get_all_interaction_plans = $this->interaction_model->get_all_assigned_interaction_plans($this->admin_session['id']);
		
		//pr($get_all_interaction_plans);
		//exit;
		
		if(!empty($get_all_interaction_plans) && count($get_all_interaction_plans) > 0)
		{
			foreach($get_all_interaction_plans as $row_plan)
			{
				
				$interaction_plan_id = $row_plan['interaction_plan_id'];
				
				///////////////////////////////////////////////////////////////////////////
		
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
				$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id'
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
				//pr($old_contacts_data);
				
				///////////////////////////////////////////////////////////////////////////
				
				$plan_id = $interaction_plan_id;
							
				//////////////////////////////////////////////////////////////////////////
				
				$table = "interaction_plan_interaction_master as ipim";
				$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
				$join_tables = array(
									'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
									'admin_users as au' => 'au.id = ipim.created_by',
									'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
									);
				
				$group_by='ipim.id';
				
				$where1 = array('ipim.interaction_plan_id'=>$plan_id);
				
				$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				//pr($interaction_list);
				
				/////////////////////////////////////////////////////////
		
				//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
				
				
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{			
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
									
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
										$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
										
										//pr($worktimedata);exit;
										
										$match = array("user_id"=>$new_user_id,"rule_type"=>1);
										$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
										
										//pr($worktimespecialdata);exit;
										
										$match = array("user_id"=>$new_user_id);
										$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
										
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
											foreach($worktimespecialdata as $row2)
											{
												$day_string = '';
												if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
												{
													switch($row2['nth_day'])
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
													switch($row2['nth_date'])
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
										
										foreach($worktimeleavedata as $row2)
										{
											if(!empty($row2['from_date']))
											{
												$leave_days1[] = $row2['from_date'];
												if(!empty($row2['to_date']))
												{
													
													//$from_date = date('Y-m-d',strtotime($row['from_date']));
													
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													
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
									
								
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
									
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
								$sendemaildate = $iccdata1['task_date'];
								
								//$iccdata['created_date'] = date('Y-m-d H:i:s');
								//$iccdata['created_by'] = $this->admin_session['id'];
								
								//pr($iccdata1);
								//exit;
								
								$this->interaction_model->update_contact_communication_record($iccdata1);
								
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
				
									$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($email_campaign_data) > 0)
									{
										if(!empty($email_campaign_data[0]['id']))
										{
											$idata['id'] = $email_campaign_data[0]['id'];
											$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->update_email_campaign_trans($idata);
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
				
									$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($sms_campaign_data) > 0)
									{
										if(!empty($sms_campaign_data[0]['id']))
										{
											$idata['id'] = $sms_campaign_data[0]['id'];
											$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_recepient_trans_model->update_record($idata);
											unset($idata);
										}
									}
								}
								unset($iccdata1);
								
								}
								
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
				
							}
						
						}
					}
				}
								
				/////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		
		//echo "here";exit;
		
		///////////////////////////////////////////////////////////////////////////////
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	/*
        @Description: Function for Update leave 
        @Author     : Mohit Trivedi
        @Input      : Update details of leave
        @Output     : List with updated leave
        @Date       : 19-08-2014
    */
	
    public function update_leave()
    {	
	    $cdata['id'] = $this->input->post('leave_id');
        $cdata['to_date'] = date('Y-m-d',strtotime($this->input->post('to_date')));
		$cdata['from_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));	
        $cdata['modified_by'] = $this->admin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->obj->update_leave($cdata);
		
		///////////////////////////////////////////////////////////////////////////////
		
		$get_all_interaction_plans = $this->interaction_model->get_all_assigned_interaction_plans($this->admin_session['id']);
		
		//pr($get_all_interaction_plans);
		//exit;
		
		if(!empty($get_all_interaction_plans) && count($get_all_interaction_plans) > 0)
		{
			foreach($get_all_interaction_plans as $row_plan)
			{
				
				$interaction_plan_id = $row_plan['interaction_plan_id'];
				
				///////////////////////////////////////////////////////////////////////////
		
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
				$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id'
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
				//pr($old_contacts_data);
				
				///////////////////////////////////////////////////////////////////////////
				
				$plan_id = $interaction_plan_id;
							
				//////////////////////////////////////////////////////////////////////////
				
				$table = "interaction_plan_interaction_master as ipim";
				$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
				$join_tables = array(
									'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
									'admin_users as au' => 'au.id = ipim.created_by',
									'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
									);
				
				$group_by='ipim.id';
				
				$where1 = array('ipim.interaction_plan_id'=>$plan_id);
				
				$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				//pr($interaction_list);
				
				/////////////////////////////////////////////////////////
		
				//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
				
				
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{			
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
									
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
										$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
										
										//pr($worktimedata);exit;
										
										$match = array("user_id"=>$new_user_id,"rule_type"=>1);
										$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
										
										//pr($worktimespecialdata);exit;
										
										$match = array("user_id"=>$new_user_id);
										$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
										
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
											foreach($worktimespecialdata as $row2)
											{
												$day_string = '';
												if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
												{
													switch($row2['nth_day'])
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
													switch($row2['nth_date'])
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
										
										foreach($worktimeleavedata as $row2)
										{
											if(!empty($row2['from_date']))
											{
												$leave_days1[] = $row2['from_date'];
												if(!empty($row2['to_date']))
												{
													
													//$from_date = date('Y-m-d',strtotime($row['from_date']));
													
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													
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
									
								
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
									
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
								
								$sendemaildate = $iccdata1['task_date'];
								
								//$iccdata['created_date'] = date('Y-m-d H:i:s');
								//$iccdata['created_by'] = $this->admin_session['id'];
								
								//pr($iccdata1);
								//exit;
								
								$this->interaction_model->update_contact_communication_record($iccdata1);
								
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
				
									$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($email_campaign_data) > 0)
									{
										if(!empty($email_campaign_data[0]['id']))
										{
											$idata['id'] = $email_campaign_data[0]['id'];
											$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->update_email_campaign_trans($idata);
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
				
									$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($sms_campaign_data) > 0)
									{
										if(!empty($sms_campaign_data[0]['id']))
										{
											$idata['id'] = $sms_campaign_data[0]['id'];
											$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_recepient_trans_model->update_record($idata);
											unset($idata);
										}
									}
								}
								
								unset($iccdata1);
								
								}
								
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
				
							}
						
						}
					}
				}
								
				/////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		
		//echo "here";exit;
		
		///////////////////////////////////////////////////////////////////////////////
		
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
	}


	/*
        @Description: Function for Update rules 
        @Author     : Mohit Trivedi
        @Input      : Update details of rules
        @Output     : List with updated rules
        @Date       : 19-08-2014
    */
	
    public function update_rules()
    {	
	    $cdata['id'] = $this->input->post('rule_id');
        $cdata['nth_day'] = $this->input->post('nth_day');
		$cdata['nth_date'] = $this->input->post('nth_date');	
        $cdata['rule_type'] = $this->input->post('rule_type');
		$change['start_time'] = $this->input->post('start_time');
		$cdata['start_time']=date("H:i",strtotime($change['start_time']));
        $change['end_time'] = $this->input->post('end_time');
		$cdata['end_time']=date("H:i",strtotime($change['end_time']));
		$cdata['modified_by'] = $this->admin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_rules($cdata);
		
		///////////////////////////////////////////////////////////////////////////////
		
		$get_all_interaction_plans = $this->interaction_model->get_all_assigned_interaction_plans($this->admin_session['id']);
		
		//pr($get_all_interaction_plans);
		//exit;
		
		if(!empty($get_all_interaction_plans) && count($get_all_interaction_plans) > 0)
		{
			foreach($get_all_interaction_plans as $row_plan)
			{
				
				$interaction_plan_id = $row_plan['interaction_plan_id'];
				
				///////////////////////////////////////////////////////////////////////////
		
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
				$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id'
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
				//pr($old_contacts_data);
				
				///////////////////////////////////////////////////////////////////////////
				
				$plan_id = $interaction_plan_id;
							
				//////////////////////////////////////////////////////////////////////////
				
				$table = "interaction_plan_interaction_master as ipim";
				$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
				$join_tables = array(
									'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
									'admin_users as au' => 'au.id = ipim.created_by',
									'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
									);
				
				$group_by='ipim.id';
				
				$where1 = array('ipim.interaction_plan_id'=>$plan_id);
				
				$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				//pr($interaction_list);
				
				/////////////////////////////////////////////////////////
		
				//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
				
				
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{			
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
									
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
										$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
										
										//pr($worktimedata);exit;
										
										$match = array("user_id"=>$new_user_id,"rule_type"=>1);
										$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
										
										//pr($worktimespecialdata);exit;
										
										$match = array("user_id"=>$new_user_id);
										$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
										
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
											foreach($worktimespecialdata as $row2)
											{
												$day_string = '';
												if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
												{
													switch($row2['nth_day'])
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
													switch($row2['nth_date'])
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
										
										foreach($worktimeleavedata as $row2)
										{
											if(!empty($row2['from_date']))
											{
												$leave_days1[] = $row2['from_date'];
												if(!empty($row2['to_date']))
												{
													
													//$from_date = date('Y-m-d',strtotime($row['from_date']));
													
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													
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
									
								
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
									
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
								
								$sendemaildate = $iccdata1['task_date'];
								
								//$iccdata['created_date'] = date('Y-m-d H:i:s');
								//$iccdata['created_by'] = $this->admin_session['id'];
								
								//pr($iccdata1);
								//exit;
								
								$this->interaction_model->update_contact_communication_record($iccdata1);
								
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
				
									$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($email_campaign_data) > 0)
									{
										if(!empty($email_campaign_data[0]['id']))
										{
											$idata['id'] = $email_campaign_data[0]['id'];
											$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->update_email_campaign_trans($idata);
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
				
									$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($sms_campaign_data) > 0)
									{
										if(!empty($sms_campaign_data[0]['id']))
										{
											$idata['id'] = $sms_campaign_data[0]['id'];
											$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_recepient_trans_model->update_record($idata);
											unset($idata);
										}
									}
								}
								unset($iccdata1);
								
								}
								
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
				
							}
						
						}
					}
				}
								
				/////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		
		//echo "here";exit;
		
		///////////////////////////////////////////////////////////////////////////////
		
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
	}

   /*
        @Description: Function for Delete work time leave
        @Author     : Mohit Trivedi
        @Input      : Delete id of Delete work time leave id
        @Output     : New Delete work time leave list after record is deleted.
        @Date       : 19-08-2014
    */
	
    function delete_leave_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_leave_record($id);
		
		///////////////////////////////////////////////////////////////////////////////
		
		$get_all_interaction_plans = $this->interaction_model->get_all_assigned_interaction_plans($this->admin_session['id']);
		
		//pr($get_all_interaction_plans);
		//exit;
		
		if(!empty($get_all_interaction_plans) && count($get_all_interaction_plans) > 0)
		{
			foreach($get_all_interaction_plans as $row_plan)
			{
				
				$interaction_plan_id = $row_plan['interaction_plan_id'];
				
				///////////////////////////////////////////////////////////////////////////
		
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
				$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id'
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
				//pr($old_contacts_data);
				
				///////////////////////////////////////////////////////////////////////////
				
				$plan_id = $interaction_plan_id;
							
				//////////////////////////////////////////////////////////////////////////
				
				$table = "interaction_plan_interaction_master as ipim";
				$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
				$join_tables = array(
									'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
									'admin_users as au' => 'au.id = ipim.created_by',
									'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
									);
				
				$group_by='ipim.id';
				
				$where1 = array('ipim.interaction_plan_id'=>$plan_id);
				
				$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				//pr($interaction_list);
				
				/////////////////////////////////////////////////////////
		
				//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
				
				
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{			
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
									
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
										$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
										
										//pr($worktimedata);exit;
										
										$match = array("user_id"=>$new_user_id,"rule_type"=>1);
										$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
										
										//pr($worktimespecialdata);exit;
										
										$match = array("user_id"=>$new_user_id);
										$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
										
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
											foreach($worktimespecialdata as $row2)
											{
												$day_string = '';
												if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
												{
													switch($row2['nth_day'])
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
													switch($row2['nth_date'])
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
										
										foreach($worktimeleavedata as $row2)
										{
											if(!empty($row2['from_date']))
											{
												$leave_days1[] = $row2['from_date'];
												if(!empty($row2['to_date']))
												{
													
													//$from_date = date('Y-m-d',strtotime($row['from_date']));
													
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													
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
									
								
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
									
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
								
								$sendemaildate = $iccdata1['task_date'];
								
								//$iccdata['created_date'] = date('Y-m-d H:i:s');
								//$iccdata['created_by'] = $this->admin_session['id'];
								
								//pr($iccdata1);
								//exit;
								
								$this->interaction_model->update_contact_communication_record($iccdata1);
								
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
				
									$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($email_campaign_data) > 0)
									{
										if(!empty($email_campaign_data[0]['id']))
										{
											$idata['id'] = $email_campaign_data[0]['id'];
											$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->update_email_campaign_trans($idata);
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
				
									$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($sms_campaign_data) > 0)
									{
										if(!empty($sms_campaign_data[0]['id']))
										{
											$idata['id'] = $sms_campaign_data[0]['id'];
											$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_recepient_trans_model->update_record($idata);
											unset($idata);
										}
									}
								}
								unset($iccdata1);
								
								}
								
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
				
							}
						
						}
					}
				}
								
				/////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		
		//echo "here";exit;
		
		///////////////////////////////////////////////////////////////////////////////
		
     	$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }


   /*
        @Description: Function for Delete work time rules
        @Author     : Mohit Trivedi
        @Input      : Delete id of work time rules
        @Output     : New work time rules list after record is deleted.
        @Date       : 19-08-2014
    */
	
    function delete_rules_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_rules_record($id);
		
		///////////////////////////////////////////////////////////////////////////////
		
		$get_all_interaction_plans = $this->interaction_model->get_all_assigned_interaction_plans($this->admin_session['id']);
		
		//pr($get_all_interaction_plans);
		//exit;
		
		if(!empty($get_all_interaction_plans) && count($get_all_interaction_plans) > 0)
		{
			foreach($get_all_interaction_plans as $row_plan)
			{
				
				$interaction_plan_id = $row_plan['interaction_plan_id'];
				
				///////////////////////////////////////////////////////////////////////////
		
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
				$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id'
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
				//pr($old_contacts_data);
				
				///////////////////////////////////////////////////////////////////////////
				
				$plan_id = $interaction_plan_id;
							
				//////////////////////////////////////////////////////////////////////////
				
				$table = "interaction_plan_interaction_master as ipim";
				$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
				$join_tables = array(
									'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
									'admin_users as au' => 'au.id = ipim.created_by',
									'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
									);
				
				$group_by='ipim.id';
				
				$where1 = array('ipim.interaction_plan_id'=>$plan_id);
				
				$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				//pr($interaction_list);
				
				/////////////////////////////////////////////////////////
		
				//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
				
				
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{			
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
									
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
										$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
										
										//pr($worktimedata);exit;
										
										$match = array("user_id"=>$new_user_id,"rule_type"=>1);
										$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
										
										//pr($worktimespecialdata);exit;
										
										$match = array("user_id"=>$new_user_id);
										$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
										
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
											foreach($worktimespecialdata as $row2)
											{
												$day_string = '';
												if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
												{
													switch($row2['nth_day'])
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
													switch($row2['nth_date'])
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
										
										foreach($worktimeleavedata as $row2)
										{
											if(!empty($row2['from_date']))
											{
												$leave_days1[] = $row2['from_date'];
												if(!empty($row2['to_date']))
												{
													
													//$from_date = date('Y-m-d',strtotime($row['from_date']));
													
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													
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
									
								
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
									
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
								
								$sendemaildate = $iccdata1['task_date'];
								
								//$iccdata['created_date'] = date('Y-m-d H:i:s');
								//$iccdata['created_by'] = $this->admin_session['id'];
								
								//pr($iccdata1);
								//exit;
								
								$this->interaction_model->update_contact_communication_record($iccdata1);
								
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
				
									$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($email_campaign_data) > 0)
									{
										if(!empty($email_campaign_data[0]['id']))
										{
											$idata['id'] = $email_campaign_data[0]['id'];
											$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->update_email_campaign_trans($idata);
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
				
									$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
									if(count($sms_campaign_data) > 0)
									{
										if(!empty($sms_campaign_data[0]['id']))
										{
											$idata['id'] = $sms_campaign_data[0]['id'];
											$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_recepient_trans_model->update_record($idata);
											unset($idata);
										}
									}
								}
								unset($iccdata1);
								
								}
								
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
				
							}
						
						}
					}
				}
								
				/////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		
		//echo "here";exit;
		
		///////////////////////////////////////////////////////////////////////////////
		
     	$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }

}