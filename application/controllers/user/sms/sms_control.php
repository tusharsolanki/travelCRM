<?php
/*
    @Description: SMS campaign controller
    @Author: Sanjay Chabhadiya
    @Input: 
    @Output: 
    @Date: 06-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sms_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('phonecall_script_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('email_signature_model');
		$this->load->model('contacts_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_texts_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('dashboard_model');
		
		$this->obj = $this->sms_campaign_master_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
		$this->load->library('Twilio');
		
		
		/*$data['sms_campaign_id'] = 10;
		$data['contact_id'] = 122;
		$data['is_send'] = '0';
		$this->obj->delete_interaction_campaign($data);
		exit;*/
		
    }
	
	
	/*
		@Description: Function for Module All details view.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 22-12-2014
    */
	
	public function sms_home()
	{
		//check user right
		check_rights('text_blast');
		$data['main_content'] = 'user/'.$this->viewName."/home";
		$this->load->view('user/include/template',$data);	
	}

    /*
		@Description: Function for Get All SMS campaign List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all SMS campaign list
		@Date: 06-08-2014
    */

    public function index()
    {	
		//check user right
		check_rights('text_blast');
		/*$this->load->library('twilio');

		$from = '+15084554748';
		$to = '+919033921029';
		$message = 'This is a test...';

		$response = $this->twilio->sms($from, $to, $message);


		if($response->IsError)
			echo 'Error: ' . $response->ErrorMessage;
		else
			echo 'Sent message to ' . $to;
			
		exit;*/
	
	
	//	echo "hi"; exit;
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('sms_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('sms_sortsearchpage_data');
		
		
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
		$config['base_url'] = site_url($this->user_type.'/'."sms/");
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
		
		$table ="sms_campaign_master as scm";   
		$fields = array('scm.id,scm.is_draft,scm.is_sent_to_all,scm.sms_send_date,scm.sms_send_time','scm.sms_message','stm.template_name');
		$join_tables = array('sms_text_template_master as stm'=>'stm.id = scm.template_name');
		
		$wherestring = 'scm.sms_type = "Campaign" AND scm.created_by IN ('.$this->user_session['agent_id'].')';
		if(!empty($searchtext))
		{
			$match=array('stm.template_name'=>$searchtext,'scm.sms_message'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query(); exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'','1');
			
			
		}
		//pr($data['datalist']);exit;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

		$field = array('id','created_by');
		$match = array('id'=>$this->user_session['id']);
		$admin_data = $this->admin_model->get_user($field,$match,'','=');
		
		
		if(count($admin_data) > 0)
		{
			$field = array('id','created_by,email_id');
			$match = array('id'=>$admin_data[0]['created_by']);
			$admin_data1 = $this->admin_model->get_user($field,$match,'','=');
			
			$user_id = $admin_data[0]['created_by'];
			$field = array('id','remain_sms');
       	 	$match = array('id'=>$user_id);
			
			$parent_db_name = $this->config->item('parent_db_name');
			$sesion_db = $this->session->userdata('db_session');
			$match1 = array('db_name'=>$sesion_db['db_name'],'email_id'=>$admin_data1[0]['email_id']);
			$admin_data = $this->admin_model->get_user('',$match1,'','=','','','','','','',$parent_db_name);
			//echo $this->db->last_query();exit;
			if(count($admin_data) > 0)
				$admin_id = $admin_data[0]['id'];
			else
				$admin_id = 0;
				
			$data['total_sms'] = $this->obj->total_sms($admin_id,$parent_db_name);
			
			$data['udata'] = $this->admin_model->get_user($field, $match,'','=');
		}
		//pr($data['udata']);exit;
		$sms_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('sms_sortsearchpage_data', $sms_sortsearchpage_data);
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
    @Description: Function Add New SMS campaign details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add SMS campaign details
    @Date: 06-08-2014
    */
   
    public function add_record()
    {
		//check user right
		check_rights('text_blast_add');
		//$id = $this->uri->segment(4);
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');

		if(!empty($id))
		{
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
			$join_tables = array(
								'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id'
							);
			$group_by='cm.id';
			$where = array('cpt.id'=>$this->uri->segment(5),'cm.id'=>$this->uri->segment(4));
			//$where_in = array('cm.id'=>$id);
			//$where = array('cm.is_subscribe'=>"'0'");
			$data['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		}

		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'."sms/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table = "contact_contacttype_trans as cct";
		$fields = array('DISTINCT(ctm.name),ctm.id,ctm.created_date,ctm.created_by,ctm.created_by,ctm.created_by,ctm.status');
		$join_tables = array(
							'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
							'contact_master as cm'=>'cct.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$where='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],'','id','desc','',$where);
		$config['total_rows'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where,'','1');
		//echo $this->db->last_query();
		
		//$data['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		//echo $this->db->last_query();
		//$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		//$config['total_rows'] =count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where));
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'."sms/search_contact_to");
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_to1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		/*;
		$data['contact_to'] = $this->contacts_model->select_records('',$match,'','=','',$config_to1['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config_to1['total_rows'] = count($this->contacts_model->select_records('',$match,'','='));
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();*/
		
		$table = "contact_master as cm";
		$where='cpt.is_default = "1" AND (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		//$where = array('cm.is_subscribe'=>"'0'");
		$group_by = 'cm.id';
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();

		//////////////////////////////////// END ///////////////////////////////////////////////

		$data['communication_plans'] = '';
		$table1='custom_field_master';
		$where1=array('module_id'=>'2');
		$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		//$data['tablefield_data']=$this->email_library_model->select_records3();
		
		$fields = array('cm.id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cpt.phone_no');
		$data['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		//$data['contact'] = $this->contacts_model->select_records('','','');
		
		$match = array();
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		
		//pr($data['contact']);exit;
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    }

    /*
		@Description: Function for Insert New SMS campaign data
		@Author: Sanjay Chabhadiya
		@Input: - Details of new SMS campaign which is inserted into DB
		@Output: - List of SMS campaign with new inserted records
		@Date: 06-08-2014
    */
   
    public function insert_data()
    {
	 	//pr($_POST);exit;
		$submit = $this->input->post('submitbtn');
		if($this->input->post('submitbtn1') || $this->input->post('submitbtn'))
		{
			$data['template_name'] = $this->input->post('template_name');
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['sms_message'] = $this->input->post('sms_message');
			
			$data['sms_send_type'] = $this->input->post('chk_is_lead');
			$send_type = $this->input->post('chk_is_lead');
			
			if($this->input->post('submitbtn'))
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';
				
			if($data['sms_send_type'] == 2)
			{
				$data['sms_send_date'] = $this->input->post('send_date');
				$data['sms_send_time'] = $this->input->post('send_time');
				$data['is_draft'] = '0';
			}
			
			if(empty($data['sms_send_type']) && $this->input->post('submitbtn'))
			{
				$data['sms_send_type'] = 1;
			}
				
			$data['sms_type'] = 'Campaign';
			$data['created_by'] = $this->user_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			//$data['status'] = '1';
			$sms_campaign_id = $this->obj->insert_record($data);	
			
			//For twilio from account
			$data['sms_send_from'] = $this->user_session['id'];
			
			if($this->input->post('phone_trans_id'))
				$data['phone_trans_id'] = $this->input->post('phone_trans_id');
			//echo $this->db->last_query();exit;
			$cdata['sms_campaign_id'] = $sms_campaign_id;
			$cdata['sms_message'] = $data['sms_message'];
			/*if($submit == 'Send Now')
				$cdata['sent_date'] = '1';*/
				
			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = '';
			$contact_id = '';
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$contact_id[$j] = $contact[$i];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
				$cdata['sent_date'] = date('Y-m-d H:i:s');
			
			if((!empty($contact_id) && $this->input->post('submitbtn1')) || ($this->input->post('submitbtn') && $data['sms_send_type'] != 1))
			{
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			
			if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
			{
				$send_sms_count = 0;
				if(!empty($contact_id))		
				{
					$sms_send_data = $this->send_sms($contact_id,$data,$cdata,$send_sms_count);
					//$flag = $email_send_data['send_mail_count'];
					$send_sms_count = $sms_send_data['send_sms_count'];
				}
			}
			
			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = array();
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table = "contact_master as cm";
					$fields = array('cm.id,cm.first_name,cm.last_name');
					$join_tables = array(
										 'contact_contacttype_trans  as cct'=>'cct.contact_id = cm.id',
										 'contact_emails_trans cet'=>'cet.contact_id = cm.id',
										 'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
										 'user_contact_trans as uct'=>'uct.contact_id = cm.id'
										);
			
					$where = 'cct.contact_type_id = '.$contact_type[$i].' AND cet.is_default = "1" AND (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
					$group_by='cm.id';
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
					/*$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$where='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].')';
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','user_contact_trans as uct'=>'uct.contact_id = cm.id');
					
					$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','', '','','','',$where);*/
					//pr($contact_type_data);exit;
					
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['id'];
							$j++;
							
						}
						if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
						{
							$sms_send_data = $this->send_sms($contact_id1,$data,$cdata,$send_sms_count);
							/*if(!empty($email_send_data))
								$flag = $email_send_data;
							if($flag == 2)
							break;*/
							$send_sms_count = $sms_send_data['send_sms_count'];
								
							
						}
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
			
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			redirect('user/'.$this->viewName);
			/*for($i=0;$i<count($contact_id);$i++)
			{
				$cdata['contact_id'] = $contact_id[$i];
				$this->sms_campaign_recepient_trans_model->insert_record($cdata);
			}*/
		}
		elseif($this->input->post('submitbtn2'))
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->sms_texts_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			else
				$data['template_name'] = "You SMS Template";
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['sms_message'] = $this->input->post('sms_message');
			//$data['sms_type'] = '1';
			$data['created_by'] = $this->user_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->sms_texts_model->insert_record($data);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
	
   	}
 
 	/*
		@Description: Function for insert SMS campaign trans
		@Author: Sanjay Chabhadiya
		@Input: - Details of SMS campaign,contact details
		@Output: - 
		@Date: 06-08-2014
   	*/
   
 	public function insert_data_trans($contact_id='',$data,$cdata='')
	{
		
		$from_data = $this->obj->in_query_data($contact_id);
		if(count($from_data) > 0)
		{
			foreach($from_data as $row)
			{
				$cdata['contact_id'] = $row['id'];
				
				$agent_name = '';
				if(!empty($row['created_by']))
				{
					$table ="login_master as lm";   
					$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
					$join_tables = array('user_master as um'=>'lm.user_id = um.id');
					$wherestring = 'lm.id = '.$row['created_by'];
					$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
					if(!empty($agent_datalist))
					{
						if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
							$agent_name = $agent_datalist[0]['admin_name'];
						else
							$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
					}
				}
				
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
				$content = $data['sms_message'];
				$cdata['sms_message'] = $content;
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
					$cdata['sms_message'] = $finlaOutput;
				}
				$this->obj->insert_sms_campaign_recepient_trans($cdata);
			}
		}
	}
	
	/*
		@Description: Function for send SMS
		@Author: Sanjay Chabhadiya
		@Input: - Details of SMS campaign,contact details and send SMS count
		@Output: - Send SMS and insert the data in SMS campaign trans
		@Date: 06-08-2014
   	*/
	
	public function send_sms($contact_id='',$data='',$cdata='',$send_sms_count='')
	{
		$from = $this->config->item('from_sms');
		
		$field = array('id','created_by');
		$match = array('id'=>$this->user_session['id']);
		$admin_data = $this->admin_model->get_user($field,$match,'','=');
		$remain_sms = 0;
		if(count($admin_data) > 0)
		{
			$user_id = $admin_data[0]['created_by'];
			$field = array('id','remain_sms');
        	$match = array('id'=>$user_id);
        	$udata = $this->admin_model->get_user($field, $match,'','=');	
		}
		
		$sms_data['flag'] = 1;
		$sms_data['send_sms_count'] = $send_sms_count;
		if(!empty($udata) && count($udata) > 0)
		{
			$remain_sms = $udata[0]['remain_sms'];
			if($remain_sms == 0)
			{
				$sms_data['flag'] = 2;
				/*$datac['is_sent_to_all'] = '0';
				$datac['total_sent'] = $send_mail_count;
				$datac['id'] = $cdata['email_campaign_id'];
				$this->obj->update_record($datac);				
				return $email_data;*/
			}
		}

		$fields = '';
		$join_tables = '';
		$where_in = '';
		//$send_mail_count = 0;
		if(!empty($contact_id))
		{
			$message = '';
			$table ="contact_master as cm";   
			$fields = array('cm.*,cpt.phone_no');
			$join_tables = array('contact_phone_trans cpt'=>'cpt.contact_id = cm.id');
			
			$group_by = 'cpt.contact_id';
			//$wherestring = "cpt.is_default = '1'";
			if(!empty($data['phone_trans_id']))
				$wherestring = "cpt.id = ".$data['phone_trans_id'];
			else	
				$wherestring = "cpt.is_default = '1'";
			
			$where_in = array('cm.id'=>$contact_id);
			$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			//echo 
			//pr($from_data);exit;
			if(count($from_data) > 0)
			{	$k=0;$l=0;$m=0;
				foreach($from_data as $row)
				{
					$cdata['contact_id'] = $row['id'];
					
					$agent_name = '';
					if(!empty($row['created_by']))
					{
						$table ="login_master as lm";   
						$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
						$join_tables = array('user_master as um'=>'lm.user_id = um.id');
						$wherestring = 'lm.id = '.$row['created_by'];
						$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
						if(!empty($agent_datalist))
						{
							if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
								$agent_name = $agent_datalist[0]['admin_name'];
							else
								$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
						}
					}
					
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
					$content = $data['sms_message'];
					$cdata['sms_message'] = $content;
					$pattern = "{(%s)}";
					$map = array();
					if($emaildata != '' && count($emaildata) > 0)
					{
						foreach($emaildata as $var => $value)
						{
							$map[sprintf($pattern, $var)] = $value;
						}
						$output = strtr($content, $map);
						$cdata['sms_message'] = $output;
					}
					//$from_data .= $contact_id[$i].",";
					$message = $cdata['sms_message'];
					
					$counter = strlen($message);
					if(!empty($counter))
						$total_message = $counter/160;
					$total_count = 0;
					if(!empty($total_message))
						$total_count = ceil($total_message);
					
					if($remain_sms == 0 || $remain_sms < $total_count)
					{
						$email_data['flag'] = 2;
						$datac['is_sent_to_all'] = '0';
						$datac['total_sent'] = $send_sms_count;
						$datac['id'] = $cdata['sms_campaign_id'];
						$this->obj->update_record($datac);
						$cdata['is_send'] = '0';
						if($remain_sms == 0 && $l == 0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = $this->lang->line('common_sms_limit_over_msg');
							$edata['created_date'] = date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $this->user_session['id'];
							$this->dashboard_model->insert_record1($edata);
							$l++;
						}
						elseif($remain_sms > 0 && $m == 0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = $this->lang->line('common_sms_limit_more_msg');
							$edata['created_date'] = date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $this->user_session['id'];
							$this->dashboard_model->insert_record1($edata);
							$m++;
						}
					}
					else
					{
						$to = $row['phone_no'];
						//$to = '+919033921029';
						
						$send_from = !empty($data['sms_send_from'])?$data['sms_send_from']:0;
						
						$this->twilio->set_admin_id($send_from);
						$response = $this->twilio->sms($from, $to, $message);
						if(!empty($response->ErrorMessage) && $response->ErrorMessage=='Authenticate' && $k== 0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = 'Authentication failed.';
							$edata['created_date'] =date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $this->user_session['id'];
							$this->dashboard_model->insert_record1($edata);
							$k++;
						}
						//echo $response->ErrorMessage;
						$cdata['phone_no'] = $to;
						$cdata['is_send'] = '1';
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$remain_sms = $remain_sms - $total_count;
						$send_sms_count++;
												
						$contact_conversation['contact_id'] = $row['id'];
						$contact_conversation['log_type'] = 8;
						$contact_conversation['campaign_id'] = !empty($cdata['sms_campaign_id'])?$cdata['sms_campaign_id']:'';
						$contact_conversation['sms_camp_template_id'] = !empty($data['template_name'])?$data['template_name']:'';
						if(!empty($data['template_name']))
						{
							$match = array('id'=>$data['template_name']);
							$template_data = $this->sms_texts_model->select_records('',$match,'','=');
							if(count($template_data) > 0)
							{
								$contact_conversation['sms_camp_template_name'] = $template_data[0]['template_name'];
							}
						}
						
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->user_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
						
					}
					
					$this->obj->insert_sms_campaign_recepient_trans($cdata);
				}
			}
			
		}
		//echo $remain_sms;
		
		//$idata['id'] = $this->user_session['id'];
		$sms_data['send_sms_count'] = $send_sms_count;
		if(isset($remain_sms))
			$idata['remain_sms'] = $remain_sms;
		if(!empty($user_id))
		{
			$idata['id'] = $user_id;
			$udata = $this->admin_model->update_user($idata);
		}
		//echo $this->db->last_query();exit;
		return $sms_data;
	}
 
 
 
 
    /*
		@Description: Get Details of Edit SMS campaign
		@Author: Sanjay Chabhadiya
		@Input: - Id of SMS campaign whose details want to change
		@Output: - Details of SMS campaign which id is selected for update
		@Date: 06-08-2014
    */
 
    public function edit_record()
    {
     	//check user right
		check_rights('text_blast_edit');
		$id = $this->uri->segment(4);
		$cdata['send_now'] = $this->uri->segment(5);
		
		//$match = array('id'=>$id,'created_by'=>$this->user_session['id']);
        /*$result = $this->obj->select_records('',$match,'','=');*/
		
		$table = 'sms_campaign_master';
		$where = 'id = '.$id.' AND created_by IN ('.$this->user_session['agent_id'].')';
        $result = $this->obj->getmultiple_tables_records($table,'','','','','','','','','','','',$where);
		$cdata['editRecord'] = $result;
		if(empty($cdata['editRecord']))
		{
			$msg = $this->lang->line('common_right_msg_sms');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		
		if(count($result) > 0)
		{
			if(($result[0]['is_draft'] == 0 && $result[0]['sms_send_date'] == '0000-00-00') || ($result[0]['sms_type'] == 'Intereaction_plan')) {
				redirect(base_url('user/'.$this->viewName));
			}
		}
		else
		{
			redirect(base_url('user/'.$this->viewName));
		}
		
		
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$sms_campaign_id = $result[0]['id'];
		$sms = $this->obj->select_sms_campaign_recepient_trans($sms_campaign_id);
		
		$i=0;
		$contact_type_to = '';
		foreach($sms as $row)
		{
			if(empty($row['contact_type']) && $row['contact_type'] == 0)
				$email_to[$i] = $row['contact_id'];
			else
			{
				if(!in_array($row['contact_type'],$contact_type_to))
					$contact_type_to[$i] = $row['contact_type'];
			}
			$i++;
		}
		
		if(!empty($email_to))
		{
			$cdata['select_to'] = implode(",",$email_to);
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
			$join_tables = array(
								'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"'
							);
			$group_by='cm.id';
			$where_in = array('cm.id'=>$email_to);
			//$where = array('cm.is_subscribe'=>"'0'");
			$cdata['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
			//$cdata['email_to'] = $this->obj->in_query($email_to);
		}
				
		if(!empty($contact_type_to))
		{
			$cdata['contact_type_to'] = $this->contact_type_master_model->contact_type_in_query($contact_type_to);
			$i=0;
			foreach($cdata['contact_type_to'] as $row)
			{
				$contact_type_to_selected[$i] = $row['id'];
				$i++;
			}
			if(!empty($contact_type_to_selected))
				$cdata['contact_type_to_selected'] = implode(",",$contact_type_to_selected);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		
		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'."sms/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 5;
		$uri_segment = 0;
		
		
		$table = "contact_contacttype_trans as cct";
		$fields = array('DISTINCT(ctm.name),ctm.id,ctm.created_date,ctm.created_by,ctm.created_by,ctm.created_by,ctm.status');
		$join_tables = array(
							'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
							'contact_master as cm'=>'cct.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$where='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';		
		$cdata['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],'0','id','desc','',$where);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where,'','1');
		//pr($cdata['contact_list']);exit;
		//$cdata['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		//echo $this->db->last_query();
		//$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config);
		$cdata['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////	
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'."sms/search_contact_to");
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config_to1['uri_segment'] = 5;
		//$uri_segment = $this->uri->segment(5);
		
		/*;
		$data['contact_to'] = $this->contacts_model->select_records('',$match,'','=','',$config_to1['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config_to1['total_rows'] = count($this->contacts_model->select_records('',$match,'','='));
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();*/
		
		$table = "contact_master as cm";
		$where='cpt.is_default = "1" AND (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$group_by = 'cm.id';
		//$where = array('cm.is_subscribe'=>"'0'");
		$cdata['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], '0','cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$cdata['pagination_contact_to'] = $this->pagination->create_links();
		
		$fields = array('cm.id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cpt.phone_no');
		$cdata['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		//echo $cdata['pagination_contact_to'];exit;
		////////////////////////////////////////////////////////////////////////////////////	
		
		//$cdata['email_to'] = $this->obj->in_query(implode(",",$email_to));
		$match = array();
		$cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$table1='custom_field_master';
		$where1=array('module_id'=>'2');
		$cdata['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		//$cdata['tablefield_data']=$this->email_library_model->select_records3();
		//pr($cdata['email_bcc']);exit;
		$cdata['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$cdata['main_content'] = "user/".$this->viewName."/add";       
		$this->load->view("user/include/template",$cdata);
		
    }

    /*
		@Description: Function for Update data
		@Author: Sanjay Chabhadiya
		@Input: - Update details of SMS campaign
		@Output: - List with updated SMS campaign details
		@Date: 06-08-2014
    */
   
    public function update_data()
    {
		//pr($_POST);exit;
		$id = $this->input->post('id');
		$submit = $this->input->post('submitbtn');
		if($this->input->post('submitbtn1') || $this->input->post('submitbtn'))
		{
			$result = $this->sms_campaign_recepient_trans_model->delete_record($id);
			$data['id'] = $id;
			$data['template_name'] = $this->input->post('template_name');
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['sms_message'] = $this->input->post('sms_message');
			$data['sms_send_type'] = $this->input->post('chk_is_lead');
			$send_type = $this->input->post('chk_is_lead');
			
			if($this->input->post('submitbtn'))
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';	
				
			if($data['sms_send_type'] == 2)
			{
				$data['sms_send_date'] = $this->input->post('send_date');
				$data['sms_send_time'] = $this->input->post('send_time');
				$data['is_draft'] = '0';
			}
			else
			{
				$data['sms_send_date'] = '';
				$data['sms_send_time'] = '';
			}
			
			if(empty($data['sms_send_type']) && $this->input->post('submitbtn'))
			{
				$data['sms_send_type'] = 1;
			}
			
			$data['sms_type'] = 'Campaign';
			$data['modified_by'] = $this->user_session['id'];
			$data['modified_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->obj->update_record($data);	
			
			//For twilio from account//
			
			$match = array('id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			
			if(!empty($result[0]['created_by']))
				$data['sms_send_from'] = $result[0]['created_by'];
			else
				$data['sms_send_from'] = 0;
			
			//////////////////////////////
			
			//echo $this->db->last_query();exit;
			$cdata['sms_campaign_id'] = $id;
			$cdata['sms_message'] = $data['sms_message'];
			/*if($submit == 'Send Now')
				$cdata['sent_date'] = '1';*/
				
			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = '';
			$contact_id = '';
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$contact_id[$j] = $contact[$i];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			
			if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
				$cdata['sent_date'] = date('Y-m-d H:i:s');
			
			if((!empty($contact_id) && $this->input->post('submitbtn1')) || ($this->input->post('submitbtn') && $data['sms_send_type'] != 1))
			{
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			
			if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
			{
				$send_sms_count = 0;
				if(!empty($contact_id))		
				{
					$sms_send_data = $this->send_sms($contact_id,$data,$cdata,$send_sms_count);
					//$flag = $email_send_data['send_mail_count'];
					$send_sms_count = $sms_send_data['send_sms_count'];
				}
			}
			
			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = array();
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table = "contact_master as cm";
					$fields = array('cm.id,cm.first_name,cm.last_name');
					$join_tables = array(
										 'contact_contacttype_trans  as cct'=>'cct.contact_id = cm.id',
										 'contact_emails_trans cet'=>'cet.contact_id = cm.id',
										 'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
										 'user_contact_trans as uct'=>'uct.contact_id = cm.id'
										);
			
					$where = 'cct.contact_type_id = '.$contact_type[$i].' AND cet.is_default = "1" AND (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
					$group_by='cm.id';
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
					/*$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$where='(cm.created_by = '.$this->user_session['id'].' OR uct.user_id = '.$this->user_session['user_id'].')';
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','user_contact_trans as uct'=>'uct.contact_id = cm.id');
					
					$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','', '','','','',$where);*/
					
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['id'];
							$j++;
							
						}
						if($this->input->post('submitbtn') && $data['sms_send_type'] != 2)
						{
							
							$sms_send_data = $this->send_sms($contact_id1,$data,$cdata,$send_sms_count);
							/*if(!empty($email_send_data))
								$flag = $email_send_data;
							if($flag == 2)
							break;*/
							$send_sms_count = $sms_send_data['send_sms_count'];
						}
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
			
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			redirect('user/'.$this->viewName);
			/*for($i=0;$i<count($contact_id);$i++)
			{
				$cdata['contact_id'] = $contact_id[$i];
				$this->sms_campaign_recepient_trans_model->insert_record($cdata);
			}*/
		}
		elseif($this->input->post('submitbtn2'))
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->sms_texts_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			else
				$data['template_name'] = "You SMS Template";
			
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['sms_message'] = $this->input->post('sms_message');
			//$data['sms_type'] = '1';
			$data['created_by'] = $this->user_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->sms_texts_model->insert_record($data);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		$searchsort_session = $this->session->userdata('sms_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect('admin/'.$this->viewName.'/'.$pagingid);
		
    }
	
   	/*
		@Description: Function for Delete Task Profile By user
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to delete
		@Output: - New Task list after record is deleted.
		@Date: 06-08-2014
    */

    function delete_record()
    {
        //check user right
		check_rights('text_blast_delete');
		$id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$this->obj->delete_email_campaign_recepient_trans($id);
		$this->obj->delete_email_campaign_attachments($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
    }
	
	/*
		@Description: Function for SMS campaign delete
		@Author: Sanjay Chabhadiya
		@Input: - Delete all id of SMS campaign record want to delete
		@Output: - Delete selected all record
		@Date: 06-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		//exit;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->sms_campaign_recepient_trans_model->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$this->sms_campaign_recepient_trans_model->delete_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		
		$searchsort_session = $this->session->userdata('sms_sortsearchpage_data');
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
		@Description: Function for Unpublish Task Profile By user
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to Unpublish
		@Output: - New Task list after record is Unpublish.
		@Date: 06-08-2014
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
		@Description: Function for publish Task Profile By user
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to publish
		@Output: - New Task list after record is publish.
		@Date: 06-08-2014
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
		@Description: Function for subcategory from category id
		@Author: Sanjay Chabhadiya
		@Input: - Parent category Id
		@Output: - Subcategory list
		@Date: 06-08-2014
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
			$table = "sms_text_template_master as stm";
			$fields = array('stm.*');
			$join_tables = array(
                                                'login_master as lm' => 'lm.id = stm.created_by',
                                            );
			$wherestring='stm.template_category = '.$category_id.' AND sms_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2" OR stm.created_by IN ('.$this->user_session['agent_id'].'))';
			$group_by = 'stm.id';
			$data['templatedata'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','stm.id','desc',$group_by,$wherestring);
		
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

		if(!empty($template_id) && $template_id != '-1')
		{
			$match = array("id"=>$template_id);
        	$cdata['templatedata'] = $this->sms_texts_model->select_records('',$match,'','=','','','','id','desc');
			if(count($cdata['templatedata']) > 0)
			{
				echo json_encode($cdata['templatedata']);
			}
		}
		else
		{
			echo '-1';
		}	
	}
	
	/*
		@Description: Function for search contact type
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Template details
		@Date: 06-08-2014
   	*/
	
	public function search_contact_ajax()
    {
		//echo 'Meet';exit;
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'."sms/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
		$table = "contact_contacttype_trans as cct";
		$fields = array('DISTINCT(ctm.name),ctm.id,ctm.created_date,ctm.created_by,ctm.created_by,ctm.created_by,ctm.status');
		$join_tables = array(
							'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
							'contact_master as cm'=>'cct.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$where='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';	
		if(!empty($searchtext))
		{$match=array('name'=>$searchtext);	}
		else
		{$match='';}
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,'id','desc','',$where);
		$config['total_rows']  = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$where,'','1');
		
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
        $this->load->view("user/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	/*
		@Description: Function for sent SMS list from SMS campaign id wise
		@Author: Sanjay Chabhadiya
		@Input: - SMS campaign id
		@Output: - Sent SMS List 
		@Date: 06-08-2014
   	*/
	
	public function sent_sms()
	{
		//check user right
		check_rights('text_blast');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;
		//pr($data['editRecord']); exit;
		$data['campaign_id'] = $id ;
		
		if(!isset($result[0]['created_by']) || (!empty($result[0]['created_by']) && is_array($this->user_session['agent_id_array']) && !in_array($result[0]['created_by'],$this->user_session['agent_id_array'])))
		{
			$msg = $this->lang->line('common_right_msg_sms_sent');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		
		if(count($result) > 0)
		{
			$sms_campaign_id = $result[0]['id'];
			$match = array('sms_campaign_id'=>$sms_campaign_id);
			$total_cnt = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','','1');
			$match = array('sms_campaign_id'=>$sms_campaign_id,'is_send'=>'1');
			$sent_cnt = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','','1');
			//echo $this->db->last_query();
			$data['not_send'] = $total_cnt - $sent_cnt;
		}	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('sent_sms_sortsearchpage_data');
		}
		$data['sortfield']		= 'sct.id';
		$data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('sent_sms_sortsearchpage_data');
		
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
				$sortfield = 'sct.id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
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
		$config['base_url'] = site_url($this->user_type.'/'."sms/sent_sms/".$id);
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 5;
			$uri_segment = $this->uri->segment(5);
		}
		$table ="contact_master as cm";
		$fields = array('cm.*,sct.id as ID,sct.is_send,sct.sms_message,sct.sent_date');
		$join_tables = array('sms_campaign_recepient_trans as sct'=>'sct.contact_id = cm.id');
		$wherestring = "sct.sms_campaign_id = ".$sms_campaign_id; //." AND is_send = '1'";
		if(!empty($searchtext))
		{	
			$match = array("CONCAT_WS(' ',cm.first_name,cm.last_name)"=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$wherestring,'','1');
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring,'','1');
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		 $sent_sms_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('sent_sms_sortsearchpage_data', $sent_sms_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_sent_sms',$data);
		}
		else
		{	
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/sent_sms_list";
			$this->load->view('user/include/template',$data);
		}
		
	}
	
	/*
		@Description: Function for sent SMS view data
		@Author: Sanjay Chabhadiya
		@Input: - SMS campaign id and contact id
		@Output: - Sent SMS view data 
		@Date: 06-08-2014
   	*/
	
	public function view_data()
	{
		$data['campaign_id'] = $this->uri->segment(4);
		$data['id'] = $this->uri->segment(5);
		$pageid = $this->uri->segment(5);
		
		$table = "sms_campaign_master scm";
		$fields = array("cm.first_name,cm.last_name,mml1.category as category,mml2.category as subcategory,scm.*,scr.sms_message,stm.template_name,scr.phone_no");
		$join_tables = array('sms_campaign_recepient_trans scr'=>'scm.id = scr.sms_campaign_id',
							'contact_master cm'=>'scr.contact_id = cm.id',
							'marketing_master_lib__category_master mml1'=>'mml1.id = scm.template_category',
							'marketing_master_lib__category_master mml2'=>'mml2.id = scm.template_subcategory',
							'sms_text_template_master stm'=>'stm.id = scm.template_name',
							//'(select * from contact_phone_trans order by is_default desc) as cpt'=>'cpt.contact_id = scr.contact_id'
							);
		$wherestring = 'scr.sms_campaign_id = '.$data['campaign_id'].' AND scr.id = '.$data['id'];
		$group_by = 'scr.id';
		$cdata['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		if(empty($cdata['datalist']))
		{
			$msg = $this->lang->line('common_right_msg_sms_send_his');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName.'/all_sent_sms');	
		}
		
		if($this->uri->segment(6) != '')
		{
			$pagingid = $this->obj->getpaging($pageid);
		}
		else
		{
			$pagingid = $this->obj->getpaging($pageid,$data['campaign_id']);
		}
		$cdata['pagingid'] = $pagingid;
		//pr($cdata['datalist']); exit;
		$cdata['main_content'] =  $this->user_type.'/'.$this->viewName."/view_data";
		$this->load->view('user/include/template',$cdata);
	}
	
	
	/*
		@Description: Function for resend SMS single or multiple
		@Author: Sanjay Chabhadiya
		@Input: - SMS campaign id or sms campaign transaction id
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function resend_sms()
	{
		$from = $this->config->item('from_sms');
		
		$id[0] = $this->input->post('single_id');
		$campaign_id = $this->uri->segment(4);
		$page = $this->uri->segment(5);
		$flag = 0;
		if(!empty($id[0]))
			$contact_id = $id;
		else
		{
			$match = array('sms_campaign_id'=>$campaign_id,'is_send'=>'0');
			$result = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=');
			//echo $this->db->last_query();
			$i=0;
			$flag = 1;
			foreach($result as $row)
			{
				$contact_id[$i] = $row['id'];
				$i++;
				
			}
		}
		$field = array('id','created_by');
		$match = array('id'=>$this->user_session['id']);
		$admin_data = $this->admin_model->get_user($field,$match,'','=');
		$remain_sms = 0;
		if(count($admin_data) > 0)
		{
			$user_id = $admin_data[0]['created_by'];
			$field = array('id','remain_sms');
        	$match = array('id'=>$user_id);
        	$udata = $this->admin_model->get_user($field, $match,'','=');	
		}
		
		//$email_data['flag'] = 1;
		if(!empty($udata) && count($udata) > 0)
		{
			$remain_sms = $udata[0]['remain_sms'];
			if($remain_sms == 0)
			{
				$sms_data['flag'] = 2;
			}
		}
		$fields = '';
		$join_tables = '';
		//$where_in = '';
		//$remain_emails = 5;
		//$send_mail_count = 0;
		if(!empty($contact_id))
		{
			$table ="sms_campaign_recepient_trans as scr";
			$fields = array('cpt.phone_no,scr.id as ID,scr.sms_message,scr.sms_campaign_id,scr.contact_id,scr.contact_id,cm.*');
			$join_tables = array('contact_phone_trans cpt'=>'cpt.contact_id = scr.contact_id','contact_master cm'=>'cm.id = scr.contact_id');
			
			$group_by = 'cpt.contact_id';
			$wherestring = "cpt.is_default = '1' AND scr.is_send = '0' AND scr.sms_campaign_id = ".$campaign_id;
			//$match = array('ecm.id'=>$email_campaign_id);
			$where_in = array('scr.id'=>$contact_id);
			$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			
			$match = array('id'=>$campaign_id);
			$campaign_data = $this->obj->select_records('',$match,'','=');
			
			$message = '';
			if(count($campaign_data) > 0)
			{
				$send_sms_count = !empty($campaign_data[0]['total_sent'])?$campaign_data[0]['total_sent']:'';
				$datac['id'] = !empty($campaign_data[0]['id'])?$campaign_data[0]['id']:'';
			}
			//pr($datalist);exit;
			if(!empty($datalist))
			{$k=0;$l=0;$m=0;
				foreach($datalist as $row)
				{$k=0;
					$cdata['id'] = $row['ID'];
					$message = $row['sms_message'];
					
					$counter = strlen($message);
					if(!empty($counter))
						$total_message = $counter/160;
					$total_count = 0;
					if(!empty($total_message))
						$total_count = ceil($total_message);
						
					if($remain_sms == 0 || $remain_sms < $total_count)
					{
						$cdata['is_send'] = '0';
						if($remain_sms == 0 && $l == 0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = $this->lang->line('common_sms_limit_over_msg');
							$edata['created_date'] = date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $this->user_session['id'];
							$this->dashboard_model->insert_record1($edata);
							$l++;
						}
						elseif($remain_sms > 0 && $m == 0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = $this->lang->line('common_sms_limit_more_msg');
							$edata['created_date'] = date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $this->user_session['id'];
							$this->dashboard_model->insert_record1($edata);
							$m++;
						}
					}
					else
					{
						//$to = '+919033921029';
						$to = $row['phone_no'];
						
						//For twilio from account//
				
						$match = array('id'=>$campaign_id);
						$result_twilio_sms = $this->obj->select_records('',$match,'','=');
						
						if(!empty($result_twilio_sms[0]['created_by']))
							$send_from = $result_twilio_sms[0]['created_by'];
						else
							$send_from = 0;
						
						////////////////////////////
						
						$this->twilio->set_admin_id($send_from);
						
						$response = $this->twilio->sms($from, $to, $message);
						if(!empty($response->ErrorMessage) && $response->ErrorMessage=='Authenticate' && $k==0)
						{
							$edata['type'] = 'Twilio';
							$edata['description'] = 'Authentication failed.';
							$edata['created_date'] =date('Y-m-d h:i:s');
							$edata['created_by'] = $this->user_session['id'];
							$edata['status'] = 1;
							$this->dashboard_model->insert_record1($edata);
							$k++;
						}
						$cdata['phone_no'] = $to;
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$cdata['is_send'] = '1'; 
						$remain_sms = $remain_sms - $total_count;
						$send_sms_count++;
	
						if(!empty($campaign_data))
						{
							$contact_conversation['contact_id'] = $row['contact_id'];
							$contact_conversation['log_type'] = 8;
							$contact_conversation['campaign_id'] = $campaign_data[0]['id'];
							$contact_conversation['sms_camp_template_id'] = $campaign_data[0]['template_name'];
	
							if(!empty($campaign_data[0]['template_name']))
							{
								$match = array('id'=>$campaign_data[0]['template_name']);
								$template_data = $this->sms_texts_model->select_records('',$match,'','=');
								if(count($template_data) > 0)
								{
									$contact_conversation['sms_camp_template_name'] = $template_data[0]['template_name'];
								}
							}
							
							$contact_conversation['created_date'] = date('Y-m-d H:i:s');
							$contact_conversation['created_by'] = $this->user_session['id'];
							$contact_conversation['status'] = '1';
							$this->contact_conversations_trans_model->insert_record($contact_conversation);
						}
					}
					$this->sms_campaign_recepient_trans_model->update_record($cdata);
				}
				$match = array('sms_campaign_id'=>$campaign_id);
				$sent_cnt = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','','1');
				if($sent_cnt == $send_sms_count)
				{
					$datac['is_draft'] = '0';
					$datac['is_sent_to_all'] = '1';
					$datac['total_sent'] = 0;
				}
				else
				{
					$datac['is_sent_to_all'] = '0';
					$datac['total_sent'] = $send_sms_count;
				}
				$datac['id'] = $campaign_id;
				$this->obj->update_record($datac);
			}
		}
		if(isset($remain_sms))
			$idata['remain_sms'] = $remain_sms;
		if(!empty($user_id))
		{
			$idata['id'] = $user_id;
			$udata = $this->admin_model->update_user($idata);
		}
		
		if($flag == 1)
			redirect('user/'.$this->viewName);
		else
			echo "/".$campaign_id."/".$page;
	}
	
	/*
		@Description: Function for Get All sent SMS  List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all sent SMS list
		@Date: 30-08-2014
    */

	
	public function all_sent_sms()
	{	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('all_sent_sms_sortsearchpage_data');
		}
		$data['sortfield']		= 'scr.id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('all_sent_sms_sortsearchpage_data');
		
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
				$sortfield = 'scr.id';
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
					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  mysql_real_escape_string($data['searchtext']);
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
		$config['base_url'] = site_url($this->user_type.'/sms/'."all_sent_sms/");
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
		
		$table ="sms_campaign_master as scm";   
		$fields = array('scr.sms_campaign_id,stm.template_name,scr.id,scr.sms_message','cm.first_name,cm.last_name','scr.sent_date,scm.interaction_id','ipi.description,ipm.plan_name');
		$join_tables = array('sms_campaign_recepient_trans as scr'=>'scr.sms_campaign_id = scm.id',
							 'contact_master as cm'=>'cm.id = scr.contact_id',
							 'sms_text_template_master as stm'=>'stm.id = scm.template_name',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
							 
		//$wherestring = "scr.is_send = '1'";
		$wherestring = 'scr.is_send = "1" AND scm.created_by IN ('.$this->user_session['agent_id'].')';
		if(!empty($searchtext))
		{
			$concat = "CONCAT_WS(' ',cm.first_name,cm.last_name)";
			$interaction_plan = "CONCAT_WS(' >> ',ipm.plan_name,ipi.description)";
			$match=array('stm.template_name'=>$searchtext,'scr.sms_message'=>$searchtext,$concat=>$searchtext,$interaction_plan=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			/*echo $this->db->last_query();
			pr($data['datalist']);exit;*/
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'','1');
		}
				
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		  $all_sent_sms_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('all_sent_sms_sortsearchpage_data', $all_sent_sms_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		

		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_all_sent_smslist',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/all_sent_smslist";
			$this->load->view('user/include/template',$data);
		}
    	
	}
	
	/*
		@Description: Function for Get All queued SMS(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued SMS
		@Date: 30-08-2014
    */
	
	public function queued_list()
	{
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$data['sortfield_name'] = 'ipi.id';
		$data['sort'] = 'desc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield_name'] = $sortfield;
			$sortby = $this->input->post('sortby');
			$data['sort'] = $sortby;
		}
		else
		{
			$sortfield = 'ipi.id';
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
		if(!empty($date1) && !empty($date2))
		{
			 $date1 = $this->input->post('date1');
			 $date2 = $this->input->post('date2');
			 $data['date1'] = $date1;
           	 $data['date2'] = $date2;	
		}
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/sms/'."queued_list/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="sms_campaign_master as scm";
		$fields = array('ipm.*');
		$join_tables = array('sms_campaign_recepient_trans as scr'=>'scr.sms_campaign_id = scm.id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$wherestring = "scm.sms_type = 'Intereaction_plan' AND scr.is_send = '0' AND ipm.status = '1' AND ipi.status='1' AND (ipm.created_by IN (".$this->user_session['agent_id'].") OR ipi.assign_to IN (".$this->user_session['agent_id']."))";
		$groupby = 'ipm.id';
		if(!empty($searchtext))
		{
			$match=array('ipm.plan_name'=>$searchtext);
			$data['interaction_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		else
		{
			$data['interaction_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
				
		$this->pagination->initialize($config);
		$data['interaction_pagination']	= $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_queued_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/queued_list";
			$this->load->view('user/include/template',$data);
		}	
	}
	
	/*
		@Description: Function for Get Queued SMS Interaction plan list
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - Interaction plan list
		@Date: 30-08-2014
    */
	
	public function interaction_queued_list()
	{
		$searchopt='';$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$data['sortfield_name']		= 'ipm.id';
		$data['sort']			= 'desc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			$sortfield = 'ipm.id';
			$sortby = 'desc';
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/sms/'."interaction_queued_list/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="sms_campaign_master as scm";
		$fields = array('ipm.*');
		$join_tables = array('sms_campaign_recepient_trans as scr'=>'scr.sms_campaign_id = scm.id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$match=array('ipm.plan_name'=>$searchtext);
		$wherestring = "scm.sms_type = 'Intereaction_plan' AND scr.is_send = '0' AND ipm.status = '1' AND ipm.created_by IN (".$this->user_session['agent_id'].')';
		$groupby = 'ipm.id';
		$data['interaction_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		
		$this->pagination->initialize($config);
		$data['interaction_pagination']	= $this->pagination->create_links();
		
		$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_interaction_queued_list',$data);
	}
	
	/*
		@Description: Function for Get Queued SMS list
		@Author: Sanjay Chabhadiya
		@Input: - interaction_plan_id
		@Output: - SMS Queued list
		@Date: 30-08-2014
    */
	
	public function interaction_plan_queued_list()
	{
		$interaction_plan = $this->uri->segment(4);
		$searchopt='';$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('sms_interaction_plan_queued_list_data');
		}
		$data['sortfield_name']		= 'scr.id';
		$data['sort']			= 'desc';
		$searchsort_session = $this->session->userdata('sms_interaction_plan_queued_list_data');
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			$sortby = $this->input->post('sortby');
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
				$sortfield = 'scr.id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		else
		{
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];
				}
			}		
		}
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
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
		
		$config['base_url'] = site_url($this->user_type.'/sms/'."interaction_plan_queued_list/".$interaction_plan);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table ="sms_campaign_recepient_trans as scr";
		$fields = array('scr.sms_message','cm.first_name,cm.last_name','ipi.description,ipm.plan_name','scm.sms_send_date,scm.sms_type,scr.send_sms_date,scm.interaction_id,scr.id,scr.is_sms_exist,ipccp1.is_done,ipi.interaction_id as interaction_id1,scr.is_sms_exist,cpt.is_default,ipi.start_type as i_start_type');
		$join_tables = array('sms_campaign_master as scm'=>'scm.id = scr.sms_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = scr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',
							 '(select * from contact_phone_trans  where is_default = "1") as cpt'=>'cpt.contact_id = scr.contact_id'
							 );
							/*email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'*/
		
		$wherestring = "scm.sms_type = 'Intereaction_plan' AND ipm.id = ".$interaction_plan." AND scr.is_send = '0' AND ipi.status='1' AND (ipm.created_by IN (".$this->user_session['agent_id'].") OR ipi.assign_to IN (".$this->user_session['agent_id']."))";
		$groupby = 'scr.id';
		if(!empty($searchtext))
		{
			$match = array('scr.sms_message'=>$searchtext,"CONCAT_WS(' ',cm.first_name,cm.last_name)"=>$searchtext,"CONCAT_WS(' >> ',ipm.plan_name,ipi.description)"=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			/*pr($data['datalist']);
			echo $this->db->last_query();exit;*/
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		
		//pr($data['datalist']);exit;
		/*if(empty($data['datalist']))
		{
			$msg = $this->lang->line('common_right_msg_sms_queued');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName.'/queued_list');	
		}*/
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$sms_sortsearchpage_data = array(
                    'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
		$this->session->set_userdata('sms_interaction_plan_queued_list_data', $sms_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/interaction_ajax_queued_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/interaction_queued_list";
			$this->load->view('user/include/template',$data);
		}
	}
	
	/*
		@Description: Function for Copy SMS campaign
		@Author: Sanjay Chabhadiya
		@Input: - SMS campaign id
		@Output: - all SMS campaign list
		@Date: 06-08-2014
    */
	
	public function copy_record()
    {
		//check user right
		check_rights('text_blast_add');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0)
		{
			$data['template_name'] = $result[0]['template_name'];
			$data['template_category'] = $result[0]['template_category'];
			$data['template_subcategory'] = $result[0]['template_subcategory'];
			$data['sms_message'] = $result[0]['sms_message'];
			$data['sms_send_type'] = $result[0]['sms_send_type'];
			
			$data['is_draft'] = '1';
			$data['created_by'] = $this->user_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$this->obj->insert_record($data);
		}
		$this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
	}

	/*
		@Description: Function for get interaction plan SMS details
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - All Details
		@Date: 03-09-2014
    */
	
	public function view_interaction_data()
	{
		$id = $this->uri->segment(4);
		$table ="sms_campaign_recepient_trans as scr";
		$fields = array('cpt.phone_no,scr.id,scr.sms_campaign_id,scr.sms_message,scr.contact_id,stm.template_name,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,mml1.category as category,scm.interaction_id,ipim.assign_to');
		$join_tables = array('sms_campaign_master scm'=>'scm.id = scr.sms_campaign_id',
							 'contact_master cm'=>'cm.id = scr.contact_id',
							 'sms_text_template_master as stm'=>'stm.id = scm.template_name',
							 'contact_phone_trans cpt'=>'cpt.contact_id = scr.contact_id',
							 'marketing_master_lib__category_master mml1'=>'mml1.id = scm.template_category',
							 'interaction_plan_interaction_master as ipim'=>'ipim.id = scm.interaction_id'
							 );
			
		$group_by = 'cpt.contact_id';
		$wherestring = "cpt.is_default = '1' AND scr.is_send = '0' AND scr.id = ".$id;
		$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		
		if(count($data['datalist']) > 0 && is_array($this->user_session['agent_id_array']) && !in_array($data['datalist'][0]['created_by'],$this->user_session['agent_id_array']) && !in_array($data['datalist'][0]['assign_to'],$this->user_session['agent_id_array']))
		{
			$msg = $this->lang->line('common_right_msg_sms_queued');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName.'/queued_list');	
		}
		elseif(count($data['datalist']) > 0)
		{
			$data['main_content'] = "user/".$this->viewName."/interaction_view_data";
        	$this->load->view('user/include/template',$data);
		}
		else
		{
			$cdata['id'] = $id;
			$cdata['is_sms_exist'] = '0';
			$this->sms_campaign_recepient_trans_model->update_record($cdata);
			$this->session->set_userdata('message_session', $newdata);
            redirect(base_url('user/'.$this->viewName.'/interaction_plan_queued_list/'.$this->uri->segment(5)));	
		}
	}
	
	/*
		@Description: Function for Send the queued SMS(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued SMS
		@Date: 03-09-2014
    */


	public function interaction_mailsms()
	{
		$from = $this->config->item('from_sms');
		
		$interaction_id = $this->input->post('interaction_id');
		$interaction_plan_id = $this->input->post('interaction_plan_id');
		$id = $this->input->post('id');
		/*$id = $this->input->post('single_id');
		$interaction_id = $this->input->post('interaction_id');
		$page = $this->uri->segment(4);*/
		
		/*$user_id = $this->user_session['id'];
		$field = array('id','remain_sms');
        $match = array('id'=>$user_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');*/
		$field = array('id','created_by');
		$match = array('id'=>$this->user_session['id']);
		$admin_data = $this->admin_model->get_user($field,$match,'','=');
		$remain_sms = 0;
		if(count($admin_data) > 0)
		{
			$user_id = $admin_data[0]['created_by'];
			$field = array('id','remain_sms');
        	$match = array('id'=>$user_id);
        	$udata = $this->admin_model->get_user($field, $match,'','=');	
		}
		
		//$result = $this->obj->email_campaign_trans_data($id);
		
		$table ="sms_campaign_recepient_trans as scr";
		$fields = array('cpt.phone_no,scr.id,scr.sms_campaign_id,scr.sms_message,scr.contact_id,scm.template_name');
		$join_tables = array('contact_phone_trans cpt'=>'cpt.contact_id = scr.contact_id',
							 'sms_campaign_master scm'=>'scm.id = scr.sms_campaign_id',
							 'contact_master cm'=>'cm.id = scr.contact_id'
							 );
			
		$group_by = 'cpt.contact_id';
		$wherestring = "cpt.is_default = '1' AND scr.is_send = '0' AND scr.id = ".$id;
		$result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		//pr($result);
		if(!empty($udata) && count($udata) > 0)
		{
			$remain_sms = $udata[0]['remain_sms'];
		}
		$message = '';
		if(count($result) > 0)
		{
			$cdata['id'] = $result[0]['id'];
			//$message = !empty($result[0]['sms_message'])?$result[0]['sms_message']:'';
			$message = $this->input->post('sms_message');

			$counter = strlen($message);
			if(!empty($counter))
				$total_message = $counter/160;
			$total_count = 0;
			if(!empty($total_message))
				$total_count = ceil($total_message);

			if($remain_sms == 0 || $remain_sms < $total_count)
			{
				$cdata['is_send'] = '0';
				if($remain_sms == 0)
				{
					$edata['type'] = 'Twilio';
					$edata['description'] = $this->lang->line('common_sms_limit_over_msg');
					$edata['created_date'] = date('Y-m-d h:i:s');
					$edata['status'] = 1;
					$edata['created_by'] = $this->user_session['id'];
					$this->dashboard_model->insert_record1($edata);
				}
				elseif($remain_sms > 0)
				{
					$edata['type'] = 'Twilio';
					$edata['description'] = $this->lang->line('common_sms_limit_more_msg');
					$edata['created_date'] = date('Y-m-d h:i:s');
					$edata['status'] = 1;
					$edata['created_by'] = $this->user_session['id'];
					$this->dashboard_model->insert_record1($edata);
				}
			}
			else
			{
				$to = $result[0]['phone_no'];
				$cdata['phone_no'] = $to;
				//$to = '+919033921029';
				
				//For twilio from account//
				
				$this->load->model('interaction_model');
				$match = array('id'=>$interaction_id);
				$result_sms_data = $this->interaction_model->select_records('',$match,'','=');
				
				if(!empty($result_sms_data[0]['assign_to']))
					$send_from = $result_sms_data[0]['assign_to'];
				else
					$send_from = 0;
				
				//////////////////////////////
				
				$this->twilio->set_admin_id($send_from);
				
				$response = $this->twilio->sms($from, $to, $message);
				if(!empty($response->ErrorMessage) && $response->ErrorMessage=='Authenticate')
				{
					$edata['type'] = 'Twilio';
					$edata['description'] = 'Authentication failed.';
					$edata['created_date'] =date('Y-m-d h:i:s');
					$edata['status'] = 1;
					$edata['created_by'] = $this->user_session['id'];
					$this->dashboard_model->insert_record1($edata);
					
				}
				$remain_sms = $remain_sms - $total_count;
				$cdata['sent_date'] = date('Y-m-d H:i:s');
				$cdata['sms_message'] = $message;
				$cdata['is_send'] = '1'; 
				if(!empty($result))
				{
					$contact_conversation['contact_id'] = $result[0]['contact_id'];
					$contact_conversation['log_type'] = 7;
					$contact_conversation['campaign_id'] = $result[0]['sms_campaign_id'];
					$contact_conversation['sms_camp_template_id'] = $result[0]['template_name'];

					if(!empty($result[0]['template_name']))
					{
						$match = array('id'=>$result[0]['template_name']);
						$template_data = $this->sms_texts_model->select_records('',$match,'','=');
						if(count($template_data) > 0)
						{
							$contact_conversation['sms_camp_template_name'] = $template_data[0]['template_name'];
						}
					}
					
					$contact_conversation['created_date'] = date('Y-m-d H:i:s');
					$contact_conversation['created_by'] = $this->user_session['id'];
					$contact_conversation['status'] = '1';
					//pr($contact_conversation);exit;
					$this->contact_conversations_trans_model->insert_record($contact_conversation);
					
					$icdata['interaction_plan_interaction_id'] = $interaction_id;
					$icdata['contact_id'] = $result[0]['contact_id'];
					$icdata['is_done']='0';
					
					$communication_trans_id = $this->contacts_model->get_interaction_plan_contact_communication_plan($icdata);
					if(count($communication_trans_id) > 0)
					{
						$icdata['id'] = $communication_trans_id[0]['id'];
						$icdata['task_date'] = date('Y-m-d');
						$icdata['task_completed_date'] = date('Y-m-d H:i:s');
						$icdata['completed_by'] = $this->user_session['id'];
						$icdata['is_done']='1';
						$this->contacts_model->update_interaction_plan_interaction_transtrans_record($icdata);
						common_rescheduled_task($icdata['id']);
					}
					
					/*$icdata['interaction_plan_interaction_id'] = $interaction_id;
					$icdata['task_date'] = date('Y-m-d');
					$icdata['contact_id'] = $result[0]['contact_id'];
					$icdata['task_completed_date'] = date('Y-m-d H:i:s');
					$icdata['completed_by'] = $this->admin_session['id'];
					$icdata['is_done']='1';
					$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata);*/
					//echo $this->db->last_query();
				}
			}
			$this->sms_campaign_recepient_trans_model->update_record($cdata);
			//echo $this->db->last_query();
		}
		else
		{
			$cdata['id'] = $id;
			$cdata['is_sms_exist'] = '0';
			$this->sms_campaign_recepient_trans_model->update_record($cdata);
		}
		//echo $send_sms_count; exit;
		//$idata['id'] = $this->user_session['id'];
		if(isset($remain_sms))
			$idata['remain_sms'] = $remain_sms;
		if(!empty($user_id))
		{
			$idata['id'] = $user_id;
			$udata = $this->admin_model->update_user($idata);
		}
		
		$searchsort_session = $this->session->userdata('sms_interaction_plan_queued_list_data');
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
		//echo $pagingid;
		if(!empty($interaction_plan_id))
			redirect('user/'.$this->viewName.'/interaction_plan_queued_list/'.$interaction_plan_id.'/'.$pagingid);
		else
			redirect('user/'.$this->viewName);
	}
	
	/*
		@Description: Function for Mobile number not available SMS transaction delete
		@Author: Sanjay Chabhadiya
		@Input: - ID
		@Output: - 
		@Date: 03-09-2014
    */
	
	function delete_record_trans()
    {
        $id = $this->input->post('id');
		$this->sms_campaign_recepient_trans_model->delete_record_campaign('',$id);
		echo 1;
    }
	
	/*
		@Description: Function for search contact SMS add or edit time
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 06-08-2014
   	*/
	
	function search_contact_to()
	{
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."sms/search_contact_to");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		
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
			$data['perpage'] = '50';
            $config['per_page'] = '50';
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
		
		$where = '';
		if(!empty($contact_status))
			$where = 'cm.contact_status = '.$contact_status.' AND ';
		if(!empty($contact_source))
			$where .= 'cm.contact_source = '.$contact_source.' AND ';
		if(!empty($contact_type))
			$where .= 'cct.contact_type_id = '.$contact_type.' AND ';
			
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'cpt.phone_no'=>$searchtext,'cm.company_name'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		
		$table = "contact_master as cm";
		$where .= 'cpt.is_default = "1" AND (cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$search_tag = $this->input->post('search_tag');
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
		
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
				
		$this->pagination->initialize($config);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();
        $this->load->view("user/".$this->viewName."/contact_to", $data);
	}
	
	/*
		@Description: Function for Selected contacts add the SMS to
		@Author: Sanjay Chabhadiya
		@Input: - contact_id
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function add_contacts_to_email()
	{
		$contacts_type = $this->input->post('contacts_type');
		$data['contacts_data'] = $this->contact_type_master_model->contact_type_in_query($contacts_type);
		echo json_encode($data['contacts_data']);
	}	
	
	public function contacts_to_email()
	{
		$contacts = $this->input->post('contacts_id');
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"'
						);
		$group_by='cm.id';
		$where_in = array('cm.id'=>$contacts);
		$data['contacts_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
		
		echo json_encode($data['contacts_data']);
	}

}