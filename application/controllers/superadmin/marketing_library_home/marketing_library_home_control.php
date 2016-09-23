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
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
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
		
		$data['main_content'] = 'superadmin/'.$this->viewName."/add";
		$this->load->view('superadmin/include/template',$data);
    }
}