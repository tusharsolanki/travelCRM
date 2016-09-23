<?php 
/*
    @Description: social media post controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 08-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class analytics_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_admin_login();
		$this->load->model('socialmedia_post_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('contacts_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('task_model');
		$this->load->library('mpdf');
		
		$this->obj = $this->socialmedia_post_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
		ini_set('memory_limit', '-1');
    }
	

    /*
		@Description: Function for Get total contacts,assign contacts,not assign contacts,new contacts,client contacts list
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 10-10-2014
    */

    public function index()
    {	
		//check user right
		check_rights('analytics');
		$data['total_lead_count'] = $this->contacts_model->get_all_contacts_count();
		$data['assigned_lead_count'] = $this->contacts_model->get_assigned_contacts_count();
		$data['not_assigned_lead_count'] = $this->contacts_model->get_not_assigned_contacts_count();
		$data['email_sent_against_interaction_plan_count'] = $this->email_campaign_master_model->email_sent_against_interaction_plan_count();
		$match = array('is_completed'=>'0');
		$data['open_task_list'] = $this->task_model->select_records('',$match,'','=','','','','','','','1');
		
		$match = array('is_completed'=>'1');
		$data['completed_task'] = $this->task_model->select_records('',$match,'','=','','','','','','','1');
		
		$match = array('contact_status'=>'4');
		$contact_id = $this->contacts_model->select_records('',$match,'','=');
		
		$data['contact_lead'] = $this->contacts_model->get_all_client_contact('1');
		//pr($data['contact_lead']); exit;
/*		select 
DATE_FORMAT(m1, '%b-%Y') created_date,count(cm.id) contact_count
from
(
select 
('2013-01-10' - INTERVAL DAYOFMONTH('2013-01-10')-1 DAY) 
+INTERVAL m MONTH as m1
from
(
select @rownum:=@rownum+1 as m from
(select 1 union select 2 union select 3 union select 4) t1,
(select 1 union select 2 union select 3 union select 4) t2,
(select 1 union select 2 union select 3 union select 4) t3,
(select 1 union select 2 union select 3 union select 4) t4,
(select @rownum:=-1) t0
) d1
) d2 
LEFT JOIN contact_master cm ON DATE_FORMAT(cm.created_date,'%b-%Y') = DATE_FORMAT(m1, '%b-%Y')
where m1<='2014-10-21'
group by m1
order by m1

*/
		$getcontact = $this->input->post('assign_contact_data');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
		$date1 = '';$date2 = '';
		if(!empty($getcontact) && $getcontact == 'new_contact')
        {
            $date1 = $this->input->post('date1');
            $date2 = $this->input->post('date2');
            $data['date1'] = $date1;
            $data['date2'] = $date2;	
        }
		else
		{
			$date1 = date('Y-m-d', strtotime('-30 days'));
			$date2 = date('Y-m-d');	
		}
		$data['new_contacts'] = $this->contacts_model->get_last_month_contacts_count($date1,$date2);
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contacts_sortsearchpage_data');
        }
        $data['sortfield']		= 'cm.id';
        $data['sortby']			= 'desc';
        $searchsort_session = $this->session->userdata('contacts_sortsearchpage_data');

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
				//$data['perpage']='10';
            }
        }
        $config['base_url'] = site_url($this->user_type.'/'.$this->viewName);
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

        $table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','cpt.phone_no','cet.email_address,cm.created_type');
