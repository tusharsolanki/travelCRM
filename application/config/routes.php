<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "user/login/login";
$route['404_override'] = 'my404';

// Base URL

$base_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// Dynamic Route Path

$pos = strpos($base_url,"admin");
/*$newpos = strpos($base_url,"superadmin");


if($newpos == false) {

	if($pos == false) {
	
		$config['base_url'] = $base_url;	
		$flag = '1';
		
		$expo1 = explode("user/",$base_url);
		//print_r($expo1);exit;
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
	
	}
	elseif(strpos($base_url,"/ws/"))
	{
		$expo1 = explode("ws/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '4';
	}
	else{
		$expo1 = explode("admin/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '2';
	}
	
}
else
{

	$config['base_url'] = $base_url;	
	$flag = '3';
	
	$expo1 = explode("superadmin/",$base_url);
	//print_r($expo1);exit;
	$config['base_url'] = $expo1[0];	
	
	$expp = !empty($expo1[1])?$expo1[1]:'';
	
	$expo = explode("/",$expp);
	$conntrol = !empty($expo['0'])?$expo['0']:'';
	
}*/
/*else
{
	$expo2 = explode("superadmin/",$base_url);
	$config['base_url'] = $expo2[0];	
	
	$expp1 = !empty($expo2[1])?$expo2[1]:'';
	
	$expo1 = explode("/",$expp1);
	$conntrol = !empty($expo1['0'])?$expo1['0']:'';
	
	$flag = '2';


}*/
	if(strpos($base_url,"/admin/")){
	$expo1 = explode("admin/",$base_url);
	$config['base_url'] = $expo1[0];	
	
	$expp = !empty($expo1[1])?$expo1[1]:'';
	
	$expo = explode("/",$expp);
	$conntrol = !empty($expo['0'])?$expo['0']:'';
	
	$flag = '2';
	
	}elseif(strpos($base_url,"/ws/")){
		$expo1 = explode("ws/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '4';
	}elseif(strpos($base_url,"/website/")){
		$expo1 = explode("website/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '6';
	}elseif(strpos($base_url,"/superadmin/")){
		$expo1 = explode("superadmin/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '3';
	}
	elseif(strpos($base_url,"/user/")){
		$expo1 = explode("user/",$base_url);
		$config['base_url'] = $expo1[0];	
		
		$expp = !empty($expo1[1])?$expo1[1]:'';
		
		$expo = explode("/",$expp);
		$conntrol = !empty($expo['0'])?$expo['0']:'';
		
		$flag = '1';
	}
	else{
		$config['base_url'] = $base_url;	
		$flag = '5';
		
		if(!empty($_SERVER['ORIG_PATH_INFO'])){
			$expo1 = explode("/",$_SERVER['ORIG_PATH_INFO']);
		}elseif(!empty($_SERVER['PATH_INFO'])){
			$expo1 = explode("/",$_SERVER['PATH_INFO']);
		}else{
			$expo1 = explode("/",$_SERVER['REQUEST_URI']);
		}
	
		$conntrol = !empty($expo1['1'])?$expo1['1']:'';

	}
	if($flag == '3')
	{
		$user_type='superadmin/';
	}
	elseif($flag == '2')
	{
		$user_type='admin/';
	}
	elseif($flag == '4')
	{
		$user_type='ws/';
	}
        elseif($flag == '6')
	{
		$user_type='website/';
	}
	elseif($flag == '1')
	{
		$user_type='user/';
	}

	else
	{
		$user_type='/';
	}
	
	// Temp alter query
	
	$route['admin/cron/cat_subcat_query']="admin/cron/cron_control/cat_subcat_query";
	
		/* Start Dashboard Path */
        //$route[$user_type.'joomla_assign'] = $user_type.'index/joomla_assign';
        $route[$user_type.'/country/check_country'] = $user_type.'/country/country_control/check_country';
        $route[$user_type.'dashboard/phone_task'] = $user_type.'index/dashboard/phone_task';
		$route[$user_type.'dashboard/daily_task'] = $user_type.'index/dashboard/daily_task';
        $route[$user_type.'dashboard/daily_task/(:num)'] = $user_type.'index/dashboard/daily_task';
        $route[$user_type.'dashboard/form_lead_list'] = $user_type.'index/dashboard/form_lead_list';
        $route[$user_type.'dashboard/form_lead_list/(:num)'] = $user_type.'index/dashboard/form_lead_list';
		$route[$user_type.'dashboard/new_leads'] = $user_type.'index/dashboard/new_leads';
		$route[$user_type.'dashboard/new_leads/(:num)'] = $user_type.'index/dashboard/new_leads';
		$route[$user_type.'dashboard/email_task'] = $user_type.'index/dashboard/email_task';
		$route[$user_type.'dashboard/email_task/(:num)'] = $user_type.'index/dashboard/email_task';
		$route[$user_type.'dashboard/send_mail'] = $user_type.'index/dashboard/send_mail';
		$route[$user_type.'dashboard/sms_task'] = $user_type.'index/dashboard/sms_task';
		$route[$user_type.'dashboard/sms_task/(:num)'] = $user_type.'index/dashboard/sms_task';
		$route[$user_type.'dashboard/send_sms'] = $user_type.'index/dashboard/send_sms';
        $route[$user_type.'joomla_assign/change_plan_status'] = $user_type.'joomla_assign/joomla_assign_control/change_plan_status';
		$route[$user_type.'dashboard/view_contacts_of_interaction_plans'] = $user_type.'index/dashboard/view_contacts_of_interaction_plans';
		
		$route[$user_type.'dashboard/view_records'] = $user_type.'index/dashboard/view_records';
		$route[$user_type.'dashboard/insert_data1'] = $user_type."index/dashboard/insert_data1";
		$route[$user_type.'dashboard/generate_pdf1'] = $user_type."index/dashboard/generate_pdf1";
		
		
		
		$route[$user_type.'dashboard/letter_label_envelope_task'] = $user_type.'index/dashboard/letter_label_envelope_task';
		$route[$user_type.'dashboard/letter_label_envelope_task/(:num)'] = $user_type.'index/dashboard/letter_label_envelope_task';
		$route[$user_type.'dashboard/telephone_task'] = $user_type.'index/dashboard/telephone_task';
		$route[$user_type.'dashboard/telephone_task/(:num)'] = $user_type.'index/dashboard/telephone_task';
		
		$route[$user_type.'dashboard/phone_call_popup/(:any)/(:any)/(:num)'] = $user_type.'index/dashboard/phone_call_popup';
		$route[$user_type.'dashboard/phone_call_popup/(:any)/(:any)'] = $user_type.'index/dashboard/phone_call_popup';
		
		$route[$user_type.'dashboard/is_completed'] = $user_type."index/dashboard/is_completed";
        $route[$user_type.'dashboard/iscompleted'] = $user_type."index/dashboard/iscompleted";
        $route[$user_type.'dashboard/ajax_delete_all'] = $user_type."index/dashboard/ajax_delete_all";
        $route[$user_type.'dashboard/update_error'] = $user_type."index/dashboard/update_error";
        $route[$user_type.'dashboard/update_error_data'] = $user_type."index/dashboard/update_error_data";
        
        $route[$user_type.'dashboard/view_error_data'] = $user_type."index/dashboard/view_error_data";
        $route[$user_type.'dashboard/view_error_data/(:num)'] = $user_type."index/dashboard/view_error_data";
        
		$route[$user_type.'dashboard/ajax_delete_task'] = $user_type."index/dashboard/ajax_delete_task";
		$route[$user_type.'dashboard/mail_out'] = $user_type."index/dashboard/mail_out";
		
		$route[$user_type.'dashboard/to_do_task'] = $user_type.'index/dashboard/to_do_task';
		$route[$user_type.'dashboard/to_do_task/(:num)'] = $user_type.'index/dashboard/to_do_task';
		/* End Dashboard Path */
		$route[$user_type.$conntrol.'/selectedview_session'] = $user_type.$conntrol.'/'.$conntrol.'_control/selectedview_session';
                $route[$user_type.$conntrol.'/change_joomla_tab_config'] = $user_type.$conntrol.'/'.$conntrol.'_control/change_joomla_tab_config';
                
		//social
		
        
	/*else
	{$user_type='superadmin/';}*/
	//echo $user_type;exit;
	/*DELETE ROUTE PATH*/
	$route[$user_type.'contact_masters/delete_website_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_website_record';
	$route[$user_type.'contact_masters/delete_field_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_field_record';
	
	//dummy function for graph  by mohit
	$route[$user_type.'analytics/graph'] = $user_type.'analytics/analytics_control/graph';
	//dummy path for graph function
	
	$route[$user_type.'contact_masters/delete_email_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_email_record';
	$route[$user_type.'department_masters/delete_department_record/(:num)'] = $user_type.'department_masters/department_masters_control/delete_department_record';


	$route[$user_type.'contact_masters/delete_phone_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_phone_record';
	$route[$user_type.'contact_masters/delete_address_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_address_record';
	$route[$user_type.'contact_masters/delete_document_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_document_record';
	$route[$user_type.'contact_masters/delete_profile_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_profile_record';
	$route[$user_type.'contact_masters/delete_contact_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_contact_record';
	$route[$user_type.'contact_masters/delete_document_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_document_record';
	$route[$user_type.'contact_masters/delete_source_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_source_record';
	$route[$user_type.'contact_masters/delete_disposition_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_disposition_record';
	$route[$user_type.'contact_masters/delete_method_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_method_record';
	$route[$user_type.'contacts/sendlinked_message'] = $user_type.'contacts/contacts_control/sendlinked_message';
	$route[$user_type.'contacts/sendlinked_invitation/(:num)'] = $user_type.'contacts/contacts_control/sendlinked_invitation';
	
	/////////// Property List master///////////////////////
	
	$route[$user_type.'property_list_masters/delete_website_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_website_record';
	$route[$user_type.'property_list_masters/delete_field_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_field_record';
	
	//dummy function for graph  by mohit
	$route[$user_type.'analytics/graph'] = $user_type.'analytics/analytics_control/graph';
	//dummy path for graph function
	///// Property list master ////////
	$route[$user_type.'property_list_masters/delete_property_list_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_property_list_record';
	
	
	$route[$user_type.'property_list_masters/delete_property_status/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_property_status';
	
	$route[$user_type.'property_list_masters/delete_document_list_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_document_list_record';
	$route[$user_type.'property_list_masters/delete_lot_type_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_lot_type_record';
	$route[$user_type.'property_list_masters/delete_transaction_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_transaction_record';
	$route[$user_type.'property_list_masters/delete_lockbox_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_lockbox_record';
	$route[$user_type.'property_list_masters/delete_sewer_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_sewer_record';
	$route[$user_type.'property_list_masters/delete_basement_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_basement_record';
	$route[$user_type.'property_list_masters/delete_architecture_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_architecture_record';
	$route[$user_type.'property_list_masters/delete_energy_source_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_energy_source_record';
	$route[$user_type.'property_list_masters/delete_exterior_finish_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_exterior_finish_record';
	$route[$user_type.'property_list_masters/delete_fireplace_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_fireplace_record';
	$route[$user_type.'property_list_masters/delete_floor_covering_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_floor_covering_record';
	$route[$user_type.'property_list_masters/delete_foundation_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_foundation_record';
	$route[$user_type.'property_list_masters/delete_green_certification_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_green_certification_record';
	$route[$user_type.'property_list_masters/delete_heating_cooling_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_heating_cooling_record';
	$route[$user_type.'property_list_masters/delete_interior_feature_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_interior_feature_record';
	$route[$user_type.'property_list_masters/delete_parking_type_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_parking_type_record';
	$route[$user_type.'property_list_masters/delete_power_company_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_power_company_record';
	$route[$user_type.'property_list_masters/delete_roof_master_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_roof_master_record';
	$route[$user_type.'property_list_masters/delete_sewer_company_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_sewer_company_record';
	$route[$user_type.'property_list_masters/delete_style_master_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_style_master_record';
	$route[$user_type.'property_list_masters/delete_water_company_record/(:num)'] = $user_type.'property_list_masters/property_list_masters_control/delete_water_company_record';
	
	///////////// Update This property master////////
	
	$route[$user_type.'property_list_masters/update_property_list'] = $user_type.'property_list_masters/property_list_masters_control/update_property_list';
	
	
	$route[$user_type.'property_list_masters/update_property_status'] = $user_type.'property_list_masters/property_list_masters_control/update_property_status';
	
	
	$route[$user_type.'property_list_masters/update_document_list'] = $user_type.'property_list_masters/property_list_masters_control/update_document_list';
	$route[$user_type.'property_list_masters/update_lot_type'] = $user_type.'property_list_masters/property_list_masters_control/update_lot_type';
	$route[$user_type.'property_list_masters/update_transaction'] = $user_type.'property_list_masters/property_list_masters_control/update_transaction';
	$route[$user_type.'property_list_masters/update_lockbox'] = $user_type.'property_list_masters/property_list_masters_control/update_lockbox';
	$route[$user_type.'property_list_masters/update_sewer'] = $user_type.'property_list_masters/property_list_masters_control/update_sewer';
	$route[$user_type.'property_list_masters/update_basement'] = $user_type.'property_list_masters/property_list_masters_control/update_basement';
	$route[$user_type.'property_list_masters/update_architecture'] = $user_type.'property_list_masters/property_list_masters_control/update_architecture';
	$route[$user_type.'property_list_masters/update_energy_source'] = $user_type.'property_list_masters/property_list_masters_control/update_energy_source';
	$route[$user_type.'property_list_masters/update_exterior_finish'] = $user_type.'property_list_masters/property_list_masters_control/update_exterior_finish';
	$route[$user_type.'property_list_masters/update_fireplace'] = $user_type.'property_list_masters/property_list_masters_control/update_fireplace';
	$route[$user_type.'property_list_masters/update_floor_covering'] = $user_type.'property_list_masters/property_list_masters_control/update_floor_covering';
	$route[$user_type.'property_list_masters/update_foundation'] = $user_type.'property_list_masters/property_list_masters_control/update_foundation';
	$route[$user_type.'property_list_masters/update_green_certification'] = $user_type.'property_list_masters/property_list_masters_control/update_green_certification';
	$route[$user_type.'property_list_masters/update_heating_cooling'] = $user_type.'property_list_masters/property_list_masters_control/update_heating_cooling';
	$route[$user_type.'property_list_masters/update_interior_feature'] = $user_type.'property_list_masters/property_list_masters_control/update_interior_feature';
	$route[$user_type.'property_list_masters/update_parking_type'] = $user_type.'property_list_masters/property_list_masters_control/update_parking_type';
	$route[$user_type.'property_list_masters/update_power_company'] = $user_type.'property_list_masters/property_list_masters_control/update_power_company';
	$route[$user_type.'property_list_masters/update_roof_master'] = $user_type.'property_list_masters/property_list_masters_control/update_roof_master';
	$route[$user_type.'property_list_masters/update_sewer_company'] = $user_type.'property_list_masters/property_list_masters_control/update_sewer_company';
	$route[$user_type.'property_list_masters/update_style_master'] = $user_type.'property_list_masters/property_list_masters_control/update_style_master';
	$route[$user_type.'property_list_masters/update_water_company'] = $user_type.'property_list_masters/property_list_masters_control/update_water_company';
	//////////// end Update /////////
	
	///////////// insert This Property master //////////////
	$route[$user_type.'property_list_masters/insert_property_list'] = $user_type.'property_list_masters/property_list_masters_control/insert_property_list';
	
	$route[$user_type.'property_list_masters/insert_property_status'] = $user_type.'property_list_masters/property_list_masters_control/insert_property_status';
	
	
	$route[$user_type.'property_list_masters/insert_document_list'] = $user_type.'property_list_masters/property_list_masters_control/insert_document_list';
	$route[$user_type.'property_list_masters/insert_lot_type_list'] = $user_type.'property_list_masters/property_list_masters_control/insert_lot_type_list';
	$route[$user_type.'property_list_masters/insert_trasaction'] = $user_type.'property_list_masters/property_list_masters_control/insert_trasaction';
	$route[$user_type.'property_list_masters/insert_lockbox'] = $user_type.'property_list_masters/property_list_masters_control/insert_lockbox';
	$route[$user_type.'property_list_masters/insert_sewer'] = $user_type.'property_list_masters/property_list_masters_control/insert_sewer';
	$route[$user_type.'property_list_masters/insert_basement'] = $user_type.'property_list_masters/property_list_masters_control/insert_basement';
	$route[$user_type.'property_list_masters/insert_architecture'] = $user_type.'property_list_masters/property_list_masters_control/insert_architecture';
	$route[$user_type.'property_list_masters/insert_energy_source'] = $user_type.'property_list_masters/property_list_masters_control/insert_energy_source';
	$route[$user_type.'property_list_masters/insert_exterior_finish'] = $user_type.'property_list_masters/property_list_masters_control/insert_exterior_finish';
	$route[$user_type.'property_list_masters/insert_fireplace'] = $user_type.'property_list_masters/property_list_masters_control/insert_fireplace';
	$route[$user_type.'property_list_masters/insert_floor_covering'] = $user_type.'property_list_masters/property_list_masters_control/insert_floor_covering';
	$route[$user_type.'property_list_masters/insert_foundation'] = $user_type.'property_list_masters/property_list_masters_control/insert_foundation';
	$route[$user_type.'property_list_masters/insert_green_certification'] = $user_type.'property_list_masters/property_list_masters_control/insert_green_certification';
	$route[$user_type.'property_list_masters/insert_heating_cooling'] = $user_type.'property_list_masters/property_list_masters_control/insert_heating_cooling';
	$route[$user_type.'property_list_masters/insert_interior_feature'] = $user_type.'property_list_masters/property_list_masters_control/insert_interior_feature';
	$route[$user_type.'property_list_masters/insert_parking_type'] = $user_type.'property_list_masters/property_list_masters_control/insert_parking_type';
	$route[$user_type.'property_list_masters/insert_power_company'] = $user_type.'property_list_masters/property_list_masters_control/insert_power_company';
	$route[$user_type.'property_list_masters/insert_roof_master'] = $user_type.'property_list_masters/property_list_masters_control/insert_roof_master';
	$route[$user_type.'property_list_masters/insert_sewer_company'] = $user_type.'property_list_masters/property_list_masters_control/insert_sewer_company';
	$route[$user_type.'property_list_masters/insert_style_master'] = $user_type.'property_list_masters/property_list_masters_control/insert_style_master';
	$route[$user_type.'property_list_masters/insert_water_company'] = $user_type.'property_list_masters/property_list_masters_control/insert_water_company';
	
	
	//// End Insert ///////////////
	
	
	//////////////End  property list master//////////
        
        ///// Joomla Dashboard /////
        $route[$user_type.'leads_dashboard/view_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record';
        $route[$user_type.'leads_dashboard/update_view'] = $user_type.'leads_dashboard/leads_dashboard_control/update_view';
        $route[$user_type.'leads_dashboard/view_record_index'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index';
	$route[$user_type.'leads_dashboard/view_record_index/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index';
        $route[$user_type.'leads_dashboard/view_record_index/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index';
	$route[$user_type.'leads_dashboard/view_record_index_fav'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_fav';
	$route[$user_type.'leads_dashboard/view_record_index_fav/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_fav';
        $route[$user_type.'leads_dashboard/view_record_index_fav/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_fav';
	$route[$user_type.'leads_dashboard/view_record_index_savser'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_savser';
	$route[$user_type.'leads_dashboard/view_record_index_savser/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_savser';
        $route[$user_type.'leads_dashboard/view_record_index_savser/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_savser';
	$route[$user_type.'leads_dashboard/view_record_index_prop_view'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_prop_view';
	$route[$user_type.'leads_dashboard/view_record_index_prop_view/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_prop_view';
        $route[$user_type.'leads_dashboard/view_record_index_prop_view/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_prop_view';
	$route[$user_type.'leads_dashboard/view_record_index_lastlog'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_lastlog';
	$route[$user_type.'leads_dashboard/view_record_index_lastlog/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_lastlog';
        $route[$user_type.'leads_dashboard/view_record_index_lastlog/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_lastlog';
        $route[$user_type.'leads_dashboard/contact_register_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_contact_register_popup';
        $route[$user_type.'leads_dashboard/view_saved_searches_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_saved_searches_popup';
        $route[$user_type.'leads_dashboard/favorite_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_favorite_popup';
        $route[$user_type.'leads_dashboard/properties_viewed_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_properties_viewed_popup';
        $route[$user_type.'leads_dashboard/last_login_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_last_login_popup';
        $route[$user_type.'leads_dashboard/add_saved_searches/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/add_saved_searches';
        $route[$user_type.'leads_dashboard/edit_saved_searches/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/edit_saved_searches';
        $route[$user_type.'leads_dashboard/edit_saved_searches/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/edit_saved_searches';
        $route[$user_type.'leads_dashboard/insert_saved_search_data'] = $user_type.'leads_dashboard/leads_dashboard_control/insert_saved_search_data';
        $route[$user_type.'leads_dashboard/update_saved_search_data'] = $user_type.'leads_dashboard/leads_dashboard_control/update_saved_search_data';
        $route[$user_type.'leads_dashboard/selectedview_session'] = $user_type.'leads_dashboard/leads_dashboard_control/selectedview_session';

        $route[$user_type.'leads_dashboard/delete_email_trans_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/delete_email_trans_record';
		$route[$user_type.'leads_dashboard/delete_phone_trans_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/delete_phone_trans_record';
		$route[$user_type.'leads_dashboard/delete_address_trans_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/delete_address_trans_record';
		$route[$user_type.'leads_dashboard/delete_website_trans_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/delete_website_trans_record';
		$route[$user_type.'leads_dashboard/delete_social_trans_record/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/delete_social_trans_record';
		$route[$user_type.'leads_dashboard/view_record_index_val_searched/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
		$route[$user_type.'leads_dashboard/view_record_index_val_searched'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
		$route[$user_type.'leads_dashboard/view_record_index_val_searched/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
       
	    $route[$user_type.'leads_dashboard/view_record_index_valuation_searched'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
		$route[$user_type.'leads_dashboard/view_record_index_valuation_searched/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
        $route[$user_type.'leads_dashboard/view_record_index_valuation_searched/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/view_record_index_val_searched';
	    $route[$user_type.'leads_dashboard/valuation_searched_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_valuation_searched_popup';
	    $route[$user_type.'leads_dashboard/edit_valuation_searched/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/edit_valuation_searched';
        $route[$user_type.'leads_dashboard/edit_valuation_searched/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/edit_valuation_searched';
	   $route[$user_type.'leads_dashboard/update_valuation_searched_data'] = $user_type.'leads_dashboard/leads_dashboard_control/update_valuation_searched_data';
	   
	    ///

        $route[$user_type.'leads_dashboard/change_contact_type'] = $user_type.'leads_dashboard/leads_dashboard_control/change_contact_type';
		 $route[$user_type.'leads_dashboard/change_contact_category'] = $user_type.'leads_dashboard/leads_dashboard_control/change_contact_category';
		  $route[$user_type.'leads_dashboard/view_contact_interaction_plan_list'] = $user_type.'leads_dashboard/leads_dashboard_control/view_contact_interaction_plan_list';
                  
        $route[$user_type.'leads_dashboard/property_contact_form/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/property_contact_form';
        $route[$user_type.'leads_dashboard/property_contact_form/(:num)/(:num)'] = $user_type.'leads_dashboard/leads_dashboard_control/property_contact_form';
        $route[$user_type.'leads_dashboard/property_contact_popup'] = $user_type.'leads_dashboard/leads_dashboard_control/view_property_contact_popup';
        ///

        $route[$user_type.'contacts/get_property/(:num)'] = $user_type.'contacts/contacts_control/get_property';
	$route[$user_type.'contacts/view_record_index'] = $user_type.'contacts/contacts_control/view_record_index';
	$route[$user_type.'contacts/view_record_index/(:num)'] = $user_type.'contacts/contacts_control/view_record_index';
        $route[$user_type.'contacts/view_record_index/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index';
	$route[$user_type.'contacts/view_record_index_fav'] = $user_type.'contacts/contacts_control/view_record_index_fav';
	$route[$user_type.'contacts/view_record_index_fav/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_fav';
        $route[$user_type.'contacts/view_record_index_fav/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_fav';
	$route[$user_type.'contacts/view_record_index_savser'] = $user_type.'contacts/contacts_control/view_record_index_savser';
	$route[$user_type.'contacts/view_record_index_savser/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_savser';
        $route[$user_type.'contacts/view_record_index_savser/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_savser';
	$route[$user_type.'contacts/view_record_index_prop_view'] = $user_type.'contacts/contacts_control/view_record_index_prop_view';
	$route[$user_type.'contacts/view_record_index_prop_view/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_prop_view';
        $route[$user_type.'contacts/view_record_index_prop_view/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_prop_view';
	$route[$user_type.'contacts/view_record_index_lastlog'] = $user_type.'contacts/contacts_control/view_record_index_lastlog';
	$route[$user_type.'contacts/view_record_index_lastlog/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_lastlog';
        $route[$user_type.'contacts/view_record_index_lastlog/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_lastlog';
        $route[$user_type.'contacts/contact_register_popup'] = $user_type.'contacts/contacts_control/view_contact_register_popup';
        $route[$user_type.'contacts/view_saved_searches_popup'] = $user_type.'contacts/contacts_control/view_saved_searches_popup';
        $route[$user_type.'contacts/favorite_popup'] = $user_type.'contacts/contacts_control/view_favorite_popup';
        $route[$user_type.'contacts/properties_viewed_popup'] = $user_type.'contacts/contacts_control/view_properties_viewed_popup';
        $route[$user_type.'contacts/last_login_popup'] = $user_type.'contacts/contacts_control/view_last_login_popup';
        $route[$user_type.'contacts/add_saved_searches/(:num)'] = $user_type.'contacts/contacts_control/add_saved_searches';
        $route[$user_type.'contacts/edit_saved_searches/(:num)/(:num)'] = $user_type.'contacts/contacts_control/edit_saved_searches';
        $route[$user_type.'contacts/edit_saved_searches/(:num)'] = $user_type.'contacts/contacts_control/edit_saved_searches';
        $route[$user_type.'contacts/insert_saved_search_data'] = $user_type.'contacts/contacts_control/insert_saved_search_data';
        $route[$user_type.'contacts/update_saved_search_data'] = $user_type.'contacts/contacts_control/update_saved_search_data';
        $route[$user_type.'contacts/view_record_index_valuation_searched'] = $user_type.'contacts/contacts_control/view_record_index_val_searched';
	$route[$user_type.'contacts/view_record_index_valuation_searched/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_val_searched';
        $route[$user_type.'contacts/view_record_index_valuation_searched/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_val_searched';
        $route[$user_type.'contacts/valuation_searched_popup'] = $user_type.'contacts/contacts_control/view_valuation_searched_popup';
        $route[$user_type.'contacts/view_record_index_valuation_contact'] = $user_type.'contacts/contacts_control/view_record_index_val_contact';
	$route[$user_type.'contacts/view_record_index_valuation_contact/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_val_contact';
        $route[$user_type.'contacts/view_record_index_valuation_contact/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_val_contact';
        $route[$user_type.'contacts/valuation_contact_popup'] = $user_type.'contacts/contacts_control/view_valuation_contact_popup';
        $route[$user_type.'contacts/view_record_index_property_contact'] = $user_type.'contacts/contacts_control/view_record_index_property_contact';
	$route[$user_type.'contacts/view_record_index_property_contact/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_property_contact';
        $route[$user_type.'contacts/view_record_index_property_contact/(:num)/(:num)'] = $user_type.'contacts/contacts_control/view_record_index_property_contact';
        $route[$user_type.'contacts/property_contact_popup'] = $user_type.'contacts/contacts_control/view_property_contact_popup';
        
        $route[$user_type.'contacts/edit_valuation_searched/(:num)/(:num)'] = $user_type.'contacts/contacts_control/edit_valuation_searched';
        $route[$user_type.'contacts/edit_valuation_searched/(:num)'] = $user_type.'contacts/contacts_control/edit_valuation_searched';
        $route[$user_type.'contacts/update_valuation_searched_data'] = $user_type.'contacts/contacts_control/update_valuation_searched_data';
        
        
	$route[$user_type.'contacts/delete_email_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_email_trans_record';
	$route[$user_type.'contacts/delete_field_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_field_trans_record';
	$route[$user_type.'contacts/delete_phone_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_phone_trans_record';
	$route[$user_type.'contacts/delete_address_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_address_trans_record';
	$route[$user_type.'contacts/delete_website_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_website_trans_record';
	$route[$user_type.'contacts/delete_social_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_social_trans_record';
	$route[$user_type.'contacts/delete_tag_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_tag_trans_record';
	$route[$user_type.'contacts/delete_communication_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_communication_trans_record';
	$route[$user_type.'contacts/delete_document_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_document_trans_record';

	$route[$user_type.'listing_manager/delete_document_trans_record/(:num)'] = $user_type.'listing_manager/listing_manager_control/delete_document_trans_record';


	$route[$user_type.'listing_manager/delete_offers_trans_record/(:num)'] = $user_type.'listing_manager/listing_manager_control/delete_offers_trans_record';

	$route[$user_type.'listing_manager/delete_price_trans_record/(:num)'] = $user_type.'listing_manager/listing_manager_control/delete_price_trans_record';

	$route[$user_type.'listing_manager/delete_houses_trans_record/(:num)'] = $user_type.'listing_manager/listing_manager_control/delete_houses_trans_record';

	$route[$user_type.'listing_manager/delete_showings_trans_record/(:num)'] = $user_type.'listing_manager/listing_manager_control/delete_showings_trans_record';
	$route[$user_type.$conntrol.'/view_contacts'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_contacts';
	
	$route[$user_type.'work_time_config_master/delete_leave_record/(:num)'] = $user_type.'work_time_config_master/work_time_config_master_control/delete_leave_record';
	
	$route[$user_type.'work_time_config_master/delete_rules_record/(:num)'] = $user_type.'work_time_config_master/work_time_config_master_control/delete_rules_record';

	// Mail Out
	$route[$user_type.'mail_out/get_envelope/(:any)'] = $user_type.'mail_out/mail_out_control/get_envelope';
	//$route[$user_type.'mail_out/ajax_subcategory'] = $user_type.'mail_out/mail_out_control/ajax_subcategory';
	$route[$user_type.'mail_out/mail_out_data'] = $user_type.'mail_out/mail_out_control/mail_out_data';
	$route[$user_type.'mail_out/search_contact_ajax'] = $user_type.'mail_out/mail_out_control/search_contact_ajax';
	$route[$user_type.'mail_out/search_contact_ajax/(:num)'] =$user_type.'mail_out/mail_out_control/search_contact_ajax';
	$route[$user_type.'mail_out/add_contacts_to_mail_out'] =$user_type.'mail_out/mail_out_control/add_contacts_to_mail_out';
	$route[$user_type.'mail_out/mail_out_print'] =$user_type.'mail_out/mail_out_control/mail_out_print';
	$route[$user_type.$conntrol.'/generate_pdf'] = $user_type.$conntrol.'/'.$conntrol.'_control/generate_pdf';
	$route[$user_type.'interaction/add_record'] = $user_type.'interaction/interaction_control/add_record';
	$route[$user_type.'interaction/add_record/(:num)'] = $user_type.'interaction/interaction_control/add_record';
	$route[$user_type.'interaction/insert_data'] = $user_type.'interaction/interaction_control/insert_data';
	$route[$user_type.'contacts/update_conversations/(:num)'] = $user_type.'contacts/contacts_control/update_conversations';
	
	$route[$user_type.'interaction/(:num)/view_archive'] = $user_type.'interaction/interaction_control/view_archive';
	$route[$user_type.'interaction/(:num)/view_archive/(:num)'] = $user_type.'interaction/interaction_control/view_archive';
	
	$route[$user_type.'default_interaction/(:num)/view_archive'] = $user_type.'default_interaction/default_interaction_control/view_archive';
	$route[$user_type.'default_interaction/(:num)/view_archive/(:num)'] = $user_type.'default_interaction/default_interaction_control/view_archive';
	
	$route[$user_type.$conntrol.'/view_archive'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_archive';
	$route[$user_type.$conntrol.'/view_archive/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_archive';
	
	/*$route[$user_type.'contacts/view_archive'] = $user_type.'contacts/contacts_control/view_archive';
	$route[$user_type.'contacts/view_archive/(:num)'] = $user_type.'contacts/contacts_control/view_archive';*/
	
	$route[$user_type.'contacts/ajax_archive_all'] = $user_type.'contacts/contacts_control/ajax_archive_all';
	$route[$user_type.'contacts/ajax_add_to_active_all'] = $user_type.'contacts/contacts_control/ajax_add_to_active_all';
	
	$route[$user_type.'interaction/(:num)/ajax_Active_all'] = $user_type.'interaction/interaction_control/ajax_Active_all';
	$route[$user_type.'interaction/(:num)/ajax_Inactive_all'] = $user_type.'interaction/interaction_control/ajax_Inactive_all';
	$route[$user_type.'default_interaction/(:num)/ajax_Active_all'] = $user_type.'default_interaction/default_interaction_control/ajax_Active_all';
	$route[$user_type.'default_interaction/(:num)/ajax_Inactive_all'] = $user_type.'default_interaction/default_interaction_control/ajax_Inactive_all';
	
	/* Start */
	
	$route[$user_type.$conntrol.'/queued_list'] = $user_type.$conntrol.'/'.$conntrol.'_control/queued_list';
	
	$route[$user_type.$conntrol.'/view_interaction_data/(:num)/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_interaction_data';
	$route[$user_type.$conntrol.'/queued_list/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/queued_list';
	
	//$route[$user_type.$conntrol.'/interaction_queued_list'] = $user_type.$conntrol.'/'.$conntrol.'_control/interaction_queued_list';
	$route[$user_type.$conntrol.'/interaction_queued_list/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/interaction_queued_list';
	$route[$user_type.$conntrol.'/interaction_queued_list/(:num)/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/interaction_queued_list';
	
	$route[$user_type.$conntrol.'/interaction_plan_queued_list/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/interaction_plan_queued_list';
	$route[$user_type.$conntrol.'/interaction_plan_queued_list/(:num)/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/interaction_plan_queued_list';
	
	$route[$user_type.$conntrol.'/premium_plan_update/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/premium_plan_update';
	$route[$user_type.$conntrol.'/released_premium_plan/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/released_premium_plan';
	$route[$user_type.$conntrol.'/contact_details'] = $user_type.$conntrol.'/'.$conntrol.'_control/contact_details';
	$route[$user_type.$conntrol.'/ajax_category'] = $user_type.$conntrol.'/'.$conntrol.'_control/ajax_category';
	//$route[$user_type.$conntrol.'/add_record/(:num)/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_record';
	
	$route[$user_type.$conntrol.'/facebook_contact'] = $user_type.$conntrol.'/'.$conntrol.'_control/facebook_contact';
	
	$route[$user_type.$conntrol.'/fbconnection'] = $user_type.$conntrol.'/'.$conntrol.'_control/fbconnection';
	
	//$route['user/'.$conntrol.'/resend_mail/(:any)/(:any)/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route[$user_type.$conntrol.'/delete_record_trans'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_record_trans';
	
	$route[$user_type.$conntrol.'/open_completed_task'] = $user_type.$conntrol.'/'.$conntrol.'_control/open_completed_task';
	$route[$user_type.$conntrol.'/open_completed_task/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/open_completed_task';
	$route[$user_type.$conntrol.'/last_month_contact'] = $user_type.$conntrol.'/'.$conntrol.'_control/last_month_contact';
	$route[$user_type.$conntrol.'/last_month_contact/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/last_month_contact';
	$route[$user_type.$conntrol.'/add_record/(:any)'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_record';
	$route[$user_type.$conntrol.'/sent_email'] = $user_type.$conntrol.'/'.$conntrol.'_control/sent_email';
	$route[$user_type.$conntrol.'/selectedview_session'] = $user_type.$conntrol.'/'.$conntrol.'_control/selectedview_session';
	$route[$user_type.$conntrol.'/ajax_templatename'] = $user_type.$conntrol.'/'.$conntrol.'_control/ajax_templatename';

	/* END */
	
	// Generale Configuration
	$route[$user_type.$conntrol.'/'.$conntrol.'_home'] = $user_type.$conntrol.'/'.$conntrol.'_control/'.$conntrol.'_home';
	
	$route[$user_type.'general_configuration/delete_user_record/(:num)'] = $user_type.'general_configuration/general_configuration_control/delete_user_record';
	
	$route[$user_type.'interaction_plans/search_contact_ajax'] = $user_type.'interaction_plans/interaction_plans_control/search_contact_ajax';
        $route[$user_type.'joomla_property_cron/property_cron_crm_index'] = $user_type.'joomla_property_cron/joomla_property_cron_control/property_cron_crm_index';
        $route[$user_type.'joomla_property_cron/property_cron_crm_index/(:num)'] = $user_type.'joomla_property_cron/joomla_property_cron_control/property_cron_crm_index';
        $route[$user_type.'joomla_property_cron/property_cron_crm_index/(:num)/(:num)'] = $user_type.'joomla_property_cron/joomla_property_cron_control/property_cron_crm_index';
        $route[$user_type.'joomla_property_cron/selected_view_session'] = $user_type.'joomla_property_cron/joomla_property_cron_control/selected_view_session';
        $route[$user_type.'joomla_property_cron/assigned_contact_list_web'] = $user_type.'joomla_property_cron/joomla_property_cron_control/assigned_contact_list_web';
        $route[$user_type.'joomla_property_cron/assigned_contact_list_crm'] = $user_type.'joomla_property_cron/joomla_property_cron_control/assigned_contact_list_crm';
        $route[$user_type.'joomla_property_cron/search_contact_ajax'] = $user_type.'joomla_property_cron/joomla_property_cron_control/search_contact_ajax';
        $route[$user_type.'joomla_property_cron/search_contact_ajax/(:num)'] = $user_type.'joomla_property_cron/joomla_property_cron_control/search_contact_ajax';
        $route[$user_type.'joomla_property_cron/delete_contact_from_valuation'] = $user_type.'joomla_property_cron/joomla_property_cron_control/delete_contact_from_valuation';
        $route[$user_type.'joomla_property_cron/getcitylist'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getcitylist';
        $route[$user_type.'joomla_property_cron/getneighborlist'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getneighborlist';
        $route[$user_type.'joomla_property_cron/getzipcodelist'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getzipcodelist';
        $route[$user_type.'joomla_property_cron/getstatelist1'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getstatelist1';
        $route[$user_type.'joomla_property_cron/getneighborlist1'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getneighborlist1';
        $route[$user_type.'joomla_property_cron/getzipcodelist1'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getzipcodelist1';
        $route[$user_type.$conntrol.'/check_address1'] = $user_type.$conntrol.'/'.$conntrol.'_control/check_address1';
        $route[$user_type.'joomla_property_cron/getcity_nei_ziplist'] = $user_type.'joomla_property_cron/joomla_property_cron_control/getcity_nei_ziplist';
        $route[$user_type.$conntrol.'/check_address'] = $user_type.$conntrol.'/'.$conntrol.'_control/check_address';
        $route[$user_type.$conntrol.'/assigned_contact_list'] = $user_type.$conntrol.'/'.$conntrol.'_control/assigned_contact_list';
        $route[$user_type.$conntrol.'/assigned_contact_list/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/assigned_contact_list';
        $route[$user_type.$conntrol.'/ajax_delete_contact_from_vreport'] = $user_type.$conntrol.'/'.$conntrol.'_control/ajax_delete_contact_from_vreport';
        
	$route[$user_type.'listing_manager/search_contact_ajax'] = $user_type.'listing_manager/listing_manager_control/search_contact_ajax';


	$route[$user_type.'interaction_plans/assign_plan'] = $user_type.'interaction_plans/interaction_plans_control/assign_plan';
	$route[$user_type.'interaction_plans/assign_plan/(:num)'] = $user_type.'interaction_plans/interaction_plans_control/assign_plan';
	$route[$user_type.'interaction_plans/search_contact_ajax/(:num)'] = $user_type.'interaction_plans/interaction_plans_control/search_contact_ajax';

	/*$route[$user_type.'interaction_plans/view_archive'] = $user_type.'interaction_plans/interaction_plans_control/view_archive';
	
	$route[$user_type.'interaction_plans/view_archive/(:num)'] = $user_type.'interaction_plans/interaction_plans_control/view_archive';*/
	
	$route[$user_type.$conntrol.'/ajax_Active_all'] = $user_type.$conntrol.'/'.$conntrol.'_control/ajax_Active_all';
	//$route[$user_type.'interaction_plans/ajax_Active_all'] = $user_type.'interaction_plans/interaction_plans_control/ajax_Active_all';
	
	//$route[$user_type.'interaction_plans/ajax_Inactive_all'] = $user_type.'interaction_plans/interaction_plans_control/ajax_Inactive_all';
	$route[$user_type.$conntrol.'/ajax_Inactive_all'] = $user_type.$conntrol.'/'.$conntrol.'_control/ajax_Inactive_all';
	
	$route[$user_type.'interaction_plans/pause_interaction_plan'] = $user_type.'interaction_plans/interaction_plans_control/pause_interaction_plan';
	
	$route[$user_type.'interaction_plans/stop_interaction_plan'] = $user_type.'interaction_plans/interaction_plans_control/stop_interaction_plan';
	
	$route[$user_type.'interaction_plans/play_interaction_plan'] = $user_type.'interaction_plans/interaction_plans_control/play_interaction_plan';
	
	$route[$user_type.'interaction_plans/all_pause_play_stop'] = $user_type.'interaction_plans/interaction_plans_control/all_pause_play_stop';
	
	$route[$user_type.'interaction_plans/delete_contact_from_plan'] = $user_type.'interaction_plans/interaction_plans_control/delete_contact_from_plan';
	
	
	//Default interection plan
	$route[$user_type.'default_interaction_plans/assign_plan'] = $user_type.'default_interaction_plans/default_interaction_plans_control/assign_plan';
	$route[$user_type.'default_interaction_plans/assign_plan/(:num)'] = $user_type.'default_interaction_plans/default_interaction_plans_control/assign_plan';
	$route[$user_type.'default_interaction_plans/search_contact_ajax/(:num)'] = $user_type.'default_interaction_plans/default_interaction_plans_control/search_contact_ajax';

	/*$route[$user_type.'default_interaction_plans/view_archive'] = $user_type.'default_interaction_plans/default_interaction_plans_control/view_archive';
	
	$route[$user_type.'default_interaction_plans/view_archive/(:num)'] = $user_type.'default_interaction_plans/default_interaction_plans_control/view_archive';*/
	
	//$route[$user_type.'default_interaction_plans/ajax_Active_all'] = $user_type.'default_interaction_plans/default_interaction_plans_control/ajax_Active_all';
	
	//$route[$user_type.'default_interaction_plans/ajax_Inactive_all'] = $user_type.'default_interaction_plans/default_interaction_plans_control/ajax_Inactive_all';
	
	$route[$user_type.'default_interaction_plans/pause_interaction_plan'] = $user_type.'default_interaction_plans/default_interaction_plans_control/pause_interaction_plan';
	
	$route[$user_type.'default_interaction_plans/stop_interaction_plan'] = $user_type.'default_interaction_plans/default_interaction_plans_control/stop_interaction_plan';
	
	$route[$user_type.'default_interaction_plans/play_interaction_plan'] = $user_type.'default_interaction_plans/default_interaction_plans_control/play_interaction_plan';
	
	$route[$user_type.'default_interaction_plans/all_pause_play_stop'] = $user_type.'default_interaction_plans/default_interaction_plans_control/all_pause_play_stop';
	
	$route[$user_type.'default_interaction_plans/delete_contact_from_plan'] = $user_type.'default_interaction_plans/default_interaction_plans_control/delete_contact_from_plan';
	
	$route[$user_type.'listing_manager/delete_contact_from_listing_manager'] = $user_type.'listing_manager/listing_manager_control/delete_contact_from_listing_manager';

	$route[$user_type.'task/delete_contact_from_task'] = $user_type.'task/task_control/delete_contact_from_task';
	//social
	//echo $route[$user_type.'social_home'] = $user_type.'social/social_control/social_home';
	$route[$user_type.'social/social_home'] = $user_type.'social/social_control/social_home';
	
	//use management
	
	//$route['admin/user_management/ajax_delete_all'] = 'admin/user_management/user_management_control/ajax_delete_all';
	//$route['admin/letter_library/ajax_delete_all'] = 'admin/letter_library/letter_library_control/ajax_delete_all';
	//$route['admin/phonecall_script/ajax_subcategory'] = 'admin/phonecall_script/phonecall_script_control/ajax_subcategory';
	//$route['admin/letter_library/ajax_subcategory'] = 'admin/letter_library/letter_library_control/ajax_subcategory';
	//$route['admin/sms_texts/ajax_subcategory'] = 'admin/sms_texts/sms_texts_control/ajax_subcategory';
	//$route['admin/email_library/ajax_subcategory'] = 'admin/email_library/email_library_control/ajax_subcategory';
	//$route['admin/socialmedia_post/ajax_subcategory'] = 'admin/socialmedia_post/socialmedia_post_control/ajax_subcategory';
	//$route['admin/envelope_library/ajax_subcategory'] = 'admin/envelope_library/envelope_library_control/ajax_subcategory';
	//$route['admin/label_library/ajax_subcategory'] = 'admin/label_library/label_library_control/ajax_subcategory';
	//$route['admin/socialmedia_post/ajax_delete_all'] = 'admin/socialmedia_post/socialmedia_post_control/ajax_delete_all';
	
	
	// email campaign
	//$route['admin/emails/ajax_subcategory'] = 'admin/emails/emails_control/ajax_subcategory';
	$route[$user_type.''.$conntrol.'/attachmentlist'] = $user_type.''.$conntrol.'/'.$conntrol.'_control/attachmentlist';
	
	/*$route[$user_type.'emails/search_contact_ajax'] = $user_type.'emails/emails_control/search_contact_ajax';
	$route[$user_type.'emails/search_contact_ajax/(:num)'] =$user_type.'emails/emails_control/search_contact_ajax';*/
	$route[$user_type.'emails/search_contact_ajax_cc'] = $user_type.'emails/emails_control/search_contact_ajax_cc';
	$route[$user_type.'emails/search_contact_ajax_cc/(:num)'] =$user_type.'emails/emails_control/search_contact_ajax_cc';
	$route[$user_type.'emails/search_contact_ajax_bcc'] = $user_type.'emails/emails_control/search_contact_ajax_bcc';
	$route[$user_type.'emails/search_contact_ajax_bcc/(:num)'] =$user_type.'emails/emails_control/search_contact_ajax_bcc';
	
	$route[$user_type.'contacts/add_to_archive/(:num)'] =$user_type.'contacts/contacts_control/add_to_archive';
	$route[$user_type.'contacts/add_to_active_list/(:num)'] =$user_type.'contacts/contacts_control/add_to_active_list';
	$route[$user_type.'contacts/property_listing'] =$user_type.'contacts/contacts_control/property_listing';
	$route[$user_type.'contacts/property_listing/(:num)'] =$user_type.'contacts/contacts_control/property_listing';
	
	//use management
	
	//$route['admin/user_management/ajax_delete_all'] = 'admin/user_management/user_management_control/ajax_delete_all';
	//$route['admin/letter_library/ajax_delete_all'] = 'admin/letter_library/letter_library_control/ajax_delete_all';
	//$route['admin/phonecall_script/ajax_subcategory'] = 'admin/phonecall_script/phonecall_script_control/ajax_subcategory';
	//$route['admin/letter_library/ajax_subcategory'] = 'admin/letter_library/letter_library_control/ajax_subcategory';
	//$route['admin/sms_texts/ajax_subcategory'] = 'admin/sms_texts/sms_texts_control/ajax_subcategory';
	//$route['admin/email_library/ajax_subcategory'] = 'admin/email_library/email_library_control/ajax_subcategory';
	//$route['admin/socialmedia_post/ajax_subcategory'] = 'admin/socialmedia_post/socialmedia_post_control/ajax_subcategory';
	//$route['admin/envelope_library/ajax_subcategory'] = 'admin/envelope_library/envelope_library_control/ajax_subcategory';
	//$route['admin/label_library/ajax_subcategory'] = 'admin/label_library/label_library_control/ajax_subcategory';
	//$route['admin/socialmedia_post/ajax_delete_all'] = 'admin/socialmedia_post/socialmedia_post_control/ajax_delete_all';
	
	//$route[$user_type.'emails/ajax_delete_all'] = $user_type.'emails/emails_control/ajax_delete_all';
	
	$route[$user_type.'user_management/delete_email_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_email_trans_record';
	$route[$user_type.'lead_capturing_view/assign_contact/(:num)']=$user_type."lead_capturing_view/lead_capturing_view_control/assign_contact";
	$route[$user_type.'lead_capturing_view/assign_contact/(:num)/(:num)']=$user_type."lead_capturing_view/lead_capturing_view_control/assign_contact";

	$route[$user_type.'lead_capturing_view/assign_lead']=$user_type."lead_capturing_view/lead_capturing_view_control/assign_lead";
	

	$route[$user_type.'lead_capturing_view/user_list']=$user_type."lead_capturing_view/lead_capturing_view_control/user_list";
	

	$route['admin/user_management/delete_phone_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_phone_trans_record';
	$route['admin/user_management/delete_address_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_address_trans_record';
	$route['admin/user_management/delete_website_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_website_trans_record';
	$route['admin/user_management/delete_social_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_social_trans_record';
	$route['admin/user_management/delete_tag_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_tag_trans_record';
	$route['admin/user_management/delete_communication_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_communication_trans_record';
	$route['admin/user_management/delete_document_trans_record/(:num)'] = 'admin/user_management/user_management_control/delete_document_trans_record';
	$route['admin/user_management/upload_document'] = 'admin/user_management/user_management_control/upload_document';
	$route['admin/user_management/update_data_ajax'] = 'admin/user_management/user_management_control/update_data_ajax';
	
	// for upload document from listing manager and update ajax list
	$route['admin/listing_manager/upload_document'] = 'admin/listing_manager/listing_manager_control/upload_document';
	$route['admin/listing_manager/update_data_ajax'] = 'admin/listing_manager/listing_manager_control/update_data_ajax';
	
	
	$route['user/listing_manager/upload_document'] = 'user/listing_manager/listing_manager_control/upload_document';
	$route['user/listing_manager/update_data_ajax'] = 'user/listing_manager/listing_manager_control/update_data_ajax';
	

	$route['admin/user_management/get_doc_trans_data'] = 'admin/user_management/user_management_control/get_doc_trans_data';
	
	$route['admin/user_management/delete_assign_record/(:num)'] = 'admin/user_management/user_management_control/delete_assign_record';
	$route['admin/user_management/assign_contact'] = 'admin/user_management/user_management_control/assign_contact';
	$route['admin/user_management/assign_contact1'] = 'admin/user_management/user_management_control/assign_contact1';
	//$route['admin/user_management/ajax_Active_all'] = 'admin/user_management/user_management_control/ajax_Active_all';
	//$route['admin/user_management/ajax_Inactive_all'] = 'admin/user_management/user_management_control/ajax_Inactive_all';
	$route['admin/user_management/change_password'] = 'admin/user_management/user_management_control/change_password';
	$route['admin/user_management/check_user'] = 'admin/user_management/user_management_control/check_user';
	$route['admin/assistant_management/check_user'] = 'admin/assistant_management/assistant_management_control/check_user';
	$route[$user_type.'user_management/delete_phone_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_phone_trans_record';
	$route[$user_type.'user_management/delete_address_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_address_trans_record';
	$route[$user_type.'user_management/delete_website_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_website_trans_record';
	$route[$user_type.'user_management/delete_social_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_social_trans_record';
	$route[$user_type.'user_management/delete_tag_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_tag_trans_record';
	$route[$user_type.'user_management/delete_communication_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_communication_trans_record';
	$route[$user_type.'user_management/delete_document_trans_record/(:num)'] = $user_type.'user_management/user_management_control/delete_document_trans_record';
	$route[$user_type.'user_management/upload_document'] = $user_type.'user_management/user_management_control/upload_document';
	$route[$user_type.'user_management/update_data_ajax'] = $user_type.'user_management/user_management_control/update_data_ajax';
	
	$route[$user_type.'user_management/get_doc_trans_data'] = $user_type.'user_management/user_management_control/get_doc_trans_data';
	$route[$user_type.'user_management/delete_assign_record/(:num)'] = $user_type.'user_management/user_management_control/delete_assign_record';
	$route[$user_type.'user_management/assign_contact'] = $user_type.'user_management/user_management_control/assign_contact';
	//$route[$user_type.'user_management/ajax_Active_all'] = $user_type.'user_management/user_management_control/ajax_Active_all';
	//$route[$user_type.'user_management/ajax_Inactive_all'] = $user_type.'user_management/user_management_control/ajax_Inactive_all';
	$route[$user_type.'user_management/change_password'] = $user_type.'user_management/user_management_control/change_password';
	$route[$user_type.'user_management/check_user'] = $user_type.'user_management/user_management_control/check_user';

	//right
	$route['superadmin/superadmin_management/check_user'] = 'superadmin/superadmin_management/superadmin_management_control/check_user'; 
	$route['superadmin/admin_management/check_user'] = 'superadmin/admin_management/admin_management_control/check_user';
	$route['superadmin/admin_management/admin_rights'] = 'superadmin/admin_management/admin_management_control/admin_rights';
	$route['superadmin/admin_management/insert_rights'] = 'superadmin/admin_management/admin_management_control/insert_rights';
	$route['superadmin/admin_management/update_rights'] = 'superadmin/admin_management/admin_management_control/update_rights';
	$route['superadmin/admin_management/edit_right/(:num)'] = 'superadmin/admin_management/admin_management_control/edit_right';
	
	$route['admin/user_management/admin_rights'] = 'admin/user_management/user_management_control/admin_rights';
	$route['admin/user_management/insert_rights'] = 'admin/user_management/user_management_control/insert_rights';
	$route['admin/user_management/update_rights'] = 'admin/user_management/user_management_control/update_rights';
	$route['admin/user_management/edit_right/(:num)'] = 'admin/user_management/user_management_control/edit_right';
	
	$route['superadmin/my_profile/check_pass'] = 'superadmin/my_profile/my_profile_control/check_pass';
	$route['superadmin/'.$conntrol.'/check_domain'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/check_domain'; 
	$route['superadmin/country/check_country'] = 'superadmin/country/country_control/check_country';
        $route['superadmin/state/check_state'] = 'superadmin/state/state_control/check_state';
        
        $route['superadmin/child_admin/get_mls'] = 'superadmin/child_admin/child_admin_control/get_mls';
        
	$route[$user_type.'contacts/fb_conversation'] = $user_type.'contacts/contacts_control/fb_conversation';
	$route[$user_type.'contacts/fb_conversation'] = $user_type.'contacts/contacts_control/fb_conversation';
	
	// Drop interaction
	$route[$user_type.'interaction_drop/drop_interaction'] = $user_type.'interaction_drop/interaction_drop_control/drop_interaction';
	$route[$user_type.'interaction_drop/drop_interaction/(:num)'] = $user_type.'interaction_drop/interaction_drop_control/drop_interaction';
	
	$route[$user_type.'interaction/ajax_selecttemplate'] = $user_type.'interaction/interaction_control/ajax_selecttemplate';
	$route[$user_type.'interaction/ajax_selectcategory'] = $user_type.'interaction/interaction_control/ajax_selectcategory';
	$route[$user_type.'interaction/assign_interaction'] = $user_type.'interaction/interaction_control/assign_interaction';
	$route[$user_type.'interaction/assign_interaction/(:num)'] = $user_type.'interaction/interaction_control/assign_interaction';
	$route[$user_type.'interaction/(:num)/(:num)'] = $user_type.'interaction/interaction_control';
	
	
	//default interection
	$route[$user_type.'default_interaction/ajax_selecttemplate'] = $user_type.'default_interaction/default_interaction_control/ajax_selecttemplate';
	$route[$user_type.'default_interaction/ajax_selectcategory'] = $user_type.'default_interaction/default_interaction_control/ajax_selectcategory';
	$route[$user_type.'default_interaction/assign_interaction'] = $user_type.'default_interaction/default_interaction_control/assign_interaction';
	$route[$user_type.'default_interaction/assign_interaction/(:num)'] = $user_type.'default_interaction/default_interaction_control/assign_interaction';
	$route[$user_type.'default_interaction/(:num)/(:num)'] = $user_type.'default_interaction/default_interaction_control';
	
	// task module
	//$route['admin/task/ajax_delete_all'] = 'admin/task/task_control/ajax_delete_all';
	$route[$user_type.$conntrol.'/add_record/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_record';
	$route[$user_type.$conntrol.'/add_linkedin'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_linkedin';
	$route[$user_type.$conntrol.'/linkedin_insert_data'] = $user_type.$conntrol.'/'.$conntrol.'_control/linkedin_insert_data';
	$route[$user_type.$conntrol.'/insert_linkedin_data'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_linkedin_data';	
	
	$route[$user_type.$conntrol.'/disconnect_linkedin'] = $user_type.$conntrol.'/'.$conntrol.'_control/disconnect_linkedin';	
	$route[$user_type.$conntrol.'/disconnect_twitter'] = $user_type.$conntrol.'/'.$conntrol.'_control/disconnect_twitter';
	$route[$user_type.$conntrol.'/disconnect_google'] = $user_type.$conntrol.'/'.$conntrol.'_control/disconnect_google';	
	$route[$user_type.$conntrol.'/disconnect_bombbomb'] = $user_type.$conntrol.'/'.$conntrol.'_control/disconnect_bombbomb';	
	
	$route[$user_type.$conntrol.'/check_twilio_number'] = $user_type.$conntrol.'/'.$conntrol.'_control/check_twilio_number';
	
	//twitter
	$route[$user_type.$conntrol.'/add_twitter'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_twitter';
	$route[$user_type.$conntrol.'/twitter_callback'] = $user_type.$conntrol.'/'.$conntrol.'_control/twitter_callback';
	$route[$user_type.$conntrol.'/clearsessions'] = $user_type.$conntrol.'/'.$conntrol.'_control/clearsessions';
	$route[$user_type.$conntrol.'/get_twitter_data'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_twitter_data';
	$route[$user_type.$conntrol.'/send_twitter_message'] = $user_type.$conntrol.'/'.$conntrol.'_control/send_twitter_message';	
	
	//google contact
	$route[$user_type.$conntrol.'/import_google_contact'] = $user_type.$conntrol.'/'.$conntrol.'_control/import_google_contact';
	$route[$user_type.$conntrol.'/google_connection'] = $user_type.$conntrol.'/'.$conntrol.'_control/google_connection';
	
	//bomb bomb connection
	$route[$user_type.$conntrol.'/bombbomb_connection'] = $user_type.$conntrol.'/'.$conntrol.'_control/bombbomb_connection';
	$route[$user_type.$conntrol.'/AddVideo'] = $user_type.$conntrol.'/'.$conntrol.'_control/AddVideo';
	$route[$user_type.$conntrol.'/VideoList'] = $user_type.$conntrol.'/'.$conntrol.'_control/VideoList';
	$route[$user_type.$conntrol.'/emailTracking'] = $user_type.$conntrol.'/'.$conntrol.'_control/emailTracking';
		
	$route[$user_type.$conntrol.'/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/';
	$route[$user_type.$conntrol.'/delete_record/(:num)/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route[$user_type.$conntrol.'/index/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/index';
	
	$route['superadmin/'.$conntrol.'/add_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/add_record';
	$route['superadmin/'.$conntrol.'/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/';
	$route['superadmin/'.$conntrol.'/delete_record/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route['superadmin/'.$conntrol.'/index/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/index';
	
	$route[$user_type.'task/my_task'] = $user_type.'task/task_control/my_task';
	$route[$user_type.'task/my_task/(:num)'] = $user_type.'task/task_control/my_task';
	$route[$user_type.'task/my_completed_task'] = $user_type.'task/task_control/my_completed_task';
	$route[$user_type.'task/my_completed_task/(:num)'] = $user_type.'task/task_control/my_completed_task';
	$route[$user_type.$conntrol.'/completed_task'] = $user_type.$conntrol.'/'.$conntrol.'_control/completed_task';
	$route[$user_type.$conntrol.'/completed_task/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/completed_task';
	$route[$user_type.'contacts/delete_last_import/(:num)'] = $user_type.'contacts/contacts_control/delete_last_import';

if($flag==3){
	//echo $conntrol;exit;
	$route['superadmin/'.$conntrol] = "superadmin/".$conntrol."/".$conntrol."_control";
	
	$route['superadmin/'.$conntrol.'/download_form/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/download_form';
	//$route['superadmin/'.$conntrol.'/resend_mail'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route['superadmin/'.$conntrol.'/resend_mail/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route['superadmin/'.$conntrol.'/resend_mail/(:any)/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	
	$route['superadmin/'.$conntrol.'/add_record'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/add_record';
	$route['superadmin/'.$conntrol.'/insert_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_data';
	$route['superadmin/'.$conntrol.'/edit_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['superadmin/'.$conntrol.'/edit_record'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['superadmin/'.$conntrol.'/copy_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/copy_record';
	$route['superadmin/'.$conntrol.'/edit_record/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['superadmin/'.$conntrol.'/edit_record/(:num)/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['superadmin/'.$conntrol.'/view_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['superadmin/'.$conntrol.'/view_record/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['superadmin/'.$conntrol.'/update_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_data';
	$route['superadmin/'.$conntrol.'/delete_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route['superadmin/'.$conntrol.'/tipslist/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control';
	$route['superadmin/'.$conntrol.'/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control';
	$route['superadmin/'.$conntrol.'/msg/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control';
	$route['superadmin/'.$conntrol.'/unpublish_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/unpublish_record';
	$route['superadmin/'.$conntrol.'/publish_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/publish_record';
	$route['superadmin/'.$conntrol.'/delete_icon'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_icon';
	$route['superadmin/'.$conntrol.'/upload_image'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/upload_image';
	$route['superadmin/'.$conntrol.'/delete_image'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_image';
	$route['superadmin/'.$conntrol.'/send_invoice/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/send_invoice';
	$route['superadmin/'.$conntrol.'/insert_plan_type'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_plan_type';
	$route['superadmin/'.$conntrol.'/insert_status'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_status';
	$route['superadmin/'.$conntrol.'/update_plan_type'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_plan_type';
	$route['superadmin/'.$conntrol.'/update_status'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_status';
	$route['superadmin/'.$conntrol.'/update_leave'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_leave';
	$route['superadmin/'.$conntrol.'/update_rules'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_rules';
	$route['superadmin/'.$conntrol.'/delete_plan_type_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_plan_type_record';
	$route['superadmin/'.$conntrol.'/delete_rules_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_rules_record';

        $route['superadmin/'.$conntrol.'/delete_status_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_status_record';
		
	$route['superadmin/'.$conntrol.'/ajax_subcategory'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_subcategory';
	$route['superadmin/'.$conntrol.'/ajax_templatedata'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_templatedata';
	//$route['superadmin/'.$conntrol.'/ajax_templatename'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_templatename';
	
	$route['superadmin/'.$conntrol.'/ajax_delete_all'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all';
	$route['superadmin/'.$conntrol.'/ajax_delete_all1'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all1';
	$route['superadmin/'.$conntrol.'/ajax_delete_attachment'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_attachment';
	$route['superadmin/'.$conntrol.'/delete_attachment'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/delete_attachment';
	
	/// MLS masters

	$route[$user_type.$conntrol.'/insert_property_type'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_property_type';
	$route[$user_type.$conntrol.'/update_property_type'] = $user_type.$conntrol.'/'.$conntrol.'_control/update_property_type';
	$route[$user_type.$conntrol.'/delete_property_type/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_property_type';
	$route[$user_type.$conntrol.'/view_property/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_property';
	$route[$user_type.$conntrol.'/insert_mls_status'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_mls_status';
	$route[$user_type.$conntrol.'/update_mls_status'] = $user_type.$conntrol.'/'.$conntrol.'_control/update_mls_status';
	$route[$user_type.$conntrol.'/delete_mls_status/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_mls_status';
	$route[$user_type.$conntrol.'/insert_mls_area'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_mls_area';
	$route[$user_type.$conntrol.'/update_mls_area'] = $user_type.$conntrol.'/'.$conntrol.'_control/update_mls_area';
	$route[$user_type.$conntrol.'/delete_mls_area/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_mls_area';

	$route[$user_type.$conntrol.'/insert_mls_settings'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_mls_settings';
	$route[$user_type.$conntrol.'/edit_mls_settings/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/edit_mls_settings';
	
	$route[$user_type.$conntrol.'/insert_mls_csv'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_mls_csv';
	$route[$user_type.$conntrol.'/upload_csv'] = $user_type.$conntrol.'/'.$conntrol.'_control/upload_csv';
	$route[$user_type.$conntrol.'/add_new_field'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_new_field';
	$route[$user_type.$conntrol.'/insert_mls'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_mls';
	$route[$user_type.$conntrol.'/get_filed_list'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_filed_list';
	$route[$user_type.$conntrol.'/add_mls/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_mls';
	$route[$user_type.$conntrol.'/delete_last_import/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/delete_last_import/(:num)';
	/*$route[$user_type.'mls/upload_csv'] = $user_type.'mls/mls_control/upload_csv';
	$route[$user_type.'mls/add_new_field'] = $user_type.'mls/mls_control/add_new_field';
	$route[$user_type.'mls/insert_mls'] = $user_type.'mls/mls_control/insert_mls';
	$route[$user_type.'mls/get_filed_list'] = $user_type.'mls/mls_control/get_filed_list';
	$route[$user_type.'mls/delete_last_import/(:num)'] = $user_type.'mls/mls_control/delete_last_import';*/
	$route[$user_type.'mls_map/get_table_stucture'] = $user_type.'mls_map/mls_map_control/get_table_stucture';
	$route[$user_type.'mls_map/get_table_data/(:any)'] = $user_type.'mls_map/mls_map_control/get_table_data';
	$route[$user_type.'mls_map/insert_mls_fields'] = $user_type.'mls_map/mls_map_control/insert_mls_fields';
	$route[$user_type.'mls_map/cron_link'] = $user_type.'mls_map/mls_map_control/cron_link';
	$route[$user_type.'mls_map/mls_cron_link/(:num)'] = $user_type.'mls_map/mls_map_control/mls_cron_link';
	$route[$user_type.'mls_map/set_cron_link'] = $user_type.'mls_map/mls_map_control/set_cron_link';
	
	$route[$user_type.'mls_map/create_cron_url/(:num)'] = $user_type.'mls_map/mls_map_control/create_cron_url';
	
	
	//MLS routes
	
	$route['superadmin/'.$conntrol.'/dump_database'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/dump_database';
	$route['superadmin/'.$conntrol.'/dump_master_database'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/dump_master_database';
	$route['superadmin/'.$conntrol.'/add_table_record/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/add_table_record';
	$route['superadmin/'.$conntrol.'/import_database/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_database';
	$route['superadmin/'.$conntrol.'/import_staging_database/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_staging_database';
	$route['superadmin/'.$conntrol.'/import_master_database/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_master_database';
	
	$route['superadmin/'.$conntrol.'/insert_master_table'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_master_table';
	
	//Dumb mapping table of mls
	$route['superadmin/'.$conntrol.'/import_mls_map/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_mls_map';
	$route['superadmin/'.$conntrol.'/import_member_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_member_map';
	$route['superadmin/'.$conntrol.'/import_office_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_office_map';
	$route['superadmin/'.$conntrol.'/import_school_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_school_map';
	$route['superadmin/'.$conntrol.'/import_area_community_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_area_community_map';
	$route['superadmin/'.$conntrol.'/import_amenity_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_amenity_map';
	$route['superadmin/'.$conntrol.'/import_prop_history_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_prop_history_map';
	$route['superadmin/'.$conntrol.'/import_image_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_image_map';
	$route['superadmin/'.$conntrol.'/import_image_map1/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_image_map1';
	$route['superadmin/'.$conntrol.'/import_property_map/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_property_map';
	$route['superadmin/'.$conntrol.'/import_property_map_indi/(:any)/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_property_map_indi';
	$route['superadmin/'.$conntrol.'/import_updated_property/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/import_updated_property';
	$route['superadmin/'.$conntrol.'/retrieve_amenity_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_amenity_data';
	$route['superadmin/'.$conntrol.'/retrieve_amenity_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_amenity_data';
	$route['superadmin/'.$conntrol.'/retrieve_area_community_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_area_community_data';
	$route['superadmin/'.$conntrol.'/retrieve_area_community_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_area_community_data';
	$route['superadmin/'.$conntrol.'/retrieve_image_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data';
	$route['superadmin/'.$conntrol.'/retrieve_image_data/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data';
    $route['superadmin/'.$conntrol.'/retrieve_image_data/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data';
    $route['superadmin/'.$conntrol.'/retrieve_image_data/(:num)/(:num)/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data';
    
    $route['superadmin/'.$conntrol.'/retrieve_image_data_indi/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data_indi';
	$route['superadmin/'.$conntrol.'/create_image_links'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/create_image_links';
	$route['superadmin/'.$conntrol.'/create_listing_crons'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/create_listing_crons';
	$route['superadmin/'.$conntrol.'/create_crons_links'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/create_crons_links';
	$route['superadmin/'.$conntrol.'/create_crons_links'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/create_crons_links';
    $route['superadmin/'.$conntrol.'/create_update_property_links'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/create_update_property_links';
	
	$route['superadmin/'.$conntrol.'/retrieve_listing_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data';
	$route['superadmin/'.$conntrol.'/retrieve_listing_data1'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data1';
	$route['superadmin/'.$conntrol.'/retrieve_listing_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data';
	$route['superadmin/'.$conntrol.'/retrieve_listing_data_indi/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data_indi';
	$route['superadmin/'.$conntrol.'/retrieve_listing_data1/(:any)/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data1';
	$route['superadmin/'.$conntrol.'/retrieve_listing_data1/(:any)/(:any)/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data1';
	$route['superadmin/'.$conntrol.'/retrieve_listing_history_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_history_data';
	$route['superadmin/'.$conntrol.'/retrieve_listing_history_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_history_data';
	$route['superadmin/'.$conntrol.'/retrieve_member_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_member_data';
	$route['superadmin/'.$conntrol.'/retrieve_member_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_member_data';
	$route['superadmin/'.$conntrol.'/retrieve_office_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_office_data';
	$route['superadmin/'.$conntrol.'/retrieve_office_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_office_data';
	$route['superadmin/'.$conntrol.'/retrieve_school_data'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_school_data';
	$route['superadmin/'.$conntrol.'/retrieve_school_data/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_school_data';
	$route['superadmin/'.$conntrol.'/property_image_list/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/property_image_list';
	$route['superadmin/'.$conntrol.'/property_image_list/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/property_image_list';
	    
        $route['superadmin/'.$conntrol.'/carousels'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/carousels';
        $route['superadmin/'.$conntrol.'/carousels/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/carousels';
        $route['superadmin/'.$conntrol.'/carousels/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/carousels';
        $route['superadmin/'.$conntrol.'/add_carousels/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/add_carousels';
        $route['superadmin/'.$conntrol.'/insert_carousels'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_carousels';
        $route['superadmin/'.$conntrol.'/edit_carousels/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/edit_carousels';
        $route['superadmin/'.$conntrol.'/unpublish_carousels/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/unpublish_carousels';
        $route['superadmin/'.$conntrol.'/publish_carousels/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/publish_carousels';
        $route['superadmin/'.$conntrol.'/carousels_delete_all'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/carousels_delete_all';
        $route['superadmin/'.$conntrol.'/check_carousels_name'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/check_carousels_name';
        $route['superadmin/'.$conntrol.'/nearbyarea/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/nearbyarea';
        $route['superadmin/'.$conntrol.'/insert_nearbyarea'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_nearbyarea';
        $route['superadmin/'.$conntrol.'/update_nearbyarea'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/update_nearbyarea';
        $route['superadmin/'.$conntrol.'/nearby_area_delete'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/nearby_area_delete';
        $route['superadmin/'.$conntrol.'/insert_footer'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/insert_footer';
        $route['superadmin/'.$conntrol.'/check_carousel_valid'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/check_carousel_valid';
        
	/* Retrive image data routes */

	$route['superadmin/'.$conntrol.'/retrieve_image_data_new/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data_new';
	$route['superadmin/'.$conntrol.'/retrieve_image_data_new_remaining/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data_new_remaining';
	$route['superadmin/'.$conntrol.'/retrieve_image_data_new_individual/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/retrieve_image_data_new_individual';
	
	/* END */

	$route['superadmin/'.$conntrol.'/sent_email/(:any)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	
	$route['superadmin/'.$conntrol.'/sent_email/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	
	$route['superadmin/'.$conntrol.'/view_data/(:num)/(:num)'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/view_data';
	
	$route['superadmin/'.$conntrol.'/(:any)'] = "superadmin/".$conntrol."/".$conntrol."_control";
	
	$route['superadmin/'.$conntrol.'/assign_package'] = 'superadmin/'.$conntrol.'/'.$conntrol.'_control/assign_package';
	
	
	
}
elseif($flag==2){
	
	//$route['admin/assign_joomla'] = 'admin/assign_joomla/assign_joomla_control';


	$route['admin/'.$conntrol] = "admin/".$conntrol."/".$conntrol."_control";
	$route['admin/'.$conntrol.'/retrieve_listing_data_cron/(:any)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data_cron';
	$route['admin/'.$conntrol.'/download_form/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/download_form';
	//$route['admin/'.$conntrol.'/resend_mail'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route['admin/'.$conntrol.'/resend_mail/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route['admin/'.$conntrol.'/resend_mail/(:any)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	
	$route['admin/'.$conntrol.'/resend_sms/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/resend_sms';
	$route['admin/'.$conntrol.'/resend_sms/(:any)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/resend_sms';
	
	$route['admin/'.$conntrol.'/add_record'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/add_record';
    $route['admin/'.$conntrol.'/add_record1'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/add_record1';
    $route['admin/'.$conntrol.'/edit_record1/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record1';
	$route['admin/'.$conntrol.'/add_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/add_record';
	
	$route[$user_type.$conntrol.'/add_record/property_master_iframe/(:any)'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_record';
	
	$route['admin/'.$conntrol.'/insert_data'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/insert_data';
	$route['admin/'.$conntrol.'/edit_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['admin/'.$conntrol.'/edit_record/(:num)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['admin/'.$conntrol.'/edit_record1/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record1';
	$route['admin/'.$conntrol.'/edit_record1/(:num)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record1';
	$route['admin/'.$conntrol.'/copy_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/copy_record';
    $route['admin/'.$conntrol.'/copy_record/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/copy_record';
	$route['admin/'.$conntrol.'/edit_record/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['admin/'.$conntrol.'/edit_record/(:num)/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['admin/'.$conntrol.'/edit_record1/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record1';
	$route['admin/'.$conntrol.'/edit_record1/(:num)/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record1';
	$route['admin/'.$conntrol.'/view_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['admin/'.$conntrol.'/view_record/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['admin/'.$conntrol.'/view_record/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['admin/'.$conntrol.'/update_data'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_data';
	$route['admin/'.$conntrol.'/delete_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route['admin/'.$conntrol.'/tipslist/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control';
	$route['admin/'.$conntrol.'/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control';
	$route['admin/'.$conntrol.'/msg/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control';
	$route['admin/'.$conntrol.'/unpublish_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/unpublish_record';
	$route['admin/'.$conntrol.'/publish_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/publish_record';
	$route['admin/'.$conntrol.'/delete_icon'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_icon';
	$route['admin/'.$conntrol.'/upload_image'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/upload_image';
	$route['admin/'.$conntrol.'/delete_image'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_image';
	$route['admin/'.$conntrol.'/send_invoice/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/send_invoice';
	$route['admin/'.$conntrol.'/insert_plan_type'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/insert_plan_type';
	$route['admin/'.$conntrol.'/insert_status'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/insert_status';
	$route['admin/'.$conntrol.'/update_plan_type'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_plan_type';
	$route['admin/'.$conntrol.'/update_status'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_status';
	$route['admin/'.$conntrol.'/update_leave'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_leave';
	$route['admin/'.$conntrol.'/update_rules'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_rules';
	$route['admin/'.$conntrol.'/delete_plan_type_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_plan_type_record';
	$route['admin/'.$conntrol.'/delete_rules_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_rules_record';
    $route['admin/'.$conntrol.'/check_slug'] = "admin/".$conntrol."/".$conntrol."_control/check_slug";
    $route['admin/'.$conntrol.'/property_contact_popup'] = "admin/".$conntrol."/".$conntrol."_control/view_property_contact_popup";
    $route['admin/'.$conntrol.'/delete_status_record/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_status_record';
		
	$route['admin/'.$conntrol.'/ajax_subcategory'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_subcategory';
	$route['admin/'.$conntrol.'/ajax_templatedata'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_templatedata';
	//$route['admin/'.$conntrol.'/ajax_templatename'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_templatename';
	$route['admin/'.$conntrol.'/add_contacts_to_email'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/add_contacts_to_email';
	
	$route['admin/'.$conntrol.'/search_contact_ajax'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_ajax';
	$route['admin/'.$conntrol.'/search_contact_ajax/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_ajax';
	$route['admin/'.$conntrol.'/contacts_to_email'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/contacts_to_email';
	$route['admin/'.$conntrol.'/search_contact_to'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_to';
	$route['admin/'.$conntrol.'/search_contact_to/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_to';
	$route['admin/'.$conntrol.'/search_contact_cc'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_cc';
	$route['admin/'.$conntrol.'/search_contact_cc/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_cc';
	$route['admin/'.$conntrol.'/search_contact_bcc'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_bcc';
	$route['admin/'.$conntrol.'/search_contact_bcc/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/search_contact_bcc';
	$route['admin/'.$conntrol.'/get_platform_contact'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/get_platform_contact';
	$route['admin/'.$conntrol.'/all_sent_mail'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_mail';
	$route['admin/'.$conntrol.'/all_sent_mail/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_mail';
	
	
	$route['admin/'.$conntrol.'/all_sent_sms'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';
	$route['admin/'.$conntrol.'/all_sent_sms/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';
	
	$route['admin/'.$conntrol.'/all_sent_social'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_social';
	
	/*$route['admin/'.$conntrol.'/all_sent_sms'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';
	$route['admin/'.$conntrol.'/all_sent_sms/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';	*/
	
	$route['admin/'.$conntrol.'/ajax_delete_all'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all';
	$route['admin/'.$conntrol.'/ajax_delete_attachment'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_attachment';
	
	$route['admin/'.$conntrol.'/delete_attachment'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_attachment';
	
	$route['admin/'.$conntrol.'/sent_email/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	$route['admin/'.$conntrol.'/sent_sms/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/sent_sms';
	
	$route['admin/'.$conntrol.'/sent_email/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	$route['admin/'.$conntrol.'/sent_sms/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/sent_sms';
	$route['admin/'.$conntrol.'/interaction_mailsms'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/interaction_mailsms';
	$route['admin/'.$conntrol.'/interaction_mailsms/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/interaction_mailsms';	
	$route['admin/'.$conntrol.'/view_data/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_data';
	$route['admin/'.$conntrol.'/view_data/(:num)/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_data';
	$route['admin/'.$conntrol.'/view_data/(:num)/(:num)/(:any)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_data';
	
        $route['admin/lead_capturing/form_lead_list'] = 'admin/lead_capturing/lead_capturing_control/form_lead_list';
        $route['admin/lead_capturing/form_lead_list/(:num)'] = 'admin/lead_capturing/lead_capturing_control/form_lead_list';
	$route['admin/'.$conntrol.'/(:any)'] = "admin/".$conntrol."/".$conntrol."_control";
	

}elseif($flag == 4)
{
	
	$route['ws/'.$conntrol] = "ws/".$conntrol."/".$conntrol."_control";
	$route['ws/'.$conntrol.'/view_form/(:any)'] = 'ws/'.$conntrol.'/'.$conntrol."_control".'/view_form';
	$route['ws/'.$conntrol.'/score_verified/(:any)'] = 'ws/'.$conntrol.'/'.$conntrol."_control".'/score_verified';
	$route['ws/'.$conntrol.'/unverify_score/(:any)'] = 'ws/'.$conntrol.'/'.$conntrol."_control".'/unverify_score';
	$route['ws/'.$conntrol.'/add_record'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/add_record';
	$route['ws/'.$conntrol.'/add_team_tournamet'] = 'ws/'.$conntrol.'/'.$conntrol."_control".'/add_team_tournamet';
	$route['ws/'.$conntrol.'/add_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/add_record';
	$route['ws/'.$conntrol.'/insert_data'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/insert_data';
	$route['ws/'.$conntrol.'/edit_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['ws/'.$conntrol.'/edit_record/(:num)/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['ws/'.$conntrol.'/view_record'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['ws/'.$conntrol.'/view_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['ws/'.$conntrol.'/update_data'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/update_data';
	$route['ws/'.$conntrol.'/delete_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route['ws/'.$conntrol.'/tipslist/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control';
	$route['ws/'.$conntrol.'/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control';
	$route['ws/'.$conntrol.'/msg/(:any)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control';
	$route['ws/'.$conntrol.'/unpublish_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/unpublish_record';
	$route['ws/'.$conntrol.'/publish_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/publish_record';
	$route['ws/'.$conntrol.'/delete_icon'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/delete_icon';
	$route['ws/'.$conntrol.'/upload_image'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/upload_image';
	$route['ws/'.$conntrol.'/delete_image'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/delete_image';
	$route['ws/'.$conntrol.'/send_invoice/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/send_invoice';
	$route['ws/'.$conntrol.'/insert_plan_type'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/insert_plan_type';
	$route['ws/'.$conntrol.'/insert_status'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/insert_status';
	$route['ws/'.$conntrol.'/update_plan_type'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/update_plan_type';
	$route['ws/'.$conntrol.'/update_status'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/update_status';
	$route['ws/'.$conntrol.'/delete_plan_type_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/delete_plan_type_record';
	$route['ws/'.$conntrol.'/delete_status_record/(:num)'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/delete_status_record';
	$route['ws/'.$conntrol.'/getuser'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/getuser';
	/*$route['ws/'.$conntrol.'/connection'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/connection';*/
	$route['ws/'.$conntrol.'/user_registration'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/user_registration';
	$route['ws/'.$conntrol.'/saved_searches'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/saved_searches';
	$route['ws/'.$conntrol.'/properties_viewed'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/properties_viewed';
	$route['ws/'.$conntrol.'/last_login'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/last_login';
	$route['ws/'.$conntrol.'/favorite'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/favorite';
        $route['ws/'.$conntrol.'/change_property_status'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/change_property_status';
        $route['ws/'.$conntrol.'/user_registration1'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/user_registration1';
        $route['ws/'.$conntrol.'/new_property_email'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/new_property_email';
        $route['ws/'.$conntrol.'/get_neighborhood_data'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/get_neighborhood_data';
        $route['ws/'.$conntrol.'/wordpress_order_details'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/wordpress_order_details';
        $route['ws/'.$conntrol.'/valuation_saved_searches'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/valuation_saved_searches';
        $route['ws/'.$conntrol.'/valuation_contact_form'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/valuation_contact_form';
        $route['ws/'.$conntrol.'/update_agent_data'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/update_agent_data';
        

        
	$route['ws/'.$conntrol.'/(:any)'] = "ws/".$conntrol."/".$conntrol."_control";
	$route['ws/'.$conntrol.'/ajax_delete_all'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all';
	$route['ws/'.$conntrol.'/ajax_publish_all'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/ajax_publish_all';
	$route['ws/'.$conntrol.'/ajax_unpublish_all'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/ajax_unpublish_all';
	$route['ws/'.$conntrol.'/team_selected_check'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/team_selected_check';
	$route['ws/'.$conntrol.'/selected_team'] = 'ws/'.$conntrol.'/'.$conntrol.'_control/selected_team';
	$route['ws/'.$conntrol.'/view_form/(:any)'] = 'ws/'.$conntrol.'/'.$conntrol."_control".'/view_form';

}
elseif($flag == 6)
{
    //$route['website/website1.com/login'] = "website/website1.com/login/login";
    $route['website/website1/login'] = "website/website1/login/login";
    $route['website/'.$conntrol] = "website/".$conntrol."/".$conntrol."_control";
    $route['website/'.$conntrol.'/add_record'] = 'website/'.$conntrol.'/'.$conntrol.'_control/add_record';
    $route['website/'.$conntrol.'/add_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/add_record';
    $route['website/'.$conntrol.'/insert_data'] = 'website/'.$conntrol.'/'.$conntrol.'_control/insert_data';
    $route['website/'.$conntrol.'/edit_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/edit_record';
    $route['website/'.$conntrol.'/edit_record/(:num)/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/edit_record';
    $route['website/'.$conntrol.'/view_record'] = 'website/'.$conntrol.'/'.$conntrol.'_control/view_record';
    $route['website/'.$conntrol.'/view_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/view_record';
    $route['website/'.$conntrol.'/update_data'] = 'website/'.$conntrol.'/'.$conntrol.'_control/update_data';
    $route['website/'.$conntrol.'/delete_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/delete_record';
    $route['website/'.$conntrol.'/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control';
    $route['website/'.$conntrol.'/msg/(:any)'] = 'website/'.$conntrol.'/'.$conntrol.'_control';
    $route['website/'.$conntrol.'/unpublish_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/unpublish_record';
    $route['website/'.$conntrol.'/publish_record/(:num)'] = 'website/'.$conntrol.'/'.$conntrol.'_control/publish_record';
    $route['website/'.$conntrol.'/upload_image'] = 'website/'.$conntrol.'/'.$conntrol.'_control/upload_image';
    $route['website/'.$conntrol.'/delete_image'] = 'website/'.$conntrol.'/'.$conntrol.'_control/delete_image';
    $route['website/'.$conntrol.'/getuser'] = 'website/'.$conntrol.'/'.$conntrol.'_control/getuser';
}
elseif($flag == 1){
	$route['user/'.$conntrol] = 'user/'.$conntrol."/".$conntrol."_control"; 
	$route['user/'.$conntrol.'/add_record'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/add_record';
	$route['user/'.$conntrol.'/insert_data'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/insert_data';
	$route['user/'.$conntrol.'/edit_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/edit_record';
	$route['user/'.$conntrol.'/edit_record/(:any)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/edit_record';
	$route['user/'.$conntrol.'/edit_record'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/edit_record';
	$route['user/'.$conntrol.'/resend_mail/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	$route['user/'.$conntrol.'/resend_mail/(:any)/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/resend_mail';
	
	$route['user/'.$conntrol.'/resend_sms/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/resend_sms';
	$route['user/'.$conntrol.'/resend_sms/(:any)/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/resend_sms';
	$route['user/'.$conntrol.'/update_data'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/update_data';
	$route['user/'.$conntrol.'/delete_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/delete_record';
	$route['user/'.$conntrol.'/(:num)'] = 'user/'.$conntrol.'/'.$conntrol."_control";
 	$route['user/'.$conntrol.'/msg/(:any)'] = 'user/'.$conntrol.'/'.$conntrol."_control"; 
	$route['user/'.$conntrol.'/copy_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/copy_record';
	$route['user/'.$conntrol.'/copy_record/(:num)/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/copy_record';
	$route['user/'.$conntrol.'/unpublish_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/unpublish_record';
	$route['user/'.$conntrol.'/publish_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/publish_record';
	$route['user/'.$conntrol.'/getLatLong'] = 'user/'.$conntrol.'/'.$conntrol.'_control/getLatLong';
	$route['user/'.$conntrol.'/upgrade_account/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/upgrade_account';
	$route['user/'.$conntrol.'/update_account'] = 'user/'.$conntrol.'/'.$conntrol.'_control/update_account';
	$route['user/'.$conntrol.'/upgradethankyou'] = 'user/'.$conntrol.'/'.$conntrol.'_control/upgradethankyou';
	$route['user/'.$conntrol.'/view_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/view_record';
	$route['user/'.$conntrol.'/view_record/(:num)/(:num)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/view_record';
	//$route['user/'.$conntrol.'/view_record/(:any)'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/view_record';
	$route['user/'.$conntrol.'/upload_image'] = 'user/'.$conntrol.'/'.$conntrol.'_control/upload_image';
	$route['user/'.$conntrol.'/delete_image'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/delete_image';
	$route['user/'.$conntrol.'/checkuser'] = 'user/'.$conntrol.'/'.$conntrol."_control".'/checkuser';
	$route['user/'.$conntrol.'/delete_status_record/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/delete_status_record';
		
	$route['user/'.$conntrol.'/ajax_subcategory'] = 'user/'.$conntrol.'/'.$conntrol.'_control/ajax_subcategory';
	$route['user/'.$conntrol.'/ajax_templatedata'] = 'user/'.$conntrol.'/'.$conntrol.'_control/ajax_templatedata';
	
	$route['user/'.$conntrol.'/ajax_delete_all'] = 'user/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all';
	$route['user/'.$conntrol.'/ajax_delete_attachment'] = 'user/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_attachment';
	
	//contact
	$route['user/'.$conntrol.'/add_contacts_to_email'] = 'user/'.$conntrol.'/'.$conntrol.'_control/add_contacts_to_email';
	
	$route['user/'.$conntrol.'/search_contact_ajax'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_ajax';
	$route['user/'.$conntrol.'/search_contact_ajax/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_ajax';
	$route['user/'.$conntrol.'/contacts_to_email'] = 'user/'.$conntrol.'/'.$conntrol.'_control/contacts_to_email';
	$route['user/'.$conntrol.'/search_contact_to'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_to';
	$route['user/'.$conntrol.'/search_contact_to/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_to';
	$route['user/'.$conntrol.'/search_contact_cc'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_cc';
	$route['user/'.$conntrol.'/search_contact_cc/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_cc';
	$route['user/'.$conntrol.'/search_contact_bcc'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_bcc';
	$route['user/'.$conntrol.'/search_contact_bcc/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/search_contact_bcc';
	$route['user/'.$conntrol.'/get_platform_contact'] = 'user/'.$conntrol.'/'.$conntrol.'_control/get_platform_contact';
	$route['user/'.$conntrol.'/all_sent_mail'] = 'user/'.$conntrol.'/'.$conntrol.'_control/all_sent_mail';
	$route['user/'.$conntrol.'/all_sent_mail/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/all_sent_mail';
	
	/*$route['user/'.$conntrol.'/queued_list'] = 'user/'.$conntrol.'/'.$conntrol.'_control/queued_list';
	$route['user/'.$conntrol.'/queued_list/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/queued_list';*/
	
	$route['user/'.$conntrol.'/all_sent_sms'] = 'user/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';
	$route['user/'.$conntrol.'/all_sent_sms/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/all_sent_sms';
	$route['user/'.$conntrol.'/sent_email/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	$route['user/'.$conntrol.'/sent_sms/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/sent_sms';
	
	$route['user/'.$conntrol.'/sent_email/(:num)/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/sent_email';
	$route['user/'.$conntrol.'/sent_sms/(:num)/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/sent_sms';
	$route['user/'.$conntrol.'/interaction_mailsms'] = 'user/'.$conntrol.'/'.$conntrol.'_control/interaction_mailsms';
	$route['user/'.$conntrol.'/interaction_mailsms/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/interaction_mailsms';
	$route['user/'.$conntrol.'/view_data/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/view_data';
	$route['user/'.$conntrol.'/view_data/(:num)/(:num)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/view_data';
	$route['user/'.$conntrol.'/view_data/(:num)/(:num)/(:any)'] = 'user/'.$conntrol.'/'.$conntrol.'_control/view_data';
        $route['user/lead_capturing/form_lead_list'] = 'user/lead_capturing/lead_capturing_control/form_lead_list';
        $route['user/lead_capturing/form_lead_list/(:num)'] = 'user/lead_capturing/lead_capturing_control/form_lead_list';
	$route['user/'.$conntrol.'/(:any)'] = "user/".$conntrol."/".$conntrol."_control";
}
else{}
// End

//For Admin Redirection 
$route['index'] = "index/index";
$route['lead_capturing_form/(:any)'] = "lead_capturing_form/lead_capturing_form_control";
$route['lead_capturing_form/view_record'] = "lead_capturing_form/lead_capturing_form_control/view_record";
$route['lead_capturing_form/insert_data'] = "lead_capturing_form/lead_capturing_form_control/insert_data";
$route['index/msg/(:any)'] ="index/index";
$route['property_listing/flyer1'] ='property_listing/property_listing_control/flyer1';
$route['property_listing/flyer1/(:any)'] ='property_listing/property_listing_control/flyer1';

$route['property_listing/flyer2'] ='property_listing/property_listing_control/flyer2';
$route['property_listing/flyer2/(:any)'] ='property_listing/property_listing_control/flyer2';

$route['property_listing/flyer3'] ='property_listing/property_listing_control/flyer3';
$route['property_listing/flyer3/(:any)'] ='property_listing/property_listing_control/flyer3';

$route['property_listing/(:any)'] = "property_listing/property_listing_control";
$route['property_listing/send_mail'] = "property_listing/property_listing_control/send_mail";
$route['contact_sms_response'] = "contact_sms_response/contact_sms_response_control";
$route['contact_sms_response1'] = "contact_sms_response1/contact_sms_response1_control";
//$route['admin'] = "admin/login/login";
//$route['admin/login'] = "admin/login/login";
$route['admin/logout'] = "admin/login/logout";
$route['admin/dashboard'] = "admin/index/dashboard";
$route['admin/dashboard/popup_changes'] = "admin/index/dashboard/popup_changes";
$route['admin/dashboard/view_record/(:num)'] = "admin/index/dashboard/view_record";
   
$route['pagenotfound'] = "pagenotfound/pagenotfound_control";


//for superadmin

$route['superadmin/logout'] = "superadmin/login/logout";
$route['superadmin/dashboard'] = "superadmin/index/dashboard";

//For User Redirection 
//$route['user'] = "user/login/login";
$route['login'] = "user/login/login";
//$route['user/login'] = "user/login/login";
$route['logout'] = "user/login/logout";
$route['user/logout'] = "user/login/logout";
$route['user/dashboard'] = "user/index/dashboard";
$route['user/dashboard/popup_changes'] = "user/index/dashboard/popup_changes";

$route['user/user_profile/check_user'] = 'user/user_profile/user_profile_control/check_user';

// Change Password of admin

$route['admin/change_password_view'] = "admin/change_password/change_password_control";
$route['admin/change_password'] = "admin/change_password/change_password_control/change_password";

//For Admin Configuration Master
$route['admin/configuration'] = "admin/configuration/configuration/index";
$route['admin/add_configuration']  ="admin/configuration/configuration/add_configuration";
$route['admin/configuration/insert_configuration']  ="admin/configuration/configuration/insert_configuration";
$route['admin/edit_configuration/id/(:num)']  ="admin/configuration/configuration/edit_configuration";
$route['admin/update_configuration']  ="admin/configuration/configuration/update_configuration";
$route['admin/delete_configuration/id/(:num)']  ="admin/configuration/configuration/delete_configuration";
$route['admin/configuration/index/(:num)'] = "admin/configuration/configuration/index";
$route['admin/configuration/index'] = "admin/configuration/configuration/index";
$route['admin/configuration/(:num)'] = "admin/configuration/configuration/index";
$route['admin/configuration/msg/(:any)'] = "admin/configuration/configuration/index";
$route['admin/configuration/msg'] = "admin/configuration/configuration/index";

//For Admin Configuration Value
$route['admin/configvalue'] = "admin/configuration/configuration/configvaluelist";
$route['admin/configvalue/add_configvalue']  ="admin/configuration/configuration/add_configvalue";
$route['admin/configvalue/insert_configvalue']  ="admin/configuration/configuration/insert_configvalue";
$route['admin/configvalue/edit_configvalue/(:num)']  ="admin/configuration/configuration/edit_configvalue";
$route['admin/configvalue/update_configvalue']  ="admin/configuration/configuration/update_configvalue";
$route['admin/configvalue/delete_configvalue/(:num)']  ="admin/configuration/configuration/delete_configvalue";
$route['admin/configvalue/index/(:num)'] = "admin/configuration/configuration/configvaluelist";
$route['admin/configvalue/index'] = "admin/configuration/configuration/configvaluelist";
$route['admin/configvalue/(:num)'] = "admin/configuration/configuration/configvaluelist";

$route['admin/assign_joomla'] = "admin/assign_joomla/";

$route['admin/configvalue/msg/(:any)'] = "admin/configuration/configuration/configvaluelist";
	
	/*INSERT ROUTE PATH*/
	$route[$user_type.'contact_masters/insert_email'] = $user_type.'contact_masters/contact_masters_control/insert_email';
	$route[$user_type.'department_masters/insert_department'] = $user_type.'department_masters/department_masters_control/insert_department';
	$route[$user_type.'contact_masters/insert_phone'] = $user_type.'contact_masters/contact_masters_control/insert_phone';
	$route[$user_type.'contact_masters/insert_address'] = $user_type.'contact_masters/contact_masters_control/insert_address';
	$route[$user_type.'contact_masters/insert_website'] = $user_type.'contact_masters/contact_masters_control/insert_website';
	$route[$user_type.'contact_masters/insert_status'] = $user_type.'contact_masters/contact_masters_control/insert_status';
	$route[$user_type.'contact_masters/insert_profile'] = $user_type.'contact_masters/contact_masters_control/insert_profile';
	$route[$user_type.'contact_masters/insert_contact'] = $user_type.'contact_masters/contact_masters_control/insert_contact';
	$route[$user_type.'contact_masters/insert_document'] = $user_type.'contact_masters/contact_masters_control/insert_document';
	$route[$user_type.'contact_masters/insert_method'] = $user_type.'contact_masters/contact_masters_control/insert_method';
	$route[$user_type.'contact_masters/insert_field'] = $user_type.'contact_masters/contact_masters_control/insert_field';
	$route[$user_type.'contact_masters/insert_source'] = $user_type.'contact_masters/contact_masters_control/insert_source';
	$route[$user_type.'contact_masters/insert_disposition'] = $user_type.'contact_masters/contact_masters_control/insert_disposition';
	$route[$user_type.'general_configuration/insert_user'] = $user_type.'general_configuration/general_configuration_control/insert_user';
	
	/*UPDATE ROUTE PATH*/
	$route[$user_type.'contact_masters/update_email'] = $user_type.'contact_masters/contact_masters_control/update_email';
	$route[$user_type.'department_masters/update_email'] = $user_type.'department_masters/department_masters_control/update_department';

	$route[$user_type.'contact_masters/update_phone'] = $user_type.'contact_masters/contact_masters_control/update_phone';
	$route[$user_type.'contact_masters/update_address'] = $user_type.'contact_masters/contact_masters_control/update_address';
	$route[$user_type.'contact_masters/update_website'] = $user_type.'contact_masters/contact_masters_control/update_website';
	$route[$user_type.'contact_masters/update_status'] = $user_type.'contact_masters/contact_masters_control/update_status';
	$route[$user_type.'contact_masters/update_profile'] = $user_type.'contact_masters/contact_masters_control/update_profile';
	$route[$user_type.'contact_masters/update_contact'] = $user_type.'contact_masters/contact_masters_control/update_contact';
	$route[$user_type.'contact_masters/update_document'] = $user_type.'contact_masters/contact_masters_control/update_document';
	$route[$user_type.'contact_masters/update_method'] = $user_type.'contact_masters/contact_masters_control/update_method';
	$route[$user_type.'contact_masters/update_field'] = $user_type.'contact_masters/contact_masters_control/update_field';

	$route[$user_type.'contact_masters/update_source'] = $user_type.'contact_masters/contact_masters_control/update_source';
	$route[$user_type.'contact_masters/update_disposition'] = $user_type.'contact_masters/contact_masters_control/update_disposition';
	$route[$user_type.'general_configuration/update_user'] = $user_type.'general_configuration/general_configuration_control/update_user';
	$route[$user_type.'work_time_config_master/update_leave'] = $user_type.'work_time_config_master/work_time_config_master_control/update_leave';
	$route[$user_type.'work_time_config_master/update_rules'] = $user_type.'work_time_config_master/work_time_config_master_control/update_rules';
	
	/* For Admin Contact Export */
	$route[$user_type.'contacts/export_contact'] = $user_type.'contacts/contacts_control/export_contact';
	$route[$user_type.'contacts/export'] = $user_type.'contacts/contacts_control/export';

	$route[$user_type.$conntrol.'/import'] = $user_type.$conntrol.'/'.$conntrol.'_control/import';
	$route[$user_type.'contacts/upload_csv'] = $user_type.'contacts/contacts_control/upload_csv';
	$route[$user_type.$conntrol.'/insert_contact_csv'] = $user_type.$conntrol.'/'.$conntrol.'_control/insert_contact_csv';
	$route[$user_type.'contacts/insert_contact'] = $user_type.'contacts/contacts_control/insert_contact';
	$route[$user_type.'contacts/get_filed_list'] = $user_type.'contacts/contacts_control/get_filed_list';
	//$route[$user_type.'contacts/ajax_delete_all'] = $user_type.'contacts/contacts_control/ajax_delete_all';
	$route[$user_type.'contacts/assign_contact'] = $user_type.'contacts/contacts_control/assign_contact';
	$route[$user_type.'contacts/interaction_id_done'] = $user_type.'contacts/contacts_control/interaction_id_done';
	$route[$user_type.'contacts/insert_last_action_communication_plan'] = $user_type.'contacts/contacts_control/insert_last_action_communication_plan';
	$route[$user_type.'contacts/insert_personal_touches'] = $user_type.'contacts/contacts_control/insert_personal_touches';
	
	$route[$user_type.'contacts/insert_contact_notes'] = $user_type.'contacts/contacts_control/insert_contact_notes';
	$route[$user_type.'contacts/change_conversations'] = $user_type.'contacts/contacts_control/change_conversations';
	
	$route[$user_type.'contacts/personal_id_done'] = $user_type.'contacts/contacts_control/personal_id_done';

	$route[$user_type.'contact_masters/delete_status_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_status_record';
	
	$route[$user_type.'contact_masters/delete_profile_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_profile_record';
	$route[$user_type.'contact_masters/delete_contact_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_contact_record';
	$route[$user_type.'contact_masters/delete_document_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_document_record';
	$route[$user_type.'contact_masters/delete_source_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_source_record';
	$route[$user_type.'contact_masters/delete_disposition_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_disposition_record';
	$route[$user_type.'contact_masters/delete_method_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_method_record';
	$route[$user_type.'contact_masters/delete_field_record/(:num)'] = $user_type.'contact_masters/contact_masters_control/delete_field_record';
	$route[$user_type.'contacts/delete_email_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_email_trans_record';
	$route[$user_type.'contacts/delete_phone_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_phone_trans_record';
	$route[$user_type.'contacts/delete_address_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_address_trans_record';
	$route[$user_type.'contacts/delete_website_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_website_trans_record';
	$route[$user_type.'contacts/delete_social_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_social_trans_record';
	$route[$user_type.'contacts/delete_tag_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_tag_trans_record';
	$route[$user_type.'contacts/delete_communication_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_communication_trans_record';
	$route[$user_type.'contacts/delete_document_trans_record/(:num)'] = $user_type.'contacts/contacts_control/delete_document_trans_record';
	$route[$user_type.'contacts/upload_document'] = $user_type.'contacts/contacts_control/upload_document';
	$route[$user_type.'contacts/update_data_ajax'] = $user_type.'contacts/contacts_control/update_data_ajax';
	$route[$user_type.'contacts/get_doc_trans_data'] = $user_type.'contacts/contacts_control/get_doc_trans_data';
	
	$route[$user_type.'listing_manager/get_doc_trans_data'] = $user_type.'listing_manager/listing_manager_control/get_doc_trans_data';

	$route[$user_type.'listing_manager/getLatLong'] = $user_type.'listing_manager/listing_manager_control/getLatLong';
	
	$route[$user_type.'listing_manager/get_offers_trans_data'] = $user_type.'listing_manager/listing_manager_control/get_offers_trans_data';

	$route[$user_type.'listing_manager/get_price_trans_data'] = $user_type.'listing_manager/listing_manager_control/get_price_trans_data';

	$route[$user_type.'listing_manager/get_houses_trans_data'] = $user_type.'listing_manager/listing_manager_control/get_houses_trans_data';

	$route[$user_type.'listing_manager/get_showings_trans_data'] = $user_type.'listing_manager/listing_manager_control/get_showings_trans_data';


	$route[$user_type.'contacts/merge_duplicate_contacts'] = $user_type.'contacts/contacts_control/merge_duplicate_contacts';
	
	$route[$user_type.'contacts/merge_search_contacts'] = $user_type.'contacts/contacts_control/merge_search_contacts';
	$route[$user_type.'contacts/get_merge_contact_data_ajax'] = $user_type.'contacts/contacts_control/get_merge_contact_data_ajax';
	$route[$user_type.'contacts/insert_merge_data'] = $user_type.'contacts/contacts_control/insert_merge_data';
	
	$route[$user_type.'contacts/get_conversation_data'] = $user_type.'contacts/contacts_control/get_conversation_data';
	$route[$user_type.'contacts/insert_conversations'] = $user_type.'contacts/contacts_control/insert_conversations';
	$route[$user_type.'contacts/update_conversations/(:num)'] = $user_type.'contacts/contacts_control/update_conversations';
	$route[$user_type.'contacts/ajax_delete_conversations'] = $user_type.'contacts/contacts_control/ajax_delete_conversations';
	$route[$user_type.'contacts/personal_id_done'] = $user_type.'contacts/contacts_control/personal_id_done';
	$route[$user_type.'contacts/interaction_id_done'] = $user_type.'contacts/contacts_control/interaction_id_done';
	
	$route[$user_type.'contacts/insert_conversations'] = $user_type.'contacts/contacts_control/insert_conversations';
	$route[$user_type.'contacts/ajax_delete_conversations'] = $user_type.'contacts/contacts_control/ajax_delete_conversations';
	$route[$user_type.'contacts/get_conversation_data'] = $user_type.'contacts/contacts_control/get_conversation_data';
	
	//$route[$user_type.'interaction_plans/ajax_delete_all'] = $user_type.'interaction_plans/interaction_plans_control/ajax_delete_all';
	//$route[$user_type.'interaction/ajax_delete_all'] = $user_type.'interaction/interaction_control/ajax_delete_all';
	/*$route[$user_type.'interaction_plans/add_contacts_to_interaction_plan'] = $user_type.'interaction_plans/interaction_plans_control/add_contacts_to_interaction_plan';*/
	$route[$user_type.$conntrol.'/view_contacts_of_interaction_plan'] = $user_type.$conntrol.'/'.$conntrol.'_control/view_contacts_of_interaction_plan';
	$route[$user_type.'interaction/view_contacts_of_interaction_by_step'] = $user_type.'interaction/interaction_control/view_contacts_of_interaction_by_step';
	$route[$user_type.'lead_capturing/view_embed_data'] = $user_type.'lead_capturing/lead_capturing_control/view_embed_data';
	$route[$user_type.'lead_capturing/view_form_data'] = $user_type.'lead_capturing/lead_capturing_control/view_form_data';	
	/* for email signature */
	//$route[$user_type.'email_signature/ajax_delete_all'] = $user_type.'email_signature/email_signature_control/ajax_delete_all';
	$route[$user_type.'email_signature/changedefaulttemplate'] = $user_type.'email_signature/email_signature_control/changedefaulttemplate';
	
	//calendar
$route[$user_type.'calendar/view_record'] = $user_type."calendar/calendar_control/view_record";
$route[$user_type.'calendar/edit_calender'] = $user_type."calendar/calendar_control/edit_calender";
$route[$user_type.'calendar/update_calender'] = $user_type."calendar/calendar_control/update_calender";
$route[$user_type.'calendar/get_calender_detail'] = $user_type."calendar/calendar_control/get_calender_detail";
$route[$user_type.'calendar/delete_calender'] = $user_type."calendar/calendar_control/delete_calender";
$route[$user_type.'calendar/update_appointment'] = $user_type."calendar/calendar_control/update_appointment";
$route[$user_type.'calendar/insert_calender'] = $user_type."calendar/calendar_control/insert_calender";
$route[$user_type.'calendar/google_login'] = $user_type."calendar/calendar_control/google_login";
$route[$user_type.'calendar/google_connection'] = $user_type."calendar/calendar_control/google_connection";

/* for email signature */
	
	$route[$user_type.'task/iscompleted'] = $user_type.'task/task_control/iscompleted';
	$route[$user_type.'task/iscompletedtask'] = $user_type.'task/task_control/iscompletedtask';
	$route[$user_type.$conntrol.'/is_completed_task'] = $user_type.$conntrol.'/'.$conntrol.'_control/is_completed_task';
	$route[$user_type.$conntrol.'/add_contacts_to_interaction_plan'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_contacts_to_interaction_plan';
        
        $route[$user_type.$conntrol.'/add_contacts_to_valuation'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_contacts_to_valuation';

	$route[$user_type.$conntrol.'/add_contacts_to_listing_manager'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_contacts_to_listing_manager';
	$route[$user_type.$conntrol.'/active_theme'] = $user_type.$conntrol.'/'.$conntrol.'_control/active_theme';
	$route[$user_type.$conntrol.'/property_listing_master'] = $user_type.$conntrol.'/'.$conntrol.'_control/property_listing_master';
	
	$route[$user_type.$conntrol.'/completed_task'] = $user_type.$conntrol.'/'.$conntrol.'_control/completed_task';
	$route[$user_type.$conntrol.'/completed_task/(:num)'] = $user_type.$conntrol.'/'.$conntrol.'_control/completed_task';
	
	
/* for cron */
$route[$user_type.$conntrol.'/retrieve_listing_data_cron'] = $user_type.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data_cron';
		$route[$user_type.$conntrol.'/retrieve_listing_data_cron/(:any)/(:any)'] = $user_type.$conntrol.'/'.$conntrol.'_control/retrieve_listing_data_cron';
	$route[$user_type.$conntrol.'/cron_set'] = $user_type.$conntrol.'/'.$conntrol.'_control/cron_set';
	$route[$user_type.$conntrol.'/cron_set_time'] = $user_type.$conntrol.'/'.$conntrol.'_control/cron_set_time';	
        $route[$user_type.$conntrol.'/get_neighborhood_data_weekly'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_neighborhood_data_weekly';
        $route[$user_type.$conntrol.'/get_valuation_data'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_valuation_data';
        $route[$user_type.$conntrol.'/get_valuation_cron_weekly'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_valuation_cron_weekly';
        $route[$user_type.$conntrol.'/get_valuation_cron_monthly'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_valuation_cron_monthly';
        $route[$user_type.$conntrol.'/get_valuation_cron_weekly_new'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_valuation_cron_weekly_new';
        $route[$user_type.$conntrol.'/get_valuation_cron_monthly_new'] = $user_type.$conntrol.'/'.$conntrol.'_control/get_valuation_cron_monthly_new';
        $route[$user_type.$conntrol.'/valuation_searched_cron_weekly'] = $user_type.$conntrol.'/'.$conntrol.'_control/valuation_searched_cron_weekly';
        $route[$user_type.$conntrol.'/valuation_searched_cron_monthly'] = $user_type.$conntrol.'/'.$conntrol.'_control/valuation_searched_cron_monthly';
		$route[$user_type.$conntrol.'/update_publish'] = $user_type.$conntrol.'/'.$conntrol.'_control/update_publish';
		$route[$user_type.$conntrol.'/add_new_template'] = $user_type.$conntrol.'/'.$conntrol.'_control/add_new_template';
		$route[$user_type.$conntrol.'/update_tempate'] = $user_type.$conntrol.'/'.$conntrol.'_control/update_tempate';
		$route[$user_type.$conntrol.'/assign_all_right'] = $user_type.$conntrol.'/'.$conntrol.'_control/assign_all_right';
		$route[$user_type.$conntrol.'/assign_all_right_user'] = $user_type.$conntrol.'/'.$conntrol.'_control/assign_all_right_user';
		$route[$user_type.$conntrol.'/assign_bomb_right'] = $user_type.$conntrol.'/'.$conntrol.'_control/assign_bomb_right';
        $route[$user_type.$conntrol.'/round_robin_cron'] = $user_type.$conntrol.'/'.$conntrol.'_control/round_robin_cron';
        $route[$user_type.$conntrol.'/property_status_change_cron'] = $user_type.$conntrol.'/'.$conntrol.'_control/property_status_change_cron';
        $route[$user_type.$conntrol.'/property_status_change_cron1'] = $user_type.$conntrol.'/'.$conntrol.'_control/property_status_change_cron1';
        $route[$user_type.$conntrol.'/new_property_cron'] = $user_type.$conntrol.'/'.$conntrol.'_control/new_property_cron';
		
		
		
		
		
	//assign joomla routes
	
	
	
// for phone call script
/*$route[$user_type.'phonecall_script/ajax_delete_all'] = $user_type.'phonecall_script/phonecall_script_control/ajax_delete_all';

$route[$user_type.'letter_library/ajax_delete_all'] = $user_type.'letter_library/letter_library_control/ajax_delete_all';

// for phone call script
$route[$user_type.'sms_texts/ajax_delete_all'] = $user_type.'sms_texts/sms_texts_control/ajax_delete_all';
// for phone call script
$route[$user_type.'email_library/ajax_delete_all'] = $user_type.'email_library/email_library_control/ajax_delete_all';

// for Envelope Library
$route[$user_type.'envelope_library/ajax_delete_all'] = $user_type.'envelope_library/envelope_library_control/ajax_delete_all';

// for Label Library
$route[$user_type.'label_library/ajax_delete_all'] = $user_type.'label_library/label_library_control/ajax_delete_all';*/

$route['unsubscribe/unsubscribe_link'] = 'unsubscribe/unsubscribe_link/unsubscribe_link_control';
$route['unsubscribe/unsubscribe_link/(:any)'] = 'unsubscribe/unsubscribe_link/unsubscribe_link_control';
$route['unsubscribe/unsubscribe_link/unsubscribe'] = 'unsubscribe/unsubscribe_link/unsubscribe_link_control/unsubscribe';

//-------------------------------------------------------------------------------------------------------------------------//
////////////////////// Reset Password//////////////////////

$route['reset_password/reset_password_link'] = 'reset_password/reset_password_link/reset_password_link_control';
//$route['reset_password/reset_password_link/(:any)'] = 'reset_password/reset_password_link/reset_password_link_control';
$route['reset_password/reset_password_link/reset_password_template/(:any)'] = 'reset_password/reset_password_link/reset_password_link_control/reset_password_template/';
$route['reset_password/reset_password_link/reset_password/'] = 'reset_password/reset_password_link/reset_password_link_control/reset_password/';


///////////////////// End Reset password/////////////////

// for super admin



	//calendar
$route['superadmin/calendar/view_record'] = "superadmin/calendar/calendar_control/view_record";
$route['superadmin/calendar/edit_calender'] = "superadmin/calendar/calendar_control/edit_calender";
$route['superadmin/calendar/update_calender'] = "superadmin/calendar/calendar_control/update_calender";
$route['superadmin/calendar/get_calender_detail'] = "superadmin/calendar/calendar_control/get_calender_detail";
$route['superadmin/calendar/delete_calender'] = "superadmin/calendar/calendar_control/delete_calender";
$route['superadmin/calendar/update_appointment'] = "superadmin/calendar/calendar_control/update_appointment";
$route['superadmin/calendar/insert_calender'] = "superadmin/calendar/calendar_control/insert_calender";
$route['superadmin/calendar/google_login'] = "superadmin/calendar/calendar_control/google_login";
$route['superadmin/calendar/google_connection'] = "superadmin/calendar/calendar_control/google_connection";


	/* for email signature */
	//$route[$user_type.'email_signature/ajax_delete_all'] = $user_type.'email_signature/email_signature_control/ajax_delete_all';
	$route['superadmin/email_signature/changedefaulttemplate'] = 'superadmin/email_signature/email_signature_control/changedefaulttemplate';


/* For Intraction plan*/

	$route['superadmin/interaction_plans/add_contacts_to_interaction_plan'] = 'superadmin/interaction_plans/interaction_plans_control/add_contacts_to_interaction_plan';
	$route['superadmin/interaction_plans/view_contacts_of_interaction_plan'] = 'superadmin/interaction_plans/interaction_plans_control/view_contacts_of_interaction_plan';

// Change Password of admin

$route['admin/change_password_view'] = "admin/change_password/change_password_control";
$route['admin/change_password'] = "admin/change_password/change_password_control/change_password";

//For Admin Configuration Master
$route['superadmin/configuration'] = "superadmin/configuration/configuration/index";
$route['superadmin/add_configuration']  ="superadmin/configuration/configuration/add_configuration";
$route['superadmin/configuration/insert_configuration']  ="superadmin/configuration/configuration/insert_configuration";
$route['superadmin/edit_configuration/id/(:num)']  ="superadmin/configuration/configuration/edit_configuration";
$route['superadmin/update_configuration']  ="superadmin/configuration/configuration/update_configuration";
$route['superadmin/delete_configuration/id/(:num)']  ="superadmin/configuration/configuration/delete_configuration";
$route['superadmin/configuration/index/(:num)'] = "superadmin/configuration/configuration/index";
$route['superadmin/configuration/index'] = "superadmin/configuration/configuration/index";
$route['superadmin/configuration/(:num)'] = "superadmin/configuration/configuration/index";
$route['superadmin/configuration/msg/(:any)'] = "superadmin/configuration/configuration/index";
$route['superadmin/configuration/msg'] = "superadmin/configuration/configuration/index";

$route['superadmin/home_report/seattle_report'] = "superadmin/home_report/home_report_control/seattle_report";
$route['superadmin/home_report/sold_location'] = "superadmin/home_report/home_report_control/sold_location";

//For superadmin Configuration Value
$route['superadmin/configvalue'] = "superadmin/configuration/configuration/configvaluelist";
$route['superadmin/configvalue/add_configvalue']  ="superadmin/configuration/configuration/add_configvalue";
$route['superadmin/configvalue/insert_configvalue']  ="superadmin/configuration/configuration/insert_configvalue";
$route['superadmin/configvalue/edit_configvalue/(:num)']  ="superadmin/configuration/configuration/edit_configvalue";
$route['superadmin/configvalue/update_configvalue']  ="superadmin/configuration/configuration/update_configvalue";
$route['superadmin/configvalue/delete_configvalue/(:num)']  ="superadmin/configuration/configuration/delete_configvalue";
$route['superadmin/configvalue/index/(:num)'] = "superadmin/configuration/configuration/configvaluelist";
$route['superadmin/configvalue/index'] = "superadmin/configuration/configuration/configvaluelist";
$route['superadmin/configvalue/(:num)'] = "superadmin/configuration/configuration/configvaluelist";
$route['superadmin/configvalue/msg/(:any)'] = "superadmin/configuration/configuration/configvaluelist";

// Change Password of admin

$route['superadmin/change_password_view'] = "superadmin/change_password/change_password_control";
$route['superadmin/change_password'] = "superadmin/change_password/change_password_control/change_password";
$route[$user_type.$conntrol.'/edit_profile'] = $user_type.$conntrol.'/'.$conntrol.'_control/edit_profile';
$route['admin/livewire_configuration/change_password']="admin/livewire_configuration/livewire_configuration_control/change_password";
$route['admin/livewire_configuration/check_password']="admin/livewire_configuration/livewire_configuration_control/check_password";

$route['admin/truncate_data/folder_truncate']="admin/truncate_data/truncate_data_control/folder_truncate";
$route['admin/truncate_data/database_truncate']="admin/truncate_data/truncate_data_control/database_truncate";
$route['admin/truncate_data/system_truncate']="admin/truncate_data/truncate_data_control/system_truncate";

//User rights
//$route['admin/'.$conntrol.'/module_master'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/module_master';

$route['admin/cron/remove_session_user']="admin/cron/cron_control/remove_session_user";
$route['admin/cron/remove_session_admin']="admin/cron/cron_control/remove_session_admin";
$route['admin/cron/backup_db']="admin/cron/cron_control/backup_db";
$route['admin/cron/update_sms_url']="admin/cron/cron_control/update_sms_url";
$route['admin/cron/delete_assistant_rights']="admin/cron/cron_control/delete_assistant_rights";
$route['superadmin/mls_cron/delete_cron']="superadmin/mls_cron/mls_cron_control/delete_cron";
$route['admin/cron/last_login']="admin/cron/cron_control/last_login";
$route['admin/cron/cron_log']="admin/cron/cron_control/cron_log";
$route['admin/cron/assign_sms_auto_responder_right']="admin/cron/cron_control/assign_sms_auto_responder_right";
$route['admin/cron/dbbackup']="admin/cron/cron_control/dbbackup";