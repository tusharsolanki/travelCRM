<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{ 
    function __construct()
    {
		parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
        check_user_login();
		$this->load->model('contact_masters_model'); 
		$this->load->model('dashboard_model'); 
		$this->obj = $this->dashboard_model;
		$this->obj1 = $this->contact_masters_model;
		$this->user_type = 'user';
		
	}

    public function index()
    {
		$doc_session_array = $this->session->userdata($this->lang->line('common_user_session_label'));
        ($doc_session_array['active'] == true) ? $this->display_dashbord() : redirect('login');
    }
	
    public function display_dashbord()
    {
		$this->load->model('dashboard_model'); 
        $data['msg'] = ($this->uri->segment(3) == 'msg') ? $this->uri->segment(4) : '';
		
		$id=$this->user_session['id'];
		
		$current = $this->input->post('date'); 
		//echo $current;
		//$dd=date('Y-m-d',strtotime($current));
		//echo $dd;exit;
		if(empty($current))
		{
			$now_date=date('Y-m-d');
			$now_date1=date('Y-m-d H:i:s');
		}
		else
		{
			$now_date=date('Y-m-d',strtotime($current));
			$c_time=date('H:i:s');
			$now_date1=date('Y-m-d H:i:s',strtotime($current." ".$c_time));
			
		}

			//echo $now_date1; exit;	
			/////////////////////////// Tasks From Task Menu Notification ///////////////////////
		
		$table = "task_master as tm";
		$fields = array('tm.id','tm.task_name','tm.task_date','tm.is_email','tm.email_time_before','tm.email_time_type','tm.is_popup','tm.popup_time_before','tm.popup_time_type','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','tm.created_date','tm.reminder_email_date ','tm.reminder_popup_date','tut.is_close as close_popup','tm.created_by','tut.id as trans_id','tm.is_close');
		$join_tables = array(
							'task_user_transcation as tut' => 'tut.task_id  = tm.id',
							'user_master as um' => 'um.id = tut.user_id'
							);
		$group_by='tm.id';
		$match=array('tut.user_id'=>$id);
		$task_notification = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','','','','','',$group_by,$where,'OR');
		
		//echo $this->db->last_query();exit;
		
		$i=0;
		$counter=0;
		if(!empty($task_notification))
				{
			//pr($task_notification);exit;
					for($j=0;$j < count($task_notification);$j++)
					{
						$k = 0;
						$c = 0;
						
						if($task_notification[$j]['task_date'] == $now_date)
						{
								$data['today_task_name'][$i]=$task_notification[$j]['task_name'];
								if(!empty($task_notification[$j]['user_name']))
								{
									$data['today_task_user_name'][$i]=$task_notification[$j]['user_name'];
								}
								$data['today_task_data'][$i]=$task_notification[$j]['task_date'];
								$k = 1;
						}
						if($k == 1)
							{$i++;}
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
										$data['task_data'][$counter]=$task_notification[$j]['task_date'];
										$data['created_by'][$counter]=$task_notification[$j]['id'];
										$data['close_popup'][$counter]=$task_notification[$j]['close_popup'];
										$data['is_close'][$counter]=$task_notification[$j]['is_close'];
										$data['user_name_own'][$counter]=$task_notification[$j]['user_name_own'];
										$data['trans_id'][$counter]=$task_notification[$j]['trans_id'];
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
										$data['task_data'][$counter]=$task_notification[$j]['task_date'];
										$data['created_by'][$counter]=$task_notification[$j]['id'];
										$data['close_popup'][$counter]=$task_notification[$j]['close_popup'];
										$data['is_close'][$counter]=$task_notification[$j]['is_close'];
										$data['user_name_own'][$counter]=$task_notification[$j]['user_name_own'];
										$data['trans_id'][$counter]=$task_notification[$j]['trans_id'];
										$c = 1;
									}
								}
								if($c == 1)
									$counter++;
							}
							
						}		
					}
				}
		
	//	pr($data['task_name_email']);
//		pr($data['user_name_email']);
//		pr($data['task_name_popup']);
//		pr($data['user_name_popup']);
		
		// pr($data['task_notification']);exit;
		///////////////////////////End  Tasks From Task Menu Notification ///////////////////////
		$data['now_date']=$now_date;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/home/ajax_list',$data);
		}
		else
		{
			$data['main_content'] = "user/home/dashboard";
		$this->load->view('user/include/template',$data);
		}	
		
    }
	public function popup_changes()
	{
		$myarray = $this->input->post('myarray'); 
		$myarray1 = $this->input->post('myarray1'); 
		

		
		for($j=0;$j<count($myarray1);$j++)
		{
			$data['id']=$myarray1[$j];
			$data['is_close']='1';
			$this->obj->update_task_trans($data);
		}
		
		for($i=0;$i<count($myarray);$i++)
		{
			pr($myarray[$i]);
			$data1['id']=$myarray[$i];	
			$data1['is_close']='1';
			//$this->obj->update_task1($data1);	
		}
		exit; 	
		
	}
	
}