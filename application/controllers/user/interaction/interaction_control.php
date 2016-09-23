<?php
/*
    @Description: Interaction controller
    @Author: Kaushik valiya
    @Input: 
    @Output: 
    @Date: 18-07-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class interaction_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
		
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		
        check_user_login();
		
		$this->load->model('interaction_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('contact_masters_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('bomb_library_model');
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
		$this->load->model('task_model');
		
		$this->obj = $this->interaction_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->contact_masters_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
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
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		
		$data['sortfield']		= 'ipim.interaction_sequence_date';
		$data['sortby']			= 'asc';
		
		if(!empty($sortfield) && !empty($sortby))
		{
			$sortfield = $this->input->post('sortfield');
			$data['sortfield'] = $sortfield;
			$sortby = $this->input->post('sortby');
			$data['sortby'] = $sortby;
		}
		else
		{
			$sortfield = 'ipim.interaction_sequence_date';
			$sortby = 'asc';
		}
		if(!empty($searchtext))
		{
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
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
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$plan_id = $this->uri->segment(3);
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction/".$plan_id);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name','ipm.description as interaction_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as contact_name','count(DISTINCT cm.id) as contact_counter,iplanm.status as plan_status,lom.admin_name');
		
		$join_tables = array(
								'interaction_plan_master as iplanm' 			=> 'iplanm.id = ipim.interaction_plan_id',
								'interaction_plan__plan_type_master as ipptm' 	=> 'ipptm.id = ipim.interaction_type',
								//'admin_users as au' 							=> 'au.id = ipim.created_by',
								'interaction_plan_interaction_master as ipm' 	=> 'ipm.id = ipim.interaction_id',
								'login_master as lom' 							=> 'lom.id = ipim.assign_to',
								'user_master as um' 							=> 'um.id = lom.user_id',
								'interaction_plan_contacts_trans ipct' 			=> 'ipct.interaction_plan_id = ipim.interaction_plan_id',
								'contact_master as cm' 							=> 'cm.id = ipct.contact_id'
							);
		
		$group_by='ipim.id';
		$status_value='1';
		$where1 = 'ipim.interaction_plan_id = '.$plan_id.' AND ipim.status = "'.$status_value.'" AND ipim.assign_to IN ('.$this->user_session['agent_id'].')';
		if(!empty($searchtext))
		{
			//pr($searchtext);exit;
			$match=array('ipim.description'=>$searchtext);
			//$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
			
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'like','','','','',$group_by,$where1,'','1');
		}
		else
		{

			//$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value,'ipim.assign_to'=>$this->user_session['id']);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			//echo $this->db->last_query();exit;

			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'','1');
		}
		$match=array('id'=>$plan_id);
		$data['list'] =$this->interaction_plans_model->select_records('',$match,'','=');
		
		if(!isset($data['list'][0]['id']))
			redirect('user/interaction_plans');
		elseif(isset($data['list'][0]['created_by']) && is_array($this->user_session['agent_id_array']) && !in_array($data['list'][0]['created_by'],$this->user_session['agent_id_array']))
		{
			$msg = $this->lang->line('common_right_msg_communication_view');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/interaction_plans');
		}
				
		$result_contact = $this->obj->current_interaction_plan($plan_id,$this->user_session['agent_id']);
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
		//pr($contact_list); exit;
		$data['interaction_plan'] = $interaction_plan;
		$data['contact_list'] = $contact_list;
		
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
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
    @Description: Function for Get All contacts List
    @Author: Nishit Modi
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
    */
    public function assign_interaction()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		
		$data['sortfield']		= 'ipim.id';
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
			$sortfield = 'id';
			$sortby = 'desc';
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
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
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$plan_id = $this->uri->segment(4);
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction/".$plan_id);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name','ipm.description as interaction_name','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as contact_name','count(DISTINCT cm.id) as contact_counter,iplanm.status as plan_status,lom.admin_name');
		
		$join_tables = array(
								'interaction_plan_master as iplanm' 			=> 'iplanm.id = ipim.interaction_plan_id',
								'interaction_plan__plan_type_master as ipptm' 	=> 'ipptm.id = ipim.interaction_type',
								//'admin_users as au' 							=> 'au.id = ipim.created_by',
								'interaction_plan_interaction_master as ipm' 	=> 'ipm.id = ipim.interaction_id',
								'login_master as lom' 							=> 'lom.id = ipim.assign_to',
								'user_master as um' 							=> 'um.id = lom.user_id',
								'interaction_plan_contacts_trans ipct' 			=> 'ipct.interaction_plan_id = ipim.interaction_plan_id',
								'contact_master as cm' 			=> 'cm.id = ipct.contact_id'
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
			$where1=array('ipim.interaction_plan_id'=>$plan_id,'ipim.status'=>$status_value);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);

			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'','1');
		}
		$match=array('id'=>$plan_id);
		$data['list'] =$this->interaction_plans_model->select_records('',$match,'','=');
		//pr($data['list']);exit;
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/assign_interaction_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/assign_interaction_list";
			$this->load->view('user/include/template',$data);
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
		
		$data['sortfield']		= 'ipim.id';
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
			$sortfield = 'id';
			$sortby = 'desc';
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = stripslashes($searchtext);
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
		if(!empty($perpage)&& $perpage != 'null')
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$plan_id = $this->uri->segment(3);
		
		$config['base_url'] = site_url($this->user_type.'/'."interaction/".$plan_id."/view_archive");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		
		
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name','count(DISTINCT ipct.contact_id) as contact_counter');
		$join_tables = array(
							'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
							'admin_users as au' => 'au.id = ipim.created_by',
							'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id',
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
		$data['list'] =$this->interaction_plans_model->select_records('',$match,'','=');
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list_archive',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list_archive";
			$this->load->view('user/include/template',$data);
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
		$match = array("ipim.id"=>$this->uri->segment(4));
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name');
		$join_tables = array('interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type');
		$result= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','');	
		$data['editRecord']=$result;
		
		$plan_id = !empty($result[0]['interaction_plan_id'])?$result[0]['interaction_plan_id']:'';
		if(!isset($result[0]['id']))
			redirect('user/interaction_plans');
		elseif(isset($result[0]['status']) && $result[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_edit_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName."/".$plan_id);
		}
		elseif(isset($result[0]['assign_to']) && is_array($this->user_session['agent_id_array']) && !in_array($result[0]['assign_to'],$this->user_session['agent_id_array']))
		{
			$msg = $this->lang->line('common_right_msg_communication_view');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName."/".$plan_id);
		}
		$interection_type_id = !empty($result[0]['interaction_type'])?$result[0]['interaction_type']:'';
		$match = array("id"=>$interection_type_id);
        $plan_type = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','interaction_plan__plan_type_master');
		//echo $plan_type[0]['name'];exit;
		switch(strtolower($plan_type[0]['name']))
		{
					case 'email':
					$table = "email_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= 'tm.email_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'bomb bomb emails':
					$fields = array('id','template_name','template_category');
					$data['template_list'] = $this->bomb_library_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'sms':
					$table = "sms_text_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'call':
					$table = " phone_call_script_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'envelope':
					$table = "envelope_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					
					break;
					case 'label':
					$table = "label_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'letter':
					$table = "letter_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$data['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
				}