//,'CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address'
//,'group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type'
        $group_by = 'cm.id';

		$join_tables = array(
			'contact__status_master as csm' => 'csm.id = cm.contact_status',
			'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
			//'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
			'(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
			'(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
			//'(SELECT catin.* FROM contact_address_trans catin GROUP BY catin.contact_id) AS cat'=>'cat.contact_id = cm.id'
		);
		$joomla_domain=$this->input->post('joomla_domain_contact_data');
		$wherestring = '';
		if(!empty($getcontact) && $getcontact == 'assign_contact_data')
			$wherestring = "cm.id IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans WHERE is_completed = '0')";
		if(!empty($getcontact) && $getcontact == 'joomla_contact_data')
			$wherestring = "cm.joomla_domain_name like '".$joomla_domain."'";
		elseif(!empty($getcontact) && $getcontact == 'not_assign_contact_data')
			$wherestring = "cm.id NOT IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans WHERE is_completed = '0')";
		elseif(!empty($getcontact) && $getcontact == 'new_contact')
		{
			$wherestring = 'cm.created_date >= (DATE_SUB(CURDATE(),INTERVAL 1 MONTH))';
			
			if(!empty($date1) && !empty($date2))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'";
			elseif(!empty($date1))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".date('Y-m-d')."'";
			elseif(!empty($date2))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN 0000-00-00 AND '".$date2."'";
		}
		elseif(!empty($getcontact) && $getcontact == 'client_contact_lead')
		{
			$join = array('contact_contact_status_trans as ccst' => 'ccst.contact_id = cm.id');
			$join_tables = array_merge($join_tables,$join);
			$wherestring = "cm.contact_status = 4";
			$group_by = 'cm.id having count(ccst.contact_id) > 1';
		}
		
		$data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
		
		//echo $this->db->last_query();
		$config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'','1');
		
		
		//jommla
		$table = "joomla_mapping as jm";
        $fields = array('jm.*,count(cm.joomla_domain_name) as total_joomla_contact');
