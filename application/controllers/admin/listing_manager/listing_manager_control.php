<?php 
/*
    @Description: Lead Capturing controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 13-09-2014
	
*/
?>
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class listing_manager_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('lead_capturing_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('listing_manager_model');
		$this->load->model('property_list_masters_model');
		$this->load->model('imageupload_model');
		$this->load->model('contact_masters_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contacts_model');
		$this->load->model('task_model');
		$this->obj = $this->listing_manager_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	

    /*
    @Description: Function for Get All Lead Capturing List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Lead Capturing list
    @Date: 13-09-2014
    */

    public function index()
    {
		//check user right
		check_rights('listing_manager');
		$searchtext='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		$current_date = $this->input->post('new_list');
			
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('property_sortsearchpage_data');
		}
		$data['sortfield']		= 'plm.id';
		$data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('property_sortsearchpage_data');
		
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
				$sortfield = 'plm.id';
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
                           /* $data['searchtext'] = $searchsort_session['searchtext'];
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
		
		if(!empty($current_date))
		{
			$icdata['login_id'] = $this->admin_session['id'];
			$icdata['listing_last_seen'] = date('Y-m-d H:i:s');
			$this->contacts_model->update_last_seen($icdata);
			//$searchtext = $current_date;
			$property_sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
				'searchtext' =>!empty($searchtext)?$searchtext:'',
				'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                                'current_date' => !empty($current_date)?$current_date:'0',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			$this->session->set_userdata('property_sortsearchpage_data', $property_sortsearchpage_data);
			redirect('admin/'.$this->viewName);
		}
		
		$config['base_url'] = site_url($this->user_type.'/'.$this->viewName);
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 3;
		//$uri_segment = $this->uri->segment(3);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
                $data['current_date'] = 0;
                if(!empty($searchsort_session['current_date']) && $searchsort_session['current_date'] != '0000-00-00 00:00:00')
                    $data['current_date'] = $searchsort_session['current_date'];
                
		$fields = array('plm.id,property_title','CONCAT_WS(" ",address_line_1,address_line_2) as address','city,mls_no,property_type_name,price,year_built,new_price,CASE WHEN new_price IS NOT NULL then new_price ELSE price END AS my_price,plm.created_date,CASE lm.user_type WHEN 2 THEN admin_name ELSE CONCAT_WS(" ",um.first_name,um.last_name) END as agent_name','count(DISTINCT plct.id) as contact_counter,plm.created_date');
		$table = 'property_listing_master as plm';
		$join_tables = array(
							 '(SELECT * FROM property_listing_price_change_trans ORDER BY id DESC) as plpct' => 'plpct.property_id = plm.id',
							 'login_master as lm' => 'lm.id = plm.assign_to',
							 'user_master as um' => 'um.id = lm.user_id',
							 'property_listing_contact_trans as plct' => 'plct.property_id = plm.id',
							 );
		$group_by = 'plm.id';
		if(!empty($searchtext))
		{
			//$match = array('CONCAT_WS(" ",address_line_1,address_line_2)'=>$searchtext,'city'=>$searchtext,'mls_no'=>$searchtext,'property_type_name'=>$searchtext,'price'=>$searchtext,'year_built'=>$searchtext);
			
			$having = "address LIKE '%".$searchtext."%' OR city LIKE '%".$searchtext."%' OR mls_no LIKE '%".$searchtext."%' OR property_type_name LIKE '%".$searchtext."%' OR year_built LIKE '%".$searchtext."%' OR my_price LIKE '%".$searchtext."%' OR plm.created_date > '".date('Y-m-d H:i:s',strtotime($searchtext))."'";
			
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,'','','',$having);
			//echo $this->db->last_query();exit;
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','','','','',$group_by,'','','1',$having);
			
				
		}
		else
		{
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by);
			//echo $this->db->last_query();exit;
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,'','','1');
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';
		$property_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                        'current_date' => !empty($data['current_date'])?$data['current_date']:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

		$this->session->set_userdata('property_sortsearchpage_data', $property_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('admin/include/template',$data);
		}
	}

    /*
    @Description: Function Add New Lead Capturing details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function add_record()
    {
		//check user right
		check_rights('listing_manager_add');
		/*$match = array('status'=>1);
		$result=$this->interaction_plans_model->select_records('',$match,'','=');
		$data['plan']=$result;*/
		
		$fields = array('id','name');
		$sortfield = "common.id";
		$sortby = 'desc';
		$table ="property_listing__property_type_master as common";
		$cdata['property_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__transaction_type_master as common";
		$cdata['transaction_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__lot_type_master as common";
		$cdata['lot_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__status_master as common";
		$cdata['status_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		/*$fields = array('id','unit_type','unit_title');
		$where = 'unit_type = 1';
		$table ="property_listing__unit_master as common";
		$cdata['property_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		*/
		$fields = array('id','unit_type','unit_title');
		$match = array('unit_type' => 1);
		$table ="property_listing__unit_master as common";
		$cdata['price_unitdata'] = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'=','','',$sortfield,$sortby);
		$match = array('unit_type' => 2);
		$cdata['area_unitdata'] = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'=','','',$sortfield,$sortby);
		$match = array('unit_type' => 3);
		$cdata['size_unitdata'] = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'=','','',$sortfield,$sortby);
		
		$fields = array('id,plan_name');
		$match = array('status'=>'1');
		$cdata['communication_plans'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','plan_name','asc','interaction_plan_master');

		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'(SELECT * FROM contact_emails_trans where is_default = "1") as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		//for contact tab
		
		$config['per_page'] = 50;
		$config['base_url'] = site_url($this->user_type.'/'."listing_manager/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$cdata['pagination'] = $this->pagination->create_links();
		
		$cdata['user_list'] = $this->task_model->get_admin_users_list();
		$cdata['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $cdata);
    }

    /*
    @Description: Function for Insert New Lead Capturing data
    @Author: Mohit Trivedi
    @Input: - Details of new Lead Capturing which is inserted into DB
    @Output: - List of Lead Capturing with new inserted records
    @Date: 13-09-2014
    */
   
    public function insert_data()
    {
		$cdata['mls_no'] = $this->input->post('mls_no');
		$cdata['property_title'] = $this->input->post('property_title');
		$property_type = $this->input->post('property_type');
		$transaction_type = $this->input->post('transaction_type');
		$lot_type = $this->input->post('lot_type');
		$price_unit = $this->input->post('price_unit');
		$living_area_unit = $this->input->post('living_area_unit');
		$total_area_unit = $this->input->post('total_area_unit');
		$lot_size_unit = $this->input->post('lot_size_unit');
		$expected_commission_unit = $this->input->post('expected_commission_unit');
		$commission_received_unit = $this->input->post('commission_received_unit');
		$status = $this->input->post('status');
		
		$slt_assigned = $this->input->post('slt_assigned');
		
		if(!empty($slt_assigned))
			$cdata['assign_to'] = $slt_assigned;
		else
			$cdata['assign_to'] = $this->admin_session['admin_id'];
		
		if(!empty($property_type))
		{
			$property_type = explode('{^}',$property_type);
			$cdata['property_type'] = $property_type[0];
			$cdata['property_type_name'] = !empty($property_type[1])?$property_type[1]:'';
		}
		if(!empty($transaction_type))
		{
			$transaction_type = explode('{^}',$transaction_type);
			$cdata['transaction_type'] = !empty($transaction_type[0])?$transaction_type[0]:'';
			$cdata['transaction_type_name'] = !empty($transaction_type[1])?$transaction_type[1]:'';
		}
		if(!empty($status))
		{
			$status = explode('{^}',$status);
			$cdata['status_id'] = !empty($status[0])?$status[0]:'';
			$cdata['status_name'] = !empty($status[1])?$status[1]:'';
		}
		if(!empty($lot_type))
		{
			$lot_type = explode('{^}',$lot_type);
			$cdata['lot_type'] = $lot_type[0];
			$cdata['lot_type_name'] = !empty($lot_type[1])?$lot_type[1]:'';
		}
		
		
		if(!empty($price_unit))
		{
			$price_unit = explode('{^}',$price_unit);
			$cdata['price_unit'] = $price_unit[0];
			$cdata['price_name'] = !empty($price_unit[1])?$price_unit[1]:'';
		}
		
		if(!empty($living_area_unit))
		{
			$living_area_unit = explode('{^}',$living_area_unit);
			$cdata['living_area_unit'] = $living_area_unit[0];
			$cdata['living_area_name'] = !empty($living_area_unit[1])?$living_area_unit[1]:'';
		}
		
		if(!empty($total_area_unit))
		{
			$total_area_unit = explode('{^}',$total_area_unit);
			$cdata['total_area_unit'] = $total_area_unit[0];
			$cdata['total_area_name'] = !empty($total_area_unit[1])?$total_area_unit[1]:'';
		}
		
		
		if(!empty($lot_size_unit))
		{
			$lot_size_unit = explode('{^}',$lot_size_unit);
			$cdata['lot_size_unit'] = $lot_size_unit[0];
			$cdata['lot_size_name'] = !empty($lot_size_unit[1])?$lot_size_unit[1]:'';
		}
		
		if(!empty($expected_commission_unit))
		{
			$expected_commission_unit = explode('{^}',$expected_commission_unit);
			$cdata['expected_commission_unit'] = $expected_commission_unit[0];
			$cdata['expected_commission_name'] = !empty($expected_commission_unit[1])?$expected_commission_unit[1]:'';
		}
		if(!empty($commission_received_unit))
		{
			$commission_received_unit = explode('{^}',$commission_received_unit);
			$cdata['commission_received_unit'] = $commission_received_unit[0];
			$cdata['commission_received_name'] = !empty($commission_received_unit[1])?$commission_received_unit[1]:'';
		}
		
		
		$cdata['listed_date'] = date('Y-m-d',strtotime($this->input->post('listed_date')));
		$cdata['listing_expire_date'] = date('Y-m-d',strtotime($this->input->post('listing_expire_date')));
		$cdata['closed_date'] = date('Y-m-d',strtotime($this->input->post('closed_date')));
		$cdata['pending_date'] = date('Y-m-d',strtotime($this->input->post('pending_date')));
		$cdata['seller_name'] = $this->input->post('seller_name');
		$cdata['price'] = $this->input->post('price');
		if(!empty($cdata['price']))
		{
			$cdata['price'] = str_replace('$','',$cdata['price']);
			$cdata['price'] = str_replace(',','',$cdata['price']);
		}
		
		$cdata['year_built'] = $this->input->post('year_built');
		$cdata['taxes'] = $this->input->post('taxes');
		$cdata['tax_id'] = $this->input->post('tax_id');
		$cdata['lot_no'] = $this->input->post('lot_no');
		$cdata['block'] = $this->input->post('block');
		$cdata['building_name'] = $this->input->post('building_name');
		$cdata['remarks'] = $this->input->post('remarks');
		
		$cdata['living_area'] = $this->input->post('living_area');
		//$cdata['living_area_unit'] = $this->input->post('living_area_unit');
		$cdata['total_area'] = $this->input->post('total_area');
		//$cdata['total_area_unit'] = $this->input->post('total_area_unit');
		$cdata['total_unfinished'] = $this->input->post('total_unfinished');
		//$cdata['total_unfinished_unit'] = $this->input->post('total_unfinished_unit');

		$cdata['lot_size'] = $this->input->post('lot_size');
		//$cdata['lot_size_unit'] = $this->input->post('lot_size_unit');
		$cdata['lot_dimension'] = $this->input->post('lot_dimension');
		$cdata['bedrooms_count'] = $this->input->post('bedrooms_count');
		$cdata['bathrooms_count'] = $this->input->post('bathrooms_count');
		$cdata['half_bathrooms_count'] = $this->input->post('half_bathrooms_count');
		
		$cdata['parking_count'] = $this->input->post('parking_count');
		$cdata['kitchen_count'] = $this->input->post('kitchen_count');
		$cdata['floor_count'] = $this->input->post('floor_count');
		
		$cdata['expected_commission'] = $this->input->post('expected_commission');
		//$cdata['expected_commission_unit'] = $this->input->post('expected_commission_unit');
		$cdata['commission_received'] = $this->input->post('commission_received');
		//$cdata['commission_received_unit'] = $this->input->post('commission_received_unit');
		$cdata['interaction_plan_id'] = $this->input->post('interaction_plan_id');
		
		$cdata['address_line_1'] = $this->input->post('address_line_1');
		$cdata['address_line_2'] = $this->input->post('address_line_2');
		$cdata['district'] = $this->input->post('district');
		$cdata['city'] = $this->input->post('city');
		$cdata['state'] = $this->input->post('state');
		$cdata['zip_code'] = $this->input->post('zip_code');
		$cdata['country'] = $this->input->post('country');
		$cdata['latitude'] = $this->input->post('latitude');
		$cdata['longitude'] = $this->input->post('longitude');
		$cdata['slug'] = $this->seoUrl($this->input->post('property_title')); 
		$match = array('slug'=>$cdata['slug']);
        $result = $this->obj->select_records('',$match,'','=');
		
		if(!empty($result) && count($result) > 0)
		{
			$i=1;
			while(count($result) > 0)
			{
				$slug = $cdata['slug'].'-'.$i;
				//$cdata['slug'] = $cdata['slug'].'-'.$i;
				$match = array('slug'=>$slug);
				$result = $this->obj->select_records('',$match,'','=');
				$i++;
			}
			$cdata['slug'] = $slug;
		}
		
		
		//	float: right;margin-right:90px
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['assign_date'] = $cdata['created_date'];
		$cdata['status'] = '1';
		//pr($_POST);
		$lastId = $this->obj->insert_record($cdata);
		
		if(!empty($lastId))
		{
			$addcontactdata = array();
			if($this->input->post('finalcontactlist'))
				$addcontactdata = explode(",",$this->input->post('finalcontactlist'));
			if(count($addcontactdata) > 0)
			{
				//pr($addcontactdata);exit;
				foreach($addcontactdata as $row)
				{
					if(!empty($row))
					{
						$icdata['property_id'] = $lastId;
						$icdata['contact_id'] = $row;
						$icdata['created_date'] = date('Y-m-d H:i:s');
						$icdata['created_by'] = $this->admin_session['id'];
						$icdata['status'] = '1';
						$this->obj->insert_contact_trans_record($icdata);
						//echo $this->db->last_query();exit;
					}
				}
			}
		}
		// Set live link
		$udata['id']=$lastId;
		$udata['live_link']=urlencode(base64_encode($this->db->database)).'--'.urlencode(base64_encode($lastId)).'--'.$cdata['slug'];
		$this->obj->update_record($udata);
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);

        $lead_capturing_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
		
		$redirecttype = $this->input->post('submitbtn');
		$tabid = $this->input->post('tabid');
		if($this->input->post('savebtn'))
			redirect('admin/'.$this->viewName);
		else
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$lastId.'/'.($tabid+1));
		}
		
    }
 
    /*
    @Description: Get Details of Edit Lead Capturing Profile
    @Author: Mohit Trivedi
    @Input: - Id of Lead Capturing whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 13-09-2014
    */
 
    public function edit_record()
    {
		//check user right
		check_rights('listing_manager_edit');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		
		$cdata['editRecord'] = $result;
		$fields = array('CONCAT_WS(",",address_line_1,address_line_2,city,state,zip_code,country) as address');
		$result1 = $this->obj->select_records($fields,$match,'','=');
		$cdata['mapaddress'] = $result1;
		$fields = array('id','name');
		$sortfield = "common.id";
		$sortby = 'desc';
		
		$table ="property_listing__property_type_master as common";
		$cdata['property_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__transaction_type_master as common";
		$cdata['transaction_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__lot_type_master as common";
		$cdata['lot_type_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__sewer_master as common";
		$cdata['sewer_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__lockbox_type_master as common";
		$cdata['lock_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);

		$table ="property_listing__basement_master as common";
		$cdata['basement_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__parking_type_master as common";
		$cdata['parking_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__style_master as common";
		$cdata['style_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__exterior_finish_master as common";
		$cdata['exterior_finish_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__foundation_master as common";
		$cdata['foundation_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__roof_master as common";
		$cdata['roof_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__architecture_master as common";
		$cdata['architecture_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__green_certification_master as common";
		$cdata['green_certification_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__fireplace_master as common";
		$cdata['fireplace_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__energy_source_master as common";
		$cdata['energy_source_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__heating_cooling_master as common";
		$cdata['heating_cooling_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__floor_covering_master as common";
		$cdata['floor_covering_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__interior_feature_master as common";
		$cdata['interior_feature_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__water_company_master as common";
		$cdata['water_company_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__power_company_master as common";
		$cdata['power_company_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__sewer_company_master as common";
		$cdata['sewer_company_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__document_type_master as common";
		$cdata['document_type'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		$table ="property_listing__status_master as common";
		$cdata['status_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		
		
		$match = array('unit_type' => 1);
		$table ="property_listing__unit_master as common";
		$cdata['price_unitdata'] = $this->obj->getmultiple_tables_records($table,'','','','',$match,'=','','',$sortfield,$sortby);

		$match = array('unit_type' => 2);
		$cdata['area_unitdata'] = $this->obj->getmultiple_tables_records($table,'','','','',$match,'=','','',$sortfield,$sortby);
		$match = array('unit_type' => 3);
		$cdata['size_unitdata'] = $this->obj->getmultiple_tables_records($table,'','','','',$match,'=','','',$sortfield,$sortby);
		
		$fields = array('id,plan_name');
		$match = array('status'=>'1');
		$cdata['communication_plans'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','plan_name','asc','interaction_plan_master');
		
		$cdata['document_trans_data'] = $this->obj->select_document_trans_record($id);
		
		$cdata['offers_trans_data'] = $this->obj->select_offers_trans_record($id);
		
		$cdata['price_trans_data'] = $this->obj->select_price_trans_record($id);
    	
		$cdata['houses_trans_data'] = $this->obj->select_houses_trans_record($id);

		$cdata['photos_trans_data'] = $this->obj->select_photos_trans_record($id);

		$cdata['showings_trans_data'] = $this->obj->select_showings_trans_record($id);
		
		$sort_by = 'id';
		$sort_field = 'asc';
		$cdata['photo_link'] = $this->obj->select_photos_trans_record($id,$sort_by,$sort_field);
		
		//$cdata['contacts_data'] = $this->obj->select_old_contact($id);
		
		$table = "property_listing_contact_trans as ct";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('ct.property_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id'
						);
		$group_by='cm.id';
		
		$cdata['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		//pr($cdata['old_contacts_data']);exit;
		
		//for contact tab
		
		$config['per_page'] = 50;
		$config['base_url'] = site_url($this->user_type.'/'."listing_manager/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);

		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'(SELECT * FROM contact_emails_trans where is_default = "1") as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$cdata['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$cdata['pagination'] = $this->pagination->create_links();

		$match = array();
		$cdata['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');

		$cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');

		$cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$match1 = array('status'=>1);
		$result1=$this->interaction_plans_model->select_records('',$match1,'','=');
		$cdata['plan']=$result1;
		$cdata['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		
		$cdata['user_list'] = $this->task_model->get_admin_users_list();
		
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Lead Capturing Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Lead Capturing
    @Output: - List with updated Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function update_data()
    {
		
		//pr($_POST);exit;
		$cdata['id'] = $this->input->post('id');
		$property_id = $this->input->post('id');
		$submitvaltab2 = $this->input->post('submitvaltab2');
		$propertytab = $this->input->post('tabid');
		$tabid=$this->input->post('tabid');
			
		//update Property Info Tab
		if($tabid == 1)
		{
			//New contact in listing management
			$listing_contacts = array();
			$listing_contacts = $this->input->post('finalcontactlist');
			$listing_contacts = explode(",",$listing_contacts);

			// old contact in listing management
			$old_contacts_data = $this->obj->select_old_contact($property_id);
			$contact_old_data = array();

			if(count($old_contacts_data) > 0)
			{
					foreach($old_contacts_data as $row)
					{
						$contact_old_data[] = $row['contact_id'];
					}
				$deletecontactdata = array_diff($contact_old_data,$listing_contacts);
				if(!empty($deletecontactdata))
				{
					$this->obj->delete_contact_trans_record_array($property_id,$deletecontactdata);
				}
			}
			$addcontactdata = array_diff($listing_contacts,$contact_old_data);
			if(count($addcontactdata) > 0)
			{
				foreach($addcontactdata as $row)
				{
					if($row != '')
					{
						$icdata['property_id'] = $property_id;
						$icdata['contact_id'] = $row;
						$icdata['created_date'] = date('Y-m-d H:i:s');
						$icdata['created_by'] = $this->admin_session['id'];
						$icdata['status'] = '1';
						$this->obj->insert_contact_trans_record($icdata);
					}
				}
			}
			
			$cdata['mls_no'] = $this->input->post('mls_no');
			$cdata['property_title'] = $this->input->post('property_title');
			$property_type = $this->input->post('property_type');
			$transaction_type = $this->input->post('transaction_type');
			$lot_type = $this->input->post('lot_type');
			$price_unit = $this->input->post('price_unit');
			$living_area_unit = $this->input->post('living_area_unit');
			$total_area_unit = $this->input->post('total_area_unit');
			$lot_size_unit = $this->input->post('lot_size_unit');
			$expected_commission_unit = $this->input->post('expected_commission_unit');
			$commission_received_unit = $this->input->post('commission_received_unit');
			$status = $this->input->post('status');
			$slt_assigned = $this->input->post('slt_assigned');
			if(!empty($slt_assigned))
				$cdata['assign_to'] = $slt_assigned;
			else
				$cdata['assign_to'] = $this->admin_session['admin_id'];
			
			$match = array('id'=>$cdata['id']);
			$result1 = $this->obj->select_records('',$match,'','=');
			if(!empty($result1[0]['assign_to']) && $result1[0]['assign_to'] != $cdata['assign_to'])
				$cdata['assign_date'] = date('Y-m-d H:i:s');

			if(!empty($property_type))
			{
				$property_type = explode('{^}',$property_type);
				$cdata['property_type'] = $property_type[0];
				$cdata['property_type_name'] = !empty($property_type[1])?$property_type[1]:'';
			}
			else
			{
				$cdata['property_type'] = '';
				$cdata['property_type_name'] = '';
			}
			if(!empty($transaction_type))
			{
				$transaction_type = explode('{^}',$transaction_type);
				$cdata['transaction_type'] = $transaction_type[0];
				$cdata['transaction_type_name'] = !empty($transaction_type[1])?$transaction_type[1]:'';
			}
			else
			{
				$cdata['transaction_type'] = '';
				$cdata['transaction_type_name'] = '';
			}
			if(!empty($status))
			{
				$status = explode('{^}',$status);
				$cdata['status_id'] = $status[0];
				$cdata['status_name'] = !empty($status[1])?$status[1]:'';
			}
			
			if(!empty($lot_type))
			{
				$lot_type = explode('{^}',$lot_type);
				$cdata['lot_type'] = $lot_type[0];
				$cdata['lot_type_name'] = !empty($lot_type[1])?$lot_type[1]:'';
			}
			else
			{
				$cdata['lot_type'] = '';
				$cdata['lot_type_name'] = '';
			}
			
			if(!empty($price_unit))
			{
				$price_unit = explode('{^}',$price_unit);
				$cdata['price_unit'] = $price_unit[0];
				$cdata['price_name'] = !empty($price_unit[1])?$price_unit[1]:'';
			}
			else
			{
				$cdata['price_unit'] = '';
				$cdata['price_name'] = '';
			}
			
			if(!empty($living_area_unit))
			{
				$living_area_unit = explode('{^}',$living_area_unit);
				$cdata['living_area_unit'] = $living_area_unit[0];
				$cdata['living_area_name'] = !empty($living_area_unit[1])?$living_area_unit[1]:'';
			}
			else
			{
				$cdata['living_area_unit'] = '';
				$cdata['living_area_name'] = '';
			}
			
			if(!empty($total_area_unit))
			{
				$total_area_unit = explode('{^}',$total_area_unit);
				$cdata['total_area_unit'] = $total_area_unit[0];
				$cdata['total_area_name'] = !empty($total_area_unit[1])?$total_area_unit[1]:'';
			}
			else
			{
				$cdata['total_area_unit'] = '';
				$cdata['total_area_name'] = '';
			}
			
			
			if(!empty($lot_size_unit))
			{
				$lot_size_unit = explode('{^}',$lot_size_unit);
				$cdata['lot_size_unit'] = $lot_size_unit[0];
				$cdata['lot_size_name'] = !empty($lot_size_unit[1])?$lot_size_unit[1]:'';
			}
			else
			{
				$cdata['lot_size_unit'] = '';
				$cdata['lot_size_name'] = '';
			}
			
			if(!empty($expected_commission_unit))
			{
				$expected_commission_unit = explode('{^}',$expected_commission_unit);
				$cdata['expected_commission_unit'] = $expected_commission_unit[0];
				$cdata['expected_commission_name'] = !empty($expected_commission_unit[1])?$expected_commission_unit[1]:'';
			}
			else
			{
				$cdata['expected_commission_unit'] = '';
				$cdata['expected_commission_name'] = '';
			}
			
			if(!empty($commission_received_unit))
			{
				$commission_received_unit = explode('{^}',$commission_received_unit);
				$cdata['commission_received_unit'] = $commission_received_unit[0];
				$cdata['commission_received_name'] = !empty($commission_received_unit[1])?$commission_received_unit[1]:'';
			}
			else
			{
				$cdata['commission_received_unit'] = '';
				$cdata['commission_received_name'] = '';
			}
			
			
			$change['listed_date'] = $this->input->post('listed_date');
			$cdata['listed_date']=date('Y-m-d',strtotime($change['listed_date']));
			$change['listing_expire_date'] = $this->input->post('listing_expire_date');
			$cdata['listing_expire_date']=date('Y-m-d',strtotime($change['listing_expire_date']));
			$change['closed_date'] = $this->input->post('closed_date');
			$cdata['closed_date']=date('Y-m-d',strtotime($change['closed_date']));
			$change['pending_date'] = $this->input->post('pending_date');
			$cdata['pending_date']=date('Y-m-d',strtotime($change['pending_date']));
			$cdata['seller_name'] = $this->input->post('seller_name');
			$cdata['price'] = $this->input->post('price');
			if(!empty($cdata['price']))
			{
				$cdata['price'] = str_replace('$','',$cdata['price']);
				$cdata['price'] = str_replace(',','',$cdata['price']);
			}
			
			$cdata['year_built'] = $this->input->post('year_built');
			$cdata['taxes'] = $this->input->post('taxes');
			$cdata['tax_id'] = $this->input->post('tax_id');
			$cdata['lot_no'] = $this->input->post('lot_no');
			$cdata['block'] = $this->input->post('block');
			$cdata['building_name'] = $this->input->post('building_name');
			$cdata['remarks'] = $this->input->post('remarks');
			
			$cdata['living_area'] = $this->input->post('living_area');
			//$cdata['living_area_unit'] = $this->input->post('living_area_unit');
			$cdata['total_area'] = $this->input->post('total_area');
			//$cdata['total_area_unit'] = $this->input->post('total_area_unit');
			$cdata['total_unfinished'] = $this->input->post('total_unfinished');
			//$cdata['total_unfinished_unit'] = $this->input->post('total_unfinished_unit');
			$cdata['lot_size'] = $this->input->post('lot_size');
			//$cdata['lot_size_unit'] = $this->input->post('lot_size_unit');
			$cdata['lot_dimension'] = $this->input->post('lot_dimension');
			$cdata['bedrooms_count'] = $this->input->post('bedrooms_count');
			$cdata['bathrooms_count'] = $this->input->post('bathrooms_count');
			$cdata['half_bathrooms_count'] = $this->input->post('half_bathrooms_count');
			
			$cdata['parking_count'] = $this->input->post('parking_count');
			$cdata['kitchen_count'] = $this->input->post('kitchen_count');
			$cdata['floor_count'] = $this->input->post('floor_count');
			
			$cdata['expected_commission'] = $this->input->post('expected_commission');
			//$cdata['expected_commission_unit'] = $this->input->post('expected_commission_unit');
			$cdata['commission_received'] = $this->input->post('commission_received');
			//$cdata['commission_received_unit'] = $this->input->post('commission_received_unit');
			$cdata['interaction_plan_id'] = $this->input->post('interaction_plan_id');
			
			$cdata['address_line_1'] = $this->input->post('address_line_1');
			$cdata['address_line_2'] = $this->input->post('address_line_2');
			$cdata['district'] = $this->input->post('district');
			$cdata['city'] = $this->input->post('city');
			$cdata['state'] = $this->input->post('state');
			$cdata['zip_code'] = $this->input->post('zip_code');
			$cdata['country'] = $this->input->post('country');
			$cdata['latitude'] = $this->input->post('latitude');
			$cdata['longitude'] = $this->input->post('longitude');
			$cdata['slug'] = $this->seoUrl($this->input->post('property_title')); 
			$match = array('slug'=>$cdata['slug']);
			$result = $this->obj->select_records('',$match,'','=');
			//pr($result);exit;
			if(!empty($result) && $result[0]['id'] != $cdata['id'])
			{
				$i=1;
				while(count($result) > 0)
				{
					$slug = $cdata['slug'].'-'.$i;
					$match = array('slug'=>$slug);
					$result = $this->obj->select_records('',$match,'','=');
					$i++;
					if(count($result) == 0 || $result[0]['id'] == $cdata['id'])
						break;
				}
				$cdata['slug'] = $slug;
			}
			
			// Set live link
			$cdata['live_link']=urlencode(base64_encode($this->db->database)).'--'.urlencode(base64_encode($cdata['id'])).'--'.$cdata['slug'];
			$this->obj->update_record($cdata);
		}
		
		elseif($tabid == 2)
		{
			$sewer_id = $this->input->post('sewer_id');
			if(!empty($sewer_id))
			{
				$sewer_id = explode('{^}',$sewer_id);
				$cdata['sewer_id'] = $sewer_id[0];
				$cdata['sewer_name'] = !empty($sewer_id[1])?$sewer_id[1]:'';
			}
			else
			{
				$cdata['sewer_id'] = '';
				$cdata['sewer_name'] = '';
			}
			
			$basement_id = $this->input->post('basement_id');
			if(!empty($basement_id))
			{
				$basement_id = explode('{^}',$basement_id);
				$cdata['basement_id'] = $basement_id[0];
				$cdata['basement_name'] = !empty($basement_id[1])?$basement_id[1]:'';
			}
			else
			{
				$cdata['basement_id'] = '';
				$cdata['basement_name'] = '';
			}
			
			$parking_type_id = $this->input->post('parking_type_id');
			if(!empty($parking_type_id))
			{
				$parking_type_id = explode('{^}',$parking_type_id);
				$cdata['parking_type_id'] = $parking_type_id[0];
				$cdata['parking_type_name'] = !empty($parking_type_id[1])?$parking_type_id[1]:'';
			}
			else
			{
				$cdata['parking_type_id'] = '';
				$cdata['parking_type_name'] = '';
			}
			
			$cdata['parking_spaces'] = $this->input->post('parking_spaces');
			$cdata['builder_name'] = $this->input->post('builder_name');
			$style_id = $this->input->post('style_id');
			if(!empty($style_id))
			{
				$style_id = explode('{^}',$style_id);
				$cdata['style_id'] = $style_id[0];
				$cdata['style_name'] = !empty($style_id[1])?$style_id[1]:'';
			}
			else
			{
				$cdata['style_id'] = '';
				$cdata['style_name'] = '';
			}
			
			$exterior_finish_id = $this->input->post('exterior_finish_id');
			if(!empty($exterior_finish_id))
			{
				$exterior_finish_id = explode('{^}',$exterior_finish_id);
				$cdata['exterior_finish_id'] = $exterior_finish_id[0];
				$cdata['exterior_finish_name'] = !empty($exterior_finish_id[1])?$exterior_finish_id[1]:'';
			}
			else
			{
				$cdata['exterior_finish_id'] = '';
				$cdata['exterior_finish_name'] = '';
			}
			
			$foundation_id = $this->input->post('foundation_id');
			if(!empty($foundation_id))
			{
				$foundation_id = explode('{^}',$foundation_id);
				$cdata['foundation_id'] = $foundation_id[0];
				$cdata['foundation_name'] = !empty($foundation_id[1])?$foundation_id[1]:'';
			}
			else
			{
				$cdata['foundation_id'] = '';
				$cdata['foundation_name'] = '';
			}
			
			$roof_id = $this->input->post('roof_id');
			if(!empty($roof_id))
			{
				$roof_id = explode('{^}',$roof_id);
				$cdata['roof_id'] = $roof_id[0];
				$cdata['roof_name'] = !empty($roof_id[1])?$roof_id[1]:'';
			}
			else
			{
				$cdata['roof_id'] = '';
				$cdata['roof_name'] = '';
			}

			$architecture_id = $this->input->post('architecture_id');
			if(!empty($architecture_id))
			{
				$architecture_id = explode('{^}',$architecture_id);
				$cdata['architecture_id'] = $architecture_id[0];
				$cdata['architecture_name'] = !empty($architecture_id[1])?$architecture_id[1]:'';
			}
			else
			{
				$cdata['architecture_id'] = '';
				$cdata['architecture_name'] = '';
			}
			
			$green_certification_id = $this->input->post('green_certification_id');
			if(!empty($green_certification_id))
			{
				$green_certification_id = explode('{^}',$green_certification_id);
				$cdata['green_certification_id'] = $green_certification_id[0];
				$cdata['green_certification_name'] = !empty($green_certification_id[1])?$green_certification_id[1]:'';
			}
			else
			{
				$cdata['green_certification_id'] = '';
				$cdata['green_certification_name'] = '';
			}
			
			$fireplace_id = $this->input->post('fireplace_id');
			if(!empty($fireplace_id))
			{
				$fireplace_id = explode('{^}',$fireplace_id);
				$cdata['fireplace_id'] = $fireplace_id[0];
				$cdata['fireplace_name'] = !empty($fireplace_id[1])?$fireplace_id[1]:'';
			}
			else
			{
				$cdata['fireplace_id'] = '';
				$cdata['fireplace_name'] = '';
			}
			
			$energy_source_id = $this->input->post('energy_source_id');
			if(!empty($energy_source_id))
			{
				$energy_source_id = explode('{^}',$energy_source_id);
				$cdata['energy_source_id'] = $energy_source_id[0];
				$cdata['energy_source_name'] = !empty($energy_source_id[1])?$energy_source_id[1]:'';
			}
			else
			{
				$cdata['energy_source_id'] = '';
				$cdata['energy_source_name'] = '';
			}
			
			$heating_cooling_id = $this->input->post('heating_cooling_id');
			if(!empty($heating_cooling_id))
			{
				$heating_cooling_id = explode('{^}',$heating_cooling_id);
				$cdata['heating_cooling_id'] = $basement_id[0];
				$cdata['heating_cooling_name'] = !empty($heating_cooling_id[1])?$heating_cooling_id[1]:'';
			}
			else
			{
				$cdata['heating_cooling_id'] = '';
				$cdata['heating_cooling_name'] = '';
			}
			
			$floor_covering_id = $this->input->post('floor_covering_id');
			if(!empty($floor_covering_id))
			{
				$floor_covering_id = explode('{^}',$floor_covering_id);
				$cdata['floor_covering_id'] = $floor_covering_id[0];
				$cdata['floor_covering_name'] = !empty($floor_covering_id[1])?$floor_covering_id[1]:'';
			}
			else
			{
				$cdata['floor_covering_id'] = '';
				$cdata['floor_covering_name'] = '';
			}
			
			$interior_feature_id = $this->input->post('interior_feature_id');
			if(!empty($interior_feature_id))
			{
				$interior_feature_id = explode('{^}',$interior_feature_id);
				$cdata['interior_feature_id'] = $interior_feature_id[0];
				$cdata['interior_feature_name'] = !empty($interior_feature_id[1])?$interior_feature_id[1]:'';
			}
			else
			{
				$cdata['interior_feature_id'] = '';
				$cdata['interior_feature_name'] = '';
			}
			
			$water_company_id = $this->input->post('water_company_id');
			if(!empty($water_company_id))
			{
				$water_company_id = explode('{^}',$water_company_id);
				$cdata['water_company_id'] = $water_company_id[0];
				$cdata['water_company_name'] = !empty($water_company_id[1])?$water_company_id[1]:'';
			}
			else
			{
				$cdata['water_company_id'] = '';
				$cdata['water_company_name'] = '';
			}
			
			$power_company_id = $this->input->post('power_company_id');
			if(!empty($power_company_id))
			{
				$power_company_id = explode('{^}',$power_company_id);
				$cdata['power_company_id'] = $power_company_id[0];
				$cdata['power_company_name'] = !empty($power_company_id[1])?$power_company_id[1]:'';
			}
			else
			{
				$cdata['power_company_id'] = '';
				$cdata['power_company_name'] = '';
			}
			
			$sewer_company_id = $this->input->post('sewer_company_id');
			if(!empty($sewer_company_id))
			{
				$sewer_company_id = explode('{^}',$sewer_company_id);
				$cdata['sewer_company_id'] = $sewer_company_id[0];
				$cdata['sewer_company_name'] = !empty($sewer_company_id[1])?$sewer_company_id[1]:'';
			}
			else
			{
				$cdata['sewer_company_id'] = '';
				$cdata['sewer_company_name'] = '';
			}
			$this->obj->update_record($cdata);
		}
		
		// Update Photos Tab data
		else if($tabid == 3)
		{
			$image = $this->input->post('hiddenFile');
			$oldlistingimg = $this->input->post('listing_pic');//new add
			$bgImgPath = $this->config->item('listing_big_img_path');
			$smallImgPath = $this->config->item('listing_small_img_path');
			if(!empty($_FILES['listing_pic']['name']))
			{  
				$uploadFile = 'listing_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldlistingimg)?$oldlistingimg:'';
				$cddata['photo'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
				$cddata['property_id']=$property_id;
				$cddata['created_date']=date('Y-m-d H:i:s');
				$cddata['created_by']=$this->admin_session['id'];
				$cddata['status']='1';
				$this->obj->insert_photos_trans_record($cddata);

			}
		}
		
		
		// Update Document Tab data
		else if($tabid == 4)
		{
			$image = $this->input->post('hiddenFile');
			$oldcontactimg = $this->input->post('contact_pic');//new add
			$bgImgPath = $this->config->item('listing_big_img_path');
			$smallImgPath = $this->config->item('listing_small_img_path');

			if(!empty($_FILES['contact_pic']['name']))
			{  
				$uploadFile = 'listing_pic';
				$thumb = "thumb";
				$hiddenImage = !empty($oldcontactimg)?$oldcontactimg:'';
				$cdata['contact_pic'] = $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
			}
			
			$cddata['id']= $this->input->post('doc_id');
			$cddata['property_id'] = $this->input->post('id');
			$cddata['document_type_id'] = $this->input->post('slt_doc_type');
			$cddata['doc_name'] = $this->input->post('txt_doc_name');
			$cddata['doc_desc'] = $this->input->post('txtarea_doc_desc');
			$cddata['doc_file'] = $this->input->post('hiddenFiledoc');
			$cddata['status'] = '1';
			
			if(trim($cddata['doc_type']) != '' || trim($cddata['doc_name']) != '' || trim($cddata['doc_desc']) != '' || trim($cddata['doc_file']) != '')
			{
				if($this->input->post('doc_id') == '')
				{
					$cddata['created_date'] = date('Y-m-d H:i:s');
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['created_by']=$this->admin_session['id'];
					$this->obj->insert_doc_trans_record($cddata);
				}
				else
				{
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['modified_by']=$this->admin_session['id'];
					$this->obj->update_doc_trans_record($cddata);
				}
					
				unset($cddata);
			}
				
		}

		// Update LockBox Tab
		elseif($tabid == 5)
		{
			$lockbox_type_id = $this->input->post('lockbox_type_id');
			if(!empty($lockbox_type_id))
			{
				$lockbox_type_id = explode('{^}',$lockbox_type_id);
				$cdata['lockbox_type_id'] = $lockbox_type_id[0];
				$cdata['lockbox_type_name'] = !empty($lockbox_type_id[1])?$lockbox_type_id[1]:'';
			}
			$cdata['lockbox_serial'] = $this->input->post('lockbox_serial');
			$cdata['lockbox_location_on_property'] = $this->input->post('lockbox_location_on_property');
			$cdata['lockbox_combination'] = $this->input->post('lockbox_combination');
			$cdata['lockbox_notes'] = $this->input->post('txtarea_lockbox_notes');
			$this->obj->update_record($cdata);
		}
		
		// Update Offers Tab data
		
		else if($tabid == 6)
		{
			$cddata['id']= $this->input->post('offers_id');
			$cddata['property_id'] = $this->input->post('id');
			$cddata['offer_price'] = $this->input->post('txt_offer_price');
			if(!empty($cddata['offer_price']))
			{
				$cddata['offer_price'] = str_replace('$','',$cddata['offer_price']);
				$cddata['offer_price'] = str_replace(',','',$cddata['offer_price']);
			}
			$cddata['offer_price_unit_id'] = $this->input->post('slt_price_unit');
			$offer_price_unit_id = $this->input->post('slt_price_unit');
			$change['offer_date'] = $this->input->post('txt_offer_date');
			$cddata['offer_agent_name'] = $this->input->post('txt_offer_agent');
			$cddata['offer_phone'] = $this->input->post('txt_offer_phone');
			$cddata['offer_notes'] = $this->input->post('txtarea_offer_notes');
			$cddata['status'] = '1';
			$fields1 = array('id','unit_type','unit_title');
			$where1 = array('id'=>$offer_price_unit_id);
			$table1 ="property_listing__unit_master as common";
			$cdata = $this->obj->getmultiple_tables_records($table1,$fields1,'','','','','=','','','','','','',$where1);
			$cddata['offer_price_unit'] = $cdata[0]['unit_title'];
			
			if(trim($cddata['offer_price']) != '' || trim($change['offer_date']) != '' || trim($cddata['offer_agent_name']) != '' || trim($cddata['offer_phone']) != '' || trim($cddata['offer_notes']) != '')
			{
				if(!empty($change['offer_date']))
					$cddata['offer_date'] = date('Y-m-d',strtotime($change['offer_date']));
				if($this->input->post('offers_id') == '')
				{
					$cddata['created_date'] = date('Y-m-d H:i:s');
					$cddata['created_by']=$this->admin_session['id'];
					$this->obj->insert_offers_trans_record($cddata);
				}
				else
				{
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['modified_by']=$this->admin_session['id'];
					$this->obj->update_offers_trans_record($cddata);
				}
					
				unset($cddata);
			}
				
		}
	
	// Update price Tab data
		else if($tabid == 7)
		{
			$cddata['id']= $this->input->post('price_change_id');
			$cddata['property_id'] = $this->input->post('id');
			$cddata['new_price'] = $this->input->post('txt_new_price');
			if(!empty($cddata['new_price']))
			{
				$cddata['new_price'] = str_replace('$','',$cddata['new_price']);
				$cddata['new_price'] = str_replace(',','',$cddata['new_price']);
			}
			$cddata['new_price_unit_id'] = $this->input->post('slt_new_price_unit_id');
			$new_price_unit_id = $this->input->post('slt_new_price_unit_id');
			$change['price_change_date'] = $this->input->post('txt_price_date');
			$cddata['price_notes'] = $this->input->post('txtarea_price_notes');
			$cddata['status'] = '1';
			$fields1 = array('id','unit_type','unit_title');
			$where1 = array('id'=>$new_price_unit_id);
			$table1 ="property_listing__unit_master as common";
			$cdata = $this->obj->getmultiple_tables_records($table1,$fields1,'','','','','=','','','','','','',$where1);
			$cddata['new_price_unit'] = $cdata[0]['unit_title'];
			
			if(trim($cddata['new_price']) != '' || trim($change['price_change_date']) != ''  || trim($cddata['price_notes']) != '')
			{
				if(!empty($change['price_change_date']))
					$cddata['price_change_date']=date('Y-m-d',strtotime($change['price_change_date']));
				if($this->input->post('price_change_id') == '')
				{
					$cddata['created_date'] = date('Y-m-d H:i:s');
					$cddata['created_by']=$this->admin_session['id'];
					$this->obj->insert_price_trans_record($cddata);
				}
				else
				{
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['modified_by']=$this->admin_session['id'];
					$this->obj->update_price_trans_record($cddata);
				}
					
				unset($cddata);
			}
				
		}

	// Update Open Houses Tab data
		else if($tabid == 8)
		{
			$cddata['id']= $this->input->post('Open_houses_id');
			$cddata['property_id'] = $this->input->post('id');
			$change['open_house_date'] = $this->input->post('txt_houses_date');
			$change['open_house_time'] = $this->input->post('txt_houses_time');
			$change['open_house_end_time'] = $this->input->post('txt_houses_end_time');
			$cddata['open_house_notes'] = $this->input->post('txtarea_houses_notes');
			$cddata['status'] = '1';
			
			if(trim($change['open_house_date']) != '')
			{
				$cddata['open_house_date']=date('Y-m-d',strtotime($change['open_house_date']));
				if(trim($change['open_house_time']) != '')
					$cddata['open_house_time']=date('H:i:s',strtotime($change['open_house_time']));
				
				if(trim($change['open_house_end_time']) != '')
					$cddata['open_house_end_time']=date('H:i:s',strtotime($change['open_house_end_time']));
					
				//pr($cddata);exit;
					
				if($this->input->post('Open_houses_id') == '')
				{
					$cddata['created_date'] = date('Y-m-d H:i:s');
					$cddata['created_by']=$this->admin_session['id'];
					$this->obj->insert_houses_trans_record($cddata);
				}
				else
				{
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['modified_by']=$this->admin_session['id'];
					$this->obj->update_houses_trans_record($cddata);
				}
					
				unset($cddata);
			}
				
		}

	// Update Open showings Tab data
		else if($tabid == 9)
		{
			$cddata['id']= $this->input->post('showings_id');
			$cddata['property_id'] = $this->input->post('id');
			$change['showings_date'] = $this->input->post('txt_showings_date');
			$change['showings_time'] = $this->input->post('txt_showings_time');
			$cddata['showings_agent_name'] = $this->input->post('txt_showings_agentname');
			$cddata['showings_agent_id'] = $this->input->post('txt_showings_agentid');
			$cddata['showings_agent_phone'] = $this->input->post('txt_showings_agentphone');
			$cddata['showings_agent_email'] = $this->input->post('txt_showings_agentemail');
			$cddata['showings_agent_office'] = $this->input->post('txt_showings_agentoffice');
			$cddata['showings_notes'] = $this->input->post('txtarea_showings_notes');
			$cddata['status'] = '1';
			
			if(trim($change['showings_date']) != '' || trim($change['showings_time']) != ''  || trim($cddata['showings_agent_name']) != '' || trim($cddata['showings_agent_id']) != '' || trim($cddata['showings_agent_phone']) != '' || trim($cddata['showings_agent_email']) != '' || trim($cddata['showings_agent_office']) != '' || trim($cddata['showings_notes']) != '')
			{
				$cddata['showings_date']=date('Y-m-d',strtotime($change['showings_date']));
				if(trim($change['showings_time']) != '')
					$cddata['showings_time']=date("H:i",strtotime($change['showings_time']));
				if($this->input->post('showings_id') == '')
				{
					$cddata['created_date'] = date('Y-m-d H:i:s');
					$cddata['created_by']=$this->admin_session['id'];
					$this->obj->insert_showings_trans_record($cddata);
				}
				else
				{
					$cddata['modified_date'] = date('Y-m-d H:i:s');
					$cddata['modified_by']=$this->admin_session['id'];
					$this->obj->update_showings_trans_record($cddata);
				}
					
				unset($cddata);
			}
				
		}
		
	// Update Contacts Tab data
		/*else if($tabid == 10)
		{
			//New contact in listing management
			$listing_contacts = array();
			$listing_contacts = $this->input->post('finalcontactlist');
			$listing_contacts = explode(",",$listing_contacts);

			// old contact in listing management
			$old_contacts_data = $this->obj->select_old_contact($property_id);
			$contact_old_data = array();

			if(count($old_contacts_data) > 0)
			{
					foreach($old_contacts_data as $row)
					{
						$contact_old_data[] = $row['contact_id'];
					}
				$deletecontactdata = array_diff($contact_old_data,$listing_contacts);
				if(!empty($deletecontactdata))
				{
					$this->obj->delete_contact_trans_record_array($property_id,$deletecontactdata);
				}
			}
			$addcontactdata = array_diff($listing_contacts,$contact_old_data);
			if(count($addcontactdata) > 0)
			{
				foreach($addcontactdata as $row)
				{
					if($row != '')
					{
						$icdata['property_id'] = $property_id;
						$icdata['contact_id'] = $row;
						$icdata['created_date'] = date('Y-m-d H:i:s');
						$icdata['created_by'] = $this->admin_session['id'];
						$icdata['status'] = '1';
						$this->obj->insert_contact_trans_record($icdata);
					}
				}
			}
		}*/
		//update public visibility Tab
		else if($tabid == 11)
		{
			$cddata['id'] = $this->input->post('id');
			$property_id = $this->input->post('id');
			//$cddata['live_link']=$this->input->post('live_link');
			$cddata['is_visible_to_public']=$this->input->post('is_visible_to_public');
			$cddata['google_analytics_code']=$this->input->post('google_analytics_code');
			$cddata['modified_by']=$this->admin_session['id'];
			$cddata['modified_by']=date('Y-m-d H:i:s');
			$cddata['status']='1';
			$this->obj->update_record($cddata);
		}
		$redirecttype = $this->input->post('submitbtn');
		
		$searchsort_session = $this->session->userdata('property_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		
		if($redirecttype == 'Save and Add More Document')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#documenets');		
		}

		else if($redirecttype == 'Save and Add More Photos')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#photo');		
		}

		else if($redirecttype == 'Save and Add More Open Houses')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#Open_houses');		
		}


		else if($redirecttype == 'Save and Add More Showings')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#showings');		
		}

		else if($redirecttype == 'Save and Add More Price')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#price_change');		
		}
		
		else if($redirecttype == 'Save and Add More Offers')
		{
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($submitvaltab2).'#Offers');		
		}
		
		else if($this->input->post('savebtn'))
		{
			$msg = $this->lang->line('common_edit_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName.'/'.$pagingid);
		}
		
		else
		{
			if($tabid == 9)
				$tabid = $tabid + 1;
			redirect('admin/'.$this->viewName.'/edit_record/'.$property_id.'/'.($tabid+1));
		}

    }
	
   /*
    @Description: Function for Delete Lead Capturing Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete id which Lead Capturing record want to delete
    @Output: - New Lead Capturing list after record is deleted.
    @Date: 13-09-2014
    */

    function delete_record()
    {
		//check user right
		check_rights('listing_manager_delete');
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete Lead Capturing Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete all id of Lead Capturing record want to delete
    @Output: - Lead Capturing list Empty after record is deleted.
    @Date: 19-09-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_record($array_data[$i]);
		}
                $searchsort_session = $this->session->userdata('lead_capturing_sortsearchpage_data');
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
		//echo 1;
	}

    /*
    @Description: Function Add New Lead Capturing details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function view_record()
    {
		//check user right
		check_rights('listing_manager');
		$id = $this->uri->segment(4);
		$match = array('form_id'=>$id);
		$result=$this->obj->select_records1('',$match,'','=');
		$data['formdata']=$result;
		$match1 = array('id'=>$id);
		$result1=$this->obj->select_records('',$match1,'','=');
		$data['form']=$result1;
		$data['main_content'] = "admin/".$this->viewName."/view";
        $this->load->view('admin/include/template', $data);
    }

    /*
    @Description: Function View Form data datails details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - View Form data datails
    @Date: 16-09-2014
    */
   
    public function view_embed_data()
    {
		$id = $this->input->post('id');
		$match = array('id'=>$id);
		$result=$this->obj->select_records('',$match,'','=');
		$data['formdata']=$result;
    }

    /*
    @Description: Function View Form data datails details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - View Form data datails
    @Date: 16-09-2014
    */
   
    public function view_form_data()
    {
		$id = $this->input->post('id');
		$match = array('id'=>$id);
		$result=$this->obj->select_records('',$match,'','=');
		$data['viewdata']=$result;
		$this->load->view($this->user_type.'/'.$this->viewName."/form_data",$data);
    }

	/*
    @Description: Function for to upload document
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 29-10-2014
    */
	function upload_document()
	{
			$uploadFile = 'uploadfile';
			$bgImgPath = $this->config->item('listing_documents_img_path');
			$doc_name= $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,'','','');
			$my_img_array['document_name'] = $doc_name;
			echo json_encode($my_img_array);
	}
	
	/*
    @Description: Function for Update listing Profile Tab4 Ajax
    @Author: Mohit Trivedi
    @Input: - Update details of Listing management
    @Output: - List with updated listing manager details
    @Date: 29-10-2014
    */
	
	function update_data_ajax()
	{
		$cdata['id'] = $this->input->post('id');
		$cddata['id']= $this->input->post('doc_id');
		$cddata['property_id'] = $this->input->post('id');
		$cddata['doc_type'] = $this->input->post('slt_doc_type');
		$cddata['doc_name'] = $this->input->post('txt_doc_name');
		$cddata['doc_desc'] = $this->input->post('txtarea_doc_desc');
		$cddata['doc_file'] = $this->input->post('hiddenFiledoc');
		$cddata['modified_date'] = date('Y-m-d H:i:s');
		$cddata['status'] 	= '1';
		
		if(trim($cddata['doc_type']) != '' || trim($cddata['doc_name']) != '' || trim($cddata['doc_desc']) != '' || trim($cddata['doc_file']) != '')
		{
			if($this->input->post('doc_id') == '')
			{
				$cddata['created_date'] = date('Y-m-d H:i:s');
				$this->obj->insert_doc_trans_record($cddata);
			}
			else
				$this->obj->update_doc_trans_record($cddata);
				
			unset($cddata);
		}
		
		$data['document_trans_data'] = $this->obj->select_document_trans_record($this->input->post('id'));
		$this->load->view($this->user_type.'/'.$this->viewName."/listing_document_ajax",$data);
		
	}

	/*
    @Description: Function to get document data
    @Author: Mohit Trivedi
    @Input: Document Id
    @Output: - 
    @Date: 30-10-2014
    */
	function get_doc_trans_data()
	{
		$id = $this->input->post('id');
		$result = $this->obj->select_document_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}

	function get_offers_trans_data()
	{
		
		$id = $this->input->post('id');
		$result = $this->obj->select_offers_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}

	function get_price_trans_data()
	{
		
		$id = $this->input->post('id');
		$result = $this->obj->select_price_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}

	function get_houses_trans_data()
	{
		
		$id = $this->input->post('id');
		$result = $this->obj->select_houses_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}

	function get_showings_trans_data()
	{
		
		$id = $this->input->post('id');
		$result = $this->obj->select_showings_trans_record_ajax($id);
		if(isset($result->id))
			echo json_encode($result);
		else
			echo "error";
	}
	
	function delete_document_trans_record()
    {
        $id = $this->uri->segment(4);
		
		$result = $this->obj->select_document_trans_record_ajax($id);
		$this->obj->delete_document_trans_record($id);
		if(!empty($result->doc_file))
		{
			$image = $result->doc_file;
			$bgImgPath = $this->config->item('listing_documents_img_path');
			$bgImgPathUpload = $this->config->item('upload_image_file_path').'listing_docs/';
			if(file_exists($bgImgPathUpload.$image))
			{ 
				@unlink($bgImgPath.$image);
			}
		}
    }
	function delete_offers_trans_record()
    {
        
		$id = $this->uri->segment(4);
		$result = $this->obj->select_offers_trans_record_ajax($id);
		$this->obj->delete_offers_trans_record($id);
    }

	function delete_price_trans_record()
    {
        
		$id = $this->uri->segment(4);
		$result = $this->obj->select_price_trans_record_ajax($id);
		$this->obj->delete_price_trans_record($id);
    }

	function delete_houses_trans_record()
    {
        
		$id = $this->uri->segment(4);
		$result = $this->obj->select_houses_trans_record_ajax($id);
		$this->obj->delete_houses_trans_record($id);
    }

	function delete_showings_trans_record()
    {
        
		$id = $this->uri->segment(4);
		$result = $this->obj->select_showings_trans_record_ajax($id);
		$this->obj->delete_showings_trans_record($id);
    }
	
	/*
    @Description: Function for delete image 
    @Author: Mohit Trivedi
    @Input: - Delete id 
    @Output: - image deleted
    @Date: 03-11-2014
    */
	public function delete_image()
	{
		$id=$this->input->post('id');
		$name=$this->input->post('name');
		$table='property_listing_photo_trans';
		$match = array('id'=>$id);
		$result = $this->obj->getmultiple_tables_records($table,'','','','',$match,'=','','','','');
		if($result[0]['photo']==$name)
		{
			$image=$result[0]['photo'];
		}
		$bgImgPath = $this->config->item('listing_big_img_path');
		$smallImgPath = $this->config->item('listing_small_img_path');
		$bgImgPathUpload = $this->config->item('upload_image_file_path').'listing/big/';
		$smallImgPathUpload = $this->config->item('upload_image_file_path').'listing/small/';
		if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
		{ 
			@unlink($bgImgPath.$image);
			@unlink($smallImgPath.$image);
		}
		$this->obj->delete_photos($id);
		echo 'done';
	}
	
	/*
		@Description: Function for get selected contact list(TO)
		@Author: Sanjay Chabhadiya
		@Input: - Contact ID
		@Output: - Contact list
		@Date: 03-11-2014
   	*/
	
	public function add_contacts_to_listing_manager()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	
	/*
		@Description: Function for search contact(TO)
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact list
		@Date: 03-11-2014
   	*/
	
	public function search_contact_ajax()
    {	
		$config['per_page'] = 50;
		$config['base_url'] = site_url($this->user_type.'/'."listing_manager/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; 
        $config['paging_function'] = 'ajax_paging'; 
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$perpage = $this->input->post('perpage');
		if(!empty($perpage))
			$config['per_page'] = $perpage;
		$data['perpage'] = $config['per_page'];
		$where= array();
		if(!empty($contact_status) && !empty($contact_source))
			$where= array('cm.contact_status'=>$contact_status,'cm.contact_source'=>$contact_source);
		elseif(!empty($contact_status))
			$where= array('cm.contact_status'=>$contact_status);
		elseif(!empty($contact_source))
			$where = array('cm.contact_source'=>$contact_source);
		if(!empty($contact_type))
		{
			$contact_type_array = array('cct.contact_type_id'=>$contact_type);
			$where = array_merge($where,$contact_type_array);
		}
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'(SELECT * FROM contact_emails_trans where is_default = "1") as cet'=>'cet.contact_id = cm.id',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$search_tag = $this->input->post('search_tag');
		if(!empty($search_tag))
		{
			$tag = explode(",",$search_tag);
			if(!empty($tag))
			{
				for($i=0;$i<count($tag);$i++)
				{
					$tag_array = array();
					$tag_array = array('(select * from contact_tag_trans where tag = "'.$tag[$i].'") as ctat'.$i.' jointype direct'=>'ctat'.$i.'.contact_id = cm.id');
					$join_tables = array_merge($join_tables,$tag_array);
				}
			}
		}
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	/*
    @Description: Function for Delete contacts from Listing Manager Ajax
    @Author:  Mohit Trivedi
    @Input: - Delete details of contacts
    @Output: - 
    @Date: 03-11-2014
    */
	
	function delete_contact_from_listing_manager()
	{
		$contact_id = $this->input->post('contact_id');
		$property_id =$this->input->post('property_id');
		
		if(!empty($property_id) && !empty($contact_id))
		{
			$this->obj->delete_contact_trans_record_indi($property_id,$contact_id);
			
			////////////// Delete Contacts Interaction Plan-Interaction Transaction Data /////////////////
			
			$match = array('property_id'=>$property_id);
			$interaction = $this->obj2->select_records('',$match,'','=');
			
			$deletecon = $contact_id;
			
			/* END */
			if(!empty($deletecon))
			{
				$data_conv['contact_id'] = $deletecon;
				$data_conv['property_id'] = $property_id;
				$data_conv['created_date'] = date('Y-m-d H:i:s');
				$data_conv['created_by'] = $this->admin_session['id'];
				$data_conv['status'] = '1';
				$this->obj->insert_contact_converaction_trans_record($data_conv);
			}
			
		}
		
	}
	
	/*
    @Description: Function for Getting latitude and longitude
    @Author:  Mohit Trivedi
    @Input: - Delete address
    @Output: - 
    @Date: 04-11-2014
    */
	function getLatLong() {
	  if ($this->input->is_ajax_request()) 
	  {
	    $address = $this->input->post('address');

	    if (!is_string($address)) die("All Addresses must be passed as a string");
	    $_url       = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
	    $_result    = false;
	    if ($_result = file_get_contents($_url))
	    {
	      if (strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
	      preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
	      $_coords['lat']  = $_match[1];
	      $_coords['long'] = $_match[2];
		  $_coords['msg']  = "OK";
	    }
	    echo json_encode($_coords); 
	  }
	  else 
	  {
		$_coords['msg']  = "Access Denied";
		echo json_encode($_coords); 
	  }
	}
	
	/*
		@Description: Function for Update the active theme
		@Author: Sanjay Chabhadiya
		@Input: - theme id
		@Output: - 
		@Date: 03-11-2014
   	*/
	
	public function active_theme()
	{
		$data['id'] = $this->input->post('property_id');
		$data['property_selected_theme'] = $this->input->post('theme_id');
		if(!empty($data['id']))
			$this->obj->update_record($data);
		echo 1;
	}
	
	/*
		@Description: Function for Insert all property listing master table
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 03-11-2014
   	*/
	
	public function property_listing_master()
	{
		$data['name'] = $this->input->post('txt_name');
		$data['created_by'] = $this->admin_session['id'];
		$data['created_date'] = date('Y-m-d H:i:s');
		$data['status'] = '1';
		
		$fields = array('id','name');
		$sortfield = "id";
		$sortby = 'desc';
		$property_type_name = $this->input->post('property_type_name');
		$cdata['exist'] = 0;
		$wherestring = 'name = "'.$data['name'].'"';
		if(!empty($property_type_name) && $property_type_name == 'property_type')
			$table = "property_listing__property_type_master";
		elseif(!empty($property_type_name) && $property_type_name == 'lot_type')
			$table ="property_listing__lot_type_master";
		elseif(!empty($property_type_name) && $property_type_name == 'transaction_type')
			$table ="property_listing__transaction_type_master";
		elseif(!empty($property_type_name) && $property_type_name == 'sewer_id')
			$table ="property_listing__sewer_master";
		elseif(!empty($property_type_name) && $property_type_name == 'basement_id')
			$table ="property_listing__basement_master";
		elseif(!empty($property_type_name) && $property_type_name == 'parking_type_id')
			$table ="property_listing__parking_type_master";		
		elseif(!empty($property_type_name) && $property_type_name == 'style_id')
			$table ="property_listing__style_master";
		elseif(!empty($property_type_name) && $property_type_name == 'exterior_finish_id')
			$table ="property_listing__exterior_finish_master";
		elseif(!empty($property_type_name) && $property_type_name == 'foundation_id')
			$table ="property_listing__foundation_master";
		elseif(!empty($property_type_name) && $property_type_name == 'roof_id')
			$table ="property_listing__roof_master";
		elseif(!empty($property_type_name) && $property_type_name == 'architecture_id')
			$table ="property_listing__architecture_master";
		elseif(!empty($property_type_name) && $property_type_name == 'green_certification_id')
			$table ="property_listing__green_certification_master";
		elseif(!empty($property_type_name) && $property_type_name == 'fireplace_id')
			$table ="property_listing__fireplace_master";
		elseif(!empty($property_type_name) && $property_type_name == 'energy_source_id')
			$table ="property_listing__energy_source_master";
		elseif(!empty($property_type_name) && $property_type_name == 'heating_cooling_id')
			$table ="property_listing__heating_cooling_master";
		elseif(!empty($property_type_name) && $property_type_name == 'floor_covering_id')
			$table ="property_listing__floor_covering_master";
		elseif(!empty($property_type_name) && $property_type_name == 'interior_feature_id')
			$table ="property_listing__interior_feature_master";
		elseif(!empty($property_type_name) && $property_type_name == 'water_company_id')
			$table ="property_listing__water_company_master";
		elseif(!empty($property_type_name) && $property_type_name == 'power_company_id')
			$table ="property_listing__power_company_master";
		elseif(!empty($property_type_name) && $property_type_name == 'sewer_company_id')
			$table ="property_listing__sewer_company_master";
		elseif(!empty($property_type_name) && $property_type_name == 'power_company_id')
			$table ="property_listing__power_company_master";
		elseif(!empty($property_type_name) && $property_type_name == 'lockbox_type_id')
			$table ="property_listing__lockbox_type_master";
		elseif(!empty($property_type_name) && $property_type_name == 'slt_doc_type')
			$table ="property_listing__document_type_master";
		elseif(!empty($property_type_name) && $property_type_name == 'status')
			$table ="property_listing__status_master";
		if(!empty($data['name']))
		{
			$property_listing_exist = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$wherestring);
			//pr($property_listing_exist);
			if(count($property_listing_exist) > 0)
				$cdata['exist'] = $property_listing_exist[0]['id'];
			else
				$this->obj->property_listing_master_insert($data,$table);
		}
		$cdata['property_listing_master'] = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','',$sortfield,$sortby);
		echo json_encode($cdata);
		
	}
	
	/*
		@Description: Function for create slug
		@Author: Sanjay Chabhadiya
		@Input: - theme id
		@Output: - 
		@Date: 03-11-2014
   	*/
	
	public function seoUrl($string) 
	{
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
		
	/*
		@Description: Function for get assign contact list
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: - 
		@Date: 08-01-2015
    */
	
	public function view_contacts()
	{
		$id = $this->input->post('id');
		
		$table = "property_listing_contact_trans as plct";
		$fields = array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$where = array('plct.property_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = plct.contact_id',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id'
						);
		$group_by='cm.id';
		$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
		//pr($data['contact_list']);
		//echo $this->db->last_query();//exit;
		
		$this->load->view($this->user_type.'/'.$this->viewName."/view_contact_popup",$data);
	}
	
	/*
		@Description: Function to view Import CSV File Add
		@Author: Sanjay Chahadiya
		@Input: 
		@Output: - 
		@Date: 08-01-2015
    */
	
	function import()
	{
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/import";
		$this->load->view('admin/include/template',$data);
	}
	
	/*
		@Description: Function to Import the CSV file and insert property
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: - 
		@Date: 08-01-2015
    */
	
	function insert_contact_csv()
	{
		ini_set('max_execution_time', 3000);
		error_reporting(0);
	?>
		<style>
			body{ position:relative;}
		</style>
        <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src="<?=base_url('images/loading.gif')?>" /><br />Please wait... It will take some time to import contacts...</div>
        
    <?php
	
		$file_handle = fopen($_FILES['csvfile']['tmp_name'], "r");
		echo $_FILES['csvfile']['name'];
		$i=0;
		while(!feof($file_handle)) 
		{
			$line_of_text = fgetcsv($file_handle, 100000000);
			if(!empty($line_of_text) && $i != 0 && !empty($line_of_text[1]))
			{
				$cdata['mls_no'] = $line_of_text[0];
				$cdata['property_title'] = $line_of_text[1];
				if(!empty($line_of_text[2]))
				{
					$data['name'] = $line_of_text[2];
					$data['created_by'] = $this->admin_session['id'];
					$data['created_date'] = date('Y-m-d H:i:s');
					$data['status'] = '1';
					$wherestring = 'name = "'.$line_of_text[2].'"';
					$table = "property_listing__property_type_master";
					$property_listing_exist = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$wherestring);
					if(count($property_listing_exist) > 0)
						$last_property_type = $property_listing_exist[0]['id'];
					else
						$last_property_type = $this->obj->property_listing_master_insert($data,$table);	
					unset($data);
					$cdata['property_type'] = $last_property_type;
					$cdata['property_type_name'] = $line_of_text[2];
				}
				
				$cdata['price'] = $line_of_text[3];
				if(!empty($cdata['price']))
				{
					$cdata['price'] = str_replace('$','',$cdata['price']);
					$cdata['price'] = str_replace(',','',$cdata['price']);
				}
				
				$cdata['address_line_1'] = $line_of_text[4];
				$cdata['address_line_2'] = $line_of_text[5];
				$cdata['district'] = $line_of_text[6];
				$cdata['city'] = $line_of_text[7];
				$cdata['state'] = $line_of_text[8];
				$cdata['zip_code'] = $line_of_text[9];
				$cdata['country'] = $line_of_text[10];
				
				$cdata['slug'] = $line_of_text[11];
				/*$cdata['slug'] = $this->seoUrl($line_of_text[1]); 
				$match = array('slug'=>$cdata['slug']);
				$result = $this->obj->select_records('',$match,'','=');
				
				if(!empty($result) && count($result) > 0)
				{
					$j=1;
					while(count($result) > 0)
					{
						$slug = $cdata['slug'].'-'.$j;
						//$cdata['slug'] = $cdata['slug'].'-'.$i;
						$match = array('slug'=>$slug);
						$result = $this->obj->select_records('',$match,'','=');
						$j++;
					}
					$cdata['slug'] = $slug;
				}*/
				$cdata['assign_to'] = $this->admin_session['admin_id'];
				$cdata['created_by'] = $this->admin_session['id'];
				$cdata['created_date'] = date('Y-m-d H:i:s');
				$cdata['status'] = '1';
				$lastId = $this->obj->insert_record($cdata);
				unset($cdata);
				
			}
			$i++;
		}
		redirect('admin/'.$this->viewName);
	}
}