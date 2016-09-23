<?php

	/*
    @Description: nwmls Model
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
	*/

class mls_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name 			  = 'mls_type_of_mls_master';
		$this->table_name1 			  = 'mls_amenity_data';
		$this->table_name2			  = 'mls_area_community_data';
		$this->table_name3			  = 'mls_property_list_master';
		$this->table_name4			  = 'mls_property_image';
		$this->table_name5			  = 'mls_property_history_data';
		$this->table_name6			  = 'mls_member_data';
		$this->table_name7			  = 'mls_office_data';
		$this->table_name8			  = 'mls_school_data';
		$this->table_name_tran 		  = 'mls_property_type';
        $this->table_name_master      = 'mls_master';
        $this->table_name_mls_csv     = 'mls_csv_master';
        $this->table_name_mls_csv_mapping_master = 'mls_csv_mapping_master';
        $this->table_name_mls_mapping_trans      = 'mls_type_of_mls_mapping_trans';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
    /*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
    
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='',$having='',$or_where='')
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
                //$this->db->join($coll, $value,$join_type);
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
        if(!empty($where_in)){
            foreach($where_in as $key => $value){
                $this->db->where_in($key,$value);
            }
        }
                
                if(!empty($or_where)){
            foreach($or_where as $key => $value){
                $this->db->or_where($key,$value);
            }
        }
            
        if($group_by != null)
        $this->db->group_by($group_by);
        if($having != null)
            $this->db->having($having);
        if($orderby != null && $sort != null)
            $this->db->order_by($orderby,$sort);
        elseif($orderby != null )
        {
            if($orderby == 'special_case')
                $this->db->order_by('is_done asc,task_date asc');
            elseif($orderby == 'special_case_task')
                $this->db->order_by('id desc');
            else
                $this->db->order_by($orderby);
        }
        
                
        if($match_values != null &&  $compare_type != null )
        $this->db->or_like($match_values);
        
        if($offset != null && $num != null)
            $this->db->limit($num,$offset);
        elseif($num != null )
            $this->db->limit($num);
        
        $query_FC = $this->db->get();
                //echo $this->db->last_query();exit;
                if(!empty($totalrow))
                    return $query_FC->num_rows();
        else
                    return $query_FC->result_array();
  
    }
    public function select_records_common($table_name='',$getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='',$group_by='')
    {
       
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        if(!empty($db_name))
        {$sql .= ' FROM '.$db_name.'.'.$table_name;}
        else
        {$sql .= ' FROM '.$table_name;}
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
        if(!empty($where_clause))
        {
            $where .= ' AND (';
            
            foreach($where_clause as $key=>$val)
            {
                $where .= $key." LIKE '%".$val."%' OR ";
            }
            $where = rtrim($where,'OR ');
            $where .= ')';
        }
        if(!empty($group_by))
        {
            $sql .=' group by '.$group_by;    
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset == "0"){$offset = "";}
        if($num == "0"){$num = "";}
        // echo 'o'.$offset;
        if($offset =="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="" && $offset== 0)
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
        //echo $this->db->last_query();
        if(!empty($totalrows))
            return $query->num_rows();
        else
            return $query->result_array();
    }
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name;}
		else
		{$sql .= ' FROM '.$this->table_name;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
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
		//echo $this->db->last_query();
        if(!empty($totalrows))
            return $query->num_rows();
        else
            return $query->result_array();
    }
    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name1;}
		else
		{$sql .= ' FROM '.$this->table_name1;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        //echo $sql;exit;
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records2($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name2;}
		else
		{$sql .= ' FROM '.$this->table_name2;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name2.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records3($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='',$wherestring='',$table_name_pass='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
        {
            if(!empty($table_name_pass))
                $sql .= ' FROM '.$db_name.'.'.$table_name_pass;
            else
                $sql .= ' FROM '.$db_name.'.'.$this->table_name3;
        }
        else
        {
            if(!empty($table_name_pass))
                $sql .= ' FROM '.$table_name_pass;
            else
                $sql .= ' FROM '.$this->table_name3;
        }
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
        else
        {
            if(!empty($wherestring))
            {
                $where .= ' AND '.$wherestring;
            }
        }
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name3.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records4($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name4;}
		else
		{$sql .= ' FROM '.$this->table_name4;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name4.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records5($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name5;}
		else
		{$sql .= ' FROM '.$this->table_name5;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name5.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records6($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name6;}
		else
		{$sql .= ' FROM '.$this->table_name6;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name6.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records7($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name7;}
		else
		{$sql .= ' FROM '.$this->table_name7;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name7.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records8($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.nwmls_'.$this->table_name8;}
		else
		{$sql .= ' FROM '.$this->table_name8;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
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
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
	 /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
    public function select_records_tran($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='',$totalrows='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name_tran;}
		else
		{$sql .= ' FROM '.$this->table_name_tran;}
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
		}
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_tran.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrows))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
    /*
    @Description: Function is for Insert mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	function insert_batch_common($data,$table_name,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.'.$table_name,$data);}
        else
        {$result =  $this->db->insert_batch($table_name,$data);}
        //echo $this->db->last_query();
        $lastId = mysql_insert_id();
        return $lastId;
    }
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
    function insert_record_mapping($data)
    {
        $result =  $this->db->insert('mls_property_list_master',$data);  
      
        $lastId = mysql_insert_id();
        return $lastId;
    }
	/*
    @Description: Function is for Insert amenities details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record1($data,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert($db_name.'.nwmls_'.$this->table_name1,$data);}
        else
        {$result =  $this->db->insert($this->table_name1,$data);}
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area community details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record2($data,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert($db_name.'.nwmls_'.$this->table_name2,$data);}
        else
        {$result =  $this->db->insert($this->table_name2,$data);}
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area property details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record3($data,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.nwmls_'.$this->table_name3,$data);}
        else
        {$result =  $this->db->insert_batch($this->table_name3,$data);}
		//echo $this->db->last_query();
		$lastId = mysql_insert_id();
		return $lastId;
    }
    
    /*
        @Description: Function is for Insert property details intot admin DB
        @Author     : Sanjay Moghariya
        @Input      : Property data and DB name
        @Output     : Insert property data
        @Date       : 03-03-2015
    */
    function insert_property_master($data,$db_name='')
    {
        if(!empty($db_name))
            $result =  $this->db->insert($db_name.'.nwmls_'.$this->table_name3,$data);
        else
            $result =  $this->db->insert($this->table_name3,$data);
		//echo $this->db->last_query();
        $lastId = mysql_insert_id();
        return $lastId;
    }
	/*
    @Description: Function is for Insert  property image details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record4($data,$db_name='')
    {
        if(!empty($db_name))
        {$result =  $this->db->insert($db_name.'.nwmls_'.$this->table_name4,$data);}
        else
        {$result =  $this->db->insert($this->table_name4,$data);}
		//echo $this->db->last_query();
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area community details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record5($data,$db_name='')
    {
        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.nwmls_'.$this->table_name5,$data);}
        else
        {$result =  $this->db->insert_batch($this->table_name5,$data);}	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert member details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record6($data,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.nwmls_'.$this->table_name6,$data);}
        else
        {$result =  $this->db->insert_batch($this->table_name6,$data);}
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert office details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record7($data,$db_name='')
    {

        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.nwmls_'.$this->table_name7,$data);}
        else
        {$result =  $this->db->insert_batch($this->table_name7,$data);}	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert school details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record8($data,$db_name='')
    {
        if(!empty($db_name))
        {$result =  $this->db->insert_batch($db_name.'.nwmls_'.$this->table_name8,$data);}
        else
        {$result =  $this->db->insert_batch($this->table_name8,$data);}
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
    function insert_common($table_name='',$data,$db_name='')
    {
        if(!empty($db_name))
        {$result =  $this->db->insert($db_name.'.'.$table_name,$data);}
        else
        {$result =  $this->db->insert($table_name,$data);}
      
        $lastId = mysql_insert_id();
        return $lastId;
    }

    /*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_common($table_name='',$data,$db_name='')
    {
        $this->db->where('id',$data['id']);
        if(!empty($db_name))
        {
            $query = $this->db->update($db_name.'.'.$table_name,$data); 
        }
        else
        {
            $query = $this->db->update($table_name,$data); 
        }
    }
    /*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record_table($table_name='',$data,$db_name='')
    {
        $this->db->where('mls_id',$data['mls_id']);
        if(!empty($db_name))
        {
            $query = $this->db->update($db_name.'.'.$table_name,$data); 
        }
        else
        {
            $query = $this->db->update($table_name,$data); 
        }
    }
    /*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_batch_record($data,$table_name,$update_id,$db_name='')
    {
        //$query = $this->db->update($this->table_name3,$data);
        if(!empty($db_name))
        {$query = $this->db->update_batch($db_name.'.'.$table_name,$data,$update_id);}
        else
        {$query = $this->db->update_batch($table_name,$data,$update_id);}
        
        //echo $this->db->last_query();
    }
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }

    public function update_record_mls_type($data)
    {
        $this->db->where('mls_id',$data['mls_id']);
        $query = $this->db->update($this->table_name,$data); 
    }

	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record1($data,$db_name='')
    {
        $this->db->where('property_type',$data['property_type']);
        $this->db->where('code',$data['code']);
		$this->db->where('value_code',$data['value_code']);
        if(!empty($db_name))
        {$query = $this->db->update($db_name.'.nwmls_'.$this->table_name1,$data);}
        else
        {$query = $this->db->update($this->table_name1,$data);}
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record2($data,$db_name='')
    {
        $this->db->where('area',$data['area']);
		$this->db->where('community',$data['community']);
        if(!empty($db_name))
        {$query = $this->db->update($db_name.'.nwmls_'.$this->table_name2,$data);}
        else
        {$query = $this->db->update($this->table_name2,$data);}
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record3($data,$db_name='')
    {
        //$query = $this->db->update($this->table_name3,$data);
        if(!empty($db_name))
		{$query = $this->db->update_batch($db_name.'.nwmls_'.$this->table_name3,$data,'LN');}
        else
        {$query = $this->db->update_batch($this->table_name3,$data,'LN');}
        
		//echo $this->db->last_query();
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record4($data,$db_name='')
    {
        $this->db->where('image_id',$data['image_id']);
        if(!empty($db_name))
        {$query = $this->db->update($db_name.'.nwmls_'.$this->table_name4,$data);}
        else
        {$query = $this->db->update($this->table_name4,$data);}
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record5($data,$db_name='')
    {
        //$this->db->where('ml_number',$data['ml_number']);
        if(!empty($db_name))
        {$query = $this->db->update_batch($db_name.'.nwmls_'.$this->table_name5,$data,'ml_number');}
        else
        {$query = $this->db->update_batch($this->table_name5,$data,'ml_number');} 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record6($data,$db_name='')
    {
        //$this->db->where('member_mls_id',$data['member_mls_id']);
        if(!empty($db_name))
        {$query = $this->db->update_batch($db_name.'.nwmls_'.$this->table_name6,$data,'member_mls_id');}
        else
        {$query = $this->db->update_batch($this->table_name6,$data,'member_mls_id');} 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record7($data,$db_name='')
    {
        //$this->db->where('office_mls_id',$data['office_mls_id']);
        if(!empty($db_name))
        {$query = $this->db->update_batch($db_name.'.nwmls_'.$this->table_name7,$data,'office_mls_id');}
        else
        {$query = $this->db->update_batch($this->table_name7,$data,'office_mls_id');}
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record8($data,$db_name='')
    {
        //$this->db->where('school_district_code',$data['school_district_code']);
       if(!empty($db_name))
        {$query = $this->db->update_batch($db_name.'.nwmls_'.$this->table_name8,$data,'school_district_code');}
        else
        {$query = $this->db->update_batch($this->table_name8,$data,'school_district_code');} 
    }
    /*
    @Description: Function for Delete mls Profile By Admin
    @Author: Niral Patel
    @Input: - mls id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_record($id)
    {
        $this->db->where('mls_id',$id);
        $this->db->delete($this->table_name);

        $this->db->where('id',$id);
        $this->db->delete($this->table_name_master);

        $this->db->where('mls_id',$id);
        $this->db->delete('mls_livewire_table_mapping');

        
        
    }
	public function delete_record3($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name3);
    }
	public function mls_delete_record($id='',$table='')
    {
        $this->db->where('id',$id);
        $this->db->delete($table);
    }
	/*
    @Description: Function for Delete mls property image By Admin
    @Author: Niral Patel
    @Input: - mls id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_record4($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name4);
    }
    /*
    @Description: Function for Delete mls property image By Admin
    @Author: Niral Patel
    @Input: - mls id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_mls_mapping_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_mls_mapping_trans);
    }
    public function delete_last_offset($id)
    {
        $this->db->where('table_type',$id);
        $this->db->delete('mls_last_updated_offset_data');
    }
    
    /*
    @Description: Function is for Insert contacts details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-07-2014
    */
    
    function insert_mls_csv($data)
    {
        $result =  $this->db->insert($this->table_name_mls_csv,$data);  
      
        $lastId = $this->db->insert_id();
        return $lastId;
    }
    function list_mls_fields($tb_name='')
    {
        $fields = $this->db->list_fields($tb_name);
        return $fields;
    }
    function add_new_field($field_name='',$field_type='',$field_size='',$field_comment='',$last_field='',$tbl_name='')
    {
        if(!empty($field_comment))
        {$field_comment="COMMENT '".$field_comment."'";}
        if($field_type == 'datetime')
        {
            $query="ALTER TABLE ".$tbl_name."  ADD ".$field_name." ".$field_type." NOT NULL ".$field_comment." AFTER ".$last_field."";
        }
        else
        {
            $query="ALTER TABLE ".$tbl_name."  ADD ".$field_name." ".$field_type."(".$field_size.") NOT NULL ".$field_comment." AFTER ".$last_field."";     
        }
        
        $this->db->query($query);
    }
    function insert_mls_mapping_record($data)
    {
        $result =  $this->db->insert($this->table_name_mls_csv_mapping_master,$data);   
      
        $lastId = $this->db->insert_id();
        return $lastId;
    }
    function select_csv_record($id)
    {
        
        $this->db->select('*');
        $this->db->from($this->table_name_mls_csv);
        $this->db->where('id',$id);
        $result = $this->db->get()->result_array();
        return $result;
        
    }
    function insert_mls_mapping_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_mls_mapping_trans,$data);    
      
        $lastId = $this->db->insert_id();
        return $lastId;
    }
    function update_mls_mapping_trans_record($data)
    {
        $this->db->where('mls_id',$data['mls_id']);
        $this->db->where('mls_master_field',$data['mls_master_field']);
        $result =  $this->db->update($this->table_name_mls_mapping_trans,$data);    
      
        $lastId = $this->db->insert_id();
        return $lastId;
    }
    function get_field($table_name='')
    {
        if(!empty($table_name))
        {
            $query="SHOW FULL COLUMNS FROM ".$table_name;
        }
        else
        {
            $query="SHOW FULL COLUMNS FROM ".$this->table_name3;
        }
        //$query="ALTER TABLE ".$this->table_name3."  ADD ".$field_name." ".$field_type."(".$field_size.") NOT NULL AFTER ".$last_field."";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function insert_cron_test($data)
    {
        $result =  $this->db->insert('cron_test',$data);    
      
        $lastId = mysql_insert_id();
        return $lastId;
    }

    public function update_cron_test($data,$table)
    {
       $this->db->where('id',$data['id']);
       $query = $this->db->update($table,$data); 
    }

    public function insert_cron_image_test($data)
    {
        $result =  $this->db->insert('cron_image_test',$data);    
      
        $lastId = mysql_insert_id();
        return $lastId;
    }

    public function update_cron_image_test($data,$table)
    {
       $this->db->where('id',$data['id']);
       $query = $this->db->update($table,$data); 
    }

    public function rename_tablename($db_name='',$old_table_name='',$new_table_name='')
    {
        $query = 'RENAME TABLE '.$db_name.'.'.$old_table_name.' TO '.$db_name.'.'.$new_table_name;
        $this->db->query($query);
    }  

    public function get_image_counter($cron_id = '')
    {
        if(!empty($cron_id))
        {
            $this->db->select('*');
            $this->db->from('cron_image_counter');
            $this->db->where('cron_id',$cron_id);
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function get_image_counter_remaining($start_cron_id = '',$end_cron_id = '')
    {
        if(!empty($start_cron_id))
        {
            $this->db->select('*');
            $this->db->from('cron_test');
            $this->db->where('cron_name','retrieve_image_data');
            $this->db->where('completed_date IS NULL');
            $this->db->where('p_type != ','star');
            $this->db->where('id > ',$start_cron_id);
            $this->db->where('id < ',$end_cron_id);
            $this->db->order_by('id','ASC');
            $this->db->limit(1);
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function update_image_counter($cron_id,$data)
    {
       if(!empty($cron_id))
       {
           $this->db->where('cron_id',$cron_id);
           $query = $this->db->update('cron_image_counter',$data); 
        }
    }
}