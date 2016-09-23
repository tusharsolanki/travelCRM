<?php
include_once("dbconfig.php");
require_once('../swift_email/swift/swift_required.php');

include_once("../../application/config/database.php");

include_once("../../application/config/config.php");

$hostname =  $db['default']['hostname'];

$database =  $db['default']['database'];

$username =  $db['default']['username'];

$password =  $db['default']['password'];




$db = new DBConnection();

$db->getConnection($hostname,$database,$username,$password);
$tab="";
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
if(isset($_GET['uid']) && $_GET['uid']!="")
{
	$query="update `appointment` set `accepted`=1 where `id`=" . $_GET['uid'];
	mysql_query($query);
	$tab="Appointment has been confirmed!";
}
if(isset($_GET['apt_id']) && $_GET['apt_id']!="")
{
	$query="select * FROM `appointment` where `id`=" . $_GET['apt_id'];
	$res=mysql_query($query);
	$row=mysql_fetch_array($res);
	$num=mysql_num_rows($res);
	
	$uid=$row['userid'];
	
	if($uid != '')
	{
	$query1="select * FROM `property_owner_information` where `PO_ID`=" .$uid;
	$res1=mysql_query($query1);
	$row1=mysql_fetch_array($res1);
	$email=$row1['Email'];
	$sdate = date("M j, Y", strtotime($row['date']));
	$stime = date("g:i a", strtotime($row['date']));
			
			//$edate = date("M j, Y", strtotime($earr[0]));
	$etime = date("g:i a", strtotime($row['end_date']));
	
	include_once("../email/delete_user_email.php");// Mesg file for User
	if($email !='')
	{
	$to = $email;
	$from = "info@sEnergyEfficiency.com";
	$subject = "Your Appointment has been cancelled";
	$mesg = "Your Appointment has been cancelled";
	domail($to, $from, $subject, $mesg);
	}
	$query1="delete FROM `property_owner_information` where `PO_ID`=" .$uid;
	mysql_query($query1);
	$query2="delete FROM `auditor_information` where `PO_ID`=" . $uid;
	mysql_query($query2);
	$query3="delete FROM `utillities` where `PO_ID`=" . $uid;
	mysql_query($query3);
	$query4="delete FROM `building_information` where `PO_ID`=" . $uid;
	mysql_query($query4);
	$query5="delete FROM `waterheater_inspection` where `PO_ID`=" . $uid;
	mysql_query($query5);
	$query6="delete FROM `furnace_inspection` where `PO_ID`=" . $uid;
	mysql_query($query6);
	$query7="delete FROM `stove_gas_inspection` where `PO_ID`=" . $uid;
	mysql_query($query7);
	$query8="delete FROM `clothes_dryer` where `PO_ID`=" . $uid;
	mysql_query($query8);
	$query9="delete FROM `spillage_monoxide_test` where `PO_ID`=" . $uid;
	mysql_query($query9);
	$query10="delete FROM `worst_depressurization` where `PO_ID`=" . $uid;
	mysql_query($query10);
	$query11="delete FROM `oven_monoxide_test` where `PO_ID`=" . $uid;
	mysql_query($query11);
	$query12="delete FROM `air_leakage` where `PO_ID`=" . $uid;
	mysql_query($query12);
	$query13="delete FROM `interior_site_inspection` where `PO_ID`=" . $uid;
	mysql_query($query13);
	$query14="delete FROM `exterior_site_inspection` where `PO_ID`=" . $uid;
	mysql_query($query14);
	$query15="delete FROM `tst_out_air_leakage` where `PO_ID`=" . $uid;
	mysql_query($query15);
	$query16="delete FROM `tst_out_clothes_dryer` where `PO_ID`=" . $uid;
	mysql_query($query16);
	$query17="delete FROM `tst_out_exterior_site_inspection` where `PO_ID`=" . $uid;
	mysql_query($query17);
	$query18="delete FROM `tst_out_oven_monoxide_test` where `PO_ID`=" . $uid;
	mysql_query($query18);
	$query19="delete FROM `tst_out_stove_gas_inspection` where `PO_ID`=" . $uid;
	mysql_query($query19);
	$query20="delete FROM `tst_out_worst_depressurization` where `PO_ID`=" . $uid;
	mysql_query($query20);
	$query21="delete FROM `energypro_calculations` where `PO_ID`=" . $uid;
	mysql_query($query21);
	$query22="delete FROM `pricing` where `PO_ID`=" . $uid;
	mysql_query($query22);
	$query23="delete FROM `home_owner_report` where `PO_ID`=" . $uid;
	mysql_query($query23);
	}
	//$query="delete FROM `appointment` where `id`=".$_GET['apt_id'];
	$query="DELETE FROM `appointment` WHERE `id` = ".$_GET['apt_id'];

	mysql_query($query);
	
	$tab="Appointment has been deleted!";
	/*code for delete appoiment*/
}
if(isset($_GET['date']) && $_GET['date']!="" && isset($_GET['end_date']) && $_GET['end_date']!="")
{
	$date = $_GET['date'];
	$end_date = $_GET['end_date'];
	$apptid = $_GET['apptid'];
	$type = $_GET['type'];
	//echo $type;
	//$sel="SELECT * FROM appointment WHERE '".$date."' between date AND end_date";
	//$sel="SELECT * FROM appointment WHERE `date`>='".$date."' AND `end_date` < '".$date."'";
	$sel="SELECT * FROM calender WHERE (start_date between '".$date."' AND '".$end_date."') AND (end_date between '".$date."' AND '".$end_date."')";
	$res=mysql_query($sel) or die(mysql_error());
	$row=mysql_fetch_array($res);
	$num=mysql_num_rows($res);
	
	if($row['id']!=$apptid)
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
}

echo $tab;
?>