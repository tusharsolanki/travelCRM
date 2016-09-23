<?php 
/*
	@Description: Admin Management controller
	@Author: Sanjay Chabhadiya
	@Input: 
	@Output: 
	@Date: 01-09-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class assign_package_control extends CI_Controller
{	
    function __construct()
    {
	    parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		$this->message_session = $this->session->userdata('message_session');
	    check_superadmin_login();
		$this->load->model('admin_model');
		$this->load->model('package_management_model');
		$this->load->model('common_function_model');
   	    $this->obj = $this->package_management_model;
	    $this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';

    }
	
    /*
		@Description: Function for Get All Admin List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all Admin list
		@Date: 01-09-2014
    */

    public function index()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		$data['sortfield']		= 'upt.id';
		$data['sortby']			= 'desc';
		
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('assign_package_sortsearchpage_data');
		}
		$searchsort_session = $this->session->userdata('assign_package_sortsearchpage_data');
		
		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'upt.id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage))
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
        else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'."assign_package/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
		
		$table ="user_package_trans as upt";
		$fields = array('lm.admin_name,pm.package_name,pm.email_counter,pm.sms_counter,pm.contacts_counter','lm.email_id');
		$join_tables = array('package_master as pm'=>'pm.id = upt.package_id','login_master as lm'=>'lm.id = upt.login_id');
		
		$wherestring = "lm.user_type = '2'";
		if(!empty($searchtext))
		{
			$match=array('lm.admin_name'=>$searchtext,'pm.package_name'=>$searchtext,'pm.email_counter'=>$searchtext,'pm.sms_counter'=>$searchtext,'pm.contacts_counter'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'1');
		}
		//pr($data['datalist']); exit;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		$sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' 	 => !empty($data['sortby'])?$data['sortby']:'',
				'searchtext' => !empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' 	 => !empty($data['perpage'])?trim($data['perpage']):'',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'');
		$this->session->set_userdata('assign_package_sortsearchpage_data', $sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('superadmin/include/template',$data);
		}
    }


    /*
    @Description: Function Add New Admin details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add Admin details
    @Date: 01-09-2014
    */
   
    public function add_record()
    {
		$match = array('user_type'=>'2');
		$data['admin_data'] = $this->admin_model->get_user('',$match,'','');
		
		$match = array('status'=>'Active');
		$data['package_data'] = $this->obj->select_records('',$match,'','=');
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }
  
    /*
		@Description: Function for Insert User package trans
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - List User package
		@Date: 01-09-2014
    */
   
    public function insert_data()
    {
		$admin_id = $this->input->post('admin_name');
		$cdata['package_id'] = $this->input->post('package_name');
		$match = array('id'=>$cdata['package_id']);
		$result = $this->obj->select_records('',$match,'','=');
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		
		if(count($admin_id) > 0)
		{
			for($i=0;$i<count($admin_id);$i++)
			{
				$cdata['login_id'] = $admin_id[$i];
				$match = array('id'=>$admin_id[$i]);
				$res = $this->admin_model->get_user('',$match,'','=');
				if(count($res) > 0 && count($result) > 0)
				{
					$idata['id'] = $admin_id[$i];
					$idata['remain_emails'] = $res[0]['remain_emails'] + $result[0]['email_counter'];
					$idata['remain_sms'] = $res[0]['remain_sms'] + $result[0]['sms_counter'];
					$idata['remain_contacts'] = $res[0]['remain_contacts'] + $result[0]['contacts_counter'];
					$this->admin_model->update_user($idata);	
					
					///////////////////////////////////
			
					/*$match = array('id'=>$this->input->post('id'));
					$parent_login = $this->admin_model->get_user('',$match,'','=');*/
					
					//pr($parent_login);exit;
					
					if(!empty($res[0]['email_id']) && !empty($res[0]['db_name']))
					{
						$childdb = $res[0]['db_name'];
						$update_parent_data['email_id'] = $res[0]['email_id'];
						
						$child_result = $this->admin_model->get_child_login_details($childdb,$update_parent_data);
						
						if(!empty($child_result[0]['email_id']))
						{
							$update_parent_data['remain_emails'] = $child_result[0]['remain_emails'] + $result[0]['email_counter'];
							$update_parent_data['remain_sms'] = $child_result[0]['remain_sms'] + $result[0]['sms_counter'];
							$update_parent_data['remain_contacts'] = $child_result[0]['remain_contacts'] + $result[0]['contacts_counter'];
							$update_parent_data['modified_date'] = date('Y-m-d H:i:s');
							
							$lastId = $this->admin_model->update_child_user_record($childdb,$update_parent_data);
						}
						
					}
					
					///////////////////////////////////
					
				}
				$this->obj->insert_user_package($cdata);
				//echo $this->db->last_query(); exit;
			}
		}

		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
		
     }
 
    /*
		@Description: Get Details of Edit Admin Profile
		@Author: Sanjay Chabhadiya
		@Input: - Id of Admin member whose details want to change
		@Output: - Details of stff which id is selected for update
		@Date: 01-09-2014
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Admin Profile
    @Author: Sanjay Chabhadiya
    @Input: - Update details of Admin
    @Output: - List with updated Admin details
    @Date: 01-09-2014
    */
   
    public function update_data()
    {
	    $cdata['id'] = $this->input->post('id');
		$cdata['package_name'] = $this->input->post('package_name');
		$cdata['email_counter'] = $this->input->post('email_counter');
		$cdata['sms_counter'] = $this->input->post('sms_counter');
		$cdata['contacts_counter']=$this->input->post('contacts_counter');

		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect(base_url('superadmin/'.$this->viewName));
		
    }
	
   /*
    @Description: Function for Delete Admin Profile By Superadmin
    @Author: Sanjay Chabhadiya
    @Input: - Delete id which Admin record want to delete
    @Output: - New Admin list after record is deleted.
    @Date: 01-09-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete superadmin Profile By Superadmin
    @Author: Sanjay Chabhadiya
    @Input: - Delete all id of superadmin record want to delete
    @Output: - superadmin post list Empty after record is deleted.
    @Date: 30-08-2014
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
    @Description: Function for Unpublish Admin Profile By Superadmin
    @Author: Sanjay Chabhadiya
    @Input: - Delete id which Admin record want to Unpublish
    @Output: - New Admin list after record is Unpublish.
    @Date: 01-09-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
    }
	
	/*
    @Description: Function for publish Admin Profile By Superadmin
    @Author: Sanjay Chabhadiya
    @Input: - Delete id which Admin record want to publish
    @Output: - New Admin post list after record is publish.
    @Date: 01-09-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '1';
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
    }
	/*
    @Description: Function for check Admin already exist
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - 
    @Date: 01-09-2014
    */

	public function assign_package()
	{
		$data['main_content'] = "superadmin/".$this->viewName."/assign_package";
        $this->load->view('superadmin/include/template', $data);	
	}
}