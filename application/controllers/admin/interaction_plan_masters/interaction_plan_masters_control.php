<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class interaction_plan_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('interaction_plan_masters_model');

        $this->obj = $this->interaction_plan_masters_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All interaction plan List
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all interaction plan list
        @Date       : 09-07-2014
    */
    public function index()
    {	
        redirect('admin/'.$this->viewName."/add_record");
    }

    /*
        @Description: Function Add New interaction plan details
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Load Form for add interaction plan details
        @Date       : 09-07-2014
    */
    public function add_record()
    {
       // $match = array("created_by"=>$this->admin_session['id']);
        $data['plan_type'] = $this->obj->select_records1('','','','=','','','','id','desc','interaction_plan__plan_type_master');
        $data['status'] = $this->obj->select_records1('','','','=','','','','id','desc','interaction_plan__status_master');	
        $data['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$data);
    }
	
    /*
    @Description: Dipaly all Function Data n List
    @Author     : Sanjay Moghariya
    @Input      :  
    @Output     : Listing for interaction plan  
    @Date       : 09-07-2014
    */
    public function all_listing()
    {
        $match = array("created_by"=>$this->admin_session['id']);
        $data['plan_type'] = $this->obj->select_records1('',$match,'','=','','','','','','interaction_plan__plan_type_master');
        $data['status'] = $this->obj->select_records1('',$match,'','=','','','','','','interaction_plan__status_master');	
        $data['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$data);
    }

    /*
        @Description: Function for Insert New interaction plan type data
        @Author     : Sanjay Moghariya
        @Input      : Details of new interaction plan type
        @Output     : List of interaction plan type
        @Date       : 09-07-2014
    */
    public function insert_plan_type()
    {
        $cdata['created_by'] = $this->admin_session['id'];
        $cdata['name'] = $this->input->post('plan_type');
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
		//pr($_POST);exit;
        $this->obj->insert_plan_type($cdata);	
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
    
    /*
        @Description: Function for Insert New interaction plan status data
        @Author     : Sanjay Moghariya
        @Input      : Details of new interaction plan status
        @Output     : List of interaction plan status
        @Date       : 09-07-2014
    */
    public function insert_status()
    {
        $cdata['created_by'] = $this->admin_session['id'];
        $cdata['name'] = $this->input->post('status_add');		
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
		//pr($_POST);exit;
        $this->obj->insert_status($cdata);	
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
    /*
        @Description: Function for Update interaction_plan type
        @Author     : Sanjay Moghariya
        @Input      : Update details of interaction_plan type
        @Output     : List with updated interaction_plan type
        @Date       : 11-07-2014
    */
    public function update_plan_type()
    {
        $cdata['id'] = $this->input->post('plan_id');
        $cdata['name'] = $this->input->post('plan_type');		
        $cdata['modified_by'] = $this->admin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');

        $this->obj->update_plan_type($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        //redirect('admin/'.$this->viewName.'/add_record');
    }
	
    /*
        @Description: Function for Update interaction_plan status
        @Author     : Sanjay Moghariya
        @Input      : Update details of interaction_plan status
        @Output     : List with updated interaction_plan status
        @Date       : 11-07-2014
    */
    public function update_status()
    {
        $cdata['id'] = $this->input->post('status_id');
        $cdata['name'] = $this->input->post('status_type');	
        $cdata['modified_by'] = $this->admin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->obj->update_status($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        //redirect('admin/'.$this->viewName.'/add_record');
    }
	
    /*
        @Description: Function for Delete interaction_plan type
        @Author     : Sanjay Moghariya
        @Input      : Delete id of interaction_plan type
        @Output     : New interaction_plan list after record is deleted.
        @Date       : 11-07-2014
    */
    function delete_plan_type_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_plan_type_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
    /*
        @Description: Function for Delete interaction_plan status
        @Author     : Sanjay Moghariya
        @Input      : Delete id of interaction_plan status
        @Output     : New interaction_plan list after record is deleted.
        @Date       : 11-07-2014
    */
    function delete_status_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_status_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
}