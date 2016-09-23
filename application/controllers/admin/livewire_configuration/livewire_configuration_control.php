<?php 
/*
    @Description:  livewire Configuration  controller
    @Author:Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 16-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class livewire_configuration_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		check_admin_login();
		
		$this->load->model('user_management_model');
		$this->load->model('common_function_model');
		$this->load->model('imageupload_model');
		$this->load->model('email_library_model');
		$this->load->model('admin_model');
		$this->obj = $this->user_management_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	

    /*
    @Description: Function for configuration List 
    @Author: Kaushik Valiya
    @Input: - List of configuration
    @Output: - all configuration list
    @Date: 16-07-2014
    */
    public function index()
    {	
				
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('admin/include/template',$data);
		
    }
	public function change_password()
    {
            $this->load->model('email_campaign_master_model');
		$oldpassword = $this->input->post('oldpassword');
		if(!empty($oldpassword))
		{
			$password = $this->input->post('npassword');
		}	
		$use_login['id'] = $this->admin_session['id'];
		$use_login['password']=$this->common_function_model->encrypt_script($password);
		$use_login['modified_by'] = $this->admin_session['id'];
		$use_login['modified_date'] = date('Y-m-d H:i:s');
		if(!empty($password))
		{
			$this->obj->update_user_password($use_login);
			
			///////////////////////////////////
			
			$match = array('id'=>$this->admin_session['id']);
			$parent_login = $this->admin_model->get_user('',$match,'','=');
			$data['name']=$parent_login[0]['admin_name'];
			if(!empty($parent_login[0]['email_id']))
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
                        'Day Of Week'=>date( "w", time()),
                        'Agent Name'=>ucwords($parent_login[0]['admin_name']),
                        'Contact First Name'=>  '',
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
				
				$to =$parent_login[0]['email_id'];
				
                                $edata = array();
                                $edata['from_name'] = "Livewire CRM";
                                $edata['from_email'] = $from;
                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
			}
			
			
			if(!empty($parent_login[0]['email_id']))
			{
				$update_parent_data['email_id'] = $parent_login[0]['email_id'];
				$update_parent_data['db_name'] = $parent_login[0]['db_name'];
				$update_parent_data['password']= $parent_login[0]['password'];
				$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
				
				$parentdb = $this->config->item('parent_db_name');
				
				
				
				$lastId = $this->user_management_model->update_parent_user_record($parentdb,$update_parent_data);
			}
			
			///////////////////////////////////
			
		}	
		redirect('admin/'.$this->viewName);
		
    }
	/*
    @Description: This Function check_user already Existing.
    @Author: Kaushik  Valiya
    @Input: Id login To check
    @Output: Yes or No
    @Date: 09-10-2014
	
	*/
	public function check_password()
	{
		$password=$this->input->post('pass');
		$ency_pass=$this->Common_function_model->encrypt_script($password);
		$id = $this->admin_session['id'];
		
		$match=array('id'=>$id);
		$exist_email= $this->obj->select_records1('',$match,'','=','','','','id','asc','login_master');
		if($ency_pass == $exist_email[0]['password'])
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}
	
	public function edit_profile()
    {
     	$id = $this->admin_session['id'];
		$match = array('id'=>$id);
        $result = $this->admin_model->get_user('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
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
	public function update_data()
	{
		//pr($_POST);exit;
		$cdata['id'] = $this->input->post('id');
		$cdata['admin_name'] = $this->input->post('admin_name');
		//$cdata['email_id'] = $this->input->post('email_id');
		$cdata['address']			= $this->input->post('address');
		$cdata['phone']			    = $this->input->post('phone');
		$cdata['user_license_no']   = $this->input->post('user_license_no');
		$cdata['mls_user_id']       = $this->input->post('mls_agent_id');
		$cdata['fb_api_key']		= $this->input->post('fb_key_id');
		$cdata['fb_secret_key']		= $this->input->post('fb_secret_key');
		
		/*$match = array('id'=>$cdata['id']);
                $result = $this->admin_model->get_user('',$match,'','=');
		if(!empty($result) && empty($result[0]['twilio_account_sid']))*/
		$cdata['twilio_account_sid'] = $this->input->post('twilio_account_sid');
		//if(!empty($result) && empty($result[0]['twilio_auth_token']))
		$cdata['twilio_auth_token']	= $this->input->post('twilio_auth_token');
		//if(!empty($result) && empty($result[0]['twilio_number']))
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
			$update_parent_data['mls_user_id']= $parent_login[0]['mls_user_id'];
			$update_parent_data['twilio_account_sid']= $parent_login[0]['twilio_account_sid'];
			$update_parent_data['twilio_auth_token']= $parent_login[0]['twilio_auth_token'];
			$update_parent_data['twilio_number']= $parent_login[0]['twilio_number'];
			$update_parent_data['fb_api_key'] = $parent_login[0]['fb_api_key'];
			$update_parent_data['fb_secret_key']	= $parent_login[0]['fb_secret_key'];
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
				$update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
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