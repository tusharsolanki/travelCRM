<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('mls_masters_model');
        $this->viewName = $this->router->uri->segments[2];
    }
	
    /*
        @Description: Function for Get All master listing
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all contact list
        @Date       : 02-03-2015
    */
    public function index()
    {	
        redirect('superadmin/'.$this->viewName."/add_record");
    }

    /*
        @Description: Function Add/Edit mls master records
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : mls master listing
        @Date       : 03-03-2015
    */
    public function add_record()
    {
        $table = "mls_property_type as mpt";
        $fields = array('mpt.*');//,'lm.user_type');
        $join_tables = array('login_master as lm' => 'lm.id = mpt.created_by');
        $join_tables = array();
        $group_by='mpt.id';
        $match = array();
        $data['property_type'] = $this->mls_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

        $table1 = "mls_status_master as msm";
        $fields1 = array('msm.*','lm.user_type');
        $join_tables1 = array('login_master as lm' => 'lm.id = msm.created_by');
        $group_by1='msm.id';
        $data['mls_status'] = $this->mls_masters_model->getmultiple_tables_records($table1,$fields1,$join_tables1,'left','','','=','','','','',$group_by1,$match);

        $table2 = "mls_area_master as mam";
        $fields2 = array('mam.*','lm.user_type');
        $join_tables2 = array('login_master as lm' => 'lm.id = mam.created_by');
        $group_by2='mam.id';
        $data['mls_area'] = $this->mls_masters_model->getmultiple_tables_records($table2,$fields2,$join_tables2,'left','','','=','','','','',$group_by2,$match);

        $data['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$data);
    }
	
    /*
        @Description: Dipaly all Function Data n List
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     :  
        @Date       : 03-03-2015
    */
    public function all_listing()
    {
        $table = "mls_property_type as mpt";
        $fields = array('mpt.*','lm.user_type');
        $join_tables = array('login_master as lm' => 'lm.id = mpt.created_by');
        $group_by='mpt.id';
        $match = array();
        $data['property_type'] = $this->mls_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

        $table1 = "mls_status_master as msm";
        $fields1 = array('msm.*','lm.user_type');
        $join_tables1 = array('login_master as lm' => 'lm.id = msm.created_by');
        $group_by1='msm.id';
        $data['mls_status'] = $this->mls_masters_model->getmultiple_tables_records($table1,$fields1,$join_tables1,'left','','','=','','','','',$group_by1,$match);

        $table2 = "mls_area_master as mam";
        $fields2 = array('mam.*','lm.user_type');
        $join_tables2 = array('login_master as lm' => 'lm.id = mam.created_by');
        $group_by2='mam.id';
        $data['mls_area'] = $this->mls_masters_model->getmultiple_tables_records($table2,$fields2,$join_tables2,'left','','','=','','','','',$group_by2,$match);

        $data['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$data);
    }
	
    /*
        @Description: Function for Insert property type
        @Author     : Sanjay Moghariya
        @Input      : Property type details
        @Output     : Insert records
        @Date       : 03-03-2015
    */
    public function insert_property_type()
    {
        //$cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['name'] = $this->input->post('property_type');
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $this->mls_masters_model->insert_property_type($cdata);
        // update already added record
        $udata['update'] = $this->input->post('property_type_update');
        $udata['idd'] = $this->input->post('area_idd');
        $update_name = $udata['update'];
        $update_id = $udata['idd'];
        for($u=0;$u<count($update_id);$u++)
        {
            $name = $update_name[$u];
            $id = $update_id[$u];
            $rdata['id'] = $id;
            $rdata['name'] = $name;		
            //$rdata['modified_by'] = $this->superadmin_session['id'];
            //$rdata['modified_date'] = date('Y-m-d H:i:s');
            $this->mls_masters_model->update_property_type($rdata); 
        }
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
    public function insert_mls_area()
    {
        $cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['name'] = $this->input->post('area_list_type');		
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $this->mls_masters_model->insert_mls_area($cdata);
        // update already added record
        $udata['update'] = $this->input->post('area_list_update');
        $udata['idd'] = $this->input->post('document_idd');
        $update_name = $udata['update'];
        $update_id = $udata['idd'];
        for($u=0;$u<count($update_id);$u++)
        {
            $name = $update_name[$u];
            $id = $update_id[$u];
            $rdata['id'] = $id;
            $rdata['name'] = $name;		
            $rdata['modified_by'] = $this->superadmin_session['id'];
            $rdata['modified_date'] = date('Y-m-d H:i:s');
            $this->mls_masters_model->update_mls_area($rdata); 
        }
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');				
    }
	
    public function insert_mls_status()
    {
        $cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['name'] = $this->input->post('mls_status_type');
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $parent_id=$this->mls_masters_model->insert_mls_status($cdata);
        //pr($parent_id);exit;
        // update already added record
        $udata['update'] = $this->input->post('mls_status_update');
        $udata['idd'] = $this->input->post('mls_status_idd');
        $update_name = $udata['update'];
        $update_id = $udata['idd'];
        for($u=0;$u<count($update_id);$u++)
        {
            $name = $update_name[$u];
            $id = $update_id[$u];
            $rdata['id'] = $id; 
            $rdata['name'] = $name;		
            $rdata['modified_by'] = $this->superadmin_session['id'];
            $rdata['modified_date'] = date('Y-m-d H:i:s');
            $this->mls_masters_model->update_mls_status($rdata);   
        }	

        /*
        
        $fields = array('id,db_name,email_id');
        $match1 = array('user_type'=>'2');
        $user_detail = $this->admin_model->get_user($fields,$match1,'','=');
        
        $i=0;
        if(!empty($parent_id))
        {
            foreach($parent_id as $row1)
            {
                if(count($user_detail) > 0)
                {
                    foreach($user_detail as $row)
                    {
                        $data['created_by'] = '1';
                        $data['name'] = $this->input->post('property_status_type');
                        $data['created_date'] = date('Y-m-d H:i:s');
                        $data['status'] = '1';
                        //pr($data);
                        $this->obj->insert_status_record_child_db($row['db_name'],$data);
                        //echo $this->db->last_query();
                        $this->db->last_query();
                    }
                }
            }
        }*/
		
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName."/add_record");				
    }
	
    /*
    @Description: Function for Update contact Profile
    @Author: Mit Makwana
    @Input: - Update details of contact
    @Output: - List with updated contact details
    @Date: 28-06-2014
    */
    public function update_property_type()
    {
        $cdata['id'] = $this->input->post('property_list_id');
        $cdata['name'] = $this->input->post('property_list_type');		
        //$cdata['modified_by'] = $this->admin_session['id'];
        //$cdata['modified_date'] = date('Y-m-d H:i:s');

        $this->mls_masters_model->update_property_type($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        //redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    public function update_mls_area()
    {
        $cdata['id'] = $this->input->post('area_list_id');
        $cdata['name'] = $this->input->post('area_list_type');
        $cdata['modified_by'] = $this->admin_session['id'];		
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->mls_masters_model->update_mls_area($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        //redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    public function update_mls_status()
    {
        $cdata['id'] = $this->input->post('mls_status_id');
        $cdata['name'] = $this->input->post('mls_status_type');	
        $cdata['modified_by'] = $this->superadmin_session['id'];	
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->mls_masters_model->update_mls_status($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
    /*
        @Description: Function for Delete contact Profile By superadmin
        @Author: Mit Makwana
        @Input: - Delete id which contact record want to delete
        @Output: - New contact list after record is deleted.
        @Date: 28-06-2014
    */
    function delete_property_type()
    {
        $id = $this->uri->segment(4);
        $this->mls_masters_model->delete_property_type($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    function delete_mls_area()
    {
        $id = $this->uri->segment(4);
        $this->mls_masters_model->delete_mls_area($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
	
    function delete_mls_status()
    {
        $id = $this->uri->segment(4);
        $this->mls_masters_model->delete_mls_status($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/add_record');
    }
}