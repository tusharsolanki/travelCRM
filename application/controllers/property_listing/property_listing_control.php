<?php 
/*
    @Description: Lead Capturing form controller
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 17-09-2014
	
*/
?>
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class property_listing_control extends CI_Controller
{	
    function __construct()
    {
		parent::__construct();
		//check user right
		check_rights('listing_manager');
 		$this->load->model('lead_capturing_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('listing_manager_model');
		$this->load->model('property_list_masters_model');
		$this->load->model('imageupload_model');
		$this->load->model('contact_masters_model');
		$this->load->model('contact_type_master_model');
		$this->load->model('contacts_model');
		$this->load->library('mpdf');
		$this->obj = $this->listing_manager_model;
		$this->viewName = $this->router->uri->segments[1];
		$widgetid=$this->router->uri->segments[2];
    }
	

    /*
    @Description: Function for Get All Lead Capturing List
    @Author: Mohit Trivedi
    @Input: - Search value or null
    @Output: - all Lead Capturing list
    @Date: 17-09-2014
    */

    public function index()
    {	
			
			//check user right
		    check_rights('listing_manager');
			$fullwidget_id = $this->router->uri->segments[2];
			
			$db_name = explode("--",$fullwidget_id);
			
			//pr($db_name);exit;
			
			if(!empty($db_name[0]) && !empty($db_name[1]))
			{
				
				//$this->session->unset_userdata('db_session');
				$database_name = base64_decode(urldecode($db_name[0]));
				
				$db_name_to_pass = $this->config->item('parent_db_name');
				$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
				$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
				$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
				
				//pr($domain_result);exit;
				
				if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
                {
					$get_db_name = $domain_result[0]['db_name'];
					$property_id = base64_decode(urldecode($db_name[1]));
					$data['property_id'] = $property_id;
					
					/*$match = array('id'=>$property_id);
					$result = $this->obj->select_records('',$match,'','=','','','','','','',$domain_result[0]['db_name']);*/
					
					$table = $domain_result[0]['db_name'].".property_listing_master as plm";
					$fields = array('plm.*','lm.admin_name,lm.phone,lm.brokerage_pic,lm.user_license_no','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.id as user_id,lm.admin_pic,um.contact_pic,ust1.website_name as facebook,ust2.website_name as linkedin,ust3.website_name as twitter,upt.phone_no,uwt.website_name, CONCAT_WS(",",plm.address_line_1,plm.address_line_2,plm.district,plm.city,plm.state,plm.zip_code,plm.country) as address,lm.address as admin_address','CONCAT_WS(" ",udt.address_line1,udt.address_line2,udt.city,udt.state,udt.zip_code,udt.country) as user_address');
					$join_tables = array($domain_result[0]['db_name'].'.login_master as lm' => 'lm.id = plm.assign_to',
										 $domain_result[0]['db_name'].'.user_master as um' => 'um.id = lm.user_id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = um.id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_website_trans order by id desc) as uwt' => 'uwt.user_id = um.id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 1) as ust1' => 'ust1.user_id = um.id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 2) as ust2' => 'ust2.user_id = um.id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 3) as ust3' => 'ust3.user_id = um.id',
										 '(select * from '.$domain_result[0]['db_name'].'.user_address_trans order by id asc) as udt' => 'udt.user_id = um.id',
										 );
					$group_by='plm.id';
					$where = array('plm.id'=>$property_id);
					$result = $this->listing_manager_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where);

					//echo $this->db->last_query();
					//pr($result);
					//exit;
					$cdata['editRecord'] = $result;
					
					$cdata['photos_trans_data'] = $this->obj->select_photos_trans_record($property_id,'','',$domain_result[0]['db_name']);
					//echo $this->db->last_query();
					//pr($cdata['photos_trans_data']);
					
				//exit;
					
					/*if(!empty($result))
					{
						$table = "login_master as lm";
						$fields = array('lm.id','lm.admin_name','lm.user_id','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as admin_name');
						$join_tables = array('user_master as um' => 'um.id = lm.user_id');
						$group_by='lm.id';
						$match3 = array('lm.id'=>$this->user_session['id']);	
						$where=array('lm.status'=>"'1'");
						$cdata['user_list']=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where,$match3);
					}*/
					
					$data = array();
					if(!empty($result[0]['property_selected_theme']) && $result[0]['property_selected_theme'] == 1)
						$this->load->view($this->viewName."/formdata",$cdata);
					elseif(!empty($result[0]['property_selected_theme']) && $result[0]['property_selected_theme'] == 2)
						$this->load->view($this->viewName."/formdata_2",$cdata);
					else
						$this->load->view($this->viewName."/formdata_3",$cdata);
				}
				else
				{
					echo "Something went wrong.";
				}
			}
			else
			{
				echo "Something went wrong.";
			}			
    }
	
	public function send_mail()
	{
		$to = $this->input->post('email_id');
		$datetime = $this->input->post('datetime');
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$txt_phone_no = $this->input->post('txt_phone_no');
		//pr($_POST);exit;
		$slt_message = $this->input->post('message');
		$uri_segment = $this->input->post('uri_segment');
		if(!empty($to))
		{
			$subject = 'New Inquiry';
			$message = 'New Inquiry Received : <br/>';
			$message .= 'Name : '.$name.'<br/>';
			$message .= 'Email : '.$email.'<br/>';
			if(!empty($txt_phone_no))
				$message .= 'Phone : '.$txt_phone_no.'<br/>';
			$message .= date($this->config->item('common_date_format'),strtotime($datetime)).' <br/>';
			$message .= $slt_message;
			$from = 'nishit.modi@tops-int.com';
			$headers = 'MIME-Version: 1.0'."\r\n";
			$headers .= "Content-type:text/html; charset=iso-8859-1\r\n"; 
			mail($to,$subject,$message,$headers,"-f".$from);
		}
		redirect($this->viewName.'/'.$uri_segment);
	}
	function flyer1()
	{
		$fullwidget_id = $this->router->uri->segments[3];
		$db_name = explode("--",$fullwidget_id);
		if(!empty($db_name[0]) && !empty($db_name[1]))
		{
			//$this->session->unset_userdata('db_session');
			$database_name = base64_decode(urldecode($db_name[0]));
			$db_name_to_pass = $this->config->item('parent_db_name');
			$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
			$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
			$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
			//pr($domain_result);exit;
			if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
			{
				$get_db_name = $domain_result[0]['db_name'];
				$property_id = base64_decode(urldecode($db_name[1]));
				$data['property_id'] = $property_id;
				
				/*$match = array('id'=>$property_id);
				$result = $this->obj->select_records('',$match,'','=','','','','','','',$domain_result[0]['db_name']);*/
				
				$table = $domain_result[0]['db_name'].".property_listing_master as plm";
				$fields = array('plm.*','lm.admin_name,lm.phone,lm.user_type,lm.brokerage_pic,lm.user_license_no','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.id as user_id,lm.admin_pic,um.contact_pic,ust1.website_name as facebook,ust2.website_name as linkedin,ust3.website_name as twitter,upt.phone_no,uwt.website_name, CONCAT_WS(",",plm.address_line_1,plm.address_line_2,plm.district,plm.city,plm.state,plm.zip_code,plm.country) as address,lm.address as admin_address,CONCAT_WS(" ",udt.address_line1,udt.address_line2) as user_address,udt.city as user_city,udt.state as user_state,udt.zip_code as user_zip_code,udt.country as user_country');
				$join_tables = array($domain_result[0]['db_name'].'.login_master as lm' => 'lm.id = plm.assign_to',
									 $domain_result[0]['db_name'].'.user_master as um' => 'um.id = lm.user_id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_website_trans order by id desc) as uwt' => 'uwt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 1) as ust1' => 'ust1.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 2) as ust2' => 'ust2.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 3) as ust3' => 'ust3.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_address_trans order by id asc) as udt' => 'udt.user_id = um.id',
									 );
				$group_by='plm.id';
				$where = array('plm.id'=>$property_id);
				$result = $this->listing_manager_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where);

				//echo $this->db->last_query();
				
				$cdata['editRecord'] = $result;
				
				$cdata['photos_trans_data'] = $this->obj->select_photos_trans_record($property_id,'','',$domain_result[0]['db_name']);
				
				$mpdf=  new mPDF('','','','','0','0','0','0','0','0');
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->SetTitle($utxt['zh-CN']);
				//$mpdf->SetAuthor($utxt['zh-CN']);
				$html=$this->load->view($this->viewName."/flyer1", $cdata,true);
				//exit;
				$this->downloadpdf($html,'flyer1',$data1);
				
			}
			else
			{
				echo "Something went wrong.";
			}
		}
		else
		{
			echo "Something went wrong.";
		}
		
	}
	function flyer2()
	{
		
		$fullwidget_id = $this->router->uri->segments[3];
		$db_name = explode("--",$fullwidget_id);
		if(!empty($db_name[0]) && !empty($db_name[1]))
		{
			//$this->session->unset_userdata('db_session');
			$database_name = base64_decode(urldecode($db_name[0]));
			$db_name_to_pass = $this->config->item('parent_db_name');
			$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
			$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
			$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
			//pr($domain_result);exit;
			if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
			{
				$get_db_name = $domain_result[0]['db_name'];
				$property_id = base64_decode(urldecode($db_name[1]));
				$data['property_id'] = $property_id;
				
				/*$match = array('id'=>$property_id);
				$result = $this->obj->select_records('',$match,'','=','','','','','','',$domain_result[0]['db_name']);*/
				
				$table = $domain_result[0]['db_name'].".property_listing_master as plm";
				$fields = array('plm.*','lm.admin_name,lm.phone,lm.user_type,lm.brokerage_pic,lm.user_license_no','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.id as user_id,lm.admin_pic,um.contact_pic,ust1.website_name as facebook,ust2.website_name as linkedin,ust3.website_name as twitter,upt.phone_no,uwt.website_name, CONCAT_WS(",",plm.address_line_1,plm.address_line_2,plm.district,plm.city,plm.state,plm.zip_code,plm.country) as address,lm.address as admin_address,CONCAT_WS(" ",udt.address_line1,udt.address_line2) as user_address,udt.city as user_city,udt.state as user_state,udt.zip_code as user_zip_code,udt.country as user_country');
				$join_tables = array($domain_result[0]['db_name'].'.login_master as lm' => 'lm.id = plm.assign_to',
									 $domain_result[0]['db_name'].'.user_master as um' => 'um.id = lm.user_id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_website_trans order by id desc) as uwt' => 'uwt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 1) as ust1' => 'ust1.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 2) as ust2' => 'ust2.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 3) as ust3' => 'ust3.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_address_trans order by id asc) as udt' => 'udt.user_id = um.id',
									 );
				$group_by='plm.id';
				$where = array('plm.id'=>$property_id);
				$result = $this->listing_manager_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where);
				//pr($result);exit;
				//echo $this->db->last_query();
				
				$cdata['editRecord'] = $result;
				
				$cdata['photos_trans_data'] = $this->obj->select_photos_trans_record($property_id,'','',$domain_result[0]['db_name']);
				
				$mpdf=  new mPDF('','','','','0','0','0','0','0','0');
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->SetTitle($utxt['zh-CN']);
				//$mpdf->SetAuthor($utxt['zh-CN']);
				$html=$this->load->view($this->viewName."/flyer2", $cdata,true);
				//exit;
				$this->downloadpdf($html,'flyer2',$data1);
				
			}
			else
			{
				echo "Something went wrong.";
			}
		}
		else
		{
			echo "Something went wrong.";
		}	
		
	}
	
	function flyer3()
	{
		
		$fullwidget_id = $this->router->uri->segments[3];
		$db_name = explode("--",$fullwidget_id);
		if(!empty($db_name[0]) && !empty($db_name[1]))
		{
			//$this->session->unset_userdata('db_session');
			$database_name = base64_decode(urldecode($db_name[0]));
			$db_name_to_pass = $this->config->item('parent_db_name');
			$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
			$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
			$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
			//pr($domain_result);exit;
			if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
			{
				$get_db_name = $domain_result[0]['db_name'];
				$property_id = base64_decode(urldecode($db_name[1]));
				$data['property_id'] = $property_id;
				
				/*$match = array('id'=>$property_id);
				$result = $this->obj->select_records('',$match,'','=','','','','','','',$domain_result[0]['db_name']);*/
				
				$table = $domain_result[0]['db_name'].".property_listing_master as plm";
				$fields = array('plm.*','lm.admin_name,lm.phone,lm.user_type,lm.brokerage_pic,lm.user_license_no','lm.email_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.id as user_id,lm.admin_pic,um.contact_pic,ust1.website_name as facebook,ust2.website_name as linkedin,ust3.website_name as twitter,upt.phone_no,uwt.website_name, CONCAT_WS(",",plm.address_line_1,plm.address_line_2,plm.district,plm.city,plm.state,plm.zip_code,plm.country) as address,lm.address as admin_address,CONCAT_WS(" ",udt.address_line1,udt.address_line2) as user_address,udt.city as user_city,udt.state as user_state,udt.zip_code as user_zip_code,udt.country as user_country');
				$join_tables = array($domain_result[0]['db_name'].'.login_master as lm' => 'lm.id = plm.assign_to',
									 $domain_result[0]['db_name'].'.user_master as um' => 'um.id = lm.user_id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_website_trans order by id desc) as uwt' => 'uwt.user_id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 1) as ust1' => 'ust1.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 2) as ust2' => 'ust2.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_social_trans where profile_type = 3) as ust3' => 'ust3.id = um.id',
									 '(select * from '.$domain_result[0]['db_name'].'.user_address_trans order by id asc) as udt' => 'udt.user_id = um.id',
									 );
				$group_by='plm.id';
				$where = array('plm.id'=>$property_id);
				$result = $this->listing_manager_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','like','', '','','',$group_by,$where);
				//pr($result);exit;
				//echo $this->db->last_query();
				
				$cdata['editRecord'] = $result;
				
				$cdata['photos_trans_data'] = $this->obj->select_photos_trans_record($property_id,'','',$domain_result[0]['db_name']);
				
				$mpdf=  new mPDF('','','','','0','0','0','0','0','0');
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->SetTitle($utxt['zh-CN']);
				//$mpdf->SetAuthor($utxt['zh-CN']);
				$html=$this->load->view($this->viewName."/flyer3", $cdata,true);
				/*echo $html;
				exit;*/
				$this->downloadpdf($html,'flyer3',$data1);
				
			}
			else
			{
				echo "Something went wrong.";
			}
		}
		else
		{
			echo "Something went wrong.";
		}	
		
	}
	
	public function downloadpdf($html1,$name,$data1)
	{
		$html=$html1;
		$mypdf = new mPDF('','','','','0','0','0','0','0','0');
		//$mypdf = new mPDF('','','','','5','5','5','20','0','8');
		//$mpdf->SetDisplayMode('fullpage');
		//$mypdf->SetHTMLHeader('', 'O', true);
		//$mypdf->SetHTMLFooter('<div style=" text-align:center;width:100%;font-weight:bold;color:#376091; ">Provident Investment - '. $data1['property_name'].' '.$data1['currency_type'].''.$data1['purchase_price'].' {DATE Y/m/d}  </div><div style="text-align:center;font-weight:bold;color:#376091;"> {PAGENO}/{nb} </div>', 'O', true);
		$mypdf->WriteHTML($html);
		//$mypdf->Output();
		$mypdf->Output("$name.pdf","D");

	}
}