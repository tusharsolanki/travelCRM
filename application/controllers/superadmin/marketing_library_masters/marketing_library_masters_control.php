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
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('marketing_library_masters_model');

        $this->obj = $this->marketing_library_masters_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All category and subcategory List
        @Author     : Mohit Trivedi
        @Input      : Search value or null
        @Output     : all category and subcategory list
        @Date       : 01-09-2014
    */
    public function index()
    {	
        redirect('superadmin/'.$this->viewName."/add_record");
    }

    /*
        @Description: Function Add New category and subcategory details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add category and subcategory details
        @Date       : 02-09-2014
    */
    public function add_record()
    {
	   	$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';
	 
	    $match = array("user_type"=>1,"parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

		$match = array("user_type"=>1,"parent != "=>0);
        $data['category_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		
        $data['main_content'] = "superadmin/".$this->viewName."/add";   
        $this->load->view("superadmin/include/template",$data);
    }
	
    /*
    @Description: Dipaly all Function Data n List
    @Author     : Mohit Trivedi
    @Input      :  
    @Output     : Listing for category and subcategory  
    @Date       : 01-09-2014
    */
    public function all_listing()
    {
	   	$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';
	 
	    $match = array("user_type"=>1,"parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		
        $data['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$data);
    }

    /*
        @Description: Function for Insert New category and subcategory type data
        @Author     : Mohit Trivedi
        @Input      : Details of new category and subcategory type
        @Output     : List of category and subcategory type
        @Date       : 01-09-2014
    */
    public function insert_plan_type()
    {
        $cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['category'] = $this->input->post('plan_type');
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        if(!empty($cdata['category']))
        {
		  $parent_id=$this->obj->insert_plan_type_parent($cdata);
		}
        //pr($parent_id);exit;
		$fields = array('id,db_name,email_id');
		$match1 = array('user_type'=>'2');
		$user_detail = $this->admin_model->get_user($fields,$match1,'','=');
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
		//pr($parent_id);
		//pr($user_detail);
		//exit;
		
		$i=0;
		if(!empty($parent_id))
		{
			foreach($parent_id as $row1)
			{
				if(count($user_detail) > 0)
				{
					foreach($user_detail as $row)
					{
						$data['created_by'] = '1';
						$data['category'] = $row1['category'];
						$data['created_date'] = date('Y-m-d H:i:s');	
						$data['superadmin_cat_id'] = $row1['id'];
						$data['status'] = '1';
						//pr($data);
						$this->obj->insert_category_record_child($row['db_name'],$data);
						//echo $this->db->last_query();
						unset($data);
						//$this->db->last_query();
						
					}
				}
			}
		}

		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
    
    /*
        @Description: Function for Insert New category and subcategory status data
        @Author     : Mohit Trivedi
        @Input      : Details of new category and subcategory status
        @Output     : List of interaction plan status
        @Date       : 01-09-2014
    */
    public function insert_status()
    {
        $category = $this->input->post('category_name');
		$parent = $this->input->post('slt_category_type');		
		
		if(!empty($category))
		{
			for($i=0;$i<count($category);$i++)
			{
				$cdata['category'] = $category[$i];
				$cdata['parent'] = $parent[$i];		
				$cdata['created_by'] = $this->superadmin_session['id'];
				$cdata['created_date'] = date('Y-m-d H:i:s');		
				$cdata['status'] = '1';
				$this->obj->insert_status($cdata);	
				
				unset($cdata);
			}
		}
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
    /*
        @Description: Function for Update interaction_plan type
        @Author     : Mohit Trivedi
        @Input      : Update details of interaction_plan type
        @Output     : List with updated interaction_plan type
        @Date       : 01-09-2014
    */
    public function update_plan_type()
    {
        $cdata['id'] = $this->input->post('plan_id');
        $cdata['category'] = $this->input->post('plan_type');		
        $cdata['modified_by'] = $this->superadmin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->obj->update_plan_type($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
    /*
        @Description: Function for Update interaction_plan status
        @Author     : Mohit Trivedi
        @Input      : Update details of interaction_plan status
        @Output     : List with updated interaction_plan status
        @Date       : 01-09-2014
    */
    public function update_status()
    {
        $cdata['id'] = $this->input->post('id');
		$cdata['parent'] = $this->input->post('parent_id');
        $cdata['category'] = $this->input->post('category_name');	
        $cdata['modified_by'] = $this->superadmin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->obj->update_status($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
    /*
        @Description: Function for Delete interaction_plan type
        @Author     : Mohit Trivedi
        @Input      : Delete id of interaction_plan type
        @Output     : New interaction_plan list after record is deleted.
        @Date       : 01-09-2014
    */
    function delete_plan_type_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_plan_type_record($id);
		$this->obj->delete_parent_category_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    /*
        @Description: Function for Delete interaction_plan status
        @Author     : Mohit Trivedi
        @Input      : Delete id of interaction_plan status
        @Output     : New interaction_plan list after record is deleted.
        @Date       : 01-09-2014
    */
    function delete_status_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_status_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
}