<?php
/*
	@Description: Marketing master controller
	@Author		: Mohit Trivedi
	@Input		: 
	@Output		: 
	@Date		: 02-09-2014

*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class marketing_library_home_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('marketing_library_masters_model');

        $this->obj = $this->marketing_library_masters_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All interaction plan List
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all interaction plan list
        @Date       : 09-07-2014
    */
    public function index()
    {
		$match = array('user_type'=>'2');
		$fields = array('bombbomb_username,bombbomb_password');
		$data['result'] = $this->admin_model->get_user($fields,$match,'','=');
				
		$data['main_content'] = 'admin/'.$this->viewName."/add";
		$this->load->view('admin/include/template',$data);
    }
}