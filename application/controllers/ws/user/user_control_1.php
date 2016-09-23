<?php 
/*
    @Description: User controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 23-09-14
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_control extends CI_Controller
{	
    function __construct()
    {
		parent::__construct(); 
		//$this->load->model('ws/user_model');
		$this->load->model('ws/user_registration_model');
		$this->load->model('ws/saved_searches_model');
		$this->load->model('ws/properties_viewed_model');
		$this->load->model('ws/favorite_model');
		$this->load->model('ws/last_login_model');
		$this->load->model('user_management_model');
		$this->load->model('common_function_model');
		$this->load->model('contact_masters_model');
                $this->load->model('map_joomla_model');
                $this->load->model('joomla_assign_model');
                
                $this->load->model('interaction_plans_model');
                $this->load->model('sms_campaign_master_model');
                $this->load->model('email_campaign_master_model');
                $this->load->model('contacts_model');
                $this->load->model('interaction_model');
                $this->load->model('work_time_config_master_model');
                $this->load->model('email_library_model');
                $this->load->model('admin_model');
                $this->load->model('joomla_property_cron_model');
                $this->load->model('package_management_model');
                $this->load->model('ws/property_valuation_searches_model');
                
		//$this->obj = $this->user_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	
    /*
    @Description: Function for Get All Player List
    @Author: Ruchi Shahu
    @Input: - Search value or null
    @Output: - all tips list
    @Date: 16-07-14
    */
   function index()
	{	
		//$postData = $_REQUEST;
 		//extract($_REQUEST);

 		//if($_REQUEST['func'] == 'getuser')
			//$this->getuser($postData);
	}

   /***********************************************************************************************
		    @Description		: Display the List of user
		    @Author     		: Niral Patel 
			@input     			: 
		    @Output     		: 
		    @Date       		: 
		    @Webservices link   http://192.168.0.54/master_panel/ws/user/getuser?name=Robert
			@Webservices link   http://192.168.0.54/master_panel/ws/user/getuser
			
    ************************************************************************************************/
    public function getuser()
    {
		$post_data = $_REQUEST;
		//pr($post_data);exit;
		$match='';
		if(isset($post_data['name']) && !empty($post_data['name']))
		{
			 $match = array("CONCAT(first_name,' ',last_name)"=>$post_data['name']);
		}
		if(!empty($post_data))
		{	
			$data = $this->obj->select_records('',$match,'','like','','', '','id','desc');  
		}
		else
		{
			$data = $this->obj->select_records('','','','','','', '','id','desc');
		}
		if(!empty($data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			$arr['data']=$data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);
    }

    /*
    @Description: Function for Insert New Player data
    @Author: Ruchi Shahu
    @Input: - Details of new Player which is inserted into DB
    @Output: - List of tips with new inserted records
    @Date: 16-07-14
    */
    public function insert_data()
    {
		$match = array('email_address'=>$this->input->get('email_address'));
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0)
		{
			$this->session->set_userdata('email_exist','Email already exist');
		}
		else
		{
			$cdata['created_by'] = 1;
			$cdata['first_name'] = $this->input->get('first_name');
			$cdata['last_name'] = $this->input->get('last_name');
			$cdata['email_address'] = $this->input->get('email_address');
			$password = $this->randr(8);
			$cdata['password'] = $this->common_function_model->encrypt_script($password);
			$cdata['mobile_no'] = $this->input->get('mobile_no');
			$cdata['user_type'] = $this->input->get('user_type');
			$cdata['created_date'] = date('Y-m-d H:i:s');		
			$cdata['status'] = 1;
			 pr($cdata);exit;
			$name = $cdata['first_name']." ".$cdata['last_name'];
			$created_midified_id = $this->obj->insert_record($cdata);	
			// $this->email($cdata['email_address'],$name,$password);
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
		}
        redirect('admin/'.$this->viewName);				
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
    
    /*
    @Description: Function for Update Player
    @Author: Ruchi Shahu
    @Input: - Update details of Player
    @Output: - List with updated Player details
    @Date: 16-07-14
    */
    public function update_data()
    {
	
		$cdata['id'] = $this->input->post('id');
		$match = array('email_address'=>$this->input->post('email_address'));
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0 && $result[0]['id']!=$cdata['id'])
		{
			$this->session->set_userdata('email_exist','Email already exist');
		}
		else
		{
			$cdata['id'] = $this->input->post('id');
			$cdata['first_name'] = $this->input->post('first_name');
			$cdata['last_name'] = $this->input->post('last_name');
			$cdata['email_address'] = $this->input->post('email_address');
			$pass = $this->input->post('password');
			if(!empty($pass))
			{	
				$cdata['password'] =  $this->common_function_model->encrypt_script($this->input->post('password'));
			}
			
			$cdata['mobile_no'] = $this->input->post('mobile_no');
			$cdata['user_type'] = $this->input->post('user_type');
   
			$cdata['modified_date'] = date('Y-m-d H:i:s');		
			$cdata['status'] = 1;
    
			$this->obj->update_record($cdata);			
			
			// Update Player Name in Team Management
			$result = $this->player_model->select_player_trans($this->input->post('id'));
			if(count($result) > 0)
			{
				$traData['player_id'] 	= $result[0]['player_id'];
				$traData['player_name'] = $cdata['first_name']." ".$cdata['last_name'];
				$this->obj->update_player_trans($traData);	
			}
			
			// Update Player1 Name OR Player2 Name in championship_team_trans table
			$match_id =array('is_completed'=>'0');
			$get_championship_id = $this->championship_model->select_records('',$match_id,'','=');
			
			if(count($get_championship_id) > 0)
			{
				$champtra['championship_id'] = $get_championship_id[0]['id'];
				$champtra['player1_id'] = $this->input->post('id');
				$champtra['player2_id'] = $this->input->post('id');
				$get_championship_trans_id = $this->selected_team_model->select_champ_records($champtra);
				//pr($get_championship_trans_id);exit;
				if(!empty($get_championship_trans_id))
				{
					$champtraData['championship_id'] = $get_championship_id[0]['id'];
					if($get_championship_trans_id[0]['player1_id'] == $champtra['player1_id'])
					{
						$champtraData['player1_id'] = $cdata['id'];
						$champtraData['player1_name'] = $cdata['first_name']." ".$cdata['last_name'];
						$this->selected_team_model->update_player1_record($champtraData);	
					}
					else
					{					
						$champtraData['player2_id'] = $cdata['id'];
						$champtraData['player2_name'] = $cdata['first_name']." ".$cdata['last_name'];
						$this->selected_team_model->update_player1_record($champtraData);	
					}
				}
			}// END
			$msg = $this->lang->line('common_edit_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
		}
		$player_id = $this->input->post('id');
		$pagingid = $this->obj->getplayerpagingid($player_id);
		//echo $pagingid;exit;
		redirect('admin/'.$this->viewName.'/'.$pagingid);
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
    }
    /*
    @Description: Function for Delete Player By Admin
    @Author: Ruchi Shahu
    @Input: - Delete id which Player record want to delete
    @Output: - New Player list after record is deleted.
    @Date: 16-07-14
    */
    function delete_record()
    {
        $id = $this->uri->segment(4);
		$returnmsg = $this->obj->delete_record($id);
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	/*
    @Description: Function for User registration Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/user_registration?user_id=1&fname=Test_joomla&mname=Test_joomlam&lname=Test_joomlal&domain=test.com
	function user_registration()
	{
		$post_data = $_REQUEST;
		//pr($post_data);
                $action = $post_data['action'];
                //$cdata['joomla_domain_name'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['joomla_domain_name'] = $domain;
                $err_flag = 0;
                $price_flag = 0;
                if($action == 'insert') 
                {
                    $cdata['first_name'] = '';$cdata['middle_name'] = '';$cdata['last_name'] = '';
                    if($post_data['fname'] != '-')
                        $cdata['first_name'] = $post_data['fname'];
                    if($post_data['mname'] != '-')
                        $cdata['middle_name'] = $post_data['mname'];
                    if($post_data['lname'] != '-')
                        $cdata['last_name'] = $post_data['lname'];
                    $cdata['joomla_user_id'] = $post_data['user_id'];
                    $cdata['joomla_domain_name'] = $domain;
                    $cdata['created_type'] = '6';
                    $cdata['created_date']  = date('Y-m-d H:i:s');
                    $cdata['status'] = '1';
                    $cdata['joomla_contact_type'] = 'Buyer';
                    $cdata['joomla_ip_address'] = !empty($post_data['ip_address'])?$post_data['ip_address']:'';
                }
                else if($action == 'update')
                {
                    //$cdata['joomla_domain_name'] = urldecode($post_data['domain']);
                    $cdata['joomla_contact_type'] = $post_data['contact_type'];
                    $cdata['id'] = $post_data['lw_id'];
                    if(!empty($post_data['timeframe'])) {
                        $cdata['joomla_timeframe'] = $post_data['timeframe'].' Months';
                    }
                    if(!empty($post_data['min_price']) && !empty($post_data['max_price']))
                    {
                        $cdata['price_range_from'] = $post_data['min_price'];
                        $cdata['price_range_to'] = $post_data['max_price'];
                        $price_flag = 1;
                    }
                    
                    if($price_flag == 1)
                    {
                        if($post_data['min_price'] > $post_data['max_price'])
                        {
                            $err_flag = 1;
                        }
                    }
                    
                    if(!empty($post_data['min_area']) && !empty($post_data['max_area']))
                    {
                        $cdata['min_area'] = $post_data['min_area'];
                        $cdata['max_area'] = $post_data['max_area'];
                        //$price_flag = 1;
                    }
                    
                }
                else if($action == 'address_update')
                {
                    $cdata['joomla_address'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['address'])));
                    $cdata['id'] = $post_data['lw_id'];
                }
                else if($action == 'mobile_update')
                {
                    $phdata['phone_no'] = $post_data['contact_number'];
                    $phdata['contact_id'] = $post_data['lw_id'];
                    $phdata['phone_type'] = 0;
                    $phdata['is_default'] = '1';
                    $phdata['status'] = '1';
                }
		$db_name = '';
                if($err_flag == 1)
                {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=false;
                }
                else {

                    //// For Getting Dynamic Database credential and connect to that database
                    $table = "joomla_mapping as jm";
                    $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                    $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                    $match = array('domain'=>$cdata['joomla_domain_name'],'lm.status'=>'1');
                    $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                    
                    //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                    if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                    {
                        /*$newdata1 = array(
                            'host_name'  => $domain_result[0]['host_name'],
                            'db_user_name' =>$domain_result[0]['db_user_name'],
                            'db_user_password' =>$domain_result[0]['db_user_password'],
                            'db_name' =>$domain_result[0]['db_name']
                        );
                        $this->session->set_userdata('db_session', $newdata1);*/
                        
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

                        $this->db = $this->load->database($db['second'], TRUE);
                    }
                    
                    /// END For Getting Dynamic Database credential and connect to that database
                    
                    if($action == 'insert')
                    {
                        $ins_data = $this->user_registration_model->insert_record($cdata);
                                        //echo $ins_data; exit;
                        
                        $edata['contact_id'] = $ins_data;
                        $edata['email_type'] = '0';
                        $edata['email_address'] = $post_data['email'];
                        $edata['is_default '] = '1';
                        $edata['status '] = '1';

                        $ins_data_email = $this->user_registration_model->insert_record_email($edata);
                    
                        // Code for updating livewire id Send by Gopal Patel 06-11-2014
                        $url = $cdata['joomla_domain_name']."/libraries/api/update_lwid.php?lwid=".$ins_data."&userid=".$post_data['user_id'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzidp encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                        $response = curl_exec($ch);
                        curl_close($ch); 
                        
                        /*$match = array('lw_admin_id'=>$id);
                        $result_last_login = $this->last_login_model->select_records('',$match,'','=');*/
                        
                        $email_temp = array(); $admin_email_temp = array();
                        $fields = array('template_name,template_subject,email_message,email_event');
                        $match = array('email_event'=>'5');
                        $autores_res = $this->email_library_model->select_records($fields,$match,'','=');
                        
                        if(!empty($autores_res) && count($autores_res) > 0)
                        {
                            if(count($autores_res) > 1)
                            {
                                foreach($autores_res as $row)
                                {
                                    if($row['template_name'] == 'Joomla User Registration Email')
                                    {
                                        $email_temp = $row;
                                    }
                                }
                            }
                            else {
                                $email_temp = $autores_res[0];
                            }
                        }
                        $this->user_registration_model->sendPasswordMail($post_data['email'],$ins_data,$post_data['fname'],$post_data['lname'],'',$email_temp);
                        
                        $fields = array('template_name,template_subject,email_message,email_event');
                        $match = array('email_event'=>'6');
                        $autores_res = $this->email_library_model->select_records($fields,$match,'','=');                        
                        if(!empty($autores_res) && count($autores_res) > 0)
                        {
                            if(count($autores_res) > 1)
                            {
                                foreach($autores_res as $row)
                                {
                                    if($row['template_name'] == 'Joomla User Registration Email Admin')
                                        $admin_email_temp = $row;
                                }
                            }
                            else {
                                $admin_email_temp = $autores_res[0];
                            }
                        }
                        /*To get Admin user email address and send email. Sanjay Moghariya 30-10-2014*/
                        $fields = array('id,admin_name,email_id');
                        $match = array('user_type'=>'2');
                        $admin_user = $this->user_management_model->select_login_records($fields,$match,'','=','','','','','');

                        if(!empty($admin_user) && count($admin_user) > 0)
                        {
                            $this->user_registration_model->sendPasswordMail($post_data['email'],$ins_data,$post_data['fname'],$post_data['lname'],$admin_user,'',$admin_email_temp);
                        }
                    }
                    else if($action == 'update')
                    {
                        $ins_data = $this->user_registration_model->update_record($cdata);
                        $ins_data = $cdata['id'];
                    }
                    else if($action == 'address_update')
                    {
                        $ins_data = $this->user_registration_model->update_record($cdata);
                        $ins_data = $cdata['id'];
                    }
                    else if($action == 'mobile_update')
                    {
                        $ins_data = $this->contacts_model->insert_phone_trans_record($phdata);
                        $ins_data = $post_data['lw_id'];
                    }

                    if(!empty($ins_data))
                    {
                            $arr['MESSAGE']='SUCCESS';
                            $arr['FLAG']=true;
                            $arr['data']=$ins_data;
                    }
                    else
                    {
                            $arr['MESSAGE']='FAIL';
                            $arr['FLAG']=false;
                    }
                }
		echo json_encode($arr);	
	}
        
	/*
    @Description: Function for Saved Searches Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/saved_searches?uid=1&name=Test_joomla&url=http://192.168.0.12/livewire_crm&where_query=select*fromproperty&pids=1&state=1&domain=test.com&lwid=1
	function saved_searches()
	{
            $post_data = $_REQUEST;

            // insert into joomla_rpl_savesearch

            $cdata['sid'] = $post_data['sid']; // Saved searches id from Joomla website table
            $action = $post_data['action'];
            $cdata['name'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['name'])));
            //$cdata['search_criteria'] = urldecode(utf8_decode($post_data['search_criteria']));
            $cdata['min_price'] = $post_data['min_price'];
            $cdata['max_price'] = $post_data['max_price'];
            $cdata['bedroom'] = $post_data['bedroom'];
            $cdata['bathroom'] = $post_data['bathroom'];
            $cdata['min_area'] = $post_data['min_area'];
            $cdata['max_area'] = $post_data['max_area'];
            $cdata['min_year_built'] = $post_data['min_year_built'];
            $cdata['max_year_built'] = $post_data['max_year_built'];
            $cdata['fireplaces_total'] = $post_data['fireplaces_total'];
            $cdata['min_lotsize'] = $post_data['min_lotsize'];
            $cdata['max_lotsize'] = $post_data['max_lotsize'];
            $cdata['garage_spaces'] = $post_data['garage_spaces'];
            
            //$cdata['search_criteria'] = $post_data['search_criteria']));
            
            $domain = urldecode($post_data['domain']);
            $domain = str_replace('www.', '', $domain);
            $domain = trim($domain,'/');
            $cdata['domain'] = $domain;
            //$cdata['domain'] 	= $post_data['domain'];
            $cdata['created_type'] 	= '2';
            
            if(!empty($action) && $action == 'update') {
                $cdata['modified_date']  = date('Y-m-d H:i:s');	
            } else {
                $cdata['uid'] = $post_data['uid'];
                $cdata['lw_admin_id'] 	= $post_data['lwid'];
                $cdata['created_date']  = date('Y-m-d H:i:s');	
            }
                
            $cdata['status'] = '1';
            //$pids = $_REQUEST['pids'];

            //// For Getting Dynamic Database credential and connect to that database
            $table = "joomla_mapping as jm";
            $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
            $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
            $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
            $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

            //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
            if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
            {
                /*$newdata1 = array(
                    'host_name'  => $domain_result[0]['host_name'],
                    'db_user_name' =>$domain_result[0]['db_user_name'],
                    'db_user_password' =>$domain_result[0]['db_user_password'],
                    'db_name' =>$domain_result[0]['db_name']
                );
                $this->session->set_userdata('db_session', $newdata1);*/

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

                //pr($db['second']);exit;

                //$this->legacy_db = $this->load->database($db['second'], TRUE);
                $this->db = $this->load->database($db['second'], TRUE);

                /*$this->legacy_db->select ('*');
                $this->legacy_db->from ('login_master');
                //$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
                $query = $this->legacy_db->get();
                $udata = $query->result_array();
                //pr($udata);exit;*/
            }

            /// END For Getting Dynamic Database credential and connect to that database
            if(!empty($action) && $action == 'update') {
                $fields = array('id');
                $match = array('sid'=>$cdata['sid'],'domain'=>$cdata['domain']);
                $ss_data = $this->saved_searches_model->select_records($fields,$match,'','=');
                if(!empty($ss_data)) {
                    $ins_data = $this->saved_searches_model->update_ss_record($cdata);
                    $ins_data = $cdata['sid'];
                }
            } else {
                $ins_data = $this->saved_searches_model->insert_record($cdata);
            }
            
            if(!empty($ins_data))
            {
                    $arr['MESSAGE']='SUCCESS';
                    $arr['FLAG']=true;
                    $arr['data']=$ins_data;
            }
            else
            {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=false;
            }
            echo json_encode($arr);	
	}
        
		
	/*
    @Description: Function for Properties Viewed Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/properties_viewed?User_id=1&log_date=10-8-2014&views=200&mlsid=1111&propery_name=shivranjni&domain=test.com&lwid=1
	function properties_viewed()
	{
		$post_data = $_REQUEST;
		
		// insert into joomla_rpl_track
		
		$cdata['uid'] 		= $post_data['User_id'];
		$cdata['log_date'] 	= $post_data['log_date'];
		//$cdata['views'] 	= $post_data['views'];
		$cdata['mlsid'] 	= $post_data['mlsid'];
		$cdata['propery_name'] 	= mysql_real_escape_string(urldecode(utf8_decode($post_data['propery_name'])));
                //$cdata['domain'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		//$cdata['domain'] 	= $post_data['domain'];
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['created_date']  = date('Y-m-d H:i:s');	
		$cdata['status'] = '1';
		
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                    /*$this->legacy_db->select ('*');
                    $this->legacy_db->from ('login_master');
                    //$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
                    $query = $this->legacy_db->get();
                    $udata = $query->result_array();
                    //pr($udata);exit;*/
                }
                    
                /// END For Getting Dynamic Database credential and connect to that database
                
                $fields = array('id,views');
                $match = array('mlsid'=>$post_data['mlsid'],'domain'=>$domain,'lw_admin_id'=>$post_data['lwid']);
                $property_data = $this->properties_viewed_model->select_records($fields,$match,'','=');
                if(!empty($property_data))
                {
                    $cdata['id'] = $property_data[0]['id'];
                    $cdata['views'] = $property_data[0]['views'] + 1;
                    $ins_data = $this->properties_viewed_model->update_record($cdata);
                    $ins_data = $property_data[0]['id'];
                }
                else
                {
                    $cdata['views'] = 1;
                    $ins_data = $this->properties_viewed_model->insert_record($cdata);
                }
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			//$sel_data = $this->user_model->se($ins_data);
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
	
	
	/*
    @Description: Function for Last Login Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/last_login?User_id=1&log_date=10-8-2014&ip=192.168.0.12&domain=test.com&lwid=1
	function last_login()
	{
		$post_data = $_REQUEST;
		
		//insert into joomla_rpl_log
		
		$cdata['uid'] 		= $post_data['User_id'];
		//$cdata['log_date'] 	= $post_data['log_date'];
		
		//echo "post date".$post_data['log_date'];
		$cdata['ip'] 	= $post_data['ip'];
		$time_zone = $this->config->item('default_timezone');
		//$cdata['domain'] 	= $post_data['domain'];
                //$cdata['domain'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['created_date']  = date('Y-m-d H:i:s');	
		$cdata['status'] = '1';
		// get from joomla_mapping table lw_admin_id
		
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                    /*$this->legacy_db->select ('*');
                    $this->legacy_db->from ('login_master');
                    //$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
                    $query = $this->legacy_db->get();
                    $udata = $query->result_array();
                    //pr($udata);exit;*/
					if(!empty($domain_result[0]['timezone']))
						{
							$time_zone = $domain_result[0]['timezone'];
						}
						
                }
                    
                /// END For Getting Dynamic Database credential and connect to that database

		$log_date = str_replace('::',' ',$post_data['log_date']); // This For Replace to joomla site Date
		$fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
		$fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
		$cdata['log_date'] 	= $fdate->format('Y-m-d H:i:s');
	//	pr($cdata);exit;
		$ins_data = $this->last_login_model->insert_record($cdata);
		//echo $this->db->last_query();exit;
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
	
	
	/*
    @Description: Function for Favorite Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/favorite?uid=1&pid=1&mlsid=1&propery_name=shivranjni&date=10-8-2014&domain=test.com&lwid=1
	function favorite()
	{
		$post_data = $_REQUEST;
		
		//insert into joomla_rpl_bookmarks
		
		$cdata['uid'] 		= $post_data['uid'];
		$cdata['pid'] 		= $post_data['pid'];
		$cdata['mlsid']		= $post_data['mlsid'];
		$cdata['propery_name'] 	= mysql_real_escape_string(urldecode(utf8_decode($post_data['propery_name'])));
		$cdata['date'] 	= $post_data['date'];
		//$cdata['domain'] 	= $post_data['domain'];
                //$cdata['domain'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['created_date']  = date('Y-m-d H:i:s');	
		$cdata['status'] = '1';
                $action = $post_data['action'];
		
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                //echo $this->db->last_query();
               // pr($domain_result);exit;
                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                    /*$this->legacy_db->select ('*');
                    $this->legacy_db->from ('login_master');
                    //$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
                    $query = $this->legacy_db->get();
                    $udata = $query->result_array();
                    //pr($udata);exit;*/
                }
                    
                /// END For Getting Dynamic Database credential and connect to that database
                if($action == 'delete')
                {
                    $dfdata['uid'] 		= $post_data['uid'];
                    $dfdata['pid'] 		= $post_data['pid'];
                    $dfdata['lw_admin_id'] 	= $post_data['lwid'];
                    $ins_data = $this->favorite_model->delete_fav_record($dfdata);
                    $ins_data = $dfdata['lw_admin_id'];
                }
                else {
                    $fields = array('id');
                    $match = array('lw_admin_id'=>$cdata['lw_admin_id'],'pid'=>$cdata['pid'],'mlsid'=>$cdata['mlsid']);
                    $fresult = $this->favorite_model->select_records($fields,$match,'','=');
                    if(empty($fresult)) {
                        $ins_data = $this->favorite_model->insert_record($cdata);
                    }
                }
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
        
    /*
        @Description: Function for send email once property status changed whom added into favorite, saved searches, valuation searches
        @Author     : Sanjay Moghariya
        @Input      : property id, mlsid, status, lw_id
        @Output     : Send Email
        @Date       : 05-11-14
    */
    function change_property_status()
    {
        $post_data = $_REQUEST;

        $cdata['pid'] 	= $post_data['pid'];
        $cdata['mlsid']	= $post_data['mlsid'];
        $cdata['status'] = $post_data['status'];
        $lw_ids = $post_data['lwid'];
        $sdomain_name = $post_data['sdomain'];
        
        $favlw_id  = $post_data['flwid'];
        $fdomain = $post_data['fdomain'];
        
        $vrlw_id  = $post_data['vaid'];
        $vrdomain = $post_data['vdomain'];
        
        //$url = "http://seattle.livewiresites.com/libraries/api/propertydata.php?id=".$post_data['pid'];
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/propertydata.php?id=".$post_data['pid'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzip encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $response = curl_exec($ch);
        curl_close($ch);
        $response = (json_decode($response, true));
        if(!empty($response['name'])) {
            $data['property_name'] = $response['name'];
            $data['property_price'] = $response['price'];
            //$ssdata['property_name'] = $response['name'];
        }
        //A: Available, P: Pending, S: Sold, PS: Pending Status
        if($post_data['status'] == 'A')
            $data['status'] = 'Available';
        else if($post_data['status'] == 'P')
            $data['status'] = 'Pending';
        else if($post_data['status'] == 'S')
            $data['status'] = 'Sold';
        else if($post_data['status'] == 'PS')
            $data['status'] = 'Pending Status';
        
        // Favorite property user list
       
        // NEW 15-11-2014
        
        if(!empty($fdomain) && count($fdomain) > 0)
        {
            $i = 0;
            foreach($fdomain as $ffdomain)
            {
                $ffdomain_name = str_replace('www.', '', $ffdomain);
                $ffdomain_name = trim($ffdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$ffdomain_name,'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                
                $fulist = array();
                if(!empty($favlw_id))
                {
                    if(!empty($favlw_id[$i]))
                    {   
                        $match = array('cm.id = '=>$favlw_id[$i]);
                        $fulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($fulist))
                        {
                            $from = $this->config->item('admin_email');

                            $full_name = 'LiveWireCRM';
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Favorite property status has been changed - Livewire CRM";
                            $agent_name = '';
                            if(!empty($fulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$fulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($fulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$fulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($fulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$fulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$fulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            if(!empty($fulist[0]['email_address']))
                            {
                                $to = $fulist[0]['email_address'];
                                $data['name'] = ucwords($fulist[0]['user_name']); 
                                $data['tabname'] = 'F';
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                //echo $msg;exit;
                                echo "Favorite<br />";
                                echo $to.'<br />';
                              
                                if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Success";
                                else
                                    echo "Mail Not sent";
                            }
                        }
                    }
                }
                $i++;
            }
        } // END Favorite
        // End NEw 15-11-2014
        
        
        // Saved searches property user list
        if(!empty($sdomain_name) && count($sdomain_name) > 0)
        {
            $i = 0;
            foreach($sdomain_name as $ssdomain)
            {
                $ssdomain_name = str_replace('www.', '', $ssdomain);
                $ssdomain_name = trim($ssdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$ssdomain_name,'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);
                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                $sulist = array();
                //pr($lw_ids);exit;
                if(!empty($lw_ids))
                {
                    if(!empty($lw_ids[$i]))
                    {   
                        $match = array('cm.id = '=>$lw_ids[$i]);
                        $sulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($sulist))
                        {
                            $from = $this->config->item('admin_email');

                            $full_name = 'LiveWireCRM';
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Saved Searches property status has been changed - Livewire CRM";
                            $agent_name = '';
                            if(!empty($sulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$sulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($sulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$sulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($sulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$sulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$sulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            
                            if(!empty($sulist[0]['email_address']))
                            {
                                $to = $sulist[0]['email_address'];
                                $data['name'] = ucwords($sulist[0]['user_name']);
                                $data['tabname'] = 'SS';
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                //echo $msg;exit;
                                echo "Saved Searches<br />";
                                echo $to.'<br />';
                                
                                if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Sent";
                                else
                                    echo "Mail not send";
                            }
                        }
                    }
                }
                //// END
                $i++;
            }
        } // End Saved Searches
        
        // Code for Valuation report search criteria property list status change 29-11-2014
        if(!empty($vrdomain) && count($vrdomain) > 0)
        {
            $i = 0;
            foreach($vrdomain as $vr_domain)
            {   
                $vrdomain_name = str_replace('www.', '', $vr_domain);
                $vrdomain_name = trim($vrdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$vrdomain_name,'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

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

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                $vrulist = array();
                //pr($lw_ids);exit;
                if(!empty($vrlw_id))
                {
                    if(!empty($vrlw_id[$i]))
                    {   
                        $match = array('cm.id = '=>$vrlw_id[$i]);
                        $vrulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($vrulist))
                        {
                            $from = $this->config->item('admin_email');

                            $full_name = 'LiveWireCRM';
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Valuation Searches property status has been changed - Livewire CRM";
                            $agent_name = '';
                            if(!empty($vrulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$vrulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($vrulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$vrulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($vrulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$vrulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$vrulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            
                            if(!empty($vrulist[0]['email_address']))
                            {
                                $to = $vrulist[0]['email_address'];
                                $data['name'] = ucwords($vrulist[0]['user_name']);
                                $data['tabname'] = 'VR';
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                echo "Valuation Searhced"."<br/>";
                                echo $to.'<br />';
                                if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Sent";
                                else
                                    echo "Mail not send";
                            }
                        }
                    }
                }
                //// END
                $i++;
            }
        }
        
        $arr['MESSAGE']='SUCCESS';
        $arr['FLAG']=true;
        //$arr['EmailMessage']=$email_msg;
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for send email if new property matches to user saved searches
        @Author     : Sanjay Moghariya
        @Input      : property ids, lw_id
        @Output     : Send Email
        @Date       : 07-11-14
    */
    function new_property_email()
    {
        $post_data = $_REQUEST;
        $cdata = array();
        $cdata['pid'] 	= $post_data['cron_pid'];
        $cdata['lw_id'] = $post_data['lw_id'];
        $domain_name = $post_data['domain'];
        $domain_name = str_replace('www.', '', $domain_name);
        $domain_name = trim($domain_name,'/');
        
        //// For Getting Dynamic Database credential and connect to that database
        $table = "joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
        $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('domain'=>$domain_name,'lm.status'=>'1');
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
        //echo $this->db->last_query();
        //
        //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
        if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
        {
            /*$newdata1 = array(
                'host_name'  => $domain_result[0]['host_name'],
                'db_user_name' =>$domain_result[0]['db_user_name'],
                'db_user_password' =>$domain_result[0]['db_user_password'],
                'db_name' =>$domain_result[0]['db_name']
            );
            $this->session->set_userdata('db_session', $newdata1);*/

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

            //pr($db['second']);exit;

            //$this->legacy_db = $this->load->database($db['second'], TRUE);
            $this->db = $this->load->database($db['second'], TRUE);

        }

        /// END For Getting Dynamic Database credential and connect to that database
        

        $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address');
        $table = ' contact_master cm';
        $join_tables = array(
            'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
        );
        
        $match = array('cm.id '=> $cdata['lw_id']);
        $sulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);

        if(!empty($sulist) && !empty($sulist[0]['email_address']))
        {
            if(!empty($cdata['pid']))
            {
                //$property_ids = explode(',',$cdata['pid']);
                $property_ids = $cdata['pid'];
                //pr($property_ids);exit;
                if(!empty($property_ids))
                {
                    foreach($property_ids as $row)
                    {
                        //$url = "http://seattle.livewiresites.com/libraries/api/propertydata.php?id=".$row;
                        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                        $url = $joomla_link."/libraries/api/propertydata.php?id=".$row;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $response = (json_decode($response, true));
                        if(!empty($response['name'])) {
                            $data['property_name'][] = $response['name'];
                            $data['property_description'][] = $response['description'];
                        }
                    }
                }
            }
        
            $sub = "New Property added that matches your Saved Searches criteria - Livewire CRM";
            $from=$this->config->item('admin_email');

            $full_name = 'LiveWireCRM';
            $from = $full_name.'<'.$from.'>';
            $headers = "From: " . $from . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $to = $sulist[0]['email_address'];
            
            $data['name'] = ucwords($sulist[0]['user_name']);
            //$from=$this->config->item('admin_email');
            if(!empty($data['property_name'])) {
                $msg   = $this->load->view('ws/new_property_email', $data, TRUE);
                if(mail($to,$sub,$msg,"-f".$headers)) {
                    //echo $msg;exit;
                    $arr['MESSAGE']='EMAIL SUCCESS';
                    $arr['FLAG']=true;
                } else {
                    $arr['MESSAGE']='EMAIL FAIL';
                    $arr['FLAG']=true;
                }
            }
        }
        else {
            $arr['MESSAGE']='FAIL';
            $arr['FLAG']=FALSE;
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for send email with pdf according to property cron setting
        @Author     : Sanjay Moghariya
        @Input      : State, City, neighborhood
        @Output     : Send Email with pdf (data fetch from zillow api)
        @Date       : 19-11-14
    */
    function get_neighborhood_data()
    {
        $state = $_REQUEST['state'];
        $city = $_REQUEST['city'];
        $neighborhood = $_REQUEST['neighborhood'];
        $url = "http://www.zillow.com/webservice/GetDemographics.htm?zws-id=X1-ZWz1b7njhnhvkb_6r0dc&state=".$state."&city=".$city."&neighborhood=".$neighborhood;
        $xml = simplexml_load_file($url);
        
        /*$json = json_encode($xml);
        $array = json_decode($json,TRUE);
        pr($array);
        exit;
        */
        
        $response = $xml->message->code;
        if($response == '0')
        {
            $data['zillow_data'] = $xml;
            $pdf_html = $this->load->view('ws/neighborhood_report_pdf', $data, TRUE);
            //pr($pdf_html);
            //exit;
            ///// PDF GENERATE CODE
            
            $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
            $stylesheet = file_get_contents('css/pdfcrm.css'); // external css
            $mypdf->WriteHTML($stylesheet,1);
            $base_url = $this->config->item('base_url');
            $logo = $base_url."images/logo.png";
            //$mypdf->SetWatermarkImage($logo);
            //$mypdf->showWatermarkImage = true;
            $img_path = $this->config->item('image_path');
            $html = '';
            //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Neighborhood Data</div>', 'O', true);
            $mypdf->SetHTMLFooter('
                <table border="0" cellpadding="0" >
                    <tr>
                        <td class="footer">Neighborhood Data</td>
                        <td class="footer1"></td>
                    </tr>
                </table>
            ', 'O', true);
            
            $html .= $pdf_html;
            
            $mypdf->WriteHTML($html);
            
            //$mypdf->Output('neighborhood_data_'.date('m-d-Y').'.pdf','D');
            
            $filename = 'neighborhood_data_'.date('m-d-Y').'.pdf';
            
            /*$fileatt = $mypdf->Output($filename, 'S');
            $attachment = chunk_split($fileatt);
            $eol = PHP_EOL;
            $separator = md5(time());
            */
            $sub = "Property Valuation Report - Livewire CRM";
            $from = 'demotops@gmail.com';

            $full_name = 'LiveWireCRM';
            $from = $full_name.'<'.$from.'>';
            
            $content = $mypdf->Output('', 'S');

            $content = chunk_split(base64_encode($content));
            $mailto = 'sanjay.moghariya@tops-int.com';
            $from_name = 'LiveWireCRM';
            $from_mail = 'demotops@gmail.com';
            $replyto = 'demotops@gmail.com';
            $uid = md5(uniqid(time()));
            $subject = 'Property Valuation Report - Livewire CRM';
            $message = 'Please find attached pdf file which showing property valuation data based on neighborhood criteria.';

            $header = "From: ".$from_name." <".$from_mail.">\r\n";
            $header .= "Reply-To: ".$replyto."\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
            $header .= "This is a multi-part message in MIME format.\r\n";
            $header .= "--".$uid."\r\n";
            $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $header .= $message."\r\n\r\n";
            $header .= "--".$uid."\r\n";
            $header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
            $header .= "Content-Transfer-Encoding: base64\r\n";
            $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
            $header .= $content."\r\n\r\n";
            $header .= "--".$uid."--";
            $is_sent = @mail($mailto, $subject, "", $header);

            //$mypdf->Output();
            
            //$to = 'sanjay.moghariya@tops-int.com';
            //$msg   = $this->load->view('ws/user_register_email', $data, TRUE);
            //mail($to,$sub,$message,$headers);
            exit;
            ///////// END PDF
            
        }
        else
        {
            echo "Result not found.";
        }
        exit;
    }
    
    /*
        @Description: Function for get WordPress order details (Admin reg.)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Insert data into Database
        @Date       : 26-11-2014
    */
    function wordpress_order_details()
    {
        $cdata['name'] 		= mysql_real_escape_string(urldecode($_REQUEST['name']));
        $cdata['email'] 	= mysql_real_escape_string(urldecode($_REQUEST['email']));
        $cdata['address']	= mysql_real_escape_string(urldecode($_REQUEST['address']));
        $cdata['phone']		= mysql_real_escape_string(urldecode($_REQUEST['phone']));
        $cdata['no_of_users']	= mysql_real_escape_string(urldecode($_REQUEST['no_of_users']));
        $cdata['password'] = !empty($_REQUEST['password'])?mysql_real_escape_string(urldecode($_REQUEST['password'])):'123456';
        $cdata['email_counter']	= mysql_real_escape_string(urldecode($_REQUEST['email_counter']));
        $cdata['sms_counter']	= mysql_real_escape_string(urldecode($_REQUEST['sms_counter']));
        $cdata['package_id']	= mysql_real_escape_string(urldecode($_REQUEST['package_id']));
        
        if(!empty($cdata['email']))
        {
            // Check Admin email exists or not.
            
            $fields = array('id,email_id');
            $match = array('email_id'=>$cdata['email']);
            $admin_res = $this->admin_model->get_user($fields,$match,'','=');
            if(!empty($admin_res) && count($admin_res) > 0)
            {
                $arr['MESSAGE'] = 'FAIL';
                $arr['FLAG'] = FALSE;
                $arr['data'] = 'Email Already Exists.';
            }
            else
            {
                $lastId = $this->create_database($cdata);
                if(!empty($lastId))
                {
                    $arr['MESSAGE']='SUCCESS';
                    $arr['FLAG']=true;
                    $arr['data']=$lastId;
                }
                else
                {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=FALSE;
                }
            }
        }
        else
        {
            $arr['MESSAGE']='FAIL';
            $arr['FLAG']=FALSE;
            $arr['data']='Please enter Email Address';
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for create new database for user
        @Author     : Sanjay Moghariya (Copy from admin_management_control-> insert_data())
        @Input      : 
        @Output     : Create new database for user
        @Date       : 26-11-2014
    */
    function create_database($admin_data)
    {
        $newdatabasename = $this->admin_model->getnewdbname()+1;
        $databasename = $this->config->item('parent_db_prefix').md5(uniqid().$newdatabasename);
        $is_db_created = $this->admin_model->createnewdb($databasename);

        // Insert entry in master database of created db START

        $databaseusername = $this->config->item('parent_db_user_prefix').$newdatabasename;

        $is_dbuser_created = $this->admin_model->createnewdbuser($databaseusername);

        $adata['admin_name'] 		= $admin_data['name'];
        $adata['email_id']		= $admin_data['email'];
        $adata['password'] 		= $this->common_function_model->encrypt_script($admin_data['password']);
        $adata['db_name']		= $databasename;
        $adata['host_name']		= "localhost";
        $adata['db_user_name']		= $databaseusername;
        $adata['db_user_password']	= $databaseusername;
        $adata['user_type'] 		= '2';
        $adata['address']	= $admin_data['address'];
        $adata['phone']		= $admin_data['phone'];
        $adata['number_of_users_allowed']	= $admin_data['no_of_users'];
        $adata['remain_emails']	= !empty($admin_data['email_counter'])?$admin_data['email_counter']:0;
        $adata['remain_sms']	= !empty($admin_data['sms_counter'])?$admin_data['sms_counter']:0;
        
        // For getting Package data from Id
        if(!empty($admin_data['package_id']))
        {
            $match = array('id'=>$admin_data['package_id']);
            $result = $this->package_management_model->select_records('',$match,'','=');
            if(!empty($result) && count($result) > 0)
            {
                $adata['remain_emails'] = $result[0]['email_counter'];
                $adata['remain_sms'] = $result[0]['sms_counter'];
                //$adata['remain_contacts'] = $result[0]['contacts_counter'];
            }
        }
        
        //$cdata['created_by'] 		= $this->superadmin_session['id'];
        $adata['created_date'] 		= date('Y-m-d H:i:s');		
        $adata['status'] 		= '1';

        $lastId=$this->admin_model->insert_user($adata);
        if($is_db_created)
        {
            $parent_db = $this->config->item('parent_db_name');
            $child_db = $databasename;
            $this->admin_model->copyonedbtoother($parent_db,$child_db,$lastId,$databaseusername);
        }
        
        /*
            $url = "http://topsdemo.in/qa/livewire_crm/v.1.7/ws/user/wordpress_order_details?name=".$name."&email=".$email."&password=".$password."&address=".$address."&phone=".$phone."&no_of_users=".$no_of_users;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzidp encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
            $response = curl_exec($ch);
            curl_close($ch);
        */
        return $lastId;
    }
    
    /*
        @Description: Function for insert valuation saved searches
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 02-12-2014
    */
    function valuation_saved_searches()
    {
        $post_data = $_REQUEST;
        //pr($_REQUEST)
        $cdata['lw_admin_id'] = $post_data['lwid'];
        $cdata['joomla_uid'] = $post_data['uid'];
        $cdata['search_address'] = urldecode($post_data['address']);
        $cdata['city'] = urldecode($post_data['city']);
        $cdata['state'] = urldecode($post_data['state']);
        $cdata['zip_code'] = urldecode($post_data['zip']);
        $domain = urldecode($post_data['domain']);
        $domain_name = str_replace('www.', '', $domain);
        $domain_name = trim($domain_name,'/');
        $cdata['domain'] = $domain_name;
        $cdata['date'] = $post_data['date'];	
        $cdata['send_report'] = 'Yes';	
        $cdata['report_timeline'] = 'Weekly';
        $cdata['created_date'] = date('Y-m-d H:i:s');
        $cdata['status'] = '1';
        $reg_flag = $post_data['reg'];
        
        /*$cdata['avg_listing_link'] = "http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=average_listing_price&city=".$cdata['city']."&state=".$cdata['state'];
        $cdata['listing_volume_link'] = "http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=listing_volume&city=".$cdata['city']."&state=".$cdata['state'];
        $cdata['sales_volume_link'] = "http://graphs.trulia.com/tools/chart/graph.png?version=141&width=300&height=200&type=qma_sales_volume&city".$cdata['city']."&state=".$cdata['state'];
        */
        //// For Getting Dynamic Database credential and connect to that database
        $table = "joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
        $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

        if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
        {
            /*$newdata1 = array(
                'host_name'  => $domain_result[0]['host_name'],
                'db_user_name' =>$domain_result[0]['db_user_name'],
                'db_user_password' =>$domain_result[0]['db_user_password'],
                'db_name' =>$domain_result[0]['db_name']
            );
            $this->session->set_userdata('db_session', $newdata1);*/

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

            $this->db = $this->load->database($db['second'], TRUE);
        }
        /// END For Getting Dynamic Database credential and connect to that database
       
        if($reg_flag == 1)
        {
            $fields = array('id,joomla_contact_type');
            $match = array('id'=>$cdata['lw_admin_id']);
            $reg_data = $this->user_registration_model->select_records($fields,$match,'','=');
            if(!empty($reg_data) && $reg_data[0]['joomla_contact_type'] == 'Buyer')
            {
                $update_data['id'] = $cdata['lw_admin_id'];
                $update_data['joomla_contact_type'] = "Buyer/Seller";
                $this->user_registration_model->update_record($update_data);
            }
        }
        else {
            $update_data['id'] = $cdata['lw_admin_id'];
            $update_data['joomla_contact_type'] = "Seller";
            $this->user_registration_model->update_record($update_data);
        }
        
        $ins_data = $this->property_valuation_searches_model->insert_record($cdata);
        //echo $this->db->last_query();
        //exit;
        if(!empty($ins_data))
        {
                $arr['MESSAGE']='SUCCESS';
                $arr['FLAG']=true;
                $arr['data']=$ins_data;
        }
        else
        {
                $arr['MESSAGE']='FAIL';
                $arr['FLAG']=false;
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for send email with pdf according to property cron setting (Weekly)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 28-11-2014
    */
    function get_valuation_cron_weekly()
    {
        $db_name = $this->config->item('parent_db_name');
        $fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
        $match = array('user_type'=>'2','status'=>'1');
        $all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
        $merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
        $all_admin1 = array_merge($all_admin,$merge_db);
        //pr($all_admin1);exit;

        if(!empty($all_admin1))
        {
            foreach($all_admin1 as $row)
            {
                $db_name1 = $row['db_name'];
                if(!empty($db_name1))
                {
                    //echo 'Hello';exit;
                    $table = $db_name1.".joomla_property_cron_master jpcm";
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Weekly');
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    //pr($cron_data);
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['state'].", ".$row['country']);
                            //$url = "http://seattle.livewiresites.com/libraries/api/valution_report.php?fulladdr=".$addr."&radius=".$row['radius_limit'];
                            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                            $url = $joomla_link."/libraries/api/valution_report.php?fulladdr=".$addr."&radius=".$row['radius_limit'];
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                            // This is what solved the issue (Accepting gzip encoding)
                            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                            $response = curl_exec($ch);
                            curl_close($ch);
                            $response = (json_decode($response, true));
//pr($reponse);
                            if(!empty($response['data']))
                            {
                                $pdata = array();
                                foreach($response['data'] as $property_data)
                                {
                                    // Get property details from property id
                                    //$url = "http://seattle.livewiresites.com/libraries/api/propertydata.php?id=".$property_data['id'];
                                    $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                                    $url = $joomla_link."/libraries/api/propertydata.php?id=".$property_data['id'];
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                                    // This is what solved the issue (Accepting gzip encoding)
                                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                                    $property_response = curl_exec($ch);
                                    curl_close($ch);
                                    $property_response = (json_decode($property_response, true));
                                    if(!empty($property_response['name']))
                                    {
                                        $pdata['property_name'][] = $property_response['name'];
                                        $pdata['property_description'][] = $property_response['description'];
                                        $pdata['price'][] = $property_response['price'];
                                    }
                                }
                                
                                ///////// PDF GENERATE CODE /////////sssss
                                $pdf_html = $this->load->view('ws/valuation_report_pdf', $pdata, TRUE);

                                //pr($pdf_html);exit;
                                
                                $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                $stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                $mypdf->WriteHTML($stylesheet,1);
                                $base_url = $this->config->item('base_url');
                                $logo = $base_url."images/logo.png";
                                //$mypdf->SetWatermarkImage($logo);
                                //$mypdf->showWatermarkImage = true;
                                $img_path = $this->config->item('image_path');
                                $html = '';
                                $mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;

                                $mypdf->WriteHTML($html);

                                $filename = 'valuation_data_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                $from_mail = $this->config->item('admin_email');
                                //$from_mail = 'demotops@gmail.com';
                                //$replyto = 'demotops@gmail.com';
                                
                                $email_temp = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'9');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);

                                if(!empty($autores_res) && count($autores_res) > 0)
                                {
                                    if(count($autores_res) > 1)
                                    {
                                        foreach($autores_res as $row)
                                        {
                                            if($row['template_name'] == 'Valuation Report')
                                            {
                                                $email_temp = $row;
                                            }
                                        }
                                    }
                                    else {
                                        $email_temp = $autores_res[0];
                                    }
                                }
                                
                                if(!empty($email_temp['template_subject']))
                                    $subject = $email_temp['template_subject'];
                                else
                                    $subject = 'Property Valuation Report - Livewire CRM';
                                
                                $table = $db_name1.".joomla_property_cron_trans as jpct";
                                $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,jpct.joomla_property_cron_master_id,jpct.contact_id','cm.first_name','cm.last_name','cet.email_address as email_id');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                //pr($contact_data);exit;
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';
                                        if(!empty($con['created_by']))
                                        {
                                            $table = $db_name1.".login_master as lm";   
                                            $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                            $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                            $wherestring = 'lm.id = '.$con['created_by'];
                                            $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                            if(!empty($agent_datalist))
                                            {
                                                if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                                    $agent_name = $agent_datalist[0]['admin_name'];
                                                else
                                                    $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                            }
                                        }
                                        if(!empty($email_temp['email_message']))
                                        {
                                            $emaildata = array(
                                                'Date'=>date('Y-m-d'),
                                                'Day'=>date('l'),
                                                'Month'=>date('F'),
                                                'Year'=>date('Y'),
                                                'Day Of Week'=>date("w",time()),
                                                'Agent Name'=>$agent_name,
                                                'Contact First Name'=>ucwords($con['first_name']),
                                                'Contact Spouse/Partner First Name'=>$con['spousefirst_name'],
                                                'Contact Last Name'=>ucwords($con['last_name']),
                                                'Contact Spouse/Partner Last Name'=>$con['spouselast_name'],
                                                'Contact Company Name'=>$con['company_name']
                                            );

                                            $pattern = "{(%s)}";
                                            $map = array();

                                            if($emaildata != '' && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($email_temp['email_message'], $map);
                                                $data['temp_msg'] = $output;
                                            }
                                        }
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['second_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        $data['country'] = $row['country'];
                                        $data['city'] = $row['city'];
                                        $data['state'] = $row['state'];
                                        $data['radius'] = $row['radius_limit'];
                                        
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);  
                                        //pr($message);exit;
                                        //echo $message;exit;
                                        $mailto = $con['email_id'];
                                        $uid = md5(uniqid(time()));
                                        $header = "From: ".$from_name." <".$from_mail.">\r\n";
                                        //$header .= "Reply-To: ".$replyto."\r\n";
                                        $header .= "MIME-Version: 1.0\r\n";
                                        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
                                        $header .= "This is a multi-part message in MIME format.\r\n";
                                        $header .= "--".$uid."\r\n";
                                        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                        //$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
                                        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                                        $header .= $message."\r\n\r\n";
                                        $header .= "--".$uid."\r\n";
                                        $header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
                                        $header .= "Content-Transfer-Encoding: base64\r\n";
                                        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
                                        $header .= $content."\r\n\r\n";
                                        $header .= "--".$uid."--";
                                        //$mailto = 'demo1tops@gmail.com';
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                        pr($mailto);
                                        if($is_sent)
                                            echo "Email Sent Successfully.";
                                        else
                                            echo "Email Not Send";
                                    }
                                }
                                //$mypdf->Output();
                                //echo "Email Sent Successfully.";
                                ///////// END PDF /////////
                            }
                            else
                            {
                                echo "Result not found.";
                            }
                        }
                    }
                }
            }
        }
    }
}