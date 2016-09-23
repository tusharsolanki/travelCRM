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

class lead_capturing_form_control extends CI_Controller
{	
    function __construct()
    { //per had b'day na 198 aava che barabar ema nandu ne add nathi karo etle ena pase this nathi levana
		parent::__construct();
		//check user right
		check_rights('form_builder');
 		$this->load->model('lead_capturing_model');
		$this->load->model('interaction_plans_model');
		$this->load->model('contact_masters_model');
		$this->load->model('lead_capturing_model');
		$this->load->model('contacts_model');
		$this->load->model('sms_campaign_master_model');
		$this->load->model('work_time_config_master_model');
		$this->load->model('interaction_model');
		$this->load->model('email_campaign_master_model');
		$this->load->model('contact_masters_model');
		$this->load->model('admin_model');
		
		
		$this->obj = $this->lead_capturing_model;
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
			
			$fullwidget_id = $this->router->uri->segments[2];
			
			$db_name = explode("--",$fullwidget_id);
			
			if(!empty($db_name[0]) && !empty($db_name[1]))
			{
				
				//$this->session->unset_userdata('db_session');
				$database_name = base64_decode(urldecode($db_name[0]));
				
				//// For Getting Dynamic Database credential and connect to that database
				/*$table = "login_master as lm";
				$fields = array('lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
				$join_tables = array();
				$match = array('lm.db_name'=>$database_name,'lm.status'=>'1');
				$domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'','',$match,'=','','','','');*/
				//pr($domain_result);
				
				$db_name_to_pass = $this->config->item('parent_db_name');
				$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
				$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
				$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
				
				//pr($domain_result);exit;
				
				if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
			
					/*$newdata1 = array(
						'host_name'  => $domain_result[0]['host_name'],
						'db_user_name' =>$domain_result[0]['db_user_name'],
						'db_user_password' =>$domain_result[0]['db_user_password'],
						'db_name' =>$domain_result[0]['db_name']
					);
					$this->session->set_userdata('db_session', $newdata1);*/
					
					//$this->db->close();



					// Insert domain into table //

					$iccdata['form_widget'] = $fullwidget_id;

					$iccdata['domain'] = $database_name;

					if (!empty($_SERVER['HTTP_CLIENT_IP']))
					 {
						$iccdata['created_ip'] = $_SERVER['HTTP_CLIENT_IP'];
					 } 
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
					 {
						$iccdata['created_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];	
					 } 
					else 
					{
						$iccdata['created_ip'] = $_SERVER['REMOTE_ADDR'];
					}

					$iccdata['created_date'] = date('Y-m-d H:i:s');

					$result =  $this->db->insert('form_builder_called_from_ip',$iccdata);
					
					$lead_id= mysql_insert_id();

					// END //




					$db = '';
						
					$db['second']['hostname'] = $domain_result[0]['host_name'];
					$db['second']['username'] = $domain_result[0]['db_user_name'];
					//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
					$db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
					$db['second']['database'] = $domain_result[0]['db_name'];
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
					
					$this->legacy_db = $this->load->database($db['second'], TRUE);
					
					//pr($db);
					
					/*$match = array('form_widget_id'=>$db_name[1]);
					$data['form_data'] =$this->obj->select_records('',$match,'','=');*/


					
					
					$this->legacy_db->select('*');
					$this->legacy_db->from('lead_master');
					$this->legacy_db->where(array('form_widget_id'=>$fullwidget_id));
					$query = $this->legacy_db->get();
					$data['form_data'] = $query->result_array();
					//pr($data);exit;
					
					$this->load->view($this->viewName."/formdata",$data);
					
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

    /*
    @Description: Function for Insert New Lead Capturing data
    @Author: Mohit Trivedi
    @Input: - Details of new Lead Capturing which is inserted into DB
    @Output: - List of Lead Capturing with new inserted records
    @Date: 13-09-2014
    */
   
    public function insert_data()
     {
     	//pr($_POST);exit;
		if($this->input->post('id') != '')
		{
			$fullwidget_id = $this->input->post('id');
			
			$db_name = explode("--",$fullwidget_id);
			
			if(!empty($db_name[0]) && !empty($db_name[1]))
			{
				//$this->session->unset_userdata('db_session');
				$database_name = base64_decode(urldecode($db_name[0]));
				
				//// For Getting Dynamic Database credential and connect to that database
				/*$table = "login_master as lm";
				$fields = array('lm.db_name,lm.host_name,lm.db_user_name,lm.db_user_password');
				$join_tables = array();
				$match = array('lm.db_name'=>$database_name,'lm.status'=>'1');
				$domain_result = $this->contact_masters_model->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','');*/
				
				$db_name_to_pass = $this->config->item('parent_db_name');
				$fields1 = array('id,db_name,host_name,db_user_name,db_user_password');
				$match = array('db_name'=>$database_name,'user_type'=>'2','status'=>'1');
				$domain_result = $this->admin_model->get_user($fields1,$match,'','=','','','','','','',$db_name_to_pass);
				
				//pr($domain_result);exit;
				
				if(!empty($domain_result) && !empty($domain_result[0]['host_name']) && !empty($domain_result[0]['db_user_name']) && !empty($domain_result[0]['db_name']))
                {
			
					/*$newdata1 = array(
						'host_name'  => $domain_result[0]['host_name'],
						'db_user_name' =>$domain_result[0]['db_user_name'],
						'db_user_password' =>$domain_result[0]['db_user_password'],
						'db_name' =>$domain_result[0]['db_name']
					);
					$this->session->set_userdata('db_session', $newdata1);*/
					
					//$this->db->close();
					$db = '';
						
					$db['second']['hostname'] = $domain_result[0]['host_name'];
					$db['second']['username'] = $domain_result[0]['db_user_name'];
					//$db['second']['password'] = "ToPs@tops$$";	//For topsdemo.in
					$db['second']['password'] = $domain_result[0]['db_user_password'];			//Local
					$db['second']['database'] = $domain_result[0]['db_name'];
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
					
					$this->legacy_db = $this->load->database($db['second'], TRUE);
					
					/*$cdata['form_widget_id']=$db_name[1];
					$match = array('form_widget_id'=>$cdata['form_widget_id']);
					$data['form_data'] =$this->obj->select_records('',$match,'','=');*/
					
					$this->legacy_db->select('*');
					$this->legacy_db->from('lead_master');
					$this->legacy_db->where(array('form_widget_id'=>$fullwidget_id));
					$query = $this->legacy_db->get();
					$data['form_data'] = $query->result_array();
					
					
					$data=$data['form_data'];
					
					if(!empty($data[0]['assign_user_id']))
                                            $assign_user_id = $data[0]['assign_user_id']; 
					
					$cdata['form_id']=$data[0]['id'];
					$cdata1['first_name_data'] = $this->input->post('f_name');
					$cdata1['first_name_title'] = $this->input->post('fname_type');
					if(!empty($cdata1['first_name_data']))
					{
						$cdata['first_name_data'] = '';
						$cdata['first_name_title'] = '';
						for($i=0;$i<count($cdata1['first_name_data']);$i++)
						{
							$cdata['first_name_data'].=$cdata1['first_name_data'][$i].'{^}';
							$cdata['first_name_title'].=$cdata1['first_name_title'][$i].'{^}';
						}
						$cdata['first_name_data']=rtrim($cdata['first_name_data'],'{^}');
						$cdata['first_name_title']=rtrim($cdata['first_name_title'],'{^}');
					}
					$cdata1['last_name_data'] = $this->input->post('l_name');
					$cdata1['last_name_title'] = $this->input->post('lname_type');
					if(!empty($cdata1['last_name_data']))
					{
						$cdata['last_name_data']='';
						$cdata['last_name_title']='';
						for($j=0;$j<count($cdata1['last_name_data']);$j++)
						{
							$cdata['last_name_data'].=$cdata1['last_name_data'][$j].'{^}';				
							$cdata['last_name_title'].=$cdata1['last_name_title'][$j].'{^}';				
						}
						$cdata['last_name_data']=rtrim($cdata['last_name_data'],'{^}');
						$cdata['last_name_title']=rtrim($cdata['last_name_title'],'{^}');
					}
					$cdata1['email_data'] = $this->input->post('email');
					$cdata1['email_title'] = $this->input->post('email_type');
					if(!empty($cdata1['email_data']))
					{
						$cdata['email_data']='';
						$cdata['email_title']='';
						for($k=0;$k<count($cdata1['email_data']);$k++)
						{
							$cdata['email_data'].=$cdata1['email_data'][$k].'{^}';				
							$cdata['email_title'].=$cdata1['email_title'][$k].'{^}';				
						}
						$cdata['email_data']=rtrim($cdata['email_data'],'{^}');	
						$cdata['email_title']=rtrim($cdata['email_title'],'{^}');			
					}
					$cdata1['phone_data']=$this->input->post('phone');
					$cdata1['phone_title']=$this->input->post('phone_type');
					if(!empty($cdata1['phone_data']))
					{
						$cdata['phone_data']='';
						$cdata['phone_title']='';
						for($l=0;$l<count($cdata1['phone_data']);$l++)
						{
							$cdata['phone_data'].=$cdata1['phone_data'][$l].'{^}';				
							$cdata['phone_title'].=$cdata1['phone_title'][$l].'{^}';				
						}
						$cdata['phone_data']=rtrim($cdata['phone_data'],'{^}');						
						$cdata['phone_title']=rtrim($cdata['phone_title'],'{^}');						
					}
					$cdata1['single_line_data'] = $this->input->post('linetext');
					$cdata1['single_line_title'] = $this->input->post('single_type');
					if(!empty($cdata1['single_line_data']))
					{
						$cdata['single_line_data']='';
						$cdata['single_line_title']='';
						for($m=0;$m<count($cdata1['single_line_data']);$m++)
						{
							$cdata['single_line_data'].=$cdata1['single_line_data'][$m].'{^}';				
							$cdata['single_line_title'].=$cdata1['single_line_title'][$m].'{^}';				
						}
						$cdata['single_line_data']=rtrim($cdata['single_line_data'],'{^}');					
						$cdata['single_line_title']=rtrim($cdata['single_line_title'],'{^}');						
					}
					$cdata1['paragraph_data'] = $this->input->post('paratext');
					$cdata1['paragraph_title'] = $this->input->post('para_type');
					if(!empty($cdata1['paragraph_data']))
					{
						$cdata['paragraph_data']='';
						$cdata['paragraph_title']='';
						for($n=0;$n<count($cdata1['paragraph_data']);$n++)
						{
							$cdata['paragraph_data'].=$cdata1['paragraph_data'][$n].'{^}';				
							$cdata['paragraph_title'].=$cdata1['paragraph_title'][$n].'{^}';				
						}
						$cdata['paragraph_data']=rtrim($cdata['paragraph_data'],'{^}');					
						$cdata['paragraph_title']=rtrim($cdata['paragraph_title'],'{^}');						
			
					}
					$cdata1['address_data'] = $this->input->post('address');
					$cdata1['address_title'] = $this->input->post('add_type');
					if(!empty($cdata1['address_data']))
					{
						$cdata['address_data']='';
						$cdata['address_title']='';
						for($o=0;$o<count($cdata1['address_data']);$o++)
						{
							$cdata['address_data'].=$cdata1['address_data'][$o].'{^}';				
							$cdata['address_title'].=$cdata1['address_title'][$o].'{^}';				
						}
						$cdata['address_data']=rtrim($cdata['address_data'],'{^}');					
						$cdata['address_title']=rtrim($cdata['address_title'],'{^}');						
					}
					$cdata1['date_data'] = $this->input->post('date'); 
					$cdata1['date_title'] = $this->input->post('date_type'); 
					if(!empty($cdata1['date_data']))
					{
						$cdata['date_data']='';
						$cdata['date_title']='';
						for($p=0;$p<count($cdata1['date_data']);$p++)
						{
							$cdata['date_data'].=$cdata1['date_data'][$p].'{^}';				
							$cdata['date_title'].=$cdata1['date_title'][$p].'{^}';				
						}
						$cdata['date_data']=rtrim($cdata['date_data'],'{^}');					
						$cdata['date_title']=rtrim($cdata['date_title'],'{^}');						
					}
					$cdata1['website_data'] = $this->input->post('website');
					$cdata1['website_title'] = $this->input->post('web_type');
					if(!empty($cdata1['website_data']))
					{
						$cdata['website_data']='';
						$cdata['website_title']='';
						for($q=0;$q<count($cdata1['website_data']);$q++)
						{
							$cdata['website_data'].=$cdata1['website_data'][$q].'{^}';				
							$cdata['website_title'].=$cdata1['website_title'][$q].'{^}';				
						}
						$cdata['website_data']=rtrim($cdata['website_data'],'{^}');					
						$cdata['website_title']=rtrim($cdata['website_title'],'{^}');						
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
					
					/*$cdata1['date_data'] = $this->input->post('');
					if(!empty($cdata1['date_data']))
					{
						$cdata['date_data']='';
						for($p=0;$p<count($cdata1['date_data']);$p++)
						{
							$cdata['date_data'].=$cdata1['date_data'][$p].'{^}';				
						}
						$cdata['date_data']=rtrim($cdata['date_data'],'{^}');						
					}*/
					
					
					
					$cdata1['area_of_interest'] = $this->input->post('areaofinterest');
					$cdata1['area_of_interest_title'] = $this->input->post('area_type');
					if($cdata1['area_of_interest']!='')
					{
						$cdata['area_of_interest'] = '';
						$cdata['area_of_interest_title'] = '';
						for($p=0;$p<count($cdata1['area_of_interest']);$p++)
						{	
							$cdata['area_of_interest'] .= $cdata1['area_of_interest'][$p].'{^}';
							$cdata['area_of_interest_title'] .= $cdata1['area_of_interest_title'][$p].'{^}';
						}	
						$cdata['area_of_interest'] = rtrim($cdata['area_of_interest'],'{^}');
						$cdata['area_of_interest_title'] = rtrim($cdata['area_of_interest_title'],'{^}');
					}
					
					$cdata1['price_range_from'] = $this->input->post('price_from');
					$cdata1['price_range_from_title'] = $this->input->post('pricefrom_type');
					if($cdata1['price_range_from']!='')
					{
						$cdata['price_range_from'] = '';
						$cdata['price_range_from_title'] = '';
						for($p=0;$p<count($cdata1['price_range_from']);$p++)
						{
							$cdata['price_range_from'] .= $cdata1['price_range_from'][$p].'{^}';
							$cdata['price_range_from_title'] .= $cdata1['price_range_from_title'][$p].'{^}';
						}
						$cdata['price_range_from'] = rtrim($cdata['price_range_from'],'{^}');
						$cdata['price_range_from_title'] = rtrim($cdata['price_range_from_title'],'{^}');
					}
					
					$cdata1['price_range_to'] = $this->input->post('price_to');
					$cdata1['price_range_to_title'] = $this->input->post('priceto_type');
					if($cdata1['price_range_to']!='')
					{
						$cdata['price_range_to'] = '';
						$cdata['price_range_to_title'] = '';
						for($p=0;$p<count($cdata1['price_range_to']);$p++)
						{
							$cdata['price_range_to'] .= $cdata1['price_range_to'][$p].'{^}';
							$cdata['price_range_to_title'] .= $cdata1['price_range_to_title'][$p].'{^}';
						}
						$cdata['price_range_to'] = rtrim($cdata['price_range_to'],'{^}');
						$cdata['price_range_to_title'] = rtrim($cdata['price_range_to_title'],'{^}');
					}
					
					$cdata1['no_of_bedrooms'] = $this->input->post('bedroom');
					$cdata1['no_of_bedrooms_title'] = $this->input->post('bedroom_type');
					if($cdata1['no_of_bedrooms']!='')
					{
						$cdata['no_of_bedrooms'] = '';
						$cdata['no_of_bedrooms_title'] = '';
						for($p=0;$p<count($cdata1['no_of_bedrooms']);$p++)
						{
							$cdata['no_of_bedrooms'] .= $cdata1['no_of_bedrooms'][$p].'{^}';
							$cdata['no_of_bedrooms_title'] .= $cdata1['no_of_bedrooms_title'][$p].'{^}';
						}
						$cdata['no_of_bedrooms'] = rtrim($cdata['no_of_bedrooms'],'{^}');
						$cdata['no_of_bedrooms_title'] = rtrim($cdata['no_of_bedrooms_title'],'{^}');
					}
					
					$cdata1['no_of_bathrooms'] = $this->input->post('bathroom');
					$cdata1['no_of_bathrooms_title'] = $this->input->post('bathroom_type');
					if($cdata1['no_of_bathrooms']!='')
					{
						$cdata['no_of_bathrooms'] = '';
						$cdata['no_of_bathrooms_title'] = '';
						for($p=0;$p<count($cdata1['no_of_bathrooms']);$p++)
						{
							$cdata['no_of_bathrooms'] .= $cdata1['no_of_bathrooms'][$p].'{^}';
							$cdata['no_of_bathrooms_title'] .= $cdata1['no_of_bathrooms_title'][$p].'{^}';
						}
						$cdata['no_of_bathrooms'] = rtrim($cdata['no_of_bathrooms'],'{^}');
						$cdata['no_of_bathrooms_title'] = rtrim($cdata['no_of_bathrooms_title'],'{^}');
					}
					
					$cdata1['buyer_preferences_notes'] = $this->input->post('buyer');
					$cdata1['buyer_preferences_notes_title'] = $this->input->post('buyer_type');
					if($cdata1['buyer_preferences_notes']!='')
					{
						$cdata['buyer_preferences_notes'] = '';
						$cdata['buyer_preferences_notes_title'] = '';
						for($p=0;$p<count($cdata1['buyer_preferences_notes']);$p++)
						{
							$cdata['buyer_preferences_notes'] .= $cdata1['buyer_preferences_notes'][$p].'{^}';
							$cdata['buyer_preferences_notes_title'] .= $cdata1['buyer_preferences_notes_title'][$p].'{^}';
						}
						$cdata['buyer_preferences_notes'] = rtrim($cdata['buyer_preferences_notes'],'{^}');
						$cdata['buyer_preferences_notes_title'] = rtrim($cdata['buyer_preferences_notes_title'],'{^}');
					}
					
					$cdata1['square_footage'] = $this->input->post('square');
					$cdata1['square_footage_title'] = $this->input->post('square_type');
					if($cdata1['square_footage']!='')
					{
						$cdata['square_footage'] = '';
						$cdata['square_footage_title'] = '';
						for($p=0;$p<count($cdata1['square_footage']);$p++)
						{
							$cdata['square_footage'] .= $cdata1['square_footage'][$p].'{^}';
							$cdata['square_footage_title'] .= $cdata1['square_footage_title'][$p].'{^}';
						}
						$cdata['square_footage'] = rtrim($cdata['square_footage'],'{^}');
						$cdata['square_footage_title'] = rtrim($cdata['square_footage_title'],'{^}');
					}
					
					$cdata1['house_style'] = $this->input->post('house');
					$cdata1['house_style_title'] = $this->input->post('house_type');
					if($cdata1['house_style']!='')
					{
						$cdata['house_style'] = '';
						$cdata['house_style_title'] = '';
						for($p=0;$p<count($cdata1['house_style']);$p++)
						{
							$cdata['house_style'] .= $cdata1['house_style'][$p].'{^}';
							$cdata['house_style_title'] .= $cdata1['house_style_title'][$p].'{^}';
						}
						$cdata['house_style'] = rtrim($cdata['house_style'],'{^}');
						$cdata['house_style_title'] = rtrim($cdata['house_style_title'],'{^}');
					}
					
					$file = $_FILES['file_name'];
					$cdata1['file_name_title'] = $this->input->post('file_type');
					if(!empty($file))
					{
						
						$cdata['file_name'] = '';
						$cdata['file_name_title'] = '';
						for($s=0;$s<count($file);$s++)
						{
							if(!empty($_FILES['file_name']['name'][$s]))
							{
								$_FILES['file_attachment']['name'] = $_FILES['file_name']['name'][$s];
								$_FILES['file_attachment']['type']    = $_FILES['file_name']['type'][$s];
								$_FILES['file_attachment']['tmp_name'] = $_FILES['file_name']['tmp_name'][$s];
								$_FILES['file_attachment']['error']       = $_FILES['file_name']['error'][$s];
								$_FILES['file_attachment']['size']    = $_FILES['file_name']['size'][$s];
								$config['upload_path'] = 'uploads/contact_docs/';
								$config['allowed_types'] = '*';
								$config['overwrite'] = false;
								$this->load->library('upload', $config);
								if($this->upload->do_upload('file_attachment'))
								{
									$datac = $this->upload->data();
									$cdata['file_name'] .= $datac['file_name'].'{^}';
									$cdata['file_name_title'] .= $datac['file_name_title'].'{^}';
								}
								else
								{
									echo $this->upload->display_errors();
									exit;
								}
							}
							//echo $cdata1['file'][$p];
							//$cdata['house_style'] .= $cdata1['house_style'][$p].'{^}';
						}
					}
					$cdata['file_name'] = rtrim($cdata['file_name'],'{^}');
					$cdata['file_name_title'] = rtrim($cdata['file_name_title'],'{^}');
					
					$cdata['created_date'] = date('Y-m-d H:i:s');		
					$cdata['status'] = '0';
					$result =  $this->legacy_db->insert('lead_data',$cdata);
                                        $lead_id = mysql_insert_id();
                                        $first_name = '';
                                        if(!empty($cdata1['first_name_data']))
                                        {
                                            foreach($cdata1['first_name_data'] as $row)
                                            {
                                                if(!empty($row))
                                                    $first_name .= $row.',';
                                            }
                                        }
                                        $last_name = '';
                                        if(!empty($cdata1['last_name_data']))
                                        {
                                            foreach($cdata1['last_name_data'] as $row)
                                            {
                                                if(!empty($row))
                                                    $last_name .= $row.',';
                                            }
                                        }
                                       
                                        $this->load->library('Twilio');
                                        
                                        $table = $database_name.".login_master as lm";
                                        $fields = array('lm.id,upt.phone_no,lm.phone,lm.user_type');
                                        $join_tables = array($database_name.'.user_master as um' => 'um.id = lm.user_id',
                                                             '(SELECT * FROM '.$database_name.'.user_phone_trans order by is_default desc) as upt' => 'upt.user_id = lm.user_id',
                                                            );
                                        $group_by = 'lm.id';
                                        if(!empty($assign_user_id))
                                            $match = array('lm.user_id'=>$assign_user_id);
                                        else
                                            $match = array('lm.user_type'=>'2');
                                        $assign_user_data = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'','','','','',$group_by);
                                        
                                        if(!empty($assign_user_data))
                                        {
                                            if(!empty($assign_user_data[0]['user_type']) && ($assign_user_data[0]['user_type'] == 2 || $assign_user_data[0]['user_type'] == 5))
                                                $phone_no = $assign_user_data[0]['phone'];
                                            else
                                                $phone_no = $assign_user_data[0]['phone_no'];
                                        }
                                        $this->load->model('sms_texts_model');
                                        $fields = array('sms_message');
                                        $match = array('sms_event'=>'6');
                                        $template_data = $this->sms_texts_model->select_records($fields,$match,'','=','','','','','',$database_name);
                                        
                                        $emaildata = array(
                                                            'Date'=>date('Y-m-d'),
                                                            'Day'=>date('l'),
                                                            'Month'=>date('F'),
                                                            'Year'=>date('Y'),
                                                            'Day Of Week'=>date( "w", time()),
                                                            'Contact First Name'=>rtrim($first_name,','),
                                                            'Contact Spouse/Partner First Name'=>'',
                                                            'Contact Last Name'=>rtrim($last_name,','),
                                                            'Contact Spouse/Partner Last Name'=>'',
                                                            'Contact Company Name'=>''
                                                           );
					$content = !empty($template_data[0]['sms_message'])?$template_data[0]['sms_message']:'';
					$pattern = "{(%s)}";
					$map = array();
					if($emaildata != '' && count($emaildata) > 0)
					{
						foreach($emaildata as $var => $value)
						{
							$map[sprintf($pattern, $var)] = $value;
						}
						$output = strtr($content, $map);
					}
					$message = !empty($output)?$output:$this->lang->line('form_builder_through_registered');
                                        if(!empty($assign_user_data[0]['id']) && !empty($phone_no))
                                        {
                                            //'+919033921029'
                                            $message1 = $this->lang->line('form_builder_through_registered_agent_msg');
                                            $this->twilio->set_admin_id($assign_user_data[0]['id'],$database_name);
                                            $response = $this->twilio->sms($this->config->item('from_sms'),$phone_no,$message1);
                                        }
                                        
                                        if(!empty($cdata1['phone_data']) && !empty($assign_user_data[0]['id']))
                                        {
                                            foreach($cdata1['phone_data'] as $row)
                                            {
                                                if(!empty($row))
                                                {
                                                    //'+919033921029'
                                                    $this->twilio->set_admin_id($assign_user_data[0]['id'],$database_name);   
                                                    $response = $this->twilio->sms($this->config->item('from_sms'),$row,$message);
                                                    break;
                                                }
                                            }
                                        }
                                        
					/*-------------------------------------------------------------------------*/
					/*********************** Insert data into conatct master *******************/
					/*-------------------------------------------------------------------------*/
					
					$leadid=$lead_id;
					$formid=$cdata['form_id'];
					$fields1=array('id','form_widget_id','created_by');
					$matchf=array('id'=> $formid);
					$form_data = $this->lead_capturing_model->select_records($fields1,$matchf,'','=','','','','','',$database_name);
					
					$user=$data[0]['assign_user_id'];
					$match1=array('id'=> $leadid);
					$data1 = $this->lead_capturing_model->select_records1('',$match1,'','=','','','','','',$database_name);
					//pr($data1);exit;
					$created_by=$form_data[0]['created_by'];
					if(!empty($data1))
					{
						//data for contact master tabel
						$cdataname['first_name']=$data1[0]['first_name_data'];
						$cdataname['last_name']=$data1[0]['last_name_data'];
						$cdatasingleline1['single_line_data']=$data1[0]['single_line_data'];
						$cdatamultiline1['paragraph_data']=$data1[0]['paragraph_data'];
						$cdatamultiline1['price_range_from']=$data1[0]['price_range_from'];
						$cdatamultiline1['price_range_to']=$data1[0]['price_range_to'];
						$cdatamultiline1['house_style']=$data1[0]['house_style'];
						$cdatamultiline1['area_of_interest']=$data1[0]['area_of_interest'];
						$cdatamultiline1['square_footage']=$data1[0]['square_footage'];
						$cdatamultiline1['no_of_bedrooms']=$data1[0]['no_of_bedrooms'];
						$cdatamultiline1['no_of_bathrooms']=$data1[0]['no_of_bathrooms'];
						$cdatamultiline1['buyer_preferences_notes']=$data1[0]['buyer_preferences_notes'];
						//pr($cdatamultiline1);		exit;
						if(!empty($cdataname['first_name'])||!empty($cdataname['last_name'])||$cdatasingleline1['single_line_data']||$cdatamultiline1['paragraph_data'])
						{
							if(!empty($cdataname['first_name']))
							{
								$condata['first_name']=str_replace("{^}",',',($cdataname['first_name']));
							}
							if(!empty($cdataname['last_name']))
							{
								$condata['last_name']=str_replace("{^}",',',($cdataname['last_name']));
							}
							if(!empty($cdatasingleline1['single_line_data']))
							{
								$cdatasingleline['single_line_data']=str_replace("{^}",',',($cdatasingleline1['single_line_data']));
								$condata['notes'].= $cdatasingleline['single_line_data'];
							}
							if(!empty($cdatamultiline1['paragraph_data']))
							{
								$cdatamultiline['paragraph_data']=str_replace("{^}",',',($cdatamultiline1['paragraph_data']));
								$condata['notes'].= $cdatamultiline['paragraph_data'];
							}
							if(!empty($cdatamultiline1['price_range_from']))
								$condata['price_range_from']=str_replace("{^}",',',($cdatamultiline1['price_range_from']));
							if(!empty($cdatamultiline1['price_range_to']))
								$condata['price_range_to']=str_replace("{^}",',',($cdatamultiline1['price_range_to']));
							if(!empty($cdatamultiline1['house_style']))
								$condata['house_style']=str_replace("{^}",',',($cdatamultiline1['house_style']));
							if(!empty($cdatamultiline1['area_of_interest']))
								$condata['area_of_interest']=str_replace("{^}",',',($cdatamultiline1['area_of_interest']));
							if(!empty($cdatamultiline1['square_footage']))
								$condata['square_footage']=str_replace("{^}",',',($cdatamultiline1['square_footage']));
							if(!empty($cdatamultiline1['no_of_bedrooms']))
								$condata['no_of_bedrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bedrooms']));
							if(!empty($cdatamultiline1['no_of_bathrooms']))
								$condata['no_of_bathrooms']=str_replace("{^}",',',($cdatamultiline1['no_of_bathrooms']));
							if(!empty($cdatamultiline1['buyer_preferences_notes']))
								$condata['buyer_preferences_notes']=str_replace("{^}",',',($cdatamultiline1['buyer_preferences_notes']));
							
							
							$condata['created_by'] = $created_by;
							$condata['created_date'] = date('Y-m-d H:i:s');		
							$condata['status'] = '1';
							$condata['created_type']='5';
							$condata['lead_id']=$leadid;
							$condata['form_id']=$cdata['form_id'];
							
							
							$lastId=$this->contacts_model->insert_record($condata,$database_name);
							//echo $this->db->last_query();
							
							unset($condata);
							
							if(!empty($data1[0]['form_id']) && !empty($lastId))
							{
								$lead_contact_type_list = $this->lead_capturing_model->select_lead_contact_trans_record($data1[0]['form_id'],$database_name);
								if(!empty($lead_contact_type_list[0]))
								{
									for($i=0;$i<count($lead_contact_type_list);$i++)
									{
											$trans_data['contact_id'] = $lastId;
											$trans_data['contact_type_id'] = $lead_contact_type_list[$i]['contact_type_id'];	
												
											$this->contacts_model->insert_contact_type_record($trans_data);
									}
								}
							}
							
							
							//contact_contacttype_trans
						//	pr($contact_type['lead_contact_type_trans']);exit;
							//End ..
							
							// Apply plan assignment //
							
							
							if(!empty($data1[0]['form_id']) && !empty($lastId))
							{
								$matchform =array('id'=>$data1[0]['form_id']);
								$formdataplan = $this->lead_capturing_model->select_records('',$matchform,'','=','','','','','',$database_name);
								
								//pr($formdataplan);exit;
								
								if(!empty($formdataplan[0]['assigned_interaction_plan_id']))
								{
									
									$interaction_plan_id = $formdataplan[0]['assigned_interaction_plan_id'];
									
									$contact_id = $lastId;
									
									////////////////////////////////////
									
									if($interaction_plan_id != '')
									{
										$match1 = array('id'=>$interaction_plan_id);
										$plandata = $this->interaction_plans_model->select_records('',$match1,'','=','','','','','',$database_name);
										
										//pr($plandata);
										
										$condata = $plandata[0];
										//pr($condata);exit;
										
										
										if(!empty($condata))
										{
											//echo $condata['plan_name'];
											$data_conv['contact_id'] = $contact_id;
											$data_conv['plan_id'] = $interaction_plan_id;
											$data_conv['plan_name'] = !empty($condata['plan_name'])?$condata['plan_name']:'';
											$data_conv['created_date'] = date('Y-m-d H:i:s');
											$data_conv['log_type'] = '2';
											$data_conv['created_by'] = $created_by;
											$data_conv['status'] = '1';
											
											//pr($data_conv);exit;
											
											$this->interaction_plans_model->insert_contact_converaction_trans_record($data_conv,$database_name);
											
											$icdata['interaction_plan_id'] = $interaction_plan_id;
											$icdata['contact_id'] = $contact_id;
											
											if($condata['plan_start_type'] == '1')
											{
												$icdata['start_date'] = date('Y-m-d');
												$icdata['plan_start_type'] = $condata['plan_start_type'];
												$icdata['plan_start_date'] = $condata['start_date'];
											}
											else
											{
												if(strtotime(date('Y-m-d')) < strtotime($condata['start_date']))
													$icdata['start_date'] = date('Y-m-d',strtotime($condata['start_date']));
												else
													$icdata['start_date'] = date('Y-m-d');
												
												$icdata['plan_start_type'] = $condata['plan_start_type'];
												$icdata['plan_start_date'] = $condata['start_date'];
											}
											
											$icdata['created_date'] = date('Y-m-d H:i:s');
											$icdata['created_by'] = $created_by;
											$icdata['status'] = '1';
											
											//pr($icdata);exit;
											
											$this->interaction_plans_model->insert_contact_trans_record($icdata,$database_name);
											
											///////////////////////////////////////////////////////////////////////////
											
											$plan_id = $interaction_plan_id;
														
											////////////////////////////////////////////////////////
											
											$table = $database_name.".interaction_plan_interaction_master as ipim";
											$fields = array('ipim.*','ipptm.name','au.name as admin_name','ipm.description as interaction_name');
											$join_tables = array(
																$database_name.'.interaction_plan__plan_type_master as ipptm' => 'ipptm.id = ipim.interaction_type',
																$database_name.'.admin_users as au' => 'au.id = ipim.created_by',
																$database_name.'.interaction_plan_interaction_master as ipm' => 'ipm.id = ipim.interaction_id'
																);
											
											$group_by='ipim.id';
											
											$where1 = array('ipim.interaction_plan_id'=>$plan_id);
											
											$interaction_list =$this->interaction_plans_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','interaction_sequence_date','asc',$group_by,$where1);
											
											
											///////////////////////// Add New Contacts Interaction Plan-Interactions Transaction /////////////////////////////
									
											//pr($interaction_list);exit;
											
											if(count($interaction_list) > 0)
											{
												foreach($interaction_list as $row1)
												{
													$assigned_user_id = !empty($row1['assign_to'])?$row1['assign_to']:0;
									
													//////////////////// Integrate User Work time config ///////////////////
													
													if(!empty($assigned_user_id))
													{
													
														//echo $assigned_user_id;
														
														//Get Working Days
														
														//$new_user_id = $this->user_management_model->get_user_id_from_login($assigned_user_id);
														$new_user_id = $assigned_user_id;
														
														//echo $new_user_id;
														
														$match = array("user_id"=>$new_user_id);
														$worktimedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$database_name.'.work_time_config_master');
														
														//pr($worktimedata);exit;
														
														$match = array("user_id"=>$new_user_id,"rule_type"=>1);
														$worktimespecialdata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$database_name.'.work_time_special_rules');
														
														//pr($worktimespecialdata);exit;
														
														$match = array("user_id"=>$new_user_id);
														$worktimeleavedata = $this->work_time_config_master_model->select_records1('',$match,'','=','','','','id','desc',$database_name.'.user_leave_data');
														
														//pr($worktimeleavedata);exit;
														
														$user_work_off_days1 = array();
														
														if(!empty($worktimedata[0]['id']))
														{
															if(empty($worktimedata[0]['if_mon']))
																$user_work_off_days1[] = 'Mon';
															if(empty($worktimedata[0]['if_tue']))
																$user_work_off_days1[] = 'Tue';
															if(empty($worktimedata[0]['if_wed']))
																$user_work_off_days1[] = 'Wed';
															if(empty($worktimedata[0]['if_thu']))
																$user_work_off_days1[] = 'Thu';
															if(empty($worktimedata[0]['if_fri']))
																$user_work_off_days1[] = 'Fri';
															if(empty($worktimedata[0]['if_sat']))
																$user_work_off_days1[] = 'Sat';
															if(empty($worktimedata[0]['if_sun']))
																$user_work_off_days1[] = 'Sun';
														}
														
														$special_days1 = array();
														
														if(!empty($worktimespecialdata))
														{
															foreach($worktimespecialdata as $row2)
															{
																$day_string = '';
																if(!empty($row2['nth_day']) && !empty($row2['nth_date']))
																{
																	switch($row2['nth_day'])
																	{
																		case 1:
																		$day_string .= 'First ';
																		break;
																		case 2:
																		$day_string .= 'Second ';
																		break;
																		case 3:
																		$day_string .= 'Third ';
																		break;
																		case 4:
																		$day_string .= 'Fourth ';
																		break;
																		case 5:
																		$day_string .= 'Last ';
																		break;
																	}
																	switch($row2['nth_date'])
																	{
																		case 1:
																		$day_string .= 'Day';
																		break;
																		case 2:
																		$day_string .= 'Weekday';
																		break;
																		case 3:
																		$day_string .= 'Weekend';
																		break;
																		case 4:
																		$day_string .= 'Monday';
																		break;
																		case 5:
																		$day_string .= 'Tuesday';
																		break;
																		case 6:
																		$day_string .= 'Wednesday';
																		break;
																		case 7:
																		$day_string .= 'Thursday';
																		break;
																		case 8:
																		$day_string .= 'Friday';
																		break;
																		case 9:
																		$day_string .= 'Saturday';
																		break;
																		case 10:
																		$day_string .= 'Sunday';
																		break;
																		default:
																		break;
																	}
																	
																	$special_days1[] = $day_string;
																	
																}
															}
														}
														
														$leave_days1 = array();
														
														foreach($worktimeleavedata as $row2)
														{
															if(!empty($row2['from_date']))
															{
																$leave_days1[] = $row2['from_date'];
																if(!empty($row2['to_date']))
																{
																	
																	//$from_date = date('Y-m-d',strtotime($row['from_date']));
																	
																	$from_date = date('Y-m-d', strtotime($row2['from_date'] . ' + 1 day'));
																	
																	$to_date = date('Y-m-d',strtotime($row2['to_date']));
																	
																	//echo $from_date."-".$to_date;
																	
																	while($from_date <= $to_date)
																	{
																		$leave_days1[] = $from_date;
																		$from_date = date('Y-m-d', strtotime($from_date . ' + 1 day'));
																	}
																}
															}
														}
														
														//pr($user_work_off_days1);
														
														//pr($special_days1);
														
														//pr($leave_days1);
														
													}
															
													////////////////////////////////////////////////////////////////////////
													
													$iccdata['interaction_plan_id'] = $plan_id;
													$iccdata['contact_id'] = $contact_id;
													$iccdata['interaction_plan_interaction_id'] = $row1['id'];
													$iccdata['interaction_type'] = $row1['interaction_type'];
													
													if($row1['start_type'] == '1')
													{
														$count = $row1['number_count'];
														$counttype = $row1['number_type'];
														
														///////////////////////////////////////////////////////////////
														
														$match = array('interaction_plan_id'=>$plan_id,'contact_id'=>$contact_id);
														$plan_contact_data = $this->interaction_plans_model->select_records_plan_contact_trans('',$match,'','=','','','','','',$database_name);
														
														//pr($plan_contact_data);exit;
														
														///////////////////////////////////////////////////////////////
														
														if(!empty($plan_contact_data[0]['start_date']))
														{
															$newtaskdate = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
															
															$newtaskdate1 = date("Y-m-d",strtotime($plan_contact_data[0]['start_date']."+ ".$count." ".$counttype));
												
															////////////////////////////////////////////////////////
															
															$repeatoff = 1;
															
															while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
															{
																// Check for Work off days
																// echo $newtaskdate;
																$day_of_date = date('D', strtotime($newtaskdate1));
																$new_special_days = array();
																
																if(!empty($special_days1))
																{
																	foreach($special_days1 as $mydays)
																	{
																		if (strpos($mydays,'Weekday') !== false) {
																			$nthday = explode(" ",$mydays);
																			if(!empty($nthday[0]))
																			{
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
																			}
																		}
																		elseif (strpos($mydays,'Weekend') !== false) {
																			$nthday = explode(" ",$mydays);
																			if(!empty($nthday[0]))
																			{
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
																			}
																		}
																		else
																			$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
																	}
																}
																
																//pr($new_special_days);
																
																if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
																{
																	//echo 'work off'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
																{
																	//echo 'special days'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
																{
																	//echo 'leave'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																else
																{
																	//echo 'else';
																	$repeatoff = 0;
																	$newtaskdate = $newtaskdate1;
																}
																
																//echo $repeatoff;
																
															}
															
															//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
															
															///////////////////////////////////////////////////////
															
															$iccdata['task_date'] = $newtaskdate;
														}
													}
													elseif($row1['start_type'] == '2')
													{
														$count = $row1['number_count'];
														$counttype = $row1['number_type'];
														
														$interaction_id = $row1['interaction_id'];
														
														$interaction_res = $this->interaction_model->get_contact_interaction_task_date($interaction_id,$contact_id,$database_name);
														
														//pr($interaction_res);
														
														//echo $interaction_res->task_date;
														
														if(!empty($interaction_res->task_date))
														{
															$newtaskdate = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
															
															$newtaskdate1 = date("Y-m-d",strtotime($interaction_res->task_date."+ ".$count." ".$counttype));
													
															////////////////////////////////////////////////////////
															
															$repeatoff = 1;
															
															while($repeatoff > 0 && ($newtaskdate1 < date("Y-m-d",strtotime($newtaskdate."+ 1 year"))))
															{
																// Check for Work off days
																// echo $newtaskdate;
																$day_of_date = date('D', strtotime($newtaskdate1));
																$new_special_days = array();
																
																if(!empty($special_days1))
																{
																	foreach($special_days1 as $mydays)
																	{
																		if (strpos($mydays,'Weekday') !== false) {
																			$nthday = explode(" ",$mydays);
																			if(!empty($nthday[0]))
																			{
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Monday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Tuesday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Wednesday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Thursday of '.date('F o', strtotime($newtaskdate1))));
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Friday of '.date('F o', strtotime($newtaskdate1))));
																			}
																		}
																		elseif (strpos($mydays,'Weekend') !== false) {
																			$nthday = explode(" ",$mydays);
																			if(!empty($nthday[0]))
																			{
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Saturday of '.date('F o', strtotime($newtaskdate1))));
					
																				$new_special_days[] = date('Y-m-d', strtotime($nthday[0].' Sunday of '.date('F o', strtotime($newtaskdate1))));
																			}
																		}
																		else
																			$new_special_days[] = date('Y-m-d', strtotime($mydays.' of '.date('F o', strtotime($newtaskdate1))));
																	}
																}
																
																//pr($new_special_days);
																
																if(!empty($user_work_off_days1) && in_array($day_of_date,$user_work_off_days1))
																{
																	//echo 'work off'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																elseif(!empty($new_special_days) && in_array($newtaskdate1,$new_special_days))
																{
																	//echo 'special days'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																elseif(!empty($leave_days1) && in_array($newtaskdate1,$leave_days1))
																{
																	//echo 'leave'."<br>";
																	$newtaskdate1 = date("Y-m-d",strtotime($newtaskdate1."+ 1 Day"));
																}
																else
																{
																	//echo 'else';
																	$repeatoff = 0;
																	$newtaskdate = $newtaskdate1;
																}
																
																//echo $repeatoff;
																
															}
															
															//while ($repeatoff > 0 || ($newtaskdate1 > date("Y-m-d",strtotime($newtaskdate."+ 1 year"))));
															
															///////////////////////////////////////////////////////
															
															//echo $newtaskdate;
															
															//$icdata['task_date'] = $newtaskdate;
														
															$iccdata['task_date'] = $newtaskdate;
														}
														
													}
													else
													{
														$iccdata['task_date'] = date('Y-m-d',strtotime($row1['start_date']));
													}
													
													$sendemaildate = $iccdata['task_date'];
													
													$iccdata['created_date'] = date('Y-m-d H:i:s');
													$iccdata['created_by'] = $created_by;
													
													$this->interaction_model->insert_contact_communication_record($iccdata,$database_name);
													//echo $this->db->last_query();exit;
													unset($iccdata);
													unset($user_work_off_days1);
													unset($special_days1);
													unset($leave_days1);
													
													/* Email campaign/SMS campaign Insert */
													$match = array('id'=>$contact_id);
													$userdata = $this->contacts_model->select_records('',$match,'','=','','','','','','',$database_name);
													$agent_name = '';
													if(count($userdata) > 0)
													{
														if(!empty($userdata[0]['created_by']))
														{
															$table =$database_name.".login_master as lm";   
															$fields = array('lm.admin_name,um.first_name,um.middle_name,um.last_name,lm.user_type');
															$join_tables = array($database_name.'.user_master as um'=>'lm.user_id = um.id');
															$wherestring = 'lm.id = '.$userdata[0]['created_by'];
															$agent_datalist = $this->lead_capturing_model->getmultiple_tables_records($table,$fields,$join_tables,'left','','','','','','','','',$wherestring);
															
															
															if(!empty($agent_datalist))
															{
																if(!empty($agent_datalist[0]['user_type']) && ($agent_datalist[0]['user_type'] == 2 || $agent_datalist[0]['user_type'] == 5))
																	$agent_name = $agent_datalist[0]['admin_name'];
																else
																	$agent_name = trim($agent_datalist[0]['first_name']).' '.trim($agent_datalist[0]['middle_name']).' '.trim($agent_datalist[0]['last_name']);
															}
														}
													}
													
													if(($row1['interaction_type'] == 6 || $row1['interaction_type'] == 8) && count($userdata) > 0)
													{	
														//$row1['id'];
														$match = array('interaction_id'=>$row1['id']);
														$campaigndata = $this->email_campaign_master_model->select_records('',$match,'','=','','','','',$database_name);
														if(count($campaigndata) > 0)
														{
															$condata1['email_campaign_id'] = $campaigndata[0]['id'];
															$condata1['contact_id'] = $contact_id;
															$emaildata = array(
																			'Date'=>date('Y-m-d'),
																			'Day'=>date('l'),
																			'Month'=>date('F'),
																			'Year'=>date('Y'),
																			'Day Of Week'=>date("w",time()),
																			'Agent Name'=>$agent_name,
																			'Contact First Name'=>$userdata['first_name'],
																			'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																			'Contact Last Name'=>$userdata['last_name'],
																			'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																			'Contact Company Name'=>$userdata['company_name']
																			);
													
															$content = $campaigndata[0]['email_message'];
															$title = $campaigndata[0]['template_subject'];
															
															//pr($emaildata);
															
															$condata1['template_subject'] = $title;
															$condata1['email_message'] = $content;
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
																
																$condata1['template_subject'] = $finaltitle;
																$finlaOutput = $output;
																$condata1['email_message'] = $finlaOutput;
															}
															
															//$emaildata['interaction_id'] = $row1['id'];
															$condata1['send_email_date'] = !empty($sendemaildate)?$sendemaildate:'';
															$this->email_campaign_master_model->insert_email_campaign_recepient_trans($condata1,$database_name);
															//echo $this->db->last_query();
														}
													}
													elseif($row1['interaction_type'] == 3 && count($userdata) > 0)
													{
														$match = array('interaction_id'=>$row1['id']);
														$smscampaigndata = $this->sms_campaign_master_model->select_records('',$match,'','=','','','','','',$database_name);
														if(count($smscampaigndata) > 0)
														{
															$condata1['sms_campaign_id'] = $smscampaigndata[0]['id'];
															$condata1['contact_id'] = $contact_id;
															$emaildata = array(
																			'Date'=>date('Y-m-d'),
																			'Day'=>date('l'),
																			'Month'=>date('F'),
																			'Year'=>date('Y'),
																			'Day Of Week'=>date("w",time()),
																			'Agent Name'=>$agent_name,
																			'Contact First Name'=>$userdata['first_name'],
																			'Contact Spouse/Partner First Name'=>$userdata['spousefirst_name'],
																			'Contact Last Name'=>$userdata['last_name'],
																			'Contact Spouse/Partner Last Name'=>$userdata['spouselast_name'],
																			'Contact Company Name'=>$userdata['company_name']
																			);
													
															$content = $smscampaigndata[0]['sms_message'];
															$condata1['sms_message'] 	= $content;
															$pattern = "{(%s)}";
															
															$map = array();
															
															if($emaildata != '' && count($emaildata) > 0)
															{
																foreach($emaildata as $var => $value)
																{
																	$map[sprintf($pattern, $var)] = $value;
																}
																$output = strtr($content, $map);
																
																$finlaOutput = $output;
																$condata1['sms_message'] = $finlaOutput;
															}
															//$smsdata['interaction_id'] = $row1['id'];
															$condata1['send_sms_date'] = !empty($sendemaildate)?$sendemaildate:'';
															$this->sms_campaign_master_model->insert_sms_campaign_recepient_trans($condata1,$database_name);
														}
														
														
													}
													unset($condata1);
													/* END */
													
												}
											}
											
											///////////////////////////////////////////////////////////////////////////
											unset($icdata);
											//exit;
											
										
										}
										
									}
									
									////////////////////////////////////
									
								}
								
							}
							
							//Assign plan to user
							if(!empty($user))
							{
								$contact_id=$lastId;
								$check_contact_id=$lastId;
								$match=array('contact_id'=>$check_contact_id,'user_id'=>$user);
								$contact_check = $this->contact_masters_model->select_records1('',$match,'','=','','','','','asc', $database_name.'.user_contact_trans');
								//pr($contact_check);exit;
								if(empty($contact_check))
								{
									$data_conver['contact_id']=$lastId;
									$data_conver['created_by'] = $created_by;
									$data_conver['assign_to'] = $user; 
									$data_conver['log_type'] = '3';// Log Type 3 -assign contact To User
									$data_conver['created_date'] = date('Y-m-d H:i:s');
									$data_conver['status'] = '1';
									$this->contacts_model->insert_contact_conversation($data_conver,$database_name);
									unset($data_conver);
								}
							}
							///////////////////////////
							
							
						}
						if(!empty($lastId))
						{
							//user assign lead in user contact transcation
							if(!empty($user))
							{
								$cdata6['user_id']=$user;
								$cdata6['contact_id']=$lastId;
								$cdata6['created_by']=$created_by;
								$cdata6['created_date'] = date('Y-m-d H:i:s');		
								$cdata6['status'] = '1';
								$this->contacts_model->insert_user_contact_trans_record($cdata6,$database_name);
							}
							//data for contact address transction 
							$cdatacontact1['address_line1']=$data1[0]['address_data'];	
							if(!empty($cdatacontact1['address_line1']))
							{
								$cdatacontact['address_line1']=explode("{^}",($cdatacontact1['address_line1']));	
								for($i=0;$i<count($cdatacontact['address_line1']);$i++)
								{
									$condata1['contact_id']=$lastId;
									$condata1['address_line1']=$cdatacontact['address_line1'][$i];
									$condata1['status'] = '1';
									$this->contacts_model->insert_address_trans_record($condata1,$database_name);
								}
							}
							//data for contact emails transcation table
							$cdataemail1['email_address']=$data1[0]['email_data'];
							if(!empty($cdataemail1['email_address']))
							{	
								$cdataemail['email_address']=explode("{^}",($cdataemail1['email_address']));	
								for($i=0;$i<count($cdataemail['email_address']);$i++)
								{
									$cdata2['contact_id']=$lastId;
									$cdata2['email_address']=$cdataemail['email_address'][$i];
									$cdata2['status'] = '1';
									if($i==0)
										$cdata2['is_default'] = '1';
									$this->contacts_model->insert_email_trans_record($cdata2,$database_name);
									unset($cdata2);
								}
							}
							//data for contact phone transcation table
							$cdataphone1['phone_no']=$data1[0]['phone_data'];
							if(!empty($cdataphone1['phone_no']))
							{
								$cdataphone['phone_no']=explode("{^}",($cdataphone1['phone_no']));
								for($i=0;$i<count($cdataphone['phone_no']);$i++)
								{
									$cdata3['contact_id']=$lastId;
									$cdata3['phone_no']=$cdataphone['phone_no'][$i];
									$cdata3['status'] = '1';
									if($i==0)
										$cdata3['is_default'] = '1';
									$this->contacts_model->insert_phone_trans_record($cdata3,$database_name);
									unset($cdata3);
								}
							}
							//data for contact website transcation table
							$cdatacontact1['website_name']=$data1[0]['website_data'];
							if(!empty($cdatacontact1['website_name']))
							{
								$cdatacontact['website_name']=explode("{^}",($cdatacontact1['website_name']));
								for($i=0;$i<count($cdatacontact['website_name']);$i++)
								{
									$cdata4['contact_id']=$lastId;
									$cdata4['website_name']=$cdatacontact['website_name'][$i];
									$cdata4['status'] = '1';
									$this->contacts_model->insert_website_trans_record($cdata4,$database_name);
								}
							}
							//data for contact document transcation table
							$cdataemail1['file_name']=$data1[0]['file_name'];
							if(!empty($cdataemail1['file_name']))
							{	
								$cdataemail['doc_file']=explode("{^}",($cdataemail1['file_name']));	
								for($i=0;$i<count($cdataemail['doc_file']);$i++)
								{
									$cdata7['contact_id'] = $lastId;
									$cdata7['doc_file'] = $cdataemail['doc_file'][$i];
									$cdata7['created_date'] = date('Y-m-d H:i:s');
									$cdata7['modified_date'] = date('Y-m-d H:i:s');
									$cdata7['status'] = '1';
									//pr($cdata6);exit;
									$this->contacts_model->insert_doc_trans_record($cdata7,$database_name);
									unset($cdata7);
								}
							}
							
							//update lead status as assigned
							$cdata5['id']=$leadid;
							$cdata5['status']=1;
							$this->lead_capturing_model->update_record1($cdata5,$database_name);
						}	}	
					
					
					/*-------------- End insert contact ---------------*/
					
					$data['form_data_message'] =$data;
					$this->load->view($this->viewName."/formmessage",$data);	
				}
			}
			else
			{
				echo "Something went wrong";exit;
			}
		}
		else
		{
			echo "Something went wrong";exit;
		}
     }

     public function view_record()
     {
     	$this->db->select('*');
		$this->db->from('form_builder_called_from_ip');
		//$this->db->where(array('form_widget_id'=>$fullwidget_id));
		$query = $this->db->get();
		$data['form_data'] = $query->result_array();
		pr($data);exit;
     }

}