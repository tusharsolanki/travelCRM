<?php 
/*
    @Description: Joomla property alert cron settings
    @Author     : Sanjay Moghariya
    @Input      : 
    @Output     : 
    @Date       : 18-11-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class joomla_property_cron_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        //check user right
		check_rights('market_watch');
        $result = check_joomla_tab_setting($this->admin_session['admin_id']);
        if(!empty($result) && $result[0]['market_watch_tab'] == '0')
            redirect('admin/dashboard');
        $this->load->model('joomla_property_cron_model');
        $this->load->model('contact_type_master_model');
        $this->load->model('contact_masters_model');
        $this->load->model('contacts_model');
        //$this->load->model('joomla_property_cron_trans');
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'admin';
    }
	
    /*
        @Description: Function for get all joomla property cron setting list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all joomla property cron list
        @Date       : 18-11-2014
    */
    public function index()
    {	
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('joomla_property_cron_sortsearchpage_data');
        }
        $data['sortfield']		= 'id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('joomla_property_cron_sortsearchpage_data');

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
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/");
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

        $where = array('data_from'=>1);
        if(!empty($searchtext))
        {
            //$match=array('name'=>$searchtext,'country'=>$searchtext,'state'=>$searchtext,'city'=>$searchtext,'neighborhood'=>$searchtext,'cron_type'=>$searchtext);
            $match=array('name'=>$searchtext,'city'=>$searchtext,'zip_code'=>$searchtext,'neighborhood'=>$searchtext,'cron_type'=>$searchtext);
            $data['datalist'] = $this->joomla_property_cron_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config['total_rows'] = $this->joomla_property_cron_model->select_records('',$match,'','like','','','','','',$where,'1');
        }
        else
        {
            $data['datalist'] = $this->joomla_property_cron_model->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            //echo $this->db->last_query();exit;
            $config['total_rows']= $this->joomla_property_cron_model->select_records('','','','','','','','','',$where,'1');
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $joomla_property_cron_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
            'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
            'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
            'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

        $this->session->set_userdata('joomla_property_cron_sortsearchpage_data', $joomla_property_cron_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        
        $data['tabid'] = '1';
        $sel_view = $this->session->userdata('selected_property_cron_view_session');
        //pr($sel_view);exit;
        if(!empty($sel_view['selected_view']) && $sel_view['selected_view'] == '2')
            $data['tabid'] = '2';
        // For CRM tab//
        $config1['per_page'] = '10';
        $config1['base_url'] = site_url($this->user_type.'/joomla_property_cron/'."property_cron_crm_index/");
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config1['uri_segment'] = 0;
        $uri_segment1 = 0;

        $where = array('data_from'=>2);
        $data['result_crm'] = $this->joomla_property_cron_model->select_records('','','','','',$config1['per_page'],$uri_segment1,'id','desc',$where);
        $config1['total_rows']= $this->joomla_property_cron_model->select_records('','','','','','','','','',$where,'1');

        $this->pagination->initialize($config1);
        $data['pagination1'] = $this->pagination->create_links();
        // End For CRM tab//
        
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
        @Description: Function for get all joomla property cron setting list from CRM
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all joomla property cron list
        @Date       : 22-06-2015
    */
    public function property_cron_crm_index()
    {	
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('joomla_property_cron_crm_sortsearchpage_data');
        }
        $data['sortfield1'] = 'id';
        $data['sortby1'] = 'desc';
        $searchsort_session = $this->session->userdata('joomla_property_cron_crm_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield1'] = $sortfield;
            $data['sortby1'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield'])) {
                if(!empty($searchsort_session['sortby'])) {
                    $data['sortfield1'] = $searchsort_session['sortfield'];
                    $data['sortby1'] = $searchsort_session['sortby'];
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
            $data['searchtext1'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext1'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage1'] = $perpage;
            $config1['per_page'] = $perpage;	
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage1'] = trim($searchsort_session['perpage']);
                $config1['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config1['per_page'] = '10';
            }
        }
        $config1['base_url'] = site_url($this->user_type.'/joomla_property_cron/'."property_cron_crm_index/");
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
        
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config1['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config1['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }

        $where = array('data_from'=>2);
        if(!empty($searchtext))
        {
            $match=array('name'=>$searchtext,'city'=>$searchtext,'zip_code'=>$searchtext,'neighborhood'=>$searchtext,'cron_type'=>$searchtext);
            $data['result_crm'] = $this->joomla_property_cron_model->select_records('',$match,'','like','',$config1['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config1['total_rows'] = $this->joomla_property_cron_model->select_records('',$match,'','like','','','','','',$where,'1');
        }
        else
        {
            $data['result_crm'] = $this->joomla_property_cron_model->select_records('','','','','',$config1['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config1['total_rows']= $this->joomla_property_cron_model->select_records('','','','','','','','','',$where,'1');
        }
        $this->pagination->initialize($config1);
        $data['pagination1'] = $this->pagination->create_links();
        $data['msg1'] = $this->message_session['msg'];
        $data['tabid'] = '2';

        $joomla_property_cron_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield1'])?$data['sortfield1']:'',
            'sortby' =>!empty($data['sortby1'])?$data['sortby1']:'',
            'searchtext' =>!empty($data['searchtext1'])?$data['searchtext1']:'',
            'perpage' => !empty($data['perpage1'])?trim($data['perpage1']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config1['total_rows'])?$config1['total_rows']:'0');

        $this->session->set_userdata('joomla_property_cron_crm_sortsearchpage_data', $joomla_property_cron_sortsearchpage_data);
        $data['uri_segment1'] = $uri_segment;
        
        if($this->input->post('result_type') == 'ajax1')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_crm',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
            $this->load->view('admin/include/template',$data);
        }
    }
    /*
        @Description: Function Add New property cron details
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     : Load Form for add details
        @Date       : 18-11-2014
    */
    public function add_record()
    {
        //$data['userlist'] = $this->joomla_property_cron_model->get_admin_users_list();
        /*$table = "contact_master as cm";
        $group_by = "cm.id";
        $wherestring = array('cm.created_type'=>'6');
        //$wherestring = '';
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as user_name','cet.email_address as email_id');
        $join_tables = array(
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
        );
        $data['userlist'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','','','cm.first_name','asc',$group_by,$wherestring);
        */
        
        //// NEW 17-12-2014
        $config['per_page'] = 50;	
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 4;
        $uri_segment = $this->uri->segment(4);
        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
        $join_tables = array(
            'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
        );
        $group_by='cm.id';
        $wherestring = array('cm.created_type'=>'6');
        $data['sortfield'] = 'cm.first_name';
        $data['sortby'] = 'asc';
        $data['contact_list'] =$this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
        $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $match = array();
        $data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
        //pr($data['contact_type']);
        $data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
        $data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');

        $data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
        
        /// END NEW 17-12-2014
        
        //// 01-01-2015
        // City Data
        /*$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?city=alldata";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $response = curl_exec($ch);
        curl_close($ch);
        $city_response = (json_decode($response, true));
        $data['city_response'] = $city_response;
        
        // Neighbor Data
        $url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?name=alldata";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $response = curl_exec($ch);
        curl_close($ch);
        $addr_response = (json_decode($response, true));
        $data['addr_response'] = $addr_response;
        
        // Zipcode data
        $url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?zipcode=alldata";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $response = curl_exec($ch);
        curl_close($ch);
        $zipcode_response = (json_decode($response, true));
        $data['zipcode_response'] = $zipcode_response;
        */
        
        /// END 01-01-2015

        //pr($data['userlist']);exit;
        
        $sdata = array('selected_view' => 1);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        $data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
    
    /*
        @Description: Function Add New property cron details
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     : Load Form for add details
        @Date       : 15-06-2015
    */
    public function add_record1()
    {
        $config['per_page'] = 50;	
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 4;
        $uri_segment = $this->uri->segment(4);
        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
        $join_tables = array(
            'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
        );
        $group_by='cm.id';
        $wherestring = array('cm.created_type'=>'6');
        $data['sortfield'] = 'cm.first_name';
        $data['sortby'] = 'asc';
        $data['contact_list'] =$this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
        $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $match = array();
        $data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
        $data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
        $data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
        $data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
        
        $sdata = array('selected_view' => 2);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        
        $data['main_content'] = "admin/".$this->viewName."/add1";
        $this->load->view('admin/include/template', $data);
    }

    /*
        @Description: Function for Insert New cron data
        @Author     : Sanjay Moghariya
        @Input      : property cron data
        @Output     : List of property cron with new inserted records
        @Date       : 18-11-2014
    */
    public function insert_data()
    {
        //pr($_POST);exit;
        $cdata['name'] = $this->input->post('name');
        /*$cdata['country'] = $this->input->post('country');
        $cdata['state'] = $this->input->post('state');*/
        $cdata['city'] = $this->input->post('city');
        $cdata['neighborhood'] = $this->input->post('neighborhood');
        $cdata['zip_code'] = $this->input->post('zip_code');
        $cdata1['user']=$this->input->post('slt_user');
        $cdata['cron_type'] = $this->input->post('cron_type');
        $cdata['radius_limit'] = $this->input->post('radius_limit');
        $cdata['data_from'] = $this->input->post('data_from');
        $cdata['created_by'] = $this->admin_session['id'];
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        if($cdata['data_from'] == '2')
            $cdata['property_id'] = $this->input->post('property_id');
	
        $cron_id = $this->joomla_property_cron_model->insert_record($cdata);
        //$contactdata = $this->input->post('finalcontactlist');
        $assign_contacts = $this->input->post('finalcontactlist');
        $assign_contacts = explode(",",$assign_contacts);
        
        // OLD
        /*if(!empty($cdata1['user']))
        {	
            foreach($cdata1['user'] as $id)
            {
                $datac['joomla_property_cron_master_id']=$cron_id;
                $datac['contact_id'] = $id;
                $datac['assigned_date'] = date('Y-m-d h:i:s');
                $datac['assigned_by'] = $this->admin_session['id'];
                $this->joomla_property_cron_model->insert_joomla_cron_trans($datac);
            }
        }
         * */
        /// NEW 17-12
        if(!empty($assign_contacts))
        {
            foreach($assign_contacts as $id)
            {
                $datac['joomla_property_cron_master_id']=$cron_id;
                $datac['contact_id'] = $id;
                $datac['assigned_date'] = date('Y-m-d h:i:s');
                $datac['assigned_by'] = $this->admin_session['admin_id'];
                $this->joomla_property_cron_model->insert_joomla_cron_trans($datac);
            }
        }
        // END NEW
        
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $joomla_property_cron_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('joomla_property_cron_sortsearchpage_data', $joomla_property_cron_sortsearchpage_data);
        $sel_view = $this->session->userdata('selected_property_cron_view_session');
        if(!empty($sel_view['selected_view']) && $sel_view['selected_view'] == '2')
            redirect('admin/'.$this->viewName.'/property_cron_crm_index');
        else
            redirect('admin/'.$this->viewName);
    }
 
    /*
        @Description: Get cron details for edit
        @Author     : Sanjay Moghariya
        @Input      : Cron id
        @Output     : cron details
        @Date       : 18-11-2014
    */
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
        
        /* OLD
        $table = "joomla_property_cron_trans as jpct";
        $fields = array('cm.id,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name');
        $where = array('jpct.joomla_property_cron_master_id'=>$id);
        $join_tables = array(
            'contact_master as cm'=>'cm.id = jpct.contact_id',
            //'(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
        );
        $group_by='cm.id';

        $cdata['contacts_data'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
        */
        
        //// NEW 17-12-2014
        $config['per_page'] = 50;	
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 4;
        //$uri_segment = $this->uri->segment(4);
        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
        $join_tables = array(
            'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
        );
        $group_by='cm.id';
        $wherestring = array('cm.created_type'=>'6');
        $cdata['sortfield'] = 'cm.first_name';
        $cdata['sortby'] = 'asc';
        $cdata['contact_list'] =$this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], '',$cdata['sortfield'],$cdata['sortby'],$group_by,$wherestring);
        $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');

        $this->pagination->initialize($config);

        $cdata['pagination'] = $this->pagination->create_links();

        $match = array();
        $cdata['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
        //pr($data['contact_type']);
        $cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
        $cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');

        $cdata['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
        
        $table = "joomla_property_cron_trans as ct";
        $fields = array('cm.id','ct.id as trans_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
        $where = array('ct.joomla_property_cron_master_id'=>$id);
        $join_tables = array(
            'contact_master as cm'=>'cm.id = ct.contact_id'
        );
        $group_by='cm.id';

        $cdata['contacts_data'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
//echo $this->db->last_query();
//pr( $data['contacts_data']);exit;
        
        /// END NEW 17-12-2014
        
        $match = array('id'=>$id);
        $result = $this->joomla_property_cron_model->select_records('',$match,'','=');
        /*if(!empty($result))
        {
            $msg = $this->lang->line('common_right_msg_task');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            redirect('admin/'.$this->viewName);	
        }*/
        $cdata['editRecord'] = $result;
        $sdata = array('selected_view' => 1);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        $cdata['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$cdata);
    }
    
    /*
        @Description: Get cron details for edit
        @Author     : Sanjay Moghariya
        @Input      : Cron id
        @Output     : cron details
        @Date       : 15-06-2015
    */
    public function edit_record1()
    {
     	$id = $this->uri->segment(4);
        
        $config['per_page'] = 50;	
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 4;
        //$uri_segment = $this->uri->segment(4);
        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
        $join_tables = array(
            'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
        );
        $group_by='cm.id';
        $wherestring = array('cm.created_type'=>'6');
        $cdata['sortfield'] = 'cm.first_name';
        $cdata['sortby'] = 'asc';
        $cdata['contact_list'] =$this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], '',$cdata['sortfield'],$cdata['sortby'],$group_by,$wherestring);
        $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');

        $this->pagination->initialize($config);

        $cdata['pagination'] = $this->pagination->create_links();

        $match = array();
        $cdata['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
        //pr($data['contact_type']);
        $cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
        $cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');

        $cdata['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
        
        $table = "joomla_property_cron_trans as ct";
        $fields = array('cm.id','ct.id as trans_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
        $where = array('ct.joomla_property_cron_master_id'=>$id);
        $join_tables = array(
            'contact_master as cm'=>'cm.id = ct.contact_id'
        );
        $group_by='cm.id';

        $cdata['contacts_data'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
        
        $match = array('id'=>$id);
        $result = $this->joomla_property_cron_model->select_records('',$match,'','=');

        $cdata['editRecord'] = $result;
        
        $sdata = array('selected_view' => 2);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        
        $cdata['main_content'] = "admin/".$this->viewName."/add1";
        $this->load->view("admin/include/template",$cdata);
    }

    /*
        @Description: Function for Update update cron
        @Author     : Sanjay Moghariya
        @Input      : Update details of Cron
        @Output     : List with updated Cron details
        @Date       : 18-11-2014
    */
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
        $cdata['name'] = $this->input->post('name');
        /*$cdata['country'] = $this->input->post('country');
        $cdata['state'] = $this->input->post('state');*/
        $cdata['city'] = $this->input->post('city');
        $cdata['neighborhood'] = $this->input->post('neighborhood');
        $cdata['zip_code'] = $this->input->post('zip_code');
        //$cdata1['new_user'] = $this->input->post('slt_user');
        $cdata['cron_type'] = $this->input->post('cron_type');
        $cdata['radius_limit'] = $this->input->post('radius_limit');
        $cdata['data_from'] = $this->input->post('data_from');
        $cdata['modified_by'] = $this->admin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        if($cdata['data_from'] == '2')
            $cdata['property_id'] = $this->input->post('property_id');
        $this->joomla_property_cron_model->update_record($cdata);
	    
        $field=array('distinct contact_id');
        $match1 = array('joomla_property_cron_master_id'=>$cdata['id']);
        $result1 = $this->joomla_property_cron_model->select_records1($field,$match1,'','=');
        //echo $this->db->last_query();exit;
        
        
        $valuation_contacts = $this->input->post('finalcontactlist');
        $valuation_contacts = explode(",",$valuation_contacts);

        $app = array();
        if(!empty($result1))
        {
            foreach($result1 as $app_manage)
            {
                $app[] = $app_manage['contact_id'];
            }
        }
        $cdata['old_user']= $app;
        $data['insert'] = array_diff ($valuation_contacts ,$cdata ['old_user'] );
        $data['delete'] = array_diff ($cdata['old_user'] ,$valuation_contacts);
        
        if($data['delete']!='')
        {
            foreach($data['delete'] as $id)
            {
                $datac1['joomla_property_cron_master_id']=$cdata['id'];
                $datac1['contact_id'] = $id;
                $fields = array('last_report_file');
                $match = array('joomla_property_cron_master_id'=>$cdata['id'],'contact_id'=>$id);
                $pdfres = $this->joomla_property_cron_model->select_records1($fields,$match,'','=');
                if(!empty($pdfres) && !empty($pdfres[0]['last_report_file']))
                {
                    $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$pdfres[0]['last_report_file'];
                    if(file_exists($pdfpath))
                    { 
                        @unlink($pdfpath);
                    }
                }
                $this->joomla_property_cron_model->delete_record1($datac1);
            }
        }
		
        if(!empty($data['insert']))
        {
            foreach($data['insert'] as $row)
            {
                $datac['joomla_property_cron_master_id']=$cdata['id'];
                $datac['contact_id'] = $row;
                $this->joomla_property_cron_model->insert_joomla_cron_trans($datac);
            }
        }
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        
        $sel_view = $this->session->userdata('selected_property_cron_view_session');
        if(!empty($sel_view['selected_view']) && $sel_view['selected_view'] == '2') {
            $searchsort_session = $this->session->userdata('joomla_property_cron_crm_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            redirect('admin/'.$this->viewName.'/property_cron_crm_index/'.$pagingid);
        }
        else {
            $searchsort_session = $this->session->userdata('joomla_property_cron_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
        }
    }
	
	
    /*
    @Description: Function for Delete Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete id which Task record want to delete
    @Output: - New Task list after record is deleted.
    @Date: 02-08-2014
    */
    function delete_record()
    {
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	
    /*
        @Description: Function for Delete cron settings
        @Author     : Sanjay Moghariya
        @Input      : cron id
        @Output     : delete record
        @Date       : 18-11-2014
    */
    public function ajax_delete_all()
    {
        $id=$this->input->post('single_remove_id');
        $array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
        if(!empty($id))
        {
            $this->joomla_property_cron_model->delete_record($id);
            $this->joomla_property_cron_model->delete_trans_cron($id);            
            unset($id);
        }
        elseif(!empty($array_data))
        {
            for($i=0;$i<count($array_data);$i++)
            {
                $this->joomla_property_cron_model->delete_record($array_data[$i]);
                $this->joomla_property_cron_model->delete_trans_cron($array_data[$i]);
                $delete_all_flag = 1;
                $cnt++;
            }
        }
		
        $searchsort_session = $this->session->userdata('joomla_property_cron_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
        $total_rows = $searchsort_session['total_rows'];
        if($delete_all_flag == 1)
        {
            $total_rows -= $cnt;
            if($pagingid*$perpage > $total_rows)
            {
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
        @Description: Get Details of cron setting
        @Author     : Sanjay Moghariya
        @Input      : property cron id
        @Output     : Details of cron
        @Date       : 18-11-2014
    */
    public function view_record()
    {
    	$id = $this->uri->segment(4);
		
        ////////////////
        $table = "joomla_property_cron_trans as jpct";
        $fields = array('cm.id,jpct.joomla_property_cron_master_id,jpct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address as email_id');
        $where = array('jpct.joomla_property_cron_master_id'=>$id);
        $join_tables = array(
            'contact_master as cm'=>'cm.id = jpct.contact_id',
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
        );
        $group_by='cm.id';

        $data['contacts_data'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
        
        ////////////////

        $match = array('id'=>$id);
        $result = $this->joomla_property_cron_model->select_records('',$match,'','=');
        $data['editRecord'] = $result;
        
        $table = "joomla_property_cron_master as jpcm";
        $fields = array('jpcm.*','cm.id as contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cet.email_address as email_id');
        $where = array('jpcm.id'=>$id);
        $join_tables = array(
            'joomla_property_cron_trans jpct'=>'jpcm.id=jpct.joomla_property_cron_master_id',
            'contact_master as cm'=>'cm.id = jpct.contact_id',
            '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id'
        );
        $group_by='jpcm.id';
        
        $data['datalist'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$where,'','=','','','','','');
        //pr($data['datalist']);exit;
        $data['main_content'] = "admin/".$this->viewName."/view"; 
        $this->load->view("admin/include/template",$data);
    }
    
    /*
        @Description: For search contact in popup
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Search contact
        @Date       : 18-12-2014
    */
    public function search_contact_ajax()
    {
        $searchsort_session = $this->session->userdata('valuation_popup_contact');
        $perpage=''; 
        $perpage = $this->input->post('perpage');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
		
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
                $config['per_page'] = '50';
				//$data['perpage'] = '50';
            }
        }
		
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            $data['sortfield'] = 'cm.first_name';
            $data['sortby'] = 'asc';
        }
	
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 4;
        $uri_segment = $this->uri->segment(4);
	
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $contact_status = $this->input->post('contact_status');
        $contact_source = $this->input->post('contact_source');
        $contact_type = $this->input->post('contact_type');
		
        $search_tag = $this->input->post('search_tag');
        $where= array();
        $where = array('cm.created_type'=>'6');
        if(!empty($contact_status) && !empty($contact_source)) {
            $contact_status_array = array('cm.contact_status'=>$contact_status,'cm.contact_source'=>$contact_source);
            $where = array_merge($where,$contact_status_array);
        }
        elseif(!empty($contact_status)) {
            $where_status = array('cm.contact_status'=>$contact_status);
            $where = array_merge($where,$where_status);
        }
        elseif(!empty($contact_source)) {
            $where_source = array('cm.contact_source'=>$contact_source);
            $where = array_merge($where,$where_source);
        }
        if(!empty($contact_type))
        {
            $contact_type_array = array('cct.contact_type_id'=>$contact_type);
            $where = array_merge($where,$contact_type_array);
        }
		
        $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext);
        
        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
        $join_tables = array(
            'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
            'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
            'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id',
        );
        $group_by='cm.id';
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
		
		
        $data['contact_list'] =$this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
        //echo $this->db->last_query();exit;
        $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');

        $iplans_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

			
        $this->session->set_userdata('valuation_popup_contact', $iplans_sortsearchpage_data);
        
        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();
		
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
    }

    /*
        @Description: For search add contact in valuation report
        @Author     : Sanjay Moghariya
        @Input      : contacts id
        @Output     : Add contact to valuation report
        @Date       : 18-12-2014
    */
    public function add_contacts_to_valuation()
    {
            $contacts=$this->input->post('contacts');
            $data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);
            $this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
    }

    /*
        @Description: For search view contact in valuation report
        @Author     : Sanjay Moghariya
        @Input      : contacts id
        @Output     : Add contact to valuation report
        @Date       : 18-12-2014
    */
    public function view_contacts_of_valuation()
    {
            $id=$this->input->post('interaction_plan');

            $table = "interaction_plan_contacts_trans as ct";
            $fields = array('ct.interaction_plan_id','cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
            $where = array('ct.interaction_plan_id'=>$id);
            $join_tables = array(
                    'contact_master as cm'=>'cm.id = ct.contact_id',
                    'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
            );
            $group_by='cm.id';

            $data['contact_list'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);

            $this->load->view($this->user_type.'/'.$this->viewName."/view_contact_popup",$data);
    }
    
    /*
        @Description: Function for Delete contacts from valuation reports Ajax
        @Author     : Sanjay Moghariya
        @Input      : Delete details of contacts
        @Output     : Delete contact from valuation report
        @Date       : 18-12-2014
    */
    function delete_contact_from_valuation()
    {
        $contact_id = $this->input->post('contact_id');
        $id = $this->input->post('trans_id');

        if(!empty($id) && !empty($contact_id))
        {
            //$data['contact_id'] = $contact_id;
            //$data['id'] = $id;
            //$this->joomla_property_cron_model->delete_record1($data);
            $fields = array('last_report_file');
            $match = array('id'=>$id,'contact_id'=>$contact_id);
            $pdfres = $this->joomla_property_cron_model->select_records1($fields,$match,'','=');
            if(!empty($pdfres) && !empty($pdfres[0]['last_report_file']))
            {
                $pdfpath = $this->config->item('base_path').'/uploads/valuation_pdf_file/'.$pdfres[0]['last_report_file'];
                if(file_exists($pdfpath))
                { 
                    @unlink($pdfpath);
                }
            }
            $this->joomla_property_cron_model->delete_from_trans($id);
            //echo $this->db->last_query();
        }
    }
    
    /*
        @Description: Function for get city/neighbor/zipcode suggestion from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : city/neighbor/zipcode
        @Output     : City/neighbor/zipcode list
        @Date       : 24-12-2014
    */
    function getcity_nei_ziplist()
    {
        $search_name = $this->input->post('search_name');
        if($search_name == 'city') {
            $city = urlencode($this->input->post('city'));
            //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?city=".$city;
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            $url = $joomla_link."/libraries/api/neighborhood_lookup.php?city=".$city;
        } else if($search_name == 'neighbor') {
            $neighbor = urlencode($this->input->post('neighbor'));
            //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?name=".$neighbor;
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            $url = $joomla_link."/libraries/api/neighborhood_lookup.php?name=".$neighbor;
        } else {
            $zipcode = urlencode($this->input->post('zipcode'));
            //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?zipcode=".$zipcode;
            $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
            $url = $joomla_link."/libraries/api/neighborhood_lookup.php?zipcode=".$zipcode;
        }
        
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
        //echo json_encode($response);
        if(!empty($response)) {
            $string.= '<ul class="token-input-list-facebook">';
            foreach($response as $row) {
                if($search_name == 'city') {
                    $string.= '<li onClick="fill(\''.addslashes($row['city']).'\');">'.$row['city'].'</li>';
                } else if($search_name == 'neighbor') {
                    $string.= '<li onClick="filln(\''.addslashes($row['name']).'\');">'.$row['name'].'</li>';
                } else if($search_name == 'zipcode') {
                    $string.= '<li onClick="fillz(\''.addslashes($row['zipcode']).'\');">'.$row['zipcode'].'</li>';
                }
            }
            $string.= '</ul>';
        } else {
                $string.= '<li>No Record found</li>';
        }
        echo $string;	
        exit;
    }
    
    /*
        @Description: Function for get city/neighbor/zipcode suggestion from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : city/neighbor/zipcode
        @Output     : City/neighbor/zipcode list
        @Date       : 24-12-2014
    */
    function getcitylist()
    {
        $city = urlencode($_REQUEST['q']);
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?city=".$city;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?city=".$city;
        
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
        //echo json_encode($response);
        $data = array();
        if(!empty($response)) {
            $i=0;
            foreach($response as $row) {
                //$data[] = array("id" => $data1['id'], "name" => $data1['name']);
                $data[] = array("id" => addslashes($row['city']),"name"=>addslashes($row['city']));
            }
        }
        if(!empty($data)) {
            echo json_encode($data);
        } else {
            echo '[]';
        }
    }
    
    /*
        @Description: Function for get neighborhood suggestion from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : neighbor name
        @Output     : neighbor list
        @Date       : 24-12-2014
    */
    function getneighborlist()
    {
        $naddr = urlencode($_REQUEST['q']);
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?name=".$naddr;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?name=".$naddr;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $nresponse = curl_exec($ch);
        curl_close($ch);
        $nresponse = (json_decode($nresponse, true));
        $ndata = array();
        if(!empty($nresponse)) {
            $i=0;
            foreach($nresponse as $row) {
                //$data[] = array("id" => $data1['id'], "name" => $data1['name']);
                $ndata[] = array("id"=>addslashes($row['name']), "name"=>addslashes($row['name']));
            }
        }
        if(!empty($ndata)) {
            echo json_encode($ndata);
        } else {
            echo '[]';
        }
    }
    
    /*
        @Description: Function for get zipcode suggestion from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : zipcode
        @Output     : zipcode list
        @Date       : 24-12-2014
    */
    function getzipcodelist()
    {
        $zipcode = urlencode($_REQUEST['q']);
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?zipcode=".$zipcode;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?zipcode=".$zipcode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $zresponse = curl_exec($ch);
        curl_close($ch);
        $zresponse = (json_decode($zresponse, true));
        $zdata = array();
        if(!empty($zresponse)) {
            $i=0;
            foreach($zresponse as $row) {
                //$data[] = array("id" => $data1['id'], "name" => $data1['name']);
                $zdata[] = array("id" => addslashes($row['zipcode']),"name"=>addslashes($row['zipcode']));
            }
        }
        if(!empty($zdata)) {
            echo json_encode($zdata);
        } else {
            echo '[]';
        }
    }
    
    /*
        @Description: Function for get state suggestion from CRM
        @Author     : Sanjay Moghariya
        @Input      : city
        @Output     : City list
        @Date       : 15-06-2015
    */
    function getstatelist1()
    {
        $city = urlencode($_REQUEST['q']);
        
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.'.mls_property_list_master';
        $where = '(STA LIKE "%' . $city . '%")';// AND mls_id = '.$this->mls_id;
        $fields = array('DISTINCT STA');
        $result = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'','20');
        $data = array();
        if(!empty($result)) {
            $i=0;
            foreach($result as $row) {
                //$data[] = array("id" => $data1['id'], "name" => $data1['name']);
                $data[] = array("id" => addslashes($row['STA']),"name"=>addslashes($row['STA']));
            }
        }
        if(!empty($data)) {
            echo json_encode($data);
        } else {
            echo '[]';
        }
    }
    
    /*
        @Description: Function for get neighborhood suggestion from CRM
        @Author     : Sanjay Moghariya
        @Input      : neighbor name
        @Output     : neighbor list
        @Date       : 15-06-2015
    */
    function getneighborlist1()
    {
        $naddr = urlencode($_REQUEST['q']);
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.'.mls_property_list_master';
        //$where = '(full_address LIKE "%' . $naddr . '%")';// AND mls_id = '.$this->mls_id;
        //$fields = array('DISTINCT full_address');
        $where = '(HSN LIKE "%' . $naddr . '%" OR STR LIKE "%' . $naddr . '%" OR SSUF LIKE "%' . $naddr . '%" OR DRS LIKE "%' . $naddr . '%" OR UNT LIKE "%' . $naddr . '%" OR CIT LIKE "%' . $naddr . '%")';// AND mls_id = '.$this->mls_id;
        $fields = array('ID,HSN, DRP, STR, SSUF, DRS, UNT, CIT');
        $nresult = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','','','','20','','','','',$where);
        //echo $this->db->last_query();
        //pr($nresult);exit;
        $ndata = array();
        if(!empty($nresult)) {
            $i=0;
            foreach($nresult as $row) {
                $address = '';
                if(!empty($row['HSN'])) { $address .= $row['HSN']; } else {$address .= ''; }
                if(!empty($row['STR'])) { $address .= ' '.$row['DRP']; } else {$address .= ''; }
                if(!empty($row['STR'])) { $address .= ' '.$row['STR']; } else {$address .= ''; }
                if(!empty($row['SSUF'])) { $address .= ' '.$row['SSUF']; } else {$address .= ''; }
                if(!empty($row['DRS'])) { $address .= ' '.$row['DRS']; } else {$address .= ''; }
                if(!empty($row['UNT'])) { $address .= ' #'.$row['UNT']; } else {$address .= ''; }
                if(!empty($row['CIT'])) { $address .= ', '.$row['CIT']; } else {$address .= ''; }
                //$ndata[] = array("id"=>addslashes($row['full_address']), "name"=>addslashes($row['full_address']));
                $ndata[] = array("id"=>addslashes($address), "name"=>addslashes($address),'property_id'=>$row['ID']);
            }
        }
        if(!empty($ndata)) {
            echo json_encode($ndata);
        } else {
            echo '[]';
        }
    }
    
    /*
        @Description: Function for get zipcode suggestion from CRM
        @Author     : Sanjay Moghariya
        @Input      : zipcode
        @Output     : zipcode list
        @Date       : 15-06-2015
    */
    function getzipcodelist1()
    {
        $zipcode = urlencode($_REQUEST['q']);
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.'.mls_property_list_master';
        $where = '(ZIP LIKE "%' . $zipcode . '%")';// AND mls_id = '.$this->mls_id;
        $fields = array('DISTINCT ZIP');
        $zresult = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'','20');
        $zdata = array();
        if(!empty($zresult)) {
            $i=0;
            foreach($zresult as $row) {
                $zdata[] = array("id" => addslashes($row['ZIP']),"name"=>addslashes($row['ZIP']));
            }
        }
        if(!empty($zdata)) {
            echo json_encode($zdata);
        } else {
            echo '[]';
        }
    }
    /*
        @Description: Function for check city, neighbor or zipcode valid or not from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : city, neighbor or zipcode
        @Output     : 
        @Date       : 15-06-2015
    */
    public function check_address1()
    {
        $city = $this->input->post('city');
        $neighbor = $this->input->post('neighbor');
        $zipcode = $this->input->post('zipcode');
        $msg = 0;
        
        // Check city exitst or not
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db.'.mls_property_list_master';
        $where = array('STA' =>$city);// AND mls_id = '.$this->mls_id;
        $fields = array('DISTINCT STA');
        $result = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'','');
        if(empty($result)) {
            $msg = 1;
            echo $msg;
            exit;
        }
        
        // Check address valid or not
        /*$where = array('full_address' =>$neighbor);// AND mls_id = '.$this->mls_id;
        $fields = array('DISTINCT full_address');
        $nresult = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'','');
        if(empty($nresult)) {
            $msg = 2;
            echo $msg;
            exit;
        }
        */
        // Check zipcode valid or not
        $where = array('ZIP' =>$zipcode);// AND mls_id = '.$this->mls_id;
        $fields = array('DISTINCT ZIP');
        $zresult = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,'','','',$where,'','');
        if(empty($zresult)) {
            $msg = 3;
            echo $msg;
            exit;
        }
    }
    
    /*
        @Description: Function for check city, neighbor or zipcode valid or not from Joomal website
        @Author     : Sanjay Moghariya
        @Input      : city, neighbor or zipcode
        @Output     : 
        @Date       : 01-01-2015
    */
    public function check_address()
    {
        $city = $this->input->post('city');
        $neighbor = $this->input->post('neighbor');
        $zipcode = $this->input->post('zipcode');
        $msg = 0;
        
        // Check city exitst or not
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?citycheck=".$city;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?citycheck=".$city;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $cresponse = curl_exec($ch);
        curl_close($ch);
        $cresponse = (json_decode($cresponse, true));
        if($cresponse['success'] == '0') {
            $msg = 1;
            echo $msg;
            exit;
        }
        
        // Check address valid or not
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?namecheck=".$neighbor;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?namecheck=".$neighbor;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $nresponse = curl_exec($ch);
        curl_close($ch);
        $nresponse = (json_decode($nresponse, true));
        if($nresponse['success'] == '0') {
            $msg = 2;
            echo $msg;
            exit;
        }
        
        // Check zipcode valid or not
        //$url = "http://seattle.livewiresites.com/libraries/api/neighborhood_lookup.php?zipcodecheck=".$zipcode;
        $joomla_link = trim($this->config->item('joomla_webservice_link'),'/');
        $url = $joomla_link."/libraries/api/neighborhood_lookup.php?zipcodecheck=".$zipcode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $zresponse = curl_exec($ch);
        curl_close($ch);
        $zresponse = (json_decode($zresponse, true));
        if($zresponse['success'] == '0') {
            $msg = 3;
            echo $msg;
            exit;
        }
    }
    
    /*
        @Description: Function for set tab session for joomla web and redirect to contact list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : Contact wise joomla property cron list
        @Date       : 23-06-2015
    */
    public function assigned_contact_list_web()
    {
        $sdata = array('selected_view' => 1);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        redirect('admin/'.$this->viewName.'/assigned_contact_list');
    }
    
    /*
        @Description: Function for set tab session for crm and redirect to contact list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : Contact wise joomla property cron list
        @Date       : 23-06-2015
    */
    public function assigned_contact_list_crm()
    {
        $sdata = array('selected_view' => 2);
        $this->session->set_userdata('selected_property_cron_view_session',$sdata);
        redirect('admin/'.$this->viewName.'/assigned_contact_list');
    }
    
    /*
        @Description: Function for get contact wise joomla property cron setting list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : Contact wise joomla property cron list
        @Date       : 02-01-2015
    */
    public function assigned_contact_list()
    {
        $searchopt='';$searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('joomla_contact_property_cron_sortsearchpage_data');
        }
        $data['sortfield']		= 'id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('joomla_contact_property_cron_sortsearchpage_data');

        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
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
            $data['searchtext'] = stripslashes($searchtext);
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                  /*  $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];

                }
            }
        }
        if(!empty($perpage) && $perpage != 'null')
        {
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
        $config['base_url'] = site_url($this->user_type.'/'."joomla_property_cron/assigned_contact_list");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        
        //$where = array('status'=>"'0'");
        $where = '';  
        
        $table = " joomla_property_cron_trans as jpct";
        //$group_by = "cm.id";
        
        //$wherestring = array('cm.created_type'=>'6','cm.joomla_category'=>"'".$searchtext."'");
        
        $fields = array('jpcm.*','jpct.last_report_file','cm.id as contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cpt.phone_no');
        $join_tables = array(
            'joomla_property_cron_master jpcm'=>'jpcm.id = jpct.joomla_property_cron_master_id',
            'contact_master cm'=>'cm.id = jpct.contact_id',
            '(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
        );
        $wherestring = array('jpcm.data_from'=>1);
        $sel_view = $this->session->userdata('selected_property_cron_view_session');
        if(!empty($sel_view['selected_view']) && $sel_view['selected_view'] == '2')
            $wherestring = array('jpcm.data_from'=>2);
            
        if(!empty($searchtext))
        {
            $match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'cpt.phone_no'=>$searchtext,'name'=>$searchtext,'city'=>$searchtext,'zip_code'=>$searchtext,'neighborhood'=>$searchtext,'cron_type'=>$searchtext);
            $data['datalist'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$wherestring);
            //	echo $this->db->last_query();exit;
            $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$wherestring,'','1');
        }
        else
        {
            $data['datalist'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$wherestring);
            //echo $this->db->last_query();exit;
            $config['total_rows'] = $this->joomla_property_cron_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring,'','1');
        }
        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $joomla_contact_property_cron_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
        $this->session->set_userdata('joomla_contact_property_cron_sortsearchpage_data', $joomla_contact_property_cron_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        
        $data['interaction_type'] = $this->contact_masters_model->select_records1('','','','','','','','name','desc', 'interaction_plan__plan_type_master');
        $data['disposition_type'] = $this->contact_masters_model->select_records1('','','','','','','','name','asc', 'contact__disposition_master');
        
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/assigned_contact_ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/assigned_contact_list";
            $this->load->view('admin/include/template',$data);
        }
    }
    
    /*
        @Description: Function for Delete cron settings
        @Author     : Sanjay Moghariya
        @Input      : cron id
        @Output     : delete record
        @Date       : 02-01-2015
    */
    public function ajax_delete_contact_from_vreport()
    {
        $id = $this->input->post('single_remove_id');
        $contact_id = $this->input->post('contact_id');
        $array_data = $this->input->post('myarray');
        $contactid_array_data = $this->input->post('contactid_array');
        $delete_all_flag = 0;$cnt = 0;
        if(!empty($id))
        {
            //$this->joomla_property_cron_model->delete_record($id);
            $this->joomla_property_cron_model->delete_trans_cron($id,$contact_id);            
            unset($id);
        }
        elseif(!empty($array_data))
        {
            for($i=0;$i<count($array_data);$i++)
            {
                //$this->joomla_property_cron_model->delete_record($array_data[$i]);
                $this->joomla_property_cron_model->delete_trans_cron($array_data[$i],$contactid_array_data[$i]);
                $delete_all_flag = 1;
                $cnt++;
            }
        }
		
        $searchsort_session = $this->session->userdata('joomla_contact_property_cron_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
        $total_rows = $searchsort_session['total_rows'];
        if($delete_all_flag == 1)
        {
            $total_rows -= $cnt;
            if($pagingid*$perpage > $total_rows)
            {
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
        @Description: Function for set selected view session
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 22-06-2015
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
            $this->session->set_userdata('joomla_contact_property_cron_crm_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('joomla_contact_property_cron_sortsearchpage_data', $sortsearchpage_data);	
        
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('selected_property_cron_view_session',$data);
    }
}