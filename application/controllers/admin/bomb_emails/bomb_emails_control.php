<?php 
/*
    @Description: Email campaign controller
    @Author: Sanjay Chabhadiya
    @Input: 
    @Output: 
    @Date: 06-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bomb_emails_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('phonecall_script_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('bomb_library_model');
		$this->load->model('contacts_model');
		$this->load->model('email_signature_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('interaction_model');
		$this->load->model('imageupload_model');
		$this->load->model('contact_masters_model');
		$this->load->model('common_function_model');
		$this->load->model('dashboard_model'); 
		$this->obj = $this->email_campaign_master_model;
		$this->view = $this->router->uri->segments[2];
		$this->viewName = 'emails';
		$this->user_type = 'admin';
    }

	/*
		@Description: Function for Module All details view.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 22-12-2014
    */
	
	/*public function emails_home()
	{
		//check user right
		check_rights('email_blast');
		
		$data['main_content'] = 'admin/'.$this->viewName."/home";
		$this->load->view('admin/include/template',$data);	
	}*/

    /*
		@Description: Function for Get All Email campaign List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all Email campaign list
		@Date: 06-08-2014
    */

    public function index()
    {	
		//check user right
		check_rights('bomb_bomb_email_blast');
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('bomb_sortsearchpage_data');
		}
		$data['sortfield']		= 'ecm.id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('bomb_sortsearchpage_data');
		
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
				$sortfield = 'ecm.id';
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
		$config['base_url'] = site_url($this->user_type.'/'.$this->view);
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
		
		$table ="email_campaign_master as ecm";   
		$fields = array('ecm.id','ecm.template_subject','ecm.is_draft,ecm.is_sent_to_all,ecm.email_send_date,ecm.email_send_time','etm.template_name');
		$join_tables = array('bomb_template_master as etm'=>'etm.id = ecm.template_name_id');
		
		$wherestring = 'ecm.email_type = "Campaign" AND ecm.email_blast_type = 1';
		if(!empty($searchtext))
		{
			$match=array('ecm.template_subject'=>$searchtext,'etm.template_name'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'','1');
		}
		
		$parent_db_name = $this->config->item('parent_db_name');
		$sesion_db = $this->session->userdata('db_session');
		$match1 = array('db_name'=>$sesion_db['db_name'],'user_type'=>'2');
		$admin_data = $this->admin_model->get_user('',$match1,'','=','','','','','','',$parent_db_name);
		if(count($admin_data) > 0)
			$admin_id = $admin_data[0]['id'];
		else
			$admin_id = 0;
		$data['total_email'] = $this->obj->total_emails($admin_id,$parent_db_name);
		$admin_id = $this->admin_session['admin_id'];
		$field = array('id','remain_emails');
        $match = array('id'=>$admin_id);
		$data['udata'] = $this->admin_model->get_user($field, $match,'','=');
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
		$bomb_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('bomb_sortsearchpage_data', $bomb_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('admin/include/template',$data);
		}
    }
	
    /*
    @Description: Function Add New Email campaign details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add Email campaign details
    @Date: 06-08-2014
    */
   
    public function add_record()
    {
		//check user right
		check_rights('bomb_bomb_email_blast_add');
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		if(!empty($connection[0]['bombbomb_username']) && !empty($connection[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($connection[0]['bombbomb_password']);
			$data['username'] = $connection[0]['bombbomb_username'];
			$data['password'] = $password;
		}
		
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if(!empty($id))
		{ 
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
			$group_by = 'cm.id';
			$where = array('cet.id'=>$this->uri->segment(5),'cm.id'=>$this->uri->segment(4));
			//$where_in = array('cm.id'=>$id);
			$data['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		}
		
		///////////////////////////////////// Contact Type ////////////////////////////////////////////////
		
		/*$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');

		$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));

		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////
		$config1['per_page'] = '10';
		$config1['cur_page'] = '0';	
		$config1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_cc');
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_cc'] = $this->contact_type_master_model->select_records('','','','','',$config1['per_page'],'','id','desc');
		$config1['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));

		$this->pagination->initialize($config1);
		
		$data['pagination_cc'] = $this->pagination->create_links();

		////////////////////////////////////////////////////////////////////////////////////
		
		$config_bcc['per_page'] = '10';	
		$config_bcc['cur_page'] = '0';	
		$config_bcc['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_bcc');
        $config_bcc['is_ajax_paging'] = TRUE; // default FALSE
        $config_bcc['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_bcc['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_bcc'] = $this->contact_type_master_model->select_records('','','','','',$config_bcc['per_page'],'','id','desc');
		$config_bcc['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		
		$this->pagination->initialize($config_bcc);
		
		$data['pagination_bcc'] = $this->pagination->create_links();*/
		//////////////////////////////////// END ////////////////////////////////////////////////
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_to');
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_to1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();

		////////////////////////////////////////////////////////////////////////////////////
	
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_cc');
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_cc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_cc'] = $this->pagination->create_links();
			
		///////////////////////////////////////////////////////////////////////////////////
		
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_bcc');
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_bcc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_bcc'] = $this->pagination->create_links();
		
		//////////////////////////////////////// END ////////////////////////////////////////////
		
		$data['communication_plans'] = '';
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by = 'cet.id';
		$data['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		$table = 'email_signature_master esm';
		$fields = array('esm.*');
		$join_tables = array('login_master lm' => 'lm.id = esm.created_by');
		$match = "user_type = '2' OR user_type = '5'";
		$data['email_signature_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$match);
		//pr($cdata['email_signature_data']);exit;
		
		$table1='custom_field_master';
		$where1=array('module_id'=>'1');
		$data['tablefield_data']=$this->bomb_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		
		$match = array();
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		// BombBomb Connection
		
		/*$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$result = $this->admin_model->get_user($fields,$match,'','=');*/
		
		$data['sortfield'] = 'cm.first_name';
		$data['sortby'] = 'asc';
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
	/*
    @Description: Function Add New Email campaign details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add Email campaign details
    @Date: 06-08-2014
    */
   
    public function add_bombemails_record()
    {
		
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if(!empty($id))
		{ 
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
			$group_by = 'cm.id';
			$where = array('cet.id'=>$this->uri->segment(5),'cm.id'=>$this->uri->segment(4));
			//$where_in = array('cm.id'=>$id);
			$data['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		}
		
		///////////////////////////////////// Contact Type ////////////////////////////////////////////////
		
		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');

		$config['total_rows'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc','1');

		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////
		$config1['per_page'] = '10';
		$config1['cur_page'] = '0';	
		$config1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_cc');	
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_cc'] = $this->contact_type_master_model->select_records('','','','','',$config1['per_page'],'','id','desc');
		$config1['total_rows'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc','1');

		$this->pagination->initialize($config1);
		
		$data['pagination_cc'] = $this->pagination->create_links();

		////////////////////////////////////////////////////////////////////////////////////
		
		$config_bcc['per_page'] = '10';	
		$config_bcc['cur_page'] = '0';	
		$config_bcc['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_bcc');
        $config_bcc['is_ajax_paging'] = TRUE; // default FALSE
        $config_bcc['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_bcc['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_bcc'] = $this->contact_type_master_model->select_records('','','','','',$config_bcc['per_page'],'','id','desc');
		$config_bcc['total_rows'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc','1');
		
		$this->pagination->initialize($config_bcc);
		
		$data['pagination_bcc'] = $this->pagination->create_links();
		//////////////////////////////////// END ////////////////////////////////////////////////
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_to');
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_to1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();

		////////////////////////////////////////////////////////////////////////////////////
	
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_cc');
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_cc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_cc'] = $this->pagination->create_links();
			
		///////////////////////////////////////////////////////////////////////////////////
		
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_bcc');
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		$data['contact_bcc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_bcc'] = $this->pagination->create_links();
		
		//////////////////////////////////////// END ////////////////////////////////////////////
		
		$data['communication_plans'] = '';
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by = 'cet.id';
		$data['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		//echo $this->db->last_query();
		//pr($data['contact']);exit;
		
		/*$match = array('created_by'=>$this->admin_session['id']);
		$data['email_signature_data'] = $this->email_signature_model->select_records('',$match,'','=');*/
		
		$table = 'email_signature_master esm';
		$fields = array('esm.*');
		$join_tables = array('login_master lm' => 'lm.id = esm.created_by');
		$match = "user_type = '2' OR user_type = '5'";
		$data['email_signature_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$match);
		//pr($cdata['email_signature_data']);exit;
		
		$table1='custom_field_master';
		$where1=array('module_id'=>'1');
		$data['tablefield_data']=$this->bomb_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		
		$match = array();
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		
		$data['sortfield'] = 'cm.first_name';
		$data['sortby'] = 'asc';
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		
		$data['main_content'] = "admin/".$this->viewName."/bomb_add";
        $this->load->view('admin/include/template', $data);
    }
    /*
		@Description: Function for Insert New Email campaign data
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign which is inserted into DB
		@Output: - List of email campaign with new inserted records
		@Date: 06-08-2014
    */
	
    public function insert_data()
    {
		//pr($_POST);exit;
		$submit = $this->input->post('submitbtn');
		if($this->input->post('submitbtn1') || $this->input->post('submitbtn'))
		{
			$data['template_name_id'] = $this->input->post('template_name');
			$data['template_category_id'] = $this->input->post('slt_category');
			$data['template_subcategory_id'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_signature'] = $this->input->post('email_signature');
			$data['email_send_type'] = $this->input->post('chk_is_lead');
			/*$data['video_id'] = $this->input->post('video_id');
			$data['thumb_url'] = $this->input->post('thumb_url');
			$data['video_title'] = $this->input->post('video_title');*/
			$send_type = $this->input->post('chk_is_lead');
			
			if($this->input->post('submitbtn'))
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';
			
			if($data['email_send_type'] == 2)
			{
				$data['email_send_date'] = $this->input->post('send_date');
				$data['email_send_time'] = date('H:i:s',strtotime($this->input->post('send_time')));
				$data['is_draft'] = '0';
			}
			
			if(empty($data['email_send_type']) && $this->input->post('submitbtn'))
				$data['email_send_type'] = 1;
			//echo $data['is_draft'];exit;;
				
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['email_type'] = 'Campaign';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			//$data['status'] = '1';
			if($this->input->post('submitbtn'))
			{
				$data['is_sent_to_all'] = '1';
				$data['total_sent'] = 0;
			}
			
			//Add bimb emails
			$data['email_blast_type'] = 1;
			
			$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			
			$email_campaign_id = $this->obj->insert_record($data);
			//$data['email_trans_id'] = $this->input->post('email_trans_id');
			/*$idata['email_campaign_id'] = $email_campaign_id;
			$idata['attachment_name'] = $file_name;
			$this->obj->insert_email_campaign_attachments($idata);*/
			
			/*if(!empty($_FILES['file_attachment']['name'][0]))
            {
				$name_array = array();
				$count = count($_FILES['file_attachment']['size']);
				foreach($_FILES as $key=>$value)
				for($s=0; $s<=$count-1; $s++) {
					$_FILES['file_attachment']['name']=$value['name'][$s];
					$_FILES['file_attachment']['type']    = $value['type'][$s];
					$_FILES['file_attachment']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['file_attachment']['error']       = $value['error'][$s];
					$_FILES['file_attachment']['size']    = $value['size'][$s];  
					$config['upload_path'] = 'uploads/attachment_file/';
					$config['allowed_types'] = '*';
					$this->load->library('upload', $config);
					if($this->upload->do_upload('file_attachment'))
					{
						$datac = $this->upload->data();
						$idata['email_campaign_id'] = $email_campaign_id;
						$idata['attachment_name'] = $datac['file_name'];
						$this->obj->insert_email_campaign_attachments($idata);
					}
					else
					{
						echo $this->upload->display_errors();
						exit;
					}
				}
			}*/
			/*$f = $this->input->post('fileName');
			if(!empty($f))
				$files = explode(",",$f);
				
			if(!empty($files))
			{
				for($i=0;$i<count($files);$i++)
				{
					$bgImgPath = $this->config->item('attachment_basepath_file');
					//$random = substr(md5(rand()),0,7);
					$bgTempPath = $this->config->item('attachment_temp');
					//$file_name =  pathinfo($bgTempPath.$files[$i]);
					//$file_name = $random.".".$file_name['extension'];
					$this->imageupload_model->copyfile($bgImgPath,$files[$i],$files[$i]);
					$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
					 
					if(file_exists($bgTempPath.$files[$i]))
					{
						$idata['email_campaign_id'] = $email_campaign_id;
						$idata['attachment_name'] = $files[$i];
						$this->obj->insert_email_campaign_attachments($idata);
						@unlink($bgTempPath.$files[$i]);
					}
					
				}
			}*/
			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = array();
			$contact_id = array();
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$explode = explode('-',$contact[$i]);
					if(!empty($explode[1]))
						$contact_id[$j] = $explode[1];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			//pr($_POST);
			//pr($contact_id);exit;
			$cdata['email_campaign_id'] = $email_campaign_id;
			
			/*$cdata['recepient_cc'] = $this->input->post('email_cc');
			$cdata['recepient_bcc'] = $this->input->post('email_bcc');*/
			if($this->input->post('submitbtn'))
				$cdata['sent_date'] = date('Y-m-d H:i:s');
			$j=0;
			if((!empty($contact_id) && $this->input->post('submitbtn1')) || ($this->input->post('submitbtn') && $data['email_send_type'] != 1))
			{
				
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			if($this->input->post('submitbtn') && $data['email_send_type'] != 2)
			{
				/*$from_cc = '';
				$from_bcc = '';
				//$from = "nishit.modi@tops-int.com";
				if($this->input->post('email_cc'))
				{
					$email_cc_data = explode(",",$this->input->post('email_cc'));
					$j=0;
					$k=0;
					$email_data_cc = array();
					$contact_type_cc = '';
					for($i=0;$i<count($email_cc_data);$i++)
					{
						if(!stristr($email_cc_data[$i],'CT-'))
						{
							$explode = explode('-',$email_cc_data[$i]);
							if(!empty($explode[1]))
								$email_data_cc[$j] = $explode[1];
							$j++;
						}
						else
						{
							$contact_type_cc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
						
					}
					if(!empty($email_data_cc))
					{
						$table = "contact_master as cm";
						$fields = array('cm.id,cet.id as email_trans_id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
						$join_tables = array(
											'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
										);
						$group_by = 'cet.id';
						$where_in = array('cet.id'=>$email_data_cc);
						$contact_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
						if(!empty($contact_data) > 0){
							foreach($contact_data as $row)
								$from_cc .= $row['email_address'].",";
						}
					}
					if(!empty($contact_type_cc))
					{
						for($i=0;$i<count($contact_type_cc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.contact_id,cet.email_address');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
												 'contact_emails_trans cet'=>'cet.contact_id = cm.id'
												 );
							
							$match = array('cct.contact_type_id'=>$contact_type_cc[$i],'cet.is_default'=>"'1'",'cm.is_subscribe'=>"'0'");
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$from_cc .= $row['email_address'].",";
								}
							}
						}
					}
					$from_cc = trim($from_cc,",");
					//$headers .= "CC:".$from_cc."\r\n";
				}
				if($this->input->post('email_bcc'))
				{
					$email_bcc_data = explode(",",$this->input->post('email_bcc'));
					$j=0;
					$k=0;
					$email_data_bcc = array();
					$contact_type_bcc = '';
					for($i=0;$i<count($email_bcc_data);$i++)
					{
						if(!stristr($email_bcc_data[$i],'CT-'))
						{
							$explode = explode('-',$email_bcc_data[$i]);
							if(!empty($explode[1]))
								$email_data_bcc[$j] = $explode[1];
							$j++;
						}
						else
						{
							$contact_type_bcc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
					}
					if(!empty($email_data_bcc))
					{
						$table = "contact_master as cm";
						$fields = array('cm.id,cet.id as email_trans_id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
						$join_tables = array(
											'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
										);
						$group_by = 'cet.id';
						$where_in = array('cet.id'=>$email_data_bcc);
						$contact_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
						if(!empty($contact_data) > 0){
							foreach($contact_data as $row)
								$from_bcc .= $row['email_address'].",";
						}
					}
					if(!empty($contact_type_bcc))
					{
						for($i=0;$i<count($contact_type_bcc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.contact_id,cet.email_address');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
												 'contact_emails_trans cet'=>'cet.contact_id = cm.id'
												 );
							
							$match = array('cct.contact_type_id'=>$contact_type_bcc[$i],'cet.is_default'=>"'1'",'cm.is_subscribe'=>"'0'");
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$from_bcc .= $row['email_address'].",";
								}
							}
						}
					}
					$from_bcc = trim($from_bcc,","); 
					//$headers .= "BCC:".$from_bcc."\r\n";
				}
				$data['from_cc'] = $from_cc;
				$data['from_bcc'] = $from_bcc;*/
				//$data['campaign_id'] = !empty($email_campaign_id)?$email_campaign_id:'0';
				//$headers .= 'MIME-Version: 1.0'."\r\n";
				$field = array('id,email_id,admin_name');
				$match = array('id'=>$this->admin_session['id']);
        		$fromdata = $this->admin_model->get_user($field, $match,'','=');
				$data['from_email'] = !empty($fromdata[0]['email_id'])?$fromdata[0]['email_id']:'';
				$data['from_name'] = !empty($fromdata[0]['admin_name'])?$fromdata[0]['admin_name']:'';
				
				if(!empty($data['email_signature']))
				{
					$match = array('id'=>$data['email_signature']);
					$email_signature = $this->email_signature_model->select_records('',$match,'','=');
				}
				
				$message = !empty($data['email_message'])?$data['email_message']:'';
				if(!empty($email_signature[0]['full_signature']))
					$message .= "<br>".$email_signature[0]['full_signature'];
					
				if($data['is_unsubscribe'] == 1){
					//$link = base_url()."unsubscribe/unsubscribe_link";
					$message .= '{(my_unsubscribe_link)}';//'<a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
				}
				if(!empty($contact_id))		
					$email_send_data = $this->send_mail($contact_id,$message,$data,$cdata,$email_signature);
			}
			
			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = '';
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table ="contact_contacttype_trans as cct";
					$fields = array('cm.*,cet.id as email_trans_id');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
										 'contact_emails_trans cet'=>'cet.contact_id = cm.id',
										 );
					$group_by = 'cm.id';
					$where = "cm.is_subscribe = '0' AND cet.is_default = '1' AND cct.contact_type_id = ".$contact_type[$i];
					//$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['email_trans_id'];
							$j++;
							
						}
						//pr($data);exit;
						if($this->input->post('submitbtn') && $data['email_send_type'] == 1)
						{
							//echo "hi"; exit;
							$email_send_data = $this->send_mail($contact_id1,$message,$data,$cdata,$email_signature);
							//$send_mail_count = $email_send_data['send_mail_count'];
								
							
						}
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
		}
		elseif($this->input->post('submitbtn2'))
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->bomb_library_model->select_records('',$match,'','=');
				if(!empty($result[0]['template_name']))
					$data['template_name'] = $result[0]['template_name'];
				else
					$data['template_name'] = $this->input->post('txt_template_subject');
			}
			else
				$data['template_name'] = $this->input->post('txt_template_subject');

			
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$this->bomb_library_model->insert_record($data);
		
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $bomb_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('bomb_sortsearchpage_data', $bomb_sortsearchpage_data);
        redirect('admin/'.$this->view);
		
    }
 	
	/*
		@Description: Function for insert email campaign trans
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign,contact details
		@Output: - 
		@Date: 06-08-2014
   */
   
	public function insert_data_trans($contact_id='',$data,$cdata='')
	{
		//$from_data = $this->obj->in_query_data($contact_id);
		//$from_data = $this->obj->in_query_data($contact_id);
		if(!empty($contact_id))
		{
			$table = "contact_master as cm";
			$fields = array('cm.id,cet.id as email_trans_id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
			$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
							);
			$group_by = 'cet.id';
			$where_in = array('cet.id'=>$contact_id);
			$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
		}
		//pr($from_data);exit;
		if(count($from_data) > 0)
		{
			foreach($from_data as $row)
			{
				$cdata['contact_id'] = $row['id'];
				$cdata['email_trans_id'] = $row['email_trans_id'];
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
						if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
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
									'Day Of Week'=>date("w",time()),
									'Agent Name'=>$agent_name,
									'Contact First Name'=>$row['first_name'],
									'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
									'Contact Last Name'=>$row['last_name'],
									'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
									'Contact Company Name'=>$row['company_name']
									);
				
				$content = $data['email_message'];
				$title = $data['template_subject'];
				//pr($emaildata);
				$cdata['template_subject'] = $title;
				$cdata['email_message'] = $content;
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
					
					$cdata['template_subject'] = $finaltitle;
					$finlaOutput = $output;
					$cdata['email_message'] = $finlaOutput;
				}
				$this->obj->insert_email_campaign_recepient_trans($cdata);
			}
		}
	}
	
	/*
		@Description: Function for send email
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign,contact details and send email count
		@Output: - Send email and insert the data in email campaign trans
		@Date: 06-08-2014
   */
	
 	public function send_mail($contact_id='',$headers='',$data='',$cdata='',$email_signature='')
	{
		$db_session = $this->session->userdata('db_session');
		if(!empty($db_session))
		{
			$db_name = $db_session['db_name'];
			$db_name1 = urlencode(base64_encode($db_name));
		}
		else
		{
			$db_obj = $this->db;
			$db_name = $db_obj->database;
			$db_name1 = urlencode(base64_encode($db_name));
		}
		//exit;
		//$admin_id = $this->admin_session['admin_id'];
		$field = array('bombbomb_username,bombbomb_password');
        $match = array('user_type'=>'2');
        $udata = $this->admin_model->get_user($field,$match,'','=');
		
		$fields = '';
		$join_tables = '';
		$where_in = '';
		$send_mail_count = 0;
		if(!empty($contact_id) && !empty($udata[0]['bombbomb_username']) && !empty($udata[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($udata[0]['bombbomb_password']);
			$data['username'] = $udata[0]['bombbomb_username'];
			$data['password'] = $password;
			
			//$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".urlencode($data['username'])."&pw=".urlencode($data['password']));
                        $url = "https://app.bombbomb.com/app/api/api.php?method=IsValidLogin";
                        $IsValidLogin = $this->admin_model->bombbombapi_curl($url,$data);
			if(!empty($IsValidLogin))
				$connection = json_decode($IsValidLogin);
			else
				$connection->status = 'failure';

			if($connection->status == 'failure')
			{
				$edata['type'] = 'Bomb Bomb';
				$edata['description'] = 'Authentication failed.';
				$edata['created_date'] =date('Y-m-d h:i:s');
				$edata['status'] = 1;
				$edata['created_by'] = $this->admin_session['id'];
				$this->dashboard_model->insert_record1($edata);
			}
			
			$message = '';
			$table ="contact_master as cm";   
			$fields = array('cm.*,cet.email_address,cet.id as email_trans_id');
			$join_tables = array('contact_emails_trans cet'=>'cet.contact_id = cm.id');
			// AND (cet.is_default = '1' OR cet.email_type = 1)
			$group_by = 'cet.id';
			$wherestring = "cm.is_subscribe = '0'";
			$where_in = array('cet.id'=>$contact_id);
			$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			if(count($datalist) > 0)
			{
				/*if(empty($data['video_id']) && !empty($data['template_name_id']))
				{
					$match = array('id'=>$data['template_name_id']);
					$result = $this->bomb_library_model->select_records('',$match,'','=');
					if(!empty($result[0]['video_id']))
						$data['video_id'] = $result[0]['video_id'];
					else
						$data['video_id'] = '';
				}*/
				
				foreach($datalist as $row)
				{
					$cdata['contact_id'] = $row['id'];
					$cdata['email_trans_id'] = $row['email_trans_id'];
					
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
							if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
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
					$content1 = $data['email_message']; 
					$content = $headers;
					$title = $data['template_subject'];
					$cdata['template_subject'] = $title;
					$output = $content;
					$cdata['email_message'] = $content1;
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
						$output1 = strtr($content1, $map);
						
						$cdata['template_subject'] = $finaltitle;
						$finlaOutput = $output1;
						$cdata['email_message'] = $finlaOutput;
						$mail_data = $output;
					}
					$subject = $cdata['template_subject'];
					$message = '<div align="left">'.$output;
					
					$to = $row['email_address'];
					$cdata['email_address'] = $to;
					if(!empty($row['email_address']) && !empty($connection->status) && $connection->status == 'success')
					{
						if($data['is_unsubscribe'] == '1'){
							$email_id = urlencode(base64_encode($to));
							$link = base_url()."unsubscribe/unsubscribe_link/".$db_name1.'--'.$email_id;
							$message1 = '<br/><br/><a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
							$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
						}
						$message .= '</div>';
						//echo $message;exit;
						//pr($data);exit;
						$response = $this->obj->BombBombMailSend($to,$subject,$message,$data);
						if(!empty($response))
							$response = json_decode($response);
					}

					
					if(!empty($response->status) && $response->status == 'success')
					{
						$send_mail_count++;
						$cdata['is_send'] = '1';
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$cdata['info'] = $response->info->email_id;
						$contact_conversation['contact_id'] = $row['id'];
						$contact_conversation['log_type'] = 6;
						$contact_conversation['campaign_id'] = !empty($cdata['email_campaign_id'])?$cdata['email_campaign_id']:'';
						$contact_conversation['email_camp_template_id'] = !empty($data['template_name_id'])?$data['template_name_id']:'';
						if(!empty($data['template_name_id']))
						{
							$match = array('id'=>$data['template_name_id']);
							$template_data = $this->bomb_library_model->select_records('',$match,'','=');
							if(count($template_data) > 0)
								$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
						}
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
					}
					else
						$send_fails = 1;
					unset($response);
					$this->obj->insert_email_campaign_recepient_trans($cdata);
				}
			}
			if(!empty($send_fails))
			{
				$icdata['is_sent_to_all'] = '0';
				$icdata['total_sent'] = $send_mail_count;
				$icdata['id'] = $cdata['email_campaign_id'];
				$this->obj->update_record($icdata);
			}
		}
	}
 
    /*
		@Description: Get Details of Edit email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Id of email campaign whose details want to change
		@Output: - Details of email campaign which id is selected for update
		@Date: 06-08-2014
    */
 
    public function edit_record()
    {
		//check user right
		check_rights('bomb_bomb_email_blast_edit');
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		if(!empty($connection[0]['bombbomb_username']) && !empty($connection[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($connection[0]['bombbomb_password']);
			$cdata['username'] = $connection[0]['bombbomb_username'];
			$cdata['password'] = $password;
		}
		
     	$id = $this->uri->segment(4);
		$cdata['send_now'] = $this->uri->segment(5);
		
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		
		if(count($result) > 0)
		{
			if(($result[0]['is_draft'] == 0 && $result[0]['email_send_date'] == '0000-00-00') || ($result[0]['email_type'] == 'Intereaction_plan')) {
				redirect(base_url('admin/'.$this->view));
			}
		}
		else
			redirect(base_url('admin/'.$this->view));
		
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cet.id';
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		$cdata['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		/*$match = array('created_by'=>$this->admin_session['id']);
		$cdata['email_signature_data'] = $this->email_signature_model->select_records('',$match,'','=');*/
		$table = 'email_signature_master esm';
		$fields = array('esm.*');
		$join_tables = array('login_master lm' => 'lm.id = esm.created_by');
		$match = "user_type = '2' OR user_type = '5'";
		$cdata['email_signature_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$match);
		//pr($cdata['email_signature_data']);exit;
				
		$email_campaign_id = $result[0]['id'];
		$email = $this->obj->select_email_campaign_recepient_trans($email_campaign_id);
		
		$cdata['attachment'] = $this->obj->select_email_campaign_attachments($id);
		
		$i=0;
		$contact_type_to = '';
		$select_to = array();
		foreach($email as $row)
		{
			if(empty($row['contact_type']) && $row['contact_type'] == 0)
			{
				$select_to[] = $row['contact_id'].'-'.$row['email_trans_id'];
				$email_to[$i] = $row['email_trans_id'];
			}
			else
			{
				if(!in_array($row['contact_type'],$contact_type_to))
					$contact_type_to[$i] = $row['contact_type'];
			}
			if($i==0)
			{
				$email_cc = $row['recepient_cc'];
				$email_bcc = $row['recepient_bcc'];
			}
			$i++;
		}
		if(!empty($email_to))
		{
			//$cdata['select_to'] = implode(",",$email_to);
			$cdata['select_to'] = implode(",",$select_to);
			$table = "contact_master as cm";
			$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
			$group_by='cet.id';
			$where_in = array('cet.id'=>$email_to);
			//$where = array('cm.is_subscribe'=>"'0'"); 
			$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
			$cdata['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
		}
		
		//pr($cdata['email_to']);exit;
		/*if(!empty($contact_type_to))
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
		if(!empty($email_cc))
		{
			$email_cc_data = explode(",",$email_cc);
			$email_cc = '';
			$contact_type_cc = '';
			$select_cc = array();
			for($i=0;$i<count($email_cc_data);$i++)
			{
				if(stristr($email_cc_data[$i],'CT-'))
					$contact_type_cc[$i] = substr($email_cc_data[$i],3);
				else
				{
					$select_cc[] = $email_cc_data[$i];
					$explode = explode('-',$email_cc_data[$i]);
					if(!empty($explode[1]))
						$email_cc[$i] = $explode[1];
				}
			}
			if(!empty($email_cc))
			{
				//$cdata['select_cc'] = implode(",",$email_cc);
				$cdata['select_cc'] = implode(",",$select_cc);
				$table = "contact_master as cm";
				$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
				$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
							);
				$group_by='cet.id';
				$where_in = array('cet.id'=>$email_cc);
				//$where = array('cm.is_subscribe'=>"'0'"); 
				$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
				$cdata['email_cc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
			}
			if(!empty($contact_type_cc))
			{
				$cdata['contact_type_cc'] = $this->contact_type_master_model->contact_type_in_query($contact_type_cc);	
				$i=0;
				foreach($cdata['contact_type_cc'] as $row)
				{
					$contact_type_cc_selected[$i] = $row['id'];
					$i++;
				}
				$cdata['contact_type_cc_selected'] = implode(",",$contact_type_cc_selected);
			}
		}
		if(!empty($email_bcc))
		{
			$email_bcc_data = explode(",",$email_bcc);
			$email_bcc = '';
			$contact_type_bcc = '';
			$select_bcc = array();
			for($i=0;$i<count($email_bcc_data);$i++)
			{
				if(stristr($email_bcc_data[$i],'CT-'))
					$contact_type_bcc[$i] = substr($email_bcc_data[$i],3);
				else
				{
					$select_bcc[] = $email_bcc_data[$i];
					$explode = explode('-',$email_bcc_data[$i]);
					if(!empty($explode[1]))
						$email_bcc[$i] = $explode[1];
				}
			}
			if(!empty($email_bcc))
			{
				//$cdata['select_bcc'] = implode(",",$email_bcc);
				$cdata['select_bcc'] = implode(",",$select_bcc);
				$table = "contact_master as cm";
				$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
				$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
							);
				$group_by='cet.id';
				$where_in = array('cet.id'=>$email_bcc);
				//$where = array('cm.is_subscribe'=>"'0'"); 
				$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
				$cdata['email_bcc'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
				//pr($cdata['email_bcc']);exit;
			}
			if(!empty($contact_type_bcc))
			{
				$cdata['contact_type_bcc'] = $this->contact_type_master_model->contact_type_in_query($contact_type_bcc);	
				$i=0;
				foreach($cdata['contact_type_bcc'] as $row)
				{
					$contact_type_bcc_selected[$i] = $row['id'];
					$i++;
				}
				$cdata['contact_type_bcc_selected'] = implode(",",$contact_type_bcc_selected);
			}
		}*/
		/////////////////////////////////////////////////////////////////////////////////////

		/*$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config);
		$cdata['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////
		$config1['per_page'] = '10';
		$config1['cur_page'] = '0';	
		$config1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_cc');	
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		
		$cdata['contact_list_cc'] = $this->contact_type_master_model->select_records('','','','','',$config1['per_page'],'','id','desc');
		$config1['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config1);
		$cdata['pagination_cc'] = $this->pagination->create_links();
		
		////////////////////////////////////////////////////////////////////////////////////
		$config_bcc['per_page'] = '10';	
		$config_bcc['cur_page'] = '0';	
		$config_bcc['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_bcc');
        $config_bcc['is_ajax_paging'] = TRUE; // default FALSE
        $config_bcc['paging_function'] = 'ajax_paging'; // Your jQuery paging
		
		$cdata['contact_list_bcc'] = $this->contact_type_master_model->select_records('','','','','',$config_bcc['per_page'],'','id','desc');
		$config_bcc['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config_bcc);	
		$cdata['pagination_bcc'] = $this->pagination->create_links();*/
		
		//////////////////////////////////// END ////////////////////////////////////////////////
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_to');
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$table='';$fields=array();$join_tables=array();$where=array();
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$where = "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		$cdata['contact_to'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'],'','cm.first_name','asc',$group_by,$where);
		
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$cdata['pagination_contact_to'] = $this->pagination->create_links();
		
		////////////////////////////////////////////////////////////////////////////////////
	
		/*$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_cc');
		$cdata['contact_cc'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'],'','cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$cdata['pagination_contact_cc'] = $this->pagination->create_links();
			
		///////////////////////////////////////////////////////////////////////////////////
		$config_to1['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_bcc');
		$cdata['contact_bcc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'],'','cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config_to1);
		
		$cdata['pagination_contact_bcc'] = $this->pagination->create_links();*/
		
		//////////////////////////////////////// END ////////////////////////////////////////////
		
		$table1='custom_field_master';
		$where1=array('module_id'=>'1');
		$cdata['tablefield_data']=$this->bomb_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
                
                $match=array();
		$cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		// BombBomb Connection
		
		/*$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$result = $this->admin_model->get_user($fields,$match,'','=');*/
		
		$cdata['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }

    /*
		@Description: Function for Update email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Update details of email campaign
		@Output: - List with updated email campaign details
		@Date: 06-08-2014
    */
   
    public function update_data()
    {
		$id = $this->input->post('id');
		$submit = $this->input->post('submitbtn');
		if($this->input->post('submitbtn1') || $this->input->post('submitbtn'))
		{
			$result = $this->obj->delete_email_campaign_recepient_trans($id);
			$data['id'] = $id;	
			$data['template_name_id'] = $this->input->post('template_name');
			$data['template_category_id'] = $this->input->post('slt_category');
			$data['template_subcategory_id'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_signature'] = $this->input->post('email_signature');
			$data['email_send_type'] = $this->input->post('chk_is_lead');
			/*$data['video_id'] = $this->input->post('video_id');
			$data['thumb_url'] = $this->input->post('thumb_url');
			$data['video_title'] = $this->input->post('video_title');*/
			$send_type = $this->input->post('chk_is_lead');
			
			if($this->input->post('submitbtn'))
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';
				
			if($data['email_send_type'] == 2)
			{
				$data['email_send_date'] = $this->input->post('send_date');
				$data['email_send_time'] = date('H:i:s',strtotime($this->input->post('send_time')));
				$data['is_draft'] = '0';
			}
			else
			{
				$data['email_send_date'] = '';
				$data['email_send_time'] = '';
			}
			
			if(empty($data['email_send_type']) && $this->input->post('submitbtn'))
			{
				$data['email_send_type'] = 1;
			}
				
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['email_type'] = 'Campaign';
			$data['modified_by'] = $this->admin_session['id'];
			$data['modified_date'] = date('Y-m-d H:i:s');		
			if($this->input->post('submitbtn'))
			{
				$data['is_sent_to_all'] = '1';
				$data['total_sent'] = 0;
			}
			
			$this->obj->update_record($data);
			
			/*if(!empty($_FILES['file_attachment']['name'][0]))
            {
				$name_array = array();
				$count = count($_FILES['file_attachment']['size']);
				foreach($_FILES as $key=>$value)
				for($s=0; $s<=$count-1; $s++) {
					$_FILES['file_attachment']['name']=$value['name'][$s];
					$_FILES['file_attachment']['type']    = $value['type'][$s];
					$_FILES['file_attachment']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['file_attachment']['error']       = $value['error'][$s];
					$_FILES['file_attachment']['size']    = $value['size'][$s];  
					$config['upload_path'] = 'uploads/attachment_file/';
					$config['allowed_types'] = '*';
					//$random = substr(md5(rand()),0,7);
					//$config['file_name'] = $random;
					//$config['encrypt_name']  = TRUE;
					$this->load->library('upload', $config);
					//$this->upload->do_upload('file_attachment');
					if($this->upload->do_upload('file_attachment'))
					{
						$datac = $this->upload->data();
						$idata['email_campaign_id'] = $id;
						$idata['attachment_name'] = $datac['file_name'];
						$this->obj->insert_email_campaign_attachments($idata);
					}
					else
					{
						echo $this->upload->display_errors();
						exit;
					}
				}
			}*/
			
			$cdata['email_campaign_id'] = $id;
			$cdata['template_subject'] = $data['template_subject'];
			$cdata['email_message'] = $data['email_message'];
			
			if($this->input->post('submitbtn') && $data['email_send_type'] != 2)
				$cdata['sent_date'] = date('Y-m-d H:i:s');

			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = array();
			$contact_id = array();
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$explode = explode('-',$contact[$i]);
					if(!empty($explode[1]))
						$contact_id[$j] = $explode[1];
					//$contact_id[$j] = $contact[$i];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			
			/*$cdata['recepient_cc'] = $this->input->post('email_cc');
			$cdata['recepient_bcc'] = $this->input->post('email_bcc');*/
			
			////////////////////////////////////////////////////////////////////////////////////////////////
			if((!empty($contact_id) && $this->input->post('submitbtn1')) || ($this->input->post('submitbtn') && $data['email_send_type'] != 1))
			{
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			
			////////////////////////////////////////////////////////////////////////////////////////////////
			
			if($this->input->post('submitbtn') && !empty($id) && $data['email_send_type'] != 2)
			{
				/*$from_cc = '';
				$from_bcc = '';
				if($this->input->post('email_cc'))
				{
					$email_cc_data = explode(",",$this->input->post('email_cc'));
					$j=0;
					$k=0;
					$email_data_cc = array();
					$contact_type_cc = '';
					for($i=0;$i<count($email_cc_data);$i++)
					{
						if(!stristr($email_cc_data[$i],'CT-'))
						{
							$explode = explode('-',$email_cc_data[$i]);
							if(!empty($explode[1]))
								$email_data_cc[$j] = $explode[1];
							$j++;
						}
						else
						{
							$contact_type_cc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
						
					}
					if(!empty($email_data_cc))
					{
						$table = "contact_master as cm";
						$fields = array('cm.id,cet.id as email_trans_id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
						$join_tables = array(
											'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
										);
						$group_by = 'cet.id';
						$where_in = array('cet.id'=>$email_data_cc);
						$contact_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
						if(!empty($contact_data) > 0){
							foreach($contact_data as $row)
								$from_cc .= $row['email_address'].",";
						}
					}
					if(!empty($contact_type_cc))
					{
						for($i=0;$i<count($contact_type_cc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.contact_id,cet.email_address');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
												 'contact_emails_trans cet'=>'cet.contact_id = cm.id'
												 );
							
							$match = array('cct.contact_type_id'=>$contact_type_cc[$i],'cet.is_default'=>"'1'",'cm.is_subscribe'=>"'0'");
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$from_cc .= $row['email_address'].",";
								}
							}
						}
					}
					$from_cc = trim($from_cc,",");
					//$headers .= "CC:".$from_cc."\r\n";
				}
				
				if($this->input->post('email_bcc'))
				{
					$email_bcc_data = explode(",",$this->input->post('email_bcc'));
					$j=0;
					$k=0;
					$email_data_bcc = array();
					$contact_type_bcc = '';
					for($i=0;$i<count($email_bcc_data);$i++)
					{
						if(!stristr($email_bcc_data[$i],'CT-'))
						{
							$explode = explode('-',$email_bcc_data[$i]);
							if(!empty($explode[1]))
								$email_data_bcc[$j] = $explode[1];
							$j++;
						}
						else
						{
							$contact_type_bcc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
					}
					if(!empty($email_data_bcc))
					{
						$table = "contact_master as cm";
						$fields = array('cm.id,cet.id as email_trans_id','cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
						$join_tables = array(
											'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
										);
						$group_by = 'cet.id';
						$where_in = array('cet.id'=>$email_data_bcc);
						$contact_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
						if(!empty($contact_data) > 0){
							foreach($contact_data as $row)
								$from_bcc .= $row['email_address'].",";
						}
					}
					if(!empty($contact_type_bcc))
					{
						for($i=0;$i<count($contact_type_bcc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.contact_id,cet.email_address');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
												 'contact_emails_trans cet'=>'cet.contact_id = cm.id'
												 );
							
							$match = array('cct.contact_type_id'=>$contact_type_bcc[$i],'cet.is_default'=>"'1'",'cm.is_subscribe'=>"'0'");
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$from_bcc .= $row['email_address'].",";
								}
							}
						}
					}
					$from_bcc = trim($from_bcc,","); 
					//$headers .= "BCC:".$from_bcc."\r\n";
				}*/
				//$headers .= 'MIME-Version: 1.0'."\r\n";
        		//$fromdata = $this->admin_model->get_user($field, $match,'','=');
				$table = "email_campaign_master as ecm";
				$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type,lm.email_id');
				$join_tables = array('login_master lm'=>'lm.id = ecm.created_by',
									 'user_master um'=>'um.id = lm.user_id',
									 );
				$group_by = 'ecm.id';
				$wherestring = "ecm.id = ".$id." AND ecm.email_blast_type = 1";
				$fromdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
				$from = '';
				$from_email = '';
				//pr($fromdata);exit;
				if(!empty($fromdata))
				{
					if(!empty($fromdata[0]['user_type']) && ($fromdata[0]['user_type'] == '2' || $fromdata[0]['user_type'] == '5'))
						$from .= $fromdata[0]['admin_name'];
					else
						$from .= trim($fromdata[0]['first_name']).' '.trim($fromdata[0]['middle_name']).' '.trim($fromdata[0]['last_name']);
					if(!empty($fromdata[0]['email_id']))
						$from_email .= $fromdata[0]['email_id'];
				}
				//$headers .= "From: ".$from." <".$from_email.">\r\n";
				
				
				//$headers .= "From: ".$from." <".$from_email.">";
				//exit;
				if(!empty($data['email_signature']))
				{
					$match = array('id'=>$data['email_signature']);
					$email_signature = $this->email_signature_model->select_records('',$match,'','=');
				}
				$message = !empty($data['email_message'])?$data['email_message']:'';
				if(!empty($email_signature[0]['full_signature']))
					$message .= "<br>".$email_signature[0]['full_signature'];
				
				if($data['is_unsubscribe'] == 1){
					$message .= '{(my_unsubscribe_link)}';
				}
				$data['from_email'] = !empty($from_email)?$from_email:'';
				$data['from_name'] = !empty($from)?$from:'';
				//$data['from_cc'] = $from_cc;
				//$data['from_bcc'] = $from_bcc;
				//$data['campaign_id'] = !empty($id)?$id:'0';
				if(!empty($contact_id))
					$email_send_data = $this->send_mail($contact_id,$message,$data,$cdata,$email_signature);
			}	

			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = '';	
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*,cet.id as email_trans_id');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id',
										 'contact_emails_trans cet'=>'cet.contact_id = cm.id'
										 );
					$group_by = 'cm.id';
					$where = "cm.is_subscribe = '0' AND cet.is_default = '1' AND cct.contact_type_id = ".$contact_type[$i];
					//$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['email_trans_id'];
							$j++;
							
						}
						if($this->input->post('submitbtn') && $data['email_send_type'] == 1)
							$email_send_data = $this->send_mail($contact_id1,$message,$data,$cdata,'',$email_signature);
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
		}
		elseif($this->input->post('submitbtn2'))
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->bomb_library_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			else
				$data['template_name'] = $this->input->post('txt_template_subject');
			
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->bomb_library_model->insert_record($data);
		}
		$msg = $this->lang->line('common_edit_success_msg');
                $newdata = array('msg'  => $msg);
		//$pagingid = $this->obj->getpagingid($id);
                $this->session->set_userdata('message_session', $newdata);
                $searchsort_session = $this->session->userdata('bomb_sortsearchpage_data');
                $pagingid = $searchsort_session['uri_segment'];
                redirect(base_url('admin/'.$this->view.'/'.$pagingid));
		
    }
	
  	/*
		@Description: Function for Delete email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which email campaign record want to delete
		@Output: - New email campaign list after record is deleted.
		@Date: 06-08-2014
    */

    function delete_record()
    {
		//check user right
		check_rights('bomb_bomb_email_blast_delete');
		
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$this->obj->delete_email_campaign_recepient_trans($id);
		$this->obj->delete_email_campaign_attachments($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->view);
    }
	
	/*
		@Description: Function for email campaign delete
		@Author: Sanjay Chabhadiya
		@Input: - Delete all id of email campaign record want to delete
		@Output: - Delete selected all record
		@Date: 06-08-2014
    */
	
	public function ajax_delete_all()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$id=$this->input->post('single_remove_id'); 
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->obj->delete_email_campaign_recepient_trans($id);
			$this->obj->delete_email_campaign_attachments($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			$id = $array_data[0];
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$this->obj->delete_email_campaign_recepient_trans($array_data[$i]);
				$this->obj->delete_email_campaign_attachments($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		//$pagingid = $this->obj->getpagingid($id);
		$searchsort_session = $this->session->userdata('bomb_sortsearchpage_data');
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
		@Description: Function for Unpublish Task Profile By Admin
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
		redirect('admin/'.$this->view);
    }
	
	/*
		@Description: Function for publish Task Profile By Admin
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
		redirect('admin/'.$this->view);
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
		//$subcategory_id=$this->input->post('loadsubcategoryId');
		//&& !empty($subcategory_id)
		if(!empty($category_id))
		{
			//$match = array("template_category"=>$category_id,"template_subcategory"=>$subcategory_id);
			$match = array("template_category"=>$category_id);
        	$cdata['templatedata'] = $this->bomb_library_model->select_records('',$match,'','=','','','','id','desc');
			echo json_encode($cdata['templatedata']);
		}
	}
	
	/*
		@Description: Function for file attachment
		@Author: Sanjay Chabhadiya
		@Input: - File List
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function mailAttachmentHeader($attachment,$message)
	{
		$mime_boundary = md5(time());
	
		$xMessage = "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"\r\n\r\n";
		
		//$xMessage .= "--".$mime_boundary."\r\n\r\n";
		
		//$xMessage .= "This is a multi-part message in MIME format.\r\n";
		$xMessage .= "--".$mime_boundary."\r\n";
		
		//$xMessage .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		
		//$xMessage .= "Content-Transfer-Encoding: 7bit\r\n";
		
		//$xMessage .= $message."\r\n\r\n";
		
		$xMessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$xMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$xMessage .= $message."\r\n\r\n";
		if(!empty($attachment))
		{
			foreach($attachment as $file)
			{
				$xMessage .= "--".$mime_boundary."\r\n";
				
				$xMessage .= "Content-type:text/html; name=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$xMessage .= "Content-Transfer-Encoding: base64\r\n";
				
				$xMessage .= "Content-Disposition: attachment; filename=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$content = file_get_contents("uploads/attachment_file/".$file['attachment_name']);
				
				$xMessage.= chunk_split(base64_encode($content));
				
				$xMessage .= "\r\n\r\n";
			
			}
		}
		$xMessage .= "--".$mime_boundary."--\r\n\r\n";
		
		return $xMessage;
	
	}
	
	/*
		@Description: Function for sent Email list from email campaign id wise
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - Sent email List 
		@Date: 06-08-2014
   	*/
	
	public function sent_email()
	{
		//check user right
		check_rights('bomb_bomb_email_blast');
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;
		$data['campaign_id'] = $id ;
		
		if(count($result) > 0)
		{
			$email_campaign_id = $result[0]['id'];
			$total_cnt = $this->obj->select_email_campaign_recepient_trans($email_campaign_id,'','','1');
			$match = array('is_send'=>'1');
			$sent_cnt = $this->obj->select_email_campaign_recepient_trans($email_campaign_id,$match,'','1');
			$data['not_send'] = $total_cnt - $sent_cnt;
			
		}	
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('sent_bomb_sortsearchpage_data');
                }
                $data['sortfield']		= 'ect.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('sent_bomb_sortsearchpage_data');
		
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
							$sortfield = $data['sortfield'];
                        	$sortby = $data['sortby'];
                        }
                    } else {
                        $sortfield = 'ect.id';
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
                           /* $data['searchtext'] = $searchsort_session['searchtext'];
                            $searchtext =  $data['searchtext'];*/
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
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/sent_email/'.$id);
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
		$fields = array('cm.*,ect.id as ID,ect.is_send,ect.template_subject,ect.sent_date,ect.is_email_exist,ect.info');
		$join_tables = array('email_campaign_recepient_trans as ect'=>'ect.contact_id = cm.id');
		$wherestring = "ect.email_campaign_id = ".$email_campaign_id; //." AND is_send = '1'";
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
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
                $sent_bomb_sortsearchpage_data = array(
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('sent_bomb_sortsearchpage_data', $sent_bomb_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_sent_email',$data);
		}
		else
		{	
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/sent_email_list";
			$this->load->view('admin/include/template',$data);
		}
		
	}
	
	/*
		@Description: Function for sent email view data
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id and contact id
		@Output: - Sent email view data 
		@Date: 06-08-2014
   	*/
	
	public function view_data()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$data['campaign_id'] = $this->uri->segment(4);
		$data['id'] = $this->uri->segment(5);
		$pageid = $this->uri->segment(5);
		
		$table = "email_campaign_recepient_trans ecr";
		$fields = array("ecm.email_message,ecm.template_subject,cm.first_name,cm.last_name,mml1.category as category,etm.template_name,ecr.*,esm.full_signature,ecr.email_address,ecr.info");
		$join_tables = array('email_campaign_master ecm'=>'ecm.id = ecr.email_campaign_id',
							'email_signature_master esm'=>'esm.id = ecm.email_signature',
							'contact_master cm'=>'ecr.contact_id = cm.id',
							'marketing_master_lib__category_master mml1'=>'mml1.id = ecm.template_category_id',
							'bomb_template_master etm'=>'etm.id = ecm.template_name_id',
							//'(select * from contact_emails_trans WHERE is_default = "1") cet'=>'cet.id = ecr.email_trans_id'
							//'user_emails_trans etm'=>'etm.id = ecm.template_name_id'
							);
		$group_by = 'ecr.id';
		$wherestring = 'ecr.email_campaign_id = '.$data['campaign_id'].' AND ecr.id = '.$data['id'].' AND ecm.email_blast_type = 1';
		$cdata['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		//pr($cdata['datalist']);exit;
		/*$email_cc = '';
		if(count($cdata['datalist']) > 0)
		{
			$email_cc = $cdata['datalist'][0]['recepient_cc'];
			$email_bcc = $cdata['datalist'][0]['recepient_bcc'];
		}
		if(!empty($email_cc))
		{
			$datalist = '';
			$email_cc_data = explode(",",$email_cc);
			$email_cc = '';
			$contact_type_cc = '';
			for($i=0;$i<count($email_cc_data);$i++)
			{
				if(stristr($email_cc_data[$i],'CT-'))
					$contact_type_cc[$i] = substr($email_cc_data[$i],3);
				else
				{
					$explode = explode('-',$email_cc_data[$i]);
					if(!empty($explode[1]))
						$datalist[$i] = $explode[1];
					//$datalist[$i] = $email_cc_data[$i];
				}
			}
			//pr($datalist);exit;
			if(!empty($datalist))
			{
				//$result = $this->obj->contact_in_query($datalist);
				$table = "contact_master as cm";
				$fields = array('cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
				$join_tables = array(
									'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
								);
				$group_by = 'cet.id';
				$where_in = array('cet.id'=>$datalist);
				$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
				//pr($from_data);exit;
				if(!empty($from_data))
				{
					foreach($from_data as $row)
						$email_cc .= $row['first_name']." ".$row['last_name']."(".$row['email_address']."); ";
				}
			}
			if(!empty($contact_type_cc))
			{
				for($i=0;$i<count($contact_type_cc);$i++)
				{
					$contact_type_data = '';
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.id,cm.first_name,cm.last_name,cet.email_address');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','contact_emails_trans cet'=>'cet.contact_id = cm.id');
					$group_by = 'cet.contact_id';
					$match = array('cct.contact_type_id'=>$contact_type_cc[$i],'cet.is_default'=>'1');
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','','','','',$group_by);
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$email_cc .= $row['first_name']." ".$row['last_name']."(".$row['email_address']."); ";
						}
					}
				}
			}
		}
		
		if(!empty($email_bcc))
		{
			$email_bcc_data = explode(",",$email_bcc);
			$email_bcc = '';
			$datalist = '';
			$contact_type_bcc = '';
			for($i=0;$i<count($email_bcc_data);$i++)
			{
				if(stristr($email_bcc_data[$i],'CT-'))
					$contact_type_bcc[$i] = substr($email_bcc_data[$i],3);
				else
				{
					$explode = explode('-',$email_bcc_data[$i]);
					if(!empty($explode[1]))
						$datalist[$i] = $explode[1];
					//$datalist[$i] = $email_bcc_data[$i];
				}
			}
			//pr($datalist);exit;
			if(!empty($datalist))
			{
				//$result = $this->obj->contact_in_query($datalist);
				$table = "contact_master as cm";
				$fields = array('cm.first_name,cm.middle_name,cm.last_name','cm.company_name','cet.email_address');
				$join_tables = array(
									'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
								);
				$group_by = 'cet.id';
				$where_in = array('cet.id'=>$datalist);
				$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
				if(!empty($from_data))
				{
					foreach($from_data as $row)
						$email_bcc .= $row['first_name']." ".$row['last_name']."(".$row['email_address']."); ";
				}
			}			
			if(!empty($contact_type_bcc))
			{
				for($i=0;$i<count($contact_type_bcc);$i++)
				{
					$contact_type_data = '';
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.id,cm.first_name,cm.last_name,cet.email_address');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','contact_emails_trans cet'=>'cet.contact_id = cm.id');
					
					$group_by = 'cet.contact_id';
					$match = array('cct.contact_type_id'=>$contact_type_bcc[$i],'cet.is_default'=>'1');
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','','','','',$group_by);
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
							$email_bcc .= $row['first_name']." ".$row['last_name']."(".$row['email_address']."); ";
					}
				}
			}
			
		}*/
                
        if($this->uri->segment(6) != '')
		{
                    $searchsort_session = $this->session->userdata('all_bombsent_maillist_sortsearchpage_data');
                    $pagingid = $searchsort_session['uri_segment'];
		}
		else
		{
                    $searchsort_session = $this->session->userdata('sent_bomb_sortsearchpage_data');
                    $pagingid = $searchsort_session['uri_segment'];
		}
		
		$cdata['pagingid'] = $pagingid;
		//$cdata ['email_cc'] = $email_cc;
		//$cdata ['email_bcc'] = $email_bcc;
		$cdata['main_content'] =  $this->user_type.'/'.$this->viewName."/view_data";
		$this->load->view('admin/include/template',$cdata);
	}
	
	/*
		@Description: Function for search contact type
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   	*/
	
	public function search_contact_ajax()
    {
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		$config['total_rows'] = $this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc','1');
		
		
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	/*
		@Description: Function for search contact type in CC
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   	*/
	
	public function search_contact_ajax_cc()
    {
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_cc');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list_cc'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		$config['total_rows'] = $this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc','1');
		
		$this->pagination->initialize($config);		
		$data['pagination_cc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax_cc", $data);
	}

	/*
		@Description: Function for search contact type in BCC
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   */

	public function search_contact_ajax_bcc()
    {
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_ajax_bcc');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list_bcc'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		$config['total_rows'] = $this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc','1');
		
		$this->pagination->initialize($config);		
		$data['pagination_bcc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax_bcc", $data);
	}
	
	/*
		@Description: Function for Selected contacts type add the email to or cc or bcc
		@Author: Sanjay Chabhadiya
		@Input: - contact_type_id
		@Output: - 
		@Date: 06-08-2014
   	*/
   
	public function add_contacts_to_email()
	{
		$contacts_type = $this->input->post('contacts_type');
		$data['contacts_data'] = $this->contact_type_master_model->contact_type_in_query($contacts_type);
		echo json_encode($data['contacts_data']);
	}	
	
	/*
		@Description: Function for Email Attachment delete
		@Author: Sanjay Chabhadiya
		@Input: -  email attachment id
		@Output: - 
		@Date: 06-08-2014
   	*/
   
	public function ajax_delete_attachment()
	{
		$id = $this->input->post('single_remove_id');
		$attachment_name = $this->input->post('attachment_name');
		$this->obj->delete_attachment($id);
		$bgTempPath = $this->config->item('upload_image_file_path').'attachment_file/';
		if(file_exists($bgTempPath.$attachment_name))
		{ 
			@unlink($bgTempPath.$attachment_name);
		}
		echo 1;
	}
	
	
	/*
		@Description: Function for Attachment delete
		@Author: Sanjay Chabhadiya
		@Input: -  Attachment name
		@Output: - 
		@Date: 06-08-2014
   	*/
   
	public function delete_attachment()
	{
		$file_name = $this->input->post('file_name');
		$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
		if(file_exists($bgTempPath.$file_name))
		{ 
			@unlink($bgTempPath.$file_name);
		}
		echo $file_name;
	}
	
	
	/*
		@Description: Function for Get All selected campaign attachment list
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - email campaign attachment list
		@Date: 06-08-2014
    */
	
	public function attachmentlist()
	{
		$email_campaign_id = $this->input->post('email_campaign_id');
		if(!empty($email_campaign_id))
			$data['attachment'] = $this->obj->select_email_campaign_attachments($email_campaign_id);
		if(count($data['attachment']) > 0)
		 	$this->load->view("admin/".$this->viewName."/attachmentlist", $data);
	}

	/*
		@Description: Function for Email template data
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
        	$cdata['templatedata'] = $this->bomb_library_model->select_records('',$match,'','=','','','','id','desc');
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
		@Description: Function for Copy email campaign
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - all email campaign list
		@Date: 06-08-2014
    */
	
	public function copy_record()
    {
		//check user right
		check_rights('bomb_bomb_email_blast_add');
		
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0)
		{
			$data['template_name_id'] = $result[0]['template_name_id'];
			$data['template_category_id'] = $result[0]['template_category_id'];
			$data['template_subcategory_id'] = $result[0]['template_subcategory_id'];
			$data['template_subject'] = $result[0]['template_subject']."-copy";
			$data['email_message'] = $result[0]['email_message'];
			$data['email_signature'] = $result[0]['email_signature'];
			$data['email_send_type'] = $result[0]['email_send_type'];
			
			$data['is_draft'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$data['email_blast_type'] = 1;
			$this->obj->insert_record($data);
		}
		$this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->view);
	}
	
	/*
		@Description: Function for download file
		@Author: Sanjay Chabhadiya
		@Input: - File name
		@Output: - 
		@Date: 06-08-2014
    */
	
	public function download_form()
	{
		$file = $this->uri->segment(4);
		$filename = base_url()."uploads/attachment_file/".$file;	
		$file_directory = $this->config->item('base_path')."/uploads/attachment_file"; //Name of the directory where all the sub directories and files exists
		//$file =  "example.docx";//Get the file from URL variable
		$file_array = explode('/', $file); //Try to seperate the folders and filename from the path
		$file_array_count = count($file_array); //Count the result
		$filename = $file_array[$file_array_count-1]; //Trace the filename
		$file_path = $file_directory.'/'.$file; //Set the file path w.r.t the download.php... It may be different for u
		if(file_exists($file_path)){
			header("Content-disposition: attachment; filename={$filename}"); //Tell the filename to the browser
			header('Content-type: application/octet-stream'); //Stream as a binary file! So it would force browser to download
			readfile($file_path); //Read and stream the file
		}
		else {
			echo "Sorry, the file does not exist!";
		}
	}
	
	/*
		@Description: Function for resend email single or multiple
		@Author: Sanjay Chabhadiya
		@Input: - Email campaign id or email campaign transaction id
		@Output: - 
		@Date: 06-08-2014
    */
	
	public function resend_mail()
	{
		//pr($_POST);exit;
		$db_session = $this->session->userdata('db_session');
		if(!empty($db_session))
		{
			$db_name = $db_session['db_name'];
			$db_name1 = urlencode(base64_encode($db_name));
		}
		else
		{
			$db_obj = $this->db;
			$db_name = $db_obj->database;
			$db_name1 = urlencode(base64_encode($db_name));
		}
		
		$single_id = $this->input->post('single_id');
		$campaign_id = $this->uri->segment(4);
		$page = $this->uri->segment(5);
		$flag = 0;
		
		$fields = '';
		$join_tables = '';
		$send_contact_id = array();
		
		$field = array('bombbomb_username,bombbomb_password');
        $match = array('user_type'=>'2');
        $udata = $this->admin_model->get_user($field,$match,'','=');
		if((!empty($campaign_id) || !empty($single_id)) && !empty($udata[0]['bombbomb_username']) && !empty($udata[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($udata[0]['bombbomb_password']);
			$data['username'] = $udata[0]['bombbomb_username'];
			$data['password'] = $password;
			
			//$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".urlencode($data['username'])."&pw=".urlencode($data['password']));
                        $url = "https://app.bombbomb.com/app/api/api.php?method=IsValidLogin";
                        $IsValidLogin = $this->admin_model->bombbombapi_curl($url,$data);
			if(!empty($IsValidLogin))
				$connection = json_decode($IsValidLogin);
			else
				$connection->status = 'failure';

			if($connection->status == 'failure')
			{
				$edata['type'] = 'Bomb Bomb';
				$edata['description'] = 'Authentication failed.';
				$edata['created_date'] =date('Y-m-d h:i:s');
				$edata['status'] = 1;
				$edata['created_by'] = $this->admin_session['id'];
				$this->dashboard_model->insert_record1($edata);
			}
			if(!empty($connection->status) && $connection->status == 'success')
			{
				$table ="email_campaign_recepient_trans as ecr";
				$fields = array('ecr.email_address,ecr.id as ID,ecr.email_campaign_id,ecr.template_subject,ecr.contact_id,ecr.contact_id,cm.*,ecm.email_signature,ecm.total_sent,ecm.email_message,ecm.is_unsubscribe,ecm.template_name_id,btm.template_name,ecm.created_by');
				$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
									 'contact_master cm'=>'cm.id = ecr.contact_id',
									 'bomb_template_master as btm'=>'btm.id = ecm.template_name_id');
				
				$group_by = 'ecr.id';
				$wherestring = "cm.is_subscribe = '0' AND ecr.is_send = '0' AND ecm.email_blast_type = 1";
				if(!empty($single_id))
					$wherestring .= " AND ecr.id = ".$single_id;
				else
					$wherestring .= " AND ecr.email_campaign_id = ".$campaign_id;
				$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
				
				$message = '';
				if(count($datalist) > 0)
				{
					$send_mail_count = !empty($datalist[0]['total_sent'])?$datalist[0]['total_sent']:'';
					$datac['id'] = !empty($datalist[0]['email_campaign_id'])?$datalist[0]['email_campaign_id']:'';	
					$message = !empty($datalist[0]['email_message'])?$datalist[0]['email_message']:'';
					if(!empty($datalist[0]['email_signature']))
					{
						$match = array('id'=>$datalist[0]['email_signature']);
						$email_signature = $this->email_signature_model->select_records('',$match,'','=');
					}
					if(!empty($email_signature))
						$message .= "<br>".$email_signature[0]['full_signature'];
					if($datalist[0]['is_unsubscribe'] == '1')
						$message .= '{(my_unsubscribe_link)}';
					$headers = $message;
					
					$agent_name = '';
					$table ="login_master as lm";   
					$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type,lm.email_id');
					$join_tables = array('user_master as um'=>'lm.user_id = um.id');
					$wherestring = 'lm.id = '.$datalist[0]['created_by'];
					$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
					if(!empty($agent_datalist))
					{
						if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
							$agent_name = $agent_datalist[0]['admin_name'];
						else
							$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
					}
					$data['from_email'] = !empty($agent_datalist[0]['email_id'])?$agent_datalist[0]['email_id']:'';
					$data['from_name'] = !empty($agent_name)?$agent_name:'';
					foreach($datalist as $row)
					{
						$cdata['id'] = $row['ID'];
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
						$content = $headers;
						$title = $row['template_subject'];
						$output = $content;
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
						}
						$subject = $row['template_subject'];
						$message = '<div align="left">'.$output;
						$to = $row['email_address'];
						$cdata['email_address'] = $to;
						//$data['video_id'] = $row['video_id'];
						if(!empty($row['email_address']))
						{
							if(!empty($row['is_unsubscribe']) && $row['is_unsubscribe'] == '1'){
								$email_id = urlencode(base64_encode($to));
								$link = base_url()."unsubscribe/unsubscribe_link/".$db_name1.'--'.$email_id;
								//$link = base_url()."unsubscribe/unsubscribe_link/".$to;
								$message1 = '<br/><br/><a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
								$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
							}
							$message .= '</div>';
							$response = $this->obj->BombBombMailSend($to,$subject,$message,$data);
							if(!empty($response))
								$response = json_decode($response);
							//mail($to,$subject,'',"-f".$message);
						}
						if(!empty($response->status) && $response->status == 'success')
						{
							$cdata['sent_date'] = date('Y-m-d H:i:s');
							$cdata['is_send'] = '1';
							$cdata['info'] = $response->info->email_id;
							$send_mail_count++;
		
							$contact_conversation['contact_id'] = $row['contact_id'];
							$contact_conversation['log_type'] = 6;
							$contact_conversation['campaign_id'] = $row['email_campaign_id'];
							$contact_conversation['email_camp_template_id'] = $row['template_name_id'];
							
							if(!empty($row['template_name']))
								$contact_conversation['email_camp_template_name'] = $row['template_name'];
							
							$contact_conversation['created_date'] = date('Y-m-d H:i:s');
							$contact_conversation['created_by'] = $this->admin_session['id'];
							$contact_conversation['status'] = '1';
							$this->contact_conversations_trans_model->insert_record($contact_conversation);
						}
						unset($response);
						$this->obj->update_email_campaign_trans($cdata);
						//echo $this->db->last_query();
					}
					$match = array('is_send'=>'1');
					$sent_cnt = $this->obj->select_email_campaign_recepient_trans($campaign_id,'','','1');
					if($sent_cnt == $send_mail_count)
					{
						$datac['is_draft'] = '0';
						$datac['is_sent_to_all'] = '1';
						$datac['total_sent'] = 0;
					}
					else
					{
						$datac['is_sent_to_all'] = '0';
						$datac['total_sent'] = $send_mail_count;
					}
					$datac['id'] = $campaign_id;
					$this->obj->update_record($datac);
				}
			}

		}
		
		if(!empty($single_id))
			echo "/".$campaign_id."/".$page;
		else
		{
			$searchsort_session = $this->session->userdata('bomb_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
			redirect('admin/'.$this->view.'/'.$pagingid);
		}
	}
	
	/*
		@Description: Function for Get All sent Email List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all sent Email list
		@Date: 30-08-2014
    */
	
	public function all_sent_mail()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('all_bombsent_maillist_sortsearchpage_data');
                }
        $data['sortfield']	= 'ecr.id';
		$data['sortby']		= 'desc';
                $searchsort_session = $this->session->userdata('all_bombsent_maillist_sortsearchpage_data');
		
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
				$sortfield = $data['sortfield'];
				$sortby = $data['sortby'];
            }
                    } else {
				$sortfield = 'ecr.id';
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
                         /*   $data['searchtext'] = $searchsort_session['searchtext'];
                            $searchtext =  $data['searchtext'];*/
							$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
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
						$data['perpage'] = '10';
                    }
		}
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/all_sent_mail/');
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
		
		$table ="email_campaign_master as ecm";   
		$fields = array('ecr.template_subject','etm.template_name','cm.first_name,cm.last_name','ecr.sent_date,ecr.id,ecr.email_campaign_id,ecm.interaction_id','ipi.description,ipm.plan_name,ecr.info');
		$join_tables = array('email_campaign_recepient_trans as ecr'=>'ecr.email_campaign_id = ecm.id',
							 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
							 'bomb_template_master as etm'=>'etm.id = ecm.template_name_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
							 
		$wherestring = "ecr.is_send = '1' AND ecm.email_blast_type = 1";
		
		if(!empty($searchtext))
		{
			
			$concat = "CONCAT_WS(' ',cm.first_name,cm.last_name)";
			$interaction_plan = "CONCAT_WS(' >> ',ipm.plan_name,ipi.description)";
			$match=array('ecr.template_subject'=>$searchtext,'etm.template_name'=>$searchtext,$concat=>$searchtext,$interaction_plan=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'','1');
		}
				
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

		$all_bombsent_maillist_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
        $this->session->set_userdata('all_bombsent_maillist_sortsearchpage_data', $all_bombsent_maillist_sortsearchpage_data);
    
	    $data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_all_sent_maillist',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/all_sent_maillist";
			$this->load->view('admin/include/template',$data);
		}
    	
	}
	
	/*
		@Description: Function for Get All queued Email(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued Email
		@Date: 30-08-2014
    */
	
	public function queued_list()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
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
		if(!empty($perpage) && $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
			$data['perpage']='10';
		}
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/queued_list/');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="email_campaign_master as ecm";
		$fields = array('ipm.*');
		$join_tables = array('email_campaign_recepient_trans as ecr'=>'ecr.email_campaign_id = ecm.id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$wherestring = "ecm.email_type = 'Intereaction_plan' AND ecr.is_send = '0' AND ipm.status = '1' AND ecm.email_blast_type = 1";
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
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		$this->pagination->initialize($config);
		$data['interaction_pagination']	= $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
		if($this->input->post('result_type') == 'ajax')
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_queued_list',$data);
		else
		{	
			
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/queued_list";
			$this->load->view('admin/include/template',$data);
		}	
	}
	
	/*
		@Description: Function for Get Queued Email Interaction list
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - Interaction plan list
		@Date: 30-08-2014
    */

	
	public function interaction_queued_list()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		$plan_id = $this->uri->segment(4);	
		$searchopt='';$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$data['sortfield_name']		= 'ipi.id';
		$data['sort']			= 'desc';
		
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
		if(!empty($perpage))
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
			$data['perpage']='10';
		}
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/interaction_queued_list/'.$plan_id);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "email_campaign_recepient_trans as ecr";
		$fields = array('ipi.id,ipi.description,btm.template_name');
		$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',
							 'bomb_template_master as btm'=>'btm.id = ipi.template_name'
							 );
		$wherestring = "ecm.email_type = 'Intereaction_plan' AND ecr.is_send = '0' AND ipm.status = '1' AND ecm.email_blast_type = 1 AND ipm.id = ".$plan_id;
		$groupby = 'ipi.id';
		if(!empty($searchtext))
		{
			$match=array('ipi.description'=>$searchtext,'btm.template_name'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			/*echo $this->db->last_query();
			pr($data['datalist']);exit;*/
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		
		$this->pagination->initialize($config);
		$data['pagination']	= $this->pagination->create_links();
		if($this->input->post('result_type') == 'ajax')
		{
		//	echo "hi";exit;
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_interaction_queued_list',$data);
		}
		else
		{	
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/queued_list";
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for Get Queued Email list
		@Author: Sanjay Chabhadiya
		@Input: - interaction_plan_id
		@Output: - Email Queued list
		@Date: 30-08-2014
    */
	
	public function interaction_plan_queued_list()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		
		$interaction_id = $this->uri->segment(4);
		$searchopt='';$searchtext='';$perpage='';
		$searchtext =mysql_real_escape_string( $this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		
		
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('emails_interaction_plan_queued_list_data');
		}
		$data['sortfield_name']		= 'ecr.id';
		$data['sort']			= 'desc';
		$searchsort_session = $this->session->userdata('emails_interaction_plan_queued_list_data');
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
				$sortfield = 'ecr.id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		}
		else
		{
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
				/*	$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];
				}
			}	
		}
		if(!empty($perpage) && $perpage != 'null')
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
				$data['perpage'] = '10';
			}
		}
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/interaction_plan_queued_list/'.$interaction_id);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 5;
			$uri_segment = $this->uri->segment(5);
		}
		
		$table = "email_campaign_recepient_trans as ecr";
		$fields = array('ecr.template_subject','cm.first_name,cm.last_name','ipi.description,ipm.plan_name','ecm.email_send_date,ecm.email_type,ecr.send_email_date,ecm.interaction_id,ecr.id,ecr.is_email_exist,ipccp1.is_done,ipi.interaction_id as interaction_id1,cm.is_subscribe,ecr.is_email_exist,cet.is_default,ipi.start_type as i_start_type,ipi.start_type as i_start_type');
		$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',
							 '(select * from contact_emails_trans where is_default = "1") as cet'=>'cet.contact_id = ecr.contact_id'
							 );
							/*email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'*/
		
		$wherestring = "ecm.email_type = 'Intereaction_plan' AND ipi.id = ".$interaction_id." AND ecr.is_send = '0' AND ipi.status = '1' AND ecm.email_blast_type = 1";
		$groupby = 'ecr.id';
		if(!empty($searchtext))
		{
			$match = array('ecr.template_subject'=>$searchtext,"CONCAT_WS(' ',cm.first_name,cm.last_name)"=>$searchtext,"CONCAT_WS(' >> ',ipm.plan_name,ipi.description)"=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
			//echo $this->db->last_query();	
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		}
		
		//pr($data['datalist']);exit;
		
		$table = "interaction_plan_interaction_master as ipi";
		$fields = array('ipi.description,ipm.plan_name');
		$join_tables = array('interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',);
		$wherestring = "ipi.id = ".$interaction_id." AND ipi.status = '1'";
		$data['list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$sms_sortsearchpage_data = array(
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('emails_interaction_plan_queued_list_data', $sms_sortsearchpage_data);
		
		$searchsort_session = $this->session->userdata('emails_interaction_plan_queued_list_data');
		//pr($searchsort_session);
		$data['uri_segment'] = $uri_segment;
		
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/interaction_ajax_queued_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/interaction_queued_list";
			$this->load->view('admin/include/template',$data);
		}
	}
	
	/*
		@Description: Function for get interaction plan email details
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - All Details
		@Date: 03-09-2014
    */
	
	public function view_interaction_data()
	{
		// Check BombBomb Username or Password exist or not.
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		if(!empty($connection) && empty($connection[0]['bombbomb_username']) && empty($connection[0]['bombbomb_password']))
			redirect('admin/social_account_master');
		//$searchsort_session = $this->session->userdata('emails_interaction_plan_queued_list_data');
		//pr($searchsort_session);
		$id = $this->uri->segment(4);
		$table = "email_campaign_recepient_trans as ecr";
		$fields = array("ecm.email_message,ecm.template_subject,GROUP_CONCAT(DISTINCT ect.attachment_name
SEPARATOR ',') as attachment_name,cm.first_name,cm.last_name,mml1.category as category,etm.template_name,ecr.*,cet.email_address,ecm.interaction_id");
		$join_tables = array('email_campaign_master ecm'=>'ecm.id = ecr.email_campaign_id',
							//'email_signature_master esm'=>'esm.id = ecm.email_signature',
							'contact_master cm'=>'ecr.contact_id = cm.id',
							'email_campaign_attachments ect'=>'ect.email_campaign_id = ecm.id',
							'marketing_master_lib__category_master mml1'=>'mml1.id = ecm.template_category_id',
							//'marketing_master_lib__category_master mml2'=>'mml2.id = ecm.template_subcategory_id',
							'bomb_template_master etm'=>'etm.id = ecm.template_name_id',
							'contact_emails_trans cet'=>'cet.contact_id = ecr.contact_id'
							);
		$group_by = 'cet.contact_id';
		$wherestring = "cet.is_default = '1' AND cm.is_subscribe = '0' AND ecr.is_send = '0' AND ecr.id = ".$id." AND ecm.email_blast_type = 1";
		$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		//pr($data['datalist']);exit;
		if(count($data['datalist']) > 0)
		{
			$data['main_content'] = "admin/".$this->viewName."/interaction_view_data";
        	$this->load->view('admin/include/template',$data);
		}
		else
		{
			$cdata['id'] = $id;
			$cdata['is_email_exist'] = '0';
			$this->obj->update_email_campaign_trans($cdata);
			$this->session->set_userdata('message_session', $newdata);
            redirect(base_url('admin/'.$this->view.'/interaction_plan_queued_list/'.$this->uri->segment(5)));	
		}
	}
	
	/*
		@Description: Function for Send the queued Email(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued Email
		@Date: 03-09-2014
    */
	
	public function interaction_mailsms()
	{
		//pr($_POST);exit;
		$db_session = $this->session->userdata('db_session');
		if(!empty($db_session))
		{
			$db_name = $db_session['db_name'];
			$db_name1 = urlencode(base64_encode($db_name));
		}
		else
		{
			$db_obj = $this->db;
			$db_name = $db_obj->database;
			$db_name1 = urlencode(base64_encode($db_name));
		}
		
		$table = "email_campaign_recepient_trans as ecr";
		$fields = array('cet.email_address,ecr.id,ecr.email_campaign_id,ecr.template_subject,ecr.email_message,ecr.contact_id,ecm.template_name_id,ecm.is_unsubscribe,btm.template_name,ipi.interaction_plan_id,ipi.start_type as i_start_type,ipccp1.is_done,ipi.interaction_id as interaction_id1,ecr.send_email_date');
		$join_tables = array('contact_emails_trans cet'=>'cet.contact_id = ecr.contact_id',
							 'email_campaign_master ecm'=>'ecm.id = ecr.email_campaign_id',
							 'contact_master cm'=>'cm.id = ecr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
							 '(select * from interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
							 'bomb_template_master as btm'=>'btm.id = ecm.template_name_id'
							 );
			
		$group_by = 'cet.contact_id';
		$wherestring = "cet.is_default = '1' AND cm.is_subscribe = '0' AND ecr.is_send = '0' AND ecm.email_blast_type = 1 AND '".date('Y-m-d')."' >= ecr.send_email_date";
		if($this->input->post('submitbtn'))
		{
			$interaction_id = $this->input->post('interaction_id');
			$interaction_plan_id = $this->input->post('interaction_plan_id');
			$id = $this->input->post('id');
			$wherestring .=  " AND ecr.id = ".$id;
		}
		else
		{
			$interaction_id = $this->uri->segment(4);
			$wherestring .= ' AND ipi.id = '.$interaction_id;
		}
		//$wherestring = "cet.is_default = '1' AND cm.is_subscribe = '0' AND ecr.is_send = '0' AND ecr.id = ".$id." AND ecm.email_blast_type = 1";
		$result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		//echo $this->db->last_query();
		//pr($result);exit;
		
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$admin_data = $this->admin_model->get_user($fields,$match,'','=');
		
		if(!empty($admin_data[0]['bombbomb_username']) && !empty($admin_data[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($admin_data[0]['bombbomb_password']);
			$data['username'] = $admin_data[0]['bombbomb_username'];
			$data['password'] = $password;
			//$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".urlencode($data['username'])."&pw=".urlencode($data['password']));
                        $url = "https://app.bombbomb.com/app/api/api.php?method=IsValidLogin";
                        $IsValidLogin = $this->admin_model->bombbombapi_curl($url,$data);
			if(!empty($IsValidLogin))
				$connection = json_decode($IsValidLogin);
			else
				$connection->status = 'failure';

			if($connection->status == 'failure')
			{
				$edata['type'] = 'Bomb Bomb';
				$edata['description'] = 'Authentication failed.';
				$edata['created_date'] =date('Y-m-d h:i:s');
				$edata['status'] = 1;
				$edata['created_by'] = $this->admin_session['id'];
				$this->dashboard_model->insert_record1($edata);
			}
		}
		//echo $connection->status;exit;
		if(count($result) > 0 && !empty($connection->status) && $connection->status == 'success')
		{
			$interaction_plan_id = $result[0]['interaction_plan_id'];
			$message = '<div align="left">';
			$subject = $this->input->post('txt_template_subject');
			$cdata['email_message'] = $this->input->post('email_message');
			$message .= $this->input->post('email_message');
			//$data['video_id'] = $this->input->post('video_id');
			
			$match = array('id'=>$result[0]['email_campaign_id']);
			$campaign_data = $this->obj->select_records('',$match,'','=');
			if(!empty($campaign_data[0]['email_signature']))
			{
				$match = array('id'=>$campaign_data[0]['email_signature']);
				$email_signature = $this->email_signature_model->select_records('',$match,'','=');
			}
			if(!empty($email_signature))
				$message .= "<br>".$email_signature[0]['full_signature'];
			if($result[0]['is_unsubscribe'] == '1')
				$message .= '<br>'.'{(my_unsubscribe_link)}';
			
			$table = "interaction_plan_interaction_master as ipim";
			$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type,lm.email_id');
			$join_tables = array('login_master lm'=>'lm.id = ipim.assign_to',
								 'user_master um'=>'um.id = lm.user_id',
								 );
			$group_by = 'ipim.id';
			$wherestring = "ipim.id = ".$interaction_id;
			$fromdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
			//pr($fromdata);exit;
			if(!empty($fromdata))
			{
				if(!empty($fromdata[0]['user_type']) && ($fromdata[0]['user_type'] == '2' || $fromdata[0]['user_type'] == '5'))
					$from = $fromdata[0]['admin_name'];
				else
					$from = trim($fromdata[0]['first_name']).' '.trim($fromdata[0]['middle_name']).' '.trim($fromdata[0]['last_name']);
				if(!empty($fromdata[0]['email_id']))
					$from_email = $fromdata[0]['email_id'];
			}
			//$headers .= "From: ".$from." <".$from_email.">\r\n";
			$data['from_email'] = !empty($from_email)?$from_email:'';
			$data['from_name'] = ($from)?$from:'';
			$headers = $message;
			if(count($result) > 0)
			{
				foreach($result as $row)
				{
					if(!empty($row['i_start_type']) && $row['i_start_type'] == 2 && !empty($row['interaction_id1']) && $row['is_done'] == '0')
					{}
					else
					{
						$cdata['id'] = $row['id'];
						if(!$this->input->post('submitbtn'))
						{
							$message = '<div align="left">';
							$subject = $row['template_subject'];
							$cdata['email_message'] = $row['email_message'];
							$message .=  $row['email_message'];
							//$data['video_id'] = $row['video_id'];
							$headers = $message;
							//$to = $row['email_address'];
							//$cdata['email_address'] = $to;
						}
						//echo $message;
						//pr($cdata);exit;
						if(!empty($row['email_address']))
						{
							$to = $row['email_address'];
							$cdata['email_address'] = $to;
							//echo "hi";exit;
							if($row['is_unsubscribe'] == '1'){
								$email_id = urlencode(base64_encode($to));
								$link = base_url()."unsubscribe/unsubscribe_link/".$db_name1.'--'.$email_id;
								$message1 = '<br/> <br/> <a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
								$message = str_replace('{(my_unsubscribe_link)}',$message1,$headers);
							}
							$message .= '</div>';
							$response = $this->obj->BombBombMailSend($to,$subject,$message,$data);
							if(!empty($response))
								$response = json_decode($response);
							//mail($to,$subject,'',"-f".$headers);
						}
						if(!empty($response->status) && $response->status == 'success')
						{
							$cdata['template_subject'] = $subject;
							$cdata['sent_date'] = date('Y-m-d H:i:s');
							$cdata['is_send'] = '1';
							$cdata['info'] = $response->info->email_id;
							if(!empty($row))
							{
								$contact_conversation['contact_id'] = $row['contact_id'];
								$contact_conversation['log_type'] = 5;
								$contact_conversation['campaign_id'] = $row['email_campaign_id'];
								$contact_conversation['email_camp_template_id'] = $row['template_name_id'];
								
								if(!empty($row['template_name']))
									$contact_conversation['email_camp_template_name'] = $row['template_name'];
								
								$contact_conversation['created_date'] = date('Y-m-d H:i:s');
								$contact_conversation['created_by'] = $this->admin_session['id'];
								$contact_conversation['status'] = '1';
								$this->contact_conversations_trans_model->insert_record($contact_conversation);
								
								$icdata['interaction_plan_interaction_id'] = $interaction_id;
								$icdata['contact_id'] = $row['contact_id'];
								$icdata['is_done']='0';
								
								$communication_trans_id = $this->contacts_model->get_interaction_plan_contact_communication_plan($icdata);
								if(count($communication_trans_id) > 0)
								{
									$icdata['id'] = $communication_trans_id[0]['id'];
									$icdata['task_date'] = date('Y-m-d');
									$icdata['task_completed_date'] = date('Y-m-d H:i:s');
									$icdata['completed_by'] = $this->admin_session['id'];
									$icdata['is_done']='1';
									$this->contacts_model->update_interaction_plan_interaction_transtrans_record($icdata);
									common_rescheduled_task($icdata['id']);
								}
								unset($response);
								unset($icdata);
							}
							//echo $this->db->last_query();
						}
						else
							$cdata['is_send'] = '0';	
						$this->obj->update_email_campaign_trans($cdata);
					}
				}
			}
		}
		elseif(!empty($connection->status) && $connection->status == 'success')
		{
			$cdata['id'] = $id;
			$cdata['is_email_exist'] = '0';
			$this->obj->update_email_campaign_trans($cdata);
		}
//exit;
		$searchsort_session = $this->session->userdata('emails_interaction_plan_queued_list_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		$delete_all_flag = 0;
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
		//echo $interaction_plan_id;exit;
		//echo $pagingid;
		if(!$this->input->post('submitbtn'))
		{
			if(!empty($interaction_plan_id))
				redirect('admin/'.$this->view.'/interaction_queued_list/'.$interaction_plan_id);
			else
				redirect('admin/'.$this->view.'/queued_list/'.$interaction_plan_id);
		}
		elseif(!empty($interaction_plan_id))
			redirect('admin/'.$this->view.'/interaction_plan_queued_list/'.$interaction_id.'/'.$pagingid);
		else
			redirect('admin/'.$this->view);
	}
	
	/*
		@Description: Function for Email Unsubscribe or Email id not available Email transaction delete
		@Author: Sanjay Chabhadiya
		@Input: - ID
		@Output: - 
		@Date: 03-09-2014
    */
	
	function delete_record_trans()
    {
        $id = $this->input->post('id');
		$this->obj->email_campaign_trans_delete('',$id);
		
		$searchsort_session = $this->session->userdata('emails_interaction_plan_queued_list_data');
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
		@Description: Function for search contact(TO)
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 06-08-2014
   	*/
	
	function search_contact_to()
	{
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_to');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
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
		
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$where = '';
		if(!empty($contact_status))
			$where .= 'cm.contact_status = '.$contact_status.' AND ';
		if(!empty($contact_source))
			$where .= 'cm.contact_source = '.$contact_source.' AND ';
		if(!empty($contact_type))
			$where .= 'cct.contact_type_id = '.$contact_type.' AND ';
			
		$where .= "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'company_name'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);

		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cet.id';
		
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
		
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
		//echo $this->db->last_query();
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/contact_to", $data);
	}
	
	/*
		@Description: Function for search contact(CC)
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 06-08-2014
   	*/
	
	function search_contact_cc()
	{
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_cc');
		$config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
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
		
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$where = '';
		if(!empty($contact_status))
			$where .= 'cm.contact_status = '.$contact_status.' AND ';
		if(!empty($contact_source))
			$where .= 'cm.contact_source = '.$contact_source.' AND ';
		if(!empty($contact_type))
			$where .= 'cct.contact_type_id = '.$contact_type.' AND ';
		$where .= "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'company_name'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cet.id';
		
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
		
		$data['contact_cc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
		//echo $this->db->last_query();
		
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination_contact_cc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/contact_cc", $data);
	}
	
	/*
		@Description: Function for search contact(BCC)
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 06-08-2014
   	*/
	
	function search_contact_bcc()
	{
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'.$this->view.'/search_contact_bcc');
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
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
		
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$where = '';
		if(!empty($contact_status))
			$where .= 'cm.contact_status = '.$contact_status.' AND ';
		if(!empty($contact_source))
			$where .= 'cm.contact_source = '.$contact_source.' AND ';
		if(!empty($contact_type))
			$where .= 'cct.contact_type_id = '.$contact_type.' AND ';
		$where .= "cm.is_subscribe = '0' AND (cet.is_default = '1' OR cet.email_type = 1)";
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'company_name'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		$table = "contact_master as cm";
		$fields = array('cm.id,cet.id as email_trans_id,cet.email_type','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cet.id';
		
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
		
		$data['contact_bcc'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
		
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination_contact_bcc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/contact_bcc", $data);
	}
	
	/*
		@Description: Function for Selected contacts add the email to or cc or bcc
		@Author: Sanjay Chabhadiya
		@Input: - contact_id
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function contacts_to_email()
	{
		$contacts = $this->input->post('contacts_id');
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address,cet.id as email_trans_id,cet.email_type');
		$join_tables = array(
						'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
					);
		$group_by = 'cet.id';
		$i = 0;
		$email_trans_id = array();
		foreach($contacts as $row)
		{
			$explode = explode('-',$row);
			if(!empty($explode[1]))
			{
				$email_trans_id[$i] = $explode[1];
				$i++;
			}
		}
		//pr($email_trans_id);
		$where_in = array('cet.id'=>$email_trans_id);
		$where = array('cm.is_subscribe'=>"'0'");
		$data['contacts_data'] = array();
		if(!empty($email_trans_id))
			$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
		//echo $this->db->last_query();
		echo json_encode($data['contacts_data']);
	}
	
	/*
		@Description: Function for selected video remove
		@Author: Sanjay Chabhadiya
		@Input: - ID
		@Output: - 
		@Date: 10-03-2015
   	*/
	
	public function delete_image()
	{
		$data['id']	= $this->input->post('id');
		$data['video_id'] = '';
		$data['thumb_url'] = '';
		$data['video_title'] = '';
		$this->obj->update_record($data);
		echo "done";
	}
	
	/*
		@Description: Function for Email Tracking
		@Author: Sanjay Chabhadiya
		@Input: - Track ID
		@Output: - 
		@Date: 11-03-2015
   	*/
	
	public function emailTracking()
	{
		$track_id = $this->input->post('track_id');
		
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$connection = $this->admin_model->get_user($fields,$match,'','=');
		$email_id = '';
		$email_pass = '';
		if(!empty($connection[0]['bombbomb_username']) && !empty($connection[0]['bombbomb_password']))
		{
			$password = $this->common_function_model->decrypt_script($connection[0]['bombbomb_password']);
			$email_id = $connection[0]['bombbomb_username'];
			$email_pass = $password;
		}
				
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,"https://app.bombbomb.com/app/api/api.php?method=emailTracking&email=".urlencode($email_id)."&pw=".urlencode($email_pass)."&action=getPerContactDetailForAllJobs&email_id=".urlencode($track_id));
		//curl_setopt($curl,CURLOPT_POST,5);
		//curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
		$result = curl_exec($curl);
		curl_close($curl);
		if(!empty($result));
			$TrackingData = json_decode($result);
		//pr($TrackingData);exit;
		//echo $per_contact_all_info[0]->email_status;
		$datalist = array();
		if(!empty($TrackingData) && !empty($TrackingData->status) && $TrackingData->status == 'success')
		{
			$per_contact_all_info = json_decode($TrackingData->info);
			if(!empty($per_contact_all_info))
			{
				$datalist['email_status'] = $per_contact_all_info[0]->email_status;
				$datalist['total_opens'] = $per_contact_all_info[0]->total_opens;
				$datalist['total_plays'] = $per_contact_all_info[0]->total_plays;
				$datalist['total_clicks'] = $per_contact_all_info[0]->total_clicks;
				$datalist['status'] = 'success';
			}
		}
		else
		{
			$datalist['email_status'] = 'NA';
			$datalist['total_opens'] = 'NA';
			$datalist['total_plays'] = 'NA';
			$datalist['total_clicks'] = 'NA';
			$datalist['status'] = 'failure';
		}
		echo json_encode($datalist);
		//$getPerContactDetailForAllJobs = json_decode($result);
	}
	
}
