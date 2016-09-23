<?php 
/*
    @Description: Assign Plan to joomla controller
    @Author     : Sanjay Moghariya
    @Input      : 
    @Output     : 
    @Date       : 27-12-2014
	
*/
?>
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class joomla_assign_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
        check_rights('communications');
        $result = check_joomla_tab_setting($this->user_session['id']);
        if(!empty($result) && $result[0]['lead_dashboard_tab'] == '0')
            redirect('user/dashboard');
        $this->load->model('joomla_assign_model');
        $this->load->model('interaction_plans_model');        
		$this->load->model('contacts_model');        
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'user';
    }
	
    /*
        @Description: Function for call home page for assign communication
        @Author     : Sanjay Moghariya
        @Input      :  
        @Output     :  
        @Date       : 23-12-2014
    */
    public function joomla_assign_home()
    {
       	//check user right
		check_rights('auto_communication');
	    $data['main_content'] = 'user/'.$this->viewName."/home";
        $this->load->view('user/include/template',$data);	
    }
    
    /*
        @Description: Function for all lead plan assign list
        @Author     : Sanjay Moghariya
        @Input      : Search value or null
        @Output     : all Lead Capturing list
        @Date       : 15-11-2014
    */
    public function index()
    {	
        //check user right
		check_rights('auto_communication');
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('joomla_assign_sortsearchpage_data');
        }
        $data['sortfield']		= 'jlps.id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('joomla_assign_sortsearchpage_data');

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
                $sortfield = 'jlps.id';
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

        $config['base_url'] = site_url($this->user_type.'/'."joomla_assign/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        
        if((!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch'))) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        $table = " joomla_leads_plan_assign as jlps";
        //$wherestring = array('cm.created_type'=>'6');
        $fields = array('jlps.*','ipm.plan_name');
        $join_tables = array(
            'interaction_plan_master as ipm'=>'jlps.interaction_plan_id = ipm.id',
        );
        $wherestr = array('jlps.created_by'=>$this->user_session['id']);
        if(!empty($searchtext))
        {
            //$match=array('jlps.min_price'=>$searchtext,'jlps.max_price'=>$searchtext,'ipm.plan_name'=>$searchtext,'jlps.prospect_type'=>$searchtext,'jlps.status'=>$searchtext);
            $match=array('ipm.plan_name'=>$searchtext,'jlps.prospect_type'=>$searchtext,'jlps.status'=>$searchtext);
            $data['datalist'] =$this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$wherestr);
            $config['total_rows'] = count($this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$wherestr));
        }
        else
        {
            $data['datalist'] =$this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],'',$wherestr);
            $config['total_rows'] = count($this->joomla_assign_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','','',$wherestr));
        }
        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $joomla_assign_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
        $this->session->set_userdata('joomla_assign_sortsearchpage_data', $joomla_assign_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        
        if($this->input->post('result_type') == 'ajax')
        {
            $this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
        }
        else
        {
            $data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
            $this->load->view('user/include/template',$data);
        }
    }

    /*
        @Description: Function for add new plan to leads
        @Author     : Sanjay Moghariya
        @Input      : 
        @Output     : Add form
        @Date       : 15-11-2014
    */
    public function add_record()
    {
		//check user right
		check_rights('auto_communication_add');
        //echo"Ami";exit;
   /*     $match = array('status'=>1);//,'assign_to '=>$this->user_session['id']);
        $result=$this->interaction_plans_model->select_records('',$match,'','=');
        $data['plan']=$result;*/
	//	pr($data['plan']);
		$table = " interaction_plan_master as ipm ";
			$fields = array('ipm.id','ipm.plan_status','ipm.*','ipim.assign_to');
			$join_tables = array(
								'interaction_plan__status_master as csm' 		=> 'csm.id = ipm.plan_status',
								'interaction_plan_interaction_master as ipim' 	=> 'ipim.interaction_plan_id = ipm.id',
								'login_master as lm'                            => 'lm.id = ipm.created_by',
								'user_master as um'                             => 'um.id = lm.user_id'
							);
			$group_by='ipm.id';
			$status_value='1';
			$match=array('ipm.status'=>$status_value);
			$wherestring='(ipim.assign_to = '.$this->user_session['id'].' OR ipm.created_by = '.$this->user_session['id'].')';
			$data['plan'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by,$wherestring);
	//	pr($data['plan']);exit;
        $data['main_content'] =  $this->user_type.'/'.$this->viewName."/add";
        $this->load->view('user/include/template',$data);
    }

    /*
        @Description: Function for Insert new lead plan
        @Author     : Sanjay Moghariya
        @Input      : Insert record details
        @Output     : List with new inserted record
        @Date       : 15-11-2014
    */
    public function insert_data()
    {
        /*$min_price = $this->input->post('min_price'); Remove in phase 1
        $max_price = $this->input->post('max_price');
        $cdata['min_price'] = str_replace(',', '', $min_price);
        $cdata['max_price'] = str_replace(',', '', $max_price);
         */
        $cdata['interaction_plan_id'] = $this->input->post('assigned_interaction_plan_id');
        $cdata['prospect_type'] = $this->input->post('prospect_type');
        $cdata['created_by'] = $this->user_session['id'];
        $cdata['created_date'] = date('Y-m-d H:i:s');
        $cdata['status'] = $this->input->post('plan_status');
        $this->joomla_assign_model->insert_record($cdata);	
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $joomla_assign_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('joomla_assign_sortsearchpage_data', $joomla_assign_sortsearchpage_data);
        redirect('user/'.$this->viewName);
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
		check_rights('auto_communication_edit');
		$id = $this->uri->segment(4);
        $match = array('id'=>$id);
        $result = $this->joomla_assign_model->select_records('',$match,'','=');
        $cdata['editRecord'] = $result;
        $match1 = array('status'=>1);
        $result1=$this->interaction_plans_model->select_records('',$match1,'','=');
        $cdata['plan']=$result1;
        $cdata['main_content'] = "user/".$this->viewName."/add";       
        $this->load->view("user/include/template",$cdata);
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
        $cdata['id'] = $this->input->post('id');
        $cdata['interaction_plan_id'] = $this->input->post('assigned_interaction_plan_id');
        $cdata['prospect_type'] = $this->input->post('prospect_type');
        /*$min_price = $this->input->post('min_price'); Remove in phase1
        $max_price = $this->input->post('max_price');
        $cdata['min_price'] = str_replace(',', '', $min_price);
        $cdata['max_price'] = str_replace(',', '', $max_price);*/
        $cdata['modified_by'] = $this->user_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $cdata['status'] = $this->input->post('plan_status');
        //pr($cdata);exit;
        $this->joomla_assign_model->update_record($cdata);
        
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('joomla_assign_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
        //redirect(base_url('user/'.$this->viewName));
		
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
		check_rights('auto_communication_delete');
		$id = $this->uri->segment(4);
        $this->joomla_assign_model->delete_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
    }
	
    /*
        @Description: Function for Delete Lead Capturing Profile By Admin
        @Author     : Mohit Trivedi
        @Input      : Delete all id of Lead Capturing record want to delete
        @Output     : Lead Capturing list Empty after record is deleted.
        @Date       : 19-09-2014
    */
	
    public function ajax_delete_all()
    {
        $id=$this->input->post('single_remove_id');
        if(!empty($id))
        {
                $this->joomla_assign_model->delete_record($id);
                unset($id);
        }
        $array_data=$this->input->post('myarray');
        for($i=0;$i<count($array_data);$i++)
        {
                $this->joomla_assign_model->delete_record($array_data[$i]);
        }
        $searchsort_session = $this->session->userdata('joomla_assign_sortsearchpage_data');
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
		check_rights('auto_communication');
		$id = $this->uri->segment(4);
		$match = array('form_id'=>$id);
		$result=$this->obj->select_records1('',$match,'','=');
		$data['formdata']=$result;
		$match1 = array('id'=>$id);
		$result1=$this->obj->select_records('',$match1,'','=');
		$data['form']=$result1;
		$data['main_content'] = "user/".$this->viewName."/view";
        $this->load->view('user/include/template', $data);
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
        @Description: Function for change plan status (Assign plan to joomla leads status)
        @Author     : Sanjay Moghariya
        @Input      : plan id, status
        @Output     : Update plan status
        @Date       : 27-12-2014
    */
    public function change_plan_status()
    {
        $plan_id=$this->input->post('plan_id');
        $status = $this->input->post('status');
        $data['id'] = $plan_id;
        $data['status'] = $status;
        $data['modified_date'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user_session['id'];
        $this->joomla_assign_model->update_record($data);

        ////// Code for redirect on current page once action is done 
        $searchsort_session = $this->session->userdata('joomla_assign_sortsearchpage_data');
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;

        echo $pagingid;
    }
}