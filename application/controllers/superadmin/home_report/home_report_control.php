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

class home_report_control extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('home_report_model');
        $this->obj = $this->home_report_model;
    }

    public function insert_mls()
    {
        // check if there is any mls entry in the db
        $mls_exist_data = $this->obj->select_query('id', 'home_report_mls', array('date( created_on ) = ' => date('Y-m-d')), 1);

        if (empty($mls_exist_data))
        {
            //---- fetch all the mls ids and insert into the temp table
            $data_mls = array(
                'insert_table' => 'home_report_mls',
                'select_table' => 'mls_master',
                'insert_columns' => 'mls_id,zipcode_status,neighbourhood_status,home_sold_zip,home_sold_city,city_status,created_on',
                'select_columns' => 'id,' . REPORT_MLS_PENDING . ',' . REPORT_MLS_PENDING . ',' . REPORT_MLS_PENDING . ',' . REPORT_MLS_PENDING . ',' . REPORT_MLS_PENDING . ',"' . date('Y-m-d H:i:s') . '"',
                'where' => ' status = 1 ',
            );
            $this->obj->insert_select($data_mls);
        }
    }

    /*
      @Description: Function for store the data for the seattle home report. Cron which will run daily
      @Author: Ishita Thaker
      @Input: -
      @Output: - Average values
      @Date: 25-05-2015
     */

    public function seattle_report()
    {

        $this->insert_mls();

        $check_data = array(
            0 => array('temp_table' => 'home_report_zip_code', 'select_fld' => 'ZIP', 'insert_fld' => 'zip_code', 'mls_flag' => 'zipcode_status'),
                //    1 => array('temp_table' => 'home_report_city_code', 'select_fld' => 'CIT', 'insert_fld' => 'city', 'mls_flag' => 'city_status'),
        );
        foreach ($check_data as $v)
        {
            $mls_temp_data = $this->obj->select_query('mls_id,id', 'home_report_mls', array($v['mls_flag'] => REPORT_MLS_PENDING));

            foreach ($mls_temp_data as $m)
            {
                $exist_data = $this->obj->select_query('id', $v['temp_table'], array('mls_id' => $m['mls_id'], 'date( created_on ) = ' => date('Y-m-d')), 1);  // check if the zip[ code exist in the temp table

                if (empty($exist_data))
                {
                    $unique_property = $this->obj->select_query('DISTINCT PTYP as property_type', 'mls_property_list_master', array('status' => 1), '', '', FALSE);

                    foreach ($unique_property as $p)
                    {
                        $data_mls = array(
                            'insert_table' => $v['temp_table'],
                            'select_table' => 'mls_property_list_master',
                            'insert_columns' => $v['insert_fld'] . ',property_type,mls_id,status,created_on',
                            'select_columns' => 'DISTINCT ' . $v['select_fld'] . ',"' . $p['property_type'] . '",' . $m['mls_id'] . ',' . REPORT_MLS_PENDING . ',"' . date('Y-m-d H:i:s') . '"',
                            'where' => ' mls_id =  ' . $m['mls_id'] . ' and ' . $v['select_fld'] . ' <> 0 and PTYP = "' . $p['property_type'] . '"',
                        );
                        $this->obj->insert_select($data_mls);
                    }
                }
                $temp_data = $this->obj->select_query('id,mls_id,property_type,' . $v['insert_fld'], $v['temp_table'], array('status' => REPORT_MLS_PENDING, 'mls_id' => $m['mls_id']));

                if (!empty($temp_data))
                {
                    foreach ($temp_data as $z)
                    {
                        $total_listing = $this->obj->select_query('COUNT(ID) as count', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1));
                        $avg_price = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'display_price <>' => 0));
                        $avg_bath = $this->obj->select_query('AVG(BTH) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BTH <> ' => 0));
                        $avg_bed = $this->obj->select_query('AVG(BR) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR <> ' => 0));
                        $avg_1bed = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR' => 1, 'display_price <>' => 0));
                        $avg_2bed = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR' => 2, 'display_price <>' => 0));
                        $avg_3bed = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR' => 3, 'display_price <>' => 0));
                        $avg_4bed = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR' => 4, 'display_price <>' => 0));
                        $avg_5bed = $this->obj->select_query('AVG(display_price) as avg', 'mls_property_list_master', array('mls_id' => $z['mls_id'], $v['select_fld'] => $z[$v['insert_fld']], 'PTYP' => $z['property_type'], 'status' => 1, 'BR' => 5, 'display_price <>' => 0));
                        $median = $this->obj->find_median('mls_id = ' . $z['mls_id'] . ' and ' . $v['select_fld'] . ' = "' . $z[$v['insert_fld']] . '" and status = 1 and display_price <> 0 and PTYP = "' . $z['property_type'] . '"', 'mls_property_list_master');

                        $update_data = array(
                            'total_listing' => $total_listing[0]['count'],
                            'average_price' => $avg_price[0]['avg'],
                            'average_bath' => $avg_bath[0]['avg'],
                            'average_bed' => $avg_bed[0]['avg'],
                            'avg_price_1bed' => $avg_1bed[0]['avg'],
                            'avg_price_2bed' => $avg_2bed[0]['avg'],
                            'avg_price_3bed' => $avg_3bed[0]['avg'],
                            'avg_price_4bed' => $avg_4bed[0]['avg'],
                            'avg_price_5bed' => $avg_5bed[0]['avg'],
                            'median_price' => $median['median'],
                            'id' => $z['id'],
                            'status' => REPORT_MLS_COMPLETED,
                            'modified_on' => date('Y-m-d H:i:s'),
                        );

                        $this->obj->update_record($update_data, $v['temp_table']);
                    }
                }

                $update_data = array('id' => $m['id'], $v['mls_flag'] => REPORT_MLS_COMPLETED, 'modified_on' => date('Y-m-d H:i:s'));
                $this->obj->update_record($update_data, 'home_report_mls');
            }
        }
        echo "Done :)";
    }

    public function sold_location()
    {
    	ini_set('display_errors', 1);
    	error_reporting(E_ALL);
    	
        $this->insert_mls();

        $check_data = array(
            0 => array('temp_table' => 'sold_property_by_zip', 'select_fld' => 'ZIP', 'insert_fld' => 'zip_code', 'mls_flag' => 'home_sold_zip', 'status_table' => 'sold_property_zip_status'),
                //     1 => array('temp_table' => 'sold_property_by_city', 'select_fld' => 'CIT', 'insert_fld' => 'city', 'mls_flag' => 'home_sold_city', 'status_table' => 'sold_property_city_status'),
        );

        foreach ($check_data as $v)
        {
            $mls_temp_data = $this->obj->select_query('mls_id,id', 'home_report_mls', array($v['mls_flag'] => REPORT_MLS_PENDING));

            foreach ($mls_temp_data as $m)
            {
                $exist_data = $this->obj->select_query('id', $v['status_table'], array('mls_id' => $m['mls_id'], 'date( created_on ) = ' => date('Y-m-d')), 1);
                if (empty($exist_data))
                {
                    $unique_property = $this->obj->select_query('DISTINCT PTYP as property_type', 'mls_property_list_master', array('status' => 1), '', '', FALSE);

                    foreach ($unique_property as $p)
                    {
                        $data_mls = array(
                            'insert_table' => $v['status_table'],
                            'select_table' => 'mls_property_list_master',
                            'insert_columns' => $v['insert_fld'] . ',property_type,mls_id,status,created_on',
                            'select_columns' => 'DISTINCT ' . $v['select_fld'] . ',"' . $p['property_type'] . '",' . $m['mls_id'] . ',' . REPORT_MLS_PENDING . ',"' . date('Y-m-d H:i:s') . '"',
                            'where' => ' mls_id =  ' . $m['mls_id'] . ' and ' . $v['select_fld'] . ' <> 0 and PTYP = "' . $p['property_type'] . '"',
                        );
                        $this->obj->insert_select($data_mls);
                    }
                }

                $temp_data = $this->obj->select_query('id,status,mls_id,property_type,' . $v['insert_fld'], $v['status_table'], array('status !=' => HOME_SOLD_COMPLETE, 'mls_id' => $m['mls_id']));
                if (!empty($temp_data))
                {
                    foreach ($temp_data as $z)
                    {
                        $already_ids_str = "";
                        if ($z['status'] == HOME_SOLD_WORKING)
                        {
                            $already_inserted_data = $this->obj->select_query('property_id', $v['temp_table'], array($v['insert_fld'] => $z[$v['insert_fld']], 'property_type' => $z['property_type'], 'mls_id' => $m['mls_id']));
                            $already_ids = array();
                            foreach ($already_inserted_data as $a)
                            {
                                array_push($already_ids, $a['property_id']);
                            }
                            if (!empty($already_ids))
                                $already_ids_str = ' and ID NOT IN (' . implode(',', $already_ids) . ')';
                        }
                        else
                        {
                            $update_status_working = array('id' => $z['id'], 'status' => HOME_SOLD_WORKING, 'modified_on' => date('Y-m-d H:i:s'));
                            $this->obj->update_record($update_status_working, $v['status_table']);
                        }
                        
                        $data_mls = array(
                            'insert_table' => $v['temp_table'],
                            'select_table' => 'mls_property_list_master',
                            'insert_columns' => 'mls_id,property_id,property_type,' . $v['insert_fld'] . ',created_on,sold_date,address,price,price_per_sqft',
                            'select_columns' => $m['mls_id'] . ',ID,"' . $z['property_type'] . '",' . $v['select_fld'] . ',"' . date('Y-m-d H:i:s') . '",CLO,full_address,display_price,display_price DIV ASF as sqft',
                            'where' => ' mls_id =  ' . $m['mls_id'] . " and " . $v['select_fld'] . " = '" . $z[$v['insert_fld']] . "' and ST='S' and PTYP = '" . $z['property_type'] . "'",
                        );
                        $this->obj->insert_select($data_mls);

                        $update_status_completed = array('id' => $z['id'], 'status' => HOME_SOLD_COMPLETE, 'modified_on' => date('Y-m-d H:i:s'));
                        $this->obj->update_record($update_status_completed, $v['status_table']);
                    }
                }
                $update_data = array('id' => $m['id'], $v['mls_flag'] => REPORT_MLS_COMPLETED, 'modified_on' => date('Y-m-d H:i:s'));
                $this->obj->update_record($update_data, 'home_report_mls');
            }
        }
        echo "Done :)";
    }

    public function seattle_report_sub()
    {

        //  $this->insert_mls();

        $check_data = array(
            0 => array('temp_table' => 'home_report_zip_code', 'select_fld' => 'ZIP', 'insert_fld' => 'zip_code', 'mls_flag' => 'zipcode_status'),
            1 => array('temp_table' => 'home_report_city_code', 'select_fld' => 'CIT', 'insert_fld' => 'city', 'mls_flag' => 'city_status'),
        );
        foreach ($check_data as $v)
        {
            //$mls_temp_data = $this->obj->select_query('mls_id,id', 'home_report_mls', array($v['mls_flag'] => REPORT_MLS_PENDING)); // fetch all the ZIp
            // foreach ($mls_temp_data as $m)
            {
                $exist_data = $this->obj->select_query('id', $v['temp_table'], array('date( created_on ) = ' => date('Y-m-d')), 1);  // check if the zip[ code exist in the temp table
                if (empty($exist_data))
                {
                    $data_mls = array(
                        'insert_table' => $v['temp_table'],
                        'select_table' => $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015',
                        'insert_columns' => $v['insert_fld'] . ',status,created_on',
                        'select_columns' => 'DISTINCT ' . $v['select_fld'] . ',1,"' . date('Y-m-d H:i:s') . '"',
                        'where' => ' 1',
                    );
                    $this->obj->insert_select($data_mls);
                }
                $temp_data = $this->obj->select_query('id,' . $v['insert_fld'], $v['temp_table'], array('status' => 1)); // fetch all the ZIp

                if (!empty($temp_data))
                {
                    foreach ($temp_data as $z)
                    {

                        $total_listing = $this->obj->select_query('COUNT(ID) as count', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']]));
                        $avg_price = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']]));
                        $avg_bath = $this->obj->select_query('AVG(BTH) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BTH <> ' => 0));
                        $avg_bed = $this->obj->select_query('AVG(BR) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR <> ' => 0));
                        $avg_1bed = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR' => 1));
                        $avg_2bed = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR' => 2));
                        $avg_3bed = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR' => 3));
                        $avg_4bed = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR' => 4));
                        $avg_5bed = $this->obj->select_query('AVG(LP) as avg', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015', array($v['select_fld'] => $z[$v['insert_fld']], 'BR' => 5));
                        $median = $this->obj->find_median($v['select_fld'] . ' = "' . $z[$v['insert_fld']] . '"', $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015');
                        // echo $this->db->last_query(); 

                        $update_data = array(
                            'total_listing' => $total_listing[0]['count'],
                            'average_price' => $avg_price[0]['avg'],
                            'average_bath' => $avg_bath[0]['avg'],
                            'average_bed' => $avg_bed[0]['avg'],
                            'avg_price_1bed' => $avg_1bed[0]['avg'],
                            'avg_price_2bed' => $avg_2bed[0]['avg'],
                            'avg_price_3bed' => $avg_3bed[0]['avg'],
                            'avg_price_4bed' => $avg_4bed[0]['avg'],
                            'avg_price_5bed' => $avg_5bed[0]['avg'],
                            'median_price' => $median['median'],
                            'id' => $z['id'],
                            'status' => 2,
                            'modified_on' => date('Y-m-d H:i:s'),
                        );

                        $this->obj->update_record($update_data, $v['temp_table']);
                        //  echo $this->db->last_query(); die;
                    }
                }

                //     $update_data = array('id' => $m['id'], $v['mls_flag'] => REPORT_MLS_COMPLETED, 'modified_on' => date('Y-m-d H:i:s'));
                //   $this->obj->update_record($update_data, 'home_report_mls');
            }
        }
        echo "Done :)";
    }

    public function sold_location_sub()
    {
        // $this->insert_mls();

        $check_data = array(
            0 => array('temp_table' => 'sold_property_by_zip', 'select_fld' => 'ZIP', 'insert_fld' => 'zip_code', 'mls_flag' => 'home_sold_zip', 'status_table' => 'sold_property_zip_status'),
            1 => array('temp_table' => 'sold_property_by_city', 'select_fld' => 'CIT', 'insert_fld' => 'city', 'mls_flag' => 'home_sold_city', 'status_table' => 'sold_property_city_status'),
        );

        foreach ($check_data as $v)
        {
            // $mls_temp_data = $this->obj->select_query('mls_id,id', 'home_report_mls', array($v['mls_flag'] => REPORT_MLS_PENDING)); // fetch all the ZIp
            //foreach ($mls_temp_data as $m)
            {
                $exist_data = $this->obj->select_query('id', $v['status_table'], array('date( created_on ) = ' => date('Y-m-d')), 1);
                if (empty($exist_data))
                {
                    $data_mls = array(
                        'insert_table' => $v['status_table'],
                        'select_table' => $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015',
                        'insert_columns' => $v['insert_fld'] . ',status,created_on',
                        'select_columns' => 'DISTINCT ' . $v['select_fld'] . ',1,"' . date('Y-m-d H:i:s') . '"',
                        'where' => ' 1',
                    );
                    $this->obj->insert_select($data_mls);
                }
                $temp_data = $this->obj->select_query('id,status,' . $v['insert_fld'], $v['status_table'], array('status !=' => 3));


                if (!empty($temp_data))
                {
                    foreach ($temp_data as $z)
                    {
                        $update_data = array('id' => $z['id'], 'status' => 2, 'modified_on' => date('Y-m-d H:i:s'));
                        $this->obj->update_record($update_data, $v['status_table']);

                        $already_ids_str = "";
                        if ($z['status'] == 2)
                        {
                            $already_inserted_data = $this->obj->select_query('property_id', $v['temp_table'], array($v['insert_fld'] => $z[$v['insert_fld']]));
                            $already_ids = array();
                            foreach ($already_inserted_data as $a)
                            {
                                array_push($already_ids, $a['property_id']);
                            }
                            if (!empty($already_ids))
                                $already_ids_str = ' and ID NOT IN (' . implode(',', $already_ids) . ')';
                        }

                        // if (!empty($z[$v['select_fld']]))
                        {
                            $data_mls = array(
                                'insert_table' => $v['temp_table'],
                                'select_table' => $this->config->item('mls_staging_db') . '.mls_property_list_master_backup_28_5_2015',
                                'insert_columns' => 'property_id,' . $v['insert_fld'] . ',created_on,sold_date,address,price,price_per_sqft',
                                'select_columns' => 'ID,' . $v['select_fld'] . ',"' . date('Y-m-d H:i:s') . '",CLO,DD,LP,LP DIV ASF as sqft',
                                'where' => $v['select_fld'] . " = '" . $z[$v['insert_fld']] . "' and ST='S'" . $already_ids_str,
                            );
                            $this->obj->insert_select($data_mls);
                        }
                        $update_data = array('id' => $z['id'], 'status' => 3, 'modified_on' => date('Y-m-d H:i:s'));
                        $this->obj->update_record($update_data, $v['status_table']);
                    }
                }
                // $update_data = array('id' => $m['id'], $v['mls_flag'] => REPORT_MLS_COMPLETED, 'modified_on' => date('Y-m-d H:i:s'));
                // $this->obj->update_record($update_data, 'home_report_mls');
            }
        }
        echo "Done :)";
    }

}