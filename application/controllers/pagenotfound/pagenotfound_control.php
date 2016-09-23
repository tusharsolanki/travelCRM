<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pagenotfound_control extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		//$this->output->set_status_header('404'); 
        $data['content'] = ''; // View name 
        $this->load->view('pagenotfound/pagenotfound',$data);//loading in my template 
	}
}