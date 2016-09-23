<?php 
/*
	@Description: Admin Management controller
	@Author: Mohit Trivedi
	@Input: 
	@Output: 
	@Date: 01-09-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contact_tab_configuration_control extends CI_Controller
{	
    function __construct()
    {
	    parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		$this->message_session = $this->session->userdata('message_session');
	    check_superadmin_login();
		$this->load->model('admin_model');
		$this->load->model('user_management_model');
		$this->load->model('common_function_model');
   	    $this->obj = $this->admin_model;
	    $this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';

    }
	
    /*
    @Description: Function for Get All Admin List
    @Author: Mohit Trivedi
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
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		
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
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		}
		if(!empty($searchopt))
		{
			$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($date1) && !empty($date2))
		{
			 $date1 = $this->input->post('date1');
			 $date2 = $this->input->post('date2');
			 $data['date1'] = $date1;
           	 $data['date2'] = $date2;	
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/'."contact_tab_configuration/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		
		if(!empty($searchtext))
		{
			$match=array('admin_name'=>$searchtext,'email_id'=>$searchtext);
			$where=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			$config['total_rows'] = count($this->obj->get_user('',$match,'','like',''));
		}
		else
		{
			$match=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
		//	echo $this->db->last_query();exit;	
			$config['total_rows']= count($this->obj->get_user('',$match));
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
			$this->load->view('superadmin/include/template',$data);
		}
    }

	
	 /*
    @Description: Function for Unpublish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to Unpublish
    @Output: - New Admin list after record is Unpublish.
    @Date: 01-09-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['is_buyer_tab'] = '0';
		$this->obj->update_user_buyer($cdata);
		
		
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		//pr($result);exit;
		if(!empty($result[0]['db_name']))
		{
			//$update_data['created_by'] = $result[0]['created_by'];
			$update_data['is_buyer_tab'] = '0';
			$this->obj->update_user_buyer($update_data,$result[0]['db_name']);
			
			$data['is_buyer_tab'] = '0';
			$this->obj->update_admin_user_buyer($data,$result[0]['db_name']);
		
		}
		
		
		$msg = $this->lang->line('common_buyur_off_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $id;
		$pagingid = $this->obj->getadminpagingid($admin_id);
		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to publish
    @Output: - New Admin post list after record is publish.
    @Date: 01-09-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['is_buyer_tab'] = '1';
		$this->obj->update_user_buyer($cdata);
		
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		if(!empty($result[0]['db_name']))
		{
			//$update_data['created_by'] = $result[0]['created_by'];
			$update_data['is_buyer_tab'] = '1';
			$this->obj->update_user_buyer($update_data,$result[0]['db_name']);
			
			$data['is_buyer_tab'] = '1';
			$this->obj->update_admin_user_buyer($data,$result[0]['db_name']);
		
		}
		$msg = $this->lang->line('common_buyur_on_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $id;
		$pagingid = $this->obj->getadminpagingid($admin_id);
		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
}