<?php
/*
    @Description: Reset Password
    @Author: Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 17-09-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reset_password_link_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		
		//$this->load->model('contact_type_master_model');
		
		$this->load->model('Admin_model');
		$this->load->model('contact_masters_model');
		$this->load->model('Common_function_model');
		
		$this->obj = $this->Admin_model;
		$this->obj1 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'unsubscribe';
		
		
    }
	

    /*
    @Description: Function for Get All Template
    @Author: Kaushik Valiya
    @Input: - load templated
    @Output: - 
    @Date: 17-09-2014
    */

    public function index()
    {	
		$data['main_content'] =  'reset_password/'.$this->viewName."/list";
		$this->load->view('reset_password/include/template',$data);
    }

    /*
    @Description: Function Load Reset Password Template
    @Author: Kaushik Valiya
    @Input: - Forgot Password
    @Output: - Send Email Template
    @Date: 17-09-2014
    */

    public function reset_password_template()
    {
		//echo $this->uri->segment(4);
		$data['main_content'] =  'reset_password/'.$this->viewName."/add";
		$this->load->view('reset_password/include/template',$data);
		//redirect($this->viewName);
		
    }
	
	  /*
    @Description: Function Load Reset Password
    @Author: Kaushik Valiya
    @Input: - Forgot Password in Add New password
    @Output: - login Admin/user
    @Date: 17-09-2014
    */
	public function reset_password()
    {
            
		//echo $this->uri->segment(4);
		$id= $this->input->post('id');
		$admin_id = base64_decode(urldecode($id));
		$password= $this->input->post('txt_npassword');
		$reset_pass['password']=$this->Common_function_model->encrypt_script($password);
		$reset_pass['id']=$admin_id;
		$reset_pass['modified_date']=date('Y-m-d: H-m-i');
		if(!empty($admin_id))
		{
                    $fields = array('id,db_name,user_type,email_id');
                    $match=array('id'=>$admin_id);
                    $exist_email= $this->obj1->select_records1($fields,$match,'','=','','','','id','asc','login_master');
		}
		else
		{
			
			$msg = $this->lang->line('mail_not_registered');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			redirect('login');
		}
		if(!empty($exist_email))
		{
                    $child_pass['password'] = $reset_pass['password'];
                    $child_pass['modified_date']=date('Y-m-d H:m:i');
                    $this->obj->update_user_child($child_pass,$exist_email);
                    $this->obj->update_user($reset_pass);
			
                    $msg = $this->lang->line('password_change_succ');
                    $newdata = array('msg'  => $msg);
                    $this->session->set_userdata('message_session', $newdata);	
                    redirect('login');
		}
		else
		{
			
			$msg = $this->lang->line('password_not_change');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			
			redirect('login');
		}
		
	}
}
