<?php 
/*
    @Description: Email Library controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 12-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class email_library_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('email_library_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		
		$this->obj = $this->email_library_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	

    /*
    @Description: Function for Get All Email Library List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Email Library list
    @Date: 12-08-2014
    */

    public function index()
    {	
		//check user right
		check_rights('email_library');
		$email_selected_view_session = $this->session->userdata('email_selected_view_session');
        if(!empty($email_selected_view_session['selected_view']))
            $selected_view = $email_selected_view_session['selected_view'];
        else
            $selected_view = '1';
		
		//echo $selected_view;exit;
		//Main
		$main=1;
		if($main == 1)	
		{
			$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
			$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
			
			//////////////////////////////////////////////////////
			
			$selected_cat = $this->input->post('selected_cat');
			$default_selected_cat = $this->input->post('default_selected_cat');
			
			//////////////////////////////////////////////////////
			
			$sortfield = $this->input->post('sortfield');
			$sortby = $this->input->post('sortby');
			$searchopt = $this->input->post('searchopt');
			$perpage = trim($this->input->post('perpage'));
			$allflag = $this->input->post('allflag');
	
			if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				$this->session->unset_userdata('email_library_sortsearchpage_data');
			}
			$data['sortfield']		= 'id';
			$data['sortby']			= 'desc';
			$searchsort_session = $this->session->userdata('email_library_sortsearchpage_data');
			
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
					$sortfield = 'id';
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
			if(!empty($searchopt))
			{
				//$searchopt = $this->input->post('searchopt');
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
			
			if(!empty($selected_cat))
			{
				//$searchtext = $this->input->post('searchtext');
				$data['selected_cat'] = $selected_cat;
			} else {
				if(empty($allflag))
				{
					if(!empty($searchsort_session['selected_cat'])) {
						$data['selected_cat'] = $searchsort_session['selected_cat'];
						$selected_cat =  $data['selected_cat'];
					}
				}
			}
			
			$config['base_url'] = site_url($this->user_type.'/'."email_library/");
			$config['is_ajax_paging'] = TRUE; // default FALSE
			$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
			//$config['uri_segment'] = 3;
			//$uri_segment = $this->uri->segment(3);
			if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				$config['uri_segment'] = 0;
				$uri_segment = 0;
			} else {
				$config['uri_segment'] = 3;
				$uri_segment = $this->uri->segment(3);
			}
			$table = "email_template_master";
			$fields = array('email_template_master.*,mmlcm.category');
			//$fields = array('*');
			$join_tables = array('marketing_master_lib__category_master mmlcm'=>'mmlcm.id = email_template_master.template_category');
			$group_by = 'email_template_master.id';
			
			$wherestring = 'email_send_type = 2 AND email_template_master.created_by IN ('.$this->user_session['agent_id'].')';
			if(!empty($searchtext) || !empty($selected_cat))
			{
				//$match=array('template_name'=>$searchtext,'template_subject'=>$searchtext);
				//$wherestring = 'email_send_type = 2 AND created_by = '.$this->user_session['id'].'';
				
				if(!empty($searchtext))
					$match=array('template_name'=>$searchtext,'template_subject'=>$searchtext);
				else
					$match=array();
				
				if(!empty($selected_cat))
					$wherestring .= ' AND template_category = '.$selected_cat;
	
				$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
				$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
			}
			else
			{
				
				$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
				$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'1');
			}
			
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['msg'] = $this->message_session['msg'];

			$email_library_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'selected_cat' =>!empty($data['selected_cat'])?$data['selected_cat']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			$this->session->set_userdata('email_library_sortsearchpage_data', $email_library_sortsearchpage_data);
			$data['uri_segment'] = $uri_segment;
			
			
			

			
		}
		//Default data
		$def=1;
		if($def == 1)
		{
			$default_selected_cat = $this->input->post('default_selected_cat');
			$default_searchopt='';$default_searchtext='';$searchoption1='';$default_perpage='';
			$default_searchtext = $this->input->post('default_searchtext');
			$default_sortfield = $this->input->post('default_sortfield');
			$default_sortby = $this->input->post('default_sortby');
			$default_searchopt = $this->input->post('default_searchopt');
			$default_perpage = trim($this->input->post('default_perpage'));
			$allflag1 = $this->input->post('allflag1');
				
			if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
				$this->session->unset_userdata('def_email_library_sortsearchpage_data');
			}
			$selected_cat = $this->input->post('selected_cat');
			$data['default_sortfield']		= 'email_template_master.id';
			$data['default_sortby']		= 'desc';
			$searchsort_session1 = $this->session->userdata('def_email_library_sortsearchpage_data');
	
			if(!empty($default_sortfield) && !empty($default_sortby))
			{
				$data['default_sortfield'] = $default_sortfield;
				$data['default_sortby'] = $default_sortby;
			}
			else
			{
				if(!empty($searchsort_session1['sortfield'])) {
					if(!empty($searchsort_session1['sortby'])) {
						$data['default_sortfield'] = $searchsort_session1['sortfield'];
						$data['default_sortby'] = $searchsort_session1['sortby'];
						$default_sortfield = $searchsort_session1['sortfield'];
						$default_sortby = $searchsort_session1['sortby'];
					}
					} else {
						$default_sortfield = 'id';
						$default_sortby = 'desc';
					}
				}
				if(!empty($default_selected_cat))
				{
							//$searchtext = $this->input->post('searchtext');
							$data['default_selected_cat'] = $default_selected_cat;
				} else {
							if(empty($allflag1))
							{
								if(!empty($searchsort_session1['selected_cat'])) {
									$data['default_selected_cat'] = $searchsort_session1['selected_cat'];
									$default_selected_cat =  $data['default_selected_cat'];
								}
							}
						}
				
				///////////////////////////////////////////////
				
			if(!empty($default_searchtext))
			{
				$data['default_searchtext'] = $default_searchtext;
			} else {
				if(empty($allflag1))
				{
					if(!empty($searchsort_session1['searchtext'])) {
						$data['default_searchtext'] = $searchsort_session1['searchtext'];
						$default_searchtext =  $data['default_searchtext'];
					}
				}
			}
			if(!empty($default_searchopt))
			{
				$data['default_searchopt'] = $default_searchopt;
			}
			if(!empty($default_perpage))
			{
				$data['default_perpage'] = $default_perpage;
				$config1['per_page'] = $default_perpage;
			}
			else
			{
				if(!empty($searchsort_session1['perpage'])) {
					$data['default_perpage'] = trim($searchsort_session1['perpage']);
					$config1['per_page'] = trim($searchsort_session1['perpage']);
				} else {
					$config1['per_page'] = '10';
				}
			}
			$config1['base_url'] = site_url($this->user_type.'/'."email_library/");
			$config1['is_ajax_paging'] = TRUE; // default FALSE
			$config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
			$config1['uri_segment'] = 3;
			$uri_segment1 = $this->uri->segment(3);
			
			$table = "email_template_master";
			$fields = array('email_template_master.*,mmlcm.category');
			//$fields = array('email_template_master.*');
			$join_tables = array(
								'login_master as lm' => 'lm.id = email_template_master.created_by',
								'marketing_master_lib__category_master mmlcm'=>'mmlcm.id = email_template_master.template_category'
							);
			
			//$fields = array('*');
			$group_by = 'email_template_master.id';
			if(!empty($default_searchtext) || !empty($default_selected_cat))
			{
				/*$match=array('template_name'=>$default_searchtext,'template_subject'=>$default_searchtext);
				$wherestring='(email_send_type = 2 AND user_type = "1" OR user_type = "2")';*/
				
				if(!empty($default_searchtext))
					$match=array('template_name'=>$default_searchtext,'template_subject'=>$default_searchtext);
				else
					$match=array();
				
				if(!empty($default_selected_cat))
					$wherestring='email_template_master.template_category = '.$default_selected_cat.' AND email_template_master.email_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2")';
				else
					$wherestring='email_template_master.email_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2")';
					
				
				$data['default_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'], $uri_segment1,$data['default_sortfield'],$data['default_sortby'],$group_by,$wherestring);
				$config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
							
			}
			else
			{
				
				$wherestring='email_send_type = 2 AND (user_type = "1" OR user_type = "2")';
				$data['default_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config1['per_page'], $uri_segment1,$data['default_sortfield'],$data['default_sortby'],$group_by,$wherestring);
				$config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'1');
			}
			$this->pagination->initialize($config1);
			$data['pagination1'] = $this->pagination->create_links();
			$data['msg'] = $this->message_session['msg'];
			$def_email_library_sortsearchpage_data = array(
				'sortfield'  => !empty($data['default_sortfield'])?$data['default_sortfield']:'',
				'sortby' =>!empty($data['default_sortby'])?$data['default_sortby']:'',
				'selected_cat' =>!empty($data['default_selected_cat'])?$data['default_selected_cat']:'',
				'searchtext' =>!empty($data['default_searchtext'])?$data['default_searchtext']:'',
				'perpage' => !empty($data['default_perpage'])?trim($data['default_perpage']):'10',
				'uri_segment' => !empty($uri_segment1)?$uri_segment1:'0',
				'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');
				
			$this->session->set_userdata('def_email_library_sortsearchpage_data', $def_email_library_sortsearchpage_data);
			$data['uri_segment1'] = $uri_segment1;
		//End default data
		}
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		//Pass selected tab
		$data['tabid'] = $selected_view;
		//pr($data);
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else if($this->input->post('result_type') == 'default_ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/default_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('user/include/template',$data);
		}
    }

    /*
    @Description: Function Add New Email Library details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Email Library details
    @Date: 12-08-2014
    */
   
    public function add_record()
    {
		//check user right
		check_rights('email_library_add');
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if($id!='')
		{
			$match = array('id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			$data['editRecord'] = $result;
			$data['insert_data']=1;
		}
		
		$data['event']=$this->obj->select_records2();
		$table1='custom_field_master';
		$where1=array('module_id'=>'3');
		$data['tablefield_data']=$this->obj->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$data['communication_plans'] = '';
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    }


/*
    @Description: Function Copy email library details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for copy email library details
    @Date: 12-08-2014
    */
   
    public function copy_record()
    {
		//check user right
		check_rights('email_library_add');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['template_name'] = $result[0]['template_name'].'-copy';
		$cdata['template_category'] = $result[0]['template_category'];
		$cdata['template_subcategory'] = $result[0]['template_subcategory'];
		$cdata['template_subject']=$result[0]['template_subject'];
		$cdata['email_message'] = $result[0]['email_message'];
		$cdata['email_send_type'] =$result[0]['email_send_type'];
		$cdata['is_unsubscribe'] = $result[0]['is_unsubscribe'];
		$cdata['email_event'] = $result[0]['email_event'];
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_copy_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
	}



    /*
    @Description: Function for Insert New Email Library data
    @Author: Mohit Trivedi
    @Input: - Details of new Email Library which is inserted into DB
    @Output: - List of Email Library with new inserted records
    @Date: 12-08-2014
    */
   
    public function insert_data()
     {
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		//$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['template_subject']=$this->input->post('txt_template_subject');
		$cdata['email_message'] = $this->input->post('email_message');
		$cdata['email_send_type'] = 2;
		$cdata['is_unsubscribe'] = $this->input->post('is_unsubscribe');
		$cdata['email_event'] = $this->input->post('email_event');
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
		
     }
 
    /*
    @Description: Get Details of Edit Email Library Profile
    @Author: Mohit Trivedi
    @Input: - Id of Email Library member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 12-08-2014
    */
 
    public function edit_record()
    {
     	//check user right
		check_rights('email_library_edit');
		$id = $this->uri->segment(4);
		//$match = array('id'=>$id,'created_by'=>$this->user_session['id']);
        //$result = $this->obj->select_records('',$match,'','=');
		$where = 'id = '.$id.' AND created_by IN ('.$this->user_session['agent_id'].')';
        $result = $this->obj->getmultiple_tables_records('email_template_master','','','','','','','','','','','',$where);
		$cdata['editRecord'] = $result;
		if(empty($cdata['editRecord']))
		{
			$msg = $this->lang->line('common_right_msg_email');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		$match = array("parent"=>'0');
        $cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
		$cdata['event']=$this->obj->select_records2();
		$table1='custom_field_master';
		$where1=array('module_id'=>'3');
		$cdata['tablefield_data']=$this->obj->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$cdata['main_content'] = "user/".$this->viewName."/add";       
		$this->load->view("user/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Email Library Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Email Library
    @Output: - List with updated Email Library details
    @Date: 12-08-2014
    */
   
    public function update_data()
    {
	    $cdata['id'] = $this->input->post('id');
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		//$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['template_subject']=$this->input->post('txt_template_subject');
		$cdata['email_message'] = $this->input->post('email_message');
		$cdata['email_send_type'] = 2;
		$cdata['is_unsubscribe'] = $this->input->post('is_unsubscribe');
		$cdata['email_event'] = $this->input->post('email_event');
		$cdata['modified_by'] = $this->user_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$searchsort_session = $this->session->userdata('email_library_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
		
    }
	
   /*
    @Description: Function for Delete Email Library Profile By user
    @Author: Mohit Trivedi
    @Input: - Delete id which Email Library record want to delete
    @Output: - New Email Library list after record is deleted.
    @Date: 12-08-2014
    */

    function delete_record()
    {
		//check user right
		check_rights('email_library_delete');       
	    $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete Email Library Profile By user
    @Author: Mohit Trivedi
    @Input: - Delete all id of Email Library record want to delete
    @Output: - Email Library list Empty after record is deleted.
    @Date: 12-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{		
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('email_library_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{	
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
	}
	
	 /*
    @Description: Function for Unpublish Email Library Profile By user
    @Author: Mohit Trivedi
    @Input: - Delete id which Email Library record want to Unpublish
    @Output: - New Email Library list after record is Unpublish.
    @Date: 12-08-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
    }
	
	/*
    @Description: Function for publish Email Library Profile By user
    @Author: Mohit Trivedi	
    @Input: - Delete id which Email Library record want to publish
    @Output: - New Email Library list after record is publish.
    @Date: 12-08-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
    }
	
	/*
    @Description: Function for Sub category
    @Author: Mohit Trivedi	
    @Input: - get id which Sub category record 
    @Output: - New Email Library list after record.
    @Date: 12-08-2014
    */

	
	public function ajax_subcategory()
	{
		$id=$this->input->post('loadId');
		
		if(!empty($id))
		{
			$match = array("parent"=>$id);
        	$cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			echo json_encode($cdata['subcategory']);
		}
	
		
	}
	/*
        @Description: Function for set selected view session for interaction plan listing
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 13-10-2014
    */
    public function selectedview_session()
    {
		$selected_view = $this->input->post('selected_view');
		
        $sortsearchpage_data = array(
            'sortfield'  => 'email_template_master.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        if($selected_view == '1')
            $this->session->set_userdata('def_email_library_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('email_library_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('email_selected_view_session',$data);
    }
	

}