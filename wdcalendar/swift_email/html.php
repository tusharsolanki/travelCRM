<html>
<body>

<h3 style="font-family:Helvetica, Arial, sans-serif;">Name : <?php echo $fname.' '.$lname; ?></h3>

<?php
	if(isset($admin) && $admin){
		echo $body;
	}
	else
	{
?>

<p style="font-family:Helvetica, Arial, sans-serif;">Thank you for using "Go Pavers Collection", a customer representative will contact you shortly to answer any question you might have.</p>
<?php 
	}
?>
</body>
</html>