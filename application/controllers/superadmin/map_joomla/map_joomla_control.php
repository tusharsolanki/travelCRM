<?php 

/*
    @Description: Superadmin Map Joomla controller
    @Author: Ami Bhatti
    @Input: 
    @Output: 
    @Date: 08-10-14
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class map_joomla_control extends CI_Controller
{	
    function __construct()
    {
	    parent::__construct(); 
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		$this->message_session = $this->session->userdata('message_session');
	    check_superadmin_login();
		$this->load->model('map_joomla_model');
		$this->load->model('admin_model');
                $this->load->model('ws/user_registration_model');
                $this->load->model('contacts_model');
                $this->load->model('contact_masters_model');
                $this->load->model('ws/favorite_model');
                $this->load->model('ws/last_login_model');
                $this->load->model('ws/properties_viewed_model');
                $this->load->model('ws/saved_searches_model');
                $this->load->model('ws/property_valuation_searches_model');
                $this->load->model('property_valuation_contact_model');
                $this->load->model('property_contact_model');
   	    $this->obj = $this->map_joomla_model;
	    $this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';

    }
	

    /*
    @Description: Function for Get All Superadmin List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Superadmin list
    @Date: 30-08-2014
    */

    public function index()
    {	
	
		//echo "Ami";exit;	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('map_joomla_sortsearchpage_data');
		}
		$searchsort_session = $this->session->userdata('map_joomla_sortsearchpage_data');
		if(!empty($sortfield) && !empty($sortby))
		{
			//$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			//$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			if(!empty($searchsort_session['sortfield'])) {
				if(!empty($searchsort_session['sortby'])) {
					$data['sortfield'] = $searchsort_session['sortfield'];
					$data['sortby'] = $searchsort_session['sortby'];
					$sortfield = $searchsort_session['sortfield'];
					$sortby = $searchsort_session['sortby'];
				}
			} else {
				$sortfield = 'id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
		} else {
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
/*					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

				}
			}
		}
		if(!empty($perpage))
		{
			//$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
        else
		{
			if(!empty($searchsort_session['perpage'])) {
				$data['perpage'] = trim($searchsort_session['perpage']);
				$config['per_page'] = trim($searchsort_session['perpage']);
			} else {
				$config['per_page'] = '10';
			}
		}
		
		$config['base_url'] = site_url($this->user_type.'/'."map_joomla/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
		
		/*if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }*/
		
		/*if(!empty($searchtext))
		{
			$match=array('domain'=>$searchtext);			
			$data['datalist'] = $this->obj->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			$config['total_rows'] = count($this->obj->get_user('',$match,'','like',''));
			if($config['total_rows'] == '0')
			{
				$match=array('admin_name'=>$searchtext);			
				$data['datalist'] = $this->obj->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->admin_model->get_user('',$match,'','like',''));
				//echo $this->db->last_query();exit;
			}
			pr($config['total_rows']);exit;
			//echo $this->db->last_query();exit;
		}*/
		$table = "joomla_mapping as jm";
        $fields = array('jm.id','jm.status','CONCAT_WS(" ",jm.domain) as domain','lm.admin_name','lm.email_id');
		if(!empty($searchtext))
        {

            $join_tables = array(
				'login_master as lm' => 'lm.id = jm.lw_admin_id'
                /*'joomla_mapping as jm' => 'jm.lw_admin_id = lm.id',	*/			
            );

            $match=array('CONCAT_WS(" ",jm.domain)'=>$searchtext,'CONCAT_WS(" ",lm.admin_name)'=>$searchtext);
			
			
            /*$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
            $config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));*/

            /////////////

            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'');
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','like','','','','','','','','1');
			//echo $this->db->last_query();exit;
            
            /////////////

        }
		else
		{
		
			 $join_tables = array(
                'login_master as lm' => 'lm.id = jm.lw_admin_id'
            );

            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'');
			//echo $this->db->last_query();exit;
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','','','','1');
		
			//echo $this->db->last_query();exit;
			/*$match=array();
			//$match=array('created_type'=>'6');
			//$where=array('created_type'=>'6');
			$data['datalist'] = $this->obj->get_user('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$config['total_rows']= count($this->obj->get_user('',$match,'','='));
			//pr($data['datalist']);
			for($i=0;$i<count($data['datalist']);$i++)
			{
			
				$field = array('admin_name');
				$match = array('id'=>$data['datalist'][$i]['lw_admin_id'],'user_type'=>'2');
        		$result = $this->admin_model->get_user($field,$match,'','=');
				
			
			  $parth[] = $result[0]['admin_name'];
				//echo $this->db->last_query();
			//////pr($result);
				//exit;
				
			}
			
			//pr($parth);
			$data['admin_name'] = $parth;*/
			
			/*echo $this->db->last_query();
			pr($config['total_rows']);exit;*/
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		$sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
				'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
		$this->session->set_userdata('map_joomla_sortsearchpage_data', $sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		
		//pr($data['datalist']);exit;
		
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('superadmin/include/template',$data);
		}
    }


    /*
    @Description: Function Add New Superadmin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Superadmin details
    @Date: 30-08-2014
    */
   
    public function add_record()
    {
		
		$field = array('admin_name','id','email_id');
		$match = array('user_type'=>'2');
        $result = $this->admin_model->get_user($field,$match,'','=');
		$data['admin_name'] = $result;
		//pr($result);
		/*echo $this->db->last_query();
		pr($result);
		exit;*/
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

   
   /*
    @Description: Function Copy superadmin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for copy superadmin details
    @Date: 30-08-2014
    */
   
    public function copy_record()
    {
		/*$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['admin_name'] = $result[0]['admin_name'].'-copy';
		$cdata['email_id'] = $result[0]['email_id'];
		$cdata['password'] = $result[0]['password'];
		$cdata['user_type'] = '1';
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$lastId=$this->obj->insert_record($cdata);*/
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
	
	}
  
    /*
    @Description: Function for Insert New Superadmin data
    @Author: Mohit Trivedi
    @Input: - Details of new Superadmin which is inserted into DB
    @Output: - List of Superadmin with new inserted records
    @Date: 30-08-2014
    */
   
    public function insert_data()
     {
        ?>
        <style>
			body{ position:relative;}
		</style>
        <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src="<?=base_url('images/loading.gif')?>" /><br />Please wait... It will take some time to import leads from domain...</div>

        <?php
        //echo "Ami"; 
        $cdata['lw_admin_id'] = $this->input->post('lw_admin_id');
        $domain = $this->input->post('domain');
        $domain = str_replace('www.', '', $domain);
        $domain = trim($domain,'/');
        /*echo substr($domain, 0, strpos($domain, '//'));
        exit;
        $pos = strpos('http://',$domain);
        if($pos === false) {
         $domain = 'http://'.$domain;
        }*/
        $cdata['domain'] = $domain;
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $lastId=$this->obj->insert_record($cdata);

        //Joomla call to retrive contact details 24-11-2014
        $joomla_url = $cdata['domain']."/libraries/api/contact.php?domain=".$cdata['domain'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $joomla_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzip encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $response = curl_exec($ch);
        curl_close($ch);
        $response = (json_decode($response, true));   
        
        //// For Getting Dynamic Database credential and connect to that database
        $table = "joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone,');
        $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');

        if(!empty($domain_result[0]['timezone'])) {
            $time_zone = $domain_result[0]['timezone'];
            date_default_timezone_set($domain_result[0]['timezone']);
        } else {
            $time_zone = $this->config->item('default_timezone');
            date_default_timezone_set($this->config->item('default_timezone'));
        }
        if(!empty($response['data']) && count($response['data']) > 0)
        {
            //// For Getting Dynamic Database credential and connect to that database
            /*$table = "joomla_mapping as jm";
            $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
            $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
            $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
            $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
            */
            if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
            {
                $db_name = $domain_result[0]['db_name'];
                foreach($response['data'] as $row)
                {
                    $con_data['first_name'] = '';$con_data['middle_name'] = '';$con_data['last_name'] = '';
                    if($row['fname'] != '-')
                        $con_data['first_name'] = $row['fname'];
                    if($row['mname'] != '-')
                        $con_data['middle_name'] = $row['mname'];
                    if($row['lname'] != '-')
                        $con_data['last_name'] = $row['lname'];
                    /*$con_data['first_name'] = $row['fname'];
                    $con_data['middle_name'] = $row['mname'];
                    $con_data['last_name'] = $row['lname'];*/
                    $con_data['joomla_user_id'] = $row['user_id'];
                    $con_data['joomla_domain_name'] = $cdata['domain'];
                    //$con_data['joomla_address'] = $row['full_property_address'];
                    $address_from_ip = $row['full_property_address'];
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

                        $con_data['joomla_address'] = $joomla_address;
                    }
                    $con_data['created_type'] = '6';
                    $con_data['created_date']  = date('Y-m-d H:i:s');
                    $con_data['status'] = '1';
                    $con_data['is_valuation_contact'] = !empty($row['val_reg'])?$row['val_reg']:'No';
                    $con_data['price_range_from'] = !empty($row['min_price'])?$row['min_price']:0;
                    $con_data['price_range_to'] = !empty($row['max_price'])?$row['max_price']:0;
                    $con_data['min_area'] = !empty($row['min_area'])?$row['min_area']:0;
                    $con_data['max_area'] = !empty($row['max_area'])?$row['max_area']:0;
                    $con_data['house_style'] = !empty($row['property_type'])?$row['property_type']:'';
                    $con_data['area_of_interest'] = !empty($row['city_area'])?$row['city_area']:'';
                    $con_data['no_of_bedrooms'] = !empty($row['bedrooms'])?$row['bedrooms']:0;
                    $con_data['no_of_bathrooms'] = !empty($row['bathrooms'])?$row['bathrooms']:0;
                    $con_data['joomla_timeframe'] = $row['timeframe'];;
                    if($row['have_house_to_sell'] == 'yes')
                        $con_data['joomla_contact_type'] = 'Seller';
                    else
                        $con_data['joomla_contact_type'] = 'Buyer';

                    $ins_data = $this->user_registration_model->insert_record($con_data,$db_name);

                    $edata['contact_id'] = $ins_data;
                    $edata['email_type'] = '0';
                    $edata['email_address'] = $row['email'];
                    $edata['is_default '] = '1';
                    $edata['status '] = '1';
                    $ins_data_email = $this->user_registration_model->insert_record_email($edata,$db_name);

                    $phdata['phone_no'] = $row['tel'];
                    $phdata['contact_id'] = $ins_data;
                    $phdata['phone_type'] = 0;
                    $phdata['is_default'] = '1';
                    $phdata['status'] = '1';

                    $ins_data_phone = $this->contacts_model->insert_phone_trans_record($phdata,$db_name);

                    // Code for updating livewire id
                    $url = $cdata['domain']."/libraries/api/update_lwid.php?lwid=".$ins_data."&userid=".$row['user_id'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzidp encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                    $lw_update = curl_exec($ch);
                    curl_close($ch);
                    
                    //Joomla call to retrive contact's favorite details
                    $favorite_url = $cdata['domain']."/libraries/api/favourite.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $favorite_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $favorite_response = curl_exec($ch);
                    curl_close($ch);
                    $favorite_response = (json_decode($favorite_response, true));
                    if(!empty($favorite_response) && count($favorite_response['data']) > 0)
                    {
                        foreach($favorite_response['data'] as $fav_row)
                        {
                            $fav_data['uid'] = $fav_row['uid'];
                            $fav_data['pid'] = $fav_row['pid'];
                            $fav_data['propery_name'] = $fav_row['name'];
                            $fav_data['mlsid'] = !empty($fav_row['mls_id'])?$fav_row['mls_id']:0;
                            
                            $log_date = str_replace('::',' ',$fav_row['date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $fav_data['date'] = $fdate->format('Y-m-d H:i:s');
                            //$fav_data['date'] = $fav_row['date'];
                            $fav_data['lw_admin_id'] = $ins_data;
                            $fav_data['domain'] = $cdata['domain'];
                            $fav_data['created_date'] = $fav_data['date'];
                            //$fav_data['created_date'] = $fav_row['date'];
                            $fav_data['status'] = '1';
                            
                            $fields = array('id');
                            $match = array('lw_admin_id'=>$fav_data['lw_admin_id'],'pid'=>$fav_data['pid'],'mlsid'=>$fav_data['mlsid']);
                            $fresult = $this->favorite_model->select_records($fields,$match,'','=');
                            if(empty($fresult)) {
                                $fav_res = $this->favorite_model->insert_record($fav_data,$db_name);
                            }
                        }
                    }

                    //Joomla call to retrive contact's Last login details
                    $lastlogin_url = $cdata['domain']."/libraries/api/lastlogin.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $lastlogin_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $lastlogin_response = curl_exec($ch);
                    curl_close($ch);
                    $lastlogin_response = (json_decode($lastlogin_response, true));
                    
                    if(!empty($lastlogin_response) && count($lastlogin_response['data']) > 0)
                    {
                        foreach($lastlogin_response['data'] as $ll_row)
                        {
                            $ll_data['uid'] = $ll_row['user_id'];
                            $log_date = str_replace('::',' ',$ll_row['log_date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $ll_data['log_date'] = $fdate->format('Y-m-d H:i:s');
                            //$ll_data['log_date'] = $ll_row['log_date'];
                            $ll_data['lw_admin_id'] = $ins_data;
                            $ll_data['ip'] = $ll_row['ip'];
                            $ll_data['domain'] = $cdata['domain'];
                            $ll_data['created_date'] = $ll_data['log_date'];
                            $ll_data['status'] = '1';
                            $ll_res = $this->last_login_model->insert_record($ll_data,$db_name);
                        }
                    }

                    //Joomla call to retrive contact's Properties viewed details
                    $pview_url = $cdata['domain']."/libraries/api/pview.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $pview_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $pview_response = curl_exec($ch);
                    curl_close($ch);
                    $pview_response = (json_decode($pview_response, true));

                    if(!empty($pview_response) && count($pview_response['data']) > 0)
                    {
                        foreach($pview_response['data'] as $pview_row)
                        {
                            $pview_data['uid'] = $pview_row['user_id'];
                            $pview_data['mlsid'] = $pview_row['mls_id'];
                            $pview_data['propery_name'] = $pview_row['name'];
                            
                            $log_date = str_replace('::',' ',$pview_row['log_date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $pview_data['log_date'] = $fdate->format('Y-m-d H:i:s');
                            //$pview_data['log_date'] = $pview_row['log_date'];
                            $pview_data['lw_admin_id'] = $ins_data;
                            $pview_data['domain'] = $cdata['domain'];
                            $pview_data['created_date'] = $pview_data['log_date'];
                            //$pview_data['created_date'] = $pview_row['log_date'];
                            $pview_data['status'] = '1';

                            $fields = array('id,views');
                            $match = array('mlsid'=>$pview_row['mls_id'],'domain'=>$cdata['domain'],'lw_admin_id'=>$ins_data);
                            $property_data = $this->properties_viewed_model->select_records($fields,$match,'','=','','','','','',$db_name);

                            if(!empty($property_data))
                            {
                                $pview_edata['id'] = $property_data[0]['id'];
                                $pview_edata['views'] = $property_data[0]['views'] + 1;
                                $pview_res = $this->properties_viewed_model->update_record($pview_edata,$db_name);
                            }
                            else
                            {
                                $pview_data['views'] = 1;
                                $pview_res = $this->properties_viewed_model->insert_record($pview_data,$db_name);
                            }
                        }
                    }

                    //Joomla call to retrive contact's Saved searches details
                    //$savedsearches_url = "http://seattle.livewiresites.com/libraries/api/savesearch.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                    $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                    //$joomla_link = 'http://prod.seattlerealestatetoday.com';
                    $savedsearches_url = $joomla_link."/libraries/api/savesearch.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $savedsearches_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $savedsearches_response = curl_exec($ch);
                    curl_close($ch);
                    $savedsearches_response = (json_decode($savedsearches_response, true));

                    if(!empty($savedsearches_response) && count($savedsearches_response['data']) > 0)
                    {
                        foreach($savedsearches_response['data'] as $savedsearches_row)
                        {
                            $savedsearches_data['uid'] = $savedsearches_row['uid'];
                            $savedsearches_data['name'] = $savedsearches_row['name'];
                            
                            $savedsearches_data['min_price'] = trim($savedsearches_row['min_price']);
                            $savedsearches_data['max_price'] = trim($savedsearches_row['max_price']);
                            $savedsearches_data['bedroom'] = trim($savedsearches_row['bedroom']);
                            $savedsearches_data['bathroom'] = trim($savedsearches_row['bathroom']);
                            $savedsearches_data['min_area'] = trim($savedsearches_row['min_area']);
                            $savedsearches_data['max_area'] = trim($savedsearches_row['max_area']);
                            $savedsearches_data['min_year_built'] = trim($savedsearches_row['min_year_built']);
                            $savedsearches_data['max_year_built'] = trim($savedsearches_row['max_year_built']);
                            $savedsearches_data['fireplaces_total'] = trim($savedsearches_row['fireplaces_total']);
                            $savedsearches_data['min_lotsize'] = trim($savedsearches_row['min_lotsize']);
                            $savedsearches_data['max_lotsize'] = trim($savedsearches_row['max_lotsize']);
                            $savedsearches_data['garage_spaces'] = trim($savedsearches_row['garage_spaces']);
                            $savedsearches_data['architecture'] = !empty($savedsearches_row['architecture'])?$savedsearches_row['architecture']:'';
                            $savedsearches_data['school_district'] = !empty($savedsearches_row['school_district'])?$savedsearches_row['school_district']:'';
                            $savedsearches_data['waterfront'] = !empty($savedsearches_row['waterfront'])?$savedsearches_row['waterfront']:'';
                            $savedsearches_data['s_view'] = !empty($savedsearches_row['view'])?$savedsearches_row['view']:'';
                            $savedsearches_data['parking_type'] = !empty($savedsearches_row['parking_type'])?$savedsearches_row['parking_type']:'';
                            //$savedsearches_data['search_criteria'] = urldecode(utf8_decode($savedsearches_row['search_criteria']));
                            $savedsearches_data['created_type'] = '2';
                            $savedsearches_data['lw_admin_id'] = $ins_data;
                            $savedsearches_data['domain'] = $cdata['domain'];
                            
                            $log_date = str_replace('::',' ',$savedsearches_row['add_date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $savedsearches_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                            //$savedsearches_data['created_date'] = $savedsearches_row['add_date'];
                            $savedsearches_data['status'] = '1';
                            $savedsearches_res = $this->saved_searches_model->insert_record($savedsearches_data,$db_name);
                        }
                    }
                    
                    //Joomla call to retrive contact's valuation searched details
                    $vs_url = $cdata['domain'].'/libraries/api/valution_data.php?lwid='.$ins_data.'&domain='.$cdata['domain'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $vs_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $vs_response = curl_exec($ch);
                    curl_close($ch);
                    $vs_response = (json_decode($vs_response, true));
                    //pr($vs_response);exit;
                    if(!empty($vs_response) && count($vs_response['data']) > 0)
                    {
                        foreach($vs_response['data'] as $vs_row)
                        {
                            $vs_data['lw_admin_id'] = $ins_data;
                            $vs_data['joomla_uid'] = $vs_row['uid'];
                            $vs_data['search_address'] = $vs_row['address'];
                            $vs_data['city'] = $vs_row['city'];
                            $vs_data['state'] = $vs_row['state'];
                            $vs_data['zip_code'] = $vs_row['zip'];
                            $vs_data['date'] = date('Y-m-d H:i:s',strtotime($vs_row['date']));
                            $log_date = str_replace('::',' ',$vs_data['date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $vs_data['date'] = $fdate->format('Y-m-d H:i:s');
                            $vs_data['domain'] = $cdata['domain'];
                            $vs_data['send_report'] = 'Yes';
                            $vs_data['report_timeline'] = 'Weekly';
                            $vs_data['created_date'] = $vs_data['date'];
                            $vs_data['status'] = '1';
                            $vs_res = $this->property_valuation_searches_model->insert_record($vs_data,$db_name);
                        }
                    }
                    
                    //Joomla call to retrive valuation contact form data
                    $valform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=v";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $valform_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $valform_response = curl_exec($ch);
                    curl_close($ch);
                    $valform_response = (json_decode($valform_response, true));
                    if(!empty($valform_response) && count($valform_response['data']) > 0)
                    {
                        foreach($valform_response['data'] as $valform_row)
                        {
                            $valform_data['uid'] = $valform_row['uid'];
                            $valform_data['pid'] = $valform_row['pid'];
                            $valform_data['mlsid'] = $valform_row['mlsid'];
                            $valform_data['property_name'] = $valform_row['property_name'];
                            $valform_data['domain'] = $cdata['domain'];
                            $valform_data['lw_admin_id'] = $ins_data;
                            $valform_data['name'] = $valform_row['name'];
                            $valform_data['email'] = $valform_row['email'];
                            $valform_data['phone'] = $valform_row['phone'];
                            $valform_data['comments'] = $valform_row['comments'];
                            
                            $log_date = str_replace('::',' ',$valform_row['date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $valform_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                            //$valform_data['created_date'] = $valform_row['date'];
                            $valform_data['status'] = '1';
                            
                            $valform_res = $this->property_valuation_contact_model->insert_record($valform_data,$db_name);
                        }
                    }
                    
                    //Joomla call to retrive contact form data
                    $conform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=c";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $conform_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $conform_response = curl_exec($ch);
                    curl_close($ch);
                    $conform_response = (json_decode($conform_response, true));
                    if(!empty($conform_response) && count($conform_response['data']) > 0)
                    {
                        foreach($conform_response['data'] as $conform_row)
                        {
                            $conform_data['uid'] = $conform_row['uid'];
                            $conform_data['pid'] = $conform_row['pid'];
                            $conform_data['mlsid'] = $conform_row['mlsid'];
                            $conform_data['property_name'] = $conform_row['property_name'];
                            $conform_data['domain'] = $cdata['domain'];
                            $conform_data['lw_admin_id'] = $ins_data;
                            $conform_data['name'] = $conform_row['name'];
                            $conform_data['email'] = $conform_row['email'];
                            $conform_data['phone'] = $conform_row['phone'];
                            $conform_data['comments'] = $conform_row['comments'];
                            $conform_data['preferred_time'] = $conform_row['time'];
                            $conform_data['form_type'] = 'property';
                            
                            $log_date = str_replace('::',' ',$conform_row['date']); // This For Replace to joomla site Date
                            $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                            $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                            $conform_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                            //$conform_data['created_date'] = $conform_row['date'];
                            $conform_data['status'] = '1';
                            
                            $conform_res = $this->property_contact_model->insert_record($conform_data,$db_name);
                        }
                    }
                    
                    //Joomla call to retrive contact details form data 18-03-2015
                    /*$conform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=cd";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $conform_url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                    // This is what solved the issue (Accepting gzip encoding)
                    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                    $conform_response = curl_exec($ch);
                    curl_close($ch);
                    $conform_response = (json_decode($conform_response, true));
                    if(!empty($conform_response) && count($conform_response['data']) > 0)
                    {
                        foreach($conform_response['data'] as $conform_row)
                        {
                            $conform_data['uid'] = $conform_row['uid'];
                            $conform_data['pid'] = $conform_row['pid'];
                            $conform_data['mlsid'] = $conform_row['mlsid'];
                            $conform_data['property_name'] = $conform_row['property_name'];
                            $conform_data['domain'] = $cdata['domain'];
                            $conform_data['lw_admin_id'] = $ins_data;
                            $conform_data['name'] = $conform_row['name'];
                            $conform_data['email'] = $conform_row['email'];
                            $conform_data['phone'] = $conform_row['phone'];
                            $conform_data['comments'] = $conform_row['comments'];
                            $conform_data['form_type'] = 'propertydetail';
                            $conform_data['created_date'] = $conform_row['date'];
                            $conform_data['status'] = '1';
                            
                            $conform_res = $this->property_contact_model->insert_record($conform_data,$db_name);
                        }
                    }*/
                }
            }
        }
        //End Joomla call to retrive contact details
        
        //Joomla call to retrive contact form details without logic
        /*$pform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=&domain=".$cdata['domain']."&action=cn";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pform_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzip encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $presponse = curl_exec($ch);
        curl_close($ch);
        $presponse = (json_decode($presponse, true));   
        
        if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
        {
            $db_name = $domain_result[0]['db_name'];
            if(!empty($presponse) && count($presponse['data']) > 0)
            {
                foreach($presponse['data'] as $pform_row)
                {
                    $pform_data['uid'] = $pform_row['uid'];
                    $pform_data['pid'] = $pform_row['pid'];
                    $pform_data['mlsid'] = $pform_row['mlsid'];
                    $pform_data['property_name'] = $pform_row['property_name'];
                    $pform_data['domain'] = $cdata['domain'];
                    $pform_data['name'] = $pform_row['name'];
                    $pform_data['email'] = $pform_row['email'];
                    $pform_data['phone'] = $pform_row['phone'];
                    $pform_data['comments'] = $pform_row['comments'];
                    $pform_data['preferred_time'] = $pform_row['time'];
                    $pform_data['created_date'] = $pform_row['date'];
                    $conform_data['form_type'] = 'property';
                    $pform_data['status'] = '1';
                    $pform_res = $this->property_contact_model->insert_record($pform_data,$db_name);
                }
            }
        }*/

        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        ?>
        <script type="text/javascript">window.location.href = "<?=base_url().'superadmin/'.$this->viewName;?>"</script>
        <?php
        //redirect('superadmin/'.$this->viewName);
		
     }
 
    /*
    @Description: Get Details of Edit Superadmin Profile
    @Author: Mohit Trivedi
    @Input: - Id of superadmin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 30-08-2014
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['editRecord'] = $result;
		/*echo $this->db->last_query();
		pr($result);
		exit;*/
		
		$field = array('admin_name','id','email_id');
		$match = array('user_type'=>'2');
        $result1 = $this->admin_model->get_user($field,$match,'','=');
		$cdata['admin_name'] = $result1;
		/*echo $this->db->last_query();
		pr($result1);*/
		
		
		
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Superadmin Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Superadmin
    @Output: - List with updated Superadmin details
    @Date: 30-08-2014
    */
   
    public function update_data()
    {
        ?>
        <style>
            body{ position:relative;}
        </style>
        <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src="<?=base_url('images/loading.gif')?>" /><br />Please wait... It will take some time to import leads from domain...</div>

        <?php
        $cdata['id'] = $this->input->post('id');
        $cdata['lw_admin_id'] = $this->input->post('lw_admin_id');
        $domain = $this->input->post('domain');
        //$domain = str_replace('http://', '', $domain);
        $domain = str_replace('www.', '', $domain);
        $domain = trim($domain,'/');
        
        $cdata['domain'] = $domain;
        $cdata['modified_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $this->obj->update_record($cdata);
        
        //// For Getting Dynamic Database credential and connect to that database
        $table = "joomla_mapping as jm";
        $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password,lm.timezone');
        $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
        $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
        $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
        
        if(!empty($domain_result[0]['timezone'])) {
            $time_zone = $domain_result[0]['timezone'];
            date_default_timezone_set($domain_result[0]['timezone']);
        } else {
            $time_zone = $this->config->item('default_timezone');
            date_default_timezone_set($this->config->item('default_timezone'));
        }
        
        if($this->input->post('old_domain') != $domain || $this->input->post('old_admin_id') != $cdata['lw_admin_id'])
        {

            //Joomla call to retrive contact details 12-12-2014
            $joomla_url = $cdata['domain']."/libraries/api/contact.php?domain=".$cdata['domain'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $joomla_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzip encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
            $response = curl_exec($ch);
            curl_close($ch);
            $response = (json_decode($response, true));   

            if(!empty($response['data']) && count($response['data']) > 0)
            {
                //// For Getting Dynamic Database credential and connect to that database
                /*$table = "joomla_mapping as jm";
                $fields = array('jm.lw_admin_id,jm.domain,lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
                $join_tables = array('login_master as lm' => 'lm.id = jm.lw_admin_id');
                $match = array('domain'=>$cdata['domain'],'lm.status'=>'1');
                $domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');
                */
                if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
                {
                    $db_name = $domain_result[0]['db_name'];
                    foreach($response['data'] as $row)
                    {
                        $con_data['first_name'] = '';$con_data['middle_name'] = '';$con_data['last_name'] = '';
                        if($row['fname'] != '-')
                            $con_data['first_name'] = $row['fname'];
                        if($row['mname'] != '-')
                            $con_data['middle_name'] = $row['mname'];
                        if($row['lname'] != '-')
                            $con_data['last_name'] = $row['lname'];
                        /*$con_data['first_name'] = $row['fname'];
                        $con_data['middle_name'] = $row['mname'];
                        $con_data['last_name'] = $row['lname'];*/
                        $con_data['joomla_user_id'] = $row['user_id'];
                        $con_data['joomla_domain_name'] = $cdata['domain'];
                        //$con_data['joomla_address'] = $row['full_property_address'];
                        $address_from_ip = $row['full_property_address'];
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

                            $con_data['joomla_address'] = $joomla_address;
                        }
                        $con_data['created_type'] = '6';
                        $con_data['created_date']  = date('Y-m-d H:i:s');
                        $con_data['status'] = '1';
                        $con_data['is_valuation_contact'] = !empty($row['val_reg'])?$row['val_reg']:'No';
                        $con_data['price_range_from'] = !empty($row['min_price'])?$row['min_price']:0;
                        $con_data['price_range_to'] = !empty($row['max_price'])?$row['max_price']:0;
                        $con_data['min_area'] = !empty($row['min_area'])?$row['min_area']:0;
                        $con_data['max_area'] = !empty($row['max_area'])?$row['max_area']:0;
                        $con_data['joomla_timeframe'] = $row['timeframe'];
                        $con_data['house_style'] = !empty($row['property_type'])?$row['property_type']:'';
                        $con_data['area_of_interest'] = !empty($row['property_type'])?$row['city_area']:'';
                        $con_data['no_of_bedrooms'] = !empty($row['bedrooms'])?$row['bedrooms']:0;
                        $con_data['no_of_bathrooms'] = !empty($row['bathrooms'])?$row['bathrooms']:0;
                        if($row['have_house_to_sell'] == 'yes')
                            $con_data['joomla_contact_type'] = 'Seller';
                        else
                            $con_data['joomla_contact_type'] = 'Buyer';

                        $ins_data = $this->user_registration_model->insert_record($con_data,$db_name);

                        $edata['contact_id'] = $ins_data;
                        $edata['email_type'] = '0';
                        $edata['email_address'] = $row['email'];
                        $edata['is_default '] = '1';
                        $edata['status '] = '1';
                        $ins_data_email = $this->user_registration_model->insert_record_email($edata,$db_name);

                        $phdata['phone_no'] = $row['tel'];
                        $phdata['contact_id'] = $ins_data;
                        $phdata['phone_type'] = 0;
                        $phdata['is_default'] = '1';
                        $phdata['status'] = '1';

                        $ins_data_phone = $this->contacts_model->insert_phone_trans_record($phdata,$db_name);

                        // Code for updating livewire id
                        $url = $cdata['domain']."/libraries/api/update_lwid.php?lwid=".$ins_data."&userid=".$row['user_id'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzidp encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
                        $lw_update = curl_exec($ch);
                        curl_close($ch);

                        //Joomla call to retrive contact's favorite details
                        $favorite_url = $cdata['domain']."/libraries/api/favourite.php?lwid=".$ins_data."&domain=".$cdata['domain'];;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $favorite_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $favorite_response = curl_exec($ch);
                        curl_close($ch);
                        $favorite_response = (json_decode($favorite_response, true));
                        if(!empty($favorite_response) && count($favorite_response['data']) > 0)
                        {
                            foreach($favorite_response['data'] as $fav_row)
                            {
                                $fav_data['uid'] = $fav_row['uid'];
                                $fav_data['pid'] = $fav_row['pid'];
                                $fav_data['propery_name'] = $fav_row['name'];
                                $fav_data['mlsid'] = !empty($fav_row['mls_id'])?$fav_row['mls_id']:0;
                                
                                $log_date = str_replace('::',' ',$fav_row['date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $fav_data['date'] = $fdate->format('Y-m-d H:i:s');
                                //$fav_data['date'] = $fav_row['date'];
                                $fav_data['lw_admin_id'] = $ins_data;
                                $fav_data['domain'] = $cdata['domain'];
                                $fav_data['created_date'] = $fav_data['date'];
                                //$fav_data['created_date'] = $fav_row['date'];
                                $fav_data['status'] = '1';
                                
                                $fields = array('id');
                                $match = array('lw_admin_id'=>$fav_data['lw_admin_id'],'pid'=>$fav_data['pid'],'mlsid'=>$fav_data['mlsid']);
                                $fresult = $this->favorite_model->select_records($fields,$match,'','=');
                                if(empty($fresult)) {
                                    $fav_res = $this->favorite_model->insert_record($fav_data,$db_name);
                                }
                            }
                        }

                        //Joomla call to retrive contact's Last login details
                        $lastlogin_url = $cdata['domain']."/libraries/api/lastlogin.php?lwid=".$ins_data."&domain=".$cdata['domain'];;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $lastlogin_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $lastlogin_response = curl_exec($ch);
                        curl_close($ch);
                        $lastlogin_response = (json_decode($lastlogin_response, true));
                        if(!empty($lastlogin_response) && count($lastlogin_response['data']) > 0)
                        {
                            foreach($lastlogin_response['data'] as $ll_row)
                            {
                                $ll_data['uid'] = $ll_row['user_id'];
                                $log_date = str_replace('::',' ',$ll_row['log_date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $ll_data['log_date'] 	= $fdate->format('Y-m-d H:i:s');
                                //$ll_data['log_date'] = $ll_row['log_date'];
                                $ll_data['lw_admin_id'] = $ins_data;
                                $ll_data['ip'] = $ll_row['ip'];
                                $ll_data['domain'] = $cdata['domain'];
                                $ll_data['created_date'] = $ll_data['log_date'];
                                $ll_data['status'] = '1';
                                $ll_res = $this->last_login_model->insert_record($ll_data,$db_name);
                            }
                        }

                        //Joomla call to retrive contact's Properties viewed details
                        $pview_url = $cdata['domain']."/libraries/api/pview.php?lwid=".$ins_data."&domain=".$cdata['domain'];;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $pview_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $pview_response = curl_exec($ch);
                        curl_close($ch);
                        $pview_response = (json_decode($pview_response, true));

                        if(!empty($pview_response) && count($pview_response['data']) > 0)
                        {
                            foreach($pview_response['data'] as $pview_row)
                            {
                                $pview_data['uid'] = $pview_row['user_id'];
                                $pview_data['mlsid'] = $pview_row['mls_id'];
                                $pview_data['propery_name'] = $pview_row['name'];
                                
                                $log_date = str_replace('::',' ',$pview_row['log_date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $pview_data['log_date'] = $fdate->format('Y-m-d H:i:s');
                                //$pview_data['log_date'] = $pview_row['log_date'];
                                $pview_data['lw_admin_id'] = $ins_data;
                                $pview_data['domain'] = $cdata['domain'];
                                $pview_data['created_date'] = $pview_data['log_date'];
                                $pview_data['status'] = '1';

                                $fields = array('id,views');
                                $match = array('mlsid'=>$pview_row['mls_id'],'domain'=>$cdata['domain'],'lw_admin_id'=>$ins_data);
                                $property_data = $this->properties_viewed_model->select_records($fields,$match,'','=','','','','','',$db_name);

                                if(!empty($property_data))
                                {
                                    $pview_edata['id'] = $property_data[0]['id'];
                                    $pview_edata['views'] = $property_data[0]['views'] + 1;
                                    $pview_res = $this->properties_viewed_model->update_record($pview_edata,$db_name);
                                }
                                else
                                {
                                    $pview_data['views'] = 1;
                                    $pview_res = $this->properties_viewed_model->insert_record($pview_data,$db_name);
                                }
                            }
                        }

                        //Joomla call to retrive contact's Saved searches details
                        //$savedsearches_url = "http://seattle.livewiresites.com/libraries/api/savesearch.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
                        //$joomla_link = 'http://prod.seattlerealestatetoday.com';
                        //$joomla_link = 'http://topsdemo.in/~seattlenew/seattlerealestatetoday';
                        $savedsearches_url = $joomla_link."/libraries/api/savesearch.php?lwid=".$ins_data."&domain=".$cdata['domain'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $savedsearches_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $savedsearches_response = curl_exec($ch);
                        curl_close($ch);
                        $savedsearches_response = (json_decode($savedsearches_response, true));

                        if(!empty($savedsearches_response) && count($savedsearches_response['data']) > 0)
                        {
                            foreach($savedsearches_response['data'] as $savedsearches_row)
                            {
                                $savedsearches_data['uid'] = $savedsearches_row['uid'];
                                $savedsearches_data['name'] = $savedsearches_row['name'];
                                $savedsearches_data['min_price'] = trim($savedsearches_row['min_price']);
                                $savedsearches_data['max_price'] = trim($savedsearches_row['max_price']);
                                $savedsearches_data['bedroom'] = trim($savedsearches_row['bedroom']);
                                $savedsearches_data['bathroom'] = trim($savedsearches_row['bathroom']);
                                $savedsearches_data['min_area'] = trim($savedsearches_row['min_area']);
                                $savedsearches_data['max_area'] = trim($savedsearches_row['max_area']);
                                $savedsearches_data['min_year_built'] = trim($savedsearches_row['min_year_built']);
                                $savedsearches_data['max_year_built'] = trim($savedsearches_row['max_year_built']);
                                $savedsearches_data['fireplaces_total'] = trim($savedsearches_row['fireplaces_total']);
                                $savedsearches_data['min_lotsize'] = trim($savedsearches_row['min_lotsize']);
                                $savedsearches_data['max_lotsize'] = trim($savedsearches_row['max_lotsize']);
                                $savedsearches_data['garage_spaces'] = trim($savedsearches_row['garage_spaces']);
                                $savedsearches_data['architecture'] = !empty($savedsearches_row['architecture'])?$savedsearches_row['architecture']:'';
                                $savedsearches_data['school_district'] = !empty($savedsearches_row['school_district'])?$savedsearches_row['school_district']:'';
                                $savedsearches_data['waterfront'] = !empty($savedsearches_row['waterfront'])?$savedsearches_row['waterfront']:'';
                                $savedsearches_data['s_view'] = !empty($savedsearches_row['view'])?$savedsearches_row['view']:'';
                                $savedsearches_data['parking_type'] = !empty($savedsearches_row['parking_type'])?$savedsearches_row['parking_type']:'';
                                //$savedsearches_data['search_criteria'] = urldecode(utf8_decode($savedsearches_row['search_criteria']));
                                $savedsearches_data['created_type'] = '2';
                                $savedsearches_data['lw_admin_id'] = $ins_data;
                                $savedsearches_data['domain'] = $cdata['domain'];
                                
                                $log_date = str_replace('::',' ',$savedsearches_row['add_date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $savedsearches_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                                //$savedsearches_data['created_date'] = $savedsearches_row['add_date'];
                                $savedsearches_data['status'] = '1';
                                $savedsearches_res = $this->saved_searches_model->insert_record($savedsearches_data,$db_name);
                            }
                        }
                        
                        //Joomla call to retrive contact's valuation searched details
                        $vs_url = $cdata['domain'].'/libraries/api/valution_data.php?lwid='.$ins_data.'&domain='.$cdata['domain'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $vs_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $vs_response = curl_exec($ch);
                        curl_close($ch);
                        $vs_response = (json_decode($vs_response, true));
                        //pr($vs_response);exit;
                        if(!empty($vs_response) && count($vs_response['data']) > 0)
                        {
                            foreach($vs_response['data'] as $vs_row)
                            {
                                $vs_data['lw_admin_id'] = $ins_data;
                                $vs_data['joomla_uid'] = $vs_row['uid'];
                                $vs_data['search_address'] = $vs_row['address'];
                                $vs_data['city'] = $vs_row['city'];
                                $vs_data['state'] = $vs_row['state'];
                                $vs_data['zip_code'] = $vs_row['zip'];
                                $vs_data['date'] = date('Y-m-d H:i:s',strtotime($vs_row['date']));
                                $log_date = str_replace('::',' ',$vs_data['date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $vs_data['date'] = $fdate->format('Y-m-d H:i:s');
                                $vs_data['domain'] = $cdata['domain'];
                                $vs_data['send_report'] = 'Yes';
                                $vs_data['report_timeline'] = 'Weekly';
                                $vs_data['created_date'] = $vs_data['date'];
                                $vs_data['status'] = '1';
                                $vs_res = $this->property_valuation_searches_model->insert_record($vs_data,$db_name);
                            }
                        }
                        
                        //Joomla call to retrive valuation contact form data
                        $valform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=v";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $valform_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $valform_response = curl_exec($ch);
                        curl_close($ch);
                        $valform_response = (json_decode($valform_response, true));
                        if(!empty($valform_response) && count($valform_response['data']) > 0)
                        {
                            foreach($valform_response['data'] as $valform_row)
                            {
                                $valform_data['uid'] = $valform_row['uid'];
                                $valform_data['pid'] = $valform_row['pid'];
                                $valform_data['mlsid'] = $valform_row['mlsid'];
                                $valform_data['property_name'] = $valform_row['property_name'];
                                $valform_data['domain'] = $cdata['domain'];
                                $valform_data['lw_admin_id'] = $ins_data;
                                $valform_data['name'] = $valform_row['name'];
                                $valform_data['email'] = $valform_row['email'];
                                $valform_data['phone'] = $valform_row['phone'];
                                $valform_data['comments'] = $valform_row['comments'];
                                
                                $log_date = str_replace('::',' ',$valform_row['date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $valform_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                                //$valform_data['created_date'] = $valform_row['date'];
                                $valform_data['status'] = '1';

                                $valform_res = $this->property_valuation_contact_model->insert_record($valform_data,$db_name);
                            }
                        }

                        //Joomla call to retrive contact form data
                        $conform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=c";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $conform_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $conform_response = curl_exec($ch);
                        curl_close($ch);
                        $conform_response = (json_decode($conform_response, true));
                        if(!empty($conform_response) && count($conform_response['data']) > 0)
                        {
                            foreach($conform_response['data'] as $conform_row)
                            {
                                $conform_data['uid'] = $conform_row['uid'];
                                $conform_data['pid'] = $conform_row['pid'];
                                $conform_data['mlsid'] = $conform_row['mlsid'];
                                $conform_data['property_name'] = $conform_row['property_name'];
                                $conform_data['domain'] = $cdata['domain'];
                                $conform_data['lw_admin_id'] = $ins_data;
                                $conform_data['name'] = $conform_row['name'];
                                $conform_data['email'] = $conform_row['email'];
                                $conform_data['phone'] = $conform_row['phone'];
                                $conform_data['comments'] = $conform_row['comments'];
                                $conform_data['preferred_time'] = $conform_row['time'];
                                
                                $log_date = str_replace('::',' ',$conform_row['date']); // This For Replace to joomla site Date
                                $fdate = new DateTime($log_date, new DateTimeZone('UTC')); //This is  Server Time Zone
                                $fdate->setTimezone(new DateTimeZone($time_zone)); //This is  system time zone(client)
                                $conform_data['created_date'] = $fdate->format('Y-m-d H:i:s');
                                //$conform_data['created_date'] = $conform_row['date'];
                                $conform_data['form_type'] = 'property';
                                $conform_data['status'] = '1';

                                $conform_res = $this->property_contact_model->insert_record($conform_data,$db_name);
                            }
                        }
                        
                        //Joomla call to retrive contact details form data 18-03-2015
                        /*$conform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=".$ins_data."&domain=".$cdata['domain']."&action=cd";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $conform_url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
                        $conform_response = curl_exec($ch);
                        curl_close($ch);
                        $conform_response = (json_decode($conform_response, true));
                        if(!empty($conform_response) && count($conform_response['data']) > 0)
                        {
                            foreach($conform_response['data'] as $conform_row)
                            {
                                $conform_data['uid'] = $conform_row['uid'];
                                $conform_data['pid'] = $conform_row['pid'];
                                $conform_data['mlsid'] = $conform_row['mlsid'];
                                $conform_data['property_name'] = $conform_row['property_name'];
                                $conform_data['domain'] = $cdata['domain'];
                                $conform_data['lw_admin_id'] = $ins_data;
                                $conform_data['name'] = $conform_row['name'];
                                $conform_data['email'] = $conform_row['email'];
                                $conform_data['phone'] = $conform_row['phone'];
                                $conform_data['comments'] = $conform_row['comments'];
                                $conform_data['form_type'] = 'propertydetail';
                                $conform_data['created_date'] = $conform_row['date'];
                                $conform_data['status'] = '1';

                                $conform_res = $this->property_contact_model->insert_record($conform_data,$db_name);
                            }
                        }*/
                    }
                }
            }
            //End Joomla call to retrive contact details
            
            //Joomla call to retrive contact form details without logic
            /*$pform_url = $cdata['domain']."/libraries/api/valuation_form_data.php?lwid=&domain=".$cdata['domain']."&action=cn";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $pform_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
            // This is what solved the issue (Accepting gzip encoding)
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
            $presponse = curl_exec($ch);
            curl_close($ch);
            $presponse = (json_decode($presponse, true));   

            if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
            {
                $db_name = $domain_result[0]['db_name'];
                if(!empty($presponse) && count($presponse['data']) > 0)
                {
                    foreach($presponse['data'] as $pform_row)
                    {
                        $pform_data['uid'] = $pform_row['uid'];
                        $pform_data['pid'] = $pform_row['pid'];
                        $pform_data['mlsid'] = $pform_row['mlsid'];
                        $pform_data['property_name'] = $pform_row['property_name'];
                        $pform_data['domain'] = $cdata['domain'];
                        $pform_data['name'] = $pform_row['name'];
                        $pform_data['email'] = $pform_row['email'];
                        $pform_data['phone'] = $pform_row['phone'];
                        $pform_data['comments'] = $pform_row['comments'];
                        $pform_data['preferred_time'] = $pform_row['time'];
                        $pform_data['created_date'] = $pform_row['date'];
                        $pform_data['status'] = '1';
                        $pform_res = $this->property_contact_model->insert_record($pform_data,$db_name);
                    }
                }
            }*/
            
        }
        
        //echo $this->db->last_query();exit;
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $superadmin_id = $this->input->post('id');
		$searchsort_session = $this->session->userdata('map_joomla_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
        //$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
        //redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));

        ?>
        <script type="text/javascript">window.location.href = "<?=base_url().'superadmin/'.$this->viewName.'/'.$pagingid;?>"</script>
        <?php
    }
	
   /*
    @Description: Function for Delete superadmin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which superadmin record want to delete
    @Output: - New superadmin list after record is deleted.
    @Date: 30-08-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete superadmin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete all id of superadmin record want to delete
    @Output: - superadmin post list Empty after record is deleted.
    @Date: 30-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
                $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
                    $delete_all_flag = 1;
                    for($i=0;$i<count($array_data);$i++) 
                    {
                        $cnt++;
                        $this->obj->delete_record($array_data[$i]);
                    }
		}
		
		$searchsort_session = $this->session->userdata('map_joomla_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
	}
	
	 /*
    @Description: Function for Unpublish superadmin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which superadmin record want to Unpublish
    @Output: - New superadmin list after record is Unpublish.
    @Date: 30-08-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		$this->obj->update_record($cdata);
		//echo $this->db->last_query();exit;
		
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		$superadmin_id = $id;
		$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish superadmin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which superadmin record want to publish
    @Output: - New superadmin post list after record is publish.
    @Date: 30-08-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$superadmin_id = $id;
		$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	/*
    @Description: Function for check superadmin already exist
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 30-08-2014
    */

    public function check_domain()
    {
        $id = $this->input->post('id');
        $domain1 = mysql_real_escape_string($this->input->post('domain'));// Validation For DB Error(E.g Domain Name " to start not valid )
		// $domain1 = str_replace('"',$this->input->post('domain'),'');
        //$domain = trim($domain1, '^(((http(?:s)?\:\/\/)|www\.)[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+((\.[a-zA-Z]{2,4})?)(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$');
        $domain = str_replace('www.', '', $domain1);
        $domain = trim($domain,'/');
        /*$pos = strpos('http://',$domain);
        if($pos === false) {
            $domain = 'http://'.$domain;
        }*/
        //echo $domain;exit;
        
        /// New Code 13-12-2014 Sanjay Moghariya
        if(!empty($id))
        {
            //Edit time
            $fields=array('id','domain');
            $match=array('domain'=>$domain);
            $and_match = array('id'=>$id);
            $exist_domain= $this->obj->get_user($fields,$match,'','=','','','','','','',$and_match);
            //echo $this->db->last_query();
            if(!empty($exist_domain))
                echo '1';
            else
                echo '0';
        }
        else
        {
            // Add time
            $fields=array('id','domain');
            $match=array('domain'=>$domain);
            $exist_domain= $this->obj->get_user($fields,$match,'','=');
            //echo $this->db->last_query();
            if(!empty($exist_domain))
                echo '1';
            else
                echo '0';
        }
        
        // Old Code
        /*$fields=array('id','domain');
        
        $match=array('domain'=>$domain);
        $exist_domain= $this->obj->get_user($fields,$match,'','');

        if(count($exist_domain)>0)
        {
            if($exist_domain[0]['id'] == $id)
            {
                echo '0';
            }
            else
            {
                echo '1';	
            }
        }
        else
        {
            echo '0';
        }*/
    }
}