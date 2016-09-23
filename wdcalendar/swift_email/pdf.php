<html>
<head>
	<style>
	body {font-family:Helvetica, Arial, sans-serif; font-size:10pt;}
	table {width:100%; border-collapse:collapse; border:1px solid #CCC;}
	td {padding:5px; border:1px solid #CCC; border-width:1px 0;}
    .style2 {color: #FF0000}
    .style3 {
	font-size: 9
}
    .style5 {color: #FF0000; font-weight: bold; }
    </style>
</head>
<body>

	<h1>Customer's Pavers Information</h1>
<div style="background-color: #000;">
		<img src="./pdf_email/gp3.jpg" />
	</div>
	<table>
		<tr>
			<th>Name:</th>
			<td><?php echo $fname.' '.$lname; ?></td>
			<th>Email:</th>
			<td><?php echo $email; ?></td>
			<th>Phone:</th>
			<td><?php echo $phone; ?></td>
		</tr>
		<tr>
			<th>Type:</th>
			<td><?php echo $product_type; ?></td>
			<th>Manufacturer:</th>
			<td><?php echo $manufacturer_name; ?></td>
			<th>Color:</th>
			<td><?php echo $color_name; ?></td>
		</tr>
		
		<!--<tr>
			
			<th>Size:</th>
			<td><?php echo $sqft; ?>Sq. Ft.</td>
			<th>Price/sq. ft:</th>
			<td>$ <?php echo $product_price; ?></td>
			<th>Total Amt.:</th>
			<td style="font-size:1.5em">$ <?php echo $total; ?></td>
		</tr>
		-->
		<tr>
			<!--<th rowspan="2"><Zipcode</th>
			<td rowspan="2"><?php echo $zipcode;?></td>-->
			<td colspan="6" align="center" valign="middle"><img width="200" height="109" src="<?php echo './administrator/'.$img_src;?>" alt="<?php echo $product_type;?>"/></td>
		</tr>
		
		<tr>
			<td colspan="6" align="center"><span class="style5">ONLINE USERS - CALL TODAY AND GET 15% OFF  </span></td>
	  </tr>
		<?php
			if($discount > 0)
			{
		?>
		<!--<tr>
			<td colspan="6" align="center"><span class="style2">CALL TODAY AND GET 15% OFF FOR ONLINE USERS</span></td>
	  </tr>-->
		<?php
			}
		?>
		>
<tr>
			<td colspan="6" align="center">Please visit us at <a href="www.gopavers.com">gopavers.com</a> or call free at  1-855-972-8377</td>
		</tr>
	</table>
	
<?php
		/*if(isset($admin) && $admin)
		{*/
			
			echo '<br/><br/> Client IP: '; 
			if ( isset($_SERVER["REMOTE_ADDR"]) )    { 
				echo '' . $_SERVER["REMOTE_ADDR"] . ' '; 
			} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    { 
				echo '' . $_SERVER["HTTP_X_FORWARDED_FOR"] . ' '; 
			} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    { 
				echo '' . $_SERVER["HTTP_CLIENT_IP"] . ' '; 
			} 
					/*}
		else
		{*/
	?>
<div style="padding:10px;">
	
		
	  <p><span class="style2">WHY GO PAVERS ?</span> Long-standing relationships with manufacturers and material suppliers help Go Pavers stock the largest selection of quality pavers, at the best prices, around.  With top-rated customer service and an ever-expanding assortment of paving stones, Go Pavers is equipped to handle all your paving project needs. Known for our attention to detail, clean cutting and precise elevations, we use ICPI certified installers to ensure your project is up to code, so your property not only looks its best, but maintains its value as well.	  </p>
		
  <p align="center"><a href="./pdf_email/gpmail.jpg"><img name="Gopavers" src="./pdf_email/gpmail.jpg" width="600" height="246" alt=""></a></p>
	  <p align="left" class="style3">All thumbnails, artwork, logos and text in connection with  the Online Pavers Collection, were created for and owned by Go Pavers, and any use  without express written permission is strictly prohibited.&nbsp; The content, organization, code, graphics,  design, compilation, magnetic translation, digital conversion and other matters  related to the Site are protected under applicable copyrights, trademarks and  other proprietary (including but not limited to intellectual property) rights.&nbsp; Any and all unauthorized use is illegal and  will be prosecuted to the fullest extent of the law.<br>
  The Online Estimator is intended for use by potential Go  Pavers customers only.&nbsp; Use by all other  third parties not affiliated with Go Pavers is strictly prohibited.&nbsp;.</p>
	  <p align="left" class="style3">*All rights reserved *Colors in images may differ from actual product</p>
  <center></center>
	</div>
	
	
<?php //}?>
</body>
</html>
