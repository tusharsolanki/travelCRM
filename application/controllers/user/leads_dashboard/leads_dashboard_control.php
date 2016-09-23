<?php 
/*
    @Description: Joomla Dashboard controller
    @Author     : Sanjay Moghariya
    @Input      : 
    @Output     : 
    @Date       : 27-12-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class leads_dashboard_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
	
        $result = check_joomla_tab_setting($this->user_session['id']);
        if(!empty($result) && $result[0]['lead_dashboard_tab'] == '0')
            redirect('user/dashboard');
        
        $this->load->model('contacts_model');
        $this->load->model('user_management_model');
        $this->load->model('contact_masters_model');
        $this->load->model('last_login_model');
        $this->load->model('marketing_library_masters_model');
        $this->load->model('interaction_plans_model');
		$this->load->model('interaction_model');
        $this->load->model('sms_campaign_master_model');
        $this->load->model('favorite_model');
        $this->load->model('properties_viewed_model');
        $this->load->model('saved_searches_model');
        $this->load->model('user_registration_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('imageupload_model');
		$this->load->model('ws/property_valuation_searches_model');
		$this->load->model('property_contact_model');
		$this->load->model('contact_type_master_model');
		
		$this->obj = $this->contacts_model;
		
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'user';
        
        define("SECOND", 1);
        define("MINUTE", 60 * SECOND);
        define("HOUR", 60 * MINUTE);
        define("DAY", 24 * HOUR);
        define("MONTH", 30 * DAY);
        
    }
	

    /*
        @Description: Function for joomla dashboard view
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all joomla leads list
        @Date       : 27-12-2014
    */
    public function index()
    {	
		//check user right
		check_rights('lead_dashboard');
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';$flag_category='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        $flag_category = $this->input->post('flag_category');// Click on contact category then Use this flag. 
        $current_date = $this->input->post('new_contact');
        $reg_source = $this->input->post('registration_source');
        $sel_lead_type = $this->input->post('lead_type');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch' || $allflag == 'changescriteria')) {
            $this->session->unset_userdata('leads_dashboard_sortsearchpage_data');
        }
        $data['sortfield']		= 'cm.id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');

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
        /*if(!empty($current_date))
        {
            //$searchtext = $this->input->post('searchtext');
            $data['current_date'] = stripslashes($current_date);
        } else {

                 if(!empty($searchsort_session['current_date1'])) 
                 {
                    $current_date1 =  $searchsort_session['current_date1'];
                    $data['current_date'] = $searchsort_session['current_date1'];
                }
        }*/
        if(!empty($searchtext))
        {
                $data['searchtext'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];
                }
            }
        }
        if(!empty($perpage))
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
        
        // Search Criteria Sanjay Moghariya 13-07-2105
        if(!empty($reg_source))
            $data['search_reg_source'] = $reg_source;
        else
        {
            if(!empty($searchsort_session['search_reg_source'])) {
                $data['search_reg_source'] = $searchsort_session['search_reg_source'];
                $reg_source = $searchsort_session['search_reg_source'];
            }
        }
        
        if(!empty($sel_lead_type))
            $data['search_lead_type'] = $sel_lead_type;
        else
        {
            if(!empty($searchsort_session['search_lead_type'])) {
                $data['search_lead_type'] = $searchsort_session['search_lead_type'];
                $sel_lead_type = $searchsort_session['search_lead_type'];
            }
        }
        // Search Criteria Sanjay Moghariya 13-07-2105

        $config['base_url'] = site_url($this->user_type.'/'."leads_dashboard/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        
        if((!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch'))) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        if(!empty($current_date))
        {
            $icdata['login_id'] = $this->user_session['id'];

            $icdata['joomla_lead_last_seen'] = date('Y-m-d H:i:s');
            $this->obj->update_last_seen($icdata);
            
            
            $leads_dashboard_sortsearchpage_data = array(
            'current_date1'=>$current_date,
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
            'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
            'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
            'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
            //pr($leads_dashboard_sortsearchpage_data);
            $this->session->set_userdata('leads_dashboard_sortsearchpage_data', $leads_dashboard_sortsearchpage_data);
            //exit;
            if(!empty($current_date))
            {
                //exit;
                redirect('user/'.$this->viewName); 
            }
        }
        $data['current_date'] = 0;
        if(!empty($searchsort_session['current_date1']) && $searchsort_session['current_date1'] != '0000-00-00 00:00:00')
            $data['current_date'] = $searchsort_session['current_date1'];
        $table = "contact_master as cm";
        $group_by = "cm.id";
        
        //// Search criteria Sanjay Moghariya 10-07-2015 ////
	$wherestring = '';
        if(!empty($reg_source))
        {
            $wherestring .= 'cm.domain_id ='.$reg_source.' AND ';
        }
        if(!empty($sel_lead_type))
        {
            $wherestring .= 'cm.joomla_contact_type ="'.$sel_lead_type.'" AND ';
        }
        //// End Search criteria Sanjay Moghariya 10-07-2015 ////
        
        if($flag_category == 1)
        {
            if($searchtext == 'Inactive')
            {   
                $wherestring .= '(cm.created_by IN ('.$this->user_session['id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR uctl.user_id = '.$this->user_session['agent_user_id'].') AND (cm.created_type = 6 AND cm.joomla_category != "Inactive Prospect" AND cm.joomla_category != "Bogus") AND ';
            }
            else {
                $wherestring .= 'cm.created_type = 6 AND cm.joomla_category = "'.$searchtext.'" AND ';
            }
        }
        else
        {
           
            /*if(!empty($current_date1) && (date('Y-m-d H:i:s', strtotime($current_date1)) == $current_date1 || $current_date1 == '0000-00-00 00:00:00'))
            {
                 $wherestring = '(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR uctl.user_id = '.$this->user_session['agent_user_id'].') AND (cm.created_type = 6) AND (cm.created_date > "'.date('Y-m-d H:i:s',strtotime($current_date1)).'")';
            }
            else
            {*/
                 $wherestring .='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].' OR uctl.user_id = '.$this->user_session['agent_user_id'].') AND (cm.created_type = 6) AND ';
            //}
        }
        $wherestring = trim($wherestring,'AND ');
        //,'sum(DISTINCT jrt.views) as total_properties_viewed'
        $fields = array('cm.*','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name'/*,'GROUP_CONCAT(DISTINCT ctt.tag separator \',\') as tag_name'*/,'cet.email_address','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as assigned_agent_name','CONCAT_WS(" ",uml.first_name,uml.middle_name,uml.last_name) as assigned_lender_name','cpt.phone_no','cet.id as em_id'/*,count(DISTINCT jrl.id) as total_visits'*/,'count(DISTINCT jrb.id) as total_favorites','count(DISTINCT jrss.id) as total_saved_searches','count(DISTINCT jrt.id) as total_properties_viewed','log.log_date','count(DISTINCT ip_call.id) as calls_made_count, ip_call.task_completed_date','count(DISTINCT ecrt.id) as emails_sent_count','ecrt.sent_date','count(DISTINCT cct.id) as calls_made_count1','cct.created_date as cct_last_call_date','count(DISTINCT cct.id)+count(DISTINCT ip_call.id) as total_calls_made','count(DISTINCT jpc.id) as total_contactform');//,'count(DISTINCT ip_email.id) as emails_sent_count', 'ip_email.task_completed_date as email_task_completed_date';
        $join_tables = array(
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
            '(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
            //'user_contact_trans as uct'=>'uct.contact_id = cm.id',
            //'user_master as um'=>'uct.user_id = um.id',
            
            '(select * from user_contact_trans where agent_type !="Lender") as uct'=>'uct.contact_id = cm.id',
            'user_master as um'=>'uct.user_id = um.id',
            
            '(select * from user_contact_trans where agent_type="Lender") as uctl'=>'uctl.contact_id = cm.id',
            'user_master as uml'=>'uctl.user_id = uml.id',
            
            '(select * from joomla_rpl_property_contact where form_type="property") as jpc'=>'jpc.lw_admin_id = cm.id',
            
            //'joomla_rpl_log as jrl'=>'jrl.lw_admin_id = cm.id',
            'joomla_rpl_bookmarks as jrb'=>'jrb.lw_admin_id = cm.id',
            'joomla_rpl_track as jrt'=>'jrt.lw_admin_id = cm.id',
            'joomla_rpl_savesearch as jrss'=>'jrss.lw_admin_id = cm.id',
            //'contact_tag_trans as ctt'=>'ctt.contact_id = cm.id',
            '(select * from joomla_rpl_log order by log_date desc) as log'=>'log.lw_admin_id = cm.id',
            '(select * from interaction_plan_contact_communication_plan where interaction_type=4 and is_done="1" order by task_completed_date desc) as ip_call'=>'ip_call.contact_id = cm.id',
            //'(select * from interaction_plan_contact_communication_plan where interaction_type=6 and is_done="1" order by task_completed_date desc) as ip_email'=>'ip_email.contact_id = cm.id',
            '(select * from email_campaign_recepient_trans where is_send="1" order by sent_date desc) as ecrt'=>'ecrt.contact_id = cm.id',
            '(select * from contact_conversations_trans where interaction_type=4 order by created_date desc) as cct'=>'cct.contact_id = cm.id'
        );
        
        if(!empty($searchtext))
        {
            if($flag_category == 1)
            {
                $match=array();
            }
            else
            {
                $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name)'=>$searchtext,'CONCAT_WS(" ",um.first_name,um.last_name)'=>$searchtext,'CONCAT_WS(" ",uml.first_name,uml.middle_name,uml.last_name)'=>$searchtext,'CONCAT_WS(" ",uml.first_name,uml.last_name)'=>$searchtext,'cm.joomla_contact_type'=>$searchtext,'cm.price_range_from'=>$searchtext,'cm.price_range_to'=>$searchtext,'cm.joomla_domain_name'=>$searchtext,'cm.joomla_address'=>$searchtext,'cpt.phone_no'=>$searchtext,'cm.joomla_category'=>$searchtext);
            }
            $datalist =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
		//	echo $this->db->last_query();exit;
            $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'','1');
        }
        else
        {
            $datalist =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$wherestring,'','1');
        }
        
        //pr($datalist);exit;
        
        if(!empty($datalist) && count($datalist) > 0)
        {
            foreach($datalist as $key=>$row)
            {
                if(!empty($row['created_date']) && $row['created_date'] != '0000-00-00 00:00:00')
                {
                    $datalist[$key]['created_date1'] = $row['created_date'];
                    $datalist[$key]['created_date'] = $this->date_difference($row['created_date']);
                }
                
                //Last Login
                if(!empty($row['log_date']) && $row['log_date'] != '0000-00-00 00:00:00')
                {
                    $datalist[$key]['last_login'] = $row['log_date'];
                    $datalist[$key]['last_login_words'] = $this->date_difference($row['log_date']);
                }
                
                // For Total Calls Made
                if(!empty($row['task_completed_date']) && $row['task_completed_date'] != '0000-00-00 00:00:00')
                {
                    $datalist[$key]['last_calls_made_date'] = $row['task_completed_date'];
                    $datalist[$key]['last_calls_made_words'] = $this->date_difference($row['task_completed_date']);
                }
                
                if(strtotime($row['task_completed_date']) < strtotime($row['cct_last_call_date']))
                {
                    $datalist[$key]['last_calls_made_date'] = $row['cct_last_call_date'];
                    if(!empty($row['cct_last_call_date']) && $row['cct_last_call_date'] != '0000-00-00 00:00:00' );
                    {
                        $dt_word = $this->date_difference($row['cct_last_call_date']);
                        $datalist[$key]['last_calls_made_words'] = $dt_word;
                    }
                }
                
                // For Total Emails Sent
                if(!empty($row['sent_date']) && $row['sent_date'] != '0000-00-00 00:00:00')
                {
                    $datalist[$key]['last_emails_sent_date'] = $row['sent_date'];
                    $datalist[$key]['last_emails_sent_words'] = $this->date_difference($row['sent_date']);
                }
            }
        }
 
        //// Search criteria Sanjay Moghariya 10-07-2105 ////
        //Assigned domain list
        $table = 'user_domain_trans as udt';
        $fields = array('udt.user_id as id,cwdm.domain_name');
        $join_tables = array('child_website_domain_master as cwdm'=>'udt.user_id = cwdm.id');
        $match = array('udt.user_id'=>$this->user_session['user_id']);
        $data['sel_assigned_domain'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=');
        //// End Search criteria Sanjay Moghariya 10-07-2105 ////
        
        $data['interaction_type'] = $this->contact_masters_model->select_records1('','','','','','','','name','desc', 'interaction_plan__plan_type_master');
        $match = array();
        $data['disposition_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
        //pr($datalist);exit;
        $data['datalist'] = $datalist;        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];
        $leads_dashboard_sortsearchpage_data = array(
            'current_date1'=>!empty($data['current_date'])?$data['current_date']:'',
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
            'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
            'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
            'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0',
            'search_reg_source' => !empty($reg_source)?$reg_source:'',
            'search_lead_type' => !empty($sel_lead_type)?$sel_lead_type:'',
        );
        $this->session->set_userdata('leads_dashboard_sortsearchpage_data', $leads_dashboard_sortsearchpage_data);
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
    
    /*
        @Description: Function for convert date into words 
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all joomla leads list
        @Date       : 27-12-2014
    */
    function date_difference($datetime)
    {   
        $date = date('Y-m-d H:i:s');
        
        $delta = strtotime($date) - strtotime($datetime);

        if ($delta < 1 * MINUTE)
        {
            return $delta == 1 ? "1 sec ago" : $delta . " sec ago";
        }
        if ($delta < 2 * MINUTE)
        {
          return "a min ago";
        }
        if ($delta < 45 * MINUTE)
        {
            return floor($delta / MINUTE) . " min ago";
        }
        if ($delta < 90 * MINUTE)
        {
          return "an hr ago";
        }
        if ($delta < 24 * HOUR)
        {
          return floor($delta / HOUR) . " hr ago";
        }
        if ($delta < 48 * HOUR)
        {
          return "1 day ago";
        }
        if ($delta < 30 * DAY)
        {
            return floor($delta / DAY) . " days ago";
        }
        if ($delta < 12 * MONTH)
        {
          $months = floor($delta / DAY / 30);
          return $months <= 1 ? "1 month ago" : $months . " month ago";
        }
        else
        {
            $years = floor($delta / DAY / 365);
            return $years <= 1 ? "1 yr ago" : $years . " yr ago";
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
        $data['interaction_plan_status'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
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
		
		$data['contact_list'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
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
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['status'] = '1';

		$interaction_plan_id = $this->contacts_model->insert_record($cdata);
		
		///////////////////////////// Interaction Plan Time Trans Data /////////////////////////////////////////
		
		$tcdata['interaction_plan_id'] = $interaction_plan_id;
		$tcdata['interaction_time_type'] = '1';
		$tcdata['interaction_time'] = date('Y-m-d H:i:s');
		$tcdata['created_by'] = $this->user_session['id'];
		
		$this->contacts_model->insert_time_record($tcdata);
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$interaction_contacts = $this->input->post('finalcontactlist');
		
		$interaction_contacts = explode(",",$interaction_contacts);
		
		//pr($interaction_contacts);
		
		if(!empty($interaction_contacts))
		{
			
			$table = "interaction_plan_interaction_master as ipim";
			$fields = array('ipim.*','ipptm.name');
			$join_tables = array(
								'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type'
								);
			$group_by='ipim.id';
			
			$match = array('ipim.interaction_plan_id'=>$plan_id);
			$interactionlist = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by);
			
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
					$icdata['created_by'] = $this->user_session['id'];
					$icdata['status'] = '1';
					
					$this->contacts_model->insert_contact_trans_record($icdata);
				
				//////////// Converation History
						
				if(!empty($row))
				{
					$data_conv['contact_id'] = $row;
					$data_conv['plan_id'] = $interaction_plan_id;
					$data_conv['plan_name'] = $this->input->post('txt_plan_name');
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '2';
					$data_conv['created_by'] = $this->user_session['id'];
					$data_conv['status'] = '1';
					$this->contacts_model->insert_contact_converaction_trans_record($data_conv);
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
								
								$interaction_res = $this->contacts_model->get_contact_interaction_task_date($interaction_id,$row);
								
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
							$icdata1['created_by'] = $this->user_session['id'];
							
							$this->interaction_plans_model->insert_contact_communication_record($icdata1);
							
							unset($icdata1);
						}
						
					}	
					
					/////////////////////////////////////////////////////////////////////////////////
					
					unset($icdata);
				}
			}
		}
		
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	

        $leads_dashboard_sortsearchpage_data = array(
            'sortfield'  => 'ipm.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0,
            'search_reg_source' => '',
            'search_lead_type' => ''
        );
        $this->session->set_userdata('leads_dashboard_sortsearchpage_data', $leads_dashboard_sortsearchpage_data);
        
        redirect('user/'.$this->viewName);				
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
 
    /*
    @Description: Function for Search contact ajax
    @Author: Mit Makwana
    @Input: - Details of Search contact ajax
    @Output: - List of contacts 
    @Date: 14-07-2014
    */
	
	public function search_contact_ajax()
    {
	
		$config['per_page'] = 50;	
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
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$data['contact_list'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view("user/".$this->viewName."/add_contact_popup_ajax", $data);
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
        check_rights('lead_dashboard_edit'); 
		$id = $this->uri->segment(4);
		$match = array("ipm.id"=>$this->uri->segment(4));
		$table = " interaction_plan_master as ipm";
		$fields = array('ipm.*','csm.name as plan_status_name','count(ipim.id) as total_interactions');
		
		$join_tables = array(
							'interaction_plan__status_master as csm' => 'csm.id = ipm.plan_status',
							'interaction_plan_interaction_master as ipim' => 'ipim.interaction_plan_id = ipm.id'
						);
		
	    $data['editRecord'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','');	 
		
		if(isset($data['editRecord'][0]['status']) && ($data['editRecord'][0]['status'] == '0'))
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		
	  	//pr($data['editRecord']);
		$data['interaction_plan_status'] = $this->contact_masters_model->select_records1('','','','=','','','','name','asc','interaction_plan__status_master');	
		
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
		
		
		
	    $data['contact_list'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
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
		
		$data['contacts_data'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		//pr($data['contacts_data']);
		
		///////////////////////////////////////////////////////////////////////////	
		
		$match = array('interaction_plan_id'=>$id);
        $data['interaction_plan_time_trans'] = $this->contacts_model->select_records_plan_time_trans('',$match,'','=','','','','id','asc');
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		///////////////////////////////////////////////////////////////////////////	
		
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
		$cdata['id'] = $this->input->post('id');
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		$id = $this->input->post('id');
		$match = array("id"=>$id);
		$interaction_plan_data_old = $this->contacts_model->select_records('',$match,'','=','','','','','','');	 
		
		//pr($interaction_plan_data_old);
		
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
		$cdata['modified_by'] = $this->user_session['id'];
		
		//pr($cdata);exit;

		$this->contacts_model->update_record($cdata);
		
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
		
		$old_contacts_data = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
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
		
		$interaction_list =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
		
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
								$uictdata['modified_by'] = $this->user_session['id'];
								//pr($uictdata);
								
								$this->contacts_model->update_record_interaction_contact($uictdata);
								
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
										$contact_interaction_plan_interaction_id = $this->interaction_plans_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
										
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
											$plan_contact_data = $this->contacts_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
											
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
											
											$interaction_res = $this->interaction_plans_model->get_contact_interaction_task_date($interaction_id,$row['id']);
											
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
										
										//pr($iccdata1);exit;
										
										$this->interaction_plans_model->update_contact_communication_record($iccdata1);
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
							$uictdata['modified_by'] = $this->user_session['id'];
							//pr($uictdata);
							
							$this->contacts_model->update_record_interaction_contact($uictdata);
							
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
										$contact_interaction_plan_interaction_id = $this->interaction_plans_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
										
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
											$plan_contact_data = $this->contacts_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
											
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
											
											$interaction_res = $this->interaction_plans_model->get_contact_interaction_task_date($interaction_id,$row['id']);
											
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
										
										//pr($iccdata1);exit;
										
										if(!empty($contact_interaction_plan_interaction_id->id))
										{
											$this->interaction_plans_model->update_contact_communication_record($iccdata1);
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
			$this->contacts_model->delete_contact_trans_record_array($interaction_plan_id,$deletecontactdata);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			$this->contacts_model->delete_contact_communication_plan_trans_record_array($interaction_plan_id,$deletecontactdata);
			
			//////////////////////////////////////////////////////////////////////////////////
			
			/* Delete SMS and Email Campaign data */
			$match = array('interaction_plan_id'=>$interaction_plan_id);
			$interaction = $this->interaction_plans_model->select_records('',$match,'','=');
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
					$data_conv['created_by'] = $this->user_session['id'];
					$data_conv['status'] = '1';
					$this->contacts_model->insert_contact_converaction_trans_record($data_conv);
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
					$data_conv['created_by'] = $this->user_session['id'];
					$data_conv['status'] = '1';
					$this->contacts_model->insert_contact_converaction_trans_record($data_conv);
				
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
					$icdata['created_by'] = $this->user_session['id'];
					$icdata['status'] = '1';
					
					$this->contacts_model->insert_contact_trans_record($icdata);
					
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
								$plan_contact_data = $this->contacts_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
								
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
								
								$interaction_res = $this->interaction_plans_model->get_contact_interaction_task_date($interaction_id,$row);
								
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
							
							$this->interaction_plans_model->insert_contact_communication_record($iccdata);
							
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
							$userdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
							//pr($userdata);
							$agent_name = '';
							if(!empty($row1['created_by']))
							{
								$table ="login_master as lm";   
								$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
								$join_tables = array('user_master as um'=>'lm.user_id = um.id');
								$wherestring = 'lm.id = '.$row1['created_by'];
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
		
		//exit;
		
		//////////////////////////////////////////////////////////////////
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		//$email_id = $this->input->post('id');
		//$pagingid = $this->contacts_model->getemailpagingid($email_id);
                $selected_view_session = $this->session->userdata('selected_view_session');
                if($selected_view_session['selected_view'] == '2')
                    $searchsort_session = $this->session->userdata('premium_leads_dashboard_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
                $pagingid = $searchsort_session['uri_segment'];
                if($selected_view_session['selected_view'] == '2')
                    $sel_val = 'premium_plan';
                else
                    $sel_val = 'my_plan';
                
                //redirect(base_url('user/'.$this->viewName.'/'.$pagingid.'#'.$sel_val));
                redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
        //redirect('user/'.$this->viewName);				
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
		$this->contacts_model->update_record($cdata);
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
				$this->contacts_model->insert_doc_trans_record($cddata);
			}
			else
				$this->contacts_model->update_doc_trans_record($cddata);
				
			unset($cddata);
		}
		
		$data['document_trans_data'] = $this->contacts_model->select_document_trans_record($this->input->post('id'));
		$this->load->view($this->user_type.'/'.$this->viewName."/contact_document_ajax",$data);
		
	}


    /*
    @Description: Get Details of contacts
    @Author     : Sanjay Moghariya (copy fron contacts_control)
    @Input      : Id of contacts member whose details want to change
    @Output     : Details of stff which id is selected for update
    @Date       : 08-12-2014
    */
    public function view_record()
    {
       //check user right
		check_rights('lead_dashboard');
	   $id = $this->uri->segment(4);
	   $match = array();
        $data['email_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');		
		$data['field_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__additionalfield_master');
		$data['phone_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['website_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		$data['contact_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
		$data['method_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__method_master');
		
		
	/*	$match = array('status'=>'1');
		$data['communication_plans'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','plan_name','asc', 'interaction_plan_master');*/
		
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
			$wherestring='(ipim.assign_to = '.$this->user_session['id'].' OR ipm.created_by = '.$this->user_session['id'].')';
			$data['communication_plans'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,$wherestring);
			
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
		
		
			//$match = array('ipm.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
			$match = 'ipm.status = "1" AND ipm.created_by NOT IN ('.$this->user_session['agent_id'].') AND ipim.assign_to = '.$this->user_session['id'];
			$data['admin_interection_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by);

		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $where = "um.status = '1' AND um.user_type = '3' AND um.id IN (".$this->user_session['agent_user_id'].")";
		$data['user_list'] = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','asc','',$where);
		$data['user_add_list'] = $this->contacts_model->select_user_contact_trans_record($id);
		$data['email_trans_data'] = $this->contacts_model->select_email_trans_record($id,'is_default','desc');
		$data['phone_trans_data'] = $this->contacts_model->select_phone_trans_record($id,'is_default','desc');
		$data['address_trans_data'] = $this->contacts_model->select_address_trans_record($id);
		$data['website_trans_data'] = $this->contacts_model->select_website_trans_record($id);
		$data['field_trans_data'] = $this->contacts_model->select_field_trans_record($id);
		$data['profile_trans_data'] = $this->contacts_model->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->contacts_model->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->contacts_model->select_tag_record($id);
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record($id,'','1');
		$data['communication_trans_data'] = $this->contacts_model->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->contacts_model->select_document_trans_record($id);
		//$data['communication_trans_data'] = $this->contacts_model->select_communication_trans_record($id);
		
		//////////// Old ////////////////////
		
        /*$match = array('id'=>$id);
        $result = $this->contacts_model->select_records('',$match,'','=');*/
		
		/////////// End ////////////////////
		
		//////////// New ////////////////////
		
        $match = array('id'=>$id);
        $result = $this->contacts_model->select_records('',$match,'','=');
		
		/////////// End ////////////////////
		
		$match1 = array("id"=>$this->user_session['id'],"is_buyer_tab"=> "1");
		
		$data['right_buyer'] = $this->contact_masters_model->select_records1('',$match1,'','=','','','','id','desc','login_master');
		$data['editRecord'] = $result;
                if(!empty($result))
                {
                    if(!empty($result[0]['created_date']) && $result[0]['created_date'] != '0000-00-00 00:00:00')
                    {
                        $data['registered_date_word'] = $this->date_difference($result[0]['created_date']);
                    }
                }
		
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
		
		/*$where_in_val = array(1,12);
		
		$where_in = array('cct.log_type'=>$where_in_val);*/
		$data['conversations'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','special_case_task','',$group_by,$where/*,$where_in*/);
		//pr($data['conversations']);exit;
		/////////////////////////////////////////////End Conversations/////////////////////////////////////////
		
		/////////////////////////////////////////////////////  personal Touches ///////////////////////////////////
		$table = "interaction_plan_contact_personal_touches as ipcp";
		$fields = array('ipcp.id','ipcp.task','iptm.name','ipcp.followup_date','ipcp.is_done');
		$join_tables = array(
								'interaction_plan__plan_type_master as iptm' => 'iptm.id = ipcp.interaction_type'
							);
		$group_by='ipcp.id';
		$where=array('contact_id'=>$id,'interaction_type'=>7,'is_done'=>"1");
		$data['personale_touches'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','followup_date','asc',$group_by,$where);
		
		$data['interaction_type'] = $this->contact_masters_model->select_records1('','','','','','','','name','desc', 'interaction_plan__plan_type_master');
		
		//for tab3 searches
		$data['selected_contact_id'] = $id;
		
 				// Saved Searches Sanjay Moghariya. 29-10-2104
		        $config1['per_page'] = '10';
                $config1['base_url'] = site_url($this->user_type.'/'."leads_dashboard/view_record_index_savser/".$id."/");
                $config1['is_ajax_paging'] = TRUE;
                $config1['paging_function'] = 'ajax_paging';
                $config1['uri_segment'] = 0;
                $uri_segment1 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_saved_searches = $this->saved_searches_model->select_records('',$match,'','=','',$config1['per_page'],$uri_segment1,'id','desc');
                $config1['total_rows']= $this->saved_searches_model->select_records('',$match,'','=','','','','','','','1');
                
                $this->pagination->initialize($config1);
                $data['pagination1'] = $this->pagination->create_links();
				
				
				// Valuation Searched Sanjay Moghariya. 02-12-2104
				$config5['per_page'] = '10';
                $config5['base_url'] = site_url($this->user_type.'/'."leads_dashboard/view_record_index_val_searched/".$id."/");
                $config5['is_ajax_paging'] = TRUE; 
                $config5['paging_function'] = 'ajax_paging';
                $config5['uri_segment'] = 0;
                $uri_segment5 = 0;

                $match = array('lw_admin_id'=>$id);
                $result_val_searched = $this->property_valuation_searches_model->select_records('',$match,'','=','',$config5['per_page'],$uri_segment5,'id','desc');
				
				
				
				$data['result_valuation_searched'] = $result_val_searched;
				$data['result_saved_searches'] = $result_saved_searches;
                $config5['total_rows']= $this->property_valuation_searches_model->select_records('',$match,'','=','','','','','','','1');
                $this->pagination->initialize($config5);
                $data['pagination5'] = $this->pagination->create_links();

                $tabid = $this->session->userdata('joomla_dbord_selected_view_session');
                $data['tabid']	=	$tabid['selected_view'];
                                
                // Properties Tab Sanjay Moghariya. 11-07-2105
                $config3['per_page'] = '10';
                $config3['base_url'] = site_url($this->user_type.'/'."leads_dashboard/view_record_index_prop_view/".$id."/");
                $config3['is_ajax_paging'] = TRUE;
                $config3['paging_function'] = 'ajax_paging';
                $config3['uri_segment'] = 0;
                $uri_segment3 = 0;

                $parent_db = $this->config->item('parent_db_name');
                $match = 'jrt.lw_admin_id = '.$id.' AND mplm.LN !=""';
                $fields = array('jrt.id,jrt.log_date,jrt.views,mplm.mls_id,mplm.LN,mplm.full_address,mplm.CIT,mplm.PTYP,mplm.ST,mplm.ASF,mplm.BR,mplm.BTH,mplm.display_price,mplm.PIC,mplm.Internal_MLS_ID,mplm.MR');
                $table = 'joomla_rpl_track as jrt';
                $join_tables = array($parent_db.'.mls_property_list_master as mplm'=>'mplm.LN = jrt.mlsid');
                $data['result_properties_viewed'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config3['per_page'],$uri_segment3,'jrt.id','desc','',$match);
                $config3['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','jrt.id','desc','',$match,'','1');
                if(!empty($data['result_properties_viewed']))
                {
                    foreach($data['result_properties_viewed'] as $prop_view)
                    {
                        if(!empty($prop_view['mls_id']) && $prop_view['mls_id'] == 1)
                        {
                            $table1 = $parent_db.'.mls_property_image';
                            $fields1 = array('image_name,image_medium_url,image_small_url,listing_number');
                            $match1 = array_map(function($element) {
                                        return $element['LN'];
                                      }, $data['result_properties_viewed']);
                            $image_list =  $this->contacts_model->getmultiple_tables_records($table1,$fields1,'','','','','','','','image_name','asc','','',array('listing_number'=>$match1));
                            
                            if(!empty($image_list))
                            {
                                foreach ($image_list as $row1)
                                {
                                    if(empty($data['image_list']) || !array_key_exists($row1['listing_number'],$data['image_list']))
                                        $data['image_list'][$row1['listing_number']] = $row1['image_name'];
                                } 
                            }
                        }
                    }
                }
                //$data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','=','',$config3['per_page'],$uri_segment3,'id','desc');	
                //$config3['total_rows'] = $this->properties_viewed_model->select_records('',$match,'','=','','','','','','','1');
                $this->pagination->initialize($config3);
                $data['pagination3'] = $this->pagination->create_links();
                // End Properties Tab Sanjay Moghariya. 11-07-2105
                                
			// Last Login
                $fields = array('log_date,lw_admin_id');
                $match = array('lw_admin_id'=>$id);
                $last_log = $this->last_login_model->select_records($fields,$match,'','=','','1','','log_date','desc');
                if(!empty($last_log) && count($last_log) > 0 && $last_log[0]['log_date'] != '0000-00-00 00:00:00')
                {
                    $ss = $this->date_difference($last_log[0]['log_date']);
                    $data['last_login_words'] = $ss;
                }
                $fields = array('log_date,lw_admin_id');
                $match = array('lw_admin_id'=>$id);
                $data['last_login_data'] = $this->last_login_model->select_records($fields,$match,'','=','','5','','log_date','desc');

                $selected_view_session = $this->session->userdata('joomla_selected_view_session');
                if(!empty($selected_view_session['selected_view']))
                    $data['tabid'] = $selected_view_session['selected_view'];
                else
                    $data['tabid'] = 1;
                
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
		$this->load->view('user/include/template',$data);
    }


   /*



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
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
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
            $data['searchtext5'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext5'] = $searchsort_session['searchtext'];
                    $searchtext =  mysql_real_escape_string($data['searchtext5']);
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
        $config['base_url'] = site_url($this->user_type.'/'."leads_dashboard/view_record_index_val_searched/".$id."/");
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
            $config['total_rows'] = $this->property_valuation_searches_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_valuation_searched'] = $this->property_valuation_searches_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            $config['total_rows']= $this->property_valuation_searches_model->select_records('',$match,'','=','','','','','','','1');

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
            @Description: Function for load insert/edit Saved searches form
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Open form
            @Date       : 27-12-2014
        */
        public function add_saved_searches()
        {
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


        /*
            @Description: Function for Insert Saved searches data
            @Author     : Sanjay Moghariya
            @Input      : Saved searches data
            @Output     : Listing
            @Date       : 27-12-2014
        */
        public function insert_saved_search_data()
        {
            $cdata['name'] = $this->input->post('txt_name');
            //$cdata['search_criteria'] = $this->input->post('search_criteria');
            $cdata['domain'] = $this->input->post('joomla_domain');
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
            
            //pr($cdata);exit;
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
            $data = array('selected_view' => 3);
            $this->session->set_userdata('joomla_dbord_selected_view_session',$data);
            $contact_id = $this->session->userdata('selected_contactid');
			if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id'].'/3');
            else
                redirect('user/'.$this->viewName);
        }
		
		/*
            @Description: Function for load edit Saved searches form
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Open form
            @Date       : 27-12-2014
        */
        public function edit_saved_searches()
        {
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
            @Description: Function for load edit Valuation searched form
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Open edit view
            @Date       : 03-12-2014
        */
        public function edit_valuation_searched()
        {
		    $id = $this->uri->segment(4);
			$match = array('id'=>$id);
            $result = $this->property_valuation_searches_model->select_records('',$match,'','=');
            $data['editRecord'] = $result;
            $data['main_content'] = "user/".$this->viewName."/add_valuation_searched";
            $this->load->view('user/include/template', $data);
        }
        /*
            @Description: Function for Update valuation searched
            @Author     : Sanjay Moghariya
            @Input      : Update details of valuation
            @Output     : List with updated valuation details
            @Date       : 03-12-2014
        */
        public function update_valuation_searched_data()
        {
			$cdata['id'] = $this->input->post('id');
            $cdata['report_timeline'] = $this->input->post('report_timeline');
            $cdata['send_report'] = $this->input->post('send_report');
            $this->property_valuation_searches_model->update_record($cdata);
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $searchsort_session = $this->session->userdata('contact_val_searched_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            
            $data = array('selected_view' => 3);
            $this->session->set_userdata('joomla_dbord_selected_view_session',$data);
            $contact_id = $this->session->userdata('selected_contactid');
            if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id'].'/3');
            else
                redirect('user/'.$this->viewName);
        }

        /*
            @Description: Function for Update saved searches
            @Author     : Sanjay Moghariya
            @Input      : Update details of saved searches
            @Output     : List with updated saved searches details
            @Date       : 27-12-2014
        */
        public function update_saved_search_data()
        {
            $cdata['id'] = $this->input->post('id');
            $cdata['name'] = $this->input->post('txt_name');
            
            $cdata['modified_date'] = date('Y-m-d H:i:s');		
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
            
            $joomla_user_id = $this->input->post('joomla_user_id');
            $edit_domain = $this->input->post('joomla_domain');
            $joomla_sid = $this->input->post('joomla_sid');
            
            $this->saved_searches_model->update_record($cdata);
            $name = urlencode($cdata['name']);
            /*$url = "http://seattle.livewiresites.com/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$edit_domain."&min_price=".$cdata['min_price']."
            &max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."
            &min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces'];
            */
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            /*$url = $joomla_link."/libraries/api/crmsavesearch.php?uid=".$joomla_user_id."&name=".$name."&domain=".$edit_domain."&min_price=".$cdata['min_price']."&max_price=".$cdata['max_price']."&bedroom=".$cdata['bedroom']."&bathroom=".$cdata['bathroom']."&min_area=".$cdata['min_area']."&max_area=".$cdata['max_area']."&min_year_built=".$cdata['min_year_built']."&max_year_built=".$cdata['max_year_built']."&fireplaces_total=".$cdata['fireplaces_total']."&min_lotsize=".$cdata['min_lotsize']."&max_lotsize=".$cdata['max_lotsize']."&garage_spaces=".$cdata['garage_spaces'];*/
            
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
            
            $this->saved_searches_model->update_record($cdata);
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $searchsort_session = $this->session->userdata('contact_savedsearch_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            $data = array('selected_view' => 3);
            $this->session->set_userdata('joomla_dbord_selected_view_session',$data);
            
            $contact_id = $this->session->userdata('selected_contactid');
            if(!empty($contact_id['contact_id']))
                redirect('user/'.$this->viewName.'/view_record/'.$contact_id['contact_id'].'/3');
            else
                redirect('user/'.$this->viewName);
            //redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
            //redirect(base_url('user/'.$this->viewName.'/view_record/'.$sel_contact_id));
        }	
	    /*

    @Description: Function for Update contacts Profile
    @Author: Nishit Modi
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 04-07-2014
    */
    public function update_view()
    {
		$cdata['id'] = $this->input->post('id');
		$contact_id = $this->input->post('id');
		$submitvaltab2 = $this->input->post('submitvaltab2');
		$viewtab = $this->input->post('viewtab');
		//$viewtab == 'list_plan' This Str in joomla dashboard Listing page to add Communication plan
		
		if($viewtab == 1 || $viewtab == 'list_plan')
		{
			$allcommunicationplans = $this->input->post('slt_communication_plan');
			//pr($allcommunicationplans);
			$oldcommunicationplans = $this->obj->select_communication_trans_record($contact_id);
			//pr($oldcommunicationplans);
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
									$match = array('id'=>$contact_id);
									$userdata = $this->contacts_model->select_records('',$match,'','=');
								
									$agent_name = '';
									if(count($userdata) > 0)
									{
										if(!empty($userdata[0]['created_by']))
										{
											$table ="login_master as lm";   
											$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
											$join_tables = array('user_master as um'=>'lm.user_id = um.id');
											$wherestring = 'lm.id = '.$userdata[0]['created_by'];
											$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
											if(!empty($agent_datalist))
											{
												if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
													$agent_name = $agent_datalist[0]['admin_name'];
												else
													$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
											}
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
		if($viewtab == 4)
		{
			$cdata['fb_id'] = $this->input->post('fbid');
			$cdata['prefix'] = $this->input->post('slt_prefix');
			$cdata['first_name'] = $this->input->post('txt_first_name');
			$cdata['middle_name'] = $this->input->post('txt_middle_name');
			$cdata['last_name'] = $this->input->post('txt_last_name');
			$cdata['spousefirst_name']=$this->input->post('txt_spousefirst_name');
			$cdata['spouselast_name']=$this->input->post('txt_spouselast_name');
			$cdata['company_name'] = $this->input->post('txt_company_name');   
			$cdata['company_post'] = $this->input->post('txt_company_post');   
			$cdata['is_lead'] = $this->input->post('chk_is_lead');
			$cdata['notes'] = $this->input->post('txtarea_notes');
			$cdata['contact_source'] = $this->input->post('slt_contact_source');
			$cdata['contact_method'] = $this->input->post('slt_contact_method');
			$cdata['contact_status'] = $this->input->post('slt_contact_status');
			$cdata['modified_by'] = $this->user_session['id'];
			$cdata['modified_date'] = date('Y-m-d H:i:s');

			//// Image Upload ///////
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
			
			if(!empty($cdata['contact_status']))
			{
				$status_id =$this->contacts_model->select_contact_status_trans_record_contact_id($contact_id,$cdata['contact_status']);
				if(empty($status_id))
				{
					$data_status['contact_status_id'] = $cdata['contact_status'];
					$data_status['contact_id'] = $contact_id;
					$data_status['created_by'] = $this->user_session['id'];
					$data_status['created_date'] = date('Y-m-d H:i:s');
					$this->contacts_model->insert_contact_contact_status_trans_record($data_status);
				}	
				
			}
			$this->contacts_model->update_record($cdata);
			unset($cdata);
			$slt_user=$this->input->post('slt_user');   
			if(!empty($slt_user))
			{
				$user_add_list = $this->contacts_model->select_user_contact_trans_record($contact_id);
				if(!empty($user_add_list))
				{
					$this->contacts_model->delete_table_user_contact_record($user_add_list[0]['contact_id']);
					$cudata['contact_id'] = $contact_id;
					$cudata['user_id'] = $this->input->post('slt_user');   
					$cudata['created_by'] = $this->user_session['id'];
					$cudata['created_date'] = date('Y-m-d H:i:s');		
					$cudata['status'] = '1';
					$this->contacts_model->insert_user_contact_trans_record($cudata);
					unset($cudata);
					
					$dataresult=$this->contacts_model->select_contact_conversation_trans_record1($contact_id,$slt_user);
					if(empty($dataresult))
					{
						$data_conver['contact_id']=$contact_id;
						$data_conver['created_by'] = $this->user_session['id'];
						$data_conver['assign_to'] = $slt_user;
						$data_conver['log_type'] = '4';// Log Type 4 Re-assign in User.
						$data_conver['created_date'] = date('Y-m-d H:i:s');
						$data_conver['status'] = '1';
						$this->contacts_model->insert_contact_conversation($data_conver);
						unset($data_conver);
					}
				}
				else
				{
					$cudata['contact_id'] = $contact_id;
					$cudata['user_id'] = $this->input->post('slt_user');   
					$cudata['created_by'] = $this->user_session['id'];
					$cudata['created_date'] = date('Y-m-d H:i:s');		
					$cudata['status'] = '1';
					$this->contacts_model->insert_user_contact_trans_record($cudata);
					
					$data_conver['contact_id']=$contact_id;
					$data_conver['created_by'] = $this->user_session['id'];
					$data_conver['assign_to'] = $this->input->post('slt_user');  
					$data_conver['log_type'] = '3';// Log Type 3 Assign in User.
					$data_conver['created_date'] = date('Y-m-d H:i:s');
					$data_conver['status'] = '1';
					$this->contacts_model->insert_contact_conversation($data_conver);
					unset($data_conver);
					
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
						
						$this->contacts_model->insert_email_trans_record($cmdata);
						
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
						$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
						if (preg_match($regex, $allemailaddresse[$i])) 
						{
							$cmdata['email_address'] = strtolower($allemailaddresse[$i]);
						}
						if($defaultemail == $allemailaddresse[$i])
							$cmdata['is_default'] = '1';
						else
							$cmdata['is_default'] = '0';
						
						$this->contacts_model->update_email_trans_record($cmdata);
						
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
						
						$this->contacts_model->insert_phone_trans_record($cpdata);
						
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
						{
							$cpdata['is_default'] = '1';
						}
						else
						{
							$cpdata['is_default'] = '0';
						}
						$this->contacts_model->update_phone_trans_record($cpdata);
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
						$this->contacts_model->insert_address_trans_record($cadata);
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
						
						$this->contacts_model->update_address_trans_record($cadata);
						
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
						$this->contacts_model->insert_website_trans_record($cwdata);
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
						$this->contacts_model->update_website_trans_record($cwdata);
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
							$csdata['website_name'] = end($s_name);
						}
						else
						{
							$csdata['website_name'] = $allsocialname[$i];
							$csdata['status'] = '1';
							$this->contacts_model->insert_social_trans_record($csdata);
							unset($csdata);
						}
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
						if(trim($allsocialnamee[$i]) != "")
						{							
							$csdata['id'] = $allsocialtypeid[$i];
							$csdata['profile_type'] = $allsocialtypee[$i];
							
							if($csdata['profile_type'] == 2)
							{
								$s_name = explode("twitter.com/",$allsocialnamee[$i]);
								$csdata['website_name'] = end($s_name);
							}
							else
							{
								$csdata['website_name'] = $allsocialnamee[$i];
								$csdata['status'] = '1';
								$this->contacts_model->update_social_trans_record($csdata);
								unset($csdata);
							}
						}
					}
				}
			}
			
			$this->contacts_model->delete_contact_type_record($contact_id);
			$allcontact_types = $this->input->post('chk_contact_type_id');
			
			if(!empty($allcontact_types) && count($allcontact_types) > 0)
			{
				foreach($allcontact_types as $row)
				{
					$ctdata['contact_id'] = $contact_id;
					$ctdata['contact_type_id'] = $row;
					$this->contacts_model->insert_contact_type_record($ctdata);
					unset($ctdata);
				}
			}
			
			
			$alltags = $this->input->post('txt_tag');
			if(!empty($alltags))
			{
				$alltag = array();
				$alltags = explode(',',$this->input->post('txt_tag'));
				for($i=0;$i<count($alltags);$i++)
				{
					if(!stristr($alltags[$i],'NEWTAG-'))
					{
						$oldtag = $this->contacts_model->select_tag_record('',$alltags[$i]);
						if(!empty($oldtag) && count($oldtag) > 0 && $oldtag[0]['contact_id'] == $contact_id)
						{
							$alltag[] = $oldtag[0]['id'];
						}
						elseif(!empty($oldtag) && count($oldtag) > 0)
						{
							$ctdata['contact_id'] = $contact_id;
							$ctdata['tag'] = $oldtag[0]['tag'];
							$ctdata['is_default'] = $oldtag[0]['is_default'];
							$lastId = $this->contacts_model->insert_tag_record($ctdata);
							$alltag[] = $lastId;
							unset($ctdata);
						}
					}
					else
					{
						$explode = explode('NEWTAG-',$alltags[$i]);
						if(!empty($explode[1]))
						{
							$explodetag = explode('{^}',$explode[1]);
						}
						if(!empty($explodetag[1]))
						{
							$ctdata['contact_id'] = $contact_id;
							$ctdata['is_default'] = '2';
							$ctdata['tag'] = $explodetag[1];
							$lastId = $this->contacts_model->insert_tag_record($ctdata);	
							$alltag[] = $lastId;
							unset($ctdata);
						}
					}
				}
				$this->contacts_model->delete_not_in_tag_trans_record($alltag,$contact_id);
			}
			else
			{
				$this->contacts_model->delete_tag_trans_record('',$contact_id);
			}
			
			
			$allcommunicationplans = $this->input->post('slt_communication_plan_id');
			$oldcommunicationplans = $this->contacts_model->select_communication_trans_record($contact_id);
			$oldcommunicationplanslist = array();
			if(count($oldcommunicationplans) > 0)
			{
				foreach($oldcommunicationplans as $row)
				{
					$oldcommunicationplanslist[] = $row['interaction_plan_id'];
				}
			}
			
			$oldplansarr = array_diff($oldcommunicationplanslist,$allcommunicationplans);
			$newplansarr = array_diff($allcommunicationplans,$oldcommunicationplanslist);
			if(!empty($oldplansarr))
			{
				foreach($oldplansarr as $rowdata)
				{
					$this->interaction_plans_model->delete_contact_trans_record_indi($rowdata,$contact_id);
					$this->interaction_plans_model->delete_contact_communication_plan_trans_record_indi($rowdata,$contact_id);
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
						
				}
			}
			
			if(!empty($newplansarr) && count($newplansarr) > 0)
			{
				foreach($newplansarr as $interaction_plan_id)
				{
					if($interaction_plan_id != '')
					{
						$match1 = array('id'=>$interaction_plan_id);
						$plandata = $this->interaction_plans_model->select_records('',$match1,'','=');
						$cdata = $plandata[0];
						if(!empty($cdata))
						{
							$data_conv['contact_id'] = $contact_id;
							$data_conv['plan_id'] = $interaction_plan_id;
							$data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
							$data_conv['created_date'] = date('Y-m-d H:i:s');
							$data_conv['log_type'] = '2';
							$data_conv['created_by'] = $this->user_session['id'];
							$data_conv['status'] = '1';
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
							$this->interaction_plans_model->insert_contact_trans_record($icdata);
							$plan_id = $interaction_plan_id;
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

							
							if(count($interaction_list) > 0)
							{
								foreach($interaction_list as $row1)
								{
									$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
									
									if(!empty($assigned_user_id))
									{
									
										$new_user_id = $assigned_user_id;
										
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
													$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
													$to_date = date('Y-m-d',strtotime($row2['to_date']));
													while($from_date <= $to_date)
													{
														$leave_days1[] = $from_date;
														$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
													}
												}
											}
										}
										
										
									}
									$iccdata['interaction_plan_id'] = $plan_id;
									$iccdata['contact_id'] = $contact_id;
									$iccdata['interaction_plan_interaction_id'] = $row1['id'];
									$iccdata['interaction_type'] = $row1['interaction_type'];
									
									if($row1['start_type'] == '1')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
										$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
										
										if(!empty($plan_contact_data[0]['start_date']))
										{
											$newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
											
											$newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
								
											$repeatoff = 1;
											
											while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
											{
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
												
												
												if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												else
												{
													$repeatoff = 0;
													$newtaskdate = $newtaskdate1;
												}
											}
											
											$iccdata['task_date'] = $newtaskdate;
										}
									}
									elseif($row1['start_type'] == '2')
									{
										$count = $row1['number_count'];
										$counttype = $row1['number_type'];
										
										$interaction_id = $row1['interaction_id'];
										
										$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id);
										
										if(!empty($interaction_res->task_date))
										{
											$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
											
											$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
									
											$repeatoff = 1;
											
											while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
											{
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
												
												if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
												{
													$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
												}
												else
												{
													$repeatoff = 0;
													$newtaskdate = $newtaskdate1;
												}
												
											}
											
										
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
									$match = array('id'=>$contact_id);
									$userdata = $this->contacts_model->select_records('',$match,'','=');
								
									$agent_name = '';
									if(count($userdata) > 0)
									{
										if(!empty($userdata[0]['created_by']))
										{
											$table ="login_master as lm";   
											$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
											$join_tables = array('user_master as um'=>'lm.user_id = um.id');
											$wherestring = 'lm.id = '.$userdata[0]['created_by'];
											$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
											if(!empty($agent_datalist))
											{
												if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
													$agent_name = $agent_datalist[0]['admin_name'];
												else
													$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
											}
										}
									}
								
									if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
									{	
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
											
											$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
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
											$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
											$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
										}
									}
								
									unset($cdata1);
								}
							}
							
							unset($icdata);
						}
						
					}
				}
			}
			
		}
		
		$redirecttype = $this->input->post('submitbtn');
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
/*		redirect('user/'.$this->viewName.'/view_record/'.$contact_id.'/'.($viewtab));*/
		 $data = array('selected_view' => $viewtab);
        $this->session->set_userdata('joomla_dbord_selected_view_session',$data);
		if($viewtab == 1)
		{
			redirect('user/'.$this->viewName.'/view_record/'.$contact_id);
		}
		else if($viewtab == 4)
		{
			redirect('user/'.$this->viewName.'/view_record/'.$contact_id);
		}
		else
		{
			redirect('user/'.$this->viewName);
		}	
    }
        /*
            @Description: Function for search saved searches
            @Author     : Mohit Trivedi
            @Input      : search word 
            @Output     : List with saved searches details
            @Date       : 11-12-2014
        */
 
    public function view_record_index_savser()
    {
	   
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
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
            $this->session->unset_userdata('joomla_savedsearch_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('joomla_savedsearch_sortsearchpage_data','');

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
            $data['searchtext1'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext1'] = $searchsort_session['searchtext'];
                    $searchtext =  mysql_real_escape_string($data['searchtext1']);
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
        $config['base_url'] = site_url($this->user_type.'/'.$this->viewName."/view_record_index_savser/".$id."/");
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
            $match=array('name'=>$searchtext,'domain'=>$searchtext,'created_date'=>$searchtext,
                'min_price'=>$searchtext,'max_price'=>$searchtext,'bedroom'=>$searchtext,'bathroom'=>$searchtext,'min_area'=>$searchtext,'max_area'=>$searchtext,
                'min_year_built'=>$searchtext,'max_year_built'=>$searchtext,'fireplaces_total'=>$searchtext,'min_lotsize'=>$searchtext,'max_lotsize'=>$searchtext,'garage_spaces'=>$searchtext);
            $data['result_saved_searches'] = $this->saved_searches_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->saved_searches_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            if(!empty($search_id)) { // For popup
                $match = array('id'=>$search_id);
            }
            else {
                $match = array('lw_admin_id'=>$id);
            }
            $data['result_saved_searches'] = $this->saved_searches_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
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
            $this->session->set_userdata('joomla_savedsearch_sortsearchpage_data', $contacts_sortsearchpage_data);
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
			@Description: Function for view saved searches popup data
			@Author     : Sanjay Moghariya
			@Input      : Selected saved search id
			@Output     : Saved searches data
			@Date       : 01-12-2014
		*/
		public function view_saved_searches_popup()
		{
			$id = $this->input->post('search_id');
			$match = array('id'=>$id);
			$data['saved_search_list'] = $this->saved_searches_model->select_records('',$match,'','=');
			$this->load->view($this->user_type.'/'.$this->viewName."/view_saved_searches_popup",$data);
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
			$this->contacts_model->delete_contact_trans_record_indi($interaction_plan_id,$contact_id);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			$this->contacts_model->delete_contact_communication_plan_trans_record_indi($interaction_plan_id,$contact_id);
			
			//////////////////////////////////////////////////////////////////////////////////
			
			/* Delete SMS and Email Campaign data */
			$match = array('interaction_plan_id'=>$interaction_plan_id);
			$interaction = $this->interaction_plans_model->select_records('',$match,'','=');
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
				$data_conv['created_by'] = $this->user_session['id'];
				$data_conv['status'] = '1';
				$this->contacts_model->insert_contact_converaction_trans_record($data_conv);
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
    @Description: Function for Delete contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 04-07-2014
    */
    function delete_record()
    {
       	check_rights('lead_dashboard_delete'); 
	    $id = $this->uri->segment(4);
        $this->contacts_model->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
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
		$contact_id = $this->input->post('id1');
        $this->contacts_model->delete_email_trans_record($id);
		$count=$this->contacts_model->select_email_trans_record($contact_id);
		echo count($count);

    }
	
	function delete_phone_trans_record()
    {
	    $id = $this->uri->segment(4);
        $this->contacts_model->delete_phone_trans_record($id);
    }
	
	function delete_address_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->contacts_model->delete_address_trans_record($id);
    }
	
	function delete_website_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->contacts_model->delete_website_trans_record($id);
    }
	
	function delete_social_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->contacts_model->delete_social_trans_record($id);
    }
	
	function delete_tag_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->contacts_model->delete_tag_trans_record($id);
    }
	
	function delete_communication_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->contacts_model->delete_communication_trans_record($id);
    }
	
	function delete_document_trans_record()
    {
        $id = $this->uri->segment(4);
		
		$result = $this->contacts_model->select_document_trans_record_ajax($id);
		$this->contacts_model->delete_document_trans_record($id);
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
		$this->contacts_model->update_record($cdata);
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
		$cdata['status'] = '1';
		$this->contacts_model->update_record($cdata);
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
        $result = $this->contacts_model->select_records('',$match,'','=');
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
		$this->contacts_model->update_record($cdata);
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
		$result = $this->contacts_model->select_document_trans_record_ajax($id);
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
			$this->contacts_model->update_record($cdata);
			
			//$email_id = $id;
			//$pagingid = $this->contacts_model->getemailpagingid($email_id);
                        if($selected_view == '1')
                            $searchsort_session = $this->session->userdata('iplan_view_archive_sortsearchpage_data');
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
			//$pagingid = $this->contacts_model->getemailpagingid($email_id);
                    if($selected_view == '1')
                        $searchsort_session = $this->session->userdata('iplan_view_archive_sortsearchpage_data');
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
			$this->contacts_model->update_record($data);
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
            if(!empty($id))
            {
                $cdata['id'] = $id;
                $cdata['status'] = '0';
                $this->contacts_model->update_record($cdata);
					//echo $this->db->last_query();
                //$email_id = $id;
                //$pagingid = $this->contacts_model->getemailpagingid($email_id);
                if($selected_view == '1')
                    $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('premium_leads_dashboard_sortsearchpage_data');
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
                //$pagingid = $this->contacts_model->getemailpagingid($email_id);
                if($selected_view == '1')
                    $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
                else
                    $searchsort_session = $this->session->userdata('premium_leads_dashboard_sortsearchpage_data');
                if(!empty($searchsort_session['uri_segment']))
                    $pagingid = $searchsort_session['uri_segment'];
                else
                    $pagingid = 0;
            }

            for($i=0;$i<count($array_data);$i++)
            {
                $data['id']=$array_data[$i];
                $data['status']='0';
                $this->contacts_model->update_record($data);
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
	
        /*
            @Description: Function use to Delete contact
            @Author     : Sanjay Moghariya
            @Input      : Contact id
            @Output     : Delete record
            @Date       : 24-11-2014
        */
        public function ajax_delete_all()
        {
            $id=$this->input->post('single_remove_id');
            $email_id = $this->input->post('single_remove_id');
            $array_data=$this->input->post('myarray');
            $delete_all_flag = 0;$cnt = 0;
            if(!empty($id))
            {
                if($this->input->post('archive') == 'archive')
                {
                        $match = array('id'=>$id);
                        $result = $this->contacts_model->select_archive_records('',$match,'','=');
                }
                else
                {
                        $match = array('id'=>$id);
                        $result = $this->contacts_model->select_records('',$match,'','=');
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
                        $this->contacts_model->delete_archive_record($id);
                }
                else
                {
                        $this->contacts_model->delete_record($id);
                }


                /////////////// Delete Doc File and Tran table Data/////////////

                $result_doc=$this->contacts_model->select_document_trans_record_contact_id($id);
                $bgImgPath_doc = $this->config->item('contact_documents_img_path');
                for($k=0;$k<count($result_doc);$k++)
                {	
                        if(!empty($result_doc[$k]['doc_file']))
                        {
                                unlink($bgImgPath_doc.$result_doc[$k]['doc_file']);
                        }
                }
                unset($k);
                ////// Delete All Trans table Recored/////////

                $this->contacts_model->delete_all_trans_table_record($id,'contact_documents_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'user_contact_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_emails_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_phone_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_address_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_website_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_social_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_contacttype_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_tag_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'contact_contact_status_trans');

                $this->contacts_model->delete_all_trans_table_record($id,'email_campaign_recepient_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'sms_campaign_recepient_trans');

                //////////////////////////

                $this->contacts_model->delete_all_trans_table_record($id,'contact_conversations_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'interaction_plan_contact_personal_touches');

                $this->contacts_model->delete_all_trans_table_record($id,'interaction_plan_contacts_trans');
                $this->contacts_model->delete_all_trans_table_record($id,'interaction_plan_contact_communication_plan');
                
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_bookmarks',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_log',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_savesearch',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_track',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_property_valuation_searches',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_property_contact',$id);
                $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_valuation_contact',$id);

                //////////////////////////

                unset($id);
            }
            elseif(!empty($array_data))
            {
                $delete_all_flag = 1;
                for($i=0;$i<count($array_data);$i++)
                {
                    $cnt++;
                    if($this->input->post('archive') == 'archive')
                    {
                            $match = array('id'=>$array_data[$i]);
                            $result = $this->contacts_model->select_archive_records('',$match,'','=');
                    }
                    else
                    {
                            $match = array('id'=>$array_data[$i]);
                            $result = $this->contacts_model->select_records('',$match,'','=');
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
                            $this->contacts_model->delete_archive_record($array_data[$i]);
                    }
                    else
                    {
                            $this->contacts_model->delete_record($array_data[$i]);
                    }

                    $result_doc=$this->contacts_model->select_document_trans_record_contact_id($array_data[$i]);
                    $bgImgPath_doc = $this->config->item('contact_documents_img_path');
                    for($k=0;$k<count($result_doc);$k++)
                    {	
                            if(!empty($result_doc[$k]['doc_file']))
                            {
                                    unlink($bgImgPath_doc.$result_doc[$k]['doc_file']);
                            }
                    }

                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_documents_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'user_contact_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_emails_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_phone_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_address_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_website_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_social_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_contacttype_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_tag_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_contact_status_trans');

                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'email_campaign_recepient_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'sms_campaign_recepient_trans');

                    //////////////////////////

                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'contact_conversations_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_personal_touches');

                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'interaction_plan_contacts_trans');
                    $this->contacts_model->delete_all_trans_table_record($array_data[$i],'interaction_plan_contact_communication_plan');
                    
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_bookmarks',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_log',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_savesearch',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_track',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_property_valuation_searches',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_property_contact',$array_data[$i]);
                    $this->contacts_model->delete_all_trans_table_record('','joomla_rpl_valuation_contact',$array_data[$i]);

                    //////////////////////////

                }
            }
            ////// $pagingid; variable in Pagination 
            if($this->input->post('archive') == 'archive')
                    $searchsort_session = $this->session->userdata('leads_dashboard_view_archive_sortsearchpage_data');
            else
                    $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
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
        
	public function ajax_delete_all_old()
	{
		//pr($_POST);exit;
		
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->contacts_model->delete_record($id);
			//$this->contacts_model->delete_record_interaction($id);
			
			unset($id);
		}
		$array_data=$this->input->post('myarray');
                $delete_all_flag = 0;$cnt = 0;
		for($i=0;$i<count($array_data);$i++)
		{
			$this->contacts_model->delete_record($array_data[$i]);
			//$this->contacts_model->delete_record_interaction($array_data[$i]);
                        $delete_all_flag = 1;
                        $cnt++;
		}
                $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
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
            @Description: Function for change contact type
            @Author     : Sanjay Moghariya
            @Input      : contact id, contact type
            @Output     : Update contact type
            @Date       : 08-12-2014
        */
        public function change_contact_type()
        {
            $contact_id=$this->input->post('contact_id');
            $contact_type = $this->input->post('contact_type');
            $data['id'] = $contact_id;
            $data['joomla_contact_type'] = $contact_type;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $data['modified_by'] = $this->user_session['id'];
            $this->contacts_model->update_record($data);
            
            ////// Code for redirect on current page once action is done 
            $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
            if(!empty($searchsort_session['uri_segment']))
                $pagingid = $searchsort_session['uri_segment'];
            else
                $pagingid = 0;

            echo $pagingid;
        }

        /*
            @Description: Function for change contact category
            @Author     : Sanjay Moghariya
            @Input      : contact id, contact category
            @Output     : Update contact category
            @Date       : 08-12-2014
        */

		public function change_contact_category()
        {
            $contact_id=$this->input->post('contact_id');
            $contact_category = $this->input->post('contact_category');
            $data['id'] = $contact_id;
            $data['joomla_category'] = $contact_category;
            $data['modified_date'] = date('Y-m-d H:i:s');
            $data['modified_by'] = $this->user_session['id'];
            $this->contacts_model->update_record($data);
            
            ////// Code for redirect on current page once action is done 
            $searchsort_session = $this->session->userdata('leads_dashboard_sortsearchpage_data');
            if(!empty($searchsort_session['uri_segment']))
                $pagingid = $searchsort_session['uri_segment'];
            else
                $pagingid = 0;

            echo $pagingid;
        }
        
    // 2 Favorite






        /*
            @Description: Function for search faverote record
            @Author     : Sanjay Moghariya
            @Input      : Selected valuation searched keyword
            @Output     : list of faveriote reocrd list
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
            $this->session->unset_userdata('leads_dashboard_favorite_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('leads_dashboard_favorite_sortsearchpage_data');

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
        $config['base_url'] = site_url($this->user_type.'/'.$this->viewName."/view_record_index_fav/".$id."/");
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
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_favorite'] = $this->favorite_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            $config['total_rows']= $this->favorite_model->select_records('',$match,'','=','','','','','','','1');

        }
        $this->pagination->initialize($config);
        $data['pagination2'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
           	'sortfield'  => !empty($data['sortfield2'])?$data['sortfield2']:'',
			'sortby' =>!empty($data['sortby2'])?$data['sortby2']:'',
			'searchtext' =>!empty($data['searchtext2'])?$data['searchtext2']:'',
			'perpage' => !empty($data['perpage2'])?trim($data['perpage2']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('leads_dashboard_favorite_sortsearchpage_data', $contacts_sortsearchpage_data);
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
    // 1 Saved Searches
    //3 Properties Viewed
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
            $this->session->unset_userdata('leads_dashboard_propviewed_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('leads_dashboard_propviewed_sortsearchpage_data');

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
        $config['base_url'] = site_url($this->user_type.'/'.$this->viewName."/view_record_index_prop_view/".$id."/");
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

        //// Sanjay Moghariya 11-07-2015
        $parent_db = $this->config->item('parent_db_name');
        //$match = array('jrt.lw_admin_id'=>$id);
        $match = 'jrt.lw_admin_id = '.$id.' AND mplm.LN !=""';
        $fields = array('jrt.id,jrt.log_date,jrt.views,mplm.mls_id,mplm.LN,mplm.full_address,mplm.CIT,mplm.PTYP,mplm.ST,mplm.ASF,mplm.BR,mplm.BTH,mplm.display_price,mplm.PIC,mplm.Internal_MLS_ID,mplm.MR');
        $table = 'joomla_rpl_track as jrt';
        $join_tables = array($parent_db.'.mls_property_list_master as mplm'=>'mplm.LN = jrt.mlsid');
        $data['result_properties_viewed'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,'jrt.id','desc','',$match);
        $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','jrt.id','desc','',$match,'','1');
        if(!empty($data['result_properties_viewed']))
        {
            foreach($data['result_properties_viewed'] as $prop_view)
            {
                if(!empty($prop_view['mls_id']) && $prop_view['mls_id'] == 1)
                {
                    $table1 = $parent_db.'.mls_property_image';
                    $fields1 = array('image_name,image_medium_url,image_small_url,listing_number');
                    $match1 = array_map(function($element) {
                                return $element['LN'];
                              }, $data['result_properties_viewed']);
                    $image_list =  $this->contacts_model->getmultiple_tables_records($table1,$fields1,'','','','','','','','image_name','asc','','',array('listing_number'=>$match1));

                    if(!empty($image_list))
                    {
                        foreach ($image_list as $row1)
                        {
                            if(empty($data['image_list']) || !array_key_exists($row1['listing_number'],$data['image_list']))
                                $data['image_list'][$row1['listing_number']] = $row1['image_name'];
                        } 
                    }
                }
            }
        }
        //// End Sanjay Moghariya 11-07-2015
        /*
        if(!empty($searchtext))
        {
            $where_clause=array('lw_admin_id'=>$id);
            $match=array('mlsid'=>$searchtext,'propery_name'=>$searchtext,'views'=>$searchtext,'domain'=>$searchtext,'log_date'=>$searchtext);
            $data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where_clause);
            $config['total_rows'] = $this->properties_viewed_model->select_records('',$match,'','like','','','','','',$where_clause,'1');
        }
        else
        {
            $match = array('lw_admin_id'=>$id);
            $data['result_properties_viewed'] = $this->properties_viewed_model->select_records('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
            $config['total_rows']= $this->properties_viewed_model->select_records('',$match,'','=','','','','','','','1');
        }*/
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
			
        $this->session->set_userdata('leads_dashboard_propviewed_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment3'] = $uri_segment;
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/property_tab',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/view";
            $this->load->view('user/include/template',$data);
        }

    }
    
    /*
        @Description: Function for set selected view session for Joomla connection listing
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 01-12-2014
    */
    public function selectedview_session()
    {
        $selected_view = $this->input->post('selected_view');
        $page_name = $this->input->post('page_name');
        if(!empty($page_name))
        {
            $data = array('selected_view' => $selected_view);
            $this->session->set_userdata('joomla_selected_view_session',$data);
        }

        if($selected_view == '3')
            $sortfield = 'id';
        else
            $sortfield = 'lw_admin_id';

        $this->session->unset_userdata('leads_dashboard_register_sortsearchpage_data');
        $this->session->unset_userdata('joomla_savedsearch_sortsearchpage_data');
        $this->session->unset_userdata('leads_dashboard_favorite_sortsearchpage_data');
        $this->session->unset_userdata('leads_dashboard_propviewed_sortsearchpage_data');
        $this->session->unset_userdata('leads_dashboard_lastlogin_sortsearchpage_data');

        $sortsearchpage_data = array(
            'sortfield'  => $sortfield,
            'sortby' =>'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0,
        );
        if($selected_view == '3')
            $this->session->set_userdata('joomla_savedsearch_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('joomla_dbord_selected_view_session',$data);
    }

    /*
        @Description: Function for view contact register popup data
        @Author     : Sanjay Moghariya
        @Input      : Selected contact register id
        @Output     : contact register data
        @Date       : 01-12-2014
    */
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
        @Description: Function for view favorite popup data
        @Author     : Sanjay Moghariya
        @Input      : Selected favorite id
        @Output     : favorite data
        @Date       : 01-12-2014
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
        @Date       : 01-12-2014
    */
    public function view_properties_viewed_popup()
    {
        $id = $this->input->post('search_id');
        $match = array('id'=>$id);
        $data['properties_viewed_list'] = $this->properties_viewed_model->select_records('',$match,'','=');
        $this->load->view($this->user_type.'/'.$this->viewName."/view_properties_viewed_popup",$data);
    }
	/*
        @Description: Function user for Contact id using in assigned Communication Plan list 
        @Author     : kaushik valiya
        @Input      : Contact id
        @Output     : List of Communication plan
        @Date       : 12-10-2014
    */
    public function view_contact_interaction_plan_list()
    {
        $id = $this->input->post('contact_id');
				
		if(!empty($id))
		{
        	$data['communication_trans_data'] = $this->contacts_model->select_communication_trans_record($id);
			/*$match = array('status'=>'1');
			$data['communication_plans'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','plan_name','asc', 'interaction_plan_master');*/
			//pr($data['communication_plans']);
			
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
			$wherestring='(ipim.assign_to = '.$this->user_session['id'].' OR ipm.created_by = '.$this->user_session['id'].')';
			$data['communication_plans'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,$wherestring);
			
			
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
		
		
			$match=array('ipm.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
			$data['admin_interection_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by);//echo $this->db->last_query();
			$data['contact_id']=$id;
			$view_load = $this->load->view($this->user_type.'/'.$this->viewName."/view_communication_plan_list_popup",$data);
			echo $view_load;
		}
		else
		{
			echo "";
		}
		
    }
    
    /*
        @Description: Function for Get joomla property contact form List (action=propertydetail)
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : get joomla property contact form list
        @Date       : 18-03-2015
    */
    public function property_contact_form()
    {
        $id = $this->uri->segment(4);
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        $data['sortfield']		= 'jpc.id';
        $data['sortby']			= 'desc';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('dashboard_contact_form_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('dashboard_contact_form_sortsearchpage_data');

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
                $sortfield = 'jpc.id';
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
                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($perpage))
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
        $config['base_url'] = site_url($this->user_type."/leads_dashboard/property_contact_form/".$id."/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        
        $table = "joomla_rpl_property_contact jpc";
        $fields = array('jpc.id,jpc.property_name,jpc.domain,jpc.name,jpc.email,jpc.phone,jpc.preferred_time,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name');
        $join_tables = array('contact_master as cm' => 'cm.id = jpc.lw_admin_id');
        $where = 'jpc.lw_admin_id ='.$id.' AND jpc.form_type = "property"';
        if(!empty($searchtext))
        {
            $match = array('jpc.property_name'=>$searchtext,'jpc.domain'=>$searchtext,'jpc.name'=>$searchtext,'jpc.email'=>$searchtext,'jpc.phone'=>$searchtext,'jpc.preferred_time'=>$searchtext);
            $data['datalist'] =$this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where);
            //echo $this->db->last_query();
            $config['total_rows'] = $this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$where,'','1');
        }
        else
        {
            $data['datalist'] =$this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where);
            //echo $this->db->last_query();
            $config['total_rows'] = $this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where,'','1');
        }

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];
        $sortsearchpage_data = array(
                'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
                'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
                'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
                'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
                'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
        $this->session->set_userdata('dashboard_contact_form_sortsearchpage_data', $sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;

        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/property_contact_form_ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/property_contact_form_list";
            $this->load->view($this->user_type.'/include/template',$data);
        }
    }
    
    /*
        @Description: Function for view property contact popup data
        @Author     : Sanjay Moghariya
        @Input      : Selected property contact id
        @Output     : Property contact data
        @Date       : 18-03-2015
    */
    public function view_property_contact_popup()
    {
        $id = $this->input->post('search_id');
        $table = "joomla_rpl_property_contact jpc";
        $fields = array('jpc.id,jpc.property_name,jpc.domain,jpc.name,jpc.email,jpc.phone,jpc.preferred_time,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,jpc.comments');
        $join_tables = array('contact_master as cm' => 'cm.id = jpc.lw_admin_id');
        $match = array('jpc.id'=>$id);
        $data['property_contact_list'] =$this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=');
        //$data['property_contact_list'] = $this->property_contact_model->select_records('',$match,'','=');
        $this->load->view($this->user_type.'/'.$this->viewName."/view_property_contact_popup",$data);
    }
	
}