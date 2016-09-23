<?php
/*
    @Description: Interaction controller
    @Author: Kaushik valiya
    @Input: 
    @Output: 
    @Date: 18-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class default_interaction_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('interaction_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('contact_masters_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('sms_texts_model');
		$this->load->model('phonecall_script_model');
		$this->load->model('envelope_library_model');
		$this->load->model('label_library_model');
		$this->load->model('letter_library_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('user_management_model');
		$this->load->model('email_signature_model');
		$this->load->model('sms_campaign_recepient_trans_model');
		//$this->load->model('imageupload_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('imageupload_model');
		$this->load->model('interaction_premium_model');
		$this->load->model('task_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('interaction_plans_premium_model');
		
		
		$this->obj = $this->interaction_premium_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';
    }
	

    /*
    @Description: Function for Get All contacts List
    @Author: Nishit Modi
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
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
                    $this->session->unset_userdata('default_interaction_sortsearchpage_data');
                }
                $data['sortfield']		= 'ipim.interaction_sequence_date';
		$data['sortby']			= 'asc';
                $searchsort_session = $this->session->userdata('default_interaction_sortsearchpage_data');
		
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
                        }
                    } else {
                        $sortfield = 'ipim.interaction_sequence_date';
			$sortby = 'asc';
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
		$plan_id = $this->uri->segment(3);
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction/".$plan_id);
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
		
		$table = "interaction_plan_interaction_master_premium as ipim";
		$fields = array('ipim.*','ipptm.name','ipm.description as interaction_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as contact_name','count(DISTINCT ipct.contact_id) as contact_counter,iplanm.status as plan_status,lom.admin_name');
		
		$join_tables = array(
								'interaction_plan_master_premium as iplanm' 	=> 'iplanm.id = ipim.interaction_plan_id',
								'interaction_plan__plan_type_master as ipptm' 	=> 'ipptm.id = ipim.interaction_type',
								//'superadmin_users as au' 							=> 'au.id = ipim.created_by',
								'interaction_plan_interaction_master_premium as ipm' 	=> 'ipm.id = ipim.interaction_id',
								'login_master as lom' 							=> 'lom.id = ipim.assign_to',
								'user_master as um' 							=> 'um.id = lom.user_id',
								'interaction_plan_contacts_trans ipct' 			=> 'ipct.interaction_plan_id = ipim.interaction_plan_id',
								'contact_master as cm' 							=> 'cm.id = ipct.contact_id'
							);
		
		$group_by='ipim.id';
		$status_value='1';
		if(!empty($searchtext))
		{
			//pr($searchtext);exit;
			$match=array('ipim.description'=>$searchtext);
			$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'like','','','','',$group_by,$where1,'','1');
		}
		else
		{
			//echo $data['sortfield']."-".$data['sortby'];
			$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'','1');
			
			$match=array('id'=>$plan_id);
			$data['list'] =$this->interaction_plans_premium_model->select_records('',$match,'','=');
				
		}
			
		$result_contact = $this->obj->current_interaction_plan($plan_id);
		$count_contact = count($result_contact);
		$interaction_plan = array();
		$contact_list = array();
		$contact_data = array();
		$interaction_id = '';
		$i = 1;
		
		//pr($result_contact); exit;
		foreach($result_contact as $row)
		{
			//echo $row['contact_id']."<br>";
			if($interaction_id == $row['interaction_plan_interaction_id'])
			{
				if($row['is_done'] == '0' && !in_array($row['contact_id'],$contact_data))
				{
					$new_contact[] = $row['contact_id'];
				}
				if($count_contact == $i)
				{
					$total_count = array_diff($new_contact,$contact_data);
					if(!empty($total_count))
						$contact_list[$interaction_id] = implode(",",$total_count);
					else
						$contact_list[$interaction_id] = "";
					$interaction_plan[$interaction_id] = count($total_count);
				}
			}
			else
			{
				if(!empty($interaction_id) && $interaction_id != $row['interaction_plan_interaction_id'])
				{
					$total_count = array_diff($new_contact,$contact_data);
					if(!empty($total_count))
						$contact_list[$interaction_id] = implode(",",$total_count);
					else
						$contact_list[$interaction_id] = "";
					$interaction_plan[$interaction_id] = count($total_count);
					$contact_data = array_merge($contact_data,$total_count);
				}
				$new_contact = array();
				//echo $row['is_done'];
				if($row['is_done'] == '0')
				{
					$new_contact[] = $row['contact_id'];
					//$contact_data[] = $row['contact_id'];
				}
				$interaction_id = $row['interaction_plan_interaction_id'];
				if($count_contact == $i)
				{
					if($row['is_done'] == '0' && !in_array($row['contact_id'],$contact_data))
					{
						$interaction_plan[$interaction_id] = 1;
						$contact_list[$interaction_id] = $row['contact_id'];
					}
					else
					{
						$interaction_plan[$interaction_id] = 0;
						$contact_list[$interaction_id] = "";
					}
				}
				
			}
			$i++;
		}
		//pr($interaction_plan);
		$data['interaction_plan'] = $interaction_plan;
		$data['contact_list'] = $contact_list;
		//pr($data['interaction_plan']);//exit;
		//pr($data['contact_list']);exit;
		
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
                $default_interaction_sortsearchpage_data = array(
                    'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
                $this->session->set_userdata('default_interaction_sortsearchpage_data',$default_interaction_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
				$searchsort_session = $this->session->userdata('default_interaction_sortsearchpage_data');
		
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
		@Description: Function for Get All contacts List
		@Author: Nishit Modi
		@Input: - Search value or null
		@Output: - all contacts list
		@Date: 04-07-2014
    */
	
    public function view_archive()
    {	
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('default_iview_archive_sortsearchpage_data');
                }
                $data['sortfield']		= 'ipim.id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('default_iview_archive_sortsearchpage_data');
		
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
/*                            $data['searchtext'] = $searchsort_session['searchtext'];
                            $searchtext =  $data['searchtext'];*/
							$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	    					$data['searchtext'] = $searchsort_session['searchtext'];

                        }
                    }
                }
		if(!empty($searchopt))
		{
			$searchopt = $this->input->post('searchopt');
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
		$plan_id = $this->uri->segment(3);
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction/".$plan_id."/view_archive");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 5;
		//$uri_segment = $this->uri->segment(5);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
                }
		
		
		$table = "interaction_plan_interaction_master_premium as ipim";
		$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name','count(DISTINCT ipct.contact_id) as contact_counter');
		$join_tables = array(
							'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
							'admin_users as au' => 'au.id = ipim.created_by',
							'interaction_plan_interaction_master_premium as ipm' => 'ipm.id = ipim.interaction_id',
							'interaction_plan_contacts_trans ipct' => 'ipct.interaction_plan_id = ipim.interaction_plan_id'
							);
		
		$group_by='ipim.id';
		$status_value='0';
		if(!empty($searchtext))
		{
			//pr($searchtext);exit;
			$match=array('ipim.description'=>$searchtext);
			$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'like','','','','',$group_by,$where1,'','1');
		}
		else
		{
		
			$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
		
			//$match=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);

			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'','1');
			}
			$match=array('id'=>$plan_id);
			$data['list'] =$this->interaction_plans_premium_model->select_records('',$match,'','=');

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
                $default_iview_archive_sortsearchpage_data = array(
                    'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
                $this->session->set_userdata('default_iview_archive_sortsearchpage_data', $default_iview_archive_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
			$this->load->view('superadmin/include/template',$data);
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
		$match = array('interaction_plan_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        $data['datalist'] = $result;
		$data['main_content'] = "superadmin/".$this->viewName."/list";       
	   	$this->load->view("superadmin/include/template",$data);
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
		$data['plan_id'] = $this->uri->segment(4);
		//$match = array("created_by"=>$this->superadmin_session['id']);
		//$match = array("created_by"=>$this->superadmin_session['id']);
		
		$match = array("id"=>$data['plan_id']);
		$check_plan_status = $this->obj1->select_records1('',$match,'','=','','','','id','asc','interaction_plan_interaction_master_premium');
		
		if(isset($check_plan_status[0]['status']) && $check_plan_status[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_plan_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('superadmin/'.$this->viewName."/".$data['plan_id']);
		}
		
		//pr($check_plan_status);exit;
		
        $data['interaction_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','interaction_plan__plan_type_master');
		
		$match = array("interaction_plan_id"=>$data['plan_id'],"status"=>'1');
		$data['interaction_list'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc','interaction_plan_interaction_master_premium');
		
		/*$match = array("status"=>'1',"user_type"=>3);
		$data['user_list'] = $this->obj2->select_records1('',$match,'','=','','','','','asc','user_master');*/
		
		/*$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name','um.status as user_status');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		$match2 = array('lm.user_type'=>'2');
		$match3 = array('lm.user_type'=>'3');
		$where=array('lm.status'=>"'1'");
		$data['user_list']=$this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where,$match3);*/
		
		$data['user_list'] = $this->task_model->get_admin_users_list();
		
		//pr($data['user_list']);exit;
		
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		
		$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.*','lm.user_type');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';

	    $match = array("parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		$match = array("parent != "=>0);
        $data['category_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$match);
		
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
		@Description: Function for Insert New Interaction Plan data
		@Author: Mit Makwana
		@Input: - Details of new contacts which is inserted into DB
		@Output: - List of contacts with new inserted records
		@Date: 14-07-2014
    */
	
    public function insert_data()
    {
		//pr($_POST);exit;
		$plan_id = $this->input->post('plan_id');
		$cdata['description'] = $this->input->post('txtarea_description');
		/*
		if($this->input->post('slt_assigned') == '')
			$cdata['assign_to']=$this->superadmin_session['id'];
		else
			$cdata['assign_to']=$this->input->post('slt_assigned');
		
		$assigned_user_id = $cdata['assign_to'];*/
		
		$interaction_start_date = '';
		
		if($this->input->post('rad_start_type') == '1')
		{
			$cdata['start_type']=$this->input->post('rad_start_type');
			$cdata['number_count']=$this->input->post('txt_interaction_stat_1');
			$cdata['number_type']=$this->input->post('slt_nub_type_1');
			
			$match = array('id'=>$plan_id);
			$interaction_plan_details = $this->interaction_plans_model->select_records('',$match,'','=');
			
			if(!empty($interaction_plan_details[0]['id']))
			{
				//echo $interaction_plan_details[0]['id'];
				if($interaction_plan_details[0]['plan_start_type'] == 1)
					$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['created_date']));
				elseif($interaction_plan_details[0]['plan_start_type'] == 2)
					$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['start_date']));
					
				$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$cdata['number_count']." ".$cdata['number_type']));
				
			}
		}
		else if($this->input->post('rad_start_type') == '2')
		{
			$cdata['start_type']=$this->input->post('rad_start_type');
			$cdata['number_count']=$this->input->post('txt_interaction_stat_2');
			$cdata['number_type']=$this->input->post('slt_nub_type_2');
			$cdata['interaction_id']=$this->input->post('slt_interaction_stat_2');
			
			$match = array('id'=>$cdata['interaction_id']);
			$interaction_details = $this->interaction_model->select_records('',$match,'','=');
			
			if(!empty($interaction_details[0]['id']))
			{
				//echo $interaction_details[0]['id'];
				$interaction_start_date = date('Y-m-d',strtotime($interaction_details[0]['interaction_sequence_date']));
				$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$cdata['number_count']." ".$cdata['number_type']));
			}
			
		}
		else if($this->input->post('rad_start_type') == '3')
		{
			$cdata['start_type']=$this->input->post('rad_start_type');
			$cdata['start_date']=date('Y-m-d',strtotime($this->input->post('rad_start_type_date')));
			$interaction_start_date = $cdata['start_date'];
		}
		
		$cdata['priority']=$this->input->post('txt_priority');
		
		if($this->input->post('rad_drop_type') == '1')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
		}
		else if($this->input->post('rad_drop_type') == '2')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
			$cdata['drop_after_day']=$this->input->post('txt_drop_after_day');
			
		}
		else if($this->input->post('rad_drop_type') == '3')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
			$cdata['drop_after_date']=date('Y-m-d',strtotime($this->input->post('rad_drop_after_date')));
		}
		$cdata['interaction_notes']=$this->input->post('txtarea_interaction_notes');
		$cdata['interaction_plan_id']=$this->input->post('plan_id');
		$cdata['interaction_type']=$this->input->post('slt_interaction_type');
		
		$cdata['template_category']=$this->input->post('slt_category');
		$cdata['template_subcategory']=$this->input->post('slt_subcategory');
		$cdata['template_name']=$this->input->post('slt_template_name');
		
		//echo $interaction_start_date;
		
		$cdata['interaction_sequence_date']=$interaction_start_date;
		$cdata['send_automatically'] = $this->input->post('send_mailsms_auto');
		$interaction_type = $this->input->post('slt_interaction_type');
		if($interaction_type == 6)
			$cdata['include_signature'] = $this->input->post('sign_include');
		
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['created_by'] = $this->superadmin_session['id'];
		
		$cdata['status'] = '1';
        $last_id=$this->obj->insert_record($cdata);	
		
		//Insert data to interection master
		
		/*$match = array('p_p_id'=>$plan_id);
		$field=array('id');
        $interaction_data = $this->interaction_plans_model->select_records('',$match,'','=','','','','id','asc');
		//pr($interaction_data);exit;
		if(!empty($interaction_data))
		{
			foreach($interaction_data as $row)
			{
					$cdata['interaction_plan_id'] = $row['id'];
					$cdata['created_by'] = $row['created_by'];
					//pr($cdata);
					$ins=$this->interaction_model->insert_record($cdata);	
			}	
		}*/
		
		        $default_interaction_sortsearchpage_data = array(
                    'sortfield'  => 'cm.id',
                    'sortby' => 'desc',
                    'searchtext' =>'',
                    'perpage' => '',
                    'uri_segment' => 0);
                $this->session->set_userdata('default_interaction_sortsearchpage_data', $default_interaction_sortsearchpage_data);
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	

        redirect('superadmin/'.$this->viewName."/".$plan_id);				
		//redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    }
	
    /*
    @Description: Get Details of Edit contacts Profile
    @Author: Nishit Modi
    @Input: - Id of contacts member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 04-07-2014
    */
    public function edit_record()
    {
		
        $id = $this->uri->segment(4);
		$match = array("ipim.id"=>$this->uri->segment(4));
		$table = "interaction_plan_interaction_master_premium as ipim";
		$fields = array('ipim.*','ipptm.name');
		$join_tables = array('interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type');
		$result= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','');	
		$data['editRecord']=$result;
		
		$plan_id = !empty($result[0]['interaction_plan_id'])?$result[0]['interaction_plan_id']:'';
		
		if(isset($result[0]['status']) && $result[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('superadmin/'.$this->viewName."/".$plan_id);
		}
		
//		pr($data['editRecord']);exit;
		
		//$match = array("created_by"=>$this->superadmin_session['id']);
        $data['interaction_type'] = $this->obj1->select_records1('','','','','','','','name','asc','interaction_plan__plan_type_master');
		
		$match = array("interaction_plan_id"=>$plan_id,"status"=>'1');
		$data['interaction_list'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc','interaction_plan_interaction_master_premium');
		
		
		$match = array("id"=>$plan_id);
		$check_plan_status = $this->obj1->select_records1('',$match,'','=','','','','id','asc','interaction_plan_interaction_master');
		
		if(isset($check_plan_status[0]['status']) && $check_plan_status[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_plan_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('superadmin/'.$this->viewName."/".$plan_id);
		}
		
		/*$match = array("status"=>'1',"user_type"=>3);
		$data['user_list'] = $this->obj2->select_records1('',$match,'','=','','','','','asc','user_master');*/
		
		/*$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		$match2 = array('lm.user_type'=>'2');
		$match3 = array('lm.user_type'=>'3');
		$where=array('lm.status'=>"'1'");
		$data['user_list']=$this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where,$match3);*/
		
		//$data['user_list'] = $this->task_model->get_admin_users_list();
		
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		/////////////////////////////////////////////////////////////
		$interaction_id = array();
		if(!empty($result) && count($interaction_id) == 0)
		{
			$match = array('interaction_plan_id'=>$result[0]['interaction_plan_id'],'status'=>'1');
			$all_result = $this->obj->select_records('',$match,'','=','','','','interaction_sequence_date','asc');
			foreach($all_result as $row)
				$interaction_id[] = $row['id'];
			//pr($interaction_id);exit;
		}
		$data['previous_interaction'] = 0;
		$data['next_interaction'] = 0;
		if(count($interaction_id) > 1)
		{
			$current_key = array_search($result[0]['id'],$interaction_id);
			end($interaction_id);         // move the internal pointer to the end of the array
			$last_key = key($interaction_id);
			if(isset($last_key) && $interaction_id[$last_key] != $result[0]['id'])
				$data['next_interaction'] = $interaction_id[$current_key + 1];
			if(!empty($last_key))
				$data['previous_interaction'] = $interaction_id[$current_key - 1];
		}
		//$data['all_interaction_id'] = $interaction_id;
		//$data['previous_interaction']	= $this->obj->get_previous_interaction($id);
		//$data['next_interaction']		= $this->obj->get_next_interaction($id);
		
		/////////////////////////////////////////////////////////////
		
		/////////////////////////// Select Email Campaign Attachment ////////////////	
		$match = array('interaction_id'=>$id);
		$interaction_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
		
		if(count($interaction_exist) > 0)
		{
			$data['email_campaign_id'] = $interaction_exist[0]['id'];
			$data['attachment'] = $this->email_campaign_master_model->select_email_campaign_attachments($interaction_exist[0]['id']);
		}
		
		/////////////////////////////////////////////////////////////////////////////

		$data['main_content'] = "superadmin/".$this->viewName."/add";       
	   	$this->load->view("superadmin/include/template",$data);
    }

    /*
    @Description: Function for Update contacts Profile
    @Author: Nishit Modi
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 04-07-2014
    */
    public function update_data()
    {
		//pr($_POST);exit;
		$cdata['id'] = $this->input->post('interaction_id');
		$interaction_id = $this->input->post('interaction_id');
		$plan_id=$this->input->post('plan_id');
		
        $cdata['description'] = $this->input->post('txtarea_description');
		
		/*if($this->input->post('slt_assigned') == '')
			$cdata['assign_to']=$this->superadmin_session['id'];
		else
			$cdata['assign_to']=$this->input->post('slt_assigned');
		
		$assigned_user_id = $cdata['assign_to'];*/
		
		$interaction_start_date = '';
		
		//echo $this->input->post('rad_start_type');exit;
		
		if($this->input->post('rad_start_type') == '1')
		{
			 $cdata['start_type']=$this->input->post('rad_start_type');
			 $cdata['number_count']=$this->input->post('txt_interaction_stat_1');
			 $cdata['number_type']=$this->input->post('slt_nub_type_1');
			 
			$match = array('id'=>$plan_id);
			$interaction_plan_details = $this->interaction_plans_model->select_records('',$match,'','=');
			
			if(!empty($interaction_plan_details[0]['id']))
			{
				//echo $interaction_plan_details[0]['id'];
				if($interaction_plan_details[0]['plan_start_type'] == 1)
					$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['created_date']));
				elseif($interaction_plan_details[0]['plan_start_type'] == 2)
					$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['start_date']));
					
				$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$cdata['number_count']." ".$cdata['number_type']));
				
			}
			 
		}
		else if($this->input->post('rad_start_type') == '2')
		{
			 $cdata['start_type']=$this->input->post('rad_start_type');
			 $cdata['number_count']=$this->input->post('txt_interaction_stat_2');
			 $cdata['number_type']=$this->input->post('slt_nub_type_2');
			 $cdata['interaction_id']=$this->input->post('slt_interaction_stat_2');
			 
			$match = array('id'=>$cdata['interaction_id']);
			$interaction_details = $this->obj->select_records('',$match,'','=');
			
			if(!empty($interaction_details[0]['id']))
			{
				//echo $interaction_details[0]['id'];
				$interaction_start_date = date('Y-m-d',strtotime($interaction_details[0]['interaction_sequence_date']));
				$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$cdata['number_count']." ".$cdata['number_type']));
				
			}
			
			$loop = 0;
			for($i=0;$i>=$loop;$i++)
			{
				if(count($interaction_details) > 0)
				{
					if($interaction_id == $interaction_details[0]['id'])
					{
						$loop = 1;
						break;
					}
					if($interaction_details[0]['start_type'] == '2')
						$match['id'] = $interaction_details[0]['interaction_id'];
					else
						$match['id'] = 0;
				}
				else
					break;
				$interaction_details = array();	
				$interaction_details = $this->obj->select_records('',$match,'','=');
				//echo $interaction_details[0]['id'];
				//pr($interaction_details);
			}
			//echo $loop; exit;
			if($loop == 1)
			{
				$newdata = array('msg'  => 'Infinite loop is created, Interaction can not be saved.');
        		$this->session->set_userdata('message_session', $newdata);
				redirect('superadmin/'.$this->viewName."/".$plan_id."/".$pagingid);
			}
			 
		}
		else if($this->input->post('rad_start_type') == '3')
		{
			$cdata['start_type']=$this->input->post('rad_start_type');
			$cdata['start_date']=date('Y-m-d',strtotime($this->input->post('rad_start_type_date')));
			$interaction_start_date = $cdata['start_date'];
		}
		
		$cdata['priority']=$this->input->post('txt_priority');
		
		if($this->input->post('rad_drop_type') == '1')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
		}
		else if($this->input->post('rad_drop_type') == '2')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
			$cdata['drop_after_day']=$this->input->post('txt_drop_after_day');
			
		}
		else if($this->input->post('rad_drop_type') == '3')
		{
			$cdata['drop_type']=$this->input->post('rad_drop_type');
			$cdata['drop_after_date']=date('Y-m-d',strtotime($this->input->post('rad_drop_after_date')));
		}
		$cdata['interaction_notes']=$this->input->post('txtarea_interaction_notes');
		//$cdata['interaction_plan_id']=$this->input->post('plan_id');
		$cdata['interaction_type']=$this->input->post('slt_interaction_type');
		
		$cdata['template_category']=$this->input->post('slt_category');
		$cdata['template_subcategory']=$this->input->post('slt_subcategory');
		$cdata['template_name']=$this->input->post('slt_template_name');
		
		$cdata['interaction_sequence_date']=$interaction_start_date;
		$cdata['send_automatically'] = $this->input->post('send_mailsms_auto');
		$interaction_type = $this->input->post('slt_interaction_type');
		if($interaction_type == 6)
			$cdata['include_signature'] = $this->input->post('sign_include');
		else
			$cdata['include_signature'] = '';
		
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$cdata['modified_by'] = $this->superadmin_session['id'];
		//pr($cdata);exit();

		$this->obj->update_record($cdata);
		
		
		///////////////////// Update Email Campaign Master or SMS campaign master Entry /////////////////////////
		
		//$interaction_type = $this->input->post('slt_interaction_type');
		$template_id = $this->input->post('slt_template_name');
		$email_campaign_id = '';
		$sms_campaign_id = '';
		
		$match = array('interaction_id'=>$interaction_id);
		$sms_campaign_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
		$sms_send = array();
		//pr($sms_campaign_exist);
		if(count($sms_campaign_exist) > 0)
		{
			
			$i = 0;
			$match = array('sms_campaign_id'=>$sms_campaign_exist[0]['id'],'is_send'=>'1');
			$res = $this->sms_campaign_recepient_trans_model->select_records('',$match,'','=');
			//pr($res);exit;
			if(count($res) > 0)
			{
				foreach($res as $row)
				{
					$sms_send[$i] = $row['contact_id'];
					$i++;
				}
			}
			$this->sms_campaign_recepient_trans_model->delete_record_campaign($sms_campaign_exist[0]['id']);
			if(count($sms_send) == 0)
			{
				$this->sms_campaign_master_model->delete_record($sms_campaign_exist[0]['id']);
				//$this->sms_campaign_master_model->delete_sms_campaign_recepient_trans($sms_campaign_exist[0]['id']);
			}
		}
		//pr($sms_send);exit;
		$match = array('interaction_id'=>$interaction_id);
		$email_campaign_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
		$email_send = array();
		if(count($email_campaign_exist) > 0)
		{
			$i = 0;
			$res = $this->email_campaign_master_model->email_campaign_trans_fetch($email_campaign_exist[0]['id']);
			if(count($res) > 0)
			{
				foreach($res as $row)
				{
					$email_send[$i] = $row['contact_id'];
					$i++;
				}
			}
			$this->email_campaign_master_model->email_campaign_trans_delete($email_campaign_exist[0]['id']);
			if(count($email_send) == 0)
			{
				$this->email_campaign_master_model->delete_record($email_campaign_exist[0]['id']);
				$this->email_campaign_master_model->delete_email_campaign_attachments($email_campaign_exist[0]['id']);
			}
		}
		
		if($interaction_type == 6 && !empty($template_id) && !empty($interaction_id))
		{
			$match = array('id'=>$template_id);
	        $result = $this->email_library_model->select_records('',$match,'','=');
			
			if(!empty($result[0]['id']))
			{
				$rowdatains = $result[0];
				$data['template_name_id'] 			= $rowdatains['id'];
				$data['template_category_id'] 		= $rowdatains['template_category'];
				$data['template_subcategory_id']	= $rowdatains['template_subcategory'];
				$data['template_subject'] 			= $rowdatains['template_subject'];
				$data['email_message'] 				= $rowdatains['email_message'];
				
				if($this->input->post('sign_include') == 1)
				{
					$match = array('created_by'=>$this->superadmin_session['id']);
					$email_signature_data			= $this->email_signature_model->select_records('',$match,'','=','','','','is_default','asc');
					if(!empty($email_signature_data[0]))
					{
						$data['email_signature'] 		= $email_signature_data[0]['id'];
						$email_sign_add = $email_signature_data[0]['full_signature'];
					}
				}
				else
					$data['email_signature'] = '';
				
				$data['email_send_type'] 			= 2;
				$data['is_unsubscribe'] 			= $rowdatains['is_unsubscribe'];
				$data['email_type'] 				= 'Intereaction_plan';
				$data['interaction_id']				= $interaction_id;
				$data['email_send_auto']			= $this->input->post('send_mailsms_auto');
				$data['modified_by'] 				= $this->superadmin_session['id'];
				$data['modified_date'] 				= date('Y-m-d H:i:s');
				//$data['status'] 					= 'Active';
				
				$match = array('interaction_id'=>$interaction_id);
				$interaction_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
				
				if(count($interaction_exist) > 0)
				{
					$email_campaign_id = $interaction_exist[0]['id'];
					$data['id'] = $email_campaign_id;
					$this->email_campaign_master_model->update_record($data);
					//$this->email_campaign_master_model->delete_email_campaign_recepient_trans($data['id']);
				}
				else
					$email_campaign_id = $this->email_campaign_master_model->insert_record($data);
				
				unset($data);
				
				$fileName = $this->input->post('fileName');
				if(!empty($fileName))
					$files = explode(",",$fileName);
				if(!empty($files))
				{
					for($i=0;$i<count($files);$i++)
					{
						$bgImgPath = $this->config->item('attachment_basepath_file');
						$random = substr(md5(rand()),0,7);
						$bgTempPath = $this->config->item('attachment_temp');
						$file_name =  pathinfo($bgTempPath.$files[$i]);
						$file_name = $random.".".$file_name['extension'];
						$this->imageupload_model->copyfile($bgImgPath,$files[$i],$file_name);
						$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
						 
						if(file_exists($bgTempPath.$files[$i]))
						{
							$idata['email_campaign_id'] = $email_campaign_id;
							$idata['attachment_name'] = $file_name;
							//pr($idata);
							$this->email_campaign_master_model->insert_email_campaign_attachments($idata);
							@unlink($bgTempPath.$files[$i]);
						}
						
					}
				}
			}
			//exit;
		}
		elseif($interaction_type == 3 && !empty($template_id) && !empty($interaction_id))
		{
			$match = array('id'=>$template_id);
	        $result = $this->sms_texts_model->select_records('',$match,'','=');
			
			if(!empty($result[0]['id']))
			{
				$rowdatains = $result[0];
				$data['template_name'] 				= $rowdatains['id'];
				$data['template_category'] 			= $rowdatains['template_category'];
				$data['template_subcategory']		= $rowdatains['template_subcategory'];
				$data['sms_message'] 				= $rowdatains['sms_message'];
				$data['sms_send_type'] 				= 2;
				$data['sms_type'] 					= 'Intereaction_plan';
				$data['interaction_id']				= $interaction_id;
				$data['sms_send_auto']				= $this->input->post('send_mailsms_auto');
				$data['modified_by'] 				= $this->superadmin_session['id'];
				$data['modified_date'] 				= date('Y-m-d H:i:s');
				//$data['status'] 					= 'Active';
				
				$match = array('interaction_id'=>$interaction_id);
				$interaction_exist = $this->sms_campaign_master_model->select_records('',$match,'','=');
				//pr($interaction_exist);exit;
				if(count($interaction_exist) > 0)
				{
					$sms_campaign_id = $interaction_exist[0]['id'];
					$data['id'] = $sms_campaign_id;
					$this->sms_campaign_master_model->update_record($data);
					//$this->sms_campaign_master_model->delete_sms_campaign_recepient_trans($data['id']);
				}
				else
					$sms_campaign_id = $this->sms_campaign_master_model->insert_record($data);

				unset($data);
			}
		}
		
		//exit;
		
		///////////////////////////////////////////////////////////////////////////
		
		//////////////////// Update Sequence date of related interactions //////////////////////////
		
		$match = array('interaction_id'=>$cdata['id']);
		$interaction_details = $this->interaction_model->select_records('',$match,'','=');
		
		if(!empty($interaction_details))
		{
			foreach($interaction_details as $row)
			{
				
				$interaction_start_date = $cdata['interaction_sequence_date'];
				$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$row['number_count']." ".$row['number_type']));
				
				$ctdata['id']=$row['id'];
				$ctdata['interaction_sequence_date']=$interaction_start_date;
				$ctdata['modified_date'] = date('Y-m-d H:i:s');
				$ctdata['modified_by'] = $this->superadmin_session['id'];
				
				$this->obj->update_record($ctdata);
				unset($ctdata);
			}
		}
		
		$match = array('interaction_plan_id'=>$plan_id);
		$interaction_details = $this->interaction_model->select_records('',$match,'','=','','','','interaction_sequence_date','asc');
		
		if(!empty($interaction_details))
		{
			foreach($interaction_details as $row)
			{
				if($row['start_type'] == 1)
				{
				
					$number_count	= $row['number_count'];
					$number_type	= $row['number_type'];
					
					$match = array('id'=>$plan_id);
					$interaction_plan_details = $this->interaction_plans_model->select_records('',$match,'','=');
					
					if(!empty($interaction_plan_details[0]['id']))
					{
						//echo $interaction_plan_details[0]['id'];
						if($interaction_plan_details[0]['plan_start_type'] == 1)
							$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['created_date']));
						elseif($interaction_plan_details[0]['plan_start_type'] == 2)
							$interaction_start_date = date('Y-m-d',strtotime($interaction_plan_details[0]['start_date']));
							
						$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$number_count." ".$number_type));
						
						$ctdata['id']=$row['id'];
						$ctdata['interaction_sequence_date']=$interaction_start_date;
						$ctdata['modified_date'] = date('Y-m-d H:i:s');
						$ctdata['modified_by'] = $this->superadmin_session['id'];
						
						$this->obj->update_record($ctdata);
						unset($ctdata);
								
					}
				}
				elseif($row['start_type'] == 2)
				{
					$number_count				= $row['number_count'];
					$number_type				= $row['number_type'];
					$newinteraction_id			= $row['interaction_id'];
					
					$match = array('id'=>$newinteraction_id);
					$interaction_details = $this->interaction_model->select_records('',$match,'','=');
					
					if(!empty($interaction_details[0]['id']))
					{
						//echo $interaction_details[0]['id'];
						$interaction_start_date = date('Y-m-d',strtotime($interaction_details[0]['interaction_sequence_date']));
						$interaction_start_date = date("Y-m-d",strtotime($interaction_start_date."+ ".$number_count." ".$number_type));
						
						$ctdata['id']=$row['id'];
						$ctdata['interaction_sequence_date']=$interaction_start_date;
						$ctdata['modified_date'] = date('Y-m-d H:i:s');
						$ctdata['modified_by'] = $this->superadmin_session['id'];
						
						$this->obj->update_record($ctdata);
						unset($ctdata);
					}
				}
				else
				{
					$interaction_start_date = $row['start_date'];
					
					$ctdata['id']=$row['id'];
					$ctdata['interaction_sequence_date']=$interaction_start_date;
					$ctdata['modified_date'] = date('Y-m-d H:i:s');
					$ctdata['modified_by'] = $this->superadmin_session['id'];
					
					$this->obj->update_record($ctdata);
					unset($ctdata);
				}
				
			}
		}
		
		//exit;
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		
		////////////////////////////////////////////////////////////////////////////////////////
		
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		//$email_id = $this->input->post('interaction_id');
		//$pagingid = $this->obj->getemailpagingid($email_id);
                $searchsort_session = $this->session->userdata('default_interaction_sortsearchpage_data');
                $pagingid = $searchsort_session['uri_segment'];
		//redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
		
        redirect('superadmin/'.$this->viewName."/".$plan_id.'/'.$pagingid);				
		//redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
    }
	
	/*
    @Description: Function for Update contacts Profile Tab2 Ajax
    @Author: Nishit Modi
    @Input: - Update details of contacts
    @Output: - List with updated contacts details
    @Date: 11-07-2014
    */
	
	function update_data_ajax()
	{
		$cdata['id'] = $this->input->post('id');
		
		$image = $this->input->post('hiddenFile');
		if(!empty($image))
		{	
			$bgImgPath = $this->config->item('contact_big_img_path');
			$smallImgPath = $this->config->item('contact_small_img_path');
			$this->imageupload_model->copyImage($bgImgPath,$smallImgPath,$image);
			$cdata['contact_pic']	= $image;
			
			$bgTempPath = $this->config->item('upload_image_file_path').'temp/big/';
			$smallTempPath = $this->config->item('upload_image_file_path').'temp/small/';
			if(file_exists($bgTempPath.$image))
			{ 
				@unlink($bgTempPath.$image);
				@unlink($smallTempPath.$image);
			}
		}
		$this->obj->update_record($cdata);
		unset($cdata);
		
		$cddata['id'] 		= $this->input->post('doc_id');
		$cddata['contact_id'] = $this->input->post('id');
		$cddata['doc_type'] = $this->input->post('slt_doc_type');
		$cddata['doc_name'] = $this->input->post('txt_doc_name');
		$cddata['doc_desc'] = $this->input->post('txtarea_doc_desc');
		$cddata['doc_file'] = $this->input->post('hiddenFiledoc');
		$cddata['modified_date'] = date('Y-m-d H:i:s');
		$cddata['status'] 	= '1';
		
		if(trim($cddata['doc_type']) != '' || trim($cddata['doc_name']) != '' || trim($cddata['doc_desc']) != '' || trim($cddata['doc_file']) != '')
		{
			if($this->input->post('doc_id') == '')
			{
				$cddata['created_date'] = date('Y-m-d H:i:s');
				$this->obj->insert_doc_trans_record($cddata);
			}
			else
				$this->obj->update_doc_trans_record($cddata);
				
			unset($cddata);
		}
		
		$data['document_trans_data'] = $this->obj->select_document_trans_record($this->input->post('id'));
		$this->load->view($this->user_type.'/'.$this->viewName."/contact_document_ajax",$data);
		
	}
	
    /*
    @Description: Function for Delete contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 04-07-2014
    */
    function delete_record()
    {
        $id = $this->uri->segment(5);
        $this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName.'/'.$this->uri->segment(4));
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	
	 /*
    @Description: Function for Unpublish contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to Unpublish
    @Output: - New contacts list after record is Unpublish.
    @Date: 04-07-2014
    */
    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		
		$cdata['id'] = $id;
		$cdata['status'] = 0;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By superadmin
    @Author: Nishit Modi
    @Input: - Delete id which contacts record want to publish
    @Output: - New contacts list after record is publish.
    @Date: 04-07-2014
    */
	function publish_record()
    {
        $id = $this->uri->segment(4);
				
		$cdata['id'] = $id;
		$cdata['status'] = 1;
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
        //redirect('superadmin/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
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
		//pr($result);exit;
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
    @Description: Function use to Delete contact in by superadmin
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	public function ajax_delete_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
			}
		}
		echo 1;
	}
	/*
    @Description: Function use to Active and Inactive contact in by superadmin
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	
	public function ajax_Active_all()
	{
            //pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$pagingid='';
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '1';
			$this->obj->update_record($cdata);
			//$email_id = $id;
			//$pagingid = $this->obj->getemailpagingid($email_id);
			$searchsort_session = $this->session->userdata('default_iview_archive_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;

			unset($id);
		}   
		elseif(!empty($array_data))
		{
			//$email_id = $array_data[0];
			//$pagingid = $this->obj->getemailpagingid($email_id);
			$searchsort_session = $this->session->userdata('default_iview_archive_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
			for($i=0;$i<count($array_data);$i++)
			{
				$data['id']=$array_data[$i];
				$data['status']='1';
				$this->obj->update_record($data);
				$delete_all_flag = 1;
				$cnt++;
			}
		}
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
	public function ajax_Inactive_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$pagingid='';
		$msg = '';
		$array_data=$this->input->post('myarray');
		$delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$match = array('interaction_id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			if(count($result) > 0)
				$msg = 'Other interaction are depanding on this interaction, Interaction can not be archieved.';
			else
			{
				$cdata['id'] = $id;
				$cdata['status'] = '0';
				$this->obj->update_record($cdata);
				//echo $this->db->last_query();exit;
				//$email_id = $id;
				//$pagingid = $this->obj->getemailpagingid($email_id);
							$searchsort_session = $this->session->userdata('default_interaction_sortsearchpage_data');
							if(!empty($searchsort_session['uri_segment']))
								$pagingid = $searchsort_session['uri_segment'];
							else
								$pagingid = 0;
			}
			unset($id);
		}
		elseif(!empty($array_data))
		{
						//pr($result);exit;
			//$email_id = $array_data[0];
			//$pagingid = $this->obj->getemailpagingid($email_id);
			for($i=0;$i<count($array_data);$i++)
			{
				$match = array('interaction_id'=>$array_data[$i]);
				$result = $this->obj->select_records('',$match,'','=');
				if(count($result) > 0)
					$msg = 'Other interaction are depanding on interaction, Interaction can not be archieved.';
				else
				{
					$data['id']=$array_data[$i];
					$data['status']='0';
					$this->obj->update_record($data);
					$delete_all_flag = 1;
					$cnt++;
				}
			}
			$searchsort_session = $this->session->userdata('default_interaction_sortsearchpage_data');
			if(!empty($searchsort_session['uri_segment']))
				$pagingid = $searchsort_session['uri_segment'];
			else
				$pagingid = 0;
		}
		
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
		$data_temp['msg'] = $msg;
		$data_temp['page'] = $pagingid;
		
		echo json_encode($data_temp);
		
	}
	
	/*
    @Description: Function for Sub category
    @Author: Mohit Trivedi	
    @Input: - get id which Sub category record 
    @Output: - New Email Library list after record.
    @Date: 12-08-2014
    */

	
	public function ajax_subcategory()
	{
		$id=$this->input->post('loadId');
		
		if(!empty($id))
		{
			$match = array("parent"=>$id);
        	$cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			echo json_encode($cdata['subcategory']);
		}
		else
		{
			$cdata['subcategory'] = array();
			echo json_encode($cdata['subcategory']);
		}
		
	}
	
	/*
    @Description: Function use to select template by superadmin
    @Author: Nishit Modi
    @Input: 
    @Output: - 
    @Date: 21-08-2014
    */
	
	public function ajax_selecttemplate()
	{
		$id=$this->input->post('loadId');
		$catid=$this->input->post('category');
		$subcatid=$this->input->post('subcategory');
		$selected = $this->input->post('selected');
		if(!empty($selected))
		{
			$sortfield = 'id';
			$sortby = 'desc';
		}
		else
		{
			$sortfield = 'template_name';
			$sortby = 'asc';
		}
		
		if(!empty($id))
		{
			$match = array("id"=>$id);
                        $cdata['plan_type'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','interaction_plan__plan_type_master');
			//pr($cdata['plan_type']);exit;
			$cdata['subcategory'] = array();
			
			if(!empty($cdata['plan_type'][0]['id']))
			{
                                $match = array();
				if($catid > 0)
                                    $match = array('template_category'=>$catid);
				switch(strtolower($cdata['plan_type'][0]['name']))
				{
					case 'email':
                                        $match = array('template_category'=>$catid,"email_send_type"=>2);
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->email_library_model->select_records($fields,$match,'','=','','','','template_name','asc');
					break;
					case 'text':
                                        $match = array('template_category'=>$catid,"sms_send_type"=>2);
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->sms_texts_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'call':
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->phonecall_script_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'envelope':
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->envelope_library_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'label':
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->label_library_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'letter':
					$fields = array('id','template_name');
					$cdata['template_list'] = $this->letter_library_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
				}
			}
			echo json_encode($cdata['template_list']);
		}
		else
		{
			$cdata['subcategory'] = array();
			echo json_encode($cdata['template_list']);
		}
	}
	
	/*
		@Description: Function for Get All selected campaign attachment list
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - email campaign attachment list
		@Date: 06-08-2014
    */
	
	public function attachmentlist()
	{
		$email_campaign_id = $this->input->post('email_campaign_id');
		if(!empty($email_campaign_id))
			$data['attachment'] = $this->email_campaign_master_model->select_email_campaign_attachments($email_campaign_id);
		if(count($data['attachment']) > 0)
		 	$this->load->view("superadmin/".$this->viewName."/attachmentlist", $data);
	}
	
	/*
		@Description: Function for Attachment delete
		@Author: Sanjay Chabhadiya
		@Input: -  Attachment name
		@Output: - 
		@Date: 06-08-2014
   	*/
   
	public function delete_attachment()
	{
		$file_name = $this->input->post('file_name');
		$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
		if(file_exists($bgTempPath.$file_name))
		{ 
			@unlink($bgTempPath.$file_name);
		}
		echo $file_name;
	}
	
	public function view_contacts_of_interaction_plan()
	{
		$id = $this->input->post('interaction_plan_id');
		$contact_list = $this->input->post('contact_list');
		if(!empty($contact_list) && !empty($id))
		{
			$where_in = array('cm.id'=>explode(',',$contact_list));
			$table = "interaction_plan_contacts_trans as ct";
			$fields = array('ct.interaction_plan_id','cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$where = array('ct.interaction_plan_id'=>$id);
			$join_tables = array(
								'contact_master as cm'=>'cm.id = ct.contact_id',
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
							);
			$group_by='cm.id';
			
			$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by,'',$where_in);
			//echo $this->db->last_query();exit;
			
			$this->load->view($this->user_type.'/interaction_plans/view_contact_popup',$data);
		}
	}
	
	public function ajax_selectcategory()
	{
		$table = "marketing_master_lib__category_master as mmc";
		$fields = array('mmc.id,mmc.category');
		$join_tables = array('login_master as lm' => 'lm.id = mmc.created_by');
		$group_by='mmc.id';

	    $match = array("parent"=>0);
        $data['plan_type'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','mmc.id','desc',$group_by,$match);
		//echo $this->db->last_query();
		echo json_encode($data['plan_type'] );
		
	}
}