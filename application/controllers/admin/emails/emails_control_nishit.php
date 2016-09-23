<?php 
/*
    @Description: Email campaign controller
    @Author: Sanjay Chabhadiya
    @Input: 
    @Output: 
    @Date: 06-08-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emails_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		
        check_admin_login();
		$this->load->model('phonecall_script_model');
		$this->load->model('marketing_library_masters_model');
		$this->load->model('user_management_model');
		$this->load->model('email_library_model');
		$this->load->model('contacts_model');
		$this->load->model('email_signature_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contact_conversations_trans_model');
		$this->load->model('imageupload_model');
		$this->obj = $this->email_campaign_master_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'admin';
    }
	

    /*
		@Description: Function for Get All Email campaign List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all Email campaign list
		@Date: 06-08-2014
    */

    public function index()
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$data['sortfield']		= 'id';
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
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		if(!empty($searchopt))
		{
			$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($perpage))
		{	
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
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
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/'."emails/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 3;
		$uri_segment = $this->uri->segment(3);
		
		$table ="email_campaign_master as ecm";   
		$fields = array('ecm.id','ecm.template_subject','ecm.is_draft,ecm.is_sent_to_all,ecm.email_send_date,ecm.email_send_time','etm.template_name');
		$join_tables = array('email_template_master as etm'=>'etm.id = ecm.template_name_id');
		
		$wherestring = 'ecm.email_type = "Campaign"';
		if(!empty($searchtext))
		{
			$match=array('ecm.template_subject'=>$searchtext,'etm.template_name'=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring));
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();exit;
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring));
		}
			
		$data['total_email'] = $this->obj->total_emails($this->admin_session['id']);
		//pr($data['datalist']);exit;
		
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_emails');
        $match = array('id'=>$admin_id);
		$data['udata'] = $this->admin_model->get_user($field, $match,'','=');
		/*echo $this->db->last_query();
		pr($data['udata']);exit;*/
		//$data['send_mail'] = $this->obj->total_emails($this->admin_session['id']);
		//pr($config);
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
			$this->load->view('admin/include/template',$data);
		}
    }

    /*
    @Description: Function Add New Email campaign details
    @Author: Sanjay Chabhadiya
    @Input: - 
    @Output: - Load Form for add Email campaign details
    @Date: 06-08-2014
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
		
		/////////////////////////////////////////////////////////////////////////////////////
		
		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		
		//pr($config);
		
		//pr($data['contact_list']);
		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();
		
		//pr($data['pagination']);

		/////////////////////////////////////////////////////////////////////////////////////
		$config1['per_page'] = '10';
		$config1['cur_page'] = '0';	
		$config1['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_cc");	
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config1['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_cc'] = $this->contact_type_master_model->select_records('','','','','',$config1['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config1['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		
		//pr($config1);
		
		
		//pr($data['contact_list_cc']);
		$this->pagination->initialize($config1);
		
		$data['pagination_cc'] = $this->pagination->create_links();
		
		//pr($data['pagination_cc']);
				

		
		////////////////////////////////////////////////////////////////////////////////////
		
		$config_bcc['per_page'] = '10';	
		$config_bcc['cur_page'] = '0';	
		$config_bcc['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_bcc");
        $config_bcc['is_ajax_paging'] = TRUE; // default FALSE
        $config_bcc['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config_bcc['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$data['contact_list_bcc'] = $this->contact_type_master_model->select_records('','','','','',$config_bcc['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config_bcc['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		
		//pr($config_bcc);
		//pr($data['contact_list_cc']);exit;
		
		$this->pagination->initialize($config_bcc);
		
		$data['pagination_bcc'] = $this->pagination->create_links();
		//pr($data['pagination_bcc']);exit;
		//echo "<pre>";print_r($this); exit;
		//pr($data1['pagination_bcc']);
		//exit;
		////////////////////////////////////////////////////////////////////////////////////
		
		
		$data['communication_plans'] = '';
		$match = array('is_subscribe'=>'0');
		$data['contact'] = $this->contacts_model->select_records('',$match,'','=');
		
		$match = array('created_by'=>$this->admin_session['id']);
		$data['email_signature_data'] = $this->email_signature_model->select_records('',$match,'','=');
		
		$data['tablefield_data']=$this->email_library_model->select_records3();
		//pr($data);exit;
		$data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }

    /*
		@Description: Function for Insert New Email campaign data
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign which is inserted into DB
		@Output: - List of email campaign with new inserted records
		@Date: 06-08-2014
    */
	
    public function insert_data()
    {
		//pr($_POST);exit;
		$submit = $this->input->post('submitbtn');
		if($submit == 'Save Campaign' || $submit == 'Send Now')
		{
			$data['template_name_id'] = $this->input->post('template_name');
			$data['template_category_id'] = $this->input->post('slt_category');
			$data['template_subcategory_id'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_signature'] = $this->input->post('email_signature');
			$data['email_send_type'] = $this->input->post('chk_is_lead');
			$send_type = $this->input->post('chk_is_lead');
			
			/*if($submit == 'Save Campaign')
				$data['is_draft'] = '0';*/
			
			if($submit == 'Send Now')
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';
			
			if($data['email_send_type'] == 2)
			{
				$data['email_send_date'] = $this->input->post('send_date');
				$data['email_send_time'] = $this->input->post('send_time');
				$data['is_draft'] = '0';
			}
			
			if(empty($data['email_send_type']) && $submit == 'Send Now')
			{
				$data['email_send_type'] = 1;
			}
			//echo $data['is_draft'];exit;;
				
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['email_type'] = 'Campaign';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			//$data['status'] = '1';
			if($submit == 'Send Now')
			{
				$data['is_sent_to_all'] = '1';
				$data['total_sent'] = 0;
			}
			//$email_campaign_id = 1;
			$email_campaign_id = $this->obj->insert_record($data);
			//if(!empty($_FILES))
			//for()
			
			/*$idata['email_campaign_id'] = $email_campaign_id;
			$idata['attachment_name'] = $file_name;
			$this->obj->insert_email_campaign_attachments($idata);*/
			
			/*if(!empty($_FILES['file_attachment']['name'][0]))
            {
				$name_array = array();
				$count = count($_FILES['file_attachment']['size']);
				foreach($_FILES as $key=>$value)
				for($s=0; $s<=$count-1; $s++) {
					$_FILES['file_attachment']['name']=$value['name'][$s];
					$_FILES['file_attachment']['type']    = $value['type'][$s];
					$_FILES['file_attachment']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['file_attachment']['error']       = $value['error'][$s];
					$_FILES['file_attachment']['size']    = $value['size'][$s];  
					$config['upload_path'] = 'uploads/attachment_file/';
					$config['allowed_types'] = '*';
					$this->load->library('upload', $config);
					if($this->upload->do_upload('file_attachment'))
					{
						$datac = $this->upload->data();
						$idata['email_campaign_id'] = $email_campaign_id;
						$idata['attachment_name'] = $datac['file_name'];
						$this->obj->insert_email_campaign_attachments($idata);
					}
					else
					{
						echo $this->upload->display_errors();
						exit;
					}
				}
			}*/
			$f = $this->input->post('fileName');
			if(!empty($f))
				$files = explode(",",$f);
			//pr($files);exit;
			if(!empty($files))
			{
				for($i=0;$i<count($files);$i++)
				{
					$bgImgPath = $this->config->item('attachment_basepath_file');
					$random = substr(md5(rand()),0,7);
					$bgTempPath = $this->config->item('attachment_temp');
					$file_name =  pathinfo($bgTempPath.$files[$i]);
					$file_name = $random.".".$file_name['extension'];
					//$file_name = $files[$i];
					$this->imageupload_model->copyfile($bgImgPath,$files[$i],$file_name);
					$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
					 
					if(file_exists($bgTempPath.$files[$i]))
					{
						$idata['email_campaign_id'] = $email_campaign_id;
						 $idata['attachment_name'] = $file_name;
						$this->obj->insert_email_campaign_attachments($idata);
						@unlink($bgTempPath.$files[$i]);
					}
					
				}
			}
			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = array();
			$contact_id = array();
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$contact_id[$j] = $contact[$i];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			
			$cdata['email_campaign_id'] = $email_campaign_id;
			//$cdata['template_subject'] = $data['template_subject'];
			//$cdata['email_message'] = $data['email_message'];
			
			$cdata['recepient_cc'] = $this->input->post('email_cc');
			$cdata['recepient_bcc'] = $this->input->post('email_bcc');
			$from_data = '';
			if($submit == 'Send Now')
				$cdata['sent_date'] = date('Y-m-d H:i:s');
			$j=0;
			/*if(!empty($contact_type) && $submit != 'Send Now')
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
					
					$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							//$cdata['contact_id'] = $row['id'];
							$contact_id1[$j] = $row['id'];
							$j++;
							//$this->obj->insert_email_campaign_recepient_trans($cdata);
						}
						
					}
					$this->insert_data_trans($contact_id1,$data,$cdata);
					$contact_id1 = '';
				}
			}*/
			if((!empty($contact_id) && $submit == 'Save Campaign') || ($submit == 'Send Now' && $data['email_send_type'] != 1))
			{
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			if($submit == 'Send Now' && $data['email_send_type'] != 2)
			{
				$send_mail_count = 0;
				$from_cc = '';
				$from_bcc = '';
				$from = "nishit.modi@tops-int.com";
				if($this->input->post('email_cc'))
				{
					$email_cc_data = explode(",",$this->input->post('email_cc'));
					$j=0;
					$k=0;
					$email_data_cc = '';
					$contact_type_cc = '';
					for($i=0;$i<count($email_cc_data);$i++)
					{
						if(!stristr($email_cc_data[$i],'CT-'))
						{
							$email_data_cc .= $email_cc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_cc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
						
					}
					if(!empty($contact_type_cc))
					{
						for($i=0;$i<count($contact_type_cc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_cc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_cc .= $row['contact_id'].",";
								}
							}
						}
					}
			
					$email_cc = explode(",",trim($email_data_cc,","));
					$from_cc_data = $this->obj->email_in_query($email_cc);
					foreach($from_cc_data as $row)
					{
						$from_cc .= $row['email_address'].",";
					}
					
					$from_cc = trim($from_cc,",");
					$headers .= "CC:".$from_cc."\r\n";
				}
				
				if($this->input->post('email_bcc'))
				{
					$email_bcc_data = explode(",",$this->input->post('email_bcc'));
					$j=0;
					$k=0;
					$email_data_bcc = '';
					$contact_type_bcc = '';
					for($i=0;$i<count($email_bcc_data);$i++)
					{
						if(!stristr($email_bcc_data[$i],'CT-'))
						{
							$email_data_bcc .= $email_bcc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_bcc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
					}
					if(!empty($contact_type_bcc))
					{
						for($i=0;$i<count($contact_type_bcc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_bcc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_bcc .= $row['contact_id'].",";
								}
							}
						}
					}

					$email_bcc = explode(",",trim($email_data_bcc,","));
					$from_bcc_data = $this->obj->email_in_query($email_bcc);
					foreach($from_bcc_data as $row)
					{
						$from_bcc .= $row['email_address'].",";
					}
					$from_bcc = trim($from_bcc,","); 
					$headers .= "BCC:".$from_bcc."\r\n";
				}
				//$subject = $data['template_subject'];
				$headers .= 'MIME-Version: 1.0'."\r\n";
				$email_signature = '';
				if(!empty($email_campaign_id))
				{
					$attachment = $this->obj->select_email_campaign_attachments($email_campaign_id);
					if(!empty($data['email_signature']))
					{
						$match = array('id'=>$data['email_signature']);
						$email_signature = $this->email_signature_model->select_records('',$match,'','=');
					}
				}
				$message = !empty($data['email_message'])?$data['email_message']:'';
				if(!empty($email_signature))
					$message .= $email_signature[0]['full_signature'];
				if($data['is_unsubscribe'] == 1){
					$link = base_url()."unsubscribe/unsubscribe_link";
					$message .= '<a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
				}
				
				if(isset($attachment) && !empty($attachment))
					$headers .= $this->mailAttachmentHeader($attachment,$message);
				else
					$headers .= $this->mailAttachmentHeader('',$message);
					
				if(!empty($contact_id))		
				{
					$email_send_data = $this->send_mail($contact_id,$headers,$data,$cdata,$from,$send_mail_count,$email_signature);
					//$flag = $email_send_data['send_mail_count'];
					$send_mail_count = $email_send_data['send_mail_count'];
				}
			}
			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = '';
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
					
					$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['id'];
							$j++;
							
						}
						if($submit == 'Send Now' && $send_type == 1)
						{
							$email_send_data = $this->send_mail($contact_id1,$headers,$data,$cdata,$from,$send_mail_count,$email_signature);
							/*if(!empty($email_send_data))
								$flag = $email_send_data;
							if($flag == 2)
							break;*/
							$send_mail_count = $email_send_data['send_mail_count'];
								
							
						}
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
		}
		elseif($submit == 'Save Template As')
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->email_library_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_send_type'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$this->email_library_model->insert_record($data);
		
		}
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
		
    }
 	
	/*
		@Description: Function for insert email campaign trans
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign,contact details
		@Output: - 
		@Date: 06-08-2014
   */
   
	public function insert_data_trans($contact_id='',$data,$cdata='')
	{
		$from_data = $this->obj->in_query_data($contact_id);
		//pr($cdata);exit;
		if(count($from_data) > 0)
		{
			foreach($from_data as $row)
			{
				$cdata['contact_id'] = $row['id'];
				$emaildata = array(
									'first name'=>$row['first_name'],
									'last name'=>$row['last_name'],
									'company name'=>$row['company_name'],
									'customer name'=>'',
									'product name'=>'',
									'manufacturer'=>'',
									'supplier name'=>'',
									'brand'=>'',
									'title'=>'',
									'city'=>'',
									'address'=>'',
									'contact type'=>'');
				
				$content = $data['email_message'];
				$title = $data['template_subject'];
				//pr($emaildata);
				$cdata['template_subject'] = $title;
				$cdata['email_message'] = $content;
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
					
					$cdata['template_subject'] = $finaltitle;
					$finlaOutput = $output;
					$cdata['email_message'] = $finlaOutput;
				}
				//$from_data .= $contact_id[$i].",";
				$this->obj->insert_email_campaign_recepient_trans($cdata);
			}
		}
	}
	
	/*
		@Description: Function for send email
		@Author: Sanjay Chabhadiya
		@Input: - Details of email campaign,contact details and send email count
		@Output: - Send email and insert the data in email campaign trans
		@Date: 06-08-2014
   */
	
 	public function send_mail($contact_id='',$headers='',$data='',$cdata='',$from='',$send_mail_count='',$email_signature='')
	{
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_emails');
        $match = array('id'=>$admin_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		$email_data['flag'] = 1;
		$email_data['send_mail_count'] = $send_mail_count;
		if(count($udata) > 0)
		{
			$remain_emails = $udata[0]['remain_emails'];
			if($remain_emails == 0)
			{
				$email_data['flag'] = 2;
				/*$datac['is_sent_to_all'] = '0';
				$datac['total_sent'] = $send_mail_count;
				$datac['id'] = $cdata['email_campaign_id'];
				$this->obj->update_record($datac);				
				return $email_data;*/
			}
		}
		$fields = '';
		$join_tables = '';
		$where_in = '';
		//$send_mail_count = 0;
		if(!empty($contact_id))
		{
			$message = '';
			$table ="contact_master as cm";   
			$fields = array('cm.*,cet.email_address');
			$join_tables = array('contact_emails_trans cet'=>'cet.contact_id = cm.id');
			
			$group_by = 'cet.contact_id';
			$wherestring = "cet.is_default = '1' AND cm.is_subscribe = '0'";
			//$match = array('ecm.id'=>$email_campaign_id);
			$where_in = array('cm.id'=>$contact_id);
			$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			
			if(count($from_data) > 0)
			{
				
				//$send_mail_count = 0;
				foreach($from_data as $row)
				{
					//echo $remain_emails;
					if($remain_emails == 0)
					{
						$email_data['flag'] = 2;
						$datac['is_sent_to_all'] = '0';
						$datac['total_sent'] = $send_mail_count;
						$datac['id'] = $cdata['email_campaign_id'];
						$this->obj->update_record($datac);
						//echo $this->db->last_query();
						//exit;
					}
					//exit;
					$cdata['contact_id'] = $row['id'];
					$emaildata = array(
										'first name'=>$row['first_name'],
										'last name'=>$row['last_name'],
										'company name'=>$row['company_name'],
										'customer name'=>'',
										'product name'=>'',
										'manufacturer'=>'',
										'supplier name'=>'',
										'brand'=>'',
										'title'=>'',
										'city'=>'',
										'address'=>'',
										'contact type'=>'');
					$content1 = $data['email_message']; 
					$content = $headers;
					$title = $data['template_subject'];
					//pr($emaildata);
					$cdata['template_subject'] = $title;
					$output = $content;
					//echo "Header : ".$headers."<br>";
					$cdata['email_message'] = $content1;
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
						$output1 = strtr($content1, $map);
						
						$cdata['template_subject'] = $finaltitle;
						$finlaOutput = $output1;
						$cdata['email_message'] = $finlaOutput;
						$mail_data = $output;
					}
					//$from_data .= $contact_id[$i].",";
					$subject = $cdata['template_subject'];
					$message = $output;
					if($remain_emails != 0){
						if(!empty($row['email_address']))
						{
							$to = $row['email_address'];
							mail($to,$subject,'',$message,"-f".$from);
						}
					}
					if($remain_emails == 0)
						$cdata['is_send'] = '0';
					else
					{
						$cdata['is_send'] = '1';
						$cdata['sent_date'] = date('Y-m-d H:i:s');
						$remain_emails--;
						$send_mail_count++;
												
						$contact_conversation['contact_id'] = $row['id'];
						$contact_conversation['log_type'] = 6;
						$contact_conversation['campaign_id'] = !empty($cdata['email_campaign_id'])?$cdata['email_campaign_id']:'';
						$contact_conversation['email_camp_template_id'] = !empty($data['template_name_id'])?$data['template_name_id']:'';
						if(!empty($data['template_name_id']))
						{
							$match = array('id'=>$data['template_name_id']);
							$template_data = $this->email_library_model->select_records('',$match,'','=');
							//	pr($template_data);
							if(count($template_data) > 0)
							{
								$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
							}
						}
						
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
						
					}
					//exit;
					$this->obj->insert_email_campaign_recepient_trans($cdata);
				}
			}
		}
		$idata['id'] = $this->admin_session['id'];
		$email_data['send_mail_count'] = $send_mail_count;
		if(isset($remain_emails))
			$idata['remain_emails'] = $remain_emails;
		
		$udata = $this->admin_model->update_user($idata);
		return $email_data;
	}
 
    /*
		@Description: Get Details of Edit email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Id of email campaign whose details want to change
		@Output: - Details of email campaign which id is selected for update
		@Date: 06-08-2014
    */
 
    public function edit_record()
    {
     	$id = $this->uri->segment(4);
		$cdata['send_now'] = $this->uri->segment(5);
		
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		
		$match = array("parent"=>'0');
        $cdata['category'] = $this->marketing_library_masters_model->select_records1('',$match,'','=','','','','id','desc','marketing_master_lib__category_master');
		
		$match = array('is_subscribe'=>'0');
		$cdata['contact'] = $this->contacts_model->select_records('',$match,'','=');
		
		$match = array('created_by'=>$this->admin_session['id']);
		$cdata['email_signature_data'] = $this->email_signature_model->select_records('',$match,'','=');
		
		$email_campaign_id = $result[0]['id'];
		$email = $this->obj->select_email_campaign_recepient_trans($email_campaign_id);
		
		$cdata['attachment'] = $this->obj->select_email_campaign_attachments($id);
		
		$i=0;
		$contact_type_to = '';
		foreach($email as $row)
		{
			if(empty($row['contact_type']) && $row['contact_type'] == 0)
				$email_to[$i] = $row['contact_id'];
			else
			{
				if(!in_array($row['contact_type'],$contact_type_to))
					$contact_type_to[$i] = $row['contact_type'];
			}
			if($i==0)
			{
				$email_cc = $row['recepient_cc'];
				$email_bcc = $row['recepient_bcc'];
			}
			$i++;
		}
		if(!empty($email_to))
			$cdata['email_to'] = $this->obj->in_query($email_to);
		if(!empty($contact_type_to))
		{
			$cdata['contact_type_to'] = $this->contact_type_master_model->contact_type_in_query($contact_type_to);
			$i=0;
			foreach($cdata['contact_type_to'] as $row)
			{
				$contact_type_to_selected[$i] = $row['id'];
				$i++;
			}
			if(!empty($contact_type_to_selected))
				$cdata['contact_type_to_selected'] = implode(",",$contact_type_to_selected);
		}
		if(!empty($email_cc))
		{
			$email_cc_data = explode(",",$email_cc);
			$email_cc = '';
			$contact_type_cc = '';
			for($i=0;$i<count($email_cc_data);$i++)
			{
				if(stristr($email_cc_data[$i],'CT-'))
					$contact_type_cc[$i] = substr($email_cc_data[$i],3);
				else
					$email_cc[$i] = $email_cc_data[$i];
			}
			if(!empty($email_cc))
				$cdata['email_cc'] = $this->obj->in_query($email_cc);
			if(!empty($contact_type_cc))
			{
				$cdata['contact_type_cc'] = $this->contact_type_master_model->contact_type_in_query($contact_type_cc);	
				$i=0;
				foreach($cdata['contact_type_cc'] as $row)
				{
					$contact_type_cc_selected[$i] = $row['id'];
					$i++;
				}
				$cdata['contact_type_cc_selected'] = implode(",",$contact_type_cc_selected);
			}
		}
		if(!empty($email_bcc))
		{
			$email_bcc_data = explode(",",$email_bcc);
			$email_bcc = '';
			$contact_type_bcc = '';
			for($i=0;$i<count($email_bcc_data);$i++)
			{
				if(stristr($email_bcc_data[$i],'CT-'))
					$contact_type_bcc[$i] = substr($email_bcc_data[$i],3);
				else
					$email_bcc[$i] = $email_bcc_data[$i];
			}
			if(!empty($email_bcc))
				$cdata['email_bcc'] = $this->obj->in_query($email_bcc);
			if(!empty($contact_type_bcc))
			{
				$cdata['contact_type_bcc'] = $this->contact_type_master_model->contact_type_in_query($contact_type_bcc);	
				$i=0;
				foreach($cdata['contact_type_bcc'] as $row)
				{
					$contact_type_bcc_selected[$i] = $row['id'];
					$i++;
				}
				$cdata['contact_type_bcc_selected'] = implode(",",$contact_type_bcc_selected);
			}
		}
		/////////////////////////////////////////////////////////////////////////////////////
		
		$config['per_page'] = '10';
		$config['cur_page'] = '0';
		$config['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list'] = $this->contact_type_master_model->select_records('','','','','',$config['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config);
		$cdata['pagination'] = $this->pagination->create_links();

		/////////////////////////////////////////////////////////////////////////////////////
		$config1['per_page'] = '10';
		$config1['cur_page'] = '0';	
		$config1['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_cc");	
        $config1['is_ajax_paging'] = TRUE; // default FALSE
        $config1['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config1['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list_cc'] = $this->contact_type_master_model->select_records('','','','','',$config1['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config1['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config1);
		$cdata['pagination_cc'] = $this->pagination->create_links();
		
		////////////////////////////////////////////////////////////////////////////////////
		$config_bcc['per_page'] = '10';	
		$config_bcc['cur_page'] = '0';	
		$config_bcc['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_bcc");
        $config_bcc['is_ajax_paging'] = TRUE; // default FALSE
        $config_bcc['paging_function'] = 'ajax_paging'; // Your jQuery paging
		//$config_bcc['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(4);
		
		$cdata['contact_list_bcc'] = $this->contact_type_master_model->select_records('','','','','',$config_bcc['per_page'],'','id','desc');
		//echo $this->db->last_query();
		$config_bcc['total_rows'] = count($this->contact_type_master_model->select_records('','','','','','','','id','desc'));
		$this->pagination->initialize($config_bcc);	
		$cdata['pagination_bcc'] = $this->pagination->create_links();
		
		$cdata['tablefield_data']=$this->email_library_model->select_records3();
		$cdata['main_content'] = "admin/".$this->viewName."/add";       
		$this->load->view("admin/include/template",$cdata);
		
    }

    /*
		@Description: Function for Update email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Update details of email campaign
		@Output: - List with updated email campaign details
		@Date: 06-08-2014
    */
   
    public function update_data()
    {
		//pr($_POST);exit;
		$id = $this->input->post('id');
		$submit = $this->input->post('submitbtn');
		if($submit == 'Save Campaign' || $submit = 'Send Now')
		{
			$result = $this->obj->delete_email_campaign_recepient_trans($id);
			//echo $result;exit;
			$data['id'] = $id;	
			$data['template_name_id'] = $this->input->post('template_name');
			$data['template_category_id'] = $this->input->post('slt_category');
			$data['template_subcategory_id'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_signature'] = $this->input->post('email_signature');
			$data['email_send_type'] = $this->input->post('chk_is_lead');
			$send_type = $this->input->post('chk_is_lead');
			
			if($submit == 'Send Now')
				$data['is_draft'] = '0';
			else
				$data['is_draft'] = '1';
				
			if($data['email_send_type'] == 2)
			{
				$data['email_send_date'] = $this->input->post('send_date');
				$data['email_send_time'] = $this->input->post('send_time');
				$data['is_draft'] = '0';
			}
			else
			{
				$data['email_send_date'] = '';
				$data['email_send_time'] = '';
			}
			
			if(empty($data['email_send_type']) && $submit == 'Send Now')
			{
				$data['email_send_type'] = 1;
			}
				
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['email_type'] = 'Campaign';
			$data['modified_by'] = $this->admin_session['id'];
			$data['modified_date'] = date('Y-m-d H:i:s');		
			if($submit == 'Send Now')
			{
				$data['is_sent_to_all'] = '1';
				$data['total_sent'] = 0;
			}
			
			$this->obj->update_record($data);
			
			/*if(!empty($_FILES['file_attachment']['name'][0]))
            {
				$name_array = array();
				$count = count($_FILES['file_attachment']['size']);
				foreach($_FILES as $key=>$value)
				for($s=0; $s<=$count-1; $s++) {
					$_FILES['file_attachment']['name']=$value['name'][$s];
					$_FILES['file_attachment']['type']    = $value['type'][$s];
					$_FILES['file_attachment']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['file_attachment']['error']       = $value['error'][$s];
					$_FILES['file_attachment']['size']    = $value['size'][$s];  
					$config['upload_path'] = 'uploads/attachment_file/';
					$config['allowed_types'] = '*';
					//$random = substr(md5(rand()),0,7);
					//$config['file_name'] = $random;
					//$config['encrypt_name']  = TRUE;
					$this->load->library('upload', $config);
					//$this->upload->do_upload('file_attachment');
					if($this->upload->do_upload('file_attachment'))
					{
						$datac = $this->upload->data();
						$idata['email_campaign_id'] = $id;
						$idata['attachment_name'] = $datac['file_name'];
						$this->obj->insert_email_campaign_attachments($idata);
					}
					else
					{
						echo $this->upload->display_errors();
						exit;
					}
				}
			}*/
			$f =$this->input->post('fileName');
			if(!empty($f))
				$files = explode(",",$f);
			//pr($files);exit;
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
						$idata['email_campaign_id'] = $id;
						$idata['attachment_name'] = $file_name;
						$this->obj->insert_email_campaign_attachments($idata);
						@unlink($bgTempPath.$files[$i]);
					}
					
				}
			}
			$cdata['email_campaign_id'] = $id;
			$cdata['template_subject'] = $data['template_subject'];
			$cdata['email_message'] = $data['email_message'];
			
			if($submit == 'Send Now' && $data['email_send_type'] != 2)
				$cdata['sent_date'] = date('Y-m-d H:i:s');

			$contact = explode(",",$this->input->post('email_to'));
			$j=0;
			$k=0;
			$contact_type = '';
			$from_data = '';
			for($i=0;$i<count($contact);$i++)
			{
				if(!stristr($contact[$i],'CT-'))
				{
					$contact_id[$j] = $contact[$i];
					$j++;
				}
				else
				{
					$contact_type[$k] = substr($contact[$i],3);
					$k++;
				}
			}
			
			$cdata['recepient_cc'] = $this->input->post('email_cc');
			$cdata['recepient_bcc'] = $this->input->post('email_bcc');
			
			////////////////////////////////////////////////////////////////////////////////////////////////
			if((!empty($contact_id) && $submit == 'Save Campaign') || ($submit == 'Send Now' && $data['email_send_type'] != 1))
			{
				$this->insert_data_trans($contact_id,$data,$cdata);
			}
			
			////////////////////////////////////////////////////////////////////////////////////////////////
			
			if($submit == 'Send Now' && !empty($id) && $data['email_send_type'] != 2)
			{
				$send_mail_count = 0;
				$from_cc = '';
				$from_bcc = '';
				/*$table ="email_campaign_master as ecm";   
				$fields = array('cet.email_address,ecr.*,esm.full_signature');
				$join_tables = array('email_campaign_recepient_trans ecr'=>'ecr.email_campaign_id = ecm.id','contact_master as cm'=>'cm.id = ecr.contact_id','contact_emails_trans cet'=>'cet.contact_id = ecr.contact_id','email_signature_master esm'=>'esm.id = ecm.email_signature');
				
				$group_by = 'cet.contact_id';
				$wherestring = "ecm.id = ".$id." AND cet.is_default = '1' AND cm.is_subscribe = '0'";
				$from_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring);*/
				$from = "nishit.modi@tops-int.com"; 
				if($this->input->post('email_cc'))
				{
					$email_cc_data = explode(",",$this->input->post('email_cc'));
					$j=0;
					$k=0;
					$email_data_cc = '';
					$contact_type_cc = '';
					for($i=0;$i<count($email_cc_data);$i++)
					{
						if(!stristr($email_cc_data[$i],'CT-'))
						{
							$email_data_cc .= $email_cc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_cc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
						
					}
					if(!empty($contact_type_cc))
					{
						for($i=0;$i<count($contact_type_cc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_cc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_cc .= $row['contact_id'].",";
								}
							}
						}
					}
			
					$email_cc = explode(",",trim($email_data_cc,","));
					$from_cc_data = $this->obj->email_in_query($email_cc);
					foreach($from_cc_data as $row)
					{
						$from_cc .= $row['email_address'].",";
					}
					
					$from_cc = trim($from_cc,",");
					$headers .= "CC:".$from_cc."\r\n";
				}
				
				if($this->input->post('email_bcc'))
				{
					$email_bcc_data = explode(",",$this->input->post('email_bcc'));
					$j=0;
					$k=0;
					$email_data_bcc = '';
					$contact_type_bcc = '';
					for($i=0;$i<count($email_bcc_data);$i++)
					{
						if(!stristr($email_bcc_data[$i],'CT-'))
						{
							$email_data_bcc .= $email_bcc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_bcc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
					}
					if(!empty($contact_type_bcc))
					{
						for($i=0;$i<count($contact_type_bcc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_bcc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_bcc .= $row['contact_id'].",";
								}
							}
						}
					}

					$email_bcc = explode(",",trim($email_data_bcc,","));
					$from_bcc_data = $this->obj->email_in_query($email_bcc);
					foreach($from_bcc_data as $row)
					{
						$from_bcc .= $row['email_address'].",";
					}
					$from_bcc = trim($from_bcc,","); 
					$headers .= "BCC:".$from_bcc."\r\n";
				}
				//$subject = $data['template_subject'];
				//$message = $data['email_message'];
				$email_signature = '';
				$headers .= 'MIME-Version: 1.0'."\r\n";
				if(!empty($id))
				{
					$attachment = $this->obj->select_email_campaign_attachments($id);
					if(!empty($data['email_signature']))
					{
						$match = array('id'=>$data['email_signature']);
						$email_signature = $this->email_signature_model->select_records('',$match,'','=');
					}
				}
				$message = !empty($data['email_message'])?$data['email_message']:'';
				if(!empty($email_signature))
					$message .= $email_signature[0]['full_signature'];
				
				if($data['is_unsubscribe'] == 1){
					$link = base_url()."unsubscribe/unsubscribe_link";
					$message .= '<a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
				}
				
				if(isset($attachment) && !empty($attachment))
					$headers .= $this->mailAttachmentHeader($attachment,$message);
				else
					$headers .= $this->mailAttachmentHeader('',$message);
				
				/*if(isset($attachment) && !empty($attachment))
					$headers .= $this->mailAttachmentHeader($attachment,$message);
				else
					$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";*/
					
				if(!empty($contact_id))		
				{
					$email_send_data = $this->send_mail($contact_id,$headers,$data,$cdata,$from,$send_mail_count,$email_signature);
					//$flag = $email_send_data['send_mail_count'];
					$send_mail_count = $email_send_data['send_mail_count'];
				}
			}	

			$contact_id = '';
			if(!empty($contact_type))
			{
				for($i=0;$i<count($contact_type);$i++)
				{
					$j=0;
					$contact_id1 = '';	
					$contact_type_data = '';
					$cdata['contact_type'] = $contact_type[$i];
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
					
					$match = array('cct.contact_type_id'=>$contact_type[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$contact_id1[$j] = $row['id'];
							$j++;
							
						}
						if($submit == 'Send Now' && $send_type == 1)
						{
							$email_send_data = $this->send_mail($contact_id1,$headers,$data,$cdata,$from,$send_mail_count,$email_signature);
							$send_mail_count = $email_send_data['send_mail_count'];
							/*if(!empty($email_send_data))
								$flag = $email_send_data['flag'];
							if($flag == 2)
								break;*/
						}
						else
							$this->insert_data_trans($contact_id1,$data,$cdata);
					}
				}
			}
		}
		elseif($submit == 'Save Template As')
		{
			if($this->input->post('template_name'))
			{
				$match = array('id'=>$this->input->post('template_name'));
				$result = $this->email_library_model->select_records('',$match,'','=');
				if(count($result) > 0)
					$data['template_name'] = $result[0]['template_name'];
			}
			if($this->input->post('is_unsubscribe'))
				$data['is_unsubscribe'] = $this->input->post('is_unsubscribe');
			else
				$data['is_unsubscribe'] = '0';
			$data['template_category'] = $this->input->post('slt_category');
			$data['template_subcategory'] = $this->input->post('slt_subcategory');
			$data['template_subject'] = $this->input->post('txt_template_subject');
			$data['email_message'] = $this->input->post('email_message');
			$data['email_send_type'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');		
			$data['status'] = '1';
			$this->email_library_model->insert_record($data);
		}
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
		redirect(base_url('admin/'.$this->viewName));
		
    }
	
  	/*
		@Description: Function for Delete email campaign
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which email campaign record want to delete
		@Output: - New email campaign list after record is deleted.
		@Date: 06-08-2014
    */

    function delete_record()
    {
        $id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$this->obj->delete_email_campaign_recepient_trans($id);
		$this->obj->delete_email_campaign_attachments($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('admin/'.$this->viewName);
    }
	
	/*
		@Description: Function for email campaign delete
		@Author: Sanjay Chabhadiya
		@Input: - Delete all id of email campaign record want to delete
		@Output: - Delete selected all record
		@Date: 06-08-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id'); 
		//exit;
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->obj->delete_email_campaign_recepient_trans($id);
			$this->obj->delete_email_campaign_attachments($id);
			unset($id);
		}
		$array_data=$this->input->post('myarray');
		for($i=0;$i<count($array_data);$i++)
		{
			$this->obj->delete_record($array_data[$i]);
			$this->obj->delete_email_campaign_recepient_trans($array_data[$i]);
			$this->obj->delete_email_campaign_attachments($array_data[$i]);
		}
		echo 1;
	}
	
	/*
		@Description: Function for Unpublish Task Profile By Admin
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to Unpublish
		@Output: - New Task list after record is Unpublish.
		@Date: 06-08-2014
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
		redirect('admin/'.$this->viewName);
    }
	
	/*
		@Description: Function for publish Task Profile By Admin
		@Author: Sanjay Chabhadiya
		@Input: - Delete id which Task record want to publish
		@Output: - New Task list after record is publish.
		@Date: 06-08-2014
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
		redirect('admin/'.$this->viewName);
    }
	
	/*
		@Description: Function for subcategory from category id
		@Author: Sanjay Chabhadiya
		@Input: - Parent category Id
		@Output: - Subcategory list
		@Date: 06-08-2014
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
	
		
	}
	
	/*
		@Description: Function for template data from category id and subcategory id wise
		@Author: Sanjay Chabhadiya
		@Input: - Parent category id and chiled category id
		@Output: - Template List
		@Date: 06-08-2014
   */
   
	public function ajax_templatedata()
	{
		$category_id=$this->input->post('loadcategoryId');
		$subcategory_id=$this->input->post('loadsubcategoryId');
		
		if(!empty($category_id) && !empty($subcategory_id))
		{
			$match = array("template_category"=>$category_id,"template_subcategory"=>$subcategory_id);
        	$cdata['templatedata'] = $this->email_library_model->select_records('',$match,'','=','','','','id','desc');
			echo json_encode($cdata['templatedata']);
		}
	}
	
	/*
		@Description: Function for Mailattachment
		@Author: Sanjay Chabhadiya
		@Input: - Attachment List
		@Output: - 
		@Date: 06-08-2014
   	*/
	
	public function mailAttachmentHeader($attachment,$message)
	{
		$mime_boundary = md5(time());
	
		$xMessage = "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"\r\n\r\n";
		
		//$xMessage .= "--".$mime_boundary."\r\n\r\n";
		
		//$xMessage .= "This is a multi-part message in MIME format.\r\n";
		$xMessage .= "--".$mime_boundary."\r\n";
		
		//$xMessage .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		
		//$xMessage .= "Content-Transfer-Encoding: 7bit\r\n";
		
		//$xMessage .= $message."\r\n\r\n";
		
		$xMessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$xMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$xMessage .= $message."\r\n\r\n";
		if(!empty($attachment))
		{
			foreach($attachment as $file)
			{
				$xMessage .= "--".$mime_boundary."\r\n";
				
				$xMessage .= "Content-Type: application/octet-stream; name=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$xMessage .= "Content-Transfer-Encoding: base64\r\n";
				
				$xMessage .= "Content-Disposition: attachment; filename=\"".basename("uploads/attachment_file/".$file['attachment_name'])."\"\r\n";
				
				$content = file_get_contents("uploads/attachment_file/".$file['attachment_name']);
				
				$xMessage.= chunk_split(base64_encode($content));
				
				$xMessage .= "\r\n\r\n";
			
			}
		}
		//pr($attachment);exit;
		$xMessage .= "--".$mime_boundary."--\r\n\r\n";
		
		return $xMessage;
	
	}
	
	/*
		@Description: Function for sent SMS list from email campaign id wise
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - Sent email List 
		@Date: 06-08-2014
   	*/
	
	public function sent_email()
	{
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		$data['editRecord'] = $result;
		//pr($data['editRecord']); exit;
		$data['campaign_id'] = $id ;
		$datalist = '';
		
		if(count($result) > 0)
		{
			$email_campaign_id = $result[0]['id'];
			$total_cnt = count($this->obj->select_email_campaign_recepient_trans($email_campaign_id));
			$match = array('is_send'=>'1');
			$sent_cnt = count($this->obj->select_email_campaign_recepient_trans($email_campaign_id,$match));
			//echo $this->db->last_query();
			$data['not_send'] = $total_cnt - $sent_cnt;
			//pr($data['not_send']);exit;
			/*$match = array('is_send'=>'1');	
			$email = $this->obj->select_email_campaign_recepient_trans($email_campaign_id,$match);
			$i=0;
			$contact_type_to = '';
			foreach($email as $row)
			{
				$datalist[$i] = $row['contact_id'];
				if($i==0)
				{
					$email_cc = $row['recepient_cc'];
					$email_bcc = $row['recepient_bcc'];
					$data['datetime'] = $row['sent_date'];
				}
				$i++;
			}*/
			
		}	
		
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$data['sortfield']		= 'cm.id';
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
			$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		}
		if(!empty($perpage))
		{
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/'."emails/sent_email/".$id);
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 5;
		$uri_segment = $this->uri->segment(5);
		//echo trim($datalist,","); exit;
		/*$join_table = array('email_campaign_recepient_trans ecm'=>'ecm.contact_id = cm.id');
		$match = array('ecm.email_campaign_id'=>$id);*/
		$table ="contact_master as cm";
		$fields = array('cm.*,ect.id as ID,ect.is_send,ect.template_subject,ect.sent_date');
		$join_tables = array('email_campaign_recepient_trans as ect'=>'ect.contact_id = cm.id');
		$wherestring = "ect.email_campaign_id = ".$email_campaign_id; //." AND is_send = '1'";
		if(!empty($searchtext))
		{	
			$match = array("CONCAT_WS(' ',cm.first_name,cm.last_name)"=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,'','','',$wherestring);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','','','','',$wherestring));
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,'','','',$wherestring);
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring));
			//pr($data['datalist']);exit;
			//echo $this->db->last_query();exit;
			/*$data['datalist'] = $this->obj->in_query_data($datalist,$config['per_page'],$uri_segment,'cm.id','desc','',$join_table,$match,'left');
			//pr($data['datalist']);exit;
			
			$config['total_rows'] = count($this->obj->in_query_data($datalist,'','','','','',$join_table,$match,'left'));*/
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_sent_email',$data);
		}
		else
		{	
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/sent_email_list";
			$this->load->view('admin/include/template',$data);
		}
		
	}
	
	/*
		@Description: Function for sent email view data
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id and contact id
		@Output: - Sent email view data 
		@Date: 06-08-2014
   	*/
	
	public function view_data()
	{
		$data['campaign_id'] = $this->uri->segment(4);
		$data['id'] = $this->uri->segment(5);
		
		$table = "email_campaign_master ecm";
		$fields = array("ecm.email_message,ecm.template_subject,GROUP_CONCAT(DISTINCT ect.attachment_name
SEPARATOR ',') as attachment_name,cm.first_name,cm.last_name,mml1.category as category,mml2.category as subcategory,etm.template_name,ecr.*,esm.full_signature");
		$join_tables = array('email_campaign_recepient_trans ecr'=>'ecm.id = ecr.email_campaign_id',
							'email_signature_master esm'=>'esm.id = ecm.email_signature',
							'contact_master cm'=>'ecr.contact_id = cm.id',
							'email_campaign_attachments ect'=>'ect.email_campaign_id = ecm.id',
							'marketing_master_lib__category_master mml1'=>'mml1.id = ecm.template_category_id',
							'marketing_master_lib__category_master mml2'=>'mml2.id = ecm.template_subcategory_id',
							'email_template_master etm'=>'etm.id = ecm.template_name_id');

		$wherestring = 'ecr.email_campaign_id = '.$data['campaign_id'].' AND ecr.contact_id = '.$data['id'];
		//$match = array('ecr.email_campaign_id'=>$data['campaign_id'],'ecr.contact_id'=>$data['id']);
		$cdata['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
		/*pr($cdata['datalist']);
		echo $this->db->last_query();exit;*/
		$email_cc = '';
		if(count($cdata['datalist']) > 0)
		{
			$email_cc = $cdata['datalist'][0]['recepient_cc'];
			$email_bcc = $cdata['datalist'][0]['recepient_bcc'];
		}
		if(!empty($email_cc))
		{
			$datalist = '';
			$email_cc_data = explode(",",$email_cc);
			$email_cc = '';
			$contact_type_cc = '';
			for($i=0;$i<count($email_cc_data);$i++)
			{
				if(stristr($email_cc_data[$i],'CT-'))
					$contact_type_cc[$i] = substr($email_cc_data[$i],3);
				else
					$datalist[$i] = $email_cc_data[$i];
			}
			if(!empty($datalist))
			{
				$result = $this->obj->contact_in_query($datalist);
				foreach($result as $row)
				{
					$email_cc .= $row['first_name']." ".$row['last_name']."; ";
				}
			}
			if(!empty($contact_type_cc))
			{
				for($i=0;$i<count($contact_type_cc);$i++)
				{
					$contact_type_data = '';
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','contact_emails_trans cet'=>'cet.contact_id = cm.id');
					$group_by = 'cet.contact_id';
					$match = array('cct.contact_type_id'=>$contact_type_cc[$i],'cet.is_default'=>'1');
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','','','','',$group_by);
					//pr($contact_type_data); exit;
					//echo $this->db->last_query();exit;
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$email_cc .= $row['first_name']." ".$row['last_name']."; ";
							//$this->obj->insert_email_campaign_recepient_trans($cdata);
						}
					}
				}
			}
		}
		
		if(!empty($email_bcc))
		{
			$email_bcc_data = explode(",",$email_bcc);
			$email_bcc = '';
			$datalist = '';
			$contact_type_bcc = '';
			for($i=0;$i<count($email_bcc_data);$i++)
			{
				if(stristr($email_bcc_data[$i],'CT-'))
					$contact_type_bcc[$i] = substr($email_bcc_data[$i],3);
				else
					$datalist[$i] = $email_bcc_data[$i];
			}
			
			if(!empty($datalist))
			{
				$result = $this->obj->contact_in_query($datalist);
				foreach($result as $row)
				{
					$email_bcc .= $row['first_name']." ".$row['last_name']."; ";
				}
			}
			
			if(!empty($contact_type_bcc))
			{
				for($i=0;$i<count($contact_type_bcc);$i++)
				{
					$contact_type_data = '';
					$table ="contact_contacttype_trans as cct";   
					$fields = array('cm.*');
					$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id','contact_emails_trans cet'=>'cet.contact_id = cm.id');
					
					$group_by = 'cet.contact_id';
					$match = array('cct.contact_type_id'=>$contact_type_bcc[$i]);
					$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','','','','',$group_by);
					//pr($contact_type_data); exit;
					//echo $this->db->last_query();exit;
					if(count($contact_type_data) > 0){
						foreach($contact_type_data as $row)
						{
							$email_bcc .= $row['first_name']." ".$row['last_name']."; ";;
							//$this->obj->insert_email_campaign_recepient_trans($cdata);
						}
					}
				}
			}
			
		}
		
		$cdata ['email_cc'] = $email_cc;
		$cdata ['email_bcc'] = $email_bcc;
		//pr($cdata['datalist']);
		$cdata['main_content'] =  $this->user_type.'/'.$this->viewName."/view_data";
		$this->load->view('admin/include/template',$cdata);
	}
	
	/*
		@Description: Function for search contact type
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   	*/
	
	public function search_contact_ajax()
    {
		//echo 'Meet';exit;
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		//echo $this->db->last_query(); exit;	
		$config['total_rows'] = count($this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc'));
		
		
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax", $data);
	}
	
	/*
		@Description: Function for search contact type in CC
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   	*/
	
	public function search_contact_ajax_cc()
    {
		//echo 'Meet';exit;
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_cc");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list_cc'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		//echo $this->db->last_query(); exit;	
		$config['total_rows'] = count($this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc'));
		
		$this->pagination->initialize($config);		
		$data['pagination_cc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax_cc", $data);
	}

	/*
		@Description: Function for search contact type in BCC
		@Author: Sanjay Chabhadiya
		@Input: - text
		@Output: - Contact type list
		@Date: 06-08-2014
   */

	public function search_contact_ajax_bcc()
    {
		//echo 'Meet';exit;
		$config['per_page'] = 10;	
		$config['base_url'] = site_url($this->user_type.'/'."emails/search_contact_ajax_bcc");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$searchtext = $this->input->post('searchtext');
	
		$match=array('name'=>$searchtext);
		
		$data['contact_list_bcc'] = $this->contact_type_master_model->select_records('',$match,'','like','',$config['per_page'],$uri_segment,'id','desc');
		//echo $this->db->last_query(); exit;	
		$config['total_rows'] = count($this->contact_type_master_model->select_records('',$match,'','like','','','','id','desc'));
		
		$this->pagination->initialize($config);		
		$data['pagination_bcc'] = $this->pagination->create_links();
        $this->load->view("admin/".$this->viewName."/add_contact_popup_ajax_bcc", $data);
	}
	
	public function add_contacts_to_email()
	{
		$contacts_type = $this->input->post('contacts_type');
		//pr($contacts_type); exit;
		$data['contacts_data'] = $this->contact_type_master_model->contact_type_in_query($contacts_type);
		//echo $this->db->last_query();
		echo json_encode($data['contacts_data']);
		//$this->load->view($this->user_type.'/'.$this->viewName."/selected_contact_ajax",$data);
	}	
	
	/*
		@Description: Function for Email Attachment delete
		@Author: Sanjay Chabhadiya
		@Input: -  email attachment id
		@Output: - 
		@Date: 06-08-2014
   	*/
   
	public function ajax_delete_attachment()
	{
		$id = $this->input->post('single_remove_id');
		$attachment_name = $this->input->post('attachment_name');
		$this->obj->delete_attachment($id);
		$bgTempPath = $this->config->item('upload_image_file_path').'attachment_file/';
		if(file_exists($bgTempPath.$attachment_name))
		{ 
			@unlink($bgTempPath.$attachment_name);
		}
		echo 1;
	}
	
	
	/*
<<<<<<< .mine
		@Description: Function for Email Attachment delete
		@Author: Sanjay Chabhadiya
		@Input: -  email attachment id
		@Output: - 
		@Date: 06-08-2014
   */
   
	public function delete_attachment()
	{
		$attachment_name = $this->input->post('file_name');
		$bgTempPath = $this->config->item('upload_image_file_path').'attachment_temp/';
		if(file_exists($bgTempPath.$attachment_name))
		{ 
			@unlink($bgTempPath.$attachment_name);
		}
		echo $attachment_name;
	}
	
	/*
=======
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
>>>>>>> .r657
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
			$data['attachment'] = $this->obj->select_email_campaign_attachments($email_campaign_id);
		if(count($data['attachment']) > 0)
		 	$this->load->view("admin/".$this->viewName."/attachmentlist", $data);
	}

	/*
		@Description: Function for sms template data
		@Author: Sanjay Chabhadiya
		@Input: - Template Id
		@Output: - Template details
		@Date: 06-08-2014
   	*/
   
	public function ajax_templatename()
	{
		$template_id = $this->input->post('template_id');
		if(!empty($template_id))
		{
			$match = array("id"=>$template_id);
        	$cdata['templatedata'] = $this->email_library_model->select_records('',$match,'','=','','','','id','desc');
			if(count($cdata['templatedata']) > 0)
			{
				echo json_encode($cdata['templatedata']);
			}
		}	
	}
	
	/*
		@Description: Function for Copy email campaign
		@Author: Sanjay Chabhadiya
		@Input: - email campaign id
		@Output: - all email campaign list
		@Date: 06-08-2014
    */
	
	public function copy_record()
    {
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		if(count($result) > 0)
		{
			$data['template_name_id'] = $result[0]['template_name_id'];
			$data['template_category_id'] = $result[0]['template_category_id'];
			$data['template_subcategory_id'] = $result[0]['template_subcategory_id'];
			$data['template_subject'] = $result[0]['template_subject']."-copy";
			$data['email_message'] = $result[0]['email_message'];
			$data['email_signature'] = $result[0]['email_signature'];
			$data['email_send_type'] = $result[0]['email_send_type'];
			
			$data['is_draft'] = '1';
			$data['created_by'] = $this->admin_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			$this->obj->insert_record($data);
		}
		$this->session->set_userdata('message_session', $newdata);	
		redirect('admin/'.$this->viewName);
	}
	
	/*
		@Description: Function for download file
		@Author: Sanjay Chabhadiya
		@Input: - File name
		@Output: - 
		@Date: 06-08-2014
    */
	
	public function download_form()
	{
		$file = $this->uri->segment(4);
		$filename = base_url()."uploads/attachment_file/".$file;	
		$file_directory = $this->config->item('base_path')."/uploads/attachment_file"; //Name of the directory where all the sub directories and files exists
		//$file =  "example.docx";//Get the file from URL variable
		$file_array = explode('/', $file); //Try to seperate the folders and filename from the path
		$file_array_count = count($file_array); //Count the result
		$filename = $file_array[$file_array_count-1]; //Trace the filename
		$file_path = $file_directory.'/'.$file; //Set the file path w.r.t the download.php... It may be different for u
		if(file_exists($file_path)){
			header("Content-disposition: attachment; filename={$filename}"); //Tell the filename to the browser
			header('Content-type: application/octet-stream'); //Stream as a binary file! So it would force browser to download
			readfile($file_path); //Read and stream the file
		}
		else {
			echo "Sorry, the file does not exist!";
		}
	}
	
	/*
		@Description: Function for resend email single or multiple
		@Author: Sanjay Chabhadiya
		@Input: - Email campaign id or email campaign transaction id
		@Output: - 
		@Date: 06-08-2014
   */
	
	public function resend_mail()
	{
		$id[0] = $this->input->post('single_id');
		$campaign_id = $this->uri->segment(4);
		$page = $this->uri->segment(5);
		$flag = 0;
		if(!empty($id[0]))
			$contact_id = $id;
		else
		{
			$match = array('is_send'=>'0');
			$result = $this->obj->select_email_campaign_recepient_trans($campaign_id,$match);
			//echo $this->db->last_query();
			$i=0;
			$flag = 1;
			foreach($result as $row)
			{
				$contact_id[$i] = $row['id'];
				$i++;
				
			}
		}
		$admin_id = $this->admin_session['id'];
		$field = array('id','remain_emails');
        $match = array('id'=>$admin_id);
        $udata = $this->admin_model->get_user($field, $match,'','=');
		$email_data['flag'] = 1;
		$email_data['send_mail_count'] = $send_mail_count;
		if(count($udata) > 0)
		{
			$remain_emails = $udata[0]['remain_emails'];
			if($remain_emails == 0)
			{
				$email_data['flag'] = 2;
			}
		}
		$fields = '';
		$join_tables = '';
		//$where_in = '';
		//$remain_emails = 5;
		//$send_mail_count = 0;
		if(!empty($contact_id))
		{
			$table ="email_campaign_recepient_trans as ecr";
			$fields = array('cet.email_address,ecr.id as ID,ecr.email_campaign_id,ecr.template_subject,ecr.contact_id,ecr.recepient_cc,ecr.recepient_bcc,ecr.contact_id,cm.*');
			$join_tables = array('contact_emails_trans cet'=>'cet.contact_id = ecr.contact_id','contact_master cm'=>'cm.id = ecr.contact_id');
			
			$group_by = 'cet.contact_id';
			$wherestring = "cet.is_default = '1' AND ecr.is_send = '0' AND ecr.email_campaign_id = ".$campaign_id;
			//$match = array('ecm.id'=>$email_campaign_id);
			$where_in = array('ecr.id'=>$contact_id);
			$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$wherestring,$where_in);
			/*echo $this->db->last_query();
			pr($datalist);exit;*/
			/*$table ="email_campaign_attachments as ect";
			$fields = array('ect.*');
			$join_tables = array('contact_master as cm'=>'cm.id = ect.contact_id','contact_emails_trans cet'=>'cet.contact_id = cm.id');
			$group_by = 'cet.contact_id';
			$match = array('cct.contact_type_id'=>$contact_type_cc[$i],'cet.is_default'=>'1');
			$datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=','','','','',$group_by);*/
			
			//$datalist = $this->obj->email_campaign_trans_in_query($id);
			
			
			/*echo $this->db->last_query();
			pr($datalist);exit;*/
			$match = array('id'=>$campaign_id);
			$campaign_data = $this->obj->select_records('',$match,'','=');
			if(count($datalist) > 0)
			{
				$from_cc = '';
				$from_bcc = '';
				$from = "nishit.modi@tops-int.com";
				if(!empty($datalist[0]['recepient_cc']))
				{
					$email_cc_data = explode(",",$datalist[0]['recepient_cc']);
					$j=0;
					$k=0;
					$email_data_cc = '';
					$contact_type_cc = '';
					for($i=0;$i<count($email_cc_data);$i++)
					{
						if(!stristr($email_cc_data[$i],'CT-'))
						{
							$email_data_cc .= $email_cc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_cc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
						
					}
					if(!empty($contact_type_cc))
					{
						for($i=0;$i<count($contact_type_cc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_cc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_cc .= $row['contact_id'].",";
								}
							}
						}
					}
			
					$email_cc = explode(",",trim($email_data_cc,","));
					$from_cc_data = $this->obj->email_in_query($email_cc);
					foreach($from_cc_data as $row)
					{
						$from_cc .= $row['email_address'].",";
					}
					
					$from_cc = trim($from_cc,",");
					$headers .= "CC:".$from_cc."\r\n";
				}
				if(!empty($datalist[0]['recepient_bcc']))
				{
					$email_bcc_data = explode(",",$datalist[0]['recepient_bcc']);
					$j=0;
					$k=0;
					$email_data_bcc = '';
					$contact_type_bcc = '';
					for($i=0;$i<count($email_bcc_data);$i++)
					{
						if(!stristr($email_bcc_data[$i],'CT-'))
						{
							$email_data_bcc .= $email_bcc_data[$i].",";
							$j++;
						}
						else
						{
							$contact_type_bcc[$k] = substr($email_cc_data[$i],3);
							$k++;
						}
					}
					if(!empty($contact_type_bcc))
					{
						for($i=0;$i<count($contact_type_bcc);$i++)
						{
							$contact_type_data = '';
							$table ="contact_contacttype_trans as cct";   
							$fields = array('cct.*');
							$join_tables = array('contact_master as cm'=>'cm.id = cct.contact_id');
							
							$match = array('cct.contact_type_id'=>$contact_type_bcc[$i]);
							$contact_type_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'',$match,'','=');
							if(count($contact_type_data) > 0){
								foreach($contact_type_data as $row)
								{
									$email_data_bcc .= $row['contact_id'].",";
								}
							}
						}
					}

					$email_bcc = explode(",",trim($email_data_bcc,","));
					$from_bcc_data = $this->obj->email_in_query($email_bcc);
					foreach($from_bcc_data as $row)
					{
						$from_bcc .= $row['email_address'].",";
					}
					$from_bcc = trim($from_bcc,","); 
					$headers .= "BCC:".$from_bcc."\r\n";
				}
				if(!empty($datalist[0]['email_campaign_id']))
				{
					$attachment = $this->obj->select_email_campaign_attachments($datalist[0]['email_campaign_id']);
				}
			}
			$message = '';
			if(count($campaign_data) > 0)
			{
				if(!empty($campaign_data[0]['email_signature']))
				{
					$match = array('id'=>$campaign_data[0]['email_signature']);
					$email_signature = $this->email_signature_model->select_records('',$match,'','=');
				}
				$send_mail_count = !empty($campaign_data[0]['total_sent'])?$campaign_data[0]['total_sent']:'';
				$datac['id'] = !empty($campaign_data[0]['id'])?$campaign_data[0]['id']:'';
				
				$message = !empty($campaign_data[0]['email_message'])?$campaign_data[0]['email_message']:'';
				if(!empty($email_signature))
					$message .= $email_signature[0]['full_signature'];
			}
			
			if($campaign_data[0]['is_unsubscribe'] == '1'){
				$link = base_url()."unsubscribe/unsubscribe_link";
				$message .= '<a href="'.$link.'" target="_blank"> Click here to unsubscribe </a>';
			}
			
			if(isset($attachment) && !empty($attachment)){
				$headers .= $this->mailAttachmentHeader($attachment,$message);
			}
			else
				$headers .= $this->mailAttachmentHeader('',$message);//'Content-type: text/html; charset=iso-8859-1'."\r\n";*/
				
			foreach($datalist as $row)
			{
				$cdata['id'] = $row['ID'];
				if($remain_emails == 0)
				{
					break;
				}
				$emaildata = array(
						'first name'=>$row['first_name'],
						'last name'=>$row['last_name'],
						'company name'=>$row['company_name'],
						'customer name'=>'',
						'product name'=>'',
						'manufacturer'=>'',
						'supplier name'=>'',
						'brand'=>'',
						'title'=>'',
						'city'=>'',
						'address'=>'',
						'contact type'=>'');
				$content = $headers;
				$title = $row['template_subject'];
				$output = $content;
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
				}
				$subject = $row['template_subject'];
				$message = $output;
				if($remain_emails != 0){
					if(!empty($row['email_address']))
					{
						if(!empty($row['email_address']))
						{
							$to = $row['email_address'];
							mail($to,$subject,'',$message,"-f".$from);
						}
					}
				}
				if($remain_emails == 0)
					$cdata['is_send'] = '0';
				else
				{
					$cdata['sent_date'] = date('Y-m-d H:i:s');
					$cdata['is_send'] = '1';
					$remain_emails--;
					$send_mail_count++;

					if(!empty($campaign_data))
					{
						$contact_conversation['contact_id'] = $row['contact_id'];
						$contact_conversation['log_type'] = 6;
						$contact_conversation['campaign_id'] = $campaign_data[0]['id'];
						$contact_conversation['email_camp_template_id'] = $campaign_data[0]['template_name_id'];
						
						if(!empty($campaign_data[0]['template_name_id']))
						{
							$match = array('id'=>$campaign_data[0]['template_name_id']);
							$template_data = $this->email_library_model->select_records('',$match,'','=');
							if(count($template_data) > 0)
							{
								$contact_conversation['email_camp_template_name'] = $template_data[0]['template_name'];
							}
						}
						
						$contact_conversation['created_date'] = date('Y-m-d H:i:s');
						$contact_conversation['created_by'] = $this->admin_session['id'];
						$contact_conversation['status'] = '1';
						$this->contact_conversations_trans_model->insert_record($contact_conversation);
					}
				}
				$this->obj->update_email_campaign_trans($cdata);
			}
		}
		//echo $send_mail_count++; exit;
		$match = array('is_send'=>'1');
		$sent_cnt = count($this->obj->select_email_campaign_recepient_trans($campaign_id));
		if($sent_cnt == $send_mail_count)
		{
			$datac['is_draft'] = '0';
			$datac['is_sent_to_all'] = '1';
			$datac['total_sent'] = 0;
		}
		else
		{
			//$email_data['flag'] = 2;
			$datac['is_sent_to_all'] = '0';
			$datac['total_sent'] = $send_mail_count;
		}
		$datac['id'] = $campaign_id;
		$this->obj->update_record($datac);
		//echo $this->db->last_query();
		$idata['id'] = $this->admin_session['id'];
		$email_data['send_mail_count'] = $send_mail_count;
		if(isset($remain_emails))
			$idata['remain_emails'] = $remain_emails;
		$udata = $this->admin_model->update_user($idata);
		//echo $this->db->last_query();
		
		if($flag == 1)
			redirect('admin/'.$this->viewName);
		else
			echo "/".$campaign_id."/".$page;
	}
	
	/*
		@Description: Function for Get All sent Email List
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all sent Email list
		@Date: 30-08-2014
    */
	
	public function all_sent_mail()
	{	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$data['sortfield']		= 'ecr.id';
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
			$sortfield = 'ecr.id';
			$sortby = 'desc';
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
		if(!empty($perpage))
		{	
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
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
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/sms/'."all_sent_mail/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="email_campaign_master as ecm";   
		$fields = array('ecr.template_subject','etm.template_name','cm.first_name,cm.last_name','ecr.sent_date');
		$join_tables = array('email_campaign_recepient_trans as ecr'=>'ecr.email_campaign_id = ecm.id',
							 'contact_master as cm'=>'cm.id = ecr.contact_id',
							 'email_template_master as etm'=>'etm.id = ecm.template_name_id'
							 );
							 
		$wherestring = "ecm.is_draft = '0' AND ecr.is_send = '1'";
		
		if(!empty($searchtext))
		{
			$concat = "CONCAT_WS(' ',cm.first_name,cm.last_name)";
			$match=array('ecr.template_subject'=>$searchtext,'etm.template_name'=>$searchtext,$concat=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring));
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			/*echo $this->db->last_query();
			pr($data['datalist']);exit;*/
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring));
		}
				
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];

		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_all_sent_maillist',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/all_sent_maillist";
			$this->load->view('admin/include/template',$data);
		}
    	
	}
	
	/*
		@Description: Function for Get All queued Email
		@Author: Sanjay Chabhadiya
		@Input: - Search value or null
		@Output: - all queued Email
		@Date: 30-08-2014
    */
	
	public function queued_list()
	{
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = $this->input->post('perpage');
		$data['sortfield']		= 'ecr.id';
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
			$sortfield = 'ecr.id';
			$sortby = 'desc';
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
		if(!empty($perpage))
		{	
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
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
			$perpage = $this->input->post('perpage');
			$data['perpage'] = $perpage;
			$config['per_page'] = $perpage;	
		}
		else
		{
        	$config['per_page'] = '10';
		}
		$config['base_url'] = site_url($this->user_type.'/emails/'."queued_list/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
		$config['uri_segment'] = 4;
		$uri_segment = $this->uri->segment(4);
		
		$table ="email_campaign_master as ecm";
		$fields = array('ecr.template_subject','cm.first_name,cm.last_name','ecm.email_send_date');
		$join_tables = array('email_campaign_recepient_trans as ecr'=>'ecr.email_campaign_id = ecm.id',
							 'contact_master as cm'=>'cm.id = ecr.contact_id',
							 );
							 
		$wherestring = "ecm.email_send_type = 2 AND ecm.email_send_date >= ".date('Y-m-d');
		
		if(!empty($searchtext))
		{
			$concat = "CONCAT_WS(' ',cm.first_name,cm.last_name)";
			$match=array('ecr.template_subject'=>$searchtext,$concat=>$searchtext);
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			//echo $this->db->last_query();
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like','','',$sortfield,$sortby,'',$wherestring));
				
		}
		else
		{
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$wherestring);
			/*echo $this->db->last_query();
			pr($data['datalist']);exit;*/
			$config['total_rows'] = count($this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','',$sortfield,$sortby,'',$wherestring));
		}
				
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		if($this->input->post('result_type') == 'ajax')
		{
			$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_queued_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/queued_list";
			$this->load->view('admin/include/template',$data);
		}	
	}
	
}
