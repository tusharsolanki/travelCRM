<?php 
/*
    @Description: mls controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_add_control extends CI_Controller
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
        $this->mls_master_db         = $this->config->item('mls_master_db');
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

        $field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records1($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_amenity_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','',$wherestring);
        
        //pr($mls_data);exit;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                $data['property_type'] 		=!empty($row['property_type'])?$row['property_type']:'';
                $data['code'] 				=!empty($row['code'])?$row['code']:'';
                $data['description']  		=!empty($row['description'])?$row['description']:'';
                $data['value_code']			=!empty($row['value_code'])?$row['value_code']:'';
                $data['value_description']	=!empty($row['value_description'])?$row['value_description']:'';
                $data['modified_date']		=date('Y-m-d h:i:s');
                //$data['status']	            ='1';
                
                $fields=array('id');
                $match=array('code'=>!empty($row['code'])?$row['code']:'','value_code'=>!empty($row['value_code'])?$row['value_code']:'');
                $res=$this->obj->select_records1('',$match,'','=','','','','','','',$this->mls_master_db);
                //pr($res);
                if(empty($res))
                {
                    $data['created_date']		=date('Y-m-d h:i:s');
                    $id=$this->obj->insert_record1($data,$this->mls_master_db);
                }
                else
                {
                    $id=$this->obj->update_record1($data,$this->mls_master_db);
                    //echo $this->db->last_query();
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
        $num    = $this->uri->segment(4);
        $offset = $this->uri->segment(5);
        //error_reporting(0);
        /*$field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records3($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '2015-05-28 12:59:59' AND '".$now."'";
        }*/

        $table =$this->mls_staging_db.".nwmls_mls_property_list_master";
        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','',$num,$offset,'id','asc','',$wherestring);
        //pr($mls_data);exit;
        //echo $this->db->last_query();exit;
       
        $i = 0;
        $j = 0;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
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
                $data[$i]["LONGI"]                      =!empty($row["LONGI"])?$row["LONGI"]:"";
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
                $data[$i]["ASCC"]                       =!empty($row["ASCC"])?$row["ASCC"]:"";
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
                $data[$i]["LotSizeSource"]   			=!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
                $data[$i]["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
                $data[$i]["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";
                $data[$i]['modified_date']				= date('Y-m-d h:i:s');
                $data[$i]['status']						='1';
                $data[$i]['mls_type_id']                ='1';
                $data[$i]['LngLatCoords']               =!empty($row["LngLatCoords"])?$row["LngLatCoords"]:"";         
                //pr($data);
                $fields=array('ID','LN');
                $match=array('LN'=>!empty($row['LN'])?$row['LN']:''); 
                //$res=$this->mls_model->select_records3('',$match,'','=','','','','','','',$this->mls_master_db);
                $res=$this->mls_model->select_records3('',$match,'','=','','','','','','',$this->mls_master_db,'','','nwmls_mls_property_list_master');
                //echo $this->db->last_query();
                //pr($data);
                //exit;
                if(empty($res))
                {
                        $data[$i]['created_date']		= date('Y-m-d h:i:s');
                        $i++;
                }
                else
                {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
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
                                $this->mls_model->insert_record3($idata,$this->mls_master_db);
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
                                $this->mls_model->update_record3($udata,$this->mls_master_db);
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
                $this->mls_model->insert_record3($idata,$this->mls_master_db);
                unset($idata);   
            }
            if(!empty($udata) && count($udata) > 0)
            {
               $this->mls_model->update_record3($udata,$this->mls_master_db);
               unset($udata);  
            }            
        }
               
        // Insert to mls database
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
		$field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records2($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_area_community_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','',$wherestring);
        
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
				$data['area'] 				=!empty($row['area'])?$row['area']:'';
				$data['community']  		=!empty($row['community'])?$row['community']:'';
				$data['modified_date']		=date('Y-m-d h:i:s');
				//pr($data);
				$fields=array('id');
				$match=array('area'=>!empty($row['area'])?$row['area']:'','community'=>!empty($row['community'])?$row['community']:'');
				$res=$this->obj->select_records2('',$match,'','=','','','','','','',$this->mls_master_db);
				//echo $this->db->last_query();exit;
				if(empty($res))
				{
					$data['created_date']		=date('Y-m-d h:i:s');
					$id=$this->obj->insert_record2($data,$this->mls_master_db);
				}
				else
				{
					$id=$this->obj->update_record2($data,$this->mls_master_db);
					//echo $this->db->last_query();
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
		//get last created date
        $field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records4($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;

        //Get data of master database
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_property_image";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','',$wherestring);
        //echo $this->db->last_query();exit;
       
        
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                //$data['property_type']            = !empty($type['name'])?$type['name']:'';
                $data['listing_number']         = !empty($row['listing_number'])?$row['listing_number']:'';
                $data['image_name']             = !empty($row['image_name'])?$row['image_name']:'';
                $data['image_height']           = !empty($row['image_height'])?$row['image_height']:'';
                $data['image_width']            = !empty($row['image_width'])?$row['image_width']:'';
                $data['image_desc']             = !empty($row['image_desc'])?$row['image_desc']:'';
                $data['last_modified_date']     = !empty($row['last_modified_date'])?$row['last_modified_date']:'';
                $data['Image_id']               = !empty($row['Image_id'])?$row['Image_id']:'';
                $data['image_url']                  = !empty($row['image_url'])?$row['image_url']:'';
                $data['image_medium_url']           = !empty($row['image_medium_url'])?$row['image_medium_url']:'';
                $data['image_small_url']            = !empty($row['image_small_url'])?$row['image_small_url']:'';
                $data['upload_date']            = !empty($row['upload_date'])?$row['upload_date']:'';
                $data['modified_date']          = date('Y-m-d h:i:s');
                
                $fields=array('id');
                $match=array('Image_id'=>!empty($row['Image_id'])?$row['Image_id']:'');
                $res=$this->mls_model->select_records4('',$match,'','=','','','','','','',$this->mls_master_db);
                if(empty($res))
                {
                    $data['created_date']       =date('Y-m-d h:i:s');
                    $id=$this->obj->insert_record4($data,$this->mls_master_db);
                }
                else
                {
                    $id=$this->obj->update_record4($data,$this->mls_master_db);
                    //echo $this->db->last_query();
                }
            }
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
        //get last created date
        /*$field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records5($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;

        //Get data of master database
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }*/

        $table =$this->mls_staging_db.".nwmls_mls_property_history_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','');
        //echo $this->db->last_query();
        //pr($mls_data);exit;
        $i = 0;
        $j = 0;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                $data[$i]['property_type']      = !empty($row['property_type'])?$row['property_type']:'';
                $data[$i]['ml_number']          = !empty($row['ml_number'])?$row['ml_number']:'';
                $data[$i]['list_price']         = !empty($row['list_price'])?$row['list_price']:'';
                $data[$i]['change_date']        = !empty($row['change_date'])?$row['change_date']:'';
                $data[$i]['modified_date']      = date('Y-m-d h:i:s');
                //pr($data);exit;
                $fields=array('id');
                $match=array('ml_number'=>!empty($row['ml_number'])?$row['ml_number']:'');
                $res=$this->mls_model->select_records5('',$match,'','=','','','','','','',$this->mls_master_db);
                if(empty($res))
                {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        $i++;
                        //$id=$this->mls_model->insert_record3($data);
                }
                else
                {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        $j++;
                        //$id=$this->mls_model->update_record3($data);
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
                            
                            $this->mls_model->insert_record5($idata,$this->mls_master_db);
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
                            $this->mls_model->update_record5($udata,$this->mls_master_db);
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
            
            $this->mls_model->insert_record5($idata,$this->mls_master_db);
            unset($idata);   
        }
        if(!empty($udata) && count($udata) > 0)
        {
           
           $this->mls_model->update_record5($udata,$this->mls_master_db);
           unset($udata);  
        }   
        echo 'Done';
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
        //get last created date
        $field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records6($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;

        //Get data of master database
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_member_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','');
        
        //echo $this->db->last_query();exit;
       
        $i = 0;
        $j = 0;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                $data[$i]['member_mls_id']          = !empty($row['member_mls_id'])?$row['member_mls_id']:'';
                $data[$i]['first_name']             = !empty($row['first_name'])?$row['first_name']:'';
                $data[$i]['last_name']              = !empty($row['last_name'])?$row['last_name']:'';
                $data[$i]['member_office_mls_id']   = !empty($row['member_office_mls_id'])?$row['member_office_mls_id']:'';
                $data[$i]['member_office_name']     = !empty($row['member_office_name'])?$row['member_office_name']:'';
                $data[$i]['member_office_area_code']= !empty($row['member_office_area_code'])?$row['member_office_area_code']:'';
                $data[$i]['member_office_phone']    = !empty($row['member_office_phone'])?$row['member_office_phone']:'';
                $data[$i]['office_phone_extension'] = !empty($row['office_phone_extension'])?$row['office_phone_extension']:'';
                $data[$i]['modified_date']          = date('Y-m-d h:i:s');
                
                $fields=array('id');
                $match=array('member_mls_id'=>!empty($row['member_mls_id'])?$row['member_mls_id']:'');
                $res=$this->mls_model->select_records6('',$match,'','=','','','','','','',$this->mls_master_db);
                if(empty($res))
                {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        $i++;
                        //$id=$this->mls_model->insert_record3($data);
                }
                else
                {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        $j++;
                        //$id=$this->mls_model->update_record3($data);
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
                            
                            $this->mls_model->insert_record6($idata,$this->mls_master_db);
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
                            $this->mls_model->update_record6($udata,$this->mls_master_db);
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
            
            $this->mls_model->insert_record6($idata,$this->mls_master_db);
            unset($idata);   
        }
        if(!empty($udata) && count($udata) > 0)
        {
           
           $this->mls_model->update_record6($udata,$this->mls_master_db);
           unset($udata);  
        }   
        echo 'Done';
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
        //get last created date
        $field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records7($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;

        //Get data of master database
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_office_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','');
        //pr($mls_data);exit;
        //echo $this->db->last_query();exit;
       
        $i = 0;
        $j = 0;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                //$data[$i]['property_type']            = !empty($type['name'])?$type['name']:'';
                $data[$i]['office_mls_id']          = !empty($row['office_mls_id'])?$row['office_mls_id']:'';
                $data[$i]['office_name']            = !empty($row['office_name'])?$row['office_name']:'';
                $data[$i]['street_care_of']         = !empty($row['street_care_of'])?$row['street_care_of']:'';
                $data[$i]['street_address']         = !empty($row['street_address'])?$row['street_address']:'';
                $data[$i]['street_city']            = !empty($row['street_city'])?$row['street_city']:'';
                $data[$i]['street_state']           = !empty($row['street_state'])?$row['street_state']:'';
                $data[$i]['street_zip_code']        = !empty($row['street_zip_code'])?$row['street_zip_code']:'';
                $data[$i]['street_zip_plus4']       = !empty($row['street_zip_plus4'])?$row['street_zip_plus4']:'';
                $data[$i]['street_county']          = !empty($row['street_county'])?$row['street_county']:'';
                $data[$i]['office_area_code']       = !empty($row['office_area_code'])?$row['office_area_code']:'';
                $data[$i]['office_phone']           = !empty($row['office_phone'])?$row['office_phone']:'';
                $data[$i]['fax_area_code']          = !empty($row['fax_area_code'])?$row['fax_area_code']:'';
                $data[$i]['fax_phone']              = !empty($row['fax_phone'])?$row['fax_phone']:'';
                $data[$i]['email_address']          = !empty($row['email_address'])?$row['email_address']:'';
                $data[$i]['webPage_address']        = !empty($row['webPage_address'])?$row['webPage_address']:'';
                $data[$i]['office_type']            = !empty($row['office_type'])?$row['office_type']:'';
                $data[$i]['modified_date']          = date('Y-m-d h:i:s');
                //pr($data);exit;
                $fields=array('id');
                $match=array('office_mls_id'=>!empty($row['office_mls_id'])?$row['office_mls_id']:'');
                $res=$this->mls_model->select_records7('',$match,'','=','','','','','','',$this->mls_master_db);
                if(empty($res))
                {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        $i++;
                        //$id=$this->mls_model->insert_record3($data);
                }
                else
                {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        $j++;
                        //$id=$this->mls_model->update_record3($data);
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
                            
                            $this->mls_model->insert_record7($idata,$this->mls_master_db);
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
                            $this->mls_model->update_record7($udata,$this->mls_master_db);
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
            
            $this->mls_model->insert_record7($idata,$this->mls_master_db);
            unset($idata);   
        }
        if(!empty($udata) && count($udata) > 0)
        {
           
           $this->mls_model->update_record7($udata,$this->mls_master_db);
           unset($udata);  
        }   
        echo 'Done';
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
        //get last created date
        $field=array('id','created_date');
        $where=array('status'=>'1');
        $last_created=$this->mls_model->select_records8($field,'','','','',1,1,'created_date','desc','',$this->mls_master_db,'');
        //echo $this->db->last_query();exit;
        //pr($last_created);exit;

        //Get data of master database
        $now=date('Y-m-d h:i:s');
        $wherestring='';
        if(!empty($last_created))
        {
            $wherestring = "DATE_FORMAT(modified_date,'%Y-%m-%d') BETWEEN '".$last_created[0]['created_date']."' AND '".$now."'";
        }

        $table =$this->mls_staging_db.".nwmls_mls_school_data";

        $mls_data = $this->obj->getmultiple_tables_records($table,'','','','','','','','','id','asc','');
        //pr($mls_data);exit;
        //echo $this->db->last_query();exit;
       
        $i = 0;
        $j = 0;
        if(!empty($mls_data))
        {
            foreach($mls_data as $row)
            {
                //$data[$i]['property_type']                    = !empty($type['name'])?$type['name']:'';
                $data[$i]['school_district_code']           = !empty($row['school_district_code'])?$row['school_district_code']:'';
                $data[$i]['school_district_description']    = !empty($row['school_district_description'])?$row['school_district_description']:'';
                $data[$i]['modified_date']                  = date('Y-m-d h:i:s');
                //pr($data[$i]);exit;
                $fields=array('id');
                $match=array('school_district_code'=>!empty($row['school_district_code'])?$row['school_district_code']:'');
                $res=$this->mls_model->select_records8('',$match,'','=','','','','','','',$this->mls_master_db);
                if(empty($res))
                {
                        $data[$i]['created_date']       = date('Y-m-d h:i:s');
                        $i++;
                        //$id=$this->mls_model->insert_record3($data);
                }
                else
                {
                        $cdata[$j] = $data[$i];
                        unset($data[$i]);
                        $j++;
                        //$id=$this->mls_model->update_record3($data);
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
                            
                            $this->mls_model->insert_record8($idata,$this->mls_master_db);
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
                            $this->mls_model->update_record8($udata,$this->mls_master_db);
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
            
            $this->mls_model->insert_record8($idata,$this->mls_master_db);
            unset($idata);   
        }
        if(!empty($udata) && count($udata) > 0)
        {
           
           $this->mls_model->update_record8($udata,$this->mls_master_db);
           unset($udata);  
        }   
        echo 'Done';
    }    
}