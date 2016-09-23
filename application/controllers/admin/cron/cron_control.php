<?php 
/*
    @Description: cron controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 21-10-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cron_control extends CI_Controller
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
		$this->load->model('common_function_model');
		$this->load->model('dashboard_model');
		
		//sms model
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_texts_model');
		$this->load->model('module_master_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('joomla_assign_model');
		$this->load->model('ws/user_registration_model');
		$this->load->model('joomla_property_cron_model');
		$this->load->library('mpdf');
		
		$this->obj = $this->email_campaign_master_model;
		$this->obj1 = $this->sms_campaign_master_model;
		$this->load->model('mls_model');
		
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
    {}
	
	 /*
    @Description: Function for Get All contacts List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
    */
    public function cron_set()
    {
    	if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $created_ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $created_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];	
        else 
            $created_ip = $_SERVER['REMOTE_ADDR'];

    	$field_data_cron = array('cron_name'=>'cron_set','created_ip'=>$created_ip,'created_date'=>date('Y-m-d H:i:s'));
    	$insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

		$db_name = $this->config->item('parent_db_name');
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password,bombbomb_username,bombbomb_password');
		$match = array('user_type'=>'2','status'=>'1');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		//pr($all_admin);exit;
		$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
		//$all_admin = array();
		$all_admin1 = array_merge($all_admin,$merge_db);
		//echo $this->db->last_query();exit;	
		//echo $this->db->last_query();
		//pr($all_admin1);exit;
		/*---------Send sms------------*/
		if(!empty($all_admin1))
		{
			foreach($all_admin1 as $row)
			{
				if(!empty($row['timezone']))
					date_default_timezone_set($row['timezone']);
				else
					date_default_timezone_set($this->config->item('default_timezone'));
				
				$db_name1 = $row['db_name'];
				$bombbomb_username = $row['bombbomb_username'];
				$bombbomb_password = $row['bombbomb_password'];
				if(!empty($db_name1)){
				$match = array('interaction_type'=>'3','status'=>'1','send_automatically'=>'1');
				$sms_list = $this->interaction_model->select_records('',$match,'','=','','','','','','',$db_name1);
				if(!empty($sms_list))
				{
					for($a=0;$a<count($sms_list);$a++)				
					{
						$interaction_id = $sms_list[$a]['id'];
						$created_by=$sms_list[$a]['created_by'];
						//Get user details
						$remain_sms = 0;
						$field = array('id','created_by','user_type','remain_sms');
						$match = array('id'=>$created_by);
						$admin_data = $this->admin_model->get_user($field,$match,'','=','','','','','','',$db_name1);
						
						if(count($admin_data) > 0 && $admin_data[0]['user_type'] != 1 && $admin_data[0]['user_type'] != 2)
						{
							$user_id = $admin_data[0]['created_by'];
							$field = array('id','remain_sms');
							$match = array('id'=>$user_id);
							//$data['total_email'] = $this->obj->total_emails($user_id);
							$udata = $this->admin_model->get_user($field,$match,'','=','','','','','','',$db_name1);
							if(!empty($udata) && count($udata) > 0)
								$remain_sms = $udata[0]['remain_sms'];
						}
						else
						{
							$user_id = $admin_data[0]['id'];
							$remain_sms = $admin_data[0]['remain_sms'];
						}
						//echo $remain_sms;exit;
						$fields = '';
						$join_tables = '';
						/*$match = array('interaction_id'=>$interection_id);
						$sms_camp_list =$this->obj1->select_records('',$match,'','=','','','','','',$db_name1);*/
						$table = $db_name1.".sms_campaign_recepient_trans as scr";
						$fields = array('scm.id,scr.id as ID,scm.template_name,scr.sms_message,cpt.is_default,scr.sms_campaign_id,scr.contact_id,ipccp1.is_done,ipi.interaction_id as interaction_id1,cpt.phone_no,ipi.assign_to');
						$join_tables = array($db_name1.'.sms_campaign_master as scm'=>'scm.id = scr.sms_campaign_id',
											 $db_name1.'.contact_master as cm jointype direct'=>'cm.id = scr.contact_id',
											 $db_name1.'.interaction_plan_interaction_master as ipi'=>'ipi.id = scm.interaction_id',
											 $db_name1.'.interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
											 '(select * from '.$db_name1.'.interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
											 '(select * from '.$db_name1.'.contact_phone_trans order by is_default desc) as cpt'=>'cpt.contact_id = scr.contact_id'
											 
											 );
						$wherestring = "scm.sms_type = 'Intereaction_plan' AND ipi.id = ".$interaction_id." AND scr.is_send = '0' AND ipi.status='1' AND scr.send_sms_date <= '".date('Y-m-d')."' AND cpt.is_default = '1'";
						$group_by = 'scr.id';
						
						$sms_camp_list = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);
						//echo $this->db->last_query()."<br>";
						//pr($sms_camp_list);//exit;
						if(!empty($sms_camp_list))
						{$k=0;$l=0;$m=0;
							for($b=0;$b<count($sms_camp_list);$b++)				
							{
								if(empty($sms_camp_list[$b]['interaction_id1']) || (!empty($sms_camp_list[$b]['interaction_id1']) && $sms_camp_list[$b]['is_done'] == '1'))
								{
									//echo "hii";exit;
									$sms_camp_id =	$sms_camp_list[$b]['id'];
									$message = '';
									$sms_camp_list[$b]['contact_id'];
									$cdata['id'] = $sms_camp_list[$b]['ID'];
									$message = $sms_camp_list[$b]['sms_message'];
									
									$counter = strlen($message);
									if(!empty($counter))
										$total_message = $counter/160;
									$total_count = 0;
									if(!empty($total_message))
										$total_count = ceil($total_message);
									
									if($remain_sms == 0 || $remain_sms < $total_count)
									{
										$cdata['is_send'] = '0';
										if($remain_sms == 0 && $l == 0)
										{
											$edata['type'] = 'Twilio';
											$edata['description'] = $this->lang->line('common_sms_limit_over_msg');
											$edata['created_date'] = date('Y-m-d h:i:s');
											$edata['status'] = 1;
											$edata['created_by'] = $this->admin_session['id'];
											$this->dashboard_model->insert_record1($edata);
											$l++;
										}
										elseif($remain_sms > 0 && $m == 0)
										{
											$edata['type'] = 'Twilio';
											$edata['description'] = $this->lang->line('common_sms_limit_more_msg');
											$edata['created_date'] = date('Y-m-d h:i:s');
											$edata['status'] = 1;
											$edata['created_by'] = $this->admin_session['id'];
											$this->dashboard_model->insert_record1($edata);
											$m++;
										}
									}
									else
									{
                                        //$to = '+919033921029';
										$to = $sms_camp_list[$b]['phone_no'];
										
										//For twilio from account//
					
										if(!empty($sms_camp_list[0]['assign_to']))
											$send_from = $sms_camp_list[0]['assign_to'];
										else
											$send_from = 0;
											
										//////////////////////////
										
										$this->twilio->set_admin_id($send_from);
										
										$response = $this->twilio->sms($from, $to, $message);
										if(!empty($response->ErrorMessage) && $response->ErrorMessage=='Authenticate' && $k== 0)
										{
											$edata['type'] = 'Twilio';
											$edata['description'] = 'Authentication failed.';
											$edata['created_date'] =date('Y-m-d h:i:s');
											$edata['status'] = 1;
											$edata['created_by'] = $created_by;
											$this->dashboard_model->insert_record1($edata);
											$k++;
										}
                                        $cdata['phone_no'] = $to;
										$cdata['sent_date'] = date('Y-m-d H:i:s');
										$cdata['is_send'] = '1'; 

										$remain_sms = $remain_sms - $total_count;
										if(!empty($sms_camp_list[$b]['template_name']))
										{
											$contact_conversation['contact_id'] = $sms_camp_list[$b]['contact_id'];
											$contact_conversation['log_type'] = 8;
											$contact_conversation['campaign_id'] = $sms_camp_list[$b]['id'];
											$contact_conversation['sms_camp_template_id'] = $sms_camp_list[$b]['template_name'];
											$match = array('id'=>$sms_camp_list[$b]['template_name']);
											$template_data = $this->sms_texts_model->select_records('',$match,'','=','','','','','',$db_name1);
											if(count($template_data) > 0)
												$contact_conversation['sms_camp_template_name'] = $template_data[0]['template_name'];
											$contact_conversation['created_date'] = date('Y-m-d H:i:s');
											$contact_conversation['created_by'] =  $created_by;
											$contact_conversation['status'] = '1';
											$this->contact_conversations_trans_model->insert_record($contact_conversation,$db_name1);
										}
										
										$icdata['interaction_plan_interaction_id'] = $interaction_id;
										$icdata['contact_id'] = $sms_camp_list[$b]['contact_id'];
										$icdata['task_completed_date'] = date('Y-m-d H:i:s');
										$icdata['completed_by'] = $created_by;
										$icdata['is_done']='1';
										$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata,$db_name1);
										unset($icdata);								
										}
									
									$this->sms_campaign_recepient_trans_model->update_record($cdata,$db_name1);
								}
							}
							if(isset($remain_sms))
								$idata['remain_sms'] = $remain_sms;
							if(!empty($user_id))
							{
								$idata['id'] = $user_id;
								$udata = $this->admin_model->update_user($idata,$db_name1);
							}
						}
					}
				}
				
				if(isset($cdata))unset($cdata);
			
		/*---------Send email------------*/
				$match=array('interaction_type'=>'6','status'=>"1",'send_automatically'=>'1');
				$email_list = $this->interaction_model->select_records('',$match,'','=','','','','','','',$db_name1);
				//pr($email_list);//exit;
				if(!empty($email_list))
				{
					for($a=0;$a<count($email_list);$a++)				
					{
						$interaction_id = $email_list[$a]['id'];
						$created_by = $email_list[$a]['created_by'];
						$field = array('id','created_by','user_type','remain_emails');
						$match = array('id'=>$created_by);
						$admin_data = $this->admin_model->get_user($field,$match,'','=','','','','','','',$db_name1);
						if(count($admin_data) > 0 && $admin_data[0]['user_type'] != 1 && $admin_data[0]['user_type'] != 2)
						{
							$user_id = $admin_data[0]['created_by'];
							$field = array('id','remain_emails');
							$match = array('id'=>$user_id);
							$udata = $this->admin_model->get_user($field,$match,'','=','','','','','','',$db_name1);
							if(!empty($udata) && count($udata) > 0)
								$remain_emails = $udata[0]['remain_emails'];
						}
						else
						{
							$user_id = $admin_data[0]['id'];
							$remain_emails = $admin_data[0]['remain_emails'];
						}
						$table = $db_name1.'.email_campaign_recepient_trans as ecr';
						$fields = array('ecm.id,ecm.template_subject','ecr.id as ID,ecr.template_subject,ecr.email_message','ipccp1.is_done,ipi.interaction_id as interaction_id1,cet.is_default,ecm.email_signature,ecm.is_unsubscribe,ecm.template_name_id,ecr.contact_id,cet.email_address,ecr.send_email_date,lm.admin_name,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,lm.email_id,lm.user_type');
						$join_tables = array($db_name1.'.email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
											 $db_name1.'.contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
											 $db_name1.'.interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
											 $db_name1.'.interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
											 '(select * from '.$db_name1.'.interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
											 //'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',
											 '(select * from '.$db_name1.'.contact_emails_trans order by is_default desc) as cet'=>'cet.contact_id = ecr.contact_id',
											 $db_name1.'.login_master lm'=>'lm.id = ipi.assign_to',
								 			 $db_name1.'.user_master um'=>'um.id = lm.user_id',
											 											 
											 );
						$wherestring = "ecm.email_type = 'Intereaction_plan' AND ipi.id = ".$interaction_id." AND ecr.is_send = '0' AND ipi.status = '1' AND ecr.send_email_date <= '".date('Y-m-d')."' AND cet.is_default = '1' AND cm.is_subscribe='0'";
						$groupby = 'ecr.id';
						$email_camp_list = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$groupby,$wherestring);
						//echo $this->db->last_query(); echo "<br>";
						//pr($email_camp_list);//exit;
						//echo "<br>";
						if(!empty($email_camp_list))
						{
							$k = 0;
							for($b=0;$b<count($email_camp_list);$b++)				
							{
								if(empty($email_camp_list[$b]['interaction_id1']) || (!empty($email_camp_list[$b]['interaction_id1']) && $email_camp_list[$b]['is_done'] == '1'))
								{
									//echo "hi";exit;
									$email_camp_id=	$email_camp_list[$b]['id'];
									//$from = "nishit.modi@tops-int.com";						
									if(!empty($email_camp_list[$b]['id']))
									{
										$data['attachment'] = $this->obj->select_email_campaign_attachments($email_camp_list[$b]['id'],$db_name1);
										$message = '';
										if(!empty($email_camp_list[$b]['email_signature']))
										{
											$match = array('id'=>$email_camp_list[$b]['email_signature']);
											$email_signature = $this->email_signature_model->select_records('',$match,'','=','','','','','','','',$db_name1);
										}
										$message = !empty($email_camp_list[$b]['email_message'])?$email_camp_list[$b]['email_message']:'';
										if(!empty($email_signature))
											$message .= "<br>".$email_signature[0]['full_signature'];
										if($email_camp_list[$b]['is_unsubscribe'] == '1')
										$message .= '{(my_unsubscribe_link)}';
										
										//$headers = 'MIME-Version: 1.0'."\r\n";
										$from = '';
										$from_email = '';
										if(!empty($email_camp_list[$b]['user_type']) && ($email_camp_list[$b]['user_type'] == '2' || $email_camp_list[$b]['user_type'] == '5'))
											$from .= $email_camp_list[$b]['admin_name'];
										else
											$from .= trim($email_camp_list[$b]['user_name']);
										if(!empty($email_camp_list[$b]['email_id']))
											$from_email .= $email_camp_list[$b]['email_id'];
										//echo $email_camp_list[$b]['ID']."-".$from."---".$from_email."<br>";
                                        
										$data['from_email'] = $from_email;
										$data['from_name'] = $from;
										//pr($data['from_name']);exit;
										
										/*$headers .= "From: ".$from." <".$from_email.">\r\n";
										if(!empty($attachment))
											$headers .= $this->mailAttachmentHeader($attachment,$message);
										else
											$headers .= $this->mailAttachmentHeader('',$message);*/	
										$cdata['id'] = $email_camp_list[$b]['ID'];
										$emaildata = array(
															'Date'=>date('Y-m-d'),
															'Day'=>date('l'),
															'Month'=>date('F'),
															'Year'=>date('Y'),
															'Day Of Week'=>date( "w", time()),
															'Agent Name'=>'',
															'Contact First Name'=>$email_camp_list[$b]['first_name'],
															'Contact Spouse/Partner First Name'=>$email_camp_list[$b]['spousefirst_name'],
															'Contact Last Name'=>$email_camp_list[$b]['last_name'],
															'Contact Spouse/Partner Last Name'=>$email_camp_list[$b]['spouselast_name'],
															'Contact Company Name'=>$email_camp_list[$b]['company_name']
														  );

										$content = $message;
										$title = $email_camp_list[$b]['template_subject'];
										$output = $content;
										$pattern = "{(%s)}";
										$map = array();
										if($emaildata != '' && count($emaildata) > 0)
										{
											foreach($emaildata as $var => $value)
											{
												$map[sprintf($pattern, $var)] = $value;
											}
											$finaltitle = strtr($title, $map);
											$output = strtr($content, $map);
										}
										$subject = $email_camp_list[$b]['template_subject'];
										$message = $output;
										if($remain_emails == 0)
										{
											$cdata['is_send'] = '0';
											if($remain_emails == 0 && $k == 0)
											{
												$edata['type'] = 'Email';
												$edata['description'] = $this->lang->line('common_email_limit_over_msg');
												$edata['created_date'] = date('Y-m-d h:i:s');
												$edata['status'] = 1;
												$edata['created_by'] = $this->admin_session['id'];
												$this->dashboard_model->insert_record1($edata);
												$k++;
											}
										}
										else
										{
											$to = $email_camp_list[$b]['email_address'];
											$cdata['email_address'] = $to;
											if(!empty($email_camp_list[$b]['email_address']))
											{
												
												if(!empty($email_camp_list[$b]['is_unsubscribe']) && $email_camp_list[$b]['is_unsubscribe'] == '1'){
													$db_name2 = urlencode(base64_encode($db_name1));
													$email_id = urlencode(base64_encode($to));
													$link = base_url()."unsubscribe/unsubscribe_link/".$db_name2.'--'.$email_id;
													$message1 = '<br/><br/><a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
													$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
												}
												//mail($to,$subject,'',"-f".$message);
												$response = $this->obj->MailSend($to,$subject,$message,$data);
												$cdata['info'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
												unset($response);
												$cdata['sent_date'] = date('Y-m-d H:i:s');
												$cdata['is_send'] = '1';
												$remain_emails--;
												$contact_conversation['contact_id'] = $email_camp_list[$b]['contact_id'];
												$contact_conversation['log_type'] = 6;
												$contact_conversation['campaign_id'] = $email_camp_list[$b]['id'];
												$contact_conversation['email_camp_template_id'] = $email_camp_list[$b]['template_name_id'];
												
												if(!empty($email_camp_list[$b]['template_name_id']))
												{
													$match = array('id'=>$email_camp_list[$b]['template_name_id']);
													$template_data = $this->email_library_model->select_records('',$match,'','=','','','','','','',$db_name1);
													if(count($template_data) > 0)
														$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
												}
												
												$contact_conversation['created_date'] = date('Y-m-d H:i:s');
												$contact_conversation['created_by'] = $created_by;
												$contact_conversation['status'] = '1';
												$this->contact_conversations_trans_model->insert_record($contact_conversation,$db_name1);
											}
										}
										$icdata['interaction_plan_interaction_id'] = $interaction_id;
										$icdata['contact_id'] = $email_camp_list[$b]['contact_id'];
										$icdata['task_completed_date'] = date('Y-m-d H:i:s');
										$icdata['completed_by'] = $created_by;
										$icdata['is_done']='1';
										$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata,$db_name1);
										unset($icdata);
										//$single_id = $this->interaction_model->get_interaction_plan_interaction_trans_record($icdata);
										//echo $single_id;
										//if(!empty($single_id))
										//common_rescheduled_task($single_id);
										$this->obj->update_email_campaign_trans($cdata,$db_name1);
									}
																	
								}
							}
							if(isset($remain_emails))
								$idata['remain_emails'] = $remain_emails;
							if(!empty($user_id))
							{
								$idata['id'] = $user_id;
								$udata = $this->admin_model->update_user($idata,$db_name1);
							}
						}
					}
				}
				
				
				/*----------  Send BombBomb API through mail -----------*/
				
				if(!empty($bombbomb_username) && !empty($bombbomb_password))
				{
					$password = $this->common_function_model->decrypt_script($bombbomb_password);
					$data['username'] = $bombbomb_username;
					$data['password'] = $password;
					$match=array('interaction_type'=>'8','status'=>"1",'send_automatically'=>'1');
					$email_list = $this->interaction_model->select_records('',$match,'','=','','','','','','',$db_name1);
					//pr($email_list);exit;
					/*$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".urlencode($data['username'])."&pw=".urlencode($data['password']));
					if(!empty($IsValidLogin))
						$connection = json_decode($IsValidLogin);*/
					
					if(!empty($email_list))
					{
						//$IsValidLogin = @file_get_contents("https://app.bombbomb.com/app/api/api.php?method=IsValidLogin&email=".urlencode($data['username'])."&pw=".urlencode($data['password']));
                                                $url = "https://app.bombbomb.com/app/api/api.php?method=IsValidLogin";
                                                $IsValidLogin = $this->admin_model->bombbombapi_curl($url,$data);
						if(!empty($IsValidLogin))
                                                    $connection = json_decode($IsValidLogin);
						else
                                                    $connection->status = 'failure';

						if($connection->status == 'failure')
						{
							$edata['type'] = 'Bomb Bomb';
							$edata['description'] = 'Authentication failed.';
							$edata['created_date'] =date('Y-m-d h:i:s');
							$edata['status'] = 1;
							$edata['created_by'] = $created_by;
							$this->dashboard_model->insert_record1($edata);
						}
						if(!empty($connection->status) && $connection->status == 'success')
						{
							for($a=0;$a<count($email_list);$a++)
							{
								$interaction_id = $email_list[$a]['id'];
								$created_by = $email_list[$a]['assign_to'];
								$table = $db_name1.'.email_campaign_recepient_trans as ecr';
								$fields = array('ecm.id,ecm.template_subject','ecr.id as ID,ecr.template_subject,ecr.email_message','ipccp1.is_done,ipi.interaction_id as interaction_id1,cet.is_default,ecm.email_signature,ecm.is_unsubscribe,ecm.template_name_id,ecr.contact_id,cet.email_address,ecr.send_email_date,lm.admin_name,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,lm.email_id,lm.user_type');
								$join_tables = array($db_name1.'.email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
													 $db_name1.'.contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
													 $db_name1.'.interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
													 $db_name1.'.interaction_plan_interaction_master as ipim' => 'ipim.id = ipi.interaction_id',
													 '(select * from '.$db_name1.'.interaction_plan_contact_communication_plan order by is_done asc) as ipccp1' => 'ipccp1.interaction_plan_interaction_id = ipim.id AND ipccp1.contact_id=cm.id',
													 //'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id',
													 '(select * from '.$db_name1.'.contact_emails_trans order by is_default desc) as cet'=>'cet.contact_id = ecr.contact_id',
													 $db_name1.'.bomb_template_master as etm'=>'etm.id = ecm.template_name_id',
													 $db_name1.'.login_master lm'=>'lm.id = ipi.assign_to',
													 $db_name1.'.user_master um'=>'um.id = lm.user_id',
																								 
													 );
								$wherestring = "ecm.email_type = 'Intereaction_plan' AND ipi.id = ".$interaction_id." AND ecr.is_send = '0' AND ipi.status = '1' AND ecr.send_email_date <= '".date('Y-m-d')."' AND cet.is_default = '1' AND cm.is_subscribe='0'";
								$groupby = 'ecr.id';
								$email_camp_list = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$groupby,$wherestring);
								if(!empty($email_camp_list))
								{
									for($b=0;$b<count($email_camp_list);$b++)				
									{
										if(empty($email_camp_list[$b]['interaction_id1']) || (!empty($email_camp_list[$b]['interaction_id1']) && $email_camp_list[$b]['is_done'] == '1'))
										{
											$email_camp_id=	$email_camp_list[$b]['id'];
											if(!empty($email_camp_list[$b]['id']))
											{
												$message = '';
												if(!empty($email_camp_list[$b]['email_signature']))
												{
													$match = array('id'=>$email_camp_list[$b]['email_signature']);
													$email_signature = $this->email_signature_model->select_records('',$match,'','=','','','','','','','',$db_name1);
												}
												$message = '<div align="left">';
												$message .= !empty($email_camp_list[$b]['email_message'])?$email_camp_list[$b]['email_message']:'';
												//$data['video_id'] = !empty($email_camp_list[$b]['video_id'])?$email_camp_list[$b]['video_id']:'';
												if(!empty($email_signature))
													$message .= "<br>".$email_signature[0]['full_signature'];
												if($email_camp_list[$b]['is_unsubscribe'] == '1')
												$message .= '{(my_unsubscribe_link)}';
												
												if(!empty($email_camp_list[$b]['user_type']) && ($email_camp_list[$b]['user_type'] == '2' || $email_camp_list[$b]['user_type'] == '5'))
													$from = $email_camp_list[$b]['admin_name'];
												else
													$from = trim($email_camp_list[$b]['user_name']);
												if(!empty($email_camp_list[$b]['email_id']))
													$from_email = $email_camp_list[$b]['email_id'];
												
												$data['from_email'] = !empty($from_email)?$from_email:'';
												$data['from_name'] = ($from)?$from:'';
												
												$cdata['id'] = $email_camp_list[$b]['ID'];
												$emaildata = array(
																	'Date'=>date('Y-m-d'),
																	'Day'=>date('l'),
																	'Month'=>date('F'),
																	'Year'=>date('Y'),
																	'Day Of Week'=>date( "w", time()),
																	'Agent Name'=>'',
																	'Contact First Name'=>$email_camp_list[$b]['first_name'],
																	'Contact Spouse/Partner First Name'=>$email_camp_list[$b]['spousefirst_name'],
																	'Contact Last Name'=>$email_camp_list[$b]['last_name'],
																	'Contact Spouse/Partner Last Name'=>$email_camp_list[$b]['spouselast_name'],
																	'Contact Company Name'=>$email_camp_list[$b]['company_name']
																  );
		
												$content = $message;
												$title = $email_camp_list[$b]['template_subject'];
												$output = $content;
												$pattern = "{(%s)}";
												$map = array();
												if($emaildata != '' && count($emaildata) > 0)
												{
													foreach($emaildata as $var => $value)
													{
														$map[sprintf($pattern, $var)] = $value;
													}
													$finaltitle = strtr($title, $map);
													$output = strtr($content, $map);
												}
												$subject = $email_camp_list[$b]['template_subject'];
												$message = $output;
												$to = $email_camp_list[$b]['email_address'];
												$cdata['email_address'] = $to;
												if(!empty($email_camp_list[$b]['email_address']))
												{
													
													if(!empty($email_camp_list[$b]['is_unsubscribe']) && $email_camp_list[$b]['is_unsubscribe'] == '1'){
														$db_name2 = urlencode(base64_encode($db_name1));
														$email_id = urlencode(base64_encode($to));
														$link = base_url()."unsubscribe/unsubscribe_link/".$db_name2.'--'.$email_id;
														$message1 = '<br/> <br/> <a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
														$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
													}
													//echo $message;exit;
													//mail($to,$subject,'',"-f".$message);
													//pr($data);exit;
													$message .= '</div>';
													$response = $this->obj->BombBombMailSend($to,$subject,$message,$data);
													if(!empty($response))
														$response = json_decode($response);
												}
											  if(!empty($response->status) && $response->status == 'success')
											  {
												$cdata['sent_date'] = date('Y-m-d H:i:s');
												$cdata['is_send'] = '1';
												$cdata['info'] = $response->info->email_id;
												$contact_conversation['contact_id'] = $email_camp_list[$b]['contact_id'];
												$contact_conversation['log_type'] = 5;
												$contact_conversation['campaign_id'] = $email_camp_list[$b]['id'];
												$contact_conversation['email_camp_template_id'] = $email_camp_list[$b]['template_name_id'];
												
												if(!empty($email_camp_list[$b]['template_name_id']))
												{
													$match = array('id'=>$email_camp_list[$b]['template_name_id']);
													$template_data = $this->email_library_model->select_records('',$match,'','=','','','','','','',$db_name1);
													if(count($template_data) > 0)
														$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
												}
												
												$contact_conversation['created_date'] = date('Y-m-d H:i:s');
												$contact_conversation['created_by'] = $created_by;
												$contact_conversation['status'] = '1';
												$this->contact_conversations_trans_model->insert_record($contact_conversation,$db_name1);
												$icdata['interaction_plan_interaction_id'] = $interaction_id;
												$icdata['contact_id'] = $email_camp_list[$b]['contact_id'];
												$icdata['task_completed_date'] = date('Y-m-d H:i:s');
												$icdata['completed_by'] = $created_by;
												$icdata['is_done']='1';
												$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata,$db_name1);
												unset($icdata);
												common_rescheduled_task($single_id);
												$this->obj->update_email_campaign_trans($cdata,$db_name1);
												//exit;
											  }
											}
																			
										}
									}
								}
							}
						}	
					}
				}
				unset($bombbomb_username);
				unset($bombbomb_password);
				/*-----------        END       ----------*/
				
				
				
				/*-----------Delete Archieve User after 30 Days----------*/
				$user=$this->user_management_model->select_records('','','','','','','','','',$db_name1);
				//echo $db_name1;pr($user);
				for($i=0;$i<count($user);$i++)
				{
					$status=$user[$i]['status'];
					if($status=='0')
					{
						//pr($user[$i]);
						$archievedate = $user[$i]['archive_date'];
						$date_of=date("Y-m-d", strtotime($archievedate));
						$curdate = date('Y-m-d');
						$deletedate = date("Y-m-d", strtotime($date_of." +30 day"));
						//echo $curdate."-".$deletedate;
						if($curdate==$deletedate)
						{
							$id=$user[$i]['id'];
							$this->user_management_model->delete_record($id,$db_name1);
							echo $this->db->last_query();
						}
					}
				}
				
				}
			}
		}
		
		
		/*---------Drop Of--------------*/
		/*$or_clause='drop_type = 2 OR drop_type = 3';
		$interaction_plan=$this->interaction_model->select_records1('','','','','','','','','','','',$or_clause);
		
		if(!empty($interaction_plan))
		{
			for($i=0;$i<count($interaction_plan);$i++)
			{
				$interaction_id=$interaction_plan[$i]['id'];
				$drop_type=$interaction_plan[$i]['drop_type'];
				if($drop_type=='2')
				{
					$interaction_id=$interaction_plan[$i]['id'];
					$drop_after_day=$interaction_plan[$i]['drop_after_day'];
					$match=array('interaction_plan_interaction_id'=>$interaction_id);
					$drop_data=$this->interaction_model->select_records2('',$match,'','=');
					
					for($j=0;$j<count($drop_data);$j++)
					{
						$is_done=$drop_data[$j]['is_done'];
						if($is_done=='0')
						{
							$task_date=$drop_data[$j]['task_date'];
							$curdate = date('Y-m-d');
							$taskdate = date("Y-m-d", strtotime($task_date." +$drop_after_day day"));
							if($taskdate==$curdate)
							{
								$id=$drop_data[$j]['id'];
								$this->interaction_model->drop_interaction1($id);	
							}
						}
					}
				}
				if($drop_type=='3')
				{
					$interaction_id=$interaction_plan[$i]['id'];
					$drop_after_date=$interaction_plan[$i]['drop_after_date'];
					$match=array('interaction_plan_interaction_id'=>$interaction_id);
					$drop_data=$this->interaction_model->select_records2('',$match,'','=');
					for($j=0;$j<count($drop_data);$j++)
					{
						$is_done=$drop_data[$j]['is_done'];
						if($is_done=='0')
						{
							$task_date=$drop_data[$j]['task_date'];
							$curdate = date('Y-m-d');
							if($drop_after_date==$curdate)
							{
								$id=$drop_data[$j]['id'];
								$this->interaction_model->drop_interaction1($id);	
							}
						}
					}
				}
			}
		}*/

		if(!empty($insert_cron_id))
		{
			$db_name = $this->config->item('parent_db_name');
        	$table = $db_name.'.cron_test';
	 		$field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
			$insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
	}

	}


 /*
    @Description: Function for crone
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: -call as crone on time and date 
    @Date: 28-10-2014
    */
    public function cron_set_time()
    {
    	if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $created_ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $created_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];	
        else 
            $created_ip = $_SERVER['REMOTE_ADDR'];

    	$field_data_cron = array('cron_name'=>'cron_set_time','created_ip'=>$created_ip,'created_date'=>date('Y-m-d H:i:s'));
    	$insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

		$db_name = $this->config->item('parent_db_name');
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password,timezone');
		$match = array('user_type'=>'2','status'=>'1');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>'','timezone'=>''));
		//$all_admin = array();
		$all_admin1 = array_merge($all_admin,$merge_db);
		//pr($all_admin1);exit;
		
		if(!empty($all_admin1))
		{
			foreach($all_admin1 as $row)
			{
				if(!empty($row['timezone']))
					date_default_timezone_set($row['timezone']);
				else
					date_default_timezone_set($this->config->item('default_timezone'));
				
				$db_name1 = $row['db_name'];
				if(!empty($db_name1)){
					/*--------------Calendar-Reminder-Email before time----------------*/
					$event_list =$this->calendar_model->cron_fetch_event($db_name1);
					//$task_list =$this->task_model->cron_fetch_task();
					//pr($event_list);
					//pr($task_list);
					//exit;
					if(!empty($event_list))
					{
						for($i=0;$i<count($event_list);$i++)
						{
							
							$eventid=$event_list[$i]['id'];
							$event_title=$event_list[$i]['event_title'];
							$event_notes=$event_list[$i]['event_notes'];
							$start_date=date($this->config->item('common_date_format'),strtotime($event_list[$i]['start_date']));
							$start_time=date($this->config->item('common_time_format'),strtotime($event_list[$i]['start_time']));
							$end_date=date($this->config->item('common_date_format'),strtotime($event_list[$i]['end_date']));
							$end_time=date($this->config->item('common_time_format'),strtotime($event_list[$i]['end_time']));
							$subject='Reminder For Event: "'.$event_title.'"';
							$message='<b>'.'Event Name:-'.'</b>'.$event_title.':'.'<br>'.'<b>'.'Description:-'.'</b>'.$event_notes.'<br>'.'<b>'.' Event Start On:-'.'</b>'.$start_date.' '.$start_time.'<br>'.'<b>'.'Event Finished on:-'.'</b>'.$end_date.' '.$end_time; 
							$created_by=$event_list[$i]['created_by'];
							$where = array('lm.id'=>$created_by);
							$table = $db_name1.'.login_master lm';
							$fields = array('lm.email_id,lm.admin_name,lm.user_type,um.first_name,um.middle_name,um.last_name');
							$join_tables = array($db_name1.'.user_master as um'=>'um.id = lm.user_id');
							$group_by = 'lm.id';
							$user=$this->user_management_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
							//echo $this->db->last_query();exit;
							$from = '';
							if(!empty($user[0]['user_type']) && ($user[0]['user_type'] == '2' || $user[0]['user_type'] == '5'))
								$from .= $user[0]['admin_name'];
							else
								$from .= trim($user[0]['first_name']).' '.trim($user[0]['middle_name']).' '.trim($user[0]['last_name']);
							//pr($user);
							//$headers .= "From: ".$from." <".$from_email.">\r\n";
							//$user=$this->user_management_model->select_login_records('',$match1,'','=','','','','','',$db_name1);
							if(!empty($user[0]['email_id']))
							{
								$to = $user[0]['email_id'];
								$from_email = $user[0]['email_id'];
								
                                                                /// Mailgun ///
                                                                $edata = array();
                                                                $edata['from_name'] = $from;
                                                                $edata['from_email'] = $to;
                                                                $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                                                                /// End Mailgun ///
                                                                
								$cdata['id']=$eventid;
								$cdata['is_mail_sent']=1;				
								$this->calendar_model->update_record($cdata,$db_name1);
							}
						}
					}
					
					/*---------Task-Reminder-Email before time------------*/
					$task_list =$this->task_model->cron_fetch_task($db_name1);
					//echo $this->db->last_query();
					//pr($task_list);
					for($i=0;$i<count($task_list);$i++)
					{
						$taskid=$task_list[$i]['id'];
						$task_name=$task_list[$i]['task_name'];
						$task_desc=$task_list[$i]['desc'];
						$subject1='Reminder For Task:-'.$task_name;
						$created_by = $task_list[$i]['created_by'];
						//pr($subject1);exit;
						$message1='<b>'.'Description:-'.'</b>'.$task_name.': '.$task_desc; 
						$match=array('task_id'=>$taskid,'is_mail_sent'=>0);
						$task_data=$this->task_model->select_records1('',$match,'','=','','','','','',$db_name1);
						//pr($task_data);
						$where = array('lm.id'=>$created_by);
						$table = $db_name1.'.login_master lm';
						$fields = array('lm.email_id,lm.admin_name,lm.user_type,um.first_name,um.middle_name,um.last_name');
						$join_tables = array($db_name1.'.user_master as um'=>'um.id = lm.user_id');
						$group_by = 'lm.id';
						$user1=$this->user_management_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
						$from = '';
						$from_email='';
						if(!empty($user1[0]['user_type']) && ($user1[0]['user_type'] == '2' || $user1[0]['user_type'] == '5'))
							$from .= $user1[0]['admin_name'];
						else
							$from .= trim($user1[0]['first_name']).' '.trim($user1[0]['middle_name']).' '.trim($user1[0]['last_name']);
						if(!empty($user1[0]['email_id']))
							$from_email = $user1[0]['email_id'];
						for($j=0;$j<count($task_data);$j++)
						{
							$userid = $task_data[$j]['user_id'];
							$task_id=$task_data[$j]['id'];
							
							$match1=array('id'=>$userid);
							$user=$this->user_management_model->select_login_records('',$match1,'','=','','','','','',$db_name1);
							//pr($user);
							if(!empty($user[0]['email_id']))
							{
								$to1 = $user[0]['email_id'];
								
                                                                /// Mailgun ///
                                                                $edata = array();
                                                                $edata['from_name'] = $from;
                                                                $edata['from_email'] = $from_email;
                                                                $response = $this->email_campaign_master_model->MailSend($to1,$subject1,$message1,$edata);
                                                                /// End Mailgun ///
								
								$cdata['id']=$task_id;
								$cdata['is_mail_sent']=1;				
								$this->task_model->update_task1($cdata,$db_name1);
							}
						}
					}
					
					//exit;
					
				}
			}
		}

		if(!empty($insert_cron_id))
		{
			$db_name = $this->config->item('parent_db_name');
        	$table = $db_name.'.cron_test';
	 		$field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
			$insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
		}
	
	}
	
	
  /*
		@Description: Function for Mailattachment
		@Author: Sanjay Chabhadiya
		@Input: - Attachment List
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function mailAttachmentHeader($attachment,$message)
	{
		$mime_boundary = md5(time());
	
		$xMessage = "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"\r\n\r\n";
		
		//$xMessage .= "--".$mime_boundary."\r\n\r\n";
		
		//$xMessage .= "This is a multi-part message in MIME format.\r\n";
		$xMessage .= "--".$mime_boundary."\r\n";
		
		//$xMessage .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		
		//$xMessage .= "Content-Transfer-Encoding: 7bit\r\n";
		
		//$xMessage .= $message."\r\n\r\n";
		
		$xMessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$xMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$xMessage .= $message."\r\n\r\n";
		if(!empty($attachment))
		{
			foreach($attachment as $file)
			{
				$xMessage .= "--".$mime_boundary."\r\n";
				
				$xMessage .= "Content-Type: application/octet-stream; name=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$xMessage .= "Content-Transfer-Encoding: base64\r\n";
				
				$xMessage .= "Content-Disposition: attachment; filename=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$content = file_get_contents("uploads/attachment_file/".$file['attachment_name']);
				
				$xMessage.= chunk_split(base64_encode($content));
				
				$xMessage .= "\r\n\r\n";
			
			}
		}
		//pr($attachment);exit;
		$xMessage .= "--".$mime_boundary."--\r\n\r\n";
		
		return $xMessage;
	
	}
        
    /*
        @Description: Function for send email with pdf according to property cron setting (Weekly) (Value Watcher)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 28-11-2014
    */
    function get_valuation_cron_weekly()
    {
        $db_name = $this->config->item('parent_db_name');
        $fields1 = array('id,db_name,host_name,db_user_name,db_user_password,admin_name,email_id,phone,address,brokerage_pic');
        $match = array('user_type'=>'2','status'=>'1');
        $all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
        $merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
        $all_admin1 = array_merge($all_admin,$merge_db);
        //pr($all_admin1);exit;

        if(!empty($all_admin1))
        {
            foreach($all_admin1 as $admin_row)
            {
                $db_name1 = $admin_row['db_name'];
                $admin_name = $admin_row['admin_name'];
                $admin_phone = $admin_row['phone'];
                $admin_email = $admin_row['email_id'];
                $admin_address = $admin_row['address'];
                $brokerage_pic = $admin_row['brokerage_pic'];
                if(!empty($db_name1))
                {
                    //echo 'Hello';exit;
                    $table = $db_name1.".joomla_property_cron_master jpcm";
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Weekly','jpcm.data_from'=>1);
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    //pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['zip_code']);
                            //$url = "http://seattle.livewiresites.com/libraries/api/valution_report.php?fulladdr=".$addr."&radius=".$row['radius_limit'];
                            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                            $radius = !empty($row['radius_limit'])?$row['radius_limit']:'';
                            $url = $joomla_link."/libraries/api/valution_report.php?fulladdr=".$addr."&radius=".$radius;
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
                                        $pdata['property_data'][] = $property_response;
                                    }
                                }
                                $pdata['neighbor_address'] = urldecode($addr);
                                
                                //////////  Agent Data  ///////////// 
                                $table = $db_name1.".joomla_property_cron_trans as jpct";
                                $fields = array('jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','uct.user_id as assigned_agent_id,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    '(SELECT uctin.* FROM '.$db_name1.'.user_contact_trans uctin GROUP BY uctin.contact_id) AS uct'=>'uct.contact_id = jpct.contact_id',
                                    $db_name1.'.user_master as um' => 'um.id = uct.user_id',
                                    $db_name1.'.login_master as lm' => 'um.id = lm.user_id',
                                    '(SELECT uatin.* FROM '. $db_name1.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                    '(SELECT uptin.* FROM '. $db_name1.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                                );
                                $group_by='uct.contact_id';

                                $agent_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','uct.contact_id','desc',$group_by);
                                
                                $pdata['agent_data'] = $agent_data;
                                $pdata['admin_data'] = $admin_row;
                                //////////  Agent Data  /////////////
                                
                                ///////// PDF GENERATE CODE /////////
                                $pdf_html = $this->load->view('ws/valuation_report_pdf', $pdata, TRUE);
                                //echo $pdf_html;exit;
                                $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                //$mypdf->WriteHTML($stylesheet,1);
                                $base_url = $this->config->item('base_url');
                                $logo = $base_url."images/logo.png";
                                //$mypdf->SetWatermarkImage($logo);
                                //$mypdf->showWatermarkImage = true;
                                $img_path = $this->config->item('image_path');
                                $html = '';
                                //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;

                                $mypdf->WriteHTML($html);
                                
                                $filename = 'market_watch_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                if(!empty($admin_email))
                                    $from_mail = $admin_email;
                                else
                                    $from_mail = $this->config->item('admin_email');
                                //$replyto = 'demotops@gmail.com';
                                
                                /*$filename = 'value_watcher_weekly_'.date('mdYHis').'.pdf';
                                if(file_exists($this->config->item('base_path')."/uploads/valuation_pdf_file"))
                                    $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                */
                                $email_temp = array();$autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'9');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);
                                
                                if(!empty($autores_res) && count($autores_res) > 0)
                                {
                                    if(count($autores_res) > 1)
                                    {
                                        foreach($autores_res as $email_row)
                                        {
                                            if($email_row['template_name'] == 'Valuation Report')
                                            {
                                                $email_temp = $email_row;
                                            }
                                        }
                                    }
                                    else {
                                        $email_temp = $autores_res[0];
                                    }
                                } else {
                                    $email_temp = array();
                                }
                                
                                if(!empty($email_temp['template_subject']))
                                    $subject = $email_temp['template_subject'];
                                else
                                    $subject = 'Property Valuation Report - Livewire CRM';
                                
                                $table = $db_name1.".joomla_property_cron_trans as jpct";
                                $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','cm.first_name','cm.last_name','cet.email_address as email_id');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                //echo $db_name1;
                                //pr($contact_data);exit;
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';$agent_datalist= array();
                                        if(!empty($con['created_by']))
                                        {
                                            $table = $db_name1.".login_master as lm";   
                                            $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                            $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                            $wherestring = 'lm.id = '.$con['created_by'];
                                            $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                            if(!empty($agent_datalist))
                                            {
                                                if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                    $agent_name = $agent_datalist[0]['admin_name'];
                                                else
                                                    $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                            }
                                        }
                                        $data = array();$emaildata = array();
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

                                            if(!empty($emaildata) && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($email_temp['email_message'], $map);
                                                $data['temp_msg'] = $output;
                                            }
                                        } else {
                                            $data['temp_msg'] = '';
                                        }
                                        
                                        $filename = 'market_watch_weekly_'.date('mdYHis').'.pdf';
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        $data['city'] = $row['city'];
                                        $data['zip_code'] = $row['zip_code'];
                                        $data['radius'] = $row['radius_limit'];
                                        $data['admin_name'] = $admin_name;
                                        $data['admin_email'] = $admin_email;
                                        $data['admin_phone'] = $admin_phone;
                                        $data['admin_address'] = $admin_address;
                                        $data['brokerage_pic'] = $brokerage_pic;
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
                                        $mailto = $con['email_id'];
                                        
                                        // Save PDF file in folder and store data into table
                                        $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                        $mypdf->Output($this->config->item('base_path')."/uploads/attachment_file/".$filename,'F');

                                        $edata = array();
                                        ///// Mailgun Email 19-03-2015
                                        $edata['from_name'] = "Property Valuation Report";
                                        $edata['from_email'] = $admin_email;
                                        $edata['attachment'][] = array('attachment_name'=>$filename);

                                        if(!empty($message))
                                        {
                                            $response = $this->email_campaign_master_model->MailSend($mailto,$subject,$message,$edata);

                                            $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                            $pdf_data['contact_id'] = $con['id'];
                                            $pdf_data['last_report_file'] = $filename;
                                            $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                            $pdf_data['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                            $this->joomla_property_cron_model->update_task($pdf_data);

                                            $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];

                                            if(file_exists($pdfpath))
                                            { 
                                                @unlink($pdfpath);
                                            }
                                            pr($mailto);
                                            //echo "Email Sent Successfully.";echo "<br />";

                                            $temppath = $this->config->item('base_path').'/uploads/attachment_file/'.$filename;
                                            if(file_exists($temppath))
                                            { 
                                                @unlink($temppath);
                                            }
                                        }
                                    }
                                }
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
    
    /*
        @Description: Function for send email with pdf according to property cron setting (Monthly) (Value Watcher)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 28-11-2014
    */
    function get_valuation_cron_monthly()
    {
        $db_name = $this->config->item('parent_db_name');
        $fields1 = array('id,db_name,host_name,db_user_name,db_user_password,admin_name,email_id,phone,address,brokerage_pic');
        $match = array('user_type'=>'2','status'=>'1');
        $all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
        $merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
        $all_admin1 = array_merge($all_admin,$merge_db);
        //pr($all_admin1);exit;

        if(!empty($all_admin1))
        {
            foreach($all_admin1 as $admin_row)
            {
                $db_name1 = $admin_row['db_name'];
                $admin_name = $admin_row['admin_name'];
                $admin_phone = $admin_row['phone'];
                $admin_email = $admin_row['email_id'];
                $admin_address = $admin_row['address'];
                $brokerage_pic = $admin_row['brokerage_pic'];
                if(!empty($db_name1))
                {
                    $table = $db_name1.".joomla_property_cron_master jpcm";
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Monthly','jpcm.data_from'=>1);
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    //pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['zip_code']);
                            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                            $radius = !empty($row['radius_limit'])?$row['radius_limit']:'';
                            $url = $joomla_link."/libraries/api/valution_report.php?fulladdr=".$addr."&radius=".$radius;
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

                            if(!empty($response['data']))
                            {
                                $pdata = array();
                                foreach($response['data'] as $property_data)
                                {
                                    // Get property details from property id
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
                                        $pdata['property_data'][] = $property_response;
                                    }
                                }
                                $pdata['neighbor_address'] = urldecode($addr);
                                
                                //////////  Agent Data  ///////////// 
                                $table = $db_name1.".joomla_property_cron_trans as jpct";
                                $fields = array('jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','uct.user_id as assigned_agent_id,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    '(SELECT uctin.* FROM '.$db_name1.'.user_contact_trans uctin GROUP BY uctin.contact_id) AS uct'=>'uct.contact_id = jpct.contact_id',
                                    $db_name1.'.user_master as um' => 'um.id = uct.user_id',
                                    $db_name1.'.login_master as lm' => 'um.id = lm.user_id',
                                    '(SELECT uatin.* FROM '. $db_name1.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                    '(SELECT uptin.* FROM '. $db_name1.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                                );
                                $group_by='uct.contact_id';

                                $agent_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','uct.contact_id','desc',$group_by);
                                
                                $pdata['agent_data'] = $agent_data;
                                $pdata['admin_data'] = $admin_row;
                                //////////  Agent Data  /////////////
                                
                                ///////// PDF GENERATE CODE /////////
                                $pdf_html = $this->load->view('ws/valuation_report_pdf', $pdata, TRUE);
                                //pr($pdf_html);exit;
                                $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                //$mypdf->WriteHTML($stylesheet,1);
                                $base_url = $this->config->item('base_url');
                                $logo = $base_url."images/logo.png";
                                //$mypdf->SetWatermarkImage($logo);
                                //$mypdf->showWatermarkImage = true;
                                $img_path = $this->config->item('image_path');
                                $html = '';
                                //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;

                                $mypdf->WriteHTML($html);
                                
                                $filename = 'value_watcher_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                if(!empty($admin_email))
                                    $from_mail = $admin_email;
                                else
                                    $from_mail = $this->config->item('admin_email');
                                
                                $email_temp = array();$autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'9');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);
                                
                                if(!empty($autores_res) && count($autores_res) > 0)
                                {
                                    if(count($autores_res) > 1)
                                    {
                                        foreach($autores_res as $email_row)
                                        {
                                            if($email_row['template_name'] == 'Valuation Report')
                                            {
                                                $email_temp = $email_row;
                                            }
                                        }
                                    }
                                    else {
                                        $email_temp = $autores_res[0];
                                    }
                                } else {
                                    $email_temp = array();
                                }
                                
                                if(!empty($email_temp['template_subject']))
                                    $subject = $email_temp['template_subject'];
                                else
                                    $subject = 'Property Valuation Report - Livewire CRM';
                                
                                $table = $db_name1.".joomla_property_cron_trans as jpct";
                                $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','cm.first_name','cm.last_name','cet.email_address as email_id');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';$agent_datalist= array();
                                        if(!empty($con['created_by']))
                                        {
                                            $table = $db_name1.".login_master as lm";   
                                            $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                            $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                            $wherestring = 'lm.id = '.$con['created_by'];
                                            $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                            if(!empty($agent_datalist))
                                            {
                                                if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                    $agent_name = $agent_datalist[0]['admin_name'];
                                                else
                                                    $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                            }
                                        }
                                        $data = array();$emaildata = array();
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

                                            if(!empty($emaildata) && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($email_temp['email_message'], $map);
                                                $data['temp_msg'] = $output;
                                            }
                                        } else {
                                            $data['temp_msg'] = '';
                                        }
                                        
                                        $filename = 'market_watch_monthly_'.date('mdYHis').'.pdf';
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        $data['city'] = $row['city'];
                                        $data['zip_code'] = $row['zip_code'];
                                        $data['radius'] = $row['radius_limit'];
                                        $data['admin_name'] = $admin_name;
                                        $data['admin_email'] = $admin_email;
                                        $data['admin_phone'] = $admin_phone;
                                        $data['admin_address'] = $admin_address;
                                        $data['brokerage_pic'] = $brokerage_pic;
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
                                        $mailto = $con['email_id'];
                                        
                                        // Save PDF file in folder and store data into table
                                        $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                        $mypdf->Output($this->config->item('base_path')."/uploads/attachment_file/".$filename,'F');
                                        $edata = array();
                                        ///// Mailgun Email 19-03-2015
                                        $edata['from_name'] = "Property Valuation Report";
                                        $edata['from_email'] = $admin_email;
                                        $edata['attachment'][] = array('attachment_name'=>$filename);

                                        if(!empty($message))
                                        {
                                            //echo $message;
                                            $response = $this->email_campaign_master_model->MailSend($mailto,$subject,$message,$edata);
                                            
                                            $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                            $pdf_data['contact_id'] = $con['id'];
                                            $pdf_data['last_report_file'] = $filename;
                                            $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                            $pdf_data['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                            $this->joomla_property_cron_model->update_task($pdf_data);

                                            $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];

                                            if(file_exists($pdfpath))
                                            { 
                                                @unlink($pdfpath);
                                            }
                                            pr($mailto);

                                            $temppath = $this->config->item('base_path').'/uploads/attachment_file/'.$filename;
                                            if(file_exists($temppath))
                                            { 
                                                @unlink($temppath);
                                            }
                                        }
                                    }
                                }
                                
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
    
    /*
        @Description: Function for send email with pdf based on Valuation search criteria by user (Weekly)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 03-12-2014
    */
    function valuation_searched_cron_weekly()
    {
        //$this->load->unload_library('mpdf');
        //$this->load->library('mpdf60/mpdf');
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
                    $table = $db_name1.".joomla_rpl_property_valuation_searches as jpvs";
                    $fields = array('jpvs.*,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by','cm.first_name','cm.last_name','cet.email_address as email_id','cpt.phone_no');
                    $join_tables = array(
                        $db_name1.'.contact_master as cm'=>'cm.id = jpvs.lw_admin_id',
                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                        '(SELECT cptin.* FROM '.$db_name1.'.contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id'
                    );
                    //$group_by='cm.id';
                    $where = array('jpvs.report_timeline'=>'Weekly','jpvs.send_report'=>'Yes');
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','jpvs.id','desc','');
                    //echo $this->db->last_query();
                   // pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b7njhnhvkb_6r0dc&address=".$row['search_address']."&citystatezip=".$row['city'].", ".$row['state'];
                            $xml = simplexml_load_file($url);
                            /*$json = json_encode($xml);
                            $xml = json_decode($json,TRUE);
                            pr($xml);
                            exit;*/
                            //pr($xml);exit;
                            $code = $xml->message->code;
                            $propertydata = array();
                            
                            if($code =="0")
                            {
                                $propertydata['bathroom'] = $xml->response->results->result->bathrooms;
                                $propertydata['bedroom'] = $xml->response->results->result->bedrooms;
                                $propertydata['finished_sq_ft'] = $xml->response->results->result->finishedSqFt;
                                $propertydata['lotsize_sq_ft'] = $xml->response->results->result->lotSizeSqFt;
                                $propertydata['property_type'] = $xml->response->results->result->useCode;
                                $propertydata['normal_val'] = $xml->response->results->result->zestimate->amount;
                                $propertydata['lowvalue'] = $xml->response->results->result->zestimate->valuationRange->low;
                                $propertydata['highvalue'] = $xml->response->results->result->zestimate->valuationRange->high;
                                $propertydata['last_sold_date'] = $xml->response->results->result->lastSoldDate;
                                $propertydata['last_sold_price'] = $xml->response->results->result->lastSoldPrice;
                                $propertydata['year_built'] = $xml->response->results->result->yearBuilt;
                            }
                            $propertydata['address'] = $row['search_address'].", ".$row['city']." ".$row['state']." ".$row['zip_code'];
                            $propertydata['city'] = $row['city'];
                            $propertydata['state'] = $row['state'];
                            $propertydata['contact_name'] = $row['first_name'].' '.$row['last_name'];
                            $propertydata['contact_email'] = $row['email_id'];
                            $propertydata['contact_phone'] = $row['phone_no'];
                            $propertydata['company_name'] = $row['company_name'];
                            
                            /*$propertydata['avg_listing_link'] = $row['avg_listing_link'];
                            $propertydata['listing_volume_link'] = $row['listing_volume_link'];
                            $propertydata['sales_volume_link'] = $row['sales_volume_link'];
                            */
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['search_address'].", ".$row['city'].", ".$row['state'].", ".$row['zip_code']);
                            //$addr = '15024 5, 50th Ave SE Everett, Washington, 98208';
                            //$url = "http://seattle.livewiresites.com/libraries/api/valution_report.php?fulladdr=".$addr."&count=10";
                            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                            $url = $joomla_link."/libraries/api/valution_report.php?fulladdr=".$addr."&count=10";
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
                            //pr($response['data']);exit;
                            if(!empty($response['data']))
                            {
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
                                    //pr($property_response);exit;
                                    if(!empty($property_response['name']))
                                    {
                                        $propertydata['nproperty_name'][] = $property_response['name'];
                                        $propertydata['nproperty_description'][] = $property_response['description'];
                                        $propertydata['nprice'][] = $property_response['price'];
                                        $propertydata['nbuild_year'][] = $property_response['build_year'];
                                        $propertydata['nbedrooms'][] = $property_response['bedrooms'];
                                        $propertydata['nbathrooms'][] = $property_response['bathrooms'];
                                        $propertydata['nsqft'][] = $property_response['sqft'];
                                        $propertydata['nlot_size'][] = $property_response['lot_size'];
                                        $propertydata['nsold_date'][] = $property_response['sold_date'];
                                    }
                                    else
                                    {
                                        $propertydata['nproperty_name'][] = '';
                                        $propertydata['nproperty_description'][] = '';
                                        $propertydata['nprice'][] = '';
                                        $propertydata['nbuild_year'][] = '';
                                        $propertydata['nbedrooms'][] = '';
                                        $propertydata['nbathrooms'][] = '';
                                        $propertydata['nsqft'][] = '';
                                        $propertydata['nlot_size'][] = '';
                                        $propertydata['nsold_date'][] = '';
                                    }
                                }
                                //pr($propertydata);exit;
                                ///////// PDF GENERATE CODE /////////
                                $pdf_html = $this->load->view('ws/valuation_searched_report_pdf_new_2', $propertydata, TRUE);
                                //$pdf_html = $this->load->view('ws/original_html', $propertydata, TRUE);

                                //pr($pdf_html);exit;
                                //$mpdf = new mPDF(mode - default '',format - A4, for example, default '', font size - default 0, default font family, margin_left, margin right, margin top, margin bottom, margin header, margin footer, L - landscape, P - portrait);
                                

                                // DOM PDF                                	
                                	$filename = 'valuation_searched_'.date('m-d-Y');
                                	pdf_create($pdf_html, $filename, $stream=TRUE, $orientation='portrait');
								// END

                                
                                //$mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                //$mypdf->WriteHTML($stylesheet,1);
                                $base_url = $this->config->item('base_url');
                                $logo = $base_url."images/logo.png";
                                //$mypdf->SetWatermarkImage($logo);
                                //$mypdf->showWatermarkImage = true;
                                $img_path = $this->config->item('image_path');
                                $html = '';
                                //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;
									//pr($html); exit;
									//$mypdf->SetDisplayMode('fullpage');
                                //$mypdf->WriteHTML($html);
                               	//$filename = 'valuation_searched_'.date('m-d-Y').'.pdf';
                                	//$content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
	                                //$mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
	                                //$mypdf->Output();
                               	exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                $from_mail = $this->config->item('admin_email');
                                //$replyto = 'demotops@gmail.com';
                                
                                $email_temp = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'9');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);
                                
                                if(!empty($autores_res) && count($autores_res) > 0)
                                {
                                    if(count($autores_res) > 1)
                                    {
                                        foreach($autores_res as $erow)
                                        {
                                            if($erow['template_name'] == 'Valuation Report')
                                            {
                                                $email_temp = $erow;
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
                                
                                $table = $db_name1.".contact_master as cm";
                                $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by','cm.first_name','cm.last_name','cet.email_address as email_id');
                                $where = array('cm.id'=>$row['lw_admin_id']);
                                $join_tables = array(
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                //echo $this->db->last_query();
                                //pr($contact_data);exit;
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';$agent_datalist= array();
                                        if(!empty($con['created_by']))
                                        {
                                            $table = $db_name1.".login_master as lm";   
                                            $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                            $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                            $wherestring = 'lm.id = '.$con['created_by'];
                                            $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                            if(!empty($agent_datalist))
                                            {
                                                if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                    $agent_name = $agent_datalist[0]['admin_name'];
                                                else
                                                    $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                            }
                                        }
                                        $data = array();$emaildata = array();
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

                                            if(!empty($emaildata) && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($email_temp['email_message'], $map);
                                                $data['temp_msg'] = $output;
                                            }
                                        } else {
                                            $data['temp_msg'] = '';
                                        }
                                        
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['search_address'] = $row['search_address'];
                                        $data['city'] = $row['city'];
                                        $data['state'] = $row['state'];
                                        $data['zip_code'] = $row['zip_code'];
                                        
                                        $message = $this->load->view('ws/valuation_searched_report_email', $data, TRUE);  
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
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                        if($is_sent) {
                                            echo $mailto,"<br />";
                                            echo "Email Sent Successfully."; echo "<br />";
                                        } else {
                                            echo "Email not send.";echo "<br />";
                                        }
                                    }
                                }
                                //$mypdf->Output();
                                //echo "Email Sent Successfully."; echo "<br />";
                                ///////// END PDF /////////
                            }
                            else
                            {
                                echo "Result not found."; echo "<br />";
                            }
                        }
                    }
                }
            }
        }
    }
    
    /*
        @Description: Function for send email with pdf based on Valuation search criteria by user (Monthly)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 29-12-2014
    */
    function valuation_searched_cron_monthly()
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
                    $table = $db_name1.".joomla_rpl_property_valuation_searches as jpvs";
                    $fields = array('jpvs.*,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by','cm.first_name','cm.last_name','cet.email_address as email_id','cpt.phone_no');
                    $join_tables = array(
                        $db_name1.'.contact_master as cm'=>'cm.id = jpvs.lw_admin_id',
                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                        '(SELECT cptin.* FROM '.$db_name1.'.contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id'
                    );
                    //$group_by='cm.id';
                    $where = array('jpvs.report_timeline'=>'Weekly','jpvs.send_report'=>'Yes');
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','jpvs.id','desc','');
                    //echo $this->db->last_query();
                   // pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b7njhnhvkb_6r0dc&address=".$row['search_address']."&citystatezip=".$row['city'].", ".$row['state'];
                            $xml = simplexml_load_file($url);
                            /*$json = json_encode($xml);
                            $xml = json_decode($json,TRUE);
                            pr($xml);
                            exit;*/
                            //pr($xml);exit;
                            $code = $xml->message->code;
                            $propertydata = array();
                            
                            if($code =="0")
                            {
                                $propertydata['bathroom'] = $xml->response->results->result->bathrooms;
                                $propertydata['bedroom'] = $xml->response->results->result->bedrooms;
                                $propertydata['finished_sq_ft'] = $xml->response->results->result->finishedSqFt;
                                $propertydata['lotsize_sq_ft'] = $xml->response->results->result->lotSizeSqFt;
                                $propertydata['property_type'] = $xml->response->results->result->useCode;
                                $propertydata['normal_val'] = $xml->response->results->result->zestimate->amount;
                                $propertydata['lowvalue'] = $xml->response->results->result->zestimate->valuationRange->low;
                                $propertydata['highvalue'] = $xml->response->results->result->zestimate->valuationRange->high;
                                $propertydata['last_sold_date'] = $xml->response->results->result->lastSoldDate;
                                $propertydata['last_sold_price'] = $xml->response->results->result->lastSoldPrice;
                                $propertydata['year_built'] = $xml->response->results->result->yearBuilt;
                            }
                            $propertydata['address'] = $row['search_address'].", ".$row['city']." ".$row['state']." ".$row['zip_code'];
                            $propertydata['city'] = $row['city'];
                            $propertydata['state'] = $row['state'];
                            $propertydata['contact_name'] = $row['first_name'].' '.$row['last_name'];
                            $propertydata['contact_email'] = $row['email_id'];
                            $propertydata['contact_phone'] = $row['phone_no'];
                            $propertydata['company_name'] = $row['company_name'];
                            
                            /*$propertydata['avg_listing_link'] = $row['avg_listing_link'];
                            $propertydata['listing_volume_link'] = $row['listing_volume_link'];
                            $propertydata['sales_volume_link'] = $row['sales_volume_link'];
                            */
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['search_address'].", ".$row['city'].", ".$row['state'].", ".$row['zip_code']);
                            //$url = "http://seattle.livewiresites.com/libraries/api/valution_report.php?fulladdr=".$addr."&count=10";
                            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                            $url = $joomla_link."/libraries/api/valution_report.php?fulladdr=".$addr."&count=10";
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
                            //pr($response['data']);exit;
                            if(!empty($response['data']))
                            {
                                //$pdata = array();
                                /*$propertydata['nproperty_name'] = array();
                                $propertydata['nproperty_description'] = array();
                                $propertydata['nprice'] = array();
                                $propertydata['nbuild_year'] = array();
                                $propertydata['bedrooms'] = array();*/
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
                                    //pr($property_response);exit;
                                    if(!empty($property_response['name']))
                                    {
                                        $propertydata['nproperty_name'][] = $property_response['name'];
                                        $propertydata['nproperty_description'][] = $property_response['description'];
                                        $propertydata['nprice'][] = $property_response['price'];
                                        $propertydata['nbuild_year'][] = $property_response['build_year'];
                                        $propertydata['nbedrooms'][] = $property_response['bedrooms'];
                                        $propertydata['nbathrooms'][] = $property_response['bathrooms'];
                                        $propertydata['nsqft'][] = $property_response['sqft'];
                                        $propertydata['nlot_size'][] = $property_response['lot_size'];
                                        $propertydata['nsold_date'][] = $property_response['sold_date'];
                                    }
                                    else
                                    {
                                        $propertydata['nproperty_name'][] = '';
                                        $propertydata['nproperty_description'][] = '';
                                        $propertydata['nprice'][] = '';
                                        $propertydata['nbuild_year'][] = '';
                                        $propertydata['nbedrooms'][] = '';
                                        $propertydata['nbathrooms'][] = '';
                                        $propertydata['nsqft'][] = '';
                                        $propertydata['nlot_size'][] = '';
                                        $propertydata['nsold_date'][] = '';
                                    }
                                }
                                //pr($propertydata);exit;
                                ///////// PDF GENERATE CODE /////////
                                $pdf_html = $this->load->view('ws/valuation_searched_report_pdf_new_1', $propertydata, TRUE);
                                //$pdf_html = $this->load->view('ws/original_html', $propertydata, TRUE);

                                //pr($pdf_html);exit;
                                //$mpdf = new mPDF(mode - default '',format - A4, for example, default '', font size - default 0, default font family, margin_left, margin right, margin top, margin bottom, margin header, margin footer, L - landscape, P - portrait);
                                $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                
                                //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                //$mypdf->WriteHTML($stylesheet,1);
                                $base_url = $this->config->item('base_url');
                                $logo = $base_url."images/logo.png";
                                //$mypdf->SetWatermarkImage($logo);
                                //$mypdf->showWatermarkImage = true;
                                $img_path = $this->config->item('image_path');
                                $html = '';
                                //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;
								//pr($html); exit;
								//$mypdf->SetDisplayMode('fullpage');
                                $mypdf->WriteHTML($html);
                                $filename = 'valuation_searched_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //$mypdf->Output();
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                $from_mail = $this->config->item('admin_email');
                                //$replyto = 'demotops@gmail.com';
                                
                                $email_temp = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'9');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);
                                
                                if(!empty($autores_res) && count($autores_res) > 0)
                                {
                                    if(count($autores_res) > 1)
                                    {
                                        foreach($autores_res as $erow)
                                        {
                                            if($erow['template_name'] == 'Valuation Report')
                                            {
                                                $email_temp = $erow;
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
                                
                                $table = $db_name1.".contact_master as cm";
                                $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by','cm.first_name','cm.last_name','cet.email_address as email_id');
                                $where = array('cm.id'=>$row['lw_admin_id']);
                                $join_tables = array(
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                //echo $this->db->last_query();
                                //pr($contact_data);exit;
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';$agent_datalist= array();
                                        if(!empty($con['created_by']))
                                        {
                                            $table = $db_name1.".login_master as lm";   
                                            $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                            $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                            $wherestring = 'lm.id = '.$con['created_by'];
                                            $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                            if(!empty($agent_datalist))
                                            {
                                                if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                    $agent_name = $agent_datalist[0]['admin_name'];
                                                else
                                                    $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                            }
                                        }
                                        $data = array();$emaildata = array();
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

                                            if(!empty($emaildata) && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($email_temp['email_message'], $map);
                                                $data['temp_msg'] = $output;
                                            }
                                        } else {
                                            $data['temp_msg'] = '';
                                        }
                                        
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['search_address'] = $row['search_address'];
                                        $data['city'] = $row['city'];
                                        $data['state'] = $row['state'];
                                        $data['zip_code'] = $row['zip_code'];
                                        
                                        $message = $this->load->view('ws/valuation_searched_report_email', $data, TRUE);  
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
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                        if($is_sent) {
                                            echo $mailto,"<br />";
                                            echo "Email Sent Successfully."; echo "<br />";
                                        } else {
                                            echo "Email not send.";echo "<br />";
                                        }
                                    }
                                }
                                //$mypdf->Output();
                                //echo "Email Sent Successfully."; echo "<br />";
                                ///////// END PDF /////////
                            }
                            else
                            {
                                echo "Result not found."; echo "<br />";
                            }
                        }
                    }
                }
            }
        }
    }
	
	/*
        @Description: Function for assign all right to all admin
        @Author     : Niral Patel
        @Input      : 
        @Output     : right assign to all admin
        @Date       : 6-2-2015
    */
	function assign_all_right()
	{
		set_time_limit(0);
		$parent_db=$this->config->item('parent_db_name');
		$table=$parent_db.'.login_master as l';
		$fields = array('l.id,l.admin_name,l.email_id,l.db_name');
		$group_by='l.id';
		$where = "l.user_type = 2";
		$admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
		//pr($admin_list);exit;
		if(!empty($admin_list))
		{
			//$match = array('default_right' => 1);
			$fields = array('id','default_right');
			$module_data = $this->module_master_model->select_records($fields,'','','','','','','','','',$parent_db);
			//echo $this->db->last_query();
			$this->module_master_model->truncate_table('user_right_transaction',$parent_db);	
			//echo $this->db->last_query();
			//exit;
			//pr($module_data);exit;
			foreach($admin_list as $row)
			{
				//pr($row);
				//pr($module_data);
				//exit;
				foreach($module_data as $row1)
				{
					$data['user_id']= $row['id'];
					if(empty($row1['default_right']))
					{$data['assign_right'] = 1;}
					else
					{$data['assign_right'] = 0;}
					$data['module_id'] = $row1['id'];
					$data['created_date'] = date('y-m-d h:i:s');
					$data['modified_date'] = date('y-m-d h:i:s');
					$data['status'] = '1';
					$this->module_master_model->insert_record1($data,$parent_db);	
				}
			}
		}
		echo 'done';
	}
	/*
        @Description: Function for assign all right to all user
        @Author     : Niral Patel
        @Input      : 
        @Output     : right assign to all user
        @Date       : 6-2-2015
    */
	function assign_all_right_user()
	{
		//echo "hi";exit;
		set_time_limit(0);
		$parent_db=$this->config->item('parent_db_name');
		$table=$parent_db.'.login_master as l';
		$fields = array('l.id,l.admin_name,l.email_id,l.db_name');
		$group_by='l.id';
		$where = "l.user_type = 2";
		$admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
		
		if(!empty($admin_list))
		{
			//$match = array('default_right' => 1);
			$fields = array('id','default_right');
			$module_data = $this->module_master_model->select_records($fields,'','','','','','','','','',$parent_db);
			//pr($module_data);exit;
			foreach($admin_list as $row)
			{
				$db_name=$row['db_name'];
				$this->module_master_model->truncate_table('user_right_transaction',$db_name);	
				$table=$db_name.'.login_master as l';
				$fields = array('l.id','l.admin_name','l.email_id','l.email_id');
				$group_by='l.id';
				$where = "l.user_type = 3 or l.user_type = 4";
				$user_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
				
					foreach($user_list as $row2)
					{
						foreach($module_data as $row4)
				        {
							
							$udata['user_id']= $row2['id'];
							if(empty($row4['default_right']))
							{$udata['assign_right'] = 1;}
							else
							{$udata['assign_right'] = 0;}
							$udata['module_id'] = $row4['id'];
							$udata['created_date'] = date('y-m-d h:i:s');
							$udata['modified_date'] = date('y-m-d h:i:s');
							$udata['status'] = '1';
							$this->module_master_model->insert_child_record($udata,$db_name);	
					   }
					}
			}
		}
		echo 'done';
	}
	/*
        @Description: Function for assign all right to all admin
        @Author     : Niral Patel
        @Input      : 
        @Output     : right assign to all admin
        @Date       : 6-2-2015
    */
	function assign_bomb_right()
	{
		set_time_limit(0);
		$parent_db=$this->config->item('parent_db_name');
		$table=$parent_db.'.login_master as l';
		$fields = array('l.id,l.admin_name,l.email_id,l.db_name');
		$group_by='l.id';
		$where = "l.user_type = 2";
		$admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
		//pr($admin_list);exit;
		if(!empty($admin_list))
		{
			//$match = array('default_right' => 1);
			$fields = array('id','default_right');
			$match=array('status' =>'1');
			$where_in="module_id in (223,228)";
			$module_data = $this->module_master_model->select_records($fields,'','','=','','','','','','',$parent_db,$where_in);
			//echo $this->db->last_query();
			//pr($module_data);exit;
			foreach($admin_list as $row)
			{
				$deletecontactdata=array('223','224','225','226','227','228','229','230','231','232');
			    $this->module_master_model->delete_module_subchild_array($row['id'],$deletecontactdata,$parent_db);
				foreach($module_data as $row1)
				{
					$data['user_id']= $row['id'];
					if(empty($row1['default_right']))
					{$data['assign_right'] = 1;}
					else
					{$data['assign_right'] = 0;}
					$data['module_id'] = $row1['id'];
					$data['created_date'] = date('y-m-d h:i:s');
					$data['modified_date'] = date('y-m-d h:i:s');
					$data['status'] = '1';
					$this->module_master_model->insert_record1($data,$parent_db);	
				}
			}
		}
		echo 'done';
	}
	/*
            @Description: Function for assign sms responder all right to admin
            @Author     : Sanjay Chabhadiya
            @Input      : 
            @Output     : right assign to all admin
            @Date       : 2-07-2015
        */
        
        function assign_sms_auto_responder_right()
	{
            set_time_limit(0);
            $parent_db=$this->config->item('parent_db_name');
            $table=$parent_db.'.login_master as l';
            $fields = array('l.id,l.admin_name,l.email_id,l.db_name');
            $group_by='l.id';
            $where = "l.user_type = 2";
            $admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);
            if(!empty($admin_list))
            {
            	$module_id = array(233,234,235,236,237);
            	foreach ($admin_list as $row) {
            		$data['user_id'] = $row['id'];
            		foreach ($module_id as $row1) {
            			$data['module_id'] = $row1;
            			$data['assign_right'] = 1;
            			$data['created_date'] = date('y-m-d h:i:s');
            			$data['status'] = 1;
            			$this->module_master_model->insert_record1($data,$parent_db);
            			echo $row['db_name']."<br>";
            			echo $this->db->insert_id();
            		}
            		unset($data);
            	}
            }
            pr($admin_list);

            
        }
        
        
        
	function delete_assistant_rights()
	{
		//echo "hi"; exit;
		set_time_limit(0);
		$parent_db=$this->config->item('parent_db_name');
		$table=$parent_db.'.login_master as l';
		$fields = array('l.id,l.admin_name,l.email_id,l.db_name');
		$group_by='l.id';
		$where = "l.user_type = 2";
		$admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
		
		if(!empty($admin_list))
		{
			//$match = array('default_right' => 1);
			$fields = array('id','default_right');
			$module_data = $this->module_master_model->select_records($fields,'','','','','','','','','',$parent_db);
			foreach($admin_list as $row)
			{
				$db_name=$row['db_name'];
				/*$this->module_master_model->truncate_table('user_right_transaction',$db_name);	
				$table=$db_name.'.login_master as l';
				$fields = array('l.id','l.admin_name','l.email_id','l.email_id');
				$group_by='l.id';
				$where = "l.user_type = 3";
				$user_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
				foreach($user_list as $row2)
				{
					foreach($module_data as $row4)
					{
						
						$udata['user_id']= $row2['id'];
						if(empty($row4['default_right']))
						{$udata['assign_right'] = 1;}
						else
						{$udata['assign_right'] = 0;}
						$udata['module_id'] = $row4['id'];
						$udata['created_date'] = date('y-m-d h:i:s');
						$udata['modified_date'] = date('y-m-d h:i:s');
						$udata['status'] = '1';
						$this->module_master_model->insert_child_record($udata,$db_name);	
				   }
				}
				*/
				$table=$db_name.'.login_master as l';
				$fields = array('l.id','l.admin_name','l.email_id','l.email_id');
				$group_by='l.id';
				$where = "l.user_type = 4";
				$assistant_list = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);	
				if(!empty($assistant_list))
				{
					foreach($assistant_list as $row1)
					{
						$icdata['user_id'] = $row1['id'];
						$this->module_master_model->delete_assistant_rights($db_name,$icdata);
				   }
				}
			}
		}
		echo "done";
	}
	
	function cat_subcat_query()
	{
		//echo 1;exit;
		$db_name = $this->config->item('parent_db_name');
		
		
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
		$match = array('user_type'=>'2','status'=>'1');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		$merge_db = array();
		$all_admin1 = array_merge($all_admin,$merge_db);
		
		if(!empty($all_admin1))
		{
			foreach($all_admin1 as $row)
			{
				$db_name1 = $row['db_name'];		
		
				$row = 'delete from '.$db_name1.'.marketing_master_lib__category_master where `superadmin_cat_id` != 0;';
				
				$query = trim($row);
				$query = $this->db->query($query);
		
				$row1 = 'INSERT INTO '.$db_name1.'.marketing_master_lib__category_master (`category`,`superadmin_cat_id`,`created_date`,`created_by`,`status`) SELECT category,id,"'.date("Y-m-d H:i:s").'" as created_date,1 as created_by,1 as status from '.$db_name.'.marketing_master_lib__category_master';
		
				$query1 = trim($row1);
				$query1 = $this->db->query($query1);
				
				$row2 = 'delete from '.$db_name1.'.email_template_master where `superadmin_template_id` != 0;';
				
				$query2 = trim($row2);
				$query2 = $this->db->query($query2);
				
				$row3 = 'delete from '.$db_name1.'.letter_template_master where `superadmin_template_id` != 0;';
				
				$query3 = trim($row3);
				$query3 = $this->db->query($query3);
			}
		}
			
	}
	
	function remove_session_user()
	{
		$this->session->unset_userdata($this->lang->line('common_user_session_label'));
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('useremail');
		$this->session->unset_userdata('active');
		$this->session->unset_userdata($this->lang->line('common_user_session_label'));
		$this->session->unset_userdata('db_session');
		$this->load->helper('cookie');
		$cookie=  $this->config->item('sess_cookie_name');
		delete_cookie($cookie);
	}
	
	function remove_session_admin()
	{
		$this->session->unset_userdata($this->lang->line('common_admin_session_label'));
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('useremail');
		$this->session->unset_userdata('active');
		$this->session->unset_userdata($this->lang->line('common_admin_session_label'));
		$this->session->unset_userdata('db_session');
		$this->load->helper('cookie');
		$cookie=  $this->config->item('sess_cookie_name');
		delete_cookie($cookie);
	}
        
        /*
            @Description: Function for assign agent / lendar to new leads using round robin and interaction plan 
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Assign agent / lendar and interaction plan
            @Date       : 17-02-2015
        */
        function round_robin_cron()
        {
        	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	            $created_ip = $_SERVER['HTTP_CLIENT_IP'];
	        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	            $created_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];	
	        else 
	            $created_ip = $_SERVER['REMOTE_ADDR'];

	    	$field_data_cron = array('cron_name'=>'round_robin_cron','created_ip'=>$created_ip,'created_date'=>date('Y-m-d H:i:s'));
    		$insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

            set_time_limit(0);
            $parent_db=$this->config->item('parent_db_name');
            $table=$parent_db.'.login_master as l';
            $fields = array('l.id,l.admin_name,l.email_id,l.address,l.phone,l.db_name,l.timezone,l.brokerage_pic');
            $group_by='l.id';
            $where = "l.user_type = 2";
            $admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);
            
            //$merge_db = array('0'=>array('id'=>'','db_name'=>$parent_db,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
            //$admin_list = array_merge($admin_list,$merge_db);
            $admin_emailid = '';
            if(!empty($admin_list))
            {
                foreach($admin_list as $dbrow)
                {
                    $admin_emailid = $dbrow['email_id'];
                    $admin_name = $dbrow['admin_name'];
                    $db_name = $dbrow['db_name'];
                    $brokerage_pic = $dbrow['brokerage_pic'];
                    $admin_id = $dbrow['id'];
                    echo '<br /><br />',$db_name,"<br /><br />";
                    
                    if(!empty($dbrow['timezone']))
                        $timezone = $dbrow['timezone'];
                    else
                        $timezone = $this->config->item('default_timezone');
                    
                    date_default_timezone_set($timezone);
                    $dt = date('Y-m-d H:i:s');
                    
                    $table = $db_name.".contact_master as cm";
                    $match = array('cm.status'=> '1','cm.created_type'=>'6');
                    $where = 'cm.created_date >= date_sub("'.$dt.'", interval 5 MINUTE) AND cm.created_date < "'.$dt.'"';
                    $fields = array('cm.id','cm.joomla_domain_name','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name','cm.joomla_contact_type','cpt.phone_no','cet.email_address','lm.admin_name','CONCAT_WS(" ",um.first_name,um.last_name) as user_name','cm.joomla_timeframe','cm.joomla_address,cm.price_range_from,cm.price_range_to,cm.min_area,cm.max_area,cm.joomla_timeframe,cm.is_valuation_contact,cm.house_style,cm.area_of_interest,cm.no_of_bedrooms,cm.no_of_bathrooms');
                    $join_tables = array(
                        '(SELECT cptin.* FROM '.$db_name.'.contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
                        '(SELECT cetin.* FROM '.$db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                        $db_name.'.login_master as lm' => 'lm.id = cm.created_by',
                        $db_name.'.user_master as um' => 'um.id = lm.user_id'
                    );
                    $group_by='cm.id';
                    $new_contact_list = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,$where);
                    $i = 0;
                    if(!empty($new_contact_list))
                    {
                        foreach($new_contact_list as $row)
                        {
                            //$domain_id = $this->get_domain_id($db_name,$row['joomla_domain_name']);
                            $domain_id = !empty($row['domain_id'])?$row['domain_id']:0;
                            $agent_id = 0;
                            $user_list = array();$rr_last_weightage = array();$rr_user_list = array();$new_rr_data = array();
                            $data = array();$rr_data = array();

                            ////// Round Robin Logic (Agent) 18-11-2014  ///////////
                            $err_flag = 0;
                            $price_flag = 0; $area_flag = 0;
                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value,lm.agent_type');
                            $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id',
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id');

                            $min_price = !empty($row['price_range_from'])?$row['price_range_from']:0;    
                            $max_price = !empty($row['price_range_to'])?$row['price_range_to']:0;

                            $min_area = !empty($row['min_area'])?$row['min_area']:0;    
                            $max_area = !empty($row['max_area'])?$row['max_area']:0;

                            if(!empty($row['price_range_from']) && !empty($row['price_range_to']))
                            {
                                $price_flag = 1;
                            }

                            if($price_flag == 1)
                            {
                                if($row['price_range_from'] > $row['price_range_to'])
                                {
                                    $err_flag = 1;
                                }
                            }

                            if(!empty($row['min_area']) && !empty($row['max_area']))
                            {
                                $area_flag = 1;
                            }

                            if($row['joomla_contact_type'] == 'Seller')
                                $agent_type = 'Inside Sales Agent';
                            if($row['joomla_contact_type'] == 'Buyer')
                                $agent_type = "Buyer's Agent";


                            /////// Check for price, area and weightage assign or not
                            $weightage_assign_flag = 0;
                            
                            // Check for Weightage assign or not to agent
                            $wuser_list = array();
                            $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                            $wuser_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                            $uwhere_in = array();
                            if(!empty($wuser_list))
                            {
                                $all_user_list = '';
                                foreach($wuser_list as $aurow) // Get agent id only
                                {
                                    $all_user_list .= $aurow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                $uwhere_in = array('um.id'=>$all_user_list);
                                
                                ///// Check for domain 09-06-2015 Sanjay Moghariya /////
                                $assigned_agent_domain_list = array();
                                if(!empty($domain_id))
                                {
                                    $dtable = $db_name.'.user_domain_trans';
                                    $dfields = array('user_id');
                                    $dmatch = array('domain_id'=>$domain_id);
                                    $dwhere_in = array('user_id'=>$all_user_list);
                                    $assigned_agent_domain_list = $this->contact_masters_model->getmultiple_tables_records($dtable,$dfields,'','','',$dmatch,'=','','','','','','','',$dwhere_in);
                                    if(!empty($assigned_agent_domain_list))
                                    {
                                        $dall_user_list = '';
                                        foreach($assigned_agent_domain_list as $daurow) // Get agent id only
                                        {
                                            $dall_user_list .= $daurow['user_id'].',';
                                        }
                                        $dall_user_list = trim($dall_user_list,',');
                                        $uwhere_in = array('um.id'=>$dall_user_list);
                                    }
                                }
                                ///// End Check for domain 09-06-2015 Sanjay Moghariya /////

                                // Check for price agent which is within price range
                                if(!empty($price_flag))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'',$min_price,$max_price,'1');
                                }
                                
                                // Check this if price range found with area criteria
                                if(!empty($user_list) && !empty($area_flag))
                                {
                                    $old_user_list = $user_list;
                                    $all_user_list = '';
                                    foreach($user_list as $aurow) // Get agent id only
                                    {
                                        $all_user_list .= $aurow['id'].',';
                                    }
                                    $all_user_list = trim($all_user_list,',');
                                    $where_in = array('um.id'=>$all_user_list);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in,'','','','','','',$min_area,$max_area,'1');
                                    if(empty($user_list))
                                        $user_list = $old_user_list;
                                }
                                // Check this if price range not found then check area criteria only
                                else if(empty($user_list) && !empty($area_flag))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'','','','','','',$min_area,$max_area,'1');
                                }
                                
                                if(empty($user_list) && !empty($assigned_agent_domain_list)) // If found agent with assigned domain and with price/area criteria
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'','','','');
                                } else { // If not found agent with assigned domain then check with excluding domain/price/area criteria
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                                }
                                
                                if(!empty($user_list))
                                {
                                    $table = $db_name.".user_rr_weightage_trans";
                                    $fields = array('user_id,user_weightage,round,round_value');
                                    $match = array('agent_type'=>$agent_type);
                                    $rr_last_weightage = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','id','desc');
                                    
                                    $assign_agent = 0;
                                    if(!empty($rr_last_weightage))
                                    {
                                        $all_user_list = '';
                                        $aulist = array();
                                        foreach($user_list as $urow) // Insert all agent data with initial 0 value
                                        {
                                            $rr_uid = array();
                                            $table = $db_name.".user_rr_weightage_trans";
                                            $fields = array('user_id');
                                            $match = array('user_id'=> $urow['id']);
                                            $rr_uid = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','id','desc');
                                            if(empty($rr_uid))
                                            {
                                                $new_rr_data['user_id'] = $urow['id'];
                                                $new_rr_data['user_weightage'] = $urow['user_weightage'];
                                                $new_rr_data['agent_type'] = $agent_type;
                                                $new_rr_data['round'] = 0;
                                                $new_rr_data['round_value'] = 0;
                                                $new_rr_uid = $this->user_management_model->insert_user_rr_weightage_trans_record($new_rr_data,$db_name);
                                            }   

                                            $all_user_list .= $urow['id'].',';
                                            $aulist[] = $urow;
                                        }
                                        $all_user_list = trim($all_user_list,',');
                                        $this->session->unset_userdata('assigned_contact_session');
                                        //Call function for assign contact to admin
                                        $rr_user_list = $this->new_rr_user_id($all_user_list,$user_list,$db_name);

                                        if(!empty($rr_user_list))
                                        {
                                            $data['contact_id'] = $row['id'];
                                            $data['user_id'] = $rr_user_list[0]['id'];
                                            $data['agent_type'] = $agent_type;
                                            $data['created_date']  = date('Y-m-d H:i:s');
                                            $data['status']  = '1';
                                            $uct_id = $this->user_management_model->insert_user_contact_trans_record($data,$db_name);
                                            
                                            $rr_data['assigned_contact_id'] = $uct_id;
                                            $rr_data['user_id'] = $rr_user_list[0]['id'];
                                            $rr_data['user_weightage'] = $rr_user_list[0]['user_weightage'];
                                            $rr_data['agent_type'] = $agent_type;
                                            $rr_data['round'] = $rr_user_list[0]['round'];
                                            $rr_data['round_value'] = $rr_user_list[0]['round_value'];

                                            $rr_weightage_id = $this->user_management_model->update_user_rr_weightage_trans_record($rr_data,$db_name);
                                            $agent_id = $rr_user_list[0]['id'];
                                            $assign_agent = 1;
                                        }

                                    }
                                    else // First Time
                                    {
                                        $data['contact_id'] = $row['id'];
                                        $data['user_id'] = $user_list[0]['id'];
                                        $data['agent_type'] = $agent_type;
                                        $data['created_date']  = date('Y-m-d H:i:s');
                                        $data['status']  = '1';
                                        $uct_id = $this->user_management_model->insert_user_contact_trans_record($data,$db_name);
                                        
                                        $rr_data['assigned_contact_id'] = $uct_id;
                                        $rr_data['user_id'] = $user_list[0]['id'];
                                        $rr_data['user_weightage'] = $user_list[0]['user_weightage'];
                                        $rr_data['agent_type'] = $agent_type;
                                        $rr_data['round'] = 1;
                                        $rr_data['round_value'] = 1;
                                        $rr_weightage_id = $this->user_management_model->insert_user_rr_weightage_trans_record($rr_data,$db_name);
                                        
                                        $agent_id = $user_list[0]['id'];
                                        $assign_agent = 1;
                                    }
                                    echo "Contact Id: ".$data['contact_id']. " Agent: ".$data['user_id']."<br />";
                                } else {
                                    echo "Agent not assigned..!! <br />";
                                }
                            } else {
                                echo "Agent not assigned..!! <br />";
                            }
                            
                            ////// Email to Agent (If assign agent to contact) 31-03-2015/////
                            $table = $db_name.".user_master as um";
                            $group_by = "cm.id";
                            $fields = array('um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                            $join_tables = array(
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                                '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id',
                            );
                            $match = array('um.id'=>$agent_id);
                            $agent_data =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=');
                            
                            /// If agent is assigned
                            if(!empty($assign_agent))
                            {
                                ////////// Send email to leads 31-03-2015 /////////
                                
                                /// Auto responder
                                if($row['joomla_contact_type'] == 'Seller')
                                    $match = array('email_event' => '11');
                                else if($row['joomla_contact_type'] == 'Buyer/Seller')
                                    $match = array('email_event' => '5');
                                else
                                    $match = array('email_event' => '10');
                                
                                $email_temp = array(); $admin_email_temp = array();$autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                //$match = array('email_event'=>'5');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name);

                                if(!empty($autores_res[0]))
                                {
                                    $email_temp = $autores_res[0];
                                }
                                $from_email = '';
                                
                                $address = '';
                                if(!empty($agent_data[0]))
                                {
                                    $from_email = '';
                                    if(!empty($agent_data[0]['email_id'])) {
                                        $from_email = $agent_data[0]['email_id'];
                                    } else {
                                        $from_email = $admin_emailid;
                                    }
                                    
                                    $address .= !empty($agent_data[0]['address_line1'])?$agent_data[0]['address_line1']:'';
                                    $address .= !empty($agent_data[0]['address_line2'])?', '.$agent_data[0]['address_line2']:'';
                                    $address .= !empty($agent_data[0]['city'])?', '.$agent_data[0]['city']:'';
                                    $address .= !empty($agent_data[0]['state'])?', '.$agent_data[0]['state']:'';
                                    $address .= !empty($agent_data[0]['zip_code'])?' '.$agent_data[0]['zip_code']:'';
                                    $agent_data[0]['address'] = $address;
                                    $brokerage_pic = $agent_data[0]['brokerage_pic'];
                                    $admin_name = $agent_data[0]['admin_name'];
                                    $this->user_registration_model->sendPasswordMail($row['email_address'],$row['id'],$row['contact_name'],$row['last_name'],'',$email_temp,'',$from_email,$row['joomla_domain_name'],$row,$brokerage_pic,$admin_name,$agent_data[0]);
                                    
                                    // Code for updating Agent name and email 05-05-2015
                                    /*$url = $this->config->item('joomla_webservice_link')."/libraries/api/update_agent.php?lwid=".$row['id']."&domain=".urlencode(utf8_encode($row['joomla_domain_name']))."&name=".urlencode(utf8_encode($admin_name))."&email=".urlencode(utf8_encode($from_email));
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                                    // This is what solved the issue (Accepting gzidp encoding)
                                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                                    $response = curl_exec($ch);
                                    curl_close($ch); */
                                    
                                }
                                ////////// END Send email to leads 31-03-2015 /////////
                                
                                /////// Email Admin / Agent 31-03-2015/////////////
                                $autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'6');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name);                        
                                if(!empty($autores_res[0]))
                                {
                                    $admin_email_temp = $autores_res[0];
                                }
                                
                                $admin_user = array('id'=>!empty($agent_data[0]['id'])?$agent_data[0]['id']:$admin_id,'email_id'=>!empty($agent_data[0]['email_id'])?$agent_data[0]['email_id']:$admin_emailid,'admin_name'=>!empty($agent_data[0]['admin_name'])?$agent_data[0]['admin_name']:$admin_name,'brokerage_pic'=>!empty($agent_data[0]['brokerage_pic'])?$agent_data[0]['brokerage_pic']:$brokerage_pic);
                                if(!empty($admin_user) && count($admin_user) > 0)
                                {
                                    $admin_emailid = $dbrow['email_id'];
                                    $admin_name = !empty($agent_data[0]['admin_name'])?$agent_data[0]['admin_name']:$dbrow['admin_name'];
                                    $db_name = $dbrow['db_name'];
                                    $brokerage_pic = $dbrow['brokerage_pic'];
                                    $from_email = $admin_emailid;
                                    $admin_user = array();
                                    $admin_user = array('id'=>$admin_id,'email_id'=>!empty($agent_data[0]['email_id'])?$agent_data[0]['email_id']:$dbrow['email_id'],'admin_name'=>$admin_name,'brokerage_pic'=>$brokerage_pic);
                                    $this->user_registration_model->sendPasswordMail($row['email_address'],$row['id'],$row['contact_name'],$row['last_name'],$admin_user,'',$admin_email_temp,$from_email,$row['joomla_domain_name'],$row,'','',$dbrow);
                                    
                                    //// Send Lead assign message user and agent by Sanjay Chabhadiya.    
                                    $this->AutoResponderSMSsend($db_name,$row,$agent_data[0]['id']);
                                    
                                }
                                /////// End Email Admin / Agent 31-03-2015/////////////
                                
                            } else { /// If agent is not assigned
                                ////////// Send email to leads 31-03-2015 /////////
                                $email_temp = array(); $admin_email_temp = array();$autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');

                                /// Auto responder
                                if($row['joomla_contact_type'] == 'Seller')
                                    $match = array('email_event' => '11');
                                else if($row['joomla_contact_type'] == 'Buyer/Seller')
                                    $match = array('email_event' => '5');
                                else
                                    $match = array('email_event' => '10');
                                
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name);

                                if(!empty($autores_res[0]))
                                {
                                    $email_temp = $autores_res[0];
                                }
                                $from_email = '';
                                if(!empty($admin_emailid)) {
                                    $from_email = $admin_emailid;
                                }
                                
                                $this->user_registration_model->sendPasswordMail($row['email_address'],$row['id'],$row['contact_name'],$row['last_name'],'',$email_temp,'',$from_email,$row['joomla_domain_name'],$row,$brokerage_pic,$admin_name,$dbrow);

                                // Code for updating Agent name and email 05-05-2015
                                /*$url = $this->config->item('joomla_webservice_link')."/libraries/api/update_agent.php?lwid=".$row['id']."&domain=".urlencode(utf8_encode($row['joomla_domain_name']))."&name=".urlencode(utf8_encode($admin_name))."&email=".urlencode(utf8_encode($from_email));
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
                                // Admin Email
                                $autores_res = array();
                                $fields = array('template_name,template_subject,email_message,email_event');
                                $match = array('email_event'=>'6');
                                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name);                        
                                if(!empty($autores_res[0]))
                                {
                                    $admin_email_temp = $autores_res[0];
                                }
                                
                                $admin_user = array('id'=>$admin_id,'email_id'=>$admin_emailid,'admin_name'=>$admin_name,'brokerage_pic'=>$brokerage_pic);
                                //pr($admin_user);exit;
                                if(!empty($admin_user) && count($admin_user) > 0)
                                {
                                    $this->user_registration_model->sendPasswordMail($row['email_address'],$row['id'],$row['contact_name'],$row['last_name'],$admin_user,'',$admin_email_temp,$from_email,$row['joomla_domain_name'],$row,'','',$dbrow);
                                    
                                    //// Send Lead assign message user and agent by Sanjay Chabhadiya.    
                                    $this->AutoResponderSMSsend($db_name,$row,'');
                                }
                                ////////// End Send email to leads  /////////
                            }

                            /////////////////////////////////////////////////////////
                            ////// Round Robin Logic (Lender) 05-01-2015  ///////////
                            /////////////////////////////////////////////////////////

                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,um.min_area,um.max_area,urwt.round,urwt.round_value,lm.agent_type');
                            $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id',
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id');

                            // Check for Weightage assign or not to lender
                            $lwuser_list = array();
                            $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                            $lwuser_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                            
                            $uwhere_in = array();
                            if(!empty($lwuser_list))
                            {
                                $user_list = array();$rr_last_weightage = array();$rr_user_list = array();$new_rr_data = array();
                                $data = array();$rr_data = array();

                                $all_user_list = '';
                                foreach($lwuser_list as $aurow) // Get lender id only
                                {
                                    $all_user_list .= $aurow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                $uwhere_in = array('um.id'=>$all_user_list);
                                
                                ///// Check for domain 09-06-2015 Sanjay Moghariya /////
                                $assigned_agent_domain_list = array();
                                if(!empty($domain_id))
                                {
                                    $dtable = $db_name.'.user_domain_trans';
                                    $dfields = array('user_id');
                                    $dmatch = array('domain_id'=>$domain_id);
                                    $dwhere_in = array('user_id'=>$all_user_list);
                                    $assigned_agent_domain_list = $this->contact_masters_model->getmultiple_tables_records($dtable,$dfields,'','','',$dmatch,'=','','','','','','','',$dwhere_in);

                                    if(!empty($assigned_agent_domain_list))
                                    {
                                        $dall_user_list = '';
                                        foreach($assigned_agent_domain_list as $daurow) // Get agent id only
                                        {
                                            $dall_user_list .= $daurow['user_id'].',';
                                        }
                                        $dall_user_list = trim($dall_user_list,',');
                                        $uwhere_in = array('um.id'=>$dall_user_list);
                                    }
                                }
                                ///// End Check for domain 09-06-2015 Sanjay Moghariya /////
                                
                                // Check for price agent which is within price range
                                if(!empty($price_flag))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'',$min_price,$max_price,'1');
                                }

                                // Check this if price range found then check with area criteria
                                if(!empty($user_list) && !empty($area_flag))
                                {
                                    $old_user_list = $user_list;
                                    $all_user_list = '';
                                    foreach($user_list as $aurow) // Get agent id only
                                    {
                                        $all_user_list .= $aurow['id'].',';
                                    }
                                    $all_user_list = trim($all_user_list,',');
                                    $where_in = array('um.id'=>$all_user_list);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in,'','','','','','',$min_area,$max_area,'1');
                                    if(empty($user_list))
                                        $user_list = $old_user_list;
                                }
                                // Check this if price range not found then check area criteria only
                                else if(empty($user_list) && !empty($area_flag))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'','','','','','',$min_area,$max_area,'1');
                                }
                                
                                if(empty($user_list) && !empty($assigned_agent_domain_list)) // If found agent with assigned domain and with price/area criteria
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'','','','');
                                }
                                else { // If not found agent with assigned domain then check with excluding domain/price/area criteria
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                                }
                   
                                if(!empty($user_list))
                                {
                                    $table = $db_name.".lender_rr_weightage_trans";
                                    $fields = array('user_id,user_weightage,round,round_value');
                                    $match = array('agent_type'=>'Lender');
                                    $rr_last_weightage = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','','','','','','id','desc');
                                    if(!empty($rr_last_weightage))
                                    {
                                        $all_user_list = '';
                                        $aulist = array();
                                        foreach($user_list as $urow)
                                        {
                                            $rr_uid = array();
                                            $table = $db_name.".lender_rr_weightage_trans";
                                            $fields = array('user_id');
                                            $match = array('user_id'=> $urow['id']);
                                            $rr_uid = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','id','desc');
                                            if(empty($rr_uid))
                                            {
                                                $new_rr_data['user_id'] = $urow['id'];
                                                $new_rr_data['user_weightage'] = $urow['user_weightage'];
                                                $new_rr_data['round'] = 0;
                                                $new_rr_data['round_value'] = 0;
                                                $new_rr_uid = $this->user_management_model->insert_lender_rr_weightage_trans_record($new_rr_data,$db_name);
                                            }   
                                            $all_user_list .= $urow['id'].',';
                                            $aulist[] = $urow;
                                        }
                                        $all_user_list = trim($all_user_list,',');

                                        $this->session->unset_userdata('assigned_contact_session');
                                        //Call function for assign contact to admin
                                        $rr_user_list = $this->new_rr_lender_id($all_user_list,$user_list,$db_name);

                                        if(!empty($rr_user_list))
                                        {
                                            $data['contact_id'] = $row['id'];
                                            $data['user_id'] = $rr_user_list[0]['id'];
                                            $data['agent_type'] = 'Lender';
                                            //$data['created_by'] = $post_data['user_id'];
                                            $data['created_date']  = date('Y-m-d H:i:s');
                                            $data['status']  = '1';
                                            $uct_id = $this->user_management_model->insert_user_contact_trans_record($data,$db_name);

                                            $rr_data['assigned_contact_id'] = $uct_id;
                                            $rr_data['user_id'] = $rr_user_list[0]['id'];
                                            $rr_data['user_weightage'] = $rr_user_list[0]['user_weightage'];
                                            $rr_data['round'] = $rr_user_list[0]['round'];
                                            $rr_data['round_value'] = $rr_user_list[0]['round_value'];

                                            $rr_weightage_id = $this->user_management_model->update_lender_rr_weightage_trans_record($rr_data,$db_name);
                                        }
                                    }
                                    else // First Time
                                    {
                                        $data['contact_id'] = $row['id'];
                                        $data['user_id'] = $user_list[0]['id'];
                                        $data['agent_type'] = 'Lender';
                                        //$data['created_by'] = $post_data['user_id'];
                                        $data['created_date']  = date('Y-m-d H:i:s');
                                        $data['status']  = '1';
                                        $uct_id = $this->user_management_model->insert_user_contact_trans_record($data,$db_name);

                                        $rr_data['assigned_contact_id'] = $uct_id;
                                        $rr_data['user_id'] = $user_list[0]['id'];
                                        $rr_data['user_weightage'] = $user_list[0]['user_weightage'];
                                        $rr_data['round'] = 1;
                                        $rr_data['round_value'] = 1;
                                        $rr_weightage_id = $this->user_management_model->insert_lender_rr_weightage_trans_record($rr_data,$db_name);
                                        //break;
                                    }
                                    echo "Contact Id: ".$data['contact_id']. " Lender: ".$data['user_id']."<br />";
                                } else {
                                    echo "Lender not assigned..!! <br />";
                                }
                            } else {
                                echo "Lender not assigned..!! <br />";
                            }
                            
                            /////////////////////////////////////////////////////////
                            ////// End Round Robin Logic (Lender) 05-01-2015  ///////
                            /////////////////////////////////////////////////////////


                            /////////// Interaction Plan 17-02-2015 Sanjay Moghariya/////////
                            $datalist = array();
                            $contact_type = $row['joomla_contact_type'];

                            ///// Assign plan to Joomla 15-11-2014 /////////
                            $table = $db_name.".joomla_leads_plan_assign as jlps";
                            //$wherestring = array('jlps.status'=>"'On'",'jlps.min_price >='=>$post_data['min_price'],'jlps.max_price <='=>$post_data['max_price']);
                            $wherestring = array('jlps.status'=>"'On'",'jlps.prospect_type'=>"'$contact_type'");
                            $fields = array('jlps.*');
                            $join_tables = array(
                                $db_name.'.interaction_plan_master as ipm'=>'jlps.interaction_plan_id = ipm.id',
                            );

                            $min_price = !empty($row['price_range_from'])?$row['price_range_from']:0;
                            $max_price = !empty($row['price_range_to'])?$row['price_range_to']:0;
                            //$datalist = $this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','', '','','','',$wherestring,'',$min_price,$max_price,'1');
                            $datalist = $this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','', '','','','',$wherestring,'',$min_price,$max_price,'0');
                            /*echo $this->db->last_query();
                            pr($datalist);
                            exit;*/
                            if(!empty($datalist) && count($datalist) > 0)
                            {
                                $iplanarr = array();
                                foreach($datalist as $irow)
                                {
                                    $iplanarr[] = $irow['interaction_plan_id'];
                                }
                                $this->assing_iplan_to_contact($iplanarr, $row['id'],$db_name);
                                echo "Interaction Plan: ";
                                pr($iplanarr);
                            } else {
                                echo "Interaction Plan not assigned..!! <br /> ";
                            }

                            ///// END Assign plan to Joomla 15-11-2014  /////////
                        }
                    }
                    else {
                        echo "New leads not found..!! <br />";
                    }
                }
            }

            if(!empty($insert_cron_id))
			{
				$db_name = $this->config->item('parent_db_name');
	        	$table = $db_name.'.cron_test';
		 		$field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
				$insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }
        
    }
       
    /*
        @Description: Function for get domain id
        @Author     : Sanjay Moghariya
        @Input      : domain name
        @Output     : domain id
        @Date       : 09-06-2015
    */
    function get_domain_id($dbname,$domain_name)
    {
        $table = $dbname.'.child_website_domain_master';
        $fields = array('id');
        $match = array('domain_name'=>$domain_name);
        $domain_arr = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
        
        $domain_id = !empty($domain_arr[0]['id'])?$domain_arr[0]['id']:'';
        
        if(!empty($domain_id))
            return $domain_id;
        else
            return 0;
    }
        
        /*
            @Description: Function for rr weightage logic
            @Author     : Sanjay Moghariya
            @Input      : all user list which matches price criteria or admin list
            @Output     : User details
            @Date       : 17-11-14 (using cron 17-02-2015)
        */
        
        function new_rr_user_id($all_user_list,$user_list,$db_name)
        {
            $table = $db_name.".user_master as um";
            $fields = array('min(urwt.round) as min_round');
            $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
            $where_in = array('um.id'=>$all_user_list);
            $rr_last_round_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
            //echo $this->db->last_query();
            //pr($rr_last_round_sort);exit;
            $cur_round = !empty($rr_last_round_sort[0]['min_round'])?$rr_last_round_sort[0]['min_round']:0;
            
            $i = 0;
            $aulist = array();
            $rr_data = array();
            
            // Check Round
            $table = $db_name.".user_master as um";
            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
            $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
            $wherestring = array('urwt.round'=>$cur_round);
            $where_in = array('um.id'=>$all_user_list);
            //$where_not_in = array('um.id'=>$curr_user_id);
            $where_not_in = '';
            $rr_last_round = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','urwt.round','desc','',$wherestring,$where_not_in,$where_in);
            /*echo "Round user List<br /><br />";
            echo $this->db->last_query();
            pr($rr_last_round);
            exit;*/
            if(!empty($rr_last_round))
            {
                $all_user_list = '';
                foreach($rr_last_round as $urow)
                {
                    $all_user_list .= $urow['id'].',';
                }
                $all_user_list = trim($all_user_list,',');
                if(count($rr_last_round) == 1)
                {
                    $curr_user_id = $rr_last_round[0]['id'];
                    $curr_weightage = $rr_last_round[0]['user_weightage'];
                    $curr_round = $rr_last_round[0]['round'];
                    $curr_round_value = $rr_last_round[0]['round_value'];
                }
                else
                {
                    // Check For Round Value
                    
                    // Find min round value
                    $table = $db_name.".user_master as um";
                    $fields = array('min(urwt.round_value) as min_round_value');
                    $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $where_in = array('um.id'=>$all_user_list);
                    $rr_last_round_val_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                    //echo $this->db->last_query();
                    //pr($rr_last_round_val_sort);exit;
                    $round_value = !empty($rr_last_round_val_sort[0]['min_round_value'])?$rr_last_round_val_sort[0]['min_round_value']:0;
                    
                    $table = $db_name.".user_master as um";
                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                    $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $wherestring = array('urwt.round_value'=>$round_value);
                    $where_in = array('um.id'=>$all_user_list);
                    //$where_not_in = array('um.id'=>$curr_user_id);
                    $where_not_in = '';
                    $rr_last_round_value = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','urwt.round_value','desc','',$wherestring,$where_not_in,$where_in);//,'1');
                    
                    /*echo "Round value user List<br /><br />";
                    echo $this->db->last_query();
                    pr($rr_last_round_value);
                    exit;*/
                    if(!empty($rr_last_round_value))
                    {
                        $all_user_list = '';
                        foreach($rr_last_round_value as $urow)
                        {
                            $all_user_list .= $urow['id'].',';
                        }
                        $all_user_list = trim($all_user_list,',');
                        if(count($rr_last_round_value) == 1)
                        {
                            $curr_user_id = $rr_last_round_value[0]['id'];
                            $curr_weightage = $rr_last_round_value[0]['user_weightage'];
                            $curr_round = $rr_last_round_value[0]['round'];
                            $curr_round_value = $rr_last_round_value[0]['round_value'];
                        }
                        else
                        {
                            // Check For Weightage
                            
                            // Find max weightage
                            $table = $db_name.".user_master as um";
                            $fields = array('max(um.user_weightage) as max_weightage_value');
                            $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $where_in = array('um.id'=>$all_user_list);
                            $rr_max_weightage_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                            //echo $this->db->last_query();
                            //pr($rr_last_round_val_sort);exit;
                            $cur_weightage = $rr_max_weightage_sort[0]['max_weightage_value'];
                            
                            
                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                            $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $wherestring = array('um.user_weightage >='=>$cur_weightage);
                            $where_in = array('um.id'=>$all_user_list);
                            //$where_not_in = array('um.id'=>$curr_user_id);
                            $where_not_in = '';
                            $rr_last_weightag = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.user_weightage','desc','',$wherestring,$where_not_in,$where_in);
                            /*echo "User weightage user List<br /><br />";
                            echo $this->db->last_query();
                            pr($rr_last_weightag);
                            exit;*/
                            if(!empty($rr_last_weightag))
                            {
                                $all_user_list = '';
                                foreach($rr_last_weightag as $urow)
                                {
                                    $all_user_list .= $urow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                if(count($rr_last_weightag) == 1)
                                {
                                    $curr_user_id = $rr_last_weightag[0]['id'];
                                    $curr_weightage = $rr_last_weightag[0]['user_weightage'];
                                    $curr_round = $rr_last_weightag[0]['round'];
                                    $curr_round_value = $rr_last_weightag[0]['round_value'];
                                }
                                else
                                {
                                    // Check For user id order
                                    $table = $db_name.".user_master as um";
                                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                                    $join_tables = array($db_name.'.user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                                    $where_in = array('um.id'=>$all_user_list);
                                    
                                    $rr_last_userid = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.id','asc','','','',$where_in);

                                    $curr_user_id = $rr_last_userid[0]['id'];
                                    $curr_weightage = !empty($rr_last_userid[0]['user_weightage'])?$rr_last_userid[0]['user_weightage']:0;
                                    $curr_round = !empty($rr_last_userid[0]['round'])?$rr_last_userid[0]['round']:0;
                                    $curr_round_value = !empty($rr_last_userid[0]['round_value'])?$rr_last_userid[0]['round_value']:0;
                                }
                            }
                        }
                    }
                }
            }
            
            if($curr_round_value >= $curr_weightage)
            {
                $curr_round += 1;
                $curr_round_value = 1;
            }
            else {
                $curr_round_value++;
            }
            if(empty($curr_round)) {
                $curr_round = 1;
            }
            if(empty($curr_round_value)) {
                $curr_round_value = 1;
            }
            
            $rr_data = array();
            $rr_data[0]['id'] = $curr_user_id;
            $rr_data[0]['user_weightage'] = $curr_weightage;
            $rr_data[0]['round'] = $curr_round;
            $rr_data[0]['round_value'] = $curr_round_value;
            //pr($rr_data);
            //exit;
            return $rr_data;
            
            //END 17-11
        }
        
        /*
            @Description: Function for rr weightage logic (Lender)
            @Author     : Sanjay Moghariya
            @Input      : all user list which matches price criteria or admin list
            @Output     : User details
            @Date       : 05-01-2014 (using cron 17-02-2015)
        */
        function new_rr_lender_id($all_user_list,$user_list,$db_name)
        {
            $table = $db_name.".user_master as um";
            $fields = array('min(urwt.round) as min_round');
            $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
            $where_in = array('um.id'=>$all_user_list);
            $rr_last_round_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
            //echo $this->db->last_query();
            //pr($rr_last_round_sort);exit;
            $cur_round = !empty($rr_last_round_sort[0]['min_round'])?$rr_last_round_sort[0]['min_round']:0;
            
            $i = 0;
            $aulist = array();
            $rr_data = array();
            
            // Check Round
            $table = $db_name.".user_master as um";
            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
            $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
            $wherestring = array('urwt.round'=>$cur_round);
            $where_in = array('um.id'=>$all_user_list);
            //$where_not_in = array('um.id'=>$curr_user_id);
            $where_not_in = '';
            $rr_last_round = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','urwt.round','desc','',$wherestring,$where_not_in,$where_in);
            /*echo "Round user List<br /><br />";
            echo $this->db->last_query();
            pr($rr_last_round);
            exit;*/
            if(!empty($rr_last_round))
            {
                $all_user_list = '';
                foreach($rr_last_round as $urow)
                {
                    $all_user_list .= $urow['id'].',';
                }
                $all_user_list = trim($all_user_list,',');
                if(count($rr_last_round) == 1)
                {
                    $curr_user_id = $rr_last_round[0]['id'];
                    $curr_weightage = $rr_last_round[0]['user_weightage'];
                    $curr_round = $rr_last_round[0]['round'];
                    $curr_round_value = $rr_last_round[0]['round_value'];
                }
                else
                {
                    // Check For Round Value
                    
                    // Find min round value
                    $table = $db_name.".user_master as um";
                    $fields = array('min(urwt.round_value) as min_round_value');
                    $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $where_in = array('um.id'=>$all_user_list);
                    $rr_last_round_val_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                    //echo $this->db->last_query();
                    //pr($rr_last_round_val_sort);exit;
                    $round_value = !empty($rr_last_round_val_sort[0]['min_round_value'])?$rr_last_round_val_sort[0]['min_round_value']:0;
                    
                    
                    
                    
                    $table = $db_name.".user_master as um";
                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                    $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $wherestring = array('urwt.round_value'=>$round_value);
                    $where_in = array('um.id'=>$all_user_list);
                    //$where_not_in = array('um.id'=>$curr_user_id);
                    $where_not_in = '';
                    $rr_last_round_value = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','urwt.round_value','desc','',$wherestring,$where_not_in,$where_in);//,'1');
                    
                    /*echo "Round value user List<br /><br />";
                    echo $this->db->last_query();
                    pr($rr_last_round_value);
                    exit;*/
                    if(!empty($rr_last_round_value))
                    {
                        $all_user_list = '';
                        foreach($rr_last_round_value as $urow)
                        {
                            $all_user_list .= $urow['id'].',';
                        }
                        $all_user_list = trim($all_user_list,',');
                        if(count($rr_last_round_value) == 1)
                        {
                            $curr_user_id = $rr_last_round_value[0]['id'];
                            $curr_weightage = $rr_last_round_value[0]['user_weightage'];
                            $curr_round = $rr_last_round_value[0]['round'];
                            $curr_round_value = $rr_last_round_value[0]['round_value'];
                        }
                        else
                        {
                            // Check For Weightage
                            
                            // Find max weightage
                            $table = $db_name.".user_master as um";
                            $fields = array('max(um.user_weightage) as max_weightage_value');
                            $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $where_in = array('um.id'=>$all_user_list);
                            $rr_max_weightage_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                            //echo $this->db->last_query();
                            //pr($rr_last_round_val_sort);exit;
                            $cur_weightage = $rr_max_weightage_sort[0]['max_weightage_value'];
                            
                            
                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                            $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $wherestring = array('um.user_weightage >='=>$cur_weightage);
                            $where_in = array('um.id'=>$all_user_list);
                            //$where_not_in = array('um.id'=>$curr_user_id);
                            $where_not_in = '';
                            $rr_last_weightag = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.user_weightage','desc','',$wherestring,$where_not_in,$where_in);
                            /*echo "User weightage user List<br /><br />";
                            echo $this->db->last_query();
                            pr($rr_last_weightag);
                            exit;*/
                            if(!empty($rr_last_weightag))
                            {
                                $all_user_list = '';
                                foreach($rr_last_weightag as $urow)
                                {
                                    $all_user_list .= $urow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                if(count($rr_last_weightag) == 1)
                                {
                                    $curr_user_id = $rr_last_weightag[0]['id'];
                                    $curr_weightage = $rr_last_weightag[0]['user_weightage'];
                                    $curr_round = $rr_last_weightag[0]['round'];
                                    $curr_round_value = $rr_last_weightag[0]['round_value'];
                                }
                                else
                                {
                                    // Check For user id order
                                    $table = $db_name.".user_master as um";
                                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                                    $join_tables = array($db_name.'.lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                                    $where_in = array('um.id'=>$all_user_list);
                                    
                                    $rr_last_userid = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','um.id','asc','','','',$where_in);

                                    $curr_user_id = $rr_last_userid[0]['id'];
                                    $curr_weightage = !empty($rr_last_userid[0]['user_weightage'])?$rr_last_userid[0]['user_weightage']:0;
                                    $curr_round = !empty($rr_last_userid[0]['round'])?$rr_last_userid[0]['round']:0;
                                    $curr_round_value = !empty($rr_last_userid[0]['round_value'])?$rr_last_userid[0]['round_value']:0;
                                }
                            }
                        }
                    }
                }
            }
            
            if($curr_round_value >= $curr_weightage)
            {
                $curr_round += 1;
                $curr_round_value = 1;
            }
            else {
                $curr_round_value++;
            }
            if(empty($curr_round)) {
                $curr_round = 1;
            }
            if(empty($curr_round_value)) {
                $curr_round_value = 1;
            }
            
            $rr_data = array();
            $rr_data[0]['id'] = $curr_user_id;
            $rr_data[0]['user_weightage'] = $curr_weightage;
            $rr_data[0]['round'] = $curr_round;
            $rr_data[0]['round_value'] = $curr_round_value;
            return $rr_data;
        }
        
        /*
            @Description: Function for Assign contact to interaction plan
            @Author     : Sanjay Moghariya (Copy from contacts_controller->insert_data())
            @Input      : Interaction plan array, contact id
            @Output     : 
            @Date       : 15-11-14 (using cron 17-02-2015)
        */
        function assing_iplan_to_contact($iplan_arr,$contact_id,$db_name)
        {
            $newplansarr = $iplan_arr;
		////////////////////////////////// Add Interaction Plan Contacts Transaction Data ///////////////////////////////////////////
            if(!empty($newplansarr) && count($newplansarr) > 0)
            {
                foreach($newplansarr as $interaction_plan_id)
                {
                    if($interaction_plan_id != '')
                    {
                        $match1 = array('id'=>$interaction_plan_id);
                        $plandata = $this->interaction_plans_model->select_records('',$match1,'','=','','','','','',$db_name);

                        //pr($plandata);exit;

                        $cdata = $plandata[0];

                        //pr($cdata);exit;

                        if(!empty($cdata))
                        {
                            //echo $cdata['plan_name'];
                            $data_conv['contact_id'] = $contact_id;
                            $data_conv['plan_id'] = $interaction_plan_id;
                            $data_conv['plan_name'] = !empty($cdata['plan_name'])?$cdata['plan_name']:'';
                            $data_conv['created_date'] = date('Y-m-d H:i:s');
                            $data_conv['log_type'] = '2';
                            //$data_conv['created_by'] = $this->admin_session['id'];
                            $data_conv['status'] = '1';

                            //pr($data_conv);exit;

                            $this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv,$db_name);

                            $icdata['interaction_plan_id'] = $interaction_plan_id;
                            $icdata['contact_id'] = $contact_id;

                            if($cdata['plan_start_type'] == '1')
                            {
                                $icdata['start_date'] = date('Y-m-d');
                                $icdata['plan_start_type'] = $cdata['plan_start_type'];
                                $icdata['plan_start_date'] = $cdata['start_date'];
                            }
                            else
                            {
                                if(strtotime(date('Y-m-d')) < strtotime($cdata['start_date']))
                                    $icdata['start_date'] = date('Y-m-d',strtotime($cdata['start_date']));
                                else
                                    $icdata['start_date'] = date('Y-m-d');

                                $icdata['plan_start_type'] = $cdata['plan_start_type'];
                                $icdata['plan_start_date'] = $cdata['start_date'];
                            }

                            $icdata['created_date'] = date('Y-m-d H:i:s');
                            //$icdata['created_by'] = $this->admin_session['id'];
                            $icdata['status'] = '1';

                            //pr($icdata);exit;

                            $this->interaction_plans_model->insert_contact_trans_record($icdata,$db_name);

                            ///////////////////////////////////////////////////////////////////////////

                            $plan_id = $interaction_plan_id;

                            ////////////////////////////////////////////////////////

                            $table = $db_name.".interaction_plan_interaction_master as ipim";
                            $fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
                            $join_tables = array(
                                $db_name.'.interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
                                $db_name.'.admin_users as au' => 'au.id = ipim.created_by',
                                $db_name.'.interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
                            );

                            $group_by='ipim.id';

                            $where1 = array('ipim.interaction_plan_id'=>$plan_id);

                            $interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);


                            ///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////

                            //pr($interaction_list);exit;

                            if(count($interaction_list) > 0)
                            {
                                foreach($interaction_list as $row1)
                                {
                                    $assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;

                                    //////////////////// Integrate User Work time config ///////////////////

                                    if(!empty($assigned_user_id))
                                    {

                                        //echo $assigned_user_id;

                                        //Get Working Days

                                        //$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
                                        $new_user_id = $assigned_user_id;

                                        //echo $new_user_id;

                                        $match = array("user_id"=>$new_user_id);
                                        $worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$db_name.'.work_time_config_master');

                                        //pr($worktimedata);exit;

                                        $match = array("user_id"=>$new_user_id,"rule_type"=>1);
                                        $worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$db_name.'.work_time_special_rules');

                                        //pr($worktimespecialdata);exit;

                                        $match = array("user_id"=>$new_user_id);
                                        $worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$db_name.'.user_leave_data');

                                        //pr($worktimeleavedata);exit;

                                        $user_work_off_days1 = array();

                                        if(!empty($worktimedata[0]['id']))
                                        {
                                            if(empty($worktimedata[0]['if_mon']))
                                                    $user_work_off_days1[] = 'Mon';
                                            if(empty($worktimedata[0]['if_tue']))
                                                    $user_work_off_days1[] = 'Tue';
                                            if(empty($worktimedata[0]['if_wed']))
                                                    $user_work_off_days1[] = 'Wed';
                                            if(empty($worktimedata[0]['if_thu']))
                                                    $user_work_off_days1[] = 'Thu';
                                            if(empty($worktimedata[0]['if_fri']))
                                                    $user_work_off_days1[] = 'Fri';
                                            if(empty($worktimedata[0]['if_sat']))
                                                    $user_work_off_days1[] = 'Sat';
                                            if(empty($worktimedata[0]['if_sun']))
                                                    $user_work_off_days1[] = 'Sun';
                                        }

                                        $special_days1 = array();

                                        if(!empty($worktimespecialdata))
                                        {
                                            foreach($worktimespecialdata as $row2)
                                            {
                                                $day_string = '';
                                                if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
                                                {
                                                    switch($row2['nth_day'])
                                                    {
                                                        case 1:
                                                        $day_string .= 'First ';
                                                        break;
                                                        case 2:
                                                        $day_string .= 'Second ';
                                                        break;
                                                        case 3:
                                                        $day_string .= 'Third ';
                                                        break;
                                                        case 4:
                                                        $day_string .= 'Fourth ';
                                                        break;
                                                        case 5:
                                                        $day_string .= 'Last ';
                                                        break;
                                                    }
                                                    switch($row2['nth_date'])
                                                    {
                                                        case 1:
                                                        $day_string .= 'Day';
                                                        break;
                                                        case 2:
                                                        $day_string .= 'Weekday';
                                                        break;
                                                        case 3:
                                                        $day_string .= 'Weekend';
                                                        break;
                                                        case 4:
                                                        $day_string .= 'Monday';
                                                        break;
                                                        case 5:
                                                        $day_string .= 'Tuesday';
                                                        break;
                                                        case 6:
                                                        $day_string .= 'Wednesday';
                                                        break;
                                                        case 7:
                                                        $day_string .= 'Thursday';
                                                        break;
                                                        case 8:
                                                        $day_string .= 'Friday';
                                                        break;
                                                        case 9:
                                                        $day_string .= 'Saturday';
                                                        break;
                                                        case 10:
                                                        $day_string .= 'Sunday';
                                                        break;
                                                        default:
                                                        break;
                                                    }
                                                    $special_days1[] = $day_string;
                                                }
                                            }
                                        }

                                        $leave_days1 = array();

                                        foreach($worktimeleavedata as $row2)
                                        {
                                            if(!empty($row2['from_date']))
                                            {
                                                $leave_days1[] = $row2['from_date'];
                                                if(!empty($row2['to_date']))
                                                {

                                                    //$from_date = date('Y-m-d',strtotime($row['from_date']));

                                                    $from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));

                                                    $to_date = date('Y-m-d',strtotime($row2['to_date']));

                                                    //echo $from_date."-".$to_date;

                                                    while($from_date <= $to_date)
                                                    {
                                                        $leave_days1[] = $from_date;
                                                        $from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
                                                    }
                                                }
                                            }
                                        }
                                        //pr($user_work_off_days1);

                                        //pr($special_days1);

                                        //pr($leave_days1);
                                    }
                                    
                                    ////////////////////////////////////////////////////////////////////////
                                    $iccdata['interaction_plan_id'] = $plan_id;
                                    $iccdata['contact_id'] = $contact_id;
                                    $iccdata['interaction_plan_interaction_id'] = $row1['id'];
                                    $iccdata['interaction_type'] = $row1['interaction_type'];

                                    if($row1['start_type'] == '1')
                                    {
                                        $count = $row1['number_count'];
                                        $counttype = $row1['number_type'];

                                        ///////////////////////////////////////////////////////////////

                                        $match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
                                        $plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','',$db_name);

                                        //pr($plan_contact_data);exit;

                                        ///////////////////////////////////////////////////////////////

                                        if(!empty($plan_contact_data[0]['start_date']))
                                        {
                                            $newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));

                                            $newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));

                                            ////////////////////////////////////////////////////////

                                            $repeatoff = 1;

                                            while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
                                            {
                                                // Check for Work off days
                                                // echo $newtaskdate;
                                                $day_of_date = date('D', strtotime($newtaskdate1));
                                                $new_special_days = array();

                                                if(!empty($special_days1))
                                                {
                                                    foreach($special_days1 as $mydays)
                                                    {
                                                        if (strpos($mydays,'Weekday') !== false) {
                                                            $nthday = explode(" ",$mydays);
                                                            if(!empty($nthday[0]))
                                                            {
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
                                                            }
                                                        }
                                                        elseif (strpos($mydays,'Weekend') !== false) {
                                                            $nthday = explode(" ",$mydays);
                                                            if(!empty($nthday[0]))
                                                            {
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
                                                            }
                                                        }
                                                        else
                                                            $new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
                                                    }
                                                }

                                                //pr($new_special_days);

                                                if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
                                                {
                                                        //echo 'work off'."<br>";
                                                        $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
                                                {
                                                        //echo 'special days'."<br>";
                                                        $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
                                                {
                                                        //echo 'leave'."<br>";
                                                        $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                else
                                                {
                                                        //echo 'else';
                                                        $repeatoff = 0;
                                                        $newtaskdate = $newtaskdate1;
                                                }

                                                //echo $repeatoff;
                                            }
                                            //while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));

                                            ///////////////////////////////////////////////////////

                                            $iccdata['task_date'] = $newtaskdate;
                                        }
                                    }
                                    elseif($row1['start_type'] == '2')
                                    {
                                        $count = $row1['number_count'];
                                        $counttype = $row1['number_type'];

                                        $interaction_id = $row1['interaction_id'];

                                        $interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id,$db_name);

                                        //pr($interaction_res);

                                        //echo $interaction_res->task_date;

                                        if(!empty($interaction_res->task_date))
                                        {
                                            $newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));

                                            $newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));

                                            ////////////////////////////////////////////////////////

                                            $repeatoff = 1;

                                            while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
                                            {
                                                // Check for Work off days
                                                // echo $newtaskdate;
                                                $day_of_date = date('D', strtotime($newtaskdate1));
                                                $new_special_days = array();

                                                if(!empty($special_days1))
                                                {
                                                    foreach($special_days1 as $mydays)
                                                    {
                                                        if (strpos($mydays,'Weekday') !== false) {
                                                            $nthday = explode(" ",$mydays);
                                                            if(!empty($nthday[0]))
                                                            {
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
                                                            }
                                                        }
                                                        elseif (strpos($mydays,'Weekend') !== false) {
                                                            $nthday = explode(" ",$mydays);
                                                            if(!empty($nthday[0]))
                                                            {
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
                                                                $new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
                                                            }
                                                        }
                                                        else
                                                            $new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
                                                    }
                                                }

                                                //pr($new_special_days);

                                                if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
                                                {
                                                    //echo 'work off'."<br>";
                                                    $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
                                                {
                                                    //echo 'special days'."<br>";
                                                    $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
                                                {
                                                    //echo 'leave'."<br>";
                                                    $newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
                                                }
                                                else
                                                {
                                                    //echo 'else';
                                                    $repeatoff = 0;
                                                    $newtaskdate = $newtaskdate1;
                                                }

                                                //echo $repeatoff;
                                            }

                                            //while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));

                                            ///////////////////////////////////////////////////////

                                            //echo $newtaskdate;

                                            //$icdata['task_date'] = $newtaskdate;

                                            $iccdata['task_date'] = $newtaskdate;
                                        }

                                    }
                                    else
                                    {
                                        $iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
                                    }

                                    $sendemaildate = $iccdata['task_date'];

                                    $iccdata['created_date'] = date('Y-m-d H:i:s');
                                    //$iccdata['created_by'] = $this->admin_session['id'];

                                    $this->interaction_model->insert_contact_communication_record($iccdata,$db_name);

                                    unset($iccdata);
                                    unset($user_work_off_days1);
                                    unset($special_days1);
                                    unset($leave_days1);

                                    /* Email campaign/SMS campaign Insert */
                                    $match = array('id'=>$contact_id);
                                    $userdata = $this->contacts_model->select_records('',$match,'','=','','','','','','',$db_name);
                                    
                                    $agent_name = '';
                                    if(!empty($userdata[0]['created_by']))
                                    {
                                        $table = $db_name.".login_master as lm";   
                                        $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                        $join_tables = array($db_name.'.user_master as um'=>'lm.user_id = um.id');
                                        $wherestring = 'lm.id = '.$userdata[0]['created_by'];
                                        $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                        if(!empty($agent_datalist))
                                        {
                                            if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                                $agent_name = $agent_datalist[0]['admin_name'];
                                            else
                                                $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                        }
                                    }
                                    if($row1['interaction_type'] == 6 && count($userdata) > 0)
                                    {	
                                        //$row1['id'];
                                        $match = array('interaction_id'=>$row1['id']);
                                        $campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=','','','','','',$db_name);
                                        if(count($campaigndata) > 0)
                                        {
                                            $cdata1['email_campaign_id'] = $campaigndata[0]['id'];
                                            $cdata1['contact_id'] = $contact_id;
                                            $emaildata = array(
                                                'Date'=>date('Y-m-d'),
                                                'Day'=>date('l'),
                                                'Month'=>date('F'),
                                                'Year'=>date('Y'),
                                                'Day Of Week'=>date("w",time()),
                                                'Agent Name'=>$agent_name,
                                                'Contact First Name'=>$userdata['first_name'],
                                                'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
                                                'Contact Last Name'=>$userdata['last_name'],
                                                'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
                                                'Contact Company Name'=>$userdata['company_name']
                                            );

                                            $content = $campaigndata[0]['email_message'];
                                            $title = $campaigndata[0]['template_subject'];

                                            //pr($emaildata);

                                            $cdata1['template_subject'] = $title;
                                            $cdata1['email_message'] = $content;
                                            $pattern = "{(%s)}";

                                            $map = array();

                                            if($emaildata != '' && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $finaltitle = strtr($title, $map);				
                                                $output = strtr($content, $map);

                                                $cdata1['template_subject'] = $finaltitle;
                                                $finlaOutput = $output;
                                                $cdata1['email_message'] = $finlaOutput;
                                            }

                                            //$emaildata['interaction_id'] = $row1['id'];
                                            $cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
                                            $this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1,$db_name);
                                            //echo $this->db->last_query();
                                        }
                                    }
                                    elseif($row1['interaction_type'] == 3 && count($userdata) > 0)
                                    {
                                        $match = array('interaction_id'=>$row1['id']);
                                        $smscampaigndata = $this->sms_campaign_master_model->select_records('',$match,'','=','','','','','',$db_name);
                                        if(count($smscampaigndata) > 0)
                                        {
                                            $cdata1['sms_campaign_id'] = $smscampaigndata[0]['id'];
                                            $cdata1['contact_id'] = $contact_id;
                                            $emaildata = array(
                                                'Date'=>date('Y-m-d'),
                                                'Day'=>date('l'),
                                                'Month'=>date('F'),
                                                'Year'=>date('Y'),
                                                'Day Of Week'=>date("w",time()),
                                                'Agent Name'=>$agent_name,
                                                'Contact First Name'=>$userdata['first_name'],
                                                'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
                                                'Contact Last Name'=>$userdata['last_name'],
                                                'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
                                                'Contact Company Name'=>$userdata['company_name']
                                            );

                                            $content = $smscampaigndata[0]['sms_message'];
                                            $cdata1['sms_message'] 	= $content;
                                            $pattern = "{(%s)}";

                                            $map = array();

                                            if($emaildata != '' && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($content, $map);

                                                $finlaOutput = $output;
                                                $cdata1['sms_message'] = $finlaOutput;
                                            }
                                            //$smsdata['interaction_id'] = $row1['id'];
                                            $cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
                                            $this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1,$db_name);
                                        }
                                    }
                                    unset($cdata1);
                                    /* END */
                                }
                            }

                            ///////////////////////////////////////////////////////////////////////////
                            unset($icdata);
                            //exit;
                        }
                    }
                }
            }
            //exit;
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        
        function backup_db()
        {
           // error_reporting(E_ALL);
                $db_name = $this->config->item('parent_db_name');
		
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
		$match = array('user_type'=>'2','status'=>'1');
		$all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
		$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>$this->config->item('root_host_name'),'db_user_name'=>$this->config->item('root_user_name'),'db_user_password'=>$this->config->item('root_password')));
		$all_admin1 = array_merge($all_admin,$merge_db);
		//pr($all_admin1);exit;
		
		
		
		if(!empty($all_admin1))
		{
			foreach($all_admin1 as $row)
			{
				$db_name1 = $row['db_name'];
				if(!empty($db_name1))
				{
	                //echo "<br>".$db_name1."<br>";
	                //echo "W :".shell_exec("whoami")."<br>";

	                //mysqldump -u livewire -p -h d6ff2f329eb5275cdd6de6652edead79053c6d0f.rackspaceclouddb.com CRM > /net/nfs/mnt/cbs1/www/vhosts/mylivewiresolution.com/database/backup_09feb15/CRM.sql
	                

	                //$op = shell_exec("mysqldump --user='root' --password='' --host='localhost' ".$db_name1." > /var/www/html/livewire_crm_2/trunk/database/backup_19feb15/".$db_name1.".sql");
	                //echo $op;

	               echo $command = "mysqldump -u livewire -p -h d6ff2f329eb5275cdd6de6652edead79053c6d0f.rackspaceclouddb.com ".$db_name1." > ".$_SERVER['DOCUMENT_ROOT']."/database/dbbackup/db-backup-".$db_name1."-".date('Y-m-d_H:i:s').".sql";
				   exec($command, $return, $status);
				   var_dump($return);
				   var_dump($status);
				   echo "<br><br>";

                //$this->backup_tables($row['host_name'],$row['db_user_name'],$row['db_user_password'],$db_name1);
                                    
				}
			}
		}
                echo 'done';
        }
        
        /* backup the db OR just a table */
        function backup_tables($host,$user,$pass,$name,$tables = '*')
        {

                $link = mysql_connect($host,$user,$pass);
                mysql_select_db($name,$link);

                //get all of the tables
                if($tables == '*')
                {
                        $tables = array();
                        $result = mysql_query('SHOW TABLES');
                        while($row = mysql_fetch_row($result))
                        {
                                $tables[] = $row[0];
                        }
                }
                else
                {
                        $tables = is_array($tables) ? $tables : explode(',',$tables);
                }
                
                $return = '';

                //cycle through
                foreach($tables as $table)
                {
                        $result = mysql_query('SELECT * FROM '.$table);
                        $num_fields = mysql_num_fields($result);

                        $return.= 'DROP TABLE '.$table.';';
                        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                        $return.= "\n\n".$row2[1].";\n\n";

                        for ($i = 0; $i < $num_fields; $i++) 
                        {
                                while($row = mysql_fetch_row($result))
                                {
                                        $return.= 'INSERT INTO '.$table.' VALUES(';
                                        for($j=0; $j<$num_fields; $j++) 
                                        {
                                                $row[$j] = addslashes($row[$j]);
                                                $row[$j] = preg_replace("\n","\\n",$row[$j]);
                                                if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                                                if ($j<($num_fields-1)) { $return.= ','; }
                                        }
                                        $return.= ");\n";
                                }
                        }
                        $return.="\n\n\n";
                }
                
                //echo dirname(__FILE__).'/../../';

                //save file
                $handle = fopen(dirname(__FILE__).'/../../../../database/dbbackup/db-backup-'.$name.'-'.date('Y-m-d H:i:s').'.sql','w+');
                fwrite($handle,$return);
                fclose($handle);
        }
	/*
    @Description: Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
   
    public function retrieve_listing_data_cron()
    {
		//echo "hi";exit;
		
		//phpinfo();
		//exit;
		
		set_time_limit(0);
		//error_reporting(E_ALL);
		//ini_set('display_errors', '1');
		$field=array('id','name');
		$where=array('status'=>'1');
		$propty_type=$this->mls_model->select_records_tran($field,$where,'','=');
		
		// Get maximum update date
		
		/*$field=array('ID','max(UD) as UD');
		$res_data=$this->mls_model->select_records3($field,'','','=','','1','0');
		//pr($res_data);
		//echo $this->db->last_query();
		if(!empty($res_data[0]['UD']))
		{
			$begin_date = str_replace(" ","T",$res_data[0]['UD']);
			$curr_date  = str_replace(" ","T",date('Y-m-d h:i:s'));
		}
		else
		{
			$begin_date = '2010-01-01T00:00:00';
			$curr_date  = '2010-07-01T00:00:00';	
		}*/
		$begin_date = $this->uri->segment(4);
		$curr_date  = $this->uri->segment(5);
		//echo $begin_date;
		//echo $curr_date;exit;
		if(!empty($propty_type))
		{
			foreach($propty_type as $type)	
			{
				
				$client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
				//echo "hi";exit;
				$XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
				$XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
				$XMLQuery .="<Message>";
				$XMLQuery .="<Head>";
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
				$XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
				$XMLQuery .="</Head>";
				$XMLQuery .="<Body>";
				$XMLQuery .="<Query>";
				$XMLQuery .="<MLS>NWMLS</MLS>";
				$XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
				//$XMLQuery .="<Status>A</Status>";
				$XMLQuery .="<BeginDate>".$begin_date."</BeginDate>";;
    			$XMLQuery .="<EndDate>".$curr_date."</EndDate>";
				$XMLQuery .="</Query>";
				$XMLQuery .="<Filter></Filter>";
				$XMLQuery .="</Body>";
				$XMLQuery .="</Message>";
				$XMLQuery .="</EverNetQuerySpecification>";
				$params = array ('v_strXmlQuery' => $XMLQuery);
				$nodelist = $client->RetrieveListingData($params); 
				$accessnodelist = $nodelist->RetrieveListingDataResult;
				$xml_result = new SimpleXMLElement($accessnodelist);
				$json = json_encode($xml_result);
				$mls_data = json_decode($json,TRUE);
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data = $mls_data[$propertytype];
					//pr($property_data);
					//echo "hiiii";echo count($property_data);
					//exit;
					$i = 0;
					$j = 0;
					foreach($property_data as $row)
					{
						$data[$i]["LN"]                         =!empty($row["LN"])?$row["LN"]:"";
						$data[$i]["PTYP"]                       =!empty($row["PTYP"])?$row["PTYP"]:"";
						$data[$i]["LAG"]                        =!empty($row["LAG"])?$row["LAG"]:"";
						$data[$i]["ST"]                         =!empty($row["ST"])?$row["ST"]:"";
						$data[$i]["LP"]                         =!empty($row["LP"])?$row["LP"]:"";
						$data[$i]["SP"]                         =!empty($row["SP"])?$row["SP"]:"";
						$data[$i]["OLP"]                        =!empty($row["OLP"])?$row["OLP"]:"";
						$data[$i]["HSN"]                        =!empty($row["HSN"])?$row["HSN"]:"";
						$data[$i]["DRP"]                        =!empty($row["DRP"])?$row["DRP"]:"";
						$data[$i]["STR"]                        =!empty($row["STR"])?$row["STR"]:"";
						$data[$i]["SSUF"]                       =!empty($row["SSUF"])?$row["SSUF"]:"";
						$data[$i]["DRS"]                        =!empty($row["DRS"])?$row["DRS"]:"";
						$data[$i]["UNT"]                        =!empty($row["UNT"])?$row["UNT"]:"";
						$data[$i]["CIT"]                        =!empty($row["CIT"])?$row["CIT"]:"";
						$data[$i]["STA"]                        =!empty($row["STA"])?$row["STA"]:"";
						$data[$i]["ZIP"]                        =!empty($row["ZIP"])?$row["ZIP"]:"";
						$data[$i]["PL4"]                        =!empty($row["PL4"])?$row["PL4"]:"";
						$data[$i]["BR"]                         =!empty($row["BR"])?$row["BR"]:"";
						$data[$i]["BTH"]                        =!empty($row["BTH"])?$row["BTH"]:"";
						$data[$i]["ASF"]                        =!empty($row["ASF"])?$row["ASF"]:"";
						$data[$i]["LSF"]                        =!empty($row["LSF"])?$row["LSF"]:"";
						$data[$i]["UD"]                         =!empty($row["UD"])?$row["UD"]:"";
						$data[$i]["AR"]                         =!empty($row["AR"])?$row["AR"]:"";
						$data[$i]["DSRNUM"]                     =!empty($row["DSRNUM"])?$row["DSRNUM"]:"";
						$data[$i]["LDR"]                        =!empty($row["LDR"])?$row["LDR"]:"";
						$data[$i]["LD"]                         =!empty($row["LD"])?$row["LD"]:"";
						$data[$i]["CLO"]                        =!empty($row["CLO"])?$row["CLO"]:"";
						$data[$i]["YBT"]                        =!empty($row["YBT"])?$row["YBT"]:"";
						$data[$i]["LO"]                         =!empty($row["LO"])?$row["LO"]:"";
						$data[$i]["TAX"]                        =!empty($row["TAX"])?$row["TAX"]:"";
						$data[$i]["MAP"]                        =!empty($row["MAP"])?$row["MAP"]:"";
						$data[$i]["GRDX"]                       =!empty($row["GRDX"])?$row["GRDX"]:"";
						$data[$i]["GRDY"]                       =!empty($row["GRDY"])?$row["GRDY"]:"";
						$data[$i]["SAG"]                        =!empty($row["SAG"])?$row["SAG"]:"";
						$data[$i]["SO"]                         =!empty($row["SO"])?$row["SO"]:"";
						$data[$i]["NIA"]                        =!empty($row["NIA"])?$row["NIA"]:"";
						$data[$i]["MR"]                         =!empty($row["MR"])?$row["MR"]:"";
						$data[$i]["LONGI"]                       =!empty($row["LONG"])?$row["LONG"]:"";
						$data[$i]["LAT"]                        =!empty($row["LAT"])?$row["LAT"]:"";
						$data[$i]["PDR"]                        =!empty($row["PDR"])?$row["PDR"]:"";
						$data[$i]["CLA"]                        =!empty($row["CLA"])?$row["CLA"]:"";
						$data[$i]["SHOADR"]                     =!empty($row["SHOADR"])?$row["SHOADR"]:"";
						$data[$i]["DD"]                         =!empty($row["DD"])?$row["DD"]:"";
						$data[$i]["AVDT"]                       =!empty($row["AVDT"])?$row["AVDT"]:"";
						$data[$i]["INDT"]                       =!empty($row["INDT"])?$row["INDT"]:"";
						$data[$i]["COU"]                        =!empty($row["COU"])?$row["COU"]:"";
						$data[$i]["CDOM"]                       =!empty($row["CDOM"])?$row["CDOM"]:"";
						$data[$i]["CTDT"]                       =!empty($row["CTDT"])?$row["CTDT"]:"";
						$data[$i]["SCA"]                        =!empty($row["SCA"])?$row["SCA"]:"";
						$data[$i]["SCO"]                        =!empty($row["SCO"])?$row["SCO"]:"";
						$data[$i]["VIRT"]                       =!empty($row["VIRT"])?$row["VIRT"]:"";
						$data[$i]["SDT"]                        =!empty($row["SDT"])?$row["SDT"]:"";
						$data[$i]["SD"]                         =!empty($row["SD"])?$row["SD"]:"";
						$data[$i]["FIN"]                        =!empty($row["FIN"])?$row["FIN"]:"";
						$data[$i]["MAPBOOK"]                    =!empty($row["MAPBOOK"])?$row["MAPBOOK"]:"";
						$data[$i]["DSR"]                        =!empty($row["DSR"])?$row["DSR"]:"";
						$data[$i]["QBT"]                        =!empty($row["QBT"])?$row["QBT"]:"";
						$data[$i]["HSNA"]                       =!empty($row["HSNA"])?$row["HSNA"]:"";
						$data[$i]["COLO"]                       =!empty($row["COLO"])?$row["COLO"]:"";
						$data[$i]["PIC"]                        =!empty($row["PIC"])?$row["PIC"]:"";
						$data[$i]["ADU"]                        =!empty($row["ADU"])?$row["ADU"]:"";
						$data[$i]["ARC"]                        =!empty($row["ARC"])?$row["ARC"]:"";
						$data[$i]["BDC"]                        =!empty($row["BDC"])?$row["BDC"]:"";
						$data[$i]["BDL"]                        =!empty($row["BDL"])?$row["BDL"]:"";
						$data[$i]["BDM"]                        =!empty($row["BDM"])?$row["BDM"]:"";
						$data[$i]["BDU"]                        =!empty($row["BDU"])?$row["BDU"]:"";
						$data[$i]["BLD"]                        =!empty($row["BLD"])?$row["BLD"]:"";
						$data[$i]["BLK"]                        =!empty($row["BLK"])?$row["BLK"]:"";
						$data[$i]["BRM"]                        =!empty($row["BRM"])?$row["BRM"]:"";
						$data[$i]["BUS"]                        =!empty($row["BUS"])?$row["BUS"]:"";
						$data[$i]["DNO"]                        =!empty($row["DNO"])?$row["DNO"]:"";
						$data[$i]["DRM"]                        =!empty($row["DRM"])?$row["DRM"]:"";
						$data[$i]["EFR"]                        =!empty($row["EFR"])?$row["EFR"]:"";
						$data[$i]["EL"]                         =!empty($row["EL"])?$row["EL"]:"";
						$data[$i]["ENT"]                        =!empty($row["ENT"])?$row["ENT"]:"";
						$data[$i]["F17"]                        =!empty($row["F17"])?$row["F17"]:"";
						$data[$i]["FAM"]                        =!empty($row["FAM"])?$row["FAM"]:"";
						$data[$i]["FBG"]                        =!empty($row["FBG"])?$row["FBG"]:"";
						$data[$i]["FBL"]                        =!empty($row["FBL"])?$row["FBL"]:"";
						$data[$i]["FBM"]                        =!empty($row["FBM"])?$row["FBM"]:"";
						$data[$i]["FBT"]                        =!empty($row["FBT"])?$row["FBT"]:"";
						$data[$i]["FBU"]                        =!empty($row["FBU"])?$row["FBU"]:"";
						$data[$i]["FP"]                         =!empty($row["FP"])?$row["FP"]:"";
						$data[$i]["FPL"]                        =!empty($row["FPL"])?$row["FPL"]:"";
						$data[$i]["FPM"]                        =!empty($row["FPM"])?$row["FPM"]:"";
						$data[$i]["FPU"]                        =!empty($row["FPU"])?$row["FPU"]:"";
						$data[$i]["GAR"]                        =!empty($row["GAR"])?$row["GAR"]:"";
						$data[$i]["HBG"]                        =!empty($row["HBG"])?$row["HBG"]:"";
						$data[$i]["HBL"]                        =!empty($row["HBL"])?$row["HBL"]:"";
						$data[$i]["HBM"]                        =!empty($row["HBM"])?$row["HBM"]:"";
						$data[$i]["HBT"]                        =!empty($row["HBT"])?$row["HBT"]:"";
						$data[$i]["HBU"]                        =!empty($row["HBU"])?$row["HBU"]:"";
						$data[$i]["HOD"]                        =!empty($row["HOD"])?$row["HOD"]:"";
						$data[$i]["JH"]                         =!empty($row["JH"])?$row["JH"]:"";
						$data[$i]["KES"]                        =!empty($row["KES"])?$row["KES"]:"";
						$data[$i]["KIT"]                        =!empty($row["KIT"])?$row["KIT"]:"";
						$data[$i]["LRM"]                        =!empty($row["LRM"])?$row["LRM"]:"";
						$data[$i]["LSD"]                        =!empty($row["LSD"])?$row["LSD"]:"";
						$data[$i]["LSZ"]                        =!empty($row["LSZ"])?$row["LSZ"]:"";
						$data[$i]["LT"]                         =!empty($row["LT"])?$row["LT"]:"";
						$data[$i]["MBD"]                        =!empty($row["MBD"])?$row["MBD"]:"";
						$data[$i]["MHM"]                        =!empty($row["MHM"])?$row["MHM"]:"";
						$data[$i]["MHN"]                        =!empty($row["MHN"])?$row["MHN"]:"";
						$data[$i]["MHS"]                        =!empty($row["MHS"])?$row["MHS"]:"";
						$data[$i]["MOR"]                        =!empty($row["MOR"])?$row["MOR"]:"";
						$data[$i]["NC"]                         =!empty($row["NC"])?$row["NC"]:"";
						$data[$i]["POC"]                        =!empty($row["POC"])?$row["POC"]:"";
						$data[$i]["POL"]                        =!empty($row["POL"])?$row["POL"]:"";
						$data[$i]["PRJ"]                        =!empty($row["PRJ"])?$row["PRJ"]:"";
						$data[$i]["PTO"]                        =!empty($row["PTO"])?$row["PTO"]:"";
						$data[$i]["TQBT"]                       =!empty($row["TQBT"])?$row["TQBT"]:"";
						$data[$i]["RRM"]                        =!empty($row["RRM"])?$row["RRM"]:"";
						$data[$i]["CMFE"]                       =!empty($row["CMFE"])?$row["CMFE"]:"";
						$data[$i]["SAP"]                        =!empty($row["SAP"])?$row["SAP"]:"";
						$data[$i]["SFF"]                        =!empty($row["SFF"])?$row["SFF"]:"";
						$data[$i]["SFS"]                        =!empty($row["SFS"])?$row["SFS"]:"";
						$data[$i]["SFU"]                        =!empty($row["SFU"])?$row["SFU"]:"";
						$data[$i]["SH"]                         =!empty($row["SH"])?$row["SH"]:"";
						$data[$i]["SML"]                        =!empty($row["SML"])?$row["SML"]:"";
						$data[$i]["SNR"]                        =!empty($row["SNR"])?$row["SNR"]:"";
						$data[$i]["STY"]                        =!empty($row["STY"])?$row["STY"]:"";
						$data[$i]["SWC"]                        =!empty($row["SWC"])?$row["SWC"]:"";
						$data[$i]["TBG"]                        =!empty($row["TBG"])?$row["TBG"]:"";
						$data[$i]["TBL"]                        =!empty($row["TBL"])?$row["TBL"]:"";
						$data[$i]["TBM"]                        =!empty($row["TBM"])?$row["TBM"]:"";
						$data[$i]["TBU"]                        =!empty($row["TBU"])?$row["TBU"]:"";
						$data[$i]["TX"]                         =!empty($row["TX"])?$row["TX"]:"";
						$data[$i]["TXY"]                        =!empty($row["TXY"])?$row["TXY"]:"";
						$data[$i]["UTR"]                        =!empty($row["UTR"])?$row["UTR"]:"";
						$data[$i]["WAC"]                        =!empty($row["WAC"])?$row["WAC"]:"";
						$data[$i]["WFG"]                        =!empty($row["WFG"])?$row["WFG"]:"";
						$data[$i]["WHT"]                        =!empty($row["WHT"])?$row["WHT"]:"";
						$data[$i]["APS"]                        =!empty($row["APS"])?$row["APS"]:"";
						$data[$i]["BDI"]                        =!empty($row["BDI"])?$row["BDI"]:"";
						$data[$i]["BSM"]                        =!empty($row["BSM"])?$row["BSM"]:"";
						$data[$i]["ENS"]                        =!empty($row["ENS"])?$row["ENS"]:"";
						$data[$i]["EXT"]                        =!empty($row["EXT"])?$row["EXT"]:"";
						$data[$i]["FEA"]                        =!empty($row["FEA"])?$row["FEA"]:"";
						$data[$i]["FLS"]                        =!empty($row["FLS"])?$row["FLS"]:"";
						$data[$i]["FND"]                        =!empty($row["FND"])?$row["FND"]:"";
						$data[$i]["GR"]                         =!empty($row["GR"])?$row["GR"]:"";
						$data[$i]["HTC"]                        =!empty($row["HTC"])?$row["HTC"]:"";
						$data[$i]["LDE"]                        =!empty($row["LDE"])?$row["LDE"]:"";
						$data[$i]["LTV"]                        =!empty($row["LTV"])?$row["LTV"]:"";
						$data[$i]["POS"]                        =!empty($row["POS"])?$row["POS"]:"";
						$data[$i]["RF"]                         =!empty($row["RF"])?$row["RF"]:"";
						$data[$i]["SIT"]                        =!empty($row["SIT"])?$row["SIT"]:"";
						$data[$i]["SWR"]                        =!empty($row["SWR"])?$row["SWR"]:"";
						$data[$i]["TRM"]                        =!empty($row["TRM"])?$row["TRM"]:"";
						$data[$i]["VEW"]                        =!empty($row["VEW"])?$row["VEW"]:"";
						$data[$i]["WAS"]                        =!empty($row["WAS"])?$row["WAS"]:"";
						$data[$i]["WFT"]                        =!empty($row["WFT"])?$row["WFT"]:"";
						$data[$i]["BUSR"]                       =!empty($row["BUSR"])?$row["BUSR"]:"";
						$data[$i]["ECRT"]                       =!empty($row["ECRT"])?$row["ECRT"]:"";
						$data[$i]["ZJD"]                        =!empty($row["ZJD"])?$row["ZJD"]:"";
						$data[$i]["ZNC"]                        =!empty($row["ZNC"])?$row["ZNC"]:"";
						$data[$i]["ProhibitBLOG"]               =!empty($row["ProhibitBLOG"])?$row["ProhibitBLOG"]:"";
						$data[$i]["AllowAVM"]                   =!empty($row["AllowAVM"])?$row["AllowAVM"]:"";
						$data[$i]["PARQ"]                       =!empty($row["PARQ"])?$row["PARQ"]:"";
						$data[$i]["BREO"]                       =!empty($row["BREO"])?$row["BREO"]:"";
						$data[$i]["BuiltGreenRating"]           =!empty($row["BuiltGreenRating"])?$row["BuiltGreenRating"]:"";
						$data[$i]["EPSEnergy"]                  =!empty($row["EPSEnergy"])?$row["EPSEnergy"]:"";
						$data[$i]["ROFR"]                       =!empty($row["ROFR"])?$row["ROFR"]:"";
						$data[$i]["HERSIndex"]                  =!empty($row["HERSIndex"])?$row["HERSIndex"]:"";
						$data[$i]["LEEDRating"]                 =!empty($row["LEEDRating"])?$row["LEEDRating"]:"";
						$data[$i]["NewConstruction"]            =!empty($row["NewConstruction"])?$row["NewConstruction"]:"";
						$data[$i]["NWESHRating"]                =!empty($row["NWESHRating"])?$row["NWESHRating"]:"";
						$data[$i]["ConstructionMethods"]        =!empty($row["ConstructionMethods"])?$row["ConstructionMethods"]:"";
						$data[$i]["EMP"]                        =!empty($row["EMP"])?$row["EMP"]:"";
						$data[$i]["EQU"]                        =!empty($row["EQU"])?$row["EQU"]:"";
						$data[$i]["EQV"]                        =!empty($row["EQV"])?$row["EQV"]:"";
						$data[$i]["FRN"]                        =!empty($row["FRN"])?$row["FRN"]:"";
						$data[$i]["GRS"]                        =!empty($row["GRS"])?$row["GRS"]:"";
						$data[$i]["GW"]                         =!empty($row["GW"])?$row["GW"]:"";
						$data[$i]["HRS"]                        =!empty($row["HRS"])?$row["HRS"]:"";
						$data[$i]["INV"]                        =!empty($row["INV"])?$row["INV"]:"";
						$data[$i]["LNM"]                        =!empty($row["LNM"])?$row["LNM"]:"";
						$data[$i]["LSI"]                        =!empty($row["LSI"])?$row["LSI"]:"";
						$data[$i]["NA"]                         =!empty($row["NA"])?$row["NA"]:"";
						$data[$i]["NP"]                         =!empty($row["NP"])?$row["NP"]:"";
						$data[$i]["PKC"]                        =!empty($row["PKC"])?$row["PKC"]:"";
						$data[$i]["PKU"]                        =!empty($row["PKU"])?$row["PKU"]:"";
						$data[$i]["RES"]                        =!empty($row["RES"])?$row["RES"]:"";
						$data[$i]["RNT"]                        =!empty($row["RNT"])?$row["RNT"]:"";
						$data[$i]["SIN"]                        =!empty($row["SIN"])?$row["SIN"]:"";
						$data[$i]["TEXP"]                       =!empty($row["TEXP"])?$row["TEXP"]:"";
						$data[$i]["TOB"]                        =!empty($row["TOB"])?$row["TOB"]:"";
						$data[$i]["YRE"]                        =!empty($row["YRE"])?$row["YRE"]:"";
						$data[$i]["YRS"]                        =!empty($row["YRS"])?$row["YRS"]:"";
						$data[$i]["LES"]                        =!empty($row["LES"])?$row["LES"]:"";
						$data[$i]["LIC"]                        =!empty($row["LIC"])?$row["LIC"]:"";
						$data[$i]["LOC"]                        =!empty($row["LOC"])?$row["LOC"]:"";
						$data[$i]["MTB"]                        =!empty($row["MTB"])?$row["MTB"]:"";
						$data[$i]["RP"]                         =!empty($row["RP"])?$row["RP"]:"";
						$data[$i]["LSZS"]                       =!empty($row["LSZS"])?$row["LSZS"]:"";
						$data[$i]["AFH"]                        =!empty($row["AFH"])?$row["AFH"]:"";
						$data[$i]["ASCC"]                        =!empty($row["ASC"])?$row["ASC"]:"";
						$data[$i]["COO"]                        =!empty($row["COO"])?$row["COO"]:"";
						$data[$i]["MGR"]                        =!empty($row["MGR"])?$row["MGR"]:"";
						$data[$i]["NAS"]                        =!empty($row["NAS"])?$row["NAS"]:"";
						$data[$i]["NOC"]                        =!empty($row["NOC"])?$row["NOC"]:"";
						$data[$i]["NOS"]                        =!empty($row["NOS"])?$row["NOS"]:"";
						$data[$i]["NOU"]                        =!empty($row["NOU"])?$row["NOU"]:"";
						$data[$i]["OOC"]                        =!empty($row["OOC"])?$row["OOC"]:"";
						$data[$i]["PKS"]                        =!empty($row["PKS"])?$row["PKS"]:"";
						$data[$i]["REM"]                        =!empty($row["REM"])?$row["REM"]:"";
						$data[$i]["SAA"]                        =!empty($row["SAA"])?$row["SAA"]:"";
						$data[$i]["SPA"]                        =!empty($row["SPA"])?$row["SPA"]:"";
						$data[$i]["STG"]                        =!empty($row["STG"])?$row["STG"]:"";
						$data[$i]["STL"]                        =!empty($row["STL"])?$row["STL"]:"";
						$data[$i]["TOF"]                        =!empty($row["TOF"])?$row["TOF"]:"";
						$data[$i]["UFN"]                        =!empty($row["UFN"])?$row["UFN"]:"";
						$data[$i]["WDW"]                        =!empty($row["WDW"])?$row["WDW"]:"";
						$data[$i]["APH"]                        =!empty($row["APH"])?$row["APH"]:"";
						$data[$i]["CMN"]                        =!empty($row["CMN"])?$row["CMN"]:"";
						$data[$i]["CTD"]                        =!empty($row["CTD"])?$row["CTD"]:"";
						$data[$i]["HOI"]                        =!empty($row["HOI"])?$row["HOI"]:"";
						$data[$i]["PKG"]                        =!empty($row["PKG"])?$row["PKG"]:"";
						$data[$i]["UNF"]                        =!empty($row["UNF"])?$row["UNF"]:"";
						$data[$i]["STRS"]                       =!empty($row["STRS"])?$row["STRS"]:"";
						$data[$i]["FUR"]                        =!empty($row["FUR"])?$row["FUR"]:"";
						$data[$i]["MLT"]                        =!empty($row["MLT"])?$row["MLT"]:"";
						$data[$i]["STO"]                        =!empty($row["STO"])?$row["STO"]:"";
						$data[$i]["AFR"]                        =!empty($row["AFR"])?$row["AFR"]:"";
						$data[$i]["APP"]                        =!empty($row["APP"])?$row["APP"]:"";
						$data[$i]["MIF"]                        =!empty($row["MIF"])?$row["MIF"]:"";
						$data[$i]["TMC"]                        =!empty($row["TMC"])?$row["TMC"]:"";
						$data[$i]["TYP"]                        =!empty($row["TYP"])?$row["TYP"]:"";
						$data[$i]["UTL"]                        =!empty($row["UTL"])?$row["UTL"]:"";
						$data[$i]["ELE"]                        =!empty($row["ELE"])?$row["ELE"]:"";
						$data[$i]["ESM"]                        =!empty($row["ESM"])?$row["ESM"]:"";
						$data[$i]["GAS"]                        =!empty($row["GAS"])?$row["GAS"]:"";
						$data[$i]["LVL"]                        =!empty($row["LVL"])?$row["LVL"]:"";
						$data[$i]["QTR"]                        =!empty($row["QTR"])?$row["QTR"]:"";
						$data[$i]["RD"]                         =!empty($row["RD"])?$row["RD"]:"";
						$data[$i]["SDA"]                        =!empty($row["SDA"])?$row["SDA"]:"";
						$data[$i]["SEC"]                        =!empty($row["SEC"])?$row["SEC"]:"";
						$data[$i]["SEP"]                        =!empty($row["SEP"])?$row["SEP"]:"";
						$data[$i]["SFA"]                        =!empty($row["SFA"])?$row["SFA"]:"";
						$data[$i]["SLP"]                        =!empty($row["SLP"])?$row["SLP"]:"";
						$data[$i]["SST"]                        =!empty($row["SST"])?$row["SST"]:"";
						$data[$i]["SUR"]                        =!empty($row["SUR"])?$row["SUR"]:"";
						$data[$i]["TER"]                        =!empty($row["TER"])?$row["TER"]:"";
						$data[$i]["WRJ"]                        =!empty($row["WRJ"])?$row["WRJ"]:"";
						$data[$i]["ZNR"]                        =!empty($row["ZNR"])?$row["ZNR"]:"";
						$data[$i]["ATF"]                        =!empty($row["ATF"])?$row["ATF"]:"";
						$data[$i]["DOC"]                        =!empty($row["DOC"])?$row["DOC"]:"";
						$data[$i]["FTR"]                        =!empty($row["FTR"])?$row["FTR"]:"";
						$data[$i]["GZC"]                        =!empty($row["GZC"])?$row["GZC"]:"";
						$data[$i]["IMP"]                        =!empty($row["IMP"])?$row["IMP"]:"";
						$data[$i]["RDI"]                        =!empty($row["RDI"])?$row["RDI"]:"";
						$data[$i]["RS2"]                        =!empty($row["RS2"])?$row["RS2"]:"";
						$data[$i]["TPO"]                        =!empty($row["TPO"])?$row["TPO"]:"";
						$data[$i]["WTR"]                        =!empty($row["WTR"])?$row["WTR"]:"";
						$data[$i]["AUCTION"]                    =!empty($row["AUCTION"])?$row["AUCTION"]:"";
						$data[$i]["LotSizeSource"]   			=!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
						$data[$i]["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
						$data[$i]["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";
						$data[$i]['modified_date']				= date('Y-m-d h:i:s');
						$data[$i]['status']						='1';
						//pr($data);
						$fields=array('ID','LN');
						$match=array('LN'=>!empty($row['LN'])?$row['LN']:'');
						$res=$this->mls_model->select_records3('',$match,'','=');
						//echo $this->db->last_query();
						//pr($data);
						//exit;
						if(empty($res))
						{
							$data[$i]['created_date']		= date('Y-m-d h:i:s');
							if(count($data) >= 100)
							{
								$this->mls_model->insert_record3($data);
								$i = 0;
								unset($data);
							}
							$i++;
							//$id=$this->mls_model->insert_record3($data);
						}
						else
						{
							$cdata[$j] = $data[$i];
							unset($data[$i]);
							if(count($cdata) >= 100)
							{
								$this->mls_model->update_record3($cdata);
								//pr($cdata); exit;
								$j = 0;
								unset($cdata);
							}
							$j++;
							//$id=$this->mls_model->update_record3($data);
						}
					}
					if(!empty($data) && count($data) > 0)
					{
						$this->mls_model->insert_record3($data);
						unset($data);	
					}
					if(!empty($cdata) && count($cdata) > 0)
					{
						$this->mls_model->update_record3($cdata);
						//pr($cdata); exit;
						unset($cdata);	
					}
				}
			}
		}
		echo 'done';
		//redirect('superadmin/mls/add_record');
    } 
    /*
    @Description: Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
   
    public function update_sms_url()
    {
    	error_reporting(0);
	    ini_set('max_execution_time', 5000);
    	$this->load->library('Services/Services_Twilio');
    	$account_detail=array(
    	'AC32c9e2c9127c55f965b34e25b2a7e77b_1bdb231ae2529705edc2c49910181b3e',
    	'ACcd1cc70b37b2646a8ecc017698cf0b59_c3ea557bf9752f5cd2938046340769f4',
    	'ACf980d539ca872735918cf2210c47f7b7_87386cb328d90637b04c46b6f08de824',
    	'ACb5cf1d0386d25481e84fef84d562b348_360eabd512ca76d91d0b5450075d8228',
    	'AC51c7ad9848ad96fdf441673a53059af2_4a833ee9ecdce04dbbd82c2b8a52796a',
    	'ACf5607c812a5c115a45c6851b0c373548_dccca80eb6ca848ec2f47777f4370bc6',
    	'ACd6020710f40efc6e3c21e83bf0edb34b_698f0d099861fc32661eb85663858ed5',
    	'ACb10427f849137c19c060213c5e8b2256_755e8e15c2bb6b1a852db69ceda29332',
    	'AC9fc25d9d043c6be850189498f7bef772_8aca134d650a7d50ff87b8e5d0767f6e',
    	'AC8a95e011e3f4a0fe1d2cc3516130e7e9_eda3986fa996c93733788aad020bf4cf',
    	'AC5c6e340ed8b07bc67bf2badda4d0994a_cd05eb1d67184e9142e7b67298ef0bc8',
    	'AC58810e77340750303d23cc454264e028_1dee70f209aeca139bc9aab7d9cebc4a',
    	'AC4e2f178e74e73949d04e6e7c0e59aca4_bc3248d884e96ca65b555efb2b9b74a8',
    	'AC430890aabcfe03c71820d2b3d996bf7c_182692dedec6b31aac24d349ae2687c1',
    	'AC30ee542c6891818ef00f048ed8ba40cf_fed9bcb06f578a48927e51bc0bbb3e26',
    	'AC2cc4e826be390258b36eeab18c29f689_a880e3e01ceecdb2e43be023c62c5909',
    	'AC2170d2220d6db8215c8a35f51d4542f9_d0024b6ba131cbd4f39f41e499b1a4f1',
    	'ACef89a18914d10999fbe4370ef44e3821_e15aee5f552b9095a21bcad6b988f50b',
    	'ACbb918008033e0797f90a9ad042ed7eee_8158ba3b2b472e15bb9c9baae33f5562',
    	'AC539713d5836a1a70cdb9a09ecb09a109_df8142796748a59dcae5516d70bcb393',
    	'ACec1d6faea6aa80d0b98f0b3268c8915a_41bd754bc567062b3953ebeb88892109',
    	'ACbdc51d336446797bceb1a894cbf0f6da_06cc8495cd54ca51575f969cbe7dcda2',
    	'AC5739f6a260c19e9526a219d8e6d8506f_8c715f5b6cac49957cc2ddf6b1d2c103',
    	'ACd909c1c3ee9b0a9837138a9f554edda4_6261c5b440034c37a2d08e307edb902a',
    	'ACeabd646c72c996cb0216ab7fc5a99d7b_d1dd0abd4a0f5918b9862c7227104f6e',
    	);
     //pr($account_detail);exit;
	
	for($i=0;$i<count($account_detail);$i++)
	{
		$account_det=explode('_',$account_detail[$i]);
    	$AccountSid			= $account_det[0];
    	$AccountAuthToken	= $account_det[1];
    	if(!empty($AccountSid) && !empty($AccountAuthToken))
		{
			$client = new Services_Twilio($AccountSid, $AccountAuthToken);
			foreach ($client->account->incoming_phone_numbers as $number) {
			    $phone_no	=$number->phone_number;
			    $phone_sid=$number->sid;
			}
			//echo $phone_sid.'<br>';
			try 
			{
				$url=base_url()."contact_sms_response";
				//$url="http://mylivewiresolution.com/contact_sms_response";

				$number = $client->account->incoming_phone_numbers->get($phone_sid);
				$number->update(array(
				//"VoiceUrl" => "http://demo.twilio.com/docs/voice.xml",
				"SmsUrl" => $url
				));
				
			} catch (Services_Twilio_RestException $e) {
				//echo 'error';
				//echo $e->getMessage();
			}
		}
	}
	echo 'Done';
    }       
    
    
    public function last_login()
    {
        $db_name = $this->config->item('parent_db_name');
        $table = $db_name.'.user_login_trans';
        $result = $this->contacts_model->getmultiple_tables_records($table,'','','','','','','','','id','desc');
        pr($result);

        $db_name = $this->config->item('parent_db_name');
        $table = $db_name.'.cron_test';
        $result = $this->contacts_model->getmultiple_tables_records($table,'','','','','','','','','id','desc');
        pr($result);
    }

    public function cron_log()
    {
        $db_name = $this->config->item('parent_db_name');
        $table = $db_name.'.cron_test';
        $result = $this->contacts_model->getmultiple_tables_records($table,'','','','','','','','','id','desc');
        pr($result);
    }
    
    /*
        @Description: Function for Send SMS
        @Author     : Sanjay Chabhadiya
        @Input      : 
        @Output     : 
        @Date       : 11-05-2015
    */
    
    public function AutoResponderSMSsend($db_name,$contact_data,$agent_id='')
    {
        $this->load->library('Twilio');
        $table = $db_name.".login_master as lm";
        $fields = array('lm.id,upt.phone_no,lm.phone,lm.user_type');
        $join_tables = array($db_name.'.user_master as um' => 'um.id = lm.user_id',
                             '(SELECT * FROM '.$db_name.'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = lm.user_id',
                            );
        $group_by = 'lm.id';
        if(!empty($agent_id))
            $match = array('lm.user_id'=>$agent_id);
        else
            $match = array('lm.user_type'=>'2');
        $assign_user_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by);

        if(!empty($assign_user_data))
        {
            if(!empty($assign_user_data[0]['user_type']) && ($assign_user_data[0]['user_type'] == 2 || $assign_user_data[0]['user_type'] == 5))
                $phone_no = $assign_user_data[0]['phone'];
            else
                $phone_no = $assign_user_data[0]['phone_no'];
        }
        $this->load->model('sms_texts_model');
        $fields = array('sms_message');
        if(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Buyer/Seller')
            $match = array('sms_event'=>'3');
        elseif(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Buyer')
            $match = array('sms_event'=>'4');
        elseif(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Seller')
            $match = array('sms_event'=>'5');
        $template_data = $this->sms_texts_model->select_records($fields,$match,'','=','','','','','',$db_name);
        $emaildata = array(
                            'Date'=>date('Y-m-d'),
                            'Day'=>date('l'),
                            'Month'=>date('F'),
                            'Year'=>date('Y'),
                            'Day Of Week'=>date( "w", time()),
                            'Contact First Name'=>!empty($contact_data['first_name'])?$contact_data['first_name']:'',
                            'Contact Spouse/Partner First Name'=>!empty($contact_data['spousefirst_name'])?$contact_data['spousefirst_name']:'',
                            'Contact Last Name'=>!empty($contact_data['last_name'])?$contact_data['last_name']:'',
                            'Contact Spouse/Partner Last Name'=>!empty($contact_data['spouselast_name'])?$contact_data['spouselast_name']:'',
                            'Contact Company Name'=>!empty($contact_data['company_name'])?$contact_data['company_name']:''
                           );
        $content = !empty($template_data[0]['sms_message'])?$template_data[0]['sms_message']:'';
        $pattern = "{(%s)}";
        $map = array();
        if($emaildata != '' && count($emaildata) > 0)
        {
            foreach($emaildata as $var => $value)
            {
                    $map[sprintf($pattern, $var)] = $value;
            }
            $output = strtr($content, $map);
        }
        $message = !empty($output)?$output:$this->lang->line('new_lead_registered');
        if(!empty($assign_user_data[0]['id']) && !empty($phone_no))
        {
            //'+919033921029'
            $this->twilio->set_admin_id($assign_user_data[0]['id'],$db_name);
            $response = $this->twilio->sms($this->config->item('from_sms'),$phone_no,$this->lang->line('new_lead_assign_agent_msg'));
        }

        if(!empty($contact_data['phone_no']) && !empty($assign_user_data[0]['id']))
        {
            //'+919033921029'
            $this->twilio->set_admin_id($assign_user_data[0]['id'],$db_name);   
            $response = $this->twilio->sms($this->config->item('from_sms'),$contact_data['phone_no'],$message);
        }
    }
    
    /*
        @Description: Function for send email once property status changed whom added into favorite, saved searches
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 21-05-2015
    */
    //// Old Loop (Property -> Domain -> Saved Searches and Favorites)
    function property_status_change_cron_old()
    {
        set_time_limit(0);
        $parent_db=$this->config->item('parent_db_name');
        $this->load->model('property_list_masters_model');
        $table = $parent_db.'.cron_data';
        $fields = array('property_status_cron_date');
        $match = array('id'=>1);
        $cdata = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
        
        $currdate = date('Y-m-d H:i:s');
        $cdate = $currdate;
        if(!empty($cdata)) {
            if(!empty($cdata[0]['property_status_cron_date']) && $cdata[0]['property_status_cron_date'] != '0000-00-00 00:00:00')
                $cdate = date('Y-m-d H:i:s',strtotime($cdata[0]['property_status_cron_date']));
        }
        $table = $parent_db.'.mls_property_list_master';
        $fields = array('ID,LN,ST,is_status_change,old_status,display_price,full_address,PTYP,BR,BTH,mls_id');
        $where = 'modified_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND modified_date < "'.$cdate.'"';
        $match = array('is_status_change'=>1);
        $property_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);
        
        //// If property data found (i.e if status change on any property)
        if(!empty($property_data))
        {
            foreach($property_data as $property_row)
            {
                //A -Active, CT - Contingent, PB - Pending Backup Offers Requested, PF - Pending Feasibility, PI - Pending Inspection, PS - Pending Short Sale, P - Pending, S - Sold
                if($property_row['ST'] == 'A')
                    $status = 'Active';
                else if($property_row['ST'] == 'P' || $property_row['ST'] == 'PB' || $property_row['ST'] == 'PF' || $property_row['ST'] == 'PI' || $property_row['ST'] == 'PS')
                    $status = 'Pending';
                else if($property_row['ST'] == 'S')
                    $status = 'Sold';
                else if($property_row['ST'] == 'CT')
                    $status = 'Contingent';
                else if($property_row['ST'] == 'E')
                    $status = 'Expired';
                
                //// Get all child database admin
                $table=$parent_db.'.child_admin_website as caw';
                $fields = array('caw.id,caw.domain,caw.mls_id,caw.first_name,caw.last_name,caw.email_id,lm.db_name,lm.admin_name,lm.email_id as admin_email,lm.address,lm.phone,lm.db_name,lm.timezone,lm.brokerage_pic');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = caw.lw_admin_id');
                $domain_list=$this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','caw.id','asc');
                $admin_emailid = '';
                if(!empty($domain_list))
                {
                    foreach($domain_list as $dbrow)
                    {
                        if($dbrow['mls_id'] == $property_row['mls_id'])
                        {
                            $email_data = array();
                            $email_data['status'] = $status;
                            $email_data['property_price'] = $property_row['display_price'];
                            $email_data['admin_emailid'] = $dbrow['admin_email'];
                            $email_data['admin_name'] = $dbrow['admin_name'];
                            $db_name = $dbrow['db_name'];
                            $email_data['brokerage_pic'] = $dbrow['brokerage_pic'];
                            $admin_id = $dbrow['id'];
                            $email_data['domain'] =$dbrow['domain'];
                            $email_data['admin_address'] = $dbrow['address'];
                            $email_data['admin_phone'] = $dbrow['phone'];

                            //// Auto responder ////
                            $autores_res = array();
                            $fields = array('template_name,template_subject,email_message,email_event');
                            $match = array('email_event'=>'4');
                            $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

                            if(!empty($email_data['admin_emailid']))
                                $from = $email_data['admin_emailid'];
                            else
                                $from = $this->config->item('admin_email');

                            ///////// Favorites ///////
                            
                            // ,lu.email_for_favorite add by sanjay c
                            // $db_name.'.lead_users as lu'=>'lu.id = jrb.uid', add by sanjay c
                            
                            $favorites_data = array();
                            $table=$db_name.'.joomla_rpl_bookmarks as jrb';
                            $fields = array('jrb.pid,jrb.propery_name as property_name,jrb.mlsid,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid,lu.email_for_favorite');
                            $match = array('jrb.mlsid'=>$property_row['LN'],'jrb.domain'=>$dbrow['domain'],'lu.email_for_favorite'=>1);
                            $join_tables = array(
                                $db_name.'.contact_master as cm'=>'cm.id = jrb.lw_admin_id',
                                $db_name.'.lead_users as lu'=>'lu.id = jrb.uid',
                                '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                                //$db_name.'.contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                                $db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                                $db_name.'.user_master as um' => 'um.id = uct.user_id',
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                                '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                            );
                            $favorites_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','jrb.id','desc');
                            if(!empty($favorites_data))
                            {
                                if(!empty($autores_res[0]['template_subject'])) {
                                    $subject = $autores_res[0]['template_subject'];
                                    $subject .= !empty($email_data['domain'])?' - '.$email_data['domain']:'';
                                }
                                else {
                                    $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                                    $subject = "Your Favorite property status has been changed - ".$d;
                                }

                                foreach($favorites_data as $fav_row)
                                {
                                    $email_data['name'] = !empty($fav_row['user_name'])?ucwords($fav_row['user_name']):'';
                                    if(!empty($fav_row['email_id'])) // Assigned agent email id
                                    {
                                        $from = $fav_row['email_id'];
                                        $email_data['admin_name'] = $fav_row['admin_name'];
                                        $email_data['admin_phone'] = $fav_row['phone'];
                                        $email_data['brokerage_pic'] = $fav_row['brokerage_pic'];
                                        $address .= !empty($fav_row['address_line1'])?$fav_row['address_line1']:'';
                                        $address .= !empty($fav_row['address_line2'])?', '.$fav_row['address_line2']:'';
                                        $address .= !empty($fav_row['city'])?', '.$fav_row['city']:'';
                                        $address .= !empty($fav_row['state'])?', '.$fav_row['state']:'';
                                        $address .= !empty($fav_row['zip_code'])?' '.$fav_row['zip_code']:'';
                                        $email_data['admin_address'] = $address;
                                    }

                                    if(!empty($fav_row['email_address'])) // Contact email id
                                        $to = $fav_row['email_address'];

                                    $edata['from_name'] = "Property Status Changed";
                                    $edata['from_email'] = $from;

                                    if(!empty($autores_res[0]['email_message']))
                                    {
                                        $emaildata = array(
                                            'Date'=>date('Y-m-d'),
                                            'Day'=>date('l'),
                                            'Month'=>date('F'),
                                            'Year'=>date('Y'),
                                            'Day Of Week'=>date("w",time()),
                                            'Agent Name'=>!empty($email_data['admin_name'])?$email_data['admin_name']:'',
                                            'Contact First Name'=> !empty($fav_row['first_name'])?ucwords($fav_row['first_name']):'',
                                            'Contact Spouse/Partner First Name'=>!empty($fav_row['spousefirst_name'])?ucwords($fav_row['spousefirst_name']):'',
                                            'Contact Last Name'=> !empty($fav_row['last_name'])?ucwords($fav_row['last_name']):'',
                                            'Contact Spouse/Partner Last Name'=> !empty($fav_row['spouselast_name'])?ucwords($fav_row['spouselast_name']):'',
                                            'Contact Company Name'=> !empty($fav_row['company_name'])?ucwords($fav_row['company_name']):''
                                        );

                                        $pattern = "{(%s)}";
                                        $map = array();

                                        if($emaildata != '' && count($emaildata) > 0)
                                        {
                                            foreach($emaildata as $var => $value)
                                            {
                                                $map[sprintf($pattern, $var)] = $value;
                                            }
                                            $output = strtr($autores_res[0]['email_message'], $map);
                                            $email_data['msg_body'] = $output;
                                        }
                                    }

                                    $email_data['tabname'] = 'F';
                                    $email_data['property_name'] =  $fav_row['property_name'];
                                    $message = $this->load->view('ws/property_status_email', $email_data, TRUE);

                                    //// Mailgun email
                                    if(!empty($to))
                                        $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                                    echo "Favorites: ".$to."<br />";
                                    pr($response);
                                }
                            } else {
                                echo "Favorites not match."."<br />";
                            }
                            ///////// End Favorites ///////

                            ///////// Saved Searches ///////
                            $saved_searches_data = array();
                            $table=$db_name.'.joomla_rpl_savesearch as ss';
                            
                            // ,lu.email_for_status_change add by sanjay c
                            // $db_name.'.lead_users as lu'=>'lu.id = ss.uid', add by sanjay c
                            $fields = array('ss.id,ss.property_type,ss.min_price,ss.max_price,ss.min_area,ss.max_area,ss.bedroom,ss.bathroom,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid,lu.email_for_status_change');
                            $match = array('lu.email_for_status_change'=>1,'ss.domain'=>$dbrow['domain']);
                            $join_tables = array(
                                $db_name.'.contact_master as cm'=>'cm.id = ss.lw_admin_id',
                                $db_name.'.lead_users as lu'=>'lu.id = ss.uid',
                                '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                                $db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                                $db_name.'.user_master as um' => 'um.id = uct.user_id',
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                                '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                            );
                            $saved_searches_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','ss.id','desc','','');
                            if(!empty($saved_searches_data))
                            {
                                if(!empty($autores_res[0]['template_subject'])) {
                                    $subject = $autores_res[0]['template_subject'];
                                    $subject .= !empty($email_data['domain'])?' - '.$email_data['domain']:'';
                                }
                                else {
                                    $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                                    $subject = "Your Saved Searches preference property status has been changed - ".$d;
                                }
                                foreach($saved_searches_data as $ss_row)
                                {
                                    //// Find property that matches with users Saved searches criteria
                                    $table = $parent_db.'.mls_property_list_master';
                                    $fields = array('ID,LN,PTYP,LAG,ST,UD,CIT,ASF,full_address,display_price,BR,BTH,TSP,ASF,YBT,is_status_change,old_status');
                                    $where = '';
                                    $match = array('mls_id'=>$dbrow['mls_id'],'ID'=>$property_row['ID']);
                                    $group_by = 'ID';
                                    if (!empty($ss_row['search_criteria']))
                                    {
                                        if(!empty($ss_row['search_category']) && $ss_row['search_category'] != 'Keyword')
                                        {
                                            if($ss_row['search_category'] == 'County')
                                                $where .= '(COU LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Community/Neighborhood')
                                                $where .= '(DSR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Property Address')
                                                $where .= '(full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Zip Code')
                                                $where .= '(ZIP LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Street Name')
                                                $where .= '(STR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';// OR SSUF LIKE "%' . $searchtext . '%") AND ';
                                        } else
                                            $where .= '(CIT LIKE "%' . $ss_row['search_criteria'] . '%" OR DSR LIKE "%' . $ss_row['search_criteria'] . '%" OR SD LIKE "%' . $ss_row['search_criteria'] . '%" OR mls_id LIKE "%' . $ss_row['search_criteria'] . '%" OR ZIP LIKE "%' . $ss_row['search_criteria'] . '%" OR full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                    }
                                    if (!empty($ss_row['min_price']))
                                        $where .= ' display_price >= "' . str_replace(',', '', $ss_row['min_price']) . '" AND ';
                                    if (!empty($ss_row['max_price']))
                                        $where .= ' display_price <= "' . str_replace(',', '', $ss_row['max_price']) . '" AND ';
                                    if (!empty($ss_row['bedroom']))
                                        $where .= ' BR >= "' . $ss_row['bedroom'] . '" AND ';
                                    if (!empty($ss_row['bathroom']))
                                        $where .= ' BTH >= "' . $ss_row['bathroom'] . '" AND ';

                                    if (!empty($ss_row['property_type']))
                                    {
                                        $where .= ' PTYP = "' . $ss_row['property_type'] . '" AND ';
                                    }

                                    if (!empty($ss_row['city']))
                                    {
                                        $city = explode('{^}',$ss_row['city']);
                                        if(is_array($city))
                                        {
                                            $where .= '(';
                                            foreach ($city as $ctrow)
                                            {
                                                $where .= ' CIT = "' . $ctrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }
                                    if (!empty($ss_row['parking_type']))
                                        $where .= ' GR = "' . $ss_row['parking_type'] . '" AND ';
                                    if (!empty($_REQUEST['garage_spaces']))
                                        $where .= ' GSP >= "' . $ss_row['garage_spaces'] . '" AND ';

                                    if (!empty($ss_row['year_built']))
                                        $where .= ' YBT = "' . $ss_row['year_built'] . '" AND ';
                                    if (!empty($ss_row['architecture']))
                                        $where .= ' ARC = "' . $ss_row['architecture'] . '" AND ';
                                    if (!empty($ss_row['school_district']))
                                        $where .= ' SD = "' . $ss_row['school_district'] . '" AND ';
                                    if (!empty($ss_row['fireplaces']))
                                        $where .= ' FP >= "' . $ss_row['fireplaces'] . '" AND ';

                                    if (!empty($ss_row['min_lotsize']))
                                        $where .= ' LSZS >= "' . $ss_row['min_lotsize'] . '" AND ';
                                    if (!empty($ss_row['property_status']))
                                    {
                                        if ($ss_row['property_status'] == 'Active')
                                            $where .= 'ST = "A" AND ';
                                        elseif ($ss_row['property_status'] == 'Pending')
                                            $where .= '(ST = "PB" OR ST = "PF" OR ST = "PI" OR ST = "PS" OR ST = "P" OR ST = "C") AND ';
                                        elseif ($ss_row['property_status'] == 'Sold')
                                            $where .= 'ST = "S" AND ';
                                        // elseif ($_REQUEST['property_status'] == 'Contingent')
                                        //     $where .= 'ST = "C" AND ';
                                    }

                                    if (!empty($ss_row['waterfront']))
                                    {
                                        $watefront = explode('{^}',$ss_row['waterfront']);
                                        if(is_array($watefront))
                                        {
                                            $where .= '(';
                                            foreach ($watefront as $wfrow)
                                            {
                                                $where .= ' WFT = "' . $wfrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }
                                    if (!empty($ss_row['s_views']))
                                    {
                                        $pview = explode('{^}',$ss_row['s_views']);
                                        if(is_array($pview))
                                        {
                                            $where .= '(';
                                            foreach ($pview as $pvrow)
                                            {
                                                $where .= ' VEW = "' . $pvrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }

                                    /*if (!empty($ss_row['waterfront']))
                                        $where .= ' WFT = "' . $ss_row['waterfront'] . '" AND ';
                                    if (!empty($ss_row['s_views']))
                                        $where .= ' VEW = "' . $ss_row['s_views'] . '" AND ';*/
                                    if(!empty($ss_row['new_construction']))
                                      $where .= ' NewConstruction = "'.$ss_row['new_construction'].'" AND ';
                                    if (isset($ss_row['short_sale']) && $ss_row['short_sale'] == 'Y')
                                        $where .= ' PARQ = "C" AND ';

                                    if (!empty($ss_row['bank_owned']))
                                        $where .= ' BREO = "' . $ss_row['bank_owned'] . '" AND ';

                                    if(!empty($ss_row['mls_id']))
                                        $where .= ' LN = "'.$ss_row['mls_id'].'" AND ';
                                    if(!empty($ss_row['CDOM']))
                                        $where .= ' CDOM <= "'.$ss_row['CDOM'].'" AND ';

                                    $where = rtrim($where, ' AND');
                                    $single_property = $this->property_list_masters_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=','', '', '', '', $group_by, $where);
                                    if(!empty($single_property))
                                    {
                                        $email_data['name'] = !empty($ss_row['user_name'])?ucwords($ss_row['user_name']):'';
                                        if(!empty($ss_row['email_id'])) // Assigned agent email id
                                        {
                                            $from = $ss_row['email_id'];
                                            $email_data['admin_name'] = $ss_row['admin_name'];
                                            $email_data['admin_phone'] = $ss_row['phone'];
                                            $email_data['brokerage_pic'] = $ss_row['brokerage_pic'];
                                            $address .= !empty($ss_row['address_line1'])?$ss_row['address_line1']:'';
                                            $address .= !empty($ss_row['address_line2'])?', '.$ss_row['address_line2']:'';
                                            $address .= !empty($ss_row['city'])?', '.$ss_row['city']:'';
                                            $address .= !empty($ss_row['state'])?', '.$ss_row['state']:'';
                                            $address .= !empty($ss_row['zip_code'])?' '.$ss_row['zip_code']:'';
                                            $email_data['admin_address'] = $address;
                                        }

                                        if(!empty($ss_row['email_address'])) // Contact email id
                                            $to = $ss_row['email_address'];

                                        $edata['from_name'] = "Property Status Changed";
                                        $edata['from_email'] = $from;

                                        if(!empty($autores_res[0]['email_message']))
                                        {
                                            $emaildata = array(
                                                'Date'=>date('Y-m-d'),
                                                'Day'=>date('l'),
                                                'Month'=>date('F'),
                                                'Year'=>date('Y'),
                                                'Day Of Week'=>date("w",time()),
                                                'Agent Name'=>!empty($email_data['admin_name'])?$email_data['admin_name']:'',
                                                'Contact First Name'=> !empty($ss_row['first_name'])?ucwords($ss_row['first_name']):'',
                                                'Contact Spouse/Partner First Name'=>!empty($ss_row['spousefirst_name'])?ucwords($ss_row['spousefirst_name']):'',
                                                'Contact Last Name'=> !empty($ss_row['last_name'])?ucwords($ss_row['last_name']):'',
                                                'Contact Spouse/Partner Last Name'=> !empty($ss_row['spouselast_name'])?ucwords($ss_row['spouselast_name']):'',
                                                'Contact Company Name'=> !empty($ss_row['company_name'])?ucwords($ss_row['company_name']):''
                                            );

                                            $pattern = "{(%s)}";
                                            $map = array();

                                            if($emaildata != '' && count($emaildata) > 0)
                                            {
                                                foreach($emaildata as $var => $value)
                                                {
                                                    $map[sprintf($pattern, $var)] = $value;
                                                }
                                                $output = strtr($autores_res[0]['email_message'], $map);
                                                $email_data['msg_body'] = $output;
                                            }
                                        }

                                        $email_data['tabname'] = 'SS';
                                        $email_data['property_name'] =  $property_row['full_address'];
                                        $message = $this->load->view('ws/property_status_email', $email_data, TRUE);
                                        //echo $message;exit;
                                        //// Mailgun email
                                        if(!empty($to))
                                            $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                                        echo "Saved Searches: ".$to."<br />";
                                        pr($response);
                                    } else {
                                        echo "Saved searches criteria not match with property."."<br />";
                                    }
                                } 
                            } else {
                                echo "Saved searches not found."."<br />";
                            }
                        }
                        ///////// End Saved Searches ///////
                    }
                }
                /// Update property status change flag
                $pupdate['ID'] = $property_row['ID'];
                $pupdate['is_status_change'] = 0;
                $this->user_management_model->update_property_status_flag($pupdate,$parent_db.'.mls_property_list_master');
            }
        }
        //exit;
        if(!empty($cdata)) {
            $udata['id'] = 1;
            $udata['property_status_cron_date'] = $currdate;
            $this->user_management_model->update_cron_data($udata,$parent_db.'.cron_data');
        } else {
            $udata['property_status_cron_date'] = $currdate;
            $this->user_management_model->insert_cron_data($udata,$parent_db.'.cron_data');
        }
    }
    
    //// Loop Change (Domain -> Saved Searches and Favorites -> Property)
    function property_status_change_cron()
    {
        set_time_limit(0);
        $parent_db=$this->config->item('parent_db_name');
        $this->load->model('property_list_masters_model');
        $table = $parent_db.'.cron_data';
        $fields = array('property_status_cron_date');
        $match = array('id'=>1);
        $cdata = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
        
        $currdate = date('Y-m-d H:i:s');
        $cdate = $currdate;
        if(!empty($cdata)) {
            if(!empty($cdata[0]['property_status_cron_date']) && $cdata[0]['property_status_cron_date'] != '0000-00-00 00:00:00')
                $cdate = date('Y-m-d H:i:s',strtotime($cdata[0]['property_status_cron_date']));
        }
        
        $table = $parent_db.'.mls_property_list_master';
        $fields = array('ID');
        $where = 'modified_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND modified_date <= "'.$cdate.'"';
        $match = array('is_status_change'=>1);
        $update_pro_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);
        
        //// Get all child database admin
        $table=$parent_db.'.child_admin_website as caw';
        $fields = array('caw.id,caw.domain,caw.mls_id,caw.first_name,caw.last_name,caw.email_id,lm.db_name,lm.admin_name,lm.email_id as admin_email,lm.address,lm.phone,lm.db_name,lm.timezone,lm.brokerage_pic');
        $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('website_status'=>1);
        $domain_list=$this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','caw.id','asc');
        $admin_emailid = '';
        
        if(!empty($domain_list))
        {
            foreach($domain_list as $dbrow)
            {
                echo '<br /><br />DB Name: '.$dbrow['db_name']."<br />";
                echo 'Domain: '.$dbrow['domain']."<br />";
                $db_name = $dbrow['db_name'];
                $domain_name = $dbrow['domain'];
                $admin_id = $dbrow['id'];
                
                //// Get child admin website id ////
                $table = $db_name.".child_website_domain_master";
                $fields = array('id');
                $match = array('domain_name'=>$dbrow['domain'],'website_status'=>1);
                $domain_id_arr = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
                $domain_id = 0;
                if(!empty($domain_id_arr[0]['id']))
                    $domain_id = $domain_id_arr[0]['id'];
                //// End get child admin website id ////
                
                ///////// Favorites ///////
                // ,lu.email_for_favorite add by sanjay c
                // $db_name.'.lead_users as lu'=>'lu.id = jrb.uid', add by sanjay c
                
                $favorites_data = array();
                $table = $db_name.'.joomla_rpl_bookmarks as jrb';
                $fields = array('jrb.pid,jrb.propery_name as property_name,jrb.mlsid,jrb.domain,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid,lu.email_for_favorite');
                $match = array('lu.email_for_favorite'=>1,'jrb.domain_id'=>$domain_id);
                //$wherestring = 'uct.agent_type != "Lender"';
                $wherestring = '';
                $join_tables = array(
                    $db_name.'.contact_master as cm'=>'cm.id = jrb.lw_admin_id',
                    $db_name.'.lead_users as lu'=>'lu.id = jrb.uid',
                    '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                    //$db_name.'.contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                    '(SELECT uctin.* FROM '. $db_name.'.user_contact_trans uctin WHERE uctin.agent_type != "Lender" GROUP BY uctin.user_id) AS uct'=>'uct.contact_id = cm.id',
                    //$db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                    $db_name.'.user_master as um' => 'um.id = uct.user_id',
                    $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                    '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                    '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                );
                $favorites_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','jrb.id','desc','',$wherestring);
                if(!empty($favorites_data))
                {
                    foreach($favorites_data as $fav_row)
                    {
                        //// Find property that matches with users Favorites list
                        $table = $parent_db.'.mls_property_list_master';
                        $fields = array('ID,LN,ST,is_status_change,old_status,display_price,full_address,PTYP,BR,BTH,mls_id');
                        $where = 'modified_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND modified_date <= "'.$cdate.'"';
                        $match = array('is_status_change'=>1,'LN'=>$fav_row['mlsid'],'mls_id'=>$dbrow['mls_id']);
                        $property_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);
                        
                        //// If property data found (i.e if status change on any property)
                        if(!empty($property_data))
                        {
                            $pc = 0;
                            $email_data = array();
                            
                            $email_data['admin_emailid'] = $dbrow['admin_email'];
                            $email_data['admin_name'] = $dbrow['admin_name'];
                            $db_name = $dbrow['db_name'];
                            $email_data['brokerage_pic'] = $dbrow['brokerage_pic'];
                            $admin_id = $dbrow['id'];
                            $email_data['domain'] =$fav_row['domain'];
                            $email_data['admin_address'] = $dbrow['address'];
                            $email_data['admin_phone'] = $dbrow['phone'];
                            
                            //// Auto responder ////
                            $autores_res = array();
                            $fields = array('template_name,template_subject,email_message,email_event');
                            $match = array('email_event'=>'4');
                            $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

                            if(!empty($autores_res[0]['template_subject'])) {
                                $subject = $autores_res[0]['template_subject'];
                                $subject .= !empty($email_data['domain'])?' - '.$email_data['domain']:'';
                            }
                            else {
                                $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                                $subject = "Your Favorite property status has been changed - ".$d;
                            }

                            if(!empty($email_data['admin_emailid']))
                                $from = $email_data['admin_emailid'];
                            else
                                $from = $this->config->item('admin_email');

                            $email_data['name'] = !empty($fav_row['user_name'])?ucwords($fav_row['user_name']):'';
                            if(!empty($fav_row['email_id'])) // Assigned agent email id
                            {
                                $from = $fav_row['email_id'];
                                $email_data['admin_name'] = $fav_row['admin_name'];
                                $email_data['admin_phone'] = $fav_row['phone'];
                                $email_data['brokerage_pic'] = $fav_row['brokerage_pic'];
                                $address .= !empty($fav_row['address_line1'])?$fav_row['address_line1']:'';
                                $address .= !empty($fav_row['address_line2'])?', '.$fav_row['address_line2']:'';
                                $address .= !empty($fav_row['city'])?', '.$fav_row['city']:'';
                                $address .= !empty($fav_row['state'])?', '.$fav_row['state']:'';
                                $address .= !empty($fav_row['zip_code'])?' '.$fav_row['zip_code']:'';
                                $email_data['admin_address'] = $address;
                            }

                            if(!empty($fav_row['email_address'])) // Contact email id
                                $to = $fav_row['email_address'];

                            $edata['from_name'] = "Property Status Changed";
                            $edata['from_email'] = $from;

                            if(!empty($autores_res[0]['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>!empty($email_data['admin_name'])?$email_data['admin_name']:'',
                                    'Contact First Name'=> !empty($fav_row['first_name'])?ucwords($fav_row['first_name']):'',
                                    'Contact Spouse/Partner First Name'=>!empty($fav_row['spousefirst_name'])?ucwords($fav_row['spousefirst_name']):'',
                                    'Contact Last Name'=> !empty($fav_row['last_name'])?ucwords($fav_row['last_name']):'',
                                    'Contact Spouse/Partner Last Name'=> !empty($fav_row['spouselast_name'])?ucwords($fav_row['spouselast_name']):'',
                                    'Contact Company Name'=> !empty($fav_row['company_name'])?ucwords($fav_row['company_name']):''
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($autores_res[0]['email_message'], $map);
                                    $email_data['msg_body'] = $output;
                                }
                            }

                            $email_data['tabname'] = 'F';
                            
                            foreach($property_data as $property_row)
                            {
                                //A -Active, CT - Contingent, PB - Pending Backup Offers Requested, PF - Pending Feasibility, PI - Pending Inspection, PS - Pending Short Sale, P - Pending, S - Sold
                                if($property_row['ST'] == 'A')
                                    $status = 'Active';
                                else if($property_row['ST'] == 'P' || $property_row['ST'] == 'PB' || $property_row['ST'] == 'PF' || $property_row['ST'] == 'PI' || $property_row['ST'] == 'PS')
                                    $status = 'Pending';
                                else if($property_row['ST'] == 'S')
                                    $status = 'Sold';
                                else if($property_row['ST'] == 'CT')
                                    $status = 'Contingent';
                                else if($property_row['ST'] == 'E')
                                    $status = 'Expired';
                                
                                $email_data['property_name'][$pc] =  $fav_row['property_name'];
                                $email_data['property_price'][$pc] = $property_row['display_price'];
                                $email_data['status'][$pc] = $status;
                                $pc++;
                                $email_data['is_p_array'] = 1;
                                $message = $this->load->view('ws/property_status_email', $email_data, TRUE);
                                //// Mailgun email
                                if(!empty($to))
                                    $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                                echo "Favorites: ".$to."<br />";
                                //pr($response);
                            }
                        } else {
                            echo "Property not found that matches with Favorites"."<br />";
                        }
                    }
                } else {
                    echo "Favorites not found"."<br />";
                }
                ///////// End Favorites ///////

                ///////// Saved Searches ///////
                $saved_searches_data = array();
                $table=$db_name.'.joomla_rpl_savesearch as ss';

                // ,lu.email_for_status_change add by sanjay c
                // $db_name.'.lead_users as lu'=>'lu.id = ss.uid', add by sanjay c
                $fields = array('ss.id,ss.*,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid,lu.email_for_status_change');
                $match = array('lu.email_for_status_change'=>1,'ss.domain_id'=>$domain_id);
                //$wherestring = 'uct.agent_type != "Lender"';
                $wherestring = '';
                $join_tables = array(
                    $db_name.'.contact_master as cm'=>'cm.id = ss.lw_admin_id',
                    $db_name.'.lead_users as lu'=>'lu.id = ss.uid',
                    '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                    //$db_name.'.contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                    '(SELECT uctin.* FROM '. $db_name.'.user_contact_trans uctin WHERE uctin.agent_type != "Lender" GROUP BY uctin.user_id) AS uct'=>'uct.contact_id = cm.id',
                    //$db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                    $db_name.'.user_master as um' => 'um.id = uct.user_id',
                    $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                    '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                    '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                );
                $saved_searches_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','ss.id','desc','',$wherestring);
                if(!empty($saved_searches_data))
                {
                    foreach($saved_searches_data as $ss_row)
                    {
                        //// Find property that matches with users Saved searches criteria
                        $table = $parent_db.'.mls_property_list_master';
                        $fields = array('ID,LN,PTYP,LAG,ST,UD,CIT,ASF,full_address,display_price,BR,BTH,TSP,ASF,YBT,is_status_change,old_status');
                        //$property_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);
                        $where = '(modified_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND modified_date <= "'.$cdate.'") AND ';
                        $match = array('is_status_change'=>1,'mls_id'=>$dbrow['mls_id']);
                        $group_by = 'ID';
                        if (!empty($ss_row['search_criteria']))
                        {
                            if(!empty($ss_row['search_category']) && $ss_row['search_category'] != 'Keyword')
                            {
                                if($ss_row['search_category'] == 'County')
                                    $where .= '(COU LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Community/Neighborhood')
                                    $where .= '(DSR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Property Address')
                                    $where .= '(full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Zip Code')
                                    $where .= '(ZIP LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Street Name')
                                    $where .= '(STR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';// OR SSUF LIKE "%' . $searchtext . '%") AND ';
                            } else
                                $where .= '(CIT LIKE "%' . $ss_row['search_criteria'] . '%" OR DSR LIKE "%' . $ss_row['search_criteria'] . '%" OR SD LIKE "%' . $ss_row['search_criteria'] . '%" OR mls_id LIKE "%' . $ss_row['search_criteria'] . '%" OR ZIP LIKE "%' . $ss_row['search_criteria'] . '%" OR full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                        }
                        if (!empty($ss_row['min_price']))
                            $where .= ' display_price >= "' . str_replace(',', '', $ss_row['min_price']) . '" AND ';
                        if (!empty($ss_row['max_price']))
                            $where .= ' display_price <= "' . str_replace(',', '', $ss_row['max_price']) . '" AND ';
                        if (!empty($ss_row['bedroom']))
                            $where .= ' BR >= "' . $ss_row['bedroom'] . '" AND ';
                        if (!empty($ss_row['bathroom']))
                            $where .= ' BTH >= "' . $ss_row['bathroom'] . '" AND ';

                        if (!empty($ss_row['property_type']))
                        {
                            $where .= ' PTYP = "' . $ss_row['property_type'] . '" AND ';
                        }

                        if (!empty($ss_row['city']))
                        {
                            $city = explode('{^}',$ss_row['city']);
                            if(is_array($city))
                            {
                                $where .= '(';
                                foreach ($city as $ctrow)
                                {
                                    $where .= ' CIT = "' . $ctrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }
                        if (!empty($ss_row['parking_type']))
                            $where .= ' GR = "' . $ss_row['parking_type'] . '" AND ';
                        if (!empty($_REQUEST['garage_spaces']))
                            $where .= ' GSP >= "' . $ss_row['garage_spaces'] . '" AND ';

                        if (!empty($ss_row['year_built']))
                            $where .= ' YBT = "' . $ss_row['year_built'] . '" AND ';
                        if (!empty($ss_row['architecture']))
                            $where .= ' ARC = "' . $ss_row['architecture'] . '" AND ';
                        if (!empty($ss_row['school_district']))
                            $where .= ' SD = "' . $ss_row['school_district'] . '" AND ';
                        if (!empty($ss_row['fireplaces']))
                            $where .= ' FP >= "' . $ss_row['fireplaces'] . '" AND ';

                        if (!empty($ss_row['min_lotsize']))
                            $where .= ' LSZS >= "' . $ss_row['min_lotsize'] . '" AND ';
                        if (!empty($ss_row['property_status']))
                        {
                            if ($ss_row['property_status'] == 'Active')
                                $where .= 'ST = "A" AND ';
                            elseif ($ss_row['property_status'] == 'Pending')
                                $where .= '(ST = "PB" OR ST = "PF" OR ST = "PI" OR ST = "PS" OR ST = "P" OR ST = "C") AND ';
                            elseif ($ss_row['property_status'] == 'Sold')
                                $where .= 'ST = "S" AND ';
                            // elseif ($_REQUEST['property_status'] == 'Contingent')
                            //     $where .= 'ST = "C" AND ';
                        }

                        if (!empty($ss_row['waterfront']))
                        {
                            $watefront = explode('{^}',$ss_row['waterfront']);
                            if(is_array($watefront))
                            {
                                $where .= '(';
                                foreach ($watefront as $wfrow)
                                {
                                    $where .= ' WFT = "' . $wfrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }
                        if (!empty($ss_row['s_views']))
                        {
                            $pview = explode('{^}',$ss_row['s_views']);
                            if(is_array($pview))
                            {
                                $where .= '(';
                                foreach ($pview as $pvrow)
                                {
                                    $where .= ' VEW = "' . $pvrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }

                        /*if (!empty($ss_row['waterfront']))
                            $where .= ' WFT = "' . $ss_row['waterfront'] . '" AND ';
                        if (!empty($ss_row['s_views']))
                            $where .= ' VEW = "' . $ss_row['s_views'] . '" AND ';*/
                        if(!empty($ss_row['new_construction']))
                          $where .= ' NewConstruction = "'.$ss_row['new_construction'].'" AND ';
                        if (isset($ss_row['short_sale']) && $ss_row['short_sale'] == 'Y')
                            $where .= ' PARQ = "C" AND ';

                        if (!empty($ss_row['bank_owned']))
                            $where .= ' BREO = "' . $ss_row['bank_owned'] . '" AND ';

                        if(!empty($ss_row['mls_id']))
                            $where .= ' LN = "'.$ss_row['mls_id'].'" AND ';
                        if(!empty($ss_row['CDOM']))
                            $where .= ' CDOM <= "'.$ss_row['CDOM'].'" AND ';
                        
                        $where = rtrim($where, ' AND');
                        $property_data = $this->property_list_masters_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=','', '', '', '', $group_by, $where);
                        
                        //// If property data found (i.e if status change on any property)
                        if(!empty($property_data))
                        {
                            $email_data = array();
                            $email_data['admin_emailid'] = $dbrow['admin_email'];
                            $email_data['admin_name'] = $dbrow['admin_name'];
                            $db_name = $dbrow['db_name'];
                            $email_data['brokerage_pic'] = $dbrow['brokerage_pic'];
                            $admin_id = $dbrow['id'];
                            $email_data['domain'] =$ss_row['domain'];
                            $email_data['admin_address'] = $dbrow['address'];
                            $email_data['admin_phone'] = $dbrow['phone'];
                            
                            //// Auto responder ////
                            $autores_res = array();
                            $fields = array('template_name,template_subject,email_message,email_event');
                            $match = array('email_event'=>'4');
                            $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

                            if(!empty($autores_res[0]['template_subject'])) {
                                $subject = $autores_res[0]['template_subject'];
                                $subject .= !empty($email_data['domain'])?' - '.$email_data['domain']:'';
                            }
                            else {
                                $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                                $subject = "Your Saved Searches preference property status has been changed - ".$d;
                            }
                            
                            if(!empty($email_data['admin_emailid']))
                                $from = $email_data['admin_emailid'];
                            else
                                $from = $this->config->item('admin_email');

                            $email_data['name'] = !empty($ss_row['user_name'])?ucwords($ss_row['user_name']):'';
                            if(!empty($ss_row['email_id'])) // Assigned agent email id
                            {
                                $from = $ss_row['email_id'];
                                $email_data['admin_name'] = $ss_row['admin_name'];
                                $email_data['admin_phone'] = $ss_row['phone'];
                                $email_data['brokerage_pic'] = $ss_row['brokerage_pic'];
                                $address .= !empty($ss_row['address_line1'])?$ss_row['address_line1']:'';
                                $address .= !empty($ss_row['address_line2'])?', '.$ss_row['address_line2']:'';
                                $address .= !empty($ss_row['city'])?', '.$ss_row['city']:'';
                                $address .= !empty($ss_row['state'])?', '.$ss_row['state']:'';
                                $address .= !empty($ss_row['zip_code'])?' '.$ss_row['zip_code']:'';
                                $email_data['admin_address'] = $address;
                            }

                            if(!empty($ss_row['email_address'])) // Contact email id
                                $to = $ss_row['email_address'];

                            $edata['from_name'] = "Property Status Changed";
                            $edata['from_email'] = $from;

                            if(!empty($autores_res[0]['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>!empty($email_data['admin_name'])?$email_data['admin_name']:'',
                                    'Contact First Name'=> !empty($ss_row['first_name'])?ucwords($ss_row['first_name']):'',
                                    'Contact Spouse/Partner First Name'=>!empty($ss_row['spousefirst_name'])?ucwords($ss_row['spousefirst_name']):'',
                                    'Contact Last Name'=> !empty($ss_row['last_name'])?ucwords($ss_row['last_name']):'',
                                    'Contact Spouse/Partner Last Name'=> !empty($ss_row['spouselast_name'])?ucwords($ss_row['spouselast_name']):'',
                                    'Contact Company Name'=> !empty($ss_row['company_name'])?ucwords($ss_row['company_name']):''
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($autores_res[0]['email_message'], $map);
                                    $email_data['msg_body'] = $output;
                                }
                            }

                            $email_data['tabname'] = 'SS';
                            $pc = 0;
                            foreach($property_data as $property_row)
                            {
                                //A -Active, CT - Contingent, PB - Pending Backup Offers Requested, PF - Pending Feasibility, PI - Pending Inspection, PS - Pending Short Sale, P - Pending, S - Sold
                                if($property_row['ST'] == 'A')
                                    $status = 'Active';
                                else if($property_row['ST'] == 'P' || $property_row['ST'] == 'PB' || $property_row['ST'] == 'PF' || $property_row['ST'] == 'PI' || $property_row['ST'] == 'PS')
                                    $status = 'Pending';
                                else if($property_row['ST'] == 'S')
                                    $status = 'Sold';
                                else if($property_row['ST'] == 'CT')
                                    $status = 'Contingent';
                                else if($property_row['ST'] == 'E')
                                    $status = 'Expired';
                                
                                $email_data['status'][$pc] = $status;
                                $email_data['property_price'][$pc] = $property_row['display_price'];
                                $email_data['property_name'][$pc] =  $property_row['full_address'];
                                $pc++;
                            }
                            $email_data['is_p_array'] = 1;
                            $message = $this->load->view('ws/property_status_email', $email_data, TRUE);
                            //echo $message;
                            //// Mailgun email
                            if(!empty($to))
                                $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                            echo "Saved Searches: ".$to."<br />";
                            //pr($response);
                        } else {
                            echo "Property not found with Saved Searches"."<br />";
                        }
                    }
                } else {
                    echo "Saved Searches not found"."<br />";
                }
            }
        }
        
        /// Update property status change flag
        if(!empty($update_pro_data))
        {
            foreach($update_pro_data as $urow)
            {
                $pupdate['ID'] = $urow['ID'];
                $pupdate['is_status_change'] = 0;
                $this->user_management_model->update_property_status_flag($pupdate,$parent_db.'.mls_property_list_master');
            }
        }
        $currdate = date("Y-m-d H:i:s",strtotime($currdate." +1 seconds"));
        if(!empty($cdata)) {
            $udata['id'] = 1;
            $udata['property_status_cron_date'] = $currdate;
            $this->user_management_model->update_cron_data($udata,$parent_db.'.cron_data');
        } else {
            $udata['property_status_cron_date'] = $currdate;
            $this->user_management_model->insert_cron_data($udata,$parent_db.'.cron_data');
        }        
    }
    
    /*
        @Description: Function for send email once property status changed whom added into favorite, saved searches
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 23-05-2015
    */
    //// Old Loop (Property -> Domain -> Saved Searches)
    function new_property_cron_old()
    {
        set_time_limit(0);
        $parent_db=$this->config->item('parent_db_name');
        $this->load->model('property_list_masters_model');
        $table = $parent_db.'.cron_data';
        $fields = array('new_property_cron_date');
        $match = array('id'=>1);
        $cdata = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
        
        $currdate = date('Y-m-d H:i:s');
        $cdate = $currdate;
        if(!empty($cdata)) {
            if(!empty($cdata[0]['new_property_cron_date']) && $cdata[0]['new_property_cron_date'] != '0000-00-00 00:00:00')
                $cdate = date('Y-m-d H:i:s',strtotime($cdata[0]['new_property_cron_date']));
        }
        $table = $parent_db.'.mls_property_list_master';
        $fields = array('ID,LN,ST,is_status_change,old_status,display_price,full_address,PTYP,BR,BTH,mls_id');
        $where = 'created_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND created_date < "'.$cdate.'"';
        //$match = array('is_status_change'=>1);
        $match = array();
        $property_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);

        //// If property data found (i.e if new property added)
        if(!empty($property_data))
        {
            foreach($property_data as $property_row)
            {
                //// Get all child database admin
                $table=$parent_db.'.child_admin_website as caw';
                $fields = array('caw.id,caw.domain,caw.mls_id,caw.first_name,caw.last_name,caw.email_id,lm.db_name,lm.admin_name,lm.email_id as admin_email,lm.address,lm.phone,lm.db_name,lm.timezone,lm.brokerage_pic');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = caw.lw_admin_id');
                $domain_list=$this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','caw.id','asc');
                $admin_emailid = '';
                if(!empty($domain_list))
                {
                    foreach($domain_list as $dbrow)
                    {
                        if($dbrow['mls_id'] == $property_row['mls_id'])
                        {
                            $email_data = array();
                            $email_data['property_price'] = $property_row['display_price'];
                            $email_data['admin_emailid'] = $dbrow['admin_email'];
                            $email_data['admin_name'] = $dbrow['admin_name'];
                            $db_name = $dbrow['db_name'];
                            $email_data['brokerage_pic'] = $dbrow['brokerage_pic'];
                            $admin_id = $dbrow['id'];
                            $email_data['domain'] =$dbrow['domain'];
                            $email_data['admin_address'] = $dbrow['address'];
                            $email_data['admin_phone'] = $dbrow['phone'];

                            if(!empty($email_data['admin_emailid']))
                                $from = $email_data['admin_emailid'];
                            else
                                $from = $this->config->item('admin_email');

                            ///////// Check Saved Searches ///////
                            $saved_searches_data = array();
                            $table=$db_name.'.joomla_rpl_savesearch as ss';
                            $fields = array('ss.*,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid');
                            
                            $join_tables = array(
                                $db_name.'.contact_master as cm'=>'cm.id = ss.lw_admin_id',
                                //$db_name.'.contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                                '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                                $db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                                $db_name.'.user_master as um' => 'um.id = uct.user_id',
                                $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                                '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                            );
                            $match = array('ss.domain'=>$dbrow['domain']);
                            $saved_searches_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','ss.id','desc','','');

                            if(!empty($saved_searches_data))
                            {
                                $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                                $subject = "New Property added that matches your Saved Searches criteria - ".$d;
                                //pr($property_row);exit;
                                foreach($saved_searches_data as $ss_row)
                                {
                                    //// Find property that matches with users Saved searches criteria
                                    $table = $parent_db.'.mls_property_list_master';
                                    $fields = array('ID,LN,PTYP,LAG,ST,UD,CIT,ASF,full_address,display_price,BR,BTH,TSP,ASF,YBT,is_status_change,old_status,MR');
                                    //$where = '(created_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND created_date < "'.$cdate.'") AND ';
                                    $where = '';
                                    $match = array('mls_id'=>$dbrow['mls_id'],'ID'=>$property_row['ID']);
                                    $group_by = 'ID';
                                    if (!empty($ss_row['search_criteria']))
                                    {
                                        if(!empty($ss_row['search_category']) && $ss_row['search_category'] != 'Keyword')
                                        {
                                            if($ss_row['search_category'] == 'County')
                                                $where .= '(COU LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Community/Neighborhood')
                                                $where .= '(DSR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Property Address')
                                                $where .= '(full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Zip Code')
                                                $where .= '(ZIP LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                            else if($ss_row['search_category'] == 'Street Name')
                                                $where .= '(STR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';// OR SSUF LIKE "%' . $searchtext . '%") AND ';
                                        } else
                                            $where .= '(CIT LIKE "%' . $ss_row['search_criteria'] . '%" OR DSR LIKE "%' . $ss_row['search_criteria'] . '%" OR SD LIKE "%' . $ss_row['search_criteria'] . '%" OR mls_id LIKE "%' . $ss_row['search_criteria'] . '%" OR ZIP LIKE "%' . $ss_row['search_criteria'] . '%" OR full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                    }
                                    if (!empty($ss_row['min_price']))
                                        $where .= ' display_price >= "' . str_replace(',', '', $ss_row['min_price']) . '" AND ';
                                    if (!empty($ss_row['max_price']))
                                        $where .= ' display_price <= "' . str_replace(',', '', $ss_row['max_price']) . '" AND ';
                                    if (!empty($ss_row['bedroom']))
                                        $where .= ' BR >= "' . $ss_row['bedroom'] . '" AND ';
                                    if (!empty($ss_row['bathroom']))
                                        $where .= ' BTH >= "' . $ss_row['bathroom'] . '" AND ';

                                    if (!empty($ss_row['property_type']))
                                    {
                                        $where .= ' PTYP = "' . $ss_row['property_type'] . '" AND ';
                                    }

                                    if (!empty($ss_row['city']))
                                    {
                                        $city = explode('{^}',$ss_row['city']);
                                        if(is_array($city))
                                        {
                                            $where .= '(';
                                            foreach ($city as $ctrow)
                                            {
                                                $where .= ' CIT = "' . $ctrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }
                                    if (!empty($ss_row['parking_type']))
                                        $where .= ' GR = "' . $ss_row['parking_type'] . '" AND ';
                                    if (!empty($_REQUEST['garage_spaces']))
                                        $where .= ' GSP >= "' . $ss_row['garage_spaces'] . '" AND ';

                                    if (!empty($ss_row['year_built']))
                                        $where .= ' YBT = "' . $ss_row['year_built'] . '" AND ';
                                    if (!empty($ss_row['architecture']))
                                        $where .= ' ARC = "' . $ss_row['architecture'] . '" AND ';
                                    if (!empty($ss_row['school_district']))
                                        $where .= ' SD = "' . $ss_row['school_district'] . '" AND ';
                                    if (!empty($ss_row['fireplaces']))
                                        $where .= ' FP >= "' . $ss_row['fireplaces'] . '" AND ';

                                    if (!empty($ss_row['min_lotsize']))
                                        $where .= ' LSZS >= "' . $ss_row['min_lotsize'] . '" AND ';
                                    if (!empty($ss_row['property_status']))
                                    {
                                        if ($ss_row['property_status'] == 'Active')
                                            $where .= 'ST = "A" AND ';
                                        elseif ($ss_row['property_status'] == 'Pending')
                                            $where .= '(ST = "PB" OR ST = "PF" OR ST = "PI" OR ST = "PS" OR ST = "P" OR ST = "C") AND ';
                                        elseif ($ss_row['property_status'] == 'Sold')
                                            $where .= 'ST = "S" AND ';
                                        // elseif ($_REQUEST['property_status'] == 'Contingent')
                                        //     $where .= 'ST = "C" AND ';
                                    }

                                    if (!empty($ss_row['waterfront']))
                                    {
                                        $watefront = explode('{^}',$ss_row['waterfront']);
                                        if(is_array($watefront))
                                        {
                                            $where .= '(';
                                            foreach ($watefront as $wfrow)
                                            {
                                                $where .= ' WFT = "' . $wfrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }
                                    if (!empty($ss_row['s_views']))
                                    {
                                        $pview = explode('{^}',$ss_row['s_views']);
                                        if(is_array($pview))
                                        {
                                            $where .= '(';
                                            foreach ($pview as $pvrow)
                                            {
                                                $where .= ' VEW = "' . $pvrow . '" OR ';
                                            }
                                            $where = rtrim($where, ' OR');
                                            $where .= ') AND ';
                                        }
                                    }

                                    /*if (!empty($ss_row['waterfront']))
                                        $where .= ' WFT = "' . $ss_row['waterfront'] . '" AND ';
                                    if (!empty($ss_row['s_views']))
                                        $where .= ' VEW = "' . $ss_row['s_views'] . '" AND ';*/
                                    if(!empty($ss_row['new_construction']))
                                      $where .= ' NewConstruction = "'.$ss_row['new_construction'].'" AND ';
                                    if (isset($ss_row['short_sale']) && $ss_row['short_sale'] == 'Y')
                                        $where .= ' PARQ = "C" AND ';

                                    if (!empty($ss_row['bank_owned']))
                                        $where .= ' BREO = "' . $ss_row['bank_owned'] . '" AND ';

                                    if(!empty($ss_row['mls_id']))
                                        $where .= ' LN = "'.$ss_row['mls_id'].'" AND ';
                                    if(!empty($ss_row['CDOM']))
                                        $where .= ' CDOM <= "'.$ss_row['CDOM'].'" AND ';

                                    $where = rtrim($where, ' AND');
                                    $single_property = $this->property_list_masters_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=','', '', '', '', $group_by, $where);
                                    if(!empty($single_property))
                                    {
                                        $email_data['name'] = !empty($ss_row['user_name'])?ucwords($ss_row['user_name']):'';
                                        if(!empty($ss_row['email_id'])) // Assigned agent email id
                                        {
                                            $from = $ss_row['email_id'];
                                            $email_data['admin_name'] = $ss_row['admin_name'];
                                            $email_data['admin_phone'] = $ss_row['phone'];
                                            $email_data['brokerage_pic'] = $ss_row['brokerage_pic'];
                                            $address .= !empty($ss_row['address_line1'])?$ss_row['address_line1']:'';
                                            $address .= !empty($ss_row['address_line2'])?', '.$ss_row['address_line2']:'';
                                            $address .= !empty($ss_row['city'])?', '.$ss_row['city']:'';
                                            $address .= !empty($ss_row['state'])?', '.$ss_row['state']:'';
                                            $address .= !empty($ss_row['zip_code'])?' '.$ss_row['zip_code']:'';
                                            $email_data['admin_address'] = $address;
                                        }

                                        if(!empty($ss_row['email_address'])) // Contact email id
                                            $to = $ss_row['email_address'];

                                        $edata['from_name'] = "New Property Added";
                                        $edata['from_email'] = $from;

                                        $email_data['property_name'] =  $property_row['full_address'];
                                        $email_data['property_description'] =  $property_row['MR'];
                                        
                                        $message = $this->load->view('ws/new_property_email', $email_data, TRUE);
                                        //echo $message;exit;
                                        //// Mailgun email
                                        if(!empty($to))
                                            $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                                        echo "New property: ".$to."<br />";
                                        pr($response);
                                    } else{
                                        echo "Search criteria not match with new added property."."<br />";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            echo "New Property not added."."<br />";
        }
        //exit;
        if(!empty($cdata)) {
            $udata['id'] = 1;
            $udata['new_property_cron_date'] = $currdate;
            $this->user_management_model->update_cron_data($udata,$parent_db.'.cron_data');
        } else {
            $udata['new_property_cron_date'] = $currdate;
            $this->user_management_model->insert_cron_data($udata,$parent_db.'.cron_data');
        }
    }
    //// Loop Change (Domain -> Saved Searches and Favorites -> Property)
    function new_property_cron()
    {
        set_time_limit(0);
        $parent_db=$this->config->item('parent_db_name');
        $this->load->model('property_list_masters_model');
        $table = $parent_db.'.cron_data';
        $fields = array('new_property_cron_date');
        $match = array('id'=>1);
        $cdata = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
        
        $currdate = date('Y-m-d H:i:s');
        $cdate = $currdate;
        if(!empty($cdata)) {
            if(!empty($cdata[0]['new_property_cron_date']) && $cdata[0]['new_property_cron_date'] != '0000-00-00 00:00:00')
                $cdate = date('Y-m-d H:i:s',strtotime($cdata[0]['new_property_cron_date']));
        }
        //echo $cdate;
        //// Get all child database admin
        $table=$parent_db.'.child_admin_website as caw';
        $fields = array('caw.id,caw.domain,caw.mls_id,caw.first_name,caw.last_name,caw.email_id,lm.db_name,lm.admin_name,lm.email_id as admin_email,lm.address,lm.phone,lm.db_name,lm.timezone,lm.brokerage_pic');
        $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('caw.website_status'=>1);
        $domain_list = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','caw.id','asc');
        $admin_emailid = '';
        if(!empty($domain_list))
        {
            foreach($domain_list as $dbrow)
            {
                echo '<br /><br />DB Name: '.$dbrow['db_name']."<br />";
                echo 'Domain: '.$dbrow['domain']."<br />";
                $db_name = $dbrow['db_name'];
                $domain_name = $dbrow['domain'];
                $admin_id = $dbrow['id'];
                
                //// Get child admin website id ////
                $domain_id_arr = array();
                $table = $db_name.'.child_website_domain_master';
                $fields = array('id');
                $match = array('domain_name'=>$dbrow['domain'],'website_status'=>1);
                $domain_id_arr = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=');
                $domain_id = 0;
                if(!empty($domain_id_arr[0]['id']))
                    $domain_id = $domain_id_arr[0]['id'];
                //// End get child admin website id ////
                
                ///////// Saved Searches ///////
                $saved_searches_data = array();
                $table=$db_name.'.joomla_rpl_savesearch as ss';
                $fields = array('ss.id,ss.*,CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code,uct.id as uctid');
                $match = array('ss.domain_id'=>$domain_id);
                //$wherestring = 'uct.agent_type != "Lender"';
                $wherestring = '';
                $join_tables = array(
                    $db_name.'.contact_master as cm'=>'cm.id = ss.lw_admin_id',
                    '(SELECT cetin.* FROM '. $db_name.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                    //$db_name.'.contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                    '(SELECT uctin.* FROM '. $db_name.'.user_contact_trans uctin WHERE uctin.agent_type != "Lender" GROUP BY uctin.user_id) AS uct'=>'uct.contact_id = cm.id',
                    //$db_name.'.user_contact_trans as uct' => 'uct.contact_id = cm.id',
                    $db_name.'.user_master as um' => 'um.id = uct.user_id',
                    $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                    '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                    '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                );

                $saved_searches_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','ss.id','desc','',$wherestring);
                //pr($saved_searches_data);
                if(!empty($saved_searches_data))
                {
                    foreach($saved_searches_data as $ss_row)
                    {
                        $property_data = array();
                        //// Find property that matches with users Saved searches criteria
                        $table = $parent_db.'.mls_property_list_master';
                        $fields = array('ID,LN,PTYP,LAG,ST,UD,CIT,ASF,full_address,display_price,BR,BTH,TSP,ASF,YBT,is_status_change,old_status,MR');
                        //$property_data = $this->property_list_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','','','',$where);
                        $where = '(created_date >= date_sub("'.$cdate.'", interval 5 MINUTE) AND created_date <= "'.$cdate.'") AND ';
                        $match = array('mls_id'=>$dbrow['mls_id']);
                        $group_by = 'ID';
                        if (!empty($ss_row['search_criteria']))
                        {
                            if(!empty($ss_row['search_category']) && $ss_row['search_category'] != 'Keyword')
                            {
                                if($ss_row['search_category'] == 'County')
                                    $where .= '(COU LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Community/Neighborhood')
                                    $where .= '(DSR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Property Address')
                                    $where .= '(full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Zip Code')
                                    $where .= '(ZIP LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                                else if($ss_row['search_category'] == 'Street Name')
                                    $where .= '(STR LIKE "%' . $ss_row['search_criteria'] . '%") AND ';// OR SSUF LIKE "%' . $searchtext . '%") AND ';
                            } else
                                $where .= '(CIT LIKE "%' . $ss_row['search_criteria'] . '%" OR DSR LIKE "%' . $ss_row['search_criteria'] . '%" OR SD LIKE "%' . $ss_row['search_criteria'] . '%" OR mls_id LIKE "%' . $ss_row['search_criteria'] . '%" OR ZIP LIKE "%' . $ss_row['search_criteria'] . '%" OR full_address LIKE "%' . $ss_row['search_criteria'] . '%") AND ';
                        }
                        if (!empty($ss_row['min_price']))
                            $where .= ' display_price >= "' . str_replace(',', '', $ss_row['min_price']) . '" AND ';
                        if (!empty($ss_row['max_price']))
                            $where .= ' display_price <= "' . str_replace(',', '', $ss_row['max_price']) . '" AND ';
                        if (!empty($ss_row['bedroom']))
                            $where .= ' BR >= "' . $ss_row['bedroom'] . '" AND ';
                        if (!empty($ss_row['bathroom']))
                            $where .= ' BTH >= "' . $ss_row['bathroom'] . '" AND ';
                        
                        if (!empty($ss_row['property_type']))
                        {
                            $where .= ' PTYP = "' . $ss_row['property_type'] . '" AND ';
                        }

                        if (!empty($ss_row['city']))
                        {
                            $city = explode('{^}',$ss_row['city']);
                            if(is_array($city))
                            {
                                $where .= '(';
                                foreach ($city as $ctrow)
                                {
                                    $where .= ' CIT = "' . $ctrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }
                        if (!empty($ss_row['parking_type']))
                            $where .= ' GR = "' . $ss_row['parking_type'] . '" AND ';
                        if (!empty($_REQUEST['garage_spaces']))
                            $where .= ' GSP >= "' . $ss_row['garage_spaces'] . '" AND ';

                        if (!empty($ss_row['year_built']))
                            $where .= ' YBT = "' . $ss_row['year_built'] . '" AND ';
                        if (!empty($ss_row['architecture']))
                            $where .= ' ARC = "' . $ss_row['architecture'] . '" AND ';
                        if (!empty($ss_row['school_district']))
                            $where .= ' SD = "' . $ss_row['school_district'] . '" AND ';
                        if (!empty($ss_row['fireplaces']))
                            $where .= ' FP >= "' . $ss_row['fireplaces'] . '" AND ';

                        if (!empty($ss_row['min_lotsize']))
                            $where .= ' LSZS >= "' . $ss_row['min_lotsize'] . '" AND ';
                        if (!empty($ss_row['property_status']))
                        {
                            if ($ss_row['property_status'] == 'Active')
                                $where .= 'ST = "A" AND ';
                            elseif ($ss_row['property_status'] == 'Pending')
                                $where .= '(ST = "PB" OR ST = "PF" OR ST = "PI" OR ST = "PS" OR ST = "P" OR ST = "C") AND ';
                            elseif ($ss_row['property_status'] == 'Sold')
                                $where .= 'ST = "S" AND ';
                            // elseif ($_REQUEST['property_status'] == 'Contingent')
                            //     $where .= 'ST = "C" AND ';
                        }

                        if (!empty($ss_row['waterfront']))
                        {
                            $watefront = explode('{^}',$ss_row['waterfront']);
                            if(is_array($watefront))
                            {
                                $where .= '(';
                                foreach ($watefront as $wfrow)
                                {
                                    $where .= ' WFT = "' . $wfrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }
                        if (!empty($ss_row['s_views']))
                        {
                            $pview = explode('{^}',$ss_row['s_views']);
                            if(is_array($pview))
                            {
                                $where .= '(';
                                foreach ($pview as $pvrow)
                                {
                                    $where .= ' VEW = "' . $pvrow . '" OR ';
                                }
                                $where = rtrim($where, ' OR');
                                $where .= ') AND ';
                            }
                        }
                        
                        /*if (!empty($ss_row['waterfront']))
                            $where .= ' WFT = "' . $ss_row['waterfront'] . '" AND ';
                        if (!empty($ss_row['s_views']))
                            $where .= ' VEW = "' . $ss_row['s_views'] . '" AND ';*/
                        if(!empty($ss_row['new_construction']))
                          $where .= ' NewConstruction = "'.$ss_row['new_construction'].'" AND ';
                        if (isset($ss_row['short_sale']) && $ss_row['short_sale'] == 'Y')
                            $where .= ' PARQ = "C" AND ';

                        if (!empty($ss_row['bank_owned']))
                            $where .= ' BREO = "' . $ss_row['bank_owned'] . '" AND ';
                        
                        if(!empty($ss_row['mls_id']))
                            $where .= ' LN = "'.$ss_row['mls_id'].'" AND ';
                        if(!empty($ss_row['CDOM']))
                            $where .= ' CDOM <= "'.$ss_row['CDOM'].'" AND ';
                        
                        $where = rtrim($where, ' AND');
                        $property_data = $this->property_list_masters_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=','', '', '', '', $group_by, $where);
                        
                        //// If property data found (i.e if status change on any property)
                        if(!empty($property_data))
                        {
                            $email_data = array();
                            $email_data['admin_emailid'] = $dbrow['admin_email'];
                            $email_data['admin_name'] = $dbrow['admin_name'];
                            $db_name = $dbrow['db_name'];
                            $email_data['brokerage_pic'] = $dbrow['brokerage_pic'];
                            $admin_id = $dbrow['id'];
                            $email_data['domain'] =$ss_row['domain'];
                            $email_data['admin_address'] = $dbrow['address'];
                            $email_data['admin_phone'] = $dbrow['phone'];
                            
                            $d = !empty($email_data['domain'])?' '.$email_data['domain']:'';
                            $subject = "New Property added that matches your Saved Searches criteria - ".$d;

                            if(!empty($email_data['admin_emailid']))
                                $from = $email_data['admin_emailid'];
                            else
                                $from = $this->config->item('admin_email');
                            
                            $email_data['name'] = !empty($ss_row['user_name'])?ucwords($ss_row['user_name']):'';
                            if(!empty($ss_row['email_id'])) // Assigned agent email id
                            {
                                $from = $ss_row['email_id'];
                                $email_data['admin_name'] = $ss_row['admin_name'];
                                $email_data['admin_phone'] = $ss_row['phone'];
                                $email_data['brokerage_pic'] = $ss_row['brokerage_pic'];
                                $address .= !empty($ss_row['address_line1'])?$ss_row['address_line1']:'';
                                $address .= !empty($ss_row['address_line2'])?', '.$ss_row['address_line2']:'';
                                $address .= !empty($ss_row['city'])?', '.$ss_row['city']:'';
                                $address .= !empty($ss_row['state'])?', '.$ss_row['state']:'';
                                $address .= !empty($ss_row['zip_code'])?' '.$ss_row['zip_code']:'';
                                $email_data['admin_address'] = $address;
                            }
                            $to='';
                            if(!empty($ss_row['email_address'])) // Contact email id
                                $to = $ss_row['email_address'];

                            $edata['from_name'] = "New Property Added";
                            $edata['from_email'] = $from;

                            $pc = 0;
                            foreach($property_data as $property_row)
                            {
                                $email_data['property_name'][$pc] =  $property_row['full_address'];
                                $email_data['property_price'][$pc] = $property_row['display_price'];
                                $email_data['property_description'][$pc] = $property_row['MR'];
                                $pc++;
                            }
                            $email_data['is_p_array'] = 1;
                            $message = $this->load->view('ws/new_property_email', $email_data, TRUE);
                            //echo $message;exit;
                            //// Mailgun email
                            if(!empty($to))
                                $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                            echo "New Property: ".$to."<br />";
                            //pr($response);
                        } else {
                            echo "Property not match with Saved searches"."<br />";
                        }
                    }
                } else {
                    echo "Saved searches not found"."<br />";
                }
            }
        }
        $currdate = date("Y-m-d H:i:s",strtotime($currdate." +1 seconds"));
        if(!empty($cdata)) {
            $udata['id'] = 1;
            $udata['new_property_cron_date'] = $currdate;
            $this->user_management_model->update_cron_data($udata,$parent_db.'.cron_data');
        } else {
            $udata['new_property_cron_date'] = $currdate;
            $this->user_management_model->insert_cron_data($udata,$parent_db.'.cron_data');
        }
    }
    
    /*  NNNNNEEEEW
        @Description: Function for send email with pdf according to property cron setting (Weekly) (Value Watcher)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 13-06-2015
    */
    function get_valuation_cron_weekly_new()
    {
        $db_name = $this->config->item('parent_db_name');
        $fields1 = array('id,db_name,host_name,db_user_name,db_user_password,admin_name,email_id,phone,address,brokerage_pic');
        $match = array('user_type'=>'2','status'=>'1');
        $all_admin1 = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
        //$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
        //$all_admin1 = array_merge($all_admin,$merge_db);
        if(!empty($all_admin1))
        {
            foreach($all_admin1 as $admin_row)
            {
                $db_name1 = $admin_row['db_name'];
                $admin_name = $admin_row['admin_name'];
                $admin_phone = $admin_row['phone'];
                $admin_email = $admin_row['email_id'];
                $admin_address = $admin_row['address'];
                $brokerage_pic = $admin_row['brokerage_pic'];
                if(!empty($db_name1))
                {
                    //echo 'Hello';exit;
                    $table = $db_name1.".joomla_property_cron_master jpcm";
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Weekly','jpcm.data_from'=>2);
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $parent_db = $this->config->item('parent_db_name');
                            $table = $parent_db.".mls_property_list_master as mplm";
                            $fields = array('ID,HSN, DRP, STR, SSUF, DRS, UNT, CIT');
                            $where = array('ID'=>$row['property_id']);//$row['neighborhood'],'STA'=>$row['city'],'ZIP'=>$row['zip_code']);
                            $addr_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','1');
                            $wherestring = '';
                            if(!empty($addr_data[0]))
                            {
                                $wherestring = '(';
                                if(!empty($addr_data[0]['HSN'])) { $wherestring .= " HSN = '".$addr_data[0]['HSN']."' AND "; }
                                if(!empty($addr_data[0]['STR'])) { $wherestring .= " DRP = '".$addr_data[0]['DRP']."' AND "; }
                                if(!empty($addr_data[0]['STR'])) { $wherestring .= " STR = '".$addr_data[0]['STR']."' AND "; }
                                if(!empty($addr_data[0]['SSUF'])) { $wherestring .= " SSUF = '".$addr_data[0]['SSUF']."' AND "; }
                                if(!empty($addr_data[0]['DRS'])) { $wherestring .= " DRS = '".$addr_data[0]['DRS']."' AND "; }
                                if(!empty($addr_data[0]['UNT'])) { $wherestring .= " UNT = '".$addr_data[0]['UNT']."' AND "; }
                                if(!empty($addr_data[0]['CIT'])) { $wherestring .= " CIT = '".$addr_data[0]['CIT']."' AND "; }
                                if(!empty($row['city'])) { $wherestring .= " STA = '".$row['city']."' AND "; }
                                if(!empty($row['zip_code'])) { $wherestring .= " ZIP = '".$row['zip_code']."' AND "; }
                                $wherestring = trim($wherestring, 'AND ');
                                $wherestring .= ')';
                                if(!empty($wherestring) && $wherestring == '()')
                                {
                                   $wherestring = ''; 
                                }
                            }
                            
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['neighborhood'].", ".$row['city']." ".$row['zip_code']);
                            $addr1 = $row['neighborhood'].", ".$row['city']." ".$row['zip_code'];
                            
                            $table = $parent_db.".mls_property_list_master as mplm";
                            $fields = array('ID,LAT,LONGI');
                            if(!empty($wherestring))
                                $where = $wherestring;
                            else
                                $where = array('full_address'=>$addr1);//$row['neighborhood'],'STA'=>$row['city'],'ZIP'=>$row['zip_code']);
                            $pro = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','','','=','1', '','ID','desc','',$where);
                            
                            if(!empty($pro[0]['LAT']) && !empty($pro[0]['LONGI']) && !empty($pro[0]['ID']))
                            {
                                $this->load->model(mls_model);
                                $radius = !empty($row['radius_limit'])?$row['radius_limit']:'100';
                                $parent_db = $this->config->item('parent_db_name');
                                $table = $parent_db.".mls_property_list_master as mplm";
                                $fields = array('mplm.ID,mplm.LN,mplm.full_address as name,mplm.LP,mplm.SP,mplm.CDOM,mplm.LSZS as lot_size,mplm.ASF as sqft,mplm.BR as bedrooms,mplm.BTH as bathrooms,mplm.display_price as price,mplm.LAT,mplm.LONGI','( 3959 * acos( cos( radians('.$pro[0]['LAT'].') ) * cos( radians( mplm.LAT ) ) * cos( radians( mplm.LONGI ) - radians('.$pro[0]['LONGI'].') ) + sin( radians('.$pro[0]['LAT'].') ) * sin( radians( mplm.LAT ) ) ) ) AS distance,mplm.PIC,mplm.Internal_MLS_ID');
                                $join_table = array();//$parent_db . '.mls_property_image as mpi' => 'mplm.LN = mpi.listing_number');
                                $wherestring = '(mplm.ST="S" AND mplm.ID !='.$pro[0]['ID'].')';
                                //$where = array();
                                $having = 'distance < '.$radius;
                                $group_by = 'mplm.ID';
                                $pdata = array();
                                
                                $pdata['property_data'] = $this->mls_model->getmultiple_tables_records($table,$fields,$join_table,'left','','','=','10', '','distance','asc',$group_by,$wherestring,'','',$having);
                                
                                if(!empty($pdata['property_data']))
                                {
                                    $pdata['neighbor_address'] = urldecode($addr);

                                    //////////  Agent Data  ///////////// 
                                    $table = $db_name1.".joomla_property_cron_trans as jpct";
                                    $fields = array('jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','uct.user_id as assigned_agent_id,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                                    $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                    $join_tables = array(
                                        '(SELECT uctin.* FROM '.$db_name1.'.user_contact_trans uctin GROUP BY uctin.contact_id) AS uct'=>'uct.contact_id = jpct.contact_id',
                                        $db_name1.'.user_master as um' => 'um.id = uct.user_id',
                                        $db_name1.'.login_master as lm' => 'um.id = lm.user_id',
                                        '(SELECT uatin.* FROM '. $db_name1.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                        '(SELECT uptin.* FROM '. $db_name1.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                                    );
                                    $group_by='uct.contact_id';

                                    $agent_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','uct.contact_id','desc',$group_by);

                                    $pdata['agent_data'] = $agent_data;
                                    $pdata['admin_data'] = $admin_row;
                                    //////////  Agent Data  /////////////

                                    ///////// PDF GENERATE CODE /////////
                                    //$pdf_html = $this->load->view('ws/valuation_report_pdf', $pdata, TRUE);
                                    $pdf_html = $this->load->view('ws/new_valuation_pdf', $pdata, TRUE);
                                    //echo $pdf_html;exit;
                                    $mypdf = new mPDF('', '', '', '', '10', '10', '5', '5', '5', '8');
                                    //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                    //$mypdf->WriteHTML($stylesheet,1);
                                    $base_url = $this->config->item('base_url');
                                    $logo = $base_url."images/logo.png";
                                    //$mypdf->SetWatermarkImage($logo);
                                    //$mypdf->showWatermarkImage = true;
                                    $img_path = $this->config->item('image_path');
                                    $html = '';
                                    //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                    //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);

                                    $html .= $pdf_html;

                                    $mypdf->WriteHTML($html);

                                    $filename = 'market_watch_'.date('m-d-Y').'.pdf';
                                    //$content = $mypdf->Output('', 'S');
                                    $content = $mypdf->Output($filename, 'D');
                                    exit;
                                    $content = chunk_split(base64_encode($content));

                                    $from_name = 'Property Valuation Report';
                                    if(!empty($admin_email))
                                        $from_mail = $admin_email;
                                    else
                                        $from_mail = $this->config->item('admin_email');
                                    //$replyto = 'demotops@gmail.com';

                                    $email_temp = array();$autores_res = array();
                                    $fields = array('template_name,template_subject,email_message,email_event');
                                    $match = array('email_event'=>'9');
                                    $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);

                                    if(!empty($autores_res) && count($autores_res) > 0)
                                    {
                                        if(count($autores_res) > 1)
                                        {
                                            foreach($autores_res as $email_row)
                                            {
                                                if($email_row['template_name'] == 'Valuation Report')
                                                {
                                                    $email_temp = $email_row;
                                                }
                                            }
                                        }
                                        else {
                                            $email_temp = $autores_res[0];
                                        }
                                    } else {
                                        $email_temp = array();
                                    }

                                    if(!empty($email_temp['template_subject']))
                                        $subject = $email_temp['template_subject'];
                                    else
                                        $subject = 'Property Valuation Report - Livewire CRM';

                                    $table = $db_name1.".joomla_property_cron_trans as jpct";
                                    $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','cm.first_name','cm.last_name','cet.email_address as email_id');
                                    $where = array('jpct.joomla_property_cron_master_id'=>$row['id'],'lu.email_for_valuation_set'=>1);
                                    $join_tables = array(
                                        $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                                        $db_name1.'.lead_users as lu'=>'lu.lw_id = jpct.contact_id'
                                    );
                                    $group_by='cm.id';

                                    $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                    //echo $db_name1;
                                    if(!empty($contact_data))
                                    {
                                        foreach($contact_data as $con)
                                        {
                                            $agent_name = '';$agent_datalist= array();
                                            if(!empty($con['created_by']))
                                            {
                                                $table = $db_name1.".login_master as lm";   
                                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                                $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                                $wherestring = 'lm.id = '.$con['created_by'];
                                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                                if(!empty($agent_datalist))
                                                {
                                                    if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                        $agent_name = $agent_datalist[0]['admin_name'];
                                                    else
                                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                                }
                                            }
                                            $data = array();$emaildata = array();
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

                                                if(!empty($emaildata) && count($emaildata) > 0)
                                                {
                                                    foreach($emaildata as $var => $value)
                                                    {
                                                        $map[sprintf($pattern, $var)] = $value;
                                                    }
                                                    $output = strtr($email_temp['email_message'], $map);
                                                    $data['temp_msg'] = $output;
                                                }
                                            } else {
                                                $data['temp_msg'] = '';
                                            }

                                            $filename = 'market_watch_weekly_'.date('mdYHis').'.pdf';
                                            $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                            $data['neighborhood'] = $row['neighborhood'];
                                            $data['city'] = $row['city'];
                                            $data['zip_code'] = $row['zip_code'];
                                            $data['radius'] = $row['radius_limit'];
                                            $data['admin_name'] = $admin_name;
                                            $data['admin_email'] = $admin_email;
                                            $data['admin_phone'] = $admin_phone;
                                            $data['admin_address'] = $admin_address;
                                            $data['brokerage_pic'] = $brokerage_pic;
                                            $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
                                            $mailto = $con['email_id'];

                                            // Save PDF file in folder and store data into table
                                            $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                            $mypdf->Output($this->config->item('base_path')."/uploads/attachment_file/".$filename,'F');

                                            $edata = array();
                                            ///// Mailgun Email 19-03-2015
                                            $edata['from_name'] = "Property Valuation Report";
                                            $edata['from_email'] = $admin_email;
                                            $edata['attachment'][] = array('attachment_name'=>$filename);

                                            if(!empty($message))
                                            {
                                                $response = $this->email_campaign_master_model->MailSend($mailto,$subject,$message,$edata);
                                                
                                                $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                                $pdf_data['contact_id'] = $con['id'];
                                                $pdf_data['last_report_file'] = $filename;
                                                $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                                $pdf_data['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                                $this->joomla_property_cron_model->update_task($pdf_data);

                                                $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];

                                                if(file_exists($pdfpath))
                                                { 
                                                    @unlink($pdfpath);
                                                }
                                                pr($mailto);
                                                //echo "Email Sent Successfully.";echo "<br />";

                                                $temppath = $this->config->item('base_path').'/uploads/attachment_file/'.$filename;
                                                if(file_exists($temppath))
                                                { 
                                                    @unlink($temppath);
                                                }
                                            }
                                        }
                                    } else {
                                        echo "Email preference not set."."<br />";
                                    }
                                    ///////// END PDF /////////
                                } else {
                                    echo "Property not found."."<br />";
                                }
                            } else {
                                echo "Result not found."."<br />";
                            }
                        }
                    } else {
                        echo "Market Watch not found."."<br />";
                    }
                }
            }
        }
    }
    
    /*  NNNNNEEEEW
        @Description: Function for send email with pdf according to property cron setting (Weekly) (Value Watcher)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 13-06-2015
    */
    function get_valuation_cron_monthly_new()
    {
        $db_name = $this->config->item('parent_db_name');
        $fields1 = array('id,db_name,host_name,db_user_name,db_user_password,admin_name,email_id,phone,address,brokerage_pic');
        $match = array('user_type'=>'2','status'=>'1');
        $all_admin1 = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
        //$merge_db = array('0'=>array('id'=>'','db_name'=>$db_name,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
        //$all_admin1 = array_merge($all_admin,$merge_db);
        if(!empty($all_admin1))
        {
            foreach($all_admin1 as $admin_row)
            {
                $db_name1 = $admin_row['db_name'];
                $admin_name = $admin_row['admin_name'];
                $admin_phone = $admin_row['phone'];
                $admin_email = $admin_row['email_id'];
                $admin_address = $admin_row['address'];
                $brokerage_pic = $admin_row['brokerage_pic'];
                if(!empty($db_name1))
                {
                    //echo 'Hello';exit;
                    $table = $db_name1.".joomla_property_cron_master jpcm";
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Monthly','jpcm.data_from'=>2);
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $parent_db = $this->config->item('parent_db_name');
                            $table = $parent_db.".mls_property_list_master as mplm";
                            $fields = array('ID,HSN, DRP, STR, SSUF, DRS, UNT, CIT');
                            $where = array('ID'=>$row['property_id']);//$row['neighborhood'],'STA'=>$row['city'],'ZIP'=>$row['zip_code']);
                            $addr_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','1');
                            $wherestring = '';
                            if(!empty($addr_data[0]))
                            {
                                $wherestring = '(';
                                if(!empty($addr_data[0]['HSN'])) { $wherestring .= " HSN = '".$addr_data[0]['HSN']."' AND "; }
                                if(!empty($addr_data[0]['STR'])) { $wherestring .= " DRP = '".$addr_data[0]['DRP']."' AND "; }
                                if(!empty($addr_data[0]['STR'])) { $wherestring .= " STR = '".$addr_data[0]['STR']."' AND "; }
                                if(!empty($addr_data[0]['SSUF'])) { $wherestring .= " SSUF = '".$addr_data[0]['SSUF']."' AND "; }
                                if(!empty($addr_data[0]['DRS'])) { $wherestring .= " DRS = '".$addr_data[0]['DRS']."' AND "; }
                                if(!empty($addr_data[0]['UNT'])) { $wherestring .= " UNT = '".$addr_data[0]['UNT']."' AND "; }
                                if(!empty($addr_data[0]['CIT'])) { $wherestring .= " CIT = '".$addr_data[0]['CIT']."' AND "; }
                                if(!empty($row['city'])) { $wherestring .= " STA = '".$row['city']."' AND "; }
                                if(!empty($row['zip_code'])) { $wherestring .= " ZIP = '".$row['zip_code']."' AND "; }
                                $wherestring = trim($wherestring, 'AND ');
                                $wherestring .= ')';
                                if(!empty($wherestring) && $wherestring == '()')
                                {
                                   $wherestring = ''; 
                                }
                            }
                            
                            // Get property listing based on neighborhood, city, state, country and radius
                            $addr = urlencode($row['neighborhood'].", ".$row['city']." ".$row['zip_code']);
                            $addr1 = $row['neighborhood'].", ".$row['city']." ".$row['zip_code'];
                            
                            $table = $parent_db.".mls_property_list_master as mplm";
                            $fields = array('ID,LAT,LONGI');
                            if(!empty($wherestring))
                                $where = $wherestring;
                            else
                                $where = array('full_address'=>$addr1);//$row['neighborhood'],'STA'=>$row['city'],'ZIP'=>$row['zip_code']);
                            $pro = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','','','=','1', '','ID','desc','',$where);
                            
                            if(!empty($pro[0]['LAT']) && !empty($pro[0]['LONGI']) && !empty($pro[0]['ID']))
                            {
                                $this->load->model(mls_model);
                                $radius = !empty($row['radius_limit'])?$row['radius_limit']:'100';
                                $parent_db = $this->config->item('parent_db_name');
                                $table = $parent_db.".mls_property_list_master as mplm";
                                $fields = array('mplm.ID,mplm.LN,mplm.full_address as name,mplm.LP,mplm.SP,mplm.CDOM,mplm.LSZS as lot_size,mplm.ASF as sqft,mplm.BR as bedrooms,mplm.BTH as bathrooms,mplm.display_price as price,mplm.LAT,mplm.LONGI','( 3959 * acos( cos( radians('.$pro[0]['LAT'].') ) * cos( radians( mplm.LAT ) ) * cos( radians( mplm.LONGI ) - radians('.$pro[0]['LONGI'].') ) + sin( radians('.$pro[0]['LAT'].') ) * sin( radians( mplm.LAT ) ) ) ) AS distance,mplm.PIC,mplm.Internal_MLS_ID');
                                $join_table = array();//$parent_db . '.mls_property_image as mpi' => 'mplm.LN = mpi.listing_number');
                                $wherestring = '(mplm.ST="S" AND mplm.ID !='.$pro[0]['ID'].')';
                                //$where = array();
                                $having = 'distance < '.$radius;
                                $group_by = 'mplm.ID';
                                $pdata = array();
                                
                                $pdata['property_data'] = $this->mls_model->getmultiple_tables_records($table,$fields,$join_table,'left','','','=','10', '','distance','asc',$group_by,$wherestring,'','',$having);
                                
                                if(!empty($pdata['property_data']))
                                {
                                    $pdata['neighbor_address'] = urldecode($addr);

                                    //////////  Agent Data  ///////////// 
                                    $table = $db_name1.".joomla_property_cron_trans as jpct";
                                    $fields = array('jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','uct.user_id as assigned_agent_id,um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                                    $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                    $join_tables = array(
                                        '(SELECT uctin.* FROM '.$db_name1.'.user_contact_trans uctin GROUP BY uctin.contact_id) AS uct'=>'uct.contact_id = jpct.contact_id',
                                        $db_name1.'.user_master as um' => 'um.id = uct.user_id',
                                        $db_name1.'.login_master as lm' => 'um.id = lm.user_id',
                                        '(SELECT uatin.* FROM '. $db_name1.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                                        '(SELECT uptin.* FROM '. $db_name1.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id'
                                    );
                                    $group_by='uct.contact_id';

                                    $agent_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','uct.contact_id','desc',$group_by);

                                    $pdata['agent_data'] = $agent_data;
                                    $pdata['admin_data'] = $admin_row;
                                    //////////  Agent Data  /////////////

                                    ///////// PDF GENERATE CODE /////////
                                    $pdf_html = $this->load->view('ws/valuation_report_pdf', $pdata, TRUE);
                                    //echo $pdf_html;exit;
                                    $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
                                    //$stylesheet = file_get_contents('css/pdfcrm.css'); // external css
                                    //$mypdf->WriteHTML($stylesheet,1);
                                    $base_url = $this->config->item('base_url');
                                    $logo = $base_url."images/logo.png";
                                    //$mypdf->SetWatermarkImage($logo);
                                    //$mypdf->showWatermarkImage = true;
                                    $img_path = $this->config->item('image_path');
                                    $html = '';
                                    //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                    //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);

                                    $html .= $pdf_html;

                                    $mypdf->WriteHTML($html);

                                    $filename = 'market_watch_'.date('m-d-Y').'.pdf';
                                    $content = $mypdf->Output('', 'S');
                                    //$content = $mypdf->Output($filename, 'D');
                                    //exit;
                                    $content = chunk_split(base64_encode($content));

                                    $from_name = 'Property Valuation Report';
                                    if(!empty($admin_email))
                                        $from_mail = $admin_email;
                                    else
                                        $from_mail = $this->config->item('admin_email');
                                    //$replyto = 'demotops@gmail.com';

                                    $email_temp = array();$autores_res = array();
                                    $fields = array('template_name,template_subject,email_message,email_event');
                                    $match = array('email_event'=>'9');
                                    $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','',$db_name1);

                                    if(!empty($autores_res) && count($autores_res) > 0)
                                    {
                                        if(count($autores_res) > 1)
                                        {
                                            foreach($autores_res as $email_row)
                                            {
                                                if($email_row['template_name'] == 'Valuation Report')
                                                {
                                                    $email_temp = $email_row;
                                                }
                                            }
                                        }
                                        else {
                                            $email_temp = $autores_res[0];
                                        }
                                    } else {
                                        $email_temp = array();
                                    }

                                    if(!empty($email_temp['template_subject']))
                                        $subject = $email_temp['template_subject'];
                                    else
                                        $subject = 'Property Valuation Report - Livewire CRM';

                                    $table = $db_name1.".joomla_property_cron_trans as jpct";
                                    $fields = array('cm.id,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by,jpct.joomla_property_cron_master_id,jpct.contact_id','jpct.last_report_file','cm.first_name','cm.last_name','cet.email_address as email_id');
                                    $where = array('jpct.joomla_property_cron_master_id'=>$row['id'],'lu.email_for_valuation_set'=>1);
                                    $join_tables = array(
                                        $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                                        $db_name1.'.lead_users as lu'=>'lu.lw_id = jpct.contact_id'
                                    );
                                    $group_by='cm.id';

                                    $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                    //echo $db_name1;
                                    if(!empty($contact_data))
                                    {
                                        foreach($contact_data as $con)
                                        {
                                            $agent_name = '';$agent_datalist= array();
                                            if(!empty($con['created_by']))
                                            {
                                                $table = $db_name1.".login_master as lm";   
                                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                                $join_tables = array($db_name1.'.user_master as um'=>'lm.user_id = um.id');
                                                $wherestring = 'lm.id = '.$con['created_by'];
                                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                                if(!empty($agent_datalist))
                                                {
                                                    if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
                                                        $agent_name = $agent_datalist[0]['admin_name'];
                                                    else
                                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                                }
                                            }
                                            $data = array();$emaildata = array();
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

                                                if(!empty($emaildata) && count($emaildata) > 0)
                                                {
                                                    foreach($emaildata as $var => $value)
                                                    {
                                                        $map[sprintf($pattern, $var)] = $value;
                                                    }
                                                    $output = strtr($email_temp['email_message'], $map);
                                                    $data['temp_msg'] = $output;
                                                }
                                            } else {
                                                $data['temp_msg'] = '';
                                            }

                                            $filename = 'market_watch_monthly_'.date('mdYHis').'.pdf';
                                            $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                            $data['neighborhood'] = $row['neighborhood'];
                                            $data['city'] = $row['city'];
                                            $data['zip_code'] = $row['zip_code'];
                                            $data['radius'] = $row['radius_limit'];
                                            $data['admin_name'] = $admin_name;
                                            $data['admin_email'] = $admin_email;
                                            $data['admin_phone'] = $admin_phone;
                                            $data['admin_address'] = $admin_address;
                                            $data['brokerage_pic'] = $brokerage_pic;
                                            $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
                                            $mailto = $con['email_id'];

                                            // Save PDF file in folder and store data into table
                                            $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                            $mypdf->Output($this->config->item('base_path')."/uploads/attachment_file/".$filename,'F');

                                            $edata = array();
                                            ///// Mailgun Email 19-03-2015
                                            $edata['from_name'] = "Property Valuation Report";
                                            $edata['from_email'] = $admin_email;
                                            $edata['attachment'][] = array('attachment_name'=>$filename);

                                            if(!empty($message))
                                            {
                                                $response = $this->email_campaign_master_model->MailSend($mailto,$subject,$message,$edata);
                                                
                                                $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                                $pdf_data['contact_id'] = $con['id'];
                                                $pdf_data['last_report_file'] = $filename;
                                                $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                                $pdf_data['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                                $this->joomla_property_cron_model->update_task($pdf_data);

                                                $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];

                                                if(file_exists($pdfpath))
                                                { 
                                                    @unlink($pdfpath);
                                                }
                                                pr($mailto);
                                                //echo "Email Sent Successfully.";echo "<br />";

                                                $temppath = $this->config->item('base_path').'/uploads/attachment_file/'.$filename;
                                                if(file_exists($temppath))
                                                { 
                                                    @unlink($temppath);
                                                }
                                            }
                                        }
                                    } else {
                                        echo "Email preference not set."."<br />";
                                    }
                                    ///////// END PDF /////////
                                } else {
                                    echo "Property not found."."<br />";
                                }
                            } else {
                                echo "Result not found."."<br />";
                            }
                        }
                    } else {
                        echo "Market Watch not found";
                    }
                }
            }
        }
    }
    
    public function dbbackup()
    {
        //error_reporting(E_ALL);
        //echo "hello";
        //exec('mysqldump -u livewire -p livewire -h d6ff2f329eb5275cdd6de6652edead79053c6d0f.rackspaceclouddb.com --databases CRM | gzip > /var/www/vhosts/mylivewiresolution.com/database/dbbackup/CRM_all_db_14_july.sql.gz');
        //$command = 'mysqldump --opt -hlocalhost -uroot -pToPs@tops$$ topsin_livewire_crm_v2_0 login_master > CRM_all_db_14_july.sql';
        //exec($command,$output=array(),$worked);

        $command = 'mysqldump --opt -hlocalhost -uroot -pToPs@tops$$ topsin_livewire_crm_v2_0 nwmls_mls_property_list_master > nwmls_mls_property_list_master.sql';
        exec($command,$output=array(),$worked);
        //var_dump($output);
        echo $worked;
        //pr($output);exit;
        exit;
        //$command="mysqldump --host=localhost --user=topsin_u_2 --password=topsin_u_2 topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea | gzip > /public_html/crm/database/CRM_all_db_14_july.sql.gz";
        //shell_exec($command);
        echo "hiii";
//        var_dump($output);
//        echo $worked;
//        pr($output);
        exec('mysqldump --opt -hlocalhost -uroot -pToPs@tops$$ `topsin_livewire_crm_v2_0` login_master > CRM_all_db_14_july.sql');
        //echo "hiiii";
    }
}
