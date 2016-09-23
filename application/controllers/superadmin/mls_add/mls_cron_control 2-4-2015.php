<?php 
/*
    @Description: mls controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_cron_control extends CI_Controller
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
    }
	

    /*
    @Description: Function for Get All Envelope List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all Envelope list
    @Date: 20-02-2015
    */

    public function index()
    {}
    function property_image_list()
    {
            $property_id = $this->uri->segment(4);
            $searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
            $searchtext = mysql_real_escape_string($this->input->post('searchtext'));
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $searchopt = $this->input->post('searchopt');
            $perpage = trim($this->input->post('perpage'));
            $allflag = $this->input->post('allflag');
            $data['sortfield']		= 'id';
            $data['sortby']			= 'desc';

            if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $this->session->unset_userdata('property_image_sortsearchpage_data');
            }
            $searchsort_session = $this->session->userdata('property_image_sortsearchpage_data');

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
                    $data['searchtext'] = stripslashes($searchtext);
            } else {
                    if(empty($allflag))
                    {
                            if(!empty($searchsort_session['searchtext'])) {
                                    /*$data['searchtext'] = $searchsort_session['searchtext'];
                                    $searchtext =  $data['searchtext'];
                                    */
                                    $searchtext =  mysql_real_escape_string($searchsort_session['searchtext']);
                            $data['searchtext'] = $searchsort_session['searchtext'];

                            }
                    }
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
            $config['base_url'] = site_url($this->user_type.'/'."mls/property_image_list/".$property_id."/");
    $config['is_ajax_paging'] = TRUE; // default FALSE
    $config['paging_function'] = 'ajax_paging'; // Your jQuery paging
            if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
            } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
            }

            if(!empty($searchtext))
            {
                    $match=array('listing_number'=>$searchtext,'image_name'=>$searchtext);
                    $where=array('property_id'=>$property_id);
                    $data['datalist'] = $this->obj->select_records4('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby,$where);
                    $config['total_rows'] = count($this->obj->select_records4('',$match,'','like',''));
            }
            else
            {
                    $match=array('property_id'=>$property_id);
                    $data['datalist'] = $this->obj->select_records4('',$match,'','=','',$config['per_page'],$uri_segment,$sortfield,$sortby);	
                    $config['total_rows']= count($this->obj->select_records4('',$match,'','='));
            }
            //pr($data['datalist']);exit;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['msg'] = $this->message_session['msg'];
            $sortsearchpage_data = array(
                    'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
                    'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
                    'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
                    'perpage' => !empty($data['perpage'])?trim($data['perpage']):'10',
                    'uri_segment' => !empty($uri_segment)?$uri_segment:'0',
                    'total_rows' => !empty($config['total_rows'])?$config['total_rows']:'0');
            $this->session->set_userdata('property_image_sortsearchpage_data', $sortsearchpage_data);
            $data['uri_segment'] = $uri_segment;

            if($this->input->post('result_type') == 'ajax')
            {
                    $this->load->view($this->user_type.'/'.$this->viewName.'/image_ajax_list',$data);
            }
            else
            {
                    $data['main_content'] =  $this->user_type.'/'.$this->viewName."/image_list";
                    $this->load->view('superadmin/include/template',$data);
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
                    $XMLQuery .="<UserId>valuedprop</UserId>";
                    $XMLQuery .="<Password>k5f21pL9</Password>";
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

                    //pr($amenity);exit;
                    foreach($amenity as $key=>$value)
                    {
                        $propertytype=$key;
                        $property_data=$amenity[$propertytype];

                        foreach($property_data as $row)
                        {
                            $data['property_type'] 		=!empty($type['name'])?$type['name']:'';
                            $data['code'] 				=!empty($row['Code'])?$row['Code']:'';
                            $data['description']  		=!empty($row['Description'])?$row['Description']:'';
                            $data['value_code']			=!empty($row['Values']['Code'])?$row['Values']['Code']:'';
                            $data['value_description']	=!empty($row['Values']['Description'])?$row['Values']['Description']:'';
                            $data['modified_date']		=date('Y-m-d h:i:s');
                            $data['status']	='1';

                            $fields=array('id');
                            $match=array('code'=>!empty($row['Code'])?$row['Code']:'','value_code'=>!empty($row['Values']['Code'])?$row['Values']['Code']:'');
                            $res=$this->obj->select_records1('',$match,'','=');
                            if(empty($res))
                            {
                                $data['created_date']		=date('Y-m-d h:i:s');
                                $id=$this->obj->insert_record1($data);
                            }
                            else
                            {
                                $id=$this->obj->update_record1($data);
                                //echo $this->db->last_query();
                            }
                        }

                    }         

            }
        }
		//redirect('superadmin/mls/add_record');
    }
	/*
    @Description: Function Add amenity data
    @Author: Niral Patel
    @Input: - 
    @Output: - insert amenity data
    @Date: 20-02-2015
    */
   
    public function retrieve_listing_data()
    {
		
        set_time_limit(0);
        //error_reporting(0);
        $field=array('id','name');
        $where=array('status'=>'1');
        $propty_type=$this->mls_model->select_records_tran($field,$where,'','=');

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
        $begin_date = $this->uri->segment(4);
        $curr_date  = $this->uri->segment(5);
        //echo $begin_date;
        //echo $curr_date;exit;
        if(!empty($propty_type))
        {
                foreach($propty_type as $type)	
                {
                        $client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
                        $XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
                        $XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
                        $XMLQuery .="<Message>";
                        $XMLQuery .="<Head>";
                        $XMLQuery .="<UserId>valuedprop</UserId>";
                        $XMLQuery .="<Password>k5f21pL9</Password>";
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
                                //pr($property_data);
                                //echo "hiiii";echo count($property_data);
                                //exit;
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
                                        $data[$i]["LONG"]                       =!empty($row["LONG"])?$row["LONG"]:"";
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
                                        $data[$i]["ASC"]                        =!empty($row["ASC"])?$row["ASC"]:"";
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
                                        $data[$i]["LotSizeSource"]   			=!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
                                        $data[$i]["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
                                        $data[$i]["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";
                                        $data[$i]['modified_date']				= date('Y-m-d h:i:s');
                                        $data[$i]['status']						='1';
                                        //pr($data);
                                        $fields=array('ID','LN');
                                        $match=array('LN'=>!empty($row['LN'])?$row['LN']:'');
                                        $res=$this->mls_model->select_records3('',$match,'','=');
                                        //echo $this->db->last_query();
                                        //pr($data);
                                        //exit;
                                        if(empty($res))
                                        {
                                                $data[$i]['created_date']		= date('Y-m-d h:i:s');
                                                if(count($data) >= 100)
                                                {
                                                        $this->mls_model->insert_record3($data);
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
                                                        $this->mls_model->update_record3($cdata);
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
                                        $this->mls_model->insert_record3($data);
                                        unset($data);	
                                }
                                if(!empty($cdata) && count($cdata) > 0)
                                {
                                        $this->mls_model->update_record3($cdata);
                                        //pr($cdata); exit;
                                        unset($cdata);	
                                }
                        }
                }
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
   
    public function retrieve_area_community_data()
    {
		set_time_limit(0);
		 
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
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
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
				$nodelist = $client->RetrieveAreaCommunityData($params); 
				$accessnodelist = $nodelist->RetrieveAreaCommunityDataResult;
				$xml_result = new SimpleXMLElement($accessnodelist);
				$json = json_encode($xml_result);
				$mls_data = json_decode($json,TRUE);
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						$data['property_type'] 		=!empty($type['name'])?$type['name']:'';
						$data['area'] 				=!empty($row['Area'])?$row['Area']:'';
						$data['community']  		=!empty($row['Community'])?$row['Community']:'';
						$data['modified_date']		=date('Y-m-d h:i:s');
						//pr($data);
						$fields=array('id');
						$match=array('area'=>!empty($row['Area'])?$row['Area']:'','community'=>!empty($row['Community'])?$row['Community']:'');
						$res=$this->obj->select_records2('',$match,'','=');
						//echo $this->db->last_query();exit;
						if(empty($res))
						{
							$data['created_date']		=date('Y-m-d h:i:s');
							$id=$this->obj->insert_record2($data);
						}
						else
						{
							$id=$this->obj->update_record2($data);
							//echo $this->db->last_query();
						}
					}
					
				}
				
			}
		}
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
		$num    = $this->uri->segment(4);
		$offset = $this->uri->segment(5);	
		$hour = $this->uri->segment(6);
		$day  = $this->uri->segment(7);
	
        set_time_limit(0);
		$fields = array('ID','LN');
		//$match  = array('LN'=>!empty($row['LN'])?$row['LN']:'');

		$res    = $this->obj->select_records3($fields,'','','=','',$num,$offset);
		//echo $this->db->last_query();exit;
		if(!empty($res))
		{
			foreach($res as $row)	
			{
				$listingID=$row['LN'];
				//$listingID='27834';
				
				try {
					/*---------------------- Get Imgae data from mls image services-------------------*/
					$client=new SoapClient('http://images.idx.nwmls.com/imageservice/imagequery.asmx?WSDL');
					$XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
					$XMLQuery .="<ImageQuery xmlns='NWMLS:EverNet:ImageQuery:1.0'>";
					$XMLQuery .="<Auth>";
					$XMLQuery .="<UserId>valuedprop</UserId>";
					$XMLQuery .="<Password>k5f21pL9</Password>";
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
					$XMLQuery .="<UserId>valuedprop</UserId>";
					$XMLQuery .="<Password>k5f21pL9</Password>";
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
						$upload_path=$this->config->item('upload_image_file_path');
						$path = $upload_path."property_image";
						
						if (!is_dir($path.'/'.$listingID)) 
						{
							$this->create_dir($listingID);
						}
						if(!empty($mls_data['Images']['Image'][0]))
						{
							for($i=0;$i<count($mls_data['Images']['Image']);$i++)
							{
								$row1  = $mls_data['Images']['Image'];
								$row2  = $mls_data1['image'];
								$data['property_id'] 		= !empty($row['ID'])?$row['ID']:'';
								$data['listing_number'] 	= !empty($row['LN'])?$row['LN']:'';
								$data['image_id'] 			= !empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'';
								$data['upload_date'] 		= !empty($row1[$i]['UploadDt'])?$row1[$i]['UploadDt']:'';
								$data['image_height'] 		= !empty($row2[$i]['PictureHeight'])?$row2[$i]['PictureHeight']:'';
								$data['image_width'] 		= !empty($row2[$i]['PictureWidth'])?$row2[$i]['PictureWidth']:'';
								$data['image_desc'] 		= !empty($row2[$i]['PictureDescription'])?$row2[$i]['PictureDescription']:'';
								$data['last_modified_date'] = !empty($row2[$i]['LastModifiedDateTime'])?$row2[$i]['LastModifiedDateTime']:'';
								$data['modified_date']		= date('Y-m-d h:i:s');
								
								$fields=array('id');
								$match=array('Image_id'=>!empty($row1[$i]['ImageId'])?$row1[$i]['ImageId']:'');
								$result=$this->obj->select_records4('',$match,'','=');
								//Upload image
								$img=$row1[$i]['BLOB'];
								$upload_path=$this->config->item('upload_image_file_path');
								$path = $upload_path."property_image";
								$img_order=$row1[$i]['ImageOrder'];
								if($img_order == 0)
								{$name = $listingID.".jpg";}
								else{$name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_0" .$img_order. ".jpg";}
								file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));
								
								$data['image_name'] 	= !empty($name)?$name:'';
								if(empty($result))
								{
									
									
									$data['created_date']	= date('Y-m-d h:i:s');
									
									$id=$this->obj->insert_record4($data);
									//echo $this->db->last_query();
								}
								else
								{
									$id=$this->obj->update_record4($data);
									//echo $this->db->last_query();
								}
							}
						}
						else
						{
							$row1 = $mls_data['Images']['Image'];
							$row2 = $mls_data1['image'];
							$data['property_id'] 		= !empty($row['ID'])?$row['ID']:'';
							$data['listing_number'] 	= !empty($row['LN'])?$row['LN']:'';
							$data['image_id'] 			= !empty($row1['ImageId'])?$row1['ImageId']:'';
							$data['upload_date'] 		= !empty($row1['UploadDt'])?$row1['UploadDt']:'';
							$data['image_height'] 		= !empty($row2['PictureHeight'])?$row2['PictureHeight']:'';
							$data['image_width'] 		= !empty($row2['PictureWidth'])?$row2['PictureWidth']:'';
							$data['image_desc'] 		= !empty($row2['PictureDescription'])?$row2['PictureDescription']:'';
							$data['last_modified_date'] = !empty($row2['LastModifiedDateTime'])?$row2['LastModifiedDateTime']:'';
							$data['modified_date']		= date('Y-m-d h:i:s');
							
							$fields=array('id');
							$match=array('Image_id'=>!empty($mls_data['Images']['Image']['ImageId'])?$mls_data['Images']['Image']['ImageId']:'');
							$result=$this->obj->select_records4('',$match,'','=');
							//Upload image
							$img=$row1['BLOB'];
							$upload_path=$this->config->item('upload_image_file_path');
							$path = $upload_path."property_image";
							$img_order=$row1['ImageOrder'];
							if($img_order == 0)
							{$name = $listingID.".jpg";}
							else{$name = (strlen($img_order) == 1)?$listingID . "_0" .$img_order. ".jpg":$listingID . "_0" .$img_order. ".jpg";}
							//$name = $listingID . "_" . $mls_data['Images']['Image']['ImageOrder'] . ".jpg";
							file_put_contents($path."/".$listingID.'/'.$name, base64_decode($img));	
							
							$data['image_name'] 		= !empty($name)?$name:'';
							if(empty($result))
							{
								
								$data['created_date']	=date('Y-m-d h:i:s');
								$id=$this->obj->insert_record4($data);
								
							}
							else
							{
								//pr($data);exit;
								$id=$this->obj->update_record4($data);
							}
							
						}
					}
				} catch(DynamoDbException $e) {
					//echo 'The item could not be retrieved.';
				}
					
			}
            //Remove cron
			$datetime = date('Y-m-d h:i:s');
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
            echo exec('crontab ../../../../tmp/crontab.txt');       
		}
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
		$begin_date = $this->uri->segment(4);
		$curr_date  = $this->uri->segment(5);
		if(!empty($propty_type))
		{
			foreach($propty_type as $type)	
			{
				$client=new SoapClient('http://evernet.nwmls.com/evernetqueryservice/evernetquery.asmx?WSDL');
				$XMLQuery ="<?xml version='1.0' encoding='utf-8' standalone='no' ?>";
				$XMLQuery .="<EverNetQuerySpecification xmlns='urn:www.nwmls.com/Schemas/General/EverNetQueryXML.xsd'>";
				$XMLQuery .="<Message>";
				$XMLQuery .="<Head>";
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
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
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						$data['property_type'] 		= !empty($type['name'])?$type['name']:'';
						$data['ml_number'] 			= !empty($row['ML_Number'])?$row['ML_Number']:'';
						$data['list_price'] 		= !empty($row['LH']['ListPrice'])?$row['LH']['ListPrice']:'';
						$data['change_date']  		= !empty($row['LH']['ChangeDate'])?$row['LH']['ChangeDate']:'';
						$data['modified_date']		= date('Y-m-d h:i:s');
						//pr($data);exit;
						$fields=array('id');
						$match=array('ml_number'=>!empty($row['ML_Number'])?$row['ML_Number']:'');
						$res=$this->obj->select_records5('',$match,'','=');
						//echo $this->db->last_query();exit;
						
						if(empty($res))
						{
							$data['created_date']	= date('Y-m-d h:i:s');
							$id=$this->obj->insert_record5($data);
						}
						else
						{
							$id=$this->obj->update_record5($data);
							//echo $this->db->last_query();
						}
					}
					
				}
				
			}
			//redirect('superadmin/mls/add_record');
		}echo 1;
		redirect('superadmin/mls/add_record');
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
		set_time_limit(0);
		 
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
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
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
				$nodelist = $client->RetrieveMemberData($params); 
				$accessnodelist = $nodelist->RetrieveMemberDataResult;
				$xml_result = new SimpleXMLElement($accessnodelist);
				$json = json_encode($xml_result);
				$mls_data = json_decode($json,TRUE);
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						$data['property_type'] 			= !empty($type['name'])?$type['name']:'';
						$data['member_mls_id'] 			= !empty($row['MemberMLSID'])?$row['MemberMLSID']:'';
						$data['first_name']  			= !empty($row['FirstName'])?$row['FirstName']:'';
						$data['last_name'] 				= !empty($row['LastName'])?$row['LastName']:'';
						$data['office_mls_id']  		= !empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'';
						$data['office_name'] 			= !empty($row['OfficeName'])?$row['OfficeName']:'';
						$data['office_area_code']  		= !empty($row['OfficeAreaCode'])?$row['OfficeAreaCode']:'';
						$data['office_phone'] 			= !empty($row['OfficePhone'])?$row['OfficePhone']:'';
						$data['office_phone_extension'] = !empty($row['OfficePhoneExtension'])?$row['OfficePhoneExtension']:'';
						$data['modified_date']			= date('Y-m-d h:i:s');
						
						$fields=array('id');
						$match=array('member_mls_id'=>!empty($row['MemberMLSID'])?$row['MemberMLSID']:'');
						$res=$this->obj->select_records6('',$match,'','=');
						//echo $this->db->last_query();exit;
						if(empty($res))
						{
							$data['created_date']		= date('Y-m-d h:i:s');
							$id=$this->obj->insert_record6($data);
						}
						else
						{
							$id=$this->obj->update_record6($data);
							//echo $this->db->last_query();
						}
					}
					
				}
				
			}
		}
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
		set_time_limit(0);
		 
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
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
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
				$nodelist = $client->RetrieveOfficeData($params); 
				$accessnodelist = $nodelist->RetrieveOfficeDataResult;
				$xml_result = new SimpleXMLElement($accessnodelist);
				$json = json_encode($xml_result);
				$mls_data = json_decode($json,TRUE);
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						$data['property_type'] 			= !empty($type['name'])?$type['name']:'';
						$data['office_mls_id'] 			= !empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'';
						$data['office_name']  			= !empty($row['OfficeName'])?$row['OfficeName']:'';
						$data['street_care_of'] 		= !empty($row['StreetCareOf'])?$row['StreetCareOf']:'';
						$data['street_address']  		= !empty($row['StreetAddress'])?$row['StreetAddress']:'';
						$data['street_city'] 			= !empty($row['StreetCity'])?$row['StreetCity']:'';
						$data['street_state']  			= !empty($row['StreetState'])?$row['StreetState']:'';
						$data['street_zip_code'] 		= !empty($row['StreetZipCode'])?$row['StreetZipCode']:'';
						$data['street_zip_plus4'] 		= !empty($row['StreetZipPlus4'])?$row['StreetZipPlus4']:'';
						$data['street_county'] 			= !empty($row['StreetCounty'])?$row['StreetCounty']:'';
						$data['office_area_code'] 		= !empty($row['OfficeAreaCode'])?$row['OfficeAreaCode']:'';
						$data['office_phone'] 			= !empty($row['OfficePhone'])?$row['OfficePhone']:'';
						$data['fax_area_code'] 			= !empty($row['FaxAreaCode'])?$row['FaxAreaCode']:'';
						$data['fax_phone'] 				= !empty($row['FaxPhone'])?$row['FaxPhone']:'';
						$data['email_address'] 			= !empty($row['EMailAddress'])?$row['EMailAddress']:'';
						$data['webPage_address'] 		= !empty($row['WebPageAddress'])?$row['WebPageAddress']:'';
						$data['office_type'] 			= !empty($row['OfficeType'])?$row['OfficeType']:'';
						$data['modified_date']			= date('Y-m-d h:i:s');
						//pr($data);exit;
						$fields=array('id');
						$match=array('office_mls_id'=>!empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'');
						$res=$this->obj->select_records7('',$match,'','=');
						//echo $this->db->last_query();exit;
						if(empty($res))
						{
							$data['created_date']		= date('Y-m-d h:i:s');
							$id=$this->obj->insert_record7($data);
						}
						else
						{
							$id=$this->obj->update_record7($data);
							//echo $this->db->last_query();
						}
					}
					
				}
				
			}
		}
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
		set_time_limit(0);
		 
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
				$XMLQuery .="<UserId>valuedprop</UserId>";
				$XMLQuery .="<Password>k5f21pL9</Password>";
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
				$nodelist = $client->RetrieveSchoolData($params); 
				$accessnodelist = $nodelist->RetrieveSchoolDataResult;
				$xml_result = new SimpleXMLElement($accessnodelist);
				$json = json_encode($xml_result);
				$mls_data = json_decode($json,TRUE);
				
				//pr($mls_data);exit;
				foreach($mls_data as $key=>$value)
				{
					$propertytype=$key;
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						$data['property_type'] 					= !empty($type['name'])?$type['name']:'';
						$data['school_district_code'] 			= !empty($row['SchoolDistrictCode'])?$row['SchoolDistrictCode']:'';
						$data['school_district_description']  	= !empty($row['SchoolDistrictDescription'])?$row['SchoolDistrictDescription']:'';
						$data['modified_date']					= date('Y-m-d h:i:s');
						//pr($data);exit;
						$fields=array('id');
						$match=array('school_district_code'=>!empty($row['SchoolDistrictCode'])?$row['SchoolDistrictCode']:'');
						$res=$this->obj->select_records8('',$match,'','=');
						//echo $this->db->last_query();exit;
						if(empty($res))
						{
							$data['created_date']		= date('Y-m-d h:i:s');
							$id=$this->obj->insert_record8($data);
						}
						else
						{
							$id=$this->obj->update_record8($data);
						}
					}
					
				}
				
			}
		}
		//redirect('superadmin/mls/add_record');
    }
    function create_image_links()
    {
        //echo 1;exit;
		$total = 1000;
		$datetime = date('Y-m-d h:i:s');
		//echo '<br>'; //exit;
        $hr = date("h",strtotime($datetime)); 
		//$hr = 16;
		//echo $hr; 
		//exit;
		$day = date("d",strtotime($datetime));
		$start = 1;
		$next = (($start * $total) - $total);
        for($i=$start;$i<=$start+29;$i++)
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
            //echo $day = date("d",strtotime($datetime));
            if($i == 1)
            {
				$url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$total.'/0/'.$hour.'/'.$day;
                //$next+=$total;
            }
            else
            {
                //echo $next;exit;
				$url=base_url().'superadmin/mls_cron/retrieve_image_data/'.$total.'/'.$next.'/'.$hour.'/'.$day;
            }   
            //echo $url;
			$month = date("m",strtotime($datetime)); 
            $output = shell_exec('crontab -l');
            //$output = shell_exec('crontab -l');
            
            //echo $output.$minute.' '.$hour.' '.$day.' '.$month.' *  curl '.$url;
            //echo '<br>';
            
            file_put_contents('../../../../tmp/cron.txt', $output.$minute.' '.$hour.' '.$day.' '.$month.' * curl '.$url.''.PHP_EOL);
            echo exec('crontab ../../../../tmp/cron.txt');
            $next+=$total;
            $hr++;
			//echo 'done';
			//exit;
        }
        echo 'done';exit;
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
}