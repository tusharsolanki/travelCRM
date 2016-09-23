<?php 
/*
	@Description: Admin Management controller
	@Author: Mohit Trivedi
	@Input: 
	@Output: 
	@Date: 01-09-2014
	
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class package_management_control extends CI_Controller
{	
    function __construct()
    {
	    parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
		$this->message_session = $this->session->userdata('message_session');
	    check_superadmin_login();
		$this->load->model('admin_model');
		$this->load->model('package_management_model');
		$this->load->model('common_function_model');
   	    $this->obj = $this->package_management_model;
	    $this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'superadmin';

    }
	
    /*
    @Description: Function for Get All Admin List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Admin list
    @Date: 01-09-2014
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
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('package_management_sortsearchpage_data');
		}
		$searchsort_session = $this->session->userdata('package_management_sortsearchpage_data');
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
/*					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];*/
					$searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
	     			$data['searchtext'] = $searchsort_session['searchtext'];
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
		
		$config['base_url'] = site_url($this->user_type.'/'."package_management/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
		
		if(!empty($searchtext))
		{
			$match=array('package_name'=>$searchtext,'email_counter'=>$searchtext,'sms_counter'=>$searchtext,'contacts_counter'=>$searchtext);
			$data['datalist'] = $this->obj->select_records('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,'');
			$config['total_rows'] = $this->obj->select_records('',$match,'','like','','','','','','1');
		}
		else
		{
			$data['datalist'] = $this->obj->select_records('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
			$config['total_rows']= $this->obj->select_records('','','','','','','','','','1');
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		$sortsearchpage_data = array(
				'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
				'sortby' 	 => !empty($data['sortby'])?$data['sortby']:'',
				'searchtext' => !empty($data['searchtext'])?$data['searchtext']:'',
				'perpage' 	 => !empty($data['perpage'])?trim($data['perpage']):'',
				'uri_segment' => !empty($uri_segment)?$uri_segment:'',
				'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'');
		$this->session->set_userdata('package_management_sortsearchpage_data', $sortsearchpage_data);
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
    @Description: Function Add New Admin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Admin details
    @Date: 01-09-2014
    */
   
    public function add_record()
    {
		$data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }

   
   /*
    @Description: Function Copy Admin details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for copy Admin details
    @Date: 01-09-2014
    */
   
    public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->get_user('',$match,'','=');
		$cdata['admin_name'] = $result[0]['admin_name'].'-copy';
		$cdata['email_id'] = $result[0]['email_id'];
		$cdata['password'] = $result[0]['password'];
		$cdata['db_name']=$result[0]['db_name'];
		$cdata['host_name']=$result[0]['host_name'];
		$cdata['db_user_name']=$result[0]['db_user_name'];
		$cdata['db_user_password']=$result[0]['db_user_password'];
		$cdata['user_type'] = '2';
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$lastId=$this->obj->insert_user($cdata);
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
	
	}
  
    /*
    @Description: Function for Insert New Admin data
    @Author: Mohit Trivedi
    @Input: - Details of new Admin which is inserted into DB
    @Output: - List of Admin with new inserted records
    @Date: 01-09-2014
    */
   
    public function insert_data()
    {
		$cdata['package_name'] = $this->input->post('package_name');
		$cdata['email_counter'] = $this->input->post('email_counter');
		$cdata['sms_counter'] = $this->input->post('sms_counter');
		$cdata['contacts_counter']=$this->input->post('contacts_counter');
		
		$cdata['created_by'] = $this->superadmin_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$lastId = $this->obj->insert_record($cdata);
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('superadmin/'.$this->viewName);
		
     }
 
    /*
    @Description: Get Details of Edit Admin Profile
    @Author: Mohit Trivedi
    @Input: - Id of Admin member whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 01-09-2014
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		$cdata['main_content'] = "superadmin/".$this->viewName."/add";       
		$this->load->view("superadmin/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Admin Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Admin
    @Output: - List with updated Admin details
    @Date: 01-09-2014
    */
   
    public function update_data()
    {
		
	    $cdata['id'] = $this->input->post('id');
		$cdata['package_name'] = $this->input->post('package_name');
		$cdata['email_counter'] = $this->input->post('email_counter');
		$cdata['sms_counter'] = $this->input->post('sms_counter');
		$cdata['contacts_counter']=$this->input->post('contacts_counter');

		$cdata['modified_by'] = $this->superadmin_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$data  = $this->obj->select_user_package_trans($cdata['id']);
		$searchsort_session = $this->session->userdata('package_management_sortsearchpage_data');
		$pagingid = $searchsort_session['uri_segment'];
		if(count($data)){
		?>
			<script type="text/javascript">
				alert('Package are already assign for the admin. It can not be edited.');
				window.location.href = '<?=base_url().'superadmin/'.$this->viewName.'/'.$pagingid;?>';
			</script>
	<?php exit; }
			$this->obj->update_record($cdata);
			$msg = $this->lang->line('common_edit_success_msg');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			$superadmin_id = $this->input->post('id');
			//$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
			redirect(base_url('superadmin/'.$this->viewName.'/'.$pagingid));
		
    }
	
   /*
    @Description: Function for Delete Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to delete
    @Output: - New Admin list after record is deleted.
    @Date: 01-09-2014
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
    @Description: Function for Delete superadmin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete all id of superadmin record want to delete
    @Output: - superadmin post list Empty after record is deleted.
    @Date: 30-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$val = 1;
		if(!empty($id))
		{
			$data  = $this->obj->select_user_package_trans($id);
			if(count($data) > 0)
			{
				$val = 2;
				unset($id);
			}
			else
			{
				$this->obj->delete_record($id);
				unset($id);
			}
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$data  = $this->obj->select_user_package_trans($array_data[$i]);
				if(count($data) == 0)
				{
					$this->obj->delete_record($array_data[$i]);
				}
				unset($data);
			}
		}
		
		$searchsort_session = $this->session->userdata('package_management_sortsearchpage_data');
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
		$data['pagingid'] = $pagingid;
		$data['val'] = $val;
		echo json_encode($data);
	}
	
	 /*
    @Description: Function for Unpublish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to Unpublish
    @Output: - New Admin list after record is Unpublish.
    @Date: 01-09-2014
    */

    function unpublish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = 'Deactive';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		$superadmin_id = $id;
		$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
		echo $pagingid;
//		redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	
	/*
    @Description: Function for publish Admin Profile By Superadmin
    @Author: Mohit Trivedi
    @Input: - Delete id which Admin record want to publish
    @Output: - New Admin post list after record is publish.
    @Date: 01-09-2014
    */

	function publish_record()
    {
        $id = $this->uri->segment(4);
		$cdata['id'] = $id;
		$cdata['status'] = 'Active';
		$this->obj->update_record($cdata);
		$msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		$superadmin_id = $id;
		$pagingid = $this->obj->getsuperadminpagingid($superadmin_id);
		echo $pagingid;
		//redirect('superadmin/'.$this->viewName.'/'.$pagingid);
    }
	/*
    @Description: Function for check Admin already exist
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - 
    @Date: 01-09-2014
    */

	public function assign_package()
	{
		$data['main_content'] = "superadmin/".$this->viewName."/assign_package";
        $this->load->view('superadmin/include/template', $data);	
	}
}