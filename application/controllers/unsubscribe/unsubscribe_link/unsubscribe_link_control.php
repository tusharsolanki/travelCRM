<?php
/*
    @Description: Task controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 06-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class unsubscribe_link_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		//$this->load->model('contact_type_master_model');
		$this->load->model('contacts_model');
		$this->load->model('unsubscribe_model');
		$this->obj = $this->unsubscribe_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'unsubscribe';
    }
	

    /*
    @Description: Function for Get All Task List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Task list
    @Date: 06-08-2014
    */

    public function index()
    {	
		$data['main_content'] =  'unsubscribe/'.$this->viewName."/list";
		$this->load->view('unsubscribe/include/template',$data);
    }

    /*
    @Description: Function Add New Task details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Task details
    @Date: 06-08-2014
    */

    public function unsubscribe()
    {
		$email_address = $this->input->post('email_id');
		$database_name = $this->input->post('user_name');
    	$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
		$this->session->unset_userdata('db_session');
		if(!empty($database_name))
		{
			$table = "login_master as lm";
			$fields = array('lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
			$join_tables = array();
			$match = array('lm.db_name'=>$database_name,'lm.status'=>'1');
			$domain_result = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
			if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
			{
		
				$newdata1 = array(
					'host_name'  => $domain_result[0]['host_name'],
					'db_user_name' =>$domain_result[0]['db_user_name'],
					'db_user_password' =>$domain_result[0]['db_user_password'],
					'db_name' =>$domain_result[0]['db_name']
				);
				$this->session->set_userdata('db_session', $newdata1);
				
				//$this->db->close();
				$db = '';
					
				$db['second']['hostname'] = $domain_result[0]['host_name'];
				$db['second']['username'] = $domain_result[0]['db_user_name'];
				//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
				$db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
				$db['second']['database'] = $domain_result[0]['db_name'];
				$db['second']['dbdriver'] = 'mysql';
				$db['second']['dbprefix'] = '';
				$db['second']['pconnect'] = TRUE;
				$db['second']['db_debug'] = TRUE;
				$db['second']['cache_on'] = FALSE;
				$db['second']['cachedir'] = '';
				$db['second']['char_set'] = 'utf8';
				$db['second']['dbcollat'] = 'utf8_general_ci';
				$db['second']['swap_pre'] = '';
				$db['second']['autoinit'] = TRUE;
				$db['second']['stricton'] = FALSE;
				
				$this->legacy_db = $this->load->database($db['second'], TRUE);	
							
				if (eregi($pattern, $email_address)){
					$match = array('email_address'=>$email_address,'is_default'=>'1');
					$result = $this->obj->get_user('',$match,'','=','','','','','',$database_name);
					if(count($result) > 0)
					{
						foreach($result as $row)
						{
							$data['id'] = $row['contact_id'];
							$data['is_subscribe'] = '1';
							$this->obj->update_user($database_name,$data);
						}
						echo "Email Unsubscribe successfully";
					}
					else
						echo "Not valid email";
				}
			}
			else
				echo "Invalid URL";
		}
		
		/*$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	*/
		//redirect($this->viewName);
		
    }
}
