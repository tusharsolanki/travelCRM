<?php

	/*
    @Description: Contact Model
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/
class contact_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'contact__email_type_master';
		$this->table_name_phone = 'contact__phone_type_master';
		$this->table_name_address = 'contact__address_type_master';
		$this->table_name_website = 'contact__websitetype_master';
		$this->table_name_status = 'contact__status_master';
		$this->table_name_profile = 'contact__social_type_master';
		$this->table_name_contact = 'contact__type_master';
		$this->table_name_document = 'contact__document_type_master';
		$this->table_name_source = 'contact__source_master';
		$this->table_name_disposition = 'contact__disposition_master';
		$this->table_method_contact = 'contact__method_master';
		$this->table_additionfield = 'contact__additionalfield_master';
		$this->table_linkedin = 'contact_linkedin_trasection';

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
    @Description: Function is for Insert Contact details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_email($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$email_type = $data['name'];
			foreach($email_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	function insert_phone($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$phone_type = $data['name'];
			foreach($phone_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_phone,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	function insert_address($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$address_type = $data['name'];
			foreach($address_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_address,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	function insert_website($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$website_type = $data['name'];
			foreach($website_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_website,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	function insert_status($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$website_type = $data['name'];
			foreach($website_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_status,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	function insert_profile($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$website_type = $data['name'];
			foreach($website_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_profile,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}

    }
	
	function insert_contact($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$contact_type = $data['name'];
			foreach($contact_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_contact,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		
		
    }


	
	function insert_document($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$document_type = $data['name'];
			foreach($document_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_document,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}

    }
	
	function insert_source($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$source_type = $data['name'];
			foreach($source_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_source,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		
    }
	
	function insert_disposition($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$disposition_type = $data['name'];
			foreach($disposition_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_name_disposition,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		
	}

	function insert_method($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$method_type = $data['name'];
			foreach($method_type as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
					$result =  $this->db->insert($this->table_method_contact,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		
		
    }

	function insert_field($data)
    {
		$created_by = !empty($data['created_by'])?$data['created_by']:0;
		if(!empty($data['name']))
		{
			$field_name = implode('{^}',$data['name']);
			if(!empty($data['field_type']))
				$field_type = implode('{^}',$data['field_type']);
			$lastId = 0;
			if(!empty($field_name) && !empty($field_type))
			{
				$field_name = explode('{^}',$field_name);
				$field_type = explode('{^}',$field_type);
				for($i=0;$i<count($field_name);$i++)
				{
					if(!empty($field_name[$i]))
						$data['name'] = $field_name[$i];
					if(!empty($field_type[$i]))
						$data['field_type'] = $field_type[$i];
					if(trim($data['name']) != "")
					{
						$data['created_by'] = $created_by;
						$data['created_date'] = date('Y-m-d H:i:s');;
						$data['status'] = '1';
						$result =  $this->db->insert($this->table_additionfield,$data);
						$lastId = mysql_insert_id();
						unset($data);
					}
				}
			}
			return $lastId;
		}
    }


	
    /*
    @Description: Function is for update customer details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_email($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_phone($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_phone,$data); 
    }
	
	public function update_address($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_address,$data); 
    }
	
	public function update_website($data)
    {	
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_website,$data); 
    }
	
	public function update_status($data)
    {	
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_status,$data); 
    }
	
	public function update_profile($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_profile,$data); 
    }
	
	public function update_contact($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_contact,$data); 
    }
	
	public function update_document($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_document,$data); 
    }
	
	public function update_source($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_source,$data); 
    }
	
	public function update_disposition($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_disposition,$data); 
    }

	public function update_method($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_method_contact,$data); 
    }

	public function update_field($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_additionfield,$data); 
    }
	
    /*
    @Description: Function for Delete Contact Profile By Admin
    @Author: Mit Makwana
    @Input: - Contact id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_email_record($id)
    {
        $this->db->where('id',$id);
        $query=$this->db->delete($this->table_name);
        
    }
	
	public function delete_phone_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_phone);
    }
	
	 public function delete_address_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_address);
    }
	
	 public function delete_website_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_website);
    }
	
	 public function delete_status_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_status);
    }
	
	 public function delete_profile_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_profile);
    }
	
	 public function delete_contact_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_contact);
    }
	
	public function delete_document_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_document);
    }
	
	public function delete_source_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_source);
    }	
	
	public function delete_disposition_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_disposition);
    }	

	public function delete_method_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_method_contact);
    }	
	public function delete_field_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_additionfield);
    }	

	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-09-2014
    */
	
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$not_where_in='',$where_in='',$order_flag='',$min_price='',$max_price='',$price_flag='',$price_null='',$weightage_null='',$min_area='',$max_area='',$area_flag='',$area_null='')
    { 
    	//echo 'hello';
    	//exit; 
    	//print_r($fields);
    	//exit;
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
				$this->db->join($coll, $value,$join_type);
			}
		}
		
		if($condition != null )
		$this->db->where($condition);
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		if(!empty($not_where_in)){
                    foreach($not_where_in as $key => $value){
                        $this->db->where_not_in($key,$value);
                    }
		}
                
                if(!empty($where_in)){
                    $ids = '';
                    foreach($where_in as $key => $value){
                        $this->db->where($key.' IN ('.$value.')', NULL, FALSE);
                        $ids .= $value.",";
                        //$this->db->where_in($key,$value);
                    }
                    $ids = trim($ids,',');
                    //$this->db->where('`user_id` IN ('.$ids.')', NULL, FALSE);
		}
                
                if(!empty($price_flag)) {
                    //$this->db->where('minimum_price BETWEEN '.$min_price.' and '.$max_price, NULL, FALSE);  
                    //$this->db->where('maximum_price BETWEEN '.$min_price.' and '.$max_price, NULL, FALSE);  
                    
                    //$this->db->where('minimum_price >= '.$min_price.' and maximum_price <= '.$min_price, NULL, FALSE);  
                    //$this->db->where('minimum_price >= '.$max_price.' and maximum_price <= '.$max_price, NULL, FALSE);  
                    
                    $this->db->where($min_price.' BETWEEN minimum_price and maximum_price', NULL, FALSE);  
                    $this->db->where($max_price.' BETWEEN minimum_price and maximum_price', NULL, FALSE);  
                }
                
                if(!empty($area_flag)) {
                    $this->db->where($min_area.' BETWEEN min_area and max_area', NULL, FALSE);  
                    $this->db->where($max_area.' BETWEEN min_area and max_area', NULL, FALSE);  
                }
                
                if(!empty($price_null)) {
                    $this->db->where('minimum_price >= 0', NULL, FALSE);  
                    $this->db->where('maximum_price > 0', NULL, FALSE);  
                }
                
                if(!empty($area_null)) {
                    $this->db->where('min_area >= 0', NULL, FALSE);  
                    $this->db->where('max_area > 0', NULL, FALSE);  
                }
                
                if(!empty($weightage_null)) {
                    $this->db->where('um.user_weightage > 0', NULL, FALSE);  
                }
                
		if($group_by != null)
		$this->db->group_by($group_by);
		
                if(empty($order_flag))
                {
                    if($orderby != null && $sort != null)
                            $this->db->order_by($orderby,$sort);
                    elseif($orderby != null )
                            $this->db->order_by($orderby);
                }
                else
                {
                    $this->db->order_by('urwt.round_value desc, um.id asc');
                }
				
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