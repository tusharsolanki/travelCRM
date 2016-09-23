<?php 
/*
@Description: mls map controller
@Author: Niral Patel
@Input: 
@Output: 
@Date: 20-02-2015
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_map_control extends CI_Controller
{	
function __construct()
{

    parent::__construct();
    $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
   	$this->message_session = $this->session->userdata('message_session');
    check_superadmin_login();
	$this->load->model('mls_model');
	$this->load->model('contacts_model');
	$this->load->model('imageupload_model');
	$this->load->model('contact_masters_model');
	$this->obj = $this->mls_model;
	$this->viewName = $this->router->uri->segments[2];
	$this->user_type = 'superadmin';
	
}
/*
@Description: Function for Get All Envelope List
@Author: Niral Patel
@Input: - Search value or null
@Output: - all Envelope list
@Date: 20-02-2015
*/
public function index()
{
	$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
	$searchtext = mysql_real_escape_string($this->input->post('searchtext'));
	$sortfield 	= $this->input->post('sortfield');
	$sortby 	= $this->input->post('sortby');
	$searchopt 	= $this->input->post('searchopt');
	$perpage 	= trim($this->input->post('perpage'));
	$allflag 	= $this->input->post('allflag');
	$data['sortfield']		= 'id';
	$data['sortby']			= 'desc';
	
	if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
		$this->session->unset_userdata('mls_connect_sortsearchpage_data');
	}
	$searchsort_session = $this->session->userdata('mls_connect_sortsearchpage_data');
	
	if(!empty($sortfield) && !empty($sortby))
	{
		$data['sortfield'] = $sortfield;
		$data['sortby'] = $sortby;
	}
	else
	{
		if(!empty($searchsort_session['sortfield'])) 
		{
			if(!empty($searchsort_session['sortby'])) 
			{
				$data['sortfield'] = $searchsort_session['sortfield'];
				$data['sortby'] = $searchsort_session['sortby'];
				$sortfield = $searchsort_session['sortfield'];
				$sortby = $searchsort_session['sortby'];
			}
		} 
		else 
		{
			$sortfield = 'id';
			$sortby = 'desc';
		}
	}
	if(!empty($searchtext))
	{
		$data['searchtext'] = stripslashes($searchtext);
	} 
	else 
	{
		if(empty($allflag))
		{
			if(!empty($searchsort_session['searchtext'])) 
			{
				$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
     			$data['searchtext'] = $searchsort_session['searchtext'];

			}
		}
	}
	if(!empty($perpage))
	{
		$data['perpage'] = $perpage;
		$config['per_page'] = $perpage;	
	}
    else
	{
		if(!empty($searchsort_session['perpage'])) 
		{
			$data['perpage'] = trim($searchsort_session['perpage']);
			$config['per_page'] = trim($searchsort_session['perpage']);
		} 
		else 
		{
			$config['per_page'] = '10';
		}
	}

	$config['base_url'] 		= site_url($this->user_type.'/'."superadmin_management/");
    $config['is_ajax_paging'] 	= TRUE; // default FALSE
    $config['paging_function'] 	= 'ajax_paging'; // Your jQuery paging
	if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
		$config['uri_segment'] 	= 0;
		$uri_segment = 0;
	} else {
		$config['uri_segment'] 	= 3;
		$uri_segment = $this->uri->segment(3);
	}
	//Get mls lists
	$table = "mls_master as mm";
	//$fields = array('mts.*,ms.mls_name');
	$fields = array('count(itm.id) as total_mapping','mm.id','ipm.mls_id','mm.mls_name','ipm.mapping_name','ipm.mls_hostname','ipm.mls_db_username','ipm.mls_db_password','ipm.mls_db_name','ipm.mls_image_url','ipm.mls_image_url','ipm.mls_dump');
	$join_tables = array('mls_type_of_mls_master ipm'=>'mm.id = ipm.mls_id',
		'mls_type_of_mls_mapping_trans itm'=>'mm.id = itm.mls_id'
		);
	$group_by='mm.id, ipm.mls_id';
	if(!empty($searchtext))
	{
		$match=array('mm.mls_name'=>$searchtext,'ipm.mapping_name'=>$searchtext,'ipm.mls_hostname'=>$searchtext,'ipm.mls_db_username'=>$searchtext);
		$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'], $uri_segment,$sortfield,$sortby,$group_by);
		$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','',$group_by,'','','1');
	}
	else
	{
		$data['datalist']    = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by);
		$config['total_rows']= $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,'','','1');
	}
	//pr($data['datalist']);exit;	
	$this->pagination->initialize($config);
	$data['pagination'] = $this->pagination->create_links();
	$data['msg'] = $this->message_session['msg'];
	$sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' 	 => !empty($data['sortby'])?$data['sortby']:'',
			'searchtext' => !empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' 	 => !empty($data['perpage'])?trim($data['perpage']):'',
			'uri_segment'=> !empty($uri_segment)?$uri_segment:'',
			'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'');
	$this->session->set_userdata('mls_connect_sortsearchpage_data', $sortsearchpage_data);
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
function add_record()
{
	//get mls
	$data['mls_data'] = $this->obj->select_records_common('mls_master');
	
	//Get mls id
	$fields=array('mls_id');
	$mlsid = $this->obj->select_records($fields,'','','=');
	$mls_id=array();
	foreach($mlsid as $row)
	{
		$mls_id[]=$row['mls_id'];
	}

	$data['mls_id']=$mls_id;
	$data['main_content'] = "superadmin/".$this->viewName."/add";
    $this->load->view('superadmin/include/template', $data);
}
/*
    @Description: Function for Insert New Superadmin data
    @Author: Mohit Trivedi
    @Input: - Details of new Superadmin which is inserted into DB
    @Output: - List of Superadmin with new inserted records
    @Date: 10-4-2015
    */
   
public function insert_data()
{
	//pr($_POST);exit;
	$cdata['mls_id']           = $this->input->post('mls_id');
	$cdata['mapping_name']     = $this->input->post('mapping_name');
	$cdata['mls_hostname']     = $this->input->post('mls_hostname');
	$cdata['mls_db_username']  = $this->input->post('mls_db_username');
	$cdata['mls_db_password']  = $this->input->post('mls_db_password');
	$cdata['mls_db_name']      = $this->input->post('mls_db_name');
	$cdata['mls_image_url']    = $this->input->post('mls_image_url');
	$cdata['mls_comment']      = $this->input->post('mls_comment');
	$cdata['created_by']       = $this->superadmin_session['id'];
	$cdata['created_date']     = date('Y-m-d H:i:s');		
	$cdata['status']           = '1';
	$lastId=$this->obj->insert_record($cdata);
	$msg = $this->lang->line('common_add_success_msg');
	$newdata = array('msg'  => $msg);
	$this->session->set_userdata('message_session', $newdata);	
	redirect('superadmin/'.$this->viewName.'/add_table_record/'.$cdata['mls_id']);
}

/*
@Description: Get Details of Edit Superadmin Profile
@Author: Mohit Trivedi
@Input: - Id of superadmin member whose details want to change
@Output: - Details of stff which id is selected for update
@Date: 10-4-2015
*/

