<?php 
/*
    @Description: Contact controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 28-06-14

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class general_configuration_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('general_configuration_model');
		
		$this->obj = $this->general_configuration_model;
		$this->viewName = $this->router->uri->segments[2];
		
    }
	
    /*
    @Description: Function for Get All contact List
    @Author: Mit Makwana
    @Input: - Search value or null
    @Output: - all contact list
    @Date: 28-06-2014
    */
	
    public function index()
    {	
        redirect('admin/'.$this->viewName."/add_record");
    }

    /*
    @Description: Function Add New contact details
    @Author: Mit Makwana
    @Input: - 
    @Output: - Load Form for add contact details
    @Date: 28-06-2014
    */
    public function add_record()
    {
        /*$fields = array('id,name');  
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);*/
		
		//$match = array("created_by"=>$this->admin_session['id']);
		$match = array();
		$data['user_type'] = $this->obj->select_records1('',$match,'','=','','','','id','desc','user__user_type_master');		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
    }

	
    /*
    @Description: Dipaly all Function Data n List
    @Author: Mit Makwana
    @Input: - 
    @Output: - 
    @Date: 28-06-2014
    */
	public function all_listing()
	{
		//$match = array("created_by"=>$this->admin_session['id']);
		$match = array();
		$data['user_type'] = $this->obj->select_records1('',$match,'','=','','','','','','user__user_type_master');
		//pr($user_type);exit;	
		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
	}

	
    /*
    @Description: Function for Insert New contact data
    @Author: Mit Makwana
    @Input: - Details of new contact which is inserted into DB
    @Output: - List of contact with new inserted records
    @Date: 28-06-2014
    */
    public function insert_user()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('user_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		
		$this->obj->insert_user($cdata);	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function update_user()
    {
		$cdata['id'] = $this->input->post('user_id');
		$cdata['name'] = $this->input->post('user_type');		
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
  }
	
	/*
    @Description: Function for Delete contact Profile By Admin
    @Author: Mit Makwana
    @Input: - Delete id which contact record want to delete
    @Output: - New contact list after record is deleted.
    @Date: 28-06-2014
    */
    function delete_user_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_user_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	
	
}