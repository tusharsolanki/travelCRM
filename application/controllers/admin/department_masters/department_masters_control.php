<?php 
/*
    @Description: Contact controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 28-06-14

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class department_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('department_masters_model');
		
		$this->obj = $this->department_masters_model;
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
		//check user right
		check_rights('configuration_contact');
        redirect('admin/'.$this->viewName."/add_record");
    }

    /*
    @Description: Function Add New contact details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add contact details
    @Date: 01-09-2014
    */
    public function add_record()
    {
		$table = "department_master as cem";
		$fields = array('cem.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = cem.created_by');
		$group_by='cem.id';
		$match = array();
		$data['email_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

	
		
		
		$data['main_content'] = "admin/".$this->viewName."/add";       
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
		$table = "contact__email_type_master as cem";
		$fields = array('cem.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = cem.created_by');
		$group_by='cem.id';
		$match = array();
		$data['email_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

	
		
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
    public function insert_department()
    {
    	$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('email_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_department($cdata);
		// update already added record
		$udata['update'] = $this->input->post('email_update');
		$udata['idd'] = $this->input->post('email_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_department($rdata);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	
    /*
    @Description: Function for Update contact Profile
    @Author: Mit Makwana
    @Input: - Update details of contact
    @Output: - List with updated contact details
    @Date: 28-06-2014
    */
    public function update_department()
    {
		$cdata['id'] = $this->input->post('email_id');
		$cdata['name'] = $this->input->post('email_type');		
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_department($cdata);
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
    function delete_department_record()
    {
    	
        $id = $this->uri->segment(4);
        $this->obj->delete_department_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	

	
}