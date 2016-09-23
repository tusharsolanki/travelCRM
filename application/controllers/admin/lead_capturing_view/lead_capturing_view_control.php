<?php
/*
    @Description: Lead capturing View controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 18-09-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lead_capturing_view_control extends CI_Controller
{	
    function __construct()
    {
 		parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('lead_capturing_model');
		$this->load->model('contact_masters_model');
		$this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('interaction_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->obj = $this->lead_capturing_model;
		$this->obj1= $this->user_management_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	

/*
	@Description: Function for Get All Lead List
	@Author: Mohit Trivedi
	@Input: - Search value or null
	@Output: - all contacts list
	@Date: 18-09-2014
*/
    public function index()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		
		$data['sortfield']		= 'ld.id';
		$data['sortby']			= 'desc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			$sortfield = 'id';
			$sortby = 'desc';
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		if(!empty($searchopt))
		{
			$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($perpage))
		{	
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		if(!empty($date1) && !empty($date2))
		{
			 $date1 = $this->input->post('date1');
			 $date2 = $this->input->post('date2');
			 $data['date1'] = $date1;
           	 $data['date2'] = $date2;	
		}
		if(!empty($perpage))
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
			$data['perpage']='10';
		}
		$form_id = $this->uri->segment(3);
		$config['base_url'] = site_url($this->user_type.'/'."lead_capturing_view/".$form_id);
        $config['is_ajax_paging'] = TRUE; 
        $config['paging_function'] = 'ajax_paging'; 
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		$table = "lead_data as ld";
		$fields = array('ld.*','CONCAT_WS(" ",ld.first_name_data,ld.last_name_data) as name','lm.form_title');
		
		$join_tables = array('lead_master as lm' => 'lm.id = ld.form_id');
		
		$group_by='ld.id';
		if(!empty($searchtext))
		{
			$match=array('ld.first_name_data'=>$searchtext,'ld.phone_data'=>$searchtext,'ld.email_data'=>$searchtext,'ld.address_data'=>$searchtext);
			
			$where1=array('ld.form_id'=>$form_id);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'like','','','','',$group_by,$where1));
		}
		else
		{
			$where1=array('ld.form_id'=>$form_id);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1));
		
		}
		//$match3=array('um.status'=> '1');
		//$data['userlist'] = $this->contact_masters_model->select_records1('',$match3,'','=','','','','','asc','user_master');
		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id,lm.agent_type');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
		$match3 = array('um.status'=> '1','lm.user_type' => '3');
		$data['userlist'] = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match3,'=','','','','asc');
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('admin/include/template',$data);
		}
    }
	
    /*
    @Description: Function View list of user details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - List Of User
    @Date: 18-09-2014
    */
    public function user_list()
    {
		//$match=array('status'=> '1');
		//$data['userlist'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','','asc','user_master');
		
		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $match=array('um.status'=> '1','lm.user_type' => '3');
		$data['userlist'] = $this->obj1->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','asc');
		
		echo json_encode($data['userlist']);
	}	
    
	
	/*
    @Description: Function assign leads to user
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - assign lead to user
    @Date: 19-09-2014
    */
    public function assign_contact()
    {

		$leadid=$this->input->post('leadid');
		$formid=$this->uri->segment(4);
		$user=$this->input->post('user');
		$match1=array('id'=> $leadid);
		$data1 = $this->obj->select_records1('',$match1,'','=');
		
		if(!empty($data1))
		{
			//data for contact master tabel
			$cdataname['first_name']=$data1[0]['first_name_data'];
			$cdataname['last_name']=$data1[0]['last_name_data'];
			$cdatasingleline1['single_line_data']=$data1[0]['single_line_data'];
			$cdatamultiline1['paragraph_data']=$data1[0]['paragraph_data'];
			$cdatamultiline1['price_range_from']=$data1[0]['price_range_from'];
			$cdatamultiline1['price_range_to']=$data1[0]['price_range_to'];
			$cdatamultiline1['house_style']=$data1[0]['house_style'];
			$cdatamultiline1['area_of_interest']=$data1[0]['area_of_interest'];
			$cdatamultiline1['square_footage']=$data1[0]['square_footage'];
			$cdatamultiline1['no_of_bedrooms']=$data1[0]['no_of_bedrooms'];
			$cdatamultiline1['no_of_bathrooms']=$data1[0]['no_of_bathrooms'];
			$cdatamultiline1['buyer_preferences_notes']=$data1[0]['buyer_preferences_notes'];
					
			if(!empty($cdataname['first_name'])||!empty($cdataname['last_name'])||$cdatasingleline1['single_line_data']||$cdatamultiline1['paragraph_data'])
			{
				if(!empty($cdataname['first_name']))
				{
					$cdata['first_name']=str_replace("{^}",',',($cdataname['first_name']));
				}
				if(!empty($cdataname['last_name']))
				{
					$cdata['last_name']=str_replace("{^}",',',($cdataname['last_name']));
				}
				if(!empty($cdatasingleline1['single_line_data']))
				{
					$cdatasingleline['single_line_data']=str_replace("{^}",',',($cdatasingleline1['single_line_data']));
					$cdata['notes'].= $cdatasingleline['single_line_data'];
				}
				if(!empty($cdatamultiline1['paragraph_data']))
				{
					$cdatamultiline['paragraph_data']=str_replace("{^}",',',($cdatamultiline1['paragraph_data']));
					$cdata['notes'].= $cdatamultiline['paragraph_data'];
				}
				if(!empty($cdatamultiline1['price_range_from']))
					$cdata['price_range_from']=str_replace("{^}",',',($cdatamultiline1['price_range_from']));
				if(!empty($cdatamultiline1['price_range_to']))
					$cdata['price_range_to']=str_replace("{^}",',',($cdatamultiline1['price_range_to']));
				if(!empty($cdatamultiline1['house_style']))
					$cdata['house_style']=str_replace("{^}",',',($cdatamultiline1['house_style']));
				if(!empty($cdatamultiline1['area_of_interest']))
					$cdata['area_of_interest']=str_replace("{^}",',',($cdatamultiline1['area_of_interest']));
				if(!empty($cdatamultiline1['square_footage']))
					$cdata['square_footage']=str_replace("{^}",',',($cdatamultiline1['square_footage']));
				if(!empty($cdatamultiline1['no_of_bedrooms']))
					$cdata['no_of_bedrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bedrooms']));
				if(!empty($cdatamultiline1['no_of_bathrooms']))
					$cdata['no_of_bathrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bathrooms']));
				if(!empty($cdatamultiline1['buyer_preferences_notes']))
					$cdata['buyer_preferences_notes']=str_replace("{^}",',',($cdatamultiline1['buyer_preferences_notes']));
				
				
				$cdata['created_by'] = $this->admin_session['id'];
				$cdata['created_date'] = date('Y-m-d H:i:s');		
				$cdata['status'] = '1';
				$cdata['created_type']='5';
				$cdata['lead_id']=$leadid;
			    $lastId=$this->contacts_model->insert_record($cdata);
				
				unset($cdata);
				
				if(!empty($data1[0]['form_id']) && !empty($lastId))
				{
					$lead_contact_type_list = $this->obj->select_lead_contact_trans_record($data1[0]['form_id']);
					if(!empty($lead_contact_type_list[0]))
					{
						for($i=0;$i<count($lead_contact_type_list);$i++)
						{
								$trans_data['contact_id'] = $lastId;
								$trans_data['contact_type_id'] = $lead_contact_type_list[$i]['contact_type_id'];		
								$this->contacts_model->insert_contact_type_record($trans_data);
					
						}
					}
				}
				
				
				//contact_contacttype_trans
			//	pr($contact_type['lead_contact_type_trans']);exit;
				//End ..
				
				// Apply plan assignment //
				
				
				if(!empty($data1[0]['form_id']) && !empty($lastId))
				{
					$matchform =array('id'=>$data1[0]['form_id']);
					$formdataplan = $this->lead_capturing_model->select_records('',$matchform,'','=');
					
					//pr($formdataplan);exit;
					
					if(!empty($formdataplan[0]['assigned_interaction_plan_id']))
					{
						
						$interaction_plan_id = $formdataplan[0]['assigned_interaction_plan_id'];
						
						$contact_id = $lastId;
						
						////////////////////////////////////
						
						if($interaction_plan_id != '')
						{
							$match1 = array('id'=>$interaction_plan_id);
							$plandata = $this->interaction_plans_model->select_records('',$match1,'','=');
							
							//pr($plandata);
							
							$cdata = $plandata[0];
							
							//pr($cdata);exit;
							
							if(!empty($cdata))
							{
								//echo $cdata['plan_name'];
								$data_conv['contact_id'] = $contact_id;
								$data_conv['plan_id'] = $interaction_plan_id;
								$data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
								$data_conv['created_date'] = date('Y-m-d H:i:s');
								$data_conv['log_type'] = '2';
								$data_conv['created_by'] = $this->admin_session['id'];
								$data_conv['status'] = '1';
								
								//pr($data_conv);exit;
								
								$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
								
								$icdata['interaction_plan_id'] = $interaction_plan_id;
								$icdata['contact_id'] = $contact_id;
								
								if($cdata['plan_start_type'] == '1')
								{
									$icdata['start_date'] = date('Y-m-d');
									$icdata['plan_start_type'] = $cdata['plan_start_type'];
									$icdata['plan_start_date'] = $cdata['start_date'];
								}
								else
								{
									if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
										$icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
									else
										$icdata['start_date'] = date('Y-m-d');
									
									$icdata['plan_start_type'] = $cdata['plan_start_type'];
									$icdata['plan_start_date'] = $cdata['start_date'];
								}
								
								$icdata['created_date'] = date('Y-m-d H:i:s');
								$icdata['created_by'] = $this->admin_session['id'];
								$icdata['status'] = '1';
								
								//pr($icdata);exit;
								
								$this->interaction_plans_model->insert_contact_trans_record($icdata);
								
								///////////////////////////////////////////////////////////////////////////
								
								$plan_id = $interaction_plan_id;
											
								////////////////////////////////////////////////////////
								
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
								
								
								///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////
						
								//pr($interaction_list);exit;
								
								if(count($interaction_list) > 0)
								{
									foreach($interaction_list as $row1)
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
										
										$iccdata['interaction_plan_id'] = $plan_id;
										$iccdata['contact_id'] = $contact_id;
										$iccdata['interaction_plan_interaction_id'] = $row1['id'];
										$iccdata['interaction_type'] = $row1['interaction_type'];
										
										if($row1['start_type'] == '1')
										{
											$count = $row1['number_count'];
											$counttype = $row1['number_type'];
											
											///////////////////////////////////////////////////////////////
											
											$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
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
												
												$iccdata['task_date'] = $newtaskdate;
											}
										}
										elseif($row1['start_type'] == '2')
										{
											$count = $row1['number_count'];
											$counttype = $row1['number_type'];
											
											$interaction_id = $row1['interaction_id'];
											
											$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id);
											
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
											
												$iccdata['task_date'] = $newtaskdate;
											}
											
										}
										else
										{
											$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
										}
										
										$sendemaildate = $iccdata['task_date'];
										
										$iccdata['created_date'] = date('Y-m-d H:i:s');
										$iccdata['created_by'] = $this->admin_session['id'];
										
										$this->interaction_model->insert_contact_communication_record($iccdata);
										
										unset($iccdata);
										unset($user_work_off_days1);
										unset($special_days1);
										unset($leave_days1);
										
										/* Email campaign/SMS campaign Insert */
										
										/*$match = array('id'=>$contact_id);
										$userdata = $this->contacts_model->select_records('',$match,'','=');*/
										
										$table = "contact_master as cm";
										$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
										$where = array('cm.id'=>$contact_id);
										$join_tables = array(
															'contact_address_trans as cat'=>'cat.contact_id = cm.id',
														);
										$group_by='cm.id';
										$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,$where);
										
										$agent_name = '';
										if(!empty($row1['assign_to']))
										{
											$table ="login_master as lm";   
											$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
											$join_tables = array('user_master as um'=>'lm.user_id = um.id');
											$wherestring = 'lm.id = '.$row1['assign_to'];
											$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
											if(!empty($agent_datalist))
											{
												if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
													$agent_name = $agent_datalist[0]['admin_name'];
												else
													$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
											}
										}
										
										if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
										{	
											//$row1['id'];
											$match = array('interaction_id'=>$row1['id']);
											$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=');
											if(count($campaigndata) > 0)
											{
												$cdata1['email_campaign_id'] = $campaigndata[0]['id'];
												$cdata1['contact_id'] = $contact_id;
												$emaildata = array(
																'Date'=>date('Y-m-d'),
																'Day'=>date('l'),
																'Month'=>date('F'),
																'Year'=>date('Y'),
																'Day Of Week'=>date("w",time()),
																'Agent Name'=>$agent_name,
																'Contact First Name'=>$userdata['first_name'],
																'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																'Contact Last Name'=>$userdata['last_name'],
																'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																'Contact Company Name'=>$userdata['company_name']
																);
										
												$content = $campaigndata[0]['email_message'];
												$title = $campaigndata[0]['template_subject'];
												
												//pr($emaildata);
												
												$cdata1['template_subject'] = $title;
												$cdata1['email_message'] = $content;
												$pattern = "{(%s)}";
												
												$map = array();
												
												if($emaildata != '' && count($emaildata) > 0)
												{
													foreach($emaildata as $var => $value)
													{
														$map[sprintf($pattern, $var)] = $value;
													}
													$finaltitle = strtr($title, $map);				
													$output = strtr($content, $map);
													
													$cdata1['template_subject'] = $finaltitle;
													$finlaOutput = $output;
													$cdata1['email_message'] = $finlaOutput;
												}
												
												//$emaildata['interaction_id'] = $row1['id'];
												$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
												$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
												//echo $this->db->last_query();
											}
										}
										elseif($row1['interaction_type'] == 3 && count($userdata) > 0)
										{
											$match = array('interaction_id'=>$row1['id']);
											$smscampaigndata = $this->sms_campaign_master_model->select_records('',$match,'','=');
											if(count($smscampaigndata) > 0)
											{
												$cdata1['sms_campaign_id'] = $smscampaigndata[0]['id'];
												$cdata1['contact_id'] = $contact_id;
												$emaildata = array(
																'Date'=>date('Y-m-d'),
																'Day'=>date('l'),
																'Month'=>date('F'),
																'Year'=>date('Y'),
																'Day Of Week'=>date("w",time()),
																'Agent Name'=>$agent_name,
																'Contact First Name'=>$userdata['first_name'],
																'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																'Contact Last Name'=>$userdata['last_name'],
																'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																'Contact Company Name'=>$userdata['company_name']
																);
										
												$content = $smscampaigndata[0]['sms_message'];
												$cdata1['sms_message'] 	= $content;
												$pattern = "{(%s)}";
												
												$map = array();
												
												if($emaildata != '' && count($emaildata) > 0)
												{
													foreach($emaildata as $var => $value)
													{
														$map[sprintf($pattern, $var)] = $value;
													}
													$output = strtr($content, $map);
													
													$finlaOutput = $output;
													$cdata1['sms_message'] = $finlaOutput;
												}
												//$smsdata['interaction_id'] = $row1['id'];
												$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
												$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
											}
											
											
										}
										unset($cdata1);
										/* END */
										
									}
								}
								
								///////////////////////////////////////////////////////////////////////////
								unset($icdata);
								//exit;
								
							
							}
							
						}
						
						////////////////////////////////////
						
					}
						
				}
				
				
				///////////////////////////
				
				
			}
			if(!empty($lastId))
			{
				//user assign lead in user contact transcation
				$cdata6['user_id']=$user;
				$cdata6['contact_id']=$lastId;
				$cdata6['created_by']=$this->admin_session['id'];
				$cdata6['created_date'] = date('Y-m-d H:i:s');		
				$cdata6['status'] = '1';
				$this->contacts_model->insert_user_contact_trans_record($cdata6);
				
				//data for contact address transction 
				$cdatacontact1['address_line1']=$data1[0]['address_data'];	
				if(!empty($cdatacontact1['address_line1']))
				{
					$cdatacontact['address_line1']=explode("{^}",($cdatacontact1['address_line1']));	
					for($i=0;$i<count($cdatacontact['address_line1']);$i++)
					{
						$cdata1['contact_id']=$lastId;
						$cdata1['address_line1']=$cdatacontact['address_line1'][$i];
						$cdata1['status'] = '1';
						$this->contacts_model->insert_address_trans_record($cdata1);
					}
				}
				//data for contact emails transcation table
				$cdataemail1['email_address']=$data1[0]['email_data'];
				if(!empty($cdataemail1['email_address']))
				{	
					$cdataemail['email_address']=explode("{^}",($cdataemail1['email_address']));	
					for($i=0;$i<count($cdataemail['email_address']);$i++)
					{
						$cdata2['contact_id']=$lastId;
						$cdata2['email_address']=$cdataemail['email_address'][$i];
						$cdata2['status'] = '1';
						if($i==0)
							$cdata2['is_default'] = '1';
						$this->contacts_model->insert_email_trans_record($cdata2);
						unset($cdata2);
					}
				}
				//data for contact phone transcation table
				$cdataphone1['phone_no']=$data1[0]['phone_data'];
				if(!empty($cdataphone1['phone_no']))
				{
					$cdataphone['phone_no']=explode("{^}",($cdataphone1['phone_no']));
					for($i=0;$i<count($cdataphone['phone_no']);$i++)
					{
						$cdata3['contact_id']=$lastId;
						$cdata3['phone_no']=$cdataphone['phone_no'][$i];
						$cdata3['status'] = '1';
						if($i==0)
							$cdata3['is_default'] = '1';
						$this->contacts_model->insert_phone_trans_record($cdata3);
						unset($cdata3);
					}
				}
				//data for contact website transcation table
				$cdatacontact1['website_name']=$data1[0]['website_data'];
				if(!empty($cdatacontact1['website_name']))
				{
					$cdatacontact['website_name']=explode("{^}",($cdatacontact1['website_name']));
					for($i=0;$i<count($cdatacontact['website_name']);$i++)
					{
						$cdata4['contact_id']=$lastId;
						$cdata4['website_name']=$cdatacontact['website_name'][$i];
						$cdata4['status'] = '1';
						$this->contacts_model->insert_website_trans_record($cdata4);
					}
				}
				//data for contact document transcation table
				$cdataemail1['file_name']=$data1[0]['file_name'];
				if(!empty($cdataemail1['file_name']))
				{	
					$cdataemail['doc_file']=explode("{^}",($cdataemail1['file_name']));	
					for($i=0;$i<count($cdataemail['doc_file']);$i++)
					{
						$cdata7['contact_id'] = $lastId;
						$cdata7['doc_file'] = $cdataemail['doc_file'][$i];
						$cdata7['created_date'] = date('Y-m-d H:i:s');
						$cdata7['status'] = '1';
						//pr($cdata6);exit;
						$this->contacts_model->insert_doc_trans_record($cdata7);
						unset($cdata7);
					}
				}
				
				//update lead status as assigned
				$cdata5['id']=$leadid;
				$cdata5['status']=1;
				$this->obj->update_record1($cdata5);
			}
			$msg = $this->lang->line('common_assign_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect(base_url('admin/'.$this->viewName.'/'.$formid));
		}
	}	
	 /*
    @Description: Function for Delete Lead Capturing Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete all id of Lead Capturing record want to delete
    @Output: - Lead Capturing list Empty after record is deleted.
    @Date: 19-09-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->obj->delete_record1($id);
			unset($id);
		}
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_record1($array_data[$i]);
		}
		echo 1;
	}

	/*
    @Description: Function to Addign Lead list
	@Author: Mohit Trivedi
    @Input: Array
    @Output: - 
    @Date: 19-09-2014
    */

	public function assign_lead()
	{
		//echo "hiiii";exit;
		$array_data=$this->input->post('myarray');
		$user_id=$this->input->post('user_id');
		$form_id=$this->input->post('form_id');
		$msg='';
		$contact_id='';
		//pr($array_data);exit;
		for($i=0;$i<count($array_data);$i++)
		{
			$contact_id=$array_data[0];
			$check_lead_id=$array_data[$i];
			$match=array('id'=>$check_lead_id,'status'=>1);
			$lead_check = $this->obj->select_records3('',$match,'','=','');
			//pr($lead_check);
			// for insert lead as contact
			if(empty($lead_check))
			{
				$data['id']=$check_lead_id;
				$match1=array('id'=> $data['id']);
				$data1 = $this->obj->select_records1('',$match1,'','=');
				//echo $check_lead_id."-";
				if(!empty($data1[0]['first_name_data']))
				{
					//echo "1-";
					//data for contact master tabel
					$cdataname['first_name']=$data1[0]['first_name_data'];
					$cdataname['last_name']=$data1[0]['last_name_data'];
					$cdatasingleline1['single_line_data']=$data1[0]['single_line_data'];
					$cdatamultiline1['paragraph_data']=$data1[0]['paragraph_data'];
					
					$cdatamultiline1['price_range_from']=$data1[0]['price_range_from'];
					$cdatamultiline1['price_range_to']=$data1[0]['price_range_to'];
					$cdatamultiline1['house_style']=$data1[0]['house_style'];
					$cdatamultiline1['area_of_interest']=$data1[0]['area_of_interest'];
					$cdatamultiline1['square_footage']=$data1[0]['square_footage'];
					$cdatamultiline1['no_of_bedrooms']=$data1[0]['no_of_bedrooms'];
					$cdatamultiline1['no_of_bathrooms']=$data1[0]['no_of_bathrooms'];
					$cdatamultiline1['buyer_preferences_notes']=$data1[0]['buyer_preferences_notes'];
			
					if(!empty($cdataname['first_name']))
					{
						$cdata['first_name']=str_replace("{^}",',',($cdataname['first_name']));
					}
					if(!empty($cdataname['last_name']))
					{
						$cdata['last_name']=str_replace("{^}",',',($cdataname['last_name']));
					}
					if(!empty($cdatasingleline1['single_line_data']))
					{
						$cdatasingleline['single_line_data']=str_replace("{^}",',',($cdatasingleline1['single_line_data']));
						$cdata['notes'].= $cdatasingleline['single_line_data'];
					}
					if(!empty($cdatamultiline1['paragraph_data']))
					{
						$cdatamultiline['paragraph_data']=str_replace("{^}",',',($cdatamultiline1['paragraph_data']));
						$cdata['notes'].= $cdatamultiline['paragraph_data'];
					}
					
					if(!empty($cdatamultiline1['price_range_from']))
						$cdata['price_range_from']=str_replace("{^}",',',($cdatamultiline1['price_range_from']));
					if(!empty($cdatamultiline1['price_range_to']))
						$cdata['price_range_to']=str_replace("{^}",',',($cdatamultiline1['price_range_to']));
					if(!empty($cdatamultiline1['house_style']))
						$cdata['house_style']=str_replace("{^}",',',($cdatamultiline1['house_style']));
					if(!empty($cdatamultiline1['area_of_interest']))
						$cdata['area_of_interest']=str_replace("{^}",',',($cdatamultiline1['area_of_interest']));
					if(!empty($cdatamultiline1['square_footage']))
						$cdata['square_footage']=str_replace("{^}",',',($cdatamultiline1['square_footage']));
					if(!empty($cdatamultiline1['no_of_bedrooms']))
						$cdata['no_of_bedrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bedrooms']));
					if(!empty($cdatamultiline1['no_of_bathrooms']))
						$cdata['no_of_bathrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bathrooms']));
					if(!empty($cdatamultiline1['buyer_preferences_notes']))
						$cdata['buyer_preferences_notes']=str_replace("{^}",',',($cdatamultiline1['buyer_preferences_notes']));
					
					$cdata['created_by'] = $this->admin_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');		
					$cdata['status'] = 1;
					$cdata['created_type']='5';
					$cdata['lead_id']=$form_id;
					$lastId=$this->contacts_model->insert_record($cdata);
					
					//echo $lastId."<br>";
					
					unset($cdata);
					
					$lead_contact_type_list = $this->obj->select_lead_contact_trans_record($form_id);
					if(!empty($lead_contact_type_list[0]))
					{
						for($j=0;$j<count($lead_contact_type_list);$j++)
						{
								$trans_data['contact_id'] = $lastId;
								$trans_data['contact_type_id'] = $lead_contact_type_list[$j]['contact_type_id'];		
								$this->contacts_model->insert_contact_type_record($trans_data);
					
						}
					}
				
				
				
					// Apply plan assignment //
					
					
					if(!empty($data1[0]['form_id']) && !empty($lastId))
					{
						$matchform =array('id'=>$data1[0]['form_id']);
						$formdataplan = $this->lead_capturing_model->select_records('',$matchform,'','=');
						
						//pr($formdataplan);exit;
						
						if(!empty($formdataplan[0]['assigned_interaction_plan_id']))
						{
							
							$interaction_plan_id = $formdataplan[0]['assigned_interaction_plan_id'];
							
							$contact_id = $lastId;
							
							////////////////////////////////////
							
							if($interaction_plan_id != '')
							{
								$match1 = array('id'=>$interaction_plan_id);
								$plandata = $this->interaction_plans_model->select_records('',$match1,'','=');
								
								//pr($plandata);
								
								$cdata = $plandata[0];
								
								//pr($cdata);exit;
								
								if(!empty($cdata))
								{
									//echo $cdata['plan_name'];
									$data_conv['contact_id'] = $contact_id;
									$data_conv['plan_id'] = $interaction_plan_id;
									$data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
									$data_conv['created_date'] = date('Y-m-d H:i:s');
									$data_conv['log_type'] = '2';
									$data_conv['created_by'] = $this->admin_session['id'];
									$data_conv['status'] = '1';
									
									//pr($data_conv);exit;
									
									$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
									
									$icdata['interaction_plan_id'] = $interaction_plan_id;
									$icdata['contact_id'] = $contact_id;
									
									if($cdata['plan_start_type'] == '1')
									{
										$icdata['start_date'] = date('Y-m-d');
										$icdata['plan_start_type'] = $cdata['plan_start_type'];
										$icdata['plan_start_date'] = $cdata['start_date'];
									}
									else
									{
										if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
											$icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
										else
											$icdata['start_date'] = date('Y-m-d');
										
										$icdata['plan_start_type'] = $cdata['plan_start_type'];
										$icdata['plan_start_date'] = $cdata['start_date'];
									}
									
									$icdata['created_date'] = date('Y-m-d H:i:s');
									$icdata['created_by'] = $this->admin_session['id'];
									$icdata['status'] = '1';
									
									//pr($icdata);exit;
									
									$this->interaction_plans_model->insert_contact_trans_record($icdata);
									
									unset($cdata);
									
									///////////////////////////////////////////////////////////////////////////
									
									$plan_id = $interaction_plan_id;
												
									////////////////////////////////////////////////////////
									
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
									
									
									///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////
							
									//pr($interaction_list);exit;
									
									if(count($interaction_list) > 0)
									{
										foreach($interaction_list as $row1)
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
											
											$iccdata['interaction_plan_id'] = $plan_id;
											$iccdata['contact_id'] = $contact_id;
											$iccdata['interaction_plan_interaction_id'] = $row1['id'];
											$iccdata['interaction_type'] = $row1['interaction_type'];
											
											if($row1['start_type'] == '1')
											{
												$count = $row1['number_count'];
												$counttype = $row1['number_type'];
												
												///////////////////////////////////////////////////////////////
												
												$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
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
													
													$iccdata['task_date'] = $newtaskdate;
												}
											}
											elseif($row1['start_type'] == '2')
											{
												$count = $row1['number_count'];
												$counttype = $row1['number_type'];
												
												$interaction_id = $row1['interaction_id'];
												
												$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id);
												
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
												
													$iccdata['task_date'] = $newtaskdate;
												}
												
											}
											else
											{
												$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
											}
											
											$sendemaildate = $iccdata['task_date'];
											
											$iccdata['created_date'] = date('Y-m-d H:i:s');
											$iccdata['created_by'] = $this->admin_session['id'];
											
											$this->interaction_model->insert_contact_communication_record($iccdata);
											
											unset($iccdata);
											unset($user_work_off_days1);
											unset($special_days1);
											unset($leave_days1);
											
											/* Email campaign/SMS campaign Insert */
											
											/*$match = array('id'=>$contact_id);
											$userdata = $this->contacts_model->select_records('',$match,'','=');*/
											
											$table = "contact_master as cm";
											$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
											$where = array('cm.id'=>$contact_id);
											$join_tables = array(
																'contact_address_trans as cat'=>'cat.contact_id = cm.id',
															);
											$group_by='cm.id';
											$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,$where);
											
											$agent_name = '';
											if(!empty($row1['assign_to']))
											{
												$table ="login_master as lm";   
												$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
												$join_tables = array('user_master as um'=>'lm.user_id = um.id');
												$wherestring = 'lm.id = '.$row1['assign_to'];
												$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
												if(!empty($agent_datalist))
												{
													if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
														$agent_name = $agent_datalist[0]['admin_name'];
													else
														$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
												}
											}
										
											if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
											{	
												//$row1['id'];
												$match = array('interaction_id'=>$row1['id']);
												$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=');
												if(count($campaigndata) > 0)
												{
													$cdata1['email_campaign_id'] = $campaigndata[0]['id'];
													$cdata1['contact_id'] = $contact_id;
													$emaildata = array(
																	'Date'=>date('Y-m-d'),
																	'Day'=>date('l'),
																	'Month'=>date('F'),
																	'Year'=>date('Y'),
																	'Day Of Week'=>date("w",time()),
																	'Agent Name'=>$agent_name,
																	'Contact First Name'=>$userdata['first_name'],
																	'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																	'Contact Last Name'=>$userdata['last_name'],
																	'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																	'Contact Company Name'=>$userdata['company_name']
																	);
											
													$content = $campaigndata[0]['email_message'];
													$title = $campaigndata[0]['template_subject'];
													
													//pr($emaildata);
													
													$cdata1['template_subject'] = $title;
													$cdata1['email_message'] = $content;
													$pattern = "{(%s)}";
													
													$map = array();
													
													if($emaildata != '' && count($emaildata) > 0)
													{
														foreach($emaildata as $var => $value)
														{
															$map[sprintf($pattern, $var)] = $value;
														}
														$finaltitle = strtr($title, $map);				
														$output = strtr($content, $map);
														
														$cdata1['template_subject'] = $finaltitle;
														$finlaOutput = $output;
														$cdata1['email_message'] = $finlaOutput;
													}
													
													//$emaildata['interaction_id'] = $row1['id'];
													$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
													$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
													//echo $this->db->last_query();
												}
											}
											elseif($row1['interaction_type'] == 3 && count($userdata) > 0)
											{
												$match = array('interaction_id'=>$row1['id']);
												$smscampaigndata = $this->sms_campaign_master_model->select_records('',$match,'','=');
												if(count($smscampaigndata) > 0)
												{
													$cdata1['sms_campaign_id'] = $smscampaigndata[0]['id'];
													$cdata1['contact_id'] = $contact_id;
													$emaildata = array(
																	'Date'=>date('Y-m-d'),
																	'Day'=>date('l'),
																	'Month'=>date('F'),
																	'Year'=>date('Y'),
																	'Day Of Week'=>date("w",time()),
																	'Agent Name'=>$agent_name,
																	'Contact First Name'=>$userdata['first_name'],
																	'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																	'Contact Last Name'=>$userdata['last_name'],
																	'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																	'Contact Company Name'=>$userdata['company_name']
																	);
											
													$content = $smscampaigndata[0]['sms_message'];
													$cdata1['sms_message'] 	= $content;
													$pattern = "{(%s)}";
													
													$map = array();
													
													if($emaildata != '' && count($emaildata) > 0)
													{
														foreach($emaildata as $var => $value)
														{
															$map[sprintf($pattern, $var)] = $value;
														}
														$output = strtr($content, $map);
														
														$finlaOutput = $output;
														$cdata1['sms_message'] = $finlaOutput;
													}
													//$smsdata['interaction_id'] = $row1['id'];
													$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
													$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
												}
												
												
											}
											unset($cdata1);
											/* END */
											
										}
									}
									
									///////////////////////////////////////////////////////////////////////////
									unset($icdata);
									//exit;
									
								
								}
								
							}
							
							////////////////////////////////////
							
						}
							
					}
					
					
					///////////////////////////
					
					
				}
				if(!empty($lastId))
				{
					$data_conv['contact_id'] = $lastId;
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '3';
					$data_conv['assign_to']=$user_id;
					$data_conv['created_by'] = $this->admin_session['id'];
					$data_conv['status'] = '1';
					$this->obj1->insert_contact_converaction_trans_record($data_conv);
					unset($data_conv);
		
				}

					if(!empty($lastId))
					{
						//user assign lead in user contact transcation
						$cdata6['user_id']=$user_id;
						$cdata6['contact_id']=$lastId;
						$cdata6['created_by']=$this->admin_session['id'];
						$cdata6['created_date'] = date('Y-m-d H:i:s');		
						$cdata6['status'] = '1';
						$this->contacts_model->insert_user_contact_trans_record($cdata6);
						
						//data for contact address transction 
						$cdatacontact1['address_line1']=$data1[0]['address_data'];	
						if(!empty($cdatacontact1['address_line1']))
						{
							$cdatacontact['address_line1']=explode("{^}",($cdatacontact1['address_line1']));	
							for($j=0;$j<count($cdatacontact['address_line1']);$j++)
							{
								$cdata1['contact_id']=$lastId;
								$cdata1['address_line1']=$cdatacontact['address_line1'][$j];
								$cdata1['status'] = '1';
								$this->contacts_model->insert_address_trans_record($cdata1);
							}
						}
						
						//data for contact emails transcation table
						$cdataemail1['email_address']=$data1[0]['email_data'];
						if(!empty($cdataemail1['email_address']))
						{	
							$cdataemail['email_address']=explode("{^}",($cdataemail1['email_address']));	
							for($k=0;$k<count($cdataemail['email_address']);$k++)
							{
								$cdata2['contact_id']=$lastId;
								$cdata2['email_address']=$cdataemail['email_address'][$k];
								$cdata2['status'] = '1';
								if($k==0)
									$cdata2['is_default'] = '1';
								$this->contacts_model->insert_email_trans_record($cdata2);
							}
						}
						//data for contact phone transcation table
						$cdataphone1['phone_no']=$data1[0]['phone_data'];
						if(!empty($cdataphone1['phone_no']))
						{
							$cdataphone['phone_no']=explode("{^}",($cdataphone1['phone_no']));
							for($l=0;$l<count($cdataphone['phone_no']);$l++)
							{
								$cdata3['contact_id']=$lastId;
								$cdata3['phone_no']=$cdataphone['phone_no'][$l];
								$cdata3['status'] = '1';
								if($l==0)
									$cdata3['is_default'] = '1';
								$this->contacts_model->insert_phone_trans_record($cdata3);
							}
						}

						//data for contact website transcation table
						$cdatacontact1['website_name']=$data1[0]['website_data'];
						if(!empty($cdatacontact1['website_name']))
						{
							$cdatacontact['website_name']=explode("{^}",($cdataphone1['phone_no']));
							for($m=0;$m<count($cdatacontact['website_name']);$m++)
							{
								$cdata4['contact_id']=$lastId;
								$cdata4['website_name']=$cdatacontact['website_name'][$m];
								$cdata4['status'] = '1';
								$this->contacts_model->insert_website_trans_record($cdata4);
							}
						}
						
						//data for contact document transcation table
						$cdataemail1['file_name']=$data1[0]['file_name'];
						//pr($cdataemail1['file_name']);exit;
						if(!empty($cdataemail1['file_name']))
						{	
							$cdataemail['doc_file']=explode("{^}",($cdataemail1['file_name']));	
							for($s=0;$s<count($cdataemail['doc_file']);$s++)
							{
								$cdata7['contact_id'] = $lastId;
								$cdata7['doc_file'] = $cdataemail['doc_file'][$s];
								$cdata7['created_date'] = date('Y-m-d H:i:s');
								$cdata7['status'] = '1';
								//pr($cdata6);exit;
								$this->contacts_model->insert_doc_trans_record($cdata7);
								//echo $this->db->last_query();exit;
								unset($cdata7);
							}
						}
						//update lead status as assigned
						$cdata5['id']=$data['id'];
						$cdata5['status']='1';
						$this->obj->update_record1($cdata5);
					}
				$msg=1;
			}
			else
			{
				/*$this->contacts_model->delete_table_user_contact_record($array_data[$i]);
				$cudata['contact_id'] =$array_data[$i];
				$cudata['user_id'] = $user_id;   
				$cudata['created_by'] = $this->admin_session['id'];
				$cudata['created_date'] = date('Y-m-d H:i:s');		
				$cudata['status'] = '1';
				$this->contacts_model->insert_user_contact_trans_record($cudata);
				unset($cudata);
				$msg=0;*/
			}
			
		}
		//exit;
		$lead_id = $contact_id;
		$pagingid = $this->obj->getemailpagingid($lead_id);
		$data_temp['msg'] = $msg;
		$data_temp['page'] = $pagingid;
		echo json_encode($data_temp);
	}
	
	public function contact_details()
	{
		$lead_id = $this->input->post('lead_id');

		$table = "lead_data as ld";
        $fields = array('ld.*,lm.first_name,lm.last_name,lm.phone_field,lm.email_field,lm.single_line_field,lm.paragraph_field,lm.address_field,lm.date_field,lm.date_field,lm.website_field,lm.price_range,lm.bedrooms
        	,lm.bathrooms,lm.area_of_interest as area_of_interest_lead,lm.buyer_preferences_notes as buyer_preferences_notes_lead,lm.house_style as house_style_lead,lm.square_footage as square_footage_lead,lm.file,lm.bathrooms,lm.id as leadid');
		$join_tables = array('lead_master as lm' => 'ld.form_id = lm.id');
		$match3 = array('ld.id'=> $lead_id);
		$data['datalist'] = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match3,'=','','','','asc');
		


		//$match1=array('id'=> $lead_id);
		//$data['datalist'] = $this->obj->select_records1('',$match1,'','=');
		//echo $this->db->last_query();
		//pr($data); exit;
		$this->load->view($this->user_type.'/'.$this->viewName.'/view',$data);
	}

}