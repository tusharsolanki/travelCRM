<?php 
/*
    @Description: Interaction Plans controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class livewire_configuration_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
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
			$this->load->view('user/include/template',$data);
		
    }
	
}