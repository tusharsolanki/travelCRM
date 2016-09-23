<?php 
/*
    @Description: Task controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 02-08-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class task_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('task_model');
		$this->load->model('admin_model');
		$this->load->model('user_management_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('contacts_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('calendar_model');
		$this->obj = $this->task_model;
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
	
	public function task_home()
	{
		//check user right
		check_rights('tasks');
		$data['main_content'] = 'admin/'.$this->viewName."/home";
		$this->load->view('admin/include/template',$data);	
	}

    /*
    @Description: Function for Get All Task List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Task list
    @Date: 02-08-2014
    */
	
    public function index()
    {	
		//check user right
		check_rights('tasks');
				
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('task_sortsearchpage_data');
                }
                $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('task_sortsearchpage_data');
		
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
		$config['base_url'] = site_url($this->user_type.'/'."task/");
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
		$where = array('is_completed'=>"'0'");
		if(!empty($searchtext))
		{
			$match=array('task_name'=>$searchtext,'task_date'=>$searchtext);
			$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			$config['total_rows'] = $this->obj->select_records('',$match,'','like','','','','','',$where,'1');
	
		}
		else
		{
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			
			//echo $this->db->last_query();exit;
			$config['total_rows']= $this->obj->select_records('','','','','','','','','',$where,'1');
			
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
        $task_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

		$this->session->set_userdata('task_sortsearchpage_data', $task_sortsearchpage_data);
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
    @Description: Function Add New Task details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Task details
    @Date: 02-08-2014
    */
    public function add_record()
    {
		//check user right
		check_rights('tasks_add');
		
		$id = $this->uri->segment(4);
		
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."task/search_contact_ajax");
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
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		
		if(!empty($id))
		{
			$match = array('id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			$data['editRecord'] = $result;
			$match1 = array('task_id'=>$id);
			$result1 = $this->obj->select_records1('',$match1,'','=');
			$app = array();
			if(!empty($result1))
			{
				foreach($result1 as $app_manage)
				{
					$app[] = $app_manage['user_id'];
				}
			}
			$data['slt_user']= $app;
			$data['insert_data']=1;
		}
		//new code for admin and user from user master and login master
		
		/*$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		$match2 = array('lm.user_type'=>'2');
		$match3 = array('lm.user_type'=>'3');
		$where=array('lm.status'=>"'1'");
		$data['userlist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where,$match3);*/
		
		$data['userlist'] = $this->obj->get_admin_users_list();
		$data['communication_plans'] = '';
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }

    /*
    @Description: Function for Insert New Task data
    @Author: Mohit Trivedi
    @Input: - Details of new Task which is inserted into DB
    @Output: - List of Task with new inserted records
    @Date: 02-08-2014
    */
    public function insert_data()
    {
		//pr($_POST);exit;
		$cdata['task_name'] = $this->input->post('txt_task_name');
		$cdata['desc'] = $this->input->post('txtarea_desc');
		$cdata['task_date'] = date('Y-m-d',strtotime($this->input->post('txt_task_date')));
		$cdata1['user']=$this->input->post('slt_user');
		if($this->input->post('is_completed'))
			$cdata['is_completed'] = $this->input->post('is_completed');
		$cdata['is_email'] = $this->input->post('is_email');
		if($cdata['is_email']=='1')
		{
			$cdata['email_time_before'] = $this->input->post('email_time_before');
			$cdata['email_time_type'] = $this->input->post('email_time_type');
		}
		$cdata['is_popup'] = $this->input->post('is_popup');
		if($cdata['is_popup']=='1')
		{
			$popup_time_before = $this->input->post('popup_time_before');
			if(!empty($popup_time_before))
			{
				$cdata['popup_time_before'] = $this->input->post('popup_time_before');
			}
			$popup_time_type = $this->input->post('popup_time_type');
			if(!empty($popup_time_type))
			{
				$cdata['popup_time_type'] = $this->input->post('popup_time_type');
			}
		}
		
		
		if(!empty($cdata['is_email']))
		{
			//echo "1";
			if(!empty($cdata['email_time_before']))
			{
				
				if(!empty($cdata['email_time_type']) && $cdata['email_time_type']=='1')
				{
							//echo $cdata['task_date'].$cdata['email_time_before']."<br>";
							$counttype='Hours';
							$newtaskdate = date($this->config->item('log_date_format'),strtotime($cdata['task_date']."- ".$cdata['email_time_before']." ".$counttype));
							$cdata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
							
				}
				if(!empty($cdata['email_time_type']) && $cdata['email_time_type']=='2')
				{
					$counttype='Days';
					$newtaskdate = date($this->config->item('common_date_format'),strtotime($cdata['task_date']."- ".$cdata['email_time_before']." ".$counttype));
					$cdata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
				}	
				
			}
		}
		
		if(!empty($cdata['is_popup']))
		{
				
			if(!empty($cdata['popup_time_before']))
			{
				
				if(!empty($cdata['popup_time_type']) && $cdata['popup_time_type']=='1')
				{
					
					$counttype='Hours';
					$newtaskdate1 = date($this->config->item('log_date_format'),strtotime($cdata['task_date']."- ".$cdata['popup_time_before']." ".$counttype));
					$cdata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	

				}
				if(!empty($cdata['popup_time_type'])&& $cdata['popup_time_type']=='2')
				{
					$counttype='Days';
					$newtaskdate1 = date($this->config->item('common_date_format'),strtotime($cdata['task_date']."- ".$cdata['popup_time_before']." ".$counttype));
					$cdata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	
				}
			}
		}	
		
		$cdata['created_by'] = $this->admin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		
		$task_id = $this->obj->insert_record($cdata);
		$contactdata = $this->input->post('finalcontactlist');
		
		if(!empty($contactdata))
		{
			$sel_user= $this->input->post('slt_user');
			$assign_user='';
			if(!empty($sel_user))
			{
				$assign_user=$sel_user[0];
			}
			$contactdata = explode(',',$contactdata);
			$idata['task_id'] = $task_id;
			$idata['created_by'] = $this->admin_session['id'];
			$idata['assign_to'] = $assign_user;
			$idata['log_type'] = 11;
			for($i=0;$i<count($contactdata);$i++)
			{
				$idata['contact_id'] = $contactdata[$i];
				$idata['created_date'] = date('Y-m-d H:i:s');
				$idata['status'] = '1';
				$this->contact_conversations_trans_model->insert_record($idata);
			}
		}
		
		if(!empty($cdata1['user']))
		{	
			foreach($cdata1['user'] as $id)
			{
				$datac['task_id']=$task_id;
				$datac['user_id'] = $id;
				$datac['status']='1';
				if($this->input->post('is_completed'))
				{$datac['is_completed'] = $this->input->post('is_completed');
				$datac['completed_date'] = date('Y-m-d h:i:s');}
				$this->obj->insert_record1($datac);
				$cdata2['task_id']=$task_id;
				$cdata2['event_inserted_type']='2';
				$cdata2['task_user_id']=$id;
				$cdata2['event_title']=$cdata['task_name'];
				$cdata2['event_notes']=$cdata['desc'];
				$cdata2['start_date']=$cdata['task_date'];
				$cdata2['end_date']=$cdata['task_date'];
				$cdata2['is_all_day']='1';
				$cdata2['is_public']='0';
				$cdata2['is_email']=$cdata['is_email'];
				if($cdata2['is_email']=='1')
				{
					$cdata2['email_time_before']=$cdata['email_time_before'];
					$cdata2['email_time_type']=$cdata['email_time_type'];
				}
				$cdata2['is_popup']=$cdata['is_popup'];
				if($cdata2['is_popup']=='1')
				{
					$cdata2['popup_time_before']=$cdata['popup_time_before'];
					$cdata2['popup_time_type']=$cdata['popup_time_type'];
				}
				$cdata2['is_pop_by']='0';
				$cdata2['is_gift']='0';
				$cdata2['ifRepeat']='0';
				$cdata2['created_by']=$cdata['created_by'];
				$cdata2['created_date']=$cdata['created_date'];
				$cdata2['status']='1';
				$cal_id = $this->obj->insert_record2($cdata2);
				
				$ct_data['calendar_id'] = $cal_id;
				$ct_data['event_start_date']=$cdata['task_date'];
				$ct_data['event_end_date']=$cdata['task_date'];
				$ct_data['event_title']=$cdata['task_name'];
				$ct_data['event_notes']=$cdata['desc'];
                                $ct_data['edit_flag']='1';
				$this->calendar_model->insert_calendar_tran($ct_data);
				$i++;
			}
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $task_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('task_sortsearchpage_data', $task_sortsearchpage_data);
        redirect('admin/'.$this->viewName);
    }
 
    /*
    @Description: Get Details of Edit Task Profile
    @Author: Mohit Trivedi
    @Input: - Id of Task member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 02-08-2014
    */
 
    public function edit_record()
    {
     	//check user right
		check_rights('tasks_edit');
		
		$id = $this->uri->segment(4);
		$table = "contact_conversations_trans as cct";
		$fields = array('cm.id,cct.task_id,cct.contact_id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name');
		$where = array('cct.task_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = cct.contact_id'
						);
		$group_by='cm.id';
		
		$cdata['contacts_data'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','',$where,'=','','','cm.first_name','asc',$group_by);
		
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		if(!empty($result) && $result[0]['is_completed'] == '1')
		{
			$msg = $this->lang->line('common_right_msg_task');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('admin/'.$this->viewName);	
		}
		$cdata['editRecord'] = $result;
		$match1 = array('task_id'=>$id);
		$result1 = $this->obj->select_records1('',$match1,'','=');
		$app = array();
		if(!empty($result1))
		{
			foreach($result1 as $app_manage)
			{
				$app[] = $app_manage['user_id'];
			}
		}
		$cdata['slt_user']= $app;
		//new code for login master and user master table data
		
		/*$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		$match2 = array('lm.user_type'=>'2');
		$match3 = array('lm.user_type'=>'3');
		$where=array('lm.status'=>"'1'");
		$cdata['userlist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where,$match3);*/
		
		$cdata['userlist'] = $this->obj->get_admin_users_list();
		
//new code end
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."task/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "contact_master as cm";
		$fields = array();
		$join_tables = array();
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		$cdata['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
		
		$this->pagination->initialize($config);
		
		$cdata['pagination'] = $this->pagination->create_links();
		
		$match = array();
		$cdata['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$cdata['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$cdata['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Task Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Task
    @Output: - List with updated Task details
    @Date: 02-08-2014
    */
 
    public function update_data()
    {
		//pr($_POST);exit;
	    $cdata['id'] = $this->input->post('id');
		$cdata['task_name'] = $this->input->post('txt_task_name');
		$cdata['desc'] = $this->input->post('txtarea_desc');
		$cdata['task_date'] = date('Y-m-d',strtotime($this->input->post('txt_task_date')));
		$cdata1['new_user']=$this->input->post('slt_user');
		//$cdata['is_completed'] = $this->input->post('is_completed');
		if($this->input->post('is_completed'))
			$cdata['is_completed'] = $this->input->post('is_completed');
		$cdata['is_email'] = $this->input->post('is_email');
		if($cdata['is_email']=='1')
		{
			$cdata['email_time_before'] = $this->input->post('email_time_before');
			$cdata['email_time_type'] = $this->input->post('email_time_type');
		}
		$cdata['is_popup'] = $this->input->post('is_popup');
		if($cdata['is_popup']=='1')
		{
			$popup_time_before = $this->input->post('popup_time_before');
			if(!empty($popup_time_before))
			{
				$cdata['popup_time_before'] = $this->input->post('popup_time_before');
			}
			$popup_time_type = $this->input->post('popup_time_type');
			if(!empty($popup_time_type))
			{
				$cdata['popup_time_type'] = $this->input->post('popup_time_type');
			}
			
		}
		
		if(!empty($cdata['is_email']))
		{
			//echo "1";
			if(!empty($cdata['email_time_before']))
			{
				
				if(!empty($cdata['email_time_type']) && $cdata['email_time_type']=='1')
				{
							//echo $cdata['task_date'].$cdata['email_time_before']."<br>";
							$counttype='Hours';
							$newtaskdate = date($this->config->item('log_date_format'),strtotime($cdata['task_date']."- ".$cdata['email_time_before']." ".$counttype));
							$cdata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
							
				}
				if(!empty($cdata['email_time_type']) && $cdata['email_time_type']=='2')
				{
					$counttype='Days';
					$newtaskdate = date($this->config->item('common_date_format'),strtotime($cdata['task_date']."- ".$cdata['email_time_before']." ".$counttype));
					$cdata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
				}	
				
			}
		}
		
		if(!empty($cdata['is_popup']))
		{
				
			if(!empty($cdata['popup_time_before']))
			{
				
				if(!empty($cdata['popup_time_type']) && $cdata['popup_time_type']=='1')
				{
					
					$counttype='Hours';
					$newtaskdate1 = date($this->config->item('log_date_format'),strtotime($cdata['task_date']."- ".$cdata['popup_time_before']." ".$counttype));
					$cdata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	

				}
				if(!empty($cdata['popup_time_type'])&& $cdata['popup_time_type']=='2')
				{
					$counttype='Days';
					$newtaskdate1 = date($this->config->item('common_date_format'),strtotime($cdata['task_date']."- ".$cdata['popup_time_before']." ".$counttype));
					$cdata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	
				}
			}
		}	
		
		$cdata['modified_by'] = $this->admin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		
		/*$task_contactdata = $this->input->post('finalcontactlist');
		if(!empty($task_contactdata))
		{
			$task_contactdata = explode(',',$task_contactdata);
			$task_id = $cdata['id'];
			$match = array('task_id'=>$task_id);
			$old_task_contacts = $this->contact_conversations_trans_model->select_records('',$match,'','=');
			$task_old_contacts = array();
			$contactdata = $task_contactdata;
			if(count($old_task_contacts) > 0)
			{
				foreach($old_task_contacts as $row)
				{
					$task_old_contacts[] = $row['contact_id'];
				}
				$deletecontactdata = array_diff($task_old_contacts,$task_contactdata);
				//pr($deletecontactdata);exit;
				if(!empty($deletecontactdata))
					$this->contact_conversations_trans_model->delete_contact_trans_record_array($task_id,$deletecontactdata);
			}
			$addcontactdata = array_diff($task_contactdata,$task_old_contacts);
			$idata['task_id'] = $task_id;	
			$idata['created_by'] = $this->admin_session['id'];
			if(count($addcontactdata) > 0)
			{
				foreach($addcontactdata as $row)
				{
					$idata['contact_id'] = $row;
					$idata['created_date'] = date('Y-m-d H:i:s');
					$idata['status'] = '1';
					$this->contact_conversations_trans_model->insert_record($idata);
				}
			}
		}*/
		
		$task_contactdata = $this->input->post('finalcontactlist');
		if($this->input->post('finalcontactlist') != '')
			$task_contactdata = explode(',',$task_contactdata);
		else
			$task_contactdata = array();
		$task_id = $cdata['id'];
		$match = array('task_id'=>$task_id);
		$old_task_contacts = $this->contact_conversations_trans_model->select_records('',$match,'','=');
		$task_old_contacts = array();
		//pr($task_contactdata);exit;
		$contactdata = $task_contactdata;
		if(count($old_task_contacts) > 0)
		{
			foreach($old_task_contacts as $row)
			{
				$task_old_contacts[] = $row['contact_id'];
			}
			$deletecontactdata = array_diff($task_old_contacts,$task_contactdata);
			//pr($deletecontactdata);exit;
			if(!empty($deletecontactdata))
				$this->contact_conversations_trans_model->delete_contact_trans_record_array($task_id,$deletecontactdata);
		}
		$addcontactdata = array_diff($task_contactdata,$task_old_contacts);
		if(count($addcontactdata) > 0)
		{
			$sel_user= $this->input->post('slt_user');
			$assign_user='';
			if(!empty($sel_user))
			{
				$assign_user=$sel_user[0];
			}
			$idata['assign_to'] = $assign_user;
			$idata['log_type'] = 11;
			$idata['task_id'] = $task_id;	
			$idata['created_by'] = $this->admin_session['id'];
			foreach($addcontactdata as $row)
			{
				$idata['contact_id'] = $row;
				$idata['created_date'] = date('Y-m-d H:i:s');
				$idata['status'] = '1';
				$this->contact_conversations_trans_model->insert_record($idata);
			}
		}
		
		$cdata2['task_id']=$cdata['id'];
		$cdata2['event_title']=$cdata['task_name'];
		$cdata2['event_notes']=$cdata['desc'];
		$cdata2['start_date']=$cdata['task_date'];
		$cdata2['end_date']=$cdata['task_date'];
		$cdata2['is_email']=$cdata['is_email'];
		if($cdata2['is_email']=='1')
		{
			$cdata2['email_time_before']=$cdata['email_time_before'];
			$cdata2['email_time_type']=$cdata['email_time_type'];
		}
		$cdata2['is_popup']=$cdata['is_popup'];
		if($cdata2['is_popup']=='1')
		{
			$cdata2['popup_time_before']=$cdata['popup_time_before'];
			$cdata2['popup_time_type']=$cdata['popup_time_type'];
		}
		$cdata2['modified_by']=$cdata['modified_by'];
		$cdata2['modified_date']=$cdata['modified_date'];
		$query=$this->obj->update_record1($cdata2);
		if(!empty($query))
		{
			for($k=0;$k < count($query); $k++)
			{
				$ct_data['calendar_id'] = $query[$k]['id'];
				$ct_data['event_start_date']=$cdata['task_date'];
				$ct_data['event_end_date']=$cdata['task_date'];
				$ct_data['event_title']=$cdata['task_name'];
				$ct_data['event_notes']=$cdata['desc'];
                                $ct_data['edit_flag']='1';
				$this->obj->update_calendar_tran($ct_data);
			}
		}
		
		$field=array('distinct user_id');
		$match1 = array('task_id'=>$cdata['id']);
		$result1 = $this->obj->select_records1($field,$match1,'','=');
		$app = array();
		if(!empty($result1))
		{
			foreach($result1 as $app_manage)
			{
				$app[] = $app_manage['user_id'];
			}
		}
		$cdata['old_user']= $app;
		
		$data['insert']=array_diff ($cdata1['new_user'] ,$cdata ['old_user'] );
		$data['delete']=array_diff ($cdata['old_user'] ,$cdata1['new_user']);
		$iscompleted=$this->input->post('is_completed');
		
		if(empty($data['insert']) && !empty($iscompleted))
		{
			foreach($cdata['old_user'] as $uid)
			{
				$udata['is_completed'] = '1';
				$udata['completed_date'] = date('Y-m-d h:i:s');
				$udata['task_id'] = $cdata['id'];
				$udata['user_id'] = $uid;
				$this->obj->update_task($udata);
			}	
		}
		if($data['delete']!='')
		{
			foreach($data['delete'] as $id)
			{
				//echo 'hi' .$id." new  ".$cdata['id'];
				$datac1['task_id']=$cdata['id'];
				$datac1['user_id'] = $id;
				$this->obj->delete_record1($datac1);
				//for calendar master
				$cdata21['task_id']=$cdata['id'];
				$cdata21['task_user_id']=$id;
				$this->obj->delete_record2($cdata21);
			}
			//exit;
		}
		
		if(!empty($data['insert']))
		{
			foreach($data['insert'] as $row)
			{
				$datac['task_id']=$cdata['id'];
				$datac['user_id'] = $row;
				if($this->input->post('is_completed'))
				{$datac['is_completed'] = $this->input->post('is_completed');}
				$datac['status']='1';
				$this->obj->insert_record1($datac);
				//for calender master
				$cdata2['task_id']=$cdata['id'];	
				$cdata2['event_inserted_type']='2';
				$cdata2['task_user_id']=$row;
				$cdata2['event_title']=$cdata['task_name'];
				$cdata2['event_notes']=$cdata['desc'];
				$cdata2['start_date']=$cdata['task_date'];
				$cdata2['end_date']=$cdata['task_date'];
				$cdata2['is_all_day']='1';
				$cdata2['is_public']='0';
				$cdata2['is_email']=$cdata['is_email'];
				if($cdata2['is_email']=='1')
				{
					$cdata2['email_time_before']=$cdata['email_time_before'];
					$cdata2['email_time_type']=$cdata['email_time_type'];
				}
				$cdata2['is_popup']=$cdata['is_popup'];
				if($cdata2['is_popup']=='1')
				{
					$cdata2['popup_time_before']=$cdata['popup_time_before'];
					$cdata2['popup_time_type']=$cdata['popup_time_type'];
				}
				$cdata2['is_pop_by']='0';
				$cdata2['is_gift']='0';
				$cdata2['ifRepeat']='0';
				$cdata2['created_by'] = $this->admin_session['id'];
				$cdata2['created_date'] = date('Y-m-d H:i:s');		
				$cdata2['status']='1';
				
				$cal_id = $this->obj->insert_record2($cdata2);
				$ct_data['calendar_id'] = $cal_id;
				$ct_data['event_start_date']=$cdata['task_date'];
				$ct_data['event_end_date']=$cdata['task_date'];
				$ct_data['event_title']=$cdata['task_name'];
				$ct_data['event_notes']=$cdata['desc'];
                                $ct_data['edit_flag']='1';
				
				$this->calendar_model->insert_calendar_tran($ct_data);
			}
		}
		//pr($data['delete']);exit;
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		//$task_id = $this->input->post('id');
		//$pagingid = $this->obj->gettaskpagingid($task_id);
        $searchsort_session = $this->session->userdata('task_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect(base_url('admin/'.$this->viewName.'/'.$pagingid));
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
    @Description: Function for Delete Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete all id of Task record want to delete
    @Output: - Task list Empty after record is deleted.
    @Date: 02-08-2014
    */
	
	public function ajax_delete_all()
	{
		
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->obj->delete_user_task($id);
			$this->contact_conversations_trans_model->delete_contact_trans_record($id);
                        
                        $fields = array('id');
                        $match = array("task_id"=>$id);
                        $calid = $this->calendar_model->select_records($fields,$match,'','=');
                        if(!empty($calid))
                        {
                            foreach($calid as $row)
                            {
                                $this->calendar_model->delete_calendar_record($row['id']);
                            }
                        }
                        $this->calendar_model->delete_caltask_record($id);
                        
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$this->obj->delete_user_task($array_data[$i]);
				$this->contact_conversations_trans_model->delete_contact_trans_record($id);
                                
                                $fields = array('id');
                                $match = array("task_id"=>$array_data[$i]);
                                $calid = $this->calendar_model->select_records($fields,$match,'','=');
                                if(!empty($calid))
                                {
                                    foreach($calid as $row)
                                    {
                                        $this->calendar_model->delete_calendar_record($row['id']);
                                    }
                                }
                                $this->calendar_model->delete_caltask_record($array_data[$i]);
                                
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		if($this->input->post('page_name') == 'completed_task')
			$searchsort_session = $this->session->userdata('completed_task_sortsearchpage_data');
		else
			$searchsort_session = $this->session->userdata('task_sortsearchpage_data');
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
//echo 1;
		echo $pagingid;
	}
	
	 /*
    @Description: Function for Unpublish Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete id which Task record want to Unpublish
    @Output: - New Task list after record is Unpublish.
    @Date: 02-08-2014
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
		$task_id =  $id;
		$pagingid = $this->obj->gettaskpagingid($task_id);
		redirect('admin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Delete id which Task record want to publish
    @Output: - New Task list after record is publish.
    @Date: 02-08-2014
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
		$task_id =  $id;
		$pagingid = $this->obj->gettaskpagingid($task_id);
		redirect('admin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Get Details of Task Profile
    @Author: Mohit Trivedi
    @Input: - Id of Task member whose details want to View
    @Output: - Details of Task which id is selected for View
    @Date: 05-08-2014
    */
    public function view_record()
    {
    	//check user right
		check_rights('tasks');
		
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
		$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match1,'','=');
		//pr($data['datalist']);exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match1,'','=','','','','','','','','1');
		//$data['userlist']=$this->user_management_model->select_records();
		$data['main_content'] = "admin/".$this->viewName."/view"; 
	   	$this->load->view("admin/include/template",$data);
    }

	/*
    @Description: Function to change task is completed or not
	@Author: Mohit Trivedi
    @Input: Array
    @Output: - 
    @Date: 05-08-2014
    */
	
	public function iscompleted()
	{
		$admin_id = $this->admin_session['id'];	
		$post_val = $this->input->post('selectedvalue');
		//update user task
		$match=array('task_id'=>$post_val);
		$fields=array('id','task_id','user_id','is_completed');
		$userlist = $this->obj->select_records1($fields,$match,'','=');
		if(!empty($userlist))
		{
			foreach($userlist as $row)
			{
				if(empty($row['is_completed']) && $row['is_completed']== '0')
				{
					$udata['is_completed'] = '1';
					$udata['completed_date'] = date('Y-m-d h:i:s');
					$udata['task_id'] = $post_val;
					$udata['user_id'] = $row['user_id'];
					$this->obj->update_task($udata);
				}
				
			}
				
		}
		
		//Update contact conversation
		$match=array('task_id'=>$post_val);
		$fields=array('id','task_id','contact_id','is_completed_task');
		$contactlist = $this->contact_conversations_trans_model->select_records($fields,$match,'','=');
		if(!empty($contactlist))
		{
			foreach($contactlist as $row)
			{
				if(empty($row['is_completed_task']) && $row['is_completed_task']== '0')
				{
					$cdata['is_completed_task'] = '1';
					$cdata['id'] = $row['id'];
					$this->contact_conversations_trans_model->update_record($cdata);
				}
			}
				
		}
		//update user task
		$match=array('id'=>$post_val);
		$alluser = $this->obj->select_records('',$match,'','=');
		if($post_val!='')
		{
			if(!empty($alluser[0]) && $alluser[0]['is_completed']== '0')
			{
				$data['is_completed'] = '1';
			}
			else
			{
				$data['is_completed'] = '0';
			}
		}
		$data['id'] = $post_val;
		$this->obj->update_record($data);
		$pagid = $this->obj->gettaskpagingid($post_val);
		echo $pagid;
		
	}
	
	public function add_contacts_to_interaction_plan()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	
	public function search_contact_ajax()
    {
	
		$config['per_page'] = 6;	
		$config['base_url'] = site_url($this->user_type.'/'."task/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
	
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
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
		
		//$where = array_merge($where_search,$where);
		//pr($where); exit;
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		//echo $this->db->last_query();exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	function delete_contact_from_task()
	{
		$contact_id[] = $this->input->post('contact_id');
		$task_id = $this->input->post('task_id');
		$this->contact_conversations_trans_model->delete_contact_trans_record_array($task_id,$contact_id);
	}
	
	public function completed_task()
	{
		//check user right
		check_rights('tasks');
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('completed_task_sortsearchpage_data');
                }
                $searchsort_session = $this->session->userdata('completed_task_sortsearchpage_data');
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
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
                            $searchtext =  $data['searchtext'];
							*/
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
		$config['base_url'] = site_url($this->user_type.'/'."task/completed_task");
                $config['is_ajax_paging'] = TRUE; // default FALSE
                $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 4;
		//$uri_segment = $this->uri->segment(4);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
		$where = array('is_completed'=>"'1'");
		if(!empty($searchtext))
		{
			$match=array('task_name'=>$searchtext,'task_date'=>$searchtext);
			$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
			$config['total_rows'] = $this->obj->select_records('',$match,'','like','','','','','',$where,'1');
	
		}
		else
		{
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);	
			$config['total_rows']= $this->obj->select_records('','','','','','','','','',$where,'1');
			
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
        $completed_task_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

		$this->session->set_userdata('completed_task_sortsearchpage_data', $completed_task_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;

		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/completed_task_list";
			$this->load->view('admin/include/template',$data);
		}
	}
        
    /*
        @Description: Function for Copy task details
        @Author     : Sanjay Moghariya
        @Input      : Old task id 
        @Output     : Copy same record and display in listing
        @Date       : 06-10-2014
    */
    public function copy_record()
    {
        $id = $this->uri->segment(4);
        $task_status = $this->uri->segment(5);
        $match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $match1 = array('task_id'=>$id);
        $tut_result = $this->obj->select_records1('',$match1,'','=');
        $cct_result = $this->contact_conversations_trans_model->select_records('',$match1,'','=');
        $cm_result = $this->calendar_model->select_records('',$match1,'','=');
        //pr($result);exit;
		if(!empty($result))
        {
            $cdata['task_name'] = $result[0]['task_name'].'-copy';
            $cdata['desc'] = $result[0]['desc'];
            $cdata['task_date'] = date('Y-m-d',strtotime($result[0]['task_date']));
            $cdata['is_email'] = $result[0]['is_email'];
            $cdata['email_time_before'] = $result[0]['email_time_before'];
            $cdata['email_time_type'] = $result[0]['email_time_type'];
            if(!empty($result[0]['reminder_email_date']) && $result[0]['reminder_email_date'] != '0000-00-00 00:00:00')
                $cdata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($result[0]['reminder_email_date']));
            $cdata['is_popup'] = $result[0]['is_popup'];
            $cdata['popup_time_before'] = $result[0]['popup_time_before'];
            $cdata['popup_time_type'] = $result[0]['popup_time_type'];
            if(!empty($result[0]['reminder_popup_date']) && $result[0]['reminder_popup_date'] != '0000-00-00 00:00:00')
                $cdata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($result[0]['reminder_popup_date']));
            $cdata['is_close'] = $result[0]['is_close'];
            $cdata['is_completed'] = $result[0]['is_completed'];
            $cdata['created_by'] = $this->admin_session['id'];
            $cdata['created_date'] = date('Y-m-d H:i:s');		
            $cdata['status'] = '1';
            $task_id = $this->obj->insert_record($cdata);
        }
        if($task_id > 0)
        {
            if(!empty($tut_result))
            {
                foreach($tut_result as $app_manage)
                {
                    $tut_data['task_id'] = $task_id;
                    $tut_data['user_id'] = $app_manage['user_id'];
                    $tut_data['is_completed'] = $app_manage['is_completed'];
                    $tut_data['is_close'] = $app_manage['is_close'];
                    $tut_data['completed_by'] = $app_manage['completed_by'];
                    if(!empty($app_manage['completed_date']) && $app_manage['completed_date'] != '0000-00-00 00:00:00')
                        $tut_data['completed_date'] = date('Y-m-d H:i:s',strtotime($app_manage['completed_date']));
                    $tut_data['status'] = '1';
                    $this->obj->insert_record1($tut_data);
                }
            }
            if(!empty($cct_result))
            {
                $cct_data['task_id'] = $task_id;
                $cct_data['created_by'] = $this->admin_session['id'];
                $cct_data['log_type'] = 11;
                $cct_data['status'] = '1';
                foreach($cct_result as $row)
                {
                    $cct_data['contact_id'] = $row['contact_id'];
                    $cct_data['created_date'] = date('Y-m-d H:i:s');
                    $this->contact_conversations_trans_model->insert_record($cct_data);
                }
            }
            //pr($cm_result); exit;
            if(!empty($cm_result))
            {
                $cm_data['task_id']=$task_id;
                $cm_data['event_inserted_type']='2';
                $cm_data['is_pop_by']='0';
                $cm_data['is_gift']='0';
                $cm_data['ifRepeat']='0';
                $cm_data['created_by']=$this->admin_session['id'];
                $cm_data['status']='1';
                foreach($cm_result as $row)
                {
                    $cm_data['task_user_id']=$row['task_user_id'];
                    $cm_data['event_title']=$row['event_title'];
                    $cm_data['event_notes']=$row['event_notes'];
                    $cm_data['start_date']=$row['start_date'];
                    $cm_data['end_date']=$row['end_date'];
                    $cm_data['is_all_day']=$row['is_all_day'];
                    $cm_data['is_public']=$row['is_public'];
                    $cm_data['is_email']=$row['is_email'];
                    $cm_data['email_time_before']=$row['email_time_before'];
                    $cm_data['email_time_type']=$row['email_time_type'];
                    $cm_data['is_popup']=$row['is_popup'];
                    $cm_data['popup_time_before']=$row['popup_time_before'];
                    $cm_data['popup_time_type']=$row['popup_time_type'];
                    $cm_data['created_date']=date('Y-m-d H:i:s');
                    $cal_id = $this->obj->insert_record2($cm_data);
                    
                    $ct_data['calendar_id'] = $cal_id;
                    $ct_data['event_start_date']=$row['start_date'];
                    $ct_data['event_end_date']=$row['end_date'];
                    $ct_data['event_title']=$row['event_title'];
                    $ct_data['event_notes']=$row['event_notes'];
                    $this->calendar_model->insert_calendar_tran($ct_data);
                }
            }
        }
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $task_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
         
        if(!empty($task_status))
        {
            $this->session->set_userdata('task_sortsearchpage_data', $task_sortsearchpage_data);
            redirect('admin/'.$this->viewName);
        } else {
            $this->session->set_userdata('task_completed_sortsearchpage_data', $task_sortsearchpage_data);
            redirect('admin/'.$this->viewName.'/completed_task');
        }
        
    }
	
}