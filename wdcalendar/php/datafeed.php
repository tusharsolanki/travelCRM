<?php

$system_path = 'system';

if (realpath($system_path) !== FALSE)

{

	$system_path = realpath($system_path).'/';

}

$system_path = rtrim($system_path, '/').'/';

define('BASEPATH', str_replace("\\", "/", $system_path));

include_once("../../application/config/database.php");

include_once("../../application/config/config.php");

$hostname =  $db['default']['hostname'];

$database =  $db['default']['database'];

$username =  $db['default']['username'];

$password =  $db['default']['password'];

include_once("dbconfig.php");

include_once("functions.php");



$db = new DBConnection();

$db->getConnection($hostname,$database,$username,$password);

require_once('../swift_email/swift/swift_required.php');

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

/* Function for getting Conformation Code by Swapnil Start */
function randr($j = 8)
{
	$string = "";
	for($i=0;$i < $j;$i++)
	{
		srand((double)microtime()*1234567);
		$x = mt_rand(0,2);
		switch($x)
		{
			case 0:$string.= chr(mt_rand(97,122));break;
			case 1:$string.= chr(mt_rand(65,90));break;
			case 2:$string.= chr(mt_rand(48,57));break;
		}
	}
	return strtoupper($string); //to uppercase
}
/* Function for getting Conformation Code by Swapnil End */

