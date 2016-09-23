<?php 
/*
    @Description: Email Signature controller
    @Author: Ruchi Shahu
    @Input: 
    @Output: 
    @Date: 2-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class email_signature_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->
user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('email_signature_model');
		
		$this->obj = $this->email_signature_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	

    /*
    @Description: Function for Get All email signature List
    @Author: Ruchi Shahu
    @Input: - Search value or null
    @Output: - all email signature list
    @Date: 02-08-2014
    */
    public function index()
    {	
		//check user right
		check_rights('email_signature');
		
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
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/'."email_signature/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		$and_match_value = array('created_by IN_query IN'=>$this->user_session['agent_id']);
		if(!empty($searchtext))
		{
			$match1=array('signature_name'=>$searchtext,'full_signature'=>$searchtext);
			//$match=array('created_by'=>$this->user_session['id']);
			$data['datalist'] = $this->obj->select_records('',$match1,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$match1,$and_match_value);
			$config['total_rows'] = $this->obj->select_records('',$match1,'','like','','','','','','',$and_match_value,'','1');
		}
		else
		{
			//$match=array('created_by'=>$this->user_session['id']);
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$and_match_value);	
			$config['total_rows']= $this->obj->select_records('','','','','','','','','','',$and_match_value,'','1');
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
			$this->load->view('user/include/template',$data);
		}
    }

    /*
    @Description: Function Add New email signature details
    @Author: Ruchi Shahu
    @Input: - 
    @Output: - Load Form for add email signature details
    @Date: 02-08-2014
    */
    public function add_record()
    {
        //check user right
		check_rights('email_signature_add');
		$fields = array('id,name');     

		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    }

    /*
    @Description: Function for Insert New email signature data
    @Author: Ruchi Shahu
    @Input: - Details of new email signature which is inserted into DB
    @Output: - List of email signature with new inserted records
    @Date: 02-08-2014
    */
    public function insert_data()
    {
		//pr($_POST);exit;
		$cdata['signature_name'] = $this->input->post('signature_name');
		$cdata['full_signature'] = $this->input->post('full_signature');
		$cdata['created_by'] = $this->user_session['id'];     
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = 1;
		$cdata['is_default'] = 0;
         //  pr($cdata);exit; 
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);				
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
    /*
    @Description: Get Details of Edit email signature
    @Author: Ruchi Shahu
    @Input: - Id of email signature member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 20-11-2013
    */
    public function edit_record()
    {
        //check user right
		check_rights('email_signature_edit');
		$id = $this->uri->segment(4);
        $data['smenu_title']=$this->lang->line('user_left_menu15');
        $data['submodule']=$this->lang->line('user_left_ssclient');
        $fields = array('id,name,created_by');
        $match = array('id'=>$id,'created_by'=>$this->user_session['id']);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		if(empty($data['editRecord']))
		{
			$msg = $this->lang->line('common_right_msg_email_signature');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		
		$data['main_content'] = "user/".$this->viewName."/add";       
	   	$this->load->view("user/include/template",$data);
    }

    /*
    @Description: Function for Update email signature
    @Author: Ruchi Shahu
    @Input: - Update details of email signature
    @Output: - List with updated email signature
    @Date: 02-08-2014
    */
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
		$cdata['signature_name'] = $this->input->post('signature_name');
		$cdata['full_signature'] = $this->input->post('full_signature');   
		$cdata['modified_by'] = $this->user_session['id'];     
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_record($cdata);
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
    }
    /*
    @Description: Function for Delete email signature By user
    @Author: Ruchi Shahu
    @Input: - Delete id which email signature record want to delete
    @Output: - New email signature list after record is deleted.
    @Date: 02-08-2014
    */
    function delete_record()
    {
        //check user right
		check_rights('email_signature_delete');
		$id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		//pr($cdata);exit;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish email signature By user
    @Author: Ruchi Shahu
    @Input: - Delete id which email signature record want to publish
    @Output: - New email signature list after record is publish.
    @Date: 04-07-2014
    */
	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '1';
		//pr($cdata);exit;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
	
	/*
    @Description: Function to delete email signature list
	@Author: Ruchi Shahu
    @Input: Array
    @Output: - 
    @Date: 04-08-2014
    */
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}	
		 
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_record($array_data[$i]);
		}
		echo 1;
	}
	
	/*
    @Description: Function to default signature selected
	@Author: Ruchi Shahu
    @Input: Array
    @Output: - 
    @Date: 04-08-2014
    */
	function ifselected()
	{
		$id = $this->input->post('id');
		$data['is_default'] = 0;
		$this->obj->update_record($data);
		
		$data['id'] = $id;
		$data['is_default'] = 1;
		$this->obj->update_record($data);
	}

	/*
    @Description: Function to change default signature
	@Author: Ruchi Shahu
    @Input: Array
    @Output: - 
    @Date: 04-08-2014
    */
	public function changedefaulttemplate()
	{
		$user_id = $this->user_session['id'];	
		//$match = array('created_by'=>$user_id);
		$alltemplates = $this->obj->select_records('','','','=');
		$post_val = $this->input->post('selectedvalue');
		
		foreach($alltemplates as $row)
		{
			//pr($row); // exit;
		
			if($post_val == $row['id'])
			{
				$data['is_default'] = 1;
			}	
			else
			{
				$data['is_default'] = 0;
			}
			
			$data['id'] = $row['id'];
			$this->obj->update_record($data);
			
			//echo $data['id'];
		}
		
	}
} 