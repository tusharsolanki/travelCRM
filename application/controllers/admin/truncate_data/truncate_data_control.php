<?php 
/*
    @Description: cron controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 21-10-2014
	
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class truncate_data_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();                
		ini_set('memory_limit', '-1');
		$this->load->model('admin_model');
    }
	

    /*
    @Description: Function for Get All contacts List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all contacts list
    @Date: 04-07-2014
    */
    public function index()
    {
	}
	
	public function folder_truncate()
	{
		$uploads = array(	$this->config->item('admin_big_img_path').'*',
						 	$this->config->item('admin_small_img_path').'*',
							$this->config->item('attachment_basepath_file').'*',
							$this->config->item('attachment_temp_basepath').'*',
							$this->config->item('broker_big_img_path').'*',
							$this->config->item('broker_small_img_path').'*',
							$this->config->item('contact_big_img_path').'*',
							$this->config->item('contact_small_img_path').'*',
							$this->config->item('contact_documents_big_csv_path').'*',
							$this->config->item('contact_documents_big_img_path').'*',
							$this->config->item('listing_big_img_path').'*',
							$this->config->item('listing_small_img_path').'*',
							$this->config->item('listing_documents_big_img_path').'*',
							$this->config->item('temp_big_img_basepath').'*',
							$this->config->item('temp_small_img_basepath').'*',
							$this->config->item('user_big_img_path').'*',
							$this->config->item('user_small_img_path').'*',
						);
		
		foreach($uploads as $row)
		{
			echo $row."<br>";
			$files = glob($row);
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
		}	
	}
	
	public function database_truncate()
	{
		$db_name = $this->config->item('parent_db_name');
		$db = '';
		$db['second']['hostname'] = 'localhost';
		$db['second']['username'] = $this->config->item('root_user_name');
		$db['second']['password'] = $this->config->item('root_password');			//Local
		$db['second']['database'] = $db_name;
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
		$alter_query = array(
								//'TRUNCATE email_campaign_master',
								'TRUNCATE admin_users',
								'TRUNCATE package_master',
								'TRUNCATE user_package_trans',
								'TRUNCATE calendar_master',
								'TRUNCATE calendar_repeat_trans',
								'TRUNCATE campaign',
								'TRUNCATE campaign_blast',
								'TRUNCATE campaign_type',
								'TRUNCATE city',
								'TRUNCATE contact_additionalfield_trans',
								'TRUNCATE contact_address_trans',
								'TRUNCATE contact_archive_master',
								'TRUNCATE contact_chat_history',
								'TRUNCATE contact_communication_plan_trans',
								'TRUNCATE contact_contacttype_trans',
								'TRUNCATE contact_contact_status_trans',
								'TRUNCATE contact_conversations_trans',
								'TRUNCATE contact_csv_master',
								'TRUNCATE contact_documents_trans',
								'TRUNCATE contact_emails_trans',
								'TRUNCATE contact_master',
								'TRUNCATE contact_phone_trans',
								'TRUNCATE contact_social_trans',
								'TRUNCATE contact_tag_trans',
								'TRUNCATE contact_website_trans',
								'TRUNCATE contact__csv_mapping_master',
								'TRUNCATE contact__csv_mapping_trans',
								'TRUNCATE countries',
								'TRUNCATE country',
								'TRUNCATE email_campaign_attachments',
								'TRUNCATE email_campaign_master',
								'TRUNCATE email_campaign_recepient_trans',
								'TRUNCATE email_signature_master',
								'TRUNCATE email_templates',
								'TRUNCATE email_template_master',
								'TRUNCATE envelope_template_master',
								'TRUNCATE fb_chat_last_sync',
								'TRUNCATE interaction_plan_adminuser_trans',
								'TRUNCATE interaction_plan_contacts_trans',
								'TRUNCATE interaction_plan_contact_activity_log_manual',
								'TRUNCATE interaction_plan_contact_communication_plan',
								'TRUNCATE interaction_plan_contact_personal_touches',
								'TRUNCATE interaction_plan_interaction_master',
								'TRUNCATE interaction_plan_interaction_master_premium',
								'TRUNCATE interaction_plan_interaction_to_do',
								'TRUNCATE interaction_plan_master',
								'TRUNCATE interaction_plan_master_premium',
								'TRUNCATE interaction_plan_time_trans',
								'TRUNCATE joomla_mapping',
								'TRUNCATE joomla_rpl_addon_multi_site',
								'TRUNCATE joomla_rpl_bookmarks',
								'TRUNCATE joomla_rpl_log',
								'TRUNCATE joomla_rpl_savesearch',
								'TRUNCATE joomla_rpl_track',
								'TRUNCATE label_template_master',
								'TRUNCATE lead_data',
								'TRUNCATE lead_master',
								'TRUNCATE letter_template_master',
								'TRUNCATE phone_call_script_master',
								'TRUNCATE property_listing_contact_trans',
								'TRUNCATE property_listing_document_trans',
								'TRUNCATE property_listing_master',
								'TRUNCATE property_listing_offers_trans',
								'TRUNCATE property_listing_open_houses_trans',
								'TRUNCATE property_listing_photo_trans',
								'TRUNCATE property_listing_price_change_trans',
								'TRUNCATE property_listing_showings_trans',
								'TRUNCATE sms_campaign_master',
								'TRUNCATE sms_campaign_recepient_trans',
								'TRUNCATE sms_text_template_master',
								'TRUNCATE social_master',
								'TRUNCATE social_media_template_master',
								'TRUNCATE social_media_template_platform_trans',
								'TRUNCATE social_platform_trans',
								'TRUNCATE social_recepient_trans',
								'TRUNCATE task_master',
								'TRUNCATE task_user_transcation',
								'TRUNCATE user_address_trans',
								'TRUNCATE user_contact_trans',
								'TRUNCATE user_emails_trans',
								'TRUNCATE user_leave_data',
								'TRUNCATE user_master',
								'TRUNCATE user_package_trans',
								'TRUNCATE user_phone_trans',
								'TRUNCATE user_rights_trans',
								'TRUNCATE user_rr_weightage_trans',
								'TRUNCATE user_social_trans',
								'TRUNCATE user_website_trans',
								'TRUNCATE work_time_config_master',
								'TRUNCATE work_time_special_rules',
								'TRUNCATE contact__additionalfield_master',
								'TRUNCATE contact__address_type_master',
								'TRUNCATE contact__csv_mapping_master',
								'TRUNCATE contact__csv_mapping_trans',
								'TRUNCATE contact__document_type_master',
								'TRUNCATE contact__email_type_master',
								'TRUNCATE contact__method_master',
								'TRUNCATE contact__phone_type_master',
								'TRUNCATE contact__social_type_master',
								'TRUNCATE contact__source_master',
								'TRUNCATE contact__status_master',
								'TRUNCATE contact__type_master',
								'TRUNCATE contact__websitetype_master',
								'TRUNCATE marketing_master_lib__category_master',
								
								'TRUNCATE contact_linkedin_trasection',
								'TRUNCATE contact_notes_trans',
								'TRUNCATE contact_twitter_trasection',
								'TRUNCATE joomla_rpl_property_valuation_searches',
								'TRUNCATE mail_blast_contact_trans',
								'TRUNCATE mail_blast_sent',
								'TRUNCATE property_listing__architecture_master',
								'TRUNCATE property_listing__basement_master',
								'TRUNCATE property_listing__document_type_master',
								'TRUNCATE property_listing__energy_source_master',
								'TRUNCATE property_listing__exterior_finish_master',
								'TRUNCATE property_listing__fireplace_master',
								'TRUNCATE property_listing__floor_covering_master',
								'TRUNCATE property_listing__foundation_master',
								'TRUNCATE property_listing__green_certification_master',
								'TRUNCATE property_listing__heating_cooling_master',
								'TRUNCATE property_listing__interior_feature_master',
								'TRUNCATE property_listing__lockbox_type_master',
								'TRUNCATE property_listing__lot_type_master',
								'TRUNCATE property_listing__parking_type_master',
								'TRUNCATE property_listing__power_company_master',
								'TRUNCATE property_listing__property_type_master',
								'TRUNCATE property_listing__roof_master',
								'TRUNCATE property_listing__sewer_company_master',
								'TRUNCATE property_listing__sewer_master',
								'TRUNCATE property_listing__status_master',
								'TRUNCATE property_listing__style_master',
								'TRUNCATE property_listing__transaction_type_master',
								'TRUNCATE property_listing__unit_master',
								'TRUNCATE property_listing__water_company_master',
								'TRUNCATE sms_response',
								"Delete from login_master where user_type !='1'",
								"INSERT INTO `contact__social_type_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
								(1, 'Facebook', '2014-07-08 08:13:54', 4, '0000-00-00 00:00:00', 0, '1'),
								(2, 'Twitter', '2014-07-08 08:13:54', 4, '2014-07-30 07:33:38', 4, '1'),
								(3, 'Linkedin', '2014-11-05 06:53:49', 2, '0000-00-00 00:00:00', 0, '1')",
			
							);
		foreach($alter_query as $row)
		{
			echo $row."<br>";
			if(trim($row) != '')
			{
				$query = trim($row);
				$query = $this->legacy_db->query($query);
			}
			//exit;
		}
					
	}
	
	public function system_truncate()
	{
		$this->folder_truncate();
		echo "<br><br>";
		$this->database_truncate();
	}
}
