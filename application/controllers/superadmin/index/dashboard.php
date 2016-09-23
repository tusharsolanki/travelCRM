<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{ 
    function __construct()
    {
		parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		
        check_superadmin_login();
		$this->load->model('contact_masters_model'); 
		$this->load->model('dashboard_model'); 
		$this->obj = $this->dashboard_model;
		$this->obj1 = $this->contact_masters_model;
	}

    public function index()
    {
		$doc_session_array = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
        ($doc_session_array['active'] == true) ? $this->display_dashbord() : redirect('superadmin/login');
    }
	
    public function display_dashbord()
    {
		
		$this->load->model('dashboard_model'); 
        $data['msg'] = ($this->uri->segment(3) == 'msg') ? $this->uri->segment(4) : '';
		
		$id=$this->superadmin_session['id'];
		
		//////////////////////////// Personal Touches Notification ///////////////////
		$table = "interaction_plan_contact_personal_touches as ipcp";
		$fields = array('ipcp.id','ipcp.task','ipcp.contact_id','iptm.name','ipcp.followup_date','ipcp.is_done','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = ipcp.interaction_type',
							'contact_master as cm' => 'cm.id = ipcp.contact_id'
							);
		$group_by='ipcp.id';
		$where=array('ipcp.created_by'=>$id);
		$data['personale_touches'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		
		//////////////////////////// End Personal Touches Notification ///////////////////
		
		/////////////////////////// Tasks From Task Menu Notification ///////////////////////
		$table = "task_master as tm";
		$fields = array('tm.id','tm.task_name','tm.task_date','tm.is_email','tm.email_time_before','tm.email_time_type','tm.is_popup','tm.popup_time_before','tm.popup_time_type','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array(
							'task_user_transcation as tut' => 'tut.task_id  = tm.id',
							'user_master as um' => 'um.id = tut.user_id'
							);
		$group_by='tm.id';
		$where=array('tm.created_by'=>$id);
		//$data['task_notification'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		// pr($data['task_notification']);exit;
		///////////////////////////End  Tasks From Task Menu Notification ///////////////////////
		$data['main_content'] = "superadmin/home/dashboard";
		$this->load->view('superadmin/include/template',$data);
    }
}