//,'CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address'
//,'group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type'
        $group_by = 'cm.id';

		$join_tables = array(
			'contact_master as cm' => 'jm.domain = cm.joomla_domain_name',
			);
		
		$wherestring1 = 'lw_admin_id = '.$this->admin_session['admin_id'];
		
		$group_by = 'cm.joomla_domain_name';
		$data['joomla_domain'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$data['sortfield'],$data['sortby'],$group_by,$wherestring1);
		/*echo $this->db->last_query();
		pr($data['joomla_domain']);exit;*/
		
		$this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];

        $contacts_sortsearchpage_data = array(
            'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
            'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
            'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
            'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
            'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
            'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
        $this->session->set_userdata('contacts_sortsearchpage_data', $contacts_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
		
		if($this->input->post('result_type') == 'ajax')
			$this->load->view($this->user_type.'/'.$this->viewName.'/contact_ajax_list',$data);
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
			$this->load->view('admin/include/template',$data);
		}
    }

    /*
		@Description: Function for Get graph day or monthly
		@Author: Sanjay Chabhadiya
		@Input: - date1,date2
		@Output: - Graph Data
		@Date: 10-10-2014
    */

    public function graph()
    {	
		$date1 = $this->input->post('date1');
		$date2 = $this->input->post('date2');
		$date3 = date_create($date1);
		$date4 = date_create($date2);
		$diff = date_diff($date3,$date4);
		$chart_type = $diff->format("%a");
		if($chart_type <= 30)
			$data['graph_data']=$this->contacts_model->get_graph_data($date1,$date2);
		elseif($chart_type > 30 && $chart_type <= 365)
			$data['graph_data']=$this->contacts_model->get_monthly_graph_data($date1,$date2);
		else
			$data['graph_data']=$this->contacts_model->get_yearly_graph_data($date1,$date2);
		
		//pr($data['graph_data']);exit;
			
		$this->load->view($this->user_type.'/'.$this->viewName.'/graph',$data);
    }


    /*
		@Description: Function for Completed or pendind task list
		@Author: Sanjay Chabhadiya
		@Input: - is_completed
		@Output: - 
		@Date: 10-10-2014
    */
	
	public function open_completed_task()
	{
		$perpage='';
		$is_completed = $this->input->post('is_completed');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
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
				$data['sortfield'] = $sortfield;
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
		if(!empty($date1) && !empty($date2))
		{
			$date1 = $this->input->post('date1');
			$date2 = $this->input->post('date2');
			$data['date1'] = $date1;
			$data['date2'] = $date2;	
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
		$config['base_url'] = site_url($this->user_type.'/'.$this->viewName."/open_completed_task/");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		if($is_completed == 'complete')
			$where = array('is_completed'=>"'1'");
		else
			$where = array('is_completed'=>"'0'");
		$data['datalist'] = $this->task_model->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
		//echo $this->db->last_query();
		$config['total_rows']= $this->task_model->select_records('','','','','','','','','',$where,'1');
		
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
		$this->load->view($this->user_type.'/'.$this->viewName.'/task_ajax_list',$data);
	}
	
	/*
		@Description: Function for send interaction plan throw email list
		@Author: Sanjay Chabhadiya
		@Input: - between date1 and date2
		@Output: - 
		@Date: 10-10-2014
    */
	
	public function sent_email()
	{
		$perpage='';
		$perpage = trim($this->input->post('perpage'));
		$date1 = $this->input->post('date3');
		$date2 = $this->input->post('date4');
		$data['date1'] = $date1;
		$data['date2'] = $date2;	
		$allflag = $this->input->post('allflag');
		
		$data['email_sent_against_interaction_plan_count'] = $this->email_campaign_master_model->email_sent_against_interaction_plan_count($date1,$date2);
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('task_sortsearchpage_data');
		}
		$sortfield = 'ipm.id';
		$sortby = 'desc';
		$searchsort_session = $this->session->userdata('task_sortsearchpage_data');
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
		$config['base_url'] = site_url($this->user_type.'/'.$this->viewName."/sent_email/");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 4;
			$uri_segment = $this->uri->segment(4);
		}
		
		$table ="email_campaign_recepient_trans ecr";   
		$fields = array('ipm.*,count(*) as total_sent_mail');
		$join_tables = array('email_campaign_master ecm' => 'ecm.id = ecr.email_campaign_id',
							 'interaction_plan_interaction_master ipim' => 'ipim.id = ecm.interaction_id',
							 'interaction_plan_master ipm' => 'ipm.id = ipim.interaction_plan_id'
							 );
		$group_by = 'ipm.id';
		$wherestring = "ecr.is_send='1' AND ipim.interaction_type = 6";
		if(!empty($date1) && !empty($date2))
			$wherestring .= " AND DATE_FORMAT(ecr.sent_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'";

		$result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$wherestring);
		$data['datalist'] = $result;
		
		$config['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','','','',$sortfield,$sortby,$group_by,$wherestring,'1');
		if(!empty($result))
		{
			foreach($result as $row)
			{
				$table ="email_campaign_recepient_trans as ecr";
				$fields = array('ecr.template_subject,ecr.sent_date',"CONCAT_WS(' ',cm.first_name,cm.last_name) as contact_name",'ipi.description,ipm.plan_name');
				$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
									 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
									 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
									 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
									 );
			
				$wherestring = "ecm.email_type = 'Intereaction_plan' AND ipm.id = ".$row['id']." AND ecr.is_send = '1'";
				if(!empty($date1) && !empty($date2))
					$wherestring .= " AND DATE_FORMAT(ecr.sent_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'";
				
				$data['emaildata'][$row['id']] = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ecr.id','desc','',$wherestring);
			}
		}
		//pr($data['emaildata']);exit;
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
		$this->load->view($this->user_type.'/'.$this->viewName.'/sent_communication_mail',$data);
	}
	
	/*
		@Description: Function for generate pdf
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 10-10-2014
    */

	public function generate_pdf()
	{
            ini_set('memory_limit', '-1');
            //error_reporting(E_ALL);
            //ini_set('display_errors', '1');
		//pr($_POST);exit;
		$chk_contact_type_id = $this->input->post('chk_contact_type_id');
		$date1 = $this->input->post('email_cp_start_date');
		$date2 = $this->input->post('email_cp_end_date');
		$date3 = $this->input->post('newcontacts_cp_start_date');
		$date4 = $this->input->post('newcontacts_cp_end_date');
		
		$data['total_lead_count'] = $this->contacts_model->get_all_contacts_count();
		$data['assigned_lead_count'] = $this->contacts_model->get_assigned_contacts_count();
		$data['not_assigned_lead_count'] = $this->contacts_model->get_not_assigned_contacts_count();
		$data['email_sent_against_interaction_plan_count'] = $this->email_campaign_master_model->email_sent_against_interaction_plan_count($date1,$date2);
		$match = array('is_completed'=>'0');
		$data['open_task_list'] = $this->task_model->select_records('',$match,'','=','','','','','','','1');
		
		$match = array('is_completed'=>'1');
		$data['completed_task'] = $this->task_model->select_records('',$match,'','=','','','','','','','1');
		$data['new_contacts'] = $this->contacts_model->get_last_month_contacts_count($date3,$date4);
		$data['contact_lead'] = $this->contacts_model->get_all_client_contact('1');
		
		
		$mypdf = new mPDF('','','','','10','10','20','20','5','8');
		
		$mypdf->SetHTMLHeader('
<div style="text-align:right;width:100%;font-weight:bold;color:#376091;">{PAGENO}</div>
', 'O', true);
$mypdf->SetHTMLFooter('
', 'O', true);
		$html = '<table><tr><td>Number of Leads : </td><td>'.$data['total_lead_count'].'</td><td>Open Task List : </td><td>'.$data['open_task_list'].'</td></tr>';
		 if(!empty($this->modules_unique_name) && in_array('communications',$this->modules_unique_name)){
		$html .= '<tr><td>Leads assigned to campaigns : </td><td>'.$data['assigned_lead_count'].'</td><td>Completed Task List : </td><td>'.$data['completed_task'].'</td></tr>';
		$html .= '<tr><td>Leads not assigned to campaigns : </td><td>'.$data['not_assigned_lead_count'].'</td><td>New Contacts(Leads) : </td><td>'.$data['new_contacts'].'</td></tr>';
		$html .= '<tr><td>No. of Emails Sent against Communication Plans : </td><td>'.$data['email_sent_against_interaction_plan_count'].'</td><td>Lead Conversation List : </td><td>'.$data['contact_lead'].'</td></tr></table>';
		 }
		 else
		 {
			$html .= '</table>';	 
		 }
		$mypdf->WriteHTML($html);
		
		$html = '';
		$last_html = '';
		
		$table = "contact_master as cm";
        $fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','csm.name as contact_status','group_concat(DISTINCT ctm.name ORDER BY ctm.name separator \',\') as contact_type','cpt.phone_no','cet.email_address','CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address');
        $group_by='cm.id';
		$join_tables = array(
                'contact__status_master as csm' => 'csm.id = cm.contact_status',
                'contact_contacttype_trans as ctt' => 'ctt.contact_id = cm.id',
                'contact__type_master as ctm'=>'ctm.id = ctt.contact_type_id',
                '(SELECT cptin.* FROM contact_phone_trans cptin WHERE cptin.is_default = "1" GROUP BY cptin.contact_id) AS cpt'=>'cpt.contact_id = cm.id',
                '(SELECT cetin.* FROM contact_emails_trans cetin WHERE cetin.is_default = "1" GROUP BY cetin.contact_id) AS cet'=>'cet.contact_id = cm.id',
                '(SELECT catin.* FROM contact_address_trans catin GROUP BY catin.contact_id) AS cat'=>'cat.contact_id = cm.id'
            );
		$wherestring = '';
		if(in_array(1,$chk_contact_type_id))
		{
			$html = '<br><b> All Leads : </b><br><br>';
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.id','desc',$group_by,$wherestring);
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/contact_list_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(2,$chk_contact_type_id))
		{
			if(!empty($html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$html = '<br><b> Leads assigned to campaigns : </b><br><br>';
			$wherestring = 'cm.id IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans)';
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.id','desc',$group_by,$wherestring);
			//pr($data['datalist']);exit;
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/contact_list_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(3,$chk_contact_type_id))
		{
			if(!empty($html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$html = '<br><b> Leads not assigned to campaigns : </b><br><br>';
			$wherestring = 'cm.id NOT IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans)';
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.id','desc',$group_by,$wherestring);
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/contact_list_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(8,$chk_contact_type_id))
		{
			$last_html .= '<br><b> Lead Conversation List : </b><br><br>';
			
			$join = array('contact_contact_status_trans as ccst' => 'ccst.contact_id = cm.id');
			$join_tables = array_merge($join_tables,$join);
			$wherestring = "cm.contact_status = 4";
			$group_by1 = 'cm.id having count(ccst.contact_id) > 1';
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.id','desc',$group_by1,$wherestring);
			$last_html .= $this->load->view($this->user_type.'/'.$this->viewName.'/contact_list_pdf',$data,true);
			unset($data['datalist']);
		}
			
		if(in_array(7,$chk_contact_type_id))
		{
			if(!empty($last_html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$last_html .= '<br><b> New Contacts(Leads) : </b><br><br>';
			
			$wherestring = 'cm.created_date >= (DATE_SUB(CURDATE(),INTERVAL 1 MONTH))';
			
			if(!empty($date3) && !empty($date4))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN '".$date3."' AND '".$date4."'";
			elseif(!empty($date3))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN '".$date3."' AND '".date('Y-m-d')."'";
			elseif(!empty($date4))
				$wherestring = "DATE_FORMAT(cm.created_date,'%Y-%m-%d') BETWEEN 0000-00-00 AND '".$date4."'";
			$data['datalist'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','cm.id','desc',$group_by,$wherestring);
			$last_html .= $this->load->view($this->user_type.'/'.$this->viewName.'/contact_list_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(4,$chk_contact_type_id))
		{
			if(!empty($html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$html = '<br><b> Emails Sent against Communication Plans : </b><br><br>';
			$table ="email_campaign_recepient_trans ecr";   
			$fields = array('ipm.*,count(*) as total_sent_mail');
			$join_tables = array('email_campaign_master ecm' => 'ecm.id = ecr.email_campaign_id',
								 'interaction_plan_interaction_master ipim' => 'ipim.id = ecm.interaction_id',
								 'interaction_plan_master ipm' => 'ipm.id = ipim.interaction_plan_id'
								 );
			$group_by = 'ipm.id';
			$wherestring = "ecr.is_send='1' AND ipim.interaction_type = 6";
			if(!empty($date1) && !empty($date2))
				$wherestring .= " AND DATE_FORMAT(ecr.sent_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'";
	
			$result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$wherestring);
			$data['datalist'] = $result;
			//pr($data['datalist']);exit;
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$table ="email_campaign_recepient_trans as ecr";
					$fields = array('ecr.template_subject,ecr.sent_date',"CONCAT_WS(' ',cm.first_name,cm.last_name) as contact_name",'ipi.description,ipm.plan_name');
					$join_tables = array('email_campaign_master as ecm'=>'ecm.id = ecr.email_campaign_id',
										 'contact_master as cm jointype direct'=>'cm.id = ecr.contact_id',
										 'interaction_plan_interaction_master as ipi'=>'ipi.id = ecm.interaction_id',
										 'interaction_plan_master as ipm'=>'ipm.id = ipi.interaction_plan_id'
										 );
					$wherestring = "ecm.email_type = 'Intereaction_plan' AND ipm.id = ".$row['id']." AND ecr.is_send = '1'";
					if(!empty($date1) && !empty($date2))
						$wherestring .= " AND DATE_FORMAT(ecr.sent_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'";
					
					$data['emaildata'][$row['id']] = $this->email_campaign_master_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','ecr.id','desc','',$wherestring);
				}
			}
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/sent_communication_mail_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(5,$chk_contact_type_id))
		{
			if(!empty($html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$html = '<br><b> Open Task List : </b><br><br>';
			$where = array('is_completed'=>"'0'");
			$data['datalist'] = $this->task_model->select_records('','','','','','','','id','desc',$where);
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/task_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(in_array(6,$chk_contact_type_id))
		{
			if(!empty($html))
			{
				$mypdf->WriteHTML($html);
				$mypdf->AddPage();
			}
			
			$html = '<br><b> Completed Task List : </b><br><br>';
			$where = array('is_completed'=>"'0'");
			$data['datalist'] = $this->task_model->select_records('','','','','','','','id','desc',$where);
			$html .= $this->load->view($this->user_type.'/'.$this->viewName.'/task_pdf',$data,true);
			unset($data['datalist']);
		}
		
		if(!empty($last_html))
		{
			$mypdf->WriteHTML($html);
			$mypdf->AddPage();
			$html = $last_html;
		}
		$mypdf->WriteHTML($html);
		$mypdf->Output('analytic'.date('m-d-Y').'.pdf','D');
	}
}