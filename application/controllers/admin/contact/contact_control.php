<?php 
/*
    @Description: tips controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 28-06-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contact_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('contact_model');
		
		$this->obj = $this->contact_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	

    /*
    @Description: Function for Get All tips List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all tips list
    @Date: 28-06-2014
    */
    public function index()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
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
        	$config['per_page'] = '3';
			$data['perpage']='3';
		}
		$config['base_url'] = site_url($this->user_type.'/'."contact/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		if(!empty($searchtext))
		{
			$match=array('first_name'=>$searchtext,'last_name'=>$searchtext,'email'=>$searchtext,'phone_no'=>$searchtext);
			$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			$config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));
		
		}
		else
		{
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$config['total_rows']= count($this->obj->select_records());
		}
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
    @Description: Function Add New tips details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add tips details
    @Date: 28-06-2014
    */
    public function add_record()
    {
        $fields = array('id,name');     

		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }

    /*
    @Description: Function for Insert New tips data
    @Author: Niral Patel
    @Input: - Details of new tips which is inserted into DB
    @Output: - List of tips with new inserted records
    @Date: 28-06-2014
    */
    public function insert_data()
    {
		//pr($_POST);exit;
		$cdata['first_name'] = $this->input->post('first_name');
		$cdata['last_name'] = $this->input->post('last_name');
		$cdata['email'] = $this->input->post('email');   
		$cdata['admin_id'] = $this->admin_session['id'];   
		$cdata['phone_no'] = $this->input->post('phone_no');   
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = 1;
            
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);				
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
    /*
    @Description: Get Details of Edit tips Profile
    @Author: Niral Patel
    @Input: - Id of tips member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 20-11-2013
    */
    public function edit_record()
    {
        $id = $this->uri->segment(4);
        $data['smenu_title']=$this->lang->line('admin_left_menu15');
        $data['submodule']=$this->lang->line('admin_left_ssclient');
        $fields = array('id,name');
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
    }

    /*
    @Description: Function for Update tips Profile
    @Author: Niral Patel
    @Input: - Update details of tips
    @Output: - List with updated tips details
    @Date: 28-06-2014
    */
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
		$cdata['first_name'] = $this->input->post('first_name');
		$cdata['last_name'] = $this->input->post('last_name');
		$cdata['email'] = $this->input->post('email');   
		$cdata['admin_id'] = $this->admin_session['id'];   
		$cdata['phone_no'] = $this->input->post('phone_no');   
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_record($cdata);
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
    }
    /*
    @Description: Function for Delete tips Profile By Admin
    @Author: Niral Patel
    @Input: - Delete id which tips record want to delete
    @Output: - New tips list after record is deleted.
    @Date: 28-06-2014
    */
    function delete_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	 /*
    @Description: Function for Unpublish tips Profile By Admin
    @Author: Niral Patel
    @Input: - Delete id which tips record want to Unpublish
    @Output: - New tips list after record is Unpublish.
    @Date: 28-06-2014
    */
    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		
		$cdata['id'] = $id;
		$cdata['status'] = 0;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish tips Profile By Admin
    @Author: Niral Patel
    @Input: - Delete id which tips record want to publish
    @Output: - New tips list after record is publish.
    @Date: 28-06-2014
    */
	function publish_record()
    {
        $id = $this->uri->segment(4);
				
		$cdata['id'] = $id;
		$cdata['status'] = 1;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
}
