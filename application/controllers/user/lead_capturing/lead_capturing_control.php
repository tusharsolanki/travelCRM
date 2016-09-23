<?php 
/*
    @Description: Lead Capturing controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 13-09-2014
	
*/
?>
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lead_capturing_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
		$this->db_session = $this->session->userdata('db_session');
        check_user_login();
		$this->load->model('lead_capturing_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_masters_model');
                $this->load->model('contacts_model');
		$this->load->model('user_management_model');
		$this->obj = $this->lead_capturing_model;
		$this->obj1= $this->user_management_model;
		$this->viewName = $this->router->uri->segments[2];
		$this->user_type = 'user';
    }
	
	
	/*
		@Description: Function for Module All details view.
		@Author: Sanjay Chabhadiya
		@Input: - 
		@Output: - 
		@Date: 22-12-2014
    */
	
	public function lead_capturing_home()
	{
		//check user right
		check_rights('form_builder');
		$data['main_content'] = 'user/'.$this->viewName."/home";
		$this->load->view('user/include/template',$data);	
	}

    /*
    @Description: Function for Get All Lead Capturing List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Lead Capturing list
    @Date: 13-09-2014
    */

    public function index()
    {	
		//check user right
		check_rights('form_builder');
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
                $allflag = $this->input->post('allflag');

                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('lead_capturing_sortsearchpage_data');
                }
                $searchsort_session = $this->session->userdata('lead_capturing_sortsearchpage_data');
		$data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		
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
		$config['base_url'] = site_url($this->user_type.'/'."lead_capturing/");
                $config['is_ajax_paging'] = TRUE;
                $config['paging_function'] = 'ajax_paging';
		//$config['uri_segment'] = 3;
		//$uri_segment = $this->uri->segment(3);
                if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 3;
                    $uri_segment = $this->uri->segment(3);
                }
		
		$table = "lead_master as smt";
		$fields = array('smt.*','smtp.plan_name');
		$join_tables = array(
							'interaction_plan_master as smtp' => 'smt.assigned_interaction_plan_id = smtp.id',
							);
		
		$group_by='smt.id';
		$status_value='1';
		$where1 = 'smt.status = "'.$status_value.'" AND smt.created_by IN ('.$this->user_session['agent_id'].')';
		if(!empty($searchtext))
		{
			
			$match=array('smt.form_title'=>$searchtext,'smtp.plan_name'=>$searchtext);	
			$data['datalist'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left',$match,'','like',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'like','','','','',$group_by,$where1,'1');
		}
		
		else
		{
			$data['datalist'] =$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'],$uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where1);
			$config['total_rows'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where1,'1');
			}
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
                
                $lead_capturing_sortsearchpage_data = array(
					'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
					'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
					'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
					'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
					'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
					'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
					
                $this->session->set_userdata('lead_capturing_sortsearchpage_data', $lead_capturing_sortsearchpage_data);
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
    @Description: Function Add New Lead Capturing details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function add_record()
    {
		//check user right
		check_rights('form_builder_add');
		//$match = array('status'=>1,'created_by'=>$this->user_session['id']);
		//$result = $this->interaction_plans_model->select_records('',$match,'','=');
		$where = 'status = "1" AND created_by IN ('.$this->user_session['agent_id'].')';
		$result = $this->obj->getmultiple_tables_records('interaction_plan_master','','','','','','','','','','','',$where);
		$data['plan'] = $result;
		$match = array();
		$data['contact_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		
		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $match=array('um.status'=> '1','um.id'=>$this->user_session['user_id']);
		$data['userlist'] = $this->obj1->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','asc');
		
		$data['main_content'] = "user/".$this->viewName."/add";
        $this->load->view('user/include/template', $data);
    }

    /*
    @Description: Function for Insert New Lead Capturing data
    @Author: Mohit Trivedi
    @Input: - Details of new Lead Capturing which is inserted into DB
    @Output: - List of Lead Capturing with new inserted records
    @Date: 13-09-2014
    */
   
    public function insert_data()
     {

		//Widget Creation code	
		$maxid2=$this->obj->select_max();
		$maxid1=$maxid2[0]['id'];
		$maxid=$maxid1+1;
		$uniqueid=uniqid();
		$data1=$uniqueid.'_'.$maxid;
		$data=md5($data1);
		
		$db_name = $this->db_session['db_name'];
		
		//echo $db_name."<br>";
		
		$db_name1 = urlencode(base64_encode($db_name));
		//echo $db_name1."<br>";
		
		//$db_name2 = urldecode(base64_decode($db_name1));
		//echo $db_name2."<br>";
		
		$final_widget = $db_name1."--".$data;
		
		
		$cdata['form_widget_id']=$final_widget;
		$cdata['form_title'] = $this->input->post('form_title');
		$cdata['form_desc'] = $this->input->post('form_desc');
		$cdatamsg['success_msg'] = $this->input->post('success_msg');
		if($this->input->post('show_title'))
			$cdata['show_title'] = $this->input->post('show_title');
		else
			$cdata['show_title'] = 0;
		
		if($this->input->post('show_desc'))
			$cdata['show_desc'] = $this->input->post('show_desc');
		else
			$cdata['show_desc'] = 0;
			
		if(!empty($cdatamsg['success_msg']))
		{
			$cdata['success_msg']=$cdatamsg['success_msg'];
		}
		else
		{
			$cdata['success_msg']='Thank you for providing the requested information. We will get back to you as soon as possible.';
		}
		$cdata['assigned_interaction_plan_id']=$this->input->post('assigned_interaction_plan_id');
		$cdata['form_width'] = $this->input->post('form_width');
		$cdata['form_height'] = $this->input->post('form_height');
		$cdata['bg_color'] = $this->input->post('bg_color');
		$cdata['lead_form'] = $this->input->post('divcontent1'); 
		$cdata1['name_field'] = $this->input->post('f_name');
		/*if($cdata1['name_field']!='')
		{
			$cdata['name_field']=count($cdata1['name_field']);
		}*/
		
		$cdata1['first_name'] = $this->input->post('f_name');
		if($cdata1['first_name']!='')
		{
			$cdata['first_name']=count($cdata1['first_name']);
		}
		
		$cdata1['last_name'] = $this->input->post('l_name');
		if($cdata1['last_name']!='')
		{
			$cdata['last_name']=count($cdata1['last_name']);
		}
		
		$cdata1['phone_field'] = $this->input->post('phone');
		if($cdata1['phone_field']!='')
		{
			$cdata['phone_field']=count($cdata1['phone_field']);
		}

		
		$cdata1['email_field'] = $this->input->post('email');
		if($cdata1['email_field']!='')
		{
			$cdata['email_field']=count($cdata1['email_field']);
		}
		
		$cdata1['single_line_field'] = $this->input->post('linetext');
		if($cdata1['single_line_field']!='')
		{
			$cdata['single_line_field']=count($cdata1['single_line_field']);
		}
		
		$cdata1['paragraph_field'] = $this->input->post('paratext');
		if($cdata1['paragraph_field']!='')
		{
			$cdata['paragraph_field']=count($cdata1['paragraph_field']);
		}
		
		$cdata1['address_field'] = $this->input->post('address');
		if($cdata1['address_field']!='')
		{
			$cdata['address_field']=count($cdata1['address_field']);
		}
		
		$cdata1['date_field'] = $this->input->post('date');
		if($cdata1['date_field']!='')
		{
			$cdata['date_field']=count($cdata1['date_field']);
		}
		
		$cdata1['website_field'] = $this->input->post('website');
		if($cdata1['website_field']!='')
		{
			$cdata['website_field']=count($cdata1['website_field']);
		}

		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		 {
	    	$cdata['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
		 } 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		 {
    		$cdata['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
		 } 
		else 
		{
    		$cdata['created_ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		$cdata1['area_of_interest'] = $this->input->post('areaofinterest');
		if($cdata1['area_of_interest']!='')
			$cdata['area_of_interest'] = count($cdata1['area_of_interest']);
		
		$cdata1['price_range'] = $this->input->post('price_from');
		if($cdata1['price_range']!='')
			$cdata['price_range']=count($cdata1['price_range']);
		
		$cdata1['bedrooms'] = $this->input->post('bedroom');
		if($cdata1['bedrooms']!='')
			$cdata['bedrooms'] = count($cdata1['bedrooms']);
		
		$cdata1['bathrooms'] = $this->input->post('bathroom');
		if($cdata1['bathrooms']!='')
			$cdata['bathrooms']=count($cdata1['bathrooms']);
		
		$cdata1['buyer_preferences_notes'] = $this->input->post('buyer');
		if($cdata1['buyer_preferences_notes']!='')
			$cdata['buyer_preferences_notes'] = count($cdata1['buyer_preferences_notes']);
		
		$cdata1['square_footage'] = $this->input->post('square');
		if($cdata1['square_footage']!='')
			$cdata['square_footage']=count($cdata1['square_footage']);
		
		$cdata1['house_style'] = $this->input->post('house');
		if($cdata1['house_style']!='')
			$cdata['house_style']=count($cdata1['house_style']);
			
		
		$cdata['created_by'] = $this->user_session['id'];
		$cdata['created_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$lastId=$this->obj->insert_record($cdata);
		/// Lead Contact type Trans///
		$contact_type = $this->input->post('contact_type_id');
		if(!empty($contact_type[0]))
		{
				foreach($contact_type as $row)
				{
					$lead_contact_type['lead_id']=$lastId;
					$lead_contact_type['contact_type_id']=$row;
					$lead_contact_type['created_by']=$this->user_session['id'];
					$lead_contact_type['created_date']=date('Y-m-d H:i:s');	
					$this->obj->insert_record_all_trans('lead_contact_type_trans',$lead_contact_type);
				}
		}
		/// End..
		$msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        
        $lead_capturing_sortsearchpage_data = array(
            'sortfield'  => 'id',
            'sortby' => 'desc',
            'searchtext' =>'',
            'perpage' => '',
            'uri_segment' => 0);
        $this->session->set_userdata('lead_capturing_sortsearchpage_data', $lead_capturing_sortsearchpage_data);
		redirect('user/'.$this->viewName);
		
     }
 
    /*
    @Description: Get Details of Edit Lead Capturing Profile
    @Author: Mohit Trivedi
    @Input: - Id of Lead Capturing whose details want to change
    @Output: - Details of stff which id is selected for update
    @Date: 13-09-2014
    */
 
    public function edit_record()
    {
		//check user right
		check_rights('form_builder_edit');
		
		$id = $this->uri->segment(4);
		$where = 'id = '.$id.' AND created_by IN ('.$this->user_session['agent_id'].')';
		$result = $this->obj->getmultiple_tables_records('lead_master','','','','','','','','','','','',$where);
		$data['plan'] = $result;
		//$match = array('id'=>$id,'created_by' =>  $this->user_session['id']);
        //$result = $this->obj->select_records('',$match,'','=');
		$cdata['editRecord'] = $result;
		if(empty($cdata['editRecord']))
		{
			$msg = $this->lang->line('common_right_msg_lead_capturing');
			$newdata = array('msg'  => $msg);
			$this->session->set_userdata('message_session', $newdata);
			redirect('user/'.$this->viewName);	
		}
		$where = 'status = "1" AND created_by IN ('.$this->user_session['agent_id'].')';
		$result1 = $this->obj->getmultiple_tables_records('interaction_plan_master','','','','','','','','','','','',$where);
		
		/*$match1 = array('status'=>1,'created_by'=>$this->user_session['id']);
		$result1=$this->interaction_plans_model->select_records('',$match1,'','=');*/
		
		$lead_contact_type_list = $this->obj->select_lead_contact_trans_record($id);
		if(!empty($lead_contact_type_list[0]))
		{
			for($i=0;$i<count($lead_contact_type_list);$i++)
			{
					$cdata['lead_contact_type_trans'][]=$lead_contact_type_list[$i]['contact_type_id'];
			}
		}
		
		$match = array();
		$cdata['contact_type'] = $this->contact_masters_model->select_records1('',$match,'','=','','','','name','asc','contact__type_master');
		
		$table = "user_master as um";
        $fields = array('um.*,lm.email_id,lm.user_id');
		$join_tables = array('login_master as lm' => 'um.id = lm.user_id');
        $match=array('um.status'=> '1','um.id'=>$this->user_session['user_id']);
		$cdata['userlist'] = $this->obj1->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','asc');
		//pr($data['userlist']);exit;
		$cdata['plan']=$result1;
		$cdata['main_content'] = "user/".$this->viewName."/add";       
		$this->load->view("user/include/template",$cdata);
		
    }

    /*
    @Description: Function for Update Lead Capturing Profile
    @Author: Mohit Trivedi
    @Input: - Update details of Lead Capturing
    @Output: - List with updated Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function update_data()
    {
		$cdata['id'] = $this->input->post('id');
		$cdata['form_title'] = $this->input->post('form_title');
		$cdata['form_desc'] = $this->input->post('form_desc');
		$cdatamsg['success_msg'] = $this->input->post('success_msg');
		if($this->input->post('show_title'))
			$cdata['show_title'] = $this->input->post('show_title');
		else
			$cdata['show_title'] = 0;
		
		if($this->input->post('show_desc'))
			$cdata['show_desc'] = $this->input->post('show_desc');
		else
			$cdata['show_desc'] = 0;
		
		if(!empty($cdatamsg['success_msg']))
		{
			$cdata['success_msg']=$cdatamsg['success_msg'];
		}
		else
		{
			$cdata['success_msg']='Thank you for providing the requested information. We will get back to you as soon as possible.';
		}

		$cdata['assigned_interaction_plan_id']=$this->input->post('assigned_interaction_plan_id');
		$cdata['form_width'] = $this->input->post('form_width');
		$cdata['form_height'] = $this->input->post('form_height');
		$cdata['bg_color'] = $this->input->post('bg_color');
		$cdata['lead_form'] = $this->input->post('divcontent1'); 
		$cdata1['name_field'] = $this->input->post('f_name');
		/*if($cdata1['name_field']!='')
		{
			$cdata['name_field']=count($cdata1['name_field']);
		}*/
		
		$cdata1['first_name'] = $this->input->post('f_name');
		if($cdata1['first_name']!='')
		{
			$cdata['first_name']=count($cdata1['first_name']);
		}
		
		$cdata1['last_name'] = $this->input->post('l_name');
		if($cdata1['last_name']!='')
		{
			$cdata['last_name']=count($cdata1['last_name']);
		}
		
		$cdata1['phone_field'] = $this->input->post('phone');
		if($cdata1['phone_field']!='')
		{
			$cdata['phone_field']=count($cdata1['phone_field']);
		}

		
		$cdata1['email_field'] = $this->input->post('email');
		if($cdata1['email_field']!='')
		{
			$cdata['email_field']=count($cdata1['email_field']);
		}
		
		$cdata1['single_line_field'] = $this->input->post('linetext');
		if($cdata1['single_line_field']!='')
		{
			$cdata['single_line_field']=count($cdata1['single_line_field']);
		}
		
		$cdata1['paragraph_field'] = $this->input->post('paratext');
		if($cdata1['paragraph_field']!='')
		{
			$cdata['paragraph_field']=count($cdata1['paragraph_field']);
		}
		
		$cdata1['address_field'] = $this->input->post('address');
		if($cdata1['address_field']!='')
		{
			$cdata['address_field']=count($cdata1['address_field']);
		}
		
		$cdata1['date_field'] = $this->input->post('date');
		if($cdata1['date_field']!='')
		{
			$cdata['date_field']=count($cdata1['date_field']);
		}
		
		$cdata1['website_field'] = $this->input->post('website');
		if($cdata1['website_field']!='')
		{
			$cdata['website_field']=count($cdata1['website_field']);
		}

		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		 {
	    	$cdata['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
		 } 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		 {
    		$cdata['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
		 } 
		else 
		{
    		$cdata['created_ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		$cdata1['area_of_interest'] = $this->input->post('areaofinterest');
		if($cdata1['area_of_interest']!='')
			$cdata['area_of_interest'] = count($cdata1['area_of_interest']);
		
		$cdata1['price_range'] = $this->input->post('price_from');
		if($cdata1['price_range']!='')
			$cdata['price_range']=count($cdata1['price_range']);
		
		$cdata1['bedrooms'] = $this->input->post('bedroom');
		if($cdata1['bedrooms']!='')
			$cdata['bedrooms'] = count($cdata1['bedrooms']);
		
		$cdata1['bathrooms'] = $this->input->post('bathroom');
		if($cdata1['bathrooms']!='')
			$cdata['bathrooms']=count($cdata1['bathrooms']);
		
		$cdata1['buyer_preferences_notes'] = $this->input->post('buyer');
		if($cdata1['buyer_preferences_notes']!='')
			$cdata['buyer_preferences_notes'] = count($cdata1['buyer_preferences_notes']);
		
		$cdata1['square_footage'] = $this->input->post('square');
		if($cdata1['square_footage']!='')
			$cdata['square_footage']=count($cdata1['square_footage']);
		
		$cdata1['house_style'] = $this->input->post('house');
		if($cdata1['house_style']!='')
			$cdata['house_style']=count($cdata1['house_style']);
		
		$cdata['modified_by'] = $this->user_session['id'];
		$cdata['modified_date'] = date('Y-m-d H:i:s');		
		$cdata['status'] = '1';
		$this->obj->update_record($cdata);
		
		//// Old Lead_contact_type Transaction Remove
		$this->obj->delete_lead_contact_trans_record($cdata['id']);
		/// Lead Contact type Trans///
		$contact_type = $this->input->post('contact_type_id');
		if(!empty($contact_type[0]))
		{
				foreach($contact_type as $row)
				{
					$lead_contact_type['lead_id']=$cdata['id'];
					$lead_contact_type['contact_type_id']=$row;
					$lead_contact_type['created_by']=$this->user_session['id'];
					$lead_contact_type['created_date']=date('Y-m-d H:i:s');	
					$this->obj->insert_record_all_trans('lead_contact_type_trans',$lead_contact_type);
				}
		}
		/// End..
		
		$msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg'  => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('lead_capturing_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        redirect(base_url('user/'.$this->viewName.'/'.$pagingid));
		//redirect(base_url('user/'.$this->viewName));
		
    }
	
   /*
    @Description: Function for Delete Lead Capturing Profile By user
    @Author: Mohit Trivedi
    @Input: - Delete id which Lead Capturing record want to delete
    @Output: - New Lead Capturing list after record is deleted.
    @Date: 13-09-2014
    */

    function delete_record()
    {
        //check user right
		check_rights('form_builder_delete');
		
		$id = $this->uri->segment(4);
		$this->obj->delete_record($id);
		$msg = $this->lang->line('common_delete_success_msg');
       	$newdata = array('msg'  => $msg);
       	$this->session->set_userdata('message_session', $newdata);	
        redirect('user/'.$this->viewName);
    }
	
	 /*
    @Description: Function for Delete Lead Capturing Profile By user
    @Author: Mohit Trivedi
    @Input: - Delete all id of Lead Capturing record want to delete
    @Output: - Lead Capturing list Empty after record is deleted.
    @Date: 19-09-2014
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		if(!empty($id))
		{
			$this->obj->delete_record($id);
			$this->obj->delete_lead_contact_trans_record($id);
			unset($id);
		}
		elseif(!empty($array_data))
		{
			for($i=0;$i<count($array_data);$i++)
			{
				$this->obj->delete_record($array_data[$i]);
				$this->obj->delete_lead_contact_trans_record($array_data[$i]);
			}
		}
		$searchsort_session = $this->session->userdata('lead_capturing_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'5';
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
    @Description: Function Add New Lead Capturing details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - Load Form for add Lead Capturing details
    @Date: 13-09-2014
    */
   
    public function view_record()
    {
		//check user right
		check_rights('form_builder');
		$id = $this->uri->segment(4);
		$match = array('form_id'=>$id);
		$result=$this->obj->select_records1('',$match,'','=');
		$data['formdata']=$result;
		$match1 = array('id'=>$id);
		$result1=$this->obj->select_records('',$match1,'','=');
		$data['form']=$result1;
		$data['main_content'] = "user/".$this->viewName."/view";
        $this->load->view('user/include/template', $data);
    }

    /*
    @Description: Function View Form data datails details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - View Form data datails
    @Date: 16-09-2014
    */
   
    public function view_embed_data()
    {
		$id = $this->input->post('id');
		$match = array('id'=>$id);
		$result=$this->obj->select_records('',$match,'','=');
		$data['formdata']=$result;
    }

    /*
    @Description: Function View Form data datails details
    @Author: Mohit Trivedi
    @Input: - 
    @Output: - View Form data datails
    @Date: 16-09-2014
    */
   
    public function view_form_data()
    {
		$id = $this->input->post('id');
		$match = array('id'=>$id);
		$result=$this->obj->select_records('',$match,'','=');
		$data['viewdata']=$result;
		$this->load->view($this->user_type.'/'.$this->viewName."/form_data",$data);
    }

	/*
		@Description: Function for Copy lead capturing form
		@Author: Sanjay Chabhadiya
		@Input: - Form id
		@Output: - all lead capturing form list
		@Date: 22-12-2014
    */
	
	public function copy_record()
    {
		//check user right
		check_rights('form_builder_add');
		$id = $this->uri->segment(4);
		$match = array('id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
		//pr($result); echo $result[0]['form_title'];
		if(count($result) > 0)
		{
			$maxid2=$this->obj->select_max();
			$maxid1=$maxid2[0]['id'];
			$maxid=$maxid1+1;
			$uniqueid=uniqid();
			$data1=$uniqueid.'_'.$maxid;
			$data2 = md5($data1);
			$db_name = $this->db_session['db_name'];
			$db_name1 = urlencode(base64_encode($db_name));
			$final_widget = $db_name1."--".$data2;
			$data['form_widget_id'] = $final_widget;
			
			$data['form_title'] = $result[0]['form_title'].' - Copy';
			$data['form_desc'] = $result[0]['form_desc'];
			$data['success_msg'] = $result[0]['success_msg'];
			$data['assigned_interaction_plan_id'] = $result[0]['assigned_interaction_plan_id'];
			$data['form_width'] = $result[0]['form_width'];
			$data['form_height'] = $result[0]['form_height'];
			$data['bg_color'] = $result[0]['bg_color'];
			$data['lead_form'] = $result[0]['lead_form'];
			$data['first_name'] = $result[0]['first_name'];
			$data['last_name'] = $result[0]['last_name'];
			$data['phone_field'] = $result[0]['phone_field'];
			$data['email_field'] = $result[0]['email_field'];
			$data['single_line_field'] = $result[0]['single_line_field'];
			$data['paragraph_field'] = $result[0]['paragraph_field'];
			$data['address_field'] = $result[0]['address_field'];
			$data['date_field'] = $result[0]['date_field'];
			$data['website_field'] = $result[0]['website_field'];
			$data['area_of_interest'] = $result[0]['area_of_interest'];
			$data['price_range'] = $result[0]['price_range'];
			$data['bedrooms'] = $result[0]['bedrooms'];
			$data['bathrooms'] = $result[0]['bathrooms'];
			$data['buyer_preferences_notes'] = $result[0]['buyer_preferences_notes'];
			$data['house_style'] = $result[0]['house_style'];
			$data['square_footage'] = $result[0]['square_footage'];
			
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
				$data['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				$data['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
			else 
				$data['created_ip'] = $_SERVER['REMOTE_ADDR'];
			$data['created_by'] = $this->user_session['id'];
			$data['created_date'] = date('Y-m-d H:i:s');
			$data['status'] = '1';
			//pr($data);exit;
			$lastID = $this->obj->insert_record($data);
			
			$lead_contact_type_list = $this->obj->select_lead_contact_trans_record($id);
			if(!empty($lead_contact_type_list))
			{
				$lead_contact_type['lead_id'] = $lastID;
				$lead_contact_type['created_by'] = $this->user_session['id'];
				$lead_contact_type['created_date']=date('Y-m-d H:i:s');	
				foreach($lead_contact_type_list as $row)
				{
					$lead_contact_type['contact_type_id'] = $row['contact_type_id'];
					$this->obj->insert_record_all_trans('lead_contact_type_trans',$lead_contact_type);
				}
			}
		}
		$this->session->set_userdata('message_session', $newdata);	
		redirect('user/'.$this->viewName);
	}
        
        /*
            @Description: Function for submitted form list
            @Author     : Sanjay Moghariya
            @Input      : 
            @Output     : Submitted form list
            @Date       : 28-04-2015
	*/
	public function form_lead_list1()
	{
            $searchtext='';$perpage='';
            $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = trim($this->input->post('perpage'));
            $allflag = $this->input->post('allflag');
            
            if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('submitted_formlead_sortsearchpage_data');
            }
            $data['sortfield']		= 'id';
            $data['sortby']		= 'desc';
            $searchsort_session = $this->session->userdata('submitted_formlead_sortsearchpage_data');

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
                $data['searchtext'] = stripslashes($searchtext);
            } else {
                if(empty($allflag))
                {
                    if(!empty($searchsort_session['searchtext'])) {
                        $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                        $data['searchtext'] = $searchsort_session['searchtext'];
                    }
                }
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
            $config['base_url'] = site_url($this->user_type.'/'."lead_capturing/form_lead_list");
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
            
            $table = "contact_listing_last_seen as clls";
            $match = array('login_id'=>$this->user_session['id']);
            $user_data = $this->contacts_model->getmultiple_tables_records($table,'','','','',$match);
            $current_date = 0;$data['current_date'] = 0;
            if(!empty($user_data[0]['form_lead_last_seen']) && $user_data[0]['form_lead_last_seen'] != '0000-00-00 00:00:00') {
                $data['current_date'] = $user_data[0]['form_lead_last_seen'];
                $current_date = $user_data[0]['form_lead_last_seen'];
            }
            
            if(!empty($current_date))
            {
                $icdata['login_id'] = $this->user_session['id'];
                $icdata['form_lead_last_seen'] = date('Y-m-d H:i:s');
                $this->contacts_model->update_last_seen($icdata);
            }

            $table = "contact_master as cm";
            $group_by='cm.id';
            $fields = array('ls.id as form_id,cm.id,cm.is_subscribe','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ld.created_ip','ld.domain_name','ld.created_date as filled_date','ls.form_title','ld.id as lead_id,cm.created_date');
            $join_tables = array(
                'lead_data as ld' => 'ld.id = cm.lead_id',
                'lead_master as ls' => 'ls.id = ld.form_id',
            );
            if(!empty($searchtext))
            {
                $cre="cm.created_type = '5' and";   
                $where = $cre.' (CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.first_name,cm.last_name) LIKE "%'.$searchtext.'%" OR ld.created_ip LIKE "%'.$searchtext.'%" OR ld.created_date LIKE "%'.$searchtext.'%" OR ls.form_title LIKE "%'.$searchtext.'%"';
                $where .= ')';
                $data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
                $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
            }
            else
            {
                $where = "cm.created_type = '5'";
                $data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
                $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
            }

            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

            $submitted_formlead_sortsearchpage_data = array(
                'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
                'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
                'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
                'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
                'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                'current_date' => !empty($data['current_date'])?$data['current_date']:'0',
                'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

            $this->session->set_userdata('submitted_formlead_sortsearchpage_data', $submitted_formlead_sortsearchpage_data);

            $data['uri_segment'] = $uri_segment;
            if($this->input->post('result_type') == 'ajax')
            {
                $this->load->view($this->user_type.'/lead_capturing/form_lead_ajax_list',$data);
            }
            else
            {
                $data['main_content'] =  $this->user_type.'/lead_capturing/form_lead_list';
                $this->load->view('user/include/template',$data);
            }
	}
        
        public function form_lead_list()
	{
            $searchtext='';$perpage='';
            $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = trim($this->input->post('perpage'));
            $allflag = $this->input->post('allflag');

            if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('submitted_formlead_sortsearchpage_data');
            }
            $data['sortfield']		= 'id';
            $data['sortby']		= 'desc';
            $searchsort_session = $this->session->userdata('submitted_formlead_sortsearchpage_data');

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
                $data['searchtext'] = stripslashes($searchtext);
            } else {
                if(empty($allflag))
                {
                    if(!empty($searchsort_session['searchtext'])) {
                        $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                        $data['searchtext'] = $searchsort_session['searchtext'];
                    }
                }
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
            $config['base_url'] = site_url($this->user_type.'/'."lead_capturing/form_lead_list");
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

            $table = "contact_listing_last_seen as clls";
            $match = array('login_id'=>$this->user_session['id']);
            $user_data = $this->contacts_model->getmultiple_tables_records($table,'','','','',$match);
            $current_date = 0;$data['current_date'] = 0;
            if(!empty($user_data[0]['form_lead_last_seen']) && $user_data[0]['form_lead_last_seen'] != '0000-00-00 00:00:00') {
                $data['current_date'] = $user_data[0]['form_lead_last_seen'];
                $current_date = $user_data[0]['form_lead_last_seen'];
            }

            if(!empty($current_date))
            {
                $icdata['login_id'] = $this->user_session['id'];
                $icdata['form_lead_last_seen'] = date('Y-m-d H:i:s');
                $this->contacts_model->update_last_seen($icdata);
            }

            $table = "contact_master as cm";
            $group_by='cm.id';
            $fields = array('ls.id as form_id,cm.id,cm.is_subscribe','CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name','ld.created_ip','ld.domain_name','ld.created_date as filled_date','ls.form_title','ld.id as lead_id,cm.created_date');
            $join_tables = array(
                'lead_data as ld' => 'ld.id = cm.lead_id',
                'lead_master as ls' => 'ls.id = ld.form_id',
                'user_contact_trans as uct'=>'uct.contact_id = cm.id',
                'login_master as lm' => 'lm.id = cm.created_by',
                'user_master as um' => 'um.id = lm.user_id'
            );
            if(!empty($searchtext))
            {
                $cre="(cm.created_type = '5') and (cm.created_by IN (".$this->user_session['agent_id'].") OR uct.user_id = ".$this->user_session['agent_user_id'].") and";   
                $where = $cre.' (CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) LIKE "%'.$searchtext.'%" OR CONCAT_WS(" ",cm.first_name,cm.last_name) LIKE "%'.$searchtext.'%" OR ld.created_ip LIKE "%'.$searchtext.'%" OR ld.created_date LIKE "%'.$searchtext.'%" OR ls.form_title LIKE "%'.$searchtext.'%"';
                $where .= ')';
                $data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
                $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
            }
            else
            {
                $where="(cm.created_type = '5') and (cm.created_by IN (".$this->user_session['agent_id'].") OR uct.user_id = ".$this->user_session['agent_user_id'].")";   
                $data['datalist'] =$this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','',$config['per_page'], $uri_segment,$data['sortfield'],$data['sortby'],$group_by,$where);
                $config['total_rows'] = $this->contacts_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','',$group_by,$where,'','1');
            }

            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['msg'] = !empty($this->message_session['msg'])?$this->message_session['msg']:'';

            $submitted_formlead_sortsearchpage_data = array(
                'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
                'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
                'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
                'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
                'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                'current_date' => !empty($data['current_date'])?$data['current_date']:'0',
                'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');

            $this->session->set_userdata('submitted_formlead_sortsearchpage_data', $submitted_formlead_sortsearchpage_data);

            $data['uri_segment'] = $uri_segment;
            if($this->input->post('result_type') == 'ajax')
            {
                $this->load->view($this->user_type.'/lead_capturing/form_lead_ajax_list',$data);
            }
            else
            {
                $data['main_content'] =  $this->user_type.'/lead_capturing/form_lead_list';
                $this->load->view('user/include/template',$data);
            }
	}   
	
}