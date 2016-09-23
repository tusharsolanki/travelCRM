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
		//sms model
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_texts_model');
		$this->load->model('module_master_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('joomla_assign_model');
		
		$this->load->model('joomla_property_cron_model');
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
		$db_name = $this->config->item('parent_db_name');
		$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
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
						/*echo $this->db->last_query()."<br>";
						pr($sms_camp_list);exit;*/
						if(!empty($sms_camp_list))
						{
							for($b=0;$b<count($sms_camp_list);$b++)				
							{
								if(empty($sms_camp_list[$b]['interaction_id1']) || (!empty($sms_camp_list[$b]['interaction_id1']) && $sms_camp_list[$b]['is_done'] == '1'))
								{
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
										$cdata['is_send'] = '0';
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
										/*$icdata['interaction_plan_interaction_id'] = $interaction_id;
										$icdata['contact_id'] = $sms_camp_list[$b]['contact_id'];
										$icdata['task_completed_date'] = date('Y-m-d H:i:s');
										$icdata['completed_by'] = $created_by;
										$icdata['is_done']='1';
										$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata);
										unset($icdata);*/									}
									
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
						//pr($email_camp_list);exit;
						if(!empty($email_camp_list))
						{
							for($b=0;$b<count($email_camp_list);$b++)				
							{
								if(empty($email_camp_list[$b]['interaction_id1']) || (!empty($email_camp_list[$b]['interaction_id1']) && $email_camp_list[$b]['is_done'] == '1'))
								{
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
										$data['from_name'] = $from_name;
										
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
											$cdata['is_send'] = '0';
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
													$message1 = '<a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
													$message = str_replace('{(my_unsubscribe_link)}',$message1,$message);
												}
												//mail($to,$subject,'',"-f".$message);
												$this->obj->MailSend($to,$subject,$message,$data);
											}
											
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
										$icdata['interaction_plan_interaction_id'] = $interaction_id;
										$icdata['contact_id'] = $email_camp_list[$b]['contact_id'];
										$icdata['task_completed_date'] = date('Y-m-d H:i:s');
										$icdata['completed_by'] = $created_by;
										$icdata['is_done']='1';
										$this->contacts_model->update_interaction_plan_interaction_transtrans_record('',$icdata);
										unset($icdata);
                                                                                
//                                                                                $single_id = $this->interaction_model->get_interaction_plan_interaction_trans_record($icdata);
//                                                                                echo $single_id;
//                                                                                if(!empty($single_id))
//                                                                                    common_rescheduled_task($single_id);

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
								//$from = "nishit.modi@tops-int.com";
								$headers = 'MIME-Version: 1.0'."\r\n";
								$headers .= "From: ".$from." <".$to.">\r\n";
								$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								//mail($to,$subject,$message,"-f".$from);
								$headers .= $message."\r\n\r\n";
								mail($to,$subject,'',"-f".$headers);
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
								//$from1 = "nishit.modi@tops-int.com";
								$headers1 = 'MIME-Version: 1.0'."\r\n";
								
								$headers1 .= "From: ".$from." <".$from_email.">\r\n";
								$headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								//mail($to,$subject,$message,"-f".$from);
								$headers1 .= $message1."\r\n\r\n";
								/*echo "<br>";
								echo $headers1;
								echo "<br>";
								echo $headers1;
								echo "<br>";   //update `task_user_transcation` set is_mail_sent= 0
								echo $subject1;
								echo "<br>";
								echo $message1;*/
								mail($to1,$subject1,$message1,"-f".$headers1);
								//$headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								//mail($to1,$subject1,$message1,$headers1,"-f".$from1);
								//mail($to1,$subject1,$message1,$headers1,$from1);
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
        @Description: Function for send email with pdf according to property cron setting (Weekly)
        @Author     : Sanjay Moghariya
        @Input      : State, City, neighborhood
        @Output     : Send Email with pdf (data fetch from zillow api)
        @Date       : 20-11-14
    */
    public function get_neighborhood_data_weekly()
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
                    //$fields = array('jpcm.*,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address');
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Weekly');
                    /*$join_tables = array(
                        $db_name1.'.joomla_property_cron_trans as jpct' => 'jpcm.id = jpct.joomla_property_cron_master_id',
                        $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                    );
                    $group_by='cm.id';
                    $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
                    */
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $state = $row['state'];
                            $city = $row['city'];
                            $neighborhood = $row['neighborhood'];
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
                                
                                ///////// PDF GENERATE CODE /////////

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

                                $filename = 'neighborhood_data_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                $from_mail = $this->config->item('admin_email');
                                //$replyto = 'demotops@gmail.com';
                                
                                $subject = 'Property Valuation Report - Livewire CRM';
                                
                                $table = "joomla_property_cron_trans as jpct";
                                $fields = array('cm.id,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address as email_id');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    'contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $uid = md5(uniqid(time()));
                                        $name = $con['contact_name'];
                                        $mailto = $con['email_id'];
                                        $message = 'Dear '.ucwords($name).',<br /><br />';
                                        $message = 'Please find attached pdf file which showing property valuation neighborhood data based on following criteria:';
                                        $message .= '<br />State: '.$state;
                                        $message .= '<br />City: '.$city;
                                        $message .= '<br />Neighborhood: '.$neighborhood.'<br />';

                                        $header = "From: ".$from_name." <".$from_mail.">\r\n";
                                        //$header .= "Reply-To: ".$replyto."\r\n";
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
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                    }
                                }
                                //$mypdf->Output();
                                
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
    
    public function get_valuation_data()
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
                    //$fields = array('jpcm.*,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address');
                    $fields = array('jpcm.*');
                    $where = array('jpcm.cron_type'=>'Weekly');
                    /*$join_tables = array(
                        $db_name1.'.joomla_property_cron_trans as jpct' => 'jpcm.id = jpct.joomla_property_cron_master_id',
                        $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                        '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                    );
                    $group_by='cm.id';
                    $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
                    */
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            $state = $row['state'];
                            $city = $row['city'];
                            //$address = $row['neighborhood'];
                            $address = '2114 Bigelow Ave';
                            $url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b7njhnhvkb_6r0dc&address=".$address."&citystatezip=".$city.",".$state;
                            $xml = simplexml_load_file($url);
                            
                            $code = $xml->message->code;
                            $prop_val = array();
                            $bathroom=0;$bedroom=0;$propertytype='';$sqfeet=0;$zipcode=0;
                            $normalvalue = '';$lowvalue = '';$highvalue = '';
                            
                            if($code =="0")
                            {
                                echo "Bath: ",$bathroom = $xml->response->results->result->bathrooms;
                                echo "Bath: ", $bedroom = $xml->response->results->result->bedrooms;
                                echo "Sq Ft: ", $finished_sq_ft = $xml->response->results->result->finishedSqFt;
                                echo "Type: ",$property_type = $xml->response->results->result->useCode;
                                echo "Normal: ",$normal_val = $xml->response->results->result->zestimate->amount;
                                echo "Low: ",$lowValue = $xml->response->results->result->zestimate->valuationRange->low;
                                echo "High: ",$highvalue = $xml->response->results->result->zestimate->valuationRange->high;
                                exit;
                                foreach($xml->children() as $child)
                                {
                                    foreach($child->children() as  $value)
                                    {
                                        foreach($value->children() as $values)
                                        {
                                            foreach($values->children() as  $key1 => $value1)
                                            {
                                                if($key1 == 'bathrooms')
                                                    $prop_val['bathroom'] = $value1;
                                                if($key1 == 'bedrooms')
                                                    $prop_val['bedroom'] = $value1;
                                                if($key1 == 'useCode')
                                                    $prop_val['property_type'] = $value1;
                                                if($key1 == 'finishedSqFt')
                                                    $prop_val['sq_feet'] = $value1;

                                                foreach($value1->children() as $key=> $value2)
                                                {
                                                    if($key == 'zipcode')
                                                        $prop_val['zipcode'] = $value2;
                                                    if($key == 'amount')
                                                        $prop_val['normalvalue'] = $value2;

                                                    foreach($value2->children() as $keylow=>$val)
                                                    {
                                                        if($keylow == 'low')
                                                            $prop_val['lowvalue'] = $val;
                                                        if($keylow == 'high')
                                                            $prop_val['highvalue'] = $val;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            //exit;
                            pr($prop_val);
                            exit;
                            
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
                                
                                ///////// PDF GENERATE CODE /////////

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

                                $filename = 'neighborhood_data_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
                                $from_mail = $this->config->item('admin_email');
                                //$replyto = 'demotops@gmail.com';
                                $uid = md5(uniqid(time()));
                                $subject = 'Property Valuation Report - Livewire CRM';
                                $message = 'Please find attached pdf file which showing property valuation neighborhood data based on following criteria:';
                                $message .= '<br />State: '.$state;
                                $message .= '<br />City: '.$city;
                                $message .= '<br />Neighborhood: '.$neighborhood.'<br />';

                                $header = "From: ".$from_name." <".$from_mail.">\r\n";
                                //$header .= "Reply-To: ".$replyto."\r\n";
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
                                
                                
                                $table = "joomla_property_cron_trans as jpct";
                                $fields = array('cm.id,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address as email_id');
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    'contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $name = $con['contact_name'];
                                        $mailto = $con['email_id'];
                                        $message = 'Dear '.ucwords($name).',<br /><br />';
                                        $message = 'Please find attached pdf file which showing property valuation neighborhood data based on following criteria:';
                                        $message .= '<br />State: '.$state;
                                        $message .= '<br />City: '.$city;
                                        $message .= '<br />Neighborhood: '.$neighborhood.'<br />';

                                        $header = "From: ".$from_name." <".$from_mail.">\r\n";
                                        //$header .= "Reply-To: ".$replyto."\r\n";
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
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                    }
                                }
                                //$mypdf->Output();
                                
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
        @Description: Function for send email with pdf according to property cron setting (Weekly) (Value Watcher)
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
                    //pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            //$addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['state'].", ".$row['country']);
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['zip_code']);
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
                                    else {
                                        $pdata['property_name'][] = '';
                                        $pdata['property_description'][] = '';
                                        $pdata['price'][] = '';
                                    }
                                }
                                $pdata['neighbor_address'] = urldecode($addr);
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
                                $mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;

                                $mypdf->WriteHTML($html);

                                $filename = 'value_watcher_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
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
                                        
                                        $filename = 'value_watcher_weekly_'.strtotime(date('m-d-Y H:i:s')).'.pdf';
                                        //pr($con);
                                        //echo "<br /><br />";echo pr($data['temp_msg']);echo "<br /><br />";
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        $data['city'] = $row['city'];
                                        $data['zip_code'] = $row['zip_code'];
                                        $data['radius'] = $row['radius_limit'];
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
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
                                        //$mailto = 'sanjay.moghariya@tops-int.com';
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                        //$is_sent = 1;
                                        if($is_sent) {
                                            // Save PDF file in folder and store data into table
                                            $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                            $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                            $pdf_data['contact_id'] = $con['id'];
                                            $pdf_data['last_report_file'] = $filename;
                                            $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                            $this->joomla_property_cron_model->update_task($pdf_data);
                                            
                                            $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];
                                            
                                            if(file_exists($pdfpath))
                                            { 
                                                @unlink($pdfpath);
                                            }
                                            pr($mailto);
                                            echo "Email Sent Successfully.";echo "<br />";
                                        } else {
                                            pr($mailto);
                                            echo "Email Not Send";echo "<br />";
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
        @Description: Function for send email with pdf according to property cron setting (Weekly)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Send Email with pdf
        @Date       : 28-11-2014
    */
    function get_valuation_cron_monthly()
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
                    $where = array('jpcm.cron_type'=>'Monthly');
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    //pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            //$addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['state'].", ".$row['country']);
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['zip_code']);
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
                                    else {
                                        $pdata['property_name'][] = '';
                                        $pdata['property_description'][] = '';
                                        $pdata['price'][] = '';
                                    }
                                }
                                $pdata['neighbor_address'] = urldecode($addr);
                                
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
                                $mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Valuation Report</div>', 'O', true);
                                //$mypdf->SetHTMLFooter('<table border="0" cellpadding="0" ><tr><td class="footer">Valuation Report</td><td class="footer1"></td></tr></table>', 'O', true);
                                
                                $html .= $pdf_html;

                                $mypdf->WriteHTML($html);

                                $filename = 'value_watcher_'.date('m-d-Y').'.pdf';
                                $content = $mypdf->Output('', 'S');
                                //$content = $mypdf->Output($filename, 'D');
                                //exit;
                                $content = chunk_split(base64_encode($content));
                                
                                $from_name = 'LiveWireCRM';
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
                                $where = array('jpct.joomla_property_cron_master_id'=>$row['id']);
                                $join_tables = array(
                                    $db_name1.'.contact_master as cm'=>'cm.id = jpct.contact_id',
                                    '(SELECT cetin.* FROM '.$db_name1.'.contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
                                );
                                $group_by='cm.id';

                                $contact_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.id','desc',$group_by);
                                //echo $db_name1;
                                //pr($contact_data);
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
                                        $filename = 'value_watcher_monthly_'.strtotime(date('m-d-Y H:i:s')).'.pdf';
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        //$data['country'] = $row['country'];
                                        $data['city'] = $row['city'];
                                        $data['zip_code'] = $row['zip_code'];
                                        //$data['state'] = $row['state'];
                                        $data['radius'] = $row['radius_limit'];
                                        
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);
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
                                        //$mailto = 'sanjay.moghariya@tops-int.com';
                                        $is_sent = mail($mailto, $subject, "", "-f".$header);
                                        //$is_sent = 1;
                                        if($is_sent) {
                                            // Save PDF file in folder and store data into table
                                            $mypdf->Output($this->config->item('base_path')."/uploads/valuation_pdf_file/".$filename,'F');
                                            $pdf_data['joomla_property_cron_master_id'] = $row['id'];
                                            $pdf_data['contact_id'] = $con['id'];
                                            $pdf_data['last_report_file'] = $filename;
                                            $pdf_data['last_report_date'] = date('Y-m-d H:i:s');
                                            $this->joomla_property_cron_model->update_task($pdf_data);
                                            
                                            $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$con['last_report_file'];
                                            
                                            if(file_exists($pdfpath))
                                            { 
                                                @unlink($pdfpath);
                                            }
                                            pr($mailto);
                                            echo "Email Sent Successfully.";echo "<br />";
                                        } else {
                                            pr($mailto);
                                            echo "Email Not Send";echo "<br />";
                                        }
                                    }
                                }
                                else
                                    echo "Result not found.<br />";
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
    
    function get_valuation_cron_monthly_old_29_12_2014()
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
                    $where = array('jpcm.cron_type'=>'Monthly');
                    $cron_data = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'=','','','jpcm.id','desc','');
                    //pr($cron_data);exit;
                    if(!empty($cron_data))
                    {
                        foreach($cron_data as $row)
                        {
                            // Get property listing based on neighborhood, city, state, country and radius
                            //$addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['state'].", ".$row['country']);
                            $addr = urlencode($row['neighborhood'].", ".$row['city'].", ".$row['zip_code']);
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
//pr($response);exit;
                            if(!empty($response['data']))
                            {
                                $pdata = array();
                                foreach($response['data'] as $property_data)
                                {
                                    // Get property details from property id
                                    $url = "http://seattle.livewiresites.com/libraries/api/propertydata.php?id=".$property_data['id'];
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
                                //$replyto = 'demotops@gmail.com';
                                
                                $email_temp = array();
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
                                //echo $this->db->last_query();
                                //pr($contact_data);exit;
                                if(!empty($contact_data))
                                {
                                    foreach($contact_data as $con)
                                    {
                                        $agent_name = '';
                                        if(!empty($con['created_by']))
                                        {
                                            $table =$db_name1.".login_master as lm";   
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
                                        $data['contact_name'] = ucwords($con['first_name'].' '.$con['last_name']);
                                        $data['neighborhood'] = $row['neighborhood'];
                                        //$data['country'] = $row['country'];
                                        $data['city'] = $row['city'];
                                        $data['zip_code'] = $row['zip_code'];
                                        //$data['state'] = $row['state'];
                                        $data['radius'] = $row['radius_limit'];
                                        
                                        $message = $this->load->view('ws/valuation_report_email', $data, TRUE);  
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
                                        pr($mailto);
                                        if($is_sent)
                                            echo "Email Sent Successfully.<br />";
                                        else
                                            echo "Email Not Send.<br />";
                                    }
                                }
                                else
                                    echo "Result not found.<br />";
                                //$mypdf->Output();
                                //echo "Email Sent Successfully.";
                                ///////// END PDF /////////
                            }
                            else
                            {
                                echo "Result not found.<br />";
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
            set_time_limit(0);
            $parent_db=$this->config->item('parent_db_name');
            $table=$parent_db.'.login_master as l';
            $fields = array('l.id,l.admin_name,l.email_id,l.db_name,l.timezone');
            $group_by='l.id';
            $where = "l.user_type = 2";
            $admin_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);
            
            //$merge_db = array('0'=>array('id'=>'','db_name'=>$parent_db,'host_name'=>'','db_user_name'=>'','db_user_password'=>''));
            //$admin_list = array_merge($admin_list,$merge_db);

            if(!empty($admin_list))
            {
                foreach($admin_list as $dbrow)
                {
                    $db_name = $dbrow['db_name'];
                    
                    if(!empty($dbrow['timezone']))
                        $timezone = $dbrow['timezone'];
                    else
                        $timezone = $this->config->item('default_timezone');
                    
                    date_default_timezone_set($timezone);
                    $dt = date('Y-m-d H:i:s');
                    //echo $dt;
                    $table = $db_name.".contact_master as cm";
                    $match = array('cm.status'=> '1','cm.created_type'=>'6');//,"DATE_FORMAT(cm.created_date, '%Y-%m-%d')" => $arcdt);
                    //$match_str = 'cm.created_date >= date_sub(CONVERT_TZ(now(),"UTC","'.$timezone.'"), interval 50 MINUTE) AND cm.created_date < CONVERT_TZ(NOW(),"UTC","'.$timezone.'")';
                    //$match_str = 'cm.created_date >= date_sub(now(), interval 900 MINUTE) AND cm.created_date < NOW()';
                    $match_str = 'cm.created_date >= date_sub("'.$dt.'", interval 5 MINUTE) AND cm.created_date < "'.$dt.'"';
                    

                    $new_contact_list = $this->contact_masters_model->getmultiple_tables_records($table,'','','','',$match,'=','','','','','',$match_str);
                    //if($db_name == 'topsin_livewire_crm_4bf586397b41a7b6bc02886076336fb7') {
                        //echo $this->db->last_query();echo "<br />";
                        //pr($new_contact_list);exit;
                    //} else {
                    //    continue;
                    //}
                    
                    $i = 0;
                    if(!empty($new_contact_list))
                    {
                        foreach($new_contact_list as $row)
                        {
                            //pr($row);exit;
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
                                //$cdata['price_range_from'] = $row['price_range_from'];
                                //$cdata['price_range_to'] = $row['price_range_to'];
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
                            /*echo $this->db->last_query();
                            pr($wuser_list);
                            exit;*/
                            if(!empty($wuser_list))
                            {
                                $all_user_list = '';
                                //pr($user_list);exit;
                                foreach($wuser_list as $aurow) // Get agent id only
                                {
                                    $all_user_list .= $aurow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                $uwhere_in = array('um.id'=>$all_user_list);

                                // Check for price agent which is within price range
                                if(!empty($price_flag))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','',$uwhere_in,'',$min_price,$max_price,'1');
                                    //echo $this->db->last_query();
                                    //pr($user_list);
                                    //exit;
                                }
                                //pr($user_list);exit;
                                // Check this if price range found with area criteria
                                if(!empty($user_list) && !empty($area_flag))
                                {
                                    $old_user_list = $user_list;
                                    //pr($old_user_list);exit;
                                    $all_user_list = '';
                                    //pr($user_list);exit;
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
                                    //echo $this->db->last_query();
                                    //pr($user_list);
                                    //exit;
                                }
                                if(empty($user_list))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>$agent_type);
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                                }
                                /*if($row['id'] == 615) {
                                    echo $this->db->last_query();
                                    pr($user_list);
                                    exit;
                                }*/
                                /*echo $this->db->last_query();
                                pr($user_list);
                                exit;*/
                                if(!empty($user_list))
                                {
                                    $table = $db_name.".user_rr_weightage_trans";
                                    $fields = array('user_id,user_weightage,round,round_value');
                                    $match = array('agent_type'=>$agent_type);
                                    $rr_last_weightage = $this->contact_masters_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','','','id','desc');
                                    //echo $this->db->last_query();
                                    //pr($rr_last_weightage);exit;
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
                                        //pr($all_user_list);exit;
                                        $this->session->unset_userdata('assigned_contact_session');
                                        //Call function for assign contact to admin
                                        $rr_user_list = $this->new_rr_user_id($all_user_list,$user_list,$db_name);

                                        if(!empty($rr_user_list))
                                        {
                                            $data['contact_id'] = $row['id'];
                                            $data['user_id'] = $rr_user_list[0]['id'];
                                            $data['agent_type'] = $agent_type;
                                            //$data['created_by'] = $post_data['user_id'];
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
                                        }

                                    }
                                    else // First Time
                                    {
                                        $data['contact_id'] = $row['id'];
                                        $data['user_id'] = $user_list[0]['id'];
                                        $data['agent_type'] = $agent_type;
                                        //$data['created_by'] = $post_data['user_id'];
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
                                        //echo $this->db->last_query();exit;
                                        //break;
                                    }
                                    echo "Contact Id: ".$data['contact_id']. " Agent: ".$data['user_id']."<br />";
                                } else {
                                    echo "Agent not assigned..!! <br />";
                                }
                            } else {
                                echo "Agent not assigned..!! <br />";
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
                            /*echo $this->db->last_query();
                            pr($lwuser_list);
                            exit;*/
                            /*if($row['id'] != 1262)
                                continue;
                            */
                            $uwhere_in = array();
                            if(!empty($lwuser_list))
                            {
                                $user_list = array();$rr_last_weightage = array();$rr_user_list = array();$new_rr_data = array();
                                $data = array();$rr_data = array();

                                $all_user_list = '';
                                //pr($user_list);exit;
                                foreach($lwuser_list as $aurow) // Get lender id only
                                {
                                    $all_user_list .= $aurow['id'].',';
                                }
                                $all_user_list = trim($all_user_list,',');
                                $uwhere_in = array('um.id'=>$all_user_list);

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
                                if(empty($user_list))
                                {
                                    $match = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
                                    $user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','um.user_weightage','desc','um.id','','','','','','','','','1');
                                }
                                //echo $area_flag;
                                /*if($row['id'] == 1262) {
                                    echo $this->db->last_query();
                                    pr($user_list);
                                    exit;
                                }*/

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
            $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
            $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                    $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $where_in = array('um.id'=>$all_user_list);
                    $rr_last_round_val_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                    //echo $this->db->last_query();
                    //pr($rr_last_round_val_sort);exit;
                    $round_value = !empty($rr_last_round_val_sort[0]['min_round_value'])?$rr_last_round_val_sort[0]['min_round_value']:0;
                    
                    $table = $db_name.".user_master as um";
                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                    $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                            $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $where_in = array('um.id'=>$all_user_list);
                            $rr_max_weightage_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                            //echo $this->db->last_query();
                            //pr($rr_last_round_val_sort);exit;
                            $cur_weightage = $rr_max_weightage_sort[0]['max_weightage_value'];
                            
                            
                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                            $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                                    $join_tables = array('user_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
            $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
            $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                    $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                    $where_in = array('um.id'=>$all_user_list);
                    $rr_last_round_val_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                    //echo $this->db->last_query();
                    //pr($rr_last_round_val_sort);exit;
                    $round_value = !empty($rr_last_round_val_sort[0]['min_round_value'])?$rr_last_round_val_sort[0]['min_round_value']:0;
                    
                    
                    
                    
                    $table = $db_name.".user_master as um";
                    $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                    $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                            $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
                            $where_in = array('um.id'=>$all_user_list);
                            $rr_max_weightage_sort = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','','','',$where_in);
                            //echo $this->db->last_query();
                            //pr($rr_last_round_val_sort);exit;
                            $cur_weightage = $rr_max_weightage_sort[0]['max_weightage_value'];
                            
                            
                            $table = $db_name.".user_master as um";
                            $fields = array('um.id,um.user_weightage,um.minimum_price,um.maximum_price,urwt.round,urwt.round_value');
                            $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
                                    $join_tables = array('lender_rr_weightage_trans as urwt' => 'um.id = urwt.user_id');
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
		
		/*---------Send sms------------*/
		
		if(!empty($all_admin1))
		{
			foreach($all_admin1 as $row)
			{
				$db_name1 = $row['db_name'];
				if(!empty($db_name1)){
//                                    echo "<br>".$db_name1."<br>";
//                                    echo "W :".shell_exec("whoami")."<br>";
//                                    echo "mysqldump -u root -p ".$db_name1." > /var/www/html/livewire_crm_2/trunk/database/backup_19feb15/".$db_name1.".sql";
//                                    $op = shell_exec("mysqldump --user='root' --password='' --host='localhost' ".$db_name1." > /var/www/html/livewire_crm_2/trunk/database/backup_19feb15/".$db_name1.".sql");
//                                    echo $op;
                                    
                                      $this->backup_tables($row['host_name'],$row['db_user_name'],$row['db_user_password'],$db_name1);
                                    
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
		set_time_limit(0);
		//error_reporting(0);
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
						$data[$i]["LONG"]                       =!empty($row["LONG"])?$row["LONG"]:"";
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
						$data[$i]["ASC"]                        =!empty($row["ASC"])?$row["ASC"]:"";
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
}
