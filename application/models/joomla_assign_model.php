<?php
	/*
    @Description: Lead Capturing Model
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 13-09-2014
	*/

class joomla_assign_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name  = 'joomla_leads_plan_assign';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 13-09-2014
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

 
 
 /*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 13-09-2014
    */
   
    public function select_records3($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name1;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }

 
 
 
 
 
 /*
    @Description: Function for get Module event
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 13-09-2014
    */
   
    public function select_records2($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_event;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_event.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        return $query->result_array();
    }

	
	
	/*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 13-09-2014
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name1;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
	    return $query->result_array();
    }
	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 13-09-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$count_flag='',$min_price='',$max_price='',$price_flag='')
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
		
                if(!empty($price_flag))
                {
                    $this->db->where('CASE WHEN (jlps.min_price > 0 AND jlps.max_price > 0) THEN '. $min_price.' BETWEEN jlps.min_price and jlps.max_price AND '. $max_price.' BETWEEN jlps.min_price and jlps.max_price
                        WHEN (jlps.min_price > 0 AND jlps.max_price <= 0) THEN jlps.min_price >= '.$min_price.'
                        WHEN (jlps.max_price > 0 AND jlps.min_price <= 0) THEN jlps.max_price >= '.$max_price.'
                        ELSE (jlps.min_price <= 0 AND jlps.max_price <= 0) END', NULL, FALSE);  
                }
                /*if(!empty($min_price) && !empty($max_price)) {
                    $this->db->where($min_price.' BETWEEN jlps.min_price and jlps.max_price', NULL, FALSE);  
                    $this->db->where($max_price.' BETWEEN jlps.min_price and jlps.max_price', NULL, FALSE);  
                }
                else if(!empty($min_price)) {
                    $this->db->where(' jlps.min_price >='.$min_price, NULL, FALSE);
                }
                else if(!empty($max_price)) {
                    $this->db->where(' jlps.max_price <='.$max_price, NULL, FALSE);
                }
                else {
                    $this->db->where('jlps.min_price <=',0);
                    $this->db->where('jlps.max_price <=',0);
                }*/
                
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
                if(!empty($count_flag))
                    return $query_FC->num_rows();
                else
                    return $query_FC->result_array();
  
	}
    

    /*
    @Description: Function is for Insert Lead Capturing details by Admin
    @Author: Mohit Trivedi
    @Input: Lead Capturing details for Insert into DB
    @Output: - Insert record into DB
    @Date: 13-09-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

    /*
    @Description: Function is for Insert Lead Capturing data details by Admin
    @Author: Mohit Trivedi
    @Input: Lead Capturing details for Insert into DB
    @Output: - Insert record into DB
    @Date: 17-09-2014
    */
	
    function insert_record1($data)
    {
        $result =  $this->db->insert($this->table_name1,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	/*
    @Description: Function is for update customer details by Admin
    @Author: Mohit Trivedi
    @Input: Lead Capturing details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 13-09-2014
    */

    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	/*
    @Description: Function is for update lead data status by Admin
    @Author: Mohit Trivedi
    @Input: Lead data details for Update into DB
    @Output: - Update lead data status into DB with give id
    @Date: 19-09-2014
    */

    public function update_record1($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name1,$data); 
		
    }
	
	/*
    @Description: Function for Delete Lead Capturing Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Lead Capturing id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 13-09-2014
    */

    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	 /*
    @Description: Function for Delete Lead Capturing Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Lead Capturing id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 13-09-2014
    */
    public function delete_record1($id)
    {
		$this->db->where('id',$id);
        $this->db->delete($this->table_name1);
    }
	
	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getletterpagingid($letter_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $letter_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
	}

	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function select_max()
	{
		$this->db->select('max(id) as id');
		$this->db->from($this->table_name);
		$result = $this->db->get()->result_array();
		return $result;

	}
	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 19-09-14
    */

	public function getemailpagingid($lead_id='')
	{
			
		$this->db->select('*');
		$this->db->from($this->table_name1);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $lead_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
	
		return $op;
	}


	
}