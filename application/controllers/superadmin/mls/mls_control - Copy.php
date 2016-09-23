<?php 
/*
    @Description: mls controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mls_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
       	$this->message_session = $this->session->userdata('message_session');
        check_superadmin_login();
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
    {	
		$searchopt='';$searchtext='';$date1='';$date2='';$searchoption='';$perpage='';
		$searchtext = $this->input->post('searchtext');
		$tab = $this->input->post('tab');
		
		$sortfield = $this->input->post('sortfield');
		$sortby = $this->input->post('sortby');
		$searchopt = $this->input->post('searchopt');
		$perpage = trim($this->input->post('perpage'));
		$allflag = $this->input->post('allflag');
		//$this->session->unset_userdata('mls_amenity_sortsearchpage_data');
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$this->session->unset_userdata('mls_amenity_sortsearchpage_data');
		}
        $data['sortfield']		= 'id';
		$data['sortby']			= 'desc';
		$searchsort_session = $this->session->userdata('mls_amenity_sortsearchpage_data');
		
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
					$tab = $searchsort_session['tab'];
				}
			} else {
				$sortfield = 'id';
				$sortby = 'desc';
			}
		}
		if(!empty($searchtext))
		{
			//$searchtext = $this->input->post('searchtext');
			$data['searchtext'] = $searchtext;
		} 
		else 
		{
			if(empty($allflag))
			{
				if(!empty($searchsort_session['searchtext'])) {
					$data['searchtext'] = $searchsort_session['searchtext'];
					$searchtext =  $data['searchtext'];
				}
			}
		}
		if(!empty($searchopt))
		{
			//$searchopt = $this->input->post('searchopt');
			$data['searchopt'] = $searchopt;
		}
		if(!empty($perpage) && $perpage != 'null')
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
		
		//echo $sortfield;
		//echo $sortby;exit;
		$config['base_url'] = site_url($this->user_type.'/'."mls/");
		$config['is_ajax_paging'] = TRUE; // default FALSE
		$config['paging_function'] = 'ajax_paging'; // Your jQuery paging
//$config['uri_segment'] = 3;
//$uri_segment = $this->uri->segment(3);
		if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
			$config['uri_segment'] = 0;
			$uri_segment = 0;
		} else {
			$config['uri_segment'] = 3;
			$uri_segment = $this->uri->segment(3);
		}
		
		if(empty($tab) || $tab == 1)
		{
			// MLS Amenity Tab
			
			if(!empty($searchtext))
			{
				$match=array('property_type'=>$searchtext,'code'=>$searchtext,'description'=>$searchtext,'value_code'=>$searchtext,'value_description'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records1('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records1('',$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records1('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				//echo $this->db->last_query();
				//pr($data['datalist']);
				$config['total_rows']= count($this->obj->select_records1());
			}
		}
		elseif(!empty($tab) && $tab == 2)
		{
			// MLS Area Community Tab
			
			if(!empty($searchtext))
			{
				$match=array('area'=>$searchtext,'community'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records2('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records2('',$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records2('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records2());
			}
		}
		elseif(!empty($tab) && $tab == 3)
		{
			// MLS Office Tab
			
			if(!empty($searchtext))
			{
				$match=array('office_mls_id'=>$searchtext,'office_name'=>$searchtext,'street_address'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records7('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records7('',$match,'','like'));
			}
			else
			{
				$data['datalist'] = $data['datalist'] = $this->obj->select_records7('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records7());
			}
		}
		elseif(!empty($tab) && $tab == 4)
		{
			// MLS Property History Tab
			
			if(!empty($searchtext))
			{
				$match=array('ml_number'=>$searchtext,'list_price'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records5('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records5('',$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records5('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records5());
			}
		}
		elseif(!empty($tab) && $tab == 5)
		{
			// MLS School Tab
			
			if(!empty($searchtext))
			{
				$match=array('school_district_code'=>$searchtext,'school_district_description'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records8('',$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records8('',$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records8('','','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records8());
			}
		}
		elseif(!empty($tab) && $tab == 6)
		{
			// MLS Member Tab
			
			$fields = array('id,property_type,member_mls_id,CONCAT_WS(" ",first_name,last_name) as name,office_name');
			if(!empty($searchtext))
			{
				$match=array('property_type'=>$searchtext,'member_mls_id'=>$searchtext,'CONCAT_WS(" ",first_name,last_name)'=>$searchtext,'office_name'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records6($fields,$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records6($fields,$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records6($fields,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records6());
			}
		}
		elseif(!empty($tab) && $tab == 7)
		{
			// MLS Property Listing Tab
			
			$fields = array('ID,LN,PTYP,LAG,ST,LP,SP,OLP,SSUF,DRS');
			if(!empty($searchtext))
			{
				$match=array('LN'=>$searchtext,'PTYP'=>$searchtext,'LAG'=>$searchtext,'ST'=>$searchtext,'LP'=>$searchtext,'SP'=>$searchtext,'OLP'=>$searchtext,'SSUF'=>$searchtext,'DRS'=>$searchtext);	
				$data['datalist'] = $this->obj->select_records3($fields,$match,'','like','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows'] = count($this->obj->select_records3($fields,$match,'','like'));
			}
			else
			{
				$data['datalist'] = $this->obj->select_records3($fields,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby);
				$config['total_rows']= count($this->obj->select_records3());
			}
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['msg'] = $this->message_session['msg'];
		if(!empty($tab))
			$data['tabid'] = $tab;
		else
			$data['tabid']= '1';
		$mls_amenity_sortsearchpage_data = array(
			'sortfield'  => !empty($data['sortfield'])?$data['sortfield']:'',
			'sortby' =>!empty($data['sortby'])?$data['sortby']:'',
			'searchtext' =>!empty($data['searchtext'])?$data['searchtext']:'',
			'perpage' => !empty($data['perpage'])?$data['perpage']:'10',
			'uri_segment' => $uri_segment,
			'total_rows' => $config['total_rows'],
			'tab' => !empty($tab)?$tab:'1'
			);
		$this->session->set_userdata('mls_amenity_sortsearchpage_data', $mls_amenity_sortsearchpage_data);
		$data['uri_segment'] = $uri_segment;
		
		if($this->input->post('result_type') == 'ajax' && !empty($tab))
		{
			if($tab == 1)
				$this->load->view($this->user_type.'/'.$this->viewName.'/ajax_list',$data);
			elseif($tab == 2)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_area_community_ajax_list',$data);
			elseif($tab == 3)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_office_ajax_list',$data);
			elseif($tab == 4)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_property_history_ajax_list',$data);
			elseif($tab == 5)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_school_ajax_list',$data);
			elseif($tab == 6)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_member_ajax_list',$data);
			elseif($tab == 7)
				$this->load->view($this->user_type.'/'.$this->viewName.'/mls_property_list_ajax_list',$data);
		}
		else
		{
			$data['main_content'] =  $this->user_type.'/'.$this->viewName."/list";
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
		redirect('superadmin/mls/add_record');
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
		$propty_type=$this->obj->select_records_tran($field,$where,'','=');
		
		// Get maximum update date
		
		$field=array('ID','max(UD) as UD');
		$res_data=$this->obj->select_records3($field,'','','=','','1','0');
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
		}
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
				$XMLQuery .="<PropertyType>RESI</PropertyType>";
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
					$property_data=$mls_data[$propertytype];
					
					foreach($property_data as $row)
					{
						pr($row);
						$data["LN"]                         =!empty($row["LN"])?$row["LN"]:"";
						$data["PTYP"]                       =!empty($row["PTYP"])?$row["PTYP"]:"";
						$data["LAG"]                        =!empty($row["LAG"])?$row["LAG"]:"";
						$data["ST"]                         =!empty($row["ST"])?$row["ST"]:"";
						$data["LP"]                         =!empty($row["LP"])?$row["LP"]:"";
						$data["SP"]                         =!empty($row["SP"])?$row["SP"]:"";
						$data["OLP"]                        =!empty($row["OLP"])?$row["OLP"]:"";
						$data["HSN"]                        =!empty($row["HSN"])?$row["HSN"]:"";
						$data["DRP"]                        =!empty($row["DRP"])?$row["DRP"]:"";
						$data["STR"]                        =!empty($row["STR"])?$row["STR"]:"";
						$data["SSUF"]                       =!empty($row["SSUF"])?$row["SSUF"]:"";
						$data["DRS"]                        =!empty($row["DRS"])?$row["DRS"]:"";
						$data["UNT"]                        =!empty($row["UNT"])?$row["UNT"]:"";
						$data["CIT"]                        =!empty($row["CIT"])?$row["CIT"]:"";
						$data["STA"]                        =!empty($row["STA"])?$row["STA"]:"";
						$data["ZIP"]                        =!empty($row["ZIP"])?$row["ZIP"]:"";
						$data["PL4"]                        =!empty($row["PL4"])?$row["PL4"]:"";
						$data["BR"]                         =!empty($row["BR"])?$row["BR"]:"";
						$data["BTH"]                        =!empty($row["BTH"])?$row["BTH"]:"";
						$data["ASF"]                        =!empty($row["ASF"])?$row["ASF"]:"";
						$data["LSF"]                        =!empty($row["LSF"])?$row["LSF"]:"";
						$data["UD"]                         =!empty($row["UD"])?$row["UD"]:"";
						$data["AR"]                         =!empty($row["AR"])?$row["AR"]:"";
						$data["DSRNUM"]                     =!empty($row["DSRNUM"])?$row["DSRNUM"]:"";
						$data["LDR"]                        =!empty($row["LDR"])?$row["LDR"]:"";
						$data["LD"]                         =!empty($row["LD"])?$row["LD"]:"";
						$data["CLO"]                        =!empty($row["CLO"])?$row["CLO"]:"";
						$data["YBT"]                        =!empty($row["YBT"])?$row["YBT"]:"";
						$data["LO"]                         =!empty($row["LO"])?$row["LO"]:"";
						$data["TAX"]                        =!empty($row["TAX"])?$row["TAX"]:"";
						$data["MAP"]                        =!empty($row["MAP"])?$row["MAP"]:"";
						$data["GRDX"]                       =!empty($row["GRDX"])?$row["GRDX"]:"";
						$data["GRDY"]                       =!empty($row["GRDY"])?$row["GRDY"]:"";
						$data["SAG"]                        =!empty($row["SAG"])?$row["SAG"]:"";
						$data["SO"]                         =!empty($row["SO"])?$row["SO"]:"";
						$data["NIA"]                        =!empty($row["NIA"])?$row["NIA"]:"";
						$data["MR"]                         =!empty($row["MR"])?$row["MR"]:"";
						$data["LONG"]                       =!empty($row["LONG"])?$row["LONG"]:"";
						$data["LAT"]                        =!empty($row["LAT"])?$row["LAT"]:"";
						$data["PDR"]                        =!empty($row["PDR"])?$row["PDR"]:"";
						$data["CLA"]                        =!empty($row["CLA"])?$row["CLA"]:"";
						$data["SHOADR"]                     =!empty($row["SHOADR"])?$row["SHOADR"]:"";
						$data["DD"]                         =!empty($row["DD"])?$row["DD"]:"";
						$data["AVDT"]                       =!empty($row["AVDT"])?$row["AVDT"]:"";
						$data["INDT"]                       =!empty($row["INDT"])?$row["INDT"]:"";
						$data["COU"]                        =!empty($row["COU"])?$row["COU"]:"";
						$data["CDOM"]                       =!empty($row["CDOM"])?$row["CDOM"]:"";
						$data["CTDT"]                       =!empty($row["CTDT"])?$row["CTDT"]:"";
						$data["SCA"]                        =!empty($row["SCA"])?$row["SCA"]:"";
						$data["SCO"]                        =!empty($row["SCO"])?$row["SCO"]:"";
						$data["VIRT"]                       =!empty($row["VIRT"])?$row["VIRT"]:"";
						$data["SDT"]                        =!empty($row["SDT"])?$row["SDT"]:"";
						$data["SD"]                         =!empty($row["SD"])?$row["SD"]:"";
						$data["FIN"]                        =!empty($row["FIN"])?$row["FIN"]:"";
						$data["MAPBOOK"]                    =!empty($row["MAPBOOK"])?$row["MAPBOOK"]:"";
						$data["DSR"]                        =!empty($row["DSR"])?$row["DSR"]:"";
						$data["QBT"]                        =!empty($row["QBT"])?$row["QBT"]:"";
						$data["HSNA"]                       =!empty($row["HSNA"])?$row["HSNA"]:"";
						$data["COLO"]                       =!empty($row["COLO"])?$row["COLO"]:"";
						$data["PIC"]                        =!empty($row["PIC"])?$row["PIC"]:"";
						$data["ADU"]                        =!empty($row["ADU"])?$row["ADU"]:"";
						$data["ARC"]                        =!empty($row["ARC"])?$row["ARC"]:"";
						$data["BDC"]                        =!empty($row["BDC"])?$row["BDC"]:"";
						$data["BDL"]                        =!empty($row["BDL"])?$row["BDL"]:"";
						$data["BDM"]                        =!empty($row["BDM"])?$row["BDM"]:"";
						$data["BDU"]                        =!empty($row["BDU"])?$row["BDU"]:"";
						$data["BLD"]                        =!empty($row["BLD"])?$row["BLD"]:"";
						$data["BLK"]                        =!empty($row["BLK"])?$row["BLK"]:"";
						$data["BRM"]                        =!empty($row["BRM"])?$row["BRM"]:"";
						$data["BUS"]                        =!empty($row["BUS"])?$row["BUS"]:"";
						$data["DNO"]                        =!empty($row["DNO"])?$row["DNO"]:"";
						$data["DRM"]                        =!empty($row["DRM"])?$row["DRM"]:"";
						$data["EFR"]                        =!empty($row["EFR"])?$row["EFR"]:"";
						$data["EL"]                         =!empty($row["EL"])?$row["EL"]:"";
						$data["ENT"]                        =!empty($row["ENT"])?$row["ENT"]:"";
						$data["F17"]                        =!empty($row["F17"])?$row["F17"]:"";
						$data["FAM"]                        =!empty($row["FAM"])?$row["FAM"]:"";
						$data["FBG"]                        =!empty($row["FBG"])?$row["FBG"]:"";
						$data["FBL"]                        =!empty($row["FBL"])?$row["FBL"]:"";
						$data["FBM"]                        =!empty($row["FBM"])?$row["FBM"]:"";
						$data["FBT"]                        =!empty($row["FBT"])?$row["FBT"]:"";
						$data["FBU"]                        =!empty($row["FBU"])?$row["FBU"]:"";
						$data["FP"]                         =!empty($row["FP"])?$row["FP"]:"";
						$data["FPL"]                        =!empty($row["FPL"])?$row["FPL"]:"";
						$data["FPM"]                        =!empty($row["FPM"])?$row["FPM"]:"";
						$data["FPU"]                        =!empty($row["FPU"])?$row["FPU"]:"";
						$data["GAR"]                        =!empty($row["GAR"])?$row["GAR"]:"";
						$data["HBG"]                        =!empty($row["HBG"])?$row["HBG"]:"";
						$data["HBL"]                        =!empty($row["HBL"])?$row["HBL"]:"";
						$data["HBM"]                        =!empty($row["HBM"])?$row["HBM"]:"";
						$data["HBT"]                        =!empty($row["HBT"])?$row["HBT"]:"";
						$data["HBU"]                        =!empty($row["HBU"])?$row["HBU"]:"";
						$data["HOD"]                        =!empty($row["HOD"])?$row["HOD"]:"";
						$data["JH"]                         =!empty($row["JH"])?$row["JH"]:"";
						$data["KES"]                        =!empty($row["KES"])?$row["KES"]:"";
						$data["KIT"]                        =!empty($row["KIT"])?$row["KIT"]:"";
						$data["LRM"]                        =!empty($row["LRM"])?$row["LRM"]:"";
						$data["LSD"]                        =!empty($row["LSD"])?$row["LSD"]:"";
						$data["LSZ"]                        =!empty($row["LSZ"])?$row["LSZ"]:"";
						$data["LT"]                         =!empty($row["LT"])?$row["LT"]:"";
						$data["MBD"]                        =!empty($row["MBD"])?$row["MBD"]:"";
						$data["MHM"]                        =!empty($row["MHM"])?$row["MHM"]:"";
						$data["MHN"]                        =!empty($row["MHN"])?$row["MHN"]:"";
						$data["MHS"]                        =!empty($row["MHS"])?$row["MHS"]:"";
						$data["MOR"]                        =!empty($row["MOR"])?$row["MOR"]:"";
						$data["NC"]                         =!empty($row["NC"])?$row["NC"]:"";
						$data["POC"]                        =!empty($row["POC"])?$row["POC"]:"";
						$data["POL"]                        =!empty($row["POL"])?$row["POL"]:"";
						$data["PRJ"]                        =!empty($row["PRJ"])?$row["PRJ"]:"";
						$data["PTO"]                        =!empty($row["PTO"])?$row["PTO"]:"";
						$data["TQBT"]                       =!empty($row["TQBT"])?$row["TQBT"]:"";
						$data["RRM"]                        =!empty($row["RRM"])?$row["RRM"]:"";
						$data["CMFE"]                       =!empty($row["CMFE"])?$row["CMFE"]:"";
						$data["SAP"]                        =!empty($row["SAP"])?$row["SAP"]:"";
						$data["SFF"]                        =!empty($row["SFF"])?$row["SFF"]:"";
						$data["SFS"]                        =!empty($row["SFS"])?$row["SFS"]:"";
						$data["SFU"]                        =!empty($row["SFU"])?$row["SFU"]:"";
						$data["SH"]                         =!empty($row["SH"])?$row["SH"]:"";
						$data["SML"]                        =!empty($row["SML"])?$row["SML"]:"";
						$data["SNR"]                        =!empty($row["SNR"])?$row["SNR"]:"";
						$data["STY"]                        =!empty($row["STY"])?$row["STY"]:"";
						$data["SWC"]                        =!empty($row["SWC"])?$row["SWC"]:"";
						$data["TBG"]                        =!empty($row["TBG"])?$row["TBG"]:"";
						$data["TBL"]                        =!empty($row["TBL"])?$row["TBL"]:"";
						$data["TBM"]                        =!empty($row["TBM"])?$row["TBM"]:"";
						$data["TBU"]                        =!empty($row["TBU"])?$row["TBU"]:"";
						$data["TX"]                         =!empty($row["TX"])?$row["TX"]:"";
						$data["TXY"]                        =!empty($row["TXY"])?$row["TXY"]:"";
						$data["UTR"]                        =!empty($row["UTR"])?$row["UTR"]:"";
						$data["WAC"]                        =!empty($row["WAC"])?$row["WAC"]:"";
						$data["WFG"]                        =!empty($row["WFG"])?$row["WFG"]:"";
						$data["WHT"]                        =!empty($row["WHT"])?$row["WHT"]:"";
						$data["APS"]                        =!empty($row["APS"])?$row["APS"]:"";
						$data["BDI"]                        =!empty($row["BDI"])?$row["BDI"]:"";
						$data["BSM"]                        =!empty($row["BSM"])?$row["BSM"]:"";
						$data["ENS"]                        =!empty($row["ENS"])?$row["ENS"]:"";
						$data["EXT"]                        =!empty($row["EXT"])?$row["EXT"]:"";
						$data["FEA"]                        =!empty($row["FEA"])?$row["FEA"]:"";
						$data["FLS"]                        =!empty($row["FLS"])?$row["FLS"]:"";
						$data["FND"]                        =!empty($row["FND"])?$row["FND"]:"";
						$data["GR"]                         =!empty($row["GR"])?$row["GR"]:"";
						$data["HTC"]                        =!empty($row["HTC"])?$row["HTC"]:"";
						$data["LDE"]                        =!empty($row["LDE"])?$row["LDE"]:"";
						$data["LTV"]                        =!empty($row["LTV"])?$row["LTV"]:"";
						$data["POS"]                        =!empty($row["POS"])?$row["POS"]:"";
						$data["RF"]                         =!empty($row["RF"])?$row["RF"]:"";
						$data["SIT"]                        =!empty($row["SIT"])?$row["SIT"]:"";
						$data["SWR"]                        =!empty($row["SWR"])?$row["SWR"]:"";
						$data["TRM"]                        =!empty($row["TRM"])?$row["TRM"]:"";
						$data["VEW"]                        =!empty($row["VEW"])?$row["VEW"]:"";
						$data["WAS"]                        =!empty($row["WAS"])?$row["WAS"]:"";
						$data["WFT"]                        =!empty($row["WFT"])?$row["WFT"]:"";
						$data["BUSR"]                       =!empty($row["BUSR"])?$row["BUSR"]:"";
						$data["ECRT"]                       =!empty($row["ECRT"])?$row["ECRT"]:"";
						$data["ZJD"]                        =!empty($row["ZJD"])?$row["ZJD"]:"";
						$data["ZNC"]                        =!empty($row["ZNC"])?$row["ZNC"]:"";
						$data["ProhibitBLOG"]               =!empty($row["ProhibitBLOG"])?$row["ProhibitBLOG"]:"";
						$data["AllowAVM"]                   =!empty($row["AllowAVM"])?$row["AllowAVM"]:"";
						$data["PARQ"]                       =!empty($row["PARQ"])?$row["PARQ"]:"";
						$data["BREO"]                       =!empty($row["BREO"])?$row["BREO"]:"";
						$data["BuiltGreenRating"]           =!empty($row["BuiltGreenRating"])?$row["BuiltGreenRating"]:"";
						$data["EPSEnergy"]                  =!empty($row["EPSEnergy"])?$row["EPSEnergy"]:"";
						$data["ROFR"]                       =!empty($row["ROFR"])?$row["ROFR"]:"";
						$data["HERSIndex"]                  =!empty($row["HERSIndex"])?$row["HERSIndex"]:"";
						$data["LEEDRating"]                 =!empty($row["LEEDRating"])?$row["LEEDRating"]:"";
						$data["NewConstruction"]            =!empty($row["NewConstruction"])?$row["NewConstruction"]:"";
						$data["NWESHRating"]                =!empty($row["NWESHRating"])?$row["NWESHRating"]:"";
						$data["ConstructionMethods"]        =!empty($row["ConstructionMethods"])?$row["ConstructionMethods"]:"";
						$data["EMP"]                        =!empty($row["EMP"])?$row["EMP"]:"";
						$data["EQU"]                        =!empty($row["EQU"])?$row["EQU"]:"";
						$data["EQV"]                        =!empty($row["EQV"])?$row["EQV"]:"";
						$data["FRN"]                        =!empty($row["FRN"])?$row["FRN"]:"";
						$data["GRS"]                        =!empty($row["GRS"])?$row["GRS"]:"";
						$data["GW"]                         =!empty($row["GW"])?$row["GW"]:"";
						$data["HRS"]                        =!empty($row["HRS"])?$row["HRS"]:"";
						$data["INV"]                        =!empty($row["INV"])?$row["INV"]:"";
						$data["LNM"]                        =!empty($row["LNM"])?$row["LNM"]:"";
						$data["LSI"]                        =!empty($row["LSI"])?$row["LSI"]:"";
						$data["NA"]                         =!empty($row["NA"])?$row["NA"]:"";
						$data["NP"]                         =!empty($row["NP"])?$row["NP"]:"";
						$data["PKC"]                        =!empty($row["PKC"])?$row["PKC"]:"";
						$data["PKU"]                        =!empty($row["PKU"])?$row["PKU"]:"";
						$data["RES"]                        =!empty($row["RES"])?$row["RES"]:"";
						$data["RNT"]                        =!empty($row["RNT"])?$row["RNT"]:"";
						$data["SIN"]                        =!empty($row["SIN"])?$row["SIN"]:"";
						$data["TEXP"]                       =!empty($row["TEXP"])?$row["TEXP"]:"";
						$data["TOB"]                        =!empty($row["TOB"])?$row["TOB"]:"";
						$data["YRE"]                        =!empty($row["YRE"])?$row["YRE"]:"";
						$data["YRS"]                        =!empty($row["YRS"])?$row["YRS"]:"";
						$data["LES"]                        =!empty($row["LES"])?$row["LES"]:"";
						$data["LIC"]                        =!empty($row["LIC"])?$row["LIC"]:"";
						$data["LOC"]                        =!empty($row["LOC"])?$row["LOC"]:"";
						$data["MTB"]                        =!empty($row["MTB"])?$row["MTB"]:"";
						$data["RP"]                         =!empty($row["RP"])?$row["RP"]:"";
						$data["LSZS"]                       =!empty($row["LSZS"])?$row["LSZS"]:"";
						$data["AFH"]                        =!empty($row["AFH"])?$row["AFH"]:"";
						$data["ASC"]                        =!empty($row["ASC"])?$row["ASC"]:"";
						$data["COO"]                        =!empty($row["COO"])?$row["COO"]:"";
						$data["MGR"]                        =!empty($row["MGR"])?$row["MGR"]:"";
						$data["NAS"]                        =!empty($row["NAS"])?$row["NAS"]:"";
						$data["NOC"]                        =!empty($row["NOC"])?$row["NOC"]:"";
						$data["NOS"]                        =!empty($row["NOS"])?$row["NOS"]:"";
						$data["NOU"]                        =!empty($row["NOU"])?$row["NOU"]:"";
						$data["OOC"]                        =!empty($row["OOC"])?$row["OOC"]:"";
						$data["PKS"]                        =!empty($row["PKS"])?$row["PKS"]:"";
						$data["REM"]                        =!empty($row["REM"])?$row["REM"]:"";
						$data["SAA"]                        =!empty($row["SAA"])?$row["SAA"]:"";
						$data["SPA"]                        =!empty($row["SPA"])?$row["SPA"]:"";
						$data["STG"]                        =!empty($row["STG"])?$row["STG"]:"";
						$data["STL"]                        =!empty($row["STL"])?$row["STL"]:"";
						$data["TOF"]                        =!empty($row["TOF"])?$row["TOF"]:"";
						$data["UFN"]                        =!empty($row["UFN"])?$row["UFN"]:"";
						$data["WDW"]                        =!empty($row["WDW"])?$row["WDW"]:"";
						$data["APH"]                        =!empty($row["APH"])?$row["APH"]:"";
						$data["CMN"]                        =!empty($row["CMN"])?$row["CMN"]:"";
						$data["CTD"]                        =!empty($row["CTD"])?$row["CTD"]:"";
						$data["HOI"]                        =!empty($row["HOI"])?$row["HOI"]:"";
						$data["PKG"]                        =!empty($row["PKG"])?$row["PKG"]:"";
						$data["UNF"]                        =!empty($row["UNF"])?$row["UNF"]:"";
						$data["STRS"]                       =!empty($row["STRS"])?$row["STRS"]:"";
						$data["FUR"]                        =!empty($row["FUR"])?$row["FUR"]:"";
						$data["MLT"]                        =!empty($row["MLT"])?$row["MLT"]:"";
						$data["STO"]                        =!empty($row["STO"])?$row["STO"]:"";
						$data["AFR"]                        =!empty($row["AFR"])?$row["AFR"]:"";
						$data["APP"]                        =!empty($row["APP"])?$row["APP"]:"";
						$data["MIF"]                        =!empty($row["MIF"])?$row["MIF"]:"";
						$data["TMC"]                        =!empty($row["TMC"])?$row["TMC"]:"";
						$data["TYP"]                        =!empty($row["TYP"])?$row["TYP"]:"";
						$data["UTL"]                        =!empty($row["UTL"])?$row["UTL"]:"";
						$data["ELE"]                        =!empty($row["ELE"])?$row["ELE"]:"";
						$data["ESM"]                        =!empty($row["ESM"])?$row["ESM"]:"";
						$data["GAS"]                        =!empty($row["GAS"])?$row["GAS"]:"";
						$data["LVL"]                        =!empty($row["LVL"])?$row["LVL"]:"";
						$data["QTR"]                        =!empty($row["QTR"])?$row["QTR"]:"";
						$data["RD"]                         =!empty($row["RD"])?$row["RD"]:"";
						$data["SDA"]                        =!empty($row["SDA"])?$row["SDA"]:"";
						$data["SEC"]                        =!empty($row["SEC"])?$row["SEC"]:"";
						$data["SEP"]                        =!empty($row["SEP"])?$row["SEP"]:"";
						$data["SFA"]                        =!empty($row["SFA"])?$row["SFA"]:"";
						$data["SLP"]                        =!empty($row["SLP"])?$row["SLP"]:"";
						$data["SST"]                        =!empty($row["SST"])?$row["SST"]:"";
						$data["SUR"]                        =!empty($row["SUR"])?$row["SUR"]:"";
						$data["TER"]                        =!empty($row["TER"])?$row["TER"]:"";
						$data["WRJ"]                        =!empty($row["WRJ"])?$row["WRJ"]:"";
						$data["ZNR"]                        =!empty($row["ZNR"])?$row["ZNR"]:"";
						$data["ATF"]                        =!empty($row["ATF"])?$row["ATF"]:"";
						$data["DOC"]                        =!empty($row["DOC"])?$row["DOC"]:"";
						$data["FTR"]                        =!empty($row["FTR"])?$row["FTR"]:"";
						$data["GZC"]                        =!empty($row["GZC"])?$row["GZC"]:"";
						$data["IMP"]                        =!empty($row["IMP"])?$row["IMP"]:"";
						$data["RDI"]                        =!empty($row["RDI"])?$row["RDI"]:"";
						$data["RS2"]                        =!empty($row["RS2"])?$row["RS2"]:"";
						$data["TPO"]                        =!empty($row["TPO"])?$row["TPO"]:"";
						$data["WTR"]                        =!empty($row["WTR"])?$row["WTR"]:"";
						$data["AUCTION"]                    =!empty($row["AUCTION"])?$row["AUCTION"]:"";
						$data["LotSizeSource"]   			=!empty($row["LotSizeSource"])?$row["LotSizeSource"]:"";
						$data["EffectiveYearBuilt"]         =!empty($row["EffectiveYearBuilt"])?$row["EffectiveYearBuilt"]:"";
						$data["EffectiveYearBuiltSource"]   =!empty($row["EffectiveYearBuiltSource"])?$row["EffectiveYearBuiltSource"]:"";
						$data['modified_date']				=date('Y-m-d h:i:s');
						$data['status']						='1';
						//pr($data);
						$fields=array('ID','LN');
						$match=array('LN'=>!empty($row['LN'])?$row['LN']:'');
						$res=$this->obj->select_records3('',$match,'','=');
						if(empty($res))
						{
							$data['created_date']		=date('Y-m-d h:i:s');
							$id=$this->obj->insert_record3($data);
						}
						else
						{
							$id=$this->obj->update_record3($data);
						}
					}
					
				}
			}
		}
		redirect('superadmin/mls/add_record');
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
		redirect('superadmin/mls/add_record');
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
		
		$field=array('id','max(change_date) as change_date');
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
		}
		
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
		redirect('superadmin/mls/add_record');
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
		redirect('superadmin/mls/add_record');
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
						$match=array('school_district_code'=>!empty($row['OfficeMLSID'])?$row['OfficeMLSID']:'');
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
							//echo $this->db->last_query();
						}
					}
					
				}
				
			}
		}
		redirect('superadmin/mls/add_record');
    }
	 /*
    @Description: Function for Delete Envelope Profile By superadmin
    @Author: Niral Patel
    @Input: - Delete all id of Envelope record want to delete
    @Output: - Envelope list Empty after record is deleted.
    @Date: 20-02-2015
    */
	
	public function ajax_delete_all()
	{
		$id=$this->input->post('single_remove_id');
		$array_data=$this->input->post('myarray');
		$tab = $this->input->post('tab');
				
		if(!empty($tab))
		{
			if($tab == 1)
				$table = 'mls_amenity_data';
			elseif($tab == 2)
				$table = 'mls_area_community_data';
			elseif($tab == 3)
				$table = 'mls_office_data';
			elseif($tab == 4)
				$table = 'mls_property_history_data';
			elseif($tab == 5)
				$table = 'mls_school_data';
			elseif($tab == 6)
				$table = 'mls_member_data';
			elseif($tab == 7)
				$table = 'mls_property_list_master';
				
			$delete_all_flag = 0;$cnt = 0;
			if(!empty($id))
			{
				$this->obj->mls_delete_record($id,$table);
				unset($id);
			}
			elseif(!empty($array_data))
			{
				for($i=0;$i<count($array_data);$i++)
				{
					$this->obj->mls_delete_record($array_data[$i],$table);
					$delete_all_flag = 1;
					$cnt++;
				}
			}
		}
		$searchsort_session = $this->session->userdata('mls_amenity_sortsearchpage_data');
		if(!empty($searchsort_session['uri_segment']))
			$pagingid = $searchsort_session['uri_segment'];
		else
			$pagingid = 0;
		$perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
		$total_rows = $searchsort_session['total_rows'];
		if($delete_all_flag == 1)
		{
			$total_rows -= $cnt;
			if($pagingid*$perpage > $total_rows) {
				if($total_rows % $perpage == 0)
				{
					$pagingid -= $perpage;
				}
			}
		} else {
			if($total_rows % $perpage == 1)
				$pagingid -= $perpage;
		}
		
		if($pagingid < 0)
			$pagingid = 0;
		echo $pagingid;
		//echo 1;
	}
	
	
}