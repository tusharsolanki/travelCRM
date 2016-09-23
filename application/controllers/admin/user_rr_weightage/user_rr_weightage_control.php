<?php 
/*
    @Description: contacts controller
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_rr_weightage_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        
        $result = check_joomla_tab_setting($this->admin_session['id']);
        if(!empty($result) && $result[0]['lead_dashboard_tab'] == '0')
            redirect('admin/dashboard');
		$this->load->model('user_management_model');
		$this->load->model('Common_function_model');
		
		$this->obj = $this->user_management_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	
    /*
        @Description: Function for Get All agent weightage
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : Agent Weightage list
        @Date       : 30-10-2014
    */
    public function index()
    {	
        //check user right
		$modules_lists = $this->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(!in_array('lead_distribution_agent',$modules_lists) || !in_array('lead_distribution_lender',$modules_lists))
			{show_404();}
		}
		$selected_view_session = $this->session->userdata('rr_selected_view_session');
        if(!empty($selected_view_session['selected_view']))
            $selected_view = $selected_view_session['selected_view'];
        else
            $selected_view = '1';
        $data['tabid'] = $selected_view;
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('agent_rr_weightage_sortsearchpage_data');
        }
        $data['sortfield']		= 'id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('agent_rr_weightage_sortsearchpage_data');

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
                    $searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
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
        $config['base_url'] = site_url($this->user_type.'/'."user_rr_weightage/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 3;
        $uri_segment = $this->uri->segment(3);
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
       
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,um.min_area,um.max_area,lm.email_id,lm.agent_type,group_concat(DISTINCT cwdm.domain_name separator \',\') as domain_name');
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id',
            'user_domain_trans as udt' => 'um.id = udt.user_id',
            'child_website_domain_master as cwdm' => 'cwdm.id = udt.domain_id');
        $where = array('um.status'=> '1','um.user_type'=>'3');
        $or_where = "(lm.agent_type='Inside Sales Agent' OR lm.agent_type='Buyer\'s Agent')";
        if(!empty($searchtext))
        {
            $match=array('CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name)'=>$searchtext,'CONCAT_WS(" ",um.first_name,um.last_name)'=>$searchtext,'um.user_weightage'=>$searchtext,'um.minimum_price'=>$searchtext,'um.maximum_price'=>$searchtext,'lm.agent_type'=>$searchtext,'lm.email_id'=>$searchtext);
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where,'',$or_where);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'',$or_where,'1');
        }
        else
        {
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where,'',$or_where);
            //echo $this->db->last_query();
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'',$or_where,'1');
        }
        
        //pr($data['datalist']);exit;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $agent_rr_weightage_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('agent_rr_weightage_sortsearchpage_data', $agent_rr_weightage_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        
        /////// Lender Listing 05-01-2015 ///////
        $searchtext1='';$perpage1='';
        $searchtext1 = mysql_real_escape_string($this->input->post('searchtext1'));
        $sortfield1 = $this->input->post('sortfield1');
        $sortby1 = $this->input->post('sortby1');
        $perpage1 = trim($this->input->post('perpage1'));
        $allflag1 = $this->input->post('allflag1');

        if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
            $this->session->unset_userdata('lender_rr_weightage_sortsearchpage_data');
        }
        $data['sortfield1'] = 'id';
        $data['sortby1']	= 'desc';
        $searchsort_session1 = $this->session->userdata('lender_rr_weightage_sortsearchpage_data');

        if(!empty($sortfield1) && !empty($sortby1))
        {
            $data['sortfield1'] = $sortfield1;
            $data['sortby1'] = $sortby1;
        }
        else
        {
            if(!empty($searchsort_session1['sortfield'])) {
                if(!empty($searchsort_session1['sortby'])) {
                    $data['sortfield1'] = $searchsort_session1['sortfield'];
                    $data['sortby1'] = $searchsort_session1['sortby'];
                    $sortfield1 = $searchsort_session1['sortfield'];
                    $sortby1 = $searchsort_session1['sortby'];

                }
            } else {
                $sortfield1 = 'id';
                $sortby1 = 'desc';
            }
        }
        if(!empty($searchtext1))
        {
            $data['searchtext1'] = stripslashes($searchtext1);
        } else {
            if(empty($allflag1))
            {
                if(!empty($searchsort_session1['searchtext'])) {
                   /* $data['searchtext1'] = $searchsort_session1['searchtext'];
                    $searchtext1 =  $data['searchtext1'];*/
					
					$searchtext1 =  mysql_real_escape_string($searchsort_session1['searchtext']);
	     			$data['searchtext1'] = $searchsort_session1['searchtext'];

                }
            }
        }
        if(!empty($perpage1) && $perpage1 != 'null')
        {
            $data['perpage1'] = $perpage1;
            $config1['per_page'] = $perpage1;	
        }
        else
        {
            if(!empty($searchsort_session1['perpage'])) {
                $data['perpage1'] = trim($searchsort_session1['perpage']);
                $config1['per_page'] = trim($searchsort_session1['perpage']);
            } else {
                $config1['per_page'] = '10';
            }
        }
        $config1['base_url'] = site_url($this->user_type.'/'."user_rr_weightage/");
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config1['uri_segment'] = 3;
        if(!empty($allflag1) && ($allflag1 == 'all' || $allflag1 == 'changesorting' || $allflag1 == 'changesearch')) {
            $config1['uri_segment'] = 0;
            $uri_segment1 = 0;
        } else {
            $config1['uri_segment'] = 3;
            $uri_segment1 = $this->uri->segment(3);
        }
        
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,um.min_area,um.max_area,lm.email_id,group_concat(DISTINCT cwdm.domain_name separator \',\') as domain_name');
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id',
            'user_domain_trans as udt' => 'um.id = udt.user_id',
            'child_website_domain_master as cwdm' => 'cwdm.id = udt.domain_id');
        $where = array('um.status'=> '1','um.user_type'=>'3','lm.agent_type'=>'Lender');
        if(!empty($searchtext1))
        {
            $match=array('CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name)'=>$searchtext1,'CONCAT_WS(" ",um.first_name,um.last_name)'=>$searchtext1,'um.user_weightage'=>$searchtext1,'um.minimum_price'=>$searchtext1,'um.maximum_price'=>$searchtext1,'lm.email_id'=>$searchtext1);
            $data['lender_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config1['per_page'], $uri_segment1,$data['sortfield'],$data['sortby1'],$group_by,$where);
            $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','','1');
        }
        else
        {
            $data['lender_datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config1['per_page'], $uri_segment1,$data['sortfield1'],$data['sortby1'],$group_by,$where);
            $config1['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','','1');
        }
        
        //pr($data['datalist']);exit;
        $this->pagination->initialize($config1);
        $data['pagination1'] = $this->pagination->create_links();

        $lender_rr_weightage_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
			'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
			'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
			'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
			'uri_segment' => !empty($uri_segment1)?$uri_segment1:'0',
			'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');

        $this->session->set_userdata('lender_rr_weightage_sortsearchpage_data', $lender_rr_weightage_sortsearchpage_data);
        $data['uri_segment1'] = $uri_segment1;
        /////// End Lender ///////
        
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
        }
        else if($this->input->post('result_type') == 'ajax1')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/lender_ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
            $this->load->view('admin/include/template',$data);
        }
    }
    
    /*
    @Description: Function for Get All contacts List
    @Author: Nishit Modi
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
    */
    public function index_old()
    {	
        $match = array('user_type'=>3,'status'=>1);
        $result = $this->user_management_model->select_records('',$match,'','=');
        $data['datalist'] = $result;
        $config['total_rows'] = $this->user_management_model->select_records('',$match,'','=','','','','','','','1');

        /*$table = "user_master as um";
        $fields = array('um.*','cm.*','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address','cpt.phone_no','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address');

        $join_tables = array(
                                                'login_master as cm jointype direct'=>'cm.id = uct.contact_id',
                                                );
        $group_by='uct.contact_id';
        $where=array('uct.user_id'=>$id);
        $cdata['assign_contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$per_page,$uri_segment,$sortfield,$sortby,$group_by,$where);

                echo $this->db->last_query();exit;
		$this->session->set_userdata('user_mgt_sortsearchpage_data', $user_mgt_sortsearchpage_data);*/
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/add";
            $this->load->view('admin/include/template',$data);
    }

    /*
    @Description: Function Add New contacts details
    @Author: Nishit Modi
    @Input: - 
    @Output: - Load Form for add contacts details
    @Date: 04-07-2014
    */
    public function add_record()
    {
	/*
        $match = array('user_type'=>3,'status'=>1);
        $result = $this->user_management_model->select_records('',$match,'','=');
        $data['datalist'] = $result;
        $config['total_rows'] = count($this->user_management_model->select_records('',$match,'','='));
        */
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,um.min_area,um.max_area,lm.email_id');
        $where = array('um.status'=> '1','um.user_type'=>'3');
        
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','id','desc',$group_by,$where);
        $data['edit_single'] = '0';
        $data['main_content'] =  $this->user_type.'/'.$this->viewName."/add";
        $this->load->view('admin/include/template',$data);	
    }

    /*
    @Description: Function for insert/update agent weightage
    @Author     : Sanjay Moghariya
    @Input      : Agent weightage data
    @Output     : List of agent weightage
    @Date       : 31-10-2014
    */
    public function update_data()
    {
        $is_edit_single = $this->input->post('is_edit_single');
        if(!empty($is_edit_single))
        {
            $data = array();
            $user_id = $this->input->post('suser_id');
            $weightage = $this->input->post('sweightage');
            $min_price = $this->input->post('smin_price');  // Remove in Phase1
            $max_price = $this->input->post('smax_price');
            $data['minimum_price'] = str_replace(',', '', $min_price);
            $data['maximum_price'] = str_replace(',', '', $max_price);
            
            /*$min_area = $this->input->post('smin_area');
            $max_area = $this->input->post('smax_area');
            $data['min_area'] = str_replace(',', '', $min_area);
            $data['max_area'] = str_replace(',', '', $max_area);
            */
            $data['id'] = $user_id;
            $data['user_weightage'] = $weightage;
            $this->obj->update_record($data);
            
            
            //// Domain ////
            $assigned_domain = $this->input->post('assigned_domain');
            
            $fields = array('domain_id');
            $match = array('user_id'=>$user_id);
            $table ='user_domain_trans';
            $olddomain = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match,'=');

            $olddomainlist = array();
            if(count($olddomain) > 0)
            {
                foreach($olddomain as $row)
                {
                    $olddomainlist[] = $row['domain_id'];
                }
            }
            
            $olddomainarr = array_diff($olddomainlist,$assigned_domain);
            $newdomainarr = array_diff($assigned_domain,$olddomainlist);

            if(isset($assigned_domain) && empty($assigned_domain))
                $olddomainarr = $olddomainlist;

            ///////////// Delete Domain Transaction Data ///////////
            if(!empty($olddomainarr))
            {
                foreach($olddomainarr as $rowdata)
                {
                    $del_data['domain_id'] = $rowdata;
                    $del_data['user_id'] = $user_id;
                    $this->obj->delete_domain_record($del_data,'user_domain_trans');
                }
            }
            
            /////////// Insert Domain Transaction Data /////////
            if(!empty($newdomainarr))
            {
                foreach($newdomainarr as $row)
                {
                    if(!empty($row))
                    {
                        $pdata['domain_id'] = $row;
                        $pdata['user_id'] = $user_id;
                        $this->obj->insert_domain_record($pdata,'user_domain_trans');
                    }
                }
            }
            //// End Domain ////
            
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            
            $selected_view_session = $this->session->userdata('selected_view_session');
            if($selected_view_session['selected_view'] == '2')
                $searchsort_session = $this->session->userdata('lender_rr_weightage_sortsearchpage_data');
            else
                $searchsort_session = $this->session->userdata('agent_rr_weightage_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
        }
        else
        {
            $data = array();
            $user_id = $this->input->post('user_id');
            $weightage = $this->input->post('weightage');
            $min_price = $this->input->post('min_price'); // Remove in Phase 1
            $max_price = $this->input->post('max_price');


            $data['modified_by'] = $this->admin_session['id'];
            $data['modified_date'] = date('Y-m-d H:i:s');

            if(!empty($user_id))
            {
                for($i=0;$i<count($user_id);$i++)
                {
                    $data['id'] = $user_id[$i];
                    $data['user_weightage'] = $weightage[$i];
                    $data['minimum_price'] = str_replace(',', '', $min_price[$i]); // Remove in Phase 1
                    $data['maximum_price'] = str_replace(',', '', $max_price[$i]);
                    $this->obj->update_record($data);
                }
            }

            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);	
            $agent_rr_weightage_sortsearchpage_data = array(
                'sortfield'  => 'id',
                'sortby' => 'desc',
                'searchtext' =>'',
                'perpage' => '',
                'uri_segment' => 0);
            $this->session->set_userdata('agent_rr_weightage_sortsearchpage_data', $agent_rr_weightage_sortsearchpage_data);
            redirect('admin/'.$this->viewName);
        }
    }
    
    /*
        @Description: Get edit form
        @Author     : Sanjay Moghariya
        @Input      : user weightage id
        @Output     : weightage details
        @Date       : 19-11-2014
    */
    public function edit_record()
    {
		$modules_lists = $this->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(!in_array('lead_distribution_agent_edit',$modules_lists) || !in_array('lead_distribution_lender_edit',$modules_lists))
			{show_404();}
		}
     	$id = $this->uri->segment(4);
        
        //// Get assigned domain list (Admin) ////
        $table = "child_website_domain_master";
        $fields = array('id,domain_name');
        $wherestr = 'website_status = 1';
        $cdata['domain_data'] =$this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','id','desc','','','','','',$wherestr);
        //// End Get assigned domain list (Admin)////
        
        //// Get assigned domain list (User) ////
        $table = "user_domain_trans";
        $fields = array('domain_id');
        $where = array('user_id'=> $id);
        $assigned_domain_id =$this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','id','desc','',$where);
        $domain_id = array();
        if(!empty($assigned_domain_id))
        {
            foreach($assigned_domain_id as $row)
            {
                $domain_id[] = $row['domain_id'];
            }
        }
        $cdata['assigned_domain_id'] = $domain_id;
        //// End Get assigned domain list (User)////
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,um.min_area,um.max_area,lm.email_id');
        $where = array('um.status'=> '1','um.user_type'=>'3','um.id'=>$id);
        
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $cdata['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','id','desc',$group_by,$where);
        $cdata['edit_single'] = '1';
        $cdata['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$cdata);
    }

    /*
    @Description: Function for Delete contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 04-07-2014
    */
    function delete_record()
    {
		$modules_lists = $this->modules_unique_name;
		if(!empty($modules_lists))
		{
			if(!in_array('lead_distribution_agent_delete',$modules_lists) || !in_array('lead_distribution_lender_delete',$modules_lists))
			{show_404();}
		}
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	function delete_assign_record()
    {
		$user_id =$this->uri->segment(4);
		
        $id = $this->input->post('id');
		//$pagingid = $this->obj->getemailpagingid2($user_id);
		
		$this->obj->delete_user_contact_trans_record($id);
                
                $searchsort_session = $this->session->userdata('user_assigned_contact_sortsearchpage_data');
                if(!empty($searchsort_session['uri_segment']))
                    $pagingid = $searchsort_session['uri_segment'];
                else
                    $pagingid = 0;

                $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'5';
                $total_rows = $searchsort_session['total_rows'];
                if($total_rows % $perpage == 1)
                    $pagingid -= $perpage;
                
                if($pagingid < 0)
                    $pagingid = 0;
		echo $pagingid;
		
	   
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	/*
    @Description: Functions for deleting various transactions data
    @Author: Nishit Modi
    @Input: - Transaction id
    @Output: - 
    @Date: 11-07-2014
    */
	
	function delete_email_trans_record()
    {
	
        $id = $this->uri->segment(4);
        $this->obj->delete_email_trans_record($id);
    }
	
	function delete_phone_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_phone_trans_record($id);
    }
	
	function delete_address_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_address_trans_record($id);
    }
	
	function delete_website_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_website_trans_record($id);
    }
	
	function delete_social_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_social_trans_record($id);
    }
	
	function delete_tag_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_tag_trans_record($id);
    }
	
	function delete_communication_trans_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_communication_trans_record($id);
    }
	
	function delete_document_trans_record()
    {
        $id = $this->uri->segment(4);
		
		$result = $this->obj->select_document_trans_record_ajax($id);
		$this->obj->delete_document_trans_record($id);
		if(!empty($result->doc_file))
		{
			$image = $result->doc_file;
			$bgImgPath = $this->config->item('contact_documents_img_path');
			$bgImgPathUpload = $this->config->item('upload_image_file_path').'contact_docs/';
			if(file_exists($bgImgPathUpload.$image))
			{ 
				@unlink($bgImgPath.$image);
			}
		}
    }
	
	 /*
    @Description: Function for Unpublish contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to Unpublish
    @Output: - New contacts list after record is Unpublish.
    @Date: 04-07-2014
    */
    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = '0';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to publish
    @Output: - New contacts list after record is publish.
    @Date: 04-07-2014
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
		redirect('admin/'.$this->viewName);
        //redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
    }
	
	/*
    @Description: Function for to upload image
    @Author: Nishit Modi
    @Input: - 
    @Output: - 
    @Date: 10-07-2014
    */
	function upload_image()
	{
		$image=$this->input->post('image');
		$hiddenImage=$this->input->post('image');
		echo $image." ".$hiddenImage;
		$uploadFile = 'uploadfile';
		$bgImgPath = $this->config->item('temp_big_img_path');
		$smallImgPath = $this->config->item('temp_small_img_path');
		$thumb = "thumb";
		$hiddenImage = '';
		echo $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,$smallImgPath,$thumb,$hiddenImage);
	}
	
	/*
    @Description: Function for delete image 
    @Author: Nishit Modi
    @Input: - Delete id 
    @Output: - image deleted
    @Date: 10-07-2014
    */
	public function delete_image()
	{
		$id=$this->input->post('id');
		$name=$this->input->post('name');
		$fields = array("id,$name");
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$bgImgPath = $this->config->item('contact_big_img_path');
		$smallImgPath = $this->config->item('contact_small_img_path');
		$image=$result[0][$name];
		
		$bgImgPathUpload = $this->config->item('upload_image_file_path').'contact/big/';
		$smallImgPathUpload = $this->config->item('upload_image_file_path').'contact/small/';
		if(file_exists($bgImgPathUpload.$image) || file_exists($smallImgPathUpload.$image))
		{ 
		
			@unlink($bgImgPath.$image);
			@unlink($smallImgPath.$image);
		}
		$cdata['id'] = $id;
		$cdata[$name] = '';
		$this->obj->update_record($cdata);
		echo 'done';
	}
	
	/*
    @Description: Function for to upload document
    @Author: Nishit Modi
    @Input: - 
    @Output: - 
    @Date: 11-07-2014
    */
	function upload_document()
	{
			$uploadFile = 'uploadfile';
			$bgImgPath = $this->config->item('contact_documents_img_path');
			$doc_name= $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,'','','');
			$my_img_array['document_name'] = $doc_name;
			echo json_encode($my_img_array);
	}
	
	/*
    @Description: Function for to upload CSV File
    @Author: Kaushik Valiya
    @Input: - 
    @Output: - 
    @Date: 11-07-2014
    */
	function upload_csv()
	{
			$uploadFile = 'uploadfile';
			$bgImgPath = $this->config->item('contact_documents_csv_path');
			$doc_name= $this->imageupload_model->uploadBigImage($uploadFile,$bgImgPath,'','','');
			$my_img_array['document_name'] = $doc_name;
			echo json_encode($my_img_array);
	}
	
	
	/*
    @Description: Function to get document data
    @Author: Nishit Modi
    @Input: Document Id
    @Output: - 
    @Date: 11-07-2014
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
	
	/*
    @Description: Function to view merge page
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 11-07-2014
    */
	function merge_duplicate_contacts()
	{
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/merge_contact_home";
		$this->load->view('admin/include/template',$data);
	}
	
	/*
    @Description: Function to view Import CSV File Add
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 11-07-2014
    */
	function import()
	{
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/import";
		$this->load->view('admin/include/template',$data);
	}
	
	 /*
    @Description: Function  use for Delete last import contact CSV File in contacts By Admin
    @Author: Kaushik Valiya
    @Input: - Delete id which contacts record last import CSV File in all contact
    @Output: - Remove all contact and Relation table data.
    @Date: 12-07-2014
    */
    function delete_last_import()
    {
        $id = $this->uri->segment(4);
		$match = array('csv_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		for($i=0;$i<count($result); $i++)
		{
			$contact_id=$result[$i]['id'];
			$this->obj->delete_table_trans_record($contact_id,'contact_emails_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_phone_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_contacttype_trans');
			$this->obj->delete_table_trans_record($contact_id,'contact_address_trans');
			$this->obj->delete_record($contact_id);
		}
		$this->obj->delete_record($result[$i]['id']);
      	$msg = $this->lang->line('common_delete_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	function merge_search_contacts()
	{
		$fields = $this->input->post('slt_fields');
		
		if(!empty($fields) && count($fields) > 0)
		{
			//$data['datalistcounter'] = $this->obj->merge_search_contacts_counter($fields);
			
			$data = $this->obj->merge_search_contacts($fields);
			
			$data['fields_data'] = $fields;
			
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/merge_contact_list";
			
			$this->load->view('admin/include/template',$data);
		}
		else
		{
			redirect('admin/'.$this->viewName.'/merge_duplicate_contacts');
		}
	}
	
	/*
    @Description: Get Details of Edit contacts Profile
    @Author: Nishit Modi
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 04-07-2014
    */
    public function view_record()
    {
      
		$id = $this->uri->segment(4);
		 
		//$match = array("created_by"=>$this->admin_session['id']);
		//$data['all']=$this->obj1->select_records1('',$match,'','=');
        $data['email_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__email_type_master');
		$data['website_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__websitetype_master');
		$data['phone_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__phone_type_master');
		$data['address_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__address_type_master');
		$data['status_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['profile_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__social_type_master');
		$data['contact_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		$data['document_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__document_type_master');
		$data['source_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$data['disposition_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc', 'contact__disposition_master');
		$data['user_type'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','user__user_type_master');
		//Get communication plan data
		$data['communication_plans'] = '';
		$data['email_trans_data'] = $this->obj->select_email_trans_record($id);
		$data['phone_trans_data'] = $this->obj->select_phone_trans_record($id);
		$data['address_trans_data'] = $this->obj->select_address_trans_record($id);
		//$data['website_trans_data'] = $this->obj->select_website_trans_record($id);
		$table = "user_website_trans as cwt";
		$fields = array('cwt.id','cwt.website_name','cwm.name');
		$join_tables = array(
								'contact__websitetype_master as cwm' => 'cwm.id = cwt.website_type'
							);
		$group_by='cwt.id';
		$where=array('user_id'=>$id);
		$data['website_trans_data'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);

		$data['profile_trans_data'] = $this->obj->select_social_trans_record($id);
		$data['contact_trans_data'] = $this->obj2->select_contact_type_record($id);
		$data['tag_trans_data'] = $this->obj2->select_tag_record($id);
		$data['communication_trans_data'] = $this->obj2->select_communication_trans_record($id);
		$data['document_trans_data'] = $this->obj2->select_document_trans_record($id);

        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		$data['main_content'] = "admin/".$this->viewName."/view"; 
		      
	   	$this->load->view("admin/include/template",$data);
    }
	/*
    @Description: Get Details mapping Field Name
    @Author: Kaushik valiya
    @Input: - onchange event to get mapping Field
    @Output: - List Of Mapping Field
    
    */
	public function get_filed_list()
    { 
			$csv_mapping_id=$this->input->post('mapping_id');
			$match = array("csv_mapping_id"=>$csv_mapping_id);
        	$field_list = $this->obj1->select_records1('',$match,'','=','','','','','asc','contact__csv_mapping_trans');
			echo json_encode($field_list);
				
	}
	
	
	/*
    @Description: This Function check_user already Existing.
    @Author: Kaushik  Valiya
    @Input: Email To check
    @Output: Yes or No
    @Date: 20-08-2014
	
	*/
	public function check_user()
	{
		$email=$this->input->post('email');
		
		//$email_login_id = $this->input->post('txt_email_id');
		// Server Side Email Validation
		$regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
		if (preg_match($regex, $email)) 
		{
			$email1 = strtolower($email);
			$match=array('email_id'=>$email1);
			$exist_email= $this->obj1->select_records1('',$match,'','=','','','','email_id','asc','login_master');
			
			if(!empty($exist_email))
			{
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
		else
		{
			echo '2';
		}
       
	}
        
    /*
        @Description: Function for set selected view session for user assignment (Agent / Lender)
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 05-01-2015
    */
    public function selectedview_session()
    {
        $selected_view = $this->input->post('selected_view');
        
        $sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        
        if($selected_view == '2')
            $this->session->set_userdata('lender_rr_weightage_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('agent_rr_weightage_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('rr_selected_view_session',$data);
    }
}