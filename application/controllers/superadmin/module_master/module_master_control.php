<?php 
/*
    @Description: Module controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 27-01-2015
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class module_master_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
		$this->load->model('module_master_model');
			
		$this->obj = $this->module_master_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';
		$this->parent_db_name = $this->config->item('parent_db_name');
    }
	

    /*
		@Description: Function for Get All Module List
		@Author: Niral Patel
		@Input: - Search value or null
		@Output: - all Module list
		@Date: 27-01-2015
    */

    public function index()
    {	
		/*$envelop_selected_view_session = $this->session->userdata('envelop_selected_view_session');
        if(!empty($envelop_selected_view_session['selected_view']))
            $selected_view = $envelop_selected_view_session['selected_view'];
        else
            $selected_view = '1';
		*/
		$main=1;
		if($main == 1)	
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
                    $this->session->unset_userdata('module_master_sortsearchpage_data');
                }
                $data['sortfield']		= 'm1.id';
				$data['sortby']			= 'desc';
                $searchsort_session = $this->session->userdata('module_master_sortsearchpage_data');
		
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
		$config['base_url'] = site_url($this->user_type.'/'."module_master/");
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
		
		$table='module_master as m1';
		$join_tables = array(
						'module_master as m2' 	=> 'm1.id= m2.module_id AND m2.module_parent = -1',
					);
		$fields = array('m1.*,GROUP_CONCAT(case when m2.module_right="" then null else m2.module_right end) module_right');
		
		$group_by='m2.module_id';
		$where = "m1.module_parent = 0";
		if(!empty($searchtext)  || !empty($selected_cat))
		{
			$match=array('m1.module_name'=>$searchtext);
			
			$data['datalist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,'m1.id','asc',$group_by,$where);
			
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,'',$join_tables,'left',$match,'','like','', '','m1.id','asc',$group_by,$where,'1');
			
		/*	$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			$config['total_rows'] = count($this->obj->select_records('',$match,'','like',''));*/
		}
		else
		{
			$data['datalist']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,'m1.id','asc',$group_by,$where);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','', '','m1.id','asc',$group_by,$where,'1');
		}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

                $module_master_sortsearchpage_data = array(
                    'sortfield'  => $data['sortfield'],
					'selected_cat' => !empty($data['selected_cat'])?$data['selected_cat']:'',
                    'sortby' =>$data['sortby'],
                    'searchtext' => !empty($data['searchtext'])?$data['searchtext']:'',
                    'perpage' => !empty($data['perpage'])?trim($data['perpage']):'',
                    'uri_segment' => !empty($uri_segment)?$uri_segment:'',
                    'total_rows' => $config['total_rows']);
                $this->session->set_userdata('module_master_sortsearchpage_data', $module_master_sortsearchpage_data);
                $data['uri_segment'] = $uri_segment;
		}
		
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
    @Description: Function Add New Module details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Module details
    @Date: 27-01-2015
    */
   
    public function add_record()
    {
		$table='module_master';
		$where = array("module_parent"=>0);
		$group_by='module_parent,module_id';
		$data['modulelist']=$this->obj->getmultiple_tables_records($table,'','','','','','','','','id','desc',$group_by,$where);
		//pr($data['modulelist']);exit;
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }
	/*
    @Description: Function Add New super superadmin tempalate
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Module details
    @Date: 12-01-2015
    */
	function add_new_template()
	{
		$match = array('is_default'=>'1');
		$field=array('DISTINCT supersuperadmin_template_id');
        $new_temp = $this->obj->select_records($field,$match,'','=','','','','id','desc','');
		if(!empty($new_temp))
		{
			foreach($new_temp as $subArray){
				foreach($subArray as $val){
					$newArray[] = $val;
				}
			}
			$new_ar=implode(',',$newArray);
			$ins=$this->obj->get_new_template($this->parent_db_name,$new_ar);
			//pr($data['new_template']);exit;
		}
		else
		{
			$new_ar='';
			$ins=$this->obj->get_new_template($this->parent_db_name);	
		}
		
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $module_master_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('module_master_sortsearchpage_data', $module_master_sortsearchpage_data);
		redirect('superadmin/'.$this->viewName);
	}
    /*
    @Description: Function for Insert New Module data
    @Author: Niral Patel
    @Input: - Details of new Module which is inserted into DB
    @Output: - List of Module with new inserted records
    @Date: 27-01-2015
    */
   
    public function insert_data()
    {
            //pr($_POST);exit;
            $cdata['module_name'] = ucwords(strtolower($this->input->post('module_name')));
            $cdata['module_unique_name'] =  strtolower($this->input->post('module_unique_name'));
            $cdata['module_parent'] = $this->input->post('module_parent');
            $cdata['created_by'] = $this->superadmin_session['id'];
            $cdata['created_date'] = date('Y-m-d H:i:s');		
            $cdata['status'] = '1';
            //Insert module
            $insert_id = $this->obj->insert_record($cdata);	

            //update module
            $udata['id'] = $insert_id;		
            $udata['module_id'] = $insert_id;

            $this->obj->update_record($udata);
            /// Insert Module in All Child DB
            $db_name = $this->config->item('parent_db_name');
            $fields1 = array('id,db_name,host_name,db_user_name,db_user_password,bombbomb_username,bombbomb_password');
            $match = array('user_type'=>'2','status'=>'1');
            $all_admin = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name);
            if(!empty($all_admin) && count($all_admin) > 0)
            {
                $cdata['module_id'] = $insert_id;
                foreach ($all_admin as $row)
                {
                    if(!empty($row['db_name']))
                        $this->obj->insert_record($cdata,$row['db_name']);
                }
                unset($cdata['module_id']);
            }

            //Insert rights
            $module_right = $this->input->post('module_right');
            $module_parent = ucfirst($this->input->post('module_parent'));
            $module_name = ucfirst($this->input->post('module_name'));
            $module_unique_name =  strtolower($this->input->post('module_unique_name'));

            if(!empty($module_right) && !empty($insert_id))
            {
                for($i=0;$i<count($module_right);$i++)	
                {
                    $cdata['module_name']			= $module_name.' '.ucfirst(strtolower($module_right[$i]));
                    if(!empty($module_parent))
                    {$cdata['module_parent']		= $this->input->post('module_parent');}
                    else
                    {$cdata['module_parent']		= '-1';}
                    $cdata['module_right'] 			= $module_right[$i];
                    $cdata['module_unique_name']                = strtolower($module_unique_name.'_'.$module_right[$i]);
                    $cdata['module_id']    			= $insert_id;
                    $this->obj->insert_record($cdata);
                    if(!empty($all_admin) && count($all_admin) > 0)
                    {
                        foreach ($all_admin as $row)
                        {
                            if(!empty($row['db_name']))
                                $this->obj->insert_record($cdata,$row['db_name']);
                        }
                    }
                }
            }
            redirect('superadmin/'.$this->viewName);
	 }
	 
	/*
		@Description: Function for copy Record
		@Author: Niral Patel
		@Input: - Details of new Module which is inserted into DB
		@Output: - List of Module with new inserted records
		@Date: 27-01-2015
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
			//pr($cdata);exit;
			$this->obj->insert_record($cdata);	
			$msg = $this->lang->line('common_add_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);	
			redirect('superadmin/'.$this->viewName);
		}
     }
 
    /*
    @Description: Get Details of Edit Module Profile
    @Author: Niral Patel
    @Input: - Id of Module member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 27-01-2015
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		
		$table='module_master';
		$where = array("module_parent"=>0);
		$group_by='module_parent,module_id';
		$data['modulelist']=$this->obj->getmultiple_tables_records($table,'','','','','','','','','id','desc',$group_by,$where);
		
		$table='module_master';
		$fields = array('*,GROUP_CONCAT(case when module_right="" then null else module_right end) module_right');
		$where = array("module_id"=>$id);
		$group_by='module_parent,module_id';
		$data['editRecord']=$this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','id','desc',$group_by,$where);
		//echo $this->db->last_query();
		//pr($data['editRecord']);exit;
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
    @Description: Function for Update Module Profile
    @Author: Niral Patel
    @Input: - Update details of Module
    @Output: - List with updated Module details
    @Date: 27-01-2015
    */
   
    public function update_data()
    {
	    $cdata['id'] = $this->input->post('id');
		$cdata['template_name'] = $this->input->post('txt_template_name');
		$cdata['template_category'] = $this->input->post('slt_category');
		$cdata['template_subcategory'] = $this->input->post('slt_subcategory');
		$cdata['envelope_content']=$this->input->post('envelope_content');
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
                        $searchsort_session = $this->session->userdata('module_master_sortsearchpage_data');
                        $pagingid = $searchsort_session['uri_segment'];
			redirect('superadmin/'.$this->viewName.'/'.$pagingid);
		}
		elseif(!empty($_POST['mailout']))
		{
			$data['template_type'] = $this->input->post('template_type');
			if($data['template_type'] == 'Module')
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
					$table1='custom_field_master';
					$where1=array('module_id'=>'4');
					$data['tablefield_data']=$this->email_library_model->getmultiple_tables_records($table1,'','','','','','','','','','asc','',$where1);
					//pr($data['contact_list']);exit;
					
				///////////////////////////////////////////////////////////////////
			
				$data['main_content'] = "superadmin/mail_out/add";
        		$this->load->view('superadmin/include/template', $data);

			}
		 }
    }
	
   /*
    @Description: Function for Delete Module Profile By superadmin
    @Author: Niral Patel
    @Input: - Delete id which Module record want to delete
    @Output: - New Module list after record is deleted.
    @Date: 27-01-2015
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
    @Description: Function for Delete Module Profile By superadmin
    @Author: Niral Patel
    @Input: - Delete all id of Module record want to delete
    @Output: - Module list Empty after record is deleted.
    @Date: 27-01-2015
    */
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
        $delete_all_flag = 0;$cnt = 0;
		if(!empty($id))
		{
			$match = array('id'=>$id);
			$field=array('supersuperadmin_template_id','template_name');
			$total_temp = $this->obj->select_records($field,$match,'','=','','','','id','asc','');
			if(!empty($total_temp))
			{
				$this->obj->delete_record($id);
			}
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$match = array('id'=>$array_data[$i]);
				$field=array('supersuperadmin_template_id','template_name');
				$total_temp = $this->obj->select_records($field,$match,'','=','','','','id','asc','');
				if(!empty($total_temp))
				{
					$this->obj->delete_record($array_data[$i]);
				}
				$delete_all_flag = 1;
				$cnt++;
			}
		}
		$searchsort_session = $this->session->userdata('module_master_sortsearchpage_data');
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
		@Description: Function for Unpublish Module Profile By superadmin
		@Author: Niral Patel
		@Input: - Delete id which Module record want to Unpublish
		@Output: - New Module list after record is Unpublish.
		@Date: 27-01-2015
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
		//redirect('superadmin/'.$this->viewName);
		$envelope_id = $id;
		$pagingid = $this->obj->getemailpagingid($envelope_id);
		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Module Profile By superadmin
    @Author: Niral Patel
    @Input: - Delete id which Module record want to publish
    @Output: - New Module list after record is publish.
    @Date: 27-01-2015
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
		redirect('superadmin/'.$this->viewName);
    }
}