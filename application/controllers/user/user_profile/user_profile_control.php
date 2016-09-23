<?php 
/*
    @Description: contacts controller
    @Author: Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 18-09-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_profile_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('user_management_model');
		$this->load->model('contact_masters_model');
		$this->load->model('imageupload_model');
		$this->load->model('contacts_model');
		$this->load->model('Common_function_model');
		$this->load->model('admin_model');
		$this->load->model('email_library_model');
		
		$this->obj = $this->user_management_model;
		$this->obj2 = $this->contacts_model;
		$this->obj1 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	

    /*
    @Description: Function for Get All contacts List
    @Author: Kaushik Valiya
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 18-09-2014
    */
    public function index()
    {	
		//pr($this->user_session);
		$id = $this->user_session['user_id'];
		 
		//$match = array("created_by"=>$this->user_session['id']);
		//$data['all']=$this->obj1->select_records1('',$match,'','=');
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['contact_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
		$data['user_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','user__user_type_master');
		//Get communication plan data
		$data['communication_plans'] = '';
		//echo"hello";
		$data['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$data['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$data['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$data['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$data['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->obj2->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->obj2->select_tag_record($id);
		$data['communication_trans_data'] = $this->obj2->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->obj2->select_document_trans_record($id);
		$data['user_info'] = $this->obj->select_user_login_record_by_userid($id);
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		//echo"<pre>";
		//print_r($data['editRecord']);exit;
		$data['main_content'] = "user/".$this->viewName."/view"; 
		      
	   	$this->load->view("user/include/template",$data);
    }

    
    /*
    @Description: Get Details of Edit contacts Profile
    @Author: Kaushik Valiya
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 18-09-2014
    */
    public function edit_record()
    {
        
		$id =$this->user_session['user_id'];
		
		//$match = array("created_by"=>$this->user_session['id']);
		$match = array();
        $cdata['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$cdata['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$cdata['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$cdata['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$cdata['user_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','user__user_type_master');
		//$match =array('user_type'=> '3','status >'=> '1');
		$data1['user_type']='3';
		$data1['status']='1';
		$cdata['agent_list'] = $this->obj->select_user_agent_list_record($data1);
		//pr($cdata['agent_list']);exit;
		//Get communication plan data
		$cdata['communication_plans'] = '';
		$cdata['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$cdata['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$cdata['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$cdata['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$cdata['profile_trans_data'] = $this->obj->select_social_trans_record($id);

		$cdata['email_trans_data'] = $this->obj->select_email_trans_record($id);
			//echo"<pre>";
			//print_r($data['email_trans_data']);exit;
		$cdata['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$cdata['address_trans_data'] = $this->obj->select_address_trans_record($id);
		$cdata['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$cdata['profile_trans_data'] = $this->obj->select_social_trans_record($id);

		$cdata['user_info'] = $this->obj->select_user_login_record($this->user_session['id']);
		//pr($cdata['user_info']);exit;
		$cdata['user_right'] = $this->obj->select_user_rights_trans_edit_record($id);
		
		
          
		
		//pr($cdata['select_data_list']);exit;
		$match=array();
		$cdata['user_list'] = $this->obj1->select_records1('',$match,'','=','','','','','asc','user_master');
		
        /*$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $cdata['editRecord'] = $result;*/
        $table = "user_master as um";
        $fields = array('um.*,lm.twilio_account_sid,lm.twilio_auth_token,lm.twilio_number,lm.phone');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
		$match3 = array('um.id'=> $id);
		$cdata['editRecord'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match3,'=','','','','asc');
		//pr($cdata['editRecord'] );exit;
		//pr($cdata['editRecord']);exit;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view('user/'.$this->viewName.'/ajax_list_assign_contact',$cdata);
		}
		else if($this->input->post('result_type') == 'ajax1')
		{
			$this->load->view('user/'.$this->viewName.'/ajax_list_select_contact',$cdata);
		}
		else
		{
			$cdata['main_content'] = "user/".$this->viewName."/add";       
			$this->load->view("user/include/template",$cdata);
		}
    }

    /*
    @Description: Function for Update contacts Profile
    @Author: Kaushik Valiya
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 18-09-2014
    */
    public function update_data()
    {
        $this->load->model('email_campaign_master_model');
		$parent_db=$this->config->item('parent_db_name');
		$cdata['id'] = $this->input->post('id');
		$user_id = $this->input->post('id');
		$contacttab = $this->input->post('contacttab');
		
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
			$use_login['fb_api_key']		= $this->input->post('fb_key_id');
			$use_login['fb_secret_key']		= $this->input->post('fb_secret_key');
			
			//Add twilio credential
	        

			$agent_name = $this->input->post('slt_user_type');
			$cdata['agent_id'] = $this->input->post('slt_agent_list');
			//pr($cdata['agent_id']);exit;
			
			if(!empty($agent_name))
			{
				$cdata['user_type'] = $this->input->post('slt_user_type');
				
			}
			/*?>$agent_id = $this->input->post('slt_agent_id');
			if(!empty($agent_id))
			{
				$cdata['agent_id'] = $this->input->post('slt_agent_id');
			}<?php */
			 
			$cdata['notes'] = $this->input->post('txtarea_notes');
			$cdata['modified_date'] = date('Y-m-d H:i:s');
			//$cdata['status']=$this->input->post('slt_status');
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
			
			$user_type= $this->input->post('slt_user_type');
		
			$use_login['user_id']=$user_id;
			$use_login['modified_by '] = $this->user_session['id'];
			$use_login['modified_date'] = date('Y-m-d H:i:s');
			
			//pr($use_login);exit;
			$use_login['twilio_account_sid'] = $this->input->post('twilio_account_sid');
			$use_login['twilio_auth_token']	= $this->input->post('twilio_auth_token');
			$use_login['twilio_number']	= $this->input->post('twilio_number');
			$use_login['phone'] 			= $this->input->post('phone');


			$this->obj->update_user_record($use_login);
			$this->obj->update_user($use_login,$parent_db);
			//echo $this->db->last_query();exit;
			
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
						$cmdata['email_address'] = $allemailaddress[$i];
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
						$cmdata['email_address'] = $allemailaddresse[$i];
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
			$use_login['email_id']= $this->input->post('email_id');
			$password = $this->input->post('txt_npassword');
			$use_login['password']=$this->Common_function_model->encrypt_script($password);
			$use_login['id'] = $this->user_session['id'];
			$use_login['modified_by'] = $this->user_session['id'];
			$use_login['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_user_password($use_login);
			
			///////////////////////////////////
			
			$match = array('id'=>$this->user_session['id']);
			$parent_login = $this->admin_model->get_user('',$match,'','=');
			
			
			$match1 = array('id'=>$this->user_session['user_id']);
			$parent_login1 = $this->obj->select_records('',$match1,'','=');
			//pr($parent_login);
			
			$data['name']=$parent_login1[0]['first_name'].' '.$parent_login1[0]['last_name'];
			// pr($data);exit;
			if(!empty($parent_login[0]['email_id']))
			{
			
			$match1 = array('email_event'=>'1');
			$reset_pass_template = $this->email_library_model->select_records('',$match1,'','=');
			
			
			 if(!empty($reset_pass_template[0]['email_message']) || !empty($reset_pass_template[0]['template_subject']))
                {
                    $emaildata = array(
                        'Date'=>'',
                        'Day'=>'',
                        'Month'=>'',
                        'Year'=>'',
                        'Day Of Week'=>'',
                        'Agent Name'=>ucwords($parent_login1[0]['first_name'])." ".ucwords($parent_login1[0]['last_name']),
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
			
			       

				$msg   = $this->load->view('user/user_profile/reset_password_email', $data, TRUE);
				 if(!empty($data['admin_temp_sub']))
					$sub = $data['admin_temp_sub'];
				else
					$sub = "Password changed - Livewire CRM";
				
				$from = $this->config->item('admin_email');
				$to =$parent_login[0]['email_id'];
				
                                $edata = array();
                                $edata['from_name'] = "Livewire CRM";
                                $edata['from_email'] = $from;
                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
			}
			
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
			
			redirect('user/'.$this->viewName.'/'.$this->user_session['id']);
		}
		
		$redirecttype = $this->input->post('submitbtn');
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);

		if($redirecttype == 'Save Profile' || $contacttab == 3)
			redirect('user/'.$this->viewName);
		else
		{
			redirect('user/'.$this->viewName.'/edit_record/'.$user_id.'/'.($contacttab+1));
		}
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
    }
	 /*
    @Description: Function for Delete contacts Profile By user
    @Author: Kaushik Valiya
    @Input: - Delete id which contacts record want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 18-09-2014
    */
    function delete_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	
	/*
    @Description: Functions for deleting various transactions data
    @Author: Kaushik Valiya
    @Input: - Transaction id
    @Output: - 
    @Date: 18-09-2014
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
    @Description: Function for Unpublish contacts Profile By user
    @Author: Kaushik Valiya
    @Input: - Delete id which contacts record want to Unpublish
    @Output: - New contacts list after record is Unpublish.
    @Date: 18-09-2014
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
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By user
    @Author: Kaushik Valiya
    @Input: - Delete id which contacts record want to publish
    @Output: - New contacts list after record is publish.
    @Date: 18-09-2014
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
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
	
	/*
    @Description: Function for to upload image
    @Author: Kaushik Valiya
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
    @Author: Kaushik Valiya
    @Input: - Delete id 
    @Output: - image deleted
    @Date: 10-07-2014
    */
	public function delete_image()
	{
		$id=$this->input->post('id');
		$name=$this->input->post('name');
		
		if($name == 'brokerage_pic')
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
			
			$cdata['id'] = $id;
			$cdata[$name] = '';
			$this->obj->update_record($cdata);
		}
		
		echo 'done';
	}
	
	/*
    @Description: Function for to upload document
    @Author: Kaushik Valiya
    @Input: - 
    @Output: - 
    @Date: 18-09-2014
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
    @Date: 18-09-2014
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
    @Author: Kaushik Valiya
    @Input: Document Id
    @Output: - 
    @Date: 18-09-2014
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
    @Description: Function to Reset Password
	@Author: Kaushik Valiya
    @Input: Npassword
    @Output: - 
    @Date: 07-08-2014
    */
	public function change_password()
	{
		
		$use_login['user_id']= $this->input->post('user_id');
		$password = $this->input->post('npassword');
		$use_login['password']=$this->Common_function_model->encrypt_script($password);
		$use_login['modified_by'] = $this->user_session['id'];
		$use_login['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_user_record($use_login);
		redirect('user/'.$this->viewName.'/edit_record/'.$use_login['user_id'].'/3');
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
		$password=$this->input->post('password');
		$ency_pass=$this->Common_function_model->encrypt_script($password);
		$email=$this->input->post('email');
		$match=array('email_id'=>$email);
		$exist_email= $this->obj1->select_records1('',$match,'','=','','','','email_id','asc','login_master');
		if($ency_pass == $exist_email[0]['password'])
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
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