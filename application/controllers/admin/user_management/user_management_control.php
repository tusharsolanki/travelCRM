<?php 
/*
    @Description: contacts controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_management_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		$this->db_session = $this->session->userdata('db_session');
        check_admin_login();
		$this->load->model('user_management_model');
		$this->load->model('contact_masters_model');
		$this->load->model('imageupload_model');
		$this->load->model('contacts_model');
		$this->load->model('Common_function_model');
		$this->load->model('admin_model');
		$this->load->model('dashboard_model');
		$this->load->model('email_library_model');
		$this->load->model('department_masters_model');

		
		$this->obj = $this->user_management_model;
		$this->obj2 = $this->contacts_model;
		$this->obj1 = $this->contact_masters_model;
		$this->obj3 = $this->department_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
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
		check_rights('user_management');
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');
                $parameter = $this->uri->segment(3);
                
                    //echo $this->uri->segment();
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    if($parameter == '2') {
                        $this->session->unset_userdata('user_mgt_archive_sortsearchpage_data');
                    } else {
                        $this->session->unset_userdata('user_mgt_sortsearchpage_data');
                    }
                }
                if($parameter == '2') {
                    $searchsort_session = $this->session->userdata('user_mgt_archive_sortsearchpage_data');
                } else {
                    $searchsort_session = $this->session->userdata('user_mgt_sortsearchpage_data');
                }
		
		$data['sortfield']		= 'um.id';
		$data['sortby']			= 'desc';
		
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
		$parameter = $this->uri->segment(3);
		if($parameter == '2')
		{
			$parameter='2';
			$status_value='0';
			
		}
		else
		{
			$parameter='1';
			$status_value='1';
			
		}
		$config['base_url'] = site_url($this->user_type.'/'."user_management/".$parameter."/");
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 4;
		//$uri_segment=$this->uri->segment(4);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
		$table = "user_master as um";
		$fields = array('um.id','um.*','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as contact_name','cpt.phone_no','cet.email_address','CONCAT_WS(", ",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address','uutm.name as user_type_name','lm.email_id as user_email','lm.agent_type as agent_type','lm.id as user_id');
		$join_tables = array(
							//'contact__status_master as csm' => 'csm.id = cm.contact_status',
							//'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'user__user_type_master as uutm'=>'uutm.id = um.user_type',
							'user_phone_trans as cpt'=>'cpt.user_id = um.id and cpt.is_default = "1"',
							'user_emails_trans as cet'=>'cet.user_id = um.id and cet.is_default = "1"',
							'user_address_trans as cat'=>'cat.user_id = um.id',
							'login_master as lm'=>'lm.user_id = um.id'
						);
		$group_by='um.id';
		if($status_value=='0')
		{
			$where1=array('um.status'=>$status_value);
		}
		else
		{
			$where1=array('um.status >='=>$status_value);
		}
		if(!empty($searchtext))
		{
			$match=array('CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name)'=>$searchtext,'CONCAT_WS(" ",um.first_name,um.last_name)'=>$searchtext,'email_address'=>$searchtext,'phone_no'=>$searchtext);
			$where1=array('um.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where1,'','','1');
			
		}
		else
		{	
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'','','1');			
		}
		$match = array('id'=>$this->admin_session['admin_id']);
		$data['remain_user'] = $this->admin_model->get_user('',$match,'','=');
		
		$match = array('user_type'=>2);
		$data['count_user'] = $this->admin_model->get_user('',$match,'','!=',1);
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

        $user_mgt_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

        $data['uri_segment'] = $uri_segment;


		//Check admin right
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
						'user_master as user' 	=> 'user.id = l.user_id',
						
					);	
		$fields = array('distinct l.id','l.admin_name','l.email_id','CONCAT_WS(" ",user.first_name,user.middle_name,user.last_name) as admin_name','ur.id AS urid');
		$group_by='l.id';
		$where = "(l.user_type = 3 or l.user_type = 4) and ur.id IS NULL";
		$data['admin_list']=$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		
		//Check admin right assign by superadmin
		$parent_db=$this->config->item('parent_db_name');
		$login_id=$this->admin_session['admin_id'];	
		$field = array('id','db_name','email_id');
		$match = array('id'=>$login_id);
		$result = $this->admin_model->get_user($field, $match,'','=');
		//pr($result);
		$field = array('id','db_name','email_id');
		$data1=array('db_name'=>$result[0]['db_name'],'email_id'=>$result[0]['email_id']);
		$admin_data = $this->admin_model->get_parent_login_details($field,$parent_db,$data1);
		$admin_id=!empty($admin_data[0]['id'])?$admin_data[0]['id']:'';
		
		$match = array('user_id' => $admin_id);
		$fields = array('module_id');
		$data['assignright_superadmin'] = $this->module_master_model->select_records1($fields,$match,'','=','','','','','','',$parent_db);
		
		if($this->input->post('result_type') == 'ajax')
		{
                    $this->session->set_userdata('user_mgt_sortsearchpage_data', $user_mgt_sortsearchpage_data);
                    $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else if($this->input->post('result_type') == 'ajax1')
		{
                    $this->session->set_userdata('user_mgt_archive_sortsearchpage_data', $user_mgt_sortsearchpage_data);
                    $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
		}
		else if($parameter == '2')
		{
                    $this->session->set_userdata('user_mgt_archive_sortsearchpage_data', $user_mgt_sortsearchpage_data);
                    $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
                    $this->load->view('admin/include/template',$data);

		}
		else
		{
                    $this->session->set_userdata('user_mgt_sortsearchpage_data', $user_mgt_sortsearchpage_data);
                    $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
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
		check_rights('user_management_add');
		$match = array('id'=>$this->admin_session['admin_id']);
		$remain_user = $this->admin_model->get_user('',$match,'','=');
		
		$match = array('user_type'=>2);
		$count_user = $this->admin_model->get_user('',$match,'','!=',1);
		
		if(isset($remain_user[0]['number_of_users_allowed']) && isset($count_user[0]['total_count']) && $remain_user[0]['number_of_users_allowed'] > $count_user[0]['total_count']) {}
		else{
			
			$msg = $this->lang->line('user_limit_over_msg');
        	$newdata = array('msg'  => $msg);
        	$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);
		}
		
		$match = array();
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');

		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
	
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['user_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','user__user_type_master');
		$data['department_type'] = $this->obj3->select_records1('',$match,'','=','','','','name','asc','department_master');
		/*$match = array('user_type' => '3');
		$data['agent_list'] = $this->obj1->select_records1('',$match,'','=','','','','first_name','asc','user_master');*/
		
		$table = 'user_master as um';
		$join_tables = array('login_master as lm' => 'lm.user_id = um.id');
		$fields = array('um.*,lm.email_id');
		$where = array('um.user_type'=>'3','um.status'=>'1');
		$data['agent_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.first_name','asc','',$where);
		//echo $this->db->last_query();exit;
		
		//Get communication plan data
		$data['communication_plans'] = '';
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
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
		$match = array('id'=>$this->admin_session['admin_id']);
		$remain_user = $this->admin_model->get_user('',$match,'','=');
		
		$match = array('user_type'=>2);
		$count_user = $this->admin_model->get_user('',$match,'','!=',1);
		
		if(isset($remain_user[0]['number_of_users_allowed']) && isset($count_user[0]['total_count']) && $remain_user[0]['number_of_users_allowed'] > $count_user[0]['total_count']) {}
		else{
			
			$msg = $this->lang->line('user_limit_over_msg');
        	$newdata = array('msg'  => $msg);
        	$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);
			
		}
		
		/*if(isset($remain_user[0]['number_of_users_allowed']) || (isset($remain_user[0]['number_of_users_allowed']) && $remain_user[0]['number_of_users_allowed'] <= 0))
		{
			$msg = $this->lang->line('user_limit_over_msg');
        	$newdata = array('msg'  => $msg);
        	$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);
		}*/
		
		$cdata['prefix'] = $this->input->post('slt_prefix');
		//$cdata['prefix'] = $this->input->post('slt_prefix');
		$cdata['first_name'] = $this->input->post('txt_first_name');
		$cdata['middle_name'] = $this->input->post('txt_middle_name');
		$cdata['last_name'] = $this->input->post('txt_last_name');
		$cdata['company_name'] = $this->input->post('txt_company_name');   
		$cdata['company_post'] = $this->input->post('txt_company_post');
		if($this->input->post('txt_birth_date'))
			$cdata['birth_date'] = date('Y-m-d',strtotime($this->input->post('txt_birth_date')));
		if($this->input->post('txt_anniversary_date'))
			$cdata['anniversary_date'] = date('Y-m-d',strtotime($this->input->post('txt_anniversary_date')));
		
		$use_login['user_license_no'] = $this->input->post('user_license_no');
		$use_login['mls_user_id'] = $this->input->post('mls_agent_id');
		
		$user_type = $this->input->post('slt_user_type');
		if(!empty($user_type))
		{
			$cdata['user_type'] = $this->input->post('slt_user_type');
		}
		$agent_id = $this->input->post('slt_agent_list');
		if(!empty($agent_id))
		{
			$cdata['agent_id'] = $this->input->post('slt_agent_list');
			$match = array('user_id'=>$agent_id);
			$userdata = $this->admin_model->get_user('',$match,'','=');
			if(!empty($userdata))
				$use_login['agent_id'] = $userdata[0]['id'];
		}
		$cdata['notes'] = $this->input->post('txtarea_notes');
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = $this->input->post('slt_status');
		if(empty($cdata['status']))
			$cdata['status'] = '1';
		$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('contact_pic');//new add
		$bgImgPath = $this->config->item('user_big_img_path');
		$smallImgPath = $this->config->item('user_small_img_path');
		if(!empty($_FILES['contact_pic']['name']))
		{  
			$uploadFile = 'contact_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
		$bgImgPath = $this->config->item('broker_big_img_path');
		$smallImgPath = $this->config->item('broker_small_img_path');
		if(!empty($_FILES['brokerage_pic']['name']))
		{  
			$uploadFile = 'brokerage_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
			$use_login['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
        $user_id = $this->obj->insert_record($cdata);
		
		/*if(!empty($user_id))
		{
			$match = array('id'=>$this->admin_session['id']);
			$remain_user = $this->admin_model->get_user('',$match,'','=');
			if(!empty($remain_user) && !empty($remain_user[0]['number_of_users_allowed']))
			{
				$iidata['id'] = $this->admin_session['id'];
				$iidata['number_of_users_allowed'] = $remain_user[0]['number_of_users_allowed'] - 1;
				$this->admin_model->update_user($iidata);
				
			}
		}*/
		unset($cdata);
		
		//$use_login['email_id'] = $this->input->post('txt_email_id');
		$email_login_id = strtolower($this->input->post('txt_email_id'));
		// Server Side Email Validation
		$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
		if (preg_match($regex, $email_login_id)) 
		{
			$use_login['email_id'] = strtolower($email_login_id);
		}
		$password = $this->input->post('txt_npassword');
		if(!empty($use_login['email_id']) && !empty($password))
		{
			$user_type= $this->input->post('slt_user_type');
			if($user_type == '3')
			 {$use_login['user_type']='3';}
			if($user_type == '4')
			 {$use_login['user_type']='4';
			}
			$use_login['agent_type'] = $this->input->post('slt_agent_type');
			$use_login['password']=$this->Common_function_model->encrypt_script($password);
			$use_login['user_id']=$user_id;
			$use_login['created_by'] = $this->admin_session['id'];
			$use_login['created_date'] = date('Y-m-d H:i:s');		
			$use_login['status'] = '1';
			$use_login['fb_api_key']		= $this->input->post('fb_key_id');
			$use_login['fb_secret_key']		= $this->input->post('fb_secret_key');
			$use_login['phone'] 			= $this->input->post('phone');
			/*$twilio_contact_no=$this->input->post('twilio_contact_no');
			$twilio_no=explode('-',$twilio_contact_no);
			$use_login['twilio_contact_no'] = $twilio_no[0].$twilio_no[1].$twilio_no[2];*/
			
			$use_login['host_name']=$this->db_session['host_name'];
			$use_login['db_user_name']=$this->db_session['db_user_name'];
			$use_login['db_user_password']=$this->db_session['db_user_password'];
			$use_login['db_name']=$this->db_session['db_name'];
			
			$parent_db=$this->config->item('parent_db_name');
			$lastId = $this->obj->insert_user_record($use_login);
			
			/*---------Insert Default right------------*/
			if(!empty($lastId))
			{
				$match = array('default_right' => 1); // Comment by Nishit
				//$match = array();// Comment by Niral
				$fields = array('id');
				$module_all_data = $this->module_master_model->select_records($fields,$match,'','=');	
				
				$parent_db=$this->config->item('parent_db_name');
				$login_id=$this->admin_session['admin_id'];	
				$field = array('id','db_name','email_id');
				$match = array('id'=>$login_id);
				$result = $this->admin_model->get_user($field, $match,'','=');
				
				$field = array('id','db_name','email_id');
				$arr=array('db_name'=>$result[0]['db_name'],'email_id'=>$result[0]['email_id']);
				$admin_data = $this->admin_model->get_parent_login_details($field,$parent_db,$arr);
				$admin_id=!empty($admin_data[0]['id'])?$admin_data[0]['id']:'';
				if($this->input->post('slt_user_type') == '3')
				{
					$match = array('user_id' => $admin_id,'assign_right' => 1);
					$fields = array('module_id');
					$module_result = $this->module_master_model->select_records1($fields,$match,'','=','','','','','','',$parent_db);
					$table=$parent_db.'.user_right_transaction as ur';
					$fields = array('ur.module_id as id');
					//$group_by='ur.user_id';
					$where = "ur.user_id = ".$admin_id;
					$module_admin_data=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','ur.module_id','asc','',$where);
					$module_data=array_merge($module_all_data,$module_admin_data);
					
					$module_id = array();
					
					foreach($module_data as $row)
					{
						$module_id[] = $row['id'];	
					}
					if(!empty($module_id))
					{
						foreach($module_id as $row)
						{
							if($row != 0 || $row != '')
							{
								$data['user_id']= $lastId;
								$data['module_id'] = $row;
								if(empty($row['default_right']))
								{$data['assign_right'] = 1;}
								else
								{$data['assign_right'] = 0;}
								$data['created_date'] = date('y-m-d h:i:s');
								$data['modified_date'] = date('y-m-d h:i:s');
								$data['status'] = '1';
								$this->module_master_model->insert_record1($data);
							}
						   
						}	
					}
				}
			}
			//create sub account in twilio by niral
			
			if(!empty($lastId))
			{
				$use_login_edit['id'] = $lastId;
				$lastdata['login_id'] = $lastId;
				$lastdata['contact_last_seen'] = $use_login['created_date'];
				$lastdata['listing_last_seen'] = $use_login['created_date'];
				$this->contacts_model->insert_last_seen($lastdata);
				//error_reporting(1);
				$twilio_subaccount=$this->input->post('twilio_subaccount');
                                if(!empty($twilio_subaccount) && $twilio_subaccount == 1)
                                    $this->create_twilio_account($use_login_edit,$email_login_id);
			} 
			
			// Insert to parent DB //
			
			$child_db = $this->db_session['db_name'];
			$parent_db = $this->config->item('parent_db_name');
			$lastId = $this->obj->insert_parent_user_record($parent_db,$child_db,$lastId);
			
			/////////////////////////
			//exit;
			//exit;
			
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
					$cmdata['user_id'] = $user_id;
					$cmdata['email_type'] = $allemailtype[$i];
					// Server Side Email Validation
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
					$cpdata['user_id'] = $user_id;
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

		//department_user data
		$department_type = $this->input->post('slt_dept');

		if(!empty($department_type) && count($department_type) > 0)
		{
			for($i=0;$i<count($department_type);$i++)
			{
				$ctdata['user_id']=$user_id;
				$ctdata['department_id']=$department_type[$i];
				$ctdata['created_date']=date('Y-m-d h:i:s');
				$this->obj->insert_dept_user_record($ctdata);
					//unset($cpdata);

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
					$cadata['user_id'] = $user_id;
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
					$cwdata['user_id'] = $user_id;
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
					$csdata['user_id'] = $user_id;
					$csdata['profile_type'] = $allsocialtype[$i];
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
				$ctdata['user_id'] = $user_id;
				$ctdata['contact_type_id'] = $row;
				
				$this->obj->insert_contact_type_record($ctdata);
				
				unset($ctdata);
			}
		}
		
		
		
		$alltags = $this->input->post('txt_tag');
		
		if(!empty($alltags) && count($alltags) > 0)
		{
			for($i=0;$i<count($alltags);$i++)
			{
				if(trim($alltags[$i]) != "")
				{
					$ctdata['user_id'] = $user_id;
					$ctdata['tag'] = $alltags[$i];
					
					$this->obj->insert_tag_record($ctdata);
					
					unset($ctdata);
				}
			}
		}
		
		
		$allcommunicationplans = $this->input->post('slt_communication_plan_id');
		
		if(!empty($allcommunicationplans) && count($allcommunicationplans) > 0)
		{
			foreach($allcommunicationplans as $row)
			{
				if($row != '')
				{
					$ccdata['user_id'] = $user_id;
					$ccdata['communication_plan_id'] = $row;
					
					$this->obj->insert_communication_trans_record($ccdata);
					
					unset($ccdata);
				}
			}
		}
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		
		$contacttab = $this->input->post('contacttab');
		$redirecttype = $this->input->post('submitbtn');
		if($redirecttype == 'Save User')
		{
			redirect('admin/'.$this->viewName);
		}
		else 
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$user_id.'/'.($contacttab+2));
		}
		
    }
    
    public function create_twilio_account($use_login_edit,$email_login_id)
    {
        //require "Services/Twilio.php";
        $this->load->library('Services/Services_Twilio');
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
        /*else
        {
                $ParentAccountSid = $this->config->item('account_sid', 'twilio');
                $ParentAuthToken  = $this->config->item('auth_token', 'twilio');
        }*/
        //$ParentAccountSid='sdgsgsdg';
        //$ParentAuthToken='sdgdrgsgsdg';

        //exit;
        //Create Sub account
        //echo $ParentAccountSid.'ac id<br>';
        if(!empty($ParentAccountSid) && !empty($ParentAuthToken))
        {
                $http = new Services_Twilio_TinyHttp(
                        'https://api.twilio.com',
                        array('curlopts' => array(
                                CURLOPT_SSL_VERIFYPEER => false,
                                CURLOPT_SSL_VERIFYHOST => 2,
                        )));

                //exit;
                //echo $ParentAccountSid;exit;
                $client = new Services_Twilio($ParentAccountSid, $ParentAuthToken, "2010-04-01", $http);

                //print_r($client);
                //exit;

                // Mock data for our newly created local application user
                $local_user = array("id"=>100, "first_name"=>$this->input->post('txt_first_name'), "last_name"=>$this->input->post('txt_last_name'), "email"=>$email_login_id, "twilio_account_sid"=>"", "twilio_auth_token"=>"");
                  //  pr($local_user);exit;
                //echo 'create sub';
                //$client = new Services_Twilio($ParentAccountSid, $ParentAuthToken);
                try
                {
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

                        /* */
                        $use_login_edit['twilio_account_sid'] = $SubAccountSid;
                        $use_login_edit['twilio_auth_token'] = $SubAccountAuthToken;
                        /* */

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
                                $this->obj->update_user_password($use_login_edit);
                                if(!empty($use_login_edit['id']))
                                    $this->obj->update_user_password($use_login_edit);
                                elseif($use_login_edit['user_id'])
                                    $this->obj->update_user_record($use_login_edit);
                                //exit;

                        }
                        catch(Exception $e)
                        {
                                $PhoneNumber = '';
                                $edata['type'] = 'Twilio';
                                $edata['description'] = $this->lang->line('invalid_twilio');
                                $edata['created_date'] =date('Y-m-d h:i:s');
                                $edata['status'] = 1;
                                $edata['created_by'] = $this->admin_session['id'];
                                $this->dashboard_model->insert_record1($edata);
                        }
                        //Update smsurl on subaccount
                        if(!empty($SubAccountSid) && !empty($SubAccountAuthToken))
                        {
                                $client = new Services_Twilio($SubAccountSid, $SubAccountAuthToken);
                                foreach ($client->account->incoming_phone_numbers as $number) {
                                    $phone_no=$number->phone_number;
                                    $phone_sid=$number->sid;
                                    /*$tnumber = $client->account->incoming_phone_numbers->get($phone_sid);
                                        $tnumber->update(array(
                                        //"VoiceUrl" => "http://demo.twilio.com/docs/voice.xml",
                                        "SmsUrl" => base_url()."/contact_sms_response"
                                        ));*/
                                }
                                try 
                                {
                                        $number = $client->account->incoming_phone_numbers->get($phone_sid);
                                        $number->update(array(
                                        //"VoiceUrl" => "http://demo.twilio.com/docs/voice.xml",
                                        "SmsUrl" => base_url()."contact_sms_response"
                                        ));
                                        //echo $number->sms_url;
                                        //echo 'done';
                                        //pr($number);
                                } catch (Services_Twilio_RestException $e) {
                                        //echo 'error';
                                        //echo $e->getMessage();
                                }
                        }
                }
                catch(Exception $e)
                {
                        $edata['type'] = 'Twilio';
                        $edata['description'] = $this->lang->line('invalid_twilio');
                        $edata['created_date'] =date('Y-m-d h:i:s');
                        $edata['status'] = 1;
                        $edata['created_by'] = $this->admin_session['id'];
                        $this->dashboard_model->insert_record1($edata);
                }
        }
        else
        {
                $edata['type'] = 'Twilio';
                $edata['description'] = $this->lang->line('blank_twilio');
                $edata['created_date'] =date('Y-m-d h:i:s');
                $edata['status'] = 1;
                $edata['created_by'] = $this->admin_session['id'];
                $this->dashboard_model->insert_record1($edata);

        }
        //end check twilio
        //echo "<p>Purchased phone number $PhoneNumber</p>";
    }
    /*
        @Description: Function Add admin right
        @Author: Niral Patel
        @Input: - 
        @Output: - Load Form for add Module details
        @Date: 27-01-2015
    */
   
    public function admin_rights()
    {
		$parent_db=$this->config->item('parent_db_name');
		$login_id=$this->admin_session['admin_id'];	
		$field = array('id','db_name','email_id');
		$match = array('id'=>$login_id);
		$result = $this->admin_model->get_user($field, $match,'','=');
		//pr($result);
		$field = array('id','db_name','email_id');
		$data=array('db_name'=>$result[0]['db_name'],'email_id'=>$result[0]['email_id']);
		$admin_data = $this->admin_model->get_parent_login_details($field,$parent_db,$data);
		$admin_id=!empty($admin_data[0]['id'])?$admin_data[0]['id']:'';
		
		$match = array('user_id' => $admin_id,'assign_right' => 1);
		$fields = array('module_id');
		$module_result = $this->module_master_model->select_records1($fields,$match,'','=','','','','','','',$parent_db);
		$table=$parent_db.'.user_right_transaction as ur';
		$join_tables = array(
						$parent_db.'.module_master as m' 	=> 'm.id= ur.module_id',
					);
		$fields = array('ur.module_id,GROUP_CONCAT(case when m.module_id="" then null else m.module_id end) module_id');
		
		$group_by='ur.user_id';
		$where = "ur.user_id = ".$admin_id." and ur.assign_right = 1 and m.module_parent = 0";
		$module=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','ur.id','asc',$group_by,$where);
		if(!empty($module))
		{
			$module_id=$module[0]['module_id'];
			$table='module_master as m1';
			$join_tables = array(
							'module_master as m2' 	=> 'm1.id= m2.module_id AND m2.module_parent = -1',
						);
			$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right,GROUP_CONCAT(case when m2.module_right="" then null else m2.id end) module_right_id');
			
			$group_by='m2.module_id';
			$where = "m1.module_parent = 0 or m1.id in(".$module_id.")";
			$data['datalist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.id','asc',$group_by,$where);
		}
		//pr($data['datalist']);exit;
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
						'user_master as user' 	=> 'user.id = l.user_id',
						
					);	
		$fields = array('distinct l.id','l.admin_name','l.email_id','l.email_id','CONCAT_WS(" ",user.first_name,user.middle_name,user.last_name) as admin_name','ur.id AS urid');
		$group_by='l.id';
		$where = "(l.user_type = 3 or l.user_type = 4) and ur.id IS NULL";
		$data['admin_list']=$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		//pr($data['admin_list']);exit;
		$data['main_content'] = "admin/".$this->viewName."/admin_rights";
        $this->load->view('admin/include/template', $data);
    }
	/*
    @Description: Function for Insert New Admin data
    @Author: Mohit Trivedi
    @Input: - Details of new Admin which is inserted into DB
    @Output: - List of Admin with new inserted records
    @Date: 01-09-2014
    */
   
    public function insert_rights()
    {
		
		//pr($_POST);exit;
		$id= $this->input->post('id');
		if(!empty($id))
		{
			$user_id = array($id);	
		}
		else
		{
			$user_id = $this->input->post('user_id');
		}
		for($i=0; $i<count($user_id); $i++)
		{
			//$user_id = $this->input->post('id');
			//$match = array('user_id' => $user_id[$i],'assign_right'=>1);
			$match = array('user_id' => $user_id[$i]);
			$fields = array('module_id');
			$eresult = $this->module_master_model->select_records1($fields,$match,'','=');
			//pr($eresult);exit;
			$module_id = $this->input->post('chk_right');
			//pr($module_id);
			//pr($module_id);exit;
			if(empty($module_id))
			{
				$module_id=array();	
			}
			else
				$module_id = array_unique($module_id);
			//pr($module_id);exit;
			if(empty($eresult))
			{
				//for($j=0; $j<count($module_id); $j++)
				foreach($module_id as $row)
				{
					if($row != 0 || $row != '')
					{
						$cdata['user_id']= $user_id[$i];
						$cdata['module_id'] = $row;
						$cdata['assign_right'] = 1;
						$cdata['created_date'] = date('y-m-d h:i:s');
						$cdata['modified_date'] = date('y-m-d h:i:s');
						$cdata['status'] = '1';
						//pr($cdata);
						$this->module_master_model->insert_record1($cdata);
					}
				   
				}
			}
			else
			{
				$old_module_id = array();
				foreach($eresult as $row)
				{
					$old_module_id[] = $row['module_id'];	
				}
				
				$deletecontactdata = array_diff($old_module_id,$module_id);
				//pr($deletecontactdata);exit;
				if(!empty($deletecontactdata))
				{
					$this->module_master_model->delete_module_array($user_id[$i],$deletecontactdata);
					
					//echo $this->db->last_query();
					////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
				}
				$final_data = array_diff($module_id,$old_module_id);
				//pr($final_data);exit;
				//Insert remaining data
				foreach($final_data as $row)
				{
					if($row != 0 || $row != '')
					{
						$cdata['user_id']= $user_id[$i];
						$cdata['module_id'] = $row;
						$cdata['assign_right'] = 1;
						$cdata['created_date'] = date('y-m-d h:i:s');
						$cdata['modified_date'] = date('y-m-d h:i:s');
						$cdata['status'] = '1';
						$this->module_master_model->insert_record1($cdata);
					}
				   
				}
			}
		}
		$msg = $this->lang->line('common_add_success_msg');
		$newdata = array('msg'  => $msg,'replace_cat'  => $replace_cat);
		$this->session->set_userdata('message_session', $newdata);    
		redirect('admin/'.$this->viewName);
		//redirect('admin/'.$this->viewName.'/edit_right/'.$user_id[0]);
	}
	/*
    @Description: Function for for edit right
    @Author: NIral Patel
    @Input: - Id of Admin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 27-01-2015
    */
 
    public function edit_right()
    {
		$parent_db=$this->config->item('parent_db_name');
		$login_id=$this->admin_session['admin_id'];	
		$field = array('id','db_name','email_id');
		$match = array('id'=>$login_id);
		$result = $this->admin_model->get_user($field, $match,'','=');
		
		$field = array('id','db_name','email_id');
		$data=array('db_name'=>$result[0]['db_name'],'email_id'=>$result[0]['email_id']);
		$admin_data = $this->admin_model->get_parent_login_details($field,$parent_db,$data);
		$admin_id=!empty($admin_data[0]['id'])?$admin_data[0]['id']:'';
		
		$match = array('user_id' => $admin_id,'assign_right' => 1);
		$fields = array('module_id');
		$module_result = $this->module_master_model->select_records1($fields,$match,'','=','','','','','','',$parent_db);
		$table=$parent_db.'.user_right_transaction as ur';
		$join_tables = array(
						$parent_db.'.module_master as m' 	=> 'm.id= ur.module_id',
					);
		$fields = array('ur.module_id,GROUP_CONCAT(case when m.module_id="" then null else m.module_id end) module_id');
		
		$group_by='ur.user_id';
		$where = "ur.user_id = ".$admin_id." and ur.assign_right = 1 and m.module_parent = 0";
		$module=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','ur.id','asc',$group_by,$where);
		
		$module_id = $module[0]['module_id'];
		$table = 'module_master as m1';
		$join_tables = array(
						'module_master as m2' 	=> 'm1.id= m2.module_id AND m2.module_parent = -1',
					);
		$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right,GROUP_CONCAT(case when m2.module_right="" then null else m2.id end) module_right_id');
		
		$group_by='m2.module_id';
		if(!empty($module_id))
		{$where = "(m1.module_parent = 0 AND m1.default_right = 0 and m1.id in(".$module_id.")) OR m1.default_right = 1";}
		else{$where = "m1.module_parent = 0 or m1.default_right = 1";}
		$data['datalist']=$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.position','asc',$group_by,$where);
		//pr($data['datalist']);exit;
		//echo $this->db->last_query();exit;
		
		//pr($data['datalist']);exit;
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'user_master as user' 	=> 'user.id = l.user_id',
						//'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id != l.id',
					);	
		$fields = array('distinct l.id','l.admin_name','l.email_id','CONCAT_WS(" ",user.first_name,user.middle_name,user.last_name) as admin_name');
		$group_by='ur.user_id';
		$where = "l.user_type = 3 or l.user_type = 4";
		$data['admin_list']=$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);	
		//echo 1;exit;
     	$id = $this->uri->segment(4);
		//get user informatiion
		$table='login_master as l';
		$join_tables = array(
						'user_master as user' 	=> 'user.id = l.user_id',
						//'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id != l.id',
					);	
		$fields = array('l.id','l.user_type,l.admin_name','l.email_id','CONCAT_WS(" ",user.first_name,user.middle_name,user.last_name) as admin_name');
		$group_by='ur.user_id';
		$where = "l.id = ".$id;
		$data['admin_name']=$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		
		if(!empty($data['admin_name'][0]['user_type']) && $data['admin_name'][0]['user_type'] != 3)
		{
			$msg = $this->lang->line('common_edit_right_msg');
        	$newdata = array('msg'  => $msg);
        	$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);
		}
		
		/*$match=array('id'=>$id);
		$fields=array('CONCAT_WS(" ",first_name,middle_name,last_name) as admin_name');
		$data['admin_name'] = $this->obj->select_records($fields,$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	*/
		//pr($data['admin_name']);exit;
		
		$match = array('user_id' => $id);
		$fields = array('module_id');
		$result = $this->module_master_model->select_records1($fields,$match,'','=');
	
		$old_module_id = array();
		foreach($result as $row)
		{
			$old_module_id[] = $row['module_id'];	
		}
		$data['assign_rights'] = $old_module_id;
		//pr($data['assign_rights']);exit;
		$data['editRecord'] = array('user_id'=>$id);

		$fields=array('id','bombbomb_password','bombbomb_username');
		$match = array('user_type'=>'2');
		$remain_user = $this->admin_model->get_user('',$match,'','=');
		if(!empty($remain_user) && !empty($remain_user[0]['bombbomb_username']) && !empty($remain_user[0]['bombbomb_password']))
		{
			$data['bomb_bomb_conection'] = 'success';
			/*$user_name=$remain_user[0]['bombbomb_username'];
			//$pa//ss=$remain_user[0]['bombbomb_password'];
			$pass=$this->Common_function_model->decrypt_script($remain_user[0]['bombbomb_password']);
			$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".$user_name."&pw=".$pass);
			//pr($IsValidLogin);exit;
			if(!empty($IsValidLogin))
				$IsValidLogin = json_decode($IsValidLogin);	
			if(!empty($IsValidLogin->status) && $IsValidLogin->status == 'success')
			{
				$data['bomb_bomb_conection']='success';
			}*/
			
		}
		else
		{
			$data['bomb_bomb_conection']='';
		}
		//echo $data['bomb_bomb_conection'];exit;
		//pr($data);exit;
		$data['main_content'] = "admin/".$this->viewName."/admin_rights";       
		$this->load->view("admin/include/template",$data);
		
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
		check_rights('user_management_edit');
		$id = $this->uri->segment(4);
		
		$match = array();
        $cdata['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$cdata['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$cdata['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$cdata['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$cdata['user_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','user__user_type_master');
		$cdata['department_type'] = $this->obj3->select_records1('',$match,'','=','','','','name','asc','department_master');
		$dept_list = array();
		$cdata['dept_list'] = $this->obj->select_transaction_user($id);
		if(!empty($cdata['dept_list']))
		{	
			foreach($cdata['dept_list'] as $row)
				$dept_list[] = $row['department_id'];
		}
		$cdata['dept_list'] = $dept_list;
		//pr($cdata['dept_list']);
		$data1['user_type']='3';
		$data1['status']='1';
		$cdata['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		//cdata['agent_list'] = $this->obj->select_user_agent_list_record($data1);
		$table = 'user_master as um';
		$join_tables = array('login_master as lm' => 'lm.user_id = um.id');
		$fields = array('um.*,lm.email_id');
		$where = "um.user_type = '3' AND um.status = '1' AND um.id != ".$id;
		$cdata['agent_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.first_name','asc','',$where);
		/*echo $this->db->last_query();
		pr($cdata['agent_list']);exit;*/
		//Get communication plan data
		$cdata['communication_plans'] = '';
		$cdata['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$cdata['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$cdata['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$cdata['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$cdata['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$cdata['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$cdata['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$cdata['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$cdata['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$cdata['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$cdata['user_info'] = $this->obj->select_user_login_record_by_userid($id);
		$cdata['user_right'] = $this->obj->select_user_rights_trans_edit_record($id);
	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('user_assigned_contact_sortsearchpage_data');
                }
		$searchsort_session = $this->session->userdata('user_assigned_contact_sortsearchpage_data');
		//pr($searchsort_session);
                $cdata['sortby']='uct.contact_id';
		$cdata['sortby']			= 'desc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
                    $cdata['sortfield'] = $sortfield;
                    $cdata['sortby'] = $sortby;
		}
		else
		{
                    if(!empty($searchsort_session['sortfield'])) {
                        if(!empty($searchsort_session['sortby'])) {
                            $cdata['sortfield'] = $searchsort_session['sortfield'];
                            $cdata['sortby'] = $searchsort_session['sortby'];
                            $sortfield = $searchsort_session['sortfield'];
                            $sortby = $searchsort_session['sortby'];
                        }
                    } else {
                    $sortfield = 'uct.id';
					$sortby = 'desc';
                    }
		}
		if(!empty($searchtext))
		{
                    $cdata['searchtext'] = stripslashes($searchtext);
                } else {
                    if(empty($allflag))
                    {
                        if(!empty($searchsort_session['searchtext'])) {
                           /* $cdata['searchtext'] = $searchsort_session['searchtext'];
                            $searchtext =  $data['searchtext'];*/
							$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     					$data['searchtext'] = $searchsort_session['searchtext'];

                        }
                    }
                }
		if(!empty($searchopt))
		{
                    $cdata['searchopt'] = $searchopt;
		}
		if(!empty($date1) && !empty($date2))
		{
                    $date1 = $this->input->post('date1');
                    $date2 = $this->input->post('date2');
                    $cdata['date1'] = $date1;
                    $cdata['date2'] = $date2;	
		}
		if(!empty($perpage) && $perpage != 'null')
		{
                    $cdata['perpage'] = $perpage;
                    $config['per_page'] = $perpage;
		}
		else
		{
                    if(!empty($searchsort_session['perpage'])) {
                        $data['perpage'] = trim($searchsort_session['perpage']);
                        $config['per_page'] = trim($searchsort_session['perpage']);
                        $per_page = trim($searchsort_session['perpage']);
                    } else {
                        $config['per_page'] = '5';
                        $per_page = '5';
                    }
		}
		
		$config['base_url'] = site_url($this->user_type.'/'."user_management/edit_record/".$id."/2/");
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }
	
		$table = "user_contact_trans as uct";
		$fields = array('uct.id as user_contact_id','cm.*','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cpt.phone_no','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address');
		
		$join_tables = array(
							'contact_master as cm jointype direct'=>'cm.id = uct.contact_id',
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id');
		$group_by='uct.contact_id';
		$where=array('uct.user_id'=>$id);
                //echo $per_page."-".$uri_segment."<br>";
		$cdata['assign_contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$per_page,$uri_segment,$sortfield,$sortby,$group_by,$where);
                //echo $this->db->last_query();exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','','1');
		
		$this->pagination->initialize($config);
		$cdata['pagination'] = $this->pagination->create_links();
		$cdata['msg'] = $this->message_session['msg'];
                
		$user_assigned_contact_sortsearchpage_data = array(
                'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
				'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' => !empty($data['perpage'])?trim($data['perpage']):'5',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
                $this->session->set_userdata('user_assigned_contact_sortsearchpage_data', $user_assigned_contact_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
                
                
		//If select and Assign Contacts list
		
		$searchopt1='';$searchtext1='';$date1='';$date2='';$searchoption1='';$perpage1='';
		$searchtext1 = $this->input->post('searchtext1');
		$sortfield1 = $this->input->post('sortfield1');
		$sortby1 = $this->input->post('sortby1');
		$searchopt1 = $this->input->post('searchopt1');
		$perpage1 = $this->input->post('perpage1');
		
		
		$cdata['sortby1']='cm.id';
		$cdata['sortby1']= 'desc';
		
		
		if(!empty($sortfield1) && !empty($sortby1))
		{
			$sortfield1 = $this->input->post('sortfield1');
			$cdata['sortfield1'] = $sortfield1;
			$sortby1 = $this->input->post('sortby1');
			$cdata['sortby1'] = $sortby1;
		}
		else
		{
			$sortfield1 = 'cm.id';
			$sortby1 = 'desc';
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$cdata['searchtext'] = $searchtext;
		}
		if(!empty($searchopt1))
		{
			$searchopt1 = $this->input->post('searchopt1');
			$cdata['searchopt1'] = $searchopt1;
		}
		if(!empty($date1) && !empty($date2))
		{
			 $date1 = $this->input->post('date1');
			 $date2 = $this->input->post('date2');
			 $cdata['date1'] = $date1;
           	 $cdata['date2'] = $date2;	
		}
		if(!empty($perpage1) && $perpage != 'null')
		{
			$perpage1 = $this->input->post('perpage1');
			$cdata['perpage1'] = $perpage1;
			$config1['per_page'] = $perpage1;	
		}
		else
		{
        	$config1['per_page'] = '5';
			$perpage1 = '5';
		}
		$match1 = array('user_id'=>$id);
		$contact_list = $this->obj1->select_records1('',$match1,'','=','','','','contact_id','desc','user_contact_trans');
		$contact_list_id=array();

		for($i=0;$i<count($contact_list);$i++)
		{
			$contact_list_id[]=$contact_list[$i]['contact_id']; 	
		}
		
		$config1['base_url'] = site_url($this->user_type.'/'."user_management/edit_record/".$id."/2/");
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$uri_segment1 = $this->uri->segment(6);
		$config1['uri_segment'] = 6;
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','cpt.phone_no','cet.email_address','CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address');
		$join_tables = array(
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$group_by='cm.id';
		if(!empty($searchtext1))
		{
			$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext1,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext1,'email_address'=>$searchtext1,'phone_no'=>$searchtext1);
			$cdata['select_data_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$perpage1, $uri_segment1,$sortfield1,$sortby1,$group_by,'',$contact_list_id);
			 $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,'',$contact_list_id,'','1');
		}
		else
		{
			$cdata['select_data_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$perpage1,$uri_segment1,$sortfield1,$sortby1,$group_by,'',$contact_list_id);
			//echo $this->db->last_query();exit;
		
		 $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'',$contact_list_id,'','1');
		
		}	
		
		$this->pagination->initialize($config1);
		$cdata['pagination1'] = $this->pagination->create_links();
		$match=array();
		
		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        //$match=array('um.status'=> '1');
		$cdata['user_list'] = $this->obj1->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','asc');
		//$cdata['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $cdata['editRecord'] = $result;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view('admin/'.$this->viewName.'/ajax_list_assign_contact',$cdata);
		}
		else if($this->input->post('result_type') == 'ajax1')
		{
			$this->load->view('admin/'.$this->viewName.'/ajax_list_select_contact',$cdata);
		}
		else
		{
			$cdata['main_content'] = "admin/".$this->viewName."/add";       
			$this->load->view("admin/include/template",$cdata);
		}
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
		 $user_id = $this->input->post('id');
		$contacttab = $this->input->post('contacttab');

		

		
		$match = array('id'=>$user_id);
		$result = $this->obj->select_records('',$match,'','=');
		if(!empty($result[0]['user_type']) && $result[0]['user_type'] == 3 && $this->input->post('slt_user_type'))
		{
			$match = array('agent_id'=>$user_id);
			$assistant_result = $this->obj->select_records('',$match,'','=');
			if(count($assistant_result) > 0 && $result[0]['user_type'] != $this->input->post('slt_user_type'))
			{
				$msg = $this->lang->line('common_not_edit_msg');
				$newdata = array('msg'  => $msg);
				$this->session->set_userdata('message_session', $newdata);
				$searchsort_session = $this->session->userdata('user_mgt_sortsearchpage_data');
				$pagingid = $searchsort_session['uri_segment'];
				redirect(base_url('admin/'.$this->viewName.'/1/'.$pagingid));
			}
		}
		//exit;
		if($contacttab == 1)
		{
			$cdata['prefix'] = $this->input->post('slt_prefix');
			$cdata['first_name'] = $this->input->post('txt_first_name');
			$cdata['middle_name'] = $this->input->post('txt_middle_name');
			$cdata['last_name'] = $this->input->post('txt_last_name');
			$cdata['company_name'] = $this->input->post('txt_company_name');   
			$cdata['company_post'] = $this->input->post('txt_company_post');

			if($this->input->post('txt_birth_date'))
				$cdata['birth_date'] = date('Y-m-d',strtotime($this->input->post('txt_birth_date')));
			else
				$cdata['birth_date'] = '';
			if($this->input->post('txt_anniversary_date'))
				$cdata['anniversary_date'] = date('Y-m-d',strtotime($this->input->post('txt_anniversary_date')));
			else
				$cdata['anniversary_date'] = '';
			$use_login['user_license_no'] = $this->input->post('user_license_no');
			$use_login['mls_user_id'] = $this->input->post('mls_agent_id');

			$agent_name = $this->input->post('slt_user_type');
			$cdata['agent_id'] = $this->input->post('slt_agent_list');
			if(!empty($cdata['agent_id']) && $agent_name == 3)
				$cdata['agent_id'] = 0;
			if(empty($cdata['agent_id']) && $agent_name == 4)
			{
				$msg = $this->lang->line('common_assistant_not_add_msg');
				$newdata = array('msg'  => $msg);
				$this->session->set_userdata('message_session', $newdata);
				$searchsort_session = $this->session->userdata('user_mgt_sortsearchpage_data');
				$pagingid = $searchsort_session['uri_segment'];
				redirect(base_url('admin/'.$this->viewName.'/1/'.$pagingid));
			}
				
			if(!empty($cdata['agent_id']))
			{
				$match = array('user_id'=>$cdata['agent_id']);
				$userdata = $this->admin_model->get_user('',$match,'','=');
				//echo $this->db->last_query();exit;
				if(!empty($userdata))
					$use_login['agent_id'] = $userdata[0]['id'];	
			}
			else
				$use_login['agent_id'] = 0;
				
			
			if(!empty($agent_name))
			{
				$cdata['user_type'] = $this->input->post('slt_user_type');
			}
			
			$cdata['notes'] = $this->input->post('txtarea_notes');
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			$cdata['status']=$this->input->post('slt_status');
			$image = $this->input->post('hiddenFile');
			$oldcontactimg = $this->input->post('contact_pic');//new add
			$bgImgPath = $this->config->item('user_big_img_path');
			$smallImgPath = $this->config->item('user_small_img_path');
			if(!empty($_FILES['contact_pic']['name']))
			{  
				$uploadFile = 'contact_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
				$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
			}

			$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
			$bgImgPath = $this->config->item('broker_big_img_path');
			$smallImgPath = $this->config->item('broker_small_img_path');
			if(!empty($_FILES['brokerage_pic']['name']))
			{  
				$uploadFile = 'brokerage_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
				$use_login['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
			}
			
			$this->obj->update_record($cdata);
			unset($cdata);
			
			$result = $this->obj->select_user_login_record_by_userid($user_id);
			$user_type= $this->input->post('slt_user_type');
			
			$use_login['phone'] = $this->input->post('phone');  	
			if(!empty($user_type))
			{
				if($user_type == '3')
				 {$use_login['user_type']='3';}
				if($user_type == '4')
				 {$use_login['user_type']='4';
				}
				$use_login['agent_type'] = $this->input->post('slt_agent_type');
				$use_login['fb_api_key']		= $this->input->post('fb_key_id');
				$use_login['fb_secret_key']		= $this->input->post('fb_secret_key');
				$use_login['user_id']=$user_id;
				$use_login['modified_by '] = $this->admin_session['id'];
				$use_login['modified_date'] = date('Y-m-d H:i:s');		
				//$parent_db=$this->config->item('parent_db_name');
                                
                                $twilio_subaccount=$this->input->post('twilio_subaccount');
                                if(!empty($twilio_subaccount) && $twilio_subaccount == 1)
                                    $this->create_twilio_account($use_login,$result[0]['email_id']);
                                else
                                {
                                    $use_login['twilio_account_sid'] = $this->input->post('twilio_account_sid');
                                    $use_login['twilio_auth_token']	= $this->input->post('twilio_auth_token');
                                    $use_login['twilio_number'] = $this->input->post('twilio_number');
                                    $this->obj->update_user_record($use_login);
                                }
				// Twilio twilio_account_sid/twilio_auth_token/twilio_number update
				$match = array('user_id'=>$user_id);
				$parent_login = $this->admin_model->get_user('',$match,'','=');
				//pr($parent_login);exit;
				if(!empty($parent_login[0]['email_id']))
				{
					$update_parent_data['email_id'] = $parent_login[0]['email_id'];
					$update_parent_data['db_name'] = $parent_login[0]['db_name'];
					$update_parent_data['user_type'] = $parent_login[0]['user_type'];
					$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
					$update_parent_data['fb_api_key']= $parent_login[0]['fb_api_key'];
					$update_parent_data['fb_secret_key']= $parent_login[0]['fb_secret_key'];
					$update_parent_data['twilio_account_sid']= $parent_login[0]['twilio_account_sid'];
					$update_parent_data['twilio_auth_token']= $parent_login[0]['twilio_auth_token'];
					$update_parent_data['twilio_number']= $parent_login[0]['twilio_number'];
					$update_parent_data['phone']= $parent_login[0]['phone'];
					$parentdb = $this->config->item('parent_db_name');
					$this->obj->update_parent_user_record($parentdb,$update_parent_data);
					//echo $this->db->last_query();exit;
				}
				
				///////////////////////////////////////////////////////
				
			}

		$department_type = $this->input->post('slt_dept');
		print_r($department_type);

		$user_id = $this->input->post('id');
		if(empty($department_type))
				$department_type = array();
			$olddept = array();
			pr($olddept);
			//exit;
			$dept_list = $this->obj->select_user_trans_record($user_id);
			print_r($dept_list);
			//exit;
			if(!empty($dept_list))
			{
				foreach($dept_list as $row)
					$olddept[] = $row['department_id'];
			}

			$deletedept = array_diff($olddept,$department_type);
			print_r($deletedept);
			//exit;
		if(!empty($deletedept))
			{
				foreach($deletedept as $row)
				{
					$deldata['user_id'] = $user_id;
					$deldata['department_id'] = $row;
					$this->obj->delete_table_trans_user_record('',$deldata);
				}
			}
			if(!empty($department_type))
			{
				//$user_add_list = $this->obj->select_user_contact_trans_record($contact_id);	
				for($i=0;$i<count($department_type);$i++)
				{
					if(!in_array($department_type[$i],$olddept))
					{
						$table="transaction_user";
						$match = array('department_id'=>$department_id[$i]);
						$dept_data = $this->obj->select_records($table,$field, $match,'','=');
						if(!empty($dept_data[0]['department_id']))
							$cudata['dept_id'] = $dept_data[0]['department_id'];
						$cudata['user_id'] = $user_id;
						//$cudata['user_id'] = $this->input->post('slt_user');
						$cudata['department_id'] = $department_type[$i];
						//$cudata['created_by'] = $this->admin_session['id'];
						$cudata['created_date'] = date('Y-m-d H:i:s');		
						//$cudata['status'] = '1';
						$this->obj->insert_dept_user_record($cudata);
						//unset($cudata);
						
					
					}
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
						$cmdata['user_id'] = $user_id;
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
						// Server Side Email Validation
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
						$cpdata['user_id'] = $user_id;
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
						$cadata['user_id'] = $user_id;
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
						$cwdata['user_id'] = $user_id;
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
						$csdata['user_id'] = $user_id;
						$csdata['profile_type'] = $allsocialtype[$i];
						$csdata['website_name'] = $allsocialname[$i];
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
						$csdata['website_name'] = $allsocialnamee[$i];
						$csdata['status'] = '1';
						
						$this->obj->update_social_trans_record($csdata);
						
						unset($csdata);
					}
				}
			}
		}
		elseif($contacttab == 2)
		{
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
			
		}
		elseif($contacttab == 3)
		{
			$data_right[] = $this->input->post('export_right');
			$data_right[] = $this->input->post('delete_merge');
			$data_right[] = $this->input->post('see_assigned');
			$data_right[] = $this->input->post('block_accessing');
			
			$kdata['user_id'] = $this->input->post('id');
			$result_data1=$this->obj->select_user_rights_trans_edit_record($kdata['user_id']);			
				if(!empty($result_data1))
				{
					$data=$this->obj->delete_user_rights_trans_record($result_data1[0]['user_id']);
					
					for($i=0; $i < count($data_right);$i++)
					{	
						if(!empty($data_right[$i]) && $data_right[$i] !='4')
						{
							$kdata['rights_id']=$data_right[$i];
							$kdata['rights_value']='0';
							$this->obj->insert_user_rights_trans_record($kdata);
						}
						else if(!empty($data_right[$i]))
						{
							
							$kdata['rights_id']=$data_right[$i];
							$kdata['rights_value']='1';
							$kdata['inactive_date']=date('Y-m-d');
							//$this->obj->insert_user_rights_trans_record($kdata);
							$cdata['id']=$kdata['user_id'];
							$cdata['status']='3';
							$this->obj->update_record($cdata);
						}
						else
						{
							$cdata['id']=$kdata['user_id'];
							$cdata['status']='1';
							$this->obj->update_record($cdata);
						}
						
					}
				}
				else
				{
					for($i=0; $i < count($data_right);$i++)
					{
						if(!empty($data_right[$i]) && $data_right[$i] !='4')
						{
							$kdata['rights_id']=$data_right[$i];
							$kdata['rights_value']='0';
							$this->obj->insert_user_rights_trans_record($kdata);
						}
						else if(!empty($data_right[$i]))
						{	
							$kdata['rights_id']=$data_right[$i];
							$kdata['rights_value']='1';
							$kdata['inactive_date']=date('Y-m-d');
							//$last_id=$this->obj->insert_user_rights_trans_record($kdata);
							$cdata['id']=$kdata['user_id'];
							$cdata['status']='3';
							$this->obj->update_record($cdata);
						}
						else
						{
							$cdata['id']=$kdata['user_id'];
							$cdata['status']='1';
							$this->obj->update_record($cdata);
						}
					}
				}
		}
		$redirecttype = $this->input->post('submitbtn');
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);

		if($redirecttype == 'Save User' || $contacttab == 3)
                {
                    $searchsort_session = $this->session->userdata('user_mgt_sortsearchpage_data');
                    $pagingid = $searchsort_session['uri_segment'];
                    redirect(base_url('admin/'.$this->viewName.'/1/'.$pagingid));
                    //redirect('admin/'.$this->viewName);
                }
		else
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$user_id.'/'.($contacttab+2));
		}
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
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
		
		$oldcontactimg = $this->input->post('contact_pic');//new add
		$bgImgPath = $this->config->item('user_big_img_path');
		$smallImgPath = $this->config->item('user_small_img_path');
		if(!empty($_FILES['contact_pic']['name']))
		{  
			$uploadFile = 'contact_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}

		$this->obj->update_record($cdata);
		unset($cdata);
		
		$cddata['id'] 		= $this->input->post('doc_id');
		$cddata['user_id'] = $this->input->post('id');
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
		//check user right
		check_rights('user_management_delete');
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	function delete_assign_record()
    {
		$user_id =$this->uri->segment(4);
		
        $id = $this->input->post('id');
		//$pagingid = $this->obj->getemailpagingid2($user_id);
		
		$this->obj->delete_user_contact_trans_record($id);
                
                $searchsort_session = $this->session->userdata('user_assigned_contact_sortsearchpage_data');
                if(!empty($searchsort_session['uri_segment']))
                    $pagingid = $searchsort_session['uri_segment'];
                else
                    $pagingid = 0;

                $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'5';
                $total_rows = $searchsort_session['total_rows'];
                if($total_rows % $perpage == 1)
                    $pagingid -= $perpage;
                
                if($pagingid < 0)
                    $pagingid = 0;
		echo $pagingid;
		
	   
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
		echo $image." ".$hiddenImage;
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
            if($this->input->post('twilio_number'))
            {
                $fields = array("id,user_id,email_id,db_name");
                $match = array('user_id'=>$id);
                $result = $this->admin_model->get_user($fields,$match,'','=');
                $cdata['twilio_account_sid'] = '';
                $cdata['twilio_auth_token'] = '';
                $cdata['twilio_number'] = '';
                
                if(!empty($result[0]['id']))
                {
                    $cdata['id'] = $result[0]['id'];
                    $this->admin_model->update_user($cdata);
                    $update_parent_data['email_id'] = $result[0]['email_id'];
                    $update_parent_data['db_name'] = $result[0]['db_name'];
                    $update_parent_data['twilio_account_sid'] = '';
                    $update_parent_data['twilio_auth_token'] = '';
                    $update_parent_data['twilio_number'] = '';
                    $parentdb = $this->config->item('parent_db_name');
                    $this->obj->update_parent_user_record($parentdb,$update_parent_data);
                }
            }
            elseif(!empty($name) && $name == 'brokerage_pic')
            {
                $fields = array("id,$name");
                $match = array('user_id'=>$id);
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

                $cdata['user_id'] = $id;
                $cdata[$name] = '';
                $this->admin_model->update_user($cdata);
            }
            else
            {
                $fields = array("id,$name");
                $match = array('id'=>$id);
                $result = $this->obj->select_records('',$match,'','=');
                $bgImgPath = $this->config->item('user_big_img_path');
                $smallImgPath = $this->config->item('user_small_img_path');
                $image=$result[0][$name];

                $bgImgPathUpload = $this->config->item('upload_image_file_path').'user/big/';
                $smallImgPathUpload = $this->config->item('upload_image_file_path').'user/small/';
                if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
                { 

                        @unlink($bgImgPath.$image);
                        @unlink($smallImgPath.$image);
                }
                $cdata[$name] = '';
                $cdata['id'] = $id;
                $this->obj->update_record($cdata);
            }
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
		$this->load->view('admin/include/template',$data);
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
		$contents = "Name,Company Name,Phone,Email,Contact Status,Contact Address,Contact Type\n";
		$data['sortfield']		= 'cm.first_name';
	//	$data['sortby']			= 'desc';
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','cpt.phone_no','cet.email_address','CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address');
		$join_tables = array(
							'contact__status_master as csm' => 'csm.id = cm.contact_status',
							'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
							'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
							'contact_phone_trans as cpt'=>'cpt.contact_id = cm.id and cpt.is_default = "1"',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id'
						);
		$group_by='cm.id';
		$res =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '',$data['sortfield'],'',$group_by);
		
		foreach($res as $row)
        {
            $contents .= '"'.$row['contact_name'].'"'.",";
            $contents .= '"'.$row['company_name'].'"'.",";
            $contents .= '"'.$row['phone_no'].'"'.",";
 			$contents .= '"'.$row["email_address"].'"'.",";
            $contents .= '"'.$row['contact_status'].'"'.",";
            $contents .= '"'.$row['full_address'].'"'.",";
            $contents .= '"'.$row['contact_type'].'"'."\r\n";
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
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/import";
		$this->load->view('admin/include/template',$data);
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
		$data['csv_file']=$this->input->post('hiddenFiledoc');
		if(!empty($data['csv_file']))
		{
		$data['created_by']=$this->admin_session['id'];
		$data['created_date']= date('Y-m-d H:i:s');
		$data['status']= '1';
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
		$this->load->view('admin/include/template',$data);
		}
		else
		{
			redirect('admin/'.$this->viewName);
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
		$data_mapping['name']=trim($this->input->post('save_mapping'));
		$csv_mapping_id = '';
		if(!empty($data_mapping['name']))
		{
			$data_mapping['created_by']=$this->admin_session['id'];
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
					if($this->input->post('slt_prefix') != '')
						$data['prefix'] = $line_of_text[$i][($this->input->post('slt_prefix')-1)];
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
						$data['created_type'] = '2';
						$data['csv_id'] = $id;
						$contact_id=$this->obj->insert_record($data);
						unset($data);
						$count_no_contact++;
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
							$email_data['email_address'] = $line_of_text[$i][($this->input->post('slt_default_email')-1)];
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
									$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
									if (preg_match($regex, $email)) {
									
									$email_data['contact_id']=$contact_id;
									$this->obj->insert_email_trans_record($email_data);
									}
									unset($email_data);
									
								}
						}
						if($this->input->post('slt_email2') != '')
							{	
								$email_data['email_address'] = $line_of_text[$i][($this->input->post('slt_email2')-1)];
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
										
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
										unset($email_data);
								}
							}
						if($this->input->post('slt_email3') != '')
							{	
							$email_data['email_address'] = $line_of_text[$i][($this->input->post('slt_email3')-1)];
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
										
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
										unset($email_data);
								}
							}
						if($this->input->post('slt_email4') != '')	
							{
								$email_data['email_address'] = $line_of_text[$i][($this->input->post('slt_email4')-1)];
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
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
										unset($email_data);
								}
							}
						if($this->input->post('slt_email5') != '')	
							{
								$email_data['email_address'] = $line_of_text[$i][($this->input->post('slt_email5')-1)];
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
										
										$email_data['contact_id']=$contact_id;
										$this->obj->insert_email_trans_record($email_data);
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
									$phone_data['contact_id']=$contact_id;
									$this->obj->insert_phone_trans_record($phone_data);
									unset($phone_data);
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
								$phone_data['contact_id']=$contact_id;
								$this->obj->insert_phone_trans_record($phone_data);
								unset($phone_data);
								unset($phone_type);	
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
								$phone_data['contact_id']=$contact_id;
								$this->obj->insert_phone_trans_record($phone_data);
								unset($phone_data);
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
		
			$data['csv_id']=$id;
			$data['count_no_contact']=$count_no_contact;
			$data['main_content'] = $this->user_type.'/'.$this->viewName."/add_more_contact";
			$this->load->view('admin/include/template',$data);
		}
		else
		{
			redirect('admin/'.$this->viewName);
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
        redirect('admin/'.$this->viewName);
    }
	
	function merge_search_contacts()
	{
		$fields = $this->input->post('slt_fields');
		
		if(!empty($fields) && count($fields) > 0)
		{
			//$data['datalistcounter'] = $this->obj->merge_search_contacts_counter($fields);
			
			$data = $this->obj->merge_search_contacts($fields);
			
			$data['fields_data'] = $fields;
			
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/merge_contact_list";
			
			$this->load->view('admin/include/template',$data);
		}
		else
		{
			redirect('admin/'.$this->viewName.'/merge_duplicate_contacts');
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
      
		$id = $this->uri->segment(4);
		 
		//$match = array("created_by"=>$this->admin_session['id']);
		//$data['all']=$this->obj1->select_records1('',$match,'','=');
        $data['email_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__email_type_master');
		$data['website_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__websitetype_master');
		$data['phone_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__social_type_master');
		$data['contact_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('','','','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('','','','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->obj1->select_records1('','','','=','','','','name','asc', 'contact__disposition_master');
		$data['user_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','user__user_type_master');
		$data['user_info'] = $this->obj->select_user_login_record_by_userid($id);
		
		//Get communication plan data
		$data['communication_plans'] = '';
		$data['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$data['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$data['address_trans_data'] = $this->obj->select_address_trans_record($id);
		//$data['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$table = "user_website_trans as cwt";
		$fields = array('cwt.id','cwt.website_name','cwm.name');
		$join_tables = array(
								'contact__websitetype_master as cwm' => 'cwm.id = cwt.website_type'
							);
		$group_by='cwt.id';
		$where=array('user_id'=>$id);
		$data['website_trans_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);

		$data['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->obj2->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->obj2->select_tag_record($id);
		$data['communication_trans_data'] = $this->obj2->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->obj2->select_document_trans_record($id);

        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		$data['main_content'] = "admin/".$this->viewName."/view"; 
		      
	   	$this->load->view("admin/include/template",$data);
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
			echo json_encode($field_list);
				
	}
	/*
    @Description: Function to ajax_Active_all Status Change
	@Author: Kaushik Valiya
    @Input: Array and Single Id
    @Output: - 
    @Date: 21-07-2014
    */
	public function ajax_Active_all()
	{
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
				$cdata['id'] = $id;
				$cdata['status'] = '1';
				$this->obj->update_record($cdata);

				$data1['user_id']=$id;
				$data1['rights_id']='4';
				$this->obj->delete_user_rights_trans_record1($data1);

				unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$data['id']=$array_data[$i];
				$data['status']='1';
				$this->obj->update_record($data);

				$data1['user_id']=$array_data[$i];
				$data1['rights_id']='4';
				$this->obj->delete_user_rights_trans_record1($data1);
			}
		}
		$searchsort_session = $this->session->userdata('user_mgt_archive_sortsearchpage_data');
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
	@Author: Kaushik Valiya
    @Input: Array and Single Id
    @Output: - 
    @Date: 21-07-2014
    */
	public function ajax_Inactive_all()
	{
		$email_id='';
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '0';
			$cdata['archive_date'] = date('Y-m-d H:i:s');
			$fields = array('id');
			$match = array('agent_id'=>$id,'status'=>'1');
			$result = $this->obj->select_records($fields,$match,'','=');
			if(empty($result))
				$this->obj->update_record($cdata);
			$email_id = $id;
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$email_id = $array_data[0];
				$data['id']=$array_data[$i];
				$data['status']='0';
				$data['archive_date'] = date('Y-m-d H:i:s');
				$match = array('agent_id'=>$data['id'],'status'=>'1');
				$result = $this->obj->select_records($fields,$match,'','=');
				if(empty($result))
					$this->obj->update_record($data);
			}
		}
		
		//$pagingid = $this->obj->getemailpagingid($email_id);
		$parameter = $this->uri->segment(3);
		//if($parameter == '2')
			//$searchsort_session = $this->session->userdata('user_mgt_archive_sortsearchpage_data');
		//else
			$searchsort_session = $this->session->userdata('user_mgt_sortsearchpage_data');
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
    @Description: Function to delete Contact list
	@Author: Kaushik Valiya
    @Input: Array
    @Output: - 
    @Date: 16-07-2014
    */
	public function ajax_delete_all()
	{
		
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
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
			}
		}
		echo 1;
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
		
		$this->load->view($this->user_type.'/'.$this->viewName."/merge_contact_popup_list",$data);
		
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

		$cdata['prefix'] = $this->input->post('radio-prefix');
		$cdata['department_id'] = $this->input->post('slt_dept');

		$cdata['first_name'] = $this->input->post('radio-first_name');
		$cdata['middle_name'] = $this->input->post('radio-middle_name');
		$cdata['last_name'] = $this->input->post('radio-last_name');
		$cdata['company_name'] = $this->input->post('radio-company_name');
		$cdata['is_lead'] = $this->input->post('radio-islead');
		$cdata['contact_source'] = $this->input->post('radio-contact_source');
		$cdata['created_type'] = '1';
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['status'] = '1';
            
		$contact_id = $this->obj->insert_record($cdata);	
		
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
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
			}
		}
		
		$msg = $this->lang->line('common_merge_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		
		redirect('admin/'.$this->viewName);
		
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
		$contact_id_temp='';
		$msg='';
		
		for($i=0;$i<count($array_data);$i++)
		{
			$contact_id_temp=$array_data[0];
			$check_contact_id=$array_data[$i];
			$match=array('contact_id'=>$check_contact_id,'user_id'=>$user_id);
			$contact_check = $this->obj1->select_records1('',$match,'','=','','','','','asc', 'user_contact_trans');
			if(empty($contact_check))
			{
				$data['contact_id']=$check_contact_id;
				$data['user_id']=$user_id;
				$data['created_by'] = $this->admin_session['id'];
				$data['created_date'] = date('Y-m-d H:i:s');		
				$data['status'] = '1';
				$this->obj->insert_user_contact_trans_record($data);
				$msg= 1;
				
				$contact_id=$this->obj->select_contact_converaction_trans_record($check_contact_id);

				if(empty($contact_id))
				{
					$data_conv['contact_id'] = $check_contact_id;
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '3';
					$data_conv['assign_to']=$user_id;
					$data_conv['created_by'] = $this->admin_session['id'];
					$data_conv['status'] = '1';
					$this->obj->insert_contact_converaction_trans_record($data_conv);
					unset($data_conv);
		
				}
				else
				{
					$dataresult=$this->obj2->select_contact_conversation_trans_record1($check_contact_id,$user_id);
					if(empty($dataresult))
					{
						$data_conv['contact_id'] = $check_contact_id;
						$data_conv['created_date'] = date('Y-m-d H:i:s');
						$data_conv['log_type'] = '4';
						$data_conv['assign_to']=$user_id;
						$data_conv['created_by'] = $this->admin_session['id'];
						$data_conv['status'] = '1';
						$this->obj->insert_contact_converaction_trans_record($data_conv);
						unset($data_conv);
					}
				}
			}
			else
			{
				$msg = 0;
			}
			
		}
		
		echo $msg;
		
	}
	/*
    @Description: Function to Addign Contact list
	@Author: Mohit Trivedi
    @Input: Array
    @Output: - 
    @Date: 13-10-2014
    */
	public function assign_contact1()
	{
		$array_data=$this->input->post('myarray');
		$user_id=$this->input->post('user_id');
		$contact_id_temp='';
		$msg='';
		
		for($i=0;$i<count($array_data);$i++)
		{
			$contact_id_temp=$array_data[0];
			$check_contact_id=$array_data[$i];
			$match=array('contact_id'=>$check_contact_id);
			$contact_check = $this->obj1->select_records1('',$match,'','=','','','','','asc', 'user_contact_trans');
			if(!empty($contact_check))
			{
				$data['contact_id']=$check_contact_id;
				$this->obj->delete_user_contact_trans_record1($data);
				
				//assign contact to new user
				$data['contact_id']=$check_contact_id;
				$data['user_id']=$user_id;
				$data['created_by'] = $this->admin_session['id'];
				$data['created_date'] = date('Y-m-d H:i:s');		
				$data['status'] = '1';
				$this->obj->insert_user_contact_trans_record($data);
				$msg= 1;
				
				unset($data);
				//contact reassign in contact_conversa
				$data_conv['contact_id'] = $check_contact_id;
				$data_conv['created_date'] = date('Y-m-d H:i:s');
				$data_conv['log_type'] = '4';
				$data_conv['assign_to']=$user_id;
				$data_conv['created_by'] = $this->admin_session['id'];
				$data_conv['status'] = '1';
				$this->obj->insert_contact_converaction_trans_record($data_conv);
				unset($data_conv);

			}
			
			/*if(empty($contact_check))
			{
				$data['contact_id']=$check_contact_id;
				$data['user_id']=$user_id;
				$data['created_by'] = $this->admin_session['id'];
				$data['created_date'] = date('Y-m-d H:i:s');		
				$data['status'] = '1';
				$this->obj->insert_user_contact_trans_record($data);
				$msg= 1;
				
				$contact_id=$this->obj->select_contact_converaction_trans_record($check_contact_id);

				if(empty($contact_id))
				{
					$data_conv['contact_id'] = $check_contact_id;
					$data_conv['created_date'] = date('Y-m-d H:i:s');
					$data_conv['log_type'] = '3';
					$data_conv['assign_to']=$user_id;
					$data_conv['created_by'] = $this->admin_session['id'];
					$data_conv['status'] = '1';
					$this->obj->insert_contact_converaction_trans_record($data_conv);

					unset($data_conv);
		
				}
				else
				{
				
					$dataresult=$this->obj2->select_contact_conversation_trans_record1($check_contact_id,$user_id);
					if(empty($dataresult))
					{
						$data_conv['contact_id'] = $check_contact_id;
						$data_conv['created_date'] = date('Y-m-d H:i:s');
						$data_conv['log_type'] = '4';
						$data_conv['assign_to']=$user_id;
						$data_conv['created_by'] = $this->admin_session['id'];
						$data_conv['status'] = '1';
						$this->obj->insert_contact_converaction_trans_record($data_conv);
						unset($data_conv);
					}
				}
			}*/
			else
			{
				$msg = 0;
			}
			
		}
		echo $msg;
	}

	/*
    @Description: Function to Reset Password
	@Author: Kaushik Valiya
    @Input: Npassword
    @Output: - 
    @Date: 07-08-2014
    */
	public function change_password()
	{
		$this->load->model('email_campaign_master_model');
		$use_login['user_id']= $this->input->post('user_id');
		$password = $this->input->post('npassword');
		$use_login['password']=$this->Common_function_model->encrypt_script($password);
		$use_login['modified_by'] = $this->admin_session['id'];
		$use_login['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_user_record($use_login);
		
		
			$match = array('id'=>$use_login['user_id']);
			$parent_login = $this->obj->select_records('',$match,'','=');
			
			$match1 = array('user_id'=>$parent_login[0]['id']);
			$parent_login1 = $this->admin_model->get_user('',$match1,'','=');
			
			$data['name']=$parent_login[0]['first_name'].' '.$parent_login[0]['last_name'];
			$data['email']=$parent_login1[0]['email_id'];
			//pr($data['name']);exit;
			if(!empty($parent_login1[0]['email_id']))
			{
			//pr($parent_login);exit;
			$match1 = array('email_event'=>'1');
			$reset_pass_template = $this->email_library_model->select_records('',$match1,'','=');
			
			
			 if(!empty($reset_pass_template[0]['email_message']) || !empty($reset_pass_template[0]['template_subject']))
             {
                    $emaildata = array(
                        'Date'=>date('Y-m-d'),
						'Day'=>date('l'),
						'Month'=>date('F'),
						'Year'=>date('Y'),
                        'Day Of Week'=>date("w",time()),
                        'Agent Name'=>ucwords($parent_login[0]['first_name'])." ".ucwords($parent_login[0]['last_name']),
                        'Contact First Name'=> '',
                        'Contact Spouse/Partner First Name'=>'',
                        'Contact Last Name'=> '',
                        'Contact Spouse/Partner Last Name'=>'',
                        'Contact Company Name'=>''
                    );

                    $pattern = "{(%s)}";
                    $map = array();

                    if($emaildata != '' && count($emaildata) > 0)
                    {
                        foreach($emaildata as $var => $value)
                        {
                            $map[sprintf($pattern, $var)] = $value;
                        }
                        $output = strtr($reset_pass_template[0]['email_message'], $map);
						$data['admin_temp_sub'] = strtr($reset_pass_template[0]['template_subject'], $map);
                        $data['admin_temp_msg'] = $output;
                    }
			  }
			
			       

				$msg   = $this->load->view('admin/livewire_configuration/reset_password_email', $data, TRUE);
				 if(!empty($data['admin_temp_sub']))
					$sub = $data['admin_temp_sub'];
				else
					$sub = "Password changed - Livewire CRM";
				
				$from = $this->config->item('admin_email');
				
				$to =$parent_login1[0]['email_id'];
				
                                $edata = array();
                                $edata['from_name'] = "Livewire CRM";
                                $edata['from_email'] = $from;
                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
			}
		///////////////////////////////////
			
		$match = array('user_id'=>$this->input->post('user_id'));
		$parent_login = $this->admin_model->get_user('',$match,'','=');
		
		//pr($parent_login);exit;
		
		if(!empty($parent_login[0]['email_id']))
		{
			$update_parent_data['email_id'] = $parent_login[0]['email_id'];
			$update_parent_data['db_name'] = $parent_login[0]['db_name'];
			$update_parent_data['password']= $parent_login[0]['password'];
			$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
			
			$parentdb = $this->config->item('parent_db_name');
			
			$lastId = $this->obj->update_parent_user_record($parentdb,$update_parent_data);
		}
		
		///////////////////////////////////
		
		redirect('admin/'.$this->viewName.'/edit_record/'.$use_login['user_id'].'/3');
	}
	/*
    @Description: This Function check_user already Existing.
    @Author: Kaushik  Valiya
    @Input: Email To check
    @Output: Yes or No
    @Date: 20-08-2014
	
	*/
	public function check_user()
	{
		$email=mysql_real_escape_string($this->input->post('email'));
		
		//$email_login_id = $this->input->post('txt_email_id');
		// Server Side Email Validation
		$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
		if (preg_match($regex, $email)) 
		{
			$email1 = strtolower($email);
			$match=array('email_id'=>$email1);
			$exist_email= $this->obj1->select_records1('',$match,'','=','','','','email_id','asc','login_master');
			
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
    @Description: This Function check_user already Existing.
    @Author: Kaushik  Valiya
    @Input: Email To check
    @Output: Yes or No
    @Date: 20-08-2014
	
	*/
	
	public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='',$where_cond='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$tbl_name;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE (';
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0].' '.$compare_type .'"%'.$match_values[$keys[0]].'%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0].' '.$compare_type .'"'.$match_values[$keys[0]].'" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);
            
            foreach($match_values as $key=>$value)
            {                
                $where .= $and_or.' '.$key.' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type .'"%'.$value.'%"';
                        break;
                    
                    case '=':
                    default:
                        $where .= $compare_type .'"'.$value.'"';
                        break;
                }
            }
			
			$where .= ')';
			
			if($where_cond)
        	{
				foreach($where_cond as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' = "'.$value.'")';
				}
			}
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$tbl_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
    /*
    @Description: This Function check twilio number already Existing.
    @Author: Niral Patel
    @Input: Twilio number check
    @Output: Yes or No
    @Date: 1-04-2015
	
	*/
	public function check_twilio_number()
	{
		
		$twilio_number=$this->input->post('twilio_number');
		$match=array('twilio_number'=>$twilio_number);
		$parent_db=$this->config->item('parent_db_name');
		$exist_email= $this->admin_model->get_user('',$match,'','=','','','','','','',$parent_db);
		//echo $this->db->last_query();exit;
		if(!empty($exist_email))
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}
}