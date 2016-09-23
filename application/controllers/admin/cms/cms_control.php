<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cms_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('admin_model');
       
        $this->load->model('common_function_model');
        $this->load->model('cms_model');

        $this->obj = $this->cms_model;
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'admin';
    }
	
    /*
        @Description: Function for Get cms List
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : get cms list
        @Date       : 25-02-2015
    */
    public function index()
    {	
        $searchtext='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        $data['sortfield']		= 'cm.id';
        $data['sortby']			= 'desc';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('cms_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('cms_sortsearchpage_data');

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
                $sortfield = 'cm.id';
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
        $config['base_url'] = site_url($this->user_type.'/'."cms/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        
        $table = "cms_master as cm";
        $fields = array('id,menu_title,title,page_url,status,domain_name');
        $join_tables = array();//'admin_users as am' => 'am.id = cm.modified_by');
        
        if(!empty($searchtext))
        {
            $match = array('cm.menu_title'=>$searchtext,'cm.title'=>$searchtext,'cm.page_url'=>$searchtext,'cm.domain_name'=>$searchtext);
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'');
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','like','','','','','','','','1');
        }
        else
        {
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'');
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','','','','','','','1');
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
        $this->session->set_userdata('cms_sortsearchpage_data', $sortsearchpage_data);
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
        @Description: Function for add cms data
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     : Load Form for add cms data
        @Date       : 25-02-2015
    */
    public function add_record()
    {
        $parentdb = $this->config->item('parent_db_name');
        $table = $parentdb.".login_master as lm";
        $fields = array('jm.id,jm.domain');
        $join_tables = array($parentdb.'.joomla_mapping as jm' => 'lm.id = jm.lw_admin_id');
        $match = array('lm.email_id'=>$this->admin_session['useremail'],'lm.user_type'=>'2','jm.status'=>'1');
        $data['assigned_domain_list'] = $this->cms_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=');
        $data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
   
    /*
        @Description: Function for Insert cms data
        @Author     : Sanjay Moghariya
        @Input      : Details of new cms
        @Output     : insert cms data
        @Date       : 25-02-2015
    */
    public function insert_data()
    {
        //pr($_POST);exit;
        $cdata['menu_title'] = $this->input->post('menu_title');
        $cdata['title'] = $this->input->post('title');
        //$cdata['page_type'] = $this->input->post('page_type');
        $cdata['domain_name'] = $this->input->post('domain_name');
        $cdata['description'] = $this->input->post('description');
        $cdata['page_url'] = $this->seoUrl($this->input->post('page_url'));
        $cdata['meta_title'] = $this->input->post('meta_title');
        $cdata['meta_keyword'] = $this->input->post('meta_keyword'); 
        $cdata['meta_description'] = $this->input->post('meta_description');
        $cdata['created_by'] = $this->admin_session['id'];
        $cdata['created_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';

        $this->obj->insert_record($cdata);	
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
     }
 
    /*
        @Description: Function for edit cms data
        @Author     : Sanjay Moghariya
        @Input      : cms id
        @Output     : cmd id data
        @Date       : 25-02-2015
    */
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
        
        $parentdb = $this->config->item('parent_db_name');
        $table = $parentdb.".login_master as lm";
        $fields = array('jm.id,jm.domain');
        $join_tables = array($parentdb.'.joomla_mapping as jm' => 'lm.id = jm.lw_admin_id');
        $match = array('lm.email_id'=>$this->admin_session['useremail'],'lm.user_type'=>'2','jm.status'=>'1');
        $data['assigned_domain_list'] = $this->cms_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=');
        
        $data['main_content'] = "admin/".$this->viewName."/add";       
        $this->load->view("admin/include/template",$data);
    }

    /*
        @Description: Function for Update cms data
        @Author     : Sanjay Moghariya
        @Input      : cms details
        @Output     : update record
        @Date       : 25-02-2015
    */
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
        $cdata['menu_title'] = $this->input->post('menu_title');
        $cdata['title'] = $this->input->post('title');
        //$cdata['page_type'] = $this->input->post('page_type');
        $cdata['domain_name'] = $this->input->post('domain_name');
        $cdata['description'] = $this->input->post('description');
        $cdata['page_url'] = $this->seoUrl($this->input->post('page_url'));
        $cdata['meta_title'] = $this->input->post('meta_title');
        $cdata['meta_keyword'] = $this->input->post('meta_keyword'); 
        $cdata['meta_description'] = $this->input->post('meta_description');
        $cdata['modified_by'] = $this->admin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        //$cdata['status'] = $this->input->post('status');
        $this->obj->update_record($cdata);
		
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('cms_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
		
    }
    
    function seoUrl($string) {
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
        @Description: Function for Delete cms data
        @Author     : Sanjay Moghariya
        @Input      : cms id
        @Output     : delete record
        @Date       : 26-02-2014
    */
    public function ajax_delete_all()
    {
        $id=$this->input->post('single_remove_id');
        $array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
        if(!empty($id))
        {
            $this->obj->delete_record($id);
            unset($id);
        }
        elseif(!empty($array_data))
        {
            $delete_all_flag = 1;
            for($i=0;$i<count($array_data);$i++)
            {
                $cnt++;
                $this->obj->delete_record($array_data[$i]);
            }
        }
        $searchsort_session = $this->session->userdata('cms_sortsearchpage_data');
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
        @Description: Function for Unpublish cms
        @Author     : Sanjay Moghariya
        @Input      : cms id
        @Output     : Unpublish cms
        @Date       : 26-02-2015
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
        $searchsort_session = $this->session->userdata('cms_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }
	
    /*
        @Description: Function for publish cms
        @Author     : Sanjay Moghariya
        @Input      : cms id
        @Output     : Publish cms
        @Date       : 26-02-2015
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
        $searchsort_session = $this->session->userdata('cms_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }
    
    /*
        @Description: Function for slug(page url) exist or not
        @Author     : Mohit Trivedi
        @Input      : slug name
        @Output     : return true/false
        @Date       : 02-03-2015
    */
    public function check_slug()
    {
        $id = $this->input->post('id');
        $domain = $this->input->post('domain_name');
        $page_url = $this->seoUrl($this->input->post('page_url'));
        $fields = array('id');
        $match=array('page_url'=>$page_url,'domain_name'=>$domain);
        
        if(!empty($id))
        {
            $not_where = array('id'=>$id);
            $result = $this->obj->select_records('',$match,'','=','','','','','',$not_where);
        } else {
            $result = $this->obj->select_records('',$match,'','=');
        }
        
        if(!empty($result))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
    }
}