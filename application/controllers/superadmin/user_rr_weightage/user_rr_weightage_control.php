<?php 
/*
    @Description: contacts controller
    @Author: Sanjay Moghariya
    @Input: 
    @Output: 
    @Date: 24-12-2014
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_rr_weightage_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        redirect(base_url('superadmin/dashboard'));
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('user_management_model');
        $this->load->model('Common_function_model');

        $this->obj = $this->user_management_model;
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';
    }
	
    /*
        @Description: Function for Get All agent weightage
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : Agent Weightage list
        @Date       : 24-12-2014
    */
    public function index()
    {	
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
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
/*                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];*/
					
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];
                }
            }
        }
        if(!empty($searchopt))
        {
            //$searchopt = $this->input->post('searchopt');
            $data['searchopt'] = $searchopt;
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
        
        
        /*$table = "user_master as um";
        $fields = array('um.id,um.user_weightage,lm.email_id,lm.user_id');
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $where = array('um.status'=> '1','um.user_type'=>'3');
        $rr_user_list = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','user_weightage','desc');
        */
        /*if(!empty($searchtext))
        {
            $match=array('first_name'=>$searchtext,'last_name'=>$searchtext,'user_weightage'=>$searchtext,'minimum_price'=>$searchtext,'maximum_price'=>$searchtext);            
            $data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config['total_rows'] = count($this->obj->select_records('',$match,'','like','','','','','',$where));

        }
        else
        {
            $data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
            $config['total_rows']= count($this->obj->select_records('','','','','','','','','',$where));
        }*/
        
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,lm.email_id');
        $where = array('um.status'=> '1','um.user_type'=>'3');
        if(!empty($searchtext))
        {
            $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
            $match=array('CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name)'=>$searchtext,'CONCAT_WS(" ",um.first_name,um.last_name)'=>$searchtext,'um.user_weightage'=>$searchtext,'um.minimum_price'=>$searchtext,'um.maximum_price'=>$searchtext);
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','','1');
        }
        else
        {
            $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
            $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','','1');
        }
        
        //pr($data['datalist']);exit;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $agent_rr_weightage_sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby' =>$data['sortby'],
            'searchtext' =>$data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('agent_rr_weightage_sortsearchpage_data', $agent_rr_weightage_sortsearchpage_data);
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
        @Description: Function Add weightage and price range
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     : Load Form for add contacts details
        @Date       : 24-12-2014
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
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,lm.email_id');
        $where = array('um.status'=> '1','um.user_type'=>'3');
        
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','id','desc',$group_by,$where);
        $data['edit_single'] = '0';
        $data['main_content'] =  $this->user_type.'/'.$this->viewName."/add";
        $this->load->view('superadmin/include/template',$data);	
    }

    /*
        @Description: Function for insert/update agent weightage
        @Author     : Sanjay Moghariya
        @Input      : Agent weightage data
        @Output     : List of agent weightage
        @Date       : 24-12-2014
    */
    public function update_data()
    {
        $is_edit_single = $this->input->post('is_edit_single');
        if(!empty($is_edit_single))
        {
            $data = array();
            $user_id = $this->input->post('suser_id');
            $weightage = $this->input->post('sweightage');
            $min_price = $this->input->post('smin_price');
            $max_price = $this->input->post('smax_price');
            
            $data['id'] = $user_id;
            $data['user_weightage'] = $weightage;
            $data['minimum_price'] = str_replace(',', '', $min_price);
            $data['maximum_price'] = str_replace(',', '', $max_price);
            $this->obj->update_record($data);
            
            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            $searchsort_session = $this->session->userdata('agent_rr_weightage_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
        }
        else
        {
            $data = array();
            $user_id = $this->input->post('user_id');
            $weightage = $this->input->post('weightage');
            $min_price = $this->input->post('min_price');
            $max_price = $this->input->post('max_price');


            $data['modified_by'] = $this->superadmin_session['id'];
            $data['modified_date'] = date('Y-m-d H:i:s');

            if(!empty($user_id))
            {
                for($i=0;$i<count($user_id);$i++)
                {
                    $data['id'] = $user_id[$i];
                    $data['user_weightage'] = $weightage[$i];
                    $data['minimum_price'] = str_replace(',', '', $min_price[$i]);
                    $data['maximum_price'] = str_replace(',', '', $max_price[$i]);
                    //$data['minimum_price'] = $min_price[$i];
                    //$data['maximum_price'] = $max_price[$i];
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
            redirect('superadmin/'.$this->viewName);
        }
    }
    
    /*
        @Description: Get edit form
        @Author     : Sanjay Moghariya
        @Input      : user weightage id
        @Output     : weightage details
        @Date       : 24-12-2014
    */
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
        $table = "user_master as um";
        $group_by='um.id';
        $fields = array('um.id,','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as agent_name,um.user_weightage,um.minimum_price,um.maximum_price,lm.email_id');
        $where = array('um.status'=> '1','um.user_type'=>'3','um.id'=>$id);
        
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $cdata['editRecord'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','id','desc',$group_by,$where);
        $cdata['edit_single'] = '1';
        $cdata['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$cdata);
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
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
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
		
	   
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
}