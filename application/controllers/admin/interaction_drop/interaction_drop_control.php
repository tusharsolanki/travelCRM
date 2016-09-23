<?php 
/*
    @Description: Interaction controller
    @Author: Kaushik valiya
    @Input: 
    @Output: 
    @Date: 18-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class interaction_drop_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        //$this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        //check_admin_login();
		$this->load->model('interaction_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('contact_masters_model');
		//$this->load->model('imageupload_model');
		
		$this->obj = $this->interaction_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->contact_masters_model;
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
    {}

    /*
    @Description: Get Details of Edit contacts Profile
    @Author: Nishit Modi
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 04-07-2014
    */
    public function drop_interaction()
    {
		$id = $this->uri->segment(4);
		$this->obj->drop_interaction($id);
	}
	
}