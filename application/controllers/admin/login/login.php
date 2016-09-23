<?php
/*
    @Description: login controller
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-2014
	
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct() 
    {
        parent::__construct();
		$this->load->model('common_function_model');
        $this->data = array();        
		
    }

    public function index() 
    {
    	
		//$forgot=$this->input->post('forgot_email');
        $admin_session = $this->session->userdata($this->lang->line('common_admin_session_label')); 
		$this->message_session = $this->session->userdata('message_session');     
        if ($admin_session['active'] === TRUE)
        {
           redirect('admin/dashboard');
        }
        else
		{
			$this->do_login();
		} 
		
    }
    
    /*
        @Description : Check Login is valid or not (Admin login)
        @Author      : Jayesh Rojasara
        @Input       : useremail, passowrd and / or useremail
        @Output      : true or false
        @Date        : 06-05-14
    */
    public function do_login() 
    {
		$email = $this->input->post('email');
        $password = $this->common_function_model->encrypt_script($this->input->post('password'));
        $forgot_password = $this->input->post('forgot_email');
		
        if($forgot_password)
        {
            $this->forgetpw_action();
        }
        else
        {            
            if($email && $password)
            {                
     			$remember_me = $this->input->post('onoffswitch');
				$cookie_name = 'adminsiteAuth';
				$cookie_time = (3600 * 24 * 14); 
				$email1 = '';
				$password1 ='';
	
				if(isset($remember_me) && ($remember_me == "on"))
				{
					setcookie ($cookie_name, 'usr='.$email.'&hash='.$this->input->post('password'), time() + $cookie_time);
				}
				else
				{
					setcookie ($cookie_name, 'usr='.$email1.'&hash='.$password1, time() + $cookie_time);
				}
				
				 $field = array('id','admin_name','email_id','status');
                 $match = array('email_id'=>$email,'password'=>$password);
                 $udata = $this->admin_model->get_user($field, $match,'','=');
				 if(count($udata) > 0)
                    {
						if($udata[0]['status'] == 1)
						{
                        $newdata = array(
                                'name'  => $udata[0]['admin_name'],
                                'id' =>$udata[0]['id'],
                                'useremail' =>$udata[0]['email_id'],
                                'active' => TRUE);
                        $this->session->set_userdata($this->lang->line('common_admin_session_label'), $newdata);
                        redirect('admin/dashboard');
						}
						else
						{
							$msg = $this->lang->line('inactive_account');
							$newdata = array('msg'  => $msg);
							$data['msg'] = $msg;
                        	$this->load->view('admin/login/login',$data);
						}
						
                    }
                    else
                    {
						
						$msg = $this->lang->line('invalid_us_pass');
        				$newdata = array('msg'  => $msg);
                        $data['msg'] = $msg;
                        $this->load->view('admin/login/login',$data);
                    }
			}
			else
            {     
                
                $data['msg'] = ($this->uri->segment(2) == 'msg') ? $this->uri->segment(3) : '';
			 	$this->load->view('admin/login/login',$data);
            }
        }
		
    }   
    
    /*
        @Description : Function to generate password and email the same to user
        @Author      : Jayesh Rojasara
        @Input       : email address
        @Output      : password to the email address
        @Date        : 06-05-14
    */
    public function forgetpw_action()
    {	
        $email = $this->input->post('forgot_email');
		
        $fields=array('id','admin_name','email_id','password');
        $match = array('email_id'=>$email);		
        $result = $this->admin_model->get_user($fields,$match,'','=');  

        if((count($result))>0)
        {
				$name =  $result[0]['admin_name'];
				$email =  $result[0]['email_id'];
				$password =  $this->common_function_model->decrypt_script($result[0]['password']);
				
				// Email Start
				
				$loginLink = $this->config->item('base_url').'admin';
				
				$pass_variable_activation = array('admin_name' => $name, 'email_id' => $email, 'password' => $password,'loginLink'=>$loginLink);
				$data['actdata'] = $pass_variable_activation;
				
				$activation_tmpl = $this->load->view('admin/email_template/user_forget_password',$data, true);
				$email  = $this->input->post('email');
				$sub = $this->config->item('sitename')." : Admin Forgot Password";  
				
				$from=$this->config->item('admin_email');
				$sendmail = $this->common_function_model->send_email($email,$sub,$activation_tmpl,$from);				
				$msg= "Mail Sent Successfully";       
		}
        else
        {
           $msg = "No Such User Found";
        }
	    $newdata = array('msg'  => $msg);
        $data['msg'] = $msg;
    	$this->load->view('admin/login/login',$data);
    }
}
