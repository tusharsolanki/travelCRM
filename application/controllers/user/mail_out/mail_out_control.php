
<?php 
/*
    @Description: Envelope Library controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 12-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mail_out_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_user_login();
		$this->load->model('mail_out_model');
		$this->load->model('mail_blast_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('envelope_library_model');
		$this->load->model('label_library_model');
		$this->load->model('letter_library_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('contacts_model');
		$this->load->model('email_library_model');
		$this->load->library('mpdf');
		$this->obj = $this->mail_out_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	/*
		@Description: Function for display home view.
		@Author: Niral Patel
		@Input: - 
		@Output: - 
		@Date: 30-12-2014
    */
	
	public function mail_out_home()
	{
		//check user right
		check_rights('mail_blast');
		$data['main_content'] = 'user/'.$this->viewName."/home";
		$this->load->view('user/include/template',$data);	
	}

   /*
    @Description: Function for Get All Envelope List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all Envelope list
    @Date: 12-08-2014
    */

    public function index()
    {	
		//check user right
		check_rights('mail_blast');
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');

		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('mailout_sortsearchpage_data');
		}
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('mailout_sortsearchpage_data');
		
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
		
		$config['base_url'] = site_url($this->user_type.'/'."mail_out/");
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
		
		$table = "mail_blast_sent as mb";
		$fields = array('mb.*,count(mb.id) as total_contacts,etm.template_name,etm.letter_content,etm.template_subject,etm.size_w as letter_size_w,etm.size_h as letter_size_h,mmlcm.category,
		,em.template_name as en_template_name,em.envelope_content,em.template_type,em.template_size_id 	
		,ltm.template_name as label_template_name,ltm.label_content,ltm.size_type,inp.name as save_type_name
		');
		$join_tables = array(
							'mail_blast_contact_trans as mct'=>'mb.id = mct.mail_blast_id',
							'letter_template_master as etm'=>'etm.id = mb.template_id',
							'envelope_template_master as em'=>'em.id = mb.template_id',
							'label_template_master as ltm'=>'ltm.id = mb.template_id',
							'marketing_master_lib__category_master mmlcm'=>'mmlcm.id = mb.category_id',
							'interaction_plan__plan_type_master inp'=>'inp.id = mb.mail_out_type',
							
						);
		 $group_by='mb.id';
		
		
		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();
		$wherestring = '(mb.created_by IN ('.$this->user_session['agent_id'].'))';
		if(!empty($searchtext))
		{
		   $match=array('mb.mail_out_type'=>$searchtext,'etm.template_name'=>$searchtext,'mb.save_type'=>$searchtext,'inp.name'=>$searchtext);
			$data['datalist'] = $this->mail_blast_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$wherestring);
			//echo $this->db->last_query();exit;
		//pr($data['datalist']);exit;	
		$config['total_rows'] = $this->mail_blast_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,$group_by,$wherestring,'1');
		}
		else
		{
			//$wherestring=array('mb.created_by'=>$this->user_session['id']);
			$data['datalist'] = $this->mail_blast_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$sortfield,$sortby,$group_by,$wherestring);
			
		//pr($data['datalist']);exit;
		/*$config['total_rows'] = count($this->mail_blast_model->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','=','','','','',$group_by,$wherestring));*/
		
		$config['total_rows'] = $this->mail_blast_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','=','','','','',$group_by,$wherestring,'1');
		//echo $this->db->last_query();exit;
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		$mailout_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
			'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
			
		$this->session->set_userdata('mailout_sortsearchpage_data', $mailout_sortsearchpage_data);
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
    @Description: Function Add New Envelope details
    @Author: Mit Makwana
    @Input: - 
    @Output: - Load Form for add Envelope details
    @Date: 12-08-2014
    */
   
    public function add_record()
    {
		//check user right
		if(!in_array('letter_add',$this->modules_unique_name) && !in_array('envelope_add',$this->modules_unique_name) && !in_array('label_add',$this->modules_unique_name)){ 
		show_404();
		}
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		/*$sortfield = $this->input->post('sortfield');
		$sortby = 'desc';*/
		$perpage = $this->input->post('perpage');
		$sortfield = 'id';
		$sortby = 'desc';
		$data['perpage'] = '50';
        $config['per_page'] = '50';
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id',
						);
		$group_by='cm.id';
		
		$wherestring = '(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		
