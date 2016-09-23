<?php 
/*
    @Description: mls controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
*/
require './assets/cdn/vendor/autoload.php';
use OpenCloud\Rackspace;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_import_control extends CI_Controller
{   
    function __construct()
    {
        parent::__construct();
        //$this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
        //$this->message_session = $this->session->userdata('message_session');
        //check_superadmin_login();
        $this->load->model('mls_model');
        $this->obj = $this->mls_model;
        
        $this->viewName = $this->router->uri->segments[2];
        $this->user_type = 'superadmin';
        $this->mls_staging_db = $this->config->item('mls_staging_db');
        $this->mls_master_db  = $this->config->item('mls_master_db');
    }
    

    /*
    @Description: Function for Get All Envelope List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all Envelope list
    @Date: 20-02-2015
    */

    public function index()
    {   
    }
    /*
    @Description: Function for dump database
    @Author: Niral Patel
    @Input: - 
    @Output: - dump database to staging db and master db
    @Date: 25-04-2015
    */
    //Import to staging database
    function import_staging_database()
    {
        //Insert cron start data
        $field_data_cron = array(
            'cron_name'=>'import_database_start',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);
        set_time_limit(0);
        $id     = $this->uri->segment(4);
        $minute = $this->uri->segment(5);
        $hour   = $this->uri->segment(6);
        $day    = $this->uri->segment(7);
        $month  = $this->uri->segment(8);
        $match  = array('mls_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        //pr($result);exit;
        if(!empty($result))
        {
            $db_host_name     = !empty($result[0]['mls_hostname'])?$result[0]['mls_hostname']:'';
            $db_user_name     = !empty($result[0]['mls_db_username'])?$result[0]['mls_db_username']:'';
            $db_password      = !empty($result[0]['mls_db_password'])?$result[0]['mls_db_password']:'';
            $db_database_name = !empty($result[0]['mls_db_name'])?$result[0]['mls_db_name']:'';
        }
        else
        {
            $db_host_name     = "104.130.52.103";
            $db_user_name     = "asheville";
            $db_password      = "asheville";
            $db_database_name = "asheville";
        }
        try
        {
            //Get tables
            //$match1=array('mls_id',$id);
            $prefix=strtolower(substr(str_replace(' ','',$db_database_name),0,3));
            $data['mls_tables_data'] = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');
            if(!empty($data['mls_tables_data']))
            {
                $child_tables=array();
                $child_tables[]=$data['mls_tables_data'][0]['main_table'];
                for($i=1;$i<=20;$i++)
                {
                    if(!empty($data['mls_tables_data'][0]['child_table'.$i]))
                    {
                        $child_tables[]=$data['mls_tables_data'][0]['child_table'.$i];
                    }   
                }
                $tables = str_replace($db_database_name.'_','',$child_tables);
            }
            //pr($tables);exit;
            if(!empty($tables))
            {
                foreach($tables as $table_name)
                {
                    //DO NOT EDIT BELOW THIS LINE
                    //Export the database and output the status to the page
                    //$command = 'mysqldump --opt -h' .$db_host_name .' -u' .$db_user_name .' -p' .$db_password .' ' .$db_database_name .' '.$table_name.' > ' .$table_name.'.sql';
                    $table_name = str_replace($prefix.'_','',$table_name);
                    $mysqlDatabaseName = $this->config->item('mls_staging_db');
                    $mysqlHostName     = $this->config->item('mls_staging_host');
                    $mysqlUserName     = $this->config->item('mls_staging_username');
                    $mysqlPassword     = $this->config->item('mls_staging_password');
                    
                    //$command="mysqldump --host=".$db_host_name." --user=".$db_user_name." --password=".$db_password." ".$db_database_name." ".$table_name." | sed -e 's/`".$table_name."`/`".$prefix."_".$table_name."`/' | mysql --host=".$mysqlHostName." --user=".$mysqlUserName." ".$mysqlDatabaseName;
                    $command="mysqldump --host=".$db_host_name." --user=".$db_user_name." --password=".$db_password." ".$db_database_name." ".$table_name." | sed -e 's/`".$table_name."`/`".$prefix."_".$table_name."`/' | mysql --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName;
                    echo '<br>';
                    exec($command,$output=array(),$worked);
                   
                    if($worked == 0)
                    {
                        echo "Import successful ".$table_name.'<br>';
                        //$this->dump_database($table_name,$id,$minute,$hour,$day,$month);
                    }                
                }
                    //Insert cron end data
                $field_data_cron = array(
                    'cron_name'=>'import_database_end',
                    'created_date'=>date('Y-m-d H:i:s')
                );
                $this->mls_model->insert_cron_test($field_data_cron);
                //Remove cron
                /*$url    = base_url().'superadmin/mls_import/import_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
                echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;exit;
                $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                $output = shell_exec('crontab -l');
                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                   //Copy cron tab and remove string
                    $newcron = str_replace($cronjob,"",$output);
                    file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                    echo exec('crontab ../../../../tmp/crontab.txt'); 
                } 
                else 
                {
                   echo 'not found';
                }
                 */
            }
          }
          catch(DynamoDbException $e) 
          {
            //print_r($e);
          }
    }
    //Import to master database
    function import_master_database()
    {
        //Insert cron start data
        $field_data_cron = array(
            'cron_name'=>'import_database_start',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);
        set_time_limit(0);
        $id     = $this->uri->segment(4);
        $minute = $this->uri->segment(5);
        $hour   = $this->uri->segment(6);
        $day    = $this->uri->segment(7);
        $month  = $this->uri->segment(8);
        $match  = array('mls_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        //pr($result);exit;
        if(!empty($result))
        {
            $db_host_name     = !empty($result[0]['mls_hostname'])?$result[0]['mls_hostname']:'';
            $db_user_name     = !empty($result[0]['mls_db_username'])?$result[0]['mls_db_username']:'';
            $db_password      = !empty($result[0]['mls_db_password'])?$result[0]['mls_db_password']:'';
            $db_database_name = !empty($result[0]['mls_db_name'])?$result[0]['mls_db_name']:'';
        }
        else
        {
            $db_host_name     = "104.130.52.103";
            $db_user_name     = "asheville";
            $db_password      = "asheville";
            $db_database_name = "asheville";
        }
        try
        {
            //Get tables
            //$match1=array('mls_id',$id);
            $prefix=strtolower(substr(str_replace(' ','',$db_database_name),0,3));
            $data['mls_tables_data'] = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');
            
            if(!empty($data['mls_tables_data']))
            {
                $child_tables=array();
                $child_tables[]=$data['mls_tables_data'][0]['main_table'];
                for($i=1;$i<=20;$i++)
                {
                    if(!empty($data['mls_tables_data'][0]['child_table'.$i]))
                    {
                        $child_tables[]=$data['mls_tables_data'][0]['child_table'.$i];
                    }   
                }
                $tables = str_replace($db_database_name.'_','',$child_tables);
            }
            //pr($tables);exit;
            if(!empty($tables))
            {
                foreach($tables as $table_name)
                {
                    //DO NOT EDIT BELOW THIS LINE
                    //Export the database and output the status to the page
                    //$command = 'mysqldump --opt -h' .$db_host_name .' -u' .$db_user_name .' -p' .$db_password .' ' .$db_database_name .' '.$table_name.' > ' .$table_name.'.sql';
                    $table_name        = str_replace($prefix.'_','',$table_name);
                    $mysqlDatabaseName = $this->config->item('mls_master_db');
                    $mysqlHostName     = $this->config->item('mls_master_host');
                    $mysqlUserName     = $this->config->item('mls_master_username');
                    $mysqlPassword     = $this->config->item('mls_master_password');
                    
                    //$command="mysqldump --host=".$db_host_name." --user=".$db_user_name." --password=".$db_password." ".$db_database_name." ".$table_name." | sed -e 's/`".$table_name."`/`".$prefix."_".$table_name."`/' | mysql --host=".$mysqlHostName." --user=".$mysqlUserName." ".$mysqlDatabaseName;
                    $command="mysqldump --host=".$db_host_name." --user=".$db_user_name." --password=".$db_password." ".$db_database_name." ".$table_name." | sed -e 's/`".$table_name."`/`".$prefix."_".$table_name."`/' | mysql --host=".$mysqlHostName." --user=".$mysqlUserName." --password=".$mysqlPassword." ".$mysqlDatabaseName;

                    exec($command,$output=array(),$worked);
                   
                    if($worked == 0)
                    {
                        echo "Import successful ".$table_name.'<br>';
                        //$this->dump_database($table_name,$id,$minute,$hour,$day,$month);
                    }         
                }
                    //Insert cron end data
                $field_data_cron = array(
                    'cron_name'=>'import_database_end',
                    'created_date'=>date('Y-m-d H:i:s')
                );
                $this->mls_model->insert_cron_test($field_data_cron);
                 //Remove cron
                /*$url    = base_url().'superadmin/mls_import/import_database/'.$id.'/'.$minute.'/'.$hour.'/'.$day.'/'.$month;
                echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;exit;
                $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                $output = shell_exec('crontab -l');
                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                   //Copy cron tab and remove string
                    $newcron = str_replace($cronjob,"",$output);
                    file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                    echo exec('crontab ../../../../tmp/crontab.txt'); 
                } 
                else 
                {
                   echo 'not found';
                }
                 */
            }
          }
          catch(DynamoDbException $e) 
          {
            //print_r($e);
          }
    }
    function import_database()
    {
    	ini_set('display_errors', 1);
    	error_reporting(E_ALL);
        //Insert cron start data
        $field_data_cron = array(
            'cron_name'=>'import_database_start',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);
        set_time_limit(0);
        //$this->mls_model->rename_tablename('livewire_staging_database','rc_data1','ne_rc_data');
        
        $id     = $this->uri->segment(4);
        $cron_time = $this->uri->segment(5);
        $match  = array('mls_id'=>$id);
        $result = $this->obj->select_records('',$match,'','=');
        //pr($result);exit;
        if(!empty($result))
        {
            $db_host_name     = !empty($result[0]['mls_hostname'])?$result[0]['mls_hostname']:'';
            $db_user_name     = !empty($result[0]['mls_db_username'])?$result[0]['mls_db_username']:'';
            $db_password      = !empty($result[0]['mls_db_password'])?$result[0]['mls_db_password']:'';
            $db_database_name = !empty($result[0]['mls_db_name'])?$result[0]['mls_db_name']:'';

        }
        else
        {
            $db_host_name     = "104.130.52.103";
            $db_user_name     = "asheville";
            $db_password      = "asheville";
            $db_database_name = "asheville";
        }
        try
        {
            //Get tables
            //$match1=array('mls_id',$id);
            $data['mls_tables_data'] = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');

            $prefix = strtolower(substr(str_replace(' ','',$db_database_name),0,3));
            if(!empty($data['mls_tables_data']))
            {
                $child_tables=array();
                $child_tables[]=$data['mls_tables_data'][0]['main_table'];
                for($i=1;$i<=20;$i++)
                {
                    if(!empty($data['mls_tables_data'][0]['child_table'.$i]))
                    {
                        $child_tables[]=$data['mls_tables_data'][0]['child_table'.$i];
                    }   
                }
                $tables = str_replace($db_database_name.'_','',$child_tables);
            
                //pr($tables);exit;
                if(!empty($tables))
                {
                    foreach($tables as $table_name)
                    {
                        //DO NOT EDIT BELOW THIS LINE
                        //Export the database and output the status to the page
                        $table_name = str_replace($prefix.'_','',$table_name);

                        $command = 'mysqldump --opt -h' .$db_host_name .' -u' .$db_user_name .' -p' .$db_password .' ' .$db_database_name .' '.$table_name.' > ' .$table_name.'.sql';
                        exec($command,$output=array(),$worked);
                        /*var_dump($output);
                        echo $worked;
                        exit;*/
                        
                        if($worked == 0)
                        {
                            echo "Export successful : ".$table_name.'<br>';
                            $this->dump_database($table_name,$prefix,$id,$cron_time);
                        }   
                        else
                        {
                        	echo "Export command not executed.";
                        }
                    }
                }
            }
            else
            {
                echo 'Tables not assigned.';
            }
          }
          catch(DynamoDbException $e) 
          {
            //print_r($e);
          }
    }
    //Import databse to staging db

    function dump_database($table_name,$prefix,$id,$cron_time)
    {

        //ENTER THE RELEVANT INFO BELOW
        $mysqlDatabaseName = $this->config->item('mls_staging_db');
        $mysqlHostName     = $this->config->item('mls_staging_host');
        $mysqlUserName     = $this->config->item('mls_staging_username');
        $mysqlPassword     = $this->config->item('mls_staging_password');
        //echo $this->config->item('mls_documents_big_csv_path').$table_name.'.sql';
        $mysqlImportFilename =$table_name.'.sql';
        $p='';
        if(!empty($mysqlPassword))
        {$p=' -p' .$mysqlPassword.' ';}
        //DO NOT EDIT BELOW THIS LINE
        //Export the database and output the status to the page
        //echo $command='mysql -h'.$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
        
        if($this->config->item('topsin_db_conditions') || $this->config->item('livewire_db_conditions'))
        {   $command="mysql -h {$mysqlHostName} -u '{$mysqlUserName}' -p'{$mysqlPassword}' '{$mysqlDatabaseName}' < '{$mysqlImportFilename}'";
            $output = shell_exec($command);
            $worked = 0; 
        }
        else
        {
            $command = 'mysql -h'.$mysqlHostName .' -u' .$mysqlUserName .' '.$p .$mysqlDatabaseName .' < '. $mysqlImportFilename;
            exec($command,$output=array(),$worked);
        }
       // echo $table_name;
        if($worked == 0)
        {
            $this->mls_model->rename_tablename($mysqlDatabaseName,$table_name,$prefix.'_'.$table_name);
            echo "<br>Import to staging database ".$table_name.'<br>';
            //echo $this->db->last_query();
            $this->dump_master_database($table_name,$prefix,$id,$cron_time);

        }
        else
        {
             echo 'Something goes wrong.<br>';
        }
    }
    //Import databse to master db
    function dump_master_database($table_name,$prefix,$id,$cron_time)
    {
        //ENTER THE RELEVANT INFO BELOW
        $mysqlDatabaseName = $this->config->item('mls_master_db');
        $mysqlHostName     = $this->config->item('mls_master_host');
        $mysqlUserName     = $this->config->item('mls_master_username');
        $mysqlPassword     = $this->config->item('mls_master_password');
        
        $mysqlImportFilename =$table_name.'.sql';
        $p='';
        if(!empty($mysqlPassword))
        {$p=' -p' .$mysqlPassword.' ';}
        //DO NOT EDIT BELOW THIS LINE
        //Export the database and output the status to the page
        //echo $command='mysql -h'.$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
        if($this->config->item('topsin_db_conditions') || $this->config->item('livewire_db_conditions'))
        {   
        	//echo 'master-if';
        	$command="mysql -h {$mysqlHostName} -u '{$mysqlUserName}' -p'{$mysqlPassword}' '{$mysqlDatabaseName}' < '{$mysqlImportFilename}'";
            $output = shell_exec($command);
            $worked = 0; 
        }
        else
        {
        	//echo 'master-else<br>';
            $command = 'mysql -h'.$mysqlHostName .' -u' .$mysqlUserName .' '.$p .$mysqlDatabaseName .' < '. $mysqlImportFilename;
            exec($command,$output=array(),$worked);
        }

        //echo "W :".$worked."<br>";

        if($worked == 0)
        {
            echo "Import to master database".$table_name.'<br><br>';
            $this->mls_model->rename_tablename($mysqlDatabaseName,$table_name,$prefix.'_'.$table_name);
            unlink($mysqlImportFilename);
            //Insert cron end data
            $fdata['mls_id']               = $id;
            $fdata['mls_dump']         = 1;
            $this->obj->update_record_mls_type($fdata);
            $field_data_cron = array(
                'cron_name'=>'import_database_end',
                'created_date'=>date('Y-m-d H:i:s')
            );
            $this->mls_model->insert_cron_test($field_data_cron);
            //Remove cron
            
            if(!empty($cron_time))
            {    
                $cron_field=explode('-',$cron_time);
                $output = shell_exec('crontab -l');
                //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                
                $url     = base_url().'superadmin/mls_import/import_database/'.$id.'/'.$cron_time;
                $minute  = $cron_field[0];
                $hour    = $cron_field[1];
                $day     = $cron_field[2];
                $month   = $cron_field[3];
                echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                
                //Copy cron tab and remove string
                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                   $newcron = str_replace($cronjob,"",$output);
                   file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                   echo exec('crontab ../../../../tmp/crontab.txt'); 
                } 
                else 
                {
                   echo 'not found';
                }
            }          
        }
        
    }
    
    
    /*
    @Description: Function Add New Envelope details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add Envelope details
    @Date: 20-02-2015
    */
   
    public function add_record()
    {
        $data['main_content'] = "superadmin/".$this->viewName."/add";
        $this->load->view('superadmin/include/template', $data);
    }
    /*
    @Description: Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
    
    public function retrieve_amenity_data()
    {
        set_time_limit(0);
        
        /*
        $field_data_cron = array(
            'cron_name'=>'retrieve_amenity_data_start',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron); */

        $field_data_cron = array(
                            'cron_name'=>'retrieve_amenity_data_start',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

        $field=array('id','name');
        $where=array('status'=>'1');
        $propty_type=$this->obj->select_records_tran($field,$where,'','=');

        if(!empty($propty_type))
        {
            foreach($propty_type as $type)  
            {
                    $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                    $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                    $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                    $XMLQuery .="<Message>";
                    $XMLQuery .="<Head>";
                    $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                    $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                    $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                    $XMLQuery .="</Head>";
                    $XMLQuery .="<Body>";
                    $XMLQuery .="<Query>";
                    $XMLQuery .="<MLS>NWMLS</MLS>";
                    $XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
                    //$XMLQuery .="<Status>A</Status>";
                    //$XMLQuery .="<BeginDate>2013-05-07T23:00:00</BeginDate>";;
                    //$XMLQuery .="<EndDate>2013-11-07T00:00:00</EndDate>";
                    $XMLQuery .="</Query>";
                    $XMLQuery .="<Filter></Filter>";
                    $XMLQuery .="</Body>";
                    $XMLQuery .="</Message>";
                    $XMLQuery .="</EverNetQuerySpecification>";
                    $params = array ('v_strXmlQuery' => $XMLQuery);
                    $nodelist = $client->RetrieveAmenityData($params); 
                    $accessnodelist = $nodelist->RetrieveAmenityDataResult;
                    $xml_result = new SimpleXMLElement($accessnodelist);
                    $json = json_encode($xml_result);
                    $amenity = json_decode($json,TRUE);

                    //foreach($amenity as $key=>$value)
                    //{
                        $propertytype=$key;
                        $property_data=$amenity['Amenity'];

                        foreach($property_data as $row)
                        {
                            $data['property_type']      =!empty($type['name'])?$type['name']:'';
                            $data['code']               =!empty($row['Code'])?$row['Code']:'';
                            $data['description']        =!empty($row['Description'])?$row['Description']:'';
                            $data['value_code']         =!empty($row['Values']['Code'])?$row['Values']['Code']:'';
                            $data['value_description']  =!empty($row['Values']['Description'])?$row['Values']['Description']:'';
                            $data['modified_date']      =date('Y-m-d h:i:s');
                            //$data['status']   ='1';   

                            $fields=array('id');
                            $match=array('property_type'=>!empty($type['name'])?$type['name']:'','code'=>!empty($row['Code'])?$row['Code']:'','value_code'=>!empty($row['Values']['Code'])?$row['Values']['Code']:'');
                            $res=$this->mls_model->select_records1('',$match,'','=','','','','','','',$this->mls_staging_db);
                            //echo $this->db->last_query();exit;                          
                            if(empty($res))
                            {
                                $data['created_date']       =date('Y-m-d h:i:s');
                                $id=$this->obj->insert_record1($data,$this->mls_staging_db);
                            }
                            else
                            {
                                $id=$this->obj->update_record1($data,$this->mls_staging_db);
                                //echo $this->db->last_query();
                            }
                        }

                    //}         

            }
        }
        
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_amenity_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        //redirect('superadmin/mls/add_record');
    }
    /*
    @Description:Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
   
    public function retrieve_listing_data1()
    {
        //$field_data_cron = array('cron_name'=>'retrieve_listing_data','created_date'=>date('Y-m-d H:i:s'));
        //$this->mls_model->insert_cron_test($field_data_cron);

        $starttime = microtime();
        //echo $starttime;
        set_time_limit(0);
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
        $field=array('id','name');
        $where=array('status'=>'1');
        $propty_type=$this->mls_model->select_records_tran($field,$where,'','=');

        //pr($propty_type);exit;

        // Get maximum update date

        /*$field=array('ID','max(UD) as UD');
        $res_data=$this->mls_model->select_records3($field,'','','=','','1','0');
        //pr($res_data);
        //echo $this->db->last_query();
        if(!empty($res_data[0]['UD']))
        {
                $begin_date = str_replace(" ","T",$res_data[0]['UD']);
                $curr_date  = str_replace(" ","T",date('Y-m-d h:i:s'));
        }
        else
        {
                $begin_date = '2010-01-01T00:00:00';
                $curr_date  = '2010-07-01T00:00:00';    
        }*/
        $begin_date = '2010-01-01T00:00:00';
        $curr_date  = '2010-02-01T00:00:00';
        //$begin_date = $this->uri->segment(4);
        //$curr_date  = $this->uri->segment(5);
        //echo $begin_date;
        //echo $curr_date;exit;
        if(!empty($propty_type))
        {
            foreach($propty_type as $type)  
            {

                echo $type['name'];

                //var_dump(class_exists('SOAPClient'));

                //phpinfo();

                $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                $XMLQuery .="<Message>";
                $XMLQuery .="<Head>";
                $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                $XMLQuery .="</Head>";
                $XMLQuery .="<Body>";
                $XMLQuery .="<Query>";
                $XMLQuery .="<MLS>NWMLS</MLS>";
                $XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
                //$XMLQuery .="<Status>A</Status>";
                $XMLQuery .="<BeginDate>".$begin_date."</BeginDate>";;
                $XMLQuery .="<EndDate>".$curr_date."</EndDate>";
                $XMLQuery .="</Query>";
                $XMLQuery .="<Filter></Filter>";
                $XMLQuery .="</Body>";
                $XMLQuery .="</Message>";
                $XMLQuery .="</EverNetQuerySpecification>";

                //pr($XMLQuery);

                $params = array ('v_strXmlQuery' => $XMLQuery);
                $nodelist = $client->RetrieveListingData($params); 
                $accessnodelist = $nodelist->RetrieveListingDataResult;
                $xml_result = new SimpleXMLElement($accessnodelist);
                $json = json_encode($xml_result);
                $mls_data = json_decode($json,TRUE);

                //pr($mls_data);exit;

                foreach($mls_data as $key=>$value)
                {
                    $propertytype=$key;
                    $property_data = $mls_data[$propertytype];
                    
                    $i = 0;
                    $j = 0;
                    foreach($property_data as $row)
                    {
                        $data[$i]["LN"]                         =!empty($row["LN"])?$row["LN"]:"";
                        $data[$i]["PTYP"]                       =!empty($row["PTYP"])?$row["PTYP"]:"";
                        $data[$i]["LAG"]                        =!empty($row["LAG"])?$row["LAG"]:"";
                        $data[$i]["ST"]                         =!empty($row["ST"])?$row["ST"]:"";
                        $data[$i]["LP"]                         =!empty($row["LP"])?$row["LP"]:"";
                        $data[$i]["SP"]                         =!empty($row["SP"])?$row["SP"]:"";
                        $data[$i]["OLP"]                        =!empty($row["OLP"])?$row["OLP"]:"";
                        $data[$i]["HSN"]                        =!empty($row["HSN"])?$row["HSN"]:"";
                        $data[$i]["DRP"]                        =!empty($row["DRP"])?$row["DRP"]:"";
                        $data[$i]["STR"]                        =!empty($row["STR"])?$row["STR"]:"";
                        $data[$i]["SSUF"]                       =!empty($row["SSUF"])?$row["SSUF"]:"";
                        $data[$i]["DRS"]                        =!empty($row["DRS"])?$row["DRS"]:"";
                        $data[$i]["UNT"]                        =!empty($row["UNT"])?$row["UNT"]:"";
                        $data[$i]["CIT"]                        =!empty($row["CIT"])?$row["CIT"]:"";
                        $data[$i]["STA"]                        =!empty($row["STA"])?$row["STA"]:"";
                        $data[$i]["ZIP"]                        =!empty($row["ZIP"])?$row["ZIP"]:"";
                        $data[$i]["PL4"]                        =!empty($row["PL4"])?$row["PL4"]:"";
                        $data[$i]["BR"]                         =!empty($row["BR"])?$row["BR"]:"";
                        $data[$i]["BTH"]                        =!empty($row["BTH"])?$row["BTH"]:"";
                        $data[$i]["ASF"]                        =!empty($row["ASF"])?$row["ASF"]:"";
                        $data[$i]["LSF"]                        =!empty($row["LSF"])?$row["LSF"]:"";
                        $data[$i]["UD"]                         =!empty($row["UD"])?$row["UD"]:"";
                        $data[$i]["AR"]                         =!empty($row["AR"])?$row["AR"]:"";
                        $data[$i]["DSRNUM"]                     =!empty($row["DSRNUM"])?$row["DSRNUM"]:"";
                        $data[$i]["LDR"]                        =!empty($row["LDR"])?$row["LDR"]:"";
                        $data[$i]["LD"]                         =!empty($row["LD"])?$row["LD"]:"";
                        $data[$i]["CLO"]                        =!empty($row["CLO"])?$row["CLO"]:"";
                        $data[$i]["YBT"]                        =!empty($row["YBT"])?$row["YBT"]:"";
                        $data[$i]["LO"]                         =!empty($row["LO"])?$row["LO"]:"";
                        $data[$i]["TAX"]                        =!empty($row["TAX"])?$row["TAX"]:"";
                        $data[$i]["MAP"]                        =!empty($row["MAP"])?$row["MAP"]:"";
                        $data[$i]["GRDX"]                       =!empty($row["GRDX"])?$row["GRDX"]:"";
                        $data[$i]["GRDY"]                       =!empty($row["GRDY"])?$row["GRDY"]:"";
                        $data[$i]["SAG"]                        =!empty($row["SAG"])?$row["SAG"]:"";
                        $data[$i]["SO"]                         =!empty($row["SO"])?$row["SO"]:"";
                        $data[$i]["NIA"]                        =!empty($row["NIA"])?$row["NIA"]:"";
                        $data[$i]["MR"]                         =!empty($row["MR"])?$row["MR"]:"";
                        $data[$i]["LONGI"]                      =!empty($row["LONG"])?$row["LONG"]:"";
                        $data[$i]["LAT"]                        =!empty($row["LAT"])?$row["LAT"]:"";
                        $data[$i]["PDR"]                        =!empty($row["PDR"])?$row["PDR"]:"";
                        $data[$i]["CLA"]                        =!empty($row["CLA"])?$row["CLA"]:"";
                        $data[$i]["SHOADR"]                     =!empty($row["SHOADR"])?$row["SHOADR"]:"";
                        $data[$i]["DD"]                         =!empty($row["DD"])?$row["DD"]:"";
                        $data[$i]["AVDT"]                       =!empty($row["AVDT"])?$row["AVDT"]:"";
                        $data[$i]["INDT"]                       =!empty($row["INDT"])?$row["INDT"]:"";
                        $data[$i]["COU"]                        =!empty($row["COU"])?$row["COU"]:"";
                        $data[$i]["CDOM"]                       =!empty($row["CDOM"])?$row["CDOM"]:"";
                        $data[$i]["CTDT"]                       =!empty($row["CTDT"])?$row["CTDT"]:"";
                        $data[$i]["SCA"]                        =!empty($row["SCA"])?$row["SCA"]:"";
                        $data[$i]["SCO"]                        =!empty($row["SCO"])?$row["SCO"]:"";
                        $data[$i]["VIRT"]                       =!empty($row["VIRT"])?$row["VIRT"]:"";
                        $data[$i]["SDT"]                        =!empty($row["SDT"])?$row["SDT"]:"";
                        $data[$i]["SD"]                         =!empty($row["SD"])?$row["SD"]:"";
                        $data[$i]["FIN"]                        =!empty($row["FIN"])?$row["FIN"]:"";
                        $data[$i]["MAPBOOK"]                    =!empty($row["MAPBOOK"])?$row["MAPBOOK"]:"";
                        $data[$i]["DSR"]                        =!empty($row["DSR"])?$row["DSR"]:"";
                        $data[$i]["QBT"]                        =!empty($row["QBT"])?$row["QBT"]:"";
                        $data[$i]["HSNA"]                       =!empty($row["HSNA"])?$row["HSNA"]:"";
                        $data[$i]["COLO"]                       =!empty($row["COLO"])?$row["COLO"]:"";
                        $data[$i]["PIC"]                        =!empty($row["PIC"])?$row["PIC"]:"";
                        $data[$i]["ADU"]                        =!empty($row["ADU"])?$row["ADU"]:"";
                        $data[$i]["ARC"]                        =!empty($row["ARC"])?$row["ARC"]:"";
                        $data[$i]["BDC"]                        =!empty($row["BDC"])?$row["BDC"]:"";
                        $data[$i]["BDL"]                        =!empty($row["BDL"])?$row["BDL"]:"";
                        $data[$i]["BDM"]                        =!empty($row["BDM"])?$row["BDM"]:"";
                        $data[$i]["BDU"]                        =!empty($row["BDU"])?$row["BDU"]:"";
                        $data[$i]["BLD"]                        =!empty($row["BLD"])?$row["BLD"]:"";
                        $data[$i]["BLK"]                        =!empty($row["BLK"])?$row["BLK"]:"";
                        $data[$i]["BRM"]                        =!empty($row["BRM"])?$row["BRM"]:"";
                        $data[$i]["BUS"]                        =!empty($row["BUS"])?$row["BUS"]:"";
                        $data[$i]["DNO"]                        =!empty($row["DNO"])?$row["DNO"]:"";
                        $data[$i]["DRM"]                        =!empty($row["DRM"])?$row["DRM"]:"";
                        $data[$i]["EFR"]                        =!empty($row["EFR"])?$row["EFR"]:"";
                        $data[$i]["EL"]                         =!empty($row["EL"])?$row["EL"]:"";
                        $data[$i]["ENT"]                        =!empty($row["ENT"])?$row["ENT"]:"";
                        $data[$i]["F17"]                        =!empty($row["F17"])?$row["F17"]:"";
                        $data[$i]["FAM"]                        =!empty($row["FAM"])?$row["FAM"]:"";
                        $data[$i]["FBG"]                        =!empty($row["FBG"])?$row["FBG"]:"";
                        $data[$i]["FBL"]                        =!empty($row["FBL"])?$row["FBL"]:"";
                        $data[$i]["FBM"]                        =!empty($row["FBM"])?$row["FBM"]:"";
                        $data[$i]["FBT"]                        =!empty($row["FBT"])?$row["FBT"]:"";
                        $data[$i]["FBU"]                        =!empty($row["FBU"])?$row["FBU"]:"";
                        $data[$i]["FP"]                         =!empty($row["FP"])?$row["FP"]:"";
                        $data[$i]["FPL"]                        =!empty($row["FPL"])?$row["FPL"]:"";
                        $data[$i]["FPM"]                        =!empty($row["FPM"])?$row["FPM"]:"";
                        $data[$i]["FPU"]                        =!empty($row["FPU"])?$row["FPU"]:"";
                        $data[$i]["GAR"]                        =!empty($row["GAR"])?$row["GAR"]:"";
                        $data[$i]["HBG"]                        =!empty($row["HBG"])?$row["HBG"]:"";
                        $data[$i]["HBL"]                        =!empty($row["HBL"])?$row["HBL"]:"";
                        $data[$i]["HBM"]                        =!empty($row["HBM"])?$row["HBM"]:"";
                        $data[$i]["HBT"]                        =!empty($row["HBT"])?$row["HBT"]:"";
                        $data[$i]["HBU"]                        =!empty($row["HBU"])?$row["HBU"]:"";
                        $data[$i]["HOD"]                        =!empty($row["HOD"])?$row["HOD"]:"";
                        $data[$i]["JH"]                         =!empty($row["JH"])?$row["JH"]:"";
                        $data[$i]["KES"]                        =!empty($row["KES"])?$row["KES"]:"";
                        $data[$i]["KIT"]                        =!empty($row["KIT"])?$row["KIT"]:"";
                        $data[$i]["LRM"]                        =!empty($row["LRM"])?$row["LRM"]:"";
                        $data[$i]["LSD"]                        =!empty($row["LSD"])?$row["LSD"]:"";
                        $data[$i]["LSZ"]                        =!empty($row["LSZ"])?$row["LSZ"]:"";
                        $data[$i]["LT"]                         =!empty($row["LT"])?$row["LT"]:"";
                        $data[$i]["MBD"]                        =!empty($row["MBD"])?$row["MBD"]:"";
                        $data[$i]["MHM"]                        =!empty($row["MHM"])?$row["MHM"]:"";
                        $data[$i]["MHN"]                        =!empty($row["MHN"])?$row["MHN"]:"";
                        $data[$i]["MHS"]                        =!empty($row["MHS"])?$row["MHS"]:"";
                        $data[$i]["MOR"]                        =!empty($row["MOR"])?$row["MOR"]:"";
                        $data[$i]["NC"]                         =!empty($row["NC"])?$row["NC"]:"";
                        $data[$i]["POC"]                        =!empty($row["POC"])?$row["POC"]:"";
                        $data[$i]["POL"]                        =!empty($row["POL"])?$row["POL"]:"";
                        $data[$i]["PRJ"]                        =!empty($row["PRJ"])?$row["PRJ"]:"";
                        $data[$i]["PTO"]                        =!empty($row["PTO"])?$row["PTO"]:"";
                        $data[$i]["TQBT"]                       =!empty($row["TQBT"])?$row["TQBT"]:"";
                        $data[$i]["RRM"]                        =!empty($row["RRM"])?$row["RRM"]:"";
                        $data[$i]["CMFE"]                       =!empty($row["CMFE"])?$row["CMFE"]:"";
                        $data[$i]["SAP"]                        =!empty($row["SAP"])?$row["SAP"]:"";
                        $data[$i]["SFF"]                        =!empty($row["SFF"])?$row["SFF"]:"";
                        $data[$i]["SFS"]                        =!empty($row["SFS"])?$row["SFS"]:"";
                        $data[$i]["SFU"]                        =!empty($row["SFU"])?$row["SFU"]:"";
                        $data[$i]["SH"]                         =!empty($row["SH"])?$row["SH"]:"";
                        $data[$i]["SML"]                        =!empty($row["SML"])?$row["SML"]:"";
                        $data[$i]["SNR"]                        =!empty($row["SNR"])?$row["SNR"]:"";
                        $data[$i]["STY"]                        =!empty($row["STY"])?$row["STY"]:"";
                        $data[$i]["SWC"]                        =!empty($row["SWC"])?$row["SWC"]:"";
                        $data[$i]["TBG"]                        =!empty($row["TBG"])?$row["TBG"]:"";
                        $data[$i]["TBL"]                        =!empty($row["TBL"])?$row["TBL"]:"";
                        $data[$i]["TBM"]                        =!empty($row["TBM"])?$row["TBM"]:"";
                        $data[$i]["TBU"]                        =!empty($row["TBU"])?$row["TBU"]:"";
                        $data[$i]["TX"]                         =!empty($row["TX"])?$row["TX"]:"";
                        $data[$i]["TXY"]                        =!empty($row["TXY"])?$row["TXY"]:"";
                        $data[$i]["UTR"]                        =!empty($row["UTR"])?$row["UTR"]:"";
                        $data[$i]["WAC"]                        =!empty($row["WAC"])?$row["WAC"]:"";
                        $data[$i]["WFG"]                        =!empty($row["WFG"])?$row["WFG"]:"";
                        $data[$i]["WHT"]                        =!empty($row["WHT"])?$row["WHT"]:"";
                        $data[$i]["APS"]                        =!empty($row["APS"])?$row["APS"]:"";
                        $data[$i]["BDI"]                        =!empty($row["BDI"])?$row["BDI"]:"";
                        $data[$i]["BSM"]                        =!empty($row["BSM"])?$row["BSM"]:"";
                        $data[$i]["ENS"]                        =!empty($row["ENS"])?$row["ENS"]:"";
                        $data[$i]["EXT"]                        =!empty($row["EXT"])?$row["EXT"]:"";
                        $data[$i]["FEA"]                        =!empty($row["FEA"])?$row["FEA"]:"";
                        $data[$i]["FLS"]                        =!empty($row["FLS"])?$row["FLS"]:"";
                        $data[$i]["FND"]                        =!empty($row["FND"])?$row["FND"]:"";
                        $data[$i]["GR"]                         =!empty($row["GR"])?$row["GR"]:"";
                        $data[$i]["HTC"]                        =!empty($row["HTC"])?$row["HTC"]:"";
                        $data[$i]["LDE"]                        =!empty($row["LDE"])?$row["LDE"]:"";
                        $data[$i]["LTV"]                        =!empty($row["LTV"])?$row["LTV"]:"";
                        $data[$i]["POS"]                        =!empty($row["POS"])?$row["POS"]:"";
                        $data[$i]["RF"]                         =!empty($row["RF"])?$row["RF"]:"";
                        $data[$i]["SIT"]                        =!empty($row["SIT"])?$row["SIT"]:"";
                        $data[$i]["SWR"]                        =!empty($row["SWR"])?$row["SWR"]:"";
                        $data[$i]["TRM"]                        =!empty($row["TRM"])?$row["TRM"]:"";
                        $data[$i]["VEW"]                        =!empty($row["VEW"])?$row["VEW"]:"";
                        $data[$i]["WAS"]                        =!empty($row["WAS"])?$row["WAS"]:"";
                        $data[$i]["WFT"]                        =!empty($row["WFT"])?$row["WFT"]:"";
                        $data[$i]["BUSR"]                       =!empty($row["BUSR"])?$row["BUSR"]:"";
                        $data[$i]["ECRT"]                       =!empty($row["ECRT"])?$row["ECRT"]:"";
                        $data[$i]["ZJD"]                        =!empty($row["ZJD"])?$row["ZJD"]:"";
                        $data[$i]["ZNC"]                        =!empty($row["ZNC"])?$row["ZNC"]:"";
                        $data[$i]["ProhibitBLOG"]               =!empty($row["ProhibitBLOG"])?$row["ProhibitBLOG"]:"";
                        $data[$i]["AllowAVM"]                   =!empty($row["AllowAVM"])?$row["AllowAVM"]:"";
                        $data[$i]["PARQ"]                       =!empty($row["PARQ"])?$row["PARQ"]:"";
                        $data[$i]["BREO"]                       =!empty($row["BREO"])?$row["BREO"]:"";
                        $data[$i]["BuiltGreenRating"]           =!empty($row["BuiltGreenRating"])?$row["BuiltGreenRating"]:"";
                        $data[$i]["EPSEnergy"]                  =!empty($row["EPSEnergy"])?$row["EPSEnergy"]:"";
                        $data[$i]["ROFR"]                       =!empty($row["ROFR"])?$row["ROFR"]:"";
                        $data[$i]["HERSIndex"]                  =!empty($row["HERSIndex"])?$row["HERSIndex"]:"";
                        $data[$i]["LEEDRating"]                 =!empty($row["LEEDRating"])?$row["LEEDRating"]:"";
                        $data[$i]["NewConstruction"]            =!empty($row["NewConstruction"])?$row["NewConstruction"]:"";
                        $data[$i]["NWESHRating"]                =!empty($row["NWESHRating"])?$row["NWESHRating"]:"";
                        $data[$i]["ConstructionMethods"]        =!empty($row["ConstructionMethods"])?$row["ConstructionMethods"]:"";
                        $data[$i]["EMP"]                        =!empty($row["EMP"])?$row["EMP"]:"";
                        $data[$i]["EQU"]                        =!empty($row["EQU"])?$row["EQU"]:"";
                        $data[$i]["EQV"]                        =!empty($row["EQV"])?$row["EQV"]:"";
                        $data[$i]["FRN"]                        =!empty($row["FRN"])?$row["FRN"]:"";
                        $data[$i]["GRS"]                        =!empty($row["GRS"])?$row["GRS"]:"";
                        $data[$i]["GW"]                         =!empty($row["GW"])?$row["GW"]:"";
                        $data[$i]["HRS"]                        =!empty($row["HRS"])?$row["HRS"]:"";
                        $data[$i]["INV"]                        =!empty($row["INV"])?$row["INV"]:"";
                        $data[$i]["LNM"]                        =!empty($row["LNM"])?$row["LNM"]:"";
                        $data[$i]["LSI"]                        =!empty($row["LSI"])?$row["LSI"]:"";
                        $data[$i]["NA"]                         =!empty($row["NA"])?$row["NA"]:"";
                        $data[$i]["NP"]                         =!empty($row["NP"])?$row["NP"]:"";
                        $data[$i]["PKC"]                        =!empty($row["PKC"])?$row["PKC"]:"";
                        $data[$i]["PKU"]                        =!empty($row["PKU"])?$row["PKU"]:"";
                        $data[$i]["RES"]                        =!empty($row["RES"])?$row["RES"]:"";
                        $data[$i]["RNT"]                        =!empty($row["RNT"])?$row["RNT"]:"";
                        $data[$i]["SIN"]                        =!empty($row["SIN"])?$row["SIN"]:"";
                        $data[$i]["TEXP"]                       =!empty($row["TEXP"])?$row["TEXP"]:"";
                        $data[$i]["TOB"]                        =!empty($row["TOB"])?$row["TOB"]:"";
                        $data[$i]["YRE"]                        =!empty($row["YRE"])?$row["YRE"]:"";
                        $data[$i]["YRS"]                        =!empty($row["YRS"])?$row["YRS"]:"";
                        $data[$i]["LES"]                        =!empty($row["LES"])?$row["LES"]:"";
                        $data[$i]["LIC"]                        =!empty($row["LIC"])?$row["LIC"]:"";
                        $data[$i]["LOC"]                        =!empty($row["LOC"])?$row["LOC"]:"";
                        $data[$i]["MTB"]                        =!empty($row["MTB"])?$row["MTB"]:"";
                        $data[$i]["RP"]                         =!empty($row["RP"])?$row["RP"]:"";
                        $data[$i]["LSZS"]                       =!empty($row["LSZS"])?$row["LSZS"]:"";
                        $data[$i]["AFH"]                        =!empty($row["AFH"])?$row["AFH"]:"";
                        $data[$i]["ASCC"]                       =!empty($row["ASC"])?$row["ASC"]:"";
                        $data[$i]["COO"]                        =!empty($row["COO"])?$row["COO"]:"";
                        $data[$i]["MGR"]                        =!empty($row["MGR"])?$row["MGR"]:"";
                        $data[$i]["NAS"]                        =!empty($row["NAS"])?$row["NAS"]:"";
                        $data[$i]["NOC"]                        =!empty($row["NOC"])?$row["NOC"]:"";
                        $data[$i]["NOS"]                        =!empty($row["NOS"])?$row["NOS"]:"";
                        $data[$i]["NOU"]                        =!empty($row["NOU"])?$row["NOU"]:"";
                        $data[$i]["OOC"]                        =!empty($row["OOC"])?$row["OOC"]:"";
                        $data[$i]["PKS"]                        =!empty($row["PKS"])?$row["PKS"]:"";
                        $data[$i]["REM"]                        =!empty($row["REM"])?$row["REM"]:"";
                        $data[$i]["SAA"]                        =!empty($row["SAA"])?$row["SAA"]:"";
                        $data[$i]["SPA"]                        =!empty($row["SPA"])?$row["SPA"]:"";
                        $data[$i]["STG"]                        =!empty($row["STG"])?$row["STG"]:"";
                        $data[$i]["STL"]                        =!empty($row["STL"])?$row["STL"]:"";
                        $data[$i]["TOF"]                        =!empty($row["TOF"])?$row["TOF"]:"";
                        $data[$i]["UFN"]                        =!empty($row["UFN"])?$row["UFN"]:"";
                        $data[$i]["WDW"]                        =!empty($row["WDW"])?$row["WDW"]:"";
                        $data[$i]["APH"]                        =!empty($row["APH"])?$row["APH"]:"";
                        $data[$i]["CMN"]                        =!empty($row["CMN"])?$row["CMN"]:"";
                        $data[$i]["CTD"]                        =!empty($row["CTD"])?$row["CTD"]:"";
                        $data[$i]["HOI"]                        =!empty($row["HOI"])?$row["HOI"]:"";
                        $data[$i]["PKG"]                        =!empty($row["PKG"])?$row["PKG"]:"";
                        $data[$i]["UNF"]                        =!empty($row["UNF"])?$row["UNF"]:"";
                        $data[$i]["STRS"]                       =!empty($row["STRS"])?$row["STRS"]:"";
                        $data[$i]["FUR"]                        =!empty($row["FUR"])?$row["FUR"]:"";
                        $data[$i]["MLT"]                        =!empty($row["MLT"])?$row["MLT"]:"";
                        $data[$i]["STO"]                        =!empty($row["STO"])?$row["STO"]:"";
                        $data[$i]["AFR"]                        =!empty($row["AFR"])?$row["AFR"]:"";
                        $data[$i]["APP"]                        =!empty($row["APP"])?$row["APP"]:"";
                        $data[$i]["MIF"]                        =!empty($row["MIF"])?$row["MIF"]:"";
                        $data[$i]["TMC"]                        =!empty($row["TMC"])?$row["TMC"]:"";
                        $data[$i]["TYP"]                        =!empty($row["TYP"])?$row["TYP"]:"";
                        $data[$i]["UTL"]                        =!empty($row["UTL"])?$row["UTL"]:"";
                        $data[$i]["ELE"]                        =!empty($row["ELE"])?$row["ELE"]:"";
                        $data[$i]["ESM"]                        =!empty($row["ESM"])?$row["ESM"]:"";
                        $data[$i]["GAS"]                        =!empty($row["GAS"])?$row["GAS"]:"";
                        $data[$i]["LVL"]                        =!empty($row["LVL"])?$row["LVL"]:"";
                        $data[$i]["QTR"]                        =!empty($row["QTR"])?$row["QTR"]:"";
                        $data[$i]["RD"]                         =!empty($row["RD"])?$row["RD"]:"";
                        $data[$i]["SDA"]                        =!empty($row["SDA"])?$row["SDA"]:"";
                        $data[$i]["SEC"]                        =!empty($row["SEC"])?$row["SEC"]:"";
                        $data[$i]["SEP"]                        =!empty($row["SEP"])?$row["SEP"]:"";
                        $data[$i]["SFA"]                        =!empty($row["SFA"])?$row["SFA"]:"";
                        $data[$i]["SLP"]                        =!empty($row["SLP"])?$row["SLP"]:"";
                        $data[$i]["SST"]                        =!empty($row["SST"])?$row["SST"]:"";
                        $data[$i]["SUR"]                        =!empty($row["SUR"])?$row["SUR"]:"";
                        $data[$i]["TER"]                        =!empty($row["TER"])?$row["TER"]:"";
                        $data[$i]["WRJ"]                        =!empty($row["WRJ"])?$row["WRJ"]:"";
                        $data[$i]["ZNR"]                        =!empty($row["ZNR"])?$row["ZNR"]:"";
                        $data[$i]["ATF"]                        =!empty($row["ATF"])?$row["ATF"]:"";
                        $data[$i]["DOC"]                        =!empty($row["DOC"])?$row["DOC"]:"";
                        $data[$i]["FTR"]                        =!empty($row["FTR"])?$row["FTR"]:"";
                        $data[$i]["GZC"]                        =!empty($row["GZC"])?$row["GZC"]:"";
                        $data[$i]["IMP"]                        =!empty($row["IMP"])?$row["IMP"]:"";
                        $data[$i]["RDI"]                        =!empty($row["RDI"])?$row["RDI"]:"";
                        $data[$i]["RS2"]                        =!empty($row["RS2"])?$row["RS2"]:"";
                        $data[$i]["TPO"]                        =!empty($row["TPO"])?$row["TPO"]:"";
                        $data[$i]["WTR"]                        =!empty($row["WTR"])?$row["WTR"]:"";
                        $data[$i]["CAP"]                        =!empty($row["CAP"])?$row["CAP"]:"";
                        $data[$i]["ELEX"]                       =!empty($row["ELEX"])?$row["ELEX"]:"";
                        $data[$i]["EXP"]                        =!empty($row["EXP"])?$row["EXP"]:"";
                        $data[$i]["GAI"]                        =!empty($row["GAI"])?$row["GAI"]:"";
                        $data[$i]["GRM"]                        =!empty($row["GRM"])?$row["GRM"]:"";
                        $data[$i]["GSI"]                        =!empty($row["GSI"])?$row["GSI"]:"";
                        $data[$i]["GSP"]                        =!empty($row["GSP"])?$row["GSP"]:"";
                        $data[$i]["HET"]                        =!empty($row["HET"])?$row["HET"]:"";
                        $data[$i]["INS"]                        =!empty($row["INS"])?$row["INS"]:"";
                        $data[$i]["NCS"]                        =!empty($row["NCS"])?$row["NCS"]:"";
                        $data[$i]["NOI"]                        =!empty($row["NOI"])?$row["NOI"]:"";
                        $data[$i]["OTX"]                        =!empty($row["OTX"])?$row["OTX"]:"";
                        $data[$i]["SIB"]                        =!empty($row["SIB"])?$row["SIB"]:"";
                        $data[$i]["TEX"]                        =!empty($row["TEX"])?$row["TEX"]:"";
                        $data[$i]["TIN"]                        =!empty($row["TIN"])?$row["TIN"]:"";
                        $data[$i]["TSP"]                        =!empty($row["TSP"])?$row["TSP"]:"";
                        $data[$i]["UBG"]                        =!empty($row["UBG"])?$row["UBG"]:"";
                        $data[$i]["USP"]                        =!empty($row["USP"])?$row["USP"]:"";
                        $data[$i]["VAC"]                        =!empty($row["VAC"])?$row["VAC"]:"";
                        $data[$i]["WSG"]                        =!empty($row["WSG"])?$row["WSG"]:"";
                        $data[$i]["AMN"]                        =!empty($row["AMN"])?$row["AMN"]:"";
                        $data[$i]["LIT"]                        =!empty($row["LIT"])?$row["LIT"]:"";
                        $data[$i]["UN1"]                        =!empty($row["UN1"])?$row["UN1"]:"";
                        $data[$i]["BR1"]                        =!empty($row["BR1"])?$row["BR1"]:"";
                        $data[$i]["BA1"]                        =!empty($row["BA1"])?$row["BA1"]:"";
                        $data[$i]["SF1"]                        =!empty($row["SF1"])?$row["SF1"]:"";
                        $data[$i]["RN1"]                        =!empty($row["RN1"])?$row["RN1"]:"";
                        $data[$i]["FP1"]                        =!empty($row["FP1"])?$row["FP1"]:"";
                        $data[$i]["WD1"]                        =!empty($row["WD1"])?$row["WD1"]:"";
                        $data[$i]["RO1"]                        =!empty($row["RO1"])?$row["RO1"]:"";
                        $data[$i]["FG1"]                        =!empty($row["FG1"])?$row["FG1"]:"";
                        $data[$i]["DW1"]                        =!empty($row["DW1"])?$row["DW1"]:"";
                        $data[$i]["UN2"]                        =!empty($row["UN2"])?$row["UN2"]:"";
                        $data[$i]["BR2"]                        =!empty($row["BR2"])?$row["BR2"]:"";
                        $data[$i]["BA2"]                        =!empty($row["BA2"])?$row["BA2"]:"";
                        $data[$i]["SF2"]                        =!empty($row["SF2"])?$row["SF2"]:"";
                        $data[$i]["RN2"]                        =!empty($row["RN2"])?$row["RN2"]:"";
                        $data[$i]["FP2"]                        =!empty($row["FP2"])?$row["FP2"]:"";
                        $data[$i]["WD2"]                        =!empty($row["WD2"])?$row["WD2"]:"";
                        $data[$i]["RO2"]                        =!empty($row["RO2"])?$row["RO2"]:"";
                        $data[$i]["FG2"]                        =!empty($row["FG2"])?$row["FG2"]:"";
                        $data[$i]["DW2"]                        =!empty($row["DW2"])?$row["DW2"]:"";
                        $data[$i]["UN3"]                        =!empty($row["UN3"])?$row["UN3"]:"";
                        $data[$i]["BR3"]                        =!empty($row["BR3"])?$row["BR3"]:"";
                        $data[$i]["BA3"]                        =!empty($row["BA3"])?$row["BA3"]:"";
                        $data[$i]["SF3"]                        =!empty($row["SF3"])?$row["SF3"]:"";
                        $data[$i]["RN3"]                        =!empty($row["RN3"])?$row["RN3"]:"";
                        $data[$i]["FP3"]                        =!empty($row["FP3"])?$row["FP3"]:"";
                        $data[$i]["WD3"]                        =!empty($row["WD3"])?$row["WD3"]:"";
                        $data[$i]["RO3"]                        =!empty($row["RO3"])?$row["RO3"]:"";
                        $data[$i]["FG3"]                        =!empty($row["FG3"])?$row["FG3"]:"";
                        $data[$i]["DW3"]                        =!empty($row["DW3"])?$row["DW3"]:"";
                        $data[$i]["UN4"]                        =!empty($row["UN4"])?$row["UN4"]:"";
                        $data[$i]["BR4"]                        =!empty($row["BR4"])?$row["BR4"]:"";
                        $data[$i]["BA4"]                        =!empty($row["BA4"])?$row["BA4"]:"";
                        $data[$i]["SF4"]                        =!empty($row["SF4"])?$row["SF4"]:"";
                        $data[$i]["RN4"]                        =!empty($row["RN4"])?$row["RN4"]:"";
                        $data[$i]["FP4"]                        =!empty($row["FP4"])?$row["FP4"]:"";
                        $data[$i]["WD4"]                        =!empty($row["WD4"])?$row["WD4"]:"";
                        $data[$i]["RO4"]                        =!empty($row["RO4"])?$row["RO4"]:"";
                        $data[$i]["FG4"]                        =!empty($row["FG4"])?$row["FG4"]:"";
                        $data[$i]["DW4"]                        =!empty($row["DW4"])?$row["DW4"]:"";
                        $data[$i]["UN5"]                        =!empty($row["UN5"])?$row["UN5"]:"";
                        $data[$i]["BR5"]                        =!empty($row["BR5"])?$row["BR5"]:"";
                        $data[$i]["BA5"]                        =!empty($row["BA5"])?$row["BA5"]:"";
                        $data[$i]["SF5"]                        =!empty($row["SF5"])?$row["SF5"]:"";
                        $data[$i]["RN5"]                        =!empty($row["RN5"])?$row["RN5"]:"";
                        $data[$i]["FP5"]                        =!empty($row["FP5"])?$row["FP5"]:"";
                        $data[$i]["WD5"]                        =!empty($row["WD5"])?$row["WD5"]:"";
                        $data[$i]["RO5"]                        =!empty($row["RO5"])?$row["RO5"]:"";
                        $data[$i]["FG5"]                        =!empty($row["FG5"])?$row["FG5"]:"";
                        $data[$i]["DW5"]                        =!empty($row["DW5"])?$row["DW5"]:"";
                        $data[$i]["UN6"]                        =!empty($row["UN6"])?$row["UN6"]:"";
                        $data[$i]["BR6"]                        =!empty($row["BR6"])?$row["BR6"]:"";
                        $data[$i]["BA6"]                        =!empty($row["BA6"])?$row["BA6"]:"";
                        $data[$i]["SF6"]                        =!empty($row["SF6"])?$row["SF6"]:"";
                        $data[$i]["RN6"]                        =!empty($row["RN6"])?$row["RN6"]:"";
                        $data[$i]["FP6"]                        =!empty($row["FP6"])?$row["FP6"]:"";
                        $data[$i]["WD6"]                        =!empty($row["WD6"])?$row["WD6"]:"";
                        $data[$i]["RO6"]                        =!empty($row["RO6"])?$row["RO6"]:"";
                        $data[$i]["FG6"]                        =!empty($row["FG6"])?$row["FG6"]:"";
                        $data[$i]["DW6"]                        =!empty($row["DW6"])?$row["DW6"]:"";
                        $data[$i]["AMP"]                        =!empty($row["AMP"])?$row["AMP"]:"";
                        $data[$i]["AVP"]                        =!empty($row["AVP"])?$row["AVP"]:"";
                        $data[$i]["BON"]                        =!empty($row["BON"])?$row["BON"]:"";
                        $data[$i]["CHT"]                        =!empty($row["CHT"])?$row["CHT"]:"";
                        $data[$i]["CSP"]                        =!empty($row["CSP"])?$row["CSP"]:"";
                        $data[$i]["DLT"]                        =!empty($row["DLT"])?$row["DLT"]:"";
                        $data[$i]["ENV"]                        =!empty($row["ENV"])?$row["ENV"]:"";
                        $data[$i]["EXA"]                        =!empty($row["EXA"])?$row["EXA"]:"";
                        $data[$i]["FAC"]                        =!empty($row["FAC"])?$row["FAC"]:"";
                        $data[$i]["NNN"]                        =!empty($row["NNN"])?$row["NNN"]:"";
                        $data[$i]["OSF"]                        =!empty($row["OSF"])?$row["OSF"]:"";
                        $data[$i]["PAD"]                        =!empty($row["PAD"])?$row["PAD"]:"";
                        $data[$i]["SIZ"]                        =!empty($row["SIZ"])?$row["SIZ"]:"";
                        $data[$i]["STF"]                        =!empty($row["STF"])?$row["STF"]:"";
                        $data[$i]["TAV"]                        =!empty($row["TAV"])?$row["TAV"]:"";
                        $data[$i]["TRI"]                        =!empty($row["TRI"])?$row["TRI"]:"";
                        $data[$i]["TSF"]                        =!empty($row["TSF"])?$row["TSF"]:"";
                        $data[$i]["VAI"]                        =!empty($row["VAI"])?$row["VAI"]:"";
                        $data[$i]["VAL"]                        =!empty($row["VAL"])?$row["VAL"]:"";
                        $data[$i]["WSF"]                        =!empty($row["WSF"])?$row["WSF"]:"";
                        $data[$i]["YVA"]                        =!empty($row["YVA"])?$row["YVA"]:"";
                        $data[$i]["CFE"]                        =!empty($row["CFE"])?$row["CFE"]:"";
                        $data[$i]["LDG"]                        =!empty($row["LDG"])?$row["LDG"]:"";
                        $data[$i]["TN1"]                        =!empty($row["TN1"])?$row["TN1"]:"";
                        $data[$i]["LX1"]                        =!empty($row["LX1"])?$row["LX1"]:"";
                        $data[$i]["NN1"]                        =!empty($row["NN1"])?$row["NN1"]:"";
                        $data[$i]["US1"]                        =!empty($row["US1"])?$row["US1"]:"";
                        $data[$i]["TN2"]                        =!empty($row["TN2"])?$row["TN2"]:"";
                        $data[$i]["LX2"]                        =!empty($row["LX2"])?$row["LX2"]:"";
                        $data[$i]["NN2"]                        =!empty($row["NN2"])?$row["NN2"]:"";
                        $data[$i]["US2"]                        =!empty($row["US2"])?$row["US2"]:"";
                        $data[$i]["TN3"]                        =!empty($row["TN3"])?$row["TN3"]:"";
                        $data[$i]["LX3"]                        =!empty($row["LX3"])?$row["LX3"]:"";
                        $data[$i]["NN3"]                        =!empty($row["NN3"])?$row["NN3"]:"";
                        $data[$i]["US3"]                        =!empty($row["US3"])?$row["US3"]:"";
                        $data[$i]["TN4"]                        =!empty($row["TN4"])?$row["TN4"]:"";
                        $data[$i]["LX4"]                        =!empty($row["LX4"])?$row["LX4"]:"";
                        $data[$i]["NN4"]                        =!empty($row["NN4"])?$row["NN4"]:"";
                        $data[$i]["US4"]                        =!empty($row["US4"])?$row["US4"]:"";
                        $data[$i]["TN5"]                        =!empty($row["TN5"])?$row["TN5"]:"";
                        $data[$i]["LX5"]                        =!empty($row["LX5"])?$row["LX5"]:"";
                        $data[$i]["NN5"]                        =!empty($row["NN5"])?$row["NN5"]:"";
                        $data[$i]["US5"]                        =!empty($row["US5"])?$row["US5"]:"";
                        $data[$i]["TN6"]                        =!empty($row["TN6"])?$row["TN6"]:"";
                        $data[$i]["LX6"]                        =!empty($row["LX6"])?$row["LX6"]:"";
                        $data[$i]["NN6"]                        =!empty($row["NN6"])?$row["NN6"]:"";
                        $data[$i]["US6"]                        =!empty($row["US6"])?$row["US6"]:"";
                        $data[$i]["ACC"]                        =!empty($row["ACC"])?$row["ACC"]:"";
                        $data[$i]["BCC"]                        =!empty($row["BCC"])?$row["BCC"]:"";
                        $data[$i]["BRI"]                        =!empty($row["BRI"])?$row["BRI"]:"";
                        $data[$i]["BSZ"]                        =!empty($row["BSZ"])?$row["BSZ"]:"";
                        $data[$i]["CCC"]                        =!empty($row["CCC"])?$row["CCC"]:"";
                        $data[$i]["CRI"]                        =!empty($row["CRI"])?$row["CRI"]:"";
                        $data[$i]["EQI"]                        =!empty($row["EQI"])?$row["EQI"]:"";
                        $data[$i]["LCC"]                        =!empty($row["LCC"])?$row["LCC"]:"";
                        $data[$i]["IRRC"]                       =!empty($row["IRRC"])?$row["IRRC"]:"";
                        $data[$i]["PSZ"]                        =!empty($row["PSZ"])?$row["PSZ"]:"";
                        $data[$i]["SSZ"]                        =!empty($row["SSZ"])?$row["SSZ"]:"";
                        $data[$i]["TAC"]                        =!empty($row["TAC"])?$row["TAC"]:"";
                        $data[$i]["VCC"]                        =!empty($row["VCC"])?$row["VCC"]:"";
                        $data[$i]["BFE"]                        =!empty($row["BFE"])?$row["BFE"]:"";
                        $data[$i]["BTP"]                        =!empty($row["BTP"])?$row["BTP"]:"";
                        $data[$i]["EQP"]                        =!empty($row["EQP"])?$row["EQP"]:"";
                        $data[$i]["FEN"]                        =!empty($row["FEN"])?$row["FEN"]:"";
                        $data[$i]["FTP"]                        =!empty($row["FTP"])?$row["FTP"]:"";
                        $data[$i]["IRS"]                        =!empty($row["IRS"])?$row["IRS"]:"";
                        $data[$i]["ITP"]                        =!empty($row["ITP"])?$row["ITP"]:"";
                        $data[$i]["LEQ"]                        =!empty($row["LEQ"])?$row["LEQ"]:"";
                        $data[$i]["LTG"]                        =!empty($row["LTG"])?$row["LTG"]:"";
                        $data[$i]["LTP"]                        =!empty($row["LTP"])?$row["LTP"]:"";
                        $data[$i]["OUT1"]                       =!empty($row["OUT"])?$row["OUT"]:"";
                        $data[$i]["STP"]                        =!empty($row["STP"])?$row["STP"]:"";
                        $data[$i]["ELEV"]                       =!empty($row["ELEV"])?$row["ELEV"]:"";
                        $data[$i]["AGR"]                        =!empty($row["AGR"])?$row["AGR"]:"";
                        $data[$i]["LNI"]                        =!empty($row["LNI"])?$row["LNI"]:"";
                        $data[$i]["MFY"]                        =!empty($row["MFY"])?$row["MFY"]:"";
                        $data[$i]["NOH"]                        =!empty($row["NOH"])?$row["NOH"]:"";
                        $data[$i]["PAS"]                        =!empty($row["PAS"])?$row["PAS"]:"";
                        $data[$i]["PRK"]                        =!empty($row["PRK"])?$row["PRK"]:"";
                        $data[$i]["SKR"]                        =!empty($row["SKR"])?$row["SKR"]:"";
                        $data[$i]["SPR"]                        =!empty($row["SPR"])?$row["SPR"]:"";
                        $data[$i]["UCS"]                        =!empty($row["UCS"])?$row["UCS"]:"";
                        $data[$i]["ANC"]                        =!empty($row["ANC"])?$row["ANC"]:"";
                        $data[$i]["MHF"]                        =!empty($row["MHF"])?$row["MHF"]:"";
                        $data[$i]["OTR"]                        =!empty($row["OTR"])?$row["OTR"]:"";
                        $data[$i]["PKA"]                        =!empty($row["PKA"])?$row["PKA"]:"";
                        $data[$i]["SRI"]                        =!empty($row["SRI"])?$row["SRI"]:"";
                        $data[$i]["AUCTION"]                    =!empty($row["AUCTION"])?$row["AUCTION"]:"";
                        $data[$i]["LotSizeSource"]              =!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
                        $data[$i]["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
                        $data[$i]["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";
                        $data[$i]['modified_date']              = date('Y-m-d h:i:s');
                        $data[$i]['status']                     =1;
                        $data[$i]['mls_type_id']                ='1';
                        pr($data);
                        if(!empty($data[$i]['LAT']) && !empty($data[$i]['LONGI']))                        
                        {   
                            //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                            $data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT("POINT(", '.$data[$i]["LAT"].'," ",'.$data[$i]["LONGI"].',")"))';
                        }
                        
                        $fields=array('ID','LN');
                        $match=array('LN'=>!empty($row['LN'])?$row['LN']:''); 
                        //$res=$this->mls_model->select_records3('',$match,'','=');
                        $res=$this->mls_model->select_records3('',$match,'','=','','','','','','',$this->mls_staging_db,'','','nwmls_mls_property_list_master');
                        //echo $this->db->last_query();
                        //pr($data);
                        //exit;

                        if(empty($res))
                        {
                                $data[$i]['created_date']       = date('Y-m-d h:i:s');
                                if(count($data) >= 100)
                                {
                                        $this->mls_model->insert_record3($data,$this->mls_staging_db);
                                        //$this->mls_model->insert_record3($data,$this->mls_master_db);
                                        $i = 0;
                                        unset($data);
                                }
                                $i++;
                                //$id=$this->mls_model->insert_record3($data);
                        }
                        else
                        {
                                $cdata[$j] = $data[$i];
                                unset($data[$i]);
                                if(count($cdata) >= 100)
                                {
                                        $this->mls_model->update_record3($cdata,$this->mls_staging_db);
                                        //$this->mls_model->update_record3($cdata,$this->mls_master_db);
                                        //pr($cdata); exit;
                                        $j = 0;
                                        unset($cdata);
                                }
                                $j++;
                                //$id=$this->mls_model->update_record3($data);
                        }
                    }
                    if(!empty($data) && count($data) > 0)
                    {
                       // $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        $this->mls_model->insert_record3($data,$this->mls_staging_db);
                        //$this->mls_model->insert_record3($data,$this->mls_master_db);
                        unset($data);   
                    }
                    if(!empty($cdata) && count($cdata) > 0)
                    {
                        $this->mls_model->update_record3($cdata,$this->mls_staging_db);
                        //$this->mls_model->update_record3($cdata,$this->mls_master_db);
                        unset($cdata);  
                    }
                }
            }
        }
        // Insert to mls database
        echo 'done';
        echo microtime()-$starttime;
        //redirect('superadmin/mls/add_record');
    }

    /*
    @Description:Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
   
    public function retrieve_listing_data()
    {
        //print ini_get('max_execution_time');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        //print ini_get('max_execution_time');
        //exit;

        $begin_date = $this->uri->segment(4);
        $curr_date  = $this->uri->segment(5);
        $propty_type= $this->uri->segment(6);
        $cron_time  = $this->uri->segment(7);
        $field_data_cron = array(
                            'cron_name'=>'retrieve_listing_data_start',
                            'p_type'=>$propty_type,
                            'begin_date'=>$begin_date,
                            'curr_date'=>$curr_date,
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

        $starttime = microtime();
        //echo $starttime;
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
        /*
        $field=array('id','name');
        $where=array('status'=>'1');
        $propty_type=$this->mls_model->select_records_tran($field,$where,'','=');
        */

        //pr($propty_type);exit;

        // Get maximum update date

        /*$field=array('ID','max(UD) as UD');
        $res_data=$this->mls_model->select_records3($field,'','','=','','1','0');
        //pr($res_data);
        //echo $this->db->last_query();
        if(!empty($res_data[0]['UD']))
        {
                $begin_date = str_replace(" ","T",$res_data[0]['UD']);
                $curr_date  = str_replace(" ","T",date('Y-m-d h:i:s'));
        }
        else
        {
                $begin_date = '2010-01-01T00:00:00';
                $curr_date  = '2010-07-01T00:00:00';    
        }*/
        //$begin_date = '2010-01-01T00:00:01';
        //$curr_date  = '2010-01-08T00:00:00';
        
        //exit;
        //echo $begin_date;
        //echo $curr_date;exit;
        //if(!empty($propty_type))
        //{
            //foreach($propty_type as $type)  
            //{

                //echo $type['name'];

                //var_dump(class_exists('SOAPClient'));

                //phpinfo();

                $type = array("name"=>$propty_type);

                $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                $XMLQuery .="<Message>";
                $XMLQuery .="<Head>";
                $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                $XMLQuery .="</Head>";
                $XMLQuery .="<Body>";
                $XMLQuery .="<Query>";
                $XMLQuery .="<MLS>NWMLS</MLS>";
                $XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
                //$XMLQuery .="<Status>A</Status>";
                $XMLQuery .="<BeginDate>".$begin_date."</BeginDate>";;
                $XMLQuery .="<EndDate>".$curr_date."</EndDate>";
                $XMLQuery .="</Query>";
                $XMLQuery .="<Filter></Filter>";
                $XMLQuery .="</Body>";
                $XMLQuery .="</Message>";
                $XMLQuery .="</EverNetQuerySpecification>";

                //pr($XMLQuery);

                $params = array ('v_strXmlQuery' => $XMLQuery);
                $nodelist = $client->RetrieveListingData($params); 
                $accessnodelist = $nodelist->RetrieveListingDataResult;
                $xml_result = new SimpleXMLElement($accessnodelist);
                $json = json_encode($xml_result);
                $mls_data = json_decode($json,TRUE);

                //pr($mls_data);

                //exit;

                foreach($mls_data as $key=>$value)
                {
                    $propertytype=$key;
                    $property_data = $mls_data[$propertytype];
                    
                    $i = 0;
                    $j = 0;
                    foreach($property_data as $row)
                    {
                        $data[$i]["LN"]                         =!empty($row["LN"])?$row["LN"]:"";
                        $data[$i]["PTYP"]                       =!empty($row["PTYP"])?$row["PTYP"]:"";
                        $data[$i]["LAG"]                        =!empty($row["LAG"])?$row["LAG"]:"";
                        $data[$i]["ST"]                         =!empty($row["ST"])?$row["ST"]:"";
                        $data[$i]["LP"]                         =!empty($row["LP"])?$row["LP"]:"";
                        $data[$i]["SP"]                         =!empty($row["SP"])?$row["SP"]:"";
                        $data[$i]["OLP"]                        =!empty($row["OLP"])?$row["OLP"]:"";
                        $data[$i]["HSN"]                        =!empty($row["HSN"])?$row["HSN"]:"";
                        $data[$i]["DRP"]                        =!empty($row["DRP"])?$row["DRP"]:"";
                        $data[$i]["STR"]                        =!empty($row["STR"])?$row["STR"]:"";
                        $data[$i]["SSUF"]                       =!empty($row["SSUF"])?$row["SSUF"]:"";
                        $data[$i]["DRS"]                        =!empty($row["DRS"])?$row["DRS"]:"";
                        $data[$i]["UNT"]                        =!empty($row["UNT"])?$row["UNT"]:"";
                        $data[$i]["CIT"]                        =!empty($row["CIT"])?$row["CIT"]:"";
                        $data[$i]["STA"]                        =!empty($row["STA"])?$row["STA"]:"";
                        $data[$i]["ZIP"]                        =!empty($row["ZIP"])?$row["ZIP"]:"";
                        $data[$i]["PL4"]                        =!empty($row["PL4"])?$row["PL4"]:"";
                        $data[$i]["BR"]                         =!empty($row["BR"])?$row["BR"]:"";
                        $data[$i]["BTH"]                        =!empty($row["BTH"])?$row["BTH"]:"";
                        $data[$i]["ASF"]                        =!empty($row["ASF"])?$row["ASF"]:"";
                        $data[$i]["LSF"]                        =!empty($row["LSF"])?$row["LSF"]:"";
                        $data[$i]["UD"]                         =!empty($row["UD"])?$row["UD"]:"";
                        $data[$i]["AR"]                         =!empty($row["AR"])?$row["AR"]:"";
                        $data[$i]["DSRNUM"]                     =!empty($row["DSRNUM"])?$row["DSRNUM"]:"";
                        $data[$i]["LDR"]                        =!empty($row["LDR"])?$row["LDR"]:"";
                        $data[$i]["LD"]                         =!empty($row["LD"])?$row["LD"]:"";
                        $data[$i]["CLO"]                        =!empty($row["CLO"])?$row["CLO"]:"";
                        $data[$i]["YBT"]                        =!empty($row["YBT"])?$row["YBT"]:"";
                        $data[$i]["LO"]                         =!empty($row["LO"])?$row["LO"]:"";
                        $data[$i]["TAX"]                        =!empty($row["TAX"])?$row["TAX"]:"";
                        $data[$i]["MAP"]                        =!empty($row["MAP"])?$row["MAP"]:"";
                        $data[$i]["GRDX"]                       =!empty($row["GRDX"])?$row["GRDX"]:"";
                        $data[$i]["GRDY"]                       =!empty($row["GRDY"])?$row["GRDY"]:"";
                        $data[$i]["SAG"]                        =!empty($row["SAG"])?$row["SAG"]:"";
                        $data[$i]["SO"]                         =!empty($row["SO"])?$row["SO"]:"";
                        $data[$i]["NIA"]                        =!empty($row["NIA"])?$row["NIA"]:"";
                        $data[$i]["MR"]                         =!empty($row["MR"])?$row["MR"]:"";
                        $data[$i]["LONGI"]                      =!empty($row["LONG"])?$row["LONG"]:"";
                        $data[$i]["LAT"]                        =!empty($row["LAT"])?$row["LAT"]:"";
                        $data[$i]["PDR"]                        =!empty($row["PDR"])?$row["PDR"]:"";
                        $data[$i]["CLA"]                        =!empty($row["CLA"])?$row["CLA"]:"";
                        $data[$i]["SHOADR"]                     =!empty($row["SHOADR"])?$row["SHOADR"]:"";
                        $data[$i]["DD"]                         =!empty($row["DD"])?$row["DD"]:"";
                        $data[$i]["AVDT"]                       =!empty($row["AVDT"])?$row["AVDT"]:"";
                        $data[$i]["INDT"]                       =!empty($row["INDT"])?$row["INDT"]:"";
                        $data[$i]["COU"]                        =!empty($row["COU"])?$row["COU"]:"";
                        $data[$i]["CDOM"]                       =!empty($row["CDOM"])?$row["CDOM"]:"";
                        $data[$i]["CTDT"]                       =!empty($row["CTDT"])?$row["CTDT"]:"";
                        $data[$i]["SCA"]                        =!empty($row["SCA"])?$row["SCA"]:"";
                        $data[$i]["SCO"]                        =!empty($row["SCO"])?$row["SCO"]:"";
                        $data[$i]["VIRT"]                       =!empty($row["VIRT"])?$row["VIRT"]:"";
                        $data[$i]["SDT"]                        =!empty($row["SDT"])?$row["SDT"]:"";
                        $data[$i]["SD"]                         =!empty($row["SD"])?$row["SD"]:"";
                        $data[$i]["FIN"]                        =!empty($row["FIN"])?$row["FIN"]:"";
                        $data[$i]["MAPBOOK"]                    =!empty($row["MAPBOOK"])?$row["MAPBOOK"]:"";
                        $data[$i]["DSR"]                        =!empty($row["DSR"])?$row["DSR"]:"";
                        $data[$i]["QBT"]                        =!empty($row["QBT"])?$row["QBT"]:"";
                        $data[$i]["HSNA"]                       =!empty($row["HSNA"])?$row["HSNA"]:"";
                        $data[$i]["COLO"]                       =!empty($row["COLO"])?$row["COLO"]:"";
                        $data[$i]["PIC"]                        =!empty($row["PIC"])?$row["PIC"]:"";
                        $data[$i]["ADU"]                        =!empty($row["ADU"])?$row["ADU"]:"";
                        $data[$i]["ARC"]                        =!empty($row["ARC"])?$row["ARC"]:"";
                        $data[$i]["BDC"]                        =!empty($row["BDC"])?$row["BDC"]:"";
                        $data[$i]["BDL"]                        =!empty($row["BDL"])?$row["BDL"]:"";
                        $data[$i]["BDM"]                        =!empty($row["BDM"])?$row["BDM"]:"";
                        $data[$i]["BDU"]                        =!empty($row["BDU"])?$row["BDU"]:"";
                        $data[$i]["BLD"]                        =!empty($row["BLD"])?$row["BLD"]:"";
                        $data[$i]["BLK"]                        =!empty($row["BLK"])?$row["BLK"]:"";
                        $data[$i]["BRM"]                        =!empty($row["BRM"])?$row["BRM"]:"";
                        $data[$i]["BUS"]                        =!empty($row["BUS"])?$row["BUS"]:"";
                        $data[$i]["DNO"]                        =!empty($row["DNO"])?$row["DNO"]:"";
                        $data[$i]["DRM"]                        =!empty($row["DRM"])?$row["DRM"]:"";
                        $data[$i]["EFR"]                        =!empty($row["EFR"])?$row["EFR"]:"";
                        $data[$i]["EL"]                         =!empty($row["EL"])?$row["EL"]:"";
                        $data[$i]["ENT"]                        =!empty($row["ENT"])?$row["ENT"]:"";
                        $data[$i]["F17"]                        =!empty($row["F17"])?$row["F17"]:"";
                        $data[$i]["FAM"]                        =!empty($row["FAM"])?$row["FAM"]:"";
                        $data[$i]["FBG"]                        =!empty($row["FBG"])?$row["FBG"]:"";
                        $data[$i]["FBL"]                        =!empty($row["FBL"])?$row["FBL"]:"";
                        $data[$i]["FBM"]                        =!empty($row["FBM"])?$row["FBM"]:"";
                        $data[$i]["FBT"]                        =!empty($row["FBT"])?$row["FBT"]:"";
                        $data[$i]["FBU"]                        =!empty($row["FBU"])?$row["FBU"]:"";
                        $data[$i]["FP"]                         =!empty($row["FP"])?$row["FP"]:"";
                        $data[$i]["FPL"]                        =!empty($row["FPL"])?$row["FPL"]:"";
                        $data[$i]["FPM"]                        =!empty($row["FPM"])?$row["FPM"]:"";
                        $data[$i]["FPU"]                        =!empty($row["FPU"])?$row["FPU"]:"";
                        $data[$i]["GAR"]                        =!empty($row["GAR"])?$row["GAR"]:"";
                        $data[$i]["HBG"]                        =!empty($row["HBG"])?$row["HBG"]:"";
                        $data[$i]["HBL"]                        =!empty($row["HBL"])?$row["HBL"]:"";
                        $data[$i]["HBM"]                        =!empty($row["HBM"])?$row["HBM"]:"";
                        $data[$i]["HBT"]                        =!empty($row["HBT"])?$row["HBT"]:"";
                        $data[$i]["HBU"]                        =!empty($row["HBU"])?$row["HBU"]:"";
                        $data[$i]["HOD"]                        =!empty($row["HOD"])?$row["HOD"]:"";
                        $data[$i]["JH"]                         =!empty($row["JH"])?$row["JH"]:"";
                        $data[$i]["KES"]                        =!empty($row["KES"])?$row["KES"]:"";
                        $data[$i]["KIT"]                        =!empty($row["KIT"])?$row["KIT"]:"";
                        $data[$i]["LRM"]                        =!empty($row["LRM"])?$row["LRM"]:"";
                        $data[$i]["LSD"]                        =!empty($row["LSD"])?$row["LSD"]:"";
                        $data[$i]["LSZ"]                        =!empty($row["LSZ"])?$row["LSZ"]:"";
                        $data[$i]["LT"]                         =!empty($row["LT"])?$row["LT"]:"";
                        $data[$i]["MBD"]                        =!empty($row["MBD"])?$row["MBD"]:"";
                        $data[$i]["MHM"]                        =!empty($row["MHM"])?$row["MHM"]:"";
                        $data[$i]["MHN"]                        =!empty($row["MHN"])?$row["MHN"]:"";
                        $data[$i]["MHS"]                        =!empty($row["MHS"])?$row["MHS"]:"";
                        $data[$i]["MOR"]                        =!empty($row["MOR"])?$row["MOR"]:"";
                        $data[$i]["NC"]                         =!empty($row["NC"])?$row["NC"]:"";
                        $data[$i]["POC"]                        =!empty($row["POC"])?$row["POC"]:"";
                        $data[$i]["POL"]                        =!empty($row["POL"])?$row["POL"]:"";
                        $data[$i]["PRJ"]                        =!empty($row["PRJ"])?$row["PRJ"]:"";
                        $data[$i]["PTO"]                        =!empty($row["PTO"])?$row["PTO"]:"";
                        $data[$i]["TQBT"]                       =!empty($row["TQBT"])?$row["TQBT"]:"";
                        $data[$i]["RRM"]                        =!empty($row["RRM"])?$row["RRM"]:"";
                        $data[$i]["CMFE"]                       =!empty($row["CMFE"])?$row["CMFE"]:"";
                        $data[$i]["SAP"]                        =!empty($row["SAP"])?$row["SAP"]:"";
                        $data[$i]["SFF"]                        =!empty($row["SFF"])?$row["SFF"]:"";
                        $data[$i]["SFS"]                        =!empty($row["SFS"])?$row["SFS"]:"";
                        $data[$i]["SFU"]                        =!empty($row["SFU"])?$row["SFU"]:"";
                        $data[$i]["SH"]                         =!empty($row["SH"])?$row["SH"]:"";
                        $data[$i]["SML"]                        =!empty($row["SML"])?$row["SML"]:"";
                        $data[$i]["SNR"]                        =!empty($row["SNR"])?$row["SNR"]:"";
                        $data[$i]["STY"]                        =!empty($row["STY"])?$row["STY"]:"";
                        $data[$i]["SWC"]                        =!empty($row["SWC"])?$row["SWC"]:"";
                        $data[$i]["TBG"]                        =!empty($row["TBG"])?$row["TBG"]:"";
                        $data[$i]["TBL"]                        =!empty($row["TBL"])?$row["TBL"]:"";
                        $data[$i]["TBM"]                        =!empty($row["TBM"])?$row["TBM"]:"";
                        $data[$i]["TBU"]                        =!empty($row["TBU"])?$row["TBU"]:"";
                        $data[$i]["TX"]                         =!empty($row["TX"])?$row["TX"]:"";
                        $data[$i]["TXY"]                        =!empty($row["TXY"])?$row["TXY"]:"";
                        $data[$i]["UTR"]                        =!empty($row["UTR"])?$row["UTR"]:"";
                        $data[$i]["WAC"]                        =!empty($row["WAC"])?$row["WAC"]:"";
                        $data[$i]["WFG"]                        =!empty($row["WFG"])?$row["WFG"]:"";
                        $data[$i]["WHT"]                        =!empty($row["WHT"])?$row["WHT"]:"";
                        $data[$i]["APS"]                        =!empty($row["APS"])?$row["APS"]:"";
                        $data[$i]["BDI"]                        =!empty($row["BDI"])?$row["BDI"]:"";
                        $data[$i]["BSM"]                        =!empty($row["BSM"])?$row["BSM"]:"";
                        $data[$i]["ENS"]                        =!empty($row["ENS"])?$row["ENS"]:"";
                        $data[$i]["EXT"]                        =!empty($row["EXT"])?$row["EXT"]:"";
                        $data[$i]["FEA"]                        =!empty($row["FEA"])?$row["FEA"]:"";
                        $data[$i]["FLS"]                        =!empty($row["FLS"])?$row["FLS"]:"";
                        $data[$i]["FND"]                        =!empty($row["FND"])?$row["FND"]:"";
                        $data[$i]["GR"]                         =!empty($row["GR"])?$row["GR"]:"";
                        $data[$i]["HTC"]                        =!empty($row["HTC"])?$row["HTC"]:"";
                        $data[$i]["LDE"]                        =!empty($row["LDE"])?$row["LDE"]:"";
                        $data[$i]["LTV"]                        =!empty($row["LTV"])?$row["LTV"]:"";
                        $data[$i]["POS"]                        =!empty($row["POS"])?$row["POS"]:"";
                        $data[$i]["RF"]                         =!empty($row["RF"])?$row["RF"]:"";
                        $data[$i]["SIT"]                        =!empty($row["SIT"])?$row["SIT"]:"";
                        $data[$i]["SWR"]                        =!empty($row["SWR"])?$row["SWR"]:"";
                        $data[$i]["TRM"]                        =!empty($row["TRM"])?$row["TRM"]:"";
                        $data[$i]["VEW"]                        =!empty($row["VEW"])?$row["VEW"]:"";
                        $data[$i]["WAS"]                        =!empty($row["WAS"])?$row["WAS"]:"";
                        $data[$i]["WFT"]                        =!empty($row["WFT"])?$row["WFT"]:"";
                        $data[$i]["BUSR"]                       =!empty($row["BUSR"])?$row["BUSR"]:"";
                        $data[$i]["ECRT"]                       =!empty($row["ECRT"])?$row["ECRT"]:"";
                        $data[$i]["ZJD"]                        =!empty($row["ZJD"])?$row["ZJD"]:"";
                        $data[$i]["ZNC"]                        =!empty($row["ZNC"])?$row["ZNC"]:"";
                        $data[$i]["ProhibitBLOG"]               =!empty($row["ProhibitBLOG"])?$row["ProhibitBLOG"]:"";
                        $data[$i]["AllowAVM"]                   =!empty($row["AllowAVM"])?$row["AllowAVM"]:"";
                        $data[$i]["PARQ"]                       =!empty($row["PARQ"])?$row["PARQ"]:"";
                        $data[$i]["BREO"]                       =!empty($row["BREO"])?$row["BREO"]:"";
                        $data[$i]["BuiltGreenRating"]           =!empty($row["BuiltGreenRating"])?$row["BuiltGreenRating"]:"";
                        $data[$i]["EPSEnergy"]                  =!empty($row["EPSEnergy"])?$row["EPSEnergy"]:"";
                        $data[$i]["ROFR"]                       =!empty($row["ROFR"])?$row["ROFR"]:"";
                        $data[$i]["HERSIndex"]                  =!empty($row["HERSIndex"])?$row["HERSIndex"]:"";
                        $data[$i]["LEEDRating"]                 =!empty($row["LEEDRating"])?$row["LEEDRating"]:"";
                        $data[$i]["NewConstruction"]            =!empty($row["NewConstruction"])?$row["NewConstruction"]:"";
                        $data[$i]["NWESHRating"]                =!empty($row["NWESHRating"])?$row["NWESHRating"]:"";
                        $data[$i]["ConstructionMethods"]        =!empty($row["ConstructionMethods"])?$row["ConstructionMethods"]:"";
                        $data[$i]["EMP"]                        =!empty($row["EMP"])?$row["EMP"]:"";
                        $data[$i]["EQU"]                        =!empty($row["EQU"])?$row["EQU"]:"";
                        $data[$i]["EQV"]                        =!empty($row["EQV"])?$row["EQV"]:"";
                        $data[$i]["FRN"]                        =!empty($row["FRN"])?$row["FRN"]:"";
                        $data[$i]["GRS"]                        =!empty($row["GRS"])?$row["GRS"]:"";
                        $data[$i]["GW"]                         =!empty($row["GW"])?$row["GW"]:"";
                        $data[$i]["HRS"]                        =!empty($row["HRS"])?$row["HRS"]:"";
                        $data[$i]["INV"]                        =!empty($row["INV"])?$row["INV"]:"";
                        $data[$i]["LNM"]                        =!empty($row["LNM"])?$row["LNM"]:"";
                        $data[$i]["LSI"]                        =!empty($row["LSI"])?$row["LSI"]:"";
                        $data[$i]["NA"]                         =!empty($row["NA"])?$row["NA"]:"";
                        $data[$i]["NP"]                         =!empty($row["NP"])?$row["NP"]:"";
                        $data[$i]["PKC"]                        =!empty($row["PKC"])?$row["PKC"]:"";
                        $data[$i]["PKU"]                        =!empty($row["PKU"])?$row["PKU"]:"";
                        $data[$i]["RES"]                        =!empty($row["RES"])?$row["RES"]:"";
                        $data[$i]["RNT"]                        =!empty($row["RNT"])?$row["RNT"]:"";
                        $data[$i]["SIN"]                        =!empty($row["SIN"])?$row["SIN"]:"";
                        $data[$i]["TEXP"]                       =!empty($row["TEXP"])?$row["TEXP"]:"";
                        $data[$i]["TOB"]                        =!empty($row["TOB"])?$row["TOB"]:"";
                        $data[$i]["YRE"]                        =!empty($row["YRE"])?$row["YRE"]:"";
                        $data[$i]["YRS"]                        =!empty($row["YRS"])?$row["YRS"]:"";
                        $data[$i]["LES"]                        =!empty($row["LES"])?$row["LES"]:"";
                        $data[$i]["LIC"]                        =!empty($row["LIC"])?$row["LIC"]:"";
                        $data[$i]["LOC"]                        =!empty($row["LOC"])?$row["LOC"]:"";
                        $data[$i]["MTB"]                        =!empty($row["MTB"])?$row["MTB"]:"";
                        $data[$i]["RP"]                         =!empty($row["RP"])?$row["RP"]:"";
                        $data[$i]["LSZS"]                       =!empty($row["LSZS"])?$row["LSZS"]:"";
                        $data[$i]["AFH"]                        =!empty($row["AFH"])?$row["AFH"]:"";
                        $data[$i]["ASCC"]                       =!empty($row["ASC"])?$row["ASC"]:"";
                        $data[$i]["COO"]                        =!empty($row["COO"])?$row["COO"]:"";
                        $data[$i]["MGR"]                        =!empty($row["MGR"])?$row["MGR"]:"";
                        $data[$i]["NAS"]                        =!empty($row["NAS"])?$row["NAS"]:"";
                        $data[$i]["NOC"]                        =!empty($row["NOC"])?$row["NOC"]:"";
                        $data[$i]["NOS"]                        =!empty($row["NOS"])?$row["NOS"]:"";
                        $data[$i]["NOU"]                        =!empty($row["NOU"])?$row["NOU"]:"";
                        $data[$i]["OOC"]                        =!empty($row["OOC"])?$row["OOC"]:"";
                        $data[$i]["PKS"]                        =!empty($row["PKS"])?$row["PKS"]:"";
                        $data[$i]["REM"]                        =!empty($row["REM"])?$row["REM"]:"";
                        $data[$i]["SAA"]                        =!empty($row["SAA"])?$row["SAA"]:"";
                        $data[$i]["SPA"]                        =!empty($row["SPA"])?$row["SPA"]:"";
                        $data[$i]["STG"]                        =!empty($row["STG"])?$row["STG"]:"";
                        $data[$i]["STL"]                        =!empty($row["STL"])?$row["STL"]:"";
                        $data[$i]["TOF"]                        =!empty($row["TOF"])?$row["TOF"]:"";
                        $data[$i]["UFN"]                        =!empty($row["UFN"])?$row["UFN"]:"";
                        $data[$i]["WDW"]                        =!empty($row["WDW"])?$row["WDW"]:"";
                        $data[$i]["APH"]                        =!empty($row["APH"])?$row["APH"]:"";
                        $data[$i]["CMN"]                        =!empty($row["CMN"])?$row["CMN"]:"";
                        $data[$i]["CTD"]                        =!empty($row["CTD"])?$row["CTD"]:"";
                        $data[$i]["HOI"]                        =!empty($row["HOI"])?$row["HOI"]:"";
                        $data[$i]["PKG"]                        =!empty($row["PKG"])?$row["PKG"]:"";
                        $data[$i]["UNF"]                        =!empty($row["UNF"])?$row["UNF"]:"";
                        $data[$i]["STRS"]                       =!empty($row["STRS"])?$row["STRS"]:"";
                        $data[$i]["FUR"]                        =!empty($row["FUR"])?$row["FUR"]:"";
                        $data[$i]["MLT"]                        =!empty($row["MLT"])?$row["MLT"]:"";
                        $data[$i]["STO"]                        =!empty($row["STO"])?$row["STO"]:"";
                        $data[$i]["AFR"]                        =!empty($row["AFR"])?$row["AFR"]:"";
                        $data[$i]["APP"]                        =!empty($row["APP"])?$row["APP"]:"";
                        $data[$i]["MIF"]                        =!empty($row["MIF"])?$row["MIF"]:"";
                        $data[$i]["TMC"]                        =!empty($row["TMC"])?$row["TMC"]:"";
                        $data[$i]["TYP"]                        =!empty($row["TYP"])?$row["TYP"]:"";
                        $data[$i]["UTL"]                        =!empty($row["UTL"])?$row["UTL"]:"";
                        $data[$i]["ELE"]                        =!empty($row["ELE"])?$row["ELE"]:"";
                        $data[$i]["ESM"]                        =!empty($row["ESM"])?$row["ESM"]:"";
                        $data[$i]["GAS"]                        =!empty($row["GAS"])?$row["GAS"]:"";
                        $data[$i]["LVL"]                        =!empty($row["LVL"])?$row["LVL"]:"";
                        $data[$i]["QTR"]                        =!empty($row["QTR"])?$row["QTR"]:"";
                        $data[$i]["RD"]                         =!empty($row["RD"])?$row["RD"]:"";
                        $data[$i]["SDA"]                        =!empty($row["SDA"])?$row["SDA"]:"";
                        $data[$i]["SEC"]                        =!empty($row["SEC"])?$row["SEC"]:"";
                        $data[$i]["SEP"]                        =!empty($row["SEP"])?$row["SEP"]:"";
                        $data[$i]["SFA"]                        =!empty($row["SFA"])?$row["SFA"]:"";
                        $data[$i]["SLP"]                        =!empty($row["SLP"])?$row["SLP"]:"";
                        $data[$i]["SST"]                        =!empty($row["SST"])?$row["SST"]:"";
                        $data[$i]["SUR"]                        =!empty($row["SUR"])?$row["SUR"]:"";
                        $data[$i]["TER"]                        =!empty($row["TER"])?$row["TER"]:"";
                        $data[$i]["WRJ"]                        =!empty($row["WRJ"])?$row["WRJ"]:"";
                        $data[$i]["ZNR"]                        =!empty($row["ZNR"])?$row["ZNR"]:"";
                        $data[$i]["ATF"]                        =!empty($row["ATF"])?$row["ATF"]:"";
                        $data[$i]["DOC"]                        =!empty($row["DOC"])?$row["DOC"]:"";
                        $data[$i]["FTR"]                        =!empty($row["FTR"])?$row["FTR"]:"";
                        $data[$i]["GZC"]                        =!empty($row["GZC"])?$row["GZC"]:"";
                        $data[$i]["IMP"]                        =!empty($row["IMP"])?$row["IMP"]:"";
                        $data[$i]["RDI"]                        =!empty($row["RDI"])?$row["RDI"]:"";
                        $data[$i]["RS2"]                        =!empty($row["RS2"])?$row["RS2"]:"";
                        $data[$i]["TPO"]                        =!empty($row["TPO"])?$row["TPO"]:"";
                        $data[$i]["WTR"]                        =!empty($row["WTR"])?$row["WTR"]:"";
                        $data[$i]["AUCTION"]                    =!empty($row["AUCTION"])?$row["AUCTION"]:"";
                        $data[$i]["LotSizeSource"]              =!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
                        $data[$i]["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
                        $data[$i]["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";

                        /* New Fields */

                        $data[$i]['CAP']                         =!empty($row['CAP'])?$row['CAP']:'';
                        $data[$i]['ELEX']                         =!empty($row['ELEX'])?$row['ELEX']:'';
                        $data[$i]['EXP']                         =!empty($row['EXP'])?$row['EXP']:'';
                        $data[$i]['GAI']                         =!empty($row['GAI'])?$row['GAI']:'';
                        $data[$i]['GRM']                         =!empty($row['GRM'])?$row['GRM']:'';
                        $data[$i]['GSI']                         =!empty($row['GSI'])?$row['GSI']:'';
                        $data[$i]['GSP']                         =!empty($row['GSP'])?$row['GSP']:'';
                        $data[$i]['HET']                         =!empty($row['HET'])?$row['HET']:'';
                        $data[$i]['INS']                         =!empty($row['INS'])?$row['INS']:'';
                        $data[$i]['NCS']                         =!empty($row['NCS'])?$row['NCS']:'';
                        $data[$i]['NOI']                         =!empty($row['NOI'])?$row['NOI']:'';
                        $data[$i]['OTX']                         =!empty($row['OTX'])?$row['OTX']:'';
                        $data[$i]['SIB']                         =!empty($row['SIB'])?$row['SIB']:'';
                        $data[$i]['TEX']                         =!empty($row['TEX'])?$row['TEX']:'';
                        $data[$i]['TIN']                         =!empty($row['TIN'])?$row['TIN']:'';
                        $data[$i]['TSP']                         =!empty($row['TSP'])?$row['TSP']:'';
                        $data[$i]['UBG']                         =!empty($row['UBG'])?$row['UBG']:'';
                        $data[$i]['USP']                         =!empty($row['USP'])?$row['USP']:'';
                        $data[$i]['VAC']                         =!empty($row['VAC'])?$row['VAC']:'';
                        $data[$i]['WSG']                         =!empty($row['WSG'])?$row['WSG']:'';
                        $data[$i]['AMN']                         =!empty($row['AMN'])?$row['AMN']:'';
                        $data[$i]['LIT']                         =!empty($row['LIT'])?$row['LIT']:'';
                        $data[$i]['UN1']                         =!empty($row['UN1'])?$row['UN1']:'';
                        $data[$i]['BR1']                         =!empty($row['BR1'])?$row['BR1']:'';
                        $data[$i]['BA1']                         =!empty($row['BA1'])?$row['BA1']:'';
                        $data[$i]['SF1']                         =!empty($row['SF1'])?$row['SF1']:'';
                        $data[$i]['RN1']                         =!empty($row['RN1'])?$row['RN1']:'';
                        $data[$i]['FP1']                         =!empty($row['FP1'])?$row['FP1']:'';
                        $data[$i]['WD1']                         =!empty($row['WD1'])?$row['WD1']:'';
                        $data[$i]['RO1']                         =!empty($row['RO1'])?$row['RO1']:'';
                        $data[$i]['FG1']                         =!empty($row['FG1'])?$row['FG1']:'';
                        $data[$i]['DW1']                         =!empty($row['DW1'])?$row['DW1']:'';
                        $data[$i]['UN2']                         =!empty($row['UN2'])?$row['UN2']:'';
                        $data[$i]['BR2']                         =!empty($row['BR2'])?$row['BR2']:'';
                        $data[$i]['BA2']                         =!empty($row['BA2'])?$row['BA2']:'';
                        $data[$i]['SF2']                         =!empty($row['SF2'])?$row['SF2']:'';
                        $data[$i]['RN2']                         =!empty($row['RN2'])?$row['RN2']:'';
                        $data[$i]['FP2']                         =!empty($row['FP2'])?$row['FP2']:'';
                        $data[$i]['WD2']                         =!empty($row['WD2'])?$row['WD2']:'';
                        $data[$i]['RO2']                         =!empty($row['RO2'])?$row['RO2']:'';
                        $data[$i]['FG2']                         =!empty($row['FG2'])?$row['FG2']:'';
                        $data[$i]['DW2']                         =!empty($row['DW2'])?$row['DW2']:'';
                        $data[$i]['UN3']                         =!empty($row['UN3'])?$row['UN3']:'';
                        $data[$i]['BR3']                         =!empty($row['BR3'])?$row['BR3']:'';
                        $data[$i]['BA3']                         =!empty($row['BA3'])?$row['BA3']:'';
                        $data[$i]['SF3']                         =!empty($row['SF3'])?$row['SF3']:'';
                        $data[$i]['RN3']                         =!empty($row['RN3'])?$row['RN3']:'';
                        $data[$i]['FP3']                         =!empty($row['FP3'])?$row['FP3']:'';
                        $data[$i]['WD3']                         =!empty($row['WD3'])?$row['WD3']:'';
                        $data[$i]['RO3']                         =!empty($row['RO3'])?$row['RO3']:'';
                        $data[$i]['FG3']                         =!empty($row['FG3'])?$row['FG3']:'';
                        $data[$i]['DW3']                         =!empty($row['DW3'])?$row['DW3']:'';
                        $data[$i]['UN4']                         =!empty($row['UN4'])?$row['UN4']:'';
                        $data[$i]['BR4']                         =!empty($row['BR4'])?$row['BR4']:'';
                        $data[$i]['BA4']                         =!empty($row['BA4'])?$row['BA4']:'';
                        $data[$i]['SF4']                         =!empty($row['SF4'])?$row['SF4']:'';
                        $data[$i]['RN4']                         =!empty($row['RN4'])?$row['RN4']:'';
                        $data[$i]['FP4']                         =!empty($row['FP4'])?$row['FP4']:'';
                        $data[$i]['WD4']                         =!empty($row['WD4'])?$row['WD4']:'';
                        $data[$i]['RO4']                         =!empty($row['RO4'])?$row['RO4']:'';
                        $data[$i]['FG4']                         =!empty($row['FG4'])?$row['FG4']:'';
                        $data[$i]['DW4']                         =!empty($row['DW4'])?$row['DW4']:'';
                        $data[$i]['UN5']                         =!empty($row['UN5'])?$row['UN5']:'';
                        $data[$i]['BR5']                         =!empty($row['BR5'])?$row['BR5']:'';
                        $data[$i]['BA5']                         =!empty($row['BA5'])?$row['BA5']:'';
                        $data[$i]['SF5']                         =!empty($row['SF5'])?$row['SF5']:'';
                        $data[$i]['RN5']                         =!empty($row['RN5'])?$row['RN5']:'';
                        $data[$i]['FP5']                         =!empty($row['FP5'])?$row['FP5']:'';
                        $data[$i]['WD5']                         =!empty($row['WD5'])?$row['WD5']:'';
                        $data[$i]['RO5']                         =!empty($row['RO5'])?$row['RO5']:'';
                        $data[$i]['FG5']                         =!empty($row['FG5'])?$row['FG5']:'';
                        $data[$i]['DW5']                         =!empty($row['DW5'])?$row['DW5']:'';
                        $data[$i]['UN6']                         =!empty($row['UN6'])?$row['UN6']:'';
                        $data[$i]['BR6']                         =!empty($row['BR6'])?$row['BR6']:'';
                        $data[$i]['BA6']                         =!empty($row['BA6'])?$row['BA6']:'';
                        $data[$i]['SF6']                         =!empty($row['SF6'])?$row['SF6']:'';
                        $data[$i]['RN6']                         =!empty($row['RN6'])?$row['RN6']:'';
                        $data[$i]['FP6']                         =!empty($row['FP6'])?$row['FP6']:'';
                        $data[$i]['WD6']                         =!empty($row['WD6'])?$row['WD6']:'';
                        $data[$i]['RO6']                         =!empty($row['RO6'])?$row['RO6']:'';
                        $data[$i]['FG6']                         =!empty($row['FG6'])?$row['FG6']:'';
                        $data[$i]['DW6']                         =!empty($row['DW6'])?$row['DW6']:'';
                        $data[$i]['AMP']                         =!empty($row['AMP'])?$row['AMP']:'';
                        $data[$i]['AVP']                         =!empty($row['AVP'])?$row['AVP']:'';
                        $data[$i]['BON']                         =!empty($row['BON'])?$row['BON']:'';
                        $data[$i]['CHT']                         =!empty($row['CHT'])?$row['CHT']:'';
                        $data[$i]['CSP']                         =!empty($row['CSP'])?$row['CSP']:'';
                        $data[$i]['DLT']                         =!empty($row['DLT'])?$row['DLT']:'';
                        $data[$i]['ENV']                         =!empty($row['ENV'])?$row['ENV']:'';
                        $data[$i]['EXA']                         =!empty($row['EXA'])?$row['EXA']:'';
                        $data[$i]['FAC']                         =!empty($row['FAC'])?$row['FAC']:'';
                        $data[$i]['NNN']                         =!empty($row['NNN'])?$row['NNN']:'';
                        $data[$i]['OSF']                         =!empty($row['OSF'])?$row['OSF']:'';
                        $data[$i]['PAD']                         =!empty($row['PAD'])?$row['PAD']:'';
                        $data[$i]['SIZ']                         =!empty($row['SIZ'])?$row['SIZ']:'';
                        $data[$i]['STF']                         =!empty($row['STF'])?$row['STF']:'';
                        $data[$i]['TAV']                         =!empty($row['TAV'])?$row['TAV']:'';
                        $data[$i]['TRI']                         =!empty($row['TRI'])?$row['TRI']:'';
                        $data[$i]['TSF']                         =!empty($row['TSF'])?$row['TSF']:'';
                        $data[$i]['VAI']                         =!empty($row['VAI'])?$row['VAI']:'';
                        $data[$i]['VAL']                         =!empty($row['VAL'])?$row['VAL']:'';
                        $data[$i]['WSF']                         =!empty($row['WSF'])?$row['WSF']:'';
                        $data[$i]['YVA']                         =!empty($row['YVA'])?$row['YVA']:'';
                        $data[$i]['CFE']                         =!empty($row['CFE'])?$row['CFE']:'';
                        $data[$i]['LDG']                         =!empty($row['LDG'])?$row['LDG']:'';
                        $data[$i]['TN1']                         =!empty($row['TN1'])?$row['TN1']:'';
                        $data[$i]['LX1']                         =!empty($row['LX1'])?$row['LX1']:'';
                        $data[$i]['NN1']                         =!empty($row['NN1'])?$row['NN1']:'';
                        $data[$i]['US1']                         =!empty($row['US1'])?$row['US1']:'';
                        $data[$i]['TN2']                         =!empty($row['TN2'])?$row['TN2']:'';
                        $data[$i]['LX2']                         =!empty($row['LX2'])?$row['LX2']:'';
                        $data[$i]['NN2']                         =!empty($row['NN2'])?$row['NN2']:'';
                        $data[$i]['US2']                         =!empty($row['US2'])?$row['US2']:'';
                        $data[$i]['TN3']                         =!empty($row['TN3'])?$row['TN3']:'';
                        $data[$i]['LX3']                         =!empty($row['LX3'])?$row['LX3']:'';
                        $data[$i]['NN3']                         =!empty($row['NN3'])?$row['NN3']:'';
                        $data[$i]['US3']                         =!empty($row['US3'])?$row['US3']:'';
                        $data[$i]['TN4']                         =!empty($row['TN4'])?$row['TN4']:'';
                        $data[$i]['LX4']                         =!empty($row['LX4'])?$row['LX4']:'';
                        $data[$i]['NN4']                         =!empty($row['NN4'])?$row['NN4']:'';
                        $data[$i]['US4']                         =!empty($row['US4'])?$row['US4']:'';
                        $data[$i]['TN5']                         =!empty($row['TN5'])?$row['TN5']:'';
                        $data[$i]['LX5']                         =!empty($row['LX5'])?$row['LX5']:'';
                        $data[$i]['NN5']                         =!empty($row['NN5'])?$row['NN5']:'';
                        $data[$i]['US5']                         =!empty($row['US5'])?$row['US5']:'';
                        $data[$i]['TN6']                         =!empty($row['TN6'])?$row['TN6']:'';
                        $data[$i]['LX6']                         =!empty($row['LX6'])?$row['LX6']:'';
                        $data[$i]['NN6']                         =!empty($row['NN6'])?$row['NN6']:'';
                        $data[$i]['US6']                         =!empty($row['US6'])?$row['US6']:'';
                        $data[$i]['ACC']                         =!empty($row['ACC'])?$row['ACC']:'';
                        $data[$i]['BCC']                         =!empty($row['BCC'])?$row['BCC']:'';
                        $data[$i]['BRI']                         =!empty($row['BRI'])?$row['BRI']:'';
                        $data[$i]['BSZ']                         =!empty($row['BSZ'])?$row['BSZ']:'';
                        $data[$i]['CCC']                         =!empty($row['CCC'])?$row['CCC']:'';
                        $data[$i]['CRI']                         =!empty($row['CRI'])?$row['CRI']:'';
                        $data[$i]['EQI']                         =!empty($row['EQI'])?$row['EQI']:'';
                        $data[$i]['LCC']                         =!empty($row['LCC'])?$row['LCC']:'';
                        $data[$i]['IRRC']                         =!empty($row['IRRC'])?$row['IRRC']:'';
                        $data[$i]['PSZ']                         =!empty($row['PSZ'])?$row['PSZ']:'';
                        $data[$i]['SSZ']                         =!empty($row['SSZ'])?$row['SSZ']:'';
                        $data[$i]['TAC']                         =!empty($row['TAC'])?$row['TAC']:'';
                        $data[$i]['VCC']                         =!empty($row['VCC'])?$row['VCC']:'';
                        $data[$i]['BFE']                         =!empty($row['BFE'])?$row['BFE']:'';
                        $data[$i]['BTP']                         =!empty($row['BTP'])?$row['BTP']:'';
                        $data[$i]['EQP']                         =!empty($row['EQP'])?$row['EQP']:'';
                        $data[$i]['FEN']                         =!empty($row['FEN'])?$row['FEN']:'';
                        $data[$i]['FTP']                         =!empty($row['FTP'])?$row['FTP']:'';
                        $data[$i]['IRS']                         =!empty($row['IRS'])?$row['IRS']:'';
                        $data[$i]['ITP']                         =!empty($row['ITP'])?$row['ITP']:'';
                        $data[$i]['LEQ']                         =!empty($row['LEQ'])?$row['LEQ']:'';
                        $data[$i]['LTG']                         =!empty($row['LTG'])?$row['LTG']:'';
                        $data[$i]['LTP']                         =!empty($row['LTP'])?$row['LTP']:'';
                        $data[$i]['OUT1']                         =!empty($row['OUT'])?$row['OUT']:'';
                        $data[$i]['STP']                         =!empty($row['STP'])?$row['STP']:'';
                        $data[$i]['ELEV']                         =!empty($row['ELEV'])?$row['ELEV']:'';
                        $data[$i]['AGR']                         =!empty($row['AGR'])?$row['AGR']:'';
                        $data[$i]['LNI']                         =!empty($row['LNI'])?$row['LNI']:'';
                        $data[$i]['MFY']                         =!empty($row['MFY'])?$row['MFY']:'';
                        $data[$i]['NOH']                         =!empty($row['NOH'])?$row['NOH']:'';
                        $data[$i]['PAS']                         =!empty($row['PAS'])?$row['PAS']:'';
                        $data[$i]['PRK']                         =!empty($row['PRK'])?$row['PRK']:'';
                        $data[$i]['SKR']                         =!empty($row['SKR'])?$row['SKR']:'';
                        $data[$i]['SPR']                         =!empty($row['SPR'])?$row['SPR']:'';
                        $data[$i]['UCS']                         =!empty($row['UCS'])?$row['UCS']:'';
                        $data[$i]['ANC']                         =!empty($row['ANC'])?$row['ANC']:'';
                        $data[$i]['MHF']                         =!empty($row['MHF'])?$row['MHF']:'';
                        $data[$i]['OTR']                         =!empty($row['OTR'])?$row['OTR']:'';
                        $data[$i]['PKA']                         =!empty($row['PKA'])?$row['PKA']:'';
                        $data[$i]['SRI']                         =!empty($row['SRI'])?$row['SRI']:'';

                        /* END */

                        $data[$i]['modified_date']              = date('Y-m-d h:i:s');
                        $data[$i]['status']                     = 1;
                        $data[$i]['mls_type_id']                ='1';
                        
                        if(!empty($data[$i]['LAT']) && !empty($data[$i]['LONGI']))                        
                        {   
                            //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                            $data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT("POINT(", '.$data[$i]["LAT"].'," ",'.$data[$i]["LONGI"].',")"))';
                        }
                        
                        $fields=array('ID','LN');
                        $match=array('LN'=>!empty($row['LN'])?$row['LN']:''); 
                        //$res=$this->mls_model->select_records3('',$match,'','=');
                        $res=$this->mls_model->select_records3('',$match,'','=','','','','','','',$this->mls_staging_db,'','','nwmls_mls_property_list_master');
                        
                        if(empty($res))
                        {
                                $data[$i]['created_date']       = date('Y-m-d h:i:s');
                                /*if(count($data) >= 100)
                                {
                                        $this->mls_model->insert_record3($data,$this->mls_staging_db);
                                        //$this->mls_model->insert_record3($data,$this->mls_master_db);
                                        $i = 0;
                                        unset($data);
                                }*/
                                $i++;
                                //$id=$this->mls_model->insert_record3($data);
                        }
                        else
                        {
                                $cdata[$j] = $data[$i];
                                unset($data[$i]);
                                /*if(count($cdata) >= 100)
                                {
                                        $this->mls_model->update_record3($cdata,$this->mls_staging_db);
                                        //$this->mls_model->update_record3($cdata,$this->mls_master_db);
                                        //pr($cdata); exit;
                                        $j = 0;
                                        unset($cdata);
                                }*/
                                $j++;
                                //$id=$this->mls_model->update_record3($data);
                        }
                    }
                     //Insert more than 100
                    if(!empty($data)  && count($data) > 0)
                    {
                        $i = 0;
                        foreach ($data as $row) 
                        {
                            foreach ($row as $key=>$value) 
                            {
                                $idata[$i][$key]=$value;
                                
                                if(count($idata[$i]) == count($row))
                                {
                                    if(count($idata) >= 100)
                                    {
                                        $this->mls_model->insert_record3($data,$this->mls_staging_db);
                                        $i = 0; 
                                        unset($idata);
                                    }
                                }
                            }
                            $i++;
                        }
                        unset($data);
                    }
                    //Update more than 100
                    if(!empty($cdata) && count($cdata) > 0)
                    {
                        $i = 0;
                        foreach ($cdata as $row) 
                        {
                            foreach ($row as $key=>$value) 
                            {
                                $udata[$i][$key]=$value;
                                 
                                if(count($udata[$i]) == count($row))
                                {
                                  if(count($udata) >= 100)
                                    {  
                                        $this->mls_model->update_record3($cdata,$this->mls_staging_db);
                                        $i = 0;
                                        unset($udata);
                                    }  
                                }
                            }
                            $i++;
                        }
                        unset($cdata);                   
                    }
                    if(!empty($idata) && count($idata) > 0)
                    {
                        $this->mls_model->insert_record3($idata,$this->mls_staging_db);
                        unset($idata);   
                    }
                    if(!empty($udata) && count($udata) > 0)
                    {
                        $this->mls_model->update_record3($udata,$this->mls_staging_db);
                        unset($udata);  
                    }
                }
            //}
        //}
        // Insert to mls database
        

        /*$field_data_cron = array(
                            'cron_name'=>'retrieve_listing_data_end',
                            'p_type'=>$propty_type,
                            'begin_date'=>$begin_date,
                            'curr_date'=>$curr_date,
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        //Remove cron
        if(!empty($cron_time))
        {    
            $cron_field=explode('-',$cron_time);
            $output = shell_exec('crontab -l');
            //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
            
            $url = base_url().'superadmin/mls_import/retrieve_listing_data/'.$begin_date.'/'.$curr_date.'/'.$propty_type.'/'.$cron_time;
            $minute  = $cron_field[0];
            $hour    = $cron_field[1];
            $day     = $cron_field[2];
            $month   = $cron_field[3];
            echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
            $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
            
            //Copy cron tab and remove string
            if (strstr($output, $cronjob)) 
            {
               echo 'found';
               $newcron = str_replace($cronjob,"",$output);
               file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
               echo exec('crontab ../../../../tmp/crontab.txt'); 
            } 
            else 
            {
               echo 'not found';
            }
        }

        echo 'done';
        echo microtime()-$starttime;
        //redirect('superadmin/mls/add_record');
    }

    /*
    @Description: Function Add area community data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area community data
    @Date: 20-02-2015
    */
   
    public function retrieve_area_community_data()
    {
        //Insert cron start data
        $field_data_cron = array(
                            'cron_name'=>'retrieve_area_community_data_start',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
        set_time_limit(0);
        
        $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
        $XMLQuery .="<Message>";
        $XMLQuery .="<Head>";
        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
        $XMLQuery .="</Head>";
        $XMLQuery .="<Body>";
        $XMLQuery .="<Query>";
        $XMLQuery .="<MLS>NWMLS</MLS>";
        //$XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
        //$XMLQuery .="<Status>A</Status>";
        //$XMLQuery .="<BeginDate>2013-05-07T23:00:00</BeginDate>";
        //$XMLQuery .="<EndDate>2013-11-07T00:00:00</EndDate>";
        $XMLQuery .="</Query>";
        $XMLQuery .="<Filter></Filter>";
        $XMLQuery .="</Body>";
        $XMLQuery .="</Message>";
        $XMLQuery .="</EverNetQuerySpecification>";
        $params = array ('v_strXmlQuery' => $XMLQuery);
        $nodelist = $client->RetrieveAreaCommunityData($params); 
        $accessnodelist = $nodelist->RetrieveAreaCommunityDataResult;
        $xml_result = new SimpleXMLElement($accessnodelist);
        $json = json_encode($xml_result);
        $mls_data = json_decode($json,TRUE);
        
        foreach($mls_data as $key=>$value)
        {
            $propertytype=$key;
            $property_data=$mls_data[$propertytype];
            
            foreach($property_data as $row)
            {
                //$data['property_type']        =!empty($type['name'])?$type['name']:'';
                $data['area']               =!empty($row['Area'])?$row['Area']:'';
                $data['community']          =!empty($row['Community'])?$row['Community']:'';
                $data['modified_date']      =date('Y-m-d h:i:s');
                $fields=array('id');
                //Get area community data
                $match=array('area'=>!empty($row['Area'])?$row['Area']:'','community'=>!empty($row['Community'])?$row['Community']:'');
                $res=$this->mls_model->select_records2('',$match,'','=','','','','','','',$this->mls_staging_db);
                if(empty($res))
                {
                    $data['created_date']       =date('Y-m-d h:i:s');
                    $id=$this->obj->insert_record2($data,$this->mls_staging_db);
                }
                else
                {
                    $id=$this->obj->update_record2($data,$this->mls_staging_db);
                }
            }
            
        }
        //Insert cron end data
        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }
        echo 'done';    
        //redirect('superadmin/mls/add_record');
    }
    /*
    @Description: Function Add area community data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area community data
    @Date: 20-02-2015
    */
   
    public function retrieve_image_data()
    {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
        $this->load->model('imageupload_model');
        $num    = $this->uri->segment(4);
        $offset = $this->uri->segment(5);   
        //Insert cron start data
        /*$field_data_cron = array(
            'cron_name' => 'retrieve_image_data_end',
            'begin_date'=> $num,
            'curr_date' => $offset,
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        $field_data_cron = array(
                            'cron_name'=>'retrieve_image_data',
                            'begin_date'=>$num,
                            'curr_date'=>$offset,
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        //$this->mls_model->insert_cron_test($field_data_cron);
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

        //$hour = $this->uri->segment(6);
        //$day  = $this->uri->segment(7);
        //$num=20;
        //$offset=1;
        set_time_limit(0);
        //Get mls property
        $fields = array('ID','LN');
        //$match  = array('LN'=>!empty($row['LN'])?$row['LN']:'');
        //$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
        $res    = $this->obj->select_records3($fields,'','','=','',$num,$offset,'','','',$this->mls_staging_db,'','','nwmls_mls_property_list_master');
        //echo $this->db->last_query();exit;
        if(!empty($res))
        {
            foreach($res as $row)   
            {
                $listingID=$row['LN'];
                //$listingID='29133407';
                
                try {
                    /*---------------------- Get Imgae data from mls image services-------------------*/
                    $client=new SoapClient('http://images.idx.nwmls.com/imageservice/imagequery.asmx?WSDL');
                    $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                    $XMLQuery .="<ImageQuery xmlns='NWMLS:EverNet:ImageQuery:1.0'>";
                    $XMLQuery .="<Auth>";
                    $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                    $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                    $XMLQuery .="</Auth>";
                    $XMLQuery .="<Query>";
                    $XMLQuery .=" <ByListingNumber>".$listingID."</ByListingNumber>";
                    $XMLQuery .=" </Query>";
                    $XMLQuery .="<Results>";
                    $XMLQuery .="<Schema>ImageData_1.0</Schema>";
                    $XMLQuery .="</Results>";
                    $XMLQuery .="</ImageQuery>";
                
                    // $params = array ('v_strXmlQuery' => $XMLQuery);
                    $params = array ('query' => $XMLQuery);
                    $result = $client->RetrieveImages ( $params );
                    $xml = simplexml_load_string ( $result->RetrieveImagesResult );
                    $json = json_encode ( $xml );
                    $mls_data = json_decode ( $json, TRUE );
                    /*--------------------------------- End -----------------------------*/
                    
                    /*---------------------- Get Imgae data from mls evernet services-------------------*/
                    
                    //Get Image data
    
                    $client1=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                    $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                    $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                    $XMLQuery .="<Message>";
                    $XMLQuery .="<Head>";
                    $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                    $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                    $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                    $XMLQuery .="</Head>";
                    $XMLQuery .="<Body>";
                    $XMLQuery .="<Query>";
                    $XMLQuery .="<MLS>NWMLS</MLS>";
                    $XMLQuery .="<ListingNumber>".$listingID."</ListingNumber>";
                    $XMLQuery .="</Query>";
                    $XMLQuery .="<Filter></Filter>";
                    $XMLQuery .="</Body>";
                    $XMLQuery .="</Message>";
                    $XMLQuery .="</EverNetQuerySpecification>";
                    $params = array ('v_strXmlQuery' => $XMLQuery);
                    $nodelist = $client1->RetrieveImageData($params); 
                    $accessnodelist = $nodelist->RetrieveImageDataResult;
                    $xml_result = new SimpleXMLElement($accessnodelist);
                    $json = json_encode ( $xml_result );
                    $mls_data1 = json_decode ( $json, TRUE );
                    
                    /*--------------------------------- End -----------------------------*/
                    
                    
                    if(!empty($mls_data) && !empty($mls_data1))
                    {
                        $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
                            'username' => $this->config->item('cdn_username'),
                            'apiKey'   => $this->config->item('cdn_api_key')
                        ));

                        // 2. Obtain an Object Store service object from the client.
                        $objectStoreService = $client->objectStoreService(null, 'ORD');

                        // 3. Get container.
                        $container = $objectStoreService->getContainer('livewire');
                        //$container = $container->createContainer('images');
                        // 4.Upload on cdn server
                        if(!empty($mls_data['Images']['Image']['ImageId']))
                        {
                            $mls_data['Images']['Image'][0]=array(
                                'ImageId'=>$mls_data['Images']['Image']['ImageId'],
                                'ImageOrder'=>$mls_data['Images']['Image']['ImageOrder'],
                                'UploadDt'=>$mls_data['Images']['Image']['UploadDt'],
                                'BLOB'=>$mls_data['Images']['Image']['BLOB'],
                            );
                            unset($mls_data['Images']['Image']['ImageId']);
                            unset($mls_data['Images']['Image']['ImageOrder']);
                            unset($mls_data['Images']['Image']['UploadDt']);
                            unset($mls_data['Images']['Image']['BLOB']);
                        }
                        if(!empty($mls_data['Images']['Image']))
                        {
                            for($i=0;$i<count($mls_data['Images']['Image']);$i++)
                            {
                                
                                $img=$mls_data['Images']['Image'][$i]['BLOB'];
                                $img_order=$mls_data['Images']['Image'][$i]['ImageOrder'];
                                if($img_order == 0)
                                {$name = $listingID.".jpg";}
                                else{
                                    $name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_" .$img_order. ".jpg";
                                }
                                
                                //Upload image
                                $container->uploadObject("/property_image/".$listingID."/original/".$name, base64_decode($img));
                                $object = $container->getObject("/property_image/".$listingID."/original/".$name);

                                $cdnUrl = $object->getPublicUrl();
                                
                                $upload_path=$this->config->item('temp_path');
                                if(!empty($cdnUrl))
                                {
                                    //Create thumbnail
                                    $imagedata2 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'b'.$name,970,'image/jpeg');
                                    $imagedata1 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'m'.$name,360,'image/jpeg');
                                    $imagedata  = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'s'.$name,150,'image/jpeg');

                                    /*echo "BIG - ".$imagedata2;
                                    echo "<br>MEDIUM - ".$imagedata1;
                                    echo "<br>SMALL - ".$imagedata;exit;*/

                                    if(!empty($imagedata2))
                                    {
                                        $container->uploadObject("/property_image/".$listingID."/big/".$name, base64_decode($imagedata2));
                                        $object = $container->getObject("/property_image/".$listingID."/big/".$name);

                                        $cdnUrl3 = $object->getPublicUrl();
                                        
                                    }
                                    if(!empty($imagedata1))
                                    {
                                        $container->uploadObject("/property_image/".$listingID."/medium/".$name, base64_decode($imagedata1));
                                        $object = $container->getObject("/property_image/".$listingID."/medium/".$name);

                                        $cdnUrl2 = $object->getPublicUrl();
                                        
                                    }
                                    if(!empty($imagedata))
                                    {
                                        $container->uploadObject("/property_image/".$listingID."/small/".$name, base64_decode($imagedata));
                                        $object = $container->getObject("/property_image/".$listingID."/small/".$name);

                                        $cdnUrl1 = $object->getPublicUrl();
                                    }
                                    
                                }


                                $row1  = $mls_data['Images']['Image'];
                                $row2  = $mls_data1['image'];
                                //$data['property_id']      = !empty($row['ID'])?$row['ID']:'';
                                if(!empty($cdnUrl1) && !empty($cdnUrl2))
                                {
                                    if(!empty($mls_data['Images']['Image'][1]['ImageId']) && isset($mls_data['Images']['Image'][1]['ImageId']) >0)
                                    {
                                        $rd1 =$row1[$i];
                                        $rd2 =$row2[$i];
                                    }
                                    else
                                    {
                                        $rd1 =$row1;
                                        $rd2 =$row2;        
                                    }
                                    $data['listing_number']     = !empty($row['LN'])?$row['LN']:'';
                                    $data['image_url']          = htmlspecialchars($cdnUrl);
                                    $data['image_big_url']      = htmlspecialchars($cdnUrl3);
                                    $data['image_medium_url']   = htmlspecialchars($cdnUrl1);
                                    $data['image_small_url']    = htmlspecialchars($cdnUrl2);

                                    $data['image_id']           = !empty($rd1['ImageId'])?$rd1['ImageId']:'';
                                    $data['upload_date']        = !empty($rd1['UploadDt'])?$rd1['UploadDt']:'';
                                    $data['image_height']       = !empty($rd2['PictureHeight'])?$rd2['PictureHeight']:'';
                                    $data['image_width']        = !empty($rd2['PictureWidth'])?$rd2['PictureWidth']:'';
                                    $data['image_desc']         = !empty($rd2['PictureDescription'])?$rd2['PictureDescription']:'';
                                    $data['last_modified_date'] = !empty($rd2['LastModifiedDateTime'])?$rd2['LastModifiedDateTime']:'';
                                    $data['modified_date']      = date('Y-m-d h:i:s');
                                    //pr($data);
                                    $fields=array('id');
                                    $match=array('Image_id'=>!empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'');
                                    $res=$this->mls_model->select_records4('',$match,'','=','','','','','','',$this->mls_staging_db);
                                    //file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));
                                    //echo $this->db->last_query();
                                    $data['image_name']     = !empty($name)?$name:'';
                                    if(empty($res))
                                    {
                                        $data['created_date']   = date('Y-m-d h:i:s');
                                        
                                        $id=$this->obj->insert_record4($data,$this->mls_staging_db);
                                        //echo $this->db->last_query();
                                    }
                                    else
                                    {
                                        $id=$this->obj->update_record4($data,$this->mls_staging_db);
                                        //echo $this->db->last_query();
                                    }
                                }
                                //exit;
							}
						}
					}
                    else
                    {
                        if(empty($mls_data) && empty($mls_data1))
                        {
                            $field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'ISES',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
                        }
                        elseif(empty($mls_data))
                        {
                            $field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'IS',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
                        }
                        else
                        {
                            $field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'ES',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
                        }
                    }
				} catch(DynamoDbException $e) {
					//echo 'The item could not be retrieved.';
				}
                echo 'Successfully Inserted.';
                 //exit;        
            }
            //Remove cron
            /*$datetime = date('Y-m-d h:i:s');
            $output = shell_exec('crontab -l');
            $month = date("m",strtotime($datetime)); 
            $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$num.'/'.$offset.'/'.$hour.'/'.$day;
            $cronjob = ('0 '.$hour.' '.$day.' '.$month.' * curl '.$url);
            //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');

            if (strstr($output, $cronjob)) 
            {
               echo 'found';
            } 
            else 
            {
               echo 'not found';
            }
            //Copy cron tab and remove string
            $newcron = str_replace($cronjob,"",$output);
            file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
            echo exec('crontab ../../../../tmp/crontab.txt');      */ 
        }
        //Insert cron start data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_image_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        echo 'done';
    }

    /*
    @Description: Function Add area community data
    @Author: Nishit Modi
    @Input: - 
    @Output: - insert image data changed logic
    @Date: 20-02-2015
    */
   
   	public function retrieve_image_data_new()
    {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
        $this->load->model('imageupload_model');

        $cron_id    = $this->uri->segment(4);

        $img_counter_data = $this->mls_model->get_image_counter($cron_id);
        //pr($img_counter_data);exit;

        if(!empty($img_counter_data) && count($img_counter_data) > 0)
        {
            $num    = $img_counter_data[0]['no_of_mls_id'];
            $offset = $img_counter_data[0]['start_mls_id'];

            if($offset >= $img_counter_data[0]['end_mls_id'])
                exit;

            $ins_data = array(
                            'start_mls_id' => ($offset+$num)
                        );
        
            $this->mls_model->update_image_counter($cron_id,$ins_data);

            //exit;

            //$num    = $this->uri->segment(4);
            //$offset = $this->uri->segment(5);   

            //Insert cron start data
            /*$field_data_cron = array(
                'cron_name' => 'retrieve_image_data_end',
                'begin_date'=> $num,
                'curr_date' => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $this->mls_model->insert_cron_test($field_data_cron);*/

            $field_data_cron = array(
                                'cron_name'=>'retrieve_image_data',
                                'begin_date'=>$num,
                                'curr_date'=>$offset,
                                'created_date'=>date('Y-m-d H:i:s')
                                );
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

            //$hour = $this->uri->segment(6);
            //$day  = $this->uri->segment(7);
            //$num=20;
            //$offset=1;
            set_time_limit(0);
            //Get mls property
            $fields = array('ID','LN');
            //$match  = array('LN'=>!empty($row['LN'])?$row['LN']:'');
            //$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            $res    = $this->obj->select_records3($fields,'','','=','',$num,$offset,'','','',$this->mls_staging_db,'','','nwmls_mls_property_list_master');
           
            //echo $this->db->last_query();exit;
            if(!empty($res))
            {
                foreach($res as $row)   
                {
                    $listingID=$row['LN'];

                    /* Test 1 */

                    /*$field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_issues',
                                'p_type'=>'1',
                                'LN'=>$listingID,
                                'created_date'=>date('Y-m-d H:i:s')
                                );
                    
                    //$this->mls_model->insert_cron_test($field_data_cron);
                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                    ////////////

                    //$listingID='29133407';
                    
                    try {
                        /*---------------------- Get Imgae data from mls image services-------------------*/
                        $client=new SoapClient('http://images.idx.nwmls.com/imageservice/imagequery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<ImageQuery xmlns='NWMLS:EverNet:ImageQuery:1.0'>";
                        $XMLQuery .="<Auth>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="</Auth>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .=" <ByListingNumber>".$listingID."</ByListingNumber>";
                        $XMLQuery .=" </Query>";
                        $XMLQuery .="<Results>";
                        $XMLQuery .="<Schema>ImageData_1.0</Schema>";
                        $XMLQuery .="</Results>";
                        $XMLQuery .="</ImageQuery>";
                    
                        // $params = array ('v_strXmlQuery' => $XMLQuery);
                        $params = array ('query' => $XMLQuery);
                        $result = $client->RetrieveImages ( $params );
                        $xml = simplexml_load_string ( $result->RetrieveImagesResult );
                        $json = json_encode ( $xml );
                        $mls_data = json_decode ( $json, TRUE );
                        /*--------------------------------- End -----------------------------*/
                        
                        /*---------------------- Get Imgae data from mls evernet services-------------------*/
                        
                        //Get Image data
        
                        $client1=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                        $XMLQuery .="<Message>";
                        $XMLQuery .="<Head>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                        $XMLQuery .="</Head>";
                        $XMLQuery .="<Body>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .="<MLS>NWMLS</MLS>";
                        $XMLQuery .="<ListingNumber>".$listingID."</ListingNumber>";
                        $XMLQuery .="</Query>";
                        $XMLQuery .="<Filter></Filter>";
                        $XMLQuery .="</Body>";
                        $XMLQuery .="</Message>";
                        $XMLQuery .="</EverNetQuerySpecification>";
                        $params = array ('v_strXmlQuery' => $XMLQuery);
                        $nodelist = $client1->RetrieveImageData($params); 
                        $accessnodelist = $nodelist->RetrieveImageDataResult;
                        $xml_result = new SimpleXMLElement($accessnodelist);
                        $json = json_encode ( $xml_result );
                        $mls_data1 = json_decode ( $json, TRUE );
                        
                        /*--------------------------------- End -----------------------------*/

                        //pr($mls_data);exit;

                        if(!empty($mls_data) && !empty($mls_data1))
                        {
                            /* Test 2 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
                                'username' => $this->config->item('cdn_username'),
                                'apiKey'   => $this->config->item('cdn_api_key')
                            ));

                            // 2. Obtain an Object Store service object from the client.
                            $objectStoreService = $client->objectStoreService(null, 'ORD');

                            // 3. Get container.
                            $container = $objectStoreService->getContainer('livewire');
                            //$container = $container->createContainer('images');

                            /* Test 2-x */

                             /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2-x',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s'),
                                        'comments'=>"hi".$mls_data['Images']
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/



                            ///////////////

                            // 4.Upload on cdn server
                            if(!empty($mls_data['Images']['Image']['ImageId']))
                            {

                                /* Test 2-x1 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                $mls_data['Images']['Image'][0]=array(
                                    'ImageId'=>$mls_data['Images']['Image']['ImageId'],
                                    'ImageOrder'=>$mls_data['Images']['Image']['ImageOrder'],
                                    'UploadDt'=>$mls_data['Images']['Image']['UploadDt'],
                                    'BLOB'=>$mls_data['Images']['Image']['BLOB'],
                                );
                                unset($mls_data['Images']['Image']['ImageId']);
                                unset($mls_data['Images']['Image']['ImageOrder']);
                                unset($mls_data['Images']['Image']['UploadDt']);
                                unset($mls_data['Images']['Image']['BLOB']);
                            }
                            if(!empty($mls_data['Images']['Image']))
                            {

                                /* Test 2-x2 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x2',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                for($i=0;$i<count($mls_data['Images']['Image']);$i++)
                                {
                                    /* Test 2-1 */

                                     /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                    //$this->mls_model->insert_cron_test($field_data_cron);
                                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                    ////////////

                                    $img=$mls_data['Images']['Image'][$i]['BLOB'];
                                    $img_order=$mls_data['Images']['Image'][$i]['ImageOrder'];
                                    if($img_order == 0)
                                    {$name = $listingID.".jpg";}
                                    else{
                                        $name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_" .$img_order. ".jpg";
                                    }
                                    
                                    //Upload image
                                    $container->uploadObject("/property_image/".$listingID."/original/".$name, base64_decode($img));
                                    $object = $container->getObject("/property_image/".$listingID."/original/".$name);

                                    $cdnUrl = $object->getPublicUrl();
                                    
                                    $upload_path=$this->config->item('temp_path');
                                    if(!empty($cdnUrl))
                                    {
                                        
                                        /* Test 2-2 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-2',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        //Create thumbnail
                                        $imagedata2 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'b'.$name,970,'image/jpeg');
                                        $imagedata1 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'m'.$name,360,'image/jpeg');
                                        $imagedata  = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'s'.$name,150,'image/jpeg');

                                        /*echo "BIG - ".$imagedata2;
                                        echo "<br>MEDIUM - ".$imagedata1;
                                        echo "<br>SMALL - ".$imagedata;exit;*/

                                        if(!empty($imagedata2))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/big/".$name, base64_decode($imagedata2));
                                            $object = $container->getObject("/property_image/".$listingID."/big/".$name);

                                            $cdnUrl3 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata1))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/medium/".$name, base64_decode($imagedata1));
                                            $object = $container->getObject("/property_image/".$listingID."/medium/".$name);

                                            $cdnUrl2 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/small/".$name, base64_decode($imagedata));
                                            $object = $container->getObject("/property_image/".$listingID."/small/".$name);

                                            $cdnUrl1 = $object->getPublicUrl();
                                        }
                                        
                                    }


                                    $row1  = $mls_data['Images']['Image'];
                                    $row2  = $mls_data1['image'];
                                    //$data['property_id']      = !empty($row['ID'])?$row['ID']:'';
                                    if(!empty($cdnUrl1) && !empty($cdnUrl2))
                                    {
                                        
                                        /* Test 2-3 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-3',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        if(!empty($mls_data['Images']['Image'][1]['ImageId']) && isset($mls_data['Images']['Image'][1]['ImageId']) >0)
                                        {
                                            $rd1 =$row1[$i];
                                            $rd2 =$row2[$i];
                                        }
                                        else
                                        {
                                            $rd1 =$row1;
                                            $rd2 =$row2;        
                                        }
                                        $data['listing_number']     = !empty($row['LN'])?$row['LN']:'';
                                        $data['image_url']          = htmlspecialchars($cdnUrl);
                                        $data['image_big_url']      = htmlspecialchars($cdnUrl3);
                                        $data['image_medium_url']   = htmlspecialchars($cdnUrl1);
                                        $data['image_small_url']    = htmlspecialchars($cdnUrl2);

                                        $data['image_id']           = !empty($rd1['ImageId'])?$rd1['ImageId']:'';
                                        $data['upload_date']        = !empty($rd1['UploadDt'])?$rd1['UploadDt']:'';
                                        $data['image_height']       = !empty($rd2['PictureHeight'])?$rd2['PictureHeight']:'';
                                        $data['image_width']        = !empty($rd2['PictureWidth'])?$rd2['PictureWidth']:'';
                                        $data['image_desc']         = !empty($rd2['PictureDescription'])?$rd2['PictureDescription']:'';
                                        $data['last_modified_date'] = !empty($rd2['LastModifiedDateTime'])?$rd2['LastModifiedDateTime']:'';
                                        $data['modified_date']      = date('Y-m-d h:i:s');
                                        //pr($data);
                                        $fields=array('id');
                                        $match=array('Image_id'=>!empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'');
                                        $res=$this->mls_model->select_records4('',$match,'','=','','','','','','',$this->mls_staging_db);
                                        //file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));
                                        //echo $this->db->last_query();
                                        $data['image_name']     = !empty($name)?$name:'';
                                        if(empty($res))
                                        {
                                            
                                            /* Test 2-4 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-4',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $data['created_date']   = date('Y-m-d h:i:s');
                                            
                                            $id=$this->obj->insert_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                        else
                                        {
                                            
                                            /* Test 2-5 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-5',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $id=$this->obj->update_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                    }
                                    //exit;
                                }
                            }
                        }
                        else
                        {
                            
                            /* Test 3 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'3',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            if(empty($mls_data) && empty($mls_data1))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ISES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            elseif(empty($mls_data))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'IS',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            else
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                        }
                    } catch(DynamoDbException $e) {
                        //echo 'The item could not be retrieved.';
                    }
                    echo 'Successfully Inserted.';
                     //exit;        
                }
                //Remove cron
                /*$datetime = date('Y-m-d h:i:s');
                $output = shell_exec('crontab -l');
                $month = date("m",strtotime($datetime)); 
                $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$num.'/'.$offset.'/'.$hour.'/'.$day;
                $cronjob = ('0 '.$hour.' '.$day.' '.$month.' * curl '.$url);
                //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');

                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                } 
                else 
                {
                   echo 'not found';
                }
                //Copy cron tab and remove string
                $newcron = str_replace($cronjob,"",$output);
                file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                echo exec('crontab ../../../../tmp/crontab.txt');      */ 
            }

            if(!empty($insert_cron_id))
            {

                /* Test 4 */

                /*$field_data_cron = array(
                            'cron_name'=>'retrieve_image_data_issues',
                            'p_type'=>'4',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
                
                //$this->mls_model->insert_cron_test($field_data_cron);
                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                ////////////

                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {

            /* Test 5 */

            /*$field_data_cron = array(
                        'cron_name'=>'retrieve_image_data_issues',
                        'p_type'=>'5',
                        'created_date'=>date('Y-m-d H:i:s')
                        );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

            ////////////

            $field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_mls_id_not_found_else',
                                'p_type'=>'GGGG',
                                'created_date'=>date('Y-m-d H:i:s')
                                );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
        }
        //Insert cron start data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_image_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        echo 'done';
    }

    /*
    @Description: Function Add area community data
    @Author: Nishit Modi
    @Input: - 
    @Output: - insert image data of remaining crons 
    @Date: 20-02-2015
    */

    public function retrieve_image_data_new_remaining()
    {
    	ini_set('display_errors', '1');
        error_reporting(E_ALL);
        $this->load->model('imageupload_model');

        $start_cron_id    = $this->uri->segment(4);
        $end_cron_id    = $this->uri->segment(5);

        $img_counter_data = $this->mls_model->get_image_counter_remaining($start_cron_id,$end_cron_id);
        //echo $this->db->last_query();
        //pr($img_counter_data);exit;

        if(!empty($img_counter_data) && count($img_counter_data) > 0)
        {
            $num    = $img_counter_data[0]['begin_date'];
            $offset = $img_counter_data[0]['curr_date'];

            /*if($offset >= $img_counter_data[0]['end_mls_id'])
                exit;*/

            /*$ins_data = array(
                            'start_mls_id' => ($offset+$num)
                        );
        
            $this->mls_model->update_image_counter($cron_id,$ins_data);*/

            //exit;

            //$num    = $this->uri->segment(4);
            //$offset = $this->uri->segment(5);   

            //Insert cron start data
            /*$field_data_cron = array(
                'cron_name' => 'retrieve_image_data_end',
                'begin_date'=> $num,
                'curr_date' => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $this->mls_model->insert_cron_test($field_data_cron);*/

            $field_data_cron_u = array(
            						'id'=>$img_counter_data[0]['id'],
            						'p_type'=>'star',
            						'created_date'=>date('Y-m-d H:i:s')
            					);
            $table = 'cron_test';
            $this->mls_model->update_cron_test($field_data_cron_u,$table);

            $insert_cron_id_main = $img_counter_data[0]['id'];
            
            //$this->mls_model->insert_cron_test($field_data_cron);

            //$hour = $this->uri->segment(6);
            //$day  = $this->uri->segment(7);
            //$num=20;
            //$offset=1;
            set_time_limit(0);
            //Get mls property
            $fields = array('ID','LN');
            //$match  = array('LN'=>!empty($row['LN'])?$row['LN']:'');
            //$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            $res    = $this->obj->select_records3($fields,'','','=','',$num,$offset,'','','',$this->mls_staging_db,'','','nwmls_mls_property_list_master');
           
            //echo $this->db->last_query();exit;
            if(!empty($res))
            {
                foreach($res as $row)   
                {
                    $listingID=$row['LN'];

                    /* Test 1 */

                    /*$field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_issues',
                                'p_type'=>'1',
                                'LN'=>$listingID,
                                'created_date'=>date('Y-m-d H:i:s')
                                );
                    
                    //$this->mls_model->insert_cron_test($field_data_cron);
                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                    ////////////

                    //$listingID='29133407';
                    
                    try {
                        /*---------------------- Get Imgae data from mls image services-------------------*/
                        $client=new SoapClient('http://images.idx.nwmls.com/imageservice/imagequery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<ImageQuery xmlns='NWMLS:EverNet:ImageQuery:1.0'>";
                        $XMLQuery .="<Auth>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="</Auth>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .=" <ByListingNumber>".$listingID."</ByListingNumber>";
                        $XMLQuery .=" </Query>";
                        $XMLQuery .="<Results>";
                        $XMLQuery .="<Schema>ImageData_1.0</Schema>";
                        $XMLQuery .="</Results>";
                        $XMLQuery .="</ImageQuery>";
                    
                        // $params = array ('v_strXmlQuery' => $XMLQuery);
                        $params = array ('query' => $XMLQuery);
                        $result = $client->RetrieveImages ( $params );
                        $xml = simplexml_load_string ( $result->RetrieveImagesResult );
                        $json = json_encode ( $xml );
                        $mls_data = json_decode ( $json, TRUE );
                        /*--------------------------------- End -----------------------------*/
                        
                        /*---------------------- Get Imgae data from mls evernet services-------------------*/
                        
                        //Get Image data
        
                        $client1=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                        $XMLQuery .="<Message>";
                        $XMLQuery .="<Head>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                        $XMLQuery .="</Head>";
                        $XMLQuery .="<Body>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .="<MLS>NWMLS</MLS>";
                        $XMLQuery .="<ListingNumber>".$listingID."</ListingNumber>";
                        $XMLQuery .="</Query>";
                        $XMLQuery .="<Filter></Filter>";
                        $XMLQuery .="</Body>";
                        $XMLQuery .="</Message>";
                        $XMLQuery .="</EverNetQuerySpecification>";
                        $params = array ('v_strXmlQuery' => $XMLQuery);
                        $nodelist = $client1->RetrieveImageData($params); 
                        $accessnodelist = $nodelist->RetrieveImageDataResult;
                        $xml_result = new SimpleXMLElement($accessnodelist);
                        $json = json_encode ( $xml_result );
                        $mls_data1 = json_decode ( $json, TRUE );
                        
                        /*--------------------------------- End -----------------------------*/

                        //pr($mls_data);exit;

                        if(!empty($mls_data) && !empty($mls_data1))
                        {
                            /* Test 2 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
                                'username' => $this->config->item('cdn_username'),
                                'apiKey'   => $this->config->item('cdn_api_key')
                            ));

                            // 2. Obtain an Object Store service object from the client.
                            $objectStoreService = $client->objectStoreService(null, 'ORD');

                            // 3. Get container.
                            $container = $objectStoreService->getContainer('livewire');
                            //$container = $container->createContainer('images');

                            /* Test 2-x */

                             /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2-x',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s'),
                                        'comments'=>"hi".$mls_data['Images']
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/



                            ///////////////

                            // 4.Upload on cdn server
                            if(!empty($mls_data['Images']['Image']['ImageId']))
                            {

                                /* Test 2-x1 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                $mls_data['Images']['Image'][0]=array(
                                    'ImageId'=>$mls_data['Images']['Image']['ImageId'],
                                    'ImageOrder'=>$mls_data['Images']['Image']['ImageOrder'],
                                    'UploadDt'=>$mls_data['Images']['Image']['UploadDt'],
                                    'BLOB'=>$mls_data['Images']['Image']['BLOB'],
                                );
                                unset($mls_data['Images']['Image']['ImageId']);
                                unset($mls_data['Images']['Image']['ImageOrder']);
                                unset($mls_data['Images']['Image']['UploadDt']);
                                unset($mls_data['Images']['Image']['BLOB']);
                            }
                            if(!empty($mls_data['Images']['Image']))
                            {

                                /* Test 2-x2 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x2',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                for($i=0;$i<count($mls_data['Images']['Image']);$i++)
                                {
                                    /* Test 2-1 */

                                     /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                    //$this->mls_model->insert_cron_test($field_data_cron);
                                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                    ////////////

                                    $img=$mls_data['Images']['Image'][$i]['BLOB'];
                                    $img_order=$mls_data['Images']['Image'][$i]['ImageOrder'];
                                    if($img_order == 0)
                                    {$name = $listingID.".jpg";}
                                    else{
                                        $name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_" .$img_order. ".jpg";
                                    }
                                    
                                    //Upload image
                                    $container->uploadObject("/property_image/".$listingID."/original/".$name, base64_decode($img));
                                    $object = $container->getObject("/property_image/".$listingID."/original/".$name);

                                    $cdnUrl = $object->getPublicUrl();
                                    
                                    $upload_path=$this->config->item('temp_path');
                                    if(!empty($cdnUrl))
                                    {
                                        
                                        /* Test 2-2 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-2',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        //Create thumbnail
                                        $imagedata2 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'b'.$name,970,'image/jpeg');
                                        $imagedata1 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'m'.$name,360,'image/jpeg');
                                        $imagedata  = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'s'.$name,150,'image/jpeg');

                                        /*echo "BIG - ".$imagedata2;
                                        echo "<br>MEDIUM - ".$imagedata1;
                                        echo "<br>SMALL - ".$imagedata;exit;*/

                                        if(!empty($imagedata2))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/big/".$name, base64_decode($imagedata2));
                                            $object = $container->getObject("/property_image/".$listingID."/big/".$name);

                                            $cdnUrl3 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata1))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/medium/".$name, base64_decode($imagedata1));
                                            $object = $container->getObject("/property_image/".$listingID."/medium/".$name);

                                            $cdnUrl2 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/small/".$name, base64_decode($imagedata));
                                            $object = $container->getObject("/property_image/".$listingID."/small/".$name);

                                            $cdnUrl1 = $object->getPublicUrl();
                                        }
                                        
                                    }


                                    $row1  = $mls_data['Images']['Image'];
                                    $row2  = $mls_data1['image'];
                                    //$data['property_id']      = !empty($row['ID'])?$row['ID']:'';
                                    if(!empty($cdnUrl1) && !empty($cdnUrl2))
                                    {
                                        
                                        /* Test 2-3 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-3',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        if(!empty($mls_data['Images']['Image'][1]['ImageId']) && isset($mls_data['Images']['Image'][1]['ImageId']) >0)
                                        {
                                            $rd1 =$row1[$i];
                                            $rd2 =$row2[$i];
                                        }
                                        else
                                        {
                                            $rd1 =$row1;
                                            $rd2 =$row2;        
                                        }
                                        $data['listing_number']     = !empty($row['LN'])?$row['LN']:'';
                                        $data['image_url']          = htmlspecialchars($cdnUrl);
                                        $data['image_big_url']      = htmlspecialchars($cdnUrl3);
                                        $data['image_medium_url']   = htmlspecialchars($cdnUrl1);
                                        $data['image_small_url']    = htmlspecialchars($cdnUrl2);

                                        $data['image_id']           = !empty($rd1['ImageId'])?$rd1['ImageId']:'';
                                        $data['upload_date']        = !empty($rd1['UploadDt'])?$rd1['UploadDt']:'';
                                        $data['image_height']       = !empty($rd2['PictureHeight'])?$rd2['PictureHeight']:'';
                                        $data['image_width']        = !empty($rd2['PictureWidth'])?$rd2['PictureWidth']:'';
                                        $data['image_desc']         = !empty($rd2['PictureDescription'])?$rd2['PictureDescription']:'';
                                        $data['last_modified_date'] = !empty($rd2['LastModifiedDateTime'])?$rd2['LastModifiedDateTime']:'';
                                        $data['modified_date']      = date('Y-m-d h:i:s');
                                        //pr($data);
                                        $fields=array('id');
                                        $match=array('Image_id'=>!empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'');
                                        $res=$this->mls_model->select_records4('',$match,'','=','','','','','','',$this->mls_staging_db);
                                        //file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));
                                        //echo $this->db->last_query();
                                        $data['image_name']     = !empty($name)?$name:'';
                                        if(empty($res))
                                        {
                                            
                                            /* Test 2-4 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-4',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $data['created_date']   = date('Y-m-d h:i:s');
                                            
                                            $id=$this->obj->insert_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                        else
                                        {
                                            
                                            /* Test 2-5 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-5',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $id=$this->obj->update_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                    }
                                    //exit;
                                }
                            }
                        }
                        else
                        {
                            
                            /* Test 3 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'3',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            if(empty($mls_data) && empty($mls_data1))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ISES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            elseif(empty($mls_data))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'IS',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            else
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                        }
                    } catch(DynamoDbException $e) {
                        //echo 'The item could not be retrieved.';
                    }
                    echo 'Successfully Inserted.';
                     //exit;        
                }
                //Remove cron
                /*$datetime = date('Y-m-d h:i:s');
                $output = shell_exec('crontab -l');
                $month = date("m",strtotime($datetime)); 
                $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$num.'/'.$offset.'/'.$hour.'/'.$day;
                $cronjob = ('0 '.$hour.' '.$day.' '.$month.' * curl '.$url);
                //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');

                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                } 
                else 
                {
                   echo 'not found';
                }
                //Copy cron tab and remove string
                $newcron = str_replace($cronjob,"",$output);
                file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                echo exec('crontab ../../../../tmp/crontab.txt');      */ 
            }

            if(!empty($insert_cron_id_main))
            {

                /* Test 4 */

                /*$field_data_cron = array(
                            'cron_name'=>'retrieve_image_data_issues',
                            'p_type'=>'4',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
                
                //$this->mls_model->insert_cron_test($field_data_cron);
                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                ////////////

                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {

            /* Test 5 */

            /*$field_data_cron = array(
                        'cron_name'=>'retrieve_image_data_issues',
                        'p_type'=>'5',
                        'created_date'=>date('Y-m-d H:i:s')
                        );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

            ////////////

            $field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_mls_id_not_found_else',
                                'p_type'=>'GGGG',
                                'created_date'=>date('Y-m-d H:i:s')
                                );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
        }
        //Insert cron start data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_image_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        echo 'done';
    }

    /*
    @Description: Function Add area community data
    @Author: Nishit Modi
    @Input: - 
    @Output: - insert image data of remaining properties
    @Date: 20-02-2015
    */

    public function retrieve_image_data_new_individual()
    {
    	ini_set('display_errors', '1');
        error_reporting(E_ALL);
        $this->load->model('imageupload_model');

        $cron_id    = $this->uri->segment(4);
        //$end_cron_id    = $this->uri->segment(5);

        //$img_counter_data = $this->mls_model->get_image_counter_remaining($start_cron_id,$end_cron_id);
        $img_counter_data = $this->mls_model->get_image_counter($cron_id);
        //echo $this->db->last_query();
        //pr($img_counter_data);exit;

        if(!empty($img_counter_data) && count($img_counter_data) > 0)
        {
            /*$num    = $img_counter_data[0]['begin_date'];
            $offset = $img_counter_data[0]['curr_date'];*/

            /*if($offset >= $img_counter_data[0]['end_mls_id'])
                exit;*/

            /*$ins_data = array(
                            'start_mls_id' => ($offset+$num)
                        );
        
            $this->mls_model->update_image_counter($cron_id,$ins_data);*/

            //exit;

            //$num    = $this->uri->segment(4);
            //$offset = $this->uri->segment(5);   

            //Insert cron start data
            /*$field_data_cron = array(
                'cron_name' => 'retrieve_image_data_end',
                'begin_date'=> $num,
                'curr_date' => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $this->mls_model->insert_cron_test($field_data_cron);*/

            $num    = $img_counter_data[0]['no_of_mls_id'];
            $offset = $img_counter_data[0]['start_mls_id'];

            if($offset >= $img_counter_data[0]['end_mls_id'])
                exit;

            $ins_data = array(
                            'start_mls_id' => ($offset+$num)
                        );
        
            $this->mls_model->update_image_counter($cron_id,$ins_data);


            $field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_indi',
                                'begin_date'=>$num,
                                'curr_date'=>$offset,
                                'created_date'=>date('Y-m-d H:i:s')
                                );
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);

            /*$field_data_cron_u = array(
            						'id'=>$img_counter_data[0]['id'],
            						'p_type'=>'star',
            						'created_date'=>date('Y-m-d H:i:s')
            					);
            $table = 'cron_test';
            $this->mls_model->update_cron_test($field_data_cron_u,$table);

            $insert_cron_id_main = $img_counter_data[0]['id'];*/
            
            //$this->mls_model->insert_cron_test($field_data_cron);

            //$hour = $this->uri->segment(6);
            //$day  = $this->uri->segment(7);
            //$num=20;
            //$offset=1;
            set_time_limit(0);
            //Get mls property
            $fields = array('ID','LN');
            //$match  = array('LN'=>!empty($row['LN'])?$row['LN']:'');
            //$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            $res    = $this->obj->select_records3($fields,'','','=','',$num,$offset,'','','',$this->mls_staging_db,'','','pm');
           
            //echo $this->db->last_query();
            //exit;
            //pr($res);exit;
            if(!empty($res))
            {
                foreach($res as $row)   
                {
                    $listingID=$row['LN'];

                    /* Test 1 */

                    /*$field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_issues',
                                'p_type'=>'1',
                                'LN'=>$listingID,
                                'created_date'=>date('Y-m-d H:i:s')
                                );
                    
                    //$this->mls_model->insert_cron_test($field_data_cron);
                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                    ////////////

                    //$listingID='29133407';
                    
                    try {
                        /*---------------------- Get Imgae data from mls image services-------------------*/
                        $client=new SoapClient('http://images.idx.nwmls.com/imageservice/imagequery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<ImageQuery xmlns='NWMLS:EverNet:ImageQuery:1.0'>";
                        $XMLQuery .="<Auth>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="</Auth>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .=" <ByListingNumber>".$listingID."</ByListingNumber>";
                        $XMLQuery .=" </Query>";
                        $XMLQuery .="<Results>";
                        $XMLQuery .="<Schema>ImageData_1.0</Schema>";
                        $XMLQuery .="</Results>";
                        $XMLQuery .="</ImageQuery>";
                    
                        // $params = array ('v_strXmlQuery' => $XMLQuery);
                        $params = array ('query' => $XMLQuery);
                        $result = $client->RetrieveImages ( $params );
                        $xml = simplexml_load_string ( $result->RetrieveImagesResult );
                        $json = json_encode ( $xml );
                        $mls_data = json_decode ( $json, TRUE );
                        /*--------------------------------- End -----------------------------*/
                        
                        /*---------------------- Get Imgae data from mls evernet services-------------------*/
                        
                        //Get Image data
        
                        $client1=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                        $XMLQuery .="<Message>";
                        $XMLQuery .="<Head>";
                        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                        $XMLQuery .="</Head>";
                        $XMLQuery .="<Body>";
                        $XMLQuery .="<Query>";
                        $XMLQuery .="<MLS>NWMLS</MLS>";
                        $XMLQuery .="<ListingNumber>".$listingID."</ListingNumber>";
                        $XMLQuery .="</Query>";
                        $XMLQuery .="<Filter></Filter>";
                        $XMLQuery .="</Body>";
                        $XMLQuery .="</Message>";
                        $XMLQuery .="</EverNetQuerySpecification>";
                        $params = array ('v_strXmlQuery' => $XMLQuery);
                        $nodelist = $client1->RetrieveImageData($params); 
                        $accessnodelist = $nodelist->RetrieveImageDataResult;
                        $xml_result = new SimpleXMLElement($accessnodelist);
                        $json = json_encode ( $xml_result );
                        $mls_data1 = json_decode ( $json, TRUE );
                        
                        /*--------------------------------- End -----------------------------*/

                        //pr($mls_data);exit;

                        if(!empty($mls_data) && !empty($mls_data1))
                        {
                            /* Test 2 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
                                'username' => $this->config->item('cdn_username'),
                                'apiKey'   => $this->config->item('cdn_api_key')
                            ));

                            // 2. Obtain an Object Store service object from the client.
                            $objectStoreService = $client->objectStoreService(null, 'ORD');

                            // 3. Get container.
                            $container = $objectStoreService->getContainer('livewire');
                            //$container = $container->createContainer('images');

                            /* Test 2-x */

                             /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'2-x',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s'),
                                        'comments'=>"hi".$mls_data['Images']
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/



                            ///////////////

                            // 4.Upload on cdn server
                            if(!empty($mls_data['Images']['Image']['ImageId']))
                            {

                                /* Test 2-x1 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                $mls_data['Images']['Image'][0]=array(
                                    'ImageId'=>$mls_data['Images']['Image']['ImageId'],
                                    'ImageOrder'=>$mls_data['Images']['Image']['ImageOrder'],
                                    'UploadDt'=>$mls_data['Images']['Image']['UploadDt'],
                                    'BLOB'=>$mls_data['Images']['Image']['BLOB'],
                                );
                                unset($mls_data['Images']['Image']['ImageId']);
                                unset($mls_data['Images']['Image']['ImageOrder']);
                                unset($mls_data['Images']['Image']['UploadDt']);
                                unset($mls_data['Images']['Image']['BLOB']);
                            }
                            if(!empty($mls_data['Images']['Image']))
                            {

                                /* Test 2-x2 */

                                /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-x2',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                ///////////////

                                for($i=0;$i<count($mls_data['Images']['Image']);$i++)
                                {
                                    /* Test 2-1 */

                                     /*$field_data_cron = array(
                                                'cron_name'=>'retrieve_image_data_issues',
                                                'p_type'=>'2-1',
                                                'LN'=>$listingID,
                                                'created_date'=>date('Y-m-d H:i:s')
                                                );
                                    
                                    //$this->mls_model->insert_cron_test($field_data_cron);
                                    $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                    ////////////

                                    $img=$mls_data['Images']['Image'][$i]['BLOB'];
                                    $img_order=$mls_data['Images']['Image'][$i]['ImageOrder'];
                                    if($img_order == 0)
                                    {$name = $listingID.".jpg";}
                                    else{
                                        $name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_" .$img_order. ".jpg";
                                    }
                                    
                                    //Upload image
                                    $container->uploadObject("/property_image/".$listingID."/original/".$name, base64_decode($img));
                                    $object = $container->getObject("/property_image/".$listingID."/original/".$name);

                                    $cdnUrl = $object->getPublicUrl();
                                    
                                    $upload_path=$this->config->item('temp_path');
                                    if(!empty($cdnUrl))
                                    {
                                        
                                        /* Test 2-2 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-2',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        //Create thumbnail
                                        $imagedata2 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'b'.$name,970,'image/jpeg');
                                        $imagedata1 = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'m'.$name,360,'image/jpeg');
                                        $imagedata  = $this->imageupload_model->create_thumb($cdnUrl,$upload_path.'s'.$name,150,'image/jpeg');

                                        /*echo "BIG - ".$imagedata2;
                                        echo "<br>MEDIUM - ".$imagedata1;
                                        echo "<br>SMALL - ".$imagedata;exit;*/

                                        if(!empty($imagedata2))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/big/".$name, base64_decode($imagedata2));
                                            $object = $container->getObject("/property_image/".$listingID."/big/".$name);

                                            $cdnUrl3 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata1))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/medium/".$name, base64_decode($imagedata1));
                                            $object = $container->getObject("/property_image/".$listingID."/medium/".$name);

                                            $cdnUrl2 = $object->getPublicUrl();
                                            
                                        }
                                        if(!empty($imagedata))
                                        {
                                            $container->uploadObject("/property_image/".$listingID."/small/".$name, base64_decode($imagedata));
                                            $object = $container->getObject("/property_image/".$listingID."/small/".$name);

                                            $cdnUrl1 = $object->getPublicUrl();
                                        }
                                        
                                    }


                                    $row1  = $mls_data['Images']['Image'];
                                    $row2  = $mls_data1['image'];
                                    //$data['property_id']      = !empty($row['ID'])?$row['ID']:'';
                                    if(!empty($cdnUrl1) && !empty($cdnUrl2))
                                    {
                                        
                                        /* Test 2-3 */

                                        /*$field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'2-3',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                        
                                        //$this->mls_model->insert_cron_test($field_data_cron);
                                        $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                        ////////////

                                        if(!empty($mls_data['Images']['Image'][1]['ImageId']) && isset($mls_data['Images']['Image'][1]['ImageId']) >0)
                                        {
                                            $rd1 =$row1[$i];
                                            $rd2 =$row2[$i];
                                        }
                                        else
                                        {
                                            $rd1 =$row1;
                                            $rd2 =$row2;        
                                        }
                                        $data['listing_number']     = !empty($row['LN'])?$row['LN']:'';
                                        $data['image_url']          = htmlspecialchars($cdnUrl);
                                        $data['image_big_url']      = htmlspecialchars($cdnUrl3);
                                        $data['image_medium_url']   = htmlspecialchars($cdnUrl1);
                                        $data['image_small_url']    = htmlspecialchars($cdnUrl2);

                                        $data['image_id']           = !empty($rd1['ImageId'])?$rd1['ImageId']:'';
                                        $data['upload_date']        = !empty($rd1['UploadDt'])?$rd1['UploadDt']:'';
                                        $data['image_height']       = !empty($rd2['PictureHeight'])?$rd2['PictureHeight']:'';
                                        $data['image_width']        = !empty($rd2['PictureWidth'])?$rd2['PictureWidth']:'';
                                        $data['image_desc']         = !empty($rd2['PictureDescription'])?$rd2['PictureDescription']:'';
                                        $data['last_modified_date'] = !empty($rd2['LastModifiedDateTime'])?$rd2['LastModifiedDateTime']:'';
                                        $data['modified_date']      = date('Y-m-d h:i:s');
                                        //pr($data);
                                        $fields=array('id');
                                        $match=array('Image_id'=>!empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'');
                                        $res=$this->mls_model->select_records4('',$match,'','=','','','','','','',$this->mls_staging_db);
                                        //file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));
                                        //echo $this->db->last_query();
                                        $data['image_name']     = !empty($name)?$name:'';
                                        if(empty($res))
                                        {
                                            
                                            /* Test 2-4 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-4',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $data['created_date']   = date('Y-m-d h:i:s');
                                            
                                            $id=$this->obj->insert_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                        else
                                        {
                                            
                                            /* Test 2-5 */

                                            /*$field_data_cron = array(
                                                        'cron_name'=>'retrieve_image_data_issues',
                                                        'p_type'=>'2-5',
                                                        'LN'=>$listingID,
                                                        'created_date'=>date('Y-m-d H:i:s')
                                                        );
                                            
                                            //$this->mls_model->insert_cron_test($field_data_cron);
                                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                                            ////////////

                                            $id=$this->obj->update_record4($data,$this->mls_staging_db);
                                            //echo $this->db->last_query();
                                        }
                                    }
                                    //exit;
                                }
                            }
                        }
                        else
                        {
                            
                            /* Test 3 */

                            /*$field_data_cron = array(
                                        'cron_name'=>'retrieve_image_data_issues',
                                        'p_type'=>'3',
                                        'LN'=>$listingID,
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                            
                            //$this->mls_model->insert_cron_test($field_data_cron);
                            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                            ////////////

                            if(empty($mls_data) && empty($mls_data1))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ISES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            elseif(empty($mls_data))
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'IS',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                            else
                            {
                                $field_data_cron = array(
                                                    'cron_name'=>'retrieve_image_data_issues',
                                                    'p_type'=>'ES',
                                                    'LN'=>$listingID,
                                                    'created_date'=>date('Y-m-d H:i:s')
                                                    );
                                
                                //$this->mls_model->insert_cron_test($field_data_cron);
                                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
                            }
                        }
                    } catch(DynamoDbException $e) {
                        //echo 'The item could not be retrieved.';
                    }
                    echo 'Successfully Inserted.';
                     //exit;        
                }
                //Remove cron
                /*$datetime = date('Y-m-d h:i:s');
                $output = shell_exec('crontab -l');
                $month = date("m",strtotime($datetime)); 
                $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$num.'/'.$offset.'/'.$hour.'/'.$day;
                $cronjob = ('0 '.$hour.' '.$day.' '.$month.' * curl '.$url);
                //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');

                if (strstr($output, $cronjob)) 
                {
                   echo 'found';
                } 
                else 
                {
                   echo 'not found';
                }
                //Copy cron tab and remove string
                $newcron = str_replace($cronjob,"",$output);
                file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                echo exec('crontab ../../../../tmp/crontab.txt');      */ 
            }

            if(!empty($insert_cron_id_main))
            {

                /* Test 4 */

                /*$field_data_cron = array(
                            'cron_name'=>'retrieve_image_data_issues',
                            'p_type'=>'4',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
                
                //$this->mls_model->insert_cron_test($field_data_cron);
                $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

                ////////////

                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {

            /* Test 5 */

            /*$field_data_cron = array(
                        'cron_name'=>'retrieve_image_data_issues',
                        'p_type'=>'5',
                        'created_date'=>date('Y-m-d H:i:s')
                        );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);*/

            ////////////

            $field_data_cron = array(
                                'cron_name'=>'retrieve_image_data_mls_id_not_found_else',
                                'p_type'=>'GGGG',
                                'created_date'=>date('Y-m-d H:i:s')
                                );
            
            //$this->mls_model->insert_cron_test($field_data_cron);
            $insert_cron_id = $this->mls_model->insert_cron_image_test($field_data_cron);
        }
        //Insert cron start data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_image_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        echo 'done';
    }
   
    function create_dir($listingID)
    {
        $upload_path=$this->config->item('upload_image_file_path');
        $image_path = $upload_path."property_image/";
        $dir_path=$image_path.$listingID;
        mkdir($dir_path,0777);
        chmod($dir_path,0777);
        //fopen($dir_path.'index.php','x+');
            
    }
    /*
    @Description: Function Add area community data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area community data
    @Date: 20-02-2015
    */
   
    public function retrieve_listing_history_data()
    {
        set_time_limit(0);
         
        error_reporting('E_ALL');
        $field=array('id','name');
        $where=array('status'=>'1');
        $propty_type=$this->obj->select_records_tran($field,$where,'','=');
       
        // Get maximum change date
        
        /*$field=array('id','max(change_date) as change_date');
        $res_data=$this->obj->select_records5($field,'','','=','','1','0');
        //pr($res_data);
        //echo $this->db->last_query();exit;
        if(!empty($res_data[0]['change_date']))
        {
            $begin_date = str_replace(" ","T",$res_data[0]['change_date']);
            $curr_date  = str_replace(" ","T",date('Y-m-d h:i:s'));
        }
        else
        {
            $begin_date = '2010-01-01T00:00:00';
            $curr_date  = '2011-01-01T00:00:00';    
        }*/
        //$begin_date = '2010-01-01T00:00:00';
        //$curr_date  = '2011-01-01T00:00:00'; 
        $begin_date = $this->uri->segment(4);
        $curr_date  = $this->uri->segment(5);
        //$propty_type  = $this->uri->segment(6);
        //Insert cron start data
        
        $field_data_cron = array(
                            'cron_name'=>'retrieve_listing_history_data_start',
                            'begin_date'=>$begin_date,
                            'curr_date'=>$curr_date,
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        //$this->mls_model->insert_cron_test($field_data_cron);
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
        
        if(!empty($propty_type))
        {
            foreach($propty_type as $type)  
            {
                $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                $XMLQuery .="<Message>";
                $XMLQuery .="<Head>";
                $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
                $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
                $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
                $XMLQuery .="</Head>";
                $XMLQuery .="<Body>";
                $XMLQuery .="<Query>";
                $XMLQuery .="<MLS>NWMLS</MLS>";
                $XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
                //$XMLQuery .="<Status>A</Status>";
                $XMLQuery .="<BeginDate>".$begin_date."</BeginDate>";;
                $XMLQuery .="<EndDate>".$curr_date."</EndDate>";
                $XMLQuery .="</Query>";
                $XMLQuery .="<Filter></Filter>";
                $XMLQuery .="</Body>";
                $XMLQuery .="</Message>";
                $XMLQuery .="</EverNetQuerySpecification>";
                $params = array ('v_strXmlQuery' => $XMLQuery);
                $nodelist = $client->RetrieveListingHistoryData($params); 
                $accessnodelist = $nodelist->RetrieveListingHistoryDataResult;
                $xml_result = new SimpleXMLElement($accessnodelist);
                $json = json_encode($xml_result);
                $mls_data = json_decode($json,TRUE);
                
                foreach($mls_data as $key=>$value)
                {
                    $propertytype=$key;
                    $property_data=$mls_data[$propertytype];
                    $i = 0;
                    $j = 0;
                    foreach($property_data as $row)
                    {
                        $data[$i]['property_type']      = !empty($type['name'])?$type['name']:'';
                        $data[$i]['ml_number']          = !empty($row['ML_Number'])?$row['ML_Number']:'';
                        $data[$i]['list_price']         = !empty($row['LH']['ListPrice'])?$row['LH']['ListPrice']:'';
                        $data[$i]['change_date']        = !empty($row['LH']['ChangeDate'])?$row['LH']['ChangeDate']:'';
                        $data[$i]['modified_date']      = date('Y-m-d h:i:s');
                        //pr($data);exit;
                        $fields=array('id');
                        $match=array('ml_number'=>!empty($row['ML_Number'])?$row['ML_Number']:'');
                        $res=$this->mls_model->select_records5('',$match,'','=','','','','','','',$this->mls_staging_db);
                        if(empty($res))
                        {
                            $data[$i]['created_date']       = date('Y-m-d h:i:s');
                            /*if(count($data) >= 100)
                            {
                                $this->mls_model->insert_record5($data,$this->mls_staging_db);
                                $i = 0;
                                unset($data);
                            }*/
                            $i++;
                                
                        }
                        else
                        {
                            $cdata[$j] = $data[$i];
                            unset($data[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                    $this->mls_model->update_record5($cdata,$this->mls_staging_db);
                                    $j = 0;
                                    unset($cdata);
                            }*/
                            $j++;                        
                        }
                    }
                } 
                //Insert more than 100
                if(!empty($data)  && count($data) > 0)
                {
                    $i = 0;
                    foreach ($data as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_record5($idata,$this->mls_staging_db);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($data);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_record5($udata,$this->mls_staging_db);
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_record5($idata,$this->mls_staging_db);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_record5($udata,$this->mls_staging_db);
                    unset($udata);  
                }   
                
            }
            //redirect('superadmin/mls/add_record');
        }
        
        //Insert cron end data
        /*$field_data_cron = array(
                            'cron_name'=>'retrieve_listing_history_data_end',
                            'begin_date'=>$begin_date,
                            'curr_date'=>$curr_date,
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        echo 'done';
    }
    /*
    @Description: Function Add mls member data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area mls member data
    @Date: 20-02-2015
    */
   
    public function retrieve_member_data()
    {
        //Insert cron start data
        $field_data_cron = array(
                            'cron_name'=>'retrieve_member_data_start',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        //$this->mls_model->insert_cron_test($field_data_cron);
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);
        set_time_limit(0);
        
        $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
        $XMLQuery .="<Message>";
        $XMLQuery .="<Head>";
        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
        $XMLQuery .="</Head>";
        $XMLQuery .="<Body>";
        $XMLQuery .="<Query>";
        $XMLQuery .="<MLS>NWMLS</MLS>";
        //$XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
        //$XMLQuery .="<Status>A</Status>";
        //$XMLQuery .="<BeginDate>2013-05-07T23:00:00</BeginDate>";;
        //$XMLQuery .="<EndDate>2013-11-07T00:00:00</EndDate>";
        $XMLQuery .="</Query>";
        $XMLQuery .="<Filter></Filter>";
        $XMLQuery .="</Body>";
        $XMLQuery .="</Message>";
        $XMLQuery .="</EverNetQuerySpecification>";
        $params = array ('v_strXmlQuery' => $XMLQuery);
        $nodelist = $client->RetrieveMemberData($params); 
        $accessnodelist = $nodelist->RetrieveMemberDataResult;
        $xml_result = new SimpleXMLElement($accessnodelist);
        $json = json_encode($xml_result);
        $mls_data = json_decode($json,TRUE);
        
        //pr($mls_data);exit;
        if(!empty($mls_data))
        {
            foreach($mls_data as $key=>$value)
            {
                $propertytype=$key;
                $property_data=$mls_data[$propertytype];
                $i = 0;
                $j = 0;
                foreach($property_data as $row)
                {
                    //$data['property_type']            = !empty($type['name'])?$type['name']:'';
                    $data[$i]['member_mls_id']          = !empty($row['MemberMLSID'])?$row['MemberMLSID']:'';
                    $data[$i]['first_name']             = !empty($row['FirstName'])?$row['FirstName']:'';
                    $data[$i]['last_name']              = !empty($row['LastName'])?$row['LastName']:'';
                    $data[$i]['member_office_mls_id']   = !empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'';
                    $data[$i]['member_office_name']     = !empty($row['OfficeName'])?$row['OfficeName']:'';
                    $data[$i]['member_office_area_code']= !empty($row['OfficeAreaCode'])?$row['OfficeAreaCode']:'';
                    $data[$i]['member_office_phone']    = !empty($row['OfficePhone'])?$row['OfficePhone']:'';
                    $data[$i]['office_phone_extension'] = !empty($row['OfficePhoneExtension'])?$row['OfficePhoneExtension']:'';
                    $data[$i]['modified_date']          = date('Y-m-d h:i:s');
                    
                    $fields=array('id');
                    $match=array('member_mls_id'=>!empty($row['MemberMLSID'])?$row['MemberMLSID']:'');
                    $res=$this->mls_model->select_records6('',$match,'','=','','','','','','',$this->mls_staging_db);
                    if(empty($res))
                    {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        /*if(count($data) >= 100)
                        {
                            $this->mls_model->insert_record6($data,$this->mls_staging_db);
                            $i = 0;
                            unset($data);
                        }*/
                        $i++;
                            
                    }
                    else
                    {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        /*if(count($cdata) >= 100)
                        {
                                $this->mls_model->update_record6($cdata,$this->mls_staging_db);
                                $j = 0;
                                unset($cdata);
                        }*/
                        $j++;                        
                    }
                }
                 //Insert more than 100
                if(!empty($data)  && count($data) > 0)
                {
                    $i = 0;
                    foreach ($data as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_record6($idata,$this->mls_staging_db);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($data);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_record6($udata,$this->mls_staging_db);
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_record6($idata,$this->mls_staging_db);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_record6($udata,$this->mls_staging_db);
                    unset($udata);  
                }
            }
        }    
        //Insert cron end data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_member_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        echo 'done';  
        //redirect('superadmin/mls/add_record');
    }
    /*
    @Description: Function Add mls office data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area mls member data
    @Date: 20-02-2015
    */
   
    public function retrieve_office_data()
    {
        //Insert cron start data
        $field_data_cron = array(
                            'cron_name'=>'retrieve_office_data_start',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        //$this->mls_model->insert_cron_test($field_data_cron);
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

        set_time_limit(0);
        
        $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
        $XMLQuery .="<Message>";
        $XMLQuery .="<Head>";
        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
        $XMLQuery .="</Head>";
        $XMLQuery .="<Body>";
        $XMLQuery .="<Query>";
        $XMLQuery .="<MLS>NWMLS</MLS>";
        //$XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
        //$XMLQuery .="<Status>A</Status>";
        //$XMLQuery .="<BeginDate>2013-05-07T23:00:00</BeginDate>";;
        //$XMLQuery .="<EndDate>2013-11-07T00:00:00</EndDate>";
        $XMLQuery .="</Query>";
        $XMLQuery .="<Filter></Filter>";
        $XMLQuery .="</Body>";
        $XMLQuery .="</Message>";
        $XMLQuery .="</EverNetQuerySpecification>";
        $params = array ('v_strXmlQuery' => $XMLQuery);
        $nodelist = $client->RetrieveOfficeData($params); 
        $accessnodelist = $nodelist->RetrieveOfficeDataResult;
        $xml_result = new SimpleXMLElement($accessnodelist);
        $json = json_encode($xml_result);
        $mls_data = json_decode($json,TRUE);
        
        //pr($mls_data);exit;
        if(!empty($mls_data))
        {
            foreach($mls_data as $key=>$value)
            {
                $propertytype=$key;
                $property_data=$mls_data[$propertytype];
                $i = 0;
                $j = 0;
                foreach($property_data as $row)
                {
                    //$data[$i]['property_type']            = !empty($type['name'])?$type['name']:'';
                    $data[$i]['office_mls_id']          = !empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'';
                    $data[$i]['office_name']            = !empty($row['OfficeName'])?$row['OfficeName']:'';
                    $data[$i]['street_care_of']         = !empty($row['StreetCareOf'])?$row['StreetCareOf']:'';
                    $data[$i]['street_address']         = !empty($row['StreetAddress'])?$row['StreetAddress']:'';
                    $data[$i]['street_city']            = !empty($row['StreetCity'])?$row['StreetCity']:'';
                    $data[$i]['street_state']           = !empty($row['StreetState'])?$row['StreetState']:'';
                    $data[$i]['street_zip_code']        = !empty($row['StreetZipCode'])?$row['StreetZipCode']:'';
                    $data[$i]['street_zip_plus4']       = !empty($row['StreetZipPlus4'])?$row['StreetZipPlus4']:'';
                    $data[$i]['street_county']          = !empty($row['StreetCounty'])?$row['StreetCounty']:'';
                    $data[$i]['office_area_code']       = !empty($row['OfficeAreaCode'])?$row['OfficeAreaCode']:'';
                    $data[$i]['office_phone']           = !empty($row['OfficePhone'])?$row['OfficePhone']:'';
                    $data[$i]['fax_area_code']          = !empty($row['FaxAreaCode'])?$row['FaxAreaCode']:'';
                    $data[$i]['fax_phone']              = !empty($row['FaxPhone'])?$row['FaxPhone']:'';
                    $data[$i]['email_address']          = !empty($row['EMailAddress'])?$row['EMailAddress']:'';
                    $data[$i]['webPage_address']        = !empty($row['WebPageAddress'])?$row['WebPageAddress']:'';
                    $data[$i]['office_type']            = !empty($row['OfficeType'])?$row['OfficeType']:'';
                    $data[$i]['modified_date']          = date('Y-m-d h:i:s');
                    //pr($data);exit;
                    $fields=array('id');
                    $match=array('office_mls_id'=>!empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'');
                    $res=$this->mls_model->select_records7('',$match,'','=','','','','','','',$this->mls_staging_db);
                    if(empty($res))
                    {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        /*if(count($data) >= 100)
                        {
                            $this->mls_model->insert_record7($data,$this->mls_staging_db);
                            $i = 0;
                            unset($data);
                        }*/
                        $i++;
                            
                    }
                    else
                    {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        /*if(count($cdata) >= 100)
                        {
                                $this->mls_model->update_record7($cdata,$this->mls_staging_db);
                                $j = 0;
                                unset($cdata);
                        }*/
                        $j++;                        
                    }
                }
                 //Insert more than 100
                if(!empty($data)  && count($data) > 0)
                {
                    $i = 0;
                    foreach ($data as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_record7($idata,$this->mls_staging_db);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($data);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_record7($udata,$this->mls_staging_db);
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_record7($idata,$this->mls_staging_db);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_record7($udata,$this->mls_staging_db);
                    unset($udata);  
                }
                
            }    
        }
        
        //Insert cron end data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_office_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }

        echo 'done';  
        //redirect('superadmin/mls/add_record');
    }
    /*
    @Description: Function Add mls school data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert area mls member data
    @Date: 20-02-2015
    */
   
    public function retrieve_school_data()
    {
        //Insert cron start data
        $field_data_cron = array(
                            'cron_name'=>'retrieve_school_data_start',
                            'created_date'=>date('Y-m-d H:i:s')
                            );
        //$this->mls_model->insert_cron_test($field_data_cron);
        $insert_cron_id = $this->mls_model->insert_cron_test($field_data_cron);

        
        set_time_limit(0);
        
        $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
        $XMLQuery .="<Message>";
        $XMLQuery .="<Head>";
        $XMLQuery .="<UserId>".$this->config->item('mls_userid')."</UserId>";
        $XMLQuery .="<Password>".$this->config->item('mls_password')."</Password>";
        $XMLQuery .="<SchemaName>StandardXML1_3</SchemaName>";
        $XMLQuery .="</Head>";
        $XMLQuery .="<Body>";
        $XMLQuery .="<Query>";
        $XMLQuery .="<MLS>NWMLS</MLS>";
        //$XMLQuery .="<PropertyType>".$type['name']."</PropertyType>";
        //$XMLQuery .="<Status>A</Status>";
        //$XMLQuery .="<BeginDate>2013-05-07T23:00:00</BeginDate>";;
        //$XMLQuery .="<EndDate>2013-11-07T00:00:00</EndDate>";
        $XMLQuery .="</Query>";
        $XMLQuery .="<Filter></Filter>";
        $XMLQuery .="</Body>";
        $XMLQuery .="</Message>";
        $XMLQuery .="</EverNetQuerySpecification>";
        $params = array ('v_strXmlQuery' => $XMLQuery);
        $nodelist = $client->RetrieveSchoolData($params); 
        $accessnodelist = $nodelist->RetrieveSchoolDataResult;
        $xml_result = new SimpleXMLElement($accessnodelist);
        $json = json_encode($xml_result);
        $mls_data = json_decode($json,TRUE);
        
        if(!empty($mls_data))
        {
            foreach($mls_data as $key=>$value)
            {
                $propertytype=$key;
                $property_data=$mls_data[$propertytype];
                $i = 0;
                $j = 0;
                foreach($property_data as $row)
                {
                    //$data[$i]['property_type']                    = !empty($type['name'])?$type['name']:'';
                    $data[$i]['school_district_code']           = !empty($row['SchoolDistrictCode'])?$row['SchoolDistrictCode']:'';
                    $data[$i]['school_district_description']    = !empty($row['SchoolDistrictDescription'])?$row['SchoolDistrictDescription']:'';
                    $data[$i]['modified_date']                  = date('Y-m-d h:i:s');
                    //pr($data[$i]);exit;
                    $fields=array('id');
                    $match=array('school_district_code'=>!empty($row['SchoolDistrictCode'])?$row['SchoolDistrictCode']:'');
                    $res=$this->mls_model->select_records8('',$match,'','=','','','','','','',$this->mls_staging_db);
                    if(empty($res))
                    {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        /*if(count($data) >= 100)
                        {
                            $this->mls_model->insert_record8($data,$this->mls_staging_db);
                            $i = 0;
                            unset($data);
                        }*/
                        $i++;
                            
                    }
                    else
                    {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        /*if(count($cdata) >= 100)
                        {
                                $this->mls_model->update_record8($cdata,$this->mls_staging_db);
                                $j = 0;
                                unset($cdata);
                        }*/
                        $j++;                        
                    }
                }
                 //Insert more than 100
                if(!empty($data)  && count($data) > 0)
                {
                    $i = 0;
                    foreach ($data as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_record8($idata,$this->mls_staging_db);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($data);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_record8($udata,$this->mls_staging_db);
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_record8($idata,$this->mls_staging_db);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_record8($udata,$this->mls_staging_db);
                    unset($udata);  
                }
            }    
        }
        
        //Insert cron end data
        /*$field_data_cron = array(
            'cron_name'=>'retrieve_school_data_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);*/

        if(!empty($insert_cron_id))
        {
            $db_name = $this->config->item('parent_db_name');
            $table = $db_name.'.cron_test';
            $field_data_cron_u = array('id'=>$insert_cron_id,'completed_date'=>date('Y-m-d H:i:s'));
            $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
        }
        
        echo 'done';       
            
        //redirect('superadmin/mls/add_record');
    }
    function create_image_links()
    {
        //echo 1;exit;
		$total = 10;
		$datetime = date('Y-m-d h:i:s');
		//echo '<br>'; //exit;

        /*$hr = date("h",strtotime($datetime)); 
		$day = date("d",strtotime($datetime));
        $month = date("m",strtotime($datetime)); */

        $hr = 8;
        $day = 29;
        $month = 5; 

		$start = 800;
        $minute = 3;
		//$next = (($start * $total) - $total);
        $next = 800;
        for($i=$start;$i<=$start+10000;$i++)
        {
            //echo $i;           
            //echo '<br>';
            // Create cron job start
            //echo "<br>";
            //$hr=$hohrur1;

            if($i % 10 == 0)
            {
                $minute = $minute+5;

                if($minute >= 60)
                {
                    $minute = ($minute)%60;
                    $hr++;
                }
                
                if($hr >= 24)
                {
                    //$day = date("d",strtotime($datetime))+1;
                    $hr=0;
                    $hour = $hr;
                    if($day == 31)
                    {
                        $day = 1;
                        $month = $month+1;
                    }
                    else
                    {
                        $day=$day+1;
                        $month = $month;
                    }
                }   
                else
                {
                    if($day > $curdate)
                    {$day=$day;}

                    $hour = $hr;
                    
                    //$day = $curdate;
                }
            }

            

            //echo $day = date("d",strtotime($datetime));
            /*if($i == 0)
            {
				// $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$total.'/0/'.$hour.'/'.$day;

                // Nishit Changes //

                $url=base_url().'superadmin/mls_import/retrieve_image_data/'.$total.'/0';
                
                // End //
                
                //$next+=$total;
            }
            else
            {*/
                //echo $next;exit;

				//$url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$total.'/'.$next.'/'.$hour.'/'.$day;

                // Nishit Changes //

                $url=base_url().'superadmin/mls_import/retrieve_image_data/'.$total.'/'.$next;

                // End //
            /*}  */ 

            //echo $url;

            //$output = shell_exec('crontab -l');
            //$output = shell_exec('crontab -l');
            
            //echo $output.$minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url;
            //echo '<br>';

            echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url."<br>";
            
            //file_put_contents('../../../../tmp/cron.txt', $output.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url.''.PHP_EOL);
            //echo exec('crontab ../../../../tmp/cron.txt');
            $next+=$total;
            //$hr++;
            //echo 'done';
            //exit;
        }
        //echo 'done';exit;
    }
    function create_listing_crons()
    {

        $prop_type_array = array("BUSO","COMI","COND","FARM","MANU","MULT","RESI","VACL");

        $startyear = 2015;
        $endyear = 2015;

        $starttime = 'T00:00:01';
        $endtime = 'T00:00:00';

        //$cron_date_start_array = array();
        //$cron_date_end_array = array();

        $links_array = array();

        $sametime = 1;

        $minute = 30;
        $hour = 18;
        $day = 22;
        $month = 5;

        for($yearrange=$startyear;$yearrange<=$endyear;$yearrange++)
        {
            for($month_range=1;$month_range<=12;$month_range++)
            {
                for($weekdaysdata=1;$weekdaysdata<=28;$weekdaysdata+=7)
                {
                    if($yearrange == 2015 && $month_range > 5)
                        break;

                    /* Set start date */

                    $temp_start_date = $yearrange.'-';

                    if($month_range < 10)
                        $temp_start_date .= '0';
                    $temp_start_date .= $month_range.'-';

                    /* END */

                    /* Set end date */

                    if($month_range == 12)
                    {
                        if($weekdaysdata < 20)
                            $temp_end_date = ($yearrange).'-';
                        else
                            $temp_end_date = ($yearrange+1).'-';
                    }
                    else
                        $temp_end_date = $yearrange.'-';

                    if($month_range < 10)
                    {
                        //echo $month_range."<br>";
                        $temp_end_date .= '0';
                    }

                    if($month_range == 12)
                    {
                        if($weekdaysdata < 20)
                            $temp_end_date .= ($month_range);
                        else
                            $temp_end_date .= '01';
                    }
                    else
                    {
                        if($weekdaysdata < 20)
                            $temp_end_date .= ($month_range);
                        else
                            $temp_end_date .= ($month_range+1);
                    }

                    $temp_end_date .= '-';    

                    /* END */            

                    //$cron_date_start_array[] = $temp_start_date.$starttime;
                    //$cron_date_end_array[] = $temp_end_date.$endtime;

                    //$links_array[] = $new_url;

                    if($minute >= 60)
                    {
                        $minute = 0;
                        $hour++;
                    }

                    if($hour >= 24)
                    {
                        $hour = 0;
                        $day++;
                    }

                    //for($weekdaysdata=1;$weekdaysdata<=28;$weekdaysdata+=7)
                    //{
                        if($weekdaysdata < 10)
                        {
                            $temp_start_date1 = '0'.$weekdaysdata;
                            
                            if($weekdaysdata == 1)
                                $temp_end_date1 = '0'.($weekdaysdata+7);
                            else
                                $temp_end_date1 = ($weekdaysdata+7);
                        }
                        else
                        {
                            $temp_start_date1 = $weekdaysdata;

                            if($weekdaysdata < 20)
                                $temp_end_date1 = ($weekdaysdata+7);
                            else
                                $temp_end_date1 = '01';
                        }

                        foreach($prop_type_array as $row_prop_type)
                        {
                            $cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                            //$cron_time='';
                            //$new_url = base_url().'superadmin/mls_import/retrieve_listing_data/'.$temp_start_date.$temp_start_date1.$starttime.'/'.$temp_end_date.$temp_end_date1.$endtime.'/'.$row_prop_type.'/'.$cron_time;
                            $new_url = base_url().'superadmin/mls_import/retrieve_listing_data/'.$temp_start_date.$temp_start_date1.$starttime.'/'.$temp_end_date.$temp_end_date1.$endtime.'/'.$row_prop_type;
                            $links_array[] = $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url;
                        }

                    //}
                    
                    //echo "<br>";
                    //exit;

                    /*$output_command = shell_exec('crontab -l');
                    file_put_contents('../../../../tmp/cron.txt', $output_command.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url.''.PHP_EOL);
                    echo exec('crontab ../../../../tmp/cron.txt');*/

                    //exit;

                    //if($sametime % 5 == 0)
                    //{
                        $minute=$minute+10;
                        //$sametime++;
                    //}
                    //else
                        //$sametime++;

                }
            }
        }

        //pr($cron_date_start_array);
        //pr($cron_date_end_array);

       

        /*if(!empty($cron_date_start_array))
        {
            for($all_dates=0;$all_dates<count($cron_date_start_array);$all_dates++)
            {

                $links_array[] = base_url().'superadmin/mls_import/retrieve_listing_data1/'.$cron_date_start_array[$all_dates].'/'.$cron_date_end_array[$all_dates];

            }
        }*/


        //echo 'done';
        //pr($links_array);

        foreach($links_array as $row){echo $row."<br>";}

        exit;
    }
    function create_listing_crons1()
    {
        $startyear = 1998;
        $endyear = 2015;

        $starttime = 'T00:00:01';
        $endtime = 'T00:00:00';

        //$cron_date_start_array = array();
        //$cron_date_end_array = array();

        $links_array = array();

        
        $minute = 10;
        $hour = 8;
        $day = 24;
        $month = 4;

        for($yearrange=$startyear;$yearrange<=$endyear;$yearrange++)
        {
            for($month_range=1;$month_range<=12;$month_range++)
            {

                if($yearrange == 2015 && $month_range > 4)
                    break;

                $temp_start_date = $yearrange.'-';
                if($month_range < 10)
                    $temp_start_date .= '0';
                $temp_start_date .= $month_range.'-01';

                if($month_range == 12)
                    $temp_end_date = ($yearrange+1).'-';
                else
                    $temp_end_date = $yearrange.'-';

                if($month_range < 9)
                    $temp_end_date .= '0';

                if($month_range == 12)
                    $temp_end_date .= '01';
                else
                    $temp_end_date .= ($month_range+1);

                $temp_end_date .= '-01';                

                //$cron_date_start_array[] = $temp_start_date.$starttime;
                //$cron_date_end_array[] = $temp_end_date.$endtime;

                $new_url = base_url().'superadmin/mls_import/retrieve_listing_data1/'.$temp_start_date.$starttime.'/'.$temp_end_date.$endtime;
                //$links_array[] = $new_url;

                if($minute == 60)
                {
                    $minute = 0;
                    $hour++;
                }

                if($hour == 24)
                {
                    $hour = 0;
                    $day++;
                }

                $links_array[] = $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url;
                //echo "<br>";
                //exit;

                $output_command = shell_exec('crontab -l');
                file_put_contents('../../../../tmp/cron.txt', $output_command.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url.''.PHP_EOL);
                echo exec('crontab ../../../../tmp/cron.txt');

                //exit;

                $minute=$minute+10;

            }
        }

        //pr($cron_date_start_array);
        //pr($cron_date_end_array);

       

        /*if(!empty($cron_date_start_array))
        {
            for($all_dates=0;$all_dates<count($cron_date_start_array);$all_dates++)
            {

                $links_array[] = base_url().'superadmin/mls_import/retrieve_listing_data1/'.$cron_date_start_array[$all_dates].'/'.$cron_date_end_array[$all_dates];

            }
        }*/


        echo 'done';
        pr($links_array);

        exit;
    }
    function create_crons_links()
    {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        /*$table_name    = 'livewire_master_database.mls_property_list_master';
        $function_name = 'retrieve_image_data';*/
        $table_name    = 'livewire_master_database.mls_member_data';
        $function_name = 'import_member_map';
        $mls_id = 6;
        $total_record = $this->obj->select_records_common($table_name,$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',1,$group_by='');
        //pr($total_record);exit;
        if($total_record > 1000)
        {
            $num    = 1000;
            $offset = 0;
        }
        else
        {
            $num    = $total_record;
            $offset = 0;   
        }    
        $tot = ceil($total_record/$num);
        $links_array = array();

        $sametime = 1;

        $minute = 0;
        $hour = 8;
        $day = 29;
        $month = 4;
        //exit;
        $mls_id = !empty($mls_id)?'/'.$mls_id:'';          
        for($i=1;$i<=$tot;$i++)
        {
                $offset = !empty($offset)?$offset:'0';
                $cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                $new_url = base_url().'superadmin/mls_import/'.$function_name.$mls_id.'/'.$num.'/'.$offset.'/'.$cron_time;
            
                if($minute >= 60)
                {
                    $minute = 0;
                    $hour++;
                }

                if($hour == 24)
                {
                    $hour = 0;
                    $day++;
                }

                $links_array[] = $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url;
                
                $output_command = shell_exec('crontab -l');
                file_put_contents('../../../../tmp/cron.txt', $output_command.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$new_url.''.PHP_EOL);
                echo exec('crontab ../../../../tmp/cron.txt');

                //exit;

                $minute=$minute+50;
                $offset= $offset+$num;
        }     

        //pr($cron_date_start_array);
        //pr($cron_date_end_array);

       

        /*if(!empty($cron_date_start_array))
        {
            for($all_dates=0;$all_dates<count($cron_date_start_array);$all_dates++)
            {

                $links_array[] = base_url().'superadmin/mls_import/retrieve_listing_data1/'.$cron_date_start_array[$all_dates].'/'.$cron_date_end_array[$all_dates];

            }
        }*/


        echo 'done';
        pr($links_array);

        exit;
    }
    function delete_cron()
    {
        $total = 100;
        $datetime = date('Y-m-d h:i:s');
        $hr = date("h",strtotime($datetime));
        $day = date("d",strtotime($datetime));
        $hr = 16;
        //$start = 1;
        //$next = $start * $total;
        $next = 100;
        for($i=1;$i<=30;$i++)
        {
            //echo $i;           
            //echo '<br>';
            // Create cron job start
            //echo "<br>";
            $minute = 0;
            //$hr=$hohrur1;
            if($hr > 24)
            {
                //$day = date("d",strtotime($datetime))+1;
                $hr=1;
                $hour = $hr;
                $day=$day+1;
            }   
            else
            {
                if($day > $curdate)
                {$day=$day;}
                $hour = $hr;    
                
                //$day = $curdate;
            }
            //$month = date("m",strtotime($datetime)); 
            $output = shell_exec('crontab -l');
            //$output = shell_exec('crontab -l');
            
          
            $month = date("m",strtotime($datetime)); 
            echo $url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$total.'/'.$next.'/'.$hour.'/'.$day; 
            $cronjob = ('0 '.$hour.' '.$day.' '.$month.' * curl '.$url);
            //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
    
            if (strstr($output, $cronjob)) 
            {
               echo 'found';
            } 
            else 
            {
               echo 'not found';
            }
            //Copy cron tab and remove string
            $newcron = str_replace($cronjob,"",$output);
            file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
            echo exec('crontab ../../../../tmp/crontab.txt');  
          
            $next+=$total;
            $hr++;
        }           
    }

    /*
    @Description: Function Add import mls mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_mls_map()
    {
        $mls_id = $this->uri->segment(4);
        
        //Get mapping data
        $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table');
        $match = array('mls_id'=>$mls_id);
        $mapping_data   = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','mls_master_field_id','asc');        
        pr($mapping_data);exit;
        //Direct map
        foreach($mapping_data as $row)
        {

        }
    }
    /*
    @Description: Function import mls member mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_office_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';

        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '4');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');

        $office_table_name           = $tran_table_data[0]['ofiice_table_name'];
        $our_database_table = 'mls_office_data';
        $table_id = 4;
        //Map office data
        if(!empty($office_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$office_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    //$url = base_url().'superadmin/mls_import/import_office_map/'.$mls_id.'/'.$cron_time;
                    $url = base_url().'superadmin/mls_import/import_office_map/'.$mls_id;
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_office_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
                
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '4',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }

            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'office_id');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //pr($mapping_data);
            $mls_default_id   = array('office_area_code','fax_area_code');
            
            //$table_name='',$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$office_table_name,'','','','','',$num,$offset);    
            //echo $this->db->last_query();exit;
            //$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$office_table_name,'','','','','','','','','','','','1');    
            
            
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$office_table_name);    

            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('office_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('office_id' => $odata['id'],'mls_id' => $mls_id);
                    }
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    foreach($mapping_data as $row)
                    {

                        $mls_field          = $row['mls_master_field'];
                        $mls_map_field      = explode('.',$row['mls_field']);
                        $mls_map_table      = $row['mls_field_table'];
                        $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                        $insert_mapdata[$i][$mls_field]='';
                        //If Field is id in our database
                        if(in_array($mls_field,$mls_default_id))
                        {
                            //Case : id -> id
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    $field = array($mls_map_tran_field[1]);
                                    $match = array($mls_map_field[1]=>$field_value);
                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                    if(!empty($retivedata))
                                    {
                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                    }
                                    else
                                    {
                                        //insert record in our transection table
                                    }
                                }                    
                            }
                            else
                            {
                                //Case : id -> value
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    //$field = array($mls_map_tran_field[1]);
                                    $match = array($mls_map_field[1]=>$field_value);
                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$office_table_name,'',$match,'','=','','1','1');        
                                    
                                    if(!empty($retivedata))
                                    {
                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                    }
                                    else
                                    {
                                        //insert record in our transection table
                                    }
                                }

                            }
                        }
                        //If Field is value in our database
                        else
                        {
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                //Case : value -> id
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                { 
                                    $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                    if(!empty($mlsmap_fields))
                                    {
                                        $msfield= array();
                                        foreach ($mlsmap_fields as $value) 
                                        {
                                           $msfield[] = $value['Field'];
                                        }
                                    
                                   
                                        if(in_array($mls_map_field[1],$msfield))
                                        {
                                            $field = array($mls_map_tran_field[1]);
                                            $match = array($mls_map_field[1]=>$field_value);
                                            $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                            if(!empty($retivedata))
                                            {
                                                $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $insert_mapdata[$i][$mls_field] = '';
                                    }
                                    
                                }
                                
                            }
                            else
                            {
                                //Case : value -> value
                                $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                            }
                        }
                    }
                    
                    if(empty($res))
                    {
                        $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                        $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                        /*if(count($insert_mapdata) >= 100)
                        {
                            $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                            $i = 0;
                            unset($insert_mapdata);
                        }*/
                        $i++;
                    }
                    else
                    {
                        $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                       // pr($insert_mapdata);
                        $cdata[$j] = $insert_mapdata[$i];
                        unset($insert_mapdata[$i]);
                        /*if(count($cdata) >= 100)
                        {
                            $this->mls_model->update_batch_record($cdata,$our_database_table,'office_id');
                            $j = 0;
                            unset($cdata);
                        }*/
                        $j++;
                    }
                }
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'office_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'office_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls member mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_member_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';

        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '3');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        //$offset = 6000;
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');
        $member_table_name           = $tran_table_data[0]['member_table_name'];
        $our_database_table = 'mls_member_data';
        $table_id = 3;
        //Map member data
        if(!empty($member_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_member_map/'.$mls_id;
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_member_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '3',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );
            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                //Insert last updated offset
                $last_data['created_date']=date('Y-m-d H:i:s');
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }

            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);

            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //Get unique id
            $field = array('mls_field');
            $match1 = array('mls_master_field'=>'member_mls_id');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match1,'','=','','','','','');        

            $mls_default_id   = array('member_office_mls_id','member_office_area_code');
            
            //$table_name='',$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            //$match = array('id'=>4483);
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'','','','','',$num,$offset);    
            

            //echo $this->db->last_query();exit;
            //pr($mapping_table_data);exit;
            //exit;
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    /*if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('member_mls_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        */$match=array('member_id' => $odata['id'],'mls_id' => $mls_id);
                   /* }*/
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    foreach($mapping_data as $row)
                    {
                        $mls_field          = $row['mls_master_field'];echo '<br>';
                        $mls_map_field      = explode('.',$row['mls_field']);
                        $mls_map_table      = $row['mls_field_table'];
                        $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                        $insert_mapdata[$i][$mls_field]='';
                        //If Field is id in our database
                        if(in_array($mls_field,$mls_default_id))
                        {
                            //Case : id -> id
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {                 
                                    if($mls_field == 'member_office_mls_id')
                                    {
                                        $field = array('id','office_mls_id');
                                        $match = array('office_mls_id' => trim($field_value));
                                        $resdata  = $this->obj->select_records_common('mls_office_data',$field,$match,'','=','');                                                
                                        
                                        if(!empty($resdata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $resdata[0]['office_mls_id'];
                                        }
                                        
                                    }
                                    
                                } 

                            }
                            else
                            {
                                //Case : id -> value
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    //$field = array($mls_map_tran_field[1]);
                                    $match = array($mls_map_field[1]=>$field_value);
                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                    
                                    if(!empty($retivedata))
                                    {
                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                    }
                                    else
                                    {
                                        //insert record in our transection table
                                    }
                                }

                            }
                        }
                        //If Field is value in our database
                        else
                        {
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                //Case : value -> id
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                { 
                                    $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                    if(!empty($mlsmap_fields))
                                    {
                                        $msfield= array();
                                        foreach ($mlsmap_fields as $value) 
                                        {
                                           $msfield[] = $value['Field'];
                                        }
                                    
                                   
                                        if(in_array($mls_map_field[1],$msfield))
                                        {
                                            $field = array($mls_map_tran_field[1]);
                                            $match = array($mls_map_field[1]=>$field_value);
                                            $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                            if(!empty($retivedata))
                                            {
                                                $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $insert_mapdata[$i][$mls_field] = '';
                                    }
                                    
                                }
                                
                            }
                            else
                            {
                                //Case : value -> value
                                $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                            }
                        }
                    }
                    //pr($insert_mapdata); echo 1;exit;
                    if(empty($res))
                    {
                        $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                        $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                        /*if(count($insert_mapdata) >= 100)
                        {
                            $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                            $i = 0;
                            unset($insert_mapdata);
                        }*/
                        $i++;
                    }
                    else
                    {
                        $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                        $cdata[$j] = $insert_mapdata[$i];
                        unset($insert_mapdata[$i]);
                        /*if(count($cdata) >= 100)
                        {
                            $this->mls_model->update_batch_record($cdata,$our_database_table,'member_id');
                            $j = 0;
                            unset($cdata);
                        }*/
                        $j++;
                    }
                }
                //pr($cdata);exit;
                /*echo  'I'.count($insert_mapdata);
                echo  count($cdata) ;exit;*/
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'member_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'member_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_member_map_end',
                'begin_date'=>$num,
                'curr_date'=>$offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_school_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '5');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');

        $school_table_name           = $tran_table_data[0]['school_table_name'];
        $our_database_table = 'mls_school_data';
        $table_id = 5;
        //Map school data
        if(!empty($school_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_school_map/'.$mls_id;
                    
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_school_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '5',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'school_district_code ');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //Get school data from master db
            $mls_default_id   = array();
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name,'','','','','',$num,$offset);    
            //$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name,'','','','','','','','','','','','1');    
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    /*if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('school_district_code ' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        */$match=array('school_id' => $odata['id'],'mls_id' => $mls_id);
                    /*}*/
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');
                        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        foreach($mapping_data as $row)
                        {
                            $mls_field          = $row['mls_master_field'];
                            $mls_map_field      = explode('.',$row['mls_field']);
                            $mls_map_table      = $row['mls_field_table'];
                            $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                            $insert_mapdata[$i][$mls_field] = '';
                            //If Field is id in our database

                            if(in_array($mls_field,$mls_default_id))
                            {
                                //Case : id -> id
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        $field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }                    
                                }
                                else
                                {
                                    //Case : id -> value
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        //$field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }

                                }
                            }
                            //If Field is value in our database
                            else
                            {
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    //Case : value -> id
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    { 
                                        $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                        if(!empty($mlsmap_fields))
                                        {
                                            $msfield= array();
                                            foreach ($mlsmap_fields as $value) 
                                            {
                                               $msfield[] = $value['Field'];
                                            }
                                        
                                       
                                            if(in_array($mls_map_field[1],$msfield))
                                            {
                                                $field = array($mls_map_tran_field[1]);
                                                $match = array($mls_map_field[1]=>$field_value);
                                                $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $insert_mapdata[$i][$mls_field] = '';
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    //Case : value -> value
                                    $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                                }
                            }
                        }
                        if(empty($res)) //Insert data
                        {
                            $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            /*if(count($insert_mapdata) >= 100)
                            {
                                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                $i = 0;
                                unset($insert_mapdata);
                            }*/
                            $i++;
                        }
                        else //Update data
                        {
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            $cdata[$j] = $insert_mapdata[$i];
                            unset($insert_mapdata[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                $this->mls_model->update_batch_record($cdata,$our_database_table,'school_id');
                                $j = 0;
                                unset($cdata);
                            }*/
                            $j++;
                        }
                    }
                }
               
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'school_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'school_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }  
            }
            else
            {
                echo 'Data not found.';
            }
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_school_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_area_community_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '2');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');

        $area_community_table_name   = $tran_table_data[0]['area_community_table_name'];
        $our_database_table = 'mls_area_community_data';
        $table_id = 2;
        if(!empty($area_community_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$area_community_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_area_community_map/'.$mls_id;
                    
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_area_community_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '2',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }


            //Map member data
       
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'area_community_id');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //pr($mapping_data);
            $mls_default_id   = array();
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$area_community_table_name,'','','','','',$num,$offset);    
            
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('area_community_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('area_community_id' => $odata['id'],'mls_id' => $mls_id);
                    }
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        foreach($mapping_data as $row)
                        {
                            $mls_field          = $row['mls_master_field'];
                            $mls_map_field      = explode('.',$row['mls_field']);
                            $mls_map_table      = $row['mls_field_table'];
                            $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                            $insert_mapdata[$i][$mls_field]='';
                            //If Field is id in our database
                            if(in_array($mls_field,$mls_default_id))
                            {
                                //Case : id -> id
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        $field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }                    
                                }
                                else
                                {
                                    //Case : id -> value
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        //$field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }

                                }
                            }
                            //If Field is value in our database
                            else
                            {
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    //Case : value -> id
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    { 
                                        $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                        if(!empty($mlsmap_fields))
                                        {
                                            $msfield= array();
                                            foreach ($mlsmap_fields as $value) 
                                            {
                                               $msfield[] = $value['Field'];
                                            }
                                        
                                       
                                            if(in_array($mls_map_field[1],$msfield))
                                            {
                                                $field = array($mls_map_tran_field[1]);
                                                $match = array($mls_map_field[1]=>$field_value);
                                                $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $insert_mapdata[$i][$mls_field] = '';
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    //Case : value -> value
                                    $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                                }
                            }
                        }
                        if(empty($res)) //Insert data
                        {
                            $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            /*if(count($insert_mapdata) >= 100)
                            {
                                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                $i = 0;
                                unset($insert_mapdata);
                            }*/
                            $i++;
                        }
                        else //Update data
                        {
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            $cdata[$j] = $insert_mapdata[$i];
                            unset($insert_mapdata[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                $this->mls_model->update_batch_record($cdata,$our_database_table,'area_community_id');
                                $j = 0;
                                unset($cdata);
                            }*/
                            $j++;
                        }
                    }
                }
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'area_community_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'area_community_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_area_community_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_amenity_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';

        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '6');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');
        $amenity_table_name          = $tran_table_data[0]['amenity_table_name'];
        $our_database_table = 'mls_amenity_data';
        $table_id = 6;
        //Map member data
        if(!empty($amenity_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$amenity_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_amenity_map/'.$mls_id;
                    
                   /* $minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_amenity_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '6',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );
            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'amenity_id');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //Get amenity data from master db
            $mls_default_id   = array();
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$amenity_table_name,'','','','','',$num,$offset);    
            
            //echo $this->db->last_query();
            //exit;
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('amenity_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('amenity_id' => $odata['id'],'mls_id' => $mls_id);
                    }
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        foreach($mapping_data as $row)
                        {
                            $mls_field          = $row['mls_master_field'];
                            $mls_map_field      = explode('.',$row['mls_field']);
                            $mls_map_table      = $row['mls_field_table'];
                            $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                            $insert_mapdata[$i][$mls_field]='';
                            //If Field is id in our database
                            if(in_array($mls_field,$mls_default_id))
                            {
                                //Case : id -> id
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        $field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }                    
                                }
                                else
                                {
                                    //Case : id -> value
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        //$field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }

                                }
                            }
                            //If Field is value in our database
                            else
                            {
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    //Case : value -> id
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    { 
                                        $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                        if(!empty($mlsmap_fields))
                                        {
                                            $msfield= array();
                                            foreach ($mlsmap_fields as $value) 
                                            {
                                               $msfield[] = $value['Field'];
                                            }
                                        
                                       
                                            if(in_array($mls_map_field[1],$msfield))
                                            {
                                                $field = array($mls_map_tran_field[1]);
                                                $match = array($mls_map_field[1]=>$field_value);
                                                $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $insert_mapdata[$i][$mls_field] = '';
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    //Case : value -> value
                                    $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                                }
                            }
                        }
                        if(empty($res)) //Insert data
                        {
                            $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            /*if(count($insert_mapdata) >= 100)
                            {
                                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                $i = 0;
                                unset($insert_mapdata);
                            }*/
                            $i++;
                        }
                        else //Update data
                        {
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            $cdata[$j] = $insert_mapdata[$i];
                            unset($insert_mapdata[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                $this->mls_model->update_batch_record($cdata,$our_database_table,'amenity_id');
                                $j = 0;
                                unset($cdata);
                            }*/
                            $j++;
                        }
                    }
                }
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'amenity_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'amenity_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_amenity_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_prop_history_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '7');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');

        $property_history_table_name = $tran_table_data[0]['property_history_table_name'];
        $our_database_table = 'mls_property_history_data';
        $table_id = 7;
        //Map school data
        if(!empty($property_history_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$property_history_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_prop_history_map/'.$mls_id;
                    
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_prop_history_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '7',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'ml_number');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //pr($mapping_data);
            $mls_default_id   = array();
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$property_history_table_name,'','','','','',$num,$offset);    
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$property_history_table_name,'','','','','','','','','','','','1');    
            //pr($mapping_table_data);exit;
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('ml_number' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('property_history_id' => $odata['id'],'mls_id' => $mls_id);
                    }
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        foreach($mapping_data as $row)
                        {
                            $mls_field          = $row['mls_master_field'];
                            $mls_map_field      = explode('.',$row['mls_field']);
                            $mls_map_table      = $row['mls_field_table'];
                            $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                            $insert_mapdata[$i][$mls_field]='';
                            //If Field is id in our database
                            if(in_array($mls_field,$mls_default_id))
                            {
                                //Case : id -> id
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        $field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }                    
                                }
                                else
                                {
                                    //Case : id -> value
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        //$field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }

                                }
                            }
                            //If Field is value in our database
                            else
                            {
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    //Case : value -> id
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    { 
                                        $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                        if(!empty($mlsmap_fields))
                                        {
                                            $msfield= array();
                                            foreach ($mlsmap_fields as $value) 
                                            {
                                               $msfield[] = $value['Field'];
                                            }
                                        
                                       
                                            if(in_array($mls_map_field[1],$msfield))
                                            {
                                                $field = array($mls_map_tran_field[1]);
                                                $match = array($mls_map_field[1]=>$field_value);
                                                $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $insert_mapdata[$i][$mls_field] = '';
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    //Case : value -> value
                                    $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                                }
                            }
                        }
                        if(empty($res)) //Insert data
                        {
                            $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            /*if(count($insert_mapdata) >= 100)
                            {
                                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                $i = 0;
                                unset($insert_mapdata);
                            }*/
                            $i++;
                        }
                        else //Update data
                        {
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            $cdata[$j] = $insert_mapdata[$i];
                            unset($insert_mapdata[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                $this->mls_model->update_batch_record($cdata,$our_database_table,'property_history_id');
                                $j = 0;
                                unset($cdata);
                            }*/
                            $j++;
                        }
                    }
                }
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'property_history_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'property_history_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_prop_history_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_image_map()
    {
        ini_set('memory_limit','-1');
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '8');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 5000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');

        $image_table_name            = $tran_table_data[0]['image_table_name'];
        $our_database_table = 'mls_property_image';
        $table_id = 8;
        //Map school data
        if(!empty($image_table_name))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$image_table_name,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_image_map/'.$mls_id;
                   
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_image_map_start',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '8',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            //Gel mls details
            $match = array('mls_id' => $mls_id);
            $mls_details             = $this->obj->select_records_common('mls_type_of_mls_master','',$match,'','=');
            //Get unique id
            $field1 = array('mls_field');
            $match1 = array('mls_master_field'=>'mls_image_id','mls_id'=>$mls_id);
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field1,$match1,'','=','','','','','');        

            $field2 = array('mls_field');
            $match2 = array('mls_master_field'=>'listing_number','mls_id'=>$mls_id);
            $mapping_number  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field2,$match2,'','=','','','','','');        
            $ln_numner = explode('.',$mapping_number[0]['mls_field']);
            //pr($ln_numner);exit;

            $field3 = array('mls_field');
            $match3 = array('mls_master_field'=>'image_url','mls_id'=>$mls_id);
            $img_url  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field3,$match3,'','=','','','','','');        
            
            //$imge_url = explode('.',$img_url[0]['mls_field']);
           
            $mls_default_id   = array();
            //$group_by='MLS_ID';

            //$table_name='',$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows=''
            $fields = array('*','count(MLS_ID) as total_image');

            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$image_table_name,'','','','','',$num,$offset,$ln_numner[1],'asc','','',''); 
            //$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$image_table_name,'','','','','','','','','','','','1');    
            //echo $this->db->last_query();
            //pr($mapping_table_data);exit;
            $first_mls_id = $mapping_table_data[0]['MLS_ID'];

            //Get last image number
            $match = array('listing_number'=>$first_mls_id);
            $image_number_data   = $this->obj->select_records_common('mls_property_image','',$match,'','=','','1','','image_no','desc','','',''); 
            
            if(!empty($image_number_data))
            {
                $listing_number = $image_number_data[0]['listing_number'];
                $image_no       = $image_number_data[0]['image_no'];
            }
            if(!empty($first_mls_id) && !empty($listing_number) && ($first_mls_id == $listing_number))
            {
              $t = $image_no+1; 
            }
            else
            {
                $t = 1;
            }
                       
            $i = 0;
            $j = 0;
            $prev_ln = '';
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {              
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('mls_image_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('mls_image_id' => $odata['id'],'mls_id' => $mls_id);
                    }
                    $fields=array('id');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        foreach($mapping_data as $row)
                        {
                            $mls_field          = $row['mls_master_field'];
                            $mls_map_field      = explode('.',$row['mls_field']);
                            $mls_map_table      = $row['mls_field_table'];
                            $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                            $insert_mapdata[$i][$mls_field]='';
                            //If Field is id in our database
                            if(in_array($mls_field,$mls_default_id))
                            {
                                //Case : id -> id
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        $field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }                    
                                }
                                else
                                {
                                    //Case : id -> value
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    {
                                        //$field = array($mls_map_tran_field[1]);
                                        $match = array($mls_map_field[1]=>$field_value);
                                        $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                        }
                                        else
                                        {
                                            //insert record in our transection table
                                        }
                                    }

                                }
                            }
                            //If Field is value in our database
                            else
                            {
                                if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                {
                                    //Case : value -> id
                                    $field_value = $odata[$mls_map_field[1]]; 
                                    if(!empty($field_value))
                                    { 
                                        $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                        if(!empty($mlsmap_fields))
                                        {
                                            $msfield= array();
                                            foreach ($mlsmap_fields as $value) 
                                            {
                                               $msfield[] = $value['Field'];
                                            }
                                        
                                       
                                            if(in_array($mls_map_field[1],$msfield))
                                            {
                                                $field = array($mls_map_tran_field[1]);
                                                $match = array($mls_map_field[1]=>$field_value);
                                                $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $insert_mapdata[$i][$mls_field] = '';
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    //Case : value -> value
                                    $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                                }
                            }
                        }
                        if(empty($res)) 
                        {
                            
                            if(count($img_url) == 0 && !empty($mls_details[0]['mls_image_url']))
                            {
                                $ln = $insert_mapdata[$i]['listing_number'];
                                /*echo 'prev->'.$prev_ln;
                                echo 'cur->'.$ln;*/
                                if((!empty($prev_ln) && $prev_ln == $ln) || $prev_ln == '')
                                {
                                    //echo 'if';
                                    //echo 'http://wncrmls.thelivewiresolution.com/IMG-'.$prev_ln.'_'.$t.'.jpg';
                                    if(empty($prev_ln))
                                    {
                                        $prev_ln = $ln;
                                    }
                                    $imgurl = $mls_details[0]['mls_image_url'].'/IMG-'.$prev_ln.'_'.$t.'.jpg';
                                    $insert_mapdata[$i]['image_url']       = $imgurl;
                                    $insert_mapdata[$i]['image_big_url']   = $imgurl;
                                    $insert_mapdata[$i]['image_medium_url']= $imgurl;
                                    $insert_mapdata[$i]['image_small_url'] = $imgurl;
                                    $insert_mapdata[$i]['image_no'] = $t;
                                    $t++;
                                    
                                }
                                else
                                {
                                    //echo 'else';
                                    $t=1;
                                    //echo 'http://wncrmls.thelivewiresolution.com/IMG-'.$ln.'_'.$t.'.jpg';
                                    //$insert_mapdata[$i]['image_url']= $mls_details[0]['mls_image_url'].'/IMG-'.$ln.'_'.$t.'.jpg';
                                    $imgurl = $mls_details[0]['mls_image_url'].'/IMG-'.$ln.'_'.$t.'.jpg';
                                    $insert_mapdata[$i]['image_url']       = $imgurl;
                                    $insert_mapdata[$i]['image_big_url']   = $imgurl;
                                    $insert_mapdata[$i]['image_medium_url']= $imgurl;
                                    $insert_mapdata[$i]['image_small_url'] = $imgurl;
                                    $insert_mapdata[$i]['image_no'] = $t;
                                    $t++;
                                }
                                $prev_ln = $ln;
                            }
                        }
                       
                        if(empty($res)) //Insert data
                        {
                            $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            /*if(count($insert_mapdata) >= 100)
                            {
                                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                $i = 0;
                                unset($insert_mapdata);
                            }*/
                            $i++;
                        }
                        else //Update data
                        {
                            $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                            $cdata[$j] = $insert_mapdata[$i];
                            unset($insert_mapdata[$i]);
                            /*if(count($cdata) >= 100)
                            {
                                $this->mls_model->update_batch_record($cdata,$our_database_table,'mls_image_id');
                                $j = 0;
                                unset($cdata);
                            }*/
                            $j++;
                        } 
                    }
                }
                //pr($insert_mapdata);exit;
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }
                //Update more than 100
                //pr($cdata);exit;
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'mls_image_id');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }

                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'mls_image_id');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_image_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_image_map1()
    {
        $mls_id = $this->uri->segment(4);
        
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $tran_table_data             = $this->obj->select_records_common('mls_child_table_mapping','',$match,'','=');
        $office_table_name           = $tran_table_data[0]['ofiice_table_name'];
        $school_table_name           = $tran_table_data[0]['school_table_name'];
        $member_table_name           = $tran_table_data[0]['member_table_name'];
        $area_community_table_name   = $tran_table_data[0]['area_community_table_name'];
        $amenity_table_name          = $tran_table_data[0]['amenity_table_name'];
        $property_history_table_name = $tran_table_data[0]['property_history_table_name'];
        $image_table_name            = $tran_table_data[0]['image_table_name'];
        
        $our_database_table = 'mls_property_image';
        $table_id = 8;

        //Map member data
        if(!empty($image_table_name))
        {
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'mls_image_id');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        

            //pr($mapping_data);
            $mls_default_id   = array();
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$image_table_name,'','','','','');    
            //pr($mapping_table_data);exit;
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            foreach($mapping_table_data as $odata)
            {
                if(!empty($mapping_data_id))
                {
                    $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                    $match=array('mls_image_id' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                }  
                else
                {
                    $match=array('mls_image_id' => $odata['id'],'mls_id' => $mls_id);
                }
                $fields=array('id');
                $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                $insert_mapdata[$i]['mls_id']   =  $mls_id;
                if(!empty($odata) && count($odata) > 0)              
                {
                    foreach($mapping_data as $row)
                    {
                        $mls_field          = $row['mls_master_field'];
                        $mls_map_field      = explode('.',$row['mls_field']);
                        $mls_map_table      = $row['mls_field_table'];
                        $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                        $insert_mapdata[$i][$mls_field]='';
                        //If Field is id in our database
                        if(in_array($mls_field,$mls_default_id))
                        {
                            //Case : id -> id
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    $field = array($mls_map_tran_field[1]);
                                    $match = array($mls_map_field[1]=>$field_value);
                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        

                                    if(!empty($retivedata))
                                    {
                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];
                                    }
                                    else
                                    {
                                        //insert record in our transection table
                                    }
                                }                    
                            }
                            else
                            {
                                //Case : id -> value
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    //$field = array($mls_map_tran_field[1]);
                                    $match = array($mls_map_field[1]=>$field_value);
                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$member_table_name,'',$match,'','=','','1','1');        
                                    
                                    if(!empty($retivedata))
                                    {
                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0]['id'];
                                    }
                                    else
                                    {
                                        //insert record in our transection table
                                    }
                                }

                            }
                        }
                        //If Field is value in our database
                        else
                        {
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                //Case : value -> id
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                { 
                                    $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                    if(!empty($mlsmap_fields))
                                    {
                                        $msfield= array();
                                        foreach ($mlsmap_fields as $value) 
                                        {
                                           $msfield[] = $value['Field'];
                                        }
                                    
                                   
                                        if(in_array($mls_map_field[1],$msfield))
                                        {
                                            $field = array($mls_map_tran_field[1]);
                                            $match = array($mls_map_field[1]=>$field_value);
                                            $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                            if(!empty($retivedata))
                                            {
                                                $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $insert_mapdata[$i][$mls_field] = '';
                                    }
                                    
                                }
                                
                            }
                            else
                            {
                                //Case : value -> value
                                $insert_mapdata[$i][$mls_field] =  $odata[$mls_map_field[1]];
                            }
                        }
                    }
                    if(empty($res)) //Insert data
                    {
                        $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                        if(count($insert_mapdata) >= 100)
                        {
                            $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                            $i = 0;
                            unset($insert_mapdata);
                        }
                        $i++;
                    }
                    else //Update data
                    {
                        $cdata[$j] = $insert_mapdata[$i];
                        unset($insert_mapdata[$i]);
                        if(count($cdata) >= 100)
                        {
                            $this->mls_model->update_batch_record($cdata,$our_database_table,'mls_image_id');
                            $j = 0;
                            unset($cdata);
                        }
                        $j++;
                    }
                }
            }
           
            // insert data when record less then 100
            if(!empty($insert_mapdata) && count($insert_mapdata) > 0)
            {
                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                unset($insert_mapdata);   
            }
            if(!empty($cdata) && count($cdata) > 0)
            {
                $this->mls_model->update_batch_record($cdata,$our_database_table,'mls_image_id');
                //pr($cdata); exit;
                unset($cdata);  
            }
            echo 'done';
        }
    }
    /*
    @Description: Function import mls school mapping data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015
    */
    function import_property_map()
    {
        ini_set('memory_limit','-1');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        //$offset = $this->uri->segment(5);
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '1');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 1000;
        $offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        //$offset = 100000;
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $mls_tables_data = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');

        $main_table = $mls_tables_data[0]['main_table'];
        
        $our_database_table = 'mls_property_list_master';
        $table_id = 1;
       
        //Map school data
        if(!empty($main_table))
        {
            $mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_property_map/'.$mls_id;
                    
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    $minute   = '*/5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_property_map_start',
                'p_type'       => $mls_id,
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '1',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'LN','mls_id'=>$mls_id);
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        
            
            //pr($mapping_data_id);exit;
            $mls_default_id   = array('LAG','CLA','LO','COLO','SD');
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','',$num,$offset);    
            //$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','','','','','','','','1');    
            //pr($mapping_table_data);exit;
            //echo $this->db->last_query();
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    //pr($odata);
                    if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('LN' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('property_id' => $odata['ID'],'mls_id' => $mls_id);
                    }
                    //$match=array('property_id' => $odata['ID'],'mls_id' => $mls_id);
                    $fields=array('id');
                    $this->db->query('SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');
                    $this->db->query('COMMIT');

                    //echo $this->db->last_query();exit;
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    $insert_mapdata[$i]['status']   =  1;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        if(!empty($mapping_data))
                        {
                            foreach($mapping_data as $row)
                            {
                                $mls_field          = $row['mls_master_field'];
                                $mls_map_field      = explode('.',$row['mls_field']);
                                $mls_map_table      = $row['mls_field_table'];
                                $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                                $insert_mapdata[$i][$mls_field] = '';
                                //If Field is id in our database
                                if(in_array($mls_field,$mls_default_id))
                                {
                                    //Case : id -> id
                                    if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                    {
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        {
                                            if($mls_field == 'LAG' || $mls_field == 'CLA')
                                            {
                                                $field = array('id','member_mls_id');
                                                $match = array('member_mls_id' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_member_data',$field,$match,'like','','');                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['member_mls_id'];
                                                }                                        
                                            } 
                                            if($mls_field == 'LO' || $mls_field == 'COLO')
                                            {
                                                $field = array('id','office_mls_id');
                                                $match = array('office_mls_id' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_office_data',$field,$match,'','=','');                                                
                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['office_mls_id'];
                                                }
                                            }
                                            if($mls_field == 'SD')
                                            {
                                                $field = array('id','school_district_code');
                                                $match = array('school_district_code' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_school_data',$field,$match,'','=','');                                                
                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['school_district_code'];
                                                }
                                            } 
                                            
                                        }               
                                    }
                                    else
                                    {
                                        //Case : id -> value
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  trim($field_value);
                                            /*if($mls_field == 'LAG' || $mls_field == 'CLA')
                                            {
                                                $match = array('first_name'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_member_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['member_mls_id'];
                                                }
                                                else
                                                {
                                                   $member_id='';
                                                   //insert record in our transection table
                                                   $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'member_mls_id'               => $member_id,
                                                        'first_name'                  => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_member_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $member_id;
                                                   }
                                                }

                                            } 
                                            if($mls_field == 'LO' || $mls_field == 'COLO')
                                            {
                                                $match = array('office_name'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_office_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['office_mls_id'];
                                                }
                                                else
                                                {
                                                   //insert record in our transection table
                                                    $office_id='';
                                                    $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'office_mls_id'               => $office_id,
                                                        'office_name'                 => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_office_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $office_id;
                                                   }
                                                }
                                                
                                            }
                                            if($mls_field == 'SD')
                                            {
                                                $match = array('school_district_description'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_school_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['school_district_code'];
                                                }
                                                else
                                                {
                                                   //insert record in our transection table
                                                   $field_value1=str_replace(' ','',$field_value);
                                                   $school_distric_code = substr(trim($field_value1),0,3);

                                                   $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'school_district_code'        => $school_distric_code,
                                                        'school_district_description' => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_school_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $school_distric_code;
                                                   }
                                                }
                                            } */
                                            //$field = array($mls_map_tran_field[1]);                                    
                                        }

                                    }
                                }
                                //If Field is value in our database
                                else
                                {
                                    if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                    {
                                        //Case : value -> id
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        { 
                                            $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                            if(!empty($mlsmap_fields))
                                            {
                                                $msfield= array();
                                                foreach ($mlsmap_fields as $value) 
                                                {
                                                   $msfield[] = $value['Field'];
                                                }
                                            
                                           
                                                if(in_array($mls_map_field[1],$msfield))
                                                {
                                                    $field = array($mls_map_tran_field[1]);
                                                    $match = array($mls_map_field[1]=>$field_value);
                                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                    if(!empty($retivedata))
                                                    {
                                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $insert_mapdata[$i][$mls_field] = '';
                                            }
                                            
                                        }
                                        
                                    }
                                    else
                                    {
                                        //Case : value -> value
                                        $insert_mapdata[$i][$mls_field] =  mysql_real_escape_string($odata[$mls_map_field[1]]);
                                    }
                                }
                            }

                            //Add property type
                            if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'resid' || strtolower($insert_mapdata[$i]['PTYP']) == 'single family detached' || strtolower($insert_mapdata[$i]['PTYP']) == 'sfr' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'preconstruction'|| strtolower($insert_mapdata[$i]['PTYP']) == 'townhouse' || strtolower($insert_mapdata[$i]['PTYP']) == 'attachd'))
                            {$insert_mapdata[$i]['PTYP'] = 'RESI';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'condo' || strtolower($insert_mapdata[$i]['PTYP']) == 'condo/coop' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'condo hotel'))
                            {$insert_mapdata[$i]['PTYP'] = 'COND';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'multi-family' || strtolower($insert_mapdata[$i]['PTYP']) == 'mobile/manufactured' || strtolower($insert_mapdata[$i]['PTYP']) == 'in-park' || strtolower($insert_mapdata[$i]['PTYP']) == 'income' || strtolower($insert_mapdata[$i]['PTYP']) == 'duplex' || strtolower($insert_mapdata[$i]['PTYP']) == 'quad plex' || strtolower($insert_mapdata[$i]['PTYP']) == 'triplex'))
                            {$insert_mapdata[$i]['PTYP'] = 'MULT';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'manufactured on leased lot' || strtolower($insert_mapdata[$i]['PTYP']) == 'multi' || strtolower($insert_mapdata[$i]['PTYP']) == 'multifamily (5+ units)' || strtolower($insert_mapdata[$i]['PTYP']) == 'res-mfg'))
                            {$insert_mapdata[$i]['PTYP'] = 'MANU';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'farms' || strtolower($insert_mapdata[$i]['PTYP']) == 'farm' || strtolower($insert_mapdata[$i]['PTYP']) == 'agricultural'))
                            {$insert_mapdata[$i]['PTYP'] = 'FARM';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'vacant land' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial Land' || strtolower($insert_mapdata[$i]['PTYP']) == 'industrial land' || strtolower($insert_mapdata[$i]['PTYP']) == 'frm/for' || strtolower($insert_mapdata[$i]['PTYP']) == 'reconly' || strtolower($insert_mapdata[$i]['PTYP']) == 'res/rec'))
                            {$insert_mapdata[$i]['PTYP'] = 'VACL';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'busop' || strtolower($insert_mapdata[$i]['PTYP']) == 'business'))
                            {$insert_mapdata[$i]['PTYP'] = 'BUSO';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'manufac' || strtolower($insert_mapdata[$i]['PTYP']) == 'com/ind' || strtolower($insert_mapdata[$i]['PTYP']) == 'industr' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial industrial' || strtolower($insert_mapdata[$i]['PTYP']) == 'office' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial improved' || strtolower($insert_mapdata[$i]['PTYP']) == 'liv-wrk' || strtolower($insert_mapdata[$i]['PTYP']) == 'industrial' || strtolower($insert_mapdata[$i]['PTYP']) == 'warehse'  || strtolower($insert_mapdata[$i]['PTYP']) == 'comm' || strtolower($insert_mapdata[$i]['PTYP']) == 'other'  || strtolower($insert_mapdata[$i]['PTYP']) == 'lt-indu' || strtolower($insert_mapdata[$i]['PTYP']) == 'church' || strtolower($insert_mapdata[$i]['PTYP']) == 'recreat' || strtolower($insert_mapdata[$i]['PTYP']) == 'mobl-pk' || strtolower($insert_mapdata[$i]['PTYP']) == 'hotel/m'))
                            {$insert_mapdata[$i]['PTYP'] = 'MULT';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'dock' || strtolower($insert_mapdata[$i]['PTYP']) == 'boat slips'))
                            {$insert_mapdata[$i]['PTYP'] = 'DOCK';}
                            else
                            {
                                $insert_mapdata[$i]['PTYP'] = $insert_mapdata[$i]['PTYP'];
                            }
                            //end ptoperty type
                            //Add property status
                            if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Closed' || $insert_mapdata[$i]['ST'] == 'SOLD' || $insert_mapdata[$i]['ST'] == 'Sold Not Listed'))
                            {$insert_mapdata[$i]['ST'] = 'S';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Active'))
                            {$insert_mapdata[$i]['ST'] = 'A';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Pending' || $insert_mapdata[$i]['ST'] == 'In Due Diligence' || $insert_mapdata[$i]['ST'] == 'Backup' || $insert_mapdata[$i]['ST'] == 'Bumpable Buyer'))
                            {$insert_mapdata[$i]['ST'] = 'P';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Short Sale Pending' || $insert_mapdata[$i]['ST'] == 'In Due Diligence'))
                            {$insert_mapdata[$i]['ST'] = 'PS';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Contingent'))
                            {$insert_mapdata[$i]['ST'] = 'CT';}
                            else
                            {
                                $insert_mapdata[$i]['ST'] = $insert_mapdata[$i]['ST'];
                            }
                            // end add property status
                            //Check status
                            if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'S'))
                            {
                                $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['SP'];
                            }
                            else
                            {
                                $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['LP'];
                            }
                            $HSN = '';$DRP = '';$STR = '';$SSUF = '';$DRS = '';$UNT = '';$CIT = '';$STA = '';$ZIP = '';
                    
                            if(!empty($insert_mapdata[$i]['HSN']))
                                $HSN = $insert_mapdata[$i]['HSN'].' ';
                            if(!empty($insert_mapdata[$i]['DRP']))
                                $DRP = $insert_mapdata[$i]['DRP'].' ';
                            if(!empty($insert_mapdata[$i]['STR']))
                                $STR = $insert_mapdata[$i]['STR'].' ';
                            if(!empty($insert_mapdata[$i]['SSUF']))
                                $SSUF = $insert_mapdata[$i]['SSUF'].' ';
                            if(!empty($insert_mapdata[$i]['DRS']))
                                $DRS = $insert_mapdata[$i]['DRS'].' ';
                            if(!empty($insert_mapdata[$i]['UNT']))
                                $UNT = '#'.$insert_mapdata[$i]['UNT'].' ';
                            if(!empty($insert_mapdata[$i]['CIT']))
                                $CIT = ', '.$insert_mapdata[$i]['CIT'].', ';
                            if(!empty($insert_mapdata[$i]['STA']))
                                $STA = $insert_mapdata[$i]['STA'].' ';
                            if(!empty($insert_mapdata[$i]['ZIP']))
                                $ZIP = $insert_mapdata[$i]['ZIP'].' ';

                            $full_address = $HSN.$DRP.$STR.$SSUF.$DRS.$UNT;
                            $full_address = substr($full_address,0,-1);
                            $full_address .= $CIT.$STA.$ZIP;
                            
                            //Insert address and hod manually
                            if($mls_id == '1')
                            {
                                $insert_mapdata[$i]['full_address'] = substr($full_address,0,-1);
                            }  
                            if($mls_id == '2')
                            {
                                //$insert_mapdata[$i]['full_address'] = !empty($odata['field_StreetNoIDX'])?$odata['field_StreetNoIDX'].',':''.!empty($odata['field_StreetNameIDX'])?$odata['field_StreetNameIDX'].',':'';
                                $insert_mapdata[$i]['full_address']  = !empty($odata['field_StreetNoIDX'])?$odata['field_StreetNoIDX'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_StreetNameIDX'])?$odata['field_StreetNameIDX'].' ':'';
                                $insert_mapdata[$i]['full_address']  = substr($insert_mapdata[$i]['full_address'],0,-1);
                                $insert_mapdata[$i]['HOD'] = !empty($odata['field_HOAAnnualDues'])?$odata['field_HOAAnnualDues'].',':''.!empty($odata['field_HOAAnnualDuesPeriod'])?$odata['field_HOAAnnualDuesPeriod'].',':'';
                                $insert_mapdata[$i]['HOD'] = substr($insert_mapdata[$i]['HOD'],0,-1);
                            }
                            if($mls_id == '4')
                            {
                                //$insert_mapdata[$i]['full_address'] = !empty($odata['field_LIST_31'])?$odata['field_LIST_31'].',':''.!empty($odata['field_LIST_33'])?$odata['field_LIST_33'].',':''.!empty($odata['field_LIST_34'])?$odata['field_LIST_34'].',':''.!empty($odata['field_LIST_36'])?$odata['field_LIST_36'].',':''.!empty($odata['field_LIST_37'])?$odata['field_LIST_37'].',':''.!empty($odata['field_LIST_35'])?$odata['field_LIST_35'].',':'';
                                $insert_mapdata[$i]['full_address'] =  !empty($odata['field_LIST_31'])?$odata['field_LIST_31'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_33'])?$odata['field_LIST_33'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_34'])?$odata['field_LIST_34'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_36'])?$odata['field_LIST_36'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_37'])?$odata['field_LIST_37'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_35'])?$odata['field_LIST_35'].' ':'';
                                $insert_mapdata[$i]['full_address'] = substr($insert_mapdata[$i]['full_address'],0,-1);

                                if(!empty($insert_mapdata[$i]['LN']))                        
                                {   
                                    //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                                    $insert_mapdata[$i]['LN'] =substr($insert_mapdata[$i]['LN'], 3);
                                }
                            }
                              
                            //set lat long
                            if(!empty($insert_mapdata[$i]['LAT']) && !empty($insert_mapdata[$i]['LONGI']))                        
                            {   
                                //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                                $insert_mapdata[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT("POINT(", '.$insert_mapdata[$i]["LAT"].'," ",'.$insert_mapdata[$i]["LONGI"].',")"))';
                            }
                            else
                            {
                                $insert_mapdata[$i]['LngLatCoords']='';
                            }

                            //pr($insert_mapdata);
                            //pr($res);exit;
                            if(empty($res)) //Insert data
                            {
                                //pr($insert_mapdata);
                                $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                                $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                                if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'equity ownership' || strtolower($insert_mapdata[$i]['PTYP']) == 'land auction' || strtolower($insert_mapdata[$i]['PTYP']) == 'rental' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'special use'))
                                {unset($insert_mapdata[$i]);}
                                /*if(count($insert_mapdata) >= 100)
                                { 
                                    $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                    $i = 0;
                                    unset($insert_mapdata);
                                }*/
                                $i++;
                            }
                            else //Update data
                            {
                                $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                                if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'equity ownership' || strtolower($insert_mapdata[$i]['PTYP']) == 'land auction' || strtolower($insert_mapdata[$i]['PTYP']) == 'rental' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'special use'))
                                {unset($insert_mapdata[$i]);}
                                $cdata[$j] = $insert_mapdata[$i];
                                unset($insert_mapdata[$i]);
                                /*if(count($cdata) >= 100)
                                {
                                    $this->mls_model->update_batch_record($cdata,$our_database_table,'LN');
                                    $j = 0;
                                    unset($cdata);
                                }*/
                                $j++;
                            }
                        }
                        else
                        {
                            echo 'Mapping not assigned.';
                        }

                    }
                }

                //pr($insert_mapdata);exit;
                echo 'Insert -'.count($insert_mapdata);
                echo ' Update -'.count($cdata);
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    //echo $this->db->last_query();
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }

                //exit;

                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'LN');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'LN');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_property_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }

    function import_property_map_indi()
    {
        ini_set('memory_limit','-1');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        $mls_id = $this->uri->segment(4);
        //$cron_time = $this->uri->segment(5);
        
        $cron_time = 'cron_time';
            
        //Get last updated date
        $match = array('mls_id' => $mls_id,'table_type' => '1');
        $last_updated_offset   = $this->obj->select_records_common('mls_last_updated_offset_data','',$match,'','=','','','','','');        
        
        $num    = 2000;
        $offset = $this->uri->segment(5);
        //$offset = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
        //$offset = 100000;
        //Get trasection table mapping
        $match = array('mls_id' => $mls_id);
        $mls_tables_data = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');

        $main_table = $mls_tables_data[0]['main_table'];
        
        $our_database_table = 'mls_property_list_master';
        $table_id = 1;
       
        //Map school data
        if(!empty($main_table))
        {
            /*$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','','','','','','','','1');    
            
            if($mapping_total < $offset)
            {
                //Remove cron
                if(!empty($cron_time))
                {    
                    //$cron_field=explode('-',$cron_time);
                    $output = shell_exec('crontab -l');
                    //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
                    $url = base_url().'superadmin/mls_import/import_property_map/'.$mls_id;
                    
                    /*$minute  = $cron_field[0];
                    $hour    = $cron_field[1];
                    $day     = $cron_field[2];
                    $month   = $cron_field[3];*/
                    /*$minute   = '*//*5'; 
                    $hour     = '*'; 
                    $day      = '*'; 
                    $month    = '*';
                    echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
                    $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
                    //$cronjob = ('* * * * * /usr/local/bin/php /home/dldl1330/public_html/new/mailchimp.php');
                    //Copy cron tab and remove string
                    if (strstr($output, $cronjob)) 
                    {
                       echo 'found';
                       $newcron = str_replace($cronjob,"",$output);
                       file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
                       echo exec('crontab ../../../../tmp/crontab.txt'); 
                    } 
                    else 
                    {
                       echo 'not found';
                    }
                }
                echo 'All data inserted.';exit;
            }
            //Insert cron start data
            $field_data_cron = array(
                'cron_name'    => 'import_property_map_start',
                'p_type'       => $mls_id,
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);
            $off = !empty($last_updated_offset[0]['last_updated_offset'])?$last_updated_offset[0]['last_updated_offset']:'0';
            $lastoffset=$num + $off;
            $last_data = array( 
                    'mls_id'            => $mls_id,
                    'table_type'        => '1',
                    'last_updated_offset' => $lastoffset,
                    'modified_date'     => date('Y-m-d H:i:s')
                    );

            if(!empty($last_updated_offset))
            {
                //Update last updated offset
                $last_data['id']  = $last_updated_offset[0]['id'];
                $this->mls_model->update_common('mls_last_updated_offset_data',$last_data);
            }    
            else
            {
                $last_data['created_date']=date('Y-m-d H:i:s');
                
                //Insert last updated offset
                $this->mls_model->insert_common('mls_last_updated_offset_data',$last_data);    
            }*/
            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique id
            $field = array('mls_field');
            $match = array('mls_master_field'=>'LN','mls_id'=>$mls_id);
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        
            
            //pr($mapping_data_id);exit;
            $mls_default_id   = array('LAG','CLA','LO','COLO','SD');
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','',$num,$offset);    
            //$mapping_total        = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','','','','','','','','1');    
            //pr($mapping_table_data);exit;
            //echo $this->db->last_query();
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            if(!empty($mapping_table_data))
            {
                foreach($mapping_table_data as $odata)
                {
                    //pr($odata);
                    /*if(!empty($mapping_data_id))
                    {
                        $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                        $match=array('LN' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                    }  
                    else
                    {
                        $match=array('property_id' => $odata['ID'],'mls_id' => $mls_id);
                    }*/
                    $match=array('property_id' => $odata['ID'],'mls_id' => $mls_id);
                    $fields=array('id');
                    $this->db->query('SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED');
                    $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');
                    $this->db->query('COMMIT');

                    //echo $this->db->last_query();exit;
                    $insert_mapdata[$i]['mls_id']   =  $mls_id;
                    $insert_mapdata[$i]['status']   =  1;
                    if(!empty($odata) && count($odata) > 0)              
                    {
                        if(!empty($mapping_data))
                        {
                            foreach($mapping_data as $row)
                            {
                                $mls_field          = $row['mls_master_field'];
                                $mls_map_field      = explode('.',$row['mls_field']);
                                $mls_map_table      = $row['mls_field_table'];
                                $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                                $insert_mapdata[$i][$mls_field] = '';
                                //If Field is id in our database
                                if(in_array($mls_field,$mls_default_id))
                                {
                                    //Case : id -> id
                                    if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                    {
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        {
                                            if($mls_field == 'LAG' || $mls_field == 'CLA')
                                            {
                                                $field = array('id','member_mls_id');
                                                $match = array('member_mls_id' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_member_data',$field,$match,'like','','');                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['member_mls_id'];
                                                }                                        
                                            } 
                                            if($mls_field == 'LO' || $mls_field == 'COLO')
                                            {
                                                $field = array('id','office_mls_id');
                                                $match = array('office_mls_id' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_office_data',$field,$match,'','=','');                                                
                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['office_mls_id'];
                                                }
                                            }
                                            if($mls_field == 'SD')
                                            {
                                                $field = array('id','school_district_code');
                                                $match = array('school_district_code' => trim($field_value));
                                                $resdata  = $this->obj->select_records_common('mls_school_data',$field,$match,'','=','');                                                
                                                
                                                if(!empty($resdata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $resdata[0]['school_district_code'];
                                                }
                                            } 
                                            
                                        }               
                                    }
                                    else
                                    {
                                        //Case : id -> value
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  trim($field_value);
                                            /*if($mls_field == 'LAG' || $mls_field == 'CLA')
                                            {
                                                $match = array('first_name'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_member_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['member_mls_id'];
                                                }
                                                else
                                                {
                                                   $member_id='';
                                                   //insert record in our transection table
                                                   $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'member_mls_id'               => $member_id,
                                                        'first_name'                  => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_member_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $member_id;
                                                   }
                                                }

                                            } 
                                            if($mls_field == 'LO' || $mls_field == 'COLO')
                                            {
                                                $match = array('office_name'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_office_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['office_mls_id'];
                                                }
                                                else
                                                {
                                                   //insert record in our transection table
                                                    $office_id='';
                                                    $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'office_mls_id'               => $office_id,
                                                        'office_name'                 => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_office_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $office_id;
                                                   }
                                                }
                                                
                                            }
                                            if($mls_field == 'SD')
                                            {
                                                $match = array('school_district_description'=>trim($field_value));
                                                $retivedata  = $this->obj->select_records_common('mls_school_data','',$match,'','=','','1','1');        
                                                if(!empty($retivedata))
                                                {
                                                    $insert_mapdata[$i][$mls_field] =  $retivedata[0]['school_district_code'];
                                                }
                                                else
                                                {
                                                   //insert record in our transection table
                                                   $field_value1=str_replace(' ','',$field_value);
                                                   $school_distric_code = substr(trim($field_value1),0,3);

                                                   $sdata=array(
                                                        'mls_id'                      => $mls_id,
                                                        'school_district_code'        => $school_distric_code,
                                                        'school_district_description' => trim($field_value),
                                                        'mls_id'                      => $mls_id,
                                                        'created_date'                => date('Y-m-d h:i:s')
                                                    );
                                                   
                                                   $lid = $this->mls_model->insert_common('mls_school_data',$sdata);
                                                   if(!empty($lid))
                                                   {
                                                        $insert_mapdata[$i][$mls_field] =  $school_distric_code;
                                                   }
                                                }
                                            } */
                                            //$field = array($mls_map_tran_field[1]);                                    
                                        }

                                    }
                                }
                                //If Field is value in our database
                                else
                                {
                                    if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                                    {
                                        //Case : value -> id
                                        $field_value = $odata[$mls_map_field[1]]; 
                                        if(!empty($field_value))
                                        { 
                                            $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                            if(!empty($mlsmap_fields))
                                            {
                                                $msfield= array();
                                                foreach ($mlsmap_fields as $value) 
                                                {
                                                   $msfield[] = $value['Field'];
                                                }
                                            
                                           
                                                if(in_array($mls_map_field[1],$msfield))
                                                {
                                                    $field = array($mls_map_tran_field[1]);
                                                    $match = array($mls_map_field[1]=>$field_value);
                                                    $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                                    if(!empty($retivedata))
                                                    {
                                                        $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $insert_mapdata[$i][$mls_field] = '';
                                            }
                                            
                                        }
                                        
                                    }
                                    else
                                    {
                                        //Case : value -> value
                                        $insert_mapdata[$i][$mls_field] =  mysql_real_escape_string($odata[$mls_map_field[1]]);
                                    }
                                }
                            }

                            //Add property type
                            if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'resid' || strtolower($insert_mapdata[$i]['PTYP']) == 'single family detached' || strtolower($insert_mapdata[$i]['PTYP']) == 'sfr' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'preconstruction'|| strtolower($insert_mapdata[$i]['PTYP']) == 'townhouse' || strtolower($insert_mapdata[$i]['PTYP']) == 'attachd'))
                            {$insert_mapdata[$i]['PTYP'] = 'RESI';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'condo' || strtolower($insert_mapdata[$i]['PTYP']) == 'condo/coop' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'condo hotel'))
                            {$insert_mapdata[$i]['PTYP'] = 'COND';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'multi-family' || strtolower($insert_mapdata[$i]['PTYP']) == 'mobile/manufactured' || strtolower($insert_mapdata[$i]['PTYP']) == 'in-park' || strtolower($insert_mapdata[$i]['PTYP']) == 'income' || strtolower($insert_mapdata[$i]['PTYP']) == 'duplex' || strtolower($insert_mapdata[$i]['PTYP']) == 'quad plex' || strtolower($insert_mapdata[$i]['PTYP']) == 'triplex'))
                            {$insert_mapdata[$i]['PTYP'] = 'MULT';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'manufactured on leased lot' || strtolower($insert_mapdata[$i]['PTYP']) == 'multi' || strtolower($insert_mapdata[$i]['PTYP']) == 'multifamily (5+ units)' || strtolower($insert_mapdata[$i]['PTYP']) == 'res-mfg'))
                            {$insert_mapdata[$i]['PTYP'] = 'MANU';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'farms' || strtolower($insert_mapdata[$i]['PTYP']) == 'farm' || strtolower($insert_mapdata[$i]['PTYP']) == 'agricultural'))
                            {$insert_mapdata[$i]['PTYP'] = 'FARM';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'vacant land' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial Land' || strtolower($insert_mapdata[$i]['PTYP']) == 'industrial land' || strtolower($insert_mapdata[$i]['PTYP']) == 'frm/for' || strtolower($insert_mapdata[$i]['PTYP']) == 'reconly' || strtolower($insert_mapdata[$i]['PTYP']) == 'res/rec'))
                            {$insert_mapdata[$i]['PTYP'] = 'VACL';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'busop' || strtolower($insert_mapdata[$i]['PTYP']) == 'business'))
                            {$insert_mapdata[$i]['PTYP'] = 'BUSO';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'manufac' || strtolower($insert_mapdata[$i]['PTYP']) == 'com/ind' || strtolower($insert_mapdata[$i]['PTYP']) == 'industr' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial industrial' || strtolower($insert_mapdata[$i]['PTYP']) == 'office' || strtolower($insert_mapdata[$i]['PTYP']) == 'commercial improved' || strtolower($insert_mapdata[$i]['PTYP']) == 'liv-wrk' || strtolower($insert_mapdata[$i]['PTYP']) == 'industrial' || strtolower($insert_mapdata[$i]['PTYP']) == 'warehse'  || strtolower($insert_mapdata[$i]['PTYP']) == 'comm' || strtolower($insert_mapdata[$i]['PTYP']) == 'other'  || strtolower($insert_mapdata[$i]['PTYP']) == 'lt-indu' || strtolower($insert_mapdata[$i]['PTYP']) == 'church' || strtolower($insert_mapdata[$i]['PTYP']) == 'recreat' || strtolower($insert_mapdata[$i]['PTYP']) == 'mobl-pk' || strtolower($insert_mapdata[$i]['PTYP']) == 'hotel/m'))
                            {$insert_mapdata[$i]['PTYP'] = 'MULT';}
                            else if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'dock' || strtolower($insert_mapdata[$i]['PTYP']) == 'boat slips'))
                            {$insert_mapdata[$i]['PTYP'] = 'DOCK';}
                            else
                            {
                                $insert_mapdata[$i]['PTYP'] = $insert_mapdata[$i]['PTYP'];
                            }
                            //end ptoperty type
                            //Add property status
                            if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Closed' || $insert_mapdata[$i]['ST'] == 'SOLD' || $insert_mapdata[$i]['ST'] == 'Sold Not Listed'))
                            {$insert_mapdata[$i]['ST'] = 'S';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Active'))
                            {$insert_mapdata[$i]['ST'] = 'A';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Pending' || $insert_mapdata[$i]['ST'] == 'In Due Diligence' || $insert_mapdata[$i]['ST'] == 'Backup' || $insert_mapdata[$i]['ST'] == 'Bumpable Buyer'))
                            {$insert_mapdata[$i]['ST'] = 'P';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Short Sale Pending' || $insert_mapdata[$i]['ST'] == 'In Due Diligence'))
                            {$insert_mapdata[$i]['ST'] = 'PS';}
                            else if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'Contingent'))
                            {$insert_mapdata[$i]['ST'] = 'CT';}
                            else
                            {
                                $insert_mapdata[$i]['ST'] = $insert_mapdata[$i]['ST'];
                            }
                            // end add property status
                            //Check status
                            if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'S'))
                            {
                                $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['SP'];
                            }
                            else
                            {
                                $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['LP'];
                            }
                            $HSN = '';$DRP = '';$STR = '';$SSUF = '';$DRS = '';$UNT = '';$CIT = '';$STA = '';$ZIP = '';
                    
                            if(!empty($insert_mapdata[$i]['HSN']))
                                $HSN = $insert_mapdata[$i]['HSN'].' ';
                            if(!empty($insert_mapdata[$i]['DRP']))
                                $DRP = $insert_mapdata[$i]['DRP'].' ';
                            if(!empty($insert_mapdata[$i]['STR']))
                                $STR = $insert_mapdata[$i]['STR'].' ';
                            if(!empty($insert_mapdata[$i]['SSUF']))
                                $SSUF = $insert_mapdata[$i]['SSUF'].' ';
                            if(!empty($insert_mapdata[$i]['DRS']))
                                $DRS = $insert_mapdata[$i]['DRS'].' ';
                            if(!empty($insert_mapdata[$i]['UNT']))
                                $UNT = '#'.$insert_mapdata[$i]['UNT'].' ';
                            if(!empty($insert_mapdata[$i]['CIT']))
                                $CIT = ', '.$insert_mapdata[$i]['CIT'].', ';
                            if(!empty($insert_mapdata[$i]['STA']))
                                $STA = $insert_mapdata[$i]['STA'].' ';
                            if(!empty($insert_mapdata[$i]['ZIP']))
                                $ZIP = $insert_mapdata[$i]['ZIP'].' ';

                            $full_address = $HSN.$DRP.$STR.$SSUF.$DRS.$UNT;
                            $full_address = substr($full_address,0,-1);
                            $full_address .= $CIT.$STA.$ZIP;
                            
                            //Insert address and hod manually
                            if($mls_id == '1')
                            {
                                $insert_mapdata[$i]['full_address'] = substr($full_address,0,-1);
                            }  
                            if($mls_id == '2')
                            {
                                //$insert_mapdata[$i]['full_address'] = !empty($odata['field_StreetNoIDX'])?$odata['field_StreetNoIDX'].',':''.!empty($odata['field_StreetNameIDX'])?$odata['field_StreetNameIDX'].',':'';
                                $insert_mapdata[$i]['full_address']  = !empty($odata['field_StreetNoIDX'])?$odata['field_StreetNoIDX'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_StreetNameIDX'])?$odata['field_StreetNameIDX'].' ':'';
                                $insert_mapdata[$i]['full_address']  = substr($insert_mapdata[$i]['full_address'],0,-1);
                                $insert_mapdata[$i]['HOD'] = !empty($odata['field_HOAAnnualDues'])?$odata['field_HOAAnnualDues'].',':''.!empty($odata['field_HOAAnnualDuesPeriod'])?$odata['field_HOAAnnualDuesPeriod'].',':'';
                                $insert_mapdata[$i]['HOD'] = substr($insert_mapdata[$i]['HOD'],0,-1);
                            }
                            if($mls_id == '4')
                            {
                                //$insert_mapdata[$i]['full_address'] = !empty($odata['field_LIST_31'])?$odata['field_LIST_31'].',':''.!empty($odata['field_LIST_33'])?$odata['field_LIST_33'].',':''.!empty($odata['field_LIST_34'])?$odata['field_LIST_34'].',':''.!empty($odata['field_LIST_36'])?$odata['field_LIST_36'].',':''.!empty($odata['field_LIST_37'])?$odata['field_LIST_37'].',':''.!empty($odata['field_LIST_35'])?$odata['field_LIST_35'].',':'';
                                $insert_mapdata[$i]['full_address'] =  !empty($odata['field_LIST_31'])?$odata['field_LIST_31'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_33'])?$odata['field_LIST_33'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_34'])?$odata['field_LIST_34'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_36'])?$odata['field_LIST_36'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_37'])?$odata['field_LIST_37'].' ':'';
                                $insert_mapdata[$i]['full_address'] .= !empty($odata['field_LIST_35'])?$odata['field_LIST_35'].' ':'';
                                $insert_mapdata[$i]['full_address'] = substr($insert_mapdata[$i]['full_address'],0,-1);

                                if(!empty($insert_mapdata[$i]['LN']))                        
                                {   
                                    //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                                    $insert_mapdata[$i]['LN'] =substr($insert_mapdata[$i]['LN'], 3);
                                }
                            }
                              
                            //set lat long
                            if(!empty($insert_mapdata[$i]['LAT']) && !empty($insert_mapdata[$i]['LONGI']))                        
                            {   
                                //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                                $insert_mapdata[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT("POINT(", '.$insert_mapdata[$i]["LAT"].'," ",'.$insert_mapdata[$i]["LONGI"].',")"))';
                            }
                            else
                            {
                                $insert_mapdata[$i]['LngLatCoords']='';
                            }

                            //pr($insert_mapdata);
                            //pr($res);exit;
                            if(empty($res)) //Insert data
                            {
                                //pr($insert_mapdata);
                                $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                                $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                                if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'equity ownership' || strtolower($insert_mapdata[$i]['PTYP']) == 'land auction' || strtolower($insert_mapdata[$i]['PTYP']) == 'rental' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'special use'))
                                {unset($insert_mapdata[$i]);}
                                /*if(count($insert_mapdata) >= 100)
                                { 
                                    $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                                    $i = 0;
                                    unset($insert_mapdata);
                                }*/
                                $i++;
                            }
                            else //Update data
                            {
                                $insert_mapdata[$i]['modified_date']      = date('Y-m-d h:i:s');
                                if(!empty($insert_mapdata[$i]['PTYP']) && (strtolower($insert_mapdata[$i]['PTYP']) == 'equity ownership' || strtolower($insert_mapdata[$i]['PTYP']) == 'land auction' || strtolower($insert_mapdata[$i]['PTYP']) == 'rental' || strtolower($insert_mapdata[$i]['PTYP']) == 'detachd' || strtolower($insert_mapdata[$i]['PTYP']) == 'special use'))
                                {unset($insert_mapdata[$i]);}
                                $cdata[$j] = $insert_mapdata[$i];
                                unset($insert_mapdata[$i]);
                                /*if(count($cdata) >= 100)
                                {
                                    $this->mls_model->update_batch_record($cdata,$our_database_table,'LN');
                                    $j = 0;
                                    unset($cdata);
                                }*/
                                $j++;
                            }
                        }
                        else
                        {
                            echo 'Mapping not assigned.';
                        }

                    }
                }

                //pr($insert_mapdata);exit;
                echo 'Insert -'.count($insert_mapdata);
                echo ' Update -'.count($cdata);
                //Insert more than 100
                if(!empty($insert_mapdata)  && count($insert_mapdata) > 0)
                {
                    $i = 0;
                    foreach ($insert_mapdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $idata[$i][$key]=$value;
                            
                            if(count($idata[$i]) == count($row))
                            {
                                if(count($idata) >= 100)
                                {
                                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                                    //echo $this->db->last_query();
                                    $i = 0; 
                                    unset($idata);
                                }
                            }
                        }
                        $i++;
                    }
                    unset($insert_mapdata);
                }

                //exit;

                //Update more than 100
                if(!empty($cdata) && count($cdata) > 0)
                {
                    $i = 0;
                    foreach ($cdata as $row) 
                    {
                        foreach ($row as $key=>$value) 
                        {
                            $udata[$i][$key]=$value;
                             
                            if(count($udata[$i]) == count($row))
                            {
                              if(count($udata) >= 100)
                                {   
                                    $this->mls_model->update_batch_record($udata,$our_database_table,'LN');
                                    $i = 0;
                                    unset($udata);
                                }  
                            }
                        }
                        $i++;
                    }
                    unset($cdata);                   
                }
                // insert data when record less then 100
                if(!empty($idata) && count($idata) > 0)
                {
                    $this->mls_model->insert_batch_common($idata,$our_database_table);
                    unset($idata);   
                }
                if(!empty($udata) && count($udata) > 0)
                {
                    $this->mls_model->update_batch_record($udata,$our_database_table,'LN');
                    //pr($cdata); exit;
                    unset($udata);  
                }
            }
            else
            {
                echo 'Data not found.';
            }
        
            /*//Insert cron end data
            $field_data_cron = array(
                'cron_name'=>'import_property_map_end',
                'begin_date'   => $num,
                'curr_date'    => $offset,
                'created_date'=>date('Y-m-d H:i:s')
            );
            $insert_cron_id_main = $this->mls_model->insert_cron_test($field_data_cron);*/

            if(!empty($insert_cron_id_main))
            {
                $db_name = $this->config->item('parent_db_name');
                $table = $db_name.'.cron_test';
                $field_data_cron_u = array('id'=>$insert_cron_id_main,'completed_date'=>date('Y-m-d H:i:s'));
                $insert_cron_id = $this->mls_model->update_cron_test($field_data_cron_u,$table);
            }
        }
        else
        {
            echo "Table not assigned.<br>";
        }
        echo 'done';
    }
    /*
    @Description: Function create cron
    @Author: Niral Patel
    @Input: - 
    @Output: - insert mls map data
    @Date: 20-02-2015*/
    function import_updated_property()
    {
        //Insert cron start data
        $field_data_cron = array(
            'cron_name'=>'import_property_map_start',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);
        $mls_id = $this->uri->segment(4);
        $num    = $this->uri->segment(5);
        $offset = $this->uri->segment(6);
        $cron_time = $this->uri->segment(7);

        $mls_id = $this->uri->segment(4);
        // Get maximum update date
        $field = array('ID','max(UD) as UD');
        $match = array('mls_id' =>$mls_id);
        $res_data=$this->mls_model->select_records3($field,'','','=','','1','0');
        //pr($res_data);exit;
        //Get trasection table mapping
        // Get selected tables
        $match = array('mls_id'=>$mls_id);
        $mls_tables_data = $this->obj->select_records_common('mls_livewire_table_mapping','',$match,'','=');

        $main_table = $mls_tables_data[0]['main_table'];
        
        $our_database_table = 'mls_property_list_master';
        $table_id = 1;

        //Map property data
        if(!empty($main_table))
        {

            //Get mapping data
            $field = array('id','mls_id','mls_master_field_id','mls_master_field','mls_field','mls_field_table','mls_transection_field');
            $match = array('mls_id'=>$mls_id,'table_id'=>$table_id);
            $mapping_data  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');                     
            
            //Get unique idate(format)
            $field = array('mls_field');
            $match = array('mls_master_field'=>'LN');
            $mapping_data_id  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        
            
            //Get updated date
            $field = array('mls_field');
            $match = array('mls_master_field'=>'UD');
            $mapping_updated_date  = $this->obj->select_records_common('mls_type_of_mls_mapping_trans',$field,$match,'','=','','','','','');        
            
            $mls_default_id   = array('LAG','CLA','LO','COLO','SD');
            $mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$main_table,'','','','','',$num,$offset);    
            //pr($res_data);exit;
            /*$now = date('Y-m-d h:i:s');
            if(!empty($res_data[0]['UD'])) 
            {
                $wherestring = '(mt.rc_ModifiedTime >="'.$res_data[0]['UD'].'" AND mt.rc_ModifiedTime <="'.$now.'")';
            }
            $table = $this->mls_master_db.'.'.$main_table.' mt';
            $fields = array('mt.*');
            $match = array('mt.mls_id'=>$mls_id);
            //$table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='',$having='',$or_where=''
            $mapping_table_data    = $this->obj->getmultiple_tables_records($table,$fields,'','','','','','','','','','',$wherestring);*/
            //echo $this->db->last_query();
            //pr($mapping_table_data);exit;
            //$mapping_table_data   = $this->obj->select_records_common($this->mls_master_db.'.'.$school_table_name);    
            
            $i = 0;
            $j = 0;
            foreach($mapping_table_data as $odata)
            {

                if(!empty($mapping_data_id))
                {
                    $mapp_id=explode('.',$mapping_data_id[0]['mls_field']);
                    $match=array('LN' => $odata[$mapp_id[1]],'mls_id' => $mls_id);
                }  
                else
                {
                    $match=array('property_id' => $odata['id'],'mls_id' => $mls_id);
                }
                $fields=array('id');
                $res   = $this->obj->select_records_common($our_database_table,$fields,$match,'','=');        
                $insert_mapdata[$i]['mls_id']   =  $mls_id;
                if(!empty($odata) && count($odata) > 0)              
                {
                    foreach($mapping_data as $row)
                    {
                        $mls_field          = $row['mls_master_field'];
                        $mls_map_field      = explode('.',$row['mls_field']);
                        $mls_map_table      = $row['mls_field_table'];
                        $mls_map_tran_field = explode('.',$row['mls_transection_field']);
                        $insert_mapdata[$i][$mls_field]='';
                        //If Field is id in our database
                        if(in_array($mls_field,$mls_default_id))
                        {
                            //Case : id -> id
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    if($mls_field == 'LAG' || $mls_field == 'CLA')
                                    {
                                        $field = array('id','member_mls_id');
                                        $match = array('member_mls_id' => trim($field_value));
                                        $resdata  = $this->obj->select_records_common('mls_member_data',$field,$match,'like','','');                                                
                                        
                                        if(!empty($resdata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $resdata[0]['member_mls_id'];
                                        }                                        
                                    } 
                                    if($mls_field == 'LO' || $mls_field == 'COLO')
                                    {
                                        $field = array('id','office_mls_id');
                                        $match = array('office_mls_id' => trim($field_value));
                                        $resdata  = $this->obj->select_records_common('mls_office_data',$field,$match,'','=','');                                                
                                        
                                        if(!empty($resdata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $resdata[0]['office_mls_id'];
                                        }
                                    }
                                    if($mls_field == 'SD')
                                    {
                                        $field = array('id','school_district_code');
                                        $match = array('school_district_code' => trim($field_value));
                                        $resdata  = $this->obj->select_records_common('mls_school_data',$field,$match,'','=','');                                                
                                        
                                        if(!empty($resdata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $resdata[0]['school_district_code'];
                                        }
                                    } 
                                }               
                            }
                            else
                            {
                                //Case : id -> value
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                {
                                    $insert_mapdata[$i][$mls_field] =  trim($field_value);
                                    /*if($mls_field == 'LAG' || $mls_field == 'CLA')
                                    {
                                        $match = array('first_name'=>trim($field_value));
                                        $retivedata  = $this->obj->select_records_common('mls_member_data','',$match,'','=','','1','1');        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['member_mls_id'];
                                        }
                                        else
                                        {
                                           $member_id='';
                                           //insert record in our transection table
                                           $sdata=array(
                                                'mls_id'                      => $mls_id,
                                                'member_mls_id'               => $member_id,
                                                'first_name'                  => trim($field_value),
                                                'mls_id'                      => $mls_id,
                                                'created_date'                => date('Y-m-d h:i:s')
                                            );
                                           
                                           $lid = $this->mls_model->insert_common('mls_member_data',$sdata);
                                           if(!empty($lid))
                                           {
                                                $insert_mapdata[$i][$mls_field] =  $member_id;
                                           }
                                        }

                                    } 
                                    if($mls_field == 'LO' || $mls_field == 'COLO')
                                    {
                                        $match = array('office_name'=>trim($field_value));
                                        $retivedata  = $this->obj->select_records_common('mls_office_data','',$match,'','=','','1','1');        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['office_mls_id'];
                                        }
                                        else
                                        {
                                           //insert record in our transection table
                                            $office_id='';
                                            $sdata=array(
                                                'mls_id'                      => $mls_id,
                                                'office_mls_id'               => $office_id,
                                                'office_name'                 => trim($field_value),
                                                'mls_id'                      => $mls_id,
                                                'created_date'                => date('Y-m-d h:i:s')
                                            );
                                           
                                           $lid = $this->mls_model->insert_common('mls_office_data',$sdata);
                                           if(!empty($lid))
                                           {
                                                $insert_mapdata[$i][$mls_field] =  $office_id;
                                           }
                                        }
                                        
                                    }
                                    if($mls_field == 'SD')
                                    {
                                        $match = array('school_district_description'=>trim($field_value));
                                        $retivedata  = $this->obj->select_records_common('mls_school_data','',$match,'','=','','1','1');        
                                        if(!empty($retivedata))
                                        {
                                            $insert_mapdata[$i][$mls_field] =  $retivedata[0]['school_district_code'];
                                        }
                                        else
                                        {
                                           //insert record in our transection table
                                           $field_value1=str_replace(' ','',$field_value);
                                           $school_distric_code = substr(trim($field_value1),0,3);

                                           $sdata=array(
                                                'mls_id'                      => $mls_id,
                                                'school_district_code'        => $school_distric_code,
                                                'school_district_description' => trim($field_value),
                                                'mls_id'                      => $mls_id,
                                                'created_date'                => date('Y-m-d h:i:s')
                                            );
                                           
                                           $lid = $this->mls_model->insert_common('mls_school_data',$sdata);
                                           if(!empty($lid))
                                           {
                                                $insert_mapdata[$i][$mls_field] =  $school_distric_code;
                                           }
                                        }
                                    } */
                                    //$field = array($mls_map_tran_field[1]);                                    
                                }

                            }
                        }
                        //If Field is value in our database
                        else
                        {
                            if(!empty($mls_map_table) && !empty($mls_map_tran_field))
                            {
                                //Case : value -> id
                                $field_value = $odata[$mls_map_field[1]]; 
                                if(!empty($field_value))
                                { 
                                    $mlsmap_fields = $this->obj->get_field($this->mls_master_db.'.'.$mls_map_table);
                                    if(!empty($mlsmap_fields))
                                    {
                                        $msfield= array();
                                        foreach ($mlsmap_fields as $value) 
                                        {
                                           $msfield[] = $value['Field'];
                                        }
                                    
                                   
                                        if(in_array($mls_map_field[1],$msfield))
                                        {
                                            $field = array($mls_map_tran_field[1]);
                                            $match = array($mls_map_field[1]=>$field_value);
                                            $retivedata  = $this->obj->select_records_common($this->mls_master_db.'.'.$mls_map_table,$field,$match,'','=','','1','1');        
                                            if(!empty($retivedata))
                                            {
                                                $insert_mapdata[$i][$mls_field] =  $retivedata[0][$mls_map_tran_field[1]];    
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $insert_mapdata[$i][$mls_field] = '';
                                    }
                                    
                                }
                                
                            }
                            else
                            {
                                //Case : value -> value
                                $insert_mapdata[$i][$mls_field] =  mysql_real_escape_string($odata[$mls_map_field[1]]);
                            }
                        }
                    }
                    //Check status
                    if(!empty($insert_mapdata[$i]['ST']) && ($insert_mapdata[$i]['ST'] == 'S' || $insert_mapdata[$i]['ST'] == 'Closed'))
                    {
                        $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['SP'];
                    }
                    else
                    {
                        $insert_mapdata[$i]['display_price'] = $insert_mapdata[$i]['LP'];
                    }
                    $HSN = '';$DRP = '';$STR = '';$SSUF = '';$DRS = '';$UNT = '';$CIT = '';$STA = '';$ZIP = '';
                    
                    if(!empty($insert_mapdata[$i]['HSN']))
                        $HSN = $insert_mapdata[$i]['HSN'].' ';
                    if(!empty($insert_mapdata[$i]['DRP']))
                        $DRP = $insert_mapdata[$i]['DRP'].' ';
                    if(!empty($insert_mapdata[$i]['STR']))
                        $STR = $insert_mapdata[$i]['STR'].' ';
                    if(!empty($insert_mapdata[$i]['SSUF']))
                        $SSUF = $insert_mapdata[$i]['SSUF'].',';
                    if(!empty($insert_mapdata[$i]['DRS']))
                        $DRS = $insert_mapdata[$i]['DRS'].',';
                    if(!empty($insert_mapdata[$i]['UNT']))
                        $UNT = '#'.$insert_mapdata[$i]['UNT'].' ';
                    if(!empty($insert_mapdata[$i]['CIT']))
                        $CIT = ','.$insert_mapdata[$i]['CIT'].' ';
                    if(!empty($insert_mapdata[$i]['STA']))
                        $STA = $insert_mapdata[$i]['STA'].' ';
                    if(!empty($insert_mapdata[$i]['ZIP']))
                        $ZIP = $insert_mapdata[$i]['ZIP'].' ';

                    $full_address = $HSN.$DRP.$STR.$SSUF.$DRS.$UNT.$CIT.$STA.$ZIP;
                    //Insert address and hod manually
                    if($mls_id == '7')
                    {
                        $insert_mapdata[$i]['full_address'] = !empty($odata['field_StreetNoIDX'])?$odata['field_StreetNoIDX'].',':''.!empty($odata['field_StreetNameIDX'])?$odata['field_StreetNameIDX'].',':'';
                        $insert_mapdata[$i]['full_address'] = substr($insert_mapdata[$i]['full_address'],0,-1);
                        $insert_mapdata[$i]['HOD'] = !empty($odata['field_HOAAnnualDues'])?$odata['field_HOAAnnualDues'].',':''.!empty($odata['field_HOAAnnualDuesPeriod'])?$odata['field_HOAAnnualDuesPeriod'].',':'';
                        $insert_mapdata[$i]['HOD'] = substr($insert_mapdata[$i]['HOD'],0,-1);
                    }
                    else
                    {
                        $insert_mapdata[$i]['full_address'] = substr($full_address,0,-1);
                    }    
                    if(!empty($insert_mapdata[$i]['LAT']) && !empty($insert_mapdata[$i]['LONGI']))                        
                    {   
                        //$data[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT(POINT(, '.$data[$i]["LAT"].',' ','.$data[$i]["LONGI"].',)))';
                        $insert_mapdata[$i]['LngLatCoords'] ='GEOMETRYFROMTEXT(CONCAT("POINT(", '.$insert_mapdata[$i]["LAT"].'," ",'.$insert_mapdata[$i]["LONGI"].',")"))';
                    }
                    else
                    {
                        $insert_mapdata[$i]['LngLatCoords']='';
                    }
                    //pr($insert_mapdata);exit;
                    if(empty($res)) //Insert data
                    {
                        //pr($insert_mapdata);
                        $insert_mapdata[$i]['created_date']       = date('Y-m-d h:i:s');
                        if(count($insert_mapdata) >= 100)
                        { 
                            $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                            $i = 0;
                            unset($insert_mapdata);
                        }
                        $i++;
                    }
                    else //Update data
                    {
                        
                        $cdata[$j] = $insert_mapdata[$i];
                        unset($insert_mapdata[$i]);
                        if(count($cdata) >= 100)
                        {
                            $this->mls_model->update_batch_record($cdata,$our_database_table,'property_id');
                            $j = 0;
                            unset($cdata);
                        }
                        $j++;
                    }
                }
            }
           
            // insert data when record less then 100
            if(!empty($insert_mapdata) && count($insert_mapdata) > 0)
            {
                $this->mls_model->insert_batch_common($insert_mapdata,$our_database_table);
                unset($insert_mapdata);   
            }
            if(!empty($cdata) && count($cdata) > 0)
            {
                $this->mls_model->update_batch_record($cdata,$our_database_table,'property_id');
                //pr($cdata); exit;
                unset($cdata);  
            }
        }
        //Insert cron end data
        $field_data_cron = array(
            'cron_name'=>'import_property_map_end',
            'created_date'=>date('Y-m-d H:i:s')
        );
        $this->mls_model->insert_cron_test($field_data_cron);
        //Remove cron
        if(!empty($cron_time))
        {    
            $cron_field=explode('-',$cron_time);
            $output = shell_exec('crontab -l');
            //$cron_time=$minute.'-'.$hour.'-'.$day.'-'.$month;
            $url = base_url().'superadmin/mls_import/import_property_map/'.$mls_id.'/'.$num.'/'.$offset.'/'.$cron_time;
            $minute  = $cron_field[0];
            $hour    = $cron_field[1];
            $day     = $cron_field[2];
            $month   = $cron_field[3];
            echo $minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url;
            $cronjob = ($minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url);
            
            //Copy cron tab and remove string
            if (strstr($output, $cronjob)) 
            {
               echo 'found';
               $newcron = str_replace($cronjob,"",$output);
               file_put_contents('../../../../tmp/crontab.txt', $newcron.PHP_EOL);
               echo exec('crontab ../../../../tmp/crontab.txt'); 
            } 
            else 
            {
               echo 'not found';
            }
        }
        echo 'done';
    }
    
    

}