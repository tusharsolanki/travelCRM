<?php 
/*
    @Description: Email Library controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 12-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class letter_library_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('letter_library_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		
		$this->obj = $this->letter_library_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		$this->parent_db_name = $this->config->item('parent_db_name');
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
		check_rights('letter_library');
		$phone1_selected_view_session = $this->session->userdata('phone1_selected_view_session');
        if(!empty($phone1_selected_view_session['selected_view']))
            $selected_view = $phone1_selected_view_session['selected_view'];
        else
            $selected_view = '1';
		
		$main=1;
		if($main == 1)	
		{
			$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
			$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
			
			//////////////////////////////////////////////////////
			
			$selected_cat = $this->input->post('selected_cat');
			
			//////////////////////////////////////////////////////
			
			$sortfield = $this->input->post('sortfield');
			$sortby = $this->input->post('sortby');
			$searchopt = $this->input->post('searchopt');
			$perpage = trim($this->input->post('perpage'));
			$allflag = $this->input->post('allflag');
			
			if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				$this->session->unset_userdata('letter_library_sortsearchpage_data');
			}
			$data['sortfield']		= 'id';
			$data['sortby']			= 'desc';
			$searchsort_session = $this->session->userdata('letter_library_sortsearchpage_data');
			
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
								/*$data['searchtext'] = $searchsort_session['searchtext'];
								$searchtext =  $data['searchtext'];*/
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
			
			$config['base_url'] = site_url($this->user_type.'/'."letter_library/");
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
			
			$table = "letter_template_master";
			$fields = array('letter_template_master.*,mmlcm.category','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name');
			$join_tables = array('marketing_master_lib__category_master mmlcm'=>'mmlcm.id = letter_template_master.template_category',
								 'login_master as lm'                          => 'lm.id = letter_template_master.created_by',
							 	 'user_master as um'                           => 'um.id = lm.user_id');
			$group_by = 'letter_template_master.id';
			
			if(!empty($searchtext) || !empty($selected_cat))
			{
				
				if(!empty($searchtext))
					$match=array('letter_template_master.template_name'=>$searchtext,'letter_template_master.template_subject'=>$searchtext);
				else
					$match=array();
				
				if(!empty($selected_cat))
					$wherestring = 'letter_template_master.template_category = '.$selected_cat.' AND is_default = 0';
				else
					$wherestring = 'is_default = 0';
				
				$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
				
				$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
				/*$match=array('template_name'=>$searchtext,'template_subject'=>$searchtext);
				$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));*/
			}
			else
			{
				$match = array('is_default'=>'0');
				$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,'');
				$config['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','1');
				/*$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
				$config['total_rows']= count($this->obj->select_records());*/
			}
			
			$match = array("parent"=>'0');
			$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['msg'] = $this->message_session['msg'];
	
			$letter_library_sortsearchpage_data = array(
				'selected_cat' =>!empty($data['selected_cat'])?$data['selected_cat']:'',
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
				'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

			$this->session->set_userdata('letter_library_sortsearchpage_data', $letter_library_sortsearchpage_data);
			$data['uri_segment'] = $uri_segment;
		}
		$def=1;
		if($def == 1)
		{
		 ////////// For Defgaut library by niral///////////
		$selected_cat1 = $this->input->post('selected_cat1');
        $searchopt1='';$searchtext1='';$searchoption1='';$perpage1='';
        $searchtext1 = $this->input->post('searchtext1');
        $sortfield1 = $this->input->post('sortfield1');
        $sortby1 = $this->input->post('sortby1');
        $searchopt1 = $this->input->post('searchopt1');
        $perpage1 = trim($this->input->post('perpage1'));
        $allflag1 = $this->input->post('allflag1');
			
        if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
            $this->session->unset_userdata('def_letter_library_sortsearchpage_data');
        }
		$selected_cat = $this->input->post('selected_cat');
        $data['sortfield1']		= 'letter_template_master.id';
        $data['sortby1']		= 'desc';
        $searchsort_session1 = $this->session->userdata('def_letter_library_sortsearchpage_data');

        if(!empty($sortfield1) && !empty($sortby1))
        {
            $data['sortfield1'] = $sortfield1;
            $data['sortby1'] = $sortby1;
        }
        else
        {
            if(!empty($searchsort_session1['sortfield'])) {
                if(!empty($searchsort_session1['sortby'])) {
                    $data['sortfield1'] = $searchsort_session1['sortfield'];
                    $data['sortby1'] = $searchsort_session1['sortby'];
                    $sortfield1 = $searchsort_session1['sortfield'];
                    $sortby1 = $searchsort_session1['sortby'];
                }
            } else {
                $sortfield1 = 'id';
                $sortby1 = 'desc';
            }
        }
		if(!empty($selected_cat1))
		{
                    //$searchtext = $this->input->post('searchtext');
                    $data['selected_cat1'] = $selected_cat1;
		} else {
                    if(empty($allflag1))
                    {
                        if(!empty($searchsort_session1['selected_cat'])) {
                            $data['selected_cat1'] = $searchsort_session1['selected_cat'];
                            $selected_cat1 =  $data['selected_cat1'];
                        }
                    }
                }
		
		///////////////////////////////////////////////
			
        if(!empty($searchtext1))
        {
            $data['searchtext1'] = $searchtext1;
        } else {
            if(empty($allflag1))
            {
                if(!empty($searchsort_session1['searchtext'])) {
                    $data['searchtext1'] = $searchsort_session1['searchtext'];
                    $searchtext1 =  $data['searchtext1'];
                }
            }
        }
        if(!empty($searchopt1))
        {
            $data['searchopt1'] = $searchopt1;
        }
        if(!empty($perpage1))
        {
            $data['perpage1'] = $perpage1;
            $config1['per_page'] = $perpage1;
        }
        else
        {
            if(!empty($searchsort_session1['perpage'])) {
                $data['perpage1'] = trim($searchsort_session1['perpage']);
                $config1['per_page'] = trim($searchsort_session1['perpage']);
            } else {
                $config1['per_page'] = '10';
            }
        }
        $config1['base_url'] = site_url($this->user_type.'/'."letter_library/");
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if((!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) || $selected_view == '1') {
            $config1['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config1['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
		$table = "letter_template_master";
		$fields = array('letter_template_master.*,mmlcm.category,et.superadmin_publish_date as parent_superadmin_publish_date,et.publish_flag as parent_publish_flag');
		$join_tables = array('marketing_master_lib__category_master mmlcm'=>'mmlcm.id = letter_template_master.template_category',''.$this->parent_db_name.'.letter_template_master et'=>'et.id = letter_template_master.superadmin_template_id');
		$group_by = 'letter_template_master.id';

		if(!empty($searchtext1) || !empty($selected_cat1))
		{
			if(!empty($searchtext1))
				$match=array('letter_template_master.template_name'=>$searchtext1,'letter_template_master.template_subject'=>$searchtext1);
			else
				$match=array();
			//$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			//$config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));
			
			if(!empty($selected_cat1))
				$wherestring='letter_template_master.template_category = '.$selected_cat1.' AND letter_template_master.is_default = 1';
			else
				$wherestring='letter_template_master.is_default = 1';
			$data['default_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'], $uri_segment,$data['sortfield1'],$data['sortby1'],$group_by,$wherestring);
			$config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','','','','','',$group_by,$wherestring,'1');
			
					
		}
		else
		{
			$match = array('letter_template_master.is_default' => 1);
			//$data['datalist'] = $this->obj->select_records('',$match,'','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$data['default_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'], $uri_segment,$data['sortfield1'],$data['sortby1'],$group_by,'');
			//pr($data['default_datalist']);exit;
			//echo $this->db->last_query();exit;
			$config1['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,'','1');
			
			
		}
       
        $this->pagination->initialize($config1);
        $data['pagination1'] = $this->pagination->create_links();

        $def_letter_library_sortsearchpage_data = array(
			'selected_cat' =>!empty($data['selected_cat1'])?$data['selected_cat1']:'',
			'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
			'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
			'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
			'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');
			
        $this->session->set_userdata('def_letter_library_sortsearchpage_data', $def_letter_library_sortsearchpage_data);
        $data['uri_segment1'] = $uri_segment;

     
		$data['tabid'] = $selected_view;
		$status_value='1';
		}
		//Get data from main db
		//$match = array('name'=>'active');
		
		$match = array('is_default'=>'1');
		$field=array('DISTINCT superadmin_template_id');
        $new_temp = $this->obj->select_records($field,$match,'','=','','','','id','desc','');
		//pr($new_temp);exit;
		if(!empty($new_temp))
		{
			foreach($new_temp as $subArray){
				foreach($subArray as $val){
					$newArray[] = $val;
				}
			}
		 	$new_ar=implode(',',$newArray);
			$data['new_template']=$this->obj->check_superadmin_template($new_ar,$this->parent_db_name);
			//pr($data['new_template']);exit;
		}
		else
		{
			$new_ar='';
			$data['new_template']=$this->obj->check_superadmin_template($new_ar,$this->parent_db_name);	
		}
		
		//echo $this->db->last_query();
		//pr($data['new_template']);
		
		//pr($data['premium_plan_update']);exit;
        if($this->input->post('result_type') == 'ajax')
        {	
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
        }
        else if($this->input->post('result_type') == 'ajax1')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/default_ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
            $this->load->view('admin/include/template',$data);
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
		check_rights('letter_library_add');
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
		$where1=array('module_id'=>'7');
		$data['tablefield_data']=$this->obj->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);

		$data['communication_plans'] = '';
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }

	/*
    @Description: Function Add New super admin tempalate
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Envelope details
    @Date: 12-01-2015
    */
	function add_new_template()
	{
		$match = array('is_default'=>'1');
		$field=array('DISTINCT superadmin_template_id');
        $new_temp = $this->obj->select_records($field,$match,'','=','','','','id','desc','');
		if(!empty($new_temp))
		{
			foreach($new_temp as $subArray){
				foreach($subArray as $val){
					$newArray[] = $val;
				}
			}
			$new_ar=implode(',',$newArray);
			$ins=$this->obj->get_new_template($this->parent_db_name,$new_ar);
			//pr($data['new_template']);exit;
		}
		else
		{
			$new_ar='';
			$ins=$this->obj->get_new_template($this->parent_db_name);	
		}
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $letter_library_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('letter_library_sortsearchpage_data', $letter_library_sortsearchpage_data);
		redirect('admin/'.$this->viewName);
	}
	/*
    @Description: Function Add New super admin tempalate update
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Envelope details
    @Date: 12-01-2015
    */
	function update_tempate()
	{
		$id = $this->input->post('id');
		$parent_id = $this->input->post('parent_id');
		
		$match = array('superadmin_template_id'=>$parent_id);
		$field=array('id','template_name');
        $total_temp = $this->obj->select_records($field,$match,'','=','','','','id','asc','');
		//pr($total_temp);exit;
		$next_temp=!empty($total_temp[0]['template_name'])?$total_temp[0]['template_name'].' - '.count($total_temp):'Template - '.count($total_temp);
		$ins=$this->obj->get_update_template($this->parent_db_name,$parent_id,$next_temp);
		$cdata['id'] = $id;
		$cdata['edit_flag'] = '0';
		$this->obj->update_record($cdata);
	}
	/*
        @Description: Function for set selected view session for interaction plan listing
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 13-10-2014
    */
   
	/*public function selectedview_session()
    {
        $selected_view = $this->input->post('selected_view');
        
        $sortsearchpage_data = array(
            'sortfield'  => 'letter_template_master.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        if($selected_view == '2')
            $this->session->set_userdata('def_letter_library_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('letter_library_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('letterlib_selected_view_session',$data);
    }*/
	public function selectedview_session()
    {
        $selected_view = $this->input->post('selected_view');
        
        $sortsearchpage_data = array(
            'sortfield'  => 'letter_template_master.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        if($selected_view == '1')
            $this->session->set_userdata('def_letter_library_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('letter_library_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('phone1_selected_view_session',$data);
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
		check_rights('letter_library_add');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['template_name'] = $result[0]['template_name'].'-copy';
		$cdata['template_category'] = $result[0]['template_category'];
		$cdata['template_subcategory'] = $result[0]['template_subcategory'];
		$cdata['size_w'] 				= $result[0]['size_w'];
		$cdata['size_h'] 				= $result[0]['size_h'];
		$cdata['template_subject']=$result[0]['template_subject'];
		$cdata['letter_content'] = $result[0]['letter_content'];
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_copy_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
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
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['size_w'] = $this->input->post('txt_size_w');
		$cdata['size_h'] = $this->input->post('txt_size_h');
		$cdata['template_subject']=$this->input->post('txt_template_subject');
		$cdata['letter_content'] = $this->input->post('letter_content');
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		
		$insert_id = $this->obj->insert_record($cdata);	
		
		if(!empty($_POST['submitbtn']))
		{
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			/*$this->session->set_userdata('message_session', $newdata);
                        $letter_library_sortsearchpage_data = array(
                            'sortfield'  => 'id',
                            'sortby' => 'desc',
                            'searchtext' =>'',
                            'perpage' => '',
                            'uri_segment' => 0);
            $this->session->set_userdata('letter_library_sortsearchpage_data', $letter_library_sortsearchpage_data);*/
			$searchsort_session = $this->session->userdata('letter_library_sortsearchpage_data');
			$paging = $searchsort_session['uri_segment'];
			redirect('admin/'.$this->viewName.'/'.$paging);
		}
		elseif(!empty($_POST['mailout']))
		{
			$data['template_type'] = $this->input->post('template_type');
			
			if($data['template_type'] == 'Letter')
			{
				$data['template_data'] = $this->letter_library_model->select_records();
		
				$match = array('id'=>$insert_id);
				$result = $this->letter_library_model->select_records('',$match,'','=');
				$data['editRecord'] = $result;
				$data['template_name'] =  $data['editRecord'][0]['template_name'];
				//pr($data['template_data']);
				
				$match = array("parent"=>'0');
					$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
				$match = array("parent"=>'0');
					$data['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
			
				
				///////////////////////////////////////////////////////////////////
				// Assign Contact List
				
					$config['per_page'] = '5';	
					$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
					$config['is_ajax_paging'] = TRUE; // default FALSE
					$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
					$config['uri_segment'] = 3;
					$uri_segment = $this->uri->segment(3);
					
					$table = "contact_master as cm";
					$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
					$join_tables = array(
										'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
									);
					$group_by='cm.id';
					
					$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
					
					$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','1');
					
					$this->pagination->initialize($config);
				
					$data['pagination'] = $this->pagination->create_links();
				
					//pr($data['contact_list']);exit;
					
				///////////////////////////////////////////////////////////////////
				
				$data['main_content'] = "admin/mail_out/add";
        		$this->load->view('admin/include/template', $data);
			}
		 }
		
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
		check_rights('letter_library_edit');
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		$match = array("parent"=>'0');
        $cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
		$cdata['event']=$this->obj->select_records2();
		$table1='custom_field_master';
		$where1=array('module_id'=>'7');
		$cdata['tablefield_data']=$this->obj->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
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
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['size_w'] = $this->input->post('txt_size_w');
		$cdata['size_h'] = $this->input->post('txt_size_h');
		$cdata['template_subject']=$this->input->post('txt_template_subject');
		$cdata['letter_content'] = $this->input->post('letter_content');
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		/*$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect(base_url('admin/'.$this->viewName));*/
		
		$this->obj->update_record($cdata);
		
		$update_id = $cdata['id'];

		if(!empty($_POST['submitbtn']))
		{
			$msg = $this->lang->line('common_edit_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			//$letter_id = $this->input->post('id');
			//$pagingid = $this->obj->getletterpagingid($letter_id);
                        $searchsort_session = $this->session->userdata('letter_library_sortsearchpage_data');
                        $pagingid = $searchsort_session['uri_segment'];
			redirect('admin/'.$this->viewName.'/'.$pagingid);
		}
		elseif(!empty($_POST['mailout']))
		{
			$data['template_type'] = $this->input->post('template_type');
			if($data['template_type'] == 'Letter')
			{
				$data['template_data'] = $this->letter_library_model->select_records();
				
				$match = array('id'=>$update_id);
				$result = $this->letter_library_model->select_records('',$match,'','=');
				$data['editRecord'] = $result;
				$data['template_name'] =  $data['editRecord'][0]['template_name'];
				//pr($data['template_name']);exit;

				$match = array("parent"=>'0');
					$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
				$match = array("parent"=>'0');
					$data['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
			
				
				///////////////////////////////////////////////////////////////////
				// Assign Contact List
				
					$config['per_page'] = '5';	
					$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
					$config['is_ajax_paging'] = TRUE; // default FALSE
					$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
					$config['uri_segment'] = 3;
					$uri_segment = $this->uri->segment(3);
					
					$table = "contact_master as cm";
					$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
					$join_tables = array(
										'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
									);
					$group_by='cm.id';
					
					$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
					
					$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','1');
					$this->pagination->initialize($config);
					$data['pagination'] = $this->pagination->create_links();
					$table1='custom_field_master';
					$where1=array('module_id'=>'4');
					$data['tablefield_data']=$this->obj->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
					//pr($data['contact_list']);exit;
					
				///////////////////////////////////////////////////////////////////
			
				$data['main_content'] = "admin/mail_out/add";
        		$this->load->view('admin/include/template', $data);

			}
		 }
    }
	
   /*
    @Description: Function for Delete Email Library Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete id which Email Library record want to delete
    @Output: - New Email Library list after record is deleted.
    @Date: 12-08-2014
    */

    function delete_record()
    {
		//check user right
		check_rights('letter_library_delete');
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete Email Library Profile By Admin
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
			$match = array('id'=>$id);
			$field=array('superadmin_template_id','template_name');
			$total_temp = $this->obj->select_records($field,$match,'','=','','','','id','asc','');
			if(!empty($total_temp))
			{
				$this->obj->delete_record($id);
				$match = array('superadmin_template_id'=>$total_temp[0]['superadmin_template_id']);
				$field=array('id','superadmin_template_id','template_name');
				$total_temp1 = $this->obj->select_records($field,$match,'','=','','1','','id','desc','');
				if(!empty($total_temp1))
				{
				$cdata['id'] = $total_temp1[0]['id'];
				$cdata['edit_flag'] = '1';
				$this->obj->update_record($cdata);
				//echo $this->db->last_query();exit;
				}
			}
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$match = array('id'=>$array_data[$i]);
				$field=array('superadmin_template_id','template_name');
				$total_temp = $this->obj->select_records($field,$match,'','=','','','','id','asc','');
				if(!empty($total_temp))
				{
					$this->obj->delete_record($array_data[$i]);
					$match = array('superadmin_template_id'=>$total_temp[0]['superadmin_template_id']);
					$field=array('id','superadmin_template_id','template_name');
					$total_temp1 = $this->obj->select_records($field,$match,'','=','','1','','id','desc','');
					if(!empty($total_temp1))
					{
					$cdata['id'] = $total_temp1[0]['id'];
					$cdata['edit_flag'] = '1';
					$this->obj->update_record($cdata);
					//echo $this->db->last_query();exit;
					}
				}
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('letter_library_sortsearchpage_data');
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
		//echo 1;
	}
	
	 /*
    @Description: Function for Unpublish Email Library Profile By Admin
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
		$letter_id = $id;
		$pagingid = $this->obj->getletterpagingid($letter_id);
		redirect('admin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Email Library Profile By Admin
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
		$letter_id = $id;
		$pagingid = $this->obj->getletterpagingid($letter_id);
		redirect('admin/'.$this->viewName.'/'.$pagingid);
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
			if(!empty($cdata['subcategory'])){
				for($i=0;$i<count($cdata['subcategory']);$i++)
				{
					$cdata['subcategory'][$i]['category'] = ucwords($cdata['subcategory'][$i]['category']);
				}
			}
			echo json_encode($cdata['subcategory']);
		}
	
		
	}
	

}