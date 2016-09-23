<?php 

/*
    @Description: logout controller
    @Author: Mohit Trivedi
    @Date: 30-08-14
	
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Logout extends CI_Controller
	{
            public function index()
            {
                $superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
                
                if($superadmin_session['active']==TRUE)
                {
                    $data['id'] = $superadmin_session['last_login_id'];
                    
                    $this->session->unset_userdata($this->lang->line('common_superadmin_session_label'));
                    $this->session->unset_userdata('name');
                    $this->session->unset_userdata('id');
                    $this->session->unset_userdata('useremail');
                    $this->session->unset_userdata('active');
                    $this->session->unset_userdata($this->lang->line('common_superadmin_session_label'));
                    //$this->load->helper('cookie');
                    //$cookie=  $this->config->item('sess_cookie_name');
                    //delete_cookie($cookie);
                    
                    date_default_timezone_set('America/New_York');
                    $data['end_date'] = date('Y-m-d H:i:s');
                    date_default_timezone_set('Asia/Kolkata');
                    $data['end_time_ist'] = date('Y-m-d H:i:s');
                    date_default_timezone_set('America/Anchorage');
                    $data['end_time_pst'] = date('Y-m-d H:i:s');
                    
                    $this->admin_model->update_user_login_trans($data,$this->config->item('parent_db_name'));
                    
                    redirect('login');
                }
                else
                    redirect('login');
            }
	}
