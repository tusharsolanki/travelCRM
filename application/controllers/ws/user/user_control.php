<?php 
/*
    @Description: User controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 23-09-14
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_control extends CI_Controller
{	
    function __construct()
    {
		parent::__construct(); 
		//$this->load->model('ws/user_model');
		$this->load->model('ws/user_registration_model');
		$this->load->model('ws/saved_searches_model');
		$this->load->model('ws/properties_viewed_model');
		$this->load->model('ws/favorite_model');
		$this->load->model('ws/last_login_model');
		$this->load->model('user_management_model');
		$this->load->model('common_function_model');
		$this->load->model('contact_masters_model');
		$this->load->model('map_joomla_model');
		$this->load->model('joomla_assign_model');
		
		$this->load->model('interaction_plans_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('contacts_model');
		$this->load->model('interaction_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('email_library_model');
		$this->load->model('admin_model');
		$this->load->model('joomla_property_cron_model');
		$this->load->model('package_management_model');
		$this->load->model('ws/property_valuation_searches_model');
		$this->load->model('property_valuation_contact_model');
		$this->load->model('property_contact_model');
        $this->load->library('mpdf');        
		//$this->obj = $this->user_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
        $this->load->library('Twilio');
    }
	
    /*
    @Description: Function for Get All Player List
    @Author: Ruchi Shahu
    @Input: - Search value or null
    @Output: - all tips list
    @Date: 16-07-14
    */
   function index()
	{	
		//$postData = $_REQUEST;
 		//extract($_REQUEST);

 		//if($_REQUEST['func'] == 'getuser')
			//$this->getuser($postData);
	}

	/*
    @Description: Function for User registration Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/user_registration?user_id=1&fname=Test_joomla&mname=Test_joomlam&lname=Test_joomlal&domain=test.com
	function user_registration()
	{
		$post_data = $_REQUEST;
		//pr($post_data);
                $action = $post_data['action'];
                //$cdata['joomla_domain_name'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['joomla_domain_name'] = $domain;
                $err_flag = 0;
                $price_flag = 0;
                if($action == 'insert') 
                {
                    $cdata['first_name'] = '';$cdata['middle_name'] = '';$cdata['last_name'] = '';
                    if($post_data['fname'] != '-')
                        $cdata['first_name'] = $post_data['fname'];
                    if($post_data['mname'] != '-')
                        $cdata['middle_name'] = $post_data['mname'];
                    if($post_data['lname'] != '-')
                        $cdata['last_name'] = $post_data['lname'];
                    $cdata['joomla_user_id'] = $post_data['user_id'];
                    $cdata['is_valuation_contact'] = !empty($post_data['val_reg'])?$post_data['val_reg']:'No';
                    $cdata['joomla_domain_name'] = $domain;
                    $cdata['created_type'] = '6';
                    $cdata['status'] = '1';
                    $cdata['joomla_contact_type'] = 'Buyer';
                    $cdata['joomla_ip_address'] = !empty($post_data['ip_address'])?$post_data['ip_address']:'';
                    $address_from_ip = !empty($post_data['address'])?mysql_real_escape_string(urldecode(utf8_decode($post_data['address']))):'';
                    $joomla_address = '';
                    if(!empty($address_from_ip))
                    {
                        $iaddr = explode('@', $address_from_ip);
                        
                        if(!empty($iaddr[0])) {
                            $city = $iaddr[0]; //City
                        }
                        if(!empty($iaddr[1])) {
                            $state = $iaddr[1]; //State
                        }
                        if(!empty($iaddr[2])) {
                            $zip = $iaddr[2]; // Zip
                        }
                        /*if(!empty($iaddr[3])) {
                            $country = $iaddr[3]; // Country
                        }*/
                        $joomla_address = (!empty($city)?$city:'').(!empty($state)?', '.$state:'').(!empty($zip)?' '.$zip:'');//.(!empty($country)?', '.$country:'');
                        
                        $cdata['joomla_address'] = $joomla_address;
                    }
                    
                    $phone_no = !empty($post_data['password'])?$post_data['password']:'';
                    if(strlen($phone_no) == 10) {
                        $ph1data['phone_no'] = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone_no);
                    }
                    //$phdata['phone_no'] = $post_data['contact_number'];
                    
                    $ph1data['phone_type'] = 0;
                    $ph1data['is_default'] = '1';
                    $ph1data['status'] = '1';
                }
                else if($action == 'update')
                {
                    //$cdata['joomla_domain_name'] = urldecode($post_data['domain']);
                    $cdata['joomla_contact_type'] = $post_data['contact_type'];
                    $cdata['id'] = $post_data['lw_id'];
                    if(!empty($post_data['timeframe'])) {
                        $cdata['joomla_timeframe'] = $post_data['timeframe'].' Months';
                    }
                    if(!empty($post_data['min_price']) && !empty($post_data['max_price']))
                    {
                        $cdata['price_range_from'] = $post_data['min_price'];
                        $cdata['price_range_to'] = $post_data['max_price'];
                        $price_flag = 1;
                    }
                    
                    if($price_flag == 1)
                    {
                        if($post_data['min_price'] > $post_data['max_price'])
                        {
                            $err_flag = 1;
                        }
                    }
                    
                    if(!empty($post_data['min_area']) && !empty($post_data['max_area']))
                    {
                        $cdata['min_area'] = $post_data['min_area'];
                        $cdata['max_area'] = $post_data['max_area'];
                        //$price_flag = 1;
                    }
                    if(!empty($post_data['property_type']))
                    {
                        $cdata['house_style'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['property_type'])));
                    }
                    if(!empty($post_data['city_area']))
                    {
                        $cdata['area_of_interest'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['city_area'])));
                    }
                    if(!empty($post_data['bedrooms']))
                    {
                        $cdata['no_of_bedrooms'] = $post_data['bedrooms'];
                    }
                    if(!empty($post_data['bathrooms']))
                    {
                        $cdata['no_of_bathrooms'] = $post_data['bathrooms'];
                    }
                }
                else if($action == 'address_update')
                {
                    /*$cdata['joomla_address'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['address'])));
                    $cdata['id'] = $post_data['lw_id'];
                    */
                    $addata['contact_id'] = $post_data['lw_id'];
                    $addata['address_line1'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['address'])));
                    $addata['status'] = '1';
                }
                else if($action == 'mobile_update')
                {
                    $phone_no = $post_data['contact_number'];
                    if(strlen($phone_no) == 10) {
                        $phdata['phone_no'] = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $post_data['contact_number']);
                    }
                    //$phdata['phone_no'] = $post_data['contact_number'];
                    $phdata['contact_id'] = $post_data['lw_id'];
                    $phdata['phone_type'] = 0;
                    $phdata['is_default'] = '1';
                    $phdata['status'] = '1';
                    $updata['contact_id'] = $post_data['lw_id'];
                }
		$db_name = '';
                if($err_flag == 1)
                {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=false;
                }
                else {

                    //// For Getting Dynamic Database credential and connect to that database
                    $parent_db = $this->config->item('parent_db_name');
                    $table = $parent_db.".joomla_mapping as jm";
                    $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
                    $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                    $match = array('jm.domain'=>$cdata['joomla_domain_name'],'lm.status'=>'1','lm.user_type'=>"2");
                    $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                    
                    //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                    if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                    {
                        /*$newdata1 = array(
                            'host_name'  => $domain_result[0]['host_name'],
                            'db_user_name' =>$domain_result[0]['db_user_name'],
                            'db_user_password' =>$domain_result[0]['db_user_password'],
                            'db_name' =>$domain_result[0]['db_name']
                        );
                        $this->session->set_userdata('db_session', $newdata1);*/
                        
                        //$this->db->close();
                        $db = '';
							
                        $db['second']['hostname'] = $domain_result[0]['host_name'];
                        $db['second']['username'] = $domain_result[0]['db_user_name'];
                        //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                        $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                        $db['second']['database'] = $domain_result[0]['db_name'];
                        $db['second']['dbdriver'] = 'mysql';
                        $db['second']['dbprefix'] = '';
                        $db['second']['pconnect'] = TRUE;
                        $db['second']['db_debug'] = TRUE;
                        $db['second']['cache_on'] = FALSE;
                        $db['second']['cachedir'] = '';
                        $db['second']['char_set'] = 'utf8';
                        $db['second']['dbcollat'] = 'utf8_general_ci';
                        $db['second']['swap_pre'] = '';
                        $db['second']['autoinit'] = TRUE;
                        $db['second']['stricton'] = FALSE;

                        $this->db = $this->load->database($db['second'], TRUE);
                    }
                    if(!empty($domain_result[0]['timezone'])) {
                        date_default_timezone_set($domain_result[0]['timezone']);
                    } else {
                        date_default_timezone_set($this->config->item('default_timezone'));
                    }
                        
                    /// END For Getting Dynamic Database credential and connect to that database
                    
                    if($action == 'insert')
                    {
                        $cdata['created_date']  = date('Y-m-d H:i:s');
                        $ins_data = $this->user_registration_model->insert_record($cdata);
                                        //echo $ins_data; exit;
                        
                        $edata['contact_id'] = $ins_data;
                        $edata['email_type'] = '0';
                        $edata['email_address'] = $post_data['email'];
                        $edata['is_default '] = '1';
                        $edata['status '] = '1';

                        $ins_data_email = $this->user_registration_model->insert_record_email($edata);
                        
                        if(!empty($ph1data['phone_no'])) {
                            $ph1data['contact_id'] = $ins_data;
                            $this->contacts_model->insert_phone_trans_record($ph1data);
                        }
                    
                        // Code for updating livewire id Send by Gopal Patel 06-11-2014
                        $url = $cdata['joomla_domain_name']."/libraries/api/update_lwid.php?lwid=".$ins_data."&userid=".$post_data['user_id'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzidp encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                        $response = curl_exec($ch);
                        curl_close($ch); 
                        
                        /*$match = array('lw_admin_id'=>$id);
                        $result_last_login = $this->last_login_model->select_records('',$match,'','=');*/
                        
                        //// OLD EMAIL LOGIC (NEW in CRON control) 13-03-2015
                        /*$email_temp = array(); $admin_email_temp = array();
                        $fields = array('template_name,template_subject,email_message,email_event');
                        $match = array('email_event'=>'5');
                        $autores_res = $this->email_library_model->select_records($fields,$match,'','=');
                        
                        if(!empty($autores_res) && count($autores_res) > 0)
                        {
                            if(count($autores_res) > 1)
                            {
                                foreach($autores_res as $row)
                                {
                                    if($row['template_name'] == 'Joomla User Registration Email')
                                    {
                                        $email_temp = $row;
                                    }
                                }
                            }
                            else {
                                $email_temp = $autores_res[0];
                            }
                        }
                        $from_email = '';
                        if(!empty($domain_result[0]['email_id'])) {
                            $from_email = $domain_result[0]['email_id'];
                        }
                        $this->user_registration_model->sendPasswordMail($post_data['email'],$ins_data,$post_data['fname'],$post_data['lname'],'',$email_temp,'',$from_email,$domain);
                        
                        $fields = array('template_name,template_subject,email_message,email_event');
                        $match = array('email_event'=>'6');
                        $autores_res = $this->email_library_model->select_records($fields,$match,'','=');                        
                        if(!empty($autores_res) && count($autores_res) > 0)
                        {
                            if(count($autores_res) > 1)
                            {
                                foreach($autores_res as $row)
                                {
                                    if($row['template_name'] == 'Joomla User Registration Email Admin')
                                        $admin_email_temp = $row;
                                }
                            }
                            else {
                                $admin_email_temp = $autores_res[0];
                            }
                        }
                        //To get Admin user email address and send email. Sanjay Moghariya 30-10-2014
                        $fields = array('id,admin_name,email_id');
                        $match = array('user_type'=>'2');
                        $admin_user = $this->user_management_model->select_login_records($fields,$match,'','=','','','','','');
                        
                        if(!empty($admin_user) && count($admin_user) > 0)
                        {
                            $this->user_registration_model->sendPasswordMail($post_data['email'],$ins_data,$post_data['fname'],$post_data['lname'],$admin_user,'',$admin_email_temp,$from_email,$domain);
                        }
                        */
                        //// END OLD EMAIL LOGIC (NEW in CRON) 13-03-2015
                    }
                    else if($action == 'update')
                    {
                        $ins_data = $this->user_registration_model->update_record($cdata);
                        $ins_data = $cdata['id'];
                    }
                    else if($action == 'address_update')
                    {
                        $this->contacts_model->insert_address_trans_record($addata);
                        //$ins_data = $this->user_registration_model->update_record($cdata);
                        $ins_data = $cdata['id'];
                    }
                    else if($action == 'mobile_update')
                    {
                        if(!empty($phdata['phone_no'])) {
                            $err_fl=0;
                            $result = $this->contacts_model->select_phone_trans_record($updata['contact_id']);
                            if(!empty($result)) {
                                if($result[0]['phone_no'] != $phdata['phone_no'])
                                    $err_fl=1;
                            }
                            if(!empty($err_fl))
                            {
                                $updata['is_default'] = '0';
                                $this->contacts_model->update_defualt_phone_trans_record($updata);
                                $ins_data = $this->contacts_model->insert_phone_trans_record($phdata);
                            }
                        }
                        $ins_data = $post_data['lw_id'];
                    }

                    if(!empty($ins_data))
                    {
                            $arr['MESSAGE']='SUCCESS';
                            $arr['FLAG']=true;
                            $arr['data']=$ins_data;
                    }
                    else
                    {
                            $arr['MESSAGE']='FAIL';
                            $arr['FLAG']=false;
                    }
                }
		echo json_encode($arr);	
	}
        
	/*
    @Description: Function for Saved Searches Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/saved_searches?uid=1&name=Test_joomla&url=http://192.168.0.12/livewire_crm&where_query=select*fromproperty&pids=1&state=1&domain=test.com&lwid=1
	function saved_searches()
	{
            $post_data = $_REQUEST;

            // insert into joomla_rpl_savesearch

            $cdata['sid'] = $post_data['sid']; // Saved searches id from Joomla website table
            $action = $post_data['action'];
            $cdata['name'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['name'])));
            //$cdata['search_criteria'] = urldecode(utf8_decode($post_data['search_criteria']));
            $cdata['min_price'] = $post_data['min_price'];
            $cdata['max_price'] = $post_data['max_price'];
            $cdata['bedroom'] = $post_data['bedroom'];
            $cdata['bathroom'] = $post_data['bathroom'];
            $cdata['min_area'] = $post_data['min_area'];
            $cdata['max_area'] = $post_data['max_area'];
            $cdata['min_year_built'] = $post_data['min_year_built'];
            $cdata['max_year_built'] = $post_data['max_year_built'];
            $cdata['fireplaces_total'] = $post_data['fireplaces_total'];
            $cdata['min_lotsize'] = $post_data['min_lotsize'];
            $cdata['max_lotsize'] = $post_data['max_lotsize'];
            $cdata['garage_spaces'] = $post_data['garage_spaces'];
            $cdata['architecture'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['architecture'])));
            $cdata['school_district'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['school_district'])));
            $cdata['waterfront'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['waterfront'])));
            $cdata['s_view'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['view'])));
            $cdata['parking_type'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['parking_type'])));
            
            //$cdata['search_criteria'] = $post_data['search_criteria']));
            
            $domain = urldecode($post_data['domain']);
            $domain = str_replace('www.', '', $domain);
            $domain = trim($domain,'/');
            $cdata['domain'] = $domain;
            //$cdata['domain'] 	= $post_data['domain'];
            $cdata['created_type'] 	= '2';
            
            if(empty($action) || $action == 'insert') {
                $cdata['uid'] = $post_data['uid'];
                $cdata['lw_admin_id'] 	= $post_data['lwid'];
            }
                
            $cdata['status'] = '1';
            //$pids = $_REQUEST['pids'];

            //// For Getting Dynamic Database credential and connect to that database
            $parent_db = $this->config->item('parent_db_name');
            $table = $parent_db.".joomla_mapping as jm";
            $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
            $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
            $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
            $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

            //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
            if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
            {
                /*$newdata1 = array(
                    'host_name'  => $domain_result[0]['host_name'],
                    'db_user_name' =>$domain_result[0]['db_user_name'],
                    'db_user_password' =>$domain_result[0]['db_user_password'],
                    'db_name' =>$domain_result[0]['db_name']
                );
                $this->session->set_userdata('db_session', $newdata1);*/

                //$this->db->close();
                $db = '';

                $db['second']['hostname'] = $domain_result[0]['host_name'];
                $db['second']['username'] = $domain_result[0]['db_user_name'];
                //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                $db['second']['database'] = $domain_result[0]['db_name'];
                $db['second']['dbdriver'] = 'mysql';
                $db['second']['dbprefix'] = '';
                $db['second']['pconnect'] = TRUE;
                $db['second']['db_debug'] = TRUE;
                $db['second']['cache_on'] = FALSE;
                $db['second']['cachedir'] = '';
                $db['second']['char_set'] = 'utf8';
                $db['second']['dbcollat'] = 'utf8_general_ci';
                $db['second']['swap_pre'] = '';
                $db['second']['autoinit'] = TRUE;
                $db['second']['stricton'] = FALSE;

                //pr($db['second']);exit;

                //$this->legacy_db = $this->load->database($db['second'], TRUE);
                $this->db = $this->load->database($db['second'], TRUE);

                /*$this->legacy_db->select ('*');
                $this->legacy_db->from ('login_master');
                //$this->legacy_db->where(array('email_id'=>$email,'password'=>$password));
                $query = $this->legacy_db->get();
                $udata = $query->result_array();
                //pr($udata);exit;*/
            }

            if(!empty($domain_result[0]['timezone'])) {
                date_default_timezone_set($domain_result[0]['timezone']);
            } else {
                date_default_timezone_set($this->config->item('default_timezone'));
            }
            
            /// END For Getting Dynamic Database credential and connect to that database
            if(!empty($action) && $action == 'update') {
                $cdata['modified_date']  = date('Y-m-d H:i:s');	
                $fields = array('id');
                $match = array('sid'=>$cdata['sid'],'domain'=>$cdata['domain']);
                $ss_data = $this->saved_searches_model->select_records($fields,$match,'','=');
                if(!empty($ss_data)) {
                    $ins_data = $this->saved_searches_model->update_ss_record($cdata);
                    $ins_data = $cdata['sid'];
                }
            } else {
                $cdata['created_date']  = date('Y-m-d H:i:s');	
                $ins_data = $this->saved_searches_model->insert_record($cdata);
            }
            
            if(!empty($ins_data))
            {
                    $arr['MESSAGE']='SUCCESS';
                    $arr['FLAG']=true;
                    $arr['data']=$ins_data;
            }
            else
            {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=false;
            }
            echo json_encode($arr);	
	}
        
		
	/*
    @Description: Function for Properties Viewed Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/properties_viewed?User_id=1&log_date=10-8-2014&views=200&mlsid=1111&propery_name=shivranjni&domain=test.com&lwid=1
	function properties_viewed()
	{
		$post_data = $_REQUEST;
		$time_zone = $this->config->item('default_timezone');
		// insert into joomla_rpl_track
		$cdata['uid'] 		= $post_data['User_id'];
		//$cdata['views'] 	= $post_data['views'];
		$cdata['mlsid'] 	= $post_data['mlsid'];
		$cdata['propery_name'] 	= mysql_real_escape_string(urldecode(utf8_decode($post_data['propery_name'])));
                //$cdata['domain'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		//$cdata['domain'] 	= $post_data['domain'];
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['status'] = '1';
		
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

                    //$this->db->close();
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                    if(!empty($domain_result[0]['timezone']))
                    {
                        $time_zone = $domain_result[0]['timezone'];
                    }
                }
                /// END For Getting Dynamic Database credential and connect to that database
                
                if(!empty($domain_result[0]['timezone'])) {
                    date_default_timezone_set($domain_result[0]['timezone']);
                } else {
                    date_default_timezone_set($this->config->item('default_timezone'));
                }
                
                $cdata['created_date']  = date('Y-m-d H:i:s');
                
                $log_date = str_replace('::',' ',$post_data['log_date']); // This For Replace to joomla site Date
		$fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
		$fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
		$cdata['log_date'] 	= $fdate->format('Y-m-d H:i:s');
                
                $fields = array('id,views');
                $match = array('mlsid'=>$post_data['mlsid'],'domain'=>$domain,'lw_admin_id'=>$post_data['lwid']);
                $property_data = $this->properties_viewed_model->select_records($fields,$match,'','=');
                if(!empty($property_data))
                {
                    $cdata['id'] = $property_data[0]['id'];
                    $cdata['views'] = $property_data[0]['views'] + 1;
                    $ins_data = $this->properties_viewed_model->update_record($cdata);
                    $ins_data = $property_data[0]['id'];
                }
                else
                {
                    $cdata['views'] = 1;
                    $ins_data = $this->properties_viewed_model->insert_record($cdata);
                }
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			//$sel_data = $this->user_model->se($ins_data);
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
	
	
	/*
    @Description: Function for Last Login Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/last_login?User_id=1&log_date=10-8-2014&ip=192.168.0.12&domain=test.com&lwid=1
	function last_login()
	{
		$post_data = $_REQUEST;
		//insert into joomla_rpl_log
		$cdata['uid'] 		= $post_data['User_id'];
		$cdata['ip'] 	= $post_data['ip'];
		$time_zone = $this->config->item('default_timezone');
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['status'] = '1';
		// get from joomla_mapping table lw_admin_id
		
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                $email_data = array();
                $email_data['domain'] = $domain;
                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    $this->db = $this->load->database($db['second'], TRUE);

                    if(!empty($domain_result[0]['timezone']))
                    {
                        $time_zone = $domain_result[0]['timezone'];
                    }
                    $email_data['to'] = $domain_result[0]['email_id'];
                    $email_data['admin_name'] = $domain_result[0]['admin_name'];
                    $email_data['admin_email'] = $domain_result[0]['email_id'];
                    $email_data['admin_brokerage_pic'] = $domain_result[0]['brokerage_pic'];
                    $email_data['admin_address'] = $domain_result[0]['address'];
                    $email_data['admin_phone'] = $domain_result[0]['phone'];
                }
                /// END For Getting Dynamic Database credential and connect to that database
                
                if(!empty($domain_result[0]['timezone'])) {
                    date_default_timezone_set($domain_result[0]['timezone']);
                } else {
                    date_default_timezone_set($this->config->item('default_timezone'));
                }
                
                $cdata['created_date']  = date('Y-m-d H:i:s');

		$log_date = str_replace('::',' ',$post_data['log_date']); // This For Replace to joomla site Date
		$fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
		$fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
		$cdata['log_date'] 	= $fdate->format('Y-m-d H:i:s');
                $log_dt 	= $fdate->format('Y-m-d');
	//	pr($cdata);exit;
		
                
                ///// Add Auto responder (Returning lead- after >30 day since last login) email Sanjay Moghariya 15-05-2015 /////
                $table = "joomla_rpl_log";
                $match = array('lw_admin_id'=>$cdata['lw_admin_id']);
                $fields = array('id,log_date');
                $log_data =$this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','1','','id','desc','','');
                
                if(!empty($log_data[0]['log_date']))
                {
                    $date30 = date('Y-m-d',strtotime($log_dt.'-1 MONTHS'));
                    $db_date = date('Y-m-d',strtotime($log_data[0]['log_date']));
                    $cur_date = date('Y-m-d',strtotime($date30));
                    
                    if(strtotime($db_date) <= strtotime($cur_date))
                    {
                        $table = "contact_master as cm";
                        $group_by = "cm.id";
                        $match = array('cm.id'=>$cdata['lw_admin_id']);
                        $fields = array('cm.first_name,cm.middle_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as assigned_agent_name','cpt.phone_no','cet.email_address','uct.user_id,lm.email_id');
                        $join_tables = array(
                            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                            '(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
                            '(select * from user_contact_trans where agent_type !="Lender") as uct'=>'uct.contact_id = cm.id',
                            'user_master as um'=>'uct.user_id = um.id',
                            'login_master as lm'=>'lm.user_id = um.id'
                        );
                        $contact_data =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'');
                        
                        if(!empty($contact_data))
                        {
                            $email_data['contact_name'] = $contact_data[0]['contact_name'];
                            $email_data['contact_email'] = $contact_data[0]['email_address'];
                            $email_data['contact_phone'] = $contact_data[0]['phone_no'];
                            if(!empty($contact_data[0]['assigned_agent_name']))
                            {
                                /*$table ="login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$row['created_by'];
                                $agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);*/
                                $email_data['agent_name'] = $contact_data[0]['assigned_agent_name'];
                                $email_data['agent_email'] = $contact_data[0]['email_id'];
                            } 

                            $autores_res = array();
                            $fields = array('template_name,template_subject,email_message,email_event');
                            $match = array('email_event'=>'7');
                            $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

                            if(!empty($autores_res[0]['template_subject'])) {
                                $subject = $autores_res[0]['template_subject'];
                                $subject .= !empty($domain)?' - '.$domain:'';
                            }
                            else {
                                $d = !empty($domain)?' '.$domain:'';
                                $subject = "Returning Lead from ".$d;
                            }

                            if(!empty($email_data['admin_email']))
                                $from = $email_data['admin_email'];
                            else
                                $from = $this->config->item('admin_email');

                            if(!empty($email_data['agent_email']))
                                $to = $email_data['agent_email'];
                            else
                                $to = $from;

                            $edata['from_name'] = "Returning Lead";
                            $edata['from_email'] = $from;

                            if(!empty($autores_res[0]['email_message']))
                            {
                                if(!empty($email_data['agent_name']))
                                    $a_nm = ucwords($email_data['agent_name']);
                                else if(!empty($email_data['admin_name']))
                                    $a_nm = ucwords($email_data['admin_name']);
                                else $a_nm = '';
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$a_nm,
                                    'Contact First Name'=> !empty($contact_data[0]['first_name'])?ucwords($contact_data[0]['first_name']):'',
                                    'Contact Spouse/Partner First Name'=>!empty($contact_data[0]['spousefirst_name'])?ucwords($contact_data[0]['spousefirst_name']):'',
                                    'Contact Last Name'=> !empty($contact_data[0]['last_name'])?ucwords($contact_data[0]['last_name']):'',
                                    'Contact Spouse/Partner Last Name'=> !empty($contact_data[0]['spouselast_name'])?ucwords($contact_data[0]['spouselast_name']):'',
                                    'Contact Company Name'=> !empty($contact_data[0]['company_name'])?ucwords($contact_data[0]['company_name']):''
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($autores_res[0]['email_message'], $map);
                                    $email_data['msg_body'] = $output;
                                }
                            }
                            $message   = $this->load->view('ws/returning_lead_email', $email_data, TRUE);
                            //// Mailgun email
                            $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                        }
                    }
                }
                //// End Auto responder email 
                $ins_data = $this->last_login_model->insert_record($cdata);
		//echo $this->db->last_query();exit;
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
	
	
	/*
    @Description: Function for Favorite Ws
    @Author: Ami Bhatti
    @Input: - Data to be inserted 
    @Output: - ID of data inserted
    @Date: 09-10-14
    */
	
	//http://192.168.0.12/livewire_crm/ws/user/favorite?uid=1&pid=1&mlsid=1&propery_name=shivranjni&date=10-8-2014&domain=test.com&lwid=1
	function favorite()
	{
		$post_data = $_REQUEST;
		$time_zone = $this->config->item('default_timezone');
		//insert into joomla_rpl_bookmarks
		
		$cdata['uid'] 		= $post_data['uid'];
		$cdata['pid'] 		= $post_data['pid'];
		$cdata['mlsid']		= $post_data['mlsid'];
		$cdata['propery_name'] 	= mysql_real_escape_string(urldecode(utf8_decode($post_data['propery_name'])));
		//$cdata['domain'] 	= $post_data['domain'];
                //$cdata['domain'] = urldecode($post_data['domain']);
                $domain = urldecode($post_data['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $cdata['domain'] = $domain;
		$cdata['lw_admin_id'] 	= $post_data['lwid'];
		$cdata['status'] = '1';
                $action = $post_data['action'];
		
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                //echo $this->db->last_query();
               // pr($domain_result);exit;
                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    /*$newdata1 = array(
                        'host_name'  => $domain_result[0]['host_name'],
                        'db_user_name' =>$domain_result[0]['db_user_name'],
                        'db_user_password' =>$domain_result[0]['db_user_password'],
                        'db_name' =>$domain_result[0]['db_name']
                    );
                    $this->session->set_userdata('db_session', $newdata1);*/

                    //$this->db->close();
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                    if(!empty($domain_result[0]['timezone']))
                    {
                        $time_zone = $domain_result[0]['timezone'];
                    }
                }
                /// END For Getting Dynamic Database credential and connect to that database
                
                if(!empty($domain_result[0]['timezone'])) {
                    date_default_timezone_set($domain_result[0]['timezone']);
                } else {
                    date_default_timezone_set($this->config->item('default_timezone'));
                }
                
                $cdata['created_date']  = date('Y-m-d H:i:s');	
                
                $log_date = str_replace('::',' ',$post_data['date']); // This For Replace to joomla site Date
		$fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
		$fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
		$cdata['date'] 	= $fdate->format('Y-m-d H:i:s');
                
                if($action == 'delete')
                {
                    $dfdata['uid'] 		= $post_data['uid'];
                    $dfdata['pid'] 		= $post_data['pid'];
                    $dfdata['lw_admin_id'] 	= $post_data['lwid'];
                    $ins_data = $this->favorite_model->delete_fav_record($dfdata);
                    $ins_data = $dfdata['lw_admin_id'];
                }
                else {
                    $fields = array('id');
                    $match = array('lw_admin_id'=>$cdata['lw_admin_id'],'pid'=>$cdata['pid'],'mlsid'=>$cdata['mlsid']);
                    $fresult = $this->favorite_model->select_records($fields,$match,'','=');
                    if(empty($fresult)) {
                        $ins_data = $this->favorite_model->insert_record($cdata);
                    }
                }
		if(!empty($ins_data))
		{
			$arr['MESSAGE']='SUCCESS';
			$arr['FLAG']=true;
			$arr['data']=$ins_data;
		}
		else
		{
			$arr['MESSAGE']='FAIL';
			$arr['FLAG']=false;
		}
		echo json_encode($arr);	
	}
        
    /*
        @Description: Function for send email once property status changed whom added into favorite, saved searches, valuation searches
        @Author     : Sanjay Moghariya
        @Input      : property id, mlsid, status, lw_id
        @Output     : Send Email
        @Date       : 05-11-14
        @Note       : Remove "_add_email_in_joomla" if WS call enable. Added email from Joomla side so no need this.  05-05-2015
    */
    function change_property_status_add_email_in_joomla()
    {
        $post_data = $_REQUEST;

        $cdata['name'] 	= !empty($post_data['name'])?$post_data['name']:'';
        $cdata['price'] = !empty($post_data['price'])?$post_data['price']:0;
        $cdata['pid'] 	= !empty($post_data['pid'])?$post_data['pid']:0;
        $cdata['mlsid']	= !empty($post_data['mlsid'])?$post_data['mlsid']:0;
        $cdata['status'] = !empty($post_data['status'])?$post_data['status']:'';
        $lw_ids = !empty($post_data['lwid'])?$post_data['lwid']:0;
        $sdomain_name = !empty($post_data['sdomain'])?$post_data['sdomain']:'';
        
        $favlw_id  = !empty($post_data['flwid'])?$post_data['flwid']:0;
        $fdomain = !empty($post_data['fdomain'])?$post_data['fdomain']:'';
        
        $vrlw_id  = !empty($post_data['vaid'])?$post_data['vaid']:0;
        $vrdomain = !empty($post_data['vdomain'])?$post_data['vdomain']:'';
        
        // Remove curl logic 05-05-2015
        /*$joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/propertydata.php?id=".$cdata['pid'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzip encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $response = curl_exec($ch);
        curl_close($ch);
        $response = (json_decode($response, true));
        if(!empty($response['name'])) {
            $ssdata['property_name'] = $response['name'];
            $ssdata['property_price'] = $response['price'];
            //$ssdata['property_name'] = $response['name'];
        }
        */
        
        //// New
        $ssdata['property_name'] = $cdata['name'];
        $ssdata['property_price'] = $cdata['price'];
        
        //A -Active, CT - Contingent, PB - Pending Backup Offers Requested, PF - Pending Feasibility, PI - Pending Inspection, PS - Pending Short Sale, P - Pending, S - Sold
        if($cdata['status'] == 'A')
            $ssdata['status'] = 'Active';
        else if($cdata['status'] == 'P' || $cdata['status'] == 'PB' || $cdata['status'] == 'PF' || $cdata['status'] == 'PI' || $cdata['status'] == 'PS')
            $ssdata['status'] = 'Pending';
        else if($cdata['status'] == 'S')
            $ssdata['status'] = 'Sold';
        else if($cdata['status'] == 'CT')
            $ssdata['status'] = 'Contingent';
        else if($cdata['status'] == 'E')
            $ssdata['status'] = 'Expired';
        
        // Favorite property user list
       
        // NEW 15-11-2014
        
        if(!empty($fdomain) && count($fdomain) > 0)
        {
            $data = array();
            $i = 0;
            
            $data['property_name'] = $ssdata['property_name'];
            $data['property_price'] =  $ssdata['property_price'];
            $data['status'] = $ssdata['status'];
            foreach($fdomain as $ffdomain)
            {
                $ffdomain_name = str_replace('www.', '', $ffdomain);
                $ffdomain_name = trim($ffdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.email_id,lm.admin_name,lm.address,lm.phone,lm.brokerage_pic');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$ffdomain_name,'lm.status'=>'1','lm.user_type'=>'2');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                
                $fulist = array();
                if(!empty($favlw_id))
                {
                    if(!empty($favlw_id[$i]))
                    {   
                        $match = array('cm.id = '=>$favlw_id[$i]);
                        $fulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($fulist))
                        {
                            if(!empty($domain_result[0]['email_id']))
                                $from = $domain_result[0]['email_id'];
                            else
                                $from = $this->config->item('admin_email');

                            /*$full_name = 'Property Status Changed - '.$ffdomain_name;
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            */
                            
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Favorite property status has been changed - ".$ffdomain_name;
                            $agent_name = '';
                            if(!empty($fulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$fulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($fulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$fulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($fulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$fulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$fulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            if(!empty($fulist[0]['email_address']))
                            {
                                $to = $fulist[0]['email_address'];
                                $data['name'] = ucwords($fulist[0]['user_name']); 
                                $data['tabname'] = 'F';
                                $data['domain'] = $ffdomain_name;
                                $data['admin_name'] = !empty($domain_result[0]['admin_name'])?$domain_result[0]['admin_name']:'';
                                $data['admin_phone'] = !empty($domain_result[0]['phone'])?$domain_result[0]['phone']:'';
                                $data['admin_address'] = !empty($domain_result[0]['address'])?$domain_result[0]['address']:'';
                                $data['brokerage_pic'] = !empty($domain_result[0]['brokerage_pic'])?$domain_result[0]['brokerage_pic']:'';
                                
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                //echo $msg;exit;
                                echo "Favorite<br />";
                                echo $to.'<br />';
                              
                                
                                ///// Mailgun Email 19-03-2015
                                $edata['from_name'] = "Property Status Changed";
                                $edata['from_email'] = $from;

                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
                                //pr($response);
                                //$updata['id'] = $ins_data;
                                //$updata['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                //$this->property_contact_model->update_record($updata);
                                
                                /*if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Success";
                                else
                                    echo "Mail Not sent";*/
                            }
                        }
                    }
                }
                $i++;
            }
        } // END Favorite
        // End NEw 15-11-2014
        
        
        // Saved searches property user list
        if(!empty($sdomain_name) && count($sdomain_name) > 0)
        {
            $data = array();
            $i = 0;
            $data['property_name'] = $ssdata['property_name'];
            $data['property_price'] =  $ssdata['property_price'];
            $data['status'] = $ssdata['status'];
            foreach($sdomain_name as $ssdomain)
            {
                $ssdomain_name = str_replace('www.', '', $ssdomain);
                $ssdomain_name = trim($ssdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.email_id,lm.admin_name,lm.address,lm.phone,lm.brokerage_pic');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$ssdomain_name,'lm.status'=>'1','lm.user_type'=>'2');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);
                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                $sulist = array();
                //pr($lw_ids);exit;
                if(!empty($lw_ids))
                {
                    if(!empty($lw_ids[$i]))
                    {   
                        $match = array('cm.id = '=>$lw_ids[$i]);
                        $sulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($sulist))
                        {
                            if(!empty($domain_result[0]['email_id']))
                                $from = $domain_result[0]['email_id'];
                            else
                                $from = $this->config->item('admin_email');

                            /*$full_name = 'Property Status Changed - '.$ssdomain_name;
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            */
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Saved Searches property status has been changed - ".$ssdomain_name;
                            $agent_name = '';
                            if(!empty($sulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$sulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($sulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$sulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($sulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$sulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$sulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            
                            if(!empty($sulist[0]['email_address']))
                            {
                                $to = $sulist[0]['email_address'];
                                $data['name'] = ucwords($sulist[0]['user_name']);
                                $data['tabname'] = 'SS';
                                $data['domain'] = $ssdomain_name;
                                $data['admin_name'] = !empty($domain_result[0]['admin_name'])?$domain_result[0]['admin_name']:'';
                                $data['admin_phone'] = !empty($domain_result[0]['phone'])?$domain_result[0]['phone']:'';
                                $data['admin_address'] = !empty($domain_result[0]['address'])?$domain_result[0]['address']:'';
                                $data['brokerage_pic'] = !empty($domain_result[0]['brokerage_pic'])?$domain_result[0]['brokerage_pic']:'';
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                //echo $msg;exit;
                                echo "Saved Searches<br />";
                                echo $to.'<br />';
                                
                                ///// Mailgun Email 19-03-2015
                                $edata['from_name'] = "Property Status Changed";
                                $edata['from_email'] = $from;

                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
                                //pr($response);
                                //$updata['id'] = $ins_data;
                                //$updata['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                //$this->property_contact_model->update_record($updata);
                                
                                /*if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Sent";
                                else
                                    echo "Mail not send";*/
                            }
                        }
                    }
                }
                //// END
                $i++;
            }
        } // End Saved Searches
        
        // Code for Valuation report search criteria property list status change 29-11-2014
        if(!empty($vrdomain) && count($vrdomain) > 0)
        {
            $data = array();
            $i = 0;
            $data['property_name'] = $ssdata['property_name'];
            $data['property_price'] =  $ssdata['property_price'];
            $data['status'] = $ssdata['status'];
            foreach($vrdomain as $vr_domain)
            {   
                $vrdomain_name = str_replace('www.', '', $vr_domain);
                $vrdomain_name = trim($vrdomain_name,'/');
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.email_id,lm.admin_name,lm.address,lm.phone,lm.brokerage_pic');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$vrdomain_name,'lm.status'=>'1','lm.user_type'=>'2');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

                //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    $db = '';

                    $db['second']['hostname'] = $domain_result[0]['host_name'];
                    $db['second']['username'] = $domain_result[0]['db_user_name'];
                    //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
                    $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
                    $db['second']['database'] = $domain_result[0]['db_name'];
                    $db['second']['dbdriver'] = 'mysql';
                    $db['second']['dbprefix'] = '';
                    $db['second']['pconnect'] = TRUE;
                    $db['second']['db_debug'] = TRUE;
                    $db['second']['cache_on'] = FALSE;
                    $db['second']['cachedir'] = '';
                    $db['second']['char_set'] = 'utf8';
                    $db['second']['dbcollat'] = 'utf8_general_ci';
                    $db['second']['swap_pre'] = '';
                    $db['second']['autoinit'] = TRUE;
                    $db['second']['stricton'] = FALSE;

                    //pr($db['second']);exit;

                    //$this->legacy_db = $this->load->database($db['second'], TRUE);
                    $this->db = $this->load->database($db['second'], TRUE);

                }
                
                $email_temp = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'4');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=');

                if(!empty($autores_res) && count($autores_res) > 0)
                {
                    if(count($autores_res) > 1)
                    {
                        foreach($autores_res as $row)
                        {
                            if($row['template_name'] == 'Property Status Changed')
                            {
                                $email_temp = $row;
                            }
                        }
                    }
                    else {
                        $email_temp = $autores_res[0];
                    }
                }
                
                $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name,cm.created_by');
                $table = ' contact_master cm';
                $join_tables = array(
                    'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
                );
                $vrulist = array();
                //pr($lw_ids);exit;
                if(!empty($vrlw_id))
                {
                    if(!empty($vrlw_id[$i]))
                    {   
                        $match = array('cm.id = '=>$vrlw_id[$i]);
                        $vrulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);
                        
                        if(!empty($vrulist))
                        {
                            if(!empty($domain_result[0]['email_id']))
                                $from = $domain_result[0]['email_id'];
                            else
                                $from = $this->config->item('admin_email');

                            /*$full_name = 'Property Status Changed - '.$vrdomain_name;
                            $from = $full_name.'<'.$from.'>';
                            $headers = "From: " . $from . "\r\n";
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            */
                            if(!empty($email_temp['template_subject']))
                                $sub = $email_temp['template_subject'];
                            else
                                $sub = "Your Valuation Searches property status has been changed - ".$vrdomain_name;
                            $agent_name = '';
                            if(!empty($vrulist[0]['created_by']))
                            {
                                $table = "login_master as lm";   
                                $fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
                                $join_tables = array('user_master as um'=>'lm.user_id = um.id');
                                $wherestring = 'lm.id = '.$vrulist[0]['created_by'];
                                $agent_datalist = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
                                if(!empty($agent_datalist))
                                {
                                    if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
                                        $agent_name = $agent_datalist[0]['admin_name'];
                                    else
                                        $agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
                                }
                            }
                            if(!empty($email_temp['email_message']))
                            {
                                $emaildata = array(
                                    'Date'=>date('Y-m-d'),
                                    'Day'=>date('l'),
                                    'Month'=>date('F'),
                                    'Year'=>date('Y'),
                                    'Day Of Week'=>date("w",time()),
                                    'Agent Name'=>$agent_name,
                                    'Contact First Name'=>ucwords($vrulist[0]['first_name']),
                                    'Contact Spouse/Partner First Name'=>$vrulist[0]['spousefirst_name'],
                                    'Contact Last Name'=>ucwords($vrulist[0]['last_name']),
                                    'Contact Spouse/Partner Last Name'=>$vrulist[0]['spouselast_name'],
                                    'Contact Company Name'=>$vrulist[0]['company_name']
                                );

                                $pattern = "{(%s)}";
                                $map = array();

                                if($emaildata != '' && count($emaildata) > 0)
                                {
                                    foreach($emaildata as $var => $value)
                                    {
                                        $map[sprintf($pattern, $var)] = $value;
                                    }
                                    $output = strtr($email_temp['email_message'], $map);
                                    $data['email_temp'] = $output;
                                }
                            }
                            
                            if(!empty($vrulist[0]['email_address']))
                            {
                                $to = $vrulist[0]['email_address'];
                                $data['name'] = ucwords($vrulist[0]['user_name']);
                                $data['tabname'] = 'VR';
                                $data['domain'] = $vrdomain_name;
                                $data['admin_name'] = !empty($domain_result[0]['admin_name'])?$domain_result[0]['admin_name']:'';
                                $data['admin_phone'] = !empty($domain_result[0]['phone'])?$domain_result[0]['phone']:'';
                                $data['admin_address'] = !empty($domain_result[0]['address'])?$domain_result[0]['address']:'';
                                $data['brokerage_pic'] = !empty($domain_result[0]['brokerage_pic'])?$domain_result[0]['brokerage_pic']:'';
                                //$from=$this->config->item('admin_email');
                                $msg   = $this->load->view('ws/property_status_email', $data, TRUE);
                                echo "Valuation Searhced"."<br/>";
                                echo $to.'<br />';
                                
                                ///// Mailgun Email 19-03-2015
                                $edata['from_name'] = "Property Status Changed";
                                $edata['from_email'] = $from;

                                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
                                //pr($response);
                                //$updata['id'] = $ins_data;
                                //$updata['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                                //$this->property_contact_model->update_record($updata);
                                
                                /*if(mail($to,$sub,$msg,"-f".$headers))
                                    echo "Mail Sent";
                                else
                                    echo "Mail not send";*/
                            }
                        }
                    }
                }
                //// END
                $i++;
            }
        }
        
        $arr['MESSAGE']='SUCCESS';
        $arr['FLAG']=true;
        //$arr['EmailMessage']=$email_msg;
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for send email if new property matches to user saved searches
        @Author     : Sanjay Moghariya
        @Input      : property ids, lw_id
        @Output     : Send Email
        @Date       : 07-11-14
    */
    function new_property_email()
    {
        $post_data = $_REQUEST;
        $cdata = array();
        $cdata['pid'] 	= $post_data['cron_pid'];
        $cdata['lw_id'] = $post_data['lw_id'];
        $domain_name = $post_data['domain'];
        $domain_name = str_replace('www.', '', $domain_name);
        $domain_name = trim($domain_name,'/');
        
        //// For Getting Dynamic Database credential and connect to that database
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.".joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.email_id,lm.admin_name,lm.address,lm.phone,lm.brokerage_pic');
        $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('domain'=>$domain_name,'lm.status'=>'1','lm.user_type'=>'2');
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
        //echo $this->db->last_query();
        //
        //if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_user_password']) && !empty($domain_result[0]['db_name']))
        if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
        {
            $db = '';

            $db['second']['hostname'] = $domain_result[0]['host_name'];
            $db['second']['username'] = $domain_result[0]['db_user_name'];
            //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
            $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
            $db['second']['database'] = $domain_result[0]['db_name'];
            $db['second']['dbdriver'] = 'mysql';
            $db['second']['dbprefix'] = '';
            $db['second']['pconnect'] = TRUE;
            $db['second']['db_debug'] = TRUE;
            $db['second']['cache_on'] = FALSE;
            $db['second']['cachedir'] = '';
            $db['second']['char_set'] = 'utf8';
            $db['second']['dbcollat'] = 'utf8_general_ci';
            $db['second']['swap_pre'] = '';
            $db['second']['autoinit'] = TRUE;
            $db['second']['stricton'] = FALSE;

            //pr($db['second']);exit;

            //$this->legacy_db = $this->load->database($db['second'], TRUE);
            $this->db = $this->load->database($db['second'], TRUE);

        }

        /// END For Getting Dynamic Database credential and connect to that database
        

        $fields = array('CONCAT_WS(" ",cm.first_name,cm.last_name) as user_name,cet.email_address');
        $table = ' contact_master cm';
        $join_tables = array(
            'contact_emails_trans as cet' => 'cet.contact_id = cm.id',
        );
        
        $match = array('cm.id '=> $cdata['lw_id']);
        $sulist = $this->saved_searches_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','','','',$match);

        if(!empty($sulist) && !empty($sulist[0]['email_address']))
        {
            if(!empty($cdata['pid']))
            {
                //$property_ids = explode(',',$cdata['pid']);
                $property_ids = $cdata['pid'];
                //pr($property_ids);exit;
                if(!empty($property_ids))
                {
                    foreach($property_ids as $row)
                    {
                        //$url = "http://seattle.livewiresites.com/libraries/api/propertydata.php?id=".$row;
                        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                        $url = $joomla_link."/libraries/api/propertydata.php?id=".$row;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $response = (json_decode($response, true));
                        if(!empty($response['name'])) {
                            $data['property_name'][] = $response['name'];
                            $data['property_description'][] = $response['description'];
                        }
                    }
                }
            }
        
            $sub = "New Property added that matches your Saved Searches criteria - ".$domain_name;
            if(!empty($domain_result[0]['email_id']))
                $from = $domain_result[0]['email_id'];
            else
                $from = $this->config->item('admin_email');

            /*$full_name = 'New Property Added - '.$domain_name;
            $from = $full_name.'<'.$from.'>';
            $headers = "From: " . $from . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            */
            $to = $sulist[0]['email_address'];
            
            $data['name'] = ucwords($sulist[0]['user_name']);
            $data['domain'] = $domain_name;
            $data['admin_name'] = !empty($domain_result[0]['admin_name'])?$domain_result[0]['admin_name']:'';
            $data['admin_phone'] = !empty($domain_result[0]['phone'])?$domain_result[0]['phone']:'';
            $data['admin_address'] = !empty($domain_result[0]['address'])?$domain_result[0]['address']:'';
            $data['brokerage_pic'] = !empty($domain_result[0]['brokerage_pic'])?$domain_result[0]['brokerage_pic']:'';
            //$from=$this->config->item('admin_email');
            if(!empty($data['property_name'])) {
                $msg   = $this->load->view('ws/new_property_email', $data, TRUE);
                
                ///// Mailgun Email 19-03-2015
                $edata['from_name'] = "New Property Added";
                $edata['from_email'] = $from;

                $response = $this->email_campaign_master_model->MailSend($to,$sub,$msg,$edata);
                //pr($response);exit;
                //$updata['id'] = $ins_data;
                //$updata['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                //$this->property_contact_model->update_record($updata);
                
                /*if(mail($to,$sub,$msg,"-f".$headers)) {
                    //echo $msg;exit;
                    $arr['MESSAGE']='EMAIL SUCCESS';
                    $arr['FLAG']=true;
                } else {
                    $arr['MESSAGE']='EMAIL FAIL';
                    $arr['FLAG']=true;
                }*/
            }
        }
        else {
            $arr['MESSAGE']='FAIL';
            $arr['FLAG']=FALSE;
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for send email with pdf according to property cron setting
        @Author     : Sanjay Moghariya
        @Input      : State, City, neighborhood
        @Output     : Send Email with pdf (data fetch from zillow api)
        @Date       : 19-11-14
    */
    function get_neighborhood_data()
    {
        $state = $_REQUEST['state'];
        $city = $_REQUEST['city'];
        $neighborhood = $_REQUEST['neighborhood'];
        $url = "http://www.zillow.com/webservice/GetDemographics.htm?zws-id=X1-ZWz1b7njhnhvkb_6r0dc&state=".$state."&city=".$city."&neighborhood=".$neighborhood;
        $xml = simplexml_load_file($url);
        
        /*$json = json_encode($xml);
        $array = json_decode($json,TRUE);
        pr($array);
        exit;
        */
        
        $response = $xml->message->code;
        if($response == '0')
        {
            $data['zillow_data'] = $xml;
            $pdf_html = $this->load->view('ws/neighborhood_report_pdf', $data, TRUE);
            //pr($pdf_html);
            //exit;
            ///// PDF GENERATE CODE
            
            $mypdf = new mPDF('', '', '', '', '10', '10', '20', '20', '5', '8');
            $stylesheet = file_get_contents('css/pdfcrm.css'); // external css
            $mypdf->WriteHTML($stylesheet,1);
            $base_url = $this->config->item('base_url');
            $logo = $base_url."images/logo.png";
            //$mypdf->SetWatermarkImage($logo);
            //$mypdf->showWatermarkImage = true;
            $img_path = $this->config->item('image_path');
            $html = '';
            //$mypdf->SetHTMLHeader('<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">Neighborhood Data</div>', 'O', true);
            $mypdf->SetHTMLFooter('
                <table border="0" cellpadding="0" >
                    <tr>
                        <td class="footer">Neighborhood Data</td>
                        <td class="footer1"></td>
                    </tr>
                </table>
            ', 'O', true);
            
            $html .= $pdf_html;
            
            $mypdf->WriteHTML($html);
            
            //$mypdf->Output('neighborhood_data_'.date('m-d-Y').'.pdf','D');
            
            $filename = 'neighborhood_data_'.date('m-d-Y').'.pdf';
            
            /*$fileatt = $mypdf->Output($filename, 'S');
            $attachment = chunk_split($fileatt);
            $eol = PHP_EOL;
            $separator = md5(time());
            */
            $sub = "Property Valuation Report - Livewire CRM";
            $from = 'demotops@gmail.com';

            $full_name = 'LiveWireCRM';
            $from = $full_name.'<'.$from.'>';
            
            $content = $mypdf->Output('', 'S');

            $content = chunk_split(base64_encode($content));
            $mailto = 'sanjay.moghariya@tops-int.com';
            $from_name = 'LiveWireCRM';
            $from_mail = 'demotops@gmail.com';
            $replyto = 'demotops@gmail.com';
            $uid = md5(uniqid(time()));
            $subject = 'Property Valuation Report - Livewire CRM';
            $message = 'Please find attached pdf file which showing property valuation data based on neighborhood criteria.';

            $header = "From: ".$from_name." <".$from_mail.">\r\n";
            $header .= "Reply-To: ".$replyto."\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
            $header .= "This is a multi-part message in MIME format.\r\n";
            $header .= "--".$uid."\r\n";
            $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $header .= $message."\r\n\r\n";
            $header .= "--".$uid."\r\n";
            $header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
            $header .= "Content-Transfer-Encoding: base64\r\n";
            $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
            $header .= $content."\r\n\r\n";
            $header .= "--".$uid."--";
            $is_sent = @mail($mailto, $subject, "", $header);

            //$mypdf->Output();
            
            //$to = 'sanjay.moghariya@tops-int.com';
            //$msg   = $this->load->view('ws/user_register_email', $data, TRUE);
            //mail($to,$sub,$message,$headers);
            exit;
            ///////// END PDF
            
        }
        else
        {
            echo "Result not found.";
        }
        exit;
    }
    
    /*
        @Description: Function for get WordPress order details (Admin reg.)
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Insert data into Database
        @Date       : 26-11-2014
    */
    function wordpress_order_details()
    {
        $cdata['name'] 		= mysql_real_escape_string(urldecode($_REQUEST['name']));
        $cdata['email'] 	= mysql_real_escape_string(urldecode($_REQUEST['email']));
        $cdata['address']	= mysql_real_escape_string(urldecode($_REQUEST['address']));
        $cdata['phone']		= mysql_real_escape_string(urldecode($_REQUEST['phone']));
        $cdata['no_of_users']	= mysql_real_escape_string(urldecode($_REQUEST['no_of_users']));
        $cdata['password'] = !empty($_REQUEST['password'])?mysql_real_escape_string(urldecode($_REQUEST['password'])):'123456';
        $cdata['email_counter']	= mysql_real_escape_string(urldecode($_REQUEST['email_counter']));
        $cdata['sms_counter']	= mysql_real_escape_string(urldecode($_REQUEST['sms_counter']));
        $cdata['package_id']	= mysql_real_escape_string(urldecode($_REQUEST['package_id']));
        
        if(!empty($cdata['email']))
        {
            // Check Admin email exists or not.
            
            $fields = array('id,email_id');
            $match = array('email_id'=>$cdata['email']);
            $admin_res = $this->admin_model->get_user($fields,$match,'','=');
            if(!empty($admin_res) && count($admin_res) > 0)
            {
                $arr['MESSAGE'] = 'FAIL';
                $arr['FLAG'] = FALSE;
                $arr['data'] = 'Email Already Exists.';
            }
            else
            {
                $lastId = $this->create_database($cdata);
                if(!empty($lastId))
                {
                    $arr['MESSAGE']='SUCCESS';
                    $arr['FLAG']=true;
                    $arr['data']=$lastId;
                }
                else
                {
                    $arr['MESSAGE']='FAIL';
                    $arr['FLAG']=FALSE;
                }
            }
        }
        else
        {
            $arr['MESSAGE']='FAIL';
            $arr['FLAG']=FALSE;
            $arr['data']='Please enter Email Address';
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for create new database for user
        @Author     : Sanjay Moghariya (Copy from admin_management_control-> insert_data())
        @Input      : 
        @Output     : Create new database for user
        @Date       : 26-11-2014
    */
    function create_database($admin_data)
    {
        $newdatabasename = $this->admin_model->getnewdbname()+1;
        $databasename = $this->config->item('parent_db_prefix').md5(uniqid().$newdatabasename);
        $is_db_created = $this->admin_model->createnewdb($databasename);

        // Insert entry in master database of created db START

        $databaseusername = $this->config->item('parent_db_user_prefix').$newdatabasename;

        $is_dbuser_created = $this->admin_model->createnewdbuser($databaseusername);

        $adata['admin_name'] 		= $admin_data['name'];
        $adata['email_id']		= $admin_data['email'];
        $adata['password'] 		= $this->common_function_model->encrypt_script($admin_data['password']);
        $adata['db_name']		= $databasename;
        $adata['host_name']		= "localhost";
        $adata['db_user_name']		= $databaseusername;
        $adata['db_user_password']	= $databaseusername;
        $adata['user_type'] 		= '2';
        $adata['address']	= $admin_data['address'];
        $adata['phone']		= $admin_data['phone'];
        $adata['number_of_users_allowed']	= $admin_data['no_of_users'];
        $adata['remain_emails']	= !empty($admin_data['email_counter'])?$admin_data['email_counter']:0;
        $adata['remain_sms']	= !empty($admin_data['sms_counter'])?$admin_data['sms_counter']:0;
        
        // For getting Package data from Id
        if(!empty($admin_data['package_id']))
        {
            $match = array('id'=>$admin_data['package_id']);
            $result = $this->package_management_model->select_records('',$match,'','=');
            if(!empty($result) && count($result) > 0)
            {
                $adata['remain_emails'] = $result[0]['email_counter'];
                $adata['remain_sms'] = $result[0]['sms_counter'];
                //$adata['remain_contacts'] = $result[0]['contacts_counter'];
            }
        }
        
        //$cdata['created_by'] 		= $this->superadmin_session['id'];
        $adata['created_date'] 		= date('Y-m-d H:i:s');		
        $adata['status'] 		= '1';

        $lastId=$this->admin_model->insert_user($adata);
        if($is_db_created)
        {
            $parent_db = $this->config->item('parent_db_name');
            $child_db = $databasename;
            $this->admin_model->copyonedbtoother($parent_db,$child_db,$lastId,$databaseusername);
        }
        
        /*
            $url = "http://topsdemo.in/qa/livewire_crm/v.1.7/ws/user/wordpress_order_details?name=".$name."&email=".$email."&password=".$password."&address=".$address."&phone=".$phone."&no_of_users=".$no_of_users;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzidp encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
            $response = curl_exec($ch);
            curl_close($ch);
        */
        return $lastId;
    }
    
    /*
        @Description: Function for insert valuation saved searches
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 02-12-2014
    */
    function valuation_saved_searches()
    {
        $post_data = $_REQUEST;
        $time_zone = $this->config->item('default_timezone');
        //pr($_REQUEST)
        $cdata['lw_admin_id'] = $post_data['lwid'];
        $cdata['joomla_uid'] = $post_data['uid'];
        $cdata['search_address'] = urldecode($post_data['address']);
        $cdata['city'] = urldecode($post_data['city']);
        $cdata['state'] = urldecode($post_data['state']);
        $cdata['zip_code'] = urldecode($post_data['zip']);
        $domain = urldecode($post_data['domain']);
        $domain_name = str_replace('www.', '', $domain);
        $domain_name = trim($domain_name,'/');
        $cdata['domain'] = $domain_name;
        $cdata['send_report'] = 'Yes';	
        $cdata['report_timeline'] = 'Weekly';
        $cdata['status'] = '1';
        $reg_flag = $post_data['reg'];
        
        //// For Getting Dynamic Database credential and connect to that database
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.".joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
        $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

        if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
        {
            $db = '';

            $db['second']['hostname'] = $domain_result[0]['host_name'];
            $db['second']['username'] = $domain_result[0]['db_user_name'];
            //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
            $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
            $db['second']['database'] = $domain_result[0]['db_name'];
            $db['second']['dbdriver'] = 'mysql';
            $db['second']['dbprefix'] = '';
            $db['second']['pconnect'] = TRUE;
            $db['second']['db_debug'] = TRUE;
            $db['second']['cache_on'] = FALSE;
            $db['second']['cachedir'] = '';
            $db['second']['char_set'] = 'utf8';
            $db['second']['dbcollat'] = 'utf8_general_ci';
            $db['second']['swap_pre'] = '';
            $db['second']['autoinit'] = TRUE;
            $db['second']['stricton'] = FALSE;

            $this->db = $this->load->database($db['second'], TRUE);
            
            if(!empty($domain_result[0]['timezone']))
            {
                $time_zone = $domain_result[0]['timezone'];
            }
            $email_data['admin_name'] = $domain_result[0]['admin_name'];
            $email_data['admin_email'] = $domain_result[0]['email_id'];
            $email_data['admin_brokerage_pic'] = $domain_result[0]['brokerage_pic'];
            $email_data['admin_address'] = $domain_result[0]['address'];
            $email_data['admin_phone'] = $domain_result[0]['phone'];
        }
        /// END For Getting Dynamic Database credential and connect to that database
        
        if(!empty($domain_result[0]['timezone'])) {
            date_default_timezone_set($domain_result[0]['timezone']);
        } else {
            date_default_timezone_set($this->config->item('default_timezone'));
        }
        $cdata['created_date'] = date('Y-m-d H:i:s');
        
        $log_date = str_replace('::',' ',$post_data['date']); // This For Replace to joomla site Date
        $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
        $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
        $cdata['date'] 	= $fdate->format('Y-m-d H:i:s');
       
        if($reg_flag == 1) // If user already registered and then do valuation
        {
            $update_data['id'] = $cdata['lw_admin_id'];
            $fields = array('id,joomla_contact_type');
            $match = array('id'=>$cdata['lw_admin_id']);
            $reg_data = $this->user_registration_model->select_records($fields,$match,'','=');
            if(!empty($reg_data) && $reg_data[0]['joomla_contact_type'] == 'Buyer')
            {
                $update_data['joomla_contact_type'] = "Buyer/Seller";
            }
            /*else if(!empty($reg_data) && $reg_data[0]['joomla_contact_type'] == 'Buyer/Seller')
            {
                $update_data['joomla_contact_type'] = "Seller";
            }*/
            $this->user_registration_model->update_record($update_data);
        }
        else { // New Valuation Registration
            $update_data['id'] = $cdata['lw_admin_id'];
            $update_data['joomla_contact_type'] = "Seller";
            $this->user_registration_model->update_record($update_data);
        }
        
        $ins_data = $this->property_valuation_searches_model->insert_record($cdata);
        
        ///// Add Auto responder email Sanjay Moghariya 15-05-2015 /////
        $table = "contact_master as cm";
        $group_by = "cm.id";
        $match = array('cm.id'=>$cdata['lw_admin_id']);
        $fields = array('upt.phone_no as aphone_no,cm.first_name,cm.middle_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as assigned_agent_name','cpt.phone_no','cet.email_address','uct.user_id,lm.email_id');
        $join_tables = array(
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
            '(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
            '(select * from user_contact_trans where agent_type !="Lender") as uct'=>'uct.contact_id = cm.id',
            'user_master as um'=>'uct.user_id = um.id',
            'login_master as lm'=>'lm.user_id = um.id',
            '(SELECT * FROM user_phone_trans order by is_default desc) as upt' => 'upt.user_id = lm.user_id',
        );
        $contact_data =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'');

        if(!empty($contact_data))
        {
            $email_data['contact_name'] = $contact_data[0]['contact_name'];
            $email_data['contact_email'] = $contact_data[0]['email_address'];
            $email_data['contact_phone'] = $contact_data[0]['phone_no'];
            $aphone_no = $contact_data[0]['aphone_no'];
            if(!empty($contact_data[0]['assigned_agent_name']))
            {
                $email_data['agent_name'] = $contact_data[0]['assigned_agent_name'];
                $email_data['agent_email'] = $contact_data[0]['email_id'];
            } 
        }

        $autores_res = array();
        $fields = array('template_name,template_subject,email_message,email_event');
        $match = array('email_event'=>'9');
        $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

        if(!empty($autores_res[0]['template_subject'])) {
            $subject = $autores_res[0]['template_subject'];
            $subject .= !empty($domain)?' - '.$domain:'';
        }
        else {
            $d = !empty($domain)?' '.$domain:'';
            $subject = "Property Valuation Request from ".$d;
        }

        if(!empty($email_data['admin_email']))
            $from = $email_data['admin_email'];
        else
            $from = $this->config->item('admin_email');

        if(!empty($email_data['agent_email']))
            $to = $email_data['agent_email'];
        else
            $to = $from;

        $edata['from_name'] = "Property Valuation Request";
        $edata['from_email'] = $from;

        if(!empty($autores_res[0]['email_message']))
        {
            if(!empty($email_data['agent_name']))
                $a_nm = ucwords($email_data['agent_name']);
            else if(!empty($email_data['admin_name']))
                $a_nm = ucwords($email_data['admin_name']);
            else $a_nm = '';
            $emaildata = array(
                'Date'=>date('Y-m-d'),
                'Day'=>date('l'),
                'Month'=>date('F'),
                'Year'=>date('Y'),
                'Day Of Week'=>date("w",time()),
                'Agent Name'=>$a_nm,
                'Contact First Name'=> !empty($contact_data[0]['first_name'])?ucwords($contact_data[0]['first_name']):'',
                'Contact Spouse/Partner First Name'=>!empty($contact_data[0]['spousefirst_name'])?ucwords($contact_data[0]['spousefirst_name']):'',
                'Contact Last Name'=> !empty($contact_data[0]['last_name'])?ucwords($contact_data[0]['last_name']):'',
                'Contact Spouse/Partner Last Name'=> !empty($contact_data[0]['spouselast_name'])?ucwords($contact_data[0]['spouselast_name']):'',
                'Contact Company Name'=> !empty($contact_data[0]['company_name'])?ucwords($contact_data[0]['company_name']):''
            );

            $pattern = "{(%s)}";
            $map = array();

            if($emaildata != '' && count($emaildata) > 0)
            {
                foreach($emaildata as $var => $value)
                {
                    $map[sprintf($pattern, $var)] = $value;
                }
                $output = strtr($autores_res[0]['email_message'], $map);
                $email_data['msg_body'] = $output;
            }
        }
        $address = '';
        if(!empty($cdata['search_address'])) $address = $cdata['search_address'].',';
        if(!empty($cdata['city'])) $address .= $cdata['city'].', ';
        if(!empty($cdata['state'])) $address .= $cdata['state'].' ';
        if(!empty($cdata['zip_code'])) $address .= $cdata['zip_code'];
        $email_data['property_name'] =  trim($address,',');
        $message = $this->load->view('ws/property_valuation_request_email', $email_data, TRUE);

        //// Mailgun email
        $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
        //// End Auto responder email /////
        
        //// SMS Integration 15-05-2015 ////
        $to_phone = 0;
        if(!empty($aphone_no)) {
            $to_phone = $aphone_no;
        }
        else if(!empty($domain_result[0]['phone'])) {
            $to_phone = $domain_result[0]['phone']; 
        }
        if(!empty($to_phone))
        {
            $message = "Property valuation request send from "
                . "Name: ".(!empty($email_data['contact_name'])?ucfirst($email_data['contact_name']):'').". Email: ".(!empty($email_data['contact_email'])?ucfirst($email_data['contact_email']):''). ". Phone: ".(!empty($email_data['contact_phone'])?ucfirst($email_data['contact_phone']):'');

            $this->load->model('sms_texts_model');
            $fields = array('sms_message');
            $match = array('sms_event'=>'2');
            $template_data = $this->sms_texts_model->select_records($fields,$match,'','=','','','','','',$db_name);
            $emaildata = array(
                'Date'=>date('Y-m-d'),
                'Day'=>date('l'),
                'Month'=>date('F'),
                'Year'=>date('Y'),
                'Day Of Week'=>date( "w", time()),
                'Contact First Name'=>!empty($contact_data[0]['first_name'])?$contact_data[0]['first_name']:'',
                'Contact Spouse/Partner First Name'=>!empty($contact_data[0]['spousefirst_name'])?$contact_data[0]['spousefirst_name']:'',
                'Contact Last Name'=>!empty($contact_data[0]['last_name'])?$contact_data[0]['last_name']:'',
                'Contact Spouse/Partner Last Name'=>!empty($contact_data[0]['spouselast_name'])?$contact_data[0]['spouselast_name']:'',
                'Contact Company Name'=>!empty($contact_data[0]['company_name'])?$contact_data[0]['company_name']:''
            );
            $content = !empty($template_data[0]['sms_message'])?$template_data[0]['sms_message']:'';
            $pattern = "{(%s)}";
            $map = array();
            if($emaildata != '' && count($emaildata) > 0)
            {
                foreach($emaildata as $var => $value)
                {
                        $map[sprintf($pattern, $var)] = $value;
                }
                $output = strtr($content, $map);
            }
            $message = !empty($output)?$output:$message;
            
            $from = $this->config->item('from_sms');
            //$to_phone = '+919033921029';
            $send_from = !empty($domain_result[0]['lw_admin_id'])?$domain_result[0]['lw_admin_id']:0;
            $this->twilio->set_admin_id($send_from);
            $sms_response = $this->twilio->sms($from, $to_phone, $message);
        }
        //// END SMS Integration 15-05-2015 ////
        
        if(!empty($ins_data))
        {
                $arr['MESSAGE']='SUCCESS';
                $arr['FLAG']=true;
                $arr['data']=$ins_data;
        }
        else
        {
                $arr['MESSAGE']='FAIL';
                $arr['FLAG']=false;
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for insert valuation contact form details
        @Author     : Sanjay Moghariya
        @Input      : data and action=valuation (valution form), property (Request Showing), propertydetail (Property details form)
        @Output     : 
        @Date       : 09-03-2015
    */
    function valuation_contact_form()
    {
        $post_data = $_REQUEST;
        $time_zone = $this->config->item('default_timezone');
        //pr($_REQUEST)
        $action	= $post_data['action'];
        $cdata['uid'] = $post_data['uid'];
        $cdata['pid'] = $post_data['pid'];
        $cdata['mlsid']	= $post_data['mlsid'];
        $cdata['property_name'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['property_name'])));
        
        $domain = urldecode($post_data['domain']);
        $domain = str_replace('www.', '', $domain);
        $domain = trim($domain,'/');
        $cdata['domain'] = $domain;
        $cdata['lw_admin_id'] = $post_data['lwid'];
        $cdata['name'] = mysql_real_escape_string(urldecode(utf8_decode($post_data['name'])));
        $cdata['email'] = $post_data['email'];
        $phone_no = $post_data['phone'];
        if(strlen($phone_no) == 10) {
            $cdata['phone'] = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone_no);
        }
        
        $cdata['comments'] = addslashes(urldecode(utf8_decode($post_data['comments'])));
        $cdata['status'] = '1';
        
        //// For Getting Dynamic Database credential and connect to that database
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.".joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
        $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('jm.domain'=>$cdata['domain'],'lm.status'=>'1','lm.user_type'=>"2");
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

        if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
        {
            $db = '';

            $db['second']['hostname'] = $domain_result[0]['host_name'];
            $db['second']['username'] = $domain_result[0]['db_user_name'];
            //$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
            $db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
            $db['second']['database'] = $domain_result[0]['db_name'];
            $db['second']['dbdriver'] = 'mysql';
            $db['second']['dbprefix'] = '';
            $db['second']['pconnect'] = TRUE;
            $db['second']['db_debug'] = TRUE;
            $db['second']['cache_on'] = FALSE;
            $db['second']['cachedir'] = '';
            $db['second']['char_set'] = 'utf8';
            $db['second']['dbcollat'] = 'utf8_general_ci';
            $db['second']['swap_pre'] = '';
            $db['second']['autoinit'] = TRUE;
            $db['second']['stricton'] = FALSE;

            $this->db = $this->load->database($db['second'], TRUE);
            
            if(!empty($domain_result[0]['timezone']))
            {
                $time_zone = $domain_result[0]['timezone'];
            }
            $email_data['admin_email'] = $domain_result[0]['email_id'];
        }
        /// END For Getting Dynamic Database credential and connect to that database
        
        date_default_timezone_set($time_zone);
        
        $cdata['created_date'] = date('Y-m-d H:i:s');
        if($action == 'valuation') {
            $ins_data = $this->property_valuation_contact_model->insert_record($cdata);
        }
        else if($action == 'propertydetail') {
            $cdata['form_type'] = 'propertydetail';
            $ins_data = $this->property_contact_model->insert_record($cdata);
        }
        else {
            $cdata['form_type'] = 'property';
            $cdata['preferred_time'] = $post_data['time'];
            $ins_data = $this->property_contact_model->insert_record($cdata);
            if(!empty($ins_data))
            {
                ///// Mailgun Email 19-03-2015
                $email_data['admin_data'] = $domain_result;
                $email_data['lead_data'] = $cdata;
                
                ///// Add Auto responder email Sanjay Moghariya 15-05-2015 /////
                $table = "contact_master as cm";
                $group_by = "cm.id";
                $match = array('cm.id'=>$cdata['lw_admin_id']);
                $fields = array('upt.phone_no,cm.first_name,cm.middle_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.company_name','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as assigned_agent_name','uct.user_id,lm.email_id');
                $join_tables = array(
                    '(select * from user_contact_trans where agent_type !="Lender") as uct'=>'uct.contact_id = cm.id',
                    'user_master as um'=>'uct.user_id = um.id',
                    'login_master as lm'=>'lm.user_id = um.id',
                    '(SELECT * FROM user_phone_trans order by is_default desc) as upt' => 'upt.user_id = lm.user_id',
                );
                $contact_data =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'');

                if(!empty($contact_data))
                {
                    $email_data['contact_name'] = $cdata['name'];
                    $email_data['contact_email'] = $cdata['email'];
                    $email_data['contact_phone'] = $cdata['phone'];
                    if(!empty($contact_data[0]['assigned_agent_name']))
                    {
                        $email_data['agent_name'] = $contact_data[0]['assigned_agent_name'];
                        $email_data['agent_email'] = $contact_data[0]['email_id'];
                        $aphone_no = $contact_data[0]['phone_no'];
                    }
                }

                $autores_res = array();
                $fields = array('template_name,template_subject,email_message,email_event');
                $match = array('email_event'=>'8');
                $autores_res = $this->email_library_model->select_records($fields,$match,'','=','','','','','','','');

                if(!empty($autores_res[0]['template_subject'])) {
                    $subject = $autores_res[0]['template_subject'];
                    $subject .= !empty($domain)?' - '.$domain:'';
                }
                else {
                    $d = !empty($domain)?' '.$domain:'';
                    $subject = "Property Showing Request from ".$d;
                }

                if(!empty($email_data['admin_email']))
                    $from = $email_data['admin_email'];
                else
                    $from = $this->config->item('admin_email');

                if(!empty($email_data['agent_email']))
                    $to = $email_data['agent_email'];
                else
                    $to = $from;
                
                $edata['from_name'] = 'Property Showing Request';
                $edata['from_email'] = $from;
                if(!empty($autores_res[0]['email_message']))
                {
                    if(!empty($email_data['agent_name']))
                        $a_nm = ucwords($email_data['agent_name']);
                    else if(!empty($domain_result[0]['admin_name']))
                        $a_nm = ucwords($domain_result[0]['admin_name']);
                    else $a_nm = '';
                    $emaildata = array(
                        'Date'=>date('Y-m-d'),
                        'Day'=>date('l'),
                        'Month'=>date('F'),
                        'Year'=>date('Y'),
                        'Day Of Week'=>date("w",time()),
                        'Agent Name'=> $a_nm,
                        'Contact First Name'=> !empty($contact_data[0]['first_name'])?ucwords($contact_data[0]['first_name']):'',
                        'Contact Spouse/Partner First Name'=>!empty($contact_data[0]['spousefirst_name'])?ucwords($contact_data[0]['spousefirst_name']):'',
                        'Contact Last Name'=> !empty($contact_data[0]['last_name'])?ucwords($contact_data[0]['last_name']):'',
                        'Contact Spouse/Partner Last Name'=> !empty($contact_data[0]['spouselast_name'])?ucwords($contact_data[0]['spouselast_name']):'',
                        'Contact Company Name'=> !empty($contact_data[0]['company_name'])?ucwords($contact_data[0]['company_name']):''
                    );

                    $pattern = "{(%s)}";
                    $map = array();

                    if($emaildata != '' && count($emaildata) > 0)
                    {
                        foreach($emaildata as $var => $value)
                        {
                            $map[sprintf($pattern, $var)] = $value;
                        }
                        $output = strtr($autores_res[0]['email_message'], $map);
                        $email_data['msg_body'] = $output;
                    }
                }
                $message = $this->load->view('ws/property_request_form_email', $email_data, TRUE);
                //// End Auto responder email 
                
                //// Mailgun email
                $response = $this->email_campaign_master_model->MailSend($to,$subject,$message,$edata);
                //pr($response);
                $updata['id'] = $ins_data;
                $updata['mailgun_id'] = !empty($response->http_response_body->id)?substr(trim($response->http_response_body->id), 1, -1):'';
                $this->property_contact_model->update_record($updata);
                
                //// SMS Integration 20-03-2015
                $to_phone = 0;
                if(!empty($aphone_no)) {
                    $to_phone = $aphone_no;
                }
                else if(!empty($domain_result[0]['phone'])) {
                    $to_phone = $domain_result[0]['phone']; 
                }
                if(!empty($to_phone))
                {
                    $from = $this->config->item('from_sms');
                    $message = "Property showing request send from "
                            . "Name: ".ucfirst($cdata['name']).". Email: ".$cdata['email']. ". Phone: ".$cdata['phone'];
                    
                    $send_from = !empty($domain_result[0]['lw_admin_id'])?$domain_result[0]['lw_admin_id']:0;
                    //echo strlen($message);exit;
                    $this->twilio->set_admin_id($send_from);
                    $sms_response = $this->twilio->sms($from, $to_phone, $message);
                    pr($sms_response);
                }
            }
        }
        //echo $this->db->last_query();
        //exit;
        if(!empty($ins_data))
        {
                $arr['MESSAGE']='SUCCESS';
                $arr['FLAG']=true;
                $arr['data']=$ins_data;
        }
        else
        {
                $arr['MESSAGE']='FAIL';
                $arr['FLAG']=false;
        }
        echo json_encode($arr);	
    }
    
    /*
        @Description: Function for update agent and email for joomla
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 05-05-2015
    */
    function update_agent_data()
    {
        // Code for updating Agent name and email 05-05-2015
        $url = $this->config->item('joomla_webservice_link')."/libraries/api/all_user_data.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $response = curl_exec($ch);
        curl_close($ch); 
        $response = (json_decode($response, true));
        //pr($response);
        if(!empty($response['data']) && count($response['data']) > 0)
        {
            foreach($response['data'] as $row)
            {
                $domain = urldecode($row['domain']);
                $domain = str_replace('www.', '', $domain);
                $domain = trim($domain,'/');
                $domain_result = array();
                //// For Getting Dynamic Database credential and connect to that database
                $parent_db = $this->config->item('parent_db_name');
                $table = $parent_db.".joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,lm.admin_name,lm.email_id,lm.brokerage_pic,lm.address,lm.phone');
                $join_tables = array($parent_db.'.login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('jm.domain'=>$domain,'lm.status'=>'1','lm.user_type'=>"2");
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                
                if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
                    $datalist = array();
                    $admin_emailid =  urlencode(utf8_encode($domain_result[0]['email_id']));
                    $admin_name =  urlencode(utf8_encode($domain_result[0]['admin_name']));
                    $db_name = $domain_result[0]['db_name'];
                    
                    $table = $db_name.".user_contact_trans uct";
                    $group_by = "uct.contact_id";
                    $fields = array('uct.user_id');
                    $match = array('uct.contact_id'=>$row['lwid']);
                    $wherestring = 'uct.agent_type != "Lender"';
                    
                    $datalist =$this->contacts_model->getmultiple_tables_records($table,$fields,'','','',$match,'=','', '','','',$group_by,$wherestring);
                    if(!empty($datalist) && !empty($datalist[0]['user_id'])) 
                    {
                        $agent_data = array();
                        $agent_id = $datalist[0]['user_id'];
                        $table = $db_name.".user_master as um";
                        $fields = array('um.id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name','lm.db_name,lm.email_id,lm.brokerage_pic','upt.phone_no as phone','uat.address_line1,uat.address_line2,uat.city,uat.state,uat.zip_code');
                        $join_tables = array(
                            $db_name.'.login_master as lm' => 'um.id = lm.user_id',
                            '(SELECT uatin.* FROM '. $db_name.'.user_address_trans uatin GROUP BY uatin.user_id) AS uat'=>'uat.user_id = um.id',
                            '(SELECT uptin.* FROM '. $db_name.'.user_phone_trans uptin WHERE uptin.is_default = "1" GROUP BY uptin.user_id) AS upt'=>'upt.user_id = um.id',
                        );
                        $match = array('um.id'=>$agent_id);
                        $agent_data =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=');
                        
                        if(!empty($agent_data[0]))
                        {
                            $admin_emailid =  urlencode(utf8_encode($agent_data[0]['email_id']));
                            $admin_name =  urlencode(utf8_encode($agent_data[0]['admin_name']));
                        }
                    }
                    
                    // Code for updating Agent name and email 05-05-2015
                    $url = $this->config->item('joomla_webservice_link')."/libraries/api/update_agent.php?lwid=".$row['lwid']."&domain=".urlencode(utf8_encode($domain))."&name=".$admin_name."&email=".$admin_emailid;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzidp encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                    $response = curl_exec($ch);
                    curl_close($ch); 
                    //pr($response);
                    echo "lwid=".$row['lwid']."&domain=". urlencode(utf8_encode($domain))."&name=".urlencode(utf8_encode($admin_name))."&email=".urlencode(utf8_encode($admin_emailid));
                    echo "<br />";
                }
            }
        }
    }
    
    /*
        @Description: Function for Send SMS
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : 
        @Date       : 15-05-2015
    */
    public function AutoResponderSMSsend($db_name,$contact_data,$agent_id='')
    {
        $this->load->library('Twilio');
        $table = $db_name.".login_master as lm";
        $fields = array('lm.id,upt.phone_no,lm.phone,lm.user_type');
        $join_tables = array($db_name.'.user_master as um' => 'um.id = lm.user_id',
            '(SELECT * FROM '.$db_name.'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = lm.user_id',
        );
        $group_by = 'lm.id';
        if(!empty($agent_id))
            $match = array('lm.user_id'=>$agent_id);
        else
            $match = array('lm.user_type'=>'2');
        $assign_user_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by);

        if(!empty($assign_user_data))
        {
            if(!empty($assign_user_data[0]['user_type']) && ($assign_user_data[0]['user_type'] == 2 || $assign_user_data[0]['user_type'] == 5))
                $phone_no = $assign_user_data[0]['phone'];
            else
                $phone_no = $assign_user_data[0]['phone_no'];
        }
        $this->load->model('sms_texts_model');
        $fields = array('sms_message');
        if(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Buyer/Seller')
            $match = array('sms_event'=>'3');
        elseif(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Buyer')
            $match = array('sms_event'=>'4');
        elseif(!empty($contact_data['joomla_contact_type']) && $contact_data['joomla_contact_type'] == 'Seller')
            $match = array('sms_event'=>'5');
        $template_data = $this->sms_texts_model->select_records($fields,$match,'','=','','','','','',$db_name);
        $emaildata = array(
            'Date'=>date('Y-m-d'),
            'Day'=>date('l'),
            'Month'=>date('F'),
            'Year'=>date('Y'),
            'Day Of Week'=>date( "w", time()),
            'Contact First Name'=>!empty($contact_data['first_name'])?$contact_data['first_name']:'',
            'Contact Spouse/Partner First Name'=>!empty($contact_data['spousefirst_name'])?$contact_data['spousefirst_name']:'',
            'Contact Last Name'=>!empty($contact_data['first_name'])?$contact_data['first_name']:'',
            'Contact Spouse/Partner Last Name'=>!empty($contact_data['spouselast_name'])?$contact_data['spouselast_name']:'',
            'Contact Company Name'=>!empty($contact_data['company_name'])?$contact_data['company_name']:''
        );
        $content = !empty($template_data[0]['sms_message'])?$template_data[0]['sms_message']:'';
        $pattern = "{(%s)}";
        $map = array();
        if($emaildata != '' && count($emaildata) > 0)
        {
            foreach($emaildata as $var => $value)
            {
                    $map[sprintf($pattern, $var)] = $value;
            }
            $output = strtr($content, $map);
        }
        $message = !empty($output)?$output:$this->lang->line('new_lead_registered');
        if(!empty($assign_user_data[0]['id']) && !empty($phone_no))
        {
            //'+919033921029'
            $this->twilio->set_admin_id($assign_user_data[0]['id'],$db_name);
            $response = $this->twilio->sms($this->config->item('from_sms'),$phone_no,$this->lang->line('new_lead_assign_agent_msg'));
        }
    }
}