public function edit_record()
{
	$id = $this->uri->segment(4);

	//Get mls details
	
	$table = "mls_master as mm";
	$fields = array('mm.id as mls_id','ipm.id','mm.mls_name','ipm.mapping_name','ipm.mls_hostname','ipm.mls_db_username','ipm.mls_db_password','ipm.mls_db_name','ipm.mls_image_url','ipm.mls_image_url');
	$join_tables = array(
							'mls_type_of_mls_master as ipm' 	=> 'ipm.mls_id = mm.id',
						);
	$match = array('mm.id'=>$id);
	$cdata['editRecord'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','', '','','','',$where);

	/*$match = array('id'=>$id);
	$result = $this->obj->select_records('',$match,'','=');
	$cdata['editRecord'] = $result;*/
	//get mls
	$cdata['mls_data'] = $this->obj->select_records_common('mls_master');

	//Get mls id
	$fields=array('mls_id');
	$mlsid = $this->obj->select_records($fields,'','','=');
	$mls_id=array();
	foreach($mlsid as $row)
	{
		$mls_id[]=$row['mls_id'];
	}
	
	$cdata['mls_id']=$mls_id;
	$cdata['msg'] = $this->message_session['msg'];
	$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
	$this->load->view("superadmin/include/template",$cdata);
}

/*
@Description: Function for Update Superadmin Profile
@Author: Mohit Trivedi
@Input: - Update details of Superadmin
@Output: - List with updated Superadmin details
@Date: 10-4-2015
*/

public function update_data()
{
	//pr($_POST);exit;
	$cdata['id'] 			   = $this->input->post('id');
	$cdata['mls_id']           = $this->input->post('mls_id');
	$cdata['mapping_name']     = $this->input->post('mapping_name');
	$cdata['mls_hostname']     = $this->input->post('mls_hostname');
	$cdata['mls_db_username']  = $this->input->post('mls_db_username');
	$cdata['mls_db_password']  = $this->input->post('mls_db_password');
	$cdata['mls_db_name']      = $this->input->post('mls_db_name');
	$cdata['mls_image_url']    = $this->input->post('mls_image_url');
	$cdata['mls_comment']      = $this->input->post('mls_comment');
	$cdata['modified_by'] 	   = $this->superadmin_session['id'];
	$cdata['modified_date']    = date('Y-m-d H:i:s');		
	$cdata['status'] 		   = '1';
	$this->obj->update_record($cdata);
	//echo $this->db->last_query();exit;
	$msg = $this->lang->line('common_edit_success_msg');
	$newdata = array('msg'  => $msg);
	$this->session->set_userdata('message_session', $newdata);
	$superadmin_id = $this->input->post('id');
	$pagingid = $searchsort_session['uri_segment'];
	$searchsort_session = $this->session->userdata('superadmin_management_sortsearchpage_data');
	$pagingid = $searchsort_session['uri_segment'];
	//$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
	redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
}
/*
@Description: Function for Delete superadmin Profile By Superadmin
@Author: Niral Patel
@Input: - Delete all id of superadmin record want to delete
@Output: - superadmin post list Empty after record is deleted.
@Date: 10-4-2015
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
            $delete_all_flag = 1;
            for($i=0;$i<count($array_data);$i++) 
            {
                $cnt++;
                $this->obj->delete_record($array_data[$i]);
            }
		}
	$searchsort_session = $this->session->userdata('mls_connect_sortsearchpage_data');
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
    @Description: Function for assign table
    @Author: Niral Patel
    @Input: - 
    @Output: - Assign table
    @Date: 20-02-2015
    */
   
    public function add_table_record()
    {
    	$id = $this->uri->segment(4);
		$match = array('mls_id'=>$id);
		$result = $this->obj->select_records('',$match,'','=');
		
		if(!empty($result))
		{
			$db_host_name     = !empty($result[0]['mls_hostname'])?$result[0]['mls_hostname']:'';
			$db_user_name	  = !empty($result[0]['mls_db_username'])?$result[0]['mls_db_username']:'';
			$db_password      = !empty($result[0]['mls_db_password'])?$result[0]['mls_db_password']:'';
			$db_database_name = !empty($result[0]['mls_db_name'])?$result[0]['mls_db_name']:'';

			$data['prefix']  = strtolower(substr(str_replace(' ','',$db_database_name),0,3)).'_';
			$data['mapping_name'] = !empty($result[0]['mapping_name'])?$result[0]['mapping_name']:'';
			$mls_connection   = array('mls_id'=> $id,'mls_connection'=>'true');
			$mls_session =$this->session->set_userdata('mls_connection',$mls_connection);			
		}
		//Database connection
        $db['second']['hostname'] = $db_host_name;
        $db['second']['username'] = $db_user_name;
        //$db['second']['password'] = "ToPs@tops$$";    //For topsdemo.in
        $db['second']['password'] = $db_password;
        $db['second']['database'] = $db_database_name;
        $db['second']['dbdriver'] = 'mysql';
        $db['second']['dbprefix'] = '';
        $db['second']['pconnect'] = TRUE;
        $db['second']['db_debug'] = TRUE;
        $db['second']['cache_on'] = FALSE;
        $db['second']['cachedir'] = '';
        $db['second']['char_set'] = 'utf8';
        $db['second']['dbcollat'] = 'utf8_general_ci';
        $db['second']['swap_pre'] = '';
        $db['second']['autoinit'] = TRUE;
        $db['second']['stricton'] = FALSE;

        /*try 
        { */
        	$this->db1 = $this->load->database($db['second'],true);
        	if(!empty($this->db1))
        	{
	        	//Get all tables of database
		        $query="SHOW TABLES";
		        $query = $this->db1->query($query);
		        $udata = $query->result_array();
		        //pr($udata);exit;
		        $data['load_tables']= $udata;
		        $data['db_name']    = $db_database_name;
		        
		        $this->db->reconnect();
		        $this->db->close();
		        
		        //Get tables
		        //$match1=array('mls_id',$id);
		        $data['mls_tables_data'] = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');
		        
		        //Get child table
			    if(!empty($data['mls_tables_data']))
			    {
			    	$child_tables=array();
		    		for($i=1;$i<=20;$i++)
		    		{
		    			if(!empty($data['mls_tables_data'][0]['child_table'.$i]))
		    			{
		    				$child_tables[]=$data['mls_tables_data'][0]['child_table'.$i];
		    			}	
		    		}
		    		$data['child_tables']=$child_tables;
			    }

		        $data['main_content'] = "superadmin/".$this->viewName."/add_table";
		        $this->load->view('superadmin/include/template', $data);
		    }
		    else
		    {
	    		$msg = 'Invalid dababase credentials';
				$newdata = array('msg'  => $msg);
				$this->session->set_userdata('message_session', $newdata);
				redirect('superadmin/'.$this->viewName.'/edit_record/'.$id);
		    }    
    	/*}
    	catch (Exception $e)
    	{
    		$msg = 'Invalid dababase credentials';
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('superadmin/'.$this->viewName.'/edit_record/'.$id);
    	}*/
    }
    /*
	@Description: Function for insert master table
	@Author: Niral Patel
	@Input: - mls credentials
	@Output: - map mls
	@Date: 10-4-2015
	*/
    public function insert_master_table()
	{
		$cdata['mls_id']           = $this->input->post('mls_id');
		$cdata['main_table']	   = $this->input->post('master_table');
		$cdata['created_by']       = $this->superadmin_session['id'];
		$cdata['created_date']     = date('Y-m-d H:i:s');	
			
		$child_db = $this->input->post('child_db');

				
		for($i=0;$i<20;$i++)
		{
			$k=$i+1;
			$cdata['child_table'.$k]=isset($child_db[$i])?$child_db[$i]:'';	
		}		
		
		//pr($cdata);exit;
		$match=array('mls_id'=>$cdata['mls_id']);
        $res = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');
        if(empty($res))
        {
        	$lastId = $this->obj->insert_common('mls_livewire_table_mapping',$cdata);
        }
        else
        {
        	$lastId = $this->obj->update_record_table('mls_livewire_table_mapping',$cdata);
        }
		$msg = $this->lang->line('common_add_success_msg');
		$newdata = array('msg'  => $msg);
		$this->session->set_userdata('message_session', $newdata);
		$this->db->reconnect();
        $this->db->close();	
		redirect('superadmin/'.$this->viewName);
	}
/*
@Description: Function for map mls
@Author: Niral Patel
@Input: - mls credentials
@Output: - map mls
@Date: 10-4-2015
*/
function add_mls()
{
	ini_set('memory_limit', '-1');	
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	
    $id = $this->uri->segment(4);
	$match = array('mls_id'=>$id);
	$result = $this->obj->select_records('',$match,'','=');
	// Get selected tables
	$match = array('mls_id'=>$id);
	$data['mls_tables_data'] = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');
	//pr($data['mls_tables_data']);exit;

	/*if(!empty($result))
	{
		$db_host_name     = !empty($result[0]['mls_hostname'])?$result[0]['mls_hostname']:'';
		$db_user_name	  = !empty($result[0]['mls_db_username'])?$result[0]['mls_db_username']:'';
		$db_password      = !empty($result[0]['mls_db_password'])?$result[0]['mls_db_password']:'';
		$db_database_name = !empty($result[0]['mls_db_name'])?$result[0]['mls_db_name']:'';
	}
	else
	{
		$db_host_name     = "localhost";
		$db_user_name	  = "root";
		$db_password      = "";
		$db_database_name = "livewire_master_database";
	}*/
	$db_database_name = $this->config->item('mls_master_db');
    $db_host_name     = $this->config->item('mls_master_host');
    $db_user_name     = $this->config->item('mls_master_username');
    $db_password      = $this->config->item('mls_master_password');
	

	$db = '';
	if(!empty($db_host_name) && !empty($db_user_name)  && !empty($db_database_name))
	{
		/*----------Connect to credentials-------------*/
		$db['second']['hostname'] = $db_host_name;
		$db['second']['username'] = $db_user_name;
		//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
		$db['second']['password'] = $db_password;
		$db['second']['database'] = $db_database_name;
		
		$db['second']['dbdriver'] = 'mysql';
		$db['second']['dbprefix'] = '';
		$db['second']['pconnect'] = TRUE;
		$db['second']['db_debug'] = TRUE;
		$db['second']['cache_on'] = FALSE;
		$db['second']['cachedir'] = '';
		$db['second']['char_set'] = 'utf8';
		$db['second']['dbcollat'] = 'utf8_general_ci';
		$db['second']['swap_pre'] = '';
		$db['second']['autoinit'] = TRUE;
		$db['second']['stricton'] = FALSE;
		
		$this->db1 = $this->load->database($db['second'], TRUE);


		//Get selected table
	    if(!empty($data['mls_tables_data']))
	    {
	    	$child_tables=array();
	    	$main_tables=array();
	    	$main_tables[]=$data['mls_tables_data'][0]['main_table'];
	    	
    		for($i=1;$i<=20;$i++)
    		{
    			if(!empty($data['mls_tables_data'][0]['child_table'.$i]))
    			{
    				$child_tables[]=$data['mls_tables_data'][0]['child_table'.$i];
    			}	
    		}
    		$table=array_merge($main_tables,$child_tables);
    		$tables = $table;
	    }
	    //Get main table fields
	    $mainresult = $this->db1->list_fields($data['mls_tables_data'][0]['main_table']);
	    
		foreach($mainresult as $field)
		{
			$fdata1[]	= $field;
		}
		$maindata[$data['mls_tables_data'][0]['main_table']][] = $fdata1;
		unset($fdata1);
	    //pr($tables);exit;
	    foreach($child_tables as $table_name)
    	{
		    $result = $this->db1->list_fields($table_name);
    		foreach($result as $field)
			{
				$fdata[]	= $field;
			}
			$tdata[$table_name][] = $fdata;
			unset($fdata);
		}
		$data['child_tables'] = $child_tables;
		$data['field_data']	  = $tdata;
		$data['main_data']	  = $maindata;
		$data['main_table']=$data['mls_tables_data'][0]['main_table'];
		//pr($data['main_data']);exit;
		//Get all tables of database
		/*$query="SHOW TABLES";
	    $query = $this->db1->query($query);
		$udata = $query->result_array();
		//pr($udata);exit;
		$data['load_tables']=$udata;


		$tdata=array();
		foreach($udata as $row)
		{
			$table_name=$row['Tables_in_'.$db_database_name];
			$result = $this->db1->list_fields($table_name);
			foreach($result as $field)
			{
				$fdata[]	= $field;
			}
			$tdata[$table_name][] = $fdata;
			unset($fdata);
		}
		$data['field_data']= $tdata;*/
		$data['db_name']   = $db_database_name;
	    //echo microtime()-$starttime;exit;
	}
	$this->db->reconnect();
    $this->db->close();	
    //Get mls field data
	$mls_fields=$this->obj->select_records_common('mls_type_of_mls_mapping_master');
	$data['mls_property_fields']= $mls_fields;
		
	//Get mls area field data
	$mls_fields=$this->obj->get_field('mls_area_community_data');
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_area_community_fields']=$mls_fields;

	//Get mls memeber field data
	$mls_fields=$this->obj->get_field('mls_member_data');
	//pr($mls_fields);	
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	//array_pop($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}

	
	$data['mls_member_fields']=$mls_fields;
	
	//Get mls office field data
	$mls_fields=$this->obj->get_field('mls_office_data');
	//pr($mls_fields);	
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_office_fields']=$mls_fields;
	//pr($data['mls_office_fields']);exit;
	//Get mls school field data
	$mls_fields=$this->obj->get_field('mls_school_data');
	//pr($mls_fields);	
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_school_fields']=$mls_fields;
	
	$match = array("status"=>'1');
	$data['contact_mapping_list']= $this->contact_masters_model->select_records1('',$match,'','=','','','','','','mls_csv_mapping_master');

	
	//Get mls amenity field data
	$mls_fields=$this->obj->get_field('mls_amenity_data');
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_amenity_fields']=$mls_fields;

	//Get mls property history field data
	$mls_fields=$this->obj->get_field('mls_property_history_data');
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_prop_history_fields']=$mls_fields;

	//Get mls image field data
	$mls_fields=$this->obj->get_field('mls_property_image');
	//pr($mls_fields);	
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=3;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	$data['mls_property_image_fields']=$mls_fields;
	//Get mapping data
	$field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
	$match = array('mls_id'=>$id);
	$mapping_data   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','mls_master_field_id','asc');
	//pr($mapping_data);exit;
	$map_data=array();
	$map_table=array();
	$map_fileds=array();
	foreach($mapping_data as $row)
	{
		$map_data[$row['mls_master_field']] = $row['mls_field'];
		$map_table[$row['mls_master_field']]= $row['mls_field_table'];
		$map_fileds[$row['mls_master_field']]= $row['mls_transection_field'];
	}
	
	$data['mapping_data']  = $map_data;
	$data['map_table']     = $map_table;
	$data['map_fileds']    = $map_fileds;
	// /pr($data['map_fileds']);exit;
	
	//Get child table trasection table
	
	$match = array('mls_id' => $id);
	$data['child_tables_tran'] = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');
	// /pr($data['child_tables_tran']);exit;
	$data['main_content'] = $this->user_type.'/'.$this->viewName."/add_mls";
	$this->load->view('superadmin/include/template',$data);
}
/*
@Description: Function to Insert Contact
@Author: Niral Patel
@Input: k
@Output: - 
@Date: 12-03-2015
*/
function insert_mls()
{
	$id = $this->input->post('id'); //mls_id
	$office_table =$this->input->post('ofiice_table_name');
	$school_table_name =$this->input->post('school_table_name');
	$area_community_table_name =$this->input->post('area_community_table_name');
	$amenity_table_name =$this->input->post('amenity_table_name');
	$property_history_table_name =$this->input->post('property_history_table_name');
	$image_table_name =$this->input->post('image_table_name');
	$member_table_name =$this->input->post('member_table_name');
	
	$tab_data['ofiice_table_name']           = !empty($office_table)?$office_table:'';
	$tab_data['school_table_name'] 		     = !empty($school_table_name)?$school_table_name:'';
	$tab_data['area_community_table_name']   = !empty($area_community_table_name)?$area_community_table_name:'';
	$tab_data['amenity_table_name'] 	     = !empty($amenity_table_name)?$amenity_table_name:'';
	$tab_data['property_history_table_name'] = !empty($property_history_table_name)?$property_history_table_name:'';
	$tab_data['image_table_name'] 			 = !empty($image_table_name)?$image_table_name:'';
	$tab_data['member_table_name'] 			 = !empty($member_table_name)?$member_table_name:'';
	$tab_data['mls_id'] 			         = !empty($id)?$id:'';

	//Get trasection table mapping
	$fields=array('mls_id');
	$match = array('mls_id' => $id);
	$tablee_data = $this->obj->select_records_common('mls_child_table_mapping',$fields,$match,'','=');

	if(!empty($tablee_data) && count($tablee_data) > 0)
	{
		$this->obj->update_record_table('mls_child_table_mapping',$tab_data);	
	}
	else
	{
		$this->obj->insert_common('mls_child_table_mapping',$tab_data);	
	}
	
	
	//Get master mls fields
	$field = array('id','master_field_name');
	$mls_fields=$this->obj->select_records_common('mls_type_of_mls_mapping_master',$field);
	
	if(!empty($mls_fields))
	{
		foreach($mls_fields as $fieldname)
		{
			$fields=$fieldname['master_field_name'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     	 = $id;
				$template['table_id'] 		     	 = 1;
				$template['mls_master_field_id'] 	 = $fieldname['id'];
				$template['mls_master_field']    	 = $fields;
				$template['mls_field'] 			 	 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	 = $this->input->post('fld_'.$fields);
				
				
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
				
			}	
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);		
					//echo $this->db->last_query();		
				}
			}
		}
		unset($template);
	}
	//Get mls image field data
	$mls_image_fields=$this->obj->get_field('mls_property_image');
	$last_ar=count($mls_image_fields);
	unset($mls_image_fields[0]);
	unset($mls_image_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_image_fields[$last_ar-$k]);	
	}
	if(!empty($mls_image_fields))
	{
		foreach($mls_image_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     	= $id;
				$template['table_id'] 		     	= 8;
				$template['mls_master_field']    	= $fields;
				$template['mls_field'] 				= $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 		= $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}	
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}	
		}
		unset($template);
	}
	//Get mls area field data
	$mls_area_fields=$this->obj->get_field('mls_area_community_data');
	$last_ar=count($mls_area_fields);
	unset($mls_area_fields[0]);
	unset($mls_area_fields[1]); 
	for($k=1;$k<=2;$k++)
	{
		unset($mls_area_fields[$last_ar-$k]);	
	}
	//pr($_POST);exit;
	if(!empty($mls_area_fields))
	{
		foreach($mls_area_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 2;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
				
			}	
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}
		}
		unset($template);
	}
	//Get mls member field data
	$mls_member_fields=$this->obj->get_field('mls_member_data');
	$last_ar=count($mls_member_fields);
	unset($mls_member_fields[0]);
	unset($mls_member_fields[1]);

	for($k=1;$k<=2;$k++)
	{
		unset($mls_member_fields[$last_ar-$k]);	
	}
	if(!empty($mls_member_fields))
	{
		foreach($mls_member_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 3;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}	
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}	

		}
		unset($template);
	}

	//Get mls office field data
	$mls_office_fields=$this->obj->get_field('mls_office_data');
	$last_ar=count($mls_office_fields);
	unset($mls_office_fields[0]);
	unset($mls_office_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_office_fields[$last_ar-$k]);	
	}
	if(!empty($mls_office_fields))
	{
		foreach($mls_office_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 4;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}	
		}
		unset($template);
	}
	//Get mls school field data
	$mls_school_fields=$this->obj->get_field('mls_school_data');
	$last_ar=count($mls_school_fields);
	unset($mls_school_fields[0]);
	unset($mls_school_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_school_fields[$last_ar-$k]);	
	}
	if(!empty($mls_school_fields))
	{
		foreach($mls_school_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 5;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}		
		}
		unset($template);
	}
	//Get mls amenity field data
	$mls_amenity_fields=$this->obj->get_field('mls_amenity_data');
	$last_ar=count($mls_amenity_fields);
	unset($mls_amenity_fields[0]);
	unset($mls_amenity_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_amenity_fields[$last_ar-$k]);	
	}
	if(!empty($mls_amenity_fields))
	{
		foreach($mls_amenity_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 6;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}	
		}
		unset($template);
	}
	//Get mls property field data
	$mls_prop_history_fields=$this->obj->get_field('mls_property_history_data');
	$last_ar=count($mls_prop_history_fields);
	unset($mls_prop_history_fields[0]);
	unset($mls_prop_history_fields[1]);
	for($k=1;$k<=2;$k++)
	{
		unset($mls_prop_history_fields[$last_ar-$k]);	
	}
	if(!empty($mls_prop_history_fields))
	{
		foreach($mls_prop_history_fields as $fieldname)
		{
			$fields=$fieldname['Field'];
			//Get mls mapping trasection data
			$field = array('id','mls_id');
			$match = array('mls_id'=>$id,'mls_master_field'=>$fields);
			$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
			
			if($this->input->post('slt_'.$fields) != '')
			{
				$template['mls_id'] 		     = $id;
				$template['table_id'] 		     = 7;
				$template['mls_master_field']    = $fields;
				$template['mls_field'] 			 = $this->input->post('slt_'.$fields);
				$template['mls_field_table'] 	 = $this->input->post('tbl_'.$fields);
				$template['mls_transection_field'] 	= $this->input->post('fld_'.$fields);	
				if(empty($res))
				{
					//Insert data
					$this->obj->insert_mls_mapping_trans_record($template);				
				}
				else
				{
					//Update data
					$this->obj->update_mls_mapping_trans_record($template);
				}
			}
			else
			{
				if(!empty($res))
				{
					//delete data
					$this->obj->delete_mls_mapping_trans_record($res[0]['id']);				
				}
			}	
		}
		unset($template);
	}
	
	redirect($this->user_type.'/'.$this->viewName);
}

