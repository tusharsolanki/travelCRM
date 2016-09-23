<?php
/*
    @Description: Admin Model
    @Author: Jayesh Rojasara
    @Date: 07-05-14
*/

class Admin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'login_master';
    }
    /*
        @Description: Check Login is valid or not
        @Author     : Jayesh Rojasara
        @Input      : User Email id and Password
        @Output     : If validate then go to home page else login error
        @Date       : 06-05-14
    */  
    
    public function check_email($email, $id)
    {
			$param_selfold = array('email_id'=>$email);
            $this->db->select();
            $this->db->from($this->table_name);
            $this->db->where($param_selfold);
			$this->db->where('id !=',$id);
            $query= $this->db->get();
		    return $query->result_array();
	}
    /*
        @Description: Function for get User List (Customer)
        @Author     : Jayesh Rojasara
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : User details
        @Date       : 07-05-14
    */
   
    public function get_user($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='',$db_name='',$totalrows='')
    {
		//pr($getfields);exit;
	    $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
		if(!empty($db_name))
			$table = $db_name.".".$this->table_name;
		else
			$table = $this->table_name;
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$table;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE (';
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0].' '.$compare_type .'"%'.$match_values[$keys[0]].'%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0].' '.$compare_type .'"'.$match_values[$keys[0]].'" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);
            
            foreach($match_values as $key=>$value)
            {                
                $where .= $and_or.' '.$key.' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type .'"%'.$value.'%"';
                        break;
                    
                    case '=':
                    default:
                        $where .= $compare_type .'"'.$value.'"';
                        break;
                }
            }
			
			$where .= ')';
			
			if($where_cond)
        	{
				foreach($where_cond as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' = "'.$value.'")';
				}
			}
			
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) as total_count FROM '.$table.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        if(!empty($totalrows))
            return $query->num_rows();
        else
            return $query->result_array();
    }
   
    /*
        @Description: Function is for Insert user details
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Insert record into DB
        @Date       :07-05-14
    */
    function insert_user($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
	}
    /*
        @Description: Function is for update user details by Admin
        @Author     : Jayesh Rojasara
        @Input      : user details
        @Output     : Update record into db
        @Date: 	07-05-14
    */
    public function update_user($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name))
			$query = $this->db->update($db_name.".".$this->table_name,$data);
		else
        	$query = $this->db->update($this->table_name,$data); 
    }
    
    /*
        @Description: Function is for update user details
        @Author     : Sanjay Moghariya
        @Input      : user details
        @Output     : Update record into db
        @Date       : 13-03-2015
    */
    public function update_user_child($passdata,$otherdata)
    {
        $this->db->where('email_id',$otherdata[0]['email_id']);
        $this->db->where('user_type',$otherdata[0]['user_type']);
        if(!empty($otherdata[0]['db_name']))
            $query = $this->db->update($otherdata[0]['db_name'].".".$this->table_name,$passdata);
        else
            $query = $this->db->update($this->table_name,$passdata); 
    }
    /*
        @Description: Function for Delete Customer Profile By Admin
        @Author     : Jayesh Rojasara
        @Input      : user id
        @Output     : Delete record from db
        @Date       : 07-05-14
    */
    public function delete_user($id)
    {
		$this->load->dbforge();
		
		$this->db->where('id',$id);
        $query = $this->db->get($this->table_name);
		$result = $query->result_array();
		
		if(!empty($result[0]['db_name']))
		{
			
			// Delete all contacts Pic
			
			$this->db->select('contact_pic');
			$contact_pic_data = $this->db->get($result[0]['db_name'].'.contact_master')->result_array();
			if(!empty($contact_pic_data))
			{
				$bgImgPath = $this->config->item('contact_big_img_path');
				$smallImgPath = $this->config->item('contact_small_img_path');
				foreach($contact_pic_data as $row)
				{
					if(!empty($row['contact_pic']))
					{
						unlink($bgImgPath.$row['contact_pic']);
						unlink($smallImgPath.$row['contact_pic']);
					}
				}
			}
			
			// Delete all contacts Doc
			
			$this->db->select('doc_file');
			$contact_doc_data = $this->db->get($result[0]['db_name'].'.contact_documents_trans')->result_array();
			if(!empty($contact_doc_data))
			{
				$docPath = $this->config->item('admin_big_img_path');
				foreach($contact_doc_data as $row)
				{
					if(!empty($row['doc_file']))
					{
						unlink($docPath.$row['doc_file']);
					}
				}
			}
			
			// Delete admin Pic
			
			$this->db->select('admin_pic');
			$admin_pic_data = $this->db->get($result[0]['db_name'].'.login_master')->result_array();
			if(!empty($admin_pic_data))
			{
				$bgImgPath = $this->config->item('admin_big_img_path');
				$smallImgPath = $this->config->item('admin_small_img_path');
				foreach($admin_pic_data as $row)
				{
					if(!empty($row['admin_pic']))
					{
						unlink($bgImgPath.$row['admin_pic']);
						unlink($smallImgPath.$row['admin_pic']);
					}
				}
			}
			
			// Delete user Pic
			
			$this->db->select('contact_pic');
			$user_pic_data = $this->db->get($result[0]['db_name'].'.user_master')->result_array();
			if(!empty($user_pic_data))
			{
				$bgImgPath = $this->config->item('user_big_img_path');
				$smallImgPath = $this->config->item('user_small_img_path');
				foreach($user_pic_data as $row)
				{
					if(!empty($row['contact_pic']))
					{
						unlink($bgImgPath.$row['contact_pic']);
						unlink($smallImgPath.$row['contact_pic']);
					}
				}
			}
			
			// Delete listing Pic
			
			$this->db->select('photo');
			$listing_pic_data = $this->db->get($result[0]['db_name'].'.property_listing_photo_trans')->result_array();
			if(!empty($listing_pic_data))
			{
				$bgImgPath = $this->config->item('listing_big_img_path');
				$smallImgPath = $this->config->item('listing_small_img_path');
				foreach($listing_pic_data as $row)
				{
					if(!empty($row['photo']))
					{
						unlink($bgImgPath.$row['photo']);
						unlink($smallImgPath.$row['photo']);
					}
				}
			}
			
			// Delete all listing Doc
			
			$this->db->select('doc_file');
			$listing_doc_data = $this->db->get($result[0]['db_name'].'.property_listing_document_trans')->result_array();
			if(!empty($contact_doc_data))
			{
				$docPath = $this->config->item('listing_documents_big_img_path');
				foreach($listing_doc_data as $row)
				{
					if(!empty($row['doc_file']))
					{
						unlink($docPath.$row['doc_file']);
					}
				}
			}
			
			// Delete all contact csv
			
			$this->db->select('csv_file');
			$contact_csv_data = $this->db->get($result[0]['db_name'].'.contact_csv_master')->result_array();
			if(!empty($contact_csv_data))
			{
				$docPath = $this->config->item('contact_documents_csv_path');
				foreach($contact_csv_data as $row)
				{
					if(!empty($row['csv_file']))
					{
						unlink($docPath.$row['csv_file']);
					}
				}
			}
			
			// Delete all Email attachments
			
			$this->db->select('attachment_name');
			$email_attach_data = $this->db->get($result[0]['db_name'].'.email_campaign_attachments')->result_array();
			if(!empty($email_attach_data))
			{
				$docPath = $this->config->item('upload_file');
				foreach($email_attach_data as $row)
				{
					if(!empty($row['attachment_name']))
					{
						unlink($docPath.$row['attachment_name']);
					}
				}
			}
			
			/*
			
			pr($contact_pic_data);
			pr($contact_doc_data);
			pr($admin_pic_data);
			pr($user_pic_data);
			pr($listing_pic_data);
			pr($listing_doc_data);
			pr($contact_csv_data);
			pr($email_attach_data);
			
			exit;
			
			*/
			
			if ($this->dbforge->drop_database($result[0]['db_name']))
			{
				$this->db->where('db_name',$result[0]['db_name']);
				$this->db->delete($this->table_name);
				
				$hostname = $this->config->item('root_host_name_for_create_db_user');
				
				//db_user_name
				$query = "GRANT USAGE ON *.* TO '".$result[0]['db_user_name']."'@'".$hostname."'";
				//$query = "GRANT USAGE ON *.* TO '".$result[0]['db_user_name']."'@'%'";
				$query = $this->db->query($query);
				$query1 = "DROP USER '".$result[0]['db_user_name']."'@'".$hostname."' ";
				$query1 = $this->db->query($query1);
			}
		}
		else
			echo "Database not exist.";
		
    }    
	
	/*
        @Description: Function to fetch new DB Name
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function getnewdbname()
	{
		$this->db->select('max(id) as max_id');
		$this->db->from('login_master');
		$query= $this->db->get();
	    $result = $query->result_array();
		
		if(count($result) > 0)
			return $result[0]['max_id'];
		else
			return 1;
	}
	
	/*
        @Description: Function to create new db
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function createnewdb($databasename='')
	{
		if($databasename != '')
		{
			$this->load->dbforge();
			if ($this->dbforge->create_database($databasename))
				return 1;
			else
				return 0;
		}
		else
			return 0;
	}
	
	/*
        @Description: Function to create new Mysql user and grant rights
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique User Name
        @Date       : 08-09-14
    */
	public function createnewdbuser($username='',$databasename='')
	{
		
		$query = "SELECT User FROM mysql.user where User = '".$username."'";
		$query = $this->db->query($query);
		$result_data = $query->result_array();
		
		if(empty($result_data))
		{
		
			// For password 41 char hash is required.
			
			/*$querypswd = "SELECT PASSWORD('".$username."') as u_pswd";
			$querypswd = $this->db->query($querypswd);
			$newpswd = $querypswd->result_array();*/
			
			//pr($newpswd);exit;
			
			//$dbuserpswd = !empty($newpswd[0]['u_pswd'])?$newpswd[0]['u_pswd']:'';
			$dbuserpswd = $username;
			
			$hostname = $this->config->item('root_host_name_for_create_db_user');
			
			//$query = "CREATE USER '".$username."'@'".$hostname."' IDENTIFIED BY PASSWORD '".$dbuserpswd."'";
			//$query = "CREATE USER '".$username."'@'%' IDENTIFIED BY PASSWORD '".$dbuserpswd."'";
			$query = "CREATE USER '".$username."'@'".$hostname."' IDENTIFIED BY '".$dbuserpswd."'";
			$query = $this->db->query($query);
			
			//$query1 = "GRANT ALL PRIVILEGES ON ".$databasename.". * TO  '".$username."'@'".$hostname."' IDENTIFIED BY PASSWORD '".$dbuserpswd."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
			//$query1 = "GRANT ALL PRIVILEGES ON * . * TO  '".$username."'@'%' IDENTIFIED BY PASSWORD '".$dbuserpswd."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
			//$query1 = "GRANT ALL PRIVILEGES ON ".$databasename.". * TO  '".$username."'@'".$hostname."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
			$query1 = "GRANT ALL PRIVILEGES ON * . * TO  '".$username."'@'".$hostname."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
			$query1 = $this->db->query($query1);
		
			//return $query."-".$query1;
		}
	}
	
	/*
        @Description: Function to copy one db to another
        @Author     : Nishit Modi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 08-09-14
    */
	public function copyonedbtoother($parent_db='',$child_db='',$lastId='',$databaseusername='')
	{
		if($parent_db != '' && $child_db != '')
		{
			////////////////////////////////////
			
			$config['hostname'] = $this->config->item('root_host_name');
			$config['username'] = $this->config->item('root_user_name');
			//$config['password'] = "ToPs@tops$$";	//For topsdemo.in
			$config['password'] = $this->config->item('root_password');	//Local
			$config['database'] = $parent_db;
			$config['dbdriver'] = "mysql";
			$config['dbprefix'] = "";
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			
			// or as gorelative notes, to access multiple databases:
			$DB_another = $this->load->database($config, TRUE);
			// and so on
			
			// connect to the database
			
			//echo "here1";
			
			//$DB_another = $this->load->database('anotherdb', TRUE); 
			
			$tables = $DB_another->list_tables();
			
			//echo "here2";pr($tables);exit;
			
			if(!empty($tables) && count($tables) > 0)
			{
				/*if($databaseusername != '')
				{
					$query_g = "GRANT ALL PRIVILEGES ON * . * TO  '".$databaseusername."'@'localhost' IDENTIFIED BY PASSWORD '".$dbuserpswd."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
					$query_g = $this->db->query($query_g);
				}*/
				
				foreach($tables as $row)
				{
					/*if($row == 'social_media_template_master' || $row == 'social_media_template_platform_trans' || $row == 'sms_text_template_master' || $row == 'phone_call_script_master')
					{}
					else
					{*/
						$sql = "CREATE TABLE ".$child_db.".".$row." LIKE ".$parent_db.".".$row; 
						$query = $this->db->query($sql);
					//}
					//echo $row.'<br>';
					if($row == 'login_master')
					{
						if($lastId != '')
						{
							//$sql_ins = "INSERT INTO ".$child_db.".login_master (user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status) SELECT user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status FROM ".$parent_db.".login_master where id  = ".$lastId.";";
                                                        $sql_ins = "INSERT INTO ".$child_db.".login_master (user_type, user_id, admin_name, email_id, address, phone,admin_pic ,brokerage_pic, user_license_no,number_of_users_allowed, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status, twilio_account_sid, twilio_auth_token, twilio_number,timezone,fb_api_key,fb_secret_key) SELECT user_type, user_id, admin_name, email_id, address, phone,admin_pic ,  brokerage_pic, user_license_no,number_of_users_allowed, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status, twilio_account_sid, twilio_auth_token, twilio_number,timezone,fb_api_key,fb_secret_key FROM ".$parent_db.".login_master where id  = ".$lastId.";";
							$query = $this->db->query($sql_ins);
						}
						//exit;
					}
					else
					{
						$not_tables = array(
							'admin_users',
							'calendar_master',
							'calendar_repeat_trans',
							'campaign',
							'campaign_blast',
							'campaign_type',
							'city',
							'contact_additionalfield_trans',
							'contact_address_trans',
							'contact_archive_master',
							'contact_chat_history',
							'contact_communication_plan_trans',
							'contact_contacttype_trans',
							'contact_contact_status_trans',
							'contact_conversations_trans',
							'contact_csv_master',
							'contact_documents_trans',
							'contact_emails_trans',
							'contact_master',
							'contact_phone_trans',
							'contact_social_trans',
							'contact_tag_trans',
							'contact_website_trans',
							'contact__csv_mapping_master',
							'contact__csv_mapping_trans',
							'countries',
							'country',
							'email_campaign_attachments',
							'email_campaign_master',
							'email_campaign_recepient_trans',
							'email_signature_master',
							'email_templates',
							'email_template_master',
							'envelope_template_master',
							'fb_chat_last_sync',
							'interaction_plan_adminuser_trans',
							'interaction_plan_contacts_trans',
							'interaction_plan_contact_activity_log_manual',
							'interaction_plan_contact_communication_plan',
							'interaction_plan_contact_personal_touches',
							'interaction_plan_interaction_master',
							'interaction_plan_interaction_master_premium',
							'interaction_plan_interaction_to_do',
							'interaction_plan_master',
							'interaction_plan_master_premium',
							'interaction_plan_time_trans',
							'joomla_mapping',
							'joomla_rpl_addon_multi_site',
							'joomla_rpl_bookmarks',
							'joomla_rpl_log',
							'joomla_rpl_savesearch',
							'joomla_rpl_track',
							'label_template_master',
							'lead_data',
							'lead_master',
							'letter_template_master',
							'phone_call_script_master',
							'property_listing_contact_trans',
							'property_listing_document_trans',
							'property_listing_master',
							'property_listing_offers_trans',
							'property_listing_open_houses_trans',
							'property_listing_photo_trans',
							'property_listing_price_change_trans',
							'property_listing_showings_trans',
							'sms_campaign_master',
							'sms_campaign_recepient_trans',
							'sms_text_template_master',
							'social_master',
							'social_media_template_master',
							'social_media_template_platform_trans',
							'social_platform_trans',
							'social_recepient_trans',
							'task_master',
							'task_user_transcation',
							'user_address_trans',
							'user_contact_trans',
							'user_emails_trans',
							'user_leave_data',
							'user_master',
							'user_package_trans',
							'user_phone_trans',
							'user_rights_trans',
							'user_rr_weightage_trans',
							'user_social_trans',
							'user_website_trans',
							'work_time_config_master',
							'work_time_special_rules',
							/*'contact__additionalfield_master',*/
							/*'contact__address_type_master',*/
							'contact__csv_mapping_master',
							'contact__csv_mapping_trans',
							/*'contact__document_type_master',*/
							/*'contact__email_type_master',*/
							/*'contact__method_master',*/
							/*'contact__phone_type_master',*/
							/*'contact__source_master',*/
							/*'contact__status_master',*/
							/*'contact__type_master',*/
							/*'contact__websitetype_master',*/
							'marketing_master_lib__category_master',
							'package_master',
							'user_package_trans',
							'contact_listing_last_seen',
							'user_right_transaction',
                            'error_data_master',
                            'mls_amenity_data',
							'mls_area_community_data',
							'mls_area_master',
							'mls_assign_data',
							'mls_child_table_mapping',
							'mls_csv_mapping_master',
							'mls_csv_mapping_trans',
							'mls_csv_master',
							'mls_last_updated_date_data',
							'mls_last_updated_offset_data',
							'mls_livewire_table_mapping',
							'mls_master',
							'mls_member_data',
							'mls_office_data',
							'mls_property_history_data',
							'mls_property_image',
							'mls_property_list_master',
							'mls_property_type',
							'mls_school_data',
							'mls_status_master',
							'mls_type_of_mls_mapping_master',
							'mls_type_of_mls_mapping_trans',
							'mls_type_of_mls_master',
							'sold_property_by_city',
							'sold_property_by_zip',
							'sold_property_city_status',
							'sold_property_zip_status',
							'home_report_mls',
							'home_report_zip_code',
							'home_report_zip_code',
							'child_admin_website',
							'child_website_agents',
							'child_website_agents_contact_info',
							'child_website_banner_master',
							'child_website_carousels_master',
							'child_website_carousels_property_type_trans',
							'child_website_carousels_trans',
							'child_website_domain_master',
							'child_website_footer_links',
							'child_website_home_social_links',
							'child_website_nearby_area',
							'cms_master',
							'child_website_cms_menu_trans',
							'lead_users',
							'blog_post',
							'blog_category_master',
							'blog_post_category_trans',
							'blog_post_comments',
							'joomla_rpl_property_valuation_searches',
							'joomla_rpl_valuation_contact',
							);
						
						//DO not insert data of above listed tables
						
						if(!in_array($row,$not_tables))
						{
							/*if($row == 'marketing_master_lib__category_master')
							{
								$sql_ins = "INSERT INTO ".$child_db.".marketing_master_lib__category_master (category, parent,superadmin_cat_id, created_date, created_by, modified_date, modified_by,status) SELECT category, parent,id, '".$now."' as created_date,'1' as created_by, modified_date, modified_by,status FROM ".$parent_db.".marketing_master_lib__category_master;";
								$query = $this->db->query($sql_ins);
//email								
								$sql_ins = "INSERT INTO ".$child_db.".email_template_master (template_name, template_category, template_subcategory, template_subject, email_message, email_send_type,email_event , is_unsubscribe,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT em.template_name, ccat.id, em.template_subcategory, em.template_subject, em.email_message,em.email_send_type , em.email_event, em.is_unsubscribe,em.id, '".$now."' as admin_publish_date, em.superadmin_publish_date,em.is_default,'1' as edit_flag, em.created_date,'1' as created_by,em.status FROM ".$parent_db.".email_template_master as em LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON em.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where em.is_default  = '1' and em.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//envlope								
								$sql_ins = "INSERT INTO ".$child_db.".envelope_template_master (template_name, template_category, template_subcategory, template_type, template_size_id, size_w,size_h , envelope_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT en.template_name, ccat.id, en.template_subcategory, en.template_type,en.template_size_id, en.size_w,en.size_h , en.envelope_content,en.id, '".$now."' as admin_publish_date, en.superadmin_publish_date,en.is_default,'1' as edit_flag, en.created_date,'1' as created_by,en.status FROM ".$parent_db.".envelope_template_master as en LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON en.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where en.is_default  = '1' and en.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//social medial								
								$sql_ins = "INSERT INTO ".$child_db.".social_media_template_master (template_name, template_category, template_subcategory, template_subject, post_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sm.template_name, ccat.id, sm.template_subcategory, sm.template_subject, sm.post_content,sm.id, '".$now."' as admin_publish_date, sm.superadmin_publish_date, sm.is_default,'1' as edit_flag,sm.created_date,'1' as created_by,sm.status FROM ".$parent_db.".social_media_template_master as sm LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sm.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where is_default  = '1' and publish_flag = '1';";
//social medial	trasection
								$query = $this->db->query($sql_ins);
$sql_ins1 = "INSERT INTO ".$child_db.".social_media_template_platform_trans (social_template_id, platform) SELECT sol.id, tsol1.platform FROM ".$child_db.".social_media_template_master as sol LEFT JOIN ".$parent_db.".social_media_template_master sol1 ON sol.superadmin_template_id  = sol1.id LEFT JOIN ".$parent_db.".social_media_template_platform_trans tsol1 ON tsol1.social_template_id  = sol1.id LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sol.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id;";
								$query = $this->db->query($sql_ins1);								
//phone								
$sql_ins = "INSERT INTO ".$child_db.".phone_call_script_master (template_name, template_category, template_subcategory, template_subject, calling_script,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT ph.template_name, ccat.id, ph.template_subcategory, ph.template_subject, ph.calling_script,ph.id, '".$now."' as admin_publish_date, ph.superadmin_publish_date,ph.is_default,'1' as edit_flag, ph.created_date,'1' as created_by,ph.status FROM ".$parent_db.".phone_call_script_master  as ph LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON ph.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id  where ph.is_default  = '1' and ph.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//sms								
								$sql_ins = "INSERT INTO ".$child_db.".sms_text_template_master (template_name, template_category, template_subcategory,  sms_message,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sms.template_name, ccat.id, sms.template_subcategory,sms.sms_message,sms.id, '".$now."' as admin_publish_date, sms.superadmin_publish_date,sms.is_default,'1' as edit_flag, sms.created_date,'1' as created_by,sms.status FROM ".$parent_db.".sms_text_template_master as sms LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sms.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where sms.is_default  = '1' and sms.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//label								
								$sql_ins = "INSERT INTO ".$child_db.".label_template_master (template_name, template_category, template_subcategory,template_type, size_type, size_w,size_h , label_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT la.template_name, ccat.id, la.template_subcategory,la.template_type, la.size_type, la.size_w,la.size_h , la.label_content,la.id, '".$now."' as admin_publish_date, la.superadmin_publish_date,la.is_default,'1' as edit_flag, la.created_date,'1' as created_by,la.status FROM ".$parent_db.".label_template_master as la LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON la.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where la.is_default  = '1' and la.publish_flag = '1';";
							$query = $this->db->query($sql_ins);
//letter						
							$sql_ins = "INSERT INTO ".$child_db.".letter_template_master (template_name, template_category, template_subcategory,size_w,size_h , template_subject,letter_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT let.template_name, ccat.id, let.template_subcategory,let.size_w,let.size_h,let.template_subject, let.letter_content,let.id, '".$now."' as admin_publish_date, let.superadmin_publish_date,let.is_default,'1' as edit_flag, let.created_date,'1' as created_by,let.status FROM ".$parent_db.".letter_template_master as let LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON let.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where let.is_default  = '1' and let.publish_flag = '1';";
							$query = $this->db->query($sql_ins);
//interection plan															
								$sql_ins = "INSERT INTO ".$child_db.". interaction_plan_master (plan_name, description, plan_status,target_audience,plan_start_type , start_date,created_date,created_by, modified_date, modified_by,p_p_id,by_superadmin,status) SELECT plan_name, description, plan_status,target_audience,plan_start_type , start_date, '".$now."' as created_date,'1' as created_by, modified_date, modified_by,id,'2' as by_superadmin,status FROM ".$parent_db.".interaction_plan_master_premium where status  = '1' and by_superadmin = '2';";
								$query = $this->db->query($sql_ins);
//interection								
								$sql_ins1 = "INSERT INTO ".$child_db.".interaction_plan_interaction_master (interaction_plan_id, interaction_type, description,assign_to,start_type , number_count,number_type,interaction_id,start_date, priority, drop_type,drop_after_day,drop_after_date,interaction_notes,template_category,template_subcategory,template_name,interaction_sequence_date,send_automatically,include_signature,created_date,created_by,modified_date,modified_by,status) SELECT cinpp.id, pint.interaction_type, pint.description,'1' as assign_to,pint.start_type , pint.number_count,pint.number_type,pint.interaction_id,pint.start_date, pint.priority, pint.drop_type,pint.drop_after_day,pint.drop_after_date,pint.interaction_notes,ccat.id,pint.template_subcategory,
(CASE 
	 WHEN pint.interaction_type = 6  THEN cemail.id
	 WHEN pint.interaction_type = 2  THEN cenv.id
	 WHEN pint.interaction_type = 4  THEN cpho.id
	 WHEN pint.interaction_type = 1  THEN clab.id
	 WHEN pint.interaction_type = 5  THEN clet.id
	 WHEN pint.interaction_type = 3  THEN csms.id
	 WHEN pint.interaction_type = 7  THEN 0
ELSE 0
END ) as template_name,pint.interaction_sequence_date,pint.send_automatically,pint.include_signature, '".$now."' as created_date,'1' as created_by,pint.modified_date,pint.modified_by,pint.status FROM ".$parent_db.".interaction_plan_interaction_master_premium as pint LEFT JOIN ".$parent_db.".interaction_plan_master_premium inpp ON pint.interaction_plan_id  = inpp.id LEFT JOIN ".$child_db.".interaction_plan_master cinpp ON cinpp.p_p_id  = inpp.id 
LEFT JOIN ".$parent_db.".envelope_template_master penv ON pint.template_name  = penv.id LEFT JOIN ".$child_db.".envelope_template_master cenv ON cenv.superadmin_template_id  = penv.id 
LEFT JOIN ".$parent_db.".email_template_master pemail ON pint.template_name  = pemail.id LEFT JOIN ".$child_db.".email_template_master cemail ON cemail.superadmin_template_id  = pemail.id
LEFT JOIN ".$parent_db.".social_media_template_master psoc ON pint.template_name  = psoc.id LEFT JOIN ".$child_db.".social_media_template_master csoc ON csoc.superadmin_template_id  = psoc.id 
LEFT JOIN ".$parent_db.".phone_call_script_master ppho ON pint.template_name  = ppho.id LEFT JOIN ".$child_db.".phone_call_script_master cpho ON cpho.superadmin_template_id  = ppho.id
LEFT JOIN ".$parent_db.".sms_text_template_master psms ON pint.template_name  = psms.id LEFT JOIN ".$child_db.".sms_text_template_master csms ON csms.superadmin_template_id  = psms.id 
LEFT JOIN ".$parent_db.".label_template_master plab ON pint.template_name  = plab.id LEFT JOIN ".$child_db.".label_template_master clab ON clab.superadmin_template_id  = plab.id 
LEFT JOIN ".$parent_db.".letter_template_master plet ON pint.template_name  = plet.id LEFT JOIN ".$child_db.".letter_template_master clet ON clet.superadmin_template_id  = plet.id
LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON pint.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id;";

								$query = $this->db->query($sql_ins1);
							}
							else if($row == 'email_template_master' || $row == 'envelope_template_master' || $row == 'social_media_template_master' || $row == 'social_media_template_platform_trans' || $row == 'phone_call_script_master' || $row == 'sms_text_template_master' || $row == 'label_template_master' || $row == 'letter_template_master' || $row == 'interaction_plan_master_premium' || $row == 'interaction_plan_interaction_master_premium' || $row == 'interaction_plan_master' || $row == 'interaction_plan_interaction_master' )
							{}
							else
							{*/
								$sql1 = "INSERT INTO ".$child_db.".".$row." SELECT * FROM ".$parent_db.".".$row;
								$query1 = $this->db->query($sql1);
							/*}*/
						}
					}
					
				}
				
				
				//Copy templte library and interection plan
				
								/*$sql = "CREATE TABLE ".$child_db.".social_media_template_master LIKE ".$parent_db.".social_media_template_master"; 
								$query = $this->db->query($sql);
								
								$sql = "CREATE TABLE ".$child_db.".social_media_template_platform_trans LIKE ".$parent_db.".social_media_template_platform_trans"; 
								$query = $this->db->query($sql);
								
								$sql = "CREATE TABLE ".$child_db.".sms_text_template_master LIKE ".$parent_db.".sms_text_template_master"; 
								$query = $this->db->query($sql);
								
								$sql = "CREATE TABLE ".$child_db.".phone_call_script_master LIKE ".$parent_db.".phone_call_script_master"; 
								$query = $this->db->query($sql);*/
								
								$now=date('Y-m-d H:i:s');
								$sql_ins = "INSERT INTO ".$child_db.".marketing_master_lib__category_master (category, parent,superadmin_cat_id, created_date, created_by, modified_date, modified_by,status) SELECT category, parent,id, '".$now."' as created_date,'1' as created_by, modified_date, modified_by,status FROM ".$parent_db.".marketing_master_lib__category_master;";
								$query = $this->db->query($sql_ins);
//email								
								$sql_ins = "INSERT INTO ".$child_db.".email_template_master (template_name, template_category, template_subcategory, template_subject, email_message,email_send_type,email_event , is_unsubscribe,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT em.template_name, ccat.id, em.template_subcategory, em.template_subject, em.email_message,em.email_send_type , em.email_event, em.is_unsubscribe,em.id, '".$now."' as admin_publish_date, em.superadmin_publish_date,em.is_default,'1' as edit_flag, em.created_date,'1' as created_by,em.status FROM ".$parent_db.".email_template_master as em LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON em.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where em.is_default  = '1' and em.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//envlope								
								$sql_ins = "INSERT INTO ".$child_db.".envelope_template_master (template_name, template_category, template_subcategory, template_type, template_size_id, size_w,size_h , envelope_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT en.template_name, ccat.id, en.template_subcategory, en.template_type,en.template_size_id, en.size_w,en.size_h , en.envelope_content,en.id, '".$now."' as admin_publish_date, en.superadmin_publish_date,en.is_default,'1' as edit_flag, en.created_date,'1' as created_by,en.status FROM ".$parent_db.".envelope_template_master as en LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON en.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where en.is_default  = '1' and en.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//social medial								
								$sql_ins = "INSERT INTO ".$child_db.".social_media_template_master (template_name, template_category, template_subcategory, template_subject, post_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sm.template_name, ccat.id, sm.template_subcategory, sm.template_subject, sm.post_content,sm.id, '".$now."' as admin_publish_date, sm.superadmin_publish_date, sm.is_default,'1' as edit_flag,sm.created_date,'1' as created_by,sm.status FROM ".$parent_db.".social_media_template_master as sm LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sm.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where is_default  = '1' and publish_flag = '1';";
//social medial	trasection
								$query = $this->db->query($sql_ins);
$sql_ins1 = "INSERT INTO ".$child_db.".social_media_template_platform_trans (social_template_id, platform) SELECT sol.id, tsol1.platform FROM ".$child_db.".social_media_template_master as sol LEFT JOIN ".$parent_db.".social_media_template_master sol1 ON sol.superadmin_template_id  = sol1.id LEFT JOIN ".$parent_db.".social_media_template_platform_trans tsol1 ON tsol1.social_template_id  = sol1.id LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sol.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id;";
								$query = $this->db->query($sql_ins1);								
//phone								
$sql_ins = "INSERT INTO ".$child_db.".phone_call_script_master (template_name, template_category, template_subcategory, template_subject, calling_script,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT ph.template_name, ccat.id, ph.template_subcategory, ph.template_subject, ph.calling_script,ph.id, '".$now."' as admin_publish_date, ph.superadmin_publish_date,ph.is_default,'1' as edit_flag, ph.created_date,'1' as created_by,ph.status FROM ".$parent_db.".phone_call_script_master  as ph LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON ph.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id  where ph.is_default  = '1' and ph.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//sms								
								//$sql_ins = "INSERT INTO ".$child_db.".sms_text_template_master (template_name, template_category, template_subcategory,sms_message,sms_send_type,sms_event,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sms.template_name, ccat.id, sms.template_subcategory,sms.sms_message,sms.sms_send_type,sms.sms_event,sms.id, '".$now."' as admin_publish_date, sms.superadmin_publish_date,sms.is_default,'1' as edit_flag, sms.created_date,'1' as created_by,sms.status FROM ".$parent_db.".sms_text_template_master as sms LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sms.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where sms.is_default  = '1' and sms.publish_flag = '1';";
								$sql_ins = "INSERT INTO ".$child_db.".sms_text_template_master (template_name, template_category, template_subcategory,sms_message,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT sms.template_name, ccat.id, sms.template_subcategory,sms.sms_message,sms.id, '".$now."' as admin_publish_date, sms.superadmin_publish_date,sms.is_default,'1' as edit_flag, sms.created_date,'1' as created_by,sms.status FROM ".$parent_db.".sms_text_template_master as sms LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON sms.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where sms.is_default  = '1' and sms.publish_flag = '1';";
								$query = $this->db->query($sql_ins);
//label								
								$sql_ins = "INSERT INTO ".$child_db.".label_template_master (template_name, template_category, template_subcategory,template_type, size_type, size_w,size_h , label_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT la.template_name, ccat.id, la.template_subcategory,la.template_type, la.size_type, la.size_w,la.size_h , la.label_content,la.id, '".$now."' as admin_publish_date, la.superadmin_publish_date,la.is_default,'1' as edit_flag, la.created_date,'1' as created_by,la.status FROM ".$parent_db.".label_template_master as la LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON la.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where la.is_default  = '1' and la.publish_flag = '1';";
							$query = $this->db->query($sql_ins);
//letter						
							$sql_ins = "INSERT INTO ".$child_db.".letter_template_master (template_name, template_category, template_subcategory,size_w,size_h , template_subject,letter_content,superadmin_template_id, admin_publish_date, superadmin_publish_date,is_default,edit_flag, created_date, created_by, status) SELECT let.template_name, ccat.id, let.template_subcategory,let.size_w,let.size_h,let.template_subject, let.letter_content,let.id, '".$now."' as admin_publish_date, let.superadmin_publish_date,let.is_default,'1' as edit_flag, let.created_date,'1' as created_by,let.status FROM ".$parent_db.".letter_template_master as let LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON let.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id where let.is_default  = '1' and let.publish_flag = '1';";
							$query = $this->db->query($sql_ins);
//interection plan															
								$sql_ins = "INSERT INTO ".$child_db.". interaction_plan_master (plan_name, description, plan_status,target_audience,plan_start_type , start_date,created_date,created_by, modified_date, modified_by,p_p_id,by_superadmin,status) SELECT plan_name, description, plan_status,target_audience,plan_start_type , start_date, '".$now."' as created_date,'1' as created_by, modified_date, modified_by,id,'2' as by_superadmin,status FROM ".$parent_db.".interaction_plan_master_premium where status  = '1' and by_superadmin = '2';";
								$query = $this->db->query($sql_ins);
//interection								
								$sql_ins1 = "INSERT INTO ".$child_db.".interaction_plan_interaction_master (interaction_plan_id, interaction_type, description,assign_to,start_type , number_count,number_type,interaction_id,start_date, priority, drop_type,drop_after_day,drop_after_date,interaction_notes,template_category,template_subcategory,template_name,interaction_sequence_date,send_automatically,include_signature,created_date,created_by,modified_date,modified_by,status) SELECT cinpp.id, pint.interaction_type, pint.description,'1' as assign_to,pint.start_type , pint.number_count,pint.number_type,pint.interaction_id,pint.start_date, pint.priority, pint.drop_type,pint.drop_after_day,pint.drop_after_date,pint.interaction_notes,ccat.id,pint.template_subcategory,
(CASE 
	 WHEN pint.interaction_type = 6  THEN cemail.id
	 WHEN pint.interaction_type = 2  THEN cenv.id
	 WHEN pint.interaction_type = 4  THEN cpho.id
	 WHEN pint.interaction_type = 1  THEN clab.id
	 WHEN pint.interaction_type = 5  THEN clet.id
	 WHEN pint.interaction_type = 3  THEN csms.id
	 WHEN pint.interaction_type = 7  THEN 0
ELSE 0
END ) as template_name,pint.interaction_sequence_date,pint.send_automatically,pint.include_signature, '".$now."' as created_date,'1' as created_by,pint.modified_date,pint.modified_by,pint.status FROM ".$parent_db.".interaction_plan_interaction_master_premium as pint LEFT JOIN ".$parent_db.".interaction_plan_master_premium inpp ON pint.interaction_plan_id  = inpp.id LEFT JOIN ".$child_db.".interaction_plan_master cinpp ON cinpp.p_p_id  = inpp.id 
LEFT JOIN ".$parent_db.".envelope_template_master penv ON pint.template_name  = penv.id LEFT JOIN ".$child_db.".envelope_template_master cenv ON cenv.superadmin_template_id  = penv.id 
LEFT JOIN ".$parent_db.".email_template_master pemail ON pint.template_name  = pemail.id LEFT JOIN ".$child_db.".email_template_master cemail ON cemail.superadmin_template_id  = pemail.id
LEFT JOIN ".$parent_db.".social_media_template_master psoc ON pint.template_name  = psoc.id LEFT JOIN ".$child_db.".social_media_template_master csoc ON csoc.superadmin_template_id  = psoc.id 
LEFT JOIN ".$parent_db.".phone_call_script_master ppho ON pint.template_name  = ppho.id LEFT JOIN ".$child_db.".phone_call_script_master cpho ON cpho.superadmin_template_id  = ppho.id
LEFT JOIN ".$parent_db.".sms_text_template_master psms ON pint.template_name  = psms.id LEFT JOIN ".$child_db.".sms_text_template_master csms ON csms.superadmin_template_id  = psms.id 
LEFT JOIN ".$parent_db.".label_template_master plab ON pint.template_name  = plab.id LEFT JOIN ".$child_db.".label_template_master clab ON clab.superadmin_template_id  = plab.id 
LEFT JOIN ".$parent_db.".letter_template_master plet ON pint.template_name  = plet.id LEFT JOIN ".$child_db.".letter_template_master clet ON clet.superadmin_template_id  = plet.id
LEFT JOIN ".$parent_db.".marketing_master_lib__category_master pcat ON pint.template_category  = pcat.id LEFT JOIN ".$child_db.".marketing_master_lib__category_master ccat ON ccat.superadmin_cat_id  = pcat.id;";

$query = $this->db->query($sql_ins1);
							
				
				
			}
			
			/*$this->db->select('*');
			$qry = $this->db->get($child_db.'.login_master');*/
			
			//pr($qry->result_array());
			
			$lastseen_query = 'INSERT INTO '.$child_db.'.contact_listing_last_seen(login_id,contact_last_seen,listing_last_seen) SELECT id,created_date,created_date as listing_date from '.$child_db.'.login_master';
			$lastseen_query = $this->db->query($lastseen_query);
			//echo 1;exit;
			
			///////////////////////////////////
		
			//$sql = "SHOW TABLES FROM $parent_db";
			//$result = mysql_query($sql,$conn1);
			
			/*while($row = mysql_fetch_row($result))
			{
				$parent_db_tables[] = $row[0];
			}*/
			
			//echo "<pre>"; print_r($parent_db_tables); exit;
			
			// Copy one db tables to another db
			
			/*for($i=0; $i<count($parent_db_tables); $i++){
				$create_table = "CREATE TABLE ".$child_db.".".$parent_db_tables[$i]." LIKE ".$parent_db.".".$parent_db_tables[$i]; 
				$result1 = mysql_query($create_table,$conn2);
				
				$insert_data = "INSERT INTO ".$child_db.".".$parent_db_tables[$i]." SELECT * FROM ".$parent_db.".".$parent_db_tables[$i];
				$result2 = mysql_query($insert_data,$conn2);
			}
		
			mysql_free_result($result);*/
		}
	}

	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getadminpagingid($admin_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('user_type','2');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $admin_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
	}
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Kaushik Valiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 17-09-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$totalrow='')
    {  
		if(!empty($fields))
		{
			foreach($fields as $coll => $value)
			{
				$this->db->select($value,false);
			}
		}
		
		$this->db->from($table);
		
		if(!empty($join_tables))
		{
			foreach($join_tables as $coll => $value)
			{
				$this->db->join($coll, $value,$join_type);
			}
		}
		
		if($condition != null )
		$this->db->where($condition);
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null && $sort != null)
			$this->db->order_by($orderby,$sort);
		elseif($orderby != null )
		{
			if($orderby == 'special_case')
				$this->db->order_by('is_done asc,task_date asc');
			else
				$this->db->order_by($orderby);
		}
				
		if($match_values != null &&  $compare_type != null )
		$this->db->or_like($match_values);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
		
		//pr($query_FC->result_array());
		//echo $this->db->last_query();exit;
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
  			return $query_FC->result_array();
  
	}
	
	function get_child_login_details($childdb='',$data='')
    {
		if(!empty($childdb))
		{
			//SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'DBName'
			$this->db->where(array('email_id'=>$data['email_id']));
			$query = $this->db->get($childdb.".login_master",$data); 
			return $query->result_array();
			//echo $this->db->last_query();exit;
		}
    }
	/*
    @Description: Function for get parent user detail
    @Author: Niral patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: user list
    @Date: 29-01-2015
    */
	function get_parent_login_details($getfields='',$parentdb='',$data='')
    {
		if(!empty($parentdb))
		{
			//SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'DBName'
			$fields =  $getfields ? implode(',', $getfields) : '';
			$this->db->select($fields);
			$this->db->where($data);
			$query = $this->db->get($parentdb.".login_master",$data); 
			return $query->result_array();
			//echo $this->db->last_query();exit;
		}
    }
	
	function update_child_user_record($childdb='',$data='')
    {
		if(!empty($childdb))
		{
			//SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'DBName'
			$this->db->where(array('email_id'=>$data['email_id']));
			$query = $this->db->update($childdb.".login_master",$data); 
			//echo $this->db->last_query();exit;
		}
    }
	  /*
        @Description: Function is for update user details by Super Admin
        @Author     : Kaushik Valiya
        @Input      : user details
        @Output     : Update record into db
        @Date: 	25-11-14
    */
    public function update_user_buyer($data,$db_name='')
    {
        $this->db->where('id',$data['id']);

		if(!empty($db_name))
		{
			$this->db->or_where('db_name',$db_name);
			$query = $this->db->update($this->table_name,$data);
		}
		else
        	$query = $this->db->update($this->table_name,$data); 
		
    }
    
     /*
        @Description: Function is for update user tab setting for all domain user
        @Author     : Sanjay Moghariya
        @Input      : user details
        @Output     : Update record into db
        @Date       : 16-03-2015
    */
    public function update_user_tab($data,$db_name='')
    {
        if(!empty($db_name))
        {
                $this->db->where('db_name',$db_name);
                $query = $this->db->update($this->table_name,$data);
        }
    }

	  /*
        @Description: Function is for update user details by Super Admin
        @Author     : Kaushik Valiya
        @Input      : user details
        @Output     : Update record into db
        @Date: 	25-11-14
    */
    public function update_admin_user_buyer($data,$db_name='')
    {
      //  $this->db->where('id',$data['id']);
	//	$this->db->or_where('created_by',$data['id']);
		if(!empty($db_name))
			$query = $this->db->update($db_name.".".$this->table_name,$data);
		
    }
	
	public function update_superadmin_data($data,$db_name='')
    {
      	$this->db->where('email_id',$data['email_id']);
		$this->db->where('db_name',$data['db_name']);
		if(!empty($db_name))
			$query = $this->db->update($db_name.".".$this->table_name,$data);
		
    }

    public function insert_listing_data()
	{
		$sql_ins = "
		INSERT INTO livewire_crm_2.mls_property_list_master (LN,PTYP,LAG,ST,LP,SP,OLP,HSN,DRP,STR,SSUF,DRS,UNT,CIT,STA,ZIP,PL4,BR,BTH,ASF,LSF,UD,AR,DSRNUM,LDR,LD,CLO,YBT,LO,TAX,MAP,GRDX,GRDY,SAG,SO,NIA,MR,LONGI,LAT,PDR,CLA,SHOADR,DD,AVDT,INDT,COU,CDOM,CTDT,SCA,SCO,VIRT,SDT,SD,FIN,MAPBOOK,DSR,QBT,HSNA,COLO,PIC,ADU,ARC,BDC,BDL,BDM,BDU,BLD,BLK,BRM,BUS,DNO,DRM,EFR,EL,ENT,F17,FAM,FBG,FBL,FBM,FBT,FBU,FP,FPL,FPM,FPU,GAR,HBG,HBL,HBM,HBT,HBU,HOD,JH,KES,KIT,LRM,LSD,LSZ,LT,MBD,MHM,MHN,MHS,MOR,NC,POC,POL,PRJ,PTO,TQBT,RRM,CMFE,SAP,SFF,SFS,SFU,SH,SML,SNR,STY,SWC,TBG,TBL,TBM,TBU,TX,TXY,UTR,WAC,WFG,WHT,APS,BDI,BSM,ENS,EXT,FEA,FLS,FND,GR,HTC,LDE,LTV,POS,RF,SIT,SWR,TRM,VEW,WAS,WFT,BUSR,ECRT,ZJD,ZNC,ProhibitBLOG,AllowAVM,PARQ,BREO,BuiltGreenRating,EPSEnergy,ROFR,HERSIndex,LEEDRating,NewConstruction,NWESHRating,ConstructionMethods,EMP,EQU,EQV,FRN,GRS,GW,HRS,INV,LNM,LSI,NA,NP,PKC,PKU,RES,RNT,SIN,TEXP,TOB,YRE,YRS,LES,LIC,LOC,MTB,RP,LSZS,AFH,ASCC,COO,MGR,NAS,NOC,NOS,NOU,OOC,PKS,REM,SAA,SPA,STG,STL,TOF,UFN,WDW,APH,CMN,CTD,HOI,PKG,UNF,STRS,FUR,MLT,STO,AFR,APP,MIF,TMC,TYP,UTL,ELE,ESM,GAS,LVL,QTR,RD,SDA,SEC,SEP,SFA,SLP,SST,SUR,TER,WRJ,ZNR,ATF,DOC,FTR,GZC,IMP,RDI,RS2,TPO,WTR,AUCTION,LotSizeSource,EffectiveYearBuilt,EffectiveYearBuiltSource,Class,testfloat,csv_id,created_date,modified_date,status) 
		SELECT LN,PTYP,LAG,ST,LP,SP,OLP,HSN,DRP,STR,SSUF,DRS,UNT,CIT,STA,ZIP,PL4,BR,BTH,ASF,LSF,UD,AR,DSRNUM,LDR,LD,CLO,YBT,LO,TAX,MAP,GRDX,GRDY,SAG,SO,NIA,MR,LONGI,LAT,PDR,CLA,SHOADR,DD,AVDT,INDT,COU,CDOM,CTDT,SCA,SCO,VIRT,SDT,SD,FIN,MAPBOOK,DSR,QBT,HSNA,COLO,PIC,ADU,ARC,BDC,BDL,BDM,BDU,BLD,BLK,BRM,BUS,DNO,DRM,EFR,EL,ENT,F17,FAM,FBG,FBL,FBM,FBT,FBU,FP,FPL,FPM,FPU,GAR,HBG,HBL,HBM,HBT,HBU,HOD,JH,KES,KIT,LRM,LSD,LSZ,LT,MBD,MHM,MHN,MHS,MOR,NC,POC,POL,PRJ,PTO,TQBT,RRM,CMFE,SAP,SFF,SFS,SFU,SH,SML,SNR,STY,SWC,TBG,TBL,TBM,TBU,TX,TXY,UTR,WAC,WFG,WHT,APS,BDI,BSM,ENS,EXT,FEA,FLS,FND,GR,HTC,LDE,LTV,POS,RF,SIT,SWR,TRM,VEW,WAS,WFT,BUSR,ECRT,ZJD,ZNC,ProhibitBLOG,AllowAVM,PARQ,BREO,BuiltGreenRating,EPSEnergy,ROFR,HERSIndex,LEEDRating,NewConstruction,NWESHRating,ConstructionMethods,EMP,EQU,EQV,FRN,GRS,GW,HRS,INV,LNM,LSI,NA,NP,PKC,PKU,RES,RNT,SIN,TEXP,TOB,YRE,YRS,LES,LIC,LOC,MTB,RP,LSZS,AFH,ASCC,COO,MGR,NAS,NOC,NOS,NOU,OOC,PKS,REM,SAA,SPA,STG,STL,TOF,UFN,WDW,APH,CMN,CTD,HOI,PKG,UNF,STRS,FUR,MLT,STO,AFR,APP,MIF,TMC,TYP,UTL,ELE,ESM,GAS,LVL,QTR,RD,SDA,SEC,SEP,SFA,SLP,SST,SUR,TER,WRJ,ZNR,ATF,DOC,FTR,GZC,IMP,RDI,RS2,TPO,WTR,AUCTION,LotSizeSource,EffectiveYearBuilt,EffectiveYearBuiltSource,Class,testfloat,csv_id,created_date,modified_date,status 
		FROM livewire_crm_2.mls_property_list_master";
		$query = $this->db->query($sql_ins);
	}
     
    public function insert_user_login_trans($data='',$db_name='')
    {
        $this->db->insert($db_name.'.user_login_trans',$data);
        return $this->db->insert_id();
    }
    public function update_user_login_trans($data='',$db_name='')
    {
        // pr($db_name);exit;
        $this->db->where('id',$data['id']);
        $this->db->update($db_name.'.user_login_trans',$data);
    }
    
    public function bombbombapi_curl($url='',$data='')
    {
        //echo "hiii";exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email=".urlencode($data['username'])."&pw=".urlencode($data['password']));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        // This is what solved the issue (Accepting gzidp encoding)
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");    
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
}