<?php 
/*
    @Description: SMS Response controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 06-01-2015
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sms_response_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('sms_response_model');
		$this->obj = $this->sms_response_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	
	/*
		@Description: Function for Module All details view.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 01-01-2015
    */
	
	public function sms_home()
	{
		$data['main_content'] = 'admin/'.$this->viewName."/home";
		$this->load->view('admin/include/template',$data);	
	}

    /*
    @Description: Function for Get All Task List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all Task list
    @Date: 06-01-2015
    */
	
    public function index()
    {	
		//check user right
		check_rights('text_response');		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('sms_sortsearchpage_data');
                }
                $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('sms_sortsearchpage_data');
		
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
/**/		} else {
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
		if(!empty($searchopt))
		{
			//$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($date1) && !empty($date2))
		{
                    $date1 = $this->input->post('date1');
                    $date2 = $this->input->post('date2');
                    $data['date1'] = $date1;
                    $data['date2'] = $date2;	
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
		$config['base_url'] = site_url($this->user_type.'/'."sms_response/");
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
		//$where = array('is_completed'=>"'0'");
		if(!empty($searchtext))
		{
			$match=array('from_number'=>$searchtext,'to_number'=>$searchtext,'message'=>$searchtext,'from_city'=>$searchtext);
			$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,'');
			$config['total_rows'] = $this->obj->select_records('',$match,'','like','','','','','','','1');
	
		}
		else
		{
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'');
			$config['total_rows']= $this->obj->select_records('','','','','','','','','','','1');
			
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
        $sms_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
		$this->session->set_userdata('sms_sortsearchpage_data', $sms_sortsearchpage_data);
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
    @Description: Function for Delete Task Profile By Admin
    @Author: Niral Patel
    @Input: - Delete id which Task record want to delete
    @Output: - New Task list after record is deleted.
    @Date: 06-01-2015
    */
    function delete_record()
    {
		//check user right
		check_rights('text_response_delete');	
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	
	 /*
    @Description: Function for Delete Task Profile By Admin
    @Author: Niral Patel
    @Input: - Delete all id of Task record want to delete
    @Output: - Task list Empty after record is deleted.
    @Date: 06-01-2015
    */
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		
        $delete_all_flag = 0;$cnt = 0;
		//exit;
		if(!empty($id))
		{			
			$this->obj->delete_record($id);
			//$this->social_recepient_trans_model->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			$id = $array_data[0];
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				//$this->social_recepient_trans_model->delete_record($array_data[$i]);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		//$pagingid = $this->obj->getpagingid($id);
		$searchsort_session = $this->session->userdata('sms_sortsearchpage_data');
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
    @Description: Get Details of Task Profile
    @Author: Niral Patel
    @Input: - Id of Task member whose details want to View
    @Output: - Details of Task which id is selected for View
    @Date: 05-08-2014
    */
    public function view_record()
    {
		//check user right
		check_rights('text_response');		
    	$id = $this->uri->segment(4);
		
		////////////////////////////////////////
		
		$table = "contact_conversations_trans as cct";
		$fields = array('cm.id,cct.task_id,cct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('cct.task_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = cct.contact_id'
						);
		$group_by='cm.id';
		
		$data['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		///////////////////////////////////////
		
		$match = array('id'=>$id);
		$result = $this->obj->select_records('',$match,'','=');
        $data['editRecord'] = $result;
		$match1=array('tm.id'=>$id);
		$table ='task_master as tm';   
		$fields = array('tm.id','tu.task_id','tu.is_completed','lm.admin_name','tu.user_id','lm.status','lm.user_type','lm.user_id','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array("task_user_transcation tu"=>'tm.id=tu.task_id',
							 "login_master as lm"=>	'tu.user_id = lm.id',
							 "user_master as um" => 'lm.user_id = um.id',
							 );
		$group_by = array('tm.id');
		$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match1,'','=','',$uri_segment,'','','');
		//pr($data['datalist']);exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match1,'','=','','','','','','','','1');
		//$data['userlist']=$this->user_management_model->select_records();
		$data['main_content'] = "admin/".$this->viewName."/view"; 
	   	$this->load->view("admin/include/template",$data);
    }
	
}