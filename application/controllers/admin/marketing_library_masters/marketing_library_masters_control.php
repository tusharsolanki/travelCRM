<?php
/*
	@Description: Marketing master controller
	@Author		: Mohit Trivedi
	@Input		: 
	@Output		: 
	@Date		: 02-09-2014

*/


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class marketing_library_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('marketing_library_masters_model');

        $this->obj = $this->marketing_library_masters_model;
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
		//check user right
		check_rights('configuration_template_library');
        redirect('admin/'.$this->viewName."/add_record");
    }

    /*
        @Description: Function Add New Marketing master details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add Marketing master details
        @Date       : 02-09-2014	
    */
    public function add_record()
    {
	   	$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';

	    $match = array("parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

		$match = array("parent != "=>0);
        $data['category_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		
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
	   	$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';
	    $match = array("parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		
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
        $cdata['category'] = $this->input->post('plan_type');
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
		if(!empty($cdata['category']))
		{
        	$this->obj->insert_plan_type($cdata);	
        }
		// update already added record
		$udata['update'] = $this->input->post('plantype_update');
		$udata['idd'] = $this->input->post('plan_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['category'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_plan_type($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg' => $msg,'message' => "insert_successfully");
        $this->session->set_userdata('message_session',$newdata);	
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
        $category = $this->input->post('category_name');
		$parent = $this->input->post('slt_category_type');		
		
		if(!empty($category))
		{
			for($i=0;$i<count($category);$i++)
			{
				if(!empty($category[$i]))
				{
					$cdata['category'] = $category[$i];
					$cdata['parent'] = $parent[$i];		
					$cdata['created_by'] = $this->admin_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');		
					$cdata['status'] = '1';
					
					if(!empty($cdata['category'][$i]) && !empty($parent[$i]))
					{
						$this->obj->insert_status($cdata);	
					}
					unset($cdata);
				}
			}
		}
		
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
        $cdata['category'] = $this->input->post('plan_type');		
        $cdata['modified_by'] = $this->admin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
		
		if(!empty($cdata['category']))
		{
			$this->obj->update_plan_type($cdata);
        }
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
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
        $cdata['id'] = $this->input->post('id');
		$cdata['parent'] = $this->input->post('parent_id');
        $cdata['category'] = $this->input->post('category_name');	
        $cdata['modified_by'] = $this->admin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->obj->update_status($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
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
		$this->obj->delete_parent_category_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg,'message' => "delete_successfully");
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