/*		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$wherestring);
		
		$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring));
*/		//echo $this->db->last_query();exit;

		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$wherestring);
		
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','','','','',$group_by,$wherestring,'1');


		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();
		$match = array("parent"=>'0');
		$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		$match = array();
		$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
		//pr($data['contact_type']);
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
		
		$table1='custom_field_master';
		$where1=array('module_id'=>'4');
		$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$data['all_tag_trans_data'] = $this->contacts_model->select_tag_record();
		$data['main_content'] =  $this->user_type.'/'.$this->viewName."/add";
		$this->load->view('user/include/template',$data);
    }

	public function search_contact_ajax()
    {
		//echo 'Meet';exit;
		$config['per_page'] = 50;	
		$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		/*$sortfield = $this->input->post('sortfield');
		$sortby = 'desc';*/
		$perpage = $this->input->post('perpage');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');

		if(!empty($perpage))
        {
            //$perpage = $this->input->post('perpage');
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;	
        }
        else
        {
			$data['perpage'] = '50';
            $config['per_page'] = '50';
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
		
		$searchtext = $this->input->post('searchtext');
		$contact_status = $this->input->post('contact_status');
		$contact_source = $this->input->post('contact_source');
		$contact_type = $this->input->post('contact_type');
		$wherestring = '';
		if(!empty($contact_status))
			$wherestring .= 'cm.contact_status = '.$contact_status.' AND ';
		if(!empty($contact_source))
			$wherestring .= 'cm.contact_source = '.$contact_source.' AND ';
		if(!empty($contact_type))
			$wherestring .= 'cct.contact_type_id = '.$contact_type.' AND ';
		
		$match=array('CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name)'=>$searchtext,'CONCAT_WS(" ",cm.first_name,cm.last_name)'=>$searchtext,'email_address'=>$searchtext,'ctat.tag'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spousemiddle_name,cm.spouselast_name)'=>$searchtext,'CONCAT_WS(" ",cm.spousefirst_name,cm.spouselast_name)'=>$searchtext);
		
		$table = "contact_master as cm";
		$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
								'user_contact_trans as uct'=>'uct.contact_id = cm.id',
								'contact_tag_trans as ctat'=>'ctat.contact_id = cm.id',
								'contact_contacttype_trans as cct'=>'cct.contact_id = cm.id'
							);
		$group_by='cm.id';
		
		$search_tag = $this->input->post('search_tag');
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
			
		$wherestring .= '(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
		
		$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
		
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
		
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
        $this->load->view("user/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	
	public function add_contacts_to_mail_out()
	{
		$contacts=$this->input->post('contacts');
		$data['contacts_data'] = $this->contacts_model->get_record_where_in_contact_master($contacts);		
		$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}
	

	 /*
		@Description: Function for Delete Envelope Profile By user
		@Author: Mit Makwana
		@Input: - Delete all id of Envelope record want to delete
		@Output: - Envelope list Empty after record is deleted.
		@Date: 12-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		if(!empty($id))
		{
			$this->mail_blast_model->delete_record($id);
			$this->mail_blast_model->delete_record1($id);
			
			unset($id);
		}
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->mail_blast_model->delete_record($array_data[$i]);
			$this->mail_blast_model->delete_record1($array_data[$i]);
		}
		$searchsort_session = $this->session->userdata('mailout_sortsearchpage_data');
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


	public function ajax_subcategory()
	{
		$id=$this->input->post('loadId');
		
		if(!empty($id))
		{
			$match = array("parent"=>$id);
        	$cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			echo json_encode($cdata['subcategory']);
		}
	}

	public function mail_out_data()
	{
		$data['template_type'] 		= $this->input->post('template_type');
		$data['template_name'] 		= $this->input->post('template_name');
		$data['template_category'] 	= $this->input->post('template_category');
		$data['template_subcategory'] 	= $this->input->post('template_subcategory');
		//echo pr($data);exit;
		$match = array("parent"=>'0');
		$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		if($data['template_type'] == 'Envelope')
		{
			$data['template_data'] = $this->envelope_library_model->select_records();
			$id = $this->input->post('template_id');		
			$match = array('id'=>$id);
			$result = $this->envelope_library_model->select_records('',$match,'','=');
			$data['editRecord'] = $result;

		}elseif($data['template_type'] == 'Label')
		{
			$data['template_data'] = $this->label_library_model->select_records();
			$id = $this->input->post('template_id');		
			$match = array('id'=>$id);
			$result = $this->label_library_model->select_records('',$match,'','=');
			$data['editRecord'] = $result;

		}else{
			$data['template_data'] = $this->letter_library_model->select_records();
			//pr($data['template_data']);exit;
			$id = $this->input->post('template_id');		
			$match = array('id'=>$id);
			$result = $this->letter_library_model->select_records('',$match,'','=');
			$data['editRecord'] = $result;

		}
	 	
		$match = array("parent"=>'0');
			$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
		$match = array("parent"=>'0');
			$data['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
			
		//pr($data);exit;
		
		///////////////////////////////////////////////////////////////////
		$config['per_page'] = '5';	
		$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		$table = "contact_master as cm";
			$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
			$join_tables = array(
								'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"',
								'user_contact_trans as uct'=>'uct.contact_id = cm.id'
							);
			$group_by='cm.id';
			$wherestring='(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';
			$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,'1');
		
		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();
		
		
		$table = "contact_contacttype_trans as cct";
		$fields = array('DISTINCT(ctm.name),ctm.id,ctm.created_date,ctm.created_by,ctm.created_by,ctm.created_by,ctm.status');
		$join_tables = array(
							'contact__type_master as ctm'=>'ctm.id = cct.contact_type_id',
							'contact_master as cm'=>'cct.contact_id = cm.id',
							'user_contact_trans as uct'=>'uct.contact_id = cm.id'
						);
		$where = '(cm.created_by IN ('.$this->user_session['agent_id'].') OR uct.user_id = '.$this->user_session['agent_user_id'].')';		
		$data['contact_type'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','id','desc','',$where);
		

		$match = array();
		$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
		$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
	
		//pr($data['contact_list']);exit;
		
		///////////////////////////////////////////////////////////////////
			
		$data['main_content'] = "user/mail_out/add";
        $this->load->view('user/include/template', $data);
	}
	
	public function get_envelope()
	{
		$mail_type = $this->uri->segment(4);
		$category = $this->input->post('slt_category');
		if($mail_type == 'Envelope' && !empty($category) && $category > 0)
			$table = 'envelope_template_master as common';
		elseif($mail_type == 'Label' && !empty($category) && $category > 0)
			$table = 'label_template_master as common';
		elseif($mail_type == 'Letter' && !empty($category) && $category > 0)
			$table = 'letter_template_master as common';
		if(!empty($table) && !empty($category))
		{
			//echo "hi";
			$fields = array('common.*');
			$join_tables = array(
								'login_master as lm'=>'lm.id = common.created_by',
							);
			$group_by='common.id';
			$where = 'common.template_category = '.$category.' AND (common.created_by IN ('.$this->user_session['agent_id'].') OR lm.user_type = 2)';
			$data['template_data'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where);
			//echo
		}
		if(!empty($data['template_data'])){
			for($i=0;$i<count($data['template_data']);$i++)
			{
				$data['template_data'][$i]['template_name'] = ucwords($data['template_data'][$i]['template_name']);
			}
			echo json_encode($data['template_data']);
		}
	}	
	
	
	public function mail_out_print()
	{
		
		//pr($_POST);exit;
		$tmp_data = $this->input->post('message_content');
		$tmp_title = '';
		$data['tmp_type'] = $this->input->post('mail_out_type');
		$template_type = $this->input->post('template_type_radio');
		$flag = $this->input->post('flag');
		
		if(!empty($flag))
		{
			
			if($_POST['mail_out_type'] == 'Envelope'){
				$match = array("id"=>$_POST['template']);
				$data['tmp_type'] = $_POST['mail_out_type'];
				$data['template_data'] = $this->envelope_library_model->select_records('',$match,'','=');
				$tmp_title = $data['template_data'][0]['template_name'];
				$tmp_data = $data['template_data'][0]['envelope_content'];
			}
			elseif($_POST['mail_out_type'] == 'Label'){
				$data['tmp_type'] = $_POST['mail_out_type'];
				$match = array("id"=>$_POST['template']);
				$data['template_data'] = $this->label_library_model->select_records('',$match,'','=');
				$tmp_title = $data['template_data'][0]['template_name'];
				$tmp_data = $data['template_data'][0]['label_content'];
			}
			elseif($_POST['mail_out_type'] == 'Letter'){
				$data['tmp_type'] = $_POST['mail_out_type'];
				$match = array("id"=>$_POST['template']);
				$data['template_data'] = $this->letter_library_model->select_records('',$match,'','=');
				$tmp_title = $data['template_data'][0]['template_name'];
				$tmp_data = $data['template_data'][0]['letter_content'];
			}
			$env_width = $data['template_data'][0]['size_w'];
			$env_height = $data['template_data'][0]['size_h'];
		}
		else
		{
			if($_POST['mail_out_type'] == 'Envelope'){
				if($template_type == 1)
				{
					$env_width = 9.5;
					$env_height = 4.125;
				}
				else
				{
					$env_width = $this->input->post('txt_size_w');
					$env_height = $this->input->post('txt_size_h');
				}
			}
			elseif($_POST['mail_out_type'] == 'Label'){
				if($template_type == 1)
				{
					$size_type = $this->input->post('size_type');
					if($size_type == 1)
					{
						$env_width = 4;
						$env_height = 1.5;
					}
					elseif($size_type == 2)
					{
						$env_width = 2.62;
						$env_height = 1;
					}
					elseif($size_type == 3)
					{
						$env_width = 4;
						$env_height = 1;
					}
					elseif($size_type == 4)
					{
						$env_width = 4;
						$env_height = 1.33;
					}
					elseif($size_type == 5)
					{
						$env_width = 4;
						$env_height = 2;
					}
					elseif($size_type == 6)
					{
						$env_width = 4;
						$env_height = 3.33;
					}
				}
				else
				{
					$env_width = $this->input->post('txt_size_w');
					$env_height = $this->input->post('txt_size_h');
				}
			}
			elseif($_POST['mail_out_type'] == 'Letter'){
				$env_width = $this->input->post('txt_size_w');
				$env_height = $this->input->post('txt_size_h');
			}
		}
		
		$interaction_contacts = $this->input->post('finalcontactlist');
		$data['finalcontactlist'] = $interaction_contacts;
		//$interaction_contacts = explode(",",$interaction_contacts);
		$sort_by = $_POST['sort_by'];
		$data['sort_by'] = $sort_by;

		$data['tmp_size_w'] = $env_width;  
		$data['tmp_size_h'] = $env_height; 
		
		
		$match = array("parent"=>'0');
		$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		//pr($data['tmp_size_h']);exit;
		//if(!empty($interaction_contacts) && !empty($sort_by))
		if(!empty($sort_by))
		{
			$cdata['sort_by'] = $sort_by;
                        $cdata['interaction_contacts'] = $interaction_contacts;
			$interaction_contacts = explode(",",$interaction_contacts);
			/*
			$cdata['interaction_contacts'] = $interaction_contacts;
			$data['interaction_contacts_data'] = $this->contacts_model->contact_select_records($cdata);*/
			
			$table = "contact_master as cm";
			$fields = array('cm.id','cm.first_name,cm.middle_name,cm.last_name,cm.spousefirst_name,cm.spouselast_name','cm.company_name,cm.first_name,cm.last_name,cm.created_by','cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code');
			$where_in = array('cm.id'=>$interaction_contacts);
			$join_tables = array(
								'contact_address_trans as cat'=>'cat.contact_id = cm.id',
							);
			$group_by='cm.id';
			
			$data['interaction_contacts_data'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$cdata['sort_by'],'asc',$group_by,'',$where_in);
			
			
			
			//pr($data['interaction_contacts_data']);exit;
			
			if(!empty($data['interaction_contacts_data']))
			{
				$finlaOutput = '';
				$finlaOutputPrint= '';
				for($i=0;$i<count($data['interaction_contacts_data']);$i++)
				{
					$agent_name = '';
					if(!empty($data['interaction_contacts_data'][$i]['created_by']))
					{
						
						$table ="login_master as lm";   
						$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
						$join_tables = array('user_master as um'=>'lm.user_id = um.id');
						$wherestring = 'lm.id = '.$data['interaction_contacts_data'][$i]['created_by'];
						$agent_datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
						if(!empty($agent_datalist))
						{
							if(!empty($agent_datalist[0]['user_type']) && $agent_datalist[0]['user_type'] == 2)
								$agent_name = $agent_datalist[0]['admin_name'];
							else
								$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
						}
					}
					 $emaildata = array(
					 					'Contact First Name'=>$data['interaction_contacts_data'][$i]['first_name'],
										'Contact Last Name'=>$data['interaction_contacts_data'][$i]['last_name'],
										'Contact Company Name'=>$data['interaction_contacts_data'][$i]['company_name'],
										'Contact Spouse/Partner First Name'=>$data['interaction_contacts_data'][$i]['spousefirst_name'],
										'Contact Spouse/Partner Last Name'=>$data['interaction_contacts_data'][$i]['spouselast_name'],
										'Agent First Name'=> !empty($agent_name)?$agent_name:'',
										'Agent Last Name'=> !empty($agent_datalist[0]['last_name'])?$agent_datalist[0]['last_name']:'',
										'Agent Company'=> !empty($agent_datalist[0]['company_name'])?$agent_datalist[0]['company_name']:'',
										'Agent Title'=>'',
										'Agent Address'=>'',
										'Agent City'=>'',
										'Agent State'=>'',
										'Contact Address'=> trim($data['interaction_contacts_data'][$i]['address_line1']).' '.trim($data['interaction_contacts_data'][$i]['address_line2']),
										'Agent Zip'=>'',
										'Contact State'=> $data['interaction_contacts_data'][$i]['state'],
										'Contact City'=> $data['interaction_contacts_data'][$i]['city'],
										'Contact Zip'=> $data['interaction_contacts_data'][$i]['zip_code']);
				
					$content 	= $tmp_data;
					$title		= $tmp_title;
					
					$pattern = "{(%s)}";
					$map = array();
					
					if($emaildata != '' && count($emaildata) > 0)
					{	foreach($emaildata as $var => $value)
						{
							$map[sprintf($pattern, $var)] = $value;
						}
					}
					
					$finaltitle = strtr($title, $map);				
					$output = strtr($content, $map);
					
					//$finlaOutput .= $finaltitle;
					
					if($i==0){
						$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;text-align:justify;'>".$output."</div></div>";
						$finlaOutputPrint .= "<div style='width:".$env_width."in;text-align:justify;'>".$output."</div>";
					}
					else{	
						$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='page-break-before:always;width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;text-align:justify;'>".$output."</div></div>";
						$finlaOutputPrint .= "<div style='page-break-before:always;width:".$env_width."in;text-align:justify;'>".$output."</div>";
					}			
				}
			}
			else
			{
				$finlaOutput = '';
				$finlaOutputPrint = '';
				//$emaildata = array('first name'=>'First Name','last name'=>'Last Name','company name'=>'Company Name');
				$emaildata = array();

				$content 	= $tmp_data;
				$title		= $tmp_title;

				$pattern = "{(%s)}";
				$map = array();

				if($emaildata != '' && count($emaildata) > 0)
				{	foreach($emaildata as $var => $value)
					{
						$map[sprintf($pattern, $var)] = $value;
					}
				}
				
				$finaltitle = strtr($title, $map);				
				$output = strtr($content, $map);
				
				//$finlaOutput .= $finaltitle;
				//$finlaOutput .= "<div style=width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;overflow:hidden; id='finalOutput'>".$finaltitle.$output."</div>";
				$finlaOutput .= "<div style='width:100%;height:".$env_height."in;background-color:#FFFFFF;overflow:auto; text-align:justify;'><div style='width:".$env_width."in;height:".$env_height."in;background-color:#FFFFFF;text-align:justify;'>".$output."</div></div>";
				$finlaOutputPrint .= "<div style='width:".$env_width."in;text-align:justify;'>".$output."</div>";
			}
		}

		$data['finlaOutput'] = $finlaOutput;
		$data['finlaOutputPrint'] = $finlaOutputPrint;
		
		$data['post_data']=$_POST;
		$data['post_data']['message_content'] = $tmp_data;
		$data['main_content'] = "user/".$this->viewName."/print";
        $this->load->view('user/include/template', $data);
	}
	
	public function generate_pdf()
	{
		$data['templatedata']  = $this->input->post('tmp_data');
		$data['template_type'] = $this->input->post('template_type');
		// Conver INCh to MM
		//pr($data['templatedata']);exit;
		
		$mm = '25.4';
		
		$data['size_w']  = $mm * $this->input->post('size_w');
		$data['size_h']  = $mm * $this->input->post('size_h');
		
		//pr($data);exit;
		
		$pdata['mail_out_type'] 	= $this->input->post('post_mail_out_type');
		$pdata['category_id'] 		= $this->input->post('post_slt_category');
		$pdata['template_id'] 		= $this->input->post('post_template');
		$pdata['sort_by'] 			= $this->input->post('post_sort_by');
		$pdata['message'] 		= $this->input->post('message_content');
		$pdata['size_w'] 		= $this->input->post('size_w');
		$pdata['size_h'] 		= $this->input->post('size_h');
		$pdata['save_type'] 		= 'download';
		$pdata['created_by'] 		= $this->user_session['id'];
		$pdata['created_date'] 		= date('Y-m-d h:i:s');
		$mail_blast_id = $this->mail_blast_model->insert_record($pdata);
		
		$finalcontactlist=$this->input->post('post_finalcontactlist');
		
		if(!empty($finalcontactlist))
		{
			$contacts=explode(',',$finalcontactlist);
			foreach($contacts as $row)
			{
				$cdata['mail_blast_id'] 	= $mail_blast_id;
				$cdata['contact_id'] 		= $row;
				$cdata['created_date'] 		= date('Y-m-d h:i:s');
				$this->mail_blast_model->insert_record1($cdata);
			}
		}
		if(!empty($data))
		{
			$this->load->view("user/mail_out/compare_pdf", $data);
		}
	}
	function insert_data()
	{
		$pdata['mail_out_type'] 	= $this->input->post('post_mail_out_type');
		$pdata['category_id'] 		= $this->input->post('post_slt_category');
		$pdata['template_id'] 		= $this->input->post('post_template');
		$pdata['message'] 		= $this->input->post('message_content');
		$pdata['size_w'] 		= $this->input->post('size_w');
		$pdata['size_h'] 		= $this->input->post('size_h');
		$pdata['sort_by'] 			= $this->input->post('post_sort_by');
		$pdata['save_type'] 		= 'print';
		$pdata['created_by'] 		= $this->user_session['id'];
		$pdata['created_date'] 		= date('Y-m-d h:i:s');
		$mail_blast_id = $this->mail_blast_model->insert_record($pdata);
		
		$finalcontactlist=$this->input->post('post_finalcontactlist');
		
		if(!empty($finalcontactlist))
		{
			$contacts=explode(',',$finalcontactlist);
			foreach($contacts as $row)
			{
				$cdata['mail_blast_id'] 	= $mail_blast_id;
				$cdata['contact_id'] 		= $row;
				$cdata['created_date'] 		= date('Y-m-d h:i:s');
				$this->mail_blast_model->insert_record1($cdata);
			}
		}
		
		
		
	}
	public function view_contacts_of_interaction_plan()
	{
		$id=$this->input->post('interaction_plan');
		
		$table = "mail_blast_contact_trans as ct";
		$fields = array('ct.mail_blast_id','cm.id as cid','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
		$where = array('ct.mail_blast_id'=>$id);
		$join_tables = array(
							'contact_master as cm'=>'cm.id = ct.contact_id',
							'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
						);
		$group_by='cm.id';
		
		$data['contact_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$where,'=','','','cm.first_name','asc',$group_by);
		
		$this->load->view($this->user_type.'/'.$this->viewName."/view_contact_popup",$data);
	}
	public function ajax_templatename()
	{
		$template_type = $this->input->post('template_type');
		$template_id = $this->input->post('template_id');
		if(!empty($template_id) && $template_id != '-1')
		{
			$match = array("id"=>$template_id);
			if($template_type == 'Letter')
				$cdata['templatedata'] = $this->letter_library_model->select_records('',$match,'','=','','','','id','desc');
			elseif($template_type == 'Envelope')
				$cdata['templatedata'] = $this->envelope_library_model->select_records('',$match,'','=','','','','id','desc');
			elseif($template_type == 'Label')
				$cdata['templatedata'] = $this->label_library_model->select_records('',$match,'','=','','','','id','desc');
			if(count($cdata['templatedata']) > 0)
			{
				echo json_encode($cdata['templatedata']);
			}
		}
		else
		{
			echo '-1';
		}	
	}
}