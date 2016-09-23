<?php 
/*
	@Description: Admin Management controller
	@Author: Mohit Trivedi
	@Input: 
	@Output: 
	@Date: 01-09-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_management_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('admin_model');
        $this->load->model('user_management_model');
        $this->load->model('common_function_model');
        $this->load->model('imageupload_model');
        $this->load->model('module_master_model');
        $this->load->model('mls_masters_model');
        $this->load->model('mls_model');

        $this->obj = $this->admin_model;
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';

    }
	
    /*
    @Description: Function for Get All Admin List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Admin list
    @Date: 01-09-2014
    */

    public function index()
    {	
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
			$this->session->unset_userdata('admin_management_sortsearchpage_data');
		}
		$searchsort_session = $this->session->userdata('admin_management_sortsearchpage_data');
		
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
					/*$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];
					*/
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
		$config['base_url'] = site_url($this->user_type.'/'."admin_management/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
		
		if(!empty($searchtext))
		{
			$match=array('admin_name'=>$searchtext,'email_id'=>$searchtext);
			$where=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			$config['total_rows'] = $this->obj->get_user('',$match,'','like','','','','','',$where,'','1');
		}
		else
		{
			$match=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$config['total_rows']= $this->obj->get_user('',$match,'','','','','','','','','','1');
		}
		//Check admin right
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
					);	
		$fields = array('l.id,l.admin_name,l.email_id, ur.id AS urid');
		$group_by='l.id';
		$where = "l.user_type = 2 and ur.id IS NULL";
		$data['admin_list']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		
		
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
		$this->session->set_userdata('admin_management_sortsearchpage_data', $sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;

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
    @Description: Function Add New Admin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Admin details
    @Date: 01-09-2014
    */
   
    public function add_record()
    {
        $data['timezone_list'] = $this->timezone_list();
        $this->load->model('mls_master_model');
        $match = array('status'=>1);
        $data['mlslistdata'] = $this->mls_master_model->select_records('',$match,'','=');
        $data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

   
   /*
    @Description: Function Copy Admin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for copy Admin details
    @Date: 01-09-2014
    */
   
    public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['admin_name'] = $result[0]['admin_name'].'-copy';
		$cdata['email_id'] = $result[0]['email_id'];
		$cdata['password'] = $result[0]['password'];
		$cdata['db_name']=$result[0]['db_name'];
		$cdata['host_name']=$result[0]['host_name'];
		$cdata['db_user_name']=$result[0]['db_user_name'];
		$cdata['db_user_password']=$result[0]['db_user_password'];
		$cdata['user_type'] = '2';
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$lastId=$this->obj->insert_user($cdata);
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
	
	}
  
    /*
    @Description: Function for Insert New Admin data
    @Author: Mohit Trivedi
    @Input: - Details of new Admin which is inserted into DB
    @Output: - List of Admin with new inserted records
    @Date: 01-09-2014
    */
   
    public function insert_data()
    {
	 	/*$sql = "select max(id)+1 as max_id from database_names";
		$result = mysql_query($sql);
		
		while($row = mysql_fetch_assoc($result))
		{
			$data[] = $row;
		}
		
		// END
		
		$databasename = "livewire_".md5(uniqid().$data[0]['max_id']); // Generate unique database name
		
		// Create new database start
		
		$sql1="CREATE DATABASE $databasename";
		$exec = mysql_query($sql1);*/
		
		// END
		
		?>
        <style>
			body{ position:relative;}
		</style>
        <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src='<?=base_url('images/loading.gif')?>' /><br />Please wait... It will take some time to setup admin account...</div>
		
		<?php // Create a 
		//exit;
                
		$newdatabasename = $this->obj->getnewdbname()+1;
		
		$databasename = $this->config->item('parent_db_prefix').md5(uniqid().$newdatabasename);			//For local
		//$databasename = "topsin_livewire_crm_".md5(uniqid().$newdatabasename);		//For topsdemo.in
		
		$is_db_created = $this->obj->createnewdb($databasename);
		
		// Insert entry in master database of created db START
		
		/*$db_name = $databasename;
		$host_name = "localhost";
		$user_name = "root";
		$password = "";
		$created_date = date('Y-m-d h:i:s');
		$status = 1;
		
		$ins_query = "insert into database_names(db_name,host_name,user_name,password,created_date,status) values('".$db_name."','".$host_name."','".$user_name."','".$password."','".$created_date."','".$status."')";
		$result = mysql_query($ins_query);*/
		
		$databaseusername = $this->config->item('parent_db_user_prefix').$newdatabasename;
		
		$is_dbuser_created = $this->obj->createnewdbuser($databaseusername,$databasename);
		
		//pr($is_dbuser_created);exit;
	 
		$cdata['admin_name'] 		= $this->input->post('admin_name');
		$cdata['email_id']			= $this->input->post('txt_email_id');
		$cdata['address']			= $this->input->post('address');
		$cdata['phone']				= $this->input->post('phone');
		$cdata['number_of_users_allowed']	= $this->input->post('number_of_users_allowed');
		$cdata['timezone']			= $this->input->post('timezone');
		$cdata['user_license_no']	= $this->input->post('user_license_no');
		$cdata['mls_user_id']	= $this->input->post('mls_agent_id');
                $cdata['mls_firm_id']	= $this->input->post('mls_firm_id');
		$cdata['twilio_account_sid']= $this->input->post('twilio_account_sid');
		$cdata['twilio_auth_token']	= $this->input->post('twilio_auth_token');
		$cdata['twilio_number']		= $this->input->post('twilio_number');
		$cdata['fb_api_key']		= $this->input->post('fb_key_id');
		$cdata['fb_secret_key']		= $this->input->post('fb_secret_key');
                
		//$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('admin_pic');//new add
		$bgImgPath = $this->config->item('admin_big_img_path');
		$smallImgPath = $this->config->item('admin_small_img_path');
		if(!empty($_FILES['admin_pic']['name']))
		{  
			$uploadFile = 'admin_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['admin_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
		$bgImgPath = $this->config->item('broker_big_img_path');
		$smallImgPath = $this->config->item('broker_small_img_path');
		if(!empty($_FILES['brokerage_pic']['name']))
		{  
			$uploadFile = 'brokerage_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
			$cdata['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$cdata['password'] 			= $this->common_function_model->encrypt_script($this->input->post('password'));
		
		$cdata['db_name']			= $databasename;
		$cdata['host_name']			= $this->config->item('root_host_name');
		$cdata['db_user_name']		= $databaseusername;
		$cdata['db_user_password']	= $databaseusername;
		
		$cdata['user_type'] 		= '2';
		$cdata['created_by'] 		= $this->superadmin_session['id'];
		$cdata['created_date'] 		= date('Y-m-d H:i:s');		
		$cdata['status'] 			= '1';
		
		//pr($cdata);exit;
		
		$lastId=$this->obj->insert_user($cdata);
                
                /// Insert Assign MLS Property
                $this->load->model('mls_master_model');
                $mlsdata = $this->input->post('mls_id');
                if(!empty($mlsdata) && !empty($lastId))
                {
                    $insert_mls['admin_id'] = $lastId;
                    $insert_mls['created_date'] = date('y-m-d h:i:s');
                    $insert_mls['created_by'] = $this->superadmin_session['id'];
                    foreach($mlsdata as $row)
                    {
                        $insert_mls['mls_id'] = $row;
                        $this->mls_master_model->insert_assign_mls($insert_mls);
                    }
                }
                
                
		//Insert default module right
		if(!empty($lastId))
		{
			//$match = array('default_right' => 1); // Comment by Nishit
			$match = array(); // Comment by Niral
			$fields = array('id','default_right');
			$module_data = $this->module_master_model->select_records($fields,$match,'','=');	
			$module_id = array();
			foreach($module_data as $row1)
			{
				$data['user_id']= $lastId;
				if(empty($row1['default_right']))
				{$data['assign_right'] = 1;}
				else
				{$data['assign_right'] = 0;}
				$data['module_id'] = $row1['id'];
				$data['created_date'] = date('y-m-d h:i:s');
				$data['modified_date'] = date('y-m-d h:i:s');
				$data['status'] = '1';
				//pr($data);
				$this->module_master_model->insert_record1($data);	
			}
		}
		
		//echo $lastId;
		
		if(is_db_created)
		{
			$parent_db = $this->config->item('parent_db_name');		//For local
			//$parent_db = "topsin_livewire_crm_v2_new";			//For topsdemo.in
			$child_db = $databasename;
			
			$this->obj->copyonedbtoother($parent_db,$child_db,$lastId,$databaseusername);
		}
		
		/*
		// First DB Info
		$conn1 = mysql_connect("localhost","root","") or die(mysql_error()); // Connection info
		$select_db1 = mysql_select_db($parent_db,$conn1); // Select db
		
		// Second DB Info
		$conn2 = mysql_connect("localhost","root","") or die(mysql_error()); // Connection info
		$select_db2 = mysql_select_db($child_db,$conn2); // Select db
		
		// Get list of tables from database
		
		$sql = "SHOW TABLES FROM $parent_db";
		$result = mysql_query($sql,$conn1);
		
		while($row = mysql_fetch_row($result))
		{
			$parent_db_tables[] = $row[0];
		}
		
		//echo "<pre>"; print_r($parent_db_tables); exit;
		
		// Copy one db tables to another db
		
		for($i=0; $i<count($parent_db_tables); $i++){
			$create_table = "CREATE TABLE ".$child_db.".".$parent_db_tables[$i]." LIKE ".$parent_db.".".$parent_db_tables[$i]; 
			$result1 = mysql_query($create_table,$conn2);
			
			$insert_data = "INSERT INTO ".$child_db.".".$parent_db_tables[$i]." SELECT * FROM ".$parent_db.".".$parent_db_tables[$i];
			$result2 = mysql_query($insert_data,$conn2);
		}
	
		mysql_free_result($result);
		*/
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		?>
		<script type="text/javascript">window.location.href = '<?=base_url().'superadmin/'.$this->viewName;?>';</script>
		<?php
     }
 
    /*
    @Description: Get Details of Edit Admin Profile
    @Author: Mohit Trivedi
    @Input: - Id of Admin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 01-09-2014
    */
 
    public function edit_record()
    {
        $this->load->model('mls_master_model');
     	$id = $this->uri->segment(4);
        $match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
        $cdata['editRecord'] = $result;
        
        $match = array('status'=>1);
        $cdata['mlslistdata'] = $this->mls_master_model->select_records('',$match,'','=');
        
        $match = array('admin_id'=>$id);
        $assign_mls = $this->mls_master_model->select_assign_mls($match);
        
        if(!empty($assign_mls))
        {
            foreach($assign_mls as $row)
            {
                $assign[] = $row['mls_id'];
            }
            
        }
        $cdata['assign_mls'] = !empty($assign)?$assign:'';
        
        $this->load->model('child_admin_model');
        $table = 'child_admin_website';
        $fields = array('mls_id');
        $where = 'lw_admin_id = '.$id;
        $child_data = $this->child_admin_model->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$where);
        foreach ($child_data as $key => $value) {
        	$child_assigned[] = $value['mls_id'];
        }
        $cdata['child_assigned'] = array_unique($child_assigned);
        $cdata['timezone_list'] = $this->timezone_list();
        $cdata['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$cdata);
    }
	/*
    @Description: Function for for edit right
    @Author: NIral Patel
    @Input: - Id of Admin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 27-01-2015
    */
 
    public function edit_right()
    {
		$table='module_master as m1';
		$join_tables = array(
						'module_master as m2' 	=> 'm1.id= m2.module_id AND m2.module_parent = -1',
					);
		$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right,GROUP_CONCAT(case when m2.module_right="" then null else m2.id end) module_right_id');
		
		$group_by='m2.module_id';
		$where = "m1.module_parent = 0 and m1.default_right = 0";
		$data['datalist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.position','asc',$group_by,$where);
		//pr($data['datalist']);exit;
		//Get list of admin
		$match=array('user_type'=>'2');
		$data['admin_list'] = $this->obj->get_user('',$match,'','=');
		
     	$id = $this->uri->segment(4);
		//get user informatiion
		$match=array('user_type'=>'2','id'=>$id);
		$fields=array('admin_name','email_id');
		$data['admin_name'] = $this->obj->get_user($fields,$match,'','=');	
		//pr($data['admin_name']);
		
		$match = array('user_id' => $id);
		$fields = array('module_id');
		$result = $this->module_master_model->select_records1($fields,$match,'','=');
		
		$old_module_id = array();
		foreach($result as $row)
		{
			$old_module_id[] = $row['module_id'];	
		}
		$data['assign_rights'] = $old_module_id;
		//pr($data['assign_rights']);exit;
		$data['editRecord'] = array('user_id'=>$id);
		//pr($data['editRecord']);exit;
		$data['main_content'] = "superadmin/".$this->viewName."/admin_rights";       
		$this->load->view("superadmin/include/template",$data);
		
    }

    /*
    @Description: Function for Update Admin Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Admin
    @Output: - List with updated Admin details
    @Date: 01-09-2014
    */
   
    public function update_data()
    {
	    $cdata['id'] = $this->input->post('id');
		$cdata['admin_name'] = $this->input->post('admin_name');
		//$cdata['email_id'] = $this->input->post('email_id');
		$cdata['address']			= $this->input->post('address');
		$cdata['phone']			= $this->input->post('phone');
		$cdata['user_license_no'] = $this->input->post('user_license_no');
		$cdata['mls_user_id'] = $this->input->post('mls_agent_id');
                $cdata['mls_firm_id'] = $this->input->post('mls_firm_id');
		$cdata['number_of_users_allowed'] = $this->input->post('number_of_users_allowed');
		$cdata['timezone'] = $this->input->post('timezone');
		$cdata['fb_api_key']		= $this->input->post('fb_key_id');
		$cdata['fb_secret_key']		= $this->input->post('fb_secret_key');
		
		$match = array('id'=>$cdata['id']);
        $result = $this->obj->get_user('',$match,'','=');
		if(!empty($result) && empty($result[0]['twilio_account_sid']))
			$cdata['twilio_account_sid']	= $this->input->post('twilio_account_sid');
		if(!empty($result) && empty($result[0]['twilio_auth_token']))
			$cdata['twilio_auth_token']	= $this->input->post('twilio_auth_token');
		if(!empty($result) && empty($result[0]['twilio_number']))
			$cdata['twilio_number']	= $this->input->post('twilio_number');
		//$image = $this->input->post('hiddenFile');
		$oldcontactimg = $this->input->post('admin_pic');//new add
		$bgImgPath = $this->config->item('admin_big_img_path');
		$smallImgPath = $this->config->item('admin_small_img_path');
		if(!empty($_FILES['admin_pic']['name']))
		{  
			$uploadFile = 'admin_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
			$cdata['admin_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$oldbrokerageimg = $this->input->post('brokerage_pic');//new add
		$bgImgPath = $this->config->item('broker_big_img_path');
		$smallImgPath = $this->config->item('broker_small_img_path');
		if(!empty($_FILES['brokerage_pic']['name']))
		{  
			$uploadFile = 'brokerage_pic';
			$thumb = "thumb";
			$hiddenImage = !empty($oldbrokerageimg)?$oldbrokerageimg:'';
			$cdata['brokerage_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
		}
		
		$cdata1['password']=$this->input->post('password');
		if(!empty($cdata1['password']))
		{
			$cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));
		}
		
		//$cdata['db_name']=$this->input->post('db_name');
		//$cdata['host_name']=$this->input->post('host_name');
		//$cdata['db_user_name']=$this->input->post('db_user_name');
		//$cdata['db_user_password']=$this->input->post('db_user_password');
		
		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		//$cdata['status'] = '1';
		$this->obj->update_user($cdata);
		
		///////////////////////////////////
                
                // Update Assign MLS Prioperty List
                $this->load->model('mls_master_model');
                
                $mlsdata = $this->input->post('mls_id');
                
                if(empty($mlsdata))
                    $mlsdata = array();
                $match = array('admin_id'=>$cdata['id']);
                $assign_mls = $this->mls_master_model->select_assign_mls($match);
                if(!empty($assign_mls))
                {
                    foreach($assign_mls as $row)
                    {
                        $oldassign_mls[] = $row['mls_id'];
                    }

                }
                if(!empty($oldassign_mls))
                {
                    $delete_oldmls = array_diff($oldassign_mls,$mlsdata);
                    $mlsdata = array_diff($mlsdata,$oldassign_mls);
                }
                if(!empty($delete_oldmls) && !empty($cdata['id']))
                {
                    $delete_mls['admin_id'] = $cdata['id'];
                    foreach($delete_oldmls as $row)
                    {
                        $delete_mls['mls_id'] = $row;
                        $this->mls_master_model->delete_assign_mls($delete_mls);
                    }
                }
                
                if(!empty($mlsdata) && !empty($cdata['id']))
                {
                    $insert_mls['admin_id'] = $cdata['id'];
                    $insert_mls['created_date'] = date('y-m-d h:i:s');
                    $insert_mls['created_by'] = $this->superadmin_session['id'];
                    foreach($mlsdata as $row)
                    {
                        $insert_mls['mls_id'] = $row;
                        $this->mls_master_model->insert_assign_mls($insert_mls);
                    }
                }
                
                $match = array('id'=>$this->input->post('id'));
		$parent_login = $this->admin_model->get_user('',$match,'','=');
		
		//pr($parent_login);exit;
		
		if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
		{
			$update_parent_data['admin_name'] = $parent_login[0]['admin_name'];
			$update_parent_data['email_id'] = $parent_login[0]['email_id'];
			$update_parent_data['db_name'] = $parent_login[0]['db_name'];
			$update_parent_data['password']= $parent_login[0]['password'];
			$update_parent_data['address']= $parent_login[0]['address'];
			$update_parent_data['phone']= $parent_login[0]['phone'];
			$update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
			$update_parent_data['number_of_users_allowed']= $parent_login[0]['number_of_users_allowed'];
			$update_parent_data['timezone']= $parent_login[0]['timezone'];
			$update_parent_data['twilio_account_sid']= $parent_login[0]['twilio_account_sid'];
			$update_parent_data['twilio_auth_token']= $parent_login[0]['twilio_auth_token'];
			$update_parent_data['twilio_number']= $parent_login[0]['twilio_number'];
			$update_parent_data['fb_api_key'] = $parent_login[0]['fb_api_key'];
			$update_parent_data['fb_secret_key'] = $parent_login[0]['fb_secret_key'];
			$update_parent_data['brokerage_pic']= $parent_login[0]['brokerage_pic'];
			$update_parent_data['user_license_no']= $parent_login[0]['user_license_no'];
			$update_parent_data['mls_user_id']= $parent_login[0]['mls_user_id'];
                        $update_parent_data['mls_firm_id']= $parent_login[0]['mls_firm_id'];
			$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
			
			$childdb = $parent_login[0]['db_name'];
			
			$lastId = $this->obj->update_child_user_record($childdb,$update_parent_data);
			//echo $this->db->last_query();exit;
		}
		
		///////////////////////////////////
		//Check admin right
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
					);	
		$fields = array('l.id,l.admin_name,l.email_id, ur.id AS urid');
		$group_by='l.id';
		$where = "l.user_type = 2 and ur.id IS NULL";
		$data['admin_list']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $this->input->post('id');
		$searchsort_session = $this->session->userdata('admin_management_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		//$pagingid = $this->obj->getadminpagingid($admin_id);
		//pr($pagingid);exit;
		redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
		
    }
	/*
    @Description: Function Add admin right
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Module details
    @Date: 27-01-2015
    */
   
    public function admin_rights()
    {
		
		$table='module_master as m1';
		$join_tables = array(
						'module_master as m2' 	=> 'm1.id= m2.module_id AND m2.module_parent = -1',
					);
		$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right,GROUP_CONCAT(case when m2.module_right="" then null else m2.id end) module_right_id');
		
		$group_by='m2.module_id';
		$where = "m1.module_parent = 0 and m1.default_right = 0";
		$data['datalist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.id','asc',$group_by,$where);
		
		//Get list of admin
		$table='login_master as l';
		$join_tables = array(
						//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
						'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
					);	
		$fields = array('l.id,l.admin_name,l.email_id, ur.id AS urid');
		$group_by='l.id';
		$where = "l.user_type = 2 and ur.id IS NULL";
		$data['admin_list']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','l.id','asc','',$where);
		//echo $this->db->last_query();
		//pr($data['admin_list']);exit;
		/*$match=array('user_type'=>'2');
		$data['admin_list'] = $this->obj->get_user('',$match,'','=','','','','','');	*/
		
		$data['main_content'] = "superadmin/".$this->viewName."/admin_rights";
        $this->load->view('superadmin/include/template', $data);
    }
	/*
    @Description: Function for Insert New Admin data
    @Author: Mohit Trivedi
    @Input: - Details of new Admin which is inserted into DB
    @Output: - List of Admin with new inserted records
    @Date: 01-09-2014
    */
   
    public function insert_rights()
    {
		$id= $this->input->post('id');
		if(!empty($id))
		{
			$user_id=array($id);	
		}
		else
		{
			$user_id= $this->input->post('user_id');
		}
		//pr($user_id);exit;
		for($i=0; $i<count($user_id); $i++)
		{
			//$user_id = $this->input->post('id');
			$match = array('user_id' => $user_id[$i],'assign_right'=>1);
			$fields = array('module_id');
			$eresult = $this->module_master_model->select_records1($fields,$match,'','=');
			
			$module_id = $this->input->post('chk_right');
			//pr($module_id);
			if(!empty($module_id))
			{
				if(in_array('228',$module_id))
				{
					$bomb_lib=array('223','224','225','226','227');
					$module_id=array_merge($bomb_lib,$module_id);					
				}
			}
			//pr($module_id);exit;
			if(empty($module_id))
			{
				$module_id=array();	
			}
			if(empty($eresult))
			{
				for($j=0; $j<count($module_id); $j++)
				{
					if($module_id[$j] != 0 || $module_id[$j] != '')
					{
						$cdata['user_id']= $user_id[$i];
						$cdata['module_id'] = $module_id[$j];
						$cdata['assign_right'] = 1;
						$cdata['created_date'] = date('y-m-d h:i:s');
						$cdata['modified_date'] = date('y-m-d h:i:s');
						$cdata['status'] = '1';
						//pr($cdata);
						$this->module_master_model->insert_record1($cdata);
					}
				   
				}
			}
			else
			{
				$old_module_id = array();
				foreach($eresult as $row)
				{
					$old_module_id[] = $row['module_id'];	
				}
				//pr($old_module_id);
				//pr($module_id);
				$deletecontactdata = array_diff($old_module_id,$module_id);

				//pr($deletecontactdata);
				if(in_array('228',$deletecontactdata))
				{
					$bomb_lib=array('223','224','225','226','227');
					$deletecontactdata=array_merge($bomb_lib,$deletecontactdata);					
				}
				if(!empty($deletecontactdata))
				{
					$field = array('id','db_name','email_id');
					$match = array('id'=>$user_id[$i]);
					$admin_data = $this->admin_model->get_user($field, $match,'','=');
					if(!empty($admin_data))
					{
						$db_name=$admin_data[0]['db_name'];	
						$table=$db_name.'.login_master as l';
						$join_tables = array(
										//'user_right_transaction as ur' 	=> 'l.id = ur.user_id',
										'(SELECT cptin.* FROM user_right_transaction cptin WHERE cptin.assign_right = "1" GROUP BY cptin.user_id) AS ur'=>'ur.user_id = l.id',
									);	
						$fields = array('l.id,l.admin_name,l.email_id');
						//$group_by='l.id';
						$where = "l.user_type = 3 or l.user_type = 4";
						$admin_user_list=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','l.id','asc','',$where);
						if(!empty($admin_user_list))
						{
							
							foreach($admin_user_list as $row)	
							{
								$this->module_master_model->delete_module_subchild_array($row['id'],$deletecontactdata,$db_name);
								//echo $this->db->last_query();
							}
						}
					}
					//pr($deletecontactdata);exit;
					$this->module_master_model->delete_module_array($user_id[$i],$deletecontactdata);
					//echo $this->db->last_query();
					////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
				}
				//pr($module_id);
				//pr($old_module_id);
				$final_data = array_diff($module_id,$old_module_id);
				//pr($final_data);exit;
				//Insert remaining data
				foreach($final_data as $row)
				{
					if($row != 0 || $row != '')
					{
						$cdata['user_id']= $user_id[$i];
						$cdata['module_id'] = $row;
						$cdata['assign_right'] = 1;
						$cdata['created_date'] = date('y-m-d h:i:s');
						$cdata['modified_date'] = date('y-m-d h:i:s');
						$cdata['status'] = '1';
						$this->module_master_model->insert_record1($cdata);
					}
				   
				}
			}
		}
		$msg = $this->lang->line('common_add_success_msg');
		$newdata = array('msg'  => $msg);
		$this->session->set_userdata('message_session', $newdata);    
		redirect('superadmin/'.$this->viewName);
		//redirect('superadmin/'.$this->viewName.'/edit_right/'.$user_id[0]);
	}
   /*
    @Description: Function for Delete Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to delete
    @Output: - New Admin list after record is deleted.
    @Date: 01-09-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_user($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
		$admin_id = $id;
		$pagingid = $this->obj->getadminpagingid($admin_id);
        redirect('superadmin/'.$this->viewName.'/'.$pagingid);
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
            $this->load->model('map_joomla_model');
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
			$this->obj->delete_user($id);
                        $this->map_joomla_model->delete_record('',$id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_user($array_data[$i]);
                                $this->map_joomla_model->delete_record('',$array_data[$i]);
			}
		}
		$searchsort_session = $this->session->userdata('admin_management_sortsearchpage_data');
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
    @Description: Function for Unpublish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to Unpublish
    @Output: - New Admin list after record is Unpublish.
    @Date: 01-09-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $id;
		$pagingid = $this->obj->getadminpagingid($admin_id);
		echo $pagingid;
//		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to publish
    @Output: - New Admin post list after record is publish.
    @Date: 01-09-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '1';
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $id;
		$pagingid = $this->obj->getadminpagingid($admin_id);
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	/*
    @Description: Function for check Admin already exist
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 01-09-2014
    */

	public function check_user()
	{
		
		$email=mysql_real_escape_string($this->input->post('email'));
		$match=array('email_id'=>$email);
		$exist_email= $this->obj->get_user('',$match,'','=');
		if(!empty($exist_email))
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}
	/*
    @Description: This Function check twilio number already Existing.
    @Author: Niral Patel
    @Input: Twilio number check
    @Output: Yes or No
    @Date: 1-04-2015
	
	*/
	public function check_twilio_number()
	{
		
		$twilio_number=$this->input->post('twilio_number');
		$match=array('twilio_number'=>$twilio_number);
		$parent_db=$this->config->item('parent_db_name');
		$exist_email= $this->admin_model->get_user('',$match,'','=','','','','','','',$parent_db);
		//echo $this->db->last_query();exit;
		if(!empty($exist_email))
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}
	
	public function delete_image()
	{
		$id = $this->input->post('id');
		$name=$this->input->post('name');
		$fields = array("id,$name");
        $match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$bgImgPath = $this->config->item('admin_big_img_path');
		$smallImgPath = $this->config->item('admin_small_img_path');
		$image=$result[0][$name];
		
		$bgImgPathUpload = $this->config->item('upload_image_file_path').'admin/big/';
		$smallImgPathUpload = $this->config->item('upload_image_file_path').'admin/small/';
		if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
		{ 
		
			@unlink($bgImgPath.$image);
			@unlink($smallImgPath.$image);
		}
		$cdata['id'] = $id;
		$cdata[$name] = '';
		$this->obj->update_user($cdata);
		
		$match = array('id'=>$id);
		$parent_login = $this->admin_model->get_user('',$match,'','=');
		
		//pr($parent_login);exit;
		
		if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
		{
			$update_parent_data['email_id'] = $parent_login[0]['email_id'];
			$update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
			$update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
			$childdb = $parent_login[0]['db_name'];
			$lastId = $this->obj->update_child_user_record($childdb,$update_parent_data);
		}
		
		echo 'done';
	}
	
	public function timezone_list()
	{
		$timezone_list 	= array();
		$timestamp 		= time();
	
		foreach(timezone_identifiers_list() as $key => $zone)
		{
			date_default_timezone_set($zone);
			$timezone_list[$zone]['zone'] = $zone;
			$timezone_list[$zone]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
		}
	
		return $timezone_list;
	}
        
    /*
        @Description: Function for Insert mls criteria
        @Author     : Sanjay Moghariya
        @Input      : mls criera data
        @Output     : insert record
        @Date       : 03-03-2015
    */
    public function insert_mls_settings()
    {
        $id= $this->input->post('id');
        if(!empty($id))
        {
            $user_id=array($id);	
        }
        else
        {
            $user_id= $this->input->post('user_id');
        }
        
        for($i=0; $i<count($user_id); $i++)
        {
            ///// Updatoe user mls property type trans //////
            $property_type_name = array();$property_type_id = array();
            $pname = '';
            $property_type = $this->input->post('chk_property_type');
            if(!empty($property_type))
            {
                foreach($property_type as $row)
                {
                    $typeid = explode('#', $row);
                    $property_type_id[] = $typeid[0];
                    $property_type_name[] = $typeid[1];
                    $pname .= "'".$typeid[1]."',";
                }
            }
            $pname = trim($pname,',');
            
            $table = 'user_mls_property_type_trans';
            $fields = array('id,user_id,mls_property_type_id');
            $match = array('user_id'=>$user_id[$i]);
            $ptype_trans_data = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);
            
            $old_pt_id = array();
            if(!empty($ptype_trans_data) && count($ptype_trans_data) > 0){
                foreach($ptype_trans_data as $row){
                    $old_pt_id[] = $row['mls_property_type_id'];
                }
            }

            $deleteptypearr = array_diff($old_pt_id,$property_type_id);
            $insertptarr = array_diff($property_type_id,$old_pt_id);

            if(isset($property_type_id) && empty($property_type_id))
                $deleteptypearr = $old_pt_id;

            if(!empty($deleteptypearr))
            {
                $this->mls_masters_model->delete_mls_property_type_trans($user_id[$i],$deleteptypearr);
            }

            if(!empty($insertptarr) && count($insertptarr) > 0)
            {
                $ptdata['user_id'] = $user_id[$i];
                foreach($insertptarr as $ptype_id)
                {
                    $ptdata['mls_property_type_id'] = $ptype_id;
                    $this->mls_masters_model->insert_mls_property_type_trans($ptdata);
                }
            }
            ///// End update user mls property type trans. //////
            
            ///// Update mls status trans //////
            $status_name = array();$status_id = array();$sname = '';
            $status = $this->input->post('chk_status');
            
            if(!empty($status))
            {
                foreach($status as $row)
                {
                    $statusid = explode('#', $row);
                    $status_id[] = $statusid[0];
                    $status_name[] = $statusid[1];
                    $sname .= "'".$statusid[1]."',";
                }
            }
            $sname = trim($sname,',');
            
            $table = 'user_mls_status_trans';
            $fields = array('id,user_id,mls_status_master_id');
            $match = array('user_id'=>$user_id[$i]);
            $status_trans_data = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);
        
            $old_status_id = array();
            if(!empty($status_trans_data) && count($status_trans_data) > 0){
                foreach($status_trans_data as $row){
                    $old_status_id[] = $row['mls_status_master_id'];
                }
            }

            $deletestatusarr = array_diff($old_status_id,$status_id);
            $insertstatusarr = array_diff($status_id,$old_status_id);

            if(isset($status_id) && empty($status_id))
                $deletestatusarr = $old_status_id;

            if(!empty($deletestatusarr))
            {
                $this->mls_masters_model->delete_mls_status_trans($user_id[$i],$deletestatusarr);
            }

            if(!empty($insertstatusarr) && count($insertstatusarr) > 0)
            {
                $statusdata['user_id'] = $user_id[$i];
                foreach($insertstatusarr as $sstatus_id)
                {
                    $statusdata['mls_status_master_id'] = $sstatus_id;
                    $this->mls_masters_model->insert_mls_status_trans($statusdata);
                }
            }
            ///// End update mls status trans. //////
            
            ///// Update mls area trans //////
            $aname = '';
            $area = $this->input->post('chk_area');
            if(!empty($aname))
                $aname = implode($area,',');
            if(empty($area))
                $area = array();
            
            $table = 'user_mls_area_trans';
            $fields = array('id,user_id,mls_area_master_id');
            $match = array('user_id'=>$user_id[$i]);
            $area_trans_data = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);
        
            $old_area_id = array();
            if(!empty($area_trans_data) && count($area_trans_data) > 0){
                foreach($area_trans_data as $row){
                    $old_area_id[] = $row['mls_area_master_id'];
                }
            }

            $deleteareaarr = array_diff($old_area_id,$area);
            $insertareaarr = array_diff($area,$old_area_id);

            if(isset($area) && empty($area))
                $deleteareaarr = $old_area_id;

            if(!empty($deleteareaarr))
            {
                $this->mls_masters_model->delete_mls_area_trans($user_id[$i],$deleteareaarr);
            }

            if(!empty($insertareaarr) && count($insertareaarr) > 0)
            {
                $areadata['user_id'] = $user_id[$i];
                foreach($insertareaarr as $area_id)
                {
                    $areadata['mls_area_master_id'] = $area_id;
                    $this->mls_masters_model->insert_mls_area_trans($areadata);
                }
            }
            ///// End update mls area trans. //////
            
            ///// Fetch property listing data based on settings and insert into Admin DB /////
            $table = 'login_master';
            $fields = array('id,db_name');
            $match = array('id'=>$user_id[$i]);
            $db_name = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);
            if(!empty($db_name))
            {
                if(!empty($pname) || !empty($aname) || !empty($sname))
                {
                    $p_data = array();
                    $table = 'mls_property_list_master';
                    //$fields = array('LN, PTYP, LAG, ST, LP, SP, OLP, HSN, DRP, STR, SSUF, DRS, UNT, CIT, STA, ZIP, PL4, BR, BTH, ASF, LSF, UD, AR, DSRNUM, LDR, LD, CLO, YBT, LO, TAX, MAP, GRDX, GRDY, SAG, SO, NIA, MR, LONG, LAT, PDR, CLA, SHOADR, DD, AVDT, INDT, COU, CDOM, CTDT, SCA, SCO, VIRT, SDT, SD, FIN, MAPBOOK, DSR, QBT, HSNA, COLO, PIC, ADU, ARC, BDC, BDL, BDM, BDU, BLD, BLK, BRM, BUS, DNO, DRM, EFR, EL, ENT, F17, FAM, FBG, FBL, FBM, FBT, FBU, FP, FPL, FPM, FPU, GAR, HBG, HBL, HBM, HBT, HBU, HOD, JH, KES, KIT, LRM, LSD, LSZ, LT, MBD, MHM, MHN, MHS, MOR, NC, POC, POL, PRJ, PTO, TQBT, RRM, CMFE, SAP, SFF, SFS, SFU, SH, SML, SNR, STY, SWC, TBG, TBL, TBM, TBU, TX, TXY, UTR, WAC, WFG, WHT, APS, BDI, BSM, ENS, EXT, FEA, FLS, FND, GR, HTC, LDE, LTV, POS, RF, SIT, SWR, TRM, VEW, WAS, WFT, BUSR, ECRT, ZJD, ZNC, ProhibitBLOG, AllowAVM, PARQ, BREO, BuiltGreenRating, EPSEnergy, ROFR, HERSIndex, LEEDRating, NewConstruction, NWESHRating, ConstructionMethods, EMP, EQU, EQV, FRN, GRS, GW, HRS, INV, LNM, LSI, NA, NP, PKC, PKU, RES, RNT, SIN, TEXP, TOB, YRE, YRS, LES, LIC, LOC, MTB, RP, LSZS, AFH, ASC, COO, MGR, NAS, NOC, NOS, NOU, OOC, PKS, REM, SAA, SPA, STG, STL, TOF, UFN, WDW, APH, CMN, CTD, HOI, PKG, UNF, STRS, FUR, MLT, STO, AFR, APP, MIF, TMC, TYP, UTL, ELE, ESM, GAS, LVL, QTR, RD, SDA, SEC, SEP, SFA, SLP, SST, SUR, TER, WRJ, ZNR, ATF, DOC, FTR, GZC, IMP, RDI, RS2, TPO, WTR, AUCTION, LotSizeSource, EffectiveYearBuilt, EffectiveYearBuiltSource, status');
                    $fields = array();
                    $match = '';
                    if(!empty($pname))
                        $match .= "PTYP IN(".$pname.")";
                    if(!empty($aname))
                        $match .= " OR AR IN(".$aname.")";
                    if(!empty($sname))
                        $match .= " OR ST IN(".$sname.")";
                    //$where_in = array('PTYP'=>$pname);
                    $p_data = $this->mls_masters_model->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match,'');
                    
                    if(!empty($p_data))
                    {
                        foreach($p_data as $row)
                        {
                            $table = $db_name[0]['db_name'].'.mls_property_list_master';
                            $fields = array('id');
                            $oldmatch = "LN = ".$row['LN'];
                            $oldp_data = $this->mls_masters_model->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$oldmatch,'');
                            if(!empty($oldp_data)) {
                                continue;
                            } else {
                                array_shift($row);
                                $row['created_date'] = date('Y-m-d H:i:s');
                                $row['modified_date'] = date('Y-m-d H:i:s');
                                $this->mls_model->insert_property_master($row,$db_name[0]['db_name']);
                                
                                
                                //$url = "http://cdn.sstatic.net/stackoverflow/img/sprites.png";
                                //$target = "/tmp/stackoverflow.png";
                                //copy($url, $target);
                                
                                /*$upload_path=$this->config->item('upload_image_file_path');
                                $path = $upload_path."property_image";

                                if (!is_dir($path.'/'.$row['LN'])) 
                                {
                                    $this->create_dir($row['LN']);
                                }
                                if(file_exists($file)) {
                                    copy($file,$file_to_go);
                                }*/
                            }
                        }
                    }
                }
                else
                {
                    $this->db->truncate($db_name[0]['db_name'].'.mls_property_list_master'); 
                }
            }
            ///// End Fetch property listing data based on settings and insert into Admin DB /////
            
        }
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);    
        redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/edit_right/'.$user_id[0]);
    }
    
    function create_dir($listingID)
    {
            $upload_path=$this->config->item('upload_image_file_path');
            $image_path = $upload_path."property_image/";
            $dir_path=$image_path.$listingID;
            mkdir($dir_path,0777);
            chmod($dir_path,0777);
            //fopen($dir_path.'index.php','x+');

    }
        
    /*
        @Description: Function for for edit mls settings
        @Author     : Sanjay Moghariya
        @Input      : admin id
        @Output     : Edit view
        @Date       : 03-03-2015
    */
    public function edit_mls_settings()
    {
        $table='mls_property_type';
        $fields = array('id,name');
        $where = "status = '1'";
        $data['mls_property_type']= $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$where);

        $table='mls_status_master';
        $fields = array('id,name');
        $where = "status = '1'";
        $data['mls_status_list']= $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$where);

        $table='mls_area_master';
        $fields = array('id,name');
        $where = "status = '1'";
        $data['mls_area_list']= $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$where);

        $id = $this->uri->segment(4);

        $match=array('user_type'=>'2','id'=>$id);
        $fields=array('admin_name','email_id');
        $data['admin_name'] = $this->obj->get_user($fields,$match,'','=');

        //Get user's property type information
        $table = 'user_mls_property_type_trans';
        $fields = array('id,user_id,mls_property_type_id');
        $match = array('user_id'=>$id);
        $pt_result = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);

        $old_type_id = array();
        foreach($pt_result as $row)
        {
            $old_type_id[] = $row['mls_property_type_id'];	
        }
        $data['assigned_property_type'] = $old_type_id;

        //Get user's status information
        $table = 'user_mls_status_trans';
        $fields = array('id,user_id,mls_status_master_id');
        $match = array('user_id'=>$id);
        $status_result = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);

        $old_status_id = array();
        foreach($status_result as $row)
        {
            $old_status_id[] = $row['mls_status_master_id'];	
        }
        $data['assigned_status'] = $old_status_id;

        //Get user's area information
        $table = 'user_mls_area_trans';
        $fields = array('id,user_id,mls_area_master_id');
        $match = array('user_id'=>$id);
        $area_result = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','', '','id','asc','',$match);

        $old_area_id = array();
        foreach($area_result as $row)
        {
            $old_area_id[] = $row['mls_area_master_id'];	
        }
        $data['assigned_area'] = $old_area_id;

        $data['editRecord'] = array('user_id'=>$id);
        $data['main_content'] = "superadmin/".$this->viewName."/mls_settings";       
        $this->load->view("superadmin/include/template",$data);
    }
}