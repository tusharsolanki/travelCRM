<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class joomla_contact_form_control extends CI_Controller
{	
    function __construct()
    {
        
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $result = check_joomla_tab_setting($this->admin_session['id']);
        if(!empty($result) && $result[0]['contact_form_tab'] == '0')
            redirect('admin/dashboard');
        $this->load->model('property_valuation_contact_model');
        $this->load->model('property_contact_model');
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'admin';
    }
	
    /*
        @Description: Function for Get joomla property contact form List
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : get joomla property contact form list
        @Date       : 10-03-2015
    */
    public function index()
    {	
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        $data['sortfield']		= 'jpc.id';
        $data['sortby']			= 'desc';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('joomla_contact_form_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('joomla_contact_form_sortsearchpage_data');

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
                $sortfield = 'jpc.id';
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
                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($perpage))
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
        $config['base_url'] = site_url($this->user_type.'/'."joomla_contact_form/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        
        $table = "joomla_rpl_property_contact jpc";
        $fields = array('jpc.id,jpc.property_name,jpc.domain,jpc.name,jpc.email,jpc.phone,jpc.preferred_time,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name');
        $join_tables = array('contact_master as cm' => 'cm.id = jpc.lw_admin_id');
        $where = 'jpc.form_type != "propertydetail"';
        if(!empty($searchtext))
        {
            $match = array('jpc.property_name'=>$searchtext,'jpc.domain'=>$searchtext,'jpc.name'=>$searchtext,'jpc.email'=>$searchtext,'jpc.phone'=>$searchtext,'jpc.preferred_time'=>$searchtext);
            $data['datalist'] =$this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where);
            $config['total_rows'] = $this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$where,'','1');
        }
        else
        {
            $data['datalist'] =$this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$where);
            $config['total_rows'] = $this->property_contact_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where,'','1');
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
        $this->session->set_userdata('joomla_contact_form_sortsearchpage_data', $sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;

        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
            $this->load->view($this->user_type.'/include/template',$data);
        }
    }
    
    /*
        @Description: Function for view property contact popup data
        @Author     : Sanjay Moghariya
        @Input      : Selected property contact id
        @Output     : Property contact data
        @Date       : 10-03-2015
    */
    public function view_property_contact_popup()
    {
        $id = $this->input->post('search_id');
        $match = array('id'=>$id);
        $data['property_contact_list'] = $this->property_contact_model->select_records('',$match,'','=');
        $this->load->view($this->user_type.'/'.$this->viewName."/view_property_contact_popup",$data);
    }
}