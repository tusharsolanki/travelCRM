<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		//date_default_timezone_set("America/New_York");
		
		/*--------------- Get module right for admin -------------*/
		$this->load->model('admin_model');
		$this->load->model('module_master_model');
		$parent_db=$this->config->item('parent_db_name');
		$params = array('name' => 'adminhtml');  
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		$this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
		if(!empty($this->admin_session['date_timezone']))
			date_default_timezone_set($this->admin_session['date_timezone']);
		elseif(!empty($this->user_session['date_timezone']))
			date_default_timezone_set($this->user_session['date_timezone']);
		else
			date_default_timezone_set($this->config->item('default_timezone'));
		//pr($this->admin_session);
        if(!empty($this->admin_session) || !empty($this->user_session))
		{	
			if(isset($this->user_session) && !empty($this->user_session))
			{
				$login_id = $this->user_session['agent_id'];
				
				$field = array('id','db_name','email_id');
				$match = array('user_type'=>'2');
				$result = $this->admin_model->get_user($field, $match,'','=');
			}
			else
			{
				$login_id = $this->admin_session['admin_id'];	
				$field = array('id','db_name','email_id');
				$match = array('id'=>$login_id);
				$result = $this->admin_model->get_user($field, $match,'','=');
			}
			
		   	if(!empty($result))
		   	{
				//Get parent admin details
				$field = array('id','db_name','email_id');
				$data=array('db_name'=>$result[0]['db_name'],'email_id'=>$result[0]['email_id']);
				$admin_data = $this->admin_model->get_parent_login_details($field,$parent_db,$data);
				$admin_id=!empty($admin_data[0]['id'])?$admin_data[0]['id']:'';
				$child_db=!empty($admin_data[0]['db_name'])?$admin_data[0]['db_name']:'';
				
				//Get admin rights
				if(isset($this->user_session) && !empty($this->user_session))
			    {
					$manage_admin_right = $this->module_master_model->module_list_by_admin($login_id,$child_db);
				}
				else
				{
					$manage_admin_right = $this->module_master_model->module_list_by_admin($admin_id,$parent_db);
				}
				if(!empty($manage_admin_right))
				{
					/*if(in_array('send_a_survey',$manage_users_result) && !in_array('manage_list',$manage_users_result) && !in_array('manage_surveys',$manage_users_result))
					 {
						array_push($manage_users_result,"manage_list","all_manage_list","add_manage_list","edit_manage_list","delete_manage_list","archive_manage_list","manage_surveys","all_manage_surveys","preview_manage_surveys","add_manage_surveys","edit_manage_surveys","delete_manage_surveys","archive_manage_surveys");
					 }*/
					@$this->modules_unique_name =   $manage_admin_right;
				}
				else
				{
					@$this->modules_unique_name =   array('no_module');					
				}
			}
		}
		else
		{
			//$match = array('user_id' => $id);
			$fields = array('module_id','module_unique_name');
			$result = $this->module_master_model->select_records($fields,'','','=');
		
			$old_module_id = array();
			foreach($result as $row)
			{
				$module[] = $row['module_unique_name'];	
			}
			$module_lists = array_values($module);
			
			@$this->modules_unique_name =   $module_lists;
		}
			//pr($this->modules_unique_name);
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */