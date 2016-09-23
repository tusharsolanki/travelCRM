<?php 
/*
    @Description:  livewire Configuration  controller
    @Author:Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 16-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class assistant_management_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		check_admin_login();
		$this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->load->model('contact_masters_model');
		$this->load->model('common_function_model');
		$this->load->model('imageupload_model');
		$this->load->model('email_library_model');
		$this->load->model('admin_model');
		$this->obj = $this->user_management_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		if(!empty($this->admin_session['user_type']) && $this->admin_session['user_type'] != '2')
			redirect('admin/dashboard');
    }
	

    /*
		@Description: Function for Assistant List 
		@Author: Sanjay Chabhadiya
		@Input: - List of Assistant
		@Output: - All Assistant List
		@Date: 06-02-2015
    */
	
    public function index()
    {	
		//check user right
		//check_rights('email_blast');
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('assistant_management_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('assistant_management_sortsearchpage_data');
		
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
		$config['base_url'] = site_url($this->user_type.'/'."assistant_management/");
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
		
		$table = 'login_master';
		$where1 = array("user_type"=>'5',"status" => '1');
		if(!empty($searchtext))
		{
			$match=array('admin_name'=>$searchtext,'email_id'=>$searchtext,'address'=>$searchtext,'phone'=>$searchtext);
			//$where1=array('um.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,'','','',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where1);
			//echo $this->db->last_query();
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,'','','',$match,'','like','','','','','',$where1,'','','1');
			
		}
		else
		{	
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,'','','','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],'',$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,'','','','','','','','','','','',$where1,'','','1');
			//echo $this->db->last_query();exit;
			//echo count($data['datalist'])."Pegination".$config['total_rows'];
			
			
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
		$assistant_management_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('assistant_management_sortsearchpage_data', $assistant_management_sortsearchpage_data);
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
		@Description: Function Add Archived Assistant List
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 09-02-2015
    */
	
	public function view_archive()
    {
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('assistant_view_archive_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('assistant_view_archive_sortsearchpage_data');
                
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

						/*$data['searchtext'] = $searchsort_session['searchtext'];
						$searchtext =  $data['searchtext'];*/
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
		$config['base_url'] = site_url($this->user_type.'/'."assistant_management/view_archive/");
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
		
		$table = 'login_master';
		$where1 = array("user_type"=>'5',"status" => '0');
		if(!empty($searchtext))
		{
			$match=array('admin_name'=>$searchtext,'email_id'=>$searchtext,'address'=>$searchtext,'phone'=>$searchtext);
			//$where1=array('um.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,'','','',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,'','','',$match,'','like','','','','','',$where1,'','','1');
			
		}
		else
		{	
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,'','','','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],'',$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,'','','','','','','','','','','',$where1,'','','1');
			//echo $this->db->last_query();exit;
			//echo count($data['datalist'])."Pegination".$config['total_rows'];
			
			
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
        $assistant_view_archive_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('assistant_view_archive_sortsearchpage_data', $assistant_view_archive_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
		}
		else
		{
			//echo "hi";exit;
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
			$this->load->view('admin/include/template',$data);
		}	
	}
	
	/*
		@Description: Function Add New Assistant details
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - Load Form for add Assistant details
		@Date: 06-02-2015
    */
	
    public function add_record()
    {
		$match = array('id'=>$this->admin_session['admin_id']);
		$remain_user = $this->admin_model->get_user('',$match,'','=');
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
	
	/*
		@Description: This Function check_user already Existing.
		@Author: Sanjay Chabhadiya
		@Input: Email To check
		@Output: Yes or No
		@Date: 06-02-2015
	*/
	
	public function check_user()
	{
		$email = mysql_real_escape_string($this->input->post('email'));
		
		//$email_login_id = $this->input->post('txt_email_id');
		// Server Side Email Validation
		$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
		if (preg_match($regex, $email)) 
		{
			$email1 = strtolower($email);
			$match=array('email_id'=>$email1);
			$exist_email= $this->contact_masters_model->select_records1('',$match,'','=','','','','email_id','asc','login_master');
			//echo $this->db->last_query();
			
			if(!empty($exist_email))
			{
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
		else
		{
			echo '2';
		}
       
	}
	
	/*
		@Description: Function for Insert New Assistant data
		@Author: Sanjay Chabhadiya
		@Input: - Details of new Assistant which is inserted into DB
		@Output: - List of Assistant with new inserted records
		@Date: 06-02-2015
    */
   
    public function insert_data()
    {
		$cdata['admin_name'] 		= $this->input->post('admin_name');
		$cdata['email_id']			= $this->input->post('txt_email_id');
		$cdata['address']			= $this->input->post('address');
		$cdata['phone']				= $this->input->post('phone');
		//$cdata['number_of_users_allowed']	= $this->input->post('number_of_users_allowed');
		$cdata['user_license_no']	= $this->input->post('user_license_no');
		$cdata['twilio_account_sid']	= $this->input->post('twilio_account_sid');
		$cdata['twilio_auth_token']	= $this->input->post('twilio_auth_token');
		$cdata['twilio_number']	= $this->input->post('twilio_number');
		$cdata['fb_api_key']	= $this->input->post('fb_api_key');
		$cdata['fb_secret_key']	= $this->input->post('fb_secret_key');
		
		//$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('admin_pic');//new add
		$bgImgPath = $this->config->item('admin_big_img_path');
		$smallImgPath = $this->config->item('admin_small_img_path');
		if(!empty($_FILES['admin_pic']['name']))
		{  
			$uploadFile = 'admin_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['admin_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
		$bgImgPath = $this->config->item('broker_big_img_path');
		$smallImgPath = $this->config->item('broker_small_img_path');
		if(!empty($_FILES['brokerage_pic']['name']))
		{  
			$uploadFile = 'brokerage_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
			$cdata['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));
		$db_session = $this->session->userdata('db_session');
		$cdata['host_name'] = $db_session['host_name'];
		$cdata['db_user_name'] = $db_session['db_user_name'];
		$cdata['db_user_password'] = $db_session['db_user_password'];
		$cdata['db_name'] = $db_session['db_name'];
		//pr($db_session);
		//pr($cdata);exit;
		
		//$user_id = $this->obj->insert_record($cdata);
		
		/*$cdata['db_name']			= $databasename;
		$cdata['host_name']			= $this->config->item('root_host_name');
		$cdata['db_user_name']		= $databaseusername;
		$cdata['db_user_password']	= $databaseusername;*/
		
		$cdata['user_type'] 		= '5';
		$cdata['created_by'] 		= $this->admin_session['id'];
		$cdata['created_date'] 		= date('Y-m-d H:i:s');		
		$cdata['status'] 			= '1';
		
		//pr($cdata);exit;
		
		$lastId = $this->admin_model->insert_user($cdata);
		
		if(!empty($lastId))
		{
			$lastdata['login_id'] = $lastId;
			$lastdata['contact_last_seen'] = $cdata['created_date'];
			$lastdata['listing_last_seen'] = $cdata['created_date'];
			$this->contacts_model->insert_last_seen($lastdata);
			// Create Twilio Sub Account
			//$use_login_edit['id'] = $lastId;
			/*$this->load->library('Services/Services_Twilio');			
			//date_default_timezone_set("America/Los_Angeles");
			
			//load config
			$this->load->config('twilio', TRUE);
			
			$field_for_twilio1 = array('twilio_account_sid','twilio_auth_token','twilio_number');
			$match_for_twilio1 = array('user_type'=>2);
			$udata_for_twilio1 = $this->user_management_model->select_login_records($field_for_twilio1, $match_for_twilio1,'','=','','','','','');
			
			if(!empty($udata_for_twilio1[0]['twilio_account_sid']) && !empty($udata_for_twilio1[0]['twilio_auth_token']) && !empty($udata_for_twilio1[0]['twilio_number']))
			{
				$ParentAccountSid = $udata_for_twilio1[0]['twilio_account_sid'];
				$ParentAuthToken  = $udata_for_twilio1[0]['twilio_auth_token'];
			}
			else
			{
				$ParentAccountSid = $this->config->item('account_sid', 'twilio');
				$ParentAuthToken  = $this->config->item('auth_token', 'twilio');
			}
			
			//exit;
			
			$http = new Services_Twilio_TinyHttp(
				'https://api.twilio.com',
				array('curlopts' => array(
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => 2,
				)));
				
			//exit;
			
			$client = new Services_Twilio($ParentAccountSid, $ParentAuthToken, "2010-04-01", $http);
			//print_r($client);
			//exit;

			// Mock data for our newly created local application user
			$local_user = array("id"=>100, "first_name"=>$this->input->post('txt_first_name'), "last_name"=>$this->input->post('txt_last_name'), "email"=>$email_login_id, "twilio_account_sid"=>"", "twilio_auth_token"=>"");
			
		
			//$client = new Services_Twilio($ParentAccountSid, $ParentAuthToken);
			$subaccount = $client->accounts->create(array(
				"FriendlyName" => $local_user['email']
			));
			
			//echo "<p>Created subaccount: {$subaccount->sid}</p>";
			
			//update and save our local_user
			$local_user['twilio_account_sid'] = $subaccount->sid;
			$local_user['twilio_auth_token'] = $subaccount->auth_token;
			//$local_user.save();
			
			// @end snippet
			// @start snippet
			// Purchase a number for the subaccount
			
			$SubAccountSid=$local_user['twilio_account_sid'];
			$SubAccountAuthToken=$local_user['twilio_auth_token'];
			
			$use_login_edit['twilio_account_sid'] = $SubAccountSid;
			$use_login_edit['twilio_auth_token'] = $SubAccountAuthToken;
			
			// create a new Twilio client for subaccount
			$http = new Services_Twilio_TinyHttp(
			'https://api.twilio.com',
			array('curlopts' => array(
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => 2,
			)));
		
			$subaccount_client = new Services_Twilio($SubAccountSid, $SubAccountAuthToken, "2010-04-01", $http);
			try
			{
				$number = $subaccount_client->account->incoming_phone_numbers->create(array(
					"AreaCode" => "858",
				));
				
				$PhoneNumber = $number->phone_number;
				$use_login_edit['twilio_number'] = $PhoneNumber;
				//pr($use_login_edit);
				$this->obj->update_user_password($use_login_edit);
				
			}
			catch(Exception $e)
			{
				//pr($e);
				$PhoneNumber = '';
			}*/
			
			//echo "<p>Purchased phone number $PhoneNumber</p>";
			//exit;
			
			
			$parent_db = $this->config->item('parent_db_name');
			$lastId = $this->obj->insert_parent_assistant_record($lastId,$parent_db);
		}
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect('admin/'.$this->viewName);
     }
	
	
	/*
		@Description: Function for Edit Assistant details
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 06-02-2015
    */
	
	public function edit_record()
    {
     	$id = $this->uri->segment('4');
		$match = array('id'=>$id);
        $result = $this->admin_model->get_user('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }
	
	/*
		@Description: Function for Update Assistant details
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 06-02-2015
    */
	
	public function update_data()
	{
		//pr($_POST);exit;
		$cdata['id'] = $this->input->post('id');
		$cdata['admin_name'] = $this->input->post('admin_name');
		//$cdata['email_id'] = $this->input->post('email_id');
		$cdata['address']			= $this->input->post('address');
		$cdata['phone']			= $this->input->post('phone');
		$cdata['user_license_no']			= $this->input->post('user_license_no');
		$cdata['fb_api_key']	= $this->input->post('fb_api_key');
		$cdata['fb_secret_key']	= $this->input->post('fb_secret_key');
		
		$match = array('id'=>$cdata['id']);
        $result = $this->admin_model->get_user('',$match,'','=');
		if(!empty($result) && empty($result[0]['twilio_account_sid']))
			$cdata['twilio_account_sid'] = $this->input->post('twilio_account_sid');
		if(!empty($result) && empty($result[0]['twilio_auth_token']))
			$cdata['twilio_auth_token']	= $this->input->post('twilio_auth_token');
		if(!empty($result) && empty($result[0]['twilio_number']))
			$cdata['twilio_number']	= $this->input->post('twilio_number');
		//$cdata['number_of_users_allowed']			= $this->input->post('number_of_users_allowed');
		//$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('admin_pic');//new add
		$bgImgPath = $this->config->item('admin_big_img_path');
		$smallImgPath = $this->config->item('admin_small_img_path');
		
		if(!empty($_FILES['admin_pic']['name']))
		{  
			$uploadFile = 'admin_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['admin_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
		$bgImgPath = $this->config->item('broker_big_img_path');
		$smallImgPath = $this->config->item('broker_small_img_path');
		if(!empty($_FILES['brokerage_pic']['name']))
		{  
			$uploadFile = 'brokerage_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
			$cdata['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$cdata1['password']=$this->input->post('password');
		if(!empty($cdata1['password']))
		{
			$cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));
		}
		
		//$cdata['db_name']=$this->input->post('db_name');
		//$cdata['host_name']=$this->input->post('host_name');
		//$cdata['db_user_name']=$this->input->post('db_user_name');
		//$cdata['db_user_password']=$this->input->post('db_user_password');
		
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		//$cdata['status'] = '1';
		$this->admin_model->update_user($cdata);
		//echo $this->db->last_query();exit;
		//echo $this->db->last_query();exit;		
		///////////////////////////////////
			
		$match = array('id'=>$this->input->post('id'));
		$parent_login = $this->admin_model->get_user('',$match,'','=');
		
				
		//pr($parent_login);exit;
		
		if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
		{
			$update_parent_data['admin_name'] = $parent_login[0]['admin_name'];
			$update_parent_data['email_id'] = $parent_login[0]['email_id'];
			$update_parent_data['db_name'] = $parent_login[0]['db_name'];
			$update_parent_data['password']= $parent_login[0]['password'];
			$update_parent_data['address']= $parent_login[0]['address'];
			$update_parent_data['phone']= $parent_login[0]['phone'];
			$update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
			$update_parent_data['brokerage_pic']= $parent_login[0]['brokerage_pic'];
			$update_parent_data['user_license_no']= $parent_login[0]['user_license_no'];
			$update_parent_data['twilio_account_sid']= $parent_login[0]['twilio_account_sid'];
			$update_parent_data['twilio_auth_token']= $parent_login[0]['twilio_auth_token'];
			$update_parent_data['twilio_number']= $parent_login[0]['twilio_number'];
			$update_parent_data['fb_api_key']	= $this->input->post('fb_api_key');
			$update_parent_data['fb_secret_key']	= $this->input->post('fb_secret_key');
			//$update_parent_data['number_of_users_allowed']= $parent_login[0]['number_of_users_allowed'];
			$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
			$parent_db_name = $this->config->item('parent_db_name');
			$lastId = $this->admin_model->update_superadmin_data($update_parent_data,$parent_db_name);
		}
		
		///////////////////////////////////
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect(base_url('admin/'.$this->viewName));	
	}
	
	
	/*
		@Description: Function to ajax_Active_all Status Change
		@Author: Sanjay Chabhadiya
		@Input: Array and Single Id
		@Output: - 
		@Date: 07-02-2015
    */
	
	public function ajax_Active_all()
	{
		$id = $this->input->post('single_active_id');
		$array_data = $this->input->post('myarray');
		$parent_db_name = $this->config->item('parent_db_name');
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '1';
			$cdata['archive_date'] = date('Y-m-d H:i:s');
			$this->admin_model->update_user($cdata);
			//echo $this->db->last_query();exit;
			$match = array('id'=>$id);
			$result = $this->admin_model->get_user('',$match,'=','');
			if(!empty($result))
			{
				unset($cdata['id']);
				$cdata['email_id'] = $result[0]['email_id'];
				$cdata['db_name'] = $result[0]['db_name'];
				$this->admin_model->update_superadmin_data($cdata,$parent_db_name);
			}			
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				//$email_id = $array_data[0];
				$cdata['id'] = $array_data[$i];
				$cdata['status'] = '1';
				$cdata['archive_date'] = date('Y-m-d H:i:s');
				$this->admin_model->update_user($cdata);
				
				$match = array('id'=>$array_data[$i]);
				$result = $this->admin_model->get_user('',$match,'=','');
				if(!empty($result))
				{
					unset($cdata['id']);
					$cdata['email_id'] = $result[0]['email_id'];
					$cdata['db_name'] = $result[0]['db_name'];
					$this->admin_model->update_superadmin_data($cdata,$parent_db_name);
				}	
			}
		}
		$searchsort_session = $this->session->userdata('assistant_view_archive_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($total_rows % $perpage == 1)
			$pagingid -= $perpage;

		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
            //echo 1;
	}
	
	/*
		@Description: Function to ajax_Inactive_all Status Change
		@Author: Sanjay Chabhadiya
		@Input: Array and Single Id
		@Output: - 
		@Date: 07-02-2015
    */
	
	public function ajax_Inactive_all()
	{
		//$email_id='';
		$id = $this->input->post('single_active_id');
		$array_data = $this->input->post('myarray');
		$parent_db_name = $this->config->item('parent_db_name');
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '0';
			$cdata['archive_date'] = date('Y-m-d H:i:s');
			$this->admin_model->update_user($cdata);
			//echo $this->db->last_query();exit;
			$match = array('id'=>$id);
			$result = $this->admin_model->get_user('',$match,'=','');
			if(!empty($result))
			{
				unset($cdata['id']);
				$cdata['email_id'] = $result[0]['email_id'];
				$cdata['db_name'] = $result[0]['db_name'];
				$this->admin_model->update_superadmin_data($cdata,$parent_db_name);
			}			
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				//$email_id = $array_data[0];
				$cdata['id'] = $array_data[$i];
				$cdata['status'] = '0';
				$cdata['archive_date'] = date('Y-m-d H:i:s');
				$this->admin_model->update_user($cdata);
			
				$match = array('id'=>$array_data[$i]);
				$result = $this->admin_model->get_user('',$match,'=','');
				if(!empty($result))
				{
					unset($cdata['id']);
					$cdata['email_id'] = $result[0]['email_id'];
					$cdata['db_name'] = $result[0]['db_name'];
					$this->admin_model->update_superadmin_data($cdata,$parent_db_name);
				}	
			}
		}
		
		//$pagingid = $this->obj->getemailpagingid($email_id);
		//$parameter = $this->uri->segment(3);
		//if($parameter == '2')
			//$searchsort_session = $this->session->userdata('user_mgt_archive_sortsearchpage_data');
		//else
			$searchsort_session = $this->session->userdata('assistant_management_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;

		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($total_rows % $perpage == 1)
			$pagingid -= $perpage;
		
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
	}
	
	/*
		@Description: Function for Delete Login User Profile Pic OR Brokerage Logo.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 06-02-2015
    */
	
	public function delete_image()
	{
		$id = $this->input->post('id');
		$name=$this->input->post('name');
		if($name == 'brokerage_pic')
		{
			$fields = array("id,$name");
			$match = array('id'=>$id);
			$result = $this->admin_model->get_user('',$match,'','=');
			
			$bgImgPath1 = $this->config->item('broker_big_img_path');
			$smallImgPath1 = $this->config->item('broker_small_img_path');
			$image1=$result[0][$name];
			
			$bgImgPathUpload1 = $this->config->item('upload_image_file_path').'broker/big/';
			$smallImgPathUpload1 = $this->config->item('upload_image_file_path').'broker/small/';
			if(file_exists($bgImgPathUpload1.$image1) || file_exists($smallImgPathUpload1.$image1))
			{ 
			
				@unlink($bgImgPath1.$image1);
				@unlink($smallImgPath1.$image1);
			}
			
			$cdata['id'] = $id;
			$cdata[$name] = '';
			$this->admin_model->update_user($cdata);
			
			$match = array('id'=>$id);
			$parent_login = $this->admin_model->get_user('',$match,'','=');
			if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
			{
				$update_parent_data['email_id'] = $parent_login[0]['email_id'];
				$update_parent_data['db_name'] = $parent_login[0]['db_name'];
				$update_parent_data['brokerage_pic']= $parent_login[0]['brokerage_pic'];
				$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
				$parentdb = $this->config->item('parent_db_name');
				$lastId = $this->admin_model->update_superadmin_data($update_parent_data,$parentdb);
			}
			//echo $this->db->last_query();exit;
		}
		else
		{
			$fields = array("id,$name");
			$match = array('id'=>$id);
			$result = $this->admin_model->get_user('',$match,'','=');
			$bgImgPath = $this->config->item('admin_big_img_path');
			$smallImgPath = $this->config->item('admin_small_img_path');
			$image=$result[0][$name];
			
			$bgImgPathUpload = $this->config->item('upload_image_file_path').'admin/big/';
			$smallImgPathUpload = $this->config->item('upload_image_file_path').'admin/small/';
			if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
			{ 
			
				@unlink($bgImgPath.$image);
				@unlink($smallImgPath.$image);
			}
			$cdata['id'] = $id;
			$cdata[$name] = '';
			$this->admin_model->update_user($cdata);
			$match = array('id'=>$id);
			$parent_login = $this->admin_model->get_user('',$match,'','=');
			
			//pr($parent_login);exit;
			
			if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
			{
				$update_parent_data['email_id'] = $parent_login[0]['email_id'];
				$update_parent_data['db_name'] = $parent_login[0]['db_name'];
				$update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
				$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
				$parentdb = $this->config->item('parent_db_name');
				$lastId = $this->admin_model->update_superadmin_data($update_parent_data,$parentdb);
			}
		}
		echo 'done';
	}
	
	
}