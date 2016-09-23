<?php 
/*
    @Description: SMS Text Auto Responder Controller
    @Author: Nishant Rathod
    @Input: 
    @Output: 
    @Date: 04-05-2015
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sms_texts_response_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('sms_texts_response_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('email_library_model');
		
		$this->obj = $this->sms_texts_response_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';
    }
	

    /*
    @Description: Function for Get All SMS Auto Response List
    @Author: Nishant Rathod
    @Input: - Search value or null
    @Output: - All SMS Auto Response List
    @Date: 04-05-2015
    */

    public function index()
    {	
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
		
		//////////////////////////////////////////////////////
		
		$selected_cat = $this->input->post('selected_cat');
		
		//////////////////////////////////////////////////////
		
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('sms_texts_response_sortsearchpage_data');
                }
                $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('sms_texts_response_sortsearchpage_data');
		
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
/*                            $data['searchtext'] = $searchsort_session['searchtext'];
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

		$config['base_url'] = site_url($this->user_type.'/'."sms_texts_response/");
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

		$table = "sms_text_template_master";
		$fields = array('sms_text_template_master.*,mmlcm.category,sem.name sms_event_name');
		$join_tables = array(
						'marketing_master_lib__category_master mmlcm'=>'mmlcm.id = sms_text_template_master.template_category',
						'sms_event_master sem'=>'sem.id = sms_text_template_master.sms_event'
						);
		$group_by = 'sms_text_template_master.id';

		if(!empty($searchtext) || !empty($selected_cat))
		{
			
			if(!empty($searchtext))
				$match=array('template_name'=>$searchtext);
			else
				$match=array();
				
			//$match=array('template_name'=>$searchtext,'template_subject'=>$searchtext);
			if(!empty($selected_cat))
				$wherestring='sms_text_template_master.sms_send_type = 1 AND sms_text_template_master.template_category = '.$selected_cat.' AND sms_text_template_master.is_default = 1';
			else
				$wherestring='sms_text_template_master.sms_send_type = 1 AND sms_text_template_master.is_default = 1';
			
			//$wherestring='sms_send_type = 1' ;
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,$wherestring,'1');
			/*$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,'','',$match,'','like',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$wherestring);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,'','',$match,'','','','','','',$group_by,$wherestring));*/
						
		}
		else
		{
			$match = array('sms_text_template_master.sms_send_type' => 1,'sms_text_template_master.is_default'=> 1);
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,'');
			//$data['datalist'] = $this->obj->select_records('',$match,'','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by);	
			$config['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','',$group_by,'','1');
			
		}
		
		//pr($data['datalist']);
		
		$match = array("parent"=>'0');
        $data['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
                $sms_texts_response_sortsearchpage_data = array(
                    'selected_cat' =>!empty($data['selected_cat'])?$data['selected_cat']:'',
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
                $this->session->set_userdata('sms_texts_response_sortsearchpage_data', $sms_texts_response_sortsearchpage_data);
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
    @Description: Function Add New SMS Auto Response details
    @Author: Nishant Rathod
    @Input: - 
    @Output: - Load Form for add SMS Auto Response details
    @Date: 04-05-2015
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
		$data['event'] = $this->obj->select_records_from_table('sms_event_master');
		$data['communication_plans'] = '';
		$table1='custom_field_master';
		$where1=array('module_id'=>'5');
		$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }
	
	/*
        @Description: Function for set selected view session for interaction plan listing
        @Author     : Sanjay Moghariya
        @Input      : Selected View
        @Output     : Set session
        @Date       : 13-10-2014
    */
    public function selectedview_session()
    {
        $selected_view = $this->input->post('selected_view');
        
        $sortsearchpage_data = array(
            'sortfield'  => 'sms_text_template_master.id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        if($selected_view == '1')
            $this->session->set_userdata('def_sms_texts_response_sortsearchpage_data', $sortsearchpage_data);
        else
            $this->session->set_userdata('sms_texts_response_sortsearchpage_data', $sortsearchpage_data);
        $data = array('selected_view' => $selected_view);
        $this->session->set_userdata('smstext_response_selected_view_session',$data);
    }
   
    /*
    @Description: Function to copy sms auto response details
    @Author: Nishant Rathod
    @Input: - 
    @Output: - New list of sms auto response with copied record
    @Date: 04-05-2015
    */
   
    public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['template_name'] = $result[0]['template_name'].'-copy';
		$cdata['template_category'] = $result[0]['template_category'];
		$cdata['template_subcategory'] = $result[0]['template_subcategory'];
		$cdata['sms_message'] = $result[0]['sms_message'];   
		$cdata['sms_send_type'] = 1;
		$cdata['sms_event'] = $result[0]['sms_event'];
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$cdata['is_default'] = 1;
		$cdata['publish_flag'] = $result[0]['publish_flag'];
		$cdata['superadmin_publish_date'] = date('Y-m-d H:i:s');
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_copy_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
	}

    /*
    @Description: Function for Insert New SMS Auto Repsonse data
    @Author: Nishant Rathod
    @Input: - Details of new SMS Auto Response which is inserted into DB
    @Output: - List of SMS Auto Response with new inserted records
    @Date: 04-05-2015
    */
   
    public function insert_data()
    {
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['sms_message'] = $this->input->post('sms_message');   
		$cdata['sms_send_type'] = 1;
		$cdata['sms_event'] = $this->input->post('sms_event');
		$cdata['is_default'] = 1;
		$cdata['publish_flag'] = $this->input->post('publish_flag');
		$cdata['superadmin_publish_date'] = date('Y-m-d H:i:s');
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->insert_record($cdata);	
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $sms_texts_response_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('sms_texts_response_sortsearchpage_data', $sms_texts_response_sortsearchpage_data);
		redirect('superadmin/'.$this->viewName);
    }

    /*
    @Description: Get Details of Edit SMS Auto Response
    @Author: Nishant Rathod
    @Input: - ID of SMS Auto Response whose details is to change
    @Output: - Details of SMS Auto Response which id is selected for update
    @Date: 04-05-2015
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
		$cdata['event']=$this->obj->select_records_from_table('sms_event_master');
		$table1='custom_field_master';
		$where1=array('module_id'=>'5');
		$cdata['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
    }

    /*
    @Description: Function for Update SMS Auto Response
    @Author: Nishant Rathod
    @Input: - Update details of SMS Auto Response
    @Output: - List with updated SMS Auto Response details
    @Date: 04-05-2015
    */
   
    public function update_data()
    {
        $cdata['id'] = $this->input->post('id');
        $cdata['template_name'] = $this->input->post('txt_template_name');
        $cdata['template_category'] = $this->input->post('slt_category');
        $cdata['template_subcategory'] = $this->input->post('slt_subcategory');
        $cdata['sms_message'] = $this->input->post('sms_message');   
        $cdata['sms_send_type'] = 1;
		$cdata['sms_event'] = $this->input->post('sms_event');
		$cdata['publish_flag'] = $this->input->post('publish_flag');
        $cdata['modified_by'] = $this->superadmin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');		
        $cdata['status'] = '1';
        $this->obj->update_record($cdata);
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        //$sms_id = $this->input->post('id');
        //$pagingid = $this->obj->getsmspagingid($sms_id);
        $searchsort_session = $this->session->userdata('sms_texts_response_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
    }
	
    /*
    @Description: Function for Delete SMS Auto Response By superadmin
    @Author: Nishant Rathod
    @Input: - ID of SMS Auto Response record want to delete
    @Output: - New SMS Auto Response list after record is deleted.
    @Date: 04-05-2015
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
    @Description: Function for Delete SMS Auto Response By superadmin
    @Author: Nishant Rathod
    @Input: - All id of SMS Auto Response record want to delete
    @Output: - SMS Auto Response list after records are deleted.
    @Date: 04-05-2015
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
				$delete_all_flag = 1;
				$cnt++;
				$this->obj->delete_record($array_data[$i]);
			}
		}
		$searchsort_session = $this->session->userdata('sms_texts_response_sortsearchpage_data');
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
    @Description: Function for Unpublish SMS Auto Response By superadmin
    @Author: Nishant Rathod
    @Input: - Delete id which SMS Auto Response record want to Unpublish
    @Output: - New SMS Auto Response list after record is Unpublish.
    @Date: 04-05-2015
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
		$sms_id = $id;
		$pagingid = $this->obj->getsmspagingid($sms_id);
		echo $pagingid;
    }
	
	/*
    @Description: Function for publish SMS Auto Response By superadmin
    @Author: Nishant Rathod
    @Input: - Delete id which SMS Auto Response record want to publish
    @Output: - New SMS Auto Response list after record is publish.
    @Date: 04-05-2015
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
		$sms_id = $id;
		$pagingid = $this->obj->getsmspagingid($sms_id);
		echo $pagingid;
    }
    /*
    @Description: Function for publish date of edit record
    @Author: Nishant Rathod
    @Input: - id which SMS Auto Response record want to publish
    @Output: - New SMS Auto Response list after record is publish.
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

	/*
    @Description: Function for SMS Auto Response subcategory By superadmin
    @Author: Nishant Rathod
    @Input: - Delete id which SMS Auto Response record want subcategory.
    @Output: - New SMS Auto Response.
    @Date: 04-05-2015
    */
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
	
}