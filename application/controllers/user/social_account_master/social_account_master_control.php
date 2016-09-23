<?php

    /*
        @Description: Controller to Get All Social Account master Data
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : all Social Account master list
        @Date       : 05-09-2014
    */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class social_account_master_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
        $this->load->model('work_time_config_master_model');
		$this->load->model('contacts_model');
		$this->load->model('imageupload_model');
		$this->load->model('contact_masters_model');
		$this->load->model('user_management_model');
		
		$this->obj1 = $this->contact_masters_model;
        $this->obj = $this->work_time_config_master_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All Social Account master List
        @Author     : Mohit Trivedi
        @Input      : Search value or null
        @Output     : all Social Account master list
        @Date       : 05-09-2014
    */
	
    public function index()
    {	
        $field = array('id','linkedin_access_token','linkedin_username','twitter_access_token','twitter_access_token_secret','twitter_username','google_access_token','google_user_name');
	 	$match = array('id'=>$this->user_session['id']);
	 	$data['linkedin_data'] = $this->user_management_model->select_login_records($field, $match,'','='); 
		$data['msg'] = $this->message_session['msg'];
	    $match = array("user_id"=>$this->user_session['id']);
	    $data['main_content'] = "user/".$this->viewName."/add";       
        $this->load->view("user/include/template",$data);
    }


    /*
        @Description: Function Add New Social Account master details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add Social Account master details
        @Date       : 05-09-2014
    */
	
    public function add_record()
    {
		if($_REQUEST['action'] == 'login')
		{
			$data['main_content'] = "user/".$this->viewName."/facebookdata/actions";       
			$this->load->view("user/include/template",$data);
		}
		else
		{
			redirect(base_url().'user/'.$this->viewName);
    	}
	}

    /*
        @Description: Function Add New Social Account master details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add Social Account master details
        @Date       : 14-08-2014
    */
	public function linkedin_insert_data()
    {
		$this->data['callback_url']         =   base_url().'user/social_account_master/linkedin_insert_data';
		$this->data['consumer_key']      	=   $this->config->item('linkedin_api_key_user');
     	$this->data['consumer_secret']    	=   $this->config->item('linkedin_secret_key_user');
		$this->data['oauth_token'] 			= $this->session->userdata('oauth_request_token');
	    $this->data['oauth_token_secret']	= $this->session->userdata('oauth_request_token_secret');
		$this->load->library('linkedin/linkedin', $this->data);
		$this->input->get('oauth_verifier');

        $this->session->set_userdata('oauth_verifier', $this->input->get('oauth_verifier'));

        $tokens = $this->linkedin->get_access_token($this->input->get('oauth_verifier'));
        $access_data = array(
            'oauth_access_token' => $tokens['oauth_token'],
            'oauth_access_token_secret' => $tokens['oauth_token_secret']
        );

        $this->session->set_userdata($access_data);

        /*
         * Store Linkedin info in a session
         */
        $auth_data = array('linked_in' => serialize($this->linkedin->token), 'oauth_secret' => $this->input->get('oauth_verifier'));
        $this->session->set_userdata(array('auth' => $auth_data));
	    $access_token=serialize($this->linkedin->token);
		//Get user profile
		$xml_response = $this->linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,email-address,phone-numbers,date-of-birth,summary,main-address,specialties,industry,location,positions,educations,languages,publications,recommendations-received,site-standard-profile-request)",unserialize($access_token));
		
		$xml = simplexml_load_string($xml_response);
		$json = json_encode($xml);
		$profile = json_decode($json,TRUE);
		$firstname=$profile['first-name'];
		$user_id=$profile['id'];
	    $lastname=$profile['last-name'];
		$linkedin_username=$firstname." ".$lastname;
		
		$id=$this->user_session['id'];
		$ldata=array('id'=>$id,'linkedin_access_token'=>$access_token,'linkedin_username'=>$linkedin_username);
		$uid = $this->admin_model->update_user($ldata);	
		
		//Get user contacts
		$user = $this->linkedin->fetch(":(id,first-name,last-name,email-address,main-address,location,positions,site-standard-profile-request)",unserialize($access_token));
		
		$xml1 = simplexml_load_string($user);
		$json1 = json_encode($xml1);
		$contacts = json_decode($json1,TRUE);
		
		if(!empty($contacts['person']))
		{
			foreach($contacts['person'] as $row)
			{ 
			
				if(!empty($row['id']))
				{
					//pr($row['first_name']);
					$cdata['first_name'] = !empty($row['first-name'])?$row['first-name']:'';			
					$cdata['last_name'] = !empty($row['last-name'])?$row['last-name']:'';
					//$cdata['contact_pic'] = !empty($row['picture_url'])?$row['picture_url']:'';
					$cdata['created_type'] = '4';
					$cdata['linkedin_id'] = !empty($row['id'])?$row['id']:'';
					
					$cdata['linkedin_user_id'] = !empty($user_id)?$user_id:'';
					$cdata['created_by'] = $this->user_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');
						
					$cdata['status'] = '1';
					
					if(!empty($cdata['first_name']))
					{    
						$imgurl = !empty($row['picture-url'])?$row['picture-url']:'';
						///////// Download Image In FB.
						if(!empty($imgurl) && $imgurl != 'null')
						{
							$fullpath = basename($imgurl);
							if(!empty($fullpath))
							{
								$filenameIn  = $imgurl;
								$filenameOut = 'uploads/contact/big/'.$fullpath.'.jpg';
								$contentOrFalseOnFailure   = file_get_contents($filenameIn);
								$byteCountOrFalseOnFailure = file_put_contents($filenameOut, $contentOrFalseOnFailure);
								
								/////// Convert Image into Small Size///////////
								$img = file_get_contents($imgurl);
								$im = imagecreatefromstring($img);
								$width = imagesx($im);
								$height = imagesy($im);
								$newwidth = '150';
								$newheight = '150';
								$thumb = imagecreatetruecolor($newwidth, $newheight);
								imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
								imagejpeg($thumb,'uploads/contact/small/'.$fullpath.'.jpg'); //save image as jpg
								imagedestroy($thumb); 
								imagedestroy($im);
							}
							if(!empty($fullpath))
							{
								$cdata['contact_pic'] = !empty($fullpath)?$fullpath.'.jpg':'';
								$data['contact_pic'] = !empty($fullpath)?$fullpath.'.jpg':'';
							}
						}
						$match = array('linkedin_id'=>$cdata['linkedin_id'],'created_by'=>$this->user_session['id']);
						$result = $this->obj1->select_records1('',$match,'','=','','','','id','desc','contact_master');
						
						if(empty($result))
						{	
							
							$contact_id = $this->contacts_model->insert_record($cdata);
							if(!empty($row['profile-url']))
							{
							$social['contact_id'] = $contact_id;
							$social['profile_type'] = 3;
							$profileurl=explode('&',$row['site-standard-profile-request']['url']);
							$social['website_name'] = !empty($profileurl)?$profileurl:'';	
							$social['status'] = '1';
							
							$this->contacts_model->insert_social_trans_record($social);
							}
							//insert email address
							if(!empty($row['email-address']))
							{
								$email['contact_id'] = $contact_id;
								$email['email_type'] = 0;
								$email['email_address'] = !empty($row['email-address'])?$row['email-address']:'';	
								$email['is_default'] = '1';
								$email['status'] = '1';
								
								//$this->contacts_model->insert_email_trans_record($email);	
							}	
							$data_tag['contact_id'] = $contact_id;
							$data_tag['tag' ]= 'linkedin';
							$this->contacts_model->insert_tag_record($data_tag);
							
							$location = !empty($row['location']['name'])?$row['location']['name']:'';
							$country_code = !empty($row['location']['country']['code'])?$row['location']['country']['code']:'';
							if(!empty($location))
							{
								$address1['contact_id'] = $contact_id;
								$address1['city'] = $location;
								$address1['country'] = $country_code;
								$address1['status'] = '1';
								$this->contacts_model->insert_address_trans_record($address1);	
							}
							
							//////////End  FB Contact In Location and Hometown////////////
						}
						else
						{
							//pr($cdata);exit;
							/*$contact_id=$result[0]['id'];
							$data['first_name'] = !empty($row['first-name'])?$row['first-name']:'';			
							$data['last_name'] = !empty($row['last-name'])?$row['last-name']:'';
							$data['created_type'] = '4';
							$data['linkedin_id'] = !empty($row['id'])?$row['id']:'';
							$data['linkedin_user_id'] = !empty($user_id)?$user_id:'';
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_date'] = date('Y-m-d H:i:s');		
							$data['id']=$result[0]['id'];
							
							
							$this->contacts_model->update_record($data);*/	
							$contact_id=$result[0]['id'];
							$data['first_name'] = !empty($row['first-name'])?$row['first-name']:'';			
							$data['last_name'] = !empty($row['last-name'])?$row['last-name']:'';
							$data['created_type'] = '4';
							$data['linkedin_id'] = !empty($row['id'])?$row['id']:'';
							$data['linkedin_user_id'] = !empty($user_id)?$user_id:'';
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_date'] = date('Y-m-d H:i:s');		
							$data['id']=$result[0]['id'];
							
							$this->contacts_model->update_record($data);
							
							$location = !empty($row['location']['name'])?$row['location']['name']:'';
							$country_code = !empty($row['location']['country']['code'])?$row['location']['country']['code']:'';
							if(!empty($location))
							{
								$address['contact_id'] = $contact_id;
								$address['city'] = $location;
								$address['country'] = $country_code;
								$this->contacts_model->update_address_trans_FB_record($address);	
							}
							if(!empty($row['email-address']))
							{
								$email['contact_id'] = $contact_id;
								$email['email_type'] = 0;
								$email['email_address'] = !empty($row['email-address'])?$row['email-address']:'';	
								$email['is_default'] = '1';
								$email['status'] = '1';
								
								//$this->contacts_model->update_email_trans_FB_record($email);	
							}	
						}
					}
					unset($cdata);
					unset($data);
		   		}
				if(!empty($user_id))
				{
					$cdata['linkedin_id'] = !empty($row['id'])?$row['id']:'';
					$cdata['linkedin_user_id'] = !empty($user_id)?$user_id:'';			
					$cdata['created_by'] = $this->user_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');
					$cdata['status'] = '1';
					
					$match = array('linkedin_id'=>$cdata['linkedin_id'],'linkedin_user_id'=>$cdata['linkedin_user_id'],'created_by'=>$this->user_session['id']);
					$result = $this->contacts_model->select_records3('',$match,'','=','','','','id','desc');
					if(empty($result))
					{
						$this->contacts_model->insert_linkedin_trasection($cdata);	
					}
				}
			}
		}
		//pr($contacts);exit;
		redirect('user/social_account_master');
		
    }
    public function linkedin_insert_data1()
    {
		$data['main_content'] = "user/".$this->viewName."/login_with_linkedin/demo";       
		$this->load->view("user/include/template",$data);
    }


    /*
        @Description: Function Add New Social Account master details
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Load Form for add Social Account master details
        @Date       : 05-09-2014
    */
	
    public function add_linkedin1()
    {
			$data['main_content'] = "user/".$this->viewName."/login_with_linkedin/index";       
			$this->load->view("user/include/template",$data);
    }
	public function add_linkedin()
    {
		$this->data['callback_url']         =   base_url().'user/social_account_master/linkedin_insert_data';
		$this->data['consumer_key']      	=   $this->config->item('linkedin_api_key_user');
     	$this->data['consumer_secret']    	=   $this->config->item('linkedin_secret_key_user');
		
		$this->load->library('linkedin/linkedin',$this->data);

        $token = $this->linkedin->get_request_token();
		
		$oauth_data = array(
            'oauth_request_token' => $token['oauth_token'],
            'oauth_request_token_secret' => $token['oauth_token_secret']
        );
//pr($oauth_data);exit;
        $this->session->set_userdata($oauth_data);

        $request_link = $this->linkedin->get_authorize_URL($token);

        header("Location: " . $request_link);
    }
	/*
        @Description: Function Add New Social Account master details
        @Author     : NIral Patel
        @Input      : 
        @Output     : Load Form for add Social Account master details
        @Date       : 18-11-2014
    */
	
    public function add_twitter()
    {
			/* Start session and load library. */
			//session_start();
			//require_once('../../../../twitter/twitteroauth.php');
			$this->load->library('twitter/twitteroauth');
			$cusumer_key=$this->config->item('twitter_access_key_user');
			$cusumer_secret=$this->config->item('twitter_secret_key_user');
			 $cusumer_callback_url=$this->config->item('twitter_callback_url_user');
			/* Build TwitterOAuth object with client credentials. */
			$connection = new TwitterOAuth($cusumer_key,$cusumer_secret);
			 
			/* Get temporary credentials. */
			$request_token = $connection->getRequestToken($cusumer_callback_url);
			
			/* Save temporary credentials to session. */
			$token = $request_token['oauth_token'];
			//$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			$newdata = array(
                                'oauth_token'  => $request_token['oauth_token'],
                                'oauth_token_secret' =>$request_token['oauth_token_secret'],
                               );
            $this->session->set_userdata('twitter_session', $newdata);
			
			/* If last connection failed don't display authorization link. */
			switch ($connection->http_code) {
			  case 200:
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				header('Location: ' . $url); 
				break;
			  default:
				/* Show notification if something went wrong. */
				echo "Could not connect to Twitter. Refresh the page or try again later. HTTP CODE" . $connection->http_code;
			}
    }
	/*
        @Description: Function Add New Social Account master details
        @Author     : NIral Patel
        @Input      : 
        @Output     : Load Form for add Social Account master details
        @Date       : 18-11-2014
    */
	
    public function twitter_callback()
    {	
			$this->load->library('twitter/twitteroauth');
			$cusumer_key=$this->config->item('twitter_access_key_user');
			$cusumer_secret=$this->config->item('twitter_secret_key_user');
			$cusumer_callback_url=$this->config->item('twitter_callback_url_user');
			
			$this->twitter_session = $this->session->userdata('twitter_session');
			//pr($this->twitter_session);
			$oauth_token=$this->twitter_session['oauth_token'];
			$oauth_token_secret=$this->twitter_session['oauth_token_secret'];
			//pr($oauth_token);
			//echo '<br>'.$_REQUEST['oauth_token'];exit;
			/* If the oauth_token is old redirect to the connect page. */
			if (isset($_REQUEST['oauth_token']) && $oauth_token !== $_REQUEST['oauth_token']) {
			$newdata = array(
                                'oauth_status'  =>'oldtoken'
                              
                               );
            $this->session->set_userdata('twitter_old_session', $newdata);
			 redirect('user/social_account_master/clearsessions');
			}
			
			
			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth($cusumer_key, $cusumer_secret, $oauth_token, $oauth_token_secret);
			//pr($connection);
			/* Request access tokens from twitter */
			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			
			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$newdata1 = array(
                                'access_token'  =>$access_token
                              
                               );
            $this->session->set_userdata('access_token_session', $newdata1);
			//$_SESSION['access_token'] = $access_token;
			
			/* Remove no longer needed request tokens */
			 $this->session->unset_userdata('twitter_session');
			
			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if ($connection->http_code == 200) 
			{
			$newdata1 = array(
                                'status'  =>'verified'
                              
                               );
            $this->session->set_userdata('twitter_status_session', $newdata1);
			 // $_SESSION['status'] = 'verified';
			//echo '1'.'dsifsdhfsjd';exit;
			   redirect('user/social_account_master/get_twitter_data');
			 
			}
			else 
			{
			 redirect('user/social_account_master/clearsessions');
			}
    }
	/*
        @Description: Function get Social Account master details
        @Author     : NIral Patel
        @Input      : 
        @Output     : Load Form for add Social Account master details
		@Date       : 18-11-2014
    */
	function get_twitter_data()
	{
	
		$this->load->library('twitter/twitteroauth');
		$cusumer_key=$this->config->item('twitter_access_key_user');
		$cusumer_secret=$this->config->item('twitter_secret_key_user');
		$cusumer_callback_url=$this->config->item('twitter_callback_url_user');
		$this->access_token_session = $this->session->userdata('access_token_session');
		//pr($this->access_token_session);exit;
		$data['twitter_access_token']=$this->access_token_session['access_token']['oauth_token'];
		$data['twitter_access_token_secret']=$this->access_token_session['access_token']['oauth_token_secret'];
		$user_id = $this->access_token_session['access_token']['user_id'];
		$data['twitter_id']=$user_id;
		$data['twitter_username']=$this->access_token_session['access_token']['screen_name'];
		$data['id']=$this->user_session['id'];
		$uid = $this->admin_model->update_user($data);
		/* If access tokens are not available redirect to connect page. */
		if (empty($this->access_token_session) || empty($data['twitter_access_token']) || empty($data['twitter_access_token_secret'])) {
			//echo 1;exit;
			redirect('user/social_account_master/clearsessions');
		}
		/* Get user access tokens out of the session. */
		//$access_token = $_SESSION['access_token'];
		
		
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth($cusumer_key,$cusumer_secret, $data['twitter_access_token'],$data['twitter_access_token_secret']);
		//Get total follwers
		$result1 = $connection->get("https://api.twitter.com/1.1/users/show.json?user_id=".$user_id);
		if(!empty($result1))
		{
			$followers_count=$result1->followers_count;
		}
		/* If method is set change API call made. Test is called by default. */
		//$content = $connection->get('account/verify_credentials');
		$total_follower=200;
		if(!empty($followers_count) && $followers_count <= $total_follower)
		{
			$result = $connection->get("https://api.twitter.com/1.1/followers/list.json?cursor=-1&count=".$total_follower);
			//pr($result);exit;
			if(!empty($result))
			{
				foreach ($result->users as $follower) 
				{
					$data1['twitter_id']=!empty($follower->id)?$follower->id:'';
					$data1['twitter_user_id']=!empty($user_id)?$user_id:'';
					$data1['twitter_handle']=!empty($follower->screen_name)?$follower->screen_name:'';
					$data1['created_by']=$this->user_session['id'];
					$data1['created_date']=date('Y-m-d H:i:s');
					$data1['status']='1';
					
					$match=array('twitter_user_id'=>$data1['twitter_user_id'],'twitter_id'=>$data1['twitter_id'],'created_by'=>$this->user_session['id']);
					$res=$this->contacts_model->select_records4('',$match,'','=','','','','id','desc');
					if(empty($res))
					{
						$this->contacts_model->insert_twitter_trasection($data1);
					}	
				}
			}
		}
		else
		{
			if(!empty($followers_count))
			{
				$cursor=-1;
				$total=round($followers_count/$total_follower);
				for($i=0;$i<$total;$i++)
				{
					$result = $connection->get("https://api.twitter.com/1.1/followers/list.json?cursor=".$cursor."&count=".$total_follower);
					$cursor = $result->next_cursor_str;
					if(!empty($result))
					{
						foreach ($result->users as $follower) 
						{
							$data1['twitter_id']=!empty($follower->id)?$follower->id:'';
							$data1['twitter_user_id']=!empty($user_id)?$user_id:'';
							$data1['twitter_handle']=!empty($follower->screen_name)?$follower->screen_name:'';
							$data1['created_by']=$this->user_session['id'];
							$data1['created_date']=date('Y-m-d H:i:s');
							$data1['status']='1';
							$match=array('twitter_user_id'=>$data1['twitter_user_id'],'twitter_id'=>$data1['twitter_id'],'created_by'=>$this->user_session['id']);
							$res=$this->contacts_model->select_records4('',$match,'','=','','','','id','desc');
							if(empty($res))
							{
								$this->contacts_model->insert_twitter_trasection($data1);
							}	
						}
					}	
				}
			}
		}
		/* If method is set change API call made. Test is called by default. */
		$content = $connection->get('account/verify_credentials');
		redirect('user/social_account_master');
	}
	function clearsessions()
	{
		//session_start();
		//session_destroy();
		  $this->session->unset_userdata('twitter_session');
		   $this->session->unset_userdata('twitter_old_session');
		/* Redirect to page with the connect to Twitter option. */
		redirect('user/social_account_master');
	}
	/*
        @Description: Function for to connect with google contact
        @Author     : Niral Patel
        @Input      : Import contact
        @Output     : Connect to google
		@Date       : 26-1-2015
    */
	function google_connection()
	{
		//google api credential
		$client_id=$this->config->item('google_access_key_user');
		$client_secret=$this->config->item('google_secret_key_user');
		$redirect_uri=$this->config->item('google_callback_url_user');
		redirect("https://accounts.google.com/o/oauth2/auth?client_id=".$client_id."&redirect_uri=".$redirect_uri."&scope=https://www.google.com/m8/feeds/&response_type=code");
	}
	/*
        @Description: Function for to get google contact
        @Author     : Niral Patel
        @Input      : Import contact
        @Output     : Connect to google
		@Date       : 26-1-2015
    */
	function import_google_contact()
	{
		//google api credential
		$client_id=$this->config->item('google_access_key_user');
		$client_secret=$this->config->item('google_secret_key_user');
		$redirect_uri=$this->config->item('google_callback_url_user');
		$max_results = 1000;
		/*$client_id='830654609639-a073bf77td0il86i2a575273juort8ad.apps.googleusercontent.com';
		$client_secret='Al0bs0jqqKtH80XAOkLFbGAD';
		$redirect_uri='http://localhost/php_demo/oauth/oauth.php';*/
		
		$auth_code = $_GET["code"];

		$fields=array(
			'code'=>  urlencode($auth_code),
			'client_id'=>  urlencode($client_id),
			'client_secret'=>  urlencode($client_secret),
			'redirect_uri'=>  urlencode($redirect_uri),
			'grant_type'=>  urlencode('authorization_code')
		);
		$post = '';
		foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
		$post = rtrim($post,'&');
		
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
		curl_setopt($curl,CURLOPT_POST,5);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
		$result = curl_exec($curl);
		curl_close($curl);
		
		$response =  json_decode($result);
		//pr($response);exit;
		$accesstoken = $response->access_token;
		
		$url = 'https://www.google.com/m8/feeds/contacts/default/full?oauth_token='.$accesstoken;
		$xmlresponse =  $this->curl_file_get_contents($url);
		if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
		{
			$msg = "OOPS !! Something went wrong. Please try reloading the page.";
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/social_account_master');
		}
		$xml =  new SimpleXMLElement($xmlresponse);
		$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
		$result = $xml->xpath('//gd:email');
		//pr($xml);
		//**$resultname = $xml->xpath('//gd:name');
		$user_name=$xml->author->name;
		if(!empty($accesstoken))
		{
			//Insert google credential
			$data['google_access_token']=$accesstoken;
			$data['google_user_name']=!empty($user_name)?trim($user_name):'';
			$data['id']=$this->user_session['id'];
			$uid = $this->admin_model->update_user($data);
		}
		foreach ($xml as $title) 
		{
		  	//echo $title->title . "<br>";
			$contact_name=trim($title->title);
			if(!empty($contact_name))
			{
				$uname=explode(' ',$contact_name);
				$cdata['first_name'] = !empty($uname[0])?$uname[0]:'';			
				$cdata['last_name'] = !empty($uname[1])?$uname[1]:'';			
				$cdata['created_type'] = '7';
				$cdata['google_user_id'] = !empty($title->id)?trim($title->id):'';
				$cdata['created_by'] = $this->user_session['id'];
				$cdata['created_date'] = date('Y-m-d H:i:s');
				$cdata['status'] = '1';
				$match = array('google_user_id'=>$cdata['google_user_id'],'created_by'=>$this->user_session['id']);
				$result = $this->obj1->select_records1('',$match,'','=','','','','id','desc','contact_master');
				if(empty($result))
				{	
					$contact_id = $this->contacts_model->insert_record($cdata);
				}
			}
		}
		$msg = $this->lang->line('common_add_success_msg');
		$newdata = array('msg'  => $msg);
		$this->session->set_userdata('message_session', $newdata);
		redirect('user/social_account_master');
		/*foreach ($result as $title) {
			echo $title->attributes()->address . "<br>";
		}
		*/
	}
	/*
        @Description: Function for to get google contact through curl
        @Author     : Niral Patel
        @Input      : url
        @Output     : contact list
		@Date       : 26-1-2015
    */
	function curl_file_get_contents($url)
	{
		 $curl = curl_init();
		 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		 
		 curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
		 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	
		 
		 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
		 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
		 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
		 curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
		 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);	//To stop cURL from verifying the peer's certificate.
		 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		 
		 $contents = curl_exec($curl);
		 curl_close($curl);
		 return $contents;
	}
    /*
        @Description: Function for Insert Facebook
        @Author     : Kaushik  Valiya
        @Input      : Details of new Social Account master type
        @Output     : List of Social Account master type
        @Date       : 05-09-2014
    */
	
    public function insert_data()
    {
		////// FB Login User Info.////////
		$user_info = $this->input->post($user_profile);
		
		///// Friends Info.////////////
		$data_list1=$this->input->post($frnd['data']);
		
		$data_list=$data_list1['frnd'];
		
		if(!empty($data_list))
		{
			for($i=0;$i < count($data_list['data']); $i++)
			{ 
				if(!empty($data_list['data'][$i]['id']))
				{
			
					$cdata['first_name'] = !empty($data_list['data'][$i]['first_name'])?$data_list['data'][$i]['first_name']:$data_list['data'][$i]['name'];
					$cdata['last_name'] = !empty($data_list['data'][$i]['last_name'])?$data_list['data'][$i]['last_name']:'';
					$cdata['birth_date'] = !empty($data_list['data'][$i]['birthday'])?date('Y-m-d',strtotime($data_list['data'][$i]['birthday'])):'';
					$cdata['created_type'] = '3';
					$cdata['fb_id'] = !empty($data_list['data'][$i]['id'])?$data_list['data'][$i]['id']:'';
					$cdata['created_by'] = $this->user_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');		
					$cdata['fb_login_id'] = $user_info['user_profile']['id'];
					$cdata['status'] = '1';

					
					if(!empty($cdata['first_name']))
					{    
						
							$imgurl = !empty($data_list['data'][$i]['picture']['data']['url'])?$data_list['data'][$i]['picture']['data']['url']:'';
							///////// Download Image In FB.
							$fullpath = basename($imgurl);
							$path1 = explode('?',$fullpath);
							//$path = end($path1);
							$path = $path1[0];
							$data['contact_pic']=$path;	
							$cdata['contact_pic']=$path;	
							$Save_path = '/uploads/fb/'.$path;
							$filenameIn  = $imgurl;
							$filenameOut = 'uploads/contact/big/'.$path;
							$contentOrFalseOnFailure   = file_get_contents($filenameIn);
							$byteCountOrFalseOnFailure = file_put_contents($filenameOut, $contentOrFalseOnFailure);
							
							$filenameIn_s  = $imgurl;
							$filenameOut_s = 'uploads/contact/small/'.$path;
							$contentOrFalseOnFailure_s   = file_get_contents($filenameIn_s);
							$byteCountOrFalseOnFailure_s = file_put_contents($filenameOut_s, $contentOrFalseOnFailure_s);
							
							/////// Convert Image into Small Size///////////
							/*$img = file_get_contents($imgurl);
							$im = imagecreatefromstring($img);
							$width = imagesx($im);
							$height = imagesy($im);
							$newwidth = '150';
							$newheight = '150';
							$thumb = imagecreatetruecolor($newwidth, $newheight);
							imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagejpeg($thumb,'uploads/contact/small/'.$path); //save image as jpg
							imagedestroy($thumb); 
							imagedestroy($im);*/

						//$match = array('fb_id'=>$cdata['fb_id']);
						//pr($this->user_session);
						$match = array('fb_id'=>$cdata['fb_id'],'created_by'=>$this->user_session['id']);
        				$result = $this->obj1->select_records1('',$match,'','=','','','','id','desc','contact_master');
						if(empty($result))
						{	
							////// Add tag ////
							$contact_id = $this->contacts_model->insert_record($cdata);
							$data_tag['contact_id'] = $contact_id;
							$data_tag['tag' ]= 'facebook';
							$this->contacts_model->insert_tag_record($data_tag);
							
							///// Social Profile and FB URl////
							$csdata['contact_id'] = $contact_id;
							$csdata['profile_type'] = '1';
							$csdata['website_name'] = 'https://www.facebook.com/'.$data_list['data'][$i]['id'];
							$csdata['status'] = '1';
						
							$this->contacts_model->insert_social_trans_record($csdata);
							unset($csdata);
							////.....
							
							
							////////// FB Contact In Location and Hometown////////////
							$location = !empty($data_list['data'][$i]['location']['name'])?$data_list['data'][$i]['location']['name']:'';
							$location1 = explode(',',$location);
							if(!empty($location1) && !empty($location))
							{
								$address1['contact_id'] = $contact_id;
								if(!empty($location1[0]))
								{
									$address1['city'] = $location1[0];
								}
								if(!empty($location1[1]))
								{
									$address1['country'] = $location1[1];
								}
								$address1['status'] = '1';
								$this->contacts_model->insert_address_trans_record($address1);	
							}
							else
							{
								$address['status'] = '1';
							}
							
							$hometown = !empty($data_list['data'][$i]['hometown']['name'])?$data_list['data'][$i]['hometown']['name']:'';
							$hometown1 = explode(',',$hometown);
							
							if(!empty($hometown1) && !empty($hometown))
							{
								$address['contact_id'] = $contact_id;
								if(!empty($hometown1[0]))
								{
									$address['city'] = $hometown1[0];
								}
								if(!empty($hometown1[1]))
								{
									$address['country'] = $hometown1[1];
								}	
								
								$this->contacts_model->insert_address_trans_record($address);	
							}
							$email = !empty($data_list['data'][$i]['email'])?$data_list['data'][$i]['email']:'';					
							if(!empty($email))
							{
								$email_data['email']= $email;
								$email_data['contact_id'] = $contact_id;
								$email_data['is_default'] = '1';
								$this->contacts_model->insert_email_trans_record($email_data);	
							}
							unset($email_data);
							unset($hometown1);
							unset($hometown);
							unset($address);
							unset($address1);
							//////////End  FB Contact In Location and Hometown////////////
						}
						else
						{
							$data['first_name'] = !empty($data_list['data'][$i]['first_name'])?$data_list['data'][$i]['first_name']:$data_list['data'][$i]['name'];
							$data['last_name'] = !empty($data_list['data'][$i]['last_name'])?$data_list['data'][$i]['last_name']:'';
							$data['birth_date'] = !empty($data_list['data'][$i]['birthday'])?date('Y-m-d',strtotime($data_list['data'][$i]['birthday'])):'';
							$data['created_type'] = '3';
							$data['fb_id'] = !empty($data_list['data'][$i]['id'])?$data_list['data'][$i]['id']:'';
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_date'] = date('Y-m-d H:i:s');		
							$data['id']=$result[0]['id'];
							$data['fb_login_id'] = $user_info['user_profile']['id'];
							
							$contact_id = $this->contacts_model->update_record($data);	
							
							//////////FB Contact In Location and Hometown////////////
							
							$location = !empty($data_list['data'][$i]['location']['name'])?$data_list['data'][$i]['location']['name']:'';
							$location1 = explode(',',$hometown);
							if(!empty($location1) && !empty($location))
							{
							$path = basename($imgurl);
							$data['contact_pic']=$path;	
							
								$file = fopen($imgurl,"rb");
								if($file)

								$address1['contact_id'] = $contact_id;
								if(!empty($location1[0]))

								{
									$address1['city'] = $location1[0];
								}
								if(!empty($location1[1]))
								{
									$address1['country'] = $location1[1];
								}
								$address1['status'] = '1';
								$this->contacts_model->update_address_trans_FB_record($address1);	
							}
							else
							{
								$address['status'] = '1';
							}
							
							$hometown = !empty($data_list['data'][$i]['hometown']['name'])?$data_list['data'][$i]['hometown']['name']:'';
							$hometown1 = explode(',',$hometown);
							if(!empty($hometown1) && !empty($hometown))
							{
								$address['contact_id'] = $contact_id;
								if(!empty($hometown1[0]))
								{
									$address['city'] = $hometown1[0];
								}
								if(!empty($hometown1[1]))
								{
									$address['country'] = $hometown1[1];
								}
								$this->contacts_model->update_address_trans_FB_record($address);	
							}
							$email = !empty($data_list['data'][$i]['email'])?$data_list['data'][$i]['email']:'';					
							if(!empty($email))
							{
								$email_data['email']= $email;
								$email_data['contact_id'] = $contact_id;
								$email_data['is_default'] = '1';
								$this->contacts_model->update_email_trans_FB_record($email_data);	
							}
							
							$data['fb_id'] = !empty($data_list['data'][$i]['id'])?$data_list['data'][$i]['id']:'';
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_date'] = date('Y-m-d H:i:s');		
							$data['id']=$result[0]['id'];
							$contact_id = $this->contacts_model->update_record($data);	
						}	
					}
		   
		   		}
			}
		}
		
	}

    /*
        @Description: Function for Insert New Linkedin type data
        @Author     : Mohit Trivedi
        @Input      : Details of new Linkedin type
        @Output     : List of Social Account master type
        @Date       : 05-09-2014
    */
	
    public function insert_linkedin_data()
    {
		$data_frnd=$this->input->post($frnd);
		
		$login_id=$this->input->post($login_id);
		$access_token=$this->input->post('access_token');
		$access_secret_token=$this->input->post('access_secret_token');
		
		$linkedin_username=$this->input->post('linkedin_uname');
		$id=$this->user_session['id'];
		$ldata=array('id'=>$id,'linkedin_access_token'=>$access_token,'linkedin_username'=>$linkedin_username);
		$uid = $this->admin_model->update_user($ldata);	
		
		//pr($data_frnd);exit;
		//$frnd1 = json_decode($data,TRUE);
		//pr($data_frnd['frnd']);
		
			/*$xml = simplexml_load_string($data);
		$json = json_encode($xml);
		$frnd_data = json_decode($json,TRUE);*/
		
		//pr($data_list->children());exit;
		//$data_list=$data_list1['frnd'];
		
		if(!empty($data_frnd['frnd']))
		{
			foreach($data_frnd['frnd'] as $row)
			{ 
			
			//	pr($row);exit;	
				if(!empty($row['id']))
				{
					//pr($row['first_name']);
					$cdata['first_name'] = !empty($row['first_name'])?$row['first_name']:'';			
					$cdata['last_name'] = !empty($row['last_name'])?$row['last_name']:'';
					//$cdata['contact_pic'] = !empty($row['picture_url'])?$row['picture_url']:'';
					$cdata['created_type'] = '4';
					$cdata['linkedin_id'] = !empty($row['id'])?$row['id']:'';
					
					$cdata['linkedin_user_id'] = !empty($row['user_id'])?$row['user_id']:'';
					$cdata['created_by'] = $this->user_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');
						
					$cdata['status'] = '1';
					
					if(!empty($cdata['first_name']))
					{    
						$imgurl = !empty($row['picture_url'])?$row['picture_url']:'';
						///////// Download Image In FB.
						if(!empty($imgurl) && $imgurl != 'null')
						{
							$fullpath = basename($imgurl);
							if(!empty($fullpath))
							{
								$filenameIn  = $imgurl;
								$filenameOut = 'uploads/contact/big/'.$fullpath.'.jpg';
								$contentOrFalseOnFailure   = file_get_contents($filenameIn);
								$byteCountOrFalseOnFailure = file_put_contents($filenameOut, $contentOrFalseOnFailure);
								
								/////// Convert Image into Small Size///////////
								$img = file_get_contents($imgurl);
								$im = imagecreatefromstring($img);
								$width = imagesx($im);
								$height = imagesy($im);
								$newwidth = '150';
								$newheight = '150';
								$thumb = imagecreatetruecolor($newwidth, $newheight);
								imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
								imagejpeg($thumb,'uploads/contact/small/'.$fullpath.'.jpg'); //save image as jpg
								imagedestroy($thumb); 
								imagedestroy($im);
							}
							if(!empty($fullpath))
							{
								$cdata['contact_pic'] = !empty($fullpath)?$fullpath.'.jpg':'';
								$data['contact_pic'] = !empty($fullpath)?$fullpath.'.jpg':'';
							}
						}
						$match = array('linkedin_id'=>$cdata['linkedin_id']);
						$result = $this->obj1->select_records1('',$match,'','=','','','','id','desc','contact_master');
						if(empty($result))
						{	
							
							$contact_id = $this->contacts_model->insert_record($cdata);
							if(!empty($row['profile_url']))
							{
							$social['contact_id'] = $contact_id;
							$social['profile_type'] = 3;
							$social['website_name'] = !empty($row['profile_url'])?$row['profile_url']:'';	
							$social['status'] = '1';
							
							$this->contacts_model->insert_social_trans_record($social);
							}
							//insert email address
							if(!empty($row['email_address']))
							{
								$email['contact_id'] = $contact_id;
								$email['email_type'] = 0;
								$email['email_address'] = !empty($row['email_address'])?$row['email_address']:'';	
								$email['is_default'] = '1';
								$email['status'] = '1';
								
								$this->contacts_model->insert_email_trans_record($email);	
							}	
							$data_tag['contact_id'] = $contact_id;
							$data_tag['tag' ]= 'linkedin';
							$this->contacts_model->insert_tag_record($data_tag);
							
							$location = !empty($row['address'])?$row['address']:'';
							$country_code = !empty($row['address1'])?$row['address1']:'';
							if(!empty($location))
							{
								$address1['contact_id'] = $contact_id;
								$address1['city'] = $location;
								$address1['country'] = $country_code;
								$address1['status'] = '1';
								$this->contacts_model->insert_address_trans_record($address1);	
							}
							
							//////////End  FB Contact In Location and Hometown////////////
						}
						else
						{
							//pr($cdata);exit;
							$contact_id=$result[0]['id'];
							$data['first_name'] = !empty($row['first_name'])?$row['first_name']:'';			
							$data['last_name'] = !empty($row['last_name'])?$row['last_name']:'';
							$data['created_type'] = '4';
							$data['linkedin_id'] = !empty($row['id'])?$row['id']:'';
							$data['linkedin_user_id'] = !empty($row['user_id'])?$row['user_id']:'';
							$data['modified_by'] = $this->user_session['id'];
							$data['modified_date'] = date('Y-m-d H:i:s');		
							$data['id']=$result[0]['id'];
							$data['linkedin_user_id'] = !empty($row['user_id'])?$row['user_id']:'';
							
							$this->contacts_model->update_record($data);	
							
							$location = !empty($row['address'])?$row['address']:'';
							$country_code = !empty($row['address1'])?$row['address1']:'';
							if(!empty($location))
							{
								$address['contact_id'] = $contact_id;
								$address['city'] = $location;
								$address['country'] = $country_code;
								$this->contacts_model->update_address_trans_FB_record($address);	
							}
							if(!empty($row['email_address']))
							{
								$email['contact_id'] = $contact_id;
								$email['email_type'] = 0;
								$email['email_address'] = !empty($row['email_address'])?$row['email_address']:'';	
								$email['is_default'] = '1';
								$email['status'] = '1';
								
								$this->contacts_model->update_email_trans_FB_record($email);	
							}	
						}
					}
					unset($cdata);
					unset($data);
		   		}
			}
		}
		
		if(!empty($data_frnd['frnd']))
		{
			foreach($data_frnd['frnd'] as $row)
			{ 
			
			//	pr($row);exit;	
				if(!empty($row['user_id']))
				{
					//pr($row['first_name']);
					$cdata['linkedin_id'] = !empty($row['id'])?$row['id']:'';
					$cdata['linkedin_user_id'] = !empty($row['user_id'])?$row['user_id']:'';			
					$cdata['created_by'] = $this->user_session['id'];
					$cdata['created_date'] = date('Y-m-d H:i:s');
					$cdata['status'] = '1';
					
					$match = array('linkedin_id'=>$cdata['linkedin_id'],'linkedin_user_id'=>$cdata['linkedin_user_id'],'created_by'=>$this->user_session['id']);
					$result = $this->contacts_model->select_records3('',$match,'','=','','','','id','desc');
					if(empty($result))
					{
						$this->contacts_model->insert_linkedin_trasection($cdata);	
					}
					unset($cdata);
					unset($data);
		   		}
			}
		}
		
	}
	/*
        @Description: Function for disconnect linkedin
        @Author     : Niral Patel
        @Input      : Details of new Linkedin type
        @Output     : Disconnect linkedin
        @Date       : 22-11-2014
    */
	function disconnect_linkedin()
	{
		$id=$this->user_session['id'];
		$ldata=array('id'=>$id,'linkedin_access_token'=>'','linkedin_username'=>'','linkedin_secret_access_token'=>'');
		echo $uid = $this->admin_model->update_user($ldata);	
	}
	/*
        @Description: Function for disconnect twitter
        @Author     : Niral Patel
        @Input      : Details of new Linkedin type
        @Output     : Disconnect twitter
        @Date       : 22-11-2014
    */
	function disconnect_twitter()
	{
		$data['twitter_access_token']='';
		$data['twitter_access_token_secret']='';
		$data['twitter_username']='';
		$data['twitter_id']='';
		$data['id']=$this->user_session['id'];
		echo $uid = $this->admin_model->update_user($data);	
	}
	/*
        @Description: Function for disconnect google
        @Author     : Niral Patel
        @Input      : Details of new google
        @Output     : Disconnect twitter
        @Date       : 22-11-2014
    */
	function disconnect_google()
	{
		$data['google_access_token']='';
		$data['google_user_name']='';
		$data['id']=$this->user_session['id'];
		echo $uid = $this->admin_model->update_user($data);	
	}
}