<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       
        check_admin_login();
		$this->load->model('interaction_model'); 
		$this->load->model('interaction_plans_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('mail_blast_model');
		$this->load->model('contact_masters_model'); 
		$this->load->model('dashboard_model'); 
		$this->load->model('calendar_model'); 
		$this->load->model('contacts_model'); 
		$this->load->model('task_model'); 
		$this->load->model('contact_conversations_trans_model'); 
		$this->load->model('envelope_library_model'); 
		$this->load->model('label_library_model'); 
		$this->load->model('letter_library_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_signature_model');
		$this->load->model('email_library_model');
		$this->load->model('sms_texts_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->obj = $this->dashboard_model;
		$this->obj1 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		$this->load->library('Twilio');
	}

    public function index()
    {
		
        $doc_session_array = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
        ($doc_session_array['active'] == true) ? $this->display_dashbord() : redirect('admin/login');
    }
	
    public function display_dashbord()
    {
		$this->load->model('dashboard_model'); 
        $data['msg'] = ($this->uri->segment(3) == 'msg') ? $this->uri->segment(4) : '';
		
		$id=$this->admin_session['id'];
		//$date_next = $this->input->post('date_val');
		$current = $this->input->post('date');
		//echo $current;
		//$dd=date('Y-m-d',strtotime($current));
		//echo $dd;exit;
		if(empty($current))
		{
			$now_date= 
			$now_date1=date('Y-m-d H:i:s');
		}
		else
		{
			$now_date=date('Y-m-d',strtotime($current));
			$c_time=date('H:i:s');
			$now_date1=date('Y-m-d H:i:s',strtotime($current." ".$c_time));
			//$now_date1=date('Y-m-d H:i:s',strtotime($current));
		}
		
		//////////////////////////// Personal Touches Notification ///////////////////
		$table = "interaction_plan_contact_personal_touches as ipcp";
		$fields = array('ipcp.id','ipcp.task','ipcp.contact_id','iptm.name','ipcp.followup_date','ipcp.is_done','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ipcp.created_date');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = ipcp.interaction_type',
							'contact_master as cm' => 'cm.id = ipcp.contact_id'
							);
		$group_by='ipcp.id';

		//$where=array('ipcp.created_by'=>$id,'ipcp.followup_date'=>"'".$now_date."'");
		$where = 'ipcp.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5")) AND ipcp.followup_date = "'.$now_date.'"';
		$data['personale_touches'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		//echo $this->db->last_query();
		//ipcp.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5")
		//pr($data['personale_touches']);exit;
		//////////////////////////// End Personal Touches Notification ///////////////////
		
		/////////////////////////// Tasks From Task Menu Notification ///////////////////////
		$table = "task_master as tm";
		$fields = array('tm.id','tm.task_name','tm.task_date','tm.is_email','tm.email_time_before','tm.email_time_type','tm.is_popup','tm.popup_time_before','tm.popup_time_type','tm.created_date','tm.reminder_email_date ','tm.reminder_popup_date','tm.is_close','group_concat(CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) ORDER BY um.first_name separator \',\') as user_name','group_concat(CONCAT_WS(" ",lm.admin_name) ORDER BY lm.admin_name separator \',\') as admin_name');
		$join_tables = array(
							'task_user_transcation as tut' => 'tut.task_id  = tm.id',
							'login_master as lm' => 'lm.id = tut.user_id',
							'user_master as um' => 'um.id = lm.user_id'
							
							);
		$group_by='tm.id';
		$where = 'tm.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5"))';
		
		$task_notification = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		/*echo $this->db->last_query();
		pr($task_notification);exit;*/
		
		$i=0;
		$counter=0;
		if(!empty($task_notification))
				{
				//pr($task_notification); exit;
					$k='';
					for($j=0;$j < count($task_notification);$j++)
					{
						
						if($task_notification[$j]['task_date'] == $now_date)
						{
										$data['today_task_name'][$i]=$task_notification[$j]['task_name'];
										$data['today_task_user_name'][$i]=$task_notification[$j]['user_name'];
										$data['today_task_admin_name'][$i]=$task_notification[$j]['admin_name'];
										$data['today_task_data'][$i]=$task_notification[$j]['task_date'];	
										$k = 1;
						}
						if($k == 1)
							{$i++;}
							
						$c = 0;
						if(!empty($task_notification[$j]['is_popup']))
						{
							if(!empty($task_notification[$j]['popup_time_before']))
							{
								if(!empty($task_notification[$j]['popup_time_type']) && $task_notification[$j]['popup_time_type']=='1')
								{		
									$now_datetime=date($this->config->item('log_date_format'));
									$task_date1=date($this->config->item('log_date_format'),strtotime($task_notification[$j]['task_date']));
									if($task_date1 >= $now_date1  && $task_notification[$j]['reminder_popup_date'] <= $now_date1)
									{
										$data['task_name_popup'][$counter]=$task_notification[$j]['task_name'];
										$data['user_name_popup'][$counter]=$task_notification[$j]['user_name'];
										$data['admin_name_popup'][$counter]=$task_notification[$j]['admin_name'];
										$data['task_data'][$counter]=$task_notification[$j]['task_date'];
										$data['created_by'][$counter]=$task_notification[$j]['id'];
										$data['is_close'][$counter]=$task_notification[$j]['is_close'];
										$c = 1;
										
									}
								}
								elseif(!empty($task_notification[$j]['popup_time_type']) && $task_notification[$j]['popup_time_type']=='2')
								{
									
									$newtaskdate1 = date('Y-m-d',strtotime($task_notification[$j]['reminder_popup_date']));
								
									if($newtaskdate1 == $now_date)
									{
										$data['task_name_popup'][$counter]=$task_notification[$j]['task_name'];
										$data['user_name_popup'][$counter]=$task_notification[$j]['user_name'];
										$data['admin_name_popup'][$counter]=$task_notification[$j]['admin_name'];
										$data['task_data'][$counter]=$task_notification[$j]['task_date'];
										$data['created_by'][$counter]=$task_notification[$j]['id'];
										$data['is_close'][$counter]=$task_notification[$j]['is_close'];
										
										$c = 1;
									}
								}
								if($c == 1)
									$counter++;
							}
						}		
					}
				}
		
		///////////////////////////End  Tasks From Task Menu Notification ///////////////////////
		$data['now_date']=$now_date;
        $data['now_date1']=$now_date1;
		
		
		//////////////////////////Email Campaing /////////////////////////////////////////
		
		
		$table = " email_campaign_master as ecm";
		$fields = array('ecm.id','ecm.template_name_id','ecm.email_send_date','etm.template_name');
		$join_tables = array(
							'email_template_master as etm' => 'etm.id = ecm.template_name_id'
							);
		$group_by='ecm.id';

		//$where = array('ecm.created_by'=>$id,'ecm.email_send_date'=>"'".$now_date."'");
		$where = 'ecm.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5")) AND ecm.email_send_date = "'.$now_date.'"';
		$data['email_campaing'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		//pr($data['email_campaing']);exit;
		//////////////////////////End Email Campaing /////////////////////////////////////////
		
		
		//////////////////////////SMS Campaing /////////////////////////////////////////
		
		
		$table = "sms_campaign_master as scm";
		$fields = array('scm.id','scm.template_name','scm.sms_send_date','sttm.template_name as temp_name');
		$join_tables = array(
							'sms_text_template_master as sttm' => 'sttm.id = scm.template_name'
							);
		$group_by='scm.id';

		//$where=array('scm.created_by'=>$id,'scm.sms_send_date'=>"'".$now_date."'");
		$where = 'scm.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5")) AND scm.sms_send_date = "'.$now_date.'"';
		$data['sms_campaing'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		//pr($data['sms_campaing']);exit;
		//////////////////////////End SMS Campaing /////////////////////////////////////////
		
		///////////////////////// calendar events open in Popup box////////////////////
		$table = "calendar_master as cm";
		$fields = array('cm.*','group_concat(CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) ORDER BY um.first_name separator \',\') as user_name','group_concat(CONCAT_WS(" ",lm.admin_name) ORDER BY lm.admin_name separator \',\') as admin_name','cm.created_by');
		$join_tables = array(
                            'login_master as lm' => 'lm.id = cm.created_by',
                            'user_master as um' => 'um.id = lm.user_id'
                            );
		$group_by='cm.id';

		//$where = array('cm.created_by'=> $id,'cm.is_popup'=> "'1'");
		$where = 'cm.created_by IN (SELECT id from login_master where (user_type = "2" OR user_type = "5")) AND cm.is_popup = "1"';
		$calendar_events =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
	//	echo $this->db->last_query();
	//pr($calendar_events);exit;
		$i=0;
		$counter=0;
		if(!empty($calendar_events))
		{
			//pr($calendar_events);exit;
			for($j=0;$j < count($calendar_events);$j++)
			{
				$c = 0;
				if(!empty($calendar_events[$j]['is_popup']) && $calendar_events[$j]['event_inserted_type'] != 2)
				{
					if(!empty($calendar_events[$j]['popup_time_before']))
					{
						if(!empty($calendar_events[$j]['popup_time_type']) && $calendar_events[$j]['popup_time_type']=='1')
						{
							$now_datetime=date($this->config->item('log_date_format'));
							$task_date1=date($this->config->item('log_date_format'),strtotime($calendar_events[$j]['start_date']." ".$calendar_events[$j]['start_time']));
							if($task_date1 >= $now_date1  && $calendar_events[$j]['reminder_popup_date'] <= $now_date1)
							{
								$data['calendar_name_popup'][$counter]=$calendar_events[$j]['event_title'];
								$data['calendar_user_name_popup'][$counter]=$calendar_events[$j]['user_name'];
								$data['calendar_admin_name_popup'][$counter]=$calendar_events[$j]['admin_name'];
								$data['calendar_data'][$counter]=$calendar_events[$j]['start_date'];
								$data['calendar_created_by'][$counter]=$calendar_events[$j]['created_by'];
								$data['calendar_is_close'][$counter]=$calendar_events[$j]['is_close'];
								$data['calendar_id'][$counter]=$calendar_events[$j]['id'];
								$c = 1;
								
							}
						}
						elseif(!empty($calendar_events[$j]['popup_time_type']) && $calendar_events[$j]['popup_time_type']=='2')
						{
							
							$newtaskdate1 = date('Y-m-d',strtotime($calendar_events[$j]['reminder_popup_date']));
							
							if($newtaskdate1 == $now_date)
							{
								$data['calendar_name_popup'][$counter]=$calendar_events[$j]['event_title'];
								$data['calendar_user_name_popup'][$counter]=$calendar_events[$j]['user_name'];
								$data['calendar_admin_name_popup'][$counter]=$calendar_events[$j]['admin_name'];
								$data['calendar_data'][$counter]=$calendar_events[$j]['start_date'];
								$data['calendar_created_by'][$counter]=$calendar_events[$j]['created_by'];
								$data['calendar_is_close'][$counter]=$calendar_events[$j]['is_close'];
								$data['calendar_id'][$counter]=$calendar_events[$j]['id'];
								$c = 1;
							}
						}
						if($c == 1)
							$counter++;
					}
				}
			}
		}		
		
		//pr($data);exit;
		//////////////////////// End calendar events ////////////////////////
		
		///////////////NEW LEADS. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		$dt = date('H');
		if($dt >= '12')
			$data['time_message'] = 'Good Afternoon';
		else
			$data['time_message'] = 'Good Morning';
		
		$table = "contact_listing_last_seen as clls";
		$match = array('login_id'=>$this->admin_session['id']);
		$admin_data = $this->contacts_model->getmultiple_tables_records($table,'','','','',$match);
		
		
		$table = "contact_master as cm";
		$fields = array('cm.*');  
        $dt = date('Y-m-d',strtotime($now_date));
		
		$current_date = date('Y-m-d');
		$new_data['date'] = $dt;
		$this->session->set_userdata('current_date_session',$new_data);
        
		//pr($admin_data);exit;
		
		$table1 = "error_data_master as et";
		$where1 = array('et.status'=>1,'et.created_by'=>$this->admin_session['id']);
		$data['error_count'] = $this->interaction_plans_model->getmultiple_tables_records($table1,'','','','',$where1,'=','','','et.id','desc','','','','1');
		

	    if(!empty($admin_data))
		{
			$data['contact_last_seen'] = $admin_data[0]['contact_last_seen'];
			$where = 'DATE_FORMAT(cm.created_date,"%Y-%m-%d") = "'.date('Y-m-d').'"';
			
		}
		else
		$where ='DATE_FORMAT(cm.created_date,"%Y-%m-%d")=' ."'".date('Y-m-d')."'";
		$contact_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','',$group_by,$where,'','1');
		//pr($contact_count);exit;
		
		$data['contact_count'] = $contact_count;
		

		//Manual lead contact
		if(!empty($admin_data))
		{
			$data['manual_contact_last_seen'] = $admin_data[0]['manual_contact_last_seen'];
			$where = 'created_type = "1" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "'.$admin_data[0]['manual_contact_last_seen'].'"';
		}
		else
			$where = 'created_type = "1" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "0000-00-00 00:00:00"';
			$contact_manual_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','',$group_by,$where,'','1');
			//pr($contact_count);exit;
			$data['contact_manual_count'] = $contact_manual_count;


		//Joomala lead contact
		if(!empty($admin_data))
		{
			$data['joomla_lead_last_seen'] = $admin_data[0]['joomla_lead_last_seen'];
			$where = 'created_type = "6" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "'.$admin_data[0]['joomla_lead_last_seen'].'"';
		}
		else
			$where = 'created_type = "6" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "0000-00-00 00:00:00"';
			$joomla_lead_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','',$group_by,$where,'','1');
		//pr($contact_count);exit;
		$data['joomla_lead_count'] = $joomla_lead_count;

		//Joomala lead contact
		if(!empty($admin_data))
		{
			$data['form_lead_last_seen'] = $admin_data[0]['form_lead_last_seen'];
			$where = 'created_type = "5" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "'.$admin_data[0]['form_lead_last_seen'].'"';
		}
		else
			$where = 'created_type = "5" and DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "0000-00-00 00:00:00"';
			$form_lead_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','',$group_by,$where,'','1');
		//pr($contact_count);exit;
		$data['form_lead_count'] = $form_lead_count;

		$table = "property_listing_master as cm";
		if(!empty($admin_data))
		{
			$data['listing_last_seen'] = $admin_data[0]['listing_last_seen'];
			$where = 'DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "'.$admin_data[0]['listing_last_seen'].'"';
		}
		else
			$where = 'DATE_FORMAT(cm.created_date,"%Y-%m-%d %H:%i:%s") > "0000-00-00 00:00:00"';


		$property_listing_count =$this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','',$group_by,$where,'','1');
		//echo $this->db->last_query();exit;
		$data['property_listing_count'] = $property_listing_count;
		///////////////END NEW LEADS. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		$table = "task_master as tm";
		$fields = array('tm.id');
		$join_tables = array(
			'task_user_transcation as tut' => 'tut.task_id  = tm.id',
		);
		
		$group_by='tm.id';
		$where=array('DATE_FORMAT(tm.task_date,"%Y-%m-%d")'=>"'$dt'",'tm.is_completed'=>"'0'");
		$task_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		$data['task_count'] = !empty($task_count)?$task_count:0;
        if(strtotime($dt) <= strtotime($current_date))
		{
			$where=array('DATE_FORMAT(tm.task_date,"%Y-%m-%d") < '=>" '$current_date'",'tm.is_completed'=>"'0'");
			$task_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
			//echo $this->db->last_query();exit;
		}
		$data['task_overdue_count'] = !empty($task_overdue_count)?$task_overdue_count:0;
		
		///////////////END TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////TELEPHONE TASK (CALL). 21-10-2014 SANJAY MOGHARIYA//////////////////////
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('contact_master as cm' => 'cm.id = ipccp.contact_id');
		$fields = array('ipccp.id');
				
		$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>"'4'",'ipccp.is_done'=>"'0'");
		$call_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		$data['call_count'] = !empty($call_count)?$call_count:0;
		//echo $this->db->last_query();exit;
		if(strtotime($dt) <= strtotime($current_date))
		{
			$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") < '=>"'$current_date'",'ipccp.interaction_type'=>"'4'",'ipccp.is_done'=>"'0'");
			$call_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		}
		
		$data['call_overdue_count'] = !empty($call_overdue_count)?$call_overdue_count:0;
		//echo $this->db->last_query();exit;

		///////////////END TELEPHONE TASK (CALL). 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////EMAIL TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('contact_master as cm' => 'cm.id = ipccp.contact_id');
		$fields = array('ipccp.id,ipccp.task_date');
		 
		$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>'6','ipccp.is_done'=>"'0'");
		$email_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		//echo $this->db->last_query();exit;
		$data['email_count'] = !empty($email_count)?$email_count:0;
		if(strtotime($dt) <= strtotime($current_date))
		{
			$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") < '=>"'$current_date'",'ipccp.interaction_type'=>'6','ipccp.is_done' => "'0'");
			$email_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		}
		//echo $this->db->last_query();
		$data['email_overdue_count'] = !empty($email_overdue_count)?$email_overdue_count:0;
		///////////////END EMAIL TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////SMS TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		$table = "interaction_plan_contact_communication_plan ipccp";
		$join_tables = array('contact_master as cm' => 'cm.id = ipccp.contact_id');
		$fields = array('ipccp.id,ipccp.task_date');
		
		$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>"'3'",'ipccp.is_done'=>"'0'");
		$sms_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		$data['sms_count'] = !empty($sms_count)?$sms_count:0;
		if(strtotime($dt) <= strtotime($current_date))
		{
			$where=array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") < '=>"'$current_date'",'ipccp.interaction_type'=>"'3'",'ipccp.is_done'=>"'0'");
			$sms_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		}
		
		$data['sms_overdue_count'] = !empty($sms_overdue_count)?$sms_overdue_count:0;
		///////////////END SMS TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////Label/Letter/Envelope TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		//////NEEDS TO CHANGE QUERY (AND/OR)//////
		$table = "interaction_plan_contact_communication_plan ipccp";
		$join_tables = array('contact_master as cm' => 'cm.id = ipccp.contact_id');
		$fields = array('ipccp.id,ipccp.task_date');
        //$match = array();
		$where = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') = '$dt' AND ipccp.is_done = '0' AND (ipccp.interaction_type = '1' OR ipccp.interaction_type = '2' OR ipccp.interaction_type = '5')";
		$group_by = 'ipccp.interaction_plan_interaction_id';
		$task_lel_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','',$group_by,$where,'','1');
		//echo $this->db->last_query();exit;
		$data['task_lel_count'] = !empty($task_lel_count)?$task_lel_count:0;
		
		//$where=array('created_by'=> $id,'DATE_FORMAT(task_date,"%Y-%m-%d") < '=>"'$dt'",'is_done' => "'0'",'interaction_type'=>"'1'",'interaction_type'=>"'2'",'interaction_type'=>"'5'");
		if(strtotime($dt) <= strtotime($current_date))
		{
			$where = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') < '$current_date' AND ipccp.is_done = '0' AND (ipccp.interaction_type = '1' OR ipccp.interaction_type = '2' OR ipccp.interaction_type = '5')";
			$task_lel_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','',$group_by,$where,'','1');
		}
		$data['task_lel_overdue_count'] = !empty($task_lel_overdue_count)?$task_lel_overdue_count:0;
                ///////////////END Label/Letter/Envelope TASK. 21-10-2014 SANJAY MOGHARIYA//////////////////////
		
		///////////////To-do TASK. 14-11-2014 SANJAY Chabhadiya//////////////////////
		//////NEEDS TO CHANGE QUERY (AND/OR)//////
		$table = "interaction_plan_contact_communication_plan ipccp";
		$join_tables = array('contact_master as cm' => 'cm.id = ipccp.contact_id');
		$fields = array('ipccp.id,ipccp.task_date');
        //$match = array();
		$where = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') = '$dt' AND ipccp.is_done = '0' AND ipccp.interaction_type = '7'";
		$to_do_task_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		
		$data['to_do_task_count'] = !empty($to_do_task_count)?$to_do_task_count:0;
		if(strtotime($dt) <= strtotime($current_date))
		{
			$where = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') < '$current_date' AND ipccp.is_done = '0' AND ipccp.interaction_type = '7'";
			$to_do_task_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where,'','1');
		}
		$data['to_do_task_overdue_count'] = !empty($to_do_task_overdue_count)?$to_do_task_overdue_count:0;
                ///////////////END To-Do TASK. 14-11-2014 SANJAY Chabhadiya//////////////////////
	
       
	   	$fields = array('id,admin_pic');
		$match = array('id'=>$this->admin_session['id']);
		$data['prifile_pic'] = $this->admin_model->get_user($fields,$match,'','=');
		//pr($data['prifile_pic']);exit;
	  // pr($data);exit;         
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/ajax_list',$data);
		}
		else
		{
			$data['main_content'] = "admin/home/dashboard";
			$this->load->view('admin/include/template',$data);
		}	
    }
	public function popup_changes()
	{
		$myarray = $this->input->post('myarray'); 
		$myarray_cal = $this->input->post('myarray_cal'); 

		$data['is_close']='1';
		for($i=0;$i<count($myarray);$i++)
		{
			$data['id']=$myarray[$i];
			$data['is_close']='1';
			$this->obj->update_task($data);	
		}
		
		/////// calendar popup close///
		$cdata['is_close']='1';
		for($j=0;$j<count($myarray_cal);$j++)
		{
			$cdata['id']=$myarray_cal[$j];
			$cdata['is_close']='1';
			$this->calendar_model->update_record($cdata);	
		}
		
	}
        
	/*
		@Description: Function for Get All Task List
		@Author     : Sanjay Moghariya
		@Input      : Search value or null
		@Output     : all Task list
		@Date       : 22-10-2014
	*/
    public function daily_task()
	{
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data1');
		}
		$data['sortfield']		= 'id';
		$data['sortby']		= 'desc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data1');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];
					}
			}
		}
		if(!empty($searchopt))
		{
			//$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($date1) && !empty($date2))
		{
			$date1 = $this->input->post('date1');
			$date2 = $this->input->post('date2');
			$data['date1'] = $date1;
			$data['date2'] = $date2;	
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/daily_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$curr_date = date('Y-m-d');
		
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$curr_date = $session_data['date'];
		else
			$curr_date = date('Y-m-d');
		$data['dt'] = $curr_date;
		if(!empty($searchtext))
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,$searchtext, $data['sortfield'],$data['sortby']);
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '',$searchtext, $data['sortfield'],$data['sortby'],'1');
		}
		else
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,'', $data['sortfield'],$data['sortby']);
			/*echo $this->db->last_query();
			print_r($data['datalist']);exit;*/
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '','', $data['sortfield'],$data['sortby'],'1');
		}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data1 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data1', $dashboard_task_sortsearchpage_data1);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/task_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/task_list';
			$this->load->view('admin/include/template',$data);
		}
	}
        
	/*
		@Description: Function for Get All Task List
		@Author     : Sanjay Moghariya
		@Input      : Search value or null
		@Output     : all Task list
		@Date       : 22-10-2014
	*/
	public function form_lead_list()
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$current_date = $this->input->post('new_contact');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_formlead_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']		= 'desc';
		$searchsort_session = $this->session->userdata('dashboard_formlead_sortsearchpage_data');

		if(!empty($sortfield) && !empty($sortby))
		{
			$data['sortfield'] = $sortfield;
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/form_lead_list");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		if(!empty($current_date))
		{
            $icdata['login_id'] = $this->admin_session['id'];

			$icdata['form_lead_last_seen'] = date('Y-m-d H:i:s');
			$this->contacts_model->update_last_seen($icdata);
			//$searchtext = $current_date;
			
			$dashboard_formlead_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($searchtext)?($searchtext):'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                        'current_date' => !empty($current_date)?$current_date:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		
		$this->session->set_userdata('dashboard_formlead_sortsearchpage_data', $dashboard_formlead_sortsearchpage_data);

		if(!empty($current_date))
				redirect('admin/'.$this->viewName.'/form_lead_list');
		}
                
                $data['current_date'] = 0;
                if(!empty($searchsort_session['current_date']) && $searchsort_session['current_date'] != '0000-00-00 00:00:00')
                    $data['current_date'] = $searchsort_session['current_date'];
		
                $table = "contact_master as cm";
                $group_by='cm.id';
		$fields = array('ls.id as form_id,cm.id,cm.is_subscribe','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ld.created_ip','ld.domain_name','ld.created_date as filled_date','ls.form_title','ld.id as lead_id,cm.created_date');
                $join_tables = array(
			'lead_data as ld' => 'ld.id = cm.lead_id',
			'lead_master as ls' => 'ls.id = ld.form_id',
			
        );
        if(!empty($searchtext))
        {
           // $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'phone_no'=>$searchtext,'tag'=>$searchtext,'csm.name'=>$searchtext,'cm.company_name'=>$searchtext);
           
		  $cre="cm.created_type = '5' and";   

          $where = $cre.' (CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.first_name,cm.last_name) LIKE "%'.$searchtext.'%" OR ld.created_ip LIKE "%'.$searchtext.'%" OR ld.domain_name LIKE "%'.$searchtext.'%" OR ls.form_title LIKE "%'.$searchtext.'%"';

          if(date('Y-m-d H:i:s', strtotime($searchtext)) == $searchtext || $searchtext == '0000-00-00 00:00:00')
			{
					 $where .= ' OR cm.created_date > "'.date('Y-m-d H:i:s',strtotime($searchtext)).'"';

			}	
			$where .= ')';
			$data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
			//echo $this->db->last_query();
			
            $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
           // echo $this->db->last_query();exit;
            /////////////

        }
        else
        {
        	$where = "cm.created_type = '5'";
			$data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
			//pr($data['datalist'] );
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
			//echo $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
			

        }

		/*$curr_date = date('Y-m-d');
		if(!empty($searchtext))
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,$searchtext, $data['sortfield'],$data['sortby']);
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '',$searchtext, $data['sortfield'],$data['sortby'],'1');
		}
		else
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,'', $data['sortfield'],$data['sortby']);
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '','', $data['sortfield'],$data['sortby'],'1');
		}*/
		
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_formlead_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                        'current_date' => !empty($data['current_date'])?$data['current_date']:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		
		$this->session->set_userdata('dashboard_formlead_sortsearchpage_data', $dashboard_formlead_sortsearchpage_data);

		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/form_lead_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/form_lead_list';
			$this->load->view('admin/include/template',$data);
		}
	}
    
    public function iscompleted()
	{
		//$admin_id = $this->admin_session['id'];	
		$post_val = $this->input->post('selectedvalue');
		//update user task
		$match=array('task_id'=>$post_val);
		$fields=array('id','task_id','user_id','is_completed');
		$userlist = $this->task_model->select_records1($fields,$match,'','=');
		if(!empty($userlist))
		{
			foreach($userlist as $row)
			{
				if(empty($row['is_completed']) && $row['is_completed']== '0')
				{
					$udata['is_completed'] = '1';
					$udata['completed_date'] = date('Y-m-d h:i:s');
					$udata['task_id'] = $post_val;
					$udata['user_id'] = $row['user_id'];
					$this->task_model->update_task($udata);
				}
				
			}
				
		}
		
		//Update contact conversation
		$match=array('task_id'=>$post_val);
		$fields=array('id','task_id','contact_id','is_completed_task');
		$contactlist = $this->contact_conversations_trans_model->select_records($fields,$match,'','=');
		if(!empty($contactlist))
		{
			foreach($contactlist as $row)
			{
				if(empty($row['is_completed_task']) && $row['is_completed_task']== '0')
				{
					$cdata['is_completed_task'] = '1';
					$cdata['id'] = $row['id'];
					$this->contact_conversations_trans_model->update_record($cdata);
				}
			}
				
		}
		//update user task
		$match=array('id'=>$post_val);
		$alluser = $this->task_model->select_records('',$match,'','=');
		if($post_val!='')
		{
			if(!empty($alluser[0]) && $alluser[0]['is_completed']== '0')
			{
				$data['is_completed'] = '1';
			}
			else
			{
				$data['is_completed'] = '0';
			}
		}
		$data['id'] = $post_val;
		$this->task_model->update_record($data);
		
                //$pagid = $this->obj->gettaskpagingid($post_val);
                
                $searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data1');
                
                if(!empty($searchsort_session['uri_segment']))
                    $pagingid = $searchsort_session['uri_segment'];
                else
                    $pagingid = 0;
                
                $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
                $total_rows = $searchsort_session['total_rows'];
                if($delete_all_flag == 1)
                {
                    $total_rows -= $cnt;
                    if($pagingid*$perpage > $total_rows) {
                        if($total_rows % $perpage == 0)
                        {
                            $pagingid -= $perpage;
                        }
                    }
                } else {
                    if($total_rows % $perpage == 1)
                        $pagingid -= $perpage;
                }
                if($pagingid < 0)
                    $pagingid = 0;
		echo $pagingid;
		//echo $pagid;
	}

	 /*
		@Description: Function for Delete Task
		@Author     : Sanjay Moghariya
		@Input      : Delete all id of Task record want to delete
		@Output     : Task list Empty after record is deleted.
		@Date       : 22-10-2014
	*/
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
				$this->task_model->delete_record($id);
				$this->task_model->delete_user_task($id);
				$this->contact_conversations_trans_model->delete_contact_trans_record($id);
				unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
					$this->task_model->delete_record($array_data[$i]);
					$this->task_model->delete_user_task($array_data[$i]);
					$this->contact_conversations_trans_model->delete_contact_trans_record($id);
					$delete_all_flag = 1;
					$cnt++;
			}
		}

		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data1');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}

		if($pagingid < 0)
			$pagingid = 0;
		//echo 1;
		echo $pagingid;
	}
	public function ajax_delete_all1()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
				$this->task_model->delete_record($id);
				$this->task_model->delete_user_task($id);
				$this->contact_conversations_trans_model->delete_contact_trans_record($id);
				unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
					$this->task_model->delete_record($array_data[$i]);
					$this->task_model->delete_user_task($array_data[$i]);
					$this->contact_conversations_trans_model->delete_contact_trans_record($id);
					$delete_all_flag = 1;
					$cnt++;
			}
		}

		$searchsort_session = $this->session->userdata('dashboard_formlead_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}

		if($pagingid < 0)
			$pagingid = 0;
		//echo 1;
		echo $pagingid;
	}
	
	
	public function new_leads()
	{
            echo "Hi";exit;
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']		= 'desc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/daily_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$curr_date = date('Y-m-d');
		if(!empty($searchtext))
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,$searchtext, $data['sortfield'],$data['sortby']);
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '',$searchtext, $data['sortfield'],$data['sortby'],'1');
		}
		else
		{
			$data['datalist'] = $this->obj->dashboard_task_list($curr_date, $config['per_page'], $uri_segment,'', $data['sortfield'],$data['sortby']);
			$config['total_rows'] = $this->obj->dashboard_task_list($curr_date, '', '','', $data['sortfield'],$data['sortby'],'1');
		}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		
		$this->session->set_userdata('dashboard_task_sortsearchpage_data', $dashboard_task_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/new_leads_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/new_leads_list';
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for Get Email Task List
		@Author     : Sanjay Chabhadiya
		@Input      : Interaction Type
		@Output     : Email Task list
		@Date       : 8-11-2014
	*/
	
	public function email_task($flag='')
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		
		$data['dt'] = $dt;
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data3');
		}
		$data['sortfield']		= 'ipccp.task_date';
		$data['sortby']		= 'asc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data3');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'ipccp.task_date';
				$sortby = 'asc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];
				}
			}
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/email_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$table = "interaction_plan_contact_communication_plan ipccp";
		$fields = array('ipccp.id,ipccp.contact_id,ecrt.template_subject,ecrt.email_message,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,ipccp.task_date,ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipim.start_type as i_start_type,ipccp1.is_done,cm.is_subscribe,ecrt.is_email_exist,cet.is_default,etm.template_name');
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'contact_master as cm jointype direct'=>'cm.id = ipccp.contact_id',
							 'email_campaign_master as ecm'=>'ecm.interaction_id = ipim.id',
							 'email_campaign_recepient_trans as ecrt'=>'ecrt.email_campaign_id= ecm.id AND ecrt.contact_id = ipccp.contact_id',
							 '(select * from contact_emails_trans where is_default = "1") as cet'=>'cet.contact_id = cm.id',
							 'email_template_master as etm'=>'etm.id = ecm.template_name_id',
							 );
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") <= '=>"'$dt'",'ipccp.interaction_type'=>"'6'",'ipccp.is_done'=>"'0'");
		else
			$wherestring = array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>"'6'",'ipccp.is_done'=>"'0'");
		$group_by = 'ipccp.id';
		if(!empty($searchtext))
		{
			$match = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'ecrt.template_subject'=>$searchtext,'ecrt.email_message'=>$searchtext,'CONCAT_WS(" >> ",ipm.plan_name,ipim.description)'=>$searchtext);
			$data['emails_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1');
		}
		else
		{
			$data['emails_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		}
		
		//echo $this->db->last_query();
		
		//pr($data['emails_datalist']);exit;
		
		$this->pagination->initialize($config);
		$data['emails_pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data3 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data3', $dashboard_task_sortsearchpage_data3);
		$data['uri_segment'] = $uri_segment;
		
		if(!empty($flag))
			return $data;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/email_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/email_task_list';
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for Send Email
		@Author     : Sanjay Chabhadiya
		@Input      : ID
		@Output     : 
		@Date       : 8-11-2014
	*/

	public function send_mail()
	{
		$id = $this->input->post('single_id');
		$contact_id = $this->input->post('contact_id');
		$uri_segment = $this->input->post('uri_segment');
		//$page = $this->uri->segment(5);
		
		$field = array('id','remain_emails');
        $match = array('id'=>$this->admin_session['admin_id']);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		
		//$email_data['send_mail_count'] = $send_mail_count;
		if(count($udata) > 0)
		{
			$remain_emails = $udata[0]['remain_emails'];
			if($remain_emails == 0)
			{
				$email_data['flag'] = 2;
			}
		}
		else
			$remain_emails = 0;
		if(!empty($id) && !empty($contact_id))
		{
			
			$table = "interaction_plan_contact_communication_plan ipccp";
			$fields = array('ipccp.contact_id,ipim.include_signature,ecm.id,ecm.template_name_id,ecm.email_signature,ecm.is_unsubscribe,ecrt.template_subject,ecrt.email_message,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,cet.email_address,ecrt.id as ID,lm.admin_name,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,lm.email_id,lm.user_type');
			$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
								 'contact_master as cm jointype direct'=>'cm.id = ipccp.contact_id',
								 'email_campaign_master as ecm'=>'ecm.interaction_id = ipim.id',
								 'email_campaign_recepient_trans as ecrt'=>'ecrt.email_campaign_id= ecm.id',
								 'contact_emails_trans cet'=>'cet.contact_id = ecrt.contact_id',
								 'login_master lm'=>'lm.id = ipim.assign_to',
								 'user_master um'=>'um.id = lm.user_id',
								 
								 );
			//$wherestring = array('ipccp.id'=> $id,'cm.is_subscribe'=>"'0'",'ecrt.is_send'=>"'0'",'ecrt.contact_id'=>$contact_id,'cet.is_default'=>"'1'");
                        $wherestring = array('ipccp.id'=> $id,'cm.is_subscribe'=>"'0'",'ecrt.contact_id'=>$contact_id,'cet.is_default'=>"'1'");
			$group_by = 'cet.contact_id';
			$datalist = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
			
			if(count($datalist) > 0)
			{
				if(!empty($datalist[0]['id']))
					$mail_data['attachment'] = $this->email_campaign_master_model->select_email_campaign_attachments($datalist[0]['id']);
				
				
				if(!empty($datalist[0]['email_signature']) && $datalist[0]['include_signature'])
				{
					$match = array('id'=>$campaign_data[0]['email_signature']);
					$email_signature = $this->email_signature_model->select_records('',$match,'','=');
				}
				$message = '';
				$message = !empty($datalist[0]['email_message'])?$datalist[0]['email_message']:'';
				if(!empty($email_signature[0]['full_signature']))
					$message .= "<br>".$email_signature[0]['full_signature'];
				if($datalist[0]['is_unsubscribe'] == '1')
					$message .= '{(my_unsubscribe_link)}';
					
				//$headers .= 'MIME-Version: 1.0'."\r\n";
				$from = '';
				$from_email = '';
				if(!empty($datalist[0]['user_type']) && $datalist[0]['user_type'] == '2')
					$from .= $datalist[0]['admin_name'];
				else
					$from .= trim($datalist[0]['user_name']);
				if(!empty($datalist[0]['email_id']))
					$from_email .= $datalist[0]['email_id'];
				
				//$headers .= "From: ".$from." <".$from_email.">\r\n";
				
				$mail_data['from_email'] = $from_email;
				$mail_data['from_name'] = $from;
				
				/*if(!empty($attachment))
					$headers .= $this->mailAttachmentHeader($attachment,$message);
				else
					$headers .= $this->mailAttachmentHeader('',$message);*/
				
				
				$cdata['id'] = $datalist[0]['ID'];
				
				if(!empty($remain_emails)){
					//$message = $headers;
					$subject = $datalist[0]['template_subject'];
					$to = $datalist[0]['email_address'];
					$cdata['email_address'] = $to;
					if(!empty($datalist[0]['email_address']))
					{
						$from = "nishit.modi@tops-int.com";
						
						if(!empty($datalist[0]['is_unsubscribe']) && $datalist[0]['is_unsubscribe'] == '1'){
							$link = base_url()."unsubscribe/unsubscribe_link/".$to;
							$message1 = '<br/><br/><a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
							$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
						}
						$response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$mail_data);
						$cdata['info'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
						unset($response);
						//mail($to,$subject,'',$message,"-f".$from);
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$cdata['is_send'] = '1';
						$remain_emails--;
						if(!empty($datalist))
						{
							$contact_conversation['contact_id'] = $datalist[0]['contact_id'];
							$contact_conversation['log_type'] = 6;
							$contact_conversation['campaign_id'] = $datalist[0]['id'];
							$contact_conversation['email_camp_template_id'] = $datalist[0]['template_name_id'];
							
							if(!empty($datalist[0]['template_name_id']))
							{
								$match = array('id'=>$datalist[0]['template_name_id']);
								$template_data = $this->email_library_model->select_records('',$match,'','=');
								if(count($template_data) > 0)
									$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
							}
							
							$contact_conversation['created_date'] = date('Y-m-d H:i:s');
							$contact_conversation['created_by'] = $this->admin_session['id'];
							$contact_conversation['status'] = '1';
							$this->contact_conversations_trans_model->insert_record($contact_conversation);
						}
						/*$data['id'] = $id;
						$data['task_date'] = date('Y-m-d');
						$data['task_completed_date'] = date('Y-m-d H:i:s');
						$data['completed_by'] = $this->admin_session['id'];
						$data['is_done']='1';
						$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
						common_rescheduled_task($this->input->post('single_id'));*/
					}
					
				}
				else
					$cdata['is_send'] = '0';
				$this->email_campaign_master_model->update_email_campaign_trans($cdata);
				
			}
			else
			{
				$cdata['contact_id'] = $contact_id;
				$cdata['is_send'] = '0';	
				$this->email_campaign_master_model->update_email_campaign_trans($cdata);	
			}
		}
		if(!empty($id))
		{
			$data['id'] = $id;
			$data['task_date'] = date('Y-m-d');
			$data['task_completed_date'] = date('Y-m-d H:i:s');
			$data['completed_by'] = $this->admin_session['id'];
			$data['is_done']='1';
			$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
			common_rescheduled_task($this->input->post('single_id'));
		}
		
		$idata['id'] = $this->admin_session['admin_id'];
		if(isset($remain_emails))
			$idata['remain_emails'] = $remain_emails;
		$udata = $this->admin_model->update_user($idata);
		
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data3');
			
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
	}
	
	/*
		@Description: Function for File Attachment
		@Author     : Sanjay Chabhadiya
		@Input      : File Name
		@Output     : 
		@Date       : 8-11-2014
	*/

	
	public function mailAttachmentHeader($attachment,$message)
	{
		$mime_boundary = md5(time());
	
		$xMessage = "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"\r\n\r\n";
		
		//$xMessage .= "--".$mime_boundary."\r\n\r\n";
		
		//$xMessage .= "This is a multi-part message in MIME format.\r\n";
		$xMessage .= "--".$mime_boundary."\r\n";
		
		//$xMessage .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		
		//$xMessage .= "Content-Transfer-Encoding: 7bit\r\n";
		
		//$xMessage .= $message."\r\n\r\n";
		
		$xMessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$xMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$xMessage .= $message."\r\n\r\n";
		if(!empty($attachment))
		{
			foreach($attachment as $file)
			{
				$xMessage .= "--".$mime_boundary."\r\n";
				
				$xMessage .= "Content-Type: application/octet-stream; name=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$xMessage .= "Content-Transfer-Encoding: base64\r\n";
				
				$xMessage .= "Content-Disposition: attachment; filename=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$content = file_get_contents("uploads/attachment_file/".$file['attachment_name']);
				
				$xMessage.= chunk_split(base64_encode($content));
				
				$xMessage .= "\r\n\r\n";
			
			}
		}
		$xMessage .= "--".$mime_boundary."--\r\n\r\n";
		
		return $xMessage;
	
	}
	
	/*
		@Description: Function for Get Letter,Label and Envelope Task List
		@Author     : Sanjay Chabhadiya
		@Input      : Interaction Type
		@Output     : Letter,Label and Envelope Task list
		@Date       : 8-11-2014
	*/


	public function letter_label_envelope_task()
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data5');
		}
		$data['sortfield'] = 'ipccp.task_date';
		$data['sortby'] = 'asc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data5');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'ipccp.task_date';
				$sortby = 'asc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/letter_label_envelope_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		$data['dt'] = $dt;
		
               // $task_lel_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$where,'','1');
		
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('contact_master as cm jointype direct' => 'ipccp.contact_id = cm.id',
							 'interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'letter_template_master as ltm' => 'ltm.id = ipim.template_name',
							 'envelope_template_master as etm' => 'etm.id = ipim.template_name',
							 'label_template_master as lltm' => 'lltm.id = ipim.template_name',
							 'marketing_master_lib__category_master mmlcm'=>'mmlcm.id = ltm.template_category',
							 'marketing_master_lib__category_master mmlcm1'=>'mmlcm1.id = etm.template_category',
							 'marketing_master_lib__category_master mmlcm2'=>'mmlcm2.id = lltm.template_category',
							 );
		$fields = array('ipccp.id,ipccp.interaction_type,ipim.id as interaction_master_interaction_id,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication','ltm.template_name as letter_template_name,ltm.id as letter_template_id,ltm.letter_content as letter_message,etm.template_name as envelope_template_name,etm.id as envelope_template_id,etm.envelope_content as envelope_message,lltm.label_content as label_message,lltm.template_name as label_template_name,lltm.id as label_template_id,ipccp.task_date','ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipim.start_type as i_start_type,ipccp1.is_done,group_concat(DISTINCT CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) separator \',\') as recipients,group_concat(ipccp.id separator \',\') as ipccp_id,group_concat(DISTINCT ipccp.contact_id separator \',\') as contact_id,mmlcm.category,mmlcm.id as label_category_id,mmlcm.category as letter_category,mmlcm1.id as en_category_id,mmlcm1.category as envelope_category,mmlcm2.id as letter_category_id,mmlcm2.category as label_category,ipccp.interaction_plan_id');
		//group_concat(CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) separator \',\') as recipients
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') <= '$dt' AND ipccp.is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		else
			$wherestring = "DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') = '$dt' AND ipccp.is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		
		//$group_by = 'ipccp.id';
		$group_by = 'ipccp.interaction_plan_interaction_id';
		if(!empty($searchtext))
		{
			//ipccp.interaction_plan_interaction_id having 
			$having = "recipients LIKE '%".$searchtext."%' OR etm.template_name LIKE '%".$searchtext."%' OR ltm.template_name LIKE '%".$searchtext."%' OR lltm.template_name LIKE '%".$searchtext."%' OR communication LIKE '%".$searchtext."%'";
			$match = array();
			//$match = array('ltm.template_name'=>$searchtext,'etm.template_name'=>$searchtext,'lltm.template_name'=>$searchtext,'CONCAT_WS(" >> ",ipm.plan_name,ipim.description)'=>$searchtext);
			$data['mailing_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring,'','',$having);
			
			
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1',$having);
		}
		else
		{
			$data['mailing_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			
			//echo $this->db->last_query();exit;
			
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		}
		
		//pr($data['datalist']);exit;
		$this->pagination->initialize($config);
		$data['mailing_pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data5 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data5', $dashboard_task_sortsearchpage_data5);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/letter_label_envelope_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/letter_label_envelope_list';
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for File Attachment
		@Author     : Sanjay Chabhadiya
		@Input      : File Name
		@Output     : 
		@Date       : 8-11-2014
	*/

	
	public function is_completed()
	{
		$id = $this->input->post('selectedvalue');
		$interaction_type = $this->input->post('interaction_type');
		if(!empty($interaction_type) && ($interaction_type == 'call' || $interaction_type == 2))
			$data['disposition'] = $this->input->post('disposition');
		if(!empty($id))
		{
			if(!empty($interaction_type) && ($interaction_type == 'letter' || $interaction_type == 1))
			{
				$table = "interaction_plan_contact_communication_plan as ipccp";
				$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id'
									 );
				$fields = array('ipccp.id,ipccp.interaction_type,ipim.template_name,ipccp.contact_id');
				//$match = array();
				//$where = "ipccp.id = ".$id;
				$where = "ipccp.interaction_plan_interaction_id= ".$id." AND ipccp.is_done = '0' ";
				$interaction_communication_plan = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where);
				//pr($interaction_communication_plan); exit;
				if(count($interaction_communication_plan) > 0)
				{
					//$data['task_date'] = date('Y-m-d');
					$data['task_completed_date'] = date('Y-m-d H:i:s');
					$data['completed_by'] = $this->admin_session['id'];
					$data['is_done']='1';
					foreach($interaction_communication_plan as $row)
					{
						$data['id'] = $row['id'];
						$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
						common_rescheduled_task($data['id']);
					}
					
				}
			}
			elseif(!empty($interaction_type) && !empty($data['disposition']) && $data['disposition'] == 3 && ($interaction_type == 'call' || $interaction_type == 2))
				$this->reschedule_call_task($id);
			else
			{
				$data['id'] = $id;
				$data['task_date'] = date('Y-m-d');
				$data['task_completed_date'] = date('Y-m-d H:i:s');
				$data['completed_by'] = $this->admin_session['id'];
				$data['is_done']='1';
				$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
				common_rescheduled_task($data['id']);
			}
		}
		
		if(!empty($interaction_type) && ($interaction_type == 'call' || $interaction_type == 2))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data2');
		elseif(!empty($interaction_type) && ($interaction_type == 'letter' || $interaction_type == 1))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data5');
		elseif(!empty($interaction_type) && ($interaction_type == 'to-do' || $interaction_type == 6))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data6');
		
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
		//pr($data);exit;	
	}
	public function ajax_delete_task()
	{
		$id=$this->input->post('single_remove_id');
		$interaction_type = $this->input->post('interaction_type');
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($interaction_type) && ($interaction_type == 'letter' || $interaction_type == 1))
		{
			if(!empty($id))
			{
					//$this->task_model->delete_record($id);
					//$this->task_model->delete_user_task($id);
					$data['interaction_plan_interaction_id'] = $id;
					$data['is_done'] = '0';
					$this->contacts_model->delete_interaction_plan_interaction_communication('',$data);
					unset($id);
			}
			elseif(!empty($array_data))
			{
				for($i=0;$i<count($array_data);$i++)
				{
					$data['interaction_plan_interaction_id'] = $array_data[$i];
					$data['is_done'] = '0';
					//$this->task_model->delete_record($array_data[$i]);
					//$this->task_model->delete_user_task($array_data[$i]);
					$this->contacts_model->delete_interaction_plan_interaction_communication('',$data);
					$delete_all_flag = 1;
					$cnt++;
				}
			}
		}
		else
		{
			if(!empty($id))
			{
					//$this->task_model->delete_record($id);
					//$this->task_model->delete_user_task($id);
					$data['id'] = $id;
					$this->contacts_model->delete_interaction_plan_interaction_communication($data);
					unset($id);
			}
			elseif(!empty($array_data))
			{
				for($i=0;$i<count($array_data);$i++)
				{
					//$this->task_model->delete_record($array_data[$i]);
					//$this->task_model->delete_user_task($array_data[$i]);
					$data['id'] = $array_data[$i];
					$this->contacts_model->delete_interaction_plan_interaction_communication($data);
					$delete_all_flag = 1;
					$cnt++;
				}
			}
		}
		
		if(!empty($interaction_type) && ($interaction_type == 'call' || $interaction_type == 2))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data2');
		elseif(!empty($interaction_type) && ($interaction_type == 'email' || $interaction_type == 5))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data3');
		elseif(!empty($interaction_type) && ($interaction_type == 'sms' || $interaction_type == 3))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data4');
		elseif(!empty($interaction_type) && ($interaction_type == 'letter' || $interaction_type == 1))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data5');
		elseif(!empty($interaction_type) && ($interaction_type == 'to-do' || $interaction_type == 6))
			$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data6');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}

		if($pagingid < 0)
			$pagingid = 0;
		//echo 1;
		echo $pagingid;
	}
	
	/*
		@Description: Function for print mail out
		@Author     : Sanjay Chabhadiya
		@Input      : Interaction ID
		@Output     : Mail out print
		@Date       : 8-11-2014
	*/
	
	public function mail_out()
	{
		$id = $this->input->post('finalid');
		$data['allinteractionid'] = $this->input->post('finalid');
		if(!empty($id))
		{
			$id = explode(",",$this->input->post('finalid'));
			for($j=0;$j<count($id);$j++)
			{
				$table = "interaction_plan_contact_communication_plan as ipccp";
				$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id'
									 );
				$fields = array('ipccp.id,ipccp.interaction_type,ipim.template_name,ipccp.contact_id');
				//$match = array();
				$where = "ipccp.interaction_plan_interaction_id = ".$id[$j]." AND ipccp.is_done = '0' AND ipccp.interaction_type = 5";
				$interaction_communication_plan = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where);
				$finlaOutput = '';
				$finlaOutputPrint = '';
				//pr($interaction_communication_plan);exit;
				$data['interaction_id'][$j] = $id[$j];
				if(count($interaction_communication_plan) > 0)
				{	
					$finalcontactlist = '';
					foreach($interaction_communication_plan as $rowid)
						$finalcontactlist .= $rowid['contact_id'].",";
		
					if($interaction_communication_plan[0]['interaction_type'] == '2'){
						$match = array("id"=>$interaction_communication_plan[0]['template_name']);
						$data['tmp_type'] = 'Envelope';
						$data['template_data'] = $this->envelope_library_model->select_records('',$match,'','=');
						$tmp_title = $data['template_data'][0]['template_name'];
						$tmp_data = $data['template_data'][0]['envelope_content'];
					}
					elseif($interaction_communication_plan[0]['interaction_type'] == '1'){
						$data['tmp_type'] = 'Label';
						$match = array("id"=>$interaction_communication_plan[0]['template_name']);
						$data['template_data'] = $this->label_library_model->select_records('',$match,'','=');
						//pr($data['template_data'][0]);exit;
						$tmp_title = $data['template_data'][0]['template_name'];
						$tmp_data = $data['template_data'][0]['label_content'];
					}
					elseif($interaction_communication_plan[0]['interaction_type'] == '5'){
						$data['tmp_type'] = 'Letter';
						$match = array("id"=>$interaction_communication_plan[0]['template_name']);
						$data['template_data'] = $this->letter_library_model->select_records('',$match,'','=');
						//pr($data['template_data'][0]);exit;
						$tmp_title = $data['template_data'][0]['template_name'];
						$tmp_data = $data['template_data'][0]['letter_content'];
					}
					
					$interaction_contacts = trim($finalcontactlist,',');
					$data['finalcontactlist'] = $interaction_contacts;
					//$interaction_contacts = explode(",",$interaction_contacts);
					$sort_by = 'first_name';
					$data['sort_by'] = $sort_by;
					
					$env_width = $data['template_data'][0]['size_w'];
					$env_height = $data['template_data'][0]['size_h'];
			
					//$data['tmp_size_w'][$j] = $env_width;  
					//$data['tmp_size_h'][$j] = $env_height;
					//$data['template_data'][$j] = $tmp_data;
					//pr($data);exit;
					//pr($data['tmp_size_h']);exit;
					//if(!empty($interaction_contacts) && !empty($sort_by))
					if(!empty($sort_by))
					{
						$cdata['sort_by'] = $sort_by;
						$cdata['interaction_contacts'] = $interaction_contacts;
						$data['interaction_contacts_data'] = $this->contacts_model->contact_select_records($cdata);
						//pr($data['interaction_contacts_data']);exit;
						
						if(!empty($data['interaction_contacts_data']))
						{
							$finlaOutput = '';
							for($i=0;$i<count($data['interaction_contacts_data']);$i++)
							{
								$agent_name = '';
								if(!empty($data['interaction_contacts_data'][$i]['created_by']))
								{
									
									$table ="login_master as lm";   
									$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
									$join_tables = array('user_master as um'=>'lm.user_id = um.id');
									$wherestring = 'lm.id = '.$data['interaction_contacts_data'][$i]['created_by'];
									$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
									if(!empty($agent_datalist))
									{
										if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
											$agent_name = $agent_datalist[0]['admin_name'];
										else
											$agent_name = $agent_datalist[0]['first_name'];
									}
								}
								
								$emaildata = array(
					 					'Contact First Name'=>$data['interaction_contacts_data'][$i]['first_name'],
										'Contact Last Name'=>$data['interaction_contacts_data'][$i]['last_name'],
										'Contact Company Name'=>$data['interaction_contacts_data'][$i]['company_name'],
										'Contact Spouse/Partner First Name'=>$data['interaction_contacts_data'][$i]['spousefirst_name'],
										'Contact Spouse/Partner Last Name'=>$data['interaction_contacts_data'][$i]['spouselast_name'],
										'Agent First Name'=> !empty($agent_name)?$agent_name:'',
										'Agent Last Name'=> !empty($agent_datalist[0]['last_name'])?$agent_datalist[0]['last_name']:'',
										'Agent Company'=> !empty($agent_datalist[0]['company_name'])?$agent_datalist[0]['company_name']:'',
										'Agent Title'=>'',
										'Agent Address'=>'',
										'Agent City'=>'',
										'Agent State'=>'',
										'Contact Address'=> trim($data['interaction_contacts_data'][$i]['address_line1']).' '.trim($data['interaction_contacts_data'][$i]['address_line2']),
										'Agent Zip'=>'',
										'Contact State'=> $data['interaction_contacts_data'][$i]['state'],
										'Contact City'=> $data['interaction_contacts_data'][$i]['city'],
										'Contact Zip'=> $data['interaction_contacts_data'][$i]['zip_code']);
							
								$content 	= $tmp_data;
								$title		= $tmp_title;
								
								$pattern = "{(%s)}";
								$map = array();
								
								if($emaildata != '' && count($emaildata) > 0)
								{	
									foreach($emaildata as $var => $value)
									{
										$map[sprintf($pattern, $var)] = $value;
									}
								}
								
								$finaltitle = strtr($title, $map);				
								$output = strtr($content, $map);
								
								//$finlaOutput .= $finaltitle;
								
								if($i==0){
									$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='width:".$env_width."in;height:".$env_height."in;'>".$output."</div></div>";
									$finlaOutputPrint .= "<div style='width:".$env_width."in;text-align:justify;'>".$output."</div>";
								}
								else{	
									$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='page-break-before:always;width:".$env_width."in;height:".$env_height."in;'>".$output."</div></div>";
									$finlaOutputPrint .= "<div style='page-break-before:always;width:".$env_width."in;text-align:justify;'>".$output."</div>";
								}
												
							}
						}
						else
						{
							$finlaOutput = '';
							$emaildata = array('first name'=>'First Name','last name'=>'Last Name','company name'=>'Company Name');
			
							$content 	= $tmp_data;
							$title		= $tmp_title;
			
							$pattern = "{(%s)}";
							$map = array();
			
							if($emaildata != '' && count($emaildata) > 0)
							{	foreach($emaildata as $var => $value)
								{
									$map[sprintf($pattern, $var)] = $value;
								}
							}
							
							$finaltitle = strtr($title, $map);				
							$output = strtr($content, $map);
							
							//$finlaOutput .= $finaltitle;
							//$finlaOutput .= "<div style=width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;overflow:hidden; id='finalOutput'>".$finaltitle.$output."</div>";
							
							$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;text-align:justify;'>".$output."</div></div>";
							$finlaOutputPrint .= "<div style='width:".$env_width."in;text-align:justify;'>".$output."</div>";
						}
					}
					
					//pr($finlaOutput);exit;
					$data['finlaOutput'][$j] = $finlaOutput;
					$data['finlaOutputPrint'][$j] = $finlaOutputPrint;
					
					$match = array("parent"=>'0');
					$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
					
					//pr($data['finlaOutput']);exit;
					//pr($data);exit;
					$data['post_data'] = $_POST;
					//pr($data['post_data']);exit;
				}
			}
			//pr($data['finlaOutputPrint']);exit;
			$data['main_content'] = "admin/home/list";
			$this->load->view('admin/include/template', $data);	
		}
		else
			redirect('admin/'.$this->viewName);
	}
	
	/*
		@Description: Function for Download PDF and Insert Downloaded PDF details
		@Author     : Sanjay Chabhadiya
		@Input      : interaction id
		@Output     : 
		@Date       : 21-1-2015
	*/
	
	public function generate_pdf1()
	{
		//$data['templatedata']  = $this->input->post('tmp_data');
		//$data['template_type'] = $this->input->post('template_type');
		/*$interaction_id = 1;
		$data['templatedata']  = $this->input->post('tmp_data_'.$interaction_id);
		pr($data['templatedata']);
		exit;*/
		
		$interaction_id = $this->input->post('interaction_id');
		if(!empty($interaction_id))
		{
			$interaction_id = explode(',',$interaction_id);
			for($i=0;$i<count($interaction_id);$i++)
			{
				$table = "interaction_plan_contact_communication_plan as ipccp";
				$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
									 'letter_template_master ltm' => 'ltm.id = ipim.template_name',
									 'envelope_template_master etm' => 'etm.id = ipim.template_name',
									 'label_template_master ltm1' => 'ltm1.id = ipim.template_name',
									 );
				$fields = array('ipccp.id,ipccp.interaction_type,ipim.template_name,ipccp.contact_id,ipim.template_category','ltm.letter_content,ltm.size_w as letter_size_w,ltm.size_h as letter_size_h','etm.envelope_content,etm.size_w as envelope_size_w,etm.size_h as envelope_size_h','ltm1.label_content,ltm1.size_w as label_size_w,ltm1.size_h as label_size_h');
				//$match = array();
				$where = "ipccp.interaction_plan_interaction_id = ".$interaction_id[$i]." AND ipccp.is_done = '0' ";
				$interaction_communication_plan = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where);
				if(!empty($interaction_communication_plan))
				{
					$pdata['mail_out_type'] 	= $interaction_communication_plan[0]['interaction_type'];
					$mailout_type = 'label';
					if($pdata['mail_out_type'] == '2')
						$mailout_type = 'envelope';
					elseif($pdata['mail_out_type'] == '5')
						$mailout_type = 'letter';
					$pdata['category_id'] 		= $interaction_communication_plan[0]['template_category'];
					$pdata['template_id'] 		= $interaction_communication_plan[0]['template_name'];
					//$pdata['sort_by'] 			= $this->input->post('post_sort_by');
					$pdata['message'] 		= $interaction_communication_plan[0][$mailout_type.'_content'];
					$pdata['size_w'] 		= $interaction_communication_plan[0][$mailout_type.'_size_w'];
					$pdata['size_h'] 		= $interaction_communication_plan[0][$mailout_type.'_size_h'];
					$pdata['save_type'] 		= 'download';
					$pdata['created_by'] 		= $this->admin_session['id'];
					$pdata['created_date'] 		= date('Y-m-d h:i:s');
					$mail_blast_id = $this->mail_blast_model->insert_record($pdata);
					foreach($interaction_communication_plan as $row)
					{
						$cdata['mail_blast_id'] 	= $mail_blast_id;
						$cdata['contact_id'] 		= $row['contact_id'];
						$cdata['created_date'] 		= date('Y-m-d h:i:s');
						$this->mail_blast_model->insert_record1($cdata);
					}
					
					$data['size_w']  = $mm * $pdata['size_w'];
					$data['size_h']  = $mm * $pdata['size_h'];
				}
				$data['templatedata']  = $this->input->post('tmp_data_'.$interaction_id[$i]);
				//pr($data);
				if(!empty($data))
				{
					$this->load->view("user/mail_out/compare_pdf", $data);
				}
				unset($data);
			}
		}
	}
	
	/*
		@Description: Function for print the mail out than Insert print details
		@Author     : Sanjay Chabhadiya
		@Input      : interaction id
		@Output     : 
		@Date       : 21-1-2015
	*/
	
	public function insert_data1()
	{
		
		$interaction_id = $this->input->post('interaction_id');
		if(!empty($interaction_id))
		{
			$interaction_id = explode(',',$interaction_id);
			for($i=0;$i<count($interaction_id);$i++)
			{
				$table = "interaction_plan_contact_communication_plan as ipccp";
				$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
									 'letter_template_master ltm' => 'ltm.id = ipim.template_name',
									 'envelope_template_master etm' => 'etm.id = ipim.template_name',
									 'label_template_master ltm1' => 'ltm1.id = ipim.template_name',
									 );
				$fields = array('ipccp.id,ipccp.interaction_type,ipim.template_name,ipccp.contact_id,ipim.template_category','ltm.letter_content,ltm.size_w as letter_size_w,ltm.size_h as letter_size_h','etm.envelope_content,etm.size_w as envelope_size_w,etm.size_h as envelope_size_h','ltm1.label_content,ltm1.size_w as label_size_w,ltm1.size_h as label_size_h');
				//$match = array();
				$where = "ipccp.interaction_plan_interaction_id = ".$interaction_id[$i]." AND ipccp.is_done = '0' ";
				$interaction_communication_plan = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where);
				if(!empty($interaction_communication_plan))
				{
					$pdata['mail_out_type'] 	= $interaction_communication_plan[0]['interaction_type'];
					$mailout_type = 'label';
					if($pdata['mail_out_type'] == '2')
						$mailout_type = 'envelope';
					elseif($pdata['mail_out_type'] == '5')
						$mailout_type = 'letter';
					$pdata['category_id'] 		= $interaction_communication_plan[0]['template_category'];
					$pdata['template_id'] 		= $interaction_communication_plan[0]['template_name'];
					$pdata['message'] 		= $interaction_communication_plan[0][$mailout_type.'_content'];
					$pdata['size_w'] 		= $interaction_communication_plan[0][$mailout_type.'_size_w'];
					$pdata['size_h'] 		= $interaction_communication_plan[0][$mailout_type.'_size_h'];
					//$pdata['sort_by'] 			= $this->input->post('post_sort_by');
					$pdata['save_type'] 		= 'print';
					$pdata['created_by'] 		= $this->admin_session['id'];
					$pdata['created_date'] 		= date('Y-m-d h:i:s');
					$mail_blast_id = $this->mail_blast_model->insert_record($pdata);
					foreach($interaction_communication_plan as $row)
					{
						$cdata['mail_blast_id'] 	= $mail_blast_id;
						$cdata['contact_id'] 		= $row['contact_id'];
						$cdata['created_date'] 		= date('Y-m-d h:i:s');
						$this->mail_blast_model->insert_record1($cdata);
					}
				}
			}
		}
	}
	
	/*
		@Description: Function for Get Telephone Task List
		@Author     : Sanjay Chabhadiya
		@Input      : Interaction Type
		@Output     : Telephone Task list
		@Date       : 8-11-2014
	*/
	
	public function telephone_task()
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data2');
		}
		$data['sortfield'] = 'ipccp.task_date';
		$data['sortby'] = 'asc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data2');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'ipccp.task_date';
				$sortby = 'asc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/telephone_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		$data['dt'] = $dt;
		
               // $task_lel_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$where,'','1');
		$fields = array('ipccp.id,ipccp.interaction_type,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','pcsm.template_name,pcsm.calling_script,cpt.phone_no,ipccp.task_date,ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipim.start_type as i_start_type,ipccp1.is_done,cm.id as contact_id,cet.id as em_id,MAX(cpt.is_default) as is_default');
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'phone_call_script_master as pcsm' => 'pcsm.id = ipim.template_name',
							 'contact_master as cm jointype direct' => 'cm.id = ipccp.contact_id',
							 '(select * from contact_phone_trans order by is_default desc) as cpt'=>'cpt.contact_id= cm.id',
							 '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
							 
							 );

		//$wherestring = "ipccp.created_by = ".$this->admin_session['id']." AND DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') <= '$dt' AND is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") <= '=>"'$dt'",'ipccp.interaction_type'=>'4');
		else
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>'4');
			
		$group_by = 'ipccp.id';
		if(!empty($searchtext))
		{
			$match = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'pcsm.template_name'=>$searchtext,'cpt.phone_no'=>$searchtext,'CONCAT_WS(" >> ",ipm.plan_name,ipim.description)'=>$searchtext);
			$data['telephone_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','','',$group_by,$wherestring,'','1');
		}
		else
		{
			$data['telephone_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			//echo $this->db->last_query();exit;
			//pr($data['telephone_datalist']);exit;
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		}
		
		//pr($data['datalist']);exit;
		$this->pagination->initialize($config);
		$data['telephone_pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data2 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data2', $dashboard_task_sortsearchpage_data2);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/telephone_task_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/telephone_task_list';
			$this->load->view('admin/include/template',$data);
		}
		
	}
	
	/*
		@Description: Function for Get Telephone Task Popup
		@Author     : Sanjay Chabhadiya
		@Input      : ID
		@Output     : Telephone Task Details
		@Date       : 8-11-2014
	*/
	
	public function phone_call_popup()
	{
		
		//$sortfield = 'ipccp.task_date';
		//$sortby = 'asc';
		
		$data['sortfield'] = $this->uri->segment(4);
		$data['sortby'] = $this->uri->segment(5);
		if(empty($data['sortfield']))
			$data['sortfield'] = 'cm.first_name';
		if(empty($data['sortby']))
			echo $data['sortby'] = 'desc';
		//pr($_POST);exit;
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		
		$data['dt'] = $dt;
		$submit = $this->input->post('submitbtn');
		$uri_segment = $this->input->post('uri_segment');
		if($this->input->post('submitbtn'))
		{
			
			$idata['id'] = $this->input->post('id');
			$idata['notes'] = $this->input->post('notes');
			$idata['disposition'] = $this->input->post('disposition');
			$idata['completed_by'] = $this->admin_session['id'];
			$idata['task_completed_date'] = date('Y-m-d H:i:s');
			$idata['is_done'] = '1';
			if($idata['disposition'] == 3)
				$this->reschedule_call_task($idata['id']);
			else
				$this->contacts_model->update_interaction_plan_interaction_transtrans_record($idata);
			$uri_segment = $this->input->post('uri_segment');
			if($this->input->post('finish') == 1)
				$data['finish'] = 1;
				//redirect('admin/'.$this->viewName.'/telephone_task');
			//$this->
		}
		elseif($this->input->post('nextbtn'))
			$uri_segment = $this->input->post('uri_segment') + 1;
		elseif($this->input->post('backbtn'))
			$uri_segment = $this->input->post('uri_segment') - 1;
		elseif($this->uri->segment(6))
			$uri_segment = $this->uri->segment(6) - 1;
			
		//$this->load->view($this->user_type.'/home/phone_call_popup');
		$data['contact_disposition_master'] = $this->contacts_model->select_contact_disposition_master();
		//pr($data['contact_disposition_master']);exit;
		/*$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'phone_call_script_master as pcsm' => 'pcsm.id = ipim.template_name',
							 'contact_master as cm jointype direct' => 'cm.id = ipccp.contact_id',
							 '(select * from contact_phone_trans order by is_default desc) as cpt'=>'cpt.contact_id= cm.id'
							 
							 );
		$fields = array('ipccp.id,ipccp.interaction_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','pcsm.template_name,pcsm.calling_script,cpt.phone_no');*/
		$fields = array('ipccp.id,ipccp.interaction_type,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','pcsm.template_name,pcsm.calling_script,cpt.phone_no,ipccp.task_date,ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipccp1.is_done,MAX(cpt.is_default) as is_default');
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'phone_call_script_master as pcsm' => 'pcsm.id = ipim.template_name',
							 'contact_master as cm jointype direct' => 'cm.id = ipccp.contact_id',
							 '(select * from contact_phone_trans order by is_default desc) as cpt'=>'cpt.contact_id= cm.id'
							 
							 );
		//$wherestring = "ipccp.created_by = ".$this->admin_session['id']." AND DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') <= '$dt' AND is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		$group_by = 'ipccp.id';
		
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") <= '=>"'$dt'",'ipccp.interaction_type'=>'4');
		else
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>'4');
		
		$data['editRecord'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','1',$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
		//echo $this->db->last_query(); exit;
		$data['total_row'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		/*echo $this->db->last_query();
		pr($data['editRecord']);exit;*/
		$data['uri_segment'] = $uri_segment;
		$data['main_content'] =  $this->user_type.'/home/phone_call_popup';
		$this->load->view('admin/include/template',$data);
	}
	
	/*
		@Description: Function for Get SMS Task List
		@Author     : Sanjay Chabhadiya
		@Input      : Interaction Type
		@Output     : SMS Task List
		@Date       : 8-11-2014
	*/
	
	public function sms_task($flag='')
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		
		$data['dt'] = $dt;
			
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data4');
		}
		$data['sortfield']		= 'ipccp.task_date';
		$data['sortby']		= 'asc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data4');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'ipccp.task_date';
				$sortby = 'asc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($date1) && !empty($date2))
		{
			$date1 = $this->input->post('date1');
			$date2 = $this->input->post('date2');
			$data['date1'] = $date1;
			$data['date2'] = $date2;	
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/sms_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$curr_date = date('Y-m-d');
		$table = "interaction_plan_contact_communication_plan ipccp";
		$fields = array('ipccp.id,ipccp.contact_id,scrt.sms_message,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,ipccp.task_date,ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipim.start_type as i_start_type,ipccp1.is_done,scrt.is_sms_exist,cpt.is_default');
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							  'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'contact_master as cm jointype direct'=>'cm.id = ipccp.contact_id',
							 'sms_campaign_master as scm'=>'scm.interaction_id = ipim.id',
							 'sms_campaign_recepient_trans as scrt'=>'scrt.sms_campaign_id= scm.id AND scrt.contact_id = ipccp.contact_id',
							 '(select * from contact_phone_trans where is_default = "1") as cpt'=>'cpt.contact_id = cm.id',
							 //'sms_text_template_master as stm'=>'stm.id = scm.template_name',
							 );
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") <= '=>"'$dt'",'ipccp.interaction_type'=>"'3'",'ipccp.is_done'=>"'0'");
		else
			$wherestring = array('DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>"'3'",'ipccp.is_done'=>"'0'");
			
		$group_by = 'ipccp.id';
		if(!empty($searchtext))
		{
			$match = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'scrt.sms_message'=>$searchtext,'CONCAT_WS(" >> ",ipm.plan_name,ipim.description)'=>$searchtext);
			$data['sms_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1');
		}
		else
		{
			$data['sms_datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			//echo $this->db->last_query();//exit;
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		}
		
		//pr($data['sms_datalist']);exit;
		$this->pagination->initialize($config);
		$data['sms_pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		if(!empty($flag))
			return $data;
		$dashboard_task_sortsearchpage_data4 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data4', $dashboard_task_sortsearchpage_data4);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/sms_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/sms_task_list';
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for Send SMS
		@Author     : Sanjay Chabhadiya
		@Input      : ID
		@Output     : 
		@Date       : 8-11-2014
	*/
	
	public function send_sms()
	{
		$id = $this->input->post('single_id');
		$contact_id = $this->input->post('contact_id');
		$uri_segment = $this->input->post('uri_segment');
		//$page = $this->uri->segment(5);
		
		$field = array('id','remain_sms');
        $match = array('id'=>$this->admin_session['admin_id']);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		
		//$email_data['send_mail_count'] = $send_mail_count;
		$remain_sms = 0;
		if(count($udata) > 0)
			$remain_sms = $udata[0]['remain_sms'];
		if(!empty($id) && !empty($contact_id))
		{
			
			$table = "interaction_plan_contact_communication_plan ipccp";
			$fields = array('ipccp.contact_id,scm.id,scm.template_name,scrt.sms_message,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,cpt.phone_no,scrt.id as ID,ipim.assign_to');
			$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
								 'contact_master as cm jointype direct'=>'cm.id = ipccp.contact_id',
								 'sms_campaign_master as scm'=>'scm.interaction_id = ipim.id',
								 'sms_campaign_recepient_trans as scrt'=>'scrt.sms_campaign_id= scm.id',
								 'contact_phone_trans cpt'=>'cpt.contact_id = scrt.contact_id'
								 );
			$wherestring = array('ipccp.id'=> $id,'scrt.is_send'=>"'0'",'scrt.contact_id'=>$contact_id,'cpt.is_default'=>"'1'");
			$group_by = 'cpt.contact_id';
			$datalist = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
			/*echo $this->db->last_query();
			pr($datalist);exit;*/
			
			if(count($datalist) > 0)
			{				
				$message = !empty($datalist[0]['sms_message'])?$datalist[0]['sms_message']:'';
				$cdata['id'] = $datalist[0]['ID'];
				
				$counter = strlen($message);
				if(!empty($counter))
					$total_message = $counter/160;
				$total_count = 0;
				if(!empty($total_message))
					$total_count = ceil($total_message);
				
				if($remain_sms == 0 || $remain_sms < $total_count)
				{
					$cdata['is_send'] = '0';
					if($remain_sms == 0)
					{
						$edata['type'] = 'Twilio';
						$edata['description'] = $this->lang->line('common_sms_limit_over_msg');
						$edata['created_date'] = date('Y-m-d h:i:s');
						$edata['status'] = 1;
						$edata['created_by'] = $this->admin_session['id'];
						$this->dashboard_model->insert_record1($edata);
					}
					elseif($remain_sms > 0)
					{
						$edata['type'] = 'Twilio';
						$edata['description'] = $this->lang->line('common_sms_limit_more_msg');
						$edata['created_date'] = date('Y-m-d h:i:s');
						$edata['status'] = 1;
						$edata['created_by'] = $this->admin_session['id'];
						$this->dashboard_model->insert_record1($edata);
					}
				}
				else
				{
					$from = $this->config->item('from_sms');
					//$to = '+919033921029';
					$to = $datalist[0]['phone_no'];
					
					//For twilio from account//
					
					if(!empty($datalist[0]['assign_to']))
						$send_from = $datalist[0]['assign_to'];
					else
						$send_from = 0;
						
					//////////////////////////
					
					$this->twilio->set_admin_id($send_from);
					
					$response = $this->twilio->sms($from, $to, $message);
					if(!empty($response->ErrorMessage) && $response->ErrorMessage=='Authenticate')
					{
						$edata['type'] = 'Twilio';
						$edata['description'] = 'Authentication failed.';
						$edata['created_date'] =date('Y-m-d h:i:s');
						$edata['status'] = 1;
						$this->dashboard_model->insert_record1($edata);
						
					}
					$cdata['phone_no'] = $to;
					$cdata['sent_date'] = date('Y-m-d H:i:s');
					$cdata['is_send'] = '1';
					$remain_sms = $remain_sms - $total_count;
					if(!empty($datalist))
					{
						$contact_conversation['contact_id'] = $datalist[0]['contact_id'];
						$contact_conversation['log_type'] = 8;
						$contact_conversation['campaign_id'] = $datalist[0]['id'];
						$contact_conversation['sms_camp_template_id'] = $datalist[0]['template_name'];

						if(!empty($datalist[0]['template_name']))
						{
							$match = array('id'=>$datalist[0]['template_name']);
							$template_data = $this->sms_texts_model->select_records('',$match,'','=');
							if(count($template_data) > 0)
								$contact_conversation['sms_camp_template_name'] = $template_data[0]['template_name'];
						}
						
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
					}
					/*$data['id'] = $id;
					$data['task_date'] = date('Y-m-d');
					$data['task_completed_date'] = date('Y-m-d H:i:s');
					$data['completed_by'] = $this->admin_session['id'];
					$data['is_done']='1';
					$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
					common_rescheduled_task($this->input->post('single_id'));*/
				}
				$this->sms_campaign_recepient_trans_model->update_record($cdata);
				
			}
			else
			{
				$cdata['contact_id'] = $contact_id;
				$cdata['is_send'] = '0';	
				$this->sms_campaign_recepient_trans_model->update_record($cdata);	
			}
		}
		
		if(!empty($id))
		{
			$data['id'] = $id;
			$data['task_date'] = date('Y-m-d');
			$data['task_completed_date'] = date('Y-m-d H:i:s');
			$data['completed_by'] = $this->admin_session['id'];
			$data['is_done']='1';
			$this->contacts_model->update_interaction_plan_interaction_transtrans_record($data);
			common_rescheduled_task($this->input->post('single_id'));	
		}
		
		$idata['id'] = $this->admin_session['admin_id'];
		if(isset($remain_sms))
			$idata['remain_sms'] = $remain_sms;
		$udata = $this->admin_model->update_user($idata);
		
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data4');
			
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
	}
	
	/*
		@Description: Get Details of Task Profile
		@Author: Sanjay Chabhadiya
		@Input: - Id of Task member whose details want to View
		@Output: - Details of Task which id is selected for View
		@Date: 31-12-2014
    */
	
	public function view_records()
	{
		$id = $this->input->post('id');
	
		////////////////////////////////////////

		$table = "contact_conversations_trans as cct";
		$fields = array('cm.id,cct.task_id,cct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('cct.task_id'=>$id);
		$join_tables = array(
				'contact_master as cm'=>'cm.id = cct.contact_id'
		);
		$group_by='cm.id';

		$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);

		///////////////////////////////////////

		$match = array('id'=>$id);
		$result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;
		$match1=array('tm.id'=>$id);
		$table ='task_master as tm';   
		$fields = array('tm.id','tu.task_id','tu.is_completed','lm.admin_name','tu.user_id','lm.status','lm.user_type','lm.user_id','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array("task_user_transcation tu"=>'tm.id=tu.task_id',
			"login_master as lm"=>	'tu.user_id = lm.id',
			"user_master as um" => 'lm.user_id = um.id',
		);
		$group_by = array('tm.id');
		$data['datalist'] = $this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match1,'','=','',$uri_segment,'','','');
		$this->load->view($this->user_type.'/home/view',$data);
	}
	
	/*
		@Description: Get To-Do task List
		@Author: Sanjay Chabhadiya
		@Input: - interaction type
		@Output: - To-Do Task List
		@Date: 31-12-2014
    */
	
	public function to_do_task()
	{
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('dashboard_task_sortsearchpage_data6');
		}
		$data['sortfield'] = 'ipccp.task_date';
		$data['sortby'] = 'asc';
		$searchsort_session = $this->session->userdata('dashboard_task_sortsearchpage_data6');

		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'ipccp.task_date';
				$sortby = 'asc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/to_do_task");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$session_data = $this->session->userdata('current_date_session');
		if(!empty($session_data))
			$dt = $session_data['date'];
		else
			$dt = date('Y-m-d');
		$data['dt'] = $dt;
		
               // $task_lel_overdue_count = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$where,'','1');
		$fields = array('ipccp.id,ipccp.interaction_type,CONCAT_WS(" >> ",ipm.plan_name,ipim.description) as communication','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ipccp.task_date,ipim1.interaction_id,ipccp.interaction_plan_interaction_id,ipim.start_type as i_start_type,ipccp1.is_done');
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id',
							 'interaction_plan_interaction_master as ipim1' => 'ipim1.id = ipccp.interaction_plan_interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim1.interaction_id AND ipccp1.contact_id=ipccp.contact_id',
							 'interaction_plan_master as ipm' => 'ipm.id = ipccp.interaction_plan_id',
							 'contact_master as cm jointype direct' => 'cm.id = ipccp.contact_id',
							 );

		//$wherestring = "ipccp.created_by = ".$this->admin_session['id']." AND DATE_FORMAT(ipccp.task_date,'%Y-%m-%d') <= '$dt' AND is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		if(strtotime($dt) <= strtotime(date('Y-m-d')))
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d") <= '=>"'$dt'",'ipccp.interaction_type'=>"'7'");
		else
			$wherestring = array('ipccp.is_done' => "'0'",'DATE_FORMAT(ipccp.task_date,"%Y-%m-%d")'=>"'$dt'",'ipccp.interaction_type'=>"'7'");
			
		$group_by = 'ipccp.id';
		if(!empty($searchtext))
		{
			$match = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" >> ",ipm.plan_name,ipim.description)'=>$searchtext);
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','','',$group_by,$wherestring,'','1');
		}
		else
		{
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield']." ".$data['sortby'].",cm.id",'',$group_by,$wherestring);
			//echo $this->db->last_query();exit;
			//pr($data['datalist']);exit;
			$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		}
		
		//pr($data['datalist']);exit;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

		$dashboard_task_sortsearchpage_data6 = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('dashboard_task_sortsearchpage_data6', $dashboard_task_sortsearchpage_data6);
		$data['uri_segment'] = $uri_segment;
		$data['tabid'] = '6';
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/to_do_task_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/home/to_do_task_list';
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Get assign contact list
		@Author: Sanjay Chabhadiya
		@Input: - Interaction Plan ID
		@Output: - Contact List
		@Date: 31-12-2014
    */
	
	public function view_contacts_of_interaction_plans()
	{
		//echo "hi";exit;
		$id = $this->input->post('interaction_plan');
		
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$fields = array('cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		//$match = array('ct.interaction_plan_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ipccp.contact_id',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by = 'ipccp.contact_id';
		$where = "ipccp.interaction_plan_interaction_id = ".$id." AND ipccp.is_done = '0' AND (ipccp.interaction_type = 1 OR ipccp.interaction_type = 2 OR ipccp.interaction_type = 5)";
		$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		//	echo $this->db->last_query();
		$this->load->view($this->user_type.'/interaction_plans/view_contact_popup',$data);
	}
	
	/*
		@Description: Calls task reschedule
		@Author: Sanjay Chabhadiya
		@Input: - id,task_date
		@Output: - Contact List
		@Date: 31-12-2014
    */
	
	public function reschedule_call_task($id=0)
	{
		/// After 1 day complete interaction than rescheduled the all depend interaction
		
		$table = "interaction_plan_contact_communication_plan as ipccp";
		$join_tables = array('interaction_plan_interaction_master as ipim' => 'ipccp.interaction_plan_interaction_id = ipim.id'
							 );
		$fields = array('ipccp.id,ipccp.interaction_type,ipim.template_name,ipccp.contact_id,ipim.assign_to','ipim.start_type,ipccp.interaction_plan_id,ipccp.interaction_plan_interaction_id,ipccp.task_date');
		//$match = array();
		$where = "ipccp.id = ".$id." AND ipccp.is_done = '0'";
		$plan_data = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where);
		//pr($plan_data);exit;
		
		$iccdata['id'] = $id;
		$iccdata['is_manualy'] = '1';
		//$data['task_completed_date'] = date('Y-m-d H:i:s');
		//$data['completed_by'] = $this->admin_session['id'];
		
		
		if(!empty($plan_data[0]['assign_to']))
		{
			//Get Working Days
			$assigned_user_id = $plan_data[0]['assign_to'];
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
			$iccdata['interaction_plan_id'] = $plan_data[0]['interaction_plan_id'];
			$iccdata['contact_id'] = $plan_data[0]['contact_id'];
			$iccdata['interaction_plan_interaction_id'] = $plan_data[0]['interaction_plan_interaction_id'];
			$iccdata['interaction_type'] = $plan_data[0]['interaction_type'];
		}
		$date1 = date_create(date("Y-m-d"));
		$date2 = date_create($this->input->post("selecteddate"));
		$diff = date_diff($date1,$date2);
		$count = $diff->format("%a");
		$counttype = 'Days';						
		/*if(!empty($plan_data[0]['task_date']) && $plan_data[0]['task_date'] > date('Y-m-d'))
		{
			$newtaskdate = date("Y-m-d",strtotime($plan_data[0]['task_date']."+ ".$count." ".$counttype));
			$newtaskdate1 = date("Y-m-d",strtotime($plan_data[0]['task_date']."+ ".$count." ".$counttype));
		}
		else
		{*/
			$newtaskdate = date("Y-m-d",strtotime(date('Y-m-d')."+ ".$count." ".$counttype));
			$newtaskdate1 = date("Y-m-d",strtotime(date('Y-m-d')."+ ".$count." ".$counttype));
		//}
		//echo $newtaskdate;exit;
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
		$this->contacts_model->update_interaction_plan_interaction_transtrans_record($iccdata);
		common_rescheduled_task($iccdata['id']);
		return 1;
	}
	public function view_error_data()
	{
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('error_sortsearchpage_data');
        }
        $data['sortfield']		= 'id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('error_sortsearchpage_data');
		
		if(!empty($sortfield) && !empty($sortby))
		{
            //$sortfield = $this->input->post('sortfield');
            $data['sortfield'] = $sortfield;
            //$sortby = $this->input->post('sortby');
            $data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
			        if(!empty($searchsort_session['sortby'])) {
			            $data['sortfield'] = $searchsort_session['sortfield'];
			            $data['sortby'] = $searchsort_session['sortby'];
						$sortfield = $searchsort_session['sortfield'];
			       		$sortby = $searchsort_session['sortby'];
						
			}
            } else {
                $sortfield = 'id';
                $sortby = 'desc';
            }
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    /*$data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
 					$data['searchtext'] = $searchsort_session['searchtext'];

                }
            }
        }
		if(!empty($searchopt))
		{
			//$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		
		if(!empty($perpage) && $perpage != 'null')
		{
            //$perpage = $this->input->post('perpage');
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;	
		}
            else
		{
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
		$config['base_url'] = site_url($this->user_type.'/'."dashboard/view_error_data/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
		$where = array('status'=>1,'created_by'=>$this->admin_session['id']);
		/*if(!empty($searchtext))
		{
			$match=array('type'=>$searchtext);
			$data['error_list'] = $this->obj->select_records1('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
	
			//echo $this->db->last_query();exit;
			$config['total_rows']= count($this->obj->select_records1('','','','','','','','','',$where));
		}
		else
		{*/
			$data['error_list'] = $this->obj->select_records1('',$where,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			//pr($data['error_list']);
			//echo $this->db->last_query();
			$config['total_rows']= $this->obj->select_records1('',$where,'','=','','','','','','1');
		/*}*/
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		//$data['msg'] = $this->message_session['msg'];
                
		$error_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

		$this->session->set_userdata('error_sortsearchpage_data', $error_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;

		$this->load->view($this->user_type.'/home/view_error_popup.php',$data);
	}
	public function update_error()
	{
		
		$id=$this->input->post('remove_id');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$edata['id'] = $id;
			$edata['status'] = 0;
			$this->obj->update_error($edata);
			unset($id);
		}
		
		$searchsort_session = $this->session->userdata('error_sortsearchpage_data');
		
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		
		if($pagingid < 0)
			$pagingid = 0;
//echo 1;
		echo $pagingid;
	}
	public function update_error_data()
	{
		$table1 = "error_data_master as et";
		$where1 = array('et.status'=>1,'et.created_by'=>$this->admin_session['id']);
		echo $this->interaction_plans_model->getmultiple_tables_records($table1,'','','','',$where1,'=','','','et.id','desc','','','','1');
		
	}
}