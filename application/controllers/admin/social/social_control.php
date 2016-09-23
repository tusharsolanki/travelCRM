<?php
/*
    @Description: social campaign controller
    @Author: Sanjay Chabhadiya
    @Input: 
    @Output: 
    @Date: 06-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class social_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('phonecall_script_model');
		$this->load->model('contact_masters_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('email_signature_model');
		$this->load->model('contacts_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('social_recepient_trans_model');
		$this->load->model('social_model');
		$this->load->model('socialmedia_post_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('social_recepient_trans_model');
		$this->load->model('admin_model');
		
		$this->load->model('contact_masters_model');
		$this->load->model('user_management_model');
		
		$this->obj = $this->social_model;
		$this->obj1 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		$this->load->library('Twilio');
		
		
		/*$data['sms_campaign_id'] = 10;
		$data['contact_id'] = 122;
		$data['is_send'] = '0';
		$this->obj->delete_interaction_campaign($data);
		exit;*/
		
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
		check_rights('social');
		
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
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('social_sortsearchpage_data');
                }
                $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('social_sortsearchpage_data');
		
		
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
                          /*  $data['searchtext'] = $searchsort_session['searchtext'];
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
		$config['base_url'] = site_url($this->user_type.'/'."social/");
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
		
		$table ="social_master as scm";   
		$fields = array('scm.id,scm.platform,scm.is_draft,scm.is_sent_to_all,scm.social_send_date,scm.social_send_time','scm.social_message','stm.template_name');
		$join_tables = array('social_media_template_master as stm'=>'stm.id = scm.template_name','contact__social_type_master as ctm'=>'ctm.id = scm.platform');
		
		$wherestring = '';
		if(!empty($searchtext))
		{
			$match=array('stm.template_name'=>$searchtext,'scm.social_message'=>$searchtext,'ctm.name'=>$searchtext);
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

	//	$data['total_social'] = $this->obj->total_social($this->admin_session['id']);
		//pr($data['total_social']);exit;
		
		/*$admin_id = $this->admin_session['id'];
		$field = array('id','remain_sms');
                $match = array('id'=>$admin_id);
		$data['udata'] = $this->admin_model->get_user($field, $match,'','=');
        */        
        $social_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

			
		$this->session->set_userdata('social_sortsearchpage_data', $social_sortsearchpage_data);
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
	public function social_home()
    {	
		$field = array('fb_api_key','fb_secret_key');
		$match = array('id'=>$this->admin_session['id']);
        $result = $this->admin_model->get_user($field,$match,'','=');
		$data['fb_deatils']= $result;
		$data['main_content'] = 'admin/'.$this->viewName."/home";
		$this->load->view('admin/include/template',$data);
    }
    /*
    @Description: Function Add New social campaign details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add social campaign details
    @Date: 06-08-2014
    */
   
    public function add_record()
    {
		$id = $this->uri->segment(4);
		if($id ==2)
		{
			//check user right
		    check_rights('twitter_add');	
		}
		 if($id == 3)
		{
			//check user right
		    check_rights('linkedin_add');	
		}
		if($id =='all')
		{
			//check user right
		    check_rights('all_channels_add');	
		}
		else{}
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if(!empty($id))
		{
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
							);
			$group_by='cm.id';
			$where_in = array('cm.id'=>$id);
			//$where = array('cm.is_subscribe'=>"'0'");
			$data['email_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,'',$where_in);
		}
		$data['profile_type'] = $this->obj1->select_records1('','','','','','','','name','asc','contact__social_type_master');

		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'."social/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		$config['total_rows'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc','1');
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'."social/search_contact_to");
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
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		$where = array('cet.is_default'=>"'1'");
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$data['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address','cm.fb_id','cm.created_type','cm.linkedin_id');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$where = array('cm.created_type'=>"'4'",'cm.linkedin_id !='=>"''");
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$data['contact_linkedin'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address','cm.fb_id','cm.created_type','cm.linkedin_id');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$where = array('cm.fb_id !='=>"''");
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$data['contact_fb'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		//////////////////////////////////// END ///////////////////////////////////////////////

		$data['communication_plans'] = '';
		$table1='custom_field_master';
		$where1=array('module_id'=>'2');
		$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
;
		//$data['tablefield_data']=$this->email_library_model->select_records3();
		
		$match = array();
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		//Get linked in page
		 $field = array('id','linkedin_access_token','linkedin_secret_access_token');
		 $match = array('id'=>$this->admin_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		 if(!empty($linkedin_access_token))
		 {
			/*$fields = array(
			'id',
            'name',
			'description',
			'industry',
			'logo-url'
			);
			$request = join(',',$fields);
			
			$response = $this->fetchcompany("POST","/v1/companies:({$request})");
			$data['linkedin_page']=$response->values;*/
			
			$this->data['consumer_key'] = $this->config->item('linkedin_api_key');
			$this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
			
			$this->load->library('linkedin/linkedin', $this->data);
			$com=$this->linkedin->getcompany(unserialize($linkedin_access_token));
			
			$xml = simplexml_load_string($com);
			$json = json_encode($xml);
			$company = json_decode($json,TRUE);
			
			$data['linkedin_page']=$company['company'];
			
		 }
		//$data['profile_trans_data'] = $this->contacts_model->select_social_name_twitter($this->admin_session['id']);
		//$data['profile_trans_data'] = $this->contacts_model->select_social_name_twitter($this->admin_session['id']);
		//echo $this->db->last_query();
		$field = array('id','twitter_id','linkedin_access_token','twitter_username');
		$match = array('id'=>$this->admin_session['id']);
		$udata = $this->admin_model->get_user($field, $match,'','=');
		if(!empty($udata))
		{
			$field1 = array('id','twitter_handle','twitter_id');
			$match = array('created_by'=>$this->admin_session['id'],'twitter_user_id'=>$udata[0]['twitter_id']);
			$twitter_social = $this->contacts_model->select_records4($field1,$match,'','=','','','','id','desc');
			$data['profile_trans_data']=$twitter_social;
			$data['profile_user_data']=$udata[0]['twitter_username'];
			//pr($data['twitter_social']);exit;
		}	
		$field = array('fb_api_key','fb_secret_key');
		$match = array('id'=>$this->admin_session['id']);
		$fb_deatils = $this->admin_model->get_user($field,$match,'','=');
		$data['fb_deatils']= $fb_deatils;
		//pr($data['profile_trans_data']);exit;
		$data['main_content'] = "admin/".$this->viewName."/add";
		//pr($data);exit;
        $this->load->view('admin/include/template', $data);
    }
	function fbconnection()
	{
		$field = array('fb_api_key','fb_secret_key');
		$match = array('id'=>$this->admin_session['id']);
        $result = $this->admin_model->get_user($field,$match,'','=');
	//	pr($result);exit;
		$facebook_api_key=!empty($result[0]['fb_api_key'])?$result[0]['fb_api_key']:$this->config->item('facebook_api_key');
		$facebook_secret_key= !empty($result[0]['fb_secret_key'])?$result[0]['fb_secret_key']:$this->config->item('facebook_secret_key');
		$callback_url         =   base_url().'admin/social/fbconnection/?fbTrue=true';
		$config=array(
		  'appId'  => $facebook_api_key,
		  'secret' => $facebook_secret_key,
		  'cookie' => true
		);
		$this->load->library('facebook/facebook',$config);
		// Create our Application instance (replace this with your appId and secret).
		$facebook = new Facebook(array(
		  'appId'  => $facebook_api_key,
		  'secret' => $facebook_secret_key,
		  'cookie' => true
		));
		
		
		if(isset($_GET['fbTrue']))
		{
		
			$token_url = "https://graph.facebook.com/oauth/access_token?"
        . "client_id=".$facebook_api_key."&redirect_uri=" . urlencode($callback_url)
        . "&client_secret=".$facebook_secret_key."&code=" . $_GET['code'];
			//pr($token_url);exit;
			$response = file_get_contents($token_url);   // get access token from url
			$params = null;
			parse_str($response, $params);
			$message = !empty($message)?$message:'';
			//pr($params['access_token']);exit;
			$this->session->set_userdata('token',$params['access_token']);
			//$_SESSION['token'] = $params['access_token'];
		
			$graph_url_pages = "https://graph.facebook.com/me/accounts?access_token=".$params['access_token'];
			$pages = json_decode(file_get_contents($graph_url_pages)); // get all pages information from above url.
			
			$dropdown = "";
			$data['pages']=$pages;
			$graph_url_pages = "https://graph.facebook.com/me/accounts?access_token=".$params['access_token'];
			$pages = json_decode(file_get_contents($graph_url_pages)); // get all pages information from above url.
			$dropdown = "";
			$message = !empty($message)?$message:'';
			//pr($pages);
			$match = array("parent"=>'0');
            $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			$data['main_content'] = "admin/".$this->viewName."/facebook_page";
        	$this->load->view('admin/include/template', $data);    
		}
		else
		{
			
				$page = explode("-",$_POST['page']);
				$page_token = $page[0];
				$page_id= $page[1];
				// status with link
				
				//echo "<pre>"; print_r($page_id); exit;
				
				$publish = $facebook->api('/'.$page_id.'/feed', 'post',
						array('access_token' => $page_token,
						'message'=> $_POST['social_message'],
						'from' => $facebook_api_key,
						'to' => $page_id,
						/*'caption' => 'LiveWire Message',
						'name' => 'LiveWire CRM',
						'link' => 'http://topsdemo.in/qa/livewire_crm/v.1.0/admin/',
						'picture' => 'http://topsdemo.in/qa/livewire_crm/v.1.0/images/logo.png',
						'description' => $_POST['status'].' via TOPS Technologies'*/
						));
						if($publish)
						{
							$data['template_name'] = $this->input->post('template_name');
							$data['template_category'] = $this->input->post('slt_category');
							//$data['template_subcategory'] = $this->input->post('slt_subcategory');
							$data['social_message'] = $this->input->post('social_message');
							$data['page_name'] = trim($this->input->post('page_name'));
							
							$data['is_draft'] = '0';
							$data['platform'] = '1';
							//$data['social_type'] = 'Campaign';
							$data['created_by'] = $this->admin_session['id'];
							$data['created_date'] = date('Y-m-d H:i:s');		
							//$data['status'] = '1';
							
							$social_campaign_id = $this->obj->insert_record($data);	
						}
				//Simple status without link
			
				//$publish = $facebook->api('/'.$page_id.'/feed', 'post',
			//        array('access_token' => $page_token,'message'=>$_POST['status'] .'   via PHPGang.com Demo',
			//        'from' => $config['App_ID']
			//        ));
			    //$data['msg']='Status updated';
				//redirect('admin/'.$this->viewName);
				?>
				<script type="text/javascript">
					window.close();
					/*window.onunload = refreshParent;
					function refreshParent() {*/
						window.opener.location.href = '<?=base_url('admin/'.$this->viewName)?>';
					/*}*/
				</script>
				
				<?
				/*$token = $this->session->userdata('token'); 
				 
				$graph_url_pages = "https://graph.facebook.com/me/accounts?access_token=".$token;
				$pages = json_decode(file_get_contents($graph_url_pages)); // get all pages information from above url.
				$dropdown = "";
				$data['pages']=$pages;
				$data['main_content'] = "admin/".$this->viewName."/facebook_page";
				$this->load->view('admin/include/template', $data);*/
			
		}	
	}
    /*
		@Description: Function for Insert New social campaign data
		@Author: Sanjay Chabhadiya
		@Input: - Details of new social campaign which is inserted into DB
		@Output: - List of social campaign with new inserted records
		@Date: 06-08-2014
    */
   
    public function insert_data()
    {
	 	
		$submit = $this->input->post('submitbtn');
		$platform = $this->input->post('platform');
		
		if(($submit == 'Save Campaign' /*|| $submit == 'Send Now'*/) && $platform != 1)
		{
			$this->data['consumer_key'] = $this->config->item('linkedin_api_key');
			$this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
			$this->load->library('linkedin/linkedin', $this->data);
			$this->load->library('twitteroauth/twitteroauth');
			$data['template_name'] = $this->input->post('template_name');
			
			$data['template_category'] = $this->input->post('slt_category');
			//$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['social_message'] = $this->input->post('social_message');
			
			$cdata1 = $this->input->post('platform');
			$data['social_send_type'] = $this->input->post('chk_is_lead');
			$send_type = $this->input->post('chk_is_lead');
			
			/*if($submit == 'Send Now')
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';*/
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');	
			if($data['social_send_type'] == 2)
			{
				
				$data['social_send_date'] = $this->input->post('send_date');
				$data['social_send_time'] = $this->input->post('send_time');
				$data['is_draft'] = '0';
				if($platform == 3 || in_array('3',$platform))
				{
					 if(count($platform) >= 1)
					{
						$data['platform'] = '3';
					}
					else
					{
						$data['platform'] = $this->input->post('platform');	
					}
					
					 $linkedpage=$this->input->post('linkedin_page');
					 $linkedin_page=explode('_',$linkedpage);
					 $data['page_name'] = !empty($linkedin_page[1])?$linkedin_page[1]:'';
					$social_campaign_id = $this->obj->insert_record($data);	
				}
				if($platform == 2 || in_array('2',$platform))
				{
					if(count($platform) >= 1)
					{
						$data['platform'] = '2';
					}
					else
					{
						$data['platform'] = $this->input->post('platform');	
					}
					$page=$this->input->post('screen_name');
					if(!empty($page))
					{
					$data['page_name'] = trim($this->input->post('screen_name'));
					}
					$social_message =$this->input->post('social_message');
					$social_message =substr($social_message,0,130);
					$data['social_message'] = $social_message;
					//$data['social_type'] = 'Campaign';
					$data['created_by'] = $this->admin_session['id'];
					$data['created_date'] = date('Y-m-d H:i:s');
					
					//$data['status'] = '1';
					$social_campaign_id = $this->obj->insert_record($data);	
				}
			}
			
			if(empty($data['social_send_type']) || $data['social_send_type'] == '1')
			{
				$data['social_send_type'] = 1;
				$data['is_draft'] = '0';
				//send to linked page
				if($platform == 2 || in_array('2',$platform))
				{
					if(count($platform) >= 1)
					{
						$data['platform'] = '2';
					}
					else
					{
						$data['platform'] = $this->input->post('platform');	
					}
					
					
					
					$consumer_key=$this->config->item('twitter_access_key');
					$consumer_secret=$this->config->item('twitter_secret_key');
					
					$field = array('id','twitter_access_token','twitter_access_token_secret');
					$match = array('id'=>$this->admin_session['id']);
					$udata = $this->user_management_model->select_login_records($field, $match,'','='); 
					
					$twitter_access_token=!empty($udata[0]['twitter_access_token'])?$udata[0]['twitter_access_token']:'';
					$twitter_access_token_secret=!empty($udata[0]['twitter_access_token_secret'])?$udata[0]['twitter_access_token_secret']:'';
					
					if(!empty($twitter_access_token) && !empty($twitter_access_token_secret))
					{
						$screen_name = $this->input->post('screen_name');
						$social_message =$this->input->post('social_message');
						$social_message =substr($social_message,0,130);
						$data['social_message'] = $social_message;
						$params4 = array();
						$params4['screen_name'] = $screen_name;
						//$params4['status'] =  "@$screen_name"." ".$social_message;
						$params4['status'] =  $social_message;
						
						
						
						//pr($params4);exit;
						//$content4 = $connection->post('direct_messages/new', $params4);
						$connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret);
						$content4 =  $connection->post('statuses/update',  $params4);
						
						
						//echo $content4->errors[0]->message;exit;
						$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
						if(!empty($error))
						{
							 $msg = $error;
							 $newdata = array('msg'  => $msg);
							 $this->session->set_userdata('message_session', $newdata);
							 redirect('admin/'.$this->viewName);
						}
						else
						{
							$page=$this->input->post('screen_name');
							if(!empty($page))
							{
							$data['page_name'] = trim($this->input->post('screen_name'));
							}
							//$data['social_type'] = 'Campaign';
							$data['created_by'] = $this->admin_session['id'];
							$data['created_date'] = date('Y-m-d H:i:s');
							//$data['status'] = '1';
							$social_campaign_id = $this->obj->insert_record($data);	
						}
					}
					else
					{
					$msg = 'You are not connected with twitter account.Please connect your twitter account.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
					}	
				}
				if($platform == 3 || in_array('3',$platform))
				{
					 if(count($platform) >= 1)
					{
						$data['platform'] = '3';
					}
					else
					{
						$data['platform'] = $this->input->post('platform');	
					}
					 $linkedpage=$this->input->post('linkedin_page');
					 $linkedin_page=explode('_',$linkedpage);
					 
					 $company_id = !empty($linkedin_page[0])?$linkedin_page[0]:'';
					 
					 $field = array('id','linkedin_access_token');
					 $match = array('id'=>$this->admin_session['id']);
					 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
					 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
					 if(!empty($linkedin_access_token) && !empty($company_id))
					 {
						$template_id = $this->input->post('template_name');
						$subject = 'Social message';
						$email_message = $this->input->post('social_message');
						
						$title = "Post on linked in";
						$descreption = "Post on linked in";
						$comment = "$email_message";
						$target_url = base_url();
						$image_url = ""; // optional 
				
						$this->load->library('linkedin', $this->data);
						//echo 'OAuthConsumer[key=786x4nevnmcvc4,secret=Hj8kmrJct9iR2ziE]';
						$status_response = $this->linkedin->share($comment, $title,$descreption, $target_url, $image_url, unserialize($linkedin_access_token),$company_id);
						//pr($status_response);exit;
						if ($status_response= '201')
						{
							$data['page_name'] = !empty($linkedin_page[1])?$linkedin_page[1]:'';
							$social_campaign_id = $this->obj->insert_record($data);	
						}
						else
						{
							$msg = 'Something going wrong.Please try again.';
							$newdata = array('msg'  => $msg);
							$this->session->set_userdata('message_session', $newdata);
							redirect('admin/'.$this->viewName);
						}
					 }
					 else
					 {
							$msg = 'You only have contact information for LinkedIn and you don`t have your LinkedIn account connected.';
							$newdata = array('msg'  => $msg);
							$this->session->set_userdata('message_session', $newdata);
							redirect('admin/'.$this->viewName);
					 }
				}
				//send to twitter handle
				
			}
			//echo $this->db->last_query();exit;
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			
			//redirect('admin/'.$this->viewName);
			/*for($i=0;$i<count($contact_id);$i++)
			{
				$cdata['contact_id'] = $contact_id[$i];
				$this->social_campaign_recepient_trans_model->insert_record($cdata);
			}*/
		}
		elseif($submit == 'Save Template As')
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->socialmedia_post_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			else
				$data['template_name'] = "You social Template";
			
			$data['template_category'] = $this->input->post('slt_category');
			//$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['post_content'] = $this->input->post('social_message');
			//$data['social_type'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->socialmedia_post_model->insert_record($data);
		}
		if($submit == 'Send Now' && $platform == 1)
		{
			$this->load->library('facebook/facebook');
			$callback_url         =   base_url().'admin/social/fbconnection/?fbTrue=true';
			$facebook_api_key=$this->config->item('facebook_api_key');
			$facebook_secret_key=$this->config->item('facebook_secret_key');
			// Create our Application instance (replace this with your appId and secret).
			$facebook = new Facebook(array(
			  'appId'  => $facebook_api_key,
			  'secret' => $facebook_secret_key,
			  'cookie' => true
			));
			
			
				redirect('https://www.facebook.com/dialog/oauth?client_id='.$facebook_api_key.'&redirect_uri='.$callback_url.'&scope=email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages');
				//echo 'Connect &nbsp;&nbsp;<a href="https://www.facebook.com/dialog/oauth?client_id='.$config['App_ID'].'&redirect_uri='.$config['callback_url'].'&scope=email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages"><img src="./images/login-button.png" alt="Sign in with Facebook"/></a>';
			
		}
		/*if($submit == 'Send Now' && $platform == 3)
		{
			 $this->data['consumer_key'] = $this->config->item('linkedin_api_key');
             $this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
			 $company_id = $this->input->post('linkedin_page');
			 
			 $this->load->library('linkedin/linkedin', $this->data);
			 $field = array('id','linkedin_access_token');
			 $match = array('id'=>$this->admin_session['id']);
			 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
			 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
			 if(!empty($linkedin_access_token) && !empty($company_id))
			 {
				 
				$template_id = $this->input->post('template_name');
				$subject = 'Social message';
				$email_message = $this->input->post('social_message');
				
				$title = "Post on linked in";
				$descreption = "Post on linked in";
				$comment = "$email_message";
				$target_url = base_url();
				$image_url = ""; // optional 
		
				$this->load->library('linkedin', $this->data);
				//echo 'OAuthConsumer[key=786x4nevnmcvc4,secret=Hj8kmrJct9iR2ziE]';
				$status_response = $this->linkedin->share($comment, $title,$descreption, $target_url, $image_url, unserialize($linkedin_access_token),$company_id);
				if ($status_response= '201')
    			{
				//pr($status_response);exit;
					$data['template_name'] = $this->input->post('template_name');
					$data['template_category'] = $this->input->post('slt_category');
					//$data['template_subcategory'] = $this->input->post('slt_subcategory');
					$data['social_message'] = $this->input->post('social_message');
					$data['platform'] = $this->input->post('platform');
					$cdata1 = $this->input->post('platform');
					$data['social_send_type'] = $this->input->post('chk_is_lead');
					$send_type = $this->input->post('chk_is_lead');
					$page=$this->input->post('page_name');
					if(!empty($page))
					{
					$data['page_name'] = trim($this->input->post('page_name'));
					}
					if($submit == 'Send Now')
						$data['is_draft'] = '0';
					else
						$data['is_draft'] = '1';
						
					if($data['social_send_type'] == 2)
					{
						$data['social_send_date'] = $this->input->post('send_date');
						$data['social_send_time'] = $this->input->post('send_time');
						$data['is_draft'] = '0';
					}
					
					if(empty($data['social_send_type']) && $submit == 'Send Now')
					{
						$data['social_send_type'] = 1;
					}
						
					//$data['social_type'] = 'Campaign';
					$data['created_by'] = $this->admin_session['id'];
					$data['created_date'] = date('Y-m-d H:i:s');
							
					//$data['status'] = '1';
					$social_campaign_id = $this->obj->insert_record($data);	
				}
				else
				{
					$msg = 'Something going wrong.Please try again.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
				}
			 
			 }
			 else
			 {
				 	$msg = 'You only have contact information for LinkedIn and you don`t have your LinkedIn account connected.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
			 }	
		}
		if($submit == 'Send Now' && $platform == 2)
		{
			   
			    $this->load->library('twitteroauth/twitteroauth');
				 $consumer_key=$this->config->item('twitter_access_key');
				 $consumer_secret=$this->config->item('twitter_secret_key');
				$field = array('id','twitter_access_token','twitter_access_token_secret');
				$match = array('id'=>$this->admin_session['id']);
				$udata = $this->user_management_model->select_login_records($field, $match,'','='); 
				
				$twitter_access_token=!empty($udata[0]['twitter_access_token'])?$udata[0]['twitter_access_token']:'';
				$twitter_access_token_secret=!empty($udata[0]['twitter_access_token_secret'])?$udata[0]['twitter_access_token_secret']:'';
				
				if(!empty($twitter_access_token) && !empty($twitter_access_token_secret))
		        {
					$screen_name = $this->input->post('screen_name');
					$social_message =$this->input->post('social_message');
					$social_message =substr($social_message,0,130);
					$params4 = array();
					$params4['screen_name'] = $screen_name;
					$params4['status'] =  "@$screen_name"." ".$social_message;
			//pr($params4);exit;
					//$content4 = $connection->post('direct_messages/new', $params4);
					$connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret);
					$content4 =  $connection->post('statuses/update',  $params4);
					
				
					//echo $content4->errors[0]->message;exit;
					$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
					if(!empty($error))
					{
						 $msg = $error;
						 $newdata = array('msg'  => $msg);
						 $this->session->set_userdata('message_session', $newdata);
						 redirect('admin/'.$this->viewName);
					}
					else
					{
						$data['template_name'] = $this->input->post('template_name');
						$data['template_category'] = $this->input->post('slt_category');
						//$data['template_subcategory'] = $this->input->post('slt_subcategory');
						$data['social_message'] = $social_message;
						$data['platform'] = $this->input->post('platform');
						$data['social_send_type'] = $this->input->post('chk_is_lead');
						$send_type = $this->input->post('chk_is_lead');
						$page=$this->input->post('screen_name');
						if(!empty($page))
						{
						$data['page_name'] = trim($this->input->post('screen_name'));
						}
						if($submit == 'Send Now')
							$data['is_draft'] = '0';
						else
							$data['is_draft'] = '1';
							
						if($data['social_send_type'] == 2)
						{
							$data['social_send_date'] = $this->input->post('send_date');
							$data['social_send_time'] = $this->input->post('send_time');
							$data['is_draft'] = '0';
						}
						
						if(empty($data['social_send_type']) && $submit == 'Send Now')
						{
							$data['social_send_type'] = 1;
						}
							
						//$data['social_type'] = 'Campaign';
						$data['created_by'] = $this->admin_session['id'];
						$data['created_date'] = date('Y-m-d H:i:s');
						
						//$data['status'] = '1';
						$social_campaign_id = $this->obj->insert_record($data);	
					}
		        }
				else
				{
					$msg = 'You are not connected with twitter account.Please connect your twitter account.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
				}
		}*/
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $social_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('social_sortsearchpage_data', $social_sortsearchpage_data);
		redirect('admin/'.$this->viewName);
		
    }
 
 	public function facebook_contact()
	{
		$contact_id = $this->input->post('contactlist');
		if(!empty($contact_id))
		{
			$fields = array('id,fb_id');
			$match = array('id'=>$contact_id);
			$result = $this->contacts_model->select_records($fields,$match,'','=');
			if(count($result) > 0 && !empty($result[0]['fb_id']))
				echo $result[0]['fb_id'];
			else
				echo 0;
		}
		else
			echo 0;
		//if()
	}
	 public function linkedin_contact()
	 {
		 //get linked in access token
		 
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->admin_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		 if(!empty($linkedin_access_token))
		 {
			 
		 $template_id = $this->input->post('template_id');
		 if(!empty($template_id) && $template_id != '-1')
			{
				$match = array("id"=>$template_id);
				$templatedata = $this->socialmedia_post_model->select_records('',$match,'','=','','','','id','desc');
			//	pr($cdata['templatedata']);exit;
				$tmp_msg=$templatedata[0]['post_content'];
				
			}
		 $contactid = $this->input->post('contactlist');
		 $contact_id=explode(',',$contactid);
		 
		 if(!empty($contact_id))
		 {
			foreach($contact_id as $row)
			{
				$fields = array('id,linkedin_id');
				$match = array('id'=>$row);
				$result = $this->contacts_model->select_records($fields,$match,'','=');	 
				$linkedin_id[]=!empty($result[0]['linkedin_id'])?$result[0]['linkedin_id']:'';
				
			}
			if(!empty($linkedin_id))
			{
				$okdata=$this->message('Subject social',$tmp_msg, $linkedin_id);
				echo $linkedin_access_token;
				pr($linkedin_id);	exit;
				echo 'done';
			}
		}
		 }
		 else
		 {
				echo 'You only have contact information for LinkedIn and you don`t have your LinkedIn account connected.';	 
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
	
	$response = $this->fetch('GET','/v1/people/~/mailbox', $xml->saveXML());
	
	return ($response);
	}
	/*
		@Description: Function for Fetch data from linked in api
		@Author: Niral Patel
		@Input: - Get data
		@Output: - 
		@Date: 06-08-2014
   	*/
	function fetch($method, $resource, $body = '') 
	 {
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->admin_session['id']);
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
		@Description: Function for Fetch data from linked in api
		@Author: Niral Patel
		@Input: - Get data
		@Output: - 
		@Date: 06-08-2014
   	*/
	function fetchcompany($method, $resource, $body = '') 
	 {
		 $field = array('id','linkedin_access_token');
		 $match = array('id'=>$this->admin_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		$params = array('oauth2_access_token' => $linkedin_access_token,
		'format' => 'json',
		'count'=>'100',
		'is-company-admin'=>'true'
		);
		
		// Need to use HTTPS
		$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
		// Tell streams to make a (GET, POST, PUT, or DELETE) request
		
		$response = file_get_contents($url);
		//pr($response);exit;
		// Native PHP object, please
		return json_decode($response);
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
				$content = $data['social_message'];
				$cdata['social_message'] = $content;
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
					$cdata['social_message'] = $finlaOutput;
				}
				//pr($cdata);exit;
				$this->obj->insert_social_recepient_trans($cdata);
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
		
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_sms');
        $match = array('id'=>$admin_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		$sms_data['flag'] = 1;
		$sms_data['send_sms_count'] = $send_sms_count;
		if(count($udata) > 0)
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
			$wherestring = "cpt.is_default = '1'";
			
			$where_in = array('cm.id'=>$contact_id);
			$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			//echo 
			//pr($from_data);exit;
			if(count($from_data) > 0)
			{	
				foreach($from_data as $row)
				{
					if($remain_sms == 0)
					{
						$email_data['flag'] = 2;
						$datac['is_sent_to_all'] = '0';
						$datac['total_sent'] = $send_sms_count;
						$datac['id'] = $cdata['sms_campaign_id'];
						$this->obj->update_record($datac);
						//echo $this->db->last_query();
						//exit;
					}
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
					if($remain_sms == 0)
						$cdata['is_send'] = '0';
					else
					{
						//$to = '+919033921029';
						//$response = $this->twilio->sms($from, $to, $message);
						
						$cdata['is_send'] = '1';
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$remain_sms--;
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
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
						
					}
					
					$this->obj->insert_sms_campaign_recepient_trans($cdata);
				}
			}
			
		}
		//echo $remain_sms;
		
		$idata['id'] = $this->admin_session['id'];
		$sms_data['send_sms_count'] = $send_sms_count;
		if(isset($remain_sms))
			$idata['remain_sms'] = $remain_sms;
		$udata = $this->admin_model->update_user($idata);
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
     	$id = $this->uri->segment(4);
		$cdata['send_now'] = $this->uri->segment(5);
		
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		//pr($result );exit;
		$cdata['editRecord'] = $result;
		
		if(count($result) > 0)
		{
			if(($result[0]['is_draft'] == 0 && $result[0]['social_send_date'] == '0000-00-00')) {
				redirect(base_url('admin/'.$this->viewName));
			}
		}
		else
		{
			redirect(base_url('admin/'.$this->viewName));
		}
		
		
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		/*$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id'
						);
		$group_by='cm.id';*/

		$social_campaign_id = $result[0]['id'];
		
		$social = $this->obj->select_social_campaign_recepient_trans($social_campaign_id);
		
		$i=0;
		$contact_type_to = '';
		
		foreach($social as $row)
		{
			if(empty($row['contact_type']) && $row['contact_type'] == 0)
				$email_to[$i] = $row['contact_id'];
			else
			{
				if(!empty($contact_type_to))
				{
					if(!in_array($row['contact_type'],$contact_type_to))
						$contact_type_to[$i] = $row['contact_type'];
				}
			}
			$i++;
		}
		
		if(!empty($email_to))
		{
			$cdata['select_to'] = implode(",",$email_to);
			$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
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
		$config['base_url'] = site_url($this->user_type.'/'."social/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config['total_rows'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc','1');
		$this->pagination->initialize($config);
		$cdata['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////	
		
		//////////////////////////////////// Contact ////////////////////////////////////////////
		
		$config_to1['per_page'] = '10';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'."social/search_contact_to");
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
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		$where = array('cet.is_default'=>"'1'");
		$cdata['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		//pr($where);
		$cdata['contact'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		//pr($cdata['contact']);exit;
		
		$this->pagination->initialize($config_to1);
		$cdata['pagination_contact_to'] = $this->pagination->create_links();
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address','cm.fb_id','cm.created_type','cm.linkedin_id');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$where = array('cm.created_type'=>"'4'",'cm.linkedin_id !='=>"''");
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$cdata['contact_linkedin'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.first_name','asc',$group_by,$where);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address','cm.fb_id','cm.created_type','cm.linkedin_id');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$where = array('cm.fb_id !='=>"''");
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$cdata['contact_fb'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','cm.first_name','asc',$group_by,$where);
		
		////////////////////////////////////////////////////////////////////////////////////	

		$table1='custom_field_master';
		$where1=array('module_id'=>'2');
		$cdata['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		//$data['tablefield_data']=$this->email_library_model->select_records3();

		$match = array();
		$cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$match = array('social_template_id'=>$id);
		$result1 = $this->obj->select_records1('',$match,'','=');
		$app = array();
		foreach($result1 as $data)
		{
			$app[] = $data['platform'];
		}
		$cdata['profile_type'] = $this->obj1->select_records1('','','','','','','','name','asc','contact__social_type_master');
		$cdata['editRecord1']= $app;
		//Get linked in page
		 $field = array('id','linkedin_access_token','linkedin_secret_access_token');
		 $match = array('id'=>$this->admin_session['id']);
		 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
		 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
		if(!empty($linkedin_access_token))
		 {
			/*$fields = array(
			'id',
            'name',
			'description',
			'industry',
			'logo-url'
			);
			$request = join(',',$fields);
			
			$response = $this->fetchcompany("POST","/v1/companies:({$request})");
			$data['linkedin_page']=$response->values;*/
			
			$this->data['consumer_key'] = $this->config->item('linkedin_api_key');
			$this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
			
			$this->load->library('linkedin/linkedin', $this->data);
			$com=$this->linkedin->getcompany(unserialize($linkedin_access_token));
			
			$xml = simplexml_load_string($com);
			$json = json_encode($xml);
			$company = json_decode($json,TRUE);
			$cdata['linkedin_page']=$company['company'];
			
		 }
		//$cdata['profile_trans_data'] = $this->contacts_model->select_social_name_twitter($this->admin_session['id']);
		$field = array('id','twitter_id','linkedin_access_token','twitter_username');
		$match = array('id'=>$this->admin_session['id']);
		$udata = $this->admin_model->get_user($field, $match,'','=');
		if(!empty($udata))
		{
			$field1 = array('id','twitter_handle','twitter_id');
			$match = array('created_by'=>$this->admin_session['id'],'twitter_user_id'=>$udata[0]['twitter_id']);
			$twitter_social = $this->contacts_model->select_records4($field1,$match,'','=','','','','id','desc');
			$cdata['profile_trans_data']=$twitter_social;
			$cdata['profile_user_data']=$udata[0]['twitter_username'];
			//pr($data['twitter_social']);exit;
		}	
		
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }

    /*
		@Description: Function for Update data
		@Author: Sanjay Chabhadiya
		@Input: - Update details of social campaign
		@Output: - List with updated social campaign details
		@Date: 06-08-2014
    */
   
    public function update_data()
    {
     //	 pr($_POST);exit;
        $id = $this->input->post('id');
		$tmp_id = $this->input->post('id');
        $submit = $this->input->post('submitbtn');
		$platform = $this->input->post('platform');
		
        if($submit == 'Save Campaign' /*|| $submit == 'Send Now'*/)
        {
				
                $result = $this->social_recepient_trans_model->delete_record($id);
                $data['id'] = $id;
                $data['template_name'] = $this->input->post('template_name');
                $data['template_category'] = $this->input->post('slt_category');
                //$data['template_subcategory'] = $this->input->post('slt_subcategory');
				$data['platform'] = $this->input->post('platform');
                $data['social_message'] = $this->input->post('social_message');
                $data['social_send_type'] = $this->input->post('chk_is_lead');
                $send_type = $this->input->post('chk_is_lead');
				$this->data['consumer_key'] = $this->config->item('linkedin_api_key');
				$this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
				$this->load->library('linkedin/linkedin', $this->data);
				$this->load->library('twitteroauth/twitteroauth');
				if($platform == 3)
				{
					$linkedpage=$this->input->post('linkedin_page');
					$linkedin_page=explode('_',$linkedpage);
					$data['page_name'] = !empty($linkedin_page[1])?$linkedin_page[1]:'';
				}
				if($platform == 2)
				{
					$page=$this->input->post('screen_name');
					if(!empty($page))
					{
					$data['page_name'] = trim($this->input->post('screen_name'));
					}
				}
              	$data['created_by'] = $this->admin_session['id'];
                $data['created_date'] = date('Y-m-d H:i:s');		
                $data['status'] = '1';

                if($data['social_send_type'] == 2)
                {
                        $data['social_send_date'] = $this->input->post('send_date');
                        $data['social_send_time'] = $this->input->post('send_time');
                        $data['is_draft'] = '0';
						if($platform == 3)
						{
							$this->obj->update_record($data);	
						}
						if($platform == 2)
						{
							$page=$this->input->post('screen_name');
							if(!empty($page))
							{
							$data['page_name'] = trim($this->input->post('screen_name'));
							}
							$social_message =$this->input->post('social_message');
							$social_message =substr($social_message,0,130);
							$data['social_message'] = $social_message;
							//$data['social_type'] = 'Campaign';
							$data['created_by'] = $this->admin_session['id'];
							$data['created_date'] = date('Y-m-d H:i:s');
							$this->obj->update_record($data);	
							//$data['status'] = '1';
							
						}
                }
                else
				{
					$data['social_send_date'] = '';
					$data['social_send_time'] = '';
					if(empty($data['social_send_type']) || $data['social_send_type'] == '1')
					{
						$data['social_send_type'] = '1';
						$data['is_draft'] = '0';
						//send to linked page
						if($platform == 3)
						{
							 $linkedpage=$this->input->post('linkedin_page');
							 $linkedin_page=explode('_',$linkedpage);
							 
							 $company_id = !empty($linkedin_page[0])?$linkedin_page[0]:'';
							 $field = array('id','linkedin_access_token');
							 $match = array('id'=>$this->admin_session['id']);
							 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
							 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
							 if(!empty($linkedin_access_token) && !empty($company_id))
							 {
								 
								$template_id = $this->input->post('template_name');
								$subject = 'Social message';
								$email_message = $this->input->post('social_message');
								
								$title = "Post on linked in";
								$descreption = "Post on linked in";
								$comment = "$email_message";
								$target_url = base_url();
								$image_url = ""; // optional 
						
								$this->load->library('linkedin', $this->data);
								//echo 'OAuthConsumer[key=786x4nevnmcvc4,secret=Hj8kmrJct9iR2ziE]';
								$status_response = $this->linkedin->share($comment, $title,$descreption, $target_url, $image_url, unserialize($linkedin_access_token),$company_id);
								//pr($status_response);exit;
								if ($status_response= '201')
								{
									$this->obj->update_record($data);	
								}
								else
								{
									$msg = 'Something going wrong.Please try again.';
									$newdata = array('msg'  => $msg);
									$this->session->set_userdata('message_session', $newdata);
									redirect('admin/'.$this->viewName);
								}
							 }
							 else
							 {
									$msg = 'You only have contact information for LinkedIn and you don`t have your LinkedIn account connected.';
									$newdata = array('msg'  => $msg);
									$this->session->set_userdata('message_session', $newdata);
									redirect('admin/'.$this->viewName);
							 }
						}
						//send to twitter handle
						if($platform == 2)
						{
							$consumer_key=$this->config->item('twitter_access_key');
							$consumer_secret=$this->config->item('twitter_secret_key');
							$field = array('id','twitter_access_token','twitter_access_token_secret');
							$match = array('id'=>$this->admin_session['id']);
							$udata = $this->user_management_model->select_login_records($field, $match,'','='); 
							
							$twitter_access_token=!empty($udata[0]['twitter_access_token'])?$udata[0]['twitter_access_token']:'';
							$twitter_access_token_secret=!empty($udata[0]['twitter_access_token_secret'])?$udata[0]['twitter_access_token_secret']:'';
							
							if(!empty($twitter_access_token) && !empty($twitter_access_token_secret))
							{
								$screen_name = $this->input->post('screen_name');
								$social_message =$this->input->post('social_message');
								$social_message =substr($social_message,0,130);
								$data['social_message'] = $social_message;
								$params4 = array();
								$params4['screen_name'] = $screen_name;
								$params4['status'] =  "@$screen_name"." ".$social_message;
								//pr($params4);exit;
								//$content4 = $connection->post('direct_messages/new', $params4);
								$connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret);
								$content4 =  $connection->post('statuses/update',  $params4);
								
								
								//echo $content4->errors[0]->message;exit;
								$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
								
								if(!empty($error))
								{
									 $msg = $error;
									 $newdata = array('msg'  => $msg);
									 $this->session->set_userdata('message_session', $newdata);
									 redirect('admin/'.$this->viewName);
								}
								else
								{
									$page=$this->input->post('screen_name');
									if(!empty($page))
									{
									$data['page_name'] = trim($this->input->post('screen_name'));
									}
									//$data['social_type'] = 'Campaign';
									$data['created_by'] = $this->admin_session['id'];
									$data['created_date'] = date('Y-m-d H:i:s');
									
									//$data['status'] = '1';
									$this->obj->update_record($data);	
								}
							}
							else
							{
							$msg = 'You are not connected with twitter account.Please connect your twitter account.';
							$newdata = array('msg'  => $msg);
							$this->session->set_userdata('message_session', $newdata);
							redirect('admin/'.$this->viewName);
							}	
						}
					}
				}

                $msg = $this->lang->line('common_add_success_msg');
                $newdata = array('msg'  => $msg);
                $this->session->set_userdata('message_session', $newdata);	
                redirect('admin/'.$this->viewName);
                /*for($i=0;$i<count($contact_id);$i++)
                {
                        $cdata['contact_id'] = $contact_id[$i];
                        $this->social_campaign_recepient_trans_model->insert_record($cdata);
                }*/
        }
        elseif($submit == 'Save Template As')
        {
			if($this->input->post('template_name'))
			{
					$match = array('id'=>$this->input->post('template_name'));
					$result = $this->socialmedia_post_model->select_records('',$match,'','=');
					if(count($result) > 0)
							$data['template_name'] = $result[0]['template_name'];
			}
			else
				$data['template_name'] = "You social Template";
			
			$data['template_category'] = $this->input->post('slt_category');
			//$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['post_content'] = $this->input->post('social_message');
			
			//$data['social_type'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->socialmedia_post_model->insert_record($data);
        }
		/*if($submit == 'Send Now' && $platform == 3)
		{//echo 546;exit;
			 $this->data['consumer_key'] = $this->config->item('linkedin_api_key');
             $this->data['consumer_secret'] = $this->config->item('linkedin_secret_key');
			 $company_id = $this->input->post('linkedin_page');
			 
			 $this->load->library('linkedin/linkedin', $this->data);
			 $field = array('id','linkedin_access_token');
			 $match = array('id'=>$this->admin_session['id']);
			 $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
			 $linkedin_access_token=!empty($udata[0]['linkedin_access_token'])?$udata[0]['linkedin_access_token']:'';
			 if(!empty($linkedin_access_token) && !empty($company_id))
			 {
				
				$template_id = $this->input->post('template_name');
				$subject = 'Social message';
				$email_message = $this->input->post('social_message');
				
				$title = "Post on linked in";
				$descreption = "Post on linked in";
				$comment = trim($email_message);
				$target_url = base_url();
				$image_url = ""; // optional 
		
				$this->load->library('linkedin', $this->data);
				//echo 'OAuthConsumer[key=786x4nevnmcvc4,secret=Hj8kmrJct9iR2ziE]';
				$status_response = $this->linkedin->share($comment, $title,$descreption, $target_url, $image_url, unserialize($linkedin_access_token),$company_id);
				
				if ($status_response == '201')
    			{
					 $data['id'] = $id;
					$data['template_name'] = $this->input->post('template_name');
					$data['template_category'] = $this->input->post('slt_category');
					//$data['template_subcategory'] = $this->input->post('slt_subcategory');
					$data['social_message'] = $this->input->post('social_message');
					$data['platform'] = $this->input->post('platform');
					$cdata1 = $this->input->post('platform');
					$data['social_send_type'] = $this->input->post('chk_is_lead');
					$send_type = $this->input->post('chk_is_lead');
					$page=$this->input->post('page_name');
					if(!empty($page))
					{
					$data['page_name'] = trim($this->input->post('page_name'));
					}
					if($submit == 'Send Now')
						$data['is_draft'] = '0';
					else
						$data['is_draft'] = '1';
						
					if($data['social_send_type'] == 2)
					{
						$data['social_send_date'] = $this->input->post('send_date');
						$data['social_send_time'] = $this->input->post('send_time');
						$data['is_draft'] = '0';
					}
					else
					{
						$data['social_send_date'] = '';
						$data['social_send_time'] = '';
					}
					if(empty($data['social_send_type']) && $submit == 'Send Now')
					{
						$data['social_send_type'] = 1;
					}
						
					//$data['social_type'] = 'Campaign';
					$data['created_by'] = $this->admin_session['id'];
					$data['created_date'] = date('Y-m-d H:i:s');
							
					//$data['status'] = '1';
					 $this->obj->update_record($data);	
					
				}
				else
				{
					$msg = 'Something going wrong.Please try again.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
				}
			 
			 }
			 else
			 {
				 	 $msg = 'You are not connected with linked in account.Please connect your linked in account';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
			 }	
		
		}
		if($submit == 'Send Now' && $platform == 2)
		{
			   
			    $this->load->library('twitteroauth/twitteroauth');
				 $consumer_key=$this->config->item('twitter_access_key');
				 $consumer_secret=$this->config->item('twitter_secret_key');
				$field = array('id','twitter_access_token','twitter_access_token_secret');
				$match = array('id'=>$this->admin_session['id']);
				$udata = $this->user_management_model->select_login_records($field, $match,'','='); 
				
				$twitter_access_token=!empty($udata[0]['twitter_access_token'])?$udata[0]['twitter_access_token']:'';
				$twitter_access_token_secret=!empty($udata[0]['twitter_access_token_secret'])?$udata[0]['twitter_access_token_secret']:'';
				
				if(!empty($twitter_access_token) && !empty($twitter_access_token_secret))
		        {
					$screen_name = $this->input->post('screen_name');
					$social_message =$this->input->post('social_message');
					$social_message =substr($social_message,0,130);
					$params4 = array();
					$params4['screen_name'] = $screen_name;
					$params4['status'] =  "@$screen_name"." ".$social_message;
			//pr($params4);exit;
					//$content4 = $connection->post('direct_messages/new', $params4);
					$connection = new TwitterOAuth($consumer_key, $consumer_secret, $twitter_access_token, $twitter_access_token_secret);
					$content4 =  $connection->post('statuses/update',  $params4);
					
				
					//echo $content4->errors[0]->message;exit;
					$error=!empty($content4->errors[0]->message)?$content4->errors[0]->message:'';
					if(!empty($error))
					{
						 $msg = $error;
						 $newdata = array('msg'  => $msg);
						 $this->session->set_userdata('message_session', $newdata);
						 redirect('admin/'.$this->viewName);
					}
					else
					{
						$data['id'] = $id;
						$data['template_name'] = $this->input->post('template_name');
						$data['template_category'] = $this->input->post('slt_category');
						//$data['template_subcategory'] = $this->input->post('slt_subcategory');
						$data['social_message'] = $social_message;
						$data['platform'] = $this->input->post('platform');
						$data['social_send_type'] = $this->input->post('chk_is_lead');
						$send_type = $this->input->post('chk_is_lead');
						$page=$this->input->post('screen_name');
						if(!empty($page))
						{
						$data['page_name'] = trim($this->input->post('screen_name'));
						}
						if($submit == 'Send Now')
							$data['is_draft'] = '0';
						else
							$data['is_draft'] = '1';
							
						if($data['social_send_type'] == 2)
						{
							$data['social_send_date'] = $this->input->post('send_date');
							$data['social_send_time'] = $this->input->post('send_time');
							$data['is_draft'] = '0';
						}
						else
						{
							$data['social_send_date'] = '';
							$data['social_send_time'] = '';
						}
						if(empty($data['social_send_type']) && $submit == 'Send Now')
						{
							$data['social_send_type'] = 1;
						}
							
						//$data['social_type'] = 'Campaign';
						$data['created_by'] = $this->admin_session['id'];
						$data['created_date'] = date('Y-m-d H:i:s');
						
						//$data['status'] = '1';
						 $this->obj->update_record($data);	
					}
		        }
				else
				{
					$msg = 'You are not connected with twitter account.Please connect your twitter account.';
					$newdata = array('msg'  => $msg);
					$this->session->set_userdata('message_session', $newdata);
					redirect('admin/'.$this->viewName);
				}
		}*/
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        //$pagingid = $this->obj->getpagingid($id);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('social_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect('admin/'.$this->viewName.'/'.$pagingid);
		
    }
	
   	/*
		@Description: Function for Delete Task Profile By Admin
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to delete
		@Output: - New Task list after record is deleted.
		@Date: 06-08-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$this->obj->delete_email_campaign_recepient_trans($id);
		$this->obj->delete_email_campaign_attachments($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	/*
		@Description: Function for social campaign delete
		@Author: Sanjay Chabhadiya
		@Input: - Delete all id of social campaign record want to delete
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
			$this->social_recepient_trans_model->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			$id = $array_data[0];
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				//$this->social_recepient_trans_model->delete_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		//$pagingid = $this->obj->getpagingid($id);
		$searchsort_session = $this->session->userdata('social_sortsearchpage_data');
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
		redirect('admin/'.$this->viewName);
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
		redirect('admin/'.$this->viewName);
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
			$match = array("template_category"=>$category_id);
        	$cdata['templatedata'] = $this->socialmedia_post_model->select_records('',$match,'','=','','','','id','desc');
			echo json_encode($cdata['templatedata']);
		}
		
	}
	
	/*
		@Description: Function for social template data
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
        	$cdata['templatedata'] = $this->socialmedia_post_model->select_records('',$match,'','=','','','','id','desc');
		//	pr($cdata['templatedata']);exit;
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
		$config['base_url'] = site_url($this->user_type.'/'."social/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		//echo $this->db->last_query(); exit;	
		$config['total_rows'] = $this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc','1');
		
		
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	/*
		@Description: Function for sent social list from social campaign id wise
		@Author: Sanjay Chabhadiya
		@Input: - social campaign id
		@Output: - Sent social List 
		@Date: 06-08-2014
   	*/
	
	public function sent_social()
	{
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;
		//pr($data['editRecord']); exit;
		$data['campaign_id'] = $id ;
		$datalist = '';
		
		if(count($result) > 0)
		{
			$social_campaign_id = $result[0]['id'];
			$match = array('social_campaign_id'=>$social_campaign_id);
			$total_cnt = $this->social_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','1');
			$match = array('social_campaign_id'=>$social_campaign_id,'is_send'=>'1');
			$sent_cnt = $this->social_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','1');
			//echo $this->db->last_query();
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
                    $this->session->unset_userdata('sent_social_sortsearchpage_data');
                }
                $data['sortfield']		= 'sct.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('sent_social_sortsearchpage_data');
		
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
		$config['base_url'] = site_url($this->user_type.'/'."social/sent_social/".$id);
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 5;
		//$uri_segment = $this->uri->segment(5);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
                }
		$table ="contact_master as cm";
		$fields = array('cm.*,sct.id as ID,sct.is_send,sct.social_message,sct.sent_date');
		$join_tables = array('social_campaign_recepient_trans as sct'=>'sct.contact_id = cm.id');
		$wherestring = "sct.social_campaign_id = ".$social_campaign_id; //." AND is_send = '1'";
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
                
        $sent_social_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

		$this->session->set_userdata('sent_social_sortsearchpage_data', $sent_social_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;

		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_sent_social',$data);
		}
		else
		{	
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/sent_social_list";
			$this->load->view('admin/include/template',$data);
		}
		
	}
	
	/*
		@Description: Function for sent social view data
		@Author: Sanjay Chabhadiya
		@Input: - social campaign id and contact id
		@Output: - Sent social view data 
		@Date: 06-08-2014
   	*/
	
	public function view_data()
	{
		$data['campaign_id'] = $this->uri->segment(4);
		//$data['id'] = $this->uri->segment(5);
		//$pageid = $this->uri->segment(5);
		
		$table = "social_master scm";
		$fields = array("mml1.category as category,mml2.category as subcategory,scm.*,stm.template_name");
		$join_tables = array('marketing_master_lib__category_master mml1'=>'mml1.id = scm.template_category',
							'marketing_master_lib__category_master mml2'=>'mml2.id = scm.template_subcategory',
							'social_media_template_master stm'=>'stm.id = scm.template_name');
		$wherestring = 'scm.id = '.$data['campaign_id'];
	
		$cdata['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
		
		if($this->uri->segment(4) != '')
		{
                    $searchsort_session = $this->session->userdata('social_sortsearchpage_data');
                    $pagingid = $searchsort_session['uri_segment'];
		}
		else
		{
                    $searchsort_session = $this->session->userdata('social_sortsearchpage_data');
                    $pagingid = $searchsort_session['uri_segment'];
		}
		
		$cdata['pagingid'] = $pagingid;
		//pr($cdata['datalist']); exit;
		$cdata['main_content'] =  $this->user_type.'/'.$this->viewName."/view_data";
		$this->load->view('admin/include/template',$cdata);
	}
	
	
	/*
		@Description: Function for resend social single or multiple
		@Author: Sanjay Chabhadiya
		@Input: - social campaign id or social campaign transaction id
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function resend_social()
	{
		$from = $this->config->item('from_social');
		
		$id[0] = $this->input->post('single_id');
		$campaign_id = $this->uri->segment(4);
		$page = $this->uri->segment(5);
		$flag = 0;
		if(!empty($id[0]))
			$contact_id = $id;
		else
		{
			$match = array('social_campaign_id'=>$campaign_id,'is_send'=>'0');
			$result = $this->social_campaign_recepient_trans_model->select_records('',$match,'','=');
			//echo $this->db->last_query();
			$i=0;
			$flag = 1;
			foreach($result as $row)
			{
				$contact_id[$i] = $row['id'];
				$i++;
				
			}
		}
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_social');
        $match = array('id'=>$admin_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		//$email_data['flag'] = 1;
		if(count($udata) > 0)
		{
			$remain_social = $udata[0]['remain_social'];
			if($remain_social == 0)
			{
				$social_data['flag'] = 2;
			}
		}
		$fields = '';
		$join_tables = '';
		//$where_in = '';
		//$remain_emails = 5;
		//$send_mail_count = 0;
		if(!empty($contact_id))
		{
			$table ="social_campaign_recepient_trans as scr";
			$fields = array('cpt.phone_no,scr.id as ID,scr.social_message,scr.social_campaign_id,scr.contact_id,scr.contact_id,cm.*');
			$join_tables = array('contact_phone_trans cpt'=>'cpt.contact_id = scr.contact_id','contact_master cm'=>'cm.id = scr.contact_id');
			
			$group_by = 'cpt.contact_id';
			$wherestring = "cpt.is_default = '1' AND scr.is_send = '0' AND scr.social_campaign_id = ".$campaign_id;
			//$match = array('ecm.id'=>$email_campaign_id);
			$where_in = array('scr.id'=>$contact_id);
			$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			
			$match = array('id'=>$campaign_id);
			$campaign_data = $this->obj->select_records('',$match,'','=');
			
			$message = '';
			if(count($campaign_data) > 0)
			{
				$send_social_count = !empty($campaign_data[0]['total_sent'])?$campaign_data[0]['total_sent']:'';
				$datac['id'] = !empty($campaign_data[0]['id'])?$campaign_data[0]['id']:'';
			}
			//pr($datalist);exit;
			foreach($datalist as $row)
			{
				$cdata['id'] = $row['ID'];
				if($remain_social == 0)
				{
					break;
				}
				$message = $row['social_message'];
				if($remain_social != 0){
					$to = '+919033921029';
					//$to = $row['phone_no'];
					$response = $this->twilio->social($from, $to, $message);
				}
				if($remain_social == 0)
					$cdata['is_send'] = '0';
				else
				{
					$cdata['sent_date'] = date('Y-m-d H:i:s');
					$cdata['is_send'] = '1'; 
					$remain_social--;
					$send_social_count++;

					if(!empty($campaign_data))
					{
						$contact_conversation['contact_id'] = $row['contact_id'];
						$contact_conversation['log_type'] = 8;
						$contact_conversation['campaign_id'] = $campaign_data[0]['id'];
						$contact_conversation['social_camp_template_id'] = $campaign_data[0]['template_name'];

						if(!empty($campaign_data[0]['template_name']))
						{
							$match = array('id'=>$campaign_data[0]['template_name']);
							$template_data = $this->socialmedia_post_model->select_records('',$match,'','=');
							if(count($template_data) > 0)
							{
								$contact_conversation['social_camp_template_name'] = $template_data[0]['template_name'];
							}
						}
						
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
					}
				}
				$this->social_campaign_recepient_trans_model->update_record($cdata);
			}
		}
		//echo $send_social_count; exit;
		$match = array('social_campaign_id'=>$campaign_id);
		$sent_cnt = $this->social_campaign_recepient_trans_model->select_records('',$match,'','=','','','','','','1');
		//echo $this->db->last_query();
		if($sent_cnt == $send_social_count)
		{
			$datac['is_draft'] = '0';
			$datac['is_sent_to_all'] = '1';
			$datac['total_sent'] = 0;
		}
		else
		{
			$datac['is_sent_to_all'] = '0';
			$datac['total_sent'] = $send_social_count;
		}
		$datac['id'] = $campaign_id;
		$this->obj->update_record($datac);
		$idata['id'] = $this->admin_session['id'];
		$social_data['send_social_count'] = $send_mail_count;
		if(isset($remain_social))
			$idata['remain_social'] = $remain_social;
		$udata = $this->admin_model->update_user($idata);
		
		if($flag == 1)
			redirect('admin/'.$this->viewName);
		else
			echo "/".$campaign_id."/".$page;
	}
	
	/*
		@Description: Function for Get All sent social  List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all sent social list
		@Date: 30-08-2014
    */

	
	public function all_sent_social()
	{	
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('all_sent_social_sortsearchpage_data');
                }
                $data['sortfield']		= 'scr.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('all_sent_social_sortsearchpage_data');
		
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
						$sortfield = $data['sortfield'];
						$sortby = $data['sortby'];
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
                           /* $data['searchtext'] = $searchsort_session['searchtext'];
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
		$config['base_url'] = site_url($this->user_type.'/social/'."all_sent_social/");
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
		
		$table ="social_master as scm";   
		$fields = array('scr.social_campaign_id,stm.template_name,scr.id,scr.social_message','cm.first_name,cm.last_name','scr.sent_date');
		$join_tables = array('social_recepient_trans as scr'=>'scr.social_campaign_id = scm.id',
							 'contact_master as cm jointype direct'=>'cm.id = scr.contact_id',
							 'social_media_template_master as stm'=>'stm.id = scm.template_name'
							 /*'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
*/							 );
							 
		$wherestring = "scr.is_send = '1'";
		
		if(!empty($searchtext))
		{
			$concat = "CONCAT_WS(' ',cm.first_name,cm.last_name)";
			$interaction_plan = "CONCAT_WS(' >> ',ipm.plan_name,ipi.description)";
			$match=array('stm.template_name'=>$searchtext,'scr.social_message'=>$searchtext,$concat=>$searchtext,$interaction_plan=>$searchtext);
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

	    $all_sent_social_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
		$this->session->set_userdata('all_sent_social_sortsearchpage_data', $all_sent_social_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;

		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_all_sent_sociallist',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/all_sent_sociallist";
			$this->load->view('admin/include/template',$data);
		}
    	
	}
	
	/*
		@Description: Function for Get All queued social(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued social
		@Date: 30-08-2014
    */
	
	public function queued_list()
	{
		$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$data['sortfield']		= 'scr.id';
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
			$sortfield = 'scr.id';
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
		$config['base_url'] = site_url($this->user_type.'/social/'."queued_list/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		/*$table ="social_master as scm";
		$fields = array('scr.social_message','cm.first_name,cm.last_name','scm.social_send_date','ipi.description,ipm.plan_name','scm.social_send_time,scr.send_social_date,scm.interaction_id,scr.id,scr.send_social_date,scr.is_social_exist');
		$join_tables = array('social_campaign_recepient_trans as scr'=>'scr.social_campaign_id = scm.id',
							 'contact_master as cm jointype direct'=>'cm.id = scr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
							 */
		//$wherestring = "scm.social_send_type = 2 AND scm.social_send_date >= ".date('Y-m-d');
		
		$table ="social_recepient_trans as scr";
		$fields = array('stm.template_name,scm.social_message','scm.social_send_date,scm.social_send_time,scm.id');
		$join_tables = array('social_master as scm'=>'scm.id = scr.social_campaign_id',
							 'social_media_template_master as stm'=>'stm.id = scm.template_name'
							 //'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id'
							 );
		$wherestring = "scr.is_send = '0' AND scm.social_send_type = 2";
		$group_by = 'scr.social_campaign_id';
		
		if(!empty($searchtext))
		{
			$match=array('stm.template_name'=>$searchtext,'scm.social_message'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$group_by,$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$wherestring);
			/*pr($data['datalist']);exit;
			echo $this->db->last_query();exit;*/
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,$group_by,$wherestring,'','1');
		}
				
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_queued_list',$data);
		}
		else
		{
			$data['sortfield_name'] = 'scm.id';
			$data['sort'] = 'desc';
			$config['base_url'] = site_url($this->user_type.'/social/'."interaction_queued_list/");
			$table ="social_master as scm";
			$fields = array('scm.*');
			$join_tables = array('social_recepient_trans as scr'=>'scr.social_campaign_id = scm.id'
								/* 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
								 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
								*/ );
			//$wherestring = "scr.is_send = '0' AND ipm.status = '1' AND ipi.status='1'";
			$wherestring = "scm.status='1'";
			//$groupby = 'ipm.id';
			$groupby = '';
			$data['interaction_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],'','scm.id','desc',$groupby,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','scm.id','desc',$groupby,$wherestring,'','1');
			
			$this->pagination->initialize($config);
			$data['interaction_pagination']	= $this->pagination->create_links();
			
			
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/queued_list";
			$this->load->view('admin/include/template',$data);
		}	
	}
	
	
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
		$config['base_url'] = site_url($this->user_type.'/social/'."interaction_queued_list/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="social_master as scm";
		$fields = array('ipm.*');
		$join_tables = array('social_campaign_recepient_trans as scr'=>'scr.social_campaign_id = scm.id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
		$match=array('ipm.plan_name'=>$searchtext);
		$wherestring = "scr.is_send = '0' AND ipm.status = '1'";
		$groupby = 'ipm.id';
		$data['interaction_plan'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$groupby,$wherestring);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$groupby,$wherestring,'','1');
		
		$this->pagination->initialize($config);
		$data['interaction_pagination']	= $this->pagination->create_links();
		
		$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_interaction_queued_list',$data);
	}
	
	public function interaction_plan_queued_list()
	{
		$interaction_plan = $this->uri->segment(4);
		$searchopt='';$searchtext='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$data['sortfield_name']		= 'scr.id';
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
			$sortfield = 'scr.id';
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
		$config['base_url'] = site_url($this->user_type.'/social/'."interaction_plan_queued_list/".$interaction_plan);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table ="social_campaign_recepient_trans as scr";
		$fields = array('scr.social_message','cm.first_name,cm.last_name','ipi.description,ipm.plan_name','scm.social_send_date,scr.send_social_date,scm.interaction_id,scr.id,scr.is_social_exist');
		$join_tables = array('social_master as scm'=>'scm.id = scr.social_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = scr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
							 );
							/*email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
							 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
							 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
							 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'*/
		
		$wherestring = "ipm.id = ".$interaction_plan." AND scr.is_send = '0' AND ipi.status='1'";
		//$groupby = 'ipm.id';
		if(!empty($searchtext))
		{
			$match = array('scr.social_message'=>$searchtext,"CONCAT_WS(' ',cm.first_name,cm.last_name)"=>$searchtext,"CONCAT_WS(' >> ',ipm.plan_name,ipi.description)"=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring,'','1');
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			/*pr($data['datalist']);
			echo $this->db->last_query();exit;*/
			//echo $this->db->last_query();exit;
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring,'','1');
		}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
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
		@Description: Function for Copy social campaign
		@Author: Sanjay Chabhadiya
		@Input: - social campaign id
		@Output: - all social campaign list
		@Date: 06-08-2014
    */
	
	public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0)
		{
			$data['template_name'] = $result[0]['template_name'];
			$data['template_category'] = $result[0]['template_category'];
			//$data['template_subcategory'] = $result[0]['template_subcategory'];
			$data['social_message'] = $result[0]['social_message'];
			$data['social_send_type'] = $result[0]['social_send_type'];
			
			$data['is_draft'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$this->obj->insert_record($data);
		}
		$this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
	}
	
	/*
		@Description: Function for Send the queued social(interaction plan)
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued social
		@Date: 03-09-2014
    */


	public function interaction_mailsocial()
	{
		$from = $this->config->item('from_social');
		
		$id = $this->input->post('single_id');
		$interaction_id = $this->input->post('interaction_id');
		$page = $this->uri->segment(4);
		
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_social');
        $match = array('id'=>$admin_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		
		//$result = $this->obj->email_campaign_trans_data($id);
		
		$table ="social_campaign_recepient_trans as scr";
		$fields = array('cpt.phone_no,scr.id,scr.social_campaign_id,scr.social_message,scr.contact_id,scm.template_name');
		$join_tables = array('contact_phone_trans cpt'=>'cpt.contact_id = scr.contact_id',
							 'social_master scm'=>'scm.id = scr.social_campaign_id',
							 'contact_master cm'=>'cm.id = scr.contact_id'
							 );
			
		$group_by = 'cpt.contact_id';
		$wherestring = "cpt.is_default = '1' AND scr.is_send = '0' AND scr.id = ".$id;
		$result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
		//pr($result);
		if(count($udata) > 0)
		{
			$remain_social = $udata[0]['remain_social'];
		}
		$message = '';
		if(count($result) > 0)
		{
			$cdata['id'] = $result[0]['id'];
			$message = !empty($result[0]['social_message'])?$result[0]['social_message']:'';
			if($remain_social == 0)
			{
				break;
			}
			if($remain_social == 0)
				$cdata['is_send'] = '0';
			else
			{
				$to = '+919033921029';
				$response = $this->twilio->social($from, $to, $message);
				
				$cdata['sent_date'] = date('Y-m-d H:i:s');
				$cdata['is_send'] = '1'; 
				$remain_social--;
				if(!empty($result))
				{
					$contact_conversation['contact_id'] = $result[0]['contact_id'];
					$contact_conversation['log_type'] = 7;
					$contact_conversation['campaign_id'] = $result[0]['social_campaign_id'];
					$contact_conversation['social_camp_template_id'] = $result[0]['template_name'];

					if(!empty($result[0]['template_name']))
					{
						$match = array('id'=>$result[0]['template_name']);
						$template_data = $this->socialmedia_post_model->select_records('',$match,'','=');
						if(count($template_data) > 0)
						{
							$contact_conversation['social_camp_template_name'] = $template_data[0]['template_name'];
						}
					}
					
					$contact_conversation['created_date'] = date('Y-m-d H:i:s');
					$contact_conversation['created_by'] = $this->admin_session['id'];
					$contact_conversation['status'] = '1';
					//pr($contact_conversation);exit;
					$this->contact_conversations_trans_model->insert_record($contact_conversation);
					//echo $this->db->last_query();
				}
			}
			$this->social_campaign_recepient_trans_model->update_record($cdata);
			//echo $this->db->last_query();
		}
		else
		{
			$cdata['id'] = $id;
			$cdata['is_social_exist'] = '0';
			$this->social_campaign_recepient_trans_model->update_record($cdata);
		}
		//echo $send_social_count; exit;
		$idata['id'] = $this->admin_session['id'];
		if(isset($remain_social))
			$idata['remain_social'] = $remain_social;
		$udata = $this->admin_model->update_user($idata);
		if($flag == 1)
			redirect('admin/'.$this->viewName);
		else
			echo "/".$page;
	}
	
	/*
		@Description: Function for Mobile number not available social transaction delete
		@Author: Sanjay Chabhadiya
		@Input: - ID
		@Output: - 
		@Date: 03-09-2014
    */
	
	function delete_record_trans()
    {
        $id = $this->input->post('id');
		$this->social_campaign_recepient_trans_model->delete_record_campaign('',$id);
		echo 1;
    }
	
	/*
		@Description: Function for search contact social add or edit time
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 06-08-2014
   	*/
	
	function search_contact_to()
	{
		
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."social/search_contact_to");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);

		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$platform_id=$this->input->post('platform_id');
		if($platform_id == '1')
		{$where1 = array('cm.fb_id !='=>'');}
		if($platform_id == '3')
		{$where1 = array('cm.created_type'=>'4','cm.linkedin_id !='=>'');}
		
		$where = array();
		if(!empty($contact_status) && !empty($contact_source))
			$where = array('cm.contact_status'=>$contact_status,'cm.contact_source'=>$contact_source);
		elseif(!empty($contact_status))
			$where = array('cm.contact_status'=>$contact_status);
		elseif(!empty($contact_source))
			$where = array('cm.contact_source'=>$contact_source);
		if(!empty($contact_type))
		{
			$contact_type_array = array('cct.contact_type_id'=>$contact_type);
			$where = array_merge($where,$contact_type_array);
			//pr($where);exit;
		}
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'cpt.phone_no'=>$searchtext,'cm.company_name'=>$searchtext,'ctat.tag'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cpt.phone_no');
		$join_tables = array(
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cm.id';
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,$where1,'like',$config['per_page'],$uri_segment,'cm.first_name','asc',$group_by,$where);
		//echo $this->db->last_query();
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,$where1,'like','','','','',$group_by,$where,'','1');
		$this->pagination->initialize($config);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/contact_to", $data);
	}
	/*
		@Description: Function for get contact paltform wise
		@Author: Niral patel
		@Input: - platform_id
		@Output: - 
		@Date: 11-11-2014
   	*/
	function get_platform_contact()
	{
		$platform_id=$this->input->post('platform_id');
		$config_to1['per_page'] = '50';
		$config_to1['cur_page'] = '0';
		$config_to1['base_url'] = site_url($this->user_type.'/'."social/search_contact_to");
        $config_to1['is_ajax_paging'] = TRUE; // default FALSE
        $config_to1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_to1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address','cm.fb_id','cm.created_type','cm.linkedin_id');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		if($platform_id == '1')
		{$where = array('cm.fb_id !='=>"''");}
		else if($platform_id == '3')
		{$where = array('cm.created_type'=>"'4'",'cm.linkedin_id !='=>"''");}
		else
		{
			$where = array('cet.is_default'=>"'1'");
		}
		//$where = array('cm.is_subscribe'=>"'0'",'cet.is_default'=>"'1'");
		$data['contact_to'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config_to1['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		//pr($data['contact_to']);
		$config_to1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		$this->pagination->initialize($config_to1);
		
		$data['pagination_contact_to'] = $this->pagination->create_links();
		echo $this->load->view($this->user_type.'/'.$this->viewName.'/contact_to',$data);	

	}
	/*
		@Description: Function for Selected contacts type add the social to
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
	
	/*
		@Description: Function for Selected contacts add the social to
		@Author: Sanjay Chabhadiya
		@Input: - contact_id
		@Output: - 
		@Date: 06-08-2014
   	*/
	
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
	
	function poston_fb_page()
	{
			
	}
}