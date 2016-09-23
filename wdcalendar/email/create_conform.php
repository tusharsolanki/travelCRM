<?php
if(isset($_GET['flag']) && $_GET['flag']=='accept')
{
	$mesg='
	<html>
		<body>
			<table width="700" border="0" cellspacing="0" cellpadding="0" style="border:5px solid #333;" bgcolor="#e6e4e1">
				<tr><td><a href="#" target="_blank"><img src="'.LOGO_PATH.'"/></a></td></tr>
				
				<tr>
					<td align="center"><h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; color:#333;">Hello! Welcome to <span style="color:#990000;">Healthassist.</span></h1></td>
				</tr>
				
				<tr><td height="40">&nbsp;</td></tr>
				
				<tr>
					<td style="color:#333; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-align:center;">
					Your Appointment Request has been Accepted by <b> '.$res_user['username'].'</b>,<br />
					Your Appointment Timing is on '.$sdate.' at '.$stime.',<br />';
					
	$mesg.='</td>
				</tr>
				
				<tr><td height="40">&nbsp;</td></tr>
				
				<tr>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666; text-align:center;" height="50">Copyrights &copy; HealthWorks L.L.C 2012</td>
				</tr>
			</table>
		</body>
	</html>';
}

if(isset($_GET['flag']) && $_GET['flag']=='reject')
{
	$mesg='
	<html>
		<body>
			<table width="700" border="0" cellspacing="0" cellpadding="0" style="border:5px solid #333;" bgcolor="#e6e4e1">
				<tr><td><a href="#" target="_blank"><img src="'.LOGO_PATH.'"/></a></td></tr>
				
				<tr>
					<td align="center"><h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; color:#333;">Hello! Welcome to <span style="color:#990000;">Healthassist.</span></h1></td>
				</tr>
				
				<tr><td height="40">&nbsp;</td></tr>
				
				<tr>
					<td style="color:#333; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-align:center;">Your Appointment Requested timing was <b>'.$sdate.' at '.$stime.'</b> to <b>'.$edate.' at '.$etime.'</b></td>
				</tr>
				
				<tr><td height="40">&nbsp;</td></tr>
				
				<tr>
					<td style="color:#333; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-align:center;">
						Your Appointment Request has been Rejected by <b> '.$res_user['username'].'</b>.<br />';
				
	$mesg.='</td>
				</tr>
				
				<tr><td height="40">&nbsp;</td></tr>
				
				<tr>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666; text-align:center;" height="50">Copyrights &copy; HealthWorks L.L.C 2012</td>
				</tr>
			</table>
		</body>
	</html>';
}
?>