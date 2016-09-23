<?php
$mesg = '<html>
<body>
<table width="700" border="0" cellspacing="0" cellpadding="0" style="border:5px solid #333;" bgcolor="#e6e4e1">
  <tr><td><a href="#" target="_blank"><img src="'.LOGO_PATH.'"/></a></td></tr>
  <tr>
    <td align="center"><h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; color:#333;">Hello!	 Welcome to <span style="color:#990000;">Healthassist.</span></h1></td>
  </tr>
  <tr>
  	<td height="40">&nbsp;</td>
  </tr>
  <tr>
  	<td style="color:#333; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-align:center;">Reschedule Appointment Information from Dr. '.$agentname.' for '.$reason.' which was previously scheduled on '.$olddate.' at '.$oldtime.'</td>
     </tr>
  <tr>
  	<td height="40">&nbsp;</td>
  </tr>
	<tr>
  	<td style="color:#333; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-align:center;">Now your appointment timing is on '.$sdate.' at '.$stime.' if you want to accept appointment, it has recurring appointments from date '.$sdate.' </td>
     </tr>
  <tr>
  	<td height="40">&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:center; font-size:18px; color:#990000; font-family:Arial, Helvetica, sans-serif;" height="40">Please Click on</td>
  </tr>

   <tr>
		<td align="center">
			<a href="'.FILE_PATH.'email/accept_reject.php?flag=accept&c_code='.$c_code.'" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#fff; padding:0 10px; text-decoration:none; height:30px; line-height:30px; display:inline-block; background-color:#333333;">Accept Request</a>
			
			&nbsp;&nbsp;&nbsp;<strong style="font-family:Arial, Helvetica, sans-serif;">or</strong>&nbsp;&nbsp;&nbsp;
			
			<a href="'.FILE_PATH.'email/accept_reject.php?flag=reject&userid='.$userid.'&agentid='.$agentid.'&c_code='.$c_code.'" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#fff; padding:0 10px; text-decoration:none; height:30px; line-height:30px; display:inline-block; background-color:#333333;">Reject Request</a>
		</td>
	</tr>
	
  <tr>
  	<td style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666; text-align:center;" height="50">Copyrights &copy; HealthWorks L.L.C 2012</td>
  </tr>
</table>

</body>
</html>
';
?>