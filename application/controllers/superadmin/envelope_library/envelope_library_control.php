<?php 
/*
    @Description: Envelope Library controller
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 12-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class envelope_library_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('envelope_library_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('label_library_model');
		$this->load->model('email_library_model');
		$this->load->model('letter_library_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_masters_model');
		
		$this->obj = $this->envelope_library_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';
    }
	

    /*
		@Description: Function for Get All Envelope List
		@Author: Mit Makwana
		@Input: - Search value or null
		@Output: - all Envelope list
		@Date: 12-08-2014
    */

    public function index()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		
		//////////////////////////////////////////////////////
		
		$selected_cat = $this->input->post('selected_cat');
		
		//////////////////////////////////////////////////////
		
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('envelope_library_sortsearchpage_data');
                }
                $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('envelope_library_sortsearchpage_data');
		
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
		$config['base_url'] = site_url($this->user_type.'/'."envelope_library/");
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
		
		if(!empty($selected_cat))
		{
                    //$searchtext = $this->input->post('searchtext');
                    $data['selected_cat'] = $selected_cat;
		} else {
                    if(empty($allflag))
                    {
                        if(!empty($searchsort_session['selected_cat'])) {
                            $data['selected_cat'] = $searchsort_session['selected_cat'];
                            $selected_cat =  $data['selected_cat'];
                        }
                    }
                }
		
		$table = "envelope_template_master";
		$fields = array('envelope_template_master.*,mmlcm.category');
		$join_tables = array('marketing_master_lib__category_master mmlcm'=>'mmlcm.id = envelope_template_master.template_category');
		$group_by = 'envelope_template_master.id';
				
		if(!empty($searchtext)  || !empty($selected_cat))
		{
			if(!empty($searchtext))
				$match=array('template_name'=>$searchtext);
			else
				$match=array();
				
			if(!empty($selected_cat))
				$wherestring = 'template_category = '.$selected_cat.' AND envelope_template_master.is_default = 1';
			
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
			
		/*	$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			$config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));*/
		}
		else
		{
			$match = array('envelope_template_master.is_default'=>1);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,'');
			$config['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','1');
			/*$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
			$config['total_rows']= count($this->obj->select_records());*/
		}
		
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

                $envelope_library_sortsearchpage_data = array(
                     'selected_cat' =>!empty($data['selected_cat'])?$data['selected_cat']:'',
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('envelope_library_sortsearchpage_data', $envelope_library_sortsearchpage_data);
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
    @Description: Function Add New Envelope details
    @Author: Mit Makwana
    @Input: - 
    @Output: - Load Form for add Envelope details
    @Date: 12-08-2014
    */
   
    public function add_record()
    {
		
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if($id!='')
		{
			$match = array('id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			$data['editRecord'] = $result;
			$data['insert_data']=1;
		}
		$table1='custom_field_master';
		$where1=array('module_id'=>'4');
		$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$data['communication_plans'] = '';
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
    @Description: Function for Insert New Envelope data
    @Author: Mit Makwana
    @Input: - Details of new Envelope which is inserted into DB
    @Output: - List of Envelope with new inserted records
    @Date: 12-08-2014
    */
   
    public function insert_data()
    {
		//pr($_POST);exit;
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['envelope_content'] = $this->input->post('envelope_content');
		$cdata['is_default'] = 1;
		$cdata['publish_flag'] = $this->input->post('publish_flag');
		$cdata['superadmin_publish_date'] = date('Y-m-d H:i:s');
		//$tmp_size = explode(',',$this->input->post('template_size'));
		//$cdata['template_size'] = $tmp_size[0];
		//$cdata['size_w'] = $tmp_size[1];
		//$cdata['size_h'] = $tmp_size[2];
		$cdata['template_type'] = $this->input->post('template_type_radio');
		if($cdata['template_type']==1)
		{
			$cdata['template_size_id'] = $this->input->post('template_size_id');
			if($cdata['template_size_id'] == 1)
			{
				$cdata['size_w'] = 9.5;
				$cdata['size_h'] = 4.125;
			}
		}
		else
		{
			$cdata['size_w'] = $this->input->post('txt_size_w');
			$cdata['size_h'] = $this->input->post('txt_size_h');	
		}
		
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		//pr($tmp_size);exit;
		//pr($cdata);exit;
		$insert_id = $this->obj->insert_record($cdata);	
		
		if(!empty($_POST['submitbtn']))
		{
			//$this->obj->insert_record($cdata);	
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
                        $envelope_library_sortsearchpage_data = array(
                            'sortfield'  => 'id',
                            'sortby' => 'desc',
                            'searchtext' =>'',
                            'perpage' => '',
                            'uri_segment' => 0);
                        $this->session->set_userdata('envelope_library_sortsearchpage_data', $envelope_library_sortsearchpage_data);
			redirect('superadmin/'.$this->viewName);
		}
		elseif(!empty($_POST['mailout']))
		{
			$data['template_type'] = $this->input->post('template_type');
			if($data['template_type'] == 'Envelope')
			{
				$data['template_data'] = $this->envelope_library_model->select_records();

				$match = array('id'=>$insert_id);
				$result = $this->envelope_library_model->select_records('',$match,'','=');
				$data['editRecord'] = $result;
				$data['template_name'] =  $data['editRecord'][0]['template_name'];
				//pr($data['template_name']);exit;
				$match = array("parent"=>'0');
					$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
				$match = array("parent"=>'0');
					$data['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
			
				
				///////////////////////////////////////////////////////////////////
				// Assign Contact List
				
					$config['per_page'] = '5';	
					$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
					$config['is_ajax_paging'] = TRUE; // default FALSE
					$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
					$config['uri_segment'] = 3;
					$uri_segment = $this->uri->segment(3);
					
					$table = "contact_master as cm";
					$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
					$join_tables = array(
										'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
									);
					$group_by='cm.id';
					
					$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
					
					$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','1');
					
					$this->pagination->initialize($config);
				
					$data['pagination'] = $this->pagination->create_links();
				
					//pr($data['contact_list']);exit;
					
				///////////////////////////////////////////////////////////////////
			
				$match = array();
				$data['contact_type'] = $this->contact_type_master_model->select_records('','','','','','','','id','desc');
				//pr($data['contact_type']);
				$data['status_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__status_master');
				$data['source_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc', 'contact__source_master');
				
				$data['main_content'] = "superadmin/mail_out/add";
        		$this->load->view('superadmin/include/template', $data);
				
				/*$mdata = array('mail_data'=>$data);
				//pr($mdata);
				$this->session->set_userdata('data_session', $mdata);
				pr($this->session->userdata('data_session'));exit;
				redirect('superadmin/mail_out/add_record');*/
			}
		 }
	 }
	 
	/*
		@Description: Function for copy Record
		@Author: Mit Makwana
		@Input: - Details of new Envelope which is inserted into DB
		@Output: - List of Envelope with new inserted records
		@Date: 12-08-2014
    */ 
	 public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		if($id!='')
		{
			$match = array('id'=>$id);
			$result = $this->obj->select_records('',$match,'','=');
			//pr($result);exit;
			$cdata['template_name'] 		= $result[0]['template_name']."-copy";
			$cdata['template_category'] 	= $result[0]['template_category'];
			$cdata['template_subcategory'] 	= $result[0]['template_subcategory'];
			$cdata['envelope_content'] 		= $result[0]['envelope_content'];
			$cdata['template_type']			= $result[0]['template_type'];
			$cdata['template_size_id']		= $result[0]['template_size_id'];
			$cdata['size_w'] 				= $result[0]['size_w'];
			$cdata['size_h'] 				= $result[0]['size_h'];
			$cdata['created_by'] 			= $this->superadmin_session['id'];
			$cdata['created_date'] 			= date('Y-m-d H:i:s');		
			$cdata['status'] 				= '1';
			$cdata['is_default'] = 1;
			$cdata['publish_flag'] = $result[0]['publish_flag'];
			$cdata['superadmin_publish_date'] = date('Y-m-d H:i:s');
			//pr($cdata);exit;
			$this->obj->insert_record($cdata);	
			$msg = $this->lang->line('common_copy_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			redirect('superadmin/'.$this->viewName);
		}
     }
 
    /*
    @Description: Get Details of Edit Envelope Profile
    @Author: Mit Makwana
    @Input: - Id of Envelope member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 12-08-2014
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		$match = array("parent"=>'0');
        $cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
		$table1='custom_field_master';
		$where1=array('module_id'=>'4');
		$cdata['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Envelope Profile
    @Author: Mit Makwana
    @Input: - Update details of Envelope
    @Output: - List with updated Envelope details
    @Date: 12-08-2014
    */
   
    public function update_data()
    {
	    $cdata['id'] = $this->input->post('id');
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['envelope_content']=$this->input->post('envelope_content');
		$cdata['publish_flag'] = $this->input->post('publish_flag');
		//$tmp_size = explode(',',$this->input->post('template_size'));
		//$cdata['template_size'] = $tmp_size[0];
		//$cdata['size_w'] = $tmp_size[1];
		//$cdata['size_h'] = $tmp_size[2];
		$cdata['template_type'] = $this->input->post('template_type_radio');
		if($cdata['template_type']==1)
		{
			$cdata['template_size_id'] = $this->input->post('template_size_id');
			if($cdata['template_size_id'] == 1)
			{
				$cdata['size_w'] = 9.5;
				$cdata['size_h'] = 4.125;
			}
		}
		else
		{
			$cdata['template_size_id'] = '';
			$cdata['size_w'] = $this->input->post('txt_size_w');
			$cdata['size_h'] = $this->input->post('txt_size_h');	
		}
		
		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		
		$this->obj->update_record($cdata);
		$update_id = $cdata['id'];

		if(!empty($_POST['submitbtn']))
		{
			$msg = $this->lang->line('common_edit_success_msg');
			$newdata = array('msg'  => $msg);
			//$envelope_id = $this->input->post('id');
			//$pagingid = $this->obj->getemailpagingid($envelope_id);
			$this->session->set_userdata('message_session', $newdata);
                        $searchsort_session = $this->session->userdata('envelope_library_sortsearchpage_data');
                        $pagingid = $searchsort_session['uri_segment'];
			redirect('superadmin/'.$this->viewName.'/'.$pagingid);
		}
		elseif(!empty($_POST['mailout']))
		{
			$data['template_type'] = $this->input->post('template_type');
			if($data['template_type'] == 'Envelope')
			{
				$data['template_data'] = $this->envelope_library_model->select_records();
				
				$match = array('id'=>$update_id);
				$result = $this->envelope_library_model->select_records('',$match,'','=');
				$data['editRecord'] = $result;
				$data['template_name'] =  $data['editRecord'][0]['template_name'];
				//pr($data['template_name']);exit;

				$match = array("parent"=>'0');
					$data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			
				$match = array("parent"=>'0');
					$data['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','!=','','','','id','desc','marketing_master_lib__category_master');
			
				
				///////////////////////////////////////////////////////////////////
				// Assign Contact List
				
					$config['per_page'] = '5';	
					$config['base_url'] = site_url($this->user_type.'/'."mail_out/search_contact_ajax");
					$config['is_ajax_paging'] = TRUE; // default FALSE
					$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
					$config['uri_segment'] = 3;
					$uri_segment = $this->uri->segment(3);
					
					$table = "contact_master as cm";
					$fields = array('cm.id','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','cm.company_name','cet.email_address');
					$join_tables = array(
										'contact_emails_trans as cet'=>'cet.contact_id = cm.id and cet.is_default = "1"'
									);
					$group_by='cm.id';
					
					$data['contact_list'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'cm.first_name','asc',$group_by);
					
					$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','1');
					
					$this->pagination->initialize($config);
				
					$data['pagination'] = $this->pagination->create_links();
				
					//pr($data['contact_list']);exit;
					
				///////////////////////////////////////////////////////////////////
			
				$data['main_content'] = "superadmin/mail_out/add";
        		$this->load->view('superadmin/include/template', $data);

			}
		 }
    }
	
   /*
    @Description: Function for Delete Envelope Profile By superadmin
    @Author: Mit Makwana
    @Input: - Delete id which Envelope record want to delete
    @Output: - New Envelope list after record is deleted.
    @Date: 12-08-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('superadmin/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete Envelope Profile By superadmin
    @Author: Mit Makwana
    @Input: - Delete all id of Envelope record want to delete
    @Output: - Envelope list Empty after record is deleted.
    @Date: 12-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
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
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('envelope_library_sortsearchpage_data');
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
		@Description: Function for Unpublish Envelope Profile By superadmin
		@Author: Mit Makwana
		@Input: - Delete id which Envelope record want to Unpublish
		@Output: - New Envelope list after record is Unpublish.
		@Date: 12-08-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['publish_flag'] = '0';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		//redirect('superadmin/'.$this->viewName);
		$envelope_id = $id;
		$pagingid = $this->obj->getemailpagingid($envelope_id);
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Envelope Profile By superadmin
    @Author: Mit Makwana
    @Input: - Delete id which Envelope record want to publish
    @Output: - New Envelope list after record is publish.
    @Date: 12-08-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['publish_flag'] = '1';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName);
    }
	/*
    @Description: Function for publish date of edit record
    @Author: NIral patel
    @Input: - id which Email Library record want to publish
    @Output: - New Email Library list after record is publish.
    @Date: 1-09-2014
    */

	function update_publish()
    {
        $id = $this->input->post('id');
		$cdata['id'] = $id;
		$cdata['superadmin_publish_date'] = date('Y-m-d H:i:s');
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
    }
	public function ajax_subcategory()
	{
		$id=$this->input->post('loadId');
		
		if(!empty($id))
		{
			$match = array("parent"=>$id);
        	$cdata['subcategory'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
			if(!empty($cdata['subcategory'])){
				for($i=0;$i<count($cdata['subcategory']);$i++)
				{
					$cdata['subcategory'][$i]['category'] = ucwords($cdata['subcategory'][$i]['category']);
				}
			}
			echo json_encode($cdata['subcategory']);
		}
	}

	/*
		@Description: Function for Get Data of Template
		@Author: Mit Makwana
		@Input: - 
		@Output: - 
		@Date: 12-08-2014
    */
	/*public function mail_out()
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
		$data['main_content'] = "superadmin/mail_out/add";
        $this->load->view('superadmin/include/template', $data);
	}*/
	
	/*public function get_envelope()
	{
		$mail_type = $this->uri->segment(4);
		if($mail_type == 'Envelope')
		{
			$data['template_data'] = $this->envelope_library_model->select_records();
			echo json_encode($data['template_data']);
			
		}elseif($mail_type == 'Label')
		{
			$data['template_data'] = $this->label_library_model->select_records();
			echo json_encode($data['template_data']);
			
		}elseif($mail_type == 'Letter'){
			$data['template_data'] = $this->letter_library_model->select_records();
			echo json_encode($data['template_data']);
		}
	}	*/

}