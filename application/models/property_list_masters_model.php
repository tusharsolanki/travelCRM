<?php

	/*
    @Description: Contact Model
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/
class property_list_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'property_listing__property_type_master';
		$this->table_name_document = 'property_listing__document_type_master';
		$this->table_name_lot_type = 'property_listing__lot_type_master';
		$this->table_name_transaction = 'property_listing__transaction_type_master';
		$this->table_name_lockbox = 'property_listing__lockbox_type_master';
		$this->table_name_sewer = 'property_listing__sewer_master';
		$this->table_name_basement = 'property_listing__basement_master';
		$this->table_name_architecture = 'property_listing__architecture_master';
		$this->table_name_energy_source = 'property_listing__energy_source_master';
		$this->table_method_exterior_finish = 'property_listing__exterior_finish_master';
		$this->table_name_fireplace = 'property_listing__fireplace_master';
		$this->table_name_floor_covering = 'property_listing__floor_covering_master';
		$this->table_name_foundation = 'property_listing__foundation_master';
		$this->table_name_green_certification = 'property_listing__green_certification_master';
		$this->table_name_heating_cooling = 'property_listing__heating_cooling_master';
		$this->table_name_interior_feature = 'property_listing__interior_feature_master';
		$this->table_name_parking_type = 'property_listing__parking_type_master';
		$this->table_name_power_company = 'property_listing__power_company_master';
		$this->table_name_roof_master = 'property_listing__roof_master';
		
		$this->table_name_sewer_company = 'property_listing__sewer_company_master';
		$this->table_name_style_master = 'property_listing__style_master';
		$this->table_name_water_company = 'property_listing__water_company_master';
		$this->table_name_property_status = 'property_listing__status_master';
		 
    }

    /*
    @Description: Function for get Module Lists
    @Author: Mit Makwana
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE ';
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
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
		
        $query = $this->db->query($query);
		
        return $query->result_array();
    }
	
	
	public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='',$where_cond='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$tbl_name;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$tbl_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
    

    /*
    @Description: Function is for Insert property details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_property_list($data)
    {
		
		$property_list_type = $data['name'];
		for($i=0;$i<count($property_list_type);$i++)
		{
			$data['name'] = $property_list_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_document_list($data)
    { //pr($_POST);exit;
		$document_list_type = $data['name'];
		for($i=0;$i<count($document_list_type);$i++)
		{
			$data['name'] = $document_list_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_document,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_lot_type($data)
    {
		$lot_type = $data['name'];
		for($i=0;$i<count($lot_type);$i++)
		{
			$data['name'] = $lot_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_lot_type,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_transaction($data)
    {
		$transaction_type = $data['name'];
		for($i=0;$i<count($transaction_type);$i++)
		{
			$data['name'] = $transaction_type[$i];
			if(trim($data['name']) != "")
			$result =  $this->db->insert($this->table_name_transaction,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_lockbox($data)
    {
		$lockbox_type = $data['name'];
		for($i=0;$i<count($lockbox_type);$i++)
		{
			$data['name'] = $lockbox_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_lockbox,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_sewer($data)
    {
		$sewer_type = $data['name'];
		for($i=0;$i<count($sewer_type);$i++)
		{
			$data['name'] = $sewer_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_sewer,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_basement($data)
    {
		$basement_list = $data['name'];
		for($i=0;$i<count($basement_list);$i++)
		{
			$data['name'] = $basement_list[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_basement,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_architecture($data)
    {
		$architecture_type = $data['name'];
		for($i=0;$i<count($architecture_type);$i++)
		{
			$data['name'] = $architecture_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_architecture,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_energy_source($data)
    {
		$energy_source_type = $data['name'];
		for($i=0;$i<count($energy_source_type);$i++)
		{
			$data['name'] = $energy_source_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_energy_source,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }

	function insert_exterior_finish($data)
    {
		$exterior_finish_type = $data['name'];
		for($i=0;$i<count($exterior_finish_type);$i++)
		{
			$data['name'] = $exterior_finish_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_method_exterior_finish,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }

	function insert_fireplace($data)
    {
		$fireplace_type = $data['name'];
		for($i=0;$i<count($fireplace_type);$i++)
		{
			$data['name'] = $fireplace_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_fireplace,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }

	function insert_floor_covering($data)
		{
			$floor_covering_type = $data['name'];
			for($i=0;$i<count($floor_covering_type);$i++)
			{
				$data['name'] = $floor_covering_type[$i];
				if(trim($data['name']) != "")
					$result =  $this->db->insert($this->table_name_floor_covering,$data);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}

	function insert_foundation($data)
    {
		$foundation_type = $data['name'];
		for($i=0;$i<count($foundation_type);$i++)
		{
			$data['name'] = $foundation_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_foundation,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_green_certification_record($data)
    {
		$green_certification_type = $data['name'];
		for($i=0;$i<count($green_certification_type);$i++)
		{
			$data['name'] = $green_certification_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_green_certification,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_heating_cooling_record($data)
    {
		$heating_cooling_type = $data['name'];
		for($i=0;$i<count($heating_cooling_type);$i++)
		{
			$data['name'] = $heating_cooling_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_heating_cooling,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_interior_feature_record($data)
    {
		$interior_feature_type = $data['name'];
		for($i=0;$i<count($interior_feature_type);$i++)
		{
			$data['name'] = $interior_feature_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_interior_feature,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_parking_type_record($data)
    {
		$parking_type = $data['name'];
		for($i=0;$i<count($parking_type);$i++)
		{
			$data['name'] = $parking_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_parking_type,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_power_company_record($data)
    {
		$power_company_type = $data['name'];
		for($i=0;$i<count($power_company_type);$i++)
		{
			$data['name'] = $power_company_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_power_company,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_roof_master_record($data)
    {
		$roof_master_type = $data['name'];
		for($i=0;$i<count($roof_master_type);$i++)
		{
			$data['name'] = $roof_master_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_roof_master,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_sewer_company_record($data)
    {
		$sewer_company_type = $data['name'];
		for($i=0;$i<count($sewer_company_type);$i++)
		{
			$data['name'] = $sewer_company_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_sewer_company,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_style_master_record($data)
    {
		$style_master_type = $data['name'];
		for($i=0;$i<count($style_master_type);$i++)
		{
			$data['name'] = $style_master_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_style_master,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_water_company_record($data)
    {
		$water_company_type = $data['name'];
		for($i=0;$i<count($water_company_type);$i++)
		{
			$data['name'] = $water_company_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_water_company,$data);
			$lastId = mysql_insert_id();
		}
		return $lastId;
    }
	
	
	
	function insert_property_status_record($data)
    {
		$property_status_type = $data['name'];
		for($i=0;$i<count($property_status_type);$i++)
		{
			$data['name'] = $property_status_type[$i];
			if(trim($data['name']) != "")
				$result =  $this->db->insert($this->table_name_property_status,$data);
			$lastId[] = mysql_insert_id();
		}
		return $lastId;
    }
	
	function insert_status_record_child_db($child_db,$data)
    {
        $idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');
        $idata['status'] = '1';
       	if(!empty($data['name']))
		{
			$email_type = $data['name'];
			foreach($email_type as $row)
			{
				//echo 'foreach';
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($child_db.'.'.$this->table_name_property_status,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		//echo 'insert_end';
    }

	
    /*
    @Description: Function is for update customer details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_property_list($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_document_list($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_document,$data); 
    }
	
	public function update_lot_type($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_lot_type,$data); 
    }
	
	public function update_transaction($data)
    {	
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_transaction,$data); 
    }
	
	public function update_lockbox($data)
    {	
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_lockbox,$data); 
    }
	
	public function update_sewer($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_sewer,$data); 
    }
	
	public function update_basement($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_basement,$data); 
    }
	
	public function update_architecture($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_architecture,$data); 
    }
	
	public function update_energy_source($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_energy_source,$data); 
    }

	public function update_exterior_finish($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_method_exterior_finish,$data); 
    }

	public function update_fireplace($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_fireplace,$data); 
    }
	
	public function update_floor_covering($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_floor_covering,$data); 
    }
		
	public function update_foundation($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_foundation,$data); 
    }
	
	public function update_green_certification_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_green_certification,$data); 
    }
	public function update_heating_cooling_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_heating_cooling,$data); 
    }
	public function update_interior_feature_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_interior_feature,$data); 
    }
	public function update_parking_type_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_parking_type,$data); 
    }
	public function update_power_company_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_power_company,$data); 
    }
	public function update_roof_master_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_roof_master,$data); 
    }
	
	public function update_sewer_company_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_sewer_company,$data); 
    }
	
	public function update_style_master_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_style_master,$data); 
    }
	
	public function update_water_company_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_water_company,$data); 
    }
	
	
	
	public function update_property_status_record($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_property_status,$data); 
    }
	
    /*
    @Description: Function for Delete Contact Profile By Admin
    @Author: Mit Makwana
    @Input: - Contact id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_property_list_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	public function delete_document_list_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_document);
    }
	
	 public function delete_lot_type_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_lot_type);
    }
	
	 public function delete_trasaction_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_transaction);
    }
	
	 public function delete_lockbox_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_lockbox);
    }
	
	 public function delete_sewer_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_sewer);
    }
	
	 public function delete_basement_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_basement);
    }
	
	public function delete_architecture_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_architecture);
    }	
	
	public function delete_energy_source_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_energy_source);
    }	

	public function delete_exterior_finish_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_method_exterior_finish);
    }	
	public function delete_fireplace_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_fireplace);
    }	
	
	public function delete_floor_covering_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_floor_covering);
    }	
	
	public function delete_foundation_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_foundation);
    }	
	
	public function delete_green_certification_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_green_certification);
    }
	public function delete_heating_cooling_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_heating_cooling);
    }	
	public function delete_interior_feature_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_interior_feature);
    }	
	public function delete_parking_type_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_parking_type);
    }	
	public function delete_power_company_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_power_company);
    }	
	public function delete_roof_master_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_roof_master);
    }	
	
	public function delete_sewer_company_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_sewer_company);
    }	
	
	public function delete_style_master_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_style_master);
    }	
	
	public function delete_water_company_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_water_company);
    }	
	
	
	public function delete_property_status_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_property_status);
    }		

	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-09-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='')
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
				$colldata = explode('jointype',$coll);
				$coll = trim($colldata[0]);
				
				if(!empty($colldata[1]))
				{	
					$join_type1 = trim($colldata[1]);
					if($join_type1 == 'direct')
						$join_type1 = '';
				}
				
				if(isset($join_type1))
					$this->db->join($coll, $value,$join_type1);
				else
					$this->db->join($coll, $value,$join_type);
				
				unset($join_type1);
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
			$this->db->order_by($orderby);
				
		if($match_values != null &&  $compare_type != null )
		$this->db->or_like($match_values);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
  		return $query_FC->result_array();
  
	}

}