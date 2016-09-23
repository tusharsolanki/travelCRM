<?php
	include_once("../php/dbconfig.php");
	require_once('../swift_email/swift/swift_required.php');
	$chkflg=0;
	
	$db = new DBConnection();
	$db->getConnection();
	
	/* Function for doing Mail by swapnil Start */
	function domail($to, $from, $subject, $mesg)
	{
		/* Swift Email code Start */
		$transport = Swift_SmtpTransport::newInstance("mail.tops-tech.com", 26)
			->setUsername('test@tops-tech.com')
			->setPassword('tops123');
		
		$mailer = new Swift_Mailer($transport); // Create new instance of SwiftMailer
		$message = Swift_Message::newInstance()
			->setSubject($subject) // Message subject
			->setTo(array($to => $to)) // Array of people to send to
			->setFrom(array($from => "Senergy Efficiency")) // From:
			->setBody($mesg, 'text/html');// Attach that HTML message from earlier      
		
		$mailer->send($message);
		/* Swift Email code Ends */
	}
	/* Function for doing Mail by swapnil End */
	
	/* Function for getting USer Info by swapnil Start */
	function get_user_mail($id)
	{
		$qry="select * from `users` where `uid`=".$id;
		$res=mysql_query($qry);
		return mysql_fetch_array($res);
	}
	/* Function for getting User Info by swapnil End */
	
	/* Function for getting Doctor Info by swapnil Start */
	function get_doc_mail($id)
	{
		$qry="select * from `agent` where `agentid`=".$id;
		$res=mysql_query($qry);
		return mysql_fetch_array($res);
	}
	/* Function for getting Doctor Info by swapnil End */
	
	/*Function for getting a confirm code record Start */
	function appt_concode($con_code,$type)
	{
		$qry="SELECT accepted FROM appointment WHERE confirm_code='".$con_code."'";
		$res=mysql_query($qry);
		$app_status=mysql_fetch_assoc($res);
		
		if($type == 'acc')
		{
			if(count($app_status['accepted'])>0 )
			{
				if($app_status['accepted'] == 1)
				{
					return "accept"; //Appt accepted 
				}
				else
				{
					return "cur"; 	 	
				}
			}
			else
			{
				return "DR";
			}
		}
		else if($type == 'rej')
		{
			if(count($app_status['accepted'])>0)
			{
				return "rej_cur"; 
			}
			else
			{
				return "rej_DR";
			}
		}
	}
	/*Function for getting a confirm code record End */
	
	
	if(isset($_GET['flag']) && $_GET['flag']=='accept')
	{
		$c_code = $_GET['c_code'];
		
		$appt = appt_concode($c_code,'acc');
		
		if($appt == "cur")
		{
			$qry="SELECT * FROM appointment WHERE confirm_code='".$c_code."'";
			$res=mysql_query($qry);
			$info=mysql_fetch_array($res);
			
			$upd="update `appointment` set `accepted`=1 where `confirm_code`='".$c_code."'";
			mysql_query($upd);
			
			$res_user = get_user_mail($info['userid']);
			$res_doc = get_doc_mail($info['agentid']);
			
			$sdnt = explode(" ", $info['date']);
			$sdate = date("m-d-Y", strtotime($sdnt[0]));
			$stime = date("g:i a", strtotime($sdnt[1]));
			
			$to = $res_doc['email'];
			$from = $res_user['email'];
			$subject = "Accepted Appointment Request by ".$res_user['username'].".";
			include_once("../email/create_conform.php");// Mesg file for appoitnment with recurring
			domail($to, $from, $subject, $mesg);
		}
	}
		
	if(isset($_GET['flag']) && $_GET['flag']=='reject')
	{
		$userid = $_GET['userid'];
		$agentid = $_GET['agentid'];
		$c_code = $_GET['c_code'];
		
		$appt = appt_concode($c_code,'rej');
		
		if($appt == "rej_cur")
		{
			$qry="SELECT * FROM appointment WHERE confirm_code='".$c_code."'";
			$res=mysql_query($qry);
			$info=mysql_fetch_array($res);
			
			$del="delete from `appointment` where `confirm_code`='".$c_code."'";
			mysql_query($del);
			
			$del1="delete from `appointment` where `parent_id`='".$info['id']."'";
			mysql_query($del1);
			
			$res_user = get_user_mail($info['userid']);
			$res_doc = get_doc_mail($info['agentid']);
			
			$sdnt = explode(" ", $info['date']);
			$sdate = date("m-d-Y", strtotime($sdnt[0]));
			$stime = date("g:i a", strtotime($sdnt[1]));
			
			$ednt = explode(" ", $info['end_date']);
			$edate = date("m-d-Y", strtotime($ednt[0]));
			$etime = date("g:i a", strtotime($ednt[1]));
			
			$to = $res_doc['email'];
			$from = $res_user['email'];
			$subject = "Appointment Request Rejected by ".$res_user['username'].".";
			include_once("../email/create_conform.php");// Mesg file for appoitnment with recurring
			domail($to, $from, $subject, $mesg);
		}
	}
	header('Location:'.REDIRECT_PATH.$appt);
?>