<?php 
/*
    @Description: contacts controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contacts_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
		$this->message_session = $this->session->userdata('message_session');
		$this->message_session1 = $this->session->userdata('message_session1');
        check_user_login();
		//pr($this->user_session);exit;
		$this->load->model('contacts_model');
		$this->load->model('contact_masters_model');
		$this->load->model('imageupload_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('interaction_model');
		$this->load->model('user_management_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('sms_texts_model');
		$this->load->model('task_model');
		$this->load->model('socialmedia_post_model');
		$this->load->model('admin_model');
		$this->load->model('favorite_model');
		$this->load->model('properties_viewed_model');
		$this->load->model('saved_searches_model');
		$this->load->model('user_registration_model');
		$this->load->model('last_login_model');
		$this->load->model('ws/property_valuation_searches_model');
                $this->load->model('property_valuation_contact_model');
                $this->load->model('property_contact_model');
		$this->obj = $this->contacts_model;
		$this->obj1 = $this->contact_masters_model;
		$this->obj2 = $this->interaction_plans_model;
		$this->obj3 = $this->interaction_plan_masters_model;
		$this->obj4 = $this->interaction_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
		ini_set('memory_limit', '-1');
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
		//check user right
		check_rights('contact');
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		$created_type = $this->input->post('created_type');
		$current_date = $this->input->post('new_contact');
		
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');
            if(!empty($searchsort_session['created_type']) && $searchtext == $searchsort_session['searchtext'])
            {}
            else
            {
                $this->session->unset_userdata('contacts_sortsearchpage_data');
            }   
        }
        $data['sortfield']		= 'cm.id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');

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
                $sortfield = 'cm.id';
                $sortby = 'desc';
            }
        }
        if(!empty($created_type))
        {
            //$searchtext = $this->input->post('searchtext');
            $data['created_type'] = stripslashes($created_type);
        } else {
                 if(!empty($searchsort_session['created_type'])) {
                $created_type =  mysql_real_escape_string($searchsort_session['created_type']);
				$data['created_type'] = $searchsort_session['created_type'];
            }
        }
        if(!empty($searchtext))
        {
            //$searchtext = $this->input->post('searchtext');
            //$data['searchtext'] = $searchtext;
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
		if(!empty($current_date))
		{
			$icdata['login_id'] = $this->user_session['id'];
			if(!empty($created_type))
            {
                $icdata['manual_contact_last_seen'] = date('Y-m-d H:i:s');
            }
            else
            {
                $icdata['contact_last_seen'] = date('Y-m-d H:i:s');
            }
			$this->obj->update_last_seen($icdata);
			//$searchtext = $current_date;
			
			$contacts_sortsearchpage_data = array(
			'created_type'=>$created_type,	
                        'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($searchtext)?$searchtext:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                        'current_date' => !empty($current_date)?$current_date:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        	$this->session->set_userdata('contacts_sortsearchpage_data', $contacts_sortsearchpage_data);
			redirect('user/'.$this->viewName);
		}
        $config['base_url'] = site_url($this->user_type.'/'."contacts/");

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
		
		/*$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm1.name ORDER BY ctm1.name separator \',\') as contact_type','cpt.phone_no','cet.email_address','cm.created_by as createdby_id','uct.id as uct_id','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name','cm.created_by','cm.created_type');
		
		$join_tables = array(
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'contact_contacttype_trans as ctt1' => 'ctt1.contact_id = cm.id',
							'contact__type_master as ctm' => 'ctm.id = ctt.contact_type_id',
							'contact__type_master as ctm1' => 'ctm1.id = ctt1.contact_type_id',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
							'interaction_plan_contacts_trans as ipct'=>'ipct.contact_id = cm.id',
							'interaction_plan_master as ipm'=>'ipct.interaction_plan_id = ipm.id',
							'interaction_plan_interaction_master as ipi'=>'ipi.interaction_plan_id = ipm.id',
							'contact_conversations_trans as cct'=>'cct.contact_id = cm.id',
							'task_master as tm'=>'cct.task_id = tm.id',
							'task_user_transcation as tut'=>'tut.task_id = tm.id',
							'login_master as lm' => 'lm.id = cm.created_by',
							'user_master as um' => 'um.id = lm.user_id'
						);
		$group_by='cm.id';*/
		
                $data['current_date'] = 0;
                if(!empty($searchsort_session['current_date']) && $searchsort_session['current_date'] != '0000-00-00 00:00:00')
                    $data['current_date'] = $searchsort_session['current_date'];
                
		$table = "contact_master as cm";
		$fields = array('cm.id,cm.is_subscribe','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','cpt.phone_no','cet.email_address','cm.created_by as createdby_id','uct.id as uct_id','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name','cm.created_by','cm.created_type,cm.created_type,cet.id as email_trans_id,cpt.id as phone_trans_id','ipi.assign_to,cm.created_date');
		
		$join_tables = array(
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
							'interaction_plan_contacts_trans as ipct'=>'ipct.contact_id = cm.id',
							'interaction_plan_master as ipm'=>'ipct.interaction_plan_id = ipm.id',
							'interaction_plan_interaction_master as ipi'=>'ipi.interaction_plan_id = ipm.id',
							'contact_conversations_trans as cct'=>'cct.contact_id = cm.id',
							'task_master as tm'=>'cct.task_id = tm.id',
							'task_user_transcation as tut'=>'tut.task_id = tm.id',
							'login_master as lm' => 'lm.id = cm.created_by',
							'user_master as um' => 'um.id = lm.user_id'
						);
		$group_by='cm.id';
		
		if(!empty($searchtext))
		{
			//$searchtext = mysql_real_escape_string($searchtext);	
			/*$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'phone_no'=>$searchtext,'tag'=>$searchtext,'csm.name'=>$searchtext,'ctm.name'=>$searchtext,'cm.company_name'=>$searchtext);*/
			
			//$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'phone_no'=>$searchtext,'tag'=>$searchtext,'csm.name'=>$searchtext,'cm.company_name'=>$searchtext,'cm.created_date'=>date('Y-m-d',strtotime($searchtext)));
			if(!empty($created_type))
			{
			    $cre="cm.created_type = '1' and";      
			}
			else
			{$cre='';}


			$wherestring =  $cre.' (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR ipi.assign_to IN ('.$this->user_session['agent_id'].') OR tut.user_id IN ('.$this->user_session['agent_id'].'))';
			
			$wherestring .= ' AND (CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.first_name,cm.last_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name) LIKE "%'.$searchtext.'%" OR email_address LIKE "%'.$searchtext.'%" OR phone_no LIKE "%'.$searchtext.'%" OR tag LIKE "%'.$searchtext.'%" OR csm.name LIKE "%'.$searchtext.'%" OR cm.company_name LIKE "%'.$searchtext.'%"';
			if(date('Y-m-d H:i:s', strtotime($searchtext)) == $searchtext || $searchtext == '0000-00-00 00:00:00')
				 $wherestring .= ' OR cm.created_date > "'.date('Y-m-d H:i:s',strtotime($searchtext)).'" OR uct.created_date > "'.date('Y-m-d H:i:s',strtotime($searchtext)).'"';
			
			//$matchuser=array('uct.user_id'=>$this->user_session['user_id'],'cm.created_by'=>$this->user_session['id']);
			//$wherestring='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].' OR ipi.assign_to = '.$this->user_session['id'].' OR tut.user_id = '.$this->user_session['id'].')';
			
			$wherestring .= ')';
			
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
			
			/////////////
			
		}
		else
		{
                    $match = array();
                    if(!empty($created_type))
                    {
                          $match= array("cm.created_type" =>'1');
                    }
			$wherestring = '(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR ipi.assign_to IN ('.$this->user_session['agent_id'].') OR tut.user_id IN ('.$this->user_session['agent_id'].'))';
			
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
			//echo $this->db->last_query();exit;
			//pr($data['datalist']);
				//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,$wherestring,'','1');
			//exit;
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		$match=array('status'=> '1');
		$data['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		
		$data['user_right'] = $this->user_management_model->select_user_rights($this->user_session['user_id']);
		//pr($user_id);
		$contacts_sortsearchpage_data = array(
			'created_type'=>$created_type,
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                        'current_date' => !empty($data['current_date'])?$data['current_date']:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contacts_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
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
	public function view_archive()
    {
		//Check Archive Permision or not
		//pr($this->user_session);exit;
		
		////...
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('contact_view_archive_sortsearchpage_data');
                }
		$data['sortfield']		= 'cm.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('contact_view_archive_sortsearchpage_data');
                
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
                        $sortfield = 'cm.id';
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
		$config['base_url'] = site_url($this->user_type.'/'."contacts/view_archive/");
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
		
		/*$table = "contact_archive_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','cpt.phone_no','cet.email_address','CONCAT_WS(" ",um.first_name,um.last_name) as user_name','cm.created_by','ipi.assign_to','uct.id as uct_id');
		$join_tables = array(
                    'contact__status_master as csm' => 'csm.id = cm.contact_status',
                    'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
                    'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
                    'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
                    'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
                    'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
					'user_contact_trans as uct'=>'uct.contact_id = cm.id',
					'interaction_plan_contacts_trans as ipct'=>'ipct.contact_id = cm.id',
					'interaction_plan_master as ipm'=>'ipct.interaction_plan_id = ipm.id',
					'interaction_plan_interaction_master as ipi'=>'ipi.interaction_plan_id = ipm.id',
					'user_contact_trans as uct'=>'uct.contact_id = cm.id',
					'contact_conversations_trans as cct'=>'cct.contact_id = cm.id',
					'task_master as tm'=>'cct.task_id = tm.id',
					'task_user_transcation as tut'=>'tut.task_id = tm.id',
					'login_master as lm' => 'lm.id = cm.created_by',
					'user_master as um' => 'um.id = lm.user_id'
                );
		$group_by='cm.id';*/
		
		$table = "contact_archive_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','cpt.phone_no','cet.email_address','CONCAT_WS(" ",um.first_name,um.last_name) as user_name','cm.created_by','ipi.assign_to','uct.id as uct_id','cm.created_type');
		$join_tables = array(
                    'contact__status_master as csm' => 'csm.id = cm.contact_status',
                    'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
                    'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
                    'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
					'user_contact_trans as uct'=>'uct.contact_id = cm.id',
					'interaction_plan_contacts_trans as ipct'=>'ipct.contact_id = cm.id',
					'interaction_plan_master as ipm'=>'ipct.interaction_plan_id = ipm.id',
					'interaction_plan_interaction_master as ipi'=>'ipi.interaction_plan_id = ipm.id',
					'user_contact_trans as uct'=>'uct.contact_id = cm.id',
					'contact_conversations_trans as cct'=>'cct.contact_id = cm.id',
					'task_master as tm'=>'cct.task_id = tm.id',
					'task_user_transcation as tut'=>'tut.task_id = tm.id',
					'login_master as lm' => 'lm.id = cm.created_by',
					'user_master as um' => 'um.id = lm.user_id'
                );
		$group_by='cm.id';
		
		if(!empty($searchtext))
		{
					
                   /* $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'phone_no'=>$searchtext,'tag'=>$searchtext,'csm.name'=>$searchtext,'ctm.name'=>$searchtext,'cm.company_name'=>$searchtext);*/				
					
					$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext,'phone_no'=>$searchtext,'tag'=>$searchtext,'csm.name'=>$searchtext,'cm.company_name'=>$searchtext);
					
					//$wherestring='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].')';
					
					$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR ipi.assign_to IN ('.$this->user_session['agent_id'].') OR tut.user_id IN ('.$this->user_session['agent_id'].'))';	
					
                    /*$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
                    $config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));*/

                    /////////////

                    $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
                    $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1');

                    /////////////
			
		}
		else
		{
			//$wherestring='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].')';
			$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR ipi.assign_to IN ('.$this->user_session['agent_id'].') OR tut.user_id IN ('.$this->user_session['agent_id'].'))';
                    //$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
                    //$config['total_rows']= count($this->obj->select_records());

                    //////////////////////////////////////////////////////

                    $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
						//echo $this->db->last_query();exit;
						//pr($data['datalist']);exit;

                    $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
			
		}
		
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		$match=array('status'=> '1');
		$data['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');

		$data['user_right']=$this->user_management_model->select_user_rights($this->user_session['user_id']);
		
                $contact_view_archive_sortsearchpage_data = array(
                   'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('contact_view_archive_sortsearchpage_data', $contact_view_archive_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			
			$this->load->view($this->user_type.'/'.$this->viewName.'/archive_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/archive_list";
			$this->load->view('user/include/template',$data);
		}	
	}
	function add_to_archive()
    {
        $id = $this->uri->segment(4);
		$this->obj->archive_record($id);
		$msg = $this->lang->line('common_archive_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
	public function ajax_archive_all()
	{
		$id=$this->input->post('single_remove_id');
		$flag='';
		$pagingid='';
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			//$pagingid = $this->obj->getemailpagingid($id);
                        $searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');
                        if(!empty($searchsort_session['uri_segment']))
                            $pagingid = $searchsort_session['uri_segment'];
                        else
                            $pagingid = 0;
			$this->obj->archive_record($id);
			$flag = 1;
		}
		elseif(!empty($array_data))
		{
			
			$searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
			
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->archive_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
				$flag = 1;
			}
		}
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($cnt > $perpage)
			{
				while($pagingid >= $total_rows)
					$pagingid -= $perpage;
				//echo $pagingid;exit;
			}
			else
			{
				if($pagingid*$perpage > $total_rows) {
					if($total_rows % $perpage == 0)
					{
						$pagingid -= $perpage;
					}
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}

		if($pagingid < 0)
			$pagingid = 0;
		
		$flag_data['pagingid']=$pagingid;			
		if(empty($flag))
		{
			$flag_data['msg']=$this->lang->line('common_archive_msg');
		}
		
		echo json_encode($flag_data);
	}
	/*
    @Description: Function for archive contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - Archive id which contacts record want to publish
    @Output: - New contacts list after record is publish.
    @Date: 18-09-2014
    */
	public function ajax_add_to_active_all()
	{
		$id=$this->input->post('single_remove_id');
		$flag='';
		$array_data=$this->input->post('myarray');
       	$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->add_to_list_record($id);
			$flag = 1;
		}
		elseif(!empty($array_data))
		{
			$flag = 1;
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->add_to_list_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('contact_view_archive_sortsearchpage_data');
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
			
			$flag_data['pagingid'] = $pagingid;			
			if(empty($flag))
			{
				$flag_data['msg']=$this->lang->line('common_archive_msg');
			}
			
			
			echo json_encode($flag_data);
                //echo $pagingid;
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
		check_rights('contact_add');
		//$match = array("created_by"=>$this->user_session['id']);
		$match = array();
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		$data['contact_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
		$data['method_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__method_master');
		//Get communication plan data
		//$data['communication_plans'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc', 'interaction_plan_master');
		//$data['communication_plans'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc', 'interaction_plan_master');
		$status_value='1';
		$table = " interaction_plan_master as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*','ipim.assign_to');
		$join_tables = array(
							'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'login_master as lm'                            => 'lm.id = ipm.created_by',
							'user_master as um'                             => 'um.id = lm.user_id'
						);
		$group_by='ipm.id';
		$status_value='1';
		
		
		$match=array('ipm.status'=>$status_value);
		$wherestring='(ipim.assign_to IN ('.$this->user_session['agent_id'].') OR ipm.created_by IN ('.$this->user_session['agent_id'].'))';
		$data['communication_plans'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','',$sortfield,$sortby,$group_by,$wherestring);
		//pr($data['communication_plans']);exit;
		//$match = array('status'=> '1');
		//$data['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		
		$table = " interaction_plan_master as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*');
		$join_tables = array(
							'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'login_master as lm'                            => 'lm.id = ipm.created_by',
							'user_master as um'                             => 'um.id = lm.user_id'
						);
		$group_by='ipm.id';
		$status_value='1';
		
		//$match=array('ipm.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
		$wherestring = 'ipm.status = '.$status_value.' AND ipim.assign_to IN ('.$this->user_session['agent_id'].')';
		$data['admin_interection_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$group_by,$wherestring);//echo $this->db->last_query();
		
		//$match =array('user_type'=> '4','agent_id'=>$this->user_session['user_id']);
		//$data['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    }

    /*
    @Description: Function for Insert New contacts data
    @Author: Nishit Modi
    @Input: - Details of new contacts which is inserted into DB
    @Output: - List of contacts with new inserted records
    @Date: 04-07-2014
    */
    public function insert_data()
    {
		//pr($_POST);exit;
		$cdata['fb_id'] = $this->input->post('fbid');
		$cdata['prefix'] = $this->input->post('slt_prefix');
		$cdata['first_name'] = $this->input->post('txt_first_name');
		$cdata['middle_name'] = $this->input->post('txt_middle_name');
		$cdata['last_name'] = $this->input->post('txt_last_name');
		$cdata['spousefirst_name']=$this->input->post('txt_spousefirst_name');
		$cdata['spousemiddle_name']=$this->input->post('txt_spousemiddle_name');
		$cdata['spouselast_name']=$this->input->post('txt_spouselast_name');
		$cdata['company_name'] = $this->input->post('txt_company_name');   
		$cdata['company_post'] = $this->input->post('txt_company_post');   
		$cdata['is_lead'] = $this->input->post('chk_is_lead');
		$cdata['notes'] = $this->input->post('txtarea_notes');
		$cdata['contact_source'] = $this->input->post('slt_contact_source');
		$cdata['contact_method'] = $this->input->post('slt_contact_method');
		$cdata['contact_status'] = $this->input->post('slt_contact_status');   
		$cdata['created_type'] = '1';
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		/// Image Upload ///////
		$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('contact_pic');//new add
		$bgImgPath = $this->config->item('contact_big_img_path');
		$smallImgPath = $this->config->item('contact_small_img_path');
		if(!empty($_FILES['contact_pic']['name']))
		{  
			$uploadFile = 'contact_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
        if(!empty($cdata['first_name']))
		{    
			$contact_id = $this->obj->insert_record($cdata);	
		
		// Contact status add in Contact_contact_status_trans 
		if(!empty($cdata['contact_status']))
		{
			$data_status['contact_status_id'] = $cdata['contact_status'];
			$data_status['contact_id'] = $contact_id;
			$data_status['created_by'] = $this->user_session['id'];
			$data_status['created_date'] = date('Y-m-d H:i:s');
			$this->obj->insert_contact_contact_status_trans_record($data_status);
			unset($data_status);
		}
		unset($cdata);
		 // assign To User Contact
		$cudata['contact_id'] = $contact_id;
		$cudata['user_id'] = $this->user_session['user_id'];   
		$cudata['created_by'] = $this->user_session['id'];
		$cudata['created_date'] = date('Y-m-d H:i:s');		
		$cudata['status'] = '1';
		$match = array('id'=>$cudata['user_id']);
		$user_data = $this->admin_model->get_user($field, $match,'','=');
		if(!empty($user_data[0]['agent_type']))
			$cudata['agent_type'] = $user_data[0]['agent_type'];
		$this->obj->insert_user_contact_trans_record($cudata);
		unset($cudata);
		$allemailtype = $this->input->post('slt_email_type');
		$allemailaddress = $this->input->post('txt_email_address');
		$defaultemail = $this->input->post('rad_email_default');
		//echo "<pre>";print_r($defaultemail);exit();
		if(!empty($allemailtype) && count($allemailtype) > 0)
		{
			for($i=0;$i<count($allemailtype);$i++)
			{
				if(trim($allemailaddress[$i]) != "")
				{
					$cmdata['contact_id'] = $contact_id;
					$cmdata['email_type'] = $allemailtype[$i];
					//$cmdata['email_address'] = $allemailaddress[$i];
					$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
					if (preg_match($regex, $allemailaddress[$i])) 
					{
						$cmdata['email_address'] = strtolower($allemailaddress[$i]);
					}
					if($defaultemail == $allemailaddress[$i])
						$cmdata['is_default'] = '1';
					else
						$cmdata['is_default'] = '0';
					$cmdata['status'] = '1';
					
					$this->obj->insert_email_trans_record($cmdata);
					
					unset($cmdata);
				}
			}
		}
		
		$allphonetype = $this->input->post('slt_phone_type');
		$allphoneno = $this->input->post('txt_phone_no');
		$defaultphone = $this->input->post('rad_phone_default');
		
		if(!empty($allphonetype) && count($allphonetype) > 0)
		{
			for($i=0;$i<count($allphonetype);$i++)
			{
				if(trim($allphoneno[$i]) != "")
				{
					$cpdata['contact_id'] = $contact_id;
					$cpdata['phone_type'] = $allphonetype[$i];
					$cpdata['phone_no'] = $allphoneno[$i];
					if($defaultphone == $allphoneno[$i])
						$cpdata['is_default'] = '1';
					else
						$cpdata['is_default'] = '0';
					$cpdata['status'] = '1';
					
					$this->obj->insert_phone_trans_record($cpdata);
					
					unset($cpdata);
				}
			}
		}
		$alladdresstype = $this->input->post('slt_address_type');
		$alladdressline1 = $this->input->post('txtarea_address_line1');
		$alladdressline2 = $this->input->post('txtarea_address_line2');
		$alladdresscity = $this->input->post('txt_city');
		$alladdressstate = $this->input->post('txt_state');
		$alladdresszip = $this->input->post('txt_zip_code');
		$alladdresscountry = $this->input->post('txt_country');
		
		if(!empty($alladdresstype) && count($alladdresstype) > 0)
		{
			for($i=0;$i<count($alladdresstype);$i++)
			{
				if(trim($alladdresstype[$i]) != "" || trim($alladdressline1[$i]) != "" || trim($alladdressline2[$i]) != "" || trim($alladdresscity[$i]) != "" || trim($alladdressstate[$i]) != "" || trim($alladdresszip[$i]) != "" || trim($alladdresscountry[$i]) != "")
				{
					$cadata['contact_id'] = $contact_id;
					$cadata['address_type'] = $alladdresstype[$i];
					$cadata['address_line1'] = $alladdressline1[$i];
					$cadata['address_line2'] = $alladdressline2[$i];
					$cadata['city'] = $alladdresscity[$i];
					$cadata['state'] = $alladdressstate[$i];
					$cadata['zip_code'] = $alladdresszip[$i];
					$cadata['country'] = $alladdresscountry[$i];
					$cadata['status'] = '1';
					
					$this->obj->insert_address_trans_record($cadata);
					
					unset($cadata);
				}
			}
		}
		
		
		$allwebsitetype = $this->input->post('txt_website_type');
		$allwebsitename = $this->input->post('txt_website_name');
		
		if(!empty($allwebsitetype) && count($allwebsitetype) > 0)
		{
			for($i=0;$i<count($allwebsitetype);$i++)
			{
				if(trim($allwebsitename[$i]) != "")
				{
					$cwdata['contact_id'] = $contact_id;
					$cwdata['website_type'] = $allwebsitetype[$i];
					$cwdata['website_name'] = $allwebsitename[$i];
					$cwdata['status'] = '1';
					
					$this->obj->insert_website_trans_record($cwdata);
					
					unset($cwdata);
				}
			}
		}
		
		$allsocialtype = $this->input->post('slt_profile_type');
		$allsocialname = $this->input->post('txt_social_profile');
		
		if(!empty($allsocialtype) && count($allsocialtype) > 0)
		{
			for($i=0;$i<count($allsocialtype);$i++)
			{
				if(trim($allsocialname[$i]) != "")
				{
					$csdata['contact_id'] = $contact_id;
					$csdata['profile_type'] = $allsocialtype[$i];
					if($csdata['profile_type'] == 2)
					{
						$s_name = explode("twitter.com/",$allsocialname[$i]);
						//pr($s_name);
						$csdata['website_name'] = end($s_name);
						//pr($csdata['website_name']);
					}
					else
						$csdata['website_name'] = $allsocialname[$i];
						
					$csdata['status'] = '1';
					
					$this->obj->insert_social_trans_record($csdata);
					
					unset($csdata);
				}
			}
		}
		
		
		$allcontact_types = $this->input->post('chk_contact_type_id');
		
		if(!empty($allcontact_types) && count($allcontact_types) > 0)
		{
			foreach($allcontact_types as $row)
			{
				$ctdata['contact_id'] = $contact_id;
				$ctdata['contact_type_id'] = $row;
				
				$this->obj->insert_contact_type_record($ctdata);
				
				unset($ctdata);
			}
		}
		
		
		$alltags = explode(',',$this->input->post('txt_tag'));
		if(!empty($alltags) && count($alltags) > 0)
		{
                        $alltag = array();
			for($i=0;$i<count($alltags);$i++)
			{
                            if(stristr($alltags[$i],'NEWTAG-'))
                            {
                                $explode = explode('{^}',$alltags[$i]);
                                if(!empty($explode[1]))
                                {
                                    $ictdata['contact_id'] = $contact_id;
                                    $ictdata['tag'] = $explode[1];
                                    $ictdata['is_default'] = '2';
                                    $lastId = $this->obj->insert_tag_record($ictdata);
                                }
                            }
			}
		}
		
		
		$newplansarr = $this->input->post('slt_communication_plan_id');
		
		
		////////////////////////////////// Add Interaction Plan Contacts Transaction Data ///////////////////////////////////////////
		
		if(!empty($newplansarr) && count($newplansarr) > 0)
		{
			foreach($newplansarr as $interaction_plan_id)
			{
				if($interaction_plan_id != '')
				{
					$match1 = array('id'=>$interaction_plan_id);
					$plandata = $this->interaction_plans_model->select_records('',$match1,'','=');
					
					//pr($plandata);
					
					$cdata = $plandata[0];
					
					//pr($cdata);exit;
					
					if(!empty($cdata))
					{
						//echo $cdata['plan_name'];
						$data_conv['contact_id'] = $contact_id;
						$data_conv['plan_id'] = $interaction_plan_id;
						$data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
						$data_conv['created_date'] = date('Y-m-d H:i:s');
						$data_conv['log_type'] = '2';
						$data_conv['created_by'] = $this->user_session['id'];
						$data_conv['status'] = '1';
						
						//pr($data_conv);exit;
						
						$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
						
						$icdata['interaction_plan_id'] = $interaction_plan_id;
						$icdata['contact_id'] = $contact_id;
						
						if($cdata['plan_start_type'] == '1')
						{
							$icdata['start_date'] = date('Y-m-d');
							$icdata['plan_start_type'] = $cdata['plan_start_type'];
							$icdata['plan_start_date'] = $cdata['start_date'];
						}
						else
						{
							if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
								$icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
							else
								$icdata['start_date'] = date('Y-m-d');
							
							$icdata['plan_start_type'] = $cdata['plan_start_type'];
							$icdata['plan_start_date'] = $cdata['start_date'];
						}
						
						$icdata['created_date'] = date('Y-m-d H:i:s');
						$icdata['created_by'] = $this->user_session['id'];
						$icdata['status'] = '1';
						
						//pr($icdata);exit;
						
						$this->interaction_plans_model->insert_contact_trans_record($icdata);
						
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
						
						$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
						
						
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
								$iccdata['contact_id'] = $contact_id;
								$iccdata['interaction_plan_interaction_id'] = $row1['id'];
								$iccdata['interaction_type'] = $row1['interaction_type'];
								
								if($row1['start_type'] == '1')
								{
									$count = $row1['number_count'];
									$counttype = $row1['number_type'];
									
									///////////////////////////////////////////////////////////////
									
									$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
									$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
									
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
									
									$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id);
									
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
								$iccdata['created_by'] = $this->user_session['id'];
								
								$this->interaction_model->insert_contact_communication_record($iccdata);
								
								unset($iccdata);
								unset($user_work_off_days1);
								unset($special_days1);
								unset($leave_days1);
								
								/* Email campaign/SMS campaign Insert */
								
								/*$match = array('id'=>$contact_id);
								$userdata = $this->contacts_model->select_records('',$match,'','=');*/
								
								$table = "contact_master as cm";
								$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
								$where = array('cm.id'=>$contact_id);
								$join_tables = array(
													'contact_address_trans as cat'=>'cat.contact_id = cm.id',
												);
								$group_by='cm.id';
								$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,$where);
							
								$agent_name = '';
								
								if(!empty($row1['assign_to']))
								{
									$table ="login_master as lm";   
									$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
									$join_tables = array('user_master as um'=>'lm.user_id = um.id');
									$wherestring = 'lm.id = '.$row1['assign_to'];
									$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
									if(!empty($agent_datalist))
									{
										if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
											$agent_name = $agent_datalist[0]['admin_name'];
										else
											$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
									}
								}
							
								if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
								{	
									//$row1['id'];
									$match = array('interaction_id'=>$row1['id']);
									$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=');
									if(count($campaigndata) > 0)
									{
										$cdata1['email_campaign_id'] = $campaigndata[0]['id'];
										$cdata1['contact_id'] = $contact_id;
										$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
														'Agent Name'=>$agent_name,
														'Contact First Name'=>$userdata['first_name'],
														'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
														'Contact Last Name'=>$userdata['last_name'],
														'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
														'Contact Company Name'=>$userdata['company_name']
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
										$cdata1['contact_id'] = $contact_id;
										$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
														'Agent Name'=>$agent_name,
														'Contact First Name'=>$userdata['first_name'],
														'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
														'Contact Last Name'=>$userdata['last_name'],
														'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
														'Contact Company Name'=>$userdata['company_name']
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
								unset($cdata1);
								/* END */
								
							}
						}
						
						///////////////////////////////////////////////////////////////////////////
						unset($icdata);
						//exit;
						
					
					}
					
				}
			}
		}
		
		//exit;
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		//exit;
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		}
		$contacttab = $this->input->post('contacttab');
		
		$redirecttype = $this->input->post('submitbtn');
		
		if($redirecttype == 'Save Contact' || $contacttab == 3)
			redirect('user/'.$this->viewName);
		else
		{
			redirect('user/'.$this->viewName.'/edit_record/'.$contact_id.'/'.($contacttab+1));
		}
		
        //redirect('user/'.$this->viewName);				
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
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
		//check user right
		check_rights('contact_edit');       
	    $id = $this->uri->segment(4);
		
		//$match = array("created_by"=>$this->user_session['id']);
		$match = array();
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['field_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__additionalfield_master');
		$data['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['contact_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
		$data['method_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__method_master');
		//$match = array('created_by'=>$this->user_session['id']);
		//$data['communication_plans'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc', 'interaction_plan_master');
		$table = " interaction_plan_master as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*','ipim.assign_to');
		$join_tables = array(
							'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'login_master as lm'                            => 'lm.id = ipm.created_by',
							'user_master as um'                             => 'um.id = lm.user_id'
						);
		$group_by='ipm.id';
		$status_value='1';
		
		$match=array('ipm.status'=>$status_value);
		$wherestring='(ipim.assign_to IN ('.$this->user_session['agent_id'].') OR ipm.created_by IN ('.$this->user_session['agent_id'].'))';
		$data['communication_plans'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,$wherestring);
		//pr($data['communication_plans']);exit;
		//$match = array('status'=> '1');
		//$data['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		
		$table = " interaction_plan_master as ipm ";
		$fields = array('ipm.id','ipm.plan_status','ipm.*');
		$join_tables = array(
							'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
							'login_master as lm'                            => 'lm.id = ipm.created_by',
							'user_master as um'                             => 'um.id = lm.user_id'
						);
		$group_by='ipm.id';
		$status_value='1';
		
		//$match=array('ipm.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
		$wherestring = 'ipm.status = '.$status_value.' AND ipim.assign_to IN ('.$this->user_session['agent_id'].')';
		$data['admin_interection_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);//echo $this->db->last_query();
		
		$data['user_add_list'] = $this->obj->select_user_contact_trans_record($id);
		$data['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$data['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$data['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$data['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$data['field_trans_data'] = $this->obj->select_field_trans_record($id);
		$data['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->obj->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->obj->select_tag_record($id);
		$data['all_tag_trans_data'] = $this->obj->select_tag_record($id,'','1');
		$data['communication_trans_data'] = $this->obj->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->obj->select_document_trans_record($id);
		$data['communication_trans_data'] = $this->obj->select_communication_trans_record($id);
		
		
		$table = "contact_master as cm";
		$fields = array('cm.*');
		
		$join_tables = array(
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
						);
		$group_by='cm.id';
		
		$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].') AND cm.id = '.$id.'';
		$data['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','',$group_by,$wherestring);
		
		//////// Check Buyer Right or Not 
		//$match1 = array("id"=>$this->user_session['id'],"is_buyer_tab"=> "1");
        $match1 = array("id"=>$this->user_session['id']);
		$data['right_buyer'] = $this->obj1->select_records1('',$match1,'','=','','','','id','desc','login_master');
		
		//pr($data['right_buyer']);exit;
		//////End Buyer Tab
			
		if(empty($data['editRecord']))
		{
			$msg = $this->lang->line('common_right_msg');
        	$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);
		}
		
		/*$match = array('id'=>$id);
		
        $result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;*/
		$data['main_content'] = "user/".$this->viewName."/add";       
	   	$this->load->view("user/include/template",$data);
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
		//pr($_POST);exit;
	    $cdata['id'] = $this->input->post('id');
		$contact_id = $this->input->post('id');
		$submitvaltab2 = $this->input->post('submitvaltab2');
		$contacttab = $this->input->post('contacttab');
		
		if($contacttab == 1)
		{
			$cdata['fb_id'] = $this->input->post('fbid');
			$cdata['prefix'] = $this->input->post('slt_prefix');
			$cdata['first_name'] = $this->input->post('txt_first_name');
			$cdata['middle_name'] = $this->input->post('txt_middle_name');
			$cdata['spousefirst_name']=$this->input->post('txt_spousefirst_name');
			$cdata['spousemiddle_name']=$this->input->post('txt_spousemiddle_name');
			$cdata['spouselast_name']=$this->input->post('txt_spouselast_name');
			$cdata['last_name'] = $this->input->post('txt_last_name');
			$cdata['company_name'] = $this->input->post('txt_company_name');   
			$cdata['company_post'] = $this->input->post('txt_company_post');   
			$cdata['is_lead'] = $this->input->post('chk_is_lead');
			$cdata['notes'] = $this->input->post('txtarea_notes');
			$cdata['contact_source'] = $this->input->post('slt_contact_source');
			$cdata['contact_method'] = $this->input->post('slt_contact_method');
			$cdata['contact_status'] = $this->input->post('slt_contact_status');
			
			$cdata['modified_by'] = $this->user_session['id'];
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			
			 ////// image Upload //////////
			$image = $this->input->post('hiddenFile');
			$oldcontactimg = $this->input->post('contact_pic');//new add
			$bgImgPath = $this->config->item('contact_big_img_path');
			$smallImgPath = $this->config->item('contact_small_img_path');
			if(!empty($_FILES['contact_pic']['name']))
			{  
				$uploadFile = 'contact_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
				$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
			}
			// Contact status Update/add in Contact_contact_status_trans
			if(!empty($cdata['contact_status']))
			{
				$status_id =$this->obj->select_contact_status_trans_record_contact_id($contact_id,$cdata['contact_status']);
				if(empty($status_id))
				{
					$data_status['contact_status_id'] = $cdata['contact_status'];
					$data_status['contact_id'] = $contact_id;
					$data_status['created_by'] = $this->user_session['id'];
					$data_status['created_date'] = date('Y-m-d H:i:s');
					$this->obj->insert_contact_contact_status_trans_record($data_status);
				}	
				
			}
			/////// Image Upload //////////////
			$this->obj->update_record($cdata);
			
			unset($cdata);
			
			$slt_user = $this->input->post('slt_user');
			//echo $slt_user;exit;
			if(!empty($slt_user))
			{
				$user_add_list = $this->obj->select_user_contact_trans_record($contact_id);
				//pr($user_add_list);exit;
				if(!empty($user_add_list))
				{
					$this->obj->delete_table_user_contact_record($user_add_list[0]['contact_id']);
					$cudata['contact_id'] = $contact_id;
					$cudata['user_id'] = $this->input->post('slt_user');   
					$cudata['created_by'] = $this->user_session['id'];
					$cudata['created_date'] = date('Y-m-d H:i:s');		
					$cudata['status'] = '1';
					$this->obj->insert_user_contact_trans_record($cudata);
					unset($cudata);
				}
				else
				{
					$cudata['contact_id'] = $contact_id;
					$cudata['user_id'] = $this->input->post('slt_user');   
					$cudata['created_by'] = $this->user_session['id'];
					$cudata['created_date'] = date('Y-m-d H:i:s');		
					$cudata['status'] = '1';
					$this->obj->insert_user_contact_trans_record($cudata);
				}
				
			}
			
			$allemailtype = $this->input->post('slt_email_type');
			$allemailaddress = $this->input->post('txt_email_address');
			$defaultemail = $this->input->post('rad_email_default');
			
			if(!empty($allemailtype) && count($allemailtype) > 0)
			{
				for($i=0;$i<count($allemailtype);$i++)
				{
					if(trim($allemailaddress[$i]) != "")
					{
						$cmdata['contact_id'] = $contact_id;
						$cmdata['email_type'] = $allemailtype[$i];
						//$cmdata['email_address'] = $allemailaddress[$i];
						$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
						if (preg_match($regex, $allemailaddress[$i])) 
						{
							$cmdata['email_address'] = strtolower($allemailaddress[$i]);
						}
						
						if($defaultemail == $allemailaddress[$i])
							$cmdata['is_default'] = '1';
						else
							$cmdata['is_default'] = '0';
						$cmdata['status'] = '1';
						
						$this->obj->insert_email_trans_record($cmdata);
						
						unset($cmdata);
					}
				}
			}
			
			
			$allemailtypeid = $this->input->post('email_type_trans_id');
			$allemailtypee = $this->input->post('slt_email_typee');
			$allemailaddresse = $this->input->post('txt_email_addresse');
			
			if(!empty($allemailtypeid) && count($allemailtypeid) > 0)
			{
				for($i=0;$i<count($allemailtypeid);$i++)
				{
					if(trim($allemailaddresse[$i]) != "")
					{
						$cmdata['id'] = $allemailtypeid[$i];
						$cmdata['email_type'] = $allemailtypee[$i];
						//$cmdata['email_address'] = $allemailaddresse[$i];
						$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
						if (preg_match($regex, $allemailaddresse[$i])) 
						{
							$cmdata['email_address'] = strtolower($allemailaddresse[$i]);
						}
						if($defaultemail == $allemailaddresse[$i])
							$cmdata['is_default'] = '1';
						else
							$cmdata['is_default'] = '0';
						
						$this->obj->update_email_trans_record($cmdata);
						
						unset($cmdata);
					}
				}
			}
			
			
			$allphonetype = $this->input->post('slt_phone_type');
			$allphoneno = $this->input->post('txt_phone_no');
			$defaultphone = $this->input->post('rad_phone_default');
			
			if(!empty($allphonetype) && count($allphonetype) > 0)
			{
				for($i=0;$i<count($allphonetype);$i++)
				{
					if(trim($allphoneno[$i]) != "")
					{
						$cpdata['contact_id'] = $contact_id;
						$cpdata['phone_type'] = $allphonetype[$i];
						$cpdata['phone_no'] = $allphoneno[$i];
						if($defaultphone == $allphoneno[$i])
							$cpdata['is_default'] = '1';
						else
							$cpdata['is_default'] = '0';
						
						$this->obj->insert_phone_trans_record($cpdata);
						
						unset($cpdata);
					}
				}
			}
			
			
			$allphonetypeid = $this->input->post('phone_type_trans_id');
			$allphonetypee = $this->input->post('slt_phone_typee');
			$allphonenoe = $this->input->post('txt_phone_noe');
			
			if(!empty($allphonetypeid) && count($allphonetypeid) > 0)
			{
				for($i=0;$i<count($allphonetypeid);$i++)
				{
					if(trim($allphonenoe[$i]) != "")
					{
						$cpdata['id'] = $allphonetypeid[$i];
						$cpdata['phone_type'] = $allphonetypee[$i];
						$cpdata['phone_no'] = $allphonenoe[$i];
						if($defaultphone == $allphonenoe[$i])
							$cpdata['is_default'] = '1';
						else
							$cpdata['is_default'] = '0';
						
						$this->obj->update_phone_trans_record($cpdata);
						
						unset($cpdata);
					}
				}
			}
			
			
			$alladdresstype = $this->input->post('slt_address_type');
			$alladdressline1 = $this->input->post('txtarea_address_line1');
			$alladdressline2 = $this->input->post('txtarea_address_line2');
			$alladdresscity = $this->input->post('txt_city');
			$alladdressstate = $this->input->post('txt_state');
			$alladdresszip = $this->input->post('txt_zip_code');
			$alladdresscountry = $this->input->post('txt_country');
			
			if(!empty($alladdresstype) && count($alladdresstype) > 0)
			{
				for($i=0;$i<count($alladdresstype);$i++)
				{
					if(trim($alladdresstype[$i]) != "" || trim($alladdressline1[$i]) != "" || trim($alladdressline2[$i]) != "" || trim($alladdresscity[$i]) != "" || trim($alladdressstate[$i]) != "" || trim($alladdresszip[$i]) != "" || trim($alladdresscountry[$i]) != "")
					{
						$cadata['contact_id'] = $contact_id;
						$cadata['address_type'] = $alladdresstype[$i];
						$cadata['address_line1'] = $alladdressline1[$i];
						$cadata['address_line2'] = $alladdressline2[$i];
						$cadata['city'] = $alladdresscity[$i];
						$cadata['state'] = $alladdressstate[$i];
						$cadata['zip_code'] = $alladdresszip[$i];
						$cadata['country'] = $alladdresscountry[$i];
						$cadata['status'] = '1';
						
						$this->obj->insert_address_trans_record($cadata);
						
						unset($cadata);
					}
				}
			}
			
			
			$alladdresstypeid = $this->input->post('address_type_trans_id');
			$alladdresstypee = $this->input->post('slt_address_typee');
			$alladdressline1e = $this->input->post('txtarea_address_line1e');
			$alladdressline2e = $this->input->post('txtarea_address_line2e');
			$alladdresscitye = $this->input->post('txt_citye');
			$alladdressstatee = $this->input->post('txt_statee');
			$alladdresszipe = $this->input->post('txt_zip_codee');
			$alladdresscountrye = $this->input->post('txt_countrye');
			
			if(!empty($alladdresstypeid) && count($alladdresstypeid) > 0)
			{
				for($i=0;$i<count($alladdresstypeid);$i++)
				{
					if(trim($alladdresstypee[$i]) != "" || trim($alladdressline1e[$i]) != "" || trim($alladdressline2e[$i]) != "" || trim($alladdresscitye[$i]) != "" || trim($alladdressstatee[$i]) != "" || trim($alladdresszipe[$i]) != "" || trim($alladdresscountrye[$i]) != "")
					{
						$cadata['id'] = $alladdresstypeid[$i];
						$cadata['address_type'] = $alladdresstypee[$i];
						$cadata['address_line1'] = $alladdressline1e[$i];
						$cadata['address_line2'] = $alladdressline2e[$i];
						$cadata['city'] = $alladdresscitye[$i];
						$cadata['state'] = $alladdressstatee[$i];
						$cadata['zip_code'] = $alladdresszipe[$i];
						$cadata['country'] = $alladdresscountrye[$i];
						
						$this->obj->update_address_trans_record($cadata);
						
						unset($cadata);
					}
				}
			}
			
			
			$allwebsitetype = $this->input->post('txt_website_type');
			$allwebsitename = $this->input->post('txt_website_name');
			
			if(!empty($allwebsitetype) && count($allwebsitetype) > 0)
			{
				for($i=0;$i<count($allwebsitetype);$i++)
				{
					if(trim($allwebsitename[$i]) != "")
					{
						$cwdata['contact_id'] = $contact_id;
						$cwdata['website_type'] = $allwebsitetype[$i];
						$cwdata['website_name'] = $allwebsitename[$i];
						$cwdata['status'] = '1';
						
						$this->obj->insert_website_trans_record($cwdata);
						
						unset($cwdata);
					}
				}
			}
			
			
			$allwebsitetypeid = $this->input->post('txt_website_typeid');
			$allwebsitetypee = $this->input->post('txt_website_typee');
			$allwebsitenamee = $this->input->post('txt_website_namee');
			
			if(!empty($allwebsitetypee) && count($allwebsitetypee) > 0)
			{
				for($i=0;$i<count($allwebsitetypee);$i++)
				{
					if(trim($allwebsitetypeid[$i]) != "")
					{
						$cwdata['id'] = $allwebsitetypeid[$i];
						$cwdata['website_type'] = $allwebsitetypee[$i];
						$cwdata['website_name'] = $allwebsitenamee[$i];
						$cwdata['status'] = '1';
						
						$this->obj->update_website_trans_record($cwdata);
						
						unset($cwdata);
					}
				}
			}
			
			
			$allsocialtype = $this->input->post('slt_profile_type');
			$allsocialname = $this->input->post('txt_social_profile');
			
			if(!empty($allsocialtype) && count($allsocialtype) > 0)
			{
				for($i=0;$i<count($allsocialtype);$i++)
				{
					if(trim($allsocialname[$i]) != "")
					{
						$csdata['contact_id'] = $contact_id;
						$csdata['profile_type'] = $allsocialtype[$i];
						if($csdata['profile_type'] == 2)
						{									  
							$s_name = explode("twitter.com/",$allsocialname[$i]);
							//pr($s_name);
							$csdata['website_name'] = end($s_name);
							//pr($csdata['website_name']);
						}
						else
							$csdata['website_name'] = $allsocialname[$i];
							
						//$csdata['website_name'] = $allsocialname[$i];
						$csdata['status'] = '1';
						
						$this->obj->insert_social_trans_record($csdata);
						
						unset($csdata);
					}
				}
			}
			
			
			$allsocialtypeid = $this->input->post('slt_profile_typeid');
			$allsocialtypee = $this->input->post('slt_profile_typee');
			$allsocialnamee = $this->input->post('txt_social_profilee');
			
			if(!empty($allsocialtypee) && count($allsocialtypee) > 0)
			{
				for($i=0;$i<count($allsocialtypee);$i++)
				{
					if(trim($allsocialtypeid[$i]) != "")
					{
						$csdata['id'] = $allsocialtypeid[$i];
						$csdata['profile_type'] = $allsocialtypee[$i];
						
						if($csdata['profile_type'] == 2)
						{
							$s_name = explode("twitter.com/",$allsocialnamee[$i]);
							//pr($s_name);
							$csdata['website_name'] = end($s_name);
							//pr($csdata['website_name']);
						}
						else
							$csdata['website_name'] = $allsocialnamee[$i];
						$csdata['status'] = '1';
						
						$this->obj->update_social_trans_record($csdata);
						
						unset($csdata);
					}
				}
			}
			
			$this->obj->delete_contact_type_record($contact_id);
			$allcontact_types = $this->input->post('chk_contact_type_id');
			
			if(!empty($allcontact_types) && count($allcontact_types) > 0)
			{
				foreach($allcontact_types as $row)
				{
					$ctdata['contact_id'] = $contact_id;
					$ctdata['contact_type_id'] = $row;
					
					$this->obj->insert_contact_type_record($ctdata);
					unset($ctdata);
				}
			}
			
			
			/*$alltags = $this->input->post('txt_tag');
			
			if(!empty($alltags) && count($alltags) > 0)
			{
				for($i=0;$i<count($alltags);$i++)
				{
					if(trim($alltags[$i]) != "")
					{
						$ctdata['contact_id'] = $contact_id;
						$ctdata['tag'] = $alltags[$i];
						
						$this->obj->insert_tag_record($ctdata);
						
						unset($ctdata);
					}
				}
			}
			
			
			$alltagsid = $this->input->post('tag_type_trans_id');
			$alltagse = $this->input->post('txt_tage');
			
			if(!empty($alltagsid) && count($alltagsid) > 0)
			{
				for($i=0;$i<count($alltagsid);$i++)
				{
					if(trim($alltagse[$i]) != "")
					{
						$ctdata['id'] = $alltagsid[$i];
						$ctdata['tag'] = $alltagse[$i];
						
						$this->obj->update_tag_record($ctdata);
						
						unset($ctdata);
					}
				}
			}*/
			
			$alltags = $this->input->post('txt_tag');
			if(!empty($alltags))
			{
				$alltag = array();
				$alltags = explode(',',$this->input->post('txt_tag'));
				//pr($alltags);
				for($i=0;$i<count($alltags);$i++)
				{
					if(!stristr($alltags[$i],'NEWTAG-'))
					{
						$oldtag = $this->obj->select_tag_record('',$alltags[$i]);
						if(!empty($oldtag) && count($oldtag) > 0 && $oldtag[0]['contact_id'] == $contact_id)
							$alltag[] = $oldtag[0]['id'];
						elseif(!empty($oldtag) && count($oldtag) > 0)
						{
							$ctdata['contact_id'] = $contact_id;
							$ctdata['tag'] = $oldtag[0]['tag'];
							$ctdata['is_default'] = $oldtag[0]['is_default'];
							$lastId = $this->obj->insert_tag_record($ctdata);
							$alltag[] = $lastId;
							unset($ctdata);
						}
						//$j++;
					}
					else
					{
						$explode = explode('NEWTAG-',$alltags[$i]);
						if(!empty($explode[1]))
							$explodetag = explode('{^}',$explode[1]);
						if(!empty($explodetag[1]))
						{
							$ctdata['contact_id'] = $contact_id;
							$ctdata['is_default'] = '2';
							$ctdata['tag'] = $explodetag[1];
							$lastId = $this->obj->insert_tag_record($ctdata);	
							$alltag[] = $lastId;
							unset($ctdata);
						}
					}
				}
				//pr($alltag);exit;
				$this->obj->delete_not_in_tag_trans_record($alltag,$contact_id);
			}
			else
				$this->obj->delete_tag_trans_record('',$contact_id);
				
			$allcommunicationplans = $this->input->post('slt_communication_plan_id');
			
			$oldcommunicationplans = $this->obj->select_communication_trans_record($contact_id);
			
			$oldcommunicationplanslist = array();
			
			if(count($oldcommunicationplans) > 0)
			{
				foreach($oldcommunicationplans as $row)
				{
					$oldcommunicationplanslist[] = $row['interaction_plan_id'];
				}
			}
			
			//pr($allcommunicationplans);
			
			//pr($oldcommunicationplanslist);
			
			$oldplansarr = array_diff($oldcommunicationplanslist,$allcommunicationplans);
			$newplansarr = array_diff($allcommunicationplans,$oldcommunicationplanslist);
			
			if(isset($allcommunicationplans) && empty($allcommunicationplans))
				$oldplansarr = $oldcommunicationplanslist;
			//pr($oldplansarr);
			
			//pr($newplansarr);
			
			//exit;
			
			////////////////////////////////// Delete Interaction Plan Contacts Transaction Data ///////////////////////////////////////////
			
			if(!empty($oldplansarr))
			{
				foreach($oldplansarr as $rowdata)
				{
					$this->interaction_plans_model->delete_contact_trans_record_indi($rowdata,$contact_id);
					
					////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
					
					$this->interaction_plans_model->delete_contact_communication_plan_trans_record_indi($rowdata,$contact_id);
					
					//////////////////////////////////////////////////////////////////////////////////
					
					/* Delete SMS and Email Campaign data */
					$match = array('interaction_plan_id'=>$rowdata);
					$interaction = $this->interaction_model->select_records('',$match,'','=');
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
					//foreach($deletecontactdata as $deletecon)
					//{
						
						$deletecon = $contact_id;
						
						if(!empty($deletecon))
						{
							
							$match1 = array('id'=>$rowdata);
							$plandata1 = $this->interaction_plans_model->select_records('',$match1,'','=');
							
							$data_conv['contact_id'] = $deletecon;
							$data_conv['plan_id'] = $rowdata;
							$data_conv['plan_name'] = !empty($plandata1[0]['plan_name'])?$plandata1[0]['plan_name']:'';
							$data_conv['created_date'] = date('Y-m-d H:i:s');
							$data_conv['log_type'] = '10';
							$data_conv['created_by'] = $this->user_session['id'];
							$data_conv['status'] = '1';
							$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
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
						
					//}
				}
			}
			
			//echo "here";exit;
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			////////////////////////////////// Add Interaction Plan Contacts Transaction Data ///////////////////////////////////////////
			
			if(!empty($newplansarr) && count($newplansarr) > 0)
			{
				foreach($newplansarr as $interaction_plan_id)
				{
					if($interaction_plan_id != '')
					{
						$match1 = array('id'=>$interaction_plan_id);
						$plandata = $this->interaction_plans_model->select_records('',$match1,'','=');
						
						//pr($plandata);
						
						$cdata = $plandata[0];
						
						//pr($cdata);exit;
						
						if(!empty($cdata))
						{
							//echo $cdata['plan_name'];
							$data_conv['contact_id'] = $contact_id;
							$data_conv['plan_id'] = $interaction_plan_id;
							$data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
							$data_conv['created_date'] = date('Y-m-d H:i:s');
							$data_conv['log_type'] = '2';
							$data_conv['created_by'] = $this->user_session['id'];
							$data_conv['status'] = '1';
							
							//pr($data_conv);exit;
							
							$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
							
							$icdata['interaction_plan_id'] = $interaction_plan_id;
							$icdata['contact_id'] = $contact_id;
							
							if($cdata['plan_start_type'] == '1')
							{
								$icdata['start_date'] = date('Y-m-d');
								$icdata['plan_start_type'] = $cdata['plan_start_type'];
								$icdata['plan_start_date'] = $cdata['start_date'];
							}
							else
							{
								if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
									$icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
								else
									$icdata['start_date'] = date('Y-m-d');
								
								$icdata['plan_start_type'] = $cdata['plan_start_type'];
								$icdata['plan_start_date'] = $cdata['start_date'];
							}
							
							$icdata['created_date'] = date('Y-m-d H:i:s');
							$icdata['created_by'] = $this->user_session['id'];
							$icdata['status'] = '1';
							
							//pr($icdata);exit;
							
							$this->interaction_plans_model->insert_contact_trans_record($icdata);
							
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
							
							$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
							
							
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
									$iccdata['contact_id'] = $contact_id;
									$iccdata['interaction_plan_interaction_id'] = $row1['id'];
									$iccdata['interaction_type'] = $row1['interaction_type'];
									
									if($row1['start_type'] == '1')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										
										///////////////////////////////////////////////////////////////
										
										$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
										$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
										
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
										
										$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id);
										
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
									$iccdata['created_by'] = $this->user_session['id'];
									
									$this->interaction_model->insert_contact_communication_record($iccdata);
									
									unset($iccdata);
									unset($user_work_off_days1);
									unset($special_days1);
									unset($leave_days1);
									
									/* Email campaign/SMS campaign Insert */
									
									/*$match = array('id'=>$contact_id);
									$userdata = $this->contacts_model->select_records('',$match,'','=');*/
									$table = "contact_master as cm";
									$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
									$where = array('cm.id'=>$contact_id);
									$join_tables = array(
														'contact_address_trans as cat'=>'cat.contact_id = cm.id',
													);
									$group_by='cm.id';
									$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,$where);
								
									$agent_name = '';
									if(!empty($row1['assign_to']))
									{
										$table ="login_master as lm";   
										$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
										$join_tables = array('user_master as um'=>'lm.user_id = um.id');
										$wherestring = 'lm.id = '.$row1['assign_to'];
										$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
										if(!empty($agent_datalist))
										{
											if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
												$agent_name = $agent_datalist[0]['admin_name'];
											else
												$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
										}
									}
									
									if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
									{	
										//$row1['id'];
										$match = array('interaction_id'=>$row1['id']);
										$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=');
										if(count($campaigndata) > 0)
										{
											$cdata1['email_campaign_id'] = $campaigndata[0]['id'];
											$cdata1['contact_id'] = $contact_id;
											$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
														'Agent Name'=>$agent_name,
														'Contact First Name'=>$userdata['first_name'],
														'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
														'Contact Last Name'=>$userdata['last_name'],
														'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
														'Contact Company Name'=>$userdata['company_name']
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
											$cdata1['contact_id'] = $contact_id;
											$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
														'Agent Name'=>$agent_name,
														'Contact First Name'=>$userdata['first_name'],
														'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
														'Contact Last Name'=>$userdata['last_name'],
														'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
														'Contact Company Name'=>$userdata['company_name']
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
									unset($cdata1);
									/* END */
									
								}
							}
							
							///////////////////////////////////////////////////////////////////////////
							unset($icdata);
							//exit;
							
						
						}
						
					}
				}
			}
			
			//exit;
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
			
		}
		elseif($contacttab == 2)
		{
			
			 /*?>$image = $this->input->post('hiddenFile');
			$oldcontactimg = $this->input->post('contact_pic');//new add
			$bgImgPath = $this->config->item('contact_big_img_path');
			$smallImgPath = $this->config->item('contact_small_img_path');
			if(!empty($_FILES['contact_pic']['name']))
			{  
				$uploadFile = 'contact_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
				$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
			}
			$this->obj->update_record($cdata);
			unset($cdata);<?php */
			
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
			
			
		}
		elseif($contacttab == 3)
		{
			$cdata['birth_date'] = date('Y-m-d',strtotime($this->input->post('txt_birth_date')));
			$cdata['anniversary_date'] = date('Y-m-d',strtotime($this->input->post('txt_anniversary_date')));
			$cdata['modified_by'] = $this->user_session['id'];
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			
			$this->obj->update_record($cdata);
			
			unset($cdata);
			
			$allfieldtype = $this->input->post('slt_field_type');
			$allfieldname = $this->input->post('txt_field_name');
			if(!empty($allfieldtype) && count($allfieldtype) > 0)
			{
				for($i=0;$i<count($allfieldtype);$i++)
				{
					if(trim($allfieldname[$i]) != "")
					{
						$cmdata1['contact_id'] = $contact_id;
						$cmdata1['field_type'] = $allfieldtype[$i];
						$cmdata1['field_name'] = $allfieldname[$i];
						$cmdata1['status'] = '1';
						$this->obj->insert_field_trans_record($cmdata1);
						
						unset($cmdata1);
					}
				}
			}
			
			
			$allfieldtypeid = $this->input->post('field_type_trans_id');
			$allfieldtypee = $this->input->post('slt_field_typee');
			$allfieldnamee = $this->input->post('txt_field_namee');
			if(!empty($allfieldtypeid) && count($allfieldtypeid) > 0)
			{
				for($i=0;$i<count($allfieldtypeid);$i++)
				{
					if(trim($allfieldnamee[$i]) != "")
					{
						$cmdata1['id'] = $allfieldtypeid[$i];
						$cmdata1['field_type'] = $allfieldtypee[$i];
						$cmdata1['field_name'] = $allfieldnamee[$i];
						$this->obj->update_field_tran_record($cmdata1);
						
						unset($cmdata1);
					}
				}
			}


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

		}
		elseif($contacttab == 4)
		{
                        $cdata['price_range_from'] = str_replace(',', '', $this->input->post('txt_price_range_from'));
                        $cdata['price_range_to'] = str_replace(',', '', $this->input->post('txt_price_range_to'));
			//$cdata['price_range_from'] = $this->input->post('txt_price_range_from');
			//$cdata['price_range_to'] = $this->input->post('txt_price_range_to');
                        $cdata['min_area'] = $this->input->post('txt_min_area');
			$cdata['max_area'] = $this->input->post('txt_max_area');
			$cdata['house_style'] = $this->input->post('txt_house_style');
			$cdata['area_of_interest'] = $this->input->post('txt_area_of_interest');
			$cdata['square_footage'] = $this->input->post('txt_square_footage');
			$cdata['no_of_bedrooms'] = $this->input->post('txt_no_of_bedrooms');
			$cdata['no_of_bathrooms'] = $this->input->post('txt_no_of_bathrooms');
			$cdata['buyer_preferences_notes'] = $this->input->post('textarea_buyer_preferences_notes');
			
			$cdata['modified_by'] = $this->user_session['id'];
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_record($cdata);
			
			unset($cdata);
		}
		
		$redirecttype = $this->input->post('submitbtn');
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		if($submitvaltab2 == '3')
		{
				redirect('user/'.$this->viewName.'/edit_record/'.$contact_id.'/'.($submitvaltab2-1).'#documenets');
		}
		if($redirecttype == 'Save Contact' || $contacttab == 4)
		{
			$email_id = $this->input->post('id');
			$searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
			redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
		}	
		else
		{
			redirect('user/'.$this->viewName.'/edit_record/'.$contact_id.'/'.($contacttab+1));
		}
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
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
		
//		$image = $this->input->post('hiddenFile');
//		
//		if(!empty($image))
//		{	
//			$bgImgPath = $this->config->item('contact_big_img_path');
//			$smallImgPath = $this->config->item('contact_small_img_path');
//			$this->imageupload_model->copyImage($bgImgPath,$smallImgPath,$image);
//			$cdata['contact_pic']	= $image;
//			
//			$bgTempPath = $this->config->item('upload_image_file_path').'temp/big/';
//			$smallTempPath = $this->config->item('upload_image_file_path').'temp/small/';
//			if(file_exists($bgTempPath.$image))
//			{ 
//				@unlink($bgTempPath.$image);
//				@unlink($smallTempPath.$image);
//			}
//		}
//		$this->obj->update_record($cdata);
//		unset($cdata);
		
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
    /*function delete_record()
    {
        $id = $this->uri->segment(4);
		$table = "contact_master as cm";
		$fields = array('cm.*');
		
		$join_tables = array(
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
						);
		$group_by='cm.id';
		
			$wherestring='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].') AND cm.id = '.$id.'';
			$data['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','',$group_by,$wherestring);
			
		if(empty($data['editRecord']))
		{
			$msg = $this->lang->line('common_delete_right_msg');
        	$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);
		}
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }*/
	
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
		$contact_id = $this->input->post('id1');
        $this->obj->delete_email_trans_record($id);
		//echo $this->db->last_query();
		$count=$this->obj->select_email_trans_record($contact_id);
		echo count($count);
		
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

	function delete_field_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_field_trans_record($id);
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
		$cdata['status'] = 0;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
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
		$cdata['status'] = 1;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
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
    @Description: Function to view merge page
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 11-07-2014
    */
	function merge_duplicate_contacts()
	{
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/merge_contact_home";
		$this->load->view('user/include/template',$data);
	}
	
	/*
		@Description: Function to create the session selected contacts or search contacts
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: - 
		@Date: 19-09-2014
    */
	
	public function export_contact()
	{
		$new_data['array_data'] = $this->input->post('myarray');
		$new_data['searchtext'] = $this->input->post('searchtext');
		$this->session->set_userdata('export_contact',$new_data);
	}
	
	 /*
    @Description: Function for Export CSV 
    @Author: Kaushik Valiya
    @Input: - 
    @Output: -Contact Master table in All Recored Export CSV File
    @Date: 11-07-2014
    */
	public function export()
    {    
		$user_data = $this->session->userdata('export_contact');
		$searchtext = '';
		if(!empty($user_data))
		{
			$where_in = !empty($user_data['array_data'])?$user_data['array_data']:'';
			if(empty($where_in))
				$searchtext = $user_data['searchtext'];
		}
		$this->session->unset_userdata('export_contact');
//		$contents = "Name,Company Name,Phone,Email,Contact Status,Contact Address,Contact Type\n";
		$contents = "First Name,Middle Name,Last Name,Company Name,Title,Phone,Email,Contact Status,Contact Address,Contact Type,Notes,Website,Social Profile Website,Date Of Birth,Date Of Anniversary,Tag\n";
		$data['sortfield']		= 'cm.first_name';
	//	$data['sortby']			= 'desc';
		$table = "contact_master as cm";
		$fields = array('cm.id','cm.*','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','group_concat(DISTINCT cpt.phone_no ORDER BY cpt.phone_no separator \',\') as phone_no','group_concat(DISTINCT cet.email_address ORDER BY cet.email_address separator \',\') as email_address','group_concat(DISTINCT CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country)) as full_address','group_concat(DISTINCT cst.website_name ORDER BY cst.website_name separator \',\') as social_profile','group_concat(DISTINCT cwt.website_name ORDER BY cwt.website_name separator \',\') as website','ctat.tag');
	$join_tables = array(
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id',
							'contact_social_trans as cst'=>'cst.contact_id = cm.id',
							'contact_website_trans as cwt'=>'cwt.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id'
						);
		$group_by='cm.id';
		//$match=array('uct.user_id'=>$this->user_session['user_id']);
		$match = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'cet.email_address'=>$searchtext,'cpt.phone_no'=>$searchtext,'ctat.tag'=>$searchtext,'csm.name'=>$searchtext,'ctm.name'=>$searchtext,'cat.address_line1'=>$searchtext,'cat.address_line2'=>$searchtext,'cat.city'=>$searchtext,'cat.state'=>$searchtext,'cat.zip_code'=>$searchtext,'cat.country'=>$searchtext);
		$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		if(!empty($where_in))
			$where_in = array('cm.id'=>$where_in);
		else
			$where_in = '';
		$res =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$data['sortfield'],'',$group_by,$wherestring,$where_in);
		$output='';
		$data1='';
		$data2='';
		foreach($res as $row)
        {
			if($row['birth_date']!='0000-00-00' && $row['birth_date']!='1970-01-01')
			{
				$data1 = date('m/d/Y',strtotime($row['birth_date']));
			}
			else
			{
				$data1='';
			}
			
			if($row['anniversary_date']!='0000-00-00' && $row['anniversary_date']!='1970-01-01')
			{
				$data2=date('m/d/Y',strtotime($row['anniversary_date']));
			}
			else
			{
				$data2='';
			}
			if(!empty($row['full_address'])){
							
				$address=str_replace(', ',',',$row['full_address']);
				$letters = array(',,,,,',',,,,',',,,',',,');
				$fruit   = array(',',',',',',',');
				$text    = $address;
				$output  = str_replace($letters, $fruit, $text);
				$output = ltrim($output,",");
				$output = rtrim($output,",");
				//echo $output;
			}	
			//$contents .= '"'.$row['contact_name'].'"'.",";
			$contents .= '"'.$row['first_name'].'"'.",";
			$contents .= '"'.$row['middle_name'].'"'.",";
			$contents .= '"'.$row['last_name'].'"'.",";
            $contents .= '"'.$row['company_name'].'"'.",";
			$contents .= '"'.$row['company_post'].'"'.",";
            $contents .= '"'.$row['phone_no'].'"'.",";
 			$contents .= '"'.$row["email_address"].'"'.",";
            $contents .= '"'.$row['contact_status'].'"'.",";
            $contents .= '"'.$output.'"'.",";
            $contents .= '"'.$row['contact_type'].'"'.",";
			$contents .= '"'.$row['notes'].'"'.",";
			$contents .= '"'.$row['website'].'"'.",";
			$contents .= '"'.$row['social_profile'].'"'.",";
            $contents .= '"'.$data1.'"'.",";
			$contents .= '"'.$data2.'"'."\r\n";
			unset($data1);
			unset($data2);
			unset($output);
		}
        $filename = "myFile.csv";
       	header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
		echo $contents;
		exit;
    }
	/*
    @Description: Function to view Import CSV File Add
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 11-07-2014
    */
	function import()
	{
		//check user right
		check_rights('import_contacts');
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/import";
		$this->load->view('user/include/template',$data);
	}
	
	/*
    @Description: Function to view Import CSV File Add
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 11-07-2014
    */
	function insert_contact_csv()
	{
		//check user right
		check_rights('import_contacts');
		$data['csv_file']=$this->input->post('hiddenFiledoc');
		
		if(!empty($data['csv_file']))
		{
		$data['created_by']=$this->user_session['id'];
		$data['created_date']= date('Y-m-d H:i:s');
		$data['status']= '1';
		$array=$this->session->all_userdata();
		$array_string=mysql_escape_string(serialize($array));// ->who(user in PC) and Fully Details for upload CSV file. $array= unserialize($rs['column']);print_r($array);
		$data['additional_information']=$array_string;
		//pr($data);exit;
		$csv_id= $this->obj->insert_contact_csv($data);	
		$file_path = $this->config->item('contact_documents_big_csv_path').$data['csv_file'];
		$file_handle = fopen($file_path, "r");
		while(!feof($file_handle)) 
		{
			$dropdown_value[] = fgetcsv($file_handle, 5000);
		}
		$data['csv_id'] = $csv_id;
		if(!empty($dropdown_value[0]) && count($dropdown_value[0]) >0)
		{
			for($i=0;$i<count($dropdown_value[0]);$i++)
			{
				$data['dropdown_data'][] = array('id'=>($i+1),'field'=>$dropdown_value[0][$i]);
			}
		}
		$match = array("status"=>'1');
		$data['contact_mapping_list']= $this->obj1->select_records1('',$match,'','=','','','','','','contact__csv_mapping_master');

		$msg = $this->lang->line('common_import_success_msg');
        $data['msg'] = $msg;
		$newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		
		//echo $data['msg'];
		$data['main_content'] = $this->user_type.'/'.$this->viewName."/add_contact";
		$this->load->view('user/include/template',$data);
		}
		else
		{
			redirect('user/'.$this->viewName);
		}
	}
	/*
    @Description: Function to Insert Contact
	@Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 12-07-2014
    */
	function insert_contact()
	{
		//check user right
		check_rights('import_contacts');
		
	?>
        
	<style>
        body{ position:relative;}
    </style>
    <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src="<?=base_url('images/loading.gif')?>" /><br />Please wait... It will take some time to import contacts...</div>
   <?php
		$data_mapping['name']=trim($this->input->post('save_mapping'));
		$csv_mapping_id = '';
		if(!empty($data_mapping['name']))
		{
			$data_mapping['created_by']=$this->user_session['id'];
			$data_mapping['created_date']= date('Y-m-d H:i:s');
			$data_mapping['status']= '1';
			$csv_mapping_id=$this->obj->insert_contact_mapping_record($data_mapping);	
		}
		$id=$this->input->post('csv_id');
		$result= $this->obj->select_csv_record($id);
		if(!empty($result))
		{
			$count_no_contact=0;
			$file_path=$this->config->item('contact_documents_big_csv_path').$result[0]['csv_file'];
			$file_handle = fopen($file_path, "r");
			$i=0;
			while(!feof($file_handle)) 
			{
				$line_of_text[] = fgetcsv($file_handle, 100000);
			}
			$flag =0;// check for Invalid Email Id of Not 
			for($i=0;$i<count($line_of_text); $i++)
			{	
				//echo $line_of_text[$i][($this->input->post('slt_prefix')-1)];
				if($i==0){
					
					if(!empty($csv_mapping_id))
					{
					$template['csv_mapping_id'] = $csv_mapping_id;
					if($this->input->post('slt_prefix') != '')
					{
						$template['contact_master_field'] = 'slt_prefix';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_prefix')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					
					if($this->input->post('slt_fname') != '')
					{
						$template['contact_master_field'] = 'slt_fname';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_fname')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_mname') != '')
					{
						$template['contact_master_field'] = 'slt_mname';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_mname')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_lname') != '')
					{
						$template['contact_master_field'] = 'slt_lname';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_lname')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_company') != '')
					{
						$template['contact_master_field'] = 'slt_company';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_company')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_address1') != '')
					{
						$template['contact_master_field'] = 'slt_address1';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_address1')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}	
					if($this->input->post('slt_address2') != '')
					{
						$template['contact_master_field'] = 'slt_address2';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_address2')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_city') != '')
					{
						$template['contact_master_field'] = 'slt_city';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_city')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_state') != '')
					{
						$template['contact_master_field'] = 'slt_state';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_state')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_contact_source') != '')
					{
						$template['contact_master_field'] = 'slt_contact_source';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_contact_source')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}	
					if($this->input->post('slt_contact_type') != '')
					{
						$template['contact_master_field'] = 'slt_contact_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_contact_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_contact_lead') != '')
					{
						$template['contact_master_field'] = 'slt_contact_lead';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_contact_lead')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}	
					if($this->input->post('slt_default_email') != '')
					{
						$template['contact_master_field'] = 'slt_default_email';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_default_email')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}	
					if($this->input->post('slt_email2') != '')
					{
						$template['contact_master_field'] = 'slt_email2';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email2')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email3') != '')
					{
						$template['contact_master_field'] = 'slt_email3';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email3')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email4') != '')
					{
						$template['contact_master_field'] = 'slt_email4';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email4')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email5') != '')
					{
						$template['contact_master_field'] = 'slt_email5';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email5')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_default_phone') != '')
					{
						$template['contact_master_field'] = 'slt_default_phone';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_default_phone')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_phone2') != '')
					{
						$template['contact_master_field'] = 'slt_phone2';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_phone2')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_phone3') != '')
					{
						$template['contact_master_field'] = 'slt_phone3';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_phone3')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email_type') != '')
					{
						$template['contact_master_field'] = 'slt_email_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email_type_2') != '')
					{
						$template['contact_master_field'] = 'slt_email_type_2';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email_type_2')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email_type_3') != '')
					{
						$template['contact_master_field'] = 'slt_email_type_3';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email_type_3')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email_type_4') != '')
					{
						$template['contact_master_field'] = 'slt_email_type_4';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email_type_4')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_email_type_5') != '')
					{
						$template['contact_master_field'] = 'slt_email_type_5';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_email_type_5')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_phone_type') != '')
					{
						$template['contact_master_field'] = 'slt_phone_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_phone_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_phone2_type') != '')
					{
						$template['contact_master_field'] = 'slt_phone2_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_phone2_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_phone3_type') != '')
					{
						$template['contact_master_field'] = 'slt_phone3_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_phone3_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
					if($this->input->post('slt_address_type') != '')
					{
						$template['contact_master_field'] = 'slt_address_type';
						$template['csv_field'] = $line_of_text[$i][($this->input->post('slt_address_type')-1)];
						$this->obj->insert_contact_mapping_trans_record($template);
					}
				}
				}
				else{
					$data['created_by']=$this->user_session['id'];
					if($this->input->post('slt_prefix') != '')
						$data['prefix'] = $line_of_text[$i][($this->input->post('slt_prefix')-1)];
					else
					 	$data['prefix'] = '';
					if($this->input->post('slt_fname') != '')
						$data['first_name'] = $line_of_text[$i][($this->input->post('slt_fname')-1)];
					if($this->input->post('slt_mname') != '')
						$data['middle_name'] = $line_of_text[$i][($this->input->post('slt_mname')-1)];
					if($this->input->post('slt_lname') != '')	
						$data['last_name'] = $line_of_text[$i][($this->input->post('slt_lname')-1)];
					if($this->input->post('slt_company') != '')	
						$data['company_name'] = $line_of_text[$i][($this->input->post('slt_company')-1)];
					if($this->input->post('slt_contact_lead') != '')	
						$data['is_lead'] = $line_of_text[$i][($this->input->post('slt_contact_lead')-1)];
					if($this->input->post('slt_contact_source') != '')	
						$c_source=$line_of_text[$i][($this->input->post('slt_contact_source')-1)];
						
					if(!empty($c_source))
					{
						$match = array("name"=>$c_source);
						$result= $this->obj1->select_records1('',$match,'','=','','','','','', 'contact__source_master');
						if(!empty($result[0]['id']))
						{
							$data['contact_source'] = $result[0]['id'];
						}
					}
					if(!empty($data['first_name']))	
					{	
						$data['created_by']   = $this->user_session['id'];
						$data['created_type'] = '2';
						$data['created_date'] = date('Y-m-d H:i:s');
						$data['is_subscribe'] = '0';
						$first_name=$data['first_name'];
						$data['csv_id'] = $id;
						$contact_id=$this->obj->insert_record($data);
						unset($data);
						$count_no_contact++;
						
						$data123['contact_id'] = $contact_id;
						//$temp_date=date('Ymd');
						//$data123['tag'] = $first_name." Import ".$temp_date;
						$temp_date=date('m/d/Y H:i:s');
						$data123['tag'] = "Import ".$temp_date;
						$this->obj->insert_tag_record($data123);
					}
					if(!empty($contact_id))
					{				
						if($this->input->post('slt_address1') != '')	
							$address['address_line1'] = $line_of_text[$i][($this->input->post('slt_address1')-1)];
						if($this->input->post('slt_address2') != '')	
							$address['address_line2'] = $line_of_text[$i][($this->input->post('slt_address2')-1)];
						if($this->input->post('slt_city') != '')	
							$address['city'] = $line_of_text[$i][($this->input->post('slt_city')-1)];
						if($this->input->post('slt_state') != '')	
							$address['state'] = $line_of_text[$i][($this->input->post('slt_state')-1)];
						
						$address_type='';
						if($this->input->post('slt_address_type') != '')	
						{
							$address_type=$line_of_text[$i][($this->input->post('slt_address_type')-1)];
						}
						if(!empty($address_type) || !empty($address['address_line1']) || !empty($address['slt_address2']) || !empty($address['slt_city'])|| !empty($address['slt_state']))	
						{
								$match = array("name"=>$address_type);
								$address_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__address_type_master');							
								if(!empty($address_list[0]['id']))
								{
									$address['address_type'] = $address_list[0]['id'];
								}
						}
						if(!empty($address['address_line1']) || !empty($address['slt_address2']) || !empty($address['slt_city'])|| !empty($address['slt_state']))	
						{				
							$address['contact_id']=$contact_id;
							$this->obj->insert_address_trans_record($address);
							unset($address);
						}
						if($this->input->post('slt_default_email') != '')	
						{
							$email_data['email_address'] = strtolower($line_of_text[$i][($this->input->post('slt_default_email')-1)]);
							$email_data['is_default']='1';
							if($this->input->post('slt_email_type') != '')	
							{	
								$email_type=$line_of_text[$i][($this->input->post('slt_email_type')-1)];
							}
							if(!empty($email_type))	
								{
									$match = array("name"=>$email_type);
									$email_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__email_type_master');		
									if(!empty($email_list[0]['id']))
									{
									$email_data['email_type'] = $email_list[0]['id'];	
									}
									unset($email_list);	
									unset($email_type);		
								}					
								if(!empty($email_data['email_address']))
								{
									$email = $email_data['email_address'];
									//$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
									$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
									if (preg_match($regex, $email)) {
									
									$email_data['contact_id']=$contact_id;
									$this->obj->insert_email_trans_record($email_data);
									}
									else
									{
										$flag=1;
									}
									unset($email_data);
									
								}
						}
						if($this->input->post('slt_email2') != '')
							{	
								$email_data['email_address'] = strtolower($line_of_text[$i][($this->input->post('slt_email2')-1)]);
								if($this->input->post('slt_email_type_2') != '')	
								{	
									$email_type=$line_of_text[$i][($this->input->post('slt_email_type_2')-1)];
								}
								if(!empty($email_type))	
								{
									$match = array("name"=>$email_type);
									$email_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__email_type_master');		
									if(!empty($email_list[0]['id']))
									{
									$email_data['email_type'] = $email_list[0]['id'];	
									}	
									unset($email_list);
									unset($email_type);	
											
								}
								if(!empty($email_data['email_address']))
								{
										
										$email = $email_data['email_address'];
										//$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
										$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
										if (preg_match($regex, $email)) 
										{
											$email_data['contact_id']=$contact_id;
											$this->obj->insert_email_trans_record($email_data);
										}
										else
										{
												$flag=1;
										}
										unset($email_data);
								
								}
							}
						if($this->input->post('slt_email3') != '')
							{	
							$email_data['email_address'] = strtolower($line_of_text[$i][($this->input->post('slt_email3')-1)]);
							if($this->input->post('slt_email_type_3') != '')	
								{
									$email_type=$line_of_text[$i][($this->input->post('slt_email_type_3')-1)];
								}
							if(!empty($email_type))	
								{
									$match = array("name"=>$email_type);
									$email_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__email_type_master');		
									if(!empty($email_list[0]['id']))
									{
									$email_data['email_type'] = $email_list[0]['id'];	
									}
									unset($email_list);
									unset($email_type);					
								}
								if(!empty($email_data['email_address']))
								{
									$email = $email_data['email_address'];
									//$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
									$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
									if (preg_match($regex, $email)) 
									{
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
									}
									else
									{
											$flag=1;
									}
									unset($email_data);
								}
							}
						if($this->input->post('slt_email4') != '')	
							{
								$email_data['email_address'] = strtolower($line_of_text[$i][($this->input->post('slt_email4')-1)]);
								if($this->input->post('slt_email_type_4') != '')	
								{
									$email_type=$line_of_text[$i][($this->input->post('slt_email_type_4')-1)];
								}
								if(!empty($email_type))	
								{
									$match = array("name"=>$email_type);
									$email_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__email_type_master');	
									if(!empty($email_list[0]['id']))
									{
									$email_data['email_type'] = $email_list[0]['id'];	

									}
									unset($email_list);
									unset($email_type);	
								}
								if(!empty($email_data['email_address']))
								{
										$email = $email_data['email_address'];
										//$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
										$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
										if (preg_match($regex, $email)) 
										{
											$email_data['contact_id']=$contact_id;
											$this->obj->insert_email_trans_record($email_data);
										}
										else
										{
												$flag=1;
										}
										unset($email_data);
								}
							}
						if($this->input->post('slt_email5') != '')	
							{
								$email_data['email_address'] = strtolower($line_of_text[$i][($this->input->post('slt_email5')-1)]);
								if($this->input->post('slt_email_type_5') != '')	
								{
									$email_type=$line_of_text[$i][($this->input->post('slt_email_type_5')-1)];
								}
								if(!empty($email_type))	
								{
									$match = array("name"=>$email_type);
									$email_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__email_type_master');		
									if(!empty($email_list[0]['id']))
									{
										$email_data['email_type'] = $email_list[0]['id'];	
									}	
									unset($email_list);
									unset($email_type);					
								}
								if(!empty($email_data['email_address']))
								{
									$email = $email_data['email_address'];
									//$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
									$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
									if (preg_match($regex, $email)) 
									{
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
									}
									else
									{
											$flag=1;
									}
									unset($email_data);
								}
							}
						if($this->input->post('slt_default_phone') != '')	
							{
								$phone_data['phone_no'] = $line_of_text[$i][($this->input->post('slt_default_phone')-1)];
								if($this->input->post('slt_phone_type') != '')	
								{
									$phone_type=$line_of_text[$i][($this->input->post('slt_phone_type')-1)];
								}
								$phone_data['is_default']='1';
								
								if(!empty($phone_type))	
								{
									$match = array("name"=>$phone_type);
									$phone_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__phone_type_master');		
									if(!empty($phone_list[0]['id']))
									{
										$phone_data['phone_type'] = $phone_list[0]['id'];	
									}	
									unset($phone_list);	
									unset($phone_type);				
								}
								if(!empty($phone_data['phone_no']))
								{
									$regex = '/^([\+]{0,1})([0-9\-])+$/';
									if(preg_match($regex, $phone_data['phone_no'])) {
										$phone_data['contact_id']=$contact_id;
										$this->obj->insert_phone_trans_record($phone_data);
										unset($phone_data);
									}
								}
							}
						if($this->input->post('slt_phone2') != '')
							{	
							$phone_data['phone_no'] = $line_of_text[$i][($this->input->post('slt_phone2')-1)];
							if($this->input->post('slt_phone2_type') != '')	
							{
								$phone_type=$line_of_text[$i][($this->input->post('slt_phone2_type')-1)];
							}
							if(!empty($phone_type))
								{
									$match = array("name"=>$phone_type);
									$phone_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__phone_type_master');		
									if(!empty($phone_list[0]['id']))
									{
										$phone_data['phone_type'] = $phone_list[0]['id'];	
									}	
									unset($phone_list);	
									unset($phone_type);				
								}
							if(!empty($phone_data['phone_no']))
							{
								$regex = '/^([\+]{0,1})([0-9\-])+$/';
								if(preg_match($regex, $phone_data['phone_no'])) {
									$phone_data['contact_id']=$contact_id;
									$this->obj->insert_phone_trans_record($phone_data);
									unset($phone_data);
									unset($phone_type);
								}
							}
						}
						if($this->input->post('slt_phone3') != '')	
						{
							$phone_data['phone_no'] = $line_of_text[$i][($this->input->post('slt_phone3')-1)];
							if($this->input->post('slt_phone3_type') != '')	
							{
								$phone_type=$line_of_text[$i][($this->input->post('slt_phone3_type')-1)];
							}
							if(!empty($phone_type))	
								{
									$match = array("name"=>$phone_type);
									$phone_list= $this->obj1->select_records1('',$match,'','=','','','','','','contact__phone_type_master');		
									if(!empty($phone_list[0]['id']))
									{
										$phone_data['phone_type'] = $phone_list[0]['id'];	
									}	
									unset($phone_list);
									unset($phone_type);				
								}
							if(!empty($phone_data['phone_no']))
							{
								$regex = '/^([\+]{0,1})([0-9\-])+$/';
								if(preg_match($regex, $phone_data['phone_no'])) {
									$phone_data['contact_id']=$contact_id;
									$this->obj->insert_phone_trans_record($phone_data);
									unset($phone_data);
								}
							}
						}
						if($this->input->post('slt_contact_type') != '')	
						{
								$c_type=$line_of_text[$i][($this->input->post('slt_contact_type')-1)];
								
								$contact_exl=explode(',',$c_type);
								for($j=0;$j<count($contact_exl);$j++)
								{
									if(!empty($contact_exl[$j]))
									{
										$match = array("name"=>trim($contact_exl[$j]));
										$result123= $this->obj1->select_records1('',$match,'','=','','','','','', ' contact__type_master');
										if(!empty($result123[0]['id']))
										{
											$c_typedata['contact_id'] = $contact_id;
											$c_typedata['contact_type_id'] = $result123[0]['id'];
											$this->obj->insert_contact_type_record($c_typedata);
										}
									}
							
								}
							unset($c_typedata);
						}
						
							
					}
					
				}
			}
			if($flag == 1)
			{
				$data['msg'] = $this->lang->line('import_contact_msg_invalid');
				
			}
			$data['csv_id']=$id;
			$data['count_no_contact']=$count_no_contact;
			$data['main_content'] = $this->user_type.'/'.$this->viewName."/add_more_contact";
			$this->load->view('user/include/template',$data);
		}
		else
		{
			redirect('user/'.$this->viewName);
		}
	}
	
	 /*
    @Description: Function  use for Delete last import contact CSV File in contacts By Admin
    @Author: Kaushik Valiya
    @Input: - Delete id which contacts record last import CSV File in all contact
    @Output: - Remove all contact and Relation table data.
    @Date: 12-07-2014
    */
    function delete_last_import()
    {
        $id = $this->uri->segment(4);
		$match = array('csv_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		for($i=0;$i<count($result); $i++)
		{
			$contact_id=$result[$i]['id'];
			$this->obj->delete_table_trans_record($contact_id,'contact_emails_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_phone_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_contacttype_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_address_trans');
			$this->obj->delete_record($contact_id);
		}
		$this->obj->delete_record($result[$i]['id']);
      	$msg = $this->lang->line('common_delete_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
    }
	
	function merge_search_contacts()
	{
		$fields = $this->input->post('slt_fields');
		if(!empty($fields) && count($fields) > 0)
		{
			//$data['datalistcounter'] = $this->obj->merge_search_contacts_counter($fields);
			
			$data = $this->obj->merge_search_contacts($fields,$this->user_session['agent_id'],$this->user_session['agent_user_id']);
					
			$data['fields_data'] = $fields;
			
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/merge_contact_list";
			
			
			
			$this->load->view('user/include/template',$data);
		}
		else
		{
			redirect('user/'.$this->viewName.'/merge_duplicate_contacts');
		}
	}
	
	/*
    @Description: Get Details of Edit contacts Profile
    @Author: Nishit Modi
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 04-07-2014
    */
    public function view_record()
    {
       if($_REQUEST['action'] =='login')
		{
			
			$data = array();
			include 'social/library.php';
			$action = $_REQUEST["action"];
			
			switch($action){
				case "login":
				include 'social/facebook.php';
				$appid 		= $this->config->item('facebook_api_key');
				$appsecret  = $this->config->item('facebook_secret_key');
				$facebook   = new Facebook(array(
					'appId' => $appid,
					'secret' => $appsecret,
					'cookie' => TRUE,
				));

				$contactid = $this->uri->segment(4);				
				$user = $facebook->getUser();
				$access_token = $facebook->getAccessToken();
				$fbuser = $facebook->getUser();
				if ($fbuser) {
					try {
						$user_profile = $facebook->api('/me');
						$frnd = $facebook->api('/me/friends');
						//$conversation=$facebook->api( '/me/conversations');
						$conversation=$facebook->api( '/me/inbox');
						$data['conversation']=$conversation;
						}
					catch (Exception $e) {
						echo $e->getMessage();
						exit();
						}
					
					$user_fbid = $fbuser;
					$friends = $facebook->api('/me/friends');
					$user_email =(!empty($user_profile["email"])) ? $user_profile["email"] : '-';
					$user_gender = (!empty($user_profile["gender"])) ? $user_profile["gender"] : '-';;
					$user_fnmae = (!empty($user_profile["first_name"])) ? $user_profile["first_name"] : '-';
					$user_lnmae = (!empty($user_profile["last_name"])) ? $user_profile["last_name"] : '-';
					$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
				}
				break;
			}
			
					$match=array('id'=>$contactid);
					$get_contact_data = $this->obj->select_records('',$match,'','=');
					
					if(!empty($get_contact_data))
					{
						//echo $contactid."<br>";
						//echo $get_contact_data[0]['fb_id']."<br>";
						//echo $user_fbid."<br>";
						//exit;
						
						$old_msg=$this->obj->select_contact_chat_message_list($contactid,$get_contact_data[0]['fb_id'],$user_fbid,$this->user_session['id']);
						
						$last_insert_msg = '0000-00-00 00:00:00';
							
							if(!empty($old_msg))
							{
									$last_insert_msg = $old_msg[0]['sync_date_time'];
							}
							
							//echo $last_insert_msg."<br>";
							
							if(!empty($conversation['data']))
							{
								
								if($get_contact_data[0]['fb_id'] == $user_fbid)
								{
									for($i=0;$i<count($conversation['data']);$i++)
									{
									//echo "i-".$i."<br>";
									//echo $last_insert_msg.'=='.$datetime.'<br>';exit;
									if(empty($conversation['data'][$i]['to']['data'][1]['id']))
									{
										
										////////////////////////////////////////////
										
										$sycdata['contact_id']=$contactid;
										$sycdata['participent1']=$conversation['data'][$i]['to']['data'][0]['id'];
										$sycdata['participent2']=$conversation['data'][$i]['to']['data'][0]['id'];
										
										$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
										$sycdata['created_by']=$this->user_session['id'];
										
										if(!empty($old_msg[0]['sync_date_time']))
										{
												$sycdata['id']= $old_msg[0]['id'];
												$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
												$result1=$this->obj->update_chat_last_sync($sycdata);
										}
										else
										{
											$this->obj->insert_chat_last_sync($sycdata);
										}
										
										
										for($j=0;$j<count($conversation['data'][$i]['comments']['data']);$j++)
										{
											//$conversation['data'][$i]['comments']['data'][$j]['from']['id'];
												
													//// This Login to Contact Converation/// 
											$datetime1 = str_replace("T"," ",$conversation['data'][$i]['comments']['data'][$j]['created_time']);
											$datetime2 = str_replace("+0000","",$datetime1);
											$datetime = trim($datetime2);		
											if($last_insert_msg < $datetime)
											{		
												$data_insert['from_fb_id']= $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][0]['name'];
												$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][0]['name'];
												
												$data_insert['contact_id']=$contactid;
												$data_insert['login_fb_id']=$user_fbid;
												$data_insert['type']=1;
												$data_insert['msg_date_time']   = $conversation['data'][$i]['comments']['data'][$j]['created_time'];
												$data_insert['msg']   = $conversation['data'][$i]['comments']['data'][$j]['message'];										$data_insert['inserted_date_time']=date('Y-m-d H:i:s');
												$data_insert['created_by']=$this->user_session['id'];
												
												$this->obj->insert_chat_history($data_insert);
											}
										}
										
										////////////////////////////////////////////
										
									}
											
								}
								}
								else
								{
									for($i=0;$i<count($conversation['data']);$i++)
									{
									//echo "i-".$i."<br>";
									//echo $last_insert_msg.'=='.$datetime.'<br>';exit;
									/*if(empty($conversation['data'][$i]['to']['data'][1]['id']))
									{
										
										////////////////////////////////////////////
										
										$sycdata['contact_id']=$contactid;
										$sycdata['participent1']=$conversation['data'][$i]['to']['data'][0]['id'];
										$sycdata['participent2']=$conversation['data'][$i]['to']['data'][0]['id'];
										
										$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
										
										if(!empty($old_msg[0]['sync_date_time']))
										{
												$sycdata['id']= $old_msg[0]['id'];
												$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
												$result1=$this->obj->update_chat_last_sync($sycdata);
										}
										else
										{
											$this->obj->insert_chat_last_sync($sycdata);
										}
										
										
										for($j=0;$j<count($conversation['data'][$i]['comments']['data']);$j++)
										{
											//$conversation['data'][$i]['comments']['data'][$j]['from']['id'];
												
													//// This Login to Contact Converation/// 
											$datetime1 = str_replace("T"," ",$conversation['data'][$i]['comments']['data'][$j]['created_time']);
											$datetime2 = str_replace("+0000","",$datetime1);
											$datetime = trim($datetime2);		
											if($last_insert_msg < $datetime)
											{		
												$data_insert['from_fb_id']= $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][0]['name'];
												$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][0]['name'];
												
												$data_insert['contact_id']=$contactid;
												$data_insert['login_fb_id']=$user_fbid;
												$data_insert['msg_date_time']   = $conversation['data'][$i]['comments']['data'][$j]['created_time'];
												$data_insert['msg']   = $conversation['data'][$i]['comments']['data'][$j]['message'];										$data_insert['inserted_date_time']=date('Y-m-d H:i:s');
												$data_insert['created_by']=$this->user_session['id'];
												
												$this->obj->insert_chat_history($data_insert);
											}
										}
										
										////////////////////////////////////////////
										
									}
									else */if($conversation['data'][$i]['to']['data'][0]['id'] == $get_contact_data[0]['fb_id'] ||(!empty($conversation['data'][$i]['to']['data'][1]['id']) && $conversation['data'][$i]['to']['data'][1]['id'] == $get_contact_data[0]['fb_id']))
									{
										
										//echo "i1-".$i."<br>";
										
										$sycdata['contact_id']=$contactid;
										$sycdata['participent1']=$conversation['data'][$i]['to']['data'][0]['id'];
										if(empty($conversation['data'][$i]['to']['data'][1]['id']))
										{
											$sycdata['participent2']=$conversation['data'][$i]['to']['data'][0]['id'];
										}
										else
										{
											$sycdata['participent2']=$conversation['data'][$i]['to']['data'][1]['id'];
										}
										
										$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
										$sycdata['created_by']=$this->user_session['id'];
										
										if(!empty($old_msg[0]['sync_date_time']))
										{
												$sycdata['id']= $old_msg[0]['id'];
												$sycdata['sync_date_time']=$conversation['data'][$i]['updated_time'];
												$result1=$this->obj->update_chat_last_sync($sycdata);
										}
										else
										{
											$this->obj->insert_chat_last_sync($sycdata);
										}
										
										for($j=0;$j<count($conversation['data'][$i]['comments']['data']);$j++)
										{
											//$conversation['data'][$i]['comments']['data'][$j]['from']['id'];
												
													//// This Login to Contact Converation/// 
											$datetime1 = str_replace("T"," ",$conversation['data'][$i]['comments']['data'][$j]['created_time']);
											$datetime2 = str_replace("+0000","",$datetime1);
											$datetime = trim($datetime2);		
											if($last_insert_msg < $datetime)
											{		
											
											if(empty($conversation['data'][$i]['to']['data'][1]['id']))
											{
													
													$data_insert['from_fb_id']= $conversation['data'][$i]['to']['data'][0]['id'];
													$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][0]['name'];
													$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][0]['id'];
													$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][0]['name'];
											}
											else{	
											
											///////// Login to other Replay ////
											if($conversation['data'][$i]['comments']['data'][$j]['from']['id'] == $conversation['data'][$i]['to']['data'][1]['id'])
											{
												$data_insert['from_fb_id']= $conversation['data'][$i]['to']['data'][1]['id'];
												$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][1]['name'];
												$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][0]['name'];
												
											}
											///////// Login to other send ////
										if($conversation['data'][$i]['comments']['data'][$j]['from']['id'] == $conversation['data'][$i]['to']['data'][0]['id'])	
											{
												$data_insert['from_fb_id']= $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][0]['name'];
												$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][1]['id'];
												$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][1]['name'];
											}
													
												
												}
												
												$data_insert['contact_id']=$contactid;
												$data_insert['login_fb_id']=$user_fbid;
												$data_insert['type']=1;
												$data_insert['msg_date_time']   = $conversation['data'][$i]['comments']['data'][$j]['created_time'];
												$data_insert['msg']   = $conversation['data'][$i]['comments']['data'][$j]['message'];										$data_insert['inserted_date_time']=date('Y-m-d H:i:s');
												$data_insert['created_by']=$this->user_session['id'];
												
											//	if($old_msg[$]msg_date_time)
											
												//pr($data_insert);
												
												$this->obj->insert_chat_history($data_insert);
												}
												
										}
										
										//// This is self history import///
										
									/*	if(empty($conversation['data'][$i]['to']['data'][1]['id']) && $conversation['data'][$i]['to']['data'][0]['id'] == $conversation['data'][$i]['comments']['data'][$j]['from']['id'])
											{
												
												echo 'hi ';
												$data_insert['contact_id']=$contactid;
												$data_insert['login_fb_id']=$user_fbid;
												
												$data_insert['from_fb_id'] = $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['from_fb_name'] = $conversation['data'][$i]['to']['data'][0]['name'];
												
												$data_insert['to_fb_id']   = $conversation['data'][$i]['to']['data'][0]['id'];
												$data_insert['to_fb_name']   = $conversation['data'][$i]['to']['data'][0]['name'];
												
												$data_insert['msg_date_time']   = $conversation['data'][$i]['comments']['data'][$j]['created_time'];
												$data_insert['msg']   = $conversation['data'][$i]['comments']['data'][$j]['message'];
												$data_insert['inserted_date_time']=date('Y-m-d H:i:s');
												$data_insert['created_by']=$this->user_session['id'];
												
												$this->obj->insert_chat_history($data_insert);
												
											}*/
										//////////////end///
										
												
											
										
										
									
									}
											
								}
								}	
									
								}
					}
			
					/*if(!empty($conversation['data']))
					{
						for($i=0;$i<count($conversation['data']);$i++)
						{
							$data=$conversation['data'][$i];
							$id[]=$data['id'];
							$participants[]=$data['participants'];
							$updated_time[]=$data['updated_time'];
							$messages[]=$data['messages'];
						}	

						for($l=0;$l<count($participants);$l++)
						{
							$data3[$l]=$participants[$l]['data'];
							for($k=0;$k<count($data3[$l]);$k++)
							{
								$data2[$l]['participent1']=$data3[$l][0]['id'];
								$data2[$l]['participent2']=$data3[$l][1]['id'];
							}
							
							$data2[$l]['updated_time']=$updated_time[$l];
						}
						
						for($j=0;$j<count($messages);$j++)
						{
							$data1[$j]=$messages[$j]['data'];
							
							for($k=0;$k<count($data1[$j]);$k++)
							{
								
								$data2[$j][$k]['msg_id']=$data1[$j][$k]['id'];
								$data2[$j][$k]['created_time']=$data1[$j][$k]['created_time'];
								$data2[$j][$k]['from_name']=$data1[$j][$k]['from']['name'];
								$data2[$j][$k]['from_id']=$data1[$j][$k]['from']['id'];
								$data2[$j][$k]['full_msg']=$data1[$j][$k]['message'];
								$data2[$j][$k]['to_name']=$data1[$j][$k]['to']['data'][0]['name'];
								$data2[$j][$k]['to_id']=$data1[$j][$k]['to']['data'][0]['id'];
							}
						}
					}
					
				
				$match=array('id'=>$contactid);
				$data5['from_id'] = $this->obj->select_records('',$match,'','=');
				$login_id=$user_fbid;//facebook login_id
				
				if(!empty($data5['from_id']))
				{
					$contact_fb_id=$data5['from_id'][0]['fb_id'];
					if(!empty($data2))
					{
						for($i=0;$i<count($data2);$i++)
						{
							if(($contact_fb_id==$data2[$i]['participent1'] || $contact_fb_id==$data2[$i]['participent2'])&&($login_id==$data2[$i]['participent1'] || $login_id==$data2[$i]['participent2']) && ($data2[$i]['participent2'] != NULL))
							{
			
									$datai['participent1'] = $data2[$i]['participent1'];
									$datai['participent2'] = $data2[$i]['participent2'];
									$datai['contact_id'] = $contactid;
									$result = $this->obj->check_sync_exist($datai);
									$lastupdatedate = '0000-00-00 00:00:00';
									if(count($result) > 0)
									{	
										$lastupdatedate = $result[0]['sync_date_time'];
										$sycdata['id']=$result[0]['id'];
										$sycdata['sync_date_time']=$data2[$i]['updated_time'];
										$this->obj->update_chat_last_sync($sycdata);
									}
									else
									{
										$sycdata['contact_id']=$contactid;
										$sycdata['participent1']=$data2[$i]['participent1'];
										$sycdata['participent2']=$data2[$i]['participent2'];
										$sycdata['sync_date_time']=$data2[$i]['updated_time'];
										$this->obj->insert_chat_last_sync($sycdata);
									}
									unset($data2[$i]['participent1']);
									unset($data2[$i]['participent2']);
									unset($data2[$i]['updated_time']);
									
									for($j=0; $j<(count($data2[$i])); $j++)
									{
										$datetime = str_replace("T"," ",$data2[$i][$j]['created_time']);
										$datetime = str_replace("+0000","",$data2[$i][$j]['created_time']);
										$datetime = trim($datetime);
										if (strtotime($lastupdatedate) < strtotime($datetime))
										{
											$data4['contact_id']=$contactid;
											$data4['login_fb_id']=$user_fbid;
											$data4['from_fb_id']= $data2[$i][$j]['from_id'];
											$data4['from_fb_name']=$data2[$i][$j]['from_name'];
											$data4['to_fb_name']=$data2[$i][$j]['to_name'];
											$data4['to_fb_id']= $data2[$i][$j]['to_id'];
											$data4['msg_date_time']= $data2[$i][$j]['created_time'];	
											$data4['msg']= $data2[$i][$j]['full_msg'];
											$data4['inserted_date_time']=date('Y-m-d H:i:s');
											$data4['created_by']=$this->user_session['id'];
											$this->obj->insert_chat_history($data4);
										}
									}
						       }
						  }
					 }
				}
			else
			{
				echo"No Records Found.....";
			}*/
		}
		
		else
		{
                    $cform_tab = check_joomla_tab_setting($this->user_session['id']);
                    if(!empty($cform_tab))
                        $data['cform_tab'] = $cform_tab[0]['contact_form_tab'];
		
		$selected_view_session = $this->session->userdata('joomla_selected_view_session');
                    if(!empty($selected_view_session['selected_view']))
                        $selected_view = $selected_view_session['selected_view'];
                    else
                        $selected_view = '1';
                    
                    if($selected_view == 105 || $selected_view == 109 || $selected_view == 108 || $selected_view == 106)
                        $data['joomla_tabid'] = $selected_view;
                    else
                        $data['joomla_tabid'] = '';
						
		$id = $this->uri->segment(4);
		$newdata = array('contact_id'=>$id);
        $this->session->set_userdata('selected_contactid',$newdata);
 		$createdby = $this->user_session['id'];
		$match2=array('user_id'=>$createdby,'contact_id'=>$id);
		$data['contact_invitation_trans']=$this->obj->select_records1('',$match2,'','=','','','','','desc',' contact_invitation_transcation');
		//$get_user_info=$this->obj->select_user_contact_trans_record($id);
		//$user_id=$get_user_info[0]['user_id'];
		/*if(!empty($user_id))
		{
			$match=array('id'=>$user_id);
			$data['user_name'] = $this->obj1->select_records1('',$match,'','=','','','','first_name','desc','user_master');
		}*/
		
		$table = "user_contact_trans as uct";
		$fields = array('first_name,middle_name,last_name,lm.agent_type');
		$join_tables = array('user_master as um'=>'um.id = uct.user_id',
							 'login_master as lm'=>'lm.user_id = um.id',
							 );
		$group_by = 'uct.id';
		$where = array('contact_id'=>$id);
		
		$data['user_name'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','',$group_by,$where);
		//pr($data['user_name']);exit;
		
		$match_cat = array("parent"=>'0');
		 $data['category'] = $this->marketing_library_masters_model->select_records1('',$match_cat,'','=','','','','id','desc','marketing_master_lib__category_master');

		$match1=array("contact_id"=>$id,"created_by"=>$createdby);
		$data['chat'] = $this->obj->select_records1('',$match1,'','=','','','','msg_date_time','desc','contact_chat_history');
		
		//$match = array("created_by"=>$this->user_session['id']);
		$match = array();
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['contact_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['field_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','desc', 'contact__additionalfield_master');
		
		$data['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		
		$data['method_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__method_master');
		$data['disposition_type'] = $this->obj1->select_records1('','','','=','','','','name','asc', 'contact__disposition_master');
		
		// Conversations
			
	 /*?>	$table = " contact_conversations_trans as cct";
		$fields = array('cct.id','cct.contact_id','cct.description','ipm.plan_name as interaction_plan_name','iptm.name','ipim.description as interaction_name','ipm.id as interaction_plan_id');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = cct.interaction_type',
							'interaction_plan_master as ipm' => 'ipm.id = cct.interaction_plan_id',
							'interaction_plan_interaction_master as ipim'=>'ipim.id = cct.interaction_plan_interaction_id'
						);
		$group_by='cct.id';
		$where=array('contact_id'=>$id);
		$data['interation_plan_communication_plan'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);<?php */
		
	$table = "contact_conversations_trans as cct";
		$fields = array('cct.id','cct.*','cct.contact_id','cct.interaction_id','cct.created_date','cct.disposition','cdm.name as disposition_name','cct.description','cct.mail_out_type','cct.log_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','CONCAT_WS(" ",um1.first_name,um1.middle_name,um1.last_name) as user_name1','cct.email_camp_template_name as email_template_name','cct.email_camp_template_name as email_campaing_template_name','cct.sms_camp_template_name as sms_template_name','cct.sms_camp_template_name as sms_template_name','cct.mail_out_template_name as letter_template_name','cct.mail_out_template_name as envelope_template_name','cct.mail_out_template_name as label_template_name','tm.task_name,tm.desc','ipm.status','tm.task_date','ipptm.name as interaction_type_name','lm_c.admin_name as created_by_admin','CONCAT_WS(" ",um_c.first_name,um_c.middle_name,um_c.last_name) as created_by_user','ecr.id as ecr_id','scr.id as scr_id');
		$join_tables = array(
					'contact__disposition_master as cdm'=>'cdm.id = cct.disposition',
					'contact_master as cm'=>'cm.id = cct.contact_id',
					'user_master as um1'=>'um1.id = cct.assign_to',
					'login_master as lm'=>'lm.id = cct.assign_to',
					'user_master as um'=>'um.id = lm.user_id',
					'task_master as tm'=>'tm.id = cct.task_id',
					'interaction_plan_master as ipm'=>'ipm.id = cct.plan_id',
					'interaction_plan__plan_type_master ipptm'=>'ipptm.id = cct.interaction_type',
					'login_master as lm_c'=>'lm_c.id = cct.created_by',
					'user_master as um_c'=>'um_c.id = lm_c.user_id',
					'email_campaign_recepient_trans ecr'=>'cct.campaign_id = ecr.email_campaign_id AND cct.contact_id = ecr.contact_id',
					'sms_campaign_recepient_trans scr'=>'cct.campaign_id = scr.sms_campaign_id AND cct.contact_id = scr.contact_id',
							);
		$group_by='cct.id';
		$where=array('cct.contact_id'=>$id/*,'assign_to'=>$this->user_session['id']*/);
		$data['conversations'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','special_case_task','',$group_by,$where);
		//pr($data['conversations']);exit;
				/////////////////////////////////////////////End Conversations/////////////////////////////////////////

		
		// End Conversations
		
		 // Communication Plan
		$table = "interaction_plan_contact_communication_plan as ipcc";
		$fields = array('ipcc.id,ipcc.completed_by,lom1.admin_name as completed_by_name,ipcc.task_completed_date','ipcc.contact_id','ipcc.task_date','ipcc.is_done','ipcc.interaction_plan_interaction_id','ipm.plan_name as interaction_plan_name','iptm.name','ipim.description as interaction_name','ipm.id as interaction_plan_id,ipim.assign_to,lom.admin_name as assign_name','ipim.status','ipim.start_type','ipim.interaction_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as assign_name1','CONCAT_WS(" ",um1.first_name,um1.middle_name,um1.last_name) as completed_by_name1');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = ipcc.interaction_type',
							'interaction_plan_master as ipm' => 'ipm.id = ipcc.interaction_plan_id',
							'interaction_plan_interaction_master as ipim'=>'ipim.id = ipcc.interaction_plan_interaction_id',
							'login_master as lom' => 'lom.id = ipim.assign_to',
							'user_master as um'=>'um.id = lom.user_id',
							'login_master as lom1' => 'lom1.id = ipcc.completed_by',
							'user_master as um1'=>'um1.id = lom1.user_id'
						);
		$group_by='ipcc.id';
		$where=array('contact_id'=>$id);
		$data['interation_plan_communication_plan'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','special_case','',$group_by,$where);
		//pr($data['interation_plan_communication_plan']);exit;
		// end Communication Plan
		
		//  personal Touches
		$table = "interaction_plan_contact_personal_touches as ipcp";
		$fields = array('ipcp.id','ipcp.task','iptm.name','ipcp.followup_date','ipcp.is_done,ipcp.created_by');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = ipcp.interaction_type'
							);
		$group_by='ipcp.id';
		$where=array('contact_id'=>$id);
		$data['personale_touches'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		
		$data['interaction_type'] = $this->obj1->select_records1('','','','','','','','name','asc', 'interaction_plan__plan_type_master');
		//pr($data['interaction_type']);exit;
		//  personal Touches
		//pr($data['interation_plan_communication_plan']);exit;
		//Get communication plan data
		$data['communication_plans'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc', 'interaction_plan_master');
		$table = "contact_additionalfield_trans as caft";
		$fields = array('caft.id','caft.field_name','cafm.name');
		$join_tables = array(
								'contact__additionalfield_master as cafm' => 'cafm.id = caft.field_type'
							);
		$group_by='caft.id';
		$where=array('contact_id'=>$id);
		$data['field_trans_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);

		$where_in = 'ipm.created_by IN ('.$this->user_session['agent_id'].')';
		$data['interaction_plan_list'] = $this->obj2->select_usercontact_intraction_plan($id,$where_in);
		$data['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$data['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$data['address_trans_data'] = $this->obj->select_address_trans_record($id);
		//$data['website_trans_data'] = $this->obj->select_website_trans_record($id);
		
		$table = "contact_website_trans as cwt";
		$fields = array('cwt.id','cwt.website_name','cwm.name');
		$join_tables = array(
								'contact__websitetype_master as cwm' => 'cwm.id = cwt.website_type'
							);
		$group_by='cwt.id';
		$where=array('contact_id'=>$id);
		$data['website_trans_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		
		
		$table = "contact_additionalfield_trans as caft";
		$fields = array('caft.id','caft.field_name','cafm.name');
		$join_tables = array(
								'contact__additionalfield_master as cafm' => 'cafm.id = caft.field_type'
							);
		$group_by='caft.id';
		$where=array('contact_id'=>$id);
		$data['field_trans_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		
		$data['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->obj->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->obj->select_tag_record($id);
		$data['communication_trans_data'] = $this->obj->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->obj->select_document_trans_record($id);
		//pr($data['email_type']);
		
        //$match = array('id'=>$id);
        //$result = $this->obj->select_records('',$match,'','=');
        
        // Contact Register Sanjay Moghariya. 29-10-2014
        $config['per_page'] = '10';
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 0;
        $uri_segment = 0;

        $table = "contact_master as cm";
        $group_by = "cm.id";
        $wherestring = array('cm.id'=>$id);

       /* $fields = array('cm.id','cm.contact_pic','cm.fb_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cm.created_date','cm.joomla_domain_name','cm.joomla_user_id','cm.created_by');*/

        $fields = array('cm.*','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address');

        $join_tables = array(
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
        );
        $result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,'','',$group_by,$wherestring);
        $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        // End SANJAY M. 29-10-2014
        
        $data['editRecord'] = $result;
		//pr($data['editRecord']);
		if(($result[0]['joomla_user_id'] != 0))
            {
                    $data['joomla'] = "Yes";
            }
            else 
            {
                    $data['joomla'] = "No";
            }
		//pr($data['joomla']);exit;	
		$field = array('id','twitter_id','linkedin_access_token');
		$match = array('id'=>$this->user_session['id']);
		$udata = $this->admin_model->get_user($field, $match,'','=');
		if($udata[0]['linkedin_access_token'])
		{
			$match = array('linkedin_id'=>$data['editRecord'][0]['linkedin_id'],'created_by'=>$this->user_session['id']);
			$result = $this->obj->select_records3('',$match,'','=','','','','id','desc');
			if(!empty($result))
			{
				if($data['editRecord'][0]['linkedin_id']==$result[0]['linkedin_id'] && $data['editRecord'][0]['created_by']==$result[0]['created_by'])
				{
					$data['already_connected']='1';
				}	
			}	
		}
		
		if(!empty($udata))
		{
			$field1 = array('twitter_handle');
			$match = array('created_by'=>$this->user_session['id'],'twitter_user_id'=>$udata[0]['twitter_id']);
			$twitter_social = $this->obj->select_records4($field1,$match,'','=','','','','id','desc');
			$twitter_screen_name=array();
			foreach($twitter_social as $twitter_social1)
			{
				$twitter_screen_name[]=$twitter_social1['twitter_handle'];
			}
			$data['twitter_screen_name']=$twitter_screen_name;
			//pr($data['twitter_social']);exit;
		}	
		
		                $config1['per_page'] = '10';
                $config1['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_savser/".$id."/");
                $config1['is_ajax_paging'] = TRUE; // default FALSE
                $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config1['uri_segment'] = 0;
                $uri_segment1 = 0;
				
                $match = array('lw_admin_id'=>$id);
                $result_saved_searches = $this->saved_searches_model->select_records('',$match,'','=','',$config1['per_page'],$uri_segment1,'id','desc');
                $config1['total_rows']= $this->saved_searches_model->select_records('',$match,'','=','','','','','','','1');
                
                $this->pagination->initialize($config1);
                $data['pagination1'] = $this->pagination->create_links();
                // End SANJAY M. 29-10-2014
				
                // Favorite Sanjay Moghariya. 29-10-2014
                $config2['per_page'] = '10';
                $config2['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_fav/".$id."/");
                $config2['is_ajax_paging'] = TRUE; // default FALSE
                $config2['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config2['uri_segment'] = 0;
                $uri_segment2 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_favorite = $this->favorite_model->select_records('',$match,'','=','',$config2['per_page'],$uri_segment2,'id','desc');
                $config2['total_rows']= $this->favorite_model->select_records('',$match,'','=','','','','','','','1');
                
                $this->pagination->initialize($config2);
                $data['pagination2'] = $this->pagination->create_links();
                // End SANJAY M. 29-10-2014
			
                // Property Viewed Sanjay Moghariya. 29-10-2014
                $config3['per_page'] = '10';
                $config3['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_prop_view/".$id."/");
                $config3['is_ajax_paging'] = TRUE; // default FALSE
                $config3['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config3['uri_segment'] = 0;
                $uri_segment3 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_properties_viewed = $this->properties_viewed_model->select_records('',$match,'','=','',$config3['per_page'],$uri_segment3,'id','desc');
                $config3['total_rows']= $this->properties_viewed_model->select_records('',$match,'','=','','','','','','','1');
                
                $this->pagination->initialize($config3);
                $data['pagination3'] = $this->pagination->create_links();
                // End SANJAY M. 29-10-2014
				
                // Last Login Sanjay Moghariya. 29-10-2014
                $config4['per_page'] = '10';
                $config4['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_lastlog/".$id."/");
                $config4['is_ajax_paging'] = TRUE; // default FALSE
                $config4['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config4['uri_segment'] = 0;
                $uri_segment4 = 0;

                $match = array('lw_admin_id'=>$id);
                //$result_last_login = $this->last_login_model->select_records('',$match,'','=','',$config4['per_page'],$uri_segment4);
                $result_last_login = $this->last_login_model->select_records('',$match,'','=','',$config4['per_page'],$uri_segment4,'id','desc');
                $config4['total_rows']= $this->last_login_model->select_records('',$match,'','=','','','','','','','1');
                
                $this->pagination->initialize($config4);
                $data['pagination4'] = $this->pagination->create_links();
                // End SANJAY M. 29-10-2014
		
                // Valuation Searched Sanjay Moghariya. 02-12-2014
                $config5['per_page'] = '10';
                $config5['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_val_searched/".$id."/");
                $config5['is_ajax_paging'] = TRUE; // default FALSE
                $config5['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config5['uri_segment'] = 0;
                $uri_segment5 = 0;
				
                $match = array('lw_admin_id'=>$id);
                $result_val_searched = $this->property_valuation_searches_model->select_records('',$match,'','=','',$config5['per_page'],$uri_segment5,'id','desc');
			//	echo "hi";exit;
                $config5['total_rows'] = $this->property_valuation_searches_model->select_records('',$match,'','=','','','','','','','1');
                $this->pagination->initialize($config5);
                $data['pagination5'] = $this->pagination->create_links();
                // End SANJAY M. 02-12-2014
                
                // Valaution contact form Sanjay Moghariya. 10-03-2015
                $config110['per_page'] = '10';
                $config110['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_val_contact/".$id."/");
                $config110['is_ajax_paging'] = TRUE; // default FALSE
                $config110['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config110['uri_segment'] = 0;
                $uri_segment110 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_val_contact = $this->property_valuation_contact_model->select_records('',$match,'','=','',$config110['per_page'],$uri_segment110,'id','desc');
                $config110['total_rows']= $this->property_valuation_contact_model->select_records('',$match,'','=','','','','','','','1');
                $this->pagination->initialize($config110);
                $data['pagination110'] = $this->pagination->create_links();
                // End Valuation contact form SANJAY M. 10-03-2015
                
                // Property contact form Sanjay Moghariya. 10-03-2015
                $config111['per_page'] = '10';
                $config111['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_property_contact/".$id."/");
                $config111['is_ajax_paging'] = TRUE; // default FALSE
                $config111['paging_function'] = 'ajax_paging'; // Your jQuery paging
                $config111['uri_segment'] = 0;
                $uri_segment111 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_property_contact = $this->property_contact_model->select_records('',$match,'','=','',$config111['per_page'],$uri_segment111,'id','desc');
                $config111['total_rows']= $this->property_contact_model->select_records('',$match,'','=','','','','','','','1');
                $this->pagination->initialize($config111);
                $data['pagination111'] = $this->pagination->create_links();
                // End Property contact form SANJAY M. 09-03-2015
                
		///////////////////////////////////////////////////////////////////////////////////
		
		//property information
		$data['perpage'] = 10;
		$config6['per_page'] = 10;	
		$config6['base_url'] = site_url($this->user_type.'/'.$this->viewName.'/property_listing');
		$config6['is_ajax_paging'] = TRUE; // default FALSE
		$config6['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config6['uri_segment'] = 3;
		//$uri_segment = $this->uri->segment(3);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config6['uri_segment'] = 0;
			$uri_segment6 = 0;
		} else {
			$config6['uri_segment'] = 0;
			$uri_segment6 = 0;
		}
		$fields = array('plm.id,property_title','CONCAT_WS(" ",address_line_1,address_line_2) as address','city,mls_no,property_type_name,price,year_built,new_price,CASE WHEN new_price IS NOT NULL then new_price ELSE price END AS my_price,plm.created_date');
		$table = 'property_listing_contact_trans as plct';
		$join_tables = array(
							 'property_listing_master as plm' => 'plct.property_id = plm.id',
							 '(SELECT * FROM property_listing_price_change_trans ORDER BY id DESC) as plpct' => 'plpct.property_id = plm.id',
							 );
		$group_by = '';
		
		$match=array('contact_id'=>$id,'plct.created_by'=>$this->user_session['id']);
		$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config6['per_page'], $uri_segment6,$data['sortfield'],$data['sortby'],$group_by);
		$config6['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config6);
		$data['pagination6'] = $this->pagination->create_links();
		
		
		//////// Check Buyer Right or Not 
		$match1 = array("id"=>$this->user_session['id'],"is_buyer_tab"=> "1");
		$data['right_buyer'] = $this->obj1->select_records1('',$match1,'','=','','','','id','desc','login_master');
		
		//pr($data['right_buyer']);exit;
		//////End Buyer Tab
		
		
		$table = "contact_master as cm";
		$fields = array('cm.created_by as createdby_id','uct.id as uct_id');
		$join_tables = array(
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
							);
		$where=array('contact_id'=>$id);
		$data['data_match'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where);
		
		///////////////////////////////////////////////////////////////////////////////////
		$selected_view_session = $this->session->userdata('selected_view_session');
                if(!empty($selected_view_session['selected_view']))
                    $selected_view = $selected_view_session['selected_view'];
                else
                    $selected_view = '104';
                
                $data['tabid'] = $selected_view;
                $data['selected_contact_id'] = $id;
		$data['result_saved_searches'] = $result_saved_searches;
		$data['result_favorite'] = $result_favorite;
		$data['result_properties_viewed'] = $result_properties_viewed;
		$data['result_last_login'] = $result_last_login;
                $data['result_valuation_searched'] = $result_val_searched;
                $data['result_valuation_contact'] = $result_val_contact;
                $data['result_property_contact'] = $result_property_contact;
		
		$data['user_right']=$this->user_management_model->select_user_rights($this->user_session['user_id']);
		$data['msg1'] = $this->message_session1['msg'];
		///////////////////////////////////////////////////////////////////////////////////
		
		
		$data['main_content'] = "user/".$this->viewName."/view";       
	   	$this->load->view("user/include/template",$data);
	 }
	}
	/*
    @Description: Get Details property information
    @Author: Niral Patel
    @Input: - 
    @Output: -
    @Date: 21-01-2015
    */
	function property_listing()
	{
		$id = $this->input->post('id');
		$searchtext6='';$perpage6='';
		$sortfield6 = $this->input->post('sortfield6');
		$sortby6 = $this->input->post('sortby6');
		$perpage6 = trim($this->input->post('perpage6'));
		$allflag6 = $this->input->post('allflag6');
		
		if(!empty($sortfield6) && !empty($sortby6))
		{
			$data['sortfield6'] = $sortfield6;
			$data['sortby6'] = $sortby6;
		}
		else
		{
			$sortfield6 = 'plm.id';
			$sortby6 = 'desc';
			$data['sortfield6'] = $sortfield6;
			$data['sortby6'] = $sortby6;
		}
		$data['perpage6'] = 10;
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->viewName.'/property_listing');
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 3;
		//$uri_segment = $this->uri->segment(3);
		if(!empty($allflag6) && ($allflag6 == 'all' || $allflag6 == 'changesorting' || $allflag6 == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		$fields = array('plm.id,property_title','CONCAT_WS(" ",address_line_1,address_line_2) as address','city,mls_no,property_type_name,price,year_built,new_price,CASE WHEN new_price IS NOT NULL then new_price ELSE price END AS my_price,plm.created_date');
		$table = 'property_listing_contact_trans as plct';
		$join_tables = array(
							 'property_listing_master as plm' => 'plct.property_id = plm.id',
							 '(SELECT * FROM property_listing_price_change_trans ORDER BY id DESC) as plpct' => 'plpct.property_id = plm.id',
							 );
		$group_by = '';
		if(!empty($searchtext6))
		{
			//$match = array('CONCAT_WS(" ",address_line_1,address_line_2)'=>$searchtext6,'city'=>$searchtext6,'mls_no'=>$searchtext6,'property_type_name'=>$searchtext6,'price'=>$searchtext6,'year_built'=>$searchtext6);
			$match=array('contact_id'=>$id,'plct.created_by'=>$this->user_session['id']);
			$having = "address LIKE '%".$searchtext6."%' OR city LIKE '%".$searchtext6."%' OR mls_no LIKE '%".$searchtext6."%' OR property_type_name LIKE '%".$searchtext6."%' OR year_built LIKE '%".$searchtext6."%' OR my_price LIKE '%".$searchtext6."%' OR plm.created_date LIKE '%".date('Y-m-d',strtotime($searchtext6))."%'";
			
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield6'],$data['sortby6'],$group_by,'','','',$having);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','','1',$having);
			
				
		}
		else
		{
			$match=array('contact_id'=>$id,'plct.created_by'=>$this->user_session['id']);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield6'],$data['sortby6'],$group_by);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','','1');
		}
		$data['uri_segment6'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination6'] = $this->pagination->create_links();
		$this->load->view('user/'.$this->viewName.'/property_list',$data);	
	}
	  /*
        @Description: Get listing for Joomla contact register
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Contact register details
        @Date       : 03-12-2014
    */
    public function view_record_index()
    {
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield']		= 'cm.id';
        $data['sortby']			= 'desc';
        $allflag = $this->input->post('allflag');
                $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_register_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_register_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
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
                $sortfield = 'cm.id';
                $sortby = 'desc';
            }
        }
        if(!empty($searchtext))
        {
            $data['searchtext'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    /*$data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];
					
					*/$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	    			 $data['searchtext'] = $searchsort_session['searchtext'];

                }
            }
        }
        if(!empty($searchopt))
        {
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
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        $table = "contact_master as cm";
       $group_by = "cm.id";
       $wherestring = array('cm.id'=>$id);
        if(!empty($searchtext))
        {
            $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cm.created_date','cm.joomla_domain_name','cm.joomla_user_id','cm.created_by');
            $join_tables = array(
                '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
            );
            $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'joomla_domain_name'=>$searchtext,'cm.created_date'=>$searchtext);
            $data['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1');
        }
        else
        {
            $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cm.created_date','cm.joomla_domain_name','cm.joomla_user_id','cm.created_by');
            $join_tables = array(
                '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
            );
            $data['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
        }
        //exit;
       /* if(!empty($searchtext))
        {
                        $match=array('first_name'=>$searchtext);
                        $data['editRecord'] = $this->contacts_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
                        $config['total_rows'] = count($this->contacts_model->select_records('',$match,'','like',''));
        }
        else
        {

                        //echo "test"; exit;

                        //echo $sortfield;
                        //echo $sortby;

                        $match = array('id'=>$id);
                //$result = $this->obj->select_records('',$match,'','=');
                        $data['editRecord'] = $this->contacts_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
                        //echo $this->db->last_query();exit;
                        $config['total_rows']= count($this->contacts_model->select_records('',$match,'','='));


        }*/
        //pr($data['editRecord']);exit;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

                /*$match = array('id'=>$id);
                $userdata = $this->contacts_model->select_records('',$match,'','=');
                echo "3". $this->db->last_query();exit;*/

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
	
        $this->session->set_userdata('contact_register_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_contact_register',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }
    }
    
    /*
        @Description: Get Joomla Favorite property listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Favorite property listing
        @Date       : 03-12-2014
    */
    public function view_record_index_fav()
    {
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield2']		= 'id';
        $data['sortby2']		= 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_favorite_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_favorite_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            //$sortfield = $this->input->post('sortfield');
            $data['sortfield2'] = $sortfield;
            //$sortby = $this->input->post('sortby');
            $data['sortby2'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield2'] = $searchsort_session['sortfield'];
                    $data['sortby2'] = $searchsort_session['sortby'];
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
            //$searchtext3 = $this->input->post('searchtext3');
            $data['searchtext2'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext2'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext2'];
                }
            }
        }
        if(!empty($searchopt))
        {
            //$searchopt = $this->input->post('searchopt');
            $data['searchopt2'] = $searchopt;
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
            $data['perpage2'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage2'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_fav/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('mlsid'=>$searchtext,'propery_name'=>$searchtext,'domain'=>$searchtext,'date'=>$searchtext);
            $data['result_favorite'] = $this->favorite_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->favorite_model->select_records('',$match,'','like','','','','','',$where_clause,'1');

            /*if(is_numeric($searchtext))
            {
                    $match=array('mlsid'=>$searchtext,'propery_name'=>$searchtext,'domain'=>$searchtext,'date'=>$searchtext);
                    $data['result_favorite']  = $this->favorite_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
                    //echo $this->db->last_query();exit;
            }
            else
            {
                    $where_clause=array('lw_admin_id'=>$id);
                    $match=array('propery_name'=>$searchtext,'domain'=>$searchtext);
                    $data['result_favorite'] = $this->favorite_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'',$sortby,$where_clause);
                    //echo $this->db->last_query();exit;
            }

            $config['total_rows'] = count($this->favorite_model->select_records('',$match,'','like',''));*/
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_favorite'] = $this->favorite_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->favorite_model->select_records('',$match,'','=','','','','','','','1');

        }
        $this->pagination->initialize($config);
        $data['pagination2'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
           	'sortfield'  => !empty($data['sortfield2'])?$data['sortfield2']:'',
			'sortby' =>!empty($data['sortfield2'])?$data['sortfield2']:'',
			'searchtext' =>!empty($data['searchtext2'])?$data['searchtext2']:'',
			'perpage' => !empty($data['perpage2'])?trim($data['perpage2']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_favorite_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment2'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_favorite',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }

    }
    
    /*
        @Description: Get Joomla Saved searches property listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Saved searches property listing
        @Date       : 03-12-2014
    */
    public function view_record_index_savser()
    {
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield1']		= 'id';
        $data['sortby1']		= 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');
        $search_id = $this->input->post('search_id'); // From Popup view

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_savedsearch_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_savedsearch_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield1'] = $sortfield;
            $data['sortby1'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield1'] = $searchsort_session['sortfield'];
                    $data['sortby1'] = $searchsort_session['sortby'];
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
            $data['searchtext1'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext1'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext1'];
                }
            }
        }
        if(!empty($searchopt))
        {
            $data['searchopt1'] = $searchopt;
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
            $data['perpage1'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage1'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_savser/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('name'=>$searchtext,'domain'=>$searchtext,'created_date'=>$searchtext,
                'min_price'=>$searchtext,'max_price'=>$searchtext,'bedroom'=>$searchtext,'bathroom'=>$searchtext,'min_area'=>$searchtext,'max_area'=>$searchtext,
                'min_year_built'=>$searchtext,'max_year_built'=>$searchtext,'fireplaces_total'=>$searchtext,'min_lotsize'=>$searchtext,'max_lotsize'=>$searchtext,'garage_spaces'=>$searchtext);
            //$match=array('name'=>$searchtext,'search_criteria'=>$searchtext,'domain'=>$searchtext,'created_date'=>$searchtext);
            $data['result_saved_searches'] = $this->saved_searches_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->saved_searches_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            if(!empty($search_id)) {
                $match = array('id'=>$search_id);
            }
            else {
                $match = array('lw_admin_id'=>$id);
            }
            $data['result_saved_searches'] = $this->saved_searches_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows']= $this->saved_searches_model->select_records('',$match,'','=','','','','','','','1');
        }
        $this->pagination->initialize($config);
        $data['pagination1'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        ////////////
        $parent_db = $this->config->item('parent_db_name');
        $match = array('status'=>'1');
        $property_type =$this->obj->getmultiple_tables_records($parent_db.'.mls_property_type','','','',$match,'');
        $pro_arr = array();
        if(!empty($property_type))
        {
            foreach($property_type as $pro_row)
            {
                $pro_arr[$pro_row['name']] = $pro_row['comment'];
            }
        }
        $data['property_type'] = $pro_arr;
        
        if(!empty($data['result_saved_searches']))
        {
            foreach($data['result_saved_searches'] as $all_row)
            {
                $fields = array('code,value_description');
                $where ='';
                if(!empty($all_row['parking_type']))
                    $where .= '(code = "GR" AND value_code = "'.$all_row['parking_type'].'") OR ';
                if(!empty($all_row['architecture']))
                    $where .= '(code = "ARC" AND value_code = "'.$all_row['architecture'].'") OR ';
                if(!empty($all_row['waterfront']))
                    $where .= '(code = "WFT" AND value_code = "'.$all_row['waterfront'].'") OR ';
                if(!empty($all_row['s_view']))
                    $where .= '(code = "VEW" AND value_code = "'.$all_row['s_view'].'") OR ';
                /*if(!empty($all_row['new_construction']))
                    $where .= '(code = "NC" AND value_code = "'.$all_row['new_construction'].'") OR ';
                 */
                if(!empty($all_row['short_sale']))
                    $where .= '(code = "PARQ" AND value_code = "'.$all_row['short_sale'].'") OR ';
                
                $where = trim($where,' OR');
                if(!empty($where)) {
                    $ame_data[$all_row['id']] = $this->obj->getmultiple_tables_records($parent_db.'.mls_amenity_data',$fields,'','','','','','','','','','code',$where);
                }
                
                if(!empty($all_row['school_district'])) {
                    $where1 = 'school_district_code = "'.$all_row['school_district'].'"';
                    $fields = array("school_district_code,school_district_description");
                    $school_data[$all_row['id']] = $this->obj->getmultiple_tables_records($parent_db.'.mls_school_data',$fields,'','','','','','','','','','school_district_code',$where1);
                }
            }
        }
        $data['ame_data'] = !empty($ame_data)?$ame_data:'';
        $data['school_data'] = !empty($school_data)?$school_data:'';
        ////////////
        
        if(empty($search_id))
        {
            $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
            'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
            'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
            'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
            $this->session->set_userdata('contact_savedsearch_sortsearchpage_data', $contacts_sortsearchpage_data);
        }
        $data['uri_segment1'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            if(!empty($search_id))
                $this->load->view($this->user_type.'/'.$this->viewName.'/view_saved_searches_popup',$data);
            else
                $this->load->view($this->user_type.'/'.$this->viewName.'/view_saved_searches',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }

    }
    
    /*
        @Description: Get Joomla Properties Viewed listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Properties Viewed listing
        @Date       : 03-12-2014
    */
    public function view_record_index_prop_view()
    {
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield3']		= 'id';
        $data['sortby3']		= 'desc';
        $allflag = $this->input->post('allflag');
                $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_propviewed_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_propviewed_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield3'] = $sortfield;
            $data['sortby3'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield3'] = $searchsort_session['sortfield'];
                    $data['sortby3'] = $searchsort_session['sortby'];
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
            $data['searchtext3'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext3'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext3'];
                }
            }
        }
        if(!empty($searchopt))
        {
            $data['searchopt3'] = $searchopt;
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
            $data['perpage3'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage3'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_prop_view/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('mlsid'=>$searchtext,'propery_name'=>$searchtext,'views'=>$searchtext,'domain'=>$searchtext,'log_date'=>$searchtext);
            $data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->properties_viewed_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
            /*$match=array('mlsid'=>$searchtext,'propery_name'=>$searchtext,'views'=>$searchtext,'domain'=>$searchtext,'log_date'=>$searchtext);
            $data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config['total_rows'] = count($this->properties_viewed_model->select_records('',$match,'','like',''));*/

        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows']= $this->properties_viewed_model->select_records('',$match,'','=','','','','','','','1');
        }
        $this->pagination->initialize($config);
        $data['pagination3'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield3'])?$data['sortfield3']:'',
			'sortby' =>!empty($data['sortby3'])?$data['sortby3']:'',
			'searchtext' =>!empty($data['searchtext3'])?$data['searchtext3']:'',
			'perpage' => !empty($data['perpage3'])?trim($data['perpage3']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_propviewed_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment3'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_properties_viewed',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }

    }
    
    /*
        @Description: Get Joomla Last Login listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Last Login listing
        @Date       : 03-12-2014
    */
    public function view_record_index_lastlog()
    {
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield4']		= 'id';
        $data['sortby4']			= 'desc';
        $allflag = $this->input->post('allflag');
                $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_lastlogin_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('contact_lastlogin_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            //$sortfield = $this->input->post('sortfield');
            $data['sortfield4'] = $sortfield;
            //$sortby = $this->input->post('sortby');
            $data['sortby4'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield4'] = $searchsort_session['sortfield'];
                    $data['sortby4'] = $searchsort_session['sortby'];
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
            $data['searchtext4'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext5'])) {
                    $data['searchtext4'] = $searchsort_session['searchtext5'];
                    $searchtext =  $data['searchtext4'];
                }
            }
        }
        if(!empty($searchopt))
        {
            //$searchopt = $this->input->post('searchopt');
            $data['searchopt4'] = $searchopt;
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
            $data['perpage4'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage4'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_lastlog/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        //$config['uri_segment'] = 3;
        //$uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        if(!empty($searchtext))
        {
            /*OLD 29-10-2014
             * 
             * if(is_numeric($searchtext)){
                    $match = array('lw_admin_id'=>$id,'log_date'=>$searchtext,'ip'=>$searchtext,'domain'=>$searchtext);

                    $data['result_last_login'] = $this->last_login_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            }else{
                    $where_clause=array('lw_admin_id'=>$id);
                    $match = array('domain'=>$searchtext);
                    $data['result_last_login'] = $this->last_login_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'',$sortby,$where_clause);
                    //echo $this->db->last_query();exit;
            }
            $config['total_rows'] = count($this->last_login_model->select_records('',$match,'','like',''));

             END OLD */
            $where_clause=array('lw_admin_id'=>$id);
            $match = array('log_date'=>$searchtext,'ip'=>$searchtext,'domain'=>$searchtext);
            $data['result_last_login'] = $this->last_login_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->last_login_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_last_login'] = $this->last_login_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->last_login_model->select_records('',$match,'','=','','','','','','','1');
        }
        $this->pagination->initialize($config);
        $data['pagination4'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield4'])?$data['sortfield4']:'',
			'sortby' =>!empty($data['sortby4'])?$data['sortby4']:'',
			'searchtext' =>!empty($data['searchtext4'])?$data['searchtext4']:'',
			'perpage' => !empty($data['perpage4'])?trim($data['perpage4']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_lastlogin_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment4'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_last_login',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }
    }
        
    /*
        @Description: Get Joomla Valuation Searched listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Valuation Searched listing
        @Date       : 02-12-2014
    */
    public function view_record_index_val_searched()
    {
        $searchopt='';$searchtext='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield5']		= 'id';
        $data['sortby5']		= 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_val_searched_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_val_searched_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield5'] = $sortfield;
            $data['sortby5'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield5'] = $searchsort_session['sortfield'];
                    $data['sortby5'] = $searchsort_session['sortby'];
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
            $data['searchtext5'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext5'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext5'];
                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage5'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage5'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_val_searched/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('search_address'=>$searchtext,'city'=>$searchtext,'state'=>$searchtext,'zip_code'=>$searchtext,'domain'=>$searchtext,'report_timeline'=>$searchtext,'send_report'=>$searchtext,'date'=>$searchtext);
            $data['result_valuation_searched'] = $this->property_valuation_searches_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->property_valuation_searches_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_valuation_searched'] = $this->property_valuation_searches_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->property_valuation_searches_model->select_records('',$match,'','=','','','','','','','1');

        }
        $this->pagination->initialize($config);
        $data['pagination5'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield5'])?$data['sortfield5']:'',
			'sortby' =>!empty($data['sortby5'])?$data['sortby5']:'',
			'searchtext' =>!empty($data['searchtext5'])?$data['searchtext5']:'',
			'perpage' => !empty($data['perpage5'])?trim($data['perpage5']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_val_searched_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment2'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_valuation_searched',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }
    }
    
    /*
        @Description: Get Joomla Valuation contact from listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Valuation contact form listing
        @Date       : 10-03-2015
    */
    public function view_record_index_val_contact()
    {
        $searchopt='';$searchtext='';$searchoption='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield110']		= 'id';
        $data['sortby110']		= 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_val_contactfrom_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_val_contactform_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield110'] = $sortfield;
            $data['sortby110'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield110'] = $searchsort_session['sortfield'];
                    $data['sortby110'] = $searchsort_session['sortby'];
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
            $data['searchtext110'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext110'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage110'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage110'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_valuation_contact/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('property_name'=>$searchtext,'domain'=>$searchtext,'name'=>$searchtext,'email'=>$searchtext,'phone'=>$searchtext);
            $data['result_valuation_contact'] = $this->property_valuation_contact_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->property_valuation_contact_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_valuation_contact'] = $this->property_valuation_contact_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows']= $this->property_valuation_contact_model->select_records('',$match,'','=','','','','','','','1');
        }
        $this->pagination->initialize($config);
        $data['pagination110'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield110'])?$data['sortfield110']:'',
            'sortby' =>!empty($data['sortby110'])?$data['sortby110']:'',
            'searchtext' =>!empty($data['searchtext110'])?$data['searchtext110']:'',
            'perpage' => !empty($data['perpage110'])?trim($data['perpage110']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_val_contactform_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment110'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_valuation_contact',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }
    }
    
    /*
        @Description: Get Joomla property contact from listing
        @Author     : Sanjay Moghariya
        @Input      : Contact id, search criteria(if any), sorting fields
        @Output     : Valuation contact form listing
        @Date       : 10-03-2015
    */
    public function view_record_index_property_contact()
    {
        $searchopt='';$searchtext='';$searchoption='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield111']		= 'id';
        $data['sortby111']		= 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_property_contactfrom_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('contact_property_contactform_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield111'] = $sortfield;
            $data['sortby111'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield111'] = $searchsort_session['sortfield'];
                    $data['sortby111'] = $searchsort_session['sortby'];
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
            $data['searchtext111'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext111'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage111'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage111'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'."contacts/view_record_index_property_contact/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('property_name'=>$searchtext,'domain'=>$searchtext,'name'=>$searchtext,'email'=>$searchtext,'phone'=>$searchtext,'preferred_time'=>$searchtext);
            $data['result_property_contact'] = $this->property_contact_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->property_contact_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_property_contact'] = $this->property_contact_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            //echo $this->db->last_query();exit;
            $config['total_rows']= $this->property_contact_model->select_records('',$match,'','=','','','','','','','1');
        }
        
        $this->pagination->initialize($config);
        $data['pagination111'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield111'])?$data['sortfield111']:'',
            'sortby' =>!empty($data['sortby111'])?$data['sortby111']:'',
            'searchtext' =>!empty($data['searchtext111'])?$data['searchtext111']:'',
            'perpage' => !empty($data['perpage111'])?trim($data['perpage111']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('contact_property_contactform_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment111'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/view_property_contact',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }
    }
	
	function send_twitter_message()
	{
		//pr($_POST);exit;
		 $this->load->library('twitteroauth/twitteroauth');
		 $consumer_key=$this->config->item('twitter_access_key_user');
		 $consumer_secret=$this->config->item('twitter_secret_key_user');
		 $field = array('id','twitter_access_token','twitter_access_token_secret');
		 $match = array('id'=>$this->user_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		
		 $twitter_access_token=!empty($udata[0]['twitter_access_token'])?$udata[0]['twitter_access_token']:'';
		 $twitter_access_token_secret=!empty($udata[0]['twitter_access_token_secret'])?$udata[0]['twitter_access_token_secret']:'';
		 $submit_twit = $this->input->post('submit_twit');
		 $submit_direct = $this->input->post('submit_direct');
		 $contactid = $this->input->post('id');
		 if(!empty($twitter_access_token) && !empty($twitter_access_token_secret))
		 {
			 
		 $template_id = $this->input->post('template_name');
		 $screen_name = $this->input->post('screen_name');
		 $email_message =$this->input->post('email_message');
		 $email_message =substr($email_message,0,130);
		 $platform = $this->input->post('platform');
		
		 if(!empty($platform) && $platform == '2')
		 {
			// Initialize the connection
			$connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret);
			
			//$check=$connection->post('direct_messages/new', array('user_id' => 'demotops', 'text' => $msg));
			if(isset($submit_direct) && !empty($submit_direct))
			{
				$params4 = array();
				$params4['screen_name'] = $screen_name;
				$params4['text'] = "@$screen_name"." ".$email_message;
		
				$content4 = $connection->post('direct_messages/new', $params4);
				//$content4 =  $connection->post('statuses/update', array('status' =>$email_message));
				
				//pr($content4);exit;
				//echo $content4->errors[0]->message;
				$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
				//pr($content4);exit;
				if(!empty($error))
				{
					 $msg = $error;
					 $newdata = array('msg'  => $msg);
					 $this->session->set_userdata('message_session1', $newdata);
					 redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
				}
				else
				{
					$data['contact_id']=$contactid;
					$data['msg']="@$screen_name"." ".$email_message;	
					$data['msg_date_time']= date('Y-m-d H:i:s');	
					$data['inserted_date_time']= date('Y-m-d H:i:s');	
					$data['type']='2';
					$data['twitter_message_type']='1';
					$data['created_by']=$this->user_session['id'];
					$this->contacts_model->insert_chat_history($data);
				}
			}
			if(isset($submit_twit) && !empty($submit_twit))
			{
				$params4 = array();
				$params4['screen_name'] = $screen_name;
				$params4['status'] = $email_message;
		
				//$content4 = $connection->post('direct_messages/new', $params4);
				$content4 =  $connection->post('statuses/update',  $params4);
				
				//pr($content4);exit;
				//echo $content4->errors[0]->message;
				$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
				//pr($content4);exit;
				if(!empty($error))
				{
					 $msg = $error;
					 $newdata = array('msg'  => $msg);
					 $this->session->set_userdata('message_session1', $newdata);
					 redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
				}
				else
				{
					$data['contact_id']=$contactid;
					$data['msg']= $email_message;	
					$data['msg_date_time']= date('Y-m-d H:i:s');	
					$data['inserted_date_time']= date('Y-m-d H:i:s');	
					$data['type']='2';
					$data['twitter_message_type']='2';
					$data['created_by']=$this->user_session['id'];
					$this->contacts_model->insert_chat_history($data);
				}
			}
		}
		redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
	
		}
		 else
		 {	 
				 $msg = 'You are not connected with twitter account.Please connect your twitter account.';
				 $newdata = array('msg'  => $msg);
				 $this->session->set_userdata('message_session1', $newdata);
				 
				 redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
		 }
	}
	/*
		@Description: Function for send message via linked in
		@Author: Niral Patel
		@Input: - Template Id
		@Output: - 
		@Date: 14-11-2014
   	*/
	function sendlinked_message()
	{
		//pr($_POST);
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->user_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		 $contactid = $this->input->post('id');
		 if(!empty($linkedin_access_token))
		 {
			 $template_id = $this->input->post('template_name');
			 $subject = $this->input->post('txt_template_subject');
			 $email_message = $this->input->post('email_message');
			 $platform = $this->input->post('platform');
			 if(!empty($contactid))
			 {
				
					$fields = array('id,linkedin_id');
					$match = array('id'=>$contactid);
					$result = $this->contacts_model->select_records($fields,$match,'','=');	 
					$linkedin_id=!empty($result[0]['linkedin_id'])?$result[0]['linkedin_id']:'';
				if(!empty($linkedin_id))
				{
					 $this->data['consumer_key'] = $this->config->item('linkedin_api_key_user');
					 $this->data['consumer_secret'] = $this->config->item('linkedin_secret_key_user');
					 
					 $this->load->library('linkedin/linkedin', $this->data);
					//echo 'OAuthConsumer[key=786x4nevnmcvc4,secret=Hj8kmrJct9iR2ziE]';
					// Start document
					/*$xml = new DOMDocument('1.0', 'utf-8');
					
					// Create element for recipients and add each recipient as a node
					$elemRecipients = $xml->createElement('recipients');
					
					// Create person node
				
					$person = $xml->createElement('person');
					$person->setAttribute('path', '/people/' . (string) $linkedin_id);
					
					// Create recipient node
					$elemRecipient = $xml->createElement('recipient');
					$elemRecipient->appendChild($person);
					
					// Add recipient to recipients node
					$elemRecipients->appendChild($elemRecipient);
				
					
					
					// Create mailbox node and add recipients, body and subject
					$elemMailbox = $xml->createElement('mailbox-item');
					$elemMailbox->appendChild($elemRecipients);
					$elemMailbox->appendChild($xml->createElement('body', ($email_message)));
					$elemMailbox->appendChild($xml->createElement('subject', ($subject)));
					
					// Append parent node to document
					$xml->appendChild($elemMailbox);*/
					$status_response = $this->linkedin->message($linkedin_id, $subject, $email_message,unserialize($linkedin_access_token));
					$data['contact_id']=$contactid;
					$data['msg']= $email_message;	
					$data['msg_date_time']= date('Y-m-d H:i:s');	
					$data['inserted_date_time']= date('Y-m-d H:i:s');	
					$data['type']='3';
					$data['created_by']=$this->user_session['id'];
					$this->contacts_model->insert_chat_history($data);
					//$okdata=$this->message($subject,$email_message,array($linkedin_id));	
					if($status_response == '201')
					{
							/*$data['contact_id']=$contactid;
							$data['msg']= $email_message;	
							$data['msg_date_time']= date('Y-m-d H:i:s');	
							$data['inserted_date_time']= date('Y-m-d H:i:s');	
							$data['type']='3';
							$data['created_by']=$this->user_session['id'];
							$this->contacts_model->insert_chat_history($data);*/
							
							
					}
					else
					{
						$msg = $status_response;
						$newdata = array('msg'  => $msg);
						$this->session->set_userdata('message_session1', $newdata);
						//redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
					}
				}
			}
		 }
		 else
		 {
			
			 $msg = 'You are not connected with linked in account.Please connect your linked in account';
			 $newdata = array('msg'  => $msg);
			 $this->session->set_userdata('message_session1', $newdata);
			//redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
				
		 }
		 redirect('user/contacts/view_record/'.$contactid.'/7#myTab2');
	}
		/*
		@Description: Function for sending linked in invitation
		@Author: Mohit Trivedi
		@Input: - Email Id
		@Output: - 
		@Date: 27-11-2014
   	*/
	function sendlinked_invitation()
	{
		 
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->user_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		 $contactid = $this->router->uri->segments[4];
		 if(!empty($linkedin_access_token))
		 {
			 if(!empty($contactid))
			 {
					$fields = array('id,contact_id,email_address');
					$match = array('contact_id'=>$contactid,'is_default'=>'1');
					$result = $this->contacts_model->select_records5($fields,$match,'','=');	 
					//pr($result);exit;
					$email=!empty($result[0]['email_address'])?$result[0]['email_address']:'';
					
				if(!empty($email))
				{
					$this->data['consumer_key'] = $this->config->item('linkedin_api_key');
					$this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
					
					$this->load->library('linkedin/linkedin', $this->data);
					$status_response = $this->linkedin->linkedin_invitation($email,unserialize($linkedin_access_token));
					
					$data['contact_id']=$this->router->uri->segments[4];
					$data['user_id']=$this->user_session['id'];
					$data['create_date']= date('Y-m-d H:i:s');
					$this->contacts_model->insert_linkedin_invitation($data);
					redirect('user/contacts/view_record/'.$contactid.'/7#myTab1');
				}
			}
		 }
		 else
		 {
			 $msg = 'You are not connected with linked in account.Please connect your linked in account';
			 $newdata = array('msg'  => $msg);
			 $this->session->set_userdata('message_session1', $newdata);
			redirect('user/contacts/view_record/'.$contactid.'/7#myTab1');
				
		 }
	}

	function message($subject, $body, $recipients)
	{
	
	
	
	// Start document
	$xml = new DOMDocument('1.0', 'utf-8');
	
	// Create element for recipients and add each recipient as a node
	$elemRecipients = $xml->createElement('recipients');
	foreach ($recipients as $recipient) {
	// Create person node

	$person = $xml->createElement('person');
	$person->setAttribute('path', '/people/' . (string) $recipient);
	
	// Create recipient node
	$elemRecipient = $xml->createElement('recipient');
	$elemRecipient->appendChild($person);
	
	// Add recipient to recipients node
	$elemRecipients->appendChild($elemRecipient);
	
	}
	
	
	// Create mailbox node and add recipients, body and subject
	$elemMailbox = $xml->createElement('mailbox-item');
	$elemMailbox->appendChild($elemRecipients);
	$elemMailbox->appendChild($xml->createElement('body', ($body)));
	$elemMailbox->appendChild($xml->createElement('subject', ($subject)));
	
	// Append parent node to document
	$xml->appendChild($elemMailbox);
	
	$response = $this->fetch('POST','/v1/people/~/mailbox', $xml->saveXML());
	
	return ($response);
	}
	function fetch($method, $resource, $body = '') 
	 {
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->user_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		$params = array('oauth2_access_token' => $linkedin_access_token,
		'format' => 'json',
		);
		
		// Need to use HTTPS
		$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
		// Tell streams to make a (GET, POST, PUT, or DELETE) request
		$context = stream_context_create(
				array('http' =>
					array('method' => $method,
						'header'=> "Content-Type:text/xml\r\n"
							. "Content-Length: " . strlen($body) . "\r\n",
						'content' => ($body)
					)
				)
			);
		/*$context = stream_context_create(
		array('http' =>
		array('method' => $method,
		)
		)
		);
		*/
		// Hocus Pocus
		$response = file_get_contents($url, false, $context);
		// Native PHP object, please
		return json_decode($response);
	}
	/*
    @Description: Get Details mapping Field Name
    @Author: Kaushik valiya
    @Input: - onchange event to get mapping Field
    @Output: - List Of Mapping Field
    
    */
	public function get_filed_list()
    { 
			$csv_mapping_id=$this->input->post('mapping_id');
			$match = array("csv_mapping_id"=>$csv_mapping_id);
        	$field_list = $this->obj1->select_records1('',$match,'','=','','','','','asc','contact__csv_mapping_trans');
			//pr($field_list);
			echo json_encode($field_list);
				
	}
	
	/*
    @Description: Function to delete Contact list
	@Author: Kaushik Valiya
    @Input: Array
    @Output: - 
    @Date: 16-07-2014
    */
	public function ajax_delete_all()
	{

		$id=$this->input->post('single_remove_id');
		$email_id = $this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$temp_flag='';
		$delete_all_flag = 0;$cnt = 0;
		$right_list =$this->obj->select_user_rights_trans_edit_record($this->user_session['user_id']);
		foreach($right_list as $row)
		{
			if($row['rights_id'] == 2){
				$temp_flag = 1;
				break;
			}
		}
	
		if($temp_flag == 1)////This Condition not Right for Delete
		{
			
			if(!empty($id))
			{
				if($this->input->post('archive') == 'archive')
				{
					$match = array('id'=>$id);
					$result = $this->obj->select_archive_records('',$match,'','=');
				}
				else
				{
					$match = array('id'=>$id);
					$result = $this->obj->select_records('',$match,'','=');
				}
				$bgImgPath = $this->config->item('contact_big_img_path');
				$smallImgPath = $this->config->item('contact_small_img_path');
				if(!empty($result[0]['contact_pic']))
				{
					unlink($bgImgPath.$result[0]['contact_pic']);
					unlink($smallImgPath.$result[0]['contact_pic']);
				}
				
				/////////////// Delete Doc File and Tran table Data/////////////
				
				$result_doc=$this->obj->select_document_trans_record_contact_id($id);
				$bgImgPath_doc = $this->config->item('contact_documents_img_path');
				for($k=0;$k<count($result_doc);$k++)
				{	
					if(!empty($result_doc[$k]['doc_file']))
					{
						unlink($bgImgPath_doc.$result_doc[$k]['doc_file']);
					}
				}
				unset($k);
				
				if($this->input->post('archive') == 'archive')
				{
					$this->obj->delete_archive_record($id);
				}
				else
				{
					$this->obj->delete_record($id);
				}
				
				////// Delete All Trans table Recored/////////
				
				$this->obj->delete_all_trans_table_record($id,'contact_documents_trans');
				$this->obj->delete_all_trans_table_record($id,'user_contact_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_emails_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_phone_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_address_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_website_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_social_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_contacttype_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_tag_trans');
				$this->obj->delete_all_trans_table_record($id,'contact_contact_status_trans');
				
				$this->obj->delete_all_trans_table_record($id,'email_campaign_recepient_trans');
				$this->obj->delete_all_trans_table_record($id,'sms_campaign_recepient_trans');
				
				//////////////////////////
				
				$this->obj->delete_all_trans_table_record($id,'contact_conversations_trans');
				$this->obj->delete_all_trans_table_record($id,'interaction_plan_contact_personal_touches');
				
				$this->obj->delete_all_trans_table_record($id,'interaction_plan_contacts_trans');
				$this->obj->delete_all_trans_table_record($id,'interaction_plan_contact_communication_plan');
                                
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_bookmarks',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_log',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_savesearch',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_track',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_property_valuation_searches',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_property_contact',$id);
                                $this->obj->delete_all_trans_table_record('','joomla_rpl_valuation_contact',$id);
				
				unset($id);
			}
			elseif(!empty($array_data))
			{
				$delete_all_flag = 1;
				for($i=0;$i<count($array_data);$i++)
				{
					$cnt++;
					$match = array('id'=>$array_data[$i]);
					if($this->input->post('archive') == 'archive')
					{
						$match = array('id'=>$array_data[$i]);
						$result = $this->obj->select_archive_records('',$match,'','=');
					}
					else
					{
						$match = array('id'=>$array_data[$i]);
						$result = $this->obj->select_records('',$match,'','=');
					}
					$bgImgPath = $this->config->item('contact_big_img_path');
					$smallImgPath = $this->config->item('contact_small_img_path');
					if(!empty($result[0]['contact_pic']))
					{
						unlink($bgImgPath.$result[0]['contact_pic']);
						unlink($smallImgPath.$result[0]['contact_pic']);
					}
					
					if($this->input->post('archive') == 'archive')
					{
						$this->obj->delete_archive_record($array_data[$i]);
					}
					else
					{
						$this->obj->delete_record($array_data[$i]);
					}
					$result_doc=$this->obj->select_document_trans_record_contact_id($array_data[$i]);
					$bgImgPath_doc = $this->config->item('contact_documents_img_path');
					for($k=0;$k<count($result_doc);$k++)
					{	
						if(!empty($result_doc[$k]['doc_file']))
						{
							unlink($bgImgPath_doc.$result_doc[$k]['doc_file']);
						}
					}
					
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_documents_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'user_contact_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_emails_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_phone_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_address_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_website_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_social_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_contacttype_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_tag_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_contact_status_trans');

					$this->obj->delete_all_trans_table_record($array_data[$i],'email_campaign_recepient_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'sms_campaign_recepient_trans');
					
					//////////////////////////
					
					$this->obj->delete_all_trans_table_record($array_data[$i],'contact_conversations_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_personal_touches');
					
					$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contacts_trans');
					$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_communication_plan');
                                        
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_bookmarks',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_log',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_savesearch',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_track',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_property_valuation_searches',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_property_contact',$array_data[$i]);
                                        $this->obj->delete_all_trans_table_record('','joomla_rpl_valuation_contact',$array_data[$i]);
					
					//////////////////////////
					}
				}
			
			}else
			{
				$data_pass['msg'] = $this->lang->line('common_delete_right_msg');
			}	
			
			///// Pagination set 
				 if($this->input->post('archive') == 'archive')
						$searchsort_session = $this->session->userdata('contact_view_archive_sortsearchpage_data');
					else
						$searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');
					if(!empty($searchsort_session['uri_segment']))
						$pagingid = $searchsort_session['uri_segment'];
					else
						$pagingid = 0;
					
					$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
					$total_rows = $searchsort_session['total_rows'];
					if($delete_all_flag == 1)
					{
						$total_rows -= $cnt;
						if($cnt > $perpage)
						{
							while($pagingid >= $total_rows)
								$pagingid -= $perpage;
						}
						else
						{
							if($pagingid*$perpage > $total_rows) {
								if($total_rows % $perpage == 0)
								{
									$pagingid -= $perpage;
								}
							}
						}
					} else {
						if($total_rows % $perpage == 1)
							$pagingid -= $perpage;
					}
					if($pagingid < 0)
						$pagingid = 0;
						
		$data_pass['pagingid']=$pagingid;
		
		echo json_encode($data_pass);
	}
	/*
    @Description: Function to Addign Contact list
	@Author: Kaushik Valiya
    @Input: Array
    @Output: - 
    @Date: 16-07-2014
    */
	public function assign_contact()
	{
		
		$array_data=$this->input->post('myarray');
		$user_id=$this->input->post('user_id');
		$msg='';
		for($i=0;$i<count($array_data);$i++)
		{
			$check_contact_id=$array_data[$i];
			$match=array('contact_id'=>$check_contact_id);
			$contact_check = $this->obj1->select_records1('',$match,'','=','','','','','asc', 'user_contact_trans');
			if(empty($contact_check))
			{
				$data['contact_id']=$check_contact_id;
				$data['user_id']=$user_id;
				$data['created_by'] = $this->user_session['id'];
				$data['created_date'] = date('Y-m-d H:i:s');		
				$data['status'] = '1';
				$this->obj->insert_user_contact_trans_record($data);
				$msg= 1;
			}
			else
			{
			
				$msg=0;
			}
			
		}
		echo $msg;
	}
	
	/*
    @Description: Function to get contact data for Merge popup
	@Author: Nishit Modi
    @Input: Array
    @Output: - 
    @Date: 17-07-2014
    */
	
	public function get_merge_contact_data_ajax()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts'] = $this->obj->get_record_where_in($contacts);
		$data['crmfields'] = array(
									array($this->lang->line('contact_add_prefix'),'prefix'),
									array($this->lang->line('contact_add_fname'),'first_name'),
									array($this->lang->line('contact_add_mname'),'middle_name'),
									array($this->lang->line('contact_add_lname'),'last_name'),
									array($this->lang->line('contact_add_company'),'company_name'),
									array($this->lang->line('common_label_address1'),'address1'),
									array($this->lang->line('common_label_address2'),'address2'),
									array($this->lang->line('common_label_city'),'city'),
									array($this->lang->line('common_label_state'),'state'),
									array($this->lang->line('common_label_contact_type'),'contact_type'),
									array($this->lang->line('common_label_contact_source'),'contact_source'),
									array('Is contact Lead','islead'),
									array($this->lang->line('common_label_contact_default_email'),'email1'),
									array($this->lang->line('common_label_email')." 2",'email2'),
									array($this->lang->line('common_label_email')." 3",'email3'),
									array($this->lang->line('common_label_contact_default_phone'),'phone1'),
									array($this->lang->line('common_label_phone')." 2",'phone2'),
									array($this->lang->line('common_label_phone')." 3",'phone3'),
								);
		//pr($data['crmfields']);exit;
		
		$this->load->view($this->user_type.'/'.$this->viewName."/merge_contact_popup_list",$data);
		
		//echo json_encode($data);
	}
	
	/*
    @Description: Function to insert merge data
	@Author: Nishit Modi
    @Input: Array
    @Output: - 
    @Date: 17-07-2014
    */
	
	public function insert_merge_data()
	{
		$array_data = $this->input->post('old_contacts');
		if(!empty($array_data))
		{
			$table = 'contact_master';
			$where_in = array('id'=>$array_data);
			$fields = array('id,notes');
			$old_notes = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','','','','',$where_in);
			if(!empty($old_notes))
			{
				foreach($old_notes as $row)
					$cdata['notes'] .= $row['notes']." ";
			}
		}
		
		$cdata['prefix'] = $this->input->post('radio-prefix');
		$cdata['first_name'] = $this->input->post('radio-first_name');
		$cdata['middle_name'] = $this->input->post('radio-middle_name');
		$cdata['last_name'] = $this->input->post('radio-last_name');
		$cdata['company_name'] = $this->input->post('radio-company_name');
		$cdata['is_lead'] = $this->input->post('radio-islead');
		$cdata['contact_source'] = $this->input->post('radio-contact_source');
		$cdata['created_type'] = '1';
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['status'] = '1';
            
		$contact_id = $this->obj->insert_record($cdata);	
		//echo $this->db->last_query();exit;
		unset($cdata);
		
		$alladdressline1 = $this->input->post('radio-address1');
		$alladdressline2 = $this->input->post('radio-address2');
		$alladdresscity = $this->input->post('radio-city');
		$alladdressstate = $this->input->post('radio-state');
		
		if(trim($alladdressline1) != "" || trim($alladdressline2) != "" || trim($alladdresscity) != "" || trim($alladdressstate) != "")
		{
			$cadata['contact_id'] = $contact_id;
			$cadata['address_line1'] = $alladdressline1;
			$cadata['address_line2'] = $alladdressline2;
			$cadata['city'] = $alladdresscity;
			$cadata['state'] = $alladdressstate;
			$cadata['status'] = '1';
			
			$this->obj->insert_address_trans_record($cadata);
			
			unset($cadata);
		}
		
		$allemailaddress = $this->input->post('radio-default-email');
		
		if(trim($allemailaddress) != "")
		{
			$cmdata['contact_id'] = $contact_id;
			$cmdata['email_address'] = $allemailaddress;
			$cmdata['is_default'] = '1';
			$cmdata['status'] = '1';
			
			$this->obj->insert_email_trans_record($cmdata);
			
			unset($cmdata);
		}
		
		$allemailaddress1 = $this->input->post('radio-email2');
		
		if(trim($allemailaddress1) != "")
		{
			$cmdata['contact_id'] = $contact_id;
			$cmdata['email_address'] = $allemailaddress1;
			$cmdata['is_default'] = '0';
			$cmdata['status'] = '1';
			
			$this->obj->insert_email_trans_record($cmdata);
			
			unset($cmdata);
		}
		
		$allemailaddress2 = $this->input->post('radio-email3');
		
		if(trim($allemailaddress2) != "")
		{
			$cmdata['contact_id'] = $contact_id;
			$cmdata['email_address'] = $allemailaddress2;
			$cmdata['is_default'] = '0';
			$cmdata['status'] = '1';
			
			$this->obj->insert_email_trans_record($cmdata);
			
			unset($cmdata);
		}
		
		$allphoneno = $this->input->post('radio-default-phone');
		
		if(trim($allphoneno) != "")
		{
			$cpdata['contact_id'] = $contact_id;
			$cpdata['phone_no'] = $allphoneno;
			$cpdata['is_default'] = '1';
			$cpdata['status'] = '1';
			
			$this->obj->insert_phone_trans_record($cpdata);
			
			unset($cpdata);
		}
		
		$allphoneno1 = $this->input->post('radio-phone2');
		
		if(trim($allphoneno1) != "")
		{
			$cpdata['contact_id'] = $contact_id;
			$cpdata['phone_no'] = $allphoneno1;
			$cpdata['is_default'] = '0';
			$cpdata['status'] = '1';
			
			$this->obj->insert_phone_trans_record($cpdata);
			
			unset($cpdata);
		}
		
		$allphoneno2 = $this->input->post('radio-phone3');
		
		if(trim($allphoneno2) != "")
		{
			$cpdata['contact_id'] = $contact_id;
			$cpdata['phone_no'] = $allphoneno2;
			$cpdata['is_default'] = '0';
			$cpdata['status'] = '1';
			
			$this->obj->insert_phone_trans_record($cpdata);
			
			unset($cpdata);
		}
		
		$array_data=$this->input->post('old_contacts');
		
		if(count($array_data) > 0)
		{
			$all_interaction_plans = array();
			$all_interaction_plans_data = array();
			$total_plans = 0;
			$is_user_assigned = 0;
			for($i=0;$i<count($array_data);$i++)
			{
				/* Copy pic */
				$match = array('id'=>$array_data[$i]);
				$result = $this->obj->select_records('',$match,'','=');
				if(!empty($result[0]['contact_pic']))
				{
					$cmdata['id'] = $contact_id;
					$cmdata['contact_pic'] = $result[0]['contact_pic'];
					$this->obj->update_record($cmdata);
				}
				
				if(!empty($result[0]))
				{
					$cmdata['id'] = $contact_id;
					
					/*if(!empty($result[0]['created_type']))
						$cmdata['created_type'] = $result[0]['created_type'];*/
					if(!empty($result[0]['fb_id']))
						$cmdata['fb_id'] = $result[0]['fb_id'];
					if(!empty($result[0]['fb_login_id']))
						$cmdata['fb_login_id'] = $result[0]['fb_login_id'];
					if(!empty($result[0]['linkedin_id']))
						$cmdata['linkedin_id'] = $result[0]['linkedin_id'];
					if(!empty($result[0]['linkedin_message_id']))
						$cmdata['linkedin_message_id'] = $result[0]['linkedin_message_id'];
					if(!empty($result[0]['lead_id']))
						$cmdata['lead_id'] = $result[0]['lead_id'];
					if(!empty($result[0]['linkedin_user_id']))
						$cmdata['linkedin_user_id'] = $result[0]['linkedin_user_id'];
					if(!empty($result[0]['joomla_user_id']))
						$cmdata['joomla_user_id'] = $result[0]['joomla_user_id'];
					if(!empty($result[0]['joomla_domain_name']))
						$cmdata['joomla_domain_name'] = $result[0]['joomla_domain_name'];
					if(!empty($result[0]['joomla_contact_type']))
						$cmdata['joomla_contact_type'] = $result[0]['joomla_contact_type'];
					
					$this->obj->update_record($cmdata);
				}
				
				/* Copy docs */
				$result_doc=$this->obj->select_document_trans_record_contact_id($array_data[$i]);
				for($k=0;$k<count($result_doc);$k++)
				{
					$ucdtdata['id'] = $result_doc[$k]['id'];
					$ucdtdata['contact_id'] = $contact_id;
					$this->obj->update_doc_trans_record($ucdtdata);
				}
				
				/* Copy tags */
				$result_tags=$this->obj->select_tag_record($array_data[$i]);
				for($k=0;$k<count($result_tags);$k++)
				{
					$ucttdata['id'] = $result_tags[$k]['id'];
					$ucttdata['contact_id'] = $contact_id;
					$this->obj->update_tag_record($ucttdata);
				}
				
				/* Copy social */
				$result_social=$this->obj->select_social_trans_record($array_data[$i]);
				for($k=0;$k<count($result_social);$k++)
				{
					$ucstdata['id'] = $result_social[$k]['id'];
					$ucstdata['contact_id'] = $contact_id;
					$this->obj->update_social_trans_record($ucstdata);
				}
				
				/* Copy website */
				$result_website=$this->obj->select_website_trans_record($array_data[$i]);
				for($k=0;$k<count($result_website);$k++)
				{
					$ucwtdata['id'] = $result_website[$k]['id'];
					$ucwtdata['contact_id'] = $contact_id;
					$this->obj->update_website_trans_record($ucwtdata);
				}
				
				/* Copy Conversations */
				$result_conversations=$this->obj->select_contact_conversation_trans_record_by_contact_id($array_data[$i]);
				for($k=0;$k<count($result_conversations);$k++)
				{
					$ucctdata['id'] = $result_conversations[$k]['id'];
					$ucctdata['contact_id'] = $contact_id;
					$this->obj->update_converstion_tran_record($ucctdata);
				}
				
				/* Copy Personal touches */
				$result_personaltouch=$this->obj->select_personal_touches_trans_record($array_data[$i]);
				for($k=0;$k<count($result_personaltouch);$k++)
				{
					$ucptdata['id'] = $result_personaltouch[$k]['id'];
					$ucptdata['contact_id'] = $contact_id;
					$this->obj->update_contact_per_tou_tran_record($ucptdata);
				}
				
				/* Copy Interaction Plans */
				$result_interaction_plan=$this->interaction_plans_model->select_assigned_contact_intraction_plan($array_data[$i]);
				//pr($result_interaction_plan);
				for($k=0;$k<count($result_interaction_plan);$k++)
				{
					if(!in_array($result_interaction_plan[$k]['interaction_plan_id'],$all_interaction_plans))
					{
						$all_interaction_plans[$total_plans] = $result_interaction_plan[$k]['interaction_plan_id'];;
						$all_interaction_plans_data[$total_plans]['plan'] = $result_interaction_plan[$k]['interaction_plan_id'];
						$all_interaction_plans_data[$total_plans]['contact_id'] = $array_data[$i];
						$total_plans++;
					}
				}
				
				if($is_user_assigned == 0)
				{
					/* Copy user-contact trans */
					$result_user_contact_trans=$this->obj->select_user_contact_trans_record($array_data[$i]);
					//pr($result_user_contact_trans);
					for($k=0;$k<count($result_user_contact_trans);$k++)
					{
						$ucuctdata['id'] = $result_user_contact_trans[$k]['id'];
						$ucuctdata['contact_id'] = $contact_id;
						$this->obj->update_user_contact_trans_record_by_id($ucuctdata);
						$is_user_assigned = 1;
					}
				}
				
				/* Copy email campaign recepient trans */
				
				//By contact_id
				
				$result_user_email_camp_trans=$this->email_campaign_master_model->email_campaign_trans_data_by_type($array_data[$i],'contact_id');
				for($k=0;$k<count($result_user_email_camp_trans);$k++)
				{
					$ucectdata['id'] = $result_user_email_camp_trans[$k]['id'];
					$ucectdata['contact_id'] = $contact_id;
					$this->email_campaign_master_model->update_email_campaign_trans($ucectdata);
				}
				
				//By cc
				
				$result_user_email_camp_trans1=$this->email_campaign_master_model->email_campaign_trans_data_by_type($array_data[$i],'recepient_cc');
				for($k=0;$k<count($result_user_email_camp_trans1);$k++)
				{
					$ucectdata1['id'] = $result_user_email_camp_trans1[$k]['id'];
					$ucectdata1['recepient_cc'] = $contact_id;
					$this->email_campaign_master_model->update_email_campaign_trans($ucectdata1);
				}
				
				//By bcc
				
				$result_user_email_camp_trans2=$this->email_campaign_master_model->email_campaign_trans_data_by_type($array_data[$i],'recepient_bcc');
				for($k=0;$k<count($result_user_email_camp_trans2);$k++)
				{
					$ucectdata2['id'] = $result_user_email_camp_trans2[$k]['id'];
					$ucectdata2['recepient_bcc'] = $contact_id;
					$this->email_campaign_master_model->update_email_campaign_trans($ucectdata2);
				}
				
				
				/* Copy sms campaign recepient trans */
				
				//By contact_id
				
				$result_user_sms_camp_trans=$this->sms_campaign_master_model->select_sms_campaign_trans_data_by_type($array_data[$i]);
				for($k=0;$k<count($result_user_sms_camp_trans);$k++)
				{
					$ucsctdata['id'] = $result_user_sms_camp_trans[$k]['id'];
					$ucsctdata['contact_id'] = $contact_id;
					$this->sms_campaign_master_model->update_sms_campaign_trans($ucsctdata);
				}
				
				
			}
			
			if(!empty($all_interaction_plans_data) && count($all_interaction_plans_data) > 0)
			{
				foreach($all_interaction_plans_data as $row_ipd)
				{
					$uciptdata['plan'] 			= $row_ipd['plan'];
					$uciptdata['contact_id'] 	= $row_ipd['contact_id'];
					$this->interaction_plans_model->update_interaction_plan_contact_trans($uciptdata,$contact_id);
					$this->interaction_plans_model->update_interaction_plan_interaction_contact_trans($uciptdata,$contact_id);
				}
				
			}
		}
		
		if(count($array_data) > 0)
		{
			for($i=0;$i<count($array_data);$i++)
			{
				/* Delete all old data trans */
				
				/*$match = array('id'=>$array_data[$i]);
				$result = $this->obj->select_records('',$match,'','=');
				
				$bgImgPath = $this->config->item('contact_big_img_path');
				$smallImgPath = $this->config->item('contact_small_img_path');
				if(!empty($result[0]['contact_pic']))
				{
					unlink($bgImgPath.$result[0]['contact_pic']);
					unlink($smallImgPath.$result[0]['contact_pic']);
				}
			
				$result_doc=$this->obj->select_document_trans_record_contact_id($array_data[$i]);
				$bgImgPath_doc = $this->config->item('contact_documents_img_path');
				for($k=0;$k<count($result_doc);$k++)
				{	
					if(!empty($result_doc[$k]['doc_file']))
					{
						unlink($bgImgPath_doc.$result_doc[$k]['doc_file']);
					}
				}*/
				
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_documents_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'user_contact_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_emails_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_phone_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_address_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_website_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_social_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_contacttype_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_tag_trans');
				
				$this->obj->delete_all_trans_table_record($array_data[$i],'email_campaign_recepient_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'sms_campaign_recepient_trans');
				
				//////////////////////////
			
				$this->obj->delete_all_trans_table_record($array_data[$i],'contact_conversations_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_personal_touches');
				
				$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contacts_trans');
				$this->obj->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_communication_plan');
				
				//////////////////////////
				
				$this->obj->delete_record($array_data[$i]);
				
				/* Delete all old data trans End */
			}
		}
		/*$array_data=$this->input->post('old_contacts');
		
		if(count($array_data) > 0)
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
			}
		}*/
		
		$msg = $this->lang->line('common_merge_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		
		redirect('user/'.$this->viewName);
		
	}
	function interaction_id_done()
	{			
		$is_done=$this->input->post('id');
		if($is_done == '1')
		{
			$data['task_date'] = date('Y-m-d');
			$data['task_completed_date'] = date('Y-m-d H:i:s');
			$data['completed_by'] = $this->user_session['id'];
			$data['is_done']='1';
		}
		else
		{	$data['is_done']='0';}
		
		$data['id']=$this->input->post('is_done_hidd');
		//pr($data);exit;
		$this->obj->update_interaction_plan_interaction_transtrans_record($data);
		//exit;
		
		// Get plan id and contact id
		
		$get_completed_data = $this->interaction_model->fetch_contact_communication_record($this->input->post('is_done_hidd'));
		
		//pr($get_completed_data);exit;
		
		if(!empty($get_completed_data[0]['interaction_plan_id']))
		{
			
			$plan_id = $get_completed_data[0]['interaction_plan_id'];
			
			//////////////////// Update task date of related interactions //////////////////////////
			
			$table = "interaction_plan_interaction_master as ipim";
			$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
			$join_tables = array(
								'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
								'admin_users as au' => 'au.id = ipim.created_by',
								'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
								);
			
			$group_by='ipim.id';
			
			$where1 = array('ipim.interaction_plan_id'=>$plan_id);
			
			$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
			
			//	echo "here";exit;
			
			//////////////// Update Contact Interaction Plan-Interaction Transaction /////////////////////
			
			if(count($interaction_list) > 0)
			{
				foreach($interaction_list as $row1)
				{
				
					if($get_completed_data[0]['interaction_plan_interaction_id'] != $row1['id'])
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
								foreach($worktimespecialdata as $row)
								{
									$day_string = '';
									if(!empty($row['nth_day']) && !empty($row['nth_date']))
									{
										switch($row['nth_day'])
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
										switch($row['nth_date'])
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
							
							foreach($worktimeleavedata as $row)
							{
								if(!empty($row['from_date']))
								{
									$leave_days1[] = $row['from_date'];
									if(!empty($row['to_date']))
									{
										
										//$from_date = date('Y-m-d',strtotime($row['from_date']));
										
										$from_date = date('Y-m-d', strtotime($row['from_date'] . ' + 1 day'));
										
										$to_date = date('Y-m-d',strtotime($row['to_date']));
										
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
					
						if(!empty($get_completed_data[0]['contact_id']))
						{
							$row = array('id'=>$get_completed_data[0]['contact_id']);
							//foreach($addcontactdata as $row)
							//{
								//$iccdata['interaction_plan_id'] = $plan_id;
								//$iccdata['contact_id'] = $row['id'];
								//$iccdata['interaction_plan_interaction_id'] = $row1['id'];
								//$iccdata['interaction_type'] = $row1['interaction_type'];
								//pr($row1);
								//exit;
								$interaction_id = $row1['id'];
								$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
								
								//pr($contact_interaction_plan_interaction_id);
								//exit;
								
								if(!empty($contact_interaction_plan_interaction_id->id))
								{
									$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
								
								
									if($row1['start_type'] == '1')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										
										///////////////////////////////////////////////////////////////
										
										$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
										$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
										
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
										
										$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
										
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
									//$iccdata['created_by'] = $this->user_session['id'];
									
									//pr($iccdata1);
									
									$sendemaildate = $iccdata1['task_date'];
									
									if(!empty($contact_interaction_plan_interaction_id->id))
									{
										$this->interaction_model->update_contact_communication_record($iccdata1);
									}
									//exit;
									unset($iccdata1);
									
									// Update old Email or SMS //
						
									if($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8)
									{
										//echo $row1['id']." ".$row['id']."<br>";;
										$table = "email_campaign_master as ecm";
										$fields = array('ecr.*');
										$join_tables = array(
											'email_campaign_recepient_trans as ecr' => 'ecr.email_campaign_id = ecm.id'
											);
					
										//$group_by='ipim.id';
					
										$where1 = array('ecm.interaction_id'=>$row1['id'],'ecr.contact_id'=>$row['id']);
					
										$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
										if(count($email_campaign_data) > 0)
										{
											if(!empty($email_campaign_data[0]['id']))
											{
												$idata['id'] = $email_campaign_data[0]['id'];
												$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
												$this->email_campaign_master_model->update_email_campaign_trans($idata);
												//echo $this->db->last_query()."<br>";
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
					
										$where1 = array('scm.interaction_id'=>$row1['id'],'scr.contact_id'=>$row['id']);
					
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
						
								///////////////////////////////
								
								}
				
							//}
						
						}
						
						unset($user_work_off_days1);
						unset($special_days1);
						unset($leave_days1);
					}
				}
			}
			
			////////////////////////////////////////////////////////////////////////////////////////
		
		}
		
	}
	
	function insert_last_action_communication_plan()
	{
		$data['is_done']='1';
		$data['task_completed_date'] = date('Y-m-d H:i:s');
		$data['completed_by'] = $this->user_session['id'];
		$data['id']=$this->input->post('is_done_hidd_tab');
		$this->obj->update_interaction_plan_interaction_transtrans_record($data);
		
		$interaction_plan_start_type = $this->input->post('rd_start_interaction_plan');
		
		$id = $this->input->post('hid_current_plan_id');
		$contact_id = $this->input->post('hid_contact_id');
		
		if($interaction_plan_start_type == 2)
		{
			
			$re_start_date = $this->input->post('r_next_interaction_start_date');
			
			///////////// Restart Interaction Plan /////////////
			
				$plan_id = $id;
				$interaction_plan_id = $id;
				
				///////////////////////////////////////////////////////////////////////////
	
				$table = "interaction_plan_contacts_trans as ct";
				$fields = array('ct.interaction_plan_id','cm.*','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ct.id as ctid','ct.start_date','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
				$where = array('ct.interaction_plan_id'=>$plan_id,'ct.contact_id'=>$contact_id);
				$join_tables = array(
									'contact_master as cm'=>'cm.id = ct.contact_id',
									'contact_address_trans as cat'=>'cat.contact_id = cm.id',
								);
				$group_by='cm.id';
				
				$old_contacts_data = $this->obj2->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
				
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
				
				$interaction_list =$this->obj2->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				/////////////////////////////////////////////////////////
									
			
				if(!empty($old_contacts_data))
				{
					foreach($old_contacts_data as $row)
					{
						$uictdata['id'] = $row['ctid'];
						$uictdata['start_date'] = !empty($re_start_date)?date('Y-m-d',strtotime($re_start_date)):date('Y-m-d');
						$uictdata['modified_date'] = date('Y-m-d H:i:s');
						$uictdata['modified_by'] = $this->user_session['id'];
						//pr($uictdata);
						
						$this->obj2->update_record_interaction_contact($uictdata);
							
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
								
								$match = array("user_id"=>$new_user_id,"rule_type"=>1);
								$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
								
								$match = array("user_id"=>$new_user_id);
								$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
								
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
								$plan_contact_data = $this->obj2->select_records_plan_contact_trans('',$match,'','=','','','','','','');
								
								
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
								
								$interaction_res = $this->obj4->get_contact_interaction_task_date($interaction_id,$row['id']);
								
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
								
									$iccdata['task_date'] = $newtaskdate;
								}
								
							}
							else
							{
								$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
							}
							$sendemaildate = $iccdata['task_date'];
							
							$iccdata['created_date'] = date('Y-m-d H:i:s');
							$iccdata['created_by'] = $this->user_session['id'];
							
							$this->obj4->insert_contact_communication_record($iccdata);
							
							$agent_name = '';
							if(!empty($row1['assign_to']))
							{
								$table ="login_master as lm";   
								$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
								$join_tables = array('user_master as um'=>'lm.user_id = um.id');
								$wherestring = 'lm.id = '.$row1['assign_to'];
								$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
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
								if(count($interaction_exist) > 0)
								{
									$email_data['email_campaign_id'] = $interaction_exist[0]['id'];
									$email_data['contact_id'] = $row['id'];
									$email_data['is_send'] = '0';
									$this->email_campaign_master_model->delete_interaction_campaign($email_data);
									
									$cdata1['email_campaign_id'] = $interaction_exist[0]['id'];
									$match = array('id'=>$row1['template_name']);
									$result = $this->email_library_model->select_records('',$match,'','=');
									if(!empty($result[0]['id']))
									{
										$rowdatainst = $result[0];
								
										$cdata1['contact_id'] = $row['id'];
										$emaildata = array(
															'Date'=>date('Y-m-d'),
															'Day'=>date('l'),
															'Month'=>date('F'),
															'Year'=>date('Y'),
															'Day Of Week'=>date("w",time()),
															'Agent Name'=>$agent_name,
															'Contact First Name'=>$row['first_name'],
															'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
															'Contact Last Name'=>$row['last_name'],
															'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
															'Contact Company Name'=>$row['company_name']
														  );
										
										$content = $rowdatainst['email_message'];
										$title = $rowdatainst['template_subject'];
										
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
										$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
									}
								}
							}
							elseif($row1['interaction_type'] == '3' && !empty($row1['template_name']))
							{
								$match = array('interaction_id'=>$row1['id']);
								$interaction_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
								if(count($interaction_exist) > 0)
								{
									$smsdata['sms_campaign_id'] = $interaction_exist[0]['id'];
									$smsdata['contact_id'] = $row['id'];
									$smsdata['is_send'] = '0';
									$this->sms_campaign_master_model->delete_interaction_campaign($smsdata);
									
									$cdata1['sms_campaign_id'] = $interaction_exist[0]['id'];
									$match = array('id'=>$row1['template_name']);
									$result = $this->sms_texts_model->select_records('',$match,'','=');
									
									if(!empty($result[0]['id']))
									{
										$rowdatainst = $result[0];
								
										$cdata1['contact_id'] = $row['id'];
										//$cdata1['sms_campaign_id'] = $sms_campaign_id;
										$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
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
							
							
							
							
							unset($user_work_off_days1);
							unset($special_days1);
							unset($leave_days1);
						
						}
					
					}
					
					///////////////////////////////////////////////////////////////////////////
					
					unset($icdata);
					unset($iccdata);
				
				}
			}
					
			
			///////////////////////////////////////////////////
			
		}
		elseif($interaction_plan_start_type == 3)
		{
			$pid = $this->input->post('slt_interaction_plan');
			$interaction_plan_id = $pid;
			$plan_id = $pid;
			$new_start_date = $this->input->post('next_interaction_start_date');
			if(!empty($contact_id))
			{
			
				////////////////////////////////////////////////////////
			
				$match = array('id'=>$plan_id);
				$interaction_plan_details = $this->interaction_plans_model->select_records('',$match,'','=');
				
				$cdata = array();
				if(!empty($interaction_plan_details[0]))
				{
					$cdata = $interaction_plan_details[0];
				}
				else
				{
					redirect("user/contacts/view_record/".$contact_id."/6#myTab2");
					exit;
				}
			
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
				
				$interaction_list =$this->obj2->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
				
				/////////////////////////////////////////////////////////
			
				$row = $contact_id;
			
				if($row != '')
				{
				
					$icdata['interaction_plan_id'] = $interaction_plan_id;
					$icdata['contact_id'] = $row;
					
					if($cdata['plan_start_type'] == '1')
					{
						$icdata['start_date'] = !empty($new_start_date)?date('Y-m-d',strtotime($new_start_date)):date('Y-m-d');
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					else
					{
						/*if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
							$icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
						else
							$icdata['start_date'] = date('Y-m-d');*/
						
						$icdata['start_date'] = !empty($new_start_date)?date('Y-m-d',strtotime($new_start_date)):date('Y-m-d');
						
						$icdata['plan_start_type'] = $cdata['plan_start_type'];
						$icdata['plan_start_date'] = $cdata['start_date'];
					}
					
					$icdata['created_date'] = date('Y-m-d H:i:s');
					$icdata['created_by'] = $this->user_session['id'];
					$icdata['status'] = '1';
					
					$this->obj2->insert_contact_trans_record($icdata);
					
					/////////////// ADD TO LOG ///////////////////
					
					$data_conv['contact_id'] = $contact_id;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $cdata['plan_name'];
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '2';
					$data_conv['created_by'] = $this->user_session['id'];
					$data_conv['status'] = '1';
					$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv);
					
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
								
								
								$match = array("user_id"=>$new_user_id,"rule_type"=>1);
								$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
								
								
								$match = array("user_id"=>$new_user_id);
								$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
								
								
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
								$plan_contact_data = $this->obj2->select_records_plan_contact_trans('',$match,'','=','','','','','','');
								
								
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
								
								$interaction_res = $this->obj4->get_contact_interaction_task_date($interaction_id,$row);
								
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
							$iccdata['created_by'] = $this->user_session['id'];
							
							$this->obj4->insert_contact_communication_record($iccdata);
							
							/*$match = array('id'=>$row);
							$userdata = $this->contacts_model->select_records('',$match,'','=');*/
							
							$table = "contact_master as cm";
							$fields = array('cm.id','cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
							$where = array('cm.id'=>$row);
							$join_tables = array(
												'contact_address_trans as cat'=>'cat.contact_id = cm.id',
											);
							$group_by='cm.id';
							$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','cm.first_name','asc',$group_by,$where);
							
							$agent_name = '';
							if(!empty($row1['assign_to']))
							{
								$table ="login_master as lm";   
								$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
								$join_tables = array('user_master as um'=>'lm.user_id = um.id');
								$wherestring = 'lm.id = '.$row1['assign_to'];
								$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
								if(!empty($agent_datalist))
								{
									if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
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
								if(count($interaction_exist) > 0)
								{
									$email_data['email_campaign_id'] = $interaction_exist[0]['id'];
									$email_data['contact_id'] = $row;
									$email_data['is_send'] = '0';
									$this->email_campaign_master_model->delete_interaction_campaign($email_data);
									
									$cdata1['email_campaign_id'] = $interaction_exist[0]['id'];
									$match = array('id'=>$row1['template_name']);
									$result = $this->email_library_model->select_records('',$match,'','=');
									if(!empty($result[0]['id']))
									{
										$rowdatainst = $result[0];
								
										$cdata1['contact_id'] = $row;
										$emaildata = array(
															'Date'=>date('Y-m-d'),
															'Day'=>date('l'),
															'Month'=>date('F'),
															'Year'=>date('Y'),
															'Day Of Week'=>date("w",time()),
															'Agent Name'=>$agent_name,
															'Contact First Name'=>$userdata['first_name'],
															'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
															'Contact Last Name'=>$userdata['last_name'],
															'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
															'Contact Company Name'=>$userdata['company_name']
														  );
										
										$content = $rowdatainst['email_message'];
										$title = $rowdatainst['template_subject'];
										
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
										$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
									}
								}
							}
							elseif($row1['interaction_type'] == '3' && !empty($row1['template_name']))
							{
								$match = array('interaction_id'=>$row1['id']);
								$interaction_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
								if(count($interaction_exist) > 0)
								{
									$smsdata['sms_campaign_id'] = $interaction_exist[0]['id'];
									$smsdata['contact_id'] = $row;
									$smsdata['is_send'] = '0';
									$this->sms_campaign_master_model->delete_interaction_campaign($smsdata);
									
									$cdata1['sms_campaign_id'] = $interaction_exist[0]['id'];
									$match = array('id'=>$row1['template_name']);
									$result = $this->sms_texts_model->select_records('',$match,'','=');
									
									if(!empty($result[0]['id']))
									{
										$rowdatainst = $result[0];
								
										$cdata1['contact_id'] = $row;
										//$cdata1['sms_campaign_id'] = $sms_campaign_id;
										$emaildata = array(
														'Date'=>date('Y-m-d'),
														'Day'=>date('l'),
														'Month'=>date('F'),
														'Year'=>date('Y'),
														'Day Of Week'=>date("w",time()),
														'Agent Name'=>$agent_name,
														'Contact First Name'=>$userdata['first_name'],
														'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
														'Contact Last Name'=>$userdata['last_name'],
														'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
														'Contact Company Name'=>$userdata['company_name']);
										
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
		
		redirect("user/contacts/view_record/".$contact_id."/6#myTab2");
		
	}
	
	/*
    @Description: This Function Insert Contact Converation List
    @Author: Kaushik  Valiya
    @Input: Contact Id to Insert contact_conversations_trans Table.
    @Output: Insert of Converation
    @Date: 15-09-2014
	
	*/
	
	function insert_conversations()
	{
		//pr($_POST);exit;
		   $from_page = $this->input->post('from_joomla_view');/// This User for leadr dashboard
		
		$data['contact_id']=$this->input->post('contact_id');
		//$data['interaction_type']=$this->input->post('sl_interaction_type');
		
		if($from_page == 1 || $from_page == 2)
		{
			$data['interaction_type']=$this->input->post('sl_interaction_type');
			$data['plan_name']= 'Call';
		}
		else
		{
			$data['plan_name']=$this->input->post('sl_interaction_type');
		}
		$data['description']=$this->input->post('description');
		$data['disposition']=$this->input->post('disposition_type');
		$data['created_by'] =$this->user_session['id'];
		$data['log_type'] = '1';// 1-Manual type
		$data['created_date'] = date('Y-m-d H:i:s');
		$data['status'] = '1';
		
		if(!empty($data['interaction_type']) || !empty($data['description']) || !empty($data['plan_name']))
			$this->obj->insert_contact_conversation($data);
        
		if($from_page == 1)
			redirect("user/leads_dashboard/view_record/".$data['contact_id']);
		else if($from_page == 2)
			redirect("user/leads_dashboard/");	
		else
                    redirect("user/contacts/view_record/".$data['contact_id']."/4#myTab2");
	}
	
	/*
    @Description: This Function update Contact Converation List
    @Author: Kaushik  Valiya
    @Input: Contact Id to update contact_conversations_trans Table.
    @Output: update of Converation
    @Date: 15-09-2014
	
	*/
	function update_conversations()
	{
		//pr($_POST);exit;
		$data['id'] = $this->router->uri->segments[4];
		//$data['interaction_type']=$this->input->post('sl_interaction_type');
		$data['plan_name']=$this->input->post('sl_interaction_type');
		$data['contact_id']=$this->input->post('contact_id');
		$data['description']=$this->input->post('description');
		$data['disposition']=$this->input->post('disposition_type');
		$data['modified_by'] = $this->user_session['id'];
		$data['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_converstion_tran_record($data);
		redirect("user/contacts/view_record/".$data['contact_id']."/4#myTab2");
	}
	
	
	/*
    @Description: This Function Delete Contact Converation List
    @Author: Kaushik  Valiya
    @Input: Contact Id to Delete contact_conversations_trans Table.
    @Output: Delete of Converation
    @Date: 15-09-2014
	
	*/
	public function ajax_delete_conversations()
	{
		
		$id=$this->input->post('id');
		if(!empty($id))
		{
			$this->obj->delete_table_conversations($id);
			unset($id);
		}
		
		echo 1;
	}
	
	function personal_id_done()
	{			
		$is_done=$this->input->post('id');
		if($is_done == '1')
		{	$data['is_done']='1';}

		else
		{	$data['is_done']='0';}
		
		$data['id']=$this->input->post('is_done_hidd');
		//pr($data);exit;
		$this->obj->update_contact_per_tou_tran_record($data);
	}
	function insert_personal_touches()
	{			
		
		$data['interaction_type']=$this->input->post('interaction_type');
		$data['contact_id']=$this->input->post('contact_id');
		$data['followup_date']=date('y-m-d',strtotime($this->input->post('followup_date')));
		$data['task']=$this->input->post('task');
		$data['created_by'] = $this->user_session['id'];
		$data['created_date'] = date('Y-m-d H:i:s');
		$data['status'] = '1';
		$this->obj->insert_interaction_plan_contact_per_touches($data);
		//$data['tabid']='2';
		//redirect("user/contacts/view_record/".$data['contact_id']);
		  $from_page = $this->input->post('from_joomla_view');
		//pr($from_page);exit;
        if($from_page == 1)
            redirect("user/leads_dashboard/view_record/".$data['contact_id']);
		else if($from_page == 2)
            redirect("user/leads_dashboard/view_record/");
		else if($from_page == 3)
			redirect("user/dashboard/telephone_task/");
		else	
		redirect("user/contacts/view_record/".$data['contact_id']."/5#myTab2");
	}
	/*
    @Description: This Function Get Contact Converation List
    @Author: Kaushik  Valiya
    @Input: Contact Id to get contact_conversations_trans Table To get
    @Output: List of Converation
    @Date: 15-09-2014
	
	*/

	function get_conversation_data()
	{		
		$id=$this->input->post('id');
		$table = "contact_conversations_trans as cct";
		$fields = array('cct.id','cct.plan_name','cct.contact_id','cct.disposition','cct.description','iptm.id as plan_type_id','iptm.name as interaction_type_name','cdm.id as dis_id','cdm.name as disposition_name','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name');
		$join_tables = array(
							'interaction_plan__plan_type_master as iptm' => 'iptm.id = cct.interaction_type',
							'contact__disposition_master as cdm'=>'cdm.id = cct.disposition',
							'contact_master as cm'=>'cm.id = cct.contact_id '
						);
		$group_by='cct.id';
		$where=array('cct.id'=>$id);
		$conversations = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
		
		echo json_encode($conversations[0]);
	}

	public function is_completed_task()
	{
		$data['id'] = $this->input->post('conversation_id');
		$data['is_completed_task'] = '1';
		$this->contact_conversations_trans_model->update_record($data);
		
		$id = $data['id'];
		$match=array('id'=> $id);
		$field=array('task_id','assign_to','is_completed_task');
		$data_result = $this->contact_conversations_trans_model->select_records($field,$match,'','=');
		$task_id=$data_result[0]['task_id'];
		
		//Get conversation all data
		$match=array('task_id'=> $task_id);
		$field=array('is_completed_task');
		$con_result = $this->contact_conversations_trans_model->select_records($field,$match,'','=');
		
		$cnt=0;
		if(!empty($con_result))
		{
		foreach($con_result as $row)
		{
			if($row['is_completed_task'] == '0')
			{
				$cnt++;
			}
		}
		}
		//Get conversation all data
		$match=array('task_id'=> $task_id,'assign_to'=>$data_result[0]['assign_to']);
		$field=array('is_completed_task','assign_to','task_id');
		$assign_user_result = $this->contact_conversations_trans_model->select_records($field,$match,'','=');
		
		$assign_cnt=0;
		if(!empty($assign_user_result))
		{
			foreach($assign_user_result as $row)
			{
				if($row['is_completed_task'] == '0')
				{
					$assign_cnt++;
				}
			}
			
			if($assign_cnt == '0')
			{
				$asdata['is_completed'] = '1';
				$asdata['completed_date'] = date('Y-m-d h:i:s');
				$asdata['user_id'] = $assign_user_result[0]['assign_to'];
				$asdata['task_id'] = $assign_user_result[0]['task_id'];
				$this->task_model->update_task($asdata);
				
			}
		}
		
		if(!empty($this->user_session) && ($this->user_session['user_type'] == '3' || $this->user_session['user_type'] == '4')){
			
			if($cnt == '0')
			{
				$udata['is_completed'] = '1';
				$udata['id'] = $task_id;
				$this->task_model->update_record($udata);	
			}
		}
		else
		{
			
			//Get user task all data
			$match=array('task_id'=> $task_id);
			$field=array('is_completed');
			$user_task_result = $this->task_model->select_records1($field,$match,'','=');
			$cnt1=0;
			if(!empty($user_task_result))
			{
			foreach($user_task_result as $row)
			{
				if($row['is_completed'] == '0')
				{
					$cnt1++;
				}
			}
			}
			if($cnt == '0' && $cnt1 == '0')
			{
				$udata['is_completed'] = '1';
				$udata['id'] = $task_id;
				$this->task_model->update_record($udata);	
			}
		}
		echo 1;
	}
	/*
		@Description: Function for send message.
		@Author: Sanjay Chabhadiya
		@Input: - Template Id
		@Output: - Template details
		@Date: 06-08-2014
   	*/
	public function fb_conversation()
    {
			//$this->load->library('social/facebook');
			
			$contact_id = $this->input->post('contact');
			$match=array('id'=> $contact_id);
        	$data_result = $this->obj->select_records('',$match,'','=');
			if(!empty($data_result[0]['fb_id']))
			{
			$action = 'fblogin';
			switch($action){
				case "fblogin":
				$this->load->library('social/facebook');
				$appid 		= "728901530477590";
				$appsecret  = "450e78a3c48a4731e4d8c2592c2bbae1";
				$facebook   = new Facebook(array(
					'appId' => $appid,
					'secret' => $appsecret,
					'cookie' => TRUE,
				));
				
				 $user = $facebook->getUser();
				$access_token = $facebook->getAccessToken();
				$fbuser = $facebook->getUser();
				
				//$friends = $facebook->friends_get();
				if ($fbuser) {
					try {
						$user_profile = $facebook->api('/me');
						$frnd = $facebook->api('/me/friends');
					}
					catch (Exception $e) {
						echo $e->getMessage();
						exit();
					}
					$user_fbid = $fbuser;
					$friends = $facebook->api('/me/friends');
					//echo"<pre>";
					//print_r($friends);exit;
					$user_email =(!empty($user_profile["email"])) ? $user_profile["email"] : '-';
					$user_gender = (!empty($user_profile["gender"])) ? $user_profile["gender"] : '-';;
					$user_fnmae = (!empty($user_profile["first_name"])) ? $user_profile["first_name"] : '-';
					$user_lnmae = (!empty($user_profile["last_name"])) ? $user_profile["last_name"] : '-';
					$user_image = "https://graph.facebook.com/".$user_fbid."/picture?type=large";
				}
				break;
				
			}
			
				$flag = '0';
				for($i=0;$i < count($frnd['data']); $i++)
				{ 
					if($data_result[0]['fb_id'] == $frnd['data'][$i]['id'])
					{
						$flag= '1';
					}
				}
				if($flag == 1)
				{
					echo $data_result[0]['fb_id'];
				}
				else
				{
					echo '0';
				}
			}
			else
			{
				echo '0';
			}			
	}


	/*
		@Description: Function for template data from category id and subcategory id wise
		@Author: Sanjay Chabhadiya
		@Input: - Parent category id and chiled category id
		@Output: - Template List
		@Date: 06-08-2014
   */
   
	public function ajax_templatedata()
	{
		$category_id=$this->input->post('loadcategoryId');
		$subcategory_id=$this->input->post('loadsubcategoryId');				
		
		if(!empty($category_id))
		{
			$table = "social_media_template_master as smt";
			$fields = array('smt.*');
			$join_tables = array(
									'login_master as lm' => 'lm.id = smt.created_by',
								 );
			$wherestring='smt.template_category = '.$category_id.' AND (lm.user_type = "1" OR lm.user_type = "2" OR smt.created_by IN ('.$this->user_session['agent_id'].'))';
			$group_by = 'smt.id';
			$data['templatedata'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','smt.id','desc',$group_by,$wherestring);
			/*echo $this->db->last_query();
			pr($data['templatedata']);exit;*/
		
			echo json_encode($data['templatedata']);
		}
	}	
	/*
		@Description: Function for sms template data
		@Author: Sanjay Chabhadiya
		@Input: - Template Id
		@Output: - Template details
		@Date: 06-08-2014
   	*/
   
	public function ajax_templatename()
	{
		$template_id = $this->input->post('template_id');
		if(!empty($template_id))
		{
			$match = array("id"=>$template_id);
        	$cdata['templatedata'] = $this->socialmedia_post_model->select_records('',$match,'','=','','','','id','desc');
			if(count($cdata['templatedata']) > 0)
			{
				echo json_encode($cdata['templatedata']);
			}
		}	
	}
        
    /*
        @Description: This Function Insert Notes List
        @Author     : Nishit Modi
        @Input      : Contact Id to Insert Contact notes.
        @Output     : Insert of Notes
        @Date       : 27-12-2014
    */
    function insert_contact_notes()
    {	

        $contact_id=$this->input->post('contact_id');
        $notes_detail=$this->input->post('notes_detail');
		if(!empty($notes_detail))
        {
            $cdata['contact_id'] = $contact_id;
            $cdata['log_type'] = 12;
            $cdata['description'] = $notes_detail;
            $cdata['created_by'] = $this->user_session['id'];
            $cdata['created_date'] = date('Y-m-d H:i:s');
            $this->obj->insert_notes_record($cdata);
        }
        //$data['tabid']='2';

        $from_page = $this->input->post('from_joomla_view');
		//pr($from_page);exit;
        if($from_page == 1)
            redirect("user/leads_dashboard/view_record/".$contact_id);
        else if($from_page == 2)
            redirect("user/leads_dashboard/");	
		else if($from_page == 3)
			redirect("user/dashboard/telephone_task/");
        else
            redirect("user/contacts/view_record/".$contact_id."/5#myTab2");
    }
    
    function change_conversations()
    {
            $id=$this->input->post('contact_id');
            $history_types = $this->input->post('history_type');

            $table = "contact_conversations_trans as cct";
            $fields = array('cct.id','cct.*','cct.contact_id','cct.interaction_id','cct.created_date','cct.disposition','cdm.name as disposition_name','cct.description','cct.mail_out_type','cct.log_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','CONCAT_WS(" ",um1.first_name,um1.middle_name,um1.last_name) as user_name1','cm.spousefirst_name','cm.spouselast_name','cct.email_camp_template_name as email_template_name','cct.email_camp_template_name as email_campaing_template_name','cct.sms_camp_template_name as sms_template_name','cct.sms_camp_template_name as sms_template_name','cct.mail_out_template_name as letter_template_name','cct.mail_out_template_name as envelope_template_name','cct.mail_out_template_name as label_template_name','tm.task_name,tm.desc','ipm.status','tm.task_date','ipptm.name as interaction_type_name');
            $join_tables = array(
                'contact__disposition_master as cdm'=>'cdm.id = cct.disposition',
                'contact_master as cm'=>'cm.id = cct.contact_id',
                'user_master as um1'=>'um1.id = cct.assign_to',
                'login_master as lm'=>'lm.id = cct.assign_to',
                'user_master as um'=>'um.id = lm.user_id',
                'task_master as tm'=>'tm.id = cct.task_id',
                'interaction_plan_master as ipm'=>'ipm.id = cct.plan_id',
                'interaction_plan__plan_type_master ipptm'=>'ipptm.id = cct.interaction_type'
            );
            $group_by='cct.id';
            $where=array('contact_id'=>$id);

            $where_in_val = $history_types;
            $where_in = array('cct.log_type'=>$where_in_val);
            $data['conversations'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','special_case_task','',$group_by,$where,$where_in);

            $this->load->view('user/leads_dashboard/history_details',$data);
    }
	  public function selectedview_session()
        {
            $selected_view = $this->input->post('selected_view');
            
            if($selected_view == '104')
                $sortfield = 'cm.id';
            else
                $sortfield = 'lw_admin_id';
            
            $this->session->unset_userdata('contact_register_sortsearchpage_data');
            $this->session->unset_userdata('contact_savedsearch_sortsearchpage_data');
            $this->session->unset_userdata('contact_favorite_sortsearchpage_data');
            $this->session->unset_userdata('contact_propviewed_sortsearchpage_data');
            $this->session->unset_userdata('contact_lastlogin_sortsearchpage_data');
            $this->session->unset_userdata('contact_val_searched_sortsearchpage_data');
            $this->session->unset_userdata('contact_val_contactform_sortsearchpage_data');
            $this->session->unset_userdata('contact_property_contactform_sortsearchpage_data');
            
            $sortsearchpage_data = array(
                'sortfield'  => $sortfield,
                'sortby' =>'desc',
                'searchtext' =>'',
                'perpage' => '',
                'uri_segment' => 0,
            );
            if($selected_view == '104') // Contact Register
                $this->session->set_userdata('contact_register_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '105') // Saved Searches
                $this->session->set_userdata('contact_savedsearch_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '106') // Favorite
                $this->session->set_userdata('contact_favorite_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '107') // Properties Viewed
                $this->session->set_userdata('contact_propviewed_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '108') // Last Login
                $this->session->set_userdata('contact_lastlogin_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '109') // Last Login
                $this->session->set_userdata('contact_val_searched_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '110') // Valuation contact form
                $this->session->set_userdata('contact_val_contactform_sortsearchpage_data', $sortsearchpage_data);
            else if($selected_view == '111') // Property contact form
                $this->session->set_userdata('contact_property_contactform_sortsearchpage_data', $sortsearchpage_data);
            //echo $selected_view;exit;
            $data = array('selected_view' => $selected_view);
            $this->session->set_userdata('joomla_selected_view_session',$data);
        }
		public function add_saved_searches()
        {
            $tab_result = check_joomla_tab_setting($this->user_session['id']);
                if(!empty($tab_result) && $tab_result[0]['lead_dashboard_tab'] == '0')
                    redirect('user/dashboard');
            $id = $this->uri->segment(4);
            $data['joomla_user_id'] = 0;
            if($id != '')
            {
                $match = array('id'=>$id);
                $result = $this->obj->select_records('',$match,'','=');
                if(!empty($result))
                {
                    $data['joomla_user_id'] = $result[0]['joomla_user_id'];
                    $data['domain'] = $result[0]['joomla_domain_name'];
                }
            }
            
            //////////
            $parent_db = $this->config->item('parent_db_name');
            $this->load->model('mls_model');
            $mls_id = 0;
            if(!empty($result))
            {
                if(!empty($result[0]['joomla_domain_name']))
                {
                    $table = $parent_db . '.child_admin_website';
                    $fields = array('mls_id,slug');
                    $match = array('domain' => $result[0]['joomla_domain_name'],'website_status'=>1);
                    $mls_data = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
                    if(!empty($mls_data))
                    {
                        $mls_id = $mls_data[0]['mls_id'];
                    }
                    else {
                        $explode = explode('.',$result[0]['joomla_domain_name']);
                        if(!empty($explode[0]))
                        {
                            $slug = str_replace('http://', '', $explode[0]);
                            $match = array('slug'=>$slug,'website_status'=>1);
                            $mls_data = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
                            if(!empty($mls_data))
                            {
                                $mls_id = $mls_data[0]['mls_id'];
                            }
                        }
                    }
                }
            }
            $data['mls_id'] = $mls_id;
            /// Get MLS Property Type
            $match = array('status' => '1');
            $data['property_type'] = $this->mls_model->select_records_tran('', $match, '', '=', '', '', '', '', '', '', $parent_db);
            
            /// Get MLS CITY
            $table = $parent_db . '.mls_property_list_master';
            $fields = array('CIT');
            $match = array('mls_id' => !empty($mls_id) ? $mls_id : 0);
            $group_by = 'CIT';
            $data['citylist'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get Parking Type Code
            $table = $parent_db . '.mls_amenity_data';
            $fields = array('code,value_code,value_description');
            $match = array('CODE' => 'GR', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $group_by = 'value_code';
            $data['parking_type'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Property Architecture Code
            $match = array('CODE' => 'ARC', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['property_architecture'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Waterfront Code
            $match = array('CODE' => 'WFT', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['waterfront'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Views Code
            $match = array('CODE' => 'VEW', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['property_views'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS New Construction
            //$match = array('CODE' => 'NC', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            //$data['new_construction'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS New Construction
            $match = array('CODE' => 'PARQ', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['short_sale'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS School District
            $table = $parent_db . '.mls_school_data';
            $fields = array('DISTINCT(school_district_code),school_district_description');
            $match = array('mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['school_district'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match);
            
            //////////
            
            $data['sel_contact_id'] = $id;
            $data['main_content'] = "user/".$this->viewName."/add_saved_searches";
            $this->load->view('user/include/template', $data);
        }
        public function insert_saved_search_data()
        {
            $cdata['name'] = $this->input->post('txt_name');
            //$cdata['search_criteria'] = $this->input->post('search_criteria');
            //$domain = str_replace('www.', '', base_url());
            //$cdata['domain'] = trim($domain,'/');
            $cdata['domain'] = $this->input->post('joomla_domain');
            //$cdata['lw_admin_id'] = $this->user_session['id'];
            $cdata['lw_admin_id'] = $this->input->post('sel_contact_id');
            $cdata['created_date'] = date('Y-m-d H:i:s');
            $cdata['status'] = '1';
            $cdata['created_type'] = '1';
            $cdata['search_criteria'] = $this->input->post('search');
            $cdata['min_price'] = str_replace(',', '', $this->input->post('min_price'));
            $cdata['max_price'] = str_replace(',', '', $this->input->post('max_price'));
            
            $cdata['bedroom'] = $this->input->post('beds');
            $cdata['bathroom'] = $this->input->post('baths');
            /*$cdata['min_area'] = trim($this->input->post('min_area'));
            $cdata['max_area'] = trim($this->input->post('max_area'));*/
            $cdata['min_year_built'] = $this->input->post('year_built');
            /*$cdata['min_year_built'] = trim($this->input->post('min_year_built'));
            $cdata['max_year_built'] = trim($this->input->post('max_year_built'));*/
            $cdata['fireplaces_total'] = $this->input->post('fireplaces');
            
            $cdata['min_lotsize'] = $this->input->post('lot_size');
            /*$cdata['min_lotsize'] = trim($this->input->post('min_lotsize'));
            $cdata['max_lotsize'] = trim($this->input->post('max_lotsize'));*/
            $cdata['garage_spaces'] = $this->input->post('garage_spaces');
            $cdata['architecture'] = $this->input->post('architecture');
            $cdata['school_district'] = $this->input->post('school_district');
            $cdata['parking_type'] = $this->input->post('parking_type');
            $cdata['url'] = 'property/search?'.$this->input->post('search_url');
            $cdata['new_construction'] = $this->input->post('new_construction');
            $cdata['bank_owned'] = $this->input->post('bank_owned');
            $cdata['short_sale'] = $this->input->post('short_sale');
            $cdata['mls_id'] = $this->input->post('mls_id');
            $cdata['CDOM'] = $this->input->post('CDOM');
            
            $property_views = $this->input->post('property_views');
            if (!empty($property_views) && $property_views != 'null' && is_array($property_views))
                $cdata['s_view'] = implode('{^}', $property_views);
            $waterfront = $this->input->post('waterfront');
            if (!empty($waterfront) && $waterfront != 'null' && is_array($waterfront))
                $cdata['waterfront'] = implode('{^}', $waterfront);
            $city = $this->input->post('city');
            if (!empty($city) && $city != 'null' && is_array($city))
                $cdata['city'] = implode('{^}', $city);
            
            $joomla_user_id = trim($this->input->post('joomla_user_id'));
            $lastid = $this->saved_searches_model->insert_record($cdata);	
            $name = urlencode($cdata['name']);
            //$url = "http://seattle.livewiresites.com/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$cdata['domain']."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&action=insert";
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            //$url = $joomla_link."/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$cdata['domain']."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&action=insert";
            $url = $joomla_link."/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$cdata['domain']."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&architecture=".$cdata['architecture']."&school_district=".$cdata['school_district']."&waterfront=".$cdata['waterfront']."&view=".$cdata['s_view']."&parking_type=".$cdata['parking_type']."&action=insert";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzip encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
            $response = curl_exec($ch);
            curl_close($ch);
            $response = (json_decode($response, true));
            
            if(!empty($response))
            {
                $udata['sid'] = $response['sid'];
                $udata['id'] = $lastid;
                $this->saved_searches_model->update_record($udata);
            }
            //pr($response);exit;
            $msg = $this->lang->line('common_add_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $saved_searches_sortsearchpage_data = array(
                'sortfield'  => 'id',
                'sortby' => 'desc',
                'searchtext' =>'',
                'perpage' => '',
                'uri_segment' => 0);
            $this->session->set_userdata('contact_savedsearch_sortsearchpage_data', $saved_searches_sortsearchpage_data);
            
            $data = array('selected_view' => 105);
            $this->session->set_userdata('joomla_selected_view_session',$data);
            
            $contact_id = $this->session->userdata('selected_contactid');
            if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id']);
            else
                redirect('user/'.$this->viewName);
        }
        
        /*
            @Description: Function for load edit Saved searches form
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Open form
            @Date       : 13-11-2014
        */
        public function edit_saved_searches()
        {
            $tab_result = check_joomla_tab_setting($this->user_session['id']);
                if(!empty($tab_result) && $tab_result[0]['lead_dashboard_tab'] == '0')
                    redirect('user/dashboard');
            //$data['sel_contact_id'] = $this->uri->segment(4);
            $id = $this->uri->segment(4);
            $match = array('id'=>$id);
            $result = $this->saved_searches_model->select_records('',$match,'','=');
            $data['editRecord'] = $result;
            
            //////////
            $parent_db = $this->config->item('parent_db_name');
            $this->load->model('mls_model');
            $mls_id = 0;
            if(!empty($result))
            {
                if(!empty($result[0]['domain']))
                {
                    $table = $parent_db . '.child_admin_website';
                    $fields = array('mls_id,slug');
                    $match = array('domain' => $result[0]['domain'],'website_status'=>1);
                    $mls_data = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
                    if(!empty($mls_data))
                    {
                        $mls_id = $mls_data[0]['mls_id'];
                    }
                    else {
                        $explode = explode('.',$result[0]['domain']);
                        if(!empty($explode[0]))
                        {
                            $slug = str_replace('http://', '', $explode[0]);
                            $match = array('slug'=>$slug,'website_status'=>1);
                            $mls_data = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
                            if(!empty($mls_data))
                            {
                                $mls_id = $mls_data[0]['mls_id'];
                            }
                        }
                    }
                }
            }
            $data['mls_id'] = $mls_id;
            /// Get MLS Property Type
            $match = array('status' => '1');
            $data['property_type'] = $this->mls_model->select_records_tran('', $match, '', '=', '', '', '', '', '', '', $parent_db);
            
            /// Get MLS CITY
            $table = $parent_db . '.mls_property_list_master';
            $fields = array('CIT');
            $match = array('mls_id' => !empty($mls_id) ? $mls_id : 0);
            $group_by = 'CIT';
            $data['citylist'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get Parking Type Code
            $table = $parent_db . '.mls_amenity_data';
            $fields = array('code,value_code,value_description');
            $match = array('CODE' => 'GR', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $group_by = 'value_code';
            $data['parking_type'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Property Architecture Code
            $match = array('CODE' => 'ARC', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['property_architecture'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Waterfront Code
            $match = array('CODE' => 'WFT', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['waterfront'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS Views Code
            $match = array('CODE' => 'VEW', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['property_views'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS New Construction
            //$match = array('CODE' => 'NC', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            //$data['new_construction'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS New Construction
            $match = array('CODE' => 'PARQ', 'mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['short_sale'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '', '', '', '', '', $group_by);
            
            /// Get MLS School District
            $table = $parent_db . '.mls_school_data';
            $fields = array('DISTINCT(school_district_code),school_district_description');
            $match = array('mls_id' => !empty($mls_id) ? $mls_id : 0);
            $data['school_district'] = $this->mls_model->getmultiple_tables_records($table, $fields, '', '', '', $match);
            
            //////////
            
            $data['main_content'] = "user/".$this->viewName."/add_saved_searches";
            $this->load->view('user/include/template', $data);
        }

        /*
            @Description: Function for Update saved searches
            @Author     : Sanjay Moghariya
            @Input      : Update details of saved searches
            @Output     : List with updated saved searches details
            @Date       : 13-11-2014
        */
        public function update_saved_search_data()
        {
            $cdata['id'] = $this->input->post('id');
            //$sel_contact_id = $this->input->post('sel_contact_id');
            $cdata['name'] = $this->input->post('txt_name');
            //$cdata['search_criteria'] = $this->input->post('search_criteria');
            
            $cdata['search_criteria'] = $this->input->post('search');
            $cdata['min_price'] = str_replace(',', '', $this->input->post('min_price'));
            $cdata['max_price'] = str_replace(',', '', $this->input->post('max_price'));
            $cdata['search_criteria'] = $this->input->post('search_criteria');
            $cdata['bedroom'] = $this->input->post('beds');
            $cdata['bathroom'] = $this->input->post('baths');
            /*$cdata['min_area'] = trim($this->input->post('min_area'));
            $cdata['max_area'] = trim($this->input->post('max_area'));*/
            $cdata['min_year_built'] = $this->input->post('year_built');
            /*$cdata['min_year_built'] = trim($this->input->post('min_year_built'));
            $cdata['max_year_built'] = trim($this->input->post('max_year_built'));*/
            $cdata['fireplaces_total'] = $this->input->post('fireplaces');
            
            $cdata['min_lotsize'] = $this->input->post('lot_size');
            /*$cdata['min_lotsize'] = trim($this->input->post('min_lotsize'));
            $cdata['max_lotsize'] = trim($this->input->post('max_lotsize'));*/
            $cdata['garage_spaces'] = $this->input->post('garage_spaces');
            $cdata['architecture'] = $this->input->post('architecture');
            $cdata['school_district'] = $this->input->post('school_district');
            $cdata['parking_type'] = $this->input->post('parking_type');
            
            $old_url = $this->input->post('old_url');
            $old_url = explode('?',$old_url);
            if(!empty($old_url))
                $cdata['url'] = $old_url[0].'?'.$this->input->post('search_url');
            $cdata['new_construction'] = $this->input->post('new_construction');
            $cdata['bank_owned'] = $this->input->post('bank_owned');
            $cdata['short_sale'] = $this->input->post('short_sale');
            $cdata['mls_id'] = $this->input->post('mls_id');
            $cdata['CDOM'] = $this->input->post('CDOM');
            
            $property_views = $this->input->post('property_views');
            if (!empty($property_views) && $property_views != 'null' && is_array($property_views))
                $cdata['s_view'] = implode('{^}', $property_views);
            $waterfront = $this->input->post('waterfront');
            if (!empty($waterfront) && $waterfront != 'null' && is_array($waterfront))
                $cdata['waterfront'] = implode('{^}', $waterfront);
            $city = $this->input->post('city');
            if (!empty($city) && $city != 'null' && is_array($city))
                $cdata['city'] = implode('{^}', $city);
            $joomla_user_id = trim($this->input->post('joomla_user_id'));
            $edit_domain = $this->input->post('joomla_domain');
            $joomla_sid = $this->input->post('joomla_sid');
            $name = urlencode($cdata['name']);
            $cdata['modified_date'] = date('Y-m-d H:i:s');		
            $this->saved_searches_model->update_record($cdata);
            
            //$url = "http://seattle.livewiresites.com/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$edit_domain."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&sid=".$joomla_sid."&action=update";
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            //$url = $joomla_link."/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$edit_domain."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&sid=".$joomla_sid."&action=update";
            $url = $joomla_link."/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$edit_domain."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces']."&architecture=".$cdata['architecture']."&school_district=".$cdata['school_district']."&waterfront=".$cdata['waterfront']."&view=".$cdata['s_view']."&parking_type=".$cdata['parking_type']."&sid=".$joomla_sid."&action=update";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzip encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
            $response = curl_exec($ch);
            curl_close($ch);
            $response = (json_decode($response, true));
            
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $searchsort_session = $this->session->userdata('contact_savedsearch_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            
            $data = array('selected_view' => 105);
            $this->session->set_userdata('joomla_selected_view_session',$data);
            
            $contact_id = $this->session->userdata('selected_contactid');
            if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id']);
            else
                redirect('user/'.$this->viewName);
            //redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
            //redirect(base_url('user/'.$this->viewName.'/view_record/'.$sel_contact_id));
        }
		
		  public function edit_valuation_searched()
        {
            $id = $this->uri->segment(4);
            $match = array('id'=>$id);
            $result = $this->property_valuation_searches_model->select_records('',$match,'','=');
            $data['editRecord'] = $result;
            $data['main_content'] = "user/".$this->viewName."/add_valuation_searched";
            $this->load->view('user/include/template', $data);
        }
		public function update_valuation_searched_data()
        {
            $cdata['id'] = $this->input->post('id');
            $cdata['report_timeline'] = $this->input->post('report_timeline');
            $cdata['send_report'] = $this->input->post('send_report');
            //$cdata['modified_date'] = date('Y-m-d H:i:s');		
            $this->property_valuation_searches_model->update_record($cdata);
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $searchsort_session = $this->session->userdata('contact_val_searched_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            
            $data = array('selected_view' => 109);
            $this->session->set_userdata('joomla_selected_view_session',$data);
            $contact_id = $this->session->userdata('selected_contactid');
            if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id']);
            else
                redirect('user/'.$this->viewName);
            //redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
            //redirect(base_url('admin/'.$this->viewName.'/view_record/'.$sel_contact_id));
        }
		    public function view_contact_register_popup()
	{
            $id = $this->input->post('search_id');
            $table = "contact_master as cm";
            $group_by = "cm.id";
            $wherestring = array('cm.id'=>$id);
            $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cm.created_date','cm.joomla_domain_name','cm.joomla_user_id','cm.created_by');
            $join_tables = array(
                '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
            );
            $data['contact_register_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
            $this->load->view($this->user_type.'/'.$this->viewName."/view_contact_register_popup",$data);
	}
        
        /*
            @Description: Function for view saved searches popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected saved search id
            @Output     : Saved searches data
            @Date       : 29-10-2014
        */
        public function view_saved_searches_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['saved_search_list'] = $this->saved_searches_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_saved_searches_popup",$data);
	}
        
        /*
            @Description: Function for view favorite popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected favorite id
            @Output     : favorite data
            @Date       : 29-10-2014
        */
        public function view_favorite_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['favorite_list'] = $this->favorite_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_favorite_popup",$data);
	}
        
        /*
            @Description: Function for view properties viewed popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected properties viewed id
            @Output     : properties viewed data
            @Date       : 29-10-2014
        */
        public function view_properties_viewed_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['properties_viewed_list'] = $this->properties_viewed_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_properties_viewed_popup",$data);
	}
        
        /*
            @Description: Function for view last login popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected last login id
            @Output     : last login data
            @Date       : 29-10-2014
        */
        public function view_last_login_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['last_login_list'] = $this->last_login_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_last_login_popup",$data);
	}
        
        /*
            @Description: Function for view valuation searched popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected valuation searched id
            @Output     : Valuation Searched data
            @Date       : 03-12-2014
        */
        public function view_valuation_searched_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['valuation_searched_list'] = $this->property_valuation_searches_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_valuation_searched_popup",$data);
	}
        
        /*
            @Description: Function for view valuation contact popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected valuation contact id
            @Output     : Valuation contact data
            @Date       : 10-03-2015
        */
        public function view_valuation_contact_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['valuation_contact_list'] = $this->property_valuation_contact_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_valuation_contact_popup",$data);
	}
        
        /*
            @Description: Function for view property contact popup data
            @Author     : Sanjay Moghariya
            @Input      : Selected property contact id
            @Output     : Property contact data
            @Date       : 10-03-2015
        */
        public function view_property_contact_popup()
	{
            $id = $this->input->post('search_id');
            $match = array('id'=>$id);
            $data['property_contact_list'] = $this->property_contact_model->select_records('',$match,'','=');
            $this->load->view($this->user_type.'/'.$this->viewName."/view_property_contact_popup",$data);
	}
        
        /*
            @Description: Get Property suggestion list
            @Author     : Sanjay Moghariya
            @Input      : search text
            @Output     : Group suggestion list
            @Date       : 02-07-2015
        */
        function get_property()
        {
            $mls_id = $this->uri->segment(4);
            
            $searchtext = $_GET['term'];
            $json=array();
            $count = 15;
            if(!empty($searchtext))
            {
                $mls_id = !empty($mls_id)?$mls_id:0;
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.'.mls_property_list_master';
                $where = '(CIT LIKE "%' . $searchtext . '%" OR DSR LIKE "%' . $searchtext . '%" OR SD LIKE "%' . $searchtext . '%" OR mls_id LIKE "%' . $searchtext . '%" OR ZIP LIKE "%' . $searchtext . '%" OR full_address LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT full_address');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label' => $row['full_address'],
                        'category' =>'Property Address',
                        'group_value'=> '',
                   );
                }

                //// New group search logic Sanjay Moghariya 27-06-2015
                /// COU - County ///
                $where = '(COU LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT COU');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label' => $row['COU'],
                        'category' =>'County',
                        'group_value'=> '',
                   );
                }

                /// CIT - City ///

                $fields = array('CIT');
                $group_by = 'CIT';
                $where = '(CIT LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count,'','','',$group_by);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label' => $row['CIT'],
                        'category' =>'City',
                        'group_value'=> '',
                    );
                }

                /// DSR - Community/Neighborhood ///
                $where = '(DSR LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT DSR');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label'=> $row['DSR'],
                        'category'=> 'Community/Neighborhood',
                        'group_value'=> '',
                    );
                }

                /// ZIP - Zip Code ///
                $where = '(ZIP LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT ZIP');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label'=> $row['ZIP'],
                        'category'=> 'Zip Code',
                        'group_value'=> '',
                    );
                }

                /// STR - Street Name ///
                $where = '(STR LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT CONCAT_WS(" ",STR,SSUF) as street_name');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label'=> $row['street_name'],
                        'category'=> 'Street Name',
                        'group_value'=> '',
                    );
                }

                /// LN - MLS# ///
                $where = '(LN LIKE "' . $searchtext . '%") AND mls_id = '.$mls_id;
                $fields = array('DISTINCT LN');
                $result = $this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label'=> $row['LN'],
                        'category'=> 'MLS#',
                        'group_value'=> '',
                    );
                }

                /// SD - School District ///
                $table = $parent_db . '.mls_school_data';
                $fields = array('DISTINCT(school_district_code),school_district_description');
                $where = '(school_district_description LIKE "%' . $searchtext . '%") AND mls_id = '.$mls_id;
                $result = $this->contacts_model->getmultiple_tables_records($table, $fields, '', '', '', $where,'',$count);
                foreach($result as $row)
                {
                    $json[]=array(
                        'label'=> $row['school_district_description'],
                        'category'=> 'School District',
                        'group_value'=> $row['school_district_code'],
                    );
                }

                $json[]=array(
                    'label' => $searchtext,
                    'category' =>'Keyword',
                    'group_value'=> '',
               );
                //// End New group search logic Sanjay Moghariya 27-06-2015
            }
            echo json_encode($json);
        }
}