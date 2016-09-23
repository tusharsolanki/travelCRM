<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class contact_sms_response1_control extends CI_Controller {



	function __construct()
	{
		parent::__construct();
		$this->load->model('sms_response_model');
		$this->load->model('admin_model');
		$this->obj = $this->sms_response_model;
	}

	function index()
	{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		//echo "hi";
		//pr($_REQUEST);
		$this->load->library('twilio');
		//$_REQUEST=array('Body'=>'Nice deal','To'=>'+15177988534','From'=>'+19408063387','FromCity'=>'CA','FromState'=>'CA','FromCountry'=>'CA','SmsStatus'=>'received');
		
		if(!empty($_REQUEST['To']))
		{
			$orig_request_to = $_REQUEST['To'];
			$_REQUEST['To']=ltrim ($_REQUEST['To'],'+1');		
			$_REQUEST['To'] = substr($_REQUEST['To'],0,3).'-'.substr($_REQUEST['To'],3,3).'-'.substr($_REQUEST['To'],6,4);
		}

		
		if(!empty($_REQUEST))
		{
			$data['from_number']=!empty($_REQUEST['From'])?$_REQUEST['From']:'';
			$data['to_number']=!empty($_REQUEST['To'])?$_REQUEST['To']:'';
			$data['message']=!empty($_REQUEST['Body'])?$_REQUEST['Body']:'';
			$data['from_city']=!empty($_REQUEST['FromCity'])?$_REQUEST['FromCity']:'';
			$data['from_state']=!empty($_REQUEST['FromState'])?$_REQUEST['FromState']:'';
			$data['from_country']=!empty($_REQUEST['FromCountry'])?$_REQUEST['FromCountry']:'';
			$data['sms_staus']=!empty($_REQUEST['SmsStatus'])?$_REQUEST['SmsStatus']:'';
			$data['response_date']=date('Y-m-d H:i:s');
			
			//pr($data);

			$parent_db=$this->config->item('parent_db_name');
			$match=array('twilio_number'=>$_REQUEST['To']);
			$user = $this->admin_model->get_user('',$match,'','=','','','','','','',$parent_db);
			
			//pr($user);

			$parent_db=$this->config->item('parent_db_name');
			$match=array('twilio_number'=>$orig_request_to);
			$user1 = $this->admin_model->get_user('',$match,'','=','','','','','','',$parent_db);

			//exit;
			//Insert response
			if(!empty($user))
			{
				$db_name=$user[0]['db_name'];
				$resid=$this->obj->insert_record($data,$db_name);
			}
			elseif(!empty($user1))
			{
				$db_name=$user1[0]['db_name'];
				$resid=$this->obj->insert_record($data,$db_name);
			}


			//forward sms
			if(!empty($user[0]['phone']))
			{
				/*$twilio_account_sid = $user[0]['twilio_account_sid'];
				$twilio_auth_token  = $user[0]['twilio_auth_token'];
				$twilio_number		= $user[0]['twilio_number'];
				$db_name = $this->config->item('parent_db_name');
				$this->twilio->set_admin_id($user[0]['id'],$db_name);
				//$client =new Services_Twilio($twilio_account_sid, $twilio_auth_token);
				if (!empty($_REQUEST['Body']))
				{
					//echo 1;exit;
				 	//$client->account->messages->sendMessage($twilio_number,'+919909158975', 'New message from: ' .$from_number .'Contents: ' .$msg_body);
				 	$response = $this->twilio->sms($twilio_number,'+919909158975','From: ' .$_REQUEST['From'] .' Message: ' .$_REQUEST['Body']);
				 	pr($response);exit;
				}*/

				//header('Content-Type: text/html');
				?>
				<Response>
				  <Message to="<?=$user[0]['phone']?>">
						<?=htmlspecialchars(' From :'.$_REQUEST['From'].' '.$_REQUEST['Body'])?>
				  </Message>
				</Response> 
			<? 
			}
			elseif(!empty($user1[0]['phone']))
			{
				/*$twilio_account_sid = $user[0]['twilio_account_sid'];
				$twilio_auth_token  = $user[0]['twilio_auth_token'];
				$twilio_number		= $user[0]['twilio_number'];
				$db_name = $this->config->item('parent_db_name');
				$this->twilio->set_admin_id($user[0]['id'],$db_name);
				//$client =new Services_Twilio($twilio_account_sid, $twilio_auth_token);
				if (!empty($_REQUEST['Body']))
				{
					//echo 1;exit;
				 	//$client->account->messages->sendMessage($twilio_number,'+919909158975', 'New message from: ' .$from_number .'Contents: ' .$msg_body);
				 	$response = $this->twilio->sms($twilio_number,'+919909158975','From: ' .$_REQUEST['From'] .' Message: ' .$_REQUEST['Body']);
				 	pr($response);exit;
				}*/

				//header('Content-Type: text/html');
				?>
				<Response>
				  <Message to="<?=$user1[0]['phone']?>">
						<?=htmlspecialchars(' From :'.$_REQUEST['From'].' '.$_REQUEST['Body'])?>
				  </Message>
				</Response> 
			<? 
			}
			
			//echo $this->db->last_query();exit;
			if(!empty($resid))
			{
				?>
				<!-- <Response>
					<Message>Thank you.</Message>
				</Response>	 -->
			<?
			}
		}
		
	}
}



/* End of file twilio_demo.php */