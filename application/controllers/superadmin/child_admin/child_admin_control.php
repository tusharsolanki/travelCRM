<?php

/*
  @Description: Superadmin Map Joomla controller
  @Author: Ami Bhatti
  @Input:
  @Output:
  @Date: 08-10-14

 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class child_admin_control extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
        $this->load->model('child_admin_model');
        $this->load->model('admin_model');
        $this->load->model('lead_users_model');
        $this->load->model('common_function_model');
        $this->load->model('carousels_master_model');
        $this->load->model('child_nearby_area_model');
        $this->load->model('cms_model');
        $this->load->model('child_footer_links_model');
        $this->load->model('agent_team_model');
        $this->load->model('imageupload_model');

        $this->obj = $this->child_admin_model;
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';
    }

    /*
      @Description: Function for Get All Superadmin List
      @Author: Mohit Trivedi
      @Input: - Search value or null
      @Output: - all Superadmin list
      @Date: 30-08-2014
     */

    public function index() {
        $searchopt = '';
        $searchtext = '';
        $date1 = '';
        $date2 = '';
        $searchoption = '';
        $perpage = '';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $searchopt = $this->input->post('searchopt');
        $perpage = trim($this->input->post('perpage'));
        $allflag = $this->input->post('allflag');
        $data['sortfield'] = 'id';
        $data['sortby'] = 'desc';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('child_admin_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                if (!empty($searchsort_session['sortby'])) {
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
        if (!empty($searchtext)) {
            $data['searchtext'] = stripslashes($searchtext);
        } else {
            if (empty($allflag)) {
                if (!empty($searchsort_session['searchtext'])) {
                    $searchtext = mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext'] = $searchsort_session['searchtext'];
                }
            }
        }
        if (!empty($perpage)) {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }

        $config['base_url'] = site_url($this->user_type . '/' . "child_admin/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        $table = "child_admin_website as caw";
        $fields = array('caw.id,caw.status,CONCAT_WS(" ",caw.first_name,caw.last_name) as user_name,caw.email_id', 'caw.domain', 'caw.slug', 'lm.admin_name,caw.status,mm.mls_name');
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id',
            'mls_master as mm' => 'mm.id=caw.mls_id');
        $where = array('website_status'=>1);
        if (!empty($searchtext)) {
            $match = array('caw.domain' => $searchtext, 'CONCAT_WS(" ",caw.first_name,caw.last_name)' => $searchtext, 'caw.email_id' => $searchtext, 'lm.admin_name' => $searchtext);
            $data['datalist'] = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, 'left', $match, '', 'like', $config['per_page'], $uri_segment, $data['sortfield'], $data['sortby'], '',$where);
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, 'left', $match, '', 'like', '', '', '', '', '', $where, '', '1');
        } else {
            $data['datalist'] = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $where, '', $config['per_page'], $uri_segment, $data['sortfield'], $data['sortby']);
           
            $config['total_rows'] = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $where, '', '', '', '', '', '', '', '', '1');
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg'] = $this->message_session['msg'];
        $sortsearchpage_data = array(
            'sortfield' => !empty($data['sortfield']) ? $data['sortfield'] : '',
            'sortby' => !empty($data['sortby']) ? $data['sortby'] : '',
            'searchtext' => !empty($data['searchtext']) ? $data['searchtext'] : '',
            'perpage' => !empty($data['perpage']) ? trim($data['perpage']) : '10',
            'uri_segment' => !empty($uri_segment) ? $uri_segment : '0',
            'total_rows' => !empty($config['total_rows']) ? $config['total_rows'] : '0');
        $this->session->set_userdata('child_admin_sortsearchpage_data', $sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->user_type . '/' . $this->viewName . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->user_type . '/' . $this->viewName . "/list";
            $this->load->view('superadmin/include/template', $data);
        }
    }

    /*
      @Description: Function Add New Child website admin details
      @Author: Sanjay Chabhadiya
      @Input: -
      @Output: - Load Form for add Child website admin details
      @Date: 16-04-2015
     */

    public function add_record() {
        $this->load->model('mls_master_model');
        $field = array('admin_name', 'id', 'email_id');
        $match = array('user_type' => '2', 'status' => '1');
        $result = $this->admin_model->get_user($field, $match, '', '=');
        $data['admin_name'] = $result;
        $match = array('status' => 1);
        $data['mls_master'] = $this->mls_master_model->select_records('', $match, '', '=');

        /// Get MLS Property Type
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db . '.mls_property_type';
        $data['property_type'] = $this->obj->getmultiple_tables_records($table);
        $data['main_content'] = "superadmin/" . $this->viewName . "/add";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
      @Description: Function for Insert New child website data
      @Author: Sanjay chabhadiya
      @Input: - Details of new Child website admin which is inserted into DB
      @Output: - List of Child website admin with new inserted records
      @Date: 16-04-2015
     */

    public function insert_data() {
        $cdata['first_name'] = $this->input->post('first_name');
        $cdata['last_name'] = $this->input->post('last_name');
        $cdata['email_id'] = $this->input->post('txt_email_id');
        $cdata['slug'] = $this->seoUrl($this->input->post('slug'));
        $cdata['mls_id'] = $this->input->post('mls_id');
        $cdata['selected_theme'] = $this->input->post('selected_theme');
        $cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));

        $cdata['lw_admin_id'] = $this->input->post('lw_admin_id');
        //$cdata['zopim_livechat_script'] = $this->input->post('zopim_livechat_script');
        $domain = $this->input->post('domain');
        $domain = str_replace('www.', '', $domain);
        $domain = trim($domain, '/');

        $cdata['domain'] = $domain;
        $cdata['created_date'] = date('Y-m-d H:i:s');
        $cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['status'] = $this->input->post('status');
        $cdata['website_status'] = 1;
        $admin_id = $this->obj->insert_record($cdata);

        $fields = array('db_name');
        $match = array('id' => $cdata['lw_admin_id']);
        $result = $this->admin_model->get_user($fields, $match, '', '=');

        if (!empty($result[0]['db_name'])) {
            $icdata['first_name'] = $cdata['first_name'];
            $icdata['last_name'] = $cdata['last_name'];
            $icdata['email'] = $cdata['email_id'];
            $icdata['password'] = $cdata['password'];
            $icdata['child_user_type'] = 1;
            $icdata['created_date'] = $cdata['created_date'];
            $lead_user_id = $this->lead_users_model->insert_record($icdata, $result[0]['db_name']);
            unset($icdata);
            $icdata['domain_name'] = $cdata['domain'];
            $icdata['slug'] = $cdata['slug'];
            $icdata['login_id'] = $lead_user_id;
            //$icdata['zopim_livechat_script'] = $cdata['zopim_livechat_script'];
            $icdata['selected_theme'] = $cdata['selected_theme'];
            $icdata['created_date'] = $cdata['created_date'];
            $icdata['status'] = $cdata['status'];
            $icdata['website_status'] = 1;
            $domain_id = $this->lead_users_model->domain_insert_record($icdata, $result[0]['db_name']);
            $iccdata['id'] = $lead_user_id;
            $iccdata['domain'] = $domain_id;
            $this->lead_users_model->update_record($iccdata, $result[0]['db_name']);
            $this->fetch_agents($result[0]['db_name'], $domain_id);
            $this->create_cms($result[0]['db_name'], $domain_id, $admin_id);
        }

        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);

        $contacttab = $this->input->post('contacttab');
        $redirecttype = $this->input->post('submitbtn');

        if ($redirecttype == 'Save' || $contacttab == 6)
            redirect('superadmin/' . $this->viewName);
        else {
            redirect('superadmin/' . $this->viewName . '/edit_record/' . $admin_id . '/' . ($contacttab + 1));
        }
    }

    /*
      @Description: Get Details of Edit Child Admin
      @Author: Sanjay Chabhadiya
      @Input: - Id of child admin
      @Output: -
      @Date: 16-04-2014
     */

    public function edit_record() {
        $this->load->model('mls_master_model');
        $id = $this->uri->segment(4);
        $tabid = $this->uri->segment(5);
        // $match = array('id'=>$id);
        // $result = $this->obj->get_user('',$match,'','=');

        $table = 'child_admin_website as caw';
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id', 'mls_master as mm' => 'mm.id=caw.mls_id');
        $fields = array('caw.*,lm.admin_name,lm.email_id as admin_email_id,mm.mls_name');
        $match = array('caw.id' => $id,'website_status'=>1);
        $result = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, '', '',$match);
        if (!isset($result[0]['id']))
            redirect(base_url('superadmin/' . $this->viewName));

        $cdata['editRecord'] = $result;
        $field = array('admin_name', 'id', 'email_id');
        $admin_id = !empty($result[0]['lw_admin_id']) ? $result[0]['lw_admin_id'] : '0';
        // $wherestring = 'user_type = 2 AND (status = "1" OR id = '.$admin_id.')';
        // $cdata['admin_name'] = $this->admin_model->getmultiple_tables_records('login_master',$field,'','','','','','','','','','',$wherestring);
        // $match = array('status'=>1,'id'=>!empty($id)?$id:0);
        // $cdata['mls_master'] = $this->mls_master_model->select_records('',$match,'OR','=');
        /// Get MLS Property Type
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db . '.mls_property_type';
        $cdata['property_type'] = $this->obj->getmultiple_tables_records($table);

        // Carosules list Sanjay Moghariya. 30-04-2015
        $config['per_page'] = '10';
        $config['base_url'] = site_url($this->user_type . '/' . "child_admin/carousels/" . $id . "/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        $config['uri_segment'] = 0;
        $uri_segment = 0;

        ///// Carousels Tab //////
        $domain_id = $this->get_domain_id($id);
        if (!empty($domain_id))
            $match = array('cwcm.child_admin_id' => $id, 'cwcm.domain_id' => $domain_id);
        else
            $match = array('cwcm.child_admin_id' => $id);
        $table = "child_website_carousels_master as cwcm";
        $fields = array('cwcm.id,cwcm.carousels_name,cwcm.order_of_position,cwcm.status,cwcm.child_record_id');
        $cdata['datalist'] = $this->obj->getmultiple_tables_records($table, $fields, '', '', '', $match, '=', $config['per_page'], $uri_segment, 'id', 'desc', $group_by, '');
        $config['total_rows'] = $this->obj->getmultiple_tables_records($table, $fields, '', '', '', $match, '=', '', '', '', '', $group_by, '', '', '1');
        $this->pagination->initialize($config);
        $cdata['pagination'] = $this->pagination->create_links();
        ///// End Carousels Tab //////
        ///// Nearby Area tab //////
        if (!empty($domain_id))
            $match = array('cwna.child_admin_id' => $id, 'cwna.domain_id' => $domain_id);
        else
            $match = array('cwna.child_admin_id' => $id);
        $table = "child_website_nearby_area as cwna";
        $fields = array('cwna.id,cwna.location_text,cwna.order_of_display,cwna.location_url,cwna.child_record_id');
        $cdata['nearby_arealist'] = $this->child_nearby_area_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=', '', '', 'id', 'desc');
        ///// End Nearby Area tab //////
        ///// Footer Tab /////
        if (!empty($domain_id))
            $match = array('cwfl.superadmin_domain_id' => $id, 'cwfl.domain_id' => $domain_id);
        else
            $match = array('cwfl.superadmin_domain_id' => $id);
        $table = "child_website_footer_links as cwfl";
        $cdata['footer_links'] = $this->child_footer_links_model->getmultiple_tables_records($table, '', '', '', '', $match, '=', '', '', 'id', 'desc');
        // pr($this->db->last_query());
        // pr($cdata['footer_links']); die;
        ///// End Footer Tab /////
        ///// CMS List for the Footer Links /////
        $table = 'child_admin_website as caw';
        $join_table = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
        $fields = array('db_name,domain,lw_admin_id');
        $match = array('caw.id' => $id);
        $db_result = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);

        if (!empty($db_result[0]['db_name'])) {
            $match = array('domain_id' => $domain_id, 'superadmin_domain_id' => $id);
            $cdata['cms_list'] = $this->cms_model->select_records('', $match, '', '=', '', '', '', '', '', '', $db_result[0]['db_name']);
        }

        ///// Policy Pages Tab /////
        if (!empty($db_result[0]['db_name']) && !empty($domain_id)) {
            $match = array('domain_id' => $domain_id, 'superadmin_domain_id' => $id, 'slug' => 'terms-of-use');
            $cdata['policy_terms'] = $this->cms_model->select_records('', $match, '', '=', '', '', '', '', '', '', $db_result[0]['db_name']);

            $match = array('domain_id' => $domain_id, 'superadmin_domain_id' => $id, 'slug' => 'privacy-policy');
            $cdata['policy_privacy'] = $this->cms_model->select_records('', $match, '', '=', '', '', '', '', '', '', $db_result[0]['db_name']);

            $match = array('domain_id' => $domain_id, 'superadmin_domain_id' => $id, 'slug' => 'dmca');
            $cdata['policy_dmca'] = $this->cms_model->select_records('', $match, '', '=', '', '', '', '', '', '', $db_result[0]['db_name']);
        }
        ///// End Policy Pages Tab /////

        $cdata['editarea_id'] = $id;
        if ($tabid == 3)
            $cdata['msg1'] = $this->message_session['msg'];
        else if ($tabid == 5)
            $cdata['msg5'] = $this->message_session['msg'];
        else if ($tabid == 6)
            $cdata['msg6'] = $this->message_session['msg'];
        $cdata['main_content'] = "superadmin/" . $this->viewName . "/add";
        $this->load->view("superadmin/include/template", $cdata);
    }

    function get_domain_id($edit_id) {
        /////// Domain id
        $table = "child_admin_website as caw";
        $fields = array('caw.id,caw.lw_admin_id,caw.domain,lm.db_name,lm.timezone,lm.email_id');
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('caw.id' => $edit_id,'website_status'=>1);
        //$match = array();
        $domain_result = $this->carousels_master_model->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $match, '=');
        $domain_id = 0;
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            $table = $domain_result[0]['db_name'] . '.child_website_domain_master';
            $fields = array('id');
            $match = array('domain_name' => $domain_result[0]['domain'],'website_status'=>1);
            $domain_arr = $this->carousels_master_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
            $domain_id = !empty($domain_arr[0]['id']) ? $domain_arr[0]['id'] : '';
        }
        ///////
        if (!empty($domain_id))
            return $domain_id;
        else
            return 0;
    }

    /*
      @Description: Function for Update Superadmin Profile
      @Author: Sanjay Chabhadiya
      @Input: - Update details of child admin
      @Output: - List with updated child admin details
      @Date: 16-04-2015
     */

    public function update_data() {

        $this->load->model('contacts_model');
        $contacttab = $this->input->post('contacttab');
        $cdata['id'] = $this->input->post('id');
        $edited_domain_id = $this->get_domain_id($cdata['id']);

        // Get Child db name
        $domain_result = $this->get_child_dbname($cdata['id']);

        if ($contacttab == 1) {
            $cdata['first_name'] = $this->input->post('first_name');
            $cdata['last_name'] = $this->input->post('last_name');
            $cdata['email_id'] = $this->input->post('txt_email_id');
            $cdata['slug'] = $this->seoUrl($this->input->post('slug'));
            $cdata['selected_theme'] = $this->input->post('selected_theme');
            //$cdata['zopim_livechat_script'] = $this->input->post('zopim_livechat_script');
            $password = $this->input->post('password');
            if (!empty($password)) {
                $cdata['password'] = $this->common_function_model->encrypt_script($this->input->post('password'));
                $icdata['password'] = $cdata['password'];
            }

            //$cdata['lw_admin_id'] = $this->input->post('lw_admin_id');
            $lw_admin_id = $this->input->post('lw_admin_id');
            $cdata['modified_date'] = date('Y-m-d H:i:s');
            $cdata['modified_by'] = $this->superadmin_session['id'];
            $cdata['status'] = $this->input->post('status');

            $icdata['first_name'] = $cdata['first_name'];
            $icdata['last_name'] = $cdata['last_name'];
            $icdata['email'] = $cdata['email_id'];
            //$icdata['zopim_livechat_script'] = $this->input->post('zopim_livechat_script');
            $icdata['child_user_type'] = 1;

            $table = 'child_admin_website as caw';
            $join_table = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
            $fields = array('db_name,domain,lw_admin_id,caw.created_date,caw.password');
            $match = array('caw.id' => $cdata['id']);
            $old_result = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);

            if (!isset($icdata['password']) && !empty($old_result[0]['password']))
                $icdata['password'] = $old_result[0]['password'];

            $icdata['child_user_type'] = 1;

            if ((!empty($old_result[0]['lw_admin_id']) && $old_result[0]['lw_admin_id'] == $lw_admin_id)) {
                $fields = array('id,domain');
                $child_data = $this->lead_users_model->select_child_website_domain_master($old_result[0]['domain'], $old_result[0]['db_name']);

                if (!empty($child_data[0]['id'])) {
                    $fields = array('id,domain');
                    $match = array('domain' => $child_data[0]['id'], 'child_user_type' => 1);
                    $lead_data = $this->lead_users_model->select_records($fields, $match, '', '=', '', '', '', '', '', $old_result[0]['db_name']);
                    if (!empty($lead_data[0]['id'])) {
                        $icdata['id'] = $lead_data[0]['id'];
                        $this->lead_users_model->update_record($icdata, $old_result[0]['db_name']);
                    }
                    $iccdata['id'] = $child_data[0]['id'];
                    $iccdata['slug'] = $cdata['slug'];
                    $iccdata['selected_theme'] = $cdata['selected_theme'];
                    //$iccdata['zopim_livechat_script'] = $cdata['zopim_livechat_script'];
                    $this->lead_users_model->domain_update_record($iccdata, $old_result[0]['db_name']);
                }
            } else {
                $fields = array('db_name');
                $match = array('id' => $lw_admin_id);
                $result = $this->admin_model->get_user($fields, $match, '', '=');
                if (!empty($result[0]['db_name'])) {
                    $new_db_name = $result[0]['db_name'];
                    $domain_name = !empty($old_result[0]['domain']) ? $old_result[0]['domain'] : '';
                    $table = $new_db_name . '.child_website_domain_master as cwd';
                    $join_table = array($new_db_name . '.lead_users as lu' => 'cwd.id = lu.domain');
                    $fields = array('cwd.*,lu.id as ID');
                    $match = array('cwd.domain_name' => $domain_name, 'child_user_type' => 1);
                    $domain_exist = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);

                    if (!empty($domain_exist[0]['id'])) {
                        $lead_user_id = $domain_exist[0]['ID'];
                        $domain_id = $domain_exist[0]['id'];

                        $icdata['status'] = $cdata['status'];
                        $icdata['id'] = $lead_user_id;
                        $this->lead_users_model->update_record($icdata, $new_db_name);
                    } else {
                        $icdata['created_date'] = !empty($old_result[0]['created_date']) ? $old_result[0]['created_date'] : date('Y-m-d H:i:s');
                        $lead_user_id = $this->lead_users_model->insert_record($icdata, $new_db_name);
                        $iicdata['domain_name'] = $domain_name;
                        $iicdata['slug'] = $cdata['slug'];
                        $iicdata['login_id'] = $lead_user_id;
                        $iicdata['selected_theme'] = $cdata['selected_theme'];
                        //$iicdata['zopim_livechat_script'] = $cdata['zopim_livechat_script'];
                        $iicdata['created_date'] = $icdata['created_date'];
                        $iicdata['status'] = $cdata['status'];
                        $domain_id = $this->lead_users_model->domain_insert_record($iicdata, $new_db_name);
                        $iccdata['id'] = $lead_user_id;
                        $iccdata['domain'] = $domain_id;
                        $this->lead_users_model->update_record($iccdata, $result[0]['db_name']);
                    }

                    /// Copy data into new admin db
                    if (!empty($old_result[0]['db_name'])) {
                        $old_db_name = $old_result[0]['db_name'];
                        $table = $old_db_name . '.child_website_domain_master as cwd';
                        $join_table = array($old_db_name . '.lead_users as lu' => 'cwd.id = lu.domain');
                        $fields = array('cwd.*,lu.id as ID');
                        $match = array('cwd.domain_name' => $domain_name, 'child_user_type' => 1);
                        $domain_master = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);

                        if (!empty($domain_master[0]['ID'])) {
                            $status_update['status'] = 0;
                            $status_update['id'] = $domain_master[0]['ID'];
                            $this->lead_users_model->update_record($status_update, $old_db_name);
                        }

                        $common_data['lead_user_id'] = $lead_user_id;
                        $common_data['domain_id'] = $domain_id;
                        $common_data['old_db_name'] = $old_db_name;
                        $common_data['new_db_name'] = $new_db_name;
                        $common_data['old_lead_user'] = !empty($domain_master[0]['ID']) ? $domain_master[0]['ID'] : 0;


                        //  insert lead user into new db lead user
                        $table = $old_db_name . '.lead_users as lu';
                        $match = array('domain' => !empty($domain_master[0]['id']) ? $domain_master[0]['id'] : 0, 'child_user_type' => 2);
                        $lead_user = $this->obj->getmultiple_tables_records($table, '', '', '', '', $match);

                        $userdata = array();
                        if (!empty($lead_user) && count($lead_user)) {
                            foreach ($lead_user as $row) {
                                //// insert lead user into livewire
                                $insert_data['first_name'] = $row['first_name'];
                                $insert_data['last_name'] = $row['last_name'];
                                $insert_data['email'] = $row['email'];
                                $insert_data['password'] = $row['password'];
                                $insert_data['child_user_type'] = $row['child_user_type'];
                                $insert_data['domain'] = $domain_id;
                                //$insert_data['zopim_livechat_script'] = $row['zopim_livechat_script'];
                                $insert_data['phone_no'] = $row['phone_no'];
                                $insert_data['address'] = $row['address'];
                                $insert_data['city'] = $row['city'];
                                $insert_data['state'] = $row['state'];
                                $insert_data['zip_code'] = $row['zip_code'];
                                $insert_data['created_date'] = $row['created_date'];
                                $insert_data['status'] = $row['status'];
                                $lastid = $this->lead_users_model->insert_record($insert_data, $new_db_name);

                                $match = array('id' => $row['lw_id']);
                                $contact_detail = $this->contacts_model->select_records('', $match, '', '=', '', '', '', '', '', '', $old_db_name);
                                /// insert contact into livewire
                                $udata['joomla_user_id'] = $lastid;
                                $udata['joomla_domain_name'] = !empty($contact_detail[0]['joomla_domain_name']) ? $contact_detail[0]['joomla_domain_name'] : '';
                                $udata['domain_id'] = $common_data['domain_id'];
                                $udata['created_type'] = '6';
                                $udata['status'] = '1';
                                $udata['joomla_contact_type'] = 'Buyer';
                                $udata['joomla_ip_address'] = !empty($contact_detail[0]['joomla_ip_address']) ? $contact_detail[0]['joomla_ip_address'] : $_SERVER['REMOTE_ADDR'];
                                $udata['created_date'] = $row['created_date'];
                                $udata['first_name'] = $row['first_name'];
                                $udata['last_name'] = $row['last_name'];
                                $contact_id = $this->contacts_model->insert_record($udata, $new_db_name);
                                $userdata[$row['id']] = $contact_id;


                                //// Update livewire id
                                $ludata['id'] = $lastid;
                                $ludata['lw_id'] = $contact_id;
                                $this->lead_users_model->update_record($ludata, $new_db_name);

                                /// insert lead contact email transaction
                                $edata['contact_id'] = $contact_id;
                                $edata['email_type'] = '0';
                                $edata['email_address'] = $insert_data['email'];
                                $edata['is_default '] = '1';
                                $edata['status'] = '1';
                                $ins_data_email = $this->contacts_model->insert_email_trans_record($edata, $new_db_name);

                                /// insert lead contact phone transaction
                                $pdata['contact_id'] = $contact_id;
                                $pdata['phone_type'] = '0';
                                $pdata['phone_no'] = $insert_data['phone_no'];
                                $pdata['is_default '] = '1';
                                $pdata['status'] = '1';

                                $this->contacts_model->insert_phone_trans_record($pdata, $new_db_name);

                                /// insert lead contact address transaction
                                $address_data['contact_id'] = $contact_id;
                                $address_data['address_type'] = '0';
                                $address_data['address_line1'] = $insert_data['address'];
                                $address_data['city'] = $insert_data['city'];
                                $address_data['state '] = $insert_data['state'];
                                $address_data['zip_code'] = $insert_data['zip_code'];
                                $address_data['status'] = '1';
                                $this->contacts_model->insert_address_trans_record($address_data, $new_db_name);

                                /// Insert user last login into new DB
                                $this->obj->insert_last_login($common_data, $lastid, $contact_id, $row['id']);

                                /// Insert users view property into new admin DB
                                $this->obj->insert_property_view($common_data, $lastid, $contact_id, $row['id']);

                                /// Insert users save property into new admin DB
                                $this->obj->insert_user_save_property($common_data, $lastid, $contact_id, $row['id']);

                                /// Insert users request showing into new admin DB
                                $this->obj->insert_showing_request($common_data, $lastid, $contact_id, $row['id']);

                                /// Insert users property valuation searches into new DB
                                $this->obj->insert_property_valuation_searches($common_data, $lastid, $contact_id, $row['id']);

                                /// Insert users valuation contact into new DB
                                $this->obj->insert_valuation_contact($common_data, $lastid, $contact_id, $row['id']);
                            }
                        }

                        // Insert Blog post category
                        $this->obj->insert_blog_category_master($common_data);
                        // Insert Blog Post
                        $this->obj->insert_blog_post($common_data);
                        // Insert Blog Post Category Transaction
                        $this->obj->insert_blog_post_category_trans($common_data);
                        // Insert Blog Post Comment
                        $this->obj->insert_blog_post_comment($common_data);

                        // Insert cms into new db cms_master and child_website_cms_menu_trans table
                        $table = $old_db_name . '.cms_master as cm';
                        $join_table = array($old_db_name . '.child_website_cms_menu_trans as cmt' => 'cm.id = cmt.cms_id');
                        $fields = array('cm.*,cmt.menu_type,cmt.menu_sequence,cmt.created_date as cmt_created_date,cmt.parent_menu_id');
                        $match = array('cm.created_by' => !empty($domain_master[0]['ID']) ? $domain_master[0]['ID'] : 0, 'parent_menu_id' => 0);
                        $cms_master = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);
                        if (!empty($cms_master))
                            $this->insert_cms_master($common_data, $cms_master);

                        // Insert carousels into new db child_website_carousels_master table
                        $table = $old_db_name . '.child_website_carousels_master as cwcm';
                        $fields = array('cwcm.*');
                        $match = array('cwcm.created_by' => !empty($domain_master[0]['ID']) ? $domain_master[0]['ID'] : 0);
                        $carousels_master = $this->obj->getmultiple_tables_records($table, $fields, '', '', '', $match);
                        if (!empty($carousels_master)) {
                            foreach ($carousels_master as $row) {
                                $insert_carousels['carousels_type'] = $row['carousels_type'];
                                $insert_carousels['carousels_name'] = $row['carousels_name'];
                                $insert_carousels['domain_id'] = $domain_id;
                                $insert_carousels['created_by'] = $lead_user_id;
                                $insert_carousels['created_date'] = $row['created_date'];
                                $carouse_id = $this->obj->insert_record_carousels_master($insert_carousels, $new_db_name);
                                $this->obj->insert_carousels_trans($carouse_id, $row['id'], $common_data);
                            }
                        }

                        //  Copy banner into new admin db
                        $this->obj->insert_banner_master($common_data);
                    }
                }
            }
            $this->obj->update_record($cdata);
        } // Analytics Code
        elseif ($contacttab == 2) {
            $tab2_data['id'] = $cdata['id'];
            $tab2_data['google_analytics_code'] = $this->input->post('google_analytics_code');
            // $tab2_data['google_adword_code'] = $this->input->post('google_adword_code');
            $tab2_data['adword_registration'] = $this->input->post('adword_registration');
            $tab2_data['adword_login'] = $this->input->post('adword_login');
            $tab2_data['adword_property_valuation'] = $this->input->post('adword_property_valuation');
            $tab2_data['adword_reg_two_property'] = $this->input->post('adword_reg_two_property');
            $tab2_data['adword_detail_property'] = $this->input->post('adword_detail_property');
            $tab2_data['adword_new_property'] = $this->input->post('adword_new_property');
            $tab2_data['modified_date'] = date('Y-m-d H:i:s');
            $tab2_data['modified_by'] = $this->superadmin_session['id'];
            $this->obj->update_record($tab2_data);

            /// Update record into child DB
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                $child_update['id'] = $edited_domain_id;
                $child_update['google_analytics_code'] = $this->input->post('google_analytics_code');
                // $child_update['google_adword_code'] = $this->input->post('google_adword_code');
                $child_update['adword_registration'] = $this->input->post('adword_registration');
                $child_update['adword_login'] = $this->input->post('adword_login');
                $child_update['adword_property_valuation'] = $this->input->post('adword_property_valuation');
                $child_update['adword_reg_two_property'] = $this->input->post('adword_reg_two_property');
                $child_update['adword_detail_property'] = $this->input->post('adword_detail_property');
                $child_update['adword_new_property'] = $this->input->post('adword_new_property');
                $this->lead_users_model->domain_update_record($child_update, $domain_result[0]['db_name']);
            }
        } elseif ($contacttab == 5) { //Home Page Meta Data
            $tab5_data['id'] = $cdata['id'];
            $tab5_data['meta_data_title'] = $this->input->post('meta_data_title');
            $tab5_data['meta_data_description'] = $this->input->post('meta_data_description');
            $tab5_data['meta_data_keywords'] = $this->input->post('meta_data_keywords');
            $tab5_data['meta_data_robot'] = $this->input->post('meta_data_robot');
            $tab5_data['meta_data_default_city'] = $this->input->post('meta_data_default_city');
            $tab5_data['modified_date'] = date('Y-m-d H:i:s');
            $tab5_data['modified_by'] = $this->superadmin_session['id'];
            $this->obj->update_record($tab5_data);

            /// Update record into child DB
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                $child5_data['id'] = $edited_domain_id;
                $child5_data['meta_data_title'] = $this->input->post('meta_data_title');
                $child5_data['meta_data_description'] = $this->input->post('meta_data_description');
                $child5_data['meta_data_keywords'] = $this->input->post('meta_data_keywords');
                $child5_data['meta_data_robot'] = $this->input->post('meta_data_robot');
                $child5_data['meta_data_default_city'] = $this->input->post('meta_data_default_city');
                $this->lead_users_model->domain_update_record($child5_data, $domain_result[0]['db_name']);
            }
        } elseif ($contacttab == 7) { //Privacy Policy
            $tab6_data['id'] = $cdata['id'];
            $tab6_data['mls_disclaimer'] = $this->input->post('mls_disclaimer');

            $oldcontactimg = $this->input->post('mls_logo');
            $bgImgPath = $this->config->item('admin_big_img_path');
            $smallImgPath = $this->config->item('admin_small_img_path');
            if (!empty($_FILES['mls_logo']['name'])) {
                $uploadFile = 'mls_logo';
                $thumb = "thumb1";
                $hiddenImage = !empty($oldcontactimg) ? $oldcontactimg : '';
                $tab6_data['mls_logo'] = $this->imageupload_model->uploadBigImage($uploadFile, $bgImgPath, $smallImgPath, $thumb, $hiddenImage);
            }

            $tab6_data['modified_date'] = date('Y-m-d H:i:s');
            $tab6_data['modified_by'] = $this->superadmin_session['id'];
            $this->obj->update_record($tab6_data);

            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                $child6_data['id'] = $edited_domain_id;
                $child6_data['mls_disclaimer'] = $this->input->post('mls_disclaimer');
                if (!empty($tab6_data['mls_logo'])) {
                    $child6_data['mls_logo'] = $tab6_data['mls_logo'];
                    copy(($bgImgPath) . $tab6_data['mls_logo'], $this->config->item('child_big_img_base_path') . $child6_data['mls_logo']);
                    copy(($smallImgPath) . $tab6_data['mls_logo'], $this->config->item('child_small_img_base_path') . $child6_data['mls_logo']);
                }
                $this->lead_users_model->domain_update_record($child6_data, $domain_result[0]['db_name']);
            }

            $terms_id = $this->input->post('terms_of_use_id');
            $policy_id = $this->input->post('privacy_policy_id');
            $dmca_id = $this->input->post('dmca_id');

            if (!empty($terms_id) && !empty($policy_id) && !empty($dmca_id)) {
                $cms_data['id'] = $terms_id;
                $cms_data['description'] = $this->input->post('terms_of_use');
                $this->cms_model->update_record($cms_data, $domain_result[0]['db_name']);

                unset($cms_data);
                $cms_data['id'] = $policy_id;
                $cms_data['description'] = $this->input->post('privacy_policy');
                $this->cms_model->update_record($cms_data, $domain_result[0]['db_name']);

                unset($cms_data);
                $cms_data['id'] = $dmca_id;
                $cms_data['description'] = $this->input->post('dmca');
                $this->cms_model->update_record($cms_data, $domain_result[0]['db_name']);
            }
        }
        $msg = $this->lang->line('common_edit_success_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $superadmin_id = $this->input->post('id');
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];

        $redirecttype = $this->input->post('submitbtn');

        if (($redirecttype == 'Save' || $contacttab == 7)) {
            redirect(base_url('superadmin/' . $this->viewName . '/' . $pagingid));
        } else {
            /* if($contacttab == 5 || $contacttab == 6)
              $contacttab = $contacttab;
              else
              $contacttab = $contacttab + 1;
             */
            redirect('superadmin/' . $this->viewName . '/edit_record/' . $cdata['id'] . '/' . ($contacttab + 1));
        }
    }

    /*
      @Description: Function for Insert CMS master
      @Author: Sanjay Chabhadiya
      @Input: -
      @Output: -
      @Date: 16-04-2015
     */

    public function insert_cms_master($data = '', $cms_master = '', $parent_id = '', $current_id = '', $menu_id = '') {
        if (!empty($parent_id)) {
            $table = $data['old_db_name'] . '.cms_master as cm';
            $join_table = array($data['old_db_name'] . '.child_website_cms_menu_trans as cmt' => 'cm.id = cmt.cms_id');
            $fields = array('cm.*,cmt.menu_type,cmt.menu_sequence,cmt.created_date as cmt_created_date,cmt.parent_menu_id');
            $match = array('cmt.parent_menu_id' => $parent_id);
            $cms_master = $this->obj->getmultiple_tables_records($table, $fields, $join_table, 'like', '', $match);
        }
        if (!empty($cms_master) && count($cms_master) > 0) {
            foreach ($cms_master as $row1) {
                $cms_data['menu_title'] = $row1['menu_title'];
                $cms_data['title'] = $row1['title'];
                $cms_data['page_type'] = $row1['page_type'];
                $cms_data['domain_id'] = $data['domain_id'];
                $cms_data['description'] = $row1['description'];
                $cms_data['page_url'] = $row1['page_url'];
                $cms_data['slug'] = $row1['slug'];
                $cms_data['page_position'] = $row1['page_position'];
                $cms_data['short_description'] = $row1['short_description'];
                $cms_data['meta_title'] = $row1['meta_title'];
                $cms_data['meta_keyword'] = $row1['meta_keyword'];
                $cms_data['meta_description'] = $row1['meta_description'];
                $cms_data['menu_id'] = !empty($menu_id) ? $menu_id : '';
                $cms_data['menu_level'] = $row1['menu_level'];
                $cms_data['publish_on'] = $row1['publish_on'];
                $cms_data['created_by'] = $data['lead_user_id'];
                $cms_data['created_date'] = $row1['created_date'];
                $cms_data['status'] = '1';
                $cms_data['menu_image'] = $row1['menu_image'];
                $cms_id = $this->cms_model->insert_record($cms_data, $data['new_db_name']);

                $trans_data['cms_id'] = $cms_id;
                $trans_data['menu_type'] = $row1['menu_type'];
                $trans_data['menu_sequence'] = $row1['menu_sequence'];
                $trans_data['parent_menu_id'] = !empty($current_id) ? $current_id : '';
                $trans_data['created_by'] = $data['lead_user_id'];
                $trans_data['created_date'] = !empty($row1['cmt_created_date']) ? $row1['cmt_created_date'] : date('Y-m-d H:i:s');
                $trans_data['status'] = '1';
                $this->cms_model->insert_cms_menu_trans($trans_data, $data['new_db_name']);

                if (isset($row1['parent_menu_id']) && $row1['parent_menu_id'] == 0)
                    $menu_id = $cms_id;
                else
                    $menu_id = $menu_id;
                $this->insert_cms_master($data, '', $row1['id'], $cms_id, $menu_id);
                if (isset($row1['parent_menu_id']) && $row1['parent_menu_id'] == 0) {
                    $update_cms['id'] = $cms_id;
                    $update_cms['menu_id'] = $cms_id;
                    $this->cms_model->update_record($update_cms, $data['new_db_name']);
                }
            }
        }
    }

    /*
      @Description: Function for Delete Child website admin By Superadmin
      @Author: Sanjay Chabhadiya
      @Input: - Delete id which Child website admin record want to delete
      @Output: - New Child website admin list after record is deleted.
      @Date: 16-04-2015
     */

    function delete_record() {
        $id = $this->uri->segment(4);
        $this->obj->delete_record($id);
        $msg = $this->lang->line('common_delete_success_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        redirect('superadmin/' . $this->viewName);
    }

    /*
      @Description: Function for Delete Child website admin By Superadmin
      @Author: Sanjay Chabhadiya
      @Input: - Delete id which Child website admin record want to delete
      @Output: - New Child website admin list after record is deleted.
      @Date: 16-04-2015
     */

    public function ajax_delete_all() {
        $id = $this->input->post('single_remove_id');
        $delete_all_flag = 0;
        $cnt = 0;

        if (!empty($id))
            $array_data[0] = $id;
        else
            $array_data = $this->input->post('myarray');

        /* if(!empty($id))
          {
          $table = "child_admin_website as caw";
          $fields = array('db_name,domain');
          $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
          $match = array('caw.id'=>$id);
          $datalist = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match);
          $domain_master = $this->lead_users_model->select_child_website_domain_master($datalist[0]['domain'],$datalist[0]['db_name']);

          $this->obj->delete_record($id);
          if(!empty($datalist[0]['db_name']))
          {
          // Select child website domain master id
          $domain_master = $this->lead_users_model->select_child_website_domain_master($datalist[0]['domain'],$datalist[0]['db_name']);
          // Delete child website domain master
          $this->lead_users_model->domain_delete_record($datalist[0]['domain'],$datalist[0]['db_name']);
          // Delete child website admin
          if(!empty($domain_master[0]['id']))
          {
          $domain_id = $domain_master[0]['id'];
          $db_name = $datalist[0]['db_name'];
          // Delete theme background image
          if(!empty($domain_master[0]['theme_bg_image']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/theme_background_image/big/'.$domain_master[0]['theme_bg_image']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/theme_background_image/big/'.$domain_master[0]['theme_bg_image']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/theme_background_image/small/'.$domain_master[0]['theme_bg_image']);
          }
          // Delete logo picture
          if(!empty($domain_master[0]['logo_pic']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/logo/big/'.$domain_master[0]['logo_pic']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/logo/big/'.$domain_master[0]['logo_pic']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/logo/small/'.$domain_master[0]['logo_pic']);
          }
          // Delete favicon picture
          if(!empty($domain_master[0]['favicon_pic']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/favicon/big/'.$domain_master[0]['favicon_pic']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/favicon/big/'.$domain_master[0]['favicon_pic']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/favicon/small/'.$domain_master[0]['favicon_pic']);
          }

          $this->lead_users_model->delete_record($domain_id,$db_name);
          // Delete CMS Master
          $this->lead_users_model->delete_child_website_data('cms_master',array('domain_id'=>$domain_id),$db_name);
          // Delete CMS Child Menu
          $this->lead_users_model->delete_child_website_data('child_website_cms_menu_trans',array('domain_id'=>$domain_id),$db_name);
          // Delete Blog Category
          $this->lead_users_model->delete_child_website_data('blog_category_master',array('domain_id'=>$domain_id),$db_name);
          // Delete Blog Post and blog post category transaction
          $result = $this->lead_users_model->select_child_website_data('blog_post',array('id,post_image'),array('domain_id'=>$domain_id),$db_name);
          if(!empty($result) && count($result) > 0)
          {
          foreach($result as $row)
          {
          if(!empty($row['post_image']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/blog_post/big/'.$row['post_image']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/blog_post/big/'.$row['post_image']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/blog_post/small/'.$row['post_image']);
          }
          $this->lead_users_model->delete_child_website_data('blog_post_category_trans',array('post_id'=>$row['id']),$db_name);
          $this->lead_users_model->delete_child_website_data('blog_post_comments',array('post_id'=>$domain_id),$db_name);
          }
          }
          $this->lead_users_model->delete_child_website_data('blog_post',array('domain_id'=>$domain_id),$db_name);

          // Delete Carousels Property transaction
          $this->lead_users_model->delete_carousels_trans($domain_id,$db_name);
          // Delete Carousels Master
          $this->lead_users_model->delete_child_website_data('child_website_carousels_master',array('domain_id'=>$domain_id),$db_name);
          // Delete Banner Master
          $result = $this->lead_users_model->select_child_website_data('child_website_banner_master',array('id,banner_image'),array('domain_id'=>$domain_id),$db_name);
          if(!empty($result) && count($result) > 0)
          {
          foreach($result as $row)
          {
          if(!empty($row['banner_image']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/banner/big/'.$row['banner_image']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/banner/big/'.$row['banner_image']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/banner/small/'.$row['banner_image']);
          }
          }
          }
          $this->lead_users_model->delete_child_website_data('child_website_banner_master',array('domain_id'=>$domain_id),$db_name);
          // Delete Home Social Link
          //$this->lead_users_model->delete_child_website_data('child_website_home_social_links',array('domain_id'=>$domain_id),$db_name);
          // Delete Nearby area
          $this->lead_users_model->delete_child_website_data('child_website_nearby_area',array('domain_id'=>$domain_id),$db_name);
          // Delete Agents
          $result = $this->lead_users_model->select_child_website_data('child_website_agents',array('id,agent_pic'),array('domain_id'=>$domain_id),$db_name);
          if(!empty($result) && count($result) > 0)
          {
          foreach($result as $row)
          {
          if(!empty($row['agent_pic']) && file_exists($this->config->item('base_path').'/livewire_child_website/uploads/admin/big/'.$row['agent_pic']))
          {
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/admin/big/'.$row['agent_pic']);
          @unlink($this->config->item('base_path').'/livewire_child_website/uploads/admin/small/'.$row['agent_pic']);
          }
          $this->lead_users_model->delete_child_website_data('child_website_agents_contact_info',array('child_web_agent_id'=>$row['id']),$db_name);
          }
          }
          $this->lead_users_model->delete_child_website_data('child_website_agents',array('domain_id'=>$domain_id),$db_name);

          // Delete Footer Links
          $this->lead_users_model->delete_child_website_data('child_website_footer_links',array('domain_id'=>$domain_id),$db_name);
          }
          }
          unset($id);
          } */
        if (!empty($array_data)) {
            if (empty($id))
                $delete_all_flag = 1;
            for ($i = 0; $i < count($array_data); $i++) {
                $cnt++;
                $table = "child_admin_website as caw";
                $fields = array('db_name,domain');
                $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
                $match = array('caw.id' => $array_data[$i]);
                $datalist = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, '', '', $match);

                if (!empty($datalist[0]['db_name'])) {
                    // Select child website domain master id
                    $domain_master = $this->lead_users_model->select_child_website_domain_master($datalist[0]['domain'], $datalist[0]['db_name']);
                    // Delete child website domain master
                    //$this->lead_users_model->domain_delete_record($datalist[0]['domain'], $datalist[0]['db_name']);
                    // Delete child website admin
                    if (!empty($domain_master[0]['id'])) {
                        $domain_id = $domain_master[0]['id'];
                        $db_name = $datalist[0]['db_name'];
                        // Delete theme background image
                        if (!empty($domain_master[0]['theme_bg_image']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/theme_background_image/big/' . $domain_master[0]['theme_bg_image'])) {
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/theme_background_image/big/' . $domain_master[0]['theme_bg_image']);
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/theme_background_image/small/' . $domain_master[0]['theme_bg_image']);
                        }
                        // Delete logo picture
                        if (!empty($domain_master[0]['logo_pic']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/logo/big/' . $domain_master[0]['logo_pic'])) {
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/logo/big/' . $domain_master[0]['logo_pic']);
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/logo/small/' . $domain_master[0]['logo_pic']);
                        }
                        // Delete favicon picture
                        if (!empty($domain_master[0]['favicon_pic']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/favicon/big/' . $domain_master[0]['favicon_pic'])) {
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/favicon/big/' . $domain_master[0]['favicon_pic']);
                            @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/favicon/small/' . $domain_master[0]['favicon_pic']);
                        }

                        //$this->lead_users_model->delete_record($domain_id, $db_name);
                        // Delete CMS Master
                        $this->lead_users_model->delete_child_website_data('cms_master', array('domain_id' => $domain_id), $db_name);
                        // Delete CMS Child Menu
                        $this->lead_users_model->delete_child_website_data('child_website_cms_menu_trans', array('domain_id' => $domain_id), $db_name);
                        // Delete Blog Category
                        $this->lead_users_model->delete_child_website_data('blog_category_master', array('domain_id' => $domain_id), $db_name);
                        // Delete Blog Post and blog post category transaction
                        $result = $this->lead_users_model->select_child_website_data('blog_post', array('id,post_image'), array('domain_id' => $domain_id), $db_name);
                        if (!empty($result) && count($result) > 0) {
                            foreach ($result as $row) {
                                if (!empty($row['post_image']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/blog_post/big/' . $row['post_image'])) {
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/blog_post/big/' . $row['post_image']);
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/blog_post/small/' . $row['post_image']);
                                }
                                $this->lead_users_model->delete_child_website_data('blog_post_category_trans', array('post_id' => $row['id']), $db_name);
                                $this->lead_users_model->delete_child_website_data('blog_post_comments', array('post_id' => $domain_id), $db_name);
                            }
                        }
                        $this->lead_users_model->delete_child_website_data('blog_post', array('domain_id' => $domain_id), $db_name);

                        // Delete Carousels Property transaction
                        $this->lead_users_model->delete_carousels_trans($domain_id, $db_name);
                        // Delete Carousels Property transaction In Master DB
                        $this->lead_users_model->delete_carousels_trans($array_data[$i]);
                        // Delete Carousels Master
                        $this->lead_users_model->delete_child_website_data('child_website_carousels_master', array('domain_id' => $domain_id), $db_name);
                        // Delete Carousels Master In Master DB
                        $this->lead_users_model->delete_child_website_data('child_website_carousels_master', array('child_admin_id'=>$array_data[$i]));
                        
                        // Delete Banner Master
                        $result = $this->lead_users_model->select_child_website_data('child_website_banner_master', array('id,banner_image'), array('domain_id' => $domain_id), $db_name);
                        if (!empty($result) && count($result) > 0) {
                            foreach ($result as $row) {
                                if (!empty($row['banner_image']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/banner/big/' . $row['banner_image'])) {
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/banner/big/' . $row['banner_image']);
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/banner/small/' . $row['banner_image']);
                                }
                            }
                        }
                        $this->lead_users_model->delete_child_website_data('child_website_banner_master', array('domain_id' => $domain_id), $db_name);
                        // Delete Home Social Link
                        $this->lead_users_model->delete_child_website_data('child_website_home_social_links',array('domain_id'=>$domain_id),$db_name);
                        // Delete Nearby area
                        $this->lead_users_model->delete_child_website_data('child_website_nearby_area', array('domain_id' => $domain_id), $db_name);
                        // Delete Nearby area In Master DB
                        $this->lead_users_model->delete_child_website_data('child_website_nearby_area', array('child_admin_id'=>$array_data[$i]));
                        // Delete Agents
                        $result = $this->lead_users_model->select_child_website_data('child_website_agents', array('id,agent_pic'), array('domain_id' => $domain_id), $db_name);
                        if (!empty($result) && count($result) > 0) {
                            foreach ($result as $row) {
                                if (!empty($row['agent_pic']) && file_exists($this->config->item('base_path') . '/livewire_child_website/uploads/admin/big/' . $row['agent_pic'])) {
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/admin/big/' . $row['agent_pic']);
                                    @unlink($this->config->item('base_path') . '/livewire_child_website/uploads/admin/small/' . $row['agent_pic']);
                                }
                                $this->lead_users_model->delete_child_website_data('child_website_agents_contact_info', array('child_web_agent_id' => $row['id']), $db_name);
                            }
                        }
                        $this->lead_users_model->delete_child_website_data('child_website_agents', array('domain_id' => $domain_id), $db_name);

                        // Delete Footer Links
                        $this->lead_users_model->delete_child_website_data('child_website_footer_links', array('domain_id' => $domain_id), $db_name);
                        // Delete Footer Links In Master DB
                        $this->lead_users_model->delete_child_website_data('child_website_footer_links', array('superadmin_domain_id'=>$array_data[$i]));
                        
                         // Delete User Domain Trans
                        $this->lead_users_model->delete_child_website_data('user_domain_trans',array('domain_id'=>$domain_id),$db_name);
                        
                        $update_data['id'] = $domain_id;
                        $update_data['website_status'] = 0;
                        $this->lead_users_model->domain_update_record($update_data,$db_name);
                    }
                }
                //$this->obj->delete_record($array_data[$i]);
                $delete_site['id'] = $array_data[$i];
                $delete_site['website_status'] = 0;
                $this->obj->update_record($delete_site);
                /* if(!empty($datalist[0]['db_name']))
                  {
                  $domain_master = $this->lead_users_model->select_child_website_domain_master($datalist[0]['domain'],$datalist[0]['db_name']);
                  $this->lead_users_model->domain_delete_record($datalist[0]['domain'],$datalist[0]['db_name']);
                  if(!empty($domain_master[0]['id']))
                  $this->lead_users_model->delete_record($domain_master[0]['id'],$datalist[0]['db_name']);
                  } */
                unset($datalist);
            }
        }

        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage']) ? $searchsort_session['perpage'] : '10';
        $total_rows = $searchsort_session['total_rows'];
        if ($delete_all_flag == 1) {
            $total_rows -= $cnt;
            if ($pagingid * $perpage > $total_rows) {
                if ($total_rows % $perpage == 0) {
                    $pagingid -= $perpage;
                }
            }
        } else {
            if ($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if ($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for Unpublish child website By Superadmin
      @Author: Sanjay Chabhadiya
      @Input: -
      @Output: -
      @Date: 16-04-2015
     */

    function unpublish_record() {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = 0;

        $table = "child_admin_website as caw";
        $fields = array('caw.id,lm.db_name,caw.domain,caw.email_id');
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('caw.id' => $id);
        $datalist = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, '', $match, '', '=');

        $this->obj->update_record($cdata);
        if (!empty($datalist[0]['id'])) {
            $icdata['status'] = 0;
            $icdata['email'] = $datalist[0]['email_id'];
            $icdata['domain'] = $datalist[0]['domain'];
            $this->lead_users_model->update_status_record($icdata, $datalist[0]['db_name']);
        }

        $msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for publish child website By Superadmin
      @Author: Sanjay Chabhadiya
      @Input: -
      @Output: -
      @Date: 16-04-2015
     */

    function publish_record() {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = 1;

        $table = "child_admin_website as caw";
        $fields = array('caw.id,lm.db_name,caw.domain,caw.email_id');
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('caw.id' => $id);
        $datalist = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, '', $match, '', '=');

        $this->obj->update_record($cdata);
        if (!empty($datalist[0]['id'])) {
            $icdata['status'] = 1;
            $icdata['email'] = $datalist[0]['email_id'];
            $icdata['domain'] = $datalist[0]['domain'];
            $this->lead_users_model->update_status_record($icdata, $datalist[0]['db_name']);
        }
        $msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for check Domain Or slug already exist
      @Author: Sanjay Chabhadiya
      @Input: -
      @Output: -
      @Date: 15-04-2015
     */

    public function check_domain() {
        $id = $this->input->post('id');
        $domain1 = mysql_real_escape_string($this->input->post('domain'));
        $domain = str_replace('www.', '', $domain1);
        $domain = trim($domain, '/');
        $slug = $this->input->post('slug');
        $fields = array('id', 'domain');
        if (!empty($id)) {
            //Edit time
            $and_match = array('id' => $id);
        }
        if (!empty($domain)) {
            $match = array('domain' => $domain, 'website_status' => 1);
            $exist_domain = $this->obj->get_user($fields, $match, '', '=', '', '', '', '', '', '', !empty($and_match) ? $and_match : '');
        }
        if (!empty($slug)) {
            $slug = $this->seoUrl(trim($slug));
            $match = array('slug' => $slug, 'website_status' => 1);
            $exist_slug = $this->obj->get_user($fields, $match, '', '=', '', '', '', '', '', '', !empty($and_match) ? $and_match : '');
        }
        if (!empty($exist_domain))
            echo '1';
        elseif ($exist_slug)
            echo '2';
        else
            echo '0';
    }

    public function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    public function get_mls() {
        $admin_id = $this->input->post('admin_id');
        $table = "mls_assign_data as mad";
        $fields = array('');
        $join_tables = array('mls_master as mm' => 'mm.id = mad.mls_id');
        $where = array('mad.admin_id' => $admin_id);
        $datalist = $this->obj->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $where);
        echo json_encode($datalist);
    }

    /*
      @Description: Get carousels list
      @Author     : Sanjay Moghariya
      @Input      :
      @Output     : Carousels list
      @Date       : 01-05-2015
     */

    public function carousels() {
        $searchtext = '';
        $perpage = '';
        $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = trim($this->input->post('perpage'));
        $data['sortfield'] = 'id';
        $data['sortby'] = 'desc';
        $allflag = $this->input->post('allflag');
        $id = $this->input->post('id');
        $edit_id = $this->input->post('edit_id');
        $data['update_id'] = $edit_id;
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('child_carousels_sortsearchpage_data');
        }
        $searchsort_session = $this->session->userdata('child_carousels_sortsearchpage_data');

        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                if (!empty($searchsort_session['sortby'])) {
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
        if (!empty($searchtext)) {
            $data['searchtext'] = stripslashes($searchtext);
        } else {
            if (empty($allflag)) {
                if (!empty($searchsort_session['searchtext'])) {
                    $searchtext = mysql_real_escape_string($searchsort_session['searchtext']);
                    $data['searchtext'] = $searchsort_session['searchtext'];
                }
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
            }
        }
        $config['base_url'] = site_url($this->user_type . '/' . "child_admin/carousels/" . $edit_id . "/");
        $config['is_ajax_paging'] = TRUE; // default FALSE
        $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        /////// Domain id
        $domain_id = $this->get_domain_id($edit_id);
        ///////
        $table = 'child_website_carousels_master';
        if (!empty($domain_id))
            $where = array('child_admin_id' => $edit_id, 'domain_id' => $domain_id);
        else
            $where = array('child_admin_id' => $edit_id);
        if (!empty($searchtext)) {
            $match = array('carousels_name' => $searchtext, 'order_of_position' => $searchtext);
            $data['datalist'] = $this->carousels_master_model->getmultiple_tables_records($table, '', '', '', $match, '', 'like', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->carousels_master_model->getmultiple_tables_records($table, '', '', '', $match, '', 'like', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '1');
        } else {
            $data['datalist'] = $this->carousels_master_model->getmultiple_tables_records($table, '', '', '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->carousels_master_model->getmultiple_tables_records($table, '', '', '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '1');
        }

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['msg1'] = $this->message_session['msg'];

        $child_carousels_sortsearchpage_data = array(
            'sortfield' => !empty($data['sortfield']) ? $data['sortfield'] : '',
            'sortby' => !empty($data['sortby']) ? $data['sortby'] : '',
            'searchtext' => !empty($data['searchtext']) ? $data['searchtext'] : '',
            'perpage' => !empty($data['perpage']) ? trim($data['perpage']) : '10',
            'uri_segment' => !empty($uri_segment) ? $uri_segment : '0',
            'total_rows' => !empty($config['total_rows']) ? $config['total_rows'] : '0');

        $this->session->set_userdata('child_carousels_sortsearchpage_data', $child_carousels_sortsearchpage_data);
        $data['uri_segment'] = $uri_segment;
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->user_type . '/' . $this->viewName . '/carousels_ajax_list', $data);
        } else {
            $data['main_content'] = $this->user_type . '/' . $this->viewName . "/add";
            $this->load->view('admin/include/template', $data);
        }
    }

    /*
      @Description: Function Add New carousels
      @Author     : Sanjay Moghariya
      @Input      :
      @Output     : Insert form
      @Date       : 30-04-2015
     */

    public function add_carousels() {
        /// Get MLS Property Type
        $data['edit_id'] = $this->uri->segment(4);
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db . '.mls_property_type';
        $data['property_type'] = $this->obj->getmultiple_tables_records($table);
        $data['main_content'] = "superadmin/" . $this->viewName . "/add_carousels";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
      @Description: Function Insert/Edit carousels
      @Author     : Sanjay Moghariya
      @Input      : Insert/Edit data
      @Output     : Insert/Update record
      @Date       : 30-04-2015
     */

    public function insert_carousels() {
        $contacttab = $this->input->post('contacttab');
        $id = $this->input->post('id');
        $edit_id = $this->input->post('edit_id');
        $child_record_id = $this->input->post('child_record_id');
        /////// Domain id
        $data['domain_id'] = $this->get_domain_id($edit_id);
        ///////
        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);

        $data['carousels_name'] = $this->input->post('carousels_name');
        $data['order_of_position'] = $this->input->post('order_of_position');
        $data['status'] = $this->input->post('status');
        $data['carousels_type'] = $this->input->post('no_of_properties'); //Property Type (Carousels Type) = Single row - 3, Double row - 6
        $data['only_views'] = $this->input->post('only_views');
        $data['only_shortsale'] = $this->input->post('only_shortsale');
        $data['only_new_construction'] = $this->input->post('only_new_construction');
        $data['only_open_houses'] = $this->input->post('only_open_houses');
        $data['only_firms_listing'] = $this->input->post('only_firms_listing');
        $data['only_forclosures'] = $this->input->post('only_forclosures');
        $data['only_agent_listing'] = $this->input->post('only_agent_listing');
        $data['only_waterfront'] = $this->input->post('only_waterfront');
        $data['custom_db_fields'] = $this->input->post('custom_db_fields');
        $data['min_price'] = $this->input->post('min_price');
        $data['max_price'] = $this->input->post('max_price');

        $data['location_filter'] = $this->input->post('location_filter');
        if (!empty($data['location_filter'])) {
            $data['county'] = $this->input->post('county');
            $data['city'] = $this->input->post('city');
            $data['community_name'] = $this->input->post('community_name');
            $data['zipcode'] = $this->input->post('zipcode');
        } else {
            $data['county'] = '';
            $data['city'] = '';
            $data['community_name'] = '';
            $data['zipcode'] = '';
        }
        $property_type = $this->input->post('property_type');
        $data['child_admin_id'] = $edit_id;
        /// Edit Carousels
        if (!empty($id)) {
            $data['id'] = $id;
            $data['modified_by'] = $this->superadmin_session['id'];
            $data['modified_date'] = date('Y-m-d H:i:s');
            $this->carousels_master_model->update_record($data);

            /// Update record into child DB
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                if (!empty($child_record_id)) {
                    $data['id'] = $child_record_id;
                    $this->carousels_master_model->update_record($data, $domain_result[0]['db_name']);
                }
            }

            $fields = array('property_type_id');
            $match = array('carousels_id' => $id);
            $table = 'child_website_carousels_property_type_trans';
            $oldptype = $this->obj->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');

            $oldptypeslist = array();
            if (count($oldptype) > 0) {
                foreach ($oldptype as $row) {
                    $oldptypeslist[] = $row['property_type_id'];
                }
            }

            $oldptypearr = array_diff($oldptypeslist, $property_type);
            $newptypearr = array_diff($property_type, $oldptypeslist);

            if (isset($property_type) && empty($property_type))
                $oldptypearr = $oldptypeslist;

            ///////////// Delete carousels property type Transaction Data ///////////
            if (!empty($oldptypearr)) {
                foreach ($oldptypearr as $rowdata) {
                    $del_data['property_type_id'] = $rowdata;
                    $del_data['carousels_id'] = $id;
                    $this->carousels_master_model->delete_ptype_record($del_data);

                    /// Delete property type into child DB
                    if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                        if (!empty($child_record_id)) {
                            $deldata['property_type_id'] = $rowdata;
                            $deldata['carousels_id'] = $child_record_id;
                            $this->carousels_master_model->delete_ptype_record($deldata, $domain_result[0]['db_name']);
                        }
                    }
                }
            }

            /////////// Insert carousels property type Transaction Data /////////
            if (!empty($newptypearr)) {
                foreach ($newptypearr as $row) {
                    if (!empty($row)) {
                        $pdata['property_type_id'] = $row;
                        $pdata['carousels_id'] = $id;
                        $this->carousels_master_model->insert_record($pdata, 'child_website_carousels_property_type_trans');

                        /// Insert property type into child DB
                        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                            if (!empty($child_record_id)) {
                                $deldata['property_type_id'] = $row;
                                $deldata['carousels_id'] = $child_record_id;
                                $this->carousels_master_model->insert_record($deldata, 'child_website_carousels_property_type_trans', $domain_result[0]['db_name']);
                            }
                        }
                    }
                }
            }

            $msg = $this->lang->line('common_edit_success_msg');
        } else { //Insert Carousels
            $data['child_admin_id'] = $edit_id;
            $data['created_by'] = $this->superadmin_session['id'];
            $data['created_date'] = date('Y-m-d H:i:s');
            $last_id = 0;
            /// Insert record into child DB
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                $last_id = $this->carousels_master_model->insert_record($data, '', $domain_result[0]['db_name']);

                if (!empty($property_type)) {
                    foreach ($property_type as $row) {
                        $pdata['property_type_id'] = $row;
                        $pdata['carousels_id'] = $last_id;
                        $this->carousels_master_model->insert_record($pdata, 'child_website_carousels_property_type_trans', $domain_result[0]['db_name']);
                    }
                }
            }

            /// Insert into Parent DB
            if (!empty($last_id))
                $data['child_record_id'] = $last_id;

            $last_id = $this->carousels_master_model->insert_record($data);

            if (!empty($property_type)) {
                foreach ($property_type as $row) {
                    $pdata['property_type_id'] = $row;
                    $pdata['carousels_id'] = $last_id;
                    $this->carousels_master_model->insert_record($pdata, 'child_website_carousels_property_type_trans');
                }
            }

            $msg = $this->lang->line('common_add_success_msg');
        }
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $superadmin_id = $this->input->post('id');
        $searchsort_session = $this->session->userdata('child_carousels_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        //exit;
        redirect('superadmin/' . $this->viewName . '/edit_record/' . $edit_id . '/' . $contacttab);
    }

    /*
      @Description: Function Edit New carousels
      @Author     : Sanjay Moghariya
      @Input      :
      @Output     : Edit form
      @Date       : 01-05-2015
     */

    public function edit_carousels() {
        $data['edit_id'] = $this->uri->segment(4);
        $data['id'] = $this->uri->segment(5);
        $match = array('id' => $data['id']);
        $result = $this->carousels_master_model->select_records('', $match, '', '=');
        $data['editRecord'] = $result;

        $match = array('carousels_id' => $data['id']);
        $table = "child_website_carousels_property_type_trans";
        $fields = array('property_type_id');
        $ptype_id = $this->carousels_master_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=');
        $property_type_id = array();
        if (!empty($ptype_id)) {
            foreach ($ptype_id as $row) {
                $property_type_id[] = $row['property_type_id'];
            }
        }
        $data['property_type_id'] = $property_type_id;
        /// Get MLS Property Type
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db . '.mls_property_type';
        $data['property_type'] = $this->obj->getmultiple_tables_records($table);
        $data['main_content'] = "superadmin/" . $this->viewName . "/add_carousels";
        $this->load->view('superadmin/include/template', $data);
    }

    /*
      @Description: Function for Unpublish carousels
      @Author     : Sanjay Moghariya
      @Input      : carousels id
      @Output     : update status
      @Date       : 01-05-2015
     */

    function unpublish_carousels() {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = 0;
        $cdata['modified_by'] = $this->superadmin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->carousels_master_model->update_record($cdata);

        $child_record_id = $this->input->post('child_record_id');
        $edit_id = $this->input->post('edit_id');
        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);
        /// Update record into child DB
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            if (!empty($child_record_id)) {
                $cdata['id'] = $child_record_id;
                $this->carousels_master_model->update_record($cdata, $domain_result[0]['db_name']);
            }
        }

        $msg = $this->lang->line('common_unpublish_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('child_carousels_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for Publish carousels
      @Author     : Sanjay Moghariya
      @Input      : carousels id
      @Output     : update status
      @Date       : 01-05-2015
     */

    function publish_carousels() {
        $id = $this->uri->segment(4);
        $cdata['id'] = $id;
        $cdata['status'] = 1;
        $cdata['modified_by'] = $this->superadmin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $this->carousels_master_model->update_record($cdata);

        $child_record_id = $this->input->post('child_record_id');
        $edit_id = $this->input->post('edit_id');
        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);
        /// Update record into child DB
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            if (!empty($child_record_id)) {
                $cdata['id'] = $child_record_id;
                $this->carousels_master_model->update_record($cdata, $domain_result[0]['db_name']);
            }
        }
        $msg = $this->lang->line('common_publish_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('child_carousels_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for Delete carousels
      @Author     : Sanjay Moghariya
      @Input      : carousels id
      @Output     : delete data
      @Date       : 01-05-2015
     */

    public function carousels_delete_all() {
        $id = $this->input->post('single_remove_id');
        $child_record_id = $this->input->post('child_record_id');
        $edit_id = $this->input->post('edit_id');
        $array_data = $this->input->post('myarray');
        $delete_all_flag = 0;
        $cnt = 0;
        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);
        if (!empty($id)) {
            $data['id'] = $id;
            $this->carousels_master_model->delete_record($data);
            $this->carousels_master_model->delete_record($data, 'child_website_carousels_property_type_trans');
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                $data['id'] = $child_record_id;
                $this->carousels_master_model->delete_record($data, '', $domain_result[0]['db_name']);
                $this->carousels_master_model->delete_record($data, 'child_website_carousels_property_type_trans', $domain_result[0]['db_name']);
            }
            unset($id);
        } elseif (!empty($array_data)) {
            $delete_all_flag = 1;
            for ($i = 0; $i < count($array_data); $i++) {
                $id_arr = explode('@', $array_data[$i]);
                $id = !empty($id_arr[0]) ? $id_arr[0] : 0;
                $child_record_id = !empty($id_arr[1]) ? $id_arr[1] : 0;

                $cnt++;
                $data['id'] = $id;
                $this->carousels_master_model->delete_record($data);
                $this->carousels_master_model->delete_record($data, 'child_website_carousels_property_type_trans');
                if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                    $data['id'] = $child_record_id;
                    $this->carousels_master_model->delete_record($data, '', $domain_result[0]['db_name']);
                    $this->carousels_master_model->delete_record($data, 'child_website_carousels_property_type_trans', $domain_result[0]['db_name']);
                }

                unset($datalist);
            }
        }
        $newdata = array('msg' => $this->lang->line('common_delete_success_msg'));
        $this->session->set_userdata('message_session', $newdata);
        $searchsort_session = $this->session->userdata('child_carousels_sortsearchpage_data');
        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage']) ? $searchsort_session['perpage'] : '10';
        $total_rows = $searchsort_session['total_rows'];
        if ($delete_all_flag == 1) {
            $total_rows -= $cnt;
            if ($pagingid * $perpage > $total_rows) {
                if ($total_rows % $perpage == 0) {
                    $pagingid -= $perpage;
                }
            }
        } else {
            if ($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if ($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;
    }

    /*
      @Description: Function for check carousels name exists or not
      @Author     : Sanjay Moghariya
      @Input      :
      @Output     :
      @Date       : 05-05-2015
     */

    public function check_carousels_name() {
        $edit_id = $this->input->post('edit_id');
        $domain_id = $this->get_domain_id($edit_id);
        $id = $this->input->post('id');

        $name = mysql_real_escape_string($this->input->post('name'));
        $fields = array('id');
        if (!empty($id)) {
            //Edit time
            $and_match = array('id' => $id);
        }
        if (!empty($name)) {
            if (!empty($domain_id))
                $match = array('carousels_name' => $name, 'domain_id' => $domain_id);
            else
                $match = array('carousels_name' => $name);

            $exist_name = $this->carousels_master_model->select_records($fields, $match, '', '=', '', '', '', '', '', '', '', '', !empty($and_match) ? $and_match : '');
            //echo $this->db->last_query();
        }
        if (!empty($exist_name))
            echo '1';
        else
            echo '0';
    }

    /*
      @Description: Function for Insert nearby area data
      @Author	: Sanjay Moghariya
      @Input	: area data
      @Output	: Insert record
      @Date	: 04-05-2015
     */

    public function nearbyarea() {
        $edit_id = $this->input->post('edit_id');
        $domain_id = $this->get_domain_id($edit_id);
        if (!empty($domain_id))
            $match = array('cwna.child_admin_id' => $edit_id, 'cwna.domain_id' => $domain_id);
        else
            $match = array('cwna.child_admin_id' => $edit_id);
        $table = "child_website_nearby_area as cwna";
        $fields = array('cwna.id,cwna.location_text,cwna.order_of_display,cwna.location_url,cwna.child_record_id');
        $cdata['nearby_arealist'] = $this->child_nearby_area_model->getmultiple_tables_records($table, $fields, '', '', '', $match, '=', '', '', 'id', 'desc');
        $cdata['editarea_id'] = $edit_id;
        $this->load->view($this->user_type . '/' . $this->viewName . '/nearby_area_list', $cdata);
    }

    /*
      @Description: Function for Insert nearby area data
      @Author	: Sanjay Moghariya
      @Input	: area data
      @Output	: Insert record
      @Date	: 04-05-2015
     */

    public function insert_nearbyarea() {
        $contacttab = $this->input->post('contacttab');
        //$id = $this->input->post('id');
        $edit_id = $this->input->post('edit_id');

        /////// Domain id
        $cdata['domain_id'] = $this->get_domain_id($edit_id);
        ///////
        $cdata['child_admin_id'] = $edit_id;
        //pr($_POST);exit;
        $location_text = $this->input->post('location_text');
        $location_url = $this->input->post('location_url');
        $order_of_display = $this->input->post('order_of_display');

        $cdata['created_by'] = $this->superadmin_session['id'];
        $cdata['created_date'] = date('Y-m-d H:i:s');
        $cdata['status'] = '1';

        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);

        if (!empty($location_text) && count($location_text) > 0) {
            foreach ($location_text as $key => $value) {
                if (!empty($location_text[$key]) && !empty($location_url[$key])) {
                    $cdata['location_text'] = $location_text[$key];
                    $cdata['location_url'] = $location_url[$key];
                    $cdata['order_of_display'] = $order_of_display[$key];
                    $last_id = '';
                    /// Insert record into child DB
                    if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                        $last_id = $this->child_nearby_area_model->insert_record($cdata, $domain_result[0]['db_name']);
                    }
                    if (!empty($last_id))
                        $cdata['child_record_id'] = $last_id;

                    /// Insert record into parent DB
                    $this->child_nearby_area_model->insert_record($cdata);
                }
            }
        }

        // update already added record
        $location_text_update = $this->input->post('location_text_update');
        $location_url_update = $this->input->post('location_url_update');
        $order_update = $this->input->post('order_update');
        $update_id = $this->input->post('location_idd');
        $child_record_id = $this->input->post('child_record_id');

        $udata['modified_by'] = $this->superadmin_session['id'];
        $udata['modified_date'] = date('Y-m-d H:i:s');
        for ($u = 0; $u < count($update_id); $u++) {
            $udata['location_text'] = $location_text_update[$u];
            $udata['location_url'] = $location_url_update[$u];
            $udata['order_of_display'] = $order_update[$u];
            $udata['id'] = $update_id[$u];
            $this->child_nearby_area_model->update_record($udata);

            /// Update record into child DB
            if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
                if (!empty($child_record_id[$u])) {
                    $udata['id'] = $child_record_id[$u];
                    $this->child_nearby_area_model->update_record($udata, $domain_result[0]['db_name']);
                }
            }
            //echo $this->db->last_query();
        }
        //exit;
        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);

        $redirecttype = $this->input->post('submitbtn');
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        if (($redirecttype == 'Save')) {
            redirect(base_url('superadmin/' . $this->viewName . '/' . $pagingid));
        } else {
            redirect('superadmin/' . $this->viewName . '/edit_record/' . $edit_id . '/' . ($contacttab + 1));
        }
    }

    /*
      @Description: Function for update nearby area data
      @Author	: Sanjay Moghariya
      @Input	: area data
      @Output	: Update record
      @Date	: 04-05-2015
     */

    public function update_nearbyarea() {
        $cdata['id'] = $this->input->post('id');
        $cdata['location_text'] = $this->input->post('location_text');
        $cdata['location_url'] = $this->input->post('location_url');
        $cdata['order_of_display'] = $this->input->post('order_of_display');
        $cdata['modified_by'] = $this->superadmin_session['id'];
        $cdata['modified_date'] = date('Y-m-d H:i:s');
        $edit_id = $this->input->post('edit_id');

        $this->child_nearby_area_model->update_record($cdata);

        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            $cdata['id'] = $this->input->post('child_record_id');
            $this->child_nearby_area_model->update_record($cdata, $domain_result[0]['db_name']);
        }
        $msg = $this->lang->line('common_edit_success_msg');
    }

    /*
      @Description: Function for delete nearby area
      @Author	: Sanjay Moghariya
      @Input	: id
      @Output	: Delete record
      @Date	: 04-05-2015
     */

    public function nearby_area_delete() {
        $id = $this->input->post('single_remove_id');
        $child_record_id = $this->input->post('child_record_id');
        $this->child_nearby_area_model->delete_record($id);
        $edit_id = $this->input->post('edit_id');
        // Get Child db name
        $domain_result = $this->get_child_dbname($edit_id);
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            $this->child_nearby_area_model->delete_record($child_record_id, $domain_result[0]['db_name']);
            echo $this->db->last_query();
        }

        $msg = $this->lang->line('common_delete_success_msg');
        echo $msg;
    }

    /*
      @Description: Function for get admin database name
      @Author	: Sanjay Moghariya
      @Input	: Admin id
      @Output	: Get Database name
      @Date	: 09-05-2015
     */

    public function get_child_dbname($admin_id) {
        // Get Child db name
        $table = "child_admin_website as caw";
        $fields = array('caw.id,caw.lw_admin_id,caw.domain,lm.db_name');
        $join_tables = array('login_master as lm' => 'lm.id = caw.lw_admin_id');
        $match = array('caw.id' => $admin_id);
        $domain_result = $this->carousels_master_model->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $match, '=');
        return $domain_result;
    }

    /*
      @Description: Function to insert footer management data
      @Author : Nishant Rathod
      @Input  : Footer Data
      @Output : Insert Record
      @Date   : 19-05-2015
     */

    public function insert_footer() {
        $contacttab = $this->input->post('contacttab');
        $id = $this->input->post('id');
        $tab6_data['id'] = $id;
        $tab6_data['footer_mls_disclaimer'] = $this->input->post('footer_mls_disclaimer');
        $tab6_data['copyright_statement'] = htmlentities($this->input->post('copyright_statement'));
        $tab6_data['modified_date'] = date('Y-m-d H:i:s');
        $tab6_data['modified_by'] = $this->superadmin_session['id'];
        $this->obj->update_record($tab6_data);

        $domain_result = $this->get_child_dbname($id);
        $domain_id = $this->get_domain_id($id);
        if (!empty($domain_result) && !empty($domain_result[0]['db_name'])) {
            $child6_data['id'] = $domain_id;
            $child6_data['footer_mls_disclaimer'] = $this->input->post('footer_mls_disclaimer');
            $child6_data['copyright_statement'] = htmlentities($this->input->post('copyright_statement'));
            $this->lead_users_model->domain_update_record($child6_data, $domain_result[0]['db_name']);
        }

        unset($tab6_data);
        $tab6_data['link_1'] = $this->input->post('link_1');
        if (!empty($tab6_data['link_1'])) {
            $tab6_data['page_type_1'] = $this->input->post('page_type_1');
            if ($tab6_data['page_type_1'] == 0) {
                $tab6_data['page_1'] = $this->input->post('page_1');
                $tab6_data['url_1'] = '';
            } else {
                $tab6_data['url_1'] = $this->input->post('url_1');
                $tab6_data['page_1'] = '';
            }
        } else {
            $tab6_data['page_type_1'] = '';
            $tab6_data['page_1'] = '';
            $tab6_data['url_1'] = '';
        }
        $tab6_data['link_2'] = $this->input->post('link_2');
        if (!empty($tab6_data['link_2'])) {
            $tab6_data['page_type_2'] = $this->input->post('page_type_2');
            if ($tab6_data['page_type_2'] == 0) {
                $tab6_data['page_2'] = $this->input->post('page_2');
                $tab6_data['url_2'] = '';
            } else {
                $tab6_data['url_2'] = $this->input->post('url_2');
                $tab6_data['page_2'] = '';
            }
        } else {
            $tab6_data['page_type_2'] = '';
            $tab6_data['page_2'] = '';
            $tab6_data['url_2'] = '';
        }
        $tab6_data['link_3'] = $this->input->post('link_3');
        if (!empty($tab6_data['link_3'])) {
            $tab6_data['page_type_3'] = $this->input->post('page_type_3');
            if ($tab6_data['page_type_3'] == 0) {
                $tab6_data['page_3'] = $this->input->post('page_3');
                $tab6_data['url_3'] = '';
            } else {
                $tab6_data['url_3'] = $this->input->post('url_3');
                $tab6_data['page_3'] = '';
            }
        } else {
            $tab6_data['page_type_3'] = '';
            $tab6_data['page_3'] = '';
            $tab6_data['url_3'] = '';
        }
        $tab6_data['domain_id'] = $domain_id;
        $tab6_data['superadmin_domain_id'] = $id;
        // pr($_POST);
        // pr($tab6_data); die;
        $footer_id = $this->input->post('footer_id');
        if (empty($footer_id)) {
            $tab6_data['created_by'] = $this->superadmin_session['id'];
            $tab6_data['created_date'] = date('Y-m-d H:i:s');
            $tab6_data['status'] = '1';
            if (!empty($domain_result[0]['db_name'])) {
                $child_record_id = $this->child_footer_links_model->insert_record($tab6_data, $domain_result[0]['db_name']);
            }
            if (!empty($child_record_id)) {
                $tab6_data['child_record_id'] = $child_record_id;
            }
            $child_record_id = $this->child_footer_links_model->insert_record($tab6_data);
        } else {
            $tab6_data['id'] = $footer_id;
            $tab6_data['modified_by'] = $this->superadmin_session['id'];
            $tab6_data['modified_date'] = date('Y-m-d H:i:s');
            $this->child_footer_links_model->update_record($tab6_data);

            $child_record_id = $this->input->post('child_record_id');
            if (!empty($child_record_id)) {
                $tab6_data['id'] = $child_record_id;
                $this->child_footer_links_model->update_record($tab6_data, $domain_result[0]['db_name']);
            }
        }

        /// Update record into child DB
        // if(!empty($domain_result) && !empty($domain_result[0]['db_name']))
        // {
        //     $child6_data['id'] = $edited_domain_id;
        //     $child6_data['footer_mls_disclaimer'] = $this->input->post('footer_mls_disclaimer');
        //     $child6_data['copyright_statement'] = $this->input->post('copyright_statement');
        //     $this->lead_users_model->domain_update_record($child6_data,$domain_result[0]['db_name']);
        // }

        $msg = $this->lang->line('common_add_success_msg');
        $newdata = array('msg' => $msg);
        $this->session->set_userdata('message_session', $newdata);

        $redirecttype = $this->input->post('submitbtn');
        $searchsort_session = $this->session->userdata('child_admin_sortsearchpage_data');
        $pagingid = $searchsort_session['uri_segment'];
        if (($redirecttype == 'Save')) {
            redirect(base_url('superadmin/' . $this->viewName . '/' . $pagingid));
        } else {
            redirect('superadmin/' . $this->viewName . '/edit_record/' . $id . '/' . ($contacttab + 1));
        }
    }

    /*
      @Description: Function to fetch agents
      @Author     : Nishant Rathod
      @Input      :
      @Output     : Agents List from login_master and user_master
      @Date       : 13-05-2015
     */

    public function fetch_agents($db_name, $domain_id) {
        //$login_master_data = $this->agent_team_model->select_records('login_master','','','','','','','','','','',$db_name);
        $table = $db_name . '.login_master lm';
        $fiedls = array('lm.id,lm.mls_user_id,lm.email_id,lm.agent_type,lm.user_type,lm.admin_name,lm.phone,lm.admin_pic,lm.created_by,lm.created_date,CONCAT_WS(" ",um.first_name,um.last_name) as user_name,um.contact_pic,um.company_post,um.notes');
        $join_tables = array($db_name . '.user_master um' => 'um.id = lm.user_id');
        $login_master_data = $this->agent_team_model->getmultiple_tables_records($table, $fiedls, $join_tables, 'left');
        $flag = 0;
        foreach ($login_master_data as $key => $value) {
            $cdata['login_id'] = $value['id'];
            $cdata['mls_user_id'] = $value['mls_user_id'];
            $cdata['agent_email_id'] = $value['email_id'];
            if ($value['agent_type'] == 'Lender' && $flag == 0) {
                $cdata['is_lender'] = 1;
                $flag = 1;
            } else
                $cdata['is_lender'] = 0;
            if ($value['user_type'] == 2 || $value['user_type'] == 5) {
                if ($value['user_type'] == 2) {
                    $cdata['is_admin'] = 1;
                    $cdata['phone_number'] = $value['phone'];
                }
                $cdata['agent_name'] = $value['admin_name'];
                if (!empty($value['admin_pic'])) {
                    $cdata['agent_pic'] = $value['admin_pic'];
                    copy($this->config->item('admin_big_img_path') . $value['admin_pic'], $this->config->item('child_big_img_base_path') . $cdata['agent_pic']);
                    copy($this->config->item('admin_small_img_path') . $value['admin_pic'], $this->config->item('child_small_img_base_path') . $cdata['agent_pic']);
                }
            } else if ($value['user_type'] == 3 || $value['user_type'] == 4) {
                $cdata['agent_name'] = $value['user_name'];
                if (!empty($value['contact_pic'])) {
                    $cdata['agent_pic'] = $value['contact_pic'];
                    copy($this->config->item('user_big_img_path') . $cdata['agent_pic'], $this->config->item('child_big_img_base_path') . $cdata['agent_pic']);
                    copy($this->config->item('user_small_img_path') . $cdata['agent_pic'], $this->config->item('child_small_img_base_path') . $cdata['agent_pic']);
                }
                $cdata['title'] = (!empty($value['company_post']))?$value['company_post']:'';
                $cdata['agent_description'] = (!empty($value['notes']))?$value['notes']:'';
            }
            $cdata['is_team_leader'] = 0;
            $cdata['show_on_web'] = 0;
            $cdata['domain_id'] = $domain_id;
            $cdata['created_by'] = $value['created_by'];
            $cdata['created_date'] = $value['created_date'];
            $cdata['status'] = 1;
            $this->agent_team_model->insert_record($cdata, $db_name);
            unset($cdata);
        }
        return 1;
    }

    public function create_cms($db_name, $domain_id, $superadmin_domain_id) {
        $cms_data['title'] = 'Terms of Use';
        $cms_data['page_type'] = '1';
        $cms_data['slug'] = 'terms-of-use';
        $cms_data['description'] = '';
        $cms_data['page_position'] = '2';
        $cms_data['domain_id'] = $domain_id;
        $cms_data['superadmin_domain_id'] = $superadmin_domain_id;
        $cms_data['created_by'] = $superadmin_domain_id;
        $cms_data['created_date'] = date('Y-m-d H:i:s');
        $cms_data['status'] = '1';
        $this->cms_model->insert_record($cms_data, $db_name);

        unset($cms_data);
        $cms_data['title'] = 'Privacy Policy';
        $cms_data['page_type'] = '1';
        $cms_data['slug'] = 'privacy-policy';
        $cms_data['description'] = '';
        $cms_data['page_position'] = '2';
        $cms_data['domain_id'] = $domain_id;
        $cms_data['superadmin_domain_id'] = $superadmin_domain_id;
        $cms_data['created_by'] = $superadmin_domain_id;
        $cms_data['created_date'] = date('Y-m-d H:i:s');
        $cms_data['status'] = '1';
        $this->cms_model->insert_record($cms_data, $db_name);

        unset($cms_data);
        $cms_data['title'] = 'Digital Millennium Copyright Act';
        $cms_data['page_type'] = '1';
        $cms_data['slug'] = 'dmca';
        $cms_data['description'] = '';
        $cms_data['page_position'] = '2';
        $cms_data['domain_id'] = $domain_id;
        $cms_data['superadmin_domain_id'] = $superadmin_domain_id;
        $cms_data['created_by'] = $superadmin_domain_id;
        $cms_data['created_date'] = date('Y-m-d H:i:s');
        $cms_data['status'] = '1';
        $this->cms_model->insert_record($cms_data, $db_name);

        unset($cms_data);
        $cms_data['title'] = 'About us';
        $cms_data['page_type'] = '1';
        $cms_data['slug'] = 'about-us';
        $cms_data['description'] = '';
        $cms_data['page_position'] = '1';
        $cms_data['domain_id'] = $domain_id;
        $cms_data['superadmin_domain_id'] = $superadmin_domain_id;
        $cms_data['created_by'] = $superadmin_domain_id;
        $cms_data['created_date'] = date('Y-m-d H:i:s');
        $cms_data['status'] = '1';
        $this->cms_model->insert_record($cms_data, $db_name);

        unset($cms_data);
        $cms_data['title'] = 'Contact us';
        $cms_data['page_type'] = '1';
        $cms_data['slug'] = 'contact-us';
        $cms_data['description'] = '';
        $cms_data['page_position'] = '1';
        $cms_data['domain_id'] = $domain_id;
        $cms_data['superadmin_domain_id'] = $superadmin_domain_id;
        $cms_data['created_by'] = $superadmin_domain_id;
        $cms_data['created_date'] = date('Y-m-d H:i:s');
        $cms_data['status'] = '1';
        $this->cms_model->insert_record($cms_data, $db_name);

        return 1;
    }

    public function delete_image() {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $fields = array("id,$name");
        $match = array('id' => $id);
        $result = $this->obj->get_user('', $match, '', '=');

        $bgImgPath = $this->config->item('admin_big_img_path');
        $smallImgPath = $this->config->item('admin_small_img_path');
        $image = $result[0][$name];

        $bgImgPathUpload = $this->config->item('upload_image_file_path') . 'admin/big/';
        $smallImgPathUpload = $this->config->item('upload_image_file_path') . 'admin/small/';
        if (file_exists($bgImgPathUpload . $image) || file_exists($smallImgPathUpload . $image)) {

            @unlink($bgImgPath . $image);
            @unlink($smallImgPath . $image);
        }
        $cdata['id'] = $id;
        $cdata[$name] = '';
        $this->obj->update_record($cdata);

        // $match = array('id'=>$id);
        // $parent_login = $this->admin_model->get_user('',$match,'','=');
        // //pr($parent_login);exit;
        // if(!empty($parent_login[0]['email_id']) && !empty($parent_login[0]['db_name']))
        // {
        //     $update_parent_data['email_id'] = $parent_login[0]['email_id'];
        //     $update_parent_data['admin_pic']= $parent_login[0]['admin_pic'];
        //     $update_parent_data['modified_date'] = $parent_login[0]['modified_date'];
        //     $childdb = $parent_login[0]['db_name'];
        //     $lastId = $this->obj->update_child_user_record($childdb,$update_parent_data);
        // }

        echo 'done';
    }

    public function check_carousel_valid() {
        $parent_db = $this->config->item('parent_db_name');
        $table = $parent_db . '.mls_property_list_master mplm';
        $join_tables = array();
        $wherestring = '(';
        $match = array('mplm.status' => 1, 'mls_id' => !empty($this->mls_id) ? $this->mls_id : 0); //array('mplm.ST'=>'A');
        $fields = array('mplm.ID');

        $only_views = $this->input->post('only_views');
        if (!empty($only_views)) // Only Views
            $wherestring .= 'mplm.VEW != "" AND ';

        $only_shortsale = $this->input->post('only_shortsale');
        if (!empty($only_shortsale)) // Only Short Sale
            $wherestring .= 'mplm.PARQ = "C" AND ';

        $only_new_construction = $this->input->post('only_new_construction');
        if (!empty($only_new_construction)) // Only New Construcation
            $wherestring .= 'mplm.newConstruction = "Y" AND ';

        $only_forclosures = $this->input->post('only_forclosures');
        if (!empty($only_forclosures)) // Only Forclosures
            $wherestring .= 'mplm.BREO != "" AND ';

        $only_waterfront = $this->input->post('only_waterfront');
        if (!empty($only_waterfront)) // Only Waterfront
            $wherestring .= 'mplm.WFT != "" AND ';

        // Min Price , Max Price
        $min_price = $this->input->post('min_price');
        $max_price = $this->input->post('max_price');
        if (!empty($min_price) && !empty($max_price))
            $wherestring .= '(mplm.display_price >=' . $min_price . ' AND mplm.display_price <=' . $max_price . ') AND ';
        else if (!empty($min_price) && empty($max_price))
            $wherestring .= 'mplm.display_price >=' . $min_price . ' AND ';
        else if (empty($min_price) && !empty($max_price))
            $wherestring .= 'mplm.display_price <=' . $max_price . ' AND ';

        $location_filter = $this->input->post('location_filter');
        if (!empty($location_filter)) {
            $county = $this->input->post('county');
            if (!empty($county))
                $wherestring .= 'mplm.COU = "' . $county . '" AND ';  // County

            $city = $this->input->post('city');
            if (!empty($city))
                $wherestring .= 'mplm.CIT = "' . $city . '" AND ';  // City

            $community_name = $this->input->post('community_name');
            if (!empty($community_name))
                $wherestring .= 'mplm.DSR = "' . $community_name . '" AND ';  // Community Name

            $zipcode = $this->input->post('zipcode');
            if (!empty($zipcode))
                $wherestring .= 'mplm.ZIP = "' . $zipcode . '" AND ';  // Zip code
        }

        // Property Type
        $property_type = $this->input->post('property_type');
        if (!empty($property_type)) {
            $ptype = explode(',', $property_type);
            $ptyp = '';
            if (!empty($ptype) && count($ptype) > 0) {
                foreach ($ptype as $pt) {
                    if (!empty($pt)) {
                        $ptyp .= '"' . $pt . '",';
                    }
                }
                $ptyp = trim($ptyp, ',');
            }
            if (!empty($ptyp))
                $wherestring .= 'mplm.PTYP in(' . $ptyp . ') AND ';
        }
        $wherestring = trim($wherestring, 'AND ');
        $wherestring .= ')';
        if (!empty($wherestring) && $wherestring == '()') {
            $wherestring = '';
        }
        //$field_list = $this->db->list_fields($parent_db . '.mls_property_list_master');

        //custom_db_fields
        $custom_db_fields = $this->input->post('custom_db_fields');
        if (!empty($custom_db_fields)) {
            $custome_fields = ' (' . $custom_db_fields . ')';
            if (!empty($wherestring))
                $wherestring .= ' AND ' . $custome_fields;
            else
                $wherestring .= $custome_fields;
        }

        $sortby = 'desc';
        $sortfield = 'mplm.ID';

        try {
            $property_list = $this->carousels_master_model->getmultiple_tables_records($table, $fields, $join_tables, 'left', '', $match, '=', '6', '', $sortfield, $sortby, '', $wherestring);
            echo 1;
        } catch (Exception $e) {
            $this->db->ar_select = array();
            $this->db->ar_from = array();
            $this->db->ar_join = array();
            $this->db->ar_where = array();
            $this->db->ar_like = array();
            $this->db->ar_groupby = array();
            $this->db->ar_having = array();
            $this->db->ar_orderby = array();
            $this->db->ar_wherein = array();
            $this->db->ar_aliased_tables = array();
            $this->db->ar_no_escape = array();
            $this->db->ar_distinct = FALSE;
            $this->db->ar_limit = FALSE;
            $this->db->ar_offset = FALSE;
            $this->db->ar_order = FALSE;
            echo 2;
        }
    }

}
