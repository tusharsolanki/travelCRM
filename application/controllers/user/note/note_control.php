<?php 
/*
    @Description: Interaction Plans controller
    @Author: Kaushik Valiya
    @Input: 
    @Output: 
    @Date: 19-02-2015
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class note_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('interaction_plans_model');
		$this->load->model('interaction_plan_masters_model');
		$this->load->model('interaction_model');
		//$this->load->model('imageupload_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('sms_campaign_recepient_trans_model');
		$this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('email_library_model');
		$this->load->model('sms_texts_model');
		$this->obj = $this->interaction_plans_model;
		$this->obj1 = $this->interaction_plan_masters_model;
		$this->obj2 = $this->interaction_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	
	

    /*
    @Description: Function for Get All Interaction List
    @Author: Kaushik Valiya
    @Input: - Search value or null
    @Output: - all interaction list
    @Date: 19-02-2015
    */
    public function index()
    {
		
		//check user right
		check_rights('communications_add');
		$match = array('name'=>'active');
        $data['interaction_plan_status'] = $this->obj1->select_records1('',$match,'','=','','','','name','asc','interaction_plan__status_master');
		
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."interaction_plans/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$group_by='cm.id';
		$where='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$where);
		//pr($data['contact_list']);exit;
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
		
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$data['msg'] =	$this->message_session['msg'];
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    
		
		}
	

    
	
	public function search_contact_ajax()
    {
	
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."note/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
	
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$perpage = $this->input->post('perpage');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$search_tag = $this->input->post('search_tag');
		
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
                $config['per_page'] = '50';
				//$data['perpage'] = '50';
            }
        }
		
		if(!empty($sortfield) && !empty($sortby))
        {
                //$sortfield = $this->input->post('sortfield');
                $data['sortfield'] = $sortfield;
                //$sortby = $this->input->post('sortby');
                $data['sortby'] = $sortby;
        }
        else
        {
			$data['sortfield'] = 'cm.first_name';
			$data['sortby'] = 'asc';
        }
		
		
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
		$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';		
		//$where = array_merge($where_search,$where);
		//pr($where); exit;
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
							'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$group_by='cm.id';
		
		if(!empty($search_tag))
		{
			$tag = explode(",",$search_tag);
			if(!empty($tag))
			{
				for($i=0;$i<count($tag);$i++)
				{
					$tag_array = array();
					$tag_array = array('(select * from contact_tag_trans where tag = "'.$tag[$i].'") as ctat'.$i.' jointype direct'=>'ctat'.$i.'.contact_id = cm.id');
					$join_tables = array_merge($join_tables,$tag_array);
				}
			}
		}
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where,$wherestring);
		//echo $this->db->last_query();
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$where,$wherestring,'1');
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
	
        	$this->load->view("user/".$this->viewName."/add_contact_popup_ajax", $data);
	
		
       // $this->load->view("user/".$this->viewName."/add_contact_popup_ajax", $data);
	}

    /*
    @Description: Function for Insert New Interaction Plan data
    @Author: Kaushik Valiya
    @Input: - Details of new contacts which is inserted into DB
    @Output: - List of contacts with new inserted records
    @Date: 19-02-2015
    */
    public function insert_data()
    {
		
		//pr($_POST);exit;
		//echo  $this->input->post('note');exit;
		$submitbtn_action=$this->input->post('submitbtn_action');
		
		
		$interaction_contacts = $this->input->post('finalcontactlist');
		//pr($interaction_contacts);exit;
		$interaction_contacts = explode(",",$interaction_contacts);
		
		
		
		if(!empty($interaction_contacts))
		{
			
			foreach($interaction_contacts as $row)
			{
				if($row != '')
				{
					if(!empty($row))
					{
						$data_conv['contact_id'] = $row;
						$data_conv['description'] = $this->input->post('note');
						$data_conv['created_date'] = date('Y-m-d H:i:s');
						$data_conv['log_type'] = '12';
						$data_conv['created_by'] = $this->user_session['id'];
						$data_conv['status'] = '1';
						//interaction_plans_model
			//			contact_conversations_trans
						$this->obj->insert_contact_converaction_trans_record($data_conv);
				}
				
				unset($icdata);
				}
			}
		}
		
		//exit;
			$msg = $this->lang->line('common_add_success_msg');
        	$newdata = array('msg'  => $msg);
        	$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);				
		
		//redirect('admin/'.$this->viewName.'/msg/'.$this->lang->line('common_add_success_msg'));
    
		}
    
	
	public function add_contacts_to_interaction_plan()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	
	
	
}