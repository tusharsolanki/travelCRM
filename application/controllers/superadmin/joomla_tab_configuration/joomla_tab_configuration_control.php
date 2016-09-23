<?php 
/*
	@Description: Joomla tab configuaration controller
	@Author     : Sanjay Moghariya
	@Input      : 
	@Output     : 
	@Date       : 06-01-2015
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class joomla_tab_configuration_control extends CI_Controller
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
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';
    }
	
    /*
        @Description: Function for Get All Admin List
        @Author     : Mohit Trivedi
        @Input      : Search value or null
        @Output     : all Admin list
        @Date       : 06-01-2015
    */
    public function index()
    {	
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');      

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('joomla_tab_config_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('joomla_tab_config_sortsearchpage_data');
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
                    /*$data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];
					*/
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
        
        $config['base_url'] = site_url($this->user_type.'/'."joomla_tab_configuration/");
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
            $data['datalist'] = $this->admin_model->get_user('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config['total_rows'] = $this->admin_model->get_user('',$match,'','like','','','','','','','','1');
        }
        else
        {
            $match=array('user_type'=>'2');
            $data['datalist'] = $this->admin_model->get_user('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);
            //	echo $this->db->last_query();exit;	
            $config['total_rows']= $this->admin_model->get_user('',$match,'','=','','','','','','','','1');
        }
        //pr($data['datalist']);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['msg'] = $this->message_session['msg'];
        $joomla_tab_config_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
        $this->session->set_userdata('joomla_tab_config_sortsearchpage_data', $joomla_tab_config_sortsearchpage_data);
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
        $cdata['is_buyer_tab'] = '0';
        $this->admin_model->update_user_buyer($cdata);
		
		
        $match = array('id'=>$id);
        $result = $this->admin_model->get_user('',$match,'','=');
        //pr($result);exit;
        if(!empty($result[0]['db_name']))
        {
            //$update_data['created_by'] = $result[0]['created_by'];
            $update_data['is_buyer_tab'] = '0';
            $this->admin_model->update_user_buyer($update_data,$result[0]['db_name']);

            $data['is_buyer_tab'] = '0';
            $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']);
        }
		
		
        $msg = $this->lang->line('common_buyur_off_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $admin_id = $id;
        $pagingid = $this->admin_model->getadminpagingid($admin_id);
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
        $cdata['is_buyer_tab'] = '1';
        $this->admin_model->update_user_buyer($cdata);
		
        $match = array('id'=>$id);
        $result = $this->admin_model->get_user('',$match,'','=');
        if(!empty($result[0]['db_name']))
        {
            //$update_data['created_by'] = $result[0]['created_by'];
            $update_data['is_buyer_tab'] = '1';
            $this->admin_model->update_user_buyer($update_data,$result[0]['db_name']);
            
            $data['is_buyer_tab'] = '1';
            $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']);
        }
        $msg = $this->lang->line('common_buyur_on_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $admin_id = $id;
        $pagingid = $this->admin_model->getadminpagingid($admin_id);exit;
        redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
    
    /*
        @Description: Function for change joomla tab setting
        @Author     : Sanjay Moghariya
        @Input      : admin id, tab name, tab value
        @Output     : Update setting
        @Date       : 06-01-2015
    */
    public function change_joomla_tab_config()
    {
        $admin_id = $this->input->post('admin_id');
        $tab_name = $this->input->post('tab_name');
        $tab_value = $this->input->post('tab_value');
        
        if($tab_name == 'Buyer Preference')
        {
            $cdata['id'] = $admin_id;
            $cdata['is_buyer_tab'] = $tab_value;
            $this->admin_model->update_user_buyer($cdata);

            $match = array('id'=>$admin_id);
            $result = $this->admin_model->get_user('',$match,'','=');
            if(!empty($result[0]['db_name']))
            {
                $update_data['is_buyer_tab'] = $tab_value;
                $this->admin_model->update_user_tab($update_data,$result[0]['db_name']);

                $data['is_buyer_tab'] = $tab_value;
                $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']);
            }
            if($tab_value == '1')
                $msg = $this->lang->line('common_buyur_on_msg');
            else
                $msg = $this->lang->line('common_buyur_off_msg');
        }
        else if($tab_name == 'Lead Dashboard')
        {
            $cdata['id'] = $admin_id;
            $cdata['lead_dashboard_tab'] = $tab_value;
            $this->admin_model->update_user_buyer($cdata);

            $match = array('id'=>$admin_id);
            $result = $this->admin_model->get_user('',$match,'','=');
            if(!empty($result[0]['db_name']))
            {
                $update_data['lead_dashboard_tab'] = $tab_value;
                $this->admin_model->update_user_tab($update_data,$result[0]['db_name']);

                $data['lead_dashboard_tab'] = $tab_value;
                $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']);
            }
            if($tab_value == '1')
                $msg = $this->lang->line('common_lead_dashboard_on_msg');
            else
                $msg = $this->lang->line('common_lead_dashboard_off_msg');
        }
        else if($tab_name == 'Market Watch')
        {
            $cdata['id'] = $admin_id;
            $cdata['market_watch_tab'] = $tab_value;
            $this->admin_model->update_user_buyer($cdata); // Update in Master DB

            $match = array('id'=>$admin_id);
            $result = $this->admin_model->get_user('',$match,'','=');
            if(!empty($result[0]['db_name']))
            {
                $update_data['market_watch_tab'] = $tab_value;
                $this->admin_model->update_user_tab($update_data,$result[0]['db_name']); // Update in Admin DB

                $data['market_watch_tab'] = $tab_value;
                $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']); // Update in Admin DB
            }
            if($tab_value == '1')
                $msg = $this->lang->line('common_market_watch_on_msg');
            else
                $msg = $this->lang->line('common_market_watch_off_msg');
        }
        else if($tab_name == 'CF')
        {
            $cdata['id'] = $admin_id;
            $cdata['contact_form_tab'] = $tab_value;
            $this->admin_model->update_user_buyer($cdata); // Update in Master DB

            $match = array('id'=>$admin_id);
            $result = $this->admin_model->get_user('',$match,'','=');
            if(!empty($result[0]['db_name']))
            {
                $update_data['contact_form_tab'] = $tab_value;
                $this->admin_model->update_user_tab($update_data,$result[0]['db_name']); // Update in Admin DB
                
                $data['contact_form_tab'] = $tab_value;
                $this->admin_model->update_admin_user_buyer($data,$result[0]['db_name']); // Update in Admin DB
            }
            if($tab_value == '1')
                $msg = $this->lang->line('common_contact_form_on_msg');
            else
                $msg = $this->lang->line('common_contact_form_off_msg');
        }

        ////// Code for redirect on current page once action is done 
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        
        $searchsort_session = $this->session->userdata('joomla_tab_config_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;

        echo $pagingid;
    }
}