<?php
/*
    @Description: login controller
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-2014
	
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct() 
    {
        parent::__construct();
		
		$this->load->model('common_function_model');
		$this->load->model('user_management_model');
        $this->data = array();        
		
    }

    public function index() 
    {
    	
		//$forgot=$this->input->post('forgot_email');
        $user_session = $this->session->userdata($this->lang->line('common_user_session_label')); 
		$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label')); 
		$this->message_session = $this->session->userdata('message_session');   
		
		//pr($this->session->userdata($this->lang->line('common_admin_session_label')));exit;  
       
        if ($user_session['active'] === TRUE)
        {
           redirect('user/dashboard');
        }
		else if ($admin_session['active'] === TRUE)
        {
           redirect('admin/dashboard');
        }
		else if ($superadmin_session['active'] === TRUE)
        {
           redirect('superadmin/dashboard');
        }

        else
		{
			$this->do_login();
		} 
		
    }
    
    /*
        @Description : Check Login is valid or not (User login)
        @Author      : Niral Patel
        @Input       : useremail, passowrd and / or useremail
        @Output      : true or false
        @Date        : 06-05-14
    */
    public function do_login() 
    {
		/*$str = $this->common_function_model->decrypt_script('');
		echo $str;exit;*/
		
        $email = $this->input->post('email');
        $password = $this->common_function_model->encrypt_script($this->input->post('password'));
        $forgot_password = $this->input->post('forgot_email');
	
        if($forgot_password)
        {
            $this->forgetpw_action();
        }
        else
        {   
            if($email && $password)
            {               
                $this->session->unset_userdata('all_passadmin_account_session');
				  $field = array('id','user_type','user_id','email_id','status','admin_name','host_name','db_user_name','db_user_password','db_name,created_by,timezone');
				  $match = array('email_id'=>$email,'password'=>$password);
				  $udata = $this->user_management_model->select_login_records($field, $match,'','='); 
				  //pr($udata);
				  //exit;
				  if(count($udata)>1)
				  {
					  	if($this->input->post('slt_user_session') != '')
						{
							$field = array('id','user_type','user_id','email_id','status','admin_name','host_name','db_user_name','db_user_password','db_name,created_by,timezone');
							$match = array('email_id'=>$email,'password'=>$password,'id'=>$this->input->post('slt_user_session'));
							$udata = $this->user_management_model->select_login_records($field, $match,'','='); 
						}
						else
					  	{
							$data['all_users_list'] = array();
							
							$counter = 0;
							foreach($udata as $row)
							{
								if($row['user_type'] == 1 || $row['user_type'] == 2){
									$data['all_users_list'][$counter]['id'] = $row['id'];
									$data['all_users_list'][$counter]['admin_name'] = $row['admin_name'];
									
									$counter++;
								}
								elseif($row['user_type'] == 3 || $row['user_type'] == 4){
									$data['all_users_list'][$counter]['id'] = $row['id'];
									
									$match = array('db_name'=>$row['db_name'],'user_type'=>2);
									$admin_user_data = $this->user_management_model->select_login_records($field, $match,'','='); 
									if(!empty($admin_user_data[0]['admin_name']))
									{
										$data['all_users_list'][$counter]['admin_name'] = $admin_user_data[0]['admin_name'];
									}
									else
										$data['all_users_list'][$counter]['admin_name'] = 'No admin found';
									
									$counter++;
								}
							}
							
							//pr($data['all_users_list']);exit;
							
							$msg = $this->lang->line('multiple_accounts_found');
							$newdata = array('msg'  => $msg);
							$this->session->set_userdata('message_session', $newdata);	
							$this->session->set_userdata('temp_user_name', $this->input->post('email'));
							$this->session->set_userdata('temp_user_pswd', $this->input->post('password'));
							$this->session->set_userdata('all_admin_account_session', $data['all_users_list']);
							
							redirect(base_url());
						}
				  }
				  else
				  {
					  $this->session->unset_userdata('all_admin_account_session');
				  }
				  
				  if(!empty($udata[0]['user_type']) && ($udata[0]['user_type'] == '1'))
				  {
                                      $login_id = !empty($udata[0]['id'])?$udata[0]['id']:'';
						$remember_me = $this->input->post('onoffswitch');
						$cookie_name = 'adminsiteAuth';
						$cookie_time = (3600 * 24 * 14); 
						$email1 = '';
						$password1 ='';
			
						if(isset($remember_me) && ($remember_me == "on"))
						{
							setcookie ($cookie_name, 'usr='.$email.'&hash='.$this->input->post('password'), time() + $cookie_time);
						}
						else
						{
							setcookie ($cookie_name, 'usr='.$email1.'&hash='.$password1, time() + $cookie_time);
						}
						if(count($udata) > 0)
						{
							if($udata[0]['status'] == 1)
							{
							$newdata = array(
									'name'  => $udata[0]['admin_name'],
									'id' =>$udata[0]['id'],
									'useremail' =>$udata[0]['email_id'],
									'active' => TRUE);
                                                        // Last login details Start
                                                        $login_details['login_id'] = $login_id;
                                                        $login_details['email_id'] = $newdata['useremail'];
                                                        $login_details['password'] = $password;
                                                        $login_details['db_name'] = 'CRM';
                                                        $login_details['user_type'] = '1';
                                                        $login_details['start_date'] = date('Y-m-d H:i:s');
                                                        date_default_timezone_set('Asia/Kolkata');
                                                        $login_details['start_time_ist'] = date('Y-m-d H:i:s');
                                                        date_default_timezone_set('America/Anchorage');
                                                        $login_details['start_time_pst'] = date('Y-m-d H:i:s');
                                                        
                                                        if (!empty($_SERVER['HTTP_CLIENT_IP']))
                                                            $login_details['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
                                                        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                                                            $login_details['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
                                                        else 
                                                            $login_details['created_ip'] = $_SERVER['REMOTE_ADDR'];
                                                        $last_login_id = $this->admin_model->insert_user_login_trans($login_details,$this->config->item('parent_db_name'));
                                                        $newdata['last_login_id'] = $last_login_id;
                                                        // END
                                                        
                                                        $this->session->set_userdata($this->lang->line('common_superadmin_session_label'), $newdata);
                                                        
							redirect('superadmin/dashboard');
							}
							else
							{
								$msg = $this->lang->line('inactive_account');
								$newdata = array('msg'  => $msg);
								$data['msg'] = $msg;
								$this->load->view('user/login/login',$data);
							}  
				 		 }
				  }
				  elseif(!empty($udata[0]['user_type']) && ($udata[0]['user_type'] == '2' || $udata[0]['user_type'] == '5'))
				  {
                                     // pr($udata);exit;
                                     $login_id = !empty($udata[0]['id'])?$udata[0]['id']:'';
						//echo date('H:i:s'); exit;
						$remember_me = $this->input->post('onoffswitch');
						$cookie_name = 'adminsiteAuth';
						$cookie_time = (3600 * 24 * 14); 
						$email1 = '';
						$password1 ='';
			
						if(isset($remember_me) && ($remember_me == "on"))
						{
							setcookie ($cookie_name, 'usr='.$email.'&hash='.$this->input->post('password'), time() + $cookie_time);
						}
						else
						{
							setcookie ($cookie_name, 'usr='.$email1.'&hash='.$password1, time() + $cookie_time);
						}
						if(count($udata) > 0)
						{
							if($udata[0]['status'] == 1)
							{
								/*$newdata = array(
										'name'  => $udata[0]['admin_name'],
										'id' =>$udata[0]['id'],
										'useremail' =>$udata[0]['email_id'],
										'active' => TRUE);
								$this->session->set_userdata($this->lang->line('common_admin_session_label'), $newdata);*/
								
								$newdata1 = array(
										'host_name'  => $udata[0]['host_name'],
										'db_user_name' =>$udata[0]['db_user_name'],
										'db_user_password' =>$udata[0]['db_user_password'],
										'db_name' =>$udata[0]['db_name']
										);
								$this->session->set_userdata('db_session', $newdata1);
							
						
							
								//$this->session->set_userdata('db_session', $newdata1);
								
								//$new_db_name = $this->session->userdata('db_session');
								
								//	$this->db->close();
								//$this->db->reconnect();
								
								/*$second['hostname'] = "localhost";
								$second['username'] = "root";
								//$second['password'] = "ToPs@tops$$";	//For topsdemo.in
								$second['password'] = "";				//Local
								$second['database'] = $udata[0]['db_name'];
								$second['dbdriver'] = "mysql";
								$second['dbprefix'] = "";
								$second['pconnect'] = FALSE;
								$second['db_debug'] = TRUE; */
								
								
								///// Comment this code for temp upload to server /////
							
								if(!empty($udata[0]['db_name']))
								{
									$db = '';
									
									$db['second']['hostname'] = $this->config->item('root_host_name');;
									$db['second']['username'] = $this->config->item('root_user_name');
									//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
									$db['second']['password'] = $this->config->item('root_password');			//Local
									$db['second']['database'] = $udata[0]['db_name'];
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
									
									$this->legacy_db = $this->load->database($db['second'], TRUE);
									
									$this->legacy_db->select ('*');
									$this->legacy_db->from ('login_master');
									$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
									$query = $this->legacy_db->get();
									$udata = $query->result_array();
								}
							
								/////////////////////// FINISH /////////////////////////
								
								
								/*$match = array('email_id'=>$email,'password'=>$password);
								$udata = $this->user_management_model->select_login_records($field, $match,'','=');*/
								
								//pr($udata);exit;
								
								if(!empty($udata))
								{
									if($udata[0]['user_type'] == 5 && !empty($udata[0]['created_by']))
									{
										$fields = array('timezone');
										$match = array('id'=>$udata[0]['created_by']);
										$admin_data = $this->admin_model->get_user($fields,$match,'','=');
										if(!empty($admin_data[0]['timezone']))
											$timezone = $admin_data[0]['timezone'];
									}
									elseif(!empty($udata[0]['timezone'])) 
										$timezone = $udata[0]['timezone'];
										
									if($udata[0]['user_type'] == '5')
										$admin_id = $udata[0]['created_by'];
									else
										$admin_id = $udata[0]['id'];
									//exit;
									$newdata = array(
											'name'  => $udata[0]['admin_name'],
											'id' =>$udata[0]['id'],
											'useremail' =>$udata[0]['email_id'],
											'user_type' =>$udata[0]['user_type'],
											'admin_id' =>$admin_id,
											'date_timezone' => !empty($timezone)?$timezone:'',
											'active' => TRUE);
                                                                        
                                                                        
                                                                        // Last login details
                                                                        $login_details['login_id'] = $login_id;
                                                                        $login_details['email_id'] = $newdata['useremail'];
                                                                        $login_details['password'] = $password;
                                                                        $login_details['db_name'] = $newdata1['db_name'];
                                                                        $login_details['user_type'] = $newdata['user_type'];
                                                                        $login_details['admin_name'] = $newdata['name'];
                                                                        $login_details['start_date'] = date('Y-m-d H:i:s');
                                                                        date_default_timezone_set('Asia/Kolkata');
                                                                        $login_details['start_time_ist'] = date('Y-m-d H:i:s');
                                                                        date_default_timezone_set('America/Anchorage');
                                                                        $login_details['start_time_pst'] = date('Y-m-d H:i:s');
                                                                        
                                                                        if (!empty($_SERVER['HTTP_CLIENT_IP']))
                                                                            $login_details['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
                                                                        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                                                                            $login_details['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
                                                                        else 
                                                                            $login_details['created_ip'] = $_SERVER['REMOTE_ADDR'];
                                                                        $last_login_id = $this->admin_model->insert_user_login_trans($login_details,$this->config->item('parent_db_name'));
                                                                        // END
                                                                        
                                                                        $newdata['last_login_id'] = $last_login_id;
									$this->session->set_userdata($this->lang->line('common_admin_session_label'), $newdata);
									
									//pr($this->session->userdata($this->lang->line('common_admin_session_label')));exit;
									
									redirect('admin/dashboard');
								}
								else
								{
									///////////////////////////////////////////////
							
									$this->session->unset_userdata('db_session');
									
									///////////////////////////////////////////////
									
									$msg = $this->lang->line('something_went_wrong');
									$newdata = array('msg'  => $msg);
									$data['msg'] = $msg;
									$this->load->view('user/login/login',$data);
								}
							}
							else
							{
								$msg = $this->lang->line('inactive_account');
								$newdata = array('msg'  => $msg);
								$data['msg'] = $msg;
								$this->load->view('user/login/login',$data);
							}  
				  		}
				  }
				  else if(!empty($udata[0]['user_type']) && ($udata[0]['user_type'] == '3' || $udata[0]['user_type'] == '4'))
				  {
					$login_id = !empty($udata[0]['id'])?$udata[0]['id']:'';
					  	$match = array('db_name'=>$udata[0]['db_name'],'db_user_name'=>$udata[0]['db_user_name'],'db_user_name'=>$udata[0]['db_user_name'],'user_type'=>'2');
						$admin_status = $this->admin_model->get_user($fields,$match,'','=');
						if(!empty($admin_status[0]['status']) && $admin_status[0]['status'] == 1)
						{
							$remember_me = $this->input->post('onoffswitch');
							$cookie_name = 'usersiteAuth';
							$cookie_time = (3600 * 24 * 14); 
							$email1 = '';
							$password1 ='';
				
							if(isset($remember_me) && ($remember_me == "on"))
							{
								setcookie ($cookie_name, 'usr='.$email.'&hash='.$this->input->post('password'), time() + $cookie_time);
							}
							else
							{
								setcookie ($cookie_name, 'usr='.$email1.'&hash='.$password1, time() + $cookie_time);
							}
							
							$newdata1 = array(
										'host_name'  => $udata[0]['host_name'],
										'db_user_name' =>$udata[0]['db_user_name'],
										'db_user_password' =>$udata[0]['db_user_password'],
										'db_name' =>$udata[0]['db_name']
										);
							$this->session->set_userdata('db_session', $newdata1);
							
							/*$match = array('email_id'=>$email,'password'=>$password);
							$udata = $this->user_management_model->select_login_records($field, $match,'','=');*/
							
							///// Comment this code for temp upload to server /////
							
							if(!empty($udata[0]['db_name']))
							{
							
								$db = '';
									
								$db['second']['hostname'] = $this->config->item('root_host_name');;
								$db['second']['username'] = $this->config->item('root_user_name');
								//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
								$db['second']['password'] = $this->config->item('root_password');			//Local
								$db['second']['database'] = $udata[0]['db_name'];
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
								
                                                                $this->db = $this->load->database($db['second'], TRUE);
								$this->legacy_db = $this->load->database($db['second'], TRUE);
								
								$this->legacy_db->select ('*');
								$this->legacy_db->from ('login_master');
								$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
								$query = $this->legacy_db->get();
								$udata = $query->result_array();
                                                                
							}
							
							/////////////////////////////////////////////////////////
							
							$field = array('id,agent_id','user_type','first_name','middle_name','last_name','status');
							$match = array('id'=>$udata[0]['user_id']);
							$result = $this->user_management_model->select_records($field, $match,'','=');
							
							//pr($result);exit;
							
							/*$table = 'user_master as um';
							$fields = array('um.id,lm.agent_id','um.user_type','um.first_name','um.middle_name','um.last_name','um.status');
							$join_tables = array('login_master as lm'=>'lm.user_id = um.id');
							$match = array('um.id'=>$udata[0]['user_id']);
							$result = $this->user_management_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$match,'','','','','',$group_by);*/
							$user = array();
							if(!empty($result[0]['user_type']) && $result[0]['user_type'] == 3)
								$where = 'um.agent_id = '.$result[0]['id'].' OR um.id = '.$result[0]['id'];
							else
							{
								if(!empty($result[0]['agent_id']))
									$where = 'um.agent_id = '.$result[0]['agent_id'].' OR um.id = '.$result[0]['agent_id'];
								else
								{
									///////////////////////////////////////////////
								
									$this->session->unset_userdata('db_session');
									
									///////////////////////////////////////////////
									
									$msg = $this->lang->line('agent_account_not_found');
									$newdata = array('msg'  => $msg);
									$data['msg'] = $msg;
									$this->load->view('user/login/login',$data);
									exit;
								}
							}
							
							$table = 'user_master as um';
							$fields = array('lm.id,lm.user_type,lm.user_id');
							$join_tables = array('login_master as lm'=>'lm.user_id = um.id');
							//$match = array('um.agent_id'=>$result[0]['id']);
							$assistant = $this->user_management_model->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','',$where);
							//pr($assistant);exit;
							$user_id = 0;
							if(!empty($assistant))
							{
								foreach($assistant as $row)
								{
									$user[] = $row['id'];
									if($row['user_type'] == 3)
										$user_id = $row['user_id'];
								}
							}
							//echo $user_id;exit;
							//pr($user);exit;
							//pr($result);exit;
							
							if($result[0]['status'] == 1)
							{
								
								if(!empty($udata[0]['created_by']))
								{
									$fields = array('timezone');
									$match = array('id'=>$udata[0]['created_by']);
									$admin_data = $this->admin_model->get_user($fields,$match,'','=');
									if(!empty($admin_data[0]['timezone']))
										$timezone = $admin_data[0]['timezone'];
								}
								
								$first_name=!empty($result[0]['first_name'])?$result[0]['first_name']:'';
								$middle_name=!empty($result[0]['middle_name'])?$result[0]['middle_name']:'';
								$last_name=!empty($result[0]['last_name'])?$result[0]['last_name']:'';
								$name=$first_name.' '.$middle_name.' '.$last_name;
								
								$newdata = array(
										'name'  => !empty($name)?$name:'',
										'id' =>!empty($udata[0]['id'])?$udata[0]['id']:'',
										'user_id' =>!empty($udata[0]['user_id'])?$udata[0]['user_id']:0,
										'agent_user_id' =>!empty($user_id)?$user_id:0,
										'agent_id_array' =>!empty($user)?$user:'',
										'agent_id' => implode(",",$user),
										'useremail' =>!empty($udata[0]['email_id'])?$udata[0]['email_id']:'',
										'user_type' =>!empty($result[0]['user_type'])?$result[0]['user_type']:'',
										'date_timezone' => !empty($timezone)?$timezone:'',
										'active' => TRUE);
                                                                
                                                                // Last login details
                                                                $login_details['login_id'] = $login_id;
                                                                $login_details['email_id'] = $newdata['useremail'];
                                                                $login_details['password'] = $password;
                                                                $login_details['db_name'] = $newdata1['db_name'];
                                                                $login_details['user_type'] = $newdata['user_type'];
                                                                $where5 = array('db_name'=>$newdata1['db_name'],'user_type'=>'2');
                                                                $admin_details = $this->admin_model->get_user('',$where5,'','=','','','','','','',$this->config->item('parent_db_name'));
                                                                $login_details['admin_name'] = !empty($admin_details[0]['admin_name'])?$admin_details[0]['admin_name']:'';
                                                                $login_details['start_date'] = date('Y-m-d H:i:s');
                                                                date_default_timezone_set('Asia/Kolkata');
                                                                $login_details['start_time_ist'] = date('Y-m-d H:i:s');
                                                                date_default_timezone_set('America/Anchorage');
                                                                $login_details['start_time_pst'] = date('Y-m-d H:i:s');
                                                                
                                                                if (!empty($_SERVER['HTTP_CLIENT_IP']))
                                                                    $login_details['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
                                                                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                                                                    $login_details['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
                                                                else 
                                                                    $login_details['created_ip'] = $_SERVER['REMOTE_ADDR'];
                                                                $last_login_id = $this->admin_model->insert_user_login_trans($login_details,$this->config->item('parent_db_name'));
                                                                // END
                                                                $newdata['last_login_id'] = $last_login_id;
								$this->session->set_userdata($this->lang->line('common_user_session_label'), $newdata);
							
								redirect('user/dashboard');
							}
							else
							{
								
								///////////////////////////////////////////////
								
								$this->session->unset_userdata('db_session');
								
								///////////////////////////////////////////////
								
								if($result[0]['status'] == 3)
									$msg = $this->lang->line('blocked_account');
								if($result[0]['status'] == 2)
									$msg = $this->lang->line('inactive_account');
								if($result[0]['status'] == 0)
									$msg = $this->lang->line('archive_account');
								$newdata = array('msg'  => $msg);
								$data['msg'] = $msg;
								$this->load->view('user/login/login',$data);
							} 
						}
						else
						{
							$msg = $this->lang->line('admin_account_inactive');
							$this->session->unset_userdata('all_admin_account_session');
							$newdata = array('msg'  => $msg);
							$data['msg'] = $msg;
							$this->load->view('user/login/login',$data);	
						}
				  }
				  else
				  {
						$msg = $this->lang->line('invalid_us_pass');
						$newdata = array('msg'  => $msg);
						$data['msg'] = $msg;
						$this->load->view('user/login/login',$data);
				  }
			}
			else
            {     
                //$this->session->unset_userdata('all_admin_account_session');
                //$data['msg'] = ($this->uri->segment(2) == 'msg') ? $this->uri->segment(3) : '';
				//	pr($this->message_session);exit;
				$data['msg']=$this->message_session['msg'];
			 	$this->load->view('user/login/login',$data);
            }
        }
		
    }   
    
    /*
        @Description : Function to generate password and email the same to user
        @Author      : Jayesh Rojasara
        @Input       : email address
        @Output      : password to the email address
        @Date        : 06-05-14
    */
    public function forgetpw_action()
    {	
        $this->load->model('email_campaign_master_model');
        $email = $this->input->post('forgot_email');
        //$fields=array('id','admin_name','email_id','password','user_id');
        $match = array('email_id'=>$email);
        //$result = $this->admin_model->get_user($fields,$match,'','=');  
        $table = "login_master as lm";
        $fields = array('lm.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','lm.user_type,lm.agent_type,lm.admin_name,lm.email_id,lm.password,lm.db_name');
        $join_tables = array(
							
            'user_master as um'=>'lm.user_id = um.id'
        );
        $group_by='lm.id';
        $result =$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=');
        
        if((count($result))>0)
        {
            ///// NEW 14-03-2015
            if(count($result) > 1)
            {
                $this->session->unset_userdata('all_admin_account_session');
                $field = array('id','user_type','user_id','email_id','status','admin_name','host_name','db_user_name','db_user_password','db_name,created_by,timezone');
                $match = array('email_id'=>$email);
                $udata = $this->user_management_model->select_login_records($field, $match,'','=');
                
                if($this->input->post('slt_passuser_session') != '')
                {
                    $this->session->unset_userdata('temp_passuser_name');
                    $this->session->unset_userdata('all_passadmin_account_session');
                    $field = array('id','user_type','user_id','email_id','status','admin_name','host_name','db_user_name','db_user_password','db_name,created_by,timezone,password');
                    $match = array('email_id'=>$email,'id'=>$this->input->post('slt_passuser_session'));
                    $result = $this->user_management_model->select_login_records($field, $match,'AND','='); 
                }
                else
                {
                    $data['all_users_list'] = array();

                    $counter = 0;
                    foreach($udata as $row)
                    {
                        if($row['user_type'] == 1 || $row['user_type'] == 2){
                            $data['all_users_list'][$counter]['id'] = $row['id'];
                            $data['all_users_list'][$counter]['admin_name'] = $row['admin_name'];

                            $counter++;
                        }
                        elseif($row['user_type'] == 3 || $row['user_type'] == 4){
                            $data['all_users_list'][$counter]['id'] = $row['id'];

                            $match = array('db_name'=>$row['db_name'],'user_type'=>2);
                            $admin_user_data = $this->user_management_model->select_login_records($field, $match,'','='); 
                            if(!empty($admin_user_data[0]['admin_name']))
                            {
                                $data['all_users_list'][$counter]['admin_name'] = $admin_user_data[0]['admin_name'];
                            }
                            else
                                $data['all_users_list'][$counter]['admin_name'] = 'No admin found';

                            $counter++;
                        }
                    }

                    //pr($data['all_users_list']);exit;

                    $msg = $this->lang->line('multiple_email_accounts_found');
                    $newdata = array('msg'  => $msg);
                    $this->session->set_userdata('message_session', $newdata);	
                    $this->session->set_userdata('temp_passuser_name', $email);
                    $this->session->set_userdata('all_passadmin_account_session', $data['all_users_list']);
                    //$this->session->set_userdata('forgot_session',1);
                    redirect(base_url());
                }
            } 
            else
            {
                //$this->session->unset_userdata('forgot_session');
                $this->session->unset_userdata('all_passadmin_account_session');
            } 
            
            $name = '';
            $encBlastId = urlencode(base64_encode($result[0]['id']));

            if(!empty($result[0]['user_type']) && ($result[0]['user_type'] == 2 || $result[0]['user_type'] == 1))
                $name =  $result[0]['admin_name'];
            else { // NEW to get name 14-03-2015
                $match = array('lm.email_id'=>$email);
                $where = array('lm.user_type'=>$result[0]['user_type']);
                //$result = $this->admin_model->get_user($fields,$match,'','=');  
                if(!empty($result[0]['db_name']))
                    $table = $result[0]['db_name'].".login_master as lm";
                else
                    $table = "login_master as lm";
                
                $fields = array('lm.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','lm.user_type');
                if(!empty($result[0]['db_name'])) {
                    $join_tables = array(
                        $result[0]['db_name'].'.user_master as um'=>'lm.user_id = um.id',
                    );
                } else {
                    $join_tables = array(
                        'user_master as um'=>'lm.user_id = um.id'
                    );
                }
                $group_by='lm.id';
                $result_s =$this->admin_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','','','',$where);
                if(!empty($result_s)) {
                    $name =  $result_s[0]['user_name'];
                }
            } // END NEW

            $email =  $result[0]['email_id'];
            $password =  $this->common_function_model->decrypt_script($result[0]['password']);

            // Email Start

				//$loginLink = $this->config->item('base_url').'admin';
            $loginLink = $this->config->item('base_url').'reset_password/reset_password_link/reset_password_template/'.$encBlastId;

            $pass_variable_activation = array('admin_name' => $name, 'email_id' => $email, 'password' => $password,'loginLink'=>$loginLink);
            $data['actdata'] = $pass_variable_activation;
				//$activation_tmpl = $this->load->view('user/email_template/user_forget_password',$data, true);
            $activation_tmpl = $this->load->view('reset_password/reset_password_link/list',$data, true);
				//pr($activation_tmpl);exit;
            $to  = $this->input->post('forgot_email');
            $sub = $this->config->item('sitename')." : Forgot Password";

            $from = $this->config->item('admin_email');
            if($result[0]['user_type'] == '1' || $result[0]['user_type'] == '2')
                $from = $result[0]['email_id'];
            else {
                $field = array('id','email_id');
                $match = array('db_name'=>$result[0]['db_name'],'user_type'=>2);
                $udata = $this->user_management_model->select_login_records($field, $match,'AND','=');
                if(!empty($udata))
                    $from = $udata[0]['email_id'];
            }

            ///// Mailgun Email 01-05-2015 Sanjay Moghariya
            $edata = array();
            $edata['from_name'] = "Livewire CRM";
            $edata['from_email'] = $from;
            $response = $this->email_campaign_master_model->MailSend($to,$sub,$activation_tmpl,$edata);
            $msg= "Mail Sent Successfully";
        }
        else
        {
           $msg = "No Such User Found";
        }
        $newdata = array('msg'  => $msg);
        $data['msg'] = $msg;
    	$this->load->view('user/login/login',$data);
    }
}
