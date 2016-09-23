<?php
    session_start();

    $config['base_url']             =   base_url();
    $config['callback_url']         =   base_url().'admin/social_account_master/linkedin_insert_data';
    $config['linkedin_access']      =   '75nefv0ycvhmza';
    $config['linkedin_secret']      =   'gY0hrwpvOU5AnTol';
	
    include_once "linkedin.php";
   
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
   if (isset($_REQUEST['oauth_verifier'])){
        $_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];

        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);
        $_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
        header("Location: " . $config['callback_url']);
        exit;
   }
   else{
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
   }
  	//for linkedin profile
    $xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,email-address,phone-numbers,date-of-birth,summary,main-address,specialties,industry,location,positions,educations,languages,publications,recommendations-received)");
	$id=$linkedin->getProfile("~:(id)");
	$fname=$linkedin->getProfile("~:(first-name)");
	$lname=$linkedin->getProfile("~:(last-name)");
	$headline=$linkedin->getProfile("~:(headline)");
	$pic=$linkedin->getProfile("~:(picture-url)");
	$email=$linkedin->getProfile("~:(email-address)");
	$contact=$linkedin->getProfile("~:(phone-numbers)");
	//pr($contact);exit;
	//contact information
	$user = $linkedin->fetch("~:(first-name,last-name,main-address,location,company,positions)");
	$cfname = $linkedin->fetch("~:(first-name)");
	$clname = $linkedin->fetch("~:(last-name)");
	$address = $linkedin->fetch("~:(main-address)");
	$company = $linkedin->fetch("~:(company)");
	$location=$linkedin->fetch("~:(location)");
	
		$xml = simplexml_load_string($user);
		$json = json_encode($xml);
		$frnd = json_decode($json,TRUE);

		$xml1 = simplexml_load_string($id);
		$json1 = json_encode($xml1);
		$user_id = json_decode($json1,TRUE);
?>
	
<?php
 	
	$final_pic = '';
 	$data = explode("<picture-url>",$pic);
	if(!empty($data[1]))
	{
		$data1 = explode("</picture-url>",$data[1]);
		if(!empty($data1[0]))
			$final_pic = $data1[0];
	}
    $search_response = $linkedin->search("?company-name=facebook&count=10");
?>
<div id="content">
    <div id="content-header">
        <h1><?php echo $this->lang->line('common_label_socialacc')?></h1>
    </div>
    <div id="content-container">
        <div class="content_right_part">
            <div class="chart_bg1 tbl_border">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-header">
                                <h3>
                                    <?php echo $this->lang->line('common_label_socialacc')?>
                                </h3>
                            </div>
                            <div class="portlet-content" id="fb_div">
							<table class="mytable">
								<tr>
									<td align="left"><h2>Hi <?php echo $fname.''.$lname;?></h2></td>
									<td><a href="javascript:void(0);" onclick="addfriendslist();">Add friends to contact</a></td>
									<td><a href="file:///C|/Users/Tops/AppData/Local/Temp/Rar$DI99.680/logout.php" class="myButton" id="logout-link">Logout In LinkedIn</a></td>

								</tr>
								<tr>
									<td><b>LinkedIn id:</b><?=(!empty($id)) ? $id : '-';
									
									?>
									 </td>
									<?php if($final_pic!=''){?>
									<td valign="top" rowspan="2"><img src="<?=$final_pic?>" height="100" /></td>
									<?php }?>
								</tr>
								<tr>
									<td><b>Headline:</b><?=(!empty($headline)) ? $headline : '-'; ?></td>
								</tr>
							</table>
							<table class="mytable">
								<tr>
									<td align='center' colspan='4'><h1>Linkdin Friends List</h1></td>
								</tr>
								<tr>
									<td><b>id</b></td>
									<td><b>Contact Name</b></td>
								</tr>
								<tr>
								   <td><?php 
								   $data = array();
								  
								   $user_data = new SimpleXMLElement($cfname);
										//pr($user_data);exit;
									
									foreach ($user_data->children() as $result)
									{
										foreach ($result as $key => $val)
										{
											if($key=='id'){
												echo $val."<br/>";	
												
											}
										}
									 }
									 ?>
									</td>
								   <td><?php $user_data = new SimpleXMLElement($cfname);
								  // pr($user_data);exit;
									
									foreach ($user_data->children() as $result)
									{
										foreach ($result as $key =>$val)
										{
											if($key=='first-name'){
												echo $val.' ';
												$data[]['first_name']=$val;	
											}

											if($key=='last-name'){
												echo $val.'<br/>';	
												$data[]['last_name']=$val;
											
											}

											if($key=='date-of-birth'){
												echo $val.'<br/>';	
												$data[]['date-of-birth']=$val;
											
											}


										}
									 }
									 ?>
									</td>

								</tr>
							</table>
							</div>
                        
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    </div>
<?php 

	$arr = array();
	
	$i=0;

	foreach($frnd['person'] as $row)
	{
		
		$arr[$i]['id']=$row['id'];
		$arr[$i]['first_name']=$row['first-name'];
		$arr[$i]['last_name']=$row['last-name'];
		$arr[$i]['picture_url']=$row['picture-url'];
		$arr[$i]['headline']=$row['headline'];
		$arr[$i]['address']=$row['location']['name'];
		$arr[$i]['address1']=$row['location']['country']['code'];
		$arr[$i]['industry']=$row['industry'];
		$arr[$i]['user_id']=$user_id['id'];
		$i++;
	}
	

?>
									
<script type="text/">

function addfriendslist()
{
	var js_array = <?=!empty($arr)?json_encode($arr):''?> ;
	
	$.ajax({
		type: "post",
		url: '<?php echo $this->config->item('admin_base_url')?><?=$viewname.'/insert_linkedin_data';?>',
		data: {'frnd':js_array}, 
		beforeSend: function() {
							$('#fb_div').block({ message: 'Loading...' }); 
		
		},
		success: function(msg) 
		{
			$('#fb_div').unblock(); 
		}
	});	
}	
</script>	