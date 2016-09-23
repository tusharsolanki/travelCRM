<?php 

/*
    @Description: Superadmin my profile controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 02-09-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class my_profile_control extends CI_Controller
{	
    function __construct()
    {
	    parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		$this->message_session = $this->session->userdata('message_session');
	    check_superadmin_login();
		$this->load->model('superadmin_management_model');
		$this->load->model('user_management_model');
		$this->load->model('common_function_model');
		$this->load->model('admin_model');
		$this->load->model('email_library_model');
   	    $this->obj = $this->superadmin_management_model;
	    $this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';

    }

    /*
    @Description: Function for profile
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - superadmin profile
    @Date: 02-09-2014
    */

    public function index()
    {	
		redirect('superadmin/'.$this->viewName."/edit_record");
    }
 
    /*
    @Description: Get Details of Edit Superadmin Profile
    @Author: Mohit Trivedi
    @Input: - Id of superadmin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 02-09-2014
    */
 
    public function edit_record()
    {
 		$id = $this->superadmin_session['id'];
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Superadmin Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Superadmin
    @Output: - List with updated Superadmin details
    @Date: 02-09-2014
    */
   
    public function update_data()
    {
        $this->load->model('email_campaign_master_model');
	    $cdata['id'] = $this->input->post('id');
		$cdata['email_id'] = $this->input->post('email_id');
		$cdata1['password']=$this->input->post('password');
		if(!empty($cdata1['password']))
		{
			$cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));
		}
		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		
			$match = array('id'=>$this->superadmin_session['id']);
			$parent_login = $this->admin_model->get_user('',$match,'','=');
			
			
			//pr($parent_login);exit;
			
			$data['name']=$parent_login[0]['admin_name'];
			// pr($data);exit;
			if(!empty($parent_login[0]['email_id']))
			{
			
			$match1 = array('email_event'=>'1');
			$reset_pass_template = $this->email_library_model->select_records('',$match1,'','=');
			
			
			 if(!empty($reset_pass_template[0]['email_message']))
                {
                    $emaildata = array(
                        'Date'=>'',
                        'Day'=>'',
                        'Month'=>'',
                        'Year'=>'',
                        'Day Of Week'=>'',
                        'Agent Name'=>'',
                        'Contact First Name'=>  ucwords($data['name']),
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
                        $data['admin_temp_msg'] = $output;
                    }
				}
			
			       

				$msg   = $this->load->view('superadmin/my_profile/reset_password_email', $data, TRUE);
				
				 if(!empty($reset_pass_template[0]['template_subject']))
					$sub = $reset_pass_template[0]['template_subject'];
				else
					$sub = "Welcome to Livewire CRM";
				
				$from = $this->config->item('admin_email');
				$to =$parent_login[0]['email_id'];
				
                                $edata = array();
                                $edata['from_name'] = "Livewire CRM";
                                $edata['from_email'] = $from;
                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
			}
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect(base_url('superadmin/'.$this->viewName));
		
    }
	
	/*
    @Description: Function for check superadmin already exist
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 02-09-2014
    */

	public function check_user()
	{
		
		$email=$this->input->post('email');
		$match=array('email_id'=>$email);
		$exist_email= $this->obj->get_user('',$match,'','=');
		if(!empty($exist_email))
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}

	/*
    @Description: Function for check superadmin already exist
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 02-09-2014
    */

	public function check_pass()
	{
		$id = $this->superadmin_session['id'];
		$password=$this->common_function_model->encrypt_script($this->input->post('currpassword'));
		$match=array('password'=>$password,'id'=>$id);
		$exist_password= $this->obj->get_user('',$match,'','=');
		if(!empty($exist_password))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

}