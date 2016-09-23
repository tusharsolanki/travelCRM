<?php 
/*
    @Description: Interaction Plans controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class interaction_plans_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('interaction_plans_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('interaction_model');
		//$this->load->model('imageupload_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('interaction_plans_premium_model');
		$this->load->model('sms_texts_model');
		$this->load->model('label_library_model');
		$this->load->model('envelope_library_model');
		$this->load->model('phonecall_script_model');
		$this->load->model('letter_library_model');
		$this->load->model('marketing_library_masters_model');
		$this->obj = $this->interaction_plans_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->interaction_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		$this->parent_db_name = $this->config->item('parent_db_name');
    }
	
	/*
		@Description: Function for Module All details view.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 22-12-2014
    */
	
	public function interaction_plans_home()
	{
		//check user right
		check_rights('communications');
		$data['main_content'] = 'admin/'.$this->viewName."/home";
		$this->load->view('admin/include/template',$data);	
	}
	

    /*
    @Description: Function for Get All Interaction List
    @Author: Nishit Modi
    @Input: - Search value or null
    @Output: - all interaction list
    @Date: 04-07-2014
    */
	
    public function index()
    {	
		//check user right
		check_rights('communications');
		
        $selected_view_session = $this->session->userdata('selected_view_session');
        if(!empty($selected_view_session['selected_view']))
            $selected_view = $selected_view_session['selected_view'];
        else
            $selected_view = '1';
        //$selected_view = $this->input->post('selected_view');
        //$selected_view = (!empty($selected_view))?$selected_view:'1';
		$main=1;
		if($main == 1)
		{
			$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
			//echo $this->input->post('searchtext');exit;
			$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
			$sortfield = $this->input->post('sortfield');
			$sortby = $this->input->post('sortby');
			$searchopt = $this->input->post('searchopt');
			$perpage = trim($this->input->post('perpage'));
			$allflag = $this->input->post('allflag');
	
			if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				$this->session->unset_userdata('iplans_sortsearchpage_data');
			}
			$data['sortfield']		= 'ipm.id';
			$data['sortby']			= 'desc';
			$searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
	
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
					$sortfield = 'ipm.id';
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
			if((!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) || $selected_view == '2') {
				$config['uri_segment'] = 0;
				$uri_segment = 0;
			} else {
				$config['uri_segment'] = 3;
				/*if(!empty($searchsort_session['uri_segment'])) {
					$uri_segment = $searchsort_session['uri_segment'];
				} else {*/
					$uri_segment = $this->uri->segment(3);
				//}
			}
			
			$table = "interaction_plan_master as ipm ";
			$fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions','iptt.interaction_time_type','count(DISTINCT cm.id) as contact_counter','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name');
			$join_tables = array(
				'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
				'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
				'interaction_plan_time_trans as iptt' 			=> 'iptt.interaction_plan_id = ipm.id',
				'interaction_plan_contacts_trans ipct' 			=> 'ipct.interaction_plan_id = ipm.id',
				'contact_master as cm'				 			=> 'cm.id = ipct.contact_id',
				'login_master as lm'                            => 'lm.id = ipm.created_by',
				'user_master as um'                             => 'um.id = lm.user_id'
			);
			$group_by='ipm.id';
			$status_value='1';
			//pr($searchtext);exit;
			if(!empty($searchtext))
			{
				
				$match=array('ipm.plan_name'=>$searchtext);
				$where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'0');
	
				$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
				//echo $this->db->last_query();exit;
				$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
			}
			else
			{
				$match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'0');
				$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$match);
				$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');
			}
			
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['msg'] = $this->message_session['msg'];
	
			//pr($data);exit;
	
			$iplans_sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
				'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
				
			$this->session->set_userdata('iplans_sortsearchpage_data', $iplans_sortsearchpage_data);
			$data['uri_segment'] = $uri_segment;
        
		}
		$premium=1;
		if($premium == 1)
		{
        ////////// For Premium Plan Sanjay M. 11-10-2014 ///////////
		
        $searchopt1='';$searchtext1='';$searchoption1='';$perpage1='';
        $searchtext1 = mysql_real_escape_string($this->input->post('searchtext1'));
        $sortfield1 = $this->input->post('sortfield1');
        $sortby1 = $this->input->post('sortby1');
        $searchopt1 = $this->input->post('searchopt1');
        $perpage1 = trim($this->input->post('perpage1'));
        $allflag1 = $this->input->post('allflag1');

        if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
            $this->session->unset_userdata('premium_iplans_sortsearchpage_data');
        }
        $data['sortfield1']		= 'ipm.id';
        $data['sortby1']		= 'desc';
        $searchsort_session1 = $this->session->userdata('premium_iplans_sortsearchpage_data');

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
        if(!empty($searchtext))
        {
            $data['searchtext1'] = stripslashes($searchtext1);
        } else {
            if(empty($allflag1))
            {
                if(!empty($searchsort_session1['searchtext'])) {
                    /*$data['searchtext1'] = $searchsort_session1['searchtext'];
                    $searchtext1 =  $data['searchtext1'];*/
					 $searchtext1 =  mysql_real_escape_string($searchsort_session1['searchtext']);
	     			$data['searchtext1'] = $searchsort_session1['searchtext'];

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
        $config1['base_url'] = site_url($this->user_type.'/'."interaction_plans/");
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

        $table = " interaction_plan_master as ipm ";
        $fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions','iptt.interaction_time_type','count(DISTINCT cm.id) as contact_counter','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name');
        $join_tables = array(
            'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
            'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
            'interaction_plan_time_trans as iptt' 		=> 'iptt.interaction_plan_id = ipm.id',
            'interaction_plan_contacts_trans ipct' 		=> 'ipct.interaction_plan_id = ipm.id',
            'contact_master as cm'                              => 'cm.id = ipct.contact_id',
			'login_master as lm'                            => 'lm.id = ipm.created_by',
			'user_master as um'                             => 'um.id = lm.user_id'
        );
        $group_by='ipm.id';
        $status_value='1';
        
        if(!empty($searchtext1))
        {
            $match=array('ipm.plan_name'=>$searchtext1);
            $where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'1');

            $data['premium_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'],$uri_segment,$data['sortfield1'],$data['sortby1'],$group_by,$where1);
            $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
        }
        else
        {
            $match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'1');
            $data['premium_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config1['per_page'],$uri_segment,$sortfield1,$sortby1,$group_by,$match);
            $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');
        }
		
        $this->pagination->initialize($config1);
        $data['pagination1'] = $this->pagination->create_links();

        $premium_iplans_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
			'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
			'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
			'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');
        
        $this->session->set_userdata('premium_iplans_sortsearchpage_data', $premium_iplans_sortsearchpage_data);
        $data['uri_segment1'] = $uri_segment;
		}
		$default=1;
		if($default == 1)
		{
			$searchopt2='';$searchtext2='';$searchoption2='';$perpage2='';
			$searchtext2 = mysql_real_escape_string($this->input->post('searchtext2'));
			$sortfield2 = $this->input->post('sortfield2');
			$sortby2 = $this->input->post('sortby2');
			$searchopt2 = $this->input->post('searchopt2');
			$perpage2 = trim($this->input->post('perpage2'));
			$allflag2 = $this->input->post('allflag2');
	
			if(!empty($allflag2) && ($allflag2 == 'all' || $allflag2 == 'changesorting' || $allflag2 == 'changesearch')) {
				$this->session->unset_userdata('default_iplans_sortsearchpage_data');
			}
			$data['sortfield2']		= 'ipm.id';
			$data['sortby2']		= 'desc';
			$searchsort_session2 = $this->session->userdata('default_iplans_sortsearchpage_data');
	
			if(!empty($sortfield2) && !empty($sortby2))
			{
				$data['sortfield2'] = $sortfield2;
				$data['sortby2'] = $sortby2;
			}
			else
			{
				if(!empty($searchsort_session2['sortfield'])) {
					if(!empty($searchsort_session2['sortby'])) {
						$data['sortfield2'] = $searchsort_session2['sortfield'];
						$data['sortby2'] = $searchsort_session2['sortby'];
						$sortfield2 = $searchsort_session2['sortfield'];
						$sortby2 = $searchsort_session2['sortby'];
					}
				} else {
					$sortfield2 = 'id';
					$sortby2 = 'desc';
				}
			}
			if(!empty($searchtext2))
			{
				$data['searchtext2'] = stripslashes($searchtext2);
			} else {
				if(empty($allflag2))
				{
					if(!empty($searchsort_session2['searchtext'])) {
						/*$data['searchtext2'] = $searchsort_session2['searchtext'];
						$searchtext2 =  $data['searchtext2'];*/
						$searchtext2 =  mysql_real_escape_string($searchsort_session2['searchtext']);
	     				$data['searchtext2'] = $searchsort_session2['searchtext'];

					}
				}
			}
			if(!empty($searchopt2))
			{
				$data['searchopt2'] = $searchopt2;
			}
			if(!empty($perpage2))
			{
				$data['perpage2'] = $perpage2;
				$config2['per_page'] = $perpage2;
			}
			else
			{
				if(!empty($searchsort_session2['perpage'])) {
					$data['perpage2'] = trim($searchsort_session2['perpage']);
					$config2['per_page'] = trim($searchsort_session2['perpage']);
				} else {
					$config2['per_page'] = '10';
				}
			}
			$config2['base_url'] = site_url($this->user_type.'/'."interaction_plans/");
			$config2['is_ajax_paging'] = TRUE; // default FALSE
			$config2['paging_function'] = 'ajax_paging'; // Your jQuery paging
			//$config['uri_segment'] = 3;
			//$uri_segment = $this->uri->segment(3);
			if((!empty($allflag2) && ($allflag2 == 'all' || $allflag2 == 'changesorting' || $allflag2 == 'changesearch')) || $selected_view == '2') {
				$config2['uri_segment'] = 0;
				$uri_segment = 0;
			} else {
				$config2['uri_segment'] = 3;
				$uri_segment = $this->uri->segment(3);
			}
			
			$table = " interaction_plan_master as ipm ";
			$fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions','iptt.interaction_time_type','count(DISTINCT cm.id) as contact_counter','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name');
			$join_tables = array(
				'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
				'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
				'interaction_plan_time_trans as iptt' 		=> 'iptt.interaction_plan_id = ipm.id',
				'interaction_plan_contacts_trans ipct' 		=> 'ipct.interaction_plan_id = ipm.id',
				'contact_master as cm'                              => 'cm.id = ipct.contact_id',
				'login_master as lm'                            => 'lm.id = ipm.created_by',
				'user_master as um'                             => 'um.id = lm.user_id'
			);
			$group_by='ipm.id';
			$status_value='1';
			
			if(!empty($searchtext2))
			{
				$match=array('ipm.plan_name'=>$searchtext2);
				$where2=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'2');
	
				$data['default_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config2['per_page'],$uri_segment,$data['sortfield2'],$data['sortby2'],$group_by,$where2);
				$config2['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where2,'','1');
			}
			else
			{
				$match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'2');
				$data['default_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config2['per_page'],$uri_segment,$sortfield2,$sortby2,$group_by,$match);
				$config2['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');
			}
			
			$this->pagination->initialize($config2);
			$data['pagination2'] = $this->pagination->create_links();
	
			$default_iplans_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield2'])?$data['sortfield2']:'',
			'sortby' =>!empty($data['sortby2'])?$data['sortby2']:'',
			'searchtext' =>!empty($data['searchtext2'])?$data['searchtext2']:'',
			'perpage' => !empty($data['perpage2'])?trim($data['perpage2']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config2['total_rows'])?$config2['total_rows']:'0');
			
			$this->session->set_userdata('default_iplans_sortsearchpage_data', $default_iplans_sortsearchpage_data);
			$data['uri_segment2'] = $uri_segment;	
		}
        $data['tabid'] = $selected_view;
		$status_value='1';
		
		/*$table = "interaction_plan_adminuser_trans as ipat ";
        $fields = array('ipmp.id');
        $join_tables = array('interaction_plan_master_premium as ipmp' => 'ipmp.id = ipat.interaction_plan_id'
							);
      	//$group_by = 'ipmp.id';
		$wherestring = 'ipmp.modified_date > ipat.modified_date AND user_id = '.$this->admin_session['id'];
		$premium_plan_update = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','','',$wherestring);*/
		//echo $this->db->last_query(); exit;
		//pr($premium_plan_update); exit;
		//echo $this->admin_session['id'];
		$db_name = $this->session->userdata('db_session');
		$parent_db_name = $this->config->item('parent_db_name');
		//pr($this->admin_session);exit;
		$fields1 = array('id');
		$match1 = array('db_name'=>$db_name['db_name'],'email_id'=>$this->admin_session['useremail']);
		$admin_data = $this->admin_model->get_user($fields1,$match1,'','=','','','','','','',$parent_db_name);
		if(!empty($admin_data))
		{
			$admin_id = $admin_data[0]['id'];
			$wherestring = 'ipmp.modified_date > ipat.modified_date AND ipat.user_id = '.$admin_id;
			$premium_plan_update = $this->obj->get_updated_premium_plans($parent_db_name,$wherestring);
		}
		//pr($premium_plan_update);exit;
		//echo $this->db->last_query();pr($premium_plan_update);exit;
		$data['premium_plan_update'] = array();
		if(!empty($premium_plan_update) && count($premium_plan_update) > 0)
		{
			$i=0;
			foreach($premium_plan_update as $row)
			{
				$data['premium_plan_update'][$i] = $row['id'];
				$i++;
			}
		}
		//pr($data['premium_plan_update']);exit;
        if($this->input->post('result_type') == 'ajax')
        {	
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
        }
        else if($this->input->post('result_type') == 'ajax1')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/premium_ajax_list',$data);
        }
		else if($this->input->post('result_type') == 'ajax2')
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
		@Description: Function for Get All Interaction List
		@Author: Nishit Modi
		@Input: - Search value or null
		@Output: - all interaction list
		@Date: 04-07-2014
    */
	
    public function view_archive()
    {
		$modules_lists = $this->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(!in_array('communications',$modules_lists) && !in_array('premium_plans',$modules_lists))
			{show_404();}
		}
        $selected_view_session = $this->session->userdata('selected_view_session');
        if(!empty($selected_view_session['selected_view']))
            $selected_view = $selected_view_session['selected_view'];
        else
            $selected_view = '1';
        
            $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
            $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $searchopt = $this->input->post('searchopt');
            $perpage = trim($this->input->post('perpage'));
            $allflag = $this->input->post('allflag');

            if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('iplan_view_archive_sortsearchpage_data');
            }
            $data['sortfield']		= 'ipm.id';
            $data['sortby']			= 'desc';
            $searchsort_session = $this->session->userdata('iplan_view_archive_sortsearchpage_data');

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
                    $sortfield = 'ipm.id';
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

            $table = " interaction_plan_master as ipm";
            $fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(DISTINCT cm.id) as contact_counter');
            $join_tables = array('interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status','interaction_plan_contacts_trans ipct' => 'ipct.interaction_plan_id = ipm.id','contact_master as cm'	=> 'cm.id = ipct.contact_id');
            $group_by='ipm.id';
            $status_value='0';
            if(!empty($searchtext))
            {
                    //pr($searchtext);exit;
                    $match=array('ipm.description'=>$searchtext);
                    $where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'0');

                    $data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
                    $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
            }
            else
            {
                    $match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'0');
                    $data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$match);
                    $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');


            }


            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['msg'] = $this->message_session['msg'];

            $iplan_view_archive_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
				
            $this->session->set_userdata('iplan_view_archive_sortsearchpage_data', $iplan_view_archive_sortsearchpage_data);
            $data['uri_segment'] = $uri_segment;

            ////////// For Premium Plan Sanjay M. 13-10-2014 ///////////

            $searchopt1='';$searchtext1='';$searchoption1='';$perpage1='';
            $searchtext1 = $this->input->post('searchtext1');
            $sortfield1 = $this->input->post('sortfield1');
            $sortby1 = $this->input->post('sortby1');
            $searchopt1 = $this->input->post('searchopt1');
            $perpage1 = trim($this->input->post('perpage1'));
            $allflag1 = $this->input->post('allflag1');

            if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
                $this->session->unset_userdata('premium_iplan_view_archive_sortsearchpage_data');
            }
            $data['sortfield1']		= 'ipm.id';
            $data['sortby1']		= 'desc';
            $searchsort_session = $this->session->userdata('premium_iplan_view_archive_sortsearchpage_data');

            if(!empty($sortfield1) && !empty($sortby1))
            {
                $data['sortfield1'] = $sortfield1;
                $data['sortby1'] = $sortby1;
            }
            else
            {
                if(!empty($searchsort_session['sortfield'])) {
                    if(!empty($searchsort_session['sortby'])) {
                        $data['sortfield1'] = $searchsort_session['sortfield'];
                        $data['sortby1'] = $searchsort_session['sortby'];
                    }
                } else {
                    $sortfield1 = 'id';
                    $sortby1 = 'desc';
                }
            }
            if(!empty($searchtext1))
            {
                //$searchtext = $this->input->post('searchtext');
                $data['searchtext1'] = $searchtext1;
            } else {
                if(empty($allflag1))
                {
                    if(!empty($searchsort_session['searchtext'])) {
                        $data['searchtext1'] = $searchsort_session['searchtext'];
                        $searchtext1 =  $data['searchtext1'];
                    }
                }
            }
            if(!empty($searchopt1))
            {
                $data['searchopt1'] = $searchopt1;
            }
            if(!empty($perpage1) && $perpage1 != 'null')
            {
                $data['perpage1'] = $perpage1;
                $config1['per_page'] = $perpage1;	
            }
            else
            {
                if(!empty($searchsort_session['perpage'])) {
                    $data['perpage1'] = trim($searchsort_session['perpage']);
                    $config1['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config1['per_page'] = '10';
                }
            }
            $config1['base_url'] = site_url($this->user_type.'/'."interaction_plans/view_archive");
            $config1['is_ajax_paging'] = TRUE; // default FALSE
            $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
            //$config1['uri_segment'] = 4;
            //$uri_segment = $this->uri->segment(4);
            if((!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) || $selected_view == '1') {
                $config1['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config1['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            //pr($uri_segment);exit;

            $table = " interaction_plan_master as ipm";
            $fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(DISTINCT cm.id) as contact_counter');
            $join_tables = array('interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status','interaction_plan_contacts_trans ipct' => 'ipct.interaction_plan_id = ipm.id','contact_master as cm'	=> 'cm.id = ipct.contact_id');
            $group_by='ipm.id';
            $status_value='0';
            if(!empty($searchtext))
            {
                $match=array('ipm.description'=>$searchtext1);
                $where1=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'1');

                $data['premium_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'],$uri_segment,$data['sortfield1'],$data['sortby1'],$group_by,$where1);
                $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','1');
            }
            else
            {
                $match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'1');
                $data['premium_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config1['per_page'],$uri_segment,$sortfield1,$sortby1,$group_by,$match);
                $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');
            }


            $this->pagination->initialize($config1);
            $data['pagination1'] = $this->pagination->create_links();

            $iplan_view_archive_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
			'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
			'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
			'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');
				
            $this->session->set_userdata('premium_iplan_view_archive_sortsearchpage_data', $iplan_view_archive_sortsearchpage_data);
            $data['uri_segment1'] = $uri_segment;
			
			$default=1;
			if($default == 1)
			{
				$searchopt2='';$searchtext2='';$searchoption2='';$perpage2='';
				$searchtext2 = mysql_real_escape_string($this->input->post('searchtext2'));
				$sortfield2 = $this->input->post('sortfield2');
				$sortby2 = $this->input->post('sortby2');
				$searchopt2 = $this->input->post('searchopt2');
				$perpage2 = trim($this->input->post('perpage2'));
				$allflag2 = $this->input->post('allflag2');
				
				if(!empty($allflag2) && ($allflag2 == 'all' || $allflag2 == 'changesorting' || $allflag2 == 'changesearch')) {
					$this->session->unset_userdata('default_view_archive_sortsearchpage_data');
				}
				$data['sortfield2']		= 'ipm.id';
				$data['sortby2']		= 'desc';
				$searchsort_session2 = $this->session->userdata('default_view_archive_sortsearchpage_data');
				
				if(!empty($sortfield2) && !empty($sortby2))
				{
					$data['sortfield2'] = $sortfield2;
					$data['sortby2'] = $sortby2;
				}
				else
				{
					if(!empty($searchsort_session2['sortfield'])) {
						if(!empty($searchsort_session2['sortby'])) {
							$data['sortfield2'] = $searchsort_session2['sortfield'];
							$data['sortby2'] = $searchsort_session2['sortby'];
							$sortfield2 = $searchsort_session2['sortfield'];
							$sortby2 = $searchsort_session2['sortby'];
						}
					} else {
						$sortfield2 = 'id';
						$sortby2 = 'desc';
					}
				}
				if(!empty($searchtext2))
				{
					$data['searchtext2'] = stripslashes($searchtext2);
				} else {
					if(empty($allflag2))
					{
						if(!empty($searchsort_session2['searchtext'])) {
							/*$data['searchtext2'] = $searchsort_session2['searchtext'];
							$searchtext2 =  $data['searchtext2'];*/
							$searchtext2 =  mysql_real_escape_string($searchsort_session2['searchtext']);
							$data['searchtext2'] = $searchsort_session2['searchtext'];
				
						}
					}
				}
				if(!empty($searchopt2))
				{
					$data['searchopt2'] = $searchopt2;
				}
				if(!empty($perpage2))
				{
					$data['perpage2'] = $perpage2;
					$config2['per_page'] = $perpage2;
				}
				else
				{
					if(!empty($searchsort_session2['perpage'])) {
						$data['perpage2'] = trim($searchsort_session2['perpage']);
						$config2['per_page'] = trim($searchsort_session2['perpage']);
					} else {
						$config2['per_page'] = '10';
					}
				}
				$config2['base_url'] = site_url($this->user_type.'/'."interaction_plans/view_archive/");
				$config2['is_ajax_paging'] = TRUE; // default FALSE
				$config2['paging_function'] = 'ajax_paging'; // Your jQuery paging
				//$config['uri_segment'] = 3;
				//$uri_segment = $this->uri->segment(3);
				if((!empty($allflag2) && ($allflag2 == 'all' || $allflag2 == 'changesorting' || $allflag2 == 'changesearch')) || $selected_view == '2') {
					$config2['uri_segment'] = 0;
					$uri_segment = 0;
				} else {
					$config2['uri_segment'] = 4;
					$uri_segment = $this->uri->segment(4);
				}
				
				$table = " interaction_plan_master as ipm ";
				$fields = array('ipm.id','ipm.plan_status','ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions','iptt.interaction_time_type','count(DISTINCT cm.id) as contact_counter','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name');
				$join_tables = array(
					'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
					'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
					'interaction_plan_time_trans as iptt' 		=> 'iptt.interaction_plan_id = ipm.id',
					'interaction_plan_contacts_trans ipct' 		=> 'ipct.interaction_plan_id = ipm.id',
					'contact_master as cm'                              => 'cm.id = ipct.contact_id',
					'login_master as lm'                            => 'lm.id = ipm.created_by',
					'user_master as um'                             => 'um.id = lm.user_id'
				);
				$group_by='ipm.id';
				$status_value='0';
				
				if(!empty($searchtext2))
				{
					$match=array('ipm.plan_name'=>$searchtext2);
					$where2=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'2');
				
					$data['default_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config2['per_page'],$uri_segment,$data['sortfield2'],$data['sortby2'],$group_by,$where2);
					$config2['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where2,'','1');
				}
				else
				{
					$match=array('ipm.status'=>$status_value,'ipm.by_superadmin'=>'2');
					$data['default_datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config2['per_page'],$uri_segment,$sortfield2,$sortby2,$group_by,$match);
					$config2['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match,'','1');
				}
				
				$this->pagination->initialize($config2);
				$data['pagination2'] = $this->pagination->create_links();
				
				$default_view_archive_sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield2'])?$data['sortfield2']:'',
				'sortby' =>!empty($data['sortby2'])?$data['sortby2']:'',
				'searchtext' =>!empty($data['searchtext2'])?$data['searchtext2']:'',
				'perpage' => !empty($data['perpage2'])?trim($data['perpage2']):'10',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
				'total_rows' => !empty($config2['total_rows'])?$config2['total_rows']:'0');
				
				$this->session->set_userdata('default_view_archive_sortsearchpage_data', $default_view_archive_sortsearchpage_data);
				$data['uri_segment2'] = $uri_segment;	
			 }
			
            $data['tabid'] = $selected_view;

            if($this->input->post('result_type') == 'ajax')
            {
                $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
            }
            else if($this->input->post('result_type') == 'ajax1')
            {
                $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_premium_archive',$data);
				
            } else if($this->input->post('result_type') == 'ajax2')
			{
				  $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_default_archive',$data);
			}
            else
            {
                $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
                $this->load->view('admin/include/template',$data);
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
		//check user right
		check_rights('communications_add');
		
		$match = array('name'=>'active');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		$data['sortfield'] = 'cm.first_name';
		$data['sortby'] = 'asc';
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
	
	
	public function search_contact_ajax()
    {
		 $searchsort_session = $this->session->userdata('iplans_popup_contact');
		 $perpage=''; 
		 $perpage = $this->input->post('perpage');
		 $sortfield = $this->input->post('sortfield');
		 $sortby = $this->input->post('sortby');
		
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
                $config['per_page'] = '50';
				//$data['perpage'] = '50';
            }
        }
		
		if(!empty($sortfield) && !empty($sortby))
        {
                //$sortfield = $this->input->post('sortfield');
                $data['sortfield'] = $sortfield;
                //$sortby = $this->input->post('sortby');
                $data['sortby'] = $sortby;
        }
        else
        {
			$data['sortfield'] = 'cm.first_name';
			$data['sortby'] = 'asc';
        }
	
		// echo $data['per_page'];exit;
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
	
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		
		$search_tag = $this->input->post('search_tag');
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
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id',
						);
		$group_by='cm.id';
		if(!empty($search_tag))
		{
			$tag = explode(",",$search_tag);
			if(!empty($tag))
			{
				for($i=0;$i<count($tag);$i++)
				{
					$tag_array = array();
					$tag_array = array('(select * from contact_tag_trans where tag = "'.$tag[$i].'") as ctat'.$i.' jointype direct'=>'ctat'.$i.'.contact_id = cm.id');
					$join_tables = array_merge($join_tables,$tag_array);
				}
			}
		}
		
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
		//echo $this->db->last_query();exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$iplans_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'50',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('iplans_popup_contact', $iplans_sortsearchpage_data);
        
		
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		if($this->input->post('result_type') == 'ajax_page_contact_popup')
		{
			$this->load->view("admin/".$this->viewName."/contact_popup_ajax", $data);
		}
		else
		{
        	$this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
		}
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
		}
		
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['status'] = '1';
       //pr($cdata);exit;

		$interaction_plan_id = $this->obj->insert_record($cdata);
		
		///////////////////////////// Interaction Plan Time Trans Data /////////////////////////////////////////
		
		$tcdata['interaction_plan_id'] = $interaction_plan_id;
		$tcdata['interaction_time_type'] = '1';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->admin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$interaction_contacts = $this->input->post('finalcontactlist');
		
		$interaction_contacts = explode(",",$interaction_contacts);
		
		//pr($interaction_contacts);
		
		if(!empty($interaction_contacts))
		{
			
			/*$table = "interaction_plan_interaction_master as ipim";
			$fields = array('ipim.*','ipptm.name');
			$join_tables = array(
								'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type'
								);
			$group_by='ipim.id';
			
			$match = array('ipim.interaction_plan_id'=>$plan_id);
			$interactionlist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by);
			*/
			foreach($interaction_contacts as $row)
			{
				if($row != '')
				{
					$icdata['interaction_plan_id'] = $interaction_plan_id;
					$icdata['contact_id'] = $row;
					if($cdata['plan_start_type'] == '1')
					{
						$icdata['start_date'] = date('Y-m-d');
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					else
					{
						if(strtotime(date('Y-m-d')) < strtotime($this->input->post('txt_start_date')))
							$icdata['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date')));
						else
							$icdata['start_date'] = date('Y-m-d');
						
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					$icdata['created_date'] = date('Y-m-d H:i:s');
					$icdata['created_by'] = $this->admin_session['id'];
					$icdata['status'] = '1';
					
					$this->obj->insert_contact_trans_record($icdata);
				
				//////////// Converation History
						
				if(!empty($row))
				{
					$data_conv['contact_id'] = $row;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $this->input->post('txt_plan_name');
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '2';
					$data_conv['created_by'] = $this->admin_session['id'];
					$data_conv['status'] = '1';
					$this->obj->insert_contact_converaction_trans_record($data_conv);
				}
				
				//////////////end Converation history
				
				
					/////////// If interactions added then add contact entry ////////////////////////
					/*
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
							$icdata1['created_by'] = $this->admin_session['id'];
							
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

        $iplans_sortsearchpage_data = array(
            'sortfield'  => 'ipm.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('iplans_sortsearchpage_data', $iplans_sortsearchpage_data);
        
		if(!empty($submitbtn_action) && isset($submitbtn_action))
		{
			redirect('admin/interaction/add_record/'.$interaction_plan_id);	
		}
		else
		{
        	redirect('admin/'.$this->viewName);				
		}
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
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
        $modules_lists = $this->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(!in_array('communications_edit',$modules_lists) && !in_array('premium_plans_edit',$modules_lists))
			{show_404();}
		}
		$id = $this->uri->segment(4);
		$match = array("ipm.id"=>$this->uri->segment(4));
		$table = " interaction_plan_master as ipm";
		$fields = array('ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions');
		
		$join_tables = array(
							'interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' => 'ipim.interaction_plan_id = ipm.id'
						);
		
	    $data['editRecord'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','');	 
		
		if(isset($data['editRecord'][0]['status']) && ($data['editRecord'][0]['status'] == '0'))
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);	
		}
		
	  	//pr($data['editRecord']);
		$data['interaction_plan_status'] = $this->obj1->select_records1('','','','=','','','','name','asc','interaction_plan__status_master');	
		
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet' => 'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		
		
	    $data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('ct.interaction_plan_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id'
						);
		$group_by='cm.id';
		
		$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		//pr($data['contacts_data']);
		
		///////////////////////////////////////////////////////////////////////////	
		
		$match = array('interaction_plan_id'=>$id);
        $data['interaction_plan_time_trans'] = $this->obj->select_records_plan_time_trans('',$match,'','=','','','','id','asc');
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		//pr($data['interaction_plan_time_trans']);exit;
		
		///////////////////////////////////////////////////////////////////////////	
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
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
     	$submitbtn_action=$this->input->post('submitbtn_action');
	    $cdata['id'] = $this->input->post('id');
		//pr($_POST);exit;
		
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		$id = $this->input->post('id');
		$match = array("id"=>$id);
		$interaction_plan_data_old = $this->obj->select_records('',$match,'','=','','','','','','');	 
		
		//pr($interaction_plan_data_old);
		//exit;
		
		///////////////////////////////Update Interaction Plan Master/////////////////////////////////////////
		
		
		$cdata['plan_name'] = $this->input->post('txt_plan_name');
		$cdata['description'] = $this->input->post('txtarea_description');
		//$cdata['plan_status'] = $this->input->post('interaction_plan_status_id');
		$cdata['target_audience'] = $this->input->post('txtarea_target_audience');

		$cdata['plan_start_type'] = $this->input->post('plan_start_date'); 
		if($cdata['plan_start_type'] == '1')
		{
			$cdata['start_date'] = '0000-00-00';
		}
		else{
			$cdata['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date')));
		}	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$cdata['modified_by'] = $this->admin_session['id'];
		
		//pr($cdata);exit;

		$this->obj->update_record($cdata);
		
		$interaction_plan_id = $this->input->post('id');
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$interaction_contacts = $this->input->post('finalcontactlist');
		
		$interaction_contacts = explode(",",$interaction_contacts);
		
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid');
		$where = array('ct.interaction_plan_id'=>$interaction_plan_id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id'
						);
		$group_by='cm.id';
		
		$old_contacts_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		///////////////////////////////////////////////////////////////////////////
		
		$plan_id = $interaction_plan_id;
					
		////////////////////////////////////////////////////////
		
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
		$join_tables = array(
							'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
							'admin_users as au' => 'au.id = ipim.created_by',
							'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
							);
		
		$group_by='ipim.id';
		
		$where1 = array('ipim.interaction_plan_id'=>$plan_id);
		
		$interaction_list =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
		
		/////////////////////////////////////////////////////////
		
		///////////////////////// Update Interaction Plan Contacts Transaction /////////////////////////
		
		//pr($interaction_plan_data_old);
		//pr($cdata);
		//exit;
		
		if(!empty($interaction_plan_data_old[0]['plan_start_type']))
		{
			if($interaction_plan_data_old[0]['plan_start_type']==2){
			
				if($cdata['plan_start_type']==2)
				{
					if(strtotime(date('Y-m-d')) <= strtotime($interaction_plan_data_old[0]['start_date']))
					{
						if(strtotime(date('Y-m-d')) <= strtotime($cdata['start_date']))
							$set_start_date = date('Y-m-d',strtotime($cdata['start_date']));
						else
							$set_start_date = date('Y-m-d');
							
						if(!empty($old_contacts_data))
						{
							foreach($old_contacts_data as $row)
							{
								$uictdata['id'] = $row['ctid'];
								$uictdata['start_date'] = $set_start_date;
								$uictdata['plan_start_type'] = 2;
								$uictdata['plan_start_date'] = $cdata['start_date'];
								$uictdata['modified_date'] = date('Y-m-d H:i:s');
								$uictdata['modified_by'] = $this->admin_session['id'];
								//pr($uictdata);
								
								$this->obj->update_record_interaction_contact($uictdata);
								
								//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
								
								if(count($interaction_list) > 0)
								{
									foreach($interaction_list as $row1)
									{
										//$iccdata['interaction_plan_id'] = $plan_id;
										//$iccdata['contact_id'] = $row['id'];
										//$iccdata['interaction_plan_interaction_id'] = $row1['id'];
										//$iccdata['interaction_type'] = $row1['interaction_type'];
										//pr($row1);
										//exit;
										$interaction_id = $row1['id'];
										$contact_interaction_plan_interaction_id = $this->obj2->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
										
										//pr($contact_interaction_plan_interaction_id);
										//exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
											
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
												
												$iccdata1['task_date'] = $newtaskdate;
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
												
												$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
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
												
												//echo $newtaskdate;
												
												//$icdata['task_date'] = $newtaskdate;
											
												$iccdata1['task_date'] = $newtaskdate;
											}
											
										}
										else
										{
											$iccdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
										}
										
										//$iccdata['created_date'] = date('Y-m-d H:i:s');
										//$iccdata['created_by'] = $this->admin_session['id'];
										
										//pr($iccdata1);exit;
										
										$this->obj2->update_contact_communication_record($iccdata1);
										unset($iccdata1);
										
										}
										
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
				else
				{
					if(!empty($old_contacts_data))
					{
						foreach($old_contacts_data as $row)
						{
							$uictdata['id'] = $row['ctid'];
							$uictdata['start_date'] = date('Y-m-d');
							$uictdata['plan_start_type'] = 1;
							$uictdata['plan_start_date'] = '';
							$uictdata['modified_date'] = date('Y-m-d H:i:s');
							$uictdata['modified_by'] = $this->admin_session['id'];
							//pr($uictdata);
							
							$this->obj->update_record_interaction_contact($uictdata);
							
							//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
								
								if(count($interaction_list) > 0)
								{
									foreach($interaction_list as $row1)
									{
										//$iccdata['interaction_plan_id'] = $plan_id;
										//$iccdata['contact_id'] = $row['id'];
										//$iccdata['interaction_plan_interaction_id'] = $row1['id'];
										//$iccdata['interaction_type'] = $row1['interaction_type'];
										//pr($row1);
										//exit;
										$interaction_id = $row1['id'];
										$contact_interaction_plan_interaction_id = $this->obj2->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
										
										//pr($contact_interaction_plan_interaction_id);
										//exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
											
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
												
												$iccdata1['task_date'] = $newtaskdate;
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
												
												$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
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
												
												//echo $newtaskdate;
												
												//$icdata['task_date'] = $newtaskdate;
											
												$iccdata1['task_date'] = $newtaskdate;
											}
											
										}
										else
										{
											$iccdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
										}
										
										//$iccdata['created_date'] = date('Y-m-d H:i:s');
										//$iccdata['created_by'] = $this->admin_session['id'];
										
										//pr($iccdata1);exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$this->obj2->update_contact_communication_record($iccdata1);
										}
										unset($iccdata1);
										
										}
										
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
		}
		
		//exit;
		
		////////////////////////////////// Delete Interaction Plan Contacts Transaction Data ///////////////////////////////////////////
		
		$interaction_old_contacts = array();
		
		if(!empty($old_contacts_data))
		{
			foreach($old_contacts_data as $row)
			{
				$interaction_old_contacts[] = $row['id'];
			}
		}
		
		//pr($interaction_old_contacts);
		
		//pr($interaction_contacts);
		
		$deletecontactdata = array_diff($interaction_old_contacts,$interaction_contacts);
		
		if(!empty($deletecontactdata))
		{
			$this->obj->delete_contact_trans_record_array($interaction_plan_id,$deletecontactdata);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			$this->obj->delete_contact_communication_plan_trans_record_array($interaction_plan_id,$deletecontactdata);
			
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
					elseif($row['interaction_type'] == 6 || $row['interaction_type'] == 8)
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
			
			/* END */
			foreach($deletecontactdata as $deletecon)
			{
				if(!empty($deletecon))
				{
					$data_conv['contact_id'] = $deletecon;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $this->input->post('txt_plan_name');
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '10';
					$data_conv['created_by'] = $this->admin_session['id'];
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
		
		$addcontactdata = array_diff($interaction_contacts,$interaction_old_contacts);
		
		//pr($deletecontactdata);
		
		//pr($addcontactdata);
		
		//exit;
		
		//////////////////////////////// Add New Interaction Plan Contacts Transaction ///////////////////////////////////////////	
		
		if(!empty($addcontactdata))
		{
			foreach($addcontactdata as $row)
			{
				if($row != '')
				{
				
					$data_conv['contact_id'] = $row;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $this->input->post('txt_plan_name');
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '2';
					$data_conv['created_by'] = $this->admin_session['id'];
					$data_conv['status'] = '1';
					$this->obj->insert_contact_converaction_trans_record($data_conv);
				
					$icdata['interaction_plan_id'] = $interaction_plan_id;
					$icdata['contact_id'] = $row;
					
					if($cdata['plan_start_type'] == '1')
					{
						$icdata['start_date'] = date('Y-m-d');
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					else
					{
						if(strtotime(date('Y-m-d')) < strtotime($this->input->post('txt_start_date')))
							$icdata['start_date'] = date('Y-m-d',strtotime($this->input->post('txt_start_date')));
						else
							$icdata['start_date'] = date('Y-m-d');
						
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					
					$icdata['created_date'] = date('Y-m-d H:i:s');
					$icdata['created_by'] = $this->admin_session['id'];
					$icdata['status'] = '1';
					
					$this->obj->insert_contact_trans_record($icdata);
					
					///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////
					
					//pr($interaction_list);exit;
					
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
							$iccdata['contact_id'] = $row;
							$iccdata['interaction_plan_interaction_id'] = $row1['id'];
							$iccdata['interaction_type'] = $row1['interaction_type'];
							
							if($row1['start_type'] == '1')
							{
								$count = $row1['number_count'];
								$counttype = $row1['number_type'];
								
								///////////////////////////////////////////////////////////////
								
								$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row);
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
									
									$iccdata['task_date'] = $newtaskdate;
								}
							}
							elseif($row1['start_type'] == '2')
							{
								$count = $row1['number_count'];
								$counttype = $row1['number_type'];
								
								$interaction_id = $row1['interaction_id'];
								
								$interaction_res = $this->obj2->get_contact_interaction_task_date($interaction_id,$row);
								
								//pr($interaction_res);
								
								//echo $interaction_res->task_date;
								
								if(!empty($interaction_res->task_date))
								{
									$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
									
									$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
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
									
									//echo $newtaskdate;
									
									//$icdata['task_date'] = $newtaskdate;
								
									$iccdata['task_date'] = $newtaskdate;
								}
								
							}
							else
							{
								$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
							}
							
							$sendemaildate = $iccdata['task_date'];
							
							$iccdata['created_date'] = date('Y-m-d H:i:s');
							$iccdata['created_by'] = $this->admin_session['id'];
							
							$this->obj2->insert_contact_communication_record($iccdata);
							
							unset($iccdata);
							unset($user_work_off_days1);
							unset($special_days1);
							unset($leave_days1);
							
							/* Email campaign/SMS campaign Insert */
							/*$match = array('id'=>$row);
							$userdata = $this->contacts_model->select_records('',$match,'','=');*/
							
							$table = "contact_master as cm";
							$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
							$where = array('cm.id'=>$row);
							$join_tables = array(
												'contact_address_trans as cat'=>'cat.contact_id = cm.id',
											);
							$group_by='cm.id';
							$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,'',$where);
							//pr($userdata);
							$agent_name = '';
							if(!empty($row1['assign_to']))
							{
								$table ="login_master as lm";   
								$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
								$join_tables = array('user_master as um'=>'lm.user_id = um.id');
								$wherestring = 'lm.id = '.$row1['assign_to'];
								$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','',$wherestring);
								if(!empty($agent_datalist))
								{
									if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
										$agent_name = $agent_datalist[0]['admin_name'];
									else
										$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
								}
							}
						
							if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
							{	
								$row1['id'];
								$match = array('interaction_id'=>$row1['id']);
								$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=');
								//pr($campaigndata);exit;
								if(count($campaigndata) > 0)
								{
									$cdata1['email_campaign_id'] = $campaigndata[0]['id'];
									$cdata1['contact_id'] = $row;
									$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$userdata[0]['first_name'],
												'Contact Spouse/Partner First Name'=>$userdata[0]['spousefirst_name'],
												'Contact Last Name'=>$userdata[0]['last_name'],
												'Contact Spouse/Partner Last Name'=>$userdata[0]['spouselast_name'],
												'Contact Company Name'=>$userdata[0]['company_name'],
												);
							
									$content = $campaigndata[0]['email_message'];
									$title = $campaigndata[0]['template_subject'];
									
									//pr($emaildata);
									
									$cdata1['template_subject'] = $title;
									$cdata1['email_message'] = $content;
									$pattern = "{(%s)}";
									
									$map = array();
									
									if($emaildata != '' && count($emaildata) > 0)
									{
										foreach($emaildata as $var => $value)
										{
											$map[sprintf($pattern, $var)] = $value;
										}
										$finaltitle = strtr($title, $map);				
										$output = strtr($content, $map);
										
										$cdata1['template_subject'] = $finaltitle;
										$finlaOutput = $output;
										$cdata1['email_message'] = $finlaOutput;
									}
									//echo $cdata1['email_message'];exit;
									//$emaildata['interaction_id'] = $row1['id'];
									$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
									$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
									//echo $this->db->last_query();
								}
							}
							elseif($row1['interaction_type'] == 3 && count($userdata) > 0)
							{
								$match = array('interaction_id'=>$row1['id']);
								$smscampaigndata = $this->sms_campaign_master_model->select_records('',$match,'','=');
								if(count($smscampaigndata) > 0)
								{
									$cdata1['sms_campaign_id'] = $smscampaigndata[0]['id'];
									$cdata1['contact_id'] = $row;
									$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$userdata[0]['first_name'],
												'Contact Spouse/Partner First Name'=>$userdata[0]['spousefirst_name'],
												'Contact Last Name'=>$userdata[0]['last_name'],
												'Contact Spouse/Partner Last Name'=>$userdata[0]['spouselast_name'],
												'Contact Company Name'=>$userdata[0]['company_name'],
												'Contact Address'=>$userdata[0]['address_line1'].' '.$userdata[0]['address_line2'],
												'Contact City'=>$userdata[0]['city'],
												'Contact State'=>$userdata[0]['state'],
												'Contact Zip'=>$userdata[0]['zip_code'],
												);
							
									$content = $smscampaigndata[0]['sms_message'];
									$cdata1['sms_message'] 	= $content;
									$pattern = "{(%s)}";
									
									$map = array();
									
									if($emaildata != '' && count($emaildata) > 0)
									{
										foreach($emaildata as $var => $value)
										{
											$map[sprintf($pattern, $var)] = $value;
										}
										$output = strtr($content, $map);
										
										$finlaOutput = $output;
										$cdata1['sms_message'] = $finlaOutput;
									}
									//$smsdata['interaction_id'] = $row1['id'];
									$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
									$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
								}
								
								
							}
							unset($userdata);
							unset($cdata1);
							/* END */
							
						}
						//echo "hello";exit;
					}
					
					///////////////////////////////////////////////////////////////////////////
					unset($icdata);
					//exit;
					
				}
			}
		}
		
		//exit;
		
		//////////////////////////////////////////////////////////////////
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		//$email_id = $this->input->post('id');
		//$pagingid = $this->obj->getemailpagingid($email_id);
                $selected_view_session = $this->session->userdata('selected_view_session');
                if($selected_view_session['selected_view'] == '2')
                    $searchsort_session = $this->session->userdata('premium_iplans_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
                $pagingid = $searchsort_session['uri_segment'];
                if($selected_view_session['selected_view'] == '2')
                    $sel_val = 'premium_plan';
                else
                    $sel_val = 'my_plan';
                
                //redirect(base_url('admin/'.$this->viewName.'/'.$pagingid.'#'.$sel_val));
				if(!empty($submitbtn_action) && isset($submitbtn_action))
				{
					redirect('admin/interaction/'.$id);					
				}
				else
				{
					redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));	
				}
                
        //redirect('admin/'.$this->viewName);				
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
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
			$this->obj->delete_contact_trans_record_indi($interaction_plan_id,$contact_id);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			$this->obj->delete_contact_communication_plan_trans_record_indi($interaction_plan_id,$contact_id);
			
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
					elseif($row['interaction_type'] == 6 || $row['interaction_type'] == 8)
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
				$data_conv['created_by'] = $this->admin_session['id'];
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
    @Description: Function for Delete contacts Profile By Admin
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
        redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
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
    @Description: Function for Unpublish contacts Profile By Admin
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
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By Admin
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
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
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
    @Description: Function use to Delete contact in by admin
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	
	public function ajax_Active_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
                $selected_view=$this->input->post('selected_view');
                $selected_view = !empty($selected_view)?$selected_view:'1';
		$pagingid='';
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '1';
			$this->obj->update_record($cdata);
			
			//$email_id = $id;
			//$pagingid = $this->obj->getemailpagingid($email_id);
                        if($selected_view == '1')
                            $searchsort_session = $this->session->userdata('iplan_view_archive_sortsearchpage_data');
						else if($selected_view == '3')
                        	$searchsort_session = $this->session->userdata('default_view_archive_sortsearchpage_data');
                        else
                            $searchsort_session = $this->session->userdata('premium_iplan_view_archive_sortsearchpage_data');
                        if(!empty($searchsort_session['uri_segment']))
                            $pagingid = $searchsort_session['uri_segment'];
                        else
                            $pagingid = 0;
			
			unset($id);
		}
		$array_data=$this->input->post('myarray');
                $delete_all_flag = 0;$cnt = 0;
		if(!empty($array_data))
		{
			//$email_id = $array_data[0];
			//$pagingid = $this->obj->getemailpagingid($email_id);
                    if($selected_view == '1')
                        $searchsort_session = $this->session->userdata('iplan_view_archive_sortsearchpage_data');
					else if($selected_view == '3')
                        $searchsort_session = $this->session->userdata('default_view_archive_sortsearchpage_data');
                    else
                        $searchsort_session = $this->session->userdata('premium_iplan_view_archive_sortsearchpage_data');
                        if(!empty($searchsort_session['uri_segment']))
                            $pagingid = $searchsort_session['uri_segment'];
                        else
                            $pagingid = 0;
		}
		
		for($i=0;$i<count($array_data);$i++)
		{
			$data['id']=$array_data[$i];
			$data['status']='1';
			$this->obj->update_record($data);
                        $delete_all_flag = 1;
                        $cnt++;
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
            $selected_view=$this->input->post('selected_view');
            $selected_view = !empty($selected_view)?$selected_view:'1';
            $pagingid='';
			$array_data=$this->input->post('myarray');
            $delete_all_flag = 0;$cnt = 0;
            if(!empty($id))
            {
                $cdata['id'] = $id;
                $cdata['status'] = '0';
                $this->obj->update_record($cdata);
					//echo $this->db->last_query();
                //$email_id = $id;
                //$pagingid = $this->obj->getemailpagingid($email_id);
                if($selected_view == '1')
                    $searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
				else if($selected_view == '3')
                     $searchsort_session = $this->session->userdata('default_view_archive_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('premium_iplans_sortsearchpage_data');
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
                if($selected_view == '1')
                    $searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
				else if($selected_view == '3')
                        $searchsort_session = $this->session->userdata('default_view_archive_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('premium_iplans_sortsearchpage_data');
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
		$searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
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
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	
	public function view_contacts_of_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$where = array('ct.interaction_plan_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
		$data['sortfield'] = 'cm.first_name';
		$data['sortby'] = 'asc';
		
        //$id = $this->uri->segment(4);
		$match = array("ipm.id"=>$id);
		$table = " interaction_plan_master as ipm";
		$fields = array('ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions');
		
		$join_tables = array(
							'interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' => 'ipim.interaction_plan_id = ipm.id'
						);
		
	    $data['editRecord'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','');	 
		
		/*if(isset($data['editRecord'][0]['status']) && ($data['editRecord'][0]['status'] == '0'))
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);	
		}*/
		
	  	//pr($data['editRecord']);
		$data['interaction_plan_status'] = $this->obj1->select_records1('','','','=','','','','name','asc','interaction_plan__status_master');	
		
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet' => 'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		
		
	    $data['contact_listdata'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		//pr($data['contact_listdata']);exit;
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('ct.interaction_plan_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id'
						);
		$group_by='cm.id';
		
		$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		//pr($data['contacts_data']);
		
		///////////////////////////////////////////////////////////////////////////	
		
		$match = array('interaction_plan_id'=>$id);
        $data['interaction_plan_time_trans'] = $this->obj->select_records_plan_time_trans('',$match,'','=','','','','id','asc');
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		//pr($data['interaction_plan_time_trans']);exit;
		
		///////////////////////////////////////////////////////////////////////////	
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
    
		
		//$this->load->view($this->user_type.'/'.$this->viewName."/view_contact_popup",$data);
		$this->load->view($this->user_type.'/'.$this->viewName."/view_contact_assign_popup",$data);
	}
	
	/*
    @Description: Function use to pause plan by admin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function pause_interaction_plan($plan_id='')
	{
		//echo $plan_id."hiii";exit;
		if(!empty($plan_id))
			$id = $plan_id;
		else
			$id = $this->input->post('interaction_plan');
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '2';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->admin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'paused');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->admin_session['id'];
			
			$this->obj->update_record($cdata);
		}
		
	}
	
	/*
    @Description: Function use to stop plan by admin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function stop_interaction_plan($plan_id='')
	{
		if(!empty($plan_id))
			$id = $plan_id;
		else
			$id=$this->input->post('interaction_plan');
		
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '3';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->admin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'stop');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->admin_session['id'];
			
			$this->obj->update_record($cdata);
		}
		
		$table ="interaction_plan_interaction_master as ipi";
		$fields = array('ipi.id');
		$join_tables = array(
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$wherestring = "ipm.id = ".$id;
		//$wherestring = "(ecm.email_type = 'Intereaction_plan' OR scm.sms_type = 'Intereaction_plan') AND ipm.id = ".$id;
		$interaction_plan_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],'','ipi.id','desc',$groupby,$wherestring);
		
		//pr($email_campaign_data); exit;
		foreach($interaction_plan_data as $row)
		{
			$interaction_id = $row['id'];
			$match = array('interaction_id'=>$interaction_id);
			$sms_campaign_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
			if(count($sms_campaign_exist) > 0)
				$this->sms_campaign_recepient_trans_model->delete_record_campaign('',$sms_campaign_exist[0]['id']);
			
			$match = array('interaction_id'=>$interaction_id);
			$email_campaign_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
			
			if(count($email_campaign_exist) > 0)
				$this->email_campaign_master_model->email_campaign_trans_delete('',$email_campaign_exist[0]['id']);
		}
		
		
		$this->obj->delete_contact_communication_plan_trans_record_not_done($id);
		
	}
	
	/*
    @Description: Function use to play plan by admin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 16-08-2014
    */
	
	public function play_interaction_plan($plan_id='')
	{
		if(!empty($plan_id))
			$id = $plan_id;
		else
			$id = $this->input->post('interaction_plan');
		
		$match = array('interaction_plan_id'=>$id);
        $interaction_plan_time_trans = $this->obj->select_records_plan_time_trans('',$match,'','=','','1','','id','desc');
		
		$tcdata['interaction_plan_id'] = $id;
		$tcdata['interaction_time_type'] = '4';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->admin_session['id'];
		
		$this->obj->insert_time_record($tcdata);
		
		$match = array('name'=>'active');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		//pr($data['interaction_plan_status']);exit;
		
		if(!empty($data['interaction_plan_status'][0]['id']))
		{
			$cdata['id'] = $id;
			$cdata['plan_status'] = $data['interaction_plan_status'][0]['id'];
			
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['modified_by'] = $this->admin_session['id'];
			
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
					
					$table = "interaction_plan_interaction_master as ipim";
					$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
					$join_tables = array(
										'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
										'admin_users as au' => 'au.id = ipim.created_by',
										'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
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
								$uictdata['modified_by'] = $this->admin_session['id'];
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
										$contact_interaction_plan_interaction_id = $this->obj2->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
										
										//pr($contact_interaction_plan_interaction_id);
										//exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
										
										
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
										
										$this->obj2->update_contact_communication_record($iccdata1);
										
										if($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8)
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
										
										}
										
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
					$fields = array('ct.interaction_plan_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','ct.id as ctid','ct.start_date','cm.*');
					$where = array('ct.interaction_plan_id'=>$plan_id);
					$join_tables = array(
										'contact_master as cm'=>'cm.id = ct.contact_id'
									);
					$group_by='cm.id';
					
					$old_contacts_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
					
					///////////////////////////////////////////////////////////////////////////
							
					////////////////////////////////////////////////////////
					
					$table = "interaction_plan_interaction_master as ipim";
					$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
					$join_tables = array(
										'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
										'admin_users as au' => 'au.id = ipim.created_by',
										'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
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
								$uictdata['modified_by'] = $this->admin_session['id'];
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
								
								$sendemaildate = $iccdata['task_date'];
								
								$iccdata['created_date'] = date('Y-m-d H:i:s');
								$iccdata['created_by'] = $this->admin_session['id'];
								
								$this->obj2->insert_contact_communication_record($iccdata);
								
								$agent_name = '';
								if(!empty($row1['assign_to']))
								{
									
									$table ="login_master as lm";   
									$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
									$join_tables = array('user_master as um'=>'lm.user_id = um.id');
									$wherestring = 'lm.id = '.$row['assign_to'];
									$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','',$wherestring);
									if(!empty($agent_datalist))
									{
										if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
											$agent_name = $agent_datalist[0]['admin_name'];
										else
											$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
									}
								}
								
								if($row1['interaction_type'] == '6' && !empty($row1['template_name']))
								{
									//echo $row1['id'];
									$match = array('interaction_id'=>$row1['id']);
									$interaction_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
									/*$table ="email_campaign_recepient_trans as ecr";
									$fields = array('ecm.*');
									$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id');
									$wherestring = "ecm.interaction_id = ".$row1['id']." AND ecr.contact_id = ".$row['id'];
									$interaction_exist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ecr.id','desc','',$wherestring);*/
									
									if(count($interaction_exist) > 0)
									{
										
										/*$match = array('contact_id'=>$row['id'],'email_campaign_id'=>$interaction_exist[0]['id'],'is_send'=>"'1'");
										//echo $interaction_exist[0]['id'];
										$contact_exist = $this->email_campaign_master_model->check_mail_send_or_no($match);
										if(count($contact_exist) == 0){ } */
										
										$cdata1['email_campaign_id'] = $interaction_exist[0]['id'];
										$match = array('id'=>$row1['template_name']);
										$result = $this->email_library_model->select_records('',$match,'','=');
										/*echo $this->db->last_query();
										pr($result);*/
										if(!empty($result[0]['id']))
										{
											$rowdatainst = $result[0];
									
											$cdata1['contact_id'] = $row['id'];
											$emaildata = array(
																'Date'=>date('Y-m-d'),
																'Day'=>date('l'),
																'Month'=>date('F'),
																'Year'=>date('Y'),
																'Day Of Week'=>date( "w", time()),
																'Agent Name'=>$agent_name,
																'Contact First Name'=>$row['first_name'],
																'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
																'Contact Last Name'=>$row['last_name'],
																'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
																'Contact Company Name'=>$row['company_name']
															  );
											
											$content = $rowdatainst['email_message'];
											$title = $rowdatainst['template_subject'];
											
											//pr($emaildata);exit;
											
											$cdata1['template_subject'] 	= $title;
											$cdata1['email_message'] 	= $content;
											$pattern = "{(%s)}";
											
											$map = array();
											
											if($emaildata != '' && count($emaildata) > 0)
											{
												foreach($emaildata as $var => $value)
												{
													$map[sprintf($pattern, $var)] = $value;
												}
												$finaltitle = strtr($title, $map);				
												$output = strtr($content, $map);
												
												$cdata1['template_subject'] = $finaltitle;
												$finlaOutput = $output;
												$cdata1['email_message'] = $finlaOutput;
											}
											$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											//$from_data .= $contact_id[$i].",";
											$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
										}
									}
								}
								elseif($row1['interaction_type'] == '3' && !empty($row1['template_name']))
								{
									$match = array('interaction_id'=>$row1['id']);
									$interaction_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
										
									/*$table ="sms_campaign_recepient_trans as scr";
									$fields = array('scm.*');
									$join_tables = array('sms_campaign_master as scm'=>'scm.id = scr.sms_campaign_id');
									$wherestring = "scm.interaction_id = ".$row1['id']." AND scr.contact_id = ".$row['id'];
									$interaction_exist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ecr.id','desc','',$wherestring);
									*/
									
									//pr($interaction_exist);
									if(count($interaction_exist) > 0)
									{
										/*$match = array('contact_id'=>$row['id'],'sms_campaign_id'=>$interaction_exist[0]['id'],'is_send'=>"'1'");
										//echo $interaction_exist[0]['id'];
										//echo $interaction_exist[0]['id'];
										$contact_exist = $this->sms_campaign_master_model->check_mail_send_or_no($match);
										if(count($contact_exist) == 0){*/
											
										$cdata1['sms_campaign_id'] = $interaction_exist[0]['id'];
										$match = array('id'=>$row1['template_name']);
										$result = $this->sms_texts_model->select_records('',$match,'','=');
										
										if(!empty($result[0]['id']))
										{
											$rowdatainst = $result[0];
									
											$cdata1['contact_id'] = $row['id'];
											$emaildata = array(
															'Date'=>date('Y-m-d'),
															'Day'=>date('l'),
															'Month'=>date('F'),
															'Year'=>date('Y'),
															'Day Of Week'=>date( "w", time()),
															'Agent Name'=>$agent_name,
															'Contact First Name'=>$row['first_name'],
															'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
															'Contact Last Name'=>$row['last_name'],
															'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
															'Contact Company Name'=>$row['company_name']);
											
											$content = $rowdatainst['sms_message'];
											$cdata1['sms_message'] 	= $content;
											$pattern = "{(%s)}";
											
											$map = array();
											
											if($emaildata != '' && count($emaildata) > 0)
											{
												foreach($emaildata as $var => $value)
												{
													$map[sprintf($pattern, $var)] = $value;
												}
												$output = strtr($content, $map);
												
												$finlaOutput = $output;
												$cdata1['sms_message'] = $finlaOutput;
											}
											$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											//$from_data .= $contact_id[$i].",";
											//pr($cdata1);
											$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
											//echo $this->db->last_query();
										}
									}
								}
								
								unset($cdata1);
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
            'sortfield'  => 'ipm.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        if($selected_view == '2')
            $this->session->set_userdata('premium_iplans_sortsearchpage_data', $sortsearchpage_data);
		else if($selected_view == '3')
            $this->session->set_userdata('default_iplans_sortsearchpage_data', $sortsearchpage_data);	
        else
            $this->session->set_userdata('iplans_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('selected_view_session',$data);
    }
	
	public function premium_plan_update()
	{
		$id = $this->router->uri->segments[4];
		$fields1 = array('id');
		$parent_db_name = $this->config->item('parent_db_name');
		$sesion_db = $this->session->userdata('db_session');
		$match1 = array('db_name'=>$sesion_db['db_name'],'email_id'=>$this->admin_session['useremail']);
		$admin_data = $this->admin_model->get_user($fields1,$match1,'','=','','','','','','',$parent_db_name);
		if(count($admin_data) > 0)
			$admin_id = $admin_data[0]['id'];
		else
			$admin_id = 0;
		/*$table = "interaction_plan_adminuser_trans as ipat ";
        $fields = array('ipmp.*,ipat.id as adminuser_trans_id,ipat.user_id');
        $join_tables = array('interaction_plan_master_premium as ipmp' => 'ipmp.id = ipat.interaction_plan_id'
						);*/
      	//$group_by = 'ipmp.id';
		$wherestring = 'ipmp.modified_date > ipat.modified_date AND user_id = '.$admin_id.' AND ipmp.id = '.$id;
		//$premium_plan_update = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','','',$wherestring);
		
		$parent_db_name = $this->config->item('parent_db_name');
		$premium_plan_update = $this->obj->get_updated_premium_plans($parent_db_name,$wherestring);

		/*echo $this->db->last_query();
		pr($premium_plan_update);exit;*/
		
		$match = array('p_p_id'=>$id,'created_by'=>$this->admin_session['admin_id']);
		$total_plan = $this->obj->select_records('',$match,'','=');
		if(count($total_plan) > 0)
			$last_plan = count($total_plan);
		else
			$last_plan = '';
		
		if(count(premium_plan_update) > 0)
		{
			$data['p_p_id'] = $id;
			$data['created_by'] = $this->admin_session['id'];
			$data['version'] = '0';
			$this->obj->update_interaction_plan($data);
			//$modified_date = date('Y-m-d H:i:s');
			foreach($premium_plan_update as $interaction_plan_data)
			{
				$plan_data['plan_name'] = $interaction_plan_data['plan_name'].' - Premium '.$last_plan;
				$plan_data['description'] = $interaction_plan_data['description'];
				$plan_data['plan_status'] = $interaction_plan_data['plan_status'];
				$plan_data['target_audience'] = $interaction_plan_data['target_audience'];
				$plan_data['plan_start_type'] = $interaction_plan_data['plan_start_type'];
				$plan_data['start_date'] = date('Y-m-d',strtotime($interaction_plan_data['start_date'])); //echo $plan_data['start_date']; exit;
				$plan_data['p_p_id'] = $interaction_plan_data['id'];
				$plan_data['by_superadmin'] = '1';
				$plan_data['version'] = '1';
				$plan_data['created_date'] = date('Y-m-d H:i:s');
				$plan_data['status'] = '1';
				$plan_data['created_by'] = $this->admin_session['id'];
				$row = $interaction_plan_data['user_id'];
				$last_id = $this->interaction_plans_model->insert_record($plan_data);
				//$last_id = 11;
				$cdata['id'] = $interaction_plan_data['adminuser_trans_id'];
				$cdata['modified_date'] = date('Y-m-d H:i:s');
				$this->obj->update_adminuser_trans_record($cdata,$parent_db_name);
				//pr($cdata);
				/*$cdata['id'] = $interaction_plan_data['id'];
				$this->interaction_plans_premium_model->update_record($cdata);*/
				
				
				$table = $parent_db_name.".interaction_plan_interaction_master_premium as ipim";
				$fields = array('ipim.*');
				$join_tables = array(
									$parent_db_name.'.interaction_plan_master_premium as ipmp' => 'ipmp.id = ipim.interaction_plan_id',
									);
				
				$group_by='ipim.id';
				$where1 = array('ipim.interaction_plan_id'=>$interaction_plan_data['id']);
				
				$interaction_list =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ipim.id','asc',$group_by,$where1);
				
				if(count($interaction_list) > 0)
				{
					foreach($interaction_list as $row1)
					{
						//pr($row1);exit;
						$idata['interaction_plan_id'] = $last_id;
						
						//$idata['interaction_plan_id'] = $plan_id;
						$idata['assign_to'] = $this->admin_session['admin_id'];
						$idata['created_by'] = $this->admin_session['id'];
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
						//$idata['template_category'] = $row1['template_category'];
						//$idata['template_subcategory'] = $row1['template_subcategory'];
						//$idata['template_name'] = $row1['template_name'];
						$db_session = $this->session->userdata('db_session');
						$db_name = $db_session['db_name'];
						if(!empty($row1['template_category']))
								{
									$where = 'superadmin_cat_id = '.$row1['template_category'];
									$table = 'marketing_master_lib__category_master';
									$category_details = $this->obj->getmultiple_tables_records($db_name.'.'.$table,'','','','','','','','','id','desc','',$where);
									if(!empty($category_details))
										$idata['template_category'] = $category_details[0]['id'];
									else
									{
										$where = 'id = '.$row1['template_category'];
										$category_details = $this->obj->getmultiple_tables_records($this->parent_db_name.".".$table,'','','','','','','','','id','desc','',$where);
										if(!empty($category_details))
										{
											$category_data['created_by'] = '1';
											$category_data['category'][0] = $category_details[0]['category'];
											$category_data['created_date'] = date('Y-m-d H:i:s');	
											$category_data['superadmin_cat_id'] = $category_details[0]['id'];
											$category_data['status'] = '1';
											//pr($data);
											$last_cat_id = $this->marketing_library_masters_model->insert_category_record($db_name,$category_data);
											$idata['template_category'] = $last_cat_id;
										}
									}
								}
								
								
								if(!empty($idata['interaction_type']) && $idata['interaction_type'] == 1)
								{
									$table = 'label_template_master';
									$model = 'label_library_model';
								}
								elseif(!empty($idata['interaction_type']) && $idata['interaction_type'] == 2)
								{
									$table = 'envelope_template_master';
									$model = 'envelope_library_model';
								}
								elseif(!empty($idata['interaction_type']) && $idata['interaction_type'] == 3)
								{
									$table = 'sms_text_template_master';
									$model = 'sms_texts_model';
								}
								elseif(!empty($idata['interaction_type']) && $idata['interaction_type'] == 4)
								{
									$table = 'phone_call_script_master';
									$model = 'phonecall_script_model';
								}elseif(!empty($idata['interaction_type']) && $idata['interaction_type'] == 5)
								{
									$table = 'letter_template_master';
									$model = 'letter_library_model';
								}elseif(!empty($idata['interaction_type']) && $idata['interaction_type'] == 6)
								{
									$table = 'email_template_master';
									$model = 'email_library_model';
								}
								
								if(!empty($row1['template_name']))
								{
									$where = 'superadmin_template_id = '.$row1['template_name'];
									$template_details = $this->obj->getmultiple_tables_records($db_name.'.'.$table,'','','','','','','','','id','desc','',$where);
									/*echo $this->db->last_query();
									pr($template_details);exit;*/
									
									if(!empty($template_details)){
										$idata['template_name'] = $template_details[0]['id']; }
									else
									{
										$where = 'id = '.$row1['template_name'];
										$template_details = $this->obj->getmultiple_tables_records($this->parent_db_name.'.'.$table,'','','','','','','','','id','desc','',$where);
										//pr($template_details);exit;
										if(!empty($template_details))
										{
											$last_cat_id = $this->$model->get_update_template($this->parent_db_name,$row1['template_name'],$template_details[0]['template_name'],$db_name);
											$idata['template_name'] = $last_cat_id;
										}
									}
								}
								
						//exit;
						$idata['interaction_sequence_date'] = $row1['interaction_sequence_date'];
						$idata['send_automatically'] = $row1['send_automatically'];
						$idata['include_signature'] = $row1['include_signature'];
						$idata['premium_plan_id'] = $row1['id'];
						$idata['status'] = '1';
						
						//pr($cdata);
						$ins = $this->interaction_model->insert_record($idata);
					}
					foreach($interaction_list as $row2)
					{
						if(!empty($row2['interaction_id']))
						{
							$match = array('premium_plan_id'=>$row2['interaction_id'],'interaction_plan_id'=>$last_id);
							$result = $this->obj2->select_records('',$match,'','=');
							
							//echo $row2['interaction_id'].'    '.count($result);
							$match = array('premium_plan_id'=>$row2['id'],'interaction_plan_id'=>$last_id);
							$res = $this->obj2->select_records('',$match,'','=');
							
							//echo count($res);
							if(count($result) > 0 && count($res) > 0)
							{
								$update_plan['id'] = $res[0]['id'];
								$update_plan['interaction_id'] = $result[0]['id'];
								$this->obj2->update_record($update_plan);
								//echo $this->db->last_query();exit;
							}
						}
					}
				}
			}
		}
		//exit;	

		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
	
		$selected_view_session = $this->session->userdata('selected_view_session');
		if($selected_view_session['selected_view'] == '2')
			$searchsort_session = $this->session->userdata('premium_iplans_sortsearchpage_data');
		else
			$searchsort_session = $this->session->userdata('iplans_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		if($selected_view_session['selected_view'] == '2')
			$sel_val = 'premium_plan';
		else
			$sel_val = 'my_plan';
		redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
    	
	}
	
	public function all_pause_play_stop()
	{
		//pr($_POST);exit;
		$plan_status = $this->input->post('plan_status');
		$where = array('status'=>'1');
		$table = "interaction_plan_master as ipm ";
		$fields = array('ipm.id','ipm.plan_status');
		$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where);
		/*echo $this->db->last_query();
		pr($datalist);exit;*/
		if(count($datalist) > 0 && !empty($plan_status))
		{
			if($plan_status == '1')
			{
				$function_name = 'play_interaction_plan';
				$match = array('name'=>'Active');
			}
			elseif($plan_status == '2')
			{
				$function_name = 'pause_interaction_plan';
				$match = array('name'=>'paused');
			}
			elseif($plan_status == '3')
			{
				$function_name = 'stop_interaction_plan';
				$match = array('name'=>'stop');
			}
			
			$interaction_plan_status = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
			if(!empty($interaction_plan_status[0]['name']))
			{
				foreach($datalist as $row)
				{
					if(!empty($row['plan_status']) && $row['plan_status'] != $interaction_plan_status[0]['id'])
					{
						$this->$function_name($row['id']);
					}
				}
			}
		}
		redirect(base_url('admin/'.$this->viewName.'/interaction_plans_home'));
	}
	
}