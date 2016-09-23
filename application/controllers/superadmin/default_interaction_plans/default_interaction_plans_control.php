<?php 
/*
    @Description: Interaction Plans controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class default_interaction_plans_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('interaction_plans_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('interaction_model');
		//$this->load->model('imageupload_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('interaction_plans_premium_model');
		$this->obj = $this->interaction_plans_premium_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->interaction_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';
    }
	

    /*
    @Description: Function for Get All contacts List
    @Author: Nishit Modi
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
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

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('default_iplans_sortsearchpage_data');
                }
                $data['sortfield']		= 'ipm.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('default_iplans_sortsearchpage_data');
		
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
							$sortfield = 'id';
							$sortby = 'desc';
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
                            $searchtext =  $data['searchtext'];
							*/
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
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/");
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
		
		$table = " interaction_plan_master_premium as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*','count(ipim.id) as total_interactions','count(DISTINCT cm.id) as contact_counter');
		$join_tables = array(
							'interaction_plan_interaction_master_premium as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'interaction_plan_adminuser_trans ipct' 		=> 'ipct.interaction_plan_id = ipm.id',
							'login_master as cm'				 			=> 'cm.id = ipct.user_id'
						);
		$group_by='ipm.id';
		$status_value='1';
		
		if(!empty($searchtext))
		{
			//pr($searchtext);exit;
			$match=array('ipm.plan_name'=>$searchtext);
			$where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>2);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
		}
		else
		{
			//$match=array('ipm.status'=>$status_value);
			$match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>2);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,'','','1');
			
			
		}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		//pr($data);exit;
		
                $default_iplans_sortsearchpage_data = array(
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
							
                $this->session->set_userdata('default_iplans_sortsearchpage_data', $default_iplans_sortsearchpage_data);
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
	public function view_archive()
    {
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('default_iplan_view_archive_sortsearchpage_data');
                }
                $data['sortfield']		= 'ipm.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('default_iplan_view_archive_sortsearchpage_data');
		
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
                            $searchtext =  $data['searchtext'];
							*/
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
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/view_archive");
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 4;
		//$uri_segment = $this->uri->segment(4);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
		//pr($uri_segment);exit;
		
		$table = " interaction_plan_master_premium as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*','count(ipim.id) as total_interactions','count(DISTINCT cm.id) as contact_counter');
		$join_tables = array(
							'interaction_plan_interaction_master_premium as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'interaction_plan_adminuser_trans ipct' 		=> 'ipct.interaction_plan_id = ipm.id',
							'login_master as cm'				 			=> 'cm.id = ipct.user_id'
						);
		$group_by='ipm.id';
		$status_value='0';
		
		if(!empty($searchtext))
		{
			//pr($searchtext);exit;
			$match=array('ipm.plan_name'=>$searchtext);
			$where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>2);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
		}
		else
		{
			//$match=array('ipm.status'=>$status_value);
			$match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>2);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,'','','1');
			
			
		}
		
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
                $default_iplan_view_archive_sortsearchpage_data = array(
                   'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('default_iplan_view_archive_sortsearchpage_data', $default_iplan_view_archive_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
			$this->load->view('superadmin/include/template',$data);
		}
	}

    /*
    @Description: Function Add New contacts details
    @Author: Nishit Modi
    @Input: - 
    @Output: - Load Form for add contacts details
    @Date: 04-07-2014
    */
    public function add_record()
    {
		$match = array('name'=>'active');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name as contact_name','lm.email_id as email_address');
		$match=array('lm.user_type'=>'2');
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'',$config['per_page'], $uri_segment,'lm.admin_name','asc','');
		//pr($data['contact_list']);exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'','','','','','','','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		//$match = array();
		//$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		//$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		//$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }
	
	
	public function search_contact_ajax()
    {
	
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
	
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$where= array();
		if(!empty($contact_status) && !empty($contact_source))
			$where= array('cm.contact_status'=>$contact_status,'cm.contact_source'=>$contact_source);
		elseif(!empty($contact_status))
			$where= array('cm.contact_status'=>$contact_status);
		elseif(!empty($contact_source))
			$where = array('cm.contact_source'=>$contact_source);
		if(!empty($contact_type))
		{
			$contact_type_array = array('cct.contact_type_id'=>$contact_type);
			$where = array_merge($where,$contact_type_array);
		}
		
		//$where = array_merge($where_search,$where);
		//pr($where); exit;
		$match=array('lm.admin_name'=>$searchtext,'lm.email_id'=>$searchtext);
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name as contact_name','lm.email_id as email_address');
		$match1=array('lm.user_type'=>'2');
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,'','',$match,$match1,'like',$config['per_page'], $uri_segment,'lm.admin_name','asc','',$where);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,'','',$match,$match1,'like','','','','','',$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view("superadmin/".$this->viewName."/add_contact_popup_ajax", $data);
	}

    /*
    @Description: Function for Insert New Interaction Plan data
    @Author: Mit Makwana
    @Input: - Details of new contacts which is inserted into DB
    @Output: - List of contacts with new inserted records
    @Date: 14-07-2014
    */
    public function insert_data()
    {
		//pr($_POST);exit;
		$submitbtn_action=$this->input->post('submitbtn_action');
		$cdata['plan_name'] = $this->input->post('txt_plan_name');
		$cdata['description'] = $this->input->post('txtarea_description');
		$cdata['plan_status'] = $this->input->post('interaction_plan_status_id');
		$cdata['target_audience'] = $this->input->post('txtarea_target_audience');
		$cdata['plan_start_type'] = $this->input->post('plan_start_date'); 
		if($cdata['plan_start_type'] == '1')
		{
			$cdata['start_date'] = '0000-00-00';
		}
		else
		{
			$cdata['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date')));
			//$icdata['start_date'] = $cdata['start_date'];
		}
		
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['modified_date'] = $cdata['created_date'];
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['status'] = '1';
		//$cdata['is_default']='1';
		$cdata['by_superadmin']='2';
		
       //pr($cdata);exit;

		$interaction_plan_id = $this->obj->insert_record($cdata);
		
		$plan_data['plan_name'] = $cdata['plan_name'];
		$plan_data['description'] = $cdata['description'];
		$plan_data['plan_status'] = $cdata['plan_status'];
		$plan_data['target_audience'] = $cdata['target_audience'];
		$plan_data['plan_start_type'] = $this->input->post('plan_start_date');
		$plan_data['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date'))); //echo $plan_data['start_date']; exit;version
		$plan_data['p_p_id'] = $interaction_plan_id;
		$plan_data['version'] = '1';
		$plan_data['by_superadmin'] = '1';
		$plan_data['created_date'] = date('Y-m-d H:i:s');
		$plan_data['status'] = '1';
		
		///////////////////////////// Interaction Plan Time Trans Data /////////////////////////////////////////
		
		$tcdata['interaction_plan_id'] = $interaction_plan_id;
		$tcdata['interaction_time_type'] = '1';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->superadmin_session['id'];
		
		//$this->obj->insert_time_record($tcdata);
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($cdata);
		$interaction_contacts = $this->input->post('finalcontactlist');
		
		$interaction_contacts = explode(",",$interaction_contacts);
		
		//pr($interaction_contacts);exit;
		
		if(!empty($interaction_contacts))
		{
			
			/*$table = "interaction_plan_interaction_master_premium as ipim";
			$fields = array('ipim.*','ipptm.name');
			$join_tables = array(
								'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type'
								);
			$group_by='ipim.id';
			
			$match = array('ipim.interaction_plan_id'=>$plan_id);
			$interactionlist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by);*/
			
			
			
			
			foreach($interaction_contacts as $row)
			{
				if(!empty($row))
				{
					$icdata['interaction_plan_id'] = $interaction_plan_id;
					$icdata['user_id'] = $row;
					$icdata['created_date'] = date('Y-m-d H:i:s');
					$cdata['modified_date'] = $icdata['created_date'];
					$icdata['created_by'] = $this->superadmin_session['id'];
					$icdata['status'] = '1';
					//pr($icdata);exit;
					$this->obj->insert_user_trans_record($icdata);
					
					/*$cdata['by_superadmin'] = '1';
					$cdata['version'] = '1';
					$cdata['created_by'] = $row;
					$cdata['p_p_id'] = $interaction_plan_id;
					$plan_id = $this->interaction_plans_model->insert_record($cdata);*/
					
					/*$fields = array('id,db_name,email_id');
					$match1 = array('id'=>$row);
					$user_detail = $this->admin_model->get_user($fields,$match1,'','=');
					if(count($user_detail) > 0 && !empty($user_detail[0]['db_name']))
					{
						$cdata['user_id'] = $row;
						$this->obj->insert_user_trans_record($cdata);
						$db_name = $user_detail[0]['db_name'];
						
						$match2 = array('email_id'=>$user_detail[0]['email_id'],'db_name'=>$user_detail[0]['db_name']);
						$user_data = $this->admin_model->get_user('',$match2,'','=','','','','','','',$db_name);
						if(!empty($user_data))
						{
							$user_id = $user_data[0]['id'];
							$plan_data['created_by'] = $user_id;
							$last_id = $this->interaction_plans_model->insert_record($plan_data,$db_name);
						}
					}*/
				
				/*//////////// Converation History
						
				if(!empty($row))
				{
					$data_conv['contact_id'] = $row;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $this->input->post('txt_plan_name');
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '2';
					$data_conv['created_by'] = $this->superadmin_session['id'];
					$data_conv['status'] = '1';
					$this->obj->insert_contact_converaction_trans_record($data_conv);
				}
				
				//////////////end Converation history
				
				
					/////////// If interactions added then add contact entry ////////////////////////
					
					if(count($interactionlist) > 0)
					{
						foreach($interactionlist as $row1)
						{
							$icdata1['interaction_plan_id'] = $interaction_plan_id;
							$icdata1['contact_id'] = $row;
							$icdata1['interaction_plan_interaction_id'] = $row1['id'];
							
							if($row1['start_type'] == '1')
							{
								$count = $row1['number_count'];
								
								$counttype = $row1['number_type'];
								
								$newtaskdate = date("Y-m-d",strtotime($icdata['start_date']."+ ".$count." ".$counttype));
								
								$icdata1['task_date'] = $newtaskdate;
							}
							elseif($row1['start_type'] == '2')
							{
								$count = $row1['number_count'];
								
								$counttype = $row1['number_type'];
								
								$interaction_id = $row1['id'];
								
								$interaction_res = $this->obj->get_contact_interaction_task_date($interaction_id,$row);
								
								//pr($interaction_res);
								
								//echo $interaction_res->task_date;
								
								if(!empty($interaction_res->task_date))
								{
									$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
								
									$icdata1['task_date'] = $newtaskdate;
								}
								
							}
							else
							{
								$icdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
							}
							
							$icdata1['created_date'] = date('Y-m-d H:i:s');
							$icdata1['created_by'] = $this->superadmin_session['id'];
							
							$this->obj2->insert_contact_communication_record($icdata1);
							
							unset($icdata1);
						}
						
					}	*/
					
					/////////////////////////////////////////////////////////////////////////////////
					
					unset($icdata);
				}
			}
		}
		
		//exit;
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	

        $default_iplans_sortsearchpage_data = array(
            'sortfield'  => 'ipm.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('default_iplans_sortsearchpage_data', $default_iplans_sortsearchpage_data);
        
		if(!empty($submitbtn_action) && isset($submitbtn_action))
		{
			redirect('superadmin/default_interaction/add_record/'.$interaction_plan_id);	
		}
		else
		{
        	redirect('superadmin/'.$this->viewName);				
		}				
		//redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
    /*
    @Description: Get Details of Edit contacts Profile
    @Author: Nishit Modi
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 04-07-2014
    */
    public function edit_record()
    {
        $id = $this->uri->segment(4);
		$match = array("ipm.id"=>$this->uri->segment(4));
		$table = " interaction_plan_master_premium as ipm";
		$fields = array('ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions');
		
		$join_tables = array(
							'interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master_premium as ipim' => 'ipim.interaction_plan_id = ipm.id'
						);
		
	    $data['editRecord'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','');	 
		
		if(isset($data['editRecord'][0]['status']) && ($data['editRecord'][0]['status'] == '0'))
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('superadmin/'.$this->viewName);	
		}
		
	  	//pr($data['editRecord']);
		$data['interaction_plan_status'] = $this->obj1->select_records1('','','','=','','','','name','asc','interaction_plan__status_master');	
		
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name as contact_name','lm.email_id as email_address');
		$match=array('lm.user_type'=>'2');
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'',$config['per_page'], $uri_segment,'lm.admin_name','asc','');
		//pr($data['contact_list']);exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'','','','','','','','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_adminuser_trans as ct";
		$fields = array('ct.interaction_plan_id','cm.id','cm.admin_name as contact_name');
		$where = array('ct.interaction_plan_id'=>$id);
		$join_tables = array(
							'login_master as cm'=>'cm.id = ct.user_id'
						);
		$group_by='cm.id';
		
		$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.admin_name','asc',$group_by);
		
		//pr($data['contacts_data']);
		
		///////////////////////////////////////////////////////////////////////////	
		
		//$match = array('interaction_plan_id'=>$id);
        //$data['interaction_plan_time_trans'] = $this->obj->select_records_plan_time_trans('',$match,'','=','','','','id','asc');
		
		//$match = array();
		//$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		//$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		//$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		//pr($data['interaction_plan_time_trans']);exit;
		
		///////////////////////////////////////////////////////////////////////////	
		
		$data['main_content'] = "superadmin/".$this->viewName."/add";       
	   	$this->load->view("superadmin/include/template",$data);
    }

    /*
    @Description: Function for Update contacts Profile
    @Author: Nishit Modi
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 04-07-2014
    */
    public function update_data()
    {
		//pr($_POST);
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		/*$id = $this->input->post('id');
		$match = array("id"=>$id);
		$interaction_plan_data_old = $this->obj->select_records('',$match,'','=','','','','','','');*/	 
		$submitbtn_action=$this->input->post('submitbtn_action');
        $cdata['id'] = $this->input->post('id');
		$cdata['plan_name'] = $this->input->post('txt_plan_name');
		$cdata['description'] = $this->input->post('txtarea_description');
		$cdata['plan_status'] = $this->input->post('interaction_plan_status_id');
		$cdata['target_audience'] = $this->input->post('txtarea_target_audience');
		$cdata['plan_start_type'] = $this->input->post('plan_start_date');
		if($cdata['plan_start_type'] == 2)
			$cdata['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date')));
		else
			$cdata['start_date'] = '';
		
		$plan_data['plan_name'] = $cdata['plan_name'];
		$plan_data['description'] = $cdata['description'];
		$plan_data['plan_status'] = $cdata['plan_status'];
		$plan_data['target_audience'] = $cdata['target_audience'];
		$plan_data['plan_start_type'] = $this->input->post('plan_start_date');
		$plan_data['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date'))); //echo $plan_data['start_date']; exit;version
		$plan_data['p_p_id'] = $cdata['id'];
		$plan_data['version'] = '1';
		$plan_data['by_superadmin'] = '1';
		$plan_data['created_date'] = date('Y-m-d H:i:s');
		$plan_data['status'] = '1';
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		/*$id = $this->input->post('id');
		$match = array("id"=>$id);
		$interaction_plan_data_old = $this->obj->select_records('',$match,'','=','','','','','','');*/
		
		//$cdata['modified_date'] = date('Y-m-d H:i:s');
		$cdata['modified_by'] = $this->superadmin_session['id'];
		
		//pr($cdata);exit;

		$this->obj->update_record($cdata);
		//echo $this->db->last_query();exit;
		
		$match = array('p_p_id' => $this->input->post('id'));
		$interaction_plan_result = $this->interaction_plans_model->select_records('',$match,'','=');
		
		if(count($interaction_plan_result) > 0)
			$plan_id = $interaction_plan_result[0]['id'];
		else
			$plan_id = '-1';
						
		//pr($interaction_plan_id); exit;
		$interaction_plan_id = $this->input->post('id');
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$interaction_contacts_list = $this->input->post('finalcontactlist');
		$interaction_contacts = array();
		if(!empty($interaction_contacts_list))
			$interaction_contacts = explode(",",$interaction_contacts_list);
		
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_adminuser_trans as ct";
		$fields = array('ct.interaction_plan_id,ct.user_id','lm.id','lm.admin_name');
		$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
		$join_tables = array(
							'login_master as lm'=>'lm.id = ct.user_id'
						);
		$group_by='lm.id';
		
		$old_contacts_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','','',$group_by);
		
		$old_contacts = array();
		foreach($old_contacts_data as $row)
			$old_contacts[] = $row['user_id'];	
		//echo $this->db->last_query();
		
		///////////////////////////////////////////////////////////////////////////
		
		//$plan_id = $interaction_plan_id;
					
		////////////////////////////////////////////////////////

		$table = "interaction_plan_interaction_master_premium as ipim";
		$fields = array('ipim.*');
		$join_tables = array(
							'interaction_plan_master_premium as ipmp' => 'ipmp.id = ipim.interaction_plan_id',
							);
		
		$group_by='ipim.id';
		
		$where1 = array('ipim.interaction_plan_id'=>$interaction_plan_id);
		
		$interaction_list =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ipim.id','asc',$group_by,$where1);
		$deletecontactdata = array_diff($old_contacts,$interaction_contacts);
		//pr($deletecontactdata);exit;
		if(!empty($deletecontactdata))
		{
			$this->obj->delete_adminuser_record_array($cdata['id'],$deletecontactdata);
			//echo $this->db->last_query();exit;
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
		}
		$final_contact_data = array_diff($interaction_contacts,$old_contacts);
		
		//pr($old_contacts);		
		//pr($interaction_contacts);		
		//pr($final_contact_data);
		//pr($_POST);exit;
		//pr($interaction_list); exit;
		
		unset($cdata);
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['modified_date'] = $cdata['created_date'];
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['interaction_plan_id'] = $interaction_plan_id;
		$cdata['status'] = '1';
		
		$idata['status'] = '1';
		$idata['created_date'] = date('Y-m-d H:i:s');
		
		if(count($final_contact_data) > 0)
		{
			foreach($final_contact_data as $row)
			{	
				$fields = array('id,db_name,email_id');
				$match1 = array('id'=>$row);
				$user_detail = $this->admin_model->get_user($fields,$match1,'','=');
				if(count($user_detail) > 0 && !empty($user_detail[0]['db_name']))
				{
					$cdata['user_id'] = $row;
					$this->obj->insert_user_trans_record($cdata);
					$db_name = $user_detail[0]['db_name'];
					
					/*$match2 = array('email_id'=>$user_detail[0]['email_id'],'db_name'=>$user_detail[0]['db_name']);
					$user_data = $this->admin_model->get_user('',$match2,'','=','','','','','','',$db_name);
					if(!empty($user_data))
					{
						$user_id = $user_data[0]['id'];
						$plan_data['created_by'] = $user_id;
						$last_id = $this->interaction_plans_model->insert_record($plan_data,$db_name);
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
								$idata['interaction_plan_id'] = $last_id;
								
								//$idata['interaction_plan_id'] = $plan_id;
								$idata['assign_to'] = $user_id;
								$idata['created_by'] = $user_id;
								$idata['interaction_type'] = $row1['interaction_type'];
								$idata['description'] = $row1['description'];
								//$idata['assign_to'] = $row1['assign_to'];
								$idata['start_type'] = $row1['start_type'];
								$idata['number_count'] = $row1['number_count'];
								$idata['number_type'] = $row1['number_type'];
								$idata['start_date'] = $row1['start_date'];
								$idata['priority'] = $row1['priority'];
								$idata['drop_type'] = $row1['drop_type'];
								$idata['drop_after_day'] = $row1['drop_after_day'];
								$idata['drop_after_date'] = $row1['drop_after_date'];
								$idata['interaction_notes'] = $row1['interaction_notes'];
								$idata['template_category'] = $row1['template_category'];
								$idata['template_subcategory'] = $row1['template_subcategory'];
								$idata['template_name'] = $row1['template_name'];
								$idata['interaction_sequence_date'] = $row1['interaction_sequence_date'];
								$idata['send_automatically'] = $row1['send_automatically'];
								$idata['include_signature'] = $row1['include_signature'];
								$idata['template_name'] = $row1['template_name'];
								$idata['premium_plan_id'] = $row1['id'];
								
								//pr($cdata);
								$ins = $this->interaction_model->insert_record($idata,$db_name);
							}
							
							foreach($interaction_list as $row2)
							{
								if(!empty($row2['interaction_id']))
								{
									$match = array('premium_plan_id'=>$row2['interaction_id'],'interaction_plan_id'=>$last_id);
									$result = $this->obj2->select_records('',$match,'','=','','','','','','',$db_name);
									
									//echo $row2['interaction_id'].'    '.count($result);
									$match = array('premium_plan_id'=>$row2['id'],'interaction_plan_id'=>$last_id);
									$res = $this->obj2->select_records('',$match,'','=','','','','','','',$db_name);
									
									//echo count($res);
									if(count($result) > 0 && count($res) > 0)
									{
										$update_plan['id'] = $res[0]['id'];
										$update_plan['interaction_id'] = $result[0]['id'];
										$this->obj2->update_record($update_plan,$db_name);
										//echo $this->db->last_query();exit;
									}
								}
							}
						}
					}*/
				}
			}
		}
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
	
		$selected_view_session = $this->session->userdata('selected_view_session');
		if($selected_view_session['selected_view'] == '2')
			$searchsort_session = $this->session->userdata('premium_default_iplans_sortsearchpage_data');
		else
			$searchsort_session = $this->session->userdata('default_iplans_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		if($selected_view_session['selected_view'] == '2')
			$sel_val = 'premium_plan';
		else
			$sel_val = 'my_plan';
		
		if(!empty($submitbtn_action) && isset($submitbtn_action))
		{
			redirect('superadmin/default_interaction/'.$interaction_plan_id);	
		}
		else
		{
        	redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));			
		}				
    }
	
	/*
    @Description: Function for Delete contacts from interaction plan Ajax
    @Author: Nishit Modi
    @Input: - Delete details of contacts
    @Output: - 
    @Date: 18-09-2014
    */
	
	function delete_contact_from_plan()
	{
		$contact_id = $this->input->post('contact_id');
		$interaction_plan_id = $this->input->post('interaction_plan');
		
		if(!empty($interaction_plan_id) && !empty($contact_id))
		{
			$this->obj->delete_adminuser_trans_record_indi($interaction_plan_id,$contact_id);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			//$this->obj->delete_contact_communication_plan_trans_record_indi($interaction_plan_id,$contact_id);
			
			//////////////////////////////////////////////////////////////////////////////////
			
			/* Delete SMS and Email Campaign data */
			$match = array('interaction_plan_id'=>$interaction_plan_id);
			$interaction = $this->obj2->select_records('',$match,'','=');
			if(count($interaction) > 0)
			{
				$i = 0;
				$j = 0;
				$email_campaign = array();
				$sms_campaign = array();
				foreach($interaction as $row)
				{
					if($row['interaction_type'] == 3)
					{
						$match = array('interaction_id'=>$row['id']);
						$result = $this->sms_campaign_master_model->select_records('',$match,'','=');
						if(count($result) > 0)
						{
							$sms_campaign[$i] = $result[0]['id'];
							$i++;
						}
					}
					elseif($row['interaction_type'] == 6)
					{
						$match1 = array('interaction_id'=>$row['id']);
						$result1 = $this->email_campaign_master_model->select_records('',$match1,'','=');
						if(count($result1) > 0)
						{
							$email_campaign[$j] = $result1[0]['id'];
							$j++;
						}
					}
				}
			}
			
			$deletecon = $contact_id;
			
			/* END */
			if(!empty($deletecon))
			{
				$data_conv['contact_id'] = $deletecon;
				$data_conv['plan_id'] = $interaction_plan_id;
				$data_conv['plan_name'] = $this->input->post('txt_plan_name');
				$data_conv['created_date'] = date('Y-m-d H:i:s');
				$data_conv['log_type'] = '10';
				$data_conv['created_by'] = $this->superadmin_session['id'];
				$data_conv['status'] = '1';
				$this->obj->insert_contact_converaction_trans_record($data_conv);
			}
			
			if(count($sms_campaign) > 0)
			{
				for($i = 0;$i<=count($sms_campaign);$i++)
				{
					$smsdata['sms_campaign_id'] = $sms_campaign[$i];
					$smsdata['contact_id'] = $deletecon;
					$smsdata['is_send'] = '0';
					$this->sms_campaign_master_model->delete_interaction_campaign($smsdata);
				}
			}

			if(count($email_campaign) > 0)
			{
				for($j = 0;$j<=count($email_campaign);$j++)
				{
					$emaildata['email_campaign_id'] = $email_campaign[$j];
					$emaildata['contact_id'] = $deletecon;
					$emaildata['is_send'] = '0';
					$this->email_campaign_master_model->delete_interaction_campaign($emaildata);
				}
			}
		}
		
	}
	
	
	/*
    @Description: Function for Update contacts Profile Tab2 Ajax
    @Author: Nishit Modi
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 11-07-2014
    */
	
	function update_data_ajax()
	{
		$cdata['id'] = $this->input->post('id');
		
		$image = $this->input->post('hiddenFile');
		if(!empty($image))
		{	
			$bgImgPath = $this->config->item('contact_big_img_path');
			$smallImgPath = $this->config->item('contact_small_img_path');
			$this->imageupload_model->copyImage($bgImgPath,$smallImgPath,$image);
			$cdata['contact_pic']	= $image;
			
			$bgTempPath = $this->config->item('upload_image_file_path').'temp/big/';
			$smallTempPath = $this->config->item('upload_image_file_path').'temp/small/';
			if(file_exists($bgTempPath.$image))
			{ 
				@unlink($bgTempPath.$image);
				@unlink($smallTempPath.$image);
			}
		}
		$this->obj->update_record($cdata);
		unset($cdata);
		
		$cddata['id'] 		= $this->input->post('doc_id');
		$cddata['contact_id'] = $this->input->post('id');
		$cddata['doc_type'] = $this->input->post('slt_doc_type');
		$cddata['doc_name'] = $this->input->post('txt_doc_name');
		$cddata['doc_desc'] = $this->input->post('txtarea_doc_desc');
		$cddata['doc_file'] = $this->input->post('hiddenFiledoc');
		$cddata['modified_date'] = date('Y-m-d H:i:s');
		$cddata['status'] 	= '1';
		
		if(trim($cddata['doc_type']) != '' || trim($cddata['doc_name']) != '' || trim($cddata['doc_desc']) != '' || trim($cddata['doc_file']) != '')
		{
			if($this->input->post('doc_id') == '')
			{
				$cddata['created_date'] = date('Y-m-d H:i:s');
				$this->obj->insert_doc_trans_record($cddata);
			}
			else
				$this->obj->update_doc_trans_record($cddata);
				
			unset($cddata);
		}
		
		$data['document_trans_data'] = $this->obj->select_document_trans_record($this->input->post('id'));
		$this->load->view($this->user_type.'/'.$this->viewName."/contact_document_ajax",$data);
		
	}
	
    /*
    @Description: Function for Delete contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 04-07-2014
    */
    function delete_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	/*
    @Description: Functions for deleting various transactions data
    @Author: Nishit Modi
    @Input: - Transaction id
    @Output: - 
    @Date: 11-07-2014
    */
	
	function delete_email_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_email_trans_record($id);
    }
	
	function delete_phone_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_phone_trans_record($id);
    }
	
	function delete_address_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_address_trans_record($id);
    }
	
	function delete_website_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_website_trans_record($id);
    }
	
	function delete_social_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_social_trans_record($id);
    }
	
	function delete_tag_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_tag_trans_record($id);
    }
	
	function delete_communication_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_communication_trans_record($id);
    }
	
	function delete_document_trans_record()
    {
        $id = $this->uri->segment(4);
		
		$result = $this->obj->select_document_trans_record_ajax($id);
		$this->obj->delete_document_trans_record($id);
		if(!empty($result->doc_file))
		{
			$image = $result->doc_file;
			$bgImgPath = $this->config->item('contact_documents_img_path');
			$bgImgPathUpload = $this->config->item('upload_image_file_path').'contact_docs/';
			if(file_exists($bgImgPathUpload.$image))
			{ 
				@unlink($bgImgPath.$image);
			}
		}
    }
	
	 /*
    @Description: Function for Unpublish contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to Unpublish
    @Output: - New contacts list after record is Unpublish.
    @Date: 04-07-2014
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
		redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to publish
    @Output: - New contacts list after record is publish.
    @Date: 04-07-2014
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
		redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
	
	/*
    @Description: Function for to upload image
    @Author: Nishit Modi
    @Input: - 
    @Output: - 
    @Date: 10-07-2014
    */
	function upload_image()
	{
		$image=$this->input->post('image');
		$hiddenImage=$this->input->post('image');
		$uploadFile = 'uploadfile';
		$bgImgPath = $this->config->item('temp_big_img_path');
		$smallImgPath = $this->config->item('temp_small_img_path');
		$thumb = "thumb";
		$hiddenImage = '';
		echo $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
	}
	
	/*
    @Description: Function for delete image 
    @Author: Nishit Modi
    @Input: - Delete id 
    @Output: - image deleted
    @Date: 10-07-2014
    */
	public function delete_image()
	{
		$id=$this->input->post('id');
		$name=$this->input->post('name');
		$fields = array("id,$name");
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		//pr($result);exit;
		$bgImgPath = $this->config->item('contact_big_img_path');
		$smallImgPath = $this->config->item('contact_small_img_path');
		$image=$result[0][$name];
		
		$bgImgPathUpload = $this->config->item('upload_image_file_path').'contact/big/';
		$smallImgPathUpload = $this->config->item('upload_image_file_path').'contact/small/';
		if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
		{ 
		
			@unlink($bgImgPath.$image);
			@unlink($smallImgPath.$image);
		}
		$cdata['id'] = $id;
		$cdata[$name] = '';
		$this->obj->update_record($cdata);
		echo 'done';
	}
	
	/*
    @Description: Function for to upload document
    @Author: Nishit Modi
    @Input: - 
    @Output: - 
    @Date: 11-07-2014
    */
	function upload_document()
	{
			$uploadFile = 'uploadfile';
			$bgImgPath = $this->config->item('contact_documents_img_path');
			$doc_name= $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,'','','');
			$my_img_array['document_name'] = $doc_name;
			echo json_encode($my_img_array);
	}
	
	/*
    @Description: Function for to upload CSV File
    @Author: Kaushik Valiya
    @Input: - 
    @Output: - 
    @Date: 11-07-2014
    */
	function upload_csv()
	{
			$uploadFile = 'uploadfile';
			$bgImgPath = $this->config->item('contact_documents_csv_path');
			$doc_name= $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,'','','');
			$my_img_array['document_name'] = $doc_name;
			echo json_encode($my_img_array);
	}
	
	
	/*
    @Description: Function to get document data
    @Author: Nishit Modi
    @Input: Document Id
    @Output: - 
    @Date: 11-07-2014
    */
	function get_doc_trans_data()
	{
		$id = $this->input->post('id');
		$result = $this->obj->select_document_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}
	
	/*
    @Description: Function use to Delete contact in by superadmin
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	
	public function ajax_Active_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		$pagingid='';
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '1';
			$this->obj->update_record($cdata);
			
			//$email_id = $id;
			//$pagingid = $this->obj->getemailpagingid($email_id);
                        $searchsort_session = $this->session->userdata('default_iplan_view_archive_sortsearchpage_data');
                        if(!empty($searchsort_session['uri_segment']))
                            $pagingid = $searchsort_session['uri_segment'];
                        else
                            $pagingid = 0;
			
			unset($id);
		}
		elseif(!empty($array_data))
		{
			//$email_id = $array_data[0];
			//$pagingid = $this->obj->getemailpagingid($email_id);
			$searchsort_session = $this->session->userdata('default_iplan_view_archive_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
				for($i=0;$i<count($array_data);$i++)
				{
					$data['id']=$array_data[$i];
					$data['status']='1';
					$this->obj->update_record($data);
					$delete_all_flag = 1;
					$cnt++;
				}
		}
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
	public function ajax_Inactive_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		$pagingid='';
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '0';
			$this->obj->update_record($cdata);
			//$email_id = $id;
			//$pagingid = $this->obj->getemailpagingid($email_id);
			$searchsort_session = $this->session->userdata('default_iplans_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
			unset($id);
		}
		
		if(!empty($array_data))
		{
			//$email_id = $array_data[0];
			//$pagingid = $this->obj->getemailpagingid($email_id);
			$searchsort_session = $this->session->userdata('default_iplans_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
			for($i=0;$i<count($array_data);$i++)
			{
				$data['id']=$array_data[$i];
				$data['status']='0';
				$this->obj->update_record($data);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
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
	
	public function ajax_delete_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->interaction_model->delete_record_interaction($id);
			
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$this->interaction_model->delete_record_interaction($array_data[$i]);
							$delete_all_flag = 1;
							$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('default_iplans_sortsearchpage_data');
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
	
	public function add_contacts_to_interaction_plan()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_user_master($contacts);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	
	public function view_contacts_of_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$table = "interaction_plan_adminuser_trans as ct";
		//$fields = array('ct.interaction_plan_id','cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$where = array('ct.interaction_plan_id'=>$id);
		$fields = array('ct.interaction_plan_id','cm.id as cid','cm.admin_name as contact_name','cm.email_id as email_address');
			$join_tables = array(
								'login_master as cm'=>'cm.id = ct.user_id',
							);
		$group_by='cm.id';
		
		$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.admin_name','asc',$group_by);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/view_contact_popup",$data);
	}
	
	/*
    @Description: Function use to pause plan by superadmin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function pause_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '2';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->superadmin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'paused');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->superadmin_session['id'];
			
			$this->obj->update_record($cdata);
		}
		
	}
	
	/*
    @Description: Function use to stop plan by superadmin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function stop_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '3';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->superadmin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'stop');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->superadmin_session['id'];
			
			$this->obj->update_record($cdata);
		}
		
		/*$table ="email_campaign_master as ecm";
		$fields = array('ecm.*');
		$join_tables = array('interaction_plan_interaction_master_premium as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master_premium as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$wherestring = "ecm.email_type = 'Intereaction_plan' AND ecr.is_send = '0'";
		//$groupby = 'ipm.id';
		$interaction_plan_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],'','ipi.id','desc',$groupby,$wherestring);
		
		
		foreach($interaction_plan_data as $row)
		{
			$match = array('interaction_id'=>$interaction_id);
			$sms_campaign_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
			$sms_send = array();
			//pr($sms_campaign_exist);
			if(count($sms_campaign_exist) > 0)
			{
				
				$i = 0;
				$match = array('sms_campaign_id'=>$sms_campaign_exist[0]['id'],'is_send'=>'1');
				$res = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=');
				//pr($res);exit;
				if(count($res) > 0)
				{
					foreach($res as $row)
					{
						$sms_send[$i] = $row['contact_id'];
						$i++;
					}
				}
				$this->sms_campaign_recepient_trans_model->delete_record_campaign($sms_campaign_exist[0]['id']);
				if(count($sms_send) == 0)
				{
					$this->sms_campaign_master_model->delete_record($sms_campaign_exist[0]['id']);
					//$this->sms_campaign_master_model->delete_sms_campaign_recepient_trans($sms_campaign_exist[0]['id']);
				}
			}
			//pr($sms_send);exit;
			$match = array('interaction_id'=>$interaction_id);
			$email_campaign_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
			$email_send = array();
			if(count($email_campaign_exist) > 0)
			{
				$i = 0;
				$res = $this->email_campaign_master_model->email_campaign_trans_fetch($email_campaign_exist[0]['id']);
				if(count($res) > 0)
				{
					foreach($res as $row)
					{
						$email_send[$i] = $row['contact_id'];
						$i++;
					}
				}
				$this->email_campaign_master_model->email_campaign_trans_delete($email_campaign_exist[0]['id']);
				if(count($email_send) == 0)
				{
					$this->email_campaign_master_model->delete_record($email_campaign_exist[0]['id']);
					$this->email_campaign_master_model->delete_email_campaign_attachments($email_campaign_exist[0]['id']);
				}
			}	
		}*/
		
		
		$this->obj->delete_contact_communication_plan_trans_record_not_done($id);
		
	}
	
	/*
    @Description: Function use to play plan by superadmin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function play_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$match = array('interaction_plan_id'=>$id);
        $interaction_plan_time_trans = $this->obj->select_records_plan_time_trans('',$match,'','=','','1','','id','desc');
		
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '4';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->superadmin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'active');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->superadmin_session['id'];
			
			$this->obj->update_record($cdata);
		}
		
		///////////////////////////////////////////////////////////////////////////	
		
		if(!empty($interaction_plan_time_trans[0]['id']))
		{
			if($interaction_plan_time_trans[0]['interaction_time_type'] == 2)
			{
				$now = time(); // or your date as well
				$your_date = strtotime($interaction_plan_time_trans[0]['interaction_time']);
				$datediff = $now - $your_date;
				$noofdays = floor($datediff/(60*60*24));
				
				if($noofdays > 0)
				{
					
					$plan_id = $id;
					
					///////////////////////////////////////////////////////////////////////////
		
					$table = "interaction_plan_contacts_trans as ct";
					$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid','ct.start_date');
					$where = array('ct.interaction_plan_id'=>$plan_id);
					$join_tables = array(
										'contact_master as cm'=>'cm.id = ct.contact_id'
									);
					$group_by='cm.id';
					
					$old_contacts_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
					
					///////////////////////////////////////////////////////////////////////////
							
					////////////////////////////////////////////////////////
					
					$table = "interaction_plan_interaction_master_premium as ipim";
					$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
					$join_tables = array(
										'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
										'admin_users as au' => 'au.id = ipim.created_by',
										'interaction_plan_interaction_master_premium as ipm' => 'ipm.id = ipim.interaction_id'
										);
					
					$group_by='ipim.id';
					
					$where1 = array('ipim.interaction_plan_id'=>$plan_id);
					
					$interaction_list =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
					
					/////////////////////////////////////////////////////////
						
						
						//pr($interaction_list);exit;
						if(!empty($old_contacts_data))
						{
							foreach($old_contacts_data as $row)
							{
								//echo $row['start_date'];
								$uictdata['id'] = $row['ctid'];
								$uictdata['start_date'] = date("Y-m-d",strtotime($row['start_date']."+ ".$noofdays." Days"));
								$uictdata['modified_date'] = date('Y-m-d H:i:s');
								$uictdata['modified_by'] = $this->superadmin_session['id'];
								//pr($uictdata);
								
								$this->obj->update_record_interaction_contact($uictdata);
								
								//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
								
								if(count($interaction_list) > 0)
								{
									foreach($interaction_list as $row1)
									{
									
										$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
			
										//////////////////// Integrate User Work time config ///////////////////
										
										if(!empty($assigned_user_id))
										{
										
											//echo $assigned_user_id;
											
											//Get Working Days
											
											//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
											$new_user_id = $assigned_user_id;
											
											//echo $new_user_id;
											
											$match = array("user_id"=>$new_user_id);
											$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
											
											//pr($worktimedata);exit;
											
											$match = array("user_id"=>$new_user_id,"rule_type"=>1);
											$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
											
											//pr($worktimespecialdata);exit;
											
											$match = array("user_id"=>$new_user_id);
											$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
											
											//pr($worktimeleavedata);exit;
											
											$user_work_off_days1 = array();
											
											if(!empty($worktimedata[0]['id']))
											{
												if(empty($worktimedata[0]['if_mon']))
													$user_work_off_days1[] = 'Mon';
												if(empty($worktimedata[0]['if_tue']))
													$user_work_off_days1[] = 'Tue';
												if(empty($worktimedata[0]['if_wed']))
													$user_work_off_days1[] = 'Wed';
												if(empty($worktimedata[0]['if_thu']))
													$user_work_off_days1[] = 'Thu';
												if(empty($worktimedata[0]['if_fri']))
													$user_work_off_days1[] = 'Fri';
												if(empty($worktimedata[0]['if_sat']))
													$user_work_off_days1[] = 'Sat';
												if(empty($worktimedata[0]['if_sun']))
													$user_work_off_days1[] = 'Sun';
											}
											
											$special_days1 = array();
											
											if(!empty($worktimespecialdata))
											{
												foreach($worktimespecialdata as $row2)
												{
													$day_string = '';
													if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
													{
														switch($row2['nth_day'])
														{
															case 1:
															$day_string .= 'First ';
															break;
															case 2:
															$day_string .= 'Second ';
															break;
															case 3:
															$day_string .= 'Third ';
															break;
															case 4:
															$day_string .= 'Fourth ';
															break;
															case 5:
															$day_string .= 'Last ';
															break;
														}
														switch($row2['nth_date'])
														{
															case 1:
															$day_string .= 'Day';
															break;
															case 2:
															$day_string .= 'Weekday';
															break;
															case 3:
															$day_string .= 'Weekend';
															break;
															case 4:
															$day_string .= 'Monday';
															break;
															case 5:
															$day_string .= 'Tuesday';
															break;
															case 6:
															$day_string .= 'Wednesday';
															break;
															case 7:
															$day_string .= 'Thursday';
															break;
															case 8:
															$day_string .= 'Friday';
															break;
															case 9:
															$day_string .= 'Saturday';
															break;
															case 10:
															$day_string .= 'Sunday';
															break;
															default:
															break;
														}
														
														$special_days1[] = $day_string;
														
													}
												}
											}
											
											$leave_days1 = array();
											
											foreach($worktimeleavedata as $row2)
											{
												if(!empty($row2['from_date']))
												{
													$leave_days1[] = $row2['from_date'];
													if(!empty($row2['to_date']))
													{
														
														//$from_date = date('Y-m-d',strtotime($row['from_date']));
														
														$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
														
														$to_date = date('Y-m-d',strtotime($row2['to_date']));
														
														//echo $from_date."-".$to_date;
														
														while($from_date <= $to_date)
														{
															$leave_days1[] = $from_date;
															$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
														}
													}
												}
											}
											
											//pr($user_work_off_days1);
											
											//pr($special_days1);
											
											//pr($leave_days1);
											
										}
												
										////////////////////////////////////////////////////////////////////////
									
										$interaction_id = $row1['id'];
										$contact_interaction_plan_interaction_id = $this->obj2->get_contact_interaction_task_date($interaction_id,$row['id']);
										
										//pr($contact_interaction_plan_interaction_id);
										//exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
										}
										
										//echo $row1['start_type'];
										if($row1['start_type'] == '1' || $row1['start_type'] == '2')
										{
											$interaction_id = $row1['id'];
											
											$interaction_res = $this->obj2->get_contact_interaction_task_date($interaction_id,$row['id']);
											
											//echo $interaction_res->task_date;
											
											if(!empty($interaction_res->task_date))
											{
												$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$noofdays." Days"));
											
												$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$noofdays." Days"));
						
												////////////////////////////////////////////////////////
												
												$repeatoff = 1;
												
												while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
												{
													// Check for Work off days
													// echo $newtaskdate;
													$day_of_date = date('D', strtotime($newtaskdate1));
													$new_special_days = array();
													
													if(!empty($special_days1))
													{
														foreach($special_days1 as $mydays)
														{
															if (strpos($mydays,'Weekday') !== false) {
																$nthday = explode(" ",$mydays);
																if(!empty($nthday[0]))
																{
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
																}
															}
															elseif (strpos($mydays,'Weekend') !== false) {
																$nthday = explode(" ",$mydays);
																if(!empty($nthday[0]))
																{
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
																	$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
																}
															}
															else
																$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
														}
													}
													
													//pr($new_special_days);
													
													if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
													{
														//echo 'work off'."<br>";
														$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
													}
													elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
													{
														//echo 'special days'."<br>";
														$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
													}
													elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
													{
														//echo 'leave'."<br>";
														$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
													}
													else
													{
														//echo 'else';
														$repeatoff = 0;
														$newtaskdate = $newtaskdate1;
													}
													
													//echo $repeatoff;
													
												}
												
												//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
												
												///////////////////////////////////////////////////////
												
												//$iccdata1['task_date'] = $newtaskdate;
											
												$iccdata1['task_date'] = $newtaskdate;
											}
										}
										else
										{
											$iccdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
										}
										
										$sendemaildate = $iccdata1['task_date'];
										//pr($iccdata1);
										
										//$this->obj2->update_contact_communication_record($iccdata1);
										
										if($row1['interaction_type'] == 6)
										{
											//echo $row1['id']." ".$row['id']."<br>";;
											$table = "email_campaign_master as ecm";
											$fields = array('ecr.*');
											$join_tables = array(
												'email_campaign_recepient_trans as ecr' => 'ecr.email_campaign_id = ecm.id'
												);
						
											//$group_by='ipim.id';
						
											$where1 = array('ecm.interaction_id'=>$row1['id'],'ecr.contact_id'=>$row['id'],'ecr.is_send'=>'0');
						
											$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
											
											if(count($email_campaign_data) > 0)
											{
												if(!empty($email_campaign_data[0]['id']))
												{
													$idata['id'] = $email_campaign_data[0]['id'];
													$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
													$this->email_campaign_master_model->update_email_campaign_trans($idata);
													//echo $this->db->last_query()."<br>";
													/*echo $this->db->last_query();
													pr($email_campaign_data); exit;*/
													unset($idata);
												}
											}
										}
										elseif($row1['interaction_type'] == 3)
										{
											$table = "sms_campaign_master as scm";
											$fields = array('scr.*');
											$join_tables = array(
												'sms_campaign_recepient_trans as scr' => 'scr.sms_campaign_id = scm.id'
												);
						
											//$group_by='ipim.id';
						
											$where1 = array('scm.interaction_id'=>$row1['id'],'scr.contact_id'=>$row['id'],'scr.is_send'=>'0');
						
											$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
											if(count($sms_campaign_data) > 0)
											{
												if(!empty($sms_campaign_data[0]['id']))
												{
													$idata['id'] = $sms_campaign_data[0]['id'];
													$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
													$this->sms_campaign_recepient_trans_model->update_record($idata);
													unset($idata);
												}
											}
										}

										unset($iccdata1);
										unset($user_work_off_days1);
										unset($special_days1);
										unset($leave_days1);
						
									}
								
								}
								
								/////////////////////////////////////////////////////////////////////////////////////////////////
								
							}
						}
					
				}				
			}
			else if($interaction_plan_time_trans[0]['interaction_time_type'] == 3)
			{
					$plan_id = $id;
					$interaction_plan_id = $id;
					$new_plan_start_date = $this->input->post('startdate');
					if(empty($new_plan_start_date))
						$new_plan_start_date = date('Y-m-d');
					else
						$new_plan_start_date = date('Y-m-d',strtotime($new_plan_start_date));
					
					///////////////////////////////////////////////////////////////////////////
		
					$table = "interaction_plan_contacts_trans as ct";
					$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid','ct.start_date');
					$where = array('ct.interaction_plan_id'=>$plan_id);
					$join_tables = array(
										'contact_master as cm'=>'cm.id = ct.contact_id'
									);
					$group_by='cm.id';
					
					$old_contacts_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
					
					///////////////////////////////////////////////////////////////////////////
							
					////////////////////////////////////////////////////////
					
					$table = "interaction_plan_interaction_master_premium as ipim";
					$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
					$join_tables = array(
										'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
										'admin_users as au' => 'au.id = ipim.created_by',
										'interaction_plan_interaction_master_premium as ipm' => 'ipm.id = ipim.interaction_id'
										);
					
					$group_by='ipim.id';
					
					$where1 = array('ipim.interaction_plan_id'=>$plan_id);
					
					$interaction_list =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
					
					/////////////////////////////////////////////////////////
										
				
					if(!empty($old_contacts_data))
					{
						foreach($old_contacts_data as $row)
						{
								$uictdata['id'] = $row['ctid'];
								$uictdata['start_date'] = $new_plan_start_date;
								$uictdata['modified_date'] = date('Y-m-d H:i:s');
								$uictdata['modified_by'] = $this->superadmin_session['id'];
								//pr($uictdata);
								
								$this->obj->update_record_interaction_contact($uictdata);
								
						///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////
						
						if(count($interaction_list) > 0)
						{
							foreach($interaction_list as $row1)
							{
							
								$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
			
								//////////////////// Integrate User Work time config ///////////////////
								
								if(!empty($assigned_user_id))
								{
								
									//echo $assigned_user_id;
									
									//Get Working Days
									
									//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
									$new_user_id = $assigned_user_id;
									
									//echo $new_user_id;
									
									$match = array("user_id"=>$new_user_id);
									$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
									
									//pr($worktimedata);exit;
									
									$match = array("user_id"=>$new_user_id,"rule_type"=>1);
									$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
									
									//pr($worktimespecialdata);exit;
									
									$match = array("user_id"=>$new_user_id);
									$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
									
									//pr($worktimeleavedata);exit;
									
									$user_work_off_days1 = array();
									
									if(!empty($worktimedata[0]['id']))
									{
										if(empty($worktimedata[0]['if_mon']))
											$user_work_off_days1[] = 'Mon';
										if(empty($worktimedata[0]['if_tue']))
											$user_work_off_days1[] = 'Tue';
										if(empty($worktimedata[0]['if_wed']))
											$user_work_off_days1[] = 'Wed';
										if(empty($worktimedata[0]['if_thu']))
											$user_work_off_days1[] = 'Thu';
										if(empty($worktimedata[0]['if_fri']))
											$user_work_off_days1[] = 'Fri';
										if(empty($worktimedata[0]['if_sat']))
											$user_work_off_days1[] = 'Sat';
										if(empty($worktimedata[0]['if_sun']))
											$user_work_off_days1[] = 'Sun';
									}
									
									$special_days1 = array();
									
									if(!empty($worktimespecialdata))
									{
										foreach($worktimespecialdata as $row2)
										{
											$day_string = '';
											if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
											{
												switch($row2['nth_day'])
												{
													case 1:
													$day_string .= 'First ';
													break;
													case 2:
													$day_string .= 'Second ';
													break;
													case 3:
													$day_string .= 'Third ';
													break;
													case 4:
													$day_string .= 'Fourth ';
													break;
													case 5:
													$day_string .= 'Last ';
													break;
												}
												switch($row2['nth_date'])
												{
													case 1:
													$day_string .= 'Day';
													break;
													case 2:
													$day_string .= 'Weekday';
													break;
													case 3:
													$day_string .= 'Weekend';
													break;
													case 4:
													$day_string .= 'Monday';
													break;
													case 5:
													$day_string .= 'Tuesday';
													break;
													case 6:
													$day_string .= 'Wednesday';
													break;
													case 7:
													$day_string .= 'Thursday';
													break;
													case 8:
													$day_string .= 'Friday';
													break;
													case 9:
													$day_string .= 'Saturday';
													break;
													case 10:
													$day_string .= 'Sunday';
													break;
													default:
													break;
												}
												
												$special_days1[] = $day_string;
												
											}
										}
									}
									
									$leave_days1 = array();
									
									foreach($worktimeleavedata as $row2)
									{
										if(!empty($row2['from_date']))
										{
											$leave_days1[] = $row2['from_date'];
											if(!empty($row2['to_date']))
											{
												
												//$from_date = date('Y-m-d',strtotime($row['from_date']));
												
												$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
												
												$to_date = date('Y-m-d',strtotime($row2['to_date']));
												
												//echo $from_date."-".$to_date;
												
												while($from_date <= $to_date)
												{
													$leave_days1[] = $from_date;
													$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
												}
											}
										}
									}
									
									//pr($user_work_off_days1);
									
									//pr($special_days1);
									
									//pr($leave_days1);
									
								}
										
								////////////////////////////////////////////////////////////////////////
						
								$iccdata['interaction_plan_id'] = $plan_id;
								$iccdata['contact_id'] = $row['id'];
								$iccdata['interaction_plan_interaction_id'] = $row1['id'];
								$iccdata['interaction_type'] = $row1['interaction_type'];
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
									$plan_contact_data = $this->obj->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
									//pr($plan_contact_data);exit;
									
									///////////////////////////////////////////////////////////////
									
									if(!empty($plan_contact_data[0]['start_date']))
									{
										$newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
										
										$newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
						
										////////////////////////////////////////////////////////
										
										$repeatoff = 1;
										
										while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
										{
											// Check for Work off days
											// echo $newtaskdate;
											$day_of_date = date('D', strtotime($newtaskdate1));
											$new_special_days = array();
											
											if(!empty($special_days1))
											{
												foreach($special_days1 as $mydays)
												{
													if (strpos($mydays,'Weekday') !== false) {
														$nthday = explode(" ",$mydays);
														if(!empty($nthday[0]))
														{
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
														}
													}
													elseif (strpos($mydays,'Weekend') !== false) {
														$nthday = explode(" ",$mydays);
														if(!empty($nthday[0]))
														{
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
															$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
														}
													}
													else
														$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
												}
											}
											
											//pr($new_special_days);
											
											if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
											{
												//echo 'work off'."<br>";
												$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
											}
											elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
											{
												//echo 'special days'."<br>";
												$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
											}
											elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
											{
												//echo 'leave'."<br>";
												$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
											}
											else
											{
												//echo 'else';
												$repeatoff = 0;
												$newtaskdate = $newtaskdate1;
											}
											
											//echo $repeatoff;
											
										}
										
										//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
										
										///////////////////////////////////////////////////////
										
										//$iccdata1['task_date'] = $newtaskdate;
										
										$iccdata['task_date'] = $newtaskdate;
									}
								}
								elseif($row1['start_type'] == '2')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									$interaction_id = $row1['interaction_id'];
									
									$interaction_res = $this->obj2->get_contact_interaction_task_date($interaction_id,$row['id']);
									
									//pr($interaction_res);
									
									//echo $interaction_res->task_date;
									
									if(!empty($interaction_res->task_date))
									{
										$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
									
										$iccdata['task_date'] = $newtaskdate;
									}
									
								}
								else
								{
									$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
								}
								
								$iccdata['created_date'] = date('Y-m-d H:i:s');
								$iccdata['created_by'] = $this->superadmin_session['id'];
								
								$this->obj2->insert_contact_communication_record($iccdata);
								unset($iccdata);
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
							
							}
						
						}
						
						///////////////////////////////////////////////////////////////////////////
						
						unset($icdata);
						
					
					}
				}
					
			}
		}
		
		///////////////////////////////////////////////////////////////////////////	
		
	}
	
	public function released_premium_plan()
	{
		$cdata['id'] = $this->router->uri->segments[4];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->interaction_plans_premium_model->update_record($cdata);
		redirect('superadmin/'.$this->viewName);
	}
	
}