function addCalendar($st, $et, $sub){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "insert into `appointment` (`reason`, `date`, `end_date`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .php2MySqlTime(js2PhpTime($st))."', '"
      .php2MySqlTime(js2PhpTime($et))."' )";
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Appointment Created';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function addDetailedCalendar($title, $date, $end_date,$type, $reason, $notes, $c_code, $erem, $appconf, $tz)
{
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
		
		$sql = "insert into `appointment`(`title`, `date`, `end_date`,`type`, `reason`, `notes`, `confirm_code`, `email_reminder`, `accepted`) values ('"
		.mysql_real_escape_string($title)."', '"
		.php2MySqlTime(js2PhpTime($date))."', '"
		.php2MySqlTime(js2PhpTime($end_date))."', '"
		.mysql_real_escape_string($type)."', '"
		.mysql_real_escape_string($reason)."', '"
		.mysql_real_escape_string($notes)."', '"
		.mysql_real_escape_string($c_code)."', '"
		.mysql_real_escape_string($erem)."', '"
		.mysql_real_escape_string($appconf)."')";
		/* For Inserting record in doc_pat for just First Time by Swapnil End */
			
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
			$ret['Data'] = mysql_insert_id();
			
      $ret['IsSuccess'] = true;
			$ret['Msg'] = 'Appointment Created';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function add_agent($id, $agent){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
		
		$sql = "SELECT * FROM appointment WHERE id=".$id;
    $handle = mysql_query($sql);
		$res_uid = mysql_fetch_array($handle);
		
		$sql = "update `appointment` set"
		. " `accepted`='1', "
		. " `agentid`='" . mysql_real_escape_string($agent) . "' "
		. "where `id`=" . $id;
				
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
			/* Code for sending Mail Starts */
			$sarr = explode(" ", php2JsTime(mySql2PhpTime($res_uid['date'])));
			$earr = explode(" ", php2JsTime(mySql2PhpTime($res_uid['end_date'])));
			
			$sdate = date("M j, Y", strtotime($sarr[0]));
			$stime = date("g:i a", strtotime($sarr[1]));
			
			//$edate = date("M j, Y", strtotime($earr[0]));
			$etime = date("g:i a", strtotime($earr[1]));
			
			$userid = $res_uid['userid'];
			$agentid = $agent;
			
			$res_user = get_user_mail($userid);
			$res_doc = get_doc_mail($agentid);
			
			$username = $res_user['username'];
			$agentname = $res_doc['agentname'];
			
			include_once("../email/allo_user_email.php");// Mesg file for User
			include_once("../email/allo_agent_email.php");// Mesg file for Agent
			
			$to = $res_user['email'];
			$from = "info@sEnergyEfficiency.com";
			$subject = "Appointment has been confirmed and Allocated to ".$res_doc['agentname'];
			
			$to1 = $res_doc['email'];
			$from1 = "info@sEnergyEfficiency.com";
			$subject1 = "You have an Appointment of ".$res_user['username'];
			
			domail($to, $from, $subject, $mesg);
			domail($to1, $from1, $subject1, $mesg1);
			/* Code for sending Mail Ends */
      $ret['IsSuccess'] = true;
		  $ret['Msg'] = 'Agent Allocated, Email Sent to User and Agent Successfully';
    }
	}
	catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function updateCalendar($id, $st, $et){
	$db = new DBConnection();
	$db->getConnection();
	
	$get="SELECT * FROM appointment WHERE id=".$id;
	$get_uid=mysql_query($get) or die(mysql_error());
	$res_uid=mysql_fetch_array($get_uid);
	
	$stdt=php2MySqlTime(js2PhpTime($st));
	//$et=php2MySqlTime(js2PhpTime($et));
	$agentid = $res_uid['agentid'];
	$userid = $res_uid['userid'];
	
	$sel="SELECT * FROM appointment WHERE '".$stdt."' between date AND end_date";
	$res=mysql_query($sel) or die(mysql_error());
	$row=mysql_fetch_array($res);
	$num=mysql_num_rows($res);
	
	if($row['id']!=$id)
	{
		if($num>0)
			$tab=0;
		else
			$tab=1;
	}
	else
	{
		$tab=1;
	}
	
	$c_code=randr(8);
  $ret = array();
  
	if($tab==1)
	{
		try{
			$sql = "update `appointment` set"
				. " `date`='" . php2MySqlTime(js2PhpTime($st)) . "', "
				. " `end_date`='" . php2MySqlTime(js2PhpTime($et)) . "' "
				. "where `id`=" . $id;
			
			if(mysql_query($sql)==false){
				$ret['IsSuccess'] = false;
				$ret['Msg'] = mysql_error();
			}else{
				$msg="Appointment Rescheduled";
				
				if($agentid!=0 && $userid!=0)
				{
					/* Code for sending Mail Starts */
					$res_user=get_user_mail($userid);
					$res_doc=get_doc_mail($agentid);
					
					$sdnt = explode(" ", $st);
					$sdate = date("m-d-Y", strtotime($sdnt[0]));
					$stime = date("g:i a", strtotime($sdnt[1]));
					
					$olddnt = explode(" ", $res_uid['date']);
					$olddate = date("m-d-Y", strtotime($olddnt[0]));
					$oldtime = date("g:i a", strtotime($olddnt[1]));
					
					$username = $res_user['username'];
					$agentname = $res_doc['agentname'];
					
					include_once("../email/reshedule_user_email.php");// Mesg file for User
					include_once("../email/reshedule_agent_email.php");// Mesg file for Agent
					
					$to = $res_user['email'];
					$from = "info@sEnergyEfficiency.com";
					$subject = "Your Appointment has been Reschedule";
					
					$to1 = $res_doc['email'];
					$from1 = "info@sEnergyEfficiency.com";
					$subject1 = "Your Appointment of ".$username." has been Reschedule";
					
					domail($to, $from, $subject, $mesg);
					domail($to1, $from1, $subject1, $mesg1);
					/* Code for sending Mail Ends */
					$msg="Appointment Rescheduled, Email Sent to User and Agent Successfully";
				}
				
				$ret['IsSuccess'] = true;
				$ret['Msg'] = $msg;
			}
		}catch(Exception $e){
			 $ret['IsSuccess'] = false;
			 $ret['Msg'] = $e->getMessage();
		}
	}
	
  return $ret;
}

function updateDetailedCalendar($id, $title, $date, $end_date,$type, $agentid, $userid, $reason, $notes, $c_code, $erem, $appconf, $tz){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
		
		$sql = "SELECT * FROM appointment WHERE id=".$id;
    $handle = mysql_query($sql);
		$res_uid = mysql_fetch_array($handle);
		
		$sql = "update `appointment` set"
		. " `title`='" . mysql_real_escape_string($title) . "', "
		. " `date`='" . php2MySqlTime(js2PhpTime($date)) . "', "
		. " `end_date`='" . php2MySqlTime(js2PhpTime($end_date)) . "', "
		. " `type`='" . mysql_real_escape_string($type) . "', "
		. " `reason`='" . mysql_real_escape_string($reason) . "', "
		. " `notes`='" . mysql_real_escape_string($notes) . "', "
		. " `accepted`='" . mysql_real_escape_string($appconf) . "', "
		. " `confirm_code`='" . mysql_real_escape_string($c_code) . "', "
		. " `email_reminder`='" . mysql_real_escape_string($erem) . "' "
		. "where `id`=" . $id;
				
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
			$msg="Appointment Rescheduled";
			
			if($agentid!=0 && $userid!=0)
			{
				/* Code for sending Mail Starts */
				$res_user=get_user_mail($userid);
				$res_doc=get_doc_mail($agentid);
				
				$sdnt = explode(" ", $date);
				$sdate = date("m-d-Y", strtotime($sdnt[0]));
				$stime = date("g:i a", strtotime($sdnt[1]));
				
				$olddnt = explode(" ", $res_uid['date']);
				$olddate = date("m-d-Y", strtotime($olddnt[0]));
				$oldtime = date("g:i a", strtotime($olddnt[1]));
				
				$username = $res_user['username'];
				$agentname = $res_doc['agentname'];
				
				include_once("../email/reshedule_user_email.php");// Mesg file for User
				include_once("../email/reshedule_agent_email.php");// Mesg file for Agent
				
				$to = $res_user['email'];
				$from = "info@sEnergyEfficiency.com";
				$subject = "Your Appointment has been Reschedule";
				
				$to1 = $res_doc['email'];
				$from1 = "info@sEnergyEfficiency.com";
				$subject1 = "Your Appointment of ".$username." has been Reschedule";
				
				domail($to, $from, $subject, $mesg);
				domail($to1, $from1, $subject1, $mesg1);
				/* Code for sending Mail Ends */
				$msg="Appointment Rescheduled, Email Sent to User and Agent Successfully";
			}
				
			$ret['IsSuccess'] = true;
			$ret['Msg'] = $msg;
    }
	}
	catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function removeCalendar($id){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    		
		$sel=mysql_query("select * from `appointment` where `id`=".$id);
		$pid=mysql_fetch_array($sel);
		$uid=$pid['userid'];
		$que = "DELETE FROM `property_owner_information` WHERE `property_owner_information`.`PO_ID` = ".$uid;
		$res=mysql_query($que);
		$que1 = "DELETE FROM `building_information` WHERE `building_information`.`PO_ID` = ".$uid;
		$res1=mysql_query($que1);
		$sql = "update `appointment` set"
				. " `reason`='Open Appointment', "
				. " `agentid`=0, "
				. " `userid`=0, "
				. " `accepted`=0 "
				. "where `id`=" . $id;
		
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
			$msg="Appointment Cancelled";
			
			if($pid['agentid']!=0 && $pid['userid']!=0)
			{
				$res_user=get_user_mail($pid['userid']);
				$res_doc=get_doc_mail($pid['agentid']);
				
				/* Code for sending Mail Starts */
				$sdnt = explode(" ", $pid['date']);
				$sdate = date("m-d-Y", strtotime($sdnt[0]));
				$stime = date("g:i a", strtotime($sdnt[1]));
				
				$ednt = explode(" ", $pid['end_date']);
				$etime = date("g:i a", strtotime($ednt[1]));
				
				$username = $res_user['username'];
				
				include_once("../email/rej_user_email.php");// Mesg file for User
				include_once("../email/rej_agent_email.php");// Mesg file for Agent
				
				$to = $res_user['email'];
				$from = "info@sEnergyEfficiency.com";
				$subject = "Your Appointment has been Rejected";
				
				$to1 = $res_doc['email'];
				$from1 = "info@sEnergyEfficiency.com";
				$subject1 = "Your Appointment of ".$username." has been Rejected";
				
				domail($to, $from, $subject, $mesg);
				domail($to1, $from1, $subject1, $mesg1);
				/* Code for sending Mail Ends */
				$msg="Appointment Cancelled, Email Sent to User and Agent Successfully";
			}
			
      $ret['IsSuccess'] = true;
      $ret['Msg'] = $msg;
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function listCalendarByRange($sd, $ed,$type,$access){
  $ret = array();
  $ret['events'] = array();
  $ret["issort"] =true;
  $ret["start"] = php2JsTime($sd);
  $ret["end"] = php2JsTime($ed);
  $ret['error'] = null;
  try{
    $db = new DBConnection();
    $db->getConnection();
	
	if($type == 'user')
	{
		if($access == 1)
		{
			$sql = "select * from `appointment` where `date` between '"
		  .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."' and type=4";
		}
		else if($access == 2)
		{
			$sql = "select * from `appointment` where `date` between '"
		  .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."' and (type=1 or type=3)";
		}
		else if($access == 3)
		{
			$sql = "select * from `appointment` where `date` between '"
		  .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."' and (type=1 or type=2)";
		}
		else
		{
			$sql = "select * from `appointment` where `date` between '"
      .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."'";
		}
	}
	else
	{
		$sql = "select * from `appointment` where `date` between '"
      .php2MySqlTime($sd)."' and '". php2MySqlTime($ed)."'";	
	}
    $handle = mysql_query($sql);
		
    while ($row = mysql_fetch_object($handle)) {
			$uname="";
			if($row->userid!=0)
			{
				$sql1 = "select * from `property_owner_information` where `PO_ID`='".$row->userid."'";
				$getuser=mysql_query($sql1);
				$row1=mysql_fetch_array($getuser);
				$uname=$row1['fname'];
				$type=$row1['type'];
				/*if($type == 1){$typedata='Energy evaluation';}
				if($type == 2){$typedata='Energy assessment';}
				if($type == 3){$typedata='Online meeting';}
				if($type == 4){$typedata='Retrofit';}*/
			}
		if($row->reason=="Open Appointment" && $row->type=="1" && $row->agentid==0 && $row->userid==0 && $row->accepted==0)
				$col=13;
		elseif($row->reason=="Open Appointment" && $row->type=="2" && $row->agentid==0 && $row->userid==0 && $row->accepted==0)
				$col=4;
		elseif($row->reason=="Open Appointment" && $row->type=="3" && $row->agentid==0 && $row->userid==0 && $row->accepted==0)
				$col=1;
		elseif($row->reason=="Open Appointment" && $row->type=="4" && $row->agentid==0 && $row->userid==0 && $row->accepted==0)
				$col=6;
		elseif($row->agentid==0 && $row->userid!=0 && $row->accepted==0)
				$col=9;
		/*elseif($row->agentid==0 && $row->userid!=0 && $row->accepted==1)
			$col=10;
		elseif($row->agentid!=0 && $row->userid!=0 && $row->accepted==1)
			$col=7;*/
			else
				$col=0;
			
      $ret['events'][] = array(
        $row->id,
        $row->reason,
        php2JsTime(mySql2PhpTime($row->date)),
        php2JsTime(mySql2PhpTime($row->end_date)),
        0,//IsAllDayEvent
        0,//more than one day event
				//$row->InstanceType,
        0,//$row->is_recurr,//Recurring event
        $col,
        1,//editable
				$uname,
        //'',//$attends
				$row->agentid."-".$row->userid."-".$row->accepted,
				$row->title,
				$type
      );
    }
	}catch(Exception $e){
     $ret['error'] = $e->getMessage();
  }
  return $ret;
}

function listCalendar($day, $type,$ltype,$access){
  $phpTime = js2PhpTime($day);
  //echo $phpTime . "+" . $type;
  switch($type){
    case "month":
      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
      break;
    case "week":
      //suppose first day of a week is monday 
      $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
      //echo date('N', $phpTime);
      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
      break;
    case "day":
      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
      break;
  }
  //echo $st . "--" . $et;
  return listCalendarByRange($st, $et,$ltype,$access);
}

header('Content-type:text/javascript;charset=UTF-8');
$method = $_GET["method"];
switch ($method) {
    case "add":
        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"]);
        break;
    case "list":
        $ret = listCalendar($_POST["showdate"], $_POST["viewtype"],$_POST["type"], $_POST["access"]);
        break;
    case "update":
        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"]);
        break; 
    case "remove":
        $ret = removeCalendar( $_POST["calendarId"]);
        break;
    case "adddetails":
				
				
				$title=$_POST["title"];
				$reason=$_POST["Subject"];
				$date = $_POST["stpartdate"] . " " . $_POST["stparttime"];
				$end_date = $_POST["stpartdate"] . " " . $_POST["etparttime"];
				$type=$_POST["type"];
				$c_code=randr(8);
				$erem=$_POST["erem"];
				$notes=$_POST["Description"];
				$appconf=$_POST["appconf"];
				$tz=$_POST["timezone"];
				
				if(isset($_GET["id"]))
				{
					$agentid=$_POST["agentid"];
					$userid=$_POST["userid"];
					
					$ret = updateDetailedCalendar($_GET["id"], $title, $date, $end_date,$type, $agentid, $userid, $reason, $notes, $c_code, $erem, $appconf, $tz);
				}
        else
				{
					$ret = addDetailedCalendar($title, $date, $end_date,$type,$reason, $notes, $c_code, $erem, $appconf, $tz);
				}
        break;
		case "addagent":
				$agent=$_POST["Agent"];
				
				$ret = add_agent($_GET["id"], $agent);
        break;
}
echo json_encode($ret);
?>