function get_table_stucture()
{
	//pr($_POST);exit;
	try
	{
		$db_host_name     = $this->input->post('db_host_name');
		$db_user_name	  = $this->input->post('db_user_name');
		$db_password      = $this->input->post('db_password');
		$db_database_name = $this->input->post('db_database_name');
		$db = '';
		if(!empty($db_host_name) && !empty($db_user_name) && !empty($db_password) && !empty($db_database_name))
		{
			$db['second']['hostname'] = $db_host_name;
			$db['second']['username'] = $db_user_name;
			//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
			$db['second']['password'] = $db_password;
			$db['second']['database'] = $db_database_name;
			$db['second']['dbdriver'] = 'mysql';
			$db['second']['dbprefix'] = '';
			$db['second']['pconnect'] = TRUE;
			$db['second']['db_debug'] = TRUE;
			$db['second']['cache_on'] = FALSE;
			$db['second']['cachedir'] = '';
			$db['second']['char_set'] = 'utf8';
			$db['second']['dbcollat'] = 'utf8_general_ci';
			$db['second']['swap_pre'] = '';
			$db['second']['autoinit'] = TRUE;
			$db['second']['stricton'] = FALSE;
			
			//pr($db['second']);exit;
			
			$this->db1 = $this->load->database($db['second'], TRUE);

			//pr($this->db1);exit;

			$query="SHOW TABLES";
		    $query = $this->db1->query($query);
			$udata = $query->result_array();
			$tdata=array();
			foreach($udata as $row)
			{
				$table_name=$row['Tables_in_asheville'];
				//$query="SHOW COLUMNS FROM ".$table_name;
		        //$query="ALTER TABLE ".$this->table_name3."  ADD ".$field_name." ".$field_type."(".$field_size.") NOT NULL AFTER ".$last_field."";
		        //$query = $this->db1->query($query);
		        //$tdata[]=$query->result_array();
				$result = $this->db1->list_fields($table_name);
				foreach($result as $field)
				{
				$data[]	= $field;
				}
				$tdata[$table_name][] = $data;
			}
			pr($tdata);exit;
		}
		else
		{
			echo 'Please enter database credentials.';
		}
	}catch(DynamoDbException $e) {
				echo 'The item could not be Retrieve.';
			}

}
function get_table_data()
{
	$table_name=$this->uri->segment(4);
	set_time_limit(0);
	$db = '';
	$db['second']['hostname'] = "104.130.52.103";
	$db['second']['username'] = "asheville";
	//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
	$db['second']['password'] = "asheville";			//Local
	$db['second']['database'] = "asheville";
	$db['second']['dbdriver'] = 'mysql';
	$db['second']['dbprefix'] = '';
	$db['second']['pconnect'] = TRUE;
	$db['second']['db_debug'] = TRUE;
	$db['second']['cache_on'] = FALSE;
	$db['second']['cachedir'] = '';
	$db['second']['char_set'] = 'utf8';
	$db['second']['dbcollat'] = 'utf8_general_ci';
	$db['second']['swap_pre'] = '';
	$db['second']['autoinit'] = TRUE;
	$db['second']['stricton'] = FALSE;
	
	//pr($db['second']);exit;
	
	$this->db1 = $this->load->database($db['second'], TRUE);
	/*$query="SHOW TABLES";
    /*$query = $this->db1->query($query);
	$udata = $query->result_array();
	$tdata=array();
	foreach($udata as $row)
	{
		$table_name=$row['Tables_in_asheville'];
		//$query="SHOW COLUMNS FROM ".$table_name;
        $query="select * from ".$table_name;
        $query = $this->db1->query($query);
        $result = $query->result_array();
		//$result = $this->db1->list_fields($table_name);
		foreach($result as $field)
		{
			$data[]	= $field;
		}
		$tdata[$table_name][] = $data;
	}*/
	//$table_name=$row['Tables_in_asheville'];
	//$query="SHOW COLUMNS FROM ".$table_name;
    $query="select * from ".$table_name;
    $query = $this->db1->query($query);
    $result = $query->result_array();
    //pr($result);exit;
	//$result = $this->db1->list_fields($table_name);
	foreach($result as $field)
	{
		$data[]	= $field;
	}	
	pr($data);exit;
}
/*
@Description: Function for get field list
@Author: Niral Patel
@Input: - Mappind id
@Output: - Field list
@Date: 11-03-2015
*/
function get_filed_list()
{ 
	$csv_mapping_id=$this->input->post('mapping_id');
	$match = array("csv_mapping_id"=>$csv_mapping_id);
	$field_list = $this->contact_masters_model->select_records1('',$match,'','=','','','','','asc','mls_csv_mapping_trans');
	//pr($field_list);
	echo json_encode($field_list);
			
}
/*
@Description: Function for add new field in mls property
@Author: Niral Patel
@Input: - 
@Output: - 
@Date: 11-03-2015
*/
function add_new_field()
{

	$field_name 	= $this->input->post('field_name');
	$field_type 	= $this->input->post('field_type');
	$field_size 	= $this->input->post('field_size');
	$field_comment 	= $this->input->post('field_comment');
	$last_field 	= $this->input->post('last_field');
	$tblname 		= $this->input->post('tblname');
	
    //Get mls field
	if(!empty($tblname) && $tblname == 'property')
	{$mls_fields  = $this->obj->list_mls_fields('mls_property_list_master');$tbl_name='mls_property_list_master';}
	if(!empty($tblname) && $tblname == 'office')
	{$mls_fields  = $this->obj->list_mls_fields('mls_office_data');$tbl_name='mls_office_data';}
	if(!empty($tblname) && $tblname == 'school')
	{$mls_fields  = $this->obj->list_mls_fields('mls_school_data');$tbl_name='mls_school_data';}
	if(!empty($tblname) && $tblname == 'member')
	{$mls_fields  = $this->obj->list_mls_fields('mls_member_data');$tbl_name='mls_member_data';}
	if(!empty($tblname) && $tblname == 'area_community')
	{$mls_fields  = $this->obj->list_mls_fields('mls_area_community_data');$tbl_name='mls_area_community_data';}
	if(!empty($tblname) && $tblname == 'amenities')
	{$mls_fields  = $this->obj->list_mls_fields('mls_amenity_data');$tbl_name='mls_amenity_data';}
	
	$mls_fields  = array_map('strtolower', $mls_fields);
	$field_name1 = strtolower($field_name);
	if(in_array($field_name,$mls_fields) || in_array($field_name1, $mls_fields))     
	{
	   	echo 'error';
	}  
	else
	{
	  	$check=$this->obj->add_new_field($field_name,$field_type,$field_size,$field_comment,$last_field,$tbl_name);
	  	echo $field_name;     
	}
}
/*
@Description: Function for display cron link
@Author: Niral Patel
@Input: - 
@Output: - 
@Date: 11-03-2015
*/
public function cron_link()
{
	$id     = $this->uri->segment(4);
	echo '-------- Retrive data from nwmls and import to staging db --------<br>';
	$path=base_url().'superadmin/';
	echo 'Retrieve amenity data => <a href="'.$path.'mls_import/retrieve_amenity_data" >'.$path.'mls_import/retrieve_amenity_data</a>' ;
	echo '<br>';
	echo 'Retrieve Member data => <a href="'.$path.'mls_import/retrieve_member_data" >'.$path.'mls_import/retrieve_member_data</a>';
	echo '<br>';
	echo 'Retrieve Office data => <a href="'.$path.'mls_import/retrieve_office_data" >'.$path.'mls_import/retrieve_office_data</a>';
	echo '<br>';
	echo 'Retrieve School data => <a href="'.$path.'mls_import/retrieve_school_data" >'.$path.'mls_import/retrieve_school_data</a>';
	echo '<br>';
	echo 'Retrieve Property Listing data => <a href="'.$path.'mls_import/retrieve_listing_data" >'.$path.'mls_import/retrieve_listing_data</a>';
	echo '<br>';
	echo 'Retrieve area community data => <a href="'.$path.'mls_import/retrieve_area_community_data" >'.$path.'mls_import/retrieve_area_community_data</a>';
	echo '<br>';
	echo 'Retrieve Image data => <a href="'.$path.'mls_import/retrieve_image_data" >'.$path.'mls_import/retrieve_image_data</a>';
	echo '<br>';
	echo 'Retrieve Property History data => <a href="'.$path.'mls_import/retrieve_listing_history_data" >'.$path.'mls_import/retrieve_listing_history_data</a>';
	echo '<br><br>';
	
	echo '---------- Dumping nwmls data from staging db to master db ---------<br>';
	echo 'Retrieve amenity data => <a href="'.$path.'mls_add/retrieve_amenity_data" >'.$path.'mls_add/retrieve_amenity_data</a>' ;
	echo '<br>';
	echo 'Retrieve Member data => <a href="'.$path.'mls_add/retrieve_member_data" >'.$path.'mls_add/retrieve_member_data</a>';
	echo '<br>';
	echo 'Retrieve Office data => <a href="'.$path.'mls_add/retrieve_office_data" >'.$path.'mls_add/retrieve_office_data</a>';
	echo '<br>';
	echo 'Retrieve School data => <a href="'.$path.'mls_add/retrieve_school_data" >'.$path.'mls_add/retrieve_school_data</a>';
	echo '<br>';
	echo 'Retrieve Property Listing data => <a href="'.$path.'mls_add/retrieve_listing_data" >'.$path.'mls_add/retrieve_listing_data</a>';
	echo '<br>';
	echo 'Retrieve area community data => <a href="'.$path.'mls_add/retrieve_area_community_data" >'.$path.'mls_add/retrieve_area_community_data</a>';
	echo '<br>';
	echo 'Retrieve Image data => <a href="'.$path.'mls_add/retrieve_image_data" >'.$path.'mls_add/retrieve_image_data</a>';
	echo '<br>';
	echo 'Retrieve Property History data => <a href="'.$path.'mls_add/retrieve_listing_history_data" >'.$path.'mls_add/retrieve_listing_history_data</a>';
	echo '<br><br>';
	
	echo '---------- Mapping data to 100 fields database ------------ <br>';
	echo 'Retrieve Office data => <a href="'.$path.'mls_import/import_office_map" >'.$path.'mls_import/import_office_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve Member data => <a href="'.$path.'mls_import/import_member_map" >'.$path.'mls_import/import_member_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve School data => <a href="'.$path.'mls_import/import_school_map" >'.$path.'mls_import/import_school_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve amenity data => <a href="'.$path.'mls_import/import_amenity_map" >'.$path.'mls_import/import_amenity_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve area community data => <a href="'.$path.'mls_import/import_area_community_map" >'.$path.'mls_import/import_area_community_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve Image data => <a href="'.$path.'mls_import/import_image_map" >'.$path.'mls_import/import_image_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve Property History data => <a href="'.$path.'mls_import/import_prop_history_map" >'.$path.'mls_import/import_prop_history_map/mls_id</a>';
	echo '<br>';
	echo 'Retrieve Property Listing data => <a href="'.$path.'mls_import/import_property_map" >'.$path.'mls_import/import_property_map/mls_id</a>';
	echo '<br>';

}
function mls_cron_link()
{
	$id = $this->uri->segment(4);
	$data['id']     = $this->uri->segment(4);
	$path=base_url().'superadmin/';
	$data['path'] =$path;
	if($id == 1)
	{
		$data['main_content'] = "superadmin/".$this->viewName."/set_cron_nwmls";
	}
	else
	{
		$data['main_content'] = "superadmin/".$this->viewName."/set_cron";
	}
	
	$this->load->view('superadmin/include/template', $data);
	/*echo '---------- Mapping data to 100 fields database ------------ <br>';
	echo 'Retrieve Office data => <a href="'.$path.'mls_import/import_office_map/'.$id.'" >'.$path.'mls_import/import_office_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve Member data => <a href="'.$path.'mls_import/import_member_map/'.$id.'" >'.$path.'mls_import/import_member_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve School data => <a href="'.$path.'mls_import/import_school_map/'.$id.'" >'.$path.'mls_import/import_school_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve amenity data => <a href="'.$path.'mls_import/import_amenity_map/'.$id.'" >'.$path.'mls_import/import_amenity_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve area community data => <a href="'.$path.'mls_import/import_area_community_map/'.$id.'" >'.$path.'mls_import/import_area_community_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve Image data => <a href="'.$path.'mls_import/import_image_map/'.$id.'" >'.$path.'mls_import/import_image_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve Property History data => <a href="'.$path.'mls_import/import_prop_history_map/'.$id.'" >'.$path.'mls_import/import_prop_history_map/'.$id.'</a>';
	echo '<br>';
	echo 'Retrieve Property Listing data => <a href="'.$path.'mls_import/import_property_map/'.$id.'" >'.$path.'mls_import/import_property_map/'.$id.'</a>';
	echo '<br>';*/

}
function set_cron_link()
{
	$id 	   = $this->input->post('id');
	$fun_name  = $this->input->post('fun_name');
	$num 	   = $this->input->post('num');
	$mls_update= $this->input->post('mls_update');
	
	
	/*$meeting_time = date('Y-m-d h:i:s');
	$datetime = date('Y-m-d H:i:s', strtotime('+6 hours', strtotime($meeting_time)));
    $datetime = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($datetime)));
    $minute   = date("i",strtotime($datetime)); 
    $hour     = date("H",strtotime($datetime)); 
	$day 	  = date("d",strtotime($datetime)); 
	$month	  = date("m",strtotime($datetime)); */
	$minute   = '*/5'; 
    $hour     = '*'; 
	$day 	  = '*'; 
	$month	  = '*';
	//$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
	//$cron_time = '5';
	/*if(!empty($num))
	{
		$offset = 0;
		$num = $num.'/'.$offset.'/';
	}
	else
	{
		$num    = 0;
		$offset = 0;
		$num = $num.'/'.$offset.'/';
	}*/
	
	if(!empty($mls_update) && $mls_update == 'update')
	{$url    = base_url().'superadmin/mls_update/'.$fun_name.'/'.$id;}
	else
	{$url    = base_url().'superadmin/mls_import/'.$fun_name.'/'.$id;}
	//$url    = base_url().'superadmin/mls_import/import_staging_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
	//$url1    = base_url().'superadmin/mls_import/import_master_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
	
     //echo $minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url;
    //echo '<br>';
    //echo $minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url1;exit;
    //exit;
 	$output = shell_exec('crontab -l');
    file_put_contents('../../../../tmp/cron.txt', $output.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url.''.PHP_EOL);
    echo exec('crontab ../../../../tmp/cron.txt');
    echo 1;exit;
	

}
function insert_mls_fields()
{
	/*$flds = array('CAP','ELEX','EXP','GAI','GRM','GSI','GSP','HET','INS','NCS','NOI','OTX','SIB','TEX','TIN','TSP','UBG','USP','VAC','WSG','AMN','LIT','UN1','BR1','BA1','SF1','RN1','FP1','WD1','RO1','FG1','DW1','UN2','BR2','BA2','SF2','RN2','FP2','WD2','RO2','FG2','DW2','UN3','BR3','BA3','SF3','RN3','FP3','WD3','RO3','FG3','DW3','UN4','BR4','BA4','SF4','RN4','FP4','WD4','RO4','FG4','DW4','UN5','BR5','BA5','SF5','RN5','FP5','WD5','RO5','FG5','DW5','UN6','BR6','BA6','SF6','RN6','FP6','WD6','RO6','FG6','DW6','AMP','AVP','BON','CHT','CSP','DLT','ENV','EXA','FAC','NNN','OSF','PAD','SIZ','STF','TAV','TRI','TSF','VAI','VAL','WSF','YVA','CFE','LDG','TN1','LX1','NN1','US1','TN2','LX2','NN2','US2','TN3','LX3','NN3','US3','TN4','LX4','NN4','US4','TN5','LX5','NN5','US5','TN6','LX6','NN6','US6','ACC','BCC','BRI','BSZ','CCC','CRI','EQI','LCC','IRRC','PSZ','SSZ','TAC','VCC','BFE','BTP','EQP','FEN','FTP','IRS','ITP','LEQ','LTG','LTP','OUT','STP','ELEV','AGR','LNI','MFY','NOH','PAS','PRK','SKR','SPR','UCS','ANC','MHF','OTR','PKA','SRI');
	
	foreach($flds as $value) 
	{
		echo '$data[$i]["'.$value.'"]                        =!empty($row["'.$value.'"])?$row["'.$value.'"]:"";';
		echo '<br>';
	}
	exit;
	$mls_fields=$this->obj->select_records_common('mls_type_of_mls_mapping_master');
	foreach($mls_fields as $row)
	{
		//pr($row);
		//Get mls mapping trasection data
		$field = array('id','mls_id');
		$match = array('mls_id'=>6,'mls_master_field'=>$row['master_field_name']);
		$res   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=');
		//pr($res);
		//echo $row['master_field_name'];exit;
		//echo $row['master_field_name'];
		if(!empty($row['master_field_name']))
		{
			$template['mls_id'] 		     	 = 6;
			$template['table_id'] 		     	 = 1;
			$template['mls_master_field_id'] 	 = $row['id'];
			$template['mls_master_field']    	 = $row['master_field_name'];
			$template['mls_field'] 			 	 = 'mls_property_list_master.'.$row['master_field_name'];
			$template['mls_field_table'] 	 	 = '';
			$template['mls_transection_field'] 	 = '';
			
			$template['created_by'] 	 		 = $this->superadmin_session['id'];
			
			
			if(empty($res))
			{
				//Insert data
				echo 'Insert =>'.$row['master_field_name'].'<br>';
				$template['created_date'] 	 	= date('Y-m-d h:i:s');

				$this->obj->insert_mls_mapping_trans_record($template);				
			}
			else
			{
				//Update data
				echo 'Update =>'.$row['master_field_name'].'<br>';
				$template['modified_date'] 	 	= date('Y-m-d h:i:s');
				$this->obj->update_mls_mapping_trans_record($template);
			}
			
		}
	}
	exit;*/
	//Insert into fields master 
	$mlss=$this->config->item('mls_master_db');
	//$mls_fields=$this->obj->get_field('mls_property_list_master');	
	$mls_fields=$this->obj->get_field($mlss.'.fl__rc_data');	

	//pr($mls_fields);exit;
	$last_ar=count($mls_fields);
	unset($mls_fields[0]);
	unset($mls_fields[1]);
	for($k=1;$k<=7;$k++)
	{
		unset($mls_fields[$last_ar-$k]);	
	}
	//pr($mls_fields);exit;
	$fields ='';$comment ='';
	echo '<table>';
	foreach($mls_fields as $row)
	{
		//$fields  .="'".$row['Field']."'".",";	
		//$comment .="'".$row['Comment']."'".",";
		//$fields[]  =$row['Field'];	
		//$comment[] =$row['Comment'];	
		//echo $row['Field'] .'=>'.$row['Comment'].'('.$row['Type'].')';
		echo '<tr><td>'.$row['Field'] .'</td><td>'.$row['Type'].'</td></tr>';
		//echo "'".$row['Field'] ."'=>'".$row['Comment']."',";
		//echo '<br>';
	}
	echo '</table>';
	exit;
	//Insert fields
	//$fields=array('LN','PTYP','LAG','ST','LP','SP','OLP','HSN','DRP','STR','SSUF','DRS','UNT','CIT','STA','ZIP','PL4','BR','BTH','ASF','LSF','UD');	
	//$comment=array('Listing Number','Property Type','Listing Agent Number','Status','Listing Price','Selling Price','Original Price','House Number','Directional Prefix','Street','Street Suffix','Directional Suffix','Unit','City','State','Zip Code','Zip Plus 4','Bedrooms','Bathrooms','Approximate Square Footage','Lot Square Footage','Update Date');	
	//$fields=array('CLA','LO','COLO','SD');	
	//$comment=array('Co-Listing Agent Number','Listing Office Number','Co Listing Number','School District Code');	
	/*$fields=array(
'IMP'=>'Improvements',
'OLP'=>'Orginial Listing Price',
'TAX'=>'Parcel Number',
'PIC'=>'Pictures',
'POS'=>'Possession',
'STY'=>'Style',
'VIRT'=>'Virtual Tour URL',
'SIZ'=>'Approx Building SqFt',
'ENS'=>'Energy Source',
'YRE'=>'Year Established',
'property_id'=>'',
'TotalUnits'=>'Number of Units in Building',
'BathsHalf'=>'1/2 Bathrooms',
'BathsForth'=>'1/4 Bathrooms',
'BathsThird'=>'3/4 Bathrooms',
'petsYN'=>'Cats & Dogs',
'DaysOnMarket'=>'Days on Market',
'ExteriorFeatures'=>'Exterior Features',
'Subdivision'=>'Neighborhood',
'PublicRemarks'=>'Property Description',
'SqFtLevel1'=>'Sq Ft Level 1',
'SqFtLevel2'=>'Sq Ft Level 2',
'SqFtLevel3'=>'Sq Ft Level 3',
'AdditionalStatus'=>'Additional Status',
'BasementSqFtFinished'=>'Basement Sq Ft Finished',
'BasementSqFtUnfinished'=>'Basement Sq Ft Unfinished',
'Cooling'=>'Cooling',
'InsideCityLimitsYN'=>'Inside City Limits',
'Porch'=>'Porch',
'StateRdYN'=>'State Road',
'WheelChairAccessYN'=>'Wheel Chair Accessible',
'BusinessType'=>'Business Type',
'Basement'=>'Basement',
'Heating'=>'Heating',
'WoodedAcres'=>'Wooded Acres',
'MfgHomesAllowedYN'=>'Manufactured Homes Allowed',
'SuitableUse'=>'Suitable Use',
'Foundation'=>'Manufactured Foundation',
'SoldPricePerSqFt'=>'$ sq/ft',
'HOARentIncludes'=>'Association Fee Includes',
'HOAPaymentFreq'=>'Association Payment Freq.',
'KitchenAppliances'=>'Kitchen Applicances',
'ParkingDescription'=>'Parking',
'PriceCurrentForStatus'=>'Price',
'SeniorHousingYN'=>'Senior Housing',
'SqFtLowerLevelTotal'=>'Sq/Ft Lower Level',
'SqFtMainLevelTotal'=>'Sq/Ft Main Level',
'SqFtUpperLevelTotal'=>'Sq/Ft Upper Level',
'SqFtApximateManufacturing'=>'Approx Manufacturing Sq/Ft',
'SqFtApproximateWarehouse'=>'Approx Warehouse Sq/Ft',
'SaleIncludes'=>'Sale Includes',
'RoadFrontage'=>'Road Frontage',
'Stories'=>'Stories',
'Construction'=>'Construction',
'Utilities'=>'Utilities',
'SqFtApproximateGross'=>'Gross Sq/Ft',
'Features'=>'Features',
'Acres'=>'Acres',
'NumberOfLotsTotal'=>'Number of Lots',
'HOAYN'=>'HOA',
'PropertyCategory'=>'Property Category',
'AccessibilityFeatures'=>'Accesibility Features',
		);*/
	$k=0;
	foreach($fields as $row => $fld)
	{
		if($fld != 'property_id')
		{
		$data['master_field_name']	= $fld;
		$data['field_comment']		= $comment[$k];
		$data['created_date']		= date('Y-m-d h:i:s');
		$data['created_by']			= $this->superadmin_session['id'];
		$data['status']				= 1;
		//pr($data);
		$this->obj->insert_common('mls_type_of_mls_mapping_master',$data);
		}
		else
		{
			$data1['master_field_name']	= $fld;
			$data1['field_comment']		= $comment[$k];
			$data1['created_date']		= date('Y-m-d h:i:s');
			$data1['created_by']			= $this->superadmin_session['id'];
			$data1['status']				= 1;
		}
		$k++;
	}	
	$this->obj->insert_common('mls_type_of_mls_mapping_master',$data1);
	echo 'done';
}
/*
@Description: Function for insert master table
@Author: Niral Patel
@Input: - mls credentials
@Output: - map mls
@Date: 10-4-2015
*/
public function create_cron_url()
{
	$id = $this->uri->segment(4);
	$meeting_time = date('Y-m-d h:i:s');
    $datetime = date('Y-m-d H:i:s', strtotime('+6 hours', strtotime($meeting_time)));
    $datetime = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($datetime)));
    $minute   = date("i",strtotime($datetime)); 
    $hour     = date("H",strtotime($datetime));  
	$day 	  = date("d",strtotime($datetime)); 
	$month	  = date("m",strtotime($datetime)); 
	$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
	$url    = base_url().'superadmin/mls_import/import_database/'.$id.'/'.$cron_time;
	//$url    = base_url().'superadmin/mls_import/import_staging_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
	//$url1    = base_url().'superadmin/mls_import/import_master_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
	
     //echo $minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url;
    //echo '<br>';
    //echo $minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url1;exit;
    //exit;
    if($this->config->item('topsin_db_conditions'))
    {
	 	$output = shell_exec('crontab -l');
	    file_put_contents('../../../../tmp/cron.txt', $output.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url.''.PHP_EOL);
	    echo exec('crontab ../../../../tmp/cron.txt');
	    $newdata = array('msg'  => 'Cron set sucessfully.');
		$this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
	}
	else
	{
		echo base_url().'superadmin/mls_import/import_database/'.$id;
	}
}
	function insert_field()
	{
		$mfield=array(
			'HOARentIncludes' => 'field_GF20121128203245813130000000',
			'BREO' => 'field_LIST_27',
			'BTH' => 'field_LIST_67',
			'BR' => 'field_LIST_66',
			'BDI' => 'field_GF20121210031859306151000000',
			'NA' => 'field_LIST_112',
			'CIT' => 'field_LIST_39',
			'CMFE' => 'field_GF20121128203214100844000000',
			'COU' => 'field_LIST_41',
			'DD' => 'field_LIST_82',
			'EL' => 'field_LIST_85',
			'ExteriorFeatures' => 'field_GF20121126194243919925000000',
			'FLS' => 'field_GF20121126194243841543000000',
			'BTH' => 'field_LIST_68',
			'TSP' => 'field_LIST_124',
			'SH' => 'field_LIST_73',
			'VAI' => 'field_LIST_26',
			'IMP' => 'field_GF20121212011939214790000000',
			'FEA' => 'field_GF20121126194243819854000000',
			'LOC' => 'field_GF20121212011743686236000000',
			'LDE' => 'field_GF20121128194156807054000000',
			'LSZ' => 'field_LIST_56',
			'LSZS' => 'field_LIST_118',
			'MHF' => 'field_GF20121128190158529252000000',
			'MHM' => 'field_FEAT20121205014333222849000000',
			'MHS' => 'field_FEAT20121205014307024734000000',
			'MBD' => 'field_ROOM_MB_room_level',
			'JH' => 'field_LIST_32',
			'LN' => 'field_LIST_105',
			'OUT1' => 'field_GF20121212011913198640000000',
			'TAX' => 'field_LIST_80',
			'ParkingDescription' => 'field_GF20121128203155695117000000',
			'POL' => 'field_LIST_109',
			'POS' => 'field_GF20121128203750698912000000',
			'PriceCurrentForStatus' => 'field_LIST_22',
			'PublicRemarks' => 'field_LIST_78',
			'PTYP' => 'field_LIST_9',
			'RS2' => 'field_GF20121128203307544548000000',
			'RDI' => 'field_GF20121212012036130762000000',
			'RoofType' => 'field_GF20121126194243845568000000',
			'CLO' => 'field_LIST_12',
			'PARQ' => 'field_LIST_71',
			'STP' => 'field_GF20121212012200798860000000',
			'SP' => 'field_LIST_23',
			'ASF' => 'field_LIST_49',
			'SFS' => 'field_LIST_97',
			'ST' => 'field_LIST_15',
			'STY' => 'field_GF20121126194243751321000000',
			'TX' => 'field_LIST_75',
			'TXY' => 'field_LIST_76',
			'TMC' => 'field_GF20121126194243889344000000',
			'TAV' => 'field_LIST_26',
			'TEX' => 'field_FEAT20121228144034116911000000',
			'UNF' => 'field_GF20121128190049321246000000',
			'UFN' => 'field_LIST_123',
			'VEW' => 'field_GF20121128190237228652000000',
			'VIRT' => 'field_UNBRANDEDIDXVIRTUALTOUR',
			'WFG' => 'field_LIST_111',
			'WFT' => 'field_GF20121126194243720208000000',
			'YBT' => 'field_LIST_53',
			'YRE' => 'field_LIST_122',
			'ZIP' => 'field_LIST_43',
			'ZNC' => 'field_LIST_74',
			'Cooling' => 'field_GF20121128203359274284000000',
			'BusinessType' => 'field_LIST_91',
			'Heating' => 'field_GF20121126194243786373000000',
			'SuitableUse' => 'field_GF20121212011847002912000000',
			'SaleIncludes' => 'field_GF20121210032031601627000000',
			'Stories' => 'field_LIST_51',
			'Construction' => 'field_GF20121126194243835004000000',
			'Utilities' => 'field_GF20121126194244411580000000',
			'Acres' => 'field_LIST_57',
			'HOAYN' => 'field_LIST_120',
			'STY' => 'field_GF20121126194243751321000000',
			'STY1' => 'field_GF20121212011728862864000000',
			'WFT' => 'field_GF20121126194243720208000000',
			'WFT1' => 'field_GF20121206202841938447000000',
			'WFT2' => 'field_GF20121206202937679400000000',
			'WFT3' => 'field_GF20121206202432319685000000',
			'CHT' => 'field_GF20121210031948632516000000',
			'CHT1' => 'field_GF20121209033551300773000000',
			'Construction' => 'field_GF20121126194243835004000000',
			'Construction1' => 'field_GF20121206202935157741000000',
			'Construction2' => 'field_GF20121206202913690592000000',
			'Construction3' => 'field_GF20121206202429699010000000',
			'Cooling' => 'field_GF20121128203359274284000000',
			'Cooling1' => 'field_GF20121206202935069920000000',
			'Cooling2' => 'field_GF20121206202913609761000000',
			'Cooling3' => 'field_GF20121206202429621266000000',
			'Flooring' => 'field_GF20121206202935671233000000',
			'Flooring1' => 'field_GF20121206202914212504000000',
			'Flooring2' => 'field_GF20121206202430370061000000',
			'Heating' => 'field_GF20121126194243786373000000',
			'Heating1' => 'field_GF20121206202935801841000000',
			'Heating2' => 'field_GF20121206202914323008000000',
			'Heating3' => 'field_GF20121206202430480207000000',
			'LIC1' => 'field_GF20121209033813826417000000',
			'LOC' => 'field_GF20121212011743686236000000',
			'LOC1' => 'field_GF20121210032004030746000000',
			'LOC2' => 'field_GF20121209033014363719000000',
			'ParkingDescription' => 'field_GF20121128203155695117000000',
			'ParkingDescription1' => 'field_GF20121206202914938674000000',
			'POS' => 'field_GF20121128203750698912000000',
			'POS1' => 'field_GF20121206202936574401000000',
			'POS2' => 'field_GF20121206202915041661000000',
			'POS3' => 'field_GF20121206202431248446000000',
			'RF' => 'field_GF20121206202915149760000000',
			'RF1' => 'field_GF20121206202431346706000000',
			'SaleIncludes' => 'field_GF20121210032031601627000000',
			'SaleIncludes1' => 'field_GF20121209033138992260000000',
			'TMC' => 'field_GF20121126194243889344000000',
			'TMC1' => 'field_GF20121206202841653258000000',
			'TMC2' => 'field_GF20121206202915944331000000',
			'TMC3' => 'field_GF20121206202432075264000000',
			'Utilities' => 'field_GF20121126194244411580000000',
			'Utilities1' => 'field_GF20121206202937541928000000',
			'Utilities2' => 'field_GF20121206202916054421000000',
			'Utilities3' => 'field_GF20121206202432180006000000',
			'VEW' => 'field_GF20121128190237228652000000',
			'VEW1' => 'field_GF20121206202841857761000000',
			'VEW2' => 'field_GF20121206202432253492000000',
			'PublicRemarks' => 'field_LIST_78',
			'PublicRemarks1' => 'field_LIST_109',
			'RoofType' => 'field_GF20121126194243845568000000',
			'RoofType1' => 'field_GF20121206202936671897000000',
			'ExteriorFeatures' => 'field_GF20121126194243919925000000',
			'ExteriorFeatures1' => 'field_GF20121206202429990607000000',
			'RS2' => 'field_GF20121128203307544548000000',
			'RS21' => 'field_GF20121206202431535736000000',
			'SqftLiving' => 'field_LIST_48',
			'SqftGuestHouse' => 'field_LIST_50',
			'FrontExposure' => 'field_LIST_58',
			'Subdivision' => 'field_LIST_77',
			'HOPA' => 'field_LIST_96',
			'ModelName' => 'field_LIST_101',
			'PetsAllowed' => 'field_LIST_112',
			'ApplicationFee' => 'field_LIST_121',
			'DevelopmentName' => 'field_LIST_130',
			'BoatServices' => 'field_GF20121128203536428561000000',
			'EquestrianFeatures' => 'field_GF20140321152154756346000000',
			'Furnished' => 'field_GF20121126194243781619000000',
			'GuestHouse' => 'field_GF20121128203514053297000000',
			'UtilitiesonSite' => 'field_GF20121206202841774017000000',
			'ForLease' => 'field_LIST_112',
			'ForSale' => 'field_LIST_95',
			'TotalBuildingSqFt' => 'field_LIST_48',
			'Offices' => 'field_FEAT20121228141330591036000000',
			'Bays' => 'field_FEAT20121228141707970724000000',
			'LoadingDocks' => 'field_FEAT20121228141754532717000000',
			'SqFtIncluded' => 'field_LIST_48',
			'SqFtOccupied' => 'field_LIST_50',
			'Training' => 'field_LIST_110',
			'Road' => 'field_GF20121209033611927253000000',
			'TypeBuilding' => 'field_GF20121209033034262092000000',

					);

			foreach($mfield as $value=>$fld) 
				{
			$template['mls_id'] 		     	 = 4;
			$template['table_id'] 		     	 = 1;
			$template['mls_master_field']    	 = $value;
			$template['mls_field'] 			 	 = 'fl__rc_data.'.$fld;
			$template['mls_field_table'] 	 	 = '';
			$template['mls_transection_field'] 	 = '';

			$template['created_by'] 	 		 = $this->superadmin_session['id'];
			$template['created_date'] 	 	= date('Y-m-d h:i:s');
			pr($template);
			$this->obj->insert_mls_mapping_trans_record($template);		
			}exit;
	}
}
/*------ End File-------*/	
