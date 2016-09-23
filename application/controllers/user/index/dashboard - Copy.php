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
		}
		else
		{
			$now_date=date('Y-m-d',strtotime($current));
		}

		//echo $now_date; exit;	
			/////////////////////////// Tasks From Task Menu Notification ///////////////////////
		
		$table = "task_master as tm";
		$fields = array('tm.id','tm.task_name','tm.task_date','tm.is_email','tm.email_time_before','tm.email_time_type','tm.is_popup','tm.popup_time_before','tm.popup_time_type','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','tm.created_date');
		$join_tables = array(
							'task_user_transcation as tut' => 'tut.task_id  = tm.id',
							'user_master as um' => 'um.id = tut.user_id'
							);
		$group_by='tm.id';
		$where=array('um.id'=>$id,'tm.task_date'=>"'".$now_date."'");
		$data['task_notification'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
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
}