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
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			$sortfield = 'id';
			$sortby = 'desc';
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		if(!empty($searchopt))
		{
			$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($date1) && !empty($date2))
		{
			 $date1 = $this->input->post('date1');
			 $date2 = $this->input->post('date2');
			 $data['date1'] = $date1;
           	 $data['date2'] = $date2;	
		}
		if(!empty($perpage) && $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/'."admin_management/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		
		if(!empty($searchtext))
		{
			$match=array('admin_name'=>$searchtext,'email_id'=>$searchtext);
			$where=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			$config['total_rows'] = count($this->obj->get_user('',$match,'','like',''));
		}
		else
		{
			$match=array('user_type'=>'2');
			$data['datalist'] = $this->obj->get_user('',$match,'','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$config['total_rows']= count($this->obj->get_user('',$match));
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['msg'] = $this->message_session['msg'];

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
		
		// Create a 
		
		$newdatabasename = $this->obj->getnewdbname()+1;
		
		//$databasename = "livewire_crm_".md5(uniqid().$newdatabasename);			//For local
		$databasename = "topsin_livewire_crm_".md5(uniqid().$newdatabasename);		//For topsdemo.in
		
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
		
		$databaseusername = "topsin_l_u_".$newdatabasename;
		
		$is_dbuser_created = $this->obj->createnewdbuser($databaseusername);
		
		//pr($is_dbuser_created);exit;
	 
		$cdata['admin_name'] 		= $this->input->post('admin_name');
		$cdata['email_id']			= $this->input->post('txt_email_id');
		$cdata['password'] 			= $this->common_function_model->encrypt_script($this->input->post('password'));
		
		$cdata['db_name']			= $databasename;
		$cdata['host_name']			= "localhost";
		$cdata['db_user_name']		= $databaseusername;
		$cdata['db_user_password']	= $databaseusername;
		
		$cdata['user_type'] 		= '2';
		$cdata['created_by'] 		= $this->superadmin_session['id'];
		$cdata['created_date'] 		= date('Y-m-d H:i:s');		
		$cdata['status'] 			= '1';
		
		//pr($cdata);
		
		$lastId=$this->obj->insert_user($cdata);
		
		if(is_db_created)
		{
			//$parent_db = "livewire_crm";							//For local
			$parent_db = "topsin_livewire_crm_v2_new";			//For topsdemo.in
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
		redirect('superadmin/'.$this->viewName);
		
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
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
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
		$cdata['email_id'] = $this->input->post('email_id');
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
		$cdata['status'] = '1';
		$this->obj->update_user($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$admin_id = $this->input->post('id');
		$pagingid = $this->obj->getadminpagingid($admin_id);
		//pr($pagingid);exit;
		redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
		
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
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->obj->delete_user($id);
			unset($id);
		}
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_user($array_data[$i]);
		}
		echo 1;
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
		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
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
		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
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
		
		$email=$this->input->post('email');
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
}