//		pr($data['editRecord']);exit;
		
		//$match = array("created_by"=>$this->user_session['id']);
        $data['interaction_type'] = $this->obj1->select_records1('','','','','','','','name','asc','interaction_plan__plan_type_master');
		
		$match = array("interaction_plan_id"=>$plan_id);
		$where_clause = 'assign_to IN ('.$this->user_session['agent_id'].')';
		$data['interaction_list'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc','interaction_plan_interaction_master',$where_clause);
		/*pr($data['interaction_list']);
		echo $this->db->last_query();
		exit;*/
		$match = array("id"=>$plan_id);
		$check_plan_status = $this->obj1->select_records1('',$match,'','=','','','','id','asc','interaction_plan_master');
		
		if(isset($check_plan_status[0]['status']) && $check_plan_status[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_plan_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName."/".$plan_id);
		}
		
		/*$match = array("status"=>'1',"user_type"=>3);
		$data['user_list'] = $this->obj2->select_records1('',$match,'','=','','','','','asc','user_master');*/
		
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		//$match2 = array('lm.user_type'=>'2');
		//$match3 = array('lm.user_type'=>'3');
		//$match3 = array('lm.id'=>$this->user_session['id']);
		//$where = array('lm.status'=>"'1'");
		$where = 'lm.status = "1" AND lm.id IN ('.$this->user_session['agent_id'].')';
		$data['user_list']=$this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where);
		//pr($data['user_list']);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		/////////////////////////////////////////////////////////////
		
		$interaction_id = array();
		if(!empty($result) && count($interaction_id) == 0)
		{
			$match = array('interaction_plan_id'=>$result[0]['interaction_plan_id'],'status'=>'1');
			//$all_result = $this->obj->select_records('',$match,'','=','','','','interaction_sequence_date','asc');
			$all_result = $this->obj1->select_records1('',$match,'','=','','','','interaction_sequence_date','asc','interaction_plan_interaction_master',$where_clause);
			//echo $this->db->last_query();exit;
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

		$data['main_content'] = "user/".$this->viewName."/view";       
	   	$this->load->view("user/include/template",$data);
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
		//$match = array("created_by"=>$this->user_session['id']);
		//$match = array("created_by"=>$this->user_session['id']);
		
		$match = array("id"=>$data['plan_id']);
		$check_plan_status = $this->obj1->select_records1('',$match,'','=','','','','id','asc','interaction_plan_master');
		
		if(!isset($check_plan_status[0]['id']))
			redirect('user/interaction_plans');
		elseif(isset($check_plan_status[0]['status']) && $check_plan_status[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_plan_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName."/".$data['plan_id']);
		}
		elseif(isset($check_plan_status[0]['created_by']) && is_array($this->user_session['agent_id_array']) && !in_array($check_plan_status[0]['created_by'],$this->user_session['agent_id_array']))
		{
			$msg = $this->lang->line('common_right_msg_communication_add');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/interaction_plans');
		}
		
		//pr($check_plan_status);exit;
		
        $data['interaction_type'] = $this->obj1->select_records1('','','','=','','','','name','asc','interaction_plan__plan_type_master');
		
		$match = array("interaction_plan_id"=>$data['plan_id']);
		$data['interaction_list'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc','interaction_plan_interaction_master');
		
		/*$match = array("status"=>'1',"user_type"=>3);
		$data['user_list'] = $this->obj2->select_records1('',$match,'','=','','','','','asc','user_master');*/
		
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		//$match2 = array('lm.user_type'=>'2');
		//$match3 = array('lm.user_type'=>'3');
		//$match3 = array('lm.id'=>$this->user_session['id']);
		
		//$where=array('lm.status'=>"'1'");
		$where = 'lm.status = "1" AND lm.id IN ('.$this->user_session['agent_id'].') AND lm.user_type = "3"';
		$data['user_list']=$this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where);
		
		//pr($data['user_list']);exit;
		
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
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
		$plan_id = $this->input->post('plan_id');
		$cdata['description'] = $this->input->post('txtarea_description');
		
		$cdata['assign_to']=$this->input->post('slt_assigned');
		$assigned_user_id = $this->input->post('slt_assigned');
		
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
			$cdata['drop_after_date']=$this->input->post('rad_drop_after_date');
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
		if($interaction_type == 6 || $interaction_type == 8)
			$cdata['include_signature'] = $this->input->post('sign_include');
		
		$cdata['created_date'] = date('Y-m-d H:i:s');
		$cdata['created_by'] = $this->user_session['id'];
		
		$cdata['status'] = '1';
       // pr($cdata);
		//exit;

		$last_id=$this->obj->insert_record($cdata);	
		
		///////////////////// Email Campaign Master Entry /////////////////////////
		
		
		$template_id = $this->input->post('slt_template_name');
		$email_campaign_id = '';
		$sms_campaign_id = '';
		
		if(($interaction_type == 6 || $interaction_type == 8) && !empty($template_id) && !empty($last_id))
		{
			if($interaction_type == 6)
			{
				$match = array('id'=>$template_id);
				$result = $this->email_library_model->select_records('',$match,'','=');
			}
			else
			{
				$match = array('id'=>$template_id);
				$result = $this->bomb_library_model->select_records('',$match,'','=');
			}
			
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
					$match = array('created_by'=>$this->user_session['id']);
					$email_signature_data = $this->email_signature_model->select_records('',$match,'','=','','','','is_default','asc');
					if(!empty($email_signature_data[0]))
					{
						$data['email_signature'] = $email_signature_data[0]['id'];
						$email_sign_add = $email_signature_data[0]['full_signature'];
					}
				}
				
				$data['email_send_type'] 			= 2;
				$data['is_unsubscribe'] 			= !empty($rowdatains['is_unsubscribe'])?$rowdatains['is_unsubscribe']:'1';
				$data['email_type'] 				= 'Intereaction_plan';
				$data['interaction_id']				= $last_id;
				$data['email_send_auto']			= $this->input->post('send_mailsms_auto');
				$data['created_by'] 				= $this->user_session['id'];
				$data['created_date'] 				= date('Y-m-d H:i:s');
				//$data['status'] 					= 'Active';
				if($interaction_type == '8')
					$data['email_blast_type'] = 1;
				//$email_campaign_id = 1;
				
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
						//$random = substr(md5(rand()),0,7);
						$bgTempPath = $this->config->item('attachment_temp');
						//$file_name =  pathinfo($bgTempPath.$files[$i]);
						//$file_name = $random.".".$file_name['extension'];
						$this->imageupload_model->copyfile($bgImgPath,$files[$i],$files[$i]);
						$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
						 
						if(file_exists($bgTempPath.$files[$i]))
						{
							$idata['email_campaign_id'] = $email_campaign_id;
							$idata['attachment_name'] = $files[$i];
							//pr($idata);
							$this->email_campaign_master_model->insert_email_campaign_attachments($idata);
							@unlink($bgTempPath.$files[$i]);
						}
						
					}
				}
			}
			//exit;
		}
		elseif($interaction_type == 3 && !empty($template_id) && !empty($last_id))
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
				$data['interaction_id']				= $last_id;
				$data['sms_send_auto']				= $this->input->post('send_mailsms_auto');
				$data['created_by'] 				= $this->user_session['id'];
				$data['created_date'] 				= date('Y-m-d H:i:s');
				//$data['status'] 					= 'Active';
				
				//$email_campaign_id = 1;
				
				$sms_campaign_id = $this->sms_campaign_master_model->insert_record($data);
				
				unset($data);
			}
		}
		
		//exit;
		
		///////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','ct.start_date','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name,cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
		$where = array('ct.interaction_plan_id'=>$plan_id);
		$join_tables = array(
							'contact_master as cm jointype direct'=>'cm.id = ct.contact_id',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id',
						);
		$group_by='cm.id';
		
		$addcontactdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
		
		//pr($addcontactdata);
		
		//exit;
		
		///////////////////////////////////////////////////////////////////////////		
		
		if(!empty($addcontactdata))
		{
		
			//////////////////// Integrate User Work time config ///////////////////
			
			if(!empty($assigned_user_id))
			{
			
				//echo $assigned_user_id;
				
				//Get Working Days
				
				//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
				$new_user_id = $assigned_user_id;
				
				//echo $new_user_id;
				
				$match = array("user_id"=>$new_user_id);
				$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
				
				//pr($worktimedata);exit;
				
				$match = array("user_id"=>$new_user_id,"rule_type"=>1);
				$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
				
				//pr($worktimespecialdata);exit;
				
				$match = array("user_id"=>$new_user_id);
				$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
				
				//pr($worktimeleavedata);exit;
				
				$user_work_off_days = array();
				
				if(!empty($worktimedata[0]['id']))
				{
					if(empty($worktimedata[0]['if_mon']))
						$user_work_off_days[] = 'Mon';
					if(empty($worktimedata[0]['if_tue']))
						$user_work_off_days[] = 'Tue';
					if(empty($worktimedata[0]['if_wed']))
						$user_work_off_days[] = 'Wed';
					if(empty($worktimedata[0]['if_thu']))
						$user_work_off_days[] = 'Thu';
					if(empty($worktimedata[0]['if_fri']))
						$user_work_off_days[] = 'Fri';
					if(empty($worktimedata[0]['if_sat']))
						$user_work_off_days[] = 'Sat';
					if(empty($worktimedata[0]['if_sun']))
						$user_work_off_days[] = 'Sun';
				}
				
				$special_days = array();
				
				if(!empty($worktimespecialdata))
				{
					foreach($worktimespecialdata as $row)
					{
						$day_string = '';
						if(!empty($row['nth_day']) && !empty($row['nth_date']))
						{
							switch($row['nth_day'])
							{
								case 1:
								$day_string .= 'First ';
								break;
								case 2:
								$day_string .= 'Second ';
								break;
								case 3:
								$day_string .= 'Third ';
								break;
								case 4:
								$day_string .= 'Fourth ';
								break;
								case 5:
								$day_string .= 'Last ';
								break;
							}
							switch($row['nth_date'])
							{
								case 1:
								$day_string .= 'Day';
								break;
								case 2:
								$day_string .= 'Weekday';
								break;
								case 3:
								$day_string .= 'Weekend';
								break;
								case 4:
								$day_string .= 'Monday';
								break;
								case 5:
								$day_string .= 'Tuesday';
								break;
								case 6:
								$day_string .= 'Wednesday';
								break;
								case 7:
								$day_string .= 'Thursday';
								break;
								case 8:
								$day_string .= 'Friday';
								break;
								case 9:
								$day_string .= 'Saturday';
								break;
								case 10:
								$day_string .= 'Sunday';
								break;
								default:
								break;
							}
							
							$special_days[] = $day_string;
							
						}
					}
				}
				
				$leave_days = array();
				
				foreach($worktimeleavedata as $row)
				{
					if(!empty($row['from_date']))
					{
						$leave_days[] = $row['from_date'];
						if(!empty($row['to_date']))
						{
							
							//$from_date = date('Y-m-d',strtotime($row['from_date']));
							
							$from_date = date('Y-m-d', strtotime($row['from_date'] . ' + 1 day'));
							
							$to_date = date('Y-m-d',strtotime($row['to_date']));
							
							//echo $from_date."-".$to_date;
							
							while($from_date <= $to_date)
							{
								$leave_days[] = $from_date;
								$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
							}
						}
					}
				}
				
				//pr($user_work_off_days);
				
				//pr($special_days);
				
				//pr($leave_days);
				
			}
					
			////////////////////////////////////////////////////////////////////////
		
			foreach($addcontactdata as $row)
			{
				if($row != '')
				{
					$icdata['interaction_plan_id'] = $plan_id;
					$icdata['contact_id'] = $row['id'];
					$icdata['interaction_plan_interaction_id'] = $last_id;
					$icdata['interaction_type'] = $this->input->post('slt_interaction_type');
					
					if($this->input->post('rad_start_type') == '1')
					{
						$count = $this->input->post('txt_interaction_stat_1');
						$counttype = $this->input->post('slt_nub_type_1');
						
						$newtaskdate = date("Y-m-d",strtotime($row['start_date']."+ ".$count." ".$counttype));
						
						//////////////////// Integrate User Work time config ///////////////////
						
						$newtaskdate1 = date("Y-m-d",strtotime($row['start_date']."+ ".$count." ".$counttype));
						
						////////////////////////////////////////////////////////
						
						$repeatoff = 1;
						
						while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
						{
							// Check for Work off days
							// echo $newtaskdate;
							$day_of_date = date('D', strtotime($newtaskdate1));
							$new_special_days = array();
							
							if(!empty($special_days))
							{
								foreach($special_days as $mydays)
								{
									if (strpos($mydays,'Weekday') !== false) {
										$nthday = explode(" ",$mydays);
										if(!empty($nthday[0]))
										{
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
										}
									}
									elseif (strpos($mydays,'Weekend') !== false) {
										$nthday = explode(" ",$mydays);
										if(!empty($nthday[0]))
										{
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
											$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
										}
									}
									else
										$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
								}
							}
							
							//pr($new_special_days);
							
							if(!empty($user_work_off_days) && in_array($day_of_date,$user_work_off_days))
							{
								//echo 'work off'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
							{
								//echo 'special days'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							elseif(!empty($leave_days) && in_array($newtaskdate1,$leave_days))
							{
								//echo 'leave'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							else
							{
								//echo 'else';
								$repeatoff = 0;
								$newtaskdate = $newtaskdate1;
							}
							
							//echo $repeatoff;
							
						}
						
						//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
						
						///////////////////////////////////////////////////////
						
						////////////////////////////////////////////////////////////////////////
						
						$icdata['task_date'] = $newtaskdate;
					}
					elseif($this->input->post('rad_start_type') == '2')
					{
						$count = $this->input->post('txt_interaction_stat_2');
						$counttype = $this->input->post('slt_nub_type_2');
						
						$interaction_id = $this->input->post('slt_interaction_stat_2');
						
						$interaction_res = $this->obj->get_contact_interaction_task_date($interaction_id,$row['id']);
						
						//pr($interaction_res);
						
						//echo $interaction_res->task_date;
						
						if(!empty($interaction_res->task_date))
						{
							$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
							//////////////////// Integrate User Work time config ///////////////////
						
							$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
							////////////////////////////////////////////////////////
							
							$repeatoff = 1;
							
							while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
							{
								// Check for Work off days
								// echo $newtaskdate;
								$day_of_date = date('D', strtotime($newtaskdate1));
								$new_special_days = array();
								
								if(!empty($special_days))
								{
									foreach($special_days as $mydays)
									{
										if (strpos($mydays,'Weekday') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										elseif (strpos($mydays,'Weekend') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										else
											$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
									}
								}
								
								//pr($new_special_days);
								
								if(!empty($user_work_off_days) && in_array($day_of_date,$user_work_off_days))
								{
									//echo 'work off'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
								{
									//echo 'special days'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								elseif(!empty($leave_days) && in_array($newtaskdate1,$leave_days))
								{
									//echo 'leave'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								else
								{
									//echo 'else';
									$repeatoff = 0;
									$newtaskdate = $newtaskdate1;
								}
								
								//echo $repeatoff;
								
							}
							
							//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
							
							///////////////////////////////////////////////////////
							
							//echo $newtaskdate;
						
							////////////////////////////////////////////////////////////////////////
						
							$icdata['task_date'] = $newtaskdate;
						}
						
					}
					else
					{
						$icdata['task_date'] = date('Y-m-d',strtotime($this->input->post('rad_start_type_date')));
					}
					
					$icdata['created_date'] = date('Y-m-d H:i:s');
					$icdata['created_by'] = $this->user_session['id'];
					
					$sendemaildate = $icdata['task_date'];
					
					$this->obj->insert_contact_communication_record($icdata);
					
					unset($icdata);
					
					$agent_name = '';
					if(!empty($assigned_user_id))
					{
						$table ="login_master as lm";   
						$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
						$join_tables = array('user_master as um'=>'lm.user_id = um.id');
						$wherestring = 'lm.id = '.$assigned_user_id;
						$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
						if(!empty($agent_datalist))
						{
							if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
								$agent_name = $agent_datalist[0]['admin_name'];
							else
								$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
						}
					}
					
					if(($interaction_type == 6 || $interaction_type == 8) && !empty($email_campaign_id))
					{
					
						if($interaction_type == 6)
						{
							$match = array('id'=>$template_id);
							$result = $this->email_library_model->select_records('',$match,'','=');
						}
						else
						{
							$match = array('id'=>$template_id);
							$result = $this->bomb_library_model->select_records('',$match,'','=');
						}
						
						if(!empty($result[0]['id']))
						{
							$rowdatainst = $result[0];
					
							$cdata1['contact_id'] = $row['id'];
							$cdata1['email_campaign_id'] = $email_campaign_id;
							$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$row['first_name'],
												'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
												'Contact Last Name'=>$row['last_name'],
												'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
												'Contact Company Name'=>$row['company_name'],
												);
							
							$content = $rowdatainst['email_message'];
							$title = $rowdatainst['template_subject'];
							
							//pr($emaildata);
							
							$cdata1['template_subject'] 	= $title;
							$cdata1['email_message'] 	= $content;
							$pattern = "{(%s)}";
							
							$map = array();
							
							if($emaildata != '' && count($emaildata) > 0)
							{
								foreach($emaildata as $var => $value)
								{
									$map[sprintf($pattern, $var)] = $value;
								}
								$finaltitle = strtr($title, $map);				
								$output = strtr($content, $map);
								
								$cdata1['template_subject'] = $finaltitle;
								$finlaOutput = $output;
								$cdata1['email_message'] = $finlaOutput;
							}
							$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
							//$from_data .= $contact_id[$i].",";
							$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
						}
			
					}
					elseif($interaction_type == 3 && !empty($sms_campaign_id))
					{
						$cdata1['sms_campaign_id'] = $sms_campaign_id;
						
						$match = array('id'=>$template_id);
						$result = $this->sms_texts_model->select_records('',$match,'','=');
						
						if(!empty($result[0]['id']))
						{
							$rowdatainst = $result[0];
					
							$cdata1['contact_id'] = $row['id'];
							$cdata1['sms_campaign_id'] = $sms_campaign_id;
							$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$row['first_name'],
												'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
												'Contact Last Name'=>$row['last_name'],
												'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
												'Contact Company Name'=>$row['company_name'],
												'Contact Address'=>$row['address_line1'].' '.$row['address_line2'],
												'Contact City'=>$row['city'],
												'Contact State'=>$row['state'],
												'Contact Zip'=>$row['zip_code'],
												);
							
							$content = $rowdatainst['sms_message'];
							$cdata1['sms_message'] 	= $content;
							$pattern = "{(%s)}";
							
							$map = array();
							
							if($emaildata != '' && count($emaildata) > 0)
							{
								foreach($emaildata as $var => $value)
								{
									$map[sprintf($pattern, $var)] = $value;
								}
								$output = strtr($content, $map);
								
								$finlaOutput = $output;
								$cdata1['sms_message'] = $finlaOutput;
							}
							$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
							//$from_data .= $contact_id[$i].",";
							$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
						}
						
					}
				}
			}
			unset($user_work_off_days);
			unset($special_days);
			unset($leave_days);
		}
		
		//exit;

		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	

        redirect('user/'.$this->viewName."/".$plan_id);				
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
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
		$table = "interaction_plan_interaction_master as ipim";
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
			redirect('user/'.$this->viewName."/".$plan_id);
		}
		
//		pr($data['editRecord']);exit;
		
		//$match = array("created_by"=>$this->user_session['id']);
        $data['interaction_type'] = $this->obj1->select_records1('','','','','','','','name','asc','interaction_plan__plan_type_master');
		
		$match = array("interaction_plan_id"=>$plan_id);
		$data['interaction_list'] = $this->obj1->select_records1('',$match,'','=','','','','description','asc','interaction_plan_interaction_master');
		
		$match = array("id"=>$plan_id);
		$check_plan_status = $this->obj1->select_records1('',$match,'','=','','','','id','asc','interaction_plan_master');
		
		if(isset($check_plan_status[0]['status']) && $check_plan_status[0]['status'] == '0')
		{
			$msg = $this->lang->line('common_plan_archive_data_error');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName."/".$plan_id);
		}
		elseif(isset($check_plan_status[0]['created_by']) && is_array($this->user_session['agent_id_array']) && !in_array($check_plan_status[0]['created_by'],$this->user_session['agent_id_array']))
		{
			$msg = $this->lang->line('common_right_msg_action');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/interaction_plans');
		}
		
		/*$match = array("status"=>'1',"user_type"=>3);
		$data['user_list'] = $this->obj2->select_records1('',$match,'','=','','','','','asc','user_master');*/
		
		$table = "login_master as lm";
		$fields = array('lm.id','lm.admin_name','lm.user_id','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name');
		$join_tables = array('user_master as um' => 'um.id = lm.user_id');
		$group_by='lm.id';
		//$match2 = array('lm.user_type'=>'2');
		//$match3 = array('lm.user_type'=>'3');
		//$match3 = array('lm.id'=>$this->user_session['id']);
		//$where=array('lm.status'=>"'1'");
		$where = 'lm.status = "1" AND lm.id IN ('.$this->user_session['agent_id'].') AND lm.user_type = "3"';
		$data['user_list']=$this->task_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match2,'','like','', '','','',$group_by,$where,$match3);
		//pr($data['user_list']);
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
		//$data['data'] = $this->obj->select_records($id);
		//$data['previous_interaction']	= $this->obj->get_previous_interaction($id);
		//echo $this->db->last_query();
		//$data['next_interaction']		= $this->obj->get_next_interaction($id);
		//echo $this->db->last_query();exit;
		
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

		$data['main_content'] = "user/".$this->viewName."/add";       
	   	$this->load->view("user/include/template",$data);
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
		
		$email_id = $this->input->post('interaction_id');
		$pagingid = $this->obj->getemailpagingid($email_id);
		
		$cdata['id'] = $this->input->post('interaction_id');
		$interaction_id = $this->input->post('interaction_id');
		$plan_id=$this->input->post('plan_id');
		
        $cdata['description'] = $this->input->post('txtarea_description');
		
		$cdata['assign_to'] = $this->input->post('slt_assigned');
		$assigned_user_id = $this->input->post('slt_assigned');
		
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
				$interaction_details = $this->interaction_model->select_records('',$match,'','=');
				//echo $interaction_details[0]['id'];
				//pr($interaction_details);
			}
			//echo $loop; exit;
			if($loop == 1)
			{
				$newdata = array('msg'  => 'Infinite loop is created, Interaction can not be saved.');
        		$this->session->set_userdata('message_session', $newdata);
				redirect('user/'.$this->viewName."/".$plan_id."/".$pagingid);
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
		if($interaction_type == 6 || $interaction_type == 8)
			$cdata['include_signature'] = $this->input->post('sign_include');
		else
			$cdata['include_signature'] = '';
		
		$cdata['modified_date'] = date('Y-m-d H:i:s');
		$cdata['modified_by'] = $this->user_session['id'];
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
			if(count($email_send) == 0 && $interaction_type != 6)
			{
				$this->email_campaign_master_model->delete_record($email_campaign_exist[0]['id']);
				$this->email_campaign_master_model->delete_email_campaign_attachments($email_campaign_exist[0]['id']);
			}
		}
		
		if(($interaction_type == 6 || $interaction_type == 8) && !empty($template_id) && !empty($interaction_id))
		{
			if($interaction_type == 6)
			{
				$match = array('id'=>$template_id);
				$result = $this->email_library_model->select_records('',$match,'','=');
			}
			else
			{
				$match = array('id'=>$template_id);
				$result = $this->bomb_library_model->select_records('',$match,'','=');
			}
			
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
					$match = array('created_by'=>$this->user_session['id']);
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
				$data['is_unsubscribe'] 			= !empty($rowdatains['is_unsubscribe'])?$rowdatains['is_unsubscribe']:'1';
				$data['email_type'] 				= 'Intereaction_plan';
				$data['interaction_id']				= $interaction_id;
				$data['email_send_auto']			= $this->input->post('send_mailsms_auto');
				$data['modified_by'] 				= $this->user_session['id'];
				$data['modified_date'] 				= date('Y-m-d H:i:s');
				//$data['status'] 					= 'Active';
				
				$match = array('interaction_id'=>$interaction_id);
				$interaction_exist = $this->email_campaign_master_model->select_records('',$match,'','=');
				
				if($interaction_type == '8')
					$data['email_blast_type'] = 1;
				
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
						//$random = substr(md5(rand()),0,7);
						$bgTempPath = $this->config->item('attachment_temp');
						//$file_name =  pathinfo($bgTempPath.$files[$i]);
						//$file_name = $random.".".$file_name['extension'];
						$this->imageupload_model->copyfile($bgImgPath,$files[$i],$files[$i]);
						$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
						 
						if(file_exists($bgTempPath.$files[$i]))
						{
							$idata['email_campaign_id'] = $email_campaign_id;
							$idata['attachment_name'] = $files[$i];
							//pr($idata);
							$this->email_campaign_master_model->insert_email_campaign_attachments($idata);
							@unlink($bgTempPath.$files[$i]);
						}
						unset($idata);
						
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
				$data['modified_by'] 				= $this->user_session['id'];
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
				$ctdata['modified_by'] = $this->user_session['id'];
				
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
						$ctdata['modified_by'] = $this->user_session['id'];
						
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
						$ctdata['modified_by'] = $this->user_session['id'];
						
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
					$ctdata['modified_by'] = $this->user_session['id'];
					
					$this->obj->update_record($ctdata);
					unset($ctdata);
				}
				
			}
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////
		
		$table = "interaction_plan_contacts_trans as ct";
		$fields = array('ct.interaction_plan_id','ct.start_date','cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name,cm.first_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
		$where = array('ct.interaction_plan_id'=>$plan_id);
		$join_tables = array(
							'contact_master as cm jointype direct'=>'cm.id = ct.contact_id',
							'contact_address_trans as cat'=>'cat.contact_id = cm.id',
						);
		$group_by='cm.id';
		
		$addcontactdata = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
		
		//pr($addcontactdata);
		
		//exit;
		
		///////////////////////////////////////////////////////////////////////////		
		
		if(!empty($addcontactdata))
		{
			//////////////////// Integrate User Work time config ///////////////////
			
			if(!empty($assigned_user_id))
			{
			
				//echo $assigned_user_id;
				
				//Get Working Days
				
				//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
				$new_user_id = $assigned_user_id;
				
				//echo $new_user_id;
				
				$match = array("user_id"=>$new_user_id);
				$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
				
				//pr($worktimedata);exit;
				
				$match = array("user_id"=>$new_user_id,"rule_type"=>1);
				$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
				
				//pr($worktimespecialdata);exit;
				
				$match = array("user_id"=>$new_user_id);
				$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
				
				//pr($worktimeleavedata);exit;
				
				$user_work_off_days = array();
				
				if(!empty($worktimedata[0]['id']))
				{
					if(empty($worktimedata[0]['if_mon']))
						$user_work_off_days[] = 'Mon';
					if(empty($worktimedata[0]['if_tue']))
						$user_work_off_days[] = 'Tue';
					if(empty($worktimedata[0]['if_wed']))
						$user_work_off_days[] = 'Wed';
					if(empty($worktimedata[0]['if_thu']))
						$user_work_off_days[] = 'Thu';
					if(empty($worktimedata[0]['if_fri']))
						$user_work_off_days[] = 'Fri';
					if(empty($worktimedata[0]['if_sat']))
						$user_work_off_days[] = 'Sat';
					if(empty($worktimedata[0]['if_sun']))
						$user_work_off_days[] = 'Sun';
				}
				
				$special_days = array();
				
				if(!empty($worktimespecialdata))
				{
					foreach($worktimespecialdata as $row)
					{
						$day_string = '';
						if(!empty($row['nth_day']) && !empty($row['nth_date']))
						{
							switch($row['nth_day'])
							{
								case 1:
								$day_string .= 'First ';
								break;
								case 2:
								$day_string .= 'Second ';
								break;
								case 3:
								$day_string .= 'Third ';
								break;
								case 4:
								$day_string .= 'Fourth ';
								break;
								case 5:
								$day_string .= 'Last ';
								break;
							}
							switch($row['nth_date'])
							{
								case 1:
								$day_string .= 'Day';
								break;
								case 2:
								$day_string .= 'Weekday';
								break;
								case 3:
								$day_string .= 'Weekend';
								break;
								case 4:
								$day_string .= 'Monday';
								break;
								case 5:
								$day_string .= 'Tuesday';
								break;
								case 6:
								$day_string .= 'Wednesday';
								break;
								case 7:
								$day_string .= 'Thursday';
								break;
								case 8:
								$day_string .= 'Friday';
								break;
								case 9:
								$day_string .= 'Saturday';
								break;
								case 10:
								$day_string .= 'Sunday';
								break;
								default:
								break;
							}
							
							$special_days[] = $day_string;
							
						}
					}
				}
				
				$leave_days = array();
				
				foreach($worktimeleavedata as $row)
				{
					if(!empty($row['from_date']))
					{
						$leave_days[] = $row['from_date'];
						if(!empty($row['to_date']))
						{
							
							//$from_date = date('Y-m-d',strtotime($row['from_date']));
							
							$from_date = date('Y-m-d', strtotime($row['from_date'] . ' + 1 day'));
							
							$to_date = date('Y-m-d',strtotime($row['to_date']));
							
							//echo $from_date."-".$to_date;
							
							while($from_date <= $to_date)
							{
								$leave_days[] = $from_date;
								$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
							}
						}
					}
				}
				
				//pr($user_work_off_days);
				
				//pr($special_days);
				
				//pr($leave_days);
				
			}
					
			////////////////////////////////////////////////////////////////////////
			
		
			foreach($addcontactdata as $row)
			{
				if($row != '')
				{
					//$icdata['interaction_plan_id'] = $plan_id;
					//$icdata['contact_id'] = $row['id'];
					//$icdata['interaction_plan_interaction_id'] = $last_id;
					
					$interaction_id = $this->input->post('interaction_id');
					$contact_interaction_plan_interaction_id = $this->obj->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
					
					if(!empty($contact_interaction_plan_interaction_id->id))
					{
						$icdata['id'] = $contact_interaction_plan_interaction_id->id;
					
					$icdata['interaction_type'] = $this->input->post('slt_interaction_type');
					
					if($this->input->post('rad_start_type') == '1')
					{
						$count = $this->input->post('txt_interaction_stat_1');
						$counttype = $this->input->post('slt_nub_type_1');
						
						$newtaskdate = date("Y-m-d",strtotime($row['start_date']."+ ".$count." ".$counttype));
						
						$newtaskdate1 = date("Y-m-d",strtotime($row['start_date']."+ ".$count." ".$counttype));
						
						////////////////////////////////////////////////////////
						
						$repeatoff = 1;
						
						while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
						{
							// Check for Work off days
							// echo $newtaskdate;
							$day_of_date = date('D', strtotime($newtaskdate1));
							$new_special_days = array();
							
							if(!empty($special_days))
							{
								foreach($special_days as $mydays)
								{
									if (strpos($mydays,'Weekday') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										elseif (strpos($mydays,'Weekend') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										else
											$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
								}
							}
							
							//pr($new_special_days);
							
							if(!empty($user_work_off_days) && in_array($day_of_date,$user_work_off_days))
							{
								//echo 'work off'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
							{
								//echo 'special days'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							elseif(!empty($leave_days) && in_array($newtaskdate1,$leave_days))
							{
								//echo 'leave'."<br>";
								$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
							}
							else
							{
								//echo 'else';
								$repeatoff = 0;
								$newtaskdate = $newtaskdate1;
							}
							
							//echo $repeatoff;
							
						}
						
						//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
						
						///////////////////////////////////////////////////////
						
						$icdata['task_date'] = $newtaskdate;
					}
					elseif($this->input->post('rad_start_type') == '2')
					{
						$count = $this->input->post('txt_interaction_stat_2');
						$counttype = $this->input->post('slt_nub_type_2');
						
						$interaction_id = $this->input->post('slt_interaction_stat_2');
						
						$interaction_res = $this->obj->get_contact_interaction_task_date($interaction_id,$row['id']);
						
						//pr($interaction_res);
						
						//echo $interaction_res->task_date;
						
						if(!empty($interaction_res->task_date))
						{
							$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
							$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
							////////////////////////////////////////////////////////
							
							$repeatoff = 1;
							
							while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
							{
								// Check for Work off days
								// echo $newtaskdate;
								$day_of_date = date('D', strtotime($newtaskdate1));
								$new_special_days = array();
								
								if(!empty($special_days))
								{
									foreach($special_days as $mydays)
									{
										if (strpos($mydays,'Weekday') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										elseif (strpos($mydays,'Weekend') !== false) {
											$nthday = explode(" ",$mydays);
											if(!empty($nthday[0]))
											{
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
												$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
											}
										}
										else
											$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
									}
								}
								
								//pr($new_special_days);
								//exit;
								
								if(!empty($user_work_off_days) && in_array($day_of_date,$user_work_off_days))
								{
									//echo 'work off'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
								{
									//echo 'special days'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								elseif(!empty($leave_days) && in_array($newtaskdate1,$leave_days))
								{
									//echo 'leave'."<br>";
									$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
								}
								else
								{
									//echo 'else';
									$repeatoff = 0;
									$newtaskdate = $newtaskdate1;
								}
								
								//echo $repeatoff;
								
							}
							
							//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
							
							///////////////////////////////////////////////////////
							
							//echo $newtaskdate;
							
							$icdata['task_date'] = $newtaskdate;
						}
						
					}
					else
					{
						$icdata['task_date'] = date('Y-m-d',strtotime($this->input->post('rad_start_type_date')));
					}
					
					//$icdata['created_date'] = date('Y-m-d H:i:s');
					//$icdata['created_by'] = $this->user_session['id'];
					$sendemaildate = $icdata['task_date'];
					//pr($icdata);//exit;
					
					$this->obj->update_contact_communication_record($icdata);
					
					unset($icdata);
					
					$agent_name = '';
					if(!empty($assigned_user_id))
					{
						$table ="login_master as lm";   
						$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
						$join_tables = array('user_master as um'=>'lm.user_id = um.id');
						$wherestring = 'lm.id = '.$assigned_user_id;
						$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
						if(!empty($agent_datalist))
						{
							if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
								$agent_name = $agent_datalist[0]['admin_name'];
							else
								$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
						}
					}
	
					if(($interaction_type == 6 || $interaction_type == 8) && !empty($email_campaign_id) && !in_array($row['id'],$email_send))
					{
						$cdata1['email_campaign_id'] = $email_campaign_id;
						if($interaction_type == 6)
						{
							$match = array('id'=>$template_id);
							$result = $this->email_library_model->select_records('',$match,'','=');
						}
						else
						{
							$match = array('id'=>$template_id);
							$result = $this->bomb_library_model->select_records('',$match,'','=');
						}
						
						if(!empty($result[0]['id']))
						{
							$rowdatainst = $result[0];
					
							$cdata1['contact_id'] = $row['id'];
							$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$row['first_name'],
												'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
												'Contact Last Name'=>$row['last_name'],
												'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
												'Contact Company Name'=>$row['company_name'],
												);
							
							$content = $rowdatainst['email_message'];
							$title = $rowdatainst['template_subject'];
							
							//pr($emaildata);
							
							$cdata1['template_subject'] 	= $title;
							$cdata1['email_message'] 	= $content;
							$pattern = "{(%s)}";
							
							$map = array();
							
							if($emaildata != '' && count($emaildata) > 0)
							{
								foreach($emaildata as $var => $value)
								{
									$map[sprintf($pattern, $var)] = $value;
								}
								$finaltitle = strtr($title, $map);				
								$output = strtr($content, $map);
								
								$cdata1['template_subject'] = $finaltitle;
								$finlaOutput = $output;
								$cdata1['email_message'] = $finlaOutput;
							}
							$cdata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
							//$from_data .= $contact_id[$i].",";
							$this->email_campaign_master_model->insert_email_campaign_recepient_trans($cdata1);
						}
			
					}
					elseif($interaction_type == 3 && !empty($sms_campaign_id) && !in_array($row['id'],$sms_send))
					{
						$cdata1['sms_campaign_id'] = $sms_campaign_id;
						$match = array('id'=>$template_id);
						$result = $this->sms_texts_model->select_records('',$match,'','=');
						
						if(!empty($result[0]['id']))
						{
							$rowdatainst = $result[0];
					
							$cdata1['contact_id'] = $row['id'];
							$cdata1['sms_campaign_id'] = $sms_campaign_id;
							$emaildata = array(
												'Date'=>date('Y-m-d'),
												'Day'=>date('l'),
												'Month'=>date('F'),
												'Year'=>date('Y'),
												'Day Of Week'=>date("w",time()),
												'Agent Name'=>$agent_name,
												'Contact First Name'=>$row['first_name'],
												'Contact Spouse/Partner First Name'=>$row['spousefirst_name'],
												'Contact Last Name'=>$row['last_name'],
												'Contact Spouse/Partner Last Name'=>$row['spouselast_name'],
												'Contact Company Name'=>$row['company_name'],
												'Contact Address'=>$row['address_line1'].' '.$row['address_line2'],
												'Contact City'=>$row['city'],
												'Contact State'=>$row['state'],
												'Contact Zip'=>$row['zip_code'],
												);
							
							$content = $rowdatainst['sms_message'];
							$cdata1['sms_message'] 	= $content;
							$pattern = "{(%s)}";
							
							$map = array();
							
							if($emaildata != '' && count($emaildata) > 0)
							{
								foreach($emaildata as $var => $value)
								{
									$map[sprintf($pattern, $var)] = $value;
								}
								$output = strtr($content, $map);
								
								$finlaOutput = $output;
								$cdata1['sms_message'] = $finlaOutput;
							}
							$cdata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
							//$from_data .= $contact_id[$i].",";
							//pr($cdata1);
							$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($cdata1);
							//echo $this->db->last_query();
						}
						//exit;
						
					}
					
					}
				}
			}
			
			unset($user_work_off_days);
			unset($special_days);
			unset($leave_days);
			
			//exit;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		
		//////////////////// Update task date of related interactions //////////////////////////
		
		$table = "interaction_plan_interaction_master as ipim";
		$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
		$join_tables = array(
							'interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
							'admin_users as au' => 'au.id = ipim.created_by',
							'interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
							);
		
		$group_by='ipim.id';
		
		$where1 = array('ipim.interaction_plan_id'=>$plan_id);
		
		$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
		
	//	echo "here";exit;
		
		//////////////// Update Cintact Interaction Plan-Interaction Transaction /////////////////////
		
		
		if(count($interaction_list) > 0)
		{
			foreach($interaction_list as $row1)
			{
			
				$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
			
				//////////////////// Integrate User Work time config ///////////////////
				
				if(!empty($assigned_user_id))
				{
				
					//echo $assigned_user_id;
					
					//Get Working Days
					
					//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
					$new_user_id = $assigned_user_id;
					
					//echo $new_user_id;
					
					$match = array("user_id"=>$new_user_id);
					$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_config_master');
					
					//pr($worktimedata);exit;
					
					$match = array("user_id"=>$new_user_id,"rule_type"=>1);
					$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','work_time_special_rules');
					
					//pr($worktimespecialdata);exit;
					
					$match = array("user_id"=>$new_user_id);
					$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc','user_leave_data');
					
					//pr($worktimeleavedata);exit;
					
					$user_work_off_days1 = array();
					
					if(!empty($worktimedata[0]['id']))
					{
						if(empty($worktimedata[0]['if_mon']))
							$user_work_off_days1[] = 'Mon';
						if(empty($worktimedata[0]['if_tue']))
							$user_work_off_days1[] = 'Tue';
						if(empty($worktimedata[0]['if_wed']))
							$user_work_off_days1[] = 'Wed';
						if(empty($worktimedata[0]['if_thu']))
							$user_work_off_days1[] = 'Thu';
						if(empty($worktimedata[0]['if_fri']))
							$user_work_off_days1[] = 'Fri';
						if(empty($worktimedata[0]['if_sat']))
							$user_work_off_days1[] = 'Sat';
						if(empty($worktimedata[0]['if_sun']))
							$user_work_off_days1[] = 'Sun';
					}
					
					$special_days1 = array();
					
					if(!empty($worktimespecialdata))
					{
						foreach($worktimespecialdata as $row)
						{
							$day_string = '';
							if(!empty($row['nth_day']) && !empty($row['nth_date']))
							{
								switch($row['nth_day'])
								{
									case 1:
									$day_string .= 'First ';
									break;
									case 2:
									$day_string .= 'Second ';
									break;
									case 3:
									$day_string .= 'Third ';
									break;
									case 4:
									$day_string .= 'Fourth ';
									break;
									case 5:
									$day_string .= 'Last ';
									break;
								}
								switch($row['nth_date'])
								{
									case 1:
									$day_string .= 'Day';
									break;
									case 2:
									$day_string .= 'Weekday';
									break;
									case 3:
									$day_string .= 'Weekend';
									break;
									case 4:
									$day_string .= 'Monday';
									break;
									case 5:
									$day_string .= 'Tuesday';
									break;
									case 6:
									$day_string .= 'Wednesday';
									break;
									case 7:
									$day_string .= 'Thursday';
									break;
									case 8:
									$day_string .= 'Friday';
									break;
									case 9:
									$day_string .= 'Saturday';
									break;
									case 10:
									$day_string .= 'Sunday';
									break;
									default:
									break;
								}
								
								$special_days1[] = $day_string;
								
							}
						}
					}
					
					$leave_days1 = array();
					
					foreach($worktimeleavedata as $row)
					{
						if(!empty($row['from_date']))
						{
							$leave_days1[] = $row['from_date'];
							if(!empty($row['to_date']))
							{
								
								//$from_date = date('Y-m-d',strtotime($row['from_date']));
								
								$from_date = date('Y-m-d', strtotime($row['from_date'] . ' + 1 day'));
								
								$to_date = date('Y-m-d',strtotime($row['to_date']));
								
								//echo $from_date."-".$to_date;
								
								while($from_date <= $to_date)
								{
									$leave_days1[] = $from_date;
									$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
								}
							}
						}
					}
					
					//pr($user_work_off_days1);
					
					//pr($special_days1);
					
					//pr($leave_days1);
					
				}
						
				////////////////////////////////////////////////////////////////////////
			
				if(!empty($addcontactdata))
				{
					foreach($addcontactdata as $row)
					{
						//$iccdata['interaction_plan_id'] = $plan_id;
						//$iccdata['contact_id'] = $row['id'];
						//$iccdata['interaction_plan_interaction_id'] = $row1['id'];
						//$iccdata['interaction_type'] = $row1['interaction_type'];
						//pr($row1);
						//exit;
						$interaction_id = $row1['id'];
						$contact_interaction_plan_interaction_id = $this->interaction_model->get_contact_interaction_task_date_not_done($interaction_id,$row['id']);
						
						//pr($contact_interaction_plan_interaction_id);
						//exit;
						
						if(!empty($contact_interaction_plan_interaction_id->id))
						{
							$iccdata1['id'] = $contact_interaction_plan_interaction_id->id;
						
						
						if($row1['start_type'] == '1')
						{
							$count = $row1['number_count'];
							$counttype = $row1['number_type'];
							
							///////////////////////////////////////////////////////////////
							
							$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$row['id']);
							$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','','');
							
							//pr($plan_contact_data);exit;
							
							///////////////////////////////////////////////////////////////
							
							if(!empty($plan_contact_data[0]['start_date']))
							{
								$newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
								
								$newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
						
								////////////////////////////////////////////////////////
								
								$repeatoff = 1;
								
								while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
								{
									// Check for Work off days
									// echo $newtaskdate;
									$day_of_date = date('D', strtotime($newtaskdate1));
									$new_special_days = array();
									
									if(!empty($special_days1))
									{
										foreach($special_days1 as $mydays)
										{
											if (strpos($mydays,'Weekday') !== false) {
												$nthday = explode(" ",$mydays);
												if(!empty($nthday[0]))
												{
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
												}
											}
											elseif (strpos($mydays,'Weekend') !== false) {
												$nthday = explode(" ",$mydays);
												if(!empty($nthday[0]))
												{
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
												}
											}
											else
												$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
										}
									}
									
									//pr($new_special_days);
									
									if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
									{
										//echo 'work off'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
									{
										//echo 'special days'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
									{
										//echo 'leave'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									else
									{
										//echo 'else';
										$repeatoff = 0;
										$newtaskdate = $newtaskdate1;
									}
									
									//echo $repeatoff;
									
								}
								
								//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
								
								///////////////////////////////////////////////////////
								
								$iccdata1['task_date'] = $newtaskdate;
							}
						}
						elseif($row1['start_type'] == '2')
						{
							$count = $row1['number_count'];
							$counttype = $row1['number_type'];
							
							$interaction_id = $row1['interaction_id'];
							
							$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$row['id']);
							
							//pr($interaction_res);
							
							//echo $interaction_res->task_date;
							
							if(!empty($interaction_res->task_date))
							{
								$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
								
								$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
							
								////////////////////////////////////////////////////////
								
								$repeatoff = 1;
								
								while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
								{
									// Check for Work off days
									// echo $newtaskdate;
									$day_of_date = date('D', strtotime($newtaskdate1));
									$new_special_days = array();
									
									if(!empty($special_days1))
									{
										foreach($special_days1 as $mydays)
										{
											if (strpos($mydays,'Weekday') !== false) {
												$nthday = explode(" ",$mydays);
												if(!empty($nthday[0]))
												{
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
												}
											}
											elseif (strpos($mydays,'Weekend') !== false) {
												$nthday = explode(" ",$mydays);
												if(!empty($nthday[0]))
												{
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
													$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
												}
											}
											else
												$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
										}
									}
									
									//pr($new_special_days);
									
									if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
									{
										//echo 'work off'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
									{
										//echo 'special days'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
									{
										//echo 'leave'."<br>";
										$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
									}
									else
									{
										//echo 'else';
										$repeatoff = 0;
										$newtaskdate = $newtaskdate1;
									}
									
									//echo $repeatoff;
									
								}
								
								//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
								
								///////////////////////////////////////////////////////
								
								//echo $newtaskdate;
								
								//$icdata['task_date'] = $newtaskdate;
							
								$iccdata1['task_date'] = $newtaskdate;
							}
							
						}
						else
						{
							$iccdata1['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
						}
						
						//$iccdata['created_date'] = date('Y-m-d H:i:s');
						//$iccdata['created_by'] = $this->user_session['id'];
						
						$sendemaildate = $iccdata1['task_date'];
						
						//pr($iccdata1);
						
						if(!empty($contact_interaction_plan_interaction_id->id))
						{
							$this->interaction_model->update_contact_communication_record($iccdata1);
						}
						//exit;
						unset($iccdata1);
						
						// Update old Email or SMS //
						
						if($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8)
						{
							//echo $row1['id']." ".$row['id']."<br>";;
							$table = "email_campaign_master as ecm";
							$fields = array('ecr.*');
							$join_tables = array(
								'email_campaign_recepient_trans as ecr' => 'ecr.email_campaign_id = ecm.id'
								);
		
							//$group_by='ipim.id';
		
							$where1 = array('ecm.interaction_id'=>$row1['id'],'ecr.contact_id'=>$row['id']);
		
							$email_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
							if(count($email_campaign_data) > 0)
							{
								if(!empty($email_campaign_data[0]['id']))
								{
									$idata['id'] = $email_campaign_data[0]['id'];
									$idata['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
									$this->email_campaign_master_model->update_email_campaign_trans($idata);
									//echo $this->db->last_query()."<br>";
									unset($idata);
								}
							}
						}
						elseif($row1['interaction_type'] == 3)
						{
							$table = "sms_campaign_master as scm";
							$fields = array('scr.*');
							$join_tables = array(
								'sms_campaign_recepient_trans as scr' => 'scr.sms_campaign_id = scm.id'
								);
		
							//$group_by='ipim.id';
		
							$where1 = array('scm.interaction_id'=>$row1['id'],'scr.contact_id'=>$row['id']);
		
							$sms_campaign_data = $this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$where1);
							if(count($sms_campaign_data) > 0)
							{
								if(!empty($sms_campaign_data[0]['id']))
								{
									$idata['id'] = $sms_campaign_data[0]['id'];
									$idata['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
									$this->sms_campaign_recepient_trans_model->update_record($idata);
									unset($idata);
								}
							}
						}
						
						///////////////////////////////
						
						}
		
					}
				
				}
				
				unset($user_work_off_days1);
				unset($special_days1);
				unset($leave_days1);
				
			}
		}
		
		//exit;
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$email_id = $this->input->post('interaction_id');
		$pagingid = $this->obj->getemailpagingid($email_id);

		//redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
		
        redirect('user/'.$this->viewName."/".$plan_id.'/'.$pagingid);				
		//redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_edit_success_msg'));
        
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
    @Description: Function for Delete contacts Profile By user
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
        redirect('user/'.$this->viewName.'/'.$this->uri->segment(4));
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_delete_success_msg'));
    }
	
	
	 /*
    @Description: Function for Unpublish contacts Profile By user
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
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_unpublish_msg'));
    }
	
	/*
    @Description: Function for publish contacts Profile By user
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
		redirect('user/'.$this->viewName);
        //redirect('user/'.$this->viewName.'/msg/'.$this->lang->line('common_publish_msg'));
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
    @Description: Function use to Delete contact in by user
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	public function ajax_delete_all()
	{
		//pr($_POST);exit;
		$id = $this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			unset($id);
		}
		
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_record($array_data[$i]);
		}
		echo 1;
	}
	/*
    @Description: Function use to Active and Inactive contact in by user
    @Author: Kaushik Valiya
    @Input: 
    @Output: - 
    @Date: 16-07-2014
    */
	
	public function ajax_Active_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
		$pagingid='';
		if(!empty($id))
		{
			$cdata['id'] = $id;
			$cdata['status'] = '1';
			$this->obj->update_record($cdata);
			$email_id = $id;
			$pagingid = $this->obj->getemailpagingid($email_id);
			
			unset($id);
		}
		elseif(!empty($array_data))
        {
			$email_id = $array_data[0];
			$pagingid = $this->obj->getemailpagingid($email_id);
			for($i=0;$i<count($array_data);$i++)
			{
				$data['id']=$array_data[$i];
				$data['status']='1';
				$this->obj->update_record($data);
			}
		}
		echo $pagingid;
	}
	public function ajax_Inactive_all()
	{
		//pr($_POST);exit;
		$id=$this->input->post('single_active_id');
		$array_data=$this->input->post('myarray');
		$pagingid='';
		$msg = '';
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
				
				$email_id = $id;
				$pagingid = $this->obj->getemailpagingid($email_id);
			}
			unset($id);
		}
		elseif(!empty($array_data))
		{			
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
				}
			}
			$email_id = $array_data[0];
			$pagingid = $this->obj->getemailpagingid($email_id);
		}
		
		$data_temp['msg'] = $msg;
		$data_temp['page'] = $pagingid;
		
		echo json_encode($data_temp);
		//echo $pagingid;
		
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
    @Description: Function use to select template by user
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
			//echo $this->db->last_query();
			$cdata['subcategory'] = array();
			
			if(!empty($cdata['plan_type'][0]['id']) && ($catid > 0 || !empty($selected)))
			{
				//$match = array('template_category'=>$catid,'template_subcategory'=>$subcatid,'created_by'=>$this->user_session['id'],"email_send_type"=>2);
				//'created_by'=>$this->user_session['id']]
				$wherestring = '';
				if($catid > 0)
					$wherestring = 'tm.template_category = '.$catid.' AND ';
				$match = array('template_category'=>$catid);
				
				switch(strtolower($cdata['plan_type'][0]['name']))
				{
					case 'email':
					$table = "email_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= 'tm.email_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'bomb bomb emails':
					$fields = array('id','template_name','template_category');
					$cdata['template_list'] = $this->bomb_library_model->select_records($fields,$match,'','=','','','',$sortfield,$sortby);
					break;
					case 'text':
					$table = "sms_text_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
                                                                'login_master as lm' => 'lm.id = tm.created_by',
                                                            );
					$wherestring .= 'sms_send_type = 2 AND (lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'call':
					$table = " phone_call_script_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'envelope':
					$table = "envelope_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					
					break;
					case 'label':
					$table = "label_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
					break;
					case 'letter':
					$table = "letter_template_master as tm";
					$fields = array('tm.id,tm.template_name,tm.template_category');
					$join_tables = array(
											'login_master as lm' => 'lm.id = tm.created_by',
										 );
					$wherestring .= '(lm.user_type = "1" OR lm.user_type = "2" OR tm.created_by IN ('.$this->user_session['agent_id'].'))';
					$group_by = 'tm.id';
					$cdata['template_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','tm.id','desc',$group_by,$wherestring);
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
		 	$this->load->view("user/".$this->viewName."/attachmentlist", $data);
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
	
	/*
		@Description: Function for view contacts of interaction plans
		@Author: Sanjay Chabhadiya
		@Input: -  Interaction plan id and contact list
		@Output: - 
		@Date: 15-10-2014
   	*/
	
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
	
}