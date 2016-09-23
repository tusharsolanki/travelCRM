<?php 
/*
    @Description: Contact controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 28-06-14

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class property_list_masters_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
		
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('property_list_masters_model');
		
		$this->obj = $this->property_list_masters_model;
		$this->viewName = $this->router->uri->segments[2];
    }
	
    /*
    @Description: Function for Get All contact List
    @Author: Mit Makwana
    @Input: - Search value or null
    @Output: - all contact list
    @Date: 28-06-2014
    */
	
    public function index()
    {	
		//check user right
		check_rights('configuration_listing_manager');
        redirect('admin/'.$this->viewName."/add_record");
    }

    /*
    @Description: Function Add New contact details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add contact details
    @Date: 01-09-2014
    */
    public function add_record()
    {
		
		$property_listing_iframe = $this->uri->segment(4);
		$data['property_master'] = $this->uri->segment(5);
        if(!empty($property_listing_iframe) && $property_listing_iframe == 'property_master_iframe')
			$data['property_listing_iframe'] = 'iframe';
		else
			$data['property_listing_iframe'] = 'all';
		$table = "property_listing__property_type_master as plpt";
		$fields = array('plpt.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = plpt.created_by');
		$group_by='plpt.id';
		$match = array();
		$data['property_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

	
		$table1 = "  property_listing__document_type_master as pldt";
		$fields1 = array('pldt.*','lm.user_type');
		$join_tables1 = array('login_master as lm' => 'lm.id = pldt.created_by');
		$group_by1='pldt.id';
		$data['document_list_type'] = $this->obj->getmultiple_tables_records($table1,$fields1,$join_tables1,'left','','','=','','','','',$group_by1,$match);	


		$table2 = "property_listing__lot_type_master as plt";
		$fields2 = array('plt.*','lm.user_type');
		$join_tables2 = array('login_master as lm' => 'lm.id = plt.created_by');
		$group_by2='plt.id';
		$data['lot_type'] = $this->obj->getmultiple_tables_records($table2,$fields2,$join_tables2,'left','','','=','','','','',$group_by2,$match);	
	

		$table3 = " property_listing__transaction_type_master as pltm";
		$fields3 = array('pltm.*','lm.user_type');
		$join_tables3 = array('login_master as lm' => 'lm.id = pltm.created_by');
		$group_by3='pltm.id';
		$data['transaction_type'] = $this->obj->getmultiple_tables_records($table3,$fields3,$join_tables3,'left','','','=','','','','',$group_by3,$match);	

		$table4 = "property_listing__lockbox_type_master as pllt";
		$fields4 = array('pllt.*','lm.user_type');
		$join_tables4 = array('login_master as lm' => 'lm.id = pllt.created_by');
		$group_by4='pllt.id';
		$data['lockbox_type'] = $this->obj->getmultiple_tables_records($table4,$fields4,$join_tables4,'left','','','=','','','','',$group_by4,$match);	

		$table5 = "property_listing__sewer_master as plsm";
		$fields5 = array('plsm.*','lm.user_type');
		$join_tables5 = array('login_master as lm' => 'lm.id = plsm.created_by');
		$group_by5='plsm.id';
		$data['sewer_type'] = $this->obj->getmultiple_tables_records($table5,$fields5,$join_tables5,'left','','','=','','','','',$group_by5,$match);	


		$table6 = "property_listing__basement_master as pbm";
		$fields6 = array('pbm.*','lm.user_type');
		$join_tables6 = array('login_master as lm' => 'lm.id = pbm.created_by');
		$group_by6='pbm.id';
		$data['basement_type'] = $this->obj->getmultiple_tables_records($table6,$fields6,$join_tables6,'left','','','=','','','','',$group_by6,$match);	

		$table7 = "property_listing__architecture_master as pam";
		$fields7 = array('pam.*','lm.user_type');
		$join_tables7 = array('login_master as lm' => 'lm.id = pam.created_by');
		$group_by7='pam.id';
		$data['architecture_type'] = $this->obj->getmultiple_tables_records($table7,$fields7,$join_tables7,'left','','','=','','','','',$group_by7,$match);	


		$table8 = "property_listing__energy_source_master as pesm";
		$fields8 = array('pesm.*','lm.user_type');
		$join_tables8 = array('login_master as lm' => 'lm.id = pesm.created_by');
		$group_by8='pesm.id';
		$data['energy_source_type'] = $this->obj->getmultiple_tables_records($table8,$fields8,$join_tables8,'left','','','=','','','','',$group_by8,$match);	
		
		$table9 = "property_listing__exterior_finish_master as pefm";
		$fields9 = array('pefm.*','lm.user_type');
		$join_tables9 = array('login_master as lm' => 'lm.id = pefm.created_by');
		$group_by9='pefm.id';
		$data['exterior_finish_type'] = $this->obj->getmultiple_tables_records($table9,$fields9,$join_tables9,'left','','','=','','','','',$group_by9,$match);	
		
		$table10 = "property_listing__fireplace_master as pfm";
		$fields10 = array('pfm.*','lm.user_type');
		$join_tables10 = array('login_master as lm' => 'lm.id = pfm.created_by');
		$group_by10='pfm.id';
		$data['fireplace_type'] = $this->obj->getmultiple_tables_records($table10,$fields10,$join_tables10,'left','','','=','','','','',$group_by10,$match);	

		$table11 = "property_listing__floor_covering_master as plfcm";
		$fields11 = array('plfcm.*','lm.user_type');
		$join_tables11 = array('login_master as lm' => 'lm.id = plfcm.created_by');
		$group_by11='plfcm.id';
		$data['floor_covering_type'] = $this->obj->getmultiple_tables_records($table11,$fields11,$join_tables11,'left','','','=','','','','',$group_by11,$match);	
		
		$table12 = "property_listing__foundation_master as pfm";
		$fields12 = array('pfm.*','lm.user_type');
		$join_tables12 = array('login_master as lm' => 'lm.id = pfm.created_by');
		$group_by12='pfm.id';
		$data['foundation_type'] = $this->obj->getmultiple_tables_records($table12,$fields12,$join_tables12,'left','','','=','','','','',$group_by12,$match);
		
		
		$table13 = "property_listing__green_certification_master as pgcm";
		$fields13 = array('pgcm.*','lm.user_type');
		$join_tables13 = array('login_master as lm' => 'lm.id = pgcm.created_by');
		$group_by13='pgcm.id';
		$data['green_certification_type'] = $this->obj->getmultiple_tables_records($table13,$fields13,$join_tables13,'left','','','=','','','','',$group_by13,$match);
		
		$table14 = "property_listing__heating_cooling_master as phcm";
		$fields14 = array('phcm.*','lm.user_type');
		$join_tables14 = array('login_master as lm' => 'lm.id = phcm.created_by');
		$group_by14='phcm.id';
		$data['heating_cooling_type'] = $this->obj->getmultiple_tables_records($table14,$fields14,$join_tables14,'left','','','=','','','','',$group_by14,$match);
	
		$table15 = "property_listing__interior_feature_master as pifm";
		$fields15 = array('pifm.*','lm.user_type');
		$join_tables15 = array('login_master as lm' => 'lm.id = pifm.created_by');
		$group_by15='pifm.id';
		$data['interior_feature_type'] = $this->obj->getmultiple_tables_records($table15,$fields15,$join_tables15,'left','','','=','','','','',$group_by15,$match);
		
		$table16 = "property_listing__parking_type_master as pptm";
		$fields16 = array('pptm.*','lm.user_type');
		$join_tables16 = array('login_master as lm' => 'lm.id = pptm.created_by');
		$group_by16='pptm.id';
		$data['parking_type'] = $this->obj->getmultiple_tables_records($table16,$fields16,$join_tables16,'left','','','=','','','','',$group_by16,$match);
		
		$table17 = "property_listing__power_company_master as ppcm";
		$fields17 = array('ppcm.*','lm.user_type');
		$join_tables17 = array('login_master as lm' => 'lm.id = ppcm.created_by');
		$group_by17='ppcm.id';
		$data['power_company_type'] = $this->obj->getmultiple_tables_records($table17,$fields17,$join_tables17,'left','','','=','','','','',$group_by17,$match);
		
		$table18 = "property_listing__roof_master as prm";
		$fields18 = array('prm.*','lm.user_type');
		$join_tables18 = array('login_master as lm' => 'lm.id = prm.created_by');
		$group_by18='prm.id';
		$data['roof_master_type'] = $this->obj->getmultiple_tables_records($table18,$fields18,$join_tables18,'left','','','=','','','','',$group_by18,$match);
		
		
		$table19 = "property_listing__sewer_company_master as pscm";
		$fields19 = array('pscm.*','lm.user_type');
		$join_tables19 = array('login_master as lm' => 'lm.id = pscm.created_by');
		$group_by19='pscm.id';
		$data['sewer_company_type'] = $this->obj->getmultiple_tables_records($table19,$fields19,$join_tables19,'left','','','=','','','','',$group_by19,$match);
		
		$table20 = "property_listing__style_master as psm";
		$fields20 = array('psm.*','lm.user_type');
		$join_tables20 = array('login_master as lm' => 'lm.id = psm.created_by');
		$group_by20='psm.id';
		$data['style_master_type'] = $this->obj->getmultiple_tables_records($table20,$fields20,$join_tables20,'left','','','=','','','','',$group_by20,$match);
		
		$table21 = "property_listing__water_company_master as pwcm";
		$fields21 = array('pwcm.*','lm.user_type');
		$join_tables21 = array('login_master as lm' => 'lm.id = pwcm.created_by');
		$group_by21='pwcm.id';
		$data['water_company_type'] = $this->obj->getmultiple_tables_records($table21,$fields21,$join_tables21,'left','','','=','','','','',$group_by21,$match);
		
		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
    }

	
    /*
    @Description: Dipaly all Function Data n List
    @Author: Mit Makwana
    @Input: - 
    @Output: - 
    @Date: 28-06-2014
    */
	public function all_listing()
	{
		//$match = array("created_by"=>$this->admin_session['id']);
		$table = "property_listing__property_type_master as plpt";
		$fields = array('plpt.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = plpt.created_by');
		$group_by='plpt.id';
		$match = array();
		$data['property_list_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);

	
		$table1 = " property_listing__document_type_master as pldt";
		$fields1 = array('pldt.*','lm.user_type');
		$join_tables1 = array('login_master as lm' => 'lm.id = pldt.created_by');
		$group_by1='pldt.id';
		$data['document_list_type'] = $this->obj->getmultiple_tables_records($table1,$fields1,$join_tables1,'left','','','=','','','','',$group_by1,$match);	

		$table2 = "property_listing__lot_type_master as plt";
		$fields2 = array('plt.*','lm.user_type');
		$join_tables2 = array('login_master as lm' => 'lm.id = plt.created_by');
		$group_by2='plt.id';
		$data['lot_type_list'] = $this->obj->getmultiple_tables_records($table2,$fields2,$join_tables2,'left','','','=','','','','',$group_by2,$match);	
	

		$table3 = " property_listing__transaction_type_master as pltm";
		$fields3 = array('pltm.*','lm.user_type');
		$join_tables3 = array('login_master as lm' => 'lm.id = pltm.created_by');
		$group_by3='pltm.id';
		$data['transaction_type'] = $this->obj->getmultiple_tables_records($table3,$fields3,$join_tables3,'left','','','=','','','','',$group_by3,$match);	

		$table4 = "property_listing__lockbox_type_master as pllt";
		$fields4 = array('pllt.*','lm.user_type');
		$join_tables4 = array('login_master as lm' => 'lm.id = pllt.created_by');
		$group_by4='pllt.id';
		$data['lockbox_type'] = $this->obj->getmultiple_tables_records($table4,$fields4,$join_tables4,'left','','','=','','','','',$group_by4,$match);	

		$table5 = "property_listing__sewer_master as plsm";
		$fields5 = array('plsm.*','lm.user_type');
		$join_tables5 = array('login_master as lm' => 'lm.id = plsm.created_by');
		$group_by5='plsm.id';
		$data['sewer_type'] = $this->obj->getmultiple_tables_records($table5,$fields5,$join_tables5,'left','','','=','','','','',$group_by5,$match);	

		$table7 = "property_listing__architecture_master as pam";
		$fields7 = array('pam.*','lm.user_type');
		$join_tables7 = array('login_master as lm' => 'lm.id = pam.created_by');
		$group_by7='pam.id';
		$data['architecture_type'] = $this->obj->getmultiple_tables_records($table7,$fields7,$join_tables7,'left','','','=','','','','',$group_by7,$match);	


		$table8 = "property_listing__energy_source_master as pesm";
		$fields8 = array('pesm.*','lm.user_type');
		$join_tables8 = array('login_master as lm' => 'lm.id = pesm.created_by');
		$group_by8='pesm.id';
		$data['energy_source_type'] = $this->obj->getmultiple_tables_records($table8,$fields8,$join_tables8,'left','','','=','','','','',$group_by8,$match);	

		$table9 = "property_listing__exterior_finish_master as pefm";
		$fields9 = array('pefm.*','lm.user_type');
		$join_tables9 = array('login_master as lm' => 'lm.id = pefm.created_by');
		$group_by9='pefm.id';
		$data['exterior_finish_type'] = $this->obj->getmultiple_tables_records($table9,$fields9,$join_tables9,'left','','','=','','','','',$group_by9,$match);	

		$table10 = "property_listing__fireplace_master as pfm";
		$fields10 = array('pfm.*','lm.user_type');
		$join_tables10 = array('login_master as lm' => 'lm.id = pfm.created_by');
		$group_by10='pfm.id';
		$data['fireplace_type'] = $this->obj->getmultiple_tables_records($table10,$fields10,$join_tables10,'left','','','=','','','','',$group_by10,$match);	
	
		$table11 = "property_listing__floor_covering_master as plfcm";
		$fields11 = array('plfcm.*','lm.user_type');
		$join_tables11 = array('login_master as lm' => 'lm.id = plfcm.created_by');
		$group_by11='plfcm.id';
		$data['floor_covering_type'] = $this->obj->getmultiple_tables_records($table11,$fields11,$join_tables11,'left','','','=','','','','',$group_by11,$match);	
	
		$table12 = "property_listing__foundation_master as pfm";
		$fields12 = array('pfm.*','lm.user_type');
		$join_tables12 = array('login_master as lm' => 'lm.id = pfm.created_by');
		$group_by12='pfm.id';
		$data['foundation_type'] = $this->obj->getmultiple_tables_records($table12,$fields12,$join_tables12,'left','','','=','','','','',$group_by12,$match);
		
		$table13 = "property_listing__green_certification_master as pgcm";
		$fields13 = array('pgcm.*','lm.user_type');
		$join_tables13 = array('login_master as lm' => 'lm.id = pgcm.created_by');
		$group_by13='pgcm.id';
		$data['green_certification_type'] = $this->obj->getmultiple_tables_records($table13,$fields13,$join_tables13,'left','','','=','','','','',$group_by13,$match);
		
		$table14 = "property_listing__heating_cooling_master as phcm";
		$fields14 = array('phcm.*','lm.user_type');
		$join_tables14 = array('login_master as lm' => 'lm.id = phcm.created_by');
		$group_by14='phcm.id';
		$data['heating_cooling_type'] = $this->obj->getmultiple_tables_records($table14,$fields14,$join_tables14,'left','','','=','','','','',$group_by14,$match);
	
		$table15 = "property_listing__interior_feature_master as pifm";
		$fields15 = array('pifm.*','lm.user_type');
		$join_tables15 = array('login_master as lm' => 'lm.id = pifm.created_by');
		$group_by15='pifm.id';
		$data['interior_feature_type'] = $this->obj->getmultiple_tables_records($table15,$fields15,$join_tables15,'left','','','=','','','','',$group_by15,$match);
		
		$table16 = "property_listing__parking_type_master as pptm";
		$fields16 = array('pptm.*','lm.user_type');
		$join_tables16 = array('login_master as lm' => 'lm.id = pptm.created_by');
		$group_by16='pptm.id';
		$data['parking_type'] = $this->obj->getmultiple_tables_records($table16,$fields16,$join_tables16,'left','','','=','','','','',$group_by16,$match);
		
		$table17 = "property_listing__power_company_master as ppcm";
		$fields17 = array('ppcm.*','lm.user_type');
		$join_tables17 = array('login_master as lm' => 'lm.id = ppcm.created_by');
		$group_by17='ppcm.id';
		$data['power_company_type'] = $this->obj->getmultiple_tables_records($table17,$fields17,$join_tables17,'left','','','=','','','','',$group_by17,$match);
		
		$table18 = "property_listing__roof_master as prm";
		$fields18 = array('prm.*','lm.user_type');
		$join_tables18 = array('login_master as lm' => 'lm.id = prm.created_by');
		$group_by18='prm.id';
		$data['roof_master_type'] = $this->obj->getmultiple_tables_records($table18,$fields18,$join_tables18,'left','','','=','','','','',$group_by18,$match);
		
		$table19 = "property_listing__sewer_company_master as pscm";
		$fields19 = array('pscm.*','lm.user_type');
		$join_tables19 = array('login_master as lm' => 'lm.id = pscm.created_by');
		$group_by19='pscm.id';
		$data['sewer_company_type'] = $this->obj->getmultiple_tables_records($table19,$fields19,$join_tables19,'left','','','=','','','','',$group_by19,$match);
		
		$table20 = "property_listing__style_master as psm";
		$fields20 = array('psm.*','lm.user_type');
		$join_tables20 = array('login_master as lm' => 'lm.id = psm.created_by');
		$group_by20='psm.id';
		$data['style_master_type'] = $this->obj->getmultiple_tables_records($table20,$fields20,$join_tables20,'left','','','=','','','','',$group_by20,$match);
		
		$table21 = "property_listing__water_company_master as pwcm";
		$fields21 = array('pwcm.*','lm.user_type');
		$join_tables21 = array('login_master as lm' => 'lm.id = pwcm.created_by');
		$group_by21='pwcm.id';
		$data['water_company_type'] = $this->obj->getmultiple_tables_records($table21,$fields21,$join_tables21,'left','','','=','','','','',$group_by21,$match);
		
		$data['main_content'] = "admin/".$this->viewName."/add";       
	   	$this->load->view("admin/include/template",$data);
	}

	
    /*
    @Description: Function for Insert New contact data
    @Author: Mit Makwana
    @Input: - Details of new contact which is inserted into DB
    @Output: - List of contact with new inserted records
    @Date: 28-06-2014
    */
    public function insert_property_list()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('property_list_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_property_list($cdata);
		// update already added record
		$udata['update'] = $this->input->post('property_list_update');
		$udata['idd'] = $this->input->post('property_idd');
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
			$this->obj->update_property_list($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_document_list()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('document_list_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_document_list($cdata);
		// update already added record
		$udata['update'] = $this->input->post('document_list_update');
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
			$this->obj->update_document_list($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_lot_type_list()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('lot_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_lot_type($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('lot_type_update');
		$udata['idd'] = $this->input->post('lot_idd');
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
			$this->obj->update_lot_type($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_trasaction()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('transaction_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_transaction($cdata);
		// update already added record
		$udata['update'] = $this->input->post('transaction_update');
		$udata['idd'] = $this->input->post('transaction_idd');
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
			$this->obj->update_transaction($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_lockbox()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('lockbox_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_lockbox($cdata);
		// update already added record
		$udata['update'] = $this->input->post('lockbox_update');
		$udata['idd'] = $this->input->post('lock_idd');
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
			$this->obj->update_lockbox($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_sewer()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('sewer_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_sewer($cdata);
		// update already added record
		$udata['update'] = $this->input->post('sewer_update');
		$udata['idd'] = $this->input->post('sewer_idd');
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
			$this->obj->update_sewer($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	public function insert_basement()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('basement_type');		
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_basement($cdata);
		// update already added record
		$udata['update'] = $this->input->post('basement_update');
		$udata['idd'] = $this->input->post('basement_idd');
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
			$this->obj->update_basement($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');				
    }
	
	 public function insert_architecture()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('architecture_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_architecture($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('architecture_update');
		$udata['idd'] = $this->input->post('architecture_idd');
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
			$this->obj->update_architecture($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	 public function insert_energy_source()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('energy_source_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_energy_source($cdata);
		// update already added record
		$udata['update'] = $this->input->post('energy_source_update');
		$udata['idd'] = $this->input->post('energy_source_idd');
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
			$this->obj->update_energy_source($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }

	 public function insert_exterior_finish()
    {
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('exterior_finish_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_exterior_finish($cdata);	
		// update already added record
		$udata['update'] = $this->input->post('exterior_finish_update');
		$udata['idd'] = $this->input->post('exterior_finish_idd');
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
			$this->obj->update_exterior_finish($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }

	public function insert_fireplace()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('fireplace_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_fireplace($cdata);
		// update already added record
		$udata['update'] = $this->input->post('fireplace_update');
		$udata['idd'] = $this->input->post('fireplace_idd');
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
			$this->obj->update_fireplace($rdata); 
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_floor_covering()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('floor_covering_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_floor_covering($cdata);
		// update already added record
		$udata['update'] = $this->input->post('floor_covering_update');
		$udata['idd'] = $this->input->post('floor_covering_idd');
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
			$this->obj->update_floor_covering($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }

	public function insert_foundation()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('foundation_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_foundation($cdata);
		// update already added record
		$udata['update'] = $this->input->post('foundation_update');
		$udata['idd'] = $this->input->post('foundation_idd');
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
			$this->obj->update_foundation($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	public function insert_green_certification()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('green_certification_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_green_certification_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('green_certification_update');
		$udata['idd'] = $this->input->post('green_certification_idd');
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
			$this->obj->update_green_certification_record($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_heating_cooling()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('heating_cooling_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_heating_cooling_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('heating_cooling_update');
		$udata['idd'] = $this->input->post('heating_cooling_idd');
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
			$this->obj->update_heating_cooling_record($rdata); 
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	public function insert_interior_feature()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('interior_feature_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_interior_feature_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('interior_feature_update');
		$udata['idd'] = $this->input->post('interior_feature_idd');
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
			$this->obj->update_interior_feature_record($rdata);  
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_parking_type()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('parking_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_parking_type_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('parking_update');
		$udata['idd'] = $this->input->post('parking_idd');
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
			$this->obj->update_parking_type_record($rdata);  
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_power_company()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('power_company_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_power_company_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('power_company_update');
		$udata['idd'] = $this->input->post('power_company_idd');
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
			$this->obj->update_power_company_record($rdata);  
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	public function insert_roof_master()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('roof_master_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_roof_master_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('roof_master_update');
		$udata['idd'] = $this->input->post('roof_master_idd');
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
			$this->obj->update_roof_master_record($rdata);   
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	public function insert_sewer_company()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('sewer_company_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_sewer_company_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('sewer_company_update');
		$udata['idd'] = $this->input->post('sewer_company_idd');
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
			$this->obj->update_sewer_company_record($rdata);   
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	public function insert_style_master()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('style_master_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_style_master_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('style_master_update');
		$udata['idd'] = $this->input->post('style_master_idd');
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
			$this->obj->update_style_master_record($rdata);   
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
	
	public function insert_water_company()
    {
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['name'] = $this->input->post('water_company_type');
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_water_company_record($cdata);
		// update already added record
		$udata['update'] = $this->input->post('water_company_update');
		$udata['idd'] = $this->input->post('water_company_idd');
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
			$this->obj->update_water_company_record($rdata);   
		}	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName."/add_record");				
    }
    /*
    @Description: Function for Update contact Profile
    @Author: Mit Makwana
    @Input: - Update details of contact
    @Output: - List with updated contact details
    @Date: 28-06-2014
    */
    public function update_property_list()
    {
		$cdata['id'] = $this->input->post('property_list_id');
		$cdata['name'] = $this->input->post('property_list_type');		
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		
		$this->obj->update_property_list($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
  }
	
	public function update_document_list()
    {
        $cdata['id'] = $this->input->post('document_list_id');
		$cdata['name'] = $this->input->post('document_list_type');
		$cdata['modified_by'] = $this->admin_session['id'];		
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_document_list($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_lot_type()
    {
        $cdata['id'] = $this->input->post('lot_type_id');
		$cdata['name'] = $this->input->post('lot_type');		
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_lot_type($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_transaction()
    {
        $cdata['id'] = $this->input->post('transaction_id');
		$cdata['name'] = $this->input->post('transaction_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_transaction($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_lockbox()
    {
        $cdata['id'] = $this->input->post('lockbox_id');
		$cdata['name'] = $this->input->post('lockbox_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_lockbox($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_sewer()
    {
        $cdata['id'] = $this->input->post('sewer_id');
		$cdata['name'] = $this->input->post('sewer_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_sewer($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_basement()
    {
        $cdata['id'] = $this->input->post('basement_id');
		$cdata['name'] = $this->input->post('basement_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		
		$this->obj->update_basement($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
		
	public function update_architecture()
    {
        $cdata['id'] = $this->input->post('architecture_id');
		$cdata['name'] = $this->input->post('architecture_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_architecture($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_energy_source()
    {
        $cdata['id'] = $this->input->post('energy_source_id');
		$cdata['name'] = $this->input->post('energy_source_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		//pr($cdata);exit;
		$this->obj->update_energy_source($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('admin/'.$this->viewName.'/add_record');
    }
	
	public function update_exterior_finish()
    {
        $cdata['id'] = $this->input->post('exterior_finish_id');
		$cdata['name'] = $this->input->post('exterior_finish_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_exterior_finish($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }

	public function update_fireplace()
    {
        $cdata['id'] = $this->input->post('fireplace_id');
		$cdata['name'] = $this->input->post('fireplace_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_fireplace($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	
	public function update_floor_covering()
    {
        $cdata['id'] = $this->input->post('floor_covering_id');
		$cdata['name'] = $this->input->post('floor_covering_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_floor_covering($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_foundation()
    {
        $cdata['id'] = $this->input->post('foundation_id');
		$cdata['name'] = $this->input->post('foundation_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_foundation($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_green_certification()
    {
        $cdata['id'] = $this->input->post('green_certification_id');
		$cdata['name'] = $this->input->post('green_certification_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_green_certification_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_heating_cooling()
    {
        $cdata['id'] = $this->input->post('heating_cooling_id');
		$cdata['name'] = $this->input->post('heating_cooling_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_heating_cooling_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_interior_feature()
    {
        $cdata['id'] = $this->input->post('interior_feature_id');
		$cdata['name'] = $this->input->post('interior_feature_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_interior_feature_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_parking_type()
    {
        $cdata['id'] = $this->input->post('parking_id');
		$cdata['name'] = $this->input->post('parking_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_parking_type_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_power_company()
    {
        $cdata['id'] = $this->input->post('power_company_id');
		$cdata['name'] = $this->input->post('power_company_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_power_company_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_roof_master()
    {
        $cdata['id'] = $this->input->post('roof_master_id');
		$cdata['name'] = $this->input->post('roof_master_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_roof_master_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_sewer_company()
    {
        $cdata['id'] = $this->input->post('sewer_company_id');
		$cdata['name'] = $this->input->post('sewer_company_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_sewer_company_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_style_master()
    {
        $cdata['id'] = $this->input->post('style_master_id');
		$cdata['name'] = $this->input->post('style_master_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_style_master_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	
	public function update_water_company()
    {
        $cdata['id'] = $this->input->post('water_company_id');
		$cdata['name'] = $this->input->post('water_company_type');	
		$cdata['modified_by'] = $this->admin_session['id'];	
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$this->obj->update_water_company_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
    /*
    @Description: Function for Delete contact Profile By Admin
    @Author: Mit Makwana
    @Input: - Delete id which contact record want to delete
    @Output: - New contact list after record is deleted.
    @Date: 28-06-2014
    */
    function delete_property_list_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_property_list_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_document_list_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_document_list_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_lot_type_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_lot_type_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_transaction_record()
    {
	    $id = $this->uri->segment(4);
        $this->obj->delete_trasaction_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_lockbox_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_lockbox_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_sewer_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_sewer_record($id);
		
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_basement_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_basement_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_architecture_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_architecture_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_energy_source_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_energy_source_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	function delete_exterior_finish_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_exterior_finish_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	function delete_fireplace_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_fireplace_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_floor_covering_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_floor_covering_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_foundation_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_foundation_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_green_certification_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_green_certification_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_heating_cooling_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_heating_cooling_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_interior_feature_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_interior_feature_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_parking_type_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_parking_type_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_power_company_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_power_company_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
	function delete_roof_master_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_roof_master_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
		function delete_sewer_company_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_sewer_company_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
		function delete_style_master_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_style_master_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
		function delete_water_company_record()
    {
		$id = $this->uri->segment(4);
        $this->obj->delete_water_company_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName.'/add_record');
    }
	
}