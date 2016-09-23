<?php 
/*
	@Description: State controller
	@Author     : Sanjay Moghariya
	@Input      : 
	@Output     : 
	@Date       : 26-12-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class state_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('state_model');
        $this->load->model('country_model');
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';

    }
	
    /*
        @Description: Function for Get state list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : state list
        @Date       : 26-12-2014
    */
    public function index()
    {	
        $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('state_sortsearchpage_data');
        }
        $data['sortfield'] = 'id';
        $data['sortby']	= 'desc';
        $searchsort_session = $this->session->userdata('state_sortsearchpage_data');

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
            $data['searchtext'] = $searchtext;
        } else {
            if(empty($allflag))
            {
                if(!empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext =  $data['searchtext'];
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

        $config['base_url'] = site_url($this->user_type.'/'."state/");
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

        
        $table = "state as st";
        $group_by = "st.id";
        $fields = array('st.*','con.country');
        $join_tables = array(
            'country as con'=>'st.country_id = con.id',
        );
        
        if(!empty($searchtext))
        {
            $match=array('con.country'=>$searchtext,'ct.name'=>$searchtext);
            $data['datalist'] =$this->state_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by);
            $config['total_rows'] = count($this->state_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by));
        }
        else
        {
            $data['datalist'] =$this->state_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by);
            $config['total_rows'] = count($this->state_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by));
        }
        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $state_sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby' =>$data['sortby'],
            'searchtext' =>$data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('state_sortsearchpage_data', $state_sortsearchpage_data);
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
        @Description: Function Add state
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     : Load Form for add state
        @Date       : 26-12-2014
    */
    public function add_record()
    {
        $data['country'] = $this->country_model->select_records();
        $data['msg'] = $this->message_session['msg'];
        $con_session = $this->session->userdata('state_add_session');
        $data['st_name'] = !empty($con_session['st_name'])?$con_session['st_name']:'';
        $data['con_id'] = !empty($con_session['con_id'])?$con_session['con_id']:'';
        $data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
        @Description: Function for Insert state
        @Author     : Sanjay Moghariya
        @Input      : Details of state which is inserted into DB
        @Output     : List of state with new inserted records
        @Date       : 26-12-2014
    */
    public function insert_data()
    {
        $cdata['name'] = $this->input->post('state');
        $cdata['country_id'] = $this->input->post('country');
        $fields = array('id');
        $match = array('name'=>$cdata['name'],'country_id'=>$cdata['country_id']);
        $get_con = $this->state_model->select_records($fields,$match,'AND','=');
        if(!empty($get_con)) {
            $msg = $this->lang->line('common_already_exists_state_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            
            $newdata = array('con_id' => $cdata['country_id'],'st_name' => $cdata['name']);
            $this->session->set_userdata('state_add_session', $newdata);
            redirect(base_url().'superadmin/'.$this->viewName.'/add_record');
        } else {
            $lastId=$this->state_model->insert_record($cdata);		
            $msg = $this->lang->line('common_add_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);	
            redirect(base_url().'superadmin/'.$this->viewName);
        }
     }
 
    /*
        @Description: Get Details of Edit state
        @Author     : Sanjay Moghariya
        @Input      : state id
        @Output     : state details
        @Date       : 26-12-2014
    */
    public function edit_record()
    {
        $id = $this->uri->segment(4);
        $match = array('id'=>$id);
        $result = $this->state_model->select_records('',$match,'','=');
        $cdata['editRecord'] = $result;
        $cdata['country'] = $this->country_model->select_records();
        $cdata['msg'] = $this->message_session['msg'];
        $cdata['main_content'] = "superadmin/".$this->viewName."/add";       
        $this->load->view("superadmin/include/template",$cdata);
    }

    /*
        @Description: Function for Update Admin Profile
        @Author: Sanjay Moghariya
        @Input: - Update details of Admin
        @Output: - List with updated Admin details
        @Date: 01-09-2014
    */
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
        $cdata['name'] = $this->input->post('state');
        $cdata['country_id'] = $this->input->post('country');
        $fields = array('id');
        $match = array('name'=>$cdata['name'],'country_id'=>$cdata['country_id']);
        $and_match = array('id'=>$cdata['id']);
        $get_con= $this->state_model->select_records('',$match,'AND','=','','','','','','',$and_match);
        //echo $this->db->last_query();exit;
        if(!empty($get_con)) {
            $msg = $this->lang->line('common_already_exists_state_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);
            redirect(base_url().'superadmin/'.$this->viewName.'/edit_record/'.$cdata['id']);
        } else {
            $this->state_model->update_record($cdata);

            $msg = $this->lang->line('common_edit_success_msg');
            $newdata = array('msg'  => $msg);
            $this->session->set_userdata('message_session', $newdata);

            $searchsort_session = $this->session->userdata('state_sortsearchpage_data');
            $pagingid = $searchsort_session['uri_segment'];
            redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
        }
    }
	
    /*
        @Description: Function for Delete state
        @Author     : Sanjay Moghariya
        @Input      : state id
        @Output     : Delete record
        @Date       : 26-12-2014
    */
    public function ajax_delete_all()
    {
        $id=$this->input->post('single_remove_id');
        $array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
        if(!empty($id))
        {
            $this->state_model->delete_record($id);
            unset($id);
        }
        elseif(!empty($array_data))
        {
            for($i=0;$i<count($array_data);$i++)
            {
                $this->state_model->delete_record($array_data[$i]);
                $delete_all_flag = 1;
                $cnt++;
            }
        }
        $searchsort_session = $this->session->userdata('state_sortsearchpage_data');
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
        @Description: Function for Unpublish state
        @Author     : Sanjay Moghariya
        @Input      : state id
        @Output     : state list after record is Unpublish.
        @Date       : 26-12-2014
    */
    function unpublish_record()
    {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = '0';
        $this->state_model->update_record($cdata);
        $msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('state_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Admin Profile By Superadmin
    @Author: Sanjay Moghariya
    @Input: - Delete id which Admin record want to publish
    @Output: - New Admin post list after record is publish.
    @Date: 01-09-2014
    */

    function publish_record()
    {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = '1';
        $this->state_model->update_record($cdata);
        $msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('state_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
    
    /*
        @Description: Function for check state name already exist
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     :  
        @Date       : 26-12-2014
    */
    public function check_state()
    {
        $id = $this->input->post('id');
        $state_name=$this->input->post('state_name');
        $country_id=$this->input->post('country_id');
        if(!empty($id))
        {
            $match=array('name'=>$state_name,'country_id'=>$country_id);
            $and_match = array('id'=>$id);
            $exist_state= $this->state_model->select_records('',$match,'AND','=','','','','','','',$and_match);
            if(!empty($exist_state))
                echo '1';
            else
                echo '0';
        }
        else {
            $match=array('name'=>$state_name,'country_id'=>$country_id);
            $exist_state= $this->state_model->select_records('',$match,'','=');
            if(!empty($exist_state))
            {
                echo '1';
            }
            else
            {
                echo '0';
            }
        }
        
    }
}