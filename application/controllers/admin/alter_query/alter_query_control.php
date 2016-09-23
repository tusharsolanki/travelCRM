<?php 
/*
    @Description: cron controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 21-10-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class alter_query_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->load->model('phonecall_script_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('email_library_model');
		$this->load->model('contacts_model');
		$this->load->model('email_signature_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('interaction_model');
		$this->load->model('imageupload_model');
		$this->load->model('contact_masters_model');
		$this->load->model('task_model');
		$this->load->model('calendar_model');
		//sms model
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_texts_model');
		$this->obj = $this->email_campaign_master_model;
		$this->obj1 = $this->sms_campaign_master_model;
		
		$this->viewName = $this->router->uri->segments[2];
		$this->load->library('Twilio');
		
		ini_set('memory_limit', '-1');
    }
	
    /*
    @Description: Function for Get All contacts List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
    */
    public function index()
    {
		$this->load->view('admin/'.$this->viewName.'/list');
	}
	
	public function add_record()
    {
		
		$all_query = $this->input->post('alter_query');
		$alter_query = explode(';',$this->input->post('alter_query'));
		//exit;
		
		//$alter_query = "ALTER TABLE `admin_users` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Enter name'";
		
		$db_name = $this->config->item('parent_db_name');
		
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
		$match = array('user_type'=>'2');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>$this->config->item('root_host_name'),'db_user_name'=>$this->config->item('root_user_name'),'db_user_password'=>$this->config->item('root_password')));
		$all_admin1 = array_merge($all_admin,$merge_db);
		//pr($all_admin1);exit;
		
		/*---------Send sms------------*/
		
		if(!empty($all_admin1))
		{
			//pr($all_admin1);
			foreach($all_admin1 as $row1)
			{
				echo $row1['db_name'];echo "<br/>";
			}
			
			foreach($all_admin1 as $row)
			{
				$db_name1 = $row['db_name'];
				if(!empty($db_name1)){
					
					$db = '';
						
					$db['second']['hostname'] = $row['host_name'];
					$db['second']['username'] = $row['db_user_name'];
					$db['second']['password'] = $row['db_user_password'];			//Local
					$db['second']['database'] = $row['db_name'];
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
					
					foreach($alter_query as $row)
					{
						if(trim($row) != '')
						{
							echo $db['second']['database']."  :-  ";
							$query = trim($row);
							$query = $this->legacy_db->query($query);

							//pr($query->result_array());

							pr($query);
							//echo $this->db->last_query();
							echo "<br/>";
						}
					}
					//echo $this->legacy_db->last_query();
					unset($db);
					
				}
			}
		}
		
		//exit;
		
		//redirect(base_url().'admin/alter_query');
		
	}

	public function insert_data()
	{
		$all_query = $this->input->post('alter_query');
		$alter_query = explode(';',$this->input->post('alter_query'));
		//exit;
		
		//$alter_query = "ALTER TABLE `admin_users` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Enter name'";
		
		$db_name = $this->config->item('parent_db_name');
		
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
		$match = array('user_type'=>'2');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>$this->config->item('root_host_name'),'db_user_name'=>$this->config->item('root_user_name'),'db_user_password'=>$this->config->item('root_password')));
		$all_admin1 = array_merge($all_admin,$merge_db);
		//pr($all_admin1);exit;
		
		/*---------Send sms------------*/
		
		if(!empty($all_admin1))
		{
			//pr($all_admin1);
			foreach($all_admin1 as $row1)
			{
				echo $row1['db_name'];echo "<br/>";
			}
			
			foreach($all_admin1 as $row)
			{
				$db_name1 = $row['db_name'];
				if(!empty($db_name1)){
					
					$db = '';
						
					$db['second']['hostname'] = $row['host_name'];
					$db['second']['username'] = $row['db_user_name'];
					$db['second']['password'] = $row['db_user_password'];			//Local
					$db['second']['database'] = $row['db_name'];
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
					
					foreach($alter_query as $row)
					{
						if(trim($row) != '')
						{
							echo $db['second']['database']."  :-  ";
							$query = trim($row);
							$query = $this->legacy_db->query($query);

							pr($query->result_array());

							//pr($query);
							//echo $this->db->last_query();
							echo "<br/>";
						}
					}
					//echo $this->legacy_db->last_query();
					unset($db);
					
				}
			}
		}
		
		//exit;
		
		//redirect(base_url().'admin/alter_query');
	}
	
}
