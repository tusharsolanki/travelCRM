<?php 
/*
    @Description: Contact controller
    @Author		: Mohit Trivedi
    @Input		: 
    @Output		: 
    @Date		: 02-09-2014

*/


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contact_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('contact_masters_model');
		$this->obj = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
    }
	
    /*
    @Description: Function for Get All contact List
    @Author		: Mohit Trivedi
    @Input		: Search value or null
    @Output		: all contact list
    @Date		: 01-09-2014
    */
    public function index()
    {	
        redirect('superadmin/'.$this->viewName."/add_record");
    }

    /*
    @Description: Function Add New contact details
    @Author		: Mohit Trivedi
    @Input		:
    @Output		: Load Form for add contact details
    @Date		: 01-09-2014
    */

    public function add_record()
    {

		$table = "contact__email_type_master as cem";
		$fields = array('cem.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = cem.created_by');
		$group_by='cem.id';
		$match = array("user_type"=>1);
		$data['email_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

	
		$table1 = "contact__phone_type_master as cpt";
		$fields1 = array('cpt.*','lm.user_type');
		$join_tables1 = array('login_master as lm' => 'lm.id = cpt.created_by');
		$group_by1='cpt.id';
		$data['phone_type'] = $this->obj->getmultiple_tables_records($table1,$fields1,$join_tables1,'left','','','=','','','','',$group_by1,$match);	


		$table2 = "contact__address_type_master as cat";
		$fields2 = array('cat.*','lm.user_type');
		$join_tables2 = array('login_master as lm' => 'lm.id = cat.created_by');
		$group_by2='cat.id';
		$data['address_type'] = $this->obj->getmultiple_tables_records($table2,$fields2,$join_tables2,'left','','','=','','','','',$group_by2,$match);	
	

		$table3 = "contact__status_master as csm";
		$fields3 = array('csm.*','lm.user_type');
		$join_tables3 = array('login_master as lm' => 'lm.id = csm.created_by');
		$group_by3='csm.id';
		$data['status_type'] = $this->obj->getmultiple_tables_records($table3,$fields3,$join_tables3,'left','','','=','','','','',$group_by3,$match);	

		$table4 = "contact__social_type_master as cstm";
		$fields4 = array('cstm.*','lm.user_type');
		$join_tables4 = array('login_master as lm' => 'lm.id = cstm.created_by');
		$group_by4='cstm.id';
		$data['profile_type'] = $this->obj->getmultiple_tables_records($table4,$fields4,$join_tables4,'left','','','=','','','','',$group_by4,$match);	

		$table5 = "contact__type_master as ctm";
		$fields5 = array('ctm.*','lm.user_type');
		$join_tables5 = array('login_master as lm' => 'lm.id = ctm.created_by');
		$group_by5='ctm.id';
		$data['contact_type'] = $this->obj->getmultiple_tables_records($table5,$fields5,$join_tables5,'left','','','=','','','','',$group_by5,$match);	


		$table6 = "contact__document_type_master as cdm";
		$fields6 = array('cdm.*','lm.user_type');
		$join_tables6 = array('login_master as lm' => 'lm.id = cdm.created_by');
		$group_by6='cdm.id';
		$data['document_type'] = $this->obj->getmultiple_tables_records($table6,$fields6,$join_tables6,'left','','','=','','','','',$group_by6,$match);	

		$table7 = "contact__source_master as csm";
		$fields7 = array('csm.*','lm.user_type');
		$join_tables7 = array('login_master as lm' => 'lm.id = csm.created_by');
		$group_by7='csm.id';
		$data['source_type'] = $this->obj->getmultiple_tables_records($table7,$fields7,$join_tables7,'left','','','=','','','','',$group_by7,$match);	


		$table8 = "contact__disposition_master as cdm";
		$fields8 = array('cdm.*','lm.user_type');
		$join_tables8 = array('login_master as lm' => 'lm.id = cdm.created_by');
		$group_by8='cdm.id';
		$data['disposition_type'] = $this->obj->getmultiple_tables_records($table8,$fields8,$join_tables8,'left','','','=','','','','',$group_by8,$match);	
		
		
		$table9 = "contact__method_master as cmm";
		$fields9 = array('cmm.*','lm.user_type');
		$join_tables9 = array('login_master as lm' => 'lm.id = cmm.created_by');
		$group_by9='cmm.id';
		$data['method_type'] = $this->obj->getmultiple_tables_records($table9,$fields9,$join_tables9,'left','','','=','','','','',$group_by9,$match);
		
		$table10 = "contact__websitetype_master as cwm";
		$fields10 = array('cwm.*','lm.user_type');
		$join_tables10 = array('login_master as lm' => 'lm.id = cwm.created_by');
		$group_by10='cwm.id';
		$data['website_type'] = $this->obj->getmultiple_tables_records($table10,$fields10,$join_tables10,'left','','','=','','','','',$group_by10,$match);	

		$table11 = "contact__additionalfield_master as cafm";
		$fields11 = array('cafm.*','lm.user_type');
		$join_tables11 = array('login_master as lm' => 'lm.id = cafm.created_by');
		$group_by11='cafm.id';
		$data['field_type'] = $this->obj->getmultiple_tables_records($table11,$fields11,$join_tables11,'left','','','=','','','','',$group_by11,$match);

		
		$data['main_content'] = "superadmin/".$this->viewName."/add";       
	   	$this->load->view("superadmin/include/template",$data);
    }

	
    /*
    @Description: Dipaly all Function Data n List
    @Author		: Mohit Trivedi
    @Input		: 
    @Output		: 
    @Date		: 01-09-2014
    */
	public function all_listing()
	{
		$match = array("created_by"=>$this->superadmin_session['id']);
		//$match = array();
		$data['email_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__email_type_master');
		$data['phone_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__phone_type_master');	
		$data['address_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__address_type_master');	
		//$data['website_type'] = $this->obj->select_records1('',$match,'','=','','','','','','website_type');	
		$data['status_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__status_master');	
		$data['profile_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__social_type_master');	
		$data['contact_type'] = $this->obj->select_records1('',$match,'','=','','','','','','contact__type_master');	
		$data['document_type'] = $this->obj->select_records1('',$match,'','=','','','','','', 'contact__document_type_master');
		$data['source_type'] = $this->obj->select_records1('',$match,'','=','','','','','', 'contact__source_master');
		$data['disposition_type'] = $this->obj->select_records1('',$match,'','=','','','','id','desc', 'contact__disposition_master');
		//pr($email_type);exit;	
		$data['main_content'] = "superadmin/".$this->viewName."/add";       
	   	$this->load->view("superadmin/include/template",$data);
	}

	
    /*
    @Description: Function for Insert New contact email data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact email which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
    public function insert_email()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('email_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_email($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('email_update');
		$udata['idd'] = $this->input->post('email_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_email($rdata);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }

    /*
    @Description: Function for Insert New contact phone data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact phone which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_phone()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('phone_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_phone($cdata);
		// update already added record
		$udata['update'] = $this->input->post('phone_update');
		$udata['idd'] = $this->input->post('phone_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_phone($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }

    /*
    @Description: Function for Insert New contact address data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact address which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_address()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('address_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_address($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('address_update');
		$udata['idd'] = $this->input->post('address_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_address($rdata);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_website()
    {
		
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('website_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_website($cdata);
		// update already added record
		$udata['update'] = $this->input->post('website_update');
		$udata['idd'] = $this->input->post('website_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_website($rdata);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	

    /*
    @Description: Function for Insert New contact status data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact Status which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_status()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('status_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_status($cdata);
		// update already added record
		$udata['update'] = $this->input->post('status_update');
		$udata['idd'] = $this->input->post('status_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_status($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
    /*
    @Description: Function for Insert New contact profile data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact profile which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_profile()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('profile_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_profile($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('profile_update');
		$udata['idd'] = $this->input->post('profile_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_profile($rdata);
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
 
    /*
    @Description: Function for Insert New contact data
    @Author		: Mohit Trivedi
    @Input		: Details of new contact which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_contact()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('contact_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_contact($cdata);
		// update already added record
		$udata['update'] = $this->input->post('contact_update');
		$udata['idd'] = $this->input->post('contact_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_contact($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
    /*
    @Description: Function for Insert New Document data
    @Author		: Mohit Trivedi
    @Input		: Details of new Document which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_document()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('document_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_document($cdata);
		// update already added record
		$udata['update'] = $this->input->post('document_update');
		$udata['idd'] = $this->input->post('document_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_document($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
    /*
    @Description: Function for Insert New Source data
    @Author		: Mohit Trivedi
    @Input		: Details of new Source which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_source()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('source_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_source($cdata);
		// update already added record
		$udata['update'] = $this->input->post('source_update');
		$udata['idd'] = $this->input->post('source_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_source($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }

    /*
    @Description: Function for Insert New Disposition data
    @Author		: Mohit Trivedi
    @Input		: Details of new Disposition which is inserted into DB
    @Output		: List of contact with new inserted records
    @Date		: 01-09-2014
    */
	public function insert_disposition()
    {
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('disposition_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_disposition($cdata);
		// update already added record
		$udata['update'] = $this->input->post('disposition_update');
		$udata['idd'] = $this->input->post('disposition_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_disposition($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
	
	/*
		@Description: Function for Insert New Contact Method data
		@Author		: Sanjay Chabhadiya
		@Input		: Details of new Contact method which is inserted into DB
		@Output		: List of contact with new inserted records
		@Date		: 02-01-2015
    */
	public function insert_method()
    {
		
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('method_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_method($cdata);
		// update already added record
		$udata['update'] = $this->input->post('method_update');
		$udata['idd'] = $this->input->post('method_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_method($rdata);
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
	
	/*
		@Description: Function for Insert New Contact Additional Field
		@Author		: Sanjay Chabhadiya
		@Input		: Details of new Contact Addition Field which is inserted into DB
		@Output		: List of contact with new inserted records
		@Date		: 02-01-2015
    */
	public function insert_field()
    {
		
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['name'] = $this->input->post('field_name');
		$cdata['field_type'] = $this->input->post('field_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_field($cdata);
		// update already added record
		$udata['update'] = $this->input->post('field_name_edit');
		$udata['update_field'] = $this->input->post('field_type_edit_action');
		$udata['idd'] = $this->input->post('field_name_idd');
		$update_name = $udata['update'];
		$update_id = $udata['idd'];
		$update_field = $udata['update_field'];
		for($u=0;$u<count($update_id);$u++)
		{
			$name = $update_name[$u];
			$id = $update_id[$u];
			$field_type = $update_field[$u];
			$rdata['id'] = $id;
			$rdata['name'] = $name;
			$rdata['field_type'] = $field_type;		
			$rdata['modified_by'] = $this->admin_session['id'];
			$rdata['modified_date'] = date('Y-m-d H:i:s');
			$this->obj->update_field($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
	
	
    /*
    @Description: Function for Update email 
    @Author		: Mohit Trivedi
    @Input		: Update details of email
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
    public function update_email()
    {
		$cdata['id'] = $this->input->post('email_id');
		$cdata['name'] = $this->input->post('email_type');		
		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_email($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
  	}

    /*
    @Description: Function for Update Phone
    @Author		: Mohit Trivedi
    @Input		: Update details of Phone
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_phone()
    {
        $cdata['id'] = $this->input->post('phone_id');
		$cdata['name'] = $this->input->post('phone_type');
		$cdata['modified_by'] = $this->superadmin_session['id'];		
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_phone($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Update Address
    @Author		: Mohit Trivedi
    @Input		: Update details of Address
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_address()
    {
        $cdata['id'] = $this->input->post('address_id');
		$cdata['name'] = $this->input->post('address_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_address($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Update website
    @Author		: Mohit Trivedi
    @Input		: Update details of website
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_website()
    {
        $cdata['id'] = $this->input->post('website_id');
		$cdata['name'] = $this->input->post('website_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_website($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Update Status
    @Author		: Mohit Trivedi
    @Input		: Update details of Status
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_status()
    {
        $cdata['id'] = $this->input->post('status_id');
		$cdata['name'] = $this->input->post('status_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_status($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Profile
    @Author		: Mohit Trivedi
    @Input		: Update details of Profile
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_profile()
    {
        $cdata['id'] = $this->input->post('profile_id');
		$cdata['name'] = $this->input->post('profile_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_profile($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Contact
    @Author		: Mohit Trivedi
    @Input		: Update details of Contact
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_contact()
    {
        $cdata['id'] = $this->input->post('contact_id');
		$cdata['name'] = $this->input->post('contact_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		
		$this->obj->update_contact($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
    /*
    @Description: Function for Document
    @Author		: Mohit Trivedi
    @Input		: Update details of Document
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_document()
    {
        $cdata['id'] = $this->input->post('document_id');
		$cdata['name'] = $this->input->post('document_type');		
		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_document($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Sourcce
    @Author		: Mohit Trivedi
    @Input		: Update details of Sourcce
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_source()
    {
        $cdata['id'] = $this->input->post('source_id');
		$cdata['name'] = $this->input->post('source_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_source($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Disposition
    @Author		: Mohit Trivedi
    @Input		: Update details of Disposition
    @Output		: List with updated contact details
    @Date		: 01-09-2014
    */
	public function update_disposition()
    {
        $cdata['id'] = $this->input->post('disposition_id');
		$cdata['name'] = $this->input->post('disposition_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_disposition($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	/*
		@Description: Function for Contact Method
		@Author		: Sanjay Chabhadiya
		@Input		: Update details of Contact Method
		@Output		: List with updated contact details
		@Date		: 02-01-2015
    */
	public function update_method()
    {
        $cdata['id'] = $this->input->post('method_id');
		$cdata['name'] = $this->input->post('method_type');	
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_method($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	/*
		@Description: Function for Contact Additional Field
		@Author		: Sanjay Chabhadiya
		@Input		: Update details of Contact Additional Field
		@Output		: List with updated contact details
		@Date		: 02-01-2015
    */
	public function update_field()
    {
        $cdata['id'] = $this->input->post('field_id');
		$cdata['name'] = $this->input->post('field_name');	
		$cdata['field_type'] = $this->input->post('field_type');
		$cdata['modified_by'] = $this->superadmin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_field($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
    /*
    @Description: Function for Delete contact email By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which contact email want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
    function delete_email_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_email_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete contact Phone By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which contact Phone want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_phone_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_phone_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete address By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which address want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_address_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_address_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete website By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which website want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_website_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_website_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete status By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which status want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_status_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_status_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete profile By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which profile want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_profile_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_profile_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
    /*
    @Description: Function for Delete contact By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which contact want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_contact_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_contact_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete document By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which document want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */
	function delete_document_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_document_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    /*
    @Description: Function for Delete source By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which source want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */

	function delete_source_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_source_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }

    /*
    @Description: Function for Delete disposition By Superadmin
    @Author		: Mohit Trivedi
    @Input		: Delete id which disposition want to delete
    @Output		: New contact list after record is deleted.
    @Date		: 01-09-2014
    */

	function delete_disposition_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_disposition_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
	/*
		@Description: Function for Delete Contact method By Superadmin
		@Author		: Sanjay Chabhadiya
		@Input		: Delete id which Contact method want to delete
		@Output		: New contact list after record is deleted.
		@Date		: 02-01-2015
    */
	
	function delete_method_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_method_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
	/*
		@Description: Function for Delete Contact Additional Field By Superadmin
		@Author		: Sanjay Chabhadiya
		@Input		: Delete id which Contact Additional Fields want to delete
		@Output		: New contact list after record is deleted.
		@Date		: 02-01-2015
    */
	
	function delete_field_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_field_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
}